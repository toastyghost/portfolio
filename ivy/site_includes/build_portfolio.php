<?
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
	
	$The_Database_To_Use = new A_Mimik_Database_Interface;
	$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
	$The_Database_To_Use->Establishes_A_Connection();
	
	$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_PORTFOLIO_ITEMS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
	
	$The_Portfolio_Items =
	$All_Portfolio_Items =
	$The_Services =
	$The_Organization_Types =
	$The_Industries = array();
	
	if (is_array($The_Submissions)) :
		foreach ($The_Submissions as $The_Submission) :
			$The_Portfolio_Item = array();
		
			$The_Portfolio_Item['id'] = $The_Submission->ID;
			$The_Portfolio_Item['create_date'] = $The_Submission->Create_Date;
			$The_Portfolio_Item['modify_date'] = $The_Submission->Modify_Date;
			$The_Portfolio_Item['creator_user'] = $The_Submission->Creator_User;
			$The_Portfolio_Item['modifier_user'] = $The_Submission->Modifier_User;
		
			if ($The_Submission->Local_Values_Array[0]->Data) $The_Portfolio_Item['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
			if ($The_Submission->Local_Values_Array[1]->Data) $The_Portfolio_Item['description'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
			if ($The_Submission->Local_Values_Array[2]->Data->ID) :
				$The_Portfolio_Item['client_id'] = $The_Submission->Local_Values_Array[2]->Data->ID;
				$The_Portfolio_Item['client_name'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
				if ($The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data) :
					$The_Portfolio_Item['client_organization_type_id'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->ID;
					$The_Portfolio_Item['client_organization_type'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
				endif;
				if ($The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data) :
					$The_Portfolio_Item['client_industry_id'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->ID;
					$The_Portfolio_Item['client_industry'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
				endif;
				$The_Portfolio_Item['client_display'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[4]->Live_Site_HTML_For_The_Data();
			endif;
			if ($The_Submission->Local_Values_Array[3]->Data) $The_Portfolio_Item['display'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();
			if ($The_Submission->Local_Values_Array[4]->Data) $The_Portfolio_Item['portfolio_image'] = $The_Submission->Local_Values_Array[4]->Live_Site_HTML_For_The_Data();
			if ($The_Submission->Local_Values_Array[11]->Data) $The_Portfolio_Item['portfolio_video'] = $The_Submission->Local_Values_Array[11]->Data->ID;
			if ($The_Submission->Local_Values_Array[5]->Data) $The_Portfolio_Item['portfolio_thumbnail'] = $The_Submission->Local_Values_Array[5]->Live_Site_HTML_For_The_Data();
			if ($The_Submission->Local_Values_Array[6]->Data->ID) :
				$The_Portfolio_Item['portfolio_service_id'] = $The_Submission->Local_Values_Array[6]->Data->ID;
				$The_Portfolio_Item['portfolio_service'] = $The_Submission->Local_Values_Array[6]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
			endif;
			
			$The_Portfolio_Item['display_on_ivy_group'] = $The_Submission->Local_Values_Array[8]->Data;
			$The_Portfolio_Item['display_on_ivy_library'] = $The_Submission->Local_Values_Array[9]->Data;
			
			if($The_Submission->Local_Values_Array[10]->Data->ID){
				if(!empty($The_Submission->Local_Values_Array[10]->Data->Local_Values_Array)){
					$The_Portfolio_Item['case_study']['id'] = $The_Submission->Local_Values_Array[10]->Data->ID;
					foreach($The_Submission->Local_Values_Array[10]->Data->Local_Values_Array as $Case_Study_Value){
						$The_Portfolio_Item['case_study'][strtr(strtolower($Case_Study_Value->Field_Information['display_name']),array(' '=>'_','.'=>'_'))] = $Case_Study_Value->Data;
					}
				}
			}
			
			if ($The_Portfolio_Item['display'] == 'Yes' && $The_Portfolio_Item['client_display'] == 'Yes' && $The_Portfolio_Item['display_on_'.str_replace(' ','_',strtolower($THE_ORGANIZATION_NAME))] == 'Yes') :
				$All_Portfolio_Items[] = $The_Portfolio_Item;
				if (($The_Service_To_Show == '' || $The_Service_To_Show == $The_Portfolio_Item['portfolio_service_id']) &&
					($The_Organization_Type_To_Show == '' || $The_Organization_Type_To_Show == $The_Portfolio_Item['client_organization_type_id']) &&
					($The_Industry_To_Show == '' || $The_Industry_To_Show == $The_Portfolio_Item['client_industry_id'])) :
					$The_Portfolio_Items[] = $The_Portfolio_Item;
				endif;
			endif;
		endforeach;
	endif;
	
	#foreach($The_Portfolio_Items as $The_Portfolio_Item) debug($The_Portfolio_Item);
	
	foreach ($All_Portfolio_Items as $The_Portfolio_Item) :
		if (($The_Organization_Type_To_Show == '' || $The_Organization_Type_To_Show == $The_Portfolio_Item['client_organization_type_id']) &&
			($The_Industry_To_Show == '' || $The_Industry_To_Show == $The_Portfolio_Item['client_industry_id']) &&
			($The_Portfolio_Item['portfolio_service'] != '')) :
			$The_Services[$The_Portfolio_Item['portfolio_service_id']] = $The_Portfolio_Item['portfolio_service'];
		endif;
		if (($The_Service_To_Show == '' || $The_Service_To_Show == $The_Portfolio_Item['portfolio_service_id']) &&
			($The_Industry_To_Show == '' || $The_Industry_To_Show == $The_Portfolio_Item['client_industry_id']) &&
			($The_Portfolio_Item['client_organization_type'] != '')) :
			$The_Organization_Types[$The_Portfolio_Item['client_organization_type_id']] = $The_Portfolio_Item['client_organization_type'];
		endif;
		if (($The_Service_To_Show == '' || $The_Service_To_Show == $The_Portfolio_Item['portfolio_service_id']) &&
			($The_Organization_Type_To_Show == '' || $The_Organization_Type_To_Show == $The_Portfolio_Item['client_organization_type_id']) &&
			($The_Portfolio_Item['client_industry'] != '')) :
			$The_Industries[$The_Portfolio_Item['client_industry_id']] = $The_Portfolio_Item['client_industry'];
		endif;
	endforeach;
	
	asort($The_Services);
	asort($The_Organization_Types);
	asort($The_Industries);
?>