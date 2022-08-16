<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class xml_controller extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model("admin/widget_model");
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db', TRUE);
		$this->load->library("memcached_library");
	}
	
	public function index(){
		$type = strtolower($this->uri->segment(1));
		switch ($type){
			case ($type=='gallery'):
				$query = "SELECT content_id , section_name , publish_start_date , last_updated_on , title , url , summary_html , first_image_path as article_page_image_path , first_image_title as img_title , first_image_alt as img_alt  , summary_html as story FROM gallery WHERE status='P' AND publish_start_date < NOW() ORDER BY publish_start_date DESC LIMIT 700";
			break;
			case ($type=='video'):
				$query = "SELECT content_id , section_name , publish_start_date , last_updated_on , title , url , summary_html , video_image_path as article_page_image_path , video_image_title as img_title , video_image_alt as img_alt , video_script as story FROM video WHERE status='P' AND publish_start_date < NOW() ORDER BY publish_start_date DESC LIMIT 700";
			break;
			default:
				$query = "SELECT content_id , section_name , publish_start_date , last_updated_on , title , url , summary_html , article_page_image_path , article_page_image_title as img_title , article_page_image_alt as img_alt , article_page_content_html as story FROM article WHERE status='P' AND publish_start_date < NOW() ORDER BY publish_start_date DESC LIMIT 700";
			break;
		}
		if(!$this->memcached_library->get($query) && $this->memcached_library->get($query) == ''){
			$data['content'] = $this->live_db->query($query)->result_array();
			$this->memcached_library->add($query,$data['content']);
		}else{
			$data['content']  = $this->memcached_library->get($query);
		}
		$data['baseUrl'] = base_url();
		$data['type'] = $type;
		$this->load->view('admin/ucxml_view',$data);
	}


}
?> 