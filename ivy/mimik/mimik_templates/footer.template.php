<?php // establish the database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php');
$The_Database_To_Use = new A_Mimik_Database_Interface;
$The_Database_To_Use->Will_Connect_Using_The_Information_In( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/database_connection_info.csv' );
$The_Database_To_Use->Establishes_A_Connection();

// overwrite The_View_Parameters array with REQUEST (GET/POST) arrays if applicable
if (is_array($_REQUEST) && !is_array($The_View_Parameters)) :
	foreach ($_REQUEST as $The_Request_Parameter => $The_Request_Parameter_Value) :
		$The_View_Parameters[$The_Request_Parameter] = $The_Request_Parameter_Value;
	endforeach;
endif;

$The_Submissions = $The_Database_To_Use->Gets_The_Template_Data_For_The_View(THE_FOOTER_VIEW_ID, $The_View_Parameters['record_id'], $The_View_Parameters['param']);
$The_Submission = $The_Submissions[0];

$The_Footer = array();

$The_Footer['id'] = $The_Submission->ID;
$The_Footer['create_date'] = $The_Submission->Create_Date;
$The_Footer['modify_date'] = $The_Submission->Modify_Date;
$The_Footer['creator_user'] = $The_Submission->Creator_User;
$The_Footer['modifier_user'] = $The_Submission->Modifier_User;

if ($The_Submission->Local_Values_Array[0]->Data) $The_Footer['title'] = $The_Submission->Local_Values_Array[0]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[1]->Data) $The_Footer['logo'] = $The_Submission->Local_Values_Array[1]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[2]->Data) $The_Footer['copyright_holder'] = $The_Submission->Local_Values_Array[2]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[3]->Data) $The_Footer['copyright_text'] = $The_Submission->Local_Values_Array[3]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[4]->Data) $The_Footer['phone_1_label'] = $The_Submission->Local_Values_Array[4]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[5]->Data) $The_Footer['phone_1_number'] = $The_Submission->Local_Values_Array[5]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[6]->Data) $The_Footer['phone_2_label'] = $The_Submission->Local_Values_Array[6]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[7]->Data) $The_Footer['phone_2_number'] = $The_Submission->Local_Values_Array[7]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[8]->Data) $The_Footer['email_address'] = $The_Submission->Local_Values_Array[8]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[9]->Data) $The_Footer['menu_id'] = $The_Submission->Local_Values_Array[9]->Data->ID;
if ($The_Submission->Local_Values_Array[10]->Data) $The_Footer['phone_3_label'] = $The_Submission->Local_Values_Array[10]->Live_Site_HTML_For_The_Data();
if ($The_Submission->Local_Values_Array[11]->Data) $The_Footer['phone_3_number'] = $The_Submission->Local_Values_Array[11]->Live_Site_HTML_For_The_Data();

$The_Footer['!mimik_logo_color'] = $The_View_Parameters['!mimik_logo_color'];

switch ($The_Footer['id']) :

/***** ivygroup *****/

