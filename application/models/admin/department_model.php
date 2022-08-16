<?php 
class department_model extends CI_Model 
{
	
	function adddepartmentdetails($user_id)
	{
		$set_object=new detailsdpt;
		return $set_object->adddepartmentdetails($user_id);
	}
	function editdepartmentdetails($input_dpt_id)
	{
		$set_object=new getdepartmentdetails;
		return $set_object->editdepartmentdetails($input_dpt_id);
	}
	function department_datatable()
	{
		$set_object=new datatable;
		return $set_object->department_datatable();	
	}
	function departmentdetails_delete($input_dpt_id)
	{
		$set_object=new getdepartmentdetails;
		return $set_object->departmentdetails_delete($input_dpt_id);	
		
	}
	function departmnt_check()
	{
		$set_object=new check_dpt;
		return $set_object->departmnt_check();	
	}
	function status_check($input_dpt_id)
	{
		$set_object=new check_dpt;
		return $set_object->status_check($input_dpt_id);	
		
	}
	
}
class detailsdpt extends department_model
{
	public function adddepartmentdetails($user_id)//fn to list datatable details
	{
			$hdn_dpt_id=$this->input->post('txthiddenid');
			$departmentname=$this->input->post('txtDepartmentName');
			$status=$this->input->post('views');
			$createdby="1";
			$modifiedby="1";
			date_default_timezone_set('Asia/Calcutta');
			$createdon=date("Y-m-d:H:i:s");
			$modifiedon=date("Y-m-d:H:i:s");
			
			if($hdn_dpt_id=="")
			{
				$this->db->trans_begin();
				$department_insert_pro = $this->db->query("CALL department_insert('".$departmentname."','".$status."','".$user_id."','".$createdon."')"); 
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('success_delete', 'Department details inserted successfully');
					redirect(base_url().'niecpan/department_manager');
				}
			}
			else
			{
				$this->db->trans_begin();
				$department_update_pro = $this->db->query("CALL department_update('".$departmentname."','".$status."','".$user_id."','".$modifiedon."','".$hdn_dpt_id."')"); 
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('success_delete', 'Department details updated successfully');
					redirect(base_url().'niecpan/department_manager');
				}
			}
	}

}
class getdepartmentdetails extends department_model
{
	public function editdepartmentdetails($input_dpt_id)
	{
		
		$section_edit= $this->db->query("CALL department_editdetails('".$input_dpt_id."')");
		return $section_edit->result_array(); 
	}
	public function departmentdetails_delete($input_dpt_id)
	{
		
		$dpt_check_pro = $this->db->query("CALL checkdeptid('".$input_dpt_id."')")->num_rows();
		if($dpt_check_pro > 0) 
		{
			$this->session->set_flashdata('fail_delete', 'Department details mapped in other tables cannot be deleted');
			redirect(base_url().'niecpan/department_manager');
		}
		else
		{
			$this->remove_department($input_dpt_id); 
			
		}
	}
	public function remove_department($input_dpt_id)
	{
		
		$dpt_delete_pro = $this->db->query("CALL department_delete('".$input_dpt_id."')"); 
		if($dpt_delete_pro == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'Department details deleted successfully');
			redirect(base_url().'niecpan/department_manager');
		}
	}
	
}

class datatable extends department_model
{
	
	public function department_datatable()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		
		case 0:
		   $order_field = 'departmentname';
			break;
		case 1:
		   $order_field = 'Createdon';
			break;
		case 2:
	   		$order_field = 'Status';
			break;
   		default:
        $order_field = 'department_id';
		}
		
		
		$Total_rows = $this->db->query('CALL department_datatable("","","","","'.$filterby.'","")')->num_rows();
		
		
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$department_manager =  $this->db->query('CALL department_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->result_array();	

		
		
		$recordsFiltered =  $this->db->query('CALL department_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Department');
		foreach($department_manager as $department) 
		{
			$subdata = array();
			/*$subdata[] = $rolemaster['User_id'];*/
			$subdata[] = $department['departmentname'];
			$subdata[] = $department['Username'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($department['Createdon']));
			if($department['Status']=="1")
			$subdata[] = '<td><a data-toggle="tooltip" title="Active" href="javascript:void()"><i class="fa fa-check"></i></a></td>';
			else
			$subdata[] = '<td><a data-toggle="tooltip" title="Inactive" href="javascript:void()"><i class="fa fa-times"></i></a></td>';
			/*$subdata[] ='<a class="button tick" href="'.base_url().'admin/department_manager/edit_details/'.$department['department_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>
			  <a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_department('.$department['department_id'].')"  id="'.$department['department_id'].'"> <i class="fa fa-trash-o"></i> </a>'; */
			  
			  $set_rights = "";
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().'niecpan/department_manager/edit_details/'.$department['department_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_department('.$department['department_id'].')"  id="'.$department['department_id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
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
class check_dpt extends department_model
{
	public function departmnt_check()
	{
		$department=$this->input->post('dpt_name');
		$dptid=$this->input->post('dept_id');
		
		$dpt_name = $this->db->query("CALL check_departmentname('".$department."','".$dptid."')"); 
		return $dpt_name->num_rows();
	}
	public function status_check($input_dpt_id)
	{
		
		$status= $this->db->query("CALL checkdeptid('".$input_dpt_id."')");
		return $status->num_rows();
	}
}