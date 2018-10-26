    //main ajax call 
    function zip_search_ajax(){
           
        var zipcode = $('form#zip_search_form input[name="zip_search"]').val();
    
    
                //process the form
            $.ajax({
                    type: "POST", // define the type of HTTP verb we want to use (POST for our form)
                    dataType : "json",
                    url: ajaxcall.ajaxurl,
                    data: {
                        "action": "zip_search", //calls the function in the functions.php file
                        "zipcode": zipcode
                    },
                    success: function(response) {
    
                        if(response.length > 0){
                            
        
                        }
                    }
    })}
    
                <form action="" method="post" id="zip_search_form">
                <input class="form-control search-input" autocomplete="off" name="zipcode" type="text" value="" placeholder="Enter Zip Code" />
                </form><input type="submit" />                                                                
    
    <script type="text/javascript">
    document.getElementById("zip_search_form").onclick = function () {
        location.href = "<?php echo zip_search(zipcode)?>";
    };
</script>

<?php$zipcode = ( isset($_GET['zipcode']) ?  $_GET['zipcode'] : ''); echo zip_search($zipcode); $url = zip_search( $zipcode ); wp_redirect($url); exit();?>