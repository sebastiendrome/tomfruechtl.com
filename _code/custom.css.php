<?php
// import google font if necessary
if($site_font == '13px "Open Sans", sans-serif'){
	echo '
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">';
}elseif($site_font == '14px Arvo, serif'){
	echo '
	<link href="https://fonts.googleapis.com/css?family=Arvo:400,400i,700" rel="stylesheet">';
}elseif($site_font == '16px Vollkorn, serif'){
	echo '
	<link href="https://fonts.googleapis.com/css?family=Vollkorn:400,400i,700" rel="stylesheet">';
}elseif($site_font == '14px "PT Sans", sans-serif'){
	echo '
	<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700" rel="stylesheet">';
}elseif($site_font == '17px "Archivo Narrow", serif'){
	echo '
	<link href="https://fonts.googleapis.com/css?family=Archivo+Narrow:400,400i,700" rel="stylesheet">';
}elseif($site_font == '16px "Old Standard TT", serif'){
	echo '
	<link href="https://fonts.googleapis.com/css?family=Old+Standard+TT:400,400i,700" rel="stylesheet">';
}elseif($site_font == '17px "EB Garamond", serif'){
	echo '
	<link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,700" rel="stylesheet">';
}
// output custom css (site bg color, font, and img/div.txt/div.html borders)
echo '
<style type="text/css">
@charset "UTF-8";
/***** user defined styles *****/

/* site bg_color */
body, #nav, .backTitle, div.txt, div.html{background-color: #'.$site_bg_color.';}

/* site_font */
body, td, th, select, input, button, textarea{
	font:'.$site_font.';
	color:#'.$font_color.';
	line-height:1.4; /* do not add pixels or ems! this is relative to font size */ 
}

/* borders */
.divItem img, .divItem div.txt, divItem.div.html{border:'.$borders.';}
';
if($borders != 'none'){
	if(strstr($borders, 'FFFFFF') && $site_bg_color != 'FFFFFF'){
		echo '.divItem div.txt, divItem.div.html{padding:20px;}'.PHP_EOL;
	}elseif( !strstr($borders, 'FFFFFF') ){
		echo '.divItem div.txt, divItem.div.html{padding:20px;}'.PHP_EOL;
	}
}else{
	echo '.divItem div.txt, divItem.div.html{border-bottom:1px solid #ddd;}'.PHP_EOL;
}
echo '
</style>';

?>