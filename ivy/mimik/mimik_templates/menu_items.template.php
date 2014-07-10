<?php 
// overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST) && !is_array($The_View_Parameters)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;

if (!$The_View_Parameters['record_id']) $The_View_Parameters['record_id'] = NULL;

function Print_The_Menu_Items($The_View_Parameters) {
	
	// establish the database connection
	global $THE_BASE_SERVER_PATH;
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/the_system_settings.config.php');
	$The_Database_To_Use = new A_Mimik_Database_Interface;
	$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
	$The_Database_To_Use->Establishes_A_Connection();

	$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_MENU_ITEMS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
	
	global $The_Menu_Path_Is_Open;
	$There_Is_An_Open_Menu_Path = false;
	
	$The_Menu_Items = array();
	
	// loop through the submissions
	if (is_array($The_Submissions)) :
	foreach ($The_Submissions as $The_Submission) :
		$The_Menu_Item = array();
	
		$The_Menu_Item['id'] = $The_Submission->ID;
		$The_Menu_Item['create_date'] = $The_Submission->Create_Date;
		$The_Menu_Item['modify_date'] = $The_Submission->Modify_Date;
		$The_Menu_Item['creator_user'] = $The_Submission->Creator_User;
		$The_Menu_Item['modifier_user'] = $The_Submission->Modifier_User;
	
		if ($The_Submission->Local_Values_Array[0]->Data) :
			$The_Menu_Item['menu_id'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	//		$The_Menu_Item['menu_pretty_url_name'] = $The_Submission->Local_Values_Array[0]->Data->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
		endif;
		if ($The_Submission->Local_Values_Array[1]->Data) $The_Menu_Item['title'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[2]->Data->ID) $The_Menu_Item['home_page'] = $The_Submission->Local_Values_Array[2]->Data->ID;
		if ($The_Menu_Item['home_page']) :
			$The_Menu_Item['home_page_url'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[5]->Live_Site_HTML_For_The_Data();
		endif;
		if ($The_Submission->Local_Values_Array[3]->Data) $The_Menu_Item['landing_page_id'] = $The_Submission->Local_Values_Array[3]->Data->ID;
		if ($The_Submission->Local_Values_Array[4]->Data) $The_Menu_Item['interior_page_id'] = $The_Submission->Local_Values_Array[4]->Data->ID;
		if ($The_Submission->Local_Values_Array[5]->Data) $The_Menu_Item['standalone_page_id'] = $The_Submission->Local_Values_Array[5]->Data->ID;
		if ($The_Submission->Local_Values_Array[6]->Data) $The_Menu_Item['standalone_page_record_id'] = $The_Submission->Local_Values_Array[6]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[7]->Data) $The_Menu_Item['url'] = $The_Submission->Local_Values_Array[7]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[8]->Data) $The_Menu_Item['open_in_new_window'] = $The_Submission->Local_Values_Array[8]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[9]->Data) $The_Menu_Item['pretty_url_name'] = $The_Submission->Local_Values_Array[9]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[10]->Data) $The_Menu_Item['order_number'] = $The_Submission->Local_Values_Array[10]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[11]->Data) $The_Menu_Item['display_as_module'] = $The_Submission->Local_Values_Array[11]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[12]->Data) $The_Menu_Item['module_content'] = $The_Submission->Local_Values_Array[12]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[13]->Data) $The_Menu_Item['module_image'] = $The_Submission->Local_Values_Array[13]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[14]->Data) $The_Menu_Item['subtitle'] = $The_Submission->Local_Values_Array[14]->Live_Site_HTML_For_The_Data();
		if ($The_Submission->Local_Values_Array[15]->Data) $The_Menu_Item['additional_parameters'] = $The_Submission->Local_Values_Array[15]->Live_Site_HTML_For_The_Data();
	
		if ($The_View_Parameters['!link_text']) $The_Menu_Item['title'] = $The_View_Parameters['!link_text'];
		if ($The_View_Parameters['!menu_pretty_url_name']) $The_Menu_Item['!menu_pretty_url_name'] = $The_View_Parameters['!menu_pretty_url_name'] . '/';
		if ($The_View_Parameters['!a_individual_id']) $The_Menu_Item['!a_id'] = strtolower($The_Menu_Item['title']) . '_link';
		if ($The_View_Parameters['!li_individual_id']) $The_Menu_Item['!li_id'] = strtolower($The_Menu_Item['title']);
		if ($The_View_Parameters['!div_container']) $The_Menu_Item['!div_container'] = $The_View_Parameters['!div_container'];
		if ($The_View_Parameters['!case']) $The_Menu_Item['!case'] = $The_View_Parameters['!case'];
		if ($The_View_Parameters['!show_subtitle']) $The_Menu_Item['!show_subtitle'] = $The_View_Parameters['!show_subtitle'];
		if ($The_View_Parameters['!center_spacer']) $The_Menu_Item['!center_spacer'] = $The_View_Parameters['!center_spacer'];
		if ($The_View_Parameters['!no_list']) $The_Menu_Item['!no_list'] = $The_View_Parameters['!no_list'];
	
		$The_Menu_Item['!classes'] = array();
		
		global $The_Landing_Page;
		global $The_Interior_Page;
		global $The_Standalone_Page;
		
		$The_Submenus = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_MENU_VIEW_ID, NULL, array('parent_menu_item' => $The_Menu_Item['id']));
	
		if (count($The_Submenus) > 0) :
			$The_Submenu = $The_Submenus[0];
		else :
			$The_Submenu = NULL;
		endif;
		
		// submenu
		ob_start();
		if (count($The_Submenu) > 0) :
			$The_Temp_View_Parameters = $The_View_Parameters;
			unset($The_View_Parameters);
			$The_Temp_Menu_Item = $The_Menu_Item;
			$The_Temp_Menu_Items = $The_Menu_Items;
			$The_Temp_Submenu = $The_Submenu;
			$The_Temp_Menu_Item_Index = $The_Menu_Item_Index;
			$The_View_Parameters['id'] = THE_MENU_VIEW_ID;
			$The_View_Parameters['record_id'] = $The_Submenu->ID;
			$The_View_Parameters['!ul_class'] = 'tertiary_nav';
			if ($The_Menu_Item['!menu_pretty_url_name']) :
				$The_View_Parameters['!parent_menu_pretty_url_name'] = $The_Menu_Item['!menu_pretty_url_name'];
			endif;
			Print_The_Menu($The_View_Parameters);
			$The_Menu_Item = $The_Temp_Menu_Item;
			$The_Menu_Items = $The_Temp_Menu_Items;
			$The_Submenu = $The_Temp_Submenu;
			$The_Menu_Item_Index = $The_Temp_Menu_Item_Index;
			$The_View_Parameters = $The_Temp_View_Parameters;
		endif;
		$The_Menu_Item['submenu_html'] = ob_get_contents();
		ob_end_clean();
		
		if ($The_Menu_Path_Is_Open) :
			$The_Menu_Item['!classes'][] = 'open';
		else :
			if (($The_Menu_Item['landing_page_id'] == $The_Landing_Page['id'] && $The_Landing_Page['id']) && 
					(!$The_Menu_Item['additional_parameters'] || ($The_Menu_Item['additional_parameters'] == The_String_Of_Additional_Parameters($_GET)))) :
				$The_Menu_Item['!classes'][] = 'active';
				$The_Menu_Item['!classes'][] = 'open';
				$There_Is_An_Open_Menu_Path = true;
				$The_Menu_Path_Is_Open = true;
			elseif (($The_Menu_Item['interior_page_id'] == $The_Interior_Page['id'] && $The_Interior_Page['id']) &&
					(!$The_Menu_Item['additional_parameters'] || ($The_Menu_Item['additional_parameters'] == The_String_Of_Additional_Parameters($_GET)))) :
				$The_Menu_Item['!classes'][] = 'active';
				$The_Menu_Item['!classes'][] = 'open';
				$There_Is_An_Open_Menu_Path = true;
				$The_Menu_Path_Is_Open = true;
			elseif (($The_Menu_Item['standalone_page_id'] == $The_Standalone_Page['id'] && $The_Standalone_Page['id']) && 
					(!$The_Menu_Item['additional_parameters'] || ($The_Menu_Item['additional_parameters'] == The_String_Of_Additional_Parameters($_GET)))) :
				$The_Menu_Item['!classes'][] = 'active';
				$The_Menu_Item['!classes'][] = 'open';
				$There_Is_An_Open_Menu_Path = true;
				$The_Menu_Path_Is_Open = true;
			endif;
		endif;
	
		$The_Menu_Items[] = $The_Menu_Item;
	
	endforeach;
	else : 
		echo 'No records found';
	endif;
	
	$The_Total_Menu_Items = count($The_Menu_Items);
	
	foreach ($The_Menu_Items as $The_Menu_Item_Index => $The_Menu_Item) :
	
		if ($The_Menu_Item_Index === ($The_Total_Menu_Items / 2) && $The_Menu_Item['!center_spacer']) :
		
			echo '<li id="center_spacer"></li>';
		
		endif;
		
		$The_Classes = $The_Menu_Item['!classes'];
	
		if ($The_Menu_Item_Index == 0) $The_Classes[] = 'first';
		if ($The_Menu_Item['display_as_module'] == 'Yes') $The_Classes[] = 'module';
		if (($The_Menu_Item_Index + 1) == $The_Total_Menu_Items) $The_Classes[] = 'last';
		
		if ($The_Menu_Item['!no_list'] != true) :
	
			echo '<li';
			if (count($The_Classes) > 0) echo ' class="' . implode(' ', $The_Classes) . '"';
			if ($The_View_Parameters['!li_id_prefix']) echo ' id="' . $The_View_Parameters['!li_id_prefix'] . '_item_' . $The_Menu_Item_Index . '"';
			elseif ($The_Menu_Item['!li_id']) echo ' id="' . $The_Menu_Item['!li_id'] . '"';
			echo '>';
			
		endif;
		
		if ($The_Menu_Item['display_as_module'] == 'Yes') :
		
			echo '<h3><a href="';
		
			if ($The_Menu_Item['home_page_url']) :
			
				echo $The_Menu_Item['home_page_url'] . $The_Menu_Item['additional_parameters'];
				
			elseif ($The_Menu_Item['url']) :
			
				echo $The_Menu_Item['url'] . $The_Menu_Item['additional_parameters'];	
			
			elseif ($The_Menu_Item['pretty_url_name']) :
			
				echo $The_Menu_Item['!menu_pretty_url_name'] . $The_Menu_Item['pretty_url_name'] . $The_Menu_Item['additional_parameters'];
				
			else :
			
				echo '#';
				
			endif;
			
			echo '"';
			
			if ($The_Menu_Item['!a_id']) :
	
				echo ' id="' . $The_Menu_Item['!a_id'] . '"';
			
			endif;
			
			if ($The_Menu_Item['open_in_new_window'] == 'Yes') :
			
				echo ' target="_blank"';
			
			endif;
			
			echo '>';
		
			echo $The_Menu_Item['title'];
			
			if ($The_Menu_Item['subtitle'] && $The_Menu_Item['!show_subtitle']) :
			
				echo '<br/><span class="link_subtitle">';
				
				echo $The_Menu_Item['subtitle'];
				
				echo '</span>';
			
			endif;
			
			echo '</a></h3>';
			
			echo '<img src="/mimik/mimik_uploads/' . $The_Menu_Item['module_image'] . '" width="144" height="103" alt="' . $The_Menu_Item['title'] . '"/>';
		
			echo '<p class="module_content">' . $The_Menu_Item['module_content'] . '</p>';
		
		else :
		
			if ($The_Menu_Item['!div_container']) :
			
				echo '<div id="' . strtolower($The_Menu_Item['title']) . '">';
				
			endif;
		
			echo '<a href="';
		
			if ($The_Menu_Item['home_page_url']) :
			
				echo $The_Menu_Item['home_page_url'] . $The_Menu_Item['additional_parameters'];
				
			elseif ($The_Menu_Item['url']) :
			
				echo $The_Menu_Item['url'] . $The_Menu_Item['additional_parameters'];	
			
			elseif ($The_Menu_Item['pretty_url_name']) :
			
				echo '/' . $The_Menu_Item['!menu_pretty_url_name'] . $The_Menu_Item['pretty_url_name'] . $The_Menu_Item['additional_parameters'];
				
			else :
			
				echo '#';
				
			endif;
			
			echo '"';
			
			if ($The_Menu_Item['!a_class']) :
			
				echo ' class="' . $The_Menu_Item['!a_class'] . '"';
				
			endif;
			
			if ($The_Menu_Item['!a_id']) :
	
				echo ' id="' . $The_Menu_Item['!a_id'] . '"';
			
			endif;
			
			if ($The_Menu_Item['open_in_new_window'] == 'Yes') :
			
				echo ' target="_blank"';
			
			endif;
			
			echo '><span class="bg">';
			
			if ($The_Menu_Item['module_image']) :
			
				echo '<img src="/mimik/mimik_uploads/' . $The_Menu_Item['module_image'] . '" alt="' . $The_Menu_Item['title'] . '" />';
			
			else :
			
				echo '<span>';
				
				if(is_array($The_Menu_Item['!classes'])){
					$wrap_with_h1 = in_array('active',$The_Menu_Item['!classes']);
					if($wrap_with_h1) echo '<h1 style="display:inline;font-weight:normal;">';
				}
				
				if ($The_Menu_Item['!case'] == 'lower') :
				
					echo strtolower($The_Menu_Item['title']);
					
				elseif ($The_Menu_Item['!case'] == 'upper') :
					
					echo strtoupper($The_Menu_Item['title']);
				
				else :
				
					echo $The_Menu_Item['title'];
					
				endif;
				
				if($wrap_with_h1) echo '</h1>';
				
				echo '</span>';
				
			endif;
			
			if ($The_Menu_Item['subtitle'] && $The_Menu_Item['!show_subtitle']) :
			
				echo '<br /><span class="link_subtitle">';
				
				echo $The_Menu_Item['subtitle'];
				
				echo '</span>';
			
			endif;
			
			echo '</span></a>';
			
			if ($The_Menu_Item['!div_container']) :
			
				echo '</div>';
				
			endif;
		
		endif;
		
		echo $The_Menu_Item['submenu_html'];
		
		if ($The_Menu_Item['!no_list'] != true) :
		
			echo '</li>';
			
		endif;

		$The_Menu_Path_Is_Open = false;
	
	endforeach;
	
	$The_Menu_Path_Is_Open = $There_Is_An_Open_Menu_Path;
	
} // end function
	?>