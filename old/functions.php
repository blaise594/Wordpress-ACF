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
/**
function get_the_user_ip() {
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
    //Checks if IP is from shared internet
    $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
    //Checks if IP is passed from proxy
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } 
    else {
    //Most trustworthy source of IP address
    $ip = $_SERVER['REMOTE_ADDR'];
    }
    //Uses ipinfo.io to find location information based on IP address
    $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

    //Returns city value from the details array
    $city=$details->city;

    return apply_filters('wpb_get_ip', $city );
    }
function show_phone() {
  $posts = get_posts(array(
    'posts_per_page'    => -1,
    'post_type'         => 'location'
  ));
  $userCity=get_the_user_ip();
  if( $posts ): 
    foreach( $posts as $post ): 
        $cityField=get_field('city');        
        $phoneField=get_field('phone_number');
         if($userCity==$cityField){
			 //echo $phoneField;
             return $phoneField;
         }
     endforeach;
     wp_reset_postdata(); 
   endif; 
  }
add_shortcode('showphone', 'show_phone');

function show_phone() {
  $userCity=get_the_user_ip();
  $posts = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'location',
	'meta_key'		=> 'city',
	'meta_value'	=> '$userCity'
));
         
        $phoneField=get_field('phone_number');
         return $phoneField;
     wp_reset_postdata(); 
   
  }
add_shortcode('showphone', 'show_phone');
**/



add_filter('the_content', 'prepend_location_data' );
function prepend_location_data( $content ){
    if( is_singular('location')){
        
        $html=include("locations.php");
	return $content . $html;
    }
    return $content;
}

?>

