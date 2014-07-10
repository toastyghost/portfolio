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

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_INTERNS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

$The_Active_Interns = array();

$The_Alumni_Interns = array();

// loop through the submissions
if (is_array($The_Submissions)) :
foreach ($The_Submissions as $The_Submission) :
	$The_Intern = array();

	$The_Intern['id'] = $The_Submission->ID;
	$The_Intern['create_date'] = $The_Submission->Create_Date;
	$The_Intern['modify_date'] = $The_Submission->Modify_Date;
	$The_Intern['creator_user'] = $The_Submission->Creator_User;
	$The_Intern['modifier_user'] = $The_Submission->Modifier_User;

	if ($The_Submission->Local_Values_Array[0]->Data) $The_Intern['last_name'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[1]->Data) $The_Intern['first_name'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[2]->Data) $The_Intern['photo'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[3]->Data) $The_Intern['bio'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[4]->Data) $The_Intern['start_date'] = $The_Submission->Local_Values_Array[4]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[5]->Data) $The_Intern['end_date'] = $The_Submission->Local_Values_Array[5]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[6]->Data) $The_Intern['start_date2'] = $The_Submission->Local_Values_Array[6]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[7]->Data) $The_Intern['end_date2'] = $The_Submission->Local_Values_Array[7]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[8]->Data) $The_Intern['is_active'] = $The_Submission->Local_Values_Array[8]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[9]->Data) $The_Intern['term'] = $The_Submission->Local_Values_Array[9]->Live_Site_HTML_For_The_Data();

	if ($The_Intern['is_active'] == 'Yes') :
	
		$The_Active_Interns[] = $The_Intern;
		
	else :
	
		$The_Alumni_Interns[] = $The_Intern;
		
	endif;

endforeach;

usort($The_Active_Interns, 'Compare_Interns');

usort($The_Alumni_Interns, 'Compare_Interns');

else : 
	echo 'No records found';
endif; ?>

<?php
if(!empty($The_Active_Interns)):
	echo '<h2 class="term">Current Interns</h2>';
	foreach ($The_Active_Interns as $The_Intern_Index => $The_Intern) :
	?>
		<div class="active_intern_container">
			<?php if ($The_Intern['photo']) : ?>
				<img class="intern_photo" src="/mimik/mimik_uploads/<?=$The_Intern['photo']?>" alt="<?=$The_Intern['first_name'] . ' ' . $The_Intern['last_name']?>" />
			<?php endif; ?>
			<div class="intern_bio">
				<h3><?=strtoupper($The_Intern['first_name'] . ' ' . $The_Intern['last_name'])?></h3>
				<?=$The_Intern['bio']?>
			</div>
		</div>
	<?php
	endforeach;
endif;
?>

<?php
$The_Displayed_Terms = array();
foreach ($The_Alumni_Interns as $The_Intern_Index => $The_Intern) :
	if (!in_array($The_Intern['term'], $The_Displayed_Terms)) :
		$The_Displayed_Terms[] = $The_Intern['term'];
		echo '<h2 class="term">' . $The_Intern['term'] . '</h2>';
	endif;
?>
	<div class="alumni_intern_container">
		<?php if ($The_Intern['photo']) : ?>
			<img class="intern_photo" src="/mimik/mimik_uploads/<?=$The_Intern['photo']?>" alt="<?=$The_Intern['first_name'] . ' ' . $The_Intern['last_name']?>" />
		<?php endif; ?>
		<div class="intern_bio">
			<h3><?=strtoupper($The_Intern['first_name'] . ' ' . $The_Intern['last_name'])?></h3>
			<?=$The_Intern['bio']?>
		</div>
	</div>
<?php
endforeach; 

function Compare_Interns($a, $b)
{
	if ($a['term'] == $b['term']) :
		if ($a['last_name'] == $b['last_name']) :
			return ($a['first_name'] > $b['first_name']) ? 1 : -1;
		else :
			return ($a['last_name'] > $b['last_name']) ? 1 : -1;
		endif; 
	else :
		return ($a['term'] < $b['term']) ? 1 : -1;
	endif;
}
?>