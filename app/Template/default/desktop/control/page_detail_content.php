<?php if(!defined('APP')) die('...');

	//her halükarda sayfayı yüklüyoruz
	$icerik = $_content->content_detail($_id);
	//print_pre($icerik);

	//yazı pasif ise ve yönetici değilse
	//ana sayfaya dönsün
	if($_SESSION[SES]['ADMIN'] <> 1 && $icerik[0]['content_status'] <> 1)
	{
		header('Location:'.LINK_INDEX);
		exit();
	}

	//başlık yoksa, yazı da yoktur ;)
	//yazı yoksa, ana sayfaya dönsün
	if($icerik[0]['content_title'] == "")
	{
		header('Location:'.LINK_INDEX);
		exit();
	}

	//yorum ekle denilmiş ise
	$comment_add = myReq('comment_add', 0);
	if($comment_add == 1)
	{
		$_comment->comment_add($_id);
	}

	$site_title				= $icerik[0]['content_seo_title'].' - Video';
	$site_canonical 		= $icerik[0]['content_url'];
	$content_metatitle 		= $icerik[0]['content_title'];
	$content_metadesc 		= substr($icerik[0]['content_seo_metadesc'], 0, 250);
	$content_metatags		= substr($icerik[0]['content_tags'], 0, 160);
	$content_metaimage		= $icerik[0]['content_image_url'];

	$link_edit 				= LINK_ACP.'&view=content&do=edit&id='.$_id;
	$link_comment_edit 		= LINK_ACP.'&view=comment&do=edit&id=';

	//etiketleri parse edelim
	$array_tags = explode(",", $icerik[0]['content_tags']);

	$benzer_list = $_content->content_list_benzer( $cat = $icerik[0]['content_cat'], $exclude = $_id, $limit = 5 );

	$pt = intval($icerik[0]['content_duration'] / 60).'d '.intval( $icerik[0]['content_duration'] % 60 ).'s';

	$template = $twig->loadTemplate('page_detail_content.twig');
	$content = $template->render
	(
		array
		(
			'content' 				=> $icerik[0],
			'content_duration' 		=> $pt,
			'content_text' 			=> n2br($icerik[0]['content_text']),
			'site_canonical' 		=> $site_canonical,
			'link_edit' 			=> $link_edit,
			'array_tags' 			=> $array_tags,
			'content_benzer_list' 	=> $benzer_list,
			//yorum kısmının değişkenleri
			'comment_list'			=> $_comment->comment_list_content($_id),
			'comment_add' 			=> $comment_add,
			'link_comment_edit' 	=> $link_comment_edit,
			'commented' 			=> $_SESSION[SES]['comment'][$_id],
			'isim'					=> $_SESSION[SES]['isim'],
		)
	);
