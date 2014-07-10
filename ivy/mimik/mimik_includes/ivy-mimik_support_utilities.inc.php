<?
session_start();

require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_html_utilities.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/site_wide_utilities.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_registration_component.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/the_system_settings.config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_mimik_multifunction_data_table.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/mdt_display_functions.inc.php' );

$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();

global $THE_BASE_SERVER_PATH;
global $THE_FILE_UPLOAD_TYPES;

$The_Server_Array = $_SERVER;

$The_Public_Facing_Forms = array($THE_BASE_URL, $THE_BASE_URL . '/', $THE_BASE_URL . '/mimik/mimik_live_data/account_management.php');

$The_Public_Facing_Form_IDs = $The_Database_To_Use->All_Public_Forms();

if (is_array($The_Public_Facing_Form_IDs)) foreach ($The_Public_Facing_Form_IDs as $The_Form_ID) :

	$The_Public_Facing_Forms[] = $THE_BASE_URL . '/mimik/mimik_live_data/form.php?id=' . $The_Form_ID;

endforeach;

$The_Form_ID = $_REQUEST['form_id'];

$Is_Public_Nonrestricted_Form = false;

if (in_array($The_Form_ID, $The_Public_Facing_Form_IDs)) :

	$The_Group_Permissions = $The_Database_To_Use->All_Group_Permissions_For_The_Form($The_Form_ID);
	
	$The_User_Permissions = $The_Database_To_Use->All_User_Permissions_For_The_Form($The_Form_ID);
	
	if (count($The_Group_Permissions) == 0 && count($The_User_Permissions) == 0) :
	
		$Is_Public_Nonrestricted_Form = true;
		
	endif;

endif;

if (!$_SESSION['login'] && !in_array($The_Server_Array['HTTP_REFERER'], $The_Public_Facing_Forms) && !Is_Public_Nonrestricted_Form) :

	echo 'Sorry, your session has timed out.  Please <a href="login.php">login</a>.';
	
	exit;

endif;

if (isset($_POST['support_function'])) :

	$The_Variable_Array = $_POST;
	
elseif (isset($_GET['support_function'])) :

	$The_Variable_Array = $_GET;
	
else :

	header('Location:' . $The_Server_Array['HTTP_REFERER'] . '?message=No function specified');
	
	exit;
	
endif;

$The_Authenticated_User_ID = $The_Database_To_Use->Gets_The_User_ID_For_The_User_Name($_SESSION['login']);

$The_Admin_Permissions = $The_Database_To_Use->All_Admin_Permissions_For_The_User($_SESSION['login']);

if ($The_Admin_Permissions == NULL) $The_Admin_Permissions = array();

switch ($The_Variable_Array['support_function']) :

case 'add_member' :

	$The_User_ID_Array = $The_Variable_Array['user_id'];
	
	$The_Group_ID = $The_Variable_Array['group_id'];
	
	$The_Number_Of_Members = 0;
	
	if (is_array($The_User_ID_Array)) foreach ($The_User_ID_Array as $The_User_ID) :
	
		if (is_numeric($The_User_ID)) $The_Database_To_Use->Create_The_Group_Association_For_The_User($The_Group_ID, $The_User_ID);
		
		$The_Number_Of_Members++;
	
	endforeach;
	
	if ($The_Number_Of_Members === 0) :
	
		$The_Message = 'No members were added to the group';
	
	elseif($The_Number_Of_Members === 1) :
	
		$The_Message = 'One member was added to the group';
		
	else :
	
		$The_Message = $The_Number_Of_Members . ' members were added to the group';
	
	endif;
	
	//echo '<div class="navigation-container"><p><strong>Groups</strong></p></div>';
	
	echo The_HTML_For_The_Message_Div($The_Message);
	
	//echo Displays_The_MDT_For_All_Groups($The_Database_To_Use, $The_Group_ID);
	
	Load_Member_Addition($The_Database_To_Use, $The_Group_ID);
	
	break;
	
case 'remove_member' :

	$The_User_ID_Array = $The_Variable_Array['user_id'];
	
	$The_Group_ID = $The_Variable_Array['group_id'];
	
	$The_Number_Of_Members = 0;
	
	if (is_array($The_User_ID_Array)) foreach ($The_User_ID_Array as $The_User_ID) :
	
		if (is_numeric($The_User_ID)) $The_Database_To_Use->Delete_The_Group_Association_For_The_User($The_Group_ID, $The_User_ID);
		
		$The_Number_Of_Members++;
	
	endforeach;
	
	if ($The_Number_Of_Members === 0) :
	
		$The_Message = 'No members were removed from the group';
	
	elseif($The_Number_Of_Members === 1) :
	
		$The_Message = 'One member was removed from the group';
		
	else :
	
		$The_Message = $The_Number_Of_Members . ' members were removed from the group';
	
	endif;
	
	//echo '<div class="navigation-container"><p><strong>Groups</strong></p></div>';
	
	echo The_HTML_For_The_Message_Div($The_Message);
	
	//echo Displays_The_MDT_For_All_Groups($The_Database_To_Use, $The_Group_ID);
	
	Load_Member_Management($The_Database_To_Use, $The_Group_ID);
	
	break;

case 'load_member_addition' :

	$The_Group_ID = $The_Variable_Array['group_id'];
	
	Load_Member_Addition($The_Database_To_Use, $The_Group_ID);

	break;
	
case 'load_member_management' :

	$The_Group_ID = $The_Variable_Array['group_id'];

	Load_Member_Management($The_Database_To_Use, $The_Group_ID);
	
	break;

case 'create_field' :
	
	$The_Form_ID = $The_Variable_Array['form_id'];

	if ($The_Variable_Array['field_name']) :
	
		if ($The_Variable_Array['field_type']) :
		
			$The_Display_Flag = false;
			
			if ($The_Variable_Array['display'] == 'on') :
			
				$The_Display_Flag = true;
			
			endif;
			
			$The_Public_Flag = false;
			
			if ($The_Variable_Array['is_public_facing'] == 'on') :
			
				$The_Public_Flag = true;
			
			endif;
			
			$The_Required_Flag = false;
			
			if ($The_Variable_Array['is_required'] == 'on') :
			
				$The_Required_Flag = true;
			
			endif;
			
			$The_New_Field_ID = $The_Database_To_Use->Create_The_Field_Of_The_Type_For_The_Table(
										$The_Variable_Array['field_name'],
										$The_Variable_Array['field_type'],
										$The_Variable_Array['input_control_width'],
										$The_Variable_Array['character_limit'],
										$The_Display_Flag,
										$The_Form_ID,
										$The_Variable_Array['relational_table_id'],
										$The_Variable_Array['relational_field_id_1'],
										$The_Variable_Array['relational_field_id_2'],
										$The_Variable_Array['relational_field_id_3'],
										$The_Variable_Array['start_year'],
										$The_Variable_Array['end_year'],
										$The_Required_Flag,
										$The_Public_Flag,
										$The_Variable_Array['explanatory_text'],
										$The_Variable_Array['options_text'] );
	
			if ($The_New_Field_ID !== false) :
			
				// do nothing
			
			else :
			
				echo 'Error: Field could not be created.';
			
			endif;
			
			$The_Fields = $The_Database_To_Use->All_User_Defined_Fields_For_The_Table($The_Form_ID);
			
			$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
			
			if ($The_Database_To_Use->Gets_The_Audience_For_The_Form($The_Form_ID) == 'Public') $Is_Public_Facing = 1;
			else $Is_Public_Facing = 0;
			
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
			echo ' &raquo; ';
			echo '<strong>Fields</strong></p></div>';
			
			echo Displays_The_MDT_For_All_Fields_For_The_Form($The_Database_To_Use, $The_Form_ID, $The_New_Field_ID);
			
		else :
		
			echo 'Error: Field type is blank.';
			
		endif;
	
	else :
	
		echo 'Error: Field name is blank.';
		
	endif;
	
	break;
	
case 'create_form' :

	if (isset($The_Variable_Array['form_name'])) :
	
		$The_New_Form_ID = $The_Database_To_Use->Create_The_Table($The_Variable_Array['form_name'], $The_Variable_Array['form_type']);
		
		$The_Group_ID_Array = explode(',', $The_Variable_Array['groups']);
		
		if ($The_Variable_Array['form_type'] == 'Image_Map') :
		
			$The_Database_To_Use->Set_The_Type_For_The_Form($The_New_Form_ID, $The_Variable_Array['form_type']);
			
			$The_Database_To_Use->Set_The_Filename_For_The_Form($The_New_Form_ID, $The_Variable_Array['filename']);
			
		endif;
		
		if (count($The_Group_ID_Array) > 0) $The_Database_To_Use->Add_The_Group_Permissions_To_The_Form($The_New_Form_ID, $The_Group_ID_Array);
		
		$The_Database_To_Use->Set_The_Audience_For_The_Form($The_New_Form_ID, $The_Variable_Array['audience']);
		
		$The_Database_To_Use->Set_The_Limit_Access_For_The_Form($The_New_Form_ID, $The_Variable_Array['limit_access']);
		
		$The_Database_To_Use->Set_The_Email_Notification_For_The_Form($The_New_Form_ID, $The_Variable_Array['email_notification_flag']);
		
		$The_Database_To_Use->Set_The_Confirmation_Message_For_The_Form($The_New_Form_ID, addslashes($The_Variable_Array['confirmation_message']));
		
		$The_Database_To_Use->Set_The_Email_Recipients_For_The_Form($The_New_Form_ID, $The_Variable_Array['email_recipients']);
		
		if ($The_New_Form_ID === false) echo The_HTML_For_The_Error_Div('Error: Form could not be created.');
		
		echo '<div class="navigation-container"><p>';
		echo '<strong>Forms</strong>';
		echo '</p></div>';
		
		echo Displays_The_MDT_For_All_Forms($The_Database_To_Use, $The_New_Form_ID);
		
		/*include('http://' . $_SERVER['SERVER_NAME'] . '/mimik/mimik_support/show_data_table.php' .
								'?config_file=' . urlencode('../mimik_configuration/mdt_forms_settings.config.php') .
								'&function=Gets_The_Forms' .
								'&fields_var=The_Form_Fields' .
								'&single_row_actions_var=The_Form_Single_Row_Actions' .
								'&multirow_actions_var=The_Form_Multirow_Actions' .
								'&table_item=form' .
								'&sort_on=display_name' .
								'&sort_dir=ASC' .
								'&highlighted_rows=' . urlencode($The_New_Form_ID) .
								'&session_id=' . session_id()); //sessionfix*/
	
	else :
	
		echo 'Error: ' . $The_Object_Name . ' name is blank.';
		
	endif;
	
	break;
	
case 'create_group' :

	if (isset($The_Variable_Array['submit_prefix'])) :
	
		$The_Submit_Prefix = $The_Variable_Array['submit_prefix'];

		$The_Group_Name = $The_Variable_Array[$The_Submit_Prefix . 'name'];

		$The_Group_ID = $The_Database_To_Use->Create_The_Group($The_Group_Name);

		if ($The_Group_ID !== false) :
		
			// do nothing
		
		else :
		
			echo The_HTML_For_The_Error_Div('Error: Group could not be created.');
		
		endif;
		
		$The_Admin_Permissions = array();
		
		$The_Submitted_Value_Pairs = array();
		
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Submit_Prefix . 'permission:') === 0) :
			
				if ($The_Value == 'on') :
				
					$The_Admin_Permissions[] = substr($The_Key, strlen($The_Submit_Prefix . 'permission:'));
					
				endif;
			
			elseif (strpos($The_Key, $The_Submit_Prefix) === 0) :
			
				if (substr($The_Key, strlen($The_Submit_Prefix)) == 'is_default') :
				
					$The_Value = ($The_Value == 'on') ? 1 : 0;
					
				endif;
			
				$The_Submitted_Value_Pairs[substr($The_Key, strlen($The_Submit_Prefix))] = $The_Value;
			
			endif;
		
		endforeach;
		
		$The_Database_To_Use->Update_The_Group($The_Group_ID, $The_Submitted_Value_Pairs, $The_Admin_Permissions);
		
		echo '<div class="navigation-container"><p><strong>Groups</strong></p></div>';
			
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Groups($The_Database_To_Use, $The_Group_ID);
		echo '</div>';
			
	else :
	
		echo 'Error: Group name is blank.';
		
	endif;

	break;
	
case 'create_group_custom_field' :

	if ($The_Variable_Array['field_name']) :
	
		if ($The_Variable_Array['field_type']) :
	
			$The_Display_Flag = false;
					
			if ($The_Variable_Array['display'] == 'on') :
			
				$The_Display_Flag = true;
			
			endif;
		
			if ($The_Variable_Array['is_required'] == 'on') :
			
				$The_Required_Flag = true;
			
			endif;
			
			$The_New_Field_ID = $The_Database_To_Use->Creates_A_Group_Custom_Field(
										$The_Variable_Array['field_name'],
										$The_Variable_Array['field_type'],
										$The_Variable_Array['input_control_width'],
										$The_Variable_Array['character_limit'],
										$The_Display_Flag,
										$The_Variable_Array['relational_table_id'],
										$The_Variable_Array['relational_field_id_1'],
										$The_Variable_Array['relational_field_id_2'],
										$The_Variable_Array['relational_field_id_3'],
										$The_Variable_Array['start_year'],
										$The_Variable_Array['end_year'],
										$The_Required_Flag,
										$The_Variable_Array['explanatory_text'],
										$The_Variable_Array['options_text'] );
										
			if ($The_New_Field_ID !== false) :
					
				// do nothing
				
			else :
			
				echo 'Error: Field could not be created.';
			
			endif;
			
		else :
		
			echo 'Error: Field type is blank.';
			
		endif;
		
	else :
	
		echo 'Error: Field name is blank.';
		
	endif;
	
	require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
	// note that there are no security restrictions on the forms displayed
	
	$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
								$_SESSION,
								$The_Database_To_Use,
								$The_Group_Custom_Field_Callback_Function, 
								$The_Group_Custom_Field_Fields, 
								$The_Group_Custom_Field_Single_Row_Actions,
								$The_Group_Custom_Field_Multirow_Actions,
								$The_Field_Start_Row, // the start row (use default: 0)
								$The_Field_Row_Limit, // the row limit (use default: 10)
								array(
									'display_in_management_view' => 'DESC',
									'display_order_number' => 'ASC'), // the sort information
								NULL, // the filter array
								NULL, // the focus element
								'field', // the item name to be displayed
								$The_New_Field_ID
							);
	
	$The_Data_Table->Loads_The_Rows();
	
	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
	echo ' &raquo; ';
	echo '<strong>Custom Fields</strong></p></div>';
	
	echo '<div class="table-wrapper">';
	echo $The_Data_Table->Live_Site_HTML();
	echo '</div>';
	
	break;

