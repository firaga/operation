<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月3日
 * Time: 下午3:16
 */
//角色列表
Ko_Web_Route::VGet('index', function () {
	echo "index";
});
//单个角色
Ko_Web_Route::VGet('role', function () {
	echo "";
});
//新增
Ko_Web_Route::VPost('role', function () {
	echo "";
});
//修改
Ko_Web_Route::VPut('role', function () {
	echo "";
});
//删除
Ko_Web_Route::VDelete('role', function () {
	echo "";
});
//权限用户列表
Ko_Web_Route::VGet('roleUserList', function () {
	echo "";
});
//新增
Ko_Web_Route::VPost('roleUser', function () {
	echo "";
});
//删除
Ko_Web_Route::VDelete('roleUser', function () {
	echo "";
});
//角色权限列表
Ko_Web_Route::VGet('rolePrivacyList', function () {
	echo "";
});
//新增
Ko_Web_Route::VPost('rolePrivacy', function () {
	echo "";
});
//删除
Ko_Web_Route::VDelete('rolePrivacy', function () {
	echo "";
});
