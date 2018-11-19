<?php 

//Salient Themes
add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
			
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));
	wp_enqueue_style( 'slick',  get_stylesheet_directory_uri() . '/css/library/slick.css', array('parent-style','font-awesome'));
 	 wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/css/library/bootstrap.min.css', array('font-awesome'));

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
    wp_enqueue_script( 'slick-js', get_stylesheet_directory_uri() . '/js/lib/slick.min.js', array('jquery', 'superfish', 'custom-js'), '', true );	
   	wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '', true );	
	wp_localize_script( 'custom-js', 'ajaxcall', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
}


//flush_rewrite_rules( false );

/** Add Custom Post Types to the site

// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'sal_flush_rewrite_rules' );
// Flush your rewrite rules
function sal_flush_rewrite_rules() {
	flush_rewrite_rules();
}
**/ 
require get_stylesheet_directory() . '/inc/locations.php';
require get_stylesheet_directory() . '/inc/testimonials.php';

//Returns users city based on IP address
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
	$record = geoip_detect2_get_info_from_ip($ip, NULL);
    $city=$record->city->name;
    return apply_filters('wpb_get_ip', $city );
    }



//Returns correct phone number based on a user's location
function zip_display(){

    $args = array(
    'posts_per_page'    => -1,
	'post_type'         => 'Locations',
	'post_status' => ('publish')
	
    );

$wp_query = new WP_Query($args); 

if( $wp_query->have_posts() ): while( $wp_query->have_posts() ) : $wp_query->the_post();
		$userCity=get_the_user_ip();

        $cityField=get_field('city');                
        $phoneField=get_field('phone_number');		
        if($userCity==$cityField){
             return ('<a class="phone-span" href="tel:1-'. $phoneField . '">' . $phoneField . '</a>');
         }
       endwhile; 
       wp_reset_postdata(); 
endif;
}
add_shortcode('display_phone', 'zip_display');

//Returns correct phone number based on a user's location-- footer
function phone_footer(){

    $args = array(
    'posts_per_page'    => -1,
	'post_type'         => 'Locations',
	'post_status' => ('publish')
	
    );

$wp_query = new WP_Query($args); 

if( $wp_query->have_posts() ): while( $wp_query->have_posts() ) : $wp_query->the_post();
		$userCity=get_the_user_ip();        
        $cityField=get_field('city');                
        $phoneField=get_field('phone_number');		
        if($userCity==$cityField){
			
			$output='<a class="phone-footer-link" href="tel:1-'. $phoneField . '">' . $phoneField . '</a>';
            return $output;
         }
       endwhile; 
       wp_reset_postdata(); 
endif;
}

/**
* LOCATION SEARCH FILTER AJAX
* 
* call location search filter ajax
*
* @return ajax json data via function.
*/
add_action( 'wp_ajax_locations_search', 'prefix_ajax_locations_search' );
add_action( 'wp_ajax_nopriv_locations_search', 'prefix_ajax_locations_search' ); //used for handling AJAX requests from unauthenticated users

function prefix_ajax_locations_search() {
    // Handle request then generate response using WP_Ajax_Response
    $zipcode = $_POST[ 'zipcode' ];

    //return our filtered location data
    echo zip_search($zipcode);
    
    wp_die(); // this is required to terminate immediately and return a proper response 
}

//Function that contains zip code search functionality
function zip_search($userZip){

    $args = array(
    'posts_per_page'    => -1,
    'post_type'         => 'Locations'
    );

$wp_query = new WP_Query($args); 

if( $wp_query->have_posts() ): while( $wp_query->have_posts() ) : $wp_query->the_post();
      $zipField=get_field('zip_codes_services');
          
          $zipString = $zipField . ', ';		
        
          $array = explode(', ' , $zipString); //split string into array seperated by ', '
		  $count=0;
        foreach($array as $value) //loop over values
        {
					          
            if($value==$userZip){
				$post_id = get_the_ID();
				$permalink=get_permalink($post_id);	
                return ($permalink); //print
           }	
                
        }

       endwhile; 
       wp_reset_postdata(); 
endif;
}


//Function that creates shortcode for the zip code search form
function zip_search_form() {
    
 $form='<form id="zipcode" action="" method="post"><input class="form-control search-input" autocomplete="off" name="zipcode" type="text" value="" placeholder="Enter Zip Code" />
 <input type="submit" /></form>'; 
    

    return $form;
}
add_shortcode('zip_search', 'zip_search_form');

