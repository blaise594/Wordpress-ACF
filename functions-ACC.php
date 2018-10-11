<?php 

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles_scripts');
function salient_child_enqueue_styles_scripts() {

    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/css/library/bootstrap.min.css', array('font-awesome'));
    wp_enqueue_style( 'bootstrap-theme', get_stylesheet_directory_uri() . '/css/library/bootstrap-theme.min.css', array('font-awesome'));

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));
    wp_enqueue_style( 'custom-style',  get_stylesheet_directory_uri() . '/css/custom.css', array('parent-style','font-awesome'));
    wp_enqueue_style( 'animate-library',  get_stylesheet_directory_uri() . '/css/library/animate.css', array('parent-style','font-awesome'));
    wp_enqueue_style( 'slick',  get_stylesheet_directory_uri() . '/css/library/slick.css', array('parent-style','font-awesome'));
    wp_enqueue_style( 'chosen',  get_stylesheet_directory_uri() . '/css/library/chosen/chosen.css', array('parent-style','font-awesome'));

    //add custom Google fonts.  The can be replaced with the Google fonts that are relevant to this theme.
    wp_enqueue_style('googleFonts-roboto', 'https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,900,700italic,500italic,400italic');
    

    wp_enqueue_script( 'modernizer-childtheme', get_stylesheet_directory_uri() . '/js/lib/modernizr.js', array('jquery'), '', false );
    wp_enqueue_script( 'waypoints-js', get_stylesheet_directory_uri() . '/js/lib/jquery.waypoints.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'countup-js', get_stylesheet_directory_uri() . '/js/lib/countUp.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'slick-js', get_stylesheet_directory_uri() . '/js/lib/slick.min.js', array('jquery', 'superfish', 'custom-js'), '', true );
    wp_enqueue_script( 'chosen-js', get_stylesheet_directory_uri() . '/js/lib/chosen.jquery.min.js', array('jquery', 'superfish', 'custom-js'), '', true );
    wp_enqueue_script( 'cookie-js', get_stylesheet_directory_uri() . '/js/lib/js.cookie.js', array('jquery'), '', true );
    wp_enqueue_script( 'traffic_source-js', get_stylesheet_directory_uri() . '/js/lib/traffic_source.js', array('jquery'), '', true );
	wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery', 'superfish'), '', true );
	wp_localize_script( 'custom-js', 'ajaxcall', array('ajaxurl' => admin_url( 'admin-ajax.php' )));

    $wp_siteurl = array( 'base_url' => get_site_url() );
    wp_localize_script( 'custom-js', 'wp_siteurl', $wp_siteurl );

    
    //addthis widget
    //Go to www.addthis.com/dashboard to customize your tools
    wp_enqueue_script( 'addthis-js', '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59dbd4e4fb885137', array('jquery'), '', true );
}
add_action('wp_enqueue_scripts', 'salient_child_remove_scripts', 100);

add_filter( 'script_loader_tag', 'sal_update_scripts', 10, 3 );
function sal_update_scripts( $tag, $handle, $src ) {

    // The handles of the enqueued scripts we want to defer
    $defer_scripts = array();

    $async_scripts = array( 
     'medchat'
    );

    if ( in_array( $handle, $defer_scripts ) ) {
        return '<script src="' . $src . '" type="text/javascript" defer></script>' . "\n";
    }

    if ( in_array( $handle, $async_scripts ) ) {
        return '<script src="' . $src . '" type="text/javascript" async></script>' . "\n";
    }
    
    return $tag;
} 

function salient_child_remove_scripts(){
    wp_dequeue_script( 'modernizer' );
}

add_image_size('home-search-thumbnail', 348, 256, array( 'left', 'top' ));
add_image_size('location-thumbnail', 606, 270, array( 'left', 'top' ));
add_image_size('physician-thumbnail', 209, 333, array( 'left', 'top' ));
// add_image_size('event-widget-2', 486, 300, true);
// add_image_size('event-widget-3', 485, 200, true);
// add_image_size('event-widget-4', 384, 250, true);
// add_image_size('blog-widget-thumbnail', 400, 300, true);
// add_image_size('video-widget-thumbnail-large', 500, 348, true);
// add_image_size('video-widget-thumbnail-small', 280, 180, true);


remove_action('add_meta_boxes', 'nectar_metabox_page');

function wpse59607_remove_meta_box( $callback )
{
    remove_meta_box( 'nectar-metabox-page-header' , 'page' , 'normal' );
    remove_meta_box( 'nectar-metabox-portfolio-display' , 'page' , 'normal' );
}

include("meta/page-meta.php");


/*********************
ADDITIONAL NAVIGATION
*********************/
// Create Wordpress Nav Menus.
register_nav_menus( array(
    'footer-copyright-menu' => __( 'Footer Copyright Menu')
) );

// footer copyright menu
function sal_footer_copyright_nav() {
    wp_nav_menu(array(
        'container' => false,                           // remove nav container
        'theme_location' => 'footer-copyright-menu',    // the location in the theme that this menu is tied to (see register_nav_menus) 
        'menu_class' => 'clearfix',        // adding custom nav class
        'menu_id' => 'footer-copyright-menu',           // id for the menu  
        'depth' => 0,                                   // limit the depth of the nav
    ));
}


//enable svg uploads in the Wordpress Media Upload
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


//Login Page URL Link and Tooltip title.
function loginpage_custom_link() {
    return get_site_url();
}
add_filter('login_headerurl','loginpage_custom_link');

function change_title_on_logo() {
    return get_bloginfo( 'name' );
}
add_filter('login_headertitle', 'change_title_on_logo');



//custom social media shortcode
function sal_social_media_links($params = array()) {
	//TODO:  add parameters to control which items show
	$options = get_nectar_theme_options(); 
	$object = '';
	$object .= '<ul class="widget-social">';

	$object .= (!empty($options['use-facebook-icon']) && $options['use-facebook-icon'] == 1) ? '<li><a target="_blank" href="' . $options['facebook-url'] . '"><i class="icon-facebook"></i> </a></li>' : '';
    $object .= (!empty($options['use-linkedin-icon']) && $options['use-linkedin-icon'] == 1) ? '<li><a target="_blank" href="' . $options['linkedin-url'] . '"><i class="icon-linkedin"></i> </a></li>' : '';
    $object .= (!empty($options['use-twitter-icon']) && $options['use-twitter-icon'] == 1) ? '<li><a target="_blank" href="' . $options['twitter-url'] . '"><i class="icon-twitter"></i> </a></li>' : '';
	$object .= (!empty($options['use-vimeo-icon']) && $options['use-vimeo-icon'] == 1) ? '<li><a target="_blank" href="' . $options['vimeo-url'] . '"><i class="icon-vimeo"></i> </a></li>' : '';
	$object .= (!empty($options['use-pinterest-icon']) && $options['use-pinterest-icon'] == 1) ? '<li><a target="_blank" href="' . $options['pinterest-url'] . '"><i class="icon-pinterest"></i> </a></li>' : '';
	$object .= (!empty($options['use-youtube-icon']) && $options['use-youtube-icon'] == 1) ? '<li><a target="_blank" href="' . $options['youtube-url'] . '"><i class="icon-youtube"></i> </a></li>' : '';
	$object .= (!empty($options['use-tumblr-icon']) && $options['use-tumblr-icon'] == 1) ? '<li><a target="_blank" href="' . $options['tumblr-url'] . '"><i class="icon-tumblr"></i> </a></li>' : '';
	$object .= (!empty($options['use-dribbble-icon']) && $options['use-dribbble-icon'] == 1) ? '<li><a target="_blank" href="' . $options['dribbble-url'] . '"><i class="icon-dribbble"></i> </a></li>' : '';
	$object .= (!empty($options['use-rss-icon']) && $options['use-rss-icon'] == 1) ? '<li><a target="_blank" href="' . $options['rss-url'] . '"><i class="icon-rss"></i> </a></li>' : '';
	$object .= (!empty($options['use-github-icon']) && $options['use-github-icon'] == 1) ? '<li><a target="_blank" href="' . $options['github-url'] . '"><i class="icon-github"></i> </a></li>' : '';
	$object .= (!empty($options['use-behance-icon']) && $options['use-behance-icon'] == 1) ? '<li><a target="_blank" href="' . $options['behance-url'] . '"><i class="icon-behance"></i> </a></li>' : '';
	$object .= (!empty($options['use-google-plus-icon']) && $options['use-google-plus-icon'] == 1) ? '<li><a target="_blank" href="' . $options['google-plus-url'] . '"><i class="icon-google-plus"></i> </a></li>' : '';
	$object .= (!empty($options['use-instagram-icon']) && $options['use-instagram-icon'] == 1) ? '<li><a target="_blank" href="' . $options['instagram-url'] . '"><i class="icon-instagram"></i> </a></li>' : '';
	$object .= (!empty($options['use-stackexchange-icon']) && $options['use-stackexchange-icon'] == 1) ? '<li><a target="_blank" href="' . $options['stackexchange-url'] . '"><i class="icon-stackexchange"></i> </a></li>' : '';
	$object .= (!empty($options['use-soundcloud-icon']) && $options['use-soundcloud-icon'] == 1) ? '<li><a target="_blank" href="' . $options['soundcloud-url'] . '"><i class="icon-soundcloud"></i> </a></li>' : '';
	$object .= (!empty($options['use-flickr-icon']) && $options['use-flickr-icon'] == 1) ? '<li><a target="_blank" href="' . $options['flickr-url'] . '"><i class="icon-flickr"></i> </a></li>' : '';
	$object .= (!empty($options['use-spotify-icon']) && $options['use-spotify-icon'] == 1) ? '<li><a target="_blank" href="' . $options['spotify-url'] . '"><i class="icon-spotify"></i> </a></li>' : '';
	$object .= (!empty($options['use-vk-icon']) && $options['use-vk-icon'] == 1) ? '<li><a target="_blank" href="' . $options['vk-url'] . '"><i class="icon-vk"></i> </a></li>' : '';
	$object .= (!empty($options['use-vine-icon']) && $options['use-vine-icon'] == 1) ? '<li><a target="_blank" href="' . $options['vine-url'] . '"><i class="icon-vine"></i> </a></li>' : '';
	$object .= '</ul>';

	return $object;
}
add_shortcode('theme_social_media_links', 'sal_social_media_links');


function sal_home_search_boxes() {
    //Location Box Settings
    $loc_photo = get_field('loc_photo', 'options');
    $loc_title = get_field('loc_box_title', 'options');
    $loc_box_description = get_field('loc_box_description', 'options');

    //Doctor Box Settings
    $doc_photo = get_field('doc_photo', 'options');
    $doc_box_title = get_field('doc_box_title', 'options');
    $doc_box_description = get_field('doc_box_description', 'options');

     //Doctor Box Settings
    $serv_photo = get_field('serv_photo', 'options');
    $serv_box_title = get_field('serv_box_title', 'options');
    $serv_box_description = get_field('serv_box_description', 'options');

    ?>

    <div id="home-search-boxes">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="search-box">
                        <?php echo (!empty($loc_photo) ? '<img src="' . $loc_photo['sizes']['home-search-thumbnail'] . '" alt="' . $loc_photo['alt'] . '">' : ''); ?>
                       <div class="box-info">
                            <?php echo (!empty($loc_title) ? '<h3 class="box-title">' . $loc_title . '</h3>' : ''); ?>
                            <?php echo (!empty($loc_box_description) ? '<div class="box-description">' . $loc_box_description . '</div>' : ''); ?>
                            <?php echo sal_custom_post_search_dropdown('locations', 'Choose the location closest to you', false, '', 'default-search'); ?>
                       </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="search-box">
                         <?php echo (!empty($doc_photo) ? '<img src="' . $doc_photo['sizes']['home-search-thumbnail'] . '" alt="' . $doc_photo['alt'] . '">' : ''); ?>
                         <div class="box-info">
                            <?php echo (!empty($doc_box_title) ? '<h3 class="box-title">' . $doc_box_title . '</h3>' : ''); ?>
                            <?php echo (!empty($doc_box_description) ? '<div class="box-description">' . $doc_box_description . '</div>' : ''); ?>
                            <?php echo sal_custom_post_search_dropdown('physicians', 'Which Doctor best fits your needs',false, '', 'default-search'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="search-box">
                         <?php echo (!empty($serv_photo) ? '<img src="' . $serv_photo['sizes']['home-search-thumbnail'] . '" alt="' . $serv_photo['alt'] . '">' : ''); ?>
                         <div class="box-info"> 
                            <?php echo (!empty($serv_box_title) ? '<h3 class="box-title">' . $serv_box_title . '</h3>' : ''); ?>
                            <?php echo (!empty($serv_box_description) ? '<div class="box-description">' . $serv_box_description . '</div>' : ''); ?>
                            <?php echo sal_custom_post_search_dropdown('services', 'What services can we help you with',false, '', 'default-search'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php }
add_shortcode('home_search_boxes', 'sal_home_search_boxes');


function sal_physicians_search_widget($atts) {
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'widget_title' => 'Find a Physician',
                'widget_layout' => 'boxed',
            ), $atts
        )
    );

    //get current main assigned physician page
    $assigned_physician_page_id = get_field('find_a_doctor_page', 'options');
    $pa_and_np_page_id = get_field('physician_assistants_and_nurse_practitioners_page', 'options');
    $view_physicians_text = get_field('view_physicians_text', 'options');
    $view_physician_assistants_text = get_field('view_physician_assistants_text', 'options');

    //get current page ID
    global $post;
    $current_page_id = $post->ID;

    $background_image_obj = get_field('search_widget_background_image', 'options');
    $search_background_image = ($background_image_obj ? $background_image_obj['sizes']['large'] : get_stylesheet_directory_uri() . '/images/find-physician-bkd.jpg');

    if($widget_title == ''){
        $widget_title = get_field('search_widget_section_title', 'options');
    }

    $right_text = get_field('section_right_text_links');


    $clear_class = (isset($_GET['specialty']) || isset($_GET['location']) || isset($_GET['service']) ? 'active' : '');

    $output = ($widget_layout == 'full' ? '<div class="physician-search-full-section right-aligned" style="background-image:url(' . $search_background_image . '); ' . ($background_image_obj ? 'background-size:cover;' : '') . '">' : '');
    $output.= ($widget_layout == 'full' ? '<div class="container">' : '');
    $output.= ($widget_layout == 'full' ? '<div class="row"><div class="col-md-8 col-md-offset-4 col-lg-9 col-lg-offset-3">' : '');

    $output.= '<div class="physician-search-widget layout-' . $widget_layout . '">';
    $output.= ($widget_title !== '' ? '<h3 class="physician-search-widget-title">' . $widget_title . '</h3>' : '');
    
    $output.= ($widget_layout == 'full' ? '<div class="row"><div class="col-md-6">' : '');
    $output.= '<div class="search-fields-container">';
    $output.= sal_custom_post_search_dropdown('physicians', 'By Name', false, '', 'physician-search');
    $output.= sal_custom_post_search_dropdown('specialties', 'By Specialty',true, 'All Specialties', 'physician-search');
    $output.= sal_custom_post_search_dropdown('services', 'By Services',true, 'All Services', 'physician-search');
    $output.= sal_custom_post_search_dropdown('locations', 'By Locations',true, 'All Locations', 'physician-search');
    $output.= '<div class="physician-search clear-search ' . $clear_class . '"><a href="#all">Clear Search</a></div>';

    $output.= '</div>';//.search-fields-container
    $output.= ($widget_layout == 'full' ? '</div>' : '');//.col-md-6
    $output.= ($widget_layout == 'full' ? '<div class="col-md-6">' : '');
    $output.= '<div class="links-container">';
    $output.= ($current_page_id !== $assigned_physician_page_id ? '<span class="button-purple-outline"><a href="' . get_the_permalink($assigned_physician_page_id) . '">' . $view_physicians_text . '</a></span>' : '');
    $output.= ($current_page_id !== $pa_and_np_page_id ? '<span class="button-pink-outline"><a href="' . get_the_permalink($pa_and_np_page_id) . '">' . $view_physician_assistants_text . '</a></span>' : '');
    $output.= '</div>';//.links-container
    $output.= ($widget_layout == 'full' ? '</div>' : '');//.col-md-6
    $output.= ($widget_layout == 'full' ? '</div>' : '');//.row

    $output.= '</div>';//.physician-search-widget

    $output.= ($widget_layout == 'full' ? '</div></div>' : '');//.row .col
    $output.= ($widget_layout == 'full' ? '</div>' : '');//.container
    $output.= ($widget_layout == 'full' ? '</div>' : '');//physician-search-full-section

    return $output;
}
add_shortcode('physicians_search', 'sal_physicians_search_widget');


//function used to limit the physician filter options to only items 
//that are associated with at least one physician
function physician_association_check($post_id, $field_checked){
     $physician_args = array( 
        'offset' => 0,
        'posts_per_page' => -1,
        'post_type' => 'physicians', 
        'orderby' => 'title', 
        'order' => 'ASC',
        'post_status' => 'publish'  
    );

    $physicians = get_posts( $physician_args );
    $physicians_count = count($physicians);

    $output = '';

    if($physicians_count > 0){
        foreach($physicians as $p){
            $p_ID = $p->ID;
            $associated_items = get_field($field_checked,$p_ID);
            $physician_associated = false;

            //make sure it is an array and has at least one item
            if(is_array($associated_items) && !empty($associated_items)){
                //cycle through the array to check if the physician is assocated with the post id
                foreach($associated_items as $item){
                    if($item->ID == $post_id){
                        $physician_associated = true;
                    }
                }
            }
            //don't continue once we know the physician is associated with this item
            if($physician_associated == true){
                break;
            }
        }
    }

    if( $physician_associated == true){
        return true;
    }else{
        return false;
    }
}



