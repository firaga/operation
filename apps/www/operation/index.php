<?php

$msg = 'hello world!';
$render = new KRender_default;
$render->oSetTemplate('operation/index.html')
	->oSetData('msg', $msg)
	->oSend();
//var_dump(debug_backtrace());
echo "index";
var_dump($_SESSION);
//$userApi=new KUser_userApi();
//$res=$userApi->checkLogin();
