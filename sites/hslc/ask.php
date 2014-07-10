<?include('header.php')?>
<div id="content" class="ask">
	<div id="content_top_gradient"></div>
	<div id="light_rays_background"></div>
	<!--[if IE 7]><table cellspacing="0" cellpadding="0"><tr><td valign="top"><![endif]-->
	<div id="librarian_heading">
		<? if($_REQUEST['auth']=='true'):?>
		<div id="chat_box"></div>
		<div id="chat_textbox_wrapper" class="custom_textbox">
			<div class="textbox_left bookend">
				<div class="textbox_right bookend">
					<div id="chat_textbox_container" class="container">
						<input id="chat_message" name="chat message"/>
					</div>
				</div>
			</div>
		</div>
		<a id="submit_button" href="#">Submit</a>
		<a id="exit" class="chat button" href="#">Exit</a>
		<? else:?>
		<div id="chat_login_box">
			<img class="alert_icon" src="images/alert_icon.png"/>
			<span class="alert">
				Please enter the following and click the "CONNECT" button.<br/>
				<img class="required" src="images/required.png"/> = Required information
			</span><br/>
			<h3 class="required field_label"><img src="images/required.png"/> Enter a screen name for this session</h3>
			<div id="card_number_textbox_wrapper" class="custom_textbox">
				<div class="textbox_left bookend">
					<div class="textbox_right bookend">
						<div id="card_number_textbox_container" class="container">
							<input id="card_number" name="card_number"/>
						</div>
					</div>
				</div>
			</div>
			<h3 class="field_label">To receive a transcript of this session,<br/>
			type your email address below</h3>
			<div id="card_number_textbox_wrapper" class="custom_textbox">
				<div class="textbox_left bookend">
					<div class="textbox_right bookend">
						<div id="card_number_textbox_container" class="container">
							<input id="card_number" name="card_number"/>
						</div>
					</div>
				</div>
			</div>
			<h3 class="field_label">Confirm email address</h3>
			<div id="card_number_textbox_wrapper" class="custom_textbox">
				<div class="textbox_left bookend">
					<div class="textbox_right bookend">
						<div id="card_number_textbox_container" class="container">
							<input id="card_number" name="card_number"/>
						</div>
					</div>
				</div>
			</div>
			<h3 class="required field_label"><img src="images/required.png"/> Enter your zip code</h3>
			<div id="card_number_textbox_wrapper" class="custom_textbox">
				<div class="textbox_left bookend">
					<div class="textbox_right bookend">
						<div id="card_number_textbox_container" class="container">
							<input id="card_number" name="card_number"/>
						</div>
					</div>
				</div>
			</div>
			<h3 class="required field_label"><img src="images/required.png"/> How may we help you?  Please type</h3>
			<h3 class="field_label second_line">full question</h3>
			<div id="card_number_textbox_wrapper" class="custom_textbox">
				<div class="textbox_left bookend">
					<div class="textbox_right bookend">
						<div id="card_number_textbox_container" class="container">
							<input id="card_number" name="card_number"/>
						</div>
					</div>
				</div>
			</div>
			<div id="button_wrapper">
				<!--[if IE 7]><table cellspacing="0" cellpadding="0"><tr><td valign="top"><![endif]-->
				<a id="exit" class="button" href="#">Exit</a>
				<!--[if IE 7]></td><td valign="top"><![endif]--><!--
				--><a id="connect" class="button" href="ask.php?auth=true">Connect</a>
				<!--[if IE 7]></td></tr></table><![endif]-->
			</div>
		</div>
		<? endif;?>
	</div><!--[if IE 7]></td><td valign="top"><![endif]--><!--
	--><div id="feature_box_faq" class="feature_box_wrapper right_column">
		<div class="feature_box corner top_left">
			<div class="feature_box corner top_right">
				<div class="feature_box horizontal side top middle"></div>
			</div>
		</div>
		<div class="feature_box vertical side left">
			<div class="feature_box vertical side right">
				<div class="feature_box middle content">
					<div class="feature_box inner">
						<h2>ASK HERE PA <span class="gold">FAQS</span></h2>
						<p>Lorem ipsum dolor sit amet elit.  Nullam convallis consectetur lacinia.  Integer venenatis suscipit egestas.  Morbo varius arcu sed leo aliquam porta.  Donec facilisis diam vel uma egestas porta sollicitudin.  Nullam convallis donec facilisis diam vel urna egestas Curabitur rutrum rutrum quam non urna arcu sed egestasconsectetur lacinia.  Integer venenatis suscipit egestast.  Morbi varius arcu sed leo aliquam porta.  Donec facilisis diam vel urna egestas rutrum consequat.  Lorem ipsum dolor sit amet elit.  Nullam convallis consectetur lacinia.  Integer venenatis egestas.  Morbi varius arcu sed leo aliquam porta.  Donec facilisis diam vel urna egestas rutrum egestas rutrum consequat.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="feature_box corner bottom_left">
			<div class="feature_box corner bottom_right">
				<div class="feature_box horizontal side bottom middle"></div>
			</div>
		</div>
	</div>
	<!--[if IE 7]></td></tr></table><![endif]-->
</div>
<?include('footer.php')?>