case 'create_submission' :

	if (isset($The_Variable_Array['submit_prefix'])) :
	
		$The_New_Submission_ID = $The_Database_To_Use->Create_The_Row_In_The_Table(NULL, $The_Variable_Array['form_id']);
		
		$The_Prefix = $The_Variable_Array['submit_prefix'];
		
		$The_Form_ID = $The_Variable_Array['form_id'];
		
		$The_Email_Notification_Information = $The_Database_To_Use->Gets_The_Email_Notification_Information_For_The_Form($The_Form_ID);
		
		$The_Email_Notification = $The_Email_Notification_Information['email_notification_flag'];
		
		if ($The_Email_Notification) $The_Email_Recipients = str_replace("\n", ', ', $The_Email_Notification_Information['email_recipients']);
		
		$The_Submitted_Value_Pairs = array();
		
		$The_Table_Name = $The_Database_To_Use->Gets_The_Name_Of_The_Table($The_Form_ID);
											
		$The_Form_Display_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
		
		if ($The_Email_Notification) :
		
			$The_Email_Subject = 'New Submission in ' . $The_Form_Display_Name;
			
			$The_Email_Body = 'This is an automatic message from ' . $THE_ORGANIZATION_NAME . '. Please do not reply to this email.' . "\n\n" .
							  'A new Submission has been created on the ' . $The_Form_Display_Name . ' Form (id ' . $The_Form_ID . ")\n\n";
			
		endif;
		
		$The_Form_Mimik_Name = The_Mimik_Safe_Form_Name($The_Form_Display_Name);
		
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Prefix) === 0) :
			
				$The_Field_Type_Condition_Array = array();
				
				$The_Field_Type_Condition_Array['table_id'] = $The_Form_ID;
				
				$The_Field_Type_Condition_Array['name'] = substr($The_Key, strlen($The_Prefix));
			
				$The_Submitted_Value_Type = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To_Multiple_Conditions(
														'Fields',
														'type',
														$The_Field_Type_Condition_Array );
				
				if ($The_Value != '' && $The_Submitted_Value_Type != '') :
					
					if (in_array($The_Submitted_Value_Type, $THE_FILE_UPLOAD_TYPES)) :
						
						$The_File_Name = substr($The_Value, strrpos($The_Value, '/') + 1);
						
						if ($The_Submitted_Value_Type == 'Secure File') :
						
							$The_Temporary_Directory = $THE_BASE_SERVER_PATH . '/' . substr($The_Value, 0, strrpos($The_Value, '/'));
						
							$The_New_File_Path = 'mimik_secure_uploads/' . $The_Form_Mimik_Name . '/' . $The_New_Submission_ID . '/' . $The_File_Name;
							
							mkdir_recursive('mimik_secure_uploads/' . $The_Form_Mimik_Name . '/' . $The_New_Submission_ID, $THE_BASE_SERVER_PATH);
							
							if (rename($THE_BASE_SERVER_PATH . '/' . $The_Value, $THE_BASE_SERVER_PATH . '/' . $The_New_File_Path)) :
							
								rmdir($The_Temporary_Directory);
								
							else :
							
								echo '<pre>Error: could not move file to permanent location. Check file permissions on ' . $The_New_File_Path . '</pre>';

							endif;
							
						elseif ($The_Submitted_Value_Type == 'Video') :
							
							$The_Temporary_Directory = $THE_BASE_SERVER_PATH . '/' . substr($The_Value, 0, strrpos($The_Value, '/'));
							
							$The_New_File_Path = 'mimik_video/' . $The_Form_Mimik_Name . '___' . $The_New_Submission_ID . '___' . $The_File_Name;
							
							if (rename($THE_BASE_SERVER_PATH . '/' . $The_Value, $THE_BASE_SERVER_PATH . '/' . $The_New_File_Path)) :
							
								$The_New_File_Path = str_replace('mimik_video/','',substr($The_New_File_Path,0,strrpos($The_New_File_Path,'.')).'.flv');
								
								rmdir($The_Temporary_Directory);
								
							else :
							
								echo '<pre>Error: could not move file to permanent location. Check file permissions on ' . $The_New_File_Path . '</pre>';

							endif;
						
						else :
						
							$The_Temporary_Directory = $THE_BASE_SERVER_PATH . '/' . substr($The_Value, 0, strrpos($The_Value, '/'));
							
							$The_New_File_Path = 'httpdocs/mimik/mimik_uploads/' . $The_Form_Mimik_Name . '/' . $The_New_Submission_ID . '/' . $The_File_Name;
							
							mkdir_recursive('httpdocs/mimik/mimik_uploads/' . $The_Form_Mimik_Name . '/' . $The_New_Submission_ID, $THE_BASE_SERVER_PATH);
							
							if (rename($THE_BASE_SERVER_PATH . '/' . $The_Value, $THE_BASE_SERVER_PATH . '/' . $The_New_File_Path)) :
							
								@rmdir($The_Temporary_Directory);
								
							else :
							
								echo '<pre>Error: could not move file to permanent location. Check file permissions on ' . $The_New_File_Path . '</pre>';
								
							endif;
							
						endif;
						
						$The_Submitted_Value_Pairs[substr($The_Key, strlen($The_Prefix))] = str_replace(array('httpdocs/mimik/mimik_uploads/', 'mimik_secure_uploads/'), '', $The_New_File_Path);
					
					else :
					
						$The_Submitted_Value_Pairs[substr($The_Key, strlen($The_Prefix))] = $The_Value;
						
					endif;
					
				endif;
				
			endif;
			
		endforeach;
		
		$The_Submitted_Value_Pairs['create_date'] = date('Y-m-d H:i:s');
		
		$The_Submitted_Value_Pairs['modify_date'] = date('Y-m-d H:i:s');
		
		if ($_SESSION['id']) :
		
			$The_Submitted_Value_Pairs['creator_user'] = $_SESSION['id'];
			
			$The_Submitted_Value_Pairs['modifier_user'] = $_SESSION['id'];
			
		endif;
		
		$The_Database_To_Use->Update_A_Database_Row( $The_Table_Name,
											'id',
											$The_New_Submission_ID,
											$The_Submitted_Value_Pairs );
											
		if ($The_Email_Notification) :
		
			foreach ($The_Submitted_Value_Pairs as $The_Key => $The_Value) :
			
				$The_Email_Body .= $The_Key . ' : ' . $The_Value . "\n";
				
			endforeach;
			
			$The_Email_Body .= 'Groups : ' . $The_Variable_Array['submit_groups'] . "\n";
			
			$The_Email_Body .= 'Users : ' . $The_Variable_Array['submit_users'] . "\n";
			
			@mail($The_Email_Recipients, $The_Email_Subject, $The_Email_Body, "From: " . htmlentities($THE_ORGANIZATION_NAME) . " <" . $THE_SITE_EMAIL_ADDRESS . ">\n");
			
		endif;
											
		$The_Group_IDs = explode(',', $The_Variable_Array['submit_groups']);
		
		$The_Database_To_Use->Set_The_Group_Permissions_For_The_Submission($The_Form_ID, $The_New_Submission_ID, $The_Group_IDs);
	
		$The_User_IDs = explode(',', $The_Variable_Array['submit_users']);
		
		$The_Database_To_Use->Set_The_User_Permissions_For_The_Submission($The_Form_ID, $The_New_Submission_ID, $The_User_IDs);
		
		if ($The_Variable_Array['is_admin_display'] == 'true') :
		
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Display_Name . '</a>';
			echo ' &raquo; ';
			echo '<strong>Submissions</strong>';
			echo '</p></div>';
		
			echo Displays_The_MDT_For_All_Submissions_For_The_Form($The_Database_To_Use, $The_Form_ID, $The_New_Submission_ID);
			
		else :
		
			$The_Confirmation_Message = $The_Database_To_Use->Gets_The_Confirmation_Message_For_The_Form($The_Form_ID);
			
			echo '<p>' . $The_Confirmation_Message . '</p>';
			
		endif;
		
	endif;
	
	break;
	
case 'create_user' :

	$The_Settings = $The_Database_To_Use->All_Settings();
		
	$Email_Is_Login = $The_Settings['email_is_login'];
	
	$An_Error_Was_Thrown = false;

	if (isset($The_Variable_Array['submit_prefix'])) :
	
		$The_Submit_Prefix = $The_Variable_Array['submit_prefix'];
		
		$The_User_ID = $The_Variable_Array['user_id'];
		
		$The_Submitted_Value_Pairs = array();
		
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Submit_Prefix) === 0) :
			
				$The_Stripped_Key = substr($The_Key, strlen($The_Submit_Prefix));
				
				if ($The_Stripped_Key != 'password' || $The_Value != '') :
				
					$The_Submitted_Value_Pairs[$The_Stripped_Key] = $The_Value;
					
				endif;
				
			endif;
			
		endforeach;
		
		if ($Email_Is_Login) :
		
			$The_Submitted_Value_Pairs['email'] = $The_Submitted_Value_Pairs['login'];
		
		endif;
	
		if ( $The_Database_To_Use->All_Values_From_The_Database_Corresponding_To(
										'Users',
										'id',
										'login',
										$The_Submitted_Value_Pairs['login'] )) :
		
			echo The_HTML_For_The_Error_Div('Error: User ' . $The_Submitted_Value_Pairs['login'] . ' already exists.');
			
			$An_Error_Was_Thrown = true;
			
		endif;
	
		if ($The_Submitted_Value_Pairs['login'] == '') :
		
			echo The_HTML_For_The_Error_Div('Error: User must have a name.');
			
			$An_Error_Was_Thrown = true;
			
		endif;
		
		if ($The_Submitted_Value_Pairs['password'] == '') :
		
			echo The_HTML_For_The_Error_Div('Error: User must have a password.');
			
			$An_Error_Was_Thrown = true;
			
		endif;
		
		if ( $The_Submitted_Value_Pairs['email'] == '' && !$Email_Is_Login) :
		
			echo The_HTML_For_The_Error_Div('Error: User ' . $The_Submitted_Value_Pairs['login'] . ' could not be created. Email is blank.');
			
			$An_Error_Was_Thrown = true;
			
		else:
			
			if ( $The_Database_To_Use->All_Values_From_The_Database_Corresponding_To(
											'Users',
											'id',
											'email',
											$The_Submitted_Value_Pairs['email'] )) :
			
				echo The_HTML_For_The_Error_Div('Error: User ' . $The_Submitted_Value_Pairs['login'] . ' could not be created. Duplicate email (' . $The_Submitted_Value_Pairs['email'] . ').');
				
				$An_Error_Was_Thrown = true;
				
			endif;
		
		endif;
			
		if (!$An_Error_Was_Thrown) :		
			
			$The_User_ID = $The_Database_To_Use->Create_The_User($The_Submitted_Value_Pairs['login']);
			
			if ($The_User_ID !== false) :
			
				$The_Invalid_Keys = array('support_function', 'random');
				
				$The_Checkbox_Keys = array('is_blocked', 'is_admin');
				
				$The_Update_Variable_Array = array();
			
				foreach ($The_Submitted_Value_Pairs as $The_Key => $The_Value) :
				
					if (!in_array($The_Key, $The_Invalid_Keys)) :
					
						if (in_array($The_Key, $The_Checkbox_Keys)) :
						
							$The_Update_Variable_Array[$The_Key] = ($The_Value == 'on') ? '1' : '0';
							
						else :
					
							$The_Update_Variable_Array[$The_Key] = $The_Value;
							
						endif;
						
					endif;
				
				endforeach;
			
				$The_Result = $The_Database_To_Use->Update_A_User( 'id',
								$The_User_ID,
								$The_Update_Variable_Array );

				if ($The_Result) :
					
					echo The_HTML_For_The_Error_Div($The_Result);
					
				else :
				
					if (isset($The_Variable_Array['group_prefix'])) :
					
						$The_Group_Prefix = $The_Variable_Array['group_prefix'];
						
						$The_Group_IDs = array();
						
						foreach ($The_Variable_Array as $The_Key => $The_Value) :
						
							if (strpos($The_Key, $The_Group_Prefix) === 0) :
							
								$The_Stripped_Key = substr($The_Key, strlen($The_Group_Prefix));
								
								if ($The_Value == 'on') :
								
									$The_Group_IDs[] = $The_Stripped_Key;
									
								endif;
								
							endif;
							
						endforeach;
						
						$The_Result = $The_Database_To_Use->Update_Group_Associations_For_The_User($The_User_ID, $The_Group_IDs);
						
					endif;
				
				endif;
			
			else :
			
				echo The_HTML_For_The_Error_Div('Error: User ' . $The_Submitted_Value_Pairs['login'] . ' could not be created.');
				
				$An_Error_Was_Thrown = true;
			
			endif;
				
		endif;
		
	else :
	
		echo The_HTML_For_The_Error_Div('Error: No properly submitted data');
		
	endif;
	
	echo '<div class="navigation-container"><p><strong>Users</strong></p></div>';
		
	echo '<div class="table-wrapper">';
	echo Displays_The_MDT_For_All_Users($The_Database_To_Use, $The_User_ID);
	echo '</div>';
	
	break;
	
case 'create_user_custom_field' :

	if ($The_Variable_Array['field_name']) :
	
		if ($The_Variable_Array['field_type']) :
	
			$The_Display_Flag = false;
					
			if ($The_Variable_Array['display'] == 'on') :
			
				$The_Display_Flag = true;
			
			endif;
			
			$The_Required_Flag = false;
					
			if ($The_Variable_Array['is_required'] == 'on') :
			
				$The_Required_Flag = true;
			
			endif;
			
			if ($The_Variable_Array['is_modifiable_by_user'] == 'on') :
			
				$The_Modifiable_By_User_Flag = true;
			
			endif;
		
			$The_New_Field_ID = $The_Database_To_Use->Creates_A_User_Custom_Field(
										$The_Variable_Array['field_name'],
										$The_Variable_Array['field_type'],
										$The_Variable_Array['input_control_width'],
										$The_Variable_Array['character_limit'],
										$The_Display_Flag,
										$The_Variable_Array['relational_table_id'],
										$The_Variable_Array['relational_field_id_1'],
										$The_Variable_Array['relational_field_id_2'],
										$The_Variable_Array['relational_field_id_3'],
										$The_Variable_Array['start_year'],
										$The_Variable_Array['end_year'],
										$The_Required_Flag,
										$The_Modifiable_By_User_Flag,
										$The_Variable_Array['explanatory_text'],
										$The_Variable_Array['options_text'] );
			if ($The_New_Field_ID !== false) :
					
				// do nothing
				
			else :
			
				echo 'Error: Field could not be created.';
			
			endif;
			
		else :
		
			echo 'Error: Field type is blank.';
			
		endif;
		
	else :
	
		echo 'Error: Field name is blank.';
		
	endif;
	
	require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
	// note that there are no security restrictions on the forms displayed
	
	$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
								$_SESSION,
								$The_Database_To_Use,
								$The_User_Custom_Field_Callback_Function, 
								$The_User_Custom_Field_Fields, 
								$The_User_Custom_Field_Single_Row_Actions,
								$The_User_Custom_Field_Multirow_Actions,
								$The_Field_Start_Row, // the start row (use default: 0)
								$The_Field_Row_Limit, // the row limit (use default: 10)
								array(
									'display_in_management_view' => 'DESC',
									'display_order_number' => 'ASC'), // the sort information
								NULL, // the filter array
								NULL, // the focus element
								'field', // the item name to be displayed
								$The_New_Field_ID
							);
	
	$The_Data_Table->Loads_The_Rows();
	
	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
	echo ' &raquo; ';
	echo '<strong>Custom Fields</strong></p></div>';
	
	echo '<div class="table-wrapper">';
	echo $The_Data_Table->Live_Site_HTML();
	echo '</div>';
	
	break;

case 'create_view' :

	if ( $The_Database_To_Use->All_Values_From_The_Database_Corresponding_To(
									'Views',
									'id',
									'display_name',
									$The_Variable_Array['display_name'] )) :
	
		echo The_HTML_For_The_Error_Div('Error: View ' . $The_Variable_Array['display_name'] . ' already exists.');
		
		$An_Error_Was_Thrown = true;
		
	endif;

	if ($The_Variable_Array['form_id'] == '') :
	
		echo The_HTML_For_The_Error_Div('Error: View must have a form.');
		
		$An_Error_Was_Thrown = true;
		
	endif;
	
	if ($The_Variable_Array['view_type'] == 'Calendar') :
	
		if ($The_Variable_Array['title_field'] == '') :
		
			echo The_HTML_For_The_Error_Div('Error: Calendar view must have a title field.');
			
			$An_Error_Was_Thrown = true;
		
		endif;
		
		if ($The_Variable_Array['width'] == '') :
		
			$The_Variable_Array['width'] = '640';
		
		endif;
		
	endif;
	
	if ($The_Variable_Array['view_type'] == 'Gallery' && $The_Variable_Array['image_field'] == '') :
	
		echo The_HTML_For_The_Error_Div('Error: Gallery view must have an image field.');
		
		$An_Error_Was_Thrown = true;
	
	endif;
	
	if ($The_Variable_Array['view_type'] == 'Video Player') :
		
		if (!$The_Variable_Array['video_field']) :
		
			echo The_HTML_For_The_Error_Div('Error: Video Player view must have a video field.');
			
			$An_Error_Was_Thrown = true;
		
		endif;
	
		if ($The_Variable_Array['width'] == '') :
		
			$The_Variable_Array['width'] = '640';
		
		endif;
		
		if ($The_Variable_Array['height'] == '') :
		
			$The_Variable_Array['height'] = '480';
			
		endif;
		
	endif;
	
	if (!$An_Error_Was_Thrown) :
		
		$The_New_View_ID = $The_Database_To_Use->Create_The_View($The_Variable_Array['display_name']);
		
		if ($The_New_View_ID !== false) :
		
			$The_Database_To_Use->Update_The_View( 'id',
							$The_New_View_ID,
							array(
								'form_id' => $The_Variable_Array['form_id'],
								'sort_field' => $The_Variable_Array['sort_field'],
								'sort_order' => $The_Variable_Array['sort_order'],
								'type' => $The_Variable_Array['view_type'],
								'width' => $The_Variable_Array['width'],
								'height' => $The_Variable_Array['height'],
								'image_field' => $The_Variable_Array['image_field'],
								'video_field' => $The_Variable_Array['video_field'],
								'title_field' => $The_Variable_Array['title_field']));
			
			$The_Group_ID_Array = explode(',', $The_Variable_Array['groups']);
			
			if (count($The_Group_ID_Array) > 0) $The_Database_To_Use->Add_The_Group_Permissions_To_The_View($The_New_View_ID, $The_Group_ID_Array);
			
			$The_Database_To_Use->Set_The_Limit_Access_For_The_View($The_New_View_ID, $The_Variable_Array['limit_access']);
		
		else :
		
			echo The_HTML_For_The_Error_Div('Error: View ' . $The_Variable_Array['display_name'] . ' could not be created.');
			
			$An_Error_Was_Thrown = true;
		
		endif;
			
	endif;
		
	// [re-]create the template file
	$The_Template_Name = The_Mimik_Safe_Template_Name($The_Variable_Array['display_name']);

	Create_The_Template_For_The_View($The_New_View_ID, $The_Template_Name, $The_Database_To_Use);
	
	echo Displays_The_MDT_For_All_Views($The_Database_To_Use, $The_New_View_ID);

	break;

