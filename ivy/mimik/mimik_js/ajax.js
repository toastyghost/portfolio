var xmlHttp;
var The_Global_Ajax_Target_ID = '';
function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		//Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	return xmlHttp;
}

function stateChanged()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		if (The_Global_Ajax_Target_ID != undefined)
		{
			if (The_Global_Ajax_Target_ID == 'your_id_to_test_here') {
				alert('xmlHttp.responseText\n' + xmlHttp.responseText);
			}

			var The_Response_Text = xmlHttp.responseText;
			
			if (The_Global_Ajax_Target_ID == 'your_id_to_test_here') {
				alert('innerHTML (before change)\n' + document.getElementById(The_Global_Ajax_Target_ID).innerHTML);
			}
			
			document.getElementById(The_Global_Ajax_Target_ID).innerHTML=The_Response_Text;
			
			if (The_Global_Ajax_Target_ID == 'your_id_to_test_here') {
				alert('innerHTML (after change)\n' + document.getElementById(The_Global_Ajax_Target_ID).innerHTML);
			}
			
			Show_The_Div(The_Global_Ajax_Target_ID);
		}
	}
}

function evaluateStateChange()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		if (xmlHttp.responseText.indexOf('Error:') == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	} 
}