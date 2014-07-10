<?

require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/a_submission.inc.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/mimik/mimik_includes/ivy-mimik_database_utilities.inc.php' );

class A_View
{
	var $Database_To_Use;
	
	function A_View($The_Input_Database_To_Use)
	{
		$this->Database_To_Use = $The_Input_Database_To_Use;
	}
	
	function Gets_The_View_Information($The_Input_View_ID)
	{
		$this->Local_Data = $this->Database_To_Use->First_Row_From_The_Database_Corresponding_To(
										'Views',
										'',
										'',
										'id',
										$The_Input_View_ID );
								
	}
	
	function Gets_The_Submissions($The_Input_View_ID, $The_Input_Submission_ID = NULL, $The_Input_Limit = NULL, $The_Input_Parameters = NULL, $The_Input_First_Record_To_Display = NULL)
	{
		$The_View_Information = $this->Database_To_Use->First_Row_From_The_Database_Corresponding_To(
										'Views',
										'',
										'',
										'id',
										$The_Input_View_ID );
										
		$The_Table_ID = $The_View_Information['form_id'];
		
		$The_Sort_Field = $The_View_Information['sort_field'];
		
		$The_Sort_Order = ($The_View_Information['sort_order'] == 'DESCENDING') ? 'DESC' : 'ASC';
	
		$this->Submission_Array = $this->Gets_The_Submissions_For_The_Form(
													$The_Table_ID,
													false,
													$The_Input_Submission_ID, 
													$The_Input_Limit, 
													$The_Input_Parameters, 
													$The_Input_First_Record_To_Display,
													$The_Sort_Field,
													$The_Sort_Order);

		return $this->Submission_Array;
	}
	
	function Gets_The_Submissions_For_The_Form(
								$The_Input_Form_ID,
								$Data_Only = false,
								$The_Input_Submission_ID = NULL, 
								$The_Input_Limit = NULL, 
								$The_Input_Parameters = NULL, 
								$The_Input_First_Record_To_Display = NULL,
								$The_Input_Sort_Field = 'id',
								$The_Input_Sort_Order = 'ASC')
	{
		$The_Table_Name = $this->Database_To_Use->Gets_The_Name_Of_The_Table( $The_Input_Form_ID );
		
		if (isset($The_Input_Submission_ID)) :
		
			$The_ID_Rows = array(array('id' => $The_Input_Submission_ID));
		
		else :
			$The_ID_Rows = $this->Database_To_Use->All_Values_From_The_Database_Corresponding_To_Multiple_Conditions(
										$The_Table_Name,
										'id',
										$The_Input_Parameters,
										$The_Input_Sort_Field,
										$The_Input_Sort_Order,
										$The_Input_Limit,
										$The_Input_First_Record_To_Display );

		endif;
		
		if (is_array($The_ID_Rows)) :
			foreach ($The_ID_Rows as $The_ID_Row) :

				$The_Submission = new A_Submission($this->Database_To_Use);
				$The_Submission->Loads_From_Table_Row($The_Input_Form_ID, $The_ID_Row['id']);

				if ($Data_Only) :
					$The_Submission_Data = array();
					$The_Submission_Data[] = $The_Submission->ID;
					$The_Submission_Data[] = $The_Submission->Create_Date;
					$The_Submission_Data[] = $The_Submission->Modify_Date;
					foreach ($The_Submission->Local_Values_Array as $The_Local_Value) :
						$The_Submission_Data[] = Gets_The_Bottom_Level_Data($The_Local_Value);
					endforeach;
					$The_Submission_Array[] = $The_Submission_Data;
				else :
					$The_Submission_Array[] = $The_Submission;
				endif;
			endforeach;
		endif;
		
		return $The_Submission_Array;
	}
};

function Gets_The_Bottom_Level_Data($The_Input_Value)
{
	if (is_a($The_Input_Value->Data, 'A_Submission')) :
		return Gets_The_Bottom_Level_Data($The_Input_Value->Data);
	else :
		return $The_Input_Value->Data;
	endif;
}
