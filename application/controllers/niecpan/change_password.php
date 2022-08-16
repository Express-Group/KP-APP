<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class change_password extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/change_password_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	public function index()
	{
		$rawdata['title']		= 'Change Password';
		$rawdata['template'] 	= 'change_password';
		$this->load->view('admin_template',$rawdata);
	}
	public function check_password()
	{
		$set_object=new passwordcheck ;
		return $set_object->check_password();
		
	}
	public function get_details()
	{
		$new_password= $this->change_password_model->get_details(USERID);
		if($new_password)
		{
			$this->session->set_flashdata('success', 'You have successfully changed your password');
			redirect('niecpan/change_password');
		}
		else
		{
			$this->session->set_flashdata('failure', 'Your password has not changed please try again');
			redirect('niecpan/change_password');
		}
	}
	public function new_check_password() {
		
		$current_password=$this->input->get('txtOldPassword');
		$encrypted_string = hash('sha512', $current_password);
		$old_password= $this->change_password_model->check_oldpassword(USERID);
	 	if($old_password['Password']==$encrypted_string)
		{
			 $data['checked'] = true;
            echo json_encode($data['checked']);
		}
		else
		{
			 $data['checked'] = false;
            echo json_encode($data['checked']);
		}
	}
}
class passwordcheck extends change_password
{
	public function check_password()
	{
		$current_password=$this->input->post('password');
		$encrypted_string = hash('sha512', $current_password);
		$old_password= $this->change_password_model->check_oldpassword(USERID);
	 	if($old_password['Password']==$encrypted_string)
		{
			echo "success";
		}
		else
		{
			echo "Please enter the correct password";
		}
	}
}
?>