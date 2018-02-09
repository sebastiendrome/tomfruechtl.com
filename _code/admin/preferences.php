<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

$title = 'ADMIN : preferences';
$description = $message = '';
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


// email verification (check that verification code, new email and old email match)
if(isset($_GET['verify'])){
	if( $_GET['verify'] == $email_verification_code.base64_encode($tmp_email).base64_encode($email) ){
		$content = file_get_contents(ROOT.CONTENT.'user_custom.php');
		if( preg_match('/\$email = \''.$email.'\';/', $content, $match) ){
			// set new email to write in user_custom.php content
			$content = str_replace($match[0], '$email = \''.$tmp_email.'\';', $content);
			// set new email_verification_code
			$new_verification_code = rand(0,999).rand(5,99).rand(111,9999);
			$content = str_replace('$email_verification_code = \''.$email_verification_code.'\';', '$email_verification_code = \''.$new_verification_code.'\';', $content);
			// write content to file
			if( $fp = fopen(ROOT.CONTENT.'user_custom.php', 'w') ){
				fwrite($fp, $content);
				fclose($fp);
				$message .= '<p class="success">Thank you, your new email '.$tmp_email.' has been verified and saved.</p>';
			}else{
				$message .= '<p class="error">Could not open preferences file.</p>';
			}
			//reload the page without the verify code query
			header("Location: ?message=".urlencode($message));
			exit;
		}
	}else{
		header("Location: /_code/admin/preferences.php");
		exit;
	}
}