case 'delete_field' :

	$The_Form_ID = $The_Variable_Array['form_id'];
	
	$The_Field_ID_Array = array();
	
	if (is_array($The_Variable_Array['field_id'])) :
	
		$The_Field_ID_Array = $The_Variable_Array['field_id'];
	
	else :
	
		$The_Field_ID_Array[] = $The_Variable_Array['field_id'];
		
	endif;
	
	foreach ($The_Field_ID_Array as $The_Field_ID) :
	
		$The_Database_To_Use->Delete_The_Field_In_The_Table($The_Field_ID, $The_Form_ID);
		
	endforeach;
	
	require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
	
	if ($The_Form_ID == 'user') :
	
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_User_Custom_Field_Callback_Function, 
									$The_User_Custom_Field_Fields, 
									$The_User_Custom_Field_Single_Row_Actions,
									$The_User_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field' // the item name to be displayed
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
		
	elseif ($The_Form_ID == 'group') :
	
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_Group_Custom_Field_Callback_Function, 
									$The_Group_Custom_Field_Fields, 
									$The_Group_Custom_Field_Single_Row_Actions,
									$The_Group_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field' // the item name to be displayed
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
	
	else :
	
		$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);

		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Fields_For_The_Form($The_Database_To_Use, $The_Form_ID);
		echo '</div>';
	
	endif;
	
	break;

case 'delete_form' :
	
	$The_Form_ID_Array = array();
	
	if (is_array($The_Variable_Array['form_id'])) :
	
		$The_Form_ID_Array = $The_Variable_Array['form_id'];
	
	else :
	
		$The_Form_ID_Array[] = $The_Variable_Array['form_id'];
		
	endif;
	
	foreach ($The_Form_ID_Array as $The_Form_ID) :
	
		$The_SQL = "SELECT * from Tables where id = " . $The_Form_ID;
		
		$The_Form = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
		$The_Form = $The_Form[0];
		
		if ($The_Form['type'] == 'Image_Map') :
		
			require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_plugins/mapper/delete_temp.php');
			Delete_Image_Map_File($The_Form['filename'],$_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_plugins/mapper/images/');
		
		endif;
		
		$The_Database_To_Use->Delete_The_Table($The_Form_ID);
		
	endforeach;
	
	echo '<div class="navigation-container"><p>';
	echo '<strong>Forms</strong>';
	echo '</p></div>';
	
	echo Displays_The_MDT_For_All_Forms($The_Database_To_Use);
	
	/*include('http://' . $_SERVER['SERVER_NAME'] . '/mimik/mimik_support/show_data_table.php' .
								'?config_file=' . urlencode('../mimik_configuration/mdt_forms_settings.config.php') .
								'&function=Gets_The_Forms' .
								'&fields_var=The_Form_Fields' .
								'&single_row_actions_var=The_Form_Single_Row_Actions' .
								'&multirow_actions_var=The_Form_Multirow_Actions' .
								'&table_item=form' .
								'&sort_on=display_name' .
								'&sort_dir=ASC' .
								'&session_id=' . session_id()); //sessionfix*/
	
	break;

case 'delete_group' :

	$The_Group_ID_Array = array();
	
	if (is_array($The_Variable_Array['group_id'])) :
	
		$The_Group_ID_Array = $The_Variable_Array['group_id'];
		
	else :
	
		$The_Group_ID_Array[] = $The_Variable_Array['group_id'];
		
	endif;
	
	foreach ($The_Group_ID_Array as $The_Group_ID) :

		$The_Database_To_Use->Delete_The_Group($The_Group_ID);
		
	endforeach;
	
	echo '<div class="navigation-container"><p><strong>Groups</strong></p></div>';
	echo '<div class="table-wrapper">';
	echo Displays_The_MDT_For_All_Groups($The_Database_To_Use);
	echo '</div>';
	
	break;
	
case 'delete_submission' :

	$The_Submission_ID_Array = array();
	
	$The_Form_ID = $The_Variable_Array['form_id'];
	
	$The_Form_Display_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
	
	if (is_array($The_Variable_Array['submission_id'])) :
	
		$The_Submission_ID_Array = $The_Variable_Array['submission_id'];
		
	else :
	
		$The_Submission_ID_Array[] = $The_Variable_Array['submission_id'];
		
	endif;
	
	$Status_Code = 0;
	$Table_Name = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query('select table_name from Tables where id = '.$The_Form_ID.';');
	$Table_Name = $Table_Name[0]['table_name'];
	$Video_Field_Names = Flatten($The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query('select name from Fields where table_id = '.$The_Form_ID.' and type = "Video";'));
	if(!empty($Video_Field_Names)) require $THE_BASE_SERVER_PATH.'/video_utils/run_external.php';
	
	foreach ($The_Submission_ID_Array as $The_Submission_ID) :
	
		if(!empty($Video_Field_Names)){
			foreach($Video_Field_Names as $Video_Field_Name){
				$File_Name = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query('select '.$Video_Field_Name.' from '.$Table_Name.' where id = '.$The_Submission_ID.';');
				$File_Name = $File_Name[0][$Video_Field_Name];
				$Python_Output = run_external('/usr/local/bin/python '.$THE_BASE_SERVER_PATH.'/video_utils/cloud_delete.py -container '.$THE_CDN_CONTAINER_NAME.' -filename '.$File_Name,$Status_Code);
			}
		}
		
		if($Status_Code === 0){
			$The_Database_To_Use->Delete_The_Submission_In_The_Table($The_Submission_ID, $The_Form_ID);
			$The_Form_Upload_Directory = The_Mimik_Safe_Form_Name($The_Form_Display_Name) . '/' . $The_Submission_ID;
			unlink_recursive($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_uploads/' . $The_Form_Upload_Directory);
			unlink_recursive($THE_BASE_SERVER_PATH . '/mimik_secure_uploads/' . $The_Form_Upload_Directory);
		}else echo 'File could not be deleted from the cloud.  Either video_utils/cloud_delete.py failed (likely from receiving bad parameters), or there is a problem with the connection to the CloudFiles service.';
	
	endforeach;
	
	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
	echo ' &raquo; ';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Display_Name . '</a>';
	echo ' &raquo; ';
	echo '<strong>Submissions</strong>';
	echo '</p></div>';
	
	echo Displays_The_MDT_For_All_Submissions_For_The_Form($The_Database_To_Use, $The_Form_ID);
	
	break;
	
case 'delete_temp_data' :

	$The_GUID = $The_Variable_Array['submission_guid'];
	
	$The_Form_ID = $The_Variable_Array['form_id'];

	unlink_recursive($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_temp_uploads/' . $The_GUID);
	
	unlink_recursive($THE_BASE_SERVER_PATH . '/mimik_temp_secure_uploads/' . $The_GUID);
	
	$The_Form_Type = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
										'Tables',
										'type',
										'',
										'',
										'id',
										$The_Form_ID );
	
	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
	echo ' &raquo; ';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
	echo ' &raquo; ';
	echo '<strong>Submissions</strong>';
	echo '</p></div>';
									
	if ($The_Form_Type == 'Normal') :
	
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Submissions_For_The_Form($The_Database_To_Use, $The_Form_ID);
		echo '</div>';
		
	elseif ($The_Form_Type == 'Image_Map') :
	
	//	echo 'Image Map not yet integrated into tabbed navigation';
	
		$The_Submissions = $The_Database_To_Use->All_Rows_For_The_Table_With_Relational_Data_Recursive($The_Variable_Array['form_id']);
	
		include('../mimik_plugins/mapper/plugin.php');
		
	endif;
	
	break;
	
case 'delete_user' :

	$The_User_ID_Array = array();

	if (is_array($The_Variable_Array['user_id'])) :
	
		$The_User_ID_Array = $The_Variable_Array['user_id'];
		
	else :
	
		$The_User_ID_Array[] = $The_Variable_Array['user_id'];
		
	endif;
	
	foreach ($The_User_ID_Array as $The_User_ID) :

		$The_Database_To_Use->Delete_The_User($The_User_ID);
		
	endforeach;
	
	echo Displays_The_MDT_For_All_Users($The_Database_To_Use);
	
	break;
	
case 'delete_user_custom_field' :

	$The_Field_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
										'Fields',
										'display_name',
										'ASC',
										'id',
										$The_Variable_Array['field_id'] );
										
	if ($The_Field_Information['type'] == 'Dynamic Select' || $The_Field_Information['type'] == 'Dynamic Radio') :
	
		$The_Database_To_Use->Delete_The_Relationship_For_The_Target_Field($The_Variable_Array['field_id']);
	
	endif;

	$The_Database_To_Use->Delete_The_User_Custom_Field($The_Variable_Array['field_id']);
	
	$The_Fields = $The_Database_To_Use->All_User_Custom_Fields();
	
	echo The_HTML_For_The_User_Custom_Fields_Displayer($The_Fields);
	
	break;
	
case 'delete_view' :

	$The_View_ID_Array = array();
	
	if (is_array($The_Variable_Array['view_id'])) :
	
		$The_View_ID_Array = $The_Variable_Array['view_id'];
		
	else :
	
		$The_View_ID_Array[] = $The_Variable_Array['view_id'];
		
	endif;
	
	foreach ($The_View_ID_Array as $The_View_ID) :

		$The_View_Name = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
										'Views',
										'display_name',
										'',
										'',
										'id',
										$The_View_ID );
											
		$The_Template_Name = str_replace(array(' '), '_', strtolower($The_View_Name));
		
		Delete_The_Template_For_The_View($The_Template_Name);

		$The_Database_To_Use->Delete_The_View($The_View_ID);

		echo Displays_The_MDT_For_All_Views($The_Database_To_Use);

	endforeach;
	
	break;

case 'load_account_editor' :

	$The_User_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
											'Users',
											'login',
											'ASC',
											'id',
											$_SESSION['id'] );
											
	$The_User_Custom_Fields_Information = $The_Database_To_Use->All_User_Custom_Fields();
	
	echo '<div class="navigation-container"><p><strong>Account</strong></p></div>';
	
	echo The_HTML_For_The_Account_Editor($The_User_Information, $The_User_Custom_Fields_Information);
	
	break;
	
case 'load_field_editor' :

	if (in_array(1, $The_Admin_Permissions)) :
	
		$The_Field_ID = $The_Variable_Array['field_id'];
		
		$The_Form_ID = $The_Variable_Array['form_id'];

		$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
		
		$The_Field_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
											'Fields',
											'display_order_number',
											'DESC',
											'id',
											$The_Field_ID );
											
		$Is_User_Custom_Field = ($The_Form_ID == 'user');
		
		$Is_Group_Custom_Field = ($The_Form_ID == 'group');
											
		$The_Field_Display_Name = $The_Field_Information['display_name'];
											
		$The_Field_Type = $The_Field_Information['type'];
		
		$The_Field_Input_Control_Width = $The_Field_Information['input_control_width'];
		
		$The_Field_Character_Limit = $The_Field_Information['character_limit'];
											
		$The_Field_Display = $The_Field_Information['display_in_management_view'];
											
		$The_Indication_Is_Required = $The_Field_Information['is_required'];
		
		$The_Indication_Is_Modifiable_By_User = $The_Field_Information['is_modifiable_by_user'];
											
		$The_Indication_Is_Public_Facing = $The_Field_Information['is_public_facing'];

		$The_Explanatory_Text = $The_Field_Information['explanatory_text'];
											
		$The_Options_Text = $The_Field_Information['options_text'];
		
		if ($The_Field_Type == 'Dynamic Select' || $The_Field_Type == 'Dynamic Radio') :
		
			$The_SQL = 'SELECT F.table_id, R.relational_field_1, R.relational_field_2, R.relational_field_3 FROM `Fields` F INNER JOIN `Relationships` R ON F.id = R.relational_field_1 WHERE R.target_field = ' . $The_Field_ID;
			
			$The_Relational_Table_Rows = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			$The_Relational_Table_ID = $The_Relational_Table_Rows[0]['table_id'];
			
			$The_Relational_Field_1 = $The_Relational_Table_Rows[0]['relational_field_1'];
			$The_Relational_Field_2 = $The_Relational_Table_Rows[0]['relational_field_2'];
			$The_Relational_Field_3 = $The_Relational_Table_Rows[0]['relational_field_3'];
			
			$The_Tables = $The_Database_To_Use->All_Forms();
			
			if (is_array($The_Tables)) foreach ($The_Tables as $The_Table_Key => $The_Table_Value) :
			
				if ($The_Table_Value['id'] == $The_Relational_Table_ID) :
				
					$The_Tables[$The_Table_Key]['selected'] = 'true';
					
				endif;
			
			endforeach;
			
			$All_Fields = $The_Database_To_Use->All_Fields_For_The_Table($The_Relational_Table_ID);
			
			$The_Fields = array();
			
			$The_Base_Fields = array('id', 'create_date', 'modify_date', 'creator_user', 'modifier_user');
			
			if (is_array($All_Fields)) :
	
				foreach ($All_Fields as $The_Field) :
				
					if (($The_Field['type'] == 'Text' || $The_Field['type'] == 'Dynamic Select' || $The_Field['type'] == 'Dynamic Radio' || $The_Field['type'] == 'Static Select' || $The_Field['type'] == 'Static Radio') && !in_array($The_Field['name'], $The_Base_Fields)) :
					
						if ($The_Field['id'] == $The_Relational_Field_1) $The_Field['selected'] = 1;
						
						if ($The_Field['id'] == $The_Relational_Field_2) $The_Field['selected'] = 2;
						
						if ($The_Field['id'] == $The_Relational_Field_3) $The_Field['selected'] = 3;
					
						$The_Fields[] = $The_Field;
						
					endif;
				
				endforeach;
				
			endif;
			
		endif;
		
		if ($The_Field_Type == 'Date') :
	
			$The_SQL = 'SELECT start_year, end_year FROM `mmk_Dates` WHERE target_field=' . $The_Field_ID;
			
			$The_Date_Table_Row = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			$The_Start_Year = $The_Date_Table_Row[0]['start_year'];
			$The_End_Year = $The_Date_Table_Row[0]['end_year'];
	
		endif;
		
		if ($Is_User_Custom_Field) :
		
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_user_custom_fields\');return false;" href="#">Custom Fields</a>';
			echo ' &raquo; ';
			echo '<strong>' . $The_Field_Display_Name . '</strong></p></div>';
		
		elseif ($Is_Group_Custom_Field) :

			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_group_custom_fields\');return false;" href="#">Custom Fields</a>';
			echo ' &raquo; ';
			echo '<strong>' . $The_Field_Display_Name . '</strong></p></div>';
			
		else :
		
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_fields&form_id=' . $The_Form_ID . '\');return false;" href="#">Fields</a>';
			echo ' &raquo; ';
			echo '<strong>' . $The_Field_Display_Name . '</strong></p></div>';
			
		endif;
		
		echo The_HTML_For_The_Field_Editor_For_The_Field_And_The_Form(
										$The_Field_ID, 
										$The_Field_Display_Name, 
										$The_Field_Type,
										$The_Field_Input_Control_Width,
										$The_Field_Character_Limit,
										$The_Field_Display, 
										$The_Form_ID, 
										$The_Tables, 
										$The_Fields, 
										$The_Start_Year, 
										$The_End_Year, 
										$The_Indication_Is_Required, 
										$The_Indication_Is_Modifiable_By_User,
										$The_Indication_Is_Public_Facing, 
										$The_Explanatory_Text, 
										$The_Options_Text, 
										$Is_User_Custom_Field, 
										$Is_Group_Custom_Field);
	
	else :
	
		echo 'Sorry, you do not have Create/Edit Form permission.';
		
	endif;
	
	break;
	
