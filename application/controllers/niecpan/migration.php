<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Migration Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

class Migration extends CI_Controller 
{	

	public function __construct() 
	{	
		parent::__construct();
		$this->load->model('admin/migration_model');
		$this->load->model('admin/template_design_model');
		$this->load->model('admin/widget_model');
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->migration_db = $CI->load->database('migration_db', TRUE);
		
		$this->ecenic_image_path = '/home/din_images/ecenic_images/';
	}
	
	public function index()  {
		
		$data['title'] 				= 'Import Ecenic';
		$data['template'] 			= 'import_ecenic';
		
			$picture_xml_file_path = image_url."/XML/2016/dn-2016-aug-1-11-pictures.xml";
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['TotalCount']			= count($xml['Content']);
		
		$data['TotalSuccess']			= $this->migration_model->get_ecenic_content(1,2);
		$data['TotalFailure']			= $this->migration_model->get_ecenic_content(2,2);
		
		$data['CurrentSuccess']			= 0;
		$data['CurrentFailure']			= 0;
		
		
		$news_xml_file_path = image_url."/XML/2016/dn-2016-aug-1-11-news.xml";
		$xml = get_object_vars(simplexml_load_file($news_xml_file_path));
		
		$data['ArticleTotalCount']			= count($xml['Content']);
		
		$data['ArticleTotalSuccess']			= $this->migration_model->get_ecenic_content(1,1);
		$data['ArticleTotalFailure']			= $this->migration_model->get_ecenic_content(2,1);
		
		$data['ArticleCurrentSuccess']			= 0;
		$data['ArticleCurrentFailure']			= 0;
		
		$picture_xml_file_path = image_url."/XML/dn-full-gallery-with-uri-may-2016-to-aug-11-2016.xml";
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['GalleryTotalCount']				=  count($xml['Content']);
		
		$data['GalleryTotalSuccess']			= $this->migration_model->get_ecenic_content(1,3);
		$data['GalleryTotalFailure']			= $this->migration_model->get_ecenic_content(2,3);
		
		$data['GalleryCurrentSuccess']			= 0;
		$data['GalleryCurrentFailure']			= 0;
		
		
		$picture_xml_file_path = image_url."/XML/dn-full-videos-with-uri-may-2016-to-aug-11-2016.xml";
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['VideoTotalCount']			=  count($xml['Content']);
		
		$data['VideoTotalFailure']			= $this->migration_model->get_ecenic_content(1,4);
		$data['VideoTotalSuccess']			= $this->migration_model->get_ecenic_content(2,4);
		
		$data['VideoCurrentSuccess']			= 0;
		$data['VideoCurrentFailure']			= 0;
		
		
		
		$this->load->view('admin_template', $data);
		
	}

	
		public function get_pictures() 
	{	
	
		$picture_xml_file_path = image_url."/XML/2016/dn-2016-jun-11-20-news.xml";
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['TotalCount']			= count($xml['Content']);
		$Success = 0;
		$Failure = 0;
		
		if($xml != '' && !empty($xml))
		{
			$TotalCount =  $this->migration_model->total_import_ecenic_content(2);
			 
			$Slice_Content  = array_slice($xml['Content'],3600,500);
/*
echo '<pre>';
print_r($Slice_Content);
exit;
*/

			foreach($Slice_Content as $key=>$Contents) {

				if($this->migration_model->check_ecenic_content( $Contents->articleid,2) == 0) {		

			$SubObject = (array)simplexml_load_string($Contents->content,'SimpleXMLElement', LIBXML_NOCDATA);
			$BinaryData = (array)simplexml_load_string($SubObject['BINARY'],'SimpleXMLElement', LIBXML_NOCDATA);
			
			$ImagePath = urldecode($BinaryData['href']);
			
	
			if(file_exists($this->ecenic_image_path.$ImagePath)) {

			$ImagePath_array = explode("/",$ImagePath);
			
			if(count($ImagePath_array) >= 5 ) 
				$this->create_image_folder_structure($ImagePath_array[0],$ImagePath_array[1],$ImagePath_array[2],$ImagePath_array[3]);
			elseif(count($ImagePath_array) == 4) 
				$this->create_image_folder_structure($ImagePath_array[0],$ImagePath_array[1],$ImagePath_array[2],'');	
		
			$Status = ($Contents->ArticleState == "published") ? 'P' : 'U';
			
			$this->db->trans_begin();
			
			$ContentId = $this->migration_model->insert_imagemaster($BinaryData,$Contents->articleid, $Contents->lastModified);
			
			$status = 0;
			
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$status = 0;
				} else {
					$this->db->trans_commit();
					if($ContentId != '' && $ContentId != 0 && $ContentId != 'NULL') {
					$status = 1;
					} else {
					$status = 0;
					}
					
				}
			
				if($status == 0 ) {
					
						$LogPath =  source_base_path.'migration_log/pictures.txt';
						
						$myfile = fopen($LogPath , "a") or die("Unable to open file!");
						
						$txt = $Contents->ArticleURI."\n";
						$txt = $ImagePath."\n";
						$txt .=  $Contents->articleid."\n";
						$txt .= "------------\n";
						
						fwrite($myfile, $txt);
						fclose($myfile);
							
						$this->migration_model->insert_ecenic_content( $Contents->articleid,2 ,"NULL" ,2,(string)$Contents->ArticleURI,(integer)$Contents->sectionID,(string)$Contents->lastModified); 
						
						$Failure ++;
						
				} else {
					
						$this->migration_model->insert_ecenic_content( $Contents->articleid,2 ,$ContentId ,1,(string)$Contents->ArticleURI,(integer)$Contents->sectionID,(string)$Contents->lastModified); 
						
						$Success++;
				}			
			} else {
				
					$LogPath =  source_base_path.'migration_log/pictures.txt';
					
					$myfile = fopen($LogPath , "a") or die("Unable to open file!");
$txt = $Contents->ArticleURI."\n";

					$txt = $ImagePath."\n";
					$txt .=  $Contents->articleid."\n";
					$txt .= "------------\n";
					fwrite($myfile, $txt);
					fclose($myfile);
						
					$this->migration_model->insert_ecenic_content( $Contents->articleid,2 ,"NULL" ,2,(string)$Contents->ArticleURI,(integer)$Contents->sectionID,(string)$Contents->lastModified); 
					
					$Failure ++;
			}
			
			}
			
			}
		}
		
		$data['title'] 				= 'Import Ecenic';
		$data['template'] 			= 'import_ecenic';
		
		$data['TotalSuccess']			= $this->migration_model->get_ecenic_content(1,2);
		$data['TotalFailure']			= $this->migration_model->get_ecenic_content(2,2);
		
		$data['CurrentSuccess']			= $Success;
		$data['CurrentFailure']			= $Failure;
		
