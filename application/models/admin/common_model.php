<?php 
class Common_model extends CI_Model
{	
	public function get_country_details() 
	{
		$class_object = new Common_model_class;
		return $class_object->get_country_details();
	}
	public function get_byline_details() 
	{
		$class_object = new Common_model_class;
		return $class_object->get_byline_details();
	}
	public function get_agency_details() 
	{
		$class_object = new Common_model_class;
		return $class_object->get_agency_details();
	}
	public function get_author_agency_id($agency_id) 
	{
		$class_object = new Common_model_class;
		return $class_object->get_author_agency_id($agency_id);
	}
	public function get_state_details($country_id) 
	{
		$class_object = new Common_model_class;
		return $class_object->get_state_details($country_id);
	}
	public function get_city_details($country_id, $state_id) 
	{
		$class_object = new Common_model_class;
		return $class_object->get_city_details($country_id, $state_id);
	}
	public function get_content_type() 
	{
		
		$type = $this->db->query('CALL get_content_type("")');
		
		return $type->result();
	}
	
	public function get_content_type_byname($content_type_name)
	{
		$class_object = new Common_model_class;
		return $class_object->get_content_type_byname($content_type_name);	
	}
	
	public function multiple_section_mapping()
	{
		$class_object = new Common_section_class;
		return $class_object->multiple_section_mapping();
	}
	public function  multiple_section_mapping_by_section_id($section_id) {
		$class_object = new Common_section_class;
		return $class_object->multiple_section_mapping_by_section_id($section_id);
	}
	
	public function  get_homePage_xml() {
		$class_object = new Common_section_class;
		return $class_object->get_homePage_xml();
	}
	
	
	public function get_content_mapping($content_id,$content_type) {
		$class_object = new Common_content_class;
		return $class_object->get_content_mapping($content_id,$content_type);
	}
	public function get_content_details($content_id) {
		$class_object = new Common_content_class;
		return $class_object->get_content_details($content_id);
	}
	public function get_content_datatables() {
		$class_object = new Common_content_class;
		return $class_object->get_content_datatables();
	}
	
	public function txt_byliner($term,$author_type)
	{
		$class_object = new autocomplete_text;
		return $class_object->txt_byliner($term,$author_type);
	}
	public function txt_state($term,$countryid)
	{
		$class_object = new autocomplete_text;
		return $class_object->txt_state($term,$countryid);
	}
	
	public function txt_city($term, $stateid)
	{
		$class_object = new autocomplete_text;
		return $class_object->txt_city($term,$stateid);
	}
	public function get_tags($search_text) {
		$class_object =  new tags_model;
		return $class_object->get_tags($search_text);
	}
	public function get_tags_by_id($tags) {
		$class_object =  new tags_model;
		return $class_object->get_tags_by_id($tags);
	}
	public function common_resize_all_images($ImageDetails) {
	$class_object =  new Common_Resize_class;
	$class_object->common_resize_all_images($ImageDetails);
	}
	
	/*
	*
	* Add the image to Image master
	*
	* @access Public
	* @param   caption, alt, physical name, temp id
	* @return image content id
	*
	*/
	
	public function add_image_by_temp_id($caption,$alt,$home_physical_name,$tempid) {
		
					$NewImageName		= '';
					$DestinationURL 	= imagelibrary_image_path;
									 	
					$Year = date('Y');
					$Month = date('n');
					$Day =  date('j');
						
					create_image_folder( $Year, $Month, $Day);
					$FolderMapping = $Year."/".$Month."/".$Day."/original/";
					
					$query = $this->db->query("CALL tempimagedetails('" . $tempid . "','')");
					
					$temp_image = $query->row_array();
					
					if(isset($temp_image['contenttype'])) {
				
					switch($temp_image['contenttype']) {
						case 1:
							$TempSourceURL = article_temp_image_path;
						break;
						case 2:
							$TempSourceURL = imagelibrary_temp_image_path;
						break;
						case 3:
							$TempSourceURL = gallery_temp_image_path;
						break;	
						case 4:
							$TempSourceURL = video_temp_image_path;
						break;	
						case 5:
							$TempSourceURL = audio_temp_image_path;
						break;	
						case 6:
							$TempSourceURL = resource_temp_image_path;
						break;	
					}
				
					 if(file_exists(source_base_path.$TempSourceURL.$temp_image['image_name'])) {
						 
					$query = $this->db->query("CALL tempimagedetails('" . $tempid . "','')");
					$TempObject = $query->result();	
					
					$Resize_Class = new Common_Resize_class();
					$Resize_Class->common_resize_all_images($TempObject);
						
					IF((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ( $temp_image['crop_resize_status'] == 1 && $temp_image['save_status'] == 1 ) || (  trim($temp_image['caption']) != trim($caption) || trim($temp_image['alt_tag']) != trim($alt) || trim($temp_image['physical_name']) != trim($home_physical_name) ) )	{
					
							
							$image_name = explode('.',$temp_image['image_name']);
							$NewImageName = trim($home_physical_name).'.'.$image_name[1];
							
							ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $TempSourceURL, $DestinationURL, $FolderMapping);
							
							///// Delete the Temp Images in Table /////
							
							$query = $this->db->query("CALL deletetempimage('" . $tempid . "')");
							
							/* New coding */
					
							$temp_image['caption']      = trim($caption);
							$temp_image['alt_tag'] 		= trim($alt);
							$temp_image['image_name'] 	= $FolderMapping.$NewImageName;
							
							$image_details =  add_image_master($temp_image);
							
							return $image_details;
								
					} else {
						
						$ImageDetails = GetImageDetailsByContentId($temp_image['imagecontent_id']);
						
						$PhysicalName = end(explode("/",$ImageDetails['ImagePhysicalPath']));
						
						$PhysicalPath = str_replace($PhysicalName,"",$ImageDetails['ImagePhysicalPath']);
						
						ImageDeleteAndPasteToLibrary($temp_image['image_name'],$PhysicalName, $TempSourceURL, $DestinationURL, $PhysicalPath);
						
						$query = $this->db->query("CALL deletetempimage('" . $tempid . "')");
						
						return $temp_image['imagecontent_id'];
					}
					
					} else {
					  return '';
					}
					
				}
	}
	public function update_caption_alt()
	
	{
		$class_object = new Add_image_by_temp();
		return $class_object->update_caption_alt();
	}
	public function update_temp_save_status($temp_id, $save_status) {

		$this->db->query("CALL update_temp_save_status(".$temp_id.",".$save_status.")");	
	
		if($save_status == 0) {
			
				$TempDetails = $this->db->query("CALL tempimagedetails('" . $temp_id . "','')")->result();
			
				foreach($TempDetails as $Temp) {
					$content_type = $Temp->contenttype;
					$imagecontent_id = $Temp->imagecontent_id;
					$ArrayImageName = explode(".",$Temp->image_name);
					$image_name = $ArrayImageName[0];
				}
			
				if(isset($content_type)) {
				
					$SourceURL  		= imagelibrary_image_path;
					
					switch($content_type) {
						case 1:
							$DestinationURL = article_temp_image_path;
							break;
						case 2:
							$DestinationURL = imagelibrary_temp_image_path;
							break;
						case 3:
							$DestinationURL = gallery_temp_image_path;			
							break;
						case 4:
							$DestinationURL = video_temp_image_path;			
							break;
						case 5:
							$DestinationURL = audio_temp_image_path;			
							break;
						case 6:
							$DestinationURL = resource_temp_image_path;
							break;
						default:
							$DestinationURL = resource_temp_image_path;
							break;
					}
					
					
					if($imagecontent_id == '' || $imagecontent_id == 'NULL' ) {
						$this->common_resize_all_images($TempDetails);
					} else {
						$ImageDetails 	= GetImageDetailsByContentId($imagecontent_id);
						
						$path = $ImageDetails['ImagePhysicalPath'];
						
						$NewPath = GenerateNewImageName($path, $image_name);
						
						ImageLibraryCopyToTemp($path,$NewPath, $SourceURL, $DestinationURL);
					}
					
					echo json_encode(array("status" => "success"));
			} else {
				echo json_encode(array("status" => "failure"));
			}
		} else {
			$this->db->query("CALL update_temp_save_status(".$temp_id.",".$save_status.")");
			echo json_encode(array("status" => "success"));
		}
	}
	public function get_temp_details($temp_id) 
	{
				
				$query = $this->db->query("CALL tempimagedetails('" . $temp_id . "','')");
				
				return $query->row_array();
	}
	
