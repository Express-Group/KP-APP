<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class newsletter_manager extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/newsletter_model');
	}
	public function index()
	{
		$set_object=new view;
		$set_object->index();
	}
	public function newsletter_datatable()
	{
		$set_object=new datatable;
		$set_object->newsletter_datatable();
	}
	public function newsletter_delete()
	{
		$set_object= new view;
		$set_object->newsletter_delete();
	}
	public function newsletter_bulk_delete()
	{
		$set_object= new view;
		$set_object->newsletter_bulk_delete();
	}
	public function newsletter_export_excel()
	{
		$set_object= new view;
		$set_object->newsletter_export_excel();
	}
}

class view extends newsletter_manager
{
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("News letter");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') 
		{
			$data['title']		= 'News Letter Manager';
			$data['template'] 	= 'newsletter_manager';
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect('niecpan/common/access_permission/newsletter_manager');	
		}
		
	}
	public function newsletter_delete()
	{
		$input_email_id = $this->uri->segment(4);
		$this->newsletter_model->remove_email($input_email_id);
	}
	public function newsletter_bulk_delete()
	{
		$input_email_ids = $this->input->post('emails');
		$emailids =  implode(",", $input_email_ids);
		$this->newsletter_model->remove_email($emailids);
	}
	public function newsletter_export_excel()
	{
		$this->newsletter_model->newsletter_email_report();
	}
	
}
class datatable extends newsletter_manager
{
	public function newsletter_datatable()         //to list subscribed emails
	{
		$emails = $this->newsletter_model->datatable_newsletter();
		print_r($emails);exit;
	}

}
?>