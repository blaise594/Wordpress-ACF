<?php
/**
 * Convert Coordinates(Latitude, Longitude) to zipcode
 *
 * @param float $lat: Latitude
 * @param float $lng: Longitude
 * @return string: zipcode
 */
function ConvertLatlngToZipcode($lat, $lng) {
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

?>