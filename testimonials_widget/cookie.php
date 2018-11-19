<?php

add_action( 'init', 'setting_my_first_cookie' );

function setting_my_first_cookie() {
 setcookie( $v_username, $v_value, 1 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
}
if(!isset($_COOKIE[$v_username])) {
    echo "The cookie: '" . $v_username . "' is not set.";
  } else {
    echo "The cookie '" . $v_username . "' is set.";
    echo "Cookie is:  " . $_COOKIE[$v_username];
    
?>
<?php

function set_cookie($phone) {
		
    setcookie( $phone, $v_value, 1 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
   }
    ?>