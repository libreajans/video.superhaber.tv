<?php if(!defined('APP')) die('...') ?>

	<script>
		function toggle(source, type)
		{
			checkboxes = document.getElementsByClassName(type);
			for(var i=0, n=checkboxes.length;i<n;i++) { checkboxes[i].checked = source.checked; }
		}
	</script>

	<form id="form1" name="form1" method="post" action="">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>
		<input type="hidden" name="user_id" value="<?=$_id?>"/>

		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"><?=$header_subtitle?></h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-12" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-gear-b"></i> Yetki Seviyesi</span>
					<select class="form-control" id="user_status" name="user_status">
						<?=$option_user_status?>
					</select>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-android-mail"></i> E-posta Adresi</span>
					<input required class="form-control" autocomplete="off" type="email" id="user_email" name="user_email" value="<?=$user_email?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-person"></i> Gerçek Adı</span>
					<input required class="form-control" autocomplete="off" type="text" id="user_name" name="user_name" value="<?=$user_name?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-key"></i> Parola Bilgileri</span>
					<input class="form-control" autocomplete="off" type="password" id="user_pass" name="user_pass" value=""/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-person"></i> Görünecek Adı</span>
					<input required class="form-control" autocomplete="off" type="text" id="user_realname" name="user_realname" value="<?=$user_realname?>"/>
				</div>
				<div class="input-group col-md-6 clearMe">
					<?php if($do == 'edit') { ?>
						<input type="checkbox" id="user_pass_renew" name="user_pass_renew"/> <label for="user_pass_renew">Parolayı Yenile</label>
					<? } ?>
					<?php if($do == 'add') { ?>
						<input type="checkbox" id="user_pass_renew" name="user_pass_renew"/> <label for="user_pass_renew">Bu Parolayı Kullan <em>(Seçilmezse otomatik parola oluşturur)</em></label>
					<? } ?>
				</div>
				<div class="input-group col-md-12 clearMe" style="padding-top: 0px; margin-top: 0px;">
					<hr/>
					<h3 style="margin-top: 0px;">Avatar Seçiniz</h3>
					<?=$liste_avatar?>
				</div>
				<div class="buttonArea">
					<button class="btn right btn-success"><?=$action_submit?></button>
				</div>
			</div>
		</div>

		<?php if(($do == 'add' && $_auth['user_add'] == 1) || ($do == 'edit' && $_auth['user_edit'] == 1)) : ?>

		<div class="box box-danger">
			<div class="box-body">
				<h3><i class="ion ion-grid"></i> Modül Yetkileri</h3>
				<div>
					<table class="table table-bordered table-striped">
						<tr>
							<th class="left"><b>Modül Yetkileri</b></th>
							<th class="center">Listele</th>
							<th class="center">Ekle</th>
							<th class="center">Düzenle</th>
							<th class="center">Sil</th>
						</tr>
						<tr>
							<td></td>
							<td class="center" width="60"><input title="Listeleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toogle_list')"/></td>
							<td class="center" width="60"><input title="Ekleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_add')"/></td>
							<td class="center" width="60"><input title="Düzenleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_edit')"/></td>
							<td class="center" width="60"><input title="Silme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_delete')"/></td>
						</tr>

						<?php
							unset($modul_title['welcome']);
							unset($modul_title['password']);
							foreach($modul_title as $module => $module_baslik) :
						?>
							<tr>
								<td><?=$module_baslik?></td>
								<td class="center"><input class="toogle_list" type="checkbox" <?php if($auth[$module.'_list'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_list" value="<?=$module?>_list"/></td>
								<td class="center"><input class="toggle_add" type="checkbox" <?php if($auth[$module.'_add'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_add" value="<?=$module?>_add"/></td>
								<td class="center"><input class="toggle_edit" type="checkbox" <?php if($auth[$module.'_edit'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_edit" value="<?=$module?>_edit"/></td>
								<td class="center"><input class="toggle_delete" type="checkbox" <?php if($auth[$module.'_delete'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_delete" value="<?=$module?>_delete"/></td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>

		<div class="box box-info">
			<div class="box-body">
				<h3><i class="ion ion-more"></i> Çeşitli Yetkiler</h3>
				<div>
					<table class="table table-bordered table-striped">
						<tr>
							<th class="left">Çeşitli Yetkiler</th>
							<th class="center" width="60"><input title="Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_others')"/></th>
						</tr>
						<tr>
							<td>Kendi Giriş/Çıkış Kayıtlarını görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['log_self'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="log_self" value="log_self"/></td>
						</tr>
						<tr>
							<td>Diğer Giriş/Çıkış Kayıtlarını görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['log_others'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="log_others" value="log_others"/></td>
						</tr>
						<tr>
							<td>Anonim Erişim Denemelerini görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['log_anonim'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="log_anonim" value="log_anonim"/></td>
						</tr>
						<tr>
							<td>Erişim Loglarını Boşaltabilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['user_truncate'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="user_truncate" value="user_truncate"/></td>
						</tr>
						<tr>
							<td>Pasif Videoları Boşaltabilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['content_truncate'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_truncate" value="content_truncate"/></td>
						</tr>
						<tr>
							<td>Pasif Yorumları Boşaltabilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['comment_truncate'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="comment_truncate" value="comment_truncate"/></td>
						</tr>
						<tr>
							<td>Etiketsiz Toplam Video Sayısını Görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['content_tags_empty'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_tags_empty" value="content_tags_empty"/></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php endif ?>
	</form>
