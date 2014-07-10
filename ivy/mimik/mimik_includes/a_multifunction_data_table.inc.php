<?php

// This defines A_Multifunction_Data_Table class with support functions.
// An "MDT" object loads rows of columnar data and displays them in 
// a user-friendly HTML <table> structure.  The table may have filter
// and pagination controls.  Each row may have one or more conditional
// actions that can be performed on it.  The collection of rows may have
// one or more actions that can be performed on a selection of the rows
// (eg: delete selected rows, alter status of selected rows, etc) or 
// that can be performed on no rows (eg: create new row, help, etc)

// TODO:
// 1. implement JOIN logic in the composite SQL statement (for dynamic
// values)
// 2. enable the optional display of the MDT as a nested <div> structure
// rather than a <table>
// 3. enable the MDT to load its rows using a function callback rather
// than a composite SQL statement (a more elegant solution for dynamic
// values)

// this class requires the following JavaScript files to be included in 
// the parent HTML head:
// js/callback.js
// js/setCaretPosition.js

define('HIDE_PAGINATION_CONTROLS', 1001);
define('HIDE_ROW_CHECKBOXES', 1002);
define('HIDE_COLUMN_HEADERS', 1003);
define('HIDE_FILTER_CONTROLS', 1004);
define('HIDE_ROWS_PER_PAGE_CONTROLS', 1005);

class A_Multifunction_Data_Table
{

	var $Database_To_Use;
	var $Displays_Column_Headers;
	var $Displays_Filter_Controls;
	var $Displays_Pagination_Controls;
	var $Displays_Row_Checkboxes;
	var $Displays_Rows_Per_Page_Controls;
	var $Field_Array;
	var $Filter_Array;
	var $Focus_Element;
	var $Function_Name;
	var $Highlighted_Row_ID_Array;
	var $Metadata_Array;
	var $Multirow_Action_Array;
	var $Page_Number;
	var $Permanent_Query_String;
	var $Select_Query;
	var $Single_Row_Action_Array;
	var $Sort_Array;
	var $Start_Row;
	var $Row_Limit;
	var $Rows;
	var $Table_Item_Name;
	var $Total_Number_Of_Rows;
	var $Where_Condition;
	
	function A_Multifunction_Data_Table(
								$The_Input_Database_To_Use = NULL,
								$The_Input_Select_Query_Or_Function_Name = '',
								$The_Input_Field_Array = NULL, 
								$The_Input_Single_Row_Action_Array = NULL,
								$The_Input_Multirow_Action_Array = NULL,
								$The_Input_Start_Row = NULL, 
								$The_Input_Row_Limit = '', 
								$The_Input_Sort_Information = NULL, 
								$The_Input_Filter_Array = NULL, 
								$The_Input_Focus_Element = '', 
								$The_Input_Table_Item_Name = '',
								$The_Input_Highlighted_Row_Information = '',
								$The_Input_Metadata_Array = NULL )
	{
		/*echo '<pre>function A_Multifunction_Data_Table(</pre>';
		debug($The_Input_Database_To_Use);
		debug($The_Input_Select_Query_Or_Function_Name);
		debug($The_Input_Field_Array); 
		debug($The_Input_Single_Row_Action_Array);
		debug($The_Input_Multirow_Action_Array);
		debug($The_Input_Start_Row); 
		debug($The_Input_Row_Limit);
		debug($The_Input_Sort_Information); 
		debug($The_Input_Filter_Array); 
		debug($The_Input_Focus_Element);
		debug($The_Input_Table_Item_Name);
		debug($The_Input_Highlighted_Row_Information);
		debug($The_Input_Metadata_Array);*/
	
		// initialize member variables
		$this->Displays_Column_Headers = true;
		$this->Displays_Filter_Controls = true;
		$this->Displays_Row_Checkboxes = true;
		$this->Displays_Pagination_Controls = true;
		$this->Displays_Rows_Per_Page_Controls = true;
		
		if ($The_Input_Database_To_Use) $this->Uses_The_Database_Connection($The_Input_Database_To_Use);
		
		if ($The_Input_Select_Query_Or_Function_Name) :
		
			$The_Member_Function_Array = array($this, $The_Input_Select_Query_Or_Function_Name);
		
			if (is_callable($The_Member_Function_Array)) :
			
				$this->Uses_The_Function($The_Input_Select_Query_Or_Function_Name);
			
			else :
			
				$this->Uses_The_Select_Query($The_Input_Select_Query_Or_Function_Name);
				
			endif;
		
		endif;
		
		if (is_array($The_Input_Field_Array)) $this->Uses_The_Fields($The_Input_Field_Array);
		
		if (is_array($The_Input_Single_Row_Action_Array)) $this->Uses_The_Single_Row_Actions($The_Input_Single_Row_Action_Array);
		
		if (is_array($The_Input_Multirow_Action_Array)) $this->Uses_The_Multirow_Actions($The_Input_Multirow_Action_Array);
		
		if ($The_Input_Start_Row !== NULL) $this->Uses_The_Pagination_Information($The_Input_Start_Row, $The_Input_Row_Limit);
	
		$this->Uses_The_Sort_Information($The_Input_Sort_Information);
		
		if (is_array($The_Input_Filter_Array)) :
		
			$this->Uses_The_Filter_Information($The_Input_Filter_Array);
			
		endif;
		
		if ($The_Input_Focus_Element) $this->Uses_The_Focus_Element($The_Input_Focus_Element);
		
		$this->Uses_The_Table_Item_Name($The_Input_Table_Item_Name);
		
		$this->Highlights_The_Rows($The_Input_Highlighted_Row_Information);
		
		$this->Uses_The_Metadata($The_Input_Metadata_Array);
		
		if (func_num_args() > 13) :
		
			for ($i = 14; $i < func_num_args(); $i++) :
			
				switch (func_get_arg($i)) :
				
				case HIDE_COLUMN_HEADERS :
					$this->Displays_Column_Headers(false);
					break;
					
				case HIDE_FILTER_CONTROLS :
					$this->Displays_Filter_Controls(false);
					break;
					
				case HIDE_PAGINATION_CONTROLS :
					$this->Displays_Pagination_Controls(false);
					break;
				
				case HIDE_ROW_CHECKBOXES :
					$this->Displays_Row_Checkboxes(false);
					break;
					
				case HIDE_ROWS_PER_PAGE_CONTROLS :
					$this->Displays_Rows_Per_Page_Controls(false);
					break;
					
				endswitch;
			
			endfor;
		
		endif;
		
	} // A_Multifunction_Data_Table
	
	function Creates_The_Permanent_Query_String()
	{
		if ($this->Function_Name) :
			$this->Permanent_Query_String = '\'function\': \'' . urlencode($this->Function_Name) . '\', ';
		else :
			$this->Permanent_Query_String = 'select_query: \'' . urlencode($this->Select_Query) . '\', ';
		endif;
		
		$this->Permanent_Query_String .= 'highlighted_rows: \'' . urlencode(serialize($this->Highlighted_Row_ID_Array)) . '\', ' .
										 'table_item: \'' . urlencode($this->Table_Item_Name) . '\', ' .
										 'fields: \'' . urlencode(serialize($this->Field_Array)) . '\', ' .
										 'single_row_actions: \'' . urlencode(serialize($this->Single_Row_Action_Array)) . '\', ' .
										 'multirow_actions: \'' . urlencode(serialize($this->Multirow_Action_Array)) . '\', ' .
										 'metadata: \'' . urlencode(serialize($this->Metadata_Array)) . '\'';
		
	} // Creates_The_Permanent_Query_String
	
