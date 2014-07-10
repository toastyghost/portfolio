<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/configuration_utilities.inc.php';

class A_Database_Interface
{
    var $Host_Name;
	var $Database_Name = "Fred";
	var $User_Name;
	var $Database_Password;
	var $Database_Connection;

########################################################################################################################
	
	// Bobby 2008-07-26
	function Dump_Member_Variables()
	{
		return array( "Host_Name" => $this->Host_Name,
					  "Database_Name" => $this->Database_Name,
					  "User_Name" => $this->User_Name,
					  "Database_Password" => $this->Database_Password,
					  "Database_Connection" => $this->Database_Connection );
					  
	}

########################################################################################################################
	
	function Will_Connect_Using_The_Information_In( $The_Database_Configuration_File )
	{
	   $The_Database_Configuration_Entry_For = The_Array_Of_Configuration_Items_In( $The_Database_Configuration_File );

	    $this->Will_Connect_Using( $The_Database_Configuration_Entry_For[ 'Host_Name' ],
		                           $The_Database_Configuration_Entry_For[ 'Database_Name' ],
								   $The_Database_Configuration_Entry_For[ 'User_Name' ],
								   $The_Database_Configuration_Entry_For[ 'Password' ]       );
								   
	}  // end Will_Connect_Using_The_Information_In
	
########################################################################################################################

    function Will_Connect_Using( $Host, $Database, $User, $Password )
	{
		$this->Host_Name = $Host;
		$this->Database_Name = $Database;
		$this->User_Name = $User;
		$this->Database_Password = $Password;
		
	}  // Will_Connect_Using -- this should be replaced by a constructor
	
########################################################################################################################
	
	function Establishes_A_Connection()
	{
	    $this->Database_Connection = mysql_connect( $this->Host_Name, $this->User_Name, $this->Database_Password )
		                                 or die( "Unable to connect to database server" );
									 
		mysql_select_db( $this->Database_Name, $this->Database_Connection )
		    or die ( "Unable to select database " . $this->Database_Name );
			
		$this->Indicate_UTF8_Encoding();
	}

########################################################################################################################
	
	function Closes_A_Connection()
	{
		return mysql_close($this->Database_Connection) or die( "Unable to connect to database server" );
		
	}

########################################################################################################################


	function Indicate_UTF8_Encoding()
	{
		$The_Result = mysql_query('SET NAMES utf8;');
		$The_Result = mysql_query('SET CHARACTER_SET utf8;');
	}


########################################################################################################################

	function All_Rows_From_The_Database_Corresponding_To_Custom_Query( $The_Custom_SQL_Query )
	{
		$sql = '';
		$sql = $The_Custom_SQL_Query;
	
		$result = mysql_query( $sql, $this->Database_Connection )
						  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
	
		while ($result_row = mysql_fetch_array( $result, MYSQL_ASSOC )) :
	
			$result_array[] = $result_row;
			
		endwhile;
			
		if ($result_array) :
			
			return $result_array;
				
		else :
			
			return NULL;
				
		endif;
	}

########################################################################################################################

	function Row_Count_Corresponding_To( $The_Table_Name,
										$The_Name_Of_The_Count_Field,
										$The_Value_Of_The_Count_Field )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Name_Of_The_Count_Field = '';
		$The_Local_Value_Of_The_Count_Field = '';
		
		$result_array = array();
		
		if ($The_Local_Name_Of_The_Key_Field !== '' && $The_Local_Value_Of_The_Key_Field !== '') :
		
			$sql .= 'SELECT Count('
						. ') FROM `' . $The_Table_Name . '` ';
		
			$sql .=	' WHERE `' . $The_Name_Of_The_Count_Field . "` = '" . $The_Value_Of_The_Count_Field . "' ";
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
		
		$result_array = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$count_array_index = 'COUNT(' . $The_Local_Value_Of_The_Count_Field . ')';
		$rowcount = $result_array[0][$count_array_index];
		
		if ($rowcount) :
		
			return $rowcount;
			
		else :
		
			return NULL;
			
		endif;
		
	}

