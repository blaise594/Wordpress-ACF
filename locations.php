<?php 
$name = get_field('location_name');
$address_1 = get_field('Address1');
$address_2 = get_field('address_line_2');
$city = get_field('city');
$state = get_field('state');
$zipcode = get_field('zipcode');
$phone = get_field('phone_number');	
$zips_serviced = get_field('zip_codes_services');	
$firstP= get_field('first_paragraph');
?>

<div class="container-wrap">
    <div class="main-content">
	    <div class="container">
		    <div class="location-top-content">
                    
                        
                
                <div class="address">
                    <div class="location-name"><?php echo $name;?></div> 
                    <div><?php echo ($city . ',' . ' ' . $state);?></div>								   
			  		<div><?php echo $phone;?></div>
                </div>                    

                <div class="first-p-title"><?php echo $city;?> Pool Service with Pool Troopers</div>      
                <div class="first-p">
                    <?php echo $firstP;?>
				</div>
                       
                                
                               
            </div><!-- #location-top-content -->
		</div><!-- .container -->
	</div><!-- .main-content -->
</div><!-- .container-wrap -->