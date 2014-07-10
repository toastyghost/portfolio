<?php 
// overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST) && !is_array($The_View_Parameters)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;

if (!$The_View_Parameters['record_id'] && !$The_View_Parameters['sp_id']) return;

if (!function_exists("Print_The_Menu")) :
	
	function Print_The_Menu($The_View_Parameters) {
		// establish the database connection
		global $THE_BASE_SERVER_PATH;
		require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/the_system_settings.config.php');

		require_once($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_templates/menu_items.template.php');

		$The_Database_To_Use = new A_Mimik_Database_Interface;
		$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
		$The_Database_To_Use->Establishes_A_Connection();
		
		$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_MENU_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
		$The_Submission = $The_Submissions[0];
		
		$The_Menu = array();
		
		// loop through the submissions
		$The_Menu['id'] = $The_Submission->ID;
		$The_Menu['create_date'] = $The_Submission->Create_Date;
		$The_Menu['modify_date'] = $The_Submission->Modify_Date;
		$The_Menu['creator_user'] = $The_Submission->Creator_User;
		$The_Menu['modifier_user'] = $The_Submission->Modifier_User;
		
		if ($The_Submission->Local_Values_Array[0]->Data) $The_Menu['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[1]->Data) $The_Menu['pretty_url_name'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[2]->Data) $The_Menu['site'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
		
		if (isset($The_View_Parameters['!ul_class'])) $The_Menu['!ul_class'] = $The_View_Parameters['!ul_class'];
		if (isset($The_View_Parameters['!ul_id'])) $The_Menu['!ul_id'] = $The_View_Parameters['!ul_id'];
		if (isset($The_View_Parameters['!a_individual_id'])) $The_Menu['!a_individual_id'] = $The_View_Parameters['!a_individual_id'];
		if (isset($The_View_Parameters['!li_individual_id'])) $The_Menu['!li_individual_id'] = $The_View_Parameters['!li_individual_id'];
		if (isset($The_View_Parameters['!div_container'])) $The_Menu['!div_container'] = $The_View_Parameters['!div_container'];
		if (isset($The_View_Parameters['!case'])) $The_Menu['!case'] = $The_View_Parameters['!case'];
		if (isset($The_View_Parameters['!show_subtitle'])) $The_Menu['!show_subtitle'] = $The_View_Parameters['!show_subtitle'];
		if (isset($The_View_Parameters['!center_spacer'])) $The_Menu['!center_spacer'] = $The_View_Parameters['!center_spacer'];
		if (isset($The_View_Parameters['!no_list'])) $The_Menu['!no_list'] = $The_View_Parameters['!no_list'];
		if (isset($The_View_Parameters['!is_modular'])) $The_Menu['!is_modular'] = $The_View_Parameters['!is_modular'];
		if (isset($The_View_Parameters['!highlight'])) $The_Menu['!highlight'] = $The_View_Parameters['!highlight'];
		if (isset($The_View_Parameters['!parent_menu_pretty_url_name'])) $The_Menu['!parent_menu_pretty_url_name'] = $The_View_Parameters['!parent_menu_pretty_url_name'];
		
		// menu items
		$The_Temp_View_Parameters = $The_View_Parameters;
		unset($The_View_Parameters);
		$The_View_Parameters['id'] = THE_MENU_ITEMS_VIEW_ID;
		$The_View_Parameters['param']['menu'] = $The_Menu['id'];
		$The_Temp_Menu = $The_Menu;
		if ($The_Menu['!ul_class']) $The_View_Parameters['!li_id_prefix'] = $The_Menu['!ul_class'];
		if ($The_Menu['pretty_url_name']) $The_View_Parameters['!menu_pretty_url_name'] = $The_Menu['!parent_menu_pretty_url_name'] . $The_Menu['pretty_url_name'];
		if ($The_Menu['!a_individual_id']) $The_View_Parameters['!a_individual_id'] = $The_Menu['!a_individual_id'];
		if ($The_Menu['!li_individual_id']) $The_View_Parameters['!li_individual_id'] = $The_Menu['!li_individual_id'];
		if ($The_Menu['!div_container']) $The_View_Parameters['!div_container'] = $The_Menu['!div_container'];
		if ($The_Menu['!case']) $The_View_Parameters['!case'] = $The_Menu['!case'];
		if ($The_Menu['!show_subtitle']) $The_View_Parameters['!show_subtitle'] = $The_Menu['!show_subtitle'];
		if ($The_Menu['!center_spacer']) $The_View_Parameters['!center_spacer'] = $The_Menu['!center_spacer'];
		if ($The_Menu['!no_list']) $The_View_Parameters['!no_list'] = $The_Menu['!no_list'];
		unset($The_Menu);
		ob_start();
		Print_The_Menu_Items($The_View_Parameters);
		$The_Menu_HTML = ob_get_contents();
		ob_end_clean();
		$The_Menu = $The_Temp_Menu;
		$The_View_Parameters = $The_Temp_View_Parameters;
		?>
		<?php
		if ($The_Menu['!no_list'] != true) : ?>
		<ul<?php if ($The_Menu['!ul_class']) echo ' class="' . $The_Menu['!ul_class'] . '"'; ?><?php if ($The_Menu['!ul_id']) echo ' id="' . $The_Menu['!ul_id'] . '"'; ?>>
		<?php
			if ($The_Menu['!highlight']) : ?>
				<div id="highlight"></div>
		<?php
			endif;
		endif; 
		
		echo $The_Menu_HTML;
		
		if ($The_Menu['!no_list'] != true) : ?>
		</ul>
		<?php
		endif;
		$The_Menu_Path_Is_Open = false;
	} // end function
endif; // conditionally declare function

?>