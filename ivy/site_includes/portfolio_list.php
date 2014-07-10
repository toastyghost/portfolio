<?
	header("Vary: *");
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	
	require_once('/var/www/vhosts/ivygroup.com/subdomains/redesign/httpdocs/mimik/mimik_configuration/the_system_settings.config.php');
	require_once($THE_BASE_SERVER_PATH.'/httpdocs/mimik/mimik_includes/sandbox_database_utilities.inc.php');
	
	if(empty($The_Portfolio_Items)) include($_SERVER['DOCUMENT_ROOT'].'/site_includes/build_portfolio.php');
	
	$filters = $_POST['filters'];
	$position = $_POST['position'];
	$is_ie7 = (strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')!==false);
	
	if($position != 0 && $position != 'NaN') echo '<script type="text/javascript">
		$(window).load(function(){
			$(".multimulti").each(function(){
				if($this.children("li:not(li:hidden)").length == 12){
					if(i<',$position,') ++i;
					else return false;
				}else return false;
				$this.css("left",parseInt($this.css("left"))-198*',$position,');
			});
			$this.css("left",0);
		});
	</script>';
	
	$i=$j=0;
	
	echo '<div id="thumbnail_frame">';
	ob_start();
	echo '<ul class="portfolio_thumbnail_list">';
	foreach($The_Portfolio_Items as $The_Portfolio_Item_Index => $The_Portfolio_Item){
		if((($filters['service'] == $The_Portfolio_Item['portfolio_service_id']) || $filters['service'] == '')
			&& (($filters['organization_type'] == $The_Portfolio_Item['client_organization_type_id']) || $filters['organization_type'] == '')
			&& (($filters['industry'] == $The_Portfolio_Item['client_industry_id']) || $filters['industry'] == '')
			|| $position === NULL /*|| $position == 'NaN'*/ || empty($filters)){
			if($i%12===0 && !$is_ie7){
				if($i>0) echo '</span>';
				echo '<span',(($i>0)?' style="left:'.(string)(198*$j).'px;"':''),' id="mm',$j,'" class="multimulti">';
				++$j;
			}
			$full_title = $The_Portfolio_Item['client_name'].' - '.$The_Portfolio_Item['title'];
			echo '<li title="',$full_title,'" class="showcase_thumbnail_item',
			(($The_Portfolio_Item['portfolio_service'])?' service_'.str_replace(' ','_',strtolower($The_Portfolio_Item['portfolio_service'])):NULL),
			(($The_Portfolio_Item['client_organization_type'])?' organization_type_'.str_replace(' ','_',strtolower($The_Portfolio_Item['client_organization_type'])):NULL),
			(($The_Portfolio_Item['client_industry'])?' industry_'.str_replace(' ','_',strtolower($The_Portfolio_Item['client_industry'])):NULL),
			'" id="showcase_thumbnail_',$The_Portfolio_Item['id'],'"><a href="#portfolio_item_',$The_Portfolio_Item['id'],'">';
			if($The_Portfolio_Item['portfolio_thumbnail']) echo '<img width="60" height="60" src="/mimik/mimik_uploads/',$The_Portfolio_Item['portfolio_thumbnail'],'" alt="',$full_title,'" />';
			else echo '<img width="60" height="60" src="/images/ivy-leaf-black-60x60.png" alt="',$full_title,'" />';
			echo '</a></li>';
			++$i;
		}
	}
	echo '</span></ul>';
	if($i!==0) ob_end_flush();
	else{
		ob_end_clean();
		echo '<p style="color:#777">No results matching your criteria were found.  Please try a different set of filters.</p><script type="text/javascript">var filters = {\'service\':\'\',\'organization_type\':\'\',\'industry\':\'\'};$(\'.filter_link.selected\').removeClass(\'selected\');</script>';
	}
	echo '</div>';
	
	#jdc 11/17/10 - old list generation... deleting as soon as i figure out what's up with css not being applied to ajax responses in ie8
	/*echo '<div id="thumbnail_frame"><ul class="portfolio_thumbnail_list">';
	foreach ($The_Portfolio_Items as $The_Portfolio_Item_Index => $The_Portfolio_Item) :
		echo '<li class="showcase_thumbnail_item" id="showcase_thumbnail_' . $The_Portfolio_Item['id'] . '"><a href="#portfolio_item_' . $The_Portfolio_Item_Index . '">';
		if ($The_Portfolio_Item['portfolio_thumbnail']) :
			echo '<img src="/mimik/mimik_uploads/' . $The_Portfolio_Item['portfolio_thumbnail'] . '" alt="' . $The_Portfolio_Item['title'] . '" />';
		else :
			echo '<img src="/images/ivy-leaf-black-60x60.png" alt="' . $The_Portfolio_Item['title'] . '" />';
		endif;
		echo '</a></li>';
	endforeach;
	echo '</ul></div>';*/
?>