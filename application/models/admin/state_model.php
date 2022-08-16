<?php 
class state_model extends CI_Model 
{
	function state_datatable()
	{
		$set_object=new datatable;
		return $set_object->state_datatable();	
	}
	function getcountryname()
	{
		$set_object=new country;
		return $set_object->getcountryname();	
	}
	function addstatedetails($user_id)
	{
		$set_object=new detailsstate;
		return $set_object->addstatedetails($user_id);
	}
	function editstatedetails($input_state_id)
	{
		$set_object=new getstatedetails;
		return $set_object->editstatedetails($input_state_id);
	}
	function statedetails_delete($input_state_id)
	{
		$set_object=new getstatedetails;
		return $set_object->statedetails_delete($input_state_id);	
	}
	function state_check()
	{
		$set_object=new check_state;
		return $set_object->state_check();	
	}
}
class detailsstate extends state_model
{
	public function addstatedetails($user_id)//fn to list datatable details
	{
			$hdn_state_id=$this->input->post('txthiddenid');
			$countryid=$this->input->post('ddCountryName');
			$statename=$this->input->post('txtStateName');
			date_default_timezone_set('Asia/Calcutta');
			$createdon=date("Y-m-d H:i:s");
			$modifiedon=date("Y-m-d H:i:s");
			if($hdn_state_id=="")
			{
				
				$state_insert_pro = $this->db->query("CALL state_insert('".$statename."','".$countryid."','".$user_id."','".$createdon."','".$user_id."','".$modifiedon."')"); 
			}
			else
			{
				
				//echo "CALL state_update('".$statename."','".$countryid."','".$user_id."','".$modifiedon."','".$hdn_state_id."')";exit;
				$state_update_pro = $this->db->query("CALL state_update('".$statename."','".$countryid."','".$user_id."','".$modifiedon."','".$hdn_state_id."')"); 
			}
	}

}
class datatable extends state_model
{
	
	public function state_datatable()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		
		case 0:
		   $order_field = 't2.CountryName';
			break;
		case 1:
		   $order_field = 't1.StateName';
			break;
		case 2:
	   		$order_field = 't3.Username';
			break;
		case 3:
			$order_field = 't1.Createdon';
			break;
   		default:
        $order_field = 'State_Id';
		}
		
		
		$Total_rows = $this->db->query('CALL state_datatable("","","","","'.$filterby.'")')->num_rows();
		
		
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$searchtxt= htmlspecialchars(trim($searchtxt));
		$searchtxt = addslashes(str_replace("'", "''", $searchtxt));
		
		$state_manager =  $this->db->query('CALL state_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->result_array();	

		
		
		$recordsFiltered =  $this->db->query('CALL state_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name('State');
		foreach($state_manager as $state) 
		{
			$subdata = array();
			/*$subdata[] = $rolemaster['User_id'];*/
			$subdata[] = $state['CountryName'];
			$subdata[] = $state['StateName'];
			$subdata[] = $state['Username'];
			$subdata[] = date("d-m-Y H:i:s",strtotime($state['Createdon']));
			
			  $set_rights = "";
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().'niecpan/state_manager/edit_details/'.$state['State_Id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_state('.$state['State_Id'].')"  id="'.$state['State_Id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
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
class country extends state_model
{
	public function getcountryname()
	{
		
		$country_name = $this->db->query("CALL get_country()"); 
		return $country_name->result_array();
	}
	
}
class getstatedetails extends state_model
{
	public function editstatedetails($input_state_id)
	{
		
		$state_edit= $this->db->query("CALL state_editdetails('".$input_state_id."')");
		return $state_edit->result_array(); 
	}
	public function statedetails_delete($input_state_id)
	{
		
		
		$state_check_pro = $this->db->query("CALL checkstateid('".$input_state_id."')")->num_rows();
		if($state_check_pro > 0) 
		{
			$this->session->set_flashdata('fail_delete', 'Selected state mapped in other tables cannot be deleted');
			redirect(base_url().'niecpan/state_manager');
		}
		else
		{
			$this->remove_state($input_state_id); 
			
		}
	}
	public function remove_state($input_state_id)
	{
		
		$state_delete_pro = $this->db->query("CALL state_delete('".$input_state_id."')"); 
		if($state_delete_pro == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'State details deleted successfully');
			redirect(base_url().'niecpan/state_manager');
		}
	}
	
}
class check_state extends state_model
{
	public function state_check()
	{
		$statename=$this->input->post('state_name');
		$countryid=$this->input->post('country_id');
		$stateid=$this->input->post('state_id');
		
		$dpt_name = $this->db->query("CALL check_statename('".$statename."','".$countryid."','".$stateid."')"); 
		return $dpt_name->num_rows();
	}

}