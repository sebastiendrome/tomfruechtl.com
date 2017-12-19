<?php
// initialize site 
//echo $_SERVER['DOCUMENT_ROOT']; exit;

if( array_key_exists('HTTP_MOD_REWRITE', $_SERVER) ){
	echo 'yes, HTTP_MOD_REWRITE ON!';
}else{
	echo 'NO!.....';
}

/*
$dirmode = 0777;
if( !is_dir($_SERVER['DOCUMENT_ROOT'].'/content') ){
	if( mkdir($_SERVER['DOCUMENT_ROOT'].'/content', $dirmode) ){
		echo '<p class="success">content directory created!</p>';
	}else{
		echo '<p class="error">Could not create content directory!</p>';
	}
}elseif( chmod($_SERVER['DOCUMENT_ROOT'].'/content', $dirmode) ){
		echo '<p class="success">yes chmod!</p>';
}else{
	echo '<p class="error">no chmod!</p>';
}

if( !file_exists($_SERVER['DOCUMENT_ROOT'].'/content/menu.txt') ){
	if( $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/content/menu.txt', 'w') ){
		echo '<p class="success">menu.txt opened!</p>';
	}
}
*/
