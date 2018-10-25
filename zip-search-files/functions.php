<?php 

//Salient Themes
add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
			
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
}


//flush_rewrite_rules( false );

/** Add Custom Post Types to the site

Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'sal_flush_rewrite_rules' );
// Flush your rewrite rules
function sal_flush_rewrite_rules() {
	flush_rewrite_rules();
}
**/ 
require get_stylesheet_directory() . '/inc/locations.php';
//require get_template_directory() . '/inc/template_functions.php';


//Adds yext knowledge tags 
// add_action( 'wp_head', 'my_header_scripts' );
// function my_header_scripts(){
  
//   $posts = get_posts(array(
//     'posts_per_page'    => -1,
//     'post_type'         => 'Location'
//    ));

//   if( $posts ): 
//     $yext_tag=get_field('yext_tag');
//     echo $yext_tag;		
//     wp_reset_postdata(); 
//   endif; 
  
// }
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
	//$ip='104.238.96.194'; //--used this to test different IP addresses--
	
    //Uses ipinfo.io to find location information based on IP address
    $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));
    //Returns city value from the details array
    $city=$details->city;
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
//var_dump($wp_query);
if( $wp_query->have_posts() ): while( $wp_query->have_posts() ) : $wp_query->the_post();
		$userCity=get_the_user_ip();
        $stateField=get_field('state');
        $cityField=get_field('city');                
        $phoneField=get_field('phone_number');
		
        if($userCity==$cityField){
             return ( '<span class="phone-span">' . $phoneField . '</span>');
         }		
       endwhile; 
       wp_reset_postdata(); 
endif;
}


//Function that contains zip code functionality
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
		
        foreach($array as $value) //loop over values
        {
			$cityField=get_field('city');
			$stateField=get_field('state');
 			//echo $value. '<br>';            
            if($value==$userZip){
						 
               return ($cityField . '<br>' . $stateField); //print 
           }	
                
        }
       endwhile; 
       wp_reset_postdata(); 
endif;
}


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