//Testimonial widget
function sal_testimonials_widget($atts){
    
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'section_title' => 'Testimonials',
                'custom_location_filter' => '',
                
            ), $atts
        )
    );

    //post data
    $filter_ids = '';
    $post_type = '';

    //initial variables
    $location_filter = '';
    

    $custom_filters_added = ($custom_location_filter !== '' ? true : false);

    if($custom_filters_added){
        if($custom_location_filter !== ''){
            $filter_ids = $custom_location_filter;
            $post_type = 'Locations';
        }
        
    }else{
        global $post;
        $post_type = $post->post_type;
        $filter_ids= $post->ID;
    }

    $output = '<div class="testimonials-block-container">';
    $output.= sal_testimonials_block($post_type, true, $filter_ids, $section_title);
    $output.= '</div>';

    return $output;
}

add_shortcode('testimonials_widget', 'sal_testimonials_widget');

////Testimonials block
function sal_testimonials_block($testimonial_type, $enabled, $id, $section_title){
	  $testimonial_args = array( 
        'posts_per_page' => $init_amount_to_display, 
        'offset'=> 0, 
        'post_type' => 'testimonials', 
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $testimonials_id_array = array();

    $testimonials = get_posts( $testimonial_args );
	$current_post_id = get_the_ID();
	
	$output.='<div class="testimonial-slideshow">';
	if($testimonials):
        foreach ($testimonials as $item) {
			
			$location = get_field('location_associated_with_testimonial', $item );	
			$city= get_field('city', $location);
			$state= get_field('state', $location);
			$author=get_field('testimonial_authors_name', $item);
			
			if($location==$current_post_id){
                $output.='<div class="testimonial-slide">';
				$output.= '<div class="testimonial-item">';
			    $output.= '<q class="testimonial">' . get_post_field('post_content', $item) . '</q>';			
                $output.= ( $author ? '<div class="testimonial-author">' . $author . '</div>' : '' );
				$output.='<div class="testimonial-location">' . $city . ', ' . $state . '</div>';
                $output.= '</div>';
                $output.='</div>';
			}
			
        }
        $output.='</div>';	   
    endif;
    return $output;
    

    
}

//Adds yext script to locations pages
function yext_schema(){
	$yext = get_field('yext_tag');
	if(strlen($yext)>5){
		echo $yext;
	} 
}

function set_city_cookie($city) { 

    if(!isset($_COOKIE['city_cookie'])) {

    // set a cookie 
    setcookie('city_cookie', $city, time()+30);
     $last_city=$_COOKIE['city_cookie'];
        return $last_city;
    }
} 
add_action('init', 'set_city_cookie');
function cta(){
    $output='<div id="phone-footer"><a href="tel:1-866-766-5877">1-866-766-5877</a></div>';	    
return $output;
}
add_shortcode('cta_footer', 'cta');
//Adds local instance of advanced custom fields
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5ba3b5c13221e',
	'title' => 'Locations',
	'fields' => array(
		array(
			'key' => 'field_5b92cda61adf8',
			'label' => 'Location Name',
			'name' => 'location_name',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5b92ce7f1adf9',
			'label' => 'Address Line 1',
			'name' => 'address1',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5b92cf061adfa',
			'label' => 'Address Line 2',
			'name' => 'address2',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5b92cf1c1adfb',
			'label' => 'City',
			'name' => 'city',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5b92cf571adfc',
			'label' => 'State',
			'name' => 'state',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Florida' => 'FL',
				'Texas' => 'TX',
				'Arizona' => 'AZ',
				'Nevada' => 'NV',
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5b92cfec1adfd',
			'label' => 'Zip Code',
			'name' => 'zip_code',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5b92d0631adfe',
			'label' => 'Phone Number',
			'name' => 'phone_number',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5b92d1331ae00',
			'label' => 'Zip Codes Serviced',
			'name' => 'zip_codes_services',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5ba4f7a520177',
			'label' => 'YEXT Knowledge Tag',
			'name' => 'yext_tag',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5be35d00d9356',
			'label' => 'YEXT Review Widget',
			'name' => 'yext_review',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'locations',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
?>
