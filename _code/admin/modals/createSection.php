<?php
// create section modal
session_start();
require('../not_logged_in.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['parent']) && !empty($_GET['parent']) ){
	$parent = urldecode($_GET['parent']);
}else{
	$parent = '';
}
?>
<div class="modal" id="createSectionContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	<h3>↓Section name:</h3>
		english name, Deutsch Name<br>
	<form name="createSectionForm" action="" method="post">
		<input type="hidden" name="parent" value="<?php echo $parent; ?>">
		<input type="text" name="createSection" value="" style="width:400px;" autofocus>
		<?php /*if(empty($parent)){
			echo '<input type="checkbox" name="isParent" id="isParent" value="isParentSection"><label for="isParent">this section will contain sub-sections?</label><br>';
		}*/ ?>
		<button type="submit" class="createSection" name="createSectionSubmit">Create</button>
	</form>
</div>
<script type="text/javascript">
document.forms[0].createSection.focus();
</script>