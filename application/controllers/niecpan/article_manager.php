<?php
/**
 * Article Manager Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Article_manager extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/common_model');
		$this->load->model('admin/article_model');
		$this->load->model('admin/image_model');
		$this->load->model('admin/article_image_model');
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
	}
	public function index()

	{

				$content_type 		= "Article Manager";
				$button_name		= "Create Article"; 
				$addPage_url 		= folder_name."/article";
				$menu_name			= "Article";
				
				$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);

		
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) {
			
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			
			$data['title']				= $content_type;
			$data['btn_name']			= $button_name;
			$data['addPage_url']		= $addPage_url;
			
			$data['template'] 	= 'article_manager';
			$this->load->view('admin_template',$data);
			
		}
				
	}
	public function article_datatable()
	{
		$this->article_model->get_article_datatables();
	}
	public function edit_article($article_id)

	{
		

		
		$data['Menu_id'] = get_menu_details_by_menu_name('Article');
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
		
		$article_id = base64_decode(urldecode($this->uri->segment('3')));	
		
		if (check_article_id($article_id) != 0)
		{
			$data['get_article_details'] 	= $this->article_model->get_article_details($article_id)->row_array();
			$get_related_article 			= $this->article_model->get_related_article_by_articleid($article_id)->result_array();
			$tags 			= $data['get_article_details']['Tags'];
			$agency_id 		= $data['get_article_details']['Agency_ID'];
	
			if(isset($tags) && trim($tags) != '') 
			$data['get_tags']				= $this->common_model->get_tags_by_id($tags);
			
			$country_id 		= $data['get_article_details']['Country_ID'];
			$state_id 			= $data['get_article_details']['State_ID'];
			$city_id 			= $data['get_article_details']['City_ID'];
			$author_id			= $data['get_article_details']['Author_ID'];
			$section_id 		= $data['get_article_details']['Section_id'];
			$home_image_id  	= $data['get_article_details']['homepageimageid'];
			$section_image_id  	= $data['get_article_details']['Sectionpageimageid'];
			$article_image_id  	= $data['get_article_details']['articlepageimageid'];
			
			/*
			foreach($get_related_article as $key => $get_article)
			{
				$get_related_id = $get_article['Related_content_id'];
				if ($get_related_id != '')
				{
					$get_article['internal_article'] = $this->article_model->get_article_details($get_related_id)->result_array();
					$get_related_article[$key] = $get_article;
				}
			} 
			*/
		
			$data['get_related_article'] 	= $get_related_article;
			$data['mapping_section'] 		= '';
			
			If($author_id != '' && $author_id != 0 )
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
		
			$data['get_country'] 			= $this->common_model->get_country_details();
			
			$data['get_agency'] 			= $this->common_model->get_agency_details();
			$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
			$data['json_section_mapping'] 	= json_encode($data['section_mapping']);
			$data['get_content_type']		= $this->common_model->get_content_type();
			$data['image_library'] 			= $this->article_image_model->get_image_library();
			
			//$this->image_model->DeleteTempAllImages(1);

			if($home_image_id != ''  && $home_image_id != 0 )
			$data['get_article_details']['homepageimageid']  = MoveDataAndImageToTemp($home_image_id);
			else 
			$data['get_article_details']['homepageimageid']  = '';
		
			if($section_image_id != ''  && $section_image_id != 0)
			$data['get_article_details']['Sectionimageid']  = MoveDataAndImageToTemp($section_image_id);
			else 
			$data['get_article_details']['Sectionimageid']  = '';
		
			if($article_image_id != ''  && $article_image_id != 0)
			$data['get_article_details']['articleimageid']  = MoveDataAndImageToTemp($article_image_id);
			else 
			$data['	']['articleimageid']  = '';
		
			/*
			echo "<pre>";
			print_r($data);
			exit;
			*/
			
			$data['title'] 					= 'Edit Article';
			$data['template'] 				= 'article';
			$this->load->view('admin_template', $data);
		}
		else
		{
			redirect(folder_name.'/article_manager');
		}
		} else {
				redirect(folder_name.'/common/access_permission/edit_article');
		}
	}
	
	public function edit_archive_article($year,$article_id)

	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Article');
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
		
		$article_id = base64_decode(urldecode($article_id));	
		
			$this->archive_db->select("*,tag_ids as Tags,agency_id as Agency_ID, author_id as Author_ID,country_id as Country_ID, state_id as State_ID, city_id as City_ID, sectionpageimageid as Sectionpageimageid, canonical_url as Canonicalurl,allow_comments as Allowcomments,no_indexed as Noindexed, no_follow as Nofollow,summary_html as summaryHTML, article_page_content_html as ArticlePageContentHTML,meta_title as MetaTitle, meta_description as MetaDescription, section_id as Section_id,created_by as Createdby, modified_by as Modifiedby, created_on as Createdon, modified_on as Modifiedon");
			$this->archive_db->from("article_".$year);
			$this->archive_db->where("content_id",$article_id);
			$Get = $this->archive_db->get();
		
			$data['get_article_details'] 	= $Get->row_array();
		
			$this->archive_db->select("*,contenttype as contentType, related_content_id as Related_content_id, related_articletitle as 	ExternalArticletitle, related_articleurl as ExternalArticleURL, display_order as DisplayPriorty");
			$this->archive_db->from("relatedcontent_".$year);
			$this->archive_db->where("content_id",$article_id);
			$Get = $this->archive_db->get();
		
			$get_related_article 			= $Get->result_array();
			$tags 			= $data['get_article_details']['Tags'];
			$agency_id 		= $data['get_article_details']['Agency_ID'];
	
			if(isset($tags) && trim($tags) != '') 
			$data['get_tags']				= $this->common_model->get_tags_by_id($tags);
			
			$country_id 		= $data['get_article_details']['Country_ID'];
			$state_id 			= $data['get_article_details']['State_ID'];
			$city_id 			= $data['get_article_details']['City_ID'];
			$author_id			= $data['get_article_details']['Author_ID'];
			$section_id 		= $data['get_article_details']['Section_id'];
			$home_image_id  	= $data['get_article_details']['homepageimageid'];
			$section_image_id  	= $data['get_article_details']['Sectionpageimageid'];
			$article_image_id  	= $data['get_article_details']['articlepageimageid'];
			
			$url_array = explode("/",$data['get_article_details']['url']);
			$GetTitleFromURL = explode(".",end($url_array));
			
			$data['get_article_details']['url_title'] = str_replace("-".$article_id,"",$GetTitleFromURL[0]);
			
			/*
			foreach($get_related_article as $key => $get_article)
			{
				$get_related_id = $get_article['Related_content_id'];
				if ($get_related_id != '')
				{
					$get_article['internal_article'] = $this->article_model->get_article_details($get_related_id)->result_array();
					$get_related_article[$key] = $get_article;
				}
			} 
			*/
		
			$data['get_related_article'] 	= $get_related_article;
			$data['mapping_section'] 		= '';
			
			If($author_id != '' && $author_id != 0 )
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
		
			$data['get_country'] 			= $this->common_model->get_country_details();
			
			$data['get_agency'] 			= $this->common_model->get_agency_details();
			$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
			$data['json_section_mapping'] 	= json_encode($data['section_mapping']);
			$data['get_content_type']		= $this->common_model->get_content_type();
			$data['image_library'] 			= $this->article_image_model->get_image_library();
			
			//$this->image_model->DeleteTempAllImages(1);

			if($home_image_id != '' && $home_image_id != 0)
			$data['get_article_details']['homepageimageid']  = MoveDataAndImageToTemp($home_image_id);
			else 
			$data['get_article_details']['homepageimageid']  = '';
		
			if($section_image_id != ''  && $section_image_id != 0)
			$data['get_article_details']['Sectionimageid']  = MoveDataAndImageToTemp($section_image_id);
			else 
			$data['get_article_details']['Sectionimageid']  = '';
		
			if($article_image_id != ''  && $article_image_id != 0)
			$data['get_article_details']['articleimageid']  = MoveDataAndImageToTemp($article_image_id);
			else 
			$data['	']['articleimageid']  = '';
		
			/*
			echo "<pre>";
			print_r($data);
			exit;
			*/
			$data['archive_year']           = $year;
			$data['title'] 					= 'Edit Archive Article';
			$data['template'] 				= 'article';
			$this->load->view('admin_template', $data);
		
		} else {
				redirect(folder_name.'/common/access_permission/edit_article');
		}
	}
	
	public function update_article($article_id)

	{

		if (check_article_id($article_id) != 0)
		{
			$this->form_validation->set_rules('txtArticleHeadLine', 'Article Head Line', 'required|trim');
			$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim');
					
			if ($this->input->post('txtStatus') != 'D')
			{
				$this->form_validation->set_rules('txtMetaTitle', 'Meta Title', 'required|trim');
				$this->form_validation->set_rules('txtBodyText', 'Body Text', 'required|trim');
			}
			if ($this->form_validation->run() == FALSE)
			{
				
				redirect(folder_name."/edit_article/" . urlencode(base64_encode($article_id)));
			}
			else
			{
				$this->article_model->update_article($article_id);
				$this->session->set_flashdata('success', 'Article Updated Successfully');
				redirect(folder_name.'/article_manager');
			}
		}
		else
		{
			redirect(folder_name.'/article_manager');
		}
	}
	
		public function update_archive_article($year,$article_id)

	{

			$this->form_validation->set_rules('txtArticleHeadLine', 'Article Head Line', 'required|trim');
			$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim');
					
			if ($this->input->post('txtStatus') != 'D')
			{
				$this->form_validation->set_rules('txtMetaTitle', 'Meta Title', 'required|trim');
				$this->form_validation->set_rules('txtBodyText', 'Body Text', 'required|trim');
			}
			if ($this->form_validation->run() == FALSE)
			{
				
				redirect(folder_name."/edit_archive_article/".$year."/". urlencode(base64_encode($article_id)));
			}
			else
			{
				$this->article_model->update_archive_article($year,$article_id);
				$this->session->set_flashdata('success', 'Article Updated Successfully');
				redirect(folder_name.'/article_manager');
			}
	}
	public function get_astrology_preview_popup()
    {
		extract($_POST);
		$data['body_text']			= urldecode($body_text);
		//$data['date']			    = $date;
		$data['rasi_name']			= $rasi_name;
		$data['menu_name']			= $menu_name;
		
		//$data['url_structure']      = GenerateBreadCrumbBySectionId($section_id);
		echo $this->load->view('admin/astrology_preview_popup',$data);
	}
	public function get_numerology_preview_popup()
    {
		extract($_POST);
		$data['body_text']			= urldecode($body_text);
		//$data['date']			    = $date;
		$data['number_name']	    = $number;
		$data['menu_name']			= $menu_name;
		//$data['url_structure']      = GenerateBreadCrumbBySectionId($section_id);
		echo $this->load->view('admin/numerology_preview_popup',$data);
	}
	
	public function get_article_preview_popup()

	{
		

		extract($_POST);
		$data['related_article'] 	= json_decode($related_article);
		$data['content_type']		= 1;
		$head_line 					= urldecode($head_line);
		$data['body_text']			= urldecode($body_text);
		$data['tags']				= json_decode($tags);
		$data['caption']			= urldecode($article_image_caption);
		$data['article_image']		= '';
		$data['author_name'] 		= $AuthorName;
		$data['byliner'] 			= $Byliner;
		
		if($publishdate != '') 
		$data['publishdate']    	= date('dS  F Y h:i A',strtotime($publishdate));
		else 
		$data['publishdate']    	= date('dS  F Y h:i A');	
		
		if($last_update != '')
		$data['last_update']    	= date('dS  F Y h:i A',strtotime($last_update));
		else 
		$data['last_update']    	=  date('dS  F Y h:i A');	

		if($section_id != '') {
	
				$section_id = urldecode($section_id);
				$section_details = get_section_by_id($section_id);
				$data['url_structure'] = GenerateBreadCrumbBySectionId($section_id);
		
				if($AuthorName != '' && $Byliner != '') {
					$data['author_name'] = $Byliner.' | '.$AuthorName;
				} elseif($AuthorName == '' && $Byliner != '') {
					$data['author_name'] = $Byliner;
				} elseif($AuthorName != '' && $Byliner == ''){
					$data['author_name'] = $AuthorName;
				}
		
				/*	$AuthorID = $section_details['AuthorID'];
					$author_id = $author_id;
					if($AuthorID !='NULL' && $AuthorID !=''){
						$data['author_name'] = get_authorname_by_id($section_details['AuthorID']);
					}elseif($author_id!=''){
						$data['author_name']= get_authorname_by_id($author_id);	
						if($data['author_name'] ==''){
							$agency_id = $agency_id;
							if($agency_id!=''){
								$agency_det =  get_agency_by_id($agency_id);
								$data['author_name'] = $agency_det['Agency_name'];
							}else{
								$data['author_name'] = '';
							}
						}
					}else{
						$agency_id = $agency_id;
						if($agency_id!=''){
						$data['author_name'] = get_agency_by_id($agency_id);
						}else{
						$data['author_name'] = '';
						}
					} */
	}
		
		 $head_line = str_replace("<p","<span",$head_line);
		 $head_line = str_replace("</p>","</span>",$head_line);
		 
		$data['article_headline'] = $head_line;
			
			if($article_image_id != '') {
			
			$ImageDetails 			 = $this->image_model->get_image($article_image_id);
			
			if(@$ImageDetails['image_name'] !='' && file_exists(source_base_path . article_temp_image_path .@$ImageDetails['image_name'])) {
				$details = getimagesize(source_base_path . article_temp_image_path.$ImageDetails['image_name']);
				$imagewidth = $details[0];
				$imageheight = $details[1];	
					
				if(folder_name == 'dmcpan')	{
					$data['article_image'] 	= image_url . article_temp_image_path.$ImageDetails['image_name'];
				} else {
					
				if ($imageheight > $imagewidth)
				{
					$data['article_image'] 	= image_url . article_temp_image_path.$ImageDetails['image_name'];
				} else if(file_exists(source_base_path.article_temp_image_path.str_replace('.','_600_390.',@$ImageDetails['image_name']))) {
					
					$data['article_image']	 = image_url.article_temp_image_path.str_replace('.','_600_390.',@$ImageDetails['image_name']);
				} else {
			
						if(isset($ImageDetails['image_name'])) {
					
							$image_name  		= $ImageDetails['image_name'];
							$ImagePath 			= source_base_path.article_temp_image_path;
							
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
							
						
			//	$result = imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
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
										
										update_temp_images( $content_id, 'Y', $ImageDetails['caption'], $ImageDetails['alt_tag'], addslashes($image_binary_file1) ,$Null_value,$Null_value,$Null_value,1,$Null_value,$Null_value,$Null_value, $modifiedon, $article_image_id,'image_600_390');
										
										$data['article_image'] = image_url.article_temp_image_path.str_replace('.','_600_390.',$ImageDetails['image_name']);
										
									}
								}
								
						}
						
						
					}
				}
			}
			
			}
			
	}
		
		
		/*echo "<pre>";
		print_r($data);
		exit; */
		
		echo $this->load->view('admin/article_preview_popup',$data);
	}
	
}
/* End of file article.php */
/* Location: ./application/controllers/article_manager.php */