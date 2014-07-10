<?php

session_start();
require_once( '../mimik_includes/ivy-mimik_database_utilities.inc.php' );
require_once('../mimik_includes/an_authentication_component.inc.php');

$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( "../mimik_configuration/database_connection_info.csv" );
$The_Database_To_Use->Establishes_A_Connection();

$The_Authentication_Component = new An_Authentication_Component($The_Database_To_Use);

$The_Authentication_Component->Redirect_If_Necessary();

$_SESSION = $The_Authentication_Component->Validated_Session($_POST['txtUserId'], $_POST['txtPassword']);

if (!$The_Authentication_Component->Is_Logged_In()) :

	header('Location:login.php');
	exit;

else : // initialize tabbed navigation stuff

	require_once( '../mimik_configuration/mdt_forms_settings.config.php' );

	require_once( '../mimik_configuration/mdt_views_settings.config.php' );
	
	require_once( '../mimik_configuration/mdt_users_settings.config.php' );
	$The_SQL = 'SELECT * FROM Fields WHERE is_user_field = \'1\' AND display_in_management_view = \'1\' ORDER BY display_order_number ASC';
	$The_Custom_User_Fields = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	if (is_array($The_Custom_User_Fields)) foreach ($The_Custom_User_Fields as $The_Key => $The_Field) :
		$The_Custom_User_Fields[$The_Key]['filterable'] = '1';
		$The_User_Fields[] = $The_Custom_User_Fields[$The_Key];
	endforeach;
	
	require_once( '../mimik_configuration/mdt_groups_settings.config.php' );
	
	$The_SQL = 'SELECT * FROM Fields WHERE is_group_field = \'1\' AND display_in_management_view = \'1\' ORDER BY display_order_number ASC';
	$The_Custom_Group_Fields = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	if (is_array($The_Custom_Group_Fields)) foreach ($The_Custom_Group_Fields as $The_Key => $The_Field) :
		$The_Custom_Group_Fields[$The_Key]['filterable'] = '1';
		$The_Group_Fields[] = $The_Custom_Group_Fields[$The_Key];
	endforeach;	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mimik</title>
<link href="../mimik_css/all.css" rel="stylesheet" type="text/css" />
<link href="../mimik_css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

<script src="../mimik_js/ajax.js" type="text/javascript"></script>
<script src="../mimik_js/ajax_functions.js" type="text/javascript"></script>
<script src="../mimik_js/utility_functions.js" type="text/javascript"></script>
<script src="../mimik_js/md5.js" type="text/javascript"></script>
<script src="../mimik_js/ajax_upload.js" type="text/javascript"></script>
<script src="../mimik_js/JSON.js" type="text/javascript"></script>
<script src="../mimik_js/utilities.js" type="text/javascript"></script>
<!-- jsTween -->
<!--<script src="../mimik_js/tween/Tween.js" type="text/javascript"></script>
<script src="../mimik_js/tween/OpacityTween.js" type="text/javascript"></script>
<script src="../mimik_js/tween/Sequence.js" type="text/javascript"></script>
<script src="../mimik_js/tween/Parallel.js" type="text/javascript"></script>
<script src="../mimik_js/tween/changeTo.js" type="text/javascript"></script>-->
<!-- /jsTween -->
<!-- jQuery -->
<script src="../mimik_js/jquery.js" type="text/javascript"></script>
<script src="../mimik_js/easing.js" type="text/javascript"></script>
<script src="../mimik_js/drag.js" type="text/javascript"></script>
<script src="../mimik_js/drop.js" type="text/javascript"></script>
<script src="../mimik_js/ui.core.js" type="text/javascript"></script>
<script src="../mimik_js/ui.draggable.js" type="text/javascript"></script>
<script src="../mimik_js/ui.resizable.js" type="text/javascript"></script>
<script src="../mimik_js/jquery.livesearch.js" type="text/javascript"></script>
<script src="../mimik_js/quicksilver.js" type="text/javascript"></script>
<!-- tabbed navigation -->
<script type="text/javascript" src="../mimik_js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('#tabs').tabs();
	});
	$(document).ready(function(){
		var tabs = $('#tabs').tabs({ cookie: { expires: 30 } });
		$('a', tabs).click(function() {
			if ( $(this).parent().hasClass('ui-tabs-selected') ) {
				tabs.tabs('load', $('a', tabs).index(this));
			}
		});
		$('textarea').live('keypress', function(event){textAreaMaxLength($(this),event);});
	});
