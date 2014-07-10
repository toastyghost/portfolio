<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/the_system_settings.config.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/site_wide_utilities.inc.php');
	
	$filename = strip_tags($_REQUEST['filename']);
	$maxSize = strip_tags($_REQUEST['maxSize']);
	$maxW = strip_tags($_REQUEST['maxW']);
	$fullPath = strip_tags($_REQUEST['fullPath']);
	$relPath = strip_tags($_REQUEST['relPath']);
	$colorR = strip_tags($_REQUEST['colorR']);
	$colorG = strip_tags($_REQUEST['colorG']);
	$colorB = strip_tags($_REQUEST['colorB']);
	$maxH = strip_tags($_REQUEST['maxH']);

	// added by rhp 2009-06-29
	$submitID = strip_tags($_REQUEST['submitID']);
	$filesize_image = $_FILES[$filename]['size'];
	if($filesize_image > 0){
		$upload_image = uploadImage($filename, $maxSize, $maxW, $fullPath, $relPath, $colorR, $colorG, $colorB, $maxH);
		if(is_array($upload_image)){
			foreach($upload_image as $key => $value) {
				if($value == "-ERROR-") {
					unset($upload_image[$key]);
				}
			}
			$document = array_values($upload_image);
			for ($x=0; $x<sizeof($document); $x++){
				$errorList[] = $document[$x];
			}
			$imgUploaded = false;
		}else{
			$imgUploaded = true;
		}
	}else{
		$imgUploaded = false;
		$errorList[] = "File Size Empty";
	}
	echo '<img src="./images/footer-split.gif" style="visibility:hidden;" onload="document.form1.action=\'test_post_results.php\'" />';
	if($imgUploaded){
		global $THE_BASE_URL;
		echo '<a href="#" onclick="Remove_Temp_File_From_The_Submission(\'' . str_replace($THE_BASE_URL . '/mimik/mimik_temp_uploads/', '', $upload_image) . '\', 0, this.parentNode.id); return false;">Remove</a><br />';
		$relPath = str_replace('../', '', $relPath);
		echo '<img src="./images/success.gif" width="16" height="16" border="0" style="margin-bottom: -4px;border:none;padding:0;"/> Success!<br />';
		echo '<img style="max-width:200px;border:none;" src="'.$upload_image.'" border="0" />';
		echo '<input type="hidden" name="' . $submitID . '" id="' . $submitID . '" value="' . $relPath . strtolower($_FILES[$filename]['name']) . '" />';
	}else{
		echo '<pre>imgUploaded = false</pre>';
		echo '<img src="./images/error.gif" width="16" height="16px" border="0" style="margin-bottom: -3px;border:none;padding:0;"/> Error(s) Found: ';
		$i=0;
		foreach($errorList as $value){
	    	echo $value;
			$i++;
			if($errorList[$i]){
				echo ', ';
			}
		}
	}
?>