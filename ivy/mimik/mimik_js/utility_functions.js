function Clear_The_Div(The_Input_Div_ID) {
	document.getElementById(The_Input_Div_ID).innerHTML = ''; 
}
function Confirm_The_Password(The_Input_Password, The_Input_Confirm_Field_Name, The_Target_Field_Name, The_Input_Feedback_Field_Name)
{
	var The_Confirm_Field = document.getElementById(The_Input_Confirm_Field_Name);
	var The_Confirm_Password = The_Confirm_Field.value;
	if (The_Input_Password == '') {
		document.getElementById(The_Target_Field_Name).value = '';
		document.getElementById(The_Input_Feedback_Field_Name).innerHTML = 'Type a password';
	}
	else {
		if (The_Input_Password == The_Confirm_Password) {
			if (Validate_The_Password(The_Input_Password, {
									  length:	[1/*8*/, Infinity],
									 /* lower:	1,
									  upper:	1,
									  numeric:	1,
									  special:	1,*/
									  badWords:	[],
									  badSequenceLength: 0 })) {
				document.getElementById(The_Target_Field_Name).value = hex_md5(The_Input_Password);
				document.getElementById(The_Input_Feedback_Field_Name).innerHTML = 'Valid password!';
			}
			else {
				document.getElementById(The_Target_Field_Name).value = '';
				document.getElementById(The_Input_Feedback_Field_Name).innerHTML = 'Invalid password';
			}
		}
		else {
			document.getElementById(The_Target_Field_Name).value = '';
			document.getElementById(The_Input_Feedback_Field_Name).innerHTML = 'Passwords don\'t match';
		}
	}
}

function Hide_The_Children_Of_The_Div(The_Input_Div_Name)
{
	The_Parent_Div = document.getElementById(The_Input_Div_Name);
	var The_Children = The_Parent_Div.childNodes;
	for (i=0; i<The_Children.length; i++) {
		var The_Classes = The_Children[i].className;
		if (The_Classes != undefined) {
			if (The_Classes.indexOf('hideable') >= 0) {
				var The_Child_Div_ID = The_Children[i].id;
				Hide_The_Div(The_Child_Div_ID);
			}
		}
	}
}
function Hide_The_Div(The_Input_Div_ID) {
	if(document.getElementById(The_Input_Div_ID).style.display != 'none')
	{
		if(The_Input_Div_ID.indexOf('master') == -1)
		{
			var block = document.getElementById(The_Input_Div_ID);
			var currentX = block.offsetLeft;
			var width = block.offsetWidth;
			
			slideLeft = new Tween(block.style,'left',Tween.strongEaseInOut,0,-400,.3,'px');
			slideLeft.start();
			
			fadeOut = new OpacityTween(block,Tween.strongEaseInOut,100,0,.3);
			fadeOut.start();
			document.getElementById(The_Input_Div_ID).style.left = currentX + 'px';
		}
		
		document.getElementById(The_Input_Div_ID).style.display = 'none';
	}
}
function Highlight_The_Div(The_Input_Div_ID) {
	var The_Current_Div_Class = document.getElementById(The_Input_Div_ID).className;
	document.getElementById(The_Input_Div_ID).className = 'active_item ' + The_Current_Div_Class;
}
function Highlight_The_Div_To_Be_Deleted(The_Input_Div_ID) {
	var The_Current_Div_Class = document.getElementById(The_Input_Div_ID).className;
	document.getElementById(The_Input_Div_ID).className = 'deleting_item ' + The_Current_Div_Class;
}
function isAlphanumericInput(theInputChar) {
	if ((("abcdefghijklmnopqrstuvwxyz0123456789").indexOf(theInputChar) > -1)) {
		return true;
	}
}
function IsNumeric(sText, float)
{
	var ValidChars = "0123456789";
	if(float===true) ValidChars+='.';
	var IsNumber=true;
	var Char;
	
	for (i = 0; i < sText.length && IsNumber == true; i++) { 
		Char = sText.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) {
			IsNumber = false;
		}
	}
	return IsNumber;
}

function Populate_The_Hidden_Date_Field_With_The_Divs(The_Input_Hidden_Date_Field_ID, The_Input_Year_Field_ID, The_Input_Month_Field_ID, The_Input_Day_Field_ID)
{
	document.getElementById(The_Input_Hidden_Date_Field_ID).value = 
	document.getElementById(The_Input_Year_Field_ID).value + '-' +
	document.getElementById(The_Input_Month_Field_ID).value + '-' +
	document.getElementById(The_Input_Day_Field_ID).value;
}

