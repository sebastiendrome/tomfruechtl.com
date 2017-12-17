<?php
// upload file form POST process (from uploadFile.php, modal window)
session_start();
require('not_logged_in.php');

require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/admin_functions.php');

// upload file form process
if(isset($_POST['uploadFileSubmit'])){
	$path = urldecode($_POST['path']);
	$replace = urldecode($_POST['replace']);
	$n = urldecode($_POST['n']);
	/*print_r($_POST);
	echo '<br>--------------------------------<br>';
	print_r($_FILES);*/
	$upload_result = upload_file($path, $replace);
	header("location: manage_contents.php?upload_result=".urlencode($upload_result));
}