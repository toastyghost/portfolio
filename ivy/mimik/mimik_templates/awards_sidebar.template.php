<?
// establish the database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv');
$The_Database_To_Use->Establishes_A_Connection();

// overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;

// if the limit parameter (submitted via POST or GET) is set, get the limited data set
if (isset($The_View_Parameters['limit'])) $The_Submissions = $The_Database_To_Use->Gets_The_Limited_Template_Data_For_The_View(28, $The_View_Parameters['limit'], $The_View_Parameters['param']);
// the limit parameter is not set... if the record_id parameter is set, get only the data for that submission
else $The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(28, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

if(is_array($The_Submissions)){
	?>
	<div id="awards_sidebar_outer">
		<div id="awards_sidebar_inner">
			<h2>awards <span class="ampersand">&amp;</span> recognition</h2>
			<div id="awards_list_frame">
				<ul id="awards_list">
				<?
					$The_Award = array();
					shuffle($The_Submissions);
					$The_Submissions = array_slice(array_filter($The_Submissions,'completeness'),0,3);
					foreach ($The_Submissions as $The_Submission){
						$The_Award['title'] = $The_Submission->Local_Values_Array[1]->Data;
						$The_Award['project_id'] = $The_Submission->Local_Values_Array[2]->Data->ID;
						$The_Award['project_title'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data;
						$The_Award['project_client_name'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data;
						?>
						<li>
							<h3><?=$The_Award['title']?></h3>
							<em>for <a href="/portfolio#portfolio_item_<?=$The_Award['project_id']?>"><?=$The_Award['project_title']?></a>, <?=$The_Award['project_client_name']?></em>
						</li>
						<?
					}
				?>
				</ul>
			</div>
		</div>
	</div><?
	if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false) echo '<br/><br/><br/><br/><br/><br/><br/>';
}else echo 'No records found';

function completeness($var){
	return($var->Local_Values_Array[1]->Data && $var->Local_Values_Array[2]->Data->ID && $var->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data && $var->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data && $var->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Local_Values_Array[4]->Data === 'Yes' && $var->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data === 'Yes' && $var->Local_Values_Array[2]->Data->Local_Values_Array[8]->Data === 'Yes');
}
?>