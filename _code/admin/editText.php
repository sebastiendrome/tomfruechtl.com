<?php
// edit Text (txt or html files) ---> http://wysihtml.com
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

// message returned from from process in save_text.php
$message = '';
if( isset($_GET['message']) ){
	$message = urldecode($_GET['message']);
	if( substr($message, 0, 2) == '1|'){
		$item = substr($message, 2);
		$_SESSION['item'] = $item;
		$message = '<p class="success">Text Saved.</p>';
	}else{
		$message = '<p class="error">'.substr($message, 2).'</p>';
	}
}

// form submit from within newFile.php modal: submitted: payj and fileName (optional)
if( isset($_POST['createText']) ){
	if( isset($_POST['path']) && !empty($_POST['path']) ){
		$item = urldecode($_POST['path']);
		$_SESSION['item'] = $item;
		if( isset($_POST['fileName']) && !empty($_POST['fileName']) ){
			$item .= '/'.filename( urldecode($_POST['fileName']), 'encode').'.html';
			//echo $item;
		}
	}else{
		exit();
	}
}

// from link to edit existing file. item is the text file, or section in which a new text file should be created...
if( isset($_GET['item']) ){
	$item = trim( urldecode($_GET['item']) );
	if( empty($item) ){
		header("location: manage_structure.php");
		exit;
	}
	$_SESSION['item'] = $item;
	
}elseif( isset($_SESSION['item']) ){
	$item = $_SESSION['item'];

}

if(!isset($item)){
	header("location: manage_structure.php");
	exit;	
}

// echo $item; -> 'section1/section2'

$title = 'ADMIN : Edit Text :';
$description = filename(str_replace(array(ROOT.CONTENT, '_XL/'), '', $item), 'decode');
$back_link = 'manage_structure.php';

$ext = file_extension(basename($item));

if( file_exists($item) && preg_match($_POST['types']['text_types'], $ext) ){
	$content = file_get_contents($item);
	if($ext == '.txt'){
		$content = my_nl2br($content);
	}

}else{
	$content = '';
}

//echo $content;

require(ROOT.'_code/inc/doctype.php');
?>

<meta http-equiv="X-UA-Compatible" content="IE=Edge">

<link href="/_code/css/admincss.css?v=2" rel="stylesheet" type="text/css">
<link href="/_code/css/wysihtml5.css" rel="stylesheet" type="text/css">

<script src="/_code/admin/wysihtml-0.5.5/dist/wysihtml-toolbar.min.js"></script>
<script src="/_code/admin/wysihtml-0.5.5/parser_rules/advanced.js"></script>

<div id="working">working...</div>

<!-- start adminContainer -->
<div id="adminContainer">
	
	<span class="title"><a href="<?php echo $back_link; ?>">&larr; back</a> | <?php echo $description; ?></span> 
	 <a href="?logout" class="button" style="float:right;">-> logout</a>
	<div class="clearBoth" id="message" style="margin:20px 0;">
		<?php echo $message; ?>
	</div>


<!-- start content -->
<div id="content" style="border:1px solid #ccc; margin-top:0; background-color:#eee;">


<!--
		<button><?php echo FIRST_LANG; ?></button> <button><?php echo SECOND_LANG; ?></button><br>
-->

<!-- toolbar with suitable buttons and dialogues -->
	
	<form name="textEditorForm" action="save_text.php" method="post" id="textEditorForm">
	<div id="toolbar">
<a data-wysihtml5-command="bold" title="CTRL+B"><b>bold</b></a>
<a data-wysihtml5-command="italic" title="CTRL+i"><i>italic</i></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Big Header text"><h1>H1</h1></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="Medium Header text"><h2>H2</h2></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" title="Small Header text"><h3>H3</h3></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="p" title="Paragraph">P</a>
	
<a data-wysihtml5-command="justifyLeft" title="Align left" unselectable="on" style="border-radius: 3px 0 0 3px;"><img src="/_code/admin/images/align-left.gif" style="width:13px; height:12px;">
</a><a data-wysihtml5-command="justifyCenter" title="Align center" unselectable="on" style="border-radius:0; margin:0 -1px;"><img src="/_code/admin/images/align-center.gif" style="width:13px; height:12px;">
</a><a data-wysihtml5-command="justifyRight" title="Align right" unselectable="on" style="border-radius: 0 3px 3px 0;"><img src="/_code/admin/images/align-right.gif" style="width:13px; height:12px;">
</a>
<a data-wysihtml5-command="justifyFull" title="Justify" unselectable="on"><img src="/_code/admin/images/align-justify.gif" style="width:13px; height:12px;">
</a>

