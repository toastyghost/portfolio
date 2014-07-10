<?
	if($The_Landing_Page['title'] == 'Services' || $The_Interior_Page['title'] == 'Services'){
		$num_chars = strlen(strip_tags($The_Landing_Page['content']));
		$number_of_height_increasing_elements = substr_count($The_Landing_Page['content'],'<p>')+substr_count($The_Landing_Page['content'],'<ul>')+substr_count($The_Landing_Page['content'],'<li>');
		$combined_height = (($num_chars/75-$number_of_height_increasing_elements)*21)+($number_of_height_increasing_elements*17)+115;
		$num_pics = $combined_height/210;
		echo '<div id="random_portfolio_link_container">';
		$i=0;
		while($i<$num_pics){
			include($_SERVER['DOCUMENT_ROOT'].'/site_includes/random_portfolio_image.php');
			++$i;
		}
		echo '</div>';
	}
?>