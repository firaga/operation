<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月24日
 * Time: 上午10:59
 */
Ko_Web_Route::VGet('index', function () {
	$msg = 'hello world';
	$render = new KRender_web;
	$render->oSetTemplate('operation/menu/list.tpl')
		->oSetData('msg', $msg)
		->oSend();
});
Ko_Web_Route::VGet('getTree', function () {
	$oApi = new KOperation_Menu_Api();
	$oTreeApi = new KOperation_Menu_TreeApi();

	$aList = $oApi->aGetAll();
	$aTree = $oTreeApi->aGetChild(0, 0);

	$sMenuTree = _getMenuTree($aTree, $aList);

	echo $sMenuTree;
	exit;
});
Ko_Web_Route::VPost('del', function () {
	$iId = Ko_Web_Request::IInput("id");

	$oApi = new KOperation_Menu_Api();

	$iRet = $oApi->iDelete($iId);

	KAdmin_Helper::vOutput($iRet);
});
Ko_Web_Route::VGet('edit', function () {
	$iId = Ko_Web_Request::IInput("id");
	$iParentId = Ko_Web_Request::IInput("parentid");
	$oApi = new KOperation_Menu_Api();
	$oTreeApi = new KOperation_Menu_TreeApi();

	$aList = $oApi->aGetAll();
	$aTree = $oTreeApi->aGetChild(0, 0);

	if ($iId) {
		$aParent = $oTreeApi->aGetParent($iId, 1);
		$iParentId = empty($aParent) ? 0 : $aParent[0];
	}

	$sMenuOptions = '<option value="0">顶级导航</option>';
	$sMenuOptions .= $this->_getMenuOptions($iParentId, $aTree, $aList);

	$this->oGetSmarty()->vAssignHtml(array(
		'title'   => '编辑导航',
		'info'    => $iId ? $aList[$iId] : array(),
		'options' => $sMenuOptions,
	), null, array('options'));
	echo $this->oGetSmarty()->sFetch('system/menu/edit.tpl');
	exit;
});
Ko_Web_Route::VPost('edit', function () {
	$sText = Ko_Web_Request::SInput('text');
	$sUrl = Ko_Web_Request::SInput('url');
	$iParentId = Ko_Web_Request::IInput("parentid");
	$iMode = Ko_Web_Request::IInput("mode");
	$iId = Ko_Web_Request::IInput("id");
	$oApi = new KOperation_Menu_Api();
	if (!in_array($_SESSION['admin']['uid'], KShequ_Conf::$super_users)) {
		KAdmin_Helper::vOutput(0);
	} else {
		KAdmin_Helper::vOutput(
			$iId ? $oApi->iUpdate($iId, $sText, $sUrl, $iParentId, $iMode)
				: $oApi->iCreate($sText, $sUrl, $iParentId, $iMode)
		);
	}
});
Ko_Web_Route::VGet('privacy', function () {
	$iId = Ko_Web_Request::IInput("id");
	$oApi = new KOperation_Menu_Api();
	$oTreeApi = new KOperation_Menu_TreeApi();
	$oPriApi = new KShequ_Menu_PrivacyApi();
	$aList = $oApi->aGetAll();
	$aMenu = $aList[$iId];

	$aParent = $oTreeApi->aGetParent($iId, 0);
	unset($aParent[count($aParent) - 1]);
	array_unshift($aParent, $iId);
	$pri_list = $oPriApi->aGetPrivacyGroupByMenuId($aParent);

	$tree_list = array();
	$aParent = array_reverse($aParent);
	$split = 0;
	foreach ($aParent as $menu_id) {
		$menu = $aList[$menu_id];
		$menu['pri'] = isset($pri_list[$menu_id]) ? $pri_list[$menu_id] : array();
		$html = $this->_getMenuHtml($menu, $split);
		$tree_list[] = $html;
		$split++;
	}

	$this->oGetSmarty()->vAssignHtml(array(
		'title'     => '权限管理',
		'info'      => $aMenu,
		'tree_list' => implode("\n", $tree_list),
	), null, array('tree_list'));
	echo $this->oGetSmarty()->sFetch('system/menu/privacy.tpl');
	exit;
});
Ko_Web_Route::VPost('privacy', function () {
	$oPriApi = new KShequ_Menu_PrivacyApi();
	$oPriApi->bAddMenuPri($_POST['admin_uid'], $_POST['menu_id']);
	echo 1;
	exit;
});
Ko_Web_Route::VPost('delpri', function () {
	$oPriApi = new KShequ_Menu_PrivacyApi();
	$oPriApi->vDeleteOneMenuPri($_POST['admin_uid'], $_POST['menu_id']);
	echo 1;
	exit;
});
Ko_Web_Route::VGet('suggest', function () {
	$sName = Ko_Web_Request::SInput("name");
	$suggest_result = KDuizhang_Tool::suggest($sName, "admin_user");
	Ko_Tool_Str::VConvert2UTF8($suggest_result);

	echo json_encode(array('ret' => 1, 'msg' => $suggest_result));
	exit;
});
Ko_Web_Route::VGet('suggestmenu', function () {
	$sName = Ko_Web_Request::SInput("name");
	$sName = Ko_Tool_Str::SConvert2GB18030(trim($sName));
	$suggest_result = KDuizhang_Tool::suggest($sName, "sq_menu", 'id', 'text');
	Ko_Tool_Str::VConvert2UTF8($suggest_result);

	$oApi = new KOperation_Menu_Api();
	$tree_api = new KOperation_Menu_TreeApi();
	$aList = $oApi->aGetAll();
	$result = array();
	if (!empty($suggest_result)) {
		foreach ($suggest_result as $v) {
			$relations = $tree_api->aGetParent($v['id'], 0);
			unset($relations[count($relations) - 1]);
			array_unshift($relations, $v['id']);
			if (!empty($relations)) {
				$relations = array_reverse($relations);
				$msg = '';
				foreach ($relations as $menu_id) {
					$msg .= $aList[$menu_id]['text'] . '=>';
				}
				$msg = trim($msg, '=>');
				$result[] = array('id' => $v['id'], 'value' => $msg);
			}
		}
	}
	echo json_encode(array('ret' => 1, 'msg' => $result));
	exit;
});

