<?php
function get_user_city(){
    $location_data = geoip_detect2_get_info_from_current_ip()->raw;
    $location = $location_data[ 'location' ];
    print_r($location);
    return $location;
}
<?php echo get_user_city() ?>
//Array ( [latitude] => 28.0772 [longitude] => -82.4455 [accuracy_radius] => 20 [metro_code] => 539 [time_zone] => America/New_York ) Array
?>