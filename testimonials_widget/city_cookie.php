<?php
function set_city_cookie($city) { 

    if(!isset($_COOKIE['city_cookie'])) {

    // set a cookie 
    setcookie('city_cookie', $city, time()+30);
     $last_city=$_COOKIE['city_cookie'];
        return $last_city;
    }

    } 
    add_action('init', 'set_city_cookie');
    Array ( [has_js] => 1 [wordpress_logged_in_4242c61769cbf868505f956452d7659b] => bmp|1541781177|A3t4HiTv7tNSVRCJ8jnJ9E3z945oGqvLbpzSItOTN8U|710d5aeda96be14f1f1b6f0df19a7ffce30c5a75d0d8704c2b62e8cb15616ee8 [wp-settings-1] => libraryContent=browse&edit_element_vcUIPanelWidth=1138&edit_element_vcUIPanelLeft=592px&edit_element_vcUIPanelTop=67px&editor=tinymce&advImgDetails=show&posts_list_mode=list [wp-settings-time-1] => 1541622190 )
    Array ( [has_js] => 1 [wordpress_logged_in_4242c61769cbf868505f956452d7659b] => bmp|1541781177|A3t4HiTv7tNSVRCJ8jnJ9E3z945oGqvLbpzSItOTN8U|710d5aeda96be14f1f1b6f0df19a7ffce30c5a75d0d8704c2b62e8cb15616ee8 [wp-settings-1] => libraryContent=browse&edit_element_vcUIPanelWidth=1138&edit_element_vcUIPanelLeft=592px&edit_element_vcUIPanelTop=67px&editor=tinymce&advImgDetails=show&posts_list_mode=list [wp-settings-time-1] => 1541622190 )
?>