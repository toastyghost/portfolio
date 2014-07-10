<?php
	require_once('../mimik_configuration/the_system_settings.config.php');
	require_once('../mimik_includes/site_wide_utilities.inc.php');
	
	global $THE_BASE_SERVER_PATH;
	
	$filename = strip_tags($_REQUEST['filename']);
	$maxSize = strip_tags($_REQUEST['maxSize']);
	$fullPath = strip_tags($_REQUEST['fullPath']);
	$relPath = strip_tags($_REQUEST['relPath']);
	
	$relPathArray = explode('/', $relPath);
	
	if (is_array($relPathArray)) foreach ($relPathArray as $relPathKey => $relPathValue) :
	
		if (!$relPathValue) unset($relPathArray[$relPathKey]);
		
	endforeach;
	
	$guid = $relPathArray[count($relPathArray) - 1];
	
	// added by rhp 2009-06-29
	$submitID = strip_tags($_REQUEST['submitID']);
	
	$filesize_file = $_FILES[$filename]['size'];
	if($filesize_file > 0){
		$upload_file = uploadFile($filename, $maxSize, $fullPath, $relPath);
		if(is_array($upload_file)){
			foreach($upload_file as $key => $value) {
				if($value == "-ERROR-") {
					unset($upload_file[$key]);
				}
			}
			$file = array_values($upload_file);
			for ($x=0; $x<sizeof($file); $x++){
				$errorList[] = $file[$x];
			}
			$fileUploaded = false;
		}else{
			$fileUploaded = true;
		}
	}else{
		$fileUploaded = false;
		$errorList[] = "File Size Empty";
	}
	echo '<img src="./images/footer-split.gif" style="visibility:hidden;" onload="document.form1.action=\'test_post_results.php\'" />';
	if($fileUploaded){
		global $THE_BASE_PATH;
		$relPath = str_replace('../', '', $relPath);
		echo '<a href="#" onclick="Remove_Temp_File_From_The_Submission(\'' . $guid . '/' . $_FILES[$filename]['name'] . '\', 0, this.parentNode.id); return false;">Remove</a><br />';
		echo '<img src="./images/success.gif" width="16" height="16" border="0" style="margin-bottom: -4px;border:none;padding:0;"/> Success!<br />';
		//echo 'URL : <a href="' . $THE_BASE_PATH . '/' . $relPath . $_FILES[$filename]['name'] . '" target="_blank">' . $THE_BASE_PATH . '/' . $relPath . $_FILES[$filename]['name'] . '</a>';
		echo '<input type="hidden" name="' . $submitID . '" id="' . $submitID . '" value="' . $relPath . $_FILES[$filename]['name'] . '" />';
	}else{
		echo '<img src="./images/error.gif" width="16" height="16px" border="0" style="margin-bottom: -3px;border:none;padding:0;"/> Error(s) Found: ';
		$i=0;
		echo $_SERVER['SCRIPT_FILENAME'] . ', ';
		foreach($errorList as $value){
	    	echo $value;
			$i++;
			if($errorList[$i]){
				echo ', ';
			}
		}
	}
?>