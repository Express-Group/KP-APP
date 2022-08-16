<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Section_manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/section_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
		$this->load->dbutil();
		$this->load->model('admin/common_model');
	}
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Section");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['rawdata'] = $this->section_model->get_section_data();
			$rawdata['title']		= 'Section Manager';
			$rawdata['template'] 	= 'section_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/section_manager');
		}
	}
	public function class_section_add()
	{
		$addsectiondetails=new add_sectiondetails;
		$addsectiondetails->section_add();
	}
	public function class_add_section_view()
	{
		$view_addsectiondetails=new view_add_section ;
		$view_addsectiondetails->view_addsection();	
	}
	public function class_add_section_form()
	{
		$view_addsectiondetails=new section_form;
		$view_addsectiondetails->class_add_section_form();	
	}
	public function class_delete_section()
	{
		$delete_sectiondetails=new view_add_section ;
		$delete_sectiondetails->delete_section();
	}
	public function section_datatable()
	{
		$this->section_model->datatable_section();
	}
	public function seperate_menu()
	{
		$set_object=new special_menu;
		$set_object->seperate_menu();
	}
	public function check_sectionname()
	{
		$section_id = $this->input->post('section_id');
		if($section_id == '')
		$section_exist= $this->section_model->get_section_name();
		else
		$section_exist= $this->section_model->get_section_name_edit();
		if($section_exist > 0)
		{
			echo "Section name already exist";
			return FALSE;
		}	
		else
		{
			echo "success";
			return TRUE;
		}
	}
	public function check_sub_sectionname()
	{
		$section_id = $this->input->post('section_id');
		if($section_id=='')
		$subsection_exist= $this->section_model->get_subsection_name();
		else
		$subsection_exist= $this->section_model->get_subsection_name_edit();
		if($subsection_exist > 0)
		{
			echo "Sub section name already exist for this section";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}
	}
	public function check_url_sectionname()
	{
		$url_exist = $this->section_model->check_url_sectionname();
		if($url_exist > 0)
		{
			echo "URL Section Name already exist";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}

	}
	function display_order()
	{
		$set_object=new orderlist;
		$set_object->display_order();
	}
	function get_sectionname()
	{
		$set_object=new sitemap;
		$set_object->get_sectionname();
	}
	function check_records()
	{
		$set_object=new orderlist;
		$set_object->check_records();
	}
	function view_sitemap()
	{
		$set_object=new sitemap;
		$set_object->view_sitemap();
	}
	function get_maxdisplay_order()
	{
		$orders= $this->section_model->get_display_order();
		echo $orders['displayorder'];
		//echo json_encode($orders);
	}
	function get_section_by_id()
	{
		 $selected_order= $this->section_model->get_section_by_id();
		 echo json_encode($selected_order);
	}
	function get_lastsectionname()
	{
		$sections= $this->section_model->getlast_sectionname();
		
		//print_r($sections);
		if(!empty($sections))
		{
			echo $sections['Sectionname'];
		}
		else
		{
			echo 'no';
		}
	}
	function get_parent_sectionname()
	{
		$parent_section = $this->section_model->get_parent_sectionname();
		if(!empty($parent_section))
		{
			echo $parent_section['URLSectionStructure'];
		}
		else
		{
			echo 'noParent';
		}
	}
	function get_parent_url_structure()
	{
		$section_id  = $this->input->post('Psection_id');
		$parent_section_structure = $this->section_model->get_section_by_id($section_id);
		if(!empty($parent_section_structure))
		{
			echo $parent_section_structure[0]['URLSectionStructure'];
		}
		else
		{
			echo 'noStructure';
		}
	}
	function get_author_by_id()
	{
		$author_details = $this->section_model->get_author_by_id();
		if(!empty($author_details))
		{
			echo json_encode($author_details);
		}
		else
		{
			echo 'noauthor';
		}

	}
	public function getPageDetails()
	{
		$sec_page_details =  $this->section_model->getPageDetails();
		if(!empty($sec_page_details))
		{
			echo $sec_page_details['pagecount'];
		}else
		{
			echo 'notinserted';
		}
	}
}
class add_sectiondetails extends Section_manager
{
	public function section_add()//to add the section details
	{
		$hid_txtSectionId = $this->input->post('txtSectionId');
		$section_name= $this->input->post('txtSectionName');
		if($hid_txtSectionId== "" )
		{
			if($section_name!="")
			{ 
				$this->form_validation->set_rules('txtSectionName','Section Name','callback_check_sectionname');
			}	
			else
			{
				$this->form_validation->set_rules('txtSectionName','Section Name','trim');
			}
		}
		$this->form_validation->set_rules('view4','Status','trim|required|xss_clean');
		$this->form_validation->set_rules('chkSubSection','Name','trim');	
		$if_sub_sction = $this->input->post('chkSubSection');	// Y
		if($if_sub_sction == "Y")
		{
			$this->form_validation->set_rules('ddSectionName','Section Name','trim|xss_clean');	
		}
		 $subsection_name= $this->input->post('ddSectionName');
		 if($hid_txtSectionId== "" )
		{
		 if($subsection_name!="")
		 {
			 $subsection_name= $this->input->post('ddSectionName');
			 $this->form_validation->set_rules('txtSectionName','Section Name','callback_check_sub_sectionname');
		 }
		}
		else
		{
			 $this->form_validation->set_rules('txtSectionName','Section Name','trim');
		}
		$this->form_validation->set_rules('chkColumnist','Name','trim');	
		$if_columnist = $this->input->post('chkColumnist');	// Y
		if($if_columnist == "Y")
		{
			$this->form_validation->set_rules('ddColumnist','Author Name','trim|required|xss_clean');	
		}
		$this->form_validation->set_rules('optHighLight','Meta Title','trim');
		$this->form_validation->set_rules('optRss','Checkbox','trim');
		$this->form_validation->set_rules('optLinkType','Name','trim');
		$this->form_validation->set_rules('txtExternalLink','Meta Title','trim');
		//$this->form_validation->set_rules('ddDisplayOrder','Checkbox','trim|required|xss_clean');



		$this->form_validation->set_rules('view4','Name','trim');
		$this->form_validation->set_rules('txtMetaTitle','Meta Title','trim');
		$this->form_validation->set_rules('txtMetaDesc','Checkbox','trim');
		$this->form_validation->set_rules('txtMetaKeyword','Name','trim');
		$this->form_validation->set_rules('chkSocialOpenGraphTag','Meta Title','trim');
		$this->form_validation->set_rules('chkSocialTwitterTag','Checkbox','trim');
		
		$this->form_validation->set_rules('chkSocialSchemaTag','Checkbox','trim');
		$this->form_validation->set_rules('chkCrawlerNoIndex','Name','trim');
		$this->form_validation->set_rules('chkCrawlerNoFollow','Meta Title','trim');
		$this->form_validation->set_rules('txtCanonicalUrl','Checkbox','trim');		
	
	
		if($this->form_validation->run() == FALSE)
		{
			//$sectiondata['sectiondata'] = $this->section_model->get_section_details();
			$sectiondata['sectiondata'] = $this->common_model->multiple_section_mapping();
			$authordata['authordata'] = $this->section_model->get_author_details();
			
			$orderdata['orderdata'] = $this->section_model->get_order_details();
			$orderdatasub['orderdatasub'] = $this->section_model->get_order_details_sub();
			
			if($hid_txtSectionId == "")
			{
			$editsectiondata['editsectiondata'] = $this->section_model->get_edit_section_details(0);
				if($subsection_name != '')
				{
					$orderdata['orderdata_sub_new'] = $this->section_model->get_order_details_sub_new($subsection_name);
				}
				else
				{
					$orderdata['orderdata_sub_new'] = array("");
				}
			
			}
			else
			$editsectiondata['disable']=$this->section_model->checksectionid_details($hid_txtSectionId);
			
			if(count($editsectiondata['editsectiondata'])>0)
			{
				$orderdata['orderdata_sub_new'] = $this->section_model->get_order_details_sub_new($editsectiondata['editsectiondata'][0]['ParentSectionID']);		
			}
			
			if($hid_txtSectionId=="")
			{
			$final_array = array_merge($sectiondata,$authordata,$orderdata,$editsectiondata,$orderdatasub);
			$final_array['title']		= 'Section create';
			$final_array['template'] 	= 'create_section';
			$this->load->view('admin_template',$final_array);
			}
			else
			{
			$final_array = array_merge($sectiondata,$authordata,$orderdata,$editsectiondata,$orderdatasub);
			$final_array['title']		= 'Section create';
			$final_array['template'] 	= 'create_section';
			$this->load->view('admin_template',$final_array);
			}
			
	
		}
		else
		{ 
			if($this->section_model->add_section(USERID))
			{
				//echo USERID;exit; //
				if($hid_txtSectionId=="")
				$this->session->set_flashdata('success', 'Section details added successfully');
				else
				$this->session->set_flashdata('success', 'Section details updated successfully');
				redirect(base_url().'niecpan/section_manager');
			}
			else
			{		
				$this->session->set_flashdata('error', 'Problem while inserting. Please try again');
				redirect(base_url().'niecpan/create_section');
				
			}
		}	
		
	}
}


