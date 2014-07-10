<?

require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/database_utilities.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/site_wide_utilities.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_configuration/the_system_settings.config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_view.inc.php' );

class A_Mimik_Database_Interface extends A_Database_Interface
{

	function A_Mimik_Database_Interface() {}
	
	function Add_The_Group_Permissions_To_The_Form($The_Input_Form_ID, $The_Input_Group_Permissions_Array)
	{
		if (is_array($The_Input_Group_Permissions_Array)) foreach ($The_Input_Group_Permissions_Array as $The_Group_ID_Index => $The_Group_ID) :
			
			if (!is_numeric($The_Group_ID)) unset($The_Input_Group_Permissions_Array[$The_Group_ID_Index]);
			
		endforeach;
		
		$The_SQL = 'DELETE FROM mmksys_Form_Group_Permissions WHERE form_id = ' . $The_Input_Form_ID;
		
		if (count($The_Input_Group_Permissions_Array) > 0) :
		
			$The_SQL .= ' AND group_ID NOT IN (';
			
			if (is_array($The_Input_Group_Permissions_Array)) foreach ($The_Input_Group_Permissions_Array as $The_Group_ID_Index => $The_Group_ID) :
			
				$The_SQL .= $The_Group_ID;
					
				if ($The_Group_ID_Index < (count($The_Input_Group_Permissions_Array) - 1)) $The_SQL .= ',';
				
			endforeach;
			
			$The_SQL .= ');';
			
		endif;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error()); // delete the form/group permission if it already exists
		
		if (is_array($The_Input_Group_Permissions_Array)) foreach ($The_Input_Group_Permissions_Array as $The_Group_ID) :
			
			$The_Existing_Row = $this->First_Row_From_The_Database_Corresponding_To_Multiple_Conditions(
													'mmksys_Form_Group_Permissions',
													array(	'form_id' => $The_Input_Form_ID,
															'group_id' => $The_Group_ID ));
			
			if (!is_array($The_Existing_Row)) :
			
				$The_SQL = 'INSERT INTO mmksys_Form_Group_Permissions (form_id, group_id) VALUES (' . $The_Input_Form_ID . ', ' . $The_Group_ID . ') ';
				
				$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
										or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
			endif;
		
		endforeach;
		
	}
	
	function Add_The_Group_Permissions_To_The_View($The_Input_View_ID, $The_Input_Group_Permissions_Array)
	{
		if (is_array($The_Input_Group_Permissions_Array)) foreach ($The_Input_Group_Permissions_Array as $The_Group_ID_Index => $The_Group_ID) :
			
			if (!is_numeric($The_Group_ID)) unset($The_Input_Group_Permissions_Array[$The_Group_ID_Index]);
			
		endforeach;
		
		$The_SQL = 'DELETE FROM mmksys_View_Group_Permissions WHERE view_id = ' . $The_Input_View_ID;
		
		if (count($The_Input_Group_Permissions_Array) > 0) :
		
			$The_SQL .= ' AND group_ID NOT IN (';
			
			foreach ($The_Input_Group_Permissions_Array as $The_Group_ID_Index => $The_Group_ID) :
			
				$The_SQL .= $The_Group_ID;
					
				if ($The_Group_ID_Index < (count($The_Input_Group_Permissions_Array) - 1)) $The_SQL .= ',';
				
			endforeach;
			
			$The_SQL .= ');';
			
		endif;

		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error()); // delete the form/group permission if it already exists
		
		if (count($The_Input_Group_Permissions_Array) > 0) :
		
			foreach ($The_Input_Group_Permissions_Array as $The_Group_ID) :
			
				$The_Existing_Row = $this->First_Row_From_The_Database_Corresponding_To_Multiple_Conditions(
														'mmksys_View_Group_Permissions',
														array(	'view_id' => $The_Input_View_ID,
																'group_id' => $The_Group_ID ));
				
				if (!is_array($The_Existing_Row)) :
				
					$The_SQL = 'INSERT INTO mmksys_View_Group_Permissions (view_id, group_id) VALUES (' . $The_Input_View_ID . ', ' . $The_Group_ID . ') ';
					
					$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
											or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
											
				endif;
			
			endforeach;
		
		endif;
		
	}
	
	function All_Displayed_Fields_For_The_Table($The_Input_Table_ID)
	{
		$The_SQL = 'SELECT id, type, display_name, name, display_order_number FROM Fields WHERE table_id = ' . $The_Input_Table_ID . ' AND display_in_management_view = \'1\' ORDER BY display_order_number ASC';
		
		$The_Displayed_Fields = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query( $The_SQL );
		
		return $The_Displayed_Fields;
	}
	
	function All_Fields_For_The_Table($The_Input_Table_ID, $The_Input_Field_Type = NULL)
	{
		if ($The_Input_Table_ID == '') :

			return NULL;

		endif;

		$The_User_Defined_Fields = $this->All_User_Defined_Fields_For_The_Table($The_Input_Table_ID);
		
		$The_Fields = array_merge($THE_GENERIC_FIELDS, $The_User_Defined_Fields);
		
		if (!is_null($The_Input_Field_Type)) :
		
			foreach ($The_Fields as $The_Field) :
			
				if ($The_Field['type'] == $The_Input_Field_Type) :
				
					$The_Filtered_Fields[] = $The_Field;
				
				endif;
			
			endforeach;
			
			$The_Fields = $The_Filtered_Fields;
		
		endif;
		
		return $The_Fields;
		
	}
	
	function All_Group_Permissions_For_The_Form($The_Input_Form_ID)
	{
		$The_SQL =  'SELECT DISTINCT g.id, g.name FROM mmksys_Form_Group_Permissions p INNER JOIN Groups g ON p.group_id = g.id ';
		
		$The_SQL .= 'WHERE p.form_id = ' . $The_Input_Form_ID . ' ORDER BY g.name';
		
		return $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	}
	
	function All_Group_Permissions_For_The_Submission($The_Input_Form_ID, $The_Input_Submission_ID)
	{
		$The_SQL =  'SELECT group_id FROM mmksys_Submission_Group_Permissions ';
		
		$The_SQL .= 'WHERE form_id = ' . $The_Input_Form_ID . ' AND submission_id = ' . $The_Input_Submission_ID;
		
		return $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	}
	
	function All_Group_Custom_Fields()
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'Fields',  // the table from which the row will be selected
										'is_group_field',
										'1',
										'display_in_management_view',
										'DESC',
										'display_order_number',
										'ASC' );
	}
	
	function All_User_Custom_Fields()
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'Fields',  // the table from which the row will be selected
										'is_user_field',
										'1',
										'display_in_management_view',
										'DESC',
										'display_order_number',
										'ASC' );
	}
	
	function All_User_Permissions_For_The_Form($The_Input_Form_ID)
	{
		$The_SQL =  'SELECT DISTINCT u.id, u.login FROM mmksys_Submission_User_Permissions p INNER JOIN Users u ON p.user_id = u.id ';
		
		$The_SQL .= 'WHERE p.form_id = ' . $The_Input_Form_ID . ' ORDER BY u.login';
		
		return $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	}
	
	function All_User_Permissions_For_The_Submission($The_Input_Form_ID, $The_Input_Submission_ID)
	{
		$The_SQL =  'SELECT user_id FROM mmksys_Submission_User_Permissions ';
		
		$The_SQL .= 'WHERE form_id = ' . $The_Input_Form_ID . ' AND submission_id = ' . $The_Input_Submission_ID;
		
		return $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	}
	
	function All_Group_Permissions_For_The_View($The_Input_View_ID)
	{
		$The_SQL = 'SELECT g.id FROM Groups g INNER JOIN mmksys_View_Group_Permissions p ON p.group_id = g.id WHERE p.view_id = ' . $The_Input_View_ID;
		
		$The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
		
		if (is_array($The_Result_Array)) foreach ($The_Result_Array as $The_Group_Information) :
		
			$The_Return_Array[] = $The_Group_Information['id'];
			
		endforeach;
		
		return $The_Return_Array;
	}
	
	function All_User_Defined_Fields_For_The_Table_Related_To_The_Target_Field($The_Input_Target_Field_ID)
	{
		$The_Related_Table_ID = $this->Gets_The_Related_Table_For_The_Relational_Field($The_Input_Target_Field_ID);
		
		return $this->All_User_Defined_Fields_For_The_Table($The_Related_Table_ID);
	}
	
	function All_Forms()
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'Tables',
										'',
										'',
										'display_name',
										'ASC' );

	}
	
	function All_Forms_Allowed_For_The_User($The_Input_User_ID)
	{
		$The_SQL  = 'SELECT DISTINCT t1.* FROM Tables t1 INNER JOIN mmksys_Form_Group_Permissions p ON p.form_id = t1.id ';
		
		$The_SQL .= 'INNER JOIN Group_User_Associations a ON p.group_id = a.group_id ';
		
		$The_SQL .= 'WHERE a.user_id=' . $The_Input_User_ID . ' AND t1.limit_access = 1 ';
		
		$The_SQL .= 'UNION SELECT t2.* FROM Tables t2 WHERE t2.limit_access = 0 ';
		
		$The_SQL .= 'ORDER BY `display_name`';
		
		return $The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	}
	
	function All_Forms_With_Fields_Of_Type($The_Input_Field_Type)
	{
		$The_SQL = "SELECT t.* from `Tables` t WHERE EXISTS (SELECT f.id FROM `Fields` f WHERE f.table_id = t.id AND f.`type` = '" . $The_Input_Field_Type . "')";
		
		return $The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	}
	
	function All_Forms_With_Date_Fields()
	{
		return $this->All_Forms_With_Fields_Of_Type('Date'); // bobby made this change
	}
	
	function All_Forms_With_Image_Fields()
	{
		return $this->All_Forms_With_Fields_Of_Type('Image');
	}
	
	function All_Forms_With_Video_Fields()
	{
		return $this->All_Forms_With_Fields_Of_Type('Video');
	}
	
	function All_Groups()
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'Groups',  // the table from which the row will be selected
										'',
										'',
										'name',
										'ASC' );

	}
	
	function All_Groups_Belonged_To_By_The_User($The_Input_User_ID)
	{
		$The_Group_IDs = array();
		
		if ($The_Input_User_ID) :
		
			$The_Group_Information = $this->All_Values_From_The_Database_Corresponding_To(
											'Group_User_Associations',
											'group_id',
											'user_id',
											$The_Input_User_ID );
											
			if (is_array($The_Group_Information)) :					
	
				foreach ($The_Group_Information as $The_Group) :
			
					$The_Group_IDs[] = $The_Group['group_id'];

					$The_Parent_Tree = $this->Gets_The_Parent_Groups_Of_The_Group($The_Group['group_id']);

					if ($The_Parent_Tree != NULL) :
					
						$The_Group_IDs = array_merge($The_Group_IDs, $The_Parent_Tree);
						
					endif;
					
				endforeach;
			
			endif;
			
			$The_Group_IDs = array_unique($The_Group_IDs);
			
			return $The_Group_IDs;
			
		endif;
	}
	
	function Gets_The_Email_Notification_Information_For_The_Form($The_Input_Form_ID)
	{
		$The_SQL = 'SELECT email_notification_flag, email_recipients FROM `Tables` WHERE id = ' . $The_Input_Form_ID;
		
		$The_Form_Rows = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
		
		return $The_Form_Rows[0];
	}
	
	function Gets_The_Parent_Groups_Of_The_Group($The_Input_Group_ID)
	{
		$The_Parent_Group_ID = $this->First_Value_From_The_Database_Corresponding_To(
											'Groups',
											'parent_group_id',
											'',
											'',
											'id',
											$The_Input_Group_ID );
		
		if (is_numeric($The_Parent_Group_ID)) :
		
			$The_Return_Array[] = $The_Parent_Group_ID;
			
			$The_Return_Array = array_merge($The_Return_Array, $this->Gets_The_Parent_Groups_Of_The_Group($The_Parent_Group_ID));
			
			return $The_Return_Array;
		
		else :

			return NULL;
			
		endif;
		
	} // Gets_The_Parent_Group_Of_The_Group

	function All_Date_Data_For_The_Field($The_Input_Field_ID)
	{
		$The_SQL = 'SELECT start_year, end_year FROM mmk_Dates WHERE target_field = ' . $The_Input_Field_ID;
		
		$The_Date_Rows = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
		
		return $The_Date_Rows[0];
	}
	
	function All_Admin_Permissions()
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'mmksys_Admin_Permissions',
										'',
										'',
										'order_number',
										'ASC' );

	}
	
	function All_Admin_Permissions_For_The_Group($The_Input_Group_ID)
	{
		if (is_numeric($The_Input_Group_ID)) return $this->All_Values_From_The_Database_Corresponding_To(
										'mmksys_Group_Admin_Permission_Associations',
										'admin_permission_id',
										'group_id',
										$The_Input_Group_ID );
	}
	
	function All_Admin_Permissions_For_The_User($The_Input_User_Name)
	{
		if ($The_Input_User_Name) :
		
			$The_User_ID = $this->Gets_The_User_ID_For_The_User_Name($The_Input_User_Name);
			
			$The_Groups = $this->All_Groups_Belonged_To_By_The_User($The_User_ID);

			$All_Admin_Permissions = array();
			
			if (is_array($The_Groups)) foreach ($The_Groups as $The_Group_ID) :
			
				$The_Admin_Permissions_For_The_Group = $this->All_Admin_Permissions_For_The_Group($The_Group_ID);
				
				if (is_array($The_Admin_Permissions_For_The_Group)) foreach ($The_Admin_Permissions_For_The_Group as $The_Admin_Permission) :
				
					if (!in_array($The_Admin_Permission['admin_permission_id'], $All_Admin_Permissions)) array_push($All_Admin_Permissions, $The_Admin_Permission['admin_permission_id']);
				
				endforeach;
			
			endforeach;
			
			return $All_Admin_Permissions;
		
		else :
		
			return NULL;
		
		endif;
	}
	
	function All_Group_Information($The_Input_Group_ID)
	{
		return $this->First_Row_From_The_Database_Corresponding_To(
										'Groups',  // the table from which the row will be selected
										'',
										'',
										'id',
										$The_Input_Group_ID );
	}
	
	function All_Relational_Data_For_The_Field($The_Input_Field_ID)
	{
		$The_SQL =  'SELECT T.table_name, ';
		$The_SQL .= '(SELECT F1.name FROM `Fields` F1 WHERE F1.id=R.relational_field_1) AS relational_field_1, ';
		$The_SQL .= '(SELECT F1.type FROM `Fields` F1 WHERE F1.id=R.relational_field_1) AS relational_type_1, ';
		$The_SQL .= 'R.relational_field_1 AS relational_id_1, ';
		$The_SQL .= '(SELECT F2.name FROM `Fields` F2 WHERE F2.id=R.relational_field_2) AS relational_field_2, ';
		$The_SQL .= '(SELECT F2.type FROM `Fields` F2 WHERE F2.id=R.relational_field_2) AS relational_type_2, ';		
		$The_SQL .= 'R.relational_field_2 AS relational_id_2, ';
		$The_SQL .= '(SELECT F3.name FROM `Fields` F3 WHERE F3.id=R.relational_field_3) AS relational_field_3, ';
		$The_SQL .= '(SELECT F3.type FROM `Fields` F3 WHERE F3.id=R.relational_field_3) AS relational_type_3, ';
		$The_SQL .= 'R.relational_field_3 AS relational_id_3 ';
		$The_SQL .= 'FROM `Tables` T INNER JOIN `Fields` F ON T.id = F.table_id ';
		$The_SQL .= 'INNER JOIN `Relationships` R ON F.id = R.relational_field_1 WHERE R.target_field = ' . $The_Input_Field_ID;

		$The_Table_Row = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);

		$The_Table_Name = $The_Table_Row[0]['table_name'];
		
		$The_Relational_Field_Name_1 = $The_Table_Row[0]['relational_field_1'];
		
		$The_Relational_Type_1 = $The_Table_Row[0]['relational_type_1'];
		
		$The_Relational_ID_1 = $The_Table_Row[0]['relational_id_1'];
		
		$The_Relational_Field_Name_2 = $The_Table_Row[0]['relational_field_2'];
		
		$The_Relational_Type_2 = $The_Table_Row[0]['relational_type_2'];
		
		$The_Relational_ID_2 = $The_Table_Row[0]['relational_id_2'];
		
		$The_Relational_Field_Name_3 = $The_Table_Row[0]['relational_field_3'];
		
		$The_Relational_Type_3 = $The_Table_Row[0]['relational_type_3'];
		
		$The_Relational_ID_3 = $The_Table_Row[0]['relational_id_3'];
		
		if ($The_Relational_Field_Name_1) :
		
			$The_SQL = 'SELECT id, ';
			
			$The_SQL .= '`' . $The_Relational_Field_Name_1 . '` ';
				
			$The_SQL .= 'AS relational_value_1';
	
			if ($The_Relational_Field_Name_2) :
			
				$The_SQL .= ', `' . $The_Relational_Field_Name_2 . '` ';
				
				$The_SQL .= 'AS relational_value_2';
				
			endif;
		
			if ($The_Relational_Field_Name_3) :
			
				$The_SQL .= ', `' . $The_Relational_Field_Name_3 . '` ';
				
				$The_SQL .= 'AS relational_value_3';
				
			endif;
				
			$The_SQL .= ' FROM `' . $The_Table_Name . '` ORDER BY `' . $The_Relational_Field_Name_1 . '`';
			
			$The_Return_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			if (!empty($The_Return_Array)) :
			
				if (is_array($The_Return_Array)) foreach ($The_Return_Array as $The_Key => $The_Value) :
				
					$The_Return_Array[$The_Key]['relational_value_1'] = $this->Specific_Relational_Data_For_The_Dynamic_Dynamic_Select($The_Relational_ID_1, $The_Value['relational_value_1']);
					if ($The_Return_Array[$The_Key]['relational_value_2']) :
						$The_Return_Array[$The_Key]['relational_value_2'] = $this->Specific_Relational_Data_For_The_Dynamic_Dynamic_Select($The_Relational_ID_2, $The_Value['relational_value_2']);
					endif;
					if ($The_Return_Array[$The_Key]['relational_value_3']) :
						$The_Return_Array[$The_Key]['relational_value_3'] = $this->Specific_Relational_Data_For_The_Dynamic_Dynamic_Select($The_Relational_ID_3, $The_Value['relational_value_3']);
					endif;
							
				endforeach;
			
			endif;
			
		endif;
		
		return $The_Return_Array;
		
	}
	
