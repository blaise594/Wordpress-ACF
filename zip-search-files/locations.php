<?php 
 /*
Template Name: Location
*
*/
get_header(); 
nectar_page_header($post->ID); 

//full page
$fp_options = nectar_get_full_page_options();
extract($fp_options);
	
?>
<?php
//Function for the zip code search functionality
function zip_search($userZip){

    $args = array(
    'posts_per_page'    => -1,
	'post_type'         => 'Location',
	'post_status' => ('publish')
	
    );


$wp_query = new WP_Query($args); 
//var_dump($wp_query);
if( $wp_query->have_posts() ): while( $wp_query->have_posts() ) : $wp_query->the_post();
      $zipField=get_field('zip_codes_services');
          
          $zipString = $zipField . ', ';		
         
          $array = explode(', ' , $zipString); //split string into array seperated by ', '
		  
        foreach($array as $value) //loop over values
        {
			$cityField=get_field('city');
			$stateField=get_field('state');
 			            
            if($value==$userZip){
						 
               return ($cityField . '<br>' . $stateField); //print 
           }	
                
        }
       endwhile; 
       wp_reset_postdata(); 
endif;
}
?>
<div class="container-wrap">
	
	<div class="<?php if($page_full_screen_rows != 'on') echo 'container'; ?> main-content">
		
		<div class="row">
			
			<?php 

			//breadcrumbs
			if ( function_exists( 'yoast_breadcrumb' ) && !is_home() && !is_front_page() ){ yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } 

			 //buddypress
			 global $bp; 
			 if($bp && !bp_is_blog_page()) echo '<h1>' . get_the_title() . '</h1>';
			
			 //fullscreen rows
			 if($page_full_screen_rows == 'on') echo '<div id="nectar_fullscreen_rows" data-animation="'.$page_full_screen_rows_animation.'" data-row-bg-animation="'.$page_full_screen_rows_bg_img_animation.'" data-animation-speed="'.$page_full_screen_rows_animation_speed.'" data-content-overflow="'.$page_full_screen_rows_content_overflow.'" data-mobile-disable="'.$page_full_screen_rows_mobile_disable.'" data-dot-navigation="'.$page_full_screen_rows_dot_navigation.'" data-footer="'.$page_full_screen_rows_footer.'" data-anchors="'.$page_full_screen_rows_anchors.'">';

				 if(have_posts()) : while(have_posts()) : the_post(); 
					
					 the_content(); 
		
				 endwhile; endif; 
				
			if($page_full_screen_rows == 'on') echo '</div>'; ?>

		</div><!--/row-->
		
	</div><!--/container-->

	<div>
    <?php $zipcode = ( isset($_GET['zipcode']) ?  $_GET['zipcode'] : ''); echo zip_search($zipcode);?>    
    <html>
    <body>
    <form action="/location.php" method="get">
    <input type="text" class="form-control search-input" placeholder="Enter Zip Code" autocomplete="off" name="zipcode" value=""><br>
    <input type="submit">
    </form>
    </body>
    </html>
    </div>
    
</div><!--/container-wrap-->

<?php get_footer(); ?>