<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class negative_manager extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/negative_model');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Comments');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$data['title']		= 'Block Words';
			$data['template'] 	= 'create_negativeKeyword';
			$this->load->view('admin_template',$data);
		} else {redirect(folder_name.'/common/access_permission/negativeWord_manager');}
	}
	public function add_keyword() //func for inerting values
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Comments');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == '1')		{
			$this->negative_model->insrt_neg_keyword(USERID);
		}		else {			redirect(folder_name.'/common/access_permission/add_negativeWord');		}
	}
	public function update_keyword() //func for updatind values
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Comments');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == '1')		{
			$this->negative_model->update_neg_keyword(USERID);
		}		else {			redirect(folder_name.'/common/access_permission/edit_negativeWord');		}
	}
	public function negativeKeyword_datatable()
	{
		$this->negative_model->pagination_datatable();
	}
	public function delete_data()
	{
		$negKey_id = $this->uri->segment(4);
		$this->negative_model->delete_keyword($negKey_id);
	}
}

?>