// Form submit validation
if(isset($_POST['submitSitePrefs']) || isset($_POST['submitUserPrefs'])){
	
	// get content of user_custom.php
	$content = file_get_contents(ROOT.CONTENT.'user_custom.php');
	// initialize new_vals (from form $_POST)
	$new_vals = array();
	foreach($_POST as $k => $v){
		if($k != 'types' && $k != 'sizes' && $k != 'submitSitePrefs' && $k != 'submitUserPrefs'){
			//echo $k.' = '.$v.'<br>';
			$new_vals[$k] = addslashes($v);
		}
	}
	
	//print_r($new_vals);
	//exit;

	// make sure blank user name and password are not save
	if( isset($new_vals['admin_username']) && empty($new_vals['admin_username']) ){
		unset($new_vals['admin_username']);
	}elseif( isset($new_vals['admin_username']) ){
		// validate username
		if(strlen($new_vals['admin_username'])<51 && strlen($new_vals['admin_username'])>2){
			$new_vals['admin_username'] = sha1($new_vals['admin_username']);
		}else{
			unset($new_vals['admin_username']);
			$message .= '<p class="error">username must be 3 to 50 characters.</p>'; 
		}
	}
	if( isset($new_vals['admin_password']) && empty($new_vals['admin_password']) ){
		unset($new_vals['admin_password']);
	}elseif( isset($new_vals['admin_password']) ){
		// validate password
		if(strlen($new_vals['admin_password'])<51 && strlen($new_vals['admin_password'])>2){
			if($new_vals['admin_password'] == $new_vals['admin_password_again']){
				$new_vals['admin_password'] = sha1($new_vals['admin_password']);
			}else{
				unset($new_vals['admin_password'], $new_vals['admin_password_again']);
				$message .= '<p class="error">The two admin passwords do not match! The new password has not been saved.</p>';
			}
			
		}else{
			unset($new_vals['admin_password']);
			$message .= '<p class="error">admin password must be 5 to 20 characters.</p>'; 
		}
	}
	
	// validate new email
	if(isset($new_vals['email']) && $new_vals['email'] != $email){
		$subject = 'Hello '.$user.', please verify your email.';
		$body = "You have requested to change the email associated with your site ".SITE.". \nClick this link to activate your new email:\n";
		$body .= PROTOCOL.SITE.'_code/admin/preferences.php?verify='.$email_verification_code.base64_encode( $new_vals['email']).base64_encode($email);
		$body .= "\n\nIf you did not make this request, please contact the webmaster as soon as possible, at ".AUTHOR_REF;
		if( mail($new_vals['email'], $subject, $body) ){
			$message .= '<p class="note">an email verification has been sent to '.$new_vals['email'].'<br>
			Please check your email (including your junk mail!) and click the verification link to activate your new email.</p>';
			// set new_vals tmp_email so it is written in user_custom.php for later verification
			$new_vals['tmp_email'] = $new_vals['email'];
			// change new_vals email value to previous email so it does not get written until verified
			$new_vals['email'] = $email;
		}else{
			$message .= '<p class="error">Error: email verification could not be sent.</p>';
		}
	}


	// format seo_description and seo_title
	if(isset($new_vals['seo_description'])){
		$new_vals['seo_description'] = trim( strip_tags( str_replace( array("'", '"',"\n", "\r"), array('&#34;', '&#39;', ' ', ' '), $new_vals['seo_description']) ) );
	}
	if(isset($new_vals['seo_title'])){
		$new_vals['seo_title'] = trim( strip_tags( str_replace( array("'", '"',"\n", "\r"), array('&#34;', '&#39;', ' ', ' '), $new_vals['seo_title']) ) );
	}

	// get full font css specitication from baisc font value
	if(isset($new_vals['site_font'])){
		$new_vals['site_font'] = $custom_fonts[$new_vals['site_font']];
	}

	//echo 'font: '.$new_vals['site_font'].'<br>'; exit;
	
	foreach($new_vals as $nk => $nv){
		if( preg_match('/\$'.$nk.' = \'.*\';/', $content, $match) ){
			//echo $match[0].'<br>';
			$content = str_replace($match[0], '$'.$nk.' = \''.$nv.'\';', $content);
			// change value of variable names corresponding tp $nk to the new value
			$$nk = $nv;
		}
	}

	// write content to file
	if( $fp = fopen(ROOT.CONTENT.'user_custom.php', 'w') ){
		fwrite($fp, $content);
		fclose($fp);
		$message .= '<p class="success">Your changes have been saved.</p>';
	}else{
		$message .= '<p class="error">Could not open preferences file.</p>';
	}

	//echo '<pre>'.str_replace('<', '&lt;', $content).'</pre>'; //exit;
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
	
	<span class="title"><a href="<?php echo $back_link; ?>">&larr; ADMIN</a> - Preferences</span> <a href="?logout" class="button" style="float:right;">logout</a>
	<div class="clearBoth" id="message">&nbsp;
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
			<form action="/_code/admin/preferences.php" name="sitePreferences" method="post">
		<h2 style="margin-top:0; padding-top:0; border-bottom:1px solid #ccc;">Site:</h2>
		
		<div class="quart" style="text-align:right;">First language:</div>
		<div class="quart"><input type="text" maxlength="50" name="first_lang" value="<?php echo $first_lang; ?>" required></div>
		<div class="quart" style="text-align:right;">Second language:</div>
		<div class="quart"><input type="text" maxlength="50" name="second_lang" value="<?php echo $second_lang; ?>" required></div>
		
		<div class="quart" style="text-align:right;">Site title:</div>
		<div class="quart"><input type="text" maxlength="100" name="seo_title" value="<?php echo $seo_title; ?>"></div>
		
		<div class="quart" style="text-align:right;">Site description:</div>
		<div class="quart"><textarea maxlength="500" name="seo_description"><?php echo $seo_description; ?></textarea></div>
		
		<div class="quart" style="text-align:right;">home page background image:
		<?php
		if(isset($_GET['upload_result']) && urldecode($_GET['upload_result']) == 'file uploaded'){
			echo '<p class="success">file uploaded</p>';
		}
		?>
		</div>
		<div class="quart"><img src="/content/<?php echo $home_image; ?>?v=<?php echo rand(1,999); ?>" style="width:100%;"><br>
		<a href="javascript:;" class="button showModal" rel="uploadFile?path=<?php echo urlencode(ROOT.'content/'); ?>&replace=<?php echo urlencode(ROOT.'content/bg.png'); ?>">Change</a>
		</div>
		
		<div class="quart" style="text-align:right;">site background color:</div>
		<div class="quart"><input name="site_bg_color" class="jscolor jscolor-active" value="<?php echo $site_bg_color; ?>" onchange="updateBgColor(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>
		
		<div class="quart" style="text-align:right;">site font: </div>
		<div class="quart">
		<select name="site_font">
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
		<div class="quart"><input name="font_color" class="jscolor jscolor-active" value="<?php echo $font_color; ?>" onchange="updateFontColor(this.jscolor);" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>
			
		<div class="quart" style="text-align:right;">images border:</div>
		<div class="quart">
			<select name="borders">
			<option value="none"<?php if($borders == 'none'){echo ' selected';}?>>none</option>
			<option value="1px solid #000000"<?php if($borders == '1px solid #000000'){echo ' selected';}?>>black</option>
			<option value="1px solid #AAAAAA"<?php if($borders == '1px solid #AAAAAA'){echo ' selected';}?>>grey</option>
			<option value="1px solid #EEEEEE"<?php if($borders == '1px solid #EEEEEE'){echo ' selected';}?>>light grey</option>
			<option value="1px solid #FFFFFF"<?php if($borders == '1px solid #FFFFFF'){echo ' selected';}?>>white</option>
		</select>
		</div>

		<div class="quart" style="text-align:right;">links color:</div>
		<div class="quart"><input name="link_color" class="jscolor jscolor-active" value="<?php echo $link_color; ?>" onchange="updateLinkColor(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>
	
		<div class="clearBoth" style="text-align:right; padding-top:20px;">
			<button type="submit" name="submitSitePrefs"> Save changes </button>
		</div>
		</form>
	</div><!-- end 1st half container -->
		
	<div class="halfContainer"><!-- start 2nd half container -->
		<form action="/_code/admin/preferences.php" name="userPreferences" method="post">
			
		<h2 style="margin-top:0; padding-top:0; border-bottom:1px solid #ccc;">You:</h2>
		
		<div class="quart" style="text-align:right;">name:</div>
		<div class="quart"><input type="text" name="user" value="<?php echo $user; ?>" maxlength="50" required></div>
		
		<div class="quart" style="text-align:right;">email:</div>
		<div class="quart"><input type="email" name="email" value="<?php echo $email; ?>" maxlength="100" required></div>
		
		<div class="quart" style="text-align:right;">admin username:</div>
		<div class="quart"><input type="text" name="admin_username" pattern=".{0}|.{3,50}" title="3 to 50 chars" value=""></div>
		
		<div class="quart" style="text-align:right;">admin password:</div>
		<div class="quart"><input type="password" name="admin_password" pattern=".{0}|.{5,20}" title="5 to 10 chars" value=""></div>

		<div class="quart" style="text-align:right;">same password again:</div>
		<div class="quart"><input type="password" name="admin_password_again" pattern=".{0}|.{5,20}" title="5 to 10 chars" value=""></div>
		
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


<?php require(ROOT.'_code/inc/adminFooter.php'); ?>

<script type="text/javascript">
function updateBgColor(jscolor){
    document.body.style.backgroundColor = '#' + jscolor;
}
function updateFontColor(jscolor){
    document.body.style.color = '#' + jscolor;
}
function updateLinkColor(jscolor){
    $('a').css('color', '#' + jscolor);
}
</script>
