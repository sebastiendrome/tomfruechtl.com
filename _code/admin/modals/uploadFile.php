<?php
// upload file modal
session_start();
require('../not_logged_in.php');
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['path']) && !empty($_GET['path']) ){
	$path = urldecode($_GET['path']);
	$item = str_replace(ROOT, '', $path);
}else{
	exit;
}

// uploaded file should replace a previous one?
if(isset($_GET['replace']) && !empty($_GET['replace'])){
	$replace = urldecode($_GET['replace']);
}else{
	$replace = '';
}

?>
<div class="modal" id="uploadFileContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	<h3>↓ Upload File:</h3>
	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="/_code/admin/upload_file.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="replace" value="<?php echo $replace; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="9999999">
		<input type="file" name="file"> 
		<button type="submit" name="uploadFileSubmit" onclick="this.style.opacity=0; this.style.cursor='default'; var i=document.getElementById('uf'); i.className += ' visible';">Upload</button><img src="/_code/images/progress.gif" id="uf" class="buttonProcess">
	</form>
</div>

