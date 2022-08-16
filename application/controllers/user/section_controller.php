<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section_controller extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url'); 		
		$this->load->helper('file');
	} 
	
	public function index()
	{
		$view_mode           = "live";
		$page_type		     = "1"; //section landing
		$this->load->library("memcached_library");
		//$this->memcached_library->flush();  // To clear the Memacache  , Un Comment this line
		$this->load->library("template_library");  // custom library to load xml and widgets;
		$section_landing_details = array();
		$live_query_string 	= explode("/",$this->uri->uri_string());
		if($this->uri->segment(1)=='topic'){
			$live_query_string 	= explode("/", "topic");
		} 
		else if(strtolower($this->uri->segment(1)) == 'author') 
		{
			$total = $this->uri->total_segments();
			if($total == 1)
			{
			 $newURL = base_url()."opinions/columns";
			 redirect($newURL,'location',301);    // redirect Author page to columns page..
			}
			//$live_query_string 	= explode("/",$this->uri->uri_string());
			$live_query_string 	= explode("/", "author");
		}
		else if(strtolower($this->uri->segment(1)) == 'columns' || strtolower($this->uri->segment(1))=="states") 
		{
			$total = $this->uri->total_segments();
			if($total == 2)
			{
			if(strtolower($this->uri->segment(2)) == 's_gurumurthy')
			 {
			 $newURL = base_url()."opinions/columns/s-gurumurthy";
			 redirect($newURL,'location',301);    
		     }else if(strtolower($this->uri->segment(2)) == 'tamil_nadu' || strtolower($this->uri->segment(2)) == 'andhra_pradesh')
			{
			  if(strtolower($this->uri->segment(2)) == 'tamil_nadu')
			   { 
		         $newURL = base_url()."states/tamil-nadu";
			     redirect($newURL,'location',301);    
			   }else if(strtolower($this->uri->segment(2)) == 'andhra_pradesh')
				{
				 $newURL = base_url()."states/andhra-pradesh";
			     redirect($newURL,'location',301); 
				 } 
			}
			}
			//$live_query_string 	= explode("/",$this->uri->uri_string());
			$live_query_string 	= $live_query_string;
		}
		switch(count($live_query_string))
		{
			case 1:
				/*  Find the section Id with sub section name
					sub section name     = $live_query_string[0] */
				$special_section	 = '';
				$url_parent_section  = '';
				$url_sub_section 	 = $live_query_string[0];
				$section_landing_details = $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	//live db
			break;
			
			case 2:
				 /*  Find the section Id with Parent section name and sub section name 
					Parent section name  = $live_query_string[0]
					sub section name     = $live_query_string[1] */
				$special_section	 = '';
				$url_parent_section  = $live_query_string[0];
				$url_sub_section 	 = $live_query_string[1];
				$section_landing_details = $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	//live db	
			break;
			
			case 3:
			/*  Find the section Id with Grand parent name and Parent section name and sub section name 
				 Grand Parent section name = $live_query_string[0] 
				 Parent section name = $live_query_string[1],
				 sub section name = $live_query_string[2]  */
				$special_section	 = $live_query_string[0];
				$url_parent_section  = $live_query_string[1];
				$url_sub_section 	 = $live_query_string[2];
				$section_landing_details = $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	 //live db	
			break;
			case 4:
			/*  Find the section Id with Grand parent name and Parent section name and sub section name 
				 Grand Parent section name = $live_query_string[0] 
				 Parent section name = $live_query_string[1],
				 sub section name = $live_query_string[2]  */
				$special_section	 = $live_query_string[1];
				$url_parent_section  = $live_query_string[2];
				$url_sub_section 	 = $live_query_string[3];
				$section_landing_details = $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	 //live db	
			break;				
		}	
		if(count($section_landing_details) > 0)
		{
			$page_details 	        = $this->widget_model->getPageDetails($section_landing_details[0]['Section_id'], $page_type);   
		}
		else
		{
			$section_landing_details    = $this->widget_model->get_sectionid_with_names("404-not-found", "", "");	
			if(count($section_landing_details) > 0)
		    {
			$page_details 	        = $this->widget_model->getPageDetails($section_landing_details[0]['Section_id'], "1");	
			}else
			{
			 /******  Condition comes here only when the above mentioned 404 page is Not Found  ********/
			 echo $this->load->view("admin/page_not_found_404", '', true);
			 exit;
			}
		}
		$section_page_id    = $page_details['menuid'];
		$section_details    = $this->widget_model->get_sectionDetails($section_page_id, $view_mode); 
		$is_home_page       = 'n';
		$page_param         = ($this->input->get('pm')!='')? $this->input->get('pm'): $page_details['menuid'];
        $xml                = "";
		$xml				= simplexml_load_string($page_details['published_templatexml']); 
		$tmpl_values        = "";
		$tmpl_values        = (strlen($xml)!=0)? (string)$xml->attributes()->templatevalues: "";
		if($tmpl_values!="")
		{
		$tmpl_values 		= explode("-", $tmpl_values);	
		}else{
		$template_id 	    = $page_details['templateid'];
		$template_details 	= $this->widget_model->getTemplateDetails($template_id); 
		$tmpl_values 		= explode("-", $template_details['template_values']);		
		}
		if($page_details['use_parent_section_template']==1 && $page_type==1){
		$parent_sectionid   = $section_details['ParentSectionID'];
		$parent_xml         = $this->template_library->get_parent_article_page($parent_sectionid, $page_type);
		$xml                = simplexml_load_string($parent_xml['published_templatexml']);
		$tmpl_values        = (strlen($xml)!=0)? (string)$xml->attributes()->templatevalues: "";
		if($tmpl_values!="")
		{
		$tmpl_values 		= explode("-", $tmpl_values);	
		}else{
		$template_id 	    = $parent_xml['templateid'];
		$template_details 	= $this->widget_model->getTemplateDetails($template_id); 
		$tmpl_values 		= explode("-", $template_details['template_values']);		
		}
		$page_details['common_header']     = $parent_xml['common_header'];
		$page_details['common_rightpanel'] = $parent_xml['common_rightpanel'];
		$page_details['common_footer']     = $parent_xml['common_footer'];
		}
				
		$data['viewmode']  = $view_mode; 
		$header_param		= "";
		$footer_param		= "";
		$right_panel_param	= "";
		$body_loop_values	= 0;
		if(strlen($xml)!= 0)
		{
			$tplheader_values 	= $xml->tplcontainer;

			$page_type          = $page_details['pagetype'];
			$header_param 		= $tplheader_values[0];
			$right_panel_param	= $tplheader_values[count($tplheader_values)-2];
			$footer_param 		= $tplheader_values[count($tplheader_values)-1];
			$body_loop_values	= $tplheader_values[0];
			
			if($page_details['common_header']==1 || $page_details['common_footer']==1 || $page_details['common_rightpanel']==1)
			{
				$common_xml         = $this->template_library->get_parent_article_page(10000, $page_type);
				$xml                = simplexml_load_string($common_xml['published_templatexml']);
				if(count($xml)> 1){
					$common_tplheader_values 	= $xml->tplcontainer; 
					if($page_details['common_header']==1){
					$header_param 	= $common_tplheader_values[0];
					}
					if($page_details['common_rightpanel']==1){
					$right_panel_param 	= $common_tplheader_values[count($common_tplheader_values)-2];				
					}
					if($page_details['common_footer']==1){
					$footer_param 	= $common_tplheader_values[count($common_tplheader_values)-1];
					}
				}
			}
			
			$is_common_header   = $page_details['common_header'];
			$common_header_file = 'common_header.php';
			$header_file        = FCPATH.'application/views/view_template'.'/'.$common_header_file;
			$header_file_exist  = file_exists($header_file);  //check file existance of common_header.php
			if(($header_file_exist!= '' && $header_file_exist!=false) && $is_common_header==1){  //check file exist
			$data['header'] 	    = $this->load->view('view_template/common_header','', TRUE);
			$data['html_header']    = true;
			}else{
			$data['header']   = $this->template_library->section_xml_containers($header_param, "header", $is_home_page, $view_mode, $page_type, $page_param);
			}
            $is_common_right   = $page_details['common_rightpanel'];
			$data['body']	  = '<section class="section-content"><div class="container SectionContainer"><div class="row">';
			$template_values_body_content = explode(",",$tmpl_values[1]);
			$b_section_inc = 0;
			$loop_break_point = count($template_values_body_content);
			for($i=1; $i<= $loop_break_point; $i++){
			
				$body_section 	= $template_values_body_content;
				$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
				
				$col_sm_val		= "12";
				$col_xs_val		= "12";
				$home_last_column = "";
				if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
				{
					if(($section_cl_val == 3 || $section_cl_val == 6 ) && array_sum($body_section) == 4){
						$home_last_column = "";
					}
					else{
						$home_last_column = "ColumnSpaceRight";
					}
				}
				
				//////  For only three column template  ////
				if(count($body_section) == 3)
				{
						if($b_section_inc == 0)
						{
							$col_sm_val		= "3";
						}
						if($b_section_inc == 1)
						{
							$col_sm_val		= "9";
						}
				}
				$c_class_value 	= " col-lg-".$section_cl_val." col-md-".$section_cl_val." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ".$home_last_column." ";
				$data['body'] .= '<div class="'. $c_class_value .'">';
				$pass_body_content = (($i) < count($template_values_body_content)) ? $tplheader_values[$i] : $right_panel_param;			
				//$pass_body_content = $tplheader_values[$i];
				if(($loop_break_point==2 && $i==2 && $is_common_right==1)||($loop_break_point==3 && $i==3 && $is_common_right==1)){
				$common_right_file  = 'common_rightpanel.php';
				$right_panel_file   = FCPATH.'application/views/view_template'.'/'.$common_right_file;
				$right_file_exist   = file_exists($right_panel_file);  //check file existance of  common_rightpanel.php
				if(($right_file_exist!= '' && $right_file_exist!=false) && $is_common_right==1){  //check file exist
				$data['body'] 	    .= $this->load->view('view_template/common_rightpanel','', TRUE);
				$data['html_rightpanel']  = true;
				}else{
				$data['body'] 	  .= $this->template_library->section_xml_containers($pass_body_content, "template_body", $is_home_page,  $view_mode, $page_type, $page_param);
				}
				}else{
				$data['body'] 	  .= $this->template_library->section_xml_containers($pass_body_content, "template_body", $is_home_page,  $view_mode, $page_type, $page_param);
				}
				$data['body']	  .= '</div>';
				$b_section_inc ++;
			}
			$data['body']	  .= '</div></div></section>';

			$is_common_footer   = $page_details['common_footer'];
			$common_footer_file = 'common_footer.php';
			$footer_file        = FCPATH.'application/views/view_template'.'/'.$common_footer_file;
			$footer_file_exist  = file_exists($footer_file);  //check file existance of common_footer.php
			if(($footer_file_exist!= '' && $footer_file_exist!=false) && $is_common_footer==1){  //check file exist
			$data['footer'] 	= $this->load->view('view_template/common_footer' ,'', TRUE);
			}else{
			$data['footer']   = $this->template_library->section_xml_containers($footer_param, "footer", $is_home_page, $view_mode, $page_type, $page_param);
			}

			
			$data['header_ad_script']	= $page_details['Header_Adscript'];
			$data['page_type']	= $page_type;
			$data['section_details']	= $section_details;
			if($data['section_details']['URLSectionName']=="404-not-found"){
			  $this->output->set_status_header('404');
			}
		}
		else   // if xml is not created condition will call this
		{
			$data['header'] 	= "";
			$data['body'] 		= "";
			$data['footer'] 	= "";
			$data['section_details']	= $section_details;
		}
		$this->load->view("admin/view_frontend", $data); 
	}
}
?>