########################################################################################################################

	function All_Rows_From_The_Database_Corresponding_To( $The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Key_Field = '',
										$The_Value_Of_The_Key_Field = '',
										$The_Name_Of_The_Primary_Order_By_Field = '',
										$The_Name_Of_The_Primary_Order_Direction = 'ASC',
										$The_Name_Of_The_Secondary_Order_By_Field = '',
										$The_Name_Of_The_Secondary_Order_Direction = 'ASC' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Name_Of_The_Key_Field = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$result_array = array();
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		$The_Local_Name_Of_The_Key_Field = mysql_real_escape_string($The_Name_Of_The_Key_Field);
		
		$The_Local_Value_Of_The_Key_Field = mysql_real_escape_string($The_Value_Of_The_Key_Field);
		
		$The_Local_Name_Of_The_Primary_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Primary_Order_By_Field);
		
		$The_Local_Name_Of_The_Primary_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Primary_Order_Direction);
		
		$The_Local_Name_Of_The_Secondary_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Secondary_Order_By_Field);
		
		$The_Local_Name_Of_The_Secondary_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Secondary_Order_Direction);
		
		$sql .= 'SELECT * '
					. ' FROM `' . $The_Local_Table_Name . '` ';
					
		if ($The_Local_Name_Of_The_Key_Field !== '' && $The_Local_Value_Of_The_Key_Field !== '') :
		
			$sql .=	' WHERE `' . $The_Local_Name_Of_The_Key_Field . "` = '" . $The_Local_Value_Of_The_Key_Field . "' ";
			
		endif;
					
		if ($The_Local_Name_Of_The_Primary_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Primary_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Primary_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Primary_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Primary_Order_Direction;
				
				if ($The_Local_Name_Of_The_Secondary_Order_By_Field !== '') :
		
					$sql .= ', ' . $The_Local_Name_Of_The_Secondary_Order_By_Field;
					
					if ($The_Local_Name_Of_The_Secondary_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Secondary_Order_Direction == 'DESC') :
					
						$sql .= ' ' . $The_Local_Name_Of_The_Secondary_Order_Direction;
						
					endif;
					
				endif;
				
			endif;
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
		
		while ($result_row = mysql_fetch_array( $result, MYSQL_ASSOC )) :
		
			$result_array[] = $result_row;
		
		endwhile;
		
		if ($result_array) :
		
			return $result_array;
			
		else :
		
			return NULL;
			
		endif;
	
	} // All_Rows_From_The_Database_Corresponding_To
	
