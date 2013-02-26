	<head>
		<title><?php bloginfo('title'); ?> :: <?php the_title(); ?></title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<?php wp_enqueue_script("jquery"); ?>

		<?php wp_head(); ?>
	</head>
