<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rolemaster_manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/rolemaster_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Role");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Role Manager';
			$rawdata['template'] 	= 'rolemaster_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/rolemaster_manager');
		}
	}
	public function role_form_view()
	{
		$set_object=new roleform;
		$set_object->role_form_view();
	}
	public function role_details()
	{
		$set_object=new add_roledetails ;
		$set_object->role_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_roledetails ;
		$set_object->edit_details();	
	}
	public function roledepartment_datatable()
	{
		$set_object=new datatables;
		$set_object->roledepartment_datatable();
	}
	public function rolemaster_delete()
	{
		$set_object=new roleform;
		$set_object->rolemaster_delete();
	}
	public function check_rolename()
	{
		$set_object=new roleform;
		$set_object->check_rolename();
	}
	
}
class roleform extends Rolemaster_manager
{
	public function role_form_view()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Role');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			//$rawdata['department']=$this->rolemaster_model->get_departmentname();
			$rawdata['rawdata'] = $this->rolemaster_model->datatable_menumaster();
			$rawdata['title']		= 'Create Role';
			$rawdata['template'] 	= 'rolemaster_form';
			$this->load->view('admin_template',$rawdata);
		} 
		else 
		{
			redirect(folder_name.'/common/access_permission/add_role');
		}
	}
	public function rolemaster_delete()
	{
		$input_role_id = $this->uri->segment(4);
		$this->rolemaster_model->rolldetails_delete($input_role_id);
	}
	public function check_rolename()
	{
		$rolenameexist = $this->rolemaster_model->rolename_check();
		//echo $rolenameexist;
		if($rolenameexist > 0)
		{
			echo "Role name already exist for this department";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}
		
	}
}

class add_roledetails extends Rolemaster_manager
{
	public function role_details()
	{
		  $hdn_role_id=$this->input->post('txthiddenid');
		  $rollname=$this->input->post('txtRoleName');
		  $department=$this->input->post('ddDepartment');
		  $status=$this->input->post('viewss');
		  $this->form_validation->set_rules('txtRoleName','Roll Name','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('ddDepartment','Department','xss_clean');
		  $this->form_validation->set_rules('views','status','xss_clean');
		   
		  if($this->form_validation->run() == FALSE)
		  { 
			  $data['title']		= 'Role';
			  $data['template'] 	= 'rolemaster_form';
			  $this->load->view('admin_template',$data);
		  }	
		  else
		  {
			  //$this->rolemaster_model->addroledetails(); 
			  if($this->rolemaster_model->addroledetails(USERID))
			  {
				  if($hdn_role_id=="")
				  $this->session->set_flashdata('success', 'Role details added successfully');
				  else
				  $this->session->set_flashdata('success', 'Role details updated successfully');
				  redirect(base_url().folder_name.'/rolemaster_manager');
			  }
			  else
			  {		
				  $this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				  redirect(base_url().folder_name.'/rolemaster_form');
				
			  }
		  }
	}
}
class edit_roledetails extends Rolemaster_manager
{
	public function edit_details()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Role');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1)
		{
			$input_role_id = $this->uri->segment(4);
			//$department['department']=$this->rolemaster_model->get_departmentname();
			$rawdata['rawdata'] = $this->rolemaster_model->datatable_menumaster();
			$roleeditdetails['roleeditdetails']= $this->rolemaster_model->editroledetails($input_role_id); 
			$status['status'] = $this->rolemaster_model->status_check($input_role_id);
			$final_array = array_merge($rawdata,$roleeditdetails,$status);
			$final_array['title']		= 'Edit Role';
			$final_array['template'] 	= 'rolemaster_form';
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/edit_role');
		}
	}
	
}
class datatables extends Rolemaster_manager
{
	public function roledepartment_datatable()
	{
		$this->rolemaster_model->datatable_roledepartment();
	}
	
}

?>