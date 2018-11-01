$("form#zipcode").on("submit", function(event) {
    $('form#zipcode .clear-button').addClass('active');
    event.preventDefault();
    
    zipcode_search(zip_search_filter());
    });    

function zipcode_search(zip_search_filter) {
    //add ajax loader
    $("form#zipcode .ajax-content-loader").addClass("active");

    //process the form
    $.ajax({
        type: "POST", // define the type of HTTP verb we want to use (POST for our form)
        url: ajaxcall.ajaxurl,
        data: {
            action: "locations_search", //calls the function in the functions.php file
            zip_search_filter: zip_search_filter
        },
        success: function(response) {
            //redirect to new page
            if (response != "") {
                    alert("You will now be redirected.");
                    window.location = "http://www.example.com/";
            }

            //remove the loader
            $("#zipcode .ajax-content-loader").removeClass(
                "active"
            );
        }
    });

    return false; //prevents the form from submitting to a new page.
}