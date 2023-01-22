<?php 

/**
 * Trigger this file on Plugin uninstall
 *
 * @package MJML Mail
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Clear Database stored data
$mjml_posts = get_posts( 
	array( 
		'post_type' => 'mjml-mail', 
		'numberposts' => -1 
	) 
);

foreach( $mjml_posts as $mjml_post ) {
	wp_delete_post( $mjml_post->ID, true );
}

// Access the database via SQL
/*global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'book'"  );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );
*/
