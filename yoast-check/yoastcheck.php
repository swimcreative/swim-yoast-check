<?php
/*
Plugin Name: Yoast Check
Plugin URI: https://swimcreative.com
description: Adds admin columns to check if Yoast SEO meta is populated with custom content
Version: 1.0
Author: Swim Creative
Author URI: https://swimcreative.com
License: GPL2
Tags: Yoast SEO, post, meta
*/

function yoast_check() {
	//check if yoast is activated
	if ( defined( 'WPSEO_VERSION' ) ) {
		//only run if in admin 
		if ( is_admin() ) {

			// get all post types
			$post_types = get_post_types( $args );

			//create array
			foreach ( $post_types as $post_type ) {
				$post_types_array[] = $post_type;
			}

			// create column
			function set_custom_edit_page_columns( $columns ) {
				$columns['yoast'] = __( 'Yoast Check' );
				return $columns;
			}
			//add data to column
			function custom_page_column( $column, $post_id ) {

				// title field check
				$check = get_post_meta( $post_id, '_yoast_wpseo_title', true );
				// description field check
				$desc = get_post_meta( $post_id, '_yoast_wpseo_metadesc', true );

				switch ( $column ) {

					case 'yoast':

						if ( $check )
							echo "<span style='font-size:1.25rem'>Title:&nbsp;👍</span>";
						else
							echo "&nbsp;<span style='font-size:1.25rem'>Title:&nbsp;🚫</span>";

						if ( $desc )
							echo "&nbsp;<span style='font-size:1.25rem'>Desc:&nbsp;👍</span>";
						else
							echo "&nbsp;<span style='font-size:1.25rem'>Desc:&nbsp;🚫</span>";

						break;

				}
			}
			// loop through and apppend columns
			foreach ( $post_types as $post_type ) {
				add_filter( 'manage_' . $post_type . '_posts_columns', 'set_custom_edit_page_columns' );
				add_action( 'manage_' . $post_type . '_posts_custom_column', 'custom_page_column', 10, 2 );
			}
		}
	}

}

// wait till after wp loaded to ensure we see the post types registered in the theme
add_action( 'wp_loaded', 'yoast_check', 99 );