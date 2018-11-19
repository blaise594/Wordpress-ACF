<?php 
ob_start();
session_start();
/*
 * Firewall Framework v1
 * 
 */
// Set the ENV variable default is development as set in the .htaccess file
$configSection = getenv('FW_CONFIG') ? getenv('FW_CONFIG') : 'dev';
$GLOBALS['env'] = $configSection;

$controllerPath = "controllers/";

// Set error level
if ($GLOBALS['env'] == 'production') {
    ini_set('display_errors', 1);
}
else {
    ini_set('display_errors', 1); // turning both off, set to 1 when something is wrong.. mostly so we don't forget to turn it back before a push
}

date_default_timezone_set('America/Halifax');

include 'library/phpscripts/common_func.php';
include 'library/phpscripts/urlparse.php';
include 'library/phpscripts/Webservices.php';
require_once 'library/vendor/autoload.php';

use GeoIp2\Database\Reader;
$reader = new Reader('library/db/GeoIP2-City.mmdb');

$wsObj = new Webservices();

$ip = $_SERVER['REMOTE_ADDR'];

 $systemUrl = "/";
    if(isset($_SESSION['pooltroopers']['systemurl'])){
        $systemUrl = $_SESSION['pooltroopers']['systemurl'];
    }
 
 $userCity = $_SESSION['pooltroopers']['city'];

 if( /*!(isset($userCity))*/ false){  /* Removed Feb 14 */
     
     $log->Add("USER IP: " . $ip );
     if(strlen($ip) >6){

        $record = $reader->city($ip);
        $userCity = $record->city->name;
        $res = setcookie("userCity", $userCity,time() + (86400 * 30), "/");       
        $location = $wsObj->getLocationDataByCity($userCity);
        
        $systemUrl = "/".$location[0]['url_state']."/".$location[0]['url_city']."/";
        
        $_SESSION['pooltroopers']['id'] = $location[0]['id'];
        $_SESSION['pooltroopers']['city'] = $location[0]['city'];
        $_SESSION['pooltroopers']['state'] = $location[0]['state'];
        $_SESSION['pooltroopers']['phone'] = $location[0]['phone_primary'];
        $address = $location[0]['address'].' ' . $location[0]['zipcode'] . ' ' . $location[0]['city'] .', '. $location[0]['state'];
        $_SESSION['pooltroopers']['address'] = $address;
        $urlCity = $location[0]['url_city'];
        
        // site the site profile
        $_SESSION['pooltroopers']['profile'] = $profile = 'standard';
        
        $altCities = array("houston","missouricity","sugarland","spring");
        
        if(in_array($urlCity, $altCities)){
            $_SESSION['pooltroopers']['profile'] = $profile = 'alternate';
        }        
        
        //Site links
        $_SESSION['pooltroopers']['systemurl'] = $systemUrl;
        

    }
        // else{ // This is for local testing
            
        //     $userCity = "Moncton";       
        //     $res = setcookie("userCity", $userCity,time() + (86400 * 30), "/");

        //      $location = $wsObj->getLocationDataByCity($userCity);         
        //      //$systemUrl = "/".$location[0]['url_state']."/".$location[0]['url_city']."/";
             
        //      $_SESSION['pooltroopers']['id'] = $location[0]['id'];
        //      $_SESSION['pooltroopers']['city'] = $location[0]['city'];
        //      $_SESSION['pooltroopers']['state'] = $location[0]['state'];
        //      $_SESSION['pooltroopers']['phone'] = $location[0]['phone_primary'];
        //      $address = urldecode($location[0]['address']).' ' . urldecode($location[0]['zipcode']) . ' ' . urldecode($location[0]['city']) .', '. urldecode($location[0]['state']);
        //      $_SESSION['pooltroopers']['address'] = $address;
        //      //$log->Add("SESION: "  . print_r($_SESSION,true));
        // }
    
 }else{
     
        $location = $wsObj->getLocationDataByCity($userCity);
        $systemUrl = "/".$location[0]['url_state']."/".$location[0]['url_city']."/";

 }

