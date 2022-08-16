<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Search extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		//$this->load->model('admin/search_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	public function index()
	{
		$rawdata['title']		= 'search';
		$rawdata['template'] 	= 'search';
		$this->load->view('admin_template',$rawdata);
	}
	
}


?>