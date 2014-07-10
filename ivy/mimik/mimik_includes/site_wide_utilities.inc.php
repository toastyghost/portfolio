<?

function js_array($array, $baseName)
{
	$jsArr = ($baseName . " = new Array(); \r\n ");
	reset ($array);
	while(list($key, $value) = each($array))
	{
		if(is_array($value))
		{
			//
		}
		$jsArr.= ($baseName . "[" . $key . "] = '" . $value . "'; \r\n ");
	}
	return $jsArr;
}

function vname(&$var, $scope=false, $prefix='unique', $suffix='value')
{
	if($scope) $vals = $scope;
	else $vals = $GLOBALS;
	$old = $var;
	$var = $new = $prefix.rand().$suffix;
	$vname = FALSE;
	foreach($vals as $key => $val)
	{
		if($val === $new) $vname = $key;
	}
	$var = $old;
	return $vname;
}

function debug(&$object, $label = '')
{
	echo "<pre>";
	if($label=='') $label = vname($object);
	echo $label . ': ';
	print_r($object);
	echo "</pre>";
}

function redirect($url)
{
	if(!headers_sent())
	{
		header('Location: '.$url);
		exit;
	}
	else
	{
		echo '
		<script type="text/javascript">
			window.location.href="'.$url.'";
		</script>
		<noscript>
			<meta http-equiv="refresh" content="0;url='.$url.'" />
		</noscript>';
		exit;
	}
}

function mkdir_recursive($The_Input_Path, $The_Parent_Path = '')
{
	require_once( '../mimik_configuration/the_system_settings.config.php' );

	global $THE_BASE_SERVER_PATH;
	
	if (strrpos($The_Input_Path, '/') == strlen($The_Input_Path) - 1) $The_Input_Path = substr($The_Input_Path, 0, strlen($The_Input_Path) - 1);
	
	$The_Path_Array = explode('/', $The_Input_Path);
	
	$The_Built_Path = $The_Parent_Path . '/';
	
	foreach ($The_Path_Array as $The_Directory) :
	
		$The_Built_Path .= $The_Directory . '/';
		
		if (!file_exists($The_Built_Path)) :
		
			mkdir($The_Built_Path, 0777);
			
		endif;
	
	endforeach;
}

function unlink_recursive($dir, $deleteRootToo = true, $The_Input_Age_In_Seconds = 0) 
{ 
	if(!$dh = @opendir($dir)) return; 

	while (false !== ($obj = readdir($dh))) :

		if($obj == '.' || $obj == '..') continue;
		
		if(filemtime($dir . '/' . $obj) <= time() - $The_Input_Age_In_Seconds) :
		
			if (!@unlink($dir . '/' . $obj)) :
			
				unlink_recursive($dir.'/'.$obj, true, $The_Input_Age_In_Seconds);
				
			endif;
			
		else :
		
			unlink_recursive($dir.'/'.$obj, false, $The_Input_Age_In_Seconds);
			
		endif;
		
	endwhile;
	
	closedir($dh); 
	
	if ($deleteRootToo) :
	
		@rmdir($dir); 
		
	endif;
	
	return;
}

function uploadFile($fileName, $maxSize, $fullPath, $relPath){
	require_once( '../mimik_configuration/the_system_settings.config.php' );

	global $THE_BASE_SERVER_PATH;
	
	$folder = $relPath;
	if (!file_exists($folder)) {
		mkdir_recursive($folder, $THE_BASE_SERVER_PATH);
	}
	$maxlimit = $maxSize;
	
	$match = "";
	$filesize = $_FILES[$fileName]['size'];
	if($filesize > 0){	
		$filename = $_FILES[$fileName]['name']; // jc 6/2 - removed case insensitivity which was causing broken images, original code follows //$filename = strtolower($_FILES[$fileName]['name']);
		// jc 6/2 - removed whitespace replacement which was causing broken images, original code follows //$filename = preg_replace('/\s/', '_', $filename);
		if($filesize < 1){ 
			$errorList[] = "File size is empty.";
		}
		if($filesize > $maxlimit){ 
			$errorList[] = "File size is too big.";
		}
		if(count($errorList)>=1){
			$errorList[]= "NO FILE SELECTED";
		}
	}
	
	if (is_uploaded_file($_FILES[$_REQUEST['filename']]['tmp_name']))
	{
		
		$name = $_FILES[$_REQUEST['filename']]['name'];
		$result = move_uploaded_file($_FILES[$_REQUEST['filename']]['tmp_name'], $THE_BASE_SERVER_PATH . '/' . $folder."$name");
		
		if ($result == 1) $fileUploaded=true;
		else $eMessage[] = 'upload fail';
	}
	
	if(sizeof($errorList) == 0){
		return $fullPath.$newfilename;
	}else{
		$eMessage = array();
		for ($x=0; $x<sizeof($errorList); $x++){
			$eMessage[] = $errorList[$x];
		}
		return $eMessage;
	}
}


