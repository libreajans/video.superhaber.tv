<?php if(!defined('APP')) die('...');

	include G_TEMPLATE_PATH.$template_name.'/control/page_404.php';
	include G_TEMPLATE_PATH.$template_name.'/control/site_header.php';
	include G_TEMPLATE_PATH.$template_name.'/control/site_footer.php';

	echo $header.$content.$footer;

