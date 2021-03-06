<?php
// delete section modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['parent']) && !empty($_GET['parent']) && $_GET['parent'] != 'undefined' ){
	$parent = urldecode($_GET['parent']);
}else{
	$parent = '';
}
if(isset($_GET['deleteSection']) && !empty($_GET['deleteSection']) ){
	$deleteSection = urldecode($_GET['deleteSection']);
}else{
	echo '<p class="error">ERROR: missing section data</p>';
	exit;
}
?>
<div class="modal" id="deleteSectionContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	<h3 class="first">Are you sure you want to delete this section:</h3>
	<p><?php echo urldecode($_GET['deleteSection']); ?></p>
	<form name="deleteSectionForm" action="" method="post">
		<input type="hidden" name="parent" value="<?php echo $parent; ?>">
		<input type="hidden" name="deleteSection" value="<?php echo $deleteSection; ?>">
		<a class="button hideModal" style="margin-left:0;">Cancel</a> <button type="submit" class="deleteSection cancel" name="deleteSectionSubmit" style="float:right;">Delete</button>
	</form>
</div>
