<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class breaking_news_manager extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/common_model');
		$this->load->model('admin/widget_model');
		$this->load->model('admin/breaking_news_model');
		
	}
	public function update_brkng_news() 
	{
		$index_object = new subclass_func;
		$index_object->update_brkng_news();
	}
	public function brkingNews_func() 
	{
		$index_object = new BrkingNews_class;
		$index_object->brkingNews_func();
	}
	public function breakingNews_datatable()
	{
		$this->breaking_news_model->pagination_datatable();
	}
	public function search_internal_article() 
	{
		$index_object = new Index_class;
		$index_object->search_internal_article();
	}
	public function changestatus() 
	{
		$index_object = new subclass_func;
		$index_object->changestatus();
	}
	
	public function update_dispOrder() 
	{
		$index_object = new subclass_func;
		$index_object->update_dispOrder();
	}
		
	public function create_breaking_news() 
	{
		$index_object = new BrkingNews_class;
		$index_object->create_breaking_news();
	}
	
	public function delete_data() 
	{
		$index_object = new subclass_func;
		$index_object->delete_data();
	}
	
	public function publish_breakingnews() 
	{
		$index_object = new subclass_func;
		$index_object->publish_breakingnews();
	}
	
	public function check_news_title() 
	{
		$index_object = new BrkingNews_class;
		$index_object->check_news_title();
	}
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Breaking News');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$data['title']		= 'Breaking News Manager';
			$data['template'] 	= 'breaking_news_manager';
			$data['homepage_id']         = $this->common_model->get_homePage_xml();
			$data['scroll_speed'] = $this->widget_model->select_setting('adminview');
			$this->load->view('admin_template',$data);
		} else {
			redirect('niecpan/common/access_permission/BreakingNews_manager');
		}
	}
	
	public function news_scroll_speed()
	{
		$this->widget_model->get_widget_setting();
	}
}
class subclass_func extends breaking_news_manager
{
	public function changestatus()
	{
		$status=$this->breaking_news_model->changestatus();
	}
	
	public function update_dispOrder()
	{
		$status=$this->breaking_news_model->update_dispOrder(USERID);
	}
	
	public function delete_data()
	{
		$news_id = $this->uri->segment(4);
		$this->breaking_news_model->delete_news_func($news_id);
	}
	
	
	public function publish_breakingnews()
	{
		$this->breaking_news_model->publish_breakingnews();
	}
	
	public function update_brkng_news()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Breaking News');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == '1') 
		{
			$news_id = base64_decode(urldecode($this->uri->segment(4)));
			$data['fetch_details'] = $this->breaking_news_model->fetch_news_val($news_id)->row_array();
			//$data['fetch_article_title'] = $this->breaking_news_model->fetch_article_title($news_id)->row_array();
			//$data['get_display_order'] =$this->breaking_news_model->get_displaorders($news_id);
		
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$data['title']		= 'Edit Breaking News';
			$data['template'] 	= 'create_breaking_news';
			$this->load->view('admin_template',$data);
		}
		else {
			redirect('niecpan/common/access_permission/edit_breaking_news');
		}
	}
}
 
class BrkingNews_class extends breaking_news_manager
{
	public function check_news_title()
	{
		$news_title_exists = $this->breaking_news_model->check_title_exists();
		if($news_title_exists > 0)
		{
			echo "exists";
			return FALSE;
		}	
		else
		{
			echo "success";
			return TRUE;
		}
	}
	
	public function create_breaking_news()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Breaking News");
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == '1') 
		{
			$news_id = '';
			$data['title']		= 'Create Breaking News';
			$data['template'] 	= 'create_breaking_news';
			$data['section_mapping'] = $this->common_model->multiple_section_mapping();
			//$data['get_display_order'] =$this->breaking_news_model->get_displaorders($news_id);
			$this->load->view('admin_template',$data);
		}
		else {
			redirect('niecpan/common/access_permission/add_breaking_news');
		}
	}
	
	public function brkingNews_func()
	{
		$this->load->library('form_validation');
		
		//if(isset($_POST['btnNewsSubmit']) && $_POST['btnNewsSubmit'] != "" && $_POST['btnNewsSubmit'] == "submit")		{
			$this->form_validation->set_rules('txtTitle','Title', 'trim|required');
			//$this->form_validation->set_rules('ddDisplayOrder','Display order', 'trim|xss_clean');
			$this->form_validation->set_rules('view3','Status', 'trim|xss_clean');
			$this->form_validation->set_rules('txtArticleId','Article Id', 'trim|xss_clean');
			$this->form_validation->set_rules('txtArticleTitle','Article title', 'trim|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->create_breaking_news();
			}
			else
			{
				$this->breaking_news_model->news_add(USERID);
			}
		}
	//}
}

class Index_class extends breaking_news_manager 
{
	public function search_internal_article() 
	{
		$search_internal_article = $this->breaking_news_model->search_internal_article();
		echo json_encode($search_internal_article);
	}
}

?>