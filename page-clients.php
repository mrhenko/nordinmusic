<?php
	/* Template Name: Referenser */
?>

<!DOCTYPE html>

<html>

	<?php get_header(); ?>	

	<body class="clients"> 

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

		<article <?php post_class('main-content'); ?>>
			<ul>

				<?php
					$wp_query = new WP_Query(array(
						'post_type' => 'nm_referenser',
						'posts_per_page' => 100,
						'meta_key' => '_nm_custom_fields_prominens',
						'orderby' => 'meta_value',
						'order' => 'ASC'
					));

					while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
						<?php $prominens = "prominens" . get_post_meta($post->ID, '_nm_custom_fields_prominens', true); ?>
						<li class="<?php echo $prominens; ?>"><?php the_title(); ?></li>
					<?php endwhile; ?>

			</ul>
		</article>
			
		<script src="<?php bloginfo('template_directory') ?>/gallery_images.js">
		</script>
		<?php wp_footer(); ?>
	</body>

</html>
