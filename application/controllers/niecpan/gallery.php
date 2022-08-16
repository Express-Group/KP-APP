<?php

/**
 * Gallery Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */
 
defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery extends CI_Controller

{
	/*constructor*/
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/gallerymodel');
		$this->load->model('admin/Common_model');
		$this->load->model('admin/article_image_model');
		$this->load->model('admin/image_model');
	}
	
	/*
	*
	* Add Gallery view page
	*
	* @access PUBLIC
	* @param NULL
	* @return View add_gallery file page (or) Redirect to access permission
	*
	*/
	public function index()

	{
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Gallery');
		
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) {
		
			
			
			if (set_value('ddState') != '')
				$data['get_state'] 		= $this->Common_model->get_state_details(set_value('ddCountry'));
			if (set_value('ddCity') != '')
				$data['get_city'] 		= $this->Common_model->get_city_details(set_value('ddCountry') , set_value('ddState'));
			if (set_value('ddAgency') != '') $data['get_byline'] = $this->common_model->get_author_agency_id(set_value('ddAgency'));
		
			$data['get_agency'] 		= $this->common_model->get_agency_details();
			$data['get_country'] 		= $this->Common_model->get_country_details();
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			
			$data['title']		 		= 'Add Gallery';
			$data['template'] 			= 'image_gallery_add';
			$data['page_name'] 			= 'add_gallery';
			
			/*
			echo "<pre>";
			print_r($data);
			exit;
			*/
			
			$this->load->view('admin_template', $data);
		
		} else {
			redirect(folder_name.'/common/access_permission/add_gallery');
		}
	}
	
	/*
	*
	* Insert Image library table data to Temp table
	*
	* @access Public
	* @param Post values from Ajax call in gallery_process.js
	* @return Set of JSON response
	*/
	
	public function Insert_temp_from_image_library() {
		$this->gallerymodel->Insert_temp_from_image_library();
	}
	
	/**
	 * Delete the image library temp images
	 *
	 * @access	public
	 * @param	
	 * @return	true
	 */
	public function delete_temp_image()
	{
		$this->image_model->delete_temp_image(3);
	}
	
	/*
	*
	* Create new gallery 
	*
	* @access public
	* @param Get Post values from FORM
	* @return Redirect to Gallery Manager Page
	*/
	
		public function add_new_gallery()

	{
		extract($_POST);
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('txtgalleryname', 'Gallery Name', 'required|trim');
		$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim|xss_clean');
		
		if (isset($txtStatus) && $txtStatus == 'P')
		{
			$this->form_validation->set_rules('txtMetaTitle', 'Meta title', 'required|trim|xss_clean');
		}
		$this->form_validation->set_rules('txtMetaDescription', 'Meta description', 'trim|xss_clean');
		$this->form_validation->set_rules('cbSectionMapping[]', 'multi section id', 'trim|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			if($this->gallerymodel->insert_gallery()) {
				
				if ($this->input->post('txtStatus') == 'P') 
					$this->session->set_flashdata('success', 'Gallery Published Successfully');
				else
					$this->session->set_flashdata('success', 'Gallery Drafted Successfully');
				
			} else {
					$this->session->set_flashdata('error', 'Gallery not created, Please Try Again');
			}
			redirect(folder_name.'/gallery_manager');
		}
	}
	
		/*
	*
	* UPDATE existing gallery 
	*
	* @access public
	* @param Get Post values from FORM
	* @return Redirect to Gallery Manager Page
	*/
	
	public function update_gallery($gallery_id)

	{	
			/*
			echo "<pre>";
			print_r($_POST);
			exit;
			*/
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('txtgalleryname', 'Gallery Name', 'required|trim');
			
			$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim|xss_clean');
			$this->form_validation->set_rules('ddState', 'State', 'trim|xss_clean');
			$this->form_validation->set_rules('ddCity', 'State', 'trim|xss_clean');
			
			$this->form_validation->set_rules('ddAgency', 'Agency', 'trim|xss_clean');
			
			$this->form_validation->set_rules('txtMetaTitle', 'Meta title', 'required|trim|xss_clean');
			$this->form_validation->set_rules('txtMetaDescription', 'Meta description', 'trim|xss_clean');
			
			$this->form_validation->set_rules('cbSectionMapping[]', 'multi section id', 'trim|xss_clean');
			$this->form_validation->set_rules('txtCanonicalUrl', 'CanonicalUrl', 'trim|xss_clean');
			
			$this->form_validation->set_rules('txtSummary', 'Summary', 'trim|xss_clean');
			$this->form_validation->set_rules('txtGalleryData', 'GalleryImages', 'trim|xss_clean');
			
			if ($this->form_validation->run() == FALSE)
			{
				redirect(folder_name."/edit_gallery/" . urlencode(base64_encode($gallery_id)));
			}
			else
			{
				if($this->gallerymodel->update_gallery($gallery_id)) {
				$this->session->set_flashdata('success', 'Gallery Updated Successfully');
				} else {
				$this->session->set_flashdata('error', 'Problem is occur, Please try Again');	
				}
				redirect(folder_name.'/gallery_manager');
			}
	}
	
	
		/*
	*
	* UPDATE existing gallery 
	*
	* @access public
	* @param Get Post values from FORM
	* @return Redirect to Gallery Manager Page
	*/
	
	public function update_archive_gallery($year, $gallery_id)

	{	
			/*
			echo "<pre>";
			print_r($_POST);
			exit;
			*/
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('txtgalleryname', 'Gallery Name', 'required|trim');
			
			$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim|xss_clean');
			$this->form_validation->set_rules('ddState', 'State', 'trim|xss_clean');
			$this->form_validation->set_rules('ddCity', 'State', 'trim|xss_clean');
			
			$this->form_validation->set_rules('ddAgency', 'Agency', 'trim|xss_clean');
			
			$this->form_validation->set_rules('txtMetaTitle', 'Meta title', 'required|trim|xss_clean');
			$this->form_validation->set_rules('txtMetaDescription', 'Meta description', 'trim|xss_clean');
			
			$this->form_validation->set_rules('cbSectionMapping[]', 'multi section id', 'trim|xss_clean');
			$this->form_validation->set_rules('txtCanonicalUrl', 'CanonicalUrl', 'trim|xss_clean');
			
			$this->form_validation->set_rules('txtSummary', 'Summary', 'trim|xss_clean');
			$this->form_validation->set_rules('txtGalleryData', 'GalleryImages', 'trim|xss_clean');
			
			if ($this->form_validation->run() == FALSE)
			{
				redirect(folder_name."/edit_archive_gallery/".$year."/".urlencode(base64_encode($gallery_id)));
			}
			else
			{
				if($this->gallerymodel->update_archive_gallery($year, $gallery_id)) {
				$this->session->set_flashdata('success', 'Archive Gallery Updated Successfully');
				} else {
				$this->session->set_flashdata('error', 'Problem is occur, Please try Again');	
				}
				redirect(folder_name.'/gallery_manager');
			}
	}
	
}
/* End of file gallery.php */
/* Location: ./application/controllers/niecpan/gallery.php */