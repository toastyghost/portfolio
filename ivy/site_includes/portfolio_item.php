<?
	if($_POST['record_id']) $The_View_Parameters['record_id'] = $_POST['record_id'];
	include($_SERVER['DOCUMENT_ROOT'].'/site_includes/build_portfolio.php');
	
	$run = true;
	foreach ($The_Portfolio_Items as $The_Portfolio_Item_Index => $The_Portfolio_Item) :
		if($run){
			echo '<div class="showcase_item';
			if ($The_Portfolio_Item_Index === 0) echo ' first';
			echo '" id="showcase_item_',$The_Portfolio_Item['id'],'"';
			if ($The_Portfolio_Item_Index !== 0) echo ' style="display:none;opacity:0;"';
			echo '>';
			
			if ($The_Portfolio_Item['portfolio_video']) :
				include $THE_BASE_URL.'/mimik/mimik_live_data/view.php?id=38&record_id='.$The_Portfolio_Item['portfolio_video'];
			elseif ($The_Portfolio_Item['portfolio_image']) :
				echo '<img width="545" height="420" class="portfolio_item_image" src="/mimik/mimik_uploads/',$The_Portfolio_Item['portfolio_image'],'" alt="',$The_Portfolio_Item['title'],'"/>';
			elseif ($The_Portfolio_Item['description']) :
				echo '<div class="portfolio_item_text">' . $The_Portfolio_Item['description'] . '</div>';
			else :
				echo '<div class="portfolio_item_text">' . $The_Portfolio_Item['title'] . '</div>';
			endif;
			
			echo '<div class="portfolio_item_information"><div class="background_gradient"></div><div class="inner_wrapper">',
				 '<h2 class="portfolio_item_title">',$The_Portfolio_Item['title'],'</h2>',
				 '<h3 class="portfolio_item_client_title">',$The_Portfolio_Item['client_name'],'</h3>';
			
			if($The_Portfolio_Item['portfolio_service']) echo '<p>Service: ',$The_Portfolio_Item['portfolio_service'],'<br/>';
			if($The_Portfolio_Item['client_organization_type']) echo 'Org type: ',$The_Portfolio_Item['client_organization_type'],'<br/>';
			if($The_Portfolio_Item['client_industry']) echo 'Industry: ',$The_Portfolio_Item['client_industry'],'</p>';	
			if($The_Portfolio_Item['description']) echo $The_Portfolio_Item['description'];
			
			if($The_Portfolio_Item['case_study']){
				echo '<br/><br/><h3>Case Study: ',$The_Portfolio_Item['case_study']['title'],'</h3>',
				$The_Portfolio_Item['case_study']['teaser'],$The_Portfolio_Item['case_study']['rest_of_article'],'<br/><br/>';
				
				for($i=1;$i<4;++$i){
					if($The_Portfolio_Item['case_study']['image_'.$i]){
						echo '<img src="/mimik/mimik_uploads/',$The_Portfolio_Item['case_study']['image_'.$i],'" alt="',$The_Portfolio_Item['case_study']['image_'.$i.'_alt_text'],'"/><br/>',
						'<em>',$The_Portfolio_Item['case_study']['image_'.$i.'_caption'],'</em><br/><br/>';
					}
				}
			}
			
			echo '</div></div></div>';
			$run = false;
		}
	endforeach;
?>