
// make sure nav height never desappears below page bottom (it is positioned fixed...)
function limitNavHeight(){
	var nH = $('#nav').height(); // recalculate nav height
	//alert(nH);
	if(nH > wH){
		//alert('too high!');
		if($('#nav').hasClass('collapsible')){
			$('#nav').removeClass('collapsible');
		}
		$('#nav').css({'height':wH+'px', 'overflow':'auto'});
		$('#nav ul').css('margin-right', 0);
	}
}

/* cookie functions */
function setCookie(c_name,value,exdays){
	var exdate=new Date();exdate.setDate(exdate.getDate()+exdays);
	var c_value=escape(value)+((exdays==null) ? "" : "; expires="+exdate.toUTCString()+"; path=/");
	document.cookie=c_name+"="+c_value;
}
/*
function getCookie(c_name){
	var c_value=document.cookie;var c_start=c_value.indexOf(" "+c_name+"=");
	if(c_start==-1){c_start=c_value.indexOf(c_name+"=");}
	if(c_start==-1){c_value=null;}
	else{c_start=c_value.indexOf("=",c_start)+1;
		var c_end=c_value.indexOf(";",c_start);
		if(c_end==-1){c_end=c_value.length;}
		c_value=unescape(c_value.substring(c_start,c_end));}
	return c_value;
}
*/

// get window width and height
var wW = $(window).width();
var wH = $(window).height();

// set cookies of window width nad height for later use
setCookie('wW', wW, 2);
setCookie('wH', wH, 2);


$(document).ready(function(){

	// get footer height
	var fH = $('#footer').outerHeight();
	// gte nav height
	var navH = $('#nav').outerHeight();

	limitNavHeight();

	// this var will detremine where the footer stands, when content container is empty
	var contentMinHeight = wH-fH-87;

	// if viewport width is less than 980px, 
	if (document.documentElement.clientWidth < 980) {
		contentMinHeight = wH-fH-60;
	}

	// if viewport width is less than 720px, 
	if (document.documentElement.clientWidth < 720) {

		contentMinHeight = wH-fH-100;
		
		// show/hide navigation for small screens
		$('#nav').on('click', function(){
			if($(this).hasClass('collapsible')){
				$(this).removeClass('collapsible').removeAttr("style");
			}else if($(this).height() == wH){ // collaspible class has been removed by limitNavHeight function, so just look for nav_height = window_height
				$(this).css({'height':navH+'px', 'overflow':'hidden'});
				$('#nav ul').css('margin-right', '10px');
			}else{
				$(this).addClass('collapsible').removeAttr("style");
				limitNavHeight();
			}
		});
		
		// avoid propagation of nav click if click on site title (#nav h1 a)
		$('#nav h1 a').click(function(event){
			event.stopPropagation();
		});
		
		// keep navigation visible if user just changed language (via url query ?lang=...)
		var query = window.location.search;
		if( query.match("lang=") ){
			$('#nav').addClass('collapsable');
			//limitNavHeight();
		}
	}

	// position footer at bottom of page even if no content
	$('#content').css('min-height', contentMinHeight+'px');


	// underline '.aMore' link when mouse over '.imgMore' (for sub-sections)
	$('div.divItem').on('mouseenter', 'a.imgMore', function(){
		$(this).closest('.divItem').children('.title').children('.aMore').css('text-decoration', 'underline');
	});
	// un-underline '.aMore' link when mouse over '.imgMore' (for sub-sections)
	$('div.divItem').on('mouseleave', 'a.imgMore', function(){
		$(this).closest('.divItem').children('.title').children('.aMore').css('text-decoration', '');
	});
});

