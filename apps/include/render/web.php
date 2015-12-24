<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月23日
 * Time: 下午3:22
 */
class KRender_web extends KRender_base {
	public function sRender() {
		$uid = $_SESSION['admin']['id'];
		$userApi = new KUser_userApi();
		$logininfo = $userApi->getUser($uid);
		$app = new KOperation_App();

		$head = new Ko_View_Smarty();
		$head->vAssignHtml('IMG_DOMAIN', IMG_DOMAIN);
		$head->vAssignHtml('WWW_DOMAIN', WWW_DOMAIN);
		$head->vAssignHtml('PASSPORT_DOMAIN', PASSPORT_DOMAIN);
		$head->vAssignHtml('logininfo', $logininfo);

		list($top_menus, $cur_menu, $left_nav_html) = $app->aGetNavData();
		$head->vAssignHtml('__top_menus', $top_menus);
		$head->vAssignHtml('__cur_menus', $cur_menu);
		$head->vAssignRaw('__admin_nav', $left_nav_html);
		$head->vAssignRaw('is_super', !!in_array($logininfo['id'], KOperation_Conf::$super_users));

		$headHtml = $head->sFetch('operation/common/header.html');
		//		$head = new Ko_View_Render_Smarty;
		//		$head->oSetTemplate('operation/common/header.html')
		//			->oSetData('IMG_DOMAIN', IMG_DOMAIN)
		//			->oSetData('WWW_DOMAIN', WWW_DOMAIN)
		//			->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN)
		//			->oSetData('logininfo', $logininfo);

		$tail = new Ko_View_Render_Smarty;
		$tail->oSetTemplate('operation/common/footer.html')
			->oSetData('IMG_DOMAIN', IMG_DOMAIN)
			->oSetData('WWW_DOMAIN', WWW_DOMAIN)
			->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN);

		return $headHtml . parent::sRender() . $tail->sRender();
	}
}
