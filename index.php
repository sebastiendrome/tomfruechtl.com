<?php
require('_code/inc/first_include.php');

$title = $user.' Artist Portfolio';
$description = $user;
$page = 'home';

// social image (for meta property="og:image") is the background image in home page...
if( file_exists( ROOT.'_code/images/home/bg.jpg') ){
	$home_image = 'bg.jpg';
}elseif( file_exists( ROOT.'_code/images/home/bg.gif') ){
	$home_image = 'bg.gif';
}elseif( file_exists( ROOT.'_code/images/home/bg.png') ){
	$home_image = 'bg.png';
}
if( isset($home_image) ){
	$social_image = 'http://'.SITE.'_code/images/home/'.$home_image;
}


require(ROOT.'_code/inc/doctype.php');

require(ROOT.'_code/inc/nav.php');

?>



    
    
<!-- start container -->
<div id="container">

<!-- start content -->
<div id="content">
&nbsp;
</div><!-- end content -->

<div class="clearBoth"></div>
</div><!-- end container -->

<?php require(ROOT.'_code/inc/footer.php'); ?>

