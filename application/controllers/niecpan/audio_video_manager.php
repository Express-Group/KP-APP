<?php
/**
 * Audio_video_manager Manager Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Audio_video_manager extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/audio_video_model');
		$this->load->model('admin/Common_model');
		$this->load->model('admin/image_model');
		$this->load->model('admin/article_image_model');
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
	}
	
	/*
	*
	* Audio Video Manager page index function
	*
	* @access PUBLIC
	* @param NULL
	* @return View Gallery Manager page
	*
	*/
	
	
	public function audio_video()

	{
		
		if($this->uri->segment('2') == 'video_manager') {
		
		$content_type 		= "Video Manager";
		$button_name		= "Create Video"; 
		$addPage_url 		= folder_name."/video";
		$menu_name			= "Video";
		
		} else {
			
		$content_type 		= "Audio Manager";
		$button_name		= "Create Audio"; 
		$addPage_url 		= folder_name."/audio";
		$menu_name			= "Audio";
		}
		
		$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);

		
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) {
			
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			
			$data['title']				= $content_type;
			$data['btn_name']			= $button_name;
			$data['addPage_url']		= $addPage_url;
			
			$data['template'] 	= 'audio_video_manager';
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
	
	public function edit_audio_video() {
		

		
		if($this->uri->segment('2') == 'edit_video') {
			$data['content_type']			= 4;
			$data['content_name']			= 'Video';
			$data['title'] 					= 'Edit Video';
			$data['page_name'] 				= 'edit_video';
			$DestinationURL					= video_temp_image_path;
		} else  {
			$data['content_type']			= 5;			
			$data['content_name']			= 'Audio';
			$data['title'] 					= 'Edit Audio';
			$data['page_name'] 				= 'edit_audio';
			$DestinationURL					= audio_temp_image_path;
		}
		
		$content_id = base64_decode(urldecode($this->uri->segment('3')));
		
		$data['Menu_id'] = get_menu_details_by_menu_name($data['content_name']);
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
		
			if ($this->image_model->DeleteTempAllImages($data['content_type']))
			{
				$data['get_audio_video_details'] 	= $this->audio_video_model->get_audio_video_details($content_id,$data['content_type'])->row_array();
				
				$resource_image_id  			= $data['get_audio_video_details']['image_id'];
				
					if($resource_image_id != '') {
				
						$OldTempName 		= $data['get_audio_video_details']['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
						$TempName = GenerateNewImageName($OldTempName, $NewImageName);
							
						ImageLibraryCopyToTemp($OldTempName,$TempName, $SourceURL,$DestinationURL);
						$PhysicalName = GetPhysicalNameFromPhysicalPath($data['get_audio_video_details']['ImagePhysicalPath']);
					
						$createdon 		= $modifiedon = date('Y-m-d H:i:s');
						
						$data['get_audio_video_details']['image_details'] = $this->image_model->addimages(USERID, $data['get_audio_video_details']['image_id'],$data['content_type'], $data['get_audio_video_details']['ImageCaption'], $data['get_audio_video_details']['ImageAlt'], $PhysicalName,$TempName, $data['get_audio_video_details']['Image1Type'], $data['get_audio_video_details']['Image2Type'], $data['get_audio_video_details']['Image3Type'], $data['get_audio_video_details']['Image4Type'], 0);
				
				} else  {
				$data['get_audio_video_details']['audio_video_image_id']  = '';
				}
				
				$country_id 					= $data['get_audio_video_details']['Country_ID'];
				$state_id						= $data['get_audio_video_details']['State_ID'];
				$city_id 						= $data['get_audio_video_details']['City_ID'];
				$agency_id 						= $data['get_audio_video_details']['Agency_ID'];
				$tags 							= $data['get_audio_video_details']['Tags'];
				$author_id						= $data['get_audio_video_details']['Author_ID'];
				
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
	
				$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
				$data['image_library'] 			= $this->article_image_model->get_image_library();
				
				$data['template'] 				= 'audio_video';
			
				
				/*
				echo "<pre>";
				print_r($data);
				exit;
				*/
				
				$this->load->view('admin_template', $data);
			}
		
		} else {
			redirect(folder_name.'/common/access_permission/edit_audio');
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
	
	public function edit_archive_audio_video($content_type, $year,$content_id) {
		
		if($this->uri->segment('2') == 'edit_archive_video') {
			$data['content_type']			= 4;
			$data['content_name']			= 'Video';
			$data['title'] 					= 'Edit Archive Video';
			$data['page_name'] 				= 'edit_video';
			$DestinationURL					= video_temp_image_path;
		} else  {
			$data['content_type']			= 5;			
			$data['content_name']			= 'Audio';
			$data['title'] 					= 'Edit Archive Audio';
			$data['page_name'] 				= 'edit_audio';
			$DestinationURL					= audio_temp_image_path;
		}
		
		$content_id = base64_decode(urldecode($content_id));
		
		$data['Menu_id'] = get_menu_details_by_menu_name($data['content_name']);
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) {
		
			if ($this->image_model->DeleteTempAllImages($data['content_type']))
			{
				if($data['content_type'] == 4) {
					$this->archive_db->select("*,tag_ids as Tags,agency_id as Agency_ID,author_id as Author_ID,country_id as Country_ID, state_id as State_ID, city_id as City_ID,image_id,video_script as VideoScript,video_site as VideoSite,canonical_url as Canonicalurl,allow_comments as Allowcomments,no_indexed as Noindexed, no_follow as Nofollow,summary_html as summaryHTML ,meta_title as MetaTitle, meta_description as MetaDescription, section_id as Section_id,created_by as Createdby, modified_by as Modifiedby, created_on as Createdon, modified_on as Modifiedon");
					$this->archive_db->from("video_".$year);
					$this->archive_db->where("content_id",$content_id);
					$Get = $this->archive_db->get();
				
					$data['get_audio_video_details'] 	= $Get->row_array();
				} else {
					$this->archive_db->select("*,tag_ids as Tags,agency_id as Agency_ID,author_id as Author_ID,country_id as Country_ID, state_id as State_ID, city_id as City_ID,image_id,audio_path as Audio_path,canonical_url as Canonicalurl,allow_comments as Allowcomments,no_indexed as Noindexed, no_follow as Nofollow,summary_html as summaryHTML ,meta_title as MetaTitle, meta_description as MetaDescription, section_id as Section_id,created_by as Createdby, modified_by as Modifiedby, created_on as Createdon, modified_on as Modifiedon");
					$this->archive_db->from("audio_".$year);
					$this->archive_db->where("content_id",$content_id);
					$Get = $this->archive_db->get();
				
					$data['get_audio_video_details'] 	= $Get->row_array();
				}
				
				$resource_image_id  			= $data['get_audio_video_details']['image_id'];
				
					if($resource_image_id != '') {
						
						$ImageDetails = GetImageDetailsByContentId($resource_image_id);	
				
						$OldTempName 		= $ImageDetails['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
						$TempName = GenerateNewImageName($OldTempName, $NewImageName);
							
						ImageLibraryCopyToTemp($OldTempName,$TempName, $SourceURL,$DestinationURL);
						$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
					
						$createdon 		= $modifiedon = date('Y-m-d H:i:s');
						
						$data['get_audio_video_details']['image_details'] = $this->image_model->addimages(USERID, $ImageDetails['content_id'],$data['content_type'], $ImageDetails['ImageCaption'], $ImageDetails['ImageAlt'], $PhysicalName,$TempName, $ImageDetails['Image1Type'], $ImageDetails['Image2Type'], $ImageDetails['Image3Type'],$ImageDetails['Image4Type'], 0);
				
				} else  {
				$data['get_audio_video_details']['audio_video_image_id']  = '';
				}
				
				$country_id 					= $data['get_audio_video_details']['Country_ID'];
				$state_id						= $data['get_audio_video_details']['State_ID'];
				$city_id 						= $data['get_audio_video_details']['City_ID'];
				$author_id						= $data['get_audio_video_details']['Author_ID'];
				$agency_id 						= $data['get_audio_video_details']['Agency_ID'];
				$section_id 					= $data['get_audio_video_details']['Section_id'];
				$tags 							= $data['get_audio_video_details']['Tags'];
	
				$url_array = explode("/",$data['get_audio_video_details']['url']);
				$GetTitleFromURL = explode(".",end($url_array));
			
				$data['get_audio_video_details']['url_title'] = str_replace("-".$content_id,"",$GetTitleFromURL[0]);
	
				if(isset($tags) && trim($tags) != '') 
				$data['get_tags']				= $this->common_model->get_tags_by_id($tags);
	
			
				If($agency_id != '' && $agency_id != 0 )
				$data['select_agency'] 			= get_agency_by_id($agency_id);	
				If($author_id != '' && $author_id != 0 )
				$data['select_author'] 			= get_authorname_by_id($author_id);
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
	
				$data['section_mapping'] 		= $this->common_model->multiple_section_mapping();
				$data['image_library'] 			= $this->article_image_model->get_image_library();
				
				$data['template'] 				= 'audio_video';
				$data['archive_year']           = $year;
			
				
				/*
				echo "<pre>";
				print_r($data);
				exit;
				*/
	
				$this->load->view('admin_template', $data);
			}
		
		} else {
			redirect(folder_name.'/common/access_permission/edit_audio');
		}
	}
	
	
	public function update_audio_video($content_id)
	
	{
		$ContentName = $this->input->post('txtContentName');
	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('txtAudioVideoHeadLine', $ContentName.' Head Line', 'required|trim');
		$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim|xss_clean');
		
		if($ContentName == 'Video')
		$this->form_validation->set_rules('txtScript', 'Script', 'required|trim|xss_clean');
			
		

		if ($this->input->post('txtStatus') != 'D')
		{
			$this->form_validation->set_rules('imgAudioVideoId', $ContentName.' Image', 'required|trim|xss_clean');
			$this->form_validation->set_rules('txtMetaTitle', 'Meta title', 'required|trim|xss_clean');
		}
		
		$this->form_validation->set_rules('txtMetaDescription', 'Meta Description', 'trim|xss_clean');
		$this->form_validation->set_rules('txtCanonicalUrl', 'Canonical Url', 'trim|xss_clean');
		
			
		
		if ($this->form_validation->run() == FALSE)
		{
			redirect(folder_name."/edit_audio/" . urlencode(base64_encode($content_id)));
		}
		else
		{
			if($this->audio_video_model->update_audio_video($content_id)) {
			
			if ($this->input->post('txtStatus') == 'P')
				$this->session->set_flashdata('success', $ContentName.' Published Successfully');
			else if ($this->input->post('txtStatus') == 'D')
				$this->session->set_flashdata('success', $ContentName.' Drafted Successfully');
			else
				$this->session->set_flashdata('success', $ContentName.' UnPublished Successfully');
		 
			} else {
				$this->session->set_flashdata('error', "Doesn't create ". $ContentName.", Try Again");
			}
			 
			 if($ContentName == 'Video')
				redirect(folder_name.'/video_manager');
			else
				redirect(folder_name.'/audio_manager');
		}
	}
	
	
	public function update_archive_audio_video($year,$content_id)
	
	{
		$ContentName = $this->input->post('txtContentName');
	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('txtAudioVideoHeadLine', $ContentName.' Head Line', 'required|trim');
		$this->form_validation->set_rules('ddMainSection', 'Section', 'required|trim|xss_clean');
		
		if($ContentName == 'Video')
		$this->form_validation->set_rules('txtScript', 'Script', 'required|trim|xss_clean');
			
		if ($this->input->post('txtStatus') != 'D')
		{
			$this->form_validation->set_rules('imgAudioVideoId', $ContentName.' Image', 'required|trim|xss_clean');
			$this->form_validation->set_rules('txtMetaTitle', 'Meta title', 'required|trim|xss_clean');
		}
		
		$this->form_validation->set_rules('txtMetaDescription', 'Meta Description', 'trim|xss_clean');
		$this->form_validation->set_rules('txtCanonicalUrl', 'Canonical Url', 'trim|xss_clean');
		
			
		
		if ($this->form_validation->run() == FALSE)
		{
			if($ContentName == 'Video')
				redirect(folder_name."/edit_archive_video/".$year."/". urlencode(base64_encode($content_id)));
			else
				redirect(folder_name."/edit_archive_audio/".$year."/". urlencode(base64_encode($content_id)));
		}
		else
		{
			if($this->audio_video_model->update_archive_audio_video($year,$content_id)) {
			
			if ($this->input->post('txtStatus') == 'P')
				$this->session->set_flashdata('success', "Archive ".$ContentName.' Published Successfully');
			else
				$this->session->set_flashdata('success', "Archive ".$ContentName.' UnPublished Successfully');
		 
			} else {
				$this->session->set_flashdata('error', "Doesn't create ". $ContentName.", Try Again");
			}
			 
			 if($ContentName == 'Video')
				redirect(folder_name.'/video_manager');
			else
				redirect(folder_name.'/audio_manager');
		}
	}
	public function audio_video_datatable()
	
	{
		$this->audio_video_model->get_audio_video_datatables();
	}
	
	public function get_audio_video_preview_popup() {
		
			extract($_POST);
		
		$data['content_type']		= $content_type;
		$head_line 					= urldecode($head_line);
		$data['body_text']			= urldecode($body_text);
		$data['tags']				= json_decode($tags);
		
		if($section_id != '') {
	
				$section_id = urldecode($section_id);
				$data['url_structure'] = GenerateBreadCrumbBySectionId($section_id);
		}
		
		if($publishdate != '') 
		$data['publishdate']    	= $publishdate;	
		else 
		$data['publishdate']    	= date('dS  F Y h:i A');	
		
		if($last_update != '')
		$data['last_update']    	= $last_update;
		else 
		$data['last_update']    	=  date('dS  F Y h:i A');	

	
		if($agency_id!=''){
			$data['author_name'] = get_agency_by_id($agency_id);
		}else{
			$data['author_name'] = '';
		}
				
		 $head_line = str_replace("<p","<span",$head_line);
		 $head_line = str_replace("</p>","</span>",$head_line);
		 
		$data['article_headline'] 	= $head_line;
		$data['script']				= urldecode($script);
				
		/*
		echo "<pre>";
		print_r($data);
		exit; 
		*/
		
		echo $this->load->view('admin/article_preview_popup',$data);
	}
	public function audio_upload() {
		
		$data['audio_path'] = '';
		
		if(isset($_FILES['audio_file']) && $_FILES['audio_file'] != '') {
		
			$oldget =  getcwd();
			
			$Year = date('Y');
			$Month = date('n');
			$Day =  date('j');
			
			$Resource_Path = source_base_path.audio_temp_file_path;
			
			chdir($Resource_Path);
			
			$config = array(
				'upload_path' 		=> getcwd(),
				'allowed_types' 	=> "mp3|MP3|wav|WAV",
				'overwrite'			=> false
			);
			
			chdir($oldget);
			$this->upload->initialize($config);
			$result_data = array();
			if (!$this->upload->do_upload('audio_file'))
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
				$data['audio_path'] = image_url.audio_temp_file_path.$upload_data['upload_data']['file_name'];
			}
		}
		
		echo json_encode($data);
		
	}
}
	
/* End of file gallery_manager.php */
/* Location: ./application/controllers/admin/gallery_manager.php */