function sal_custom_post_search_dropdown($post_type, $default_text, $include_all_option, $all_text, $search_type){
    //check the url for initial search queries.  This will filter the page which is useful when linking from other pages via the dropdown options.
    $specialty_initial_query = ( !empty($_GET['specialty']) ? $_GET['specialty'] : '' );
    $location_initial_query = ( !empty($_GET['location']) ? $_GET['location'] : '' );

    //get current page ID
    global $post;
    $current_page_id = $post->ID;

    //get current main assigned physician page
    $assigne_physician_page_id = get_field('find_a_doctor_page', 'options');

    $physician_page_data_link = ($current_page_id == $assigne_physician_page_id ? '' : get_the_permalink($assigne_physician_page_id) );

    $search_args = array( 
        'offset' => 0,
        'posts_per_page' => -1,
        'post_type' => $post_type, 
        'orderby' => 'title', 
        'order' => 'ASC',
        'post_status' => 'publish',
        'post_parent' => 0
    );

    if($post_type == 'physicians'){
        $search_args['meta_key'] = 'last_name';
        $search_args['orderby'] = 'meta_value';
    }

    $results = get_posts( $search_args );
    $results_count = count($results);
    $output = '';

    if($results_count > 0):
        $output.= '<form method="GET" action="#" class="search-box-form" id="' . $post_type . '-search-form" data-redirect-page-url="' . $physician_page_data_link . '" data-search-type="' . $search_type . '">';
        $output.= '<div class="form-row">';
        $output.= '<select class="form-control" data-placeholder="' . $default_text . '" data-filter="' . $post_type . '" id="' . $post_type .'-select" name="' . $post_type . '">';
        $output.= '<option value="" class="reset-value" disabled selected data-id="">' . $default_text . '</option>';
        $output.= ($include_all_option == true ? '<option value="all" data-id="all">' . $all_text . '</option>' : '');

        $i= 0;

        foreach ( $results as $result ):
            $default_selected = '';
            $item_ID = $result->ID;
            $item_title = $result->post_title;
            $physician_included = false;

            if($post_type == 'services'){
                //function checks to make sure this id is assocaited with at least one physician. returns true or false.
                $physician_included = physician_association_check($item_ID,'services');
            }elseif($post_type == 'specialties'){
                //function checks to make sure this id is assocaited with at least one physician. returns true or false.
                $physician_included = physician_association_check($item_ID,'specialities');
            }elseif($post_type == 'locations'){
                //function checks to make sure this id is assocaited with at least one physician. returns true or false.
                $physician_included = physician_association_check($item_ID,'location_availability');
            }


            //change the title display for the physician names so their last name displays first.
            if($post_type == 'physicians'){
                $first_name = get_field('first_name',$item_ID);
                $last_name = get_field('last_name',$item_ID);
                $middle_name_initial = get_field('middle_name_initial',$item_ID);
                $physician_title = get_field('physician_title',$item_ID);
            
                $item_title = '';
                $item_title.= $last_name;
                $item_title.= ', ' . $first_name;
                $item_title.= ' ' . $middle_name_initial;
                $item_title.= ', ' . $physician_title;
            }
            //check the url for any requeries.  If they exist and match the current result id we set it as the default selected item.
            if( ($specialty_initial_query !== '' && $specialty_initial_query == $item_ID)  || ($location_initial_query !== '' && $location_initial_query == $item_ID)){
                $default_selected = 'selected';
            }

           if($search_type !== 'physician-search' || $post_type == 'physicians' || ($search_type == 'physician-search' && $physician_included == true) ){
                $output.= '<option value="' . get_the_permalink($item_ID) . '" data-id="' . $item_ID . '" ' . $default_selected . '>' . $item_title . '</option>';
           }
            
        endforeach;

        $output.='</select></div></form>';

    endif;

    return $output;
}


// CUSTOM CATEGORY ? TAXONOMY TERM DROP DOWN REDIRECT LIST
// custom function to create a select list based on a taxonomy term and parent_id.
// Note: The parent will not display in the list.

function sal_category_redirect_filter_shortcode($atts){
    extract(
        shortcode_atts(
            array(
                'category' => 'tribe_events_cat',
                'post_type' => 'tribe_events',
                'default_text' => 'Search...',
                'parent_id' => 11
            ), $atts
        )
    );

    //get current page ID
    global $post;
    $current_page_id = $post->ID;

    $term_args = array( 
        'taxonomy' => $category,
        'orderby' => 'name',
        'order' => ASC,
        'child_of' => $parent_id
    );

    $terms = get_terms( $term_args );
    $terms_count = count($terms);
    $output = '';

    if($terms_count > 0):
        $output.= '<form method="GET" action="#" class="search-box-form" id="' . $post_type . '-search-form" data-search-type="category">';
        $output.= '<div class="form-row">';
        $output.= '<select class="form-control" data-placeholder="' . $default_text . '" data-filter="' . $post_type . '" id="' . $post_type .'-select" name="' . $post_type . '">';
        $output.= '<option value="" class="reset-value" disabled selected data-id="">' . $default_text . '</option>';

        $i= 0;

        foreach ( $terms as $term ):
            $default_selected = '';
            $term_ID = $term->term_id;
            $term_title = $term->name;
            $term_slug = $term->slug;
            $term_url = get_term_link($term_slug, $category);
            $term_url.= ($category == 'tribe_events_cat' ? '/list' : '');

            $output.= '<option value="' . $term_url . '" data-id="' . $term_ID . '" ' . $default_selected . '>' . $term_title . '</option>';
            
        endforeach;

        $output.='</select></div></form>';

    endif;

    return $output;
}

add_shortcode('category_select_redirect', 'sal_category_redirect_filter_shortcode');


function sal_number_count($atts) {

     extract(
        shortcode_atts(
            array(
                'count' => 0,
            ), $atts
        )
    );

    return '<span class="count-number" data-count="' . $count . '"></span>';
}
add_shortcode('count_number_up', 'sal_number_count');



function sal_custom_post_list($atts){

    $content = '';

    extract(
        shortcode_atts(
            array(
                'post_type' => 'post',
                'two_columns' => 'false',
                'first_column_amount' => 6
            ), $atts
        )
    );

    $post_args = array( 
        'offset' => 0,
        'posts_per_page' => -1,
        'post_type' => $post_type, 
        'orderby' => 'title', 
        'order' => 'ASC',
        'post_status' => 'publish'  
    );

    $posts = get_posts( $post_args );
    $posts_count = count($posts);

    $item_count = 0;

    if($posts_count > 0):
    
    $content.= '<div class="custom-post-list">';
    $content.= '<div class="row">';
    $content.= '<div class="' . ($two_columns == 'true' ? 'col-md-6' : 'col-md-12') . '">';
    $content.= '<ul class="post-list">';
    
    foreach ( $posts as $post ):
        $item_count++;

        $content.= '<li>';
        $content.= '<a href="' . get_the_permalink($post->ID) . '">' . $post->post_title . '</a>';
        $content.= '</li>';
        $content.= ($item_count == $first_column_amount && $two_columns == 'true' ? '</ul></div><div class="col-md-6"><ul>' : '');
            
    endforeach;

    $content.= '</ul>';
    $content.= '</div>';
    $content.= '</div>';
    $content.= '</div>';

    endif; 

    return $content;
}

add_shortcode('custom_content_list', 'sal_custom_post_list');


/**
* PAGE PARENT SECTION LINKS SHORTCODE WIDGET
* 
* display a list of links for a specific section of pages based on the root parent element.
*
* @return output string
*/


function sal_generate_page_parent_page_section_links($atts){

    extract(
        shortcode_atts(
            array(
                'title' => 'Areas of Focus',
            ), $atts
        )
    );

    global $post;
    $post_id = $post->ID;
    $post_type = $post->post_type;
    $parent_post_id = $post->post_parent;

    $parents = get_post_ancestors( $post_id );
    /* Get the ID of the 'top most' Page if not return current page ID */
    $top_parent_id = ($parents) ? $parents[count($parents)-1]: $post->ID;

    $menu_args = array( 
        'post_type' => $post_type,
        'post_status' => 'publish',
        'sort_column' => 'menu_order',
        'sort_order' => 'ASC',
        'hierarchical' => 0,
        'child_of' => $top_parent_id
    );

    $menu_links = get_pages( $menu_args );

    $output = '<div class="section-page-links">';
    $output.= ($title !== '' ? '<h3 class="section-title">' . $title . '</h3>' : '');
    $output.= '<ul class="first-level">';
    $output.= '<li class="' . ($parent_post_id == 0 ? 'active' : '' ) . '">';
    $output.= '<span class="link-underline-blue"><a href="' . get_the_permalink($parent_post_id) .'">';
    $output.= get_the_title($parent_post_id);
    $output.= '</a></span>';
    $output.= '<ul class="second-level">';

    foreach ( $menu_links as $item ):
        $menu_id = $item->ID;

        $output.= '<li class="' . ($menu_id == $post_id ? 'active' : '' ) . '">';
        $output.= '<span class="link-underline-blue"><a href="' . get_the_permalink($menu_id) .'">';
        $output.= $item->post_title;
        $output.= '</a></span></li>';
    endforeach;

    $output.='</ul>';
    $output.='</li>';
    $output.='</ul>';
    $output.='</div>';
    
    return $output;
}
add_shortcode('parent_page_section_links', 'sal_generate_page_parent_page_section_links');


//adds a custom advanced gravity forms shortcode
function sal_advanced_gform($atts){

    extract(
        shortcode_atts(
            array(
                'form_id' => '',
                'form_title' => '',
                'populate_field_parameter' => '',
            ), $atts
        )
    );

    gravity_form_enqueue_scripts($form_id, true);
    $content = '<div class="advanced-gfrom-wrapper">';
    $content.= ($form_title !== '' ? '<h2>' . $form_title . '</h2>' : '');
    $content.= gravity_form( $form_id, false, true, false, array( $populate_field_parameter => get_the_title() ), true, 1, false );
    $content.= '</div>';

    return $content;
    
}
add_shortcode('gform_advanced', 'sal_advanced_gform');


//generate a custom sitemap
function sal_bottom_theme_cta_buttons(){

    //get button option settings
    $buttons = get_field('theme_btm_call_to_action_button','options');
    $count_btns = count($buttons);

    if($buttons){
        $output = '<div class="bottom-cta-links"><div class="container"><div class="row"><div class="cta-links-container">';
        $col_class = '';

        if($count_btns == 1){
            $col_class = 'col-md-12';
        }elseif($count_btns == 2){
            $col_class = 'col-md-6';
        }else{
            $col_class = 'col-md-4';
        }


        foreach ($buttons as $btn) {
            $title = $btn['button_text'];
            $link = $btn['button_link'];
            $cta_link = (strpos($link,'tel:') !== false || strpos($link,'mailto:') !== false ? $link : addhttp($link) );
            $new_window = $btn['button_link_new_window'];
            $target = ($new_window ? '_blank' : '_self');
            
            $output.= '<div class="' . $col_class . '"><div class="cta-link"><a href="' . $cta_link . '" target="' . $target . '"><div class="btn-text">' . $title . '</div></a></div></div>';            
        }

        $output.= '</div></div></div></div>';
    }

    return $output;
}
add_shortcode('bottom_theme_cta_buttons', 'sal_bottom_theme_cta_buttons');

function sal_bottom_theme_template_cta_buttons(){

    $default_image = get_stylesheet_directory_uri() . '/images/footer-cta-bkd.jpg';
    $background_image = $default_image;

    if(is_singular('assistants')){   
        $assistants_template_bkd = get_field( 'assistants_template_background_image', 'options' );
        $background_image = (is_array($assistants_template_bkd) ? $assistants_template_bkd['sizes']['large'] : $default_image);
    }
    if(is_singular('onc_social_worker')){   
        $onc_sw_template_bkd = get_field( 'onc_sw_template_background_image', 'options' );
        $background_image = (is_array($onc_sw_template_bkd) ? $onc_sw_template_bkd['sizes']['large'] : $default_image);
    }

    $background_style = ($background_image !== '' ? 'style="background-image:url(' . $background_image . ')"' : '');

    $output = '<div class="footer-template-content" ' . $background_style . '>';
    $output.= sal_bottom_theme_cta_buttons();
    $output.='</div>';

    return $output;
}

/*********************
ADD ACF OPTION PAGES
add the acf options tab to the admin navigation.
*********************/
//add the acf options tab to the admin navigation.
if(function_exists('acf_add_options_page')) { 

    //General Theme Options
    acf_add_options_page(array(
        'page_title'    => 'Website Options',
        'menu_title'    => 'Website Options',
        'menu_slug'     => 'florida_ortho_options',
        'capability'    => 'edit_posts',
        'icon_url'      => 'dashicons-admin-generic'
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Home Page Settings',
        'parent_slug'   => 'florida_ortho_options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Contact Settings',
        'parent_slug'   => 'florida_ortho_options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Assigned Pages',
        'parent_slug'   => 'florida_ortho_options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Bottom Call to Action Buttons',
        'menu_title'   => 'Bottom CTA Buttons',
        'parent_slug'   => 'florida_ortho_options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Locations Global Settings',
        'menu_title'   => 'Locations General Settings',
        'parent_slug'   => 'florida_ortho_options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Physicians Search Widget Global Settings',
        'menu_title'   => 'Physicians Search Widget Global Settings',
        'parent_slug'   => 'florida_ortho_options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Social Media',
        'menu_title'   =>  'Social Media',
        'parent_slug'   => 'florida_ortho_options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Analytics',
        'menu_title'   =>  'Analytics',
        'parent_slug'   => 'florida_ortho_options',
    ));
}


//ACF: Update the Google Maps API Key for the front end and backend of the site.
function my_acf_init() {    
    $options = get_nectar_theme_options(); 
    acf_update_setting('google_api_key', $options['google-maps-api-key']);
}
add_action('acf/init', 'my_acf_init', 10);

add_filter('acf/settings/google_api_key', function () {
    return 'AIzaSyA7TbEEb-n6OLFwNKYZ1UQcAycH40SEAi4';
});



/*********************
ADD HTTP
adds http to the front of a url if it is not already there.
*********************/
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

/*********************
PHONE NUMBERS TOUCH AND NO TOUCH
for each phone number it outputs one as a link for touch devices and one that is not a link for non touch devices 
*********************/
function sal_phone_numbers($phone_number, $schema_layout){
	$phone_link = str_replace(array('(', ')', '-', ' '), '', $phone_number);

	$schema_open = ($schema_layout == true ? '<span itemprop="addressLocality">' : '' );
	$schema_close = ($schema_layout == true ? '</span>' : '' );

	$output = '';

	$output.= '<div class="phone-number">';
	$output.= $schema_open;
	$output.= '<a href="tel:' . $phone_link . '">' . $phone_number . '</a>';
	$output.= $schema_close;
	$output.= '</div>';

	return $output;
}


/*********************
GENERATES A SHORTCODE GOOGLE MAP OF ALL THE LOCATIONS
each location is output on the map with a marker.
*********************/
function sal_locations_map(){
    ob_start();
    get_template_part('template-parts/content','all-locations-map');
    return ob_get_clean();
}

add_shortcode('all_locations_map', 'sal_locations_map');


/*********************
ALL LOCATIONS GRID
display a grid of the locations in the database
*********************/
function sal_locations_grid($atts){
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'location_type' => 'normal'
            ), $atts
        )
    );

    $type = ($location_type == 'urgent care' ? 'urgent-care' : 'not-urgent-care');

    ob_start();
    ?>
    <div class="locations-grid-container no-background">
        <div class="container">
            <div class="locations-section">
                <?php echo sal_all_locations_grid_display($type); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('locations_grid', 'sal_locations_grid');