function Populate_The_WYSIWYG_Editors(The_Input_Array_Of_WYSIWYG_IDs)
{
	for(i = 0; The_Input_Array_Of_WYSIWYG_IDs[i]; i++)
	{
		document.getElementById(The_Input_Array_Of_WYSIWYG_IDs[i]).value = FCKeditorAPI.GetInstance(The_Input_Array_Of_WYSIWYG_IDs[i]).GetHTML();
	}
}

function Set_The_Upload_File_Name(The_Input_Field_ID, The_Input_File_Name)
{
	The_Last_Slash_Position = The_Input_File_Name.lastIndexOf('\\');
	if (The_Last_Slash_Position != -1) {
		The_File_Name = The_Input_File_Name.substring(The_Last_Slash_Position + ('\\').length);
	}
	else {
		The_File_Name = The_Input_File_Name;
	}
	document.getElementById(The_Input_Field_ID).value=The_File_Name;	
}
function Show_The_Div(The_Input_Div_ID)
{
	if(document.getElementById(The_Input_Div_ID).style.display != 'block')
	{
		document.getElementById(The_Input_Div_ID).style.display = 'block';
		
		if(The_Input_Div_ID.indexOf('master') == -1)
		{
			var block = document.getElementById(The_Input_Div_ID);
			var currentX = block.offsetLeft;
			var width = block.offsetWidth;
			
			slideRight = new Tween(block.style,'left',Tween.strongEaseInOut,-400,0,.3,'px');
			slideRight.start();
			
			fadeIn = new OpacityTween(block,Tween.strongEaseInOut,0,100,.3);
			fadeIn.start();
			
			document.getElementById(The_Input_Div_ID).style.left = currentX + 'px';
		}
	}
}
function Submit_The_Form(The_Input_Form_Name, The_Input_Target_Name)
{
	var The_Form = document.getElementById(The_Input_Form_Name);
	if (The_Input_Target_Name != undefined) {
		The_Form.target = The_Input_Target_Name;
	}
	The_Form.submit();
}
function Disable_Enter_Key(e){
	var key;
	if(window.event)
		key = window.event.keyCode;     //IE
	else
		key = e.which;     //firefox
	if(key == 13)
		return false;
	else
		return true;
}
function Unhighlight_All_Children_Of_The_Div(The_Input_Div_ID)
{
	var The_Div = document.getElementById(The_Input_Div_ID);
	var The_Children = The_Div.childNodes;
	for (i=0; i<The_Children.length; i++) {
		The_Children[i].className = The_Children[i].className.replace(/active_item/, '');
		The_Children[i].className = The_Children[i].className.replace(/new_item/, '');
		The_Children[i].className = The_Children[i].className.replace(/deleting_item/, '');
	}
}
function The_Checked_Elements_Within_The_Div_Tagged_As(The_Input_Div, The_Input_Prefix_Name)
{
	var The_Children = The_Input_Div.getElementsByTagName('*');
	var The_Tagged_Elements = new Array();
	for (var i in The_Children) {
		var The_Name = The_Children[i].name;
		if (The_Name != undefined) {
			if (The_Name.indexOf(The_Input_Prefix_Name) == 0) {
				if (The_Children[i].checked) {
					The_Tagged_Elements.push(The_Children[i]);
				}
			}
		}
	}
	return The_Tagged_Elements;
}

function The_Checked_Value_Of_The_Node_List(The_Input_Node_List) {
	if(!The_Input_Node_List)
		return "";
	var The_Node_Length = The_Input_Node_List.length;
	if(The_Node_Length == undefined)
		if(The_Input_Node_List.checked)
			return The_Input_Node_List.value;
		else
			return "";
	for(var i = 0; i < The_Node_Length; i++) {
		if(The_Input_Node_List[i].checked) {
			return The_Input_Node_List[i].value;
		}
	}
	return "";
}
function The_Elements_Within_The_Div_Tagged_As(The_Input_Div, The_Input_Prefix_Name)
{
	var The_Tagged_Elements = new Array();
	$('#'+The_Input_Div.id+' input[type=checkbox]:checked,#'+The_Input_Div.id+' input[type=radio]:checked,#'+The_Input_Div.id+' [name*='+The_Input_Prefix_Name+']:not(input[type=checkbox],input[type=radio])').each(function(){
		The_Tagged_Elements.push($(this)[0]);
	});
	return The_Tagged_Elements;
	
	// jdc 2010-03-31 - BELOW: before jQuery rewrite (checkboxes and radio buttons did not save correctly in IE browsers)
	
	/*var The_Children = The_Input_Div.getElementsByTagName('*');
	var The_Tagged_Elements = new Array();
	for (var i in The_Children) {
		var The_Name = The_Children[i].name;
		if (The_Name != undefined) {
			if (The_Name.indexOf(The_Input_Prefix_Name) == 0) {
				The_Tagged_Elements.push(The_Children[i]);
			}
		}
	}
	return The_Tagged_Elements;*/
}

