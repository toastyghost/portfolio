<?php

session_start();

require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/the_system_settings.config.php' );

function The_HTML_For_The_Account_Editor($The_Input_User_Information, $The_Input_User_Custom_Fields_Information = NULL)
{
	$The_Submit_Tag = 'EDIT_ACCOUNT:';
	
	$The_List_Name = 'account_modification_list';
	
	$The_HTML .= '<h2>Account</h2>';
	
	$The_HTML .= '<ul class="list" id="' . $The_List_Name . '">';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Login';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box">';
	$The_HTML .= $The_Input_User_Information['login'];
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// password
	
	$The_Field_ID = 'password';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Password';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input onkeyup="Confirm_The_Password(this.value, \'password_confirm\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// password confirm
	
	$The_Field_ID = 'password_confirm';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Confirm Password';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input onkeyup="Confirm_The_Password(this.value, \'password\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// password (submitted)
	
	$The_Field_ID = $The_Submit_Tag . 'password';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<small><a href="#" onclick="alert(\'Passwords must be at least 8 characters long with at least one upper and lower-case letter, one number, and one symbol.\');return false;">Password Validation</a></small>';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '<div id="password_feedback"></div>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_User_Custom_Fields_Information)) foreach ($The_Input_User_Custom_Fields_Information as $The_Field) :
	
		$The_Field_ID = $The_Submit_Tag . $The_Field['name'];
	
		$The_HTML .= '<li class="list_item">';
		
		$The_HTML .= '<div class="list_item_column">';
		
		$The_HTML .= $The_Field['display_name'];
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '<div class="list_item_column">';

		$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Input_User_Information[$The_Field['name']] . '" />';

		$The_HTML .= '</div>';

		$The_HTML .= '</li>';
	
	endforeach;
	
	$The_HTML .= '</ul>';

	$The_HTML .= '<p class="admin_control_box">';

	$The_HTML .= '<a href="#" onclick="Modify_The_Account_With_The_Tagged_Items_In_The_Div(\'' . $The_Input_User_Information['id'] . '\', \'' . $The_Submit_Tag . '\', \'' . $The_List_Name . '\');return false;">OK</a> | ';

	$The_HTML .= '<a href="#" onclick="changeTo(\'placeholder\',-1);return false;">Cancel</a>';
	
	$The_HTML .= '</p>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_Error_Div($The_Input_Error_Message)
{
	$The_Div_ID = 'error_' . substr(md5(uniqid(rand(),1)),0,5);
	
	$The_HTML = '';
	
	$The_HTML .= '<div class="error list_item" id="' . $The_Div_ID . '">';
	
	$The_HTML .= '<div class="list_item_wide_column">';
	
	$The_HTML .= $The_Input_Error_Message;
	
	$The_HTML .= '</div>';
	
	$The_HTML .= '<div class="list_item_control_box">';
	
	$The_HTML .= '<a href="#" onclick="Hide_The_Div(\'' . $The_Div_ID . '\');return false;">Close</a>';
	
	$The_HTML .= '</div>';
	
	$The_HTML .= '</div>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_Message_Div($The_Input_Message = '', $The_Input_Div_ID = NULL)
{
	$The_Div_ID = ($The_Input_Div_ID != NULL) ? $The_Input_Div_ID : 'message_' . substr(md5(uniqid(rand(),1)),0,5);
	
	$The_HTML = '';
	
	$The_HTML .= '<div class="list_item message" id="' . $The_Div_ID . '">';
	
	$The_HTML .= '<div style="display:none; float:left;" id="' . $The_Div_ID . '-text">';
	
	$The_HTML .= $The_Input_Message;
	
//	$The_HTML .= '<img src="../mimik_images/blank.gif" onload="setTimeout(function() {$(this).parent().fadeOut(1000);}, 2000);" />';

	$The_HTML .= '<img src="../mimik_images/blank.gif" onload="$(\'#' . $The_Div_ID . '-text\').fadeIn(1000);" />';
	
	$The_HTML .= '</div>';
	
	$The_HTML .= '</div>';
	
	return $The_HTML;
	
}

// This function echoes its HTML in order to properly display the <form> element for WYSIWYG data
function The_HTML_For_The_Submission_Creator_For_The_Fields_And_The_Form(
												$The_Input_Fields,
												$The_Input_Form_Name,
												$The_Input_Form_ID,
												$The_Input_Submission_GUID,
												$The_Input_Indication_Of_Admin_Display = true,
												$The_Input_HTML_Prefix = '',
												$The_Input_HTML_Suffix = '',
												$The_Input_Indication_To_Use_Field_Labels = true,
												$The_Input_Element_Class_Array = array() )
{
	global $THE_SECURE_FILES_PATH;
	global $THE_BASE_URL;
	
	$The_Submit_Tag = 'CREATESUBMISSION:';
	
	$The_Group_Tag = 'CREATESUBMISSIONGROUP:';
	
	$The_User_Tag = 'CREATESUBMISSIONUSER:';

	$The_List_Name = 'submission_creation_list';

	$The_Fields = $The_Input_Fields;
	
	$The_Group_Div_Name = 'create_submission_group_div';
	
	$The_User_Div_Name = 'create_submission_user_div';
	
	$The_Javascript_Array_Of_WYSIWYG_IDs = 'var The_Javascript_Array_Of_WYSIWYG_IDs = [];';
	
	$The_Required_Fields = array();
	
	$The_WYSIWYG_Counter = 0;

	$The_HTML = '';
	
	if ($The_Input_HTML_Prefix) echo $The_Input_HTML_Prefix;
	
	if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) :
	
		echo '<ul class="list" id="' . $The_List_Name . '">';
		
	else :
	
		echo '<div id="' . $The_List_Name . '">';
		
	endif;
	
	echo '<input type="hidden" name="' . $The_Submit_Tag . 'id" id="' . $The_Submit_Tag . 'id" value="' . $The_Input_Submission_GUID . '" />';
	
	$The_Number_Of_Slow_Loading_Fields = 0;
	
	if (is_array($The_Fields)) foreach ($The_Fields as $The_Field) :
	
		if ($The_Field['is_required'] == 1) $The_Required_Fields[] = $The_Field['name'];
	
		$The_Relational_Data = $The_Field['relational_data'];
		
		$The_Date_Data = $The_Field['date_data'];
		
		$The_Explanatory_Text = $The_Field['explanatory_text'];

		if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) :

			echo '<li class="list_item">';
	
			echo '<div class="list_item_column">';
			
		endif;
		
		if ($The_Input_Indication_To_Use_Field_Labels) :
		
			if ($The_Field['type'] == 'Group Permission' || $The_Field['type'] == 'User Permission') echo '<strong>';
		
			echo $The_Field['display_name'];
			
			if ($The_Field['is_required']) echo ' *';
			
			if ($The_Explanatory_Text) :
			
				echo '<br /><small>' . $The_Explanatory_Text . '</small>';
				
			endif;
			
			if ($The_Field['type'] == 'Group Permission' || $The_Field['type'] == 'User Permission') echo '</strong>';
			
		endif;
	
// commented out to keep the RHA single-field public-facing form formatted properly
//		echo '</div>';
		
		if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) echo '</div>';
		
		if ($The_Field['type'] == 'Group Permission' || $The_Field['type'] == 'User Permission' || $The_Field['type'] == 'Text Area') echo '<br />';
		
		if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) echo '<div class="list_item_double_wide_column">';
		
		// set the field id
		if ($The_Field['type'] == 'Group Permission') $The_Field_ID = $The_Group_Tag . $The_Field['name'];
		elseif ($The_Field['type'] == 'User Permission') $The_Field_ID = $The_User_Tag . $The_Field['name'];
		else $The_Field_ID = $The_Submit_Tag . $The_Field['name'];
		
		// COMPONENT
		
		switch ($The_Field['type']) :

		case 'Text' :
		case 'Number' :
		case 'Decimal' :
		
			echo '<input type="text" ';
			
			if ($The_Input_Element_Class_Array['Text'] || $The_Field['is_required']) echo 'class="';
			
			$The_Text_Input_Classes = $The_Input_Element_Class_Array['Text'];
			
			$The_Text_Input_Classes .= ($The_Field['is_required']) ? ' required' : '';
			
			$The_Text_Input_Classes .= ($The_Field['type'] == 'Number') ? ' number' : '';
			
			$The_Text_Input_Classes .= ($The_Field['type'] == 'Decimal') ? ' decimal' : '';
			
			echo trim($The_Text_Input_Classes);
			
			if ($The_Input_Element_Class_Array['Text'] || $The_Field['is_required']) echo '" ';
			
			echo 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" ';
			
			if ($The_Field['type'] == 'Text') :
			
				if (is_numeric($The_Field['input_control_width'])) :
				
					if ($The_Field['input_control_width'] > 0) :
					
						echo 'size="' . $The_Field['input_control_width'] . '" ';
						
					endif;
					
				endif;
				
				if (is_numeric($The_Field['character_limit']) && $The_Field['character_limit'] > 0) :
					
					echo 'maxlength="' . $The_Field['character_limit'] . '" ';
					
				else :
					
					preg_match('{(\d+)}', $THE_FIELD_TYPE_ARRAY['Text']['sql_field_type'], $m); 

					echo 'maxlength="' . $m[1] . '" ';
						
				endif;
			
			endif;
			
			echo '/>';

			break;

		case 'Text Area' :

			echo '</div>';
			
			echo '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
			
			echo '<div class="list_item_double_wide_column">';
			
			echo '<textarea ';
			
			if ($The_Input_Element_Class_Array['Text Area'] || $The_Field['is_required']) echo 'class="';
			
			$The_Text_Area_Classes = $The_Input_Element_Class_Array['Text Area'];
			
			$The_Text_Area_Classes .= ($The_Field['is_required']) ? ' required' : '';
			
			echo trim($The_Text_Area_Classes);
			 
			if ($The_Input_Element_Class_Array['Text Area'] || $The_Field['is_required']) echo '"';
			
			echo 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"';
			
			if (is_numeric($The_Field['character_limit']) && $The_Field['character_limit'] > 0) :
					
				echo ' maxlength="' . $The_Field['character_limit'] . '"';
				
			endif;
			
			echo '></textarea>';

			break;
			
		case 'Date' :
		
			$The_Months = array(''=>'', '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
								'07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
			
			$The_Month_Field_ID = $The_Field['name'] . '_month_create';
			
			$The_Day_Field_ID = $The_Field['name'] . '_day_create';
			
			$The_Year_Field_ID = $The_Field['name'] . '_year_create';
				
			echo '<select ';
			
			if ($The_Input_Element_Class_Array['Date']) echo 'class="' . $The_Input_Element_Class_Array['Date'] . '" ';
			
			echo 'onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Month_Field_ID . '" id="' . $The_Month_Field_ID . '">';
			
			if (is_array($The_Months)) foreach ($The_Months as $The_Month_Value => $The_Month_String) :
				echo '<option value="' . $The_Month_Value . '">' . $The_Month_String . '</option>';
			endforeach;
			
			echo '</select>';
			
			echo '<select ';
			
			if ($The_Input_Element_Class_Array['Date']) echo 'class="' . $The_Input_Element_Class_Array['Date'] . '" ';
			
			echo 'onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Day_Field_ID . '" id="' . $The_Day_Field_ID . '">';
			
			echo '<option value=""></option>';
			
			for ($The_Day = 1; $The_Day <= 31; $The_Day++) :
				echo '<option value="' . $The_Day . '">' . $The_Day . '</option>';
			endfor;
			
			echo '</select>';
			
			echo '<select ';
			
			if ($The_Input_Element_Class_Array['Date']) echo 'class="' . $The_Input_Element_Class_Array['Date'] . '" ';
			
			echo 'onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Year_Field_ID . '" id="' . $The_Year_Field_ID . '">';
			
			echo '<option value=""></option>';
			
			for ($The_Year = $The_Date_Data['start_year']; $The_Year <= $The_Date_Data['end_year']; $The_Year++) :
				echo '<option value="' . $The_Year . '">' . $The_Year . '</option>';
			endfor;
			
			echo '</select>';
			
			echo '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" ';
			
			if ($The_Field['is_required']) echo 'class="required" ';
			
			echo '/>';
			
			break;

		case 'File' :
		case 'Video' :
		
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('NEWSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('NEWSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'CREATESUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_file.php';
			echo '<form ';
			
			if ($The_Input_Element_Class_Array['File']) echo 'class="' . $The_Input_Element_Class_Array['File'] . '" ';
			
			echo 'action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			echo '<input type="hidden" name="itemtype" value="image" />';
			echo '<input type="hidden" name="maxSize" value="9999999999" />';
			echo '<input type="hidden" name="maxW" value="960" />';
			echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			echo '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			echo '<input type="hidden" name="filename" value="filename" />';
			echo '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			echo "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			echo '</form>';
			echo '<div id="' . $The_Upload_Area . '"></div>';
			
			break;
			
		case 'Secure File' :
			
			$The_Upload_Relative_Path = 'mimik_temp_secure_uploads/' . str_replace('NEWSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik_temp_secure_uploads/' . str_replace('NEWSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'CREATESUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_secure_file.php';
			echo '<form ';
			
			if ($The_Input_Element_Class_Array['Secure File']) echo 'class="' . $The_Input_Element_Class_Array['Secure File'] . '" ';
			
			echo 'action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			echo '<input type="hidden" name="itemtype" value="image" />';
			echo '<input type="hidden" name="maxSize" value="9999999999" />';
			echo '<input type="hidden" name="maxW" value="960" />';
			//echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			echo '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			echo '<input type="hidden" name="filename" value="filename" />';
			echo '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			echo "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			echo '</form>';
			echo '<div id="' . $The_Upload_Area . '"></div>';
			
			break;
		
		case 'Image' :
		
			//echo '<input type="file" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" size="20" />';
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('NEWSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('NEWSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload.php';
			echo '<form ';
			
			if ($The_Input_Element_Class_Array['Image']) echo 'class="' . $The_Input_Element_Class_Array['Image'] . '" ';
			
			echo 'action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			echo '<input type="hidden" name="itemtype" value="image" />';
			echo '<input type="hidden" name="maxSize" value="9999999999" />';
			echo '<input type="hidden" name="maxW" value="960" />';
			echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			echo '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			echo '<input type="hidden" name="colorR" value="255" />';
			echo '<input type="hidden" name="colorG" value="255" />';
			echo '<input type="hidden" name="colorB" value="255" />';
			echo '<input type="hidden" name="maxH" value="960" />';
			echo '<input type="hidden" name="filename" value="filename" />';
			echo '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			echo "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			echo '</form>';
			echo '<div id="' . $The_Upload_Area . '"></div>';
			
			break;
			
		case 'WYSIWYG' :
		
			$The_Number_Of_Slow_Loading_Fields++;
		
			echo '</div>';
			
			echo '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
			
			$The_Javascript_Array_Of_WYSIWYG_IDs .= 'The_Javascript_Array_Of_WYSIWYG_IDs[' . $The_WYSIWYG_Counter++ . '] = \'' . $The_Field_ID . '\';';
			
			echo '<div class="list_item_double_wide_column">';
		
			include_once($_SERVER['DOCUMENT_ROOT'] . "/mimik/fckeditor/fckeditor.php") ;
			$oFCKeditor = new FCKeditor($The_Field_ID) ;
			$oFCKeditor->ToolbarSet = 'MimikToolbar';
			$oFCKeditor->Width = '600px';
			$oFCKeditor->Height = '300px';
			$oFCKeditor->BasePath = '../fckeditor/' ;
			$oFCKeditor->Value = '';
			$oFCKeditor->Create();
			
			break;
			
		case 'Static Select' :
			$The_Options = explode("\n",$The_Field['options_text']);
			
			if (is_array($The_Options)) :
				echo '<select ';
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Field['is_required']) echo 'class="';
				
				$The_Static_Select_Input_Classes = $The_Input_Element_Class_Array['Static Select'];
				$The_Static_Select_Input_Classes .= ($The_Field['is_required']) ? ' required' : '';
				echo trim($The_Static_Select_Input_Classes);
				
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Field['is_required']) echo '" ';
				echo 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"><option></option>';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Select_Key = substr($The_Option,0,$Separator);
						$The_Static_Select_Value = substr($The_Option,$Separator+1);
						echo '<option value="' . $The_Static_Select_Key . '">' . $The_Static_Select_Value . '</option>';
					else:
						echo '<option value="' . $The_Option . '">' . $The_Option . '</option>';
					endif;
				endforeach;
				echo '</select>';
			endif;
			
			break;
		
		case 'Dynamic Select' :
		case 'Dynamic Radio' :

			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_set_of_aggregated_value_definitions.inc.php' );
			
			$The_Database_To_Use = new A_Mimik_Database_Interface;
			$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT']."/mimik/mimik_configuration/database_connection_info.csv" );
			$The_Database_To_Use->Establishes_A_Connection();
			
			$The_Set_Of_Aggregated_Value_Definitions = new A_Set_Of_Aggregated_Value_Definitions($The_Database_To_Use);
			
			$The_Aggregated_Value_Definition_Information = $The_Database_To_Use->Gets_The_Dynamic_Relation_Information_For($The_Field['id']);

			if (is_array($The_Aggregated_Value_Definition_Information['row_ids'])) foreach ($The_Aggregated_Value_Definition_Information['row_ids'] as $The_Row_ID) :

				$The_Aggregated_Value_Definition = new An_Aggregated_Value_Definition(
																$The_Aggregated_Value_Definition_Information['table_id'],
																$The_Aggregated_Value_Definition_Information['column_ids'],
																$The_Row_ID );
			
				$The_Set_Of_Aggregated_Value_Definitions->Appends_The_Aggregated_Value_Definition($The_Aggregated_Value_Definition);
			
			endforeach;
			
			$The_Result_Set_Of_Aggregated_Value_Pairs = $The_Set_Of_Aggregated_Value_Definitions->Gets_The_Set_Of_Aggregated_Values(' ');

			$The_Result_Set_Of_Aggregated_Value_Pairs->Sorts_Alphabetically();

			if ($The_Field['is_required']) $The_Required_Class = 'required';
			else $The_Required_Class = '';
			
			if ($The_Field['type'] == 'Dynamic Select') :
				echo $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Select_Box($The_Field_ID, NULL, NULL, $The_Required_Class);
			elseif ($The_Field['type'] == 'Dynamic Radio') :
				echo $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Radio_Buttons($The_Field_ID, NULL, NULL, $The_Required_Class);
			endif;

			break;
		
		case 'Static Radio' :
			$The_Options = explode("\n",$The_Field['options_text']);
			
			if (is_array($The_Options)) :
				$The_Static_Radio_Input_Classes = $The_Input_Element_Class_Array['Static Radio'];
				$The_Static_Radio_Input_Classes .= ($The_Field['is_required']) ? ' required' : '';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Radio_Key = substr($The_Option,0,$Separator);
						$The_Static_Radio_Value = substr($The_Option,$Separator+1);
					else:
						$The_Static_Radio_Key = $The_Option;
						$The_Static_Radio_Value = $The_Option;
					endif;
					
					echo '<input type="radio"';
					if ($The_Input_Element_Class_Array['Static Radio'] || $The_Field['is_required']) echo ' class="' . $The_Static_Radio_Input_Classes . '"';
					echo ' name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Static_Radio_Key . '">' . $The_Static_Radio_Value . '<br/>';
				endforeach;
			endif;
			
			break;
			
		case 'Group Permission' :
	
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
			
			$The_Database_To_Use = new A_Mimik_Database_Interface;
			$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT']."/mimik/mimik_configuration/database_connection_info.csv" );
			$The_Database_To_Use->Establishes_A_Connection();
			
			$All_Groups = $The_Database_To_Use->All_Groups();
			
			if (is_array($All_Groups)) :
			
				$The_Random_Group_Permission_Input_ID = uniqid('group');
				
				echo '<form autocomplete="off" method="get">';
				
				echo 'Filter on name: <input id="' . $The_Random_Group_Permission_Input_ID . '" type="text" name="' . $The_Random_Group_Permission_Input_ID . '" value=""/>';
			
				echo '<ul id="' . $The_Group_Div_Name . '" class="height200 scrollable">';
			
				foreach ($All_Groups as $The_Group_Information) :
				
					echo '<li><div class="list_item_column"><span class="filterable">' . $The_Group_Information['name'] . '</span></div>';
					
					echo '<div class="list_item_narrow_column">';
					
					echo '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_Group_Information['id'] . '" ';
					
					if ($The_Field['is_required']) echo 'class="required" ';
					
					echo '/></div><div class="clear"></div></li>';
				
				endforeach;
				
				echo '</ul></form><br />';
				
				echo '<img onload="$(\'#' . $The_Random_Group_Permission_Input_ID . '\').live(\'keyup\', function(){ $(\'#' . $The_Random_Group_Permission_Input_ID . '\').liveUpdate(\'#' . $The_Group_Div_Name . '\').focus(); });" id="" src="/mimik/mimik_images/blank.gif"/>';
			
			else :
			
				echo 'No Groups';
			
			endif;
			
			break;
			
		case 'User Permission' :

			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
			
			$The_Database_To_Use = new A_Mimik_Database_Interface;
			$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT']."/mimik/mimik_configuration/database_connection_info.csv" );
			$The_Database_To_Use->Establishes_A_Connection();
			
			$All_Users = $The_Database_To_Use->All_Users();
			
			if (is_array($All_Users)) :
			
				$The_Random_User_Permission_Input_ID = uniqid('user');

				echo '<form autocomplete="off" method="get">';

				echo 'Filter on name: <input id="' . $The_Random_User_Permission_Input_ID . '" type="text" name="' . $The_Random_User_Permission_Input_ID . '" value=""/>';

				echo '<ul id="' . $The_User_Div_Name . '" class="height200 scrollable width400">';

				foreach ($All_Users as $The_User_Information) :

					echo '<li><div class="list_item_double_wide_column">';
					
					echo '<span class="filterable">' . $The_User_Information['login'] . '</span></div>';

					echo '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_User_Information['id'] . '" ';
					
					if ($The_Field['is_required']) echo 'class="required" ';
					
					echo '/>';

					echo '</li>';

				endforeach;
				
				echo '</ul></form><br />';
				
				echo '<img onload="$(\'#' . $The_Random_User_Permission_Input_ID . '\').live(\'keyup\', function(){ $(\'#' . $The_Random_User_Permission_Input_ID . '\').liveUpdate(\'#' . $The_User_Div_Name. '\').focus(); });" id="" src="/mimik/mimik_images/blank.gif"/>';
			
			else :
			
				echo 'No Users';
			
			endif;
			
			break;

		default :

			echo '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" ';
			
			if ($The_Field['is_required']) echo 'class="required" ';
			
			echo '/>';

			break;

		endswitch;
		
		if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) :
		
			echo '</div>';
			
			echo '</li>';

		endif;

	endforeach;

	if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) echo '<li class="list_item">* Required field</li></ul>';
	
	else echo '</div>';
	
	echo '<script>';
		echo '$(\'.ok_button_container a\').hide();';
		echo '$(\'.ok_button_container span\').show();';
		echo '$(document).ready(function() {';
			echo 'setTimeout(function() {';
				echo '$(\'.ok_button_container span\').hide();';
				echo '$(\'.ok_button_container a\').show();';
			echo '}, ' . ($The_Number_Of_Slow_Loading_Fields * 1500) . ');';
		echo '});';
	echo '</script>';
	
	if ($The_Input_HTML_Suffix) echo $The_Input_HTML_Suffix;
	
	if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) echo '<p class="admin_control_box">';
	
	echo '<span class="ok_button_container">';
	
	//echo '<span>OK</span>';

	echo '<a href="#" ';
	
	if ($The_Input_Element_Class_Array['OK']) echo 'class="' . $The_Input_Element_Class_Array['OK'] . '" ';
	
	echo 'onclick="if (Verifies_That_Required_Fields_Are_Filled()) { ' . $The_Javascript_Array_Of_WYSIWYG_IDs . 'Populate_The_WYSIWYG_Editors(The_Javascript_Array_Of_WYSIWYG_IDs); Modify_The_Submission_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(\'NEW\', \'' . $The_Input_Submission_GUID . '\', \'' . $The_Submit_Tag . '\', \'' . $The_Group_Tag . '\', \'' . $The_User_Tag . '\', \'' . $The_List_Name . '\', ' . $The_Input_Form_ID;
	
	if ($The_Input_Indication_Of_Admin_Display) echo ',true';
	else echo ',false';
	
	if ($The_Input_Indication_Of_Admin_Display) echo ', $(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\')';
	
	else echo ', \'public_form_submission_creator\'';
	
	echo '); return false;} else { alert(\'At least one required field is blank or contains an invalid value.\'); return false; }">OK</a>';
	
	echo '</span>';
	
	if ($The_Input_Indication_Of_Admin_Display || count($The_Input_Fields) > 1) :
	
		echo ' | ';
	
		echo '<a href="#" onclick="Delete_The_Temp_Data_For_The_GUID(\'' . str_replace('NEWSUBMISSION:', '', $The_Input_Submission_GUID) . '\', ' . $The_Input_Form_ID . ');';
		
		if ($The_Input_Indication_Of_Admin_Display) echo '$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_submissions&form_id=' . $The_Input_Form_ID . '\');';
		
		echo 'return false;">Cancel</a>';
	
		echo '</p>';
		
	endif;

	return $The_HTML;

}

