<?php
/*********** 1: UTILITARIAN FUNCTIONS (USED WITHIN OTHER FUNCTIONS) ***************/

// COPY DIRECTORY AND ITS CONTENTS
function copyr($source, $dest){
    if (is_file($source)) {// Simple copy for a file
        return copy($source, $dest);
    }
    if (!is_dir($dest)) {// Make destination directory
        mkdir($dest,0777);
    }
    $dir = dir($source);// Loop through the folder
    while (false !== $entry = $dir->read()) {
        if ($entry == '.' || $entry == '..') {// Skip pointers
            continue;
        }
        if ($dest !== "$source/$entry") {// Deep copy directories
            copyr("$source/$entry", "$dest/$entry");
        }
    }
    $dir->close();// Clean up
    return true;
}

//FUNCTION TO REMOVE DIRECTORY AND ITS CONTENTS:
function rmdirr($dirname){
    if (!file_exists($dirname)){// Sanity check
        return false;
    }
    if (is_file($dirname)){// Simple delete for a file
        return unlink($dirname);
    }
    $dir = dir($dirname);// Loop through the folder
    while (false !== $entry = $dir->read()){
        if ($entry == '.' || $entry == '..'){// Skip pointers
            continue;
        }
        rmdirr("$dirname/$entry");// Recurse
    }
    $dir->close();// Clean up
    return rmdir($dirname);
}

// get human file size
function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

// validate new section name (format: "english, Deutsch")
function validate_section_name($newName){
	//$newName = str_replace("\\", '', $newName);
	if(!strstr($newName, ',')){
		$newName .= ', '.$newName;
	}else{
		$split = explode(',', $newName);
		$en = trim($split[0]);
		$de = trim($split[1]);
		if(empty($de)){
			$newName = $en.', '.$en;
		}elseif(empty($en)){
			$newName = $de.', '.$de;
		}
	}
	$newName = strip_tags($newName);
	// remove dangerous caracters
	$newName = str_replace(array("\\", "\t", "\n", "\r", "(", ")", "/"), '', $newName);
	return $newName;
}

// sanitize user input
function sanitize_text($input){
	$input = 
	preg_replace('/on(load|unload|click|dblclick|mouseover|mouseenter|mouseout|mouseleave|mousemove|mouseup|keydown|pageshow|pagehide|resize|scroll)[^"]*/i', '', $input);
	$input = addslashes( strip_tags($input, ALLOWED_TAGS) );
	//$input = preg_replace('/(#|?/i', '', $input);
	return $input;
}
/*
// sanitize html content
function sanitize_html($input){
	
}
*/

// display file
function display_file_admin($path, $file_name){
	$ext = file_extension($file_name);
	
	// various ways to display file depending on extension
	if( preg_match($_POST['types']['resizable_types'], $ext) ){
		$item = $path.'/_S/'.$file_name;
		// url link to file
		$file_link = str_replace(ROOT, '' , $item);
		$display_file = '<a href="/'.str_replace('/_S/', '/_XL/', $file_link).'" title="open in new window" target="_blank"><img src="/'.$file_link.'?rand='.rand(111,999).'" id="'.$file_name.'"></a>';
		
	}else{
		// if not an image, the file is in the _XL directory (no various sizes)
		$item = $path.'/_XL/'.$file_name;
		// url link to file
		$file_link = str_replace(ROOT, '' , $item);
		
		if( preg_match($_POST['types']['audio_types'], $ext) ){ // audio, show <audio>
			
			if($ext == '.mp3' || $ext == '.mpeg'){
				$media_type = 'mpeg';
			}elseif($ext == 'ogg'){
				$media_type = 'ogg';
			}elseif($ext == 'wav'){
				$media_type = 'wav';
			}
			$display_file = '<audio controls>
			<source src="/'.$file_link.'" type="audio/'.$media_type.'">
			<a href="/'.$file_link.'" title="open in new window" target="_blank"><img src="/_code/images/'.substr($ext, 1).'.png" id="'.$file_name.'"></a>
			</audio>';
		
		}elseif($ext == '.txt'){ // txt
			$display_file = '<div class="txt admin">'.my_nl2br( strip_tags( file_get_contents($item) , ALLOWED_TAGS ) ).'</div>';
		
		}elseif($ext == '.html'){ // html
			$display_file = '<div class="html admin">'.strip_tags( file_get_contents($item) , ALLOWED_TAGS ).'</div>';
		
		}else{
			$display_file = '<a href="/'.str_replace('/_S/', '/_XL/', $file_link).'" title="open in new window" target="_blank"><img src="/_code/images/'.substr($ext,1).'.png" id="'.$file_name.'"></a>';
		}
	}
	if( !isset($display_file) || empty($display_file) ){
		$display_file = '<p class="error">Cannot display '.$path.$file_name.'</p>';
	}
	return $display_file;
}


// generate menu file output from 3D array
function array_to_menu_file($menu_array){
	$menu_file = '';
	foreach($menu_array as $key => $val){
		if(!empty($key)){ // don't generate empty lines
			$menu_file .= $key."\n";
			if(!empty($val)){ // don't generate empty lines
				foreach($val as $k => $v){
					$menu_file .= "\t".$k."\n";
					if(!empty($v)){
						foreach($v as $vk => $vv){
							$menu_file .= "\t\t".$vk."\n";
						}
					}
				}
			}
		}
	} 
	return $menu_file;
}

// insert item in associative array at specific position
function insert_at($array = [], $item = [], $position = 0) {
	$previous_items = array_slice($array, 0, $position, true);
	$next_items     = array_slice($array, $position, NULL, true);
	return $previous_items + $item + $next_items;
}

/*** !!!!!! DOES NOT WORK !!!!!! ***/
// find key in multi-dimensional array, 
// return array of parent(s) of key (or empty array if key is in first level, or false if key is not found)
function array_keys_deep($deep_array, $path_array, $n = 0, $length){
	if($n < $length){
		foreach($deep_array as $k => $v){
			if( strstr($k, filename($path_array[$n], 'decode').',') ){
				$return_array[$n] = $k;
				if( is_array($v) ){
					$n++;
					$return_array[$n] = array_keys_deep($v, $path_array, $n);
				}
			}
		}
	}
	return $return_array;
}






