<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月22日
 * Time: 下午2:41
 */
class KUser_userApi extends Ko_Mode_Item {
	protected $_aConf = array(
		'item' => 'user',
	);

	public function getList($start, $length) {

	}

	public function getUser($uid) {
		return $this->aGet($uid);
	}

	public function getUsers($uids) {
		return $this->aGetListByKeys($uids);
	}

	public function addUser($params) {
		$insert = array(
			'username' => $params['username'],
			'flag'     => 1,
			'add_uid'  => $params['add_uid'],
			'ctime'    => date("Y-m-d H:i:s"),
		);
		return $this->iInsert($insert);
	}

	public function doAfterLogin($uid) {
		$user = new self();
		$userInfo = $user->getUser($uid);
		if (!$userInfo) {
			return false;
		}
		session_start();
		$_SESSION ["admin"] = $userInfo;
		$userLoginApi=new KUser_loginApi();
		setcookie('admintoken', $userLoginApi->SGetSessionToken($_SESSION["admin"]["id"], ''), 0, '/', '.' . MAIN_DOMAIN, false, true);
		return true;
	}

	public function logoutAdminAuth() {
		session_start();
		unset ($_SESSION ['admin']);
		setcookie('admintoken', '', 0, '/', '.' . MAIN_DOMAIN, false, true);
	}

	public function checkLogin(){
		$userLoginApi=new KUser_loginApi();
		$tokenuid = $userLoginApi->ICheckSessionToken($_COOKIE ['admintoken'], $sExinfo, $iErrno);
		if ($tokenuid)
		{
			if(!self::loginAdminAuth($tokenuid)) {
				//锁定
				return false;
			}
		}else{
			return false;
		}
	}

}