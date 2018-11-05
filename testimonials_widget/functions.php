<?php
/**
 * Load Testimonial Widget
 */
require get_template_directory() . '/inc/class-testimonial-widget.php';




/**
 * Enqueue admin testimonials javascript
 */
function testimonials_enqueue_scripts() {
  wp_enqueue_script(
    'admin-testimonials', get_template_directory_uri() . '/js/admin-testimonials.js',
    array( 'jquery', 'underscore', 'backbone' )
  );
}
add_action( 'admin_enqueue_scripts', 'testimonials_enqueue_scripts' );
?>