<?php
	//enable error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	// Register navigation menus
	register_nav_menu('primary', 'Huvudmeny');

	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size('retina', 1136, 756, true);
		add_image_size('desktop', 568, 378, true);
		add_image_size('tablet', 400, 266, true);
		add_image_size('small-thumbs', 90, 60, true);
		//add_image_size( 'category-thumb', 300, 9999 ); //300 pixels wide (and unlimited height)
		//add_image_size( 'homepage-thumb', 220, 180, true ); //(cropped)
	}
	
	add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );  
	
	function custom_image_sizes_choose( $sizes ) {  
    	$custom_sizes = array(  
        'retina' => 'För retina MacBook Pro',
		'desktop' => 'För de flesta laptops och desktops',
		'tablet' => 'För iPads, NetBooks, Smartphones etc.',
		'small-thumbs' => 'Småbilder'
    	);  
    
		return array_merge( $sizes, $custom_sizes );  
	}  



	/**
	* Custom post type for the clients
	*
	**/
	function mrh_clients_init() {
		$labels = array(
			'name' => __('Referenser'),
			'singular_name' => __('Referens'),
			'add_new' => __('Lägg till'),
			'add_new_item' => __('Lägg till referens'),
			'edit_item' => __('Redigera referens'),
			'new_item' => __('Ny referens'),
			'all_items' => __('Alla referenser'),
			'view_item' => __('Visa referens'),
			'search_items' => __('Sök bland referenser'),
			'not_found' => __('Inga referenser hittades'),
			'not_found_in_trash' => __('Inga referenser hittades i papperskorgen'),
			'parent_item_colon' => '',
			'menu_name' => 'Referenser'
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'editor', 'author')
		); 

		register_post_type('mrh_clients',$args);
	}

	/*
	// returns the content of $GLOBALS['post']
	// if the page is called 'debug'
	function my_the_content_filter($content) {
		// assuming you have created a page/post entitled 'debug'
		if ($GLOBALS['post']->post_name == 'debug') {
			return var_export($GLOBALS['post'], TRUE );
		}
		// otherwise returns the database content
		return $content;
	}

	add_filter( 'the_content', 'my_the_content_filter' );
	*/
	
	add_filter('the_content', 'mrh_process_image_tags', 20);

	/**
	* This function treats a given text string as (x)html and searches it
	* for <img> elements. When it finds one, it adds data-attributes for
	* the various sizes of images that WP Theme provides.
	*
	* This new element is then used by JavaScript to dynamically load
	* "retina" class graphics
	**/
	function mrh_process_image_tags($html) {
		// Load the HTML
		$dom = new DOMDocument();
		$dom->loadHTML($html);

		// Loop through all images
		$images = $dom->getElementsByTagName('img');
		foreach ($images as $image) {

			/*$src = explode('.', $image->getAttribute('src'));
			*/

			$src = explodeLast('.', $image->getAttribute('src'));

			$sizeless = explodeLast('-', $src[0]);

			// Filter out the sizes from the filename
			/*$sizeless_src = explode('-', $src[0]);

			$i = 0;
			$sizeless = '';
			while ($i < count($sizeless_src) - 1) {
				$sizeless .= $sizeless_src[$i] . '-';
				$i++;
			}*/
				
			$retina = $sizeless[0] . '1136x756.' . $src[1];
			$desktop = $sizeless[0] . '568x378.' . $src[1];
			$tablet = $sizeless[0] . '400x266.' . $src[1];

			/*
				add_image_size('retina', 1136, 756);
				add_image_size('desktop', 568, 378);
				add_image_size('tablet', 400, 266);
				add_image_size('small-thumbs', 90, 60, true);
			*/

			// Replace the image
			$image->setAttribute('data-retina', $retina);
			$image->setAttribute('data-desktop', $desktop);
			$image->setAttribute('data-tablet', $tablet);

			// Remove size attributes
			$image->setAttribute('width', '');
			$image->setAttribute('height', '');

		}

		// Get the new HTML string
		$html = $dom->saveHTML();

		return $html;

		/*$dom = new DOMDocument();
		$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);

		$images = $xpath->query('descendant::img');

		foreach ($images as $image) {
			$new_image_parameters = array(
				'src' => $image->getAttribute('src'),
				'class' => $image->getAttribute('alt')
			);
			
			// Create the new image
			$new_image = $dom->createElement('img');
			$new_src = $dom->createAttribute('src');
			$new_src->value= $new_image_parameters['src'] . 'x2';
			$new_image->appendChild($new_src);

			$dom->replaceChild($new_image, $image);
			

			//return $new_image;
		}*/

	}

	function explodeLast($delimiter, $string) {
		$exploded = explode($delimiter, $string);

		$i = 0;
		$explodedPartOne = '';
		while ($i < count($exploded) - 1) {
			$explodedPartOne .= $exploded[$i] . $delimiter;
			$i++;
		}
		$explodedPartTwo = $exploded[$i];

		return array($explodedPartOne, $explodedPartTwo);
	}

		/*
		$dom = new DOMDocument();
		$dom->loadHTML(get_the_content());
		$xpath = new DOMXPath($dom);

		// XPath expression to get the first link's href-attribute
		// $link_href = $xpath->query('descendant::a/@href')->item(0)->nodeValue; // "Old" syntax
		$link_href = $xpath->query('descendant::a[not(contains(@rel, "footnote"))]/@href')->item(0)->nodeValue;
						
		echo '<h1><a href="' . $link_href . '">' . $hka_the_title . '</a> <a href="' . get_permalink() . '" class="permalink">∞</a> </h1>';
		*/
?>
