<?php

/*****************************************************************************************************************
												   VIEWS
 *****************************************************************************************************************/

/* Initialize the view table id ********************************************/

$The_View_Table_Item_Name = 'view';

/* Initialize the view query ***********************************************/

$The_View_Query = 'SELECT * FROM `Views`';

/* Initialize the view start row *******************************************/

$The_Default_Start_Row = 0;

/* Initialize the view row limit *******************************************/

$The_Default_Row_Limit = 50;

/* Initialize the view sort information ************************************/

$The_Default_Sort_Information = array('display_name' => 'ASC');

/* Initialize view single row actions **************************************/

$The_View_Single_Row_Actions = array(
					array(
						'display_name' => 'Edit',
						//'image' => '/mimik/mimik_images/edit_user16.png',
						'class' => 'edit-view',
						'href' => 'support/edit-view.php?id=[id]',
						'onclick' => "$(this).parents().filter('tr').addClass('selected-edit'); if (confirm('edit!')) { $(this).parents().filter('tr').removeClass('selected-edit'); } else { $(this).parents().filter('tr').removeClass('selected-delete'); } return false;",
						'tip' => 'Edit Form'
					),
					array(
						'display_name' => 'Delete',
						//'image' => '/mimik/mimik_images/delete_user16.png',
						'class' => 'delete-view',
						'href' => 'support/delete-view.php?id=[id]',
						'onclick' => "$(this).parents().filter('tr').addClass('selected-delete'); if (confirm('delete?')) { $(this).parents().filter('tr').removeClass('selected-delete'); } else { $(this).parents().filter('tr').removeClass('selected-delete'); } return false;",
						'tip' => 'Delete View'
					),
					array(
						'display_name' => 'Preview',
						//'image' => '/mimik/mimik_images/delete_user16.png',
						'class' => 'preview-view',
						'href' => '/mimik/mimik_live_data/view.php?id=[id]',
						'tip' => 'Preview this View',
						'target' => '_blank'
					),
				);

/* Initialize view multirow actions ***************************************/

$The_View_Multirow_Actions = array(
					array(
						'display_name' => 'Create New View',
						'href' => '/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php?support_function=load_view_creator',
						'onclick' => "$(this).parents().filter('.ui-tabs-panel').load($(this).attr('href') + '&target_div=' + $(this).parents().filter('.ui-tabs-panel').attr('id') + '&rand=" . rand() . "'); return false;",
					),
				);

/* Initialize the view fields **********************************************/

// manually select some fields to be displayed
$The_View_Fields = array(
					array(
						'display_name' => 'ID',
						'name' => 'id',
						'type' => 'Number',
						'display_order_number' => 1,
						'tip' => 'Internal ID number within Mimik',
						'filterable' => '1' ),
					array(
						'display_name' => 'Name',
						'name' => 'display_name',
						'type' => 'Text',
						'display_order_number' => 2,
						'tip' => 'Display name',
						'filterable' => '1' ),
					array(
						'display_name' => 'Type',
						'name' => 'type',
						'type' => 'Text',
						'display_order_number' => 3,
						'is_graphic_field' => 1,
						'tip' => 'Normal, Calendar, or Gallery View',
						'graphic_map' => array(
											'Normal' => '/mimik/mimik_images/normal_view.png',
											'Calendar' => '/mimik/mimik_images/calendar_view.png',
											'Gallery' => '/mimik/mimik_images/gallery_view.png',
											'Video Player' => '/mimik/mimik_images/video_view.png'),
						'text_map' => array(
											'Normal' => 'Normal',
											'Calendar' => 'Calendar',
											'Gallery' => 'Gallery',
											'Video Player' => 'Video Player' ) )
				);

/* Initialize the view nav info ********************************************/

$The_View_Navigation_Information = array(array('text' => 'Views', 'is_current' => 1));

?>
