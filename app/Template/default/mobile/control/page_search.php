<?php if(!defined('APP')) die('...');

	$site_title			= 'Arama - '.$L['pIndex_Company'];
	$content_metatitle 	= $L['pIndex_Company'];
	$content_metadesc 	= $L['pIndex_Company'];
	$content_metatags	= $L['pIndex_Company'];
	$site_canonical		= LINK_SEARCH;
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	$keyword = myReq('search', 1);
	if($keyword <> "" && strlen($keyword) > 2)
	{
		$content_list_search = $_content->content_list_search( $keyword, $limit = ($config['index_cat_limit']*5), $json = 0 );
	}

	$template = $twig->loadTemplate('page_search.twig');
	$content = $template->render
	(
		array
		(
			'keyword' 		=> $keyword,
			'content_list'	=> $content_list_search,
		)
	);
