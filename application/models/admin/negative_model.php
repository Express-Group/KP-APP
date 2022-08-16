<?php 
class negative_model extends CI_Model
{
	public function pagination_datatable()
	{
		$class_object = new negative_datatable;
		return $class_object->pagination_datatable();
	}
	public function delete_keyword($negKey_id) //delete function
	{
		$this->db->trans_begin();
		
		$del_query = $this->db->query("CALL delete_negativeKeyword('".$negKey_id."')");
		
		$success_msg =  'Deleted Successfully';
		$fail_msg = "Problem while deleting. Please try again";

		if($this->db->trans_status() == FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $fail_msg);
			redirect(folder_name.'/negative_manager');	
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('success', $success_msg);
			redirect(folder_name.'/negative_manager');	
		}
		
	}
	public function insrt_neg_keyword($userID) //negative keyword insert function
	{
		$created_on = date("Y-m-d H:i:s");  
		$modfied_on = date("Y-m-d H:i:s");  
		
		//$created_on = date("Y-m-d H:i:s"); 
		//$modfied_on = date("Y-m-d H:i:s"); 
		$get_keywords = $this->input->post('get_keyword');
		
		$get_hidden_id = $this->input->post('hidden_id');
		
		$get_keywords = htmlspecialchars($get_keywords);
		$get_keywords = addslashes(str_replace("'", "&#039;", $get_keywords));
		
		$split_values = explode(",", $get_keywords);
		$cnt = count($split_values);
		
		$this->db->trans_begin();
		
		for($i = 0; $i<$cnt; $i++)
		{
			$insert_query = $this->db->query("CALL insert_negative_keywords('".trim($split_values[$i])."', '".$created_on."', '".$userID."', '".$userID."')");
			
			$success_msg =  'Inserted Successfully';
			$fail_msg = "Problem while inserting. Please try again";
		}
		if($this->db->trans_status() == FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $fail_msg);
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('success', $success_msg);
		}
	}
	
	public function update_neg_keyword($user_id) //update function
	{
		extract($_POST);
		$modfied_on = date("Y-m-d H:i:s");  

		$get_keyword = htmlspecialchars($get_keyword);
		$get_keyword = addslashes(str_replace("'", "&#039;", $get_keyword));
		
		$this->db->trans_begin();
		
		$update_query = $this->db->query("CALL  update_negative_keywords('".trim($get_keyword)."', '".$modfied_on."', '".$user_id."', '".$get_id."')");
		
		$success_msg =  'Updated Successfully';
		$fail_msg = "Problem while updating. Please try again";
				
		if($this->db->trans_status() == FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $fail_msg);
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('success', $success_msg);
		}
	}
}

class negative_datatable extends negative_model
{
	public function pagination_datatable() //function to fetch values in datatable and for serach results
	{
			extract($_POST);
			
			$Field = $order[0]['column'];
			$order = $order[0]['dir'];
	
			switch ($Field)
			{
			case 0:
				$order_field = 'Negativeword';
				break;
			case 1:
				$order_field = 'createdon';
				break;
			default:
			$order_field = 'negativeword_id';
			}
			
			$Menu_id = get_menu_details_by_menu_name('Comments');
			
			
			$Total_rows = $this->db->query('CALL negativeKey_datatable("","")')->num_rows();
			
			$searchtxt = htmlspecialchars(trim($searchtxt));
			$searchtxt = addslashes(str_replace("'", "&#039;", $searchtxt));
			
			$negative_manager =  $this->db->query('CALL negativeKey_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$searchtxt.'")')->result_array();	
	
			
			
			$recordsFiltered =  $this->db->query('CALL negativeKey_datatable("","'.$searchtxt.'")')->num_rows();
			$data['draw'] = $draw;
			$data["recordsTotal"] = $Total_rows;
			$data["recordsFiltered"] = $recordsFiltered ;
			$data['data'] = array();
			$Count = 0;
			
			foreach($negative_manager as $neg_keyword) 
			{
				$subdata = array();
		
				$subdata[] ='<input type="text" class="DataTextBox" name="EditName'.$neg_keyword['negativeword_id'].'" id="EditName'.$neg_keyword['negativeword_id'].'" value="'.$neg_keyword['Negativeword'].'"/><span id="keyword_error"></span>';
				$subdata[] = date("d-m-Y H:i:s",strtotime($neg_keyword['createdon']));
				
				$set_button = "";
				if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
					$set_button .='<a class="button tick"  href="#"  onclick="update_neg_key('.$neg_keyword['negativeword_id'].')" data-toggle="tooltip" title="Save"> <i class="fa fa-file-text-o" ></i> </a>';
				}else { $set_button .= "";				}
				
				if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == 1)				{
					$set_button .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_neg_key('.$neg_keyword['negativeword_id'].')"  id="'.$neg_keyword['negativeword_id'].'"> <i class="fa fa-trash-o"></i> </a>'; 
				} else {$set_button .= "";}
				
		   		$subdata[] = $set_button;
				$data['data'][$Count] = $subdata;
				$Count++;
			}
			if($recordsFiltered == 0) {
			}
			
			echo json_encode($data);
			exit;
	}
}
?>