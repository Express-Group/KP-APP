<?php
/**
 * Resources Manager Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Resources_manager extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/resources_model');
		$this->load->model('admin/common_model');
		$this->load->model('admin/live_content_model');
		$this->load->model('admin/article_image_model');
		$this->load->model('admin/image_model');
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
	}
	public function index()

	{

				$content_type 		= "Resources Manager";
				$button_name		= "Create Resources"; 
				$addPage_url 		= folder_name."/resources";
				$menu_name			= "Resources";
				
				$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);

		
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) {
			
			$data['title']				= $content_type;
			$data['btn_name']			= $button_name;
			$data['addPage_url']		= $addPage_url;
			
			$data['template'] 	= 'resources_manager';
			$this->load->view('admin_template',$data);
			
		}
				
	}
	public function resource_datatable()
	{
		$this->resources_model->get_resource_datatables();
	}
	public function edit_resources($resources_id)

	{
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Resources');
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
			
			if($this->image_model->DeleteTempAllImages(6)) {
		
				$resources_id = base64_decode(urldecode($this->uri->segment('3')));	
			
				$data['get_resources_details'] 	= $this->resources_model->get_resources_details($resources_id)->row_array();
			
				$resource_image_id  	= $data['get_resources_details']['image_id'];
				
				$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
				$data['image_library'] 			= $this->article_image_model->get_image_library();
				
				
				if($resource_image_id != '') {
				
						$OldTempName 		= $data['get_resources_details']['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= resource_temp_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
						$TempName = GenerateNewImageName($OldTempName, $NewImageName);
							
						ImageLibraryCopyToTemp($OldTempName,$TempName, $SourceURL,$DestinationURL);
						$PhysicalName = GetPhysicalNameFromPhysicalPath($data['get_resources_details']['ImagePhysicalPath']);
					
						$createdon 		= $modifiedon = date('Y-m-d H:i:s');
						
						$data['get_resources_details']['image_details'] = $this->image_model->addimages(USERID, $data['get_resources_details']['image_id'],6, $data['get_resources_details']['ImageCaption'], $data['get_resources_details']['ImageAlt'], $PhysicalName,$TempName, $data['get_resources_details']['Image1Type'], $data['get_resources_details']['Image2Type'], $data['get_resources_details']['Image3Type'], $data['get_resources_details']['Image4Type'], 0);
				
				} else  {
				$data['get_resources_details']['resource_image_id']  = '';
				}
				/*
				echo "<pre>";
				print_r($data['get_resources_details']);
				exit;
				*/
			$data['page_name']				= 'edit_resource';
			$data['title'] 					= 'Edit resources';
			$data['template'] 				= 'resources';
			$this->load->view('admin_template', $data);
			
			}
		} else {
				redirect(folder_name.'/common/access_permission/edit_resources');
		}
	}
	
	public function edit_archive_resources($year,$resources_id)

	{
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Resources');
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
			
			if($this->image_model->DeleteTempAllImages(6)) {
		
				$resources_id = base64_decode(urldecode($resources_id));	
			
				$this->archive_db->select("*,created_by as CreatedName,created_on as Createdon,modified_by as ModifiedName,modified_on as Modifiedon");
				$this->archive_db->from("resources_".$year);
				$this->archive_db->where("content_id",$resources_id);
				
				$Get = $this->archive_db->get();
				
				$data['get_resources_details'] 	= $Get->row_array();
			
				$resource_image_id  	= $data['get_resources_details']['image_id'];
				
				$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
				$data['image_library'] 			= $this->article_image_model->get_image_library();
				
				
				if($resource_image_id != '') {
						
						$ImageDetails = GetImageDetailsByContentId($resource_image_id);	
						
						$OldTempName 		= $ImageDetails['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= resource_temp_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
						$TempName = GenerateNewImageName($OldTempName, $NewImageName);
							
						ImageLibraryCopyToTemp($OldTempName,$TempName, $SourceURL,$DestinationURL);
						$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
					
						$createdon 		= $modifiedon = date('Y-m-d H:i:s');
						
						$data['get_resources_details']['image_details'] = $this->image_model->addimages(USERID, $ImageDetails['content_id'],6, $ImageDetails['ImageCaption'], $ImageDetails['ImageAlt'], $PhysicalName,$TempName, $ImageDetails['Image1Type'], $ImageDetails['Image2Type'], $ImageDetails['Image3Type'], $ImageDetails['Image4Type'], 0);
				
				} else  {
				$data['get_resources_details']['resource_image_id']  = '';
				}
				
					$url_array = explode("/",$data['get_resources_details']['url']);
				$GetTitleFromURL = explode(".",end($url_array));
			
				$data['get_resources_details']['url_title'] = str_replace("-".$resources_id,"",$GetTitleFromURL[0]);
				
				/*
				echo "<pre>";
				print_r($data['get_resources_details']);
				exit;
				*/
			$data['archive_year']	       = $year;
			$data['page_name']				= 'edit_resource';
			$data['title'] 					= 'Edit Archive Resources';
			$data['template'] 				= 'resources';
			$this->load->view('admin_template', $data);
			
			}
		} else {
				redirect(folder_name.'/common/access_permission/edit_resources');
		}
	}
	
	public function update_resources($resources_id)

	{

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			$this->form_validation->set_rules('resource_head_line_id', 'Resource Head Line', 'required|trim');

			if ($this->input->post('txtStatus') != 'D')
			{
				$this->form_validation->set_rules('imgResourceId', 'Resource Image', 'required|trim|xss_clean');
			}
			
			$this->form_validation->set_rules('txtUrlTitle', 'URL Title', 'trim|xss_clean');
			
			if ($this->form_validation->run() == FALSE)
			{
				
				redirect(folder_name."/edit_resources/" . urlencode(base64_encode($resources_id)));
			}
			else
			{
				if($this->resources_model->update_resources($resources_id)) {
					$this->session->set_flashdata('success', 'Resources Updated Successfully');
					redirect(folder_name.'/resources_manager');
				}
			}
		
	}
	
		public function update_archive_resources($year, $resources_id)

	{

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			$this->form_validation->set_rules('resource_head_line_id', 'Resource Head Line', 'required|trim');

			if ($this->input->post('txtStatus') != 'D')
			{
				$this->form_validation->set_rules('imgResourceId', 'Resource Image', 'required|trim|xss_clean');
			}
			
			$this->form_validation->set_rules('txtUrlTitle', 'URL Title', 'trim|xss_clean');
			
			if ($this->form_validation->run() == FALSE)
			{
				
				redirect(folder_name."/edit_archive_resources/".$year."/". urlencode(base64_encode($resources_id)));
			}
			else
			{
				if($this->resources_model->update_archive_resources($year,$resources_id)) {
					$this->session->set_flashdata('success', 'Archive Resources Updated Successfully');
					redirect(folder_name.'/resources_manager');
				}
			}
		
	}
	
}
/* End of file resources.php */
/* Location: ./application/controllers/resources_manager.php */