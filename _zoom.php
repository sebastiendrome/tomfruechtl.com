<?php
require('_code/inc/first_include.php');

$title = $user.' Artist Portfolio';
$description = $user;

require(ROOT.'_code/inc/doctype.php');

if(isset($_GET['img'])){
    $get_img = urldecode($_GET['img']);
	// load different size depending on screen width
	if(isset($_COOKIE['wW']) && $_COOKIE['wW'] < 800){
		$dir_size = '_L';
	}else{
		$dir_size = '_XL';
	}
    $img = preg_replace('/\/_(S|M|L)\//', '/'.$dir_size.'/', $get_img);
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
body, html{height:100%;}
img#inOut{display:block; margin:auto; max-width:<?php echo $w; ?>px;}
img.out{width:100%; cursor:zoom-in;}
img.in{width:auto; cursor:zoom-out;}
</style>

<!-- start nav -->
<div class="backTitle zoomPage">
    <ul>
        <li><a href="javascript:window.history.back();">&larr; <?php echo $back; ?></a></li>
    </ul>
</div><!-- end nav -->
    
    
<div id="bigImgContainer">
<img src="<?php echo $img; ?>" id="inOut" class="out">
</div>



<!-- jQuery -->
<script type="text/javascript" src="/_code/js/jquery-3.2.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/_code/js/js.js?v=4" charset="utf-8"></script>

<script type="text/javascript">

function mousePosImage(mouseX, mouseY, ratio){
	
	var newPosX = Math.round(mouseX*ratio);
	var newPosY = Math.round(mouseY*ratio);
	
	//alert(mouseX+' x '+ratio);
	
	$('html,body').animate({ scrollLeft:newPosX, scrollTop:newPosY }, 0);
}

$(document).ready(function(){
	
	var imgW = <?php echo $w; ?>;
	var imgH = <?php echo $h; ?>;
	var relImgW = $('#inOut').outerWidth(true);
	var relImgH = $('#inOut').outerHeight(true);
	var ratio = imgW/relImgW;
	
	$('html,body').animate({ scrollTop: -( wH - relImgH ) / 2  }, 200);

    $('body').on('click', 'img#inOut', function(e){
		
		var mouseX = Math.round(e.pageX);
		//and from the top
		var mouseY = Math.round(e.pageY);
		
        if( $(this).hasClass("in") ){ // zoom out
            $(this).removeClass("in").addClass("out");
			//$('html,body').animate({ scrollTop: $(this).offset().top - ( wH - relImgH ) / 2  }, 200);
			//mousePosImage(mouseX, mouseY, ratio);
        }else{
            $(this).removeClass("out").addClass("in");// zoom in
			//mousePosImage(mouseX, mouseY, ratio);
        }
    });
});


</script>

</body>
</html>