/****************************************************************************************************************************/
	
	function Specific_Relational_Data_For_The_Dynamic_Dynamic_Select($The_Input_Relationship_ID, $The_Input_Actual_Value)
	{
		$The_SQL = 'SELECT 1 FROM Relationships WHERE target_field = ' . $The_Input_Relationship_ID;
		
		$The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
		
		if (is_array($The_Result_Array) && $The_Input_Actual_Value != '' && $The_Input_Actual_Value != NULL) :
		
			$The_SQL  = 'SELECT R.relational_field_1 AS `relationship_id`, T.table_name, F.name AS `field_name` ';
			$The_SQL .= 'FROM Relationships R INNER JOIN Fields F ';
			$The_SQL .= 'ON R.relational_field_1 = F.id ';
			$The_SQL .= 'INNER JOIN Tables T ';
			$The_SQL .= 'ON T.id = F.table_id ';
			$The_SQL .= 'WHERE R.target_field = ' . $The_Input_Relationship_ID . ' ';
			$The_SQL .= 'LIMIT 1';
			
			$The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			$The_New_Relationship_ID = $The_Result_Array[0]['relationship_id'];
			
			$The_Table_Name = $The_Result_Array[0]['table_name'];
			
			$The_Field_Name = $The_Result_Array[0]['field_name'];
			
			$The_SQL = 'SELECT `' . $The_Field_Name . '` FROM `' . $The_Table_Name . '` WHERE id = ' . $The_Input_Actual_Value;
			
			$The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			$The_New_Actual_Value = $The_Result_Array[0][$The_Field_Name];
			
			return $this->Specific_Relational_Data_For_The_Dynamic_Dynamic_Select($The_New_Relationship_ID, $The_New_Actual_Value);
			
		else :
		
			return $The_Input_Actual_Value;
		/*
			$The_SQL  = 'SELECT T.table_name, F.name AS `field_name` ';
			$The_SQL .= 'FROM Relationships R INNER JOIN Fields F ';
			$The_SQL .= 'ON R.relational_field_1 = F.id ';
			$The_SQL .= 'INNER JOIN Tables T ';
			$The_SQL .= 'ON T.id = F.table_id ';
			$The_SQL .= 'WHERE R.target_field = ' . $The_Input_Relationship_ID . ' ';
			$The_SQL .= 'LIMIT 1';
			
			$The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			$The_Table_Name = $The_Result_Array[0]['table_name'];
			
			$The_Field_Name = $The_Result_Array[0]['field_name'];
			
			$The_SQL = 'SELECT `' . $The_Field_Name . '` FROM `' . $The_Table_Name . '` WHERE id = ' . $The_Input_Actual_Value;
			
			$The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			$The_New_Actual_Value = $The_Result_Array[0][$The_Field_Name];
			
			return $The_New_Actual_Value;
		*/	
		endif;
	}
	
/****************************************************************************************************************************/
	
	function All_Relational_Data_For_The_Table_Visible_In_Management_View($The_Input_Table_ID)
	{
		$The_Relationship_SQL = 'SELECT R.* FROM Relationships R INNER JOIN `Fields` F ON R.target_field = F.id WHERE F.display_in_management_view = \'1\' AND F.table_id = ' . $The_Input_Table_ID;
		
		$The_Relationship_Rows = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_Relationship_SQL);
		
		if (is_array($The_Relationship_Rows)) foreach ($The_Relationship_Rows as $The_Relationship_Row) :
		
			$The_Relationship_IDs = array();
		
			foreach ($The_Relationship_Row as $The_Relationship_Row_Key => $The_Relationship_Row_Value) :
		
				if (!in_array($The_Relationship_Row_Key, array('id', 'target_field')) && $The_Relationship_Row_Value != '') $The_Relationship_IDs[] = $The_Relationship_Row_Value;
		
			endforeach;
			
			$The_Indexed_Relationships[$The_Relationship_Row['target_field']] = $The_Relationship_IDs;
		
		endforeach;

		return $The_Indexed_Relationships;

	}
	
	function All_Rows_For_The_Table($The_Input_Table_ID, $The_Input_Sort_Field = '', $The_Input_Sort_Order = '')
	{
		$The_Sort_Order = ($The_Input_Sort_Order == 'ASCENDING') ? 'ASC' : 'DESC';
		
		$The_Sort_Field = ($The_Input_Sort_Field == '') ? 'modify_date' : $The_Input_Sort_Field;
		
		$The_Table_Name = $this->Gets_The_Name_Of_The_Table( $The_Input_Table_ID );
										
		$The_Rows = $this->All_Rows_From_The_Database_Corresponding_To(
										$The_Table_Name,
										'',
										'',
										$The_Sort_Field,
										$The_Sort_Order);
		
		return $The_Rows;
	
	}
	
