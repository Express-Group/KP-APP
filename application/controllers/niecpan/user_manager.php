<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_Manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/user_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("User");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'User Manager';
			$rawdata['template'] 	= 'user_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else
		{
			redirect(folder_name.'/common/access_permission/askprabhu_manager');	
		}
	
	}
	public function user_form_view()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('User');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1)
		{
			$rawdata['rawdata'] = $this->user_model->datatable_menumaster();
			$rawdata['rolename']= $this->user_model->get_rolename();
			$rawdata['user_id'] 	= '';
			$rawdata['title']		= 'Create user';
			$rawdata['template'] 	= 'user_form';
			$this->load->view('admin_template',$rawdata);
		} 
		else 
		{
			redirect(folder_name.'/common/access_permission/add_user');
		}
	}
	public function rolenames()
	{
		$roles= $this->user_model->get_rolename();
		echo json_encode($roles);
		
	}
	public function user_details()
	{
		$set_object=new add_userdetails ;
		$set_object->user_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_userdetails ;
		$set_object->edit_details();	
	}
	public function getuser_details()
	{
		$set_object=new edit_userdetails ;
		$set_object->getuser_details();	
	}
	public function check_emailID()
	{
		$set_object=new checkmail;
		$set_object->check_emailID();
		
	}
	function user_datatable()
	{
		$this->user_model->datatable_user();
		
	}
	function delete_user()
	{
		$set_object=new delete;
		$set_object->delete_user();
		
	}
	
}
class add_userdetails extends User_Manager
{
	public function user_details()
	{
		$hdn_user_id=$this->input->post('txthiddenid');
		
		$this->form_validation->set_rules('txtUserName','User Name','trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('txtPassword','Password','trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('ddStatus','Status','trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('txtFirstName','First Name','trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('txtLastName','Last Name','trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('txtEmployeeCode','Employee code','trim|strip_tags|xss_clean');
		//$this->form_validation->set_rules('txtMobileNo','Mobile No','trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('txtContactNo','Contact No','trim|strip_tags|xss_clean');
		//$this->form_validation->set_rules('txtEmailID','Email Id','trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('txtAltEmailID','Alt Emailid','trim|strip_tags|xss_clean');
		
		//$this->form_validation->set_rules('txtNameRM','Name','trim|strip_tags|xss_clean');
		//$this->form_validation->set_rules('txtJobTitleRM','Jobtitle','trim|strip_tags|xss_clean');
		//$this->form_validation->set_rules('txtDptRM','Department','trim|strip_tags|xss_clean');
		//$this->form_validation->set_rules('txtMobileNoRM','Mobile Number','trim|strip_tags|xss_clean');
		//$this->form_validation->set_rules('txtContactNoRM','Contact Number','trim|strip_tags|xss_clean');
		//$this->form_validation->set_rules('txtEmailIDRM','Email ID','trim|strip_tags|xss_clean');
		   
		if($this->form_validation->run() == FALSE)
		{ 
			$data['title']		= 'Create User';
			$data['template'] 	= 'user_form';
			$this->load->view('admin_template',$data);
		}	
		else
		{
			 if($this->user_model->adduserdetails(USERID))
			  {
				  if($hdn_user_id=="")
				  $this->session->set_flashdata('success', 'User details added successfully');
				  else
				  $this->session->set_flashdata('success', 'User details updated successfully');
				  redirect(base_url().folder_name.'/user_manager');
			  }
			  else
			  {		
				  $this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				  redirect(base_url().folder_name.'/user_manager');
			  }
		}
		
	}
	
	
	
}

class edit_userdetails extends User_Manager
{
	public function edit_details()
	{
		$roledetails= $this->user_model->roledetails(); 
		echo json_encode($roledetails);
		
	}
	public function getuser_details()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('User');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1)
		{
			$input_role_id = $this->uri->segment(4);
			$rawdata['rawdata'] = $this->user_model->datatable_menumaster();
			$rolenames['rolenames']= $this->user_model->get_rolename();
			$editdetails['editdetails']= $this->user_model->edituserdetails($input_role_id);
			$rolename['rolename'] =  $this->user_model->get_rolename();
			$final_array = array_merge($rawdata,$editdetails,$rolename);
			
			$final_array['user_id'] 	= $input_role_id;
			$final_array['title']		= 'Edit user';
			$final_array['template'] 	= 'user_form';
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/edit_user');
		}
		
	}
	
}
class checkmail extends User_Manager
{
	public function check_emailID()
	{
		$emailid= $this->user_model->check_useremailID();
		$user_id = $this->input->post('userid');
		$emailid_exist="";
		$user_exist="";
		
		$emailid_exist= $this->user_model->check_useremailID();
		$user_exist= $this->user_model->check_username();
		
		if($user_exist > 0)
		{
			echo "Username already exist";
			return FALSE;
		}
		else if($emailid_exist > 0)
		{
			echo "Email ID already exist";
			return FALSE;
		}
		else
		{
			return TRUE;
		}
		
	}
	
	
}
class delete extends User_Manager
{
	
	public function delete_user()
	{
		$input_role_id = $this->uri->segment(4);
		$this->user_model->user_delete($input_role_id);
	}
	
	
}