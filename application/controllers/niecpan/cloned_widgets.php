<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cloned_widgets extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('admin/cloned_widgets_model');		
		
	} 
	
	//////// Cloned_widgets Index page  /////	
	public function index()
	{
		$cloned_parent_instance_id 			= (isset($_POST['cloned_parent_instance_id'])) ? $this->input->post('cloned_parent_instance_id') : "";
		//Get all cloned parent widgets			
		$all_cloned_parents 				= array();
		$all_cloned_parents 				= $this->cloned_widgets_model->list_all_cloned_parent_widgets();		
		$data["cloned_parents"] 			= $all_cloned_parents;
		$data['cloned_parent_instance_id']	= $cloned_parent_instance_id;
		
		$data['title']			= 'Cloned Widgets';
		$data['template'] 		= 'cloned_widgets';
		$this->load->view('admin_template',$data);
	}
	
	public function show_cloned_children(){
		$search_by_cloned_parent 		= $this->input->post('search_by_cloned_parent');
		$cloned_children_list 			= $this->cloned_widgets_model->list_out_cloned_children($search_by_cloned_parent);
		$data['cloned_children_list'] 	= $cloned_children_list;
		
		$published_children_list		= $this->cloned_widgets_model->list_out_published_cloned_children($search_by_cloned_parent);
		$data['published_children_list']= $published_children_list;
		
		$content = $this->load->view("admin/cloned_widgets_details", $data, true);
		echo $content;
	}
	
}
?>