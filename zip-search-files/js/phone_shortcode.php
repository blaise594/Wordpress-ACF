<?php

//Function that creates shortcode for the dynamic phone number
function phone_shortcode() {
    
 $code= '<div id="phone"><span class="phone-span">1-866-766-5877</span></div>';    

    return $code;
}
add_shortcode('phone', 'phone_shortcode');
?>