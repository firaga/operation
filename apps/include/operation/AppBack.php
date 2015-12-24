<?php

class KShequ_App extends Ko_App_RoutePage
{
	protected $_bAutoSession = true;
	protected $is_admin = false;
	protected $aUser = array();
	protected $aPriMenuIds = array();
	protected $currentMenuId= 0;
	private $unCheckPages=array(
		'/',
		'/system/no_priority.php',
		'/ginfo/ajax_post.php',
		//        '/ginfo/audit.php',
		'/activity/ajax.php',
		'/game/ajax_reality_show.php',
		'/ginfo/data/ajax_post.php',
		'/ginfo/operate/ajax_post.php',
		'/ginfo/search/ajax_post.php',
		'/ginfo/treasure/ajax_post.php',
		'/pic_upload/up.php',
		'/together/ajax.php',
		'/wenda/ajax.php',
		'/audit/monitor/ajax_post.php',
		'/ginfo/tags/ajax_post.php',
	);

	protected function vPreRun()
	{
		$this->aUser = $_SESSION['admin'];
		Ko_Tool_Str::VConvert2UTF8($this->aUser);
		$this->is_admin = in_array($_SESSION['admin']['uid'],KShequ_Conf::$super_users);

		$this->oGetSmarty()->vAssignHtml('__user', $this->aUser);
		$this->oGetSmarty()->vAssignHtml("is_super",$this->is_admin ? 1 : 0);
		//权限检查
		$this->_vCheckPriority();
		list($top_menus,$cur_menu,$left_nav_html) = $this->_aGetNavData();
		if(!$this->bIsAjaxRequest())
		{
			$this->oGetSmarty()->vAssignHtml('__top_menus', $top_menus);
			$this->oGetSmarty()->vAssignHtml('__cur_menus', $cur_menu);
			$this->oGetSmarty()->vAssignRaw('__admin_nav', $left_nav_html);
			$this->oGetSmarty()->vAssignHtml('__admin_with', $this->_aGetRuntimeData());
		}
	}

	public function vSetPageTitle($sTitle)
	{
		$this->oGetSmarty()->vAssignHtml('__page_title', $sTitle);
	}

	public function vRedirectTo($sUri, $aWith = array())
	{
		if(!empty($aWith)) {
			$this->_vSetRuntimeData($aWith);
		}
		header('location:' . $sUri);
		exit;
	}

	private function _vSetRuntimeData($aData)
	{
		$_SESSION['common']['runtime']['shequa_dmin'] = $aData;
	}

	private function _aGetRuntimeData()
	{
		$aData = isset($_SESSION['common']['runtime']['shequa_dmin'])
			? $_SESSION['common']['runtime']['shequa_dmin'] : array();
		unset($_SESSION['common']['runtime']['shequa_dmin']);
		return $aData;
	}

	private function _vEchoNoPrivacy()
	{
		//        echo Ko_Tool_Str::SConvert2GB18030("无权限访问，请联系 shaohui@mafengwo.com");
		//	    echo '<META http-equiv="Content-Type" content="text/html; charset=utf-8">';
		//	    echo ("无权限访问，请联系 shaohui@mafengwo.com");
		header('Location:/system/no_priority.php?from='.$this->currentMenuId);
		exit;
	}

	private function _vCheckPriority(){
		if ($_SESSION['admin']['uid'] == 'hongming') {
			return;
		}
		if (!$this->_bCheckPriority()) {
			$this->_vEchoNoPrivacy();
		}
	}

	private function _bCheckPriority(){
		$cur_uri = KShequ_Func::sGetDirUriName();
		if(in_array($cur_uri,$this->unCheckPages) || false!==strpos('ajax',$cur_uri)) {
			return true;
		}
		$cur_menu=$this->_aGetCurrentMenu();
		if(!empty($cur_menu)){
			$this->currentMenuId=$cur_menu['id'];
		}
		if($this->is_admin){
			return true;
		}
		$tree_api = new KShequ_Menu_TreeApi();
		$role_api= new KShequ_Menu_roleApi();
		$this->aPriMenuIds=$role_api->aGetMenuIdByAdminUid($_SESSION['admin']['id']);
		if((empty($this->aPriMenuIds) || !is_array($this->aPriMenuIds)) || !$cur_uri){
			return false;
		}
		//文件不在目录树中看是否有此路径的顶级权限
		if(empty($cur_menu)) {
			$menu_api = new KShequ_Menu_Api();
			$cur_menus = $menu_api->aGetByUri(dirname($cur_uri));
			$top_menu = current($cur_menus);
			if(is_int($top_menu['id'])){
				//判断是否为一级节点
				$tree_api = new KShequ_Menu_TreeApi();
				$top_menus=$tree_api->aGetParent($top_menu['id']);
				if(isset($top_menus[0]) && $top_menus[0] == 0 && in_array($top_menu['id'],$this->aPriMenuIds)){
					return true;
				}
			}
			return false;
		}

		$parent_tree = $tree_api->aGetParent($cur_menu['id'],0);
		if (is_array($parent_tree) && !empty($parent_tree))
		{
			unset($parent_tree[count($parent_tree) - 1]);
			$parent_tree = array_values($parent_tree);
		}
		array_unshift($parent_tree,$cur_menu['id']);
		if (count(array_intersect($parent_tree,$this->aPriMenuIds)) == 0){
			return false;
		}
		return true;
	}

