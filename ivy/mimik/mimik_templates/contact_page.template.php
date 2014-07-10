<?php // establish the database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();
?>

<?php // overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;
?>

<?php // if the limit parameter (submitted via POST or GET) is set, get the limited data set
if (isset($The_View_Parameters['limit'])) $The_Submissions = $The_Database_To_Use->Gets_The_Limited_Template_Data_For_The_View(27, $The_View_Parameters['limit'], $The_View_Parameters['param']);
// the limit parameter is not set... if the record_id parameter is set, get only the data for that submission
else $The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(27, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
$The_Submission = $The_Submissions[0];

$Left_Column_Content = $The_Submission->Local_Values_Array[0]->Data;
$Map_Embed_Code = $The_Submission->Local_Values_Array[1]->Data;
$Form_ID = $The_Submission->Local_Values_Array[2]->Data;
$Form_Heading = $The_Submission->Local_Values_Array[3]->Data;
$Form_Intro_Text = $The_Submission->Local_Values_Array[4]->Data;
$Project_Planner_File = $The_Submission->Local_Values_Array[5]->Data;
?>

<!--[if IE 7]><table cellspacing="0" cellpadding="0"><tr><td valign="top"><![endif]-->
<div id="contact_leftcol_wrapper" class="contact_column_wrapper"><?=$Left_Column_Content?></div>
<!--[if IE 7]></td><td valign="top"><![endif]-->
<div id="contact_rightcol_wrapper" class="contact_column_wrapper">
	<em>Charlottesville Location</em>
	<div id="map_wrapper"><?=$Map_Embed_Code?></div>
	<div id="form_heading_wrapper">
		<h1><?=$Form_Heading?></h1>
		<p><?=$Form_Intro_Text?></p>
		<? /*<p>If you are inquiring about a potential web project, we ask that you download and complete a copy of our <a href="<?=$Project_Planner_File?>">project planner</a>, and upload it back to us using the form below.</p>
		<p>Understanding your expectations early on expedites the information-gathering phase of the project, ensuring that we can get the ball rolling on your new site as quickly as possible.</p>*/?>
	</div>
	<div id="contact_form_wrapper">
		<div id="contact_form_inner">
			<?
				$The_View_Parameters['id'] = $Form_ID;
				include($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_live_data/form_data.php');
			?>
		</div>
	</div>
</div>
<!--[if IE 7]></td></tr></table><![endif]-->