/*********** 2: DISPLAY FUNCTIONS (FUNCTIONS THAT OUTPUT HTML MARKUP) ***************/

// display site structure from menu array
function site_structure($menu_array = '', $currentItem = ''){
	
	// generate menu array from menu.txt file
	if( empty($menu_array) ){
		$menu_array = menu_file_to_array();
	}
	
	$n = $s = 0; // n will increment sections items, s will increment sub-sections items
	$site_structure = '<ul class="structure">'.PHP_EOL;
	
	foreach($menu_array as $key => $var){
		if(!empty($key)){ // ignore empty items
			
			$n++;
			$s = 0;
			
			// get dir name from $key ($en = english version is also the directory name)
			list($en, $de) = explode(',', $key);
			$path = CONTENT.filename($en, 'encode');
			
			// hidden vs. visible sections
			if(substr($key,0,1) == '_'){ // detect hidden sections
				// remove _ (underscore) from name
				$name =  substr($key, 1);
				$status = ' class="hidden"';
				$show_hide = '[+]show';
				$sh_class = 'show';
				$sh_title = 'make this section visible to the public.';
			}else{
				$name = $key;
				$status = '';
				$show_hide = '[-]hide';
				$sh_class = 'hide';
				$sh_title = 'hide this from the public, without deleting it.';
			}
			
			// html output for a section
			$site_structure .= '<li'.$status.' data-name="'.$key.'" data-position="'.$n.'">
			<input type="text" class="nameInput" name="'.$key.'" value="'.$name.'"> <input type="text" class="position" name="order'.$key.'" value="'.$n.'" data-parent="undefined" data-item="'.$key.'" data-oldposition="'.$n.'">
			<a href="javascript:;" class="up" title="move up">∧</a>
			<a href="javascript:;" class="down" title="move down">∨</a> 
			<a href="manage_contents.php?item='.$path.'">edit content</a> <a href="javascript:;" class="newSub showModal" rel="createSection?parent='.urlencode($key).'">↓create sub-section</a>
			<a href="javascript:;" class="'.$sh_class.'" title="'.$sh_title.'">'.$show_hide.'</a> 
			<a href="javascript:;" class="delete showModal" rel="deleteSection?deleteSection='.urlencode($key).'">[x]delete</a>'.PHP_EOL;
			
			if(!empty($var)){ // section contains something
				
				$site_structure .= '<ul>'.PHP_EOL;
				
				// section contents
				foreach($var as $k => $v){
					
					// ignore empty items
					if(!empty($k)){
						
						$s++;
						
						// directories (=sub-sections)
						if(strstr($k, ',')){
							$split = explode(',', $k);
							$subpath = '/'.filename($split[0], 'encode');
							
							// hidden vs. visible sub-sections
							if(substr($k,0,1) == '_'){  // detect hidden sub-sections
								// remove _ (underscore) from name
								$name =  substr($k, 1);
								$status = ' hidden';
								$show_hide = '[+]show';
								$sh_class = 'show';
							}else{
								$name = $k;
								$status = '';
								$show_hide = '[-]hide';
								$sh_class = 'hide';
							}
							
							// html output for a sub-section
							$site_structure .= '<li class="sub'.$status.'" data-name="'.$k.'" data-parent="'.$key.'" data-position="'.$s.'"><input type="text" class="nameInput" name="'.$k.'" value="'.$name.'"> <input type="text" class="position" name="order'.$k.'" value="'.$s.'" data-parent="'.$key.'" data-item="'.$k.'" data-oldposition="'.$s.'"> 
							<a href="javascript:;" class="up" title="move up">∧</a> 
							<a href="javascript:;" class="down" title="move down">∨</a> 
							<a href="manage_contents.php?item='.$path.$subpath.'">edit content</a> <a href="javascript:;" class="'.$sh_class.'" title="hide this from the public, without deleting it.">'.$show_hide.'</a> <a href="javascript:;" class="delete showModal" rel="deleteSection?parent='.urlencode($key).'&deleteSection='.urlencode($k).'">[x]delete</a></li>'.PHP_EOL;
						
						// files
						}else{
							
							// get file extension (including dot: ".jpg")
							$ext = file_extension($k);
							// various ways to display file depending on extension
							if( preg_match($_POST['types']['resizable_types'], $ext) ){
								// url to file
								$file = CONTENT.filename($en, 'encode').'/_S/'.$k;
								$display_file = $file;
							}else{
								// url to file
								$file = CONTENT.filename($en, 'encode').'/_XL/'.$k;
								$display_file = '_code/images/'.substr($ext,1).'.png';
							}
							
							$txt_file_name = preg_replace('/'.preg_quote($ext).'/', '.txt', $k);
							$txt_file = ROOT.CONTENT.filename($en, 'encode').'/en/'.$txt_file_name;
							if( file_exists($txt_file) ){
								$description = strip_tags( file_get_contents($txt_file) );
							}else{
								$description = '';
							}
							
							if(empty($description)){
								$description = filename($k, 'decode');
							}else{
								$description = str_replace(array("\'", '\"'), array('&#39;','&quot;'), $description);
								$description = substr($description, 0, 35);
							}
							$site_structure .= '<li data-name="'.$k.'" data-position="'.$s.'"><span class="imgInput" style="background-image:url(/'.$display_file.');">'.$description.'</span>
							<input type="text" class="position" name="order'.$k.'" value="'.$s.'" data-parent="'.$key.'" data-item="'.$k.'" data-oldposition="'.$s.'">
							<a href="javascript:;" class="up" title="move up">∧</a>
							<a href="javascript:;" class="down" title="move down">∨</a> 
							<a href="manage_contents.php?item='.$path.'#'.preg_replace('/[^A-Za-z0-9]/', '', $k).'">edit</a> 
							<a href="javascript:;" class="delete showModal" rel="deleteFile?parent='.urlencode($key).'&file='.urlencode(ROOT.$file).'">[x]delete</a></li>'.PHP_EOL;
						}
						
						
						
						
					}
				}
				$site_structure .= '</ul>'.PHP_EOL;
			}
			$site_structure .= '</li>'.PHP_EOL;
		}
	}
	$site_structure .= '</ul>'.PHP_EOL;
	return $site_structure;
}


