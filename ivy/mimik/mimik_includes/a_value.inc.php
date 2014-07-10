<?

require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/the_system_settings.config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/site_includes/site_html_utilities.inc.php' );

class A_Value
{
	var $Field_Information;
	var $Data;
	var $Database_To_Use;
	var $Is_Relational = false;
	
	function A_Value($The_Input_Data, $The_Input_Field_Information, $The_Input_Database_To_Use = NULL)
	{
		$this->Database_To_Use = $The_Input_Database_To_Use;
		$this->Field_Information = $The_Input_Field_Information;
		$this->Display_Name = $this->Field_Information['display_name'];
		
		if ($this->Field_Information['type'] == 'Dynamic Select' || $this->Field_Information['type'] == 'Dynamic Radio') :
		
			if ($The_Input_Data) :

				$The_Relational_Table_ID = $this->Database_To_Use->Gets_The_Related_Table_For_The_Relational_Field($this->Field_Information['id']);
				$The_Relational_Submission = new A_Submission($this->Database_To_Use);
				$The_Relational_Submission->Loads_From_Table_Row($The_Relational_Table_ID, $The_Input_Data);
				$this->Is_Relational = true;
				$this->Data = $The_Relational_Submission;
				
			else :
			
				$this->Data = NULL;
				
			endif;
			
		else :
			$this->Data = $The_Input_Data;
		endif;
	}
	
	function Live_Site_HTML()
	{
		$The_HTML = '';
		
		if ($this->Field_Information['type'] == 'Dynamic Select' || $this->Field_Information['type'] == 'Dynamic Radio') :
		
			$The_HTML .= '<blockquote>' . $this->Field_Information['display_name'] . '<br />' . $this->Data->Live_Site_HTML() . '</blockquote>';

		else :
		
			if ($this->Data != '') :

				$The_HTML .= $this->Field_Information['display_name'] . ' : ';
				
				$this->Live_Site_HTML_For_The_Data();
	
				$The_HTML .= "<br />\n";
				
			endif;
			
		endif;
		
		return $The_HTML;
	}
	
	function Live_Site_HTML_For_The_Data()
	{
		global $THE_BASE_PATH;
	
		$The_HTML = '';
		
		switch($this->Field_Information['type']) :
		case 'Text Area' :
			$The_HTML .= Valid_HTML_For_The_Plain_Text_With_Line_Break_Replacement($this->Data);
			break;
		case 'File' :
			$The_HTML .= '<a href="/mimik/mimik_uploads/' . urlencode($this->Data) . '" target="_blank">' . urlencode($this->Data) . '</a>';
			break;
		case 'Secure File' :
			$The_HTML .= '<a href="/mimik/mimik_live_data/secure_file.php?filename=' . urlencode($this->Data) . '" target="_blank">' . urlencode($this->Data) . '</a>';
			break;
		case 'User Permission' :
			$The_User_Information = $this->Database_To_Use->All_User_Information($this->Data);
			$The_User_Name = $The_User_Information['login'];
			$The_HTML .= /*$The_User_Name*/ 'USER PERMISSION (TODO)';
			break;
		case 'Group Permission' :
			$The_Group_Information = $this->Database_To_Use->All_Group_Information($this->Data);
			$The_Group_Name = $The_Group_Information['name'];
			$The_HTML .= /*$The_Group_Name*/ 'GROUP PERMISSION (TODO)';
			break;
		default :
			$The_HTML .= $this->Data;
			break;
		endswitch;
			
		return $The_HTML;
	}
}

?>
