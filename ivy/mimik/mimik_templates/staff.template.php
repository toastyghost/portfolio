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

if (isset($_GET['partners'])) :
	$Partners_Only = true;
elseif (isset($_GET['members'])) :
	$Staff_Members_Only = true;
endif;

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_STAFF_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

$The_Staff_Members = array();

$The_Alumni_Interns = array();

// loop through the submissions
if (is_array($The_Submissions)) :
foreach ($The_Submissions as $The_Submission) :
	$The_Staff = array();

	$The_Staff['id'] = $The_Submission->ID;
	$The_Staff['create_date'] = $The_Submission->Create_Date;
	$The_Staff['modify_date'] = $The_Submission->Modify_Date;
	$The_Staff['creator_user'] = $The_Submission->Creator_User;
	$The_Staff['modifier_user'] = $The_Submission->Modifier_User;

	if ($The_Submission->Local_Values_Array[0]->Data) $The_Staff['last_name'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[1]->Data) $The_Staff['first_name'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[2]->Data) $The_Staff['partner'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[3]->Data) $The_Staff['title'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[4]->Data) $The_Staff['email'] = $The_Submission->Local_Values_Array[4]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[5]->Data) $The_Staff['phone'] = $The_Submission->Local_Values_Array[5]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[6]->Data) $The_Staff['bio'] = $The_Submission->Local_Values_Array[6]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[7]->Data) $The_Staff['photo'] = $The_Submission->Local_Values_Array[7]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[8]->Data) $The_Staff['library_bio'] = $The_Submission->Local_Values_Array[8]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[9]->Data) $The_Staff['library_email'] = $The_Submission->Local_Values_Array[9]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[10]->Data) $The_Staff['display_in_ivy_group'] = $The_Submission->Local_Values_Array[10]->Live_Site_HTML_For_The_Data() == 'Yes';
	if ($The_Submission->Local_Values_Array[11]->Data) $The_Staff['display_in_ivy_library'] = $The_Submission->Local_Values_Array[11]->Live_Site_HTML_For_The_Data() == 'Yes';

	if (strpos($_SERVER['SCRIPT_URI'], 'ivygroup.com') !== false) :
		$Ivy_Group = true;
	else :
		$Ivy_Library = true;
	endif;
	
	if ($Ivy_Library) :
		$The_Staff['bio'] = $The_Staff['library_bio'];
		$The_Staff['email'] = $The_Staff['library_email'];
	endif;
	
	if (($Ivy_Group && $The_Staff['display_in_ivy_group']) || ($Ivy_Library && $The_Staff['display_in_ivy_library'])) :
		if ($The_Staff['partner'] == 'Yes') :
			$The_Partners[] = $The_Staff;
		else :
			$The_Staff_Members[] = $The_Staff;
		endif;
	endif;
	
endforeach;

else : 
	echo 'No records found';
endif; ?>
<div id="staff_shadow_top"></div>
<div class="staff_directory">
	<?php
	if (!$Staff_Members_Only) :
		$i=0;
		$Partner_Count = count($The_Partners);
		foreach ($The_Partners as $The_Partner_Index => $The_Partner) :
		?>
			<div class="staff_container partner_container">
				<?php if ($The_Partner['photo']) : ?>
					<img src="/mimik/mimik_uploads/<?=$The_Partner['photo']?>" alt="<?=$The_Partner['first_name'] . ' ' . $The_Partner['last_name']?>" width="100" height="120" align="left"/>
				<?php endif; ?>
				<h3><?=strtoupper($The_Partner['first_name'] . ' ' . $The_Partner['last_name'])?></h3>
				<em><?=$The_Partner['title']?></em>
				<div class="contact_info">
					<?=munge($The_Partner['email'])?><br />
					<?=$The_Partner['phone']?>
				</div>
				<div class="bio"><?=$The_Partner['bio']?></div>
			</div>
		<?php
			++$i;
			if($i<$Partner_Count) echo '<div class="staff_separator"></div>';
		endforeach;
	endif; // !Staff_Members_Only
	
	if (!$Partners_Only) :
		$i=0;
		$Staff_Count = count($The_Staff_Members);
		foreach ($The_Staff_Members as $The_Staff_Index => $The_Staff) :
		?>
			<div class="staff_container">
				<?php if ($The_Staff['photo']) : ?>
					<img src="/mimik/mimik_uploads/<?=$The_Staff['photo']?>" alt="<?=$The_Staff['first_name'] . ' ' . $The_Staff['last_name']?>" width="100" height="120" align="left"/>
				<?php endif; ?>
				<h3><?=strtoupper($The_Staff['first_name'] . ' ' . $The_Staff['last_name'])?></h3>
				<em><?=$The_Staff['title']?></em>
				<div class="contact_info">
					<?=munge($The_Staff['email'])?><br />
					<?=$The_Staff['phone']?>
				</div>
				<div class="bio"><?=$The_Staff['bio']?></div>
			</div>
		<?php
			++$i;
			if($i<$Staff_Count) echo '<div class="staff_separator"></div>';
		endforeach;
	endif; // !Partners_Only?>
</div>
<div id="staff_shadow_bottom"></div>