########################################################################################################################

	function All_Rows_From_The_Database_Corresponding_To_Multiple_Conditions(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Array_Of_Conditions = array(), // array{ key=>value, key=>value... }
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC' )
	{
		/*echo "<pre>All_Rows_From_The_Database_Corresponding_To_Multiple_Conditions($The_Table_Name, ";
		print_r($The_Array_Of_Conditions);
		echo "<pre>, $The_Name_Of_The_Order_By_Field, $The_Name_Of_The_Order_Direction</pre>";*/
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Array_Of_Conditions = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$result_array = array();
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		foreach ($The_Array_Of_Conditions as $The_Condition_Field_Name => $The_Condition_Field_Value) :
		
			if (is_num($The_Condition_Field_Name)) :

				$The_Local_Array_Of_Complex_Conditions[] = $The_Condition_Field_Value;

			else :

				$The_Local_Array_Of_Conditions[mysql_real_escape_string($The_Condition_Field_Name)] = mysql_real_escape_string($The_Condition_Field_Value);

			endif;
		
		endforeach;
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT * '
					. ' FROM `' . $The_Local_Table_Name . '` '
					. ' WHERE ';
					
		if (is_array($The_Local_Array_Of_Conditions)) foreach ($The_Local_Array_Of_Conditions as $The_Local_Condition_Field_Name => $The_Local_Condition_Field_Value) :
		
			$sql .= '(`' . $The_Local_Condition_Field_Name . "` = '" . $The_Local_Condition_Field_Value . "') AND ";
			
		endforeach;

		if (is_array($The_Local_Array_Of_Complex_Conditions)) foreach ($The_Local_Array_Of_Complex_Conditions as $The_Complex_Condition) :

			$sql .= '(' . $The_Complex_Condition . ') AND ';

		endforeach;
		
		$sql .= "1 ";
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		while ($result_row = mysql_fetch_array( $result, MYSQL_ASSOC )) :
		
			$result_array[] = $result_row;
		
		endwhile;
		
		if ($result_array) :
		
			return $result_array;
			
		else :
		
			return NULL;
			
		endif;
	
	} // All_Rows_From_The_Database_Corresponding_To_Multiple_Conditions
	
########################################################################################################################

	function All_Values_From_The_Database_Corresponding_To(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Target_Field,
										$The_Name_Of_The_Key_Field = '',
										$The_Value_Of_The_Key_Field = '',
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC',
										$The_Input_Limit = '' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Name_Of_The_Key_Field = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Target_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$result_array = array();
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		$The_Local_Name_Of_The_Key_Field = mysql_real_escape_string($The_Name_Of_The_Key_Field);
		
		$The_Local_Value_Of_The_Key_Field = mysql_real_escape_string($The_Value_Of_The_Key_Field);
		
		$The_Local_Name_Of_The_Target_Field = mysql_real_escape_string($The_Name_Of_The_Target_Field);
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT `' . $The_Local_Name_Of_The_Target_Field . '` '
					. ' FROM `' . $The_Local_Table_Name . '` ';
					
		if ($The_Local_Name_Of_The_Key_Field !== '' && $The_Local_Value_Of_The_Key_Field !== '') :
		
			$sql .= ' WHERE `' . $The_Local_Name_Of_The_Key_Field . "` = '" . $The_Local_Value_Of_The_Key_Field . "' ";
		
		endif;
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		if ($The_Input_Limit !== '' && $The_Input_Limit > 0) :
		
			$sql .= ' LIMIT ' . $The_Input_Limit;
			
		endif;
		
//		debug($sql);
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		while ($result_row = mysql_fetch_array( $result, MYSQL_ASSOC )) :
		
			$result_array[] = $result_row;
		
		endwhile;
		
		if ($result_array) :
		
			return $result_array;
			
		else :
		
			return NULL;
			
		endif;
	
	} // All_Values_From_The_Database_Corresponding_To
	
########################################################################################################################

	function All_Values_From_The_Database_Corresponding_To_Inequality_Condition(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Target_Field,
										$The_Name_Of_The_Key_Field = '',
										$The_Value_Of_The_Key_Field = '',
										$The_Inequality_Operator = 1, // if >0, apply GREATER THAN; if <0, apply LESS THAN; if=0, apply EQUALS
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Name_Of_The_Key_Field = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Target_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$result_array = array();
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		$The_Local_Name_Of_The_Key_Field = mysql_real_escape_string($The_Name_Of_The_Key_Field);
		
		$The_Local_Value_Of_The_Key_Field = mysql_real_escape_string($The_Value_Of_The_Key_Field);
		
		$The_Local_Value_Of_The_Inequality_Operator = mysql_real_escape_string($The_Inequality_Operator);
		
		$The_Local_Name_Of_The_Target_Field = mysql_real_escape_string($The_Name_Of_The_Target_Field);
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT `' . $The_Local_Name_Of_The_Target_Field . '` '
					. ' FROM `' . $The_Local_Table_Name . '` '
					. ' WHERE `' . $The_Local_Name_Of_The_Key_Field . "` ";
					
		if ($The_Local_Value_Of_The_Inequality_Operator > 0) :
		
			$sql .= '>';
			
		elseif ($The_Local_Value_Of_The_Inequality_Operator < 0) :
		
			$sql .= '<';
			
		else :
		
			$sql .= '=';
			
		endif;
		
		$sql .= " '" . $The_Local_Value_Of_The_Key_Field . "' ";
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		while ($result_row = mysql_fetch_array( $result, MYSQL_ASSOC )) :
		
			$result_array[] = $result_row;
		
		endwhile;
		
		if ($result_array) :
		
			return $result_array;
			
		else :
		
			return NULL;
			
		endif;
	
	} // All_Values_From_The_Database_Corresponding_To_Inequality_Condition
	
########################################################################################################################

	function All_Values_From_The_Database_Corresponding_To_Multiple_Conditions(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Target_Field,
										$The_Array_Of_Conditions = array(), // array{ key=>value, key=>value... }
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC',
										$The_Input_Limit = '',
										$The_Input_First_Record = '' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Array_Of_Conditions = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Target_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$result_array = array();
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		if (is_array($The_Array_Of_Conditions)) :
		
			foreach ($The_Array_Of_Conditions as $The_Condition_Field_Name => $The_Condition_Field_Value) :
		
				$The_Local_Array_Of_Conditions[mysql_real_escape_string($The_Condition_Field_Name)] = mysql_real_escape_string($The_Condition_Field_Value);
		
			endforeach;
		
		endif;
		
		$The_Local_Name_Of_The_Target_Field = mysql_real_escape_string($The_Name_Of_The_Target_Field);
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT `' . $The_Local_Name_Of_The_Target_Field . '` '
					. ' FROM `' . $The_Local_Table_Name . '` '
					. ' WHERE ';
					
		if (is_array($The_Local_Array_Of_Conditions)) :
		
			foreach ($The_Local_Array_Of_Conditions as $The_Local_Condition_Field_Name => $The_Local_Condition_Field_Value) :
			
				if (is_numeric($The_Local_Condition_Field_Name)) :
				
					$sql .= '(' . $The_Local_Condition_Field_Value . ') AND ';
					
				else :
					
					if ($The_Local_Condition_Field_Value) :
					
						$sql .= '(`' . $The_Local_Condition_Field_Name . "` = '" . $The_Local_Condition_Field_Value . "') AND ";
					
					else :
					
						$sql .= '((`' . $The_Local_Condition_Field_Name . "` IS NULL ) OR ";
						$sql .= '(`' . $The_Local_Condition_Field_Name . "` = '') OR ";
						$sql .= '(`' . $The_Local_Condition_Field_Name . "` = '0')) AND ";
						
					endif;
										
				endif;
				
			endforeach;
			
		endif;
		
		$sql .= "1 ";
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		if ($The_Input_Limit !== '' && $The_Input_Limit > 0) :
		
			$sql .= ' LIMIT ';
			
			if ($The_Input_First_Record) $sql .= $The_Input_First_Record . ', ';
			
			$sql .= $The_Input_Limit;
			
		endif;

//		debug($sql);

		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  //or die('Unknown field');
		
		while ($result_row = mysql_fetch_array( $result, MYSQL_ASSOC )) :
		
			$result_array[] = $result_row;
		
		endwhile;
		
		if ($result_array) :

			return $result_array;
			
		else :
		
			return NULL;
			
		endif;
	
	} // All_Values_From_The_Database_Corresponding_To_Multiple_Conditions
	
########################################################################################################################

	function All_Values_From_The_Database_Corresponding_To_Multiple_Inequality_Conditions(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Target_Field,
										$The_Array_Of_Conditions = array(), // array{ key=>value, key=>value... }
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC' )
	{
	
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Array_Of_Conditions = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Target_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$result_array = array();
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		foreach ($The_Array_Of_Conditions as $The_Condition_Field_Name => $The_Condition_Field_Value) :
		
			$The_Local_Array_Of_Condition_Values = array();
		
			foreach ($The_Condition_Field_Value as $The_Field_Name => $The_Field_Value) :
		
				$The_Local_Array_Of_Condition_Values[mysql_real_escape_string($The_Field_Name)] = mysql_real_escape_string($The_Field_Value);
				
			endforeach;
		
			$The_Local_Array_Of_Conditions[] = $The_Local_Array_Of_Condition_Values;
		
		endforeach;
		
		$The_Local_Name_Of_The_Target_Field = mysql_real_escape_string($The_Name_Of_The_Target_Field);
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT `' . $The_Local_Name_Of_The_Target_Field . '` '
					. ' FROM `' . $The_Local_Table_Name . '` '
					. ' WHERE ';
					
		foreach ($The_Local_Array_Of_Conditions as $key => $The_Local_Condition) :
		
			$sql .= '(`' . $The_Local_Condition['field_name'] . '` ';
			
			if ($The_Local_Condition['inequality_operator'] > 0) : 
			
				$sql .= '>';
				
			elseif ($The_Local_Condition['inequality_operator'] < 0) :
			
				$sql .= '<';
				
			else :
			
				$sql .= '=';
				
			endif;
			
			$sql .= " '" . $The_Local_Condition['field_value'] . "') AND ";
			
		endforeach;
		
		$sql .= "1 ";
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		while ($result_row = mysql_fetch_array( $result, MYSQL_ASSOC )) :
		
			$result_array[] = $result_row;
		
		endwhile;
		
		if ($result_array) :
		
			return $result_array;
			
		else :
		
			return NULL;
			
		endif;
	
	} // All_Values_From_The_Database_Corresponding_To_Multiple_Inequality_Conditions
	
########################################################################################################################

	function Executes_The_SQL_Query($The_Input_SQL_Query)
	{
		$The_Result = mysql_query( $The_Input_SQL_Query, $this->Database_Connection )
					  or die( "SQL: $The_Input_SQL_Query<br/><br/>MySQL_ERROR: " . mysql_error());
					  
	}
	
########################################################################################################################

	function First_Row_From_The_Database_Corresponding_To(
										$The_Table_Name,
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC',
										$The_Name_Of_The_Key_Field = '',
										$The_Value_Of_The_Key_Field = '' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Name_Of_The_Key_Field = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		$The_Local_Name_Of_The_Key_Field = mysql_real_escape_string($The_Name_Of_The_Key_Field);
		
		$The_Local_Value_Of_The_Key_Field = mysql_real_escape_string($The_Value_Of_The_Key_Field);
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT * '
					. ' FROM `' . $The_Local_Table_Name . '` ';
					
		if ($The_Local_Name_Of_The_Key_Field !== '' && $The_Local_Value_Of_The_Key_Field !== '') :
		
			$sql .= ' WHERE `' . $The_Local_Name_Of_The_Key_Field . "` = '" . $The_Local_Value_Of_The_Key_Field . "' ";
			
		endif;
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
//if ($The_Table_Name == 'mimik_Blog_Posts') debug($sql);		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		$result_row = mysql_fetch_array( $result, MYSQL_ASSOC );
		
		return $result_row;
		
	} // First_Row_From_The_Database_Corresponding_To
	
########################################################################################################################

	function First_Row_From_The_Database_Corresponding_To_Multiple_Conditions(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Array_Of_Conditions = array(), // array{ key=>value, key=>value... }
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Array_Of_Conditions = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		foreach ($The_Array_Of_Conditions as $The_Condition_Field_Name => $The_Condition_Field_Value) :
		
			$The_Local_Array_Of_Conditions[mysql_real_escape_string($The_Condition_Field_Name)] = mysql_real_escape_string($The_Condition_Field_Value);
		
		endforeach;
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT * '
					. ' FROM `' . $The_Local_Table_Name . '` '
					. ' WHERE ';
					
		foreach ($The_Local_Array_Of_Conditions as $The_Local_Condition_Field_Name => $The_Local_Condition_Field_Value) :
		
			$sql .= '(`' . $The_Local_Condition_Field_Name . "` = '" . $The_Local_Condition_Field_Value . "') AND ";
			
		endforeach;
		
		$sql .= "1 ";
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		$result_row = mysql_fetch_array( $result, MYSQL_ASSOC );
		
		return $result_row;
	
	} // First_Row_From_The_Database_Corresponding_To_Multiple_Conditions
	
########################################################################################################################

	function First_Value_From_The_Database_Corresponding_To(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Target_Field,
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC',
										$The_Name_Of_The_Key_Field = '',  // the set of field name/value pairs
										$The_Value_Of_The_Key_Field = '' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Name_Of_The_Key_Field = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Target_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		$The_Local_Name_Of_The_Key_Field = mysql_real_escape_string($The_Name_Of_The_Key_Field);
		
		$The_Local_Value_Of_The_Key_Field = mysql_real_escape_string($The_Value_Of_The_Key_Field);
		
		$The_Local_Name_Of_The_Target_Field = mysql_real_escape_string($The_Name_Of_The_Target_Field);
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT `' . $The_Local_Name_Of_The_Target_Field . '` '
					. ' FROM `' . $The_Local_Table_Name . '` ';
					
		if ($The_Local_Name_Of_The_Key_Field !== '' && $The_Local_Value_Of_The_Key_Field !== '') :
		
			$sql .= ' WHERE `' . $The_Local_Name_Of_The_Key_Field . "` = '" . $The_Local_Value_Of_The_Key_Field . "' ";
			
		endif;
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		$result_row = mysql_fetch_array( $result, MYSQL_ASSOC );
		
		if ($result_row[$The_Name_Of_The_Target_Field]) :
		
			return $result_row[$The_Name_Of_The_Target_Field];
			
		else :
		
			return NULL;
			
		endif;
	
	} // First_Value_From_The_Database_Corresponding_To
	
########################################################################################################################

	function First_Value_From_The_Database_Corresponding_To_Multiple_Conditions(
										$The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Target_Field,
										$The_Array_Of_Conditions = array(), // array{ key=>value, key=>value... }
										$The_Name_Of_The_Order_By_Field = '',
										$The_Name_Of_The_Order_Direction = 'ASC' )
	{
		$sql = '';
		$The_Local_Table_Name = '';
		$The_Local_Array_Of_Conditions = '';
		$The_Local_Value_Of_The_Key_Field = '';
		$The_Local_Name_Of_The_Target_Field = '';
		$The_Local_Name_Of_The_Order_By_Field = '';
		$The_Local_Name_Of_The_Order_Direction_Field = '';
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		foreach ($The_Array_Of_Conditions as $The_Condition_Field_Name => $The_Condition_Field_Value) :
		
			$The_Local_Array_Of_Conditions[mysql_real_escape_string($The_Condition_Field_Name)] = mysql_real_escape_string($The_Condition_Field_Value);
		
		endforeach;
		
		$The_Local_Name_Of_The_Target_Field = mysql_real_escape_string($The_Name_Of_The_Target_Field);
		
		$The_Local_Name_Of_The_Order_By_Field = mysql_real_escape_string($The_Name_Of_The_Order_By_Field);
		
		$The_Local_Name_Of_The_Order_Direction = mysql_real_escape_string($The_Name_Of_The_Order_Direction);
		
		$sql .= 'SELECT `' . $The_Local_Name_Of_The_Target_Field . '` '
					. ' FROM `' . $The_Local_Table_Name . '` '
					. ' WHERE ';
					
		foreach ($The_Local_Array_Of_Conditions as $The_Local_Condition_Field_Name => $The_Local_Condition_Field_Value) :
		
			$sql .= '(`' . $The_Local_Condition_Field_Name . "` = '" . $The_Local_Condition_Field_Value . "') AND ";
			
		endforeach;
		
		$sql .= "1 ";
		
		if ($The_Local_Name_Of_The_Order_By_Field !== '') :
		
			$sql .= 'ORDER BY `' . $The_Local_Name_Of_The_Order_By_Field . '`';
			
			if ($The_Local_Name_Of_The_Order_Direction == 'ASC' || $The_Local_Name_Of_The_Order_Direction == 'DESC') :
			
				$sql .= ' ' . $The_Local_Name_Of_The_Order_Direction;
				
			endif;
			
		endif;
		
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		$result_row = mysql_fetch_array( $result, MYSQL_ASSOC );
		
		if ($result_row[$The_Name_Of_The_Target_Field]) :
		
			return $result_row[$The_Name_Of_The_Target_Field];
			
		else :
		
			return NULL;
			
		endif;
	
	} // First_Value_From_The_Database_Corresponding_To_Multiple_Conditions
	
########################################################################################################################


	function Row_From_The_Database_Corresponding_To( $The_Table_Name,  // the table from which the row will be selected
										$The_Name_Of_The_Key_Field,  // the set of field name/value pairs
										$The_Value_Of_The_Key_Field )
	{
		$The_Local_Table_Name = '';
		$The_Local_Name_Of_The_Key_Field = '';
		$The_Local_Value_Of_The_Key_Field = '';
		
		$The_Local_Table_Name = mysql_real_escape_string($The_Table_Name);
		
		$The_Local_Name_Of_The_Key_Field = mysql_real_escape_string($The_Name_Of_The_Key_Field);
		
		$The_Local_Value_Of_The_Key_Field = mysql_real_escape_string($The_Value_Of_The_Key_Field);
		
		$sql .= 'SELECT * '
					. ' FROM `' . $The_Local_Table_Name . '` '
					. ' WHERE `' . $The_Local_Name_Of_The_Key_Field . "` = '" . $The_Local_Value_Of_The_Key_Field . "'";
					
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
					  
		$result_row = mysql_fetch_array( $result, MYSQL_ASSOC );
					  
		return $result_row;
		
	}  // Row_From_The_Database_Corresponding_To
	
########################################################################################################################
	
	function Update_A_Database_Row( $The_Table_Name,
									$The_Key_Element_Name,
									$The_Key_Element_Value,
									$The_Set_Of_Database_Field_Value_Pairs )
	{
		$The_Set_Command = "SET ";
		
		if (count($The_Set_Of_Database_Field_Value_Pairs) > 0) :
		
			foreach( $The_Set_Of_Database_Field_Value_Pairs as $The_Field_Name => $The_Field_Value ) :
			
				if ($The_Field_Name == 'modify_date' && $The_Field_Value == 'NOW()') :
				
					$The_Set_Command .= "`" . $The_Field_Name . "`=" . addslashes($The_Field_Value) . ", ";
				
				else :
				
					$The_Set_Command .= "`" . $The_Field_Name . "`='" . addslashes($The_Field_Value) . "', ";
				
				endif;
			
			endforeach;
			
			$The_Set_Command = substr_replace( $The_Set_Command, " ", strrpos( $The_Set_Command, "," ) );
			
			//   echo "The_Set_Command = |" . $The_Set_Command . "|<br/>";
			
			$sql = "UPDATE `$The_Table_Name` " .
								$The_Set_Command .
								"WHERE `$The_Key_Element_Name` = '$The_Key_Element_Value' ";
			
			$result = mysql_query( $sql, $this->Database_Connection )
						or die( "$sql<br/><br/>" . mysql_error());
		
		endif;

	}  // end Update_A_Database_Row


########################################################################################################################

	function Update_The_Value_For_A_Single_Entry( $The_Table_Name,
												  $The_Key_Element_Name,  $The_Key_Value,
												  $The_Element_To_Update, $The_New_Value )
	{
		$sql = "UPDATE `$The_Table_Name` " .
			   "SET `$The_Element_To_Update` = '$The_New_Value' " .
			   "WHERE `$The_Key_Element_Name` = '$The_Key_Value' " .
			   "LIMIT 1 ";
			   
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "$sql<br/><br/>" . mysql_error());
					  
	}  // Update_The_Value_For_A_Single_Entry

########################################################################################################################

	function Update_The_Integer_Value_For_A_Single_Entry( $The_Table_Name,
												  $The_Key_Element_Name,  $The_Key_Value,
												  $The_Element_To_Update, $The_New_Value )
	{
		$sql = "UPDATE `$The_Table_Name` " .
			   "SET `$The_Element_To_Update` = $The_New_Value " .
			   "WHERE `$The_Key_Element_Name` = '$The_Key_Value' " .
			   "LIMIT 1 ";
			   
		$result = mysql_query( $sql, $this->Database_Connection )
					  or die( "$sql<br/><br/>" . mysql_error());
					  
	}  // Update_The_Value_For_A_Single_Entry
	
};  // A_Database_Interface


