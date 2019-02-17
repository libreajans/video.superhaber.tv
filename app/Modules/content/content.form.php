<?php if(!defined('APP')) die('...') ?>

	<?=$uyarilar_text?>

	<?php if($do <> 'add' && $content_tags == "") :?>
		<div class="box box-solid bg-red">
			<div class="box-body-wp">
				<i class="ion ion-alert-circled"></i>&nbsp;&nbsp;&nbsp;&nbsp;Video etiketlerini boş bırakmayınız.
			</div>
		</div>
	<?php endif ?>

	<?php if($aspect_error_image == 1) :?>
		<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_image_text?></div></div>
	<?php endif;?>

	<?php if($aspect_error_video == 1) :?>
		<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_video_text?></div></div>
	<?php endif;?>

	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jMetro/jquery-ui.css">
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jTag/css/jquery.tagit.css"/>

	<script src="<?=G_VENDOR_JQUERY?>jUi/jquery-ui.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jTag/js/tag-it.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jAreYouSure/jquery.are-you-sure.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jAreYouSure/ays-beforeunload-shim.js"></script>

	<script>$(function() { $('#content_tags').tagit({allowSpaces: true});1});</script>
	<script>$(function() { $('#content_time').datetimepicker({lang:'tr', timepicker:true, format:'Y-m-d H:i:s'}); }); </script>
	<script>$(function() { $('#form1').areYouSure(); }); </script>

	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>
