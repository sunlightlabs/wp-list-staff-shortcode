<?php

/*
Plugin Name: List Staff Shortcode
Description: Lightly modified version of List Pages Shortcode to just get staff by category.
Author: Bill Hunt
Version: 1.0.0
Author URI: http://www.krues8dr.com
*/

// [bartag foo="foo-value"]
function stafflist_function( $atts ) {
	  global $post;

	  $content = '';

	  $args = array(
			'meta_query' => array(
				array(
					'key'		=> 'staff-group',
					'value'		=> $atts['group'],
					'compare'	=> 'LIKE'
				)
			),
	  	'post_type' => 'page',
	  	'order' => 'ASC',
	  	'orderby' => 'menu_order',
	  	'nopaging' => true
  	);

		// The Query
		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			$content .= '<ul class="sunlightStaff">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$meta = get_post_meta($post->ID);

				$content .= '<li><a href="' . get_the_permalink() .'">
					<span class="imgWrapper">'. get_the_post_thumbnail() .'</span>
					<span class="name">'. get_the_title() .'</span>
					<span class="staffTitle">'. $meta['staff-role'][0] .'</span></a></li>';
			}
			$content .= '</ul>';
			/* Restore original Post Data */

		} else {
			// no posts found
		}
		wp_reset_postdata();
		return $content;
}
add_shortcode( 'stafflist', 'stafflist_function' );

function sfrex_function() {
  $args = array(
		'meta_query' => array(
			array(
				'key'		=> 'staff-group',
				'value'		=> 'staff',
				'compare'	=> 'LIKE'
			)
		),
  	'post_type' => 'page',
  	'order' => 'ASC',
  	'orderby' => 'menu_order',
  	'nopaging' => true
	);

	// The Query
	$the_query = new WP_Query( $args );

	$content = '';

	$people_count = $the_query->post_count;

	$days_since_epoch = ceil(time() / (24 * 60 * 60));

	$person_number = $days_since_epoch % $people_count;

	$count = 0;
	// The Loop
	if ( $the_query->have_posts() ) {

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			if($count == $person_number) {
				$content .= '
					<span class="img">'. get_the_post_thumbnail() .'</span>
					<span class="name">Today\'s Cleanosaur:<br/>'. get_the_title() .'</span>';
			}
			$count++;
		}
	}
	return $content;
}
add_shortcode( 'sfrex', 'sfrex_function' );

?>
