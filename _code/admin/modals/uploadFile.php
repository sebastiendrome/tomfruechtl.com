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
}else{
	$replace = '';
}

$supported_types = $_POST['types']['supported_types'];
$supported_types = str_replace(array("/^\.(", ")$/i", 's?', 'e?', '?'), '', $supported_types);
$split_types = explode('|', $supported_types);


?>
<div class="modal" id="uploadFileContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	Supported File Types:<br>
		<?php echo implode(',', $split_types); ?>
	<p>Maximum Upload Size: <?php echo $max_upload_size; ?></p>
	
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
	
	<div class="tip">
		<a href="javascript:;" class="tipTitle">Tips for adding Texts</a>
		<ol>
		If you want your visitors to download PDF or DOC files, it is best to upload them here. 
		However, if you want to provide reading material directly on your site pages, the best is to click on the <a class="button" href="editText.php?item=<?php echo urlencode($path); ?>">[+]new text</a> button at the top of this page. This will open a text editor in which you can paste text from whatever source/format. The text will be re-formatted to look good on the site, with no download necessary for your visitors.
		</ol>
	</div>
	
	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="/_code/admin/upload_file.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="replace" value="<?php echo $replace; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="9999999">
		<input type="file" name="file"> 
		<button type="submit" name="uploadFileSubmit" onclick="this.style.opacity=0; this.style.cursor='default'; var i=document.getElementById('uf'); i.className += ' visible';">Upload</button><img src="/_code/images/progress.gif" id="uf" class="buttonProcess">
	</form>
</div>

