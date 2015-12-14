<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月14日
 * Time: 下午7:09
 */
class KQa_Dao extends Ko_Dao_Factory {
	public $_aDaoConf = array(
		'redis'  => array(
			'type' => 'redis'
		),
		'mcache' => array(
			'type' => 'mcache',
		),
		'user'   => array(
			'type' => 'db_single',
			'kind' => 'opr_user',
			'key'  => 'id',
		),
	);
}