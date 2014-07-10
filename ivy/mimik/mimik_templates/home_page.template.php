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

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_HOME_PAGE_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);

$The_Submission = $The_Submissions[0];

$The_Home_Page = array();

// loop through the submissions
$The_Home_Page['id'] = $The_Submission->ID;
$The_Home_Page['create_date'] = $The_Submission->Create_Date;
$The_Home_Page['modify_date'] = $The_Submission->Modify_Date;
$The_Home_Page['creator_user'] = $The_Submission->Creator_User;
$The_Home_Page['modifier_user'] = $The_Submission->Modifier_User;

if ($The_Submission->Local_Values_Array[0]->Data) $The_Home_Page['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[1]->Data) $The_Home_Page['heading'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[2]->Data) $The_Home_Page['text'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[3]->Data) $The_Home_Page['seo_page_title'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[4]->Data) :
	$The_Home_Page['background_color_name'] = $The_Submission->Local_Values_Array[4]->Data->Local_Values_Array[0]->Data;
	$The_Home_Page['background_color_rgb'] = $The_Submission->Local_Values_Array[4]->Data->Local_Values_Array[1]->Data;
endif;
if ($The_Submission->Local_Values_Array[6]->Data) $The_Home_Page['extra_menu_id'] = $The_Submission->Local_Values_Array[6]->Data->ID;
if ($The_Submission->Local_Values_Array[7]->Data) $The_Home_Page['site'] = $The_Submission->Local_Values_Array[7]->Data;

switch($The_Home_Page['site']) :

/****** ivygroup ******/

case 'ivygroup.com' :
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$The_Home_Page['title']?></title>
	<meta name="google-site-verification" content="sEff-4P5Y9RKrCX9SWrTMIVDamc8mjHn-rkviRo8LTw" />
	<link type="text/css" rel="stylesheet" href="/css/all.css"/>
	<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
	<script type="text/javascript" src="/js/easing.js"></script>
	<script type="text/javascript" src="/js/color.js"></script>
	<script type="text/javascript" src="/js/custom.js"></script>
	<!-- TODO: REMOVE THIS BEFORE LAUNCH... FOR DEBUGGING PURPOSES ONLY -->
	<script type="text/javascript" src="/js/dump.js"></script>
	<!-- END TODO -->
	<script type="text/javascript" src="/js/unitpngfix.js"></script>
</head>
<body style="background-color:#<?=$The_Home_Page['background_color_rgb']?>">
	<div id="clouds"></div>
	<div id="gradient"></div>
	<div id="header_outer">
		<?php // header
			$The_Temp_View_Parameters = $The_View_Parameters;
			unset($The_View_Parameters);
			$The_Temp_Home_Page = $The_Home_Page;
			$The_View_Parameters['id'] = THE_HEADER_VIEW_ID;
			$The_View_Parameters['record_id'] = 1;
			include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
			$The_Home_Page = $The_Temp_Home_Page;
			$The_View_Parameters = $The_Temp_View_Parameters;
		?>
		<div id="content_shadow_left">
			<div id="content_shadow_right">
				<div id="page_body">
					<div id="content_wrapper">
						<div id="welcome">
							<!--<h1><?=$The_Home_Page['heading']?></h1>-->
							<?=$The_Home_Page['text']?>
							<?php // extra menu
								/*$The_Temp_View_Parameters = $The_View_Parameters;
								unset($The_View_Parameters);
								$The_View_Parameters['id'] = THE_MENU_VIEW_ID;
								$The_View_Parameters['record_id'] = $The_Home_Page['extra_menu_id'];
								$The_View_Parameters['!ul_class'] = 'extra_nav';
								$The_Temp_Home_Page = $The_Home_Page;
								//include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
								Print_The_Menu($The_View_Parameters);
								$The_Home_Page = $The_Temp_Home_Page;
								$The_View_Parameters = $The_Temp_View_Parameters;*/
							?>
						</div>
						<?php
						$The_Temp_View_Parameters = $The_View_Parameters;
						unset($The_View_Parameters);
						$The_Temp_Home_Page = $The_Home_Page;
						$The_View_Parameters['id'] = THE_HOME_PAGE_SHOWCASE_ITEMS_VIEW_ID;
						$The_View_Parameters['limit'] = 7;
						include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
						$The_Home_Page = $The_Temp_Home_Page;
						$The_View_Parameters = $The_Temp_View_Parameters;
						?>
					</div>
				</div>
			</div>
		</div>
		<?php // footer
		$The_Temp_View_Parameters = $The_View_Parameters;
		unset($The_Temp_View_Parameters);
		$The_View_Parameters['id'] = THE_FOOTER_VIEW_ID;
		$The_View_Parameters['record_id'] = 1;
		$The_View_Parameters['!mimik_logo_color'] = $The_Home_Page['background_color_name'];
		$The_Temp_Home_Page = $The_Home_Page;
		include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
		$The_Home_Page = $The_Temp_Home_Page;
		$The_View_Parameters = $The_Temp_View_Parameters;
		?>
	</div>
</body>
</html>
<?php
break;

/****** ivylibrary ******/

case 'ivylibrary.com' :

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_NEWS_ARTICLES_VIEW_ID, NULL, array('display_on_ivy_library_home_page' => 'Yes'));

$The_News_Articles = array();

