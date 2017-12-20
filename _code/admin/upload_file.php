<?php
// upload file form POST process (from uploadFile.php, modal window)
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

// increase memory size to allow heavy image manipulations (rotating large image and generating sized-down copies)
ini_set('memory_limit','160M');

// upload file form process
if(isset($_POST['uploadFileSubmit'])){
	$path = urldecode($_POST['path']);
	$replace = urldecode($_POST['replace']);
	/*print_r($_POST);
	echo '<br>--------------------------------<br>';
	print_r($_FILES);*/
	$upload_result = upload_file($path, $replace);
	header("location: manage_contents.php?upload_result=".urlencode($upload_result));
	exit;
}
