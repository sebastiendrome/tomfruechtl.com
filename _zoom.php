<?php
require('_code/inc/first_include.php');

// make sure there is an image request, or else exit
if(isset($_GET['img']) && !empty($_GET['img'])){
	$img = urldecode($_GET['img']);
	// validate file extension, or else exit
	$ext = file_extension(basename($img));
	if( !preg_match($types['resizable_types'], $ext) ) {
		exit;
	}
	// use different directory size depending on screen width
	if(isset($_COOKIE['wW']) && $_COOKIE['wW'] < 800){
		$dir_size = '_L';
	}else{
		$dir_size = '_XL';
	}
	// img url
	$img_url = preg_replace('/\/_(S|M|L)\//', '/'.$dir_size.'/', $img);
	// get image file width and height
	list($orig_img_w, $orig_img_h) = getimagesize(ROOT.$img_url);
}else{
	exit;
}

$title = $user.' Artist Portfolio';
$description = $user;
$social_image = PROTOCOL.SITE.$img_url;

require(ROOT.'_code/inc/doctype.php');

?>

<style type="text/css">
/* orizontaly center the image, limit its display width to its actual width */
img#inOut{display:block; margin:auto; max-width:<?php echo $orig_img_w; ?>px;}
/* reduced size style */
img.isOut{width:100%; cursor:zoom-in;}
/* full size style */
img.isIn{width:auto; cursor:zoom-out;}
</style>

<!-- start nav -->
<div class="backTitle zoomPage">
	<ul>
		<li><a href="javascript:window.history.back();">&larr; <?php echo BACK; ?></a></li>
	</ul>
</div><!-- end nav -->



<img src="<?php echo $img_url; ?>" id="inOut" class="isOut" title="click to zoom-in">



<!-- jQuery -->
<script type="text/javascript" src="/_code/js/jquery-3.2.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/_code/js/js.js?v=4" charset="utf-8"></script>

<script type="text/javascript">

$(document).ready(function(){
	
	// image file actual width and height
	var orig_img_w = <?php echo $orig_img_w; ?>;
	var orig_img_h = <?php echo $orig_img_h; ?>;
	// image width and height as displayed
	var img_display_w = $('#inOut').width();
	var img_display_h = $('#inOut').height();
	// ratio between two image sizes (original and as displayed)
	var ratio = orig_img_w/img_display_w;
	ratio = ratio.toFixed(2);

	//alert(ratio);
	
	// scroll to vertical middle of image
	$('html,body').animate({ scrollTop: -( wH - img_display_h ) / 2  }, 200);

	// When user clicks on image (to zoom in, or to zoom out), we want to:
	// 1. change the image style/size accordingly,
	// 2. scroll so that image point where user just clicked is centered in window
    $('body').on('click', 'img#inOut', function(e){
		
		// get mouse coordinates relative to image
		var y = e.pageY - $(this).offset().top; // from top edge
		var x = e.pageX - $(this).offset().left; // from left edge

		//alert(y+' '+x);

		// if user zooms-in, change image style to zoom-out, and vice-versa
        if( $(this).hasClass("isOut") ){
            $(this).removeClass("isOut").addClass("isIn"); // let's zoom in
			// image is now full size. Let's calculate new coordinates by multiplying old ones by ratio
			var new_y = y*ratio; // top
			var new_x = x*ratio; // left

			//alert(new_y+' '+new_x);

		// if user zooms-out,...
		}else{
			$(this).removeClass("isIn").addClass("isOut"); // let's zoom out
			// image is now reduced. Let's calculate new coordinates by dividing old ones by ratio
			var new_y = y/ratio; // top
			var new_x = x/ratio; // left

			//alert(new_y+' '+new_x);

		}

		// now that we have the new coordinates, let's calculate the distance relative to window width and height, where these coordinates will be centered in window:
		var fromTop = new_y-(wH/2);
		var fromLeft = new_x-(wW/2);
		// and finaly let's scroll there...
		$('html,body').scrollTop(fromTop).scrollLeft(fromLeft);
		
	});
});


</script>

</body>
</html>
