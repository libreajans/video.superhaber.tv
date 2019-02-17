<?php if(!defined('APP')) die('...');

	$site_title					= tr_ucwords($_key).' - Video';
	$content_metatitle 			= tr_ucwords($_key).' etiketi için bulunan sonuçlar, '.$L['pIndex_Company'];
	$content_metadesc 			= tr_ucwords($_key).' etiketi için bulunan sonuçlar, '.$L['pIndex_Company'];
	$content_metatags			= $_key.', '.$L['pIndex_Company'];
	$site_canonical				= LINK_TAGS.$_key;
	$content_metaimage			= G_IMGLINK_APP.'logo_sh.png';

	//3 karakterden kısa bir etiket aranıyorsa sistemi yormamak adına ana sayfaya yönlendiriyoruz
	if(strlen($_key) < 3)
	{
		header('Location:'.LINK_INDEX);
		exit();
	}
	else
	{
		$content_list = $_content->content_list_manset(
			$page 		= 1,
			$limit 		= $config['index_cat_limit'],
			$type 		= 'none',
			$cat 		= 'none',
			$etiket 	= $_key,
			$json 		= 0
		);
		$content_list_pages	= $_content->content_list_manset_pages(
			$limit 		= $config['index_cat_limit'],
			$type 		= 'none',
			$cat 		= 'none',
			$etiket 	= $_key
		);

		//aranan etiket 0 sonuç döndürmüş ise
		if(count($content_list) == 0)
		{
			header('Location:'.LINK_INDEX);
			exit();
		}
	}

	$template = $twig->loadTemplate('page_tags.twig');
	$content = $template->render
	(
		array
		(
			'key' 					=> $_key,
			'content_list'			=> $content_list,
			'content_list_pages'	=> $content_list_pages,
		)
	);

