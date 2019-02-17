<?php if(!defined('APP')) die('...');

	$site_title			= 'Sayfa Bulunamadı - '.$L['pIndex_Company'];
	$content_metatitle 	= $L['pIndex_Company'];
	$content_metadesc 	= $L['pIndex_CompanyDesc'];
	$content_metatags	= $L['pIndex_Company'];
	$site_canonical 	= LINK_INDEX;
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	$template = $twig->loadTemplate('page_404.twig');
	$content = $template->render
	(
		array
		(
			'index_manset' => $_content->content_list_manset(
				$page 		= 1,
				$limit 		= $config['index_manset_main'],
				$type 		= '1',
				$cat 		= 'none',
				$etiket 	= 'none',
				$json 		= 0
			),
		)
	);

	//hatalı urlyi kaydedelim
	if($_SERVER['REQUEST_URI'] <> '/index.php?page=404')
	{
		$uri = get_real_ip().' - ['.date('d/M/Y:H:i:s').'] "'.$_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].' '.$_SERVER['SERVER_PROTOCOL'].'"';
		$uri.=' - "'.$_SERVER['HTTP_USER_AGENT'].'" - '.$_SERVER['HTTP_REFERER']."\n";
		$uri.= file_get_contents('cache/404.errors');
		file_put_contents('cache/404.errors', $uri);
	}