########################################################################################################################
########################################################################################################################


function Insert_A_New_Database_Row( $The_Table_Name,  // the table to which the row will be added
                                    $The_Set_Of_Field_Name_And_Value_Pairs,  // the set of field name/value pairs
									$The_Set_Of_Field_Name_And_Database_Function_Pairs, // DB functions like MySQL NOW()
									$The_Database_To_Query                              ) // the connection to the database
{
    $The_Field_Name_String = "(";
	$The_Field_Value_String = "(";
	
	foreach( $The_Set_Of_Field_Name_And_Value_Pairs as $The_Field_Name => $The_Field_Value )
	{
	   $The_Field_Name_String = $The_Field_Name_String . " `" . $The_Field_Name . "` ,";
	   $The_Field_Value_String = $The_Field_Value_String . " '" . $The_Field_Value . "' ,";
	
	};  // end foreach
	
	foreach( $The_Set_Of_Field_Name_And_Database_Function_Pairs as $The_Field_Name => $The_Function )
	{
	   $The_Field_Name_String = $The_Field_Name_String . " `" . $The_Field_Name . "` ,";
	   $The_Field_Value_String = $The_Field_Value_String . " " . $The_Function . " ,";
	
	};  // end foreach
	
	$The_Field_Name_String  = substr_replace( $The_Field_Name_String,  ")", -1, 1 );
	$The_Field_Value_String = substr_replace( $The_Field_Value_String, ")", -1, 1 );
	
	$sql = "INSERT INTO `$The_Table_Name` " . $The_Field_Name_String . " VALUES " . $The_Field_Value_String . ";";
	
//	echo "||" . $sql . "|| <br/>"; //TBD TEMP
		   
    $result = mysql_query( $sql, $The_Database_To_Query->Database_Connection )
	              or die( "SQL: $sql<br/><br/>MySQL_ERROR: " . mysql_error());
				 
	$The_ID_Of_The_Inserted_Row = mysql_insert_id( $The_Database_To_Query->Database_Connection );

   return $The_ID_Of_The_Inserted_Row;	
	
};  // end Insert_A_New_Database_Row

