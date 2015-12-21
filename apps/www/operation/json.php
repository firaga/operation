<?php
$jsonData = array(
	'update'     => array(
		'username' => 'jichen',
		'passwd'   => '1235',
	),
	'post_style' => 'default',
);
echo json_encode($jsonData, true) . chr(10);