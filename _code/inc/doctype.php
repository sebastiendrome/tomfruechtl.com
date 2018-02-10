<?php
/* 
required to be defined on top of each page individualy:
$title, $description
*/

/*
optional: 
$social_url, $social_image
*/
if( !isset($social_url) || empty($social_url) ){
	$social_url = PROTOCOL.SITE.substr($_SERVER['REQUEST_URI'],1); // http(s)://example.com/path/to/dir/
}
// social image (for meta property="og:image") is the background image in home page...
if( file_exists( ROOT.CONTENT.'bg.jpg') ){
	$home_image = 'bg.jpg';
}elseif( file_exists( ROOT.CONTENT.'bg.gif') ){
	$home_image = 'bg.gif';
}elseif( file_exists( ROOT.CONTENT.'bg.png') ){
	$home_image = 'bg.png';
}
if( isset($home_image) && !isset($social_image) ){
	$social_image = PROTOCOL.SITE.CONTENT.$home_image;
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
<link href="/_code/css/<?php echo $css; ?>/css.css?v=15" rel="stylesheet" type="text/css">

<style type="text/css">
/* limit container width depending on screen size and resulting SIZE var defined in first_include. */
#content{max-width:<?php echo $_POST['sizes'][substr(SIZE,1)]['width']; ?>px;}
</style>

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 980px)" href="/_code/css/<?php echo $css; ?>/max-980px.css?v=15">
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/<?php echo $css; ?>/max-720px.css?v=15">

</head>

<body<?php if(isset($page) && $page == 'home'){echo ' class="bg" style="background-image: url(/content/'.$home_image.'); background-repeat:no-repeat; background-size:100%;"';}?>>