<a data-wysihtml5-command="createLink" href="javascript:;" unselectable="on" class="wysihtml5-command-dialog-opened" style="border-radius: 3px 0 0 3px;">link</a><a data-wysihtml5-command="removeLink" href="javascript:;" unselectable="on" class="" style="border-radius: 0 3px 3px 0; margin-left:-1px;"><s>link</s></a>

<div id="workflow">
<a data-wysihtml5-command="undo" href="javascript:;" unselectable="on" title="Undo">undo</a><a data-wysihtml5-command="redo" href="javascript:;" unselectable="on" title="Redo">redo</a><a data-wysihtml5-action="change_view" title="Show HTML" class="" onclick="if(this.className == ''){this.className = 'wysihtml5-command-active'}else{this.className = ''}">show code</a>
</div>

	<div data-wysihtml5-dialog="createLink" style="display:none;">
        <label>
          Link:
          <input data-wysihtml5-dialog-field="href" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>
   </div>
   
   <div class="textareaContainer">
	  <textarea id="textarea" name="content" placeholder="Enter or paste text ..."><?php echo $content; ?></textarea>
  </div>
	  <br>
	  <input type="hidden" name="item" value="<?php echo $item; ?>">
	  <button type="reset" name="reset">Reset</button>
	  <button type="submit" name="saveTextEditor" id="saveTextEditor" style="float:right;">SAVE</button>
	</form>

<div style="display:none;">
	<h2>Events:</h2>
<div id="log"></div>
</div>

<div class="clearBoth"></div>

</div><!-- end content -->


</div><!-- end adminContainer -->

<?php require(ROOT.'_code/inc/adminFooter.php'); ?>

<script type="text/javascript">

var editor = new wysihtml5.Editor("textarea", {
	toolbar:        "toolbar",
	parserRules:    wysihtml5ParserRules,
	stylesheets:  "../css/stylesheet.css"
	//useLineBreaks:  false
});

var formmodified = 0;

var log = document.getElementById("log");

editor
	.on("load", function() {
		log.innerHTML += "<div>load</div>";
	})
	.on("focus", function() {
		log.innerHTML += "<div>focus</div>";
	})
	.on("blur", function() {
		log.innerHTML += "<div>blur</div>";
	})
	.on("change", function() {
		log.innerHTML += "<div>change</div>";
		formmodified = 1;
	})
	.on("paste", function() {
		log.innerHTML += "<div>paste</div>";
		formmodified = 1;
	})
	.on("newword:composer", function() {
		log.innerHTML += "<div>newword:composer</div>";
	})
	.on("undo:composer", function() {
		log.innerHTML += "<div>undo:composer</div>";
		formmodified = 1;
	})
	.on("redo:composer", function() {
		log.innerHTML += "<div>redo:composer</div>";
		formmodified = 1;
	});


// set texarea height depending on window size
$("#textEditorForm textarea").css("height", wH-280+'px');
// but make sure it is never less than 100px high
if($("#textEditorForm textarea").height() < 100){
	$("#textEditorForm textarea").css("height", 100+'px');
}

// prevent user from leaving the page without saving his changes
$(document).ready(function(){
	window.onbeforeunload = function(e){
		var warning = "Your changes have not been saved. Are you sure you wish to leave the page?";
		if (formmodified == 1) {
			var e = e || window.event;
			// For IE and Firefox
			if (e){
				e.returnValue = warning;
			}
			// For Safari
			return warning;
		}
	}
	$("button#saveTextEditor").click(function() {
        formmodified = 0;
    });
});

/*
// update item name
function saveTextEditor(item, content){
	var item = encodeURIComponent(item);
	$.ajax({
		method: "POST",
		url: "admin_ajax.php",
		data: 'saveTextEditor&item='+item+'&content='+content
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
	});
}
*/

/*
$("button#saveText").on('click', function(e){
	var item = '<?php echo $item; ?>';
	var newContent = $('#textarea').val();
	//alert(newContent);
	saveTextEditor(item, newContent);
	e.preventDefault();
	// scroll to top of window
	window.scrollTo(0, 0);
});
*/

</script>

