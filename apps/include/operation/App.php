<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月23日
 * Time: 下午5:06
 */
class KOperation_App{

	protected $aPriMenuIds = array();
	private function _aGetCurrentMenu() {
		$cur_uri = KOperation_Func::sGetCurUri();
		$cur_uri_params = KOperation_Func::aGetCurUriParams();
		$has_params = false;
		$cur_menu = array();
		if ($cur_uri) {
			$menu_api = new KOperation_Menu_Api();
			$cur_menus = $menu_api->aGetByUri($cur_uri);
			if ($cur_menus) {
				foreach ($cur_menus as $menu) {
					$tmp_menu_params = KOperation_Func::aGetUriParams($menu['url']);
					if ($cur_uri_params === $tmp_menu_params || count(array_intersect_assoc($tmp_menu_params, $cur_uri_params)) > 0) {
						$cur_menu = $menu;
						$has_params = true;
						break;
					}
				}
			}
		}
		if (!$has_params && !empty($cur_menus)) {
			$cur_menu = array_shift($cur_menus);
		}
		return $cur_menu;
	}

	public function aGetNavData() {
		$tree_api = new KOperation_Menu_TreeApi();
		$menu_api = new KOperation_Menu_Api();

		$cur_menu = $this->_aGetCurrentMenu();

		$relation_menu = array();
		if ($cur_menu) {
			$relation_menu = $tree_api->aGetParent($cur_menu['id'], 0);
			if ($relation_menu) {
				unset($relation_menu[count($relation_menu) - 1]);
			}
			$top_menu_id = $relation_menu ? $relation_menu[count($relation_menu) - 1] : $cur_menu['id'];
			if ($relation_menu) {
				$relation_menu = array_values($relation_menu);
				$relation_menu[] = $cur_menu['id'];
			}
		}
		if (!$top_menu_id) {
			$top_menu_id = 1;
		}
		// 如果没有关联的导航则以第一个子节点为关联导航
		$menu_tree = $tree_api->aGetChild($top_menu_id, 0);
		if ($menu_tree) {
			$this->_vSetAllPriMenuIds($menu_tree);
		}
		// 获取全部导航信息
		$all_menu = $menu_api->aGetAll();

		// 获取顶部导航
		$top_ids = array_keys($tree_api->aGetChild(0, 1));
		$top_menus = array();
		foreach ($top_ids as $top_id) {
			$top_menus[$top_id] = $all_menu[$top_id];
		}

		//menu_tree 左侧菜单树
		//relation_menu 当前节点父节点集合
		$left_nav_html = $this->_sRenderingNav($menu_tree, $all_menu, $relation_menu);

		//顶层导航a,当前节点a,左侧菜单html
		return array($top_menus, $cur_menu, $left_nav_html);
	}

	private function _vSetAllPriMenuIds($item) {
		foreach ($item as $key => $value) {
			if (in_array($key, $this->aPriMenuIds, true)) {
				$sub_keys = array();
				$this->_aGetAllSubKeys($value, $sub_keys);
				if ($sub_keys) {
					$this->aPriMenuIds = array_merge($this->aPriMenuIds, $sub_keys);
				}
			} else {
				$this->_vSetAllPriMenuIds($value);
			}
		}
	}

	private function _aGetAllSubKeys($menu_tree, array &$sub_keys) {
		foreach ($menu_tree as $key => $value) {
			$sub_keys[] = $key;
			if ($value) {
				$this->_aGetAllSubKeys($value, $sub_keys);
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
	//$aTree 左侧菜单树
	//$all_menu 全部菜单
	//$aCurrent 当前节点父节点集合,包括自己,
	private function _sRenderingNav($aTree, $all_menu, $aCurrent = array(), $iLevel = 0) {
		$sHtml = '';
		foreach ($aTree as $iId => $aSubTree) {
			// 是否有权限
			if (!$this->is_admin && !in_array($iId, $this->aPriMenuIds, true)) {
				//continue;
			}
			//URL为空并且没有子菜单则跳过渲染
			if ($all_menu[$iId]['url'] == '' && empty($aSubTree)) {
				continue;
			}
			$sSubHtml = '';
			if (!empty($aSubTree)) {
				$sSubHtml = $this->_sRenderingNav($aSubTree, $all_menu, $aCurrent, $iLevel + 1);
				if ($sSubHtml == '') {
					continue;
				}
			}
			//渲染菜单
			$sHtml .= '<li class="' . (in_array($iId, $aCurrent) ? 'active' : '') . '">';
			$sHtml .= '<a href="' . $all_menu[$iId]['url'] . '">';
			$sHtml .= '<span class="title">' . $all_menu[$iId]['text'] . '</span>';
			$sHtml .= ($iLevel == 0 && in_array($iId, $aCurrent)) ? '<span class="selected"></span>' : '';
			$sHtml .= $sSubHtml !== '' ? '<span class="arrow"></span>' : '';
			$sHtml .= '</a>';
			$sHtml .= $sSubHtml;
			$sHtml .= '</li>';
		}
		//如果不为顶级菜单做包装渲染
		if ($iLevel !== 0 && $sHtml !== '') {
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
