<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月22日
 * Time: 下午5:31
 */
Ko_Web_Event::On ('ko.dispatch', 'before', function () {
	$uri = Ko_Web_Request::SRequestUri();
	$script = Ko_Web_Request::SScriptName();
	$userApi=new KUser_userApi();
	if($uri=='/user/login/index'){
		echo "hook logout".chr(10);
//		$userApi->logoutAdminAuth();
	}elseif($uri=='/rest/user/login/'){

	}else{
		if(!$userApi->checkLogin()){
			$url='http://'.WWW_DOMAIN.'/user/login/index';
			header('Location:'.$url);
			exit;
		}
	}
});