	function Creates_The_Where_Condition()
	{
		$this->Where_Condition = '';
		
		if (is_array($this->Filter_Array)) :
		
			$this->Where_Condition = ' ';
		
			$The_Filter_Index = 1;
			
			$The_Number_Of_Filter_Fields = count($this->Filter_Array);
			
			foreach ($this->Filter_Array as $The_Filter_Field => $The_Filter_Value) :
		
				$this->Where_Condition .= '`' . $The_Filter_Field . '` LIKE \'%' . addslashes($The_Filter_Value) . '%\'';
				
				if ($The_Filter_Index < $The_Number_Of_Filter_Fields) $this->Where_Condition .= ' AND ';
				
				$The_Filter_Index++;
				
			endforeach;
			
		endif;
		
		return $this->Where_Condition;
		
	} // Creates_The_Where_Condition
	
	function Displays_Column_Headers($The_Input_Indication_To_Display_Column_Headers = true)
	{
		$this->Displays_Column_Headers = $The_Input_Indication_To_Display_Column_Headers;
	
	} // Displays_Column_Headers
	
	function Displays_Filter_Controls($The_Input_Indication_To_Display_Filter_Controls = true)
	{
		$this->Displays_Filter_Controls = $The_Input_Indication_To_Display_Filter_Controls;
	
	} // Displays_Filter_Controls
	
	function Displays_Pagination_Controls($The_Input_Indication_To_Display_Pagination_Controls = true)
	{
		$this->Displays_Pagination_Controls = $The_Input_Indication_To_Display_Pagination_Controls;
	
	} // Displays_Pagination_Controls
	
	function Displays_Row_Checkboxes($The_Input_Indication_To_Display_Row_Checkboxes = true)
	{
		$this->Displays_Row_Checkboxes = $The_Input_Indication_To_Display_Row_Checkboxes;
	
	} // Displays_Row_Checkboxes
	
	function Displays_Rows_Per_Page_Controls($The_Input_Indication_To_Display_Rows_Per_Page_Controls = true)
	{
		$this->Displays_Rows_Per_Page_Controls = $The_Input_Indication_To_Display_Rows_Per_Page_Controls;
	
	} // Displays_Rows_Per_Page_Controls
	
	function Gets_The_Arrayed_URL_Parameters($The_Input_Array_Name, $The_Input_Array)
	{
		$The_URL_Parameters = '';
		
		if (is_array($The_Input_Array)) :
		
			$The_Filter_Index = 1;
				
			$The_Number_Of_Filter_Fields = count($this->Filter_Array);
		
			foreach ($this->Filter_Array as $The_Filter_Field => $The_Filter_Value) :
		
				$The_URL_Parameters .= $The_Input_Array_Name . '__' . $The_Filter_Field . ': \'' . $The_Filter_Value . '\'';
				
				if ($The_Filter_Index < $The_Number_Of_Filter_Fields) $The_URL_Parameters .= ', ';
				
				$The_Filter_Index++;
				
			endforeach;
			
		endif;
		
		return $The_URL_Parameters;
		
	} // Gets_The_Arrayed_URL_Parameters
	
	function Gets_The_Pagination_URL_Parameters($The_Input_Start_Row = NULL, $The_Input_Row_Limit = NULL)
	{
		if ($The_Input_Start_Row !== NULL) :
		
			$The_Start_Row = $The_Input_Start_Row;
			
			if ($The_Input_Row_Limit !== NULL) :
			
				$The_Row_Limit = $The_Input_Row_Limit;
				
			else :
			
				$The_Row_Limit = $this->Row_Limit;
				
			endif;
			
		elseif ($this->Start_Row !== NULL && $this->Row_Limit !== NULL) :
		
			$The_Start_Row = $this->Start_Row;
			
			$The_Row_Limit = $this->Row_Limit;
			
		else :
		
			return false;
			
		endif;
		
		return 'start: \'' . $The_Start_Row . '\', limit: \'' . $The_Row_Limit . '\'';
		
	} // Gets_The_Pagination_URL_Parameters
	
	function Gets_The_Sort_URL_Parameters($The_Input_Sort_Information = NULL, $The_Input_New_Sort_Information = NULL)
	{
		if (is_array($The_Input_Sort_Information)) :
		
			$The_Sort_Information = $The_Input_Sort_Information;
			
		elseif (is_array($this->Sort_Array)) :
		
			$The_Sort_Information = $this->Sort_Array;
			
		else :
		
			return false;
			
		endif;
		
		// if new sort parameters are to be prepended to the sort information array...
		if (is_array($The_Input_New_Sort_Information)) :
		
			$The_New_Sort_Information = $The_Input_New_Sort_Information;
		
			foreach ($The_New_Sort_Information as $The_New_Field => $The_New_Direction) :
			
				unset($The_Sort_Information[$The_New_Field]);
				
			endforeach;
			
			array_push($The_New_Sort_Information, $The_Sort_Information);
			
			$The_Sort_Information = $The_New_Sort_Information;

		endif;
		
		return 'sort_info: \'' . urlencode(serialize($The_Sort_Information)) . '\'';
		
	} // Gets_The_Sort_URL_Parameters
	
	function Highlights_The_Rows($The_Input_Highlighted_Row_Information = NULL)
	{
		if (is_array($The_Input_Highlighted_Row_Information)) :
		
			$this->Highlighted_Row_ID_Array = $The_Input_Highlighted_Row_Information;
			
		else :
			
			$this->Highlighted_Row_ID_Array = NULL;
			
			$this->Highlighted_Row_ID_Array[] = $The_Input_Highlighted_Row_Information;
			
		endif;
		
		$this->Creates_The_Permanent_Query_String();
	
	} // Highlights_The_Row
	