/**
* VIDEO PORTFOLIO KEYWORED SEARCH DISPLAY
* 
* displays all of the videos with search options
*
* @param  $search_terms = string for the term that is being searched.
*
* @return html / ajax reloaded content
*/
function sal_video_keyword_search_display($search_terms){
    echo $search;

    global $wpdb, $paged, $max_num_pages, $current_date;

    // Add wildcards to our search results to display anything that matches
    $keyword = '%' . $search_terms . '%';

    $portfolio_query = $wpdb->prepare("SELECT * 
        FROM wp_posts AS posts 
        WHERE posts.post_type = %s 
        AND posts.post_status = %s
        AND posts.post_title LIKE %s
        ORDER BY posts.post_date DESC
        LIMIT 15 OFFSET 0", 'portfolio', 'publish', $keyword );

    $portfolioDBCall = $wpdb->get_results($portfolio_query, OBJECT);
    $count = 0;

   if($portfolioDBCall): ?>
        <div id="current-search-container">
            <div class="current-search">You are searching for: <?php echo $search_terms ?></div> 
            <a href="?cat=all" class="clear-search">Clear Search <i class="fa fa-times"></i></a>
        </div>
        <div class="portfolio-wrap">
            <div class="container">
                <div class="row no-masonry">

                   <?php  foreach ($portfolioDBCall as $item):
                        $count++;
                        $id = $item->ID;
                        $title = $item->post_title;
                        $thumbnail_id = get_post_thumbnail_id($id);

                        if( $thumbnail_id ) {    
                            list($portfolio_photo,$width,$height) = wp_get_attachment_image_src($thumbnail_id,'portfolio-thumb',false);
                        }

                        sal_video_html_grid_item($id, $title, $portfolio_photo);

                        //add clearfixes to the rows based on the size of the browser.
                        if($count % 2 == 0){
                            echo '<div class="clearfix visible-xs-block"></div>';
                        }

                        if($count % 3 == 0){
                            echo '<div class="clearfix hidden-xs"></div>';
                        }

                        endforeach;
                   ?>                   
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="error-notice">We are sorry but there are no videos that match your search.  Please try another search term or view videos by category.</div>
    <?php endif; ?>        
<?php 
}

/**
* VIDEO PORTFOLIO GRID ITEM HTML
* 
* displays the html used for the portfolio custom search ajax loops
*
* @param  $id = post item id.
* @param  $title = post item title.
* @param  $photo = post item photo.
*
* @return html
*/
function sal_video_html_grid_item($id, $title, $photo){
?>
    <div class="col-sm-4 col-xs-6">
        <div class="portfolio-item">
             <div class="thubmnail-container" style="background-image:url(<?php echo $photo ?>);">
                <div class="item-info"> 
                    <a href="https://www.floridaortho.com/wp-content/themes/salient/includes/portfolio-functions/video.php?post-id=<?php echo $id ?>&amp;iframe=true&amp;width=854" class="default-link watch-video-link" rel="prettyPhoto[portfolio_gal]">Watch Video</a> 
                    <a class="default-link details" href="<?php echo get_the_permalink($id); ?>">More Details</a>
                </div><!--item-info-->
            </div><!--thubmnail-container-->

            <div class="item-meta">
                <h4 class="item-title"><a href="<?php echo get_the_permalink($id); ?>"><?php echo $title; ?></a></h4>                           
            </div>
        </div>
    </div>    
<?php
}

/**
* VIDEO PORTFOLIO DEFAULT AND CATEOGRY SEARCH DISPLAY
* 
* displays the html returned from the main display and ajax call for category filtering
*
* @param  $category = category search slug.
* @param  $got_to_page = the ajax page number for the page that should be displayed.
*
* @return html / ajax reloaded content
*/
function sal_video_default_category_search_display($category, $got_to_page){

    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $current_page = ($got_to_page == '' || $got_to_page == 1 ? $paged : $got_to_page );

    $amount_per_page = 15;
    $posts_per_page = ( !empty($category) && $category !== 'all' ? -1 : $amount_per_page);

    //get the current offset to use with pagination
    $offset = ( $current_page == 1 ? 0 : $amount_per_page * ($current_page - 1) );

    $video_args = array( 
        'post_type' => 'portfolio', 
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => $posts_per_page, 
        'paged' => $paged,
        'offset' => $offset
    );

    $all_video_args = array( 
        'post_type' => 'portfolio', 
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1, 
    );

    if(!empty($category) && $category !== 'all'){
        $video_args['tax_query'] = array(
            array(
                'taxonomy' => 'project-type',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }

    $video_posts = get_posts( $video_args );

    $the_query = new WP_Query($video_args);
    $count = 0;
    $video_amount = count($video_posts);
    $all_videos_amount = count(get_posts( $all_video_args ));

    $last_page = ( ($amount_per_page * $current_page) >= $all_videos_amount ? true : false );
    $first_page = ( $current_page == 1 ? true : false );

    $prev_page = $current_page - 1;
    $next_page = $current_page + 1;

    if($video_amount > 0): ?>
        <div class="portfolio-wrap">
            <div class="container">
                <div class="row no-masonry">

                   <?php  while ($the_query->have_posts()) : $the_query->the_post();
                        $count++;
                        $id = get_the_ID();
                        $thumbnail_id = get_post_thumbnail_id();

                        if( $thumbnail_id ) {    
                            list($portfolio_photo,$width,$height) = wp_get_attachment_image_src($thumbnail_id,'portfolio-thumb',false);
                        }

                        sal_video_html_grid_item($id, get_the_title(), $portfolio_photo);

                        //add clearfixes to the rows based on the size of the browser.
                        if($count % 2 == 0){
                            echo '<div class="clearfix visible-xs-block"></div>';
                        }

                        if($count % 3 == 0){
                            echo '<div class="clearfix hidden-xs"></div>';
                        }

                        endwhile;
                   ?>
                </div>
            </div>
        </div>
        <?php if ($the_query->max_num_pages > 1 && ( empty($category) || $category == 'all') ): // check if the max number of pages is greater than 1.  Don't show the page if there is a category filter.  ?>
            <div id="pagination" class=" alt-style-padding" data-is-text="All items loaded">
                <?php echo (!$first_page ? '<div class="prev"><a href="#" data-page="' . $prev_page . '">Prev</a></div>' : '' ); ?>
                <?php echo (!$last_page ? '<div class="next"><a href="#" data-page="' . $next_page . '">Next</a></div>' : '' ); ?>
            </div>
            <div class="page-count">page <?php echo $current_page . ' of ' . $all_videos_amount ?></div>
        <?php endif; ?>
    <?php endif; ?>
        
    
<?php wp_reset_postdata();

}

/**
* VIDEO PORTFOLIO SEARCH SHORTCODE WIDGET
* 
* displays the advanced search video display from a shortcode
*
*
* @return shortcode html content
*/
function sal_video_search_widget(){
    $taxonomy = 'project-type';

    ob_start();

    if(!empty($_GET['search'])){
        $category_search = $_GET["search"];
    }

    if(!empty($_GET['cat'])){
        $category_filter = $_GET["cat"];
    }


    $term_arg = array(
        'taxonomy'=> $taxonomy, 
        'title_li' => '' ,
        'show_count' => 1,
        'echo' => false  
    );
    ?>
    <div class="custom-portfolio-filters">
        <div class="filter-options-container">
            <ul class="filter-options">
                <li><a href="#" data-tab="keyword-search" class="active">Keyword</a></li>
                <li><a href="#" data-tab="category-search">Category</a></li>
            </ul>
        </div>
        <div class="search-containers">
            <div id="keyword-search" class="keyword-search search-container active">
                <form method="GET" action="#" class="portfolio_search_form" id="portfolio_search_form">
                    <input type="text" name="search-terms" placeholder="Search All Videos"/>
                    <input class="portfolio-search-submit" data-display="all" type="submit" value="Search" />
                </form>
            </div>
            <div id="category-search" class="portfolio-filters search-container">
                <a href="#" data-sortable-label="Filter Videos" data-default-label="Filter Videos" id="sort-portfolio"><span>Filter Videos</span> <i class="icon-angle-down"></i></a>
                <ul>
                    <li class="cat-item <?php echo (empty($_GET['cat']) ? 'active' : ''); ?>"><a href="/all/">All</a></li>
                    <?php 
                        //need to make sure the count shows inside of the link using a preg_replace.  By default it shows outside of the link.
                        $cat_list = wp_list_categories($term_arg); 
                        //$cat_list = preg_replace('/\<\/a\> \((.*)\)/','<span class="post_count">($1)</span></a>',$cat_list);
                        $cat_list = sal_category_list($cat_list);

                        echo $cat_list;
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div id="portfolio-section">
        <div class="ajax-content-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" title="loading" aria-hidden="true"></i></div>
        <div id="portfolio-display-container">
            <?php 
            if($category_search){
                    sal_video_keyword_search_display($category_search); 
                }elseif($category_filter){
                    sal_video_default_category_search_display($category_filter, '');
                }else{
                    sal_video_default_category_search_display('', '');
                }
            ?>
        </div>
    </div>
<?php 
    return ob_get_clean();
}
add_shortcode('video_advanced_search', 'sal_video_search_widget');


/**
* PLACE POST COUNT INSIDE A LINK
* 
* makes sure the post count is inside the link for the category list in Wordpress.
*
* @return HTML
*/

add_filter( 'wp_list_categories', 'sal_category_list' );
function sal_category_list( $list ) {
    $list = str_replace( '</a> <span class="post_count">', ' <span class="post_count">(', $list );
    $list = str_replace( '</span>', ')</span></a>', $list );
    return $list;
}

/**
* AJAX CALL FOR THE PORTFOLIO SEARCH RESULTS
* 
* calls the sal_video_keyword_search_display based on the search results
*
* @return ajax code to our jquery function
*/
add_action( 'wp_ajax_portfolio_search_results', 'prefix_ajax_portfolio_search_results' );
add_action( 'wp_ajax_nopriv_portfolio_search_results', 'prefix_ajax_portfolio_search_results' );
function prefix_ajax_portfolio_search_results() {
    // Handle request then generate response using WP_Ajax_Response
    $search_terms = $_POST[ 'search_terms' ];
    
    // return all our data to an AJAX call
    sal_video_keyword_search_display($search_terms);

    wp_die(); // this is required to terminate immediately and return a proper response 
}

/**
* AJAX CALL FOR THE PORTFOLIO CATEGORY FILTER RESULTS
* 
* calls the sal_video_default_category_search_display based on the category filter
*
* @return ajax code to our jquery function
*/
add_action( 'wp_ajax_portfolio_category_filter', 'prefix_ajax_portfolio_category_filter' );
add_action( 'wp_ajax_nopriv_portfolio_category_filter', 'prefix_ajax_portfolio_category_filter' );
function prefix_ajax_portfolio_category_filter() {
    // Handle request then generate response using WP_Ajax_Response
    $category = $_POST[ 'cat_slug' ];
    $got_to_page = $_POST[ 'got_to_page' ];
    
    // return all our data to an AJAX call
    sal_video_default_category_search_display($category, $got_to_page);

    wp_die(); // this is required to terminate immediately and return a proper response 
}


function sal_get_pagenum_link( $pagenum = 1, $escape = true, $base = null ) {
    global $wp_rewrite;

    $pagenum = (int) $pagenum;

    $request = $base ? remove_query_arg( 'paged', $base ) : remove_query_arg( 'paged' );
}


/************************************************
GENERATES A SHORTCODE TO DISPLAY THE SOCIAL MEDIA 
CONTENT BLOCK FROM THE ACF OPTIONS SETTINGS
************************************************/
function sal_social_media_content_block($atts){
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'horizontal_position' => 'left',
                'vertical_position' => 'top'
            ), $atts
        )
    );

    $content = get_field('social_media_content_block','options');
    $output = '<div class="social-media-content-block ' . $horizontal_position . ' ' . $vertical_position . '">';
    $output.= '<div class="white-transparent-box">';
    $output.= $content;
    $output.= '</div>';
    $output.= '</div>';

    return $output;
}

add_shortcode('social_media_content_block', 'sal_social_media_content_block');



/**
 * Customize the main Wordpress wysiwyg editor to add some additional classes
 *
 *
 * @package Salient Child Theme
 */

/**
 * Apply styles to the visual editor
 */
add_filter('mce_css', 'mcekit_editor_style');
function mcekit_editor_style($url) {

    if ( !empty($url) )
        $url .= ',';

    // Retrieves the plugin directory URL
    // Change the path here if using different directories
    $url .= trailingslashit( get_stylesheet_directory_uri() ) . '/css/tinymce.css';

    return $url;
}

/**
 * Add "Styles" drop-down
 */
add_filter( 'mce_buttons_2', 'mce_editor_buttons' );

function mce_editor_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}

/**
 * Add styles/classes to the "Styles" drop-down
 */
add_filter( 'tiny_mce_before_init', 'mce_before_init' );

function mce_before_init( $settings ) {

    $style_formats = array(
        array(
            'title' => 'Link with Blue Underline',
            'inline' => 'span',
            'classes' => 'link-underline-blue',
            'wrapper' => true,
        ),
        array(
            'title' => 'Title with Blue Underline',
            'block' => 'div',
            'classes' => 'title-underline-blue',
            'wrapper' => true,
        ),
        array(
            'title' => 'Arrow List',
            'selector' => 'ul',
            'classes' => 'arrow-style-list',
        )
    );

    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;

}

/* Learn TinyMCE style format options at http://www.tinymce.com/wiki.php/Configuration:formats */

/*
 * Add custom stylesheet to the website front-end with hook 'wp_enqueue_scripts'
 */
add_action('wp_enqueue_scripts', 'mcekit_editor_enqueue');

// Add more buttons (font size select, superscript text, subscript text) to the rich text editor (TinyMCE) in WordPress
// $buttons is a variable of type array that contains default TinyMCE buttons for a particular row.
// I use array_unshift() to add the additional buttons in front of all the other buttons in the row. If you want to achieve the complete opposite, use array_push().

function register_additional_button($buttons) {
   array_unshift($buttons, 'sup', 'sub'); //'fontsizeselect' ,
   return $buttons;
}

// Assigns register_additional_button() to "mce_buttons_2" filter
add_filter('mce_buttons_2', 'register_additional_button');

/*
 * Enqueue stylesheet, if it exists.
 */
function mcekit_editor_enqueue() {
  $StyleUrl = get_stylesheet_directory_uri().'/css/tinymce.css'; // Customstyle.css is relative to the current file
		
  wp_enqueue_style( 'myCustomStyles', $StyleUrl );
}

function orderbyreplace($orderby) {
    return str_replace('menu_order', 'mt1.meta_value, mt2.meta_value', $orderby);
}




/** Add Custom Post Types to the site **/

// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'sal_flush_rewrite_rules' );

// Flush your rewrite rules
function sal_flush_rewrite_rules() {
	flush_rewrite_rules();
}

require get_stylesheet_directory() . '/inc/gforms.php';
require get_stylesheet_directory() . '/inc/woocommerce.php';

require get_stylesheet_directory() . '/inc/post_types/locations.php';
require get_stylesheet_directory() . '/inc/post_types/physicians.php';
require get_stylesheet_directory() . '/inc/post_types/oncology_social_workers.php';
require get_stylesheet_directory() . '/inc/post_types/assistants-pa-arnp.php';
require get_stylesheet_directory() . '/inc/post_types/specialties.php';
require get_stylesheet_directory() . '/inc/post_types/services.php';
require get_stylesheet_directory() . '/inc/post_types/testimonials.php';


function sal_all_locations_grid_display($filter){

    $args = array( 
        'posts_per_page' => -1, 
        'offset'=> 0, 
        'post_type' => 'locations', 
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    );

    if($filter == 'urgent-care'){
        $args['meta_query'] = array(
            array(
                'key'       => 'facility_type',
                'value'     => '"urgent-care"',
                'compare'   => 'LIKE',
            ),
        );
    }

    // if($filter == 'not-urgent-care'){
    //     $args['meta_query'] = array(
    //         array(
    //             'key'       => 'facility_type',
    //             'value'     => '"urgent-care"',
    //             'compare'   => 'NOT LIKE',
    //         ),
    //     );
    // }

    $myposts = get_posts( $args );

    $locations_query = new WP_Query($args);
    $count = 0;
    $postAmount = count(get_posts($args));

    if($postAmount > 0): ?>

               <?php  while ($locations_query->have_posts()) : $locations_query->the_post();
                    $title =  get_the_title();

                    $address_1 = (get_field('address_1') ? get_field('address_1') : '');
                    $address_2 = (get_field('address_2') ? ', ' .  get_field('address_2') : '');
                    $city = (get_field('city') ? get_field('city') . ', ' : '');
                    $state = (get_field('state') ? get_field('state') . ' ' : '');
                    $zipcode = (get_field('zipcode') ? get_field('zipcode') : '');

                    $office_type = get_field('facility_type');
                    $office_type_class = '';
                    
                    if( is_array($office_type) ){
                        $office_type_class = (in_array('urgent-care', $office_type) && $filter == 'urgent-care' ? 'urgent-care' : '');
                    }

                    $phone_numbers = get_field('phone_numbers');
                    $phone_count = count($phone_numbers);
	
					$fax_numbers = get_field('fax_numbers');
					$fax_count = count($fax_numbers);

                    $hours = get_field('hours_of_operation');

                    $include_review_section = get_field('include_review_section');
                    $yelp_review_link = get_field('yelp_review_link');
                    $google_review_link = get_field('google_review_link');

                    $col_class = 'col-md-4 ';

                    if($postAmount < 3){
                       // $col_class = ($postAmount == 2 ? 'col-md-6' : 'col-md-12');
                    }


                    if($count == 0 || $count % 3 == 0){
                        if($count > 0){
                            echo '</div>';
                        }
                        echo '<div class="row">';
                    }
                    ?>
                    <div class="<?php echo $col_class ?> col-sm-12">
                        <div class="location-item <?php echo $office_type_class ?>">
                            <h3><a href="<?php echo get_the_permalink($post->ID) ?>"><?php echo $title; ?></a></h3>
                            
                            <?php
                            $thumbnail_id = get_post_thumbnail_id();

                            if( $thumbnail_id ) {    
                                list($location_photo,$width,$height) = wp_get_attachment_image_src($thumbnail_id,'location-thumbnail',false);
                                $thumbnail_alt = get_post_meta($thumbnail_id,'_wp_attachment_image_alt', true); 

                                echo '<div class="location-photo"><img src=' . $location_photo . ' alt="' . $thumbnail_alt . '"></div>';
                            }

                            if(!empty($office_type) && ( (in_array('urgent-care', $office_type) && $filter !== 'urgent-care' ) || in_array('surgery-center', $office_type) ) ): ?>
                                <div class="location-office-types">
                                     <?php if(in_array('urgent-care', $office_type) && $filter !== 'urgent-care'): ?>
                                        <div class="office-type-item">
                                            <i class="fa fa-asterisk red"></i>Florida Orthopaedic Institute & Orthopaedic <span class="red-text">Urgent</span> Care
                                        </div>
                                    <?php endif; ?>

                                    <?php if(in_array('surgery-center', $office_type)): ?>
                                        <div class="office-type-item">
                                            <i class="fa fa-caret-right"></i>Florida Orthopaedic Institute & Surgery Center
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif;

                            echo '<div class="location-details">';

                                if(!empty($hours)):
                                 ?>
                                    <?php //location hours block ?>
                                    <div class="location-hours detail-block">
                                        <i class="fa fa-clock-o"></i>
                                        <?php foreach($hours as $hour): ?>
                                            <div class="hours-row">
                                                <span class="days"><?php echo $hour['days_of_the_week'] ?>: </span>
                                                <span class="hours"><?php echo $hour['hours_of_operation'] ?></span>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>


                                 <?php //location address block ?>
                                 <?php if($address_1 || $address_2 || $city || $state || $zipcode): ?>
                                     <div class="location-address detail-block">
                                         <i class="fa fa-map-marker"></i>

                                         
                                             <div class="location-address-box" itemscope="" itemtype="http://schema.org/Organization"> 
                                                <meta itemprop="name" content="<?php echo bloginfo( 'name' ); ?>">
                                                
                                                <?php  echo "<a href='http://maps.google.com/maps?q=" . $address_1 . "," . $city . "," . $state . "&z=17' target='_blank'>" ?>
                                                    <span itemprop="streetAddress">
                                                        <?php echo $address_1 ?><?php echo $address_2 ?>
                                                    </span><br/>
                                                    <span class="city-state-zip">
                                                        <span itemprop="addressLocality"><?php echo $city ?><?php echo $state ?></span>
                                                        <span itemprop="postalCode"><?php echo $zipcode ?></span>
                                                    </span>
                                                </a>
                                            </div>
                                     </div>
                                <?php endif; ?>
                                
                                <?php //location phone number block ?>
                                <?php
                                if($phone_numbers): ?>
                                    <div class="location-phone-numbers detail-block">
                                        <i class="fa fa-phone"></i>
                                        <?php foreach($phone_numbers as $number): ?>
                                            <div class="phone-number-row">
                                                <span class="phone-number"><?php echo sal_phone_numbers($number['phone_number'],true) ?></span>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                if($include_review_section && ($yelp_review_link || $google_review_link)): ?>
                                    <div class="location-reviews detail-block clearfix">
                                        <i class="fa fa-commenting"></i>
                                        <?php foreach($phone_numbers as $number): ?>
                                            <label>Review on:</label>
                                            <ul class="reviews-list">
                                                <?php if($yelp_review_link): ?>
                                                    <li><a class="yelp" href="<?php echo $yelp_review_link ?>" onclick="trackOutboundLink('<?php echo $yelp_review_link ?>'); return false;" target="_blank">Yelp</a></li>
                                                <?php endif; ?>
                                                <?php if($google_review_link): ?>
                                                    <li><a class="google" href="<?php echo $google_review_link ?>" onclick="trackOutboundLink('<?php echo $google_review_link ?>'); return false;" target="_blank">Google</a></li>
                                                <?php endif; ?>
                                            </ul>

                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <div class="location-link">
                                <a href="<?php echo get_the_permalink($post->ID); ?>">View More Information</a>
                            </div>


                        </div>
                     </div>

                    <?php 
                        $count++;

                        if($count == $postAmount){
                            echo '</div>';
                        }
                     ?>

                <?php endwhile; ?>
    <?php endif; ?>
        
    
<?php wp_reset_postdata();
}



