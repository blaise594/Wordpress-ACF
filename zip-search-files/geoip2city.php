<?php
function get_user_location(){
    $location_data = geoip_detect2_get_info_from_current_ip()->raw;
    $location = $location_data[ 'location' ];
    $lat= $location['latitude'];
    $lng=$location['longitude'];
    return $location;
}

function get_user_long(){
    $location_data = geoip_detect2_get_info_from_current_ip()->raw;
    $long = $location_data[ 'longitude' ];
    
    return $long;
}


<?php echo get_user_city() ?>
//Array ( [latitude] => 28.0772 [longitude] => -82.4455 [accuracy_radius] => 20 [metro_code] => 539 [time_zone] => America/New_York ) Array

function ConvertLatlngToZipcode() {
    $location_data = geoip_detect2_get_info_from_current_ip()->raw;
    $location = $location_data[ 'location' ];
    $lat= $location['latitude'];
    $lng=$location['longitude'];
    $latLng = array();
    $details_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . ",". $lng  ."&sensor=false";
    $ch = curl_init();
    //var_export($details_url);
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    if ($response['status'] != 'OK') {
        return null;
    }
    $data = array();
 
    //$latLng = $response['results'][0]['address_components'][5]['long_name'];
    foreach($response['results']['0']['address_components'] as $element){
        $data[ implode(' ',$element['types']) ] = $element['long_name'];
    }
    //var_export($latLng);
    return $data['postal_code'];
}

function convert($lat, $long){
    $details_url = "https://api.opencagedata.com/geocode/v1/json?q=" . $lat . "+". $long  ."&key=90c1152fc52743e395f82cb58cca1211";
    $details = json_decode(file_get_contents($details_url));
    var_dump($details);
}
?>