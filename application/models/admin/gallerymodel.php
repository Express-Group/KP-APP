<?php
/**
 * Gallerymodel Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */
 defined('BASEPATH') OR exit('No direct script access allowed');
class Gallerymodel extends CI_Model

{
	public function __construct()

	{
		parent::__construct();
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
		$this->load->model('admin/live_content_model');
	}
	
	/*
	*
	* Create new Gallery
	*
	* @access Public
	* @param POST values from image_gallery_add file
	* @return Redirect to Gallery Manager after insert gallery master
	*
	*/
	
	public function insert_gallery() {
		
		/*
		echo "<pre>";
		print_r($_POST);
		exit;
		*/
		
		extract($_POST);
		
		
		$data 				= json_decode($txtGalleryData);
		
		$null_value 		= "NULL";
		$gallery_details 	= $this->get_additional_gallery_details();
		
		/*
		echo "<pre>";
		print_r($gallery_details);
		exit;
		*/
		
		$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
		
		if(isset($txtTags))
		$txtTags 			= PrepareTagInputValue($txtTags);
		else 
		$txtTags 			= '';	
		
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
		
		create_image_folder( $Year, $Month, $Day);
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		
		if (trim($txtgalleryname) != '')
		{
			
			$this->db->trans_begin();
			$this->live_db->trans_begin();
			
			$this->db->query('CALL add_gallerymaster(NULL,"'.addslashes($gallery_details['UrlTitle']).'","'.addslashes($txtgalleryname).'","'.addslashes($gallery_details['url']).'","'.addslashes($txtSummary).'","'.addslashes($txtTags).'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$gallery_details['PublishStartDate'].'","'.$gallery_details['cbNoIndex'].'","'.$gallery_details['cbNoFollows'].'","'.addslashes($txtCanonicalUrl).'","'.$gallery_details['cbAllowComments'].'","'.$ddMainSection.'",'.$gallery_details['ddAgency'].','.$gallery_details['ddByLine'].','.$gallery_details['ddCountry'].','.$gallery_details['ddState'].','.$gallery_details['ddCity'].',"'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'. date("Y-m-d H:i:s").'",@insert_id)');
	
			$result 	= $this->db->query("SELECT @insert_id")->result_array();
			$gallery_id = $result[0]['@insert_id'];
		
		
			if($gallery_id != '' && $gallery_id != 'NULL'  && $gallery_id != 0) {
				
				$gallery_details['url'] = $gallery_details['url']."-".$gallery_id.".html";
				
				$this->db->query('CALL update_url_structure('.$gallery_id.',"'.addslashes($gallery_details['url']).'",3)');
				
				if ($txtGalleryData != '{}' && $txtGalleryData != '') {
					
				$temp_images = $this->image_model->viewtempimage(USERID, 3);
				$this->common_model->common_resize_all_images($temp_images);
				
				$data 				= json_decode($txtGalleryData);
				$SourceURL  		= gallery_temp_image_path;
				$DestinationURL 	= imagelibrary_image_path;
				
					$UploadCount = 0;
					
					foreach($data as $key=>$result)
					{
					
						$query = $this->db->query("CALL tempimagedetails('" . $result->image_id . "','')");
						$temp_image = $query->row_array();
						
						if(isset($temp_image['image_name']) && file_exists(source_base_path.$SourceURL.$temp_image['image_name'])) {  
						
						$image_name = explode('.',$temp_image['image_name']);
						$NewImageName = $result->physical_name.'.'.$image_name[1];
					
						IF((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ( $temp_image['crop_resize_status'] == 1 && $temp_image['save_status'] == 1 ) || (  trim($temp_image['caption']) != trim($result->image_caption) || trim($temp_image['alt_tag']) != trim($result->image_alt) || trim($temp_image['physical_name']) != trim($result->physical_name) ) )	{
							
							ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $SourceURL, $DestinationURL, $FolderMapping);
							
							///// Delete the Temp Images in Table /////
							
							$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
							
							/* New coding */
							
							$temp_image['caption']      = trim($result->image_caption);
							$temp_image['alt_tag'] 		= trim($result->image_alt);
							$temp_image['image_name'] 	= $FolderMapping.$NewImageName;
							
							$image_id =  add_image_master($temp_image);
						
							$this->db->query('CALL add_gallery_related_images ("' . $gallery_id . '","' .$image_id . '","'.($key+1).'")');
							
						} else {
							
							$this->db->query('CALL add_gallery_related_images ("' . $gallery_id . '","' .$temp_image['imagecontent_id'] . '","'.($key+1).'")');
							
							$ImageDetails = GetImageDetailsByContentId($temp_image['imagecontent_id']);
							
							$PhysicalName = end(explode("/",$ImageDetails['ImagePhysicalPath']));
							
							$PhysicalPath = str_replace($PhysicalName,"",$ImageDetails['ImagePhysicalPath']);
							
							ImageDeleteAndPasteToLibrary($temp_image['image_name'],$PhysicalName, $SourceURL, $DestinationURL, $PhysicalPath);
							
							$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
							
						}
						
						$UploadCount++;
						
						} else {
								$this->db->trans_rollback();
								$this->db->query("CALL deletetempimage('" . $result->image_id . "')");
						}
					} 
				}
			}
			
			if($UploadCount == 0 && $txtStatus != 'D') {
				$this->session->set_flashdata('error', "Problem while inserting. Please try again");
				redirect(folder_name.'/gallery_manager');
			}
			
			if($txtStatus == 'P') {
							
				$ImageDetails = $this->get_gallery_images($gallery_id)->result_array();
				
				if(isset($ImageDetails)) {
					foreach($ImageDetails as $key=>$Image) {
					
				if($key == 0) {
					$gallery_details['LiveGalleryDetails']['first_image_path']  	= $Image['ImagePhysicalPath'];
					$gallery_details['LiveGalleryDetails']['first_image_title']  	= $Image['ImageCaption'];
					$gallery_details['LiveGalleryDetails']['first_image_alt'] 	 	= $Image['ImageAlt'];
					$gallery_details['LiveGalleryDetails']['content_id']  			= $gallery_id;
					$gallery_details['LiveGalleryDetails']['url']					= $gallery_details['url'];
					$gallery_details['LiveGalleryDetails']['status']				= 'P';
					
					$this->insert_live_gallery($gallery_details['LiveGalleryDetails']);
				}
				
				$GalleryRelatedImages =  array(
										"content_id" 					=> $gallery_id,
										"gallery_image_path" 			=> $Image['ImagePhysicalPath'],
										"gallery_image_title" 			=> $Image['ImageCaption'],
										"gallery_image_alt" 			=> $Image['ImageAlt'],
										"display_order"					=> $Image['display_order']
										);
					$this->insert_live_gallery_related_images($GalleryRelatedImages);
					}
				
				}
			}
			
			$this->insert_gallery_mapping($gallery_id);
			
			
			if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				return TRUE;
			}
			
		}
		
	}
	
		
	/*
	*
	* Insert Gallery Details in Live gallery table
	*
	* @access Public
	* @param Set the Gallery Details in Array format
	* @return TRUE or FALSE
	*
	*/
	
	public function insert_live_gallery($gallery_details) {

		$this->live_db->query('CALL add_gallery('.$gallery_details["content_id"].',NULL,'.$gallery_details["section_id"].',"'.addslashes($gallery_details["section_name"]).'",'.$gallery_details["parent_section_id"].',"'.addslashes($gallery_details["parent_section_name"]).'",'.$gallery_details["grant_section_id"].',"'.addslashes($gallery_details["grant_parent_section_name"]).'","'.$gallery_details["publish_start_date"].'","'.$gallery_details["last_updated_on"].'","'.addslashes($gallery_details["title"]).'","'.addslashes($gallery_details["url"]).'","'.addslashes($gallery_details["summary_html"]).'","'.addslashes($gallery_details["first_image_path"]).'","'.addslashes($gallery_details["first_image_title"]).'","'.addslashes($gallery_details["first_image_alt"]).'",'.$gallery_details["hits"].',"'.addslashes($gallery_details["tags"]).'",'.$gallery_details["allow_comments"].',"'.addslashes($gallery_details["agency_name"]).'","'.addslashes($gallery_details["author_name"]).'","'.addslashes($gallery_details["country_name"]).'","'.addslashes($gallery_details["state_name"]).'","'.addslashes($gallery_details["city_name"]).'",'.$gallery_details["no_indexed"].','.$gallery_details["no_follow"].',"'.addslashes($gallery_details["canonical_url"]).'","'.addslashes($gallery_details["meta_Title"]).'","'.addslashes($gallery_details["meta_description"]).'","'.$gallery_details['status'].'")');

		return $this->live_db->query('CALL add_short_content_details ('.$gallery_details["content_id"].',"'.addslashes(strip_tags($gallery_details["title"])).'","'.addslashes($gallery_details["tags"]).'","'.addslashes($gallery_details["summary_plain_text"]).'","",'.$gallery_details["section_id"].',3)');
			
	}
	
	/*
	*
	* Insert Gallery Image Details in Live gallery related images table
	*
	* @access Public
	* @param Set the Gallery Details in Array format
	* @return TRUE or FALSE
	*
	*/
	
	public function insert_live_gallery_related_images($gallery_details) {
		return $this->live_db->query('CALL  add_gallery_related_images ("'.$gallery_details["content_id"].'","'.addslashes($gallery_details["gallery_image_path"]).'","'.addslashes($gallery_details["gallery_image_title"]).'","'.addslashes($gallery_details["gallery_image_alt"]).'","'.$gallery_details["display_order"].'")');
	}
	
	
	/*
	*
	* Update existing Gallery
	*
	* @access Public
	* @param POST values from image_gallery_add file
	* @return Redirect to Gallery Manager after update gallery details
	*
	*/
	
	public function update_gallery($gallery_id) {
			
		/*
		echo "<pre>";
		print_r($_POST);
		exit;
		*/
		
		extract($_POST);
		
		$data 				= json_decode($txtGalleryData);
		
		$null_value 		= "NULL";
		$gallery_details 	= $this->get_additional_gallery_details();
		
		/*
		echo "<pre>";
		print_r($gallery_details);
		exit;
		*/
		
		$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
		
		if(isset($txtTags))
		$txtTags 			= PrepareTagInputValue($txtTags);
		else 
		$txtTags 			= '';	
		
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
		
		create_image_folder( $Year, $Month, $Day);
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		
		if (trim($txtgalleryname) != '')
		{
			$this->db->trans_begin();
			$this->live_db->trans_begin();
			
			$gallery_details['url'] = $gallery_details['url']."-".$gallery_id.".html";
			
			$this->db->query('CALL update_gallerymaster('.$gallery_id.',"'.addslashes($gallery_details['UrlTitle']).'","'.addslashes($txtgalleryname).'","'.addslashes($gallery_details['url']).'","'.addslashes($txtSummary).'","'.addslashes($txtTags).'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$gallery_details['PublishStartDate'].'","'.$gallery_details['cbNoIndex'].'","'.$gallery_details['cbNoFollows'].'","'.addslashes($txtCanonicalUrl).'","'.$gallery_details['cbAllowComments'].'","'.$ddMainSection.'",'.$gallery_details['ddAgency'].','.$gallery_details['ddByLine'].','.$gallery_details['ddCountry'].','.$gallery_details['ddState'].','.$gallery_details['ddCity'].',"'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'")');
	
			if($this->delete_gallery_image($gallery_id)) {
				
					if ($txtGalleryData != '{}' && $txtGalleryData != '') {
						
				$temp_images = $this->image_model->viewtempimage(USERID, 3);
				$this->common_model->common_resize_all_images($temp_images);
				
				$data 				= json_decode($txtGalleryData);
				$SourceURL  		= gallery_temp_image_path;
				$DestinationURL 	= imagelibrary_image_path;
				
					$UploadCount = 0;
					
					foreach($data as $key=>$result)
					{
					
						$query = $this->db->query("CALL tempimagedetails('" . $result->image_id . "','')");
						$temp_image = $query->row_array();
						
						$image_name = explode('.',$temp_image['image_name']);
						$NewImageName = $result->physical_name.'.'.$image_name[1];
						
						if(isset($temp_image['image_name']) && file_exists(source_base_path.$SourceURL.$temp_image['image_name'])) {  
						
						//echo "<pre>";
						//print_r($result);
						
						IF((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ( $temp_image['crop_resize_status'] == 1 && $temp_image['save_status'] == 1 ) || (  trim($temp_image['caption']) != trim($result->image_caption) || trim($temp_image['alt_tag']) != trim($result->image_alt) || trim($temp_image['physical_name']) != trim($result->physical_name) ) )	{
							
							ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $SourceURL, $DestinationURL, $FolderMapping);
							
							///// Delete the Temp Images in Table /////
							
							$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
							
							/* New coding */
							
							$temp_image['caption']      = trim($result->image_caption);
							$temp_image['alt_tag'] 		= trim($result->image_alt);
							$temp_image['image_name'] 	= $FolderMapping.$NewImageName;
							
							$image_id =  add_image_master($temp_image);
						
							$this->db->query('CALL add_gallery_related_images ("' . $gallery_id . '","' .$image_id . '","'.($key+1).'")');
							
						} else {
							
							$this->db->query('CALL add_gallery_related_images ("' . $gallery_id . '","' .$temp_image['imagecontent_id'] . '","'.($key+1).'")');
							
							$ImageDetails = GetImageDetailsByContentId($temp_image['imagecontent_id']);
							
							$PhysicalName = end(explode("/",$ImageDetails['ImagePhysicalPath']));
							
							$PhysicalPath = str_replace($PhysicalName,"",$ImageDetails['ImagePhysicalPath']);
							
							ImageDeleteAndPasteToLibrary($temp_image['image_name'],$PhysicalName, $SourceURL, $DestinationURL, $PhysicalPath);
							
							$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
							
						}
						
						$UploadCount++;
						
						} else {
							$this->db->trans_rollback();
							$this->db->query("CALL deletetempimage('" . $result->image_id . "')");
						}
					
					} 
				}
			}
			
			if($UploadCount == 0 && $txtStatus != 'D')  {
				$this->session->set_flashdata('error', "Problem while updating. Please try again");
				redirect(folder_name.'/gallery_manager');
			}
				
			if($txtStatus == 'P') {
			
				$ImageDetails = $this->get_gallery_images($gallery_id)->result_array();
			
				if($this->live_content_model->check_livecontents($gallery_id,3) <= 0) {
					
					if(isset($ImageDetails)) {
						foreach($ImageDetails as $key=>$Image) {
						
					if($key == 0) {
						$gallery_details['LiveGalleryDetails']['first_image_path']  	= $Image['ImagePhysicalPath'];
						$gallery_details['LiveGalleryDetails']['first_image_title']  	= $Image['ImageCaption'];
						$gallery_details['LiveGalleryDetails']['first_image_alt'] 	 	= $Image['ImageAlt'];
						$gallery_details['LiveGalleryDetails']['content_id']  			= $gallery_id;
						$gallery_details['LiveGalleryDetails']['url']					= $gallery_details['url'];
						$gallery_details['LiveGalleryDetails']['status']				= 'P';
						
						$this->insert_live_gallery($gallery_details['LiveGalleryDetails']);
					}
					
					$GalleryRelatedImages =  array(
											"content_id" 					=> $gallery_id,
											"gallery_image_path" 			=> $Image['ImagePhysicalPath'],
											"gallery_image_title" 			=> $Image['ImageCaption'],
											"gallery_image_alt" 			=> $Image['ImageAlt'],
											"display_order"					=> $Image['display_order']
											);
						$this->insert_live_gallery_related_images($GalleryRelatedImages);
						}
					
					}
					
				} else {
					
					 $this->live_db->query("CALL delete_gallery_related_images (". $gallery_id.")");
						
								if(isset($ImageDetails)) {
									foreach($ImageDetails as $key=>$Image) {
								
										if($key == 0) {
										$gallery_details['LiveGalleryDetails']['first_image_path']  	= $Image['ImagePhysicalPath'];
										$gallery_details['LiveGalleryDetails']['first_image_title']  	= $Image['ImageCaption'];
										$gallery_details['LiveGalleryDetails']['first_image_alt'] 	 	= $Image['ImageAlt'];
										$gallery_details['LiveGalleryDetails']['content_id']  			= $gallery_id;
										$gallery_details['LiveGalleryDetails']['url']					= $gallery_details['url'];
										$gallery_details['LiveGalleryDetails']['status']				= 'P';
										
										$this->update_live_gallery($gallery_details['LiveGalleryDetails']);
										}
										
										$GalleryRelatedImages =  array(
																"content_id" 					=> $gallery_id,
																"gallery_image_path" 			=> $Image['ImagePhysicalPath'],
																"gallery_image_title" 			=> $Image['ImageCaption'],
																"gallery_image_alt" 			=> $Image['ImageAlt'],
																"display_order"					=> $Image['display_order']
																);
										$this->insert_live_gallery_related_images($GalleryRelatedImages);
									}
								}
				}
			}
			
			if($txtStatus == 'U') {
				 $this->live_db->query("CALL delete_livecontents (". $gallery_id.",3)");
			}
			
	
			if($this->delete_content_mapping($gallery_id))
				$this->insert_gallery_mapping($gallery_id);
			
			if ($this->db->trans_status() === FALSE && $this->live_db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				return TRUE;
			}
			
		}
			
	}
	
	
		
	/*
	*
	* Update existing Gallery
	*
	* @access Public
	* @param POST values from image_gallery_add file
	* @return Redirect to Gallery Manager after update gallery details
	*
	*/
	
	public function update_archive_gallery($year, $gallery_id) {
			
		/*
		echo "<pre>";
		print_r($_POST);
		exit;
		*/
		
		extract($_POST);
		
		$temp_images = $this->image_model->viewtempimage(USERID, 3);
		$this->common_model->common_resize_all_images($temp_images);
		
		$data 				= json_decode($txtGalleryData);
		
		$null_value 		= "NULL";
		$gallery_details 	= $this->get_additional_gallery_details();
		
		/*
		echo "<pre>";
		print_r($gallery_details);
		exit;
		*/
		
		$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
		
		if(isset($txtTags))
		$txtTags 			= PrepareTagInputValue($txtTags);
		else 
		$txtTags 			= '';	
		
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
		
		create_image_folder( $Year, $Month, $Day);
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		
		if (trim($txtgalleryname) != '')
		{
			$this->archive_db->trans_begin();
			$this->db->trans_begin();
			
			
				$update_archive_details = $gallery_details['LiveGalleryDetails'];
				
				$update_archive_details['url'] = $gallery_details['url']."-".$gallery_id.".html";
				
				unset($update_archive_details['ecenic_id']);
				
				$update_archive_details['tag_ids']  = $txtTags;
				$update_archive_details['agency_id']  = $gallery_details['ddAgency'];
				$update_archive_details['author_id']  = $gallery_details['ddByLine'];
				$update_archive_details['country_id']  = $gallery_details['ddCountry'];
				$update_archive_details['state_id']  = $gallery_details['ddState'];
				$update_archive_details['city_id']  = $gallery_details['ddCity'];
				
				$update_archive_details['modified_by'] = get_userdetails_by_id(USERID);
				$update_archive_details['modified_on'] = $update_archive_details['last_updated_on'];
				
				$this->archive_db->where("content_id",$gallery_id);
				
				if($this->archive_db->delete("gallery_related_images_".$year)) {
				
					if ($txtGalleryData != '{}' && $txtGalleryData != '') {
				
				$data 				= json_decode($txtGalleryData);
				$SourceURL  		= gallery_temp_image_path;
				$DestinationURL 	= imagelibrary_image_path;
				
					foreach($data as $key=>$result)
					{
					
						$query = $this->db->query("CALL tempimagedetails('" . $result->image_id . "','')");
						$temp_image = $query->row_array();
						
						$image_name = explode('.',$temp_image['image_name']);
						$NewImageName = $result->physical_name.'.'.$image_name[1];
					
						if(isset($temp_image['imagecontent_id']) && $temp_image['imagecontent_id'] != ''  &&  $temp_image['imagecontent_id'] != 'NULL' && $temp_image['imagecontent_id'] != 0  && trim($temp_image['caption']) == trim($result->image_caption) && trim($temp_image['alt_tag']) == trim($result->image_alt) && trim($temp_image['physical_name']) == trim($result->physical_name))
						{	
					
							$Images = GetImageDetailsByContentId($temp_image['imagecontent_id'] );	
							
							$insert_array['content_id'] = $gallery_id;
							$insert_array['image_id'] = $Images['content_id'];
							$insert_array['gallery_image_path'] = $Images['ImagePhysicalPath'];
							$insert_array['gallery_image_title'] = $Images['ImageCaption'];
							$insert_array['gallery_image_alt'] = $Images['ImageAlt'];
							$insert_array['display_order'] = ($key+1);
							
							$this->archive_db->insert("gallery_related_images_".$year,$insert_array);
							
							if($key == 0) {
								$update_archive_details['first_image_path'] 	= $Images['ImagePhysicalPath'];
								$update_archive_details['first_image_title'] 	= $Images['ImageCaption'];
								$update_archive_details['first_image_alt'] 		= $Images['ImageAlt'];
							}
							
							ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $SourceURL, $DestinationURL, $FolderMapping);
							
							$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
					
						} else {
							
							ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $SourceURL, $DestinationURL, $FolderMapping);
							
							///// Delete the Temp Images in Table /////
							
							$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
							
							/* New coding */
							if (trim($temp_image['caption']) != '')
							{
									$temp_image['caption']      = trim($result->image_caption);
									$temp_image['alt_tag'] 		= trim($result->image_alt);
									$temp_image['image_name'] 	= $FolderMapping.$NewImageName;
									
									$image_id =  add_image_master($temp_image);
									
									$Images = GetImageDetailsByContentId($image_id);	
									
									$insert_array['content_id'] 		 = $gallery_id;
									$insert_array['image_id'] 			 = $Images['content_id'];
									$insert_array['gallery_image_path']  = $Images['ImagePhysicalPath'];
									$insert_array['gallery_image_title'] = $Images['ImageCaption'];
									$insert_array['gallery_image_alt'] 	 = $Images['ImageAlt'];
									$insert_array['display_order'] 		 = ($key+1);
									
									$this->archive_db->insert("gallery_related_images_".$year,$insert_array);
									
									if($key == 0) {
										$update_archive_details['first_image_path'] 	= $Images['ImagePhysicalPath'];
										$update_archive_details['first_image_title'] 	= $Images['ImageCaption'];
										$update_archive_details['first_image_alt'] 		= $Images['ImageAlt'];
									}
									
									
								
							}
						}
					} 
				}
				
			}
			
			if(!empty($update_archive_details)) {
				$this->archive_db->where("content_id",$gallery_id);
				$this->archive_db->update("gallery_".$year,$update_archive_details);
			}
		
		
			$this->archive_db->where("content_id",$gallery_id);
			$this->archive_db->delete("gallery_section_mapping_".$year);
				
				
			$insert_array 	= array();
			$insert_array['content_id'] = $gallery_id;
			$insert_array['section_id'] = $ddMainSection;
		
			$this->archive_db->insert("gallery_section_mapping_".$year,$insert_array);
		
			if (isset($cbSectionMapping))
			{
				$cbSectionMapping = array_diff($cbSectionMapping, array($ddMainSection));
			
				foreach($cbSectionMapping as $mapping)
				{
					$insert_array 	= array();
					$insert_array['content_id'] = $gallery_id;
					$insert_array['section_id'] = $mapping;
					
					$this->archive_db->insert("gallery_section_mapping_".$year,$insert_array);
					
				}
			}
			
			if ($this->db->trans_status() === FALSE && $this->archive_db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->archive_db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				$this->archive_db->trans_commit();
				return TRUE;
			}
			
		}
			
	}
	
	
	/*
	*
	* Update Gallery Details in Gallery master table
	*
	* @access PUBLIC
	* @param Updated Gallery Details in Array Format
	* @return TRUE or FALSE
	*
	*/
	
	public function update_live_gallery($gallery_details) {
		
		$this->live_db->query('CALL update_gallery('.$gallery_details["content_id"].','.$gallery_details["section_id"].',"'.addslashes($gallery_details["section_name"]).'",'.$gallery_details["parent_section_id"].',"'.addslashes($gallery_details["parent_section_name"]).'",'.$gallery_details["grant_section_id"].',"'.addslashes($gallery_details["grant_parent_section_name"]).'","'.$gallery_details["publish_start_date"].'","'.$gallery_details["last_updated_on"].'","'.addslashes($gallery_details["title"]).'","'.addslashes($gallery_details["url"]).'","'.addslashes($gallery_details["summary_html"]).'","'.addslashes($gallery_details["first_image_path"]).'","'.addslashes($gallery_details["first_image_title"]).'","'.addslashes($gallery_details["first_image_alt"]).'","'.addslashes($gallery_details["tags"]).'",'.$gallery_details["allow_comments"].',"'.addslashes($gallery_details["agency_name"]).'","'.addslashes($gallery_details["author_name"]).'","'.addslashes($gallery_details["country_name"]).'","'.addslashes($gallery_details["state_name"]).'","'.addslashes($gallery_details["city_name"]).'",'.$gallery_details["no_indexed"].','.$gallery_details["no_follow"].',"'.addslashes($gallery_details["canonical_url"]).'","'.addslashes($gallery_details["meta_Title"]).'","'.addslashes($gallery_details["meta_description"]).'","'.$gallery_details['status'].'")');

		return $this->live_db->query('CALL update_short_content_details ('.$gallery_details["content_id"].',"'.addslashes(strip_tags($gallery_details["title"])).'","'.addslashes($gallery_details["tags"]).'","'.addslashes($gallery_details["summary_plain_text"]).'","",'.$gallery_details["section_id"].',3)');
		
	}
	
	
	/*
	*
	* Insert the gallery section mapping data
	*
	* @access Public
	* @param  gallery_id from gallery master table
	* @return TRUE;
	*/
	
	public function insert_gallery_mapping($gallery_id)

	{
		extract($_POST);
		/*
		echo "<pre>";
		print_r($cbSectionMapping);
		exit;
		*/
		if (isset($gallery_id) && $gallery_id != '')
		{
			
			$insert_array 	= array();
			$insert_array[] = $gallery_id;
			$insert_array[] = $ddMainSection;
			
			$result = implode('","', $insert_array);
			
			$gallery_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",3)');
			$gallery_mapping->result();
			
			 if($this->input->post('txtStatus') == 'P') {
			
			$result = implode('","', $insert_array);
			$gallery_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",3)');
			
			 }
			
			if (isset($cbSectionMapping))
			{
				$cbSectionMapping = array_diff($cbSectionMapping, array($ddMainSection));
				
				foreach($cbSectionMapping as $mapping)
				{
					$insert_array 	= array();
					$insert_array[] = $gallery_id;
					$insert_array[] = $mapping;
					
					$result = implode('","', $insert_array);
					
					$gallery_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",3)');
					$gallery_mapping->result();
					
				
					 if($this->input->post('txtStatus') == 'P') {
						
						$live_insert_array 		= array();
						
						$live_insert_array[] 	= $gallery_id;
						$live_insert_array[] 	= $mapping;

						$result = implode('","', $live_insert_array);
						$gallery_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",3)');
						
					}  
					
				}
			}
		}
		
		return TRUE;
	}
	/*
	*
	* Delete the gallery section mapping data
	*
	* @access Public
	* @param  gallery_id from gallery master table
	* @return TRUE or FALSE
	*/
	public function delete_content_mapping($content_id)

	{
		
		if($this->input->post('txtStatus') == 'P' || $this->input->post('txtStatus') == 'U')	
		$query = $this->live_db->query("CALL delete_section_mapping (". $content_id.",3)");
		
		$content_mapping = $this->db->query('CALL delete_content_mapping (' . $content_id . ',3)');
		return $content_mapping;
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
		
				$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
				$SourceURL  		= imagelibrary_image_path;
				$DestinationURL		= gallery_temp_image_path;
				
				$ImageDetails 	= GetImageDetailsByContentId($content_id);
				
				$path = $ImageDetails['ImagePhysicalPath'];
				
				$NewPath = GenerateNewImageName($path, $NewImageName);
				
				ImageLibraryCopyToTemp($path,$NewPath, $SourceURL, $DestinationURL);
				$path = $NewPath;
			
				$createdon 		= $modifiedon = date('Y-m-d H:i:s');
				
				if (isset($caption))
				{
				
					$Image1Type 	= $ImageDetails['Image1Type'];
					$Image2Type 	= $ImageDetails['Image2Type'];
					$Image3Type 	= $ImageDetails['Image3Type'];
					$Image4Type 	= $ImageDetails['Image4Type'];
					
					$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
					
					$query 				= $this->db->query("CALL insert_temp_images('" . USERID . "'," . $content_id . ",3,'" . addslashes($caption) . "','" . addslashes($alt) . "','".addslashes($PhysicalName)."','".addslashes($path)."',".$Image1Type.",".$Image2Type.",".$Image3Type.",".$Image4Type.",1,'" . $createdon . "','" . $modifiedon . "',@insert_id)");
					
					$result 			= $this->db->query("SELECT @insert_id")->result_array();
					$image_temp_id 		= $result[0]['@insert_id'];
					$data['image_id'] 	= $image_temp_id;
					$data['source'] 	= image_url.gallery_temp_image_path.$path;
					
					$Physical_extension_array = explode(".",$path);
					
					$data['caption'] 	= $caption;
					$data['alt'] 		= $alt;
					
					$data['physical_name'] 		= $PhysicalName;
					$data['physical_extension'] = $Physical_extension_array[1];
					
					$data['imagecontent_id'] 		= $content_id;
					$data['image1_type'] = $Image1Type; 
					$data['image2_type'] = $Image2Type;
					$data['image3_type'] = $Image3Type;
					$data['image4_type'] = $Image4Type;
					
				}
		
		echo json_encode($data);
	
	}
	
	
	/*
	*
	* Generate the Additional Information From Gallery Add Form
	*
	* @access Public
	* @param POST values from Gallery Form
	* @return Set the Gallery Data ( CMS & LIVE )
	*/
	
	public function get_additional_gallery_details()

	{
		
		extract($_POST);
		
			if ($txtPublishStartDate != '')
			$data['PublishStartDate'] = date('Y-m-d H:i', strtotime($txtPublishStartDate));
			
			if(trim($txtUrlTitle) == '')
			$data['UrlTitle'] = addslashes(trim(strip_tags($txtgalleryname)));
			else
			$data['UrlTitle'] = trim($txtUrlTitle);
		
			$data['UrlTitle'] = RemoveSpecialCharacters($data['UrlTitle']);
			$data['UrlTitle'] = mb_strtolower(join( "-",( explode(" ",$data['UrlTitle']) ) ));
		
			if(!isset($ddAgency))
			$ddAgency = 0;
		
			$MainSection = get_section_by_id($ddMainSection);
					
			$Year =  date('Y', strtotime($data['PublishStartDate']));
			$Month =  date('M', strtotime($data['PublishStartDate']));
			$Date =  date('d', strtotime($data['PublishStartDate']));
			
		
			$data['url']   = mb_strtolower(join( "-",( explode(" ",@$MainSection['URLSectionStructure'] ))))."/".$Year."/".mb_strtolower($Month)."/".$Date."/".$data['UrlTitle'];
		

		if($ddAgency == '' && $ddAgency == 0) 
			$data['ddAgency'] = "NULL";
		else 
			$data['ddAgency'] = $ddAgency;
		
		$AuthorType = 1;
		
		if(!isset($ddAgency)) {
			$ddAgency = 'NULL';
			$AuthorType = 2;
		}
		
		if($ddAgency == '') {
			$ddAgency = 'NULL';
			$AuthorType = 1;
		}
		
			if($ddByLine == '' && trim($txtByLine) != '' ) {
				
				$this->db->select("Author_id");
				$this->db->from("authormaster");
				$this->db->where("authorType",trim($AuthorType));
				$this->db->like("AuthorName",trim(addslashes($txtByLine)));
				$get_result = $this->db->get();
				
				$AuthorDetails = $get_result->result_array();
				
				if(isset($AuthorDetails[0]['Author_id']))
					$data['ddByLine'] = $AuthorDetails[0]['Author_id'];
				else
					$data['ddByLine'] = add_bylinertxt(trim(addslashes($txtByLine)), trim($ddAgency),trim($AuthorType),USERID);
				
			} else  {
				
				if($ddByLine == '' && trim($txtByLine) == '')
				$data['ddByLine'] = "NULL";
				else 
				$data['ddByLine'] = $ddByLine;
			}
		
		
		$data['cbAllowComments'] 	= 1;
		
		if (isset($cbNoIndex) && $cbNoIndex == 'on') $data['cbNoIndex'] = 1;
		else $data['cbNoIndex'] = 0;
		if (isset($cbNoFollows) && $cbNoFollows == 'on') $data['cbNoFollows'] = 1;
		else $data['cbNoFollows'] = 0;
		
		if ($ddCountry == '') $data['ddCountry'] = "NULL";
		else $data['ddCountry'] = $ddCountry;
		if ($ddCountry == '') $data['ddCountry'] = "NULL";
		else $data['ddCountry'] = $ddCountry;
		if ($ddState == '') $data['ddState'] = "NULL";
		else $data['ddState'] = $ddState;
		if ($ddCity == '') $data['ddCity'] = "NULL";
		else $data['ddCity'] = $ddCity;
		
		$data = array_map('trim',$data);
		
		//if($txtStatus == 'P') {
		
		# Start the Live gallery Table Details 
		
		$LiveGalleryDetails = array();
		
		
		$LiveGalleryDetails['ecenic_id'] 								= 'NULL';
		$LiveGalleryDetails['section_id'] 								= 'NULL';
		$LiveGalleryDetails['section_name'] 							= '';
		$LiveGalleryDetails['parent_section_id'] 						= 'NULL';
		$LiveGalleryDetails['parent_section_name'] 						= '';	
		$LiveGalleryDetails['grant_section_id'] 						= 'NULL';
		$LiveGalleryDetails['grant_parent_section_name'] 				= '';
		
		$LiveGalleryDetails['hits']											= 0;
		
		$LiveGalleryDetails['allow_comments']								= 0;
		$LiveGalleryDetails['no_indexed']									= 0;
		$LiveGalleryDetails['no_follow']									= 0;
		
		$LiveGalleryDetails['agency_name'] 									= '';
		$LiveGalleryDetails['author_name']									= '';
 		$LiveGalleryDetails['country_name'] 								= '';
		$LiveGalleryDetails['state_name'] 									= '';
		$LiveGalleryDetails['city_name'] 									= '';
		$LiveGalleryDetails['tags']											= '';
		$LiveGalleryDetails['status'] 										= $txtStatus;
		
		$LiveGalleryDetails['url'] 											= $data['url'] ;
	
		$LiveGalleryDetails['publish_start_date']							= $data['PublishStartDate'];
			
				
			if($data['ddByLine'] != "NULL" && $LiveGalleryDetails['author_name'] == '' ) {
				
				$LiveGalleryDetails['author_name'] 		= $txtByLine;		
			}			
			
			$MainSection = get_section_by_id($ddMainSection);
			
			if(isset($MainSection)) {
			
			$LiveGalleryDetails['section_id'] 			= $MainSection['Section_id'];
			$LiveGalleryDetails['section_name'] 		= $MainSection['Sectionname'];
			
				if(isset($MainSection['ParentSectionID']) && $MainSection['ParentSectionID'] != '') {
					
					$ParentMainSection = get_section_by_id($MainSection['ParentSectionID']);
					
					if(isset($ParentMainSection['Section_id'])) {
					$LiveGalleryDetails['parent_section_id'] 						= 	$ParentMainSection['Section_id'];
					$LiveGalleryDetails['parent_section_name'] 						= 	$ParentMainSection['Sectionname'];
					}
					
					if(isset($ParentMainSection['ParentSectionID']) && $ParentMainSection['ParentSectionID'] != '') {
					
						$GrantMainSection = get_section_by_id($ParentMainSection['ParentSectionID']);
						
						if(isset($GrantMainSection['Section_id'])) {
						$LiveGalleryDetails['grant_section_id'] 						= 	$GrantMainSection['Section_id'];
						$LiveGalleryDetails['grant_parent_section_name'] 				= 	$GrantMainSection['Sectionname'];
						}
					}
					
				}
			
			}
				
			$LiveGalleryDetails['last_updated_on'] 				= date('Y-m-d H:i');	
			$LiveGalleryDetails['title'] 						= $txtgalleryname;
			$LiveGalleryDetails['url'] 							= $data['url'];
			$LiveGalleryDetails['summary_html'] 				= $txtSummary;
			
			/*
				if(isset($column_id) && $column_id != '' && $column_id != 'NULL') {
				$ColumnDetails = column_editdetails($column_id);
				
				if(isset($ColumnDetails['column_name']))
					$LivegalleryDetails['column_name'] = $ColumnDetails['column_name'];
			}
			*/
			
			if(isset($txtTags) != '')
			$LiveGalleryDetails['tags'] 					= implode(', ',@$txtTags);
			
			if($data['ddAgency'] != "NULL")
				$LiveGalleryDetails['agency_name'] 			= @get_agencyname_by_id($data['ddAgency']);
				
			if($data['ddCountry'] != "NULL")
				$LiveGalleryDetails['country_name'] 		= @get_countryname_by_id($data['ddCountry']);
				
			if($data['ddState'] != "NULL")	
				$LiveGalleryDetails['state_name'] 			= $txtState;
				
			if($data['ddCity'] != "NULL")	
				$LiveGalleryDetails['city_name'] 			= $txtCity;
			
			$LiveGalleryDetails['allow_comments'] 			=  $data['cbAllowComments'];
			$LiveGalleryDetails['no_indexed'] 				=  $data['cbNoIndex'];
			$LiveGalleryDetails['no_follow'] 				=  $data['cbNoFollows'];
			
			$LiveGalleryDetails['canonical_url']  			= $txtCanonicalUrl;
			$LiveGalleryDetails['meta_Title']  				= $txtMetaTitle;
			$LiveGalleryDetails['meta_description']  		= $txtMetaDescription;

			$data['LiveGalleryDetails'] = array_map('trim',$LiveGalleryDetails);
		//}
		
	
		/*
		echo "<pre>";
		print_r($data);
		exit;  
		*/
		 
		# End the Live Gallery Table Details 
		
		return $data;
	}
	
		
	/*
	*
	* Get the gallery data table using gallery manager page
	*
	* @access public
	* @param POST values from gallery manager view file
	* @return JSON format output to gallery manager page
	*
	*/
	public function get_gallery_datatables() {
			extract($_POST);
			
		$Search_text = trim($Search_text);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

				$content_type 	= "1"; 
			  $menu_name		= "Gallery";
		 
		 $Menu_id = get_menu_details_by_menu_name($menu_name);
		
		switch ($Field) {
    case 1:
        $order_field 	= 'm.title';
		$archive_field 	= 'title';
        break;
    case 2:
        $order_field 	= 'm.Section_id';
		$archive_field 	= 'section_name';
        break;
	case 4:
       $order_field 	= 'um.Username';
	   $archive_field 	= 'created_by';
        break;
	case 5:
       $order_field 	= 'm.Modifiedon';
	   $archive_field 	= 'modified_on';
        break;
	case 6:
       $order_field 	= 'm.status';
	   $archive_field 	= 'status';
        break;
		
    default:
        $order_field = 'm.content_id';
		$archive_field 	= 'content_id';
		}

		$Total_rows = 250;

		$Search_value = $Search_text;
		
				
		if($Search_by == 'ContentId') {
		$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		$Search_value = $Search_result;
		} else {
		$Search_value = $Search_text;
		}
		
		$CurrentYear = date('Y');
		
		if ($check_in != '')
		{
			$check_in_date 	= new DateTime($check_in);
			$check_in 		= $check_in_date->format('Y-m-d');
			$CheckInYear 	=  $check_in_date->format('Y');
		}
		if ($check_out != '')
		{
			$check_out_date 	= new DateTime($check_out);
			$check_out	 		= $check_out_date->format('Y-m-d')." 23:59:59";
			$CheckOutYear 		=  $check_out_date->format('Y');
		}
				
		$Search_value = htmlentities($Search_value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		
		$Search_value =  str_replace("&#039","&#39",$Search_value);

		$gallery_manager =  $this->db->query('CALL gallery_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'")')->result_array();	
		
		$recordsFiltered = $this->db->query('CALL gallery_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT 0, 250 ","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'")')->num_rows();
		
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;

		foreach($gallery_manager as $gallery) {
			
			$gallery_image = '';

			 $edit_url = "edit_gallery/".urlencode(base64_encode($gallery['content_id']));

			$subdata = array();
	
			$subdata[] = $gallery['content_id'];
			
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($gallery['title']).'">'.shortDescription(strip_tags($gallery['title'])).'</p>';
			
			
			//$subdata[] = $gallery['URLSectionStructure'];
			$subdata[] =  GenerateBreadCrumbBySectionId($gallery['Section_id']);
			
			$gallery_images = $this->db->query('CALL get_gallery_images(' .		$gallery['content_id'] . ')')->row();
			
			if(isset($gallery_images->ImagePhysicalPath)) {
				$article_image = image_url.imagelibrary_image_path.str_replace("original","w150X150", $gallery_images->ImagePhysicalPath);
					$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.$article_image.'" /></div></td>';
			} else {
					$subdata[] = '<td>-</td>';
			}
		
			
			$subdata[] = $gallery['Username'];
			$change_date_format = date('d-m-Y H:i:s', strtotime($gallery['Modifiedon']));
			$subdata[] = $change_date_format;
			
			switch($gallery["status"])
			{
			case("P"):
				$status_icon = '<span data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$gallery['content_id'].'" data-original-title="Active"><i id="status_img'.$gallery['content_id'].'"  class="fa fa-check"></i></span>';
				break;
			case("U"):	
				$status_icon = '<span data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$gallery['content_id'].'"  data-original-title="Active"><i id="status_img'.$gallery['content_id'].'" class="fa fa-times"></i></span>';
				break;
			case("D"):			
				$status_icon = '<span data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$gallery['content_id'].'"  data-original-title="Active"><i id="status_img'.$gallery['content_id'].'" class="fa fa-floppy-o"></i></span>';
				break;	
			default;
				$status_icon = '';
			}
			
			$subdata[] = $status_icon;
			
			//$subdata[] = $gallery['Hits'];
		//	$subdata[] = 0;
			
			$set_status ='<div class="buttonHolder">';
			
				if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
					$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a><a class="button tick tooltip-2"  href="'.BASEURL.$gallery['url'].'?page=preview" target="_blank" title="Preview"><i class="fa fa-eye"></i></i></a>';
				}
				else
					$set_status .= '';
			
				if($gallery["status"]=="P")
                {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { 
					$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Unpublish" content_id = '.$gallery['content_id'].' status ="'.$gallery["status"].'" name="'.strip_tags($gallery['title']).'" id="status_change"><i id="status'.$gallery['content_id'].'" class="fa fa-pause"></i></a>'.'';
					}
				}
                elseif($gallery["status"]=="U")
                { 
				 	if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<a data-toggle="tooltip" href="#" title="Publish" class="button heart" data-original-title="" content_id = '.$gallery['content_id'].' status ="'.$gallery["status"].'" name="'.strip_tags($gallery['title']).'" id="status_change"><i id="status'.$gallery['content_id'].'" class="fa fa-caret-right"></i></a>'.'';
					}
				}
				
				if($gallery["status"]=="P" ) {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="unpublish_checkbox[]" value="'.$gallery['content_id'].'" id="unpublish_checkbox_id" status ="'.$gallery["status"].'"    ></span>';
					}
				}
				
				if($gallery["status"]=="U" ) {
					if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"    title="Select"   name="publish_checkbox[]" value="'.$gallery['content_id'].'"   status ="'.$gallery["status"].'"    id="publish_checkbox_id" ></span>';
					}
				}				
			
			if($set_status != '') {			  
			$set_status .= '</div>';
			$subdata[] = $set_status ;
			}
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
	if($check_in != '' && $check_out != '') {
			
			
			
			if($CheckInYear <= $CurrentYear) {
				
				$TableName = "gallery_".$CheckInYear;

				
				if ($this->archive_db->table_exists($TableName)) {
					
					$ArchiveRecordsFiltered = 0;
					
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
							
							if(trim($Status) != '') 
								$this->archive_db->like("status",$Status);
							
							switch(trim($Search_by)) {
								case "Title":
								$this->archive_db->like("title",$Search_value);
								break;
								case "ContentId":
								$this->archive_db->where("content_id",$Search_value);
								break;
								case "created_by":
								$this->archive_db->like("created_by",$Search_value);
								break;
								default:
								$this->archive_db->where("( title LIKE '%".$Search_value."%' OR  created_by LIKE '%".$Search_value."%')");
								break;
							}
							
							if($Section != '')
								$this->archive_db->like("section_id",$Section);
							
							$this->archive_db->limit($length,$start);
							$this->archive_db->order_by($archive_field,$order);
							
							$Get = $this->archive_db->get();
							$archive_content_manager 	= $Get->result_array();
						
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
											
							
							if(trim($Status) != '') 
								$this->archive_db->like("status",$Status);
							
							switch(trim($Search_by)) {
								case "Title":
								$this->archive_db->like("title",$Search_value);
								break;
								case "ContentId":
								$this->archive_db->where("content_id",$Search_value);
								break;
								case "created_by":
								$this->archive_db->like("created_by",$Search_value);
								break;
								default:
								$this->archive_db->where("( title LIKE '%".$Search_value."%' OR  created_by LIKE '%".$Search_value."%')");
								break;
							}
							
							if($Section != '')
								$this->archive_db->like("section_id",$Section);
							
							$this->archive_db->limit(250,0);
							
							$Get = $this->archive_db->get();
							$ArchiveRecordsFiltered =  $Get->num_rows();
							
							
					if($ArchiveRecordsFiltered != 0 ){
							foreach($archive_content_manager as $article) {
							
							$article_image = '';

							$edit_url = "edit_archive_gallery/".$CheckInYear."/".urlencode(base64_encode($article['content_id']));

							$subdata = array();
							
							$Style = "";
							
							$subdata[] = $article['content_id'];
							
							if($article['status'] == 'P' && strtotime($article['publish_start_date']) > strtotime(date('d-m-Y H:i:s'))) 
							$Style = "style='color:red'";
					
							$subdata[] ='<p class="tooltip_cursor" '.$Style.' title="'.strip_tags($article['title']).'">'.shortDescription(strip_tags($article['title'])).'</p>';

							$subdata[] =  (GetBreadCrumbByURL($article['url']));
							
							if($article['first_image_path'] != '' ) {
									if($article['first_image_path'] != '') {
										$Image150X150 	= str_replace("original","w150X150", $article['first_image_path']);
										$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
									} else {
										$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a></td>';	
									}
								} else  {
								$subdata[] = '<td><a href="javascript:void()">-</a></td>';
								}	
							
							$subdata[] = $article['modified_by'];
							$change_date_format = date('d-m-Y H:i:s', strtotime($article['modified_on']));
							$subdata[] = $change_date_format;
							
							switch($article["status"])
							{
							case("P"):
								$status_icon = '<a data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$article['content_id'].'" data-original-title="Active"><i id="status_img'.$article['content_id'].'"  class="fa fa-check"></i></a>';
								break;
							case("U"):	
								$status_icon = '<a data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-times"></i></a>';
								break;
							case("D"):			
								$status_icon = '<a data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-floppy-o"></i></a>';
								break;	
							default;
								$status_icon = '';
							}
							
							$subdata[] = $status_icon;
							
							//$subdata[] = $article['Hits'];
							//$subdata[] = 0;
							
							$set_status ='<div class="buttonHolder">';
							
								if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
									$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a><a class="button tick tooltip-2"  href="'.base_url().$article['url'].'?page=preview" target="_blank" title="Preview"><i class="fa fa-eye"></i></i></a>';
								}
								else {
									$set_status .= '';
								}
							
								
							if($set_status != '') {			  
							$set_status .= '</div>';
							$subdata[] = $set_status ;
							}
							
							$data['data'][$Count] = $subdata;
							$Count++;
						}
						
						$recordsFiltered += $ArchiveRecordsFiltered;
						
					}
				}
			}
		}
		
				
		$data['draw'] 				= $draw;
		$data["recordsTotal"] 		= $Total_rows;
		$data["recordsFiltered"] 	= $recordsFiltered;
		
		
		echo json_encode($data);
		exit;
		
	}
	
	/*
	*
	* Get Gallery Details from Gallery table 
	*
	* @access PUBLIC
	* @param Gallery Id (content_id)
	* @return Gallery master table records
	*/
	
	public function get_gallery_details($gallery_id)

	{
		$gallery_manager = $this->db->query('CALL get_gallery_by_id(' . $gallery_id . ')');
		return $gallery_manager;
	}
	
	/*
	*
	* Get Gallery Details from Gallery table 
	*
	* @access PUBLIC
	* @param Gallery Id (content_id)
	* @return Gallery master table records
	*/
	
	
	public function get_gallery_images($gallery_id)

	{
		$gallery_images = $this->db->query('CALL get_gallery_images(' . $gallery_id . ')');
		return $gallery_images;
	}
	
	/*
	*
	* Delete Gallery images based on gallery content id
	*
	* @access PUBLIC
	* @param Gallery Content Id
	* @return TRUE or FALSE
	*
	*/
	
	public function delete_gallery_image($gallery_id)

	{
		$type_value = $this->db->query('CALL delete_gallery_image (' . $gallery_id . ')');
		return $type_value;
	}
	
}
/* End of file gallerymodel.php */
/* Location: ./application/models/admin/gallerymodel.php */