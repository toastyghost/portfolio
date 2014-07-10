<?php
require_once('../mimik_configuration/the_system_settings.config.php');
require_once('../mimik_includes/a_submission.inc.php');
require_once('../mimik_includes/ivy-mimik_database_utilities.inc.php');
require_once('../mimik_includes/ivy-mimik_html_utilities.inc.php');
require_once('../../site_includes/site_html_utilities.inc.php');
$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( '../mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();

$The_Form_ID = $_GET['id'];

// standalone page information
if ($The_Form_ID == 452) :
	$The_Standalone_Page_Information = $The_Database_To_Use->Gets_The_Limited_Template_Data_For_The_View(194, 1/*limit*/, array('id'=>15)/*page id*/);
endif;

include('../../site_includes/header.php');
?>
		<div class="shadow_left"> 
			<div class="shadow_right"> 
				<div class="main"> 
					<div class="mainnav_bottom"></div> 
					<div class="content_wrapper" style="display:block;"> 
						<div class="dots_wrapper"> 
							<div class="dots"></div> 
						</div> 
						<div class="sidenav_wrapper"> 
				<?php // Menu if not text-only
				if (!$The_Indication_To_Be_Text_Only) :
					// if there is a menu_id in the URL, force the use of that menu
					if ($The_Menu_ID != '') :
						$The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=138&page_id=' . $_REQUEST['record_id'] . '&record_id=' . $The_Menu_ID . '&force_menu=1';
					// otherwise, use the "default" menu for this landing page
					else :
						$The_Standalone_Menu_Association_ID = $The_Standalone_Page_Information[0]->Local_Values_Array[2]->Data->ID;
                        $The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=138&page_id=' . $_REQUEST['record_id'] . '&page_type=standalone&record_id=' . $The_Standalone_Menu_Association_ID;
					endif;
					include($The_Include);
				endif;
				?>
						<div class="sidebar_dots"></div>
				<?php // Module if not text-only
				$The_Marketing_Module_ID = $The_Standalone_Page_Information[0]->Local_Values_Array[3]->Data->ID;
				if (!$The_Indication_To_Be_Text_Only) :
					$The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=122&record_id=' . $The_Marketing_Module_ID;
					include($The_Include);
				endif;
				?>
					</div> 
						<div class="content_tertiary">
				<?php
if ($The_Form_ID != '') :

	echo '<input type="hidden" name="current_form" id="current_form" value="' . $The_Form_ID . '" />';

	$The_Form_Name = $The_Database_To_Use->Gets_The_Name_Of_The_Table($The_Form_ID);
	
	$The_Form_Display_Name = $The_Database_To_Use->Gets_The_Display_Name_Of_The_Table($The_Form_ID);
										
	$The_Fields = $The_Database_To_Use->All_User_Defined_Fields_For_The_Table($The_Form_ID);
		
	if (is_array($The_Fields)) :
	
		foreach ($The_Fields as $The_Key => $The_Field) :
		
			if ($The_Field['is_public_facing'] == '0') :
			
				unset($The_Fields[$The_Key]);
				
			else :
			
				if ($The_Field['type'] == 'Dynamic Select') :
				
					$The_Fields[$The_Key]['relational_data'] = $The_Database_To_Use->All_Relational_Data_For_The_Field($The_Field['id']);
					
				endif;
				
				if ($The_Field['type'] == 'Date') :
				
					$The_Fields[$The_Key]['date_data'] = $The_Database_To_Use->All_Date_Data_For_The_Field($The_Field['id']);
				
				endif;
				
			endif;
		
		endforeach;
			
		$The_Stripped_Table_Name = strtolower(str_replace('mimik_', '', $The_Form_Name));
		
		$The_New_Submission_GUID = uniqid('NEWSUBMISSION:');
		
		echo '<h2>' . $The_Form_Display_Name . '</h2>';
		
		echo '<div style="margin-top:12px;" id="public_form_submission_creator">';
		The_HTML_For_The_Submission_Creator_For_The_Fields_And_The_Form($The_Fields, $The_Stripped_Table_Name, $The_Form_ID, $The_New_Submission_GUID, false);
		echo '</div>';
		
	else :
	
		echo '<em>This Form has no Fields defined that can be modified in the public display.</em>';
		
	endif;
	
else :

	echo 'please specify an id';

endif;
?>
					</div> 
						
						<?php // Module if text-only
		if ($The_Indication_To_Be_Text_Only) :
			$The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=122&limit=1';
			include($The_Include);
		endif;
		?>
		
		<?php // main nav if text-only
		if ($The_Indication_To_Be_Text_Only) :
			if ($The_Menu_ID != '') :
				$The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=138&page_id=' . $_REQUEST['record_id'] . '&record_id=' . $The_Menu_ID . '&force_menu=1';
			// otherwise, use the "default" menu for this landing page
			else :
				$The_Standalone_Menu_Association_ID = $The_Standalone_Page_Information[0]->Local_Values_Array[2]->Data->ID;
				$The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=138&page_id=' . $_REQUEST['record_id'] . '&page_type=standalone&record_id=' . $The_Standalone_Menu_Association_ID;
			endif;
			include($The_Include);
		endif;
		?>
		
		<?php // Menu if text-only
		if ($The_Indication_To_Be_Text_Only) :
			// if there is a menu_id in the URL, force the use of that menu
			if ($The_Menu_ID != '') :
				$The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=138&page_id=' . $_REQUEST['record_id'] . '&record_id=' . $The_Menu_ID . '&force_menu=1';
			// otherwise, use the "default" menu for this landing page
			else :
				$The_Standalone_Menu_Association_ID = $The_Standalone_Page_Information[0]->Local_Values_Array[2]->Data->ID;
				$The_Include = $THE_BASE_URL . '/mimik/mimik_live_data/view.php?id=138&page_id=' . $_REQUEST['record_id'] . '&page_type=standalone&record_id=' . $The_Standalone_Menu_Association_ID;
			endif;
			include($The_Include);
		endif;
	
		include('../../site_includes/footer.php');
?>