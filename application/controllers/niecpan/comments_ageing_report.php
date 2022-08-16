<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class comments_ageing_report extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/comments_ageing_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Comments Ageing');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$rawdata['title']		= 'Comments Approval Ageing Report';
			$rawdata['template'] 	= 'comments_report';
			$rawdata['get_sectionid'] 	= '';
			$rawdata['content_type'] 	= '';
			$rawdata['Sectionname']		=  '';
			//$rawdata['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$this->load->view('admin_template',$rawdata);
		}else {
			redirect(folder_name.'/common/access_permission/comments_aging');
		}
	}
	
	public function userdata()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Comments Ageing');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			
			extract($_GET);
			//$sectionid = base64_decode(urldecode($this->uri->segment(4)));
			
			$sectionid = $secid;
			
			$sectiondetails 	= $this->comments_ageing_model->get_section_by_id($sectionid);
			
			//$rawdata['Sectionname']		=  ' - '.$sectiondetails['Sectionname'];
			$rawdata['Sectionname']		=  ' - '. GenerateBreadCrumbBySectionId($sectiondetails['Section_id']);

			$rawdata['title']		= 'Comments Approval Ageing Report';
			$rawdata['template'] 	= 'comments_report';
			
			$rawdata['get_sectionid'] 	= $sectionid;
			
			$rawdata['content_type'] 	= $type;
			//$rawdata['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$this->load->view('admin_template',$rawdata);
		
		}else {
			redirect(folder_name.'/common/access_permission/comments_aging');
		}
		
	}
	
	public function comments_datatable()
	{
		$this->comments_ageing_model->comments_datatable();
	}
	
	public function article_excel()
	{
		//print_r($_GET); exit;
		extract($_GET);
		$this->comments_ageing_model->comments_excel($section_id, $type, $from, $to);
	}
	
}

?>