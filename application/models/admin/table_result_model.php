<?php 
class table_result_model extends CI_Model 
{
	
	function add_table_details($user_id)
	{
		$set_object=new add_tableresultdata;
		return $set_object->add_table_details($user_id);
	}
	function edittableresultdetails($input_dpt_id)
	{
		$set_object=new gettableresultdetails;//
		return $set_object->edittableresultdetails($input_dpt_id);
	}
	function table_result_datatable()
	{
		$set_object=new datatable;
		return $set_object->table_result_datatable();	
	}
	function tableresult_delete($input_t_id)
	{
		$set_object=new table_dpt;
		return $set_object->tableresult_delete($input_t_id);	
		
	}
	function table_data_name_check()
	{
		$set_object=new table_dpt;
		return $set_object->table_data_name_check();	
		
	}
	
}
class add_tableresultdata extends table_result_model
{
	public function add_table_details($user_id) //fn to list datatable details
	{
		  $hdn_t_id= $this->input->post('t_id');
		  $table_name = $this->input->post('table_data_name');
		  $table_heading = $this->input->post('table_data_heading');
		  $no_of_column = $this->input->post('noofcolumns');
		  //$no_of_column = $this->input->post('noofcolumns');
		  $column_data = serialize($this->input->post('column_data'));
		  $table_data = serialize($this->input->post('table_data'));
			
			date_default_timezone_set('Asia/Calcutta');
			$createdon=date("Y-m-d H:i:s");
			$modifiedon=date("Y-m-d H:i:s");
			if($hdn_t_id=="")
			{
				$this->db->trans_begin();
				$column_insert_pro = $this->db->query("CALL insert_table_data('".$table_name."','".$table_heading."','".$column_data."','".$table_data."', '".$user_id."','".$user_id."','".$createdon."')"); 
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					 return "add_error";
				}
				else
				{
					$this->db->trans_commit();
				 return "add_success";

				}
			}
			else
			{
				$this->db->trans_begin();
				$column_update_pro = $this->db->query("CALL update_table_data('".$table_name."','".$table_heading."','".$column_data."','".$table_data."','".$user_id."','".$modifiedon."','".$hdn_t_id."')"); 
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					  return "update_error";
				}
				else
				{
					$this->db->trans_commit();
                  return "update_success";
				}
			}
	}

}
class gettableresultdetails extends table_result_model
{
	public function edittableresultdetails($input_t_id)
	{
		$table_data_edit= $this->db->query("CALL edit_table_result_details('".$input_t_id."')");
		return $table_data_edit->result_array(); 
	}
	
}

class datatable extends table_result_model
{
	
	public function table_result_datatable()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		
		case 0:
		   $order_field = 't1.table_data_name';
			break;
		case 1:
		   $order_field = 't1.Createdby';
			break;
		default:
        $order_field = 't1.id';
		}
		
		
		$Total_rows = $this->db->query('CALL table_result_datatable("","","","","'.$filterby.'")')->num_rows();
		
		
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$table_result_manager =  $this->db->query('CALL table_result_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->result_array();	

		
		
		$recordsFiltered =  $this->db->query('CALL table_result_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Table Result');
		foreach($table_result_manager as $table_result_data) 
		{
			$subdata = array();
			$subdata[] = $table_result_data['table_data_name'];
			$subdata[] = $table_result_data['Headings']; 
			$subdata[] = $table_result_data['Username'];
			$subdata[] = date("d-m-Y H:i:s",strtotime($table_result_data['Createdon']));
			
			
			  
			  $set_rights = "";
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().'niecpan/table_result_manager/edit_details/'.$table_result_data['id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_topic('.$table_result_data['id'].')"  id="'.$table_result_data['id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
			  }
			  else 
			  { 
			  	$set_rights.="";
			  }
   			 $subdata[] = $set_rights;
			  
	   
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
				if($recordsFiltered == 0) {

				}
		
		echo json_encode($data);
		exit;

	}
	
}
class table_dpt extends table_result_model
{
	public function table_data_name_check()
	{
		$t_dataname = $this->input->post('table_data_name');
		$tid = $this->input->post('t_id');
		
		$table_data_name = $this->db->query("CALL check_tabledataname('".$t_dataname."','".$tid."')"); 
		return $table_data_name->num_rows();
	}
	
		public function tableresult_delete($input_t_id)
	{
		$table_delete = $this->db->query("CALL delete_tabledata('".$input_t_id."')"); 
		if($table_delete == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'Table deleted successfully');
			redirect(base_url().'niecpan/table_result_manager');
		}
		else
		{
			$this->session->set_flashdata('fail_delete', 'Selected Table result cannot be deleted');
			redirect(base_url().'niecpan/table_result_manager');
		}
		
	}
	
}