	function Live_Site_HTML()
	{
		$The_HTML = '';
		
		$The_Number_Of_Single_Row_Actions = count($this->Single_Row_Action_Array);

		$The_Number_Of_Fields = count($this->Field_Array);
		
		if (is_array($this->Field_Array)) foreach ($this->Field_Array as $The_Field) :
			
			if ($The_Field['hide']) $The_Number_Of_Fields--;
			
		endforeach;
		
		$The_Number_Of_Columns = $The_Number_Of_Fields;
		
		if ($this->Displays_Row_Checkboxes) $The_Number_Of_Columns++;
		
		if (count($The_Number_Of_Single_Row_Actions) > 0) $The_Number_Of_Columns++;
		
		$The_Parent_Element_ID = uniqid('table-');

		$The_Sort_URL_Parameters = $this->Gets_The_Sort_URL_Parameters();
		
		$The_Pagination_URL_Parameters = $this->Gets_The_Pagination_URL_Parameters();
		
		$The_Filter_URL_Parameters = $this->Gets_The_Arrayed_URL_Parameters('filter', $this->Filter_Array);
		
		if ($The_Sort_URL_Parameters) :
		
			if ($The_Filter_URL_Parameters) :
			
				$The_URL_Parameters = $The_Sort_URL_Parameters . ', ' . $The_Filter_URL_Parameters;
				
			else :
			
				$The_URL_Parameters = $The_Sort_URL_Parameters;
				
			endif;
			
		elseif ($The_Filter_URL_Parameters) :
		
			$The_URL_Parameters = $The_Filter_URL_Parameters;
			
		endif;
		
		$The_HTML_For_The_Pagination_Links = The_HTML_For_The_Pagination_Links(
														$The_Parent_Element_ID, 
														$this->Total_Number_Of_Rows, 
														$this->Page_Number, 
														$this->Table_Item_Name,
														$The_URL_Parameters, 
														$this->Row_Limit, 
														10, 
														$this->Displays_Rows_Per_Page_Controls, 
														$this->Permanent_Query_String);
			
		$The_HTML .= '<div class="table-container" id="' . $The_Parent_Element_ID . '">';
		
		$The_HTML .= '<table id="' . $this->Table_Item_Name . '-table" class="multifunction-data-table" cellpadding="0" cellspacing="0">';
		
		$The_HTML .= '<thead>';
		
		if ($this->Multirow_Action_Array || $this->Displays_Pagination_Controls) :
					
			$The_HTML .= '<tr><td class="header" colspan="' . $The_Number_Of_Columns . '">';
			
			if ($this->Multirow_Action_Array && count($this->Row_Array) > 10) :
			
				$The_HTML .= '<div style="float:left;"><ul class="multirow-actions">';
				
				// $The_HTML .= '<li class="actions-header">Actions</li>';
				
				foreach ($this->Multirow_Action_Array as $The_Action) :
				
					$The_HREF = $The_Action['href'];
					
					// this populates the HREF attribute appropriately,
					// replacing "id=[id]" with "id=" . $The_Row['id']
					preg_match_all('/\[[^\[\]]+\]/', $The_HREF, $The_Matches, PREG_SET_ORDER);
					
					if (is_array($The_Matches)) :
					
						foreach ($The_Matches as $The_Match) :
						
							$The_Match_Field_Name = str_replace(array('[',']'), NULL, $The_Match[0]);
						
							$The_HREF = str_replace($The_Match[0], $The_Row[$The_Match_Field_Name], $The_HREF);
						
						endforeach;
						
					endif;
					
					// this populates the HREF attribute appropriately,
					// replacing "meta_id=[meta_id]" with "meta_id" = $this->Metadata_Array['meta_id']
					preg_match_all('/\<[^<>]+\>/', $The_HREF, $The_Matches, PREG_SET_ORDER);
					
					if (is_array($The_Matches)) :
					
						foreach ($The_Matches as $The_Match) :
						
							$The_Match_Field_Name = str_replace(array('<','>'), NULL, $The_Match[0]);
						
							$The_HREF = str_replace($The_Match[0], $this->Metadata_Array[$The_Match_Field_Name], $The_HREF);
						
						endforeach;
						
					endif;
				
					$The_HTML .= '<li><a href="' . $The_HREF . '" onclick="' . $The_Action['onclick'] . ' return false;">' . $The_Action['display_name'] . '</a></li>';
				
				endforeach;
				
				$The_HTML .= '</div>';
				
			endif; // if ($this->Multirow_Action_Array)
			
			if ($this->Displays_Pagination_Controls) :
			
				$The_HTML .= $The_HTML_For_The_Pagination_Links;
				
			endif; // if ($this->Displays_Pagination_Controls)
			
			$The_HTML .= '</td></tr>';
				
		endif; // if ($this->Displays_Pagination_Controls)
		
		if ($this->Displays_Column_Headers) :
		
			$The_HTML .= '<tr>';
			
			if ($this->Displays_Row_Checkboxes) :
			
				$The_HTML .= '<th class="checkall"><input id="' . $this->Table_Item_Name . '-checkall" type="checkbox" />';
				
				$The_HTML .= '<img src="/mimik/mimik_images/blank.gif" onload="$(\'#' . $this->Table_Item_Name . '-checkall\').click(function(){var checked_status = this.checked; $(\'#' . $this->Table_Item_Name . '-table .' . $this->Table_Item_Name . '-check\').attr(\'checked\', checked_status).change(); });" />';
	
				$The_HTML .= '</th>';
			
			endif;
			
			if ($The_Number_Of_Fields > 0) :

				foreach ($this->Field_Array as $The_Field) :
				
					if ($The_Field['hide'] == false) :
			
						$The_New_Sort_Direction = 'ASC';
						
						$The_Header_Class = '';
						
						if ($this->Sort_Array[$The_Field['name']] == 'ASC') :
						
							$The_New_Sort_Direction = 'DESC';
								
							$The_Header_Class = 'sort-down';
							
						else :
							
							$The_New_Sort_Direction = 'ASC';
							
							if ($this->Sort_Array[$The_Field['name']] == 'DESC') :
							
								$The_Header_Class = 'sort-up';
								
							endif;
							
						endif;
						
						$The_HTML .= '<th class="' . $The_Header_Class . ' ' . $this->Table_Item_Name . '-' . $The_Field['name'] . '-header">';
						
						$The_New_Sort_URL_Parameters = $this->Gets_The_Sort_URL_Parameters($this->Sort_Array, array($The_Field['name'] => $The_New_Sort_Direction));
						
						if ($The_Filter_URL_Parameters != '' && substr($The_Filter_URL_Parameters, strlen($The_Filter_URL_Parameters) - strlen(', ')) != ', ') $The_Filter_URL_Parameters .= ', ';
						
						$The_HTML .= '<a href="#" onclick="var go=false;' .
															'if ($(\'.' . $this->Table_Item_Name . '-check:checked\').length > 0) {' .
																'if (confirm(\'Row(s) will become unchecked if you proceed. Proceed?\')) { go=true; }' .
															'} else { go=true; }' .
															'if (go){' .
																'$.post(\'/mimik/mimik_support/show_data_table.php\',{' . 
																$this->Permanent_Query_String . ', ' .
																'sort_on: \'' . $The_Field['name'] . '\', ' .
																'sort_dir: \'' . $The_New_Sort_Direction . '\', ' .
																$The_Filter_URL_Parameters .
																$The_Pagination_URL_Parameters . ', ' .
																'rand: \'' . rand() . '\'}, ' .
																'function(data) { $(\'#' . $The_Parent_Element_ID . 
																'\').html(data); });' .
															'}return false;"';
						
						if ($The_Field['tip']) $The_HTML .= ' class="info"';
						
						$The_HTML .= '>';
						
						$The_HTML .= $The_Field['display_name'];
						
						if ($The_Field['tip']) $The_HTML .= '<span>' . $The_Field['tip'] . '</span>';
						
						$The_HTML .= '</a>';
						
						$The_HTML .= '</th>';
						
					endif;
			
				endforeach;
				
			endif; // if ($The_Number_Of_Fields > 0)
			
			if ($The_Number_Of_Single_Row_Actions > 0) :
				
				$The_HTML .= '<th></th>';
				
			endif; // if ($The_Number_Of_Single_Row_Actions > 0)
			
			$The_HTML .= '</tr>';
			
		endif; // if ($this->Displays_Column_Headers)
		
		if ($this->Displays_Filter_Controls) :
		
			if ($The_Number_Of_Fields > 0) :
					
				$The_HTML .= '<tr>';
				
				if ($this->Displays_Row_Checkboxes && !$this->Displays_Column_Headers) :
				
					$The_HTML .= '<td><input id="' . $this->Table_Item_Name . '-checkall" type="checkbox" />';
					
					$The_HTML .= '<img src="/mimik/mimik_images/blank.gif" onload="$(\'#' . $this->Table_Item_Name . '-checkall\').click(function(){var checked_status = this.checked; $(\'.' . $this->Table_Item_Name . '-check\').attr(\'checked\', checked_status).change(); });" />';
		
					$The_HTML .= '</td>';
				
				elseif ($this->Displays_Row_Checkboxes && $this->Displays_Column_Headers) :
				
					$The_HTML .= '<td></td>';
				
				endif; // if ($this->Displays_Row_Checkboxes && !$this->Displays_Column_Headers)
				
				foreach ($this->Field_Array as $The_Field) :
				
					if ($The_Field['hide'] == false) :
				
						if ($The_Field['filterable']) :
						
							$The_HTML .= '<td><input type="text" value="';
							
							if ($this->Filter_Array[$The_Field['name']]) :
						
								$The_HTML .= $this->Filter_Array[$The_Field['name']];
								
							endif;
				
							$The_New_Filter_Array = $this->Filter_Array;
							
							if (is_array($The_New_Filter_Array)) foreach ($The_New_Filter_Array as $The_New_Filter_Field => $The_New_Filter_Value) :
							
								$The_New_Filter_Array[$The_New_Filter_Field] = '\'' . $The_New_Filter_Value . '\'';
							
							endforeach;
							
							$The_New_Filter_Array[$The_Field['name']] = '$(\'#filter-' . $this->Table_Item_Name . '-' . $The_Field['name'] . '\').val()';
							
							$The_Filter_Index = 1;
							
							$The_Number_Of_New_Filter_Fields = count($The_New_Filter_Array);
							
							$The_New_Filter_URL_Parameters = '';
							
							foreach ($The_New_Filter_Array as $The_New_Filter_Field => $The_New_Filter_Value) :
							
								$The_New_Filter_URL_Parameters .= 'filter__' . $The_New_Filter_Field . ': ' . $The_New_Filter_Value;
								
								$The_New_Filter_URL_Parameters .= ', ';
								
							endforeach;
							
							$The_New_Filter_URL_Parameters = substr($The_New_Filter_URL_Parameters, 0, strlen($The_New_Filter_URL_Parameters) - strlen(', '));
							
							$The_HTML .= '" id="filter-' . $this->Table_Item_Name . '-' . $The_Field['name'] . '" class="filter-box';
							
							if ($The_Field['type'] == 'Number') $The_HTML .= ' width40';
							
							$The_HTML .= '" />';
							
							$The_New_Pagination_URL_Parameters = $this->Gets_The_Pagination_URL_Parameters(0);
							
							if ($The_New_Filter_URL_Parameters != '') $The_New_Filter_URL_Parameters .= ', ';
							
							if ($The_Sort_URL_Parameters != '' && substr($The_Sort_URL_Parameters, strlen($The_Sort_URL_Parameters) - strlen(', ')) != ', ') $The_Sort_URL_Parameters .= ', ';
							
							if ($The_New_Pagination_URL_Parameters != '') $The_New_Pagination_URL_Parameters .= ', ';
							
							if ($this->Focus_Element == 'filter-' . $this->Table_Item_Name . '-' . $The_Field['name']) :
							
								$The_Focus_Set_Event = 'setCaretPosition(\'' . $this->Focus_Element . '\', \'end\');';
								
							endif;
							
							$The_Live_Event = 
												'<script>' .
													'$(document).ready(function(){' .
														$The_Focus_Set_Event .
														'function filter() { ' .
															'$.post(\'/mimik/mimik_support/show_data_table.php\', ' .
															'{' . 
																$this->Permanent_Query_String . ', ' . 
																$The_New_Filter_URL_Parameters . 
																$The_Sort_URL_Parameters .
																$The_New_Pagination_URL_Parameters .
																'focus_element: \'filter-' . $this->Table_Item_Name . '-' . $The_Field['name'] . '\', ' .
																'rand: \'' . rand() . '\'' .
															'}, ' .
															'function(data) { ' .
																'$(\'#filter-' . $this->Table_Item_Name . '-' . $The_Field['name'] . '\').parents(\'.table-container\').html(data);' .
															'}); ' .
														'}' .
														'var timer=null;var theOldValue=\'\';' .
														'$(\'#filter-' . $this->Table_Item_Name . '-' . $The_Field['name'] . '\').die(\'keyup\'); ' .
														'$(\'#filter-' . $this->Table_Item_Name . '-' . $The_Field['name'] . '\').live(\'keyup\', ' .
															'function(e) {' .
																'if ( typeof event == "undefined" ) event = window.event;' .
																'var key=e.keyCode;' .
																'var keychar = String.fromCharCode(key);' .
																'keychar = keychar.toLowerCase();' .
																'if (key == 13) {timer=null;$(\'#' . $this->Table_Item_Name . '-table input, #' . $this->Table_Item_Name . '-table a\').attr(\'disabled\',\'disabled\');$(\'#' . $this->Table_Item_Name . '-table a\').attr(\'href\',\'#\').attr(\'onclick\',\'return false;\');filter();}' .
																'if (isAlphanumericInput(keychar) || key == 8 || key == 46) {' .
																	'if(timer) {window.clearTimeout(timer);}' .
																	'timer=window.setTimeout(function(){timer=null;$(\'#' . $this->Table_Item_Name . '-table input, #' . $this->Table_Item_Name . '-table a\').attr(\'disabled\',\'disabled\');$(\'#' . $this->Table_Item_Name . '-table a\').attr(\'href\',\'#\').attr(\'onclick\',\'return false;\');filter();},1000);' .
																	'theInput=null;' .
																'}' .
															'}' .
														');' .
													'});' .
												'</script>';

							$The_HTML .= $The_Live_Event;

						else :
						
							$The_HTML .= '<td>&nbsp;</td>';
							
						endif;
					
					endif;
			
				endforeach;
			
				if ($The_Number_Of_Single_Row_Actions > 0) :
					
					$The_HTML .= '<td></td>';
					
				endif;
				
				$The_HTML .= '</tr>';
				
			endif; // if ($The_Number_Of_Fields > 0)
			
		endif; // if ($this->Displays_Filter_Controls)
		
		if ($this->Displays_Row_Checkboxes && !$this->Displays_Filter_Controls && !$this->Displays_Column_Headers) :
		
			$The_HTML .= '<tr><td><input id="' . $this->Table_Item_Name . '-checkall" type="checkbox" />';
					
			$The_HTML .= '<img src="/mimik/mimik_images/blank.gif" onload="$(\'#' . $this->Table_Item_Name . '-checkall\').click(function(){var checked_status = this.checked; $(\'.' . $this->Table_Item_Name . '-check\').attr(\'checked\', checked_status).change(); });" />';

			$The_HTML .= '</td></tr>';
		
		endif; // ($this->Displays_The_Row_Checkboxes && !$this->Displays_The_Filter_Controls && !$this->Displays_The_Column_Headers) :
		
		$The_HTML .= '</thead><tbody>';
		
		if (is_array($this->Row_Array)) :
		
			$The_Row_Stripe_Class = 'odd';
		
			foreach ($this->Row_Array as $The_Row_Index => $The_Row) :
			
				if (in_array($The_Row['id'], $this->Highlighted_Row_ID_Array)) $The_Highlighted_Row_Class = ' highlighted';
				else $The_Highlighted_Row_Class = '';
			
				$The_HTML .= '<tr class="' . $The_Row_Stripe_Class . $The_Highlighted_Row_Class . '" id="' . $this->Table_Item_Name . '-row-' . $The_Row['id'] . '">';
				
				if ($The_Row_Stripe_Class == 'odd') $The_Row_Stripe_Class = 'even';
				else $The_Row_Stripe_Class = 'odd';
				
				if ($this->Displays_Row_Checkboxes) :
			
					$The_HTML .= '<td><input type="checkbox" class="' . $this->Table_Item_Name . '-check" id="' . $this->Table_Item_Name . '-check-' . $The_Row['id'] . '" name="' . $this->Table_Item_Name . '-id" value="' . $The_Row['id'] . '" />';
					
					$The_HTML .= '<img src="/mimik/mimik_images/blank.gif" onload="$(\'#' . $this->Table_Item_Name . '-check-' . $The_Row['id'] . '\').change(function(){if($(this).attr(\'checked\')) { $(this).parents().filter(\'tr\').addClass(\'selected\'); } else { $(this).parents().filter(\'tr\').removeClass(\'selected\'); }});" />';
					
					$The_HTML .= '</td>';
					
				endif;
				
				if (is_array($this->Field_Array)) foreach ($this->Field_Array as $The_Field) :
				
					if ($The_Field['hide'] == false) :
				
						$The_HTML .= '<td>';
						
						if ($The_Field['is_graphic_field']) :
						
							$The_HTML .= '<a class="info"><img src="' . $The_Field['graphic_map'][$The_Row[$The_Field['name']]] . '" alt="';
						
						endif;
						
						$The_HTML .= $The_Row[$The_Field['name']];
						
						if ($The_Field['is_graphic_field']) :
						
							$The_HTML .= '" /><span>';
							
							if ($The_Field['text_map'][$The_Row[$The_Field['name']]]) :
							
								$The_HTML .= $The_Field['text_map'][$The_Row[$The_Field['name']]];
								
							else :
							
								$The_HTML .= $The_Row[$The_Field['name']];
								
							endif;
							
							$The_HTML .= '</span></a>';
							
						endif;
						
						$The_HTML .= '</td>';
						
					endif;
						
				endforeach;
				
				if ($The_Number_Of_Single_Row_Actions > 0) :
						
					$The_HTML .= '<td class="single-row-actions"><div style="white-space: nowrap;">';
				
					foreach ($this->Single_Row_Action_Array as $The_Action_Index => $The_Action) :
					
						$Enable_The_Action = true;
					
						// if the action has a condition/conditions to be met, loop through
						// the condition(s) and set $Enable_The_Action accordingly
						if (is_array($The_Action['condition'])) :
						
							foreach ($The_Action['condition'] as $The_Condition_Key => $The_Condition_Value) :
							
								if ($The_Row[$The_Condition_Key] != $The_Condition_Value) :
								
									$Enable_The_Action = false;
									
								endif;
							
							endforeach;
							
						endif;
							
						if ($Enable_The_Action) :
				
							if ($The_Action['href']) :
							
								$The_HREF = $The_Action['href'];
						
								// this populates the HREF attribute appropriately,
								// replacing "id=[id]" with "id=" . $The_Row['id']
								preg_match_all('/\[[^\[\]]+\]/', $The_HREF, $The_Matches, PREG_SET_ORDER);
								
								if (is_array($The_Matches)) :
								
									foreach ($The_Matches as $The_Match) :
									
										$The_Match_Field_Name = str_replace(array('[',']'), NULL, $The_Match[0]);
									
										$The_HREF = str_replace($The_Match[0], urlencode($The_Row[$The_Match_Field_Name]), $The_HREF);
									
									endforeach;
									
								endif;
								
								// this populates the HREF attribute appropriately,
								// replacing "meta_id=<meta_id>" with "meta_id" = $this->Metadata_Array['meta_id']
								preg_match_all('/\<[^<>]+\>/', $The_HREF, $The_Matches, PREG_SET_ORDER);
								
								if (is_array($The_Matches)) :
								
									foreach ($The_Matches as $The_Match) :
									
										$The_Match_Field_Name = str_replace(array('<','>'), NULL, $The_Match[0]);
									
										$The_HREF = str_replace($The_Match[0], urlencode($this->Metadata_Array[$The_Match_Field_Name]), $The_HREF);
									
									endforeach;
									
								endif;
											
							else :
							
								$The_HREF = '#';
							
							endif;
							
							if ($The_Action['onclick']) :
							
								$The_OnClick = $The_Action['onclick'];
								
								// this populates the OnClick attribute appropriately,
								// replacing "id=[id]" with "id=" . $The_Row['id']
								preg_match_all('/\[[^\[\]]+\]/', $The_HREF, $The_Matches, PREG_SET_ORDER);
								
								if (is_array($The_Matches)) :
								
									foreach ($The_Matches as $The_Match) :
									
										$The_Match_Field_Name = str_replace(array('[',']'), NULL, $The_Match[0]);
									
										$The_OnClick = str_replace($The_Match[0], $The_Row[$The_Match_Field_Name], $The_OnClick);
									
									endforeach;
									
								endif;
											
							else :
							
								$The_OnClick = '';
							
							endif;
							
							if ($The_Action['onclick']) :
							
								$The_OnClick = $The_Action['onclick'];
								
								// this populates the OnClick attribute appropriately,
								// replacing "id=[id]" with "id=" . $The_Row['id']
								preg_match_all('/\[[^\[\]]+\]/', $The_OnClick, $The_Matches, PREG_SET_ORDER);
								
								if (is_array($The_Matches)) :
								
									foreach ($The_Matches as $The_Match) :
									
										$The_Match_Field_Name = str_replace(array('[',']'), NULL, $The_Match[0]);
									
										$The_OnClick = str_replace($The_Match[0], $The_Row[$The_Match_Field_Name], $The_OnClick);
									
									endforeach;
									
								endif;
											
							else :
							
								$The_OnClick = '';
							
							endif;
					
							$The_HTML .= '<a href="' . $The_HREF . '" onclick="' . $The_OnClick . '" class="info ' . $The_Action['class'] . '" target="' . $The_Action['target'] . '">';
							
							if ($The_Action['image']) :
							
								$The_HTML .= '<img src="' . $The_Action['image'] . '" class="single-row-action-image" />';
							
							else :
							
								$The_HTML .= $The_Action['display_name'];
								
							endif;
							
							if ($The_Action['tip']) :
							
								$The_HTML .= '<span>' . $The_Action['tip'] . '</span>';
								
							endif;
							
							$The_HTML .= '</a>';
							
							if (!$The_Action['image'] && $The_Action_Index < ($The_Number_Of_Single_Row_Actions - 1)) :
							
								$The_HTML .= ' | ';
								
							endif;
						
						else : // display the action, but without a link
						
							if ($The_Action['image']) :
							
								$The_HTML .= '<img src="' . $The_Action['image'] . '" />';
							
							else :
							
								$The_HTML .= $The_Action['display_name'];
								
							endif;
							
							if (!$The_Action['image'] && $The_Action_Index < ($The_Number_Of_Single_Row_Actions - 1)) :
							
								$The_HTML .= ' | ';
								
							endif;
						
						endif; // if ($Enable_The_Action)
						
					endforeach;
					
					$The_HTML .= '</div></td>';
					
				endif;
				
				$The_HTML .= '</tr>';
				
			endforeach;
			
			$The_HTML .= '</tbody>';
			
		else :
		
			$The_HTML .= '<tr><td style="text-align:center;" colspan="' . $The_Number_Of_Fields . '"><em>No rows</em></td></tr>';
			
		endif;
		
		if ($this->Multirow_Action_Array || $this->Displays_Pagination_Controls) :
			
			$The_HTML .= '<tfoot><tr><td class="footer" colspan="' . $The_Number_Of_Columns . '">';
			
			if ($this->Multirow_Action_Array) :
			
				$The_HTML .= '<div style="float:left;"><ul class="multirow-actions">';
				
				// $The_HTML .= '<li class="actions-header">Actions</li>';
				
				foreach ($this->Multirow_Action_Array as $The_Action) :
				
					$The_HREF = $The_Action['href'];
					
					// this populates the HREF attribute appropriately,
					// replacing "id=[id]" with "id=" . $The_Row['id']
					preg_match_all('/\[[^\[\]]+\]/', $The_HREF, $The_Matches, PREG_SET_ORDER);
					
					if (is_array($The_Matches)) :
					
						foreach ($The_Matches as $The_Match) :
						
							$The_Match_Field_Name = str_replace(array('[',']'), NULL, $The_Match[0]);
						
							$The_HREF = str_replace($The_Match[0], $The_Row[$The_Match_Field_Name], $The_HREF);
						
						endforeach;
						
					endif;
					
					// this populates the HREF attribute appropriately,
					// replacing "meta_id=[meta_id]" with "meta_id" = $this->Metadata_Array['meta_id']
					preg_match_all('/\<[^<>]+\>/', $The_HREF, $The_Matches, PREG_SET_ORDER);
					
					if (is_array($The_Matches)) :
					
						foreach ($The_Matches as $The_Match) :
						
							$The_Match_Field_Name = str_replace(array('<','>'), NULL, $The_Match[0]);
						
							$The_HREF = str_replace($The_Match[0], $this->Metadata_Array[$The_Match_Field_Name], $The_HREF);
						
						endforeach;
						
					endif;
				
					$The_HTML .= '<li><a href="' . $The_HREF . '" onclick="' . $The_Action['onclick'] . ' return false;">' . $The_Action['display_name'] . '</a></li>';
				
				endforeach;
				
				$The_HTML .= '</div>';
				
			endif; // if ($this->Multirow_Action_Array)
			
			if ($this->Displays_Pagination_Controls) :
			
				$The_HTML .= $The_HTML_For_The_Pagination_Links;
				
			endif; // if ($this->Displays_Pagination_Controls)
			
			$The_HTML .= '</td></tr></tfoot>';
			
		endif; // if ($this->Multirow_Action_Array || $this->Displays_Pagination_Controls)
		
		$The_HTML .= '</table>';
		
		return $The_HTML;
	
	} // Live_Site_HTML
	
