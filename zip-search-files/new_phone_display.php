<?php
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
    //Returns zip value from the details array
    $zip=$details->postal;
    return apply_filters('wpb_get_ip', $zip );
    }

//Returns correct phone number based on a user's location
function zip_display(){
    $userZip=get_the_user_ip();
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
                                  
                if($value==$userZip){
                    					
                    return ($permalink); //print
               }	
                    
            }
           endwhile; 
           wp_reset_postdata(); 
    endif;
}











//original
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
// 		$func=get_the_user_ip();
// 	var_dump($func);
// 	if(strlen($func)<20){$userCity=$func;}else{$userCity='Tampa';}
        $stateField=get_field('state');
        $cityField=get_field('city');                
        $phoneField=get_field('phone_number');
		$defaultPhoneNumber="1-866-766-5877";
        if($userCity==$cityField){
             return ( '<span class="phone-span">' . $phoneField . '</span>');
         }else{
    return ( '<span class="phone-span">' . $defaultPhoneNumber . '</span>');
}		
       endwhile; 
       wp_reset_postdata(); 
endif;
}
?>