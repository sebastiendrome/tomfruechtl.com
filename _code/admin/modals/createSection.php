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
	<h3>↓Section name:</h3>
		<?php echo FIRST_LANG; ?> name, <?php echo SECOND_LANG; ?> name<br>
	<form name="createSectionForm" action="" method="post">
		<input type="hidden" name="parent" value="<?php echo $parent; ?>">
		<input type="text" name="createSection" maxlength="50" value="" style="width:400px;" autofocus>
		<button type="submit" class="createSection" name="createSectionSubmit">Create</button>
	</form>
</div>
<script type="text/javascript">
document.forms[0].createSection.focus();
</script>