	function Loads_The_Rows()
	{
		if ($this->Select_Query) :
		
			$this->Loads_The_Rows_Using_The_Select_Query();
			
			$this->Sets_The_Total_Number_Of_Rows_Using_The_Select_Query();
			
		elseif ($this->Function_Name) :
		
			$this->Loads_The_Rows_Using_The_Function();
			
			$this->Sets_The_Total_Number_Of_Rows_Using_The_Function();
			
		else :
		
			echo '<pre>Error: no select query or callback function specified</pre>';
		
			return false;
			
		endif;
		
		return true;
		
	} // Loads_The_Rows
	
	function Loads_The_Rows_Using_The_Function()
	{
		// overload in extension class
		
	} // function Loads_The_Rows_Using_The_Function
	
	function Loads_The_Rows_Using_The_Select_Query()
	{
		$The_SQL = $this->Select_Query;
		
		if ($this->Where_Condition) :
		
			if (strpos(strtolower($this->Select_Query), ' where ')) :
			
				$The_SQL .= ' AND ';
			
			else :
			
				$The_SQL .= ' WHERE ';
			
			endif;
			
			$The_SQL .= $this->Where_Condition;
			
		endif;
		
		if (is_array($this->Sort_Array)) :
		
			$The_SQL .= ' ORDER BY ';
			
			$The_Number_Of_Sort_Fields = count($this->Sort_Array);
			
			$i = 1;
			
			foreach ($this->Sort_Array as $The_Field => $The_Direction) :
			
				$The_SQL .= $The_Field . ' ' . $The_Direction;
				
				if ($i++ < $The_Number_Of_Sort_Fields) $The_SQL .= ',';
			
			endforeach;
			
		endif;
		
		if ($this->Row_Limit != 'All' && $this->Row_Limit) $The_SQL .= ' LIMIT ' . $this->Row_Limit . ' OFFSET ' . $this->Start_Row;

		$The_Result = $this->Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
		
		if (is_array($The_Result)) foreach ($The_Result as $The_Row) :
				
			$this->Row_Array[] = $The_Row;
		
		endforeach;
		
	} // Loads_The_Rows_Using_The_Select_Query
	