/**
* PHYSICIAN SHORTCODE WIDGET
* 
* display a physician widget with a grid of all the physicians that match the arguments
*
* @param  $atts = array of shortcode variables.
* @param  $atts[location_filter] = filter by a specific location id
* @param  $atts[specialty_filter] = filter by a specific specialty id
* @param  $atts[service_filter] = filter by a specific service id
*
* @return sting
*/

function sal_physicians_widget($atts){

    //shortcode options
    extract(
        shortcode_atts(
            array(
                'custom_location_filter' => '',
                'custom_specialty_filter' => '',
                'custom_service_filter' => ''
            ), $atts
        )
    );

     //initial variables
    $location_filter = '';
    $specialty_filter = '';
    $service_filter = '';

    $custom_filters_added = ($custom_location_filter !== '' || $custom_specialty_filter !=='' || $custom_service_filter !== '' ? true : false);

    if($custom_filters_added){
        if($custom_location_filter !== ''){
            $location_filter = $custom_location_filter;
        }
        if($custom_specialty_filter !== ''){
            $specialty_filter = $custom_specialty_filter;
        }
        if($custom_service_filter !== ''){
            $service_filter = $custom_service_filter;
        }
    }else{
        //post data
        global $post;
        $post_id = $post->ID;
        $post_type = $post->post_type;

        switch ($post_type) {
            case 'locations':
                $location_filter = $post_id;
                break;
            case 'specialties':
                $specialty_filter = $post_id;
                break;
            case 'services':
                $service_filter = $post_id;
                break;
        }
    }

    $output = '<div id="physicians-section-widget">';
    $output.= '<div id="physicians-container" class="display-widget">';
    $output.= sal_return_physicians('physicians','',$location_filter, $specialty_filter, $service_filter, true);
    $output.= '</div>';
    $output.= '</div>';

    return $output;
}

add_shortcode('physicians_widget', 'sal_physicians_widget');


function sal_return_physicians($post_type,$letter_filter,$location_filter,$specialty_filter,$service_filter,$is_shortcode_widget){
    ob_start();

    global $wpdb;

    //if the all option has been selected then we reset the fitler to blank so we get all the results.
    $specialty_filter = ($specialty_filter == 'all' ? '' : $specialty_filter);
    $service_filter = ($service_filter == 'all' ? '' : $service_filter);
    $location_filter = ($location_filter == 'all' ? '' : $location_filter);

    //check what filters are included in this search
    $filters_array = array();

    if($location_filter !== ''){
        array_push($filters_array, 'location');
    }
    if($specialty_filter !== ''){
        array_push($filters_array, 'specialty');
    }
    if($service_filter !== ''){
        array_push($filters_array, 'service');
    }

    $count_filters = count($filters_array);

    $keyword = $letter_filter . '%';

    //some custom fields need to be mapped based on the type of post
    switch ($post_type) {
        case 'assistants':
            $last_name = 'assistants_last_name';
            break;
        case 'onc_social_worker':
            $last_name = 'social_worker_last_name';
            break;
        default:
            $last_name = 'last_name';
            break;
    }


    $physicians_query = $wpdb->prepare("SELECT * 
        FROM $wpdb->posts AS posts 
        LEFT JOIN $wpdb->postmeta AS meta ON meta.post_id = posts.ID
        WHERE 
        posts.post_type = %s 
        AND posts.post_status = %s
        AND meta.meta_key = %s
        AND meta.meta_value LIKE %s
        ORDER BY meta.meta_value ASC", $post_type, 'publish', $last_name, $keyword );

    $physiciansDBCall = $wpdb->get_results($physicians_query, OBJECT);

    $physicians_id_array = array();

    //error message
    $error_message = '<div class="container"><div class="empty-content-message">We are sorry but there are no physicians that match your combined search criteria.  Please try a new search.</div></div>';

    if($physiciansDBCall):

        foreach ($physiciansDBCall as $item) {
            //add all the physicians if there are no filters present.
            if($specialty_filter == '' && $location_filter == '' && $service_filter == ''){
                array_push($physicians_id_array, $item->ID);
            }
            elseif($specialty_filter !== '' || $location_filter !== '' || $service_filter !== ''){
                //set our variables to see which doctors match each of the filter possibilities
                $includes_specialty = false;
                $includes_service = false;
                $includes_location = false;

                //get all of the results associated with each doctor
                $all_specialties = get_field('specialities', $item->ID);
                $all_services = get_field('services', $item->ID);
                $all_locations = get_field('location_availability', $item->ID);

                $filter_amount_match = 0;
                $include_doctor = false;

                if($specialty_filter !== '' && $all_specialties){
                    foreach($all_specialties as $spec){
                        //check if the returned value has multiple comma separated ids
                        if(strpos($specialty_filter, ',') !== false){
                            $specialty_multiple_filter_ids = explode(',', $specialty_filter);
                            $includes_specialty  = ( in_array($spec->ID, $specialty_multiple_filter_ids) ? true : false);
                        }else{
                            $includes_specialty  = ($spec->ID == $specialty_filter ? true : false);
                        }
                        //end foreach loop once we have one true value because we know there is a match
                        if($includes_specialty == true){
                            break;
                        }
                    }
                }
                
                if($service_filter !== '' && $all_services){
                    foreach($all_services as $serv){
                        //check if the returned value has multiple comma separated ids
                        if(strpos($service_filter, ',') !== false){
                            $service_multiple_filter_ids = explode(',', $service_filter);
                            $includes_service  = ( in_array($serv->ID, $service_multiple_filter_ids) ? true : false);
                        }else{
                            $includes_service  = ($serv->ID == $service_filter ? true : false);
                        }
                        //end foreach loop once we have one true value because we know there is a match
                        if($includes_service == true){
                            break;
                        }
                    }


                }

                if($location_filter !== '' && $all_locations){
                    foreach($all_locations as $loc){
                        //check if the returned value has multiple comma separated ids
                        if(strpos($location_filter, ',') !== false){
                            $locations_multiple_filter_ids = explode(',', $location_filter);
                            $includes_location  = ( in_array($loc->ID, $locations_multiple_filter_ids) ? true : false);
                        }else{
                            $includes_location  = ($loc->ID == $location_filter ? true : false);
                        }
                        //end foreach loop once we have one true value because we know there is a match
                        if($includes_location == true){
                            break;
                        }
                    }
                }


                $count = 0;

                foreach($filters_array as $key => $value){
                    $count++;

                    if(${'includes_' . $value} == true ){
                        $filter_amount_match++;
                    }

                    //this pretty much checks for the end of the loop
                    if($count == $count_filters){
                        
                        if($filter_amount_match == $count_filters){
                            $include_doctor = true;
                        }
                    }
                 }

                 if($include_doctor == true){
                    array_push($physicians_id_array, $item->ID);
                 }
            }
        }

    $physician_count = count($physicians_id_array);

    $count = 0;
    $post_counter = 0;
    ?>
        
    <?php
        if($physician_count > 0):

            foreach ($physicians_id_array as $id):
                
                $physician_name =  get_the_title($id);

                //some custom fields need to be mapped based on the type of post
                switch ($post_type) {
                    case 'assistants':
                        $profile_photo_key = 'assistant_profile_photo';
                        break;
                    case 'onc_social_worker':
                        $profile_photo_key = 'social_worker_profile_photo';
                        break;
                    default:
                        $profile_photo_key = 'physician_profile_photo';
                        break;
                }

                $profile_photo = get_field($profile_photo_key, $id);
                $specialities = get_field('specialities', $id);
                $locations = get_field('location_availability', $id);
                $services = get_field('services', $id);
                $assistant_title = get_field('assistant_title', $id);
                $social_worker_title = get_field('social_worker_title', $id);
                $photo_displayed = ($profile_photo ? $profile_photo['sizes']['physician-thumbnail'] : get_stylesheet_directory_uri() . '/images/default-profile-photo.jpg'); 

                if($count == 0 || $count % 4 == 0){
                    if($count > 0){
                        echo '</div></div></div>';
                    }
                    echo '<div class="full-row"><div class="' . ($is_shortcode_widget == false ? 'container' : 'container widget-container') . '  tab-container"><div class="row">';
                }
                ?>           

                <div class="col-md-3">
                    <div class="physician-item">
                        <a href="<?php echo get_the_permalink($id) . ($is_shortcode_widget == false ? '?b=1' : '') ?>">
                            <?php     
                                echo '<div class="physician-photo" style="background-image:url(' . $photo_displayed . ')"></div>';
                            ?>

                            <div class="physician-details">
                                <div class="physician-name"><?php echo $physician_name ?></div>
                                <?php 
                                    $combined = '';
                                    $combined_full_array = array();

                                    //set our array of specialties and services based on if they are present as arrays or not
                                    if(is_array($specialities) && is_array($services)){
                                        $combined = array_merge($specialities, $services);
                                    }elseif(is_array($specialities)){
                                        $combined = $specialities;
                                    }elseif(is_array($services)){
                                        $combined = $services;
                                    }

                                    //check that an array of combined items exists and create a simplified array of link items.
                                    if(is_array($combined)){
                                        //alphabatize our array
                                        //sort($combined);

                                        foreach($combined as $post){
                                            $parent = $post->post_parent;

                                            //only show the top level items.
                                            if($parent == 0){
                                                array_push($combined_full_array, $post->post_title);
                                            }
                                        }
                                    }

                                    //count the items in our array
                                    $obj_count = count($combined_full_array);
                                    $serv_count = 0;

                                    if( $obj_count > 0 ): ?>
                                        <div class="specialties">
                                            <?php foreach($combined_full_array as $item): ?>
                                                <?php $serv_count++; ?>
                                                <?php echo $item .  ($serv_count < $obj_count ? ', ' : ''); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif;

                                    if($assistant_title): ?>
                                        <div class="specialties">
                                            <?php echo $assistant_title; ?>
                                        </div>
                                    <?php endif;

                                    if($social_worker_title): ?>
                                        <div class="specialties">
                                            <?php echo $social_worker_title; ?>
                                        </div>
                                    <?php endif;

                                ?>

                            </div>
                        </a>
                    </div>
                 </div>

                <?php 
                    $count++;

                    if($count == $physician_count){
                        echo '</div></div></div>';
                    }
                 ?>

            <?php endforeach; ?>
        <?php else: ?>
            <?php  echo ( !is_singular() ?  $error_message : '' ); ?>
        <?php endif; ?>
    <?php else: ?>
    <?php  echo ( !is_singular() ?  $error_message : '' ); ?>
<?php endif; ?>
    
        
    
<?php

return ob_get_clean();

}

function sal_physician_alpha_filter($post_type){
    $alphas = range('a', 'z');

    //some custom fields need to be mapped based on the type of post
    switch ($post_type) {
        case 'assistants':
            $last_name = 'assistants_last_name';
            break;
        case 'onc_social_worker':
            $last_name = 'social_worker_last_name';
            break;
        default:
            $last_name = 'last_name';
            break;
    }

    $physician_args = array( 
        'numberposts' => -1, 
        'offset'=> 0, 
        'post_type' => $post_type, 
        'post_status' => 'publish',
        'orderby' => 'meta_value',
        'meta_key' => $last_name,
        'order' => 'ASC'
    );

    $physicians = get_posts( $physician_args );

    $lastname_firstletter_array = array();
    $i = 0;

    foreach ($physicians as $p) {
        $lastname_firstletter_array[$i] = strtolower(substr(get_field($last_name, $p->ID), 0, 1));
        $i++;
    }

    $select_mobile_form = '';
    $select_mobile_form_options = '';
    $output.= '<div id="alphabet-physician-filter">';
    $output.= '<ul id="sort-by-lastname-list">';
    $output.= '<li class="filter-label">List by Last Initial:</li>';
            
    foreach($alphas as $a){
        $class = 'enabled';

        if(!in_array($a,$lastname_firstletter_array)){
            $class = 'disabled';
        }

        $output.= '<li class="filter-option ' . $class . '"><a href="#' . $a . '" >' . $a . '</a></li>';

        if(in_array($a,$lastname_firstletter_array)){
            $select_mobile_form_options.= '<option value="' . $a . '">' . $a . '</option>';
        }
        
    }

    $select_mobile_form.= '<form method="GET" action="#" id="mobile-alpha-filter">';
    $select_mobile_form.= '<div class="form-row">';
    $select_mobile_form.= '<select class="form-control" data-filter="" id="" name="">';
    $select_mobile_form.= '<option value="all">All</option>';
    $select_mobile_form.= $select_mobile_form_options;
    $select_mobile_form.= '</select></div></form>';

    $output.= '<li class="filter-option default enabled"><a href="#all" class="active">All</a></li>';
    $output.= '</ul>';
    $output.= $select_mobile_form;
    $output.= '</div>';


    return $output;
}


add_action( 'wp_ajax_filter_physicians', 'prefix_ajax_filter_physicians' );
add_action( 'wp_ajax_nopriv_filter_physicians', 'prefix_ajax_filter_physicians' );
function prefix_ajax_filter_physicians() {
    // Handle request then generate response using WP_Ajax_Response
    $post_type = $_POST[ 'post_type' ];
    $letter_filter = $_POST[ 'letter_filter' ];
    $location_filter = $_POST[ 'location_filter' ];
    $services_filter = $_POST[ 'services_filter' ];
    $specialty_filter = $_POST[ 'specialty_filter' ];

    // return all our data to an AJAX call
    echo sal_return_physicians($post_type,$letter_filter,$location_filter,$specialty_filter,$services_filter,false);

    wp_die(); // this is required to terminate immediately and return a proper response 
}



function sal_medical_professionals_display($atts){

    //shortcode options
    extract(
        shortcode_atts(
            array(
                'type' => 'Physicians'
            ), $atts
        )
    );

    $post_type = 'physicians';

    if($type == 'Assistants'){
        $post_type = 'assistants';
    }elseif($type == 'Oncology Social Workers'){
        $post_type = 'onc_social_worker';
    }

    //check the url for initial search queries.  This will filter the page which is useful when linking from other pages via the dropdown options.
    $specialty_initial_query = ( !empty($_GET['specialty']) ? $_GET['specialty'] : '' );
    $service_initial_query = ( !empty($_GET['service']) ? $_GET['service'] : '' );
    $location_initial_query = ( !empty($_GET['location']) ? $_GET['location'] : '' );

    $output = sal_physician_alpha_filter($post_type);
    $output.= '<div id="physicians-section" data-post-type="' . $post_type . '">';
    $output.= '<div class="ajax-content-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" title="loading" aria-hidden="true"></i></div>';
    $output.= '<div id="physicians-container" class="display-full">';
    $output.= sal_return_physicians($post_type,'',$location_initial_query,$specialty_initial_query,$service_initial_query,false);
    $output.= '</div>';
    $output.= '</div>';

    return $output;
}

add_shortcode('medical_professionals', 'sal_medical_professionals_display');


// function sal_assistants_display(){
//     $output = sal_physician_alpha_filter('assistants');
//     $output.= '<div id="physicians-section" data-post-type="assistants">';
//     $output.= '<div class="ajax-content-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" title="loading" aria-hidden="true"></i></div>';
//     $output.= '<div id="physicians-container" class="display-full">';
//     $output.= sal_return_physicians('assistants','','','','',false);
//     $output.= '</div>';
//     $output.= '</div>';

//     return $output;
// }

// add_shortcode('assistants_display', 'sal_assistants_display');

// function sal_oncology_social_workers_display(){
//     $output = sal_physician_alpha_filter('onc_social_worker');
//     $output.= '<div id="physicians-section" data-post-type="assistants">';
//     $output.= '<div class="ajax-content-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" title="loading" aria-hidden="true"></i></div>';
//     $output.= '<div id="physicians-container" class="display-full">';
//     $output.= sal_return_physicians('onc_social_worker','','','','',false);
//     $output.= '</div>';
//     $output.= '</div>';

//     return $output;
// }

// add_shortcode('onc_social_worker_display', 'sal_oncology_social_workers_display');


/**
* POST OBJECT TO LIST
* 
* takes a post object and turns it into a string of items
*
* @param  $array = the array we are running a foreach for.
* @param  $type = if you want the items to link use 'linclass="k';
* @param  $container_class = the class for the containing div.
* @param  $commas = true will add commas in betweene each item.
* @param  $html_list = true turn the list into an html ul li list.
*
* @return sting
*/

function sal_string_from_postobj($post_object,$type,$container_class, $commas, $html_list){
    if($post_object):

        $count = 0;
        $obj_count = count($post_object);
        $comma_class = ($commas == true ? ' with-commas' : '');
        $post_object_list = ($html_list == true ? '<ul class="' . $container_class . $comma_class . '">' : '<div class="' . $container_class . $comma_class . '">');

        foreach($post_object as $post):
                $count++;
                $post_object_list.= ($html_list == true ? '<li>' : '');
                $post_object_list.= ($type == 'link' ? '<a href="' . get_the_permalink($post->ID) . '" target="_blank">' : '');
                $post_object_list.= $post->post_title;
                $post_object_list.= ($type == 'link' ? '</a>' : '');
                $post_object_list.= ($count < $obj_count && $commas == true ? ', ' : '');
                $post_object_list.= ($html_list == true ? '</li>' : '');
        
        endforeach;

        $post_object_list.= ($html_list == true ? '</ul>' : '</div>');

        return $post_object_list;
    endif;
}


/**
* POST ID ARRAY TO LIST
* 
* takes an array of post IDs and turns it into a string of items
*
* @param  $array = the array we are running a foreach for.
* @param  $type = if you want the items to link use 'link';
* @param  $container_class = the class for the containing div.
* @param  $commas = true will add commas in betweene each item.
* @param  $html_list = true turn the list into an html ul li list.
*
* @return sting
*/

function sal_string_from_postarray($post_id_array,$type,$container_class, $commas, $html_list){
    if($post_id_array):

        $count = 0;
        $obj_count = count($post_id_array);

        $post_array_list = ($html_list == true ? '<ul class="' . $container_class . '">' : '<div class="' . $container_class . '">');

        foreach($post_id_array as $post_id):
            
                $count++;
                $post_array_list.= ($html_list == true ? '<li>' : '');
                $post_array_list.= ($type == 'link' ? '<a href="' . get_the_permalink($post_id) . '" target="_blank">' : '');
                $post_array_list.= get_the_title($post_id);
                $post_array_list.= ($type == 'link' ? '</a>' : '');
                $post_array_list.= ($count < $obj_count && $commas == true ? ', ' : '');
                $post_array_list.= ($html_list == true ? '</li>' : '');
        
        endforeach;

        $post_array_list.= ($html_list == true ? '</ul>' : '</div>');

        return $post_array_list;
    endif;
}



/**
* POST OBJECT TO LIST
* 
* takes a post object and turns it into a string of items
*
* @param  $array = the array we are running a foreach for.
* @param  $type = if you want the items to link use 'link';
* @param  $container_class = the class for the containing div.
* @param  $commas = true will add commas in betweene each item.
* @param  $html_list = true turn the list into an html ul li list.
*
* @return sting
*/

function sal_testimonials_block($testimonial_type, $enabled, $id, $section_title){
    global $post;
    $post_id = ($id ? $id : $post->ID);

    if($enabled == true){

        $background_style = get_field('testimonial_slider_background_style', $post_id); 
        $uploaded_background_image = get_field('custom_testimonial_section_background', $post_id);        
        $background = (!empty($uploaded_background_image) && $background_style == 'custom-background'  ? $uploaded_background_image['sizes']['large'] : get_stylesheet_directory_uri() . '/images/testimonials-default-background.jpg');
        $background_style = ($background_style == 'default-background' || $background_style == 'custom-background' ? 'style="background-image:url(' . $background . ')"' : '');


        $testimonial_args = array( 
            'posts_per_page' => -1, 
            'offset'=> 0, 
            'post_type' => 'testimonials', 
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        );

        if($testimonial_type == 'physician'){
            $testimonial_args['meta_query'] = array(
                array(
                    'key'       => 'doctor_associated_with_testimonial',
                    'value'     => $post_id,
                    'compare'   => '=',
                ),
            );
        }elseif($testimonial_type == 'location'){
            $testimonial_args['meta_query'] = array(
                array(
                    'key'       => 'location_associated_with_testimonial',
                    'value'     => $post_id,
                    'compare'   => '=',
                ),
            );
        }

        $testimonials = get_posts( $testimonial_args );
        $testimonials_query = new WP_Query($testimonial_args);

        $testimonials_id_array = array();

    if($testimonials):

        //set our variables to see which doctors have match each of the filter possibilities
        $includes_id = false;

        foreach ($testimonials as $item) {

            if($testimonial_type == 'physician' || $testimonial_type == 'location'){
                array_push($testimonials_id_array, $item->ID);
            }
            elseif($testimonial_type == 'services'){
                $all_services = get_field('services_associated_with_testimonial', $item->ID);

                if( is_array($all_services) ){

                    foreach ($all_services as $key => $value) {
                        //check if the returned value has multiple comma separated ids
                        if(strpos($value, ',') !== false){
                            $value_multiple = explode(',', $value);
                            $includes_id  = ( in_array($post_id, $value_multiple) ? true : false);
                        }else{
                            $includes_id  = ($post_id == $value ? true : false);
                        }

                        if($includes_id == true){
                            array_push($testimonials_id_array, $item->ID);
                        }
                    }
                }
            }
            elseif($testimonial_type == 'specialties'){
                $all_specialties = get_field('specialties_associated_with_testimonial', $item->ID);

                if( is_array($all_specialties) ){
                    foreach ($all_specialties as $key => $value) {
                        //check if the returned value has multiple comma separated ids
                        if(strpos($value, ',') !== false){
                            $value_multiple = explode(',', $value);
                            $includes_id  = ( in_array($post_id, $value_multiple) ? true : false);
                        }else{
                            $includes_id  = ($post_id == $value ? true : false);
                        }

                        if($includes_id == true){
                            array_push($testimonials_id_array, $item->ID);
                        }
                    }
                }
            }
            elseif($testimonial_type == 'regen_medicine'){
                $all_regen_medicine = get_field('regen_medicine_associated_with_testimonial', $item->ID);

                foreach ($all_regen_medicine as $key => $value) {
                    //check if the returned value has multiple comma separated ids
                    if(strpos($value, ',') !== false){
                        $value_multiple = explode(',', $value);
                        $includes_id  = ( in_array($post_id, $value_multiple) ? true : false);
                    }else{
                        $includes_id  = ($post_id == $value ? true : false);
                    }

                    if($includes_id == true){
                        array_push($testimonials_id_array, $item->ID);
                    }
                }
            }
        }

        $included_testimonials_count = count($testimonials_id_array);

        if( $included_testimonials_count > 0 ): ?>
            <?php global $post; ?>
            <div class="testimonials-content-block" <?php echo $background_style ?>>
                <div class="container">
                    <h2><?php echo ($section_title ? $section_title : 'Testimonials'); ?></h2>
                        <div class="testimonial-slideshow">
                        <?php // loop through the rows of data
                        foreach ($testimonials_id_array as $post): ?>
                            <?php 
                                setup_postdata($post);
                                $author = get_field('testimonial_author_name', get_the_ID() );
                             ?>
                            <div class="testimonial-slide">
                                <div class="testimonial"><?php the_content() ?></div>
                                <?php echo( $author ? '<div class="testimonial-author">' . $author . '</div>' : '' ); ?>
                            </div>
                        <?php endforeach; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        <?php endif;?>
    <?php endif; ?>
    <?php
    }
}

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

function sal_testimonials_widget($atts){
    
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'section_title' => 'Testimonials',
                'custom_location_filter' => '',
                'custom_specialty_filter' => '',
                'custom_service_filter' => ''
            ), $atts
        )
    );

    //post data
    $filter_ids = '';
    $post_type = '';

    //initial variables
    $location_filter = '';
    $specialty_filter = '';
    $service_filter = '';

    $custom_filters_added = ($custom_location_filter !== '' || $custom_specialty_filter !=='' || $custom_service_filter !== '' ? true : false);

    if($custom_filters_added){
        if($custom_location_filter !== ''){
            $filter_ids = $custom_location_filter;
            $post_type = 'locations';
        }
        if($custom_specialty_filter !== ''){
            $filter_ids = $custom_specialty_filter;
            $post_type = 'specialties';
        }
        if($custom_service_filter !== ''){
            $filter_ids = $custom_service_filter;
            $post_type = 'services';
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



function sal_testimonials_display($atts){

    //shortcode options
    extract(
        shortcode_atts(
            array(
                'initial_amount' => 20
            ), $atts
        )
    );

    //check the url for initial search queries.  This will filter the page which is useful when linking from other pages via the dropdown options.
    $physician_initial_query = ( !empty($_GET['physician']) ? $_GET['physician'] : '' );

    $output = '<div id="testimonials-section">';
    $output.= '<div class="ajax-content-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" title="loading" aria-hidden="true"></i></div>';
    $output.= '<div id="testimonials-container" class="display-full">';
    $output.= sal_return_testimonials($physician_initial_query, $initial_amount);
    $output.= '</div>';
    $output.= '</div>';

    return $output;
}

add_shortcode('all_testimonials', 'sal_testimonials_display');


/**
* ALL TESTIMONIALS FUNCTION
* 
* returns all of the testimonials in the database.
*
* @return sting
*/

function sal_return_testimonials($doctor_name_filter,$init_amount_to_display){
    global $post;

    //if the all option has been selected then we reset the fitler to blank so we get all the results.
    $doctor_name_filter = ($doctor_name_filter == 'all' ? '' : $doctor_name_filter);

    //amount to show initially before any searches
    $init_amount_to_display = ($init_amount_to_display == '' ? -1 : $init_amount_to_display);

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

    $output = '';

    //add a variable to store our empty return notice.
    $empty_notice = 'We are sorry but there are no testimonials to display for this physician.  Please try searching for another physician.';
    $empty_content = '<div class="container"><div class="empty-content-message full-width">' . $empty_notice . '</div></div>';

    if($testimonials):
        foreach ($testimonials as $item) {
            //add all the testimonials if there is no doctor name filter present.
            if($doctor_name_filter == ''){
                array_push($testimonials_id_array, $item->ID);
            }
            else{
                //get the doctor associated with
                $doctor_id = get_field('doctor_associated_with_testimonial', $item->ID);


                if($doctor_id == $doctor_name_filter){
                    array_push($testimonials_id_array, $item->ID);
                }
            }
        }

        $count_testimonials = count($testimonials_id_array);

        if($count_testimonials > 0):
            $output.= '<div id="testimonials-block">';
            $output.= '<div class="testimonial-content">';
            
            foreach ($testimonials_id_array as $id):
                $author = get_field('testimonial_author_name', $id );
                $doctor_for_id = get_field('doctor_associated_with_testimonial', $id );
                $location_treated_at = get_field('location_associated_with_testimonial', $id );
                $services_used = get_field('services_associated_with_testimonial', $id );
                $specialties_used = get_field('specialties_associated_with_testimonial', $id );

                $output.= '<div class="testimonial-item">';
                $output.= '<q class="testimonial">' . get_post_field('post_content', $id) . '</q>';
                $output.= ( $author ? '<div class="testimonial-author">' . $author . '</div>' : '' );
                $output.= ( $doctor_for_id ? '<div class="associated-item"><span class="label">Treated By:</span><a href="' . get_the_permalink($doctor_for_id) . '" target="_blank">' . get_the_title($doctor_for_id) . '</a></div>' : '' );
                $output.= ( $location_treated_at ? '<div class="associated-item"><span class="label">Location Treated At:</span><a href="' . get_the_permalink($location_treated_at) . '" target="_blank">' . get_the_title($location_treated_at) . '</a></div>' : '' );
                $output.= ( $services_used ? '<div class="associated-list"><span class="label">Services Used:</span>' . sal_string_from_postarray($services_used,'link', 'available-items', true, false)  . '</div>' : '' );
                $output.= ( $specialties_used ? '<div class="associated-list"><span class="label">Specialties Used:</span>' . sal_string_from_postarray($specialties_used,'link', 'available-items', true, false)  . '</div>' : '' );
                $output.= '</div>';
            endforeach;

            $output.= '</div>';
            $output.= '</div>';
        else:
            $output.= $empty_content;
        endif;
    else:
        $output.= $empty_content;
    endif;

    return $output;
}

add_action( 'wp_ajax_filter_testimonials', 'prefix_ajax_filter_testimonials' );
add_action( 'wp_ajax_nopriv_filter_testimonials', 'prefix_ajax_filter_testimonials' );
function prefix_ajax_filter_testimonials() {
    // Handle request then generate response using WP_Ajax_Response
    $doctor_name_filter = $_POST[ 'doctor_name_filter' ];
    
    // return all our data to an AJAX call
    echo sal_return_testimonials($doctor_name_filter,'');

    wp_die(); // this is required to terminate immediately and return a proper response 
}

/**
* TESTIMONIALS SEARCH WIDGET
* 
* returns all of the testimonials in the database.
*
* @return sting
*/

function sal_testimonials_search_widget($atts) {
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'widget_title' => 'Search Testimonials',
                'widget_layout' => 'boxed',
            ), $atts
        )
    );

    $output = '<div class="testimonial-search-widget layout-' . $widget_layout . '">';
    $output.= ($widget_title !== '' ? '<h3 class="search-widget-title">' . $widget_title . '</h3>' : '');
    $output.= '<div class="search-fields-container">';
    $output.= sal_custom_post_search_dropdown('physicians', 'By Physician\'s Name', true, 'All Physicians', 'testimonials-search');
    $output.= '<div class="testimonial-search clear-search"><a href="#all">Clear Search</a></div>';
    $output.= '</div>';
    $output.= '</div>';

    return $output;
}
add_shortcode('testimonials_search', 'sal_testimonials_search_widget');


