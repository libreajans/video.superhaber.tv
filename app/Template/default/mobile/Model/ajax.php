<?php if(!defined('APP')) die('...');

	$type = myReq('type', 1);
	
	if($type == 'index')
	{
		$template = $twig->loadTemplate('ajax/ajax_block_content.twig');
		$content = $template->render
		(
			array
			(
				'content_list' =>  $_content->content_list_manset(
					$page 		= $_pg, 
					$limit 		= $config['index_cat_limit'], 
					$type 		= '0', 
					$cat 		= 'none', 
					$etiket 	= 'none', 
					$json 		= 0 
				),
			)
		);
		echo $content;
	}	

	if($type == 'cat')
	{
		$template = $twig->loadTemplate('ajax/ajax_block_content.twig');
		$content = $template->render
		(
			array
			(
				'content_list' =>  $_content->content_list_manset(
					$page 		= $_pg, 
					$limit 		= $config['index_cat_limit'], 
					$type 		= 'none', 
					$cat 		= $_id, 
					$etiket 	= 'none', 
					$json 		= 0 
				),
			)
		);
		echo $content;
	}


	if($type == 'tag')
	{
	
		$template = $twig->loadTemplate('ajax/ajax_block_content.twig');
		$content = $template->render
		(
			array
			(
				'content_list' =>  $_content->content_list_manset(
					$page 		= $_pg, 
					$limit 		= $config['index_cat_limit'], 
					$type 		= 'none', 
					$cat 		= 'none', 
					$etiket 	= $_key, 
					$json 		= 0 
				),
			)
		);
		echo $content;
	}