	public function add_article_cms_to_livecontents($content_id) {
			
		$ContentDetails = $this->db->query('CALL get_article_by_id(' . $content_id . ')')->result_array();
	
		
if(isset($ContentDetails)) {

	foreach($ContentDetails as $Article) {

# Start the Live Article Table Details 
		
		$LiveArticleDetails = array();
		
		$LiveArticleDetails['ecenic_id'] 								= 'NULL';
		$LiveArticleDetails['section_id'] 								= 'NULL';
		$LiveArticleDetails['section_name'] 							= '';
		
		$LiveArticleDetails['parent_section_id'] 						= 'NULL';
		$LiveArticleDetails['parent_section_name'] 						= '';
		
		$LiveArticleDetails['grant_section_id'] 						= 'NULL';
		$LiveArticleDetails['grant_parent_section_name'] 				= '';
		
		$LiveArticleDetails['linked_to_columnist']                      = 0;
		
		# Home Image Empty Data
		
		$LiveArticleDetails['home_page_image_path'] 					= '';
		$LiveArticleDetails['home_page_image_title'] 					= '';
		$LiveArticleDetails['home_page_image_alt'] 						= '';
	
		# Section Image Empty Data
		
		$LiveArticleDetails['section_page_image_path'] 						= '';
		$LiveArticleDetails['section_page_image_title'] 					= '';
		$LiveArticleDetails['section_page_image_alt'] 						= '';
	
		# Article Image Empty Data
		
		$LiveArticleDetails['article_page_image_path'] 						= '';
		$LiveArticleDetails['article_page_image_title'] 					= '';
		$LiveArticleDetails['article_page_image_alt'] 						= '';
		
		$LiveArticleDetails['url'] 											= "";
		$LiveArticleDetails['tags']											= "";
		
		# Author Image Empty Data
		
		$LiveArticleDetails['author_image_path'] 						= '';
		$LiveArticleDetails['author_image_title'] 					= '';
		$LiveArticleDetails['author_image_alt'] 						= '';
		
		$LiveArticleDetails['column_name'] 									= '';
		$LiveArticleDetails['hits']											= 0;
		
	
		$LiveArticleDetails['allow_comments']								= 0;
		$LiveArticleDetails['allow_pagination']								= 0;
		$LiveArticleDetails['agency_name'] 									= '';
		$LiveArticleDetails['author_name']									= '';
		
		$LiveArticleDetails['country_name'] 								= '';
		$LiveArticleDetails['state_name'] 									= '';
		$LiveArticleDetails['city_name'] 									= '';
		
		$LiveArticleDetails['no_indexed']									= 0;
		$LiveArticleDetails['no_follow']									= 0;
		$LiveArticleDetails['section_promotion'] 							= 0;
		$LiveArticleDetails['status'] 										= 'P';
			
			
			$MainSection = get_section_by_id($Article['Section_id']);
			
			if(isset($MainSection)) {
			
			$LiveArticleDetails['section_id'] 			= $MainSection['Section_id'];
			$LiveArticleDetails['section_name'] 		= $MainSection['Sectionname'];
			
			if($MainSection['AuthorID'] != '' && $MainSection['AuthorID'] != 'NULL' && $MainSection['AuthorID'] != 0) {			   $AuthorDetails 								= get_authordetails_by_id($MainSection['AuthorID']);
						$LiveArticleDetails['linked_to_columnist'] 	 = 1;
						$column_id 			 						 = $AuthorDetails['column_id'];
						
						
									
						$LiveArticleDetails['author_name'] 		= $AuthorDetails['AuthorName'];			
						
						$LiveArticleDetails['author_image_path'] 					= @addslashes($AuthorDetails['image_path']);
						$LiveArticleDetails['author_image_title'] 					= @addslashes($AuthorDetails['image_alt']);
						$LiveArticleDetails['author_image_alt'] 					= @addslashes($AuthorDetails['image_caption']);
						
						
						/*if($AuthorDetails['image_id'] != '' && $AuthorDetails['image_id'] != 'NULL' && $AuthorDetails['image_id'] != 0) {
							$AuthorImageDetails = GetImageDetailsByContentId($AuthorDetails['image_id']);
			
							$LiveArticleDetails['author_image_path'] 					= @addslashes($AuthorImageDetails['ImagePhysicalPath']);
							$LiveArticleDetails['author_image_title'] 					= @addslashes($AuthorImageDetails['ImageCaption']);
							$LiveArticleDetails['author_image_alt'] 					= @addslashes($AuthorImageDetails['ImageAlt']);
						} */
						
			}
			
				if(isset($Article['ParentSectionID']) && $Article['ParentSectionID'] != '') {
					
					$ParentMainSection = get_section_by_id($Article['ParentSectionID']);
					
					if(isset($ParentMainSection['Section_id'])) {
					$LiveArticleDetails['parent_section_id'] 						= 	$ParentMainSection['Section_id'];
					$LiveArticleDetails['parent_section_name'] 						= 	$ParentMainSection['Sectionname'];
					}
					
					if(isset($ParentMainSection['ParentSectionID']) && $ParentMainSection['ParentSectionID'] != '') {
					

						$GrantMainSection = get_section_by_id($ParentMainSection['ParentSectionID']);
						
						if(isset($GrantMainSection['Section_id'])) {
						$LiveArticleDetails['grant_section_id'] 						= 	$GrantMainSection['Section_id'];
						$LiveArticleDetails['grant_parent_section_name'] 				= 	$GrantMainSection['Sectionname'];
						}
					}
					
				}
			}
			
			if ($Article['publish_start_date'] != '' && $Article['publish_start_date']  != '0000-00-00 00:00:00')
				$LiveArticleDetails['publish_start_date'] = date('Y-m-d H:i', strtotime($Article['publish_start_date']));
			else 
				$LiveArticleDetails['publish_start_date'] = date('Y-m-d H:i', strtotime($Article['Modifiedon']));
				
			if($Article['publish_end_date'] != '' && $Article['publish_end_date']  != '0000-00-00 00:00:00')
				$LiveArticleDetails['publish_end_date']	= date('Y-m-d H:i', strtotime($Article['publish_end_date']));
			else 
				$LiveArticleDetails['publish_end_date']	= '';
				
			$LiveArticleDetails['last_updated_on'] 				= date('Y-m-d H:i', strtotime($Article['Modifiedon']));
			$LiveArticleDetails['title'] 						= $Article['title'];
			$LiveArticleDetails['url_title'] 					= $Article['url_title'];
			
			/*
			$url_structure = preg_replace('/[^A-Za-z0-9\-\s]/', '', @$Article['url_title']);
			$url_structure = join( "-",( explode(" ",@$url_structure) ) );
			
			$Year =  date('Y', strtotime($LiveArticleDetails['publish_start_date']));
			$Month =  date('M', strtotime($LiveArticleDetails['publish_start_date']));
			$Date =  date('d', strtotime($LiveArticleDetails['publish_start_date']));
		
			$LiveArticleDetails['url'] 							= join( "-",( explode(" ",@$MainSection['URLSectionStructure'] )))."/".preg_replace('/[^A-Za-z0-9\-]/', '', $url_structure)."/".$Year."/".$Month."/".$Date."/".$content_id.".html";
			*/
			$LiveArticleDetails['url']  						= $Article['url'];
			
			$LiveArticleDetails['summary_html'] 				= $Article['summaryHTML'];
			$LiveArticleDetails['article_page_content_html'] 	= $Article['ArticlePageContentHTML'];

			if($Article['homepageimageid'] != 'NULL' && $Article['homepageimageid'] != '') {
				
				$HomeImageDetails = GetImageDetailsByContentId($Article['homepageimageid']);

				$LiveArticleDetails['home_page_image_path'] 					= $HomeImageDetails['ImagePhysicalPath'];
				$LiveArticleDetails['home_page_image_title'] 					= $HomeImageDetails['ImageCaption'];
				$LiveArticleDetails['home_page_image_alt'] 						= $HomeImageDetails['ImageAlt'];
			}
	
			if($Article['Sectionpageimageid'] != 'NULL' && $Article['Sectionpageimageid'] != '') {
				
				$SectionImageDetails = GetImageDetailsByContentId($Article['Sectionpageimageid']);

				$LiveArticleDetails['section_page_image_path'] 						= $SectionImageDetails['ImagePhysicalPath'];
				$LiveArticleDetails['section_page_image_title'] 					= $SectionImageDetails['ImageCaption'];
				$LiveArticleDetails['section_page_image_alt'] 						= $SectionImageDetails['ImageAlt'];
			}

			if($Article['articlepageimageid'] != 'NULL' && $Article['articlepageimageid'] != '') {
					
				$ArticleImageDetails = GetImageDetailsByContentId($Article['articlepageimageid']);

				$LiveArticleDetails['article_page_image_path'] 						= $ArticleImageDetails['ImagePhysicalPath'];
				$LiveArticleDetails['article_page_image_title'] 					= $ArticleImageDetails['ImageCaption'];
				$LiveArticleDetails['article_page_image_alt'] 						= $ArticleImageDetails['ImageAlt'];
			}
			
			if(isset($column_id) && $column_id != '' && $column_id != 'NULL') {
				$ColumnDetails = column_editdetails($column_id);
				
				if(isset($ColumnDetails['column_name']))
					$LiveArticleDetails['column_name'] = $ColumnDetails['column_name'];
			}
		
			if(isset($Article['Tags'] ) && trim($Article['Tags'] ) != '') {
			$TagArray	= $this->common_model->get_tags_by_id($Article['Tags']);
			$NewTagArray = array();
			
				foreach($TagArray as $Tags) {
					$NewTagArray[] = $Tags->tag_name;
				}
			$LiveArticleDetails['tags']		= implode(",",$NewTagArray);			
			}
			
			if($Article['Author_ID'] != "NULL" && $Article['Author_ID'] != "") {
				
				$AuthorDetails 							= get_authordetails_by_id($Article['Author_ID']);
				
				$LiveArticleDetails['author_name'] 							= $AuthorDetails['AuthorName'];
				
				if($LiveArticleDetails['author_image_path']  == '' && @$AuthorDetails['image_path'] != '' ) {
	
					$LiveArticleDetails['author_image_path'] 					= @$AuthorDetails['image_path'];
					$LiveArticleDetails['author_image_title'] 					= @$AuthorDetails['image_caption'];
					$LiveArticleDetails['author_image_alt'] 					= @$AuthorDetails['image_alt'];
					
				}
			}
			
			if($Article['Agency_ID'] != "NULL" && $Article['Agency_ID'] != "")
				$LiveArticleDetails['agency_name'] 			= @get_agencyname_by_id($Article['Agency_ID']);
				
			if($Article['Country_ID'] != "NULL" && $Article['Country_ID'] != "")
				$LiveArticleDetails['country_name'] 		= @get_countryname_by_id($Article['Country_ID']);
				
			if($Article['State_ID'] != "NULL" && $Article['State_ID'] != "")	
				$LiveArticleDetails['state_name'] 			= @get_statename_by_id($Article['State_ID']);
				
			if($Article['City_ID'] != "NULL" && $Article['City_ID'] != "")	
				$LiveArticleDetails['city_name'] 			= @get_cityname_by_id($Article['City_ID']);

		
			$LiveArticleDetails['allow_comments'] 			=  $Article['Allowcomments'];
			$LiveArticleDetails['allow_pagination'] 		=  $Article['allow_pagination'];
			
			$LiveArticleDetails['no_indexed'] 				=  $Article['Noindexed'] ;
			
			$LiveArticleDetails['no_follow'] 				=  $Article['Nofollow'];
			
			$LiveArticleDetails['canonical_url']  			=	$Article['Canonicalurl'];
			$LiveArticleDetails['meta_Title']  				=	$Article['MetaTitle'];
			$LiveArticleDetails['meta_description']  		= 	$Article['MetaDescription'];
			$LiveArticleDetails['content_id']  				= 	$Article['content_id'];
			$LiveArticleDetails['section_promotion'] 		= 	$Article['section_promotion'];
			$LiveArticleDetails['status'] 				= 'P';

				$Livecount = $this->live_content_model->check_livecontents($Article['content_id'], 1);
				
				if($Livecount <= 0) 
					$this->live_content_model->insert_article($LiveArticleDetails);
				else
					$this->live_content_model->update_article($LiveArticleDetails);	
					
				$this->live_content_model->delete_section_mapping($Article['content_id'],1);

	
		$Multiple_section_mapping = $this->common_model->get_content_mapping($Article['content_id'],1)->result_array();
		
		if(isset($Multiple_section_mapping)) {
			foreach($Multiple_section_mapping as $mapping) {
						$live_insert_array 		= array();
						
						$live_insert_array[] 	= $Article['content_id'];
						$live_insert_array[] 	=  $mapping['Section_ID'];
						
						$this->live_content_model->insert_section_mapping($live_insert_array,1);	
			}
		}
		
		$this->live_db->query("CALL delete_article_related_content (". $Article['content_id'].")");
		
		$related_article_details = $this->db->query('CALL get_related_article_by_id (' . $Article['content_id'] . ')')->result_array();
		
		if(isset($related_article_details)) {
			foreach($related_article_details as $related){
				if($related['Related_content_id'] != 'NULL' && $related['Related_content_id'] != '') {
					
					$related_article_details = $this->db->query('CALL get_article_by_id(' . $related['Related_content_id'] . ')')->result_array();
				
					$content_title	= addslashes(@$related_article_details[0]['title']);
					$content_url 	= addslashes(@$related_article_details[0]['url']);
					
					$this->live_db->query('CALL add_article_related_content ("' . $Article['content_id'] . '","'.$related['Related_content_id'].'","'.$content_title.'","'.$content_url.'","'.$related['DisplayPriorty'].'")');
				} else {
					$this->live_db->query('CALL add_article_related_content ("' . $Article['content_id'] . '","'.$related['Related_content_id'].'","'.$related['ExternalArticletitle'].'","'.$related['ExternalArticleURL'].'","'.$related['DisplayPriorty'].'")');
				}
			}
		}
		
	
	
		# End the Live Article Table Details 
			
			}

		}
			
	}
	
	
	
