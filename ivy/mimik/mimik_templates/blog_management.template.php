<?php // establish the database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();

?>
<div class="blog_wrapper">
	<div class="blog_sidebar">
		<div class="blog_category_listings_wrapper">
		<?php // blog category listings
			$The_Temp_View_Parameters = $The_View_Parameters;
			unset($The_View_Parameters);
			$The_View_Parameters['id'] = THE_BLOG_CATEGORY_LISTINGS_VIEW_ID;
			$The_Temp_Blog = $The_Blog;
			unset($The_Blog);
			include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
			$The_Blog = $The_Temp_Blog;
			$The_View_Parameters = $The_Temp_View_Parameters;
		?>
		</div>
		<div class="blog_archive_listings_wrapper">
		<?php // blog archive listings
			$The_Temp_View_Parameters = $The_View_Parameters;
			unset($The_View_Parameters);
			$The_View_Parameters['id'] = THE_BLOG_ARCHIVE_LISTINGS_VIEW_ID;
			$The_Temp_Blog = $The_Blog;
			unset($The_Blog);
			include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
			$The_Blog = $The_Temp_Blog;
			$The_View_Parameters = $The_Temp_View_Parameters;
		?>
		</div>
	</div>
	<div class="blog_posts_wrapper">
	<?php // blog posts
		$The_Temp_View_Parameters = $The_View_Parameters;
		unset($The_View_Parameters);
		$The_View_Parameters['id'] = THE_BLOG_POSTS_VIEW_ID;
		$The_View_Parameters['param'] = $_REQUEST['param'];
		$The_View_Parameters['!tag'] = $_REQUEST['tag'];
		$The_Temp_Blog = $The_Blog;
		unset($The_Blog);
		include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
		$The_Blog = $The_Temp_Blog;
		$The_View_Parameters = $The_Temp_View_Parameters;
	?>
	</div>
</div>
<div class="clear"></div>