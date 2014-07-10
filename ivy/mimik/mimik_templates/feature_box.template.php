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

if (!$The_View_Parameters['record_id']) return;

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_FEATURE_BOX_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
$The_Submission = $The_Submissions[0];

$The_Feature_Box = array();

$The_Feature_Box['id'] = $The_Submission->ID;
$The_Feature_Box['create_date'] = $The_Submission->Create_Date;
$The_Feature_Box['modify_date'] = $The_Submission->Modify_Date;
$The_Feature_Box['creator_user'] = $The_Submission->Creator_User;
$The_Feature_Box['modifier_user'] = $The_Submission->Modifier_User;

if ($The_Submission->Local_Values_Array[0]->Data) $The_Feature_Box['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[1]->Data) $The_Feature_Box['content'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[2]->Data) $The_Feature_Box['image'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[3]->Data->ID) $The_Feature_Box['link_id'] = $The_Submission->Local_Values_Array[3]->Data->ID;
if ($The_Submission->Local_Values_Array[4]->Data) $The_Feature_Box['link_text'] = $The_Submission->Local_Values_Array[4]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[5]->Data) $The_Feature_Box['photo_credit'] = $The_Submission->Local_Values_Array[5]->Live_Site_HTML_For_The_Data();

if ($The_View_Parameters['!class']) $The_Feature_Box['!class'] = explode(' ', $The_View_Parameters['!class']);
if ($The_View_Parameters['!type']) $The_Feature_Box['!type'] = $The_View_Parameters['!type'];
if ($The_View_Parameters['!size']) $The_Feature_Box['!size'] = $The_View_Parameters['!size'];

if (strpos($_SERVER['SCRIPT_URI'], 'ivygroup.com') !== false) :

/***** ivygroup *****/

?>
<?php if (in_array('first', $The_Feature_Box['!class'])) : ?>
	<?php if ($The_Feature_Box['!type'] == 'sidebar') : ?>
		<div class="sidebar_box_top"></div>
	<?php endif; ?>
<?php endif; ?>
<?php if ($The_Feature_Box['!type'] == 'sidebar') : ?>
<div class="sidebar_box_container">
<?php endif; ?>
	<?php if ($The_Feature_Box['image']) : ?>
		<img src="/mimik/mimik_uploads/<?=$The_Feature_Box['image']?>" alt="<?=$The_Feature_Box['title']?>" height="129" width="156" />
	<?php endif; ?>
	<h2><?=$The_Feature_Box['title']?></h2>
	<p>
		<?=$The_Feature_Box['content']?>
		<?php // menu item
			if ($The_Feature_Box['link_id']) :
				$The_Temp_View_Parameters = $The_View_Parameters;
				unset($The_View_Parameters);
				$The_View_Parameters['id'] = THE_MENU_ITEMS_VIEW_ID;
				$The_View_Parameters['record_id'] = $The_Feature_Box['link_id'];
				$The_View_Parameters['!link_text'] = $The_Feature_Box['link_text'];
				$The_Temp_Feature_Box = $The_Feature_Box;
				unset($The_Feature_Box);
				include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
				$The_Feature_Box = $The_Temp_Feature_Box;
				$The_View_Parameters = $The_Temp_View_Parameters;
			endif;
		?>
	</p>
</div>
<?php if (in_array('last', $The_Feature_Box['!class'])) : ?>
	<?php if ($The_Feature_Box['!type'] == 'sidebar') : ?>
		<div class="sidebar_box_bottom"></div>
	<?php endif; ?>
<?php endif; 

else : 

/***** ivylibrary *****/
?>

	<div class="contents">
		<?php
		if ($The_Feature_Box['!size'] == 'large') : ?>
			<h1><?=$The_Feature_Box['title']?></h1>
		<?php
		else : ?>
			<h2><?=$The_Feature_Box['title']?></h2>
		<?php
		endif; ?>
		<p class="photo">
			<?php
			if ($The_Feature_Box['!size'] == 'large') : ?>
			<img src="/mimik/mimik_uploads/<?=$The_Feature_Box['image']?>" alt="<?=$The_Feature_Box['title']?>" height="152" width="246" />
			<?php
			else : ?>
			<img src="/mimik/mimik_uploads/<?=$The_Feature_Box['image']?>" alt="<?=$The_Feature_Box['title']?>" height="100" width="162" />
			<?php
			endif; ?>
			<br />
			<small><?=$The_Feature_Box['photo_credit']?></small>
		</p>
		<p><?=$The_Feature_Box['content']?></p>
		<p><?php // menu item
			if ($The_Feature_Box['link_id']) :
				$The_Temp_View_Parameters = $The_View_Parameters;
				unset($The_View_Parameters);
				$The_View_Parameters['id'] = THE_MENU_ITEMS_VIEW_ID;
				$The_View_Parameters['record_id'] = $The_Feature_Box['link_id'];
				$The_View_Parameters['!link_text'] = $The_Feature_Box['link_text'];
				$The_Temp_Feature_Box = $The_Feature_Box;
				unset($The_Feature_Box);
				include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
				$The_Feature_Box = $The_Temp_Feature_Box;
				$The_View_Parameters = $The_Temp_View_Parameters;
			endif;
		?></p>
	</div>

<?php
endif; ?>