// display section or sub-section contents
function display_content_admin($path = '', $menu_array = ''){
	
	// if no path provided, use SESSION[item] if possible.
	if( empty($path) ){
		if( isset($_SESSION['item']) && !empty($_SESSION['item']) ){
			$path = ROOT.$_SESSION['item'];
		}else{ // if no session, then we can't know the path, so let's just display an error message.
			$display = '<p class="error">No SESSION[item], not path!...</p>';
			return $display;
		}
	}
	
	// if no menu_array provided, generate menu array from menu.txt file
	if( empty($menu_array) ){
		$menu_array = menu_file_to_array();
		
		// get current directory (=section)
		$depth = basename($path);
		
		// attempt to match current directory (=section) to base level of menu_array (=menu_array[key])
		// and generate sub-array of items accordingly
		foreach($menu_array as $k => $v){
			//$display .=  $k.'<br>';
			if( preg_match('/^'.preg_quote(filename($depth, 'decode')).',/', $k) ){
				$parent = $k;
				$depth_array = $menu_array[$k];
				$split = explode(',', $k); // split two sides of the sub-section name, to get english and german versions
				break;
			}else{
				// else, attempt to match current directory to second level of menu_array(=menu_array[key][val])
				// and generate sub-sub array of items accordingly
				foreach($v as $vk => $vv){
					if( preg_match('/^'.preg_quote(filename($depth, 'decode')).',/', $vk) ){
						$parent = $k.'/'.$vk;
						$depth_array = $menu_array[$k][$vk];
						$split = explode(',', $vk); // split two sides of the sub-section name, to get english and german versions
						break;
					}
				}
			}
		}
	}
	
	
	$display = '<ul class="content">';
	$n = 0;
	//$display .= '<pre>'.print_r($depth_array).'</pre>';
	
	// loop through the files (images?) if there are any
	if( !empty($depth_array) ){
		foreach($depth_array as $key => $val){
			
			// ignore empty array keys (empty line in menu.txt file)
			if( !empty($key) ){
				
				$n++;
				$display .= '<li><a name="'.preg_replace('/[^A-Za-z0-9]/', '', $key).'"></a>'.PHP_EOL;
				
				// FILES
				if( !strstr($key, ',') ){
					
					$ext = file_extension($key);
					$item = $path.'/_S/'.$key; // default
					
					$display_file = display_file_admin($path, $key);
					
					// various ways to display file depending on extension
					if( !preg_match($_POST['types']['resizable_types'], $ext) ){
						$item = $path.'/_XL/'.$key;
					}
					$file_link = str_replace(ROOT, '' , $item);
					
					// get text description english and deutsch versions
					$txt_filename = preg_replace('/'.preg_quote($ext).'/', '.txt', $key);
					$en_file = $path.'/en/'.$txt_filename;
					$de_file = $path.'/de/'.$txt_filename;
					
					// create txt files if they don't already exist
					if(!file_exists($en_file)){
						if(!$fp = fopen($en_file, "w")){
							echo '<p class="error">could not create EN text file</p>';
						}
					}
					if(!file_exists($de_file)){
						if(!$fp = fopen($de_file, "w")){
							echo '<p class="error">could not create DE text file</p>';
						}
					}
					// get content of text files
					$en = stripslashes( my_br2nl( file_get_contents($en_file) ) );
					$de = stripslashes( my_br2nl( file_get_contents($de_file) ) );
					
					// html output for a file
					$display .= '<div class="imgContainer" data-file_path="'.$item.'"><p>
					<input type="text" class="position" name="order'.$item.'" value="'.$n.'" data-oldposition="'.$n.'" data-parent="'.$parent.'" data-item="'.$key.'">
					<a href="javascript:;" class="up" title="move up">∧</a>
					<a href="javascript:;" class="down" title="move down">∨</a><!-- '.filename($key, 'decode').'--></p>';
					$display .= $display_file;
					$display .= '<p>
					<!--<a href="/_code/admin/rotate_image.php?image='.$item.'" class="button rotate" data-image="'.$item.'" style="margin-left:0;"><img src="images/img-rotate.png" style="border:none; background:none; display:inline;"> rotate</a>-->
					<a href="javascript:;" class="button replace showModal" style="margin-left:0;"  rel="newFile?path='.urlencode(ROOT.$_SESSION['item']).'&replace='.urlencode($item).'">replace</a> 
					<a href="javascript:;" class="button cancel showModal" rel="deleteFile?file='.urlencode($item).'" style="margin-right:0;">delete</a>';
					if( preg_match($_POST['types']['text_types'], $ext) ){ // txt
						$display .= '<a class="button submit" href="/_code/admin/editText.php?item='.urlencode($item).'" style="float:right;">Edit Text</a>';
					}
					
					$display .= '</p>
					</div>';
					// texts
					$display .= '<div class="actions">
					<input type="hidden" class="file" value="'.$item.'">
					<p>description: <span class="tags">allowed tags: &lt;b>&lt;u>&lt;i>&lt;a> <span class="tagTip">&lt;b><b>bold</b>&lt;/b> &lt;u><u>underline</u>&lt;/u> &lt;i><i>italic</i>&lt;/i> &lt;a&nbsp;href="http://yourlink.com">link&lt;/a></span></span></p>';
					
					$display .= '↓'.FIRST_LANG.'<br>
					<input type="hidden" class="enMemory" value="'.$en.'">
					<textarea class="en" name="en_txt" maxlength="300">'.$en.'</textarea>
					↓'.SECOND_LANG.'<br>
					<input type="hidden" class="deMemory" value="'.$de.'">
					<textarea class="de" name="de_txt" maxlength="300">'.$de.'</textarea>
					<a href="javascript:;" class="button submit saveText disabled" style="float:right; margin-top:10px; margin-right:0;">Save changes</a>';
					$display .= '</div>
					<div style="clear:both;">&nbsp;</div>';
					
				
				}else{ // directory = sub-section
					
					$item = basename($path);
					$sub_item = $key;
					$split = explode(',', $sub_item);
					$sub_dir = filename($split[0], 'encode');
					$section_name = filename(basename($sub_item), 'decode');

					$display .= '<div style="float:left; width:400px;" data-name="'.$section_name.'" data-parent="'.$parent.'">';
					
					// html output for a sub-section
					$display .= '<p>
					<input type="text" class="position" name="order'.$sub_item.'" value="'.$n.'" data-oldposition="'.$n.'" data-parent="'.$parent.'" data-item="'.$sub_item.'">
					<a href="javascript:;" class="up" title="move up">∧</a>
					<a href="javascript:;" class="down" title="move down">∨</a> 
					&nbsp;&nbsp;&nbsp;<strong>Sub-section:</strong></p>
					<input type="text" class="nameInput" name="'.$sub_item.'" value="'.$section_name.'">';
					
					$display .= '</div>';
					
					foreach($val as $k => $v){
						$first_file = $path.'/'.$sub_dir.'/_S/'.$k;
						break;
					}
					// display sub-section name and file only if a first file has been found
					if(isset($first_file)){
						$first_file_link = str_replace(ROOT, '/', $first_file);
						$ext = file_extension($k);
						// various ways to display file depending on extension
						if( preg_match($_POST['types']['resizable_types'], $ext) ){
							$display_file = '<img src="'.$first_file_link.'" alt="'.$sub_item.'" style="display:block; float:left; width:160px; margin-right:50px;">';
						}else{
							$display_file = '<div style="float:left; width:160px; margin-right:50px; height:80px; padding-top:45px; border:1px solid #ccc; overflow:hidden;">'.basename($first_file_link).'</div>';
						}
						$display .= $display_file;
						unset($first_file); // make sure first_file doesn't stay set for next sub-section through the foreach loop
					}
					
					$display .= '<div style="float:left; width:400px;">
					<p>&nbsp;</p>
					<a href="?item='.urlencode(CONTENT.$item.'/'.$sub_dir).'">edit content</a>
					</div>';
					
					$display .= '<div class="clearBoth">&nbsp;</div>';
				}
				$display .= '</li>';
				
			}
		}
	}else{
		$display .= '<li>This section is empty...</li>';
	}
	
	$display .= '</ul>';
	
	return $display;
}






