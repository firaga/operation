<?php
Ko_Web_Route::VGet('index', function () {
	$msg = 'hello world!';
	$render = new KRender_web;
	$render->oSetTemplate('operation/index.html')
//		->oSetData('msg', $msg)
		->oSend();
	//var_dump(debug_backtrace());
	//$userApi=new KUser_userApi();
	//$res=$userApi->checkLogin();
});