	public function add_gallery_cms_to_livecontents($gallery_id) {
			
		$ContentDetails = $this->db->query('CALL get_gallery_by_id(' . $gallery_id . ')')->result_array();
	
		
if(isset($ContentDetails)) {

	foreach($ContentDetails as $Gallery) {

# Start the Live Gallery Table Details 
		
		$LiveGalleryDetails = array();
		
		$LiveGalleryDetails['ecenic_id'] 								= 'NULL';
		$LiveGalleryDetails['section_id'] 								= 'NULL';
		$LiveGalleryDetails['section_name'] 							= '';
		
		$LiveGalleryDetails['parent_section_id'] 						= 'NULL';
		$LiveGalleryDetails['parent_section_name'] 						= '';
		
		$LiveGalleryDetails['grant_section_id'] 						= 'NULL';
		$LiveGalleryDetails['grant_parent_section_name'] 				= '';
	
		$LiveGalleryDetails['url'] 											= "";
		$LiveGalleryDetails['tags']											= "";
		

		$LiveGalleryDetails['hits']											= 0;
		
	
		$LiveGalleryDetails['allow_comments']								= 0;
		$LiveGalleryDetails['agency_name'] 									= '';
		$LiveGalleryDetails['author_name']									= '';
		
		$LiveGalleryDetails['country_name'] 								= '';
		$LiveGalleryDetails['state_name'] 									= '';
		$LiveGalleryDetails['city_name'] 									= '';
		
		$LiveGalleryDetails['no_indexed']									= 0;
		$LiveGalleryDetails['no_follow']									= 0;
		$LiveGalleryDetails['status'] 										= 'P';
			
			
			$MainSection = get_section_by_id($Gallery['Section_id']);
			
			if(isset($MainSection)) {
			
			$LiveGalleryDetails['section_id'] 			= $MainSection['Section_id'];
			$LiveGalleryDetails['section_name'] 		= $MainSection['Sectionname'];
			
				if(isset($Gallery['ParentSectionID']) && $Gallery['ParentSectionID'] != '') {
					
					$ParentMainSection = get_section_by_id($Gallery['ParentSectionID']);
					
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
			
			if ($Gallery['publish_start_date'] != '' && $Gallery['publish_start_date']  != '0000-00-00 00:00:00')
				$LiveGalleryDetails['publish_start_date'] = date('Y-m-d H:i', strtotime($Gallery['publish_start_date']));
			else 
				$LiveGalleryDetails['publish_start_date'] = date('Y-m-d H:i', strtotime($Gallery['Modifiedon']));
			
			$LiveGalleryDetails['last_updated_on'] 				= date('Y-m-d H:i', strtotime($Gallery['Modifiedon']));
			$LiveGalleryDetails['title'] 						= $Gallery['title'];
			
					/*
			$url_structure = preg_replace('/[^A-Za-z0-9\-\s]/', '', @$Gallery['url_title']);
			$url_structure = join( "-",( explode(" ",@$url_structure) ) );
		
			$Year =  date('Y', strtotime($LiveGalleryDetails['publish_start_date']));
			$Month =  date('m', strtotime($LiveGalleryDetails['publish_start_date']));
			$Date =  date('d', strtotime($LiveGalleryDetails['publish_start_date']));
		
			$LiveGalleryDetails['url'] 							= join( "-",( explode(" ",@$MainSection['URLSectionStructure'] )))."/".preg_replace('/[^A-Za-z0-9\-]/', '', $url_structure)."/".$Year."/".$Month."/".$Date."/".$gallery_id.".html";
			*/
			
			$LiveGalleryDetails['url']   						= $Gallery['url'];
			
			$LiveGalleryDetails['summary_html'] 				= $Gallery['summaryHTML'];
			$LiveGalleryDetails['summary_plain_text'] 			= strip_tags($Gallery['summaryHTML']);
		
			if(isset($Gallery['Tags'] ) && trim($Gallery['Tags'] ) != '') {
			$TagArray	= $this->common_model->get_tags_by_id($Gallery['Tags']);
			$NewTagArray = array();
			
				foreach($TagArray as $Tags) {
					$NewTagArray[] = $Tags->tag_name;
				}
			$LiveGalleryDetails['tags']		= implode(",",$NewTagArray);			
			}
			
			if($Gallery['Author_ID'] != "NULL" && $Gallery['Author_ID'] != "") {
				
				$AuthorDetails 							= get_authordetails_by_id($Gallery['Author_ID']);
				
				$LiveGalleryDetails['author_name'] 							= addslashes($AuthorDetails['AuthorName']);
				
			}
			
			
			if($Gallery['Agency_ID'] != "NULL" && $Gallery['Agency_ID'] != "")
				$LiveGalleryDetails['agency_name'] 			= @get_agencyname_by_id($Gallery['Agency_ID']);
				
			if($Gallery['Country_ID'] != "NULL" && $Gallery['Country_ID'] != "")
				$LiveGalleryDetails['country_name'] 		= @get_countryname_by_id($Gallery['Country_ID']);
				
			if($Gallery['State_ID'] != "NULL" && $Gallery['State_ID'] != "")	
				$LiveGalleryDetails['state_name'] 			= @get_statename_by_id($Gallery['State_ID']);
				
			if($Gallery['City_ID'] != "NULL" && $Gallery['City_ID'] != "")	
				$LiveGalleryDetails['city_name'] 			= @get_cityname_by_id($Gallery['City_ID']);

		
			$LiveGalleryDetails['allow_comments'] 			=  $Gallery['Allowcomments'];
			
			$LiveGalleryDetails['no_indexed'] 				=  $Gallery['Noindexed'] ;
			
			$LiveGalleryDetails['no_follow'] 				=  $Gallery['Nofollow'];
			
			$LiveGalleryDetails['canonical_url']  			=	$Gallery['Canonicalurl'];
			$LiveGalleryDetails['meta_Title']  				=	$Gallery['MetaTitle'];
			$LiveGalleryDetails['meta_description']  		= 	$Gallery['MetaDescription'];
			$LiveGalleryDetails['content_id']  				= 	$Gallery['content_id'];
			$LiveGalleryDetails['status'] 					= 'P';
			
			$ImageDetails 	= $this->db->query('CALL get_gallery_images(' . $Gallery['content_id'] . ')')->result_array();
			$Livecount 		= $this->live_content_model->check_livecontents($Gallery['content_id'], 3);
				
				if($Livecount <= 0) {
					
					if(isset($ImageDetails)) {
						foreach($ImageDetails as $key=>$Image) {
						
					if($key == 0) {
						$LiveGalleryDetails['first_image_path']  	= $Image['ImagePhysicalPath'];
						$LiveGalleryDetails['first_image_title']  	= $Image['ImageCaption'];
						$LiveGalleryDetails['first_image_alt'] 	 	= $Image['ImageAlt'];
						$LiveGalleryDetails['content_id']  			= $Gallery['content_id'];
						$LiveGalleryDetails['url']					= $Gallery['url'];
						$LiveGalleryDetails['status']				= 'P';
						
						$this->live_content_model->insert_gallery($LiveGalleryDetails);
					}
					
					$GalleryRelatedImages =  array(
											"content_id" 					=> $Gallery['content_id'],
											"gallery_image_path" 			=> $Image['ImagePhysicalPath'],
											"gallery_image_title" 			=> $Image['ImageCaption'],
											"gallery_image_alt" 			=> $Image['ImageAlt'],
											"display_order"					=> $Image['display_order']
											);
						$this->live_content_model->insert_gallery_related_images($GalleryRelatedImages);
						}
					
					}
					
				} else {
					
						$this->live_content_model->delete_gallery_related_images($Gallery['content_id']);
						
								if(isset($ImageDetails)) {
									foreach($ImageDetails as $key=>$Image) {
								
										if($key == 0) {
										$LiveGalleryDetails['first_image_path']  	= $Image['ImagePhysicalPath'];
										$LiveGalleryDetails['first_image_title']  	= $Image['ImageCaption'];
										$LiveGalleryDetails['first_image_alt'] 	 	= $Image['ImageAlt'];
										$LiveGalleryDetails['content_id']  			= $Gallery['content_id'];
										$LiveGalleryDetails['url']					= $Gallery['url'];
										$LiveGalleryDetails['status']				= 'P';
										
										$this->live_content_model->update_gallery($LiveGalleryDetails);
										}
										
										$GalleryRelatedImages =  array(
																"content_id" 					=> $Gallery['content_id'],
																"gallery_image_path" 			=> $Image['ImagePhysicalPath'],
																"gallery_image_title" 			=> $Image['ImageCaption'],
																"gallery_image_alt" 			=> $Image['ImageAlt'],
																"display_order"					=> $Image['display_order']
																);
										$this->live_content_model->insert_gallery_related_images($GalleryRelatedImages);
									}
								}
				}	
					
		$this->live_content_model->delete_section_mapping($Gallery['content_id'],3);
				
		$Multiple_section_mapping = $this->common_model->get_content_mapping($Gallery['content_id'],3)->result_array();
		
		if(isset($Multiple_section_mapping)) {
			foreach($Multiple_section_mapping as $mapping) {
						$live_insert_array 		= array();
						
						$live_insert_array[] 	= $Gallery['content_id'];
						$live_insert_array[] 	=  $mapping['Section_ID'];
						
						$this->live_content_model->insert_section_mapping($live_insert_array,3);	
			}
		}
		
		# End the Live Article Table Details 
			
			}

		}
			
	}
	
	public function add_audio_video_cms_to_livecontents($content_id,$content_type) {
		
			$ContentDetails = $this->db->query('CALL get_audio_video_by_id(' . $content_id . ','.$content_type.')')->result_array();
	
		
if(isset($ContentDetails)) {

	foreach($ContentDetails as $Video) {

# Start the Live Video Table Details 
		
		$LiveVideoDetails = array();
		
		$LiveVideoDetails['ecenic_id'] 								= 'NULL';
		$LiveVideoDetails['section_id'] 								= 'NULL';
		$LiveVideoDetails['section_name'] 							= '';
		
		$LiveVideoDetails['parent_section_id'] 						= 'NULL';
		$LiveVideoDetails['parent_section_name'] 						= '';
		
		$LiveVideoDetails['grant_section_id'] 						= 'NULL';
		$LiveVideoDetails['grant_parent_section_name'] 				= '';
	
		$LiveVideoDetails['url'] 									= $Video['url'];
		$LiveVideoDetails['tags']									= "";
		

		$LiveVideoDetails['hits']									= 0;
		
	
		$LiveVideoDetails['allow_comments']							= 0;
		$LiveVideoDetails['agency_name'] 							= '';
		$LiveVideoDetails['author_name']							= '';
		
		$LiveVideoDetails['country_name'] 								= '';
		$LiveVideoDetails['state_name']									= '';
		$LiveVideoDetails['city_name'] 									= '';
		
		$LiveVideoDetails['no_indexed']									= 0;
		$LiveVideoDetails['no_follow']									= 0;
		$LiveVideoDetails['status'] 									= 'P';
			
		$LiveVideoDetails['audio_video_image_path']  				= $Video['ImagePhysicalPath'];
		$LiveVideoDetails['audio_video_image_title']  			= $Video['ImageCaption'];
		$LiveVideoDetails['audio_video_image_alt']  			= $Video['ImageAlt'];
			
			
			$MainSection = get_section_by_id($Video['Section_id']);
			
			if(isset($MainSection)) {
			
			$LiveVideoDetails['section_id'] 			= $MainSection['Section_id'];
			$LiveVideoDetails['section_name'] 		= $MainSection['Sectionname'];
			
				if(isset($Video['ParentSectionID']) && $Video['ParentSectionID'] != '') {
					
					$ParentMainSection = get_section_by_id($Video['ParentSectionID']);
					
					if(isset($ParentMainSection['Section_id'])) {
					$LiveVideoDetails['parent_section_id'] 						= 	$ParentMainSection['Section_id'];
					$LiveVideoDetails['parent_section_name'] 						= 	$ParentMainSection['Sectionname'];
					}
					
					if(isset($ParentMainSection['ParentSectionID']) && $ParentMainSection['ParentSectionID'] != '') {
					

						$GrantMainSection = get_section_by_id($ParentMainSection['ParentSectionID']);
						
						if(isset($GrantMainSection['Section_id'])) {
						$LiveVideoDetails['grant_section_id'] 						= 	$GrantMainSection['Section_id'];
						$LiveVideoDetails['grant_parent_section_name'] 				= 	$GrantMainSection['Sectionname'];
						}
					}
					
				}
			}
			
			if ($Video['publish_start_date'] != '' && $Video['publish_start_date']  != '0000-00-00 00:00:00')
				$LiveVideoDetails['publish_start_date'] = date('Y-m-d H:i', strtotime($Video['publish_start_date']));
			else 
				$LiveVideoDetails['publish_start_date'] = date('Y-m-d H:i', strtotime($Video['Modifiedon']));
			
			$LiveVideoDetails['last_updated_on'] 				= date('Y-m-d H:i', strtotime($Video['Modifiedon']));
			$LiveVideoDetails['title'] 						= $Video['title'];
			$LiveVideoDetails['summary_html'] 				= $Video['summaryHTML'];
			
			if($content_type == 4) {
			
			$LiveVideoDetails['script']				= $Video['VideoScript'];
			$LiveVideoDetails['video_site']			=  $Video['VideoSite'];
			} else {
			$LiveVideoDetails['audio_path']				= $Video['Audio_path'];	
			}
			
		
			if(isset($Video['Tags'] ) && trim($Video['Tags'] ) != '') {
			$TagArray	= $this->common_model->get_tags_by_id($Video['Tags']);
			$NewTagArray = array();
			
				foreach($TagArray as $Tags) {
					$NewTagArray[] = $Tags->tag_name;
				}
			$LiveVideoDetails['tags']		= implode(",",$NewTagArray);			
			}
			
			if($Video['Author_ID'] != "NULL" && $Video['Author_ID'] != "") {
				$AuthorDetails 						= get_authordetails_by_id($Video['Author_ID']);
				$LiveVideoDetails['author_name'] 	= addslashes($AuthorDetails['AuthorName']);
			}
	
			if($Video['Agency_ID'] != "NULL" && $Video['Agency_ID'] != "")
				$LiveVideoDetails['agency_name'] 			= @get_agencyname_by_id($Video['Agency_ID']);
				
			if($Video['Country_ID'] != "NULL" && $Video['Country_ID'] != "")
				$LiveVideoDetails['country_name'] 		= @get_countryname_by_id($Video['Country_ID']);
				
			if($Video['State_ID'] != "NULL" && $Video['State_ID'] != "")	
				$LiveVideoDetails['state_name'] 			= @get_statename_by_id($Video['State_ID']);
				
			if($Video['City_ID'] != "NULL" && $Video['City_ID'] != "")	
				$LiveVideoDetails['city_name'] 			= @get_cityname_by_id($Video['City_ID']);

		
			$LiveVideoDetails['allow_comments'] 			=  $Video['Allowcomments'];
			
			$LiveVideoDetails['no_indexed'] 				=  $Video['Noindexed'] ;
			
			$LiveVideoDetails['no_follow'] 				=  $Video['Nofollow'];
			
			$LiveVideoDetails['canonical_url']  			=	$Video['Canonicalurl'];
			$LiveVideoDetails['meta_Title']  				=	$Video['MetaTitle'];
			$LiveVideoDetails['meta_description']  		= 	$Video['MetaDescription'];
			$LiveVideoDetails['content_id']  				= 	$Video['content_id'];
			$LiveVideoDetails['status'] 					= 'P';
			
			if($content_type == 4)
				$this->live_content_model->insert_update_live_video($LiveVideoDetails);
			else
				$this->live_content_model->insert_update_live_audio($LiveVideoDetails);
			
			$this->live_content_model->delete_section_mapping($Video['content_id'],$content_type);
				
		$Multiple_section_mapping = $this->common_model->get_content_mapping($Video['content_id'],$content_type)->result_array();
		
		if(isset($Multiple_section_mapping)) {
			foreach($Multiple_section_mapping as $mapping) {
						$live_insert_array 		= array();
						
						$live_insert_array[] 	= $Video['content_id'];
						$live_insert_array[] 	=  $mapping['Section_ID'];
						
						$this->live_content_model->insert_section_mapping($live_insert_array,$content_type);	
			}
		}
		
			
			
	}
}
		
	}
	
	public function add_resources_cms_to_livecontents($content_id) {
		
	
		$ContentDetails = $this->db->query('CALL get_resources_by_id (' . $content_id . ')')->result_array();

		if(isset($ContentDetails)) {

			foreach($ContentDetails as $Resources) {
				
				$LiveResourcesDetails['content_id']  			= $content_id;
				$LiveResourcesDetails['ecenic_id'] 	 			= 'NULL';
				$LiveResourcesDetails['title']  				= $Resources['title'];
				$LiveResourcesDetails['url']  					= $Resources['url'];
				$LiveResourcesDetails['resource_url']  			= $Resources['resource_url'];
				$LiveResourcesDetails['article_id']  			= $Resources['content_id'];
				$LiveResourcesDetails['image_path']  			= $Resources['ImagePhysicalPath'];
				$LiveResourcesDetails['image_caption']  		= $Resources['ImageCaption'];
				$LiveResourcesDetails['image_alt']  			= $Resources['ImageAlt'];
				$LiveResourcesDetails['publish_start_date']  	= $Resources['publish_start_date'];
				$LiveResourcesDetails['last_updated_on']  		= date('Y-m-d H:i');
				
				$Livecount = $this->live_content_model->check_livecontents($content_id, 6);
					
				if($Livecount <= 0) 
					$this->live_content_model->insert_resources($LiveResourcesDetails);
				else
					$this->live_content_model->update_resources($LiveResourcesDetails);	
		
		}
				
			}
		}
		
		
	
}

class autocomplete_text extends Common_model
{
	function txt_byliner($term,$author_type)
	{
		/* if($agencyid == 0) 
			$agencyid = "NULL"; */
		
	/*	$search = 'AuthorName LIKE "%'.$term.'%"';
		$get_result = $this->db->query("CALL get_byline_text('".$search."','".$author_type."')"); */
		
		$this->db->select("*");
		$this->db->from("authormaster");
		$this->db->where("authorType",$author_type);
		$this->db->like("AuthorName",addslashes($term),"both");
		$get_result = $this->db->get();

		return $get_result->result_array();
	}
	function txt_state($term,$countryid)
	{
		$get_result = $this->db->query("CALL get_state_text('".$term."','".$countryid."')");
		
		return $get_result->result_array();
	}
	function txt_city($term,$stateid)
	{
		$get_result = $this->db->query("CALL get_city_text('".$term."','".$stateid."')");		
		return $get_result->result_array();
	}
}
class Common_model_class extends Common_model 
{
	
	public function get_byline_details() 
	{
		
		$byline = $this->db->query('CALL get_author()');
		
		return $byline->result();
	}
	public function get_agency_details() 
	{
		
		$agency = $this->db->query('CALL get_agency("")');
		
		return $agency->result();
	}
	public function get_country_details() 
	{
		
		$country = $this->db->query('CALL get_country()');
		
		return $country->result();
	}
	public function get_state_details($country_id)
	{
		
		$state = $this->db->query('CALL get_state("'.$country_id.'")');
		
		return $state->result();
	}
	public function get_author_agency_id($agency_id)
	{
		
		$author = $this->db->query('CALL get_author_by_agency_id("'.$agency_id.'")');
		
		return $author->result();
	}
	public function get_city_details($country_id, $state_id) 
	{
		
		$city = $this->db->query('CALL get_city("'.$state_id.'")');
		
		return $city->result();
	}
	
	
	public function get_content_type_byname($content_type_name) 
	{
		
		$type = $this->db->query("CALL get_content_type('".$content_type_name."')");
		
		return $type->row_array();
	}
	
}
class Common_section_class extends Common_model {
	
	public function multiple_section_mapping()
	{
		
		
		$empty_val = '';

		$list_multi_sectn = $this->db->query('CALL get_section("'.$empty_val.'")');
		
		$get_result = $list_multi_sectn->result_array();
		
            foreach($get_result as $key => $get_multi_section)
            {
				
				$get_sec_id = $get_multi_section['Section_id'];
				$list_multi_sectn = $this->db->query('CALL get_section("'.$get_sec_id.'")');
				
               $sub_section_result = $list_multi_sectn->result_array();
				
				foreach($sub_section_result as $sub_key => $sub_section) {
					
					$get_sub_sec_id = $sub_section['Section_id'];
					$sub_list_multi_sectn = $this->db->query('CALL get_section("'.$get_sub_sec_id.'")');
					
					$sub_section_result[$sub_key]['sub_sub_section'] = $sub_list_multi_sectn->result_array();
					
				}
				
				$get_multi_section['sub_section'] = $sub_section_result;
				
				
                $get_result[$key] = $get_multi_section;
            }
			
		/*	echo "<pre>";
			print_r($get_result);
			exit;  */
		
            return $get_result;
	}
	public function multiple_section_mapping_by_section_id($sec_id)
	{
		
		$empty_val = '';
		
		$list_multi_sectn = $this->db->query('CALL get_section("'.$sec_id.'")');
		
		$get_result = $list_multi_sectn->result_array();

            foreach($get_result as $key => $get_multi_section)
            {
				
				$get_sec_id = $get_multi_section['Section_id'];
				$list_multi_sectn = $this->db->query('CALL get_section("'.$get_sec_id.'")');
				
                $get_multi_section['sub_section'] = $list_multi_sectn->result_array();
                $get_result[$key] = $get_multi_section;
            }
            return $get_result;
	}
	
	public function get_homePage_xml(){
		$home_page_section = $this->db->query("CALL get_homePage_template_versionId()");	
		$home_page_details = $home_page_section->row_array();
		return $home_page_details;
	}
}


class Common_content_class extends Common_model {
	
	/*
	*
	* Get content mapping based on content type (1)
	*
	* @access public
	* @param content_id, content_type
	* @return result of query 
	*/
	
	public function get_content_mapping($content_id, $content_type) {
		
		$type = $this->db->query('CALL get_content_mapping ('.$content_id.','.$content_type.')');
		
		return $type;
	}
	public function get_content_details($content_id) {
		
		$type_value = $this->db->query('CALL get_content_details ('.$content_id.')');
		
		return $type_value;
	}
	public function get_content_datatables()
	{
		
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
		if($draw == 1) {
			if($Page_name == 'image_manager') $Field = 5; else $Field = 6;
		}
	
		
		 switch($Page_name) {
			 case "article_manager":
				$content_type = "1";
			   $menu_name		= "Article";
			 
			 break;
			 case "image_manager"; 
			 	$content_type = "2";
			   $menu_name		= "Image Library";
			 break;
			 			case "gallery_manager"; 
			 	$content_type = "3";
			   	$menu_name		= "Gallery";
			 
			 break;
			 			case "video_manager"; 
			 $content_type = "4";
			 $edit_url = "audio_video_manager/edit_data/";
			   $menu_name		= "Video";
			 break;
			 			case "audio_manager"; 
			 $content_type = "5";
			   $menu_name		= "Audio";
			 break;
			    		default: 
			$content_type = "1"; 
			  $menu_name		= "Article";
		 }
		 
		 $Menu_id = get_menu_details_by_menu_name($menu_name);
		
	if($Page_name != 'image_manager') {
		
		switch ($Field) {
    case 0:
        $order_field = 'cv.Title';
        break;
    case 1:
        $order_field = 's.Sectionname';
        break;
	case 2:
        $order_field = 's.Sectionname';
        break;
	case 3:
        $order_field = 'cv.Title';
        break;
	case 4:
       $order_field = 'am.AuthorName';
	     break;
	case 5:
       $order_field = 'um.Username';
        break;
	case 6:
       $order_field = 'cm.Modifiedon';
        break;
	case 7:
       $order_field = 'cm.status';
        break;
	case 8:
       $order_field = 'cm.Hits';
        break;
    default:
        $order_field = 'cm.content_id';
		}
		
	} else {
		
		switch ($Field) {
    case 0:
        $order_field = 'cv.Title';
        break;
    case 1:
        $order_field = 's.Sectionname';
        break;
	case 2:
        $order_field = 's.Sectionname';
        break;
	case 3:
       $order_field = 'am.AuthorName';
	     break;
	case 4:
       $order_field = 'um.Username';
        break;
	case 5:
       $order_field = 'cm.Modifiedon';
        break;
	case 6:
       $order_field = 'cm.status';
        break;
	case 7:
       $order_field = 'cm.Hits';
        break;
		
    default:
        $order_field = 'cm.content_id';
		}
		
	}

		//
		$Total_rows = 250;//$this->db->query('CALL content_datatable("","","","","","","","'.$content_type.'")')->num_rows();

		$Search_value = $Search_text;
		
		if($Search_by == 'article_id') {
		$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		
		if($Search_result == '')
			$Search_value = $Search_text;
		else
			$Search_value = $Search_result;
		}
		
		
		
		if($check_in != '')  {
		$check_in_date 	= strtotime($check_in);
		$check_in = date('Y-m-d',$check_in_date);
		}
		
		if($check_out != '')  {
		$check_out_date = strtotime($check_out);
		$check_out = date('Y-m-d',$check_out_date); 
		}
				
		$Search_value = htmlentities($Search_value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		
		$Search_value =  str_replace("&#039","&#39",$Search_value);

		
		$article_manager =  $this->db->query('CALL content_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'")')->result_array();	
		
		$recordsFiltered = $this->db->query('CALL content_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT 0, 250 ","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'")')->num_rows();
		
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		

		foreach($article_manager as $article) {
			
				$article_image = '';
			
			 switch($Page_name) {
			 case "article_manager":
			 $edit_url = "edit_article/".urlencode(base64_encode($article['content_id']));
			 
				
				$article_manager = $this->db->query('CALL get_article_by_id(' . $article['content_id'] . ')');
				
				$article_details = $article_manager->result_array();
				
				$articleimageid = @$article_details[0]['articleimageid'];
				
				if(isset($articleimageid) && trim($articleimageid) != '')
				$article_image = get_image_by_contentid_type($articleimageid,0);
			
			
			 break;
			 case "image_manager"; 
			 $edit_url = "edit_image/".urlencode(base64_encode($article['content_id']));
			 break;
			 case "gallery_manager"; 
			 $edit_url = "edit_gallery/".urlencode(base64_encode($article['content_id']));
			 
			 if($article['contentversion_id'] != '') {
			 
			 
			$gallery_images = $this->db->query('CALL get_gallery_images(' . $article['contentversion_id'] . ')')->row();
			
			if(isset($gallery_images->ImagePhysicalPath)) {
				$article_image = image_url.imagelibrary_image_path.str_replace("original","w150X150", $gallery_images->ImagePhysicalPath);
			}
			
			 }
		
			 break;
			 case "video_manager"; 
			 $edit_url = "audio_video_manager/edit_data/4/".urlencode(base64_encode($article['content_id']));
			 
			 
		$fetch_values = $this->db->query('CALL get_video_audio_details("'.$article['content_id'].'", "4")')->row();
		
		if(isset($fetch_values->image_id)) {
			$article_image = get_image_by_contentid_type($fetch_values->image_id, 0);
		}
			 
			 break;
			 case "audio_manager"; 
			 $edit_url = "audio_video_manager/edit_data/5/".urlencode(base64_encode($article['content_id']));			
			 
				
				$fetch_values = $this->db->query('CALL get_video_audio_details("'.$article['content_id'].'", "5")')->row();
				
				if(isset($fetch_values->image_id)) {
					$article_image = get_image_by_contentid_type($fetch_values->image_id, 0);
				}
					 
			 break;
			 default: 
			 $edit_url = "";
		 }
			
			
			$subdata = array();
	
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($article['Title']).'">'.shortDescription(strip_tags($article['Title'])).'</p>';
			
			//$subdata[] = '<a href="#" title="'.strip_tags($article['Title']).'">'.shortDescription(strip_tags($article['Title'])).'</a>';
			$subdata[] = $article['Sectionname'];
			$ParentSection = get_sectionname_by_id(get_parentid_by_sectionid($article['Section_id']));
			$subdata[] = ($ParentSection == "") ? "-" : $ParentSection;
			
			
			if($Page_name != 'image_manager') {
				if($article['ImageAvailable'] == "Y") {
					if($article_image != '') {	
						$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.$article_image.'" /></div></td>';
					} else {
						$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a></td>';	
					}
				} else  {
				$subdata[] = '<td><a href="javascript:void()">-</a></td>';
				}
			}
			
			$subdata[] = ($article['AuthorName'] != '') ? ucfirst($article['AuthorName']) : '-';
			
			$subdata[] = $article['Username'];
			//$subdata[] = $article['previous_status'];
			$change_date_format = date('d-m-Y H:i:s', strtotime($article['Modifiedon']));
			$subdata[] = $change_date_format;
			
			switch($article["status"])
			{
			case("P"):
			if($Page_name == 'image_manager')
				$status_icon = '<a data-toggle="tooltip" title="Active" href="javascript:void()" id="img_change'.$article['content_id'].'" data-original-title="Active"><i id="status_img'.$article['content_id'].'"  class="fa fa-check"></i></a>';
			else
				$status_icon = '<a data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$article['content_id'].'" data-original-title="Active"><i id="status_img'.$article['content_id'].'"  class="fa fa-check"></i></a>';
				break;
			case("U"):	
			if($Page_name == 'image_manager')	
				$status_icon = '<a data-toggle="tooltip" title="InActive" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-times"></i></a>';	
			else
				$status_icon = '<a data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-times"></i></a>';
				break;
			case("D"):			
				$status_icon = '<a data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-floppy-o"></i></a>';
				break;
			case("A"):			
				$status_icon = '<a data-toggle="tooltip" title="Ready for Approval" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-share-square-o"></i></a>';
				break;	
			case("T"):			
				$status_icon = '<a data-toggle="tooltip" title="Trash" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-ban"></i></a>';
				break;			
			default;
				$status_icon = '';
			}
			
			$subdata[] = $status_icon;
			
			
			$subdata[] = $article['Hits'];
			
			$set_status ='<div class="buttonHolder">';
			if($article["status"] !="T")
            {
				if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 'Y'){
					$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a>'. '';
				}
				else
					$set_status .= '';
			}
			else 
			{
				$set_status .= '';
			}
				if($article["status"]=="P")
                {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 'Y') { 
					if($Page_name == 'image_manager')
					$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Inactive" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['Title']).'" id="image_status_change"><i id="status'.$article['content_id'].'"  style="color:#337ab7;" class="fa fa-times"></i></a> '.'';
					else
					$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Unpublish" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['Title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-pause"></i></a>'.'';
					}
				}
                elseif($article["status"]=="U")
                { 
				 	if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 'Y') {
					if($Page_name == 'image_manager')
					$set_status .= '<a data-toggle="tooltip" href="#" title="Active" class="button heart" data-original-title="" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['Title']).'" id="image_status_change"><i id="status'.$article['content_id'].'" style="color:#337ab7;" class="fa fa-check"></i></a>'.'';
					else
					$set_status .= '<a data-toggle="tooltip" href="#" title="Publish" class="button heart" data-original-title="" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['Title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-caret-right"></i></a>'.'';
					}
				}
				
				if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == 'Y')
				{
					if($article["status"]!="T" && $article["content_exists"]!='Y'){ 
					$set_status .= '<a data-toggle="tooltip" href="#" title="Trash" class="button heart" data-original-title="" content_id = '.$article['content_id'].' status = "T" change_status'.$article['content_id'].' = '.$article["status"].' name="'.strip_tags($article['Title']).'" id="change_trash"><i id="status'.$article['content_id'].'" class="fa fa-ban"></i></a>'.'';
					}
					
					if($article["status"]=="T"){ 
					$set_status .= '<a data-toggle="tooltip" href="#" title="Restore" class="button heart" data-original-title="" content_id = '.$article['content_id'].'  status = "R"  change_status'.$article['content_id'].' = '.$article["previous_status"].'   name="'.strip_tags($article['Title']).'" id="change_restore"><i id="status'.$article['content_id'].'" class="fa fa-refresh"></i></a>'.'';
					}
				}
				if($article["status"]=="P" ) {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 'Y') {
					if($Page_name != 'image_manager')
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="unpublish_checkbox[]" value="'.$article['content_id'].'" id="unpublish_checkbox_id" status ="'.$article["status"].'"   previous_status ="'.$article["previous_status"].'"   rel="'.$article['contentversion_id'].'"></span>';
					}
				}
				
				if($article["status"]=="U" || $article["status"]=="A") {
					if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 'Y') {
					if($Page_name != 'image_manager')
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"    title="Select"   name="publish_checkbox[]" value="'.$article['content_id'].'"  rel="'.$article['contentversion_id'].'"  status ="'.$article["status"].'"  previous_status ="'.$article["previous_status"].'"  id="publish_checkbox_id" ></span>';
					}
				}
				
				if($article["status"]=="D") {
					if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == 'Y') {
				//	if($Page_name != 'image_manager')
					//$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"   name="trash_checkbox[]" value="'.$article['content_id'].'"  rel="'.$article['contentversion_id'].'" status ="'.$article["status"].'"  previous_status ="'.$article["previous_status"].'"  id="publish_checkbox_id" ></span>';
					}
				}
				
