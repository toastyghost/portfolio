<?
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/sandbox_database_utilities.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/site_wide_utilities.inc.php');
	
	$mdb = new database();
	$mdb->get_connection_info($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv');
	$mdb->connect();
	
	
	if($The_Landing_Page['title'] == 'Services' || $The_Interior_Page['title'] == 'Services'){
		$num_chars = strlen(strip_tags($The_Landing_Page['content']));
		$number_of_height_increasing_elements = substr_count($The_Landing_Page['content'],'<p>')+substr_count($The_Landing_Page['content'],'<ul>')+substr_count($The_Landing_Page['content'],'<li>');
		$combined_height = (($num_chars/75-$number_of_height_increasing_elements)*21)+($number_of_height_increasing_elements*17)+115;
		$num_pics = $combined_height/210;
		echo '<div id="random_portfolio_link_container">';
		$i=0;
		while($i<$num_pics){
			$additional_sql = '';
			if(!is_array($simultaneous_projects) || empty($simultaneous_projects)) $simultaneous_projects = array();
			else $additional_sql .= ' and id not in('.implode(',',$simultaneous_projects).')';
			if($project_filter_info['service']) $project_filter_sql = ' service = '.$project_filter_info['service'].' and';
			$random_project = $mdb->fetch("select id,title,portfolio_image from (select id,title,portfolio_image from mimik_Projects where".$project_filter_sql." display = 'Yes' and appear_on_ivy_group = 'Yes' and portfolio_image <> ''".$additional_sql.") as p1 order by rand() limit 1");
			if(!empty($random_project)){
				$random_project = $random_project[0];
				if(!in_array($random_project['id'],$simultaneous_projects)) array_push($simultaneous_projects,$random_project['id']);
				$client_name = $mdb->fetch("select name from mimik_Clients c left join mimik_Projects p on c.id = p.client where p.id = ".$random_project['id']);
				$client_name = $client_name[0]['name'];
				$full_title = $client_name.' - '.$random_project['title'];
				//jdc 1/27/11 - TODO: if resampled image doesn't exist in cache, create it.  (what size should images be on services pages?)
				echo '<div><a class="random_portfolio_link" title="',$full_title,'" href="/portfolio#portfolio_item_',$random_project['id'],'"><img class="random_portfolio_image" width="272.5" height="210" src="/mimik/mimik_uploads/',$random_project['portfolio_image'],'" alt="',$full_title,'"/></a></div>';
			}
		}
		echo '</div>';
	}
		
	$mdb->disconnect();
	unset($mdb);
?>