case 'load_fields' :

	if (in_array(1, $The_Admin_Permissions)) : // Create/Edit Forms
	
		$The_Form_ID = $The_Variable_Array['form_id'];
		
		$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);

		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Fields_For_The_Form($The_Database_To_Use, $The_Form_ID);
		echo '</div>';
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Form permission.';
		
	endif;
	
	break;
	
case 'load_form_editor' :

	if (in_array(1, $The_Admin_Permissions)) : // Create/Edit Forms

		$The_Target_Div_Name = $The_Variable_Array['target_div'];
	
		$The_Groups = $The_Database_To_Use->All_Groups();
	
		$The_Form_ID = $The_Variable_Array['form_id'];
		
		$The_Permissions = $The_Database_To_Use->All_Group_Permissions_For_The_Form($The_Form_ID);
		
		if (is_array($The_Permissions)) foreach ($The_Permissions as $The_Permission_Key => $The_Permission_Value) :
		
			$The_Permissions[$The_Permission_Key] = $The_Permission_Value['id'];
		
		endforeach;

		$The_Form_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
															'Tables',
															'',
															'',
															'id',
															$The_Form_ID );
															
		$The_Form_Name = $The_Form_Information['display_name'];
		
		$The_Form_Type = $The_Form_Information['type'];
		
		$The_Form_Filename = $The_Form_Information['filename'];
		
		$The_Form_Audience = $The_Form_Information['audience'];
		
		$The_Form_Limit_Access = $The_Form_Information['limit_access'];
		
		$The_Preview_View_ID = $The_Form_Information['preview_view_id'];
		
		$The_Confirmation_Message = stripslashes($The_Form_Information['confirmation_message']);
		
		$The_Email_Notification_Flag = $The_Form_Information['email_notification_flag'];
		
		$The_Email_Recipients = $The_Form_Information['email_recipients'];
		
		$The_Group_Array = array();
		
		if (is_array($The_Groups)) :
		
			foreach ($The_Groups as $The_Group) :
		
				if (is_array($The_Permissions)) :
				
					if (array_search($The_Group['id'], $The_Permissions) !== false) $The_Group_Array[] = array(
												'id' => $The_Group['id'],
												'name' => $The_Group['name'],
												'used' => 1);
					
					else $The_Group_Array[] = array(
												'id' => $The_Group['id'],
												'name' => $The_Group['name'],
												'used' => 0);
				
				else :
				
					$The_Group_Array[] = array(
												'id' => $The_Group['id'],
												'name' => $The_Group['name'],
												'used' => 0);
					
				endif;
				
			endforeach;
			
		endif;
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; <strong>' . $The_Form_Name . '</strong></p></div>';
		
		echo The_HTML_For_The_Form_Editor(
							$The_Form_ID,
							$The_Form_Name,
							$The_Form_Type,
							$The_Form_Filename,
							$The_Form_Audience,
							$The_Form_Limit_Access,
							$The_Target_Div_Name,
							$The_Group_Array,
							$The_Confirmation_Message,
							$The_Preview_View_ID,
							$The_Email_Notification_Flag,
							$The_Email_Recipients);
							
	else :
	
		echo 'Sorry, you do not have Create/Edit Forms permission';
	
	endif;
						
	break;
	
case 'load_form_creator' :

	if (in_array(1, $The_Admin_Permissions)) : // Create/Edit Forms

		$The_Groups = $The_Database_To_Use->All_Groups();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; <strong>Create Form</strong></p></div>';
		
		echo The_HTML_For_The_Form_Creator($The_Groups);
					
	else :
	
		echo 'Sorry, you do not have Create/Edit Forms permission.';
		
	endif;
				
	break;
	
case 'load_form_menu' :
	
	switch ($The_Variable_Array['view_type']) :
	
		case 'Calendar' :
			$The_Forms_Array = $The_Database_To_Use->All_Forms_With_Date_Fields();
			break;
			
		case 'Gallery' :
			$The_Forms_Array = $The_Database_To_Use->All_Forms_With_Image_Fields();
			break;
			
		case 'Video Player' :
			$The_Forms_Array = $The_Database_To_Use->All_Forms_With_Video_Fields();
			break;
			
		case 'Normal' :
		default :
			$The_Forms_Array = $The_Database_To_Use->All_Forms();
			break;
	
	endswitch;
	
	echo The_HTML_For_The_View_Forms_Select_Menu('view_form', $The_Forms_Array);
	
	break;
	
case 'load_field_creator' :

	$The_Form_ID = $The_Variable_Array['form_id'];
	
	if (is_numeric($The_Form_ID)) :
	
		$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
	
		$The_Form_Audience = $The_Database_To_Use->Gets_The_Audience_For_The_Form($The_Form_ID);
		
		$The_Field_Is_User_Custom = false;
	
	elseif ($The_Form_ID == 'user') :
	
		$The_Form_Audience = 'Admin';
	
		$The_Field_Is_User_Custom = true;
		
	elseif ($The_Form_ID == 'group') :
	
		$The_Form_Audience = 'Admin';
	
		$The_Field_Is_Group_Custom = true;
		
	endif;
	
	if ($The_Field_Is_User_Custom) :
	
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_user_custom_fields\');return false;" href="#">User Fields</a>';
		echo ' &raquo; ';
		echo '<strong>Create User Field</strong></p></div>';

	elseif ($The_Field_Is_Group_Custom) :
	
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_group_custom_fields\');return false;" href="#">Group Fields</a>';
		echo ' &raquo; ';
		echo '<strong>Create Group Field</strong></p></div>';
	
	else :
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_fields&form_id=' . $The_Form_ID . '\');return false;" href="#">Fields</a>';
		echo ' &raquo; ';
		echo '<strong>Create Field</strong></p></div>';

	endif;
	
	echo The_HTML_For_The_Field_Creator(
						$The_Form_ID,
						$The_Form_Audience,
						$The_Field_Is_User_Custom,
						$The_Field_Is_Group_Custom);

	break;
	
