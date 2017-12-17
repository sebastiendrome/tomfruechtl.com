
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

// underline '.aMore' link when mouse over '.imgMore' (for sub-sections)
$('div.divItem').on('mouseenter', 'a.imgMore', function(){
	$(this).closest('.divItem').children('.title').children('.aMore').css('text-decoration', 'underline');
});
// underline '.aMore' link when mouse over '.imgMore' (for sub-sections)
$('div.divItem').on('mouseleave', 'a.imgMore', function(){
	$(this).closest('.divItem').children('.title').children('.aMore').css('text-decoration', '');
});




