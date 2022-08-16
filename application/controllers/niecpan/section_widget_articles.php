<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class section_widget_articles extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->helper('xml');
		
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->model('admin/section_widget_article_model');
		
		$this->load->model('admin/article_model');
		$this->load->model('admin/common_model');
		$this->load->model('admin/comment_model');
	}
	
	public function get_section_group()
	{
		$section = $this->section_widget_article_model->multiple_section_mapping();
		
		foreach($section as $key => $value)
		{
			$childCategories = "";
			$special_section = "";
			//////  Sub section ///////
			if(@$value['sub_section'] != '')
				foreach(@$value['sub_section'] as $skey => $svalue)
				{
					$childCategories[] = array(
						'categoryId' => $svalue['Section_id'],
						'categoryName' => $svalue['Sectionname'],
						"Section_landing" => $svalue['Section_landing']
					);
					
					foreach(@$svalue['special_section'] as $splkey => $spl_value)
					{
						$special_section[] = array(
							'categoryId' => $spl_value['Section_id'],
							'categoryName' => $spl_value['Sectionname']
						);
					}
				}
			$data['categoryList'][] = array(
				"categoryId" => $value['Section_id'],
				"categoryName" => $value['Sectionname'],
				"childCategories" => $childCategories,
				"special_section" => $special_section,
				"Section_landing" => $value['Section_landing']
			);
		}
		return $data;
	}
	
	
	
	public function index()
	{
		$get_widget_type = $this->uri->segment(2);
		if($get_widget_type == "jumbo_widget_articles")
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Jumbo Menu Articles");
			$data['title']   = 'Jumbo Menu Articles';
			$data['breadcrumb_url']   = 'niecpan/jumbo_widget_articles';
			$access_url = 'jumbo_widget_articles';
		}
		else if($get_widget_type == "editor_pick_articles")
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Editors Pick Articles");
			$data['title']   = "Editor's Pick Articles";
			$data['breadcrumb_url']   = 'niecpan/editor_pick_articles';
			$access_url = 'editor_pick_articles';
		}
		else if($get_widget_type == "trending_now_articles")
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Trending Now Articles");
			$data['title']   = "Trending Now Articles";
			$data['breadcrumb_url']   = 'niecpan/trending_now_articles';
			$access_url = 'trending_now_articles';
		}
		else if($get_widget_type == "related_articles")
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Related Articles");
			$data['title']   = "Related Articles";
			$data['breadcrumb_url']   = 'niecpan/related_articles';
			$access_url = 'related_articles';
		}
		if(defined("USERACCESS_ADD" . $data['Menu_id']) && constant("USERACCESS_ADD" . $data['Menu_id']) == '1')
		{
			
			$user_id = USERID;
			//$mainsection_details	= array();
			//$section_details = $this->section_widget_article_model->get_section_by_id(@$page_sectionid);
			
			$edit_flag             = '0';
			$data['edit_flag']     = $edit_flag;
			//$data['section_group'] = $this->get_section_group();
			
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			
			$content_type_group         = $this->common_model->get_content_type("");
			
			$data['homepage_id']         = $this->common_model->get_homePage_xml();
			$data['content_type_group'] = $content_type_group;
			$data['check_widget_type']  = $get_widget_type;
			$data['template']           = 'section_widget_articles_view';
			
			$this->load->view('admin_template', $data);
			
		}
		else
		{
			redirect('niecpan/common/access_permission/'.$access_url.'');
		}
	}
	
	public function get_widgetInstancearticle()
	{
		$user_id                   = USERID;
		$page_name                 = strtolower($this->input->post("home_value"));
		$content_type_names        = array(
			"None" => 1,
			"Article" => 2,
			"Gallery" => 3,
			"Video" => 4,
			"Audio" => 5
		);
		$add_articles_limit        = $this->input->post("add_articles_limit");
		$search_bysection          = $this->input->post("search_bysection");
		$search_bytype             = $this->input->post("search_bytype");
		$search_bycontent          = $this->input->post("search_bycontent");
		$search_bytitle            = $this->input->post("search_bytitle");
		$current_widget_section_id = $this->input->post("parent_section_id");
		$parent_section_id         = ($search_bysection != '') ? $search_bysection : (($search_bysection == 'all') ? "" : $current_widget_section_id);
		$content_type_name         = array_search($this->input->post("content_type"), $content_type_names);
		$content_type_id           = $this->common_model->get_content_type_byname($content_type_name);
		//$data['contenttype_id']		= ($search_bytype != '') ? $search_bytype : (($search_bytype == 'all') ? ""  : ((count($content_type_id) > 0) ? $content_type_id['contenttype_id'] : '' ));
		
		$section_name = $this->input->post("get_sectionname");
		
		$search_by_widget_type = $this->input->post("hidden_widgettype");
		
		$get_sectionname = strtolower($this->input->post('get_sectionname'));
		
		if(trim($get_sectionname) == "galleries")
		{
			$data['contenttype_id'] = 3;
		}
		elseif(trim($get_sectionname) == "videos")
		{
			$data['contenttype_id'] = 4;
		}
		elseif(trim($get_sectionname) == "audios")
		{
			$data['contenttype_id'] = 5;
		}
		else
		{
			$data['contenttype_id'] = 1;
		}
		
		
		if($search_by_widget_type == "jumbo_widget_articles")
		{
			$widget_type   = "1";
			$get_sectionID = $search_bysection;
		}
		else if($search_by_widget_type == "editor_pick_articles")
		{
			$widget_type   = "2";
			$get_sectionID = 0;
		}
		else if($search_by_widget_type == "trending_now_articles")
		{
			$widget_type   = "3";
			$get_sectionID = 0;
		}
		else if($search_by_widget_type == "related_articles")
		{
			$widget_type   = "4";
			$get_sectionID = $search_bysection;
		}
		
		$search_bytitle= htmlspecialchars(trim($search_bytitle));
		$search_bytitle = addslashes(str_replace("'", "&#039;", $search_bytitle));
		
		if($search_bycontent == 'content_title')
		{
			$all_available_articles = $this->section_widget_article_model->get_all_available_articles($parent_section_id, $data['contenttype_id'], $search_bycontent, $search_bytitle, $section_name);
		}
		else if($search_bycontent == 'content_id')
		{
			$all_available_articles = $this->section_widget_article_model->required_commonwidget_content_by_id($search_bytitle, $data['contenttype_id'], $section_name);
		}
		
		
		$widget_instance_articles        = $this->section_widget_article_model->get_widgetInstanceTempArticles('', $user_id, $widget_type, $get_sectionID);
		
		$live_section_articles_count        = $this->section_widget_article_model->live_section_articles_count($widget_type, $get_sectionID);
		//echo $this->db->last_query();
		$instancecontent_idlist          = array();
		$widget_instance_active_articles = array();
		foreach($widget_instance_articles as $key => $for_content_id)
		{
			$instancecontent_idlist[]          = $for_content_id['content_id'];
			$widget_instance_active_articles[] = $for_content_id;
		}
		
		$data['all_available_articles'] = $all_available_articles;
		$data['instance_added_article'] = $widget_instance_articles;
		$data['instance_added_article'] = $widget_instance_active_articles;
		
		$data['live_section_articles_count'] = $live_section_articles_count;
		
		$data['parent_section_id']      = $parent_section_id;
		$data['widget_type']            = $widget_type;
		$data['section_name']           = $section_name;
		$data['instancecontent_idlist'] = $instancecontent_idlist;
		$data['add_articles_limit']     = $add_articles_limit;
		$content                        = $this->load->view("admin/section_widget_articles_popup", $data, true);
		echo $content;
	}
	
	//////  Get related articles for relatedContentId
	
	
	
	public function widgetarticle_customdetails()
	{
		//extract($_POST); print_r($_POST); exit;
		$user_id                                 = USERID;
		////  Need to upload the file in folder as well as DB  /////// 
		$custom_details                          = array();
		$custom_details['instanceId']            = '';
		$custom_details['instanceMainSectionId'] = '';
		$custom_details['instanceSubSectionId']  = '';
		
		$custom_details['get_SectionID']      = $this->input->post("section_id_value");
		$custom_details['content_id']         = $this->input->post("content_id");
		$custom_details['Title']              = $this->input->post("Title");
		$custom_details['Summary']            = '';
		$custom_details['old_image']          = $this->input->post("old_image");
		$custom_details['old_image_name']     = $this->input->post("old_image_name");
		$custom_details['checkWiget_Type']    = $this->input->post("check_widget_Type");
		$custom_details['physical_imagename'] = $this->input->post("physical_imagename");
		$custom_details['contentType_id']     = $this->input->post("contentType_id");
		
		$custom_details['get_sectionname']     = $this->input->post("get_sectionname");
		
		if(strtolower($custom_details['get_sectionname']) == "galleries")
		{
			$custom_details['content_type'] = '3';
		}
		else if(strtolower($custom_details['get_sectionname']) == "videos")
		{
			$custom_details['content_type'] = '4';
		}
		else if(strtolower($custom_details['get_sectionname']) == "audios")
		{
			$custom_details['content_type'] = '5';
		}
		else 
		{
			$custom_details['content_type'] = '1';
		}
		
		$custom_details['filename']  = $this->input->post("filename");
		$custom_details['full_path'] = $this->input->post("full_path");
		$custom_details['orig_name'] = $this->input->post("orig_name");
		$custom_details['temp_name'] = $this->input->post("temp_name");
		
		$custom_details['image_alt']        = $this->input->post("image_alt");
		$custom_details['image_caption']    = $this->input->post("image_caption");
		$custom_details['physical_name']    = $this->input->post("physical_name");
		$custom_details['image_library_id'] = $this->input->post("image_library_id");
		$custom_details['display_order'] = $this->input->post("display_order");
		
		$custom_details['temp_image_id'] = $this->input->post("temp_image_id");
		
		
		if($custom_details['old_image'] == "remove")
		{
			$custom_details['old_image_id'] = '';
		}
		else
		{
			$custom_details['old_image_id'] = $this->input->post("old_image_id");
		}
		$custom_details['checked_status'] = ($this->input->post("checked_status") == 'true') ? '1' : '2';
		$custom_details['uploaded_image'] = '';
		$custom_details['edit_flag']      = 'Add';
		$is_image_uploaded['image1_type'] = '';
		$is_image_uploaded['image2_type'] = '';
		$is_image_uploaded['image3_type'] = '';
		$is_image_uploaded['image4_type'] = '';
		$is_image_uploaded['image_name'] = '';
		$is_image_uploaded['save_status'] = '';
		if($custom_details['temp_image_id'] != '' && $custom_details['temp_image_id'] != 0)
		{
			$temp_image_save_details = $this->section_widget_article_model->temp_custom_image_details($custom_details['temp_image_id'], '')->row_array();
			//print_r($temp_image_save_details); exit;
			//if($temp_image_save_details['save_status'] == 1 || $temp_image_save_details['save_status'] == 2)
			{
				$is_image_uploaded['image1_type'] = $temp_image_save_details['image1_type']; //image value for 600X390
				$is_image_uploaded['image2_type'] = $temp_image_save_details['image2_type']; //image value for 600X300
				$is_image_uploaded['image3_type'] = $temp_image_save_details['image4_type']; //image value for 100X65
				$is_image_uploaded['image4_type'] = $temp_image_save_details['image3_type']; //image value for 150X150		
			}
			$is_image_uploaded['image_name'] = $temp_image_save_details['image_name']; 
			$is_image_uploaded['save_status'] = $temp_image_save_details['save_status']; 
		}
		
		//if(($custom_details['full_path']!='' && $custom_details['image_library_id'] == "" && $custom_details['temp_image_id'] != "" && $custom_details['old_image_id'] == "" ))
		if(($custom_details['full_path'] != '' && $custom_details['image_library_id'] == "" && $custom_details['temp_image_id'] == "") or ($custom_details['temp_image_id'] != "" && $custom_details['image_library_id'] == "") or ($is_image_uploaded['save_status'] == 2 && $custom_details['image_library_id'] != ""))
		{
			$image_upload_path              = "uploads/jumbo_menu_articles";
			$uploaded_image_controll        = 'uploaded_image';
				
			$is_image_uploaded['sectionID'] = $custom_details['get_SectionID'];
			$is_image_uploaded['filename']  = $custom_details['filename'];
			
			$is_image_uploaded['full_path']     = $custom_details['full_path'];
			$is_image_uploaded['orig_name']     = $custom_details['orig_name'];
			$is_image_uploaded['temp_name']     = $custom_details['temp_name'];
			$is_image_uploaded['temp_image_id'] = $custom_details['temp_image_id'];
			$is_image_uploaded['image_alt']     = $custom_details['image_alt'];
			$is_image_uploaded['image_caption'] = $custom_details['image_caption'];
			$is_image_uploaded['image_library_id'] =  $custom_details['image_library_id'] ;
			$is_image_uploaded['physical_imagename'] = $custom_details['physical_name'];
			$this->section_widget_article_model->insert_image($is_image_uploaded);
		}
		else
		{
			//$custom_details['uploaded_image']	= $custom_details['old_image'];
			//$custom_details['image_name']		= $custom_details['old_image_name'];
		}
		
		$first_update_incactive = 1;
		
		if($first_update_incactive == 1)
		{
			$this->section_widget_article_model->inactivate_articlecustomdetails($custom_details, $user_id);
			$first_update_incactive++;
		}
		
		$widget_content_id = $this->section_widget_article_model->addwidget_articlecustomdetails_temporary($custom_details, $user_id);
		
		if($widget_content_id && $custom_details['temp_image_id'] != '' && $custom_details['temp_image_id'] != 0)
			$this->delete_temp_custom_image($is_image_uploaded['image_name'],$custom_details['temp_image_id']);

		////////  Arranging records in sequence order  ////////
		//@$related_content_id = '';
		//$table_name 		 = "sectionwidgetarticle";
		//$this->section_widget_article_model->display_order_in_sequence($table_name, $custom_details['instanceId'], $custom_details['instanceMainSectionId'],  $custom_details['instanceSubSectionId'], $user_id, @$related_content_id, $custom_details['get_SectionID'], $custom_details['checkWiget_Type']);
		
		header("content-type : application/json");
		echo json_encode($widget_content_id);
		
		
	}
	public function add_widget_article($is_save_temp, $temp_view)
	{
		extract($_POST);
		$user_id              = USERID;
		$article_postedvalues = $this->input->post('required_values');
		
		$is_save_temp_exp = explode("-", $is_save_temp);
		
		$first_update_incactive     = 0;
		$previous_item_displayorder = "";
		//print_r($article_postedvalues); exit;
		
		if(count($article_postedvalues[0]) > 0) {
			foreach($article_postedvalues as $widget_article_details)
			{
				//print_r($widget_article_details); exit;
				$custom_details = array();
				
				$custom_details['section_id']         = @$widget_article_details["section_id"];
				$custom_details['content_id']         = @$widget_article_details["article_id"];
				$custom_details['Title']              = @$widget_article_details["custom_title"];
				$custom_details['Summary']            = @$widget_article_details["custom_summary"];
				$custom_details['instancecontent_id'] = '';
				$custom_details['display_order']      = $widget_article_details["article_priority"];
				$custom_details['old_image_id']       = "";
				$custom_details['checked_status']     = "1";
				$custom_details['uploaded_image']     = @$widget_article_details['old_image'];
				
				$custom_details['image_name']    = @$widget_article_details['old_image_name'];
				$custom_details['modified_date'] = @$widget_article_details['modified_date'];
				
				$custom_details['checkWiget_Type'] = @$widget_article_details['checkWigetType'];
				
				$custom_details['related_content_id'] = @$related_content_id;
				
				$custom_details['get_SectionID']  = @$widget_article_details['getSectionID'];
				$custom_details['contentType_id'] = @$widget_article_details['contentType_id'];
				
				$custom_details['img_alt']  = @$widget_article_details['img_alt'];
				$custom_details['img_caption']  = @$widget_article_details['img_caption'];
				$custom_details['img_path'] = @$widget_article_details['img_path'];
				
				
				$custom_details['get_sectionname']     = @$widget_article_details['get_sectionname'];
		
				if(strtolower($custom_details['get_sectionname']) == "galleries")
				{
					$custom_details['content_type'] = '3';
				}
				else if(strtolower($custom_details['get_sectionname']) == "videos")
				{
					$custom_details['content_type'] = '4';
				}
				else if(strtolower($custom_details['get_sectionname']) == "audios")
				{
					$custom_details['content_type'] = '5';
				}
				else 
				{
					$custom_details['content_type'] = '1';
				}
				
				$custom_details['display_order']      = '';
				if($is_save_temp_exp[0] == 'saveTemporary' or $is_save_temp == 'publish')
				{
					if($first_update_incactive == 0)
					{
						//////  previous inactive method  ///
						$this->section_widget_article_model->inactivate_instance_temparticles_status($custom_details, $user_id);
					}
					
					$result = $this->section_widget_article_model->addwidget_articlecustomdetails_temporary($custom_details, $user_id);
					
					if($is_save_temp == 'publish')
					{
						$result = $this->section_widget_article_model->addwidget_articlecustomdetails_permanent($custom_details, $first_update_incactive);
					}
					
					$first_update_incactive++;
				}
				
				////////  Arranging records in sequence order  ////////
				//$table_name = "sectionwidgetarticle";
				//$this->section_widget_article_model->display_order_in_sequence($table_name, '', '', '', $user_id, '', $custom_details['section_id'], $custom_details['checkWiget_Type']); 
				
				
			}
		}
		else
		{
			$custom_details = array();
			$custom_details['section_id']  = $getSectionID;
			$custom_details['checkWiget_Type'] = $checkWigetType;
			$custom_details['content_id'] = '';
				
			$this->section_widget_article_model->inactivate_instance_temparticles_status($custom_details, $user_id);
			
			if($is_save_temp == 'publish')
			{
				$result = $this->section_widget_article_model->delete_section_widget_articles_live($custom_details, $user_id);
			}
		}
	}
	
	
	public function upload_image($upload_path, $image_control_name)
	{
		$new_name = md5(rand(10000000000000000, 99999999999999999) . date('yymmddhis'));
		
		$imagefile       = $_FILES[$image_control_name]['tmp_name'];
		$image_file_name = date("YmdHis") . "_" . $_FILES["uploaded_image"]['name'];
		
		$oldget = getcwd();
		chdir(source_base_path . section_article_image_path);
		$config['upload_path'] = getcwd();
		chdir($oldget);
		//$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|PNG|GIF|JPG';
		$config['file_name']     = $new_name;
		$this->upload->initialize($config);
		$result_data = array();
		if(!$this->upload->do_upload($image_control_name))
		{
			$error                          = array(
				'error' => $this->upload->display_errors()
			);
			$result_data['message']         = strip_tags($error['error']);
			$result_data['status']          = 0;
			$result_data['image_file_name'] = $image_file_name;
			//$status = false;
		}
		else
		{
			$result_data = $this->upload->data();
			
			ImageJPEG(ImageCreateFromString(file_get_contents($result_data['full_path'])), $result_data['full_path'], image_resolution);
			
			$result_data['message']         = "Image Uploaded Successfully";
			$result_data['status']          = 1;
			$result_data['image_file_name'] = $image_file_name;
		}
		
		return $result_data;
	}
	
	
	public function view_temparticles()
	{}
	
	
	public function custom_image_upload()
	{
		extract($_POST);
		$result = array();
		$oldget = getcwd();
		chdir(source_base_path . section_article_image_path);
		$config = array(
			'upload_path' => getcwd(),
			'allowed_types' => "gif|jpg|png|jpeg",
			'encrypt_name' => TRUE
		);
		
		chdir($oldget);
		$this->upload->initialize($config);
		$result_data = array();
		if(!$this->upload->do_upload('imagelibrary'))
		{
			$error                  = array(
				'error' => $this->upload->display_errors()
			);
			$result_data['message'] = $error['error'];
			$result_data['status']  = 0;
		}
		else
		{
			$data   = array(
				'upload_data' => $this->upload->data()
			);
			$result = $this->upload->data();
			ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])), $data['upload_data']['full_path'], image_resolution);
			$result['imagefile']     = $_FILES['imagelibrary']['tmp_name'];
			$caption_array           = explode('.', $data['upload_data']['orig_name']);
			$result['caption']       = $caption_array[0];
			$result['physical_name'] = $caption_array[0];
			$result['alt_tag']       = $caption_array[0];
			$result['content_typ']   = 1;
			$result['Null_value']    = "NULL";
			
			$result['image'] = image_url . section_article_image_path . $data['upload_data']['file_name'];
			
			$caption 			= $caption_array[0];
			$content_type 		= 1;
			$Null_value			= "NULL";
			/*$imagefile 			= $_FILES['imagelibrary']['tmp_name'];
			$caption_array 		= explode('.', $data['upload_data']['orig_name']);
			$caption 			= $caption_array[0];
			$content_type 		= 1;
			$Null_value			= "NULL";*/
			
			$result_data	= $this->section_widget_article_model->custom_image_upload(USERID,$Null_value , $content_type,$caption, $caption,  $caption, $data['upload_data']['file_name'],0,0,0,0,1, $article_id, $instance_id, $mainSectionConfig_id);
			
			$result = array_merge($result, $result_data);
		}
		echo json_encode($result);
	}
	
	public function search_image_library()
	{
		extract($_POST);
		$this->session->set_userdata('image_caption', $Caption);
		$search_image_library_result = $this->section_widget_article_model->search_image_library($Caption);
		echo json_encode($search_image_library_result);
	}
	
	public function search_image_library_scroll()
	{
		$data['pages']         = $this->uri->segment('4');
		$data['image_library'] = $this->section_widget_article_model->search_image_library_scroll($data['pages']);
		$data['nextpages']     = $this->uri->segment('4') + 1;
		
		//echo $this->load->view('admin/custom_image_scroll', $data);
		
		if(count($data['image_library']) > 0){
			echo $this->load->view('admin/custom_image_scroll', $data);
		}
		else{
			echo "";
		}
	}
	
	public function Insert_temp_from_image_library()
	{
		extract($_POST);
		if($content_id != '')
		{
			$NewImageName   = md5(rand(10000000000000000, 99999999999999999) . date('yymmddhis'));
			$SourceURL      = imagelibrary_image_path;
			$DestinationURL = section_article_image_path;
			$ImageDetails   = GetImageDetailsByContentId($content_id);
			$path           = $ImageDetails['ImagePhysicalPath'];
			$NewPath        = GenerateNewImageName($path, $NewImageName);
			ImageLibraryCopyToTemp($path, $NewPath, $SourceURL, $DestinationURL);
			$path        = $NewPath;
			$contenttype = $contentType;
			
			if(isset($caption))
			{
				$temp_image_details = $this->section_widget_article_model->Insert_temp_from_image_library($ImageDetails, $content_id, $caption, $alt, $path, $contenttype, $article_id, $instance_id, $mainSectionConfig_id, $NewImageName);
				//$temp_image_details['temp_name'] = $NewImageName;	
				echo json_encode($temp_image_details);
			}
			else
			{
				echo json_encode(array(""));
			}
		}
		else
		{
			//tempImageIndex 
			$temp_image_details = $this->common_image_processing($tempImageIndex);
			echo json_encode($temp_image_details);
			//echo json_encode(array(""));
		}
	}
	
	public function custom_image_processing_old()
	{
		try
		{
			$ImageType = $this->uri->segment('2');
			if($ImageType != '')
			{
				$data['image_path'] = section_article_image_path;
				$TempImageId        = base64_decode(urldecode($this->uri->segment('4')));
				
				$viewimage           = $this->section_widget_article_model->temp_custom_image_details('', $TempImageId)->row_array();
				$data['temp_images'] = $viewimage;
				
				$this->section_widget_article_model->common_resize_all_images($viewimage);
				//print_r($viewimage); exit;
				if(isset($viewimage['image_name']))
				{
					
					$Tempdata['imgsrc'] = image_url . $data['image_path'] . $viewimage['image_name'];
					
					$ImageName    = $viewimage['image_name'];
					$Image600X390 = str_replace(".", "_600_390.", $ImageName);
					$Image600X300 = str_replace(".", "_600_300.", $ImageName);
					$Image100X65  = str_replace(".", "_100_65.", $ImageName);
					$Image150X150 = str_replace(".", "_150_150.", $ImageName);
					
					$Tempdata['image_binary_file600X390'] = image_url . $data['image_path'] . $Image600X390;
					$Tempdata['image_binary_file600X300'] = image_url . $data['image_path'] . $Image600X300;
					$Tempdata['image_binary_file150X150'] = image_url . $data['image_path'] . $Image150X150;
					$Tempdata['image_binary_file100X65']  = image_url . $data['image_path'] . $Image100X65;
					
					$Tempdata['imgtype600X390'] = $viewimage['image1_type'];
					$Tempdata['imgtype600X300'] = $viewimage['image2_type'];
					$Tempdata['imgtype100X65']  = $viewimage['image3_type'];
					$Tempdata['imgtype150X150'] = $viewimage['image4_type'];
					
					$Tempdata['imagenames']      = $viewimage['image_name'];
					$Tempdata['imageid']         = $viewimage['imageid'];
					$Tempdata['image_caption']   = $viewimage['caption'];
					$Tempdata['image_alt']       = $viewimage['alt_tag'];
					$Tempdata['imagecontent_id'] = $viewimage['imagecontent_id'];
					
					$data['last_crop_image'] = $Tempdata;
					
					$data['title']     = 'Edit Custom Image';
					$data['page_name'] = 'add_image';
					$data['template']  = 'section_widget_image_crop';
					$this->load->view('admin_template', $data);
					
				}
				else
				{
					redirect('niecpan/');
				}
				
			}
			else
			{
				redirect('niecpan/');
			}
			
		}
		catch(Exception $e)
		{
			echo 'Caught exception: ', $e->getMessage(), "\n";
		}
		//$this->section_widget_article_model->custom_image_processing();
	}
	
	public function custom_image_processing()
	{
		try {		
				$ImageType = $this->uri->segment('2'); 			
				if($ImageType != '') 
				{
					$data['image_path']	 = section_article_image_path;										
						$TempImageId 			= base64_decode(urldecode($this->uri->segment('4')));
												
						$supported_image_width 	= $this->uri->segment('5');
						$supported_image_height	= $this->uri->segment('6');
						
							$viewimage = $this->section_widget_article_model->temp_custom_image_details('', $TempImageId)->row_array();
							if(count($viewimage) > 0)
							{
								///  Do nothing 
							}
							else
							{
								////  Search id in temporary table  ////
								$viewimage = $this->section_widget_article_model->temp_custom_image_details($TempImageId, '')->row_array();
							}
							
							$data['temp_images'] = $viewimage;
							$this->section_widget_article_model->common_resize_all_images($viewimage);
					
							if(isset($viewimage['image_name'])) {
								
								$Tempdata['imgsrc'] 			= image_url.$data['image_path'].$viewimage['image_name'];
														
								$ImageName = $viewimage['image_name'];									
								$Image600X390 	= str_replace(".","_600_390.", $ImageName);
								$Image600X300 	= str_replace(".","_600_300.", $ImageName);
								$Image100X65 	= str_replace(".","_100_65.", $ImageName);
								$Image150X150 	= str_replace(".","_150_150.", $ImageName);
								
								$Tempdata['image_binary_file600X390']		= image_url.$data['image_path'].$Image600X390;
								$Tempdata['image_binary_file600X300']		=  image_url.$data['image_path'].$Image600X300;
								$Tempdata['image_binary_file150X150']		=  image_url.$data['image_path'].$Image150X150;
								$Tempdata['image_binary_file100X65']		=  image_url.$data['image_path'].$Image100X65;
								
								$Tempdata['imgtype600X390']		= $viewimage['image1_type'];
								$Tempdata['imgtype600X300']		= $viewimage['image2_type'];
								$Tempdata['imgtype100X65']		= $viewimage['image3_type'];
								$Tempdata['imgtype150X150']		= $viewimage['image4_type'];
								
								$Tempdata['imagenames'] 		= $viewimage['image_name'];
								$Tempdata['imageid'] 			= $viewimage['imageid'];
								$Tempdata['image_caption'] 		= $viewimage['caption'];
								$Tempdata['image_alt'] 			= $viewimage['alt_tag'];
								$Tempdata['imagecontent_id'] 	= $viewimage['imagecontent_id'];
								
								$data['last_crop_image'] = $Tempdata;
								
								$data['supported_image_width'] 	= $supported_image_width;
								$data['supported_image_height'] = $supported_image_height;
								
								$data['title']		 	= 'Edit Custom Image';
								$data['page_name'] 		= 'add_image';
								$data['template'] 		= 'section_widget_image_crop';
								$this->load->view('admin_template', $data);
							
							} else {
								redirect('niecpan/');
							}
				
				} else {
					redirect('niecpan/');
				}
			
			} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		
	
		//$this->section_widget_article_model->custom_image_processing();
	}
	
	public function crop_custom_image()
	{
		try
		{
			extract($_POST);
			$data = '';
			if(!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->section_widget_article_model->temp_custom_image_details($crop_image_id, '')->row_array();
			
			if(isset($ImageDetails['image_name']))
			{
				$image_src_path = '';
				$image_src_path = source_base_path . section_article_image_path;
				$source         = image_url . section_article_image_path;
				$src            = $image_src_path . $ImageDetails['image_name'];
				
				switch($image_type)
				{
					case "image_600_390":
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
					
					case "image_600_300":
						$dst    = $image_src_path . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						break;
					
					case "image_100_65":
						$dst    = $image_src_path . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						break;
					
					case "image_150_150":
						$dst    = $image_src_path . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						break;
					
					default:
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
						
				}
				
				
				$ImageDetails = getimagesize($src);
				
				$ImageExtension = explode("/", $ImageDetails['mime']);
				$extType        = strtolower($ImageExtension[1]);
				
				if(!empty($src) && !empty($dst) && !empty($data))
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
					$size      = getimagesize($src);
					$size_w    = $size[0]; // natural width
					$size_h    = $size[1]; // natural height
					$src_img_w = $size_w;
					$src_img_h = $size_h;
					$degrees   = $data->rotate;
					// Rotate the source image
					if(is_numeric($degrees) && $degrees != 0)
					{
						// PHP's degrees is opposite to CSS's degrees
						$new_img = imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));
						imagedestroy($src_img);
						$src_img   = $new_img;
						$deg       = abs($degrees) % 180;
						$arc       = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
						$src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
						$src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
						// Fix rotated image miss 1px issue when degrees < 0
						$src_img_w -= 1;
						$src_img_h -= 1;
					}
					$tmp_img_w = $data->width;
					$tmp_img_h = $data->height;
					$dst_img_w = $crop_width;
					$dst_img_h = $crop_height;
					$src_x     = $data->x;
					$src_y     = $data->y;
					
					if($src_x <= -$tmp_img_w || $src_x > $src_img_w)
					{
						$src_x = $src_w = $dst_x = $dst_w = 0;
					}
					else if($src_x <= 0)
					{
						$dst_x = -$src_x;
						$src_x = 0;
						$src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
					}
					else if($src_x <= $src_img_w)
					{
						$dst_x = 0;
						$src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
					}
					if($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h)
					{
						$src_y = $src_h = $dst_y = $dst_h = 0;
					}
					else if($src_y <= 0)
					{
						$dst_y = -$src_y;
						$src_y = 0;
						$src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
					}
					else if($src_y <= $src_img_h)
					{
						$dst_y = 0;
						$src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
					}
					// Scale to destination position and size
					$ratio = $tmp_img_w / $dst_img_w;
					$dst_x /= $ratio;
					$dst_y /= $ratio;
					$dst_w /= $ratio;
					$dst_h /= $ratio;
					$dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
					// Add transparent background to destination image
					imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
					imagesavealpha($dst_img, true);
					$result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					if($result)
					{
						if(!imagejpeg($dst_img, $dst))
						{
							$result_value['status'] = 'error';
							$result_value['msg']    = "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg']    = "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
					$ImageDetails = getimagesize($dst);
					$width        = $ImageDetails[0];
					$height       = $ImageDetails[1];
					$size         = $ImageDetails['bits'];
					$type         = $ImageDetails['mime'];
					$Imagedata    = '';
					$modifiedon   = date('Y-m-d H:i:s');
					
					
					$Null_value = 'NULL';
					$PathArray  = explode('/', $dst);
					$TempPath   = end($PathArray);
					
					$content_id = 'NULL';
					
					switch($image_type)
					{
						case "image_600_390":
							
							$image_type_1_value = 2;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							
							break;
						
						case "image_600_300":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 2;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_100_65":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = 2;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_150_150":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = 2;
							
							break;
						
						default:
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 2;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							break;
							
					}
					
					$this->section_widget_article_model->update_custom_crop_image($content_id, $crop_caption, $crop_alt, $image_type_1_value, $image_type_2_value, $image_type_3_value, $image_type_4_value, $modifiedon, $crop_image_id, $image_type, '0');
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
					$viewimage = $this->section_widget_article_model->temp_custom_image_details($crop_image_id, '')->row_array();
					if(isset($viewimage['image_binary_file']))
					{
						
						$result_value['image1_type'] = $viewimage['image1_type'];
						$result_value['image2_type'] = $viewimage['image2_type'];
						$result_value['image3_type'] = $viewimage['image3_type'];
						$result_value['image4_type'] = $viewimage['image4_type'];
					}
					
					$result_value['status']  = 'success';
					$result_value['msg']     = "Successfully cropped the image";
					$result_value['source']  = $source;
					$result_value['imageid'] = $crop_image_id;
					echo json_encode($result_value);
				}
			}
			else
			{
				$result_value['status'] = 'error';
				$result_value['msg']    = "Invalid Image";
				echo json_encode($result_value);
			}
		}
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg']    = 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	}
	
	public function resize_custom_image()
	{
		try
		{
			
			extract($_POST);
			$data = '';
			if(!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->section_widget_article_model->temp_custom_image_details($crop_image_id, '')->row_array();
			
			if(isset($ImageDetails['image_name']))
			{
				
				$image_src_path = source_base_path . section_article_image_path;
				$src            = $image_src_path . $ImageDetails['image_name'];
				$source         = image_url . section_article_image_path;
				
				switch($image_type)
				{
					case "image_600_390":
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
					
					case "image_600_300":
						$dst    = $image_src_path . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_300.", $ImageDetails['image_name']);
						break;
					
					case "image_100_65":
						$dst    = $image_src_path . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_100_65.", $ImageDetails['image_name']);
						break;
					
					case "image_150_150":
						$dst    = $image_src_path . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_150_150.", $ImageDetails['image_name']);
						break;
					
					default:
						$dst    = $image_src_path . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						$source = $source . str_replace(".", "_600_390.", $ImageDetails['image_name']);
						break;
						
				}
				
				
				
				$ImageDetails = getimagesize($src);
				
				$ImageExtension = explode("/", $ImageDetails['mime']);
				$extType        = strtolower($ImageExtension[1]);
				
				
				if(!empty($src) && !empty($dst) && !empty($data))
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
					
					$dst_w = $crop_width;
					$dst_h = $crop_height;
					
					// Load
					
					$dst_img = imagecreatetruecolor($dst_w, $dst_h);
					
					
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
						if(!imagejpeg($dst_img, $dst))
						{
							$result_value['status'] = 'error';
							$result_value['msg']    = "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg']    = "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
					$ImageDetails = getimagesize($dst);
					$width        = $ImageDetails[0];
					$height       = $ImageDetails[1];
					$size         = $ImageDetails['bits'];
					$type         = $ImageDetails['mime'];
					$Imagedata    = '';
					$modifiedon   = date('Y-m-d H:i:s');
					
					
					$Null_value  = 'NULL';
					$contenttype = 'ImageLibrary';
					$PathArray   = explode('/', $dst);
					$TempPath    = end($PathArray);
					
					$content_id = 'NULL';
					
					switch($image_type)
					{
						case "image_600_390":
							
							$image_type_1_value = 1;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							break;
						
						case "image_600_300":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 1;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_100_65":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = 1;
							$image_type_4_value = $Null_value;
							
							break;
						
						case "image_150_150":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = 1;
							
							break;
						
						default:
							$image_type_1_value = $Null_value;
							$image_type_2_value = 1;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							break;
							
					}
					
					$this->section_widget_article_model->update_custom_crop_image($content_id, $crop_caption, $crop_alt, $image_type_1_value, $image_type_2_value, $image_type_3_value, $image_type_4_value, $modifiedon, $crop_image_id, $image_type, '0');
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
					
					$viewimage = $this->section_widget_article_model->temp_custom_image_details($crop_image_id, '')->row_array();
					
					if(isset($viewimage['image_binary_file']))
					{
						
						$result_value['image1_type'] = $viewimage['image1_type'];
						$result_value['image2_type'] = $viewimage['image2_type'];
						$result_value['image3_type'] = $viewimage['image3_type'];
						$result_value['image4_type'] = $viewimage['image4_type'];
					}
					
					$result_value['status']  = 'success';
					$result_value['msg']     = "Successfully cropped the image";
					$result_value['source']  = $source;
					$result_value['imageid'] = $crop_image_id;
					echo json_encode($result_value);
				}
			}
			else
			{
				$result_value['status'] = 'error';
				$result_value['msg']    = "Invalid Image";
				echo json_encode($result_value);
			}
		}
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg']    = 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
		
	}
	
	public function update_custom_image_changes()
	{
		/* Update the -- table save status to '1' */
		$temp_image_id = $this->input->post('tempimageid');
		$update_img    = $this->input->post('update_img');
		$physical_name = $this->input->post('physical_name');
		$content_id    = $this->input->post('content_id');
		
		$temp_image_save_details = $this->section_widget_article_model->temp_custom_image_details($temp_image_id, '')->row_array();
		if(count($temp_image_save_details) > 0)
		{
			/*if($update_img != 'cancel')
			{
				$update_save_status = ($temp_image_save_details['save_status'] == 0) ? '2' : '1';
				$this->section_widget_article_model->update_custom_crop_image('', '', '', '', '', '', '', '', $temp_image_id, '', $update_save_status);
			}*/
			if($update_img == 'save')
			{
				$update_save_status = ($temp_image_save_details['save_status'] == 0) ? '2' : '1';
				$this->section_widget_article_model->update_custom_crop_image( '', '', '', '', '', '', '', '', $temp_image_id, '', $update_save_status );
			}	
			elseif($update_img == 'cancel')
			{
				
				$content_type 		= $temp_image_save_details['contenttype'];
				$imagecontent_id 	= $temp_image_save_details['imagecontent_id'];
				$image_name 		= explode(".",$temp_image_save_details['image_name']);				
				$image_name 		= $image_name[0];
				if(isset($content_type)) {
					
					if($imagecontent_id != ''){
						//$this->delete_temp_custom_image($temp_image_save_details['image_name'],$temp_image_id);										
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= article_temp_image_path;		
						$ImageDetails 		= GetImageDetailsByContentId($imagecontent_id);		
						$path 				= $ImageDetails['ImagePhysicalPath'];								
						ImageLibraryCopyToTemp($path,$temp_image_save_details['image_name'], $SourceURL, $DestinationURL);
						
						$path				= $temp_image_save_details['image_name'];
						$this->section_widget_article_model->Insert_temp_from_image_library($ImageDetails, $imagecontent_id, $temp_image_save_details['caption'], $temp_image_save_details['alt_tag'], $path, $temp_image_save_details['contenttype'], $temp_image_save_details['Articlecontent_id'], '', '', '' );
						
						$this->section_widget_article_model->update_custom_crop_image( '', '', '', '', '', '', '', '', $temp_image_id, '', '1' );
						
					}
					else
					{
						$this->section_widget_article_model->update_custom_crop_image( '', '', '', '0', '0', '0', '0', '', $temp_image_id, 'cancel', '1' );
						
						$viewimage 			= $this->section_widget_article_model->temp_custom_image_details($temp_image_id, '')->row_array();						
						$this->section_widget_article_model->common_resize_all_images_again($viewimage);
					}
					
					$msg		= "Custom image changes are cancelled";			
					$msg_type 	= 1;
					$show_msg 	= 1;
					$res_status = 1;
					
				} else {
					$msg		= "Internal server error while cancelling changes";			
					$msg_type 	= 2;
					$show_msg 	= 1;
					$res_status = 2;
				}
		
			}
		}
		
		if(mysql_error() == '')
		{
			if($update_img != 'cancel')
			{
				$msg        = "Images saved successfully";
				$msg_type   = 1;
				$show_msg   = 1;
				$res_status = 1;
			}
			else
			{
				$msg        = "Custom image changes are cancelled";
				$msg_type   = 1;
				$show_msg   = 1;
				$res_status = 1;
			}
		}
		else
		{
			$msg        = "Internal server error";
			$msg_type   = 2;
			$show_msg   = 1;
			$res_status = 0;
		}
		$emsg = array(
			"msg" => $msg,
			"msg_type" => $msg_type,
			"show_msg" => $show_msg,
			"res_status" => $res_status
		);
		header("content-type: application/json");
		echo json_encode($emsg);
	}
	
	public function check_custom_image_name_old()
	{
		extract($_POST);
		//print_r($_POST); exit;
		
		$Year  = date('Y');
		$Month = date('n');
		$Day   = date('j');
		create_image_folder($Year, $Month, $Day);
		$FolderMapping = $Year . "/" . $Month . "/" . $Day . "/original/";
		$ArrayPhysical = explode('.', $physical_name);
		
		$verify_name = true;
		//if($is_image_from_libarary != '')
		{
			if($temp_id != '')
			{
				$temp_image = $this->section_widget_article_model->temp_custom_image_details($temp_id, '')->row_array();
				if(count($temp_image) > 0)
				{
					$img_crop_status = 0;
					if($temp_image['image1_type'] == 2 || $temp_image['image2_type'] == 2 || $temp_image['image3_type'] == 2 || $temp_image['image4_type'] == 2)
					{
						$img_crop_status = 1;
					}
					$verify_name = ($temp_image['imagecontent_id'] == "NULL" && $temp_image['save_status'] == 1) ? true : (($temp_image['imagecontent_id'] != "" && $temp_image['save_status'] == 2 && $img_crop_status == 1) ? true : false);
				}
				else
				{
					$verify_name = false;
				}
			}
			
			if($verify_name && trim($ArrayPhysical[0]) != '')
			//if(trim($ArrayPhysical[0]) != '')
			{
				if(file_exists(source_base_path . imagelibrary_image_path . $FolderMapping . trim($physical_name)))
				{
					$data['status'] = 'false';
				}
				else
				{
					$data['status'] = 'true';
				}
			}
			else
			{
				$data['status'] = 'true';
			}
		}
		/*else
		{
			$data['status'] = 'true';
		}*/
		echo json_encode($data);
	}
	
	public function check_custom_image_name() {
		extract($_POST);					 
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
		create_image_folder( $Year, $Month, $Day);
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		$ArrayPhysical = explode('.',$physical_name);
		
		if($is_image_from_libarary != ''){
			if($temp_id != ''){
				$temp_image = $this->section_widget_article_model->temp_custom_image_details($temp_id, '')->row_array();
				if(count($temp_image) > 0){
				$verify_name = ($temp_image['imagecontent_id'] == "NULL" && $temp_image['save_status'] == 1)? true : (($temp_image['imagecontent_id'] != "" && $temp_image['save_status'] == 2) ? true : false);					
				}
				else
				{
					$verify_name = false;
				}

			}
			else
			{
				$verify_name = true;
			}
			
			if($verify_name && trim($ArrayPhysical[0]) != '')
			{
				if (file_exists(source_base_path . imagelibrary_image_path . $FolderMapping .trim($physical_name))) {
						$data['status'] = 'false';						
					} else  {
						$data['status'] = 'true';						
					}
			}
			else
			{
				$data['status'] = 'true';
			}
		}
		else
		{
			//$data['status'] = 'true';
			if(trim($ArrayPhysical[0]) != '')
			{
				if (file_exists(source_base_path . imagelibrary_image_path . $FolderMapping .trim($physical_name))) {
						$data['status'] = 'false';						
					} else  {
						$data['status'] = 'true';						
					}
			}
			else
			{
				$data['status'] = 'true';
			}
		}			
		echo json_encode($data);
	}
	
	public function delete_temp_custom_image($temp_table_image_name, $temp_image_id)
	{
		$TempSourceURL = section_article_image_path;
		/* Delete existed temporary images */
		DeleteTempImage($temp_table_image_name, $temp_image_id, $TempSourceURL);
		$this->section_widget_article_model->delete_temp_custom_image($temp_image_id);
	}
	
	public function common_image_processing($temporary_image_table_id) 
	{		

		$data['image_path']	 	= section_article_image_path;				
		$TempImageId 			= $temporary_image_table_id;

		$viewimage 				= $this->section_widget_article_model->temp_custom_image_details($TempImageId, '')->row_array();
		$data['temp_images'] 	= $viewimage;
		$this->section_widget_article_model->common_resize_all_images($viewimage);

		if(isset($viewimage['image_name'])) {
			
			$Tempdata['imgsrc'] 			= image_url.$data['image_path'].$viewimage['image_name'];
									
			$ImageName = $viewimage['image_name'];									
			$Image600X390 	= str_replace(".","_600_390.", $ImageName);
			$Image600X300 	= str_replace(".","_600_300.", $ImageName);
			$Image100X65 	= str_replace(".","_100_65.", $ImageName);
			$Image150X150 	= str_replace(".","_150_150.", $ImageName);
			
			$Tempdata['image_binary_file600X390']		= image_url.$data['image_path'].$Image600X390;
			$Tempdata['image_binary_file600X300']		=  image_url.$data['image_path'].$Image600X300;
			$Tempdata['image_binary_file150X150']		=  image_url.$data['image_path'].$Image150X150;
			$Tempdata['image_binary_file100X65']		=  image_url.$data['image_path'].$Image100X65;
			
			$Tempdata['imgtype600X390']		= $viewimage['image1_type'];
			$Tempdata['imgtype600X300']		= $viewimage['image2_type'];
			$Tempdata['imgtype100X65']		= $viewimage['image3_type'];
			$Tempdata['imgtype150X150']		= $viewimage['image4_type'];
			
			$Tempdata['imagenames'] 		= $viewimage['image_name'];
			$Tempdata['imageid'] 			= $viewimage['imageid'];
			$Tempdata['image_caption'] 		= $viewimage['caption'];
			$Tempdata['image_alt'] 			= $viewimage['alt_tag'];
			$Tempdata['imagecontent_id'] 	= $viewimage['imagecontent_id'];
			
			$data['image_id'] 	= $TempImageId;
			$data['source'] 	= image_url.section_article_image_path;
			
			$Physical_extension_array = explode(".",$viewimage['image_name']);
			
			$data['caption'] 	= $viewimage['caption'];
			$data['alt'] 		= $viewimage['alt_tag'];
			
			$data['physical_name'] 		= $viewimage['physical_name'];
			$data['physical_extension'] = $Physical_extension_array[1];
			
			$data['imagecontent_id'] 		= $viewimage['imagecontent_id'];
			$data['image1_type'] = $viewimage['image1_type'];
			$data['image2_type'] = $viewimage['image2_type'];
			$data['image3_type'] = $viewimage['image3_type'];
			$data['image4_type'] = $viewimage['image4_type'];
		} else {
			$data = "";
		}
			return $data;
								
			
		
	}
}

?>