case 'load_user_creator' :

	$The_Groups = $The_Database_To_Use->All_Groups();
	
	$The_User_Custom_Fields_Information = $The_Database_To_Use->All_User_Custom_Fields();
		
	if(is_array($The_User_Custom_Fields_Information)) foreach ($The_User_Custom_Fields_Information as $The_Key => $The_Field) :
	
		if ($The_Field['type'] == 'Dynamic Select' || $The_Field['type'] == 'Dynamic Radio') :
			
			$The_User_Custom_Fields_Information[$The_Key]['relational_data'] = $The_Database_To_Use->All_Relational_Data_For_The_Field($The_Field['id']);
			
		endif;
		
		if ($The_Field['type'] == 'Date') :
		
			$The_User_Custom_Fields_Information[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
		
		endif;
		
	endforeach;
	
	$The_Settings = $The_Database_To_Use->All_Settings();
	
	$Moderation_Is_Required = $The_Settings['moderation_required'];
	
	$Email_Is_Login = $The_Settings['email_is_login'];
	
	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
	echo ' &raquo; <strong>Create User</strong></p></div>';

	echo The_HTML_For_The_User_Creator(
				$The_Groups,
				$The_User_Custom_Fields_Information,
				$Moderation_Is_Required,
				$Email_Is_Login);
					
	break;
	
case 'load_group_creator' :

	$The_Admin_Permissions = $The_Database_To_Use->All_Admin_Permissions();
	
	$The_Groups = $The_Database_To_Use->All_Groups();
	
	$The_Group_Custom_Fields_Information = $The_Database_To_Use->All_Group_Custom_Fields();
	
	if (is_array($The_Group_Custom_Fields_Information)) foreach ($The_Group_Custom_Fields_Information as $The_Key => $The_Field) :
	
		if ($The_Field['type'] == 'Dynamic Select' || $The_Field['type'] == 'Dynamic Radio') :
		
			$The_Group_Custom_Fields_Information[$The_Key]['relational_data'] = $The_Database_To_Use->All_Relational_Data_For_The_Field($The_Field['id']);
			
		endif;
		
		if ($The_Field['type'] == 'Date') :
		
			$The_Group_Custom_Fields_Information[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
		
		endif;
		
	endforeach;
	
	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
	echo ' &raquo; <strong>Create Group</strong></p></div>';

	echo The_HTML_For_The_Group_Creator($The_Admin_Permissions, $The_Group_Custom_Fields_Information, $The_Groups);

	break;
	
case 'load_view_creator' :

	$The_Forms = $The_Database_To_Use->All_Forms();
	$The_Groups = $The_Database_To_Use->All_Groups();

	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_views\');return false;" href="#">Views</a>';
	echo ' &raquo; <strong>Create View</strong></p></div>';
	
	echo The_HTML_For_The_View_Creator(
			$The_Groups,
			$The_Forms);
		
	break;
	
case 'load_user_custom_fields' :

	if (in_array(5, $The_Admin_Permissions)) : // Create/Edit Users
	
		$The_Field_ID = $The_Variable_Array['field_id'];
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
		// note that there are no security restrictions on the forms displayed
		
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_User_Custom_Field_Callback_Function, 
									$The_User_Custom_Field_Fields, 
									$The_User_Custom_Field_Single_Row_Actions,
									$The_User_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field', // the item name to be displayed
									$The_Field_ID // row id to highlight
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Users permission.';
		
	endif;
	
	break;

case 'load_forms' :

	if (in_array(1, $The_Admin_Permissions) || in_array(2, $The_Admin_Permissions)) : // Create/Edit Forms, Submit to Forms

		$The_Form_ID = $The_Variable_Array['form_id'];

		if ($The_Form_ID) :
		
			$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Variable_Array['form_id']);
		
			include( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_forms_settings.config.php' );
		
			// note that there are no security restrictions on the forms displayed
			
			$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
										$_SESSION,
										$The_Database_To_Use,
										$The_Form_Callback_Function, 
										$The_Form_Fields, 
										$The_Form_Single_Row_Actions,
										$The_Form_Multirow_Actions,
										0, // start row
										1, // row limit
										NULL, // the sort information, only showing one row
										array('id' => $The_Form_ID), // the filter array
										NULL, // the focus element
										$The_Form_Table_Item_Name,
										$The_Form_ID // row id to highlight
									);
		
			$The_Data_Table->Loads_The_Rows();
			
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
			echo ' &raquo; ';
			echo '<strong>' . $The_Form_Name . '</strong></p></div>';
			
			echo '<div class="table-wrapper">';
			echo $The_Data_Table->Live_Site_HTML();
			echo '</div>';
		
		else :
		
			echo '<div class="navigation-container"><p><strong>Forms</strong></p></div>';
			
			//echo Displays_The_MDT_For_All_Forms($The_Database_To_Use);
			
			/*echo "<pre>http://" . $_SERVER['SERVER_NAME'] . "/mimik/mimik_support/show_data_table.php
								?config_file=" . urlencode('../mimik_configuration/mdt_forms_settings.config.php') . "
								&function=Gets_The_Forms
								&fields_var=The_Form_Fields
								&single_row_actions_var=The_Form_Single_Row_Actions
								&multirow_actions_var=The_Form_Multirow_Actions
								&table_item=form
								&create_parent=1
								&highlighted_rows=" . urlencode($The_Form_ID) . '</pre>';*/
			
			include('http://' . $_SERVER['SERVER_NAME'] . '/mimik/mimik_support/show_data_table.php' .
								'?config_file=' . urlencode('../mimik_configuration/mdt_forms_settings.config.php') .
								'&function=Gets_The_Forms' .
								'&fields_var=The_Form_Fields' .
								'&single_row_actions_var=The_Form_Single_Row_Actions' .
								'&multirow_actions_var=The_Form_Multirow_Actions' .
								'&table_item=form' .
								'&create_parent=1' .
								'&highlighted_rows=' . urlencode($The_Form_ID) .
								'&session_id=' . session_id()); //sessionfix
			
		endif;
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Forms or Submit to Forms permission.';
	
	endif;
	
	break;
	
case 'load_groups' :

	if (in_array(6, $The_Admin_Permissions)) : // Create/Edit Groups

		echo '<div class="navigation-container"><p><strong>Groups</strong></p></div>';
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Groups($The_Database_To_Use, $The_Variable_Array['group_id']);
		echo '</div>';
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Groups permission.';
	
	endif;
	
	break;
	
case 'load_group_custom_fields' :

	if (in_array(6, $The_Admin_Permissions)) : // Create/Edit Groups
	
		$The_Field_ID = $The_Variable_Array['field_id'];
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
		// note that there are no security restrictions on the forms displayed
		
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_Group_Custom_Field_Callback_Function, 
									$The_Group_Custom_Field_Fields, 
									$The_Group_Custom_Field_Single_Row_Actions,
									$The_Group_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field', // the item name to be displayed
									$The_Field_ID // row id to highlight
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Groups permission.';
		
	endif;
	
	break;
	
case 'load_group_editor' :

	if (in_array(6, $The_Admin_Permissions)) : // Create/Edit Groups

		$The_Group_ID = $The_Variable_Array['group_id'];

		$The_Group_Name = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
											'Groups',
											'name',
											'',
											'',
											'id',
											$The_Group_ID );
	
		$The_Group_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
											'Groups',
											'name',
											'ASC',
											'id',
											$The_Group_ID );
											
		$The_Groups = $The_Database_To_Use->All_Groups();
		
		if (is_array($The_Groups)) foreach ($The_Groups as $The_Group_Key => $The_Group_Value) :
		
			$The_Groups[$The_Group_Key]['parent_group_tree'] = $The_Database_To_Use->Gets_The_Parent_Groups_Of_The_Group($The_Group_Value['id']);
		
		endforeach;
											
		$All_Admin_Permissions = $The_Database_To_Use->All_Admin_Permissions();
		
		$The_Current_Admin_Permissions = $The_Database_To_Use->All_Admin_Permissions_For_The_Group($The_Group_ID);
		
		if (is_array($The_Admin_Permissions)) foreach ($All_Admin_Permissions as $The_Index => $The_Admin_Permission) :
		
			if (is_array($The_Current_Admin_Permissions)) foreach ($The_Current_Admin_Permissions as $The_Current_Admin_Permission) :
			
				if ($The_Admin_Permission['id'] == $The_Current_Admin_Permission['admin_permission_id']) $All_Admin_Permissions[$The_Index]['used'] = true;
			
			endforeach;
			
		endforeach;
		
		$The_Group_Custom_Fields_Information = $The_Database_To_Use->All_Group_Custom_Fields();
		
		if (is_array($The_Group_Custom_Fields_Information)) foreach ($The_Group_Custom_Fields_Information as $The_Key => $The_Field) :
		
			if ($The_Field['type'] == 'Dynamic Select' || $The_Field['type'] == 'Dynamic Radio') :
			
				$The_Group_Custom_Fields_Information[$The_Key]['relational_data'] = $The_Database_To_Use->All_Relational_Data_For_The_Field($The_Field['id']);
				
			endif;
		
			if ($The_Field['type'] == 'Date') :
			
				$The_Group_Custom_Fields_Information[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
			
			endif;
			
		endforeach;
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; <strong>' . $The_Group_Name . '</strong></p></div>';
		
		echo The_HTML_For_The_Editor_For_The_Group('groups_displayer', $The_Group_ID, $The_Group_Information, $All_Admin_Permissions, $The_Group_Custom_Fields_Information, $The_Groups);

	else :
	
		echo 'Sorry, you do not have Create/Edit Groups permission.';
	
	endif;

	break;
	
case 'load_relational_fields' :

	if (in_array(1, $The_Admin_Permissions)) : // Create/Edit Forms

		$All_Fields = $The_Database_To_Use->All_Fields_For_The_Table($The_Variable_Array['table_id']);
		
		$The_Selectable_Fields = array();
		
		$The_Submit_Tag = ($The_Variable_Array['submit_tag'] == 'undefined') ? '' : $The_Variable_Array['submit_tag'];
		
		$The_Base_Fields = array('id', 'create_date', 'modify_date', 'creator_user', 'modifier_user');
		
		if (is_array($All_Fields)) foreach ($All_Fields as $The_Field) :
		
			if (($The_Field['type'] == 'Text' || $The_Field['type'] == 'Static Select' || $The_Field['type'] == 'Static Radio' || $The_Field['type'] == 'Dynamic Select' || $The_Field_Type == 'Dynamic Radio') && !in_array($The_Field['name'], $The_Base_Fields)) :
			
				$The_Selectable_Fields[] = $The_Field;
				
			endif;
		
		endforeach;
		
		echo The_HTML_For_The_Relational_Field_Selectors($The_Selectable_Fields, $The_Submit_Tag);
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Forms permission.';
	
	endif;
	
	break;
	
case 'load_relational_tables' :

	$The_Tables = $The_Database_To_Use->All_Forms();
	
	echo The_HTML_For_The_Relational_Table_Selector($The_Tables, $The_Variable_Array['submit_tag']);
	
	break;
	
case 'load_settings_editor' :

	if (in_array(7, $The_Admin_Permissions)) : // Edit Settings

		$The_Settings = $The_Database_To_Use->All_Settings();
		
		echo The_HTML_For_The_Message_Div('', 'settings_message');
		
		echo The_HTML_For_The_Settings_Editor($The_Settings, 'settings_message');

	else :
	
		echo 'Sorry, you do not have Edit Settings permission.';
		
	endif;
	
	break;
	
case 'load_image_fields' :
	
	$The_Fields = $The_Database_To_Use->All_Fields_For_The_Table($The_Variable_Array['form_id'], 'Image');
	
	echo The_HTML_For_The_Field_Select($The_Fields, $The_Variable_Array['select_name']);
	
	break;
	
case 'load_video_fields' :

	$The_Fields = $The_Database_To_Use->All_Fields_For_The_Table($The_Variable_Array['form_id'], 'Video');
	
	echo The_HTML_For_The_Field_Select($The_Fields, $The_Variable_Array['select_name']);
	
	break;

case 'load_sort_fields' :

	if ($The_Variable_Array['view_type'] == 'Calendar') :
	
		$The_Field_Type = 'Date';
	
	else :
	
		$The_Field_Type = NULL;
		
	endif;
	
	$The_Fields = $The_Database_To_Use->All_Fields_For_The_Table($The_Variable_Array['form_id'], $The_Field_Type);
	
	echo The_HTML_For_The_Field_Select($The_Fields, $The_Variable_Array['select_name']);
	
	break;

case 'get_form_type' :

	echo $The_Database_To_Use->Gets_The_Type_Of_The_Table($The_Variable_Array['form_id']);
	
	break;
	
case 'load_submission_creator' :

	if (in_array(2, $The_Admin_Permissions)) : // Submit to Forms
	
		$The_Form_ID = $The_Variable_Array['form_id'];
		
		$The_Fields = $The_Database_To_Use->All_User_Defined_Fields_For_The_Table($The_Form_ID);
		
		if (is_array($The_Fields)) foreach ($The_Fields as $The_Key => $The_Field) :
		
			// COMPONENT : when you componentize Dynamic Select and Date Fields, pull this code
			// and the DB functions into the correct subfolder in components/
		
			if ($The_Field['type'] == 'Dynamic Select' || $The_Field['type'] == 'Dynamic Radio') :
			
				$The_Fields[$The_Key]['relational_data'] = $The_Database_To_Use->All_Relational_Data_For_The_Field($The_Field['id']);
				
			endif;
			
			if ($The_Field['type'] == 'Date') :
			
				$The_Fields[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
			
			endif;
		
		endforeach;
		
		$The_Table_Name = $The_Database_To_Use->Gets_The_Name_Of_The_Table($The_Form_ID);
		
		$The_Form_Display_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
		
		$The_Stripped_Table_Name = strtolower(str_replace('mimik_', '', $The_Table_Name));
		
		$The_New_Submission_GUID = uniqid('NEWSUBMISSION:');
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Display_Name . '</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_submissions&form_id=' . $The_Form_ID . '\');return false;" href="#">Submissions</a>';
		echo ' &raquo; ';
		echo '<strong>Create Submission</strong>';
		echo '</p></div>';
		
		The_HTML_For_The_Submission_Creator_For_The_Fields_And_The_Form($The_Fields, $The_Stripped_Table_Name, $The_Form_ID, $The_New_Submission_GUID);

	else :
	
		echo 'Sorry, you do not have Submit to Forms permission.';
	
	endif;

	break;
	
case 'load_submission_editor' :

	if (in_array(2, $The_Admin_Permissions)) : // Submit to Forms
	
		$The_Form_ID = $The_Variable_Array['form_id'];
		
		$The_Submission_ID = $The_Variable_Array['submission_id'];
		
		$The_Form_Display_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);

		$The_Fields = $The_Database_To_Use->All_User_Defined_Fields_For_The_Table($The_Form_ID);
		
		$The_Form_Is_Public = ($The_Database_To_Use->Gets_The_Audience_For_The_Form($The_Form_ID) == 'Public') ? true : false;
				
		if ($The_Form_Is_Public) :
		
			$The_Fields = array_merge($THE_GENERIC_FIELDS, $The_Fields);
			
		endif;
		
		if (is_array($The_Fields)) foreach ($The_Fields as $The_Key => $The_Field) :
		
			// COMPONENT : when you componentize Dynamic Select, Date, Group Permission,
			// and User Permission Fields, pull this code and the DB function into
			// the correct components/ subfolder
		
			switch ($The_Field['type']) :
			
			case 'Dynamic Select' :
			case 'Dynamic Radio' :
			
				$The_Fields[$The_Key]['relational_data'] = $The_Database_To_Use->All_Relational_Data_For_The_Field($The_Field['id']);
				
				break;
			
			case 'Date' :
			
				$The_Fields[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
				
				break;
				
			case 'Group Permission' :
			
				$The_Fields[$The_Key]['group_permission_data'] = $The_Database_To_Use->All_Groups();
				
				break;
				
			case 'User Permission' :
			
				$The_Fields[$The_Key]['user_permission_data'] = $The_Database_To_Use->All_Users();
				
				break;
			
			endswitch;
		
		endforeach;
		
		$The_Table_Name = $The_Database_To_Use->Gets_The_Name_Of_The_Table($The_Form_ID);
	
		$The_Upload_Subfolder = strtolower(substr($The_Table_Name, strlen('mimik_')));
		
		if (is_array($The_Fields)) foreach ($The_Fields as $The_Key => $The_Field) :
		
			switch ($The_Field['type']) :
			
			// COMPONENT : when you componentize Group Permission and User Permission Fields, 
			// pull this code and the DB function into the correct components/ subfolder
			
			case 'Group Permission' :
			
				$The_Group_Permission_Information = $The_Database_To_Use->All_Group_Permissions_For_The_Submission(
											$The_Form_ID,
											$The_Submission_ID);
											
				if (is_array($The_Group_Permission_Information)) foreach ($The_Group_Permission_Information as $The_Group_Permission) :
				
					$The_Fields[$The_Key]['value'][] = $The_Group_Permission['group_id'];
				
				endforeach;
			
				break;
				
			case 'User Permission' :
			
				$The_User_Permission_Information = $The_Database_To_Use->All_User_Permissions_For_The_Submission(
											$The_Form_ID,
											$The_Submission_ID);
			
				if (is_array($The_User_Permission_Information)) foreach ($The_User_Permission_Information as $The_User_Permission) :
				
					$The_Fields[$The_Key]['value'][] = $The_User_Permission['user_id'];
				
				endforeach;
			
				break;
				
			default :
			
				$The_Fields[$The_Key]['value'] = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
											$The_Table_Name,
											$The_Field['name'],
											'',
											'',
											'id',
											$The_Submission_ID );
											
			endswitch;
		
		endforeach;
	
		$The_Edited_Submission_GUID = uniqid('EDITSUBMISSION:');
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Display_Name . '</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_submissions&form_id=' . $The_Form_ID . '\');return false;" href="#">Submissions</a>';
		echo ' &raquo; ';
		echo '<strong>Edit Submission</strong></p></div>';
		
		echo The_HTML_For_The_Submission_Editor_For_The_Fields_And_The_Submission_And_The_Form($The_Fields, $The_Variable_Array['submission_id'], $The_Variable_Array['form_id'], $The_Upload_Subfolder, $The_Edited_Submission_GUID);
	
	else :
	
		echo 'Sorry, you do not have Submit to Forms permission.';
	
	endif;
	
	break;
	
case 'load_submissions' :

	$The_Form_ID = $The_Variable_Array['form_id'];
	
	$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
	
	$The_Form_Type = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
										'Tables',
										'type',
										'',
										'',
										'id',
										$The_Form_ID );
										
	echo '<div class="navigation-container"><p>';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
	echo ' &raquo; ';
	echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
	echo ' &raquo; ';
	echo '<strong>Submissions</strong>';
	echo '</p></div>';
	
	if ($The_Form_Type == 'Normal') :
	
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Submissions_For_The_Form($The_Database_To_Use, $The_Variable_Array['form_id']);
		echo '</div>';
		
	elseif ($The_Form_Type == 'Image_Map') :
	
	//	echo 'Image Map not yet integrated into tabbed navigation';
	
		$The_Submissions = $The_Database_To_Use->All_Rows_For_The_Table_With_Relational_Data_Recursive($The_Variable_Array['form_id']);
	
		include('../mimik_plugins/mapper/plugin.php');
		
	endif;
	
	break;
	
case 'load_image_map' :
	
	$The_Submissions = $The_Database_To_Use->All_Rows_For_The_Table_With_Relational_Data_Recursive($The_Variable_Array['form_id']);
	
	include($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_plugins/mapper/plugin.php');
	break;

case 'load_users' :

	if (in_array(5, $The_Admin_Permissions)) : // Create/Edit Users

		echo '<div class="navigation-container"><p><strong>Users</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Users($The_Database_To_Use);
		echo '</div>';
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Users permission.';
		
	endif;
	
	break;
	
case 'load_user_editor' :

	if (in_array(5, $The_Admin_Permissions)) : // Create/Edit Users

		$The_User_ID = $The_Variable_Array['user_id'];
		
		$The_Groups = $The_Database_To_Use->All_Groups();
		
		$The_User_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
											'Users',
											'login',
											'ASC',
											'id',
											$The_User_ID );
		
		$The_User_Name = $The_User_Information['login'];
		
		$The_Group_Membership_Information = $The_Database_To_Use->All_Groups_Belonged_To_By_The_User($The_User_ID);
		
		$The_User_Custom_Fields_Information = $The_Database_To_Use->All_User_Custom_Fields();
		
		if (is_array($The_User_Custom_Fields_Information)) :
		
			foreach ($The_User_Custom_Fields_Information as $The_Key => $The_Field) :
			
				if ($The_Field['type'] == 'Dynamic Select' || $The_Field['type'] == 'Dynamic Radio') :
			
					$The_User_Custom_Fields_Information[$The_Key]['relational_data'] = $The_Database_To_Use->All_Relational_Data_For_The_Field($The_Field['id']);
					
				endif;
			
				if ($The_Field['type'] == 'Date') :
				
					$The_User_Custom_Fields_Information[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
				
				endif;
				
			endforeach;
			
		endif;
		
		$The_Settings = $The_Database_To_Use->All_Settings();
		
		$Moderation_Is_Required = $The_Settings['moderation_required'];
		
		$Email_Is_Login = $The_Settings['email_is_login'];
		
		$Allow_Login_Change = $The_Settings['allow_login_change'];
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
		echo ' &raquo; <strong>' . $The_User_Name . '</strong></p></div>';
		
		echo The_HTML_For_The_Editor_For_The_User($The_User_ID, $The_User_Name, $The_Groups, $The_User_Information, $The_Group_Membership_Information, $The_User_Custom_Fields_Information, true, $Moderation_Is_Required, $Email_Is_Login, $Allow_Login_Change);
	
	else :
	
		echo 'Sorry, you do not have Create/Edit Users permission.';
		
	endif;
	
	break;

case 'load_view_editor' :

	if (in_array(3, $The_Admin_Permissions)) : // Create/Edit Views

		$The_View_Name = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
											'Views',
											'display_name',
											'',
											'',
											'id',
											$The_Variable_Array['view_id'] );
	
		$The_Forms = $The_Database_To_Use->All_Forms();
		
		$The_Groups = $The_Database_To_Use->All_Groups();
		
		$The_Permissions = $The_Database_To_Use->All_Group_Permissions_For_The_View($The_Variable_Array['view_id']);
		
		$The_View_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
											'Views',
											'display_name',
											'ASC',
											'id',
											$The_Variable_Array['view_id'] );
											
		$The_Fields = $The_Database_To_Use->All_Fields_For_The_Table($The_View_Information['form_id']);
		
		$The_Group_Array = array();
		
		if (is_array($The_Groups)) :
		
			foreach ($The_Groups as $The_Group) :
		
				if (is_array($The_Permissions)) :
				
					if (array_search($The_Group['id'], $The_Permissions) !== false) $The_Group_Array[] = array(
												'id' => $The_Group['id'],
												'name' => $The_Group['name'],
												'used' => 1);
					
					else $The_Group_Array[] = array(
												'id' => $The_Group['id'],
												'name' => $The_Group['name'],
												'used' => 0);
				
				else :
				
					$The_Group_Array[] = array(
												'id' => $The_Group['id'],
												'name' => $The_Group['name'],
												'used' => 0);
					
				endif;
				
			endforeach;
			
		endif;
		
		echo The_HTML_For_The_Editor_For_The_View($The_Variable_Array['view_id'], $The_View_Name, $The_Forms, $The_Group_Array, $The_View_Information, $The_Fields);
	
	else :
	
		echo 'Sorry, you do not have Create/Edit Views permission.';
		
	endif;

	break;
	
case 'load_view_preview' :

	$The_View_Name = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
										'Views',
										'display_name',
										'',
										'',
										'id',
										$The_Variable_Array['view_id'] );
										
	$The_Template_Name = str_replace(array(' '), '_', strtolower($The_View_Name));
	
	$The_Template_Full_File_Name = $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_templates/' . $The_Template_Name . '.template.php';
	
	echo '<strong>' . $The_Template_Full_File_Name . '&hellip;</strong><br /><br />';

	if (file_exists($The_Template_Full_File_Name)) :
	
		ob_start();
	
		include($The_Template_Full_File_Name);
	
		$The_Template_Contents = ob_get_contents();
		
		ob_end_clean();
		
	else :
	
		$The_Template_Contents = 'Sorry, this View is incomplete or there is a problem with the template file: ' . $The_Template_Full_File_Name;
		
	endif;
	
	echo $The_Template_Contents;

	break;
	
case 'load_views' :

	if (in_array(3, $The_Admin_Permissions) || in_array(4, $The_Admin_Permissions)) : // Create/Edit Views, Access Views

		if ($The_Variable_Array['view_id']) :
		
			$The_View_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_View($The_Variable_Array['view_id']);
		
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_views_settings.config.php' );
		
			// note that there are no security restrictions on the forms displayed
			
			$The_Data_Table = new A_Multifunction_Data_Table(
										$The_Database_To_Use,
										$The_View_Query, 
										$The_View_Fields, 
										$The_View_Single_Row_Actions,
										$The_View_Multirow_Actions,
										$The_View_Start_Row, // the start row (use default: 0)
										$The_View_Row_Limit, // the row limit (use default: 10)
										array('display_name' => 'ASC'), // the sort information
										array('id' => $The_Variable_Array['view_id']), // the filter array
										NULL, // the focus element
										'view', // the item name to be displayed
										$The_Variable_Array['view_id'] // row id to highlight
									);
		
			$The_Data_Table->Loads_The_Rows();
			
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_views\');return false;" href="#">Views</a>';
			echo ' &raquo; ';
			echo '<strong>' . $The_View_Name . '</strong></p></div>';
			
			echo '<div class="table-wrapper">';
			echo $The_Data_Table->Live_Site_HTML();
			echo '</div>';
		
		else :
		
			echo '<div class="navigation-container"><p><strong>Views</strong></p></div>';
			
			echo Displays_The_MDT_For_All_Views($The_Database_To_Use);
			
		endif;
		
	else :
	
		echo 'Sorry, you do not have Create/Edit Views or Access Views permission.';
	
	endif;
	
	break;
	
