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

if (is_array($The_View_Parameters['param'])) foreach ($The_View_Parameters['param'] as $The_Param_Index => $The_Param_Value) :

	if ($The_Param_Value == '') :
	
		unset($The_View_Parameters['param'][$The_Param_Index]);
	
	else :
	
		if ($The_Param_Index == 'month') :
		
			$The_View_Parameters['!month'] = $The_Param_Value;
			
			unset($The_View_Parameters['param'][$The_Param_Index]);
			
		endif;
		
	endif;

endforeach;

$The_Tags = explode(',', strtolower($View_Parameters['!tag']));

$Filter_With_Tags = (count($The_Tags) > 0) ? true : 0;

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_BLOG_POSTS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

$The_Blog_Posts = array();

// loop through the submissions
if (is_array($The_Submissions)) foreach ($The_Submissions as $The_Submission) :
	
	$The_Blog_Post = array();

	$The_Blog_Post['id'] = $The_Submission->ID;
	$The_Blog_Post['create_date'] = $The_Submission->Create_Date;
	$The_Blog_Post['modify_date'] = $The_Submission->Modify_Date;
	$The_Blog_Post['creator_user'] = $The_Submission->Creator_User;
	$The_Blog_Post['modifier_user'] = $The_Submission->Modifier_User;
	
	if ($The_Submission->Local_Values_Array[0]->Data) $The_Blog_Post['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[1]->Data) $The_Blog_Post['text'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[2]->Data) :
		$The_Blog_Post['category_id'] = $The_Submission->Local_Values_Array[2]->Data->ID;
		$The_Blog_Post['category_name'] = $The_Submission->Local_Values_Array[2]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	endif;
	if ($The_Submission->Local_Values_Array[3]->Data) :
	
		$The_Blog_Post['tags'] = strtolower($The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data());
	
		$The_Blog_Post['!tag_array'] = array_map('trim', explode(',', $The_Blog_Post['tags']));

		debug($The_Blog_Post['!tag_array']);

	endif;

	if ($Filter_With_Tags) : 
		
		$Has_Tag = false;
		
		foreach ($The_Tags as $The_Tag) : 
		
			if (in_array($The_Tag, $The_Blog_Post['!tag_array'])) $Has_Tag = true;
		
		endforeach;
	
	else :
	
		$Has_Tag = true;
	
	endif;

	if ($The_View_Parameters['!month']) :
	
		if (substr($The_Blog_Post['modify_date'], 0, strlen($The_View_Parameters['!month'])) == $The_View_Parameters['!month']) :
		
			if ($Has_Tag) : 
				$The_Blog_Posts[] = $The_Blog_Post;
			endif;
			
		endif;
	
	else :

		if ($Has_Tag) : 
			$The_Blog_Posts[] = $The_Blog_Post;
		endif;
		
	endif;

endforeach; 

if ($The_View_Parameters['param']['category']) : 
	if (count($The_Blog_Posts) > 0) : ?>
	<h2>Category: <?=$The_Blog_Posts[0]['category_name']?></h2>
<?php
	else : ?>
		<p>Sorry, there are no blog posts for that category.</p>
	<?php
	endif;
elseif ($The_View_Parameters['!month']) :
	if (count($The_Blog_Posts) > 0) : ?>
	<h2>Archive: <?=date("F Y", strtotime($The_Blog_Posts[0]['modify_date']))?></h2>
<?php
	else : ?>
		<p>Sorry, there are no blog posts for that month.</p>
	<?php
	endif;
endif;
foreach ($The_Blog_Posts as $The_Blog_Post) : ?>
	<div class="blog_post">
		<h3><?=$The_Blog_Post['title']?></h3>
		<div class="blog_post_content"><?=$The_Blog_Post['text']?></div>
		<p class="blog_post_information">
			Created: <?=$The_Blog_Post['create_date']?><br />
			Last modified: <?=$The_Blog_Post['modify_date']?><br />
			Filed under category: <a href="?param[category]=<?=$The_Blog_Post['category_id']?>"><?=$The_Blog_Post['category_name']?></a><br />
			Tags: <?=$The_Blog_Post['tags']?>
		</p>
	</div>
<?php
endforeach; ?>