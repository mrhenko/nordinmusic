<!DOCTYPE html>

<html>

	<?php get_header(); ?>	

	<body <?php
		if (is_front_page()) {
			echo ' class="main-page" ';	
		}
	?>>

	<aside id="tagline">
		<p><?php bloginfo('description'); ?></p>
	</aside>

		<header id="logo" role="banner">
			<h1><a href="<?php bloginfo('url'); ?>">
				<span>Nordin</span> Music Productions
				<?php /*bloginfo('title');*/ ?>
			</a></h1>
		</header><!-- #logo -->

		<nav role="navigation">
			<?php
				wp_nav_menu(array(
					'theme_location' => 'primary',
					'depth' => 1,
					'container' => false
				));
			?>
		</nav><!-- The main navigation -->

		<?php if ( have_posts() ) :?>
			<?php while (have_posts() ) :?>
				<?php the_post(); ?>
				<article <?php post_class('main-content'); ?>>
					<?php
						the_content();
						//print_r(mrh_process_image_tags(get_the_content()));
					?>
				</article>
			<?php endwhile; ?>
		<?php endif; ?>

		<script src="<?php bloginfo('template_directory') ?>/gallery_images.js">
		</script>
		<?php wp_footer(); ?>
	</body>

</html>
