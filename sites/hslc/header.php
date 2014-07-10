<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>PowerLibrary</title>
	<link rel="stylesheet" href="css/all.css"/>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
</head>
<body>
	<div id="header_shadow_left" class="shadow">
		<div id="header_shadow_right" class="shadow">
			<div id="header">
				<a id="logo" href="index.php">Home</a>
				<div id="login_box_wrapper">
					<div id="login_box_shadow_left" class="login_box_shadow">
						<div id="login_box_shadow_right" class="login_box_shadow">
							<div id="login_box">
									<a id="librarian_login" class="librarian_login utility_link" href="#">
									<img id="librarian_login_hover" class="librarian_login utility_link_hover" src="images/librarian_login_hover.png" width="171" height="60"/>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div id="contact_us_wrapper">
					<a id="contact_us" class="contact_us utility_link" href="#">
						<img id="contact_us_hover" class="contact_us utility_link_hover" src="images/contact_us_hover.png" width="101" height="36"/>
					</a>
				</div>
				<div id="tagline_wrapper">
					<div id="tagline_shadow_top_right">
						<div id="tagline_shadow_top"></div>
					</div>
					<div id="tagline_shadow_right">
						<div id="logo_shadow">
							<div id="tagline">
								<img id="tagline_text" src="images/tagline.png" width="357" height="30"/>
							</div>
						</div>
					</div>
				</div>
				<? if(strpos($_SERVER['PHP_SELF'],'find.php')===false):?>
				<div id="search" class="home">
					<div id="container_left" class="bookend">
						<div id="container_right" class="bookend">
							<div id="container">
								<div id="textbox_left" class="bookend">
									<div id="textbox_right" class="bookend">
										<div id="textbox_container">
											<input id="home_search_textbox" type="text" value="FIND IT..."/>
										</div>
									</div>
								</div>
								<a id="go_button" href="#">Go</a>
							</div>
						</div>
					</div>
				</div>
				<? endif?>
				<div id="header_shadow_bottom_right">
					<div id="header_shadow_bottom"></div>
				</div>
			</div>
		</div>
		</div>
	</div>
	<div id="header_spacer_shadow_left" class="shadow">
		<div id="header_spacer_shadow_right" class="shadow">
			<div id="header_spacer">
				<div id="logo_shadow"></div>
				<div id="fill" class="fill"></div>
			</div>
		</div>
	</div>
	<div id="container_shadow_left" class="shadow">
		<div id="container_shadow_right" class="shadow">
			<div id="container">
				<div id="container_spacer">
					<div id="logo_shadow"></div>
					<div id="fill" class="fill"></div>
				</div>
				<div id="primary_nav">
					<!--[if IE 7]><table cellspacing="0" cellpadding="0"><tr><td><![endif]-->
					<div id="about_us">
						<a id="about_us_link" href="about.php">About Us</a>
					</div><!--[if IE 7]></td><td>
					<![endif]--><div id="find_it_pa" class="large_button">
						<div id="find_it_pa_bookend_left" class="bookend left">
							<div id="find_it_pa_bookend_right" class="bookend right">
								<div id="find_it_pa_container" class="background">
									<a id="find_it_pa_link" href="find.php">Find It PA</a>
								</div>
							</div>
						</div>
					</div><!--[if IE 7]></td><td>
					<![endif]--><div id="ask_here_pa" class="large_button">
						<div id="ask_here_pa_bookend_left" class="bookend left">
							<div id="ask_here_pa_bookend_right" class="bookend right">
								<div id="ask_here_pa_container" class="background">
									<a id="ask_here_pa_link" href="ask.php">Ask Here PA</a>
								</div>
							</div>
						</div>
					</div>
					<!--[if IE 7]></td></tr></table><![endif]-->
				</div>