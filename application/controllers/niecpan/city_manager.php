<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class City_Manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/city_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("City");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'City';
			$rawdata['template'] 	= 'city_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/city_manager');
		}
	}
	public function city_datatable()
	{
		$this->city_model->city_datatable();
	}
	public function city_form_view()
	{
		$set_object=new cityform;
		$set_object->city_form_view();
	}
	public function city_details()
	{
		$set_object=new add_citydetails ;
		$set_object->city_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_citydetails;
		$set_object->edit_details();	
	}
	public function city_delete()
	{
		$set_object=new cityform;
		$set_object->city_delete();
	}
	public function check_cityname()
	{
		$cityexist= $this->city_model->city_check();
		if($cityexist > 0)
		{
			echo "Cityname name already exists";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}

	}
}
class cityform extends City_Manager
{
	public function city_form_view()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('City');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$rawdata['state']=$this->city_model->getstatename();
			//print_r($rawdata['country']);exit;
			$rawdata['title']		= 'City';
			$rawdata['template'] 	= 'city_form';
			$this->load->view('admin_template',$rawdata);
		} 
		else 
		{
			redirect('niecpan/common/access_permission/add_city');
		}
	}
	public function city_delete()
	{
		$input_city_id = $this->uri->segment(4);
		$this->city_model->citydetails_delete($input_city_id);
	}
}

class add_citydetails extends City_Manager
{
	public function city_details()
	{
		  $hdn_city_id=$this->input->post('txthiddenid');
		  $this->form_validation->set_rules('txtCityName','City Name','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('ddStateName','State Name','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('views','status','xss_clean');
		  if($this->form_validation->run() == FALSE)
		  { 
			  $data['title']		= 'City';
			  $data['template'] 	= 'city_form';
			  $this->load->view('admin_template',$data);
		  }	
		  else
		  {
			  //$this->rolemaster_model->addroledetails(); 
			  if($this->city_model->addcitydetails(USERID))
			  {
				  if($hdn_city_id=="")
				  $this->session->set_flashdata('success', 'City details added successfully');
				  else
				  $this->session->set_flashdata('success', 'City details updated successfully');
				  redirect(base_url().'niecpan/city_manager');
			  }
			  else
			  {		
				  $this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				  redirect(base_url().'niecpan/city_manager');
				
			  }
		  }
	}
}
class edit_citydetails extends City_Manager
{
	public function edit_details()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('City');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1)
		{
			$input_city_id = $this->uri->segment(4);
			$final_array['state']=$this->city_model->getstatename();
			$final_array['cityeditdetails']= $this->city_model->editcitydetails($input_city_id); 
			//$final_array = array_merge($rawdata,$roleeditdetails,$department);
			$final_array['title']		= 'City master';
			$final_array['template'] 	= 'city_form';
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect('niecpan/common/access_permission/edit_city');
		}
	}
	
}