<?php if(!defined('APP')) die('...');

	//her halükarda sayfayı yüklüyoruz
	$icerik = $_content->content_detail($_id);

	//yönetici değilse
	//yazı pasif ise
	//null sayfaya dönsün
	//çünkü burası embed sayfası
	if($icerik[0]['content_title'] == "" && $icerik[0]['content_status'] <> 1)
	{
		header("HTTP/1.1 404 Not Found");
		header('Location:'.SITELINK.'null.html');
		exit();
	}

	//her halükarda okunma sayılarını artır
	$_content->content_view($_id);

	$baslik 				= $icerik[0]['content_title'];
	$site_title				= $baslik.' | '. $L['pIndex_Company'];
	$content_metatags		= substr($icerik[0]['content_tags'], 0, 160);

	//hangi tipde içerik istenmiş
 	$type = myReq('type', 0);

	if($type == 11)
	{
		$width		= '650';
		$height 	= '370';

		$template = $twig->loadTemplate('page_content_embed.twig');
	}

	//desktop için wh değerleri dışardan alınması istenmişse
	if($type == 12)
	{
		$keyword	= myReq('search', 1);
		if($keyword == '') $keyword = '300x250';
		$width		= $keyword[0].$keyword[1].$keyword[2];
		$height 	= $keyword[4].$keyword[5].$keyword[6];

		$template = $twig->loadTemplate('page_content_embed.twig');
	}

	if($type == 21)
	{
		$width		= '650';
		$height 	= '370';

		$template = $twig->loadTemplate('page_content_embed_app.twig');
	}

	//app için wh değerleri dışardan alınması istenmişse
	if($type == 22)
	{
		$keyword	= myReq('search', 1);
		if($keyword == '') $keyword = '300x250';
		$width		= $keyword[0].$keyword[1].$keyword[2];
		$height 	= $keyword[4].$keyword[5].$keyword[6];

		$template = $twig->loadTemplate('page_content_embed_app.twig');
	}

	$embed = $template->render
	(
		array
		(
			'content' 				=> $icerik[0],
			'width' 				=> $width,
			'height' 				=> $height,
			'site_title' 			=> $site_title,
		)
	);
