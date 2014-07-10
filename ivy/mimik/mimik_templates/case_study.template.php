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
if (isset($The_View_Parameters['limit'])) $The_Submissions = $The_Database_To_Use->Gets_The_Limited_Template_Data_For_The_View(21, $The_View_Parameters['limit'], $The_View_Parameters['param']);
// the limit parameter is not set... if the record_id parameter is set, get only the data for that submission
else $The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(21, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
?>

<?php // loop through the submissions
if (is_array($The_Submissions)) :
foreach ($The_Submissions as $The_Submission) :
?>
id : <?php echo $The_Submission->ID; ?><br />
create_date : <?php echo $The_Submission->Create_Date; ?><br />
modify_date : <?php echo $The_Submission->Modify_Date; ?><br />

creator_user : <?php echo $The_Submission->Creator_User; ?><br />

modifier_user : <?php echo $The_Submission->Modifier_User; ?><br />

<?php // Title
if ($The_Submission->Local_Values_Array[0]->Data) : ?>
Title : <?php echo $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

	<blockquote>
	Project<br />
	id : <?php echo $The_Submission->Local_Values_Array[1]->Data->ID; ?><br />
	create_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Create_Date; ?><br />
	modify_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Modify_Date; ?><br />

	creator_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Creator_User; ?><br />

	modifier_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Modifier_User; ?><br />

	<?php // Title
	if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[0]->Data) : ?>
	Title : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data() ?><br />
	<?php endif; ?>

	<?php // Description
	if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[1]->Data) : ?>
	Description : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[1]->Live_Site_HTML_For_The_Data() ?><br />
	<?php endif; ?>

		<blockquote>
		Client<br />
		id : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->ID; ?><br />
		create_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Create_Date; ?><br />
		modify_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Modify_Date; ?><br />

		creator_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Creator_User; ?><br />

		modifier_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Modifier_User; ?><br />

		<?php // Name
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data) : ?>
		Name : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data() ?><br />
		<?php endif; ?>

		<?php // Description
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[1]->Data) : ?>
		Description : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[1]->Live_Site_HTML_For_The_Data() ?><br />
		<?php endif; ?>

			<blockquote>
			Organization Type<br />
			id : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->ID; ?><br />
			create_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Create_Date; ?><br />
			modify_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Modify_Date; ?><br />

			creator_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Creator_User; ?><br />

			modifier_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Modifier_User; ?><br />

			<?php // Name
			if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Data) : ?>
			Name : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[2]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data() ?><br />
			<?php endif; ?>

			</blockquote>

			<blockquote>
			Industry<br />
			id : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->ID; ?><br />
			create_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->Create_Date; ?><br />
			modify_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->Modify_Date; ?><br />

			creator_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->Creator_User; ?><br />

			modifier_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->Modifier_User; ?><br />

			<?php // Name
			if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->Local_Values_Array[0]->Data) : ?>
			Name : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[3]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data() ?><br />
			<?php endif; ?>

			</blockquote>

		<?php // Display
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[4]->Data) : ?>
		Display : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[4]->Live_Site_HTML_For_The_Data() ?><br />
		<?php endif; ?>

		<?php // Image
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[5]->Data) : ?>
		Image : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[5]->Live_Site_HTML_For_The_Data() ?><br />
		<?php endif; ?>

		<?php // Client Of
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[6]->Data) : ?>
		Client Of : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[2]->Data->Local_Values_Array[6]->Live_Site_HTML_For_The_Data() ?><br />
		<?php endif; ?>

		</blockquote>

	<?php // Display
	if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[3]->Data) : ?>
	Display : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[3]->Live_Site_HTML_For_The_Data() ?><br />
	<?php endif; ?>

	<?php // Portfolio Image
	if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[4]->Data) : ?>
	Portfolio Image : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[4]->Live_Site_HTML_For_The_Data() ?><br />
	<?php endif; ?>

	<?php // Portfolio Thumbnail
	if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[5]->Data) : ?>
	Portfolio Thumbnail : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[5]->Live_Site_HTML_For_The_Data() ?><br />
	<?php endif; ?>

		<blockquote>
		Service<br />
		id : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[6]->Data->ID; ?><br />
		create_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[6]->Data->Create_Date; ?><br />
		modify_date : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[6]->Data->Modify_Date; ?><br />

		creator_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[6]->Data->Creator_User; ?><br />

		modifier_user : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[6]->Data->Modifier_User; ?><br />

		<?php // Name
		if ($The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[6]->Data->Local_Values_Array[0]->Data) : ?>
		Name : <?php echo $The_Submission->Local_Values_Array[1]->Data->Local_Values_Array[6]->Data->Local_Values_Array[0]->Live_Site_HTML_For_The_Data() ?><br />
		<?php endif; ?>

		</blockquote>

	</blockquote>

<?php // Full Text
if ($The_Submission->Local_Values_Array[2]->Data) : ?>
Full Text : <?php echo $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 1
if ($The_Submission->Local_Values_Array[3]->Data) : ?>
Image 1 : <?php echo $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 1 Alt Text
if ($The_Submission->Local_Values_Array[4]->Data) : ?>
Image 1 Alt Text : <?php echo $The_Submission->Local_Values_Array[4]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 1 Caption
if ($The_Submission->Local_Values_Array[5]->Data) : ?>
Image 1 Caption : <?php echo $The_Submission->Local_Values_Array[5]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 2
if ($The_Submission->Local_Values_Array[6]->Data) : ?>
Image 2 : <?php echo $The_Submission->Local_Values_Array[6]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 2 Alt Text
if ($The_Submission->Local_Values_Array[7]->Data) : ?>
Image 2 Alt Text : <?php echo $The_Submission->Local_Values_Array[7]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 2 Caption
if ($The_Submission->Local_Values_Array[8]->Data) : ?>
Image 2 Caption : <?php echo $The_Submission->Local_Values_Array[8]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 3
if ($The_Submission->Local_Values_Array[9]->Data) : ?>
Image 3 : <?php echo $The_Submission->Local_Values_Array[9]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 3 Alt Text
if ($The_Submission->Local_Values_Array[10]->Data) : ?>
Image 3 Alt Text : <?php echo $The_Submission->Local_Values_Array[10]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php // Image 3 Caption
if ($The_Submission->Local_Values_Array[11]->Data) : ?>
Image 3 Caption : <?php echo $The_Submission->Local_Values_Array[11]->Live_Site_HTML_For_The_Data() ?><br />
<?php endif; ?>

<?php
endforeach;
else : ?>
No records found
<?php
endif;
?>

