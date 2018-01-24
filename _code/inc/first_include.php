<?php
/******** TO DO ********
 * User admin: upload home page backgound-image
 * preferences admin (set all vars in user_cutom.php) 
 * _zoom.php: zoom in and out on target
 * audio plays ininterrupted on page change
 */
session_start();
// initialize site 
define("SITE", $_SERVER['HTTP_HOST'].'/');

// php root and error reporting, local vs. remote
if( strstr($_SERVER['HTTP_HOST'],'.local') ){ 	// local server
    define("ROOT", '/Users/seb/Sites/'.SITE);
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	define( 'DISPLAY_DEBUG', TRUE );
	define( 'LOG_ERRORS', TRUE );
}else{ 											// remote server
    define("ROOT", '/home/public/');
	ini_set('display_errors', 0);
	define("SEND_ERRORS_TO", 'sebdedie@gmail.com');
	define( 'DISPLAY_DEBUG', FALSE );
	define( 'LOG_ERRORS', TRUE );
}

// site style sheet
define("CSS", 'default');

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

// reference to me...       
define("AUTHOR_REF", 'sebdedie@gmail.com');


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
$types['supported_types'] = '/^\.(s?html?|txt|jpe?g?|png|gif|pdf|docx?|msword|odt|mp3|mpe?g|ogg|wav)$/i';
// TEXT
$types['text_types'] = '/^\.(s?html?|txt)$/i';
// html mode
$types['html_mode_types'] = '/^\.(s?html?)$/i';
// text mode
$types['text_mode_types'] = '/^\.txt$/i';
// audio
$types['audio_types'] = '/^\.(mp3|mpe?g|ogg|wav)$/i';
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
define("ALLOWED_TAGS", '<b><strong><br><u><i><a><h1><h2><h3><span><div>');

// error handler
require(ROOT.'_code/errors.php');
// require custom parameters set by user (username, css style, admin creds...)
require(ROOT.CONTENT.'user_custom.php');
define("FIRST_LANG", $first_lang);
define("SECOND_LANG", $second_lang);
// require common functions
require(ROOT.'_code/inc/functions.php');

