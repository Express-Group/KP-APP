<?php 
class newsagency_model extends CI_Model 
{
	function addnewsagencydetails($userid)
	{
		$set_object=new detailsdpt;
		return $set_object->addnewsagencydetails($userid);
	}
	function editnewsagencydetails($input_dpt_id)
	{
		$set_object=new getnewsagencydetails;
		return $set_object->editnewsagencydetails($input_dpt_id);
	}
	function newsagency_datatable()
	{
		$set_object=new datatable;
		return $set_object->newsagency_datatable();	
	}
	function newsagencydetails_delete($input_dpt_id)
	{
		$set_object=new getnewsagencydetails;
		return $set_object->newsagencydetails_delete($input_dpt_id);	
	}
	public function agency_check()
	{
		$set_object=new newsagencycheck;
		return $set_object->agency_check();	
	}
	/*public function status_check($input_dpt_id)
	{
		$set_object=new newsagencycheck;
		return $set_object->status_check($input_dpt_id);	
	}*/
}
class detailsdpt extends newsagency_model
{
	public function addnewsagencydetails($userid)//fn to list datatable details
	{
			$hdn_dpt_id=$this->input->post('txthiddenid');
			$newsagencyname=$this->input->post('txtAgecncyName');
			
			$newsagencyname= htmlspecialchars($this->input->post('txtAgecncyName'));
			$newsagencyname = addslashes(str_replace("'", "&#039;", $newsagencyname));
			
			$status=$this->input->post('views');
			$createdby="1";
			$modifiedby="1";
			date_default_timezone_set('Asia/Calcutta');
			$createdon=date("Y-m-d:H:i:s");
			$modifiedon=date("Y-m-d:H:i:s");
			if($hdn_dpt_id=="")
			{
				$this->db->trans_begin();
				$newsagency_insert_pro = $this->db->query("CALL newsagency_insert('".$newsagencyname."','".$status."','".$userid."','".$createdon."')"); 
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}
			}
			else
			{
				$this->db->trans_begin();
				$newsagency_update_pro = $this->db->query("CALL newsagency_update('".$newsagencyname."','".$status."','".$userid."','".$modifiedon."','".$hdn_dpt_id."')"); 
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}
			}
	}

}
class getnewsagencydetails extends newsagency_model
{
	public function editnewsagencydetails($input_dpt_id)
	{
		$section_edit= $this->db->query("CALL newsagency_editdetails('".$input_dpt_id."')");
		return $section_edit->result_array(); 
	}
	public function newsagencydetails_delete($input_dpt_id)
	{
		$dpt_check_pro = $this->db->query("CALL checkagencyid('".$input_dpt_id."')")->num_rows();
		if($dpt_check_pro > 0) 
		{
			$this->session->set_flashdata('fail_delete', 'Newsagency details mapped in other tables cannot be deleted');
			redirect(base_url().'niecpan/newsagency_manager');
		}
		else
		{
			$this->remove_newsagency($input_dpt_id); 
			
		}
	}
	public function remove_newsagency($input_dpt_id)
	{
		$dpt_delete_pro = $this->db->query("CALL newsagency_delete('".$input_dpt_id."')"); 
		if($dpt_delete_pro == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'Newsagency details deleted successfully');
			redirect(base_url().'niecpan/newsagency_manager');
		}
	}
	
}

class datatable extends newsagency_model
{
	
	public function newsagency_datatable()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		
		case 0:
		   $order_field = 't1.Agency_name';
			break;
		case 1:
		   $order_field = 't2.Username';
			break;
		case 2:
		   $order_field = 't1.Createdon';
			break;
		case 3:
	   		$order_field = 't1.Status';
			break;
   		default:
        $order_field = 't1.Agency_id';
		}
		
		$Total_rows = $this->db->query('CALL newsagency_datatable("","","","","'.$filterby.'","")')->num_rows();
		
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$searchtxt= htmlspecialchars(trim($searchtxt));
		$searchtxt = addslashes(str_replace("'", "&#039;", $searchtxt));
			
		$newsagency_manager =  $this->db->query('CALL newsagency_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->result_array();	

		
		$recordsFiltered =  $this->db->query('CALL newsagency_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		
		foreach($newsagency_manager as $newsagency) 
		{
			$subdata = array();
			/*$subdata[] = $rolemaster['User_id'];*/
			$subdata[] = $newsagency['Agency_name'];
			$subdata[] = $newsagency['Username'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($newsagency['Createdon']));
			if($newsagency['Status']=="1")
			$subdata[] = '<td><i title="Active" class="fa fa-check"></i></td>';
			else
			$subdata[] = '<td><i title="Inactive"  class="fa fa-times"></i></td>';
			$subdata[] ='<a class="button tick" href="'.base_url().'niecpan/newsagency_manager/edit_details/'.$newsagency['Agency_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>
			  <a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_newsagency('.$newsagency['Agency_id'].')"  id="'.$newsagency['Agency_id'].'"> <i class="fa fa-trash-o"></i> </a>'; 
	   
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
				if($recordsFiltered == 0) {

				}
		
		echo json_encode($data);
		exit;

	}
	
}
class newsagencycheck extends newsagency_model
{
	public function agency_check()
	{
		$agency=$this->input->post('agencynme');
		
		$agency= htmlspecialchars($this->input->post('agencynme'));
		$agency = addslashes(str_replace("'", "&#039;", $agency));
			
		$agencyid=$this->input->post('agency_id');
		$agency_name = $this->db->query("CALL check_newsagencyname('".trim($agency)."','".$agencyid."')"); 
		return $agency_name->num_rows();
	}
	/*public function status_check($input_dpt_id)
	{
		$this->db->reconnect();
		$status= $this->db->query("CALL checkagencyid('".$input_dpt_id."')");
		return $status->num_rows();	
	}*/
}