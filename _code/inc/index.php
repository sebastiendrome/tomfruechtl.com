<?php
if( !empty(SECTION) ){
	$title = $user.': '.filename(SECTION, 'decode').'.';
}else{
	$title = $user.' Artist Portfolio.';
}
if( !empty(CONTEXT_PATH) ){
	if(CONTEXT_PATH !== SECTION){
		$description = filename(str_replace(array(CONTENT, '/'), array('', ' '), CONTEXT_PATH), 'decode');
	}else{
		$description = filename(SECTION, 'decode');
	}
}else{
	$description = '';
}

require(ROOT.'_code/inc/doctype.php');

require(ROOT.'_code/inc/nav.php');

$display = display_content_array(getcwd()); 
?>


<!-- start content -->
<div id="content">
<?php
echo $display;
?>
</div><!-- end content -->

<div class="clearBoth"></div>

<?php require(ROOT.'_code/inc/js.php'); ?>

<?php require(ROOT.'_code/inc/footer.php'); ?>

