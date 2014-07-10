<style>
	pre {
		text-align:left;
		background-color:white;
	}
</style>
<?
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/sandbox_database_utilities.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/site_wide_utilities.inc.php');

	$db = new database();
	$db->get_connection_info($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv');
	$db->connect();

	$sql = "select * from Tables where id = ".$The_View->Local_Data['form_id'];
	$table = $db->fetch($sql);
	$table = $table[0]['table_name'];

	if($The_View->Local_Data['sort_order']=='ASCENDING'){
		$sort_order = 'asc';
	}else{
		$sort_order = 'desc';
	}
	$sql = "select * from ".$table." order by ".$The_View->Local_Data['sort_field']." ".$sort_order;
	$images = $db->fetch($sql);
?>
<link rel="stylesheet" href="/mimik/mimik_plugins/gallery/style.css" type="text/css" media="screen" />
<script type="text/javascript">var _siteRoot='index.php',_root='index.php';</script>
<script type="text/javascript" src="/mimik/mimik_js/jquery.js"></script>
<script type="text/javascript" src="/mimik/mimik_plugins/gallery/js/scripts.js"></script>
<div id="header">
	<div class="wrap">
		<div id="slide-holder">
			<div id="slide-runner">
				<?
					ob_start;
					$i=0;
					foreach($images as $image){
						$slider[$i]['id'] = $image['id'];
						$slider[$i]['client'] = $The_View->Local_Data['display_name'];
						$slider[$i]['desc'] = $image[$The_View->Local_Data['title_field']];
						$slider[$i]['filename'] = $image[$The_View->Local_Data['image_field']];
						echo '<a href="/mimik/mimik_uploads/',$slider[$i]['filename'],'" target="_blank"><img id="',$slider[$i]['id'],'" src="/mimik/mimik_uploads/',$slider[$i]['filename'],'" class="slide" alt="',$slider[$i]['desc'],'" /></a>';
						$i++;
					}
					ob_end_flush;
				?>
				<div id="slide-controls">
					<p id="slide-client" class="text"><strong><span></span></strong></p>
					<p id="slide-desc" class="text"></p>
					<p id="slide-nav"></p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			if(!window.slider) var slider={};
			slider.data=<?=json_encode($slider)?>;
		</script>
	</div>
</div>