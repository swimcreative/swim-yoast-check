<?php
/**
 * Plugin Name: Swim Alternative Text Injector
 * Description: Automatically adds alt text to Gutenberg blocks entered in the Media Library.
 * Version: 1.0.0
 * Author: Swim Creative
 * Author URI: https://swimcreative.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */


 add_filter( 'render_block', function( $content, $block ) {
    if(!is_admin()) {

    /* COVER BLOCK */

    if( 'core/cover' == $block['blockName'] ) {

        $url = $block['attrs']['url'];
        if($url) {
         $image_id = attachment_url_to_postid( $url );
         //echo $image_id;
         $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

        // Empty alt
        if( false !== strpos( $content, 'alt=""' ) ) {
        $content = str_replace( 'alt=""', 'alt="' . $alt . '"', $content );
 
       // No alt
        } elseif( false === strpos( $content, 'alt="' ) ) {
         $content = str_replace( 'src="', 'alt="' . $alt . '" src="', $content );
          }
       }
    }

    /* GALLERY */

    if( 'core/gallery' == $block['blockName'] ) {
        
        $image_array = $block['attrs']['ids'];
       // print_R($block);
        foreach($image_array as $img) {
      
            $alt = get_post_meta( $img, '_wp_attachment_image_alt', true );

            if($alt) {
            // $content = get_post_gallery_images();
           //  $content = wp_get_attachment_image($img);
           //  $content .=
            // Empty alt
            if( false !== strpos( $content, 'alt=""' ) ) {
             $content = str_replace( 'alt="" data-id="'.$img.'"', 'alt="' . $alt . '" data-id="'.$img.'"', $content );
            // No alt
            } elseif( false === strpos( $content, 'alt="' ) ) {
             $content = str_replace( 'src="', 'alt="' . $alt . '" src="', $content );
            }
          }
        }

    }

     
    /* MEDIA TEXT */

    if( 'core/media-text' == $block['blockName'] ) {

    $alt = get_post_meta( $block['attrs']['mediaId'], '_wp_attachment_image_alt', true );
    if( empty( $alt ) )
       
    return $content;

    // Empty alt
    if( false !== strpos( $content, 'alt=""' ) ) {
        $content = str_replace( 'alt=""', 'alt="' . $alt . '"', $content );

    // No alt
    } elseif( false === strpos( $content, 'alt="' ) ) {
        $content = str_replace( 'src="', 'alt="' . $alt . '" src="', $content );
        }
    }


    /* IMAGE */

    if( 'core/image' !== $block['blockName'] )
        return $content;

    $alt = get_post_meta( $block['attrs']['id'], '_wp_attachment_image_alt', true );
    if( empty( $alt ) )
        return $content;

    // Empty alt
    if( false !== strpos( $content, 'alt=""' ) ) {
        $content = str_replace( 'alt=""', 'alt="' . $alt . '"', $content );

    // No alt
    } elseif( false === strpos( $content, 'alt="' ) ) {
        $content = str_replace( 'src="', 'alt="' . $alt . '" src="', $content );
        }
    }

    return $content;

 }, 10, 2 );
