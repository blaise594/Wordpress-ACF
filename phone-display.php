

<?php 
//implemented into site
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


$posts = get_posts(array(
    'posts_per_page'    => -1,
    'post_type'         => 'location'
));
$userCity=get_the_user_ip();
if( $posts ): 
     foreach( $posts as $post ): 
		$stateField=get_field('state');
        $cityField=get_field('city');                
        $phoneField=get_field('phone_number');        
        if($userCity==$cityField){
             echo ( $cityField . ',' . ' ' . $stateField . '</br>' . $phoneField );
         }		
     endforeach;
    wp_reset_postdata(); 
 endif; 


?>