/**
* SPECIALTIES & SERVICES BODY DIAGRAM
* 
* display the specialties in a body diagram 
*
*
* @return HTML output
*/

function sal_ss_body_diagram($atts){
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'default_state' => 'hidden'
            ), $atts
        )
    );

    $state_class = ($default_state == 'displayed' ? 'show' :'');
    $marker_class = ($default_state == 'displayed' ? 'active' :'');

    ob_start();
    $specialty_args = array( 
        'posts_per_page' => -1, 
        'offset'=> 0, 
        'post_type' => 'specialties', 
        'post_status' => 'publish',
        'meta_key' => 'include_this_specialty_on_the_body_diagram',
        'meta_value' => 1,
        'post_parent' => 0
    );

    $specialties = get_posts( $specialty_args );
    $specialties_count = count($specialties);

    $front_markers_array = array('shoulder','hand-wrist','knee','knee-leg','elbow', 'adult-reconstruction-and-arthritis-surgery');
    $anim_left_items_array = array('shoulder','hand-wrist','hip-thigh','trauma','elbow');

    if( $specialties_count > 0 ): ?>
            <?php 
                $front_markers = '';
                $back_markers = '';
                $animate_from_left_markers = '';

            // loop through the rows of data
            foreach ($specialties as $spec): ?>
                <?php 
                    $animate_class = (in_array($spec->post_name, $anim_left_items_array) ? 'left' : 'right');
                    $current_marker = '';
                    $current_marker.= '<div id="'. $spec->post_name .'" class="marker ' . $state_class . ' ' . $animate_class . '">';
                    $current_marker.= '<div class="marker-info">';
                    $current_marker.= '<div class="marker-title-container"><div class="marker-title"><div class="title-text">' . $spec->post_title . '</div></div></div>';
                    $current_marker.= '<div class="pointer-bar"></div>';
                    $current_marker.= '<div class="marker-description"><div class="description">' . $spec->post_excerpt;
                    $current_marker.= '<a href="' . get_the_permalink($spec->ID) .'" class="learn-more">Learn More <i class="fa fa-angle-right"></i></a>';
                    $current_marker.= '</div></div>';
                    $current_marker.= '</div>';
                    $current_marker.= '<div class="marker-dot ' . $marker_class . '"><span class="close-btn"><i class="fa fa-times"></i></span></div>';
                    $current_marker.= '</div>';

                    if(in_array($spec->post_name, $front_markers_array) ){
                        $front_markers.= $current_marker;
                    }else{
                        $back_markers.= $current_marker;
                    }
                 ?>
            <?php endforeach; ?>

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
                    <div class="diagram-display-options">
                         <div class="row">
                            <div class="col-md-6 left"><a id="show-all-info-boxes" href="#">Show All</a></div>
                            <div class="col-md-6 right"><a id="hide-all-info-boxes" href="#">Hide All</a></div>
                        </div>
                    </div>
                </div>
            </div>  
    <?php endif;
    return ob_get_clean();
}

add_shortcode('specialties_body_diagram', 'sal_ss_body_diagram');



/**
* PHYSICIAN ASSISTANTS LIST DISPLAY
* 
* displays a list of physician assistants based on the physician assitant ID
*
* @param  $ID = the id of the physician.
*
* @return sting
*/

function sal_get_assistants_for_physician($post_id){

        $assistants_args = array( 
            'posts_per_page' => -1, 
            'offset'=> 0, 
            'post_type' => 'assistants', 
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query'    => array(
                array(
                    'key'       => 'physicians_assisting',
                    'value'     => $post_id,
                    'compare'   => 'LIKE'
                )
            )
        );

        $assitants = get_posts( $assistants_args );
        $assistants_query = new WP_Query($assistants_args);

        if( $assitants ): ?>
            <ul class="assistants-list">
                <?php // loop through the rows of data
                while ($assistants_query->have_posts()) : $assistants_query->the_post(); ?>
                    <li class="assitant-item"><a href="<?php echo get_the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
            </ul>
        <?php endif;?>

        <?php wp_reset_postdata(); 
}


/**
* PHYSICIAN ASSISTANTS LIST DISPLAY
* 
* displays a list of physician assistants based on the physician assitant ID
*
* @param  $ID = the id of the physician.
*
* @return sting
*/

