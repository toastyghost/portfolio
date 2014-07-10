<? $num=10?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="home" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>The Ivy Group, Ltd.</title>
	<link type="text/css" rel="stylesheet" href="css/all.css"/>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript" src="js/color.js"></script>
	<script type="text/javascript">
		page_bg_colors = {'home':'#47818a','about':'#003663','services':'#8c001d','portfolio':'#a1987c','contact':'#6f4a6c'};
	</script>
	<script type="text/javascript" src="js/custom.js"></script>
	<!-- TODO: REMOVE THIS BEFORE LAUNCH... FOR DEBUGGING PURPOSES ONLY -->
	<script type="text/javascript" src="js/dump.js"></script>
	<!-- END TODO -->
</head>
<body>
	<img id="clouds" src="images/clouds.png"/>
	<div id="gradient"></div>
	<div id="gradient_back"></div>
	<div id="header_outer">
		<div id="primary_nav_left_margin"></div>
		<div id="primary_nav_right_margin"></div>
		<div id="primary_nav_bottom_shadow"></div>
		<div id="header_inner">
			<ul id="utility_nav">
				<li><div id="blog"><a id="blog_link" href="#">blog</a></div></li>
				<li><div id="login"><a id="login_link" href="#">login</a></div></li>
			</ul>
			<div class="utility nav_hover" id="blog_hover"></div>
			<div class="utility nav_hover" id="login_hover"></div>
			<div id="logo_container"><a id="home_link" href="#"><img src="images/logo.png"/></a></div>
			<ul id="primary_nav">
				<li id="about"><a id="about_link" href="#">about</a></li>
				<li id="services"><a id="services_link" href="#">services</a></li>
				<li id="center_spacer"></li>
				<li id="portfolio"><a id="portfolio_link" href="#">portfolio</a></li>
				<li id="contact"><a id="contact_link" href="#">contact</a></li>
			</ul>
			<div class="primary nav_hover" id="about_hover"></div>
			<div class="primary nav_hover" id="services_hover"></div>
			<div class="primary nav_hover" id="home_hover"></div>
			<div class="primary nav_hover" id="portfolio_hover"></div>
			<div class="primary nav_hover" id="contact_hover"></div>
		</div>
		<div id="secondary_navigation_container">
			<ul id="secondary_navigation">
				<!-- -->
			</ul>
		</div>
		<div id="content_shadow_left">
			<div id="content_shadow_right">
				<div id="page_body">
					<div id="content_wrapper">
						<div id="welcome">
							<h1>Welcome</h1>
							<p>Established in 1989, The Ivy Group, Ltd. provides the full spectrum of marketing consulting and support services—including market research, branding, advertising, graphic design, website design, website hosting, content management, and public relations. Partners Pam Fitzgerald and Nancy Davis manage offices in downtown Charlottesville (VA) and Philadelphia (PA).</p>
						</div>
						<div id="showcase">
							<div id="photo_frame">
								<ul id="photos" style="width:<?=550*$num?>px;">
									<?
										$current = ' current';
										for($i=1;$i<=$num;$i++){
											$j = sprintf('%02d',$i);
											echo '<li><div class="photo',$current,'" id="photo',$j,'" style="background:url(images/placeholder.jpg) no-repeat;"><div class="overlay" id="overlay',$j,'"><h1>STORY HEADING</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent nunc mauris, dignissim id elementum sit amet, sodales a felis. Justo, quis purus metus amet, consectetur adipiscing dolor sit amet&hellip; <a class="more" href="#">read more</a></p></div></div></li>';
											if($current)unset($current);
										}// multiline version in html comment below
									?>
									<!--<li><div class="photo" id="photo##" style="background:url(images/placeholder.jpg) no-repeat;">
										<div class="overlay" id="overlay##">
											<h1>STORY HEADING</h1>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent nunc mauris, dignissim id elementum sit amet, sodales a felis. Justo, quis purus metus amet, consectetur adipiscing dolor sit amet&hellip; <a class="more" href="#">read more</a></p>
										</div>
									</div></li>-->
								</ul>
							</div>
							<div id="navigation">
								<div class="arrow" id="left_arrow">
									<img src="images/showcase_arrow_left_hover.png"/>
								</div>
								<div id="thumbnail_frame">
									<ul id="thumbs">
										<? for($i=1;$i<=$num;$i++) echo '<li><a id="thumb',sprintf('%02d',$i),'" href="javascript:"><img src="images/placeholder_thumb.png"/></a></li>'?>
									</ul>
								</div>
								<div class="arrow" id="right_arrow">
									<img src="images/showcase_arrow_right_hover.png"/>
								</div>
								<div id="highlight_bracket"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div id="footer_top"></div>
			<div id="footer_body">
				<div id="footer_content">
					<div id="footer_content_wrapper">
						<img src="images/logo_white.png"/>
						<p id="copy">
							&copy; 2010 THE IVY GROUP, LTD.<br/>
							THE IVY GROUP DESIGNED, PROGRAMMED, PRODUCED AND CONGRATULATED ITSELF FOR THIS SITE.<br/>
							<a href="#">SITE MAP</a>
						</p>
						<p id="contact">
							<span class="heading">CONTACT INFO</span><br/>
							(434) 979-2678 <span class="smaller">Charlottesville</span><br/>
							(800) IVY-1250 <span class="smaller">Toll-Free</span><br/>
							<a href="mailto:contact@ivygroup.com">contact@ivygroup.com</a>
						</p>
						<p id="mimik">
							THIS SITE IS POWERED BY<br/>
							<img src="images/mimik_logo_home.png"/>
						</p>
					</div>
				</div>
			</div>
			<div id="footer_shadow"></div>
		</div>
	</div>
</body>
</html>