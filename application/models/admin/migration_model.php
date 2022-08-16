<?php 
/**
 * Migration Model Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

class Migration_model extends CI_Model
{	

	public function __construct()

	{
		parent::__construct();
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		$this->live_db = $CI->load->database('live_db', TRUE);
		
	}

	public function total_import_ecenic_content($Type) {
			
		$this->migration_db->select("ecenic_content_id");
		$this->migration_db->from("ecenic_content");
		$this->migration_db->where("content_type",$Type);
		$get_result = $this->migration_db->get();
		return $get_result->num_rows();
		
	}
	
		public function check_ecenic_content($content_id, $type) {

		$this->db->select("ecenic_content_id");
		$this->db->from("ecenic_content");
		$this->db->where("ecenic_id",$content_id);
		$this->migration_db->where("content_type",$type);
		$get_result = $this->db->get();
		return $get_result->num_rows();
	
	}
	
	public function insert_ecenic_content($ecenic_content_id,$ecenice_content_type ,$content_id ,$status,$ecenic_url,$section_id,$Created_by) {
		
		$InsertArray = array();
		$InsertArray['ecenic_id'] = $ecenic_content_id;
		$InsertArray['content_id'] = $content_id;
		$InsertArray['status'] = $status;
		$InsertArray['content_type'] = $ecenice_content_type;
		$InsertArray['ecenic_url'] = $ecenic_url;
		$InsertArray['section_id'] = $section_id;
		$InsertArray['created_by'] = "{$Created_by}";
		
		$this->db->insert('ecenic_content',$InsertArray);
		
	}
	
	public function get_ecenic_content($Status, $Type) {
		
		$this->migration_db->select("ecenic_content_id");
		$this->migration_db->from("ecenic_content");
		$this->migration_db->where("content_type",$Type);
		$this->migration_db->where("status",$Status);
		$get_result = $this->migration_db->get();
		return $get_result->num_rows();
		
	}
	
	public function check_sectionname($SectionID) 
	{
	$result = $this->db->query("SELECT * FROM (`sectionmaster`) WHERE  `ecenic_section_id` = '".$SectionID."'");
		return $result;
	}
	
	public function get_city_statedetails($CityName) {
		
		$CityName = str_replace("'",'"',$CityName);
		$this->migration_db->reconnect();
		$result = $this->migration_db->query("SELECT * FROM (`citymaster` as cm) JOIN statemaster as sm ON cm.State_Id = sm.State_Id  WHERE  cm.`CityName`  LIKE '".$CityName."'");
		return $result;
	}
	
	public function get_content_by_ecenic_id ($Ecenic_id,$content_type) 
	{
	
			$result = $this->db->query("SELECT * FROM ecenic_content WHERE ecenic_id='".$Ecenic_id."' AND content_type ='".$content_type."'");
			return $result;
			 
	}
		
	
	// Starting Agency Code
	
	public function check_agencyname($AgencyName) 
	{
		$AgencyName = str_replace("'",'"',$AgencyName);
	
	$result = $this->db->query("SELECT * FROM (`newsagencymaster`) WHERE  `Agency_name`  LIKE '".$AgencyName."'");
		return $result;
	}
	
	public function insert_agencydetails($AgencyName, $CreatedOn, $UserId) 
	{
			$AgencyName = str_replace("'",'"',$AgencyName);
	
		$user_insert_pro = $this->db->query("CALL add_agency('".$AgencyName."',1,'".$CreatedOn."',".$UserId.",'".$CreatedOn."',".$UserId.",@insert_id)"); 
		
		$slct_lst_id=$this->db->query("SELECT @insert_id");	
		$last_id = $slct_lst_id->row_array();
		return $user_id = $last_id['@insert_id'];
	}	
	
	// Ending Agency Code
	
	// Starting Agency Code
	
	public function check_authorname($AuthorName) 
	{
		$AuthorName = str_replace("'",'"',$AuthorName);
	
	$result = $this->db->query("SELECT * FROM (`authormaster`) WHERE  `AuthorName`  LIKE '".$AuthorName."'");
		return $result;
	}
	
	public function insert_authordetails($AuthorName, $CreatedOn, $UserId) 
	{
			$AuthorName = str_replace("'",'"',$AuthorName);
	
		$user_insert_pro = $this->db->query("CALL addauthordetails(1,'".$AuthorName."','',1,'','".$UserId."','".$CreatedOn."','".$UserId."','".$CreatedOn."',NULL,NULL,'','',@last_insert_id)"); 
		/*
		$last_id = get_author_by_name($AuthorName);
		return $user_id = $last_id['Author_id'];*/
		
		$insert_id_result = $this->db->query("SELECT @last_insert_id")->result_array();
		return $user_id = $insert_id_result[0]['@last_insert_id'];
		
	}	
	
	// Ending Agency Code
	
	// Starting User Code
	
	public function check_username($UserName)
	{
		$UserName = str_replace("'",'"',$UserName);
		$dpt_name = $this->db->query("CALL check_username('".$UserName."','')"); 
		return $dpt_name;
	}
	
		public function insert_userdetails($UserName, $CreatedOn) 
	{
		$Null_value = "NULL";
		$password = hash('sha512', $UserName);
	
		$user_insert_pro = $this->db->query("CALL userdetails_insert('".$UserName."','".$password."','','',".$Null_value.",'','',1,'".USERID."','".$CreatedOn."','',@insert_id)");
		
		if($user_insert_pro == TRUE) {
			
					
					$slct_lst_id=$this->db->query("SELECT @insert_id");	
					$last_id = $slct_lst_id->row_array();
					$user_id = $last_id['@insert_id'];
					
					$this->db->query("CALL  useraccess_rights_insert('".$user_id."', '4',1,1,1,1,1,1, '".USERID."','".$CreatedOn."','".USERID."','".$CreatedOn."')");
					
					$fpm_insert_pro = $this->db->query("CALL fpmuserrights_insert('".$user_id."',1,1,1,'".USERID."','".$CreatedOn."', 1,1)");	
					
					return $user_id;
			
				}
	}
	
	// Ending User Code
	
	public function insert_imagemaster($Image, $Ecenic_Id, $ModifiedDate) {
		
			$ImagePath 			= urldecode($Image['href']);
		
			$ImagePath_array 	= explode("/",$ImagePath);
			
				$ImageTitle = urldecode($Image['title']);
			
			if(count($ImagePath_array) >=5) {
				
				$year = $ImagePath_array[0];
				$month = $ImagePath_array[1];
				$date = $ImagePath_array[2];
				$time = $ImagePath_array[3];
				
				$data['binary_file']	 = '';
				
				$OrginalImagePath	= $this->ecenic_image_path.$ImagePath;
				$DestinationURL 	= destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/original/";  
				
				$data['image_name'] =  $year."/".$month."/".$date."/".$time."/original/".$ImageTitle;
				
			} else {
				
				$year = $ImagePath_array[0];
				$month = $ImagePath_array[1];
				$date = $ImagePath_array[2];
	
				$data['binary_file']	 = '';
				
				$OrginalImagePath	= $this->ecenic_image_path.$ImagePath;
				$DestinationURL 	= destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/original/";  
				
				$data['image_name'] =  $year."/".$month."/".$date."/original/".$ImageTitle;
				
			}
			
			if(copy($OrginalImagePath , $DestinationURL. $ImageTitle)) {
			
			$src = $DestinationURL. $ImageTitle;
			
			 $info = getimagesize($OrginalImagePath);
	
			 if (!empty($src))
					 {
					  switch ($info['mime']) //check image type
					  {
					  case 'image/gif':
					   $src_img = imagecreatefromgif($src);
					   break;
				
					  case 'image/jpg':
					   $src_img = imagecreatefromjpeg($src);
					   break;
					   
					  case 'image/jpeg':
					   $src_img = imagecreatefromjpeg($src);
					  break;
				
					  case 'image/png':
					   $src_img = imagecreatefrompng($src);
					   break;
					  }
					  
					 
					 if (!$src_img)
					 {
					   $result_value['status'] = 'error';
					   $result_value['msg']  = "Failed to read the image file";
					   return '';
					 }
					  
					 $size   = getimagesize($src);
					 $src_w  = $size[0];
					 $src_h  = $size[1]; 
					  
					 //resize image to 600*390 size
					 $dst_w   = 600;
					 $dst_h   = 390;
					  
					 $dst_img   = imagecreatetruecolor($dst_w, $dst_h);
					 $dst   = str_replace("original","w600X390",$src);
					 
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
					 
						$result 	= imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
					 
					// $result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w,$src_h);
				 
					   if($result) {
						if(imagejpeg($dst_img, $dst)) {
								$ImageDetails 				= getimagesize($dst);
								$imagetype 					= $ImageDetails['mime'];
								//$data['image_binary_file1']	 = 'data:' . $imagetype . ';base64,' . base64_encode(file_get_contents($dst));
								$data['image_binary_file1']	 = '';
								$image_binary_bool1 = true;
								$image1_type   = 1;
								imagedestroy($dst_img); 
						}
					   }
					   
									   
				 
						 //resize image to 600*300 size
						$dst_w 		= 600;
						$dst_h 		= 300;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						 $dst   = str_replace("original","w600X300",$src);
					
					
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
						
					//	$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$result 	= imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
						
						// Start the fit crop 
					
						//$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
						
							if($result) {
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$imagetype 					= $ImageDetails['mime'];
									//$data['image_binary_file2']	 = 'data:' . $imagetype . ';base64,' . base64_encode(file_get_contents($dst));
									$data['image_binary_file2']	 = '';
									$image_binary_bool2 = true;
									$image2_type 		= 1;
									imagedestroy($dst_img);	
								}
							} 

						 //resize image to 150*150 size
						$dst_w 		= 150;
						$dst_h 		= 150;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						 $dst   = str_replace("original","w150X150",$src);
					
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
						
						//$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$result 	= imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
						
						// Start the fit crop 
					
						//$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
							if($result) {
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$imagetype 					= $ImageDetails['mime'];
									// $data['image_binary_file3']	 = 'data:' . $imagetype . ';base64,' . base64_encode(file_get_contents($dst));
									$data['image_binary_file3']  = '';
									$image_binary_bool3 = true;
									$image3_type 		= 1;
									imagedestroy($dst_img);	
								}
							}

						//resize image to 100*65 size
						$dst_w 		= 100;
						$dst_h 		= 65;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst   = str_replace("original","w100X65",$src);
						
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
						
						//$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$result 	= imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height); 
						
						// Start the fit crop 
						
					
						//$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$imagetype 					= $ImageDetails['mime'];
									// $data['image_binary_file4']	 = 'data:' . $imagetype . ';base64,' . base64_encode(file_get_contents($dst));
									$data['image_binary_file4']	 = '';
									$image_binary_bool4 = true;
									$image4_type 		= 1;
									imagedestroy($dst_img);	
								}
							}
						
					
								$caption_array   = explode('.', $ImageTitle);
								$caption    = @$caption_array[0];
								
								$NULL = "NULL";
								
								$insert_array 	= array();
								$insert_array[] = addslashes(str_replace("'",'"',$caption));	
								$insert_array[] = addslashes(str_replace("'",'"',$caption));	
								$insert_array[] = $data['image_name'];
								$insert_array[] = 1;
								$insert_array[] = 0;
								$insert_array[] = 0;
								$insert_array[] = 0;
								$insert_array[] = 1;
								/*$insert_array[] = NULL;
								$insert_array[] = $ModifiedDate;
								$insert_array[] = NULL;
								$insert_array[] = $ModifiedDate;*/
								
								
								
								$result 			= implode('","', $insert_array);
								$image_gallery 		= $this->db->query('CALL add_image_master('.$Ecenic_Id.',"' . $result . '",'.$NULL.',"'.$ModifiedDate.'",'.$NULL.',"'.$ModifiedDate.'",@insert_id)');
								$result 			= $this->db->query("SELECT @insert_id")->row_array();
							
						return $image_library_id 	= $result['@insert_id'];
							
					 
					  }
					  
					 } else {
						 return '';
					 }
			
		
	}

	
	// Start Article Insert Code
	
	public function insert_article_master($article_details) {
	
			$this->db->trans_begin();
			//$this->archive_db->trans_begin();
			$this->live_db->trans_begin();
			
			
			$Year =  date('Y', strtotime((string)$article_details['publish_start_date']));
			
			if($Year == 2009 || $Year == 2010 || $Year == 2011 || $Year == 2012 || $Year == 2013 || $Year == 2014 || $Year == 2015  || $Year == 2016) {
			
			$ArticleMaster     = $article_details;
			$ArticleRelated 	= $article_details['ArticleRelated'];
			$ArticleLive 		= $article_details['LiveArticleDetails'];
			$related_article_id     =  $article_details['RelatedArticle'];
			
			$this->db->query('CALL add_articlemaster('.$article_details['ecenic_id'].',"'.addslashes($article_details['url_title']).'","'.addslashes($article_details['title']).'","'.addslashes($article_details['url']).'","'.addslashes($article_details['summaryHTML']).'","'.addslashes($article_details['ArticlePageContentHTML']).'","'.$article_details['publish_start_date'].'","'.$article_details['publish_end_date'].'",'.$article_details['scheduled_article'].',"'.$article_details['Tags'].'","'.$article_details['MetaTitle'].'","'.$article_details['MetaDescription'].'","'.$article_details['Noindexed'].'","'.$article_details['Nofollow'].'","'.$article_details['Canonicalurl'].'","'.$article_details['Allowcomments'].'",1,"'.$article_details['section_promotion'].'","'.$article_details['status'].'","'.$article_details['Createdby'].'","'.$article_details['Createdon'].'","'.$article_details['Modifiedby'].'","'. $article_details['Modifiedon'].'","",@insert_id)');
	
			$status  = $article_details['status'];
	
			$result 	= $this->db->query("SELECT @insert_id")->result_array();
			$article_id = $result[0]['@insert_id'];

			if($article_id != '' && $article_id != 'NULL') {
				
				$Update_url = $article_details['url']."-".$article_id.".html";
				
				$this->db->query('CALL update_url_structure('.$article_id.',"'.addslashes($Update_url).'",1)');
			
				$article_details = $ArticleRelated;
				$Section = $article_details['Section_id'];
			
				$this->db->query('CALL add_articlerelateddate('.$article_id.','.$article_details['Section_id'].','.$article_details['Agency_ID'].','.$article_details['Author_ID'].','.$article_details['Country_ID'].','.$article_details['State_ID'].','.$article_details['City_ID'].','.$article_details['homepageimageid'] .','.$article_details['Sectionpageimageid'] .','.$article_details['articlepageimageid'] .')');
				
				$insert_array 	= array();
				$insert_array[] = $article_id;
				$insert_array[] = $Section;
				
				$result = implode('","', $insert_array);
				
				$article_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",1)');
				
				if ($status == 'P') {
					$ArticleLive['url'] 			= $Update_url;
					$ArticleLive['content_id'] 	= $article_id;
					
					$article_details = $ArticleLive;
					
					//$this->archive_db->insert("article_".$Year,$article_details);
					$this->live_db->insert("article",$article_details);
		
					$short_content_array = array(
					"content_id" => $article_details["content_id"],
					"title"      => addslashes(strip_tags($article_details["title"])),
					"tags"		=> addslashes($article_details["tags"]),
					"summary" => addslashes($article_details["summary_html"]),
					"bodytext" => addslashes(strip_tags($article_details['article_page_content_html'])),
					"section_id" => $article_details["section_id"]
					);
					
					//$this->archive_db->insert("short_article_details_".$Year,$short_content_array);
					$this->live_db->insert("short_article_details",$short_content_array);
					
					$insert_array 	= array(
					"content_id" => $article_id,
					"section_id" => $Section
					);
					
					//$this->archive_db->insert("article_section_mapping_".$Year,$insert_array);
					$this->live_db->insert("article_section_mapping",$insert_array);
				
				}

				if(!empty($related_article_id)) {
				
				if($article_details['status'] == 'P') {
					
					foreach($related_article_id as $key=>$related) {

						$related_article_details =   $this->db->query('CALL get_article_by_id(' . $related . ')')->result_array();
			
						 $related_article_array = array(
						 "content_id" => $article_details["content_id"] ,
						 "contenttype" => 1,
						 "related_content_id" => $related,
						 "related_articletitle" => addslashes(@$related_article_details[0]['title']),
						 "related_articleurl" => addslashes(@$related_article_details[0]['url']),
						 "display_order" => $key+1 
						 );
						 
						//$this->archive_db->insert("relatedcontent_".$Year,$related_article_array);
						$this->live_db->insert("relatedcontent",$related_article_array);
					
					}
				}
			}
					
				//	if ($this->db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE) {
						if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						//$this->archive_db->trans_rollback();
						$this->live_db->trans_rollback();
						$Insert_array = array(
						"content_id" => "NULL",
						"url"		=> ""
						);
						return $Insert_array;
					} else {
						$this->db->trans_commit();
						//$this->archive_db->trans_commit();
						$this->live_db->trans_commit();
						$Insert_array = array(
						"content_id" => $article_id,
						"url"		=> $Update_url
						);
						/*
						$this->db->where("content_id",$article_id);
						$this->db->delete("articlerelateddata");
						
						$this->db->where("content_id",$article_id);
						$this->db->delete("articlemaster");
						*/
						return $Insert_array;
					}
			}
			
			} else {
				RETURN "NULL";
			}
	}
	
		// Start Article Insert Code
	
	public function insert_article_master_archive($article_details) {
	
			$this->db->trans_begin();
			$this->archive_db->trans_begin();
			//$this->live_db->trans_begin();
			
			
			$Year =  date('Y', strtotime((string)$article_details['publish_start_date']));
			
			if($Year == 2009 || $Year == 2010 || $Year == 2011 || $Year == 2012 || $Year == 2013 || $Year == 2014 || $Year == 2015  || $Year == 2016) {
			
			$ArticleMaster     = $article_details;
			$ArticleRelated 	= $article_details['ArticleRelated'];
			$ArticleLive 		= $article_details['LiveArticleDetails'];
			$related_article_id     =  $article_details['RelatedArticle'];
			
			$this->db->query('CALL archive_add_articlemaster('.$article_details['ecenic_id'].',"'.addslashes($article_details['url_title']).'","'.addslashes($article_details['title']).'","'.addslashes($article_details['url']).'","'.addslashes($article_details['summaryHTML']).'","'.addslashes($article_details['ArticlePageContentHTML']).'","'.$article_details['publish_start_date'].'","'.$article_details['publish_end_date'].'",'.$article_details['scheduled_article'].',"'.$article_details['Tags'].'","'.$article_details['MetaTitle'].'","'.$article_details['MetaDescription'].'","'.$article_details['Noindexed'].'","'.$article_details['Nofollow'].'","'.$article_details['Canonicalurl'].'","'.$article_details['Allowcomments'].'",1,"'.$article_details['section_promotion'].'","'.$article_details['status'].'","'.$article_details['Createdby'].'","'.$article_details['Createdon'].'","'.$article_details['Modifiedby'].'","'. $article_details['Modifiedon'].'","",@insert_id)');
	
			$status  = $article_details['status'];
	
			$result 	= $this->db->query("SELECT @insert_id")->result_array();
			$article_id = $result[0]['@insert_id'];

			if($article_id != '' && $article_id != 'NULL') {
				
				$Update_url = $article_details['url']."-".$article_id.".html";
				
				$this->db->query('CALL archive_update_url_structure('.$article_id.',"'.addslashes($Update_url).'",1)');
			
				$article_details = $ArticleRelated;
				$Section = $article_details['Section_id'];
			
				$this->db->query('CALL archive_add_articlerelateddate('.$article_id.','.$article_details['Section_id'].','.$article_details['Agency_ID'].','.$article_details['Author_ID'].','.$article_details['Country_ID'].','.$article_details['State_ID'].','.$article_details['City_ID'].','.$article_details['homepageimageid'] .','.$article_details['Sectionpageimageid'] .','.$article_details['articlepageimageid'] .')');
				
				/*
				$insert_array 	= array();
				$insert_array[] = $article_id;
				$insert_array[] = $Section;
				
				$result = implode('","', $insert_array);
				
				$article_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",1)');
				*/
				
				if ($status == 'P') {
					$ArticleLive['url'] 			= $Update_url;
					$ArticleLive['content_id'] 	= $article_id;
					
					$article_details = $ArticleLive;
					
					$this->archive_db->insert("article_".$Year,$article_details);
					//$this->live_db->insert("article",$article_details);
		
					$short_content_array = array(
					"content_id" => $article_details["content_id"],
					"title"      => addslashes(strip_tags($article_details["title"])),
					"tags"		=> addslashes($article_details["tags"]),
					"summary" => addslashes($article_details["summary_html"]),
					"bodytext" => addslashes(strip_tags($article_details['article_page_content_html'])),
					"section_id" => $article_details["section_id"]
					);
					
					$this->archive_db->insert("short_article_details_".$Year,$short_content_array);
					//$this->live_db->insert("short_article_details",$short_content_array);
					
					$insert_array 	= array(
					"content_id" => $article_id,
					"section_id" => $Section
					);
					
					$this->archive_db->insert("article_section_mapping_".$Year,$insert_array);
					//$this->live_db->insert("article_section_mapping",$insert_array);
				
				}

				if(!empty($related_article_id)) {
				
				if($article_details['status'] == 'P') {
					
					foreach($related_article_id as $key=>$related) {

						$related_article_details =   $this->db->query('CALL get_article_by_id(' . $related . ')')->result_array();
			
						 $related_article_array = array(
						 "content_id" => $article_details["content_id"] ,
						 "contenttype" => 1,
						 "related_content_id" => $related,
						 "related_articletitle" => addslashes(@$related_article_details[0]['title']),
						 "related_articleurl" => addslashes(@$related_article_details[0]['url']),
						 "display_order" => $key+1 
						 );
						 
						$this->archive_db->insert("relatedcontent_".$Year,$related_article_array);
						//$this->live_db->insert("relatedcontent",$related_article_array);
					
					}
				}
			}
					
					if ($this->db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE) {
						//if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$this->archive_db->trans_rollback();
						//$this->live_db->trans_rollback();
						$Insert_array = array(
						"content_id" => "NULL",
						"url"		=> ""
						);
						return $Insert_array;
					} else {
						$this->db->trans_commit();
						$this->archive_db->trans_commit();
						//$this->live_db->trans_commit();
						$Insert_array = array(
						"content_id" => $article_id,
						"url"		=> $Update_url
						);
						
						/*
						$this->db->where("content_id",$article_id);
						$this->db->delete("articlerelateddata");
						
						$this->db->where("content_id",$article_id);
						$this->db->delete("articlemaster");
						*/
						
						return $Insert_array;
					}
			}
			
			} else {
				RETURN "NULL";
			}
	}
	// End Article Insert Code

	
	// Start Gallery Insert Code
	
	public function insert_gallery_master($gallery_details) {
	
			$this->db->trans_begin();
			//$this->archive_db->trans_begin();
			$this->live_db->trans_begin();
			
			$Year =  date('Y', strtotime((string)$gallery_details['publish_start_date']));
			
			if($Year == 2009 || $Year == 2010 || $Year == 2011 || $Year == 2012 || $Year == 2013 || $Year == 2014 || $Year == 2015  || $Year == 2016) {
			
			$GalleryMaster     = $gallery_details;
			$GalleryRelated 	= $gallery_details['GalleryRelatedImages'];
			$GalleryLive 		= $gallery_details['LiveGalleryDetails'];
			
			$this->db->query('CALL add_gallerymaster('.$gallery_details['ecenic_id'].',"'.addslashes($gallery_details['url_title']).'","'.addslashes($gallery_details['title']).'","'.addslashes($gallery_details['url']).'","'.addslashes($gallery_details['summaryHTML']).'","'.$gallery_details['Tags'].'","'.$gallery_details['MetaTitle'].'","'.$gallery_details['MetaDescription'].'","'.$gallery_details['publish_start_date'].'","'.$gallery_details['Noindexed'].'","'.$gallery_details['Nofollow'].'","'.$gallery_details['Canonicalurl'].'","'.$gallery_details['Allowcomments'].'",'.$gallery_details['Section_id'].','.$gallery_details['Author_ID'].','.$gallery_details['Agency_ID'].','.$gallery_details['Country_ID'].','.$gallery_details['State_ID'].','.$gallery_details['City_ID'].',"'.$gallery_details['status'].'","'.$gallery_details['Createdby'].'","'.$gallery_details['Createdon'].'","'.$gallery_details['Modifiedby'].'","'. $gallery_details['Modifiedon'].'",@insert_id)');
	
			$status  = $gallery_details['status'];
	
			$result 	= $this->db->query("SELECT @insert_id")->result_array();
			$gallery_id = $result[0]['@insert_id'];

			if($gallery_id != '' && $gallery_id != 'NULL') {
				
				$Update_url = $gallery_details['url']."-".$gallery_id.".html";
				
				$this->db->query('CALL update_url_structure('.$gallery_id.',"'.addslashes($Update_url).'",3)');
				
				$insert_array 	= array();
				$insert_array[] = $gallery_id;
				$insert_array[] = $gallery_details['Section_id'];
				
				$result = implode('","', $insert_array);
				
				$gallery_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",3)');
				
				foreach($GalleryRelated as $key=>$image_id) {
				
				$this->db->query('CALL add_gallery_related_images ("' . $gallery_id . '","' .$image_id . '","'.($key+1).'")');
				
				}
				
				$GalleryLive['url'] 		= $Update_url;
				$GalleryLive['content_id'] 	= $gallery_id;
				$Section 					= $gallery_details['Section_id'];
				
				$gallery_details = $GalleryLive;
				
				$ImageDetails = $this->db->query('CALL get_gallery_images(' . $gallery_id . ')')->result_array();
				
					if(isset($ImageDetails)) {
					foreach($ImageDetails as $key=>$Image) {
					
				if($key == 0) {
					$gallery_details['first_image_path']  	= $Image['ImagePhysicalPath'];
					$gallery_details['first_image_title']  	= $Image['ImageCaption'];
					$gallery_details['first_image_alt'] 	= $Image['ImageAlt'];
					
						//$this->archive_db->insert("gallery_".$Year,$gallery_details);
						$this->live_db->insert("gallery",$gallery_details);
						
						$short_content_array = array(
						"content_id" 	=> $gallery_details["content_id"],
						"title"      	=> addslashes(strip_tags($gallery_details["title"])),
						"tags"			=> addslashes($gallery_details["tags"]),
						"summary" 		=> addslashes($gallery_details["summary_html"]),
						"bodytext" 		=> "",
						"section_id" 	=> $gallery_details["section_id"]
						);
						
						//$this->archive_db->insert("short_gallery_details_".$Year,$short_content_array);
						$this->live_db->insert("short_gallery_details",$short_content_array);
					
					
				}
				
						$GalleryRelatedImages =  array(
						"content_id" 					=> $gallery_id,
						//"image_id"					=> $Image['content_id'],
						"gallery_image_path" 			=> $Image['ImagePhysicalPath'],
						"gallery_image_title" 			=> $Image['ImageCaption'],
						"gallery_image_alt" 			=> $Image['ImageAlt'],
						"display_order"					=> $Image['display_order']
						);
					
					//$this->archive_db->insert("gallery_related_images_".$Year,$GalleryRelatedImages);
					$this->live_db->insert("gallery_related_images",$GalleryRelatedImages);
					
					}
				
				}
				
				/*
				$insert_array 	= array();
				$insert_array[] = $gallery_id;
				$insert_array[] = $Section;
				
				$result = implode('","', $insert_array);
				
				$result = implode('","', $insert_array);
				$article_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",3)');
				*/
				
				//	if ($this->db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE) {
					if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						//$this->archive_db->trans_rollback();
						$this->live_db->trans_rollback();
						echo $this->db->_error_message(); 
						exit;
						return $Insert_array = array(
						"content_id" => "NULL",
						"url"		=> ""
						);
					} else {
						$this->db->trans_commit();
						//$this->archive_db->trans_commit();
						$this->live_db->trans_commit();
						return $Insert_array = array(
						"content_id" => $gallery_id,
						"url"		=> $Update_url
						);
						
						/*
						$this->db->where("content_id",$gallery_id);
						$this->db->delete("galleryrelatedimages");		
						
						$this->db->where("content_id",$gallery_id);
						$this->db->delete("gallerymaster");
						*/
					}
			}
		}
	}
	
	// Start Gallery Insert Code
	
	
	// Start Gallery Insert Code
	
	public function insert_gallery_master_archive($gallery_details) {
	
			$this->db->trans_begin();
			$this->archive_db->trans_begin();
			//$this->live_db->trans_begin();
			
			$Year =  date('Y', strtotime((string)$gallery_details['publish_start_date']));
			
			if($Year == 2009 || $Year == 2010 || $Year == 2011 || $Year == 2012 || $Year == 2013 || $Year == 2014 || $Year == 2015  || $Year == 2016) {
			
			$GalleryMaster     = $gallery_details;
			$GalleryRelated 	= $gallery_details['GalleryRelatedImages'];
			$GalleryLive 		= $gallery_details['LiveGalleryDetails'];
			
			$this->db->query('CALL archive_add_gallerymaster('.$gallery_details['ecenic_id'].',"'.addslashes($gallery_details['url_title']).'","'.addslashes($gallery_details['title']).'","'.addslashes($gallery_details['url']).'","'.addslashes($gallery_details['summaryHTML']).'","'.$gallery_details['Tags'].'","'.$gallery_details['MetaTitle'].'","'.$gallery_details['MetaDescription'].'","'.$gallery_details['publish_start_date'].'","'.$gallery_details['Noindexed'].'","'.$gallery_details['Nofollow'].'","'.$gallery_details['Canonicalurl'].'","'.$gallery_details['Allowcomments'].'",'.$gallery_details['Section_id'].','.$gallery_details['Author_ID'].','.$gallery_details['Agency_ID'].','.$gallery_details['Country_ID'].','.$gallery_details['State_ID'].','.$gallery_details['City_ID'].',"'.$gallery_details['status'].'","'.$gallery_details['Createdby'].'","'.$gallery_details['Createdon'].'","'.$gallery_details['Modifiedby'].'","'. $gallery_details['Modifiedon'].'",@insert_id)');
	
			$status  = $gallery_details['status'];
	
			$result 	= $this->db->query("SELECT @insert_id")->result_array();
			$gallery_id = $result[0]['@insert_id'];

			if($gallery_id != '' && $gallery_id != 'NULL') {
				
				$Update_url = $gallery_details['url']."-".$gallery_id.".html";
				
				$this->db->query('CALL archive_update_url_structure('.$gallery_id.',"'.addslashes($Update_url).'",3)');
				/*
				$insert_array 	= array();
				$insert_array[] = $gallery_id;
				$insert_array[] = $gallery_details['Section_id'];
				
				$result = implode('","', $insert_array);
				
				$gallery_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",3)');
				*/
				foreach($GalleryRelated as $key=>$image_id) {
				
				$this->db->query('CALL archive_add_gallery_related_images ("' . $gallery_id . '","' .$image_id . '","'.($key+1).'")');
				
				}
				
				$GalleryLive['url'] 		= $Update_url;
				$GalleryLive['content_id'] 	= $gallery_id;
				$Section 					= $gallery_details['Section_id'];
				
				$gallery_details = $GalleryLive;
				
				$ImageDetails = $this->db->query('CALL archive_get_gallery_images(' . $gallery_id . ')')->result_array();
				
					if(isset($ImageDetails)) {
					foreach($ImageDetails as $key=>$Image) {
					
				if($key == 0) {
					$gallery_details['first_image_path']  	= $Image['ImagePhysicalPath'];
					$gallery_details['first_image_title']  	= $Image['ImageCaption'];
					$gallery_details['first_image_alt'] 	= $Image['ImageAlt'];
					
						$this->archive_db->insert("gallery_".$Year,$gallery_details);
						//$this->live_db->insert("gallery",$gallery_details);
						
						$short_content_array = array(
						"content_id" 	=> $gallery_details["content_id"],
						"title"      	=> addslashes(strip_tags($gallery_details["title"])),
						"tags"			=> addslashes($gallery_details["tags"]),
						"summary" 		=> addslashes($gallery_details["summary_html"]),
						"bodytext" 		=> "",
						"section_id" 	=> $gallery_details["section_id"]
						);
						
						$this->archive_db->insert("short_gallery_details_".$Year,$short_content_array);
						//$this->live_db->insert("short_gallery_details",$short_content_array);
					
					
				}
				
						$GalleryRelatedImages =  array(
						"content_id" 					=> $gallery_id,
						"image_id"						=> $Image['content_id'],
						"gallery_image_path" 			=> $Image['ImagePhysicalPath'],
						"gallery_image_title" 			=> $Image['ImageCaption'],
						"gallery_image_alt" 			=> $Image['ImageAlt'],
						"display_order"					=> $Image['display_order']
						);
					
					$this->archive_db->insert("gallery_related_images_".$Year,$GalleryRelatedImages);
					//$this->live_db->insert("gallery_related_images",$GalleryRelatedImages);
					
					}
				
				}
				
				/*
				$insert_array 	= array();
				$insert_array[] = $gallery_id;
				$insert_array[] = $Section;
				
				$result = implode('","', $insert_array);
				
				$result = implode('","', $insert_array);
				$article_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",3)');
				*/
				
					if ($this->db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE) {
				//	if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$this->archive_db->trans_rollback();
						//$this->live_db->trans_rollback();
						echo $this->db->_error_message(); 
						exit;
						return $Insert_array = array(
						"content_id" => "NULL",
						"url"		=> ""
						);
					} else {
						$this->db->trans_commit();
						$this->archive_db->trans_commit();
						//$this->live_db->trans_commit();
						return $Insert_array = array(
						"content_id" => $gallery_id,
						"url"		=> $Update_url
						);
						
						/*
						$this->db->where("content_id",$gallery_id);
						$this->db->delete("archive_galleryrelatedimages");		
						
						$this->db->where("content_id",$gallery_id);
						$this->db->delete("archive_gallerymaster");
						*/
					}
			}
		}
	}
	
	// Start Gallery Insert Code
	
	public function insert_video_master_archive($video_details) {
	
			$this->db->trans_begin();
			$this->archive_db->trans_begin();
			//$this->live_db->trans_begin();
			
			$Year =  date('Y', strtotime((string)$video_details['publish_start_date']));
			
			if($Year == 2009 || $Year == 2010 || $Year == 2011 || $Year == 2012 || $Year == 2013 || $Year == 2014 || $Year == 2015  || $Year == 2016) {
			
			$VideoMaster     = $video_details;
			$VideoLive 		= $video_details['LiveVideoDetails'];
			
			$this->db->query('SET @contentid= 0;CALL archive_add_update_videomaster('.$video_details['ecenic_id'].',"'.addslashes($video_details['url_title']).'","'.addslashes($video_details['title']).'","'.addslashes($video_details['url']).'","'.addslashes($video_details['summaryHTML']).'","'.$video_details['Tags'].'","'.$video_details['MetaTitle'].'","'.$video_details['MetaDescription'].'","'.$video_details['publish_start_date'].'","'.$video_details['Noindexed'].'","'.$video_details['Nofollow'].'","'.$video_details['Canonicalurl'].'","'.$video_details['Allowcomments'].'",'.$video_details['Section_id'].','.$video_details['Author_ID'].','.$video_details['Agency_ID'].','.$video_details['Country_ID'].','.$video_details['State_ID'].','.$video_details['City_ID'].','.$video_details['image_id'].',"'.$video_details['VideoScript'].'","'.$video_details['VideoSite'].'","'.$video_details['status'].'","'.$video_details['Createdby'].'","'.$video_details['Createdon'].'","'.$video_details['Modifiedby'].'","'. $video_details['Modifiedon'].'",@contentid)');
	
			$status  = $video_details['status'];
	
			$result 	= $this->db->query("SELECT @contentid")->result_array();
			$video_id = $result[0]['@contentid'];

			if($video_id != '' && $video_id != 'NULL') {
				
				$Update_url = $video_details['url']."-".$video_id.".html";
				
				$this->db->query('CALL archive_update_url_structure('.$video_id.',"'.addslashes($Update_url).'",4)');
				/*
				$insert_array 	= array();
				$insert_array[] = $video_id;
				$insert_array[] = $video_details['Section_id'];
				
				$result = implode('","', $insert_array);
				
				$video_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",4)');
				*/
				$VideoLive['url'] 			= $Update_url;
				$VideoLive['content_id'] 	= $video_id;
				$Section 					= $video_details['Section_id'];
				
				$video_details = $VideoLive;
				
				$this->archive_db->insert("video_".$Year,$video_details);
				//$this->live_db->insert("video",$video_details);
						
						$short_content_array = array(
						"content_id" => $video_details["content_id"],
						"title"      => addslashes(strip_tags($video_details["title"])),
						"tags"		=> addslashes($video_details["tags"]),
						"summary" => addslashes($video_details["summary_html"]),
						"bodytext" => "",
						"section_id" => $video_details["section_id"]
						);
						
						$this->archive_db->insert("short_video_details_".$Year,$short_content_array);
						//$this->live_db->insert("short_video_details",$short_content_array);
						
						
						$insert_array 				= array();
						$insert_array['content_id'] = $video_id;
						$insert_array['section_id'] = $Section;
						
						$this->archive_db->insert("video_section_mapping_".$Year,$insert_array);
						//$this->live_db->insert("video_section_mapping",$insert_array);
						
				}
				
					if ($this->db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE) {
					//if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$this->archive_db->trans_rollback();
						//$this->live_db->trans_rollback();
						return $Insert_array = array(
						"content_id" => "NULL",
						"url"		=> ""
						);
					} else {
						$this->db->trans_commit();
						$this->archive_db->trans_commit();
						//$this->live_db->trans_commit();
						return $Insert_array = array(
						"content_id" => $video_id,
						"url"		=> $Update_url
						);
						
						/*
						$this->db->where("content_id",$video_id);
						$this->db->delete("videomaster");
						*/
					}
			
		}
	}
	
	
	public function insert_video_master($video_details) {
	
			$this->db->trans_begin();
			//$this->archive_db->trans_begin();
			$this->live_db->trans_begin();
			
			$Year =  date('Y', strtotime((string)$video_details['publish_start_date']));
			
			if($Year == 2009 || $Year == 2010 || $Year == 2011 || $Year == 2012 || $Year == 2013 || $Year == 2014 || $Year == 2015  || $Year == 2016) {
			
			$VideoMaster     = $video_details;
			$VideoLive 		= $video_details['LiveVideoDetails'];
			
			$this->db->query('SET @contentid= 0;CALL add_update_videomaster('.$video_details['ecenic_id'].',"'.addslashes($video_details['url_title']).'","'.addslashes($video_details['title']).'","'.addslashes($video_details['url']).'","'.addslashes($video_details['summaryHTML']).'","'.$video_details['Tags'].'","'.$video_details['MetaTitle'].'","'.$video_details['MetaDescription'].'","'.$video_details['publish_start_date'].'","'.$video_details['Noindexed'].'","'.$video_details['Nofollow'].'","'.$video_details['Canonicalurl'].'","'.$video_details['Allowcomments'].'",'.$video_details['Section_id'].','.$video_details['Author_ID'].','.$video_details['Agency_ID'].','.$video_details['Country_ID'].','.$video_details['State_ID'].','.$video_details['City_ID'].','.$video_details['image_id'].',"'.$video_details['VideoScript'].'","'.$video_details['VideoSite'].'","'.$video_details['status'].'","'.$video_details['Createdby'].'","'.$video_details['Createdon'].'","'.$video_details['Modifiedby'].'","'. $video_details['Modifiedon'].'",@contentid)');
	
			$status  = $video_details['status'];
	
			$result 	= $this->db->query("SELECT @contentid")->result_array();
			$video_id = $result[0]['@contentid'];

			if($video_id != '' && $video_id != 'NULL') {
				
				$Update_url = $video_details['url']."-".$video_id.".html";
				
				$this->db->query('CALL update_url_structure('.$video_id.',"'.addslashes($Update_url).'",4)');
				
				$insert_array 	= array();
				$insert_array[] = $video_id;
				$insert_array[] = $video_details['Section_id'];
				
				$result = implode('","', $insert_array);
				
				$video_mapping = $this->db->query('CALL add_content_mapping("' . $result . '",4)');
				
				$VideoLive['url'] 			= $Update_url;
				$VideoLive['content_id'] 	= $video_id;
				$Section 					= $video_details['Section_id'];
				
				$video_details = $VideoLive;
				
				//$this->archive_db->insert("video_".$Year,$video_details);
				$this->live_db->insert("video",$video_details);
						
						$short_content_array = array(
						"content_id" => $video_details["content_id"],
						"title"      => addslashes(strip_tags($video_details["title"])),
						"tags"		=> addslashes($video_details["tags"]),
						"summary" => addslashes($video_details["summary_html"]),
						"bodytext" => "",
						"section_id" => $video_details["section_id"]
						);
						
						//$this->archive_db->insert("short_video_details_".$Year,$short_content_array);
						$this->live_db->insert("short_video_details",$short_content_array);
						
						
						$insert_array 				= array();
						$insert_array['content_id'] = $video_id;
						$insert_array['section_id'] = $Section;
						
						//$this->archive_db->insert("video_section_mapping_".$Year,$insert_array);
						$this->live_db->insert("video_section_mapping",$insert_array);
						
				}
				
					//if ($this->db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE) {
					if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						//$this->archive_db->trans_rollback();
						$this->live_db->trans_rollback();
						return $Insert_array = array(
						"content_id" => "NULL",
						"url"		=> ""
						);
					} else {
						$this->db->trans_commit();
						//$this->archive_db->trans_commit();
						$this->live_db->trans_commit();
						return $Insert_array = array(
						"content_id" => $video_id,
						"url"		=> $Update_url
						);
						
						/*
						$this->db->where("content_id",$video_id);
						$this->db->delete("videomaster");
						*/
					}
			
		}
	}
	
	
	
	// Start Audio Insert Code
	
	public function insert_audio_master($audio_details) {
	
			$this->db->trans_begin();
			$this->archive_db->trans_begin();
			
			$Year =  date('Y', strtotime((string)$audio_details['publish_start_date']));
			
			if($Year == 2009 || $Year == 2010 || $Year == 2011 || $Year == 2012 || $Year == 2013 || $Year == 2014 || $Year == 2015  || $Year == 2016) {
			
			$AudioMaster     = $audio_details;
			$AudioLive 		= $audio_details['LiveAudioDetails'];
			
			$this->db->query('SET @contentid= 0;CALL add_update_audiomaster('.$audio_details['ecenic_id'].',"'.addslashes($audio_details['url_title']).'","'.addslashes($audio_details['title']).'","'.addslashes($audio_details['url']).'","'.addslashes($audio_details['summaryHTML']).'","'.$audio_details['Tags'].'","'.$audio_details['MetaTitle'].'","'.$audio_details['MetaDescription'].'","'.$audio_details['publish_start_date'].'","'.$audio_details['Noindexed'].'","'.$audio_details['Nofollow'].'","'.$audio_details['Canonicalurl'].'","'.$audio_details['Allowcomments'].'",'.$audio_details['Section_id'].','.$audio_details['Agency_ID'].','.$audio_details['Country_ID'].','.$audio_details['State_ID'].','.$audio_details['City_ID'].','.$audio_details['image_id'].',"'.$audio_details['Audio_path'].'","'.$audio_details['status'].'","'.$audio_details['Createdby'].'","'.$audio_details['Createdon'].'","'.$audio_details['Modifiedby'].'","'. $audio_details['Modifiedon'].'",@contentid)');
	
			$status  = $audio_details['status'];
	
			$result 	= $this->db->query("SELECT @contentid")->result_array();
			$audio_id = $result[0]['@contentid'];

			if($audio_id != '' && $audio_id != 'NULL') {
				
				$Update_url = $audio_details['url']."-".$audio_id.".html";
				
				$this->db->query('CALL update_url_structure('.$audio_id.',"'.addslashes($Update_url).'",5)');
				
				$AudioLive['url'] 			= $Update_url;
				$AudioLive['content_id'] 	= $audio_id;
				$Section 					= $audio_details['Section_id'];
				
				$audio_details = $AudioLive;
				
				$this->archive_db->insert("audio_".$Year,$audio_details);
						
						$short_content_array = array(
						"content_id" => $audio_details["content_id"],
						"title"      => addslashes(strip_tags($audio_details["title"])),
						"tags"		=> addslashes($audio_details["tags"]),
						"summary" => addslashes(@$audio_details['summaryHTML']),
						"bodytext" => "",
						"section_id" => $audio_details["section_id"]
						);
						
						$this->archive_db->insert("short_audio_details_".$Year,$short_content_array);
						
						
						$insert_array 				= array();
						$insert_array['content_id'] = $audio_id;
						$insert_array['section_id'] = $Section;
						
						$this->archive_db->insert("audio_section_mapping_".$Year,$insert_array);
						
				}
				
					if ($this->db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$this->archive_db->trans_rollback();
						return $Insert_array = array(
						"content_id" => "NULL",
						"url"		=> ""
						);
					} else {
						$this->db->trans_commit();
						$this->archive_db->trans_commit();
						return $Insert_array = array(
						"content_id" => $audio_id,
						"url"		=> $Update_url
						);
						
						$this->db->where("content_id",$audio_id);
						$this->db->delete("audiomaster");
					}
			
		}
	}
	
	
	public function get_ecenic_section_by_id($Section_id) {

		$this->db->select("*");
		$this->db->from("sectionmaster");
		$this->db->where("ecenic_section_id",$Section_id);
		$get = $this->db->get();
		return  $get->row_array();
	}
	// End Article Insert Code
	
	
}
?>