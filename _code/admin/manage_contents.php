<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

ini_set('upload_max_filesize', '20M');
ini_set('post_max_size', '21M');


$max_upload_size = ini_get('upload_max_filesize');
$message = '';
// back history, will be attached to the "back" button, using javascript:history.go(n)
$back_History = -1; // will be decremented each time we reload this page (if $item is not set but $_SESSION['item'] is)


// upload result (from admin/upload_file.php)
if(isset($_GET['upload_result'])){
	$message = urldecode($_GET['upload_result']);
}

// embed result (from admin/embed_media.php)
if(isset($_GET['embed_result'])){
	$message = urldecode($_GET['embed_result']);
	// disable XXS protection so that iframes in embeded media that was just edited do load
	header("X-XSS-Protection: 0");
}

// message GET (from delete_file.php for exemple)
if(isset($_GET['message'])){
	$message = urldecode($_GET['message']);
}

// item is the section content that should be shown in this page...
if(isset($_GET['item'])){
	$item = trim(urldecode($_GET['item']));
	if(empty($item)){
		header("location: manage_structure.php");
		exit;
	}
	$_SESSION['item'] = $item;
	
}elseif(isset($_SESSION['item'])){
	$item = $_SESSION['item'];
	--$back_History; // we've reloaded this page, decrement back history
	//echo $item; exit;
}

// if no item, go back to admin manage_structure page
if(!isset($item)){
	header("location: manage_structure.php");
	exit;	
}elseif( !is_dir(ROOT.$item) ){// security check, or if user deleted a section that is still in memory session
	if( isset($_SESSION['item']) ){
		unset($_SESSION['item']);
	}
	header("location: manage_structure.php");
	exit;
}

// echo $item; -> 'section1/section2'


$title = 'ADMIN : Site Content :';
$description = filename(str_replace(CONTENT, '', $item), 'decode');

$back_link = 'javascript:history.go('.$back_History.')';
$path = ROOT.$item;

//echo $path;

require(ROOT.'_code/inc/doctype.php');
?>

<link href="/_code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/admin-max-720px.css">

<div id="working">working...</div>

<!-- start container -->
<div id="adminContainer">
	
	<span class="title"><a href="<?php echo $back_link; ?>">&larr; back</a> | <?php echo $description; ?></span> 
	<a href="javascript:;" class="button showModal" rel="newFile?path=<?php echo urlencode(ROOT.$item); ?>" title="upload or create a file">+ new file</a> <a href="javascript:;" class="button showModal" rel="embedMedia?path=<?php echo urlencode(ROOT.$item); ?>" title="insert media content from other sites">&lt;embed media></a> <a href="?logout" class="button" style="float:right;">&times; logout</a> <a href="preferences.php" title="preferences" class="button preferences">preferences</a>
	<div class="clearBoth" id="message" style="margin:20px 0;">
		<?php if( isset($message) ){
			echo $message;
		}
		?>
	</div>
	
	<div id="contentContainer">
		<div id="ajaxTarget">
	<?php 
	$display = display_content_admin($path);
	echo $display;
	?>
		</div>
	</div>


<div class="clearBoth"></div>
</div><!-- end container -->




<?php require(ROOT.'_code/inc/adminFooter.php'); ?>
