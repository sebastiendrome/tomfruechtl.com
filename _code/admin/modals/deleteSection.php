<?php
// delete section modal
session_start();
require('../not_logged_in.php');

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
	Are you sure you want to delete this section:
	<h3><?php echo urldecode($_GET['deleteSection']); ?></h3>
	<form name="deleteSectionForm" action="" method="post">
		<input type="hidden" name="parent" value="<?php echo $parent; ?>">
		<input type="hidden" name="deleteSection" value="<?php echo $deleteSection; ?>">
		yes,<button type="submit" class="deleteSection cancel" name="deleteSectionSubmit">Delete</button>
	</form>
</div>