function sal_display_all_assistants_table($atts){

        //shortcode options
        extract(
            shortcode_atts(
                array(
                    'physician_column_title' => 'Physician Assistant (PA)/Nurse Practitioner (ARNP)',
                    'assisting_column_title' => 'Assisting'
                ), $atts
            )
        );

        $assistants_args = array( 
            'posts_per_page' => -1, 
            'offset'=> 0, 
            'post_type' => 'assistants', 
            'post_status' => 'publish',
            'meta_key' => 'assistants_last_name',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        );

        $assitants = get_posts( $assistants_args );
        $assistants_query = new WP_Query($assistants_args);

        $output = '';

        if( $assitants ):
            $output.= '<div class="assistants-table-container">';
            $output.= '<div class="table-heading"><div class="container tab-container"><div class="row">';
            $output.= '<div class="col-md-8">' . $physician_column_title . '</div>';
            $output.= '<div class="col-md-4">' . $assisting_column_title . '</div>';
            $output.= '</div></div></div>';
            
            // loop through the rows of data
            while ($assistants_query->have_posts()) : $assistants_query->the_post();
                $first_name = get_field('assistants_first_name');
                $middle_name = (get_field('assistants_middle_initial') ? ' ' . get_field('assistants_middle_initial') : '');
                $last_name = get_field('assistants_last_name');
                $assistant_type = get_field('assistant_type');
                $job_titles = ($assistant_type !== '' ? ' - ' . $assistant_type : '');


                $full_name = $last_name . ', ' . $first_name . $middle_name . $job_titles;

                $output.= '<div class="assistant-row"><div class="container tab-container"><div class="row">';
                $output.= '<div class="col-md-8"><div class="assistant-name">' . $full_name . '</div></div>';
                $output.= '<div class="col-md-4">';

                $assisting_physicians_array = get_field('physicians_assisting'); 
                $assisting_locations_array = get_field('locations_assisting'); 

                $assisting_doctors = sal_string_from_postarray($assisting_physicians_array,'link','linked-physicians', true, false);
                $assisting_locations = sal_string_from_postarray($assisting_locations_array,'link','linked-locations', true, false);

                $output.= '<div class="mobile-label">Assisting:</div>';
                $output.= ($assisting_doctors ? '<div class="link-set with-icon doctors">' . $assisting_doctors . '</div>' : '');
                $output.= ($assisting_locations ? '<div class="link-set with-icon locations">' . $assisting_locations . '</div>' : '');


                $output.= '</div>';
                $output.= '</div></div></div>';
                                            
            endwhile;                
            $output.= '</div>';

        endif;

        return $output;

    wp_reset_postdata(); 
}
add_shortcode('all_assistants_table', 'sal_display_all_assistants_table');


//generate a custom sitemap
function sal_website_sitemap(){

    class Sitemap_Menu extends Walker_Nav_Menu {
        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            global $wp_query;
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            $depthClass = ( $depth == 0  ? 'first-level' : 'sub-level' ); // define top level nav
            
            $class_names = $value = '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
            $class_names = ' class="' . esc_attr( $class_names ) . ' ' . $depthClass . '"';

            $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

            $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
            $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
            $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
            $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .' class="' . $depthClass . '"><span class="menu-title">';
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            //get all the physicians as a simple link list and put them under the main menu for "View All Phyisicans"
            if(strtolower($item->title) == 'view all physicians'){ 

                $physician_args = array( 
                    'offset' => 0,
                    'posts_per_page' => -1,
                    'post_type' => 'physicians', 
                    'orderby' => 'title', 
                    'order' => 'ASC',
                    'post_status' => 'publish'  
                );

                $physicians = get_posts( $physician_args );

                if($physicians){
                    $item_output .= '<ul class="sub-menu">';

                    foreach($physicians as $phy){
                        $item_output .= '<li><a href="' . $phy->ID . '">' . $phy->post_title . '</a></li>';
                    }

                    $item_output .= '</ul>';
                }
            }
            //get all the portolfio items as a simple link list and put them under the main menu for "View All Phyisicans"
            elseif(strtolower($item->title) == 'video' || strtolower($item->title) == 'videos'){ 

                $video_args = array( 
                    'offset' => 0,
                    'posts_per_page' => -1,
                    'post_type' => 'portfolio', 
                    'orderby' => 'title', 
                    'order' => 'ASC',
                    'post_status' => 'publish'  
                );

                $videos = get_posts( $video_args );

                if($videos){
                    $item_output .= '<ul class="sub-menu">';

                    foreach($videos as $v){
                        $item_output .= '<li><a href="' . $v->ID . '">' . $v->post_title . '</a></li>';
                    }

                    $item_output .= '</ul>';
                }
            }

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
    }

    $return_content = '<div class="sitemap-container">';
    $return_content.= wp_nav_menu( array('walker' => new Sitemap_Menu, 'theme_location' => 'top_nav', 'container' => 'ul', 'menu_class' => 'custom-sitemap') );
    $return_content.= '</div">';

    return $return_content;
}
add_shortcode('thesitemap', 'sal_website_sitemap');



// Add specific CSS class by filter
add_filter( 'body_class', 'my_class_names');

function my_class_names( $classes ) {
    
    global $post;
    $postid = get_the_ID();
    $body_class = '';

    $title = get_post_meta($postid, '_nectar_header_title', true);
    $subtitle = get_post_meta($postid, '_nectar_header_subtitle', true);

    if($title || $subtitle){
        $body_class = 'has-title-box';
    }
    
    // add 'class-name' to the $classes array
    $classes[] = $body_class;
    // return the $classes array
    return $classes;
}


/**
* CUSTOM LOGIN PAGE
* 
* Adds custom theming to the main wp-admin login screen
*
*/
function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 {
            font-weight: 400;
            overflow: hidden;
            -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13);
            box-shadow:none;
            background:none;
        }
        .login h1 a { 
            background-image:url('.get_site_url().'/wp-content/uploads/2018/06/ACC_Logo.png) !important; 
            width:320px !important; 
            max-width:100%;
            background-size:320px 137px !important; 
            margin:20px auto;
            height: 137px !important;
        }
        body.login.interim-login h1 a {
            background-image:url('.get_site_url().'/wp-content/uploads/2018/06/ACC_Logo.png) !important; 
        }
        #loginform{
            margin-top:0px;
            background:#fff;
        }
        body.login:not(.interim-login) {
            position:relative;
        }
        .login #backtoblog a, .login #nav a{
            color:#fff;
        }
        .login #backtoblog a:hover, .login #nav a:hover{
            text-decoration:underline;
            color:#fff;
        }
        .login #nav{
            padding:15px 0 5px;
        }
        .login #backtoblog{
            padding:0 0 15px;
        }
        .login #nav,
        .login #backtoblog{
            text-align:center;
            margin:0;
        }
        .login #nav a,
        .login #backtoblog a{
            color:#6e445a;
        }
        .login #nav a:hover,
        .login #backtoblog a:hover{
            color:#f16979;
        }
        .login .message{
            color:#fff;
            background:#f16979;
            border:0;
            margin:0 !important;
        }
        .login form input[type="text"]{
            background:#fff !important;
        }
    
    </style>';
}
add_action('login_head', 'my_custom_login_logo');


/*********************
Force SSL for the entire site except the Bill Tracker Page.
We have to do it in a function and not the htaccess file because we need to exclude one specific page.
*********************/
function force_https () {
    $addr = $_SERVER['HTTP_HOST'];
    $production_site = true;

    if(preg_match('/localhost/', $addr) || preg_match('/cordesowen.com/', $addr)){
        $production_site = false;
    }

    //only redirect to SSL if it is on the production server.
    if($production_site == true){
        if( !is_ssl() ) {
            wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
            exit();
        }
    }
}



#-----------------------------------------------------------------#
# Custom page header
#-----------------------------------------------------------------# 