case 'modify_account' :

	if (isset($The_Variable_Array['submit_prefix'])) :
	
		$The_Prefix = $The_Variable_Array['submit_prefix'];
		
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Prefix) === 0) :
			
				$The_Stripped_Key = substr($The_Key, strlen($The_Prefix));
				
				if ($The_Stripped_Key == 'password' && $The_Value == '') :
				
					// do nothing
					
				else :
				
					$The_Database_To_Use->Update_The_Value_For_A_Single_Entry(
												'Users',
												'id',
												$The_Variable_Array['id'],
												$The_Stripped_Key,
												$The_Value );
				
				endif;
				
			endif;
			
		endforeach;
		
		$The_User_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
											'Users',
											'login',
											'ASC',
											'id',
											$The_Variable_Array['id'] );
											
		$The_User_Custom_Fields_Information = $The_Database_To_Use->All_User_Custom_Fields();
		
		echo The_HTML_For_The_Message_Div('Account updated');
		
		echo The_HTML_For_The_Account_Editor($The_User_Information, $The_User_Custom_Fields_Information);
		
	endif;

	break;
	
case 'modify_field' :
	
	if (isset($The_Variable_Array['submit_prefix'])) :
	
		$The_Form_ID = $The_Variable_Array['form_id'];
	
		$The_Prefix = $The_Variable_Array['submit_prefix'];
		
		$The_Field_ID = $The_Variable_Array['field_id'];
		
		$The_Field_Type = $The_Variable_Array[$The_Prefix . 'type'];
		
		$The_Database_To_Use->Delete_The_Relationship_For_The_Target_Field($The_Field_ID);
		
		$The_Database_To_Use->Delete_The_Date_For_The_Target_Field($The_Field_ID);
		
		// COMPONENT : when you componentize Dynamic Select and Date fields, 
		// pull this code into the correct components/ subfolder
		
		if ($The_Field_Type == 'Dynamic Select' || $The_Field_Type == 'Dynamic Radio') :
		
			$The_Relational_Field_1 = $The_Variable_Array[$The_Prefix . 'relational_field_1'];
			
			$The_Relational_Field_2 = $The_Variable_Array[$The_Prefix . 'relational_field_2'];
			
			$The_Relational_Field_3 = $The_Variable_Array[$The_Prefix . 'relational_field_3'];
			
			$The_Database_To_Use->Create_The_Relationship($The_Field_ID, $The_Relational_Field_1, $The_Relational_Field_2, $The_Relational_Field_3);
			
		endif;
		
		if ($The_Field_Type == 'Date') :
		
			$The_Start_Year = $The_Variable_Array[$The_Prefix . 'start_year'];
			
			$The_End_Year = $The_Variable_Array[$The_Prefix . 'end_year'];
			
			$The_Database_To_Use->Create_The_Date($The_Field_ID, $The_Start_Year, $The_End_Year);
		
		endif;
		
		$The_Relational_Values = array('relational_field_1', 'relational_field_2', 'relational_field_3');
		
		$The_Date_Values = array('start_year', 'end_year');
		
		$The_Checkbox_Values = array('display_in_management_view', 'is_required', 'is_modifiable_by_user', 'is_public_facing');
		
		$The_Submitted_Value_Pairs = array();
		
		//default all checkbox values to '0'
		foreach ($The_Checkbox_Values as $The_Checkbox_Value) :
		
			$The_Submitted_Value_Pairs[$The_Checkbox_Value] = '0';
		
		endforeach;
		
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Prefix) === 0) :
			
				$The_Stripped_Key = substr($The_Key, strlen($The_Prefix));
				
				if (!in_array($The_Stripped_Key, $The_Relational_Values) && !in_array($The_Stripped_Key, $The_Date_Values)) :
				
					if (in_array($The_Stripped_Key, $The_Checkbox_Values)) :
					
						if ($The_Value == 'on') :
						
							$The_Value = '1';
							
						else :
						
							$The_Value = '0';
							
						endif;
						
					endif;
					
					$The_Submitted_Value_Pairs[$The_Stripped_Key] = $The_Value;
				
				endif;
				
			endif;
			
		endforeach;
		
		$The_Field_Name = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
											'Fields',
											'name',
											'',
											'',
											'id',
											$The_Field_ID );

		$The_Database_To_Use->Update_A_Database_Row( 'Fields',
											'id',
											$The_Field_ID,
											$The_Submitted_Value_Pairs );
		
		$The_Field_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
											'Fields',
											'',
											'',
											'id',
											$The_Field_ID );
											
		if ($The_Form_ID == 'user') :
		
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
			$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
										$_SESSION,
										$The_Database_To_Use,
										$The_User_Custom_Field_Callback_Function, 
										$The_User_Custom_Field_Fields, 
										$The_User_Custom_Field_Single_Row_Actions,
										$The_User_Custom_Field_Multirow_Actions,
										$The_Field_Start_Row, // the start row (use default: 0)
										$The_Field_Row_Limit, // the row limit (use default: 10)
										array(
											'display_in_management_view' => 'DESC',
											'display_order_number' => 'ASC'), // the sort information
										NULL, // the filter array
										NULL, // the focus element
										'field', // the item name to be displayed
										$The_Field_ID // row id to highlight
									);
			
			$The_Data_Table->Loads_The_Rows();
			
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
			echo ' &raquo; ';
			echo '<strong>Custom Fields</strong></p></div>';
			
			echo '<div class="table-wrapper">';
			echo $The_Data_Table->Live_Site_HTML();
			echo '</div>';
		
		elseif ($The_Form_ID == 'group') :
		
			require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
			$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
										$_SESSION,
										$The_Database_To_Use,
										$The_Group_Custom_Field_Callback_Function, 
										$The_Group_Custom_Field_Fields, 
										$The_Group_Custom_Field_Single_Row_Actions,
										$The_Group_Custom_Field_Multirow_Actions,
										$The_Field_Start_Row, // the start row (use default: 0)
										$The_Field_Row_Limit, // the row limit (use default: 10)
										array(
											'display_in_management_view' => 'DESC',
											'display_order_number' => 'ASC'), // the sort information
										NULL, // the filter array
										NULL, // the focus element
										'field', // the item name to be displayed
										$The_Field_ID // row id to highlight
									);
			
			$The_Data_Table->Loads_The_Rows();
			
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
			echo ' &raquo; ';
			echo '<strong>Custom Fields</strong></p></div>';
			
			echo '<div class="table-wrapper">';
			echo $The_Data_Table->Live_Site_HTML();
			echo '</div>';
		
		else :
			
			$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
			
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
			echo ' &raquo; ';
			echo '<strong>Fields</strong></p></div>';
			
			echo Displays_The_MDT_For_All_Fields_For_The_Form($The_Database_To_Use, $The_Form_ID, $The_Field_ID);
			
		endif;
		
	endif;

	break;
	
case 'modify_form' :

	if (isset($The_Variable_Array['form_id'])) :
	
		$The_Form_ID = $The_Variable_Array['form_id'];
		
		$The_Form_Name = $The_Variable_Array['form_name'];
		
		$The_Database_To_Use->Set_The_Name_For_The_Form($The_Form_ID, $The_Form_Name);
		
		$The_Database_To_Use->Set_The_Type_For_The_Form($The_Form_ID, $The_Variable_Array['form_type']);
		
		$The_Database_To_Use->Set_The_Filename_For_The_Form($The_Form_ID, $The_Variable_Array['filename']);
		
		$The_Group_ID_Array = explode(',', $The_Variable_Array['groups']);
		
		if (count($The_Group_ID_Array) > 0) $The_Database_To_Use->Add_The_Group_Permissions_To_The_Form($The_Form_ID, $The_Group_ID_Array);
		
		$The_Database_To_Use->Set_The_Audience_For_The_Form($The_Form_ID, $The_Variable_Array['audience']);
		
		$The_Database_To_Use->Set_The_Confirmation_Message_For_The_Form($The_Form_ID, addslashes($The_Variable_Array['confirmation_message']));
		
		$The_Database_To_Use->Set_The_Limit_Access_For_The_Form($The_Form_ID, $The_Variable_Array['limit_access']);
	
		if (is_numeric($The_Variable_Array['preview_view_id'])) $The_Database_To_Use->Set_The_Preview_View_ID_For_The_Form($The_Form_ID, $The_Variable_Array['preview_view_id']);
		
		$The_Database_To_Use->Set_The_Email_Notification_For_The_Form($The_Form_ID, $The_Variable_Array['email_notification_flag']);
		
		$The_Database_To_Use->Set_The_Email_Recipients_For_The_Form($The_Form_ID, $The_Variable_Array['email_recipients']);
		
		if ($The_Form_ID === false) echo The_HTML_For_The_Error_Div('Error: Form could not be created.');
		
		//echo Displays_The_MDT_For_All_Forms($The_Database_To_Use, $The_Form_ID);
		
		echo '<div class="navigation-container"><p><strong>Forms</strong></p></div>';
		
		echo Displays_The_MDT_For_All_Forms($The_Database_To_Use, $The_Form_ID);
		
		/*include('http://' . $_SERVER['SERVER_NAME'] . '/mimik/mimik_support/show_data_table.php' .
								'?config_file=' . urlencode('../mimik_configuration/mdt_forms_settings.config.php') .
								'&function=Gets_The_Forms' .
								'&fields_var=The_Form_Fields' .
								'&single_row_actions_var=The_Form_Single_Row_Actions' .
								'&multirow_actions_var=The_Form_Multirow_Actions' .
								'&table_item=form' .
								'&highlighted_rows=' . urlencode($The_Form_ID));*/
		
	else :
	
		echo 'Error: Form name is blank.';
		
	endif;
	
	break;
	
case 'modify_group' :

	if ($The_Variable_Array['group_id'] && $The_Variable_Array['submit_prefix']) :
		$The_Group_ID = $The_Variable_Array['group_id'];
		$The_Submit_Prefix = $The_Variable_Array['submit_prefix'];
		if ($The_Group_ID !== false) :
			// do nothing
		else :
			echo The_HTML_For_The_Error_Div('Error: Group could not be created.');
		endif;
		$The_Admin_Permissions = array();
		$The_Submitted_Value_Pairs = array();
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
			if (strpos($The_Key, $The_Submit_Prefix . 'permission:') === 0) :
				if ($The_Value == 'on') :
					$The_Admin_Permissions[] = substr($The_Key, strlen($The_Submit_Prefix . 'permission:'));
				endif;
			elseif (strpos($The_Key, $The_Submit_Prefix) === 0) :
				if (substr($The_Key, strlen($The_Submit_Prefix)) == 'is_default') :
					$The_Value = ($The_Value == 'on') ? 1 : 0;
				endif;
				$The_Submitted_Value_Pairs[substr($The_Key, strlen($The_Submit_Prefix))] = $The_Value;
			endif;
		endforeach;
		$The_Submitted_Value_Pairs['modify_date'] = date('Y-m-d H:i:s');
		$The_Database_To_Use->Update_The_Group($The_Group_ID, $The_Submitted_Value_Pairs, $The_Admin_Permissions);
		echo '<div class="navigation-container"><p><strong>Groups</strong></p></div>';
		echo '<div class="table-wrapper">';
		echo Displays_The_MDT_For_All_Groups($The_Database_To_Use, $The_Group_ID);
		echo '</div>';
	else :
		echo 'Error: Submitted data was not properly formatted.';
	endif;
	break;
	
case 'modify_settings' :

	if (isset($The_Variable_Array['submit_prefix'])) :
	
		$The_Prefix = $The_Variable_Array['submit_prefix'];
		
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Prefix) === 0) :
			
				$The_Stripped_Key = substr($The_Key, strlen($The_Prefix));
				
				if (in_array($The_Stripped_Key, array(
												'moderation_required', 
												'registration_allowed', 
												'allow_account_updates_by_users', 
												'email_is_login',
												'allow_login_change')
												)) :
				
					$The_Value = ($The_Value == 'on') ? '1' : '0';
				
				endif;
				
				$The_Database_To_Use->Update_The_Value_For_A_Single_Entry(
												'Utilities',
												'utility_name',
												$The_Stripped_Key,
												'utility_value',
												$The_Value );
				
			endif;
			
		endforeach;
		
		$The_Settings = $The_Database_To_Use->All_Settings();
		
		echo The_HTML_For_The_Message_Div('Settings updated', 'settings_message');
		
		echo The_HTML_For_The_Settings_Editor($The_Settings, 'settings_message');
		
	endif;

	break;

case 'modify_submission' :

	if (isset($The_Variable_Array['submit_prefix'])) :
	
		$The_Submission_ID = $The_Variable_Array['submission_id'];
	
		$The_Prefix = $The_Variable_Array['submit_prefix'];
		
		$The_Form_ID = $The_Variable_Array['form_id'];
		
		$The_Submitted_Value_Pairs = array();
		
		$The_Table_Name = $The_Database_To_Use->Gets_The_Name_Of_The_Table($The_Form_ID);
											
		$The_Form_Display_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
		
		$The_Form_Mimik_Name = The_Mimik_Safe_Form_Name($The_Form_Display_Name);
											
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Prefix) === 0) :
			
				$The_Field_Type_Condition_Array = array();
				
				$The_Field_Type_Condition_Array['table_id'] = $The_Form_ID;
				
				$The_Field_Type_Condition_Array['name'] = substr($The_Key, strlen($The_Prefix));
			
				$The_Submitted_Value_Type = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To_Multiple_Conditions(
														'Fields',
														'type',
														$The_Field_Type_Condition_Array );
														
				if (/*$The_Value != '' && */$The_Submitted_Value_Type != '') :
				
					if (in_array($The_Submitted_Value_Type, $THE_FILE_UPLOAD_TYPES)) :
					
						$The_File_Name = substr($The_Value, strrpos($The_Value, '/') + 1);
						
						if ($The_Submitted_Value_Type == 'Secure File') :
						
							$The_Temporary_Directory = $THE_BASE_SERVER_PATH . '/' . substr($The_Value, 0, strrpos($The_Value, '/'));
						
							$The_File_Path = 'mimik_secure_uploads/' . $The_Form_Mimik_Name . '/' . $The_Submission_ID . '/' . $The_File_Name;
							
							mkdir_recursive('mimik_secure_uploads/' . $The_Form_Mimik_Name . '/' . $The_Submission_ID, $THE_BASE_SERVER_PATH);
							
							if (!rename($THE_BASE_SERVER_PATH . '/' . $The_Value, $THE_BASE_SERVER_PATH . '/' . $The_File_Path)) :
							
								echo '<pre>Error: could not move file to permanent location. Check file permissions on ' . $The_File_Path . '</pre>';

							endif;
							
						elseif ($The_Submitted_Value_Type == 'Video') :
						
							$The_Temporary_Directory = $THE_BASE_SERVER_PATH . '/' . substr($The_Value, 0, strrpos($The_Value, '/'));
						
							$The_File_Path = 'mimik_video/' . $The_Form_Mimik_Name . '___' . $The_Submission_ID . '___' . $The_File_Name;
							
							if (!rename($THE_BASE_SERVER_PATH . '/' . $The_Value, $THE_BASE_SERVER_PATH . '/' . $The_File_Path)) :
							
								echo '<pre>Error: could not move file to permanent location. Check file permissions on ' . $The_File_Path . '</pre>';

							endif;
						
						else :
						
							$The_Temporary_Directory = $THE_BASE_SERVER_PATH . '/' . substr($The_Value, 0, strrpos($The_Value, '/'));

							$The_File_Path = 'httpdocs/mimik/mimik_uploads/' . $The_Form_Mimik_Name . '/' . $The_Submission_ID . '/' . $The_File_Name;
							
							mkdir_recursive('httpdocs/mimik/mimik_uploads/' . $The_Form_Mimik_Name . '/' . $The_Submission_ID, $THE_BASE_SERVER_PATH);
							
							if (!rename($THE_BASE_SERVER_PATH . '/' . $The_Value, $THE_BASE_SERVER_PATH . '/' . $The_File_Path)) :
							
								echo '<pre>Error: could not move file to permanent location. Check file permissions on ' . $The_File_Path . '</pre>';
								
							endif;
							
						endif;
						
						$The_Submitted_Value_Pairs[substr($The_Key, strlen($The_Prefix))] = str_replace(array('httpdocs/mimik/mimik_uploads/', 'mimik_secure_uploads/'), '', $The_File_Path);
					
					else :
					
						$The_Submitted_Value_Pairs[substr($The_Key, strlen($The_Prefix))] = $The_Value;
						
					endif;
					
				endif;
				
			endif;
			
		endforeach;
		
		if($The_Temporary_Directory) unlink_recursive($The_Temporary_Directory);
		
		if ($_SESSION['id']) :
		
			$The_Submitted_Value_Pairs['modifier_user'] = $_SESSION['id'];
			
		endif;
		
		$The_Submitted_Value_Pairs['modify_date'] = date('Y-m-d H:i:s');
		
		$The_Database_To_Use->Update_A_Database_Row( $The_Table_Name,
											'id',
											$The_Submission_ID,
											$The_Submitted_Value_Pairs );
											
		$The_Group_IDs = explode(',', $The_Variable_Array['submit_groups']);
		
		$The_Database_To_Use->Set_The_Group_Permissions_For_The_Submission($The_Form_ID, $The_Submission_ID, $The_Group_IDs);
	
		$The_User_IDs = explode(',', $The_Variable_Array['submit_users']);
		
		$The_Database_To_Use->Set_The_User_Permissions_For_The_Submission($The_Form_ID, $The_Submission_ID, $The_User_IDs);
		
		if ($The_Variable_Array['is_admin_display'] == 'true') :
		
			echo '<div class="navigation-container"><p>';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
			echo ' &raquo; ';
			echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Display_Name . '</a>';
			echo ' &raquo; ';
			echo '<strong>Submissions</strong>';
			echo '</p></div>';
			
			echo Displays_The_MDT_For_All_Submissions_For_The_Form($The_Database_To_Use, $The_Form_ID, $The_Submission_ID);
			
		else :
		
			$The_Confirmation_Message = $The_Database_To_Use->Gets_The_Confirmation_Message_For_The_Form($The_Form_ID);
			
			echo $The_Confirmation_Message;
			
		endif;
		
	endif;

	break;
	
