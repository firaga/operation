<?php
/**
 * Created by PhpStorm.
 * User: Loin
 * Date: 14-8-6
 * Time: 下午6:15
 */
class KAdmin_Helper
{
	public static function vOutput($vData, $sType = 'text') {
		$_SESSION['back_url'] = '';
		if (strtoupper($sType) == 'JSON') {
			// 返回JSON数据
			header("Content-Type:text/html; charset=utf-8");
			echo json_encode($vData);
		} else {
			echo $vData;
		}
		exit();
	}
}