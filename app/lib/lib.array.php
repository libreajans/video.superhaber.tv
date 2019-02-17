<?php
	if(!defined('APP')) die('...');

	$modul_title = array(
		'welcome' 			=> 'Hoşgeldiniz',
		'password' 			=> 'Bilgilerim',
		'user' 				=> 'Kullanıcı Yönetimi',
		'content' 			=> 'Video Yönetimi',
		'comment' 			=> 'Yorum Yönetimi',
		'stats' 			=> 'İstatistik Yönetimi',
	);

	$modul_name = array(
		'welcome' 			=> 'welcome/',
		'password' 			=> 'password/',
		'user' 				=> 'user/',
		'content' 			=> 'content/',
		'comment' 			=> 'comment/',
		'stats' 			=> 'stats/',
	);

	$modul_files = array(
		'welcome'			=> 'welcome.main.php',
		'password'			=> 'password.main.php',
		'user'				=> 'user.main.php',
		'content'			=> 'content.main.php',
		'comment'			=> 'comment.main.php',
		'stats'				=> 'stats.main.php',
	);

	$array_content_type = array(
		0 => 'Normal',
		1 => 'Manşet',
	);


	$array_user_status = array(
		'1' => 'Aktif Kullanıcı',
		'0' => 'Pasif Kullanıcı',
		'9' => 'Yönetici',
	);

	//bu iki diziyi yayın durumlarını görselleştirirken kullanıyoruz
	$array_content_status = array(
 		0 => 'Pasif',
		1 => 'Yayında',
		2 => 'Taslak',
		3 => 'Silinmiş',
		4 => 'Ajans',
	);

	$array_content_status_bar = array(
		'1' => '<i class="ion ion-checkmark-circled text-green"></i>',
		'0' => '<i class="ion ion-alert-circled text-yellow"></i>',
		'2' => '<i class="ion ion-help-circled text-light-blue"></i>',
		'3' => '<i class="ion ion-alert-circled text-red"></i>',
		'4' => '<i class="ion ion-close-circled text-yellow"></i>',
	);

	$array_comment_status = array(
		1 => 'Onaylanmış',
		2 => 'Onay Bekliyor',
		3 => 'Spam',
		4 => 'Silinmiş',
	);

	$array_comment_status_bar = array(
		'1' => '<i class="ion ion-checkmark-circled text-green"></i>',
		'2' => '<i class="ion ion-help-circled text-light-blue"></i>',
		'3' => '<i class="ion ion-alert-circled text-yellow"></i>',
		'4' => '<i class="ion ion-close-circled text-red"></i>',
	);

	$array_content_comment_status = array(
		1 => 'Yorum Özelliği Açık',
		0 => 'Yorum Özelliği Kapalı',
	);

	$array_limits = array(
		100 => '100 Kayıt Göster',
		250 => '250 Kayıt Göster',
		500 => '500 Kayıt Göster',
		0	=> 'Tüm Kayıtları Göster',
	);

	$array_cat_name = array(
		201 => 'Gündem',
		202 => 'Siyaset',
		203 => 'Spor',
		204 => 'Tv',
		205 => 'Sinema',
		206 => 'Eğlence',
		207 => 'Haber',
		208 => 'Teknoloji',
		209 => 'Kültür',
		210 => 'Yaşam',
		211 => 'Müzik',
		212 => 'Magazin',
		213 => 'Yemek',
	);
	$array_cat_url = array(
		201 => SITELINK.'gundem',
		202 => SITELINK.'siyaset',
		203 => SITELINK.'spor',
		204 => SITELINK.'tv',
		205 => SITELINK.'sinema',
		206 => SITELINK.'eglence',
		207 => SITELINK.'haber',
		208 => SITELINK.'teknoloji',
		209 => SITELINK.'kultur',
		210 => SITELINK.'yasam',
		211 => SITELINK.'muzik',
		212 => SITELINK.'magazin',
		213 => SITELINK.'yemek',
	);

	$array_page_name = array(
		101 => 'Künye',
		102 => 'Kullanım Şartları',
		103 => 'Gizlilik Bildirimi',
		104 => 'Hukuki Bildirim',
		105 => 'Tanıtım',
		106 => 'İletişim',
	);

	$array_page_url = array(
		101 => 'http://www.superhaber.tv/kunye',
		102 => 'http://www.superhaber.tv/kullanim-sartlari',
		103 => 'http://www.superhaber.tv/gizlilik-bildirimi',
		104 => 'http://www.superhaber.tv/hukuka-aykirilik-bildirimi',
		105 => 'http://www.superhaber.tv/tanitim',
		106 => 'http://www.superhaber.tv/iletisim',
	);

	$L = array(
		'pIndex_Company' 					=> 'Super Video',
		'pIndex_CompanyDesc' 				=> 'Video, Haber, Tv, Dizi, Sinema, Film, Kısa Film, Fragman, Spor ve Komik şeyler',
	);

	// Sistem mesajları
	$messages = array();

	$messages['error_add']						= array('type' => 'error', 	'text' => 'Kayıt eklenirken bir hata oluştu.');
	$messages['error_edit']						= array('type' => 'error', 	'text' => 'Kayıt değiştirilirken bir hata oluştu.');
	$messages['error_delete']					= array('type' => 'error', 	'text' => 'Kayıt silinirken bir hata oluştu.');
	$messages['error_order']					= array('type' => 'error', 	'text' => 'Haberler sıralanırken bir hata oluştu.');
	$messages['error_truncate']					= array('type' => 'error', 	'text' => 'Kayıtlar temizlenirken bir hata oluştu.');
	$messages['error_kurucuUyeSilinemez']		= array('type' => 'error', 	'text' => 'Kurucu Üye Silinemez.');
	$messages['error_yetki']					= array('type' => 'error', 	'text' => 'Yetkisiz İşlem Denemesi.');

	$messages['info_add']						= array('type' => 'info',	'text' => 'Kayıt başarıyla eklendi');
	$messages['info_edit']						= array('type' => 'info',	'text' => 'Kayıt başarıyla değiştirildi.');
	$messages['info_delete']					= array('type' => 'info',	'text' => 'Kayıt başarıyla silindi.');
	$messages['info_truncate']					= array('type' => 'info',	'text' => 'Tablo başarıyla temizlendi.');
	$messages['info_truncate_passive_content']	= array('type' => 'info',	'text' => 'Pasif içerikler başarıyla temizlendi.');
	$messages['info_truncate_passive_comment']	= array('type' => 'info',	'text' => 'Pasif yorumlar başarıyla temizlendi.');
	$messages['info_truncate_acces_logs']		= array('type' => 'info',	'text' => 'Erişim logları başarıyla temizlendi.');
	$messages['info_truncate_content_image']	= array('type' => 'info',	'text' => 'Gereksiz resimler başarıyla temizlendi.');

	//İmageMagick desteği durumuna göre farklı dosya türlerine izin veriyoruz
	if(RC_Imagemagick <> 1)
	{
		$allowed_image_types = array(
				'image/pjpeg',
				'image/jpeg',
				'image/jpg'
		);
	}
	else
	{
		$allowed_image_types = array(
				'image/pjpeg',
				'image/jpeg',
				'image/jpg',
				'image/png',
				'image/gif'
		);
	}
	$allowed_video_types = array(
		'video/mp4',
	);

	$array_content_type_image_wh['w']	= 650;
	$array_content_type_image_wh['h']	= 370;

	$array_social_media = array(
		'twitter_name'		=> 'superhabertv',
		'twitter_hashtag'	=> 'superhaber',
		'facebook'			=> 'https://www.facebook.com/SuperhaberTV-321014511409514/',
		'twitter'			=> 'https://twitter.com/superhabertv',
		'instagram'			=> 'https://instagram.com/superhaber',
		'google'			=> 'https://plus.google.com/111897686187400089428',
		'feed'				=> SITELINK.'feed',
	);

	$config = array(
		'index_manset_main' 		=> 7,
		'index_manset_superhaber' 	=> 5,
		'index_manset_mostview' 	=> 4,
		//
		'index_cat_limit' 			=> 20,
		//
		'index_app_manset' 			=> 10,
		'index_app_others' 			=> 20,
		//
		'meta_tags'		 			=> 'Video, Haber, Tv, Dizi, Sinema, Film, Kısa Film, Fragman, Spor, Komik, ',
		//
		//reklamlar
		'reklam_300x250' => '',
		'reklam_300x600' => '',
		'reklam_468x60' => '',
		'reklam_728x90' => '',
		'reklam_mobile_300x250' => '',
		'reklam_big_index' => '',
	);