<!--		<input type="hidden" name="org_content_video" value="<?=$content_video?>"/>-->
		<input type="hidden" name="content_image_dir" value="<?=$content_image_dir?>"/>
		<!-- botlarla eklenen içeriklerin orjinal id değerlerini tutuyor, o sebeple önemli -->
		<input type="hidden" name="content_redirect" value="<?=$content_redirect?>"/>


		<div class="box box-info">
			<div class="box-header">
				<div style="float:left; width:75%">
					<h3 class="box-title contentTitleSize"><?=$header_subtitle?></h3>
				</div>
				<div style="float:right; width:25%">
					<?php if($hata_delete <> '1') : ?>
						<a href="<?=LINK_ACP?>&amp;view=content&amp;action=delete&amp;id=<?=$_id?>">
							<p style="float:right; margin:5px 10px 0px 0px;" class="btn right btn-danger"> <?php if($content_status == '3') echo '<i class="ion ion-checkmark"></i>';?> Sil </p>
						</a>
					<?php endif ?>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="draft" class="btn right btn-primary">
					<?php if($content_status == '2') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_draft?></button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="publish" class="btn right btn-success">
					<?php if($content_status == '1') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_publish?></button>
				</div>
			</div>
			<div class="box-body">
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Tip</span>
					<select required class="form-control" id="content_type" name="content_type">
						<?=$option_type?>
					</select>
				</div>

				<div class="input-group col-md-3" style="float:left;">
					<select required class="form-control" id="content_cat" name="content_cat">
						<?=$option_cat?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="content_cat2" name="content_cat2">
						<?=$option_cat2?>
					</select>
				</div>

				<div class="input-group col-md-12" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Başlık</span>
					<input required class="form-control" type="text" id="content_title" name="content_title" value="<?=$content_title?>"/>
				</div>

				<div class="input-group col-md-12" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Metin</span>
					<textarea required style="height:120px;" class="form-control" id="content_text" name="content_text"><?=$content_text?></textarea>
				</div>

				<div class="input-group col-md-12" style="float:left;">
					<span class="input-group-addon"><i title="Etiketler | {etiketler küçük harflerle girilmelidir}" class="ion ion-help"></i> Video Etiketleri</span>
					<input class="form-control" type="text" id="content_tags" name="content_tags" value="<?=$content_tags?>" style="display:none;"/>
				</div>
				<?php if(RC_DetailedSeo == 1) : ?>
					<div class="input-group col-md-12" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-help" title="Video Detay sayfasının Title alanında kullanılır"></i> Meta Title </span>
						<input required class="form-control" type="text" id="content_seo_title" name="content_seo_title" value="<?=$content_seo_title?>"/>
					</div>
					<div class="input-group col-md-12" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-help" title="Video linki oluşturulurken kullanılır"></i> Meta URL </span>
						<input required maxlength="128" class="form-control" type="text" id="content_seo_url" name="content_seo_url" value="<?=$content_seo_url?>"/>
					</div>
					<div class="input-group col-md-12" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-help" title="Video Detay sayfasının Meta Açıklama alanında kullanılır"></i> Meta Açıklama </span>
						<input required maxlength="128" class="form-control" type="text" id="content_seo_metadesc" name="content_seo_metadesc" value="<?=$content_seo_metadesc?>"/>
					</div>
				<?php endif ?>

				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>

		<div class="box box-danger box-n">
			<div class="box-header">
				<h3 class="box-title"><i class="ion ion-document"></i> Alternatif Alanlar</h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-android-menu"></i> Yayın Tarihi</span>
					<input class="form-control" id="content_time" name="content_time" value="<?=$content_time?>"/>
				</div>

				<div class="input-group col-md-6" style="float:left; height:34px; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
					<span class="input-group-addon">
						&nbsp;<input type="checkbox" <?=$content_ads_status_checked?> name="content_ads_status" id="content_ads_status" /> <label for="content_ads_status">REKLAM GÖSTERİLMESİN!</label>
					</span>
				</div>

				<div class="input-group col-md-12 clearMe"></div>

				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-android-menu"></i> Yorum Durumu</span>
					<select class="form-control" id="content_comment_status" name="content_comment_status">
						<?=$option_comment?>
					</select>
				</div>

				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-android-menu"></i> Ek Kategori</span>
					<select class="form-control" id="content_cat3" name="content_cat3">
						<?=$option_cat3?>
					</select>
				</div>

				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>

		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"><i class="ion ion-image"></i> Video Resmi | Video</h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-6" style="float:left;">
					<br/><input type="file" style="width: 100%; padding: 0px;" id="content_image" name="content_image"/>

					<?php if($action_type == 'edit' && $content_image <> '') : ?>
						<br/><input style="max-width:320px !important;" class="form-control" type="text" id="org_content_image" name="org_content_image" value="<?=$content_image?>" accept="image/*"/>
					<?php endif ?>

					<?php if($content_image <> '') :?>
						<br/><br/><img style="max-width:320px !important; border:1px dotted #228866;" src="<?=$content_image_link?>"/>
					<?php endif ?>

					<?php if($aspect_error_image == 1) :?>
						<br/><br/><div class="box box-solid bg-red" style="max-width:320px !important;"><div class="box-body-wp"><?=$aspect_error_image_text?></div></div>
					<?php endif;?>

					<?php if($action_type == 'edit' && $content_image <> '') : ?>
						<br/><br/>
						<a href="javascript:void(0);" onclick="window.open('<?=LINK_BASE?>crop&id=0&amp;content_image_dir=<?=$content_image_dir?>&amp;type=content_image&amp;img=<?=$content_image?>','', 'width=1300, height=700, scrollbars=yes, left=0, top=0')"><b class="btn btn-primary">Resmi Kırp</b></a>
					<?php endif ?>

					<?php if($content_image <> '') :?>
						<br /><br />
						<input type="checkbox" name="delete_content_image" id="delete_content_image"/>
						<label for="delete_content_image">Video Görseli Kaldır</label>
					<?php endif ?>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<br/><input type="file" style="width: 100%; padding: 0px;" id="content_video" name="content_video" accept="video/mp4"/>

					<?php if($content_video <> '') :?>
						<input style="max-width:320px !important;" class="form-control" type="text" id="org_content_video" name="org_content_video" value="<?=$content_video?>"/>

						<br/><br/>
							<video width="400" height="228" controls>
								<source src="<?=$content_video_link?>" type="video/mp4">
							</video>
					<?php endif ?>


					<?php if($content_video <> '') :?>
						<br /><br />
						<input type="checkbox" name="delete_content_video" id="delete_content_video"/>
						<label for="delete_content_video">Video Kaldır</label>
					<?php endif ?>

				</div>

				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>

		<div class="box box-primary">
			<div class="box-body">
				<div class="buttonArea">
					<?php if($do <> 'add') : ?>
					<div style="float:left; margin-top:10px; margin-left:10px;">
						<i class="ion ion-refresh" title="Ziyaret sayısı"></i> <b><?=$content_view?></b>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<i class="ion ion-document" title="Okunma sayısı"></i> <?=$content_view_real?>
					</div>
					<?php endif ?>
					<?php if($do <> 'add' && $hata_delete <> '1') : ?>
						<a href="<?=LINK_ACP?>&amp;view=content&amp;action=delete&amp;id=<?=$_id?>">
							<p style="float:right; margin:5px 10px 0px 0px;" class="btn right btn-danger"> <?php if($content_status == '3') echo '<i class="ion ion-checkmark"></i>';?> Sil </p>
						</a>
					<?php endif ?>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="draft" class="btn right btn-primary">
					<?php if($content_status == '2') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_draft?></button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="publish" class="btn right btn-success">
					<?php if($content_status == '1') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_publish?></button>
				</div>
				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>



	</form>

	<script>
		$("form").submit(function(e)
		{
			var ref = $(this).find("[required]");
			$(ref).each(function()
			{
				if ( $(this).val() == '' )
				{
					alert("Zorunlu alanlar boş bırarılamaz.");
					$(this).focus();
					e.preventDefault();
					return false;
				}
			});
			return true;
		});
	</script>