function uploadImage($fileName, $maxSize, $maxW, $fullPath, $relPath, $colorR, $colorG, $colorB, $maxH = null){

	require_once( '../mimik_configuration/the_system_settings.config.php' );

	global $THE_BASE_SERVER_PATH;
	
	$folder = $relPath;
	if (!file_exists($folder)) {
		mkdir_recursive($folder, $THE_BASE_SERVER_PATH);
	}
	$maxlimit = $maxSize;
	
	switch($_REQUEST['itemtype']):
		case('image'):
			$allowed_ext = "jpg,jpeg,gif,png,bmp";
			break;
		case('document'):
			$allowed_ext = "doc,ppt,pdf,xls,docx,xlsx";
			break;
	endswitch;
	
	$match = "";
	$filesize = $_FILES[$fileName]['size'];
	if($filesize > 0){	
		$filename = strtolower($_FILES[$fileName]['name']); // jc 6/2 - removed case insensitivity which was causing broken images, original code follows //$filename = strtolower($_FILES[$fileName]['name']);
		// jc 6/2 - removed whitespace replacement which was causing broken images, original code follows //$filename = preg_replace('/\s/', '_', $filename);
		if($filesize < 1){ 
			$errorList[] = "File size is empty.";
		}
		if($filesize > $maxlimit){ 
			$errorList[] = "File size is too big.";
		}
		if(count($errorList)<1){
			$file_ext = preg_split("/\./",$filename);
			$file_ext[1] = strtolower($file_ext[1]);
			$allowed_ext = preg_split("/\,/",$allowed_ext);
			foreach($allowed_ext as $ext){
				if($ext==end($file_ext)){
					$match = "1"; // File is allowed
					$NUM = time();
					$front_name = $file_ext[0]; // jc 6/2 - removed filename length limit, original code follows //$front_name = substr($file_ext[0], 0, 15);
					$newfilename = $front_name/*."_".$NUM*/.".".end($file_ext);
					$filetype = end($file_ext);
					$save = $THE_BASE_SERVER_PATH . '/' . $folder.$newfilename;
					if($filetype=='jpg' || $filetype=='gif' || $filetype=='png'){
						//if(!file_exists($save)){
							list($width_orig, $height_orig) = getimagesize($_FILES[$fileName]['tmp_name']);
							if($maxH == null){
								if($width_orig < $maxW){
									$fwidth = $width_orig;
								}else{
									$fwidth = $maxW;
								}
								$ratio_orig = $width_orig/$height_orig;
								$fheight = $fwidth/$ratio_orig;
								
								$blank_height = $fheight;
								$top_offset = 0;
									
							}else{
								if($width_orig <= $maxW && $height_orig <= $maxH){
									$fheight = $height_orig;
									$fwidth = $width_orig;
								}else{
									if($width_orig > $maxW){
										$ratio = ($width_orig / $maxW);
										$fwidth = $maxW;
										$fheight = ($height_orig / $ratio);
										if($fheight > $maxH){
											$ratio = ($fheight / $maxH);
											$fheight = $maxH;
											$fwidth = ($fwidth / $ratio);
										}
									}
									if($height_orig > $maxH){
										$ratio = ($height_orig / $maxH);
										$fheight = $maxH;
										$fwidth = ($width_orig / $ratio);
										if($fwidth > $maxW){
											$ratio = ($fwidth / $maxW);
											$fwidth = $maxW;
											$fheight = ($fheight / $ratio);
										}
									}
								}
								if($fheight == 0 || $fwidth == 0 || $height_orig == 0 || $width_orig == 0){
									die("FATAL ERROR REPORT ERROR CODE [add-pic-line-67-orig] to <a href='http://www.atwebresults.com'>AT WEB RESULTS</a>");
								}
								if($fheight < 45){
									$blank_height = 45;
									$top_offset = round(($blank_height - $fheight)/2);
								}else{
									$blank_height = $fheight;
								}
							}
							$image_p = imagecreatetruecolor($fwidth, $blank_height);
							imagesavealpha($image_p, true);
							$white = imagecolorallocatealpha($image_p, $colorR, $colorG, $colorB, 127);
							imagefill($image_p, 0, 0, $white);
							switch($filetype){
								case "gif":
									$image = @imagecreatefromgif($_FILES[$fileName]['tmp_name']);
								break;
								case "jpg":
									$image = @imagecreatefromjpeg($_FILES[$fileName]['tmp_name']);
								break;
								case "jpeg":
									$image = @imagecreatefromjpeg($_FILES[$fileName]['tmp_name']);
								break;
								case "png":
									$image = @imagecreatefrompng($_FILES[$fileName]['tmp_name']);
								break;
							}
							@imagecopyresampled($image_p, $image, 0, $top_offset, 0, 0, $fwidth, $fheight, $width_orig, $height_orig);
							switch($filetype){
								case "gif":
									if(!@imagegif($image_p, $save)){
										$errorList[]= "PERMISSION DENIED [GIF]";
									}
								break;
								case "jpg":
									if(!@imagejpeg($image_p, $save, 100)){
										$errorList[]= "PERMISSION DENIED [JPG]";
									}
								break;
								case "jpeg":
									if(!@imagejpeg($image_p, $save, 100)){
										$errorList[]= "PERMISSION DENIED [JPEG]";
									}
								break;
								case "png":
									if(!@imagepng($image_p, $save, 0)){
										$errorList[]= "PERMISSION DENIED [PNG]";
									}
								break;
							}
							@imagedestroy($filename);
						/*}else{
							$errorList[]= "A file with that name already exists.";
						}*/
					}else{
						$type = $_FILES[$_REQUEST['filename']]['type'];
						
						if (is_uploaded_file($_FILES[$_REQUEST['filename']]['tmp_name']))
						{
							if ($type != "application/pdf" && $type != "application/msword" && $type != "application/vnd.ms-excel" && $type != "application/vnd.ms-powerpoint"){
								$match = "";
							}else{
								$match = "1";
								$name = $_FILES[$_REQUEST['filename']]['name'];
								$result = move_uploaded_file($_FILES[$_REQUEST['filename']]['tmp_name'], $folder."/$name");
								
								if ($result == 1) $imgUploaded=true;
									else $imgUploaded=false;
							}
						}
					}	
				}
			}		
		}
	}else{
		$errorList[]= "NO FILE SELECTED";
	}
	if(!$match){
		$errorList[]= "File type isn't allowed: " . substr($filename, strpos($filename, '.'));
	}
	if(sizeof($errorList) == 0){
		return $fullPath.$newfilename;
	}else{
		$eMessage = array();
		for ($x=0; $x<sizeof($errorList); $x++){
			$eMessage[] = $errorList[$x];
		}
		return $eMessage;
	}
}

