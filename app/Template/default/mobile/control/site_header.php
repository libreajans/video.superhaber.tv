<?php if(!defined('APP')) die('...');

	//manşet fazlasına bakım
	$_content->get_cron();

	$template = $twig->loadTemplate('site_header.twig');
	$header = $template->render
	(
		array
		(
			//og: için test
			'content' 					=> $icerik[0],
			//her sayfanın kendisinden gelecek olan değerler
			'site_title'				=> $site_title,
			'site_canonical'			=> $site_canonical,
			'content_metatitle' 		=> $content_metatitle,
			'content_metadesc' 			=> $content_metadesc,
			'content_metatags'			=> $content_metatags,
			'content_metaimage'			=> $content_metaimage,
		)
	);
