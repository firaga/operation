<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月24日
 * Time: 下午6:20
 */
Ko_Web_Route::VGet('index', function () {
	$msg = 'hello world';
	$render = new KRender_web;
	$render->oSetTemplate('operation/menu/list.tpl')
		->oSetData('msg', $msg)
		->oSend();
});
