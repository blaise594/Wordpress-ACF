<?php
function cta(){
    $output.='<div id="call-to-action">';
    $output.='<div class="container">';
    $output.='<span> Call Our Residential Pool Customer Service Team  </span>';
    $output.='<div id="phone">1-866-766-5877</div>';
    $output.='<a class="nectar-button  see-through" data-color-override="false" href="/find-location/" style="visibility: visible; color: rgb(255, 255, 255); border-color: rgba(255, 255, 255, 0.75);">Find Your Location </a>';
    $output.='</div>';
    $output.='</div>';
return $output;
}
add_shortcode('cta_footer', 'cta');

function cta(){
    $output='<div id="call-to-action">
    <div class="container">        
        <span> Call Our Residential Pool Customer Service Team  </span>
        <a class="nectar-button  see-through" data-color-override="false" href="/find-location/" style="visibility: visible; color: rgb(255, 255, 255); border-color: rgba(255, 255, 255, 0.75);">Find Your Location </a>
    </div>
</div>';
return $output;
}
add_shortcode('cta_footer', 'cta');

//footer #355
<script>document.getElementById("phone").innerHTML = '<?php echo phone_footer; ?>';</script>

function phone_script(){
    $output. = '<script>document.getElementById("phone").innerHTML ='; 
    $output. = "'<?php echo zip_display(); ?>';</script>;";
	echo $output;
}
function cta(){
    $output.='<div id="call-to-action">';
    $output.='<div class="container">';
    $output.='<span> Call Our Residential Pool Customer Service Team  </span>';	
    $output.='<div id="phone-footer">1-866-766-5877</div>';
	$output.='<a class="nectar-button  see-through" data-color-override="false" href="/find-location/" style="visibility: visible; color: rgb(255, 255, 255); border-color: rgba(255, 255, 255, 0.75);">Find Your Location </a>';
	$output.='</div>';
    $output.='</div>';
    
return $output;
}
add_shortcode('cta_footer', 'cta');
<a href="tel:1-866-766-5877">1-866-766-5877</a>

<div id="phone-body-link"><a href="tel:1-866-766-5877">1-866-766-5877</a></div>
<script>document.getElementById("phone-footer").innerHTML = '<?php echo phone_footer(); ?>';</script>
[phone_body_display]
?>