	function Sets_The_Total_Number_Of_Rows_Using_The_Function()
	{
		// overload in extended class

	} // Sets_The_Total_Number_Of_Rows_Using_The_Function
	
	function Sets_The_Total_Number_Of_Rows_Using_The_Select_Query()
	{
		$The_From_Clause = substr($this->Select_Query, strpos($this->Select_Query, 'FROM '));
	
		$The_SQL = 'SELECT count(*) AS `total` ' . $The_From_Clause;

		if ($this->Where_Condition) :
		
			if (strpos(strtolower($The_From_Clause), ' where ')) :

				$The_SQL .= ' AND '; 
				
			else :
			
				$The_SQL .= ' WHERE ';
				
			endif;
			
			$The_SQL .= $this->Where_Condition;
			
		endif;
		
		$The_Row_Count_Result = $this->Database_To_Use->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
		
		$The_Row_Count_Result_Rows = $The_Row_Count_Result[0]['total'];
		
		$this->Total_Number_Of_Rows = $The_Row_Count_Result_Rows;

	} // Sets_The_Total_Number_Of_Rows_Using_The_Select_Query
	
	function Uses_The_Database_Connection($The_Input_Database_To_Use)
	{
		$this->Database_To_Use = $The_Input_Database_To_Use;
		
	} // Uses_The_Database_Connection
	