/*********** 3: ACTIVE FUNCTIONS (FUNCTIONS THAT CHANGE THE CONTENT) ***************/

// change section or sub-section name (update menu.txt AND rename directory)
function update_name($oldName, $newName, $parent, $adminPage){
	$result = $menu_array = $output = $error = '';

	if(!empty($oldName) && !empty($newName)){
		
		$contents = file_get_contents(MENU_FILE);
		
		// validate new name
		$newName = validate_section_name($newName);
		
		// get ready to rename section directory
		$old_dir = dir_from_section_name($oldName);
		$new_dir = dir_from_section_name($newName);
		
		if( $parent != 'undefined' && !empty($parent) ){ // sub-section!
			$old = "\t".$oldName."\n";
			$new = "\t".$newName."\n";
			$parent_dir = dir_from_section_name($parent);
			$old_dir = $parent_dir.'/'.$old_dir;
			$new_dir = $parent_dir.'/'.$new_dir;
			
		}elseif( strstr($contents, $oldName."\n") ){ // no sub-section!
			$old = $oldName."\n";
			$new = $newName."\n";
		
		}else{
			$error .= '<p class="error">ERROR: No match!</p>';
		}
		
		$new_contents = str_replace($old, $new, $contents);
		
		if($fp = fopen(MENU_FILE, "w")){
			fwrite($fp, $new_contents);
			fclose($fp);
			if( !rename(ROOT.CONTENT.$old_dir, ROOT.CONTENT.$new_dir) ){
				$error .= '<p class="error">ERROR: Could not rename '.ROOT.CONTENT.$old_dir.' to '.ROOT.CONTENT.$new_dir.'</p>';
			}
		}else{
			$error .= '<p class="error">ERROR: Could not open '.MENU_FILE.'</p>';
		}
		
	}else{
		$error .= '<p class="error">ERROR: Empty name!</p>';
	}
	if(!empty($error)){
		$result .= $error;
	}else{
		//$menu_array = menu_file_to_array();
		//$currentItem = $newName;

		// generate html output for manage structure admin page
		if($adminPage == 'manage_structure'){
			$output = site_structure();
		// generate html output for manage content admin page
		}elseif($adminPage == 'manage_contents'){
			$output = display_content_admin();
		}

		$result .= $output;
	}

	echo $result;
	
}


// change section or sub-section position (update menu.txt)
function update_position($item, $oldPosition, $newPosition, $parent, $adminPage){
	$result = $output = $error = '';
	
	if( !empty($item) && !empty($oldPosition) && !empty($newPosition) ){
		
		// generate 3D array from menu file
		$menu_array = menu_file_to_array();
		$newPos = $newPosition-1; // arrays start with 0, not 1
		$oldPos = $oldPosition-1;
		
		// update $menu_array:
		// if a single section, remove array key and re-insert it to proper position
		if($parent == 'undefined' || empty($parent) ){
			
			// create array key item => values 
			$insert_array = array($item => $menu_array[$item]);
			// unset this key from menu array
			unset($menu_array[$item]);
			// insert it at new position
			$menu_array = insert_at($menu_array, $insert_array, $newPos);
		
		// if a sub-section: remove sub array key and re-insert it to proper position
		}elseif($parent != 'undefined' && !empty($parent) ){
			
			// only one parent
			if( !strstr($parent, '/') ){
				// duplicate parent array key into new array $inner_array
				$inner_array = $menu_array[$parent];
				// create array
				$insert_array[$item] = $menu_array[$parent][$item];
				// unset item key in $inner_array
				unset($inner_array[$item]);
				// insert it in new position into $inner_array
				$inner_array = insert_at($inner_array, $insert_array, $newPos);
				
				// update parent array key 
				$menu_array[$parent] = $inner_array;
			
			// 2 parents	
			}else{
				$split_parents = explode('/', $parent);
				$parent_1 = $split_parents[0];
				$parent_2 = $split_parents[1];
				/*
				$error .= $menu_array[$parent_1][$parent_2];
				echo $error;
				exit;
				*/
				// duplicate parent array key into new array $inner_array
				$inner_array = $menu_array[$parent_1][$parent_2];
				// create array
				$insert_array[$item] = $menu_array[$parent_1][$parent_2][$item];
				// unset item key in $inner_array
				unset($inner_array[$item]);
				// insert it in new position into $inner_array
				$inner_array = insert_at($inner_array, $insert_array, $newPos);
				
				// update parent array key 
				$menu_array[$parent_1][$parent_2] = $inner_array;
			}
		}
		
		// generate new content to write into menu file, from updated $menu_array
		$menu_new_content = array_to_menu_file($menu_array);
		
		// update menu file (write new content into menu.txt)
		if($fp = fopen(MENU_FILE, "w")){
			fwrite($fp, $menu_new_content);
			fclose($fp);
		}else{
			$error .= '<p class="error">ERROR: could not open '.MENU_FILE.'</p>';
		}
	}else{
		$result .= '<p class="error">ERROR: new position cannot be empty or nul.</p>';
	}
	
	if( !empty($error) ){
		$result .= $error;
	}else{
		$currentItem = $item;
		// generate html output for manage structure admin page
		if($adminPage == 'manage_structure'){
			$output .= site_structure();
		
		// generate html output for manage content admin page
		}elseif($adminPage == 'manage_contents'){
			//$output .= '<h1>OK</h1>';
			$output .= display_content_admin();
		}
		
		$result .= $output;
	}
	
	echo $result;
}


