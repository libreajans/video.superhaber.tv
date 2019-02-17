<?php if(!defined('APP')) die('...');

	//tema yolundan dosyalarımızı çağırıyoruz
	include G_TEMPLATE_PATH.$template_name.'/control/page_feed.php';

	header('Content-type: application/xml');
	echo $content;
