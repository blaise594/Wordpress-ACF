<?php
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


function sal_testimonials_block($testimonial_type, $enabled, $id, $section_title){
    global $post;
    $post_id = ($id ? $id : $post->ID);
    <div class="testimonial-slide">
    <div class="testimonial"><?php the_content() ?></div>
    <?php echo( $author ? '<div class="testimonial-author">' . "some words here" . '</div>' : '' ); ?>
</div>

    
}
?>