// show/hide section or sub-section (update menu.txt AND rename directory)
function show_hide($item, $parent){
	$result = $menu_array = $site_structure = $error = '';
	if(!empty($item)){
		$content = file_get_contents(MENU_FILE);
		
		// make sure the item exists
		if(!strstr($content, $item."\n")){
			$error .= '<p class="error">ERROR: could not find '.$item.'!</p>';
		}
		
		// determine if it is a sub section
		if(!empty($parent) && $parent != 'undefined'){ // yes
			$path = ROOT.CONTENT.dir_from_section_name($parent).'/';
			$start_match = "\t";
		}else{ // no
			$path = ROOT.CONTENT;
			$start_match = '';
		}
		
		// show or hide it?
		if(substr($item, 0, 1) == '_'){ // show it!
			$new_item = substr($item, 1);
		
		}else{ // hide it!
			$new_item = '_'.$item;
		}
		
		$new_content = str_replace($start_match.$item."\n", $start_match.$new_item."\n", $content);
		
		$rename_src = $path.dir_from_section_name($item);
		$rename_dest = $path.dir_from_section_name($new_item);
		//echo $rename_src.' => '.$rename_dest; exit;
		
		// update menu file
		if($fp = fopen(MENU_FILE, "w")){
			fwrite($fp, $new_content);
			fclose($fp);
			// atempt to rename directory
			if( !rename($rename_src, $rename_dest) ){
				$error .= '<p class="error">ERROR: could not rename '.$rename_src.' to '.$rename_dest.'</p>';
			}
			
		}else{
			$error .= '<p class="error">ERROR: could not open '.MENU_FILE.'</p>';
		}
		
	}else{
		$error .= '<p class="error">ERROR: item is empty!</p>';	
	}
	if(!empty($error)){
		$result .= $error;
	}else{
		$menu_array = menu_file_to_array();
		$currentItem = $new_item;
		$site_structure = site_structure($menu_array, $currentItem);
		$result .= $site_structure;
	}
	
	echo $result;
}


// create new section or sub-section (if a sub-section, $parent will NOT be empty. If a main section, $parent WILL be empty)
function create_section($parent, $createSection){
	$result = $error = '';
	$contents = file_get_contents(MENU_FILE);
	
	$new_dir = '_'.dir_from_section_name($createSection); // add underscore to hide the new section
	
	if(!empty($parent) && $parent != 'undefined'){ // if $parent is not empty, we're creating a sub-section in this parent section
		$new_contents = preg_replace('/'.preg_quote($parent).'\n/', $parent."\n\t_".$createSection."\n", $contents);
		$new_dir = dir_from_section_name($parent).'/'.$new_dir;
	}else{ // we're creating a main section
		$new_contents = '_'.$createSection."\n".$contents;
	}
	
	// make sure the section name does not already exist in this location
	if( is_dir(ROOT.CONTENT.$new_dir) || is_dir( str_replace('/_', '/', ROOT.CONTENT.$new_dir)) ){
		$result = '<p class="error">ERROR: <strong>'.$new_dir.'</strong> already exists!</p>';
		return $result;
	}
	
	if($fp = fopen(MENU_FILE, "w")){
		fwrite($fp, $new_contents);
		fclose($fp);
		// create directory for section
		if(!copyr(ROOT.'_code/section_template', ROOT.CONTENT.$new_dir)){
			$error .= '<p class="error">ERROR: Could not create '.ROOT.CONTENT.$new_dir.'</p>';
		}
	}else{
		$error .= '<p class="error">ERROR: Could not open '.MENU_FILE.'</p>';
	}
	if(!empty($error)){
		$result .= $error;
	}else{
		$result .= '<p class="success">the section "<strong>'.$createSection.'</strong>" has been created.</p>';
	}
	return $result;
}