case 'modify_user' :

	if ($The_Variable_Array['submit_prefix']) :
	
		$Password_Changed = false;
	
		$The_Submit_Prefix = $The_Variable_Array['submit_prefix'];
		
		$The_User_ID = $The_Variable_Array['user_id'];
		
		$The_Submitted_Value_Pairs = array();
		
		$Password_Changed = false;
		
		foreach ($The_Variable_Array as $The_Key => $The_Value) :
		
			if (strpos($The_Key, $The_Submit_Prefix) === 0) :
			
				$The_Stripped_Key = substr($The_Key, strlen($The_Submit_Prefix));
				
				if (in_array($The_Stripped_Key, array('is_admin', 'is_blocked'))) :
				
					$The_Value = ($The_Value == 'on') ? '1' : '0';
				
				endif;
				
				if ($The_Stripped_Key == 'response_message') :
				
					$The_Response_Message = $The_Value;
					
				else :
				
					if ($The_Stripped_Key == 'password') :
					
						if ($The_Value != '') :
						
							$Password_Changed = true;
							
							$The_Submitted_Value_Pairs[$The_Stripped_Key] = $The_Value;
							
						endif;
						
					else :
				
						$The_Submitted_Value_Pairs[$The_Stripped_Key] = $The_Value;
					
					endif;
					
				endif;
				
			endif;
			
		endforeach;
		
		$The_Result = $The_Database_To_Use->Update_A_User(
											'id',
											$The_User_ID,
											$The_Submitted_Value_Pairs );
											
		if ($The_Result) :
		
			echo The_HTML_For_The_Error_Div($The_Result);
			
		else :
		
			if ($The_Variable_Array['group_prefix']) :
			
				$The_Group_Prefix = $The_Variable_Array['group_prefix'];
				
				$The_Group_IDs = array();
				
				foreach ($The_Variable_Array as $The_Key => $The_Value) :
				
					if (strpos($The_Key, $The_Group_Prefix) === 0) :
					
						$The_Stripped_Key = substr($The_Key, strlen($The_Group_Prefix));
						
						if ($The_Value == 'on') :
						
							$The_Group_IDs[] = $The_Stripped_Key;
							
						endif;
						
					endif;
					
				endforeach;
				
				$The_Result = $The_Database_To_Use->Update_Group_Associations_For_The_User($The_User_ID, $The_Group_IDs);
				
			endif;
			
			$The_Registration_Component = new A_Registration_Component($The_Database_To_Use);
			
			$The_User_Information = $The_Database_To_Use->All_User_Information($The_User_ID);
			
			$The_Login = $The_User_Information['login'];
			
			if ($The_Submitted_Value_Pairs['moderation_status'] == 'APPROVED') :
			
				$The_Registration_Component->Sends_The_Approval_Email(
															$The_Login,
															$The_Submitted_Value_Pairs['email'],
															$The_Response_Message );
														
			elseif ($The_Submitted_Value_Pairs['moderation_status'] == 'DENIED') :
			
				$The_Registration_Component->Sends_The_Denial_Email(
															$The_Login,
															$The_Submitted_Value_Pairs['email'],
															$The_Response_Message );
															
			endif;
		
		endif;
		
		if ($Password_Changed) :
			
			$The_Database_To_Use->Sets_The_Temporary_Password_Flag($The_User_ID, false);
			
		endif;
		
		if ($The_Variable_Array['admin_display'] == 1) :
		
			echo '<div class="navigation_container"><p><strong>Users</strong></p></div>';

			echo Displays_The_MDT_For_All_Users($The_Database_To_Use, $The_User_ID);

		else :

			if ($Password_Changed) $The_Message = 'Your account information has been updated. Your password was updated.';
			else $The_Message = 'Your account information has been updated. Your password was not updated.';

			echo The_HTML_For_The_Message_Div($The_Message);

			$The_User_Information = $The_Database_To_Use->First_Row_From_The_Database_Corresponding_To(
												'Users',
												'login',
												'ASC',
												'id',
												$The_User_ID );
			
			$The_User_Name = $The_User_Information['login'];
			
			$The_User_Custom_Fields_Information = $The_Database_To_Use->All_User_Custom_Fields();
			
			if (is_array($The_User_Custom_Fields_Information)) :
			
				foreach ($The_User_Custom_Fields_Information as $The_Key => $The_Field) :
				
					if ($The_Field['type'] == 'Date') :
					
						$The_User_Custom_Fields_Information[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
					
					endif;
					
				endforeach;
				
			endif;
			
			$The_Settings = $The_Database_To_Use->All_Settings();
		
			$Moderation_Is_Required = $The_Settings['moderation_required'];
			
			$Email_Is_Login = $The_Settings['email_is_login'];
			
			$Allow_Login_Change = $The_Settings['allow_login_change'];
			
			echo The_HTML_For_The_Editor_For_The_User($The_User_ID, $The_User_Name, NULL, $The_User_Information, NULL, $The_User_Custom_Fields_Information, false, $Moderation_Is_Required, $Email_Is_Login, $Allow_Login_Change);

		endif;
		
	endif;

	break;

case 'modify_view' :

	if ( !$The_Database_To_Use->All_Values_From_The_Database_Corresponding_To(
									'Views',
									'id',
									'id',
									$The_Variable_Array['view_id'] )) :
									
		echo The_HTML_For_The_Error_Div('Error: View id ' . $The_Variable_Array['view_id'] . ' could not be found.');
		
		$An_Error_Was_Thrown = true;
		
	endif;
	
	if ($The_Variable_Array['form_id'] == '') :
	
		echo The_HTML_For_The_Error_Div('Error: View must have a form.');
		
		$An_Error_Was_Thrown = true;
		
	endif;
	
	if (!$An_Error_Was_Thrown) :
	
		$The_View_ID = $The_Variable_Array['view_id'];
		
		if ($The_View_ID !== false) :
		
			$The_Database_To_Use->Update_The_View( 'id',
							$The_View_ID,
							array(
								'display_name' => $The_Variable_Array['display_name'],
								'form_id' => $The_Variable_Array['form_id'],
								'sort_field' => $The_Variable_Array['sort_field'],
								'sort_order' => $The_Variable_Array['sort_order'],
								'type' => $The_Variable_Array['view_type'],
								'width' => $The_Variable_Array['width'],
								'title_field' => $The_Variable_Array['title_field']));
			
			$The_Group_ID_Array = explode(',', $The_Variable_Array['groups']);
			
			if (count($The_Group_ID_Array) > 0) $The_Database_To_Use->Add_The_Group_Permissions_To_The_View($The_View_ID, $The_Group_ID_Array);
			
			$The_Database_To_Use->Set_The_Limit_Access_For_The_View($The_View_ID, $The_Variable_Array['limit_access']);
			
		endif;
		
	else :
	
		echo The_HTML_For_The_Error_Div('Error: View ' . $The_Variable_Array['display_name'] . ' could not be created.');
		
		$An_Error_Was_Thrown = true;
		
	endif;
		
	// [re-]create the template file
	if ($The_Variable_Array['recreate_template']) :
	
		$The_Template_Name = The_Mimik_Safe_Template_Name($The_Variable_Array['display_name']);
	
		Create_The_Template_For_The_View($The_View_ID, $The_Template_Name, $The_Database_To_Use);
		
	endif;
	
	echo Displays_The_MDT_For_All_Views($The_Database_To_Use, $The_View_ID);
		
	break;
	
case 'move_field_down' :

	$The_Field_ID = $The_Variable_Array['field_id'];
	
	$The_Form_ID = $The_Variable_Array['form_id'];
	
	$The_Database_To_Use->Increment_The_Field_Display_Position_Within_The_Table($The_Field_ID, $The_Form_ID);
	
	if ($The_Form_ID == 'user') :
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
		// note that there are no security restrictions on the forms displayed
		
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_User_Custom_Field_Callback_Function, 
									$The_User_Custom_Field_Fields, 
									$The_User_Custom_Field_Single_Row_Actions,
									$The_User_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field', // the item name to be displayed
									$The_Field_ID // row id to highlight
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
		
	elseif ($The_Form_ID == 'group') :
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
		// note that there are no security restrictions on the forms displayed
		
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_Group_Custom_Field_Callback_Function, 
									$The_Group_Custom_Field_Fields, 
									$The_Group_Custom_Field_Single_Row_Actions,
									$The_Group_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field', // the item name to be displayed
									$The_Field_ID // row id to highlight
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
	
	else :
	
		$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Fields</strong></p></div>';
	
		echo Displays_The_MDT_For_All_Fields_For_The_Form($The_Database_To_Use, $The_Form_ID, $The_Field_ID);
		
	endif;
	
	break;
	
case 'move_field_up' :

	$The_Field_ID = $The_Variable_Array['field_id'];
	
	$The_Form_ID = $The_Variable_Array['form_id'];
	
	$The_Database_To_Use->Decrement_The_Field_Display_Position_Within_The_Table($The_Field_ID, $The_Form_ID);
	
	if ($The_Form_ID == 'user') :
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
		// note that there are no security restrictions on the forms displayed
		
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_User_Custom_Field_Callback_Function, 
									$The_User_Custom_Field_Fields, 
									$The_User_Custom_Field_Single_Row_Actions,
									$The_User_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field', // the item name to be displayed
									$The_Field_ID // row id to highlight
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_users\');return false;" href="#">Users</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
		
	elseif ($The_Form_ID == 'group') :
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_fields_settings.config.php' );
		
		// note that there are no security restrictions on the forms displayed
		
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Database_To_Use,
									$The_Group_Custom_Field_Callback_Function, 
									$The_Group_Custom_Field_Fields, 
									$The_Group_Custom_Field_Single_Row_Actions,
									$The_Group_Custom_Field_Multirow_Actions,
									$The_Field_Start_Row, // the start row (use default: 0)
									$The_Field_Row_Limit, // the row limit (use default: 10)
									array(
										'display_in_management_view' => 'DESC',
										'display_order_number' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'field', // the item name to be displayed
									$The_Field_ID // row id to highlight
								);
		
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<strong>Custom Fields</strong></p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
	
	else :
	
		$The_Form_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms\');return false;" href="#">Forms</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_forms&form_id=' . $The_Form_ID . '\');return false;" href="#">' . $The_Form_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Fields</strong></p></div>';
	
		echo Displays_The_MDT_For_All_Fields_For_The_Form($The_Database_To_Use, $The_Form_ID, $The_Field_ID);
		
	endif;
	
	break;
	
case 'move_user_custom_field_down' :

	$The_Field_ID = $The_Variable_Array['field_id'];
	
	$The_Database_To_Use->Increment_The_Field_Display_Position_Within_The_Table($The_Field_ID, 'user');
	
	$The_Fields = $The_Database_To_Use->All_User_Custom_Fields();
	
	echo The_HTML_For_The_User_Custom_Fields_Displayer($The_Fields, $The_Field_ID);
	
	break;
	
case 'move_user_custom_field_up' :

	$The_Field_ID = $The_Variable_Array['field_id'];
	
	$The_Database_To_Use->Decrement_The_Field_Display_Position_Within_The_Table($The_Field_ID, 'user');
	
	$The_Fields = $The_Database_To_Use->All_User_Custom_Fields();
	
	echo The_HTML_For_The_User_Custom_Fields_Displayer($The_Fields, $The_Field_ID);
	
	break;
	
case 'remove_file' :

	$The_Form_ID = $The_Variable_Array['form_id'];
	
	$The_Submission_ID = $The_Variable_Array['submission_id'];
	
	$The_Field_Name = $The_Variable_Array['field_name'];
	
	$The_Upload_Area = $The_Variable_Array['upload_area'];
	
	$The_Field_Type = $The_Database_To_Use->Field_Type_Of_The_Field_For_The_Form($The_Form_ID, $The_Field_Name);
	
	if (!$The_Field_Type) :
		echo 'Error: file type not found.';
		break;
	endif;
	
	$The_Form_Name = $The_Database_To_Use->Gets_The_Name_Of_The_Table($The_Form_ID);
	
	$The_Form_Safe_Name = The_Mimik_Safe_Form_Name($The_Database_To_Use->Gets_The_Name_Of_The_Table($The_Form_ID));
	
	if (!$The_Form_Safe_Name) :
		echo 'Error: form name is blank.';
		break;
	endif;
	
	$The_Submission_Information = $The_Database_To_Use->Gets_The_Submission_From_The_Table($The_Form_ID, $The_Submission_ID);
	
	$The_File_Name = $The_Submission_Information[$The_Field_Name];
	
	if (!$The_File_Name) :
		echo 'Error: file name is blank.';
		break;
	endif;
	
	switch ($The_Field_Type) :
	
	case 'File' :
		if (Removes_The_File($The_File_Name)) :
			$The_Database_To_Use->Update_The_Value_For_A_Single_Entry( $The_Form_Name, 'id', $The_Submission_ID, $The_Field_Name, '' );
			echo 'File "' . $The_File_Name . '" removed; submission updated.';
		else :
			echo 'Error: file could not be removed.';
		endif;
		break;
		
	case 'Secure File' :
		if (Removes_The_Secure_File($The_File_Name)) :
			$The_Database_To_Use->Update_The_Value_For_A_Single_Entry( $The_Form_Name, 'id', $The_Submission_ID, $The_Field_Name, '' );
			echo 'Secure file "' . $The_File_Name . '" removed; submission updated.';
		else :
			echo 'Error: secure file could not be removed.';
		endif;
		break;
		
	case 'Image' :
		if (Removes_The_Image($The_File_Name)) :
			$The_Database_To_Use->Update_The_Value_For_A_Single_Entry( $The_Form_Name, 'id', $The_Submission_ID, $The_Field_Name, '' );
			echo 'Image "' . $The_File_Name . '" removed; submission updated.';
		else :
			echo 'Error: image could not be removed.';
		endif;
		break;
		
	default :
		echo 'Error: unknown field type (' . $The_Field_Type . ').';
		break;
	
	endswitch;
	
	break;
	
case 'remove_temp_file' :

	$The_File_Path = $The_Variable_Array['file_path'];
	
	$Is_Secure = $The_Variable_Array['is_secure'];
	
	if ($Is_Secure) :
		if (Removes_The_Temp_Secure_File($The_File_Path)) echo 'Temporary secure file removed.';
		else echo 'Error: temporary secure file could not be removed.';
	else :
		if (Removes_The_Temp_File($The_File_Path)) echo 'Temporary file removed.';
		else echo 'Error: temporary file could not be removed.';
	endif;
	
	break;

endswitch;

function Create_The_Template_For_The_View($The_Input_View_ID, $The_Input_Template_Name, $The_Input_Database_To_Use, $The_Input_Tip_Indication = false, $The_Input_Calendar_Event_Indication = false)
{
	$The_Database_To_Use = $The_Input_Database_To_Use;
	
	$The_Table_ID = $The_Database_To_Use->First_Value_From_The_Database_Corresponding_To(
										'Views',
										'form_id',
										'',
										'',
										'id',
										$The_Input_View_ID );
	
	$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View($The_Input_View_ID);
	
	$The_Submission = $The_Submissions[0];
	
	$The_Group_Permission_Field_ID = false;
	
	$The_User_Permission_Field_ID = false;
	
	$The_Template_Text = '';
	
	if (!$The_Input_Calendar_Event_Indication) :
		$View_Type = Flatten($The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query("select type from Views where id = " . $The_Input_View_ID));
		$View_Type = $View_Type[0];
	else :
		$View_Type = 'Normal';
	endif;
	
	if (!$The_Input_Tip_Indication) :
		$Form_Info = Flatten($The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query("select type,filename from Tables where id = " . $The_Table_ID));
		$Form_Type = $Form_Info[0];
		$Filename = $Form_Info[1];
		unset($Form_Info);
	else :
		$Form_Type = 'Normal';
	endif;
	
	$The_Template_Text = '';

	if ($Form_Type == 'Normal' || $View_Type == 'Calendar' || $View_Type == 'Gallery' || $View_Type == 'Video Player') :
	
		$The_Template_Text .= '<?php // establish the database connection' . "\n";
	
		$The_Template_Text .= 'require_once($_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_includes/a_submission.inc.php\');' . "\n";
		$The_Template_Text .= 'require_once($_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php\');' . "\n";
		
		$The_Template_Text .= '$The_Database_To_Use = new A_Mimik_Database_Interface;' . "\n";
	
		$The_Template_Text .= '$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_configuration/database_connection_info.csv\' );' . "\n";
	
		$The_Template_Text .= '$The_Database_To_Use->Establishes_A_Connection();' . "\n?>\n\n";
		
		$The_Template_Text .= '<?php // overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable' . "\n";
		
		$The_Template_Text .= 'if (is_array($_REQUEST)) :' . "\n";
		
		$The_Template_Text .= "\t" . 'foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :' . "\n";
		
		$The_Template_Text .= "\t\t" . '$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;' . "\n";
		
		$The_Template_Text .= "\t" . 'endforeach;' . "\n";
		
		$The_Template_Text .= 'endif;' . "\n";
		
		$The_Template_Text .= '?>' . "\n\n";
		
		// COMPONENT : when you componentize Group Permission and User Permission Fields,
		// pull this code into the correct components/ subfolder
		
		if (is_array($The_Submission)) foreach ($The_Submission->Local_Values_Array as $The_Field_Index => $The_Value) :
	
			if ($The_Value->Field_Information['type'] == 'Group Permission') $The_Group_Permission_Field_ID = $The_Field_Index;
			
			if ($The_Value->Field_Information['type'] == 'User Permission') $The_User_Permission_Field_ID = $The_Field_Index;
		
		endforeach;
		
		if ($The_Group_Permission_Field_ID || $The_User_Permission_Field_ID) :
		
			$The_Template_Text .= '<?php // check permission on group and/or user' . "\n";
			
			$The_Template_Text .= 'if (';
			
			if ($The_Group_Permission_Field_ID) :
			
				$The_Template_Text .= 'in_array($The_Submission->Local_Values_Array[' . $The_Group_Permission_Field_ID . ']->Data, $_SESSION[\'groups\']';
				
				if ($The_User_Permission_Field_ID) :
				
					$The_Template_Text .= ' && in_array($The_Submission->Local_Values_Array[' . $The_Group_Permission_Field_ID . ']->Data, $_SESSION[\'groups\']';
					
				endif;
				
			elseif ($The_User_Permission_Field_ID) :
			
				$The_Template_Text .= 'in_array($The_Submission->Local_Values_Array[' . $The_Group_Permission_Field_ID . ']->Data, $_SESSION[\'groups\']';
				
			endif;
			
			$The_Template_Text .= ') : ' . "\n?>\n\n";
		
		endif;
		
		$The_Template_Text .= "<?php // if the limit parameter (submitted via POST or GET) is set, get the limited data set\n";
		$The_Template_Text .= 'if (isset($The_View_Parameters[\'limit\'])) $The_Submissions = $The_Database_To_Use->Gets_The_Limited_Template_Data_For_The_View(' . $The_Input_View_ID . ', $The_View_Parameters[\'limit\'], $The_View_Parameters[\'param\']);' . "\n";
		$The_Template_Text .= "// the limit parameter is not set... if the record_id parameter is set, get only the data for that submission\n";
		$The_Template_Text .= 'else $The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(' . $The_Input_View_ID . ', $The_View_Parameters[\'record_id\'], $The_View_Parameters[\'param\']);' . "\n?>\n\n";
	
		$The_Template_Text .= '<?php // loop through the submissions' . "\n";
		$The_Template_Text .= 'if (is_array($The_Submissions)) :' . "\n";
			
		if ($View_Type == 'Calendar' || $View_Type == 'Gallery' || $View_Type == 'Video Player') :
		
			$The_Template_Text .= '$The_View = new A_View($The_Database_To_Use);' . "\n";
			$The_Template_Text .= '$The_View->Gets_The_View_Information($The_View_Parameters[\'id\']);' . "\n";
			
		endif;
		
		if ($View_Type == 'Calendar') :
			
			$The_Template_Text .= 'include($_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_plugins/calendar/plugin.php\');' . "\n";
			
			$The_Tip_Template_Name = $The_Input_Template_Name.'_calendar_tip';
			$The_Tip_View_ID = $The_Database_To_Use->Create_The_View($The_Tip_Template_Name);
			$The_Database_To_Use->Update_The_View('id', $The_Tip_View_ID, array('form_id' => $The_Table_ID));
			Create_The_Template_For_The_View($The_Tip_View_ID, $The_Tip_Template_Name, $The_Database_To_Use, true, true);
			
		elseif ($View_Type == 'Gallery') :
		
			$The_Template_Text .= 'include($_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_plugins/gallery/plugin.php\');' . "\n";
			
		elseif ($View_Type == 'Video Player') :
		
			$The_Template_Text .= 'include($_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_plugins/video/plugin.php\');' . "\n";

		elseif ($View_Type == 'Normal') :
		
			$The_Template_Text .= 'foreach ($The_Submissions as $The_Submission) :' . "\n?>\n";
			
			if ($The_Submission) : 
				
				$The_Template_Text .= $The_Submission->Gets_The_Template_Code();
				
			endif;
			
			$The_Template_Text .= '<?php' . "\n";
			
			$The_Template_Text .= 'endforeach;' . "\n";
		
		endif;
		
		$The_Template_Text .= 'else : ?>' . "\n";
		
		$The_Template_Text .= 'No records found' . "\n";
		
		$The_Template_Text .= '<?php' . "\n" . 'endif;' . "\n" . '?>' . "\n\n";
		
		if ($The_Group_Permission_Field_ID || $The_User_Permission_Field_ID) :
		
			$The_Template_Text .= '<?php' . "\n" . 'else : // current user does not have permission to view' . "\n\n";
			
			$The_Template_Text .= 'echo \'Sorry, you do not have permission to view this submission\';' . "\n\n";
			
			$The_Template_Text .= 'endif;' . "\n" . '?>' . "\n";
			
		endif;
	
	elseif ($Form_Type == 'Image_Map') :
	
		$The_Template_Text .=
		'<?
			require_once($_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_includes/sandbox_database_utilities.inc.php\');
			$db = new Database();
			$db->get_connection_info($_SERVER[\'DOCUMENT_ROOT\'].\'/mimik/mimik_configuration/database_connection_info.csv\');
			$db->connect();
			$sql = "SELECT * FROM `Tables` where id = '.$The_Table_ID.'";
			$form = $db->fetch($sql);
			$form = $form[0];
		?>
		<script type="text/javascript" src="/mimik/mimik_js/jquery.js"></script>
		<script type="text/javascript" src="/mimik/mimik_js/easing.js"></script>
		<script type="text/javascript" src="/mimik/mimik_plugins/mapper/js/custom_view.js"></script>
		<script type="text/javascript" src="/mimik/mimik_plugins/mapper/js/utilities.js"></script>
		<script type="text/javascript" src="/mimik/mimik_js/utilities.js"></script>
		<script type="text/javascript" src="/mimik/mimik_js/JSON.js"></script>
		<input type="hidden" id="is_view" value="true"/>
		<input type="hidden" id="current_form" name="current_form" value="'.$The_Table_ID.'"/>'.'
		<span id="upload_area"><img onload="loadPoints('.$The_Table_ID.'); $(window).resize(function(){clearPoints(); loadPoints('.$The_Table_ID.');});" id="map" src="/mimik/mimik_plugins/mapper/images/<?=$form[\'filename\']?>"/></span>';
		
	endif;

	$The_File_Name = $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_templates/' . strtolower($The_Input_Template_Name) . '.template.php';
	
	$The_File_Handle = fopen($The_File_Name, 'w') or die("can't open file");
	
	fwrite($The_File_Handle, $The_Template_Text);
	
	fclose($The_File_Handle);
	
	//chmod($The_File_Name, 0777);
}

function Delete_The_Template_For_The_View($The_Input_Template_Name)
{
	$The_File_Name = $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_templates/' . $The_Input_Template_Name . '.template.php';
	
	if (file_exists($The_File_Name)) :
	
		unlink($The_File_Name);
		
	endif;
}

function Load_Member_Addition($The_Input_Database_To_Use, $The_Input_Group_ID)
{
	if ($The_Input_Group_ID) :
		
		$The_Group_Name = $The_Input_Database_To_Use->Gets_The_Name_Of_The_Group($The_Input_Group_ID);
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_users_settings.config.php' );
		
		$The_User_Custom_Fields = $The_Input_Database_To_Use->All_User_Custom_Fields();
		
		$The_Full_Name_Field = '';
		
		if (is_array($The_User_Custom_Fields)) foreach ($The_User_Custom_Fields as $The_Custom_Field_Key => $The_Custom_Field_Value) :
		
			$The_User_Custom_Fields[$The_Custom_Field_Key]['filterable'] = 1;
			
			if ($The_Custom_Field_Value['name'] == 'full_name') $The_Full_Name_Field = $The_Custom_Field_Value['name'];
		
		endforeach;
		
		$The_User_Fields_To_Display = array_merge($The_User_Fields, $The_User_Custom_Fields);
		
		if ($The_Full_Name_Field) :
		
			$The_Sort_Information = array('full_name' => 'ASC');
			
		else :
		
			$The_Sort_Information = $The_Default_Sort_Information;
			
		endif;
		
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Input_Database_To_Use,
									'Gets_The_Users_Unassociated_To_The_Group', // $The_User_Query,
									$The_User_Fields_To_Display, 
									NULL, // single row actions
									$The_Add_Member_Multirow_Actions, // multirow actions
									$The_Default_Start_Row, // the start row (use default: 0)
									$The_Default_Row_Limit, // the row limit (use default: 10)
									$The_Sort_Information, // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'add_member', // the item name to be displayed
									'', // the highlighted row
									array('group_id' => $The_Input_Group_ID) // metadata
								);
	
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups&group_id=' . $The_Input_Group_ID . '\');return false;" href="#">' . $The_Group_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Add Members</strong>';
		echo '</p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
		
	else :
	
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups&group_id=' . $The_Input_Group_ID . '\');return false;" href="#">' . $The_Group_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Add Members</strong>';
		echo '</p></div>';
		
		echo '<p>Sorry, no group ID specified.</p>';
		
	endif;
	
} // Load_Member_Addition

function Load_Member_Management($The_Input_Database_To_Use, $The_Input_Group_ID)
{
	if ($The_Input_Group_ID) :
		
		$The_Group_Name = $The_Input_Database_To_Use->Gets_The_Name_Of_The_Group($The_Input_Group_ID);
	
		require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/mdt_users_settings.config.php' );
		
		$The_User_Custom_Fields = $The_Input_Database_To_Use->All_User_Custom_Fields();
		
		$The_Full_Name_Field = '';
		
		if (is_array($The_User_Custom_Fields)) foreach ($The_User_Custom_Fields as $The_Custom_Field_Key => $The_Custom_Field_Value) :
		
			$The_User_Custom_Fields[$The_Custom_Field_Key]['filterable'] = 1;
			
			if ($The_Custom_Field_Value['name'] == 'full_name') $The_Full_Name_Field = $The_Custom_Field_Value['name'];
		
		endforeach;
		
		$The_User_Fields_To_Display = array_merge($The_User_Fields, $The_User_Custom_Fields);
		
		if ($The_Full_Name_Field) :
		
			$The_Sort_Information = array('full_name' => 'ASC');
			
		else :
		
			$The_Sort_Information = $The_Default_Sort_Information;
			
		endif;
	
		$The_Data_Table = new A_Mimik_Multifunction_Data_Table(
									$_SESSION,
									$The_Input_Database_To_Use,
									'Gets_The_Users_Associated_To_The_Group', // $The_User_Query,
									$The_User_Fields_To_Display, 
									NULL, // single row actions
									$The_Remove_Member_Multirow_Actions, // multirow actions
									$The_User_Start_Row, // the start row (use default: 0)
									$The_User_Row_Limit, // the row limit (use default: 10)
									array('id' => 'ASC'), // the sort information
									NULL, // the filter array
									NULL, // the focus element
									'remove_member', // the item name to be displayed
									'', // the highlighted row
									array('group_id' => $The_Input_Group_ID) // metadata
								);
	
		$The_Data_Table->Loads_The_Rows();
		
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups&group_id=' . $The_Input_Group_ID . '\');return false;" href="#">' . $The_Group_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Manage Members</strong>';
		echo '</p></div>';
		
		echo '<div class="table-wrapper">';
		echo $The_Data_Table->Live_Site_HTML();
		echo '</div>';
	
	else :
	
		echo '<div class="navigation-container"><p>';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups\');return false;" href="#">Groups</a>';
		echo ' &raquo; ';
		echo '<a onclick="$(this).parents().filter(\'.ui-tabs-panel\').load(\'/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_groups&group_id=' . $The_Input_Group_ID . '\');return false;" href="#">' . $The_Group_Name . '</a>';
		echo ' &raquo; ';
		echo '<strong>Add Members</strong>';
		echo '</p></div>';
		
		echo '<p>Sorry, no group ID specified.</p>';
		
	endif;
	
} // Load_Member_Management

// COMPONENT : move these files into the file_field, image_field, and secure_file_field component subfolders

function Removes_The_File($The_Input_File_Name)
{
	global $THE_BASE_SERVER_PATH;
	
	return @unlink($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_uploads/' . $The_Input_File_Name);

}

function Removes_The_Image($The_Input_File_Name)
{
	global $THE_BASE_SERVER_PATH;
	
	return @unlink($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_uploads/' . $The_Input_File_Name);
	
}

function Removes_The_Secure_File($The_Input_File_Name)
{
	global $THE_BASE_SERVER_PATH;
	
	return @unlink($THE_BASE_SERVER_PATH . '/mimik_secure_uploads/' . $The_Input_File_Name);

}

// used by both Image and File
function Removes_The_Temp_File($The_Input_File_Path)
{
	global $THE_BASE_SERVER_PATH;
	
	return @unlink($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_temp_uploads/' . $The_Input_File_Path);

}

function Removes_The_Temp_Secure_File($The_Input_File_Path)
{
	global $THE_BASE_SERVER_PATH;
	
	return @unlink($THE_BASE_SERVER_PATH . '/mimik_temp_secure_uploads/' . $The_Input_File_Path);

}
?>
