<?php
function total_freedom(){
    $output='<div class="row pricing-table three-cols " data-style="default"><div class="pricing-column no-highlight extra-color-2">';
    $output.='<h3>Freedom Swim</h3>';
    $output.='<div class="pricing-column-content">';
    $output.='<h4> <span class="dollar-sign">$ </span>'; 
    $output.=get_field('freedom_swim_price'); 
    $output.='</h4>';
    $output.='<span class="interval">Per Month </span></div></div><div class="pricing-column no-highlight extra-color-2">';
    $output.='<h3>Freedom Plus</h3>';
    $output.='<div class="pricing-column-content">';
    $output.='<h4> <span class="dollar-sign">$</span>';
    $output.=get_field('freedom_plus_price');
    $output.='</h4>';
    $output.='<span class="interval">Per Month </span></div></div><div class="pricing-column highlight extra-color-2">';
    $output.='<h3>Total Freedom <span class="highlight-reason">Most Popular </span></h3>';
    $output.='<div class="pricing-column-content">';
    $output.='<h4> <span class="dollar-sign">$</span>';
    $output.=get_field('total_freedom_price');
    $output.='</h4>';
    $output.='<span class="interval">Per Month</span>';
    $output.='</div>';
    $output.='</div>';
    $output.='</div>';

    return $output;
}
add_shortcode('price_chart', 'total_freedom');
?>