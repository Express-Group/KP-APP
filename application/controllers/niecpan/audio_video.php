<?php
/**
 * Audio & Video Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');
class Audio_video extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/audio_video_model');
		$this->load->model('admin/common_model');
		$this->load->model('admin/live_content_model');
		$this->load->model('admin/article_image_model');
		$this->load->model('admin/image_model');
	}
	public function create_audio_video()

	{
	
		$Page_type = $this->uri->segment('2');
	
		$data['Menu_id'] = get_menu_details_by_menu_name(ucfirst($Page_type));
		
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) {
	
		$data['get_country'] 		= $this->common_model->get_country_details();
		$data['get_agency'] 		= $this->common_model->get_agency_details();
		$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
		$data['get_content_type'] 	= $this->common_model->get_content_type();
		$data['image_library'] 		= $this->article_image_model->get_image_library();
		
		$this->image_model->DeleteTempAllImages(6);
		
		if (set_value('ddState') != '') $data['get_state'] = $this->common_model->get_state_details(set_value('ddCountry'));
		if (set_value('ddCity') != '') $data['get_city'] = $this->common_model->get_city_details(set_value('ddCountry') , set_value('ddState'));
		if (set_value('ddAgency') != '') $data['get_byline'] = $this->common_model->get_author_agency_id(set_value('ddAgency'));
		
		IF ($Page_type == 'video') {
			$data['title'] 				= 'Create Video';
			$data['page_name']			= 'create_video';
			$data['content_type']		= 4;
			$data['content_name']		= 'Video';
		} else {
			$data['title'] 				= 'Create Audio';
			$data['page_name']			= 'create_audio';
			$data['content_type']		= 5;			
			$data['content_name']		= 'Audio';
		}
		
		$data['template'] 			= 'audio_video';
		
		$this->load->view('admin_template', $data);
		
		} else {
			redirect(folder_name.'/common/access_permission/add_resources');
		}
	
	}
	
		public function add_audio_video()
	
	{

		$ContentName = $this->input->post('txtContentName');
	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('txtAudioVideoHeadLine', $ContentName.' Head Line', 'required|trim');
		$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim|xss_clean');
		
		if($ContentName == 'Video')
		$this->form_validation->set_rules('txtScript', 'Script', 'required|trim|xss_clean');
			
		

		if ($this->input->post('txtStatus') != 'D')
		{
			$this->form_validation->set_rules('imgAudioVideoId', $ContentName.' Image', 'required|trim|xss_clean');
			$this->form_validation->set_rules('txtMetaTitle', 'Meta title', 'required|trim|xss_clean');
		}
		
		$this->form_validation->set_rules('txtMetaDescription', 'Meta Description', 'trim|xss_clean');
		$this->form_validation->set_rules('txtCanonicalUrl', 'Canonical Url', 'trim|xss_clean');
		
			
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->create_audio_video();
		}
		else
		{
			if($this->audio_video_model->insert_audio_video()) {
			
			if ($this->input->post('txtStatus') == 'P')
				$this->session->set_flashdata('success', $ContentName.' Published Successfully');
			else if ($this->input->post('txtStatus') == 'D')
				$this->session->set_flashdata('success', $ContentName.' Drafted Successfully');
			else
				$this->session->set_flashdata('success', $ContentName.' UnPublished Successfully');
		 
			} else {
				$this->session->set_flashdata('error', "Doesn't create ". $ContentName.", Try Again");
			}
			
				
		if($ContentName == 'Video')			 
			redirect(folder_name.'/video_manager');
		else
			redirect(folder_name.'/audio_manager');
		
		}
	}
	
	public function delete_temp_image() 
	
	{
			$this->image_model->delete_temp_image(6);
	}
	
}
/* End of file article.php */
/* Location: ./application/controllers/article.php */