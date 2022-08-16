<?php
class section_widget_article_model extends CI_Model
{
	
	public $image_variable = '';
	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	
	public function insert_image($is_image_uploaded)
	{
		//print_r($is_image_uploaded); exit;
		
		$data['sectionID'] = $is_image_uploaded['sectionID'];
		
		$data['image_alt']     = $is_image_uploaded['image_alt'];
		$data['image_caption'] = $is_image_uploaded['image_caption'];
		$data['image_name']    = $is_image_uploaded['orig_name'];
		$data['temp_name']     = $is_image_uploaded['temp_name'];
		
		$data['temp_image_id'] = $is_image_uploaded['temp_image_id'];
		
		$data['image_library_id'] = $is_image_uploaded['image_library_id'];
		
		$upload_path = $is_image_uploaded['full_path'];
		
		$image1_type = $is_image_uploaded['image1_type'];
		$image2_type = $is_image_uploaded['image2_type'];
		$image4_type = $is_image_uploaded['image4_type'];
		$image3_type = $is_image_uploaded['image3_type'];
		
		$crop_image_name = $is_image_uploaded['image_name'];
		$save_status = $is_image_uploaded['save_status'];
		
		$data['filename']           = $is_image_uploaded['filename'];
		$data['physical_imagename'] = $is_image_uploaded['physical_imagename'];
		$set_path                   = date("Ymdhis") . $is_image_uploaded['filename'];
		$data['img_extension']      = pathinfo($set_path, PATHINFO_EXTENSION);
		$data['img_exist']          = 'Y';
		
		$data['encode_image'] = '';
		//image resize code
		
		$image_binary_bool1 = false;
		$image_binary_bool2 = false;
		$image_binary_bool3 = false;
		$image_binary_bool4 = false;
		
		if($save_status == 2)
		{
			$data['image_name']    = $crop_image_name;
			$data['temp_name']    = $crop_image_name;
		}
		
		if(isset($upload_path) && $upload_path != '' && $save_status != 2)
		{
			
			$ImagePath = '';
			
			$src            = $ImagePath . $upload_path;
			
			$ImageExtension = explode("/", $src);
			$LastArray      = explode('.', end($ImageExtension));
			$extType        = strtolower($LastArray[1]);
			
			$image_binary_bool1 = false;
			$image_binary_bool2 = false;
			$image_binary_bool3 = false;
			$image_binary_bool4 = false;
			
			//if(!empty($src) && $data['temp_image_id'] == '')
			if(!empty($src))
			{
				switch($extType) //check image type
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
				$src_w = $size[0];
				$src_h = $size[1];
				
				//resize image to 600*390 size
				$dst_w = 600;
				$dst_h = 390;
				$dst_img = imagecreatetruecolor($dst_w, $dst_h);
				$dst     = $ImagePath . str_replace(".", "_600_390.", $upload_path);
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
					if(imagejpeg($dst_img, $dst))
					{
						$ImageDetails = getimagesize($dst);
						$width        = $ImageDetails[0];
						$height       = $ImageDetails[1];
						$size         = $ImageDetails['bits'];
						$imagetype    = $ImageDetails['mime'];
						
						$image_binary_bool1 = true;
						$image1_type        = 1;
						imagedestroy($dst_img);
					}
				}
				
				//resize image to 600*300 size
				$dst_w = 600;
				$dst_h = 300;
				$dst_img = imagecreatetruecolor($dst_w, $dst_h);
				$dst     = $ImagePath . str_replace(".", "_600_300.", $upload_path);
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
					if(imagejpeg($dst_img, $dst))
					{
						$ImageDetails       = getimagesize($dst);
						$width              = $ImageDetails[0];
						$height             = $ImageDetails[1];
						$size               = $ImageDetails['bits'];
						$imagetype          = $ImageDetails['mime'];
						$image_binary_bool2 = true;
						$image2_type        = 1;
						imagedestroy($dst_img);
					}
				}
				
				//resize image to 150*150 size
				$dst_w = 150;
				$dst_h = 150;
				$dst_img = imagecreatetruecolor($dst_w, $dst_h);
				$dst     = $ImagePath . str_replace(".", "_150_150.", $upload_path);
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
					if(imagejpeg($dst_img, $dst))
					{
						$ImageDetails       = getimagesize($dst);
						$width              = $ImageDetails[0];
						$height             = $ImageDetails[1];
						$size               = $ImageDetails['bits'];
						$imagetype          = $ImageDetails['mime'];
						$image_binary_bool4 = true;
						$image3_type        = 1;
						imagedestroy($dst_img);
					}
				}
				
