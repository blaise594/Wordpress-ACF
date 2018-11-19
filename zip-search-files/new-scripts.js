/*
 * Put all your regular jQuery in here.
 * Within this funtion you can use the namespace $ instead of jQuery
 * ex. use this $('#id') ... NOT jQuery('#id')
*/
jQuery(document).ready(function($) {
    $("li#menu-item-1863").onload(function(_event){
        city_display();
    });
    $("form#zipcode").on("submit", function(event) {
        event.preventDefault();

        $('form#zipcode .clear-button').addClass('active');

        //get the entered zipcode value to pass through our function.
        var zipcode = $(this).find('input[name="zipcode"]').val();
                
        zipcode_search(zipcode);
       
    });    

    function zipcode_search(zipcode) {
        //add ajax loader
        $("form#zipcode .ajax-content-loader").addClass("active");

        //process the form
        $.ajax({
            type: "POST", // define the type of HTTP verb we want to use (POST for our form)
            url: ajaxcall.ajaxurl,
            data: {
                action: "locations_search", //calls the function in the functions.php file
                zipcode: zipcode
            },
            success: function(response) {
				//redirect to new page
                if (response.length > 5) {
                        //alert(response);
					
                        window.location = response;
                }
				else{
					window.location = "/no-locations-found/";
				}
				
                //remove the loader
                $("#zipcode .ajax-content-loader").removeClass(
                    "active"
                );
            }
        });

        return false; //prevents the form from submitting to a new page.
    }

/*Javascript logic to target phone div here*/

    function city_display() {
        $.ajax({
            type: "POST", // define the type of HTTP verb we want to use (POST for our form)
            url: ajaxcall.ajaxurl,
            data: {
                action: "get_the_user_ip", //calls the function in the functions.php file
                //city:city
            },
            success: function(response) {
				alert(response);
				
                
            }
        });
    }

// function displayPhone() {
//     document.getElementById("phone").innerHTML = "Iframe is loaded.";
// }

/*
	 * Location Single Page Testimonial Slideshow
	 * Add slick slider to the testimonial section of the lcoation page
	*/
	if ($(".testimonial-slideshow").length > 0) {
		$(".testimonial-slideshow").slick({
			dots: false,
			infinite: true,
			speed: 300,
			fade: false,
			cssEase: "ease",
			autoplay: true,
			autoplaySpeed: 8000,
			prevArrow:
				'<div class="slick-prev"><i class="fa fa-chevron-left"></i></div>,',
			nextArrow:
				'<div class="slick-next"><i class="fa fa-chevron-right"></i></div>',
			draggable: false,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						draggable: true
					}
				}
			]
		});
    }
}); /* end of as page load scripts */