function _getMenuHtml($menu, $split) {
	$sHtml = '<ol class="dd-list">';
	$sHtml .= '<li class="dd-item" data-id="' . $menu['id'] . '">';
	$sHtml .= '<div class="dd-content">';
	$sHtml .= '<span class="dd-view">';
	if ($split) {
		$sHtml .= "|";
	}
	for ($i = 1; $i <= $split; $i++) {
		$sHtml .= "--";
	}
	$sHtml .= $menu['text'] . '</span>';
	if ($menu['pri']) {
		foreach ($menu['pri'] as $v2) {
			$sHtml .= '<span class="dd-op" style="display:block;">';
			$sHtml .= '<a class="_j_del_user" href="#" title="点击删除" data-uid="' . $v2['id'] . '">';
			$sHtml .= $v2['name'] . '</a></span>';
		}
	}
	$sHtml .= '</div>';

	$sHtml .= '</li>';
	$sHtml .= '</ol>';
	return $sHtml;
}

function _getMenuTree($aTree, $aList, $iLevel = 0) {
	$is_dev = true;
	$sHTML = '<ol class="dd-list">';
	foreach ($aTree as $iId => $aSubTree) {
		$sHTML .= '<li class="dd-item" data-id="' . $iId . '">';
		$sHTML .= '<div class="dd-content">';
		$sHTML .= '<span class="dd-view">' . $aList[$iId]['text'] . '</span>';
		$sHTML .= '<span class="dd-op">';
		if ($is_dev) {
			$iLevel + 1 < KOperation_Menu_TreeApi::MAX_DEPTH && $sHTML .= '<a class="dd-add" href="#">新增</a>';
			$sHTML .= '<a class="dd-edit" href="#" data-toggle="modal">编辑</a>';
			$sHTML .= '<a class="dd-delete" href="#">删除</a>';
			$sHTML .= '<a class="dd-privacy" href="#">权限</a>';
		}
		$sHTML .= '</span>';
		$sHTML .= '</div>';
		$sHTML .= !empty($aSubTree) ? _getMenuTree($aSubTree, $aList, $iLevel + 1) : '';
		$sHTML .= '</li>';
	}
	$sHTML .= '</ol>';
	return $sHTML;
}

function _getMenuOptions($iCurrentId, $aTree, $aList, $sPrefix = '', $iLevel = 0) {
	$sHTML = '';
	$iCounter = 0;
	foreach ($aTree as $iId => $aSubTree) {
		if ($iLevel + 1 == KOperation_Menu_TreeApi::MAX_DEPTH) {
			continue;
		}
		$iCounter++;
		$sHTML .= '<option value="' . htmlspecialchars($iId) . '"'
			. ($iCurrentId == $iId ? ' selected="true"' : '') . '>';
		$sCurrentPrefix = $sPrefix . $iCounter . '.';
		$sHTML .= $sCurrentPrefix . '&nbsp;' . htmlspecialchars($aList[$iId]['text']) . '</option>';
		$sHTML .= _getMenuOptions($iCurrentId, $aSubTree, $aList, $sCurrentPrefix, $iLevel + 1);
	}
	return $sHTML;
}