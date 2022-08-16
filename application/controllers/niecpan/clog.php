<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class clog extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/user_login_model');
		$this->load->model('admin/image_model');
	}
	
	public function index()
	{
		$data['title']		= 'Login Page';
		$data['template'] 	= 'user_login_page';
		$this->load->view('admin_template',$data);
	}
	
	public function forgot_pwd()
	{
		$data['title']		= 'Forgot Password';
		$data['template'] 	= 'forgot_password';
		$this->load->view('admin_template',$data);
	}
	
	public function check_username()
	{
		$check_login_details = $this->user_login_model->check_login_details();
	}
	
	public function check_frgt_password()
	{
		$check_login_details = $this->user_login_model->forgot_password();
	}
	
	public function reset_password_page()
	{
		$data['title']		= 'Reset Password';
		$data['template'] 	= 'reset_password';
		$data['userID'] 	= $this->uri->segment(4);
		$this->load->view('admin_template',$data);
	}
	
	public function reset_password()
	{
		$user_id = base64_decode(urldecode($this->uri->segment(4))); 
		$check_login_details = $this->user_login_model->reset_user_password($user_id);
	}
	
	function logout()
	{
		$this->image_model->DeleteTempAllTypeImages();
		//$this->session->sess_destroy();
		$unset_sessionId = $this->session->unset_userdata('userID');
		$unset_firstName = $this->session->unset_userdata('first_name');
		$unset_lastName = $this->session->unset_userdata('last_name');
		
		if($unset_sessionId  == "")
			redirect('niecpan/clog');
	}
}


?>