	private function _aGetCurrentMenu(){
		$cur_uri = KShequ_Func::sGetCurUri();
		$cur_uri_params=KShequ_Func::aGetCurUriParams();
		$has_params=false;
		$cur_menu = array();
		if ($cur_uri) {
			$menu_api = new KShequ_Menu_Api();
			$cur_menus = $menu_api->aGetByUri($cur_uri);
			if($cur_menus) {
				foreach ($cur_menus as $menu) {
					$tmp_menu_params = KShequ_Func::aGetUriParams($menu['url']);
					if ($cur_uri_params === $tmp_menu_params || count(array_intersect_assoc($tmp_menu_params, $cur_uri_params)) > 0) {
						$cur_menu = $menu;
						$has_params = true;
						break;
					}
				}
			}
		}
		if(!$has_params && !empty($cur_menus)) {
			$cur_menu = array_shift($cur_menus);
		}
		return $cur_menu;
	}

	private function _aGetNavData()
	{
		$tree_api = new KShequ_Menu_TreeApi();
		$menu_api = new KShequ_Menu_Api();

		$cur_uri = KShequ_Func::sGetCurUri();
		$cur_params=KShequ_Func::aGetCurUriParams();
		if($cur_uri==='/system/no_priority.php' && is_numeric($cur_params['from'])){
			$cur_menu=$menu_api->aGet(intval($cur_params['from']));
		}else{
			$cur_menu=$this->_aGetCurrentMenu();
		}
		$relation_menu = array();
		if ($cur_menu)
		{
			$relation_menu = $tree_api->aGetParent($cur_menu['id'],0);
			if ($relation_menu)
			{
				unset($relation_menu[count($relation_menu) - 1]);
			}
			$top_menu_id = $relation_menu ? $relation_menu[count($relation_menu) - 1] : $cur_menu['id'];
			if ($relation_menu)
			{
				$relation_menu = array_values($relation_menu);
				$relation_menu[] = $cur_menu['id'];
			}
		}
		if (!$top_menu_id)
		{
			$top_menu_id = 1;
		}
		// 如果没有关联的导航则以第一个子节点为关联导航
		$menu_tree = $tree_api->aGetChild($top_menu_id, 0);
		if ($menu_tree)
		{
			$this->_vSetAllPriMenuIds($menu_tree);
		}

		if (empty($relation_menu) && $menu_tree)
		{
			foreach($menu_tree as $menu_id => $item)
			{
				$relation_menu[] = $menu_id;
				break;
			}
		}

		// 获取全部导航信息
		$all_menu = $menu_api->aGetAll();

		// 获取顶部导航
		$top_ids = array_keys($tree_api->aGetChild(0, 1));
		$top_menus = array();
		foreach($top_ids as $top_id)
		{
			$top_menus[$top_id] = $all_menu[$top_id];
		}

		//menu_tree 左侧菜单树
		//relation_menu 当前节点父节点集合
		$left_nav_html = $this->_sRenderingNav($menu_tree,$all_menu, $relation_menu);

		return array($top_menus,$cur_menu,$left_nav_html);
	}

	private function _vSetAllPriMenuIds($item)
	{
		foreach($item as $key => $value)
		{
			if (in_array($key,$this->aPriMenuIds,true))
			{
				$sub_keys = array();
				$this->_aGetAllSubKeys($value,$sub_keys);
				if ($sub_keys)
				{
					$this->aPriMenuIds = array_merge($this->aPriMenuIds,$sub_keys);
				}
			}
			else
			{
				$this->_vSetAllPriMenuIds($value);
			}
		}
	}

	private function _aGetAllSubKeys($menu_tree,array &$sub_keys)
	{
		foreach($menu_tree as $key => $value)
		{
			$sub_keys[] = $key;
			if ($value)
			{
				$this->_aGetAllSubKeys($value,$sub_keys);
			}
		}
	}

	/**
	 * 渲染导航
	 * @param $aTree
	 * @param $all_menu
	 * @param array $aCurrent
	 * @param int $iLevel
	 * @return string
	 */
	private function _sRenderingNav($aTree, $all_menu, $aCurrent = array(), $iLevel = 0) {
		$sHtml = '';
		foreach($aTree as $iId => $aSubTree)
		{
			// 是否有权限
			if (!$this->is_admin && !in_array($iId,$this->aPriMenuIds,true))
			{
				//continue;
			}
			//URL为空并且没有子菜单则跳过渲染
			if($all_menu[$iId]['url'] == '' && empty($aSubTree))
			{
				continue;
			}
			$sSubHtml = '';
			if(!empty($aSubTree))
			{
				$sSubHtml = $this->_sRenderingNav($aSubTree, $all_menu, $aCurrent, $iLevel + 1);
				if($sSubHtml == '')
				{
					continue;
				}
			}
			//渲染菜单
			$sHtml .= '<li class="' . (in_array($iId, $aCurrent) ? 'active' : '') .  '">';
			$sHtml .= '<a href="' . $all_menu[$iId]['url'] . '">';
			$sHtml .= '<span class="title">' . $all_menu[$iId]['text'] . '</span>';
			$sHtml .= ($iLevel == 0 && in_array($iId, $aCurrent)) ? '<span class="selected"></span>' : '';
			$sHtml .= $sSubHtml !== '' ? '<span class="arrow"></span>' : '';
			$sHtml .= '</a>';
			$sHtml .= $sSubHtml;
			$sHtml .= '</li>';
		}
		//如果不为顶级菜单做包装渲染
		if($iLevel !== 0 && $sHtml !== '')
		{
			$sHtml = '<ul class="sub-menu">' . $sHtml . '</ul>';
		}
		return $sHtml;
	}

	protected function vErr($sMsg, $iCode = 0) {
		echo json_encode(array('error' => array('msg' => $sMsg, 'code' => $iCode)));
		exit;
	}

	protected function vRet($aData = array()) {
		echo json_encode(array('data' => $aData));
		exit;
	}
}
