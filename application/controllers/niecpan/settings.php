<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class settings extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/settings_model');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Settings');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
		$data['title']		= 'Settings';
		$data['template'] 	= 'setting';
		$data['settings_result'] = $this->settings_model->fetch_data();
		$this->load->view('admin_template',$data);
		} else {
			redirect('niecpan/common/access_permission/Settings_manager');
		}
	}
	
	public function insert_update()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('slide_count','No of Slider count - Galleries Videos', 'trim');
		$this->form_validation->set_rules('other_stories','No of Article count - Other Stories', 'trim');
		$this->form_validation->set_rules('subsection_count','Sub section other stories count', 'trim');
		$this->form_validation->set_rules('trending_now','No of Article count - Trending Now', 'trim');
		$this->form_validation->set_rules('trending_time','Time intervel - Trending Now', 'trim');
		$this->form_validation->set_rules('mostread_time','Time intervel - Most Read', 'trim');
		$this->form_validation->set_rules('email_to', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('google_url','Google+ Page URL', 'trim');
		$this->form_validation->set_rules('facebook_url','Facebook URL', 'trim');
		$this->form_validation->set_rules('twitter_url','Twitter URL', 'trim');
		$this->form_validation->set_rules('rss_url','RSS URL', 'trim');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			$this->settings_model->insert_update_func(USERID);
		}
	}
	
}

?>