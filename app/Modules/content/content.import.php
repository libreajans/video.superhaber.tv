<?php
	if(!defined('APP')) die('...');

	$content_image_dir = date("Y/m/d/");
	define('SUBFOLDER', $content_image_dir);

	class YouTubeVideoDownloader
	{

		//formatlar için kaynak: http://www.genyoutube.net/formats-resolution-youtube-videos.html
		//
		//22 = 1280 x 720	: mp4
		//35 = 854  x 480 	: flv (işlevsiz, ses yok)
		//34 = 640  x 360 	: flv (işlevsiz, ses yok)
		//18 = 640  x 360	: mp4
		// 5 = 400  x 240	: flv

		//38 = 4096 x 3072	: mp4 (çok büyük)
		//37 = 1920 x 1080	: mp4
		//46 = 1920 x 1080	: webm (işlevsiz, player desteklemiyor + ses yok)
		//45 = 1280 x 720	: webm (işlevsiz, player desteklemiyor + ses yok)
		//43 = 640 x 360	: webm (işlevsiz, player desteklemiyor)
		//44 = 854 x 480	: webm (işlevsiz, player desteklemiyor + ses yok)

		//mp4 player tarafından desteklenen formatlar
		//5, 18, 22, 37, 34, 35, 37, 38, 43, 44, 45, 46

		private $supportedVideoFormat = array('5', '18', '22', '34', '35', '37', '38', '43', '44', '45', '46');
		private $videoID;
		private $downloadUrl;
		private $videoFormat;
		private $videoImage;
		//public erişilebilecek değerler
		public 	$videoTitle;
		public 	$videoKeywords;
		public 	$videoUrl;
		public 	$videoFileName;
		public 	$imageUrl;
		public 	$imageFileName;
		public	$errorText;

		public function __construct($videoID)
		{
			$this->videoID = $videoID;
			$this->createDownloadLink();
		}

		private function parseStreams(array $streams)
		{
			$formats = [];

			if (count($streams) === 1 and $streams[0] === '' )
			{
				return $formats;
			}

			foreach ($streams as $format)
			{
				parse_str($format, $format_info);
				parse_str(urldecode($format_info['url']), $url_info);

				if (isset($format_info['bitrate']))
				{
					$quality = isset($format_info['quality_label']) ? $format_info['quality_label'] : round($format_info['bitrate']/1000).'k';
				}
				else
				{
					$quality =  isset($format_info['quality']) ? $format_info['quality'] : '';
				}

				$type = explode(';', $format_info['type']);

				$formats[] = [
					'itag' => $format_info['itag'],
					'quality' => $quality,
					'type' => $type[0],
					'url' => $format_info['url'],
					'expires' => isset($url_info['expire']) ? date("G:i:s T", $url_info['expire']) : '',
					'ipbits' => isset($url_info['ipbits']) ? $url_info['ipbits'] : '',
					'ip' => isset($url_info['ip']) ? $url_info['ip'] : '',
				];
			}

			return $formats;
		}

		private function getVideoFormatLink($data, $type)
		{
			foreach($data as $k => $v)
			{
				if($v['itag'] == $type)
				{
					return $v;
				}
			}
		}

		private function createDownloadLink()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL , "https://www.youtube.com/get_video_info?video_id=".$this->videoID."&asv=3&el=detailpage&hl=en_US");
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0");
			curl_setopt($ch, CURLOPT_REFERER, "https://www.youtube.com");
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			$infoPage = curl_exec($ch);
			curl_close($ch);
			parse_str($infoPage, $arr);
			//print_pre($arr);

			//imaj yolu tespit edildi
			$videoImageBig 					= 'http://i.ytimg.com/vi/'.$arr['video_id'].'/hq720.jpg';
			$videoImageDefault 				= 'http://i.ytimg.com/vi/'.$arr['video_id'].'/hqdefault.jpg';

			//büyük resim varsa büyük, yoksa normal resmi kullanıyoruz
			$size = getimagesize($videoImageBig);
			if($size[0] > 0)
			{
				$this->videoImage = $videoImageBig;
			}
			else
			{
				$this->videoImage = $videoImageDefault;
			}

			$this->videoKeywords 			= $arr['keywords'];	// set keywords of the video
			$this->videoTitle 				= $arr['title'];	// set title of the video

			$streams = explode(',', $arr['url_encoded_fmt_stream_map']);
			$list_video = $this->parseStreams($streams);

 			//print_pre($list_video);

			if($data == '') $data = $this->getVideoFormatLink($list_video, '22');
			if($data == '') $data = $this->getVideoFormatLink($list_video, '35');
			if($data == '') $data = $this->getVideoFormatLink($list_video, '34');
			if($data == '') $data = $this->getVideoFormatLink($list_video, '18');
			if($data == '') $data = $this->getVideoFormatLink($list_video, '5');
 			//print_pre($data);

			$ext = explode("/", $data['type']);
			$this->videoExtension = ".".$ext[1];

			parse_str(urldecode($data['url']), $url_info);
			//print_pre($url_info);

			if($url_info['signature'] == '')
			{
				//Video eğer youtube şifre algoritması korumalıysa şu siteden indirip kayıt ediyoruz
				$mylink = 'https://api.unblockvideos.com/youtube_downloader?id='.$this->videoID.'&selector=mp4&redirect=true';
			}
			else
			{
				$mylink = $data['url'];
			}
			$this->downloadLink = $mylink;
			$this->curlDownload();
		}

		private function curlDownload()
		{
			//+++ youtube videosunu sisteme kaydedelim
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->downloadLink);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0");
			curl_setopt($ch, CURLOPT_REFERER, "https://www.youtube.com");
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			$data = curl_exec($ch);
			$itime = curl_getinfo($ch);
 			//print_pre($itime);
			print_pre(curl_error($ch));
			curl_close ($ch);
			if($itime['http_code'] <> '200') return '';

			$filename = 'youtube-'.format_url($this->videoTitle).'-'.gen_key(5).$this->videoExtension;
			$destination = VIDEO_DIRECTORY.SUBFOLDER.$filename;
			$file = fopen($destination, "w+");
			fputs($file, $data);
			fclose($file);
			//--- youtube videosunu sisteme kaydedelim

			//değerleri nesneye gönderelim
			$this->videoUrl 		= $destination;
			$this->videoFileName 	= $filename;


			//+++ youtube resmini sisteme kaydedelim
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->videoImage);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0");
			curl_setopt($ch, CURLOPT_REFERER, "https://www.youtube.com");
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			$data = curl_exec ($ch);
			$error = curl_error($ch);
			curl_close ($ch);

			$filename		= format_url($this->videoTitle).'-'.gen_key(5).'.jpg';
			$destination	= IMAGE_DIRECTORY.'content/'.SUBFOLDER.$filename;
			$file = fopen($destination, "w+");
			fputs($file, $data);
			fclose($file);
			//--- youtube resmini sisteme kaydedelim

			//değerleri nesneye gönderelim
			$this->imageUrl 		= $destination;
			$this->imageFileName 	= $filename;
		}
	}

	//Youtube url'sini id ye dönüştürelim
	$filter = myReq('filter');

	//sadece form submit edilmişse işlemi yapıyoruz
	if($filter == 1)
	{
		$url 	= myReq('keyword');
		if($url <> "" && strlen($url) < 15)
		{
			$id = trim(strip_tags($url));
		}

		if($url <> "" && strlen($url) > 15)
		{
			$dizi = parse_url($url);
			parse_str($dizi['query'], $arr);
			$id = $arr['v'];
		}

		//id yoksa hiç işleme başlamıyoruz
		if($id <> '')
		{
			$obj = new YouTubeVideoDownloader($id);

			if($obj->imageFileName <> '')
			{
				//varsayılan değerlerimizi atayalım
				$_REQUEST['content_title'] 			= $obj->videoTitle;
				$_REQUEST['content_seo_title'] 		= $obj->videoTitle;
				$_REQUEST['content_seo_url'] 		= $obj->videoTitle;
				$_REQUEST['content_seo_metadesc'] 	= $obj->videoTitle;
				$_REQUEST['content_text'] 			= $obj->videoTitle;
				$_REQUEST['content_tags'] 			= $obj->videoKeywords;
				$_REQUEST['content_image'] 			= $obj->imageFileName;
				$_REQUEST['content_video'] 			= $obj->videoFileName;
				$_REQUEST['content_type'] 			= 0;
				$_REQUEST['content_cat'] 			= 121;
				$_REQUEST['content_status'] 		= 1;
				$_REQUEST['content_comment_status'] = 1;
				$_REQUEST['content_time'] 			= time();

				//içerik ekleyelim ve düzenleyelim
				$_id = $_content->content_add();
				$_content->content_edit($_id);
				//tamam artık,
				//madem yayınladık ilgili içeriğe dönelim
				$uyarilar = 'Video Başarıyla Eklendi. &rarr; <a href="'.LINK_ACP.'&view=content&do=edit&id='.$_id.'"># Düzenle</a>';
				$uyarilar_text = showMessageBoxS($uyarilar, 'error');
			}
			else
			{
				$uyarilar_text = showMessageBoxS('Video korumalı, silinmiş veya farklı bir şekilde hatalı olabilir.', 'error');
			}
		}
		else
		{
			if(myReq('filter') == 1)
			{
				$uyarilar_text = showMessageBoxS('İd Hatalı!', 'error');
			}
		}
	}
