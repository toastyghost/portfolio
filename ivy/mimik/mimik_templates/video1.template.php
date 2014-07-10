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
if (isset($The_View_Parameters['limit'])) $The_Submissions = $The_Database_To_Use->Gets_The_Limited_Template_Data_For_The_View(35, $The_View_Parameters['limit'], $The_View_Parameters['param']);
// the limit parameter is not set... if the record_id parameter is set, get only the data for that submission
else $The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(35, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
?>

<?php // loop through the submissions
if (is_array($The_Submissions)) :
$The_View = new A_View($The_Database_To_Use);
$The_View->Gets_The_View_Information($The_View_Parameters['id']);
include($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_plugins/video/plugin.php');
else : ?>
No records found
<?php
endif;
?>

