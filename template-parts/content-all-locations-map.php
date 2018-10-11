<div id="all-locations-map"></div>
                            
    <script type="text/javascript">
    //<![CDATA[
        var locations = '';

        locations = [

            <?php
            $args = array( 
                'posts_per_page' => -1, 
                'offset'=> 0, 
                'post_type' => 'locations', 
                'post_status' => 'publish',
            );
            $myposts = get_posts( $args );

            $locations_query = new WP_Query($args);
            $count = 0;
            $postAmount = count(get_posts($args));

            $options = get_nectar_theme_options();
            $google_maps_api_key = $options['google-maps-api-key'];

            while ($locations_query->have_posts()) : $locations_query->the_post();

                $address = get_field('location_on_a_map');                            
                $lat = $address['lat'];
                $lng = $address['lng'];
            
                $title =  get_the_title();

                $address_display = '';
                $address_display .= (get_field('address_1') ? get_field('address_1') . '<br/>' : '');
                $address_display .= (get_field('address_2') ? get_field('address_2') . '<br/>' : '');
                $address_display .= (get_field('city') ? get_field('city') . ', ' : '');
                $address_display .= (get_field('state') ? get_field('state') . ' ' : '');
                $address_display .= (get_field('zipcode') ? get_field('zipcode') . ' ' : '');

                $office_type = get_field('facility_type');

                $urgent_care = 'normal';
                
                if(is_array($office_type)){
                    $urgent_care = (in_array('urgent-care', $office_type) ? 'urgent-care' : 'normal');
                }

                $phone_numbers = get_field('phone_numbers');
                $phone_count = count($phone_numbers);
                $phone_display = '';
                $i = 0;
                
                if($phone_count > 0){
                    foreach ($phone_numbers as $n) {
                        $i++;
                        $phone_display .= $n['phone_number'];
                        if($i < $phone_count){
                            $phone_display .= '<br/>';
                        }
                     } 
                }
                
                
                $address_link = "<a href='https://www.google.com/maps/dir/?api=1&destination=" . $lat . "," . $lng . "' target='_blank' class='directions-button'>Get Directions</a>";

                $map_info = "<div class='map-info-container'><div class='map-info'><h3 class='popup-title'>" . $title . "</h3><div class='address'>" . $address_display . "</div><div class='phone-numbers'>" . $phone_display . "</div>" . $address_link . "</div></div>";
                
                echo '["'. $map_info . '", ' . $lng . ', ' . $lat .', "' . $urgent_care . '"],';

                ?>

                <?php endwhile; ?>
                
            
                <?php wp_reset_postdata(); ?>
        ];                                      
        
        var map;

        function initMap() {
            var mapOptions = {
              scrollwheel: false,
              zoom: 20,
               zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL,
                    position: google.maps.ControlPosition.RIGHT_TOP
                },
              streetViewControl: false,
              center: new google.maps.LatLng(25.30, -75.00),
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              mapTypeControl: false
            }

            var map = new google.maps.Map(document.getElementById('all-locations-map'),  mapOptions);

            var infowindow = new google.maps.InfoWindow();
            var bounds = new google.maps.LatLngBounds();

            for (i = 0; i < locations.length; i++) {  

                var marker = '';
                
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][2], locations[i][1]),
                    animation: google.maps.Animation.DROP,
                    map: map
                });

                 // process multiple info windows
                (function(marker, i) {
                    // add click event
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    });
                })(marker, i);

                //extend the bounds to include each marker's position
                bounds.extend(marker.position);

                //now fit the map to the newly inclusive bounds
                map.fitBounds(bounds);

                //(optional) restore the zoom level after the map is done scaling
                // var listener = google.maps.event.addListener(map, "idle", function () {
                //     map.setZoom(14);
                //     google.maps.event.removeListener(listener);
                // });

            } 
        }


    //]]>

    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_maps_api_key ?>&callback=initMap"></script>