</script>
<!-- multifunction data table -->
<script type="text/javascript" src="../mimik_js/callback.js"></script>
<script type="text/javascript" src="../mimik_js/setCaretPosition.js"></script>

<style type="text/css">
	/*demo page css*/
	body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}
	.demoHeaders { margin-top: 2em; }
	#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
	#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
	ul#icons {margin: 0; padding: 0;}
	ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
	ul#icons span.ui-icon {float: left; margin: 0 4px;}
</style>
		
<!-- TODO: plugin scripts should be included programmatically -->

<!-- Calendar -->
<script src="../mimik_plugins/calendar/fullcalendar.min.js" type="text/javascript"></script>
<!-- /Calendar -->
<!-- Mapper -->
<!--<script src="../mimik_plugins/mapper/js/custom.js" type="text/javascript"></script>-->
<script src="../mimik_plugins/mapper/php_ajax_image_upload/scripts/ajaxupload.js" type="text/javascript"></script>
<script src="../mimik_plugins/mapper/js/formvalues.js" type="text/javascript"></script>
<script src="../mimik_plugins/mapper/js/utilities.js" type="text/javascript"></script>
<script src="../mimik_plugins/mapper/js/custom.js" type="text/javascript"></script>
<!-- /Mapper -->
<!-- fckEditor -->
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>
<!-- /fckEditor -->

<script type="text/javascript" src="../mimik_js/jquery.dump.js"></script>

<script type="text/javascript">
	var wait = 0;
</script>
</head>

<body onload="">

<div id="header">
	<h1>Mimik</h1>
</div>

<p>
	<? echo $The_Authentication_Component->HTML_For_The_Greeting(); ?>
	<a href="logout.php">Logout</a>
</p>

<?
$The_Admin_Permissions = $The_Database_To_Use->All_Admin_Permissions_For_The_User($_SESSION['login']);

$The_User_Has_Create_Edit_Forms_Permission = in_array(1, $The_Admin_Permissions);
$The_User_Has_Submit_To_Forms_Permission = in_array(2, $The_Admin_Permissions);
$The_User_Has_Create_Edit_Views_Permission = in_array(3, $The_Admin_Permissions);
$The_User_Has_Access_Views_Permission = in_array(4, $The_Admin_Permissions);
$The_User_Has_Create_Edit_Users_Permission = in_array(5, $The_Admin_Permissions);
$The_User_Has_Create_Edit_Groups_Permission = in_array(6, $The_Admin_Permissions);
$The_User_Has_Edit_Settings_Permission = in_array(7, $The_Admin_Permissions);
?>

