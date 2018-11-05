<?php
/***********************
/*  Testimonials CUSTOM POST TYPE
***********************/

// let's create the function for the custom type
function testimonials_post_type() { 
	// creating (registering) the custom type 
	register_post_type( 'testimonials', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Testimonials', ' salient-child' ), /* This is the Title of the Group */
			'singular_name' => __( 'Testimonial', ' salient-child' ), /* This is the individual type */
			'all_items' => __( 'All Testimonials', ' salient-child' ), /* the all items menu item */
			'add_new' => __( 'Add Testimonial', ' salient-child' ), /* The add new menu item */
			'add_new_item' => __( 'Add Testimonial', ' salient-child' ), /* Add New Display Title */
			'edit' => __( 'Edit', ' salient-child' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Testimonial', ' salient-child' ), /* Edit Display Title */
			'new_item' => __( 'New Testimonial', ' salient-child' ), /* New Display Title */
			'view_item' => __( 'View Testimonials', ' salient-child' ), /* View Display Title */
			'search_items' => __( 'Search Testimonials', ' salient-child' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', ' salient-child' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', ' salient-child' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'Adds Testimonials to the website', ' salient-child' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-format-status', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'Testimonials', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => false, /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'revisions')
		) /* end of options */
	); /* end of register post type */
}
	
// adding the function to the Wordpress init
add_action( 'init', 'testimonials_post_type');

//change the title description
function testimonials_change_admin_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'testimonials' == $screen->post_type ) {
          $title = 'Enter a Short Title to identify the testimonial (only used in admin)';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'testimonials_change_admin_title_text' );



/*-------------------------------------------------------------------------------
	Add Custom Columns to the main edit page
-------------------------------------------------------------------------------*/

function additional_testimonial_edit_chart_columns($columns)
{
	$columns = array(
		'cb'	 	=> '<input type="checkbox" />',
		'title' 	=> 'Title',
		'location'	=> 'Location',
		'testimonial_author'	=> 'Testimonial Author',
		'author'	=> 'Author',
		'date'		=> 'Date',
	);
	return $columns;
}

function add_additional_testimonial_edit_chart_columns($column)
{
	global $post;
	if($column == 'testimonial_author'){
		echo get_field('testimonial_author_name');
	}
	elseif($column == 'location')
	{
		$location_page_id = get_field('location_associated_with_testimonial');
		if($location_page_id){
			$location_page = get_post($location_page_id);
			echo '<a href="' . get_the_permalink($location_page_id) . '">' . $location_page->post_title . '</a>';
		}else{
			echo '&mdash; NA &mdash;';
		}
	}
	
	
}

add_action("manage_testimonials_posts_custom_column", "add_additional_testimonial_edit_chart_columns");
add_filter("manage_edit-testimonials_columns", "additional_testimonial_edit_chart_columns");

/*-------------------------------------------------------------------------------
	Sortable Columns
-------------------------------------------------------------------------------*/

function additional_testimonial_columns_register_sortable( $columns )
{
	$columns['location'] = 'Location';
	$columns['testimonial_author'] = 'Testimonial Author';
	return $columns;
}

add_filter("manage_edit-testimonials_sortable_columns", "additional_testimonial_columns_register_sortable" );


?>