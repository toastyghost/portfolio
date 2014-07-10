<?php // establish the database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();

$The_Service_To_Show = $_GET['service'];
$The_Organization_Type_To_Show = $_GET['organization_type'];
$The_Industry_To_Show = $_GET['industry'];

// overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST) && !is_array($The_View_Parameters)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;

include($_SERVER['DOCUMENT_ROOT'].'/site_includes/build_portfolio.php');

?>
<div id="portfolio_sidebar">
	<div id="shadow_top_left">
		<div id="shadow_top_right">
			<div id="shadow_top"></div>
		</div>
	</div>
	<div id="shadow_left">
		<div id="shadow_right">
			<div id="shadow_inner">
			<div class="portfolio_filter_container" id="portfolio_filter_container">
			<ul id="portfolio_filter_visible"><!--
				--><li><a id="service" class="category_link" href="javascript:">SERVICES <img width="12" height="6" src="/images/portfolio_select_arrow.png"/></a></li><!--
				--><li><a id="organization_type" class="category_link" href="javascript:">ORGANIZATION TYPE <img width="12" height="6" src="/images/portfolio_select_arrow.png"/></a></li><!--
				--><li><a id="industry" class="category_link" href="javascript:">INDUSTRY <img width="12" height="6" src="/images/portfolio_select_arrow.png"/></a></li><!--
			--></ul>
			<div id="filter_options">
				<ul id="service">
					<?
						foreach($The_Services as $The_Service_ID => $The_Service_Name)
							echo '<li><a href="javascript:" id="service_',$The_Service_ID,'" class="service filter_link">',$The_Service_Name,'</a></li>';
						unset($The_Services);
					?>
				</ul>
				<ul id="organization_type">
					<?
						foreach($The_Organization_Types as $The_Organization_Type_ID => $The_Organization_Type_Name)
							echo '<li><a href="javascript:" id="organization_type_',$The_Organization_Type_ID,'" class="organization_type filter_link">',$The_Organization_Type_Name,'</a></li>';
						unset($The_Organization_Types);
					?>
				</ul>
				<ul id="industry">
					<?
						foreach($The_Industries as $The_Industry_ID => $The_Industry_Name)
							echo '<li><a href="javascript:" id="industry_',$The_Industry_ID,'" class="industry filter_link">',$The_Industry_Name,'</a></li>';
						unset($The_Industries);
					?>
				</ul>
			</div>
		</div>

				<div id="arrow_left" class="arrow_container top">
					<div id="arrow_top_left" class="arrow_corner"></div>
					<div id="arrow_left_gradient" class="side_gradient">
						<a id="arrow_left_link" class="arrow_link" href="javascript://"></a>
					</div>
					<div id="arrow_left_gradient_hover" class="side_gradient_hover">
						<a id="arrow_left_hover" class="arrow_hover" href="javascript://"></a>
					</div>
					<div id="arrow_bottom_left" class="arrow_corner"></div>
				</div>
				<div id="thumbnail_container">
					<? include($THE_BASE_SERVER_PATH.'/httpdocs/site_includes/portfolio_list.php');?>
				</div>
				<div id="arrow_right" class="arrow_container top">
					<div id="arrow_top_right" class="arrow_corner"></div>
					<div id="arrow_right_gradient" class="side_gradient">
						<a id="arrow_right_link" class="arrow_link" href="javascript://"></a>
					</div>
					<div id="arrow_right_gradient_hover" class="side_gradient_hover">
						<a id="arrow_right_hover" class="arrow_hover" href="javascript://"></a>
					</div>
					<div id="arrow_bottom_right" class="arrow_corner"></div>
				</div><!--arrow_right-->
				<p id="filters"></p>
				<div id="current_filters">
					<span class="current_filter_label">Service: <span class="current_filter" id="current_service_filter"></span></span><br/>
					<span class="current_filter_label">Organization Type: <span class="current_filter" id="current_organization_type_filter"></span></span><br/>
					<span class="current_filter_label">Industry: <span class="current_filter" id="current_industry_filter"></span></span><br/>
				</div>
				<a id="clear_filters" href="javascript://">clear filters</a>
			</div><!--shadow_inner-->
		</div><!--shadow_right-->
	</div><!--shadow_left-->
	<?
		// awards sidebar
		$The_Temp_View_Parameters = $The_View_Parameters;
		unset($The_View_Parameters);
		$The_View_Parameters['id'] = 28;
		include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
		$The_View_Parameters = $The_Temp_View_Parameters;
		unset($The_Temp_View_Parameters);
	?>
</div><!--portfolio_sidebar-->
<?
echo '<div class="portfolio_items_container" id="portfolio_items_container">';
$The_View_Parameters['record_id'] = $The_Portfolio_Items[0]['id'];
include($_SERVER['DOCUMENT_ROOT'].'/site_includes/portfolio_item.php');
echo '</div>';
?>