function uploadSecureFile($fileName, $maxSize, $fullPath, $relPath){
	require_once( '../mimik_configuration/the_system_settings.config.php' );

	global $THE_BASE_SERVER_PATH;
	
	$folder = $relPath;
	if (!file_exists($THE_BASE_SERVER_PATH . '/' . $folder)) {
		mkdir_recursive($folder, $THE_BASE_SERVER_PATH);
	}
	else {
	}

	$folder = $THE_BASE_SERVER_PATH . '/' . $folder;
	
	$maxlimit = $maxSize;
	
	$match = "";
	$filesize = $_FILES[$fileName]['size'];
	if($filesize > 0){	
		$filename = $_FILES[$fileName]['name']; // jc 6/2 - removed case insensitivity which was causing broken images, original code follows //$filename = strtolower($_FILES[$fileName]['name']);
		// jc 6/2 - removed whitespace replacement which was causing broken images, original code follows //$filename = preg_replace('/\s/', '_', $filename);
		if($filesize < 1){ 
			$errorList[] = "File size is empty.";
		}
		if($filesize > $maxlimit){ 
			$errorList[] = "File size is too big.";
		}
		if(count($errorList)>=1){
			$errorList[]= "NO FILE SELECTED";
		}
	}
	
	if (is_uploaded_file($_FILES[$_REQUEST['filename']]['tmp_name']))
	{
		
		$name = $_FILES[$_REQUEST['filename']]['name'];
		$result = move_uploaded_file($_FILES[$_REQUEST['filename']]['tmp_name'], $folder."/$name");
		
		if ($result == 1) $fileUploaded=true;
		else $eMessage[] = 'upload fail';
	}
	
	if(sizeof($errorList) == 0){
		return $fullPath.$newfilename;
	}else{
		$eMessage = array();
		for ($x=0; $x<sizeof($errorList); $x++){
			$eMessage[] = $errorList[$x];
		}
		return $eMessage;
	}
}