/***********************************************************************************************************/
	// returns array of rows with the generic fields, normal fields, and full values of the relational fields
	// (recursive)
	function All_Rows_For_The_Table_With_Relational_Data_Recursive($The_Input_Table_ID, $The_Input_Sort_Field = '', $The_Input_Sort_Order = '')
	{
		require_once($_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/an_aggregated_value_definition.inc.php');
		$The_Rows = $this->All_Rows_For_The_Table($The_Input_Table_ID,
										$The_Input_Sort_Field,
										$The_Input_Sort_Order);
										
		$The_Dynamic_Select_Fields = $this->All_Relational_Data_For_The_Table_Visible_In_Management_View($The_Input_Table_ID);
		
		$The_Group_Permission_Fields = $this->Gets_The_Names_Of_All_Group_Permission_Fields_For_The_Form($The_Input_Table_ID);
		
		$The_User_Permission_Fields = $this->Gets_The_Names_Of_All_User_Permission_Fields_For_The_Form($The_Input_Table_ID);
		
		if (is_array($The_Rows)) foreach ($The_Rows as $The_Row_Key => $The_Row) :
		
			if (is_array($The_Group_Permission_Fields)) foreach ($The_Group_Permission_Fields as $The_Group_Permission_Field) :
			
				$The_Group_IDs = $this->All_Group_Permissions_For_The_Submission($The_Input_Table_ID, $The_Row['id']);
				
				if (is_array($The_Group_IDs)) foreach ($The_Group_IDs as $The_Group_ID) :
				
					$The_Group_Information = $this->All_Group_Information($The_Group_ID['group_id']);
				
					$The_Rows[$The_Row_Key][$The_Group_Permission_Field['name']][] = $The_Group_Information['name'];
					
				endforeach;
			
			endforeach;
			
			if (is_array($The_Rows[$The_Row_Key][$The_Group_Permission_Field['name']])) $The_Rows[$The_Row_Key][$The_Group_Permission_Field['name']] = implode(', ', $The_Rows[$The_Row_Key][$The_Group_Permission_Field['name']]);

			if (is_array($The_User_Permission_Fields)) foreach ($The_User_Permission_Fields as $The_User_Permission_Field) :
			
				$The_User_IDs = $this->All_User_Permissions_For_The_Submission($The_Input_Table_ID, $The_Row['id']);
				
				if (is_array($The_User_IDs)) foreach ($The_User_IDs as $The_User_ID) :
				
					$The_User_Information = $this->All_User_Information($The_User_ID['user_id']);
					
					$The_Rows[$The_Row_Key][$The_User_Permission_Field['name']][] = $The_User_Information['login'];
					
				endforeach;
			
			endforeach;
			
			if (is_array($The_Rows[$The_Row_Key][$The_User_Permission_Field['name']])) $The_Rows[$The_Row_Key][$The_User_Permission_Field['name']] = implode(', ', $The_Rows[$The_Row_Key][$The_User_Permission_Field['name']]);
		
			if (is_array($The_Dynamic_Select_Fields)) :
			
				foreach ($The_Dynamic_Select_Fields as $The_Dynamic_Select_Field_ID => $The_Dynamic_Select_Field_Relationship_Array) :
			
					$The_Dynamic_Select_Field_Name = $this->First_Value_From_The_Database_Corresponding_To(
											'Fields', 'name', '', 'ASC', 'id', $The_Dynamic_Select_Field_ID );
											
					if ($The_Rows[$The_Row_Key][$The_Dynamic_Select_Field_Name]) :
					
						$The_Column_IDs = array($The_Dynamic_Select_Field_ID);
						
						$The_Aggregated_Value_Definition = new An_Aggregated_Value_Definition($The_Input_Table_ID, $The_Column_IDs, $The_Row['id'], $this);
						
						$The_Rows[$The_Row_Key][$The_Dynamic_Select_Field_Name] = $The_Aggregated_Value_Definition->Gets_The_Aggregated_Value(' ');
						
					else :
					
						$The_Rows[$The_Row_Key][$The_Dynamic_Select_Field_Name] = NULL;
				
					endif;
				
				endforeach;
				
			endif;
		
		endforeach;
		
		return $The_Rows;
	
	}
/***********************************************************************************************************/
	
	function All_Settings()
	{
		$The_Utilities_Rows = $this->All_Rows_From_The_Database_Corresponding_To('Utilities');
		
		if (is_array($The_Utilities_Rows)) foreach ($The_Utilities_Rows as $The_Utility) :
		
			$The_Settings[$The_Utility['utility_name']] = $The_Utility['utility_value'];
		
		endforeach;
		
		return $The_Settings;
	}
	
	function All_User_Defined_Fields_For_The_Table($The_Input_Table_ID)
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'Fields',  // the table from which the row will be selected
										'table_id',
										$The_Input_Table_ID,
										'display_in_management_view',
										'DESC',
										'display_order_number',
										'ASC' );
	}
	
	function All_Public_Forms()
	{
		$The_Forms = $this->All_Values_From_The_Database_Corresponding_To(
										'Tables',
										'id',
										'audience',
										'Public');
										
		$The_Return_Array = array();
		
		if (is_array($The_Forms)) foreach ($The_Forms as $The_Form) :
		
			$The_Return_Array[] = $The_Form['id'];
		
		endforeach;
		
		return $The_Return_Array;
	}
	
	function Gets_The_Audience_For_The_Form($The_Input_Form_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Tables',  // the table from which the row will be selected
										'audience',
										'',
										'',
										'id',
										$The_Input_Form_ID );
	
	}
	
	function Gets_The_Blocked_Status_For_The_User($The_Input_User_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'is_blocked',
										'',
										'',
										'id',
										$The_Input_User_ID );
	
	}
	
	function Gets_The_Form_ID_For_The_View($The_Input_View_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
											'Views', 'form_id', '', 'ASC', 'id', $The_Input_View_ID );
	}
	
	function Gets_The_Limit_Access_For_The_Form($The_Input_Form_ID)
	{
		$The_SQL = 'SELECT `limit_access` FROM Tables WHERE `id` = ' . $The_Input_Form_ID;
		
		$The_Rows = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
				
		$The_Return_Value = $The_Rows[0]['limit_access'];
		
		return $The_Return_Value;
	}
	
	function Gets_The_Limit_Access_For_The_View($The_Input_View_ID, $The_Input_Limit_Access)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
											'Views', 'limit_access', '', 'ASC', 'id', $The_Input_View_ID );
	}
	
	function Gets_The_Moderation_Status_For_The_User($The_Input_User_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'moderation_status',
										'',
										'',
										'id',
										$The_Input_User_ID );
	
	}
	
	function Gets_The_Search_Results_Text_Setting()
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Utilities',
										'utility_value',
										'',
										'',
										'utility_name',
										'search_results_text' );
	}
	
	function Gets_Whether_The_Form_Has_A_Group_Permission_Field($The_Input_Form_ID)
	{
		$The_SQL = 'SELECT 1 FROM Fields WHERE type=\'Group Permission\' AND table_id=' . $The_Input_Form_ID;
		
		$The_Result = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query( $The_SQL );
		
		if (is_array($The_Result)) if (count($The_Result) > 0) return true;
		
		return false;
	}
	
	function Gets_Whether_The_Form_Has_A_User_Permission_Field($The_Input_Form_ID)
	{
		$The_SQL = 'SELECT 1 FROM Fields WHERE type=\'User Permission\' AND table_id=' . $The_Input_Form_ID;
		
		$The_Result = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query( $The_SQL );
		
		if (is_array($The_Result)) if (count($The_Result) > 0) return true;
		
		return false;
	}
	
	function Gets_The_User_By_Confirmation_Code($The_Input_Confirmation_Code)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'id',
										'',
										'',
										'confirmation_code',
										$The_Input_Confirmation_Code );
	
	}
	
	function Gets_The_User_Email_For_The_User_ID($The_Input_User_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'email',
										'',
										'',
										'id',
										$The_Input_User_ID );
	}
	
	function Gets_The_User_ID_For_The_Email($The_Input_User_Email)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'id',
										'',
										'',
										'email',
										$The_Input_User_Email );
	}
	
	function Gets_The_User_ID_For_The_User_Name($The_Input_User_Name)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'id',
										'',
										'',
										'login',
										$The_Input_User_Name );
	}
	
	function Gets_The_User_Name_For_The_User_ID($The_Input_User_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'login',
										'',
										'',
										'id',
										$The_Input_User_ID );
	}
	
	function All_User_Information($The_Input_User_ID)
	{
		return $this->First_Row_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'',
										'',
										'id',
										$The_Input_User_ID );
	}
	
	function All_Users()
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'Users',  // the table from which the row will be selected
										'',
										'',
										'login',
										'ASC' );

	}
	
	function All_Users_In_The_Group($The_Input_Group_ID)
	{
		return $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query(
			'SELECT u.* FROM Users u INNER JOIN Group_User_Associations a ON u.id = a.user_id WHERE a.group_id = ' . $The_Input_Group_ID);
			
	}
	
	function All_Views()
	{
		return $this->All_Rows_From_The_Database_Corresponding_To(
										'Views',  // the table from which the row will be selected
										'',
										'',
										'display_name',
										'ASC' );

	}
	
	function All_Views_Allowed_For_The_User($The_Input_User_ID)
	{
		$The_SQL  = 'SELECT DISTINCT v1.* FROM Views v1 INNER JOIN mmksys_View_Group_Permissions p ON p.view_id = v1.id ';
		
		$The_SQL .= 'INNER JOIN Group_User_Associations a ON p.group_id = a.group_id ';
		
		$The_SQL .= 'WHERE a.user_id=' . $The_Input_User_ID . ' AND v1.limit_access = 1 ';
		
		$The_SQL .= 'UNION SELECT v2.* FROM Views v2 WHERE v2.limit_access = 0 ';
		
		$The_SQL .= 'ORDER BY `display_name`';
		
		return $The_Result_Array = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
	}
	
	function Create_The_Date($The_Input_Field_ID, $The_Input_Start_Year, $The_Input_End_Year)
	{
		$The_Field_ID = $The_Input_Field_ID;
		
		$The_SQL = 'INSERT INTO mmk_Dates (target_field, start_year, end_year) VALUES (';
				
		$The_SQL .= $The_Field_ID . ', ' . $The_Input_Start_Year . ', ' . $The_Input_End_Year . ')';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
								or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	
	}
	
	function Create_The_Field_Of_The_Type_For_The_Table(
							$The_Input_Field_Name,
							$The_Input_Field_Type,
							$The_Input_Input_Control_Width,
							$The_Input_Character_Limit,
							$The_Input_Display_Flag,
							$The_Input_Table_ID,
							$The_Input_Relational_Table_ID = '',
							$The_Input_Relational_Field_ID_1 = '',
							$The_Input_Relational_Field_ID_2 = '',
							$The_Input_Relational_Field_ID_3 = '',
							$The_Input_Start_Year = '',
							$The_Input_End_Year = '',
							$The_Input_Required_Flag = '0',
							$The_Input_Public_Flag = '0',
							$The_Input_Explanatory_Text = '',
							$The_Input_Options_Text = '')
	{
		$The_New_Field_Name = The_Mimik_Safe_Field_Name($The_Input_Field_Name);
		
		$The_New_Field_Display_Name = $The_Input_Field_Name;

		$The_New_Field_Type = $The_Input_Field_Type;
		
		if ($The_New_Field_Type == 'Text') $The_New_Input_Control_Width = $The_Input_Input_Control_Width;
		else $The_New_Input_Control_Width = 'NULL';
		
		if ($The_New_Field_Type == 'Text' || $The_New_Field_Type == 'Text Area') $The_New_Character_Limit = $The_Input_Character_Limit;
		else $The_New_Character_Limit = 'NULL';
		
		$The_New_Display_Flag = ($The_Input_Display_Flag == true) ? '1' : '0';
		
		$The_New_Required_Flag = ($The_Input_Required_Flag == true) ? '1' : '0';
		
		$The_New_Public_Flag = ($The_Input_Public_Flag == true) ? '1' : '0';
		
		$The_Table_ID = $The_Input_Table_ID;
		
		if ( $this->First_Row_From_The_Database_Corresponding_To_Multiple_Conditions(
										'Fields',
										array('name'=>$The_New_Field_Name,'table_id'=>$The_Table_ID),
										'display_order_number',
										$The_Name_Of_The_Order_Direction = 'DESC' ) ) :
			return false;
			
		else :
		
			$The_Highest_Display_Order_Number_Row = $this->First_Row_From_The_Database_Corresponding_To(
										'Fields',
										'display_order_number',
										'DESC',
										'table_id',
										$The_Table_ID );

			$The_Highest_Display_Order_Number = $The_Highest_Display_Order_Number_Row['display_order_number'];
			
			$The_New_Display_Order_Number = $The_Highest_Display_Order_Number + 1;
			
			global $THE_FIELD_TYPE_ARRAY;
			
			$The_SQL = 'INSERT INTO Fields (display_name, name, type, display_in_management_view, display_order_number, table_id, is_required, is_public_facing, explanatory_text, options_text, input_control_width, character_limit) ' .
						'VALUES (\'' . $The_New_Field_Display_Name . '\', ' .
						'\'' . $The_New_Field_Name . '\', \'' .
						$The_New_Field_Type . '\', \'' .
						$The_New_Display_Flag . '\', ' .
						$The_New_Display_Order_Number . ', ' .
						$The_Table_ID . ', \'' .
						$The_New_Required_Flag . '\', \''.
						$The_New_Public_Flag . '\', \''.
						$The_Input_Explanatory_Text . '\', \''.
						$The_Input_Options_Text. '\', ' .
						$The_New_Input_Control_Width . ', ' .
						$The_New_Character_Limit . ')';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
			$The_Field_ID = mysql_insert_id($this->Database_Connection);
			
			$The_Table_Name = $this->Gets_The_Name_Of_The_Table($The_Table_ID);
			
			// only alter the user table if the SQL field type is not NULL (it's null for Group Permission and User Permission)
			if ($THE_FIELD_TYPE_ARRAY[$The_New_Field_Type]['sql_field_type']) :
			
				$The_SQL = 'ALTER TABLE `' . $The_Table_Name . '` ADD `' . $The_New_Field_Name . '` ';
				
				// use the SQL data type defined in the_system_settings.config.php
				$The_SQL .= $THE_FIELD_TYPE_ARRAY[$The_New_Field_Type]['sql_field_type'];
				
				$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
											or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
											
			endif;
									
			// for "Dynamic Select"	fields, write the appropriate row to the Relationships table
			if ($The_New_Field_Type == 'Dynamic Select' || $The_New_Field_Type == 'Dynamic Radio') :
			
				$this->Create_The_Relationship($The_Field_ID, $The_Input_Relational_Field_ID_1, $The_Input_Relational_Field_ID_2, $The_Input_Relational_Field_ID_3);
										
			endif;
			
			// for "Date" fields, write the appropriate row to the Dates table
			if ($The_New_Field_Type == 'Date') :
			
				$this->Create_The_Date($The_Field_ID, $The_Input_Start_Year, $The_Input_End_Year);
										
			endif;
			
		endif;
		
		return $The_Field_ID;
	}
	
	function Create_The_Group($The_Input_Group_Name)
	{
		if ( $this->All_Values_From_The_Database_Corresponding_To(
										'Groups',
										'id',
										'name',
										$The_Input_Group_Name )) :
		
			return false;
			
		else :
		
			$The_SQL = 'INSERT INTO Groups (name, create_date, modify_date) VALUES (\'' . $The_Input_Group_Name . '\', \'' . date('Y-m-d H:i:s') . '\', \'' . date('Y-m-d H:i:s') . '\')';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
			$The_Group_ID = mysql_insert_id($this->Database_Connection);
										
		endif;
		
		return $The_Group_ID;
		
	}
	
	function Create_The_Group_Association_For_The_User($The_Input_Group_ID, $The_Input_User_ID)
	{
		$The_SQL = 'INSERT INTO Group_User_Associations (group_id, user_id) VALUES (' . $The_Input_Group_ID . ', ' . $The_Input_User_ID . ');';
		
		return mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	}
	
	function Delete_The_Group_Association_For_The_User($The_Input_Group_ID, $The_Input_User_ID)
	{
		$The_SQL = 'DELETE FROM Group_User_Associations WHERE group_id = ' . $The_Input_Group_ID . ' AND user_id = ' . $The_Input_User_ID;
		
		return mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	}
	
	function Create_The_Admin_Permission_For_The_Group($The_Input_Admin_Permission_ID, $The_Input_Group_ID)
	{
		$The_SQL = 'INSERT INTO mmksys_Group_Admin_Permission_Associations (group_id, admin_permission_id) VALUES (' . $The_Input_Group_ID . ', ' . $The_Input_Admin_Permission_ID . ');';
		
		return mysql_query( $The_SQL, $this->Database_Connection )
					or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	}
	
	function Create_The_Relationship($The_Input_Field_ID, $The_Input_Relational_Field_ID_1, $The_Input_Relational_Field_ID_2 = '', $The_Input_Relational_Field_ID_3 = '')
	{
		$The_Field_ID = $The_Input_Field_ID;
		
		$The_SQL = 'INSERT INTO Relationships (target_field, relational_field_1, relational_field_2, relational_field_3) VALUES (';
				
		$The_SQL .= $The_Field_ID . ', ';
		
		if ($The_Input_Relational_Field_ID_1 != '') :
		
			$The_SQL .= $The_Input_Relational_Field_ID_1;
			
		else :
		
			$The_SQL .= 'NULL';
			
		endif;
		
		$The_SQL .= ', ';
		
		if ($The_Input_Relational_Field_ID_2 != '') :
		
			$The_SQL .= $The_Input_Relational_Field_ID_2;
			
		else :
		
			$The_SQL .= 'NULL';
			
		endif;
		
		$The_SQL .= ', ';
		
		if ($The_Input_Relational_Field_ID_3 != '') :
		
			$The_SQL .= $The_Input_Relational_Field_ID_3;
			
		else :
		
			$The_SQL .= 'NULL';
			
		endif;
		
		$The_SQL .= ')';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
								or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	
	}
	
	function Create_The_Row_In_The_Table($The_Input_Value_Pairs, $The_Input_Table_ID)
	{
		$The_Table_Name = $this->Gets_The_Name_Of_The_Table( $The_Input_Table_ID );
										
		$The_SQL = 'INSERT INTO `' . $The_Table_Name . '` (`create_date`, `modify_date`';
		
		if (is_array($The_Input_Value_Pairs)) :
		
			foreach($The_Input_Value_Pairs as $The_Key => $The_Value) :
			
				$The_SQL .= ', `' . $The_Key . '`';
			
			endforeach;

		endif;
		
		$The_SQL .= ') VALUES ( \'' . date('Y-m-d H:i:s') . '\', \'' . date('Y-m-d H:i:s') . '\'';
		
		if (is_array($The_Input_Value_Pairs)) :
		
			foreach($The_Input_Value_Pairs as $The_Key => $The_Value) :
			
				$The_Value = addslashes($The_Value);
			
				$The_SQL .= ', \'' . $The_Value . '\'';
			
			endforeach;

		endif;
		
		$The_SQL .= ');';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
		$The_Submission_ID = mysql_insert_id($this->Database_Connection);
		
		return $The_Submission_ID;
		
	}
	
	function Create_The_Table($The_Input_Table_Name, $The_Input_Table_Type = 'Normal')
	{
		$The_Input_Table_Name = ucwords($The_Input_Table_Name);
	
		$The_New_Table_Name = 'mimik_' . str_replace(' ', '_', $The_Input_Table_Name);
		
		if ( $this->All_Values_From_The_Database_Corresponding_To(
										'Tables',
										'id',
										'table_name',
										$The_New_Table_Name )) :
		
			return false;
			
		else :
		
			$The_SQL = 'INSERT INTO Tables (display_name, table_name) VALUES (\'' . $The_Input_Table_Name . '\', \'' . $The_New_Table_Name . '\')';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
			$The_Table_ID = mysql_insert_id($this->Database_Connection);
										
			if ($The_Input_Table_Type == 'Image_Map') $Additional_Columns_SQL = ', `x` INTEGER, `y` INTEGER';
			else $Additional_Columns_SQL = '';
			
			$The_SQL = 'CREATE TABLE `' . $The_New_Table_Name . '` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `create_date` DATETIME NOT NULL, `modify_date` DATETIME NOT NULL, `creator_user` INT NULL, `modifier_user` INT NULL' . $Additional_Columns_SQL . ') ENGINE = MYISAM ;';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
			
			if ($The_Result && $The_Input_Table_Type == 'Image_Map') :
			
				$The_Tip_Template_Name = $The_Input_Table_Name.'_mmksys_tip';
				$The_Tip_View_ID = $this->Create_The_View($The_Tip_Template_Name);
				$this->Update_The_View('id', $The_Tip_View_ID, array('form_id' => $The_Table_ID));
				Create_The_Template_For_The_View($The_Tip_View_ID, $The_Tip_Template_Name, $this, true);
			
			endif;
										
		endif;
		
		return $The_Table_ID;
		
	}
	
	function Create_The_User(
							$The_Input_User_Name, 
							$The_Input_Password = '', 
							$The_Input_Email = '', 
							$The_Input_Confirmation_Code = '', 
							$The_Input_Moderation_Status = 'NEW')
	{
		if ( $this->All_Values_From_The_Database_Corresponding_To(
										'Users',
										'id',
										'login',
										$The_Input_User_Name )) :
		
			return false;
			
		else :
		
			$The_SQL = 'INSERT INTO Users (login, create_date, modify_date';
			
			if ($The_Input_Password) $The_SQL .= ', password';
			
			if ($The_Input_Email) $The_SQL .= ', email';
			
			if ($The_Input_Moderation_Status) $The_SQL .= ', moderation_status';
			
			if ($The_Input_Confirmation_Code) $The_SQL .= ', confirmation_code';
			
			$The_SQL .= ') VALUES (\'' . $The_Input_User_Name . '\', \'' . date('Y-m-d H:i:s') . '\', \'' . date('Y-m-d H:i:s') . '\'';
			
			if ($The_Input_Password) $The_SQL .= ', \'' . md5($The_Input_Password) . '\'';
			
			if ($The_Input_Email) $The_SQL .= ', \'' . $The_Input_Email . '\'';
			
			if ($The_Input_Moderation_Status) $The_SQL .= ', \'' . $The_Input_Moderation_Status . '\'';
			
			if ($The_Input_Confirmation_Code) $The_SQL .= ', \'' . $The_Input_Confirmation_Code . '\'';
			
			$The_SQL .= ')';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
			$The_User_ID = mysql_insert_id($this->Database_Connection);
			
			$The_Default_Groups = $this->All_Values_From_The_Database_Corresponding_To(
										'Groups',
										'id',
										'is_default',
										'1' );
			
			if (is_array($The_Default_Groups)) :
			
				foreach ($The_Default_Groups as $The_Group) :
				
					$this->Create_The_Group_Association_For_The_User($The_Group['id'], $The_User_ID);
				
				endforeach;
				
			endif;
										
		endif;
		
		return $The_User_ID;
		
	}
	
	function Create_The_View($The_Input_View_Name)
	{
		$The_Input_View_Name = ucwords($The_Input_View_Name);
	
		$The_New_View_Name = str_replace(' ', '_', $The_Input_View_Name);
		
		if ( $this->All_Values_From_The_Database_Corresponding_To(
										'Views',
										'id',
										'display_name',
										$The_Input_View_Name )) :
		
			return false;
			
		else :
		
			$The_SQL = 'INSERT INTO Views (display_name) VALUES (\'' . $The_Input_View_Name . '\')';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
			$The_View_ID = mysql_insert_id($this->Database_Connection);
										
		endif;
		
		return $The_View_ID;
		
	}
	
	function Creates_A_Group_Custom_Field(
							$The_Input_Field_Name,
							$The_Input_Field_Type,
							$The_Input_Input_Control_Width,
							$The_Input_Character_Limit,
							$The_Input_Display_Flag,
							$The_Input_Relational_Table_ID = '',
							$The_Input_Relational_Field_ID_1 = '',
							$The_Input_Relational_Field_ID_2 = '',
							$The_Input_Relational_Field_ID_3 = '',
							$The_Input_Start_Year = '',
							$The_Input_End_Year = '',
							$The_Input_Required_Flag = false,
							$The_Input_Explanatory_Text = '',
							$The_Input_Options_Text = '')
	{
		return $this->Creates_A_Custom_Field('group', $The_Input_Field_Name, $The_Input_Field_Type, $The_Input_Input_Control_Width,
							$The_Input_Character_Limit, $The_Input_Display_Flag, $The_Input_Relational_Table_ID, 
							$The_Input_Relational_Field_ID_1, $The_Input_Relational_Field_ID_2, $The_Input_Relational_Field_ID_3,
							$The_Input_Start_Year, $The_Input_End_Year, $The_Input_Required_Flag, $The_Input_Modifiable_By_User_Flag,
							$The_Input_Explanatory_Text, $The_Input_Options_Text);
	}
	
	function Creates_A_User_Custom_Field(
							$The_Input_Field_Name,
							$The_Input_Field_Type,
							$The_Input_Input_Control_Width,
							$The_Input_Character_Limit,
							$The_Input_Display_Flag,
							$The_Input_Relational_Table_ID = '',
							$The_Input_Relational_Field_ID_1 = '',
							$The_Input_Relational_Field_ID_2 = '',
							$The_Input_Relational_Field_ID_3 = '',
							$The_Input_Start_Year = '',
							$The_Input_End_Year = '',
							$The_Input_Required_Flag = false,
							$The_Input_Modifiable_By_User_Flag = false,
							$The_Input_Explanatory_Text = '',
							$The_Input_Options_Text = '')
	{
		return $this->Creates_A_Custom_Field('user', $The_Input_Field_Name, $The_Input_Field_Type, $The_Input_Input_Control_Width,
							$The_Input_Character_Limit, $The_Input_Display_Flag, $The_Input_Relational_Table_ID, 
							$The_Input_Relational_Field_ID_1, $The_Input_Relational_Field_ID_2, $The_Input_Relational_Field_ID_3,
							$The_Input_Start_Year, $The_Input_End_Year, $The_Input_Required_Flag, $The_Input_Modifiable_By_User_Flag,
							$The_Input_Explanatory_Text, $The_Input_Options_Text);
	}
	
	function Creates_A_Custom_Field(
							$The_Input_Type, // 'user' or 'group'
							$The_Input_Field_Name,
							$The_Input_Field_Type,
							$The_Input_Input_Control_Width,
							$The_Input_Character_Limit,
							$The_Input_Display_Flag,
							$The_Input_Relational_Table_ID = '',
							$The_Input_Relational_Field_ID_1 = '',
							$The_Input_Relational_Field_ID_2 = '',
							$The_Input_Relational_Field_ID_3 = '',
							$The_Input_Start_Year = '',
							$The_Input_End_Year = '',
							$The_Input_Required_Flag = false,
							$The_Input_Modifiable_By_User_Flag = false,
							$The_Input_Explanatory_Text = '',
							$The_Input_Options_Text = '')
	{
		$The_New_Field_Name = The_Mimik_Safe_Field_Name($The_Input_Field_Name);
		
		$The_New_Field_Display_Name = $The_Input_Field_Name;

		$The_New_Field_Type = $The_Input_Field_Type;
		
		if ($The_New_Field_Type == 'Text') $The_New_Input_Control_Width = $The_Input_Input_Control_Width;
		else $The_New_Input_Control_Width = 'NULL';
		
		if (($The_New_Field_Type == 'Text' || $The_New_Field_Type == 'Text Area') && is_numeric($The_Input_Character_Limit)) $The_New_Character_Limit = $The_Input_Character_Limit;
		else $The_New_Character_Limit = 'NULL';
		
		$The_New_Display_Flag = ($The_Input_Display_Flag == true) ? 1 : 0;
		
		$The_New_Required_Flag = ($The_Input_Required_Flag == true) ? 1 : 0;
		
		$The_New_Modifiable_By_User_Flag = ($The_Input_Modifiable_By_User_Flag == true) ? 1 : 0;
		
		if ( $this->First_Row_From_The_Database_Corresponding_To_Multiple_Conditions(
										'Fields',
										array(
											'name'=>$The_New_Field_Name,
											'is_' . $The_Input_Type . '_field'=>1
										),
										'display_order_number',
										$The_Name_Of_The_Order_Direction = 'DESC' ) ) :
			return false;
			
		else :
		
			$The_Highest_Display_Order_Number_Row = $this->First_Row_From_The_Database_Corresponding_To(
										'Fields',
										'display_order_number',
										'DESC',
										'is_' . $The_Input_Type . '_field',
										1 );

			$The_Highest_Display_Order_Number = $The_Highest_Display_Order_Number_Row['display_order_number'];
			
			$The_New_Display_Order_Number = $The_Highest_Display_Order_Number + 1;
			
			global $THE_FIELD_TYPE_ARRAY;
			
			$The_SQL = 'INSERT INTO `Fields` (display_name, name, type, display_in_management_view, display_order_number, ' .
					   'is_' . $The_Input_Type . '_field, is_required, is_modifiable_by_user, explanatory_text, options_text, ' .
					   'input_control_width, character_limit) ' .
						'VALUES (\'' . $The_New_Field_Display_Name . '\', ' .
						'\'' . $The_New_Field_Name . '\', \'' .
						$The_New_Field_Type . '\', \'' .
						$The_New_Display_Flag . '\', ' .
						$The_New_Display_Order_Number . ', ' .
						'\'1\', \'' .
						$The_New_Required_Flag . '\', \'' .
						$The_New_Modifiable_By_User_Flag . '\', \'' .
						$The_Input_Explanatory_Text . '\', \'' . 
						$The_Input_Options_Text . '\', ' .
						$The_New_Input_Control_Width . ', ' .
						$The_New_Character_Limit . ')';
						
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
			$The_Field_ID = mysql_insert_id($this->Database_Connection);
			
			if ($The_Input_Type == 'user') :
			
				// only alter the Users table if the SQL field type is not NULL (it's null for Group Permission and User Permission)
				if ($THE_FIELD_TYPE_ARRAY[$The_New_Field_Type]['sql_field_type']) :
				
					$The_SQL = 'ALTER TABLE `Users` ADD `' . $The_New_Field_Name . '` ';
					
					// use the SQL data type defined in the_system_settings.config.php
					$The_SQL .= $THE_FIELD_TYPE_ARRAY[$The_New_Field_Type]['sql_field_type'];
					
					$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
												or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
												
				endif;
				
			elseif ($The_Input_Type == 'group') :
			
				// only alter the Groups table if the SQL field type is not NULL (it's null for Group Permission and User Permission)
				if ($THE_FIELD_TYPE_ARRAY[$The_New_Field_Type]['sql_field_type']) :
				
					$The_SQL = 'ALTER TABLE `Groups` ADD `' . $The_New_Field_Name . '` ';
					
					// use the SQL data type defined in the_system_settings.config.php
					$The_SQL .= $THE_FIELD_TYPE_ARRAY[$The_New_Field_Type]['sql_field_type'];
					
					$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
												or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
												
				endif;
			
			endif;
									
			// for "Dynamic Select"	fields, write the appropriate row to the Relationships table
			if ($The_New_Field_Type == 'Dynamic Select' || $The_New_Field_Type == 'Dynamic Radio') :
			
				$this->Create_The_Relationship($The_Field_ID, $The_Input_Relational_Field_ID_1, $The_Input_Relational_Field_ID_2, $The_Input_Relational_Field_ID_3);
										
			endif;
			
			// for "Date" fields, write the appropriate row to the Dates table
			if ($The_New_Field_Type == 'Date') :
			
				$this->Create_The_Date($The_Field_ID, $The_Input_Start_Year, $The_Input_End_Year);
										
			endif;
			
		endif;
		
		return $The_Field_ID;
	}
	
	function Decrement_The_Field_Display_Position_Within_The_Table($The_Input_Field_ID, $The_Input_Table_ID)
	{
		if ($The_Input_Table_ID == 'user') :
		
			$The_Fields = $this->All_User_Custom_Fields();
		
		elseif ($The_Input_Table_ID == 'group') :
		
			$The_Fields = $this->All_Group_Custom_Fields();
		
		else :
		
			$The_Fields = $this->All_Rows_From_The_Database_Corresponding_To(
										'Fields',  // the table from which the row will be selected
										'table_id',
										$The_Input_Table_ID,
										'display_in_management_view',
										'DESC',
										'display_order_number',
										'ASC' );
										
		endif;
		
		foreach ($The_Fields as $The_Counter => $The_Field) :
		
			if ($The_Field['id'] == $The_Input_Field_ID) :
			
				$The_Target_Field_Counter = $The_Counter;
				
			endif;
		
		endforeach;
		
		if ($The_Target_Field_Counter == 0) : 
			
			return false;
			
		endif;
		
		$The_Target_Field_Order_Number = $The_Fields[$The_Target_Field_Counter - 1]['display_order_number'];
		
		$The_Shifted_Field_Order_Number = $The_Fields[$The_Target_Field_Counter]['display_order_number'];
		
		$The_Shifted_Field_ID = $The_Fields[$The_Target_Field_Counter - 1]['id'];
		
		$The_Target_Field_Displayed_In_Management_View = $this->Gets_The_Value_In_The_Row_And_Column_Of_The_Table(
												'Fields',
												'display_in_management_view',
												$The_Input_Field_ID );
		
		$The_Shifted_Field_Displayed_In_Management_View = $this->Gets_The_Value_In_The_Row_And_Column_Of_The_Table(
												'Fields',
												'display_in_management_view',
												$The_Shifted_Field_ID );
												
		$this->Update_The_Value_For_A_Single_Entry( 'Fields',
												  'id',
												  $The_Input_Field_ID,
												  'display_order_number',
												  $The_Target_Field_Order_Number );
												  
		$this->Update_The_Value_For_A_Single_Entry( 'Fields',
												  'id',
												  $The_Shifted_Field_ID,
												  'display_order_number',
												  $The_Shifted_Field_Order_Number );
	}
	
	function Delete_All_Group_Associations_For_The_User($The_Input_User_ID)
	{
		if (is_numeric($The_Input_User_ID)) :
		
			$The_SQL = 'DELETE FROM Group_User_Associations WHERE user_id = ' . $The_Input_User_ID;
		
			return mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
		else :
		
			return true;
			
		endif;
	}
	
	function Delete_The_Date_For_The_Target_Field($The_Input_Target_Field_ID)
	{
		$The_SQL = 'DELETE FROM mmk_Dates WHERE target_field = ' . $The_Input_Target_Field_ID . ' LIMIT 1';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	
	}
	
	function Delete_The_Field_In_The_Table($The_Input_Field_ID, $The_Input_Table_ID)
	{
		if (is_numeric($The_Input_Table_ID)) $The_Table_Name = $this->Gets_The_Name_Of_The_Table( $The_Input_Table_ID );

		$The_Field_Name = $this->First_Value_From_The_Database_Corresponding_To(
										'Fields',
										'name',
										'',
										'',
										'id',
										$The_Input_Field_ID );
										
		$The_Field_Information = $this->First_Row_From_The_Database_Corresponding_To(
											'Fields',
											'display_name',
											'ASC',
											'id',
											$The_Input_Field_ID );
											
		$Is_User_Field = $The_Field_Information['is_user_field'];
		
		$Is_Group_Field = $The_Field_Information['is_group_field'];
											
		$The_Field_Type = $The_Field_Information['type'];
											
		if ($The_Field_Type == 'Dynamic Select' || $The_Field_Type == 'Dynamic Radio') :
		
			$this->Delete_The_Relationship_For_The_Target_Field($The_Input_Field_ID);
		
		endif;
		
		if ($The_Field_Type == 'Group Permission') :
		
			$this->Delete_The_Group_Permissions_For_The_Submission($The_Input_Table_ID);
		
		endif;
		
		if ($The_Field_Type == 'User Permission') :
		
			$this->Delete_The_User_Permissions_For_The_Submission($The_Input_Table_ID);
		
		endif;
		
		// don't alter the table structure for Group Permission or User Permission
		// fields; they don't exist as fields in the table
		
		global $THE_FIELD_TYPE_ARRAY;
		
		if ($Is_User_Field) :
		
			$The_SQL = 'ALTER TABLE `Users` DROP `' . $The_Field_Name . '`';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
											or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
		
		elseif ($Is_Group_Field) :
		
			$The_SQL = 'ALTER TABLE `Groups` DROP `' . $The_Field_Name . '`';
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
											or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
		
		else :
		
			if ($THE_FIELD_TYPE_ARRAY[$The_Field_Type]['sql_field_type']) :
	
				$The_SQL = 'ALTER TABLE `' . $The_Table_Name . '` DROP `' . $The_Field_Name . '`';
				
				$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
												or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
												
			endif;
			
		endif;

		$The_SQL = 'DELETE FROM Fields WHERE id = ' . $The_Input_Field_ID;

		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());

	}
	
	function Delete_The_Group($The_Input_Group_ID)
	{
		$The_SQL = 'DELETE FROM Groups WHERE id = ' . $The_Input_Group_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	
	}
	
	function Delete_The_Group_Permissions_For_The_Form($The_Input_Form_ID)
	{
		$The_SQL = 'DELETE FROM mmksys_Form_Group_Permissions WHERE form_id = ' . $The_Input_Form_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
	}
	
	function Delete_The_Group_Permissions_For_The_Submission($The_Input_Form_ID, $The_Input_Submission_ID = NULL)
	{
		$The_SQL = 'DELETE FROM mmksys_Submission_Group_Permissions WHERE form_id = ' . $The_Input_Form_ID;
		
		if ($The_Input_Submission_ID != NULL) $The_SQL .= ' AND submission_id = ' . $The_Input_Submission_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
	}
	
	function Delete_The_User_Permissions_For_The_Submission($The_Input_Form_ID, $The_Input_Submission_ID = NULL)
	{
		$The_SQL = 'DELETE FROM mmksys_Submission_User_Permissions WHERE form_id = ' . $The_Input_Form_ID;
		
		if ($The_Input_Submission_ID != NULL) $The_SQL .= ' AND submission_id = ' . $The_Input_Submission_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
	}
	
	function Delete_The_Relationship_For_The_Target_Field($The_Input_Target_Field_ID)
	{
		$The_SQL = 'DELETE FROM `Relationships` WHERE target_field = ' . $The_Input_Target_Field_ID . ' LIMIT 1';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	
	}
	
	function Delete_The_Submission_In_The_Table($The_Input_Submission_ID, $The_Input_Table_ID)
	{
		$The_Table_Name = $this->Gets_The_Name_Of_The_Table( $The_Input_Table_ID );
		
		$The_SQL .= 'DELETE FROM `' . $The_Table_Name . '` WHERE id = ' . $The_Input_Submission_ID . ';';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
	}
	
	function Delete_The_Table($The_Input_Table_ID)
	{
		$The_Table_Name = $this->Gets_The_Name_Of_The_Table( $The_Input_Table_ID );
		
		$The_Result_Rows = $this->All_Values_From_The_Database_Corresponding_To(
										'Fields',
										'id',
										'table_id',
										$The_Input_Table_ID);
										
		if (is_array($The_Result_Rows)) foreach ($The_Result_Rows as $The_Result_Row) :
		
			$this->Delete_The_Field_In_The_Table($The_Result_Row['id'], $The_Input_Table_ID);
		
		endforeach;
										
		$The_SQL = 'DROP Table `' . $The_Table_Name . '`';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
		$The_SQL = 'DELETE FROM Tables WHERE id = ' . $The_Input_Table_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
		$this->Delete_The_Group_Permissions_For_The_Form($The_Input_Table_ID);
		
		$this->Delete_The_Group_Permissions_For_The_Submission($The_Input_Table_ID, NULL); // deletes for all submissions
		
		$this->Delete_The_User_Permissions_For_The_Submission($The_Input_Table_ID, NULL); // deletes for all submissions
		
		/* unnecessary
		$The_SQL = 'DELETE FROM Fields WHERE table_id = ' . $The_Input_Table_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
		*/
		
		$The_SQL = 'SELECT id FROM Views WHERE form_id = ' . $The_Input_Table_ID;
		
		$The_Result_Row = $this->First_Row_From_The_Database_Corresponding_To(
										'Views',
										'',
										'',
										'form_id',
										$The_Input_Table_ID );
										
		if (is_array($The_Result_Row)) $this->Delete_The_View($The_Result_Row['id']);
	}
	
	function Delete_The_User($The_Input_User_ID)
	{
		$The_SQL = 'DELETE FROM Users WHERE id = ' . $The_Input_User_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
		$this->Delete_All_Group_Associations_For_The_User($The_Input_User_ID);
	
	}
	
	function Delete_The_Group_Custom_Field($The_Input_Field_ID)
	{
		return Delete_The_Custom_Field('group', $The_Input_Field_ID);
		
	}
	
	function Delete_The_User_Custom_Field($The_Input_Field_ID)
	{
		return Delete_The_Custom_Field('user', $The_Input_Field_ID);
		
	}
	
	function Delete_The_Custom_Field($The_Input_Type, $The_Input_Field_ID)
	{
		$The_Field_Name = $this->First_Value_From_The_Database_Corresponding_To(
										'Fields',
										'name',
										'',
										'',
										'id',
										$The_Input_Field_ID );
										
		if ($The_Input_Type == 'user') $The_SQL = 'ALTER TABLE `Users` DROP `' . $The_Field_Name . '`';
		elseif ($The_Input_Type == 'group') $The_SQL = 'ALTER TABLE `Groups` DROP `' . $The_Field_Name . '`';
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());

		$The_SQL = 'DELETE FROM Fields WHERE id = ' . $The_Input_Field_ID;

		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());

	}
	
	function Delete_The_View($The_Input_View_ID)
	{
		$The_SQL = 'DELETE FROM Views WHERE id = ' . $The_Input_View_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
										
		$The_SQL = 'DELETE FROM mmksys_View_Group_Permissions WHERE view_id = ' . $The_Input_View_ID;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
						  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
	
	}
	
	function Field_Type_Of_The_Field_For_The_Form($The_Input_Form_ID, $The_Input_Field_Name)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Fields',
										'type',
										'',
										'',
										'name',
										$The_Input_Field_Name );
	}
	
	function Gets_The_Confirmation_Message_For_The_Form($The_Input_Form_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Tables',
										'confirmation_message',
										'',
										'',
										'id',
										$The_Input_Form_ID );
	}
	
	function Gets_The_Name_Of_The_Group($The_Input_Group_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Groups',
										'name',
										'',
										'',
										'id',
										$The_Input_Group_ID );
	
	}
	
	function Gets_The_Display_Name_Of_The_Table($The_Input_Table_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Tables',
										'display_name',
										'',
										'',
										'id',
										$The_Input_Table_ID );
	}
	
	function Gets_The_Display_Name_Of_The_View($The_Input_View_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Views',
										'display_name',
										'',
										'',
										'id',
										$The_Input_View_ID );
	}

	function Gets_The_Display_Name_Of_The_Field($The_Input_Field_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Fields',
										'display_name',
										'',
										'',
										'id',
										$The_Input_Field_ID	);
	}
	
	function Gets_The_Dynamic_Relation_Information_For($The_Input_Target_Field_ID)
	{
		$The_Result_Array = $this->First_Row_From_The_Database_Corresponding_To(
										'Relationships',
										'',
										'',
										'target_field',
										$The_Input_Target_Field_ID );
		
		if (!is_array($The_Result_Array)) return false;
		else $The_Return_Array = array();
		
		$The_Return_Array['table_id'] = $this->First_Value_From_The_Database_Corresponding_To(
										'Fields',
										'table_id',
										'',
										'',
										'id',
										$The_Result_Array['relational_field_1'] );
										
		$The_Table_Name = $this->Gets_The_Name_Of_The_Table($The_Return_Array['table_id']);

		$The_Row_IDs = $this->All_Values_From_The_Database_Corresponding_To(
										$The_Table_Name,
										'id' );
										
		if (is_array($The_Row_IDs)) foreach ($The_Row_IDs as $The_Row_ID) :
		
			$The_Return_Array['row_ids'][] = $The_Row_ID['id'];
		
		endforeach;
												
		$The_Return_Array['column_ids'] = array(
											$The_Result_Array['relational_field_1'],
											$The_Result_Array['relational_field_2'],
											$The_Result_Array['relational_field_3'] );
		
		return $The_Return_Array;
	}
	
	function Gets_The_Dynamic_Value_Relation_For($The_Input_Table_ID, $The_Input_Column_ID, $The_Input_Row_ID)
	{
		$The_Result_Array = $this->First_Row_From_The_Database_Corresponding_To(
										'Relationships',
										'',
										'',
										'target_field',
										$The_Input_Column_ID );
		
		if (!is_array($The_Result_Array)) return false;
		else $The_Return_Array = array();
		
		$The_Return_Array['table_id'] = $this->First_Value_From_The_Database_Corresponding_To(
										'Fields',
										'table_id',
										'',
										'',
										'id',
										$The_Result_Array['relational_field_1'] );
										
		$The_Return_Array['row_id'] = $this->Gets_The_Value_In_The_Row_And_Column_Of_The_Table(
												$The_Input_Table_ID,
												$The_Input_Column_ID,
												$The_Input_Row_ID );
												
		$The_Return_Array['column_ids'] = array(
											$The_Result_Array['relational_field_1'],
											$The_Result_Array['relational_field_2'],
											$The_Result_Array['relational_field_3'] );
		
		return $The_Return_Array;
	}
	
	function Gets_The_Field_Value_Pairs_For_The_Table_ID($The_Input_Table_ID)
	{
		$The_Fields = $this->All_Rows_From_The_Database_Corresponding_To(
										'Fields',
										'table_id',
										$The_Input_Table_ID,
										'display_order_number',
										'ASC' );
										
		$The_Table_Information = $this->First_Row_From_The_Database_Corresponding_To(
										'Tables',
										'',
										'',
										'id',
										$The_Input_Table_ID );
										
		$The_Table_Name = $The_Table_Information['table_name'];
		
		$The_Temporary_Field_Value_Pairs = $this->All_Rows_From_The_Database_Corresponding_To(
										$The_Table_Name,
										'',
										'',
										$The_Sort_Field,
										$The_Sort_Order );
		
		if (is_array($The_Temporary_Field_Value_Pairs)) :

			foreach ($The_Temporary_Field_Value_Pairs as $The_Key => $The_Temporary_Field_Value_Pair) :
			
				foreach ($The_Fields as $The_Field) :
				
					if ($The_Field['type'] == 'Dynamic Select' || $The_Field['type'] == 'Dynamic Radio') :
					
						$The_Relational_Table_ID = $this->Gets_The_Related_Table_For_The_Relational_Field($The_Field['id']);
						
						$The_Relational_Fields = $this->All_User_Defined_Fields_For_The_Table($The_Relational_Table_ID);
						
						$The_Field_Value_Pairs[$The_Key][$The_Field['name']] = $this->Gets_The_Relational_Data_For_The_Item($The_Relational_Table_ID, $The_Temporary_Field_Value_Pair[$The_Field['name']], $The_Field['id']);
					
					else :
				
						$The_Field_Value_Pairs[$The_Key][$The_Field['name']] = $The_Temporary_Field_Value_Pair[$The_Field['name']];
						
					endif;
				
				endforeach;
			
			endforeach;
			
		endif;
		
		return $The_Field_Value_Pairs;
	}
	
	function Gets_The_ID_Of_The_Form_For_The_Safe_Name($The_Input_Safe_Form_Name)
	{
		$The_SQL = 'SELECT id FROM Tables WHERE LOWER(REPLACE(REPLACE(display_name, \'mimik_\', \'\'), \' \', \'_\')) = \'' . $The_Input_Safe_Form_Name . '\'';
		
		$The_Rows = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
				
		return $The_Rows[0]['id'];
	}
	
	function Gets_The_Name_Of_The_Field($The_Input_Field_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Fields',
										'name',
										'',
										'',
										'id',
										$The_Input_Field_ID );
	}
	
	function Gets_The_Name_Of_The_Table($The_Input_Table_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Tables',
										'table_name',
										'',
										'',
										'id',
										$The_Input_Table_ID );
	}
	
	function Gets_The_Safe_Name_Of_The_Form_For_The_ID($The_Input_ID)
	{
		$The_SQL = 'SELECT LOWER(REPLACE(REPLACE(display_name, \'mimik_\', \'\'), \' \', \'_\')) as `display_name` WHERE id = ' . $The_Input_Form_ID;
		
		$The_Rows = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
				
		return $The_Rows[0]['display_name'];
	}
	
	function Gets_The_Type_Of_The_Table($The_Input_Table_ID)
	{
		return $this->First_Value_From_The_Database_Corresponding_To(
										'Tables',
										'type',
										'',
										'',
										'id',
										$The_Input_Table_ID );
	}
	
	function Gets_The_Names_Of_All_Group_Permission_Fields_For_The_Form($The_Input_Form_ID)
	{
		return $this->All_Values_From_The_Database_Corresponding_To_Multiple_Conditions(
										'Fields',
										'name',
										array('type' => 'Group Permission',
											  'table_id' => $The_Input_Form_ID) );
	}
	
	function Gets_The_Names_Of_All_User_Permission_Fields_For_The_Form($The_Input_Form_ID)
	{
		return $this->All_Values_From_The_Database_Corresponding_To_Multiple_Conditions(
										'Fields',
										'name',
										array('type' => 'User Permission',
											  'table_id' => $The_Input_Form_ID) );
	}
	
	function Gets_The_Related_Table_For_The_Relational_Field($The_Input_Field_ID)
	{
		$The_Relational_Table_SQL = 'SELECT FR.table_id FROM `Fields` FR INNER JOIN Relationships R ON FR.id = R.relational_field_1 INNER JOIN `Fields` FT ON FT.id = R.target_field WHERE FT.id = ' . $The_Input_Field_ID;
						
		$The_Relational_Table_Rows = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_Relational_Table_SQL);
		
		return $The_Relational_Table_Rows[0]['table_id'];
		
	}
	
	function Gets_The_Relational_Data_For_The_Item($The_Input_Table_ID, $The_Input_Row_ID, $The_Input_Field_ID)
	{
		$The_Table_Information = $this->First_Row_From_The_Database_Corresponding_To(
										'Tables',
										'',
										'',
										'id',
										$The_Input_Table_ID );
										
		$The_Table_Name = $The_Table_Information['table_name'];
		
		$The_Temporary_Field_Value_Pairs = $this->All_Rows_From_The_Database_Corresponding_To(
										$The_Table_Name,
										'id',
										$The_Input_Row_ID );
		
		return $The_Temporary_Field_Value_Pairs[0];
	}
	
	function Gets_The_Submission_From_The_Table($The_Input_Table_ID, $The_Input_Submission_ID)
	{
		$The_Table_Name = $this->Gets_The_Name_Of_The_Table($The_Input_Table_ID);

		$The_Result_Rows = $this->First_Row_From_The_Database_Corresponding_To(
										$The_Table_Name,
										'',
										'',
										'id',
										$The_Input_Submission_ID );
										
		return $The_Result_Rows;
	}
	
	function Gets_The_Limited_Template_Data_For_The_View($The_Input_View_ID, $The_Input_Limit, $The_Input_Parameters = NULL, $The_Input_First_Record_To_Display = NULL)
	{
		$The_View = new A_View($this);
		
		$The_Submissions = $The_View->Gets_The_Submissions($The_Input_View_ID, $The_Input_Submission_ID, $The_Input_Limit, $The_Input_Parameters, $The_Input_First_Record_To_Display);
		
		return $The_Submissions;
	}
	
	function Gets_The_Permission_Limited_Template_Data_For_The_View($The_Input_View_ID, $The_Input_User_ID, $The_Input_Group_Array, $The_Input_Limit = NULL, $The_Input_Parameters = NULL, $The_Input_First_Record_To_Display = NULL)
	{
		$The_View = new A_View($this);
		
		$The_Submissions = $The_View->Gets_The_Submissions($The_Input_View_ID, $The_Input_Submission_ID, NULL, $The_Input_Parameters);
		
		$The_Form_ID = $this->Gets_The_Form_ID_For_The_View($The_Input_View_ID);
		
		$The_Limited_Submission_Set = array();
		
		if (is_array($The_Submissions)) foreach ($The_Submissions as $The_Key => $The_Submission) :
		
			$Has_Permission_Restrictions = false;
	
			$The_User_Has_Permission_To_View_The_Record = false;
	
			$The_Group_Permissions = $this->All_Group_Permissions_For_The_Submission($The_Form_ID, $The_Submission->ID);
				
			$The_User_Permissions = $this->All_User_Permissions_For_The_Submission($The_Form_ID, $The_Submission->ID);
	
			if (is_array($The_Group_Permissions) || is_array($The_User_Permissions)) $Has_Permission_Restrictions = true;
	
			if (is_array($The_Group_Permissions)) foreach ($The_Group_Permissions as $The_Group_Permission) :
	
				if (in_array($The_Group_Permission['group_id'], $The_Input_Group_Array)) :
	
					$The_User_Has_Permission_To_View_The_Record = true;
	
				endif;
	
			endforeach;
	
			if (!$The_User_Has_Permission_To_View_The_Record) :
	
				if (is_array($The_User_Permissions)) :
	
					foreach ($The_User_Permissions as $The_User_Permission) :
	
						if ($The_User_Permission['user_id'] == $The_Input_User_ID) :
	
							$The_User_Has_Permission_To_View_The_Record = true;
	
						endif;
	
					endforeach;
	
				endif;
	
			endif;
			
			if ($The_User_Has_Permission_To_View_The_Record || !($Has_Permission_Restrictions)) :
			
				$The_Limited_Submission_Set[] = $The_Submission;
			
			endif;

		endforeach;
		
		if ($The_Input_First_Record_To_Display) :
		
			$The_Limited_Submission_Set = array_slice($The_Limited_Submission_Set, $The_Input_First_Record_To_Display, $The_Input_Limit);
			
		else :
		
			if ($The_Input_Limit) :
			
				$The_Limited_Submission_Set = array_slice($The_Limited_Submission_Set, 0, $The_Input_Limit);
			
			endif;

		endif;
		
		return $The_Limited_Submission_Set;
	}
	
	function Gets_The_Template_Data_For_The_Form($The_Input_Form_ID)
	{
		$The_View = new A_View($this);
		
		$The_Submissions = $The_View->Gets_The_Submissions_For_The_Form($The_Input_Form_ID, true);
		
		return $The_Submissions;
	}
	
	function Gets_The_Template_Data_For_The_View($The_Input_View_ID, $The_Input_Submission_ID = NULL, $The_Input_Parameters = NULL)
	{
		$The_View = new A_View($this);

		// the third parameter is the limit on the number of returned submissions, which is NULL
		$The_Submissions = $The_View->Gets_The_Submissions($The_Input_View_ID, $The_Input_Submission_ID, NULL, $The_Input_Parameters);

		return $The_Submissions;
	}
	
	function Gets_The_Value_In_The_Row_And_Column_Of_The_Table($The_Input_Table_ID, $The_Input_Column_ID, $The_Input_Row_ID)
	{
		if (is_numeric($The_Input_Table_ID)) $The_Table_Name = $this->Gets_The_Name_Of_The_Table( $The_Input_Table_ID );
		else $The_Table_Name = $The_Input_Table_ID;
		
		if (is_numeric($The_Input_Column_ID)) $The_Field_Name = $this->Gets_The_Name_Of_The_Field( $The_Input_Column_ID );
		else $The_Field_Name = $The_Input_Column_ID;
		
		$The_Result_Value = $this->First_Value_From_The_Database_Corresponding_To(
										$The_Table_Name,
										$The_Field_Name,
										'',
										'',
										'id',
										$The_Input_Row_ID );
										
		return $The_Result_Value;
	}
	
	function Increment_The_Field_Display_Position_Within_The_Table($The_Input_Field_ID, $The_Input_Table_ID)
	{
		if ($The_Input_Table_ID == 'user') :
		
			$The_Fields = $this->All_User_Custom_Fields();
			
		elseif ($The_Input_Table_ID == 'group') :
		
			$The_Fields = $this->All_Group_Custom_Fields();
		
		else :
		
			$The_Fields = $this->All_Rows_From_The_Database_Corresponding_To(
										'Fields',  // the table from which the row will be selected
										'table_id',
										$The_Input_Table_ID,
										'display_in_management_view',
										'DESC',
										'display_order_number',
										'ASC' );
										
		endif;

		foreach ($The_Fields as $The_Counter => $The_Field) :
		
			if ($The_Field['id'] == $The_Input_Field_ID) :
			
				$The_Target_Field_Counter = $The_Counter;
			
			endif;
		
		endforeach;
		
		if ($The_Target_Field_Counter == count($The_Fields) - 1) : 
			
			return false;
			
		endif;
		
		$The_Target_Field_Order_Number = $The_Fields[$The_Target_Field_Counter + 1]['display_order_number'];
		
		$The_Shifted_Field_Order_Number = $The_Fields[$The_Target_Field_Counter]['display_order_number'];
		
		$The_Shifted_Field_ID = $The_Fields[$The_Target_Field_Counter + 1]['id'];
		
		$this->Update_The_Value_For_A_Single_Entry( 'Fields',
												  'id',
												  $The_Input_Field_ID,
												  'display_order_number',
												  $The_Target_Field_Order_Number );
												  
		$this->Update_The_Value_For_A_Single_Entry( 'Fields',
												  'id',
												  $The_Shifted_Field_ID,
												  'display_order_number',
												  $The_Shifted_Field_Order_Number );
	}
	
	function Set_The_Audience_For_The_Form($The_Input_Form_ID, $The_Input_Audience)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'audience',
												  $The_Input_Audience );
	}
	
	function Set_The_Audience_For_The_View($The_Input_View_ID, $The_Input_Audience)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Views',
												  'id',
												  $The_Input_View_ID,
												  'audience',
												  $The_Input_Audience );
	}
	
	function Set_The_Confirmation_Message_For_The_Form($The_Input_Form_ID, $The_Input_Confirmation_Message)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'confirmation_message',
												  $The_Input_Confirmation_Message );
	}
	
	function Set_The_Email_Notification_For_The_Form($The_Input_Form_ID, $The_Input_Email_Notification_Flag)
	{
		$this->Update_The_Integer_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'email_notification_flag',
												  $The_Input_Email_Notification_Flag );
	}
	
	function Set_The_Email_Recipients_For_The_Form($The_Input_Form_ID, $The_Input_Email_Recipients)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'email_recipients',
												  $The_Input_Email_Recipients );
	}
	
	function Set_The_Group_Permissions_For_The_Submission($The_Input_Form_ID, $The_Input_Submission_ID, $The_Input_Group_Permissions_Array)
	{
		foreach ($The_Input_Group_Permissions_Array as $The_Group_ID_Index => $The_Group_ID) :
			
			if (!is_numeric($The_Group_ID)) unset($The_Input_Group_Permissions_Array[$The_Group_ID_Index]);
			
		endforeach;
		
		$The_SQL = 'DELETE FROM mmksys_Submission_Group_Permissions WHERE form_id = ' . $The_Input_Form_ID . ' AND submission_id = ' . $The_Input_Submission_ID;
		
		if (count($The_Input_Group_Permissions_Array) > 0) :
		
			$The_SQL .= ' AND group_id NOT IN (';
			
			foreach ($The_Input_Group_Permissions_Array as $The_Group_ID_Index => $The_Group_ID) :
			
				$The_SQL .= $The_Group_ID;
					
				if ($The_Group_ID_Index < (count($The_Input_Group_Permissions_Array) - 1)) $The_SQL .= ',';
				
			endforeach;
			
			$The_SQL .= ');';
			
		endif;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error()); // delete the form/group permission if it already exists
		
		if (count($The_Input_Group_Permissions_Array) > 0) :
		
			foreach ($The_Input_Group_Permissions_Array as $The_Group_ID) :
			
				$The_Existing_Row = $this->First_Row_From_The_Database_Corresponding_To_Multiple_Conditions(
														'mmksys_Submission_Group_Permissions',
														array(	'form_id' => $The_Input_Form_ID,
																'submission_id' => $The_Input_Submission_ID,
																'group_id' => $The_Group_ID ));
				
				if (!is_array($The_Existing_Row)) :
				
					$The_SQL = 'INSERT INTO mmksys_Submission_Group_Permissions (form_id, submission_id, group_id) VALUES (' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', ' . $The_Group_ID . ') ';
					