// delete section (if a sub-section, $parent will NOT be empty. If a main section, $parent WILL be empty)
function delete_section($parent, $deleteSection){
	$result = $error = '';
	
	// generate 3D array from menu file
	$menu_array = menu_file_to_array();
	
	// if a sub-section: remove sub array key and re-insert it to proper position
	if($parent != 'undefined' && !empty($parent) ){
		// unset item key in $inner_array
		unset($menu_array[$parent][$deleteSection]);
		$parent_dir = dir_from_section_name($parent);
		$dir_to_delete = $parent_dir.'/'.dir_from_section_name($deleteSection);
	
	// if a single section, remove array key and re-insert it to proper position
	}else{
		// unset this key from menu array
		unset($menu_array[$deleteSection]);
		$dir_to_delete = dir_from_section_name($deleteSection);
	}
	
	// get content of original menu file
	$menu = file_get_contents(MENU_FILE);
	// generate new menu file from updated menu array
	$menu_file = array_to_menu_file($menu_array);
	// compare both, id they're identical, something went wrong
	if($menu == $menu_file){
		$error .= '<p class="error">ERROR: $menu = $menu_file...</p>';
	}else{
		// update menu file (write $menu_file into menu.txt)
		if($fp = fopen(MENU_FILE, "w")){
			fwrite($fp, $menu_file);
			fclose($fp);
			
			// delete directory
			if( !rmdirr(ROOT.CONTENT.$dir_to_delete) ){
				$error .= '<p class="error">ERROR: could not delete '.$dir_to_delete.'</p>';
			}
			
		}else{
			$error .= '<p class="error">ERROR: could not open '.MENU_FILE.'</p>';
		}
		
		if(!empty($error)){
			$result .= $error;
		}else{
			$result .= '<p class="success">the section "<strong>'.$deleteSection.'</strong>" has been deleted.</p>';
		}
	}
	
	
	
	return $result;
}


// delete file, and all its size versions, and its corresponding txt description files (both en and de versions)
function delete_file($delete_file){
	$message = '';
	$file_name = basename($delete_file);
	$ext = file_extension($file_name);
	
	// 1. delete files
	if( file_exists($delete_file) ){
		$txt_file = preg_replace('/'.preg_quote($ext).'$/', '.txt', $file_name );
		if( preg_match($_POST['types']['resizable_types'], $ext) ){ // resizable (images) files
			// get description files for deletion
			$en_txt = preg_replace('/\/_S\/.*/', '/en/'.$txt_file, $delete_file );
			$de_txt = preg_replace('/\/_S\/.*/', '/de/'.$txt_file, $delete_file );
			// get all sizes for deletion
			$xl_file = str_replace('/_S/', '/_XL/', $delete_file);
			$m_file = str_replace('/_S/', '/_M/', $delete_file);
			$l_file = str_replace('/_S/', '/_L/', $delete_file);
			
			if( unlink($delete_file) ){
				$message .= '<p class="success">The file has been deleted.</p>';
				// delete all sizes
				unlink($xl_file);
				unlink($m_file);
				unlink($l_file);
			}else{
				$message .= '<p class="error">ERROR: The file could not be deleted.</p>';
			}
			
		}else{ // not images... no sizes.
			// get description files for deletion
			$en_txt = preg_replace('/\/_XL\/.*/', '/en/'.$txt_file, $delete_file );
			$de_txt = preg_replace('/\/_XL\/.*/', '/de/'.$txt_file, $delete_file );
			
			if( unlink($delete_file) ){
				$message .= '<p class="success">The file has been deleted.</p>';
			}else{
				$message .= '<p class="error">ERROR: The file could not be deleted.</p>';
			}
		}
		
		// delete description files
		if(!unlink($en_txt) || !unlink($de_txt)){
			$message .= '<p class="note">Note: the text description corresponding to the file could not be deleted... </p>';
		}
	}else{
		$message .= '<p class="error">ERROR: File does not exist: '.$delete_file.'</p>';
	}
	
	// 2. update menu.txt
	$menu = file_get_contents(MENU_FILE);
	
	// attemp matching the file name with a menu line, first with two tabs (sub-sub section)
	if( strstr($menu, "\t\t".$file_name."\n") ){
		$replace = "\t\t".$file_name."\n";
	// then with one tab (sub section)
	}elseif( strstr($menu, "\t".$file_name."\n") ){
		$replace = "\t".$file_name."\n";
	// then with no tab (main section)
	}elseif( strstr($menu, $file_name."\n") ){
		$replace = $file_name."\n";
	}
	
	if( !isset($replace) ){
		$message .= '<p class="error">ERROR: Cannot match '.$file_name.' with menu.</p>';
	}else{
		$new_content = str_replace($replace, '', $menu);
		if($fp = fopen(MENU_FILE, 'w')){
			fwrite($fp, $new_content);
			fclose($fp);
			//$message .= '<p class="success">The menu has been updated.</p>';
		}else{
			$message .= '<p class="error">ERROR: Could not update menu file.</p>';
		}
	}
	return $message;
}


// save text description
function save_text_description($file, $en_txt, $de_txt){
	
	$error = $result = '';
	
	// sanitize user input
	$en_txt = sanitize_text($en_txt);
	$de_txt = sanitize_text($de_txt);
	$en_txt = my_nl2br($en_txt);
	$de_txt = my_nl2br($de_txt);
	
	$file_name = basename($file);
	$ext = file_extension($file_name);
	$text_file_name = preg_replace('/'.preg_quote($ext).'/', '.txt', $file_name);
	
	// need both S and XL for non-images files saved in XL dir
	$txt_dir = str_replace(array('/_S','/_XL'), '', dirname($file));
	$en_file = $txt_dir.'/en/'.$text_file_name;
	$de_file = $txt_dir.'/de/'.$text_file_name;
	
	if($fp = fopen($en_file, 'w')){
		fwrite($fp, $en_txt);
		fclose($fp);
	}else{
		$error .= '<p class="error">Could not open '.$en_file.'</p>';
	}
	if($fp = fopen($de_file, 'w')){
		fwrite($fp, $de_txt);
		fclose($fp);
	}else{
		$error .= '<p class="error">Could not open '.$de_file.'</p>';
	}
	
	if(!empty($error)){
		$result .= $error;
	}else{
		$result .= '<p class="success">Text saved for file: '.filename(basename($file), 'decode').'</p>';
	}
	return $result;
}


