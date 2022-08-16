<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class general_report extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/general_report_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('General Report');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$rawdata['title']		= 'General Report';
			$rawdata['template'] 	= 'general_report';
			$rawdata['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$this->load->view('admin_template',$rawdata);
		}else {
			redirect(folder_name.'/common/access_permission/general_report');
		}
	}
	
	public function general_report_excel()
	{
		$this->general_report_model->general_excel_report();
	}
	
}
















?>