	function Uses_The_Fields($The_Input_Field_Array)
	{
		usort($The_Input_Field_Array, 'Sorts_The_Fields');

		$this->Field_Array = $The_Input_Field_Array;
		
		$this->Creates_The_Permanent_Query_String();
		
	} // Uses_The_Fields
	
	function Uses_The_Filter_Information($The_Input_Filter_Array)
	{
		if (is_array($The_Input_Filter_Array)) :

			foreach ($The_Input_Filter_Array as $The_Filter_Key => $The_Filter_Value) :
		
				if ($The_Filter_Value == '') unset($The_Input_Filter_Array[$The_Filter_Key]);
		
			endforeach;
			
			if (count($The_Input_Filter_Array) == 0) :
			
				$The_Input_Filter_Array = NULL;
			
			endif;
			
		endif;
		
		$this->Filter_Array = $The_Input_Filter_Array;
		
		$this->Creates_The_Where_Condition();
		
	} // Uses_The_Filters
	
	function Uses_The_Focus_Element($The_Input_Focus_Element)
	{
		$this->Focus_Element = $The_Input_Focus_Element;
		
	} // Uses_The_Fields
	
	function Uses_The_Function($The_Input_Function_Name)
	{
		$this->Function_Name = $The_Input_Function_Name;
		
		$this->Select_Query = NULL;
		
		$this->Creates_The_Permanent_Query_String();
		
	} // Uses_The_Function
	
