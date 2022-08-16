<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Newsagency_Manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/newsagency_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$rawdata['title']		= 'News Agency';
		$rawdata['template'] 	= 'newsagency_manager';
		$this->load->view('admin_template',$rawdata);
	}
	public function newsagency_form_view()
	{
		$set_object=new newsagencyform;
		$set_object->newsagency_form_view();
	}
	public function newsagency_details()
	{
		$set_object=new add_newsagencydetails ;
		$set_object->newsagency_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_newsagencydetails ;
		$set_object->edit_details();	
	}
	public function newsagency_datatable()
	{
		$this->newsagency_model->newsagency_datatable();
	}
	public function newsagency_delete()
	{
		$set_object=new newsagencyform;
		$set_object->newsagency_delete();
	}
	public function check_agencyname()
	{
		$agencyexist= $this->newsagency_model->agency_check();
		if($agencyexist > 0)
		{
			echo "Newsagency name already exists";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}

	}
	
}

class newsagencyform extends newsagency_Manager
{
	public function newsagency_form_view()
	{
		$rawdata['title']		= 'newsagency Manager';
		$rawdata['template'] 	= 'newsagency_form';
		$this->load->view('admin_template',$rawdata);
	}
	public function newsagency_delete()
	{
		$input_dpt_id = $this->uri->segment(4);
		$this->newsagency_model->newsagencydetails_delete($input_dpt_id);
	}
}


class add_newsagencydetails extends newsagency_Manager
{
	public function newsagency_details()
	{
		  $hdn_dpt_id=$this->input->post('txthiddenid');
		  $rollname=$this->input->post('txtAgecncyName');
		  $status=$this->input->post('views');
		  $this->form_validation->set_rules('txtAgecncyName','Newsagency Name','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('views','status','xss_clean');
		   
		  if($this->form_validation->run() == FALSE)
		  { 
			  $data['title']		= 'newsagency';
			  $data['template'] 	= 'newsagency_form';
			  $this->load->view('admin_template',$data);
		  }	
		  else
		  {
			  //$this->rolemaster_model->addroledetails(); 
			  if($this->newsagency_model->addnewsagencydetails(USERID))
			  {
				  if($hdn_dpt_id=="")
				  $this->session->set_flashdata('success', 'Newsagency details added successfully');
				  else
				  $this->session->set_flashdata('success', 'Newsagency details updated successfully');
				  redirect(base_url().'niecpan/newsagency_manager');
			  }
			  else
			  {		
				  $this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				  redirect(base_url().'niecpan/newsagency_manager');
				
			  }
		  }
	}
}
class edit_newsagencydetails extends newsagency_Manager
{
	public function edit_details()
	{
		$input_dpt_id = $this->uri->segment(4);
		$final_array['dpteditdetails']= $this->newsagency_model->editnewsagencydetails($input_dpt_id); 
		//$final_array['status'] = $this->newsagency_model->status_check($input_dpt_id);
		//$final_array = array_merge($rawdata,$roleeditdetails,$newsagency);
		$final_array['title']		= 'Newsagency master';
		$final_array['template'] 	= 'newsagency_form';
		$this->load->view('admin_template',$final_array);
	}
	
}















?>