<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common extends CI_Controller 
{	

	public function __construct() 
	{
		
		parent::__construct();
		$this->load->model('admin/common_model');
		$this->load->model('admin/image_model');

		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
		$this->load->model('admin/live_content_model');		
	}

	public function index() 
	{			
		$data['title']		= 'Dashboard';
		$data['template'] 	= 'dashboard';
		$this->load->view('admin_template',$data);
	}	
	
	public function multiple_section_mapping() 
	{	
		extract($_POST);
	
		if(isset($content_id) && $content_id != '' && $archive_year == "") {
			$data['get_article_mapping'] 	= $this->common_model->get_content_mapping($content_id,$content_type)->result();
			
			$set_of_mapping = array();
			
			foreach($data['get_article_mapping'] as $mapping)
			{
				$set_of_mapping[] = $mapping->Section_ID;
			}
			
			$data['mapping_section'] 		= $set_of_mapping;
			
		} elseif(isset($content_id) && $content_id != '' && $archive_year != "") {
			
			switch($content_type) {
				case 1:
				$TableName = "article_section_mapping_".$archive_year;
				break;
				case 3:
				$TableName = "gallery_section_mapping_".$archive_year;
				break;
				case 4:
				$TableName = "video_section_mapping_".$archive_year;
				break;
				case 5:
				$TableName = "audio_section_mapping_".$archive_year;
				break;
			}
			
			$this->archive_db->select("*,section_id as Section_ID");
			$this->archive_db->from($TableName);
			$this->archive_db->where("content_id",$content_id);
			$Get = $this->archive_db->get();
			
			$data['get_article_mapping'] 	= $Get->result();
			
			$set_of_mapping = array();
			
			foreach($data['get_article_mapping'] as $mapping)
			{
				$set_of_mapping[] = $mapping->Section_ID;
			}
			
			$data['mapping_section'] 		= $set_of_mapping;
		}
		
		$data['content_type']		= $content_type;
		$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
		
		$this->load->view('admin/multiple_section_mapping',$data);	
	}
	public function get_json_section() {
		
		extract($_POST);
		
		$section_mapping 	= $this->common_model->multiple_section_mapping();
		
		echo json_encode($section_mapping);
	}
	
	public function get_state() 
	{
		$class_object = new Commonsub_class;
		$class_object->get_state();
	}
	public function get_city() 
	{
		$class_object = new Commonsub_class;
		$class_object->get_city();
	}
	public function get_author_agency_id() 
	{
		$class_object = new Commonsub_class;
		$class_object->get_author_agency_id();
	}
	public function content_datatable() {
		$class_object = new Content_datatable_class;
		$class_object->content_datatable();
	}
	public function changestatus() {
		$class_object = new status_change_class;
		$class_object->changestatus();
	}
	public function status_statement() {
		$publish_object = new status_change_class;
		return $publish_object->status_statement();
	}
	public function menu() {
		$menu_object =  new menu_class;
		$menu_object->menu_template();
	}
	
	public function trash_content() {
		
		$class_object = new status_change_class;
		$class_object->trash_content();
	}
	
	public function restore_content() {
		
		$class_object = new status_change_class;
		$class_object->restore_content();
	}
	
	public function get_poll_results()
	{
		$class_object = new poll_result;
		$class_object->get_poll_results();
	}
	
	public function select_poll_results()
	{
		$class_object = new poll_result;
		$class_object->select_poll_results();
	}
	
	public function get_image_by_content_ajax() {
		extract($_POST);
		echo get_image_by_contentid_type($content_id,$type);
	}

	public function content_manager() {
	
		$content_name = $this->uri->segment('2');
	  
		 switch($content_name) {
						case "article_manager":
			 $content_type 		= "Article Manager";
			  $button_name		= "Create Article"; 
			   $addPage_url 	= folder_name."/article";
			   $menu_name		= "Article";
			 break;
						case "image_manager"; 
			 $content_type 		= "Image Manager";
			  $button_name 		= "Create Image";
			  $addPage_url 		= folder_name."/image";
			   $menu_name		= "Image Library";
			 break;
			 			case "gallery_manager"; 
			 $content_type 		= "Gallery Manager";
			  $button_name 		= "Create Gallery"; 
			    $addPage_url 	= folder_name."/gallery"; 
				 $menu_name		= "Gallery";
			 break;
			 			case "video_manager"; 
			 $content_type 		= "Video Manager";
			 $button_name 		= "Create video"; 
			 $addPage_url 		= folder_name."/audio_video_manager/create_page/4";
			  $menu_name		= "Video";
			 break;
			 			case "audio_manager"; 
			 $content_type 		= "Audio Manager";
			  $button_name 		= "Create Audio"; 
			   $addPage_url 	= folder_name."/audio_video_manager/create_page/5";
			    $menu_name		= "Audio";
			 break;
			default: 
			$content_type 		= "Article Manager"; 
			 $button_name 		= "Create Article"; 
			  $addPage_url 		= folder_name."/article";
			   $menu_name		= "Article";
		 }
		 
		$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);

		
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 'Y') {
        
		$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
		
		$data['title']				= $content_type;
		$data['btn_name']			= $button_name;
		$data['addPage_url']		= $addPage_url;
		
		$data['template'] 	= 'content_manager';
		$this->load->view('admin_template',$data);
		
		} else {
			redirect(folder_name.'/common/access_permission/'.$content_name);
		}
		
		
	}
	public function update_temp_save_status() {
		
		extract($_POST);
		$this->common_model->update_temp_save_status($tempimageid, $save_status);
		
	}
	public function check_image_name() {
			extract($_POST);
					 
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
			
		create_image_folder( $Year, $Month, $Day);
		
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		
		$temp_image = $this->common_model->get_temp_details($temp_id);

		$ArrayPhysical = explode('.',$physical_name);
		
		IF((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ( $temp_image['crop_resize_status'] == 1 && $temp_image['save_status'] == 1 ) || (  trim($temp_image['caption']) != trim($caption) || trim($temp_image['alt_tag']) != trim($alt) || trim($temp_image['physical_name']) != trim($ArrayPhysical[0]) ) )	{


			if (file_exists(source_base_path . imagelibrary_image_path . $FolderMapping .trim($physical_name))) {
					$data['status'] = 'false';
				} else  {
					$data['status'] = 'true';
				}
				
		} else {
				$data['status'] = 'true';
		}
		
			echo json_encode($data);
		}
	public function access_permission() {
			
		$content_name = $this->uri->segment('4');
		$module		= '';
		
		   switch($content_name) {
						case "article_manager":
						$module = "Article Manager";
			break;
						case "image_manager":
						$module 		= "Image Manager";
			 break;
			 			case "gallery_manager": 
						 $module 		= "Gallery Manager";
			 break;
			 			case "video_manager": 
						 $module 		= "Video Manager";
			 break;
			 			case "audio_manager": 
						 $module 		= "Audio Manager";
			 break;
			 	case "audio_manager": 
						 $module 		= "Audio Manager";
			 break;
			 	case "edit_article": 
						 $module 		= "edit existing Article";
			 break;
			 case "add_article": 
						 $module 		= "add new Article";
			 break;
			 	case "edit_gallery": 
						 $module 		= "edit existing Gallery";
			 break;
			 case "add_gallery": 
						 $module 		= "add new Gallery";
			 break;
			 	case "edit_image": 
						 $module 		= "edit existing Image";
			 break;
			 case "add_image": 
						 $module 		= "add new Image";
			 break;

			 case "user_manager": 
						 $module 		= "User Manager";
			 break;
			 case "add_user": 
						 $module 		= "Add New User";
			 break;
			 case "edit_user": 
						 $module 		= "Edit User";
			 break;
			 case "askprabhu_manager": 
						 $module 		= "Askprabhu Manager";
			 break;
			  case "edit_askprabhu": 
						 $module 		= "Edit Askprabhu";
			 break;
			 case "rolemaster_manager": 
			 			$module 		= "Rolemaster Manager";
			 break;
			 case "add_role": 
						 $module 		= "Add New Role";
			 break;
			 case "edit_role": 
						 $module 		= "Edit Role";
			 break;
			 case "author_manager": 
						 $module 		= "Author Manager";
			 break;
			 case "add_author": 
						 $module 		= "Add New Author";
			 break;
			 case "edit_author": 
						 $module 		= "Edit Author";
			 break;
			 case "section_manager": 
						 $module 		= "Section Manager";
			 break;
			 case "add_section": 
						 $module 		= "Add New Section";
			 break;
			  case "edit_section": 
						 $module 		= "Edit Section";
			 break;
			 case "comment_manager": 
						 $module 		= "Comment Manager";
			 break;
			 case "department_manager": 
						 $module 		= "Department Manager";
			 break;
			 case "edit_department": 
						 $module 		= "Edit Department";
			 break;
			 case "add_department": 
						 $module 		= "Add New Department";
			 break;

			
			 case "edit_audio": 
						 $module 		= "edit existing Audio";
			 break;
			 case "edit_video": 
						 $module 		= "edit existing Video";
			 break;
			 
			 case "add_audio": 
						 $module 		= "add new Audio";
			 break;
			 case "add_video": 
						 $module 		= "add new Video";
			 break;
			 
			 case "add_poll": 
						 $module 		= "add new Poll";
			 break;
			 
			 case "edit_poll": 
						 $module 		= "edit existing Poll";
			 break;
			 
			  case "add_negativeWord": 
						 $module 		= "add new Negative Word";
			 break;
			 
			 case "edit_negativeWord": 
						 $module 		= "edit existing Negative Word";
			 break;
			 case "add_breaking_news": 
						 $module 		= "add new Breaking News";
			 break;
			 
			 case "edit_breaking_news": 
						 $module 		= "edit existing Breaking News";
			 break;
			 
			 case "BreakingNews_manager": 
						 $module 		= "Breaking News";
			 break;
			 
			 case "poll_manager": 
						 $module 		= "Poll";
			 break;
			 
			 case "negativeWord_manager": 
						 $module 		= "Negative Words";
			 break;
			 case "city_manager": 
						 $module 		= "City Manager";
			 break;
			 case "add_city": 
						 $module 		= "add new city";
			 break;
			 case "edit_city": 
						 $module 		= "edit existing city";
			 break;
			  case "state_manager": 
						 $module 		= "State Manager";
			 break;
			 case "add_state": 
						 $module 		= "add new state";
			 break;
			 case "edit_state": 
						 $module 		= "edit existing state";
			 break;
			 case "topic_manager": 
						 $module 		= "Topic Manager";
			 break;
			 case "add_topic": 
						 $module 		= "add new topic";
			 break;
			 case "edit_topic": 
						 $module 		= "edit existing topic";
			 break;
			 case "byliner_manager": 
						 $module 		= "Byliner Manager";
			 break;
			 case "columnist_manager": 
						 $module 		= "Columnist Manager";
			 break;
			 case "add_byliner": 
						 $module 		= "add new byliner";
			 break;
			 case "add_columnist": 
						 $module 		= "add new columnist";
			 break;
			 case "edit_byliner": 
						 $module 		= "edit existing byliner ";
			 break;
			 case "edit_columnist": 
						 $module 		= "edit existing columnist";
			 break;
			 
			 
			  case "section_widget_article": 
						 $module 		= "Section Widget Articles";
			 break;
			 case "table_result_manager": 
						 $module 		= "Table Result Manager";
			 break;
			 case "create_tableresultdata": 
						 $module 		= "Create Table Result data";
			 break;
			 case "edit_tableresultdata": 
						 $module 		= "Edit Table Result data";
			 break;
			 
			 case "Settings_manager": 
						 $module 		= "Settings";
			 break;
			 
			
			
			 case "jumbo_widget_articles": 
						 $module 		= "Jumbo Menu Articles";
			 break;
			 
			  case "editor_pick_articles": 
						 $module 		= "Editor Pick Articles";
			 break;
			 
			  case "trending_now_articles": 
						 $module 		= "Trending Now Articles";
			 break;
			 case "newsletter_manager": 
						 $module 		= "News Letter";
			 break;
			case "article_aging": 
						 $module 		= "Article Ageing Report";		
			break;		 
			case "top_content": 
						 $module 		= "Top Content Report";		
			 break;			 
			case "general_report": 
						 $module 		= "General Report";					 	 
			 break;
			 case "comments_aging": 
						 $module 		= "Comments Ageing Report";					 	 
			 break;
			 
			 case "user_productivity": 
						 $module 		= "User Productivity Report";					 	 
			 break;
			 
			 case "content_hit_manager": 
						 $module 		= "Hit & Email Count";					 	 
			 break;
		   }
		   
		
		
		$data['module'] =	$module;
		$data['title']		= 'Access Denied';
		$data['template'] 	= 'access_denied';
		$this->load->view('admin_template',$data);
	}
	public function version_publish() {
		$this->common_model->version_publish();
	}
	public function publish_content() {
		
		$class_object = new status_change_class;
		$class_object->publish_content();
	}
	public function unpublish_content() {
		
		$class_object = new status_change_class;
		$class_object->unpublish_content();
	}


	public function delete_draft_content() {
		
		$class_object = new status_change_class;
		$class_object->delete_draft_content();
	}
	public function tags() {
		
		$class_object = new tag_common;
		$search_text = $this->input->get('term');
		$tag_result = $class_object->get_tags($search_text);
		$result_array = array();
		if(isset($tag_result)) {
			
			foreach($tag_result as $key=>$tags) {
				$result_array[$key]['id'] = $tags->tag_id;
				$result_array[$key]['label'] = $tags->tag_name;
				$result_array[$key]['value'] = $tags->tag_name;
				
			}
		}
		
		echo json_encode($result_array);
		exit;
		
	}
	
	public function get_author_name() {
		
		$class_object = new byline_autocomlete;
		$class_object->get_author_name();
	}
	
	public function get_state_name() {
		
		$class_object = new byline_autocomlete;
		$class_object->get_state_name();
	}
	
	public function get_city_name() {
		
		$class_object = new byline_autocomlete;
		$class_object->get_city_name();
	}
	
	public function get_tags_by_meta_title() {
		extract($_POST);
		GetTagNamesBasedMetaTitle($metatitle);
	}
	public function crop_image()

	{
		$crop_object = new Common_image_process_class;
		return $crop_object->crop_image();
	}
	
	public function resize_image()

	{
		$crop_object = new Common_image_process_class;
		return $crop_object->resize_image();
	}
	public function update_caption_alt() {
		$crop_object = new Common_image_process_class;
		return $crop_object->update_caption_alt();
	}
}
class Commonsub_class extends Common 
{
	public function get_state() {
		
		$country_id = $this->input->post('country_id');
		$get_state 	= $this->common_model->get_state_details($country_id);

		echo json_encode($get_state);
	}
	public function get_city() {
		
		$country_id = $this->input->post('country_id');	
		$state_id 	= $this->input->post('state_id');
		$get_city 	= $this->common_model->get_city_details($country_id ,$state_id);

		echo json_encode($get_city);
	}
	public function get_author_agency_id() {
		$agency_id = $this->input->post('agency_id');	
		$get_agency 	= $this->common_model->get_author_agency_id($agency_id);
		echo json_encode($get_agency);
	}
}

