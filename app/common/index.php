<?php
	if(!defined('APP')) die('...');

	use phpFastCache\CacheManager;

	error_reporting(E_ERROR);

	//uzun çalışsın
	ini_set('max_execution_time', 60);

	//türkiyeye geçelim
	date_default_timezone_set('Europe/Istanbul');

	//sayfa saatini başlatıyoruz
	$starttime = microtime(true);

	//--- [+]--- Root Path Doğrulaması ---
	$_SERVER['DOCUMENT_ROOT'] = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT']);
	if(substr($_SERVER['DOCUMENT_ROOT'], -1) != '/')
	{
		$_SERVER['DOCUMENT_ROOT'] .= '/';
	}
	//--- [-]--- Root Path Doğrulaması sonu ---

	//öncel ayarlar ayrı dosyaya alındı, bu sayede
	//init dosyasındaki yapılan değişikliklerden
	//yayındaki site daha az etkileniyor
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.config.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.prefunctions.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.session.php';

	//hata bastırma şeklini belirliyoruz
	//error_reporting(E_ERROR);
	if(ST_DEBUG == 2) error_reporting(E_ALL);

	$_id			= myReq('id', 0);
	$_pg			= myReq('pg', 0);
	$_key 			= myReq('key',2);
	$sayfaadi 		= myReq('page',2);

	//sayfalar varsayılan olarak cache e kapalıdır,
	//sadece izin verilen sayfaları cacheleyelim
	//lakin hiçbir sayfa phpFastCache için test edilmedi
	//yani hiçbir sayfayı şimdilik cache etmeyelim
	$cachable	= 0;

	//uygun model dosyasını çağıralım
	//arayüzü değişmeyen sayfalar için common klasörünü kullanalım
	//mobil ve  değişen sayfalar için
	switch($sayfaadi)
	{
		//COMMON içinden çalışanlar
		case 'crop':
			$inc		= 'crop';
			break;

		case 'service':
			$inc		= 'service';
			break;

		case 'giris':
			$inc		= 'giris';
			break;

		case 'acp':
			$inc		= 'acp';
			break;

		case 'cron_dha':
			$inc		= 'cron_dha';
			break;

		//checkmobile PASİF olanlar
		case 'sitemap':
			$checmobile = 0;
			$inc		= 'sitemap';
			break;

		case 'sitemap_news':
			$checmobile = 0;
			$inc		= 'sitemap_news';
			break;

		case 'feed':
			$checmobile = 0;
			$inc		= 'feed';
			break;

		case 'embed':
			$inc		= 'content_embed';
			$checmobile = 0;
			break;

		//checkmobile AKTİF ve cacheble AKTİF olanlar
		case 'index':
			$inc		= 'index';
			$cachable	= 1;
			break;

		case 'detail_content':
			$inc		= 'detail_content';
			$cachable	= 1;
			break;

		//checkmobile AKTİF ve cacheble PASİF olanlar
		case '404':
			$inc		= '404';
			break;

		case 'cat':
			$inc		= 'cat';
			break;

		case 'ajax':
			$inc		= 'ajax';
			break;

		case 'archive':
			$inc		= 'archive';
			break;

		case 'search':
			$inc		= 'search';
			break;

		case 'tags':
			$inc		= 'tags';
			break;
	}

	//cache de sayfa yoksa bu kısım işlemeye başlayacak
	//sayfayı iteleyelim
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.php';

	if
	(
		$sayfaadi == 'crop' or
		$sayfaadi == 'cron_dha' or
		$sayfaadi == 'service' or
		$sayfaadi == 'giris' or
		$sayfaadi == 'acp'
	)
	{
		if($sayfaadi == "service" OR $sayfaadi == "acp" )
		{
			include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.template.php';
		}
		include $_SERVER['DOCUMENT_ROOT'].'app/common/'.$inc.'.php';
	}
	else
	{
		//template iteleyelim
		if($inc <> '')
		{
			include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.template.php';
			include $_SERVER['DOCUMENT_ROOT'].'app/Template/'.$template_name.'/Model/'.$inc.'.php';
		}
	}