if (isset($_SERVER['REQUEST_URI'])) {
    
    $parse = new URLParse();
    $path = $parse->parse_path($_SERVER['REQUEST_URI'],true);   
    $directory = $path['route'][0];
    $log->Add("Directory: " . $directory);
    // 
    $isException = false;
    
    $statesInSystem = array('florida','texas','arizona','nevada','newbrunswick');
    // set for state/city
    if(in_array($directory, $statesInSystem)){
        $log->Add("Found an Exception: $directory");
        $isException = true;

    }

    if($isException){
        //$log->Add("Exemption is true");
        
        $state = $path['route'][0];
        $city = $path['route'][1];
        $service = $path['route'][2];
        
        // BUG FIX. Chris Upright Jan 10, 2017
        $wsObj->updateLocationVariables($city, $state);
        
        include_once $controllerPath.'/Location.php';
        
        $full_path = "views/location/index.php";

    }else{ // Open Exception Else
        
        // Is  directory
        $isDirectory = false;
        $checkDirectory = glob("views/*");  
        foreach($checkDirectory as $dir)
        {
            $dir = str_replace("views/", "", $dir);     
                if($directory == $dir){
                    $isDirectory = true;
                    $log->Add("Directory: $dir");
                }   
        }
    
    //Yes it's a folder in the system
    if($isDirectory){   
            // base views directory
            $filedir    = "views";
            // create the routing
            $directory  = $path['route'][0];            
            // Get the file
            $file       = $path['route'][1];
            // Is it a file in the directory
            if(isset($file)){
                // build dir
                $files = scandir('views/'.$directory);
                // check that directory
                //$log->Add("Checking Directory: views/$directory");
                // Look for the file
                if(in_array($file.'.php', $files)){
                    //$log->Add("Found a mathing file name: $file");                    
                    $file = $file.".php";
                }else{
                    //$log->Add("No matching file found: $file");
                    $file = "index.php";
                }
                
                
            }else{
                //$log->Add("No file found");
                $file = "index.php";
            }
            // $controller = ucfirst($directory);
            // include_once $controllerPath.'/'.$controller.'.php';
            $full_path = $filedir.'/'.$directory.'/'.$file;

    }else{
        // Nothing in the URL
        //$log->Add("No Directory");
        //include_once $controllerPath.'/Welcome.php';
        $full_path = 'views/site/index.php';
    }
    
}//Close exception
    
}else{
    // Nothing set
    //$log->Add("Go to the default");
    include_once $controllerPath.'/Welcome.php';
    $full_path = 'views/site/index.php';
}
?>

    <?php
/*
 * This layout file is the FirewallFramework v1 loader
 * USE: 
 *  All JS and CSS will be loaded here
 *  Pre-processing will happen here
 *  $page['var'] is an array for holding all
 * 
 */

// mobile detect
require_once 'library/phpscripts/mobileDetect.php';
require_once 'library/phpscripts/Webservices.php';

    $webServicesObj = new Webservices();
    $detect = new MobileDetect;
    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