case '1' :
?>
<div id="footer">
	<div id="footer_top"></div>
	<div id="footer_body">
		<div id="footer_content">
			<div id="footer_content_wrapper">
				<img width="180" height="140" src="/mimik/mimik_uploads/<?=$The_Footer['logo']?>" alt="The Ivy Group"/>
				<p id="copy">
					&copy; <?=date('Y')?> <?=strtoupper($The_Footer['copyright_holder'])?><br/>
					<?=strtoupper($The_Footer['copyright_text'])?><br />
					<?php // auxiliary menu
						$The_Temp_View_Parameters = $The_View_Parameters;
						unset($The_View_Parameters);
						$The_View_Parameters['id'] = THE_MENU_VIEW_ID;
						$The_View_Parameters['record_id'] = $The_Footer['menu_id'];
						$The_View_Parameters['!case'] = 'upper';
						$The_View_Parameters['!no_list'] = true;
						$The_Temp_Footer = $The_Footer;
						include($THE_BASE_SERVER_PATH . '/httpdocs/mimik/mimik_live_data/view_data.php');
						$The_Footer = $The_Temp_Footer;
						$The_View_Parameters = $The_Temp_View_Parameters;
					?>
				</p>
				<p id="contact_info">
					<span class="heading">CONTACT INFO</span><br/>
					<?php if ($The_Footer['phone_1_number']) : 
						echo $The_Footer['phone_1_number']?> <span class="smaller"><?=$The_Footer['phone_1_label']?></span><br/>
					<?php endif;
					if ($The_Footer['phone_2_number']) : 
						echo $The_Footer['phone_2_number']?> <span class="smaller"><?=$The_Footer['phone_2_label']?></span><br/>
					<?php endif;
					if ($The_Footer['phone_3_number']) : 
						echo $The_Footer['phone_3_number']?> <span class="smaller"><?=$The_Footer['phone_3_label']?></span><br/>
					<?php endif;
					echo munge($The_Footer['email_address'], false);?>
				</p>
				<ul id="footer_social_media">
					<li><a href="http://facebook.com/TheIvyGroup" target="_blank" id="facebook_link">Follow us on Facebook</a></li>
				</ul>
				<p id="mimik">
					THIS SITE IS POWERED BY<br/>
					<?
						$sql = "select replace(lcase(name),' ','_') as name,rgb_code,lcase(section) as section from mimik_Colors";
						$colors = $The_Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($sql);
						$logo_color_lower = str_replace(' ','_',strtolower($The_Footer['!mimik_logo_color']));
						$page_bg_colors = '<script type="text/javascript">page_bg_colors = {';
						$color_count = count($colors);
						$i=1;
						foreach($colors as $color){
							echo '<img width="89" height="83" id="mimik_logo_',$color['name'],'" style="position:absolute;opacity:',(($logo_color_lower==$color['name'])?1:0),';display/*\**/:none\9;*position:static;" src="/images/mimik_logo_',$color['name'],'.png" alt="MIMIK IMS"/>';
							$page_bg_colors .= "'$color[section]':{'code':'#$color[rgb_code]','name':'$color[name]'}".(($i<$color_count)?',':'');
							++$i;
						}
						$page_bg_colors .= '};</script>';
						echo $page_bg_colors;
					?>
				</p>
			</div>
		</div>
	</div>
	<div id="footer_shadow"></div>
</div>
<?php
break;

/***** ivylibrary *****/

case '2' :

?>
<footer>
	<a href="http://redesign.ivygroup.com/" title="The Ivy Group, Ltd."><img src="/mimik/mimik_uploads/<?=$The_Footer['logo']?>" alt="The Ivy Group" /></a>
	<p id="copy">
		&copy; <?=date('Y')?> <?=strtoupper($The_Footer['copyright_holder'])?><br/>
					<?=strtoupper($The_Footer['copyright_text'])?><br />
	</p>
	<p id="contact_info">
		<span class="heading">Contact Info</span><br/>
		<?php if ($The_Footer['phone_1_number']) : 
			echo $The_Footer['phone_1_number']?> <span class="smaller"><?=$The_Footer['phone_1_label']?></span><br/>
		<?php endif;
		if ($The_Footer['phone_2_number']) : 
			echo $The_Footer['phone_2_number']?> <span class="smaller"><?=$The_Footer['phone_2_label']?></span><br/>
		<?php endif;
		if ($The_Footer['phone_3_number']) : 
			echo $The_Footer['phone_3_number']?> <span class="smaller"><?=$The_Footer['phone_3_label']?></span><br/>
		<?php endif;
		echo munge($The_Footer['email_address'], false);?>
	</p>
	<p id="mimik">
		This site is powered by<br/>
		<a href="http://redesign.ivygroup.com/web/mimik/" title="MIMIK Information Management System : The Ivy Group, Ltd."><img id="mimik_logo_red" src="/images/mimik_logo_red.png" alt="MIMIK IMS"/></a>
	</p>
	<br class="clear" />
</footer>

<?php
break;
endswitch; ?>