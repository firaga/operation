<?php

class KOperation_Menu_TreeApi extends Ko_Mode_Tree
{
	const MAX_DEPTH = 4;

	protected $_aConf = array(
		'treeApi' => 'sqMenuTreeDao',
		'mc' => 'mcache',
		'maxdepth' => self::MAX_DEPTH,
	);
}