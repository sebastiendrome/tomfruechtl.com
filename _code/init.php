<?php
// initialize site 
define("SITE", $_SERVER['HTTP_HOST'].'/');

// php root and error reporting, local vs. remote
if(strstr($_SERVER['HTTP_HOST'],'.local')){ 	// local server
    define("ROOT", '/Users/seb/Sites/'.SITE);
	ini_set( 'error_reporting', E_ALL );
	ini_set('display_errors', 1);
}else{ 											// remote server
    define("ROOT", '/home/public/');
	ini_set('display_errors', 0);
}

// check if mod_rewrite is working (as per .htaccess file that would have set the HTTP_MOD_REWRITE server environment var)
if( array_key_exists('HTTP_MOD_REWRITE', $_SERVER) ){
	$mod_rewrite = true;
}else{
	$mod_rewrite = false;
}

// define content directory (which will contain menu.txt and all user sections/files, although it is hidden from urls via mod_rewrite)
if($mod_rewrite){
	// if mod_rewrite works, we'll locate all user files in "content" directory
	define("CONTENT", 'content/');
}else{
	// if not, we'll have to use site root directory
	define("CONTENT", '');
}

// define menu file
define("MENU_FILE", ROOT.CONTENT.'menu.txt');

// attempt to create content directory, and chmod it to full permissions
$dirmode = 0777;
if( !is_dir($_SERVER['DOCUMENT_ROOT'].'/'.CONTENT) ){
	if( mkdir($_SERVER['DOCUMENT_ROOT'].'/'.CONTENT, $dirmode) ){
		echo '<p class="success">content directory created.</p>';
	}else{
		echo '<p class="error">Could not create content directory.</p>';
	}
}elseif( chmod($_SERVER['DOCUMENT_ROOT'].'/'.CONTENT, $dirmode) ){
		echo '<p class="success">yes, chmod.</p>';
}else{
	echo '<p class="error">no chmod.</p>';
}

// attempt to create menu.txt file
if( !file_exists(MENU_FILE) ){
	if( $fp = fopen(MENU_FILE, 'w') ){
		echo '<p class="success">menu.txt opened.</p>';
	}else{
		echo '<p class="error">could not create menu.txt.</p>';
	}
}

