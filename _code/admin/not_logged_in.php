<?php
if( !isset($_SESSION) ){
	session_start();
}
// initialize vars.
$message = '';
$logged_in = FALSE; // let's assume we're not logged in yet...

// kill sessions if user logged out.
if( isset($_GET['logout']) ){
	unset($_SESSION['userName']);
	unset($_SESSION['kftgrnpoiu']);
}

// login form POST processing
if( isset($_POST['login']) ){
	$usr = trim( strip_tags( urldecode($_POST['userName']) ) );
	$pwd = trim( strip_tags( urldecode($_POST['password']) ) );
	$_SESSION['userName'] = sha1($usr);
	$_SESSION['kftgrnpoiu'] = sha1($pwd);
}

// successful login
if( 
isset($_SESSION['kftgrnpoiu']) 
&& isset($_SESSION['userName']) 
&& $_SESSION['kftgrnpoiu'] == $admin_password 
&& $_SESSION['userName'] == $admin_username
){
	$logged_in = TRUE; // this will grant us access

// wrong login
}elseif( isset($_SESSION['kftgrnpoiu']) ){
	$message .= '<p style="color:red;">Wrong Login! Please try again.</p>';
}

// form action: remove query string (for exemple ?logout)
$form_action = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);

if(!$logged_in){
	// login form markup
	$login_form = '
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">';
	ob_start();
	require(ROOT.'_code/custom.css.php');
	$login_form .= ob_get_contents();
	ob_end_flush();
	$login_form .= '<link href="/_code/css/default/css.css?v=12" rel="stylesheet" type="text/css">
	</head>
	<body style="background-color:#ccc;">

	<div id="admin" style="position:absolute;width:33%;left:33%;top:10%;">
	<div style="background-color:#fff; text-align:center; padding:10px; border:4px solid #000;">
	'.$message.'
	<form name="l" id="l" action="'.$form_action.'" method="post" style="margin-top:10px;">
	username: <input type="text" autocorrect="off" autocapitalize="none" name="userName" autofocus><br><br>
	password: <input type="password" name="password"><br><br>
	<input type="submit" name="login" value=" LOGIN ">
	</form>

	<noscript><p style="color:red;">JavaScript appears to be disabled on this browser.<br>
	In order to use the admin area you must enable JavaScript in your Browser preferences.</p></noscript>

	</div>
	</div>

	</body>
	</html>';
	
	echo $login_form; 
	exit;
}

