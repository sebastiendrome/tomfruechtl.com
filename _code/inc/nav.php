<?php

// generate navigation links depending on menu array of items from menu txt file
function make_nav($menu_array){
    $nav = '';
    foreach($menu_array as $key => $val){
		// ignore empty items and hidden items (whose name starts with underscore)
		if(!empty($key) && substr(basename($key),0,1) !== '_'){
			$class = '';
			$split = explode(',', $key);
			if(LANG=='en'){
				$name = $split[0];
			}elseif(LANG=='de'){
				$name = $split[1];
			}
			$dir = filename($split[0], 'encode');
	        $link = '/'.$dir.'/';
	        if($dir == SECTION || strstr(CONTEXT_PATH, '/'.$dir) ){
	            $class = ' class="selected"';
	        }
	        $nav .= '<li><a href="'.$link.'"'.$class.'>'.$name.'</a></li>'.PHP_EOL;
		}
    }
    return $nav;
}

$menu_array = menu_file_to_array(); 
$nav = make_nav($menu_array);

?>

<!-- start nav -->
<div id="nav">
    <ul>
        <li><h1><a href="/">Tom Früchtl</a></h1></li>
<?php 
echo $nav;
 ?>
     <li>&nbsp;</li>
     <li><a href="?lang=en"<?php if(LANG == 'en'){echo ' class="selected"';} ?> style="padding-right:0;"><?php echo FIRST_LANG; ?></a> | <a href="?lang=de"<?php if(LANG == 'de'){echo ' class="selected"';} ?> style="padding-left:0;"><?php echo SECOND_LANG; ?></a></li>
    </ul>
	<a id="mobileMenu" href="javascript:;"><img src="/_code/images/mobile-menu.svg" style="width:23px;" onerror="this.onerror=null; this.src='/_code/images/mob-nav.png'"></a>
</div><!-- end nav -->
