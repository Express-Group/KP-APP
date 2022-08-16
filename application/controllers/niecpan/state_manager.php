<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class State_Manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/state_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("State");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'State';
			$rawdata['template'] 	= 'state_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/state_manager');
		}
	}
	public function state_datatable()
	{
		$this->state_model->state_datatable();
	}
	public function state_form_view()
	{
		$set_object=new stateform;
		$set_object->state_form_view();
	}
	public function state_details()
	{
		$set_object=new add_statedetails ;
		$set_object->state_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_statedetails;
		$set_object->edit_details();	
	}
	public function state_delete()
	{
		$set_object=new stateform;
		$set_object->state_delete();
	}
	public function check_statename()
	{
		$stateexist= $this->state_model->state_check();
		if($stateexist > 0)
		{
			echo "Statename name already exists";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}

	}
}
class stateform extends State_Manager
{
	public function state_form_view()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('State');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$rawdata['country']=$this->state_model->getcountryname();
			//print_r($rawdata['country']);exit;
			$rawdata['title']		= 'State';
			$rawdata['template'] 	= 'state_form';
			$this->load->view('admin_template',$rawdata);
		} 
		else 
		{
			redirect('niecpan/common/access_permission/add_state');
		}
	}
	public function state_delete()
	{
		$input_state_id = $this->uri->segment(4);
		$this->state_model->statedetails_delete($input_state_id);
	}
}

class add_statedetails extends State_Manager
{
	public function state_details()
	{
		  $hdn_state_id=$this->input->post('txthiddenid');
		  $this->form_validation->set_rules('txtStateName','State Name','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('ddCountryName','Country Name','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('views','status','xss_clean');
		  if($this->form_validation->run() == FALSE)
		  { 
			  $data['title']		= 'State';
			  $data['template'] 	= 'state_form';
			  $this->load->view('admin_template',$data);
		  }	
		  else
		  {
			  //$this->rolemaster_model->addroledetails(); 
			  if($this->state_model->addstatedetails(USERID))
			  {
				  if($hdn_state_id=="")
				  $this->session->set_flashdata('success', 'State details added successfully');
				  else
				  $this->session->set_flashdata('success', 'State details updated successfully');
				  redirect(base_url().'niecpan/state_manager');
			  }
			  else
			  {		
				  $this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				  redirect(base_url().'niecpan/state_manager');
			  }
		  }
	}
}
class edit_statedetails extends State_Manager
{
	public function edit_details()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('State');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1)
		{
			$input_state_id = $this->uri->segment(4);
			$final_array['country']=$this->state_model->getcountryname();
			$final_array['stateeditdetails']= $this->state_model->editstatedetails($input_state_id); 
			//$final_array = array_merge($rawdata,$roleeditdetails,$department);
			$final_array['title']		= 'State master';
			$final_array['template'] 	= 'state_form';
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect('niecpan/common/access_permission/edit_state');
		}
	}
	
}