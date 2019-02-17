<?php if(!defined('APP')) die('...');

	$template = $twig->loadTemplate('site_footer.twig');
	$footer = $template->render
	(
		array
		(
			'cat_name_long'	=> $array_cat_name,
			'year'			=> date("Y"),
		)
	);
