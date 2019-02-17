<?php if(!defined('APP')) die('...');

	$site_canonical		= LINK_FEED;

	$list = $_content->content_list_rss(100);

	$template = $twig->loadTemplate('page_feed.twig');
	$content = $template->render
	(
		array
		(
			'content'			=> $list,
			'last_build_date'	=> pco_format_date(mktime()),
		)
	);
