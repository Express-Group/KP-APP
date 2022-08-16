<?php 
class rolemaster_model extends CI_Model 
{
	function datatable_menumaster()
	{
		$datatables=new datatable_role;
		return $datatables->datatable_menumaster();	  
	}
	function addroledetails($userid)
	{
		$set_object=new detailsrole;
		return $set_object->addroledetails($userid);
	}
	function editroledetails($input_role_id)
	{
		$set_object=new getrole_details ;
		return $set_object->editroledetails($input_role_id);
	}
	function datatable_roledepartment()
	{
		$set_object=new roledeparment;
		return $set_object->datatable_roledepartment();	
	}
	function rolldetails_delete($input_role_id)
	{
		$set_object=new getrole_details;
		return $set_object->rolldetails_delete($input_role_id);	
	}
	/*function get_departmentname()
	{
		$set_object=new department_name;
		return $set_object->get_departmentname();	
	}*/
	function rolename_check()
	{
		$set_object=new rolecheck;
		return $set_object->rolename_check();
	}
	function status_check($input_role_id)
	{
		$set_object=new rolecheck;
		return $set_object->status_check($input_role_id);	
	}
}

class datatable_role extends rolemaster_model
{
	public function datatable_menumaster()//fn to list datatable details
	{
		$this->db->reconnect();
		$menu_select = $this->db->query("CALL menu_display()");
		return $menu_select->result_array();
	}  
}
class rolecheck extends rolemaster_model
{
	public function rolename_check()
	{
		//$rolename= $this->input->post('role_name');
		
		$rolename= htmlspecialchars($this->input->post('role_name'));
		$rolename = addslashes(str_replace("'", "&#039;", $rolename));
			
		$roleid=$this->input->post('rollid');
		$this->db->reconnect();
		$section_name = $this->db->query("CALL check_rolename('".trim($rolename)."', '".$roleid."')");
		return $section_name->num_rows();
	}
	public function status_check($input_role_id)
	{
		$this->db->reconnect();
		$status= $this->db->query("CALL checkroleid_inuser('".$input_role_id."')");
		return $status->num_rows();	
		
	}
	
	
}
class detailsrole extends rolemaster_model
{
	
