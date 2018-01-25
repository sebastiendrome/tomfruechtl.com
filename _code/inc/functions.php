<?php
// scan directory content, return array
function content_array($dir){
    $content_array = array();
    foreach(scandir($dir) as $file){
		// filter out system files
        if(substr($file,0,1) !== '.'){
            $content_array[$dir.'/'.$file] = $file;
        }
    }
	asort($content_array);
    $content_array = array_keys($content_array);
	rsort($content_array);
    return $content_array;
}


// read indented text file into multi-dimensional array
function menu_file_to_array($file = MENU_FILE, $indentation = "\t") {
	
	$contents = file_get_contents($file);

	$menu_array = array();
	$path = array();

	foreach (explode("\n", $contents) as $line) {
		// get depth and label
		$depth = 0;
		while (substr($line, 0, strlen($indentation)) === $indentation) {
			$depth += 1;
			$line = substr($line, strlen($indentation));
		}

		// truncate path if needed
		while ($depth < sizeof($path)) {
			array_pop($path);
		}

		// keep label (at depth)
		$path[$depth] = $line;

		// traverse path and add label to menu array
		$parent =& $menu_array;
		foreach ($path as $depth => $key) {
			if (!isset($parent[$key])) {
				$parent[$line] = array();
				break;
			}
			$parent =& $parent[$key];
		}
	}
	//print_r($menu_array);
	return $menu_array;
}


// show section content, or subsection content. Data comes from $menu_array, generated from reading 
// file menu.txt via above function
function display_content_array($path, $menu_array = ''){
	
	// initialize output
    $display = '';
	
	// generate menu array from menu.txt file
	if( empty($menu_array) ){
		$menu_array = menu_file_to_array();
		
		// get current directory (=section)
		$depth = basename($path);
		
		// attempt to match current directory (=section) to first depth of menu_array (=menu_array[key])
		// and generate sub-array of items accordingly
		foreach($menu_array as $k => $v){
			//$display .=  $k.'<br>';
			if( preg_match('/^'.preg_quote(filename($depth, 'decode')).',/', $k) ){
				$depth_array = $menu_array[$k];
				$split = explode(',', $k); // split two sides of the sub-section name, to get english and german versions
				break;
			}
		}
		// if no match, attempt to match current directory (=section) to second depth of menu_array(=menu_array[key][val])
		// and generate sub-sub array of items accordingly
		if(!isset($depth_array)){
			foreach($menu_array as $k => $v){
				foreach($v as $vk => $vv){
					if( preg_match('/^'.preg_quote(filename($depth, 'decode')).',/', $vk) ){
						$depth_array = $menu_array[$k][$vk];
						$split = explode(',', $vk); // split two sides of the sub-section name, to get english and german versions
						break;
					}
				}
			}
			// language dependent title for this sub-section (get german title from $menu_array)
			if(LANG == 'en'){
				$subsection_title = filename(SECTION, 'decode');
			}elseif(LANG == 'de') {
				$subsection_title = trim($split[1]);
			}
			
	        $display .= '<div class="backTitle">
			<ul><li><!--<a href="'.str_replace('/'.basename($_SERVER['REQUEST_URI']), '', $_SERVER['REQUEST_URI']).'">-->
			<a href="javascript:window.history.back();">&larr; '.BACK.'</a> | <u>'.$subsection_title.'</u></li></ul>
	        </div>
	        <p class="title">&nbsp;</p>'.PHP_EOL;
		}
		
		// now we can recreate menu_array so it is the proper array of items depending on current directory depth.
		$menu_array = $depth_array;
	}
	
	
	// loop through menu_array to display the content
    foreach($menu_array as $key => $val){
        
		// filter out hidden files/folders (whose name starts with underscore)
        if( substr(basename($key),0,1) !== '_' && !empty($key) ){
            
			// open item container
			$display .= '<div class="divItem"><!-- start div item container -->'.PHP_EOL;
			
			// does item represent a file or a folder?
			if(!strstr($key, ',')){ // file
				
				$display_file = display_file($path, $key);
				
				// get text description english or deutsch version depending on LANG (cookie)
				$ext = file_extension($key);
				$txt_filename = preg_replace('/'.preg_quote($ext).'$/', '.txt', $key);
				$text_file = $path.'/'.LANG.'/'.$txt_filename;
				if( file_exists($text_file) ){
					$description = stripslashes( file_get_contents($text_file) );
				}else{
					$description = '';
				}
				
				// display file and description
				$display .= $display_file;
				$display .= '<p class="description">'.$description.'</p>';

				
			}else{ // folder = sub-section. show sub-section name and its first file.
				
				// langage dependent title for this subsection
				$split = explode(',', $key);
				if(LANG == 'en'){
					$sec_name = $split[0];
				}elseif(LANG == 'de'){
					$sec_name = trim($split[1]);
				}
				$sec_dir = filename($split[0], 'encode');
				
				// get the first file in subfolder to represent this subsection.
				// avoid repeating same file through loop, if subsequent passages don't finde a 1st file... 
				if(isset($first_file)){
					unset($first_file);
				}
				foreach($val as $k => $v){
					$first_file = $path.'/'.$sec_dir.'/'.SIZE.'/'.$k;
					break;
				}
				// display sub-section name and file only if a first file has been found
				if( isset($first_file) ){
					//echo $sec_dir;
					$url_link = str_replace(ROOT.CONTENT, '', $path);
					$display .= '<p class="title"><a href="/'.$url_link.'/'.$sec_dir.'/" class="aMore">'.$sec_name.' | &rarr; '.MORE.'</a></p>';
					
					// if optional 3rd var is TRUE, display file without enclosing <a> tag.
					$display_file = display_file($path.'/'.$sec_dir, $k, TRUE);
					
					$display .= '<a href="/'.$url_link.'/'.$sec_dir.'/" class="imgMore">'.$display_file.'</a>';
				}
			}
			
			$display .= '</div><!-- end div item container -->'.PHP_EOL; // close item container
		}
	}
	return $display;
}


