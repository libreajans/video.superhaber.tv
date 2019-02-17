<?php if(!defined('APP')) die('...');

	//tema yolundan dosyalarımızı çağırıyoruz
	include G_TEMPLATE_PATH.$template_name.'/control/page_tags.php';
	include G_TEMPLATE_PATH.$template_name.'/control/site_header.php';
	include G_TEMPLATE_PATH.$template_name.'/control/site_footer.php';

	echo $header.$content.$footer;
