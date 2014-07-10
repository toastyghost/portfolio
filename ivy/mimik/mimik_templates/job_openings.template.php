<?php // establish the database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();

// overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST) && !is_array($The_View_Parameters)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_JOB_OPENINGS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

$The_Job_Openings = array();

// loop through the submissions
if (is_array($The_Submissions)) foreach ($The_Submissions as $The_Submission) :
	
	$The_Job_Opening = array();

	$The_Job_Opening['id'] = $The_Submission->ID;
	$The_Job_Opening['create_date'] = $The_Submission->Create_Date;
	$The_Job_Opening['modify_date'] = $The_Submission->Modify_Date;
	$The_Job_Opening['creator_user'] = $The_Submission->Creator_User;
	$The_Job_Opening['modifier_user'] = $The_Submission->Modifier_User;
	
	if ($The_Submission->Local_Values_Array[0]->Data) $The_Job_Opening['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[1]->Data) $The_Job_Opening['description'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();

	$The_Job_Openings[] = $The_Job_Opening;

endforeach;

foreach ($The_Job_Openings as $The_Job_Opening) :
?>
	<div class="job_opening">
		<h2><?=$The_Job_Opening['title']?></h2>
		<p><?=$The_Job_Opening['description']?></p>
	</div>
<?php
endforeach; ?>