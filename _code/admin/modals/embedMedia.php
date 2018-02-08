<?php
// embed media modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');

// path where new .emb file should be created. If 'edit' was clicked, the path is the path/to/file.emb
if(isset($_GET['path']) && !empty($_GET['path']) ){
    $path = urldecode($_GET['path']);
    $ext = file_extension($path);
    if($ext == '.emb'){
        $content = file_get_contents($path); // get file content if editing an already existing file.
    }
}else{
	exit;
}


?>
<div class="modal" id="embedMediaContainer">
<a class="closeBut">&times;</a>
	<!-- upload file start -->
	<div>
	<h3 class="first">Embed Media</h3>
    <p>from: Youtube, Vimeo, twitter, soundcloud, bandcamp, etc.</p>
    <p class="note">! Only paste code from trusted sources: malicious code could break your site and/or make it dangerous to use !</p>
	<form name="embedMediaForm" id="embedMediaForm" action="/_code/admin/embed_media.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<textarea name="embedMedia" style="width:100%; height:200px;" placeholder="paste embed code here"><?php if(isset($content)){echo $content;} ?></textarea>
		<a class="button hideModal" style="margin-left:0;">Cancel</a> <button type="submit" name="embedMediaSubmit" style="float:right; margin-right:0;" onclick="this.style.opacity=0; this.style.cursor='default'; var i=document.getElementById('uf'); i.className += ' visible';"> Save </button><img src="/_code/images/progress.gif" id="uf" class="buttonProcess">
	</form>
	</div>
	<!-- upload file end -->


	

	
</div>