foreach ($The_Submissions as $The_Submission) :

	$The_News_Article = array();
	
	// loop through the submissions
	$The_News_Article['id'] = $The_Submission->ID;
	$The_News_Article['create_date'] = $The_Submission->Create_Date;
	$The_News_Article['modify_date'] = $The_Submission->Modify_Date;
	$The_News_Article['creator_user'] = $The_Submission->Creator_User;
	$The_News_Article['modifier_user'] = $The_Submission->Modifier_User;
	
	if ($The_Submission->Local_Values_Array[0]->Data) $The_News_Article['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[1]->Data) $The_News_Article['full_text'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[2]->Data) $The_News_Article['story_image'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
	if ($The_Submission->Local_Values_Array[3]->Data) $The_News_Article['story_image_alt_text'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();

	$The_News_Articles[] = $The_News_Article;
	
endforeach;

usort($The_News_Articles, 'sort_news_articles');

?><!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">

  <!-- www.phpied.com/conditional-comments-block-downloads/ -->
  <!--[if IE]><![endif]-->

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!--  Mobile Viewport Fix
        j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag 
  device-width : Occupy full width of the screen in its current orientation
  initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
  maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
  -->
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">


  <!-- Place favicon.ico and apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">


  <!-- CSS : implied media="all" -->
  <link rel="stylesheet" href="/css/style.css?v=1">

  <!-- For the less-enabled mobile browsers like Opera Mini -->
  <link rel="stylesheet" media="handheld" href="/css/handheld.css?v=1">

 
  <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
  <script src="/js/modernizr-1.5.min.js"></script>

</head>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->

<div id="container">
  	<?php // header
		$The_Temp_View_Parameters = $The_View_Parameters;
		unset($The_View_Parameters);
		$The_Temp_Home_Page = $The_Home_Page;
		$The_View_Parameters['id'] = THE_HEADER_VIEW_ID;
		$The_View_Parameters['record_id'] = 2;
		include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
		$The_Home_Page = $The_Temp_Home_Page;
		$The_View_Parameters = $The_Temp_View_Parameters;
	?>
    
    <div id="main" class="homepage">
		<div id="welcome_container" class="clearfix module">
			<div class="contents">
				<h1><?=$The_Home_Page['heading']?></h1>
				<p><?=$The_Home_Page['text']?></p>
			</div>
			<?php // extra menu
				$The_Temp_View_Parameters = $The_View_Parameters;
				unset($The_View_Parameters);
				$The_View_Parameters['id'] = THE_MENU_VIEW_ID;
				$The_View_Parameters['record_id'] = $The_Home_Page['extra_menu_id'];
				$The_View_Parameters['!ul_class'] = 'clearfix extra_nav';
				$The_Temp_Home_Page = $The_Home_Page;
				//include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
				Print_The_Menu($The_View_Parameters);
				$The_Home_Page = $The_Temp_Home_Page;
				$The_View_Parameters = $The_Temp_View_Parameters;
			?>
		</div>
		
		<div id="press_releases_container" class="clearfix module">
			<div class="contents">
				<h2>What's New</h2>
				<ul id="press_releases">
					<?php 
					foreach ($The_News_Articles as $The_News_Article) : ?>
					<li>
						<a href="#">
							<div class="bg">
								<strong><?=$The_News_Article['title']?></strong><br />
								<em><?=date("j M Y", strtotime($The_News_Article['modify_date'])); ?></em><br/>
								<span><?php echo substr(strip_tags($The_News_Article['full_text']), 0, 100); ?>&hellip;</span>
							</div>
						</a>
					</li>
					<?php
					endforeach; ?>
				</ul>
			</div>
		</div>
		<br class="clear" />
    </div>
    
	<?php // footer
		$The_Temp_View_Parameters = $The_View_Parameters;
		unset($The_View_Parameters);
		$The_Temp_Home_Page = $The_Home_Page;
		$The_View_Parameters['id'] = THE_FOOTER_VIEW_ID;
		$The_View_Parameters['record_id'] = 2;
		include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
		$The_Home_Page = $The_Temp_Home_Page;
		$The_View_Parameters = $The_Temp_View_Parameters;
	?>
	
	</div> <!--! end of #container -->


	<!-- Javascript at the bottom for fast page loading -->
	
	<script type="text/javascript">
		$('.showcase_thumbnail_item').tinyTips('yellow','title');
	</script>
	
	<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script>!window.jQuery && document.write('<script src="/js/jquery-1.4.2.min.js"><\/script>')</script>
	
	
	<script src="/js/plugins.js?v=1"></script>
	<script src="/js/script.js?v=1"></script>
	
	<!--[if lt IE 7 ]>
	<script src="/js/dd_belatedpng.js?v=1"></script>
	<![endif]-->
	
	
	<!-- asynchronous google analytics: mathiasbynens.be/notes/async-analytics-snippet 
	   change the UA-XXXXX-X to be your site's ID 
	<script>
	var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
	(function(d, t) {
	var g = d.createElement(t),
		s = d.getElementsByTagName(t)[0];
	g.async = true;
	g.src = '//www.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g, s);
	})(document, 'script');
	</script>-->
  
</body>
</html>
<?php
break;
endswitch; 

function sort_news_articles($a, $b) {
	return ($a['modify_date'] > $b['modify_date']) ? -1 : 1;
}
?>
