<?php if(!defined('APP')) die('...');

	//id burada cat_id anlamına gelmektedir, content_id ile karıştırılmamalıdır
	$site_title				= $array_cat_name[$_id].' - Video';
	$content_metatitle 		= $array_cat_name[$_id].', '.$L['pIndex_Company'];
	$content_metadesc 		= $array_cat_name[$_id].', '.$L['pIndex_Company'];
	$content_metatags		= $array_cat_name[$_id].', '.$L['pIndex_Company'];
	$site_canonical			= $array_cat_url[$_id];
	$content_metaimage		= G_IMGLINK_APP.'logo_sh.png';


	$template = $twig->loadTemplate('page_cat.twig');
	$content = $template->render
	(
		array
		(
			'content_list' => $_content->content_list_manset(
				$page 		= 1,
				$limit 		= $config['index_cat_limit'],
				$type 		= 'none',
				$cat 		= $_id,
				$etiket 	= 'none',
				$json 		= 0
			),
			'content_list_pages'	=> $_content->content_list_manset_pages(
				$limit 		= $config['index_cat_limit'],
				$type 		= 'none',
				$cat 		= $_id,
				$etiket 	= 'none'
			),
		)
	);
