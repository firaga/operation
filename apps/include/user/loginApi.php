<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月14日
 * Time: 下午6:22
 */
class KUser_loginApi extends Ko_Mode_Item{
	protected $_aConf = array(
			'item' => 'user',
	);
	const ERRNO_UNREGISTERED=1;
	const ERRNO_PASSWD=2;
	public function iLogin($sUsername,$sPasswd,&$iErrNo=0) {
		if(!$aUserInfo=$this->aIsRegistered($sUsername)){
			$iErrNo=self::ERRNO_UNREGISTERED;
			return 0;
		}
		if(md5($aUserInfo['salt'].'_'.$sPasswd)!=$aUserInfo['passwd']){
			$iErrNo=self::ERRNO_UNREGISTERED;
			return 0;
		}
		//setcookie
		return $aUserInfo['id'];
	}

	public function aIsRegistered($aUsername){
		$aOption = new Ko_Tool_SQL();
		$aOption->oWhere(array('username'=>$aUsername));
		$aList=$this->aGetList($aOption,1000);
		return $aList[0];
	}

	public function bRegister($sUsername,$sPasswd,&$iErrNo=0) {
		$sSalt=rand(1000,9999);
		$aData=array(
				'username'=>$sUsername,
				'passwd'=>md5($sSalt.'_'.$sPasswd),
				'salt'=>$sSalt,
		);
		$iUid=$this->iInsert($aData);
		return !!$iUid;
	}
}
