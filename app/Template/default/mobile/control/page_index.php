<?php if(!defined('APP')) die('...');

	$site_title				= $L['pIndex_Company'];
	$content_metatitle 		= $L['pIndex_Company'];
	$content_metadesc 		= $L['pIndex_CompanyDesc'];
	$content_metatags		= $L['pIndex_Company'];
	$site_canonical 		= LINK_INDEX;
	$content_metaimage		= G_IMGLINK_APP.'logo_sh.png';

	$template = $twig->loadTemplate('page_index.twig');
	$content = $template->render
	(
		array
		(
				'index_manset_main'	=> $_content->content_list_manset(
						$page 		= 1,
						$limit 		= $config['index_manset_main'],
						$type 		= '1',
						$cat 		= 'none',
						$etiket 	= 'none',
						$json 		= 0
				),
				'content_list'			=> $_content->content_list_manset(
						$page 		= 1,
						$limit 		= $config['index_cat_limit'],
						$type 		= '0',
						$cat 		= 'none',
						$etiket 	= 'none',
						$json 		= 0
				),
				'content_list_pages'	=> $_content->content_list_manset_pages(
						$limit 		= $config['index_cat_limit'],
						$type 		= '0',
						$cat 		= 'none',
						$etiket 	= 'none'
				),
				'index_most_view'		=> $_content->content_most_view($config["index_manset_mostview"]),
		)
	);
