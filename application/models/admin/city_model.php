<?php 
class city_model extends CI_Model 
{
	function city_datatable()
	{
		$set_object=new datatable;
		return $set_object->city_datatable();	
	}
	function getstatename()
	{
		$set_object=new state;
		return $set_object->getstatename();	
	}
	function addcitydetails($user_id)
	{
		$set_object=new detailscity;
		return $set_object->addcitydetails($user_id);
	}
	function editcitydetails($input_city_id)
	{
		$set_object=new getcitydetails;
		return $set_object->editcitydetails($input_city_id);
	}
	function citydetails_delete($input_city_id)
	{
		$set_object=new getcitydetails;
		return $set_object->citydetails_delete($input_city_id);	
	}
	function city_check()
	{
		$set_object=new check_city;
		return $set_object->city_check();	
	}
}
class detailscity extends city_model
{
	public function addcitydetails($user_id)//fn to list datatable details
	{
			$hdn_city_id=$this->input->post('txthiddenid');
			$stateid=$this->input->post('ddStateName');
			$cityname=trim($this->input->post('txtCityName'));
			date_default_timezone_set('Asia/Calcutta');
			$createdon=date("Y-m-d H:i:s");
			$modifiedon=date("Y-m-d H:i:s");
			if($hdn_city_id=="")
			{
				
				$city_insert_pro = $this->db->query("CALL city_insert('".$cityname."','".$stateid."','".$user_id."','".$createdon."','".$user_id."','".$modifiedon."')"); 
			}
			else
			{
				
				//echo "CALL city_update('".$cityname."','".$stateid."','".$user_id."','".$modifiedon."','".$hdn_city_id."')";exit;
				$city_update_pro = $this->db->query("CALL city_update('".$cityname."','".$stateid."','".$user_id."','".$modifiedon."','".$hdn_city_id."')"); 
			}
	}

}
class datatable extends city_model
{
	
	public function city_datatable()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		
		case 0:
		   $order_field = 't2.StateName';
			break;
		case 1:
		   $order_field = 't1.CityName';
			break;
		case 2:
	   		$order_field = 't3.Username';
			break;
		case 3:
			$order_field = 't1.Createdon';
			break;
   		default:
        $order_field = 'City_Id';
		}
		
		
		$Total_rows = $this->db->query('CALL city_datatable("","","","","'.$filterby.'")')->num_rows();
		
		
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
		
		$city_manager =  $this->db->query('CALL city_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->result_array();	
  
		
		
		$recordsFiltered =  $this->db->query('CALL city_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name('City');
		foreach($city_manager as $city) 
		{
			$subdata = array();
			/*$subdata[] = $rolemaster['User_id'];*/
			$subdata[] = $city['StateName'];
			$subdata[] = $city['CityName'];
			$subdata[] = $city['Username'];
			$subdata[] = date("d-m-Y H:i:s",strtotime($city['Createdon']));
			
			  $set_rights = "";
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().'niecpan/city_manager/edit_details/'.$city['City_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_city('.$city['City_id'].')"  id="'.$city['City_id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
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
class state extends city_model
{
	public function getstatename()
	{
		
		$state_name = $this->db->query("CALL get_statename()"); 
		return $state_name->result_array();
	}
	
}
class getcitydetails extends city_model
{
	public function editcitydetails($input_city_id)
	{
		
		$city_edit= $this->db->query("CALL city_editdetails('".$input_city_id."')");
		return $city_edit->result_array(); 
	}
	public function citydetails_delete($input_city_id)
	{
		
		
		$city_check_pro = $this->db->query("CALL checkcityid('".$input_city_id."')")->num_rows();
		if($city_check_pro > 0) 
		{
			$this->session->set_flashdata('fail_delete', 'Selected city mapped in other tables cannot be deleted');
			redirect(base_url().'niecpan/city_manager');
		}
		else
		{
			$this->remove_city($input_city_id); 
			
		}
	}
	public function remove_city($input_city_id)
	{
		
		$city_delete_pro = $this->db->query("CALL city_delete('".$input_city_id."')"); 
		if($city_delete_pro == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'City details deleted successfully');
			redirect(base_url().'niecpan/city_manager');
		}
	}
	
}
class check_city extends city_model
{
	public function city_check()
	{
		$cityname=$this->input->post('city_name');
		$stateid=$this->input->post('state_id');
		$cityid=$this->input->post('city_id');
		
		$dpt_name = $this->db->query("CALL check_cityname('".$cityname."','".$stateid."','".$cityid."')"); 
		return $dpt_name->num_rows();
	}

}