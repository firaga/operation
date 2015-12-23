<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月23日
 * Time: 下午3:22
 */
class KRender_web extends KRender_base
{
	public function sRender()
	{
		$loginApi = new KUser_loginApi;
		$uid = $loginApi->iGetLoginUid();
		$userApi=new KUser_userApi();
		$logininfo=$userApi->getUser($uid);

		$head = new Ko_View_Render_Smarty;
		$head->oSetTemplate('operation/common/header.html')
			->oSetData('IMG_DOMAIN', IMG_DOMAIN)
			->oSetData('WWW_DOMAIN', WWW_DOMAIN)
			->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN)
			->oSetData('logininfo', $logininfo);

		$tail = new Ko_View_Render_Smarty;
		$tail->oSetTemplate('operation/common/footer.html');

		return $head->sRender().parent::sRender().$tail->sRender();
	}
}
