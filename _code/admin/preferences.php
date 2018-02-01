<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

$title = 'ADMIN : preferences';
$description = '';
$page = 'admin';
$back_link = 'manage_structure.php';

$custom_fonts = array(
	'Courier New'=>'14px "Courier New", Courier, monospace',
	'Lucida Console'=>'12px "Lucida Console", Monaco, monospace',
	'Lucida Sans'=>'13px "Lucida Sans Unicode", "Lucida Grande", sans-serif',
	'Tahoma'=>'13px Tahoma, Geneva, sans-serif',
	'Trebuchet MS'=>'13px "Trebuchet MS", Helvetica, sans-serif',
	'Verdana'=>'12px Verdana, Geneva, sans-serif',
	'Times New Roman'=>'14px "Times New Roman", Times, serif',
	'Georgia'=>'14px Georgia, serif',
	'Palatino Linotype'=>'14px "Palatino Linotype", "Book Antiqua", Palatino, serif',
	'Open Sans'=>'13px "Open Sans", sans-serif',
	'EB Garamond'=>'17px "EB Garamond", serif',
	'Old Standard TT'=>'16px "Old Standard TT", serif',
	'Archivo Narrow'=>'17px "Archivo Narrow", serif',
	'PT Sans'=>'14px "PT Sans", sans-serif',
	'Vollkorn'=>'16px Vollkorn, serif',
	'Arvo'=>'14px Arvo, serif',
);

// create new sub-section
if(isset($_POST['submitSitePrefs'])){
	
	if(!empty($_POST['seo_description'])){
		$seo_description = trim( strip_tags( str_replace( array("'", '"'), array('&#34;', '&#39;'), $_POST['seo_description']) ) );
	}
	if(!empty($_POST['seo_title'])){
		$seo_title = trim( strip_tags( str_replace( array("'", '"'), array('&#34;', '&#39;'), $_POST['seo_title'] ) ) );
	}
	if(!empty($_POST['site_bg_color'])){
		$site_bg_color = $_POST['site_bg_color'];
	}
	if(!empty($_POST['site_font'])){
		$site_bg_color = $_POST['site_bg_color'];
	}
		
	//$message = save_site_prefs($seo_description, $seo_title, $site_bg_color);
}

// message GET (from delete_file.php for exemple)
if(isset($_GET['message'])){
	$message = urldecode($_GET['message']);
}

require(ROOT.'_code/inc/doctype.php');
?>
<script src="/_code/js/jscolor.min.js"></script>
<link href="/_code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<div id="working">working...</div>

<!-- start adminContainer -->
<div id="adminContainer">
	
	<span class="title"><a href="<?php echo $back_link; ?>">&larr; ADMIN</a> - Preferences</span> <a href="?logout" class="button" style="float:right;">-> logout</a>
	<div class="clearBoth" id="message" style="margin:20px 0;">
		<?php if( isset($message) ){
			echo $message;
		}
		?>
	</div>
	
	<div id="prefContainer">
		<div id="ajaxTarget">
		<?php if( isset($result) && !empty($result) ){
			echo $result;
		}
		?>
		
		<div class="halfContainer"><!-- start 1st half container -->
			<form action="" name="sitePreferences" method="post">
		<h3 style="margin-top:0; padding-top:0; border-bottom:1px solid #ccc;">Site:</h3>
		
		<div class="quart" style="text-align:right;">First language:</div>
		<div class="quart"><input type="text" maxlength="100" name="first_lang" value="<?php echo $first_lang; ?>"></div>
		<div class="quart" style="text-align:right;">Second language:</div>
		<div class="quart"><input type="text" maxlength="100" name="second_lang" value="<?php echo $second_lang; ?>"></div>
		
		<div class="quart" style="text-align:right;">home page title:</div>
		<div class="quart"><input type="text" maxlength="100" name="seo_title" value="<?php echo $seo_title; ?>"></div>
		
		<div class="quart" style="text-align:right;">home page description:</div>
		<div class="quart"><textarea maxlength="500" name="seo_description"><?php echo $seo_description; ?></textarea></div>
		
		<div class="quart" style="text-align:right;">home page background image:
		<?php
		if(isset($_GET['upload_result']) && urldecode($_GET['upload_result']) == 'file uploaded'){
			echo '<p class="success">file uploaded</p>';
		}
		?>
		</div>
		<div class="quart"><img src="/content/<?php echo $home_image; ?>" style="width:100%;"><br>
		<a href="javascript:;" class="button showModal" rel="uploadFile?path=<?php echo urlencode(ROOT.'content/'); ?>&replace=<?php echo urlencode(ROOT.'content/bg.png'); ?>">Change</a>
		<?php
		if(isset($_GET['upload_result']) && urldecode($_GET['upload_result']) == 'file uploaded'){
			echo '<a href="/">test</a>';
		}
		?>
		</div>
		
		<div class="quart" style="text-align:right;">site background color:</div>
		<div class="quart"><input name="site_bg_color" class="jscolor jscolor-active" value="<?php echo $site_bg_color; ?>" onchange="update(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>
		
		<div class="quart" style="text-align:right;">site font: </div>
		<div class="quart">
		<select name="font">
			<?php 
			foreach($custom_fonts as $k=>$v){
				if($site_font == $v){
					$selected = ' selected';
				}else{
					$selected = '';
				}
				echo '<option value="'.$k.'"'.$selected.'>'.$k.'</option>'.PHP_EOL;
			}
			?>
		</select>
		</div>

		<div class="quart" style="text-align:right;">font color:</div>
		<div class="quart"><input name="font_color" class="jscolor jscolor-active" value="<?php echo $font_color; ?>" onchange="update(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>
			
		<div class="quart" style="text-align:right;">images border:</div>
		<div class="quart">
			<select name="border">
			<option value="none">none</option>
			<option value="000000">black</option>
			<option value="AAAAAA">grey</option>
			<option value="EEEEEE">light grey</option>
			<option value="FFFFFF">white</option>
		</select>
		</div>
	
		<div class="clearBoth" style="text-align:right; padding-top:20px;">
			<button type="submit" name="submitSitePrefs"> Save changes </button>
		</div>
		</form>
	</div><!-- end 1st half container -->
		
	<div class="halfContainer"><!-- start 2nd half container -->
		<form action="" name="userPreferences" method="post">
			
		<h3 style="margin-top:0; padding-top:0; border-bottom:1px solid #ccc;">You:</h3>
		
		<div class="quart" style="text-align:right;">name:</div>
		<div class="quart"><input type="text" name="user" value="<?php echo $user; ?>"></div>
		
		<div class="quart" style="text-align:right;">email:</div>
		<div class="quart"><input type="email" name="email" value="<?php echo $email; ?>"></div>
		
		<div class="quart" style="text-align:right;">admin username:</div>
		<div class="quart"><input type="text" name="admin_username" value=""></div>
		
		<div class="quart" style="text-align:right;">admin password:</div>
		<div class="quart"><input type="password" name="admin_password" value=""></div>
	
		<div class="clearBoth" style="text-align:right; padding-top:20px;">
			<button type="submit" name="submitUserPrefs"> Save changes </button>
		</div>
		
		</form>
	</div><!-- end 2nd half container -->
	<?php 
	
	?>
</div><!-- ajaxTarget end -->
	</div><!-- prefContainer end -->


<div class="clearBoth"></div>
</div><!-- end adminContainer -->


<script type="text/javascript">
function update(jscolor) {
    // 'jscolor' instance can be used as a string
    document.body.style.backgroundColor = '#' + jscolor;
}
</script>

<?php require(ROOT.'_code/inc/adminFooter.php'); ?>
