<?php
/**
 * Audio_Video Model Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */


header('Content-Type: text/html; charset=utf-8');
class Audio_video_model extends CI_Model

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
	
	public function insert_audio_video()

	{
			extract($_POST);	
	
			$audio_video_details 	= $this->get_additional_audio_video_details();
		
			if(isset($txtTags) != '')
				$txtTags 		= PrepareTagInputValue($txtTags);
			else
				$txtTags = '';
			
			$null_value 		= "NULL";
	
			$this->db->trans_begin();
			$this->live_db->trans_begin();
			
			if($txtContentType == 4)			
			$this->db->query('SET @contentid =0; CALL add_update_videomaster(NULL,"'.addslashes($audio_video_details['UrlTitle']).'","'.addslashes($txtAudioVideoHeadLine).'","'.addslashes($audio_video_details['url']).'","'.addslashes($txtSummary).'","'.addslashes($txtTags).'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$audio_video_details['PublishStartDate'].'","'.$audio_video_details['cbNoIndex'].'","'.$audio_video_details['cbNoFollows'].'","'.addslashes($txtCanonicalUrl).'","'.$audio_video_details['cbAllowComments'].'","'.$ddMainSection.'",'.$audio_video_details['ddAgency'].','.$audio_video_details['ddByLine'].','.$audio_video_details['ddCountry'].','.$audio_video_details['ddState'].','.$audio_video_details['ddCity'].','.$audio_video_details['imgAudioVideoId'].',"'.addslashes($audio_video_details['script']).'","","'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'. date("Y-m-d H:i:s").'",@contentid)');
			
			if($txtContentType == 5)
			$this->db->query('SET @contentid =0; CALL add_update_audiomaster(NULL,"'.addslashes($audio_video_details['UrlTitle']).'","'.addslashes($txtAudioVideoHeadLine).'","'.addslashes($audio_video_details['url']).'","'.addslashes($txtSummary).'","'.addslashes($txtTags).'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$audio_video_details['PublishStartDate'].'","'.$audio_video_details['cbNoIndex'].'","'.$audio_video_details['cbNoFollows'].'","'.addslashes($txtCanonicalUrl).'","'.$audio_video_details['cbAllowComments'].'","'.$ddMainSection.'",'.$audio_video_details['ddAgency'].','.$audio_video_details['ddByLine'].','.$audio_video_details['ddCountry'].','.$audio_video_details['ddState'].','.$audio_video_details['ddCity'].','.$audio_video_details['imgAudioVideoId'].',"'.addslashes($audio_video_details['audio_path']).'","'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'. date("Y-m-d H:i:s").'",@contentid)');
				
	
			$result 	= $this->db->query("SELECT @contentid")->result_array();
			$audio_video_id = $result[0]['@contentid'];
			

			if($audio_video_id != '' && $audio_video_id != 'NULL') {
				
				$audio_video_details['url'] = $audio_video_details['url']."-".$audio_video_id.".html";
				
				$this->db->query('CALL update_url_structure('.$audio_video_id.',"'.addslashes($audio_video_details['url']).'",'.$txtContentType.')');
			
				if ($txtStatus == 'P') {
				$audio_video_details['LiveAudioVideoDetails']['url'] 			= $audio_video_details['url'];
				$audio_video_details['LiveAudioVideoDetails']['content_id'] 	= $audio_video_id;
				
				if($txtContentType == 4)	
					$this->insert_update_live_video($audio_video_details['LiveAudioVideoDetails']);
				
				if($txtContentType == 5)
					$this->insert_update_live_audio($audio_video_details['LiveAudioVideoDetails']);
				
				}  
				
				$this->insert_content_mapping($audio_video_id,$txtContentType);
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
	* Insert the article details in article master and related data
	*
	* @access public
	* @param Post values from Add Form
	* @return TRUE
	*
	*/
	
	public function update_audio_video($content_id)

	{
			extract($_POST);	
	
			$audio_video_details 	= $this->get_additional_audio_video_details();
		
			if(isset($txtTags) != '')
				$txtTags 		= PrepareTagInputValue($txtTags);
			else
				$txtTags = '';
			
			$null_value 		= "NULL";
	
			$this->db->trans_begin();
			$this->live_db->trans_begin();
			
			$audio_video_details['url'] = $audio_video_details['url']."-".$content_id.".html";
			
			if($txtContentType == 4)		
			$this->db->query('SET @contentid ='.$content_id.'; CALL add_update_videomaster(NULL,"'.addslashes($audio_video_details['UrlTitle']).'","'.addslashes($txtAudioVideoHeadLine).'","'.addslashes($audio_video_details['url']).'","'.addslashes($txtSummary).'","'.addslashes($txtTags).'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$audio_video_details['PublishStartDate'].'","'.$audio_video_details['cbNoIndex'].'","'.$audio_video_details['cbNoFollows'].'","'.addslashes($txtCanonicalUrl).'","'.$audio_video_details['cbAllowComments'].'","'.$ddMainSection.'",'.$audio_video_details['ddAgency'].','.$audio_video_details['ddByLine'].','.$audio_video_details['ddCountry'].','.$audio_video_details['ddState'].','.$audio_video_details['ddCity'].','.$audio_video_details['imgAudioVideoId'].',"'.addslashes($audio_video_details['script']).'","","'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'. date("Y-m-d H:i:s").'",@contentid)');
		
			if($txtContentType == 5)
			$this->db->query('SET @contentid ='.$content_id.'; CALL add_update_audiomaster(NULL,"'.addslashes($audio_video_details['UrlTitle']).'","'.addslashes($txtAudioVideoHeadLine).'","'.addslashes($audio_video_details['url']).'","'.addslashes($txtSummary).'","'.addslashes($txtTags).'","'.addslashes(trim($txtMetaTitle)).'","'.addslashes(trim($txtMetaDescription)).'","'.$audio_video_details['PublishStartDate'].'","'.$audio_video_details['cbNoIndex'].'","'.$audio_video_details['cbNoFollows'].'","'.addslashes($txtCanonicalUrl).'","'.$audio_video_details['cbAllowComments'].'","'.$ddMainSection.'",'.$audio_video_details['ddAgency'].','.$audio_video_details['ddByLine'].','.$audio_video_details['ddCountry'].','.$audio_video_details['ddState'].','.$audio_video_details['ddCity'].','.$audio_video_details['imgAudioVideoId'].',"'.addslashes($audio_video_details['audio_path']).'","'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'. date("Y-m-d H:i:s").'",@contentid)');
			

			if($content_id != '' && $content_id != 'NULL') {
			
				if ($txtStatus == 'P') {
				$audio_video_details['LiveAudioVideoDetails']['url'] 			= $audio_video_details['url'];
				$audio_video_details['LiveAudioVideoDetails']['content_id'] 	= $content_id;
				
					if($txtContentType == 4)	
						$this->insert_update_live_video($audio_video_details['LiveAudioVideoDetails']);
					
					if($txtContentType == 5)
						$this->insert_update_live_audio($audio_video_details['LiveAudioVideoDetails']);
				
				} 
				
				
					if($this->delete_content_mapping($content_id,$txtContentType))
					$this->insert_content_mapping($content_id,$txtContentType);	
				
			
				if($txtStatus == 'U')
				$this->delete_livecontents($content_id,$txtContentType);
				
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
	* Insert the article details in article master and related data
	*
	* @access public
	* @param Post values from Add Form
	* @return TRUE
	*
	*/
	
	public function update_archive_audio_video($year, $content_id)

	{
			extract($_POST);	
	
			$audio_video_details 	= $this->get_additional_audio_video_details();
		
			if(isset($txtTags) != '')
				$txtTags 		= PrepareTagInputValue($txtTags);
			else
				$txtTags = '';
			
			$null_value 		= "NULL";
	
			$this->archive_db->trans_begin();
			
			$update_archive_details = $audio_video_details['LiveAudioVideoDetails'];
			
			$update_archive_details['url'] = $audio_video_details['url']."-".$content_id.".html";
			
			unset($update_archive_details['ecenic_id']);
			unset($update_archive_details['hits']);	
			
			$update_archive_details['tag_ids']  = $txtTags;
			$update_archive_details['agency_id']  = $audio_video_details['ddAgency'];
			$update_archive_details['country_id']  = $audio_video_details['ddCountry'];
			$update_archive_details['state_id']  = $audio_video_details['ddState'];
			$update_archive_details['city_id']  = $audio_video_details['ddCity'];
			$update_archive_details['image_id']	= $audio_video_details['imgAudioVideoId'];
			
			$update_archive_details['modified_by'] = get_userdetails_by_id(USERID);
			$update_archive_details['modified_on'] = $update_archive_details['last_updated_on'];
			
			if($txtContentType == 4) {
				
				$update_archive_details['video_image_path']  	= $update_archive_details['audio_video_image_path'];
				$update_archive_details['video_image_title']  	= $update_archive_details['audio_video_image_title'];
				$update_archive_details['video_image_alt']  	= $update_archive_details['audio_video_image_alt'];
				$update_archive_details['video_script']  		= $update_archive_details['script'];
					
				unset($update_archive_details['audio_video_image_path']);
				unset($update_archive_details['audio_video_image_title']);
				unset($update_archive_details['audio_video_image_alt']);	
				unset($update_archive_details['script']);
					
				$this->archive_db->where("content_id",$content_id);
				$this->archive_db->update("video_".$year,$update_archive_details);
				
				$MappingTableName = "video_section_mapping_".$year;
				
			}
			
		
			if($txtContentType == 5) {
				
				$update_archive_details['audio_image_path']  = $update_archive_details['audio_video_image_path'];
				$update_archive_details['audio_image_title']  = $update_archive_details['audio_video_image_title'];
				$update_archive_details['audio_image_alt']  = $update_archive_details['audio_video_image_alt'];
				
				unset($update_archive_details['audio_video_image_path']);
				unset($update_archive_details['audio_video_image_title']);
				unset($update_archive_details['audio_video_image_alt']);
				
				$this->archive_db->where("content_id",$content_id);
				$this->archive_db->update("audio_".$year,$update_archive_details);
				
				$MappingTableName = "audio_section_mapping_".$year;
				
			}
			
			
				
				$this->archive_db->where("content_id",$content_id);
				$this->archive_db->delete($MappingTableName );

				$insert_array 	= array();
				$insert_array['content_id'] = $content_id;
				$insert_array['section_id'] = $ddMainSection;
			
				$this->archive_db->insert($MappingTableName ,$insert_array);
			
				if (isset($cbSectionMapping))
				{
					$cbSectionMapping = array_diff($cbSectionMapping, array($ddMainSection));
				
					foreach($cbSectionMapping as $mapping)
					{
						$insert_array 	= array();
						$insert_array['content_id'] = $content_id;
						$insert_array['section_id'] = $mapping;
						
						$this->archive_db->insert($MappingTableName ,$insert_array);
						
					}
				}		
				
			if ($this->archive_db->trans_status() === FALSE ) {
				$this->archive_db->trans_rollback();
				return FALSE;
			} else {
				$this->archive_db->trans_commit();
				return TRUE;
			}
		
		
	}
	
	
	/*
	*
	* Insert the live video details in video and short video details
	*
	* @access public
	* @param Array of video details
	* @return TRUE
	*
	*/
	
	public function insert_update_live_video($audio_video_details) {
	
	$this->live_db->query('CALL add_update_video('.$audio_video_details["content_id"].',NULL,'.$audio_video_details["section_id"].',"'.addslashes($audio_video_details["section_name"]).'",'.$audio_video_details["parent_section_id"].',"'.addslashes($audio_video_details["parent_section_name"]).'",'.$audio_video_details["grant_section_id"].',"'.addslashes($audio_video_details["grant_parent_section_name"]).'","'.$audio_video_details["publish_start_date"].'","'.$audio_video_details["last_updated_on"].'","'.addslashes($audio_video_details["title"]).'","'.addslashes($audio_video_details["url"]).'","'.addslashes($audio_video_details["summary_html"]).'","'.addslashes($audio_video_details["script"]).'","","'.addslashes($audio_video_details["audio_video_image_path"]).'","'.addslashes($audio_video_details["audio_video_image_title"]).'","'.addslashes($audio_video_details["audio_video_image_alt"]).'",'.$audio_video_details["hits"].',"'.addslashes($audio_video_details["tags"]).'",'.$audio_video_details["allow_comments"].',"'.addslashes($audio_video_details["agency_name"]).'","'.addslashes($audio_video_details["author_name"]).'","'.addslashes($audio_video_details["country_name"]).'","'.addslashes($audio_video_details["state_name"]).'","'.addslashes($audio_video_details["city_name"]).'",'.$audio_video_details["no_indexed"].','.$audio_video_details["no_follow"].',"'.addslashes($audio_video_details["canonical_url"]).'","'.addslashes($audio_video_details["meta_Title"]).'","'.addslashes($audio_video_details["meta_description"]).'","'.$audio_video_details['status'].'")');
		
		return TRUE;
		
	}
	
	/*
	*
	* Insert the live video details in video and short video details
	*
	* @access public
	* @param Array of video details
	* @return TRUE
	*
	*/
	
	public function insert_update_live_audio($audio_video_details) {
	
	$this->live_db->query('CALL add_update_audio('.$audio_video_details["content_id"].',NULL,'.$audio_video_details["section_id"].',"'.addslashes($audio_video_details["section_name"]).'",'.$audio_video_details["parent_section_id"].',"'.addslashes($audio_video_details["parent_section_name"]).'",'.$audio_video_details["grant_section_id"].',"'.addslashes($audio_video_details["grant_parent_section_name"]).'","'.$audio_video_details["publish_start_date"].'","'.$audio_video_details["last_updated_on"].'","'.addslashes($audio_video_details["title"]).'","'.addslashes($audio_video_details["url"]).'","'.addslashes($audio_video_details["summary_html"]).'","'.addslashes($audio_video_details["audio_path"]).'","'.addslashes($audio_video_details["audio_video_image_path"]).'","'.addslashes($audio_video_details["audio_video_image_title"]).'","'.addslashes($audio_video_details["audio_video_image_alt"]).'",'.$audio_video_details["hits"].',"'.addslashes($audio_video_details["tags"]).'",'.$audio_video_details["allow_comments"].',"'.addslashes($audio_video_details["agency_name"]).'","'.addslashes($audio_video_details["author_name"]).'","'.addslashes($audio_video_details["country_name"]).'","'.addslashes($audio_video_details["state_name"]).'","'.addslashes($audio_video_details["city_name"]).'",'.$audio_video_details["no_indexed"].','.$audio_video_details["no_follow"].',"'.addslashes($audio_video_details["canonical_url"]).'","'.addslashes($audio_video_details["meta_Title"]).'","'.addslashes($audio_video_details["meta_description"]).'","'.$audio_video_details['status'].'")');
		
		return TRUE;
		
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
	* Generate the article details from POST value
	*
	* @access public
	* @param POST values from article form
	* @return Set the article details in Array format
	*
	*/
	
	public function get_additional_audio_video_details()

	{	

		extract($_POST);
			
		if($txtContentType == 4) {	  
		
		$pattern = '/width="(\d+)"/i';
		$replacement = 'width="90%"';
		
		$txtScript = preg_replace($pattern, $replacement, $txtScript);
		
		$data['script']	 		= $txtScript;
		
		} else {
		$data['audio_path'] = $ExistingAudioSource;
		}
			
		if ($txtPublishStartDate != '')
		$data['PublishStartDate'] = date('Y-m-d H:i', strtotime($txtPublishStartDate));
	
		if(trim($txtUrlTitle) == '')
			$data['UrlTitle'] = addslashes(trim(strip_tags($txtAudioVideoHeadLine)));
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
		
		$audio_video_physical_name = stripslashes(RemoveSpecialCharacters($audio_video_physical_name));
	
		if($imgAudioVideoId != '')
		$data['imgAudioVideoId'] = $this->common_model->add_image_by_temp_id($audio_video_image_caption,$audio_video_image_alt,$audio_video_physical_name,$imgAudioVideoId);
		else 
		$data['imgAudioVideoId']  = 'NULL';
	
		$data['imgAudioVideoId'] = ($data['imgAudioVideoId'] == ''? "NULL":$data['imgAudioVideoId']);
		
		$MainSection = get_section_by_id($ddMainSection);
		
		$Year =  date('Y', strtotime($data['PublishStartDate']));
		$Month =  date('M', strtotime($data['PublishStartDate']));
		$Date =  date('d', strtotime($data['PublishStartDate']));
		
		$data['url']   = mb_strtolower(join( "-",( explode(" ",@$MainSection['URLSectionStructure'] ))))."/".$Year."/".mb_strtolower($Month)."/".$Date."/".$data['UrlTitle'];
		
		if(isset($_FILES['btnAudioSource']['tmp_name']) && $_FILES['btnAudioSource']['tmp_name'] != '' && isset($_FILES['btnAudioSource']['name']) && $_FILES['btnAudioSource']['name'] != '') {
			
			$oldget =  getcwd();
			
			$Year = date('Y');
			$Month = date('n');
			$Day =  date('j');
						
			create_image_folder_resource( $Year, $Month, $Day,audio_source_path);
			
			$Resource_Path = $Year."/".$Month."/".$Day;
			
			chdir(source_base_path.audio_source_path.$Resource_Path);
			
			$config = array(
				'upload_path' 		=> getcwd(),
				'allowed_types' 	=> "mp3|MP3|wav|WAV",
				'overwrite'			=> false
			);
			
			chdir($oldget);
			$this->upload->initialize($config);
			$result_data = array();
			if (!$this->upload->do_upload('btnAudioSource'))
			{
				$upload_data = array(
					'error' => $this->upload->display_errors()
				);
				
				$data['audio_path'] = '';
			}
			else
			{
				$upload_data = array(
					'upload_data' => $this->upload->data()
				);
				$data['audio_path'] = $Resource_Path."/".$upload_data['upload_data']['file_name'];
			}
			
		} 

		$data = array_map('trim',$data);
		
	# Start the Live Resource Table Details 
		
		$LiveAudioVideoDetails = array();
		
				
		# Author Image Empty Data
		/*
		$LiveAudioVideoDetails['author_image_path'] 						= '';
		$LiveAudioVideoDetails['author_image_title'] 						= '';
		$LiveAudioVideoDetails['author_image_alt'] 						= '';
		$LiveAudioVideoDetails['author_image_height'] 						= '';
		$LiveAudioVideoDetails['author_image_width'] 						= ''; */
		
		$LiveAudioVideoDetails['ecenic_id'] 								= 'NULL';
		$LiveAudioVideoDetails['section_id'] 								= 'NULL';
		$LiveAudioVideoDetails['section_name'] 							= '';
		$LiveAudioVideoDetails['parent_section_id'] 						= 'NULL';
		$LiveAudioVideoDetails['parent_section_name'] 						= '';	
		$LiveAudioVideoDetails['grant_section_id'] 						= 'NULL';
		$LiveAudioVideoDetails['grant_parent_section_name'] 				= '';
		
		# Resource Image Empty Data
		
		$LiveAudioVideoDetails['audio_video_image_path'] 					= '';
		$LiveAudioVideoDetails['audio_video_image_title'] 					= '';
		$LiveAudioVideoDetails['audio_video_image_alt'] 					= '';
	
		
		$LiveAudioVideoDetails['hits']										= 0;
		
		$LiveAudioVideoDetails['allow_comments']							= 0;
		$LiveAudioVideoDetails['no_indexed']								= 0;
		$LiveAudioVideoDetails['no_follow']									= 0;
		
		$LiveAudioVideoDetails['author_name']								= '';
		$LiveAudioVideoDetails['agency_name'] 								= '';
 		$LiveAudioVideoDetails['country_name'] 								= '';
		$LiveAudioVideoDetails['state_name'] 								= '';
		$LiveAudioVideoDetails['city_name'] 								= '';
		$LiveAudioVideoDetails['tags']										= '';
		$LiveAudioVideoDetails['status'] 									= $txtStatus;
	
		$LiveAudioVideoDetails['publish_start_date']						= $data['PublishStartDate'];
	
		if($data['ddByLine'] != "NULL" && $LiveAudioVideoDetails['author_name'] == '' ) {
				$LiveAudioVideoDetails['author_name'] 		= $txtByLine;		
		}	
	
		//if($txtStatus == 'P') {
			
			$MainSection = get_section_by_id($ddMainSection);
			
			if(isset($MainSection)) {
			
			$LiveAudioVideoDetails['section_id'] 			= $MainSection['Section_id'];
			$LiveAudioVideoDetails['section_name'] 		= $MainSection['Sectionname'];
			
				if(isset($MainSection['ParentSectionID']) && $MainSection['ParentSectionID'] != '') {
					
					$ParentMainSection = get_section_by_id($MainSection['ParentSectionID']);
					
					if(isset($ParentMainSection['Section_id'])) {
					$LiveAudioVideoDetails['parent_section_id'] 						= 	$ParentMainSection['Section_id'];
					$LiveAudioVideoDetails['parent_section_name'] 						= 	$ParentMainSection['Sectionname'];
					}
					
					if(isset($ParentMainSection['ParentSectionID']) && $ParentMainSection['ParentSectionID'] != '') {
					
						$GrantMainSection = get_section_by_id($ParentMainSection['ParentSectionID']);
						
						if(isset($GrantMainSection['Section_id'])) {
						$LiveAudioVideoDetails['grant_section_id'] 						= 	$GrantMainSection['Section_id'];
						$LiveAudioVideoDetails['grant_parent_section_name'] 				= 	$GrantMainSection['Sectionname'];
						}
					}
					
				}
			
			}
				
			$LiveAudioVideoDetails['last_updated_on'] 			= date('Y-m-d H:i');	
			$LiveAudioVideoDetails['title'] 						= $txtAudioVideoHeadLine;
			$LiveAudioVideoDetails['url'] 						= $data['url'];
			
			if($txtContentType == 4)
			$LiveAudioVideoDetails['script']				= $data['script'];
			else
			$LiveAudioVideoDetails['audio_path']			= $data['audio_path'];
			
			
			$LiveAudioVideoDetails['summary_html'] 				= $txtSummary;
			
			if($data['imgAudioVideoId'] != 'NULL') {
				
				$ResourceImageDetails = GetImageDetailsByContentId($data['imgAudioVideoId']);

				$LiveAudioVideoDetails['audio_video_image_path'] 					= $ResourceImageDetails['ImagePhysicalPath'];
				$LiveAudioVideoDetails['audio_video_image_title'] 					= $ResourceImageDetails['ImageCaption'];
				$LiveAudioVideoDetails['audio_video_image_alt'] 						= $ResourceImageDetails['ImageAlt'];
			}
			
			/*
			if(isset($column_id) && $column_id != '' && $column_id != 'NULL') {
				$ColumnDetails = column_editdetails($column_id);
				
				if(isset($ColumnDetails['column_name']))
					$LiveAudioVideoDetails['column_name'] = $ColumnDetails['column_name'];
			}
			*/
			
			if(isset($txtTags) != '')
			$LiveAudioVideoDetails['tags'] 					= implode(', ',@$txtTags);
			
			/*
			if($data['ddByLine'] != "NULL") {
				$LiveAudioVideoDetails['author_name'] 			= $txtByLine;
				
				$AuthorDetails 		= get_authordetails_by_id($data['ddByLine']);
				if($AuthorDetails['image_id'] != '' && $AuthorDetails['image_id'] != 'NULL'  && $AuthorDetails['image_id'] != 0) {
					$AuthorImageDetails = GetImageDetailsByContentId($AuthorDetails['image_id']);
	
					$LiveAudioVideoDetails['author_image_path'] 					= @addslashes($AuthorImageDetails['ImagePhysicalPath']);
					$LiveAudioVideoDetails['author_image_title'] 					= @addslashes($AuthorImageDetails['Title']);
					$LiveAudioVideoDetails['author_image_alt'] 						= @addslashes($AuthorImageDetails['ImageAlt']);
					$LiveAudioVideoDetails['author_image_height'] 					= @$AuthorImageDetails['Height'];
					$LiveAudioVideoDetails['author_image_width'] 					= @$AuthorImageDetails['Width']; 
				}
			}
			*/
			
			if($data['ddAgency'] != "NULL")
				$LiveAudioVideoDetails['agency_name'] 			= @get_agencyname_by_id($data['ddAgency']);
				
			if($data['ddCountry'] != "NULL")
				$LiveAudioVideoDetails['country_name'] 		= @get_countryname_by_id($data['ddCountry']);
				
			if($data['ddState'] != "NULL")	
				$LiveAudioVideoDetails['state_name'] 			= $txtState;
				
			if($data['ddCity'] != "NULL")	
				$LiveAudioVideoDetails['city_name'] 			= $txtCity;
			
			$LiveAudioVideoDetails['allow_comments'] 			=  $data['cbAllowComments'];
			$LiveAudioVideoDetails['no_indexed'] 				=  $data['cbNoIndex'];
			$LiveAudioVideoDetails['no_follow'] 				=  $data['cbNoFollows'];
			
			$LiveAudioVideoDetails['canonical_url']  			= $txtCanonicalUrl;
			$LiveAudioVideoDetails['meta_Title']  				= $txtMetaTitle;
			$LiveAudioVideoDetails['meta_description']  		= $txtMetaDescription;

		//}
		
		$data['LiveAudioVideoDetails'] = array_map('trim',$LiveAudioVideoDetails);
		 
		 
		# End the Live Resource Table Details 
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
	public function get_audio_video_datatables() {
			extract($_POST);
			
		$Search_text = trim($Search_text);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
			$CurrentYear = date('Y');

		if($Page_name == 'audio_manager') {
			$content_type = 5; 
			$menu_name		= "Audio";
			$TableName = "audio_".$CurrentYear;
		} else {
			$content_type = 4; 
			$menu_name		= "Video";
			$TableName = "video_".$CurrentYear;
		}
		 
		 $Menu_id = get_menu_details_by_menu_name($menu_name);

		
		switch ($Field) {
    case 1:
        $order_field = 'm.title';
		$archive_field 	= 'title';
        break;
    case 2:
        $order_field = 'm.Section_id';
		$archive_field 	= 'section_name';
        break;
	case 3:
       $order_field = 'um.Username';
	   $archive_field 	= 'created_by';
        break;
	case 4:
       $order_field = 'm.Modifiedon';
	   $archive_field 	= 'modified_on';
        break;
	case 5:
       $order_field = 'm.status';
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

		$article_manager =  $this->db->query('CALL audio_video_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'")')->result_array();	
		
		$recordsFiltered = $this->db->query('CALL audio_video_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT 0, 250 ","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'")')->num_rows();
		
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;

		foreach($article_manager as $article) {
			
			$article_image = '';
			
			if($content_type == 4)
				$edit_url = "edit_video/".urlencode(base64_encode($article['content_id']));
			else 
				$edit_url = "edit_audio/".urlencode(base64_encode($article['content_id']));

			$subdata = array();
			
			$subdata[] = $article['content_id'];
	
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($article['title']).'">'.shortDescription(strip_tags($article['title'])).'</p>';
			

			
			//$subdata[] = $article['URLSectionStructure'];
			$subdata[] =  GenerateBreadCrumbBySectionId($article['Section_id']);
			
			if($article['image_id'] != '' ) {
					if($article['ImagePhysicalPath'] != '') {
						$Image150X150 	= str_replace("original","w150X150", $article['ImagePhysicalPath']);
						$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
					} else {
						$subdata[] = '<td><i class="fa fa-picture-o"></i></td>';	
					}
				} else  {
				$subdata[] = '<td>-</td>';
				}	
			
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
					$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a>'. '';
				}
				else
					$set_status .= '';
			
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
				
					if($Page_name == 'audio_manager') {
						$TableName = "audio_".$CheckInYear;
					} else {
						$TableName = "video_".$CheckInYear;
					}	
				
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

							if($content_type == 4) {
								$edit_url = "edit_archive_video/".$CheckInYear."/".urlencode(base64_encode($article['content_id']));
								$image_path = @$article['video_image_path'];
							} else  {
								$edit_url = "edit_archive_audio/".$CheckInYear."/".urlencode(base64_encode($article['content_id']));
								$image_path = @$article['audio_image_path'];
							}
										

							$subdata = array();
							
							$Style = "";
							
							if($article['status'] == 'P' && strtotime($article['publish_start_date']) > strtotime(date('d-m-Y H:i:s'))) 
							$Style = "style='color:red'";
							$subdata[] = $article['content_id'];
							$subdata[] ='<p class="tooltip_cursor" '.$Style.' title="'.strip_tags($article['title']).'">'.shortDescription(strip_tags($article['title'])).'</p>';

							$subdata[] =  (GetBreadCrumbByURL($article['url']));
							
						
									if($image_path != '') {
										$Image150X150 	= str_replace("original","w150X150", $image_path);
										$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
									} else {
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
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		$content_type = $article_Type;
		
		switch ($Field)
		{
		case 0:
			$order_field = 'm.title';
			break;

		case 1:
			$order_field = 's.Sectionname';
			break;

		case 2:
			$order_field = 'm.Modifiedon';
			break;

		default:
			$order_field = 'm.content_id';
		}
		if ($content_id != '')
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
		
		if ($check_in != '')
		{
			$check_in_date = new DateTime($check_in);
			$check_in = $check_in_date->format('Y-m-d');
		}
		if ($check_out != '')
		{
			$check_out_date = new DateTime($check_out);
			$check_out = $check_out_date->format('Y-m-d');
		}
		$article_manager = $this->db->query('CALL get_related_content_datatable ("' . $content_where_condition . 'ORDER BY ' . $order_field . ' ' . $order . ' LIMIT ' . $start . ', ' . $length . '","' . $check_in . '","' . $check_out . '","' . $Search_value . '","' . $Search_by . '","' . $Section . '","' . $Status . '","' . $content_type . '")')->result_array();
		
		$recordsFiltered = $this->db->query('CALL get_related_content_datatable ("' . $content_where_condition . 'ORDER BY ' . $order_field . ' ' . $order . ' LIMIT 0, 100","' . $check_in . '","' . $check_out . '","' . $Search_value . '","' . $Search_by . '","' . $Section . '","' . $Status . '","' . $content_type . '")')->num_rows();
		
		$data['draw'] 				= $draw;
		$data["recordsTotal"] 		= $Total_rows;
		$data["recordsFiltered"] 	= $recordsFiltered;
		
		$data['data'] 				= array();
		$Count 						= 0;
	
		foreach($article_manager as $article)
		{
			$article['content_type_id'] = 1;
			 switch($article['content_type_id']) {
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
			 default: 
			 $edit_url = "";
			}
			
			$subdata = array();
			
			$subdata[] = '<div align="center"><p title="' . strip_tags($article['title']) . '" ><a href="'.base_url().folder_name."/".$edit_url.'" >' . shortDescription(strip_tags($article['title'])) . '</a></p></div>';
			$subdata[] = $article['Sectionname'];
			$subdata[] = $article['Modifiedon'];
			$subdata[] = '<a href="javascript:void(0);" long_title ="'.trim(strip_tags($article['title'])) . '" short_title="'.trim(shortDescription(strip_tags($article['title']))) . '" value="' . $article['content_id'] . '" rel="1" data-toggle="tooltip" href="javascript:void()" class="button tick"  title="Add" data-original-title="Add" id="internal_action" ><i class="fa fa-plus"></i></a>';
			
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		if ($recordsFiltered == 0)
		{
		}
		echo json_encode($data);
		exit;
	}
	
	/*
	*
	* Insert the video section mapping data
	*
	* @access Public
	* @param  content_id from video master table
	* @return TRUE;
	*/
	
	public function insert_content_mapping($content_id,$content_type)

	{
		extract($_POST);
		
		if (isset($content_id) && $content_id != '')
		{
			$insert_array 	= array();
			$insert_array[] = $content_id;
			$insert_array[] = $ddMainSection;
			
			$result = implode('","', $insert_array);
			
			$article_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",'.$content_type.')');
			$article_mapping->result();
			
			if($this->input->post('txtStatus') == 'P') {
			$result = implode('","', $insert_array);
			$article_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",'.$content_type.')'); 
			}
			
			if (isset($cbSectionMapping))
			{
				
				$cbSectionMapping = array_diff($cbSectionMapping, array($ddMainSection));
				
				foreach($cbSectionMapping as $mapping)
				{
					$insert_array 	= array();
					$insert_array[] = $content_id;
					$insert_array[] = $mapping;
					
					$result = implode('","', $insert_array);
					
					$article_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",'.$content_type.')');
					
					if($this->input->post('txtStatus') == 'P') {
						
						$live_insert_array 		= array();
						
						$live_insert_array[] 	= $content_id;
						$live_insert_array[] 	= $mapping;

						$result = implode('","', $live_insert_array);
						$article_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",'.$content_type.')');
						
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
	public function delete_content_mapping($content_id,$content_type)

	{
		
		if($this->input->post('txtStatus') == 'P' || $this->input->post('txtStatus') == 'U')	
		$query = $this->live_db->query("CALL delete_section_mapping (". $content_id.",".$content_type.")");
		
		$content_mapping = $this->db->query('CALL delete_content_mapping (' . $content_id . ','.$content_type.')');
		return $content_mapping;
	}	
	
}
/* End of file article_model.php */
/* Location: ./application/models/admin/article_model.php */