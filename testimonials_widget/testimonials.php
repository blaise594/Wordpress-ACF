<?php
//Testimonial widget
function sal_testimonials_widget($atts){
    
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'section_title' => 'Testimonials',
                'custom_location_filter' => '',
                
            ), $atts
        )
    );

    //post data
    $filter_ids = '';
    $post_type = '';

    //initial variables
    $location_filter = '';
    

    $custom_filters_added = ($custom_location_filter !== '' ? true : false);

    if($custom_filters_added){
        if($custom_location_filter !== ''){
            $filter_ids = $custom_location_filter;
            $post_type = 'Locations';
        }
        
    }else{
        global $post;
        $post_type = $post->post_type;
        $filter_ids= $post->ID;
    }

    $output = '<div class="testimonials-block-container">';
    $output.= sal_testimonials_block($post_type, true, $filter_ids, $section_title);
    $output.= '</div>';

    return $output;
}

add_shortcode('testimonials_widget', 'sal_testimonials_widget');

////Testimonials block
function sal_testimonials_block($testimonial_type, $enabled, $id, $section_title){
	  $testimonial_args = array( 
        'posts_per_page' => $init_amount_to_display, 
        'offset'=> 0, 
        'post_type' => 'testimonials', 
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $testimonials_id_array = array();

    $testimonials = get_posts( $testimonial_args );
	$current_post_id = get_the_ID();
	$current_post_type=get_post_type($current_post_ID);
	$output.='<div class="testimonial-slideshow">';
	if($testimonials):
        foreach ($testimonials as $item) {
			
			$location = get_field('location_associated_with_testimonial', $item );	
			$city= get_field('city', $location);
			$state= get_field('state', $location);
			$author=get_field('testimonial_authors_name', $item);
			$universal=get_field('is_universal', $item);
			$date=get_field('testimonial_date', $item);
			$customer_city=get_field('customer_city', $item);
			
			if($location==$current_post_id){				
                $output.='<div class="testimonial-slide">';				
				$output.= '<div class="testimonial-item">';
			    $output.= '<q class="testimonial">' . get_post_field('post_content', $item) . '</q>';			
                $output.= ( $author ? '<div class="testimonial-author">' . $author . '</div>' : '' );			
				
				$output.='<div class="testimonial-location">';
				if(strlen($customer_city)>1){$output.=$customer_city;}
				else{$output.= $city . ', ' . $state;}
				$output.='</div>';
				$output.= ( $date ? '<div class="testimonial-date">' . $date . '</div>' : '' );
                $output.= '</div>';
                $output.='</div>';					
			}
			if($testimonial_type!='locations'&&$universal==true){				
                $output.='<div class="testimonial-slide">';				
				$output.= '<div class="testimonial-item">';
			    $output.= '<q class="testimonial">' . get_post_field('post_content', $item) . '</q>';			
                $output.= ( $author ? '<div class="testimonial-author">' . $author . '</div>' : '' );
				$output.='<div class="testimonial-location">';
				if(strlen($customer_city)>1){$output.=$customer_city;}
				else{$output.= $city . ', ' . $state;}
				$output.='</div>';
				$output.= ( $date ? '<div class="testimonial-date">' . $date . '</div>' : '' );
                $output.= '</div>';
                $output.='</div>';					
			}
			
        }
        $output.='</div>';	 
		
    endif;
    return $output;
    

    
}
<script>document.getElementById("phone").innerHTML = '<?php echo zip_display(); ?>';</script>
<script>
	var lastphone = localStorage.getItem('lastphone'); 	
		if (lastphone.length>0) {document.getElementById("phone").innerHTML = lastphone;} 	
</script>
<script>document.getElementById("phone-footer").innerHTML = '<?php echo phone_footer(); ?>';</script>
<script>
	var lastphonefoot = localStorage.getItem('lastphonefoot'); 	
		if (lastphone.length>0) {document.getElementById("phone-footer").innerHTML = lastphonefoot;} 	
</script>
<script>
	var lastphonebody = localStorage.getItem('lastphonebody'); 	
		if (lastphone.length>0) {document.getElementById("phone-body").innerHTML = lastphonebody;} 	
</script>
?>