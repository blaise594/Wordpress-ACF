<?php 

//Salient Themes
add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
			
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
}


// Register Custom Post Type
function location_post_type() {

	$labels = array(
		'name'                  => _x( 'Locations', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Location', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Locations', 'text_domain' ),
		'name_admin_bar'        => __( 'Location', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Location', 'text_domain' ),
		'description'           => __( 'Locations information page.', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' ),
		'taxonomies'            => array( 'region', 'city' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-site',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		//added to get rid of /blog in url		
		'rewrite' => array('slug' => 'location','with_front' => false),	
		'capability_type'       => 'page',
	);
	register_post_type( 'location', $args );

}
add_action( 'init', 'location_post_type', 0 );



add_filter('the_content', 'prepend_location_data' );
function prepend_location_data( $content ){
    if( is_singular('location')){
        
        $html=include("locations.php");
	return $content . $html;
    }
    return $content;
}





/*********************
ADD ACF OPTION PAGES
add the acf options tab to the admin navigation.
*********************/
//add the acf options tab to the admin navigation.
if(function_exists('acf_add_options_page')) { 

    acf_add_options_page();
    acf_add_options_sub_page('Header');
    acf_add_options_sub_page('Footer');
    //General Theme Options
    acf_add_options_page(array(
        'page_title'    => 'Website Options',
        'menu_title'    => 'Website Options',
        'menu_slug'     => 'website-options',
        'capability'    => 'edit_posts',
        'parent_slug'   => '',
        'position'      => false,
        'icon_url'      => false,
		'redirect'      => false,
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Header',
        'menu_title'    => 'Header',
        'menu_slug'     => 'website-options-header',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'website-options',
        'position'      => false,
        'icon_url'      => false,
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Footer',
        'menu_title'    => 'Footer',
        'menu_slug'     => 'website-options-footer',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'website-options',
        'position'      => false,
        'icon_url'      => false,
    ));
	
    
}
add_action('widgets_init', 'mat_widget_areas');
function mat_widget_areas(){
    register_sidebar(array(
        'name'  => 'Phone Widget',
        'id'    => 'phone-widget',
        'description' => 'The widget to display the correct phone number for a location near a user',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_wiget'   => '</li>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
?>

