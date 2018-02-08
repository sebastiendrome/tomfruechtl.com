
/***** manage site structure functions *****************************************************/
// change input NAME
$('#structureContainer, #contentContainer').on('change', 'input.nameInput', function() {
	var url = window.location.href;
	//alert(url);
	if( url.match(/manage_contents/) ){
		var adminPage = 'manage_contents';
	}else if( url.match(/manage_structure/) ){
		var adminPage = 'manage_structure';
	}
	//alert(adminPage);
	var oldName = $(this).parent().data("name");
	var newName = $(this).val();
	var parent = $(this).parent().data("parent"); // get parent name in case this is a sub-section
	//alert(parent+' > '+oldName);
	// add underscore to newName if necessary
	if(oldName.substr(0, 1) == '_'){
		newName = '_'+newName;
	}
	updateName(oldName, newName, parent, adminPage);
});


// change input POSITION 
$('#structureContainer, #contentContainer').on('change', 'input.position', function() {
	var url = window.location.href;
	//alert(url);
	if( url.match(/manage_contents/) ){
		var adminPage = 'manage_contents';
	}else if( url.match(/manage_structure/) ){
		var adminPage = 'manage_structure';
	}
	//alert(adminPage);
	var parent = $(this).data("parent"); // get parent name in case this is a sub-section
	var item = $(this).data("item");
	var oldPosition = $(this).data("oldposition");
	//alert(parent+' > '+item+' -> '+oldPosition);
	var newPosition = $(this).val();
	updatePosition(item, oldPosition, newPosition, parent, adminPage);
});


// change POSITION move up or down
$('#structureContainer, #contentContainer').on('click', 'a.up, a.down', function(e) {
	var url = window.location.href;
	//alert(url);
	if( url.match(/manage_contents/) ){
		var adminPage = 'manage_contents';
	}else if( url.match(/manage_structure/) ){
		var adminPage = 'manage_structure';
	}
	//alert(adminPage);
	var dataInput = $(this).closest('li').find('input.position');
	var parent = dataInput.data("parent"); // get parent name in case this is a sub-section
	var item = dataInput.data("item");
	var oldPosition = dataInput.data("oldposition");
	//alert(parent+' > '+item+' -> '+oldPosition);
	if($(this).hasClass("up")){
		var newPosition = oldPosition-1;
	}else{
		var newPosition = oldPosition+1;
	}
	updatePosition(item, oldPosition, newPosition, parent, adminPage);
	e.preventDefault();
});


// SHOW or HIDE section
$('#structureContainer').on('click', 'a.show, a.hide', function(e) {
	var item = $(this).parents('li').data("name");
	var parent = $(this).parents('ul').parents('li').data("name"); // get parent name in case this is a sub-section
	//alert(parent+' > '+item);
	showHide(item, parent);
	e.preventDefault();
});
	



/***** manage site content functions *****************************************************/


// save text description
$('#contentContainer').on('click', 'a.saveText', function() {
	var file = $(this).parent('div.actions').find('input.file').val();
	var enText = $(this).parent('div.actions').find('textarea.en').val();
	//alert(file+' entxt:'+enText);
	var deText = $(this).parent('div.actions').find('textarea.de').val();
	saveTextDescription(file, enText, deText);
});

// show / hide tips
$('body').on('click', 'div.tip a.tipTitle', function(e){
	var olDisplay = $(this).closest('div.tip').children('ol').css('display');
	//alert(olDisplay);
	if(olDisplay == 'none'){
		$(this).addClass("open");
		$(this).closest('div.tip').children('ol').css('display', 'block');
	}else{
		$(this).removeClass("open");
		$(this).closest('div.tip').children('ol').css('display', 'none');
	}
	
	e.preventDefault();
});




// update item name
function updateName(oldName, newName, parent, adminPage){
	var oldName = encodeURIComponent(oldName);
	var newName = encodeURIComponent(newName);
	var parent = encodeURIComponent(parent);
	$.ajax({
		method: "GET",
		url: "admin_ajax.php",
		data: 'updateName&oldName='+oldName+'&newName='+newName+'&parent='+parent+'&adminPage='+adminPage
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
	});
}

// change position
function updatePosition(item, oldPosition, newPosition, parent, adminPage){
	//alert(item+': from '+oldPosition+' to '+newPosition);
	var item = encodeURIComponent(item);
	var parent = encodeURIComponent(parent);
	
	//alert(item+' > '+parent);
	
	$.ajax({
		method: "GET",
		url: "admin_ajax.php",
		data: 'updatePosition&item='+item+'&oldPosition='+oldPosition+'&newPosition='+newPosition+'&parent='+parent+'&adminPage='+adminPage
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
	});
}

// show / hide item
function showHide(item, parent){
	var item = encodeURIComponent(item);
	var parent = encodeURIComponent(parent);
	$.ajax({
		method: "GET",
		url: "admin_ajax.php",
		data: 'showHide&item='+item+'&parent='+parent
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
	});
}

// rotate image /****** NOT WORKING *******/
function rotateImg(image){
	var image = encodeURIComponent(image);
	$.ajax({
		method: "GET",
		url: "rotate_image.php",
		data: 'rotateImage&image='+image
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
	});
}

/*
function deleteItem(item){
	var item = encodeURIComponent(item);
	$.ajax({
		method: "GET",
		url: "admin_ajax.php",
		data: 'delete&item='+item
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
	});
}
*/