		$this->load->view('admin_template', $data);
		
	}	
	
	
		public function get_articles() 
	{	
	
		$picture_xml_file_path =  image_url."/XML/2016/dn-2016-aug-1-11-news.xml";
		$ImagePath 				= imagelibrary_image_path;
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['ArticleTotalCount']			= count($xml['Content']);
		$Success = 0;
		$Failure = 0;
		
		if($xml != '' && !empty($xml))
		{
			
			$TotalCount = $this->migration_model->total_import_ecenic_content(1);
		 
			$Slice_Content  = array_slice($xml['Content'],6000,1000);
		/*
			echo "<pre>";
			print_r($Slice_Content);
			exit;
*/
			
			foreach( $Slice_Content  as $key=>$Contents)  {
			
			if($this->migration_model->check_ecenic_content( $Contents->articleid,1) == 0 && (string)$Contents->ArticleState == 'published') {
		

			$Status = "P";
			$related_article_id = array();
						
			$SubObject = (array)simplexml_load_string($Contents->content,'SimpleXMLElement', LIBXML_NOCDATA);
			
			$Ecenic_Summary = (array)simplexml_load_string(@$SubObject['COM.ESCENIC.SUMMARIES'],'SimpleXMLElement', LIBXML_NOCDATA);
			
			$Body = str_replace("<p><strong>Related Articles</strong></p>","related_articles_nie",$SubObject['BODY']);
			$Body = str_replace("<p>Related Articles</p>","related_articles_nie",$Body);
			$Body = str_replace("<p><u><strong>Related Articles</strong></u></p>","related_articles_nie",$Body);
			
			$Body = $Body .'</body>';
		
			preg_match("/related_articles_nie(.*?)<\/body>/s", $Body , $match);
				
			if(isset($match[1]) && $match[1] != '') {
			
				$doc = new DOMDocument();
				@$doc->loadHTML($match[1]);

				$ahreftags = $doc->getElementsByTagName('a');
				
				$RelatedArticleLink = '';
					foreach ($ahreftags as $tag) {
						
							$RelatedArticleLink = $tag->getAttribute('href');
							$parseurl = parse_url($RelatedArticleLink, PHP_URL_PATH);
						
							$explodeurl = explode('/',$parseurl);
							
							if(count($explodeurl) == 3) {
									$related_article = $explodeurl[2];	
							} else if(count($explodeurl) > 3) {
									$related_article_array = explode('.',end($explodeurl));
									$related_article = str_replace('article','',@$related_article_array[0]);
							} else {
									$related_article = '';
							}
							
							if($related_article != '') {
							
							$ArticleDetails = $this->migration_model->get_content_by_ecenic_id($related_article,1);
						
							if($ArticleDetails->num_rows() != 0)
								 $related_article_id[] = $ArticleDetails->row()->content_id;	
							 
							}
					}		
				}			
				
			
			$UserId 		= USERID;
			
			$Country 		= "NULL";
			$LiveCountryName    = "";
			$City 			= "NULL";
			$LiveCityName    	= "";
			$State 			= "NULL";
			$LiveStateName 		= "";
			
			$Section 		= "NULL";
			
			$Agency			= "NULL";
			$Author			 = "NULL";
			$LiveAgencyName 	= "";
			$LiveAuthorName 	= "";
			
			$HomeImageId 	= "NULL";
			$SectionImageId = "NULL";
			$ArticleImageId = "NULL";
			
			$Topic_id       = "NULL";

			
			if(isset($SubObject['LASTEDITEDBY']) && $SubObject['LASTEDITEDBY'] != ''){
				$UserDetails  = $this->migration_model->check_username($SubObject['LASTEDITEDBY']);
				if($UserDetails->num_rows() != 0 ) {
					$UserId = $UserDetails->row()->User_id;
				} else {
					$UserId  = $this->migration_model->insert_userdetails($SubObject['LASTEDITEDBY'], $Contents->lastModified );
				}
			}

		
			if($Contents->sectionID == 2878) {
				$Contents->sectionID = 2802;
			} elseif($Contents->sectionID == 4494 || $Contents->sectionID == 4495 || $Contents->sectionID == 4496 || $Contents->sectionID == 4488 || $Contents->sectionID == 4489 || $Contents->sectionID == 4490 || $Contents->sectionID == 4491 || $Contents->sectionID == 4492 || $Contents->sectionID == 4493 ) {
				$Contents->sectionID = 4487;
			} elseif($Contents->sectionID == 2205 || $Contents->sectionID == 2207 || $Contents->sectionID == 2209 || $Contents->sectionID == 2211 || $Contents->sectionID == 2213 || $Contents->sectionID == 2215 || $Contents->sectionID == 2217 || $Contents->sectionID == 2219 || $Contents->sectionID == 2325 || $Contents->sectionID == 2192 || $Contents->sectionID == 2195 || $Contents->sectionID == 2197 || $Contents->sectionID == 2199 || $Contents->sectionID == 2201 || $Contents->sectionID == 2203 ) {
				$Contents->sectionID = 1871;
			} elseif($Contents->sectionID == 242 || $Contents->sectionID == 243 || $Contents->sectionID == 244 || $Contents->sectionID == 245 || $Contents->sectionID == 246 || $Contents->sectionID == 247 || $Contents->sectionID == 248 ) {
				$Contents->sectionID = 231;
			} elseif($Contents->sectionID == 7143  ) {
				$Contents->sectionID = 249;
			} elseif($Contents->sectionID == 251 || $Contents->sectionID == 252 || $Contents->sectionID == 253 || $Contents->sectionID == 254 || $Contents->sectionID == 255 || $Contents->sectionID == 256 || $Contents->sectionID == 257 ) {
				$Contents->sectionID = 250;
			} elseif($Contents->sectionID == 6114 || $Contents->sectionID == 6116 || $Contents->sectionID == 6118 ) {
				$Contents->sectionID = 6112;
			} elseif($Contents->sectionID == 6530  ) {
				$Contents->sectionID = 6528;
			}elseif($Contents->sectionID == 298) {
				$Contents->sectionID = 5524;
			}
				
			$SectonDetails = $this->migration_model->check_sectionname($Contents->sectionID);
			
			if($SectonDetails->num_rows() != 0 ) {
					$Section = $SectonDetails->row()->Section_id;
				} 
	
			if($Section != "NULL") {
				
				if(isset($SubObject['LOCATION']) && $SubObject['LOCATION'] != '') {
					$LocationArray = explode('/',$SubObject['LOCATION']);
				
					$LocationArray = explode("(", $LocationArray[0]);
					$CityName = trim($LocationArray[0]);
					
					$CityDetails = $this->migration_model->get_city_statedetails($CityName)->row();
					
					if(isset($CityDetails->City_id) && $CityDetails->City_id != '') {
					$City 				= $CityDetails->City_id;
					$LiveCityName    	= $CityName;
					}
				
					if(isset($CityDetails->State_Id) && $CityDetails->State_Id != '') {
					$State 				= $CityDetails->State_Id;
					$LiveStateName    	= @get_statename_by_id($State);
					}
				
					if(isset($CityDetails->Country_id) && $CityDetails->Country_id != '') {
					$Country 			= $CityDetails->Country_id;
					$LiveCountryName 	= @get_countryname_by_id($Country);
					}
				}
				
			
				if(isset($SubObject['BYLINE']) && $SubObject['BYLINE'] != '') {
					
					$AuthorName = trim($SubObject['BYLINE']);
					
					/*
					$ExplodeByline = explode(" | ",$SubObject['BYLINE']);
					
					if(count($ExplodeByline) == 2) {
					$AgencyName = $ExplodeByline[1];
					$AuthorName = $ExplodeByline[0];
					} else {
					$AgencyName = 	$SubObject['BYLINE'];
					}
					
				
					if(trim(isset($AgencyName)) != '' ) {
					
						$AgencyDetails  = $this->migration_model->check_agencyname(trim($AgencyName));
								
								if($AgencyDetails->num_rows() != 0 ) {
									$Agency 		= $AgencyDetails->row()->Agency_id;
									$LiveAgencyName = trim($AgencyName);
								} else {
									$Agency  = $this->migration_model->insert_agencydetails($AgencyName, (string)$Contents->lastModified, $UserId);
							}	
						}
				*/
				
				if(trim(isset($AuthorName)) != '' ) {
				
						$AuthorDetails  = $this->migration_model->check_authorname(trim($AuthorName));
								
								if($AuthorDetails->num_rows() != 0 ) {
									$Author 		= $AuthorDetails->row()->Author_id;
									$LiveAuthorName = trim($AuthorName);
								} else {
									$Author  = $this->migration_model->insert_authordetails($AuthorName, (string)$Contents->lastModified, $UserId);
							}	
						}
				}
						
				$SubObjectBody = $SubObject['BODY'];			
							
				$doc = new DOMDocument();
				@$doc->loadHTML($SubObjectBody);

				$tags = $doc->getElementsByTagName('img');
				
				$LinkTags = $doc->getElementsByTagName('a');
				
				$BodyTextLink = '';
				foreach($LinkTags as $Link) {
					$BodyTextLink = $Link->getAttribute('href');
					
					if(isset($BodyTextLink) && $BodyTextLink != '') {
						
							$RelatedArticleLink = $Link->getAttribute('href');
							
							$parseurl = parse_url($RelatedArticleLink, PHP_URL_PATH);
							
							$explodeurl = explode('/',$parseurl);
							
							if(count($explodeurl) == 3) {
									$article_id = $explodeurl[2];	
							} else if(count($explodeurl) > 3) {
									$article_id_array = explode('.',end($explodeurl));
									$article_id = str_replace('article','',@$article_id_array[0]);
							} else {
									$article_id = '';
							}
					}
					
					
					if(!is_numeric($article_id)) {
						$article_id_array = explode('.',@$explodeurl[1]);
						$article_id = str_replace('article','',@$article_id_array[0]);
					}
					
					if($article_id != '' && is_numeric($article_id)) {
					
						$ArticleDetails = $this->migration_model->get_content_by_ecenic_id($article_id,1);
						
						$ArticleDetails = $ArticleDetails->row_array();
				
					if(isset($ArticleDetails['section_id']) && $ArticleDetails['section_id'] != 0) {
				
							$CurrentURL =$ArticleDetails['ecenic_url'];
						
							$SubObjectBody = str_replace($RelatedArticleLink, $CurrentURL, $SubObjectBody);
					}	
						
					}
					
				}

				$BodyTextSrc = '';
				foreach ($tags as $tag) {
					   $BodyTextSrc = $tag->getAttribute('src');
					   
						if(isset($BodyTextSrc) && $BodyTextSrc != '') {
					$SrcArray = explode("/",$BodyTextSrc);
					
					if(isset($SrcArray[2]) && $SrcArray[2] != '') {
					
					$ArticleImageDetails = $this->migration_model->get_content_by_ecenic_id($SrcArray[2],2);
					
	
						if($ArticleImageDetails->num_rows() != 0) {
							 $ArticleImageDetails = get_imagedetails_by_contentid($ArticleImageDetails->row()->content_id);
							 
							 if(isset($ArticleImageDetails['ImagePhysicalPath']) != ''){
								 
							 $SubObjectBody = str_replace($BodyTextSrc,image_url.$ImagePath.$ArticleImageDetails['ImagePhysicalPath'],  $SubObjectBody );
						
							 }		 
						}
					}
				}
				}
			if(isset($Ecenic_Summary['picturerel']) && !(empty($Ecenic_Summary['picturerel']))) {
				
				$PictureContent = @(array)$Ecenic_Summary['picturerel'];
				
				$PictureHref 		= @(array)$PictureContent['content-summary']->link->href;
				$PictureTitleArray =  @(array)$PictureContent['content-summary']->link->title;
				
				$picture_array = explode("/",@$PictureHref[0]);
				
				$PictureId = @$picture_array[2];
				$PictureTitle = @$PictureTitleArray[0];
				
			
				if($PictureId != '') {
					
					$ArticleImageDetails = $this->migration_model->get_content_by_ecenic_id ($PictureId,2);	
				
					if($ArticleImageDetails->num_rows() != 0)
						 $ArticleImageId = $ArticleImageDetails->row()->content_id;	
				}
				
				if($PictureId != '' && $ArticleImageId == 'NULL') {
					
					$LogPath =  source_base_path.'migration_log/pictures.txt';
					
					$myfile = fopen($LogPath , "a") or die("Unable to open file!");
					$txt = 	strip_tags($PictureTitle)."\n";
					$txt .=  $PictureId ."\n";
					$txt .= "------------\n";
					fwrite($myfile, $txt);
					fclose($myfile);
				}	
			}
			
			$Body = str_replace("<p><strong>Related Articles</strong></p>","related_articles_nie",$SubObjectBody );
			$Body = str_replace("<p>Related Articles</p>","related_articles_nie",$Body);
			$Body = str_replace("<p><u><strong>Related Articles</strong></u></p>","related_articles_nie",$Body);
			
			$Body = $Body .'</body>';

			$bodytext = preg_replace("/related_articles_nie(.*?)<\/body>/s","", $Body);
		
			$MainSection = get_section_by_id($Section);
		
			$Year =  date('Y', strtotime((string)$Contents->publishDate));
			$Month =  date('M', strtotime((string)$Contents->publishDate));
			$Date =  date('d', strtotime((string)$Contents->publishDate));
			
			$ArticleURI 	= explode("/",(string)$Contents->ArticleURI);
			$url_structure 	= urldecode(@$ArticleURI[3]);
			
			
			
			if(trim($url_structure) == '') {
				
				  $url_structure = strip_tags($SubObject['TITLE']);
				  $url_structure = RemoveSpecialCharacters(@$url_structure);
				  $url_structure = mb_strtolower(join( "-",( explode(" ",$url_structure) ) ));
				  
			}
			
			// Article Master table
			
			$ArticleContentDetails['ecenic_id'] 					= (string)$Contents->articleid;
			$ArticleContentDetails['url_title'] 					= str_replace("-"," ",$url_structure);
			$ArticleContentDetails['title'] 						= $SubObject['TITLE'];
			//$ArticleContentDetails['url']  							= join( "-",( explode(" ",@$MainSection['URLSectionStructure'] )))."/".str_replace(" ","-",$url_structure) ."/".$Year."/".$Month."/".$Date;
			$ArticleContentDetails['url'] 							= mb_strtolower(join( "-",( explode(" ",@$MainSection['URLSectionStructure'] ))))."/".$Year."/".mb_strtolower($Month)."/".$Date."/".$url_structure;
			$ArticleContentDetails['summaryHTML'] 					= addslashes($SubObject['LEADTEXT']);
			$ArticleContentDetails['ArticlePageContentHTML'] 		= $bodytext;
			$ArticleContentDetails['publish_start_date'] 			= (string)$Contents->publishDate;
			$ArticleContentDetails['publish_end_date'] 				= '';
			$ArticleContentDetails['scheduled_article'] 			= 0;
			$ArticleContentDetails['Tags'] 							= '';
			$ArticleContentDetails['MetaTitle'] 					= addslashes(strip_tags($SubObject['TITLE']));
			$ArticleContentDetails['MetaDescription'] 				= addslashes(strip_tags($SubObject['TITLE']));
			$ArticleContentDetails['Noindexed'] 					= 1;
			$ArticleContentDetails['Nofollow'] 						= 1;
			$ArticleContentDetails['Canonicalurl'] 					= '';
			$ArticleContentDetails['Allowcomments'] 				= 1;
			$ArticleContentDetails['section_promotion'] 			= 1;
			$ArticleContentDetails['link_to_resource'] 				= 0;
			$ArticleContentDetails['status'] 						= $Status;
			$ArticleContentDetails['Createdby'] 					= $UserId;
			$ArticleContentDetails['Createdon'] 					= (string)$Contents->lastModified;
			$ArticleContentDetails['Modifiedby'] 					= $UserId;
			$ArticleContentDetails['Modifiedon'] 					= (string)$Contents->lastModified;
			
			//   Article Related Data
			
			$ArticleContentDetails['ArticleRelated']['Section_id'] 					= $Section;
			$ArticleContentDetails['ArticleRelated']['Agency_ID'] 					= $Agency;
			$ArticleContentDetails['ArticleRelated']['Author_ID'] 					= $Author;
			$ArticleContentDetails['ArticleRelated']['Country_ID'] 					= $Country;
			$ArticleContentDetails['ArticleRelated']['State_ID'] 					= $State;
			$ArticleContentDetails['ArticleRelated']['City_ID'] 					= $City;
			$ArticleContentDetails['ArticleRelated']['homepageimageid'] 			= $HomeImageId;
			$ArticleContentDetails['ArticleRelated']['Sectionpageimageid'] 			= $SectionImageId;
			$ArticleContentDetails['ArticleRelated']['articlepageimageid'] 			= $ArticleImageId;

		
		# Start the Live Article Table Details 
	
		$LiveArticleDetails = array();
		
		$LiveArticleDetails['ecenic_id'] 								= $ArticleContentDetails['ecenic_id'];
		$LiveArticleDetails['section_id'] 								= $Section;
		$LiveArticleDetails['section_name'] 							= @$MainSection['Sectionname'];
		$LiveArticleDetails['parent_section_id'] 						= 'NULL';
		$LiveArticleDetails['parent_section_name'] 						= '';
		$LiveArticleDetails['grant_section_id'] 						= 'NULL';
		$LiveArticleDetails['grant_parent_section_name'] 				= '';
		
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
		
		// Archive contents live contents
			
			/*$ArticleContentDetails['ArticleRelated']['tag_ids'] 					= '';
			$LiveArticleDetails['agency_id'] 					= $Agency;
			$LiveArticleDetails['author_id'] 					= $Author;
			$LiveArticleDetails['country_id'] 					= $Country;
			$LiveArticleDetails['state_id'] 					= $State;
			$LiveArticleDetails['city_id'] 						= $City;
			$LiveArticleDetails['homepageimageid'] 				= $HomeImageId;
			$LiveArticleDetails['sectionpageimageid'] 			= $SectionImageId;
			$LiveArticleDetails['articlepageimageid'] 			= $ArticleImageId; */
		
		
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
		
		if($ArticleImageId != 'NULL') {
					
				$ArticleImageDetails = GetImageDetailsByContentId($ArticleImageId);

				$LiveArticleDetails['article_page_image_path'] 						= addslashes($ArticleImageDetails['ImagePhysicalPath']);
				$LiveArticleDetails['article_page_image_title'] 					= addslashes($ArticleImageDetails['ImageCaption']);
				$LiveArticleDetails['article_page_image_alt'] 						= addslashes($ArticleImageDetails['ImageAlt']);
		} else {
		
		$LiveArticleDetails['article_page_image_path'] 						= '';
		$LiveArticleDetails['article_page_image_title'] 					= '';
		$LiveArticleDetails['article_page_image_alt'] 						= '';
		
		}
		
		# Author Image Empty Data
		
		$LiveArticleDetails['url'] 											= $ArticleContentDetails['url'];
		
		$LiveArticleDetails['author_image_path'] 							= '';
		$LiveArticleDetails['author_image_title'] 							= '';
		$LiveArticleDetails['author_image_alt'] 							= '';
		
		$LiveArticleDetails['column_name'] 									= '';
		$LiveArticleDetails['hits']											= 0;
		$LiveArticleDetails['tags']											= '';
		
		$LiveArticleDetails['allow_comments']								= 1;
		
		$LiveArticleDetails['agency_name'] 									= $LiveAgencyName;
		$LiveArticleDetails['author_name']									= $LiveAuthorName;
		
		$LiveArticleDetails['country_name'] 								= $LiveCountryName;
		$LiveArticleDetails['state_name'] 									= $LiveStateName;
		$LiveArticleDetails['city_name'] 									= $LiveCityName;
		
		$LiveArticleDetails['no_indexed']									= 1;
		$LiveArticleDetails['no_follow']									= 1;
		$LiveArticleDetails['section_promotion'] 							= 1;
		$LiveArticleDetails['allow_pagination'] 							= 1;
		$LiveArticleDetails['status'] 										= $Status;
		
		$LiveArticleDetails['publish_start_date']  = '';
		$LiveArticleDetails['publish_end_date'] = '';
		
		if($ArticleContentDetails['publish_start_date'] != '')
		$LiveArticleDetails['publish_start_date'] 			= date('Y-m-d H:i', strtotime($ArticleContentDetails['publish_start_date']));
		
		if($ArticleContentDetails['publish_end_date'] != '')
		$LiveArticleDetails['publish_end_date']				= date('Y-m-d H:i', strtotime($ArticleContentDetails['publish_end_date']));
				
		$LiveArticleDetails['last_updated_on'] 				= (string)$Contents->lastModified;	
		$LiveArticleDetails['title'] 						= $ArticleContentDetails['title'];
		$LiveArticleDetails['summary_html'] 				= $ArticleContentDetails['summaryHTML'];
		$LiveArticleDetails['article_page_content_html'] 	= $ArticleContentDetails['ArticlePageContentHTML'];
		
		$LiveArticleDetails['canonical_url']  				= $ArticleContentDetails['Canonicalurl'];
		$LiveArticleDetails['meta_Title']  					= addslashes($ArticleContentDetails['MetaTitle']);
		$LiveArticleDetails['meta_description']  			= addslashes($ArticleContentDetails['MetaDescription']);
		
		$UserName 											= ($UserId != USERID) ? get_userdetails_by_id($UserId) : "";
		/*
		$LiveArticleDetails['created_by']					= $UserName;
		$LiveArticleDetails['created_on']					= (string)$Contents->lastModified;
		$LiveArticleDetails['modified_by']					= $UserName;
		$LiveArticleDetails['modified_on']					=( string)$Contents->lastModified;
		*/
		
		$ArticleContentDetails['LiveArticleDetails'] = $LiveArticleDetails;
		
		$ArticleContentDetails['RelatedArticle'] = $related_article_id;
		
		$Result = $this->migration_model->insert_article_master($ArticleContentDetails);

		if(isset($Result['content_id']) && $Result['content_id'] != 'NULL' && $Result['content_id'] != '') {

				$this->migration_model->insert_ecenic_content( $Contents->articleid,1 ,$Result['content_id'] ,1,$Result['url'],(INT)$Contents->sectionID,(string)$Contents->lastModified); 
				
				$Success++;
			
		} else {
		
				$LogPath =  source_base_path.'migration_log/article.txt';
					
					$myfile = fopen($LogPath , "a") or die("Unable to open file!");
					$txt =  "Section :".$Contents->sectionID ."\n";
					$txt .=  "Content Id :".$Contents->articleid ."\n";
					$txt .=  "Status :".(string)$Contents->ArticleState ."\n";
					$txt .=  "URL  :".urldecode($Contents->ArticleURI) ."\n";
					$txt .= "------------\n";
					fwrite($myfile, $txt);
					fclose($myfile);
					
					$this->migration_model->insert_ecenic_content( $Contents->articleid,1 ,"NULL" ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified); 
					
						$Failure++;
					
		
	
		}
						
				} else {
					
		
					$LogPath = source_base_path.'migration_log/article_section.txt';
					
					$myfile = fopen($LogPath , "a") or die("Unable to open file!");
					$txt =  "Section :".$Contents->sectionID ."\n";
					$txt .=  "Content Id :".$Contents->articleid ."\n";
					$txt .= "------------\n";
					fwrite($myfile, $txt);
					fclose($myfile);
					
						$Failure++;
						
					$this->migration_model->insert_ecenic_content( $Contents->articleid,1 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
				
				}
				
			} else {
				
				
				if((string)$Contents->ArticleState != 'published') {
				
					$LogPath =  source_base_path.'migration_log/article.txt';
					
					$myfile = fopen($LogPath , "a") or die("Unable to open file!");
					$txt =  "Section :".$Contents->sectionID ."\n";
					$txt .=  "Content Id :".$Contents->articleid ."\n";
					$txt .=  "Status :".(string)$Contents->ArticleState ."\n";
					$txt .=  "URL  :".urldecode($Contents->ArticleURI) ."\n";
					$txt .= "------------\n";
					fwrite($myfile, $txt);
					fclose($myfile);
					
					$this->migration_model->insert_ecenic_content( $Contents->articleid,1 ,"NULL" ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified); 
					
					$Failure++;
				} 
				
			}  
			
			}

		//	echo "Article Import Successfully...";	
		
		}
		
		//$picture_xml_file_path = image_url."/ecenic_images/nie-2014-jan-pictures.xml";
		
		$data['TotalCount']			= 0; //$data['ArticleTotalCount'];
		
		$data['TotalSuccess']			= 0; //$this->migration_model->get_ecenic_content(1,2);
		$data['TotalFailure']			= 0 ;//$this->migration_model->get_ecenic_content(2,2);
		
		$data['CurrentSuccess']			= 0;
		$data['CurrentFailure']			= 0;
		
			
		//$data['ArticleTotalCount']			= count($xml['Content']);
		
		$data['title'] 				= 'Import Ecenic';
		$data['template'] 			= 'import_ecenic';
		
		$data['ArticleTotalSuccess']			= $this->migration_model->get_ecenic_content(1,1);
		$data['ArticleTotalFailure']			= $this->migration_model->get_ecenic_content(2,1);
		
		$data['ArticleCurrentSuccess']			= $Success;
		$data['ArticleCurrentFailure']			= $Failure;

		$this->load->view('admin_template', $data);
		
		
	}
	
	
	
	public function get_galleries() 
	{	
	
		$picture_xml_file_path = image_url."/XML/dn-full-gallery-with-uri-may-2016-to-aug-11-2016.xml";
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['GalleryTotalCount']			= count($xml['Content']);
		$Success = 0;
		$Failure = 0;
			
		if($xml != '' && !empty($xml))
		{
		
			$TotalCount = $this->migration_model->total_import_ecenic_content(3);
			 
			$Slice_Content  = array_slice($xml['Content'],0,200);
			
			/*
			echo "<pre>";
			print_r($Slice_Content);
			exit;
			*/
					
			foreach($Slice_Content as $key=>$Contents) {
			/*	
			echo "<pre>";
			print_r($Contents);
			exit;
			*/
				
			
			$SubObject = (array)simplexml_load_string($Contents->content,'SimpleXMLElement', LIBXML_NOCDATA);
			
			$Ecenic_Summary = (array)simplexml_load_string(@$SubObject['COM.ESCENIC.SUMMARIES'],'SimpleXMLElement', LIBXML_NOCDATA);
			
				if($this->migration_model->check_ecenic_content( $Contents->articleid,3) == 0) {
					
					if( (string)$Contents->ArticleState != 'published') {
					
						$LogPath =  source_base_path.'migration_log/gallery.txt';
							
							$myfile = fopen($LogPath , "a") or die("Unable to open file!");
							$txt = 	"Gallery >> ".strip_tags($SubObject['TITLE'])."\n";
							$txt .= (string)$Contents->ArticleState."\n";
							$txt .=  $Contents->articleid ."\n";
							$txt .= "------------\n";
							fwrite($myfile, $txt);
							fclose($myfile);
						
						$this->migration_model->insert_ecenic_content( $Contents->articleid,3 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
						$Failure++;
					
					} else {
						
							$Status = 'P';
			
							$UserId 		= USERID;
							
							$Country 		= "NULL";
							$City 			= "NULL";
							$State 			= "NULL";
							
							$Agency			= "NULL";
							$Section		= "NULL";
							$Topic_id       = "NULL";
							$ImageId 		= array();
							
						if(isset($Contents->sectionID) && $Contents->sectionID != '') {
							
							$SectonDetails = $this->migration_model->check_sectionname($Contents->sectionID);
				
							if($SectonDetails->num_rows() != 0 ) {
									$Section = $SectonDetails->row()->Section_id;
							} 
						} else {
							$Section 		= 440;
						}
						
					if($Section != "NULL") { 
							
							if(isset($SubObject['LASTEDITEDBY']) && $SubObject['LASTEDITEDBY'] != ''){
								$UserDetails  = $this->migration_model->check_username($SubObject['LASTEDITEDBY']);
								if($UserDetails->num_rows() != 0 ) {
									$UserId = $UserDetails->row()->User_id;
								} else {
									$UserId  = $this->migration_model->insert_userdetails($SubObject['LASTEDITEDBY'], $Contents->lastModified );
								}
							} 
							
							
					if(isset($Ecenic_Summary['picturerel']) && !(empty($Ecenic_Summary['picturerel']))) {
						
						$PictureContent = @(array)$Ecenic_Summary['picturerel'];
					
						foreach($PictureContent['content-summary'] as $Picture) {
					
							$PictureHref = $Picture->link->href;
							$PictureTitleArray =  $Picture->link->title;
							
							$ImageBoolean = 0;
						
							
						$PictureIdArray = @explode("/",$PictureHref);
						$PictureId = @$PictureIdArray[2];
						$PictureTitle = @$PictureTitleArray;
						
						//echo $PictureId;
			
						if($PictureId != '') {
							$ImageDetails = $this->migration_model->get_content_by_ecenic_id($PictureId,2);
						
							if($ImageDetails->num_rows() != 0) {
								 $ImageId[] = $ImageDetails->row()->content_id;	
								 $ImageBoolean = 1;
							}
						}
							/*				
							echo "<pre>";
							print_r($ImageId);
							exit;
							*/				
							
							if(empty($ImageId) && $ImageBoolean == 0) {
								 
								$LogPath =  source_base_path.'migration_log/gallery_image.txt';
								
								$myfile = fopen($LogPath , "a") or die("Unable to open file!");
								$txt = 	"Gallery Image >> ".strip_tags($PictureTitle)."\n";
								$txt .=  $PictureId ."\n";
								$txt .= "------------\n";
								fwrite($myfile, $txt);
								fclose($myfile);
							}
							
						}
			
						if(!empty($ImageId)) {
			
							if(date('Y', strtotime((string)$Contents->publishDate)) < 2009) {
								$EcenicPublishDate = (string)$Contents->lastModified;
							} else {
								$EcenicPublishDate = (string)$Contents->publishDate;
							}
						
							$MainSection = get_section_by_id($Section);
						
							$Year =  date('Y', strtotime($EcenicPublishDate));
							$Month =  date('M', strtotime($EcenicPublishDate));
							$Date =  date('d', strtotime($EcenicPublishDate));
							
							$ArticleURI 	= explode("/",(string)$Contents->ArticleURI);
							$url_structure 	= urldecode(@$ArticleURI[3]);
						
							if(trim($url_structure) == '') {
								  $url_structure = strip_tags($SubObject['TITLE']);
								  $url_structure = RemoveSpecialCharacters(@$url_structure);
								  $url_structure = mb_strtolower(join( "-",( explode(" ",$url_structure) ) ));
							}
						
						$GalleryContentDetails['ecenic_id'] 					= (string)$Contents->articleid;
						$GalleryContentDetails['url_title'] 					=  str_replace("-"," ",$url_structure);
						$GalleryContentDetails['title'] 						=  $SubObject['TITLE'];
						//$GalleryContentDetails['url']  							=  join( "-",( explode(" ",@$MainSection['URLSectionStructure'] )))."/".str_replace(" ","-",$url_structure) ."/".$Year."/".$Month."/".$Date;
						$GalleryContentDetails['url'] 							= mb_strtolower(join( "-",( explode(" ",@$MainSection['URLSectionStructure'] ))))."/".$Year."/".mb_strtolower($Month)."/".$Date."/".$url_structure;
						
						$GalleryContentDetails['summaryHTML'] 					= (string)$SubObject['LEADTEXT'];
						$GalleryContentDetails['publish_start_date'] 			= $EcenicPublishDate;
						$GalleryContentDetails['Tags'] 							= '';
						$GalleryContentDetails['MetaTitle'] 					=(strip_tags($SubObject['TITLE']));
						$GalleryContentDetails['MetaDescription'] 				= (strip_tags($SubObject['TITLE']));
						$GalleryContentDetails['Noindexed'] 					= 1;
						$GalleryContentDetails['Nofollow'] 						= 1;
						$GalleryContentDetails['Canonicalurl'] 					= '';
						$GalleryContentDetails['Allowcomments'] 				= 1;
						
						$GalleryContentDetails['Section_id'] 					= $Section;
						$GalleryContentDetails['Author_ID'] 					= "NULL";
						$GalleryContentDetails['Agency_ID'] 					= "NULL";
						$GalleryContentDetails['Country_ID'] 					= "NULL";
						$GalleryContentDetails['State_ID'] 						= "NULL";
						$GalleryContentDetails['City_ID'] 						= "NULL";
						
						$GalleryContentDetails['status'] 						= $Status;
						$GalleryContentDetails['Createdby'] 					= $UserId;
						$GalleryContentDetails['Createdon'] 					= (string)$Contents->lastModified;
						$GalleryContentDetails['Modifiedby'] 					= $UserId;
						$GalleryContentDetails['Modifiedon'] 					= (string)$Contents->lastModified;
						
						$GalleryContentDetails['GalleryRelatedImages'] 			=  $ImageId;
							
						# Start the Live Article Table Details 
					
						$LiveGalleryDetails = array();
						
						$LiveGalleryDetails['ecenic_id'] 								= $GalleryContentDetails['ecenic_id'] ;
						$LiveGalleryDetails['section_id'] 								= $GalleryContentDetails['Section_id'] ;
						$LiveGalleryDetails['section_name'] 							= "Other";
						$LiveGalleryDetails['parent_section_id'] 						= 'NULL';
						$LiveGalleryDetails['parent_section_name'] 						= '';
						$LiveGalleryDetails['grant_section_id'] 						= 'NULL';
						$LiveGalleryDetails['grant_parent_section_name'] 				= '';
						/*
						$LiveGalleryDetails['tag_ids'] 								= '';
						$LiveGalleryDetails['agency_id'] 							= "NULL";
						$LiveGalleryDetails['country_id'] 							= "NULL";
						$LiveGalleryDetails['state_id'] 							= "NULL";
						$LiveGalleryDetails['city_id'] 								= "NULL";
						*/
						
						# Home Image Empty Data
						
						$LiveGalleryDetails['first_image_path'] 					= '';
						$LiveGalleryDetails['first_image_title'] 					= '';
						$LiveGalleryDetails['first_image_alt'] 						= '';
					
						$LiveGalleryDetails['url'] 											= $GalleryContentDetails['url'];
						
						$LiveGalleryDetails['hits']											= 0;
						$LiveGalleryDetails['tags']											= '';
						
						$LiveGalleryDetails['allow_comments']								= 1;
						
						$LiveGalleryDetails['agency_name'] 									= '';
						$LiveGalleryDetails['country_name'] 								= '';
						$LiveGalleryDetails['state_name'] 									= '';
						$LiveGalleryDetails['city_name'] 									= '';
						
						$LiveGalleryDetails['no_indexed']									= 1;
						$LiveGalleryDetails['no_follow']									= 1;
						$LiveGalleryDetails['status'] 										= $Status;
						
						$LiveGalleryDetails['publish_start_date']  = '';
						
						if($GalleryContentDetails['publish_start_date'] != '')
						$LiveGalleryDetails['publish_start_date'] 			= date('Y-m-d H:i', strtotime($GalleryContentDetails['publish_start_date']));
								
						$LiveGalleryDetails['last_updated_on'] 				= (string)$Contents->lastModified;	
						$LiveGalleryDetails['title'] 						= $GalleryContentDetails['title'] ;
						$LiveGalleryDetails['summary_html'] 				= $GalleryContentDetails['summaryHTML'];
						
						$LiveGalleryDetails['canonical_url']  				= $GalleryContentDetails['Canonicalurl'];
						$LiveGalleryDetails['meta_Title']  					= $GalleryContentDetails['MetaTitle'];
						$LiveGalleryDetails['meta_description']  			= $GalleryContentDetails['MetaDescription'];
						
						$UserName 											= ($UserId != USERID) ? get_userdetails_by_id($UserId) : "";
						/*
						$LiveGalleryDetails['created_by']					= $UserName;
						$LiveGalleryDetails['created_on']					= (string)$Contents->lastModified;
						$LiveGalleryDetails['modified_by']					= $UserName;
						$LiveGalleryDetails['modified_on']					=( string)$Contents->lastModified;
						*/
						$GalleryContentDetails['LiveGalleryDetails'] = $LiveGalleryDetails;

						$Result = $this->migration_model->insert_gallery_master($GalleryContentDetails);
							
						
						if(isset($Result['content_id']) && $Result['content_id'] != 'NULL' && $Result['content_id'] != '') {
							
							$this->migration_model->insert_ecenic_content( $Contents->articleid,3 ,$Result['content_id'] ,1,(string)urldecode($Result['url']),(INT)$Section,(string)$Contents->lastModified); 
						
								$Success++;	
							} else  {
							
										$LogPath =  source_base_path.'migration_log/gallery.txt';
										
										$myfile = fopen($LogPath , "a") or die("Unable to open file!");
										$txt = 	"Gallery >> ".strip_tags($SubObject['TITLE'])."\n";
										$txt .= (string)$Contents->ArticleState."\n";
										$txt .=  $Contents->articleid ."\n";
										$txt .= "------------\n";
										fwrite($myfile, $txt);
										fclose($myfile);
									
									$this->migration_model->insert_ecenic_content( $Contents->articleid,3 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
								$Failure++;
							}
						} else  {
						
								$LogPath =  source_base_path.'migration_log/gallery.txt';
								
								$myfile = fopen($LogPath , "a") or die("Unable to open file!");
								$txt = 	"Gallery >> ".strip_tags($SubObject['TITLE'])."\n";
								$txt .= (string)$Contents->ArticleState."\n";
								$txt .=  $Contents->articleid ."\n";
								$txt .= "------------\n";
								fwrite($myfile, $txt);
								fclose($myfile);
							
							$this->migration_model->insert_ecenic_content( $Contents->articleid,3 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
							$Failure++;
						}
							
						
						} 
					
					} else {
						
						$LogPath = source_base_path.'migration_log/gallery_section.txt';
						
						$myfile = fopen($LogPath , "a") or die("Unable to open file!");
						$txt =  "Section :".$Contents->sectionID ."\n";
						$txt .=  "Content Id :".$Contents->articleid ."\n";
						$txt .= "------------\n";
						fwrite($myfile, $txt);
						fclose($myfile);
							
						$this->migration_model->insert_ecenic_content( $Contents->articleid,3 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
						$Failure++;					
					}
					
					}
				}  
				
			}
							
		$data['TotalCount']			= 0;
		
		$data['TotalSuccess']			= 0;
		$data['TotalFailure']			= 0;
		$data['CurrentSuccess']			= 0;
		$data['CurrentFailure']			= 0;

		$data['ArticleTotalCount']			= 0;
		$data['ArticleTotalSuccess']			= 0;
		$data['ArticleTotalFailure']			= 0;
		$data['ArticleCurrentSuccess']			= 0;
		$data['ArticleCurrentFailure']			= 0;
			//$this->migration_db->reconnect();
		$data['GalleryTotalSuccess']			= $this->migration_model->get_ecenic_content(1,3);
		//	$this->migration_db->reconnect();
		$data['GalleryTotalFailure']			= $this->migration_model->get_ecenic_content(2,3);
		
		$data['GalleryCurrentSuccess']			= $Success;
		$data['GalleryCurrentFailure']			= $Failure;
		
		
		$data['ArticleTotalCount']				= 0;
		
		$data['ArticleTotalSuccess']			= 0;
		
		$data['ArticleTotalFailure']			= 0;
		
		$data['ArticleCurrentSuccess']			= 0;
		
		$data['ArticleCurrentFailure']			= 0;
	
		
		$data['title'] 				= 'Import Ecenic';
		$data['template'] 			= 'import_ecenic';
		
		$this->load->view('admin_template', $data); 
			
		}
		
	}
	
	public function get_video() {
		
		$picture_xml_file_path = image_url."/XML/dn-full-videos-with-uri-may-2016-to-aug-11-2016.xml";
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['VideoTotalCount']			= count($xml['Content']);
		$Success = 0;
		$Failure = 0;
		
		if($xml != '' && !empty($xml))
		{
			
			$TotalCount = $this->migration_model->total_import_ecenic_content(4);
			 
			$Slice_Content  = array_slice($xml['Content'],0,1);
			
			foreach($Slice_Content as $key=>$Contents) {
				
				/*
				echo "<pre>";
				print_r($Contents);
				exit; 
				*/
		
				if($this->migration_model->check_ecenic_content( $Contents->articleid,4) == 0 && (string)$Contents->ArticleState == 'published') {
					
					if( (string)$Contents->ArticleState != 'published') {
					
						$LogPath =  source_base_path.'migration_log/video.txt';
							
							$myfile = fopen($LogPath , "a") or die("Unable to open file!");
							$txt = 	"Gallery >> ".strip_tags($SubObject['TITLE'])."\n";
							$txt .= (string)$Contents->ArticleState."\n";
							$txt .=  $Contents->articleid ."\n";
							$txt .= "------------\n";
							fwrite($myfile, $txt);
							fclose($myfile);
						
						$this->migration_model->insert_ecenic_content( $Contents->articleid,4 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
						$Failure++;
					
					} else {
					
					$Status 			= "P";
					$UserId 			= USERID;
					
					$Country 			= "NULL";
					$LiveCountryName 	= "";
					
					$City 				= "NULL";
					$LiveCityName  		= "";
					
					$State 				= "NULL";
					$LiveStateName		= "";
					
					$Section 			= "NULL";
					$SectionName    	= "";
					
					$Author				= "NULL";
					
					$Agency				= "NULL";
					$LiveAgencyName		= "";
					
					$ImageId 			= "NULL";
					
					$SubObject 		= (array)simplexml_load_string($Contents->content,'SimpleXMLElement', LIBXML_NOCDATA);
					$Ecenic_Summary = (array)simplexml_load_string(@$SubObject['COM.ESCENIC.SUMMARIES'],'SimpleXMLElement', LIBXML_NOCDATA);
					
					if(isset($SubObject['LASTEDITEDBY']) && $SubObject['LASTEDITEDBY'] != ''){
						$UserDetails  = $this->migration_model->check_username($SubObject['LASTEDITEDBY']);
						if($UserDetails->num_rows() != 0 ) {
							$UserId = $UserDetails->row()->User_id;
						} else {
							$UserId  = $this->migration_model->insert_userdetails($SubObject['LASTEDITEDBY'], $Contents->lastModified );
						}
					} 
					
					$SectonDetails = $this->migration_model->check_sectionname($Contents->sectionID);
				
					if($SectonDetails->num_rows() != 0 ) {
							$Section = $SectonDetails->row()->Section_id;
					} 
					
					if($Section != "NULL") {
					
						if(isset($SubObject['LOCATION']) && $SubObject['LOCATION'] != '') {
							$LocationArray = explode('/',$SubObject['LOCATION']);
						
							$LocationArray = explode("(", $LocationArray[0]);
							$CityName = trim($LocationArray[0]);
							
							$CityDetails = $this->migration_model->get_city_statedetails($CityName)->row();
							
							if(isset($CityDetails->City_id) && $CityDetails->City_id != '') {
							$City 				= $CityDetails->City_id;
							$LiveCityName    	= $CityName;
							}
						
							if(isset($CityDetails->State_Id) && $CityDetails->State_Id != '') {
							$State 				= $CityDetails->State_Id;
							$LiveStateName    	= @get_statename_by_id($State);
							}
						
							if(isset($CityDetails->Country_id) && $CityDetails->Country_id != '') {
							$Country 			= $CityDetails->Country_id;
							$LiveCountryName 	= @get_countryname_by_id($Country);
							}
						}
						
					/*
						if(isset($SubObject['BYLINE']) && $SubObject['BYLINE'] != '') {
							
							$ExplodeByline = explode(" | ",$SubObject['BYLINE']);
							
							if(count($ExplodeByline) == 2) {
							$AgencyName = $ExplodeByline[1];
							$AuthorName = $ExplodeByline[0];
							} else {
							$AgencyName = 	$SubObject['BYLINE'];
							}
							
						
							if(trim(isset($AgencyName)) != '' ) {
							
								$AgencyDetails  = $this->migration_model->check_agencyname(trim($AgencyName));
										
										if($AgencyDetails->num_rows() != 0 ) {
											$Agency 		= $AgencyDetails->row()->Agency_id;
											$LiveAgencyName = trim($AgencyName);
										} else {
											$Agency  = $this->migration_model->insert_agencydetails($AgencyName, (string)$Contents->lastModified, $UserId);
									}	
								}
						} 
						*/
					
						if(isset($Ecenic_Summary['previewrel']) && !(empty($Ecenic_Summary['previewrel']))) {
								
								$PictureContent = @(array)$Ecenic_Summary['previewrel'];
						
								if(isset($PictureContent['content-summary'][0]->link->href)) {
									$Picture = $PictureContent['content-summary'][0];
									$PictureHref = $Picture->link->href;
									$PictureTitleArray =  $Picture->link->title;
								
								$PictureIdArray = @explode("/",$PictureHref[0]);
								$PictureId = $PictureIdArray[2];
								$PictureTitle = $PictureTitleArray[0];

								if($PictureId != '') {
									$ImageDetails = $this->migration_model->get_content_by_ecenic_id($PictureId,2);
								
									if($ImageDetails->num_rows() != 0)
										 $ImageId = $ImageDetails->row()->content_id;	
								}
								
										//echo $PictureId;
										//exit;
									if($PictureId != '' && $ImageId == 'NULL') {
										
										$LogPath =  source_base_path.'migration_log/video_image.txt';
										
										$myfile = fopen($LogPath , "a") or die("Unable to open file!");
										$txt = 	"video Image >> ".strip_tags($PictureTitle)."\n";
										$txt .=  $PictureId ."\n";
										$txt .= "------------\n";
										fwrite($myfile, $txt);
										fclose($myfile);
									}
									
								}
						}
								
						if(date('Y', strtotime((string)$Contents->publishDate)) < 2009) {
							$EcenicPublishDate = (string)$Contents->lastModified;
						} else {
							$EcenicPublishDate = (string)$Contents->publishDate;
						}
					
						$Year =  date('Y', strtotime($EcenicPublishDate));
						$Month =  date('M', strtotime($EcenicPublishDate));
						$Date =  date('d', strtotime($EcenicPublishDate));
						
						$ArticleURI 	= explode("/",(string)$Contents->ArticleURI);
						$url_structure 	= urldecode(@$ArticleURI[3]);
					
						if(trim($url_structure) == '') {
							$url_structure = strip_tags($SubObject['TITLE']);
							$url_structure = RemoveSpecialCharacters(@$url_structure);
							$url_structure = mb_strtolower(join( "-",( explode(" ",$url_structure) ) ));
						}
						
						
						$MainSection = get_section_by_id($Section);
						
						$VideoContentDetails['ecenic_id'] 					= (string)$Contents->articleid;
						$VideoContentDetails['url_title'] 					=  str_replace("-"," ",$url_structure);
						$VideoContentDetails['title'] 						=  $SubObject['TITLE'];
						//$VideoContentDetails['url']  						= join( "-",( explode(" ",@$MainSection['URLSectionStructure'] )))."/".str_replace(" ","-",$url_structure) ."/".$Year."/".$Month."/".$Date;
						
						$VideoContentDetails['url'] 							= mb_strtolower(join( "-",( explode(" ",@$MainSection['URLSectionStructure'] ))))."/".$Year."/".mb_strtolower($Month)."/".$Date."/".$url_structure;
						
						$VideoContentDetails['summaryHTML'] 				= (string)$SubObject['LEADTEXT'];
						$VideoContentDetails['publish_start_date'] 			= $EcenicPublishDate;
						$VideoContentDetails['Tags'] 						= '';
						$VideoContentDetails['MetaTitle'] 					=(strip_tags($SubObject['TITLE']));
						$VideoContentDetails['MetaDescription'] 			= (strip_tags($SubObject['TITLE']));
						$VideoContentDetails['Noindexed'] 					= 1;
						$VideoContentDetails['Nofollow'] 					= 1;
						$VideoContentDetails['Canonicalurl'] 				= '';
						$VideoContentDetails['Allowcomments'] 				= 1;
						$VideoContentDetails['Section_id'] 					= $Section;
						$VideoContentDetails['Author_ID'] 					= $Author;
						$VideoContentDetails['Agency_ID'] 					= $Agency;
						$VideoContentDetails['Country_ID'] 					= $Country;
						$VideoContentDetails['State_ID'] 					= $State;
						$VideoContentDetails['City_ID'] 					= $City;
						$VideoContentDetails['image_id']					= $ImageId;
						$VideoContentDetails['VideoScript']					= str_replace('"','',(string)$SubObject['CODE']);
						$VideoContentDetails['VideoSite']					= (string)$Contents->Articletype;
						$VideoContentDetails['status'] 						= $Status;
						$VideoContentDetails['Createdby'] 					= $UserId;
						$VideoContentDetails['Createdon'] 					= (string)$Contents->lastModified;
						$VideoContentDetails['Modifiedby'] 					= $UserId;
						$VideoContentDetails['Modifiedon'] 					= (string)$Contents->lastModified;
						
						$VideoContentDetails['GalleryRelatedImages'] 		=  $ImageId;
							
						# Start the Live Video Table Details 
					
						$LiveVideoDetails = array();
						
						$LiveVideoDetails['ecenic_id'] 							= $VideoContentDetails['ecenic_id'] ;
						$LiveVideoDetails['section_id'] 						= $VideoContentDetails['Section_id'] ;
						$LiveVideoDetails['section_name'] 						= @$MainSection['Sectionname'];
						$LiveVideoDetails['parent_section_id'] 					= 'NULL';
						$LiveVideoDetails['parent_section_name'] 				= '';
						$LiveVideoDetails['grant_section_id'] 					= 'NULL';
						$LiveVideoDetails['grant_parent_section_name'] 			= '';
									
							if(isset($MainSection['ParentSectionID']) && $MainSection['ParentSectionID'] != '') {
								
								$ParentMainSection = get_section_by_id($MainSection['ParentSectionID']);
								
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
						/*
						$LiveVideoDetails['tag_ids']					= '';
						$LiveVideoDetails['agency_id'] 					= $Agency;
						$LiveVideoDetails['country_id'] 				= $Country;
						$LiveVideoDetails['state_id'] 					= $State;
						$LiveVideoDetails['city_id'] 					= $City;
						$LiveVideoDetails['image_id']					= $ImageId;
						*/
						$LiveVideoDetails['video_image_path'] 					= '';
						$LiveVideoDetails['video_image_title'] 					= '';
						$LiveVideoDetails['video_image_alt'] 					= '';
						
						if($ImageId != 'NULL') {
					
							$VideoImageDetails = GetImageDetailsByContentId($ImageId);
							
							if(isset($VideoImageDetails['ImagePhysicalPath'])) {
								$LiveVideoDetails['video_image_path'] 			= addslashes($VideoImageDetails['ImagePhysicalPath']);
								$LiveVideoDetails['video_image_title'] 			= addslashes($VideoImageDetails['ImageCaption']);
								$LiveVideoDetails['video_image_alt'] 			= addslashes($VideoImageDetails['ImageAlt']);
							}
						}
						
						$LiveVideoDetails['url'] 								= $VideoContentDetails['url'];
						$LiveVideoDetails['hits']								= 0;
						$LiveVideoDetails['tags']								= '';
						$LiveVideoDetails['allow_comments']						= 1;
						$LiveVideoDetails['agency_name'] 						= $LiveAgencyName;
						$LiveVideoDetails['country_name'] 						= $LiveCountryName;
						$LiveVideoDetails['state_name'] 						= $LiveStateName;
						$LiveVideoDetails['city_name'] 							= $LiveCityName;
						$LiveVideoDetails['no_indexed']							= 1;
						$LiveVideoDetails['no_follow']							= 1;
						$LiveVideoDetails['status'] 							= $Status;
						
						$LiveVideoDetails['publish_start_date']  = '';
						
						if($VideoContentDetails['publish_start_date'] != '')
						$LiveVideoDetails['publish_start_date'] 					= date('Y-m-d H:i', strtotime($VideoContentDetails['publish_start_date']));
								
						$LiveVideoDetails['last_updated_on'] 						= (string)$Contents->lastModified;	
						$LiveVideoDetails['title'] 									= $VideoContentDetails['title'] ;
						$LiveVideoDetails['summary_html'] 							= $VideoContentDetails['summaryHTML'];
						$LiveVideoDetails['video_script'] 							= $VideoContentDetails['VideoScript'];
						$LiveVideoDetails['video_site'] 							= $VideoContentDetails['VideoSite'];
						$LiveVideoDetails['canonical_url']  						= $VideoContentDetails['Canonicalurl'];
						$LiveVideoDetails['meta_Title']  							= $VideoContentDetails['MetaTitle'];
						$LiveVideoDetails['meta_description']  						= $VideoContentDetails['MetaDescription'];
					
						$UserName 											= ($UserId != USERID) ? get_userdetails_by_id($UserId) : "";
						/*
						$LiveVideoDetails['created_by']							= $UserName;
						$LiveVideoDetails['created_on']							= (string)$Contents->lastModified;
						$LiveVideoDetails['modified_by']						= $UserName;
						$LiveVideoDetails['modified_on']						=( string)$Contents->lastModified;
						*/
						$VideoContentDetails['LiveVideoDetails'] 				= $LiveVideoDetails;

						$Result = $this->migration_model->insert_video_master($VideoContentDetails);
							
						if(isset($Result['content_id']) && $Result['content_id'] != 'NULL' && $Result['content_id'] != '') {
							
							$this->migration_model->insert_ecenic_content( $Contents->articleid,4 ,$Result['content_id'] ,1,$Result['url'],(INT)$Section,(string)$Contents->lastModified); 
							$Success++;	
							
						} else  {
									$LogPath =  source_base_path.'migration_log/video.txt';
									
									$myfile = fopen($LogPath , "a") or die("Unable to open file!");
									$txt = 	"Video >> ".strip_tags($SubObject['TITLE'])."\n";
									$txt .= (string)$Contents->ArticleState."\n";
									$txt .=  $Contents->articleid ."\n";
									$txt .= "------------\n";
									fwrite($myfile, $txt);
									fclose($myfile);
								
								$this->migration_model->insert_ecenic_content( $Contents->articleid,4 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
								$Failure++;
						}
							
							
					} else {
						
							$LogPath =  source_base_path.'migration_log/video_section.txt';
								
								$myfile = fopen($LogPath , "a") or die("Unable to open file!");
								$txt = 	"Video >> ".strip_tags($SubObject['TITLE'])."\n";
								$txt .= (string)$Contents->ArticleState."\n";
								$txt .=  $Contents->articleid ."\n";
								$txt .= "------------\n";
								fwrite($myfile, $txt);
								fclose($myfile);
							
							$this->migration_model->insert_ecenic_content( $Contents->articleid,4 ,'NULL' ,2,(string)urldecode($Contents->ArticleURI),(integer)$Contents->sectionID,(string)$Contents->lastModified);
							$Failure++;
					}
		
					}
				} 
			}
		}
		
		$data['TotalCount']			= 0;

		$data['TotalSuccess']			= 0;
		$data['TotalFailure']			= 0;
		$data['CurrentSuccess']			= 0;
		$data['CurrentFailure']			= 0;

		$data['ArticleTotalCount']			= 0;
		$data['ArticleTotalSuccess']			= 0;
		$data['ArticleTotalFailure']			= 0;
		$data['ArticleCurrentSuccess']			= 0;
		$data['ArticleCurrentFailure']			= 0;
	
		$data['GalleryTotalSuccess']			= 0;
		
		$data['GalleryTotalFailure']			= 0;
		
		$data['GalleryCurrentSuccess']			= 0;
		$data['GalleryCurrentFailure']			= 0;
		
		
		$data['VideoTotalSuccess']			=  $this->migration_model->get_ecenic_content(1,4);
		
		$data['VideoTotalFailure']			=  $this->migration_model->get_ecenic_content(2,4);
		
		$data['VideoCurrentSuccess']			= $Success;
		$data['VideoCurrentFailure']			= $Failure;
		
		$data['title'] 				= 'Import Ecenic';
		$data['template'] 			= 'import_ecenic';
		
		$this->load->view('admin_template', $data); 
	}
	
	public function get_audio() {
		
			$picture_xml_file_path = image_url."/ecenic_images/audio/dn-audio.xml";
		
		$xml = get_object_vars(simplexml_load_file($picture_xml_file_path));
		
		$data['AudioTotalCount']			= count($xml['Content']);
		$Success = 0;
		$Failure = 0;
		
		if($xml != '' && !empty($xml))
		{
			
			$TotalCount = $this->migration_model->total_import_ecenic_content(4);
			 
			$Slice_Content  = array_slice($xml['Content'],0,1);
			
			foreach($Slice_Content as $key=>$Contents) {
				
				/*
				echo "<pre>";
				print_r($Contents);
				exit; 
				*/
		
				if($this->migration_model->check_ecenic_content( $Contents->articleid,5) == 0 && (string)$Contents->ArticleState == 'published') {
					
					$Status 			= "P";
					$UserId 			= USERID;
					
					$Country 			= "NULL";
					$LiveCountryName 	= "";
					
					$City 				= "NULL";
					$LiveCityName  		= "";
					
					$State 				= "NULL";
					$LiveStateName		= "";
					
					$Section 			= "NULL";
					$SectionName    	= "";
					
					$Agency				= "NULL";
					$LiveAgencyName		= "";
					
					$ImageId 			= "NULL";
					$AudioPhysicalPath  = "";
					
					$SubObject 		= (array)simplexml_load_string($Contents->content,'SimpleXMLElement', LIBXML_NOCDATA);
			
					$BinaryData = (array)simplexml_load_string($SubObject['BINARY'],'SimpleXMLElement', LIBXML_NOCDATA);
					
					$AudioPath = urldecode($BinaryData['href']);
					
			
					if(file_exists(source_base_path.'/ecenic_audio/'.$AudioPath)) {
						
						$AudioPath_array = explode("/",$AudioPath);
						
						list($Year,$Month,$Date,$None,$FileName) = $AudioPath_array;
			
						create_image_folder_resource($Year,$Month,$Date,audio_source_path);
						
						$OrginalAudioPath	= source_base_path.'ecenic_audio/'.$AudioPath;
						$DestinationURL 	= destination_base_path.audio_source_path.$Year."/".$Month."/".$Date."/". $FileName;  
						
						copy($OrginalAudioPath , $DestinationURL);
						
						$AudioPhysicalPath = $Year."/".$Month."/".$Date."/". $FileName;
						
					}
					
					if($AudioPhysicalPath != '') {
					
						$Ecenic_Summary = (array)simplexml_load_string(@$SubObject['COM.ESCENIC.SUMMARIES'],'SimpleXMLElement', LIBXML_NOCDATA);
						
						if(isset($SubObject['LASTEDITEDBY']) && $SubObject['LASTEDITEDBY'] != ''){
							$UserDetails  = $this->migration_model->check_username($SubObject['LASTEDITEDBY']);
							if($UserDetails->num_rows() != 0 ) {
								$UserId = $UserDetails->row()->User_id;
							} else {
								$UserId  = $this->migration_model->insert_userdetails($SubObject['LASTEDITEDBY'], $Contents->lastModified );
							}
						} 
						
					
						$SectonDetails = $this->migration_model->check_sectionname($Contents->sectionID);
						
					
						if($SectonDetails->num_rows() != 0 ) {
								$Section = $SectonDetails->row()->Section_id;
						} 
						
					
						if($Section != "NULL") {
						
						
							if(isset($SubObject['LOCATION']) && $SubObject['LOCATION'] != '') {
								$LocationArray = explode('/',$SubObject['LOCATION']);
							
								$LocationArray = explode("(", $LocationArray[0]);
								$CityName = trim($LocationArray[0]);
								
								$CityDetails = $this->migration_model->get_city_statedetails($CityName)->row();
								
								if(isset($CityDetails->City_id) && $CityDetails->City_id != '') {
								$City 				= $CityDetails->City_id;
								$LiveCityName    	= $CityName;
								}
							
								if(isset($CityDetails->State_Id) && $CityDetails->State_Id != '') {
								$State 				= $CityDetails->State_Id;
								$LiveStateName    	= @get_statename_by_id($State);
								}
							
								if(isset($CityDetails->Country_id) && $CityDetails->Country_id != '') {
								$Country 			= $CityDetails->Country_id;
								$LiveCountryName 	= @get_countryname_by_id($Country);
								}
							}
							
							/*
							if(isset($SubObject['BYLINE']) && $SubObject['BYLINE'] != '') {
								
								$ExplodeByline = explode(" | ",$SubObject['BYLINE']);
								
								if(count($ExplodeByline) == 2) {
								$AgencyName = $ExplodeByline[1];
								$AuthorName = $ExplodeByline[0];
								} else {
								$AgencyName = 	$SubObject['BYLINE'];
								}
								
							
								if(trim(isset($AgencyName)) != '' ) {
								
									$AgencyDetails  = $this->migration_model->check_agencyname(trim($AgencyName));
											
											if($AgencyDetails->num_rows() != 0 ) {
												$Agency 		= $AgencyDetails->row()->Agency_id;
												$LiveAgencyName = trim($AgencyName);
											} else {
												$Agency  = $this->migration_model->insert_agencydetails($AgencyName, (string)$Contents->lastModified, $UserId);
										}	
									}
							}
							*/
						
							if(isset($Ecenic_Summary['previewrel']) && !(empty($Ecenic_Summary['previewrel']))) {
									
									$PictureContent = @(array)$Ecenic_Summary['previewrel'];
							
									if(isset($PictureContent['content-summary'][0]->link->href)) {
										$Picture = $PictureContent['content-summary'][0];
										$PictureHref = $Picture->link->href;
										$PictureTitleArray =  $Picture->link->title;
									
									$PictureIdArray = @explode("/",$PictureHref[0]);
									$PictureId = $PictureIdArray[2];
									$PictureTitle = $PictureTitleArray[0];
									
									if($PictureId != '') {
										$ImageDetails = $this->migration_model->get_content_by_ecenic_id($PictureId,2);
									
										if($ImageDetails->num_rows() != 0)
											 $ImageId = $ImageDetails->row()->content_id;	
									}
									
										if($PictureId != '' && $ImageId == 'NULL') {
											
											$LogPath =  source_base_path.'migration_log/audio_image.txt';
											
											$myfile = fopen($LogPath , "a") or die("Unable to open file!");
											$txt = 	"video Image >> ".strip_tags($PictureTitle)."\n";
											$txt .=  $PictureId ."\n";
											$txt .= "------------\n";
											fwrite($myfile, $txt);
											fclose($myfile);
										}
										
									}
							}
							
		
							if(date('Y', strtotime((string)$Contents->publishDate)) < 2009) {
								$EcenicPublishDate = (string)$Contents->lastModified;
							} else {
								$EcenicPublishDate = (string)$Contents->publishDate;
							}
						
							$Year =  date('Y', strtotime($EcenicPublishDate));
							$Month =  date('m', strtotime($EcenicPublishDate));
							$Date =  date('d', strtotime($EcenicPublishDate));
							
							$ArticleURI 	= explode("/",(string)$Contents->ArticleURI);
							$url_structure 	= $ArticleURI[3];
						
							if(trim($url_structure) == '') {
								$url_structure = strip_tags($SubObject['TITLE']);
								$url_structure = RemoveSpecialCharacters(@$url_structure);
								$url_structure = mb_strtolower(join( "-",( explode(" ",$url_structure) ) ));
							}
							
							$MainSection = get_section_by_id($Section);
							
							
							$AudioContentDetails['ecenic_id'] 					= (string)$Contents->articleid;
							$AudioContentDetails['url_title'] 					=  str_replace("-"," ",$url_structure);
							$AudioContentDetails['title'] 						=  $SubObject['TITLE'];
							
							//$AudioContentDetails['url']  						= join( "-",( explode(" ",@$MainSection['URLSectionStructure'] )))."/".str_replace(" ","-",$url_structure) ."/".$Year."/".$Month."/".$Date;
							
							$AudioContentDetails['url'] 						= mb_strtolower(join( "-",( explode(" ",@$MainSection['URLSectionStructure'] ))))."/".$Year."/".mb_strtolower($Month)."/".$Date."/".$url_structure;
							
							$AudioContentDetails['summaryHTML'] 				= (string)@$SubObject['LEADTEXT'];
							$AudioContentDetails['publish_start_date'] 			= $EcenicPublishDate;
							$AudioContentDetails['Tags'] 						= '';
							$AudioContentDetails['MetaTitle'] 					=(strip_tags($SubObject['TITLE']));
							$AudioContentDetails['MetaDescription'] 			= (strip_tags($SubObject['TITLE']));
							$AudioContentDetails['Noindexed'] 					= 1;
							$AudioContentDetails['Nofollow'] 					= 1;
							$AudioContentDetails['Canonicalurl'] 				= '';
							$AudioContentDetails['Allowcomments'] 				= 1;
							$AudioContentDetails['Section_id'] 					= $Section;
							$AudioContentDetails['Agency_ID'] 					= $Agency;
							$AudioContentDetails['Country_ID'] 					= $Country;
							$AudioContentDetails['State_ID'] 					= $State;
							$AudioContentDetails['City_ID'] 					= $City;
							$AudioContentDetails['image_id']					= $ImageId;
							$AudioContentDetails['Audio_path']					= addslashes($AudioPhysicalPath);
							$AudioContentDetails['status'] 						= $Status;
							$AudioContentDetails['Createdby'] 					= $UserId;
							$AudioContentDetails['Createdon'] 					= (string)$Contents->lastModified;
							$AudioContentDetails['Modifiedby'] 					= $UserId;
							$AudioContentDetails['Modifiedon'] 					= (string)$Contents->lastModified;
							
							$AudioContentDetails['GalleryRelatedImages'] 		=  $ImageId;
								
							# Start the Live Audio Table Details 
						
							$LiveAudioDetails = array();
							
							$LiveAudioDetails['ecenic_id'] 							= $AudioContentDetails['ecenic_id'] ;
							$LiveAudioDetails['section_id'] 						= $AudioContentDetails['Section_id'] ;
							$LiveAudioDetails['section_name'] 						= @$MainSection['Sectionname'];
							$LiveAudioDetails['parent_section_id'] 					= 'NULL';
							$LiveAudioDetails['parent_section_name'] 				= '';
							$LiveAudioDetails['grant_section_id'] 					= 'NULL';
							$LiveAudioDetails['grant_parent_section_name'] 			= '';
										
								if(isset($MainSection['ParentSectionID']) && $MainSection['ParentSectionID'] != '') {
									
									$ParentMainSection = get_section_by_id($MainSection['ParentSectionID']);
									
									if(isset($ParentMainSection['Section_id'])) {
									$LiveAudioDetails['parent_section_id'] 						= 	$ParentMainSection['Section_id'];
									$LiveAudioDetails['parent_section_name'] 						= 	$ParentMainSection['Sectionname'];
									}
									
									if(isset($ParentMainSection['ParentSectionID']) && $ParentMainSection['ParentSectionID'] != '') {
									

										$GrantMainSection = get_section_by_id($ParentMainSection['ParentSectionID']);
										
										if(isset($GrantMainSection['Section_id'])) {
										$LiveAudioDetails['grant_section_id'] 						= 	$GrantMainSection['Section_id'];
										$LiveAudioDetails['grant_parent_section_name'] 				= 	$GrantMainSection['Sectionname'];
										}
									}
									
								}	
								
							$LiveAudioDetails['tag_ids']					= '';
							$LiveAudioDetails['agency_id'] 					= $Agency;
							$LiveAudioDetails['country_id'] 				= $Country;
							$LiveAudioDetails['state_id'] 					= $State;
							$LiveAudioDetails['city_id'] 					= $City;
							$LiveAudioDetails['image_id']					= $ImageId;
							
							$LiveAudioDetails['audio_image_path'] 					= '';
							$LiveAudioDetails['audio_image_title'] 					= '';
							$LiveAudioDetails['audio_image_alt'] 					= '';
							
							if($ImageId != 'NULL') {
						
								$AudioImageDetails = GetImageDetailsByContentId($ImageId);
								
								if(isset($AudioImageDetails['ImagePhysicalPath'])) {
									$LiveAudioDetails['audio_image_path'] 			= addslashes($AudioImageDetails['ImagePhysicalPath']);
									$LiveAudioDetails['audio_image_title'] 			= addslashes($AudioImageDetails['ImageCaption']);
									$LiveAudioDetails['audio_image_alt'] 			= addslashes($AudioImageDetails['ImageAlt']);
								}
							}
							
							$LiveAudioDetails['url'] 								= $AudioContentDetails['url'];
							$LiveAudioDetails['hits']								= 0;
							$LiveAudioDetails['tags']								= '';
							$LiveAudioDetails['allow_comments']						= 1;
							$LiveAudioDetails['agency_name'] 						= $LiveAgencyName;
							$LiveAudioDetails['country_name'] 						= $LiveCountryName;
							$LiveAudioDetails['state_name'] 						= $LiveStateName;
							$LiveAudioDetails['city_name'] 							= $LiveCityName;
							$LiveAudioDetails['no_indexed']							= 1;
							$LiveAudioDetails['no_follow']							= 1;
							$LiveAudioDetails['status'] 							= $Status;
							
							$LiveAudioDetails['publish_start_date']  = '';
							
							if($AudioContentDetails['publish_start_date'] != '')
							$LiveAudioDetails['publish_start_date'] 					= date('Y-m-d H:i', strtotime($AudioContentDetails['publish_start_date']));
									
							$LiveAudioDetails['last_updated_on'] 						= (string)$Contents->lastModified;	
							$LiveAudioDetails['title'] 									= $AudioContentDetails['title'] ;
							$LiveAudioDetails['summary_html'] 							= $AudioContentDetails['summaryHTML'];
							$LiveAudioDetails['audio_path'] 							= $AudioContentDetails['Audio_path'];
							$LiveAudioDetails['canonical_url']  						= $AudioContentDetails['Canonicalurl'];
							$LiveAudioDetails['meta_Title']  							= $AudioContentDetails['MetaTitle'];
							$LiveAudioDetails['meta_description']  						= $AudioContentDetails['MetaDescription'];
						
							$UserName 											= ($UserId != USERID) ? get_userdetails_by_id($UserId) : "";
							
							$LiveAudioDetails['created_by']							= $UserName;
							$LiveAudioDetails['created_on']							= (string)$Contents->lastModified;
							$LiveAudioDetails['modified_by']						= $UserName;
							$LiveAudioDetails['modified_on']						=( string)$Contents->lastModified;
						
							$AudioContentDetails['LiveAudioDetails'] = $LiveAudioDetails;
							
							$Result = $this->migration_model->insert_audio_master($AudioContentDetails);
								
							if(isset($Result['content_id']) && $Result['content_id'] != 'NULL' && $Result['content_id'] != '') {
								
								$this->migration_model->insert_ecenic_content( $Contents->articleid,5 ,$Result['content_id'] ,1,$Result['url'],(INT)$Section,(string)$Contents->lastModified); 
								$Success++;	
								
							} else  {
										$LogPath =  source_base_path.'migration_log/audio.txt';
										
										$myfile = fopen($LogPath , "a") or die("Unable to open file!");
										$txt = 	"Audio >> ".strip_tags($SubObject['TITLE'])."\n";
										$txt .= (string)$Contents->ArticleState."\n";
										$txt .=  $Contents->articleid ."\n";
										$txt .= "------------\n";
										fwrite($myfile, $txt);
										fclose($myfile);
									
									$this->migration_model->insert_ecenic_content( $Contents->articleid,5 ,'NULL' ,2,(string)$Contents->ArticleURI,(integer)$Contents->sectionID,(string)$Contents->lastModified);
									$Failure++;
							}
								
								
						} else {
							
								$LogPath =  source_base_path.'migration_log/audio_section.txt';
									
									$myfile = fopen($LogPath , "a") or die("Unable to open file!");
									$txt = 	"Audio >> ".strip_tags($SubObject['TITLE'])."\n";
									$txt .= (string)$Contents->ArticleState."\n";
									$txt .=  $Contents->articleid ."\n";
									$txt .= "------------\n";
									fwrite($myfile, $txt);
									fclose($myfile);
								
								$this->migration_model->insert_ecenic_content( $Contents->articleid,5 ,'NULL' ,2,(string)$Contents->ArticleURI,(integer)$Contents->sectionID,(string)$Contents->lastModified);
								$Failure++;
						}
						
					} else {
						
						$LogPath =  source_base_path.'migration_log/audio.txt';
								
								$myfile = fopen($LogPath , "a") or die("Unable to open file!");
								$txt = 	"Audio >> ".strip_tags($SubObject['TITLE'])."\n";
								$txt .= (string)$Contents->ArticleState."\n";
								$txt .=  $Contents->articleid ."\n";
								$txt .= "------------\n";
								fwrite($myfile, $txt);
								fclose($myfile);
							
							$this->migration_model->insert_ecenic_content( $Contents->articleid,5 ,'NULL' ,2,(string)$Contents->ArticleURI,(integer)$Contents->sectionID,(string)$Contents->lastModified);
							$Failure++;
						
					}
		
				} else {
					
					if( (string)$Contents->ArticleState != 'published') {
					
						$LogPath =  source_base_path.'migration_log/audio.txt';
							
							$myfile = fopen($LogPath , "a") or die("Unable to open file!");
							$txt = 	"Audio >> ".strip_tags($SubObject['TITLE'])."\n";
							$txt .= (string)$Contents->ArticleState."\n";
							$txt .=  $Contents->articleid ."\n";
							$txt .= "------------\n";
							fwrite($myfile, $txt);
							fclose($myfile);
						
						$this->migration_model->insert_ecenic_content( $Contents->articleid,5 ,'NULL' ,2,(string)$Contents->ArticleURI,(integer)$Contents->sectionID,(string)$Contents->lastModified);
						$Failure++;
						
					}
				}
			}
		}
		
		$data['TotalCount']			= 0;

		$data['TotalSuccess']			= 0;
		$data['TotalFailure']			= 0;
		$data['CurrentSuccess']			= 0;
		$data['CurrentFailure']			= 0;

		$data['ArticleTotalCount']			= 0;
		$data['ArticleTotalSuccess']			= 0;
		$data['ArticleTotalFailure']			= 0;
		$data['ArticleCurrentSuccess']			= 0;
		$data['ArticleCurrentFailure']			= 0;
	
		$data['GalleryTotalSuccess']			= 0;
		
		$data['GalleryTotalFailure']			= 0;
		
		$data['GalleryCurrentSuccess']			= 0;
		$data['GalleryCurrentFailure']			= 0;
		
		$data['AudioTotalSuccess']			=  $this->migration_model->get_ecenic_content(1,5);
		
		$data['AudioTotalFailure']			=  $this->migration_model->get_ecenic_content(2,5);
		
		$data['AudioCurrentSuccess']			= $Success;
		$data['AudioCurrentFailure']			= $Failure;
		
		$data['title'] 				= 'Import Ecenic';
		$data['template'] 			= 'import_ecenic';
		
		$this->load->view('admin_template', $data); 
		
	} 


	public function create_image_folder_structure($year,$month,$date,$time) {
		
	
		
			if(!file_exists(destination_base_path.imagelibrary_image_path.$year)) {
				mkdir(destination_base_path.imagelibrary_image_path.$year,0777);
				chmod(destination_base_path.imagelibrary_image_path.$year, 0777);
			}
			
			if(!file_exists(destination_base_path.imagelibrary_image_path.$year."/".$month)) {
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month,0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month, 0777);
			}
			
			
			if(!file_exists(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date)) {
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date,0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date, 0777);	
			}
			
			if(isset($time) && $time != '') {
			
					if(!file_exists(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time)) {
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time,0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time, 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/original",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/original", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w600X390",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w600X390", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w600X300",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w600X300", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w150X150",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w150X150", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w100X65",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/".$time."/w100X65", 0777);
					
					}
					
			} else {
					
					
					if(!file_exists(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date)) {
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/original",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/original", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X390",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X390", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X300",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X300", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w150X150",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w150X150", 0777);
					
					mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w100X65",0777);
					chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w100X65", 0777);
					
					}
					
				}
			
	}

}
/* End of file migration.php */
/* Location: ./application/controllers/dmcpan/migration.php */