// Define page title and description based on the page
$page['title'] = "Pool Troopers your full service pool maintenance company";
$titleCity = $_SESSION['pooltroopers']['city'];
$titleState = $_SESSION['pooltroopers']['state'];
$showNav = true;
$showFooter = true;
// Meta Data runs from here
switch($directory){
    
    case "ask-us-anything":
        $page['title'] =  "Ask Us Anything The Skimmer Blog - Pool Troopers ";
        $page['description'] = "Ask us anything at Pool Troopers, your full service pool cleaning company: pool service, chemical services, pool equipment repair.";
        break;
    case "combo-packages":
        $page['title'] = "Pool Cleaning and Service Packages - Pool Troopers";
        $page['description'] = "Combo Packages by Pool Troopers, your full service pool cleaning company: pool service, chemical services, pool equipment repair.";
        break;
    case "contact-us":
        $page['title'] = "Pool Cleaning Company Contact Info | Pool Troopers";
        $page['description'] = "Pool Cleaning Company Contact Information: Pool Troopers. We provide pool service, chemical services, pool equipment repair. Contact us today for a quote.";
        break;
    case "find-location":
        $page['title'] = "Pool Service Company Locations | Pool Troopers";
        $page['description'] = "Pool Service Company Locations in Florida, Texas, and Arizona. Find the Pool Troopers locations nearest to you to receive a full service pool cleaning.";
        break;
    case "get-started":
        $page['title'] = "Pool Service – Get Started | Pool Troopers";
        $page['description'] = "Pool Service and getting started with Pool Troopers. - We believe professional pool cleaning services should be easy for you. Call us today!";
        break;
    case "inside-story":
        $page['title'] = "Team players who think like owners - Pool Troopers";
        $page['description'] = "Pool Troopers inside story, your full service pool cleaning company: pool service, chemical services, pool equipment repair.";
        break;
    case "invisible-heros":
        $page['title'] =  "Invisible Heroes | Pool Troopers";
        $page['description'] = "Invisible Heroes keep us free and safe. Learn more about Pool Troopers’ Invisible Heroes Program and how you can participate and submit your story.";
        break;
    case "join-us":
        $page['title'] = "Pool Technician Jobs | Pool Troopers";
        $page['description'] = "Pool Technician Jobs at Pool Troopers include pool service responsibilities, chemical services, pool equipment repair, and more. Apply now!";
        break;
    case "net-zero":
        $page['title'] = "Net Zero Approach | Pool Troopers";
        $page['description'] = "Net Zero Approach of pricing for our chemical service is designed to give you greater freedom without costing you more money. Call now to learn more.";
        break;
    case "our-freedom-guarantee":
        $page['title'] = "Pool Troopers Freedom Guarantee - Pool Troopers";
        $page['description'] = "Pool Troopers freedom guarantee, means no contracts, the benefits of our priority repair advantage, and the free use of our salt chlorine generator.";
        break;
    case "our-history":
        $page['title'] = "Pool Troopers History | Pool Troopers";
        $page['description'] = "Pool Troopers History and information. Learn more about the beginning of Pool Troopers and how they’ve grown across Florida, Texas, and Arizona.";
        break;
    case "our-people":
        $page['title'] = "Pool Troopers Pool Service Team | Pool Troopers";
        $page['description'] = "Pool Troopers professional pool service team has your back. Learn more about our team today and the services we offer. Call to schedule an appointment!";
        break;
    case "premium-service":
        $page['title'] = "Enjoy the freedom of our Premium Chemical Service with Pool Troopers";
        $page['description'] = "Pool Troopers premium pool service - your full service pool cleaning company: pool service, chemical services, pool equipment repair.";
        break;
    case "repair-service":
        $page['title'] = "Pool repair service - Pool Troopers";
        $page['description'] = "Pool repair service by Pool Troopers provides you with pool equipment repair by our fully licensed pool repair professionals. Call us today for a free estimate.";
        break;
	case "pool-repair-service":
        $page['title'] = "Pool Repair Service | Pool Troopers";
        $page['description'] = "Pool repair service by Pool Troopers provides you with pool equipment repair by our fully licensed pool repair professionals. Call us today for a free estimate.";
        break;
    case "saltwater-system":
        $page['title'] = "Salt Water Pool System - Free Use | Pool Troopers";
        $page['description'] = "Salt Water Pool System offered for free by Pool Troopers eliminate chlorine spikes, and create a silky, natural, and swim-safe experience. Call now.";
        break;
    case "premium-chemical-service":
        $page['title'] = "Pool Chemicals - Premium Chemical Service | Pool Troopers";
        $page['description'] = "Pool Chemicals keep your pool clean. Pool Troopers premium chemical service gives you free use of our salt water chlorine generator. Call today to learn more.";
        break;
    case "conventional-chemical-service":
            $page['title'] = "Pool Chemicals Conventional Chemical Service | Pool Troopers";
        $page['description'] = "Pool Chemicals and conventional chemical services offered by Pool Troopers Includes all of the necessary chemicals to keep you and your family swim safe.";
            break;
    case "cleaning-services":
        $page['title'] = "Pool Cleaning Service | Pool Troopers";
        $page['description'] = "Pool Cleaning Service provides cleaning, maintenance & repair. When you need the best, trust Pool Troopers for all your pool and spa needs.";
        break;
    case "what-we-do":
        $page['title'] = "Pool service, Cleaning and Repair | Pool Troopers";
        $page['description'] = "Pool Service by Pool Troopers has options for a full service pool cleaning, chemical services, and pool equipment repair. Call today to learn more.";
    break;
    case "pool-watcher":
        $page['title'] = "National Pool Watcher Program  - Pool Troopers";
        $page['description'] = "We created the National Pool Watcher Program because we believe undistracted pool watchers can eliminate preventable pool related accidents";
        break;
    case "pool-service":
        $page['title'] = "Pool Service Information, Pool Troopers";
        $page['description'] = "Pool Service provided by Pool Troopers includes pool cleaning, maintenance, and repair. When you need the best, trust us for all of your pool and spa needs.";
        break;
    case "conventional-combo-packages":
        $page['title'] = "Pool Service Packages | Pool Troopers";
        $page['description'] = "Pool Service Packages offered by Pool Troopers. Learn more now about our package options. We're happy to help you choose the best package for your needs.";
        break;
    case "our-best-deal":
    case "2-months-free":
    case "1-month-free":
    case "current-offer":
        $page['title'] = "Current Offer  - Pool Troopers";
        $page['description'] = "";
        $showNav    = false;
        $showFooter = false;
        break;   
    case "":
        $page['title'] = "Pool Troopers Professionals Providing Pool Cleaning Services";
        $page['description'] = "Pool Troopers licensed, insured professionals provide pool cleaning services such as  chemical services, pool equipment repair, and more.";
    break;

    default:
    $page['title'] = "Pool Service $titleCity, $titleState by Pool Troopers ";
    $page['description'] = "Professional $titleCity pool services company - cleaning, maintenance &amp; repair. When you need the best, trust $titleCity Pool Troopers for all of your pool and spa needs.";
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta name="robots" content="noodp">
        <link rel="canonical" href="https://www.pooltroopers.com/">

        <title><?php echo $page['title'];?></title>        
        <meta name="description" content="<?php echo $page['description'];?>">
            
        <meta property="og:locale" content="en_US">
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php echo $page['title'];?>">
        <meta property="og:description" content="<?php echo $page['description'];?>">
        <meta property="og:url" content="https://www.pooltroopers.com/">
        <meta property="og:site_name" content="Pool Troopers">
        <meta property="og:image" content="https://www.pooltroopers.com/library/img/pooltroopers_facebook.jpg">
        
        <meta name="twitter:card" content="summary">
        <meta name="twitter:description" content="<?php echo $page['description'];?>">
        <meta name="twitter:title" content="<?php echo $page['title'];?>">
        <meta name="twitter:image" content="https://www.pooltroopers.com/library/img/pooltroopers_twitter.jpg">
        
        <link rel="icon" href="https://www.pooltroopers.com/library/img/icons/cropped-logo-32x32.png?x86745" sizes="32x32">
        <link rel="icon" href="https://www.pooltroopers.com/library/img/icons/cropped-logo-192x192.png?x86745" sizes="192x192">
        <link rel="apple-touch-icon-precomposed" href="https://www.pooltroopers.com/library/img/icons/cropped-logo-180x180.png?x86745">
        <meta name="msapplication-TileImage" content="https://www.pooltroopers.com/library/img/icons/cropped-logo-270x270.png">
        <link rel="shortlink" href="https://www.pooltroopers.com/">
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- Google font library -->
       <!--  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500,700" rel="stylesheet"> 
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
        <script src="https://use.fontawesome.com/6c8680e8e1.js"></script>
        
        <?php if($deviceType=='computer'){?>
            <link rel="stylesheet" href="/library/css/core.min.css">
        <!-- raw files
            <link rel="stylesheet" href="/library/css/core.css">
            <link href="/library/css/ct-navbar.css" rel="stylesheet" /> 
        -->
        <?php }else{?>
            <link rel="stylesheet" href="/library/css/mobile_core.min.css">
        <!-- <link rel="stylesheet" href="/library/css/mobile_core.css">
            <link href="/library/css/mobile-ct-navbar.css" rel="stylesheet" />
        -->
        <?php }?>
        <?php /*FormValidation plugin and the class supports validating Bootstrap form */ ?>
        <link rel="stylesheet" href="/library/formvalidation/css/formValidation.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="/library/formvalidation/js/formValidation.min.js"></script>
        <script src="/library/formvalidation/js/framework/bootstrap.min.js"></script>
        <script src="/library/js/typeahead.bundle.min.js"></script>
        <script src="/library/js/jquery.cookie.js"></script>
        <script src="/library/js/ct-navbar.js"></script>
        
<?php    // Analytics include
                include_once 'library/phpscripts/analytics.php';
                ?>

<?php
    //This is where the custom scripts for the head go    
    switch($path['route'][1])
    {
        case "bocaraton":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=23" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="23"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5086.6194763122485!2d-80.0883920608291!3d26.352180782902092!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d8e21c832f1e9b%3A0x466a0b0c50e8c9c7!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536073608880" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "brandon":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=33" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="33"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54408.523905821465!2d-82.31774814187702!3d27.920640924080853!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2d6c2a702caff%3A0xe0719ff5ac6be909!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536073510377" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "bradenton":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=40" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="40"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d64391.02327560717!2d-82.6008785276407!3d27.482848128265598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c3167896b90915%3A0xce9019e7c179ee81!2sBay+Area+Pool+Service!5e0!3m2!1sen!2sus!4v1536073321507" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break; 
        case "capecoral":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=6" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="6"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114159.10032477547!2d-82.00462811819897!3d26.60131717052666!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88db4715b6e6aaab%3A0x23aae4efe8a8383f!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536073157124" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "clearwater":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=26" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="26"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d76083.06493610264!2d-80.29912795201304!3d26.280779831236256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88ded56e02bdcecb%3A0xe53f15d16158e1ab!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536072179239" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "coralsprings":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=47" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="47"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d76083.06493610264!2d-80.29912795201304!3d26.280779831236256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88ded56e02bdcecb%3A0xe53f15d16158e1ab!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536072179239" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "dadecity":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=15" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="15"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127775.08372964188!2d-82.28869241630892!3d28.465960598761924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2a822d3fa9ead%3A0xf45a4d10ee4034e2!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536071645635" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "ftmyers":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=9" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="9"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d93415.28109086349!2d-81.99971994756821!3d26.626612629849912!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88db4715b6e6aaab%3A0x23aae4efe8a8383f!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536071402016" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "northfortlauderdale":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=48" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="48"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d68323.96990223724!2d-80.28030216151167!3d26.19506214128249!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9011ab9feaaab%3A0x388c0e2f27d91e5e!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536070905913" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';            
            break;
        case "jupiter":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=24" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="24"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d184112.03898346866!2d-80.22767048719413!3d26.855028225008972!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d929e3dc9e7957%3A0x739a79c50b58fdff!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536070659601" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "lakemary":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=20" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="20"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d314705.68634040677!2d-81.49662660244097!3d28.748712640638356!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e772ba8e39f205%3A0x3b800dd75ece943e!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536070365020" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "longwood":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=19" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="9"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d76855.46227852405!2d-81.34726216440244!3d28.6803950622085!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e7710a5ea55555%3A0x391351f51461fd!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536069331169" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "naples":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=4" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="4"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d109227.89167406016!2d-81.79485497351217!3d26.095193202761106!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88db72d28fe21931%3A0x917d5f173e9ff5bd!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535754182967" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "newportrichey":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=30" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="30"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d93186.63238150849!2d-82.78841225989255!3d28.221801451452254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c28de414140001%3A0xfafcb8eeb6cb2cb7!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535753956819" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "orlando":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=18" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="18"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d447710.4238501529!2d-81.63531761785339!3d28.757247818453735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e778da2946eac1%3A0xd7d9412fa15b0d7c!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535753187279" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "oviedo":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=17" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="17"></a>';
            $review='<script type="text/javascript" src="//sites.yext.com/232141-reviews.js"></script>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d447710.4238501529!2d-81.63531761785339!3d28.757247818453735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e778da2946eac1%3A0xd7d9412fa15b0d7c!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535753187279" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "pompano":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=49" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="49"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d76710.16429244506!2d-80.20605575846788!3d26.237957409446373!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d8d843a1540001%3A0x31b5d05e70f1a210!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535752881668" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "portcharlotte":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=44" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="44"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d227480.7250958433!2d-82.38447391840104!3d27.01795572978484!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88dcab64a5f15555%3A0xa6fc683c49228480!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535752496216" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "sarasota":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=46" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="46"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113257.15899941282!2d-82.50382215453479!3d27.49147276582255!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c33f80c5283db3%3A0xda4ecd3a07478258!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535752285883" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "springhill":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=14" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="14"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d112197.61464613353!2d-82.5991045883539!3d28.50436836586778!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e81e10a67110c3%3A0xb6e877b5f92025c3!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535752154721" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break; 
        case "stpetersburg":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=28" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="28"></a>';
            $review='<script type="text/javascript" src="//sites.yext.com/232718-reviews.js"></script>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d133039.32123148328!2d-82.73750744722543!3d27.849223512775264!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2eded3d700001%3A0x15549c168be4109b!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535751826571" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;   
        case "tampa":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=37" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="37"></a>';            
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d93450.52991066617!2d-82.50596091911176!3d27.916414116921757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2c1980040769f%3A0x64d11d0f8cd1133b!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535751386937" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break; 
        case "venice":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=42" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="42"></a>';
            $review='<script type="text/javascript" src="//sites.yext.com/232726-reviews.js"></script>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d183894.29485160677!2d-82.3864514361365!3d27.019377646286173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88dcac8301af74eb%3A0x53d0d6839236bc8!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535750959683" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;  
        case "wesleychapel":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=36" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="36"></a>';
            $review='<script type="text/javascript" src="//sites.yext.com/232721-reviews.js"></script>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d450065.02878003527!2d-82.7281136379224!3d28.2032703701754!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2bb12ab0c0001%3A0x53e335c19a3f3f87!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535750290512" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "westpalmbeach":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=25" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="25"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14258.470261320132!2d-80.058186!3d26.692711!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x739a79c50b58fdff!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1536068847030" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "carrollton":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=3" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="3"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3346.2053912778892!2d-96.91010104893064!3d32.99836007987929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c25ed1b487fa9%3A0x616f4a9729a1043a!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535749909516" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "dallas":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=1" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="1"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d428310.74462830543!2d-97.0751691474693!3d32.99909044103388!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c23ccfac43757%3A0xa2e1e56a8fc7923!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535749750830" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "flowermound":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=51" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="51"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d214176.3366960634!2d-97.01545076751694!3d32.990452228768234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c15efa166bc1d%3A0xfb4f9add1487884c!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535749215098" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "grapevine":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=50" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="50"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61602.790076435514!2d-96.9145276920487!3d32.989746901353726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c23ccfac43757%3A0xa2e1e56a8fc7923!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535749067908" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "houston":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=13" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="13"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d110809.39488369251!2d-95.55335511696455!3d29.78360944443481!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640c51188b00001%3A0x5b77ef503eed123c!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535748924143" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "missouricity":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=10" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="10"></a>';            
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d222076.8895018266!2d-95.77370883724983!3d29.57601737773259!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640d9ffc2bf25f3%3A0xdf93ff4615730414!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535747032678" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "plano":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=2" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="2"></a>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d428310.7446283055!2d-97.09259329746934!3d32.99909044103387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c23ccfac43757%3A0xa2e1e56a8fc7923!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535745706353" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "spring":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=12" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="12"></a>'; 
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d443493.3674556415!2d-95.76538990384525!3d29.725785715500997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86412a80323c0001%3A0xd608cc346d5d434b!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535745472372" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';           
            break;
        case "southlake":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=38" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="38"></a>';            
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d428310.7446283055!2d-97.09259329746934!3d32.99909044103387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c24b9251d5a67%3A0x63e16e9a1ed593e!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535740741797" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
        case "sugarland":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=11" type="text/javascript"></script>';
            $phone='<a data-yext-field="phone" data-yext-id="11"></a>';
            $review='<!––Insert review widget here-->';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d222077.879786013!2d-95.77302174511054!3d29.575567186839162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86411c66d1f73889%3A0x5d6c39597941d7c7!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535746848662" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            break;
            case "phoenix":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=21" type="text/javascript"></script>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d106330.14304067138!2d-111.96814204674975!3d33.610053135926925!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x872b0f6313bb696f%3A0xff06dd10c52546c2!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535737048784" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            $phone='<a data-yext-field="phone" data-yext-id="21"></a>';
            $review='<script type="text/javascript" src="//sites.yext.com/232562-reviews.js"></script>';
            break;
        case "scottsdale":
            echo '<script async src="https://knowledgetags.yextpages.net/embed?key=olfY2fScn135iO71Bibk5qfZYKbeKA7tv5SYQyarNyES8CicqFrpT_Lz_vCECnda&account_id=4116025801950195021&location_id=22" type="text/javascript"></script>';
            $map='<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d106382.77439094654!2d-111.97030899758386!3d33.56735976912813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x872b0f6313bb696f%3A0xff06dd10c52546c2!2sPool+Troopers!5e0!3m2!1sen!2sus!4v1535573925333" width="340" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>';
            $phone='<a data-yext-field="phone" data-yext-id="22"></a>';             
            $review='<script type="text/javascript" src="//sites.yext.com/232563-reviews.js"></script>';
            break;
    }
                ?>
                
    </head>
    <body>
        <?php 
            // This is the page construct
            if($showNav){
                if($deviceType=='computer'){
                    include_once 'views/header.php';
                }else{
                    include_once 'views/mobile_header.php';
                }
            }
            /*
                
                // if show nav === false then hide it

            */

            include_once $full_path;
                
                

            if($showFooter){
            if($deviceType=='computer'){
                include_once 'views/footer.php';
            }else{
                include_once 'views/mobile_footer.php';
            }
            }
        ?>
        <!-- <script src="https://www.geoplugin.net/javascript.gp" type="text/javascript"></script> -->
    <script>
    
//      $(function(){
//          var country = geoplugin_countryName();
//          var city = geoplugin_city();
//          $.cookie('location', city, { expires: 14 });
//      });
        
    (function ($) {
        
          $(document).ready(function(){         
            // hide .navbar first
            $(".secondarynav").hide();          
            // fade in .navbar
            // get User's location
            
            
            
            $(function () {
                $(window).scroll(function () {
                    // set distance user needs to scroll before we fadeIn navbar
                    if ($(this).scrollTop() > 100) {
                        $('.secondarynav').fadeIn();
                    } else {
                        $('.secondarynav').fadeOut();
                    }
                });
            });
        });
          }(jQuery));

      
    </script>
        <div id="ttdUniversalPixelTagd2815df5aa294e6dbacbf8135832ff58" style="display:none">
            <script src="https://js.adsrvr.org/up_loader.1.1.0.js" type="text/javascript"></script>
            <script type="text/javascript">
                (function(global) {
                    if (typeof TTDUniversalPixelApi === 'function') {
                        var universalPixelApi = new TTDUniversalPixelApi();
                        universalPixelApi.init("e0u3jh1", ["0qf861y"], "https://insight.adsrvr.org/track/up", "ttdUniversalPixelTagd2815df5aa294e6dbacbf8135832ff58");
                    }
                })(this);
            </script>
        </div>

        <script type="text/javascript">
          window._mfq = window._mfq || [];
          (function() {
            var mf = document.createElement("script");
            mf.type = "text/javascript"; mf.async = true;
            mf.src = "//cdn.mouseflow.com/projects/a4269626-9a60-417c-aed0-d9ab949be357.js";
            document.getElementsByTagName("head")[0].appendChild(mf);
          })();
        </script>
        <!-- Facebook Pixel Code -->

<script>


!function(f,b,e,v,n,t,s){
if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1920978881565848');
fbq('track', 'PageView');
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '783503785134781'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=783503785134781&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->

<noscript><img height="1" width="1" src="https://www.facebook.com/tr?id=1920978881565848&ev=PageView&noscript=1"/></noscript>
<!-- End Facebook Pixel Code -->
<img src="https://audience.bakemorepies.com/bmp_tracking/?name=PoolTroopers" alt="">
<script src="//scripts.iconnode.com/61320.js"></script>
<script type="text/javascript">

/* <![CDATA[ */

var google_conversion_id = 819069415;

var google_custom_params = window.google_tag_params;

var google_remarketing_only = true;

/* ]]> */

</script>

<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">

</script>

<noscript>

<div style="display:inline;">

<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/819069415/?guid=ON&amp;script=0"/>

</div>

</noscript>

<!-- begin olark code -->
<script type="text/javascript" async> ;(function(o,l,a,r,k,y){if(o.olark)return; r="script";y=l.createElement(r);r=l.getElementsByTagName(r)[0]; y.async=1;y.src="//"+a;r.parentNode.insertBefore(y,r); y=o.olark=function(){k.s.push(arguments);k.t.push(+new Date)}; y.extend=function(i,j){y("extend",i,j)}; y.identify=function(i){y("identify",k.i=i)}; y.configure=function(i,j){y("configure",i,j);k.c[i]=j}; k=y._={s:[],t:[+new Date],c:{},l:a}; })(window,document,"static.olark.com/jsclient/loader.js");
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('7762-165-10-5637');</script>
<!-- end olark code -->

    </body>
</html>