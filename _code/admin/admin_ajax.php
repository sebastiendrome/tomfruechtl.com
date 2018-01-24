<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

/*********************** manage STRUCTURE ajax calls ***************************************/
// update name
if( isset($_GET['updateName']) ){
	$oldName = trim(urldecode($_GET['oldName']));
	$newName = trim(urldecode($_GET['newName']));
	$parent = urldecode($_GET['parent']); // if $parent is NOT empty or 'undefined', then we're renaming a sub-section
	$adminPage = urldecode($_GET['adminPage']);
	
	$result = update_name($oldName, $newName, $parent, $adminPage);
}

// update position
if(isset($_GET['updatePosition'])){
	$item = urldecode($_GET['item']);
	$oldPosition = urldecode($_GET['oldPosition']);
	$newPosition = urldecode($_GET['newPosition']);
	$parent = urldecode($_GET['parent']); // can be 'undefined', be can also be parent1/parent2
	$adminPage = urldecode($_GET['adminPage']);
	
	$result = update_position($item, $oldPosition, $newPosition, $parent, $adminPage);
}

// show or hide
if(isset($_GET['showHide'])){
	$item = urldecode($_GET['item']);
	$parent = urldecode($_GET['parent']);
	
	$result = show_hide($item, $parent);
}




/*********************** manage CONTENT ajax calls ***************************************/

// save text description (of files in manage content)
if(isset($_GET['saveTextDescription'])){
	$file = urldecode($_GET['file']); // full path to file
	$en_txt = urldecode($_GET['enText']);
	$de_txt = urldecode($_GET['deText']);
	$result = save_text_description($file, $en_txt, $de_txt);
}
/*
// save text description (of files in manage content)
if(isset($_POST['saveTextEditor'])){
	$file = urldecode($_POST['item']); // full path to file
	$content = urldecode($_POST['content']);
	$result = save_text_editor($file, $content);
}
*/

if( isset($result) ){
	echo $result;
}