	function Uses_The_Metadata($The_Input_Metadata_Array)
	{
		$this->Metadata_Array = $The_Input_Metadata_Array;
	
		$this->Creates_The_Permanent_Query_String();
	
	} // Uses_The_Metadata
	
	function Uses_The_Multirow_Actions($The_Input_Multirow_Action_Array)
	{
		$this->Multirow_Action_Array = $The_Input_Multirow_Action_Array;
		
		$this->Creates_The_Permanent_Query_String();
		
	} // Uses_The_Single_Row_Actions
	
	function Uses_The_Pagination_Information($The_Input_Start_Row, $The_Input_Row_Limit)
	{
		$this->Start_Row = $The_Input_Start_Row;
	
		$this->Row_Limit = $The_Input_Row_Limit;
		
		if ($The_Input_Row_Limit == 'All') $this->Page_Number = 1;

		else $this->Page_Number = floor($The_Input_Start_Row / $The_Input_Row_Limit) + 1;
		
	} // Uses_The_Pagination_Information
	
	function Uses_The_Select_Query($The_Input_Select_Query)
	{
		$this->Select_Query = $The_Input_Select_Query;
		
		$this->Creates_The_Permanent_Query_String();
		
		$this->Function_Name = NULL;
		
	} // Uses_The_Select_Query
	
	function Uses_The_Single_Row_Actions($The_Input_Single_Row_Action_Array)
	{
		$this->Single_Row_Action_Array = $The_Input_Single_Row_Action_Array;
		
	} // Uses_The_Single_Row_Actions
	
	function Uses_The_Sort_Information($The_Input_Sort_Information)
	{
		$this->Sort_Array = NULL;
		
		if (is_array($The_Input_Sort_Information)) :
		
			$this->Sort_Array = $The_Input_Sort_Information;
			
		endif;
		
	} // Uses_The_Sort_Information
	
	function Uses_The_Table_Item_Name($The_Input_Table_Item_Name)
	{
		if ($The_Input_Table_Item_Name != '') $this->Table_Item_Name = $The_Input_Table_Item_Name;

		else $this->Table_Item_Name = 'data';
		
		$this->Creates_The_Permanent_Query_String();
		
	} // Uses_The_Select_Query

}; // class A_Multifunction_Data_Table

/***************************************************************************************************/

function Sorts_The_Fields($a, $b)
{
	if ($a['display_order_number'] == $b['display_order_number']) return 0;
	
	return ($a['display_order_number'] < $b['display_order_number']) ? -1 : 1;
}