				if($article["status"]=="T") {
					if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == 'Y') {
					if($Page_name != 'image_manager')
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"   name="restore_checkbox[]" value="'.$article['content_id'].'"  rel="'.$article['contentversion_id'].'" status ="'.$article["status"].'"  previous_status ="'.$article["previous_status"].'"  id="publish_checkbox_id" ></span>';
					}
				}
				
			if($set_status != '') {			  
			$set_status .= '</div>';
			$subdata[] = $set_status ;
			}
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
		echo json_encode($data);
		exit;
		
	}
}
class tags_model extends Common_model 
{
	public function get_tags($search_text) {
		
		/*
		$search = 'tag_name LIKE "%'.$search_text.'%"';
		$select_query 	= $this->db->query("CALL get_tags('','".$search."')");		
		*/
		
		$this->db->select("*");
		$this->db->from("tag_master");
		$this->db->where("tag_name",addslashes($search_text),"both");
		$this->db->where("status",1);
		$select_query = $this->db->get();
		
		$result			= $select_query->result();		
		return $result;
	}
	public function get_tags_by_id($tags) {
		
		$select_query 	= $this->db->query("CALL get_tags('".$tags."','')");		
		
		$result			= $select_query->result();		
		return $result;
	}
}


class Common_Resize_class extends Common_model 