// display file
// if optional var $raw is TRUE, display file without enclosing <a> tag.
function display_file($path, $file_name, $raw = FALSE){
	
	$ext = file_extension($file_name);
	
	// get text description english and deutsch versions
	$txt_filename = preg_replace('/'.preg_quote($ext).'/', '.txt', $file_name);
	$text_file = $path.'/'.LANG.'/'.$txt_filename;
	
	if( file_exists($text_file) ){
		$description = stripslashes( file_get_contents($text_file) );
		$alt_content = substr( str_replace(array('\"', "\'"), array('&#34;', '&#39;'), strip_tags($description) ), 0, 30);
		$alt = ' alt="'.$alt_content.'"';
	}else{
		$description = '';
		$alt = ' alt="'.$file_name.'"';
	}
	
	// various ways to display file depending on extension
	if( preg_match($_POST['types']['resizable_types'], $ext) ){ // images
		$item = $path.'/'.SIZE.'/'.$file_name;
		list($w, $h) = getimagesize($item);
		// url link to file
		$file_link = str_replace(ROOT, '', $item);
		// 'raw' or not: with surrounding zoom <a> link
		if($raw){
			$start_link = $end_link = '';
		}else{
			$start_link = '<a href="/_zoom.php?img='.urlencode($file_link).'" class="zoom">';
			$end_link = '</a>';
		}
		
		$display_file = $start_link.'<img src="/'.$file_link.'"'.$alt.' style="max-width:'.$w.'px">'.$end_link;
		
	}else{
		// if not an image, the file is in the _XL directory (no various sizes)
		$item = $path.'/_XL/'.$file_name;
		// url link to file
		$file_link = str_replace(ROOT, '' , $item);
		
		if( preg_match($_POST['types']['audio_types'], $ext) ){ // audio, show <audio>
			$item = $path.'/_S/'.$file_name;
			if($ext == '.mp3' || $ext == '.mpeg'){
				$media_type = 'mpeg';
			}elseif($ext == 'ogg'){
				$media_type = 'ogg';
			}elseif($ext == 'wav'){
				$media_type = 'wav';
			}
			$display_file = '<audio controls>
			<source src="/'.$file_link.'" type="audio/'.$media_type.'">
			<a href="/'.$file_link.'" title="open in new window" target="_blank"><img src="/_code/images/'.substr($ext, 1).'.png"></a>
			</audio>';
		
		}elseif( preg_match($_POST['types']['text_types'], $ext) ){ // text files (html or txt)
			$contents = file_get_contents($item);
			// detect if there is a language version to link to
			if( preg_match('/\[[a-zA-Z]*\.version\]/', $contents, $matches) ){
				$lang = str_replace(array('.version', '[', ']'), '', $matches[0]);
				$contents = str_replace($matches[0], '<a name="'.$lang.str_replace($ext,'',$file_name).'"></a>', $contents);
				$contents = '<a href="#'.$lang.str_replace($ext,'',$file_name).'" class="langLink">&darr; '.$lang.' version</a>'.$contents;
			}
			if($ext == '.txt'){ // txt
				$display_file = '<div class="txt">'.my_nl2br( strip_tags( $contents, ALLOWED_TAGS ) ).'</div>';
				
			}elseif($ext == '.html'){ // html
				if( preg_match('/(?:<body[^>]*>)(.*)<\/body>/isU', $contents, $matches) ){
					$display_file = '<div class="txt">'.$matches[1].'</div>';
				}else{
					$display_file = '<div class="txt">'.$contents.'</div>';
				}
			
			}
		}else{
			$display_file = '<a href="/'.$file_link.'" title="open in new window" target="_blank"><img src="/_code/images/'.substr($ext,1).'.png" class="icon"></a>';
		}
	}
	if( !isset($display_file) || empty($display_file) ){
		$display_file = '<p class="error">Cannot display '.$path.$file_name.'</p>';
	}
	return $display_file;
}


