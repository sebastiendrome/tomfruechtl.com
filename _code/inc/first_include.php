<?php
/******** TO DO ********
 * User admin: upload home page backgound-image fix for jpg vs gif vs png
 * max-length validation on all user inputs 
 * audio to play ininterrupted on page change?
 */
session_start();

// initialize site 
define("SITE", $_SERVER['HTTP_HOST'].'/');

// Protocol: http vs https
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define("PROTOCOL", $protocol);

// php root and error reporting, local vs. remote
if( strstr(SITE,'.local') ){ 					// local server
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
// error handler
require(ROOT.'_code/errors.php');

// content directory (which contains all user files)
define("CONTENT", 'content/');

// menu file (used as site structure)
define("MENU_FILE", ROOT.CONTENT.'menu.txt');
if(!file_exists(MENU_FILE)){
	if( !$fp = fopen(MENU_FILE, 'w') ){
		echo '<p style="color:red;">Error: Could not create menu file!</p>';
	}
}

// reference paths
define("CONTEXT_PATH", str_replace( ROOT, '', getcwd() ) );
define("SECTION", urldecode( basename(CONTEXT_PATH) ) );

// reference to site author...
define("AUTHOR_REF", 'sebdedie@gmail.com');

// require custom parameters set by user (first_lang and second_lang css style, username, admin creds...)
require(ROOT.CONTENT.'user_custom.php');

// set allowed tags for strip_tags function, used for validating user txt input
define("ALLOWED_TAGS", '<b><strong><br><u><i><a><h1><h2><h3><span><div>');

// language dependent constants (for 'more' and 'back' links)
define("FIRST_LANG", $first_lang);
define("SECOND_LANG", $second_lang);

// LANGUAGES
$languages = array();
$languages['english'] = array('more'=>'more', 'back'=>'back');
$languages['français'] = array('more'=>'plus', 'back'=>'retour');
$languages['Deutsch'] = array('more'=>'mehr', 'back'=>'zurück');

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

if(LANG == 'en'){ // en is $first_lang
	if( !array_key_exists($first_lang, $languages) ){ // if choosen first lang is not part of the lang array
		$default_lang = 'english';
	}else{
		$default_lang = $first_lang;
	}
	define("MORE", $languages[$default_lang]['more']);
	define("BACK", $languages[$default_lang]['back']);
	
}elseif(LANG == 'de'){ // de is $second_lang
	if( !array_key_exists($second_lang, $languages) ){ // if choosen first lang is not part of the lang array
		$other_lang = 'english';
	}else{
		$other_lang = $second_lang;
	}
	define("MORE", $languages[$other_lang]['more']);
	define("BACK", $languages[$other_lang]['back']);
}

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
// register $types as a $_POST var, so it is accessible within functions scope (like a contant).
$_POST['types'] = $types;

// FILE SIZES:
$sizes = array();
$sizes['L'] = array("width"=>800, "height"=>667);
$sizes['M'] = array("width"=>650, "height"=>542);
$sizes['S'] = array("width"=>300, "height"=>250);
// register $sizes as a $_POST var, so it is accessible within functions scope.
$_POST['sizes'] = $sizes;

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

// require common functions
require(ROOT.'_code/inc/functions.php');

