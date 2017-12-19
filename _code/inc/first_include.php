<?php
// site and user name
define("USER_NAME", 'Tom Früchtl');

// php root, local vs. remote
if(strstr($_SERVER['HTTP_HOST'],'.local')){ // local server
	define("SITE", 'tomfruechtl.com/');
    define("ROOT", '/Users/seb/Sites/'.SITE);
	ini_set( 'error_reporting', E_ALL );
	ini_set('display_errors', 1);
}else{ // remote server
	define("SITE", 'tomfruechtl.com/');
    define("ROOT", '/home/public/');
	ini_set('display_errors', 0);
}

// reference paths
define("FULL_PATH", getcwd());
define("CONTEXT_PATH", str_replace(ROOT, '', FULL_PATH) );
define("SECTION", urldecode( basename(FULL_PATH) ) );

// url link
define("URL_LINK", str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']) );

// content directory (which contains menu.txt and all public sections/files, although it is hidden from urls via mod_rewrite)
define("CONTENT", 'content/');
// menu file
define("MENU_FILE", ROOT.CONTENT.'menu.txt');
if(!file_exists(MENU_FILE)){
	if( !$fp = fopen(MENU_FILE, 'w') ){
		echo 'could not create menu file!';
	}
}


// language (en/de) - set by GET query, stored as cookie
if(isset($_GET['lang']) && ($_GET['lang'] == 'en' || $_GET['lang'] == 'de')){
    if(isset($_COOKIE['lang'])){
        setcookie ("lang", "", time() - 3600); // delete cookie!
    }
    $_COOKIE['lang'] = $_GET['lang']; // set global var
    setcookie("lang", $_GET['lang'], time()+60*60*24*30, '/'); // set cookie
}
if(isset($_COOKIE['lang']) && ($_COOKIE['lang'] == 'en' || $_COOKIE['lang'] == 'de') ){
    $lang = $_COOKIE['lang'];
}else{
    $lang = "en"; // default
}
define("LANG", $lang);
//echo 'lang: '.$_COOKIE['lang'].'<br>';

// image size 
$size = "_M";
if(isset($_COOKIE['wW'])){
    if($_COOKIE['wW'] > 1370 ){
        $size = "_L";
    }elseif($_COOKIE['wW'] < 340){
		$size = "_S";
	}
}
define("SIZE", $size);


// FILE TYPES
$types = array();
// ALL
$types['supported_types'] = '/^\.(s?html?|txt|rtf|jpe?g?|png|gif|pdf)$/i';
// TEXT
$types['text_types'] = '/^\.(s?html?|txt|rtf)$/i';
// html mode
$types['html_mode_types'] = '/^\.(s?html?)$/i';
// text mode
$types['text_mode_types'] = '/^\.(txt|rtf)$/i';
// images
$types['image_types'] = '/^\.(jpe?g?|png|gif|pdf)$/i';
// re-sizable
$types['resizable_types'] = '/^\.(jpe?g?|png|gif)$/i';
// register $types as a $_POST var, so it is accessible within all functions...
$_POST['types'] = $types;

// FILE SIZES:
$sizes = array();
$sizes['L'] = array("width"=>800, "height"=>667);
$sizes['M'] = array("width"=>650, "height"=>542);
$sizes['S'] = array("width"=>300, "height"=>250);
// register $sizes as a $_POST var, so it is accessible within all functions...
$_POST['sizes'] = $sizes;

// set allowed tags for strip_tags function, used for validating user txt input
define("ALLOWED_TAGS", '<b><strong><br><u><i><a>');

// require common functions
require(ROOT.'_code/inc/functions.php');
?>