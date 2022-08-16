<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class poll_manager extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/common_model');
		$this->load->model('admin/poll_model');
	}
	
	public function pollmanager_datatable()
	{
		$this->poll_model->pagination_datatable();
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Poll');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) {
		$data['title']		= 'Poll Manager';
		$data['template'] 	= 'poll_manager';
		$this->load->view('admin_template',$data);
		} else {
			redirect(folder_name.'/common/access_permission/poll_manager');
		}
	}
	
	public function create_poll_page()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Poll');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$data['title']		= 'Create Poll';
			$data['template'] 	= 'create_poll';
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$this->load->view('admin_template',$data);
		}
		else {
			redirect(folder_name.'/common/access_permission/add_poll');
		}
	}
	
	public function update_poll()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Poll');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) 
		{
			$poll_id = base64_decode(urldecode($this->uri->segment(4)));
			$data['fetch_details'] = $this->poll_model->fetch_poll_val($poll_id)->row_array();
			//$data['fetch_article_title'] = $this->poll_model->fetch_article_title($poll_id)->row_array();
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$data['title']		= 'Edit Poll';
			$data['template'] 	= 'create_poll';
			
			$this->load->view('admin_template',$data);
		}
		else {
			redirect(folder_name.'/common/access_permission/edit_poll');
		}
	}
	
	
	public function get_poll_result()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Poll');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW".$data['Menu_id']) == 1) 
		{
			$poll_id = base64_decode(urldecode($this->uri->segment(4)));
			$data['sel_poll_result'] = $this->poll_model->select_poll($poll_id)->row_array();
			$data['fetch_details'] = $this->poll_model->fetch_poll_val($poll_id)->row_array();
			$data['title']		= 'View Result';
			$data['template'] 	= 'poll_results';
			
			$this->load->view('admin_template',$data);
		}
		else {
			redirect(folder_name.'/common/access_permission/view_poll_result');
		}
	}
	
	public function inactivate_poll()
	{
		$poll_id = $this->uri->segment(4);
		$this->poll_model->inactivate_poll_status($poll_id);
	}
	
	public function change_status()
	{
		$this->poll_model->change_status();
	}
	
	public function delete_data()
	{
		$poll_id = $this->uri->segment(4);
		$this->poll_model->delete_poll($poll_id);
	}
	
	public function image_check() //image validation function
	{
		$file_name = $_FILES['btnImageUPload']['name'];
		$file_ext = trim(pathinfo($file_name, PATHINFO_EXTENSION));
		
		$file_size = $_FILES['btnImageUPload']['size'];
		if($file_ext != "jpg" && $file_ext != "png" && $file_ext != "jpeg")
		{
			$this->form_validation->set_message('image_check', 'Invalid file type. Only JPG, JPEG and PNG types are accepted');
			return FALSE;
		}	
		else
		{
			return TRUE;
		}
	}
	
	public function insrt_poll() 
	{
		$this->load->library('form_validation');
		
			if(!empty($_FILES['btnImageUPload']['name']))
			{
				$this->form_validation->set_rules('btnImageUPload','Image', 'callback_image_check');
			}
			$this->form_validation->set_rules('txtQuestion','Question', 'trim|required|xss_clean');
			$this->form_validation->set_rules('txtArticleId','Article Id', 'trim|xss_clean');
			
			$this->form_validation->set_rules('ddOptions','Number of options', 'trim|xss_clean');
			$this->form_validation->set_rules('txtOption1','Option text 1', 'trim|xss_clean');
			$this->form_validation->set_rules('txtOption2','Option text 2', 'trim|xss_clean');
			$this->form_validation->set_rules('txtOption3','Option text 3', 'trim|xss_clean');
			$this->form_validation->set_rules('txtOption4','Option text 4', 'trim|xss_clean');
			$this->form_validation->set_rules('txtOption5','Option text 5', 'trim|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->create_poll_page();
			}
			else
			{
				$this->poll_model->poll_add(USERID);
			}
	}
	
	public function search_internal_article() 
	{
		$search_internal_article = $this->poll_model->search_internal_article();
		echo json_encode($search_internal_article);
	}


	public function custom_image_upload()
	{
		extract($_POST);
		$result = array();
		$oldget = getcwd();
		chdir(source_base_path . poll_temp_image_path);
		$config = array(
			'upload_path' => getcwd(),
			'allowed_types' => "gif|jpg|png|jpeg",
			'encrypt_name' => TRUE
		);
		
		chdir($oldget);
		$this->upload->initialize($config);
		$result_data = array();
		if(!$this->upload->do_upload('imagelibrary'))
		{
			$error                  = array(
				'error' => $this->upload->display_errors()
			);
			$result_data['message'] = $error['error'];
			$result_data['status']  = 0;
		}
		else
		{
			$data   = array(
				'upload_data' => $this->upload->data()
			);
			$result = $this->upload->data();
			ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])), $data['upload_data']['full_path'], image_resolution);
			$result['imagefile']     = $_FILES['imagelibrary']['tmp_name'];
			$caption_array           = explode('.', $data['upload_data']['orig_name']);
			$result['caption']       = $caption_array[0];
			$result['physical_name'] = $caption_array[0];
			$result['alt_tag']       = $caption_array[0];
			$result['content_typ']   = 1;
			$result['Null_value']    = "NULL";
			
			$result['image'] = image_url . poll_temp_image_path . $data['upload_data']['file_name'];
			
			$caption 			= $caption_array[0];
			$content_type 		= 1;
			$Null_value			= "NULL";
			/*$imagefile 			= $_FILES['imagelibrary']['tmp_name'];
			$caption_array 		= explode('.', $data['upload_data']['orig_name']);
			$caption 			= $caption_array[0];
			$content_type 		= 1;
			$Null_value			= "NULL";*/
			
			$result_data	= $this->poll_model->custom_image_upload(USERID,$Null_value , $content_type,$caption, $caption,  $caption, $data['upload_data']['file_name'],0,0,0,0,1, '', '', '');
			
			$result = array_merge($result, $result_data);
		}
		echo json_encode($result);
	}
	
	public function search_image_library()
	{
		extract($_POST);
		$this->session->set_userdata('image_caption', $Caption);
		$search_image_library_result = $this->poll_model->search_image_library($Caption);
		echo json_encode($search_image_library_result);
	}
	
	public function search_image_library_scroll()
	{
		$data['pages']         = $this->uri->segment('4');
		$data['image_library'] = $this->poll_model->search_image_library_scroll($data['pages']);
		$data['nextpages']     = $this->uri->segment('4') + 1;
		
		//echo $this->load->view('admin/custom_image_scroll', $data);
		
		if(count($data['image_library']) > 0){
			echo $this->load->view('admin/custom_image_scroll', $data);
		}
		else{
			echo "";
		}
	}
	
	public function Insert_temp_from_image_library()
	{
		extract($_POST);
		if($content_id != '')
		{
			$NewImageName   = md5(rand(10000000000000000, 99999999999999999) . date('yymmddhis'));
			$SourceURL      = imagelibrary_image_path;
			$DestinationURL = poll_temp_image_path;
			$ImageDetails   = GetImageDetailsByContentId($content_id);
			$path           = $ImageDetails['ImagePhysicalPath'];
			$NewPath        = GenerateNewImageName($path, $NewImageName);
			ImageLibraryCopyToTemp($path, $NewPath, $SourceURL, $DestinationURL);
			$path        = $NewPath;
			$contenttype = $contentType;
			
			if(isset($caption))
			{
				$temp_image_details = $this->poll_model->Insert_temp_from_image_library($ImageDetails, $content_id, $caption, $alt, $path, $contenttype, '', '', '', $NewImageName);
				//$temp_image_details['temp_name'] = $NewImageName;	
				echo json_encode($temp_image_details);
			}
			else
			{
				echo json_encode(array(""));
			}
		}
		else
		{
			//tempImageIndex 
			$temp_image_details = $this->common_image_processing($tempImageIndex);
			echo json_encode($temp_image_details);
			//echo json_encode(array(""));
		}
	}
	
	
	public function custom_image_processing()
	{
		try {		
				$ImageType = $this->uri->segment('2'); 			
				if($ImageType != '') 
				{
					$data['image_path']	 = poll_temp_image_path;										
						$TempImageId 			= base64_decode(urldecode($this->uri->segment('4')));
												
						$supported_image_width 	= $this->uri->segment('5');
						$supported_image_height	= $this->uri->segment('6');
						
							$viewimage = $this->poll_model->temp_custom_image_details('', $TempImageId)->row_array();
							if(count($viewimage) > 0)
							{
								///  Do nothing 
							}
							else
							{
								////  Search id in temporary table  ////
								$viewimage = $this->poll_model->temp_custom_image_details($TempImageId, '')->row_array();
							}
							
							$data['temp_images'] = $viewimage;
							$this->poll_model->common_resize_all_images($viewimage);
					
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
								
								$data['supported_image_width'] 	= $supported_image_width;
								$data['supported_image_height'] = $supported_image_height;
								
								$data['title']		 	= 'Edit Custom Image';
								$data['page_name'] 		= 'add_image';
								$data['template'] 		= 'poll_image_cropping';
								$this->load->view('admin_template', $data);
							
							} else {
								redirect(folder_name);
							}
				
				} else {
					redirect(folder_name);
				}
			
			} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		
	
		//$this->poll_model->custom_image_processing();
	}
	
	public function crop_custom_image()
	{
		try
		{
			extract($_POST);
			$data = '';
			if(!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->poll_model->temp_custom_image_details($crop_image_id, '')->row_array();
			
			if(isset($ImageDetails['image_name']))
			{
				$image_src_path = '';
				$image_src_path = source_base_path . poll_temp_image_path;
				$source         = image_url . poll_temp_image_path;
				$src            = $image_src_path . $ImageDetails['image_name'];
				
				switch($image_type)
				{
					case "image_600_390":
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
					
					case "image_600_300":
						$dst    = $image_src_path . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						break;
					
					case "image_100_65":
						$dst    = $image_src_path . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						break;
					
					case "image_150_150":
						$dst    = $image_src_path . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						break;
					
					default:
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
						
				}
				
				
				$ImageDetails = getimagesize($src);
				
				$ImageExtension = explode("/", $ImageDetails['mime']);
				$extType        = strtolower($ImageExtension[1]);
				
				if(!empty($src) && !empty($dst) && !empty($data))
				{
					switch($extType)
					{
						case 'gif':
							$src_img = imagecreatefromgif($src);
							break;
						
						case 'jpg':
							$src_img = imagecreatefromjpeg($src);
							break;
						
						case 'jpeg':
							$src_img = imagecreatefromjpeg($src);
							break;
						
						case 'png':
							$src_img = imagecreatefrompng($src);
							break;
					}
					if(!$src_img)
					{
						$result_value['status'] = 'error';
						$result_value['msg']    = "Failed to read the image file";
						return json_encode($result_value);
					}
					$size      = getimagesize($src);
					$size_w    = $size[0]; // natural width
					$size_h    = $size[1]; // natural height
					$src_img_w = $size_w;
					$src_img_h = $size_h;
					$degrees   = $data->rotate;
					// Rotate the source image
					if(is_numeric($degrees) && $degrees != 0)
					{
						// PHP's degrees is opposite to CSS's degrees
						$new_img = imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));
						imagedestroy($src_img);
						$src_img   = $new_img;
						$deg       = abs($degrees) % 180;
						$arc       = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
						$src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
						$src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
						// Fix rotated image miss 1px issue when degrees < 0
						$src_img_w -= 1;
						$src_img_h -= 1;
					}
					$tmp_img_w = $data->width;
					$tmp_img_h = $data->height;
					$dst_img_w = $crop_width;
					$dst_img_h = $crop_height;
					$src_x     = $data->x;
					$src_y     = $data->y;
					
					if($src_x <= -$tmp_img_w || $src_x > $src_img_w)
					{
						$src_x = $src_w = $dst_x = $dst_w = 0;
					}
					else if($src_x <= 0)
					{
						$dst_x = -$src_x;
						$src_x = 0;
						$src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
					}
					else if($src_x <= $src_img_w)
					{
						$dst_x = 0;
						$src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
					}
					if($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h)
					{
						$src_y = $src_h = $dst_y = $dst_h = 0;
					}
					else if($src_y <= 0)
					{
						$dst_y = -$src_y;
						$src_y = 0;
						$src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
					}
					else if($src_y <= $src_img_h)
					{
						$dst_y = 0;
						$src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
					}
					// Scale to destination position and size
					$ratio = $tmp_img_w / $dst_img_w;
					$dst_x /= $ratio;
					$dst_y /= $ratio;
					$dst_w /= $ratio;
					$dst_h /= $ratio;
					$dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
					// Add transparent background to destination image
					imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
					imagesavealpha($dst_img, true);
					$result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					if($result)
					{
						if(!imagejpeg($dst_img, $dst))
						{
							$result_value['status'] = 'error';
							$result_value['msg']    = "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg']    = "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
					$ImageDetails = getimagesize($dst);
					$width        = $ImageDetails[0];
					$height       = $ImageDetails[1];
					$size         = $ImageDetails['bits'];
					$type         = $ImageDetails['mime'];
					$Imagedata    = '';
					$modifiedon   = date('Y-m-d H:i:s');
					
					
					$Null_value = 'NULL';
					$PathArray  = explode('/', $dst);
					$TempPath   = end($PathArray);
					
					$content_id = 'NULL';
					
					switch($image_type)
					{
						case "image_600_390":
							
							$image_type_1_value = 2;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							
							break;
						
						case "image_600_300":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 2;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_100_65":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = 2;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_150_150":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = 2;
							
							break;
						
						default:
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 2;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							break;
							
					}
					
					$this->poll_model->update_custom_crop_image($content_id, $crop_caption, $crop_alt, $image_type_1_value, $image_type_2_value, $image_type_3_value, $image_type_4_value, $modifiedon, $crop_image_id, $image_type, '0');
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
					$viewimage = $this->poll_model->temp_custom_image_details($crop_image_id, '')->row_array();
					if(isset($viewimage['image_binary_file']))
					{
						
						$result_value['image1_type'] = $viewimage['image1_type'];
						$result_value['image2_type'] = $viewimage['image2_type'];
						$result_value['image3_type'] = $viewimage['image3_type'];
						$result_value['image4_type'] = $viewimage['image4_type'];
					}
					
					$result_value['status']  = 'success';
					$result_value['msg']     = "Successfully cropped the image";
					$result_value['source']  = $source;
					$result_value['imageid'] = $crop_image_id;
					echo json_encode($result_value);
				}
			}
			else
			{
				$result_value['status'] = 'error';
				$result_value['msg']    = "Invalid Image";
				echo json_encode($result_value);
			}
		}
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg']    = 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	}
	
	public function resize_custom_image()
	{
		try
		{
			
			extract($_POST);
			$data = '';
			if(!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->poll_model->temp_custom_image_details($crop_image_id, '')->row_array();
			
			if(isset($ImageDetails['image_name']))
			{
				
				$image_src_path = source_base_path . poll_temp_image_path;
				$src            = $image_src_path . $ImageDetails['image_name'];
				$source         = image_url . poll_temp_image_path;
				
				switch($image_type)
				{
					case "image_600_390":
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
					
					case "image_600_300":
						$dst    = $image_src_path . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						break;
					
					case "image_100_65":
						$dst    = $image_src_path . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						break;
					
					case "image_150_150":
						$dst    = $image_src_path . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						break;
					
					default:
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
						
				}
				
				
				
				$ImageDetails = getimagesize($src);
				
				$ImageExtension = explode("/", $ImageDetails['mime']);
				$extType        = strtolower($ImageExtension[1]);
				
				
				if(!empty($src) && !empty($dst) && !empty($data))
				{
					switch($extType)
					{
						case 'gif':
							$src_img = imagecreatefromgif($src);
							break;
						
						case 'jpg':
							$src_img = imagecreatefromjpeg($src);
							break;
						
						case 'jpeg':
							$src_img = imagecreatefromjpeg($src);
							break;
						
						case 'png':
							$src_img = imagecreatefrompng($src);
							break;
					}
					if(!$src_img)
					{
						$result_value['status'] = 'error';
						$result_value['msg']    = "Failed to read the image file";
						return json_encode($result_value);
					}
					$size  = getimagesize($src);
					$src_w = $size[0]; // natural width
					$src_h = $size[1]; // natural height	
					
					$dst_w = $crop_width;
					$dst_h = $crop_height;
					
					// Load
					
					$dst_img = imagecreatetruecolor($dst_w, $dst_h);
					
					
					//$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
					$source_ratio = $src_w / $src_h; 
					   $destination_ratio = $dst_w / $dst_h; 
					
					  // crop to fit 
					  if ($source_ratio > $destination_ratio) { 
					   // source has a wider ratio 
					   $temp_width = (int)($src_h * $destination_ratio); 
					   $temp_height = $src_h; 
					   $source_x = (int)(($src_w - $temp_width) / 2); 
					   $source_y = 0; 
					  } else { 
					   // source has a taller ratio 
					   $temp_width = $src_w; 
					   $temp_height = (int)($src_w / $destination_ratio); 
					   $source_x = 0; 
					   $source_y = (int)(($src_h - $temp_height) / 2); 
					  } 
					  $destination_x = 0; 
					  $destination_y = 0; 
					  $source_width = $temp_width; 
					  $source_height = $temp_height; 
					  $new_destination_width = $dst_w; 
					  $new_destination_height = $dst_h; 
					  
					 
					  $result =  imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height);
					if($result)
					{
						if(!imagejpeg($dst_img, $dst))
						{
							$result_value['status'] = 'error';
							$result_value['msg']    = "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg']    = "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
					$ImageDetails = getimagesize($dst);
					$width        = $ImageDetails[0];
					$height       = $ImageDetails[1];
					$size         = $ImageDetails['bits'];
					$type         = $ImageDetails['mime'];
					$Imagedata    = '';
					$modifiedon   = date('Y-m-d H:i:s');
					
					
					$Null_value  = 'NULL';
					$contenttype = 'ImageLibrary';
					$PathArray   = explode('/', $dst);
					$TempPath    = end($PathArray);
					
					$content_id = 'NULL';
					
					switch($image_type)
					{
						case "image_600_390":
							
							$image_type_1_value = 1;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							break;
						
						case "image_600_300":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 1;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_100_65":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = 1;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_150_150":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = 1;
							
							break;
						
						default:
							$image_type_1_value = $Null_value;
							$image_type_2_value = 1;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							break;
							
					}
					
					$this->poll_model->update_custom_crop_image($content_id, $crop_caption, $crop_alt, $image_type_1_value, $image_type_2_value, $image_type_3_value, $image_type_4_value, $modifiedon, $crop_image_id, $image_type, '0');
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
					
					$viewimage = $this->poll_model->temp_custom_image_details($crop_image_id, '')->row_array();
					
					if(isset($viewimage['image_binary_file']))
					{
						
						$result_value['image1_type'] = $viewimage['image1_type'];
						$result_value['image2_type'] = $viewimage['image2_type'];
						$result_value['image3_type'] = $viewimage['image3_type'];
						$result_value['image4_type'] = $viewimage['image4_type'];
					}
					
					$result_value['status']  = 'success';
					$result_value['msg']     = "Successfully cropped the image";
					$result_value['source']  = $source;
					$result_value['imageid'] = $crop_image_id;
					echo json_encode($result_value);
				}
			}
			else
			{
				$result_value['status'] = 'error';
				$result_value['msg']    = "Invalid Image";
				echo json_encode($result_value);
			}
		}
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg']    = 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
		
	}
	
	public function update_custom_image_changes()
	{
		/* Update the -- table save status to '1' */
		$temp_image_id = $this->input->post('tempimageid');
		$update_img    = $this->input->post('update_img');
		$physical_name = $this->input->post('physical_name');
		$content_id    = $this->input->post('content_id');
		
		$temp_image_save_details = $this->poll_model->temp_custom_image_details($temp_image_id, '')->row_array();
		if(count($temp_image_save_details) > 0)
		{
			/*if($update_img != 'cancel')
			{
				$update_save_status = ($temp_image_save_details['save_status'] == 0) ? '2' : '1';
				$this->poll_model->update_custom_crop_image('', '', '', '', '', '', '', '', $temp_image_id, '', $update_save_status);
			}*/
			if($update_img == 'save')
			{
				$update_save_status = ($temp_image_save_details['save_status'] == 0) ? '2' : '1';
				$this->poll_model->update_custom_crop_image( '', '', '', '', '', '', '', '', $temp_image_id, '', $update_save_status );
			}	
			elseif($update_img == 'cancel')
			{
				
				$content_type 		= $temp_image_save_details['contenttype'];
				$imagecontent_id 	= $temp_image_save_details['imagecontent_id'];
				$image_name 		= explode(".",$temp_image_save_details['image_name']);				
				$image_name 		= $image_name[0];
				if(isset($content_type)) {
					
					if($imagecontent_id != ''){
						//$this->delete_temp_custom_image($temp_image_save_details['image_name'],$temp_image_id);										
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= article_temp_image_path;		
						$ImageDetails 		= GetImageDetailsByContentId($imagecontent_id);		
						$path 				= $ImageDetails['ImagePhysicalPath'];								
						ImageLibraryCopyToTemp($path,$temp_image_save_details['image_name'], $SourceURL, $DestinationURL);
						
						$path				= $temp_image_save_details['image_name'];
						$this->poll_model->Insert_temp_from_image_library($ImageDetails, $imagecontent_id, $temp_image_save_details['caption'], $temp_image_save_details['alt_tag'], $path, $temp_image_save_details['contenttype'], $temp_image_save_details['Articlecontent_id'], '', '', '' );
						
						$this->poll_model->update_custom_crop_image( '', '', '', '', '', '', '', '', $temp_image_id, '', '1' );
						
					}
					else
					{
						$this->poll_model->update_custom_crop_image( '', '', '', '0', '0', '0', '0', '', $temp_image_id, 'cancel', '1' );
						
						$viewimage 			= $this->poll_model->temp_custom_image_details($temp_image_id, '')->row_array();						
						$this->poll_model->common_resize_all_images_again($viewimage);
					}
					
					$msg		= "Custom image changes are cancelled";			
					$msg_type 	= 1;
					$show_msg 	= 1;
					$res_status = 1;
					
				} else {
					$msg		= "Internal server error while cancelling changes";			
					$msg_type 	= 2;
					$show_msg 	= 1;
					$res_status = 2;
				}
		
			}
		}
		
		if(mysql_error() == '')
		{
			if($update_img != 'cancel')
			{
				$msg        = "Images saved successfully";
				$msg_type   = 1;
				$show_msg   = 1;
				$res_status = 1;
			}
			else
			{
				$msg        = "Custom image changes are cancelled";
				$msg_type   = 1;
				$show_msg   = 1;
				$res_status = 1;
			}
		}
		else
		{
			$msg        = "Internal server error";
			$msg_type   = 2;
			$show_msg   = 1;
			$res_status = 0;
		}
		$emsg = array(
			"msg" => $msg,
			"msg_type" => $msg_type,
			"show_msg" => $show_msg,
			"res_status" => $res_status
		);
		header("content-type: application/json");
		echo json_encode($emsg);
	}
	
	
	public function check_custom_image_name() {
		extract($_POST);					 
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
		create_image_folder( $Year, $Month, $Day);
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		$ArrayPhysical = explode('.',$physical_name);
		
		if($is_image_from_libarary != ''){
			if($temp_id != ''){
				$temp_image = $this->poll_model->temp_custom_image_details($temp_id, '')->row_array();
				if(count($temp_image) > 0){
				$verify_name = ($temp_image['imagecontent_id'] == "NULL" && $temp_image['save_status'] == 1)? true : (($temp_image['imagecontent_id'] != "" && $temp_image['save_status'] == 2) ? true : false);					
				}
				else
				{
					$verify_name = false;
				}

			}
			else
			{
				$verify_name = true;
			}
			
			if($verify_name && trim($ArrayPhysical[0]) != '')
			{
				if (file_exists(source_base_path . imagelibrary_image_path . $FolderMapping .trim($physical_name))) {
						$data['status'] = 'false';						
					} else  {
						$data['status'] = 'true';						
					}
			}
			else
			{
				$data['status'] = 'true';
			}
		}
		else
		{
			//$data['status'] = 'true';
			if(trim($ArrayPhysical[0]) != '')
			{
				if (file_exists(source_base_path . imagelibrary_image_path . $FolderMapping .trim($physical_name))) {
						$data['status'] = 'false';						
					} else  {
						$data['status'] = 'true';						
					}
			}
			else
			{
				$data['status'] = 'true';
			}
		}			
		echo json_encode($data);
	}
	
	public function delete_temp_custom_image($temp_table_image_name, $temp_image_id)
	{
		$TempSourceURL = poll_temp_image_path;
		/* Delete existed temporary images */
		DeleteTempImage($temp_table_image_name, $temp_image_id, $TempSourceURL);
		$this->poll_model->delete_temp_custom_image($temp_image_id);
	}
	
	public function common_image_processing($temporary_image_table_id) 
	{		

		$data['image_path']	 	= poll_temp_image_path;				
		$TempImageId 			= $temporary_image_table_id;

		$viewimage 				= $this->poll_model->temp_custom_image_details($TempImageId, '')->row_array();
		$data['temp_images'] 	= $viewimage;
		$this->poll_model->common_resize_all_images($viewimage);

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
			
			$data['image_id'] 	= $TempImageId;
			$data['source'] 	= image_url.poll_temp_image_path;
			
			$Physical_extension_array = explode(".",$viewimage['image_name']);
			
			$data['caption'] 	= $viewimage['caption'];
			$data['alt'] 		= $viewimage['alt_tag'];
			
			$data['physical_name'] 		= $viewimage['physical_name'];
			$data['physical_extension'] = $Physical_extension_array[1];
			
			$data['imagecontent_id'] 		= $viewimage['imagecontent_id'];
			$data['image1_type'] = $viewimage['image1_type'];
			$data['image2_type'] = $viewimage['image2_type'];
			$data['image3_type'] = $viewimage['image3_type'];
			$data['image4_type'] = $viewimage['image4_type'];
		} else {
			$data = "";
		}
			return $data;
								
			
		
	}

}

?>