				//resize image to 100*65 size
				$dst_w = 100;
				$dst_h = 65;
				$dst_img = imagecreatetruecolor($dst_w, $dst_h);
				$dst     = $ImagePath . str_replace(".", "_100_65.", $upload_path);
				$result  = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
				if($result)
				{
					if(imagejpeg($dst_img, $dst))
					{
						$ImageDetails       = getimagesize($dst);
						$width              = $ImageDetails[0];
						$height             = $ImageDetails[1];
						$size               = $ImageDetails['bits'];
						$imagetype          = $ImageDetails['mime'];
						$image_binary_bool3 = true;
						$image4_type        = 1;
						imagedestroy($dst_img);
					}
				}
			}
		}
		
		if($image_binary_bool1 == true || $image_binary_bool2 == true || $image_binary_bool3 == true || $image_binary_bool4 == true || ($data['temp_image_id'] != '' && $save_status == 2 ) || ($data['image_library_id'] != '' && $save_status == 2 ))
		{
			$temp_image = array();
			
			//$TempSourceURL = section_article_image_path.$data['temp_name'];	
			$TempSourceURL = section_article_image_path;
			
			$datatimestring = strtotime(date("Ymdhis"));
			$DestinationURL = imagelibrary_image_path;
			
			$image_name   = explode('.', $data['image_name']);
			$NewImageName = $data['physical_imagename'] . '.' . $image_name[1];
			$Year         = date('Y');
			$Month        = date('n');
			$Day          = date('j');
			
			create_image_folder($Year, $Month, $Day);
			$FolderMapping = $Year . "/" . $Month . "/" . $Day . "/original/";
			
			ImageDeleteAndPasteToLibrary($data['temp_name'], $NewImageName, $TempSourceURL, $DestinationURL, $FolderMapping);
			$caption_array = explode('.', $data['filename']);
			$caption       = $caption_array[0];
			
			//$temp_image['alt_tag'] = $caption;
			//$temp_image['caption'] = $caption;
			
			$temp_image['alt_tag']     = $data['image_alt'];
			$temp_image['caption']     = $data['image_caption'];
			$temp_image['image_name']  = $FolderMapping . $NewImageName;
			$temp_image['image1_type'] = $image1_type;
			$temp_image['image2_type'] = $image2_type;
			$temp_image['image3_type'] = $image3_type;
			$temp_image['image4_type'] = $image4_type;
			
			$image_content_id     = add_image_master($temp_image);
			$this->image_variable = $image_content_id;
		}
	}
	
	////////////// Get all available menus / Sections from sectionmaster table   //////////
	public function multiple_section_mapping()
	{
		//$this->db->cache_off();
		
		$empty_val        = '';
		$list_multi_sectn = $this->db->query('CALL get_section("' . $empty_val . '")');
		$get_result       = $list_multi_sectn->result_array();
		
		foreach($get_result as $key => $get_multi_section)
		{
			
			$get_sec_id                    = $get_multi_section['Section_id'];
			$page_details                  = $this->db->query('CALL get_pagemaster_using_sectionid("' . $get_sec_id . '", "")');
			$get_multi_section['page_det'] = $page_details->result_array();
			
			$list_multi_sectn                 = $this->db->query('CALL get_section("' . $get_sec_id . '")');
			$get_multi_section['sub_section'] = $list_multi_sectn->result_array();
			
			foreach($get_multi_section['sub_section'] as $subkey => $subsection_page)
			{
				
				$get_subsec_id               = $subsection_page['Section_id'];
				$subsec_page_details         = $this->db->query('CALL get_pagemaster_using_sectionid("' . $get_subsec_id . '", "")');
				$subsection_page['page_det'] = $subsec_page_details->result_array();
				///// Is Special Menu /// 
				
				$special_section_details = $this->db->query('CALL get_seperatemenu ("' . $subsection_page['IsSeperateWebsite'] . '", "' . $get_subsec_id . '")')->result_array();
				//echo "<br>". $this->db->last_query();
				$specila_list            = array();
				foreach($special_section_details as $splkey => $special_section)
				{
					//print_r($special_section); exit;
					
					$get_splsec_id               = $special_section['Section_id'];
					$splsec_page_details         = $this->db->query('CALL get_pagemaster_using_sectionid("' . $get_splsec_id . '", "")');
					$special_section['page_det'] = $splsec_page_details->result_array();
					
					///////  Add Specialsection to main Subsection array object  /////
					$specila_list[$splkey] = $special_section;
				}
				$subsection_page['special_section']        = $specila_list;
				///////  Add Subsection to main section array object  /////
				$get_multi_section['sub_section'][$subkey] = $subsection_page;
				
			}
			$get_result[$key] = $get_multi_section;
		}
		
		return $get_result;
		
	}
	
	//////////////  Get section Id from page_master table  ///////
	public function getSectionFromPage($pageId)
	{
		//$this->db->cache_off();		
		
		$query                       = $this->db->query("CALL get_xmlpage_details('" . $pageId . "')");
		$result                      = $query->row_array();
		$required_result['menuid']   = $result['menuid'];
		$required_result['pagetype'] = $result['pagetype'];
		return $required_result;
	}
	
	public function get_section_by_id($section_id)
	{
		//$this->db->cache_on();	
		
		$select_query = $this->db->query("CALL get_section_by_id('" . $section_id . "')");
		$result       = $select_query->row_array();
		return $result;
	}
	
	public function get_all_available_articles($section_id, $content_type, $search_bycontent, $search_bytitle, $section_name)
	{
		$order_field  = "cm.Modifiedon";
		$order        = "DESC";
		$start_limt   = "0";
		$length       = "60";
		$check_in     = "";
		$check_out    = "";
		$Search_value = $search_bytitle;
		$Search_by    = ($Search_value != '') ? "Title" : "";
		$Section      = $section_id;
		$Status       = "P";
		$content_type = $content_type;
		
		$article_manager = $this->db->query('CALL get_common_widget_article_datatable(" ORDER BY ' . $order_field . ' ' . $order . ' LIMIT ' . $start_limt . ', ' . $length . '","' . $check_in . '","' . $check_out . '","' . $Search_value . '","' . $search_bycontent . '","' . $Section . '","' . $Status . '","' . $content_type . '", "' . trim($section_name) . '")');
		//echo $this->db->last_query();
		return $result = $article_manager->result_array();
	}
	
	public function required_commonwidget_content_by_id($contennt_ids, $content_type_id, $section_name)
	{
		//$this->db->cache_off();	
		$order_field  = "cm.Modifiedon";
		$order        = "DESC";
		$start_limt   = "0";
		$length       = "60";
		$check_in     = "";
		$check_out    = "";
		$Search_value = "";
		$Search_by    = "";
		$Section      = "";
		$Status       = "P";
		$content_type = $content_type_id;
		
		$article_manager = $this->db->query('CALL required_commonwidget_content_by_id(" ORDER BY ' . $order_field . ' ' . $order . ' LIMIT ' . $start_limt . ', ' . $length . '","' . $check_in . '","' . $check_out . '","' . $Search_value . '","' . $Search_by . '","' . $Section . '","' . $Status . '","' . $content_type . '", "' . $contennt_ids . '", "' . trim($section_name) . '")');
		
		return $result = $article_manager->result_array();
	}
	
	public function get_image_by_contentid($content_id)
	{
		$article_details = $this->db->query('CALL get_imagedetails_by_contentid(' . $content_id . ')');
		$row_array       = $article_details->row_array();
		return $row_array;
	}
	
	
	
	
	public function inactivate_articlecustomdetails($custom_details, $user_id)
	{
		//$sectioID = $custom_details['get_SectionID'];
		$content_id             = $custom_details['content_id'];
		$instance_id            = "";
		$instance_maisection_id = "";
		$instance_subsection_id = "";
		$user_id                = $user_id;
		$instancecontent_id     = "";
		$checkWiget_Type        = $custom_details['checkWiget_Type'];
		if($checkWiget_Type == "jumbo_widget_articles")
		{
			$widgetType = "1";
			$sectioID   = $custom_details['get_SectionID'];
		}
		else if($checkWiget_Type == "editor_pick_articles")
		{
			$widgetType = "2";
			$sectioID   = 0;
		}
		else if($checkWiget_Type == "trending_now_articles")
		{
			$widgetType = "3";
			$sectioID   = 0;
		}
		else if($checkWiget_Type == "related_articles")
		{
			$widgetType = "4";
			$sectioID   =  $custom_details['get_SectionID'];
		}
		
		$insert_result = $this->db->query("CALL inactivate_section_customwidget_status('" . $sectioID . "','" . $user_id . "', '" . $content_id . "', '" . $widgetType . "')");
	}
	
	public function delete_section_widget_articles_live($custom_details, $user_id) //move active status jumbo menu and editor pick news to live table
	{
		//$sectioID = $custom_details['section_id'];
			
			$user_id                = $user_id;
			$checkWiget_Type        = $custom_details['checkWiget_Type'];
			if($checkWiget_Type == "jumbo_widget_articles")
			{
				$widgetType = "1";
				$sectioID   = $custom_details['section_id'];
			}
			else if($checkWiget_Type == "editor_pick_articles")
			{
				$widgetType = "2";
				$sectioID   = 0;
			}
			else if($checkWiget_Type == "trending_now_articles")
			{
				$widgetType = "3";
				$sectioID   = 0;
			}
			else if($checkWiget_Type == "related_articles")
			{
				$widgetType = "4";
				$sectioID   =  $custom_details['section_id'];
			}
		
		
		$this->live_db->trans_begin();
		
		$insert_result = $this->live_db->query("CALL delete_section_widget_articles('" . $sectioID . "', '" . $widgetType . "')");
		
		if($this->live_db->trans_status() === FALSE)
		{
			$this->live_db->trans_rollback();
			$return_msg = array(
				"status" => false,
				"message" => "Internal error to save in " . $content_id . " Article",
				"inserted_id" => ""
			);
		}
		else
		{
			$this->live_db->trans_commit();
			$return_msg = array(
				"status" => true,
				"message" => " Article published saved"
			);
		}
		
		return $return_msg;
	}
	
	public function addwidget_articlecustomdetails_temporary($custom_details, $user_id)
	{
		//print_r($custom_details); exit;
		$checkWiget_Type = $custom_details['checkWiget_Type'];
		
		$content_type = $custom_details['content_type'];
		
		if($checkWiget_Type == "jumbo_widget_articles")
		{
			$widgetType     = "1";
			$get_section_id = $custom_details['get_SectionID'];
		}
		else if($checkWiget_Type == "editor_pick_articles")
		{
			$widgetType     = "2";
			$get_section_id = 0;
		}
		else if($checkWiget_Type == "trending_now_articles")
		{
			$widgetType     = "3";
			$get_section_id = 0;
		}
		else if($checkWiget_Type == "related_articles")
		{
			$widgetType     = "4";
			$get_section_id = $custom_details['get_SectionID'];
		}
		
		$return_msg           = array(
			"status" => false,
			"message" => "Internal error",
			"inserted_id" => ""
		);
		$content_id           = $custom_details['content_id'];
		$custom_title         = addslashes($custom_details['Title']);
		$custom_summary       = addslashes($custom_details['Summary']);
		$uploaded_image       = ($custom_details['uploaded_image']);
		$uploaded_image_name  = @$custom_details['image_name'];
		$uploaded_image_id    = $custom_details['old_image_id'];
		$update_displayorder  = (@$custom_details['display_order'] == '') ? '' : @$custom_details['display_order'];
		$update_contet_status = (@$custom_details['checked_status'] == '') ? '2' : @$custom_details['checked_status'];
		$user_id              = $user_id;
		$insert_date          = @$custom_details['modified_date'];
		
		$contentTypeID = @$custom_details['contentType_id'];
		
		$image_library_id = @$custom_details['image_library_id'];
		
		$checkWiget_Type = $custom_details['checkWiget_Type'];
		
		$related_content_id = '';
		
		$get_image_id = $this->image_variable;
		
		if($get_image_id != "")
		{
			$get_image_id;
		}
		else if($image_library_id != "")
		{
			$get_image_id = $image_library_id;
		}
		else if($uploaded_image_id != "")
		{
			$get_image_id = $uploaded_image_id;
		}
		
		else if($uploaded_image != "")
		{
			$get_image_id = $uploaded_image;
		}
		else
		{
			$get_image_id = 'NULL';
		}
		
		$insert_date = date("Y-m-d H:i:s", time());
		
		$this->db->trans_begin();
		$insert_result = $this->db->query("CALL insert_section_widget_article('" . $content_id . "', '" . $custom_title . "', '" . $custom_summary . "', '" . $uploaded_image . "', '" . $user_id . "', '" . $update_contet_status . "', '" . $update_displayorder . "', '" . $insert_date . "','" . $uploaded_image_name . "', '" . $get_section_id . "', '" . $get_image_id . "', '" . $widgetType . "', '" . $content_type . "')");
		
		
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$return_msg = array(
				"status" => false,
				"message" => "Internal error to save in " . $content_id . " Article",
				"inserted_id" => ""
			);
		}
		else
		{
			$this->db->trans_commit();
			$return_msg = array(
				"status" => true,
				"message" => " Article successfully saved"
			);
		}
		
		return $return_msg;
	}
	
	public function addwidget_articlecustomdetails_permanent($custom_details, $first_update_incactive) //move active status jumbo menu and editor pick news to live table
	{
		//print_r($custom_details); exit;
		$checkWiget_Type = $custom_details['checkWiget_Type'];
		
		$content_type = $custom_details['content_type'];
		
		if($checkWiget_Type == "jumbo_widget_articles")
		{
			$widgetType     = "1";
			$get_section_id = $custom_details['get_SectionID'];
		}
		else if($checkWiget_Type == "editor_pick_articles")
		{
			$widgetType = "2";
			$get_section_id   = 0;
		}
		else if($checkWiget_Type == "trending_now_articles")
		{
			$widgetType = "3";
			$get_section_id   = 0;
		}
		else if($checkWiget_Type == "related_articles")
		{
			$widgetType = "4";
			$get_section_id   = $custom_details['get_SectionID'];
		}
		
		$content_id           = $custom_details['content_id'];
		$custom_title         = addslashes($custom_details['Title']);
		$custom_summary       = addslashes($custom_details['Summary']);
		$uploaded_image       = ($custom_details['uploaded_image']);
		$uploaded_image_name  = @$custom_details['image_name'];
		$uploaded_image_id    = $custom_details['old_image_id'];
		$update_displayorder  = (@$custom_details['display_order'] == '') ? '' : @$custom_details['display_order'];
		$update_contet_status = (@$custom_details['checked_status'] == '') ? '2' : @$custom_details['checked_status'];
		//$user_id              = $user_id;
		$insert_date          = @$custom_details['modified_date'];
		
		$img_alt  = @$custom_details['img_alt'];
		$img_path = @$custom_details['img_path'];
		$img_caption = @$custom_details['img_caption'];
		
		$checkWiget_Type = $custom_details['checkWiget_Type'];
		
		$insert_date = date("Y-m-d H:i:s", time());
		$this->live_db->trans_begin();
		
		$insert_result = $this->live_db->query("CALL publish_commonwidget_articles('" . $content_id . "', '" . $custom_title . "', '" . $custom_summary . "', '" . $update_displayorder . "', '" . $get_section_id . "', '" . $widgetType . "', '" . $img_path . "',  '" . $img_alt . "', '" . $first_update_incactive . "', '".$img_caption."', '".$content_type."')");
		
		if($this->live_db->trans_status() === FALSE)
		{
			$this->live_db->trans_rollback();
			$return_msg = array(
				"status" => false,
				"message" => "Internal error to save in " . $content_id . " Article",
				"inserted_id" => ""
			);
		}
		else
		{
			$this->live_db->trans_commit();
			$return_msg = array(
				"status" => true,
				"message" => " Article published saved"
			);
		}
		
		return $return_msg;
	}
	
	
	public function get_widgetInstanceTempArticles($content_id, $user_id, $widget_type, $sectionid)
	{
		$select_query = $this->db->query("CALL get_sectionWidgetArticles('" . $content_id . "', '" . $user_id . "', '" . $sectionid . "', '" . $widget_type . "')");
		$result       = $select_query->result_array();
		return $result;
	}
	
	public function get_widgetInstanceTempArticles_active($content_id, $user_id)
	{
		$select_query = $this->db->query("CALL get_section_WidgetArticles_active ('" . $content_id . "', '" . $user_id . "')");
		$result       = $select_query->result_array();
		return $result;
	}
	
	
	public function inactivate_instance_temparticles_status($custom_details, $user_id)
	{
		if($custom_details != '')
		{
			//$sectioID = $custom_details['section_id'];
			$content_id             = $custom_details['content_id'];
			$instance_id            = "";
			$instance_maisection_id = "";
			$instance_subsection_id = "";
			$user_id                = $user_id;
			$instancecontent_id     = "";
			$checkWiget_Type        = $custom_details['checkWiget_Type'];
			if($checkWiget_Type == "jumbo_widget_articles")
			{
				$widgetType = "1";
				$sectioID   = $custom_details['section_id'];
			}
			else if($checkWiget_Type == "editor_pick_articles")
			{
				$widgetType = "2";
				$sectioID   = 0;
			}
			else if($checkWiget_Type == "trending_now_articles")
			{
				$widgetType = "3";
				$sectioID   = 0;
			}
			else if($checkWiget_Type == "related_articles")
			{
				$widgetType = "4";
				$sectioID   = $custom_details['section_id'];
			}
		}
		else
		{
			$content_id             = '';
			$instance_id            = "";
			$instance_maisection_id = "";
			$instance_subsection_id = "";
			$user_id                = $user_id;
			$instancecontent_id     = "";
		}
		
		$user_id       = $user_id;
		$insert_result = $this->db->query("CALL inactivate_section_widget_status('" . $sectioID . "','" . $user_id . "', '" . $content_id . "', '" . $widgetType . "')");
	}
	
	public function live_section_articles_count($widget_type, $get_sectionID)
	{
		$insert_result = $this->live_db->query("CALL select_section_widget_article_count('" . $widget_type . "', '" . $get_sectionID . "')")->num_rows();
		return $insert_result;
	}
	
	public function display_order_in_sequence($table_name, $widget_instance_id, $instance_mainsection_id, $instance_subsection_id, $user_id, $related_content_id, $getSectionID, $widget_type)
	{
		if($widget_type == "jumbo_widget_articles")
		{
			$widgetType = "1";
			$SectionID  = $getSectionID;
		}
		else if($checkWiget_Type == "editor_pick_articles")
		{
			$widgetType = "2";
			$sectioID   = 0;
		}
		else if($checkWiget_Type == "trending_now_articles")
		{
			$widgetType = "3";
			$sectioID   = 0;
		}
		else if($checkWiget_Type == "related_articles")
		{
			$widgetType = "4";
			$sectioID   = $getSectionID;
		}
		$instance_mainsection_id = ($instance_mainsection_id == '') ? 'NULL' : $instance_mainsection_id;
		$instance_subsection_id  = ($instance_subsection_id == '') ? 'NULL' : $instance_subsection_id;
		
		$this->db->query("CALL section_display_order_in_sequence('" . $table_name . "', '" . $widget_instance_id . "', '" . $instance_mainsection_id . "', '" . $instance_subsection_id . "','" . $user_id . "', '" . $SectionID . "', '" . $widgetType . "')");
		
		if(!mysql_error())
		{
			return array(
				"status" => true
			);
		}
		else
		{
			return array(
				"status" => false
			);
		}
	}
	
	
	public function custom_image_upload($userid, $imagecontent_id, $contenttype, $caption, $alt_tag, $physical_name, $file_name, $image1_type, $image2_type, $image3_type, $image4_type, $display_order, $article_id, $instance_id, $mainSectionConfig_id)
	{
		
		$createdon            = date('Y-m-d H:i:s');
		$modifiedon           = date('Y-m-d H:i:s');
		$mainSectionConfig_id = ($mainSectionConfig_id == '') ? 'NULL' : $mainSectionConfig_id;
		$query                = $this->db->query("CALL insert_temp_section_widget_image('" . $userid . "'," . $imagecontent_id . ",'" . $contenttype . "','" . addslashes($caption) . "','" . addslashes($alt_tag) . "','" . addslashes($physical_name) . "','" . addslashes($file_name) . "'," . $image1_type . "," . $image2_type . "," . $image3_type . "," . $image4_type . "," . $display_order . ",'" . $createdon . "','" . $modifiedon . "',@insert_id,'" . $instance_id . "','" . $mainSectionConfig_id . "','" . $article_id . "')");
		
		$query    = $this->db->query("SELECT @insert_id");
		$returnid = $query->result_array();
		
		if(isset($returnid[0]['@insert_id']) && $returnid[0]['@insert_id'] != '')
		{
			
			$data['image_id']        = $returnid[0]['@insert_id'];
			$data['imagecontent_id'] = $imagecontent_id;
			$data['caption']         = $caption;
			$data['alt_tag']         = $alt_tag;
			$data['physical_name']   = $physical_name;
			
			
			switch($contenttype)
			{
				case 1:
					$data['image'] = image_url . section_article_image_path . $file_name;
					break;
				case 2:
					$data['image'] = image_url . imagelibrary_temp_image_path . $file_name;
					break;
				case 3:
					$data['image'] = image_url . gallery_temp_image_path . $file_name;
					break;
				case 4:
					$data['image'] = image_url . video_temp_image_path . $file_name;
					break;
				case 5:
					$data['image'] = image_url . audio_temp_image_path . $file_name;
					break;
				case 6:
					$data['image'] = image_url . resource_temp_image_path . $file_name;
					break;
			}
			
			$PhysicalExtension_array    = explode('.', $file_name);
			$data['physical_extension'] = $PhysicalExtension_array[1];
			
			$data['image1_type'] = $image1_type;
			$data['image2_type'] = $image1_type;
			$data['image3_type'] = $image1_type;
			$data['image4_type'] = $image1_type;
			
		}
		else
		{
			echo '{"type":1,"message":"Invalid image, please try again","line":0}';
			exit;
		}
		return $data;
	}
	
	public function search_image_library($Caption)
	{
		//$Order = "ORDER BY Modifiedon desc LIMIT 0, 16";	
		$Order = "ORDER BY Modifiedon desc LIMIT 0, 12";			
		//$Order = "ORDER BY Modifiedon DESC";
		if($Caption != '')
			$search = $this->db->query('CALL search_image_related_data("' . $Caption . '","' . $Order . '")');
		else
			$search = $this->db->query('CALL get_image_related_data("' . $Order . '")');
		
		return $search->result();
	}
	
	public function Insert_temp_from_image_library($ImageDetails, $content_id, $caption, $alt, $path, $contenttype, $article_id, $instance_id, $mainSectionConfig_id, $NewImageName)
	{
		$Image1Type   = $ImageDetails['Image1Type'];
		$Image2Type   = $ImageDetails['Image2Type'];
		$Image3Type   = $ImageDetails['Image3Type'];
		$Image4Type   = $ImageDetails['Image4Type'];
		$createdon    = $modifiedon = date('Y-m-d H:i:s');
		$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
		
		$query = $this->db->query("CALL insert_temp_section_widget_image('" . USERID . "'," . $content_id . ",'" . $contenttype . "','" . addslashes($caption) . "','" . addslashes($alt) . "','" . addslashes($PhysicalName) . "','" . addslashes($path) . "'," . $Image1Type . "," . $Image2Type . "," . $Image3Type . "," . $Image4Type . ",1,'" . $createdon . "','" . $modifiedon . "',@insert_id,'" . $instance_id . "','" . $mainSectionConfig_id . "','" . $article_id . "')");
		
		$result           = $this->db->query("SELECT @insert_id")->result_array();
		$image_temp_id    = $result[0]['@insert_id'];
		$data['image_id'] = $image_temp_id;
		$data['source']   = image_url . section_article_image_path . $path;
		
		$Physical_extension_array = explode(".", $path);
		
		$data['caption'] = $caption;
		$data['alt']     = $alt;
		
		$data['physical_name']      = $PhysicalName;
		$data['physical_extension'] = $Physical_extension_array[1];
		$data['orig_name']          = $PhysicalName . '.' . $Physical_extension_array[1];
		
		$data['temp_name'] = $NewImageName . '.' . $Physical_extension_array[1];
		
		$data['imagecontent_id'] = $content_id;
		$data['image1_type']     = $Image1Type;
		$data['image2_type']     = $Image2Type;
		$data['image3_type']     = $Image3Type;
		$data['image4_type']     = $Image4Type;
		
		return $data;
	}
	
	public function search_image_library_scroll($page)
	{
		$offset  = ($page * 12) - 12;
		$Caption = $this->session->userdata('image_caption');
		
		$Order = "ORDER BY Modifiedon desc LIMIT " . $offset . ", 12";
		
		if($Caption != '')
			$search = $this->db->query('CALL search_image_related_data("' . $Caption . '","' . $Order . '")');
		else
			$search = $this->db->query('CALL get_image_related_data("' . $Order . '")');
		
		return $search->result();
	}
	
	public function temp_custom_image_details($temp_table_image_id, $saved_image_id)
	{
		return $query = $this->db->query("CALL temp_section_widget_image_data('" . $temp_table_image_id . "','" . $saved_image_id . "')");
	}
	
	
	public function common_resize_all_images($ImageDetails)
	{
		try
		{
			$ArrayImage = $ImageDetails;
			
			$physical_name   = $ArrayImage['physical_name'];
			$image1_type     = $ArrayImage['image1_type'];
			$image2_type     = $ArrayImage['image2_type'];
			$image3_type     = $ArrayImage['image3_type'];
			$image4_type     = $ArrayImage['image4_type'];
			$image_name      = $ArrayImage['image_name'];
			$imageid         = $ArrayImage['imageid'];
			$imagecontent_id = $ArrayImage['imagecontent_id'];
			$contenttype     = $ArrayImage['contenttype'];
			$caption         = $ArrayImage['caption'];
			$alt_tag         = $ArrayImage['alt_tag'];
			
			$TempSourceURL = section_article_image_path;
			
			
			$Image600X390 = str_replace(".", "_600_390.", $image_name);
			$Image600X300 = str_replace(".", "_600_300.", $image_name);
			$Image100X65  = str_replace(".", "_100_65.", $image_name);
			$Image150X150 = str_replace(".", "_150_150.", $image_name);
			
			
			$image_binary_bool1 = false;
			$image_binary_bool2 = false;
			$image_binary_bool3 = false;
			$image_binary_bool4 = false;
			
			if(isset($image_name))
			{
				$ImagePath = source_base_path . section_article_image_path;
				
				$src = $ImagePath . $image_name;
				
				if(file_exists($src))
				{
					$ImageDetails = getimagesize($src);
					
					$ImageExtension = explode("/", $ImageDetails['mime']);
					$extType        = strtolower($ImageExtension[1]);
					
					if(!empty($src))
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
						
						
						if(!file_exists(source_base_path . $TempSourceURL . $Image600X390))
						{
							
							$dst_w = 600;
							$dst_h = 390;
							
							$dst_img = imagecreatetruecolor($dst_w, $dst_h);
							$dst     = $ImagePath . str_replace(".", "_600_390.", $image_name);
							
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
								if(imagejpeg($dst_img, $dst))
								{
									$ImageDetails = getimagesize($dst);
									$width        = $ImageDetails[0];
									$height       = $ImageDetails[1];
									$size         = $ImageDetails['bits'];
									$imagetype    = $ImageDetails['mime'];
									
									$image_binary_bool1 = true;
									
									$image1_type = 1;
									
									imagedestroy($dst_img);
								}
							}
						}
						
						if(!file_exists(source_base_path . $TempSourceURL . $Image600X300))
						{
							
							
							$dst_w = 600;
							$dst_h = 300;
							
							$dst_img = imagecreatetruecolor($dst_w, $dst_h);
							$dst     = $ImagePath . str_replace(".", "_600_300.", $image_name);
							
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
								if(imagejpeg($dst_img, $dst))
								{
									$ImageDetails = getimagesize($dst);
									$width        = $ImageDetails[0];
									$height       = $ImageDetails[1];
									$size         = $ImageDetails['bits'];
									$imagetype    = $ImageDetails['mime'];
									
									$image_binary_bool2 = true;
									$image2_type        = 1;
									
									imagedestroy($dst_img);
								}
							}
						}
						
						if(!file_exists(source_base_path . $TempSourceURL . $Image150X150))
						{
							$dst_w = 150;
							$dst_h = 150;
							
							$dst_img = imagecreatetruecolor($dst_w, $dst_h);
							$dst     = $ImagePath . str_replace(".", "_150_150.", $image_name);
							
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
								if(imagejpeg($dst_img, $dst))
								{
									$ImageDetails = getimagesize($dst);
									$width        = $ImageDetails[0];
									$height       = $ImageDetails[1];
									$size         = $ImageDetails['bits'];
									$imagetype    = $ImageDetails['mime'];
									
									
									$image_binary_bool4 = true;
									$image3_type        = 1;
									
									imagedestroy($dst_img);
								}
							}
						}
						
						if(!file_exists(source_base_path . $TempSourceURL . $Image100X65))
						{
							
							$dst_w = 100;
							$dst_h = 65;
							
							$dst_img = imagecreatetruecolor($dst_w, $dst_h);
							$dst     = $ImagePath . str_replace(".", "_100_65.", $image_name);
							
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
								if(imagejpeg($dst_img, $dst))
								{
									$ImageDetails = getimagesize($dst);
									$width        = $ImageDetails[0];
									$height       = $ImageDetails[1];
									$size         = $ImageDetails['bits'];
									$imagetype    = $ImageDetails['mime'];
									
									$image_binary_bool3 = true;
									$image4_type        = 1;
									
									imagedestroy($dst_img);
								}
							}
						}
						
						if($image_binary_bool1 == true || $image_binary_bool2 == true || $image_binary_bool3 == true || $image_binary_bool4 == true)
						{
							
							$imagecontent_id = 'NULL';
							
							$caption = str_replace("'", '"', $caption);
							$alt     = str_replace("'", '"', $alt_tag);
							
							$createdon = $modifiedon = date('Y-m-d H:i:s');
							
							$query = $this->db->query("CALL update_full_temp_images('" . $imageid . "','" . USERID . "'," . $imagecontent_id . ",'" . $contenttype . "','" . addslashes($caption) . "','" . addslashes($alt_tag) . "','" . addslashes($physical_name) . "','" . addslashes($image_name) . "'," . $image1_type . "," . $image2_type . "," . $image3_type . "," . $image4_type . ",1,'" . $createdon . "','" . $modifiedon . "')");
							
							imagedestroy($src_img);
						}
					}
				}
				else
				{
					redirect("niecpan/");
				}
			}
			else
			{
				$result_value['status'] = 'error';
				$result_value['msg']    = "Invalid Image";
				echo json_encode($result_value);
			}
			
			
			return true;
		}
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg']    = 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	}
	
	function delete_temp_custom_image($custom_image_tempid)
	{
		$query = $this->db->query("CALL delete_section_widget_temp_image ('" . $custom_image_tempid . "')");
	}
	
	function update_custom_crop_image($ContentImageId, $crop_caption, $crop_alt, $image_600X390_type, $image_600X300_type, $image_100X65_type, $image_150X150_type, $modifiedon, $crop_image_id, $imagetype, $commit_status)
	{
		$CI =& get_instance();
		
		$crop_caption = str_replace("'", '"', $crop_caption);
		$crop_alt     = str_replace("'", '"', $crop_alt);
		
		$query = $CI->db->query("CALL update_section_widget_crop_image('" . $ContentImageId . "','" . $crop_caption . "','" . $crop_alt . "','" . $image_600X390_type . "','" . $image_600X300_type . "','" . $image_100X65_type . "','" . $image_150X150_type . "','" . $modifiedon . "','" . $crop_image_id . "','" . $imagetype . "','" . $commit_status . "')");
		
		return $query;
		
	}
	
	public function add_image_by_temp_id($caption,$alt,$home_physical_name,$tempid) {
		
		$NewImageName		= '';
		$DestinationURL 	= imagelibrary_image_path;
							
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
			
		create_image_folder( $Year, $Month, $Day);
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		
		$query = $this->temp_custom_image_details($tempid, '');
		$temp_image = $query->row_array();
		
		if(isset($temp_image['contenttype'])) {
			$TempSourceURL = article_temp_image_path;
		
			$query = $this->temp_custom_image_details($tempid, '');						
			$TempObject = $query->result();
			
			$Resize_Class = new Common_Resize_class();
			$Resize_Class->common_resize_all_images($TempObject);
		
		if((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ($temp_image['save_status'] == 2 ) || (  trim($temp_image['caption']) != trim($caption) || trim($temp_image['alt_tag']) != trim($alt) || trim($temp_image['physical_name']) != trim($home_physical_name) ) )
		{	
			$image_name = explode('.',$temp_image['image_name']);
			$NewImageName = $home_physical_name.'.'.$image_name[1];
			
			ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $TempSourceURL, $DestinationURL, $FolderMapping);
			
			$query = $this->temp_custom_image_details($tempid, '');
			
			$temp_image = $query->row_array();
		
			$temp_image['caption']      = trim($caption);
			$temp_image['alt_tag'] 		= trim($alt);
			$temp_image['image_name'] 	= $FolderMapping.$NewImageName;
			
			$image_details =  add_image_master($temp_image);						
			
			///// Delete the Temp Images in Table /////						
			$query = $this->db->query("CALL delete_temp_custom_image ('" . $tempid . "')");
			return $image_details ;
			
		} else {
			
			$ImageDetails = get_imagedetails_by_contentid($temp_image['imagecontent_id']);
			$PhysicalName = end(explode("/",$ImageDetails['ImagePhysicalPath']));
			
			$PhysicalPath = str_replace($PhysicalName,"",$ImageDetails['ImagePhysicalPath']);
			
			if(ImageDeleteAndPasteToLibrary($temp_image['image_name'],$PhysicalName, $TempSourceURL, $DestinationURL, $PhysicalPath)) {
				DeleteTempImage($temp_image['image_name'],$tempid, $TempSourceURL);
			}

			return $temp_image['imagecontent_id'];
			
		}
		
	}
	}
	
	public function add_image_by_temp_id_old($caption,$alt,$home_physical_name,$tempid) {
		
					$NewImageName		= '';
					$DestinationURL 	= imagelibrary_image_path;
									 	
					$Year = date('Y');
					$Month = date('n');
					$Day =  date('j');
						
					create_image_folder( $Year, $Month, $Day);
					$FolderMapping = $Year."/".$Month."/".$Day."/original/";
					
					$query = $this->temp_custom_image_details($tempid, '');
					$temp_image = $query->row_array();
					
					if(isset($temp_image['contenttype'])) {
						$TempSourceURL = article_temp_image_path;
					
					
					if (isset($temp_image['imagecontent_id']) && $temp_image['imagecontent_id'] != '' && $temp_image['imagecontent_id'] != 0 && trim($temp_image['caption']) == trim($caption) && trim($temp_image['alt_tag']) == trim($alt) && trim($temp_image['physical_name']) == trim($home_physical_name) && $temp_image['save_status'] == 1)
					{	
						
						$query = $this->temp_custom_image_details($tempid, '');
						
						$TempObject = $query->result();
						
						$Resize_Class = new Common_Resize_class();
						$Resize_Class->common_resize_all_images($TempObject);
				
						$image_name = explode('.',$temp_image['image_name']);
						$NewImageName = $home_physical_name.'.'.$image_name[1];
						
						$ImageDetails = get_imagedetails_by_contentid($temp_image['imagecontent_id']);
						$OldPhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
						
					
						$PhysicalPath = explode('/',$ImageDetails['ImagePhysicalPath']);
						
						$OldFolderMapping = $PhysicalPath[0].'/'.$PhysicalPath[1].'/'.$PhysicalPath[2].'/'.$PhysicalPath[3].'/';
						
						if(ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $TempSourceURL, $DestinationURL, $OldFolderMapping)) {
							DeleteTempImage($temp_image['image_name'],$tempid, $TempSourceURL);
						}
		
						return $temp_image['imagecontent_id'];
						
						
					} else {
			
						$query = $this->temp_custom_image_details($tempid, '');
						
						$TempObject = $query->result();
						
						$Resize_Class = new Common_Resize_class();
						$Resize_Class->common_resize_all_images($TempObject);
						
						$image_name = explode('.',$temp_image['image_name']);
						$NewImageName = $home_physical_name.'.'.$image_name[1];
						
						ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $TempSourceURL, $DestinationURL, $FolderMapping);
						
						$query = $this->temp_custom_image_details($tempid, '');
						
						$temp_image = $query->row_array();
						
						/* New coding */
						if (trim($temp_image['caption']) != '')
						{
								$temp_image['caption']      = trim($caption);
								$temp_image['alt_tag'] 		= trim($alt);
								$temp_image['image_name'] 	= $FolderMapping.$NewImageName;
								
								$image_details =  add_image_master($temp_image);						
						}
						
						///// Delete the Temp Images in Table /////						
						$query = $this->db->query("CALL delete_temp_custom_image ('" . $tempid . "')");
						return $image_details ;
					}
					
				}
	}
	
}
?>