// CUSTOM nl2br
function my_nl2br($content){
	$content = str_replace(array("\r\n","\r","\n"),'<br>',$content);
	return $content;
}
// CUSTOM br2nl
function my_br2nl($content){
	$content = str_replace('<br>', "\n", $content);
	return $content;
}

// ENCODE STRING TO SAFE FILENAME
function filename($string, $de_encode){
	$char = array
	(
		' ', '/', '\\', '(', ')', '[', ']', '{', '}', '|', '<', '>', '*', '#', '%', '&', '$', '@', '+', '!', '?', ',', '.', ';', ':', '"', "'", '‘', '’', '“', '”', '‛', '‟', '′', '″', '©', 'ç', 'à', 'á', 'â', 'ã', 'ä', 'Ä', 'Ö', 'Ü', 'ß', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ĩ', 'ï', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'ü', 'û'
	);
	$rep =  array
	(
		'qZ','zFSz','zBSz','zOPz','zCPz','zOBz','zCBz','zOAz','zCAz','zVLz','zPz','zNz','zSRz','zPDz','zPTz','zAz','zDRz','zATz','zPSz','zEPz','zQz','zCz','zDz','zSCz','zCNz','zQTz','zSQz','zSQDz','zSQUz','zQDz','zQUz','zSQFz','zQFz','zAFz','zDAFz','zCYz','qCCq','qAGq','qAAq','qACq','qATq','qADq','QADQ','QODQ','QUDQ','qSSq','qEGq','qEAq','qECq','qEDq','qIGq','qIAq','qICq','qITq','qIDq','qOGq','qOAq','qOCq','qOTq','qODq','qUGq','qUAq','qUCq','qUDq'
	);
	if($de_encode == 'encode'){
		foreach($char as $key => $value){
			$string = str_replace($value, $rep[$key], $string);
		}
	}elseif($de_encode == 'decode'){
		foreach($rep as $key => $value){
			$string = str_replace($value, $char[$key], $string);
		}
	}
	return $string;
}

// get file name without extension
function file_name_no_ext($file_name){
	if( strstr($file_name, '/') ){
		$file_name = basename($file_name);
	}
	$file_name_no_ext = preg_replace('/\.[a-zA-Z]*$/', '', $file_name);
	return $file_name_no_ext;
}

// get file extension from file name
function file_extension($file_name){
	preg_match('/\.[a-zA-Z]*$/', $file_name, $matches);
	if( !empty($matches) ){
		return $matches[0];
	}else{
		return false;
	}
}

// get directory name from bilingual section name ("english name, Deutsch Name" => "english_name")
function dir_from_section_name($section) {
	$split = explode(',', $section);
	$dir_name = filename($split[0], 'encode');
	return $dir_name;
}

// check if a folder is empty or not. Returns "true" if it is empty
function is_empty_folder($dir){
	if(is_dir($dir)){
		$dir_contents = glob("$dir/*");
		foreach($dir_contents as $s){
			if(!preg_match('/^\./',basename($s))){
				$filtered[] = $s;
			}
		}
		if (count($filtered) == 0){
			return true;
		}else{
			return false;
		}
	}elseif(is_file($dir)){
		$f_size = filesize($dir);
		if($f_size < 5){
			return true;
		}else{
			return false;
		}
	}
}
