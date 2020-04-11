<?php

/**
 * Triggers on plugin uninstall.
 * Clears database stored data.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) or die('Die!');


 
 // FIRST APPROACH: clear wordpress database data of the plugin, doesnt delete trashed data
 $posts = get_posts( array('post_type'=>'tq', 'numberposts'=> -1) );
 foreach($posts as $post){
     wp_delete_post($post->ID, false);
 } 



 // SECOND APPROACH: clear wordpress database data of the plugin
 /*
 global $wpdb;
 // deletes from wp_posts
 $wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'tq'");
 // deletes the postmeta, delete all non existent postids from wp_postmeta
 $wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
 // deletes relationships
 $wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");
 */