// save text content from text editor
function save_text_editor($file, $content){
	$error = $result = '';
	
	// check if we're editing a pre-existing txt file, or creating a new one in this section
	$ext = file_extension(basename($file));
	if(!$ext){ // no file extension, we'll create a new txt file
		// add the _XL directory to file path
		$path = $file.'/_XL/';
		
		if( preg_match('/<h\d.*<\/h\d>/is', $content, $matches) ){
			$clean = preg_replace( '/(\s|<br>)+/', ' ', $matches[0]);	
		}else{
			$clean = preg_replace( '/(\s|<br>)+/', ' ', $content);
		}
		
		$new_file_name = filename( substr( strip_tags( trim($clean) ), 0, 22), 'encode' );
		if( !empty($new_file_name) ){
			$new_file_name .= '-'.rand(100,999).'.html';
			$new_file = $path.$new_file_name;
		}
	}else{
		$new_file = $file;
		$new_file_name = basename($new_file);
		$path = preg_replace('/'.$new_file_name.'$/', '', $new_file);
	}
	
	
	
	// write new content into new file (create it if it does not exist)
	if($fp = fopen($new_file, 'w')){
		fwrite($fp, $content);
		fclose($fp);
		
		// update menu
		$menu = file_get_contents(MENU_FILE);
		
		// match $path with menu sections and sub-sections
		$split = explode('/', $path);
		$tabs = '';
		// for each match from path against menu title line, set $match and add a tab 
		foreach($split as $s){
			if( !empty($s) ){ // avoid empty lines/matches...
				if( strstr($menu, filename($s, 'decode').',' ) ){
					$match = filename($s, 'decode');
					$tabs .= "\t";
				}
			}
		}
		
		$new_content = preg_replace('/('.preg_quote($match).',.*)/', "$1"."\n".$tabs.$new_file_name, $menu);
		
		
		if($fp = fopen(MENU_FILE, 'w') ){
			fwrite($fp, $new_content);
			fclose($fp);
		}else{
			$error .= 'Could not open menu file';
		}
		
	}else{
		$error .= 'Could not open '.$file;
	}
	
	if(!empty($error)){
		$result .= '0|'.$error;
	}else{
		$result .= '1|'.$new_file;
	}
	return $result;
}




/******************************* UPLOAD / RESIZE FILE *******************************************/

// straight-up upload file function, used in later function. 
// Requires a FORM-submitted file input named "file"
function up_file($upload_dest){
	if( move_uploaded_file($_FILES['file']['tmp_name'], $upload_dest) ) {
		return true;
	}else{
		return false;
	}
}


// upload file (under manage content) - requires updating menu.txt
function upload_file($path, $replace){
	// initialize upload results
	$upload_message = $resize_result = $menu_update_result = '';
	$types = $_POST['types'];
	$max_upload_size = ini_get('upload_max_filesize');

	$file_name = $_FILES['file']['name']; // 'file' must be the name of the file upload input in the sending html FORM!
	$file_type = $_FILES['file']['type'];
	
	// get and format file extension
	if(isset($file_type) && !empty($file_type)){ // get it from file metadata
		$split = explode('/', $file_type);
		$ext = '.'.strtolower($split[1]);
		$ext = str_replace('jpeg', 'jpg', $ext);
		// Mac .txt files can use the "plain" file type (for plain text)!...
		if($ext == '.plain' || $ext == '.text'){
			$ext = '.txt';
		}
		// msword file type (can be generated by open office)... and docx can be .doc, to use the doc.png icon...
		if($ext == '.msword' || $ext == '.docx'){
			$ext = '.doc';
		}
	}else{ // if no metadata, take file extension
		$ext = file_extension($file_name);
		$ext = strtolower($ext);
		$ext = str_replace('jpeg', 'jpg', $ext);
	}
	
	if($ext == '.jpg'){ 
		$jpg = true; 
	}elseif($ext == '.gif'){ 
		$gif = true; 
	}elseif($ext == '.png'){
		$png = true; 
	}else{
		$jpg = $gif = $png = false; 
	}
	
	// check against extension if file type is supported
	if (!preg_match($types['supported_types'], $ext)){
		$upload_message .= '<p class="error">This file type is not supported: '.$ext.'<br>The file has not been uploaded.</p>';
	
	// UPLOAD FILE
	}else{
		
		// format/clean file name (without the extension)
		$file_name_no_ext = file_name_no_ext($file_name);
		$file_name_no_ext = filename($file_name_no_ext, 'encode');
		
		// is it an image? (if yes, it will be resized and uploaded in various sizes/directories)
		if( preg_match($types['resizable_types'], $ext) ){
			$resize = TRUE;
		}else{
			$resize = FALSE;
		}
		
		$path .= '/_XL/'; // append the extra large (original version) size directory to upload path
		
		// if we're uploading a file to replace another one
		if( !empty($replace) ){
			$replace_file_name_no_ext = file_name_no_ext($replace);
			$upload_dest = $path.$replace_file_name_no_ext.$ext;
			// if the original file and its replacement don't have the same extension, delete the original
			$replace_ext = file_extension($replace);
			if( $replace_ext != $ext){
				if( !unlink($replace) ){
					$upload_message .= '<p class="note">Note: could not delete '.$replace.'</p>';
				}
			}
		// if we're uploading to add a new file
		}else{
			// let's make sure the file name is unique
			$rand = rand(1,999);
			$new_file_name = $file_name_no_ext.'-'.$rand.$ext;
			$upload_dest = $path.$new_file_name;
		}
		
		// upload
		if( up_file($upload_dest) ){

			//@chmod($upload_dest, 0775);
			
			// RESIZE, if file is resizable (image)
			if($resize){
				
				// width and height ($w & $h) may be used for image rotation, and will be passed to resize_all function
				list($w, $h) = getimagesize($upload_dest);
				
				// read exif data and fix image orientation now if necessary! (concerns only jpgs)
				if($jpg){
					$exif = exif_read_data($upload_dest);
					$rotate = false;
					if ( !empty($exif['IFD0']['Orientation']) ) {
						$orientation = $exif['IFD0']['Orientation'];
						$rotate = true;
					}elseif( !empty($exif['Orientation']) ){
						$orientation = $exif['Orientation'];
						$rotate = true;
					}else{
						$upload_message .= '<p class="note">Note: could not read image orientation for '.$upload_dest.'</p>';
					}
					
					// image orientation needs to be fixed! 
					if($rotate){
						
						$new = imagecreatetruecolor($w, $h);
						
						if(!$new){
							$upload_message .= '<p class="error">could not imagecreatetruecolor</p>';
						
						}else{
							// we can resize jpg, gif and png files.
							if($jpg){ 
								$from = imagecreatefromjpeg($upload_dest);
							}elseif($gif){ 
								$from = imagecreatefromgif($upload_dest); 
							}elseif($png){
								$from = imagecreatefrompng($upload_dest);
							}
							
							if(!$from){
								$upload_message .= '<p class="error">could not imagecreatefromjpeg: '.$upload_dest.'</p>';
							
							}else{
								if( !imagecopyresampled($new, $from, 0, 0, 0, 0, $w, $h, $w, $h) ){
									$upload_message .= '<p class="error">could not imagecopyresampled</p>';
								}else{
									
									switch($orientation) {
										case 3:
											$new = imagerotate($new, 180, 0);
											break;
										case 6:
											$new = imagerotate($new, -90, 0);
											break;
										case 8:
											$new = imagerotate($new, 90, 0);
											break;
									}
									
									if($jpg){
										imagejpeg($new, $upload_dest, 90);
									}elseif($gif){ 
										imagegif($new, $upload_dest); 
									}elseif($png){
										imagepng($new, $upload_dest);
									}
									
									// update width and height now! Or else resizing will be off...
									list($w, $h) = getimagesize($upload_dest);
								}
							}
						}
						imagedestroy($new);
					}
				}
				
				$resize_result .= resize_all($upload_dest, $w, $h);
				if(substr($resize_result, 0, 1) == '0'){
					$upload_message .= '<p class="error">'.$resize_result.'</p>';
				}
			}
			
			$upload_message .= '<p class="success">File uploaded!</p>';
			
			
			
			// UPDATE MENU.txt (only if not a file replacement, in which case there's nothing to update)
			if( empty($replace) ){
				$menu = file_get_contents(MENU_FILE);
				// match $path with menu sections and sub-sections
				$split = explode('/', $path);
				$tabs = '';
				// for each match from path against menu title line, set $match and add a tab 
				foreach($split as $s){
					if( !empty($s) ){ // avoid empty lines/matches...
						if( strstr($menu, filename($s, 'decode').',' ) ){
							$match = filename($s, 'decode');
							$tabs .= "\t";
							//$upload_message .= $s.'<br>';
						}
					}
				}
				//$upload_message .= '$match: '.$match.'<br>';
				$new_content = preg_replace('/('.preg_quote($match).',.*)/', "$1"."\n".$tabs.$new_file_name, $menu);
				//$upload_message .= '<pre>'.$new_content.'</pre>';
				
				if($fp = fopen(MENU_FILE, 'w') ){
					fwrite($fp, $new_content);
					fclose($fp);
				}else{
					$upload_message .= '<p class="error">Could not open menu file.</p>';
				}
				
			}elseif( $replace_ext != $ext ){
				$menu = file_get_contents(MENU_FILE);
				$new_content = str_replace($replace_file_name_no_ext.$replace_ext, $replace_file_name_no_ext.$ext, $menu);
				if($fp = fopen(MENU_FILE, 'w') ){
					fwrite($fp, $new_content);
					fclose($fp);
				}else{
					$upload_message .= '<p class="error">Could not open menu file.</p>';
				}
			}
			
		}else{
			$upload_message .= '<p class="error">Error: make sure the file is not bigger than '.$max_upload_size.'!</p>';
		}
	}

	$upload_results = $upload_message.$menu_update_result;
	
	return $upload_results;
}


