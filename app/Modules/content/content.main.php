<?php
	if(!defined('APP')) die('...');

	unset($array_content_status[0]);
	//unset($array_content_status[4]);

	//truncate işlemi istenmiş ise
	if($action == 'truncate')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'none';

		//yetki denetimi
		if($_auth['content_'.$action] <> '1') $hata_truncate = '1';
		if($hata_truncate <> '1')
		{
			try
			{
				$_content->content_truncate();
				$message = $messages['info_truncate_passive_content'];
			}
			catch(Exception $e)
			{
				//demek ki ekleme işlemi sırasında bir hata oluştur
									$message 			= $messages['error_delete'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}

	//update işlemi istenmiş ise
	if($action == 'edit')
	{
		$list					= $_content->content_list($_id);
		$content_type			= $list[0]['content_type'];
		$content_user			= $list[0]['content_user'];
		$content_cat			= $list[0]['content_cat'];
		$content_image_dir		= $list[0]['content_image_dir'];

		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do			= 'edit';
		$hata_edit	= '';

		//sonrasında yetki denetimini gerçekleştiriyoruz
		//
		//içerik düzenleme yetkisine sahip mi diye bakıyoruz
		//ve düzenlemek istediği yetkiyi ayışıp aşmadığına bakıyoruz
		if($_REQUEST['save_action'] == 'publish')	$_REQUEST['content_status'] = 1;
		if($_REQUEST['save_action'] == 'draft')		$_REQUEST['content_status'] = 2;

		if($_auth['content_'.$action] <> '1') $hata_edit = '1';

		if($hata_edit <> '1')
		{
			try
			{
				//varolan imajlar kaldırılmak istenmişse
				//işlemin bu kısmı gerçekleşiyor
				//lakin bu işlem için ilave bir yetkilendirme denetimi yapılmıyor
				//düzenleme yetkisinin varolması yeterli bulunuyor
				if($_REQUEST['delete_content_image'] == 'on')
				{
					$_content->content_delete_content_image($_id, $content_image_dir);
					$_REQUEST['org_content_image'] = '';
				}

				if($_REQUEST['delete_content_video'] == 'on')
				{
					$_content->content_delete_content_video($_id, $content_image_dir);
					$_REQUEST['org_content_video'] = '';
				}

				if($_FILES['content_image']['size'])
				{
					if(!in_array($_FILES['content_image']['type'], $allowed_image_types))
					{
						$uyarilar.= 'İç resim için yüklemek istediğiniz dosya tipi uygun değil: '.$_FILES['content_image']['type'].'<br/>';
					}
					else
					{
						$posNokta	= strrpos(basename($_FILES['content_image']['name']), '.');
						$fileName	= substr(basename($_FILES['content_image']['name']), 0, $posNokta);
						$fileExt	= strtolower(substr(basename($_FILES['content_image']['name']), $posNokta+1));

						$dosyaAdi	= 'content_'.format_url($_REQUEST['content_title']).'_'.gen_key(15).'.'.$fileExt;
						$im_big 	= IMAGE_DIRECTORY.'content/'.$content_image_dir.$dosyaAdi;
						$im_small	= IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$dosyaAdi;

						$_image->load($_FILES['content_image']['tmp_name']);

						//resmin orjinal halini kayıt altına alalım
						$_image->save_new(
							$_FILES['content_image']['tmp_name'],
							$im_big,
							IMAGE_DIRECTORY.'content/'.$content_image_dir
						);

						//resmin thumbs versiyonunu oluşturuyoruz
						if(RC_Imagemagick <> 1)
						{
							include $_SERVER['DOCUMENT_ROOT'].'vendors/classes/class.thumbnail.php';
							$tn_image = new Thumbnail($im_big, 350, 0, 100);
							$tn_image->save($im_small);
						}
						else
						{
							$im = new \Imagick(realpath($im_big));

							//düz yöntemde resmi yeni boyutuna küçültüyoruz
							$im->thumbnailImage(350, null, false);

							//resmi tekrar yerine yazıyoruz
							$im->writeImage($im_small);
						}

						//sonrasındaki eski imajı silelim
						$_content->content_delete_content_image($_id, $content_image_dir);

						//dosya ismimiz belli oldu
						$_REQUEST['content_image'] = $dosyaAdi;
					}
				}
				else
				{
					//eski resim olduğu gibi kullanılması istenmiş ise
					$_REQUEST['content_image'] = $_REQUEST['org_content_image'];
				}

				//aynı işlemi manşet resmi için de yapıyoruz
				if($_FILES['content_video']['size'])
				{
					if(!in_array($_FILES['content_video']['type'], $allowed_video_types))
					{
						$uyarilar.= 'Eklemek istediğiniz video tipi uygun değil: '.$_FILES['content_video']['type'].'<br/>';
					}
					else
					{
						$posNokta = strrpos(basename($_FILES['content_video']['name']), '.');
						$fileName = substr(basename($_FILES['content_video']['name']), 0, $posNokta);
						$fileExt = strtolower(substr(basename($_FILES['content_video']['name']), $posNokta+1));

						$dosyaAdi = 'video_'.format_url($_REQUEST['content_title']).'_'.gen_key(15).'.'.$fileExt;

						//resmi orjinal haliyle kayıt altına alıyoruz
						$_image->save_new(
							$_FILES['content_video']['tmp_name'],
							VIDEO_DIRECTORY.$content_image_dir.$dosyaAdi,
							VIDEO_DIRECTORY.$content_image_dir
						);

						//sonrasındaki eski imajı silelim
						$_content->content_delete_content_video($_id, $content_image_dir);

						//dosya ismimiz belli oldu
						$_REQUEST['content_video'] = $dosyaAdi;
					}
				}
				else
				{
					//eski resim olduğu gibi kullanılması istenmiş ise
					$_REQUEST['content_video'] = $_REQUEST['org_content_video'];
				}

				//içerik eklenmek isteniyor ise
				//önce bir tane boş içerik ekliyoruz
				//sonra, dönen id değerine göre düzenleme modunda içerikyı açıyoruz
				$_content->content_edit($_id);

				//uyarı mesajı
				$message			= $messages['info_edit'];
				if($uyarilar <> '') $uyarilar_text = showMessageBoxS($uyarilar, 'error');
			}
			catch(Exception $e)
			{
				//demek ki ekleme işlemi sırasında bir hata oluştur
									$message 			= $messages['error_edit'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
			$do = 'list';
		}
	}

	//delete işlemi istenmiş ise
	if($action == 'delete')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'none';

		//içerik durumu için ek sorgu gönderiyoruz
		$list					= $_content->content_list($_id);
		$content_type			= $list[0]['content_type'];
		$content_user			= $list[0]['content_user'];
		$content_cat			= $list[0]['content_cat'];

		//yetki denetimlerini kontrol ediyoruz
		//kişi içerik silme yetkisine sahip değilse içerik silemez
		if($_auth['content_'.$action] <> '1') $hata_delete = '1';

		//herhangi bir yetkilendirme hatası almamış isek
		if($hata_delete <> '1')
		{
			try
			{
				$_content->content_delete_soft($_id);
				$message = $messages['info_delete'];
			}
			catch(Exception $e)
			{
				//demek ki ekleme işlemi sırasında bir hata oluştur
									$message 			= $messages['error_delete'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}

	if($do == 'edit')
	{
		$list 				= $_content->content_detail($_id);
		$url 				= $list[0]['content_url'];

		$header_subtitle	= 'Düzenle &rarr; <a href="'.$url.'">'.$list[0]['content_title'].'</a>';
	}

	if($do == 'add')
	{
		$header_subtitle	= 'Video Ekle';
	}

	if($do == 'list')
	{
		$header_subtitle	= 'Video Listesi';
	}

	if($do == 'import')
	{
		$header_subtitle	= 'Youtube\'dan Aktar';
	}

	if(!empty($message['type']))
	{
		$alert = showMessageBoxS($message['text'], $message['type']);
	}

?>

<section class="content">
	<div>
		<ol class="breadcrumb">
			<li><a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> Ana Sayfa</a></li>
			<li><a href="<?=LINK_ACP?>&view=content&amp;do=list"><i class="ion ion-document"></i> <?=$page_name?></a></li>
			<li class="active"><?=$header_subtitle?></li>
		</ol>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?=$alert?>
			<?php
				switch($do)
				{
					case 'add':
					case 'edit':
						include ACP_MODULE_PATH.$modul_name[$view].'content.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'content.form.php';
					break;
					case 'import':
						include ACP_MODULE_PATH.$modul_name[$view].'content.import.php';
					break;
					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'content.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
