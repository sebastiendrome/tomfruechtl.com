<?php
session_start();
require('not_logged_in.php');
require('../inc/first_include.php');
require('admin_functions.php');

$title = 'ADMIN : Site Structure :';
$description = '';
$page = 'admin';

// create new sub-section
if(isset($_POST['createSectionSubmit'])){
	if(!empty($_POST['parent'])){
		$parent = urldecode($_POST['parent']);
	}else{
		$parent = '';
	}
	if(!empty($_POST['createSection'])){
		$createSection = urldecode($_POST['createSection']);
		$createSection = validate_section_name($createSection);
	}
		
	$message = create_section($parent, $createSection);
}

// DELETE section
if(isset($_POST['deleteSectionSubmit'])){
	if(!empty($_POST['parent'])){
		$parent = urldecode($_POST['parent']);
		//$parent = filename($parent, 'encode');
	}else{
		$parent = '';
	}
	if(!empty($_POST['deleteSection'])){
		$deleteSection = urldecode($_POST['deleteSection']);
		//$deleteSection = filename($deleteSection, 'encode');
	}
	
	$message = delete_section($parent, $deleteSection);
}

// message GET (from delete_file.php for exemple)
if(isset($_GET['message'])){
	$message = urldecode($_GET['message']);
}


$menu_array = menu_file_to_array();
$site_structure = site_structure($menu_array);

require(ROOT.'_code/inc/doctype.php');
?>

<link href="/_code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<div id="working">working...</div>

<!-- start container -->
<div id="adminContainer">
	
	<span class="title">ADMIN - Site Structure</span> <a href="javascript:;" class="button showModal" rel="createSection">[+]create new section</a> <a href="?logout" class="button" style="float:right;">-> logout</a>
	<br><span id="message">
		<?php if( isset($message) ){
			echo $message;
		}
		?>
	</span>
	<br>
	&nbsp;(english, Deustch)
	
	<div id="structureContainer">
		<div id="ajaxTarget">
		<?php if(isset($result) && !empty($result)){
			echo $result;
		}
		?>
	<?php 
	//print_r($menu_array);
	echo $site_structure; 
	?>
		</div>
	</div>


<div class="clearBoth"></div>
</div><!-- end container -->




<?php require(ROOT.'_code/inc/adminFooter.php'); ?>