?>
	<?=$uyarilar_text?>

	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jMetro/jquery-ui.css">
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.css"/>
	<script src="<?=G_VENDOR_JQUERY?>jUi/jquery-ui.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.js"></script>
	<script>$(function() { $('#time').datetimepicker({lang:'tr', timepicker:false, format:'Y-m-d'}); }); </script>

	<div class="box box-info">

		<form action="<?=LINK_ACP?>" name="form1" method="get">
			<input type="hidden" name="page" value="acp"/>
			<input type="hidden" name="view" value="<?=$view?>"/>
			<input type="hidden" name="do" value="<?=$do?>"/>
			<input type="hidden" name="filter" value="1"/>

			<div class="box-header">
				<h3 class="box-title">Youtube'dan Aktar</h3>
			</div>
			<div class="box-body" style="height: 60px;">
				<div class="input-group col-md-11" style="float:left;">
					<input placeholder="Youtube ID veya Youtube Linki" class="form-control" type="text" id="keyword" name="keyword" value="<?=$keyword?>"/>
				</div>
				<div class="input-group col-md-1" style="float:right; text-align:right;">
					<button class="btn btn-success" style="width:100%">Aktar</button>
				</div>
			</div>
		</form>
		<div class="input-group col-md-12 clearMe"></div>
	</div>