########################################################################################################################

function The_Enumeration_Field_Values_For( $The_Field_Name, $The_Table_Name, $The_Database_To_Query )
{
   $sql = 'SHOW COLUMNS FROM `' . $The_Table_Name . '` LIKE "' . $The_Field_Name . '";';

   $result = mysql_query( $sql, $The_Database_To_Query->Database_Connection )
	             or die( "$sql<br/><br/>" . mysql_error());

   $result_row = mysql_fetch_array( $result, MYSQL_ASSOC);

   $The_Enumeration_Field_String = $result_row['Type']; //cs DEBUG

   preg_match_all( "/'(.*?)'/", $The_Enumeration_Field_String, $The_Enumeration_Field_Values );

   return $The_Enumeration_Field_Values[ 1 ];  // ...[0] is the matches for the entire pattern {'(.*?)'}, 
                                               // ...[1] is the matches for the subpattern {(.*?)}, the items between the single quotes
   
}; // end The_Enumeration_Field_Values_For

########################################################################################################################

function Update_A_Database_Row( $The_Table_Name,
                                $The_Key_Element_Name,  $The_Key_Element_Value,
	                            $The_Set_Of_Database_Field_Value_Pairs,
//	                            $The_Set_Of_Database_Field_Function_Pairs,
							    $The_Database_To_Update                    )
{
   $The_Set_Command = "SET ";
   
   foreach( $The_Set_Of_Database_Field_Value_Pairs as $The_Field_Name => $The_Field_Value ) :

      $The_Set_Command .= "`" . $The_Field_Name . "`='" . str_replace( "'", "\'", $The_Field_Value ) . "', ";
	  
   endforeach;
   
   $The_Set_Command = substr_replace( $The_Set_Command, " ", strrpos( $The_Set_Command, "," ) );
   
//   echo "The_Set_Command = |" . $The_Set_Command . "|<br/>";
   
   $sql = "UPDATE `$The_Table_Name` " .
	      $The_Set_Command .
	      "WHERE `$The_Key_Element_Name` = '$The_Key_Element_Value' ";
		   
	return;	   
	
   $result = mysql_query( $sql, $The_Database_To_Update->Database_Connection )
                or die( "$sql<br/><br/>" . mysql_error());
				  
};  // end Update_A_Database_Row

########################################################################################################################


?>
