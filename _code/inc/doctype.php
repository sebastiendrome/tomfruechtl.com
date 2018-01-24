<?php
/* 
required to be defined on top of each page:
$title, $description
optional: 
$social_image, $social_url
*/
if( !isset($social_url) || empty($social_url) ){
	$social_url = 'http://'.SITE.substr($_SERVER['REQUEST_URI'],1); // http://example.com/path/to/dir/
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

<?php require(ROOT.'_code/custom.css.php'); ?>
<link href="/_code/css/<?php echo $css; ?>/css.css?v=12" rel="stylesheet" type="text/css">

<style type="text/css">
/* limit container width depending on screen size and resulting SIZE var defined in first_include. */
#content{max-width:<?php echo $_POST['sizes'][substr(SIZE,1)]['width']; ?>px;}
</style>

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 980px)" href="/_code/css/<?php echo $css; ?>/max-980px.css">
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/<?php echo $css; ?>/max-720px.css">

</head>

<body<?php if(isset($page) && $page == 'home'){echo ' style="background-image: url(/content/'.$home_image.'); background-repeat:no-repeat; background-size:100%;"';}?>>
