<?php 
    $specialty_args = array( 
        'posts_per_page' => -1, 
        'offset'=> 0, 
        'post_type' => 'specialties', 
        'post_status' => 'publish',
        'meta_key' => 'include_this_specialty_on_the_body_diagram',
        'meta_value' => 1 
    );

    $specialties = get_posts( $specialty_args );
    $specialties_query = new WP_Query($specialty_args);
    $specialties_count = count($specialties);

    $front_markers_array = array('shoulder','hand-wrist','knee');
    $anim_left_items_array = array('shoulder','hand-wrist','hip-thigh','headconcussion');

    if( $specialties_count > 0 ): ?>
            <?php 
                $front_markers = '';
                $back_markers = '';
                $animate_from_left_markers = '';

            // loop through the rows of data
            while ($specialties_query->have_posts()) : $specialties_query->the_post(); ?>
                <?php 
                    $animate_class = (in_array($post->post_name, $anim_left_items_array) ? 'left' : 'right');
                    $current_marker = '';
                    $current_marker.= '<div id="'. $post->post_name .'" class="marker ' . $animate_class . '">';
                    $current_marker.= '<div class="marker-info">';
                    $current_marker.= '<div class="marker-title-container"><div class="marker-title">' . get_the_title() . '</div></div>';
                    $current_marker.= '<div class="pointer-bar"></div>';
                    $current_marker.= '<div class="marker-description"><div class="description">' . get_the_excerpt();
                    $current_marker.= '<a href="' . get_the_permalink() .'" class="learn-more">Learn More <i class="fa fa-angle-right"></i></a>';
                    $current_marker.= '</div></div>';
                    $current_marker.= '</div>';
                    $current_marker.= '<div class="marker-dot"><span class="close-btn"><i class="fa fa-times"></i></span></div>';
                    $current_marker.= '</div>';

                    if(in_array($post->post_name, $front_markers_array) ){
                        $front_markers.= $current_marker;
                    }else{
                        $back_markers.= $current_marker;
                    }
                 ?>
            <?php endwhile; ?>

            <div id="body-diagram-container">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="diagram left">
                                <?php echo $front_markers ?>
                                <?php include (get_stylesheet_directory() . '/images/svg/figure-front.php'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="diagram right">
                                <?php echo $back_markers ?>
                                <?php include (get_stylesheet_directory() . '/images/svg/figure-back.php'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><a id="show-all-info-boxes" href="#">Show All</a></div>
                        <div class="col-md-6"><a id="hide-all-info-boxes" href="#">Hide All</a></div>
                    </div>
                </div>
            </div>  
    <?php endif;?>
<?php wp_reset_postdata(); ?>