<?php
// delete file modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['file']) && !empty($_GET['file']) ){
	$file = urldecode($_GET['file']);
	$ext = file_extension($file);
	$file_url = str_replace(ROOT, '', $file);
	
	// various ways to display file depending on extension
	if( preg_match($_POST['types']['resizable_types'], $ext) ){
		$display_file = '<img src="/'.$file_url.'?rand='.rand(111,999).'" style="border:1px solid #000;">';
	}else{
		$display_file = '<img src="/_code/images/'.substr($ext,1).'.png" style="border:1px solid #000;">';
	}
	
}else{
	exit;
}

?>
<div class="modal" id="deleteFileContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	<p><strong>Are you sure you want to delete this file:</strong></p>
	<?php echo $display_file; ?><br>
	<p><?php echo $file_url; ?></p>
	<form name="deleteFileForm" action="/_code/admin/delete_file.php" method="post">
		<input type="hidden" name="deleteFile" value="<?php echo urlencode($file); ?>">
	Yes, <button type="submit" name="deleteFileSubmit" class="cancel">Delete</button>
</form>	
</div>
