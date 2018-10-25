<?php
//Version circa 10/24/18
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
            $cityField=strtolower($cityField);
 			//echo $value. '<br>';            
            if($value==$userZip){
			   $url='http://dev-pool-troopers.pantheonsite.io/' . 'locations/' . $cityField . '-' . 'fl';		 
               return ($url); //print 
           }	
                
        }
       endwhile; 
       wp_reset_postdata(); 
endif;
}
?>