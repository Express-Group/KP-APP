<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class top_content_report extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/top_content_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		//$data['Menu_id'] = get_menu_details_by_menu_name('Top content');
		//if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$rawdata['title']		= 'Top Content Report';
			$rawdata['template'] 	= 'top_content_reports';
			$rawdata['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$this->load->view('admin_template',$rawdata);
		/*}else {
			redirect(folder_name.'/common/access_permission/top_content');
		}*/
	}
	
	public function top_content_datatable()
	{
		$this->top_content_model->top_content_datatable();
	}
	
	public function top_content_excel()
	{
		//$type = $this->uri->segment(4);
		//print_r($_GET); exit;
		extract($_GET);
		
		$this->top_content_model->top_content_excel_report($type, $status, $from, $to);
	}
	
}
















?>