<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Jichen Zhou
 * Date: 2015年12月3日
 * Time: 下午7:40
 */
class KPhoto_Dao extends Ko_Dao_Factory {
	protected $_aDaoConf = array (
			'redis' => array (
					'type' => 'redis'
			),
			'mcache' => array (
					'type' => 'mcache',
			),
			'sqMenu' => array (
					'type' => 'db_single',
					'kind' => 'sq_menu',
					'key' => 'id',
			),
			'sqMenuTree' => array(
					'type' => 'db_single',
					'kind' => 'sq_menu_tree',
					'key' => 'id',
			),
			'sqRole' => array(
					'type' => 'db_single',
					'kind' => 'sq_role',
					'key' => 'rid',
			),
			'sqUser' => array(
					'type' => 'db_single',
					'kind' => 'sq_user',
					'key' => 'id',
			),
			'sqRolePrivacy' => array(
					'type' => 'db_single',
					'kind' => 'sq_role_menu_privacy',
					'key' => 'id',
			),
	);
}
