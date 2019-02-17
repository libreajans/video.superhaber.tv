<?php if(!defined('APP')) die('...');

	/**
	|
	| Geçerli talep tipleri ve talep şekilleri

	|--------------------------------------------------------------

	| index.php?page=service&secure=MY_SECURITY_KEY&type=manset_superhaber
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=manset_superhaber
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=manset_superhaber
	|
	| Super Haber ana sayfası için ayarlanmış miktardaki manşet bilgisini döndürür
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=icerik&id={NUMBER}
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=icerik&id=12452
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=icerik&id=10091
	|
	| Belirtilen içerike ait bilgileri döndürür
	| Data yoksa false döner
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=icerik_app&id={NUMBER}&wh={TEXT:800x600}
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=icerik_app&id=12452&wh=800x600
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=icerik_app&id=10091&wh=800x600
	|
	| uygulamaya özel içerik listesi döner; içerik yoksa false değil boş array döner
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=kategori&id={NUMBER}
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=kategori&id=211
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=kategori&id=211
	|
	| Belirtilen kategori türüne ait içerikleri döndürür,
	| Kabul edilen kategoriler şunlardır
	| (dizi değerlerimizdeki $array_cat_name ile aynıdır)
	|
	|	201 : Gündem
	|	202 : Siyaset
	|	203 : Spor
	|	204 : Tv
	|	205 : Sinema
	|	206 : Eğlence
	|   207 : Haber
	|	208 : Teknoloji
	|	209 : Kültür
	|	210 : Yaşam
	|   211 : Müzik
	|	212 : Magazin
	|	213 : Yemek
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=manset_app_unified
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=manset_app_unified
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=manset_app_unified
	|
	| Uygulama için son girilen içerikleri döner
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=manset_app_seperated
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=manset_app_seperated
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=manset_app_seperated
	|
	| Uygulama için son girilen içerikleri ana manşet, ve diğerleri şeklinde ikili olarak döner
	|
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=arama&search={VARCHAR}
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=arama&search=aysun
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=arama&search=aysun
	|
	| Tüm data içinde belirtilen kelimeyi arar ve bulunan en fazla 100 sonucu görüntüler
	| Aranan kelime yoksa veya aranan kelime 3 harften az karakter içeriyorsa false döner
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=yorumEkle&id={CONTENT_ID/Number}&author={AUTHOR/Varchar}&comment={COMMENT/Varchar}
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=yorumEkle&id=10082&author=tester&comment=test_metin
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=yorumEkle&id=10082&author=tester&comment=test_metin
	|
	| Belirtilen içerike yorum eklemeye yarar;
	| zorunlu alanlar: id, author, comment
	|
	| Kayıt kabul edildiyse true, hatalıysa false döner
	| Dikkat: true dönmesi ilgili yorumun sitede yayınlanacağı anlamına gelmez
	| Yorumlar, editörler onayladıktan sonra sitede görünür olacaktır
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=yorumListe&id={CONTENT_ID/Number}
	|
	| Örnek Canlı: http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=yorumListe&id=12986
	| Örnek Lokal: http://supervideo.app/index.php?page=service&secure=MY_SECURITY_KEY&type=yorumListe&id=10083
	|
	*/

	include 'lib/init.php';
	//init çağrısından önce kod olmaması tercihimdir

	define('serviceApiKey', 'MY_SECURITY_KEY');

	//init çağrısından önce kod olmaması tercihimdir
	$type	= htmlspecialchars($_REQUEST['type']);
	$secure	= htmlspecialchars($_REQUEST['secure']);
	//arama
	$keyword	= myReq('search', 1);
	$wh			= myReq('wh', 1);

	//yorum kayıt için gerekli olanlar
	$author		= htmlspecialchars($_REQUEST['author']);
	$comment	= htmlspecialchars($_REQUEST['comment']);

	if($secure <> serviceApiKey)
	{
		die('Anahtar Hatali');
	}

	if($type == 'manset_superhaber')
	{
		$data = $_content->content_list_manset
		(
			$page 		= 1,
			$limit 		= $config['index_manset_superhaber'],
			$type 		= '1',
			$cat 		= 'none',
			$etiket 	= 'none',
			$json 		= 1
		);
		$data = array('data' => $data);
	}

	//içerik ile ilgili detay bilgileri getiriyoruz
	if($type == 'icerik' && $_id > 0)
	{
		$data_detail = $_content->content_detail($_id, 1);

		$data = array
		(
			'data'		=> false,
		);

		//false geliyorsa direk dönelim
		if($data_detail == false)
		{
			$data = array('data' => false);
		}
		else
		{
			if($wh == '') $wh = '320x200';
			$data_detail[0]['content_embed_app'] = SITELINK.'embed-app/'.$_id.'/'.$wh.'/';

			$data = array
			(
				'data'		=> $data_detail,
			);
		}
	}

	//içerik ile ilgili detay bilgileri getiriyoruz
	if($type == 'icerik_app' && $_id > 0)
	{
		$data_detail 	= $_content->content_detail($_id, 1);
		//print_pre($data_detail);
		$list_comments 	= $_comment->comment_list_content($_id);
		if(!is_array($list_comments))
		{
			$list_comments = array();
		}

		//lazım olmayan değerleri kapatalım
		unset( $data_detail[0]['content_text']);
		if($wh == '') $wh = '300x250';
		$data_detail[0]['content_embed_app'] = SITELINK.'embed-app/'.$_id.'/'.$wh.'/';

		$data = array
		(

			'data'		=> $data_detail[0],
			'smilar' 	=> $_content->content_list_benzer( $data_detail[0]['content_cat'], $exclude = $_id, $limit = 5),
			'comment' 	=> $list_comments,
		);
	}

	//kategoriye ait dataları döndürür
	//belli bir id belirtilmelidir
	if($type == 'kategori' && $_id > 200 && $_id < 214)
	{
		//kategoriler sayfasında
		//sur manşet diye gönderdiğimiz data aslında
		//manşet olmayan diğer içeriklerin listesini oluşturmakta kullanılıyor
		$data = $_content->content_list_manset
		(
			$page 		= 1,
			$limit 		= 50,
			$type 		= 'none',
			$cat 		= $_id,
			$etiket 	= 'none',
			$json 		= 1
		);
		$data = array('data' => $data);
	}

	//uygulama için son girilen içerikleri gönderir
	if($type == 'manset_app_unified')
	{
		$data = $_content->content_list_manset
		(
			$page 		= 1,
			$limit 		= 50,
			$type 		= 'none',
			$cat 		= 'none',
			$etiket 	= 'none',
			$json 		= 1
		);
		//istenmeyen datalar kaldırılıyor
		foreach($data as $k => $v)
		{
			unset(
				$data[$k]['content_image_dir'],
				$data[$k]['content_seo_url'],
				$data[$k]['content_image'],
				$data[$k]['content_cat_url']
			);
		}
 		$data = array('data' => $data);
	}

	if($type == 'manset_app_seperated')
	{
		$index_manset = $_content->content_list_manset(
			$page 		= 1,
			$limit 		= $config['index_app_manset'],
			$type 		= '1',
			$cat 		= 'none',
			$etiket 	= 'none',
			$json 		= 1
		);
		$index_non_manset = $_content->content_list_manset(
			$page 		= 1,
			$limit 		= $config['index_app_others'],
			$type 		= '0',
			$cat 		= 'none',
			$etiket 	= 'none',
			$json 		= 1
		);

		$data['data'] = array
		(
			'ana_manset' => $index_manset,
			'sur_manset' => $index_non_manset
		);
	}

	if($type == 'arama')
	{
		$data = array('data' => $_content->content_list_search($keyword, $limit = 100, $json = 1));
	}

	if($type == 'yorumEkle')
	{
		$data = array('data' => $_comment->comment_add_app($_id, $author, $comment));
	}

	if($type == 'yorumListe' && $_id > 0)
	{
		$data = array('data' => $_comment->comment_list_content($_id, $json = 1));
	}

	header('Content-type: application/json');
	$data = json_encode($data) ;
	echo $data;
