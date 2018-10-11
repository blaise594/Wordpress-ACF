<?php
/***********************
/*  LOCATIONS CUSTOM POST TYPE
***********************/

// let's create the function for the custom type
function locations_post_type() { 
	// creating (registering) the custom type 
	register_post_type( 'locations', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Locations', ' salient-child' ), /* This is the Title of the Group */
			'singular_name' => __( 'Location', ' salient-child' ), /* This is the individual type */
			'all_items' => __( 'All Locations', ' salient-child' ), /* the all items menu item */
			'add_new' => __( 'Add Location', ' salient-child' ), /* The add new menu item */
			'add_new_item' => __( 'Add Location', ' salient-child' ), /* Add New Display Title */
			'edit' => __( 'Edit', ' salient-child' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Location', ' salient-child' ), /* Edit Display Title */
			'new_item' => __( 'New Location', ' salient-child' ), /* New Display Title */
			'view_item' => __( 'View Locations', ' salient-child' ), /* View Display Title */
			'search_items' => __( 'Search Locations', ' salient-child' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', ' salient-child' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', ' salient-child' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'Adds Locations to the website', ' salient-child' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-location', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'locations', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => false, /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt')
		) /* end of options */
	); /* end of register post type */
}
	
// adding the function to the Wordpress init
add_action( 'init', 'locations_post_type');

?>