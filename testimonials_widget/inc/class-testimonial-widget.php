<?php
/**
* TESTIMONIALS SHORTCODE WIDGET
* 
* display a testimonials slider block on a page based on specific filter criteria
*
* @param  $atts = array of shortcode variables.
* @param  $atts[testimonial_type] = display testimonials that are attached to a specific post type.
* @param  $atts[section_title] = the title of the section
* @param  $atts[custom_page_id] = the id of the page attached to the testimonials content.  Leave blank to use current page.
*
* @return sting
*/

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
	$output.='<div class="testimonial-slideshow">';
	if($testimonials):
        foreach ($testimonials as $item) {
			
			$location = get_field('location_associated_with_testimonial', $item );	
			
			$author=get_field('testimonial_authors_name', $item);
			
			if($location==$current_post_id){
                $output.='<div class="testimonial-slide">';
				$output.= '<div class="testimonial-item">';
			    $output.= '<q class="testimonial">' . get_post_field('post_content', $item) . '</q>';			
                $output.= ( $author ? '<div class="testimonial-author">' . $author . '</div>' : '' );	
                $output.= '</div>';
                $output.='</div>';
			}
			
        }
        $output.='</div>';
    endif;
    return $output;
    

    
}

