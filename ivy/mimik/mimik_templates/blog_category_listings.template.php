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

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_BLOG_CATEGORY_LISTINGS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

$The_Blog_Category_Listings = array();

// loop through the submissions
if (is_array($The_Submissions)) foreach ($The_Submissions as $The_Submission) :
	
	$The_Blog_Category_Listing = array();

	$The_Blog_Category_Listing['id'] = $The_Submission->ID;
	$The_Blog_Category_Listing['create_date'] = $The_Submission->Create_Date;
	$The_Blog_Category_Listing['modify_date'] = $The_Submission->Modify_Date;
	$The_Blog_Category_Listing['creator_user'] = $The_Submission->Creator_User;
	$The_Blog_Category_Listing['modifier_user'] = $The_Submission->Modifier_User;
	
	if ($The_Submission->Local_Values_Array[0]->Data) $The_Blog_Category_Listing['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();

	$The_Blog_Category_Listings[] = $The_Blog_Category_Listing;

endforeach; ?>
<h2>Categories</h2>
<ul class="blog_category_listing_container">
	<li><a href="?param[category]=">ALL</a></li>
<?php
foreach ($The_Blog_Category_Listings as $The_Blog_Category_Listing) : ?>
	<li><a href="?param[category]=<?=$The_Blog_Category_Listing['id']?>"><?=$The_Blog_Category_Listing['title']?></a></li>
<?php
endforeach; ?>
</ul>