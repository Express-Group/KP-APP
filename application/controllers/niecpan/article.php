<?php

/**
 * Article Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');
class Article extends CI_Controller

{
	
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/article_model');
		$this->load->model('admin/common_model');
		$this->load->model('admin/article_image_model');
		$this->load->model('admin/live_content_model');
		$this->load->model('admin/image_model');

	}
	public function index()

	{
	
		$data['Menu_id'] = get_menu_details_by_menu_name('Article');
		
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) {
	
		$data['get_country'] 		= $this->common_model->get_country_details();
		$data['get_agency'] 		= $this->common_model->get_agency_details();
		$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();

		$data['get_content_type'] 	= $this->common_model->get_content_type();
		$data['image_library'] 		= $this->article_image_model->get_image_library();
		
		//$this->image_model->DeleteTempAllImages(1);
		
		if (set_value('ddState') != '') $data['get_state'] = $this->common_model->get_state_details(set_value('ddCountry'));
		if (set_value('ddCity') != '') $data['get_city'] = $this->common_model->get_city_details(set_value('ddCountry') , set_value('ddState'));
		if (set_value('ddAgency') != '') $data['get_byline'] = $this->common_model->get_author_agency_id(set_value('ddAgency'));
		
		$data['title'] 				= 'Create Article';
		$data['template'] 			= 'article';
		
		$this->load->view('admin_template', $data);
		
		} else {
			redirect(folder_name.'/common/access_permission/add_article');
		}
	
	}
	
	public function create_article()
	
	{

	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('txtArticleHeadLine', 'Article Head Line', 'required|trim');
		$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim');
		
		if ($this->input->post('txtStatus') != 'D')
		{
			$this->form_validation->set_rules('txtMetaTitle', 'Meta Title', 'required|trim');
			$this->form_validation->set_rules('txtBodyText', 'Body Text', 'required|trim');
		}
		if ($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			if($this->article_model->insert_article()) {
			
			if ($this->input->post('txtStatus') == 'P')
				$this->session->set_flashdata('success', 'Article Published Successfully');
			else if ($this->input->post('txtStatus') == 'D')
				$this->session->set_flashdata('success', 'Article Drafted Successfully');
			else
				$this->session->set_flashdata('success', 'Article Send to Approval Successfully');
		 
				$this->session->set_userdata('main_section',$this->input->post('ddMainSection'));
		 
			} else {
				$this->session->set_flashdata('error', "Doesn't create article, Try Again");
			}
			 
			redirect(folder_name.'/article_manager');
		}
	}
	/*
	* Search the related contents in article
	*
	* @access public
	* @param Ajax call post values
	* @return JSON format array values
	*/
	
	public function search_internal_article()

	{
		$this->article_model->search_internal_article();
	}
}
/* End of file article.php */
/* Location: ./application/controllers/article.php */