

<?php
//This file contains a bunch of different things I tried for the zip search function

function zip_search($userZip){
    $posts = get_posts(array(
        'posts_per_page'    => -1,
        'post_type'         => 'Location'
    ));
   
	//var_dump($posts);
    if( $posts ) 
	
        foreach( $posts as $post )
			//TODO: Get each posts ID and assign to a variable
			
            $zipField=get_field('zip_codes_serviced', 1684);
            //var_dump($zipField);
            $zipString = $zipField . ', ';
            var_dump($zipString);

            $array = explode(', ' , $zipString); //split string into array seperated by ', '
             foreach($array as $value) //loop over values
             {
                $cityField=get_field('city', 1684);
                 $stateField=get_field('state', 1684);
                 var_dump($cityField)           
                if($value==$userZip){

                    return ($cityField . '<br>' . $stateField); //print 
                }   
             }       
		}
        wp_reset_postdata(); 
    } 
}
?>
<?php

function zip_search($userZip){
    $posts = get_posts(array(
        'posts_per_page'    => -1,
        'post_type'         => 'Location'
    ));
   
	//var_dump($posts);
    if( $posts ) 
	
        foreach( $posts as $post )
			//TODO: Get each posts ID and assign to a variable
			
            $zipField=get_field('zip_codes_serviced');
            //var_dump($zipField);
            $zipString = $zipField . ', ';
            
		}
        wp_reset_postdata(); 
    } 
}
?>
 <?php
            function zip_search($userZip){
            $posts = get_posts(array(
                'posts_per_page'    => -1,
                'post_type'         => 'Location'
            ));
				var_dump($posts); -->
//  			$post1=$posts[1];	
//              $ID= $posts[1]->ID;
//  			$meta=get_metadata('post', $ID);
//  				$metaID=$meta[0];
//  				var_dump($metaID);
				
        	
//             if( $posts ) {
        	
//                 foreach( $posts as $post )
//         			//TODO: Get each posts ID and assign to a variable
//         			$ID=$post->ID;
// 				    $meta=get_metadata('post', $ID);
// 				     var_dump($meta);
//                     $zipField=get_field('zip_codes_serviced');
//                     var_dump($zipField);
//                     $zipString = $zipField . ', ';
                    
//         		}
                 //wp_reset_postdata(); 
//       } 
        
//         ?>