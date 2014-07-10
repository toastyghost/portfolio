<?include('header.php')?>
<div id="content" class="find">
	<div id="content_top_gradient"></div>
	<div id="books_overlay"></div>
	<? if($_REQUEST['auth']=='true'):?>
	<div id="find_it_search">
		<div id="find_it_search_left" class="bookend">
			<div id="find_it_search_right" class="bookend">
				<div id="container">
					<div id="find_it_search_textbox_left" class="bookend">
						<div id="find_it_search_textbox_right" class="bookend">
							<div id="textbox_container">
								<input type="text" id="find_it_search_textbox" value="FIND IT..."/>
							</div>
						</div>
					</div>
					<a id="find_it_go_button" href="#">Go</a>
					<div id="search_filter">
						Browse by 
						<div class="custom_checkbox">
							<input id="check_subject" name="check_subject" class="checkbox" type="checkbox" value="subject"/>
							<label for="check_subject"><a class="graphic"></a> SUBJECT</label>
						</div> or see 
						<div class="custom_checkbox">
							<input id="check_all" name="check_all" class="checkbox" type="checkbox" value="all"/>
							<label for="check_all"><a class="graphic"></a> ALL</label>
						</div> databases
					</div>
				</div>
			</div>
		</div>
	</div>
	<? else:?>
	<div id="find_it_login_box">
		<div id="find_it_login_box_inner">
			<img class="alert_icon" src="images/alert_icon.png"/><span class="alert">You need to log in before continuing&hellip;</span><br/>
			<h3 class="field_label">County</h3>
			<div class="custom_field select_box">
				<select id="select_county" name="select_county" class="select">
					<option value=""></option>
					<? for($i=0;$i<7;$i++):?>
					<option value="<?=$i?>">Drop Down Menu Item <?=$i+1?></option>
					<? endfor;?>
				</select>
				<div class="select_shadow top left side">
					<div class="select_shadow top right side">
						<div class="select_shadow top middle"></div>
					</div>
				</div>
				<div class="select_shadow_left">
					<div class="select_shadow_right">
						<a id="select_closed_county" class="select_closed" href="javascript:"></a>
					</div>
				</div>
				<div class="select_shadow bottom left side">
					<div class="select_shadow bottom right side" id="change_library">
						<div class="select_shadow bottom middle"></div>
					</div>
				</div>
				<div id="select_open_county" class="select_open">
					<div class="select_shadow_left">
						<div class="select_shadow_right inner">
							<div class="select_open_inner">
								<ul id="select_graphical_county" class="select_graphical">
									<? for($i=0;$i<7;$i++):?>
									<li class="test" id="item_<?=$i?>"><a id="link_<?=$i?>" href="javascript:">Drop Down Menu Item <?=$i+1?></a></li>
									<? endfor;?>
								</ul>
							</div>
						</div>
					</div>
					<div class="select_shadow bottom left side">
						<div class="select_shadow bottom right side">
							<div class="select_shadow bottom middle"></div>
						</div>
					</div>
				</div>
			</div>
			<h3 class="field_label">Library</h3>
			<div class="custom_field select_box">
				<select id="select_library" name="select_library" class="select">
					<option value=""></option>
					<? for($i=0;$i<7;$i++):?>
					<option value="<?=$i?>">Drop Down Menu Item <?=$i+1?></option>
					<? endfor;?>
				</select>
				<div class="select_shadow top left side">
					<div class="select_shadow top right side">
						<div class="select_shadow top middle"></div>
					</div>
				</div>
				<div class="select_shadow_left">
					<div class="select_shadow_right">
						<a id="select_closed_library" class="select_closed" href="javascript:"></a>
					</div>
				</div>
				<div class="select_shadow bottom left side">
					<div class="select_shadow bottom right side" id="change_library">
						<div class="select_shadow bottom middle"></div>
					</div>
				</div>
				<div id="select_open_library" class="select_open">
					<div class="select_shadow_left">
						<div class="select_shadow_right inner">
							<div class="select_open_inner">
								<ul id="select_graphical_library" class="select_graphical">
									<? for($i=0;$i<7;$i++):?>
									<li class="test" id="item_<?=$i?>"><a id="link_<?=$i?>" href="javascript:">Drop Down Menu Item <?=$i+1?></a></li>
									<? endfor;?>
								</ul>
							</div>
						</div>
					</div>
					<div class="select_shadow bottom left side">
						<div class="select_shadow bottom right side">
							<div class="select_shadow bottom middle"></div>
						</div>
					</div>
				</div>
			</div>
			<h3 class="field_label">Library Card Number</h3>
			<div id="card_number_textbox_wrapper" class="custom_textbox">
				<div class="textbox_left bookend">
					<div class="textbox_right bookend">
						<div id="card_number_textbox_container" class="container">
							<input id="card_number" name="card_number"/>
						</div>
					</div>
				</div>
			</div>
			<a id="submit_button" href="find.php?auth=true">Submit</a>
		</div>
	</div>
	<? endif;?>
</div>
<?include('footer.php')?>