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
<!-- <style>
	.entry-title{
		background-color: #FF0000;
	}
	.single-below-header{
		visibility: hidden;
	}
</style> -->
<div class="location-container">
		    <div class="location-top-content">
                    
                        
                
                <div class="address">
					<div class="location-name"><?php echo $name;?></div> 
					<br>
                    <div><?php echo ($city . ',' . ' ' . $state);?></div>								   
			  		<div><?php echo $phone;?></div>
					<br>
                </div>                    

				<div class="first-p-title"><?php echo $city;?> Pool Service with Pool Troopers</div>    
				
                <div class="first-p">
                    <?php echo $firstP;?>
				</div>
                       
                                
                               
            </div><!-- #location-top-content -->
		</div><!-- .container -->