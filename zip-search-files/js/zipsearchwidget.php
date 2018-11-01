<?php
function zip_search_widget($atts) {
    //shortcode options
    extract(
        shortcode_atts(
            array(
                'widget_title' => 'Find a Location',
                'widget_layout' => 'boxed',
            ), $atts
        )
    );

 

 $clear_class = (isset($_GET['zipcode']) ? 'active' : '');

 $output = '<div class="zip-search-widget layout-' . $widget_layout . '">';
 $output.= ($widget_title !== '' ? '<h3 class="zip-search-widget-title">' . $widget_title . '</h3>' : '');
 $output.= ($widget_layout == 'full' ? '<div class="container">' : '');
 $output.= ($widget_layout == 'full' ? '<div class="col-md-6">' : '');
 $output.= '<div class="search-fields-container">';
 

 $output.= '</div>';//.search-fields-container
 $output.= ($widget_layout == 'full' ? '</div>' : '');//.col-md-6
 $output.= ($widget_layout == 'full' ? '<div class="col-md-6">' : '');
 $output.= '<div class="links-container">';
 $ouput='<form id="zipcode" action="" method="post"><input class="form-control search-input" autocomplete="off" name="zipcode" type="text" value="" placeholder="Enter Zip Code" />
 <input type="submit" /></form>';
 $output.= '</div>';//.links-container
 $output.= ($widget_layout == 'full' ? '</div>' : '');//.col-md-6
 $output.= ($widget_layout == 'full' ? '</div>' : '');//.container
 $output.= '</div>';//#zip-search-widget

 return $output;
    

    return $output;
}
add_shortcode('zip_search', 'zip_search_form');
?>
<?php
function zip_search_form() {
    
 $form='<form id="zipcode" action="" method="post"><input class="form-control search-input" autocomplete="off" name="zipcode" type="text" value="" placeholder="Enter Zip Code" />
 <input type="submit" /></form>'; 
    

    return $form;
}
add_shortcode('zip_search', 'zip_search_form');
?>
