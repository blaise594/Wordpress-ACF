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
function zip_search($userZip){
    $posts = get_posts(array(
        'posts_per_page'    => -1,
        'post_type'         => 'location'
    ));
    //Set user zip to 33606 for testing purposes
    //$userZip=33606;

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
	<span><?php zip_search(33606);?></span>
	<span>Locations Landing Page--where zip code function can go </span>
</div><!--/container-wrap-->

<?php get_footer(); ?>