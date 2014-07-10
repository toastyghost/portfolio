function Confirm_Deletion_Of_The_Object(The_Input_Object_Type, The_Input_Object_ID, The_Input_Div_Name, The_Input_Parent_ID_Name)
{
	var The_Parent_Object_ID;
	var The_URL_Parameter_String = '';
	if (The_Input_Parent_ID_Name != undefined)
	{
		The_Parent_Object_ID = document.getElementById(The_Input_Parent_ID_Name).value;
	}
	Highlight_The_Div_To_Be_Deleted(The_Input_Div_Name);
	switch (The_Input_Object_Type)
	{
	case 'Value' :
		The_URL_Parameter_String += 'delete_value&value_id=' + The_Input_Object_ID;
		The_URL_Parameter_String += '&form_id=' + The_Parent_Object_ID;
		The_Global_Ajax_Target_ID = 'values_displayer';
		break;
	case 'Field' :
		The_URL_Parameter_String += 'delete_field&field_id=' + The_Input_Object_ID;
		The_URL_Parameter_String += '&form_id=' + The_Parent_Object_ID;
		The_Global_Ajax_Target_ID = 'fields_displayer';
		break;
	case 'User Custom Field' :
		The_URL_Parameter_String += 'delete_user_custom_field&field_id=' + The_Input_Object_ID;
		The_Global_Ajax_Target_ID = 'user_custom_fields_displayer';
		break;
	case 'Form' :
		The_URL_Parameter_String += 'delete_form&form_id=' + The_Input_Object_ID;
		The_Global_Ajax_Target_ID = 'forms_displayer';
		break;
	case 'Group' :
		The_URL_Parameter_String += 'delete_group&group_id=' + The_Input_Object_ID;
		The_Global_Ajax_Target_ID = 'groups_displayer';
		break;
	case 'Submission' :
		The_URL_Parameter_String += 'delete_submission&submission_id=' + The_Input_Object_ID;
		The_URL_Parameter_String += '&form_id=' + The_Parent_Object_ID;
		The_Global_Ajax_Target_ID = 'submissions_displayer';
		break;
	case 'User' :
		The_URL_Parameter_String += 'delete_user&user_id=' + The_Input_Object_ID;
		The_Global_Ajax_Target_ID = 'users_displayer';
		break;
	case 'View' :
		The_URL_Parameter_String += 'delete_view&view_id=' + The_Input_Object_ID;
		The_Global_Ajax_Target_ID = 'views_displayer';
		break;
	}
	if (confirm('Are you sure you want to delete the ' + The_Input_Object_Type + '?\nThis cannot be recovered.')) {
		xmlHttp = GetXmlHttpObject();
		var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
		url += '?support_function=';
		url += The_URL_Parameter_String;
		url += '&random=' + Math.random();
		xmlHttp.onreadystatechange=stateChanged;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
	Unhighlight_All_Children_Of_The_Div(The_Global_Ajax_Target_ID);
	return false;
}

function Create_The_Field_For_The_Form_ID(
								The_Field_Name_Div_Name, 
								The_Field_Type_Div_Name, 
								The_Input_Control_Width_Div_Name,
								The_Character_Limit_Div_Name,
								The_Relational_Table_ID, 
								The_Relational_Field_Name_1, 
								The_Relational_Field_Name_2, 
								The_Relational_Field_Name_3, 
								The_Display_Div_Name, 
								The_Input_Form_ID, 
								The_Input_Start_Year_ID, 
								The_Input_End_Year_ID, 
								The_Public_Facing_Div_Name, 
								The_Input_Is_Required_Div_Name, 
								The_Input_Is_Modifiable_By_User_Div_Name, 
								The_Input_Explanatory_Text_Div_Name,
								The_Input_Options_Text_Div_Name,
								The_Input_Is_User_Field_Div_Name,
								The_Input_Is_Group_Field_Div_Name,
								The_Input_Target_Div_ID) {
	var The_Field_Name = document.getElementById(The_Field_Name_Div_Name).value;
	var The_Field_Type = document.getElementById(The_Field_Type_Div_Name).value;
	var The_Input_Control_Width = document.getElementById(The_Input_Control_Width_Div_Name).value;
	var The_Character_Limit = document.getElementById(The_Character_Limit_Div_Name).value;
	var The_Explanatory_Text = escape(document.getElementById(The_Input_Explanatory_Text_Div_Name).value);
	var The_Options_Text = escape(document.getElementById(The_Input_Options_Text_Div_Name).value);
	var The_Form_ID = The_Input_Form_ID;
	if (The_Field_Type == 'Dynamic Select' || The_Field_Type == 'Dynamic Radio') {
		var The_Relational_Table_ID = document.getElementById(The_Relational_Table_ID).options[document.getElementById(The_Relational_Table_ID).selectedIndex].value;
		var The_Relational_Field_ID_1 = document.getElementById(The_Relational_Field_Name_1).options[document.getElementById(The_Relational_Field_Name_1).selectedIndex].value;
		var The_Relational_Field_ID_2 = document.getElementById(The_Relational_Field_Name_2).options[document.getElementById(The_Relational_Field_Name_2).selectedIndex].value;
		var The_Relational_Field_ID_3 = document.getElementById(The_Relational_Field_Name_3).options[document.getElementById(The_Relational_Field_Name_3).selectedIndex].value;
	}
	if (The_Field_Type == 'Date') {
		var The_Start_Year = document.getElementById(The_Input_Start_Year_ID).value;
		var The_End_Year = document.getElementById(The_Input_End_Year_ID).value;
		if (!IsNumeric(The_Start_Year)) {
			alert('Start Year must be a four-digit number.');
			return true;
		}
		if (!IsNumeric(The_End_Year)) {
			alert('End Year must be a four-digit number.');
			return true;
		}
		if (The_End_Year <= The_Start_Year) {
			alert('Start Year must be before End Year.');
			return true;
		}
	}
	var The_Display_Flag = 'off';
	if (document.getElementById(The_Display_Div_Name).checked) {
		The_Display_Flag = 'on';
	}
	var The_Public_Flag = 'off';
	if (document.getElementById(The_Public_Facing_Div_Name) != null) {
		if (document.getElementById(The_Public_Facing_Div_Name).checked) {
			The_Public_Flag = 'on';
		}
	}
	var The_Required_Flag = 'off';
	if (document.getElementById(The_Input_Is_Required_Div_Name).checked) {
		The_Required_Flag = 'on';
	}
	var Is_User_Field = document.getElementById(The_Input_Is_User_Field_Div_Name).value;
	var Is_Group_Field = document.getElementById(The_Input_Is_Group_Field_Div_Name).value;
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	if (Is_User_Field == true || Is_User_Field == '1' || Is_User_Field == 'true' || Is_User_Field == 1) {
		var The_Modifiable_By_User_Flag = 'off';
		if (document.getElementById(The_Input_Is_Modifiable_By_User_Div_Name).checked) {
			The_Modifiable_By_User_Flag = 'on';
		}
		url += '?support_function=create_user_custom_field';
		url += '&is_modifiable_by_user=' + The_Modifiable_By_User_Flag;
	}
	else {
		if (Is_Group_Field == true || Is_Group_Field == '1' || Is_Group_Field == 'true' || Is_Group_Field == 1) {
			url += '?support_function=create_group_custom_field';
		}
		else {
			url += '?support_function=create_field';
			url += '&form_id=' + The_Form_ID;
			url += '&is_public_facing=' + The_Public_Flag;
		}
	}
	url += '&field_name=' + The_Field_Name;
	url += '&field_type=' + The_Field_Type;
	url += '&input_control_width=' + The_Input_Control_Width;
	url += '&character_limit=' + The_Character_Limit;
	url += '&relational_table_id=' + The_Relational_Table_ID;
	url += '&relational_field_id_1=' + The_Relational_Field_ID_1;
	url += '&relational_field_id_2=' + The_Relational_Field_ID_2;
	url += '&relational_field_id_3=' + The_Relational_Field_ID_3;
	url += '&start_year=' + The_Start_Year;
	url += '&end_year=' + The_End_Year;
	url += '&display=' + The_Display_Flag;
	url += '&is_required=' + The_Required_Flag;
	url += '&explanatory_text=' + The_Explanatory_Text;
	url += '&options_text=' + The_Options_Text;
	url += '&random=' + Math.random();
	xmlHttp = GetXmlHttpObject();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Create_The_Form(The_Input_List, The_Input_Form_Name, The_Input_Form_Type, The_Input_Form_Filename, The_Input_Group_Permissions_Prefix, The_Input_Target_Div_ID, The_Input_Audience_Node_List, The_Input_Confirmation_Message, The_Input_Limit_Access_Node_List, The_Input_Email_Notification_Node_List, The_Input_Email_Recipients) {
	xmlHttp = GetXmlHttpObject();
	var The_New_Form_Name = document.getElementById(The_Input_Form_Name).value;
	var The_New_Form_Type = document.getElementById(The_Input_Form_Type).value;
	var The_New_Form_Filename = document.getElementById(The_Input_Form_Filename).value.replace('C:\\fakepath\\','');
	var The_Audience = The_Checked_Value_Of_The_Node_List(The_Input_Audience_Node_List);
	var Limit_Access = The_Checked_Value_Of_The_Node_List(The_Input_Limit_Access_Node_List);
	var Email_Notification = The_Checked_Value_Of_The_Node_List(The_Input_Email_Notification_Node_List);
	var The_Group_Permissions = The_Elements_Within_The_Div_Tagged_As(The_Input_List, The_Input_Group_Permissions_Prefix);
	var The_Group_String = '';
	if (The_Group_Permissions != null) {
		for (i=0; i<The_Group_Permissions.length; i++) {
			var The_Group = The_Group_Permissions[i].value;
			if (The_Group_Permissions[i].checked) {
				The_Group_String += The_Group + ',';
			}
		}
	}
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=create_form';
	url += '&form_name=' + The_New_Form_Name;
	url += '&form_type=' + The_New_Form_Type;
	url += '&filename=' + The_New_Form_Filename;
	url += '&groups=' + The_Group_String;
	url += '&audience=' + The_Audience;
	url += '&confirmation_message=' + escape(The_Input_Confirmation_Message);
	url += '&limit_access=' + Limit_Access;
	url += '&email_notification_flag=' + Email_Notification;
	url += '&email_recipients=' + escape(The_Input_Email_Recipients);
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
/*  jc 3/25/10 - DEPRECATED - this function appears to be replaced by the "modify" equivalent, with a parameter specifying that it's a new submission
function Create_The_Submission_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(The_Prefix_Name, The_Div_Name, The_Form_ID_Input_Name) {
	var The_Div = document.getElementById(The_Div_Name);
	var The_Tagged_Children = The_Elements_Within_The_Div_Tagged_As(The_Div, The_Prefix_Name);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Children != null) {
		for (i=0; i<The_Tagged_Children.length; i++) {
			var The_Name = The_Tagged_Children[i].name;
			var The_Value = The_Tagged_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Field_Type = document.getElementById(The_Name).type;
			if(The_Field_Type != 'radio' || (The_Field_Type == 'radio' && The_Value == $("input[name='"+The_Name+"']:checked").val())){
				The_Fields_To_Submit.push(The_Name);
				The_Values_To_Submit.push(The_Value);
			}
		}
	}
	xmlHttp = GetXmlHttpObject();
	var The_Form_ID = document.getElementById(The_Form_ID_Input_Name).value;
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	var params = 'support_function=modify_submission&form_id=' + The_Form_ID;
	params += '&submission_id=' + The_Input_Submission_ID;
	params += '&submit_prefix=' + The_Prefix_Name;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		params += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	params += '&random=' + Math.random();
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//	xmlHttp.setRequestHeader("Content-length", params.length);
//	xmlHttp.setRequestHeader("Connection", "close");
	The_Global_Ajax_Target_ID = 'submissions_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.send(params);
}*/
function Create_The_Group(The_Input_Submit_Prefix, The_Input_List_Name, The_Input_Target_Div_ID) {
	xmlHttp = GetXmlHttpObject();
	var The_Group_Div = document.getElementById(The_Input_List_Name);
	var The_Tagged_Fields = The_Elements_Within_The_Div_Tagged_As(The_Group_Div, The_Input_Submit_Prefix);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Fields != null) {
		for (i=0; i<The_Tagged_Fields.length; i++) {
			var The_Name = The_Tagged_Fields[i].name;
			var The_Value = The_Tagged_Fields[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Fields[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=create_group';
	url += '&submit_prefix=' + The_Input_Submit_Prefix;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		url += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Create_The_Instance_Of_The_Object(The_Input_Object_Type, The_Input_Div_Name, The_Input_Target_Div_Name) {
	xmlHttp = GetXmlHttpObject();
	var The_New_Object_Instance_Name = document.getElementById(The_Input_Div_Name).value;
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=create_instance';
	url += '&instance_name=' + The_New_Object_Instance_Name;
	url += '&object_type=' + The_Input_Object_Type;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_Name;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Create_The_User(The_Submit_Prefix_Name, The_Group_Prefix_Name, The_User_Div_Name, The_Group_Div_Name, The_Input_Target_Div_ID) {
	var The_User_Div = document.getElementById(The_User_Div_Name);
	var The_Group_Div = document.getElementById(The_Group_Div_Name);
	var The_Tagged_Submit_Children = The_Elements_Within_The_Div_Tagged_As(The_User_Div, The_Submit_Prefix_Name);
	var The_Tagged_Group_Children = The_Elements_Within_The_Div_Tagged_As(The_Group_Div, The_Group_Prefix_Name);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Submit_Children != null) {
		for (i=0; i<The_Tagged_Submit_Children.length; i++) {
			var The_Name = The_Tagged_Submit_Children[i].name;
			var The_Value = The_Tagged_Submit_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Submit_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	if (The_Tagged_Group_Children != null) {
		for (i=0; i<The_Tagged_Group_Children.length; i++) {
			var The_Name = The_Tagged_Group_Children[i].name;
			var The_Value = The_Tagged_Group_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Group_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	var params = 'support_function=create_user';
	params += '&submit_prefix=' + The_Submit_Prefix_Name;
	params += '&group_prefix=' + The_Group_Prefix_Name;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		params += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	params += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//	xmlHttp.setRequestHeader("Content-length", params.length);
//	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.send(params);
}
function Create_The_User_Custom_Field(The_Field_Name_Div_Name, The_Field_Type_Div_Name, The_Relational_Table_ID, The_Relational_Field_Name_1, The_Relational_Field_Name_2, The_Relational_Field_Name_3, The_Display_Div_Name, The_Input_Start_Year_ID, The_Input_End_Year_ID) {
	var The_Field_Name = document.getElementById(The_Field_Name_Div_Name).value;
	var The_Field_Type = document.getElementById(The_Field_Type_Div_Name).value;
	if (The_Field_Type == 'Dynamic Select' || The_Field_Type == 'Dynamic Radio') {
		var The_Relational_Field_ID_1 = document.getElementById(The_Relational_Field_Name_1).options[document.getElementById(The_Relational_Field_Name_1).selectedIndex].value;
		var The_Relational_Field_ID_2 = document.getElementById(The_Relational_Field_Name_2).options[document.getElementById(The_Relational_Field_Name_2).selectedIndex].value;
		var The_Relational_Field_ID_3 = document.getElementById(The_Relational_Field_Name_3).options[document.getElementById(The_Relational_Field_Name_3).selectedIndex].value;
	}
	else {
		var The_Relational_Field_ID_1 = '';
		var The_Relational_Field_ID_2 = '';
		var The_Relational_Field_ID_3 = '';
	}
	if (The_Field_Type == 'Date') {
		var The_Start_Year = document.getElementById(The_Input_Start_Year_ID).value;
		var The_End_Year = document.getElementById(The_Input_End_Year_ID).value;
		if (!IsNumeric(The_Start_Year)) {
			alert('Start Year must be a four-digit number.');
			return true;
		}
		if (!IsNumeric(The_End_Year)) {
			alert('End Year must be a four-digit number.');
			return true;
		}
		if (The_End_Year <= The_Start_Year) {
			alert('Start Year must be before End Year.');
			return true;
		}
	}
	else {
		var The_Start_Year = '';
		var The_End_Year = '';
	}
	var The_Display_Flag = 'off';
	if (document.getElementById(The_Display_Div_Name).checked) {
		The_Display_Flag = 'on';
	}
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=create_user_custom_field';
	url += '&field_name=' + The_Field_Name;
	url += '&field_type=' + The_Field_Type;
	url += '&display=' + The_Display_Flag;
	url += '&relational_table_id=' + The_Relational_Table_ID;
	url += '&relational_field_id_1=' + The_Relational_Field_ID_1;
	url += '&relational_field_id_2=' + The_Relational_Field_ID_2;
	url += '&relational_field_id_3=' + The_Relational_Field_ID_3;
	url += '&start_year=' + The_Start_Year;
	url += '&end_year=' + The_End_Year;
	url += '&random=' + Math.random();
	xmlHttp = GetXmlHttpObject();
	The_Global_Ajax_Target_ID = 'user_custom_fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Create_The_View(The_Input_View_Name_Control_Name,
						 The_Input_Form_Control_Name,
						 The_Input_Sort_Field_Control_Name,
						 The_Input_Sort_Order_Control_Name,
						 The_Input_Limit_Access_Node_List,
						 The_Input_List,
						 The_Input_Group_Permissions_Prefix,
						 The_Input_Target_Div_Name) {
	xmlHttp = GetXmlHttpObject();
	var The_New_View_Name = document.getElementById(The_Input_View_Name_Control_Name).value;
	var The_New_View_Type = document.getElementById('view_type').value;
	var The_New_View_Width = document.getElementById('view_width').value;
	var The_New_View_Height = document.getElementById('view_height').value;
	var The_New_Form_ID = document.getElementById(The_Input_Form_Control_Name).value;
	var The_New_Image_Field = document.getElementById('image_field').value;
	var The_New_Video_Field = document.getElementById('video_field').value;
	var The_New_Title_Field = document.getElementById('title_field').value;
	var The_New_Sort_Field_ID = document.getElementById(The_Input_Sort_Field_Control_Name).value;
	var The_New_Sort_Order = document.getElementById(The_Input_Sort_Order_Control_Name).value;
	var Limit_Access = The_Checked_Value_Of_The_Node_List(The_Input_Limit_Access_Node_List);
	//alert(The_Input_List + '\n' + The_Input_Group_Permissions_Prefix);
	var The_Group_Permissions = The_Elements_Within_The_Div_Tagged_As(The_Input_List, The_Input_Group_Permissions_Prefix);
	var The_Group_String = '';
	if (The_Group_Permissions != null) {
		for (i=0; i<The_Group_Permissions.length; i++) {
			var The_Group = The_Group_Permissions[i].value;
			if (The_Group_Permissions[i].checked) {
				The_Group_String += The_Group + ',';
			}
		}
	}
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=create_view';
	url += '&display_name=' + The_New_View_Name;
	url += '&view_type=' + The_New_View_Type;
	url += '&width=' + The_New_View_Width;
	url += '&height=' + The_New_View_Height;
	url += '&form_id=' + The_New_Form_ID;
	url += '&image_field=' + The_New_Image_Field;
	url += '&video_field=' + The_New_Video_Field;
	url += '&title_field=' + The_New_Title_Field;
	url += '&sort_field=' + The_New_Sort_Field_ID;
	url += '&sort_order=' + The_New_Sort_Order;
	url += '&groups=' + The_Group_String;
	url += '&limit_access=' + Limit_Access;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_Name;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
/*function Create_The_View(The_Input_Submit_Tag, The_Input_List_Name) {
	var The_View_Div = document.getElementById(The_Input_List_Name);
	var The_Tagged_Submit_Children = The_Elements_Within_The_Div_Tagged_As(The_View_Div, The_Input_Submit_Tag);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Submit_Children != null) {
		for (i=0; i<The_Tagged_Submit_Children.length; i++) {
			var The_Name = The_Tagged_Submit_Children[i].name;
			var The_Value = The_Tagged_Submit_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Submit_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=create_view';
	url += '&submit_prefix=' + The_Input_Submit_Tag;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		url += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'views_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}*/

function Delete_The_Submission_For_The_Form_ID(The_Input_Submission_ID, The_Form_ID_Input_Name)
{
	var The_Form_ID;
	var The_URL_Parameter_String = '';
	if (The_Form_ID_Input_Name != undefined)
	{
		The_Form_ID = document.getElementById(The_Form_ID_Input_Name).value;
	}
	The_URL_Parameter_String += 'delete_submission&submission_id=' + The_Input_Submission_ID;
	The_URL_Parameter_String += '&form_id=' + The_Form_ID;
	The_Global_Ajax_Target_ID = 'submissions_displayer';
	
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=';
	url += The_URL_Parameter_String;
	url += '&random=' + Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
	
	Unhighlight_All_Children_Of_The_Div(The_Global_Ajax_Target_ID);
	return false;
}

function Delete_The_Temp_Data_For_The_GUID(The_Input_GUID, The_Input_Form_ID)
{
	var The_Form_ID;
	var The_URL_Parameter_String = '';
	The_URL_Parameter_String += 'delete_temp_data&submission_guid=' + The_Input_GUID;
	The_Global_Ajax_Target_ID = 'submissions_displayer';
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=';
	url += The_URL_Parameter_String;
	url += '&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
//	Unhighlight_All_Children_Of_The_Div(The_Global_Ajax_Target_ID);
	return false;
}

function Delete_Image_Map_File(The_Input_Filename)
{
	$.post('../mimik_plugins/mapper/delete_temp.php',{'filename':The_Input_Filename});
}

function Display_The_Fields_For_A_Relational_Selection_In_The_Div(The_Input_Table_ID, The_Input_Div_ID, The_Input_Submit_Tag)
{
	if (The_Input_Table_ID > 0) {
		xmlHttp = GetXmlHttpObject();
		var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
		url += '?support_function=load_relational_fields';
		url += '&table_id=' + The_Input_Table_ID;
		url += '&submit_tag=' + The_Input_Submit_Tag;
		url += '&random=' + Math.random();
		The_Global_Ajax_Target_ID = The_Input_Div_ID;
		xmlHttp.onreadystatechange=stateChanged;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
	else {
		document.getElementById(The_Input_Div_ID).innerHTML = '';
	}
}

function Display_The_Tables_For_A_Relational_Selection_In_The_Div(The_Input_Field_Type, The_Input_Div_ID, The_Input_Submit_Tag)
{
	if (The_Input_Field_Type == 'Dynamic Select' || The_Input_Field_Type == 'Dynamic Radio')
	{
		if (document.getElementById(The_Input_Div_ID).innerHTML == '')
		{
			xmlHttp = GetXmlHttpObject();
			var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
			url += '?support_function=load_relational_tables';
			url += '&submit_tag=' + The_Input_Submit_Tag;
			url += '&random=' + Math.random();
			The_Global_Ajax_Target_ID = The_Input_Div_ID;
			xmlHttp.onreadystatechange=stateChanged;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
	}
	else {
		document.getElementById(The_Input_Div_ID).innerHTML = '';
		document.getElementById('fields_for_relational_selection').innerHTML = '';
	}
}

function Display_The_Year_Fields_For_A_Date_Selection_In_The_Div(The_Input_Field_Type, The_Input_Start_Year_Div_ID, The_Input_End_Year_Div_ID)
{
	if (The_Input_Field_Type == 'Date')
	{
		document.getElementById(The_Input_Start_Year_Div_ID).innerHTML = '<div class="list_item_column">Start Year:</div>' + 
																		'<div class="list_item_column"><input type="text" name="start_year" id="start_year" /></div>';
		document.getElementById(The_Input_End_Year_Div_ID).innerHTML = '<div class="list_item_column">End Year:</div>' + 
																		'<div class="list_item_column"><input type="text" name="end_year" id="end_year" /></div>';
	}
	else {
		document.getElementById(The_Input_Start_Year_Div_ID).innerHTML = '';
		document.getElementById(The_Input_End_Year_Div_ID).innerHTML = '';
	}
}

function Initialize_The_Upload(The_Input_Field_ID)
{
	document.getElementById('submission_modification_form').onsubmit=function() {
		document.getElementById('submission_modification_form').target = 'upload_target'; // iframe
	}
}
function Load_The_Fields_Displayer_For_The_Form(The_Input_Form_ID) {
	var The_Form_ID = The_Input_Form_ID;
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_fields&form_id=' + The_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Load_The_Forms_Displayer() {
	xmlHttp = GetXmlHttpObject();
	if (document.getElementById('current_form') != undefined) {
		The_Current_Form_Control = document.getElementById('current_form');
		The_Current_Form_Control.parentNode.removeChild(The_Current_Form_Control);
	}
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_forms';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'forms_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Load_The_Groups_Displayer() {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_groups';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'groups_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Remove_File_From_The_Submission(The_Input_Form_ID, The_Input_Submission_ID, The_Input_Field_Name, The_Input_Div_ID) {
	if (confirm('Are you sure you want to remove this file?')) {
		xmlHttp = GetXmlHttpObject();
		var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
		url += '?support_function=remove_file';
		url += '&form_id=' + The_Input_Form_ID;
		url += '&submission_id=' + The_Input_Submission_ID;
		url += '&field_name=' + The_Input_Field_Name;
		url += '&upload_area=' + The_Input_Div_ID;
		url += '&random=' + Math.random();
		The_Global_Ajax_Target_ID = The_Input_Div_ID;
		xmlHttp.onreadystatechange=stateChanged;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
}
function Remove_Temp_File_From_The_Submission(The_Input_File_Path, The_Input_Secure, The_Input_Div_ID) {
	if (confirm('Are you sure you want to remove this file?')) {
		xmlHttp = GetXmlHttpObject();
		var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
		url += '?support_function=remove_temp_file';
		url += '&file_path=' + The_Input_File_Path;
		url += '&is_secure=' + The_Input_Secure;
		url += '&random=' + Math.random();
		The_Global_Ajax_Target_ID = The_Input_Div_ID;
		xmlHttp.onreadystatechange=stateChanged;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
}

function Show_The_Editor_For_The_Account() {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_account_editor';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'account_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function Show_The_Editor_For_The_Settings() {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_settings_editor';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'settings_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Load_The_Users_Displayer() {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_users';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'users_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Load_The_Views_Displayer() {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_views';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'views_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function Modify_The_Account_With_The_Tagged_Items_In_The_Div(The_User_ID, The_Prefix_Name, The_Div_Name)
{
	var The_Div = document.getElementById(The_Div_Name);
	var The_Tagged_Children = The_Elements_Within_The_Div_Tagged_As(The_Div, The_Prefix_Name);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Children != null) {
		for (i=0; i<The_Tagged_Children.length; i++) {
			var The_Name = The_Tagged_Children[i].name;
			var The_Value = The_Tagged_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=modify_account';
	url += '&id=' + The_User_ID;
	url += '&submit_prefix=' + The_Prefix_Name;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		url += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'account_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function Modify_The_Field_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(The_Input_Field_ID, The_Prefix_Name, The_Div_Name, The_Input_Form_ID, The_Input_Target_Div_ID) {
	var The_Div = document.getElementById(The_Div_Name);
	var The_Tagged_Children = The_Elements_Within_The_Div_Tagged_As(The_Div, The_Prefix_Name);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Children != null) {
		for (i=0; i<The_Tagged_Children.length; i++) {
			var The_Name = The_Tagged_Children[i].name;
			var The_Value = The_Tagged_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			else {
				The_Value = escape(The_Value);
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var The_Form_ID = The_Input_Form_ID;
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=modify_field';
	url += '&form_id=' + The_Form_ID;
	url += '&field_id=' + The_Input_Field_ID;
	url += '&submit_prefix=' + The_Prefix_Name;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		url += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Modify_The_Form(The_Input_Form_ID, The_Input_List, The_Input_Form_Name, The_Input_Form_Type, The_Input_Form_Filename, The_Input_Group_Permissions_Prefix, The_Input_Target_Div_Name, The_Input_Audience_Node_List, The_Input_Confirmation_Message, The_Input_Limit_Access_Node_List, The_Input_Preview_View_ID, The_Input_Email_Notification_Node_List, The_Input_Email_Recipients) {
	xmlHttp = GetXmlHttpObject();
	var The_Form_Name = document.getElementById(The_Input_Form_Name).value;
	var The_Form_Type = document.getElementById(The_Input_Form_Type).value;
	var The_Form_Filename = document.getElementById(The_Input_Form_Filename).value;
	var The_Audience = The_Checked_Value_Of_The_Node_List(The_Input_Audience_Node_List);
	var Limit_Access = The_Checked_Value_Of_The_Node_List(The_Input_Limit_Access_Node_List);
	var Email_Notification = The_Checked_Value_Of_The_Node_List(The_Input_Email_Notification_Node_List);
	var The_Group_Permissions = The_Elements_Within_The_Div_Tagged_As(The_Input_List, The_Input_Group_Permissions_Prefix);
	var The_Group_String = '';
	if (The_Group_Permissions != null) {
		for (i=0; i<The_Group_Permissions.length; i++) {
			var The_Group = The_Group_Permissions[i].value;
			if (The_Group_Permissions[i].checked) {
				The_Group_String += The_Group + ',';
			}
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php';
	var params = 'support_function=modify_form&form_id=' + The_Input_Form_ID;
	params += '&form_name=' + The_Form_Name;
	params += '&form_type=' + The_Form_Type;
	params += '&filename=' + The_Form_Filename;
	params += '&groups=' + The_Group_String;
	params += '&audience=' + The_Audience;
	params += '&confirmation_message=' + The_Input_Confirmation_Message;
	params += '&limit_access=' + Limit_Access;
	params += '&email_notification_flag=' + Email_Notification;
	params += '&preview_view_id=' + The_Input_Preview_View_ID;
	params += '&email_recipients=' + escape(The_Input_Email_Recipients);
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//	xmlHttp.setRequestHeader("Content-length", params.length);
//	xmlHttp.setRequestHeader("Connection", "close");
	The_Global_Ajax_Target_ID = The_Input_Target_Div_Name;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.send(params);
}
function Modify_The_Group(The_Input_Group_ID, The_Input_List_Name, The_Input_Submit_Prefix, The_Input_Target_Div_ID)
{
	var The_Group_Div = document.getElementById(The_Input_List_Name);
	var The_Tagged_Fields = The_Elements_Within_The_Div_Tagged_As(The_Group_Div, The_Input_Submit_Prefix);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Fields != null) {
		for (i=0; i<The_Tagged_Fields.length; i++) {
			var The_Name = The_Tagged_Fields[i].name;
			var The_Value = The_Tagged_Fields[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Fields[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=modify_group';
	url += '&group_id=' + The_Input_Group_ID;
	url += '&submit_prefix=' + The_Input_Submit_Prefix;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		url += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Modify_The_Settings_With_The_Tagged_Items_In_The_Div(The_Prefix_Name, The_Div_Name)
{
	var The_Div = document.getElementById(The_Div_Name);
	var The_Tagged_Children = The_Elements_Within_The_Div_Tagged_As(The_Div, The_Prefix_Name);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Children != null) {
		var The_Search_Results_Text_Elements = document.getElementsByName(The_Prefix_Name + 'search_results_text');
		for (i=0; i<The_Search_Results_Text_Elements.length; i++) {
			if (The_Search_Results_Text_Elements[i].checked) {
				The_Fields_To_Submit.push(The_Search_Results_Text_Elements[i].name);
				The_Values_To_Submit.push(The_Search_Results_Text_Elements[i].value);
			}
		}
		for (i=0; i<The_Tagged_Children.length; i++) {
			var The_Name = The_Tagged_Children[i].name;
			var The_Value = The_Tagged_Children[i].value;
			if (The_Name != The_Prefix_Name + 'search_results_text') {
				if (The_Value == 'CHECK:on') {
					if (The_Tagged_Children[i].checked) {
						The_Value = 'on';
					}
					else {
						The_Value = 'off';
					}
				}
				The_Fields_To_Submit.push(The_Name);
				The_Values_To_Submit.push(The_Value);
			}
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=modify_settings';
	url += '&submit_prefix=' + The_Prefix_Name;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		url += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'settings';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Modify_The_Submission_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(
														The_Input_Submission_ID,
														The_Input_Submission_GUID,
														The_Input_Submit_Prefix_Name,
														The_Input_Group_Prefix_Name,
														The_Input_User_Prefix_Name,
														The_Div_Name,
														The_Input_Form_ID,
														The_Input_Indication_Of_Admin_Display,
														The_Input_Target_Div_ID,
														The_Input_User_ID) {
	var The_Div = document.getElementById(The_Div_Name);
	var Is_Admin_Display = The_Input_Indication_Of_Admin_Display;
	if (The_Input_Indication_Of_Admin_Display == undefined) {
		Is_Admin_Display = true;
	}
	var The_Tagged_Submit_Children = The_Elements_Within_The_Div_Tagged_As(The_Div, The_Input_Submit_Prefix_Name);
	var The_Tagged_Group_Children = The_Checked_Elements_Within_The_Div_Tagged_As(The_Div, The_Input_Group_Prefix_Name);
	var The_Tagged_User_Children = The_Checked_Elements_Within_The_Div_Tagged_As(The_Div, The_Input_User_Prefix_Name);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	var The_Groups_To_Submit = new Array();
	var The_Users_To_Submit = new Array();
	if (The_Tagged_Submit_Children != null) {
		for (i=0; i<The_Tagged_Submit_Children.length; i++) {
			var The_Name = The_Tagged_Submit_Children[i].name;
			var The_Value = The_Tagged_Submit_Children[i].value;
			/*
			
			jdc 2010-03-05
			
			this part is commented out because its logic has been added to the tightened-up
			jquery selector in The_Elements_Within_The_Div_Tagged_As(), in utility_functions.js
			
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Submit_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Field_Type = document.getElementById(The_Name).type;*/
			//if(The_Field_Type != 'radio' || (The_Field_Type == 'radio' && The_Value == $("input[name='"+The_Name+"']:checked").val())){
				The_Fields_To_Submit.push(The_Name);
				The_Values_To_Submit.push(encodeURIComponent(The_Value));
			//}
		}
	}
	if (The_Tagged_Group_Children != null) {
		for (i=0; i<The_Tagged_Group_Children.length; i++) {
			var The_Group_ID = The_Tagged_Group_Children[i].value;
			The_Groups_To_Submit.push(The_Group_ID);
		}
	}
	if (The_Tagged_User_Children != null) {
		for (i=0; i<The_Tagged_User_Children.length; i++) {
			var The_User_ID = The_Tagged_User_Children[i].value;
			The_Users_To_Submit.push(The_User_ID);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '/mimik/mimik_includes/ivy-mimik_support_utilities.inc.php';
	var params = 'support_function=';
	if (The_Input_Submission_ID == 'NEW') {
		params += 'create_submission';
	}
	else {
		params += 'modify_submission&submission_id=' + The_Input_Submission_ID;
	}
	params += '&submission_guid=' + The_Input_Submission_GUID;
	params += '&form_id=' + The_Input_Form_ID;
	params += '&submit_prefix=' + The_Input_Submit_Prefix_Name;
	params += '&is_admin_display=' + Is_Admin_Display;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		params += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	params += '&submit_groups=' + The_Groups_To_Submit;
	params += '&submit_users=' + The_Users_To_Submit;
	params += '&random=' + Math.random();
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//	xmlHttp.setRequestHeader("Content-length", params.length);
//	xmlHttp.setRequestHeader("Connection", "close");
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.send(params);
}
function Modify_The_User_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(The_Input_User_ID, The_Submit_Prefix_Name, The_Group_Prefix_Name, The_User_Div_Name, The_Group_Div_Name, The_Input_Target_Div_ID, The_Input_Indication_Of_Admin_Display)
{
	var The_Tagged_Submit_Children = null;
	var The_User_Div = null;
	if (The_User_Div_Name != '' && The_User_Div_Name != undefined) {
		The_User_Div = document.getElementById(The_User_Div_Name);
		The_Tagged_Submit_Children = The_Elements_Within_The_Div_Tagged_As(The_User_Div, The_Submit_Prefix_Name);
	}
	var The_Tagged_Group_Children = null;
	var The_Group_Div = null;
	if (The_Group_Div_Name != '' && The_Group_Div_Name != undefined) {
		The_Group_Div = document.getElementById(The_Group_Div_Name);
		The_Tagged_Group_Children = The_Elements_Within_The_Div_Tagged_As(The_Group_Div, The_Group_Prefix_Name);
	}
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Submit_Children != null) {
		for (i=0; i<The_Tagged_Submit_Children.length; i++) {
			var The_Name = The_Tagged_Submit_Children[i].name;
			var The_Value = The_Tagged_Submit_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Submit_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	if (The_Tagged_Group_Children != null) {
		for (i=0; i<The_Tagged_Group_Children.length; i++) {
			var The_Name = The_Tagged_Group_Children[i].name;
			var The_Value = The_Tagged_Group_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Group_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	var params = 'support_function=modify_user&user_id=' + The_Input_User_ID;
	params += '&submit_prefix=' + The_Submit_Prefix_Name;
	params += '&group_prefix=' + The_Group_Prefix_Name;
	params += '&admin_display=' + The_Input_Indication_Of_Admin_Display;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		params += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	params += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_ID;
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//	xmlHttp.setRequestHeader("Content-length", params.length);
//	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.send(params);
}
function Modify_The_View(The_Input_View_ID,
						 The_Input_View_Name_Control_Name,
						 The_Input_Form_Control_Name,
						 The_Input_Sort_Field_Control_Name,
						 The_Input_Sort_Order_Control_Name,
						 The_Input_Limit_Access_Node_List,
						 The_Input_List,
						 The_Input_Group_Permissions_Prefix,
						 The_Input_Target_Div_Name,
						 The_Input_Indication_To_Recreate_Template) {
	xmlHttp = GetXmlHttpObject();
	if (The_Input_Indication_To_Recreate_Template == undefined) {
		The_Input_Indication_To_Recreate_Template = 0;
	}
	var The_New_View_Name = document.getElementById(The_Input_View_Name_Control_Name).value;
	var The_New_Form_ID = document.getElementById(The_Input_Form_Control_Name).value;
	var The_New_Sort_Field_ID = document.getElementById(The_Input_Sort_Field_Control_Name).value;
	var The_New_Sort_Order = document.getElementById(The_Input_Sort_Order_Control_Name).value;
	var Limit_Access = The_Checked_Value_Of_The_Node_List(The_Input_Limit_Access_Node_List);
	var The_Group_Permissions = The_Elements_Within_The_Div_Tagged_As(The_Input_List, The_Input_Group_Permissions_Prefix);
	var The_Group_String = '';
	if (The_Group_Permissions != null) {
		for (i=0; i<The_Group_Permissions.length; i++) {
			var The_Group = The_Group_Permissions[i].value;
			if (The_Group_Permissions[i].checked) {
				The_Group_String += The_Group + ',';
			}
		}
	}
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=modify_view';
	url += '&view_id=' + The_Input_View_ID;
	url += '&display_name=' + The_New_View_Name;
	url += '&form_id=' + The_New_Form_ID;
	url += '&sort_field=' + The_New_Sort_Field_ID;
	url += '&sort_order=' + The_New_Sort_Order;
	url += '&groups=' + The_Group_String;
	url += '&limit_access=' + Limit_Access;
	url += '&recreate_template=' + The_Input_Indication_To_Recreate_Template;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_Name;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
/*function Modify_The_View_With_The_Tagged_Items_In_The_Div_For_The_Form_ID(The_Input_View_ID, The_Prefix_Name, The_Div_Name, The_Input_Indication_To_Recreate_Template)
{
	if (The_Input_Indication_To_Recreate_Template == undefined) {
		The_Input_Indication_To_Recreate_Template = 0;
	}
	var The_Div = document.getElementById(The_Div_Name);
	var The_Tagged_Children = The_Elements_Within_The_Div_Tagged_As(The_Div, The_Prefix_Name);
	var The_Fields_To_Submit = new Array();
	var The_Values_To_Submit = new Array();
	if (The_Tagged_Children != null) {
		for (i=0; i<The_Tagged_Children.length; i++) {
			var The_Name = The_Tagged_Children[i].name;
			var The_Value = The_Tagged_Children[i].value;
			if (The_Value == 'CHECK:on') {
				if (The_Tagged_Children[i].checked) {
					The_Value = 'on';
				}
				else {
					The_Value = 'off';
				}
			}
			The_Fields_To_Submit.push(The_Name);
			The_Values_To_Submit.push(The_Value);
		}
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=modify_view';
	url += '&view_id=' + The_Input_View_ID;
	url += '&submit_prefix=' + The_Prefix_Name;
	url += '&recreate_template=' + The_Input_Indication_To_Recreate_Template;
	for (i=0; i<The_Fields_To_Submit.length; i++) {
		url += '&' + The_Fields_To_Submit[i] + '=' + The_Values_To_Submit[i];
	}
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'views_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}*/
function Move_The_Field_Down_And_Refresh_The_Fields_For_The_Form(The_Input_Field_ID, The_Input_Form_ID){
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=move_field_down&field_id=' + The_Input_Field_ID;
	url += '&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Move_The_Field_Down_And_Refresh_The_User_Custom_Fields(The_Input_Field_ID) {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=move_user_custom_field_down&field_id=' + The_Input_Field_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'user_custom_fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Move_The_Field_Up_And_Refresh_The_Fields_For_The_Form(The_Input_Field_ID, The_Input_Form_ID){
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=move_field_up&field_id=' + The_Input_Field_ID;
	url += '&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Move_The_Field_Up_And_Refresh_The_User_Custom_Fields(The_Input_Field_ID) {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=move_user_custom_field_up&field_id=' + The_Input_Field_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'user_custom_fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Value_Editor_Div_For_The_Value(The_Input_Value_ID, The_Input_Field_ID) {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_value_editor&value_id=' + The_Input_Value_ID + '&field_id=' + The_Input_Field_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'value_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Fields_Div_For_The_Form(The_Input_Form_ID) {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_fields&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Field_Editor_Div_For_The_Field(The_Input_Field_ID, The_Input_Form_ID) {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_field_editor&field_id=' + The_Input_Field_ID + '&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'field_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function Show_The_Form_Creator_Div()
{
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_form_creator';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'form_creator';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function Show_The_Submission_Editor_Div_For_The_Submission(The_Input_Submission_ID, The_Input_Form_ID){
	$('#forms').load('../mimik_includes/ivy-mimik_support_utilities.inc.php',{
					 	support_function:'load_submission_editor',
						submission_id:The_Input_Submission_ID,
						form_id:The_Input_Form_ID
					 });
	
	/*xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_submission_editor&submission_id=' + The_Input_Submission_ID + '&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'submission_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);*/
}
function Show_The_Submissions_Div_For_The_Form(The_Input_Form_ID, The_Input_Sort_Field, The_Input_Sort_Order) {
	/*Clear_The_Div('field_creator');
	Hide_The_Div('field_creator');
	Clear_The_Div('field_editor');
	Hide_The_Div('field_editor');
	Clear_The_Div('fields_displayer');
	Hide_The_Div('fields_displayer');
	Clear_The_Div('form_creator');
	Hide_The_Div('form_creator');
	Clear_The_Div('submission_creator');
	Hide_The_Div('submission_creator');
	Clear_The_Div('submissions_displayer');
	Hide_The_Div('submissions_displayer');*/
	var The_Sort_Field = '';
	var The_Sort_Order = '';
	if (The_Input_Sort_Field != undefined)
	{
		The_Sort_Field = The_Input_Sort_Field;
	}
	if (The_Input_Sort_Order != undefined)
	{
		The_Sort_Order = The_Input_Sort_Order;
	}
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_submissions&form_id=' + The_Input_Form_ID;
	url += '&sort_field=' + The_Sort_Field;
	url += '&sort_order=' + The_Sort_Order;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'submissions_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Image_Map_For_The_Form(The_Input_Form_ID) {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_image_map&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'forms';//'submissions_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Submission_Creator_Div_For_The_Form(The_Input_Form_ID) {
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_submission_creator&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'submission_creator';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function Show_The_User_Custom_Field_Creator_Div(The_Input_Div_Name, The_Input_Target_Div_Name, The_Input_Form_Is_Public_Facing)
{
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_user_custom_field_creator';
	url += '&div_name=' + The_Input_Div_Name;
	url += '&is_public_facing=' + The_Input_Form_Is_Public_Facing;
	url += '&target_div_name=' + The_Input_Target_Div_Name;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = The_Input_Target_Div_Name;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function Show_The_Editor_Div_For_The_Form(The_Input_Form_ID) {
	/*Hide_The_Div('form_displayer');*/
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_form_editor&form_id=' + The_Input_Form_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'form_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Editor_Div_For_The_Group(The_Input_Group_ID, The_Input_Group_Div_ID) {
	/*Hide_The_Div('group_creator');*/
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_group_editor&group_id=' + The_Input_Group_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'group_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Editor_Div_For_The_User(The_Input_User_ID, The_Input_User_Div_ID) {
	/*Hide_The_Div('user_creator');*/
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_user_editor&user_id=' + The_Input_User_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'user_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Editor_Div_For_The_View(The_Input_View_ID, The_Input_View_Div_ID) {
	/*Hide_The_Div('view_previewer');*/
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_view_editor&view_id=' + The_Input_View_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'view_editor';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_Preview_Div_For_The_View(The_Input_View_ID)
{
	/*Hide_The_Div('view_editor');*/
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_view_preview&view_id=' + The_Input_View_ID;
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'view_previewer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Show_The_User_Custom_Field_Creator_Div()
{
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_object_creator';
	url += '&object_type=user_custom_field';
	url += '&div_name=user_custom_fields_displayer';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'user_custom_field_creator';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Update_The_View_Form_Select_For_The_View_Type()
{
	$('#view_form_wrapper').load('../mimik_includes/ivy-mimik_support_utilities.inc.php',{'support_function':'load_form_menu','view_type':$('#view_type').val()});
	switch($('#view_type').val()){
		case 'Calendar':
			$('#calendar_width_container,#title_field_container').css('display','block');
			$('#image_field_container,#video_field_container,#view_height_container').css('display','none');
			$('#sort_order').attr({selectedIndex:1,disabled:true});
			$('#image_field option').remove();
			$('#image_field').prepend('<option value="">No Form Selected</option>');
		break;
		case 'Gallery':
			$('#image_field_container,#title_field_container').css('display','block');
			$('#title_field option,#image_field option').remove();
			$('#title_field,#image_field').prepend('<option value="">No Form Selected</option>');
			$('#calendar_width_container,#view_height_container,#video_field_container').css('display','none');
			$('#view_width').val('');
		break;
		case 'Video Player':
			$('#calendar_width_container,#view_height_container,#title_field_container,#video_field_container').css('display','block');
			$('#image_field_container').css('display','none');
			$('#title_field option,#video_field option').remove();
			$('#title_field,#video_field').prepend('<option value="">No Form Selected</option>');
			$('#view_width,#view_height').val('');
		break;
		default:
			$('#calendar_width_container,#view_height_container').css('display','none');
			$('#view_width').val('');
			$('#sort_field option').remove();
			$('#sort_field').prepend('<option value="">No Form Selected</option>');
			$('#sort_order').removeAttr('selectedIndex');
			$('#sort_order').removeAttr('disabled');
			$('#title_field_container,#image_field_container,#video_field_container').css('display','none');
			$('#title_field option,#image_field option,#video_field option').remove();
			$('#title_field').prepend('<option value="">No Form Selected</option>');
		break;
	}
}
function Show_The_User_Custom_Fields_Displayer_Div()
{
	xmlHttp = GetXmlHttpObject();
	var url = '../mimik_includes/ivy-mimik_support_utilities.inc.php';
	url += '?support_function=load_user_custom_fields_displayer';
	url += '&random=' + Math.random();
	The_Global_Ajax_Target_ID = 'user_custom_fields_displayer';
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
function Update_The_Sort_Field_Select_For_The_Form_In_The_Div(The_Input_Form_ID, The_Input_Field_Div_ID, The_Input_Select_Name)
{
	if($('#view_type').val() == 'Gallery'){
		$('#image_field_wrapper').load('../mimik_includes/ivy-mimik_support_utilities.inc.php',{
			support_function: 'load_image_fields',
			form_id: The_Input_Form_ID,
			select_name: 'image_field',
			view_type: 'Gallery'
		});
	}
	if($('#view_type').val() == 'Video Player'){
		$('#video_field_wrapper').load('../mimik_includes/ivy-mimik_support_utilities.inc.php',{
			support_function: 'load_video_fields',
			form_id: The_Input_Form_ID,
			select_name: 'video_field',
			view_type: 'Video Player'
		});
	}
	$.post('../mimik_includes/ivy-mimik_support_utilities.inc.php',{
		   'support_function':'get_form_type',
		   'form_id':parseInt($('#view_form').val())},
	function(form_type){
		if(form_type == 'Image_Map' && $('#view_type').val() == 'Normal'){
			$('#sort_field').val('');
			$('#sort_field').attr('disabled',true);
			$('#sort_order').attr('disabled',true);
		}else{
			$('#sort_field').removeAttr('disabled');
			if($('#view_type').val()!='Calendar'){
				$('#sort_order').removeAttr('disabled');
			}
			$('#'+The_Input_Field_Div_ID).load('../mimik_includes/ivy-mimik_support_utilities.inc.php',{
											   'support_function':'load_sort_fields',
											   'form_id':The_Input_Form_ID,
											   'select_name':The_Input_Select_Name,
											   'view_type':$('#view_type').val()});
			$('#title_field_wrapper').load('../mimik_includes/ivy-mimik_support_utilities.inc.php',{
										   'support_function':'load_sort_fields',
										   'form_id':The_Input_Form_ID,
										   'select_name':'title_field',
										   'view_type':'Normal'});
		}
	});
}
function Display_The_Controls_For_The_Text_Field(The_Input_Field_Type)
{
	if(The_Input_Field_Type == 'Text') {
		$('#input_control_width_container').css('display','block');
	}else{
		$('#input_control_width_container').css('display','none');
	}
	if (The_Input_Field_Type == 'Text' || The_Input_Field_Type == 'Text Area') {
		$('#character_limit_container').css('display','block');
	}else {
		$('#character_limit_container').css('display','none');
	}
}
function Display_The_Options_Textarea_For_The_Field_Type(The_Input_Field_Type)
{
	if(The_Input_Field_Type == 'Static Select' || The_Input_Field_Type == 'Static Radio'){
		$('#options_text_container').css('display','block');
	}else{
		$('#options_text_container').css('display','none');
	}
}