<?php
require('_code/inc/first_include.php');

$title = $user.' Artist Portfolio';
$description = $user;

require(ROOT.'_code/inc/doctype.php');

if(isset($_GET['img'])){
    $get_img = urldecode($_GET['img']);
    $img = preg_replace('/\/_(S|M|L)\//', '/_XL/', $get_img);
	list($w, $h) = getimagesize(ROOT.$img);
}

if(LANG == 'en'){
	$back = 'back';
}elseif(LANG == 'de'){
	$back = 'zurück';
}

//$back = $_SERVER['HTTP_REFERER'];
?>

<style>
img#inOut{display:block; margin:auto; max-width:<?php echo $w; ?>px;}
img.out{width:100%; cursor:zoom-in;}
img.in{width:auto; cursor:zoom-out;}
</style>

<!-- start nav -->
<div class="backTitle zoomPage" style="left:0; background-color:rgba(255, 255, 255, .8);">
    <ul>
        <li><a href="javascript:history.go(-1);">&larr; <?php echo $back; ?></a></li>
    </ul>
</div><!-- end nav -->
    
    
<div id="bigImgContainer">
<img src="<?php echo $img; ?>" id="inOut" class="out">
</div>



<?php require(ROOT.'_code/inc/footer.php'); ?>

<script type="text/javascript">

$(document).ready(function(){

    $('body').on('click', 'img#inOut', function(){
        if( $(this).hasClass("in") ){ // zoom out
            $(this).removeClass("in").addClass("out");
        }else{
            $(this).removeClass("out").addClass("in");// zoom in
        }
        //alert($(this).attr("style"));
    });
});

</script>