//					echo "<pre>The_SQL = $The_SQL</pre>";
					
					$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
											or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
											
				endif;
			
			endforeach;
		
		endif;
		
	}
	
	function Set_The_Limit_Access_For_The_Form($The_Input_Form_ID, $The_Input_Limit_Access)
	{
		if ($The_Input_Limit_Access == '' || $The_Input_Limit_Access == 0) $The_Input_Limit_Access = 0;
		else $The_Input_Limit_Access = 1;
		
		$this->Update_The_Integer_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'limit_access',
												  $The_Input_Limit_Access );
	}
	
	function Set_The_Limit_Access_For_The_View($The_Input_View_ID, $The_Input_Limit_Access)
	{
		if ($The_Input_Limit_Access == '' || $The_Input_Limit_Access == 0) $The_Input_Limit_Access = 0;
		else $The_Input_Limit_Access = 1;
		
		$this->Update_The_Integer_Value_For_A_Single_Entry( 'Views',
												  'id',
												  $The_Input_View_ID,
												  'limit_access',
												  $The_Input_Limit_Access );
	}
	
	function Set_The_Preview_View_ID_For_The_Form($The_Input_Form_ID, $The_Input_Preview_View_ID)
	{
		$this->Update_The_Integer_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'preview_view_id',
												  $The_Input_Preview_View_ID );
	}
	
	function Sets_The_Moderation_Status_For_The_User($The_Input_User_ID, $The_Input_Moderation_Status)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Users',
												  'id',
												  $The_Input_User_ID,
												  'moderation_status',
												  $The_Input_Moderation_Status );
												  
	}
	
	function Set_The_Name_For_The_Form($The_Input_Form_ID, $The_Input_Name)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'display_name',
												  $The_Input_Name );
	}
	
	function Sets_The_Temporary_Password_Flag($The_Input_User_ID, $The_Input_Flag)
	{
		if (in_array($The_Input_Flag, array(true, '1', 1))) $The_Input_Flag = '1';

		else $The_Input_Flag = '0';
		
		$this->Update_The_Value_For_A_Single_Entry( 'Users',
												  'id',
												  $The_Input_User_ID,
												  'temporary_password',
												  $The_Input_Flag );
	
	}
	
	function Set_The_Type_For_The_Form($The_Input_Form_ID, $The_Input_Type)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'type',
												  $The_Input_Type );
	}
	
	function Set_The_User_Permissions_For_The_Submission($The_Input_Form_ID, $The_Input_Submission_ID, $The_Input_User_Permissions_Array)
	{
		foreach ($The_Input_User_Permissions_Array as $The_User_ID_Index => $The_User_ID) :
			
			if (!is_numeric($The_User_ID)) unset($The_Input_User_Permissions_Array[$The_User_ID_Index]);
			
		endforeach;
		
		$The_SQL = 'DELETE FROM mmksys_Submission_User_Permissions WHERE form_id = ' . $The_Input_Form_ID . ' AND submission_id = ' . $The_Input_Submission_ID;
		
		if (count($The_Input_User_Permissions_Array) > 0) :
		
			$The_SQL .= ' AND user_id NOT IN (';
			
			foreach ($The_Input_User_Permissions_Array as $The_User_ID_Index => $The_User_ID) :
			
				$The_SQL .= $The_User_ID;
					
				if ($The_User_ID_Index < (count($The_Input_User_Permissions_Array) - 1)) $The_SQL .= ',';
				
			endforeach;
			
			$The_SQL .= ');';
			
		endif;
		
//		echo "<pre>The_SQL = $The_SQL</pre>";
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error()); // delete the form/group permission if it already exists
		
		if (count($The_Input_User_Permissions_Array) > 0) :
		
			foreach ($The_Input_User_Permissions_Array as $The_User_ID) :
			
				$The_Existing_Row = $this->First_Row_From_The_Database_Corresponding_To_Multiple_Conditions(
														'mmksys_Submission_User_Permissions',
														array(	'form_id' => $The_Input_Form_ID,
																'submission_id' => $The_Input_Submission_ID,
																'user_id' => $The_User_ID ));
				
				if (!is_array($The_Existing_Row)) :
				
					$The_SQL = 'INSERT INTO mmksys_Submission_User_Permissions (form_id, submission_id, user_id) VALUES (' . $The_Input_Form_ID . ', ' . $The_Input_Submission_ID . ', ' . $The_User_ID . ') ';
					
