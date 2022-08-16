<?php
/**
 * Image Manager Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Image_manager extends CI_Controller

{
	// Constructor
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/image_model'); // Load the image model file
		$this->load->model('admin/common_model'); // Load the common model file
	}
	/**
	 * Image manager page
	 *
	 * @access	public
	 * @param	
	 * @return	image manager page view load
	 */
	public function index() {
		$content_type 		= "Image Manager";
		$button_name		= "Create Image"; 
		$addPage_url 		= folder_name."/image";
		$menu_name			= "Image Library";
				
		$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);

		
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) {
			
			$data['title']				= $content_type;
			$data['btn_name']			= $button_name;
			$data['addPage_url']		= $addPage_url;
			
			$data['template'] 	= 'image_manager';
			$this->load->view('admin_template',$data);
		}
	}
	
	/**
	 * Get image datatable from image library
	 *
	 * @access	public
	 * @param	Get data from Datatable
	 * @return	Json result
	 */
	public function image_datatable() 
	{
			$this->image_model->get_image_datatables();
	}
	
	/**
	 * Edit image
	 *
	 * @access	public
	 * @param	Get image Id  from url segment
	 * @return	Edit page of image library (edit_image)
	 */
	public function edit_image()

	{
	
		$image_id = base64_decode(urldecode($this->uri->segment('3')));
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Image Library');
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
		
	
			if ($this->image_model->DeleteTempAllImages(2))
			{
				
				$data['get_image_details'] 		= $this->image_model->get_imagedetails_imageId($image_id)->row_array();
				$data['get_imagedetails_byid'] 	= $this->image_model->get_imagedetails_imageId($image_id)->result_array();
	
				if (isset($data['get_imagedetails_byid']))
				{
					
					foreach($data['get_imagedetails_byid'] as $Images)
					{
				
						$OldTempName 		= $Images['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= imagelibrary_temp_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
						$TempName = GenerateNewImageName($OldTempName, $NewImageName);
							
						ImageLibraryCopyToTemp($OldTempName,$TempName, $SourceURL,$DestinationURL);
						$PhysicalName = GetPhysicalNameFromPhysicalPath($Images['ImagePhysicalPath']);
					
						$createdon 		= $modifiedon = date('Y-m-d H:i:s');
						
						$this->image_model->addimages(USERID, $Images['content_id'],2, $Images['ImageCaption'], $Images['ImageAlt'], $PhysicalName,$TempName, $Images['Image1Type'], $Images['Image2Type'], $Images['Image3Type'], $Images['Image4Type'], 0);
						
					}
				}
			
				$data['check_image'] 			= CheckImageInOtherContent($image_id);
				$data['temp_images'] 			= $this->image_model->viewtempimage(USERID, 2);
				
				$data['title'] 					= 'Edit image';
				$data['template'] 				= 'image';
				$data['page_name'] 				= 'edit_image';
				
				$this->load->view('admin_template', $data);
			}
		
		} else {
				redirect(folder_name.'/common/access_permission/edit_image');
		}
	}
	/**
	 * Update image
	 *
	 * @access	public
	 * @param	Get image Id  from url segment
	 * @return	Update image library data
	 */
	public function update_image()

	{
		$image_id = $this->uri->segment('4');
		$this->image_model->update_image($image_id);
		$this->session->set_flashdata('success', 'Image Updated Successfully');
		redirect(folder_name.'/image_manager');
	}
}
/* End of file image_manager.php */
/* Location: ./application/controllers/niecpan/image_manager.php */