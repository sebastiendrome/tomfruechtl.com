<?php
/* 
required to be defined on top of each page:
$title, $description
optional: 
$social_image, $social_url
*/
if( !isset($social_url) || empty($social_url) ){
	$social_url = 'http://'.SITE.substr($_SERVER['REQUEST_URI'],1);
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="<?php echo $description; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:url"           content="<?php echo $social_url; ?>">
<meta property="og:type"          content="website">
<meta property="og:title"         content="<?php echo $title; ?>">
<meta property="og:description"   content="<?php echo $description; ?>">
<?php 
if( isset($social_image) && !empty($social_image) ){ ?>
<meta property="og:image"         content="<?php echo $social_image; ?>">
<?php 
}
?>
<title><?php echo $title; ?></title>


<link href="/_code/css/css.css?v=7" rel="stylesheet" type="text/css">

<style type="text/css">
/* limit container width depending on screen size and resulting SIZE var defined in first_include. */
#content{max-width:<?php echo $_POST['sizes'][substr(SIZE,1)]['width']; ?>px;}

</style>

<link rel="stylesheet" media="(max-width: 980px)" href="/_code/css/max-980px.css">
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/max-720px.css">

</head>

<body<?php if(isset($page) && $page == 'home'){echo ' style="background: url(/_code/images/home/'.$home_image.') no-repeat; background-size:100%;"';}?>>
