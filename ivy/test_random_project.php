<form id="form1" name="form1" method="post" action="test_random_project.php">
	<select id="service" name="service">
		<option value="1"<?=($_POST['service']==1)?' selected="selected"':''?>>Strategy</option>
		<option value="2"<?=($_POST['service']==2)?' selected="selected"':''?>>Branding</option>
		<option value="3"<?=($_POST['service']==3)?' selected="selected"':''?>>Communications</option>
		<option value="4"<?=($_POST['service']==4)?' selected="selected"':''?>>Print</option>
		<option value="5"<?=($_POST['service']==5)?' selected="selected"':''?>>Broadcast</option>
		<option value="6"<?=($_POST['service']==6)?' selected="selected"':''?>>Web</option>
	</select>
	<input type="submit"/>
</form>
<?
	if($_POST['service']){
		include($_SERVER['DOCUMENT_ROOT'].'/site_includes/random_portfolio_image.php');
		echo '<br/>';
		include($_SERVER['DOCUMENT_ROOT'].'/site_includes/random_portfolio_image.php');
		echo '<br/>';
		include($_SERVER['DOCUMENT_ROOT'].'/site_includes/random_portfolio_image.php');
	}
?>