<?php if(!defined('APP')) die('...');


	if($_auth['log_self'] 	== 1) 			$logSelf 			= $_user->get_user_log($_SESSION[SES]['user_id'], 'self');
	if($_auth['log_others'] == 1)			$logOthers 			= $_user->get_user_log($_SESSION[SES]['user_id'], 'others');
	if($_auth['log_anonim'] == 1)			$logAnonim 			= $_user->get_user_log($_SESSION[SES]['user_id'], 'anonim');
	if($_auth['comment_edit'] == 1)			$commentDraft 		= $_comment->get_comment_draft();

	if($_auth['content_edit'] == 1)			$adet_toplam_user	= $_content->adet_etiketsiz($_SESSION[SES]['user_id']);
	if($_auth['content_tags_empty'] == 1) 	$adet_toplam		= $_content->adet_etiketsiz();
?>
	<script>
		$(document).ready(function()
		{
			$('#logData1').dataTable(
			{
				"aaSorting"		: [[0, "desc", 1]],
				"bPaginate" 	: false,	//sayfalama özelliğini açıp kapatır
				"bLengthChange"	: false, 	//sayı seçimini açıp kapatır
				"bFilter"		: false, 	//arama alanını açıp kapatır
				"bStateSave" 	: false,	//kalınan noktayı kaydetmeye yarar
				"bSort" 		: false, 	//sıralama özelliğini açıp kapatır
				"bInfo"			: false,	//bilgi alanını açıp kapatır
				//dil dosyalarını yükler
				"oLanguage" 	: { "sUrl": "<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.lang.js"}
			});
			$('#logData2').dataTable(
			{
				"aaSorting"		: [[0, "desc", 1]],
				"bPaginate" 	: false,	//sayfalama özelliğini açıp kapatır
				"bLengthChange"	: false, 	//sayı seçimini açıp kapatır
				"bFilter"		: false, 	//arama alanını açıp kapatır
				"bStateSave" 	: false,	//kalınan noktayı kaydetmeye yarar
				"bSort" 		: false, 	//sıralama özelliğini açıp kapatır
				"bInfo"			: false,	//bilgi alanını açıp kapatır
				//dil dosyalarını yükler
				"oLanguage" 	: { "sUrl": "<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.lang.js"}
			});
			$('#logData3').dataTable(
			{
				"aaSorting"		: [[0, "desc", 1]],
				"bPaginate" 	: false,	//sayfalama özelliğini açıp kapatır
				"bLengthChange"	: false, 	//sayı seçimini açıp kapatır
				"bFilter"		: false, 	//arama alanını açıp kapatır
				"bStateSave" 	: false,	//kalınan noktayı kaydetmeye yarar
				"bSort" 		: false, 	//sıralama özelliğini açıp kapatır
				"bInfo"			: false,	//bilgi alanını açıp kapatır
				//dil dosyalarını yükler
				"oLanguage" 	: { "sUrl": "<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.lang.js"}
			});
		});
	</script>

	<section class="content">
		<div>
			<ol class="breadcrumb">
				<li><a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> Ana Sayfa</a></li>
				<li class="active"><i class="ion ion-arrow-swap"></i> Giriş-Çıkış Kayıtları</li>
			</ol>
		</div>

		<?php if($_auth['comment_edit'] == 1 && $commentDraft > 0) :?>
			<div class="callout callout-warning">
				<h4><i class="ion ion-information-circled"></i>&nbsp; Editör Bilgilendirme</h4>
				<p><a href="<?=LINK_ACP?>&amp;view=comment&amp;do=list"><b><?=$commentDraft?></b> yorum denetim için bekliyor.</a></p>
			</div>

		<?endif?>

		<?php if($_auth['contact_edit'] == 1 && $contactDraft > 0) :?>
			<div class="callout callout-info">
				<h4><i class="ion ion-information-circled"></i>&nbsp; Editör Bilgilendirme</h4>
				<p><a href="<?=LINK_ACP?>&amp;view=contact&amp;do=list"><b><?=$contactDraft?></b> iletişim formu mesajı denetim için bekliyor.</a></p>
			</div>
		<?endif?>

		<?php if($_auth['content_edit'] == 1 && $adet_toplam_user > 0) :?>
			<div class="callout callout-danger">
				<h4><i class="ion ion-information-circled"></i>&nbsp; Editör Bilgilendirme</h4>
				<p><a href="<?=LINK_ACP?>&amp;view=contact&amp;do=list"><b><?=$adet_toplam_user?></b> içeriğiniz etiketsiz, lütfen düzeltiniz.</a></p>
			</div>
		<?endif?>



		<div class="row">
			<div class="col-xs-12">

				<?php if($_auth['log_self'] == 1 && $logSelf <> '') :?>
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title">Size Ait Giriş-Çıkış Kayıtları</h3>
						</div>
						<div class="box-body">
							<table id="logData1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="150">Tarih</th>
										<th>Log</th>
										<th width="100">İp Adresi</th>
									</tr>
								</thead>
								<tbody>
									<?=$logSelf?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif ?>

				<?php if($_auth['log_others'] == 1 && $logOthers <> '') :?>
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title">Diğer Kullanıcılara Ait Giriş-Çıkış Kayıtları</h3>
						</div>
						<div class="box-body">
							<table id="logData2" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="150">Tarih</th>
										<th>Log</th>
										<th width="100">İp Adresi</th>
									</tr>
								</thead>
								<tbody>
									<?=$logOthers?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif ?>

				<?php if($_auth['log_anonim'] == 1 && $logAnonim <> '') :?>
					<div class="box box-danger">
						<div class="box-header">
							<h3 class="box-title">Anonim Erişim Denemeleri</h3>
						</div>
						<div class="box-body">
							<table id="logData3" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="150">Tarih</th>
										<th>Log</th>
										<th width="100">İp Adresi</th>
									</tr>
								</thead>
								<tbody>
									<?=$logAnonim?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</section>
