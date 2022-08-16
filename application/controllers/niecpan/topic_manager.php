<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class topic_manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/topic_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Column");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Column Manager';
			$rawdata['template'] 	= 'topic_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/topic_manager');
		}
	}
	public function topic_form_view()
	{
		$set_object=new topicform;
		$set_object->topic_form_view();
	}

	public function topic_details()
	{
		$set_object=new add_topicdetails ;
		$set_object->topic_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_topicdetails ;
		$set_object->edit_details();	
	}
	public function topic_datatable()
	{
		$this->topic_model->topic_datatable();
	}
	public function topic_delete()
	{
		$set_object=new topicform;
		$set_object->topic_delete();
	}
	public function check_topicname()
	{
		$dptexist= $this->topic_model->departmnt_check();
		if($dptexist > 0)
		{
			echo "topic name already exists";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}

	}
	
}

class topicform extends topic_Manager
{
	public function topic_form_view()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Column');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Create Column';
			$rawdata['template'] 	= 'topic_form';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/add_topic');
		}
	}
	public function topic_delete()
	{
		$input_dpt_id = $this->uri->segment(4);
		$this->topic_model->topicdetails_delete($input_dpt_id);
	}
}


class add_topicdetails extends topic_Manager
{
	public function topic_details()
	{
		  echo $hdn_topic_id=$this->input->post('txthiddenid');
		  $rollname=$this->input->post('txttopicName');
		  $status=$this->input->post('views');
		  $this->form_validation->set_rules('txtTopicName','topic Name','trim|required|strip_tags|xss_clean');
		  //$this->form_validation->set_rules('views','status','xss_clean');
		   
		  if($this->form_validation->run() == FALSE)
		  { 
			  $data['title']		= 'Column';
			  $data['template'] 	= 'topic_form';
			  $this->load->view('admin_template',$data);
		  }	
		  else
		  {
			  //$this->rolemaster_model->addroledetails(); 
			  if($this->topic_model->addtopicdetails(USERID))
			  {
				  if($hdn_topic_id!="")
				  $this->session->set_flashdata('success', 'Topic updated successfully');
				  else
				  $this->session->set_flashdata('success', 'Topic added successfully');
				  redirect(base_url().'niecpan/topic_manager');
			  }
			  else
			  {		
				  $this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				  redirect(base_url().'niecpan/topic_manager');
				
			  }
		  }
	}
}
class edit_topicdetails extends topic_Manager
{
	public function edit_details()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Column');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) 
		{
			$input_dpt_id = $this->uri->segment(4);
			$final_array['dpteditdetails']= $this->topic_model->edittopicdetails($input_dpt_id); 
			//$final_array = array_merge($rawdata,$roleeditdetails,$topic);
			$final_array['title']		= 'Edit Column';
			$final_array['template'] 	= 'topic_form';
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect('niecpan/common/access_permission/edit_topic');
		}
	}
	
}

?>