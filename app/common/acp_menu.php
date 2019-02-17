<?php if(!defined('APP')) die('...'); ?>
<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?=G_IMGLINK_DEV?>Avatar/<?=$_SESSION[SES]['user_avatar']?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?=$_SESSION[SES]['user_name']?></p>
				<a href="#"><i class="ion ion-record text-success"></i> Online</a>
			</div>
		</div>
		<ul class="sidebar-menu">
			<li>
				<a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> <span>Ana Sayfa</span></a>
			</li>
			<li>
				<a href="<?=LINK_INDEX?>"><i class="ion ion-reply"></i> <span>Siteye Dön</span></a>
			</li>
			<?php if($_auth['content_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'content') echo " active";?>">
					<a href="#">
						<i class="ion ion-document"></i>
						<span>Video Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
<!--						<?php if($_auth['content_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;type=-1&amp;cat=-1&amp;user=-1&amp;status=-1"><i class="ion ion-android-list"></i> Video Listesi</a></li>
						<?php endif?>-->
						<?php if($_auth['content_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add"><i class="ion ion-android-add"></i> Video Ekle</a></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=import"><i class="ion ion-social-youtube"></i> Youtube'dan Aktar</a></li>
						<?php endif?>
						<?php if($_auth['content_list'] == 1) : ?>
							<li><hr style="margin-top: 0px; margin-bottom: 0px;"/></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;type=-1&amp;cat=-1&amp;user=-1&amp;status=1"><i class="ion ion-checkmark-circled text-green"></i> Video Listesi</a></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;type=-1&amp;cat=-1&amp;user=<?=$_SESSION[SES]['user_id']?>&amp;status=-1"><i class="ion ion-android-person text-green"></i> Benim Videolarım</a></li>
							<li>
								<a href="<?=LINK_ACP?>&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;type=-1&amp;cat=-1&amp;user=-1&amp;status=2">
									<i class="ion ion-help-circled text-light-blue"></i> Taslak Videolar
								</a>
							</li>
						<?php endif?>

						<?php if($_auth['content_truncate'] == 1) : ?>
							<li><hr style="margin-top: 0px; margin-bottom: 0px;"/></li>
							<li><a onclick="javascript:return confirm('Pasif Videoleri temizlemek üzeresiniz')" href="<?=LINK_ACP?>&amp;view=content&amp;action=truncate"><i class="ion ion-android-delete text-maroon"></i> Pasif Videoleri Temizle</a></li>
						<?php endif?>
					</ul>
				</li>
			<?php endif ?>

			<?php if($_auth['comment_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'comment') echo " active";?>">
					<a href="#">
						<i class="ion ion-android-hangout"></i>
						<span>Yorum Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['comment_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=comment&amp;do=list"><i class="ion ion-android-list"></i> Yorum Listesi</a></li>
						<?php endif?>
						<?php if($_auth['comment_truncate'] == 1) : ?>
							<li><a onclick="javascript:return confirm('Pasif yorumları temizlemek üzeresiniz?')" href="<?=LINK_ACP?>&amp;view=comment&amp;action=truncate"><i class="ion ion-android-delete text-maroon"></i> Pasif Yorumları Temizle</a></li>
						<?php endif?>
					</ul>
				</li>
			<?php endif ?>


			<?php if($_auth['user_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'user') echo " active";?>">
					<a href="#">
						<i class="ion ion-android-person"></i>
						<span>Kullanıcı Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['user_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=user&amp;do=add"><i class="ion ion-android-add"></i> Kullanıcı Ekle</a></li>
						<?php endif?>
						<?php if($_auth['user_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=user&amp;do=list"><i class="ion ion-android-list"></i> Kullanıcı Listesi</a></li>
						<?php endif?>
						<?php if($_auth['user_truncate'] == 1) : ?>
							<li><a onclick="javascript:return confirm('Erişim Loglarını temizlemek üzeresiniz')" href="<?=LINK_ACP?>&amp;view=user&amp;action=truncate"><i class="ion ion-android-delete text-maroon"></i> Erişim Loglarını Temizle</a></li>
						<?php endif?>
					</ul>
				</li>
			<?php endif ?>

			<?php if($_auth['stats_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'stats') echo " active";?>">
					<a href="#">
						<i class="ion ion-stats-bars"></i>
						<span>İstatistik Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['stats_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=stats&amp;do=add"><i class="ion ion-android-add"></i> Güncel İstatistik</a></li>
						<?php endif?>
						<?php if($_auth['stats_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=stats&amp;do=list"><i class="ion ion-android-list"></i> İstatistik Arşivi</a></li>
						<?php endif?>
					</ul>
				</li>
			<?php endif ?>


			<?php
				$endtime = microtime(true);
				$endtime = substr(($endtime - $starttime),0,6);

				$kullanim = memory_get_peak_usage(true);
				$kullanim = number_format($kullanim / 1024);
			?>
			<li class="treeview">
				<a href="#">
					<i class="ion ion-android-alert"></i>
					<span>Runtime Info</span>
					<i class="ion ion-ios-arrow-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li><a href=""><i class="ion ion-android-time"></i> SÜS : <?=$endtime?></a></li>
					<li><a href=""><i class="ion ion-flash"></i> MEM : <?=$kullanim?></a></li>
				</ul>
			</li>
		</ul>
	</section>
</aside>