function The_Mimik_Safe_Field_Name($The_Input_Field_Name)
{
	return strtolower(preg_replace("/[^a-zA-Z0-9]/", "_", $The_Input_Field_Name));
}

function The_Mimik_Safe_Form_Name($The_Input_Form_Name)
{
	return strtolower(preg_replace("/[^a-zA-Z0-9]/", "_", $The_Input_Form_Name));
}

function The_Mimik_Safe_Template_Name($The_Input_Template_Name)
{
	return strtolower(preg_replace("/[^a-zA-Z0-9]/", "_", $The_Input_Template_Name));
}

function Flatten($Nested_Array)
{

	if (is_array($Nested_Array)) :
	
		foreach ($Nested_Array as $Element) :
		
			if (is_array($Element)) :
			
				$Results = Flatten($Element);
				
				foreach ($Results as $The_Result) :
				
					$Flat_Array[] = $The_Result;
					
				endforeach;
				
			else :
			
				$Flat_Array[] = $Element;
				
			endif;
			
		endforeach;
		
	endif;
	
	return $Flat_Array;
	
} // Flatten

function is_chrome()
{
	return(eregi("chrome", $_SERVER['HTTP_USER_AGENT']));
} // is_chrome

function delete_directory($dir){
	if($handle = opendir($dir)){
		$array = array();
		while(false !== ($file = readdir($handle))){
			if($file != "." && $file != ".."){
				if(is_dir($dir.$file)){
					if(!@rmdir($dir.$file)){
						delete_directory($dir.$file.'/');
					}
				}else{
					@unlink($dir.$file);
				}
			}
		}
		closedir($handle);
		@rmdir($dir);
	}
} // delete_directory