<!-- Tabs -->
<div id="tabs">
	<ul>
		<li><a title="forms" href="<?php 
						if ($The_User_Has_Create_Edit_Forms_Permission || $The_User_Has_Submit_To_Forms_Permission) :
							echo '/mimik/mimik_support/show_data_table.php' .
								 '?create_parent=1' .
								 '&function=' . urlencode($The_Form_Callback_Function) . 
								 '&config_file=' . urlencode('../mimik_configuration/mdt_forms_settings.config.php') .
								 '&fields_var=The_Form_Fields' .
								 '&single_row_actions_var=The_Form_Single_Row_Actions' .
								 '&multirow_actions_var=The_Form_Multirow_Actions' .
								 '&rand=' . rand() .
								 '&table_item=' . $The_Form_Table_Item_Name .
								 '&nav_info=' . urlencode(serialize($The_Form_Navigation_Information)) .
								 '&session_id=' . session_id(); //sessionfix
						else :
							echo '#';
						endif;
			?>"<?php
						if (!$The_User_Has_Create_Edit_Forms_Permission && !$The_User_Has_Submit_To_Forms_Permission) :
							echo ' class="ui-state-disabled"';
						endif;
			?>>Forms</a></li>
		
		<li><a title="views" href="<?php
						if ($The_User_Has_Create_Edit_Views_Permission || $The_User_Has_Access_Views_Permission) :
							echo '/mimik/mimik_support/show_data_table.php?' . 
								 'create_parent=1' .
								 '&select_query=' . urlencode($The_View_Query) .
								 '&config_file=' . urlencode('../mimik_configuration/mdt_views_settings.config.php') .
								 '&fields_var=The_View_Fields' . 
								 '&single_row_actions_var=The_View_Single_Row_Actions' .
								 '&multirow_actions_var=The_View_Multirow_Actions' .
								 '&rand=' . rand() .
								 '&table_item=' . $The_View_Table_Item_Name .
								 '&nav_info=' . urlencode(serialize($The_View_Navigation_Information)) .
								 '&session_id=' . session_id(); //sessionfix
						else :
							echo '#';
						endif;
			?>"<?php
						if (!$The_User_Has_Create_Edit_Views_Permission && !$The_User_Has_Access_Views_Permission) :
							echo ' class="ui-state-disabled"';
						endif;
			?>>Views</a></li>

		<li><a title="users" href="<?php
						if ($The_User_Has_Create_Edit_Users_Permission) :
							echo '/mimik/mimik_support/show_data_table.php' .
								 '?create_parent=1' .
								 '&select_query=' . urlencode($The_User_Query) .
								 '&config_file=' . urlencode('../mimik_configuration/mdt_users_settings.config.php') .
								 '&fields_var=The_User_Fields' .
								 '&single_row_actions_var=The_User_Single_Row_Actions' .
								 '&multirow_actions_var=The_User_Multirow_Actions' .
								 '&rand=' . rand() .
								 '&table_item=' . $The_User_Table_Item_Name .
								 '&nav_info=' . urlencode(serialize($The_User_Navigation_Information)) .
								 '&show_special_filter=1' .
								 '&show_special_filter_function=Gets_The_Users_Associated_To_The_Group' . 
								 '&custom_fields=1' .
								 '&session_id=' . session_id(); //sessionfix
						else :
							echo '#';
						endif;
			?>"<?php
						if (!$The_User_Has_Create_Edit_Users_Permission) :
							echo ' class="ui-state-disabled"';
						endif;
			?>>Users</a></li>
		
		<li><a title="groups" href="<?php
						if ($The_User_Has_Create_Edit_Groups_Permission) :
							echo '/mimik/mimik_support/show_data_table.php' .
								 '?create_parent=1' .
								 '&select_query=' . urlencode($The_Group_Query) .
								 '&config_file=' . urlencode('../mimik_configuration/mdt_groups_settings.config.php') .
								 '&fields_var=The_Group_Fields' .
								 '&single_row_actions_var=The_Group_Single_Row_Actions' .
								 '&multirow_actions_var=The_Group_Multirow_Actions' .
								 '&rand=' . rand() .
								 '&table_item=' . $The_Group_Table_Item_Name .
								 '&nav_info=' . urlencode(serialize($The_Group_Navigation_Information)) .
								 '&custom_fields=1' .
								 '&session_id=' . session_id(); //sessionfix
						else :
							echo '#';
						endif;
			?>"<?php
						if (!$The_User_Has_Create_Edit_Groups_Permission) :
							echo ' class="ui-state-disabled"';
						endif;
			?>>Groups</a></li>
			
		<li><a title="settings" href="<?php
						if ($The_User_Has_Edit_Settings_Permission) :
							echo '/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php' .
								 '?support_function=load_settings_editor';
						else :
							echo '#';
						endif;
			?>"<?php
						if (!$The_User_Has_Edit_Settings_Permission) :
							echo ' class="ui-state-disabled"';
						endif;
			?>>Settings</a></li>
	</ul>
</div>

<?
endif; // is logged in
?>

</body>
</html>
