<?php
/**
 * Gallery Manager Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Gallery_manager extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/gallerymodel');
		$this->load->model('admin/Common_model');
		$this->load->model('admin/article_image_model');
		$this->load->model('admin/image_model');
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
	}
	
	/*
	*
	* Gallery Manager page index function
	*
	* @access PUBLIC
	* @param NULL
	* @return View Gallery Manager page
	*
	*/
	
	
	public function index()

	{

		$content_type 		= "Gallery Manager";
		$button_name		= "Create Gallery"; 
		$addPage_url 		= folder_name."/gallery";
		$menu_name			= "Gallery";
		
		$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);

		
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) {
			
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			
			$data['title']				= $content_type;
			$data['btn_name']			= $button_name;
			$data['addPage_url']		= $addPage_url;
			
			$data['template'] 	= 'gallery_manager';
			$this->load->view('admin_template',$data);
			
		}
				
	}
	
	/*
	*
	* Edit Gallery page function
	*
	* @access PUBLIC
	* @param Get Gallery tables records
	* @return View  page
	*
	*/
	
	public function edit_gallery() {
		
		$gallery_id = base64_decode(urldecode($this->uri->segment('3')));
		$TempImages = array();
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Gallery');
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
		
				$data['get_gallery_details'] 	= $this->gallerymodel->get_gallery_details($gallery_id)->row_array();
				$data['get_gallery_images'] 	= $this->gallerymodel->get_gallery_images($gallery_id)->result_array();	
			
				if (isset($data['get_gallery_images']))
				{
					foreach($data['get_gallery_images'] as $Images)
					{
						$OldTempName 		= $Images['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= gallery_temp_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
						$TempName = GenerateNewImageName($OldTempName, $NewImageName);
						
						ImageLibraryCopyToTemp($OldTempName, $TempName, $SourceURL,$DestinationURL);
						$PhysicalName = GetPhysicalNameFromPhysicalPath($Images['ImagePhysicalPath']);
		
						$TempImages[] = $this->image_model->addimages(USERID, $Images['content_id'],3,$Images['ImageCaption'], $Images['ImageAlt'], $PhysicalName, $TempName,  $Images['Image1Type'],  $Images['Image2Type'],  $Images['Image3Type'], $Images['Image4Type'], $Images['display_order']);
						
					}
				}
				
				$country_id 					= $data['get_gallery_details']['Country_ID'];
				$state_id						= $data['get_gallery_details']['State_ID'];
				$city_id 						= $data['get_gallery_details']['City_ID'];
				$agency_id 						= $data['get_gallery_details']['Agency_ID'];
				$tags 							= $data['get_gallery_details']['Tags'];
				$author_id						= $data['get_gallery_details']['Author_ID'];
				
				if(isset($tags) && trim($tags) != '') 
				$data['get_tags']				= $this->common_model->get_tags_by_id($tags);
				
				If($author_id != '')
				$data['select_author'] 			= get_authorname_by_id($author_id);
			
				If($state_id != '')			
				$data['select_state'] 			= get_statename_by_id($state_id);	
				If($city_id != '')
				$data['select_city'] 			= get_cityname_by_id($city_id);	
				$data['get_agency'] 			= $this->common_model->get_agency_details();
				$data['get_country'] 			= $this->common_model->get_country_details();
				
				$Count = 0;
				foreach($TempImages as $key=>$TempDetails) {
					
					$Result = $this->image_model->GetTempImages($TempDetails['image_id']);
					
					$data['temp_images'][$Count] = $Result[0];
					
					$Count++;
				}
				
				$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
		
				$data['title'] 					= 'Edit gallery';
				$data['template'] 				= 'image_gallery_add';
				$data['page_name'] 				= 'edit_gallery';
				
				/*
				echo "<pre>";
				print_r($data['temp_images']);
				exit;
				*/
				
				$this->load->view('admin_template', $data);
		
		} else {
			redirect(folder_name.'/common/access_permission/edit_gallery');
		}
	}
	
		/*
	*
	* Edit Gallery page function
	*
	* @access PUBLIC
	* @param Get Gallery tables records
	* @return View  page
	*
	*/
	
	public function edit_archive_gallery($year,$gallery_id) {
		
		$gallery_id = base64_decode(urldecode($gallery_id));
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Gallery');
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
		
			if ($this->image_model->DeleteTempAllImages(3))
			{
				
				$this->archive_db->select("*,tag_ids as Tags,author_id as Author_ID,agency_id as Agency_ID,country_id as Country_ID, state_id as State_ID, city_id as City_ID, canonical_url as Canonicalurl,allow_comments as Allowcomments,no_indexed as Noindexed, no_follow as Nofollow,summary_html as summaryHTML ,meta_title as MetaTitle, meta_description as MetaDescription, section_id as Section_id,created_by as Createdby, modified_by as Modifiedby, created_on as Createdon, modified_on as Modifiedon");
				$this->archive_db->from("gallery_".$year);
				$this->archive_db->where("content_id",$gallery_id);
				$Get = $this->archive_db->get();
			
				$data['get_gallery_details'] 	= $Get->row_array();
				
				$this->archive_db->select("image_id as ImageID,display_order");
				$this->archive_db->from("gallery_related_images_".$year);
				$this->archive_db->where("content_id",$gallery_id);
				$Get = $this->archive_db->get();
				
				$data['get_gallery_images'] = $Get->result_array();
			
				
				
				
				//$data['get_gallery_details'] 	= $this->gallerymodel->get_gallery_details($gallery_id)->row_array();
				//$data['get_gallery_images'] 	= $this->gallerymodel->get_gallery_images($gallery_id)->result_array();	
			
				if (isset($data['get_gallery_images']))
				{
					foreach($data['get_gallery_images'] as $ImagesID)
					{
						
						
						$Images = GetImageDetailsByContentId($ImagesID['ImageID']);	
						
						$OldTempName 		= $Images['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= gallery_temp_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
						$TempName = GenerateNewImageName($OldTempName, $NewImageName);
						
						ImageLibraryCopyToTemp($OldTempName, $TempName, $SourceURL,$DestinationURL);
						$PhysicalName = GetPhysicalNameFromPhysicalPath($Images['ImagePhysicalPath']);
		
						$this->image_model->addimages(USERID, $Images['content_id'],3,$Images['ImageCaption'], $Images['ImageAlt'], $PhysicalName, $TempName,  $Images['Image1Type'],  $Images['Image2Type'],  $Images['Image3Type'], $Images['Image4Type'], $ImagesID['display_order']);
						
					}
				}
				
				$country_id 					= $data['get_gallery_details']['Country_ID'];
				$state_id						= $data['get_gallery_details']['State_ID'];
				$city_id 						= $data['get_gallery_details']['City_ID'];
				$agency_id 						= $data['get_gallery_details']['Agency_ID'];
				$section_id 					= $data['get_gallery_details']['Section_id'];
				$tags 							= $data['get_gallery_details']['Tags'];
				$author_id						= $data['get_gallery_details']['Author_ID'];
				
				$url_array = explode("/",$data['get_gallery_details']['url']);
				$GetTitleFromURL = explode(".",end($url_array));
			
				$data['get_gallery_details']['url_title'] = str_replace("-".$gallery_id,"",$GetTitleFromURL[0]);
				
				
				if(isset($tags) && trim($tags) != '') 
				$data['get_tags']				= $this->common_model->get_tags_by_id($tags);
				
				If($author_id != '')
				$data['select_author'] 			= get_authorname_by_id($author_id);
			
				If($agency_id != '' && $agency_id != 0 )
				$data['select_agency'] 			= get_agency_by_id($agency_id);	
				If($state_id != '' && $state_id != 0 )
				$data['select_state'] 			= get_statename_by_id($state_id);	
				If($city_id != '' && $city_id != 0 )
				$data['select_city'] 			= get_cityname_by_id($city_id);	
			
				If($section_id != '' && $section_id != 0 ) {
				$section_details 			= get_section_by_id($section_id);	
				$data['select_section_name'] = $section_details['Sectionname'];
				$data['select_parent_name']  = get_sectionname_by_id($section_details['Section_id']);
				}
			
				$data['get_agency'] 			= $this->common_model->get_agency_details();
				$data['get_country'] 			= $this->common_model->get_country_details();
	
				$data['temp_images'] 			= $this->image_model->viewtempimage(USERID, 3);
				$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
		
				$data['archive_year']           = $year;
				$data['title'] 					= 'Edit Archive Gallery';
				$data['template'] 				= 'image_gallery_add';
				$data['page_name'] 				= 'edit_gallery';
				
				/*
				echo "<pre>";
				print_r($data);
				exit;
				*/
				
				$this->load->view('admin_template', $data);
			}
		
		} else {
			redirect(folder_name.'/common/access_permission/edit_gallery');
		}
	}
	
	
	public function gallery_datatable()
	
	{
		$this->gallerymodel->get_gallery_datatables();
	}
	
	public function get_gallery_preview_popup() {
		
			extract($_POST);
		 $gallery_images	= json_decode($gallery_images,true);
		$data['content_type']		= $content_type;
		$head_line 					= urldecode($head_line);
		$data['body_text']			= urldecode($body_text);
		$data['tags']				= json_decode($tags);

		$data['gallery_images']		= array();
		
		if($publishdate != '') 
		$data['publishdate']    	= $publishdate;	
		else 
		$data['publishdate']    	= date('dS  F Y h:i A');	
		
		if($last_update != '')
		$data['last_update']    	= $last_update;
		else 
		$data['last_update']    	=  date('dS  F Y h:i A');	

		
		
		if($section_id != '') {
	
				$section_id = urldecode($section_id);
				$data['url_structure'] = GenerateBreadCrumbBySectionId($section_id);
		}
						$agency_id = $agency_id;
						if($agency_id!=''){
						$data['author_name'] = get_agency_by_id($agency_id);
						}else{
						$data['author_name'] = '';
						}
		
		 $head_line = str_replace("<p","<span",$head_line);
		 $head_line = str_replace("</p>","</span>",$head_line);
		 
		$data['article_headline'] = $head_line;
			
			if(count($gallery_images) != 0) {
				
				foreach($gallery_images as $key=>$gallery) {
				
					$ImageNames = explode("/", $gallery['source']);
					$image_name 		=  end($ImageNames);
				
					$imagedetails 	= getimagesize(source_base_path . gallery_temp_image_path.$image_name);
					$imagewidth 	= $imagedetails[0];
                    $imageheight 	= $imagedetails[1];
					
					$data['gallery_images'][$key]['is_verticle']  = '';
					
				/*	if ($imageheight > $imagewidth) {
						$data['gallery_images'][$key]['source']	 = image_url.gallery_temp_image_path.$image_name;
						$data['gallery_images'][$key]['caption']	 = $gallery['image_caption'];
						$data['gallery_images'][$key]['alt']	 = $gallery['image_alt'];
						$data['gallery_images'][$key]['is_verticle'] = 'style="width:100%"';
					} else {  */
			
			if(file_exists(source_base_path.gallery_temp_image_path.$image_name)) {				
				$data['gallery_images'][$key]['source']	 = image_url.gallery_temp_image_path.$image_name;
				$data['gallery_images'][$key]['caption']	 = $gallery['image_caption'];
				$data['gallery_images'][$key]['alt']	 = $gallery['image_alt'];
				
				if($imagewidth > 600 && $imagewidth < 700) {
					$data['gallery_images'][$key]['is_verticle']  =  'style="width:70%"'; 
				} elseif($imagewidth < 600) {
					$data['gallery_images'][$key]['is_verticle']  =   'style="width:60%"';
				} 
				
			} else {
				
				$data['gallery_images'][$key]['source']	 = '';
				$data['gallery_images'][$key]['caption']	 = $gallery['image_caption'];
				$data['gallery_images'][$key]['alt']	 = $gallery['image_alt'];
				$data['gallery_images'][$key]['is_verticle'] = '';
				
				/*
					if(isset($image_name)) {
				
						$ImagePath 			= source_base_path.gallery_temp_image_path;
						
						$src 			= $ImagePath . $image_name;
						$ImageExtension = explode("/", $src);
						$LastArray 		= explode('.', end($ImageExtension));
						$extType 		= strtolower($LastArray[1]);
						
						if (!empty($src))
					{
						switch ($extType)
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
					
						if (!$src_img)
						{
							$result_value['status'] = 'error';
							$result_value['msg'] 	= "Failed to read the image file";
							return json_encode($result_value);
						}
						
						$size 		= getimagesize($src);
						$src_w 		= $size[0]; // natural width
						$src_h		= $size[1]; // natural height	
						
						
						
					
						$dst_w 		= 600;
						$dst_h 		= 390;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_600_390.",$image_name);
					
						$result = imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w,
 $src_h);
							if($result) {
							
								if(imagejpeg($dst_img, $dst, 30)) {
									$ImageSizeDetails 			= getimagesize($dst);
									$width 						= $ImageSizeDetails[0];
									$height 					= $ImageSizeDetails[1];
									$size 						= $ImageSizeDetails['bits'];
									$imagetype					= $ImageSizeDetails['mime'];
									$image_binary_file1 		= '';
									
									$image_binary_bool1 = true;
									$image1_type 		= 1;
									
									imagedestroy($dst_img);	
									
									$Null_value 		= 'NULL';
									$modifiedon 		= date('Y-m-d H:i:s');
									$content_id 		= 'NULL';
									
									//update_temp_images( $content_id, 'Y', $ImageDetails['caption'], $ImageDetails['alt_tag'], addslashes($image_binary_file1) ,$Null_value,$Null_value,$Null_value,1,$Null_value,$Null_value,$Null_value, $modifiedon, $article_image_id,'image_600_390');
									
								$data['gallery_images'][$key]['source']	 = image_url.gallery_temp_image_path.str_replace('.','_600_390.',@$image_name);
								$data['gallery_images'][$key]['caption']	 = $gallery['image_caption'];
								$data['gallery_images'][$key]['alt']	 = $gallery['image_alt'];
								$data['gallery_images'][$key]['is_verticle'] = '';
									
								}
							}
							
					}
					
					
				} */
			}
				//	}
			}
		}
		
		/*
		echo "<pre>";
		print_r($data);
		exit; 
		*/
		
		echo $this->load->view('admin/article_preview_popup',$data);
	}
}
	
/* End of file gallery_manager.php */
/* Location: ./application/controllers/admin/gallery_manager.php */