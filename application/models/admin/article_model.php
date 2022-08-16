<?php
/**
 * Article Model Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */


header('Content-Type: text/html; charset=utf-8');
class Article_model extends CI_Model

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
	* Insert the article details in article master and related data
	*
	* @access public
	* @param Post values from Add Form
	* @return TRUE
	*
	*/
	
	public function insert_article()

	{
		extract($_POST);	
	
		$article_details 	= $this->get_additional_article_details();
		
		if(isset($txtTags) != '')
			$txtTags 		= PrepareTagInputValue($txtTags);
		else
			$txtTags = '';
		
		$null_value 		= "NULL";
	
			$this->db->trans_begin();
			$this->live_db->trans_begin();
			
			$this->db->query('CALL add_articlemaster(NULL,"'.trim(addslashes($article_details['UrlTitle'])).'","'.trim(addslashes($txtArticleHeadLine)).'","'.trim(addslashes($txtUrlStructure)).'","'.trim(addslashes($txtSummary)).'","'.trim(addslashes($txtBodyText)).'","'.trim($article_details['PublishStartDate']).'","'.trim($article_details['PublishEndDate']).'",'.trim($article_details['scheduled_article']).',"'.trim($txtTags).'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$article_details['cbNoIndex'].'","'.$article_details['cbNoFollows'].'","'.$txtCanonicalUrl.'","'.$article_details['cbAllowComments'].'","'.$article_details['cbAllowPagination'].'","'.$article_details['cbSectionPromotion'].'","'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'.$article_details['modifiedon'] .'","'.$article_details['draft_on'].'",@insert_id)');
	
			$result 	= $this->db->query("SELECT @insert_id")->result_array();
			$article_id = $result[0]['@insert_id'];

			if($article_id != '' && $article_id != 'NULL') {
				
				$article_details['url'] = $article_details['url']."-".$article_id.".html";
				
				$this->db->query('CALL update_url_structure('.$article_id.',"'.addslashes($article_details['url']).'",1)');
			
				$this->db->query('CALL add_articlerelateddate('.$article_id.','.$ddMainSection.','.$article_details['ddAgency'].','.$article_details['ddByLine'].','.$article_details['ddCountry'].','.$article_details['ddState'].','.$article_details['ddCity'].','.$article_details['imgHomeImageId'] .','.$article_details['imgSectionImageId'] .','.$article_details['imgArticleImageId'] .')');
				
				if ($txtStatus == 'P') {
				$article_details['LiveArticleDetails']['url'] 			= $article_details['url'];
				$article_details['LiveArticleDetails']['content_id'] 	= $article_id;
				$this->insert_live_article($article_details['LiveArticleDetails']);
				} 
				
				$this->insert_article_mapping($article_id);
				$this->insert_related_article($article_id);	
			
			}
	
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
	
	/*
	*
	* Insert the live article details in article and short article details
	*
	* @access public
	* @param Array of article details
	* @return TRUE
	*
	*/
	
	public function insert_live_article($article_details) {
	
		$this->live_db->query('CALL add_article ('.$article_details["content_id"].','.$article_details["ecenic_id"].','.$article_details["section_id"].',"'.addslashes($article_details["section_name"]).'",'.$article_details["parent_section_id"].',"'.addslashes($article_details["parent_section_name"]).'",'.$article_details["grant_section_id"].',"'.addslashes($article_details["grant_parent_section_name"]).'","'.$article_details["linked_to_columnist"].'","'.$article_details["publish_start_date"].'","'.@$article_details["publish_end_date"].'","'.$article_details["last_updated_on"].'","'.addslashes($article_details["title"]).'","'.addslashes($article_details["url"]).'","'.addslashes($article_details["summary_html"]).'","'.addslashes($article_details['article_page_content_html']).'","'.addslashes($article_details["home_page_image_path"]).'","'.addslashes($article_details["home_page_image_title"]).'","'.addslashes($article_details["home_page_image_alt"]).'","'.addslashes($article_details["section_page_image_path"]).'","'.addslashes($article_details["section_page_image_title"]).'","'.addslashes($article_details["section_page_image_alt"]).'","'.addslashes($article_details["article_page_image_path"]).'","'.addslashes($article_details["article_page_image_title"]).'","'.addslashes($article_details["article_page_image_alt"]).'","'.addslashes($article_details["column_name"]).'",'.$article_details["hits"].',"'.addslashes($article_details["tags"]).'",'.$article_details["allow_comments"].','.$article_details["allow_pagination"].',"'.addslashes($article_details["agency_name"]).'","'.addslashes($article_details["author_name"]).'","'.addslashes($article_details["author_image_path"]).'","'.addslashes($article_details["author_image_title"]).'","'.addslashes($article_details["author_image_alt"]).'","'.trim(addslashes($article_details["country_name"])).'","'.trim(addslashes($article_details["state_name"])).'","'.trim(addslashes($article_details["city_name"])).'",'.$article_details["no_indexed"].','.$article_details["no_follow"].',"'.addslashes($article_details["canonical_url"]).'","'.trim(addslashes($article_details["meta_Title"])).'","'.trim(addslashes($article_details["meta_description"])).'",'.$article_details["section_promotion"].',"'.$article_details["status"].'")');
	
		$this->live_db->query('CALL add_short_content_details ('.$article_details["content_id"].',"'.trim(addslashes(strip_tags($article_details["title"]))).'","'.trim(addslashes($article_details["tags"])).'","'.trim(addslashes($article_details["summary_html"])).'","'.trim(addslashes(strip_tags($article_details['article_page_content_html']))).'",'.$article_details["section_id"].',1)');
		
		return TRUE;
		
	}
	
	/*
	*
	* Update the article details in article master and related data
	*
	* @access public
	* @param primary article id
	* @return redirect to article manager page
	*
	*/
		public function update_article($article_id)

		{
		
			/*
			echo "<pre>";
			print_r($_POST);
			exit;
			*/
			
			extract($_POST);
	
			$article_details 			= $this->get_additional_article_details();
			$txtTags 					= PrepareTagInputValue(@$txtTags);
	
			/*
			echo "<pre>";
			print_r($article_details);
			exit; 
			*/
			
			$article_details['content_id'] = $article_id;
		
				$this->db->trans_begin();
				$this->live_db->trans_begin();
				
				$article_details['url'] = $article_details['url']."-".$article_id.".html";
				
				$this->db->query('CALL update_articlemaster("'.$article_id.'","'.trim(addslashes($article_details['UrlTitle'])).'","'.trim(addslashes($txtArticleHeadLine)).'","'.trim(addslashes($article_details['url'])) .'","'.trim(addslashes($txtSummary)).'","'.trim(addslashes($txtBodyText)).'","'.$article_details['PublishStartDate'].'","'.$article_details['PublishEndDate'].'",'.$article_details['scheduled_article'].',"'.$txtTags.'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$article_details['cbNoIndex'].'","'.$article_details['cbNoFollows'].'","'.addslashes($txtCanonicalUrl).'","'.$article_details['cbAllowComments'].'","'.$article_details['cbAllowPagination'].'",'.$article_details['cbSectionPromotion'].',"'.$txtStatus.'","'.USERID.'","'. $article_details['modifiedon'].'")');	

	
				$this->db->query('CALL update_articlerelateddate('.$article_id.','.$ddMainSection.','.$article_details['ddAgency'].','.$article_details['ddByLine'].','.$article_details['ddCountry'].','.$article_details['ddState'].','.$article_details['ddCity'].','.$article_details['imgHomeImageId'] .','.$article_details['imgSectionImageId'] .','.$article_details['imgArticleImageId'] .')');
				
				if($txtStatus == 'P') {
					$article_details['LiveArticleDetails']['url'] 		= $article_details['url'];
					$article_details['LiveArticleDetails']['content_id'] = $article_id;
					$Livecount = $this->live_content_model->check_livecontents($article_id, 1);
					
					if($Livecount <= 0) 
						$this->insert_live_article($article_details['LiveArticleDetails']);
					else
						$this->update_live_article($article_details['LiveArticleDetails']);	
				}
				if($this->delete_content_mapping($article_id)) 
				$this->insert_article_mapping($article_id);
			
				if($this->delete_related_article($article_id)) {
				$this->insert_related_article($article_id);
				}
				
				if($txtStatus == 'U')
				$this->delete_livecontents($article_id,1);
				
				if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				$this->session->set_flashdata('Error', "Article not updated Successfully");
				redirect(folder_name.'/article_manager');
				} else {
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				
				switch ($txtStatus)
				{
				case 'D':
				$this->session->set_flashdata('success', "Article Drafted Successfully");
				break;
				case 'P':
				$this->session->set_flashdata('success', "Article Published Successfully");
				break;
				case 'U':
				$this->session->set_flashdata('success', "Article Unpublished Successfully");
				break;
				
				redirect(folder_name.'/article_manager');
				
				}
		}
	}
	
	/*
	*
	* Update the article details in article master and related data
	*
	* @access public
	* @param primary article id
	* @return redirect to article manager page
	*
	*/
		public function update_archive_article($year,$article_id)

		{
		
			/*
			echo "<pre>";
			print_r($_POST);
			exit;
			*/
			
			extract($_POST);
	
			$article_details 			= $this->get_additional_article_details();
			$txtTags 					= PrepareTagInputValue(@$txtTags);
	
			/*
			echo "<pre>";
			print_r($article_details);
			print_r($txtTags);
			exit; 
			*/
			
			$article_details['content_id'] = $article_id;
		
				$this->archive_db->trans_begin();
				
				$update_archive_details = $article_details['LiveArticleDetails'];
				
				$update_archive_details['url'] = $article_details['url']."-".$article_id.".html";
				
				unset($update_archive_details['ecenic_id']);
				
				$update_archive_details['tag_ids']  			= $txtTags;
				$update_archive_details['agency_id']  			= $article_details['ddAgency'];
				$update_archive_details['author_id']  			= $article_details['ddByLine'];
				$update_archive_details['country_id']  			= $article_details['ddCountry'];
				$update_archive_details['state_id']  			= $article_details['ddState'];
				$update_archive_details['city_id']  			= $article_details['ddCity'];
				$update_archive_details['homepageimageid']  	= $article_details['imgHomeImageId'];
				$update_archive_details['sectionpageimageid']  	= $article_details['imgSectionImageId'];
				$update_archive_details['articlepageimageid']  	= $article_details['imgArticleImageId'];
				
				
				
				$update_archive_details['modified_by'] = get_userdetails_by_id(USERID);
				$update_archive_details['modified_on'] = $update_archive_details['last_updated_on'];
				
				/*
				echo "<pre>";
				print_r($update_archive_details);
				exit;
				*/
				
				$this->archive_db->where("content_id",$article_id);
				$this->archive_db->update("article_".$year,$update_archive_details);
				
				
				$this->archive_db->where("content_id",$article_id);
				$this->archive_db->delete("article_section_mapping_".$year);
				
				$this->archive_db->where("content_id",$article_id);
				$this->archive_db->delete("relatedcontent_".$year);
				
				$insert_array 	= array();
				$insert_array['content_id'] = $article_id;
				$insert_array['section_id'] = $ddMainSection;
			
				$this->archive_db->insert("article_section_mapping_".$year,$insert_array);
			
				if (isset($cbSectionMapping))
				{
					$cbSectionMapping = array_diff($cbSectionMapping, array($ddMainSection));
				
					foreach($cbSectionMapping as $mapping)
					{
						$insert_array 	= array();
						$insert_array['content_id'] = $article_id;
						$insert_array['section_id'] = $mapping;
						
						$this->archive_db->insert("article_section_mapping_".$year,$insert_array);
						
					}
				}
				
				if ($hide_external_link != '' && $hide_external_link != '[]') {
					$external_array = json_decode($hide_external_link);
				
					foreach($external_array as $key=>$external) {
						$null_value 	= "NULL";
						$display_order 	= ($key+1);
						
						if ($external->type == 'E') {
							$update_related_content['content_id'] 			= $article_id;
							$update_related_content['contenttype'] 			= 0;
							$update_related_content['related_content_id'] 	= $null_value;
							$update_related_content['related_articletitle'] = addslashes($external->external_title);
							$update_related_content['related_articleurl'] 	= addslashes($external->external_url);
							$update_related_content['display_order']		= $display_order;
						} else {
							$update_related_content['content_id'] 			= $article_id;
							$update_related_content['contenttype'] 			=  $external->content_type;
							$update_related_content['related_content_id'] 	= $external->content_id;
							$update_related_content['related_articletitle'] = addslashes($external->long_title);
							$update_related_content['related_articleurl'] 	= addslashes($external->url);
							$update_related_content['display_order'] 		= $display_order;
						}
						
						if(!empty($update_related_content)) {
						$this->archive_db->insert("relatedcontent_".$year,$update_related_content);
						}
						
					}
				}

				if ($this->archive_db->trans_status() === FALSE ) {
				$this->archive_db->trans_rollback();
				$this->session->set_flashdata('Error', "Archive Article not updated Successfully");
				redirect(folder_name.'/article_manager');
				} else {
				$this->archive_db->trans_commit();
				$this->session->set_flashdata('success', "Archive Article Updated Successfully");
				redirect(folder_name.'/article_manager');
				}
				
	}
	
	/*
	*
	* Delete the live article details in all article based table
	*
	* @access public
	* @param content id and type (1)
	* @return TRUE
	*
	*/
	public function delete_livecontents($content_id, $type) {
		$query = $this->live_db->query("CALL delete_livecontents (". $content_id.",".$type.")");
		return $query;
	}
	
	
	/*
	*
	* Update the live article details in article and short article table
	*
	* @access public
	* @param Array of article details
	* @return TRUE
	*
	*/
	public function update_live_article($article_details) {
	
		$this->live_db->query('CALL update_article ('.$article_details["content_id"].','.$article_details["section_id"].',"'.addslashes($article_details["section_name"]).'",'.$article_details["parent_section_id"].',"'.addslashes($article_details["parent_section_name"]).'",'.$article_details["grant_section_id"].',"'.addslashes($article_details["grant_parent_section_name"]).'","'.$article_details["linked_to_columnist"].'","'.$article_details["publish_start_date"].'","'.$article_details["publish_end_date"].'","'.$article_details["last_updated_on"].'","'.addslashes($article_details["title"]).'","'.addslashes($article_details["url"]).'","'.addslashes($article_details["summary_html"]).'","'.addslashes($article_details['article_page_content_html']).'","'.addslashes($article_details["home_page_image_path"]).'","'.addslashes($article_details["home_page_image_title"]).'","'.addslashes($article_details["home_page_image_alt"]).'","'.addslashes($article_details["section_page_image_path"]).'","'.addslashes($article_details["section_page_image_title"]).'","'.addslashes($article_details["section_page_image_alt"]).'","'.addslashes($article_details["article_page_image_path"]).'","'.addslashes($article_details["article_page_image_title"]).'","'.addslashes($article_details["article_page_image_alt"]).'","'.addslashes($article_details["column_name"]).'","'.addslashes($article_details["tags"]).'",'.$article_details["allow_comments"].','.$article_details["allow_pagination"].',"'.addslashes($article_details["agency_name"]).'","'.addslashes($article_details["author_name"]).'","'.addslashes($article_details["author_image_path"]).'","'.addslashes($article_details["author_image_title"]).'","'.addslashes($article_details["author_image_alt"]).'","'.addslashes($article_details["country_name"]).'","'.addslashes($article_details["state_name"]).'","'.addslashes($article_details["city_name"]).'",'.$article_details["no_indexed"].','.$article_details["no_follow"].',"'.addslashes($article_details["canonical_url"]).'","'.addslashes($article_details["meta_Title"]).'","'.addslashes($article_details["meta_description"]).'",'.$article_details["section_promotion"].',"'.$article_details["status"].'")');
	
		$this->live_db->query('CALL update_short_content_details ('.$article_details["content_id"].',"'.addslashes(strip_tags($article_details["title"])).'","'.addslashes($article_details["tags"]).'","'.addslashes($article_details["summary_html"]).'","'.addslashes(strip_tags($article_details['article_page_content_html'])).'",'.$article_details["section_id"].',1)');
		
		return TRUE;
	}
	
	/*
	*
	* Generate the article details from POST value
	*
	* @access public
	* @param POST values from article form
	* @return Set the article details in Array format
	*
	*/
	
	public function get_additional_article_details()

	{	
		/*
		echo "<pre>";
		print_r($_POST);
		exit;
		*/
		extract($_POST);
		
			if($txtStatus == 'D') 
				$data['draft_on'] = date('Y-m-d H:i');
			else 
				$data['draft_on'] = '';
		
			$data['PublishStartDate'] 	= '';
			$data['PublishEndDate']	 	= '';
		
			if(@$txtPublishStartDate1 == '' && $txtStatus == 'P' &&  $txtOldStatus == 'D') {
				$data['PublishStartDate'] = date('Y-m-d H:i');
			} else if($txtStatus == 'D') {
				$data['PublishStartDate'] = date('Y-m-d H:i');
			}
		
			if (@$txtPublishStartDate1 != '') {
			$data['PublishStartDate'] = date('Y-m-d H:i', strtotime(@$txtPublishStartDate1));
			$data['scheduled_article']	= 1;
			} else {
			$data['scheduled_article']	= 0;
			}
			
			if (@$txtPublishStartDate1 == '' && $txtStatus == 'P'  && $schedule_article == 1)
			$data['PublishStartDate'] = date("Y-m-d H:i:s");
			
			if($data['PublishStartDate'] == '' && $txtPublishStartDate != '') 
			$data['PublishStartDate'] =	date('Y-m-d H:i', strtotime($txtPublishStartDate));
		
		if ($txtPublishEndDate != '')
			$data['PublishEndDate'] = date('Y-m-d H:i', strtotime($txtPublishEndDate));
		
			if(!isset($txtPublishStartDate1)) {
				$data['PublishStartDate'] =	date('Y-m-d H:i', strtotime($txtPublishStartDate));
			}
			
			
			
			if( $txtStatus == 'P' && ( $txtOldStatus == 'D' ||  $txtOldStatus == '' ) && folder_name == 'dmcpan') {
					$data['modifiedon'] = $data['PublishStartDate'];
			} else {
					$data['modifiedon'] = date('Y-m-d H:i');
			}

		
		if(trim($txtUrlTitle) == '') 
			$data['UrlTitle'] = addslashes(trim(strip_tags($txtArticleHeadLine)));
		else
			$data['UrlTitle'] = trim($txtUrlTitle);
		
		$data['UrlTitle'] = RemoveSpecialCharacters($data['UrlTitle']);
		$data['UrlTitle'] = mb_strtolower(join( "-",( explode(" ",$data['UrlTitle']) ) ));
		
		
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
				
				
				/*
				$search = 'AuthorName LIKE "'.$txtByLine.'"';
				$get_result = $this->db->query("CALL get_byline_text('".$search."',".$AuthorType.")");
				$AuthorDetails = $get_result->result_array();
				*/
				
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
		
		if($ddAgency == '' && $ddAgency == 0) 
				$data['ddAgency'] = "NULL";
			else 
				$data['ddAgency'] = $ddAgency;
		
	
		if (isset($cbAllowComments) && $cbAllowComments == 'on') $data['cbAllowComments'] = 1;
		else $data['cbAllowComments'] = 0;
		
		if (isset($cbAllowPagination) && $cbAllowPagination == 'on') $data['cbAllowPagination'] = 1;
		else $data['cbAllowPagination'] = 0;
		
		if (isset($cbNoIndex) && $cbNoIndex == 'on') $data['cbNoIndex'] = 1;
		else $data['cbNoIndex'] = 0;
		if (isset($cbNoFollows) && $cbNoFollows == 'on') $data['cbNoFollows'] = 1;
		else $data['cbNoFollows'] = 0;
		if ($ddCountry == '') $data['ddCountry'] = "NULL";
		else $data['ddCountry'] = $ddCountry;
		if ($ddState == '') $data['ddState'] = "NULL";
		else $data['ddState'] = $ddState;
		if ($ddCity == '') $data['ddCity'] = "NULL";
		else $data['ddCity'] = $ddCity;
		if (isset($cbSectionPromotion) && $cbSectionPromotion == 'on') $data['cbSectionPromotion'] = 1;
		else $data['cbSectionPromotion'] = 0;
		
		$home_physical_name = stripslashes(RemoveSpecialCharacters($home_physical_name));

		
		if($imgHomeImageId != '')
		$imgHomeImageId = $this->common_model->add_image_by_temp_id($home_image_caption,$home_image_alt,$home_physical_name,$imgHomeImageId);


		$section_physical_name = stripslashes(RemoveSpecialCharacters($section_physical_name));
	
		if($imgSectionImageId != '')
		$imgSectionImageId = $this->common_model->add_image_by_temp_id($section_image_caption,$section_image_alt,$section_physical_name,$imgSectionImageId);
	
		$article_physical_name = stripslashes(RemoveSpecialCharacters($article_physical_name));
	
		if($imgArticleImageId != '')
		$imgArticleImageId = $this->common_model->add_image_by_temp_id($article_image_caption,$article_image_alt,$article_physical_name,$imgArticleImageId);
		
		if ($imgHomeImageId == '' || $imgHomeImageId == 0 ) $data['imgHomeImageId'] = "NULL";
		else $data['imgHomeImageId'] = $imgHomeImageId;
		if ($imgSectionImageId == ''  || $imgSectionImageId == 0 ) $data['imgSectionImageId'] = "NULL";
		else $data['imgSectionImageId'] = $imgSectionImageId;
		if ($imgArticleImageId == ''  || $imgArticleImageId == 0 ) $data['imgArticleImageId'] = "NULL";
		else $data['imgArticleImageId'] = $imgArticleImageId;
		
		$MainSection = get_section_by_id($ddMainSection);
		
		$Year =  date('Y', strtotime($data['PublishStartDate']));
		$Month =  date('M', strtotime($data['PublishStartDate']));
		$Date =  date('d', strtotime($data['PublishStartDate']));
		
		
		$data['url']   = mb_strtolower(join( "-",( explode(" ",@$MainSection['URLSectionStructure'] ))))."/".$Year."/".mb_strtolower($Month)."/".$Date."/".$data['UrlTitle'];
		
		
		$data  = array_map('trim',$data);
		
		# Start the Live Article Table Details 
	
		$LiveArticleDetails = array();	
		
		$LiveArticleDetails['ecenic_id'] 								= 'NULL';
		$LiveArticleDetails['section_id'] 								= 'NULL';
		$LiveArticleDetails['section_name'] 								= '';
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
		
		# Author Image Empty Data
		
		$LiveArticleDetails['url'] 											= $data['url'] ;
		
		$LiveArticleDetails['author_image_path'] 							= '';
		$LiveArticleDetails['author_image_title'] 							= '';
		$LiveArticleDetails['author_image_alt'] 							= '';
		
		$LiveArticleDetails['column_name'] 									= '';
		$LiveArticleDetails['hits']											= 0;
		$LiveArticleDetails['tags']											= '';
		
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
		$LiveArticleDetails['status'] 										= $txtStatus;

			
			$MainSection = get_section_by_id($ddMainSection);
			
			if(isset($MainSection)) {
			
			$LiveArticleDetails['section_id'] 			= $MainSection['Section_id'];
			$LiveArticleDetails['section_name'] 		= $MainSection['Sectionname'];
			
			if($MainSection['AuthorID'] != '' && $MainSection['AuthorID'] != 'NULL' && $MainSection['AuthorID'] != 0) {			   $AuthorDetails 								= get_authordetails_by_id($MainSection['AuthorID']);
						$LiveArticleDetails['linked_to_columnist'] 	 = 1;
						$column_id 			 						 = $AuthorDetails['column_id'];
						
						
				$LiveArticleDetails['author_name'] 		= 	$AuthorDetails['AuthorName'];		
				$data['ddByLine']		 				= 	$MainSection['AuthorID'];	
				
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
				if(isset($MainSection['ParentSectionID']) && $MainSection['ParentSectionID'] != '') {
					
					$ParentMainSection = get_section_by_id($MainSection['ParentSectionID']);
					
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
			if($data['PublishStartDate'] != '')
			$LiveArticleDetails['publish_start_date'] 			= date('Y-m-d H:i', strtotime($data['PublishStartDate']));
		
			if($data['PublishEndDate'] != '')
			$LiveArticleDetails['publish_end_date']				= date('Y-m-d H:i', strtotime($data['PublishEndDate']));
				
			$LiveArticleDetails['last_updated_on'] 				= $data['modifiedon'];	
			$LiveArticleDetails['title'] 						= $txtArticleHeadLine;
			$LiveArticleDetails['summary_html'] 				= $txtSummary;
			$LiveArticleDetails['article_page_content_html'] 	= $txtBodyText;

			if($data['imgHomeImageId'] != 'NULL') {
				
				$HomeImageDetails = GetImageDetailsByContentId($data['imgHomeImageId']);

				$LiveArticleDetails['home_page_image_path'] 					= $HomeImageDetails['ImagePhysicalPath'];
				$LiveArticleDetails['home_page_image_title'] 					= $HomeImageDetails['ImageCaption'];
				$LiveArticleDetails['home_page_image_alt'] 						= $HomeImageDetails['ImageAlt'];
			}
	
			if($data['imgSectionImageId'] != 'NULL') {
				
				$SectionImageDetails = GetImageDetailsByContentId($data['imgSectionImageId']);

				$LiveArticleDetails['section_page_image_path'] 						= $SectionImageDetails['ImagePhysicalPath'];
				$LiveArticleDetails['section_page_image_title'] 					= $SectionImageDetails['ImageCaption'];
				$LiveArticleDetails['section_page_image_alt'] 						= $SectionImageDetails['ImageAlt'];
			}

			if($data['imgArticleImageId'] != 'NULL') {
					
				$ArticleImageDetails = GetImageDetailsByContentId($data['imgArticleImageId']);

				$LiveArticleDetails['article_page_image_path'] 						= $ArticleImageDetails['ImagePhysicalPath'];
				$LiveArticleDetails['article_page_image_title'] 					= $ArticleImageDetails['ImageCaption'];
				$LiveArticleDetails['article_page_image_alt'] 						= $ArticleImageDetails['ImageAlt'];
			}
			
			if(isset($column_id) && $column_id != '' && $column_id != 'NULL') {
				$ColumnDetails = column_editdetails($column_id);
				
				if(isset($ColumnDetails[0]['column_name']))
					$LiveArticleDetails['column_name'] = $ColumnDetails[0]['column_name'];
			}
	
			if(isset($txtTags) != '')
			$LiveArticleDetails['tags'] 					= implode(', ',@$txtTags);
		
			$LiveArticleDetails['allow_comments'] 			=  $data['cbAllowComments'];
			$LiveArticleDetails['allow_pagination'] 			=  $data['cbAllowPagination'];
			
			if($data['ddAgency'] != "NULL")
				$LiveArticleDetails['agency_name'] 			= @get_agencyname_by_id($data['ddAgency']);
			
			
			if($data['ddByLine'] != "NULL" && $LiveArticleDetails['author_name'] == '' ) {
				
				$LiveArticleDetails['author_name'] 		= $txtByLine;			
				
				$AuthorDetails 							= get_authordetails_by_id($data['ddByLine']);
				
				if($LiveArticleDetails['author_image_path']  == '' && @$AuthorDetails['image_path'] != '' ) {
	
					$LiveArticleDetails['author_image_path'] 					= @$AuthorDetails['image_path'];
					$LiveArticleDetails['author_image_title'] 					= @$AuthorDetails['image_caption'];
					$LiveArticleDetails['author_image_alt'] 					= @$AuthorDetails['image_alt'];
					
				}
			}

				
			if($data['ddCountry'] != "NULL")
				$LiveArticleDetails['country_name'] 		= @get_countryname_by_id($data['ddCountry']);
				
			if($data['ddState'] != "NULL")	
				$LiveArticleDetails['state_name'] 			= $txtState;
				
			if($data['ddCity'] != "NULL")	
				$LiveArticleDetails['city_name'] 			= $txtCity;
	
			$LiveArticleDetails['no_indexed'] 				=  $data['cbNoIndex'];
			$LiveArticleDetails['no_follow'] 				= $data['cbNoFollows'];
			$LiveArticleDetails['canonical_url']  			= $txtCanonicalUrl;
			$LiveArticleDetails['meta_Title']  				= $txtMetaTitle;
			$LiveArticleDetails['meta_description']  		= $txtMetaDescription;
			$LiveArticleDetails['section_promotion'] 		= $data['cbSectionPromotion'];
			//$LiveArticleDetails['status'] 					= 'P';
		//}
		
		$data['LiveArticleDetails'] = array_map('trim',$LiveArticleDetails); 
		
		# End the Live Article Table Details 
		/*
		echo "<pre>";
		print_r($data);
		exit;
		*/
		
		
		
		return $data;
	}
	
	/*
	*
	* Get the article data table using article manager page
	*
	* @access public
	* @param POST values from article manager view file
	* @return JSON format output to article manager page
	*
	*/
	public function get_article_datatables() {
			extract($_POST);
		
		$Search_text = trim($Search_text);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

			$content_type = "1"; 
			  $menu_name		= "Article";
		 
		 $Menu_id = get_menu_details_by_menu_name($menu_name);

		
		switch ($Field) {
    case 1:
        $order_field 	= 'm.title';
		$archive_field 	= 'title';
        break;
    case 2:
        $order_field 	= 'ard.Section_id';
		$archive_field 	= 'section_name';
        break;
	case 4:
       $order_field 	= 'am.AuthorName';
	   $archive_field 	= 'author_name';
	   break;
	case 5:
       $order_field 	= 'um.Username';
	   $archive_field 	= 'created_by';
       break;
	case 6:
       $order_field 	= 'm.Modifiedon';
	   $archive_field 	= 'modified_on';
       break;
	case 7:
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

		$article_manager =  $this->db->query('CALL article_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'")')->result_array();	
		
		$recordsFiltered = $this->db->query('CALL article_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT 0, 250 ","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'")')->num_rows();
		
		/*$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ; */
		$data['data'] = array();
		$Count = 0;

		foreach($article_manager as $article) {
			
			$article_image = '';

			 $edit_url = "edit_article/".urlencode(base64_encode($article['content_id']));

			$subdata = array();
			
			$Style = "";
			
			if($article['status'] == 'P' && strtotime($article['publish_start_date']) > strtotime(date('d-m-Y H:i:s'))) 
			$Style = "style='color:red'";
			$subdata[] = $article['content_id'];
			$subdata[] ='<p class="tooltip_cursor" '.$Style.' title="'.strip_tags($article['title']).'">'.shortDescription(strip_tags($article['title'])).'</p>';

			$subdata[] =  GenerateBreadCrumbBySectionId($article['Section_id']);
			
			if($article['articlepageimageid'] != '' ||  $article['homepageimageid'] || $article['Sectionpageimageid'] ) {
					if($article['ImagePhysicalPath'] != '') {
						$Image150X150 	= str_replace("original","w150X150", $article['ImagePhysicalPath']);
						$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
					} else {
						$subdata[] = '<td><i class="fa fa-picture-o"></i></td>';	
					}
				} else  {
				$subdata[] = '<td>-</td>';
				}	
			
			$subdata[] = ($article['AuthorName'] != '') ? ($article['AuthorName']) : '-';
			
			$subdata[] = $article['Username'];
			$change_date_format = date('d-m-Y H:i:s', strtotime($article['Modifiedon']));
			$subdata[] = $change_date_format;
			
			switch($article["status"])
			{
			case("P"):
				$status_icon = '<span data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$article['content_id'].'" data-original-title="Active"><i id="status_img'.$article['content_id'].'"  class="fa fa-check"></i></span>';
				break;
			case("U"):	
				$status_icon = '<span data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-times"></i></span>';
				break;
			case("D"):			
				$status_icon = '<span data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-floppy-o"></i></span>';
				break;	
			default;
				$status_icon = '';
			}
			
			$subdata[] = $status_icon;
			
			//$subdata[] = $article['Hits'];
			//$subdata[] = 0;
			
			$set_status ='<div class="buttonHolder">';
			
				if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
					$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a><a class="button tick tooltip-2"  href="'.BASEURL.$article['url'].'?page=preview" target="_blank" title="Preview"><i class="fa fa-eye"></i></i></a>';
				}
				else {
					$set_status .= '';
				}
			
				if($article["status"]=="P")
                {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { 
					$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Unpublish" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-pause"></i></a>'.'';
					}
				}
                elseif($article["status"]=="U")
                { 
				 	if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<a data-toggle="tooltip" href="#" title="Publish" class="button heart" data-original-title="" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-caret-right"></i></a>'.'';
					}
				}
				
				if($article["status"]=="P" ) {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="unpublish_checkbox[]" value="'.$article['content_id'].'" id="unpublish_checkbox_id" status ="'.$article["status"].'"    ></span>';
					}
				}
				
				if($article["status"]=="U" ) {
					if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"    title="Select"   name="publish_checkbox[]" value="'.$article['content_id'].'"   status ="'.$article["status"].'"    id="publish_checkbox_id" ></span>';
					}
				}
				/*
				if($article["status"]=="D") {
					if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"   name="trash_checkbox[]" value="'.$article['content_id'].'"  status ="'.$article["status"].'"    id="publish_checkbox_id" ></span>';
					}
				} */
				
			
			if($set_status != '') {			  
			$set_status .= '</div>';
			$subdata[] = $set_status ;
			}
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
		if($check_in != '' && $check_out != '') {
			
			
			
			if($CheckInYear <= $CurrentYear) {
				
				$TableName = "article_".$CheckInYear;

				
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
								case "ByLine":
								$this->archive_db->like("author_name",$Search_value);
								break;
								default:
								$this->archive_db->where("( title LIKE '%".$Search_value."%' OR  created_by LIKE '%".$Search_value."%' OR author_name LIKE '%".$Search_value."%')");
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
								case "ByLine":
								$this->archive_db->like("author_name",$Search_value);
								break;
								default:
								$this->archive_db->where("( title LIKE '%".$Search_value."%' OR  created_by LIKE '%".$Search_value."%' OR author_name LIKE '%".$Search_value."%')");
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

							$edit_url = "edit_archive_article/".$CheckInYear."/".urlencode(base64_encode($article['content_id']));

							$subdata = array();
							
							$Style = "";
							
							$subdata[] = $article['content_id'];
							
							if($article['status'] == 'P' && strtotime($article['publish_start_date']) > strtotime(date('d-m-Y H:i:s'))) 
							$Style = "style='color:red'";
					
							$subdata[] ='<p class="tooltip_cursor" '.$Style.' title="'.strip_tags($article['title']).'">'.shortDescription(strip_tags($article['title'])).'</p>';

							$subdata[] =  (GetBreadCrumbByURL($article['url']));
							
							if($article['articlepageimageid'] != '' ||  $article['homepageimageid'] || $article['sectionpageimageid'] ) {
									if($article['article_page_image_path'] != '') {
										$Image150X150 	= str_replace("original","w150X150", $article['article_page_image_path']);
										$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
									} else {
										$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a></td>';	
									}
								} else  {
								$subdata[] = '<td><a href="javascript:void()">-</a></td>';
								}	
							
							$subdata[] = ($article['author_name'] != '') ? ($article['author_name']) : '-';
							
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
									$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a><a class="button tick tooltip-2"  href="'.BASEURL.$article['url'].'?page=preview" target="_blank" title="Preview"><i class="fa fa-eye"></i></i></a>';
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
	* Search the related contents in article
	*
	* @access public
	* @param Ajax call post values
	* @return JSON format array values
	*/
	
		public function search_internal_article()

	{
		extract($_POST);
		$Search_text = trim($Search_text);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		$content_type = $article_Type;
		$data = array();
		
		switch ($Field)
		{
		case 0:
			$order_field = 'm.title';
			$archive_field = 'title';
			break;

		case 1:
			$order_field = 's.Section_id';
			$archive_field = 'section_id';
			break;

		case 2:
			$order_field = 'm.publish_start_date';
			$archive_field = 'publish_start_date';
			break;

		default:
			$order_field = 'm.content_id';
			$archive_field = "content_id";
		}
		if (isset($content_id) && $content_id != '')
		{
			$content_where_condition = " AND m.content_id != " . $content_id . " ";
		}
		else
		{
			$content_where_condition = "";
		}
	
		$Total_rows = 100;//$this->db->query('CALL get_related_content_datatable ("' . $content_where_condition . ' ","","","","","","","' . $content_type . '")')->num_rows();
		
		$Search_value = $Search_text;
		if ($Search_by == 'article_id')
		{
			$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
			if ($Search_result == '') $Search_value = $Search_text;
			else $Search_value = $Search_result;
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
	//	if(isset($CheckInYear) == isset($CheckOutYear) && $CheckInYear <= )
		
		$article_manager = $this->db->query('CALL get_related_content_datatable ("' . $content_where_condition . 'ORDER BY ' . $order_field . ' ' . $order . ' LIMIT ' . $start . ', ' . $length . '","' . $check_in . '","' . $check_out . '","' . $Search_value . '","' . $Search_by . '","' . $Section . '","' . $Status . '","' . $content_type . '")')->result_array();
		
		$recordsFiltered = $this->db->query('CALL get_related_content_datatable ("' . $content_where_condition . 'ORDER BY ' . $order_field . ' ' . $order . ' LIMIT 0, 100","' . $check_in . '","' . $check_out . '","' . $Search_value . '","' . $Search_by . '","' . $Section . '","' . $Status . '","' . $content_type . '")')->num_rows();

			
			$data['data'] 				= array();
			$Count 						= 0;
		
		
		if($recordsFiltered != 0)  {
		
			foreach($article_manager as $article)
			{
				
				 switch($article_Type) {
				 case 1:
				 $edit_url = "edit_article/".urlencode(base64_encode($article['content_id']));
				 break;
				 break;
				 case 3; 
				 $edit_url = "edit_gallery/".urlencode(base64_encode($article['content_id']));
				 break;
				 case 4; 
				 $edit_url = "audio_video_manager/edit_data/4/".urlencode(base64_encode($article['content_id']));
				 break;
				 case 5; 
				 $edit_url = "audio_video_manager/edit_data/5/".urlencode(base64_encode($article['content_id']));			
				 break;
				 case 6; 
				 $edit_url = "edit_resources/".urlencode(base64_encode($article['content_id']));			
				 break;
				 default: 
				 $edit_url = "";
				}
				
				$subdata = array();
				
				$subdata[] = '<div align="center"><p title="' . stripslashes(strip_tags($article['title'])) . '" ><a href="'.base_url().folder_name."/".$edit_url.'" >' . stripslashes(shortDescription(strip_tags($article['title']))) . '</a></p></div>';
				$URLSectionStructure = (isset($article['Section_id']))? GenerateBreadCrumbBySectionId($article['Section_id']) : "-";
				$subdata[] = $URLSectionStructure;
				$subdata[] = date('d-m-Y H:i:s', strtotime($article['publish_start_date']));
				$subdata[] = '<a href="javascript:void(0);" long_title ="'.stripslashes(trim(strip_tags($article['title']))) . '" short_title="'.stripslashes(trim(shortDescription(strip_tags($article['title'])))) . '" value="' . $article['content_id'] . '" rel="'.$content_type.'" data-toggle="tooltip" href="javascript:void()" class="button tick" url="'.$article['url'].'" bread_crumb="'.$URLSectionStructure.'"  title="Add" data-original-title="Add" id="internal_action" ><i class="fa fa-plus"></i></a>';
				
				
				$data['data'][$Count] = $subdata;
				$Count++;
			}
		}
		
		if($check_in != '' && $check_out != '') {
			
			
			
			if($CheckInYear <= $CurrentYear) {
				

				
				switch($content_type) {
					case 1:
					$TableName = "article_".$CheckInYear;
					break;
					case 3:
					$TableName = "gallery_".$CheckInYear;
					break;
					case 4:
					$TableName = "video_".$CheckInYear;
					break;
					case 5:
					$TableName = "audio_".$CheckInYear;
					break;
					case 6:
					$TableName = "resources_".$CheckInYear;
					break;
				}

				
				if ($this->archive_db->table_exists($TableName)) {
					
					$ArchiveRecordsFiltered = 0;
					
					if($content_type != 6) {
					
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
							
							if(trim($Search_value) != '')
									$this->archive_db->like("title",$Search_value);
							
							if($Section != '')
								$this->archive_db->like("section_id",$Section);
							
							if (isset($content_id) && $content_id != '')
								$this->archive_db->where("content_id !=",$content_id);
							
							
							$this->archive_db->limit($length,$start);
							$this->archive_db->order_by($archive_field,$order);
							
							$Get = $this->archive_db->get();
							$archive_content_manager 	= $Get->result_array();
							
							//echo $this->archive_db->last_query();
							//exit;
							
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
											
							if(trim($Search_value) != '')
									$this->archive_db->like("title",$Search_value);
							
							if($Section != '')
								$this->archive_db->like("section_id",$Section);
							
							if (isset($content_id) && $content_id != '')
								$this->archive_db->where("content_id !=",$content_id);
							
							
							$this->archive_db->limit(100,0);
							
							$Get = $this->archive_db->get();
							$ArchiveRecordsFiltered =  $Get->num_rows();
					
					} else {

					
					
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
							
							if(trim($Search_value) != '')
									$this->archive_db->like("title",$Search_value);
							
							if (isset($content_id) && $content_id != '')
								$this->archive_db->where("content_id !=",$content_id);
							
							
							$this->archive_db->limit($length,$start);
							$this->archive_db->order_by($archive_field,$order);
							
							$Get = $this->archive_db->get();
							$archive_content_manager 	= $Get->result_array();
							
							//echo $this->archive_db->last_query();
							//exit;
							
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
											
							if(trim($Search_value) != '')
									$this->archive_db->like("title",$Search_value);
								
							if (isset($content_id) && $content_id != '')
								$this->archive_db->where("content_id !=",$content_id);
							
							$this->archive_db->limit(100,0);
							
							$Get = $this->archive_db->get();
							$ArchiveRecordsFiltered =  $Get->num_rows();					
						
					}
					
					
					if($ArchiveRecordsFiltered != 0 ){
							foreach($archive_content_manager as $article) {
							
							$subdata = array();
							
							$SectionDetails  = get_section_by_id(@$article['section_id']);
							$URLSectionStructure = @$SectionDetails['URLSectionStructure'];
							
							
							
							$subdata[] = '<div align="center"><p title="' . stripslashes(strip_tags($article['title'])) . '" ><a href="javascript:void(0);" >' . stripslashes(shortDescription(strip_tags($article['title']))) . '</a></p></div>';
							$URLSectionStructure = ($URLSectionStructure != '')? $URLSectionStructure : "-";
							$subdata[] = $URLSectionStructure;
							$subdata[] = date('d-m-Y H:i:s', strtotime($article['publish_start_date']));
							$subdata[] = '<a href="javascript:void(0);" long_title ="'.stripslashes(trim(strip_tags($article['title']))) . '" short_title="'.stripslashes(trim(shortDescription(strip_tags($article['title'])))). '" value="' . $article['content_id'] . '" rel="'.$content_type.'" data-toggle="tooltip" href="javascript:void()" class="button tick" url="'.$article['url'].'" bread_crumb="'.$URLSectionStructure.'"  title="Add" data-original-title="Add" id="internal_action" ><i class="fa fa-plus"></i></a>';
							
							
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
	* Insert the article section mapping data
	*
	* @access Public
	* @param  article_id from article master table
	* @return TRUE;
	*/
	
	public function insert_article_mapping($article_id)

	{
		extract($_POST);
		
		if (isset($article_id) && $article_id != '')
		{
			$insert_array 	= array();
			$insert_array[] = $article_id;
			$insert_array[] = $ddMainSection;
			
			$result = implode('","', $insert_array);
			
			$article_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",1)');
			$article_mapping->result();
			
			if($this->input->post('txtStatus') == 'P') {
			$result = implode('","', $insert_array);
			$article_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",1)');
			}
			
			if (isset($cbSectionMapping))
			{
				$cbSectionMapping = array_diff($cbSectionMapping, array($ddMainSection));
			
				foreach($cbSectionMapping as $mapping)
				{
					$insert_array 	= array();
					$insert_array[] = $article_id;
					$insert_array[] = $mapping;
					
					$result = implode('","', $insert_array);
					
					$article_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",1)');
					
					 if($this->input->post('txtStatus') == 'P') {
						
						$live_insert_array 		= array();
						
						$live_insert_array[] 	= $article_id;
						$live_insert_array[] 	= $mapping;

						$result = implode('","', $live_insert_array);
						$article_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",1)');
						
					}  
					
				}
			}
		}
		
		return TRUE;
	}
	/*
	*
	* Delete the article section mapping data
	*
	* @access Public
	* @param  article_id from article master table
	* @return TRUE or FALSE
	*/
	public function delete_content_mapping($content_id)

	{
		
		if($this->input->post('txtStatus') == 'P' || $this->input->post('txtStatus') == 'U')	
		$query = $this->live_db->query("CALL delete_section_mapping (". $content_id.",1)");
		
		$content_mapping = $this->db->query('CALL delete_content_mapping (' . $content_id . ',1)');
		return $content_mapping;
	}

	
	/*
	*
	* Insert the article related content data
	*
	* @access Public
	* @param  article_id from article master table
	* @return TRUE  or FALSE
	*/
	
	public function insert_related_article($article_id)

	{
		extract($_POST);
		if ($hide_external_link != '' && $hide_external_link != '[]')
		{
			$external_array = json_decode($hide_external_link);
		
			foreach($external_array as $key=>$external)
			{
				$null_value 	= "NULL";
				$display_order 	= ($key+1);
				
				if ($external->type == 'E')
				{
					
					$content_type 	= 0;
					$content_title 	= addslashes(trim($external->external_title));
					$content_url 	= addslashes($external->external_url);
					
					$article_relation = $this->db->query('CALL add_related_content ("' . $article_id . '","0",' . $null_value . ',"' .$content_title . '","' . $content_url  . '","'.$display_order.'")');
				}
				else
				{
					if(isset($external->content_id) && $external->content_id != '')
					//$related_article_details = $this->get_article_details($external->content_id)->result_array();
					
					$content_type 	=  $external->content_type;
					//$content_title	= addslashes(@$related_article_details[0]['title']);
					//$content_url 	= addslashes(@$related_article_details[0]['url']);
				
					$article_relation = $this->db->query('CALL add_related_content ("' . $article_id . '","' . $external->content_type . '",' . $external->content_id . ',"' .addslashes($external->long_title) . '","' . addslashes($external->url) . '","' . $display_order . '")');
				}
				
				if($this->input->post('txtStatus') == 'P') {
					$this->live_db->query('CALL add_article_related_content ("' . $article_id . '","'.$content_type.'","'.addslashes(trim($external->long_title)).'","'.addslashes(trim($external->url)).'","'.$display_order.'")');
				}
				
			}
		}
	}
	
	/*
	*
	* Delete the article related content data
	*
	* @access Public
	* @param  article_id from article master table
	* @return TRUE;
	*/
	
	public function delete_related_article($article_id)

	{
		$related_article = $this->db->query('CALL delete_related_article  ("' . $article_id . '")');
		
		if($this->input->post('txtStatus') == 'P' || $this->input->post('txtStatus') == 'U')	
			$query = $this->live_db->query("CALL delete_article_related_content (". $article_id.")");
		
		return $related_article;
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
	
}
/* End of file article_model.php */
/* Location: ./application/models/admin/article_model.php */