{
	public function common_resize_all_images($ImageDetails) {
	
		try
		{	
					
			foreach($ImageDetails as $key => $ArrayImage) {
						

				$physical_name     	= $ArrayImage->physical_name;
				$image1_type 		= $ArrayImage->image1_type;
				$image2_type 		= $ArrayImage->image2_type;
				$image3_type 		= $ArrayImage->image3_type;
				$image4_type 		= $ArrayImage->image4_type;
				$image_name  		= $ArrayImage->image_name;
				$imageid	 		= $ArrayImage->imageid;
				$imagecontent_id 	= $ArrayImage->imagecontent_id;
				$contenttype		= $ArrayImage->contenttype;
				$caption   			= $ArrayImage->caption;
				$alt_tag   			= $ArrayImage->alt_tag;
				$save_status 		= $ArrayImage->save_status;
				
				switch($contenttype) {
						case 1:
							$TempSourceURL = article_temp_image_path;
						break;
						case 2:
							$TempSourceURL = imagelibrary_temp_image_path;
						break;
						case 3:
							$TempSourceURL =  gallery_temp_image_path;
						break;	
						case 4:
							$TempSourceURL =  video_temp_image_path;
						break;	
						case 5:
							$TempSourceURL =  audio_temp_image_path;
						break;	
						case 6:
							$TempSourceURL = resource_temp_image_path;
						break;
				}
				
				$Image600X390 	= str_replace(".","_600_390.", $image_name);
				$Image600X300 	= str_replace(".","_600_300.", $image_name);
				$Image100X65 	= str_replace(".","_100_65.", $image_name);
				$Image150X150 	= str_replace(".","_150_150.", $image_name);
				
				$image_binary_bool1 = false;
				$image_binary_bool2 = false;
				$image_binary_bool3 = false;
				$image_binary_bool4 = false;
				
				if (isset($image_name))
				{		
				
					$ImagePath = '';
		
					switch($contenttype) {
						case 1:
							$ImagePath = source_base_path.article_temp_image_path;
						break;
						case 2:
							$ImagePath = source_base_path.imagelibrary_temp_image_path;
						break;
						case 3:
							$ImagePath = source_base_path.gallery_temp_image_path;
						break;
						case 4:
							$ImagePath = source_base_path.video_temp_image_path;
						break;
						case 5:
							$ImagePath = source_base_path.audio_temp_image_path;
						break;
						case 6:
							$ImagePath = source_base_path.resource_temp_image_path;
						break;
					}
				
					$src 			= $ImagePath . $image_name;
					
					if(file_exists($src)) {
				
					$ImageDetails 				= getimagesize($src);
				
					$ImageExtension = explode("/",$ImageDetails['mime']);
					$extType 		= strtolower($ImageExtension[1]);
					
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
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image600X390) || $save_status == 0) {
					
						$dst_w 		= 600;
						$dst_h 		= 390;
						
						   $source_ratio = $src_w / $src_h; 
						   $destination_ratio = $dst_w / $dst_h; 
						
						$dst 		= $ImagePath. str_replace(".","_600_390.",$image_name);
						
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
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$result =  imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
					
						/*
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
					
						$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w,
 $src_h);
						*/
 
							if($result) {
								
							if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype					= $ImageDetails['mime'];
								
									$image_binary_bool1 = true;
									
								
									$image1_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
				
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image600X300) || $save_status == 0) {
						
					
						$dst_w 		= 600;
						$dst_h 		= 300;
						
						// Start the fit crop 
								
						$dst 		= $ImagePath. str_replace(".","_600_300.",$image_name);
								
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
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$result 	= imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
						
						// Start the fit crop 
						
						/*
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
					
						$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w,
 $src_h); */
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									$image_binary_bool2 = true;
									$image2_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image150X150) || $save_status == 0) {
					
						$dst_w 		= 150;
						$dst_h 		= 150;
						
						$dst 		= $ImagePath. str_replace(".","_150_150.",$image_name);
						
								
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
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$result =  imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
						
						
						/*
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
					
						$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w,
 $src_h);
						 
						 */
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									
									$image_binary_bool4 = true;
									$image3_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image100X65) || $save_status == 0) {
					
						$dst_w 		= 100;
						$dst_h 		= 65;
						
						$dst 		= $ImagePath. str_replace(".","_100_65.",$image_name);
						
								
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
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$result =  imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
						
						/*
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						
					
						$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w,
 $src_h);
						*/	
			
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									$image_binary_bool3 = true;
									$image4_type 		= 1;
									
									//imagedestroy($dst_img);	
								}
							}
						}
						
						if($image_binary_bool1 == true || $image_binary_bool2 == true || $image_binary_bool3 == true || $image_binary_bool4 == true) {
							
							if($imagecontent_id == '' && $imagecontent_id == 0)
								$imagecontent_id = "NULL";
						
							$caption = str_replace("'",'"',$caption);
							$alt 	 =  str_replace("'",'"',$alt_tag);
					
							
							$createdon =  $modifiedon =  date('Y-m-d H:i:s');
							
							$query = $this->db->query("CALL update_full_temp_images('".$imageid."','" . USERID . "'," . $imagecontent_id . ",'".$contenttype."','" . addslashes($caption) . "','" . addslashes($alt_tag) . "','".addslashes($physical_name)."','".addslashes($image_name)."',".$image1_type.",".$image2_type.",".$image3_type.",".$image4_type.",1,'" . $createdon . "','" . $modifiedon . "')");
							
							imagedestroy($src_img);
							
						} 
					
					}
					
					} else {
						$result_value['status'] 	= 'error';
						$result_value['msg'] 		= "Invalid Image";
						echo json_encode($result_value);
					}
					
				}
				else
				{
					$result_value['status'] 	= 'error';
					$result_value['msg'] 		= "Invalid Image";
					echo json_encode($result_value);
				}
				
			}
			return true;
		} catch(Exception $e){
			$result_value['status'] = 'error';
			$result_value['msg'] 	= 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	
	}
}
class Add_image_by_temp extends Common_model 

