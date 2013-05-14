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
		$dom->loadHTML('<?xml encoding="UTF-8">' . $html);

		// Loop through all images
		$images = $dom->getElementsByTagName('img');
		foreach ($images as $image) {

			// Filters out the file suffix
			$src = explodeLast('.', $image->getAttribute('src'));

			// Filters out the size info in filenames
			$sizeless = explodeLast('-', $src[0]);
				
			$retina = $sizeless[0] . '1136x756.' . $src[1];
			$desktop = $sizeless[0] . '568x378.' . $src[1];
			$tablet = $sizeless[0] . '400x266.' . $src[1];

			// Replace the image
			$image->setAttribute('data-retina', $retina);
			$image->setAttribute('data-desktop', $desktop);
			$image->setAttribute('data-tablet', $tablet);

			// Remove size attributes
			$image->removeAttribute('width');
			$image->removeAttribute('height');
			//$image->setAttribute('width', '');
			//$image->setAttribute('height', '');

		}

		// Get the new HTML string
		$html = get_inner_html($dom->getElementsByTagName('body')->item(0));
		
		//$html = $dom->save();

		return $html;
	}

	function get_inner_html( $node ) { 
		$innerHTML= ''; 
		$children = $node->childNodes; 
		foreach ($children as $child) { 
			$innerHTML .= $child->ownerDocument->saveXML( $child ); 
		} 
		
		return $innerHTML;
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


	/* --------------------------------------------------
	 * --------------------------------------------------
	 * -------------------------------------------------- */

	/* Custom Post Types */
	function nm_loadReferenser() {
		register_post_type('nm_referenser',
			array (
					'labels' => array(
					'name' > __('Referenser'),
					'singular_name' => __('Referens'),
					'add_new' => __('Lägg till referens'),
					'menu_name' => __('Referenser'),
					'edit_item' => __('Redigera referens'),
					'view_item' => __('Visa referens'),
					'not_found' => __('Inga referenser kunde hittas.')
					),
				'public' => true,
				'rewrite' => array('slug' => 'referenser'),
				'menu_position' => __(20),
				'supports' => array('title')
			)
		);
	}

	function nm_addMetaBoxes() {
		add_meta_box('nm_referensranking', 'Prominens', 'nm_displayMetaFields', 'nm_referenser');
	}

	function nm_displayMetaFields() {
		global $post;

		$prominens = get_post_meta($post->ID, '_nm_custom_fields_prominens', true);

		echo '<div>
				<label for="ranging">Prominens</label>
				<select name="prominens">
					<option value="1"';
					
					if ($prominens == '1') echo ' selected ';
		
		echo '		>1 (Viktigast)</option>
					<option value="2"';
					
					if ($prominens == '2') echo ' selected ';
					
		echo '		>2</option>
					<option value="3"';
					
					if ($prominens == '3') echo ' selected ';
					
		echo '		>3 (minst viktig)</option>
				</select>
			</div>';

	
	}

	function nm_saveCustomFields() {
		if (isset($_POST['prominens'])) {
			update_post_meta($_POST['post_ID'], '_nm_custom_fields_prominens', $_POST['prominens']);
		}
	}

	add_action('save_post', 'nm_saveCustomFields');

	function nm_loadCustomPostTypes() { #nm is the "namespace" nordinmusic
		nm_loadReferenser();	
	}

	add_action('init', 'nm_loadCustomPostTypes');
	add_action('add_meta_boxes', 'nm_addMetaBoxes');


?>
