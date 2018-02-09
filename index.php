<?php
require('_code/inc/first_include.php');

if(empty($seo_title)){
    $title = $user.' Artist Portfolio';
}else{
    $title = $seo_title;
}
if(empty($seo_description)){
    $description = $user;
}else{
    $description = $seo_description;
}

$page = 'home';

require(ROOT.'_code/inc/doctype.php');

require(ROOT.'_code/inc/nav.php');

?>


<!-- start content -->
<div id="content">
&nbsp;
</div><!-- end content -->

<div class="clearBoth"></div>

<?php require(ROOT.'_code/inc/js.php'); ?>

<?php require(ROOT.'_code/inc/footer.php'); ?>