function createNew(position, parent){
	var parent = encodeURIComponent(parent);
	$.ajax({
		method: "GET",
		url: "admin_ajax.php",
		data: 'createNew&position='+position+'&parent='+parent
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
	});
}

// save text from textarea into file.txt
function saveTextDescription(file, enText, deText){
	var file = encodeURIComponent(file);
	var enText = encodeURIComponent(enText);
	var deText = encodeURIComponent(deText);
	$.ajax({
		method: "GET",
		url: "admin_ajax.php",
		data: 'saveTextDescription&file='+file+'&enText='+enText+'&deText='+deText
	})
	.done(function(msg){
		$('#message').html(msg);
		$('a.button.saveText').each( function(i){
			if(!$(this).hasClass("disabled")){
				$(this).addClass("disabled");
				if(msg.match("^<p class=\"success")){
					$(this).before('<span id="localMessage">text saved</span> ');
				}
				return false;
			}
		});
	});
}








/***** behavior functions *****************************************************/

// select text input
$('#adminContainer').on('click', 'input.position', function(){
	$(this).select();
});

// hide all modalContainer(s) and overlay
$('body').on('click', 'div.overlay', function(){
	$(this).fadeOut();
	$('div.modalContainer').hide();
	return false;
});

// assign behavior to .showModal
$('body').on('click', '.showModal', function(e){
	var modal = $(this).attr("rel");
	var nextpage = $(this).attr("href");
	if(nextpage !== 'javascript:;' && nextpage !== '#'){
		if(modal.indexOf('?') !== -1){
			modal = modal+'&redirect='+encodeURIComponent(nextpage);
		}else{
			modal = modal+'?redirect='+encodeURIComponent(nextpage);
		}
	}
	showModal(modal);
	e.preventDefault();
});

// assign behavior to .closeBut (close parent div on click)
$('body').on('click', '.closeBut, .hideModal', function(){
    hideModal($(this));
    return false;
});

// display 'working' div while processing ajax requests
$('#working').bind('ajaxStart', function(){
    $(this).show();
}).bind('ajaxStop', function(){
    $(this).hide();
});

// if the value of textarea (for description texts) is changed, highlight "save changes" button
$('textarea.en, textarea.de').bind('input propertychange', function(){
	// check which one (en or de) was changed
	if($(this).hasClass("en")){
		var oldValue = $(this).parent().find('input.enMemory').val();
	}else if($(this).hasClass("de")){
		var oldValue = $(this).parent().find('input.deMemory').val();
	}
	// compare old and new value
	if($(this).val() != oldValue){ // it has changed
		$(this).parent().find('a.button.saveText').removeClass("disabled");
		if ($("#localMessage").length){
			$("#localMessage").remove();
		}
	}else{
		$(this).parent().find('a.button.saveText').addClass("disabled");
	}
});

// highlight current container (li) when a link is clicked that starts an ajx request
$('#structureContainer').on('click', 'ul.structure li a, ul.structure li input', function(){
	$(this).parent().addClass("active");
});
$('#structureContainer').on('mouseleave', 'ul.structure li a', function(){
	$(this).parent().removeClass("active");
});
$('#structureContainer').on('blur', 'ul.structure li input', function(){
	$(this).parent().removeClass("active");
});

// go to editor when click inside text or html file
$("div.txt.admin, div.html.admin").on('click', function(){
	var redirect = $(this).parent().find('a.button.edit').attr("href");
	//alert(redirect);
	window.location.href = redirect;
});

// show allowd tags tip
$('.tags').on("mouseenter", function(){
	//alert('hover');
	$(this).children("span.tagTip").show();
});
$('.tags').on("mouseleave", function(){
	//alert('out');
	$(this).children("span.tagTip").hide();
});


// show Modal window
function showModal(modal, callback){
	var $newDiv,
	    $overlayDiv,
		query = '';
		
	// create overlay if it does not exist
	if($('div.overlay').length == 0){
	    $overlayDiv = $('<div class="overlay"/>');
		$('body').append($overlayDiv);
	}else{
	    $overlayDiv = $('div.overlay');
	}
	$overlayDiv.fadeIn();
	// parse and check for query string (rel="zoomModal?img=/path/to/image.jpg") will append query string to loading modal.
	if(modal.indexOf('?') !== -1){
		var splitRel = modal.split("?");
		modal = splitRel[0];
		query =  '?'+splitRel[1];
		//alert(query);
	}
	// create modalContainer if it does not exist
	if($('div#'+modal).length == 0){
		$newdiv = $('<div class="modalContainer" id="'+modal+'"/>');
		$('body').append($newdiv);
	}else{
		$newdiv = $('div#'+modal);
	}
	$newdiv.load('/_code/admin/modals/'+modal+'.php'+query);
	$newdiv.show();
	checkModalHeight('#'+modal);
	if(callback !== undefined && typeof callback === 'function') {
        callback();
    }
}


function hideModal($elem){
    var n = $('div.modalContainer:visible').length;
	if(n > 0){
	    $elem.closest('div.modalContainer').hide();
	    n = n-1;
	}else{
	    $elem.closest('div').hide();
	}
	//alert(n);
    if(n < 1){
        $('div.overlay').fadeOut();
    }
}

// change positioning of modals to account for scrolling down window!
function checkModalHeight(elem){
    var scroltop = parseInt($(window).scrollTop());
    var newtop = scroltop+50;
    if(newtop<100){
	    newtop =  100;	
    }
    //alert(newtop);
    $(elem).animate({top: newtop},100);
}
