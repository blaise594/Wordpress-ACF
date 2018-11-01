


function testimonials_search(doctor_name_filter) {
		//add our ajax loader
		$("#testimonials-section .ajax-content-loader").addClass("active");

		//process the form
		$.ajax({
			type: "POST", // define the type of HTTP verb we want to use (POST for our form)
			url: ajaxcall.ajaxurl,
			data: {
				action: "filter_testimonials", //calls the function in the functions.php file
				doctor_name_filter: doctor_name_filter
			},
			success: function(response) {
				//update our testimonial content
				$("#testimonials-container").html(response);

				//remove the loader
				$("#testimonials-section .ajax-content-loader").removeClass(
					"active"
				);
			}
		});

		return false; //this prevents the form from submitting to a new page.
    }
    

    //in functions.php
    add_action( 'wp_ajax_filter_testimonials', 'prefix_ajax_filter_testimonials' );
add_action( 'wp_ajax_nopriv_filter_testimonials', 'prefix_ajax_filter_testimonials' );
function prefix_ajax_filter_testimonials() {
    // Handle request then generate response using WP_Ajax_Response
    $doctor_name_filter = $_POST[ 'doctor_name_filter' ];
    
    // return all our data to an AJAX call
    echo sal_return_testimonials($doctor_name_filter,'');

    wp_die(); // this is required to terminate immediately and return a proper response 
}

//Line 2426 functions.php
$output.= sal_return_testimonials($physician_initial_query, $initial_amount);

//Line 2421 functions.php
$physician_initial_query = ( !empty($_GET['physician']) ? $_GET['physician'] : '' );