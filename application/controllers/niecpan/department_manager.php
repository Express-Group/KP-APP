<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Department_Manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/department_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Department");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Department';
			$rawdata['template'] 	= 'department_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/department_manager');
		}
	}
	public function department_form_view()
	{
		$set_object=new departmentform;
		$set_object->department_form_view();
	}

	public function department_details()
	{
		$set_object=new add_departmentdetails ;
		$set_object->department_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_departmentdetails ;
		$set_object->edit_details();	
	}
	public function department_datatable()
	{
		$this->department_model->department_datatable();
	}
	public function department_delete()
	{
		$set_object=new departmentform;
		$set_object->department_delete();
	}
	public function check_departmentname()
	{
		$dptexist= $this->department_model->departmnt_check();
		if($dptexist > 0)
		{
			echo "Department name already exists";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}

	}
	public function status_change()
	{
		$status_check= $this->department_model->status_check();
	}
	
}

class departmentform extends Department_Manager
{
	public function department_form_view()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Department');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Department Manager';
			$rawdata['template'] 	= 'department_form';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/add_department');
		}
	}
	public function department_delete()
	{
		$input_dpt_id = $this->uri->segment(4);
		$dptcheck= $this->department_model->departmentdetails_delete($input_dpt_id);
	}
}


class add_departmentdetails extends Department_Manager
{
	public function department_details()
	{
		  $hdn_dpt_id=$this->input->post('txthiddenid');
		  $rollname=$this->input->post('txtDepartmentName');
		  $status=$this->input->post('views');
		  $this->form_validation->set_rules('txtDepartmentName','Department Name','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('views','status','xss_clean');
		   
		  if($this->form_validation->run() == FALSE)
		  { 
			  $data['title']		= 'Department';
			  $data['template'] 	= 'department_form';
			  $this->load->view('admin_template',$data);
		  }	
		  else
		  {
			  //$this->rolemaster_model->addroledetails(); 
			  if($this->department_model->adddepartmentdetails(USERID))
			  {
				  if($hdn_dpt_id=="")
				  $this->session->set_flashdata('success', 'Department details added successfully');
				  else
				  $this->session->set_flashdata('success', 'Department details updated successfully');
				  redirect(base_url().'niecpan/department_manager');
			  }
			  else
			  {		
				  $this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				  redirect(base_url().'niecpan/department_manager');
				
			  }
		  }
	}
}
class edit_departmentdetails extends Department_Manager
{
	public function edit_details()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Department');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) 
		{
			$input_dpt_id = $this->uri->segment(4);
			$final_array['status']= $this->department_model->status_check($input_dpt_id);
			//print_r($status_check);exit;
			$final_array['dpteditdetails']= $this->department_model->editdepartmentdetails($input_dpt_id); 
			//$final_array = array_merge($rawdata,$roleeditdetails,$department);
			$final_array['title']		= 'Department master';
			$final_array['template'] 	= 'department_form';
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect('niecpan/common/access_permission/edit_department');
		}
	}
	
}

?>