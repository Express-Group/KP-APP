<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class content_hit_manager extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/content_hit_model');
	}
	public function index()
	{
		$set_object=new view;
		$set_object->index();
	}
	public function content_hit_datatable()
	{
		$set_object=new datatable;
		$set_object->content_hit_datatable();
	}
	public function fetch_email_details()
	{
		$set_object = new view;
		$set_object->fetch_email_details();
	}
}

class view extends content_hit_manager
{
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Hit & Email Count");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') 
		{
			$data['content_id']	= '';
			$data['content_type']	= '';
			$data['title']		= 'Content Hit & Email Manager';
			$data['template'] 	= 'content_hit_manager';
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect(folder_name.'/common/access_permission/content_hit_manager');	
		}
		
	}
	
	public function fetch_email_details()
	{
		extract($_GET);
		
		$data['Menu_id'] = get_menu_details_by_menu_name("Hit & Email Count");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') 
		{
			$data['content_id']	= $id;
			$data['content_type']	= $type;
			$data['title']		= 'Content Hit & Email Manager';
			$data['template'] 	= 'content_hit_manager';
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect(folder_name.'/common/access_permission/content_hit_manager');	
		}
		
	}
	
	/*public function fetch_email_datatable()
	{
		$fetch_email = $this->content_hit_model->fetch_email_address();
	}*/
	
}
class datatable extends content_hit_manager
{
	public function content_hit_datatable()       
	{
		$emails = $this->content_hit_model->datatable_content_hit();
	}

}
?>