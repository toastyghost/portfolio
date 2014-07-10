<?php // establish the database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');

require_once($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_templates/menu.template.php');

$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();

// overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST) && !is_array($The_View_Parameters)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_HEADER_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
$The_Submission = $The_Submissions[0];

$The_Header = array();

$The_Header['id'] = $The_Submission->ID;
$The_Header['create_date'] = $The_Submission->Create_Date;
$The_Header['modify_date'] = $The_Submission->Modify_Date;
$The_Header['creator_user'] = $The_Submission->Creator_User;
$The_Header['modifier_user'] = $The_Submission->Modifier_User;

if ($The_Submission->Local_Values_Array[0]->Data) $The_Header['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[1]->Data) $The_Header['menu_id'] = $The_Submission->Local_Values_Array[1]->Data->ID;
if ($The_Submission->Local_Values_Array[2]->Data) $The_Header['utility_menu_id'] = $The_Submission->Local_Values_Array[2]->Data->ID;
if ($The_Submission->Local_Values_Array[3]->Data) $The_Header['logo'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();

switch ($The_Header['id']) :

/***** ivygroup.com *****/

case 1 :
?>		<div id="primary_nav_left_margin"></div>
		<div id="primary_nav_right_margin"></div>
		<div id="primary_nav_bottom_shadow"></div>
		<div id="header_inner">
			<?php // utility menu - jdc 1/25/11: disabled until blog/login actually have content ready
				/*$The_Temp_View_Parameters = $The_View_Parameters;
				unset($The_View_Parameters);
				$The_View_Parameters['id'] = THE_MENU_VIEW_ID;
				$The_View_Parameters['record_id'] = $The_Header['utility_menu_id'];
				$The_View_Parameters['!ul_id'] = 'utility_nav';
				$The_View_Parameters['!div_container'] = true;
				$The_View_Parameters['!a_individual_id'] = true;
				$The_View_Parameters['!case'] = 'lower';
				$The_Temp_Header = $The_Header;
				//include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
				Print_The_Menu($The_View_Parameters);
				$The_Header = $The_Temp_Header;
				$The_View_Parameters = $The_Temp_View_Parameters;*/
			?>
			<!--<div class="utility nav_hover" id="blog_hover"></div>
			<div class="utility nav_hover" id="login_hover"></div>-->
			<div id="logo_container" <?=(($_REQUEST['id']==1 && $_REQUEST['record_id']==1)?'class="active" ':NULL)?>><a id="home_link" href="/"><img width="189" height="137" src="/mimik/mimik_uploads/<?=$The_Header['logo']?>" alt="The Ivy Group, Ltd."/></a></div>
			<?php // primary menu
				$The_Temp_View_Parameters = $The_View_Parameters;
				unset($The_View_Parameters);
				$The_View_Parameters['id'] = THE_MENU_VIEW_ID;
				$The_View_Parameters['record_id'] = $The_Header['menu_id'];
				$The_View_Parameters['!ul_id'] = 'primary_nav';
				$The_View_Parameters['!li_individual_id'] = true;
				$The_View_Parameters['!a_individual_id'] = true;
				$The_View_Parameters['!case'] = 'lower';
				$The_View_Parameters['!center_spacer'] = true;
				$The_View_Parameters['!show_subtitle'] = true;
				$The_Temp_Header = $The_Header;
				//include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
				Print_The_Menu($The_View_Parameters);
				$The_Header = $The_Temp_Header;
				$The_View_Parameters = $The_Temp_View_Parameters;
			?>
			<div class="primary nav_hover unitPng" id="about_hover"></div>
			<div class="primary nav_hover unitPng" id="services_hover"></div>
			<div class="primary nav_hover unitPng" id="home_hover"></div>
			<div class="primary nav_hover unitPng" id="portfolio_hover"></div>
			<div class="primary nav_hover unitPng" id="contact_hover"></div>
		</div>
<?php
break;

/***** ivylibrary *****/

case 2 :
?>
	<header>
		<?php // utility menu
			$The_Temp_View_Parameters = $The_View_Parameters;
			unset($The_View_Parameters);
			$The_View_Parameters['id'] = THE_MENU_VIEW_ID;
			$The_View_Parameters['record_id'] = $The_Header['utility_menu_id'];
			$The_View_Parameters['!ul_id'] = 'utility_nav';
			$The_View_Parameters['!a_individual_id'] = true;
			$The_Temp_Header = $The_Header;
			//include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
			Print_The_Menu($The_View_Parameters);
			$The_Header = $The_Temp_Header;
			$The_View_Parameters = $The_Temp_View_Parameters;
		?>
		<div id="logo_container"><a id="home_link" href="/"><img src="/mimik/mimik_uploads/<?=$The_Header['logo']?>" alt="Ivy Library: a division of The Ivy Group, Ltd." /></a></div>
		<ul id="primary_nav">
			<li class="first" id="about"><a href="/about" id="about_link">About<br/><span class="link_subtitle">we've been around</span></a></li>
			<li id="services"><a href="/services" id="services_link">Services<br/><span class="link_subtitle">are we a good fit?</span></a></li>
			<li id="center_spacer">&nbsp;</li>
			<li id="portfolio"><a href="/portfolio" id="portfolio_link">Portfolio<br/><span class="link_subtitle">look here first</span></a></li>
			<li class="last" id="contact"><a href="/contact" id="contact_link">Contact<br/><span class="link_subtitle">pleased to meet you</span></a></li>
		</ul>
    </header>
<?php
break;
endswitch; ?>