<?php
function 
$posts = get_posts(array(
    'posts_per_page'    => -1,
    'post_type'         => 'location'
));
//Set user zip to 33606 for testing purposes
$userZip=33606;

if( $posts ): 
     foreach( $posts as $post ): 
        $zipField=get_field('zip_codes_serviced');
        
		//echo $zipField;
        $zipString = $zipField . ', ';
		//echo $zipArray;
        
        $array = explode(', ' , $zipString); //split string into array seperated by ', '
		
        foreach($array as $value) //loop over values
        {
			$cityField=get_field('city');
			$stateField=get_field('state');
 			//echo $value. '<br>';            
            if($value==$userZip){
						 
               echo ($cityField . '<br>' . $stateField); //print 
           }	
         }       
     endforeach;
    wp_reset_postdata(); 
 endif; 
?>