class view_add_section extends Section_manager
{
	public function view_addsection()//to get section and author details
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Section');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) 
		{
			$input_section_id = $this->uri->segment(4);
			$orders_display['orders_display']=$this->section_model->get_sections();
			$selected_order['slted_order']= $this->section_model->get_section_by_id($input_section_id);
			$seperatesection['seperatemenu'] = $this->section_model->get_seperate_menu();
			//$sectiondata['sectiondata'] = $this->section_model->get_section_details();
			$sectiondata['sectiondata'] = $this->common_model->multiple_section_mapping();
			$authordata['authordata'] = $this->section_model->get_author_details();
			$orderdata['orderdata'] = $this->section_model->get_order_details();
			$orderdatasub['orderdatasub'] = $this->section_model->get_order_details_sub();
			//$orderdatasub['orderdatasub'] = $this->section_model->get_order_details_sub();
			$editsectiondata['childrows']=$this->section_model->check_childrows($input_section_id);
			$editsectiondata['editsectiondata'] = $this->section_model->get_edit_section_details($input_section_id);
			$Display_order['display']=$this->section_model->get_displaorders();
			//print_r($editsectiondata['editsectiondata']); exit;
			$editsectiondata['disable']=$this->section_model->checksectionid_details($input_section_id);
			//echo $editsectiondata['editsectiondata'][0]['ParentSectionID']; exit;
			if(count($editsectiondata['editsectiondata'])>0)
			{
				$orderdata['orderdata_sub_new'] = $this->section_model->get_order_details_sub_new($editsectiondata['editsectiondata'][0]['ParentSectionID']);		
			}
			
			$parentname['parentSectionName'] = array();
			$get_parent_sectionname = $this->section_model->get_edit_section_details($editsectiondata['editsectiondata'][0]['ParentSectionID']);
			if(count($get_parent_sectionname) > 0)
				$parentname['parentSectionName'] = $get_parent_sectionname[0]['Sectionname'];
			
			$final_array = array_merge($sectiondata,$authordata,$orderdata,$editsectiondata,$orderdatasub,$seperatesection,$Display_order,$selected_order,$orders_display, $parentname);
			$final_array['title']		= 'Edit Section';
			$final_array['template'] 	= 'create_section';
			$this->load->view('admin_template',$final_array);
		} 
		else 
		{
			redirect('niecpan/common/access_permission/edit_section');
		}
	}
	public function delete_section()//delete the section details
	{		
		$input_section_id = $this->uri->segment(4);
		$qry_result1 = $this->section_model->get_check_section($input_section_id);
	}
	

}
class special_menu extends Section_manager
{
	public function seperate_menu()//function to get menu list
	{
		$get_menunames= $this->section_model->get_main_menus();	
		echo json_encode($get_menunames);
	}
}
class section_form extends Section_manager
{
	public function class_add_section_form()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Section');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$seperatesection['seperatemenu'] = $this->section_model->get_seperate_menu();
			//$sectiondata['sectiondata'] = $this->section_model->get_section_details();  
			$sectiondata['sectiondata'] = $this->common_model->multiple_section_mapping();
			$authordata['authordata'] = $this->section_model->get_author_details();
			$orderdata['orderdata'] = $this->section_model->get_order_details();
			$orderdatasub['orderdatasub'] = $this->section_model->get_order_details_sub();
			$Display_order['display']=$this->section_model->get_displaorders();
			$sectionnmae['section']= $this->section_model->getlast_sectionname();
			$final_array = array_merge($sectiondata,$authordata,$orderdata,$orderdatasub,$seperatesection,$Display_order,$sectionnmae);
			$final_array['title']		= 'Create section';
			$final_array['template'] 	= 'create_section';
			$this->load->view('admin_template',$final_array);
		} 
		else 
		{
			redirect('niecpan/common/access_permission/add_section');
		}
		
	}
	
		
}

class orderlist extends Section_manager
{
	public function display_order()//fn to get display order
	{
		$Display_order=$this->section_model->get_displaorders();	
		echo json_encode($Display_order);
		
	}
	public function check_records()
	{
		$section['rows']=$this->section_model->get_sectionrecords();	
		echo $section['rows'];exit; 
		//$section['rows']=$this->section_model->checksectionid_details($hid_txtSectionId);
	}
	
}
class sitemap extends Section_manager
{
	public function view_sitemap()
	{
		$rawdata['title']		= 'Site Map';
		$rawdata['template'] 	= 'sitemap_form';
		$rawdata['menu'] = $this->section_model->get_menu();
		$this->load->view('admin_template',$rawdata);
	}
	public function get_sectionname()
	{
		$sectionnmae= $this->section_model->get_sections();	
		echo json_encode($sectionnmae);
		
	}
	public function get_lastsectionname()
	{
		$sectionnmae= $this->section_model->getlast_sectionname();
		echo $sec_name= $sectionnmae['Sectionname'];
		//print_r($sectionnmae);
		//echo json_encode($sectionnmae);
	}

}

