<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class user_productivity_report extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		//$this->load->model('admin/user_productivity_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
		$this->load->model('admin/user_model');
		$this->load->model('admin/user_productivity_model');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('User Productivity');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$rawdata['title']		= 'User Productivity Reports';
			$rawdata['template'] 	= 'user_productivity_report';
			$rawdata['role_name'] 	= $this->user_model->get_rolename();
			$this->load->view('admin_template',$rawdata);
		}else {
			redirect(folder_name.'/common/access_permission/user_productivity');
		}
	}
	
	public function user_report_datatable()
	{
		$this->user_productivity_model->user_report_datatable();
	}
	
	public function user_report_excel()
	{
		extract($_GET);
		$this->user_productivity_model->user_excel_report();
	}
	
}


?>