//					echo "<pre>The_SQL = $The_SQL</pre>";
					
					$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
											or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
											
				endif;
			
			endforeach;
		
		endif;
		
	}
	
	function Set_The_Filename_For_The_Form($The_Input_Form_ID, $The_Input_Filename)
	{
		$this->Update_The_Value_For_A_Single_Entry( 'Tables',
												  'id',
												  $The_Input_Form_ID,
												  'filename',
												  $The_Input_Filename );
	}
	
	function Update_A_User(	$The_Input_Key_Element_Name,
							$The_Input_Key_Element_Value,
							$The_Input_Set_Of_Database_Field_Value_Pairs )
	{
		if ($The_Input_Key_Element_Name == 'id') :
			$The_User_ID = $The_Input_Key_Element_Value;
		else :
			return 'Error: no user ID specified (ivy-mimik_db_utils line 2625)';
		endif;
		
		if ($The_Input_Set_Of_Database_Field_Value_Pairs['login']) :
		
			$The_SQL = 'SELECT id FROM Users WHERE login = \'' . $The_Input_Set_Of_Database_Field_Value_Pairs['login'] . '\'';
			
			$The_Duplicate_Login = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			if (is_array($The_Duplicate_Login)) :
			
				foreach ($The_Duplicate_Login as $The_Duplicate_Login_Row) :
			
					if ($The_Duplicate_Login_Row['id'] != $The_User_ID) :
					
						return 'Error: duplicate login';
						
					endif;
					
				endforeach;
				
			endif;
			
		endif;
		
		if ($The_Input_Set_Of_Database_Field_Value_Pairs['email']) :
		
			$The_SQL = 'SELECT id FROM Users WHERE email = \'' . $The_Input_Set_Of_Database_Field_Value_Pairs['email'] . '\'';
			
			$The_Duplicate_Email = $this->All_Rows_From_The_Database_Corresponding_To_Custom_Query($The_SQL);
			
			if (is_array($The_Duplicate_Email)) :
			
				foreach ($The_Duplicate_Email as $The_Duplicate_Email_Row) :
			
					if ($The_Duplicate_Email_Row['id'] != $The_User_ID) :
					
						return 'Error: duplicate email';
						
					endif;
					
				endforeach;
				
			endif;
			
		endif;
		
		$The_Input_Set_Of_Database_Field_Value_Pairs['modify_date'] = date('Y-m-d H:i:s');
	
		$this->Update_A_Database_Row( 'Users',
							$The_Input_Key_Element_Name,
							$The_Input_Key_Element_Value,
							$The_Input_Set_Of_Database_Field_Value_Pairs );
	}
	
	function Update_The_View(
							$The_Input_Key_Element_Name,
							$The_Input_Key_Element_Value,
							$The_Input_Set_Of_Database_Field_Value_Pairs )
	{
		if (isset($The_Input_Set_Of_Database_Field_Value_Pairs['view_name'])) :
		
			$The_Input_Set_Of_Database_Field_Value_Pairs['display_name'] = $The_Input_Set_Of_Database_Field_Value_Pairs['view_name'];
			
			unset($The_Input_Set_Of_Database_Field_Value_Pairs['view_name']);
			
		endif;
	
		$this->Update_A_Database_Row( 'Views',
							$The_Input_Key_Element_Name,
							$The_Input_Key_Element_Value,
							$The_Input_Set_Of_Database_Field_Value_Pairs );
	}
	
	function Update_Group_Associations_For_The_User($The_Input_User_ID, $The_Input_Group_IDs)
	{
		$this->Delete_All_Group_Associations_For_The_User($The_Input_User_ID);
		
		if (is_array($The_Input_Group_IDs)) :
		
			foreach ($The_Input_Group_IDs as $The_Group_ID) :
			
				$this->Create_The_Group_Association_For_The_User($The_Group_ID, $The_Input_User_ID);
			
			endforeach;
			
		endif;
	}
	
	function Update_The_Group($The_Input_Group_ID, $The_Input_Value_Pairs, $The_Input_Admin_Permission_IDs)
	{
		if (is_array($The_Input_Value_Pairs)) :
		
			$The_SQL = 'UPDATE Groups SET ';
		
			foreach ($The_Input_Value_Pairs as $The_Key => $The_Value) :
			
				$The_SQL .= '`' . $The_Key . '` = \'' . $The_Value . '\', ';
			
			endforeach;
			
			$The_SQL = substr($The_SQL, 0, strlen($The_SQL) - strlen(', '));
			
			$The_SQL .= 'WHERE id = ' . $The_Input_Group_ID;
			
			$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());

		endif;
	
		$The_SQL = 'DELETE FROM mmksys_Group_Admin_Permission_Associations WHERE group_id=' . $The_Input_Group_ID;
		
		if (count($The_Input_Admin_Permission_IDs) > 0) :
		
			$The_SQL .= ' AND admin_permission_id NOT IN(';
		
			foreach ($The_Input_Admin_Permission_IDs as $The_Index => $The_Admin_Permission_ID) :
				
				if (is_numeric($The_Admin_Permission_ID)) $The_SQL .= $The_Admin_Permission_ID;
				
				if ($The_Index < count($The_Input_Admin_Permission_IDs) - 1) $The_SQL .= ', ';
			
			endforeach;
			
			$The_SQL .= ')';
			
		endif;
		
		$The_Result = mysql_query( $The_SQL, $this->Database_Connection )
  				or die( "SQL: $The_SQL<br/><br/>MySQL_ERROR: " . mysql_error());
		
		if (is_array($The_Input_Admin_Permission_IDs)) foreach ($The_Input_Admin_Permission_IDs as $The_Admin_Permission_ID) :
			
			$this->Create_The_Admin_Permission_For_The_Group($The_Admin_Permission_ID, $The_Input_Group_ID);
		
		endforeach;
			
	}
	
}; // class A_Mimik_Database_Interface

?>
