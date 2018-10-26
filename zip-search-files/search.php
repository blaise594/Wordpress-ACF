<?php $zipcode = ( isset($_GET['zipcode']) ?  $_GET['zipcode'] : ''); 
	$url = zip_search( $zipcode );
    wp_redirect($url);
    exit();?>

    <?php $zipcode = ( isset($_GET['zipcode']) ?  $_GET['zipcode'] : ''); ?>