<?php

/**
 * Image Class
 *
 * @package		ENPL
 * @subpackage	NewIndianExpress
 * @category	Image
 * @author		IE Team
 * @link		http://cms.newindianexpress.com/niecpan
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Image extends CI_Controller

{
	

	/*constructor*/
	public function __construct()

	{
		
		parent::__construct();
		$this->load->model('admin/image_model'); //  Load the image model file
		$this->load->model('admin/Common_model'); //  Load the common model file
	}	
	
	 /**
	 * Create image function
	 *
	 * @access	public
	 * @param	
	 * @return	create image view file
	 */
	public function index()

	{
		

		
		$data['Menu_id'] = get_menu_details_by_menu_name('Image Library');
		
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) {
			
		$this->image_model->DeleteTempAllImages(2);
		
		$data['temp_images'] 			= $this->image_model->viewtempimage(USERID, 2);
		
		$data['title']		 		= 'Add Image';
		$data['template'] 			= 'image';
		$data['page_name'] 			= 'add_image';
		
		$this->load->view('admin_template', $data);
		
		} else {
		redirect(folder_name.'/common/access_permission/add_image');
		}
	}
	
	/**
	 * Multiple image upload function
	 *
	 * @access	public
	 * @param	upload image files in POST
	 * @return	JSON string
	 */
	public function multiple_image_upload()

	{
		
		if (count($_FILES['imagelibrary']['tmp_name']) != 0)
		{
			$TotalCount 		= count($_FILES['imagelibrary']['tmp_name']);
			$SuccessCount 		= 0;
			$FailureCount 		= 0;
			
			$oldget =  getcwd();
			
			if($this->input->post('content_type') == 2)
			chdir(source_base_path.imagelibrary_temp_image_path);
			else 
			chdir(source_base_path.gallery_temp_image_path);	
			
			$config = array(
				'upload_path' 	=> getcwd(),
				'allowed_types' => "gif|jpg|png|jpeg",
				'encrypt_name' 	=> TRUE
			);
			
			chdir($oldget);
			$files 				= $_FILES;
			$result 			= array();
			for ($Count = 0; $Count < $TotalCount; $Count++)
			{
				if ($files['imagelibrary']['name'][$Count] != '')
				{
					$result_data 							= array();
					$_FILES['imagelibrary']['name']	 		= $files['imagelibrary']['name'][$Count];
					$_FILES['imagelibrary']['type'] 		= $files['imagelibrary']['type'][$Count];
					$_FILES['imagelibrary']['tmp_name'] 	= $files['imagelibrary']['tmp_name'][$Count];
					$_FILES['imagelibrary']['error'] 		= $files['imagelibrary']['error'][$Count];
					$_FILES['imagelibrary']['size'] 		= $files['imagelibrary']['size'][$Count];
					
					$this->load->library('upload');
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('imagelibrary'))
					{
						$error = array(
							'error' => $this->upload->display_errors()
						);
						$FailureCount++;
					}
					else
					{
						$data = array(
							'upload_data' => $this->upload->data()
						);
						
						ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], image_resolution);
						
						$content_type 		= $this->input->post('content_type');
						$imagefile 			= $_FILES['imagelibrary']['tmp_name'];
						$caption_array 		= explode('.', $data['upload_data']['orig_name']);
						$caption 			= $caption_array[0];
						$Null_value 		= 'NULL';
						$display_order      = $SuccessCount+1;
					
					$insertimage 	= $this->image_model->addimages(USERID,$Null_value , $content_type,$caption, $caption,  $caption, $data['upload_data']['file_name'],0,0,0,0,$display_order);
					
						$SuccessCount++;
						$result[$Count] = $insertimage;
					}
				}
			}
			echo json_encode($result);
		}
	}
	
	/**
	 * Single image upload function
	 *
	 * @access	public
	 * @param	upload image files in POST
	 * @return	JSON string
	 */
	public function upload_new_images()

	{
		
		$status = true;
		$imagefile = $_FILES['userImage']['tmp_name'];
		
		$oldget =  getcwd();
		
		switch($this->input->post('content_type')) {
		case 2:
			$image_path = imagelibrary_temp_image_path;
			break;
		case 3:
			$image_path = gallery_temp_image_path;			
			break;
		case 4:
			$image_path = video_temp_image_path;
			break;
		case 5:
			$image_path = audio_temp_image_path;
			break;
		case 6:
			$image_path = resource_temp_image_path;
			break;
		default:
			$image_path = resource_temp_image_path;
			break;
			
		}
			
			chdir(source_base_path.$image_path);
		
		

		$config = array(
			'upload_path' 		=> getcwd(),
			'allowed_types' 	=> "gif|jpg|png|jpeg",
			'encrypt_name'	 	=> TRUE
		);
		
		chdir($oldget);
		$this->upload->initialize($config);
		$result_data = array();
		if (!$this->upload->do_upload('userImage'))
		{
			$error = array(
				'error' => $this->upload->display_errors()
			);
			$result_data['message'] = strip_tags($error['error']);
			$result_data['status'] 	= 0;
			$status = false;
		}
		else
		{
			$data = array(
				'upload_data' => $this->upload->data()
			);
			 ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], image_resolution);
			
			$content_type 			= $this->input->post('content_type');
			$imagefile 				= $_FILES['userImage']['tmp_name'];
			$caption_array 			= explode('.', $data['upload_data']['orig_name']);
			$caption 				= $caption_array[0];
			$Null_value 			= 'NULL';
			$display_order      	= 1;
			
			$insertimage 	= $this->image_model->addimages(USERID,$Null_value , $content_type,$caption, $caption,  $caption, $data['upload_data']['file_name'],0,0,0,0,$display_order);
					
			
			$result = $insertimage;
			
			$result['message'] = "Image added Successfully";
			$result['status'] 	= 1;
			
		}
		
		if($status == false) echo json_encode($result_data); 
		else echo json_encode($result);
		
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
		$this->image_model->delete_temp_image(2);
	}
	
	/**
	 * common image resize & crop process function
	 *
	 * @access	public
	 * @param	segment of page
	 * @return	true
	 */
	public function common_image_processing() {
		
	try {
		
			$ImageType = $this->uri->segment('2'); 
			
			if($ImageType != '') {
						
				if($ImageType == 'article_image_processing') {	
				$data['image_path']	 = article_temp_image_path;
				$data['Menu_id']	= get_menu_details_by_menu_name('Article');
				} else if($ImageType == 'gallery_image_processing') {
				$data['image_path']	 = gallery_temp_image_path;
				$data['Menu_id']	= get_menu_details_by_menu_name('Gallery');
				} else if($ImageType == 'video_image_processing') {
				$data['image_path']	 = video_temp_image_path;
				$data['Menu_id']	= get_menu_details_by_menu_name('Video');
				} else if($ImageType == 'audio_image_processing') {
				$data['image_path']	 = audio_temp_image_path;
				$data['Menu_id']	= get_menu_details_by_menu_name('Audio');
				} else  if($ImageType == 'resource_image_processing') {
				$data['image_path']	 = resource_temp_image_path;
				$data['Menu_id']	= get_menu_details_by_menu_name('Resources');
				}	 else {
				$data['image_path']	 = imagelibrary_temp_image_path;
				$data['Menu_id']	= get_menu_details_by_menu_name('Image Library');
				}				
					
					$TempImageId 		= base64_decode(urldecode($this->uri->segment('3')));
					
					if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) {
						$data['temp_images'] =	$this->image_model->GetTempImages($TempImageId);
						
						
						$this->common_model->common_resize_all_images($data['temp_images']);
						
						$viewimage = $this->image_model->get_image($TempImageId);
						
				
						if(isset($viewimage['image_name'])) {
							
							$Tempdata['imgsrc'] 			= image_url.$data['image_path'].$viewimage['image_name'];
													
							$ImageName = $viewimage['image_name'];									
							$Image600X390 	= str_replace(".","_600_390.", $ImageName);
							$Image600X300 	= str_replace(".","_600_300.", $ImageName);
							$Image100X65 	= str_replace(".","_100_65.", $ImageName);
							$Image150X150 	= str_replace(".","_150_150.", $ImageName);
							
							$Tempdata['image_binary_file600X390']		= image_url.$data['image_path'].$Image600X390;
							$Tempdata['image_binary_file600X300']		=  image_url.$data['image_path'].$Image600X300;
							$Tempdata['image_binary_file150X150']		=  image_url.$data['image_path'].$Image150X150;
							$Tempdata['image_binary_file100X65']		=  image_url.$data['image_path'].$Image100X65;
							
							$Tempdata['imgtype600X390']		= $viewimage['image1_type'];
							$Tempdata['imgtype600X300']		= $viewimage['image2_type'];
							$Tempdata['imgtype100X65']		= $viewimage['image3_type'];
							$Tempdata['imgtype150X150']		= $viewimage['image4_type'];
							
							$Tempdata['imagenames'] 		= $viewimage['image_name'];
							$Tempdata['imageid'] 			= $viewimage['imageid'];
							$Tempdata['image_caption'] 		= $viewimage['caption'];
							$Tempdata['image_alt'] 			= $viewimage['alt_tag'];
							$Tempdata['imagecontent_id'] 	= $viewimage['imagecontent_id'];
							
							$data['last_crop_image'] = $Tempdata;
							
							$data['title']		 	= 'Resize & Crop Image';
							$data['page_name'] 		= 'add_image';
							$data['template'] 		= 'common_image';
							
							
							$this->load->view('admin_template', $data);
						
						} else {
							show_404();
						}
						
					} else {
						redirect(folder_name.'/common/access_permission/add_image');
					}
				
				
			
			} else {
				show_404();
			}
			
		} catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
	}
	
	/**
	 * Add new image in image library
	 *
	 * @access	public
	 * @param	post value from add image form
	 * @return	image manager page
	 */
	
	public function add_new_image()

	{
			if($this->input->post('txtImageData') != '{}') {
				
				$this->image_model->insert_image();
				
				if ($this->input->post('txtStatus') == 1) 
					$this->session->set_flashdata('success', 'Image Activated Successfully');
				else 
					$this->session->set_flashdata('success', 'Image InActivated Successfully');
			} else {
				$this->session->set_flashdata('Error', 'Please add atleast one image');
			}
				redirect(folder_name.'/image_manager');
		
	}
	
	/*
	*
	* Insert temp image from image libraray
	*
	* @access Public
	* @param POST values from Ajax call
	* @return Set the JSON values from Temp table
	*/
	
	public function Insert_temp_from_image_library()
	{
		extract($_POST);
		
			switch($content_type) {
				case 2:
					$image_path = imagelibrary_temp_image_path;
					break;
				case 3:
					$image_path = gallery_temp_image_path;			
					break;
				case 4:
					$image_path = video_temp_image_path;			
					break;
				case 5:
					$image_path = audio_temp_image_path;			
					break;
				case 6:
					$image_path = resource_temp_image_path;
					break;
				default:
					$image_path = resource_temp_image_path;
					break;
			}
		
				$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
				$SourceURL  		= imagelibrary_image_path;
				$DestinationURL		= $image_path;
				
				$ImageDetails 	= GetImageDetailsByContentId($content_id);
	
				$path = $ImageDetails['ImagePhysicalPath'];
				
				$NewPath = GenerateNewImageName($path, $NewImageName);
				
				ImageLibraryCopyToTemp($path,$NewPath, $SourceURL, $DestinationURL);
				$path = $NewPath;
			
				$createdon 		= $modifiedon = date('Y-m-d H:i:s');
				
				if (isset($ImageDetails['ImageCaption']))
				{
				
					$Image1Type 	= $ImageDetails['Image1Type'];
					$Image2Type 	= $ImageDetails['Image2Type'];
					$Image3Type 	= $ImageDetails['Image3Type'];
					$Image4Type 	= $ImageDetails['Image4Type'];
					
					$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
					
					$query 				= $this->db->query("CALL insert_temp_images('" . USERID . "'," . $content_id . ",'".$content_type."','" . addslashes($ImageDetails['ImageCaption']) . "','" . addslashes($ImageDetails['ImageAlt']) . "','".addslashes($PhysicalName)."','".addslashes($path)."',".$ImageDetails['Image1Type'].",".$ImageDetails['Image2Type'].",".$ImageDetails['Image3Type'].",".$ImageDetails['Image4Type'].",1,'" . $createdon . "','" . $modifiedon . "',@insert_id)");
					
					$result 			= $this->db->query("SELECT @insert_id")->result_array();
					$image_temp_id 		= $result[0]['@insert_id'];
					$data['image_id'] 	= $image_temp_id;
					$data['source'] 	= image_url.$image_path.$path;
					
					$Physical_extension_array = explode(".",$path);
					
					$data['caption'] 	= $ImageDetails['ImageCaption'];
					$data['alt'] 		= $ImageDetails['ImageAlt'];
					
					$data['physical_name'] 		= $PhysicalName;
					$data['physical_extension'] = $Physical_extension_array[1];
					
					$data['imagecontent_id'] 		= $content_id;
					$data['image1_type'] = $ImageDetails['Image1Type']; 
					$data['image2_type'] = $ImageDetails['Image2Type'];
					$data['image3_type'] = $ImageDetails['Image3Type'];
					$data['image4_type'] = $ImageDetails['Image4Type'];
					
				}
		
		echo json_encode($data);
	
	}
	public function delete_all_temp_images() {
		$this->image_model->DeleteTempAllImages(1);
		$this->image_model->DeleteTempAllImages(2);
		$this->image_model->DeleteTempAllImages(3);
		$this->image_model->DeleteTempAllImages(4);
		$this->image_model->DeleteTempAllImages(5);
		$this->image_model->DeleteTempAllImages(6);
	
	}
}
/* End of file image.php */
/* Location: ./application/controllers/niecpan/image.php */