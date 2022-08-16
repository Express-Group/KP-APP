<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class article_ageing_report extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/article_ageing_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Article Ageing');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$rawdata['title']		= 'Article Approval Ageing Report';
			$rawdata['template'] 	= 'article_ageing_report';
			$rawdata['get_user_id'] 	= '';
			
			$rawdata['Username']		=  '';
			//$rawdata['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$this->load->view('admin_template',$rawdata);
		}else {
			redirect(folder_name.'/common/access_permission/article_aging');
		}
	}
	
	public function userdata()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Article Ageing');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$userid = base64_decode(urldecode($this->uri->segment(4)));
			
			$userdetails 	= $this->article_ageing_model->get_userdetails_by_id($userid);
			
			$rawdata['Username']		=  ' - '.$userdetails['Username'];
			$rawdata['title']		= 'Article Approval Ageing Report';
			$rawdata['template'] 	= 'article_ageing_report';
			
			$rawdata['get_user_id'] 	= $userid;
			$rawdata['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$this->load->view('admin_template',$rawdata);
		
		}else {
			redirect(folder_name.'/common/access_permission/article_aging');
		}
		
	}
	
	public function article_ageing_datatable()
	{
		$this->article_ageing_model->article_ageing_datatable();
	}
	
	public function article_excel()
	{
		//print_r($_GET); exit;
		extract($_GET);
		$this->article_ageing_model->article_excel_report($userid, $from, $to, $section);
	}
	
}

?>