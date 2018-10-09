<?php
//Not sure if this is neccesary for site
//Insert into functions.php file
/*********************
ADD ACF OPTION PAGES
add the acf options tab to the admin navigation.
*********************/
//add the acf options tab to the admin navigation.
if(function_exists('acf_add_options_page')) { 

    acf_add_options_page();
    acf_add_options_sub_page('Header');
    acf_add_options_sub_page('Footer');
    //General Theme Options
    acf_add_options_page(array(
        'page_title'    => 'Website Options',
        'menu_title'    => 'Website Options',
        'menu_slug'     => 'website-options',
        'capability'    => 'edit_posts',
        'parent_slug'   => '',
        'position'      => false,
        'icon_url'      => false,
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Header',
        'menu_title'    => 'Header',
        'menu_slug'     => 'website-options-header',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'website-options',
        'position'      => false,
        'icon_url'      => false,
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Footer',
        'menu_title'    => 'Footer',
        'menu_slug'     => 'website-options-footer',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'website-options',
        'position'      => false,
        'icon_url'      => false,
    ));

    
}
?>