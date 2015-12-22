<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月22日
 * Time: 下午2:41
 */
class KUser_userApi extends Ko_Mode_Item{
	protected $_aConf = array(
		'item' => 'user',
	);

	public function getList($start,$length){

	}

	public function getUser($uid){
		return $this->aGet($uid);
	}

	public function getUsers($uids){
		return $this->aGetListByKeys($uids);
	}

	public function addUser($params){
		$insert=array(
			'username'=>$params['username'],
			'flag'=>1,
			'add_uid'=>$params['add_uid'],
			'ctime'=>date("Y-m-d H:i:s"),
		);
		return $this->iInsert($insert);
	}

}