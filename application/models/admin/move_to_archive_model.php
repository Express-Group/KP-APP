<?php 
/**
 * Move To Archive Model Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

class  Move_to_archive_model extends CI_Model
{	

	public function __construct()

	{
		parent::__construct();
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	
	public function content_move_to_archive($content_type ,$start_date, $end_date ) {
		
		$StatusArray = array();
		
		$start_date 	= 	date('Y-m-d', strtotime($start_date));
		$end_date 		= 	date('Y-m-d', strtotime($end_date));	
		
		$Query = $this->db->query("SET @content_ids='';CALL get_content_publish_date(".$content_type.",'".$start_date."','".$end_date."',@content_ids);");
		
		//echo $this->db->last_query();
		
		$ContentCollections = $this->db->query("SELECT @content_ids")->row_array();	
		
		/*
		echo "<pre>";
		print_r($ContentCollections);
		exit;
		*/
		
		if(isset($ContentCollections['@content_ids']) && trim($ContentCollections['@content_ids']) != '') {
			
			$ArrayContent = array_filter(explode(",",$ContentCollections['@content_ids']));
			$NotExistContent = array();
			
			foreach($ArrayContent as $Contentid) {
				$InstanceCount = $this->live_db->query("SET @count_content=0;CALL check_widgetinstancecontentlive(".$Contentid.",".$content_type.",@count_content)");
				
				$ContentCount = $this->live_db->query("SELECT @count_content")->row_array();	
				
				$InstanceCount =  $ContentCount['@count_content'];
				
				if($InstanceCount == 0) 
					$NotExistContent[] = $Contentid;
			}
			
					/*
					echo "<pre>";
					print_r($NotExistContent);
					exit;
					*/
		
			
			if(!empty($NotExistContent)) {
		
				$ContentCollections = implode(",",$NotExistContent);
			
				$FlatArticleMaster = $this->get_content_with_check($ContentCollections,$content_type,"");
				
				/*
					echo "<pre>";
					print_r($FlatArticleMaster);
					exit;	
				*/
				
				if(isset($FlatArticleMaster)) {
					
					foreach($FlatArticleMaster as $ContentDetails) {
						
					$this->archive_db->trans_begin();
					
					$Content_id 		= $ContentDetails['content_id'];
						
					switch($content_type) {
					
						case 1: 
							$Related_Article 		= $this->get_related_article_by_contentid($Content_id);
							$SectionMapping 		= $this->get_section_mapping($Content_id,$content_type);
							$CMS_relateddetails 	= $this->get_related_article_by_articleid($Content_id)->result_array();

							$CMS_contentdetails = $this->get_article_details($Content_id)->row_array();
							
							$this->move_to_archive_article($ContentDetails,$CMS_contentdetails,$Related_Article,$CMS_relateddetails,$SectionMapping);
						break;
						case 3: 
							$Related_Images 	= $this->get_gallery_images_by_id($Content_id);
							$SectionMapping 	= $this->get_section_mapping($Content_id,$content_type);
							
							$CMS_relatedimages 	= $this->get_gallery_images($Content_id)->result_array();
							
							$CMS_contentdetails = $this->get_gallery_details($Content_id)->row_array();
							
							$this->move_to_archive_gallery($ContentDetails,$CMS_contentdetails, $Related_Images, $CMS_relatedimages,$SectionMapping);
						break;
						case 4: 
							$SectionMapping 	= $this->get_section_mapping($Content_id,$content_type);
							
							$CMS_contentdetails = $this->get_audio_video_details($Content_id,$content_type)->row_array();
							
							$this->move_to_archive_video($ContentDetails, $CMS_contentdetails, $SectionMapping);
						break;
						case 5: 
							$SectionMapping 	= $this->get_section_mapping($Content_id,$content_type);
							
							$CMS_contentdetails = $this->get_audio_video_details($Content_id,$content_type)->row_array();
							
							$this->move_to_archive_audio($ContentDetails, $CMS_contentdetails, $SectionMapping);
						break;
						case 6: 
							
							$CMS_contentdetails = $this->get_resources_details($Content_id)->row_array();
						
							$this->move_to_archive_resources($ContentDetails,$CMS_contentdetails);
						break;
					}
					
					if($this->archive_db->trans_status() == FALSE) {
						$this->archive_db->trans_rollback();
						 $StatusArray[$Content_id] = array(
								"status" => "Failure",
								"Message" => "Don't move the contents to archive" 
								);
					} else {
						
						$DeleteStatus = $this->delete_cms_live_contents($content_type, $Content_id);
						
						if($DeleteStatus) {
							$this->archive_db->trans_commit();
							$StatusArray[$Content_id] =  array(
								"status" => "Success",
								"Message" => "CMS & LIVE content moved to archive Successfully" 
								);
						} else {
							$this->archive_db->trans_rollback();
							 $StatusArray[$Content_id] =  array(
								"status" => "Failure",
								"Message" => "Don't removed & moved to archive,Please check log file" 
								);
						}
					}
				}
				} else {
						$StatusArray[0] = array(
						"status" => "Failure",
						"Message" => "CMS exists But don't have this (".$ContentCollections.") in LIVE"
						);
				}
				
			} else {
				 $StatusArray[0] = array(
				"status" => "Failure",
				"Message" => "Don't have contents, between this dates" 
				);
			}
			
		} else {
			 $StatusArray[0] = array(
			"status" => "Failure",
			"Message" => "Don't have contents, between this dates" 
			);
			
		}
		
		return $StatusArray;
	}
	
	public function move_to_archive_article($article_details,$CMS_articledetails, $Related_Article, $CMS_relatedarticle, $SectionMapping) {
		
		$Year = date("Y",strtotime($article_details["publish_start_date"]));
		
		$ContentTable_Name 			=  "article_".$Year;
		$ShortTable_Name 			=  "short_article_details_".$Year;
		$RelatedTable_Name 			=  "relatedcontent_".$Year;
		$SectionMappingTable_Name 	= "article_section_mapping_".$Year;
		
		$article_details['tag_ids'] 			= $CMS_articledetails['Tags'];
		$article_details['agency_id'] 			= $CMS_articledetails['Agency_ID'];
		$article_details['author_id'] 			= $CMS_articledetails['Author_ID'];
		$article_details['country_id'] 			= $CMS_articledetails['Country_ID'];
		$article_details['state_id'] 			= $CMS_articledetails['State_ID'];
		$article_details['city_id'] 			= $CMS_articledetails['City_ID'];
		$article_details['homepageimageid'] 	= $CMS_articledetails['homepageimageid'];
		$article_details['sectionpageimageid'] 	= $CMS_articledetails['Sectionpageimageid'];
		$article_details['articlepageimageid'] 	= $CMS_articledetails['articlepageimageid']; 
		
		$article_details['created_by'] 			= get_userdetails_by_id($CMS_articledetails['Createdby']); 
		$article_details['created_on'] 			= $CMS_articledetails['Createdon']; 
		$article_details['modified_by'] 		= get_userdetails_by_id($CMS_articledetails['Modifiedby']); 
		$article_details['modified_on'] 		= $CMS_articledetails['Modifiedon']; 
		
		$this->archive_db->select("content_id");
		$this->archive_db->from($ContentTable_Name);
		$this->archive_db->where("content_id",$article_details['content_id']);
		$Get = $this->archive_db->get();
		$Result = $Get->row_array();
			
		$short_details = array(
			"content_id" 	=> $article_details['content_id'],
			"title" 		=> addslashes(strip_tags($article_details['title'])),
			"tags" 			=> $article_details['tags'],
			"summary" 		=> addslashes(strip_tags($article_details['summary_html'])),
			"bodytext" 		=> addslashes(strip_tags($article_details['article_page_content_html'])),
			"section_id" 	=> $article_details['section_id']
		);
		
		if(isset($Result['content_id'])) {
			
			$this->archive_db->where("content_id",$article_details['content_id']);
			$this->archive_db->update($ContentTable_Name,$article_details);
		
			$this->archive_db->where("content_id",$short_details['content_id']);
			$this->archive_db->update($ShortTable_Name,$short_details);
			
		} else {
			
			$this->archive_db->insert($ContentTable_Name,$article_details);
			$this->archive_db->insert($ShortTable_Name,$short_details);
			
		}
		
		$this->archive_db->where("content_id",$article_details['content_id']);
		$this->archive_db->delete($SectionMappingTable_Name);
		
		if($SectionMapping) {
			foreach($SectionMapping as $Mapping) {
				$this->archive_db->insert($SectionMappingTable_Name,$Mapping);
			}
		}
		
		$this->archive_db->where("content_id",$article_details['content_id']);
		$this->archive_db->delete($RelatedTable_Name);	
	
		if($Related_Article) {
			foreach($Related_Article as $Key=>$Related) {
				$Related['related_content_id'] = $CMS_relatedarticle[$Key]['Related_content_id'];
				$this->archive_db->insert($RelatedTable_Name,$Related);
			}
		}
		
	}
	
	public function move_to_archive_gallery($gallery_details,$CMS_gallerydetails, $Related_Images,$CMS_galleryimages, $SectionMapping) {
		
		$Year = date("Y",strtotime($gallery_details["publish_start_date"]));
	
		$ContentTable_Name =  "gallery_".$Year;
		$ShortTable_Name =  "short_gallery_details_".$Year;
		$RelatedTable_Name = "gallery_related_images_".$Year;
		$SectionMappingTable_Name = "gallery_section_mapping_".$Year;
		
		$gallery_details['tag_ids'] 			= $CMS_gallerydetails['Tags'];
		$gallery_details['agency_id'] 			= $CMS_gallerydetails['Agency_ID'];
		$gallery_details['author_id'] 			= $CMS_gallerydetails['Author_ID'];
		$gallery_details['country_id'] 			= $CMS_gallerydetails['Country_ID'];
		$gallery_details['state_id'] 			= $CMS_gallerydetails['State_ID'];
		$gallery_details['city_id'] 			= $CMS_gallerydetails['City_ID'];
		
		$gallery_details['created_by'] 			= get_userdetails_by_id($CMS_gallerydetails['Createdby']); 
		$gallery_details['created_on'] 			= $CMS_gallerydetails['Createdon']; 
		$gallery_details['modified_by'] 		= get_userdetails_by_id($CMS_gallerydetails['Modifiedby']); 
		$gallery_details['modified_on'] 		= $CMS_gallerydetails['Modifiedon']; 
	
		$this->archive_db->select("content_id");
		$this->archive_db->from($ContentTable_Name);
		$this->archive_db->where("content_id",$gallery_details['content_id']);
		$Get = $this->archive_db->get();
		$Result = $Get->row_array();
			
		$short_details = array(
			"content_id" 	=> $gallery_details['content_id'],
			"title" 		=> addslashes(strip_tags($gallery_details['title'])),
			"tags" 			=> $gallery_details['tags'],
			"summary" 		=> addslashes(strip_tags($gallery_details['summary_html'])),
			"bodytext" 		=> '',
			"section_id" 	=> $gallery_details['section_id']
		);
		
		if(isset($Result['content_id'])) {
			
			$this->archive_db->where("content_id",$gallery_details['content_id']);
			$this->archive_db->update($ContentTable_Name,$gallery_details);
		
			$this->archive_db->where("content_id",$short_details['content_id']);
			$this->archive_db->update($ShortTable_Name,$short_details);
			
		} else {
			
			$this->archive_db->insert($ContentTable_Name,$gallery_details);
			$this->archive_db->insert($ShortTable_Name,$short_details);
			
		}
		
		$this->archive_db->where("content_id",$gallery_details['content_id']);
		$this->archive_db->delete($SectionMappingTable_Name);
		
		if($SectionMapping) {
			foreach($SectionMapping as $Mapping) {
				$this->archive_db->insert($SectionMappingTable_Name,$Mapping);
			}
		}
		
		$this->archive_db->where("content_id",$gallery_details['content_id']);
		$this->archive_db->delete($RelatedTable_Name);	
	
		if($Related_Images) {
			foreach($Related_Images as $Key=>$Related) {
				$Related['image_id'] = $CMS_galleryimages[$Key]['ImageID'];
				$this->archive_db->insert($RelatedTable_Name,$Related);
			}
		}
		
	
	}
	
	public function move_to_archive_video($video_details,$CMS_videodetails, $SectionMapping) {
		
		$Year = date("Y",strtotime($video_details["publish_start_date"]));
	
		$ContentTable_Name =  "video_".$Year;
		$ShortTable_Name =  "short_video_details_".$Year;
		$SectionMappingTable_Name = "video_section_mapping_".$Year;
		
		$video_details['tag_ids'] 			= $CMS_videodetails['Tags'];
		$video_details['agency_id'] 		= $CMS_videodetails['Agency_ID'];
		$video_details['author_id'] 		= $CMS_videodetails['Author_ID'];
		$video_details['country_id'] 		= $CMS_videodetails['Country_ID'];
		$video_details['state_id'] 			= $CMS_videodetails['State_ID'];
		$video_details['city_id'] 			= $CMS_videodetails['City_ID'];
		$video_details['image_id'] 			= $CMS_videodetails['image_id'];
	
		$video_details['created_by'] 		= get_userdetails_by_id($CMS_videodetails['Createdby']); 
		$video_details['created_on'] 		= $CMS_videodetails['Createdon']; 
		$video_details['modified_by'] 		= get_userdetails_by_id($CMS_videodetails['Modifiedby']); 
		$video_details['modified_on'] 		= $CMS_videodetails['Modifiedon']; 
		
		$this->archive_db->select("content_id");
		$this->archive_db->from($ContentTable_Name);
		$this->archive_db->where("content_id",$video_details['content_id']);
		$Get = $this->archive_db->get();
		$Result = $Get->row_array();
			
		$short_details = array(
			"content_id" 	=> $video_details['content_id'],
			"title" 		=> addslashes(strip_tags($video_details['title'])),
			"tags" 			=> $video_details['tags'],
			"summary" 		=> addslashes(strip_tags($video_details['summary_html'])),
			"bodytext" 		=> '',
			"section_id" 	=> $video_details['section_id']
		);
		
		if(isset($Result['content_id'])) {
			
			$this->archive_db->where("content_id",$video_details['content_id']);
			$this->archive_db->update($ContentTable_Name,$video_details);
		
			$this->archive_db->where("content_id",$short_details['content_id']);
			$this->archive_db->update($ShortTable_Name,$short_details);
			
		} else {
			
			$this->archive_db->insert($ContentTable_Name,$video_details);
			$this->archive_db->insert($ShortTable_Name,$short_details);
			
		}
		
		$this->archive_db->where("content_id",$video_details['content_id']);
		$this->archive_db->delete($SectionMappingTable_Name);
		
		if($SectionMapping) {
			foreach($SectionMapping as $Mapping) {
				$this->archive_db->insert($SectionMappingTable_Name,$Mapping);
			}
		}
	
	}
	
	public function move_to_archive_audio($audio_details,$CMS_audiodetails,$SectionMapping) {
		
		$Year = date("Y",strtotime($audio_details["publish_start_date"]));
	
		$ContentTable_Name 			= "audio_".$Year;
		$ShortTable_Name 			= "short_audio_details_".$Year;
		$SectionMappingTable_Name	= "audio_section_mapping_".$Year;
		
		$audio_details['tag_ids'] 			= $CMS_audiodetails['Tags'];
		$audio_details['agency_id'] 		= $CMS_audiodetails['Agency_ID'];
		$audio_details['author_id'] 		= $CMS_audiodetails['Author_ID'];
		$audio_details['country_id'] 		= $CMS_audiodetails['Country_ID'];
		$audio_details['state_id'] 			= $CMS_audiodetails['State_ID'];
		$audio_details['city_id'] 			= $CMS_audiodetails['City_ID'];
		$audio_details['image_id'] 			= $CMS_audiodetails['image_id'];
		
		$audio_details['created_by'] 		= get_userdetails_by_id($CMS_audiodetails['Createdby']); 
		$audio_details['created_on'] 		= $CMS_audiodetails['Createdon']; 
		$audio_details['modified_by'] 		= get_userdetails_by_id($CMS_audiodetails['Modifiedby']); 
		$audio_details['modified_on'] 		= $CMS_audiodetails['Modifiedon']; 
		
		$this->archive_db->select("content_id");
		$this->archive_db->from($ContentTable_Name);
		$this->archive_db->where("content_id",$audio_details['content_id']);
		$Get = $this->archive_db->get();
		$Result = $Get->row_array();
			
		$short_details = array(
			"content_id" 	=> $audio_details['content_id'],
			"title" 		=> addslashes(strip_tags($audio_details['title'])),
			"tags" 			=> $audio_details['tags'],
			"summary" 		=> addslashes(strip_tags($audio_details['summary_html'])),
			"bodytext" 		=> '',
			"section_id" 	=> $audio_details['section_id']
		);
		
		if(isset($Result['content_id'])) {
			
			$this->archive_db->where("content_id",$audio_details['content_id']);
			$this->archive_db->update($ContentTable_Name,$audio_details);
		
			$this->archive_db->where("content_id",$short_details['content_id']);
			$this->archive_db->update($ShortTable_Name,$short_details);
			
		} else {
			
			$this->archive_db->insert($ContentTable_Name,$audio_details);
			$this->archive_db->insert($ShortTable_Name,$short_details);
			
		}
		
		$this->archive_db->where("content_id",$audio_details['content_id']);
		$this->archive_db->delete($SectionMappingTable_Name);
		
		if($SectionMapping) {
			foreach($SectionMapping as $Mapping) {
				$this->archive_db->insert($SectionMappingTable_Name,$Mapping);
			}
		}
	
	}
	
	public function move_to_archive_resources($resource_details, $CMS_resourcedetails) {
		
		$Year = date("Y",strtotime($resource_details["publish_start_date"]));
	
		$ContentTable_Name =  "resources_".$Year;
		//$ShortTable_Name =  "short_resource_details_".$Year;
		
		$resource_details['image_id'] = $CMS_resourcedetails['image_id'];

		$resource_details['created_by'] 		= $CMS_resourcedetails['CreatedName']; 
		$resource_details['created_on'] 		= $CMS_resourcedetails['Createdon']; 
		$resource_details['modified_by'] 		= $CMS_resourcedetails['ModifiedName']; 
		$resource_details['modified_on'] 		= $CMS_resourcedetails['Modifiedon']; 

		$this->archive_db->select("content_id");
		$this->archive_db->from($ContentTable_Name);
		$this->archive_db->where("content_id",$resource_details['content_id']);
		$Get = $this->archive_db->get();
		$Result = $Get->row_array();
		
/*		
		$short_details = array(
			"content_id" 	=> $resource_details['content_id'],
			"title" 		=> addslashes(strip_tags($resource_details['title'])),
			"tags" 			=> $resource_details['tags'],
			"summary" 		=> addslashes(strip_tags($resource_details['summary_html'])),
			"bodytext" 		=> '',
			"section_id" 	=> $resource_details['section_id']
		);
		
		*/
		
		if(isset($Result['content_id'])) {
			
			$this->archive_db->where("content_id",$resource_details['content_id']);
			$this->archive_db->update($ContentTable_Name,$resource_details);
		
			//$this->archive_db->where("content_id",$short_details['content_id']);
			//$this->archive_db->update($ShortTable_Name,$short_details);
			
		} else {
			
			$this->archive_db->insert($ContentTable_Name,$resource_details);
			//$this->archive_db->insert($ShortTable_Name,$short_details);
			
		}
	
	}
	
	public function delete_cms_live_contents($content_type, $content_id) {
		
			$this->db->trans_begin();
			$this->live_db->trans_begin();
		
			switch($content_type) {
				
			case 1:	
			
				$this->db->where("content_id",$content_id);
				$this->db->delete("relatedcontent");	
				
				$this->db->where("content_id",$content_id);
				$this->db->delete("articlesectionmapping");	
				
				$this->db->where("content_id",$content_id);
				$this->db->delete("articlerelateddata");	
				
				$this->db->where("content_id",$content_id);
				$this->db->delete("articlemaster");	
				
				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("relatedcontent");	

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("article_section_mapping");	

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("short_article_details");				
				
				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("article");	
			
			break;
			case 3:
			
				$this->db->where("content_id",$content_id);
				$this->db->delete("gallerysectionmapping");		
				
				$this->db->where("content_id",$content_id);
				$this->db->delete("galleryrelatedimages");		
				
				$this->db->where("content_id",$content_id);
				$this->db->delete("gallerymaster");		

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("gallery_section_mapping");	

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("short_gallery_details");	

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("gallery_related_images");		
				
				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("gallery");	
			
			break;
			
			case 4:
			
				$this->db->where("content_id",$content_id);
				$this->db->delete("videosectionmapping");		
				
				$this->db->where("content_id",$content_id);
				$this->db->delete("videomaster");		

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("video_section_mapping");	

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("short_video_details");	
				
				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("video");	
			
			break;
			
			case 5:
			
				$this->db->where("content_id",$content_id);
				$this->db->delete("audiosectionmapping");		
				
				$this->db->where("content_id",$content_id);
				$this->db->delete("audiomaster");		

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("audio_section_mapping");	

				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("short_audio_details");	
				
				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("audio");	
			
			break;
			
			case 6:

				$this->db->where("content_id",$content_id);
				$this->db->delete("resourcemaster");		
				
				$this->live_db->where("content_id",$content_id);
				$this->live_db->delete("resources");	
			
			break;
			
			
		}
		
		
			IF($this->db->trans_status() == FALSE || $this->live_db->trans_status() ==FALSE) {
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				return TRUE;
			}
			
		
		
	}
	
	
	public function get_content_with_check($Contentids, $ContentType,$OrderCondition) {
		
		$Result = $this->live_db->query("CALL get_content_with_check('".$Contentids."',".$ContentType.",'".$OrderCondition."') ")->result_array();
		
		return $Result;
	}
	
	public function get_section_mapping($Contentid,$ContentType) {
		
		$Result = $this->live_db->query("CALL get_section_mapping('".$Contentid."',".$ContentType.") ")->result_array();
		
		return $Result;
	}
	
	public function get_related_article_by_contentid ($Contentid) {
		
		$Result = $this->live_db->query("CALL get_related_article_by_contentid(".$Contentid.") ")->result_array();
		
		return $Result;
	
	}
	
	public function get_gallery_images_by_id($Contentid) {
		
		$Result = $this->live_db->query("CALL get_gallery_images_by_id(".$Contentid.") ")->result_array();
		
		return $Result;
	
	}
	
		/*
	*
	* Get the article details using article id
	*
	* @access public
	* @param article id
	* @return article details object value
	*
	*/
	
	public function get_article_details($article_id)

	{
		$article_manager = $this->db->query('CALL get_article_by_id(' . $article_id . ')');
		return $article_manager;
	}
	
		/*
	*
	* Get the article related content data
	*
	* @access Public
	* @param  article_id from url segment
	* @return get related article table records based on article_id ;
	*/
	
	public function get_related_article_by_articleid($article_id)

	{
		$article_details = $this->db->query('CALL get_related_article_by_id (' . $article_id . ')');
		return $article_details;
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
	* Get the get_audio_video_details using content id
	*
	* @access public
	* @param content id
	* @return article details object value
	*
	*/
	
	public function get_audio_video_details($content_id,$content_type)

	{
		$audio_video_manager = $this->db->query('CALL get_audio_video_by_id(' . $content_id . ','.$content_type.')');
		return $audio_video_manager;
	}
	
	public function get_resources_details($resources_id) {
		$resources_manager = $this->db->query('CALL get_resources_by_id(' . $resources_id . ')');
		return $resources_manager;
	}
	
}
?>