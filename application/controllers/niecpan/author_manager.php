<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Author_manager extends CI_Controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url'); 
		$this->load->model('admin/authormodel');
		$this->load->library('form_validation');
		$this->load->model('admin/live_content_model');
		$this->load->library('session'); 		
	} 
	public function common_author()
	{
		//echo $this->uri->segment('2');exit;
		if($this->uri->segment('2') == 'byliner_manager')
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Byline");
			$type = 1;
			$page_name = 'Byline Manager';
			$page_redirect_name = "byliner_manager";
		}
		else
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Columnist");
			$page_name = 'Columinst Manager';
			$type = 2;
			$page_redirect_name = "columnist_manager";
		}
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$data['title']		=  $page_name;
			$data['template'] 	= 'author_manager';
			$data['type'] = $type;
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect(folder_name.'/common/access_permission/'.$page_redirect_name);	
		}
	}
	public function view_addform()
	{
		$set_object = new author_form;
		return $set_object->view_addform();
	}
	
	public function addauthordetails()
	{
		$set_object = new author_form;
		return $set_object->addauthordetails();
	}
	public function editauthor()
	{
		$set_object= new datatable;
		$set_object->editauthor();
	}
	public function author_datatable()
	{
	
		$set_object = new datatable;
		return $set_object->author_datatable();
	}
	public function delete_author()
	{
		$set_object = new datatable;
		return $set_object->delete_author();
	}
	public function checkname()
	{
		$set_object = new datatable;
		return $set_object->checkname();
	}
	public function emailcheck()
	{
		$set_object = new mailvalidation;
		return $set_object->emailcheck();
	}
	public function check_authorname()
	{
		$set_object = new mailvalidation;
		return $set_object->check_authorname();
	}
}

class author_form extends Author_manager
{
	public function view_addform()
	{
		
		if($this->uri->segment('4') == 1)
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Byline");
			$type = 1;
			$page_name = 'Byline';
			$page_redirect_name = "add_byliner";
		}
		else
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Columnist");
			$page_name = 'Columinst';
			$type = 2;
			$page_redirect_name = "add_columnist";
		}
		
		//$data['Menu_id'] = get_menu_details_by_menu_name('Byline');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$final_array['agencyname']	= $this->authormodel->getagency_name();
			$final_array['topicname']	= $this->authormodel->gettopic_name();
			$final_array['title']		= $page_name;
			$final_array['template'] 	= 'author_form';
			$final_array['type'] = $type;
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/'.$page_redirect_name);
		}
	}
	public function addauthordetails()
	{
		
			$author_tyoe= $this->input->post('rbtab-group-1');
			
			$displayname=$this->input->post('txtdisplayname');
			$email=$this->input->post('txtemail');
			$biography=$this->input->post('txtbiography');
			$this->form_validation->set_rules('txtdisplayname','Display Name','trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('txtemail','Email Address','trim|xss_clean');
			$this->form_validation->set_rules('txtbiography','Biography','trim|xss_clean');
			 
			if($this->form_validation->run() == FALSE)
			{ 
				$data['title']		= 'Author Manager';
				$data['template'] 	= 'author_form';
				$this->load->view('admin_template',$data);
			}
			else
			{  
					$get_auhorid=$this->input->post("txtAuthorId");
					$authorids='';	
					if($get_auhorid="")
					{
						$authors['authors']=$this->authormodel->addauthordetails('0',USERID); 
						if($this->input->post('rbtab-group-1')=='Byline')
						{
							redirect(folder_name.'/byliner_manager',$authors);
						}
						if($this->input->post('rbtab-group-1')=='columnist')
						{
							redirect(folder_name.'/columnist_manager',$authors);
						}
					}
					else
					{
						$authorid=$this->uri->segment(4);
						$authors['authors']=$this->authormodel->addauthordetails($authorid,USERID); 
						if($this->input->post('rbtab-group-1')=='Byline')
						{
							redirect(folder_name.'/byliner_manager',$authors);
						}
						if($this->input->post('rbtab-group-1')=='columnist')
						{
							redirect(folder_name.'/columnist_manager',$authors); 
						}
					}
			} 
	}

}

class datatable extends Author_manager
{
	public function author_datatable()
	{
		$this->authormodel->datatable_author();
	}
	public function editauthor()
	{
		
		if($this->uri->segment('5') == 1)
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Byline");
			$type = 1;
			$page_name = 'Byline';
			$page_redirect_name = "edit_byliner";
		}
		else
		{
			$data['Menu_id'] = get_menu_details_by_menu_name("Columnist");
			$page_name = 'Columinst';
			$type = 2;
			$page_redirect_name = "edit_columnist";
		}
		
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1)
		{
			$id= $this->uri->segment(4);
			$agencyname['agencyname']=$this->authormodel->getagency_name();
			$topicname['topicname']	= $this->authormodel->gettopic_name();
			$data['authors']= $this->authormodel->getauthor($this->uri->segment(4));
			$status_check['status_check'] = $this->authormodel->status_check($id);
			$final_array = array_merge($agencyname,$data,$topicname,$status_check);
			$final_array['title']		= $page_name;
			$final_array['template'] 	= 'author_form';
			$final_array['type'] = $type;
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/'.$page_redirect_name);
		}
	}
	public function delete_author()
	{
		$input_author_id = $this->uri->segment(4);
		$qry_result = $this->authormodel->check_author($input_author_id);
	}
}

class mailvalidation extends Author_manager
{
	public function emailcheck()
	{
		if(!empty($this->input->post["authord"]))
		{
			$authorids=$this->input->post["authord"];
		}
		else
		{
			$authorids="";
		}
		$email=$this->input->post('emailid');
		$validate_email=$this->authormodel->validate_email();
		if(!empty($validate_email))
		{
			echo $validate_email['0']['Email'];
		}
		else
		{
			echo "";
		}
	}
	
	public function check_authorname()
	{
		$check_authorname =$this->authormodel->validate_authorname();
	}
}
