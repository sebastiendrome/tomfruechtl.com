
//alert('hello');

/* cookie functions */
function setCookie(c_name,value,exdays){
	var exdate=new Date();exdate.setDate(exdate.getDate()+exdays);
	var c_value=escape(value)+((exdays==null) ? "" : "; expires="+exdate.toUTCString()+"; path=/");
	document.cookie=c_name+"="+c_value;
}
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
var wW = $(window).width();
var wH = $(window).height();
//alert(wW+' x '+wH);
setCookie('wW',wW,2);
setCookie('wH',wH,2);

// position footer at bottom of page even if no content
var fH = $('#footer').outerHeight( true ); // true = includeMargin
$('#content').css('min-height', (wH-80-fH)+'px'); // 80 is 20 padding + 20 margin (20*4)

// limit nav height so it does not desappear below page bottom (it is positioned fixed)
var navH = $('#nav').outerHeight( true ); // true = includeMargin
//alert(navH);
if(navH > wH-20){
	$('#nav').css({'height':(wH-20)+'px', 'overflow':'auto'});
	$('#nav ul').css('padding-right', 0);
}

// underline '.aMore' link when mouse over '.imgMore' (for sub-sections)
$('div.divItem').on('mouseenter', 'a.imgMore', function(){
	$(this).closest('.divItem').children('.title').children('.aMore').css('text-decoration', 'underline');
});
// underline '.aMore' link when mouse over '.imgMore' (for sub-sections)
$('div.divItem').on('mouseleave', 'a.imgMore', function(){
	$(this).closest('.divItem').children('.title').children('.aMore').css('text-decoration', '');
});



// if viewport width is less than 720px, 
if (document.documentElement.clientWidth < 720) {
	
	// show/hide navigation for small screens
	$('#nav').on('click', function(){
		var navH = $(this).height();
		if(navH == 43){
			$(this).css('height', 'auto');
			navH = $(this).height(); // recalculate nav height now, and make sure it's not too high
			//wH = $(window).height();
			if(navH > wH-60){
				$('#nav').css({'height':(wH-20)+'px', 'overflow':'auto'});
				$('#nav ul').css('padding-right', 0);
			}
		}else{
			$(this).css('height', '43px');
		}
	});
	
	// avoid propagation of nav click if click on site title (#nav h1 a)
	$('#nav h1 a').click(function(event){
	  event.stopPropagation();
	});
	
	// keep navigation visible if user just changed language (via url query ?lang=...)
	var query = window.location.search;
	if( query.match("lang=") ){
		$('#nav').css('height', 'auto');
		navH = $('#nav').height(); // recalculate nav height now, and make sure it's not too high
		//wH = $(window).height();
		if(navH > wH-60){
			$('#nav').css({'height':(wH-20)+'px', 'overflow':'auto'});
			$('#nav ul').css('padding-right', 0);
		}
	}
}

