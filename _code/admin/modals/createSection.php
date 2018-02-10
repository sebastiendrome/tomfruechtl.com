<?php
// create section modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['parent']) && !empty($_GET['parent']) ){
	$parent = urldecode($_GET['parent']);
}else{
	$parent = '';
}
?>
<div class="modal" id="createSectionContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	<h3 class="first">↓Section name:</h3>
	<form name="createSectionForm" action="" method="post">
		In both languages, separated with a coma.
		<input type="hidden" name="parent" value="<?php echo $parent; ?>">
		<input type="text" name="createSection" maxlength="100" value="" style="width:100%;" placeholder="<?php echo FIRST_LANG; ?>, <?php echo SECOND_LANG; ?>" autofocus>
		<p><a class="button hideModal" style="margin-left:0;">Cancel</a> <button type="submit"  name="createSectionSubmit" style="float:right; margin-right:0;">Create</button></p>
	</form>
</div>
<script type="text/javascript">
document.forms[0].createSection.focus();
</script>