// resize image to various sizes
function resize_all($upload_dest, $w, $h){
	
	$resize_result = '';
	
	// resize image to various sizes as specified by $_POST['sizes'] array
	foreach($_POST['sizes'] as $key => $val){
		
		$width = $val['width'];
		$height = $val['height'];
		$resize_dest = str_replace('/_XL', '/_'.$key, $upload_dest);
		
		if($w > $width || $h > $height){
			$resize_result .= resize($upload_dest, $resize_dest, $w, $h, $width, $height);
				
		}else{
			if( !copy($upload_dest, $resize_dest) ){
				$resize_result .= '0|could not copy '.$upload_dest.' to '.$resize_dest.'<br>';
			}
		}
	}
	
	return $resize_result;
}


// resize image
function resize($src, $dest, $width_orig, $height_orig, $width, $height){

	$types = $_POST['types'];
	$result = '';

	$ext = file_extension($src); //extract extension
	$ext = str_replace('jpeg', 'jpg', strtolower($ext) ); // format it for later macthing
	if($ext == '.jpg'){ 
		$jpg = true; 
	}elseif($ext == '.gif'){ 
		$gif = true; 
	}elseif($ext == '.png'){
		$png = true; 
	}else{
		$jpg = $gif = $png = false; 
	}
	
	// make sure file is resizable (match against file extension)
	if ( preg_match($types['resizable_types'], $ext) ){
		

		// if image is bigger than the target width or height, calculate new sizes and resize
		if($width_orig > $width || $height_orig > $height){
			$scale = min($width/$width_orig, $height/$height_orig);
			$width = round($width_orig*$scale);
			$height = round($height_orig*$scale);
			
			// create canvas for image with new sizes
			$new = imagecreatetruecolor($width, $height);
			if(!$new){
				$result .= '0|could not imagecreatetruecolor<br>';
			
			}else{
				// we can resize jpg, gif and png files.
				if($jpg){ 
					$from = imagecreatefromjpeg($src);
					//$from = imagecreatefromjpegexif($src); // <-- attempt to fix orientation problem
					//$new = imagecreatetruecolor($height, $width);
				}elseif($gif){ 
					$from = imagecreatefromgif($src); 
				}elseif($png){
					$from = imagecreatefrompng($src);
				}
				
				if(!$from){
					$result .= '0|could not imagecreatefromjpeg: '.$src.'<br>';
				
				}else{
					if( !imagecopyresampled($new, $from, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig) ){
						$result .= '0|could not imagecopyresampled<br>';
					}else{
						
						if($jpg){
							imagejpeg($new, $dest, 90);
						}elseif($gif){ 
							imagegif($new, $dest); 
						}elseif($png){
							imagepng($new, $dest);
						}
						imagedestroy($new);
						
						//$result .= '1|'.$src.'<br>';
					}
				}
			}
			
		// no need to resize, the original image is too small
		}else{
			$result .= '1|no need to resize.';
		}
	
	// file is not resizable
	}else{
		$result .= '0|file is not resizable.';
	}
	
	return $result;
}

