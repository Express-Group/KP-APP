<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class special_controller extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db' , TRUE);
	}
	
	public function index(){
		redirect('/' ,301);
	}
	
	public function language_fest(){
		$data['title'] = "Language Fest-  2020";
		$data['total_rows'] = $this->live_db->query("SELECT ar.content_id FROM article_section_mapping as asp LEFT JOIN article as ar ON ar.content_id = asp.content_id  WHERE ar.status = 'P' AND ar.publish_start_date < NOW() AND ( asp.section_id IN ( SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = '164' OR Section_id = '164' )) GROUP BY ar.content_id")->num_rows();
		$this->load->library('pagination');
		$config['base_url'] = BASEURL.'special-page/language_fest';
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 15;
		$config['page_query_string'] = TRUE;
		$config['num_links'] = 5;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$row = (isset($_GET['per_page']) && $_GET['per_page']!='') ? $_GET['per_page'] : 0;
		
		$data["data"] = $this->live_db->query("SELECT ar.content_id , ar.title , ar.summary_html , ar.url , ar.article_page_image_path , ar.article_page_image_title , ar.article_page_image_alt FROM article_section_mapping as asp LEFT JOIN article as ar ON ar.content_id = asp.content_id  WHERE ar.status = 'P' AND ar.publish_start_date < NOW() AND ( asp.section_id IN ( SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = '164' OR Section_id = '164' )) GROUP BY ar.content_id  ORDER BY publish_start_date DESC LIMIT ".$row." , ".$config['per_page']."")->result();
		$this->load->view('admin/specialpage/language_fest' , $data);
	}
	
	public function womens_day($year=2020){
		$data['title'] = "Women's Day-  2020";
		$data['total_rows'] = $this->live_db->query("SELECT ar.content_id FROM article_section_mapping as asp LEFT JOIN article as ar ON ar.content_id = asp.content_id  WHERE ar.status = 'P' AND ar.publish_start_date < NOW() AND ( asp.section_id IN ( SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = '54' OR Section_id = '54' )) GROUP BY ar.content_id")->num_rows();
		$this->load->library('pagination');
		$config['base_url'] = BASEURL.'special-page/womens_day';
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 15;
		$config['page_query_string'] = TRUE;
		$config['num_links'] = 5;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$row = (isset($_GET['per_page']) && $_GET['per_page']!='') ? $_GET['per_page'] : 0;
		
		$data["data"] = $this->live_db->query("SELECT ar.content_id , ar.title , ar.summary_html , ar.url , ar.article_page_image_path , ar.article_page_image_title , ar.article_page_image_alt FROM article_section_mapping as asp LEFT JOIN article as ar ON ar.content_id = asp.content_id  WHERE ar.status = 'P' AND ar.publish_start_date < NOW() AND ( asp.section_id IN ( SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = '54' OR Section_id = '54' )) GROUP BY ar.content_id  ORDER BY publish_start_date DESC LIMIT ".$row." , ".$config['per_page']."")->result();
		$this->load->view('admin/specialpage/womens_day' , $data);
	}
}
?> 