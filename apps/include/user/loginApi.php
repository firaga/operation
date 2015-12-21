<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月14日
 * Time: 下午6:22
 */
class KUser_loginApi extends Ko_Mode_Item {
	protected $_aConf = array(
		'item' => 'user',
	);
	const ERRNO_UNREGISTERED = 1;
	const ERRNO_PASSWD = 2;
	const ERRNO_REGISTERED = 3;

	public function iLogin($sUsername, $sPasswd, &$iErrNo = 0) {
		if (!$aUserInfo = $this->aIsRegistered($sUsername)) {
			$iErrNo = self::ERRNO_UNREGISTERED;
			return 0;
		}
		if (md5($aUserInfo['salt'] . '_' . $sPasswd) != $aUserInfo['passwd']) {
			$iErrNo = self::ERRNO_PASSWD;
			return 0;
		}
		//setcookie
		setcookie('uid',$aUserInfo['id']);
		return $aUserInfo['id'];
	}

	public function aIsRegistered($sUsername) {
		$aOption = new Ko_Tool_SQL();
		$aOption->oWhere('username=?',$sUsername);
		$aOption->oOffset(0)->oLimit(1);
		$aList = $this->aGetList($aOption);
		return $aList[0];
	}

	public function iRegister($sUsername, $sPasswd, &$iErrNo = 0) {
		if ($aUserInfo = $this->aIsRegistered($sUsername)) {
			$iErrNo = self::ERRNO_REGISTERED;
			return 0;
		}
		$sSalt = rand(1000, 9999);
		$aData = array(
			'username' => $sUsername,
			'passwd'   => md5($sSalt . '_' . $sPasswd),
			'salt'     => $sSalt,
		);
		var_dump($aData);
		$iUid = $this->iInsert($aData);
		var_dump($iUid);
		return $iUid;
	}

	public function iGetLoginUid() {
		return $_COOKIE['uid'];

	}
}
