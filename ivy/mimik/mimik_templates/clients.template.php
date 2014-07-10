<?
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
	
	$The_Database_To_Use = new A_Mimik_Database_Interface;
	$The_Database_To_Use->Will_Connect_Using_The_Information_In($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv');
	$The_Database_To_Use->Establishes_A_Connection();
	
	if(is_array($_REQUEST)){
		foreach($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value){
			$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
		}
	}
	if(isset($The_View_Parameters['limit'])) $The_Submissions = $The_Database_To_Use->Gets_The_Limited_Template_Data_For_The_View(19, $The_View_Parameters['limit'],$The_View_Parameters['param']);
	else $The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(19,$The_View_Parameters['record_id'],$The_View_Parameters['param']);
	
	if(is_array($The_Submissions)){
		foreach ($The_Submissions as $The_Submission){
			if($The_Submission->Local_Values_Array[4]->Data === 'Yes'){
				$id = $The_Submission->ID;
				$clients[$id] = $The_Submission->Local_Values_Array[0]->Data;
				/*$clients[$id]['name'] = $The_Submission->Local_Values_Array[0]->Data;
				$clients[$id]['description'] = $The_Submission->Local_Values_Array[1]->Data;
				$clients[$id]['organization_type'] = $The_Submission->Local_Values_Array[2]->Data;
				$clients[$id]['industry'] = $The_Submission->Local_Values_Array[3]->Data->Local_Values_Array[0]->Data;
				$clients[$id]['image'] = $The_Submission->Local_Values_Array[5]->Data;*/
			}
		}
		unset($The_Submissions);
		sort($clients);
		
		$halfway = (integer)round(count($clients)/2);
		echo '<div class="clients"><h2>Clients</h2><p>What follows is a list of all our clients, present and past.  Due to its length, convenient show/hide functionality has been added.</p><span class="js_hidden"><ul><span class="client_list_column">';
		$i=0;
		foreach($clients as $client){
			echo '<li>',$client,'</li>';
			if($i===$halfway) echo '</span><span class="client_list_column">';
			++$i;
		}
		echo '</span></ul><br style="clear:both"/></span></div>';
	}
?>

