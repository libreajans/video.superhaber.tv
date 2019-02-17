<?php if(!defined('APP')) die('...');

	$type	= myReq('type',1);
	$image	= myReq('image',1);
	
	if($type == 0) $list = $_content->content_list_sitemap($limit = 500, $type = 0);
	if($type == 1) $list = $_content->content_list_sitemap($limit = 200, $type = 1);

	$template = $twig->loadTemplate('page_sitemap.twig');
	$content = $template->render
	(
		array
		(
			'type'		=> $type,
			'image'		=> $image,
			'content'	=> $list,
			'page_url'	=> $array_page_url,
			'tarih' 	=> date('Y-m-d',time()).'T'.date('H:i:s',time()).'+02:00',
		)
	);
