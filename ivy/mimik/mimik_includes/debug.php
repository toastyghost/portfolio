<?
	require_once('../mimik_includes/json.inc.php');
	require_once('../mimik_includes/site_wide_utilities.inc.php');
	
	$JSON = new Services_JSON();
	$obj = $JSON->decode($_POST['json_string']);
	debug($obj);
?>