{
	public function update_caption_alt() 
	
	{
		extract($_POST);
		
		$TempDetails = $this->image_model->get_image($tempimageid);
		
		if($TempDetails['caption'] != $caption || $TempDetails['alt_tag'] != $alt ) 
			
		{
		
		$TempDetails['caption'] 		= str_replace("'",'"',$caption);
		$TempDetails['alt_tag'] 		= str_replace("'",'"',$alt);
		$TempDetails['imagecontent_id'] = "NULL";
			
		$this->db->query("CALL update_full_temp_images(".USERID.",".$TempDetails['imagecontent_id'].",'".$TempDetails['type']."','".stripslashes($TempDetails['caption'])."','".stripslashes($TempDetails['alt_tag'])."','".$TempDetails['image_size']."','".$TempDetails['image_binary_file']."','".$TempDetails['image_name']."','".$TempDetails['image_binary_file1']."','".$TempDetails['image_binary_file2']."','".$TempDetails['image_binary_file3']."','".$TempDetails['image_binary_file4']."','".$TempDetails['image1_type']."','".$TempDetails['image2_type']."','".$TempDetails['image3_type']."','".$TempDetails['image4_type']."','".$TempDetails['img_width']."','".$TempDetails['img_height']."','".$TempDetails['imagetype']."',1,'".date('Y-m-d H:i:s')."','".$TempDetails['imageid']."')");
		
		}
		
		$data['status'] = 'success';
		
		echo json_encode($data);
		
	}

}
?>