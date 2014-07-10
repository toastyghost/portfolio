<?
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/sandbox_database_utilities.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/site_wide_utilities.inc.php');
	
	$db = new database();
	$db->get_connection_info($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv');
	$db->connect();
	
	$sql = '';
	$union = ' union ';
	if(!empty($_POST['filters'])){
		if($_POST['filters']['service']) $sql .= 'select name as service from mimik_Services where id = '.$_POST['filters']['service'];
		if($_POST['filters']['organization_type']){
			if($sql) $sql .= $union;
			$sql .= 'select name as organization_type from mimik_Organization_Types where id = '.$_POST['filters']['organization_type'];
		}
		if($_POST['filters']['industry']){
			if($sql) $sql .= $union;
			$sql .= 'select name as industry from mimik_Industries where id = '.$_POST['filters']['industry'];
		}
	}
	if($sql){
		$filters = $db->fetch($sql);
		debug($sql);
	}
?>