class Content_datatable_class extends Common 
{
	public function Content_datatable()
	{
		$this->common_model->get_content_datatables();
	}
}

class status_change_class extends Common
{
	/*activating and deactivating status*/
 public function changestatus()
 {
	extract($_POST);
			
	$this->db->trans_start();
	$this->live_db->trans_start();	
			
	update_content_status($contentid,$content_type,$status,USERID,date("Y-m-d H:i:s"));

	
	if($status == 'P') {
		if($content_type == 1) {
			$this->common_model->add_article_cms_to_livecontents($contentid);
		} else if($content_type == 3) {
			$this->common_model->add_gallery_cms_to_livecontents($contentid);
		} else if($content_type == 4) {
			$this->common_model->add_audio_video_cms_to_livecontents($contentid,4);
			} else if($content_type == 5) {
			$this->common_model->add_audio_video_cms_to_livecontents($contentid,5);
		} else if($content_type == 6) {
			 $this->common_model->add_resources_cms_to_livecontents($contentid);
		}
	}
	
	if($status == 'U') {
		$this->live_content_model->delete_livecontents($contentid,$content_type);
	}
	
	if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				echo "failure";
	} else {
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				echo "success";
	}
	
		
	
 }

 public function delete_draft_content() {
	 
	$this->db->trans_start();
	
	 
	 	extract($_POST);
		foreach($contentids['content_id'] as $key=>$content_id) {
			
			if(isset($content_type)){
			
				if($content_type == 2)
					$content_status = '0';
				else 
					$content_status = 'X';
			
				update_content_status($content_id,$content_type, $content_status , USERID, date("Y-m-d H:i:s"));
				
			}
		}
		
	if ($this->db->trans_status() === FALSE ) {
				$this->db->trans_rollback();
				return FALSE;
	} else {
				$this->db->trans_commit();
				return TRUE;
	}
	
		
 }

 public function publish_content() {
	 
	$this->db->trans_start();
	$this->live_db->trans_start();	
	 
	 	extract($_POST);
		foreach($contentids['content_id'] as $key=>$content_id) {
			
			if(isset($content_type)){
			
				if($content_type == 2)
					$content_status = '1';
				else 
					$content_status = 'P';
			
				update_content_status($content_id,$content_type, $content_status , USERID, date("Y-m-d H:i:s"));
				
				if($content_type == 1) {
					$this->common_model->add_article_cms_to_livecontents($content_id);
				} else if($content_type == 3) {
					$this->common_model->add_gallery_cms_to_livecontents($content_id);
				} else if($content_type == 4) {
					$this->common_model->add_audio_video_cms_to_livecontents($content_id,4);
				} else if($content_type == 5) {
					$this->common_model->add_audio_video_cms_to_livecontents($content_id,5);
				}else if($content_type == 6) {
					$this->common_model->add_resources_cms_to_livecontents($content_id);
				}
				
			}
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
  public function unpublish_content() {
	  
	 $this->db->trans_start();
	$this->live_db->trans_start();	
	 
	  
	 	extract($_POST);
		foreach($contentids['content_id'] as $key=>$content_id) {
			
			if(isset($content_type)){
				
				if($content_type == 2)
					$content_status = '0';
				else 
					$content_status = 'U';
				
				update_content_status($content_id, $content_type, $content_status , USERID, date("Y-m-d H:i:s"));
				
				if($content_status == 'U' && $content_status != 2) {
				$this->live_content_model->delete_livecontents($content_id,$content_type);
				}
			}	
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
 
 public function trash_content() {
	 
	 	extract($_POST);
		foreach($contentids['content_id'] as $key=>$content_id) {
			$get_previous_status = $Change_Status['change_status'][$key];
			if($get_previous_status != "T")
				update_previous_content_status($content_id, $get_previous_status);
				
			update_content_status($content_id, 'T' , USERID, date("Y-m-d H:i:s"));
		}
		
		return true;
 }
 
 
  public function restore_content() {
	 	extract($_POST);
		foreach($contentids['content_id'] as $key=>$content_id) {
			$get_previous_status = $Change_Status['change_status'][$key];
			update_content_status($content_id, $get_previous_status , USERID, date("Y-m-d H:i:s"));
		}
		return true;
 }
 
 public function status_statement()
	{
		extract($_POST);
		
		if(isset($schedule_status) && $schedule_status == '1') {
			echo  "Status : Scheduled";
			exit;
		}
		
				switch($print_status)
				{
					case "Published":
						echo "Status : Published | Published on  : ".$publishdate;
						break;
					case "Unpublished":
						echo "Status : Unpublished | Unpublished on  : ".$unpublishdate;
						break;
					case "Draft":
						echo "Status : Draft";
						break;
					default:
						echo "Status : None";
						break;
				}
		
		 
		}
}
class menu_class extends common 
{
	public function menu_template() {
		$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
		$this->load->view('admin/menu/menu.php',$data);
	}
}

class byline_autocomlete extends common
{
	function get_author_name()
	{
		extract($_GET);
		$author_type = $this->uri->segment(4);
		$get_byline_name = $this->common_model->txt_byliner($term,$author_type);
		
		$data = array();
		
		foreach ($get_byline_name as $key=>$row){
			$data[$key]['label'] = $row['AuthorName'];
            $data[$key]['text'] = $row['Author_id'];
      }
		 echo json_encode($data); 
	}
	
	function get_state_name()
	{
		extract($_GET);
		$countryid = $this->uri->segment(4);
		$get_state_name = $this->common_model->txt_state($term,$countryid);
		
		$data = array();
		
		foreach ($get_state_name as $key=>$row){
			$data[$key]['label'] = $row['StateName'];
            $data[$key]['text'] = $row['State_Id'];
      }
		 echo json_encode($data); 
	}
	
	function get_city_name()
	{
		extract($_GET);
		$stateid = $this->uri->segment(5);
		$get_city_name = $this->common_model->txt_city($term,$stateid);
		
		$data = array();
		
		foreach ($get_city_name as $key=>$row){
			$data[$key]['label'] = $row['CityName'];
            $data[$key]['text'] = $row['City_id'];
      }
		 echo json_encode($data); 
	}
}
class tag_common extends common 

{
	function get_tags($search_text) 
	{
		return $this->common_model->get_tags($search_text);
	}
}
class Common_image_process_class extends common

{
	public function update_caption_alt() 
	
	{
		$this->common_model->update_caption_alt();
	}
	
	public function crop_image()

	{
		try
		{
			extract($_POST);
			
			$data = '';
			if (!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->image_model->get_image($crop_image_id);
			
			if (isset($ImageDetails['image_name']))
			{
				$image_src_path= '';
		
					switch($image_imagetype) {
						case 1:
							$image_src_path= source_base_path.article_temp_image_path;
							$source = image_url.article_temp_image_path;
						break;
						case 2:
							$image_src_path= source_base_path.imagelibrary_temp_image_path;
							$source = image_url.imagelibrary_temp_image_path;
						break;
						case 3:
							$image_src_path= source_base_path.gallery_temp_image_path;
							$source = image_url.gallery_temp_image_path;
						break;
						case 4:
							$image_src_path= source_base_path.video_temp_image_path;
							$source = image_url.video_temp_image_path;
						break;
						case 5:
							$image_src_path= source_base_path.audio_temp_image_path;
							$source = image_url.audio_temp_image_path;
						break;
						case 6:
							$image_src_path= source_base_path.resource_temp_image_path;
							$source = image_url.resource_temp_image_path;
						break;
					}
					
				
					$src  = $image_src_path. $ImageDetails['image_name'];
				
					switch($image_type) {
						case "image_600_390":
							$dst 		= $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
					case "image_600_300":
							$dst 		= $image_src_path. str_replace(".","_600_300.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_300.",$ImageDetails['image_name']);
					break;
					
					case "image_100_65":
							$dst 		= $image_src_path. str_replace(".","_100_65.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_100_65.",$ImageDetails['image_name']);
					break;
					
					case "image_150_150":
							$dst 		= $image_src_path. str_replace(".","_150_150.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_150_150.",$ImageDetails['image_name']);
					break;
					
					default:
							$dst 		= $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
				}
	
				
				$ImageDetailsSize 				= getimagesize($src);
				
				$ImageExtension = explode("/",$ImageDetailsSize['mime']);
				$extType 		= strtolower($ImageExtension[1]);
				
				if (!empty($src) && !empty($dst) && !empty($data))
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
					$size_w 	= $size[0]; // natural width
					$size_h 	= $size[1]; // natural height
					$src_img_w 	= $size_w;
					$src_img_h 	= $size_h;
					$degrees 	= $data->rotate;
					// Rotate the source image
					if (is_numeric($degrees) && $degrees != 0)
					{
						// PHP's degrees is opposite to CSS's degrees
						$new_img 	= imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));
						imagedestroy($src_img);
						$src_img 	= $new_img;
						$deg 		= abs($degrees) % 180;
						$arc 		= ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
						$src_img_w 	= $size_w * cos($arc) + $size_h * sin($arc);
						$src_img_h 	= $size_w * sin($arc) + $size_h * cos($arc);
						// Fix rotated image miss 1px issue when degrees < 0
						$src_img_w-= 1;
						$src_img_h-= 1;
					}
					$tmp_img_w 	= $data->width;
					$tmp_img_h 	= $data->height;
					$dst_img_w 	= $crop_width;
					$dst_img_h 	= $crop_height;
					$src_x 		= $data->x;
					$src_y 		= $data->y;
					
					if ($src_x <= - $tmp_img_w || $src_x > $src_img_w)
					{
						$src_x = $src_w = $dst_x = $dst_w = 0;
					}
					else if ($src_x <= 0)
					{
						$dst_x = - $src_x;
						$src_x = 0;
						$src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
					}
					else if ($src_x <= $src_img_w)
					{
						$dst_x = 0;
						$src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
					}
					if ($src_w <= 0 || $src_y <= - $tmp_img_h || $src_y > $src_img_h)
					{
						$src_y = $src_h = $dst_y = $dst_h = 0;
					}
					else if ($src_y <= 0)
					{
						$dst_y = - $src_y;
						$src_y = 0;
						$src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
					}
					else if ($src_y <= $src_img_h)
					{
						$dst_y = 0;
						$src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
					}
					// Scale to destination position and size
					$ratio = $tmp_img_w / $dst_img_w;
					$dst_x/= $ratio;
					$dst_y/= $ratio;
					$dst_w/= $ratio;
					$dst_h/= $ratio;
					$dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
					// Add transparent background to destination image
					imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
					imagesavealpha($dst_img, true);
					$result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					if ($result)
					{
						if (!imagejpeg($dst_img, $dst,100))
						{
							$result_value['status'] = 'error';
							$result_value['msg'] 	= "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg'] 	= "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
						$ImageDetailsSize 	= getimagesize($dst);
						$width 			= $ImageDetailsSize[0];
						$height 		= $ImageDetailsSize[1];
						$size 			= $ImageDetailsSize['bits'];
						$type 			= $ImageDetailsSize['mime'];
						$Imagedata  = '';
						$modifiedon 	= date('Y-m-d H:i:s');
						
						
						$Null_value 		= 'NULL';
						$PathArray 			= explode('/', $dst);
						$TempPath 			= end($PathArray);

						$content_id = $ImageDetails['imagecontent_id'];
						
						if($content_id == '')
						$content_id = "NULL";
						
						switch($image_type) {
					case "image_600_390":
							update_temp_images( $content_id, addslashes($crop_caption), addslashes($crop_alt),2,$Null_value,$Null_value,$Null_value, $modifiedon, $crop_image_id,'image_600_390',0,1);
						
					break;
					
					case "image_600_300":
							update_temp_images( $content_id,  $crop_caption, $crop_alt,$Null_value,2,$Null_value,$Null_value, $modifiedon, $crop_image_id,'image_600_300',0,1);
							
					break;
					
					case "image_100_65":
							update_temp_images( $content_id, $crop_caption, $crop_alt,$Null_value,$Null_value,2,$Null_value, $modifiedon, $crop_image_id,'image_100_65',0,1);
							
					break;
					
					case "image_150_150":
							update_temp_images( $content_id, $crop_caption, $crop_alt,$Null_value,$Null_value,$Null_value,2, $modifiedon, $crop_image_id,'image_150_150',0,1);
							
					break;
					
					default:
							update_temp_images( $content_id, $crop_caption, $crop_alt, $Null_value,2,$Null_value,$Null_value,$Null_value, $modifiedon, $crop_image_id,'image_600_390',0,1);
								
					break;
						
						}
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
					$viewimage 				= $this->image_model->get_image($crop_image_id);
					if(isset($viewimage['image_binary_file'])) 
					{	
					
					$result_value['image1_type']	= $viewimage['image1_type'];
					$result_value['image2_type']	= $viewimage['image2_type'];
					$result_value['image3_type']	= $viewimage['image3_type'];
					$result_value['image4_type']	= $viewimage['image4_type'];
					}
							
					$result_value['status'] 	= 'success';
					$result_value['msg'] 		= "Successfully cropped the image";
					$result_value['source'] 	= $source;
					$result_value['imageid'] 	= $crop_image_id;
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
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg'] 	= 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	}
	
	public function resize_image() {
	
	try
		{

			extract($_POST);
			$data = '';
			if (!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->image_model->get_image($crop_image_id);
			
			if (isset($ImageDetails['image_name']))
			{
				
				$image_src_path= '';
		
					switch($image_imagetype) {
						case 1:
							$image_src_path= source_base_path.article_temp_image_path;
							$source = image_url.article_temp_image_path;
						break;
						case 2:
							$image_src_path= source_base_path.imagelibrary_temp_image_path;
							$source = image_url.imagelibrary_temp_image_path;
						break;
						case 3:
							$image_src_path= source_base_path.gallery_temp_image_path;
							$source = image_url.gallery_temp_image_path;
						break;
						case 4:
							$image_src_path= source_base_path.video_temp_image_path;
							$source = image_url.video_temp_image_path;
						break;
						case 5:
							$image_src_path= source_base_path.audio_temp_image_path;
							$source = image_url.audio_temp_image_path;
						break;
						case 6:
							$image_src_path= source_base_path.resource_temp_image_path;
							$source = image_url.resource_temp_image_path;
						break;
					}
				
					$src  = $image_src_path. $ImageDetails['image_name'];
					
				
					switch($image_type) {
						case "image_600_390":
							$dst 		=  $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
					case "image_600_300":
							$dst 		=  $image_src_path. str_replace(".","_600_300.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_300.",$ImageDetails['image_name']);
					break;
					
					case "image_100_65":
							$dst 		=  $image_src_path. str_replace(".","_100_65.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_100_65.",$ImageDetails['image_name']);
					break;
					
					case "image_150_150":
							$dst 		=  $image_src_path. str_replace(".","_150_150.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_150_150.",$ImageDetails['image_name']);
					break;
					
					default:
							$dst 		=  $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
				}
				
				
							
				$ImageDetailsSize 				= getimagesize($src);
				
				$ImageExtension = explode("/",$ImageDetailsSize['mime']);
				$extType 		= strtolower($ImageExtension[1]);
			
				
				if (!empty($src) && !empty($dst) && !empty($data))
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
					$src_w 	= $size[0]; // natural width
					$src_h	= $size[1]; // natural height	
					
					$dst_w 	= $crop_width;
					$dst_h 	= $crop_height;
					
					// Load
					
					$dst_img  = imagecreatetruecolor($dst_w, $dst_h);
					
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
						
					
					//$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
					if ($result)
					{
						if (!imagejpeg($dst_img, $dst))
						{
							$result_value['status'] = 'error';
							$result_value['msg'] 	= "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg'] 	= "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
						$ImageDetailsSize 	= getimagesize($dst);
						$width 			= $ImageDetailsSize[0];
						$height 		= $ImageDetailsSize[1];
						$size 			= $ImageDetailsSize['bits'];
						$type 			= $ImageDetailsSize['mime'];
						$Imagedata 		= '';
						$modifiedon 	= date('Y-m-d H:i:s');
						
						
						$Null_value 		= 'NULL';
						$contenttype 		= 'ImageLibrary';
						$PathArray 			= explode('/', $dst);
						$TempPath 			= end($PathArray);
		
						$content_id = $ImageDetails['imagecontent_id'];
						
						if($content_id == '')
						$content_id = "NULL";
						
						switch($image_type) {
					case "image_600_390":
							update_temp_images( $content_id, addslashes($crop_caption), addslashes($crop_alt),1,$Null_value,$Null_value,$Null_value, $modifiedon, $crop_image_id,'image_600_390',0,1);
						
					break;
					
					case "image_600_300":
							update_temp_images( $content_id,  $crop_caption, $crop_alt,$Null_value,1,$Null_value,$Null_value, $modifiedon, $crop_image_id,'image_600_300',0,1);
							
					break;
					
					case "image_100_65":
							update_temp_images( $content_id, $crop_caption, $crop_alt,$Null_value,$Null_value,1,$Null_value, $modifiedon, $crop_image_id,'image_100_65',0,1);
							
					break;
					
					case "image_150_150":
							update_temp_images( $content_id, $crop_caption, $crop_alt,$Null_value,$Null_value,$Null_value,1, $modifiedon, $crop_image_id,'image_150_150',0,1);
							
					break;
					
					default:
							update_temp_images( $content_id, $crop_caption, $crop_alt, $Null_value,1,$Null_value,$Null_value,$Null_value, $modifiedon, $crop_image_id,'image_600_390',0,1);
								
					break;
						
						}
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
								
					$viewimage 				= $this->image_model->get_image($crop_image_id);
					
					if(isset($viewimage['image_binary_file'])) 
					{	
					
					$result_value['image1_type']	= $viewimage['image1_type'];
					$result_value['image2_type']	= $viewimage['image2_type'];
					$result_value['image3_type']	= $viewimage['image3_type'];
					$result_value['image4_type']	= $viewimage['image4_type'];
					}
					
					$result_value['status'] 	= 'success';
					$result_value['msg'] 		= "Successfully cropped the image";
					$result_value['source'] 	= $source;
					$result_value['imageid'] 	= $crop_image_id;
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
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg'] 	= 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	
	}
}

class poll_result extends common
{
	public function get_poll_results()
	{
		$this->common_model->insert_poll_results();
	}
	
	public function select_poll_results()
	{
		extract($_POST);
		$poll_count = $this->common_model->select_poll($get_poll_id)->row_array();
		return $poll_count;
	}
}

/* End of file common.php */
/* Location: ./application/controllers/common.php */