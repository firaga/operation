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
					'kind' => 'opr_menu',
					'key' => 'id',
			),
			'sqMenuTree' => array(
					'type' => 'db_single',
					'kind' => 'opr_menu_tree',
					'key' => 'id',
			),
	);
}