function The_HTML_For_The_Editor_For_The_Group($The_Input_Target_Div_Name, $The_Input_Group_ID, $The_Input_Group_Information, $The_Input_Admin_Permissions, $The_Input_Group_Custom_Fields = NULL, $The_Input_Groups = NULL)
{
	$The_HTML = '';
	
	$The_Submit_Prefix = 'EDIT_GROUP:';
	
	$The_Group_Name_Tag = $The_Submit_Prefix . 'name';
	
	$The_List_Name = 'group_items';
	
	$The_Default_Tag = $The_Submit_Prefix . 'is_default';
	
	$The_HTML .= '<ul class="list" id="' . $The_List_Name . '">';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Name';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<input type="text" name="' . $The_Group_Name_Tag . '" id="' . $The_Group_Name_Tag . '" value="' . $The_Input_Group_Information['name'] . '" />';;
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Create Date';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= $The_Input_Group_Information['create_date'];
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Modify Date';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= $The_Input_Group_Information['modify_date'];
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Created By';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= $The_Input_Group_Information['creator_user'];
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Last Modified By';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= $The_Input_Group_Information['modifier_user'];
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Default for New Users?';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<input type="checkbox" name="' . $The_Default_Tag . '" id="' . $The_Default_Tag . '" value="CHECK:on" ';
	if ($The_Input_Group_Information['is_default']) :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Parent Group</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<select id="' . $The_Submit_Prefix . 'parent_group_id" name="' . $The_Submit_Prefix . 'parent_group_id">';
	$The_HTML .= '<option value="">None</option>';
	if (is_array($The_Input_Groups)) foreach ($The_Input_Groups as $The_Group) :
		if ($The_Group['id'] != $The_Input_Group_Information['id']) :
			$Death_Loop = false;
			if (is_array($The_Group['parent_group_tree'])) :
				if (in_array($The_Input_Group_Information['id'], $The_Group['parent_group_tree'])) :
					$Death_Loop = true;
				endif;
			endif;
			if (!$Death_Loop) :
				$The_HTML .= '<option ';
					if ($The_Group['id'] == $The_Input_Group_Information['parent_group_id']) :
						$The_HTML .= 'selected="selected" ';
					endif;
				$The_HTML .= 'value="' . $The_Group['id'] . '">' . $The_Group['name'] . '</option>';
			endif;
		endif;
	endforeach;
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_Group_Custom_Fields)) foreach ($The_Input_Group_Custom_Fields as $The_Custom_Field) :
	
		$The_HTML .= '<li class="list_item">';
		
		$The_HTML .= '<div class="list_item_column">';
		
		$The_HTML .= $The_Custom_Field['display_name'];
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '<div class="list_item_double_wide_column">';
		
		$The_Field_ID = $The_Submit_Prefix . $The_Custom_Field['name'];
		
		$The_Value = $The_Input_Group_Information[$The_Custom_Field['name']];

		// COMPONENT

		switch ($The_Custom_Field['type']) :
		
		case 'Text' :
		case 'Number' :
		case 'Decimal' :

			$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" onKeyPress="return Disable_Enter_Key(event);" ';
			
			if ($The_Custom_Field['type'] == 'Text') :
			
				if (is_numeric($The_Custom_Field['input_control_width'])) :
				
					if ($The_Custom_Field['input_control_width'] > 0) :
					
						$The_HTML .= 'size="' . $The_Custom_Field['input_control_width'] . '" ';
						
					endif;
					
				endif;
				
				if (is_numeric($The_Field['character_limit']) && $The_Custom_Field['character_limit'] > 0) :
					
					$The_HTML .= 'maxlength="' . $The_Custom_Field['character_limit'] . '" ';
					
				else :
					
					preg_match('{(\d+)}', $THE_FIELD_TYPE_ARRAY['Text']['sql_field_type'], $m); 

					$The_HTML .= 'maxlength="' . $m[1] . '" ';
						
				endif;
			
			endif;
			
			$The_HTML .= '/>';

			break;

		case 'Text Area' :
		
			$The_HTML .= '</div>';
			
			$The_HTML .= '<div class="clear"></div>';

			$The_HTML .= '<textarea name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"';
			
			if (is_numeric($The_Field['character_limit']) && $The_Field['character_limit'] > 0) :
					
				$The_HTML .= ' maxlength="' . $The_Field['character_limit'] . '"';
				
			endif;
			
			$The_HTML .= '>' . $The_Value . '</textarea>';

			break;
			
		case 'Date' :
		
			$The_Date_Data = $The_Custom_Field['date_data'];
			
			$The_Current_Year_Value = (int) substr($The_Value, 0, 4);
			
			$The_Current_Month_Value = substr($The_Value, 5, 2);
			
			$The_Current_Day_Value = (int) substr($The_Value, 8, 2);
			
			$The_Months = array(''=>'', '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
								'07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
			
			$The_Month_Field_ID = $The_Field['name'] . '_month_edit';
			
			$The_Day_Field_ID = $The_Field['name'] . '_day_edit';
			
			$The_Year_Field_ID = $The_Field['name'] . '_year_edit';
				
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Month_Field_ID . '" id="' . $The_Month_Field_ID . '">';
			
			$The_HTML .= '<option value=""></option>';
			
			if (is_array($The_Months)) foreach ($The_Months as $The_Month_Value => $The_Month_String) :
				$The_HTML .= '<option value="' . $The_Month_Value . '"';
				if ($The_Current_Month_Value == $The_Month_Value) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Month_String . '</option>';
			endforeach;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Day_Field_ID . '" id="' . $The_Day_Field_ID . '">';
			
			$The_HTML .= '<option value=""></option>';
			
			for ($The_Day = 1; $The_Day <= 31; $The_Day++) :
				$The_HTML .= '<option value="' . $The_Day . '"';
				if ($The_Current_Day_Value == $The_Day) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Day . '</option>';
			endfor;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Year_Field_ID . '" id="' . $The_Year_Field_ID . '">';
			
			for ($The_Year = $The_Date_Data['start_year']; $The_Year <= $The_Date_Data['end_year']; $The_Year++) :
				$The_HTML .= '<option value="' . $The_Year . '"';
				if ($The_Current_Year_Value == $The_Year) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Year . '</option>';
			endfor;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" />';
			
			break;
			
		case 'File' :
		case 'VIdeo' :
			
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_file.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
				$The_HTML .= '<p>URL : <a href="' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '" target="_blank">' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '</a></p>';
//				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
			
		case 'Secure File' :
			
			$The_Value_Array = explode('/', $The_Value);
			$The_Current_Filename = $The_Value_Array[count($The_Value_Array)-1];
			$The_Upload_Relative_Path = 'mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_secure_file.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			//echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a>';
				$The_HTML .= '<p>FILE : <a href="../mimik_live_data/secure_file.php?filename=' . urlencode(str_replace('mimik_secure_uploads/', '', $The_Value)) . '" target="_blank">' . $The_Current_Filename . '</a></p>';
//				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
		
		case 'Image' :
		
			//echo '<input type="file" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" size="20" />';
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="colorR" value="255" />';
			$The_HTML .= '<input type="hidden" name="colorG" value="255" />';
			$The_HTML .= '<input type="hidden" name="colorB" value="255" />';
			$The_HTML .= '<input type="hidden" name="maxH" value="960" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= '<p>';
			$The_HTML .= "<input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" />";
			$The_HTML .= "</p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
				$The_HTML .= '<img style="max-width:200px;border:none;" src="../mimik_uploads/' . $The_Value .'" border="0" /><br />';
				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
		
		case 'Static Select' :
			$The_Options = explode("\n",$The_Custom_Field['options_text']);
			
			if (is_array($The_Options)) :
				$The_HTML .= '<select ';
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= 'class="';
				
				$The_Static_Select_Input_Classes = $The_Input_Element_Class_Array['Static Select'];
				$The_Static_Select_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
				$The_HTML .= trim($The_Static_Select_Input_Classes);
				
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= '" ';
				$The_HTML .= 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"><option></option>';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Select_Key = substr($The_Option,0,$Separator);
						$The_Static_Select_Value = substr($The_Option,$Separator+1);
						$The_HTML .= '<option value="' . $The_Static_Select_Key . '"';
						if($The_Value == $The_Static_Select_Key) $The_HTML .= ' selected="selected"';
						$The_HTML .= '>' . $The_Static_Select_Value . '</option>';
					else:
						$The_HTML .= '<option value="' . $The_Option . '"';
						if($The_Value == $The_Option) $The_HTML .= ' selected="selected"';
						$The_HTML .= '>' . $The_Option . '</option>';
					endif;
				endforeach;
				$The_HTML .= '</select>';
			endif;
			
			break;
			
		case 'Static Radio' :
			$The_Options = explode("\n",$The_Custom_Field['options_text']);
			
			if (is_array($The_Options)) :
				$The_Static_Radio_Input_Classes = $The_Input_Element_Class_Array['Static Radio'];
				$The_Static_Radio_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Radio_Key = substr($The_Option,0,$Separator);
						$The_Static_Radio_Value = substr($The_Option,$Separator+1);
					else:
						$The_Static_Radio_Key = $The_Option;
						$The_Static_Radio_Value = $The_Option;
					endif;
					
					$The_HTML .= '<input type="radio"';
					if ($The_Input_Element_Class_Array['Static Radio'] || $The_Custom_Field['is_required']) $The_HTML .= ' class="' . $The_Static_Radio_Input_Classes . '"';
					$The_HTML .= ' name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Static_Radio_Key . '"';
					if ($The_Value == $The_Static_Radio_Key) $The_HTML .= ' checked="checked"';
					$The_HTML .= '>' . $The_Static_Radio_Value . '<br/>';
				endforeach;
			endif;
			
			break;
		
		case 'Dynamic Select' :
		case 'Dynamic Radio' :
			$The_HTML .= '<em>Dynamic Fields are disabled</em>';
			/*
			$The_Value = $The_Field['value'];
			
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_set_of_aggregated_value_definitions.inc.php' );
			
			$The_Database_To_Use = new A_Mimik_Database_Interface;
			$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT']."/mimik/mimik_configuration/database_connection_info.csv" );
			$The_Database_To_Use->Establishes_A_Connection();
			
			$The_Set_Of_Aggregated_Value_Definitions = new A_Set_Of_Aggregated_Value_Definitions($The_Database_To_Use);
			
			$The_Aggregated_Value_Definition_Information = $The_Database_To_Use->Gets_The_Dynamic_Relation_Information_For($The_Field['id']);

			if (is_array($The_Aggregated_Value_Definition_Information['row_ids'])) foreach ($The_Aggregated_Value_Definition_Information['row_ids'] as $The_Row_ID) :

				$The_Aggregated_Value_Definition = new An_Aggregated_Value_Definition(
																$The_Aggregated_Value_Definition_Information['table_id'],
																$The_Aggregated_Value_Definition_Information['column_ids'],
																$The_Row_ID );
			
				$The_Set_Of_Aggregated_Value_Definitions->Appends_The_Aggregated_Value_Definition($The_Aggregated_Value_Definition);
			
			endforeach;
			
			$The_Result_Set_Of_Aggregated_Value_Pairs = $The_Set_Of_Aggregated_Value_Definitions->Gets_The_Set_Of_Aggregated_Values(' ');
			
			if ($The_Field['is_required']) $The_Required_Class = 'required';
			else $The_Required_Class = '';
			
			if ($The_Field['type'] == 'Dynamic Select') :
				$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Select_Box($The_Field_ID, $The_Value, NULL, $The_Required_Class);
			elseif ($The_Field['type'] == 'Dynamic Radio') :
				$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Radio_Buttons($The_Field_ID, $The_Value, NULL, $The_Required_Class);
			endif;
			*/
			break;
			
		case 'WYSIWYG' :
		
			$The_HTML .= '</div>';
			
			$The_HTML .= '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
			
			$The_HTML .= '<div class="list_item_double_wide_column">';

			$The_Javascript_Array_Of_WYSIWYG_IDs .= 'The_Javascript_Array_Of_WYSIWYG_IDs[' . $The_WYSIWYG_Counter++ . '] = \'' . $The_Field_ID . '\';';
			
			include_once($_SERVER['DOCUMENT_ROOT'] . "/mimik/fckeditor/fckeditor.php") ;
			$oFCKeditor = new FCKeditor($The_Field_ID) ;
			$oFCKeditor->ToolbarSet = 'MimikToolbar';
			$oFCKeditor->Width = '600px';
			$oFCKeditor->Height = '300px';
			$oFCKeditor->BasePath = '../fckeditor/' ;
			$oFCKeditor->Value = $The_Value;
			$oFCKeditor->Create();
			
			break;
		
		case 'Group Permission' :
		
			$All_Groups = $The_Field['group_permission_data'];
			
			if (is_array($All_Groups)) :
			
				$The_HTML .= '<div id="' . $The_Group_Div_Name . '">';
			
				if (is_array($All_Groups)) foreach ($All_Groups as $The_Group_Information) :
				
					$The_HTML .= '<div class="list_item_column">' . $The_Group_Information['name'] . '</div>';
					
					$The_HTML .= '<div class="list_item_narrow_column"><input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_Group_Information['id'] . '" ';
					
					if (is_array($The_Field['value'])) :
					
						if (in_array($The_Group_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
						
					endif;
					
					$The_HTML .= '/></div>';
				
				endforeach;
				
				$The_HTML .= '</div>';
			
			else :
			
				$The_HTML .= 'No Groups';
			
			endif;
			
			break;
			
		case 'User Permission' :
		
			$All_Users = $The_Field['user_permission_data'];
			
			if (is_array($All_Users)) :
			
				$The_HTML .= '<div id="' . $The_Group_Div_Name . '">';
			
				if (is_array($All_Users)) foreach ($All_Users as $The_User_Information) :
				
					$The_HTML .= '<div class="list_item_double_wide_column">' . $The_User_Information['login'] . '</div>';
					
					$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_User_Information['id'] . '" ';
					
					if (is_array($The_Field['value'])) :
					
						if (in_array($The_User_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
						
					endif;
					
					$The_HTML .= '/>';
				
				endforeach;
				
				$The_HTML .= '</div>';
			
			else :
			
				$The_HTML .= 'No Users';
			
			endif;
			
			break;

		default :

			$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';

			break;

		endswitch;
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '</li>';
	
	endforeach;

	$The_HTML .= '<li class="list_item">';

	$The_HTML .= '<div class="list_item_column"><strong>Permissions</strong></div>';

	$The_HTML .= '</li>';
	
	if (is_array($The_Input_Admin_Permissions)) foreach ($The_Input_Admin_Permissions as $The_Admin_Permission) :
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= $The_Admin_Permission['display_name'];
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_narrow_column">';
		$The_HTML .= '<input type="checkbox" name="' . $The_Submit_Prefix . 'permission:' . $The_Admin_Permission['id'] . '" id="' . $The_Permissions_Prefix . $The_Admin_Permission['id'] . '" value="CHECK:on" ';
		if ($The_Admin_Permission['used']) $The_HTML .= 'checked="checked" ';
		$The_HTML .= '/>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	endforeach;

	$The_HTML .= '</ul>';

	$The_HTML .= '<p class="admin_control_box">';

	$The_HTML .= '<a href="#" onclick="Modify_The_Group(' . $The_Input_Group_ID . ', \'' . $The_List_Name . '\', \'' . $The_Submit_Prefix . '\', $(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\')); return false;">OK</a> | ';

	$The_HTML .= '<a href="#" onclick="';
	$The_HTML .= '$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');';
	$The_HTML .= 'return false;">Cancel</a>';

	$The_HTML .= '</p>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_Editor_For_The_User($The_Input_User_ID, $The_Input_User_Name, $The_Input_Groups, $The_Input_User_Information, $The_Input_Group_Membership_Information, $The_Input_User_Custom_Fields = NULL, $The_Input_Indication_Of_Admin_Display = true, $Moderation_Is_Required = true, $Email_Is_Login = false, $Allow_Login_Change = false)
{
	$The_Submit_Tag = 'EDIT_USER:';

	$The_User_List_Name = 'user_modification_list';
	
	if ($The_Input_Indication_Of_Admin_Display) $The_Group_List_Name = 'user_modification_group_membership_list';

	$The_HTML = '';
	
	$The_HTML .= '<ul class="list" id="' . $The_User_List_Name . '">';
	
	$The_Field_ID = $The_Submit_Tag . 'login';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Login';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	if ($Allow_Login_Change) :
		$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Input_User_Name . '" />';
	else :
		$The_HTML .= $The_Input_User_Name;
	endif;
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if ($The_Input_Indication_Of_Admin_Display) :
	
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Create Date';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_HTML .= $The_Input_User_Information['create_date'];
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Modify Date';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_HTML .= $The_Input_User_Information['modify_date'];
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
	endif;
	
	// password
	
	$The_Password_Field_ID = 'password';
	$The_Password_Confirm_Field_ID = 'password_confirm';
	
	if (!$The_Input_Indication_Of_Admin_Display) :
	
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<a href="#" onclick="' .
									'$(\'#' . $The_Password_Field_ID . '\').removeAttr(\'disabled\'); ' .
									'$(\'#' . $The_Password_Field_ID . '\').val(\'\'); ' .
									'$(\'#' . $The_Password_Confirm_Field_ID . '\').removeAttr(\'disabled\'); ' .
									'$(\'#' . $The_Password_Confirm_Field_ID . '\').val(\'\'); ' .
									'return false;">' .
									'Update Password</a>';
		$The_HTML .= '</div>';
		$The_HTML .= '<ul id="password_items">';
		
	endif;
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Password';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	if ($The_Input_Indication_Of_Admin_Display) $The_HTML .= '<input onkeyup="Confirm_The_Password(this.value, \'password_confirm\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Password_Field_ID . '" id="' . $The_Password_Field_ID . '" />';
	else $The_HTML .= '<input disabled="disabled" onkeyup="Confirm_The_Password(this.value, \'password_confirm\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Password_Field_ID . '" id="' . $The_Password_Field_ID . '" value="DUMMYDATA" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// password confirm
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Confirm Password';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	if ($The_Input_Indication_Of_Admin_Display) $The_HTML .= '<input onkeyup="Confirm_The_Password(this.value, \'password\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Password_Confirm_Field_ID . '" id="' . $The_Password_Confirm_Field_ID . '" />';
	else $The_HTML .= '<input disabled="disabled" onkeyup="Confirm_The_Password(this.value, \'password\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Password_Confirm_Field_ID . '" id="' . $The_Password_Confirm_Field_ID . '" value="DUMMYDATA" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// password (submitted)
	
	$The_Field_ID = $The_Submit_Tag . 'password';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<small><a href="#" onclick="alert(\'Passwords must be at least 8 characters long with at least one upper and lower-case letter, one number, and one symbol.\');return false;">Password Validation</a></small>';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '<div id="password_feedback"></div>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (!$The_Input_Indication_Of_Admin_Display) :
	
		$The_HTML .= '</ul>';
		$The_HTML .= '</li>';
	
	endif;
	
	// email
	
	$The_Field_ID = $The_Submit_Tag . 'email';
	$The_HTML .= '<li class="list_item" ';
	if ($Email_Is_Login) $The_HTML .= 'style="display:none;" ';
	$The_HTML .= '>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Email';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Input_User_Information['email'] . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	/////////////////////////
	
	if ($The_Input_Indication_Of_Admin_Display && $Moderation_Is_Required) :
	
		$The_Field_ID = $The_Submit_Tag . 'moderation_status';
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Moderation Status';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<select name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" onchange="if (this.options[this.selectedIndex].value == \'APPROVED\' || this.options[this.selectedIndex].value == \'DENIED\') { Show_The_Div(\'response_message\'); } else { Hide_The_Div(\'response_message\'); }">';
		$The_HTML .= '<option value="NEW"';
		if ($The_Input_User_Information['moderation_status'] == 'NEW') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>New</option>';
		$The_HTML .= '<option value="APPROVED"';
		if ($The_Input_User_Information['moderation_status'] == 'APPROVED') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>Approved</option>';
		$The_HTML .= '<option value="DENIED"';
		if ($The_Input_User_Information['moderation_status'] == 'DENIED') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>Denied</option>';
		$The_HTML .= '</select>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		/////////////////////////
	
		$The_Field_ID = $The_Submit_Tag . 'response_message';
		$The_HTML .= '<li class="list_item" id="response_message" style="display:none">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Response Message (optional)<br />';
		$The_HTML .= '<small>This will be appended in an form email to the registrant</small>';
		$The_HTML .= '</div><br />';
		$The_HTML .= '<div class="list_item_double_wide_column">';
		$The_HTML .= '<textarea name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"></textarea>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		/////////////////////////
		
		$The_Field_ID = $The_Submit_Tag . 'is_blocked';
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Blocked?';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
		if ($The_Input_User_Information['is_blocked']) :
			$The_HTML .= 'checked="checked" ';
		endif;
		$The_HTML .= '/>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
	endif;
	
	if (is_array($The_Input_User_Custom_Fields)) foreach ($The_Input_User_Custom_Fields as $The_Custom_Field) :
	
		if ($The_Input_Indication_Of_Admin_Display || $The_Custom_Field['is_modifiable_by_user']) :
	
			$The_HTML .= '<li class="list_item">';
			
			$The_HTML .= '<div class="list_item_column">';
			
			$The_HTML .= $The_Custom_Field['display_name'];
			
			$The_HTML .= '</div>';
			
			if ($The_Field['type'] == 'Text Area') $The_HTML .= '<br />';
			
			$The_HTML .= '<div class="list_item_double_wide_column">';
			
			$The_Field_ID = $The_Submit_Tag . $The_Custom_Field['name'];
			
			$The_Value = $The_Input_User_Information[$The_Custom_Field['name']];
	
			// COMPONENT
	
			switch ($The_Custom_Field['type']) :
			
			case 'Text' :
			case 'Number' :
			case 'Decimal' :
	
				$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" onKeyPress="return Disable_Enter_Key(event);" ';
				
				if ($The_Custom_Field['type'] == 'Text') :
			
				if (is_numeric($The_Custom_Field['input_control_width'])) :
				
					if ($The_Custom_Field['input_control_width'] > 0) :
					
						$The_HTML .= 'size="' . $The_Custom_Field['input_control_width'] . '" ';
						
					endif;
					
				endif;
				
				if (is_numeric($The_Custom_Field['character_limit']) && $The_Custom_Field['character_limit'] > 0) :
					
					$The_HTML .= 'maxlength="' . $The_Custom_Field['character_limit'] . '" ';
					
				else :
					
					preg_match('{(\d+)}', $THE_FIELD_TYPE_ARRAY['Text']['sql_field_type'], $m); 

					$The_HTML .= 'maxlength="' . $m[1] . '" ';
						
				endif;
			
			endif;
				
				$The_HTML .= '/>';
	
				break;
	
			case 'Text Area' :
			
				$The_HTML .= '</div>';
				
				$The_HTML .= '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
	
				$The_HTML .= '<textarea name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"';
				
				if (is_numeric($The_Field['character_limit']) && $The_Field['character_limit'] > 0) :
					
					$The_HTML .= ' maxlength="' . $The_Field['character_limit'] . '"';
					
				endif;
			
				$The_HTML .= '>' . $The_Value . '</textarea>';
	
				break;
				
			case 'Date' :
			
				$The_Date_Data = $The_Custom_Field['date_data'];
				
				$The_Current_Year_Value = (int) substr($The_Value, 0, 4);
				
				$The_Current_Month_Value = substr($The_Value, 5, 2);
				
				$The_Current_Day_Value = (int) substr($The_Value, 8, 2);
				
				$The_Months = array(''=>'', '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
									'07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
				
				$The_Month_Field_ID = $The_Field['name'] . '_month_edit';
				
				$The_Day_Field_ID = $The_Field['name'] . '_day_edit';
				
				$The_Year_Field_ID = $The_Field['name'] . '_year_edit';
					
				$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Month_Field_ID . '" id="' . $The_Month_Field_ID . '">';
				
				if (is_array($The_Months)) foreach ($The_Months as $The_Month_Value => $The_Month_String) :
					$The_HTML .= '<option value="' . $The_Month_Value . '"';
					if ($The_Current_Month_Value == $The_Month_Value) $The_HTML .= ' selected="selected"';
					$The_HTML .= '>' . $The_Month_String . '</option>';
				endforeach;
				
				$The_HTML .= '</select>';
				
				$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Day_Field_ID . '" id="' . $The_Day_Field_ID . '">';
				
				$The_HTML .= '<option value=""></option>';
				
				for ($The_Day = 1; $The_Day <= 31; $The_Day++) :
					$The_HTML .= '<option value="' . $The_Day . '"';
					if ($The_Current_Day_Value == $The_Day) $The_HTML .= ' selected="selected"';
					$The_HTML .= '>' . $The_Day . '</option>';
				endfor;
				
				$The_HTML .= '</select>';
				
				$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Year_Field_ID . '" id="' . $The_Year_Field_ID . '">';
				
				$The_HTML .= '<option value=""></option>';
				
				for ($The_Year = $The_Date_Data['start_year']; $The_Year <= $The_Date_Data['end_year']; $The_Year++) :
					$The_HTML .= '<option value="' . $The_Year . '"';
					if ($The_Current_Year_Value == $The_Year) $The_HTML .= ' selected="selected"';
					$The_HTML .= '>' . $The_Year . '</option>';
				endfor;
				
				$The_HTML .= '</select>';
				
				$The_HTML .= '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" />';
				
				break;
				
			case 'File' :
			case 'Video' :
				
				$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
				$The_Action = '../mimik_support/ajax_upload_file.php';
				$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
				$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
				$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
				$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
				$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
				$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
				$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
				$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
				$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
				$The_HTML .= '</form>';
				$The_HTML .= '<div id="' . $The_Upload_Area . '">';
				if ($The_Value) :
					$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
					$The_HTML .= '<p>URL : <a href="' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '" target="_blank">' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '</a></p>';
	//				$The_HTML .= '../mimik_uploads/' . $The_Value;
				endif;
				$The_HTML .= '</div>';
				
				break;
				
			case 'Secure File' :
				
				$The_Value_Array = explode('/', $The_Value);
				$The_Current_Filename = $The_Value_Array[count($The_Value_Array)-1];
				$The_Upload_Relative_Path = 'mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Full_Path = $THE_BASE_URL . '/mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
				$The_Action = '../mimik_support/ajax_upload_secure_file.php';
				$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
				$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
				$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
				$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
				//echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
				$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
				$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
				$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
				$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
				$The_HTML .= '</form>';
				$The_HTML .= '<div id="' . $The_Upload_Area . '">';
				if ($The_Value) :
					$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a>';
					$The_HTML .= '<p>FILE : <a href=' . $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_live_data/secure_file.php?filename=' . urlencode(str_replace('mimik_secure_uploads/', '', $The_Value)) . '" target="_blank">' . $The_Current_Filename . '</a></p>';
	//				$The_HTML .= '../mimik_uploads/' . $The_Value;
				endif;
				$The_HTML .= '</div>';
				
				break;
			
			case 'Image' :
			
				//echo '<input type="file" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" size="20" />';
				$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Area = $The_Field['name'] . '_upload_area';
				$The_Action = '../mimik_support/ajax_upload.php';
				$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
				$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
				$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
				$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
				$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
				$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
				$The_HTML .= '<input type="hidden" name="colorR" value="255" />';
				$The_HTML .= '<input type="hidden" name="colorG" value="255" />';
				$The_HTML .= '<input type="hidden" name="colorB" value="255" />';
				$The_HTML .= '<input type="hidden" name="maxH" value="960" />';
				$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
				$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
				$The_HTML .= '<p>';
				$The_HTML .= "<input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" />";
				$The_HTML .= "</p>";
				$The_HTML .= '</form>';
				$The_HTML .= '<div id="' . $The_Upload_Area . '">';
				if ($The_Value) :
					$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
					$The_HTML .= '<img style="max-width:200px;border:none;" src="' . $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_uploads/' . $The_Value .'" border="0" /><br />';
					$The_HTML .= $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_uploads/' . $The_Value;
				endif;
				$The_HTML .= '</div>';
				
				break;
				
			case 'Static Select' :
				
				$The_Options = explode("\n",$The_Custom_Field['options_text']);
				
				if (is_array($The_Options)) :
					$The_HTML .= '<select ';
					if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= 'class="';
					
					$The_Static_Select_Input_Classes = $The_Input_Element_Class_Array['Static Select'];
					$The_Static_Select_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
					$The_HTML .= trim($The_Static_Select_Input_Classes);
					
					if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= '" ';
					$The_HTML .= 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"><option></option>';
					
					foreach($The_Options as $The_Option):
						if($Separator = strpos($The_Option,":")!==false):
							$The_Static_Select_Key = substr($The_Option,0,$Separator);
							$The_Static_Select_Value = substr($The_Option,$Separator+1);
							$The_HTML .= '<option value="' . $The_Static_Select_Key . '"';
							if($The_Value == $The_Static_Select_Key) $The_HTML .= ' selected="selected"';
							$The_HTML .= '>' . $The_Static_Select_Value . '</option>';
						else:
							$The_HTML .= '<option value="' . $The_Option . '"';
							if($The_Value == $The_Option) $The_HTML .= ' selected="selected"';
							$The_HTML .= '>' . $The_Option . '</option>';
						endif;
					endforeach;
					$The_HTML .= '</select>';
				endif;
				
				break;
			
			case 'Static Radio' :
				
				$The_Options = explode("\n",$The_Custom_Field['options_text']);
				
				if (is_array($The_Options)) :
					$The_Static_Radio_Input_Classes = $The_Input_Element_Class_Array['Static Radio'];
					$The_Static_Radio_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
					
					foreach($The_Options as $The_Option):
						if($Separator = strpos($The_Option,":")!==false):
							$The_Static_Radio_Key = substr($The_Option,0,$Separator);
							$The_Static_Radio_Value = substr($The_Option,$Separator+1);
						else:
							$The_Static_Radio_Key = $The_Option;
							$The_Static_Radio_Value = $The_Option;
						endif;
						
						$The_HTML .= '<input type="radio"';
						if ($The_Input_Element_Class_Array['Static Radio'] || $The_Custom_Field['is_required']) $The_HTML .= ' class="' . $The_Static_Radio_Input_Classes . '"';
						$The_HTML .= ' name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Static_Radio_Key . '"';
						if ($The_Value == $The_Static_Radio_Key) $The_HTML .= ' checked="checked"';
						$The_HTML .= '>' . $The_Static_Radio_Value . '<br/>';
					endforeach;
				endif;
				
				break;
			
			case 'Dynamic Select' :
			case 'Dynamic Radio' :
				$The_HTML .= '<em>Dynamic Fields are disabled</em>';
				/*
				$The_Value = $The_Field['value'];
				
				require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
				require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_set_of_aggregated_value_definitions.inc.php' );
				
				$The_Database_To_Use = new A_Mimik_Database_Interface;
				$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT']."/mimik/mimik_configuration/database_connection_info.csv" );
				$The_Database_To_Use->Establishes_A_Connection();
				
				$The_Set_Of_Aggregated_Value_Definitions = new A_Set_Of_Aggregated_Value_Definitions($The_Database_To_Use);
				
				$The_Aggregated_Value_Definition_Information = $The_Database_To_Use->Gets_The_Dynamic_Relation_Information_For($The_Field['id']);
	
				if (is_array($The_Aggregated_Value_Definition_Information['row_ids'])) foreach ($The_Aggregated_Value_Definition_Information['row_ids'] as $The_Row_ID) :
	
					$The_Aggregated_Value_Definition = new An_Aggregated_Value_Definition(
																	$The_Aggregated_Value_Definition_Information['table_id'],
																	$The_Aggregated_Value_Definition_Information['column_ids'],
																	$The_Row_ID );
				
					$The_Set_Of_Aggregated_Value_Definitions->Appends_The_Aggregated_Value_Definition($The_Aggregated_Value_Definition);
				
				endforeach;
				
				$The_Result_Set_Of_Aggregated_Value_Pairs = $The_Set_Of_Aggregated_Value_Definitions->Gets_The_Set_Of_Aggregated_Values(' ');
				
				if ($The_Field['is_required']) $The_Required_Class = 'required';
				else $The_Required_Class = '';
				
				if ($The_Field['type'] == 'Dynamic Select') :
					$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Select_Box($The_Field_ID, $The_Value, NULL, $The_Required_Class);
				elseif ($The_Field['type'] == 'Dynamic Radio') :
					$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Radio_Buttons($The_Field_ID, $The_Value, NULL, $The_Required_Class);
				endif;
				*/
				break;
				
			case 'WYSIWYG' :
			
				$The_HTML .= '</div>';
				
				$The_HTML .= '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
				
				$The_HTML .= '<div class="list_item_double_wide_column">';
	
				$The_Javascript_Array_Of_WYSIWYG_IDs .= 'The_Javascript_Array_Of_WYSIWYG_IDs[' . $The_WYSIWYG_Counter++ . '] = \'' . $The_Field_ID . '\';';
				
				include_once($_SERVER['DOCUMENT_ROOT'] . "/mimik/fckeditor/fckeditor.php") ;
				$oFCKeditor = new FCKeditor($The_Field_ID) ;
				$oFCKeditor->ToolbarSet = 'MimikToolbar';
				$oFCKeditor->Width = '600px';
				$oFCKeditor->Height = '300px';
				$oFCKeditor->BasePath = '../fckeditor/' ;
				$oFCKeditor->Value = $The_Value;
				$oFCKeditor->Create();
				
				break;
			
			case 'Group Permission' :
			
				$All_Groups = $The_Field['group_permission_data'];
				
				if (is_array($All_Groups)) :
				
					$The_HTML .= '<div id="' . $The_Group_Div_Name . '">';
				
					if (is_array($All_Groups)) foreach ($All_Groups as $The_Group_Information) :
					
						$The_HTML .= '<div class="list_item_column">' . $The_Group_Information['name'] . '</div>';
						
						$The_HTML .= '<div class="list_item_narrow_column"><input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_Group_Information['id'] . '" ';
						
						if (is_array($The_Field['value'])) :
						
							if (in_array($The_Group_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
							
						endif;
						
						$The_HTML .= '/></div>';
					
					endforeach;
					
					$The_HTML .= '</div>';
				
				else :
				
					$The_HTML .= 'No Groups';
				
				endif;
				
				break;
				
			case 'User Permission' :
			
				$All_Users = $The_Field['user_permission_data'];
				
				if (is_array($All_Users)) :
				
					$The_HTML .= '<div id="' . $The_User_Div_Name . '">';
				
					if (is_array($All_Users)) foreach ($All_Users as $The_User_Information) :
					
						$The_HTML .= '<div class="list_item_double_wide_column">' . $The_User_Information['login'] . '</div>';
						
						$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_User_Information['id'] . '" ';
						
						if (is_array($The_Field['value'])) :
						
							if (in_array($The_User_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
							
						endif;
						
						$The_HTML .= '/>';
					
					endforeach;
					
					$The_HTML .= '</div>';
				
				else :
				
					$The_HTML .= 'No Users';
				
				endif;
				
				break;
	
			default :
	
				$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	
				break;
	
			endswitch;
			
			$The_HTML .= '</div>';
			
			$The_HTML .= '</li>';
			
		endif;
	
	endforeach;

	/////////////////////////
	
	$The_HTML .= '</ul>';
	
	if ($The_Input_Indication_Of_Admin_Display) :
	
		$The_HTML .= '<h3>Group Membership</h3>';
		
		$The_HTML .= '<ul id="' . $The_Group_List_Name . '">';
		
		$The_Group_Tag = 'EDIT_USER_GROUP:';
		
		if (is_array($The_Input_Groups)) :
		
			$Is_Odd = true;
		
			foreach ($The_Input_Groups as $The_Group) :
			
				$The_Field_ID = $The_Group_Tag . $The_Group['id'];
				$The_HTML .= '<li class="list_item';
				if ($Is_Odd) $The_HTML .= ' odd';
				$The_HTML .= '">';
				$Is_Odd = !$Is_Odd;
				$The_HTML .= '<div class="list_item_wide_column">';
				$The_HTML .= $The_Group['name'];
				$The_HTML .= '</div>';
				$The_HTML .= '<div class="list_item_narrow_column">';
				$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
				if (is_array($The_Input_Group_Membership_Information)) :
					if (in_array($The_Group['id'], $The_Input_Group_Membership_Information)) :
						$The_HTML .= 'checked="checked" ';
					endif;
				endif;
				$The_HTML .= '/>';
				$The_HTML .= '</div>';
				$The_HTML .= '</li>';
			
			endforeach;
			
		endif;
		
	endif;

	$The_HTML .= '</ul>';
	
	$The_HTML .= '<p class="admin_control_box">';
	
	if ($The_Input_Indication_Of_Admin_Display) :
		$The_Target_Div_ID = '$(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\')';
		$The_Admin_Display = '1';
	else :
		$The_Target_Div_ID = '$(this).parents().filter(\'div\').attr(\'id\')';
		$The_Admin_Display = '0';
	endif;

	$The_HTML .= '<a href="#" onclick="Modify_The_User_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(' . $The_Input_User_ID . ', \'' . $The_Submit_Tag . '\', \'' . $The_Group_Tag . '\', \'' . $The_User_List_Name . '\', \'' . $The_Group_List_Name . '\', ' . $The_Target_Div_ID . ', ' . $The_Admin_Display . '); return false;">OK</a> | ';

	$The_HTML .= '<a href="#" onclick="';
	$The_HTML .= '$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');';
	$The_HTML .= 'return false;">Cancel</a>';

	$The_HTML .= '</p>';

	return $The_HTML;

}

function The_HTML_For_The_Editor_For_The_View($The_Input_View_ID, $The_Input_View_Name, $The_Input_Forms, $The_Input_Groups, $The_Input_View_Information, $The_Input_Fields)
{
	$The_HTML = '';
	
	$The_View_Name_Tag = 'view_name';
	
	$The_View_Type_Tag = 'view_type';
	
	$The_View_Width_Tag = 'view_width';
	
	$The_Image_Field_Tag = 'image_field';
	
	$The_Title_Field_Tag = 'title_field';
	
	$The_Group_Permissions_Prefix = 'EDIT_VIEW_GROUP:';
	
	$The_List_Name = 'view_modification_list';
	
	$The_Form_Div_Name = 'view_form';
	
	$The_Sort_Field_Name = 'sort_field';
	
	$The_Sort_Order_Name = 'sort_order';
	
	$The_Limit_Access_Div_Name = 'create_view_limit_access';
	
	$The_Audience_Div_Name = 'edit_view_audience';
	
	$The_Form_Name = 'EDITVIEW:form';
	
	$The_HTML .= '<h2>Edit View ( ' . $The_Input_View_Name . ' )</h2>';
	
	$The_HTML .= '<form name="' . $The_Form_Name . '" id="' . $The_Form_Name . '">';
	
	$The_HTML .= '<ul class="list" name="' . $The_List_Name . '" id="' . $The_List_Name . '">';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Name:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_View_Name_Tag . '" id="' . $The_View_Name_Tag . '" value="' . $The_Input_View_Information['display_name'] . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Type:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<select onchange="Update_The_View_Form_Select_For_The_View_Type();" name="' . $The_View_Type_Tag . '" id="' . $The_View_Type_Tag . '" />';
	$The_HTML .= '<option value="Normal"';
	if ($The_Input_View_Information['type'] == 'Normal') $The_HTML .= ' selected="selected"';
	$The_HTML .= '>Normal</option>';
	$The_HTML .= '<option value="Calendar"';
	if ($The_Input_View_Information['type'] == 'Calendar') $The_HTML .= ' selected="selected"';
	$The_HTML .= '>Calendar</option>';
	$The_HTML .= '<option value="Gallery"';
	if ($The_Input_View_Information['type'] == 'Gallery') $The_HTML .= ' selected="selected"';
	$The_HTML .= '>Gallery</option>';
	$The_HTML .= '<option value="Video Player"';
	if ($The_Input_View_Information['type'] == 'Video Player') $The_HTML .= ' selected="selected"';
	$The_HTML .= '>Video Player</option>';
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li';
	if ($The_Input_View_Information['type'] != 'Calendar' && $The_Input_View_Information['type'] != 'Video Player') $The_HTML .= ' style="display:none;"';
	$The_HTML .= ' id="calendar_width_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Width:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_View_Width_Tag . '" id="' . $The_View_Width_Tag . '"';
	if ($The_Input_View_Information['type'] == 'Calendar' && $The_Input_View_Information['type'] != 'Video Player') $The_HTML .= ' value="' . $The_Input_View_Information['width'] . '"';
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li';
	if ($The_Input_View_Information['type'] != 'Video Player') $The_HTML .= ' style="display:none;"';
	$The_HTML .= ' id="view_height_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Height:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_View_Height_Tag . '" id="' . $The_View_Height_Tag . '"';
	if ($The_Input_View_Information['type'] == 'Video Player') $The_HTML .= ' value="' . $The_Input_View_Information['height'] . '"';
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Form:</div>';
	$The_HTML .= '<div id="view_form_wrapper" class="list_item_wide_column">';
	$The_HTML .= '<select onchange="Update_The_Sort_Field_Select_For_The_Form_In_The_Div(this.options[this.selectedIndex].value, \'sort_field_div\', \'' . 'sort_field\');" name="' . $The_Form_Div_Name . '" id="' . $The_Form_Div_Name . '"><option></option>';
	if (is_array($The_Input_Forms)) :
		foreach ($The_Input_Forms as $The_Form) :
			$The_HTML .= '<option value="' . $The_Form['id'] . '"';
			if ($The_Form['id'] == $The_Input_View_Information['form_id']) :
				$The_HTML .= ' selected';
			endif;
			$The_HTML .= '>' . $The_Form['display_name'] . '</option>';
		endforeach;
	endif;
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	//------
	
	$The_HTML .= '<li ';
	if ($The_Input_View_Information['type'] != 'Gallery') $The_HTML .= 'style="display:none;"';
	$The_HTML .= ' id="image_field_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Image Field</div>';
	$The_HTML .= '<div id="image_field_wrapper" class="list_item_wide_column">';
	$The_HTML .= The_HTML_For_The_Field_Select($The_Input_Fields, $The_Image_Field_Tag, $The_Input_View_Information['image_field']);
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	//------
	
	$The_HTML .= '<li ';
	if ($The_Input_View_Information['type'] != 'Video Player') $The_HTML .= 'style="display:none;"';
	$The_HTML .= ' id="video_field_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Video Field</div>';
	$The_HTML .= '<div id="video_field_wrapper" class="list_item_wide_column">';
	$The_HTML .= The_HTML_For_The_Field_Select($The_Input_Fields, $The_Video_Field_Tag, $The_Input_View_Information['video_field']);
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	//------
	
	$The_HTML .= '<li ';
	if ($The_Input_View_Information['type'] != 'Gallery') $The_HTML .= 'style="display:none;"';
	$The_HTML .= 'id="title_field_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Title Field</div>';
	$The_HTML .= '<div id="title_field_wrapper" class="list_item_wide_column">';
	$The_HTML .= The_HTML_For_The_Field_Select($The_Input_Fields, $The_Title_Field_Tag, $The_Input_View_Information['title_field']);
	$The_HTML .= '</div>';
	
	//------
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Sort On';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<div id="sort_field_div">';
	$The_HTML .= The_HTML_For_The_Field_Select($The_Input_Fields, $The_Sort_Field_Name, $The_Input_View_Information['sort_field']);
	$The_HTML .= '</div>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	//------
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Sort Order:</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<select name="' . $The_Sort_Order_Name . '" id="' . $The_Sort_Order_Name . '">';
	$The_HTML .= '<option></option>';
	$The_HTML .= '<option value="ASCENDING"';
	if ($The_Input_View_Information['sort_order'] == 'ASCENDING') :
		$The_HTML .= ' selected';
	endif;
	$The_HTML .= '>Ascending</option>';
	$The_HTML .= '<option value="DESCENDING"';
	if ($The_Input_View_Information['sort_order'] == 'DESCENDING') :
		$The_HTML .= ' selected';
	endif;
	$The_HTML .= '>Descending</option></select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Limit Access';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="1" ';
	if ($The_Input_View_Information['limit_access']) $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> Yes<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="0" ';
	if (!$The_Input_View_Information['limit_access']) $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> No';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<strong>Groups</strong>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_Groups)) foreach ($The_Input_Groups as $The_Group) :
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= $The_Group['name'];
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_narrow_column">';
		$The_HTML .= '<input type="checkbox" name="' . $The_Group_Permissions_Prefix . $The_Group['id'] . '" value="' . $The_Group['id'] . '" ';
		if ($The_Group['used']) $The_HTML .= 'checked="checked" ';
		$The_HTML .= '/>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	endforeach;
	
	$The_HTML .= '</ul>';

	$The_HTML .= '</form>';
	
	$The_HTML .= '<p class="admin_control_box">';

	$The_HTML .= '<a href="#" onclick="Modify_The_View(\'' . $The_Input_View_ID . '\', \'' . $The_View_Name_Tag . '\', \'' . $The_Form_Div_Name . '\', \'' . $The_Sort_Field_Name . '\', \'' . $The_Sort_Order_Name . '\', document.getElementById(\'' . $The_Form_Name . '\').' . $The_Limit_Access_Div_Name . ', ' . 'document.getElementById(\'' . $The_List_Name . '\'), \'' . $The_Group_Permissions_Prefix . '\', \'views_displayer\');changeTo(\'views_displayer\');return false;">Save</a> | ';
	
	$The_HTML .= '<a href="#" onclick="Modify_The_View(\'' . $The_Input_View_ID . '\', \'' . $The_View_Name_Tag . '\', \'' . $The_Form_Div_Name . '\', \'' . $The_Sort_Field_Name . '\', \'' . $The_Sort_Order_Name . '\', document.getElementById(\'' . $The_Form_Name . '\').' . $The_Limit_Access_Div_Name . ', ' . 'document.getElementById(\'' . $The_List_Name . '\'), \'' . $The_Group_Permissions_Prefix . '\', \'views_displayer\', 1);changeTo(\'views_displayer\');return false;">Save With New Template</a> | ';

	$The_HTML .= '<a href="#" onclick="changeTo(\'views_displayer\',-1);return false;">Cancel</a>';

	$The_HTML .= '</p>';

	return $The_HTML;
}

function The_HTML_For_The_Field_Editor_For_The_Field_And_The_Form(
							  $The_Input_Field_ID,
							  $The_Input_Field_Display_Name,
							  $The_Input_Field_Type,
							  $The_Input_Field_Input_Control_Width,
							  $The_Input_Field_Character_Limit,
							  $The_Input_Field_Display,
							  $The_Input_Form_ID = 0,
							  $The_Input_Tables = NULL,
							  $The_Input_Fields = NULL,
							  $The_Input_Start_Year = NULL,
							  $The_Input_End_Year = NULL,
							  $The_Input_Indication_Is_Required = true,
							  $The_Input_Indication_Is_Modifiable_By_User = true,
							  $The_Input_Public_Facing_Indication = false,
							  $The_Input_Explanatory_Text = '',
							  $The_Input_Options_Text = '',
							  $Is_User_Custom_Field = false,
							  $Is_Group_Custom_Field = false)
{
	global $THE_FIELD_TYPE_ARRAY;

	$The_HTML = '';

	$The_Submit_Tag = 'EDIT_FIELD:';

	$The_List_Name = 'field_modification_list';

	$The_HTML .= '<ul class="list" id="' . $The_List_Name . '">';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Field Type:</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_Field_ID = $The_Submit_Tag . 'type';
	$The_Value = $The_Input_Field_Type;
	$The_HTML .= '<select onchange="Display_The_Controls_For_The_Text_Field(this.value);Display_The_Options_Textarea_For_The_Field_Type(this.value); Display_The_Year_Fields_For_A_Date_Selection_In_The_Div(this.options[this.selectedIndex].value, \'start_year_for_date_selection\', \'end_year_for_date_selection\');Display_The_Tables_For_A_Relational_Selection_In_The_Div(this.options[this.selectedIndex].value, \'tables_for_relational_selection\', \'' . $The_Submit_Tag . '\');" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '">';
	if (is_array($THE_FIELD_TYPE_ARRAY)) foreach ($THE_FIELD_TYPE_ARRAY as $The_Type) :
		$The_HTML .= '<option value="' . $The_Type['value'] . '"';
		if ($The_Type['value'] == $The_Value) :
			$The_HTML .= ' selected="selected"';
		endif;
		$The_HTML .= '>' . $The_Type['display_name'] . '</option>';
	endforeach;
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="options_text_container"';
	if (!($The_Input_Field_Type == 'Static Select' || $The_Input_Field_Type == 'Static Radio')) :
		$The_HTML .= ' style="display:none;"';
	endif;
	$The_HTML .= '>';
	$The_HTML .= '<div class="list_item_column">Options:<br/><small>Separate with line breaks.</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<textarea name="' . $The_Submit_Tag . 'options_text" id="' . $The_Submit_Tag . 'options_text" style="min-width:180px;max-width:180px;min-height:80px;max-height:80px;">' . $The_Input_Options_Text . '</textarea>';
	$The_HTML .= '</div>';
	$The_HTMl .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="input_control_width_container"';
	if (!($The_Input_Field_Type == 'Text')) :
		$The_HTML .= ' style="display:none;"';
	endif;
	$The_HTML .= '>';
	$The_HTML .= '<div class="list_item_column">Input Control Width: *</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Submit_Tag . 'input_control_width" id="' . $The_Submit_Tag . 'input_control_width" value="' . $The_Input_Field_Input_Control_Width . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	$The_HTML .= '<li class="list_item" id="character_limit_container"';
	if (!($The_Input_Field_Type == 'Text' || $The_Input_Field_Type == 'Text Area')) :
		$The_HTML .= ' style="display:none;"';
	endif;
	$The_HTML .= '>';
	$The_HTML .= '<div class="list_item_column">Character Limit:<br /><small>Set to 0 or blank to specify no limit</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Submit_Tag . 'character_limit" id="' . $The_Submit_Tag . 'character_limit" value="' . $The_Input_Field_Character_Limit . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="tables_for_relational_selection">';
	
	if ($The_Value == 'Dynamic Select' || $The_Value == 'Dynamic Radio') :
	
		$The_HTML .= The_HTML_For_The_Relational_Table_Selector($The_Input_Tables, $The_Submit_Tag);
	
	endif;
	
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="fields_for_relational_selection">';
	
	if ($The_Value == 'Dynamic Select' || $The_Value == 'Dynamic Radio') :
	
		$The_HTML .= The_HTML_For_The_Relational_Field_Selectors($The_Input_Fields, $The_Submit_Tag);
		
	endif;
		
	$The_HTML .= '</li>';
	
	/**************************************/
	
	$The_HTML .= '<li class="list_item" id="start_year_for_date_selection">';
	
	if ($The_Value == 'Date') :
	
		$The_HTML .= '<div class="list_item_column">Start Year:</div>';
		
		$The_HTML .= '<div class="list_item_column">';
	
		$The_HTML .= '<input type="text" name="' . $The_Submit_Tag . 'start_year" id="' . $The_Submit_Tag . 'start_year" value="' . $The_Input_Start_Year . '" />';
	
		$The_HTML .= '</div>';
	
	endif;
	
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="end_year_for_date_selection">';
	
	if ($The_Value == 'Date') :
	
		$The_HTML .= '<div class="list_item_column">End Year:</div>';
		
		$The_HTML .= '<div class="list_item_column">';
	
		$The_HTML .= '<input type="text" name="' . $The_Submit_Tag . 'end_year" id="' . $The_Submit_Tag . 'end_year" value="' . $The_Input_End_Year . '" />';
		
		$The_HTML .= '</div>';
		
	endif;
		
	$The_HTML .= '</li>';
	
	/**************************************/

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Display In Management View</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_Field_ID = $The_Submit_Tag . 'display_in_management_view';
	$The_Value = $The_Input_Field_Display;
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Value == '1') :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	/**************************************/

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Required</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_Field_ID = $The_Submit_Tag . 'is_required';
	$The_Value = $The_Input_Indication_Is_Required;
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Value == '1') :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	/**************************************/

	if (!$Is_User_Custom_Field && !$Is_Group_Custom_Field) :

		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">Public-Facing</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_Field_ID = $The_Submit_Tag . 'is_public_facing';
		$The_Value = $The_Input_Public_Facing_Indication;
		$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
		if ($The_Value == '1') :
			$The_HTML .= 'checked="checked" ';
		endif;
		$The_HTML .= '/>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
	endif;
	
	/**************************************/
	
	if ($Is_User_Custom_Field) :
	
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">Allow Modification by User?</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_Field_ID = $The_Submit_Tag . 'is_modifiable_by_user';
		$The_Value = $The_Input_Indication_Is_Modifiable_By_User;
		$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
		if ($The_Value == '1') :
			$The_HTML .= 'checked="checked" ';
		endif;
		$The_HTML .= '/>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	
	endif;

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Explanatory Text:<br /><small>Will appear in admin and public forms.</small></div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_Field_ID = $The_Submit_Tag . 'explanatory_text';
	$The_Value = $The_Input_Explanatory_Text;
	$The_HTML .= '<textarea style="width:400px; height:200px;" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '">';
	$The_HTML .= $The_Value;
	$The_HTML .= '</textarea>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	$The_HTML .= '</ul>';

	$The_HTML .= '<p class="admin_control_box">';

	$The_HTML .= '<a href="#" onclick="Modify_The_Field_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(\'' . $The_Input_Field_ID . '\', \'' . $The_Submit_Tag . '\', \'' . $The_List_Name . '\', \'' . $The_Input_Form_ID . '\', $(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\')); return false;">OK</a> | ';

	if ($Is_User_Custom_Field) :
	
		$The_HTML .= '<a href="#" onclick="';
		$The_HTML .= '$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_user_custom_fields\');';
		$The_HTML .= 'return false;">Cancel</a>';

	elseif ($Is_Group_Custom_Field) :
	
		$The_HTML .= '<a href="#" onclick="';
		$The_HTML .= '$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_group_custom_fields\');';
		$The_HTML .= 'return false;">Cancel</a>';
	
	else :

		$The_HTML .= '<a href="#" onclick="';
		$The_HTML .= '$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_fields&form_id=' . $The_Input_Form_ID . '\');';
		$The_HTML .= 'return false;">Cancel</a>';
		
	endif;

	$The_HTML .= '</p>';

	return $The_HTML;

}

function The_HTML_For_The_Field_Select($The_Input_Fields, $The_Input_Select_Name = '', $The_Input_Value_Of_The_Selected_Option = '')
{
	$The_HTML = '<select name="' . $The_Input_Select_Name . '" id="' . $The_Input_Select_Name . '">';

	if (is_array($The_Input_Fields)) :
	
		$The_HTML .= '<option value=""></option>';
	
		foreach ($The_Input_Fields as $The_Field) :
		
			$The_HTML .= '<option value="' . $The_Field['name'] . '"';
			
			if ($The_Field['name'] == $The_Input_Value_Of_The_Selected_Option) :
			
				$The_HTML .= ' selected';
				
			endif;
			
			$The_HTML .= '>';
		
			$The_HTML .= $The_Field['display_name'];
			
			$The_HTML .= '</option>';
			
		endforeach;
	
	else :
	
		$The_HTML .= '<option value="">No Form Selected</option>';
	
	endif;
	
	$The_HTML .= '</select>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_Hidden_Form_ID($The_Input_Form_ID)
{
        $The_HTML = '';

        $The_HTML .= '<input type="hidden" name="current_form" id="current_form" value="' . $The_Input_Form_ID . '" />';

        return $The_HTML;

}

function The_HTML_For_The_User_Creator($The_Input_Groups, $The_Input_User_Custom_Fields = NULL, $Moderation_Is_Required = true, $Email_Is_Login = false)
{
	$The_HTML = '';
	
	$The_User_List_Name = 'user_creation_list';
		
	$The_Group_List_Name = 'user_creation_group_membership_list';
	
	$The_User_Div_Name = ''; // only if users have a User Permission field associated
	
	$The_Group_Div_Name = ''; // only if users have a Group Permission field associated
	
	$The_Submit_Tag = 'CREATE_USER:';
	
	$The_Group_Tag = 'CREATE_USER_GROUP:';
	
	$The_HTML .= '<ul id="' . $The_User_List_Name . '">';

	$The_Field_ID = $The_Submit_Tag . 'login';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Name:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	$The_Field_ID = 'password';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Password';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input onkeyup="Confirm_The_Password(this.value, \'password_confirm\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// password confirm
	
	$The_Field_ID = 'password_confirm';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Confirm Password';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input onkeyup="Confirm_The_Password(this.value, \'password\', \'' . $The_Submit_Tag . 'password' . '\', \'password_feedback\');" type="password" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// password (submitted)
	
	$The_Field_ID = $The_Submit_Tag . 'password';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<small><a href="#" onclick="alert(\'Passwords must be at least 8 characters long with at least one upper and lower-case letter, one number, and one symbol.\');return false;">Password Validation</a></small>';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';
	$The_HTML .= '<div id="password_feedback"></div>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	// email

	if (!$Email_Is_Login) :
		$The_Field_ID = $The_Submit_Tag . 'email';
		$The_HTML .= '<li class="list_item" ';
		$The_HTML .= '>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Email';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Input_User_Information['email'] . '" />';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	endif;

	/////////////////////////
	
	if (!$Moderation_Is_Required) :
	
		$The_Field_ID = $The_Submit_Tag . 'moderation_status';
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Moderation Status';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<select name="' . $The_Field_ID . '" id="' . $The_Field_ID . '">';
		$The_HTML .= '<option value="NEW"';
		if ($The_Input_User_Information['moderation_status'] == 'NEW') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>New</option>';
		$The_HTML .= '<option value="APPROVED"';
		if ($The_Input_User_Information['moderation_status'] == 'APPROVED') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>Approved</option>';
		$The_HTML .= '<option value="DENIED"';
		if ($The_Input_User_Information['moderation_status'] == 'DENIED') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>Denied</option>';
		$The_HTML .= '</select>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
	endif;
	
	/////////////////////////
	
	$The_Field_ID = $The_Submit_Tag . 'is_blocked';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Blocked?';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Input_User_Information['is_blocked']) :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_User_Custom_Fields)) foreach ($The_Input_User_Custom_Fields as $The_Custom_Field) :
	
		$The_HTML .= '<li class="list_item">';
		
		$The_HTML .= '<div class="list_item_column">';
		
		$The_HTML .= $The_Custom_Field['display_name'];
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '<div class="list_item_double_wide_column">';
		
		$The_Field_ID = $The_Submit_Tag . $The_Custom_Field['name'];
		
		$The_Value = $The_Input_User_Information[$The_Custom_Field['name']];

		// COMPONENT

		switch ($The_Custom_Field['type']) :
		
		case 'Text' :
		case 'Number' :
		case 'Decimal' :

			$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" onKeyPress="return Disable_Enter_Key(event);" ';
			
			if ($The_Custom_Field['type'] == 'Text') :
			
				if (is_numeric($The_Custom_Field['input_control_width'])) :
				
					if ($The_Custom_Field['input_control_width'] > 0) :
					
						$The_HTML .= 'size="' . $The_Custom_Field['input_control_width'] . '" ';
						
					endif;
					
				endif;
				
				if (is_numeric($The_Custom_Field['character_limit']) && $The_Custom_Field['character_limit'] > 0) :
					
					$The_HTML .= 'maxlength="' . $The_Custom_Field['character_limit'] . '" ';
					
				else :
					
					preg_match('{(\d+)}', $THE_FIELD_TYPE_ARRAY['Text']['sql_field_type'], $m); 

					$The_HTML .= 'maxlength="' . $m[1] . '" ';
						
				endif;
			
			endif;
			
			$The_HTML .= '/>';

			break;

		case 'Text Area' :
		
			$The_HTML .= '</div>';
			
			$The_HTML .= '<div class="clear"></div>';

			$The_HTML .= '<textarea name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"';
			
			if (is_numeric($The_Custom_Field['character_limit']) && $The_Custom_Field['character_limit'] > 0) :
					
				$The_HTML .= ' maxlength="' . $The_Custom_Field['character_limit'] . '"';
				
			endif;
			
			$The_HTML .= '>' . $The_Value . '</textarea>';

			break;
			
		case 'Date' :
		
			$The_Date_Data = $The_Custom_Field['date_data'];
			
			$The_Current_Year_Value = (int) substr($The_Value, 0, 4);
			
			$The_Current_Month_Value = substr($The_Value, 5, 2);
			
			$The_Current_Day_Value = (int) substr($The_Value, 8, 2);
			
			$The_Months = array(''=>'', '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
								'07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
			
			$The_Month_Field_ID = $The_Field['name'] . '_month_edit';
			
			$The_Day_Field_ID = $The_Field['name'] . '_day_edit';
			
			$The_Year_Field_ID = $The_Field['name'] . '_year_edit';
				
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Month_Field_ID . '" id="' . $The_Month_Field_ID . '">';
			
			$The_HTML .= '<option value=""></option>';
			
			if (is_array($The_Months)) foreach ($The_Months as $The_Month_Value => $The_Month_String) :
				$The_HTML .= '<option value="' . $The_Month_Value . '"';
				if ($The_Current_Month_Value == $The_Month_Value) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Month_String . '</option>';
			endforeach;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Day_Field_ID . '" id="' . $The_Day_Field_ID . '">';
			
			$The_HTML .= '<option value=""></option>';
			
			for ($The_Day = 1; $The_Day <= 31; $The_Day++) :
				$The_HTML .= '<option value="' . $The_Day . '"';
				if ($The_Current_Day_Value == $The_Day) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Day . '</option>';
			endfor;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Year_Field_ID . '" id="' . $The_Year_Field_ID . '">';
			
			for ($The_Year = $The_Date_Data['start_year']; $The_Year <= $The_Date_Data['end_year']; $The_Year++) :
				$The_HTML .= '<option value="' . $The_Year . '"';
				if ($The_Current_Year_Value == $The_Year) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Year . '</option>';
			endfor;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" />';
			
			break;
			
		case 'File' :
		case 'Video' :
			
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_file.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
				$The_HTML .= '<p>URL : <a href="' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '" target="_blank">' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '</a></p>';
//				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
			
		case 'Secure File' :
			
			$The_Value_Array = explode('/', $The_Value);
			$The_Current_Filename = $The_Value_Array[count($The_Value_Array)-1];
			$The_Upload_Relative_Path = 'mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_secure_file.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			//echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a>';
				$The_HTML .= '<p>FILE : <a href="../mimik_live_data/secure_file.php?filename=' . urlencode(str_replace('mimik_secure_uploads/', '', $The_Value)) . '" target="_blank">' . $The_Current_Filename . '</a></p>';
//				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
		
		case 'Image' :
		
			//echo '<input type="file" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" size="20" />';
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="colorR" value="255" />';
			$The_HTML .= '<input type="hidden" name="colorG" value="255" />';
			$The_HTML .= '<input type="hidden" name="colorB" value="255" />';
			$The_HTML .= '<input type="hidden" name="maxH" value="960" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= '<p>';
			$The_HTML .= "<input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" />";
			$The_HTML .= "</p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
				$The_HTML .= '<img style="max-width:200px;border:none;" src="../mimik_uploads/' . $The_Value .'" border="0" /><br />';
				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
			
		case 'Static Select' :
				
			$The_Options = explode("\n",$The_Custom_Field['options_text']);
			
			if (is_array($The_Options)) :
				$The_HTML .= '<select ';
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= 'class="';
				
				$The_Static_Select_Input_Classes = $The_Input_Element_Class_Array['Static Select'];
				$The_Static_Select_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
				$The_HTML .= trim($The_Static_Select_Input_Classes);
				
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= '" ';
				$The_HTML .= 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"><option></option>';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Select_Key = substr($The_Option,0,$Separator);
						$The_Static_Select_Value = substr($The_Option,$Separator+1);
						$The_HTML .= '<option value="' . $The_Static_Select_Key . '">' . $The_Static_Select_Value . '</option>';
					else:
						$The_HTML .= '<option value="' . $The_Option . '">' . $The_Option . '</option>';
					endif;
				endforeach;
				$The_HTML .= '</select>';
			endif;
			
			break;
		
		case 'Static Radio' :
			
			$The_Options = explode("\n",$The_Custom_Field['options_text']);
			
			if (is_array($The_Options)) :
				$The_Static_Radio_Input_Classes = $The_Input_Element_Class_Array['Static Radio'];
				$The_Static_Radio_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Radio_Key = substr($The_Option,0,$Separator);
						$The_Static_Radio_Value = substr($The_Option,$Separator+1);
					else:
						$The_Static_Radio_Key = $The_Option;
						$The_Static_Radio_Value = $The_Option;
					endif;
					
					$The_HTML .= '<input type="radio"';
					if ($The_Input_Element_Class_Array['Static Radio'] || $The_Custom_Field['is_required']) $The_HTML .= ' class="' . $The_Static_Radio_Input_Classes . '"';
					$The_HTML .= ' name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Static_Radio_Key . '">' . $The_Static_Radio_Value . '<br/>';
				endforeach;
			endif;
			
			break;
		
		case 'Dynamic Select' :
		case 'Dynamic Radio' :
			$The_HTML .= '<em>Dynamic Fields are disabled</em>';
			/*
			$The_Value = $The_Field['value'];
			
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_set_of_aggregated_value_definitions.inc.php' );
			
			$The_Database_To_Use = new A_Mimik_Database_Interface;
			$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT']."/mimik/mimik_configuration/database_connection_info.csv" );
			$The_Database_To_Use->Establishes_A_Connection();
			
			$The_Set_Of_Aggregated_Value_Definitions = new A_Set_Of_Aggregated_Value_Definitions($The_Database_To_Use);
			
			$The_Aggregated_Value_Definition_Information = $The_Database_To_Use->Gets_The_Dynamic_Relation_Information_For($The_Field['id']);

			if (is_array($The_Aggregated_Value_Definition_Information['row_ids'])) foreach ($The_Aggregated_Value_Definition_Information['row_ids'] as $The_Row_ID) :

				$The_Aggregated_Value_Definition = new An_Aggregated_Value_Definition(
																$The_Aggregated_Value_Definition_Information['table_id'],
																$The_Aggregated_Value_Definition_Information['column_ids'],
																$The_Row_ID );
			
				$The_Set_Of_Aggregated_Value_Definitions->Appends_The_Aggregated_Value_Definition($The_Aggregated_Value_Definition);
			
			endforeach;
			
			$The_Result_Set_Of_Aggregated_Value_Pairs = $The_Set_Of_Aggregated_Value_Definitions->Gets_The_Set_Of_Aggregated_Values(' ');
			
			if ($The_Field['is_required']) $The_Required_Class = 'required';
			else $The_Required_Class = '';
			
			if ($The_Field['type'] == 'Dynamic Select') :
				$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Select_Box($The_Field_ID, $The_Value, NULL, $The_Required_Class);
			elseif ($The_Field['type'] == 'Dynamic Radio') :
				$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Radio_Buttons($The_Field_ID, $The_Value, NULL, $The_Required_Class);
			endif;
			*/
			break;
			
		case 'WYSIWYG' :
		
			$The_HTML .= '</div>';
			
			$The_HTML .= '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
			
			$The_HTML .= '<div class="list_item_double_wide_column">';

			$The_Javascript_Array_Of_WYSIWYG_IDs .= 'The_Javascript_Array_Of_WYSIWYG_IDs[' . $The_WYSIWYG_Counter++ . '] = \'' . $The_Field_ID . '\';';
			
			include_once($_SERVER['DOCUMENT_ROOT'] . "/mimik/fckeditor/fckeditor.php") ;
			$oFCKeditor = new FCKeditor($The_Field_ID) ;
			$oFCKeditor->ToolbarSet = 'MimikToolbar';
			$oFCKeditor->Width = '600px';
			$oFCKeditor->Height = '300px';
			$oFCKeditor->BasePath = '../fckeditor/' ;
			$oFCKeditor->Value = $The_Value;
			$oFCKeditor->Create();
			
			break;
		
		case 'Group Permission' :
		
			$All_Groups = $The_Field['group_permission_data'];
			
			if (is_array($All_Groups)) :
			
				$The_HTML .= '<div id="' . $The_Group_Div_Name . '">';
			
				if (is_array($All_Groups)) foreach ($All_Groups as $The_Group_Information) :
				
					$The_HTML .= '<div class="list_item_column">' . $The_Group_Information['name'] . '</div>';
					
					$The_HTML .= '<div class="list_item_narrow_column"><input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_Group_Information['id'] . '" ';
					
					if (is_array($The_Field['value'])) :
					
						if (in_array($The_Group_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
						
					endif;
					
					$The_HTML .= '/></div>';
				
				endforeach;
				
				$The_HTML .= '</div>';
			
			else :
			
				$The_HTML .= 'No Groups';
			
			endif;
			
			break;
			
		case 'User Permission' :
		
			$All_Users = $The_Field['user_permission_data'];
			
			if (is_array($All_Users)) :
			
				$The_HTML .= '<div id="' . $The_User_Div_Name . '">';
			
				if (is_array($All_Users)) foreach ($All_Users as $The_User_Information) :
				
					$The_HTML .= '<div class="list_item_double_wide_column">' . $The_User_Information['login'] . '</div>';
					
					$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_User_Information['id'] . '" ';
					
					if (is_array($The_Field['value'])) :
					
						if (in_array($The_User_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
						
					endif;
					
					$The_HTML .= '/>';
				
				endforeach;
				
				$The_HTML .= '</div>';
			
			else :
			
				$The_HTML .= 'No Users';
			
			endif;
			
			break;

		default :

			$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';

			break;

		endswitch;
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '</li>';
	
	endforeach;
	
	$The_HTML .= '</ul>';
	
	$The_HTML .= '<h3>Group Membership</h3>';
	
	$The_HTML .= '<ul id="' . $The_Group_List_Name . '">';
	
	if (is_array($The_Input_Groups)) :
	
		foreach ($The_Input_Groups as $The_Group) :
		
			$The_Field_ID = $The_Group_Tag . $The_Group['id'];
			$The_HTML .= '<li class="list_item">';
			$The_HTML .= '<div class="list_item_column">';
			$The_HTML .= $The_Group['name'];
			$The_HTML .= '</div>';
			$The_HTML .= '<div class="list_item_narrow_column">';
			$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
			if ($The_Group['is_default'] == '1') :
				$The_HTML .= 'checked="checked" ';
			endif;
			$The_HTML .= '/>';
			$The_HTML .= '</div>';
			$The_HTML .= '</li>';
		
		endforeach;
		
	endif;

	$The_HTML .= '</ul>';
	
	$The_HTML .= '<p class="admin_control_box">';
	
	$The_HTML .= '<a href="#" onclick="Create_The_User(' .
												'\'' . $The_Submit_Tag . '\', ' .
												'\'' . $The_Group_Tag . '\', ' .
												'\'' . $The_User_List_Name . '\', ' .
												'\'' . $The_Group_List_Name . '\', ' .
												'$(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\')); return false;">OK</a> | ';

	$The_HTML .= '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');';
	$The_HTML .= 'return false;">Cancel</a>';

	$The_HTML .= '</p>';
	
	return $The_HTML;
}

function The_HTML_For_The_Form_Creator($The_Input_Groups)
{
	$The_HTML = '';
	
	$The_Form_Name_Tag = 'form_name';
	
	$The_Group_Permissions_Prefix = 'CREATE_FORM_GROUP:';
	
	$The_List_Name = 'form_creator_list';
	
	$The_Confirmation_Message_Div_Name = 'create_form_confirmation_message_div';
	
	$The_Confirmation_Message_Textarea_Name = 'CREATEFORM_confirmation_message';
	
	$The_Audience_Div_Name = 'form_audience';
	
	$The_Create_Form_Form_Name = 'CREATEFORM_the_form';
	
	$The_Limit_Access_Div_Name = 'create_form_limit_access';
	
	$The_Email_Notification_Div_Name = 'create_form_email_notification_flag';
	
	$The_Email_Recipients_Tag = 'CREATEFORM_email_recipients';
	
	$The_HTML .= '<form name="' . $The_Create_Form_Form_Name . '" id="' . $The_Create_Form_Form_Name . '">';

	$The_HTML .= '<ul class="list" name="' . $The_List_Name . '" id="' . $The_List_Name . '">';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Name</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Form_Name_Tag . '" id="' . $The_Form_Name_Tag . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Type';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<select onchange="if(this.value==\'Image_Map\'){document.getElementById(\'filename_container\').style.display=\'block\';}else{document.getElementById(\'filename_container\').style.display=\'none\';}" id="type">';
	$The_HTML .= '<option value="Normal">Normal</option>';
	$The_HTML .= '<option value="Image_Map">Image Map</option>';
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	$The_HTML .= '<li style="display:none;" id="filename_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Image';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= "<input type=\"file\" onchange=\"mapperAjaxUpload(document.getElementById('".$The_Create_Form_Form_Name."'),'/mimik/mimik_plugins/mapper/php_ajax_image_upload/scripts/ajaxupload.php?itemtype=image&amp;filename=display_file_name&amp;maxSize=9999999999&amp;maxW=9999&amp;fullPath=" . $THE_BASE_URL . "/mimik/mimik_plugins/mapper/images/&amp;relPath=../../images/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=9999','upload_area','<img style=margin-left:17px; src=\'/mimik/mimik_plugins/mapper/php_ajax_image_upload/images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' />','Error in Upload, check settings and path info in source code.');newValue=document.getElementById('display_file_name').value;if(newValue.lastIndexOf('png')!=-1||newValue.lastIndexOf('PNG')!=-1||newValue.lastIndexOf('gif')!=-1||newValue.lastIndexOf('GIF')!=-1||newValue.lastIndexOf('jpg')!=-1||newValue.lastIndexOf('JPG')!=-1||newValue.lastIndexOf('jpeg')!=-1||newValue.lastIndexOf('JPEG')!=-1){document.getElementById('SUBMIT:file_name').value=newValue;}else{alert('Invalid file type');document.getElementById('SUBMIT:file_name').value='';};\" name=\"display_file_name\" id=\"display_file_name\"/>";
	$The_HTML .= '<input type="hidden" value="" name="file_name" id="SUBMIT:file_name"/>';
	$The_HTML .= '<span id="upload_area"' . /* style="position:fixed;top:0;right:0;display:block;" */ '></span>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Audience';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Audience_Div_Name . '" id="' . $The_Audience_Div_Name . '" value="Admin" checked="checked" onclick="Hide_The_Div(\'' . $The_Confirmation_Message_Div_Name . '\');" /> Administrative<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Audience_Div_Name . '" id="' . $The_Audience_Div_Name . '" value="Public" onclick="Show_The_Div(\'' . $The_Confirmation_Message_Div_Name . '\');" /> Public';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_wide_column" id="' . $The_Confirmation_Message_Div_Name . '" style="display:none;">';
	$The_HTML .= 'Confirmation Message<br />';
	$The_HTML .= '<textarea name="' . $The_Confirmation_Message_Textarea_Name . '" id="' . $The_Confirmation_Message_Textarea_Name . '"></textarea><br />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Limit Access';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="1" /> Yes<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="0" checked="checked" /> No';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Send Email Notification?<br /><small>When a new Submission is created in this form, send email notification(s)</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Email_Notification_Div_Name . '" id="' . $The_Email_Notification_Div_Name . '" value="1" /> Yes<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Email_Notification_Div_Name . '" id="' . $The_Email_Notification_Div_Name . '" value="0" checked="checked" /> No<br />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Email Recipient(s)<br /><small>For multiple recipients, separate with line breaks</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<textarea style="height:100px; width:200px;" name="' . $The_Email_Recipients_Tag . '" id="' . $The_Email_Recipients_Tag . '"></textarea>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<strong>Groups</strong>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_Groups)) foreach ($The_Input_Groups as $The_Group) :
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= $The_Group['name'];
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_narrow_column">';
		$The_HTML .= '<input type="checkbox" name="' . $The_Group_Permissions_Prefix . $The_Group['id'] . '" value="' . $The_Group['id'] . '" />';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	endforeach;
		
	$The_HTML .= '</ul>';
	$The_HTML .= '</form>';
	
	$The_HTML .= '<p class="admin_control_box">';
	
	$The_HTML .= '<a href="#" onclick="Create_The_Form(' .
												'document.getElementById(\'' . $The_List_Name . '\'), ' .
												'\'' . $The_Form_Name_Tag . '\', ' .
												'\'type\', ' .
												'\'SUBMIT:file_name\', ' .
												'\'' . $The_Group_Permissions_Prefix . '\', ' .
												'$(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\'), ' .
												'document.getElementById(\'' . $The_Create_Form_Form_Name . '\').' . $The_Audience_Div_Name . ', ' .
												'document.getElementById(\'' . $The_Confirmation_Message_Textarea_Name . '\').value, ' .
												'document.getElementById(\'' . $The_Create_Form_Form_Name . '\').' . $The_Limit_Access_Div_Name . ', ' .
												'document.getElementById(\'' . $The_Create_Form_Form_Name . '\').' . $The_Email_Notification_Div_Name . ', ' .
												'document.getElementById(\'' . $The_Email_Recipients_Tag . '\').value);' .
												' return false;">OK</a> | ';

	$The_HTML .= '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');';
	$The_HTML .= 'return false;">Cancel</a>';
	$The_HTML .= '</p>';

	return $The_HTML;
	
}

function The_HTML_For_The_Form_Editor($The_Input_Form_ID, $The_Input_Form_Name, $The_Input_Form_Type, $The_Input_Form_Filename, $The_Input_Form_Audience, $The_Input_Limit_Access, $The_Input_Target_Div_Name, $The_Input_Group_Array, $The_Input_Confirmation_Message, $The_Input_Preview_View_ID = '', $The_Input_Email_Notification_Flag = 0, $The_Input_Email_Recipients = '')
{
	$The_HTML = '';
	
	$The_Form_Name_Tag = 'form_name';
	
	$The_Group_Permissions_Prefix = 'EDIT_FORM_GROUP:';
	
	$The_List_Name = 'form_editor_list';
	
	$The_Audience_Div_Name = 'form_audience';
	
	$The_Confirmation_Message_Div_Name = 'edit_form_confirmation_message_div';

	$The_Confirmation_Message_Textarea_Name = 'EDITFORM_confirmation_message_div';
	
	$The_Edit_Form_Form_Name = 'EDITFORM:the_form';
	
	$The_Preview_View_ID_Tag = 'EDITFORM_preview_view_id';
	
	$The_Limit_Access_Div_Name = 'EDITFORM_limit_access';
	
	$The_Email_Notification_Div_Name = 'EDITFORM_email_notification';
	
	$The_Email_Recipients_Tag = 'EDITFORM_email_recipients';

	$The_HTML .= '<form name="' . $The_Edit_Form_Form_Name . '" id="' . $The_Edit_Form_Form_Name . '">';
	
	$The_HTML .= '<ul class="list" name="' . $The_List_Name . '" id="' . $The_List_Name . '">';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Name</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Form_Name_Tag . '" id="' . $The_Form_Name_Tag . '" ';
	$The_HTML .= 'value="' . $The_Input_Form_Name . '" ';
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Type</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<select id="type" onchange="if(this.value==\'Image_Map\'){document.getElementById(\'filename_container\').style.display=\'block\';}else{document.getElementById(\'filename_container\').style.display=\'none\';}">';
	$The_HTML .= '<option value="Normal"';
	if($The_Input_Form_Type == 'Normal') $The_HTML .= ' selected="selected"';
	$The_HTML .= '>Normal</option>';
	$The_HTML .= '<option value="Image_Map"';
	if($The_Input_Form_Type == 'Image_Map') $The_HTML .= ' selected="selected"';
	$The_HTML .= '>Image Map</option>';
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="filename_container"';
	if($The_Input_Form_Type != 'Image_Map') $The_HTML .= ' style="display: none;"';
	$The_HTML .= '><div class="list_item_column">Image</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= "<input type=\"file\" onchange=\"mapperAjaxUpload(document.getElementById('".$The_Edit_Form_Form_Name."'),'/mimik/mimik_plugins/mapper/php_ajax_image_upload/scripts/ajaxupload.php?itemtype=image&amp;filename=display_file_name&amp;maxSize=9999999999&amp;maxW=9999&amp;fullPath=" . $THE_BASE_URL . "/mimik/mimik_plugins/mapper/images/&amp;relPath=../../images/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=9999','upload_area','<img style=margin-left:17px; src=\'php_ajax_image_upload/images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' />','Error in Upload, check settings and path info in source code.');newValue=document.getElementById('display_file_name').value;if(newValue.lastIndexOf('png')!=-1||newValue.lastIndexOf('PNG')!=-1||newValue.lastIndexOf('gif')!=-1||newValue.lastIndexOf('GIF')!=-1||newValue.lastIndexOf('jpg')!=-1||newValue.lastIndexOf('JPG')!=-1||newValue.lastIndexOf('jpeg')!=-1||newValue.lastIndexOf('JPEG')!=-1){document.getElementById('SUBMIT:file_name').value=newValue;}else{alert('Invalid file type');document.getElementById('SUBMIT:file_name').value='';};\" name=\"display_file_name\" id=\"display_file_name\"/>";
	$The_HTML .= '<input type="hidden" value="' . $The_Input_Form_Filename . '" name="file_name" id="SUBMIT:file_name"/>';
	$The_HTML .= '<span id="upload_area"' . /* style="position:fixed;top:0;right:0;display:block;" */ '>';
	if($The_Input_Form_Filename != NULL && $The_Input_Form_Filename != '') $Full_Path = $_SERVER['DOCUMENT_ROOT'] . '/mimik/mimik_plugins/mapper/images/' . $The_Input_Form_Filename;
	if(file_exists($Full_Path)) $The_HTML .= '<img src="' . $Full_Path . '"/>';
	$The_HTML .= '</span>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Audience';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Audience_Div_Name . '" id="' . $The_Audience_Div_Name . '" value="Admin" onclick="Hide_The_Div(\'' . $The_Confirmation_Message_Div_Name . '\');" ';
	if ($The_Input_Form_Audience == 'Admin') $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> Administrative<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Audience_Div_Name . '" id="' . $The_Audience_Div_Name . '" value="Public" onclick="Show_The_Div(\'' . $The_Confirmation_Message_Div_Name . '\');" ';
	if ($The_Input_Form_Audience == 'Public') $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> Public';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_wide_column" id="' . $The_Confirmation_Message_Div_Name . '"';
	if ($The_Input_Form_Audience == 'Admin') $The_HTML .= ' style="display:none;"';
	if ($The_Input_Form_Audience == 'Public') $The_HTML .= ' style="display:block;"';
	$The_HTML .= '>';
	$The_HTML .= 'Confirmation Message<br />';
	$The_HTML .= '<textarea name="' . $The_Confirmation_Message_Textarea_Name . '" id="' . $The_Confirmation_Message_Textarea_Name . '">' . $The_Input_Confirmation_Message . '</textarea><br />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Limit Access';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="1" ';
	if ($The_Input_Limit_Access == '1') $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> Yes<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="0" ';
	if ($The_Input_Limit_Access == '0') $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> No<br />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Preview View ID</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Preview_View_ID_Tag . '" id="' . $The_Preview_View_ID_Tag . '" ';
	$The_HTML .= 'value="' . $The_Input_Preview_View_ID . '" ';
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Send Email Notification?<br /><small>When a new Submission is created in this form, send email notification(s)</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Email_Notification_Div_Name . '" id="' . $The_Email_Notification_Div_Name . '" value="1" ';
	if ($The_Input_Email_Notification_Flag == '1') $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> Yes<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Email_Notification_Div_Name . '" id="' . $The_Email_Notification_Div_Name . '" value="0" ';
	if ($The_Input_Email_Notification_Flag == '0') $The_HTML .= 'checked="checked" ';
	$The_HTML .= '/> No<br />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Email Recipient(s)<br /><small>For multiple recipients, separate with line breaks</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<textarea style="height:100px; width:200px;" name="' . $The_Email_Recipients_Tag . '" id="' . $The_Email_Recipients_Tag . '">';
	$The_HTML .= $The_Input_Email_Recipients;
	$The_HTML .= '</textarea>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<strong>Groups</strong>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_Group_Array)) foreach ($The_Input_Group_Array as $The_Group) :
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= $The_Group['name'];
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_narrow_column">';
		$The_HTML .= '<input type="checkbox" name="' . $The_Group_Permissions_Prefix . $The_Group['id'] . '" value="' . $The_Group['id'] . '" ';
		
		if ($The_Group['used']) $The_HTML .= 'checked="checked"';
		
		$The_HTML .= '/>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	endforeach;
		
	$The_HTML .= '</ul>';
	
	$The_HTML .= '</form>';
	
	$The_HTML .= '<p class="admin_control_box">';

	$The_HTML .= '<a href="#" onclick="Modify_The_Form(' . $The_Input_Form_ID . ', ' .
							'document.getElementById(\'' . $The_List_Name . '\'), ' .
							'\''. $The_Form_Name_Tag . '\', ' .
							'\'type\', ' .
							'\'SUBMIT:file_name\', ' .
							'\'' . $The_Group_Permissions_Prefix . '\', ' .
							'\'' . $The_Input_Target_Div_Name . '\', ' .
							'document.getElementById(\'' . $The_Edit_Form_Form_Name . '\').' . $The_Audience_Div_Name . ', ' .
							'document.getElementById(\'' . $The_Confirmation_Message_Textarea_Name . '\').value, ' .
							'document.getElementById(\'' . $The_Edit_Form_Form_Name . '\').' . $The_Limit_Access_Div_Name . ', ' .
							'document.getElementById(\'' . $The_Edit_Form_Form_Name . '\').' . $The_Preview_View_ID_Tag . '.value, ' .
							'document.getElementById(\'' . $The_Edit_Form_Form_Name . '\').' . $The_Email_Notification_Div_Name . ', ' .
							'document.getElementById(\'' . $The_Edit_Form_Form_Name . '\').' . $The_Email_Recipients_Tag . '.value);' .
							'return false;">Submit</a> | ';
	
	$The_HTML .= '<a href="#" onclick="';
	$The_HTML .= '$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');';
	$The_HTML .= 'return false;">Cancel</a>';
	
	$The_HTML .= '</p>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_View_Creator($The_Input_Groups, $The_Input_Forms = NULL)
{
	$The_HTML = '';
	
	$The_View_Name_Tag = 'view_name';
	
	$The_View_Type_Tag = 'view_type';
	
	$The_View_Width_Tag = 'view_width';
	
	$The_View_Height_Tag = 'view_height';
	
	$The_Image_Field_Tag = 'image_field';
	
	$The_Video_Field_Tag = 'video_field';
	
	$The_Title_Field_Tag = 'title_field';
	
	$The_Group_Permissions_Prefix = 'CREATE_VIEW_GROUP:';
	
	$The_List_Name = 'view_creator_list';
	
	$The_Form_Div_Name = 'view_form';
	
	$The_Sort_Field_Name = 'sort_field';
	
	$The_Sort_Order_Name = 'sort_order';
	
	$The_Limit_Access_Div_Name = 'create_view_limit_access';
	
	$The_Form_Name = 'VIEW:form_audience';
		
	$The_HTML .= '<form name="' . $The_Form_Name . '" id="' . $The_Form_Name . '">';
	
	$The_HTML .= '<ul class="list" name="' . $The_List_Name . '" id="' . $The_List_Name . '">';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Name:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_View_Name_Tag . '" id="' . $The_View_Name_Tag . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Type:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<select onchange="Update_The_View_Form_Select_For_The_View_Type();" name="' . $The_View_Type_Tag . '" id="' . $The_View_Type_Tag . '">';
	$The_HTML .= '<option value="Normal">Normal</option>';
	$The_HTML .= '<option value="Calendar">Calendar</option>';
	$The_HTML .= '<option value="Gallery">Gallery</option>';
	$The_HTML .= '<option value="Video Player">Video Player</option>';
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li';
	if ($The_Input_View_Information['type'] != 'Calendar' && $The_Input_View_Information['type'] != 'Video Player') $The_HTML .= ' style="display:none;"';
	$The_HTML .= ' id="calendar_width_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Width:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_View_Width_Tag . '" id="' . $The_View_Width_Tag . '"';
	if ($The_Input_View_Information['view_type'] == 'Calendar') $The_HTML .= ' value="' . $The_Input_View_Information['width'] . '"';
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li';
	if ($The_Input_View_Information['type'] != 'Video Player') $The_HTML .= ' style="display:none;"';
	$The_HTML .= ' id="view_height_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Height:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_View_Height_Tag . '" id="' . $The_View_Height_Tag . '"';
	if ($The_Input_View_Parameters['view_type'] == 'Video Player') $The_HTML .= ' value="' . $The_Input_View_Information['height'] . '"';
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Form:';
	$The_HTML .= '</div>';
	$The_HTML .= '<div id="view_form_wrapper" class="list_item_wide_column">';
	$The_HTML .= '<select onchange="Update_The_Sort_Field_Select_For_The_Form_In_The_Div(this.options[this.selectedIndex].value, \'sort_field_div\', \'' . 'sort_field\');" name="' . $The_Form_Div_Name . '" id="' . $The_Form_Div_Name . '"><option></option>';
	if (is_array($The_Input_Forms)) :
		foreach ($The_Input_Forms as $The_Form) :
			$The_HTML .= '<option value="' . $The_Form['id'] . '"';
			if ($The_Form['id'] == $The_Input_View_Information['form_id']) :
				$The_HTML .= ' selected';
			endif;
			$The_HTML .= '>' . $The_Form['display_name'] . '</option>';
		endforeach;
	endif;
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
		
	$The_HTML .= '<li style="display:none;" id="image_field_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Image Field</div>';
	$The_HTML .= '<div id="image_field_wrapper" class="list_item_wide_column">';
	$The_HTML .= '<select name="' . $The_Image_Field_Tag . '" id="' . $The_Image_Field_Tag . '">';
	$The_HTML .= '<option value="">No Form Selected</option>';
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li style="display:none;" id="video_field_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Video Field</div>';
	$The_HTML .= '<div id="video_field_wrapper" class="list_item_wide_column">';
	$The_HTML .= '<select name="' . $The_Video_Field_Tag . '" id="' . $The_Video_Field_Tag . '">';
	$The_HTML .= '<option value="">No Form Selected</option>';
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li style="display:none;" id="title_field_container" class="list_item">';
	$The_HTML .= '<div class="list_item_column">Title Field</div>';
	$The_HTML .= '<div id="title_field_wrapper" class="list_item_wide_column">';
	$The_HTML .= '<select name="' . $The_Title_Field_Tag . '" id="' . $The_Title_Field_Tag . '">';
	$The_HTML .= '<option value="">No Form Selected</option>';
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Sort On</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<div id="sort_field_div">';

	$The_HTML .= The_HTML_For_The_Field_Select($The_Input_Fields, $The_Sort_Field_Name, $The_Input_View_Information['sort_field']);

	$The_HTML .= '</div>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	//------
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Sort Order';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<select name="' . $The_Sort_Order_Name . '" id="' . $The_Sort_Order_Name . '">';
	$The_HTML .= '<option></option>';
	$The_HTML .= '<option value="ASCENDING"';
	if ($The_Input_View_Information['sort_order'] == 'ASCENDING') :
		$The_HTML .= ' selected';
	endif;
	$The_HTML .= '>Ascending</option>';
	$The_HTML .= '<option value="DESCENDING"';
	if ($The_Input_View_Information['sort_order'] == 'DESCENDING') :
		$The_HTML .= ' selected';
	endif;
	$The_HTML .= '>Descending</option></select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Limit Access';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="1" /> Yes<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Limit_Access_Div_Name . '" id="' . $The_Limit_Access_Div_Name . '" value="0" checked="checked" /> No';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<strong>Groups</strong>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_Groups)) foreach ($The_Input_Groups as $The_Group) :
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= $The_Group['name'];
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_narrow_column">';
		$The_HTML .= '<input type="checkbox" name="' . $The_Group_Permissions_Prefix . $The_Group['id'] . '" value="' . $The_Group['id'] . '" />';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	endforeach;
		
	$The_HTML .= '</ul>';
	
	$The_HTML .= '</form>';

	$The_HTML .= '<p class="admin_control_box">';
	
	$The_HTML .= '<a href="#" onclick="Create_The_View(' .
												'\'' . $The_View_Name_Tag . '\', ' .
												'\'' . $The_Form_Div_Name . '\', ' .
												'\'' . $The_Sort_Field_Name . '\', ' .
												'\'' . $The_Sort_Order_Name . '\', ' .
												'document.getElementById(\'' . $The_Form_Name . '\').' . $The_Limit_Access_Div_Name . ', ' .
												'document.getElementById(\'' . $The_List_Name . '\'), ' .
												'\'' . $The_Group_Permissions_Prefix . '\', ' .
												'$(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\'));' .
												' return false;">OK</a> | ';

	$The_HTML .= '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_views\');';
	$The_HTML .= 'return false;">Cancel</a>';

	$The_HTML .= '</p>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_View_Forms_Select_Menu($The_Input_Form_Div_Name, $The_Input_Forms)
{
	$The_HTML = '';
	$The_HTML .= '<select onchange="Update_The_Sort_Field_Select_For_The_Form_In_The_Div(this.options[this.selectedIndex].value, \'sort_field_div\', \'' . 'sort_field\');" name="' . $The_Input_Form_Div_Name . '" id="' . $The_Input_Form_Div_Name . '"><option></option>';
	if (is_array($The_Input_Forms)) :
		foreach ($The_Input_Forms as $The_Form) :
			$The_HTML .= '<option value="' . $The_Form['id'] . '"';
			if ($The_Form['id'] == $The_Input_View_Information['form_id']) :
				$The_HTML .= ' selected';
			endif;
			$The_HTML .= '>' . $The_Form['display_name'] . '</option>';
		endforeach;
	endif;
	$The_HTML .= '</select>';
	return $The_HTML;
}

function The_HTML_For_The_Group_Creator($The_Input_Admin_Permissions, $The_Input_Group_Custom_Fields = NULL, $The_Input_Groups = NULL)
{
	$The_HTML = '';
	
	$The_Submit_Tag = 'CREATE_GROUP:';
	
	$The_List_Name = 'create_group_items';
	
	$The_HTML .= '<ul class="list" id="' . $The_List_Name . '">';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Name</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Submit_Tag . 'name" id="' . $The_Submit_Tag . 'name" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	if (is_array($The_Input_Group_Custom_Fields)) foreach ($The_Input_Group_Custom_Fields as $The_Custom_Field) :
	
		$The_HTML .= '<li class="list_item">';
		
		$The_HTML .= '<div class="list_item_column">';
		
		$The_HTML .= $The_Custom_Field['display_name'];
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '<div class="list_item_double_wide_column">';
		
		$The_Field_ID = $The_Submit_Tag . $The_Custom_Field['name'];
		
		$The_Value = $The_Input_User_Information[$The_Custom_Field['name']];

		// COMPONENT

		switch ($The_Custom_Field['type']) :
		
		case 'Text' :
		case 'Number' :
		case 'Decimal' :

			$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" onKeyPress="return Disable_Enter_Key(event);" ';
			
			if ($The_Custom_Field['type'] == 'Text') :
			
				if (is_numeric($The_Custom_Field['input_control_width'])) :
				
					if ($The_Custom_Field['input_control_width'] > 0) :
					
						$The_HTML .= 'size="' . $The_Custom_Field['input_control_width'] . '" ';
						
					endif;
					
				endif;
				
				if (is_numeric($The_Custom_Field['character_limit']) && $The_Custom_Field['character_limit'] > 0) :
					
					$The_HTML .= 'maxlength="' . $The_Custom_Field['character_limit'] . '" ';
					
				else :
					
					preg_match('{(\d+)}', $THE_FIELD_TYPE_ARRAY['Text']['sql_field_type'], $m); 

					$The_HTML .= 'maxlength="' . $m[1] . '" ';
						
				endif;
			
			endif;
			
			$The_HTML .= '/>';

			break;

		case 'Text Area' :
		
			$The_HTML .= '</div>';
			
			$The_HTML .= '<div class="clear"></div>';

			$The_HTML .= '<textarea name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"';
			
			if (is_numeric($The_Custom_Field['character_limit']) && $The_Custom_Field['character_limit'] > 0) :
					
				$The_HTML .= ' maxlength="' . $The_Custom_Field['character_limit'] . '"';
				
			endif;
			
			$The_HTML .= '>' . $The_Value . '</textarea>';

			break;
			
		case 'Date' :
		
			$The_Date_Data = $The_Custom_Field['date_data'];
			
			$The_Current_Year_Value = (int) substr($The_Value, 0, 4);
			
			$The_Current_Month_Value = substr($The_Value, 5, 2);
			
			$The_Current_Day_Value = (int) substr($The_Value, 8, 2);
			
			$The_Months = array(''=>'', '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
								'07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
			
			$The_Month_Field_ID = $The_Field['name'] . '_month_edit';
			
			$The_Day_Field_ID = $The_Field['name'] . '_day_edit';
			
			$The_Year_Field_ID = $The_Field['name'] . '_year_edit';
				
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Month_Field_ID . '" id="' . $The_Month_Field_ID . '">';
			
			$The_HTML .= '<option value=""></option>';
			
			if (is_array($The_Months)) foreach ($The_Months as $The_Month_Value => $The_Month_String) :
				$The_HTML .= '<option value="' . $The_Month_Value . '"';
				if ($The_Current_Month_Value == $The_Month_Value) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Month_String . '</option>';
			endforeach;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Day_Field_ID . '" id="' . $The_Day_Field_ID . '">';
			
			$The_HTML .= '<option value=""></option>';
			
			for ($The_Day = 1; $The_Day <= 31; $The_Day++) :
				$The_HTML .= '<option value="' . $The_Day . '"';
				if ($The_Current_Day_Value == $The_Day) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Day . '</option>';
			endfor;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Year_Field_ID . '" id="' . $The_Year_Field_ID . '">';
			
			for ($The_Year = $The_Date_Data['start_year']; $The_Year <= $The_Date_Data['end_year']; $The_Year++) :
				$The_HTML .= '<option value="' . $The_Year . '"';
				if ($The_Current_Year_Value == $The_Year) $The_HTML .= ' selected="selected"';
				$The_HTML .= '>' . $The_Year . '</option>';
			endfor;
			
			$The_HTML .= '</select>';
			
			$The_HTML .= '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" />';
			
			break;
			
		case 'File' :
		case 'Video' :
			
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_file.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
				$The_HTML .= '<p>URL : <a href="' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '" target="_blank">' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '</a></p>';
//				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
			
		case 'Secure File' :
			
			$The_Value_Array = explode('/', $The_Value);
			$The_Current_Filename = $The_Value_Array[count($The_Value_Array)-1];
			$The_Upload_Relative_Path = 'mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload_secure_file.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			//echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a>';
				$The_HTML .= '<p>FILE : <a href="../mimik_live_data/secure_file.php?filename=' . urlencode(str_replace('mimik_secure_uploads/', '', $The_Value)) . '" target="_blank">' . $The_Current_Filename . '</a></p>';
//				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
		
		case 'Image' :
		
			//echo '<input type="file" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" size="20" />';
			$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
			$The_Upload_Area = $The_Field['name'] . '_upload_area';
			$The_Action = '../mimik_support/ajax_upload.php';
			$The_HTML .= '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
			$The_HTML .= '<input type="hidden" name="itemtype" value="image" />';
			$The_HTML .= '<input type="hidden" name="maxSize" value="9999999999" />';
			$The_HTML .= '<input type="hidden" name="maxW" value="960" />';
			$The_HTML .= '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
			$The_HTML .= '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
			$The_HTML .= '<input type="hidden" name="colorR" value="255" />';
			$The_HTML .= '<input type="hidden" name="colorG" value="255" />';
			$The_HTML .= '<input type="hidden" name="colorB" value="255" />';
			$The_HTML .= '<input type="hidden" name="maxH" value="960" />';
			$The_HTML .= '<input type="hidden" name="filename" value="filename" />';
			$The_HTML .= '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
			$The_HTML .= '<p>';
			$The_HTML .= "<input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" />";
			$The_HTML .= "</p>";
			$The_HTML .= '</form>';
			$The_HTML .= '<div id="' . $The_Upload_Area . '">';
			if ($The_Value) :
				$The_HTML .= '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
				$The_HTML .= '<img style="max-width:200px;border:none;" src="../mimik_uploads/' . $The_Value .'" border="0" /><br />';
				$The_HTML .= '../mimik_uploads/' . $The_Value;
			endif;
			$The_HTML .= '</div>';
			
			break;
		
		case 'Static Select' :
			$The_Options = explode("\n",$The_Custom_Field['options_text']);
			
			if (is_array($The_Options)) :
				$The_HTML .= '<select ';
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= 'class="';
				
				$The_Static_Select_Input_Classes = $The_Input_Element_Class_Array['Static Select'];
				$The_Static_Select_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
				$The_HTML .= trim($The_Static_Select_Input_Classes);
				
				if ($The_Input_Element_Class_Array['Static Select'] || $The_Custom_Field['is_required']) $The_HTML .= '" ';
				$The_HTML .= 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"><option></option>';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Select_Key = substr($The_Option,0,$Separator);
						$The_Static_Select_Value = substr($The_Option,$Separator+1);
						$The_HTML .= '<option value="' . $The_Static_Select_Key . '"';
						if($The_Value == $The_Static_Select_Key) $The_HTML .= ' selected="selected"';
						$The_HTML .= '>' . $The_Static_Select_Value . '</option>';
					else:
						$The_HTML .= '<option value="' . $The_Option . '"';
						if($The_Value == $The_Option) $The_HTML .= ' selected="selected"';
						$The_HTML .= '>' . $The_Option . '</option>';
					endif;
				endforeach;
				$The_HTML .= '</select>';
			endif;
			
			break;
			
		case 'Static Radio' :
			$The_Options = explode("\n",$The_Custom_Field['options_text']);
			
			if (is_array($The_Options)) :
				$The_Static_Radio_Input_Classes = $The_Input_Element_Class_Array['Static Radio'];
				$The_Static_Radio_Input_Classes .= ($The_Custom_Field['is_required']) ? ' required' : '';
				
				foreach($The_Options as $The_Option):
					if($Separator = strpos($The_Option,":")!==false):
						$The_Static_Radio_Key = substr($The_Option,0,$Separator);
						$The_Static_Radio_Value = substr($The_Option,$Separator+1);
					else:
						$The_Static_Radio_Key = $The_Option;
						$The_Static_Radio_Value = $The_Option;
					endif;
					
					$The_HTML .= '<input type="radio"';
					if ($The_Input_Element_Class_Array['Static Radio'] || $The_Custom_Field['is_required']) $The_HTML .= ' class="' . $The_Static_Radio_Input_Classes . '"';
					$The_HTML .= ' name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Static_Radio_Key . '"';
					if ($The_Value == $The_Static_Radio_Key) $The_HTML .= ' checked="checked"';
					$The_HTML .= '>' . $The_Static_Radio_Value . '<br/>';
				endforeach;
			endif;
			
			break;
		
		case 'Dynamic Select' :
		case 'Dynamic Radio' :
			$The_HTML .= '<em>Dynamic Fields are disabled</em>';
		/*
			$The_Value = $The_Field['value'];
			
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_set_of_aggregated_value_definitions.inc.php' );
			
			$The_Database_To_Use = new A_Mimik_Database_Interface;
			$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT']."/mimik/mimik_configuration/database_connection_info.csv" );
			$The_Database_To_Use->Establishes_A_Connection();
			
			$The_Set_Of_Aggregated_Value_Definitions = new A_Set_Of_Aggregated_Value_Definitions($The_Database_To_Use);
			
			$The_Aggregated_Value_Definition_Information = $The_Database_To_Use->Gets_The_Dynamic_Relation_Information_For($The_Field['id']);

			if (is_array($The_Aggregated_Value_Definition_Information['row_ids'])) foreach ($The_Aggregated_Value_Definition_Information['row_ids'] as $The_Row_ID) :

				$The_Aggregated_Value_Definition = new An_Aggregated_Value_Definition(
																$The_Aggregated_Value_Definition_Information['table_id'],
																$The_Aggregated_Value_Definition_Information['column_ids'],
																$The_Row_ID );
			
				$The_Set_Of_Aggregated_Value_Definitions->Appends_The_Aggregated_Value_Definition($The_Aggregated_Value_Definition);
			
			endforeach;

			$The_Result_Set_Of_Aggregated_Value_Pairs = $The_Set_Of_Aggregated_Value_Definitions->Gets_The_Set_Of_Aggregated_Values(' ');

			if ($The_Field['is_required']) $The_Required_Class = 'required';
			else $The_Required_Class = '';
			
			if ($The_Field['type'] == 'Dynamic Select') :
				$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Select_Box($The_Field_ID, $The_Value, NULL, $The_Required_Class);
			elseif ($The_Field['type'] == 'Dynamic Radio') :
				$The_HTML .= $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Radio_Buttons($The_Field_ID, $The_Value, NULL, $The_Required_Class);
			endif;
		*/	
			break;
			
		case 'WYSIWYG' :
		
			$The_HTML .= '</div>';
			
			$The_HTML .= '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
			
			$The_HTML .= '<div class="list_item_double_wide_column">';

			$The_Javascript_Array_Of_WYSIWYG_IDs .= 'The_Javascript_Array_Of_WYSIWYG_IDs[' . $The_WYSIWYG_Counter++ . '] = \'' . $The_Field_ID . '\';';
			
			include_once($_SERVER['DOCUMENT_ROOT'] . "/mimik/fckeditor/fckeditor.php") ;
			$oFCKeditor = new FCKeditor($The_Field_ID) ;
			$oFCKeditor->ToolbarSet = 'MimikToolbar';
			$oFCKeditor->Width = '600px';
			$oFCKeditor->Height = '300px';
			$oFCKeditor->BasePath = '../fckeditor/' ;
			$oFCKeditor->Value = $The_Value;
			$oFCKeditor->Create();
			
			break;
		
		case 'Group Permission' :
		
			$All_Groups = $The_Field['group_permission_data'];
			
			if (is_array($All_Groups)) :
			
				$The_HTML .= '<div id="' . $The_Group_Div_Name . '">';
			
				if (is_array($All_Groups)) foreach ($All_Groups as $The_Group_Information) :
				
					$The_HTML .= '<div class="list_item_column">' . $The_Group_Information['name'] . '</div>';
					
					$The_HTML .= '<div class="list_item_narrow_column"><input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_Group_Information['id'] . '" ';
					
					if (is_array($The_Field['value'])) :
					
						if (in_array($The_Group_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
						
					endif;
					
					$The_HTML .= '/></div>';
				
				endforeach;
				
				$The_HTML .= '</div>';
			
			else :
			
				$The_HTML .= 'No Groups';
			
			endif;
			
			break;
			
		case 'User Permission' :
		
			$All_Users = $The_Field['user_permission_data'];
			
			if (is_array($All_Users)) :
			
				$The_HTML .= '<div id="' . $The_User_Div_Name . '">';
			
				if (is_array($All_Users)) foreach ($All_Users as $The_User_Information) :
				
					$The_HTML .= '<div class="list_item_double_wide_column">' . $The_User_Information['login'] . '</div>';
					
					$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_User_Information['id'] . '" ';
					
					if (is_array($The_Field['value'])) :
					
						if (in_array($The_User_Information['id'], $The_Field['value'])) $The_HTML .= 'checked="checked" ';
						
					endif;
					
					$The_HTML .= '/>';
				
				endforeach;
				
				$The_HTML .= '</div>';
			
			else :
			
				$The_HTML .= 'No Users';
			
			endif;
			
			break;

		default :

			$The_HTML .= '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" />';

			break;

		endswitch;
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '</li>';
	
	endforeach;
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Default for New Users?</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<input type="checkbox" value="CHECK:on" id="' . $The_Submit_Tag . 'is_default" name="' . $The_Submit_Tag . 'is_default">';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Parent Group</div>';
	$The_HTML .= '<div class="list_item_wide_column">';
	$The_HTML .= '<select id="' . $The_Submit_Tag . 'parent_group_id" name="' . $The_Submit_Tag . 'parent_group_id">';
	$The_HTML .= '<option value="">None</option>';
	if (is_array($The_Input_Groups)) foreach ($The_Input_Groups as $The_Group) :
		$The_HTML .= '<option value="' . $The_Group['id'] . '">' . $The_Group['name'] . '</option>';
	endforeach;
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column"><strong>Permissions</strong></div>';
	$The_HTML .= '</li>';
	
	if (is_array($The_Input_Admin_Permissions)) foreach ($The_Input_Admin_Permissions as $The_Admin_Permission) :
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= $The_Admin_Permission['display_name'];
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_narrow_column">';
		$The_HTML .= '<input type="checkbox" name="' . $The_Submit_Tag . 'permission:' . $The_Admin_Permission['id'] . '" id="' . $The_Submit_Tag . 'permission:' . $The_Admin_Permission['id'] . '" value="CHECK:on" />';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
	endforeach;

	$The_HTML .= '</ul>';

	$The_HTML .= '<p class="admin_control_box">';
	
	$The_HTML .= '<a href="#" onclick="Create_The_Group(' .
												'\'' . $The_Submit_Tag . '\', ' .
												'\'' . $The_List_Name . '\', ' .
												'$(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\')); return false;">OK</a> | ';
	
	$The_HTML .= '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');';
	$The_HTML .= 'return false;">Cancel</a>';
	$The_HTML .= '</p>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_Field_Creator(
							$The_Input_Form_ID, 
							$The_Input_Form_Audience = 'Admin', 
							$The_Input_Field_Is_User_Field = false, 
							$The_Input_Field_Is_Group_Field = false)
{
	if ($The_Input_Form_ID == '') $The_Input_Form_ID = 0;
	
	$The_HTML = '';
	
	$The_Field_Name_Tag = 'field_name';
	
	$The_List_Name = 'field_creator_list';
	
	$The_Submit_Tag = 'CREATE_FIELD:';
	
	$The_HTML .= '<ul class="list" id="' . $The_List_Name . '">';
	
	$The_HTML .= '<input type="hidden" name="is_user_field" id="is_user_field"' . ' value="' . $The_Input_Field_Is_User_Field . '" />';
	
	$The_HTML .= '<input type="hidden" name="is_group_field" id="is_group_field"' . ' value="' . $The_Input_Field_Is_Group_Field . '" />';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Name:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="' . $The_Field_Name_Tag . '" id="' . $The_Field_Name_Tag . '" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	global $THE_FIELD_TYPE_ARRAY;
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Field Type:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<select onchange="Display_The_Controls_For_The_Text_Field(this.value); Display_The_Options_Textarea_For_The_Field_Type(this.value); Display_The_Year_Fields_For_A_Date_Selection_In_The_Div(this.options[this.selectedIndex].value, \'start_year_for_date_selection\', \'end_year_for_date_selection\');Display_The_Tables_For_A_Relational_Selection_In_The_Div(this.options[this.selectedIndex].value, \'tables_for_relational_selection\');" name="field_type" id="field_type">';
	if (is_array($THE_FIELD_TYPE_ARRAY)) foreach ($THE_FIELD_TYPE_ARRAY as $The_Type) :
		$The_HTML .= '<option value="' . $The_Type['value'] . '"';
		if ($The_Type['value'] == $The_Value) :
			$The_HTML .= ' selected="selected"';
		endif;
		$The_HTML .= '>' . $The_Type['display_name'] . '</option>';
	endforeach;
	$The_HTML .= '</select>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="options_text_container" style="display:none;">';
	$The_HTML .= '<div class="list_item_column">Options:<br/><small>Separate with line breaks.</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<textarea name="options_text" id="options_text" style="min-width:180px;max-width:180px;min-height:80px;max-height:80px;"></textarea>';
	$The_HTML .= '</div>';
	$The_HTMl .= '</li>';
	
	$The_HTML .= '<li class="list_item" id="input_control_width_container">';
	$The_HTML .= '<div class="list_item_column">Input Control Width:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="input_control_width" id="input_control_width" value="20" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	$The_HTML .= '<li class="list_item" id="character_limit_container">';
	$The_HTML .= '<div class="list_item_column">Character Limit:<br /><small>Set to 0 or blank to specify no limit</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="text" name="character_limit" id="character_limit" value="255" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
			
	$The_HTML .= '<li class="list_item" id="tables_for_relational_selection">';
	$The_HTML .= '</li>';
		
	$The_HTML .= '<li class="list_item" id="fields_for_relational_selection">';
	$The_HTML .= '</li>';
		
	$The_HTML .= '<li class="list_item" id="start_year_for_date_selection">';
	$The_HTML .= '</li>';

	$The_HTML .= '<li class="list_item" id="end_year_for_date_selection">';
	$The_HTML .= '</li>';

	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Display In Management View:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="checkbox" name="display_in_management_view" id="display_in_management_view" value="CHECK:on" checked="checked" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if (!$The_Input_Field_Is_User_Field && !$The_Input_Field_Is_Group_Field) :
	
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">Public-Facing</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_HTML .= '<input type="checkbox" name="is_public_facing" id="is_public_facing" value="CHECK:on" ';
		if ($The_Input_Form_Audience == 'Public') :
			$The_HTML .= 'checked="checked" ';
		endif;
		$The_HTML .= '/>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
	endif;
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Required:</div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<input type="checkbox" name="is_required" id="is_required" value="CHECK:on" checked="checked" />';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	if ($The_Input_Field_Is_User_Field) :
	
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">Allow Modification by User?</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_HTML .= '<input type="checkbox" name="is_modifiable_by_user" id="is_modifiable_by_user" value="CHECK:on" />';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
	endif;
	
//	$The_HTML .= '<li class="list_item" style="display:none;">';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">Explanatory Text:<br /><small>Will appear in admin and public forms.</small></div>';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<textarea name="explanatory_text" id="explanatory_text"></textarea>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '</ul>';

	$The_HTML .= '<p class="admin_control_box">';
	$The_HTML .= '<a href="#" onclick="Create_The_Field_For_The_Form_ID(' .
														'\'field_name\', ' .
														'\'field_type\', ' .
														'\'input_control_width\', ' .
														'\'character_limit\', ' .
														'\'relational_table\', ' .
														'\'relational_field_1\', ' .
														'\'relational_field_2\', ' .
														'\'relational_field_3\', ' .
														'\'display_in_management_view\', ' .
														'\'' . $The_Input_Form_ID . '\', ' .
														'\'start_year\', ' .
														'\'end_year\', ' .
														'\'is_public_facing\', ' .
														'\'is_required\', ' .
														'\'is_modifiable_by_user\', ' .
														'\'explanatory_text\', ' .
														'\'options_text\', ' .
														'\'is_user_field\', ' .
														'\'is_group_field\', ' .
														'$(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\')); ' .
														'return false;">OK</a> | ';
	
	if ($The_Input_Field_Is_User_Field) :

		$The_HTML .= '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_user_custom_fields\');';
	
	elseif ($The_Input_Field_Is_Group_Field) :
	
		$The_HTML .= '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_group_custom_fields\');';
	
	else :
	
		$The_HTML .= '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_fields&form_id=' . $The_Input_Form_ID . '\');';

	endif;

	$The_HTML .= 'return false;">Cancel</a>';
	$The_HTML .= '</p>';
		
	return $The_HTML;
	
}

function The_HTML_For_The_Object_Creator($The_Input_Object_Name, $The_Input_Object_Type, $The_Input_Div_Name, $The_Input_Target_Div_Name, $The_Input_Container_Div_Name, $The_Input_Forms = NULL, $The_Form_Is_Public_Facing = false)
{
	echo "<pre>The_HTML_For_The_Object_Creator($The_Input_Object_Name, $The_Input_Object_Type, $The_Input_Div_Name, $The_Input_Target_Div_Name, $The_Input_Container_Div_Name, $The_Input_Forms, $The_Form_Is_Public_Facing)</pre>";
	$The_HTML = '';
	
	$The_Object_Name_Tag = $The_Input_Object_Type . '_name';
	
	$The_Submit_Tag = 'CREATE_OBJECT:';
	
	if ($The_Input_Object_Name == 'View') :

		$The_List_Name = 'view_modification_list';
		
	endif;
	
	$The_HTML .= '<h2>Create ' . $The_Input_Object_Name . '</h2>';
	
	$The_HTML .= '<ul class="list" id="' . $The_List_Name . '">';

	$The_HTML .= '<li class="list_item">';

	$The_HTML .= '<div class="list_item_column">Name:</div>';

	$The_HTML .= '<div class="list_item_column">';
	
	if ($The_Input_Object_Name == 'View') :

		$The_Object_Name_Tag = $The_Submit_Tag . $The_Object_Name_Tag;
	
	endif;
	
	$The_HTML .= '<input type="text" name="' . $The_Object_Name_Tag . '" id="' . $The_Object_Name_Tag . '" />';

	$The_HTML .= '</div>';

	$The_HTML .= '</li>';

	switch ($The_Input_Object_Name) :
	
	case 'User Custom Field' :
	
		global $THE_FIELD_TYPE_ARRAY;
	
		$The_HTML .= '<li class="list_item">';

		$The_HTML .= '<div class="list_item_column">Field Type:</div>';
	
		$The_HTML .= '<div class="list_item_column">';
	
		$The_HTML .= '<select onchange="Display_The_Year_Fields_For_A_Date_Selection_In_The_Div(this.options[this.selectedIndex].value, \'start_year_for_date_selection\', \'end_year_for_date_selection\');Display_The_Tables_For_A_Relational_Selection_In_The_Div(this.options[this.selectedIndex].value, \'tables_for_relational_selection\');" name="field_type" id="field_type">';
		
		if (is_array($THE_FIELD_TYPE_ARRAY)) foreach ($THE_FIELD_TYPE_ARRAY as $The_Type) :
			$The_HTML .= '<option value="' . $The_Type['value'] . '"';
			if ($The_Type['value'] == $The_Value) :
				$The_HTML .= ' selected="selected"';
			endif;
			$The_HTML .= '>' . $The_Type['display_name'] . '</option>';
		endforeach;

		$The_HTML .= '</select>';
	
		$The_HTML .= '</div>';
	
		$The_HTML .= '</li>';
		
		$The_HTML .= '<li class="list_item" id="tables_for_relational_selection">';
		$The_HTML .= '</li>';
		
		$The_HTML .= '<li class="list_item" id="fields_for_relational_selection">';
		$The_HTML .= '</li>';
		
		$The_HTML .= '<li class="list_item" id="start_year_for_date_selection">';
		$The_HTML .= '</li>';

		$The_HTML .= '<li class="list_item" id="end_year_for_date_selection">';
		$The_HTML .= '</li>';

		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">Display In Management View:</div>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<input type="checkbox" name="display_in_management_view" id="display_in_management_view" value="CHECK:on" checked="checked" />';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		if ($The_Input_Object_Name == 'Field') :
			$The_HTML .= '<li class="list_item">';
			$The_HTML .= '<div class="list_item_column">Public-Facing</div>';
			$The_HTML .= '<div class="list_item_wide_column">';
			$The_HTML .= '<input type="checkbox" name="is_public_facing" id="is_public_facing" value="CHECK:on" ';
			if ($The_Form_Is_Public_Facing) :
				$The_HTML .= 'checked="checked" ';
			endif;
			$The_HTML .= '/>';
			$The_HTML .= '</div>';
			$The_HTML .= '</li>';
		endif;
		
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">Required:</div>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<input type="checkbox" name="is_required" id="is_required" value="CHECK:on" checked="checked" />';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">Explanatory Text:<br /><small>Will appear in admin and public forms.</small></div>';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<textarea name="explanatory_text" id="explanatory_text"></textarea>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		break;
		
	case 'View' :
	
		$The_Field_ID = $The_Submit_Tag . 'form_id';
	
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Form';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_HTML .= '<select onchange="Update_The_Sort_Field_Select_For_The_Form_In_The_Div(this.options[this.selectedIndex].value, \'sort_field_div\', \'' . $The_Submit_Tag . 'sort_field\');" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"><option></option>';
		if (is_array($The_Input_Forms)) :
			foreach ($The_Input_Forms as $The_Form) :
				$The_HTML .= '<option value="' . $The_Form['id'] . '"';
				if ($The_Form['id'] == $The_Input_View_Information['form_id']) :
					$The_HTML .= ' selected';
				endif;
				$The_HTML .= '>' . $The_Form['display_name'] . '</option>';
			endforeach;
		endif;
		$The_HTML .= '</select>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		//------
		
		$The_Field_ID = $The_Submit_Tag . 'sort_field';
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Sort On';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_HTML .= '<div id="sort_field_div">';

		$The_HTML .= The_HTML_For_The_Field_Select($The_Input_Fields, $The_Field_ID, $The_Input_View_Information['sort_field']);

		$The_HTML .= '</div>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		//------
		
		$The_Field_ID = $The_Submit_Tag . 'sort_order';
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= 'Sort Order';
		$The_HTML .= '</div>';
		$The_HTML .= '<div class="list_item_wide_column">';
		$The_HTML .= '<select name="' . $The_Field_ID . '" id="' . $The_Field_ID . '">';
		$The_HTML .= '<option></option>';
		$The_HTML .= '<option value="ASCENDING"';
		if ($The_Input_View_Information['sort_order'] == 'ASCENDING') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>Ascending</option>';
		$The_HTML .= '<option value="DESCENDING"';
		if ($The_Input_View_Information['sort_order'] == 'DESCENDING') :
			$The_HTML .= ' selected';
		endif;
		$The_HTML .= '>Descending</option></select>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		break;
		
	case 'Form';
	
		$The_HTML .= '<li class="list_item">';
		$The_HTML .= '<div class="list_item_column">';
		$The_HTML .= '<strong>Groups</strong>';
		$The_HTML .= '</div>';
		$The_HTML .= '</li>';
		
		$The_Groups = array(
						array('name' => 'Group 1', 'id' => 1),
						array('name' => 'Group 2', 'id' => 2),
						array('name' => 'Group 3', 'id' => 3),
						array('name' => 'Group 4', 'id' => 4),
						array('name' => 'Group 5', 'id' => 5) );
		
		if (is_array($The_Groups)) foreach ($The_Groups as $The_Group) :
			$The_HTML .= '<li class="list_item">';
			$The_HTML .= '<div class="list_item_column">';
			$The_HTML .= $The_Group['name'];
			$The_HTML .= '</div>';
			$The_HTML .= '<div class="list_item_narrow_column">';
			$The_HTML .= '<input type="checkbox" name="group" value="' . $The_Group['id'] . '" />';
			$The_HTML .= '</div>';
			$The_HTML .= '</li>';
		endforeach;
		
	endswitch;
	
	$The_HTML .= '</ul>';

	$The_HTML .= '<p class="admin_control_box">';
	
	switch ($The_Input_Object_Name) :
	
	case 'User Custom Field' :
	
		$The_HTML .= '<a href="#" onclick="if (!Create_The_User_Custom_Field(\'' . $The_Object_Name_Tag . '\', \'field_type\', \'relational_table\', \'relational_field_1\', \'relational_field_2\', \'relational_field_3\', \'display_in_management_view\', \'start_year\', \'end_year\')) { changeTo(\'' . $The_Input_Target_Div_Name . '\'); } return false;">OK</a> | ';
		break;
		
	case 'View' :
		
		$The_HTML .= '<a href="#" onclick="Create_The_View(\'' . $The_Submit_Tag . '\', \'' . $The_List_Name . '\');changeTo(\'' . $The_Input_Target_Div_Name . '\');return false;">OK</a> | ';
		break;
		
	case 'Form' :
	
		$The_HTML .= '<a href="#" onclick="Create_The_Instance_Of_The_Object(\'form\', \'form_name\', \'forms_displayer\');changeTo(\'' . $The_Input_Target_Div_Name . '\');return false;">Submit</a> | ';
		break;
		
	endswitch;
	
	$The_HTML .= '<a href="#" onclick="changeTo(\'' . $The_Input_Target_Div_Name . '\',-1);return false;">Cancel</a>';

	$The_HTML .= '</p>';
	
	return $The_HTML;
	
}

function The_HTML_For_The_Relational_Field_Selectors($The_Input_Fields, $The_Input_Submit_Tag = '')
{
	$The_HTML = '';
	
	for ($The_Counter = 1; $The_Counter <= 3; $The_Counter++) :
	
		$The_HTML .= '<div style="width:100%;overflow:hidden;margin-bottom:0.2em;">';
	
		$The_HTML .= '<div class="list_item_column">Field ' . $The_Counter . ':</div>';
		
		$The_HTML .= '<div class="list_item_column">';
		
		$The_Field_ID = $The_Input_Submit_Tag . 'relational_field_' . $The_Counter;
		
		$The_HTML .= '<select name="' . $The_Field_ID . '" id="' . $The_Field_ID . '">';
		
		$The_HTML .= '<option value=""></option>';
		
		if (is_array($The_Input_Fields)) foreach ($The_Input_Fields as $The_Field) :
		
			$The_HTML .= '<option value="' . $The_Field['id'] . '"';
			
			if ($The_Field['selected'] == $The_Counter) :
			
				$The_HTML .= ' selected="selected"';
				
			endif;
			
			$The_HTML .= '>' . $The_Field['display_name'] . '</option>';
		
		endforeach;
		
		$The_HTML .= '</select>';
		
		$The_HTML .= '</div>';
		
		$The_HTML .= '</div>';
		
	endfor;
	
	return $The_HTML;
}

function The_HTML_For_The_Relational_Table_Selector($The_Input_Table_Information, $The_Input_Submit_Tag_For_The_Field_Selectors = '')
{
	$The_HTML = '';
	
	$The_HTML .= '<div class="list_item_column">Form:</div>';
	
	$The_HTML .= '<div class="list_item_column">';
	
	$The_HTML .= '<select name="relational_table" id="relational_table" onchange="Display_The_Fields_For_A_Relational_Selection_In_The_Div(this.options[this.selectedIndex].value, \'fields_for_relational_selection\', \'' . $The_Input_Submit_Tag_For_The_Field_Selectors . '\');">';
	
	$The_HTML .= '<option value=""></option>';
	
	if (is_array($The_Input_Table_Information)) foreach ($The_Input_Table_Information as $The_Table) :
	
		$The_HTML .= '<option value="' . $The_Table['id'] . '"';
		
		if ($The_Table['selected']) :
		
			$The_HTML .= ' selected="selected"';
			
		endif;
		
		$The_HTML .= '>' . $The_Table['display_name'] . '</option>';
		
	endforeach;
	
	$The_HTML .= '</select>';
	
	$The_HTML .= '</div>';
	
	return $The_HTML;
}

function The_HTML_For_The_Settings_Editor($The_Input_Settings, $The_Input_Message_Div_ID)
{
	$The_Submit_Tag = 'EDIT_SETTINGS:';
	
	$The_List_Name = 'settings_modification_list';
	
	$The_HTML .= '<ul class="list" id="' . $The_List_Name . '">';
	
	$The_Field_ID = $The_Submit_Tag . 'allow_account_updates_by_users';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Allow Account Updates By Users?';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box">';
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Input_Settings['allow_account_updates_by_users']) :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_Field_ID = $The_Submit_Tag . 'email_is_login';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Email is Login';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box">';
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Input_Settings['email_is_login']) :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_Field_ID = $The_Submit_Tag . 'allow_login_change';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Allow Login Changes by Administrators?';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box">';
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Input_Settings['allow_login_change']) :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_Field_ID = $The_Submit_Tag . 'moderation_required';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Moderation Required for Registration?';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box">';
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Input_Settings['moderation_required']) :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_Field_ID = $The_Submit_Tag . 'registration_allowed';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Allow Registration?';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box">';
	$The_HTML .= '<input type="checkbox" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="CHECK:on" ';
	if ($The_Input_Settings['registration_allowed']) :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/>';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_Field_ID = $The_Submit_Tag . 'search_results_text';
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= 'Search Results Text';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box">';
	$The_HTML .= '<input type="radio" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="relevant" ';
	if ($The_Input_Settings['search_results_text'] == 'relevant') :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/> Relevant Text<br />';
	$The_HTML .= '<input type="radio" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="introduction" ';
	if ($The_Input_Settings['search_results_text'] == 'introduction') :
		$The_HTML .= 'checked="checked" ';
	endif;
	$The_HTML .= '/> Introduction Text';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';
	
	$The_HTML .= '<li class="list_item">';
	$The_HTML .= '<div class="list_item_column">';
	$The_HTML .= '<a href="#" onclick="$(\'#update_output\').load(\'/mimik/mimik_update/update.php\'); return false;">Update Database</a>';
	$The_HTML .= '</div>';
	$The_HTML .= '<div class="list_item_control_box" id="update_output">';
	$The_HTML .= '</div>';
	$The_HTML .= '</li>';

	$The_HTML .= '</ul>';

	$The_HTML .= '<p class="admin_control_box">';

	$The_HTML .= '<a href="#" onclick="$(\'#' . $The_Input_Message_Div_ID . '-text\').fadeOut(100); Modify_The_Settings_With_The_Tagged_Items_In_The_Div(\'' . $The_Submit_Tag . '\', \'' . $The_List_Name . '\');return false;">OK</a>';

//	$The_HTML .= '<a href="#" onclick="changeTo(\'placeholder\',-1);return false;">Cancel</a>';
	
	$The_HTML .= '</p>';
	
	return $The_HTML;
}

function The_HTML_For_The_Submission_Editor_For_The_Fields_And_The_Submission_And_The_Form(
			$The_Input_Fields_With_Values, 
			$The_Input_Submission_ID, 
			$The_Input_Form_ID, 
			$The_Input_Form_Name, 
			$The_Input_Submission_GUID)
{
	global $THE_SECURE_FILES_PATH;
	global $THE_BASE_URL;
	global $THE_BASE_PATH;
	
	$The_Fields = $The_Input_Fields_With_Values;
	
	$The_Use_Of_A_File_Upload = false;
	
	$The_Javascript_Array_Of_WYSIWYG_IDs = 'var The_Javascript_Array_Of_WYSIWYG_IDs = [];';
	
	$The_WYSIWYG_Counter = 0;

	$The_Submit_Tag = 'EDITSUBMISSION:';
	
	$The_Upload_Tag = 'UPLOAD:';

	$The_Group_Tag = 'EDITSUBMISSIONGROUP:';
	
	$The_User_Tag = 'EDITSUBMISSIONUSER:';
	
	$The_Group_Div_Name = 'submission_modification_group';
	
	$The_User_Div_Name = 'submission_modification_user';

	$The_List_Name = 'submission_modification_list';

	$The_Fields = $The_Input_Fields_With_Values;
	
	$The_Group_Information = $The_Input_Group_Information;
	
	$The_User_Information = $The_Input_User_Information;

	$The_HTML = '';
	
	$The_Number_Of_Slow_Loading_Fields = 0;
	
	echo '<ul class="list" id="' . $The_List_Name . '">';
	
	if (is_array($The_Fields)) foreach ($The_Fields as $The_Field) :
	
		$The_Generic_Fields_To_Be_Displayed = array('create_date', 'modify_date');
		
		if ($The_Field['is_generic'] && in_array($The_Field['name'], $The_Generic_Fields_To_Be_Displayed)) :
		
			echo '<li class="list_item">';
			
			echo '<div class="list_item_column">' . $The_Field['display_name'] . '</div>';
			
			echo '<div class="list_item_double_wide_column">' . date('F j, Y, g:i a', strtotime($The_Field['value'])) . '</div>';
			
			echo '</li>';
		
		endif;
	
	endforeach;
	
	if (is_array($The_Fields)) foreach ($The_Fields as $The_Field) :
	
		if ($The_Field['is_generic']) :
		
			// do nothing
			
		else :
		
			$The_Relational_Data = $The_Field['relational_data'];
			
			$The_Date_Data = $The_Field['date_data'];
			
			$The_Explanatory_Text = $The_Field['explanatory_text'];
			
			$The_Options_Text = $The_Field['options_text'];
		
			echo '<li class="list_item">';
	
			echo '<div class="list_item_column">';
			
			if ($The_Field['type'] == 'Group Permission' || $The_Field['type'] == 'User Permission') echo '<strong>';
			
			echo $The_Field['display_name'];
			
			if ($The_Field['is_required']) echo ' *';
			
			if ($The_Explanatory_Text) :
			
				echo '<br /><small>' . $The_Explanatory_Text . '</small>';
				
			endif;
			
			if ($The_Field['type'] == 'Group Permission' || $The_Field['type'] == 'User Permission') echo '</strong>';
	
			echo '</div>';
			
			if ($The_Field['type'] == 'Group Permission' || $The_Field['type'] == 'User Permission') echo '<br />';
	
			echo '<div class="list_item_double_wide_column">';
	
			if ($The_Field['type'] == 'Group Permission') $The_Field_ID = $The_Group_Tag . $The_Field['name'];
			elseif ($The_Field['type'] == 'User Permission') $The_Field_ID = $The_User_Tag . $The_Field['name'];
			else $The_Field_ID = $The_Submit_Tag . $The_Field['name'];
			
			$The_Upload_ID = $The_Upload_Tag . $The_Field['name'];
	
			$The_Value = $The_Field['value'];

			// COMPONENT
			switch ($The_Field['type']) :
	
			case 'Text' :
			case 'Number' :
			case 'Decimal' :
	
				echo '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" onKeyPress="return Disable_Enter_Key(event);" class="';
				
				//if ($The_Field['is_required']) echo 'class="required" ';
				
				$The_Text_Input_Classes = $The_Input_Element_Class_Array['Text'];
				
				$The_Text_Input_Classes .= ($The_Field['is_required']) ? ' required' : '';
				
				$The_Text_Input_Classes .= ($The_Field['type'] == 'Number') ? ' number' : '';
				
				$The_Text_Input_Classes .= ($The_Field['type'] == 'Decimal') ? ' decimal' : '';
				
				echo trim($The_Text_Input_Classes);
				
				echo '" ';
				
				if ($The_Field['type'] == 'Text') :
				
					if (is_numeric($The_Field['input_control_width'])) :
					
						if ($The_Field['input_control_width'] > 0) :
						
							echo 'size="' . $The_Field['input_control_width'] . '" ';
							
						endif;
						
					endif;
					
					if (is_numeric($The_Field['character_limit']) && $The_Field['character_limit'] > 0) :
						
						echo 'maxlength="' . $The_Field['character_limit'] . '" ';
						
					else :
						
						preg_match('{(\d+)}', $THE_FIELD_TYPE_ARRAY['Text']['sql_field_type'], $m); 
	
						echo 'maxlength="' . $m[1] . '" ';
							
					endif;
				
				endif;
				
				echo '/>';
	
				break;
	
			case 'Text Area' :
			
				echo '</div>';
				
				echo '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
				
				echo '<div class="list_item_double_wide_column">';
	
				echo '<textarea name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" ';
				
				if ($The_Field['is_required']) echo 'class="required" ';
				
				if (is_numeric($The_Field['character_limit']) && $The_Field['character_limit'] > 0) :
						
					echo 'maxlength="' . $The_Field['character_limit'] . '" ';
					
				endif;
				
				echo '>' . $The_Value . '</textarea>';
	
				break;
				
			case 'Date' :
				
				$The_Current_Year_Value = (int) substr($The_Value, 0, 4);
				
				$The_Current_Month_Value = substr($The_Value, 5, 2);
				
				$The_Current_Day_Value = (int) substr($The_Value, 8, 2);
				
				$The_Months = array(''=>'', '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
									'07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
				
				$The_Month_Field_ID = $The_Field['name'] . '_month_edit';
				
				$The_Day_Field_ID = $The_Field['name'] . '_day_edit';
				
				$The_Year_Field_ID = $The_Field['name'] . '_year_edit';
					
				echo '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Month_Field_ID . '" id="' . $The_Month_Field_ID . '">';
				
				if (is_array($The_Months)) foreach ($The_Months as $The_Month_Value => $The_Month_String) :
					echo '<option value="' . $The_Month_Value . '"';
					if ($The_Current_Month_Value == $The_Month_Value) echo ' selected="selected"';
					echo '>' . $The_Month_String . '</option>';
				endforeach;
				
				echo '</select>';
				
				echo '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Day_Field_ID . '" id="' . $The_Day_Field_ID . '">';
				
				echo '<option value=""></option>';
				
				for ($The_Day = 1; $The_Day <= 31; $The_Day++) :
					echo '<option value="' . $The_Day . '"';
					if ($The_Current_Day_Value == $The_Day) echo ' selected="selected"';
					echo '>' . $The_Day . '</option>';
				endfor;
				
				echo '</select>';
				
				echo '<select onchange="Populate_The_Hidden_Date_Field_With_The_Divs(\'' . $The_Field_ID . '\', \'' . $The_Year_Field_ID . '\', \'' . $The_Month_Field_ID . '\', \'' . $The_Day_Field_ID . '\')" name="' . $The_Year_Field_ID . '" id="' . $The_Year_Field_ID . '">';
				
				echo '<option value=""></option>';
				
				for ($The_Year = $The_Date_Data['start_year']; $The_Year <= $The_Date_Data['end_year']; $The_Year++) :
					echo '<option value="' . $The_Year . '"';
					if ($The_Current_Year_Value == $The_Year) echo ' selected="selected"';
					echo '>' . $The_Year . '</option>';
				endfor;
				
				echo '</select>';
				
				echo '<input type="hidden" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Value . '" ';
				
				if ($The_Field['is_required']) echo 'class="required" ';
				
				echo '/>';
				
				break;
				
			case 'File' :
			case 'Video' :
				
				$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
				$The_Action = '../mimik_support/ajax_upload_file.php';
				echo '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
				echo '<input type="hidden" name="itemtype" value="image" />';
				echo '<input type="hidden" name="maxSize" value="9999999999" />';
				echo '<input type="hidden" name="maxW" value="960" />';
				echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
				echo '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
				echo '<input type="hidden" name="filename" value="filename" />';
				echo '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
				echo "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
				echo '</form>';
				echo '<div id="' . $The_Upload_Area . '">';
				if ($The_Value) :
					echo '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
					echo '<p>URL : <a href="' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '" target="_blank">' . $THE_BASE_PATH . '/mimik_uploads/' . $The_Value . '</a></p>';
	//				echo '../mimik_uploads/' . $The_Value;
				endif;
				echo '</div>';
				
				break;
				
			case 'Secure File' :
				
				$The_Value_Array = explode('/', $The_Value);
				$The_Current_Filename = $The_Value_Array[count($The_Value_Array)-1];
				$The_Upload_Relative_Path = 'mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Full_Path = $THE_BASE_URL . '/mimik_temp_secure_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Area = 'EDITSUBMISSION:' . $The_Field['name'] . '_upload_area';
				$The_Action = '../mimik_support/ajax_upload_secure_file.php';
				echo '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
				echo '<input type="hidden" name="itemtype" value="image" />';
				echo '<input type="hidden" name="maxSize" value="9999999999" />';
				echo '<input type="hidden" name="maxW" value="960" />';
				//echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
				echo '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
				echo '<input type="hidden" name="filename" value="filename" />';
				echo '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
				echo "<p><input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" /></p>";
				echo '</form>';
				echo '<div id="' . $The_Upload_Area . '">';
				if ($The_Value) :
					echo '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a>';
					echo '<p>FILE : <a href="../mimik_live_data/secure_file.php?filename=' . urlencode(str_replace('mimik_secure_uploads/', '', $The_Value)) . '" target="_blank">' . $The_Current_Filename . '</a></p>';
	//				echo '../mimik_uploads/' . $The_Value;
				endif;
				echo '</div>';
				
				break;
			
			case 'Image' :
			
				//echo '<input type="file" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" size="20" />';
				$The_Upload_Relative_Path = 'httpdocs/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Full_Path = $THE_BASE_URL . '/mimik/mimik_temp_uploads/' . str_replace('EDITSUBMISSION:', '', $The_Input_Submission_GUID) . '/';
				$The_Upload_Area = $The_Field['name'] . '_upload_area';
				$The_Action = '../mimik_support/ajax_upload.php';
				echo '<form action="' . $The_Action . '" method="post" enctype="multipart/form-data">';
				echo '<input type="hidden" name="itemtype" value="image" />';
				echo '<input type="hidden" name="maxSize" value="9999999999" />';
				echo '<input type="hidden" name="maxW" value="960" />';
				echo '<input type="hidden" name="fullPath" value="' . $The_Upload_Full_Path . '" />';
				echo '<input type="hidden" name="relPath" value="' . $The_Upload_Relative_Path . '" />';
				echo '<input type="hidden" name="colorR" value="255" />';
				echo '<input type="hidden" name="colorG" value="255" />';
				echo '<input type="hidden" name="colorB" value="255" />';
				echo '<input type="hidden" name="maxH" value="960" />';
				echo '<input type="hidden" name="filename" value="filename" />';
				echo '<input type="hidden" name="submitID" value="' . $The_Field_ID . '" />';
				echo '<p>';
				echo "<input type=\"file\" name=\"filename\" onchange=\"ajaxUpload(this.form,'" . $The_Action . "?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=" . $The_Upload_Full_Path . "&amp;relPath=" . $The_Upload_Relative_Path . "&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','" . $The_Upload_Area . "','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;\" />";
				echo "</p>";
				echo '</form>';
				echo '<div id="' . $The_Upload_Area . '">';
				if ($The_Value) :
					echo '<a href="#" onclick="Remove_File_From_The_Submission(' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', \'' . $The_Field['name'] . '\', \'' . $The_Upload_Area . '\'); return false;">Remove</a><br />';
					echo '<img style="max-width:200px;border:none;" src="../mimik_uploads/' . $The_Value .'" border="0" /><br />';
					echo '../mimik_uploads/' . $The_Value;
				endif;
				echo '</div>';
				
				break;
				
			case 'Static Select' :
				$The_Options = explode("\n",$The_Field['options_text']);
				
				if (is_array($The_Options)) :
					echo '<select ';
					if ($The_Input_Element_Class_Array['Static Select'] || $The_Field['is_required']) echo 'class="';
					
					$The_Static_Select_Input_Classes = $The_Input_Element_Class_Array['Static Select'];
					$The_Static_Select_Input_Classes .= ($The_Field['is_required']) ? ' required' : '';
					echo trim($The_Static_Select_Input_Classes);
					
					if ($The_Input_Element_Class_Array['Static Select'] || $The_Field['is_required']) echo '" ';
					echo 'name="' . $The_Field_ID . '" id="' . $The_Field_ID . '"><option></option>';
					
					foreach($The_Options as $The_Option):
						if($Separator = strpos($The_Option,":")!==false):
							$The_Static_Select_Key = substr($The_Option,0,$Separator);
							$The_Static_Select_Value = substr($The_Option,$Separator+1);
							echo '<option value="' . $The_Static_Select_Key . '"';
							if($The_Value == $The_Static_Select_Key) echo ' selected="selected"';
							echo '>' . $The_Static_Select_Value . '</option>';
						else:
							echo '<option value="' . $The_Option . '"';
							if($The_Value == $The_Option) echo ' selected="selected"';
							echo '>' . $The_Option . '</option>';
						endif;
					endforeach;
					echo '</select>';
				endif;
				
				break;
			
			case 'Static Radio' :
				$The_Options = explode("\n",$The_Field['options_text']);
				
				if (is_array($The_Options)) :
					$The_Static_Radio_Input_Classes = $The_Input_Element_Class_Array['Static Radio'];
					$The_Static_Radio_Input_Classes .= ($The_Field['is_required']) ? ' required' : '';
					
					foreach($The_Options as $The_Option):
						if($Separator = strpos($The_Option,":")!==false):
							$The_Static_Radio_Key = substr($The_Option,0,$Separator);
							$The_Static_Radio_Value = substr($The_Option,$Separator+1);
						else:
							$The_Static_Radio_Key = $The_Option;
							$The_Static_Radio_Value = $The_Option;
						endif;
						
						$html .= '<input type="radio"';
						if ($The_Input_Element_Class_Array['Static Radio'] || $The_Field['is_required']) $html .= ' class="' . $The_Static_Radio_Input_Classes . '"';
						$html .= ' name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" value="' . $The_Static_Radio_Key . '"';
						if ($The_Value == $The_Static_Radio_Key) $html .= ' checked="checked"';
						$html .= '>' . $The_Static_Radio_Value . '<br/>';
					endforeach;
					echo $html;
					unset($html);
				endif;
				
				break;
				
			case 'Dynamic Select' :
			case 'Dynamic Radio' :
			
				$The_Value = $The_Field['value'];
				
				require_once( '../mimik_includes/ivy-mimik_database_utilities.inc.php' );
				require_once( '../mimik_includes/a_set_of_aggregated_value_definitions.inc.php' );
				
				$The_Database_To_Use = new A_Mimik_Database_Interface;
				$The_Database_To_Use->Will_Connect_Using_The_Information_In( "../mimik_configuration/database_connection_info.csv" );
				$The_Database_To_Use->Establishes_A_Connection();
				
				$The_Set_Of_Aggregated_Value_Definitions = new A_Set_Of_Aggregated_Value_Definitions($The_Database_To_Use);
				
				$The_Aggregated_Value_Definition_Information = $The_Database_To_Use->Gets_The_Dynamic_Relation_Information_For($The_Field['id']);
	
				if (is_array($The_Aggregated_Value_Definition_Information['row_ids'])) foreach ($The_Aggregated_Value_Definition_Information['row_ids'] as $The_Row_ID) :
	
					$The_Aggregated_Value_Definition = new An_Aggregated_Value_Definition(
																	$The_Aggregated_Value_Definition_Information['table_id'],
																	$The_Aggregated_Value_Definition_Information['column_ids'],
																	$The_Row_ID );
				
					$The_Set_Of_Aggregated_Value_Definitions->Appends_The_Aggregated_Value_Definition($The_Aggregated_Value_Definition);
				
				endforeach;
				
				$The_Result_Set_Of_Aggregated_Value_Pairs = $The_Set_Of_Aggregated_Value_Definitions->Gets_The_Set_Of_Aggregated_Values(' ');
				
				$The_Result_Set_Of_Aggregated_Value_Pairs->Sorts_Alphabetically();

				if ($The_Field['is_required']) $The_Required_Class = 'required';
				else $The_Required_Class = '';
				
				if ($The_Field['type'] == 'Dynamic Select') :
					echo $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Select_Box($The_Field_ID, $The_Value, NULL, $The_Required_Class);
				elseif ($The_Field['type'] == 'Dynamic Radio') :
					echo $The_Result_Set_Of_Aggregated_Value_Pairs->HTML_For_The_Radio_Buttons($The_Field_ID, $The_Value, NULL, $The_Required_Class);
				endif;
				
				break;
			
			case 'WYSIWYG' :
			
				$The_Number_Of_Slow_Loading_Fields++;
			
				echo '</div>';
				
				echo '<div class="clear"></div>'; // closes the list_item_wide_column div (unused by WYSIWYG)
				
				echo '<div class="list_item_double_wide_column">';
	
				$The_Javascript_Array_Of_WYSIWYG_IDs .= 'The_Javascript_Array_Of_WYSIWYG_IDs[' . $The_WYSIWYG_Counter++ . '] = \'' . $The_Field_ID . '\';';
				
				include_once($_SERVER['DOCUMENT_ROOT'] . "/mimik/fckeditor/fckeditor.php") ;
				$oFCKeditor = new FCKeditor($The_Field_ID) ;
				$oFCKeditor->ToolbarSet = 'MimikToolbar';
				$oFCKeditor->Width = '600px';
				$oFCKeditor->Height = '300px';
				$oFCKeditor->BasePath = '../fckeditor/' ;
				$oFCKeditor->Value = $The_Value;
				$oFCKeditor->Create();
				
				break;
			
			case 'Group Permission' :
			
				$All_Groups = $The_Field['group_permission_data'];
				
				if (is_array($All_Groups)) :
				
					$The_Random_Group_Permission_Input_ID = uniqid('group');
					
					echo '<form autocomplete="off" method="get">';
					
					echo 'Filter on name: <input id="' . $The_Random_Group_Permission_Input_ID . '" type="text" name="' . $The_Random_Group_Permission_Input_ID . '" value=""/>';
				
					echo '<ul id="' . $The_Group_Div_Name . '" class="height200 scrollable">';
				
					if (is_array($All_Groups)) foreach ($All_Groups as $The_Group_Information) :
					
						echo '<li><div class="list_item_wide_column">';
						
						echo '<span class="filterable">' . $The_Group_Information['name'] . '</span></div>';
						
						echo '<div class="list_item_narrow_column">';
						
						echo '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_Group_Information['id'] . '" ';
						
						if (is_array($The_Field['value'])) :
						
							if (in_array($The_Group_Information['id'], $The_Field['value'])) echo 'checked="checked" ';
							
						endif;
						
						if ($The_Field['is_required']) echo 'class="required" ';
						
						echo '/></div></li>';
					
					endforeach;
					
					echo '</ul></form><br />';
					
					echo '<img onload="$(\'#' . $The_Random_Group_Permission_Input_ID . '\').live(\'keyup\', function(){ $(\'#' . $The_Random_Group_Permission_Input_ID . '\').liveUpdate(\'#' . $The_Group_Div_Name . '\').focus(); });" id="" src="/mimik/mimik_images/blank.gif"/>';
				
				else :
				
					echo 'No Groups';
				
				endif;
				
				break;
				
			case 'User Permission' :
			
				$All_Users = $The_Field['user_permission_data'];
				
				if (is_array($All_Users)) :
				
					$The_Random_User_Permission_Input_ID = uniqid('user');
	
					echo '<form autocomplete="off" method="get">';
	
					echo 'Filter on name: <input id="' . $The_Random_User_Permission_Input_ID . '" type="text" name="' . $The_Random_User_Permission_Input_ID . '" value=""/>';
	
					echo '<ul id="' . $The_User_Div_Name . '" class="height200 scrollable width400">';
				
					if (is_array($All_Users)) foreach ($All_Users as $The_User_Information) :
					
						echo '<li><div class="list_item_double_wide_column"><span class="filterable">' . $The_User_Information['login'] . '</span></div>';
						
						echo '<input type="checkbox" name="' . $The_Field_ID . '" value="' . $The_User_Information['id'] . '" ';
						
						if (is_array($The_Field['value'])) :
						
							if (in_array($The_User_Information['id'], $The_Field['value'])) echo 'checked="checked" ';
							
						endif;
						
						if ($The_Field['is_required']) echo 'class="required" ';
						
						echo '/></li>';
					
					endforeach;
					
					echo '</ul></form><br />';
					
					echo '<img onload="$(\'#' . $The_Random_User_Permission_Input_ID . '\').live(\'keyup\', function(){ $(\'#' . $The_Random_User_Permission_Input_ID . '\').liveUpdate(\'#' . $The_User_Div_Name. '\').focus(); });" id="" src="/mimik/mimik_images/blank.gif"/>';
				
				else :
				
					echo 'No Users';
				
				endif;
				
				break;
	
			default :
			
				echo '<input type="text" name="' . $The_Field_ID . '" id="' . $The_Field_ID . '" ';
				
				if ($The_Field['is_required']) echo 'class="required" ';
				
				echo '/>';
					
				break;
	
			endswitch;
			
			echo '</div>';

			echo '</li>';		
			
		endif; // if (!$The_Field['is_generic'])

	endforeach;
	
	$The_Creator_User_Tag =  $The_Submit_Tag . 'creator_user';
	
	echo '<li class="list_item">* Required field</li>';
	
	echo '</ul>';
	
	echo '<script>';
		echo '$(\'.ok_button_container a\').hide();';
		echo '$(\'.ok_button_container span\').show();';
		echo '$(document).ready(function() {';
			echo 'setTimeout(function() {';
				echo '$(\'.ok_button_container span\').hide();';
				echo '$(\'.ok_button_container a\').show();';
			echo '}, ' . ($The_Number_Of_Slow_Loading_Fields * 1500) . ');';
		echo '});';
	echo '</script>';
	
	echo '<p class="admin_control_box">';
	
	$The_Database_To_Use = new A_Mimik_Database_Interface;
	$The_Database_To_Use->Will_Connect_Using_The_Information_In($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv');
	$The_Database_To_Use->Establishes_A_Connection();
	
	$The_Form_Type = $The_Database_To_Use->Gets_The_Type_Of_The_Table($The_Input_Form_ID);
	
	if ($The_Form_Type == 'Image_Map') :
	
		$The_Javascript_Function_Call = 'Show_The_Image_Map_For_The_Form';
		
		$The_Javascript_Function_Call .= '(' . $The_Input_Form_ID . ');';
		
	else :
	
		$The_Javascript_Function_Call = '';
		
	endif;
	
	echo '<span class="ok_button_container">';
	
	echo '<span>OK</span>';
	
	echo '<a href="#" class="ok_button" ';
	
	echo 'onclick="if (Verifies_That_Required_Fields_Are_Filled()) { ' .
				$The_Javascript_Array_Of_WYSIWYG_IDs . 
				'Populate_The_WYSIWYG_Editors(The_Javascript_Array_Of_WYSIWYG_IDs); ' .
				'Modify_The_Submission_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(\'' . 
						$The_Input_Submission_ID . '\', \'' . 
						$The_Input_Submission_GUID . '\', \'' . 
						$The_Submit_Tag . '\', \'' . 
						$The_Group_Tag . '\', \'' . 
						$The_User_Tag . '\', \'' . 
						$The_List_Name . '\', ' .
						$The_Input_Form_ID . ', ' .
						'true, ' .
						'$(this).parents().filter(\'.ui-tabs-panel\').attr(\'id\'), \'' .
						$The_Creator_User_Tag . '\');';
	
	echo ' ' . $The_Javascript_Function_Call;
				
	echo '} else { alert(\'At least one required field is blank or contains an invalid value.\'); } return false;">OK</a>';
	
	echo '</span>';
	
	echo ' | ';
	
	//echo '<a href="#" onclick="$(\'#map\').attr(\'src\',$(\'#previous\').val());changeTo(\'submissions_displayer\',-1);return false;">Cancel</a>';
	
	echo '<a href="#" onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_submissions&form_id=' . $The_Input_Form_ID . '\');';
	echo 'return false;">Cancel</a>';
	
	echo '</p>';
	
	echo '</form>'; // used to be a div

	return $The_HTML;

}

function Sort_By_User_Defined_Field($a, $b)
{
    if ($a[$GLOBALS['sort_field']] == $b[$GLOBALS['sort_field']]) :
        return 0;
    endif;
	
	if ($GLOBALS['sort_order'] == 'ASCENDING') :
		if ($a[$GLOBALS['sort_field']] < $b[$GLOBALS['sort_field']]) return -1;
		else return 1;
	else :
		if ($a[$GLOBALS['sort_field']] > $b[$GLOBALS['sort_field']]) return -1;
		else return 1;
	endif;
}

?>