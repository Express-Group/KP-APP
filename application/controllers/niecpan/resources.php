<?php
/**
 * Resources Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');
class Resources extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/resources_model');
		$this->load->model('admin/common_model');
		$this->load->model('admin/live_content_model');
		$this->load->model('admin/article_image_model');
		$this->load->model('admin/image_model');
	}

public function index()

	{
	
		$data['Menu_id'] = get_menu_details_by_menu_name('Resources');
		
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) {
	
		$data['image_library'] 		= $this->article_image_model->get_image_library();
		$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
		
		$this->image_model->DeleteTempAllImages(6);
		
		$data['title'] 				= 'Create Resource';
		$data['page_name']			= 'create_resource';
		$data['template'] 			= 'resources';
		
		$this->load->view('admin_template', $data);
		
		} else {
			redirect(folder_name.'/common/access_permission/add_resources');
		}
	
	}
	public function create_resources()
	
	{

	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('resource_head_line_id', 'Resource Head Line', 'required|trim');

		if ($this->input->post('txtStatus') != 'D')
		{
			//$this->form_validation->set_rules('hiddn_article_id', 'Live Article', 'required|trim|xss_clean');
			$this->form_validation->set_rules('imgResourceId', 'Resource Image', 'required|trim|xss_clean');
		}
		
		$this->form_validation->set_rules('txtUrlTitle', 'URL Title', 'trim|xss_clean');
		
			
		
		if ($this->form_validation->run() == FALSE)
		{
			
			$this->index();
		}
		else
		{
			if($this->resources_model->insert_resources()) {
			
			if ($this->input->post('txtStatus') == 'P')
				$this->session->set_flashdata('success', 'Resource Published Successfully');
			else if ($this->input->post('txtStatus') == 'D')
				$this->session->set_flashdata('success', 'Resource Drafted Successfully');
			else
				$this->session->set_flashdata('success', 'Resource Send to Approval Successfully');
		 
			} else {
				$this->session->set_flashdata('error', "Doesn't create resource, Try Again");
			}
			 
			redirect(folder_name.'/resources_manager');
		}
	}
	
public function delete_temp_image() 
	
	{
			$this->image_model->delete_temp_image(6);
	}
}
/* End of file resources.php */
/* Location: ./application/controllers/niecpan/resources.php */