if (!function_exists('json_encode'))
{
	function json_encode($a=false)
	{
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		if (is_scalar($a))
		{
			if (is_float($a))
			{
				return floatval(str_replace(",", ".", strval($a)));
			}

			if (is_string($a))
			{
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			}
			else
				return $a;
		}
		$isList = true;
		for ($i = 0, reset($a); $i < count($a); $i++, next($a))
		{
			if (key($a) !== $i)
			{
				$isList = false;
				break;
			}
		}
		$result = array();
		if ($isList)
		{
			foreach ($a as $v) $result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		}
		else
		{
			foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}

if (!function_exists('json_decode')){
	function json_decode($json)
	{
		$comment = false;
		$out = '$x=';
		for ($i=0; $i<strlen($json); $i++)
		{
			if (!$comment)
			{
				if ($json[$i] == '{')        $out .= ' array(';
				else if ($json[$i] == '}')    $out .= ')';
				else if ($json[$i] == ':')    $out .= '=>';
				else                         $out .= $json[$i];
			}
			else $out .= $json[$i];
			if ($json[$i] == '"')    $comment = !$comment;
		}
		eval($out . ';');
		return $x;
	}
}

function strip_punctuation($text)
{
	$urlbrackets    = '\[\]\(\)';
	$urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
	$urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
	$urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
	
	$specialquotes  = '\'"\*<>';
	
	$fullstop       = '\x{002E}\x{FE52}\x{FF0E}';
	$comma          = '\x{002C}\x{FE50}\x{FF0C}';
	$arabsep        = '\x{066B}\x{066C}';
	$numseparators  = $fullstop . $comma . $arabsep;
	
	$numbersign     = '\x{0023}\x{FE5F}\x{FF03}';
	$percent        = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
	$prime          = '\x{2032}\x{2033}\x{2034}\x{2057}';
	$nummodifiers   = $numbersign . $percent . $prime;
	
	return preg_replace(
		array(
			'/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
			'/\p{Po}(?<![' . $specialquotes .
				$numseparators . $urlall . $nummodifiers . '])/u',
			'/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
			'/[' . $specialquotes . $numseparators . $urlspaceafter .
				'\p{Pd}\p{Pc}]+((?= )|$)/u',
			'/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
			'/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
			'/ +/',
		),
		' ',
		$text );
} // strip_punctuation

function get_ext($file)
{
	return substr($file, strrpos($file, '.')+1);
} // get_ext

function directory_to_array($directory, $filename = '', $extension = '', $recursive = true){
	$array_items = array();
	if($handle = opendir($directory)){
		while(false !== ($file = readdir($handle))){
			if($file != "." && $file != ".."){
				if(is_dir($directory. "/" . $file)){
					if($recursive){
						$array_items = array_merge($array_items, directory_to_array($directory."/".$file, $filename, $extension, $recursive));
					}
					$file = $directory . "/" . $file;
					if($extension == '' && $filename == ''){
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}else{
					if(get_ext($file) == $extension || $file == $filename || ($filename == '' && $extension == '')){
						$file = $directory."/".$file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
} // directory_to_array

function get_protocol(){
	$protocol = 'http';
	if($_SERVER['HTTPS'] == 'On') $protocol.= 's';
	$protocol.= '://';
	return $protocol;
} // get_protocol # original return strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

function toggle(&$Boolean){
	if($Boolean == true):
		$Boolean = false;
	elseif($Boolean == false):
		$Boolean = true;
	endif;
} // toggle

function strposnth($haystack,$needle,$nth=1,$insensitive=0){
	if($insensitive){
		$haystack=strtolower($haystack);
		$needle=strtolower($needle);
	}
	$count=substr_count($haystack,$needle);
	if($count<1 || $nth>$count) return false;
	for($i=0,$pos=0,$len=0;$i<$nth;$i++){
		$pos=strpos($haystack,$needle,$pos+$len);
		if($i==0) $len=strlen($needle);
	}
	return $pos;
} // strposnth

$FIREFOX = 'Firefox';
$CHROME = 'Chrome';
$SAFARI = 'Safari';
$OPERA = 'Opera';
$MSIE = 'Internet Explorer';
function browser($user_agent = ''){
	if(!$user_agent && $_SERVER['HTTP_USER_AGENT']) $user_agent = $_SERVER['HTTP_USER_AGENT'];
	global $FIREFOX,$CHROME,$SAFARI,$OPERA,$MSIE;
	if(strpos($user_agent,'Firefox')!==false) return $FIREFOX;
	elseif(strpos($user_agent,'Chrome')!==false) return $CHROME;
	elseif(strpos($user_agent,'Safari')!==false) return $SAFARI;
	elseif(strpos($user_agent,'Presto')!==false) return $OPERA;
	elseif(strpos($user_agent,'MSIE')!==false) return $MSIE;
} // browser

function browser_version($user_agent = ''){
	if(!$user_agent && $_SERVER['HTTP_USER_AGENT']) $user_agent = $_SERVER['HTTP_USER_AGENT'];
	global $FIREFOX,$CHROME,$SAFARI,$OPERA,$MSIE;
	$browser = browser();
	if($browser===$FIREFOX) $version = (float)substr($user_agent,strpos($_SERVER['HTTP_USER_AGENT'],'Firefox/')+8,3);
	elseif($browser===$CHROME) $version = (float)substr($user_agent,strpos($_SERVER['HTTP_USER_AGENT'],'Chrome/')+7,3);
	elseif($browser===$SAFARI) $version = (float)substr($user_agent,strpos($_SERVER['HTTP_USER_AGENT'],'Version/')+8,3);
	elseif($browser===$OPERA) $version = (float)substr($user_agent,strpos($_SERVER['HTTP_USER_AGENT'],'Version/')+8,5);
	return $version;
} // browser_version

function browser_supports_webfonts($user_agent = ''){
	if(!$user_agent && $_SERVER['HTTP_USER_AGENT']) $user_agent = $_SERVER['HTTP_USER_AGENT'];
	global $FIREFOX,$CHROME,$SAFARI,$OPERA;
	$browser = browser($user_agent);
	$version = browser_version($user_agent);
	if(($browser===$FIREFOX && $version >= 3.5)||($browser===$CHROME && $version >= 4)||($browser===$SAFARI && $version >= 3.1)||($browser===$OPERA && $version >= 10)) return true;
	else return false;
} // browser_supports_webfonts

function get_font_name($file){
	$slash_pos = strrpos($file,'/')+1;
	return ucwords(str_replace('_',' ',substr($file,$slash_pos,strrpos($file,'.')-$slash_pos)));
} // get_font_name

function get_cufon_font_name($file){
	$font = file_get_contents($file);
	$font = substr($font,strpos($font,'font-family'));
	$start = strposnth($font,'"',2)+1;
	$length = strposnth($font,'"',3)-$start;
	$font = substr($font,$start,$length);
	return $font;
} // get_cufon_font_name

?>
