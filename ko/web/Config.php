<?php
/**
 * Config
 *
 * @package ko/Web
 * @author zhangchu
 */

if (!defined('KO_CONFIG_SITE_INI'))
{
	define('KO_CONFIG_SITE_INI', '');
}
if (!defined('KO_CONFIG_SITE_CACHE'))
{
	define('KO_CONFIG_SITE_CACHE', '');
}

/**
 * 加载web域名等配置
 */
class Ko_Web_Config
{
	private static $s_sConfFile = KO_CONFIG_SITE_INI;
	private static $s_sCacheFile = KO_CONFIG_SITE_CACHE;
	private static $s_aConfig = array();
	private static $s_aHostConfig = array();

	private $_sAppName = '';
	private $_sDocumentRoot = '';
	private $_sRewriteConf = '';
	private $_sRewriteCache = '';

	public static function VSetConf($sConfFile, $sCacheFile = '')
	{
		self::$s_sConfFile = $sConfFile;
		self::$s_sCacheFile = $sCacheFile;
	}

	public static function VLoad()
	{
		if (is_file(self::$s_sConfFile)) {
			if ('' === self::$s_sCacheFile) {
				self::$s_aConfig = parse_ini_file(self::$s_sConfFile, true);
			} else {
				$cacheDir = dirname(self::$s_sCacheFile);
				if (!is_dir($cacheDir)) {
					mkdir($cacheDir, 0777, true);
					if (!is_dir($cacheDir)) {
						self::$s_aConfig = parse_ini_file(self::$s_sConfFile, true);
						return;
					}
				}
				if (!is_file(self::$s_sCacheFile) || filemtime(self::$s_sConfFile) > filemtime(self::$s_sCacheFile)) {
					self::$s_aConfig = parse_ini_file(self::$s_sConfFile, true);
					$script = "<?php\nKo_Web_Config::VLoadConfig("
						. var_export(self::$s_aConfig, true)
						. ");\n";
					file_put_contents(self::$s_sCacheFile, $script);
				} else {
					require_once(self::$s_sCacheFile);
				}
			}
		}
	}

	public static function VLoadConfig($aConfig)
	{
		self::$s_aConfig = $aConfig;
	}

	public static function SGetAppName($host = null)
	{
		return self::_OGetConfig($host)->_sAppName;
	}

	public static function SGetDocumentRoot($host = null)
	{
		return self::_OGetConfig($host)->_sDocumentRoot;
	}

	public static function SGetRewriteConf($host = null)
	{
		return self::_OGetConfig($host)->_sRewriteConf;
	}

	public static function SGetRewriteCache($host = null)
	{
		return self::_OGetConfig($host)->_sRewriteCache;
	}

	/**
	 * @return self
	 */
	private static function _OGetConfig($host)
	{
		if (is_null($host)) {
			$host = Ko_Web_Request::SHttpHost();
		}
		if (!isset(self::$s_aHostConfig[$host])) {
			self::$s_aHostConfig[$host] = new self;
			if (isset(self::$s_aConfig['global'][$host])) {
				$appname = self::$s_aConfig['global'][$host];
				self::$s_aHostConfig[$host]->_sAppName = $appname;
				if (isset(self::$s_aConfig['app_' . $appname])) {
					self::$s_aHostConfig[$host]->_sDocumentRoot = strval(self::$s_aConfig['app_' . $appname]['documentroot']);
					self::$s_aHostConfig[$host]->_sRewriteConf = strval(self::$s_aConfig['app_' . $appname]['rewriteconf']);
					self::$s_aHostConfig[$host]->_sRewriteCache = strval(self::$s_aConfig['app_' . $appname]['rewritecache']);
				}
			}
		}
		return self::$s_aHostConfig[$host];
	}
}
