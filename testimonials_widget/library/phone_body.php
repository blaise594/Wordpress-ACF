<?php
function phone_body(){
    $output = '<div id="phone-body">'
    $output.='Call Our Customer Service Team' 
    $output.=zip_display();
    $output.='or leave a message below'
    $output.='</div>'

    return $output;
}
add_shortcode('phone_body', 'phone_body');

function zip_echo(){

    $args = array(
    'posts_per_page'    => -1,
    'post_type'         => 'Locations'
    );

$wp_query = new WP_Query($args); 

if( $wp_query->have_posts() ): while( $wp_query->have_posts() ) : $wp_query->the_post();
      $zipField=get_field('zip_codes_services');
      $name=get_field('location_name');    
      
	  //echo $name . '<br>';
      echo '<div>' . $zipField . '</div>';
      

       endwhile; 
       wp_reset_postdata(); 
endif;
}
?>
<?php echo zip_echo(); ?>