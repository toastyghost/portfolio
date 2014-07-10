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

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_HOME_PAGE_SHOWCASE_ITEMS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
$The_Submission = $The_Submissions[0];

$The_Home_Page_Showcase_Items = array();

// loop through the submissions
if (is_array($The_Submissions)) :
foreach ($The_Submissions as $The_Submission) :
	$The_Home_Page_Showcase_Item = array();

	$The_Home_Page_Showcase_Item['id'] = $The_Submission->ID;
	$The_Home_Page_Showcase_Item['create_date'] = $The_Submission->Create_Date;
	$The_Home_Page_Showcase_Item['modify_date'] = $The_Submission->Modify_Date;
	$The_Home_Page_Showcase_Item['creator_user'] = $The_Submission->Creator_User;
	$The_Home_Page_Showcase_Item['modifier_user'] = $The_Submission->Modifier_User;
	
	if ($The_Submission->Local_Values_Array[0]->Data) $The_Home_Page_Showcase_Item['order_number'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[1]->Data) :
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[5]->Data) :
			$The_Home_Page_Showcase_Item['item_type'] = 'External URL';
		else :
			$The_Home_Page_Showcase_Item['item_type'] = 'News Article';
		endif;
		$The_Home_Page_Showcase_Item['item_id'] = $The_Submission->Local_Values_Array[1]->Data->ID;
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[0]->Data) :
			$The_Home_Page_Showcase_Item['item_title'] = $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
			$The_Home_Page_Showcase_Item['item_text'] = strip_tags($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[1]->Live_Site_HTML_For_The_Data());
		endif;
	elseif ($The_Submission->Local_Values_Array[6]->Data) :
		$The_Home_Page_Showcase_Item['item_type'] = 'Project';
		$The_Home_Page_Showcase_Item['item_id'] = $The_Submission->Local_Values_Array[6]->Data->ID;
		if ($The_Submission->Local_Values_Array[6]->Data->Local_Values_Array[0]->Data) :
			$The_Home_Page_Showcase_Item['item_title'] = $The_Submission->Local_Values_Array[6]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
			$The_Home_Page_Showcase_Item['item_text'] = strip_tags($The_Submission->Local_Values_Array[6]->Data->Local_Values_Array[1]->Live_Site_HTML_For_The_Data());
		endif;
	endif;
	
	if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data) $The_Home_Page_Showcase_Item['client'] = $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data;
	if ($The_Submission->Local_Values_Array[2]->Data) $The_Home_Page_Showcase_Item['image'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[3]->Data) $The_Home_Page_Showcase_Item['showcase_box_text'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[4]->Data) $The_Home_Page_Showcase_Item['display'] = $The_Submission->Local_Values_Array[4]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[5]->Data) $The_Home_Page_Showcase_Item['thumbnail'] = $The_Submission->Local_Values_Array[5]->Live_Site_HTML_For_The_Data();
	
	if ($The_Home_Page_Showcase_Item['display'] == 'Yes') :
		$The_Home_Page_Showcase_Items[] = $The_Home_Page_Showcase_Item;
	endif;
endforeach;
else : 
	echo 'No records found';
endif;
unset($The_Submissions);
?>
	<div id="showcase">
		<div id="photo_frame">
			<ul id="photos" style="width:<?=550*$num?>px;">
				<?php
				$current = ' current';
				// build the showcase box
				require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/summarize.php');
				foreach ($The_Home_Page_Showcase_Items as $The_Home_Page_Showcase_Item_Index => $The_Home_Page_Showcase_Item) :
					$j = $The_Home_Page_Showcase_Item_Index+1;
					echo '<li><div class="photo',$current,'" id="photo_',$j,'" style="background:url(/mimik/mimik_uploads/' . $The_Home_Page_Showcase_Item['image'] . ') no-repeat;">';
					/*echo '<div class="overlay" id="overlay',$j,'">';
					echo '<h1>',$The_Home_Page_Showcase_Item['item_title'],'</h1><p>',summarize($The_Home_Page_Showcase_Item['item_text'],20);
					if($The_Home_Page_Showcase_Item['item_type']=='Project')
						echo ' <a class="more" href="/portfolio#portfolio_item_',$The_Home_Page_Showcase_Item['item_id'],'">read more</a></p>';
					elseif($The_Home_Page_Showcase_Item['item_type']=='News Article')
						echo ''; # do nothing for now; news article template is default/unused.
					else echo '';
					echo '</div>*/
					echo '</div></li>';
					if($current)unset($current);
				endforeach;
				?>
			</ul>
		</div>
		<div id="navigation">
			<div class="arrow unitPng" id="left_arrow">
				<img width="40" height="40" src="images/showcase_arrow_left_hover.png" alt="left"/>
			</div>
			<div id="thumbnail_frame">
				<ul id="thumbs">
					<?php
					foreach ($The_Home_Page_Showcase_Items as $The_Home_Page_Showcase_Item_Index => $The_Home_Page_Showcase_Item) :
						echo '<li><a class="thumbnail_link" title="';
						if($The_Home_Page_Showcase_Item['client']) echo /*'&#60;strong&#62;',*/$The_Home_Page_Showcase_Item['client']/*.'&#60;&#47;strong&#62;&#60;br&#47;&#62;'*/;
						echo $The_Home_Page_Showcase_Item['item_title'],'" id="thumb_',($The_Home_Page_Showcase_Item_Index+1),'" href="javascript:"><img width="60" height="60" src="',(($The_Home_Page_Showcase_Item['thumbnail'])?'/mimik/mimik_uploads/'.$The_Home_Page_Showcase_Item['thumbnail']:'/images/ivy-leaf-black-60x60.png'),'" alt="',$The_Home_Page_Showcase_Item['item_title'],'"/></a></li>';
					endforeach; ?>
				</ul>
			</div>
			<div class="arrow unitPng" id="right_arrow">
				<img width="40" height="40" src="images/showcase_arrow_right_hover.png" alt="right"/>
			</div>
			<? #<![if !IE]>?><div id="highlight_bracket"></div><? #<![endif]>?>
		</div>
	</div>