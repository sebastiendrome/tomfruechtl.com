<?php
// upload file modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');

$max_upload_size = ini_get('upload_max_filesize');

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
	$replace_filename = basename($replace);
}else{
	$replace = $replace_filename = '';
}

$supported_types = str_replace(array("/^\.(", ")$/i", 's?', 'e?', '?'), '', $_POST['types']['supported_types']);
$split_types = explode('|', $supported_types);


?>
<div class="modal" id="newFileContainer" style="color:#000;">
	<a href="javascript:;" class="closeBut">&times;</a>
	
	<!-- upload file start -->
	<div>
	<h3 class="first">Upload file</h3>
	Maximum Upload Size: <?php echo $max_upload_size; ?><br>
	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="/_code/admin/upload_file.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="replace" value="<?php echo $replace; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="9999999">
		<input type="file" name="file" id="fileUpload" style="width:70%;"> 
		<button type="submit" name="uploadFileSubmit" onclick="this.style.opacity=0; this.style.cursor='default'; var i=document.getElementById('uf'); i.className += ' visible';">Upload</button><img src="/_code/images/progress.gif" id="uf" class="buttonProcess">
	</form>
	<?php 
// only show the create file option if the modal is not opened from the replace button
if(empty($replace)){
?>
	<p>PDF and DOC files (i.e. files created with microsoft Word, Adobe Reader or similar apps) will not be directly readable on the site but only available for download. To format text content and make it readable on the site, use the "Create file" option below.
	</p>
<?php } ?>
	</div>
	<!-- upload file end -->
	
<?php 
// only show the create file option if the modal is not opened from the replace button
if(empty($replace)){
?>
	
	<!-- create file start -->
	<div style="border-top:1px solid #ccc; margin-top:20px; padding:5px 0;" id="createFileDiv">
	<h3>Create file</h3>
	<form name="createTextForm" action="/_code/admin/editText.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		File name:
		<input type="text" name="fileName" value="" style="width:55%; padding:5px 0;" placeholder="temp-name"> 
		<button type="submit" name="createText">Create</button>
	</form>
	<p></p>
	</div>
	<!-- create file end -->

<?php
}
?>
	
	<!-- tips start -->
	<div style="border-top:1px solid #ccc; margin-top:20px;">
	<p>Tips:</p>
		<div class="tip">
			<a href="javascript:;" class="tipTitle">Supported File Types</a>
			<ol><?php echo implode(', ', $split_types); ?>.<br>
			These file types will be natively displayed in the site pages, except for pdf, docx, msword and odt.</ol>
		</div>
		<div class="tip">
			<a href="javascript:;" class="tipTitle">How to best optimize Images for the web, using Photoshop</a>
			<ol>
			<li>Open your file in Photoshop</li>
			<li>Under menu, select: Image > Image Size...</li>
			<li>Adjust the Width and Height, in pixels, so that neither exceeds 3000px</li>
			<li>Check "Constrain Proportions" and "Resample Image"</li>
			<li>Click OK.</li>
			<li>Under menu, select: File > Save for Web & Devices...</li>
			<li>Select JPEG format, Very High quality, check Optimized</li>
			<li>Click Save.</li>
			</ol>
		</div>
		
	</div>
	<!-- tips end -->

	<a class="button hideModal" style="margin-left:0;">Cancel</a>
	
</div>

<script type="text/javascript">
$("#fileUpload").on("change", function(){
	var upVal = this.value;
	if(upVal != ''){
		$("#createFileDiv").css('opacity', .3);
	}
});
</script>