<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home_controller extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
	} 
	
	public function index()
	{
		$view_mode           = "live";
		$page_type		     = "1"; //home section
		$home_file           = 'home.php';
		//$home_file           = 'home_new.php';
		$page                = 'home'; 
		$file                = FCPATH.'application/views/view_template'.'/'.$home_file;
		$check_file_exist    = file_exists($file);  //check file existance of home.php
		if($check_file_exist!= '' && $check_file_exist!=false){  //check file exist
			$ExpireTime = 60; // seconds (= 1 mins)
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			$this->output->set_header("Cache-Control: cache, must-revalidate");
			$this->output->set_header("Cache-Control: max-age=".$ExpireTime);
			$this->output->set_header("Pragma: cache");
			//echo file_get_contents($file);exit;
		 $this->load->view('view_template/home');
		}else{ // if file not exist condition will call this
			$this->load->helper('form');
			$this->load->helper('url'); 		
			$this->load->helper('file');
		    $this->load->library("memcached_library");
		    //$this->memcached_library->flush();  // To clear the Memacache  , Un Comment this line
			$this->load->library("template_library");  // custom library to load xml and widgets;
			$section_details = array();
			$home_section_details = $this->widget_model->get_sectionid_with_names("Home", "" , "");
			if(count($home_section_details) > 0)
		     {
			    $page_details 	      = $this->widget_model->getPageDetails($home_section_details[0]['Section_id'], $page_type);
			 }
			 else
		      {
				$section_404_details= $this->widget_model->get_sectionid_with_names("404-not-found", "", "");	
				if(count($section_404_details) > 0)
				{
				$page_details 	= $this->widget_model->getPageDetails($section_404_details[0]['Section_id'], "1");	
				$page_type		= "1";    //section
				}else
				{
				 /******  Condition comes here only when the above mentioned 404 page is Not Found  ********/
				 echo $this->load->view("admin/page_not_found_404", '', true);
				 exit;
			  }
		     }
			$section_page_id      = $page_details['menuid'];
			$section_details      = $this->widget_model->get_sectionDetails($section_page_id, $view_mode); 
			$page_param           = 'home';
			$is_home_page         = 'y';
			$xml                  = "";
			$xml				  = simplexml_load_string($page_details['published_templatexml']);   // home Xml details
            $tmpl_values          = "";
			$tmpl_values          = (strlen($xml)!=0)? (string)$xml->attributes()->templatevalues: "";
			if($tmpl_values!="")
			{
			$tmpl_values 		  = explode("-", $tmpl_values);	
			}else{
			$template_id 		  = $page_details['templateid'];
			$template_details 	  = $this->widget_model->getTemplateDetails($template_id); 
			$tmpl_values 		  = explode("-", $template_details['template_values']);		
			}
			$data['viewmode']     = $view_mode; 
		    $header_param		= "";
			$footer_param		= "";
			$right_panel_param	= "";
			$body_loop_values	= 0;

			if(strlen($xml)!= 0)
			{
				$tplheader_values   = $xml->tplcontainer;
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
				
				$data['header']   = $this->template_library->section_xml_containers($header_param, "header", $is_home_page, $view_mode, $page_type, $page_param);
	
				$data['body']	  = '<section class="section-content"><div class="container SectionContainer"><div class="row">';
				$template_values_body_content = explode(",",$tmpl_values[1]);
				$b_section_inc = 0;
			for($i=1; $i<=count($template_values_body_content); $i++){
			
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
				$data['body'] 	  .= $this->template_library->section_xml_containers($pass_body_content, "template_body", $is_home_page,  $view_mode, $page_type, $page_param);			
				$data['body']	  .= '</div>';
				$b_section_inc ++;
			}
				$data['body']	  .= '</div></div></section>';
	
				$data['footer']   = $this->template_library->section_xml_containers($footer_param, "footer", $is_home_page, $view_mode, $page_type, $page_param);
				
				
				$data['header_ad_script']	= $page_details['Header_Adscript'];
				
				$data['page_type'] = $page_details['pagetype'];
				
				$data['section_details']	= $section_details;
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
			
}?>