	public function addroledetails($userid)//fn to list datatable details
	{
			$hdn_role_id=$this->input->post('txthiddenid');
			//$rollname=$this->input->post('txtRoleName');
			
			$rollname= htmlspecialchars($this->input->post('txtRoleName'));
			$rollname = addslashes(str_replace("'", "&#039;", $rollname));
			
			//$department=$this->input->post('ddDepartment');
			$status=$this->input->post('views');
			$create_delete=$this->input->post('chkPageDesign');
			$add_article=$this->input->post('chkAddArticle');
			$add_advertisement=$this->input->post('chkAdvertisement');
			//$addconfiguration=$this->input->post('chkConfiguration');
			//$addpublish=$this->input->post('chkPublish');
			
			$addconfiguration=$create_delete;
			$addpublish=$create_delete;
			
			$addreleaselock=$this->input->post('chkreleasetemplate');
			
			$menu_id=$this->input->post('foo');
			$rights=$this->input->post('rights');
			$array_keys = array_keys($rights);
			$array_values = array_values($rights);

			
			$createdby="1";
			$modifiedby="1";
			date_default_timezone_set('Asia/Calcutta');
			$createdon=date("Y-m-d:H:i:s");
			$modifiedon=date("Y-m-d:H:i:s");
			if($hdn_role_id=="")
			{
				
				$this->db->trans_begin();
				
				$role_insert_pro = $this->db->query("CALL role_insert('".$rollname."',".$status.",".$userid.",'".$createdon."', @insert_id)"); 
				if($role_insert_pro == TRUE)
				{
					$slct_lst_id=$this->db->query("SELECT @insert_id");	
					$last_id = $slct_lst_id->row_array();
						$role_id = $last_id['@insert_id'];
						
						$fpm_insert_pro = $this->db->query("CALL fpmrolerights_insert('".$role_id."','".$create_delete."','".$add_article."','".$add_advertisement."','".$userid."','".$createdon."','".$addconfiguration."','".$addpublish."', '".$addreleaselock."')");
						
						
						foreach($rights as $menuid => $values)
						{
							$this->db->query("CALL  roleaccess_rights_insert('".$role_id."', '".$menuid."','".@$values['view']."','".@$values['add']."','".@$values['edit']."','".@$values['delete']."','".@$values['publish']."','".@$values['unpublish']."', '".$userid."','".$createdon."','".$userid."','".$modifiedon."')");
							
						}	
					
					}
					
					if ($this->db->trans_status() === FALSE)
						$this->db->trans_rollback();
					else
						$this->db->trans_commit();
					
			return true;
			}
			else
			{
				$this->db->trans_begin();
				
				$role_insert_pro = $this->db->query("CALL role_update('".$rollname."','".$status."',".$userid.",'".$modifiedon."', '".$hdn_role_id."')"); 
				if($role_insert_pro == TRUE)
				{
					
					$fpm_update_pro = $this->db->query("CALL fpmrolerights_update('".$hdn_role_id."','".$create_delete."','".$add_article."','".$add_advertisement."','".$userid."','".$modifiedon."','".$addconfiguration."','".$addpublish."', '".$addreleaselock."')");
					$this->db->query("CALL  roleaccess_rights_delete('".$hdn_role_id."')");
					foreach($rights as $menuid => $values)
					{
						$this->db->query("CALL  roleaccess_rights_insert('".$hdn_role_id."', '".$menuid."','".@$values['view']."','".@$values['add']."','".@$values['edit']."','".@$values['delete']."','".@$values['publish']."','".@$values['unpublish']."', '".$userid."','".$createdon."','".$userid."','".$modifiedon."')");
					}	
				}
				
					if ($this->db->trans_status() === FALSE)
						$this->db->trans_rollback();
					else
						$this->db->trans_commit();
				
			return true;	
			}

	}  
	
	
}
class getrole_details extends rolemaster_model
{
	public function editroledetails($input_role_id)
	{
		
		$section_edit= $this->db->query("CALL role_editdetails('".$input_role_id."')");
		return $section_edit->result_array(); 
	}
	public function rolldetails_delete($input_role_id)
	{
		
		
		$role_check_pro = $this->db->query("CALL checkroleid_inuser('".$input_role_id."')")->num_rows();
		if($role_check_pro > 0) 
		{
			$this->session->set_flashdata('fail_delete', 'Role details mapped in usermaster cannot be deleted');
			redirect(base_url().folder_name.'/rolemaster_manager');
			
		}
		else
		{
			$this->remove_role($input_role_id); 
			
		}
	}
	public function remove_role($input_role_id)
	{
		
		$role_delete_pro = $this->db->query("CALL role_delete('".$input_role_id."')"); 
		if($role_delete_pro == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'Role details deleted successfully');
			redirect(base_url().folder_name.'/rolemaster_manager');
		}
	}
	
}
class roledeparment extends rolemaster_model
{
	
	public function datatable_roledepartment()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		/*case 0:
			$order_field = 't2.User_id';
			break;
		case 2:
			$order_field = 't3.departmentname';
			break;*/
		case 0:
		   $order_field = 't1.rolename';
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
        $order_field = 't1.role_id';
		}
		
		
		$Total_rows = $this->db->query('CALL rolemaster_datatable("","","","","'.$filterby.'",2)')->num_rows();
		
		
		
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
			
		$rolemaster_manager =  $this->db->query('CALL rolemaster_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->result_array();	

		
		
		$recordsFiltered =  $this->db->query('CALL rolemaster_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name("Role");

		
		foreach($rolemaster_manager as $rolemaster) 
		{
			$subdata = array();
			/*$subdata[] = $rolemaster['User_id'];*/
			$subdata[] = $rolemaster['rolename'];
			$subdata[] = $rolemaster['Username'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($rolemaster['Createdon']));
			if($rolemaster['Status']==1)
			$subdata[] = '<td><i title="Active" class="fa fa-check"></i></td>';
			else
			$subdata[] = '<td><i title="Inactive"  class="fa fa-times"></i></td>';
		
			   $set_rights = "";
			  
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().folder_name.'/rolemaster_manager/edit_details/'.$rolemaster['role_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_rolemaster('.$rolemaster['role_id'].')"  id="'.$rolemaster['role_id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
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
/*class department_name extends rolemaster_model
{
	public function get_departmentname()
	{
		
		$dpt_name = $this->db->query("CALL get_departmentname()"); 
		return $dpt_name->result_array();
		
	}
	
}*/

?>