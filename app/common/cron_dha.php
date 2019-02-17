<?php if(!defined('APP')) die('...');

	include 'lib/init.php';

	//bu kısımda DHA'ya bağlanıp XML verisini çekeceğiz
	//file get content uzak sunucuda
	//ayarlardan dolayı çalışmıyor
	//onun yerine curl kullanıyoruz
	//initalize

	//+++ muhtemel üç seçenek, SİLME ----
	//curl_setopt($ch, CURLOPT_URL , "http://ajans.dha.com.tr/dhayharss_videoluresimli.php?x=haberiyakala&y=6a288a5f993d01833914439960d447d6");
	//curl_setopt($ch, CURLOPT_URL , "http://ajans.dha.com.tr/dhayharss_resimli.php?x=haberiyakala&y=6a288a5f993d01833914439960d447d6");
	//curl_setopt($ch, CURLOPT_URL , "http://ajans.dha.com.tr/dhayharss.php?x=haberiyakala&y=6a288a5f993d01833914439960d447d6");
	//--- muhtemel üç seçenek, SİLME ----

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL , "http://ajans.dha.com.tr/dhayharss_videoluresimli.php?x=haberiyakala&y=6a288a5f993d01833914439960d447d6");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
	curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
	$data_curl = curl_exec($ch);
	//echo $data;
	curl_close($ch);
	//datalarımızın yerelde tutulacağı dosya yol
	//sadece kimi dataları inceleme amaçlı
// 	$url_local = 'cache/dha-'.date('Y-m-d-H-i').'.xml';
// 	file_put_contents($url_local, $data_curl);

 	$data = simplexml_load_string($data_curl);
 	//print_pre($data);

	$adet = count($data->channel->item);
	if($adet > 0)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$list[$i]['title'] 			= strip_tags($data->channel->item[$i]->title);
			$list[$i]['guid'] 			= strip_tags($data->channel->item[$i]->guid);
			$list[$i]['video'] 			= strip_tags($data->channel->item[$i]->videos);

			//videolu içerikleri kabul etmiyoruz
			$video 		= strip_tags($data->channel->item[$i]->videos);
			$videoExt 	= strip_tags($data->channel->item[$i]->videosextension);
			if($video <> "" && $videoExt == "mp4")
			{
			}
			else
			{
				//videolu haberler, bizi hiç rahatsız etmeyin
				//$list[$i]['description'] = $list[$i]['description'].'<br/><br/><h1><a href="'.$video.'">'.$video.'</a></h1>';
				$list[$i]['guid'] = '';
			}
		}

		//print_pre($list);
		//guid olmayan içerikleri dizimizden uzaklaştıralım :(
		for($i = 0; $i < $adet; $i++)
		{
			if($list[$i]['guid'] == "") unset($list[$i]);
		}

		for($i = 0; $i < $adet; $i++)
		{
			//içerik daha önce kaydedilmiş mi?
			//bunun için veritabanındaki content_redirect alanınındaki guid değerlerine bakıyoruz
			//hızlı işlem için redirect alanlarına index ekledik

			//önce bakıyoruz, içerik daha önce kayıt edilmiş mi?
			if($list[$i]['guid'] == "")
			{
				echo '* guid boş, tozol<br/>';
			}
			else
			{
				if($_content->get_content_bot_guid($list[$i]['guid']) == false)
				{
	// 				echo $list[$i]['video']."<br/><br/>";

	// 				başlık'ı oluşturalım
	// 				başlık metninden kimi değerleri kaldıralım
					$title = $list[$i]['title'];
					$title = str_replace('(Görüntülü Haber) 5','', $title);
					$title = str_replace('(Görüntülü Haber) 4','', $title);
					$title = str_replace('(Görüntülü Haber) 3','', $title);
					$title = str_replace('(Görüntülü Haber) 2','', $title);
					$title = str_replace('(Görüntülü Haber) 1','', $title);
					$title = str_replace('(Görüntülü Haber)','', $title);
					$title = str_replace('(Geniş Haber)','', $title);

					if($list[$i]['video'] <> "" && strlen($list[$i]['video']) > 8 )
					{
						//videoyu kaydetmeye çalışalım
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL , $list[$i]['video']);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HEADER, false);
						$data_curl = curl_exec($ch);
						//echo $data;
						curl_close($ch);
						//datalarımızın yerelde tutulacağı dosya yol
						//sadece kimi dataları inceleme amaçlı
						$dosya_adi = 'dha-'.format_url($title).'_'.gen_key(5).'.mp4';
						$url_local = VIDEO_DIRECTORY.date('Y/m/d/').$dosya_adi;
						file_put_contents($url_local, $data_curl);
					}

					//dosya inmiş mi kontrol edelim; dosya inmişse
					//ve boyutu da belli bir yükseklikten genişse
					//içeriği kayıt etmeye çalışalım

					if(file_exists($url_local) == true && filesize($url_local) > 32)
					{
						//video var diye düşünüyoruz
						//içerik kayıtlı değil, kayıt et
						//lakin bunun için kimi değerleri REQUEST içine taşımamız lazım
						$_REQUEST['content_redirect'] 			= $list[$i]['guid'];

						$_REQUEST['content_title'] 				= $title;
						$_REQUEST['content_video'] 				= $dosya_adi;

						$_REQUEST['content_seo_title'] 			= $title;
						$_REQUEST['content_seo_url'] 			= $title;
						$_REQUEST['content_seo_metadesc'] 		= $title;

						$_REQUEST['content_text'] 				= $title;
						$_REQUEST['content_time'] 				= date("Y-m-d H:i:s");
						$_REQUEST['content_status'] 			= 4;			//ajans haberi diye kayıt için
						$_REQUEST['content_user'] 				= 44; 			//paneldeki user id, dha için şimdilik 44
						$_REQUEST['content_cat'] 				= 121; 			//kategorimiz 121 olsun; ajans haberlerine düşsün
						$_REQUEST['content_comment_status'] 	= 1; 			//yorum özelliği aktif olsun
						$_REQUEST['content_type'] 				= 0; 			//düz haber olsun
						$_REQUEST['content_tags'] 				= 'DHA, ajans';	//etiket ataması yapalım
						$_REQUEST['content_check_images'] 		= 1;		//bu değer sayesinde resim durumlarını bypas edip,
																			//ajans içeriği diye kaydedebiliyoruz
						echo '.';
						//varsa daha önce bu guid ile kayıtlı YAYINDA OLMAYAN içerik siliyoruz
						$_content->content_delete_from_guid($list[$i]['guid']);
						$_id = $_content->content_add($user = $_REQUEST['content_user']);
						$_content->content_edit($_id);
					}
					else
					{
						//video yok kardeşim, hiç uğraşma
						echo '* video yok kardeşim, hiç uğraşma <br/>';
					}
				}
				else
				{
					//pas geç, kayıt edilmiş zaten
					echo '! kayıt edilmiş, pas geç <br/>';
				}
			}
		}
	}
	else
	{
		//data yüklemesi yapılamadı
		//TODO sayfayı yeniden yükle!
	}