function Validate_The_Password (pw, options) {
	// default options (allows any password)
	var o = {
		lower:    0,
		upper:    0,
		alpha:    0, /* lower + upper */
		numeric:  0,
		special:  0,
		length:   [0, Infinity],
		custom:   [ /* regexes and/or functions */ ],
		badWords: [],
		badSequenceLength: 0,
		noQwertySequences: false,
		noSequential:      false
	};

	for (var property in options)
		o[property] = options[property];

	var	re = {
			lower:   /[a-z]/g,
			upper:   /[A-Z]/g,
			alpha:   /[A-Z]/gi,
			numeric: /[0-9]/g,
			special: /[\W_]/g
		},
		rule, i;

	// enforce min/max length
	if (pw.length < o.length[0] || pw.length > o.length[1])
		return false;

	// enforce lower/upper/alpha/numeric/special rules
	for (rule in re) {
		if ((pw.match(re[rule]) || []).length < o[rule])
			return false;
	}

	// enforce word ban (case insensitive)
	for (i = 0; i < o.badWords.length; i++) {
		if (pw.toLowerCase().indexOf(o.badWords[i].toLowerCase()) > -1)
			return false;
	}

	// enforce the no sequential, identical characters rule
	if (o.noSequential && /([\S\s])\1/.test(pw))
		return false;

	// enforce alphanumeric/qwerty sequence ban rules
	if (o.badSequenceLength) {
		var	lower   = "abcdefghijklmnopqrstuvwxyz",
			upper   = lower.toUpperCase(),
			numbers = "0123456789",
			qwerty  = "qwertyuiopasdfghjklzxcvbnm",
			start   = o.badSequenceLength - 1,
			seq     = "_" + pw.slice(0, start);
		for (i = start; i < pw.length; i++) {
			seq = seq.slice(1) + pw.charAt(i);
			if (
				lower.indexOf(seq)   > -1 ||
				upper.indexOf(seq)   > -1 ||
				numbers.indexOf(seq) > -1 ||
				(o.noQwertySequences && qwerty.indexOf(seq) > -1)
			) {
				return false;
			}
		}
	}

	// enforce custom regex/function rules
	for (i = 0; i < o.custom.length; i++) {
		rule = o.custom[i];
		if (rule instanceof RegExp) {
			if (!rule.test(pw))
				return false;
		} else if (rule instanceof Function) {
			if (!rule(pw))
				return false;
		}
	}

	// great success!
	return true;
}

function Verifies_That_Required_Fields_Are_Filled() {
	var All_Elements = document.all ? document.all : document.getElementsByTagName('*');
	var The_Required_Elements = new Array();
	var The_Required_Radio_Button_Groups = new Array();
	for (var e = 0; e < All_Elements.length; e++) {
		if (All_Elements[e].className.indexOf('required') >= 0) {
			The_Required_Elements[The_Required_Elements.length] = All_Elements[e];
		}
	}
	for (e = 0; e < The_Required_Elements.length; e++) {
		if (The_Required_Elements[e].type == 'radio') {
			if (!The_Required_Radio_Button_Groups.in_array(The_Required_Elements[e].name)) {
				The_Required_Radio_Button_Groups.push(The_Required_Elements[e].name);
			}
		}
		if (The_Required_Elements[e].className.indexOf('number') >= 0) {
			if (!IsNumeric(The_Required_Elements[e].value)) {
				return false;
			}
		}
		if (The_Required_Elements[e].className.indexOf('decimal') >= 0) {
			if (!IsNumeric(The_Required_Elements[e].value,true)) {
				return false;
			}
		}
		if (The_Required_Elements[e].value == '' || The_Required_Elements[e].value == undefined) {
			return false;
		}
	}
	for (r = 0; r < The_Required_Radio_Button_Groups.length; r++) {
		if ($("'input[name=" + The_Required_Radio_Button_Groups[r] + "]:checked'").val()) {
			// do nothing
		}
		else {
			return false;
		}
	}
	return true;
	
	// jQuery rewrite in progress!!
	
	/*$('.required').each(function(){
		fieldType = $(this).attr('type');
		if(fieldType == 'radio'){
			fieldValue = $('input[@name='+$(this).attr('name')+']:checked').val();
		}else{
			fieldValue = $(this).val();
		}
		if(fieldValue == '' || fieldValue == undefined){
			return false;
		}
	});
	return true;*/
}

Array.prototype.in_array = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
}

function textAreaMaxLength(object,event){
	var key = event.which;
	//all keys including return.
	if(key >= 33 || key == 13) {
		var maxLength = $(object).attr("maxlength");
		var length = $(object).val().length;
		if(length >= maxLength && maxLength != undefined && maxLength > 0 && maxLength != '' && maxLength != null) {
			event.preventDefault();
		}
	}
}