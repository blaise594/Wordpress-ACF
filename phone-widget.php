<?php
//insert into functions.php file
//Not sure if this is necesarry
add_action('widgets_init', 'mat_widget_areas');
function mat_widget_areas(){
    register_sidebar(array(
        'name'  => 'Phone Widget',
        'id'    => 'phone-widget',
        'description' => 'The widget to display the correct phone number for a location near a user',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_wiget'   => '</li>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
?>