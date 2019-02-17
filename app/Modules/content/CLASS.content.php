<?php

	class content
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function content_list_manset(
			$page 		= 1,
			$limit 		= 10,
			$type 		= 'none',
			$cat 		= 'none',
			$etiket 	= 'none',
			$json 		= 0
		)
		{
			/**
			* İçerik datalarını listeleyen fonksiyonumuz
			* Etiket, Kategori veya İçerik Tipine göre listeleme yapar
			*/

			if($type <> 'none')					$sql_type 		= 'AND content_type = '.$type;
			if($cat <> 'none') 					$sql_cat 		= 'AND (content_cat IN('.$cat.') OR content_cat2 IN('.$cat.') OR content_cat3 IN('.$cat.'))';
			if($etiket <> 'none') 				$sql_etiket 	= 'AND content_tags LIKE "%'.$etiket.'%"';

			if($_SESSION[SES]['ADMIN'] <> 1) 	$sql_admin		= 'AND content_time < now()';
			if($page == 0) $page = 1;

			$sql = 'SELECT
						content_id,
						content_title,
						content_time,
						content_view,
						content_url,
						content_image_url,
						content_thumb_url
					FROM
						'.T_CONTENT.','.T_VIEW.'
					WHERE
						'.T_CONTENT.'.content_id = '.T_VIEW.'.id
					AND
						content_status = 1
						'.$sql_admin.'
						'.$sql_type.'
						'.$sql_cat.'
						'.$sql_etiket.'
					ORDER BY
						content_time DESC
					LIMIT
						'.(($page-1)*$limit).','.$limit;
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url'] 			= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_image_url']		= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['content_thumb_url'] 		= G_IMGLINK.$list[$i]['content_thumb_url'];
				$list[$i]['content_time_f']			= timeConvert($list[$i]['content_time']);
				$list[$i]['content_view_f']			= nice_number($list[$i]['content_view']);
			}

			if($json == 0) return $list;

			if($json == 1)
			{
				/**
				| Json datayı uygulamaya gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				for($i = 0; $i < $adet; $i++)
				{
					//$list[$i]['content_title'] = null;
					unset
					(
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}


		public function content_list_manset_pages($limit = 10, $type = 'none', $cat = 'none', $etiket = 'none')
		{
			if($type <> 'none')					$sql_type 		= 'AND content_type = '.$type;
			if($cat <> 'none') 					$sql_cat 		= 'AND (content_cat IN('.$cat.') OR content_cat2 IN('.$cat.') OR content_cat3 IN('.$cat.'))';
			if($etiket <> 'none') 				$sql_etiket 	= 'AND content_tags LIKE "%'.$etiket.'%"';

			if($_SESSION[SES]['ADMIN'] <> 1) 	$sql_admin		= 'AND content_time < now()';
			$sql = 'SELECT
						count(content_id)
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
						'.$sql_admin.'
						'.$sql_type.'
						'.$sql_cat.'
						'.$sql_etiket.'
						;';
			if(memcached == 0) $adet = $this->conn->GetOne($sql);
			if(memcached == 1) $adet = $this->conn->CacheGetOne(cachetime, $sql);
			$madet = $adet/$limit;
			return intval($madet);
		}


		public function content_most_view($limit = 10, $json = 0)
		{
			$sql = 'SELECT
						content_id,
						ABS
						(
							timestampdiff(DAY, now(), content_time)
						) as ago,
						(
							content_view_real/ABS(timestampdiff(DAY, now(), content_time))
						) as ortalama,
						content_title,
						content_time,
						content_view,
						content_view_real,
						content_url,
						content_image_url,
						content_thumb_url
					FROM
						'.T_CONTENT.','.T_VIEW.'
					WHERE
						'.T_CONTENT.'.content_id = '.T_VIEW.'.id
					AND
						content_status = 1
					AND
						content_time < now()
					ORDER BY
						ortalama
					DESC
						LIMIT 0,'.$limit;

			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime*5, $sql);

			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
 					$list[$i]['content_url'] 			= SITELINK.$list[$i]['content_url'];
					$list[$i]['content_image_url']		= G_IMGLINK.$list[$i]['content_image_url'];
					$list[$i]['content_thumb_url'] 		= G_IMGLINK.$list[$i]['content_thumb_url'];
					$list[$i]['content_time_f']			= timeConvert($list[$i]['content_time']);
					$list[$i]['content_view_f']			= nice_number($list[$i]['content_view']);
				}
				if($json == 0) return $list;
			}
			else
			{
				//hiçbir sonuç yoksa false dönelim, yönetilmesi daha kolay oluyor
				return false;
			}
		}

		public function content_list_search($keyword = '', $limit, $json = 0)
		{
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			if(strlen($keyword) < 3) return false;

			if($keyword	<> '')
			{
				$keyword = addslashes(htmlspecialchars(strip_tags($keyword)));
				$sql_keyword	= ' AND
				(
					content_title LIKE "%'.$keyword.'%"
					OR
					content_text LIKE "%'.$keyword.'%"
					OR
					content_seo_title LIKE "%'.$keyword.'%"
					OR
					content_tags LIKE "%'.$keyword.'%"
				)';
			}

			$sql = 'SELECT
						content_id,
						content_title,
						content_seo_url,
						content_time,
						content_view,
						content_view_real,
						content_url,
						content_image_url,
						content_thumb_url
					FROM
						'.T_CONTENT.','.T_VIEW.'
					WHERE
						'.T_CONTENT.'.content_id = '.T_VIEW.'.id
					AND
						content_status = 1
						'.$sql_admin.'
						'.$sql_keyword.'
					ORDER BY
						content_time DESC
					LIMIT 0, '.$limit;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
 					$list[$i]['content_url'] 			= SITELINK.$list[$i]['content_url'];
					$list[$i]['content_image_url']		= G_IMGLINK.$list[$i]['content_image_url'];
					$list[$i]['content_thumb_url'] 		= G_IMGLINK.$list[$i]['content_thumb_url'];
					$list[$i]['content_time_f']			= timeConvert($list[$i]['content_time']);
					$list[$i]['content_view_f']			= nice_number($list[$i]['content_view']);
				}
				if($json == 0) return $list;

				if($json == 1)
				{
					/**
					| Json datayı uygulamaya gönderirken kullanıyoruz
					| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
					*/

					for($i = 0; $i < $adet; $i++)
					{
						//$list[$i]['content_title'] = null;
						unset
						(
							$list[$i]['content_image_dir'],
							$list[$i]['content_seo_url'],
							$list[$i]['content_image'],
							$list[$i]['xxx']
						);
					}
					return $list;
				}

			}
			else
			{
				return false;
			}
		}

		public function content_list_benzer($cat, $_id, $limit=3)
		{
			/**
			| İçerik detayındaki
			| Benzer Yazılar bölümünü oluşturur
			*/
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_title,
						content_url,
						content_image_url,
						content_thumb_url,
						content_view
					FROM
						'.T_CONTENT.','.T_VIEW.'
					WHERE
						'.T_CONTENT.'.content_id = '.T_VIEW.'.id
					AND
						content_status = 1
					AND
						content_id <> '.$_id.'
						'.$sql_admin.'
					AND
					( content_cat = '.$cat.' OR content_cat2 = '.$cat.' OR content_cat3 = '.$cat.' )
					ORDER BY
						rand()
					LIMIT
						0,'.$limit;
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url'] 			= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_image_url']		= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['content_thumb_url'] 		= G_IMGLINK.$list[$i]['content_thumb_url'];
				$list[$i]['content_time_f']			= timeConvert($list[$i]['content_time']);
				$list[$i]['content_view_f']			= nice_number($list[$i]['content_view']);
			}
			return $list;
		}

		public function content_truncate_manset()
		{
			/**
			|Ana Manşet türündeki içeriklerin
			|15'inciden sonrasını normal içerik'e dönüştürür
			|
			|Önce İlgili içeriklere ait ID değerlerini alıyoruz
			|Sonra bu değerleri sql ile normal içerik haline getiriyoruz
			*/
			global $config;

			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
						AND content_time < now()
						AND content_type = 1
					ORDER BY
						content_time DESC
					LIMIT
						'.$config['index_manset_main'].',100';
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$record = array('content_type' 		=> 0);
					$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$list[$i]['content_id']);
					if($rs == false)
					{
						throw new Exception($this->conn->ErrorMsg());
					}
				}
			}
		}

		//cronlanmış görevleri yapalım
		public function get_cron()
		{
			//datalarımızın yerelde tutulacağı klasör yolu
			$url_local = "cache/cron.txt";

			if(filesize($url_local) == 0) unlink($url_local);

			if(file_exists($url_local) && ((filemtime($url_local)+3600) > time()))
			{
				//dosya var
			}
			else
			{
				$this->content_truncate_manset();
				$data = 'true';
				file_put_contents($url_local, $data);
			}
		}

		public function adet_etiketsiz($user = 0)
		{
			if($user <> 0)
			{
				$ek_sql = ' AND content_user = '.$user;
			}

			$sql = 'SELECT
						count(content_id)
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_time > "2016-01-01"
						'.$ek_sql.'
					AND
						content_tags = "";';
			return $this->conn->GetOne($sql);
		}

		public function listFolderFiles($dir)
		{
			global $a;
			$ffs = scandir($dir);
			$i = 0;
			$list = array();
			foreach($ffs as $ff)
			{
				if ($ff != '.' && $ff != '..' )
				{
					if(is_file(realpath($dir).'/'.$ff))
					{
						$a.=realpath($dir).'/'.$ff.'|';
					}

					if(is_dir($dir.'/'.$ff))
					{
						$this->listFolderFiles($dir.'/'.$ff);
					}
				}
			}
			//print_pre($list);
			return $a;
		}

		public function clean_cache()
		{
			/**
			| Eski tarihli pasif içerikleri otomatik silelim
			*/
			$time = date('Y-m-d 00:00:00',strtotime("-2 day"));
			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 0
					AND
						create_time < "'.$time.'"';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$this->content_delete($v['content_id']);
			}
			return true;
		}

		public function content_add($user = '')
		{
			//user zorlanması talep edilmişse bu seçenek işimize yarıyor
			//mesela botlardan gelen haberleri billi bir kullanıcı ile ilişkilendiriyoruz
			if($user == '') 					$user = $_SESSION[SES]['user_id'];

			//normalde daima reklam gösterilsin
			$content_ads_status = 1;

			//tarihi ve image dir otomatik biz atayalım
			$content_image_dir 	= date('Y/m/d/');
			$content_time 		= date("Y-m-d H:i:s");

			//önce eski dataları silelim
			self::clean_cache();

			$record = array(
				'content_type'					=> 0,
				'content_cat'					=> 0,
				'content_time'					=> $content_time,
				'content_ads_status'			=> $content_ads_status,
				'content_user'					=> $user,
				'content_image_dir'				=> $content_image_dir
			);
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'INSERT');
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			$content_id = $this->conn->Insert_ID();

			//içerik view tablosuna ekleniyor
			$record = array(
				'id' => $content_id
			);
			$rs = $this->conn->AutoExecute(T_VIEW, $record, 'INSERT');

			return $content_id;
		}

		public function content_edit($_id)
		{
			global $array_content_type_image_wh;

			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim($v);

 			$_REQUEST['content_tags'] = tr_strtolower($_REQUEST['content_tags']);

			//botlardan gelen içerikte resim kontrolünü bypas ediyoruz
			if($_REQUEST['content_check_images'] <> 1)
			{
				//içerik resmi yok ise taslak
				//etiket yok ise taslak
				if($_REQUEST['content_image'] == '') 	$_REQUEST['content_status'] = 2;
				if($_REQUEST['content_tags'] == '') 	$_REQUEST['content_status'] = 2;

				//resim varsa resim boyutlarını kontrol ediyoruz
				//Boyutlar hatalı ise taslak olarak kaydediyoruz
				if($_REQUEST['content_image'] <> '')
				{
					$image_sizes = getimagesize(IMAGE_DIRECTORY.'content/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image']);
					if($image_sizes[0] <> $array_content_type_image_wh['w']) $_REQUEST['content_status'] = 2;
					if($image_sizes[1] <> $array_content_type_image_wh['h']) $_REQUEST['content_status'] = 2;
				}
			}

			//video varsa videonun süresini hesaplıyoruz
			//google a süresini bildirmek için kullanıyoruz
			if($_REQUEST['content_video'] <> '')
			{
				//video süresini burada hesaplıyoruz
				require_once(SITEPATH.'vendors/getid3/getid3.php');
				$getID3 = new getID3;
				$info = $getID3->analyze(VIDEO_DIRECTORY.$_REQUEST['content_image_dir'].$_REQUEST['content_video']);
				//print_pre($info);
				$_REQUEST['content_duration'] 	= $info['playtime_seconds'];
				$_REQUEST['content_video_url'] 	= $_REQUEST['content_image_dir'].$_REQUEST['content_video'];
			}

			if($_REQUEST['content_ads_status'] == 'on') 		$_REQUEST['content_ads_status'] = 0;
			if($_REQUEST['content_ads_status'] <> 'on') 		$_REQUEST['content_ads_status'] = 1;

			if(RC_DetailedSeo == 0)
			{
				$_REQUEST['content_seo_title'] = $_REQUEST['content_seo_url'] = $_REQUEST['content_seo_metadesc'] = $_REQUEST['content_title'];
			}
			else
			{
				if($_REQUEST['content_seo_title'] == '' ) 		$_REQUEST['content_seo_title']		= $_REQUEST['content_title'];
				if($_REQUEST['content_seo_url'] == '' ) 		$_REQUEST['content_seo_url']		= $_REQUEST['content_title'];
				if($_REQUEST['content_seo_metadesc'] == '' ) 	$_REQUEST['content_seo_metadesc']	= $_REQUEST['content_title'];
			}

			if($_REQUEST['content_image'] <> '')
			{
				$_REQUEST['content_image_url']		= 'content/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image'];
				$_REQUEST['content_thumb_url']		= 'thumbs/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image'];
			}
			$_REQUEST['content_url']				= self::url_content_inline($_REQUEST['content_seo_title'], $_id);

			//etikette nokta, apostrof yer alamaz
			$_REQUEST['content_tags'] = str_replace(array('.','"','\''),'', $_REQUEST['content_tags']);

			$record = array(
				'content_title'					=> $_REQUEST['content_title'],
				'content_text'					=> $_REQUEST['content_text'],
				'content_video'					=> $_REQUEST['content_video'],
				'content_tags'					=> $_REQUEST['content_tags'],
				'content_type'					=> $_REQUEST['content_type'],
				//cat
				'content_cat'					=> $_REQUEST['content_cat'],
				'content_cat2'					=> $_REQUEST['content_cat2'],
				'content_cat3'					=> $_REQUEST['content_cat3'],
				//seo
				'content_seo_title'				=> $_REQUEST['content_seo_title'],
				'content_seo_url'				=> $_REQUEST['content_seo_url'],
				'content_seo_metadesc'			=> $_REQUEST['content_seo_metadesc'],
				//
				'content_status'				=> $_REQUEST['content_status'],
				'content_comment_status'		=> $_REQUEST['content_comment_status'],
				'content_ads_status'			=> $_REQUEST['content_ads_status'],
				//
				'content_time'					=> $_REQUEST['content_time'],
				'content_duration'				=> $_REQUEST['content_duration'],
				//
				'content_url'					=> $_REQUEST['content_url'],
				'content_image'					=> $_REQUEST['content_image'],
				'content_image_url'				=> $_REQUEST['content_image_url'],
				'content_thumb_url'				=> $_REQUEST['content_thumb_url'],
				'content_video_url'				=> $_REQUEST['content_video_url'],

				//botlarla eklenen içeriklerin orjinal id değerlerini tutuyor, o sebeple önemli
				'content_redirect'				=> $_REQUEST['content_redirect'],
				//son değişiklik tarihi, güncellenme tarihi olarak kullanabiliriz
				'change_time'					=> time(),

			);
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			//ajans botu ise ve otomatik olarak yayına atmaya çalışmıyorsa
			if($_REQUEST['content_status'] == 1 && $list[0]['content_user'] == 44)
			{
				$record = array('content_user'	=> $_SESSION[SES]['user_id']);
				$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
			}

			//içerik yayında ise ve durumu aktif ise pinglemeyi deneyelim
			if((ST_ONLINE == 1 && $_REQUEST['content_status'] == 1) OR ($_REQUEST['force_ping'] == 'on') )
			{
				$list = self::content_detail($_id);

				//içerik googla gitmemişse veya pinglenmesi isteniyorsa pingleyelim
				if($list[0]['content_google_status'] == 0 or $_REQUEST['force_ping'] == 'on')
				{
					ping_it($list[0]['content_url']);
				}
			}
		}

		/**
		| Standart 4 İşlem; Ekle, Düzenle, Sil, Listele
		*/

		public function content_delete($_id)
		{
			//image_dir değerini bulalım
			$content_image_dir = self::get_image_dir($_id);

			//içerik resmini sil
 			self::content_delete_content_image($_id, $content_image_dir);

			//içerik video sil
 			self::content_delete_content_video($_id, $content_image_dir);

			//okunma sayılarını siliyoruz
			//İstatistik şaşmaması açısından, içerik silsek de okunma sayılarını silmiyoruz
			//$sql = 'DELETE FROM '.T_VIEW.' WHERE id= '.$_id;
			//$this->conn->Execute($sql);

 			//en son içeriği sil
			$sql = 'DELETE FROM '.T_CONTENT.' WHERE content_id= '.$_id;
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_delete_soft($_id)
		{
			/**
			| İçeriği silinmiş olarak işaretlemeye yarar
			| Silme işlemi daha sonra yetkili kişi tarafından garbage edilir
			*/
			$record = array('content_status' => 3);
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_truncate()
		{
			/**
			| soft silinmiş veya pre taslak içerikleri siler
			*/
			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_status IN (0,3)';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$this->content_delete($v['content_id']);
			}
			return true;
		}

		/**
		| Haber resimlerini silme fonksiyonları
		*/

		public function get_image_dir($_id)
		{
			//image_dir değerini döndürür
			$sql = 'SELECT
						content_image_dir
					FROM
						'.T_CONTENT.'
					WHERE
						content_id = '.$_id;
			return $this->conn->GetOne($sql);
		}

		public function content_delete_content_image($_id, $content_image_dir)
		{
			$sql = 'SELECT
						content_image
					FROM
						'.T_CONTENT.'
					WHERE
						content_id = '.$_id;
			$file_name = $this->conn->GetOne($sql);

			if($file_name <> '')
			{
				//resmin kendisini sil
				@unlink(IMAGE_DIRECTORY.'content/'.$content_image_dir.$file_name);
				//resmin küçük halini sil
				@unlink(IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$file_name);
			}

			$record['content_image'] = '';
			$this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
		}

		public function content_delete_content_video($_id, $content_image_dir)
		{
			$sql = 'SELECT
						content_video
					FROM
						'.T_CONTENT.'
					WHERE
						content_id = '.$_id;
			$file_name = $this->conn->GetOne($sql);

			if($file_name <> '')
			{
				@unlink(VIDEO_DIRECTORY.$content_image_dir.$file_name);
			}

			$record['content_video'] = '';
			$this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
		}

		/**
		| Çeşitli Listeleme Fonksiyonları
		*/

		public function content_list($_id = 0)
		{
			/**
			| En yavaş çalışan versiyondur
			| tüm dataları çevirir
			| bu datalara içerik metinleri de dahildir
			*/
			$sql = 'SELECT
						*
					FROM '.T_CONTENT;

			if($_id <> 0)
			{
				$sql = $sql.' WHERE content_id = '.$_id;
			}
			return $this->conn->GetAll($sql);
		}

		public function content_detail($_id, $json = 0)
		{
			/**
			| İlgili içeriğe ait tüm dataları getirir
			*/
			global $array_cat_name, $array_cat_url;

			$sql = 'SELECT
						*,
						DATE_FORMAT(content_time,"%d.%m.%Y %H:%i") AS publish_time
					FROM
						'.T_CONTENT.','.T_VIEW.'
					WHERE
						'.T_CONTENT.'.content_id = '.T_VIEW.'.id
					AND
						content_id = '.$_id;

			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$list[0]['content_url'] 			= SITELINK.$list[0]['content_url'];
			$list[0]['content_image_url']		= G_IMGLINK.$list[0]['content_image_url'];
			$list[0]['content_thumb_url']		= G_IMGLINK.$list[0]['content_thumb_url'];
			$list[0]['content_video_url']		= G_VIDEOLINK.$list[0]['content_video_url'];
			$list[0]['content_embed_url'] 		= SITELINK.'embed/'.$list[0]['content_id'];
			$list[0]['content_html5_embed_url'] = SITELINK.'html5-embed/'.$list[0]['content_id'];
			$list[0]['content_cat_name']		= $array_cat_name[$list[0]['content_cat']];
			$list[0]['content_cat_url']			= $array_cat_url[$list[0]['content_cat']];
			$list[0]['content_time_f']			= timeConvert($list[0]['content_time']);
			$list[0]['content_view_f']			= nice_number($list[0]['content_view']);

			//eğer içerik google'a eklenmemiş ise
			//google arama motoru ziyaret ettimi diye kontrol ediyoruz
			if($list[0]['content_google_status'] == 0)
			{
				self::is_google($_id);
			}

			if($json == 0) return $list;

			if($json == 1)
			{

				if($list[0]['content_title'] == '') return false;
				/**
				| Json datayı tablet ve phone uygulamasına gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				$list[0]['content_text'] = str_replace("assets/",SITELINK."assets/",$list[0]['content_text']);
				$list[0]['content_desc'] = n2br($list[0]['content_text']);
				$list[0]['content_text'] = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<link href="'.G_CSSLINK.'app_001.css" rel="stylesheet" media="screen"/></head>
<body>
<div class="contentDetail" id="contentDetail">
<h1>'.$list[0]['content_title'].'</h1>
<p>'.$list[0]['content_text'].'</p>
<iframe
width="300"
height="200" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"
allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen
src="'.SITELINK.'html5-embed/'.$list[0]['content_id'].'">
</iframe>
</div>
</body>
</html>';
				unset
				(
					$list[0]['content_user'],
					//$list[0]['content_view'],
					$list[0]['content_view_real'],
					$list[0]['content_status'],
					$list[0]['content_tags'],
					$list[0]['content_type'],
					$list[0]['content_video'],
					$list[0]['content_image'],
					$list[0]['content_image_dir'],
					$list[0]['content_image_url'],
					$list[0]['content_seo_title'],
					$list[0]['content_seo_url'],
					$list[0]['content_seo_metadesc'],
					$list[0]['content_time'],
					$list[0]['content_cat_name'],
					$list[0]['content_cat_url'],
					$list[0]['create_time'],
					$list[0]['change_time'],
					$list[0]['publish_time'],
					$list[0]['content_cat2'],
					$list[0]['content_cat3'],
					$list[0]['content_redirect'],
					$list[0]['content_google_status'],
					$list[0]['content_comment_status'],
					$list[0]['id'],
					$list[0]['xxx']
				);
				return $list;
			}
		}

		public function content_list_small($type = '-1', $cat = '-1', $user = '-1', $status = '-1', $time = '', $keyword = '', $limit = 30)
		{
			/**
			| Daha basit bir içerik listesi çevirir
			| Yönetim panelinde Haberleri listelerken kullanıyoruz
			*/
			$sql_status		= ' AND content_status IN (1,2)';
			if($status	<> '-1')	$sql_status		= ' AND content_status = '.$status;
			if($type 	<> '-1') 	$sql_type 		= ' AND content_type = '.$type;
			if($cat		<> '-1') 	$sql_cat 		= ' AND content_cat = '.$cat;
			if($user	<> '-1')	$sql_user 		= ' AND content_user = '.$user;
			if($time	<> '')		$sql_time		= ' AND content_time LIKE "'.$time.'%"';
			if($keyword	<> '')		$sql_keyword	= ' AND
			(
				content_text LIKE "%'.$keyword.'%"
				OR
				content_seo_title LIKE "%'.$keyword.'%"
				OR
				content_tags LIKE "%'.$keyword.'%"
			)';

			if($limit > 0) 	$sql_limit	= 'LIMIT 0,'.$limit;

			$sql = '
				SELECT
					content_id,
					content_title,
					content_image,
					content_video,
					content_seo_title,
					content_seo_url,
					content_seo_metadesc,
					content_status,
					content_cat,
					content_cat2,
					content_cat3,
					content_type,
					content_user,
					content_time,
					content_tags,
					content_image_dir,
					content_view,
					content_view_real,
					content_comment_status,
					content_url,
					content_google_status,
					date_format(content_time, "%Y.%m.%d %H.%i") AS content_time_f,
					DATE_FORMAT(content_time,"%Y-%m-%d") AS content_date,
					content_view
				FROM
					'.T_CONTENT.','.T_VIEW.'
				WHERE
					'.T_CONTENT.'.content_id = '.T_VIEW.'.id
				AND
					content_status IN (1,2,3,4)
					'.$sql_type.'
					'.$sql_cat.'
					'.$sql_user.'
					'.$sql_status.'
					'.$sql_time.'
					'.$sql_keyword.'
				ORDER BY
					content_id DESC
					'.$sql_limit;
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['content_url'] = SITELINK.$list[$i]['content_url'];
				}
				return $list;
			}
			else
			{
				//hiçbir sonuç yoksa false dönelim, yönetilmesi daha kolay oluyor
				return false;
			}
		}

		public function content_list_rss($limit)
		{
			$sql = 'SELECT
						content_id,
						content_title,
						content_text,
						content_url,
						content_thumb_url,
						content_image_url,
						content_time,
						DATE_FORMAT(content_time,"%Y-%m-%d") AS content_date,
						DATE_FORMAT(content_time,"%H:%i:%s") AS content_hours
					FROM
						'.T_CONTENT.'
					WHERE
						content_time < now()
					AND
						content_status = 1
					ORDER BY
						content_time DESC
					LIMIT 0,'.$limit;
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
				$list[$i]['content_image_url']	= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['changetar']			= pco_format_date(strtotime($list[$i]['content_time']));
			}
			return $list;
		}

		public function content_list_sitemap($limit,$type)
		{
			if($type == 0)
			{
				$sql = '
					SELECT
						content_id,
						content_title,
						content_seo_url,
						content_image_dir,
						content_image,
						content_url,
						content_thumb_url,
						content_image_url,
						DATE_FORMAT(content_time,"%Y-%m-%d") AS content_date,
						DATE_FORMAT(content_time,"%H:%i:%s") AS content_hours
					FROM
						'.T_CONTENT.'
					WHERE
						content_time < now()
					AND
						content_status = 1
					ORDER BY
						content_time DESC
					LIMIT 0,'.$limit;
			}
			if($type == 1)
			{
				$sql = 'SELECT
						content_id,
						content_title,
						content_seo_url,
						content_image_dir,
						content_image,
						content_url,
						content_thumb_url,
						content_image_url,
						DATE_FORMAT(change_time,"%Y-%m-%d") AS content_date,
						DATE_FORMAT(change_time,"%H:%i:%s") AS content_hours
					FROM
						'.T_CONTENT.'
					WHERE
						content_time < now()
					AND
						content_status = 1
					ORDER BY
						change_time DESC
					LIMIT 0,'.$limit;
			}
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
				$list[$i]['content_image_url']	= G_IMGLINK.$list[$i]['content_image_url'];
			}
			return $list;
		}

		private function url_content_inline( $title, $id )
		{
			/*
				İnline video linki oluşturur, sonra sitelink ile birleştirerek tam link oluşturulur
			*/
			$url = format_url($title).S_CONTENT.$id.S_CONTENT_EXT;
			return $url;
		}

		public function content_view($_id)
		{
			//her halükarda okunma sayısını artıralım
			$sql = 'UPDATE
						'.T_VIEW.'
					SET
						content_view = (content_view + 1)
					WHERE
						id = '.$_id;
			$rs = $this->conn->Execute($sql);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			//bot değilse gerçek okunma sayısını da artıralım
			if(!$this->is_bot())
			{
				if($_SESSION[SES]["content_view"][$_id] <> 1)
				{
					$sql = 'UPDATE
								'.T_VIEW.'
							SET
								content_view_real = (content_view_real + 1)
							WHERE
								id = '.$_id;
					$rs = $this->conn->Execute($sql);
					if($rs == false)
					{
						throw new Exception($this->conn->ErrorMsg());
					}
					else
					{
						$_SESSION[SES]["content_view"][$_id] = 1;
					}
				}
			}
		}

		/**
		| Arama Motorları
		*/

		public function is_google($_id)
		{
			//google bot gelmiş ise
			//geldi diye işaretleyelim
			//ilerde ping atarken kullanacağız ve
			//google bot gelmiş içerikleri tekrar ping atmayacağız

			$spiders = array(
				"Googlebot",
				"Googlebot-News",
				"Googlebot-Image",
				"Googlebot-Video",
				"Googlebot-Mobile",
				"Googlebot/",
				"Googlebot-Image/",
			);

			foreach($spiders as $spider)
			{
				//If the spider text is found in the current user agent, then return true
				if (stripos($_SERVER['HTTP_USER_AGENT'], $spider) !== false ) $x = true;
			}

			if($x == true )
			{
				$sql = 'UPDATE
							'.T_CONTENT.'
						SET
							content_google_status = 1
						WHERE
							content_id = '.$_id;
				$rs = $this->conn->Execute($sql);
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
			}
		}


		public function is_bot()
		{
			$spiders = array(
				"abot",
				"dbot",
				"ebot",
				"hbot",
				"kbot",
				"lbot",
				"mbot",
				"nbot",
				"obot",
				"pbot",
				"rbot",
				"sbot",
				"tbot",
				"vbot",
				"ybot",
				"zbot",
				"bot.",
				"bot/",
				"_bot",
				".bot",
				"/bot",
				"-bot",
				":bot",
				"(bot",
				"crawl",
				"slurp",
				"spider",
				"seek",
				"accoona",
				"acoon",
				"adressendeutschland",
				"ah-ha.com",
				"ahoy",
				"altavista",
				"ananzi",
				"anthill",
				"appie",
				"arachnophilia",
				"arale",
				"araneo",
				"aranha",
				"architext",
				"aretha",
				"arks",
				"asterias",
				"atlocal",
				"atn",
				"atomz",
				"augurfind",
				"backrub",
				"bannana_bot",
				"baypup",
				"bdfetch",
				"big brother",
				"biglotron",
				"bjaaland",
				"blackwidow",
				"blaiz",
				"blog",
				"blo.",
				"bloodhound",
				"boitho",
				"booch",
				"bradley",
				"butterfly",
				"calif",
				"cassandra",
				"ccubee",
				"cfetch",
				"charlotte",
				"churl",
				"cienciaficcion",
				"cmc",
				"collective",
				"comagent",
				"combine",
				"computingsite",
				"csci",
				"curl",
				"cusco",
				"daumoa",
				"deepindex",
				"delorie",
				"depspid",
				"deweb",
				"die blinde kuh",
				"digger",
				"ditto",
				"dmoz",
				"docomo",
				"download express",
				"dtaagent",
				"dwcp",
				"ebiness",
				"ebingbong",
				"e-collector",
				"ejupiter",
				"emacs-w3 search engine",
				"esther",
				"evliya celebi",
				"ezresult",
				"facebook",
				"falcon",
				"felix ide",
				"ferret",
				"fetchrover",
				"fido",
				"findlinks",
				"fireball",
				"fish search",
				"fouineur",
				"funnelweb",
				"gazz",
				"gcreep",
				"genieknows",
				"getterroboplus",
				"geturl",
				"glx",
				"goforit",
				"golem",
				"grabber",
				"grapnel",
				"gralon",
				"griffon",
				"gromit",
				"grub",
				"gulliver",
				"hamahakki",
				"harvest",
				"havindex",
				"helix",
				"heritrix",
				"hku www octopus",
				"homerweb",
				"htdig",
				"html index",
				"html_analyzer",
				"htmlgobble",
				"hubater",
				"hyper-decontextualizer",
				"ia_archiver",
				"ibm_planetwide",
				"ichiro",
				"iconsurf",
				"iltrovatore",
				"image.kapsi.net",
				"imagelock",
				"incywincy",
				"indexer",
				"infobee",
				"informant",
				"ingrid",
				"inktomisearch.com",
				"inspector web",
				"intelliagent",
				"internet shinchakubin",
				"ip3000",
				"iron33",
				"israeli-search",
				"ivia",
				"jack",
				"jakarta",
				"javabee",
				"jetbot",
				"jumpstation",
				"katipo",
				"kdd-explorer",
				"kilroy",
				"knowledge",
				"kototoi",
				"kretrieve",
				"labelgrabber",
				"lachesis",
				"larbin",
				"legs",
				"libwww",
				"linkalarm",
				"link validator",
				"linkscan",
				"lockon",
				"lwp",
				"lycos",
				"magpie",
				"mantraagent",
				"mapoftheinternet",
				"marvin/",
				"mattie",
				"mediafox",
				"mediapartners",
				"mercator",
				"merzscope",
				"microsoft url control",
				"minirank",
				"miva",
				"mj12",
				"mnogosearch",
				"moget",
				"monster",
				"moose",
				"motor",
				"multitext",
				"muncher",
				"muscatferret",
				"mwd.search",
				"myweb",
				"najdi",
				"nameprotect",
				"nationaldirectory",
				"nazilla",
				"ncsa beta",
				"nec-meshexplorer",
				"nederland.zoek",
				"netcarta webmap engine",
				"netmechanic",
				"netresearchserver",
				"netscoop",
				"newscan-online",
				"nhse",
				"nokia6682/",
				"nomad",
				"noyona",
				"nutch",
				"nzexplorer",
				"objectssearch",
				"occam",
				"omni",
				"open text",
				"openfind",
				"openintelligencedata",
				"orb search",
				"osis-project",
				"pack rat",
				"pageboy",
				"pagebull",
				"page_verifier",
				"panscient",
				"parasite",
				"partnersite",
				"patric",
				"pear.",
				"pegasus",
				"peregrinator",
				"pgp key agent",
				"phantom",
				"phpdig",
				"picosearch",
				"piltdownman",
				"pimptrain",
				"pinpoint",
				"pioneer",
				"piranha",
				"plumtreewebaccessor",
				"pogodak",
				"poirot",
				"pompos",
				"poppelsdorf",
				"poppi",
				"popular iconoclast",
				"psycheclone",
				"publisher",
				"python",
				"rambler",
				"raven search",
				"roach",
				"road runner",
				"roadhouse",
				"robbie",
				"robofox",
				"robot",
				"robozilla",
				"rules",
				"salty",
				"sbider",
				"scooter",
				"scoutjet",
				"scrubby",
				"search.",
				"searchprocess",
				"semanticdiscovery",
				"senrigan",
				"sg-scout",
				"shai'hulud",
				"shark",
				"shopwiki",
				"sidewinder",
				"sift",
				"silk",
				"simmany",
				"site searcher",
				"site valet",
				"sitetech-rover",
				"skymob.com",
				"sleek",
				"smartwit",
				"sna-",
				"snappy",
				"snooper",
				"sohu",
				"speedfind",
				"sphere",
				"sphider",
				"spinner",
				"spyder",
				"steeler/",
				"suke",
				"suntek",
				"supersnooper",
				"surfnomore",
				"sven",
				"sygol",
				"szukacz",
				"tach black widow",
				"tarantula",
				"templeton",
				"/teoma",
				"t-h-u-n-d-e-r-s-t-o-n-e",
				"theophrastus",
				"titan",
				"titin",
				"tkwww",
				"toutatis",
				"t-rex",
				"tutorgig",
				"twiceler",
				"twisted",
				"ucsd",
				"udmsearch",
				"url check",
				"updated",
				"vagabondo",
				"valkyrie",
				"verticrawl",
				"victoria",
				"vision-search",
				"volcano",
				"voyager/",
				"voyager-hc",
				"w3c_validator",
				"w3m2",
				"w3mir",
				"walker",
				"wallpaper",
				"wanderer",
				"wauuu",
				"wavefire",
				"web core",
				"web hopper",
				"web wombat",
				"webbandit",
				"webcatcher",
				"webcopy",
				"webfoot",
				"weblayers",
				"weblinker",
				"weblog monitor",
				"webmirror",
				"webmonkey",
				"webquest",
				"webreaper",
				"websitepulse",
				"websnarf",
				"webstolperer",
				"webvac",
				"webwalk",
				"webwatch",
				"webwombat",
				"webzinger",
				"wget",
				"whizbang",
				"whowhere",
				"wild ferret",
				"worldlight",
				"wwwc",
				"wwwster",
				"xenu",
				"xget",
				"xift",
				"xirq",
				"yandex",
				"yanga",
				"yeti",
				"yodao",
				"zao/",
				"zippp",
				"zyborg",
				//google
				"Googlebot",
				"Googlebot-News",
				"Googlebot-Image",
				"Googlebot-Video",
				"Googlebot-Mobile",
				"Google-HTTP-Java-Client/",
				//bing
				"bingbot",
				//yandex
				"YandexBot",
				"YandexMetrika",
				"YandexMobileBot",
				"YandexBlogs",
				"YandexMedia",
				"YandexSomething",
				//sosyal medya
				"Twitterbot",
				"facebookexternalhit/",
				//diğer zararlılar
				"ahrefs",
				"Applebot",
				"MetaURI",
				"ShowyouBot",
				"RSSOwl",
				"MJ12bot",
				"DoCoMo",
				"ichiro",
				"moget",
				"NaverBot",
				"Baiduspider",
				"Baiduspider-video",
				"Baiduspider-image",
				"Sogou-Test-Spider",
				"Sogou",
				"sogou",
				"YoudaoBot",
				"YoudaoBot-Image",
				"Majestic-SEO",
				"DeuSu",
				"okhttp",
				"Moreover",
				"PaperLiBot",
				"GrapeshotCrawler",
				"LivelapBot",
				"Plukkie",
				"link_thumbnailer",
				"PicoFeed",
				"Nuzzel",
				"istellabot",
				"aria2",
				"SocialRankIOBot",
			);

			foreach($spiders as $spider)
			{
				//If the spider text is found in the current user agent, then return true
				if ( stripos($_SERVER['HTTP_USER_AGENT'], $spider) !== false ) return true;
			}
			//If it gets this far then no bot was found!
			return false;
		}

		public function get_content_bot_guid($guid)
		{
			if($guid == "") return false;

			$sql = 'SELECT
						count(content_id)
					FROM
						'.T_CONTENT.'
					WHERE
						content_redirect = "'.$guid.'"';
			$rs = $this->conn->GetOne($sql);
			if($rs == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function content_delete_from_guid($guid)
		{
			//botların eklediği aynı id ye sahip içerikleri siliyoruz
			if($guid == '') return false;

			//sonrasında içeriği silelim
			$sql = 'DELETE
						FROM '.T_CONTENT.'
					WHERE
						content_status = 4
					AND
						content_redirect= "'.$guid.'";';
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_list_bot_bakim()
		{
			//datalarımızın yerelde tutulacağı klasör yolu
			$url_local = "cache/cron_bot_bakim.txt";

			if(filesize($url_local) == 0) unlink($url_local);

			if(file_exists($url_local) && ((filemtime($url_local)+3600) > time()))
			{
				//dosya var
			}
			else
			{
				$time = date('Y-m-d 00:00:00',strtotime("-2 day"));
				$sql = 'SELECT
							content_id
						FROM
							'.T_CONTENT.'
						WHERE
							content_status = 4
						AND
							content_time < "'.$time.'"';
				$list = $this->conn->GetAll($sql);
				$adet = count($list);
				for($i = 0; $i < $adet; $i++)
				{
					//$this->content_delete_soft($list[$i]['content_id']);
					$this->content_delete($list[$i]['content_id']);
				}
				return $list;
			}
		}

		public function content_ads_status($_id)
		{
			/**
			| İlgili içeriğe ait tüm dataları getirir
			 */
			$sql = 'SELECT
						content_ads_status
					FROM
						'.T_CONTENT.'
					WHERE
						content_id = '.$_id;
			if(memcached == 0) $rs = $this->conn->GetOne($sql);
			if(memcached == 1) $rs = $this->conn->CacheGetOne(cachetime, $sql);

			//sonucu dönelim
			return $rs;
		}

	}
