<?php if(!defined('APP')) die('...');

	$action_type			= 'edit';
	$action_submit_draft	= 'Taslak';
	$action_submit_publish	= 'Yayınla';

	if($do == 'add')
	{
		$_id = $_content->content_add();

		//tam burada içeriği yeniden çağıracağız
		//edit için gerek yok, content.main içinde onu zaten çağırdık
		$list = $_content->content_detail($_id);
	}

	//text
	$content_text					= $list[0]['content_text'];

	//varchar
	$content_title					= $list[0]['content_title'];
	$content_seo_title				= $list[0]['content_seo_title'];
	$content_seo_url				= $list[0]['content_seo_url'];
	$content_seo_metadesc			= $list[0]['content_seo_metadesc'];

	//varchar formatsız
	$content_image					= $list[0]['content_image'];
	$content_image_dir				= $list[0]['content_image_dir'];
	$content_video					= $list[0]['content_video'];
	$content_redirect				= $list[0]['content_redirect'];
	$content_tags					= $list[0]['content_tags'];

	//short int
	$content_status					= $list[0]['content_status'];
	$content_ads_status				= $list[0]['content_ads_status'];
	$content_comment_status			= $list[0]['content_comment_status'];

	//int
	$content_cat					= $list[0]['content_cat'];
	$content_cat2					= $list[0]['content_cat2'];
	$content_cat3					= $list[0]['content_cat3'];
	$content_type					= $list[0]['content_type'];
	$content_view					= $list[0]['content_view'];
	$content_view_real				= $list[0]['content_view_real'];

	//timestamp
	$content_time					= $list[0]['content_time'];

	//biçimlendirmeler
	$content_title 					= htmlspecialchars($content_title);
	$content_seo_title 				= htmlspecialchars($content_seo_title);
	$content_seo_url 				= htmlspecialchars($content_seo_url);
	$content_seo_metadesc 			= htmlspecialchars($content_seo_metadesc);

	//resimlerin bilgileri
	$content_image_link				= G_IMGLINK.'content/'.$content_image_dir.$content_image;
	$content_video_link				= G_VIDEOLINK.$content_image_dir.$content_video;

	//resimlerin yolları
	$content_image_path				= IMAGE_DIRECTORY.'content/'.$content_image_dir.$content_image;
	$content_video_path				= VIDEO_DIRECTORY.$content_image_dir.$content_video;

 	//içerik tipleri
	foreach($array_content_type as $k => $v)
	{
		$selected = ''; if($content_type <> '' && $content_type == $k) $selected = 'selected';
		$option_type.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

 	//yorum özelliği
	foreach($array_content_comment_status as $k => $v)
	{
		$selected = ''; if($content_comment_status <> '' && $content_comment_status == $k) $selected = 'selected';
		$option_comment.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	//kategori 1
	$option_cat = '<option value="">Kategori Seçiniz</option>'. "\n";
	foreach($array_cat_name as $k => $v)
	{
		$selected = ''; if($content_cat <> '' && $content_cat == $k) $selected = 'selected';
		$option_cat.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}
	//kategori 2
	$option_cat2 = '<option value="">Kategori Seçiniz</option>'. "\n";
	foreach($array_cat_name as $k => $v)
	{
		$selected = ''; if($content_cat2 <> '' && $content_cat2 == $k) $selected = 'selected';
		$option_cat2.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}
	//kategori 3
	$option_cat3 = '<option value="">Kategori Seçiniz</option>'. "\n";
	foreach($array_cat_name as $k => $v)
	{
		$selected = ''; if($content_cat3 <> '' && $content_cat3 == $k) $selected = 'selected';
		$option_cat3.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

 	//
 	if($content_ads_status == 0)			$content_ads_status_checked = ' checked';

	//---[+]--- Resim Alanı Uyarıları ----------------------------------------------

	//Resimler yoksa uyarı üretiyoruz
	if($content_image == '' && $do <> "add")
	{
		$aspect_error_image			= 1;
		$aspect_error_image_text	= 'Resim alanını boş bırakmayınız.';
	}

	//resim varsa resim boyutlarını kontrol ediyoruz
	//Boyutlar hatalı ise uyarı veriyoruz
	if($content_image <> '')
	{
		$image_sizes = getimagesize($content_image_path);
		if($image_sizes[0] <> $array_content_type_image_wh['w']) $aspect_error_image = 1;
		if($image_sizes[1] <> $array_content_type_image_wh['h']) $aspect_error_image = 1;
		$aspect_error_image_text	= 'Resim boyutları hatalı, lütfen resmi kırpınız';
	}

	if($content_video <> '')
	{
		if(filesize($content_video_path) < 32) $aspect_error_video = 1;
		$aspect_error_video_text	= 'Video dosyası hatalı, lütfen kontrol ediniz';
	}

	//---[-]--- Resim Alanı Uyarıları ----------------------------------------------

	//sil butonu için yetkilendirme testi
	if($_auth['content_delete'] <> '1') $hata_delete = '1';
