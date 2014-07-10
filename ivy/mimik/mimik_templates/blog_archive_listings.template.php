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

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_BLOG_ARCHIVE_LISTINGS_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

$The_Blog_Archive_Listings = array();

// loop through the submissions
if (is_array($The_Submissions)) foreach ($The_Submissions as $The_Submission) :
	
	$The_Blog_Archive_Listing = array();

	$The_Blog_Archive_Listing['id'] = $The_Submission->ID;
	$The_Blog_Archive_Listing['create_date'] = $The_Submission->Create_Date;
	$The_Blog_Archive_Listing['modify_date'] = $The_Submission->Modify_Date;
	$The_Blog_Archive_Listing['creator_user'] = $The_Submission->Creator_User;
	$The_Blog_Archive_Listing['modifier_user'] = $The_Submission->Modifier_User;
	
	$The_Blog_Archive_Listing['month'] = date("Y-m", strtotime($The_Blog_Archive_Listing['modify_date']));

	if (!in_array($The_Blog_Archive_Listing['month'], $The_Blog_Archive_Listings)) :
	
		$The_Blog_Archive_Listings[] = $The_Blog_Archive_Listing['month'];
		
	endif;

endforeach; 

sort($The_Blog_Archive_Listings);

$The_Blog_Archive_Listings = array_reverse($The_Blog_Archive_Listings);

?>
<h2>Archive</h2>
<ul class="blog_archive_listing_container">
	<li><a href="?param[month]=">ALL</a></li>
<?php
foreach ($The_Blog_Archive_Listings as $The_Blog_Archive_Listing) : ?>
	<li><a href="?param[month]=<?=$The_Blog_Archive_Listing?>"><?=date("F Y", strtotime($The_Blog_Archive_Listing . '-01'))?></a></li>
<?php
endforeach; ?>
</ul>