<?php

class KRest_User_login {
	public static $s_aConf = array(
		'unique'          => 'int',
		'stylelist'       => array(
			'default' => 'int',
		),
		'poststylelist'   => array(
			'default' => array(
				'hash', array(
					'username' => 'string',
					'passwd'   => 'string',
				),
			),
			'register' => array(
				'hash', array(
					'username' => 'string',
					'passwd'   => 'string',
				)
			),
		),
	);

	public function post($update, $after = null,$post_style) {
		$api = new KUser_loginApi();
		if($post_style=='default'){
			$uid = $api->iLogin($update['username'], $update['passwd'], $errno);
			if (!$uid) {
				if (Ko_Mode_User::E_LOGIN_USER == $errno) {
					throw new Exception('用户名不存在', 1);
				}
				if (Ko_Mode_User::E_LOGIN_PASS == $errno) {
					throw new Exception('密码错误', 2);
				}
				throw new Exception('登录失败，请重试', 2);
			}
		}elseif($post_style=='register'){
			$userApi=new KUser_userApi();
			$insert=array(
				'username'=>$update['username'],
			);
			$uid=$userApi->addUser($insert);
			if(!$uid){
				throw new Exception('注册失败,请重试');
			}
			$ret = $api->bRegisterUid($uid,$update['username'], $update['passwd'], $errno);
			if(!$uid){
				switch($errno){
					case 3:
						throw new Exception('用户名已存在', 1);
						break;
				}
			}
		}
		return array('key' => $uid);
	}

	public function delete($id, $before = null) {
		$api = new KUser_loginApi();
		$api->vSetLoginUid(0);
		return array('key' => $id);
	}
}