function The_HTML_For_The_Pagination_Links(
							$The_Input_Parent_Element_ID, 
							$The_Input_Total_Number_Of_Rows, 
							$The_Input_Current_Results_Page_Number, 
							$The_Input_Table_Item_Name,
							$The_Input_URL_Parameters = '', 
							$The_Input_Number_Of_Records_Per_Results_Page = 10, 
							$The_Input_Number_Of_Page_Links = 10, 
							$The_Input_Indication_To_Display_Rows_Per_Page_Controls = true, 
							$The_Input_Permanent_Query_String = '')
{
	if ($The_Input_URL_Parameters == '') $The_Input_URL_Parameters = 'other: \'null\'';
	
	if ($The_Input_Number_Of_Records_Per_Results_Page == 'All') $The_Total_Number_Of_Pages = 1;
	elseif ($The_Input_Number_Of_Records_Per_Results_Page) $The_Total_Number_Of_Pages = ceil($The_Input_Total_Number_Of_Rows / $The_Input_Number_Of_Records_Per_Results_Page);
	else $The_Total_Number_Of_Pages = 1;
	
	if ($The_Input_Current_Results_Page_Number > $The_Total_Number_Of_Pages) $The_Input_Current_Results_Page_Number = $The_Total_Number_Of_Pages;

	$The_HTML = '<div class="pagination">';

	if ($The_Total_Number_Of_Pages > 1) :

		$The_HTML .= (($The_Input_Current_Results_Page_Number > 1)?'<a href="#" onclick="var go=false;if ($(\'.' . $The_Input_Table_Item_Name . '-check:checked\').length > 0){if(confirm(\'Row(s) will become unchecked if you proceed. Proceed?\')){go=true;}}else{go=true;}' .
																							'if(go){$.post(' .
																							'\'/mimik/mimik_support/show_data_table.php\', ' .
																							'{' . 
																								$The_Input_Permanent_Query_String . ', ' .
																								'start: \'0\', ' .
																								'limit: \'' . $The_Input_Number_Of_Records_Per_Results_Page . '\', ' . 
																								$The_Input_URL_Parameters . ', ' .
																								'rand: \'' . rand() . '\'' .
																							'},' .
																							'function(data) { ' .
																								'$(\'#' . $The_Input_Parent_Element_ID . '\').html(data);' .
																							'} ' .
																						');} return false;">First</a> ':'First ');

		$The_HTML .= (($The_Input_Current_Results_Page_Number > 1)?'<a href="#" onclick="var go=false;if ($(\'.' . $The_Input_Table_Item_Name . '-check:checked\').length > 0){if(confirm(\'Row(s) will become unchecked if you proceed. Proceed?\')){go=true;}}else{go=true;}' .
																							'if(go){$.post(' .
																							'\'/mimik/mimik_support/show_data_table.php\', ' .
																							'{' . 
																								$The_Input_Permanent_Query_String . ', ' .
																								'start: \'' . ($The_Input_Current_Results_Page_Number - 2) * $The_Input_Number_Of_Records_Per_Results_Page . '\', ' .
																								'limit: \'' . $The_Input_Number_Of_Records_Per_Results_Page . '\', ' . 
																								$The_Input_URL_Parameters . ', ' .
																								'rand: \'' . rand() . '\'' .
																							'},' .
																							'function(data) { ' .
																								'$(\'#' . $The_Input_Parent_Element_ID . '\').html(data);' .
																							'} ' .
																						');} return false;">Prev</a> ':'Prev ');

		$The_First_Page_Link_Number = (($The_Input_Current_Results_Page_Number - floor($The_Input_Number_Of_Page_Links / 2)) >= 1) ? $The_Input_Current_Results_Page_Number - floor($The_Input_Number_Of_Page_Links / 2) : 1;

		$The_Last_Page_Link_Number = (($The_Input_Current_Results_Page_Number + ceil($The_Input_Number_Of_Page_Links / 2)) < $The_Total_Number_Of_Pages) ? $The_Input_Current_Results_Page_Number + ceil($The_Input_Number_Of_Page_Links / 2) : $The_Total_Number_Of_Pages;

		if ($The_First_Page_Link_Number > 1) $The_HTML .= '&hellip; ';

		for ($The_Index = $The_First_Page_Link_Number; $The_Index <= $The_Last_Page_Link_Number; $The_Index++) :

			$The_HTML .= (($The_Input_Current_Results_Page_Number == $The_Index)?$The_Index . ' ':'<a href="#" onclick="var go=false;if ($(\'.' . $The_Input_Table_Item_Name . '-check:checked\').length > 0){if(confirm(\'Row(s) will become unchecked if you proceed. Proceed?\')){go=true;}}else{go=true;}' .
																														'if(go){$.post(' .
																															'\'/mimik/mimik_support/show_data_table.php\', ' .
																															'{' . 
																																$The_Input_Permanent_Query_String . ', ' .
																																'start: \'' . ($The_Index - 1) * $The_Input_Number_Of_Records_Per_Results_Page . '\', ' . 
																																'limit: \'' . $The_Input_Number_Of_Records_Per_Results_Page . '\', ' . 
																																$The_Input_URL_Parameters . ', ' . 
																																'rand: \'' . rand() . '\'' .
																															'}, ' .
																															'function(data) { ' .
																																'$(\'#' . $The_Input_Parent_Element_ID . '\').html(data);' .
																															'} ' .
																														');} return false;">' . $The_Index . '</a> ');

		endfor;
		
		if ($The_Last_Page_Link_Number < $The_Total_Number_Of_Pages) $The_HTML .= '&hellip; ';

		$The_HTML .=  (($The_Input_Current_Results_Page_Number < $The_Total_Number_Of_Pages)?'<a href="#" onclick="var go=false;if ($(\'.' . $The_Input_Table_Item_Name . '-check:checked\').length > 0){if(confirm(\'Row(s) will become unchecked if you proceed. Proceed?\')){go=true;}}else{go=true;}' .
																													'if(go){$.post(' .
																														'\'/mimik/mimik_support/show_data_table.php\', ' .
																														'{' . 
																															$The_Input_Permanent_Query_String . ', ' .
																															'start: \'' . $The_Input_Current_Results_Page_Number * $The_Input_Number_Of_Records_Per_Results_Page . '\', ' .
																															'limit: \'' . $The_Input_Number_Of_Records_Per_Results_Page . '\', ' . 
																															$The_Input_URL_Parameters . ', ' .
																															'rand: \'' . rand() . '\'' .
																														'}, ' .
																														'function(data) { ' .
																															'$(\'#' . $The_Input_Parent_Element_ID . '\').html(data);' .
																														'} ' .
																													');} return false;">Next</a> ':'Next ');

		if (($The_Input_Total_Number_Of_Rows % $The_Input_Number_Of_Records_Per_Results_Page) === 0) :
		
			$The_Last_Page_Offset = $The_Input_Number_Of_Records_Per_Results_Page;
			
		else :
		
			$The_Last_Page_Offset = ($The_Input_Total_Number_Of_Rows % $The_Input_Number_Of_Records_Per_Results_Page);
			
		endif;
		
		$The_HTML .= (($The_Input_Current_Results_Page_Number < $The_Total_Number_Of_Pages)?'<a href="#" onclick="var go=false;if ($(\'.' . $The_Input_Table_Item_Name . '-check:checked\').length > 0){if(confirm(\'Row(s) will become unchecked if you proceed. Proceed?\')){go=true;}}else{go=true;}' .
																													'if(go){$.post(' .
																														'\'/mimik/mimik_support/show_data_table.php\', ' . 
																														'{' .
																															$The_Input_Permanent_Query_String . ', ' .
																															'start: \'' . ($The_Input_Total_Number_Of_Rows - $The_Last_Page_Offset) . '\', ' .
																															'limit: \'' . $The_Input_Number_Of_Records_Per_Results_Page . '\', ' . 
																															$The_Input_URL_Parameters . ', ' .
																															'rand: \'' . rand() . '\'' .
																														'}, ' .
																														'function(data) { ' .
																															'$(\'#' . $The_Input_Parent_Element_ID . '\').html(data);' .
																														'} ' .
																													');} return false;">Last</a> ':'Last ');
		
		$The_HTML .= '<br />';

	endif;
	
	if ($The_Input_Indication_To_Display_Rows_Per_Page_Controls) :
	
		$The_Rows_Per_Page_Values = array('10', '25', '50', 'All');
		
		if (!in_array($The_Input_Number_Of_Records_Per_Results_Page, $The_Rows_Per_Page_Values)) :
		
			$The_Rows_Per_Page_Values[] = $The_Input_Number_Of_Records_Per_Results_Page;
		
		endif;
		
		sort($The_Rows_Per_Page_Values);
		
		$The_HTML .= '<select onchange="var go=false;if ($(\'.' . $The_Input_Table_Item_Name . '-check:checked\').length > 0){if(confirm(\'Row(s) will become unchecked if you proceed. Proceed?\')){go=true;}}else{go=true;}' .
										'if(go){$.post(\'' . 
											'/mimik/mimik_support/show_data_table.php\', ' .
											'{' . 
												$The_Input_Permanent_Query_String . ', ' . 
												'start: \'0\', ' . 
												'limit: this.options[this.selectedIndex].value, ' . 
												$The_Input_URL_Parameters . ', ' .
												'rand: \'' . rand() . '\'' .
											'}, ' .
											'function(data) { ' .
												'$(\'#' . $The_Input_Parent_Element_ID . '\').html(data);' .
											'} ' .
										');}">';
		
		foreach ($The_Rows_Per_Page_Values as $The_Row_Per_Page_Value) :
		
			$The_HTML .= '<option value="' . $The_Row_Per_Page_Value . '"';
			
			if ($The_Input_Number_Of_Records_Per_Results_Page == $The_Row_Per_Page_Value) $The_HTML .= ' selected="selected"';
			
			$The_HTML .= '>' . $The_Row_Per_Page_Value . '</option>';
	
		endforeach;
		
		$The_Number_Of_Records_Per_Results_Page = ($The_Input_Number_Of_Records_Per_Results_Page == 'All') ? $The_Input_Total_Number_Of_Rows : $The_Input_Number_Of_Records_Per_Results_Page;
		
		$The_HTML .= '</select> rows per page (' . max(0, (($The_Input_Current_Results_Page_Number - 1) * $The_Input_Number_Of_Records_Per_Results_Page + 1)) . ' - ' . max(0, min(($The_Input_Current_Results_Page_Number * $The_Number_Of_Records_Per_Results_Page), $The_Input_Total_Number_Of_Rows)) . ' of ' . $The_Input_Total_Number_Of_Rows . ')';

	endif; // if ($The_Input_Indication_To_Display_Rows_Per_Page_Controls)

	$The_HTML .=  '</div>';
	
	return $The_HTML;

} // The_HTML_For_The_Pagination_Links

?>