if ( !function_exists( 'nectar_page_header' ) ) {
    function nectar_page_header($postid) {
        
        global $options;
        global $post;
        global $nectar_theme_skin;

        $bg = get_post_meta($postid, '_nectar_header_bg', true);
        $bg_color = get_post_meta($postid, '_nectar_header_bg_color', true);
        $font_color = get_post_meta($postid, '_nectar_header_font_color', true);
        $parallax_bg = get_post_meta($postid, '_nectar_header_parallax', true);
        $title = get_post_meta($postid, '_nectar_header_title', true);
        $subtitle = get_post_meta($postid, '_nectar_header_subtitle', true);
        $height = get_post_meta($postid, '_nectar_header_bg_height', true); 
        $page_template = get_post_meta($postid, '_wp_page_template', true); 
        $display_sortable = get_post_meta($postid, 'nectar-metabox-portfolio-display-sortable', true);
        $inline_filters = (!empty($options['portfolio_inline_filters']) && $options['portfolio_inline_filters'] == '1') ? '1' : '0';
        $filters_id = (!empty($options['portfolio_inline_filters']) && $options['portfolio_inline_filters'] == '1') ? 'portfolio-filters-inline' : 'portfolio-filters';
        $text_align = get_post_meta($postid, '_nectar_page_header_alignment', true); 
        $text_align_v = get_post_meta($postid, '_nectar_page_header_alignment_v', true); 
        $fullscreen_header = (!empty($options['blog_header_type']) && $options['blog_header_type'] == 'fullscreen' && is_singular('post')) ? true : false;
        $post_header_style = (!empty($options['blog_header_type'])) ? $options['blog_header_type'] : 'default'; 
        $bottom_shadow = get_post_meta($postid, '_nectar_header_bottom_shadow', true); 
        $bg_overlay = get_post_meta($postid, '_nectar_header_overlay', true); 
        $text_effect = get_post_meta($postid, '_nectar_page_header_text-effect', true); 
        $animate_in_effect = (!empty($options['header-animate-in-effect'])) ? $options['header-animate-in-effect'] : 'none';
        (!empty($display_sortable) && $display_sortable == 'on') ? $display_sortable = '1' : $display_sortable = '0';
        
        //incase no title is entered for portfolio, still show the filters
        if( $page_template == 'template-portfolio.php' && empty($title)) $title = get_the_title($post->ID);
        
        $bg_type = get_post_meta($postid, '_nectar_slider_bg_type', true); 
        if(empty($bg_type)) $bg_type = 'image_bg'; 

        if( (!empty($bg) || !empty($bg_color) || $bg_type == 'video_bg' || $bg_type == 'particle_bg') && !is_post_type_archive( 'post' ) ) {  
        
        $social_img_src = (empty($bg)) ? 'none' : $bg;
        $bg = (empty($bg)) ? 'none' : $bg;

        if($bg_type == 'image_bg' || $bg_type == 'particle_bg') {
            (empty($bg_color)) ? $bg_color = '#000' : $bg_color = $bg_color;
        } else {
            $bg = 'none'; //remove stnd bg image for video BG type
        }
        $bg_color_string = (!empty($bg_color)) ? 'background-color: '.$bg_color.'; ' : null;

        if($bg_type == 'particle_bg') {
            $rotate_timing = get_post_meta($postid, '_nectar_particle_rotation_timing', true); 
            $disable_explosion = get_post_meta($postid, '_nectar_particle_disable_explosion', true);
            $shapes = get_post_meta($postid, '_nectar_canvas_shapes', true); 
            if(empty($shapes)) $bg_type = 'image_bg';
        }
        if($bg_type == 'video_bg') {
            $video_webm = get_post_meta($postid, '_nectar_media_upload_webm', true); 
            $video_mp4 = get_post_meta($postid, '_nectar_media_upload_mp4', true); 
            $video_ogv = get_post_meta($postid, '_nectar_media_upload_ogv', true); 
            $video_image = get_post_meta($postid, '_nectar_slider_preview_image', true); 
        }
        $box_roll = get_post_meta($postid, '_nectar_header_box_roll', true); 
        if(!empty($options['boxed_layout']) && $options['boxed_layout'] == '1') $box_roll = 'off';
        $bg_position = get_post_meta($postid, '_nectar_page_header_bg_alignment', true); 
        if(empty($bg_position)) $bg_position = 'top'; 

        if( $post_header_style == 'default_minimal' && ($post->post_type == 'post' && is_single())) {
            $height = (!empty($height)) ? preg_replace('/\s+/', '', $height) : 550;
        } else {
            $height = (!empty($height)) ? preg_replace('/\s+/', '', $height) : 350;
        }

        $not_loaded_class = ($nectar_theme_skin != 'ascend') ? "not-loaded" : null;     
        $page_fullscreen_header = get_post_meta($postid, '_nectar_header_fullscreen', true); 
        $fullscreen_class = ($fullscreen_header == true || $page_fullscreen_header == 'on') ? "fullscreen-header" : null;
        $bottom_shadow_class = ($bottom_shadow == 'on') ? " bottom-shadow": null;
        $bg_overlay_class = ($bg_overlay == 'on') ? " bg-overlay": null;
        $ajax_page_loading = (!empty($options['ajax-page-loading']) && $options['ajax-page-loading'] == '1') ? true : false;

        if($animate_in_effect == 'slide-down') {
            $wrapper_height_style = null;
        } else {
            $wrapper_height_style = 'style="height: '.$height.'px;"';
        }
        if($fullscreen_header == true && ($post->post_type == 'post' && is_single()) || $page_fullscreen_header == 'on') $wrapper_height_style = null; //diable slide down for fullscreen headers
    
        $midnight_non_parallax = (!empty($parallax_bg) && $parallax_bg == 'on') ? null : 'data-midnight="light"';
        if($box_roll != 'on') { echo '<div id="page-header-wrap" data-animate-in-effect="'. $animate_in_effect .'" data-midnight="light" class="'.$fullscreen_class.'" '.$wrapper_height_style.'>'; } 
        if(!empty($box_roll) && $box_roll == 'on') { 
            wp_enqueue_style('box-roll'); 
            echo '<div class="nectar-box-roll">'; 
        }
        ?>
        <div class="<?php echo $not_loaded_class . ' ' . $fullscreen_class . $bottom_shadow_class . $bg_overlay_class; ?>" <?php if($post->post_type == 'post' && is_single()) echo 'data-post-hs="'.$post_header_style.'"'; ?> data-animate-in-effect="<?php echo $animate_in_effect; ?>" id="page-header-bg" <?php echo $midnight_non_parallax; ?> data-text-effect="<?php echo $text_effect; ?>" data-bg-pos="<?php echo $bg_position; ?>" data-alignment="<?php echo (!empty($text_align)) ? $text_align : 'left' ; ?>" data-alignment-v="<?php echo (!empty($text_align_v)) ? $text_align_v : 'middle' ; ?>" data-parallax="<?php echo (!empty($parallax_bg) && $parallax_bg == 'on') ? '1' : '0'; ?>" data-height="<?php echo (!empty($height)) ? $height : '350'; ?>" style="<?php echo $bg_color_string; ?> height: <?php echo (!empty($height)) ? $height : '350'; ?>px;">
            
            <?php 

            if(!empty($bg) && $bg != 'none') { ?><div class="page-header-bg-image" style="background-image: url(<?php echo $bg; ?>);"></div> <?php } ?>

            <?php if($bg_type != 'particle_bg') { echo '<div class="container">'; }
            
                    
                    if($post->ID != 0 && $post->post_type && $post->post_type == 'portfolio') { ?>
                    
                    <div class="row project-title">
                    <div class="container">
                    <div class="col span_6 section-title <?php if(empty($options['portfolio_social']) || $options['portfolio_social'] == 0 || empty($options['portfolio_date']) || $options['portfolio_date'] == 0 ) echo 'no-date'?>">
                        
                        <h1><?php the_title(); ?></h1>
                        <?php if(!empty($subtitle)) { ?> <span class="subheader"><?php echo $subtitle; ?></span> <?php } ?>
                        
                        <?php 

                        global $options;
                        $single_nav_pos = (!empty($options['portfolio_single_nav'])) ? $options['portfolio_single_nav'] : 'in_header';

                        if($single_nav_pos == 'in_header') project_single_controls(); ?>
                        
                    </div>
                </div> 
            
            </div><!--/row-->
                        
                        
                        
                        
                        
                        
                        
                    <?php } elseif($post->ID != 0 && $post->post_type == 'post' && is_single() ) { 
                        
                        // also set as an img for social sharing/
                        if($social_img_src != 'none') echo '<img class="hidden-social-img" src="'.$social_img_src.'" alt="'.get_the_title().'" />';

                        ?>
                        
                        <div class="row">

                            <div class="col span_6 section-title blog-title">
                                <div class="inner-wrap">

                                    <?php if(($post->post_type == 'post' && is_single()) && $post_header_style == 'default_minimal') {

                                            $categories = get_the_category();
                                            if ( ! empty( $categories ) ) {
                                                $output = null;
                                                foreach( $categories as $category ) {
                                                    $output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', NECTAR_THEME_NAME), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>';
                                                }
                                                echo trim( $output);
                                            }
                                    } ?>

                                    <h1 class="entry-title"><?php the_title(); ?></h1>

                                     <?php if(($post->post_type == 'post' && is_single()) && $fullscreen_header == true ) { ?>
                                        <div class="author-section">
                                            <span class="meta-author vcard author">  
                                                <?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), 100 ); }?>
                                            </span> 
                                            <div class="avatar-post-info">
                                                <span class="fn"><?php the_author_posts_link(); ?></span>
                                                <span class="meta-date date updated"><i><?php echo get_the_date(); ?></i></span>
                                             </div>
                                        </div>
                                <?php } ?>
                            
                            
                                <?php if($fullscreen_header != true) { ?>   
                                    <div id="single-below-header">
                                        <span class="meta-author vcard author"><span class="fn"><?php echo __('By', NECTAR_THEME_NAME); ?> <?php the_author_posts_link(); ?></span></span><!--
                                        --><span class="meta-date date updated"><?php echo get_the_date(); ?></span><!--
                                        --><?php if($post_header_style != 'default_minimal') { ?> <span class="meta-category"><?php the_category(', '); ?></span> <?php } else { ?><!--
                                        --><span class="meta-comment-count"><a href="<?php comments_link(); ?>"> <?php comments_number( __('No Comments', NECTAR_THEME_NAME), __('One Comment ', NECTAR_THEME_NAME), __('% Comments', NECTAR_THEME_NAME) ); ?></a></span>
                                    <?php } ?>
                                    </div><!--/single-below-header-->
                                <?php } ?>
                                
                                <?php if($fullscreen_header != true && $post_header_style != 'default_minimal') { ?>

                                <div id="single-meta" data-sharing="<?php echo ( !empty($options['blog_social']) && $options['blog_social'] == 1 ) ? '1' : '0'; ?>">
                                    <ul>
        
        
                                       
                                        <li class="meta-comment-count">
                                            <a href="<?php comments_link(); ?>"><i class="icon-default-style steadysets-icon-chat"></i> <?php comments_number( __('No Comments', NECTAR_THEME_NAME), __('One Comment ', NECTAR_THEME_NAME), __('% Comments', NECTAR_THEME_NAME) ); ?></a>
                                        </li>
                                            <li>
                                            <?php echo '<span class="n-shortcode">'.nectar_love('return').'</span>'; ?>
                                        </li>
                                        <?php if( !empty($options['blog_social']) && $options['blog_social'] == 1 ) { 
                                           
                                           echo '<li class="meta-share-count"><a href="#"><i class="icon-default-style steadysets-icon-share"></i><span class="share-count-total">0</span></a> <div class="nectar-social">';
                                           
                                        
                                            //facebook
                                            if(!empty($options['blog-facebook-sharing']) && $options['blog-facebook-sharing'] == 1) { 
                                                echo "<a class='facebook-share nectar-sharing' href='#' title='".__('Share this', NECTAR_THEME_NAME)."'> <i class='icon-facebook'></i> <span class='count'></span></a>";
                                            }
                                            //twitter
                                            if(!empty($options['blog-twitter-sharing']) && $options['blog-twitter-sharing'] == 1) {
                                                echo "<a class='twitter-share nectar-sharing' href='#' title='".__('Tweet this', NECTAR_THEME_NAME)."'> <i class='icon-twitter'></i> <span class='count'></span></a>";
                                            }
                                            //google plus
                                            if(!empty($options['blog-google-plus-sharing']) && $options['blog-google-plus-sharing'] == 1) {
                                                echo "<a class='google-plus-share nectar-sharing-alt' href='#' title='".__('Share this', NECTAR_THEME_NAME)."'> <i class='icon-google-plus'></i> <span class='count'> ".GetGooglePlusShares(get_permalink($post->ID))." </span></a>";
                                            }
                                            
                                            //linkedIn
                                            if(!empty($options['blog-linkedin-sharing']) && $options['blog-linkedin-sharing'] == 1) {
                                                echo "<a class='linkedin-share nectar-sharing' href='#' title='".__('Share this', NECTAR_THEME_NAME)."'> <i class='icon-linkedin'></i> <span class='count'> </span></a>";
                                            }
                                            //pinterest
                                            if(!empty($options['blog-pinterest-sharing']) && $options['blog-pinterest-sharing'] == 1) {
                                                echo "<a class='pinterest-share nectar-sharing' href='#' title='".__('Pin this', NECTAR_THEME_NAME)."'> <i class='icon-pinterest'></i> <span class='count'></span></a>";
                                            }
                                            
                                          echo '</div></li>';
        
                                        }
                                    ?>
                                    
                                    

                                    </ul>
                                    
                                </div><!--/single-meta-->

                            <?php } //end if theme skin default ?>
                            </div>
                        </div><!--/section-title-->
                    </div><!--/row-->
                        
                            
                        
                        
                    
                    <?php //default 
                    } else if($bg_type != 'particle_bg') {

                        if(!empty($box_roll) && $box_roll == 'on') { 
                            $alignment = (!empty($text_align)) ? $text_align : 'left';
                            $v_alignment = (!empty($text_align_v)) ? $text_align_v : 'middle';
                            echo '<div class="overlaid-content" data-text-effect="'.$text_effect.'" data-alignment="'.$alignment.'" data-alignment-v="'.$v_alignment.'"><div class="container">';
                        }  ?>

                         <div class="row">
                            <div class="col span_6">
                                <div class="inner-wrap">
                                    <?php echo ($title !== '' ? '<h1>' . $title . '</h1>' : ''); ?>
                                    <span class="subheader"><?php echo $subtitle; ?></span>
                                </div>
                                 
                                <?php // portfolio filters
                                    if( $page_template == 'template-portfolio.php' && $display_sortable == '1' && $inline_filters == '0') { ?>
                                    <div class="<?php echo $filters_id;?>" instance="0">
                                            <a href="#" data-sortable-label="<?php echo (!empty($options['portfolio-sortable-text'])) ? $options['portfolio-sortable-text'] :'Sort Portfolio'; ?>" id="sort-portfolio"><span><?php echo (!empty($options['portfolio-sortable-text'])) ? $options['portfolio-sortable-text'] : __('Sort Portfolio',NECTAR_THEME_NAME); ?></span> <i class="icon-angle-down"></i></a> 
                                        <ul>
                                           <li><a href="#" data-filter="*"><?php echo __('All', NECTAR_THEME_NAME); ?></a></li>
                                           <?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'project-type', 'show_option_none'   => '', 'walker' => new Walker_Portfolio_Filter())); ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                </div>
                          </div>
                      
                      <?php if(!empty($box_roll) && $box_roll == 'on') echo '</div></div><!--/overlaid-content-->';

                 } ?>
                    
                    
                
            <?php if($bg_type != 'particle_bg') { echo '</div>'; } //closing container 


             if(($post->ID != 0 && $post->post_type == 'post' && is_single()) && $fullscreen_header == true || $page_fullscreen_header == 'on') { 
                 $rotate_in_class = ( $text_effect == 'rotate_in') ? 'hidden' : null;
                 $button_styling = (!empty($options['button-styling'])) ? $options['button-styling'] : 'default'; 
                 if($button_styling == 'default'){
                    echo '<div class="scroll-down-wrap"><a href="#" class="section-down-arrow '.$rotate_in_class.'"><i class="icon-salient-down-arrow icon-default-style"> </i></a></div>';
                 } else {
                    echo '<div class="scroll-down-wrap '.$rotate_in_class.'"><a href="#" class="section-down-arrow"><i class="fa fa-angle-down top"></i><i class="fa fa-angle-down"></i></a></div>';
                 }

              } 

        
        //video bg
        if($bg_type == 'video_bg') {
            
            if ( floatval(get_bloginfo('version')) >= "3.6" ) {
                wp_enqueue_script('wp-mediaelement');
                wp_enqueue_style('wp-mediaelement');
            } else {
                //register media element for WordPress 3.5
                wp_register_script('wp-mediaelement', get_template_directory_uri() . '/js/mediaelement-and-player.min.js', array('jquery'), '1.0', TRUE);
                wp_register_style('wp-mediaelement', get_template_directory_uri() . '/css/mediaelementplayer.min.css');
                
                wp_enqueue_script('wp-mediaelement');
                wp_enqueue_style('wp-mediaelement');
            }
            
            //parse video image
            if(strpos($video_image, "http://") !== false || strpos($video_image, "https://") !== false){
                $video_image_src = $video_image;
            } else {
                $video_image_src = wp_get_attachment_image_src($video_image, 'full');
                $video_image_src = $video_image_src[0];
            }
            
            //$poster_markup = (!empty($video_image)) ? 'poster="'.$video_image_src.'"' : null ;
            $poster_markup = null;
            $video_markup = null;
            
            $video_markup .=  '<div class="video-color-overlay" data-color="'.$bg_color.'"></div>';
            
                 
            $video_markup .= '
            
            <div class="mobile-video-image" style="background-image: url('.$video_image_src.')"></div>
            <div class="nectar-video-wrap" data-bg-alignment="'.$bg_position.'">
                
                
                <video class="nectar-video-bg" width="1800" height="700" '.$poster_markup.'  preload="auto" loop autoplay>';
                    if(!empty($video_webm)) { $video_markup .= '<source type="video/webm" src="'.$video_webm.'">'; }
                    if(!empty($video_mp4)) { $video_markup .= '<source type="video/mp4" src="'.$video_mp4.'">'; }
                    if(!empty($video_ogv)) { $video_markup .= '<source type="video/ogg" src="'. $video_ogv.'">'; }
                  
               $video_markup .='</video>
        
            </div>';
            
            echo $video_markup;
        }

        //particle bg
        if($bg_type == 'particle_bg') {

            wp_enqueue_script('nectarParticles');

            echo '<div class=" nectar-particles" data-disable-explosion="'.$disable_explosion.'" data-rotation-timing="'.$rotate_timing.'"><div class="canvas-bg"><canvas id="canvas" data-active-index="0"></canvas></div>';

            $images = explode( ',', $shapes);
            $i = 0;

            if(!empty($shapes)) {

                if(!empty($box_roll) && $box_roll == 'on') { 
                    $alignment = (!empty($text_align)) ? $text_align : 'left';
                    $v_alignment = (!empty($text_align_v)) ? $text_align_v : 'middle';
                    echo '<div class="overlaid-content" data-text-effect="'.$text_effect.'" data-alignment="'.$alignment.'" data-alignment-v="'.$v_alignment.'">';
                }

                echo '<div class="container"><div class="row"><div class="col span_6" >';

                foreach ( $images as $attach_id ) {
                    $i++;

                    $img = wp_get_attachment_image_src(  $attach_id, 'full' );

                    $attachment = get_post( $attach_id );
                    $shape_meta = array(
                        'caption' => $attachment->post_excerpt,
                        'title' => $attachment->post_title,
                        'bg_color' => get_post_meta( $attachment->ID, 'nectar_particle_shape_bg_color', true ),
                        'color' => get_post_meta( $attachment->ID, 'nectar_particle_shape_color', true ),
                        'color_mapping' => get_post_meta( $attachment->ID, 'nectar_particle_shape_color_mapping', true ),
                        'alpha' => get_post_meta( $attachment->ID, 'nectar_particle_shape_color_alpha', true ),
                        'density' => get_post_meta( $attachment->ID, 'nectar_particle_shape_density', true ),
                        'max_particle_size' => get_post_meta( $attachment->ID, 'nectar_particle_max_particle_size', true )
                    );
                    if(!empty($shape_meta['density'])) {
                        switch($shape_meta['density']) {
                            case 'very_low':
                                $shape_meta['density'] = '19';
                            break;
                            case 'low':
                                $shape_meta['density'] = '16';
                            break;
                            case 'medium':
                                $shape_meta['density'] = '13';
                            break;
                            case 'high':
                                $shape_meta['density'] = '10';
                            break;
                            case 'very_high':
                                $shape_meta['density'] = '8';
                            break;
                        }
                    }

                    if(!empty($shape_meta['color']) && $shape_meta['color'] == '#fff' || !empty($shape_meta['color']) && $shape_meta['color'] == '#ffffff') $shape_meta['color'] = '#fefefe';

                    //data for particle shape
                    echo '<div class="shape" data-src="'.$img[0].'" data-max-size="'.$shape_meta['max_particle_size'].'" data-alpha="'.$shape_meta['alpha'].'" data-density="'.$shape_meta['density'].'" data-color-mapping="'.$shape_meta['color_mapping'].'" data-color="'.$shape_meta['color'].'" data-bg-color="'.$shape_meta['bg_color'].'"></div>';

                    //overlaid content
                    echo '<div class="inner-wrap shape-'.$i.'">';
                    echo '<h1>'.$shape_meta["title"].'</h1> <span class="subheader">'.$shape_meta["caption"].'</span>';
                    echo '</div>';

                } ?>

                </div></div></div>

                <div class="pagination-navigation">
                    <div class="pagination-current"></div>
                    <div class="pagination-dots">
                        <?php foreach ( $images as $attach_id ) {
                            echo '<button class="pagination-dot"></button>';
                        } ?>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="690">
                  <defs>
                    <filter id="goo">
                      <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
                      <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 69 -16" result="goo"></feColorMatrix>
                      <feComposite in="SourceGraphic" in2="goo" operator="atop"></feComposite>
                    </filter>
                  </defs>
                </svg>

                <?php if(!empty($box_roll) && $box_roll == 'on') echo '</div><!--/overlaid-content-->'; ?>

            </div> <!--/nectar particles-->

            <?php }
        } //particle bg ?>

        </div>

       <?php 

        echo '</div>';  

        } else if( !empty($title) && !is_archive()) { ?>
            
            <div class="row page-header-no-bg" data-alignment="<?php echo (!empty($text_align)) ? $text_align : 'left' ; ?>">
                <div class="container"> 
                    <div class="col span_12 section-title">
                        <h1><?php echo $title; ?><?php if(!empty($subtitle)) echo '<span>' . $subtitle . '</span>'; ?></h1>
                        
                        <?php // portfolio filters
                        if( $page_template == 'template-portfolio.php' && $display_sortable == '1' && $inline_filters == '0') { ?>
                        <div class="<?php echo $filters_id;?>" instance="0">
                            
                            <a href="#" data-sortable-label="<?php echo (!empty($options['portfolio-sortable-text'])) ? $options['portfolio-sortable-text'] :'Sort Portfolio'; ?>" id="sort-portfolio"><span><?php echo (!empty($options['portfolio-sortable-text'])) ? $options['portfolio-sortable-text'] : __('Sort Portfolio',NECTAR_THEME_NAME); ?></span> <i class="icon-angle-down"></i></a> 
                            
                            <ul>
                               <li><a href="#" data-filter="*"><?php echo __('All', NECTAR_THEME_NAME); ?></a></li>
                               <?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'project-type', 'show_option_none'   => '', 'walker' => new Walker_Portfolio_Filter())); ?>
                            </ul>
                        </div>
                    <?php } ?>
                        
                    </div>
                </div>

            </div> 
            
        <?php } else if(is_category() || is_tag() || is_date() || is_author() ) {

            /*blog archives*/
            $archive_bg_img = (isset($options['blog_archive_bg_image'])) ? nectar_options_img($options['blog_archive_bg_image']) : null;
            $t_id =  get_cat_ID( single_cat_title( '', false ) ) ;
            $terms =  get_option( "taxonomy_$t_id" );

            $heading = null;
            $subtitle = null;

            if(is_author()){

                $heading =  get_the_author();
                $subtitle = __('All Posts By', NECTAR_THEME_NAME );

            } else if(is_category()) {

                $heading =  single_cat_title( '', false );
                $subtitle = __('Category', NECTAR_THEME_NAME );

            } else if(is_tag()) {

                $heading =  wp_title("",false);
                $subtitle = __('Tag', NECTAR_THEME_NAME );

            } else if(is_date()){

                if ( is_day() ) :

                    $heading = get_the_date();
                    $subtitle = __('Daily Archives', NECTAR_THEME_NAME );
                
                elseif ( is_month() ) :

                    $heading = get_the_date( _x( 'F Y', 'monthly archives date format', NECTAR_THEME_NAME ) );
                    $subtitle = __('Monthly Archives', NECTAR_THEME_NAME );

                elseif ( is_year() ) :

                    $heading =  get_the_date( _x( 'Y', 'yearly archives date format', NECTAR_THEME_NAME ) );
                    $subtitle = __('Yearly Archives', NECTAR_THEME_NAME );

                else :
                    $heading = __( 'Archives', NECTAR_THEME_NAME );

                endif;
            } else {
                    $heading = wp_title("",false);
            } ?>


            <?php 
            if(!empty($terms['category_image']) || !empty($archive_bg_img)) { 

                $bg_img = $archive_bg_img;
                if(!empty($terms['category_image'])) $bg_img = $terms['category_image'];

                if($animate_in_effect == 'slide-down') {
                    $wrapper_height_style = null;
                } else {
                    $wrapper_height_style = 'style="height: 350px;"';
                }
            ?>

            <div id="page-header-wrap" data-midnight="light" <?php echo $wrapper_height_style; ?>>   
                <div id="page-header-bg" data-animate-in-effect="<?php echo $animate_in_effect; ?>" id="page-header-bg" data-text-effect="" data-bg-pos="center" data-alignment="left" data-alignment-v="center" data-parallax="0" data-height="350" style="height: 350px;">
            
                    <div class="page-header-bg-image" style="background-image: url(<?php echo $bg_img; ?>);"></div> 

                    <div class="container">
                        <div class="row">
                            <div class="col span_6">
                                 <div class="inner-wrap">
                                    <span class="subheader"><?php echo $subtitle; ?></span>
                                    <h1><?php echo $heading; ?></h1>
                                </div>
                             
                            </div>
                        </div>
                              
                   </div>
                </div>

            </div>
            <?php } else { ?>


                 <div class="row page-header-no-bg" data-alignment="<?php echo (!empty($text_align)) ? $text_align : 'left' ; ?>">
                    <div class="container"> 
                        <div class="col span_12 section-title">
                            <span class="subheader"><?php echo $subtitle; ?></span>
                            <h1><?php echo $heading; ?></h1>
                        </div>
                    </div>

                </div> 


            <?php }
        }
 
    }
}


