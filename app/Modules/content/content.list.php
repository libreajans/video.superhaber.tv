<?php
	if(!defined('APP')) die('...');

	$array_users = $_user->user_shortlist();

	//hariçten gazele hayır
	if($_REQUEST['filter'] == 1)
	{
		unset($list_content_type, $list_cat_name, $list_users, $list_content_status, $list_content_limits);

		$type 		= myReq('type', 	0);
		$cat		= myReq('cat', 		0);
		$user		= myReq('user', 	0);
		$status		= myReq('status', 	0);
		$limit		= myReq('limit', 	0);
		$filter		= myReq('filter',	0);
		$time		= myReq('time', 	2);
		$keyword	= myReq('keyword', 	2);

		$_SESSION[SES]['content_list']['type']		= $type;
		$_SESSION[SES]['content_list']['cat']		= $cat;
		$_SESSION[SES]['content_list']['user']		= $user;
		$_SESSION[SES]['content_list']['status']	= $status;
		$_SESSION[SES]['content_list']['limit']		= $limit;
		$_SESSION[SES]['content_list']['time']		= $time;
		$_SESSION[SES]['content_list']['keyword']	= $keyword;
		$_SESSION[SES]['content_list']['filter']	= 1;
	}

	foreach($array_content_type as $k => $v)
	{
		if($_SESSION[SES]['content_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['content_list']['type'] == $k) $selected = 'selected'; }
		$list_content_type.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_cat_name as $k => $v)
	{
		if($_SESSION[SES]['content_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['content_list']['cat'] == $k) $selected = 'selected'; }
		$list_cat_name.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_users as $k => $v)
	{
		if($_SESSION[SES]['content_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['content_list']['user'] == $k) $selected = 'selected'; }
		$list_users.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_content_status as $k => $v)
	{
		if($_SESSION[SES]['content_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['content_list']['status'] == $k) $selected = 'selected'; }
		$list_content_status.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_limits as $k => $v)
	{
		if($_SESSION[SES]['content_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['content_list']['limit'] == $k) $selected = 'selected'; }
		$list_content_limits.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	//filtreleme istenmişse, filtrelenmiş sonuçları gösteriyoruz
	if($_SESSION[SES]['content_list']['filter'] == 1)
	{
		$list = $_content->content_list_small
		(
			$_SESSION[SES]['content_list']['type'],
			$_SESSION[SES]['content_list']['cat'],
			$_SESSION[SES]['content_list']['user'],
			$_SESSION[SES]['content_list']['status'],
			$_SESSION[SES]['content_list']['time'],
			$_SESSION[SES]['content_list']['keyword'],
			$_SESSION[SES]['content_list']['limit']
		);
	}
	else
	{
		//henüz bir oturuma sahip olmayan bir kullanıcı ise
		//50 tane içerik varsayılan olarak gösteriyoruz
		$list = $_content->content_list_small('-1', '-1', '-1', '-1', '', '', 100);
	}

	$adet	= count($list);
	if($list <> false)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$content_id					= $list[$i]['content_id'];
			$content_title				= $list[$i]['content_title'];
			$content_status				= $list[$i]['content_status'];
			$content_image				= $list[$i]['content_image'];
			$content_video				= $list[$i]['content_video'];
			$content_tags				= $list[$i]['content_tags'];
			$content_image_dir			= $list[$i]['content_image_dir'];
			$content_type				= $list[$i]['content_type'];
			$content_cat				= $list[$i]['content_cat'];
			$content_cat2				= $list[$i]['content_cat2'];
			$content_cat3				= $list[$i]['content_cat3'];
			$content_user				= $list[$i]['content_user'];
			$content_time				= $list[$i]['content_time'];
			$content_time_f				= $list[$i]['content_time_f'];
			$content_seo_title			= $list[$i]['content_seo_title'];
			$content_seo_url			= $list[$i]['content_seo_url'];
			$content_seo_metadesc		= $list[$i]['content_seo_metadesc'];
			$content_view				= $list[$i]['content_view'];
			$content_view_real			= $list[$i]['content_view_real'];
			$content_url				= $list[$i]['content_url'];
			$content_comment_status		= $list[$i]['content_comment_status'];
			$content_google_status		= $list[$i]['content_google_status'];

			$content_id_f				= str_pad($content_id, 4, "0", STR_PAD_LEFT);
			$content_status_f			= $array_content_status[$content_status];
			$content_status_icon		= $array_content_status_bar[$content_status];

			$content_user_f				= $array_users[$content_user];
			$content_type_f				= $array_content_type[$content_type];

			//dosyaların yolları
			$content_image_path			= IMAGE_DIRECTORY.'content/'.$content_image_dir.$content_image;
			$content_video_path			= VIDEO_DIRECTORY.$content_image_dir.$content_video;

														$content_cat_f = '<p>'.$array_cat_name[$content_cat].'</p>';
			if($array_cat_name[$content_cat2] <> '')	$content_cat_f.= '<p>'.$array_cat_name[$content_cat2].'</p>';
			if($array_cat_name[$content_cat3] <> '') 	$content_cat_f.= '<p>'.$array_cat_name[$content_cat3].'</p>';

			$link_edit					= LINK_ACP.'&amp;view=content&amp;do=edit&amp;id='.$content_id;
			$link_delete				= LINK_ACP.'&amp;view=content&amp;action=delete&amp;id='.$content_id;
			$confirm_sorusu				= 'Video kaydını silmek üzeresiniz?';

			// ------------------ yetkilendirme düzenleme kısmı ------------------
			$hata_delete = '';
			$delete_link = '';

			if($_auth['content_delete'] <> '1') $hata_delete = '1';

			if($hata_delete <> '1')
			{
				if($_SESSION[SES]['user_id'] == 1)
				{
					$delete_link = '<a target="_blank" rel="noopener noreferrer" href="'.$link_delete.'"><i class="btn btn-danger ion ion-close"></i></a>';
				}
				else
				{
					$delete_link = '<a href="'.$link_delete.'" onclick="javascript:return confirm(\''.$confirm_sorusu.'\')"><i class="btn btn-danger ion ion-close"></i></a>';
				}
			}

			// ------------------ yetkilendirme düzenleme kısmı ------------------

			$edit_link 					= '';
			$hata_edit 					= '';
			$content_image_f			= '';
			$content_video_f			= '';
			$content_comment_f 			= '';
			$content_tags_f 			= '';
			$content_image_health_f 	= '';
			$content_google_f 			= '';
			$error_aspect 				= '';
			$error_video				= '';
			$aspect_error_image 		= '';
			$aspect_error_image_text	= '';


			if($_auth['content_edit'] <> '1') $hata_edit = '1';

			if($hata_edit <> '1')
			{
				$edit_link = '<a title="Düzenle" href="'.$link_edit.'"><i class="btn btn-primary ion ion-edit"></i></a>';
			}
			// ------------------ yetkilendirme kısmı sonu ------------------

			//---[+]--- Resim Alanı Uyarıları ----------------------------------------------
			if($content_image <> '')
			{
				$image_sizes = getimagesize($content_image_path);
				if($image_sizes[0] <> $array_content_type_image_wh['w']) $aspect_error_image = 1;
				if($image_sizes[1] <> $array_content_type_image_wh['h']) $aspect_error_image = 1;
				if($aspect_error_image == 1)
				{
					$error_aspect = 1;
				}
			}
			//---[-]--- Resim Alanı Uyarıları ----------------------------------------------

			//---[+]--- Video Alanı Uyarıları ----------------------------------------------
			if($content_video <> '')
			{
				//video yerinde yoksa veya 32 bitten küçük ise video yoktur!
				if(file_exists($content_video_path) <> 1 OR filesize($content_video_path) < 32 ) $error_video = 1;
			}
			//---[-]--- Video Alanı Uyarıları ----------------------------------------------

			if($error_aspect == 1)					$content_image_health_f = '<i title="Resim boyutları hatalı" class="btn btn-danger ion ion-alert"></i>';
			if($error_video == 1)					$content_video_f 		= '<i title="Video Eksik" class="btn btn-danger ion ion-alert"></i>';
			if($content_comment_status == 0)		$content_comment_f 		= '<i title="Yorum Özelliği Kapalı" class="btn btn-warning  ion ion-alert"></i>';;
			if($content_image == '')				$content_image_f 		= '<i title="Resim Eksik" class="btn btn-danger ion ion-alert"></i>';
			if($content_tags == '')					$content_tags_f 		= '<i title="Etiket Eksik" class="btn btn-warning ion ion-alert"></i>';
			if($content_google_status == 1)			$content_google_f		= '<i title="Google Ziyaret Etti" class="btn btn-success ion ion-social-google"></i>';;

			$sayfa_icerik.='
				<tr>
					<td>'.$content_id_f.'</td>
					<td><a href="'.$content_url.'">'.$content_title.'</a></td>
					<td>'.$content_type_f.'</td>
					<td>'.$content_cat_f.'</td>
					<td class="right">'.$content_status_f.' '.$content_status_icon.'</td>
					<td>'.$content_user_f.'</td>
					<td class="right">'.$content_view.'</td>
					<td>
						<p class="hideMe">'.$content_time_f.'</p>
						'.$content_time_f.'
					</td>
					<td width="30" class="center">
						'.$content_google_f.'
						'.$content_image_health_f.'
						'.$content_video_f.'
						'.$content_image_f.'
						'.$content_comment_f.'
						'.$content_tags_f.'
					</td>
					<td width="30" class="center">'.$delete_link.'</td>
					<td width="30" class="center">'.$edit_link.'</td>
					<td class="hideMe">
						'.$content_seo_title.'
						'.$content_seo_url.'
						'.$content_seo_metadesc.'
					</td>
				</tr>';
		}
	}
	else
	{
		$alert = showMessageBoxS('Herhangi bir kayıt bulunamadı.', 'error');
	}

?>

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
				<h3 class="box-title">Video Yönetimi - Hızlı Filtrele</h3>
			</div>
			<div class="box-body" style="height: 60px;">
				<div class="input-group col-md-6" style="float:left;">
					<input placeholder="Aranacak Kelime" class="form-control" type="text" id="keyword" name="keyword" value="<?=$keyword?>"/>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<input placeholder="Tarih" class="form-control" type="text" id="time" name="time" value="<?=$time?>"/>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="limit" name="limit">
						<?=$list_content_limits?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="type" name="type">
						<option value="-1">Video Tipi</option>
						<?=$list_content_type?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="cat" name="cat">
						<option value="-1">Kategori</option>
						<?=$list_cat_name?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="user" name="user">
						<option value="-1">Ekleyen</option>
						<?=$list_users?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="status" name="status">
						<option value="-1">Yayın Durumu</option>
						<?=$list_content_status?>
					</select>
				</div>
				<div class="input-group col-md-12" style="float:right; text-align:right;">
					<button class="btn btn-success">Filtrele</button>
				</div>
			</div>
		</form>
		<div class="input-group col-md-12 clearMe"></div>
		<hr/>
		<div class="input-group col-md-12 clearMe"></div>

		<?php if($list <> false) : ?>
			<script>
				$(document).ready(function()
				{
					$('#recordList_Content').dataTable(
					{
						"aaSorting"		: [[0, "desc", 1]],
						"bPaginate" 	: true,		//sayfalama özelliğini açıp kapatır
						"bLengthChange"	: true, 	//sayı seçimini açıp kapatır
						"bFilter"		: true, 	//arama alanını açıp kapatır
						"bStateSave" 	: true,		//kalınan noktayı kaydetmeye yarar
						"bSort" 		: true, 	//sıralama özelliğini açıp kapatır
						"bInfo"			: true,		//bilgi alanını açıp kapatır
						//dil dosyalarını yükler
						"oLanguage"				:
						{
							"sProcessing"		: "Lütfen Bekleyin...",
							"sLengthMenu"		: "_MENU_ kayıt göster, her sayfada",
							"sZeroRecords"		: "Herhangi bir sonuç bulunamadı",
							"sInfo"				: "_TOTAL_ kayıttan _START_ ile _END_ arası kayıt görüntüleniyor",
							"sInfoEmpty"		: "Gösterilecek bir kayıt bulunamadı",
							"sInfoFiltered"		: "(Toplam _MAX_ kayıt arasında)",
							"sInfoPostFix"		: "",
							"sSearch"			: "Filtrelenmiş sonuçlar içinde ARA",
							"oPaginate"			:
							{
								"sFirst"		: "İlk",
								"sPrevious"		: "Önceki Sayfa",
								"sNext"			: "Sonraki Sayfa",
								"sLast"			: "Son"
							}
						}
					});
				});
			</script>

			<div class="box-body">
				<div class="input-group col-md-12">
					<table id="recordList_Content" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">ID</th>
								<th>Başlık</th>
								<th width="50"><i class="ion ion-star" title="Video Tipi"></i></th>
								<th width="50"><i class="ion ion-folder" title="Kategori"></i></th>
								<th width="50"><i class="ion ion-ios-checkmark" title="Yayın Durumu"></i></th>
								<th width="50"><i class="ion ion-person" title="Ekleyen"></i></th>
								<th width="20"><i class="ion ion-refresh" title="Gösterim Sayısı"></i></th>
								<th width="80"><i class="ion ion-calendar" title="Yayın Tarihi"></i></th>
								<th class="hideMe"></th>
								<th class="hideMe"></th>
								<th class="hideMe"></th>
								<th class="hideMe"></th>
							</tr>
						</thead>
						<tbody>
							<?=$sayfa_icerik?>
						</tbody>
					</table>
				</div>
				<div class="input-group col-md12 clearMe"></div>
			</div>
		<?php endif ?>
	</div>