#-----------------------------------------------------------------#
# OVERRIDE SALIENT RECENT POSTS WIDGET
#-----------------------------------------------------------------# 
function foi_recent_posts($atts, $content = null) {
    extract(shortcode_atts(array("title_labels" => 'true', 'category' => 'all', 'slider_size' => '600', 'color_scheme' => 'light', 'slider_above_text' => '', 'posts_per_page' => '4', 'columns' => '4', 'style' => 'default', 'post_offset' => '0'), $atts));  
    
    global $post;  
    global $options;
    
    $posts_page_id = get_option('page_for_posts');
    $posts_page = get_page($posts_page_id);
    $posts_page_title = $posts_page->post_title;
    $posts_page_link = get_page_uri($posts_page_id);
    
    $title_label_output = null;
    $recent_posts_title_text = (!empty($options['recent-posts-title'])) ? $options['recent-posts-title'] :'Recent Posts';       
    $recent_posts_link_text = (!empty($options['recent-posts-link'])) ? $options['recent-posts-link'] :'View All Posts';        
    
    //incase only all was selected
    if($category == 'all') {
        $category = null;
    }
    
    if($style != 'slider') {
            
            ob_start(); 
            
            echo $title_label_output; ?>
            
            <div class="row blog-recent columns-<?php echo $columns; ?>" data-style="<?php echo $style; ?>" data-color-scheme="<?php echo $color_scheme; ?>">

                <?php echo ($title_labels == 'true') ? $title_label_output = '<h2>'.$recent_posts_title_text.'</h2>' : $title_label_output = null; ?>

                <?php echo ($style == 'title_only' ? '<ul class="list-with-arrow-markers-links">' : ''); ?>
                
                <?php 
                $recentBlogPosts = array(
                  'showposts' => $posts_per_page,
                  'category_name' => $category,
                  'ignore_sticky_posts' => 1,
                  'offset' => $post_offset,
                  'tax_query' => array(
                      array( 'taxonomy' => 'post_format',
                          'field' => 'slug',
                          'terms' => array('post-format-link','post-format-quote'),
                          'operator' => 'NOT IN'
                          )
                      )
                );

                $recent_posts_query = new WP_Query($recentBlogPosts);  

                if( $recent_posts_query->have_posts() ) :  while( $recent_posts_query->have_posts() ) : $recent_posts_query->the_post();  


                if($columns == '4') {
                    $col_num = 'span_3';
                } else if($columns == '3') {
                    $col_num = 'span_4';
                } else if($columns == '2') {
                    $col_num = 'span_6';
                } else {
                    $col_num = 'span_12';
                }
                
                ?>

                <?php echo ($style !== 'title_only' ? '<div class="col ' . $col_num . '">' : ''); ?>
                    
                    <?php 
                        
                        $wp_version = floatval(get_bloginfo('version'));
                        
                        if($style == 'default') {

                            if(get_post_format() == 'video'){

                                 if ( $wp_version < "3.6" ) {
                                     $video_embed = get_post_meta($post->ID, '_nectar_video_embed', true);
                                        
                                     if( !empty( $video_embed ) ) {
                                         echo '<div class="video-wrap">' . stripslashes(htmlspecialchars_decode($video_embed)) . '</div>';
                                     } else { 
                                         //nectar_video($post->ID); 
                                     }
                                 }
                                 else {
                                    
                                    $video_embed = get_post_meta($post->ID, '_nectar_video_embed', true);
                                    $video_m4v = get_post_meta($post->ID, '_nectar_video_m4v', true);
                                    $video_ogv = get_post_meta($post->ID, '_nectar_video_ogv', true); 
                                    $video_poster = get_post_meta($post->ID, '_nectar_video_poster', true); 
                                  
                                    if( !empty($video_embed) || !empty($video_m4v) ){
                
                                       $wp_version = floatval(get_bloginfo('version'));
                                                
                                      //video embed
                                      if( !empty( $video_embed ) ) {
                                        
                                           echo '<div class="video">' . do_shortcode($video_embed) . '</div>';
                                        
                                      } 
                                      //self hosted video pre 3-6
                                      else if( !empty($video_m4v) && $wp_version < "3.6") {
                                        
                                           echo '<div class="video">'; 
                                               //nectar_video($post->ID); 
                                           echo '</div>'; 
                                         
                                      } 
                                      //self hosted video post 3-6
                                      else if($wp_version >= "3.6"){
                        
                                          if(!empty($video_m4v) || !empty($video_ogv)) {
                                            
                                              $video_output = '[video ';
                                            
                                              if(!empty($video_m4v)) { $video_output .= 'mp4="'. $video_m4v .'" '; }
                                              if(!empty($video_ogv)) { $video_output .= 'ogv="'. $video_ogv .'"'; }
                                            
                                              $video_output .= ' poster="'.$video_poster.'"]';
                                            
                                              echo '<div class="video">' . do_shortcode($video_output) . '</div>';  
                                          }
                                      }
                                    
                                   } // endif for if there's a video
                                    
                                } // endif for 3.6 
                                
                            } //endif for post format video
                            
                            else if(get_post_format() == 'audio'){ ?>
                                <div class="audio-wrap">        
                                    <?php 
                                    if ( $wp_version < "3.6" ) {
                                        //nectar_audio($post->ID);
                                    } 
                                    else {
                                        $audio_mp3 = get_post_meta($post->ID, '_nectar_audio_mp3', true);
                                        $audio_ogg = get_post_meta($post->ID, '_nectar_audio_ogg', true); 
                                        
                                        if(!empty($audio_ogg) || !empty($audio_mp3)) {
                                            
                                            $audio_output = '[audio ';
                                            
                                            if(!empty($audio_mp3)) { $audio_output .= 'mp3="'. $audio_mp3 .'" '; }
                                            if(!empty($audio_ogg)) { $audio_output .= 'ogg="'. $audio_ogg .'"'; }
                                            
                                            $audio_output .= ']';
                                            
                                            echo  do_shortcode($audio_output);  
                                        }
                                    } ?>
                                </div><!--/audio-wrap-->
                            <?php }
                            
                            else if(get_post_format() == 'gallery'){
                                
                                if ( $wp_version < "3.6" ) {
                                    
                                    
                                    if ( has_post_thumbnail() ) { echo get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => '')); }
                                    
                                }
                                
                                else {
                                    
                                    $gallery_ids = grab_ids_from_gallery(); ?>
                        
                                    <div class="flex-gallery"> 
                                             <ul class="slides">
                                                <?php 
                                                foreach( $gallery_ids as $image_id ) {
                                                     echo '<li>' . wp_get_attachment_image($image_id, 'portfolio-thumb', false) . '</li>';
                                                } ?>
                                            </ul>
                                     </div><!--/gallery-->

                           <?php }
                                        
                            }
                            
                            else {
                                if ( has_post_thumbnail() ) { echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => '')) . '</a>'; }
                            }
                    
                        ?>

                            <div class="post-header">
                                <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>    
                                <span class="meta-author"><?php the_author_posts_link(); ?> </span> <span class="meta-category"> | <?php the_category(', '); ?> </span> <span class="meta-comment-count"> | <a href="<?php comments_link(); ?>">
                                <?php comments_number( __('No Comments',NECTAR_THEME_NAME), __('One Comment',NECTAR_THEME_NAME), '% '. __('Comments',NECTAR_THEME_NAME) ); ?></a> </span>
                            </div><!--/post-header-->
                            
                            <?php the_excerpt(); 

                        } // default style
                        else if($style == 'minimal') { ?>

                            <a href="<?php the_permalink(); ?>"></a>
                            <div class="post-header">
                                <span class="meta"> <?php echo get_the_date() . ' ' . __('in',NECTAR_THEME_NAME); ?> <?php the_category(', '); ?> </span> 
                                <h3 class="title"><?php the_title(); ?></h3>    
                            </div><!--/post-header-->
                            <?php the_excerpt(); ?>
                            <span><?php echo __('Read More',NECTAR_THEME_NAME); ?> <i class="icon-button-arrow"></i></span>

                        <?php } else if($style == 'title_only') { ?>

                            <li>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </li>

                        <?php } 

                        else if($style == 'classic_enhanced' || $style == 'classic_enhanced_alt') { 

                            if($columns == '4') {
                                $image_attrs =  array('title' => '', 'sizes' => '(min-width: 1300px) 25vw, (min-width: 1000px) 33vw, (min-width: 690px) 100vw, 100vw');
                            } else if($columns == '3') {
                                $image_attrs =  array('title' => '', 'sizes' => '(min-width: 1300px) 33vw, (min-width: 1000px) 33vw, (min-width: 690px) 100vw, 100vw');
                            } else if($columns == '2') {
                                $image_attrs =  array('title' => '', 'sizes' => '(min-width: 1600px) 50vw, (min-width: 1300px) 50vw, (min-width: 1000px) 50vw, (min-width: 690px) 100vw, 100vw');
                            } else {
                                $image_attrs =  array('title' => '', 'sizes' => '(min-width: 1000px) 100vw, (min-width: 690px) 100vw, 100vw');
                            } ?>

                            <div <?php post_class('inner-wrap'); ?>>

                            <?php
                            if ( has_post_thumbnail() ) { 
                                if($style == 'classic_enhanced') {
                                    echo'<a href="' . get_permalink() . '" class="img-link"><span class="post-featured-img">'.get_the_post_thumbnail($post->ID, 'portfolio-thumb', $image_attrs) .'</span></a>'; 
                                } else if($style == 'classic_enhanced_alt') {
                                    $masonry_sizing_type = (!empty($options['portfolio_masonry_grid_sizing']) && $options['portfolio_masonry_grid_sizing'] == 'photography') ? 'photography' : 'default';
                                    $cea_size = ($masonry_sizing_type == 'photography') ? 'regular_photography' : 'tall';
                                    echo'<a href="' . get_permalink() . '" class="img-link"><span class="post-featured-img">'.get_the_post_thumbnail($post->ID, $cea_size, $image_attrs) .'</span></a>'; 
                                }
                            } ?>

                            <?php
                            echo '<span class="meta-category">';
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                $output = null;
                                foreach( $categories as $category ) {
                                    $output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                                }
                                echo trim( $output);
                                }
                            echo '</span>'; ?>
                                
                            <a class="entire-meta-link" href="<?php the_permalink(); ?>"></a>

                            <div class="article-content-wrap">
                                <div class="post-header">
                                    <span class="meta"> <?php echo get_the_date(); ?> </span> 
                                    <h3 class="title"><?php the_title(); ?></h3>    
                                </div><!--/post-header-->
                                <div class="excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                            
                            <div class="post-meta">
                                <span class="meta-author"> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <i class="icon-default-style icon-salient-m-user"></i> <?php the_author(); ?></a> </span> 
                                
                                <?php if(comments_open()) { ?>
                                    <span class="meta-comment-count">  <a href="<?php comments_link(); ?>">
                                        <i class="icon-default-style steadysets-icon-chat-3"></i> <?php comments_number( '0', '1','%' ); ?></a>
                                    </span>
                                <?php } ?>
                                
                                <div class="nectar-love-wrap">
                                    <?php if( function_exists('nectar_love') ) nectar_love(); ?>
                                </div><!--/nectar-love-wrap-->  
                            </div>

                        </div>

                        <?php }  ?>
                    
                    <?php echo ($style !== 'title_only' ? '</div>' : ''); ?>
                
                <?php endwhile; endif; 
                      wp_reset_postdata();
                ?>

            <?php echo ($style == 'title_only' ? '</ul>' : ''); ?>
            
            </div><!--/blog-recent-->
        
        <?php

        wp_reset_query();
        
        $recent_posts_content = ob_get_contents();
        
        ob_end_clean();
    
    } // regular recent posts


    else { //slider


        ob_start(); 
            
        echo $title_label_output; ?>
        
        <?php 
        $recentBlogPosts = array(
          'showposts' => $posts_per_page,
          'category_name' => $category,
          'ignore_sticky_posts' => 1,
          'offset' => $post_offset,
          'tax_query' => array(
              array( 'taxonomy' => 'post_format',
                  'field' => 'slug',
                  'terms' => array('post-format-link','post-format-quote'),
                  'operator' => 'NOT IN'
                  )
              )
        );

        $recent_posts_query = new WP_Query($recentBlogPosts);  


        $animate_in_effect = (!empty($options['header-animate-in-effect'])) ? $options['header-animate-in-effect'] : 'none';
        echo '<div class="nectar-recent-posts-slider" data-height="'.$slider_size.'" data-animate-in-effect="'.$animate_in_effect.'">';

        /*echo '<div class="nectar-recent-post-content"><div class="recent-post-container container"><div class="inner-wrap"><span class="strong">'.$slider_above_text.'</span>';
        $i = 0;
        if( $recent_posts_query->have_posts() ) :  while( $recent_posts_query->have_posts() ) : $recent_posts_query->the_post(); global $post; ?>

                <h2 class="post-ref-<?php echo $i; ?>"><a href=" <?php echo get_permalink(); ?>" class="full-slide-link"> <?php echo the_title(); ?> </a></h2>
                <?php $i++; ?>

        <?php endwhile; endif; 
        echo '</div></div></div>'; */

        echo '<div class="nectar-recent-posts-slider-inner">'; 
        $i = 0;
        if( $recent_posts_query->have_posts() ) :  while( $recent_posts_query->have_posts() ) : $recent_posts_query->the_post(); global $post; ?>

                <?php 
                    $bg = get_post_meta($post->ID, '_nectar_header_bg', true);
                    $bg_color = get_post_meta($post->ID, '_nectar_header_bg_color', true);
                    $bg_image_id = null;
                    $featured_img = null;
                    
                    if(!empty($bg)){
                        //page header
                        $featured_img = $bg;

                    } elseif(has_post_thumbnail($post->ID)) {
                        $bg_image_id = get_post_thumbnail_id($post->ID);
                        $image_src = wp_get_attachment_image_src($bg_image_id, 'full');
                        $featured_img = $image_src[0];
                    }


                ?>

                <div class="nectar-recent-post-slide <?php if($bg_image_id == null) echo 'no-bg-img'; ?> post-ref-<?php echo $i; ?>">

                    <div class="nectar-recent-post-bg"  style=" <?php if(!empty($bg_color)) { ?> background-color: <?php echo $bg_color;?>; <?php } ?> background-image: url(<?php echo $featured_img;?>);" > </div>

                    <?php 

                    echo '<div class="recent-post-container container"><div class="inner-wrap">';

                    echo '<span class="strong">';
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                $output = null;
                                foreach( $categories as $category ) {
                                    $output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', NECTAR_THEME_NAME), $category->name ) ) . '"><span class="'.$category->slug.'">'.esc_html( $category->name ) .'</span></a>';
                                }
                                echo trim( $output);
                            }
                        echo '</span>'; ?>
                    
                        <h2 class="post-ref-<?php echo $i; ?>"><a href=" <?php echo get_permalink(); ?>" class="full-slide-link"> <?php echo the_title(); ?> </a></h2> 
                    </div></div>
                        

                </div>

                <?php $i++; ?>

        <?php endwhile; endif; 

              wp_reset_postdata();
    
         echo '</div></div>';

        wp_reset_query();
        
        $recent_posts_content = ob_get_contents();
        
        ob_end_clean();
    }


    return $recent_posts_content;

}

#-----------------------------------------------------------------#
# REMOVE THE SALIENT RECENT POSTS SHORTCODE AND REPLACE IT WITH OUR CUSTOM ONE
#-----------------------------------------------------------------# 
add_action('init', 'remove_parent_theme_shortcodes', 100);

function remove_parent_theme_shortcodes() {

// remove shortcode from parent theme
// shortcode_name should be the name of the shortcode you want to modify
remove_shortcode( 'recent_posts' );

// add the same shortcode in child theme with our own function
add_shortcode('recent_posts', 'foi_recent_posts');
}




function sal_recent_posts_widget($atts) {
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'amount' => 3,
                'offset' => 0,
            ), $atts
        )
    );

    $posts_args = array( 
        'posts_per_page' => $amount,
        'post_type' => 'post',
        'offset' => $offset,
        'orderby'   => 'date',
        'order'     => 'DESC',
        'post_status' => 'publish',
    );

    $posts = get_posts( $posts_args );
    $posts_count = count($timelines);

    $output = '';

    if($posts_count > 0):
        foreach($posts as $post):


                $count++;
                $output.= '<li>';
                $output.= '<a href="' . get_the_permalink($post->ID) . '" target="_blank">';
                $output.= $post->post_title;
                $output.= '</a>';
                $output.= '</li>';
        endforeach;
    endif;

    return $output;
}
add_shortcode( 'foi_recent_posts', 'sal_recent_posts_widget' );


add_action( 'vc_before_init', 'your_name_integrateWithVC' );
function your_name_integrateWithVC() {
   vc_map( array(
      "name" => __( "Bar tag test", "my-text-domain" ),
      "base" => "bartag",
      "class" => "",
      "category" => __( "Content", "my-text-domain"),
      'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(
         array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text", "my-text-domain" ),
            "param_name" => "foo",
            "value" => __( "Default param value", "my-text-domain" ),
            "description" => __( "Description for foo param.", "my-text-domain" )
         )
      )
   ) );
}

/**
 * Customized version of the Category Filter that includes CSS classes for subcategories
 * New filter available in WP-Admin > Events > Settings > Filters
 */
if ( class_exists( 'Tribe__Events__Filterbar__Filters__Category' ) ) {
    class Tribe__Events__Filterbar__Filters__Category_Custom extends Tribe__Events__Filterbar__Filters__Category {
        /**
         * Flatten out the hierarchical list of event categories into a single list of values,
         * applying formatting (non-breaking spaces) to help indicate the depth of each nested
         * item.
         *
         * @param array $term_items
         * @param array $existing_list
         * @return array
         */
        protected function flattened_term_list( array $term_items, array $existing_list = null ) {
            // Pull in the existing list when called recursively
            $flat_list = is_array( $existing_list ) ? $existing_list : array();
            // Add each item - including nested items - to the flattened list
            foreach ( $term_items as $term ) {
                $flat_list[] = array(
                    'name'  => $term->name,
                    'value' => $term->term_id,
                    'data'  => array( 'slug' => $term->slug ),
                    'class' => 'tribe-events-category-' . $term->slug . ' tribe-events-subcategory-depth-' . $term->depth,
                );
                if ( ! empty( $term->children ) ) {
                    $child_items = $this->flattened_term_list( $term->children, $existing_list );
                    $flat_list = array_merge( $flat_list, $child_items );
                }
            }
            return $flat_list;
        }
    }
    new Tribe__Events__Filterbar__Filters__Category_Custom( __( 'Event Categories with Child Classes', 'tribe-events-filter-view' ), 'category' );
}

function add_salient_studio_to_vc() {
return false;
}