<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class article_controller extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url'); 		
		$this->load->helper('file');
		$this->load->model('admin/comment_model');
	} 
	
	public function index()
	{
		$view_mode            = "live";
		$page_type		     = "2"; //Article landing
		$this->load->library("memcached_library");
		//$this->memcached_library->flush();  // To clear the Memacache  , Un Comment this line
		$this->load->library("template_library");  // custom library to load xml and widgets;
		$uri_string          = explode('/',$this->uri->uri_string());
		$total               = $this->uri->total_segments();
		$last_segment        = $this->uri->segment($total);
		$last_seg            = explode('-',$last_segment);
        $pos                 = strrpos(end($last_seg), ".html");  //$last_seg[0]
		$content_exist       = false;
		$content_id          = '';
		$article_landing_details = array();
		if ($pos != false) { 
		$content_exist = true;
		}
		$i = 1;	
		$image_number       = '';
        $content_from       = "live";
		$live_query_string	= explode("-",$this->uri->uri_string());	
		$uri_string         = explode('/',$live_query_string[count($live_query_string)-1]);
		$last_uri           = $uri_string[count($uri_string)-1];
		
		$ecenic_substring   = explode('.',$last_segment);
		$ecenic_pos         = substr(end($ecenic_substring),0,3); // "ece"
		$ecenic_article     = false;
		if ($ecenic_pos == "ece") { 
		$ecenic_article = true;
		}
		
		if($content_exist && !empty($last_uri))   
		{
					  /************** Article page -- live articles  ******************/
			$page_type		         = "2";		//article
			$image_number	         = 1;	
			$url_seg                 = explode(".html", $last_segment);
			$split_uri 	             = preg_split('~--(?=[^--]*$)~', $url_seg[0]);
			$content_id_from_url     = (count($split_uri)>=2)? end(explode("-", $split_uri[0])):  end(explode("-", $split_uri[0]));
			$content_id              = (!is_numeric($content_id_from_url))? ((count($split_uri)>1)? $split_uri[1]: "") :$content_id_from_url; 
			 /* if(file_exists(FCPATH."application/views/view_template/article/".$content_id.".php")){
				setcookie("article_response", "ASHTML");
				echo $this->load->view('view_template/article/'.$content_id,'', TRUE);
				die();
			} */ 
			//print_r($content_id);exit;
			$image_number	         = (count($split_uri)>=2)? ((!is_numeric($content_id_from_url))? 1: $split_uri[1]): 1;
			$current_url             = trim(str_replace(base_url(), " ", current_url()));
			$preview_article         = ($this->input->get('page')!='') ? $this->input->get('page') : '';
			$content_from            = ($preview_article!='')? 'preview' : 'live';
			$live_query_string       = explode("/",$this->uri->uri_string());
			$year                    = (count($live_query_string)>4) ? $live_query_string[count($live_query_string)-4] : '';
			$sectionname             = strtolower($this->uri->segment(1));
			$sub_section_name        = strtolower($this->uri->segment(2));
			switch ($sectionname) {
				case ($sectionname == "ಫೋಟೊ ಗ್ಯಾಲರಿ" || $sectionname == "photos" || $sectionname =="photogallery"):
					$content_type_id = 3;
					/* Generate url if page index available*/
					if(count($split_uri)>=2)
					{
					array_pop($live_query_string);
					$section_string      = join("/", $live_query_string);
					$article_string      = join("-", explode("-", $split_uri[0]));
					$current_url         = $section_string."/".$article_string.".html";
					}
					$table               = "gallery_".$year.","."gallery_related_images_".$year;
					break;
				case ($sectionname == "ವಿಡಿಯೋ" || $sub_section_name=="e-videos"  || $sectionname == "videos" ):
					$content_type_id = 4;
					$table           = "video_".$year;
					break;
				case "audios":
					$content_type_id = 5;
					$table           = "audio_".$year;
					break;
				/*case "resources":
					$content_type_id = 6;
					$table           = "resources_".$year;
					break;*/
				default:
					$content_type_id = 1;
					/* Generate url if page index available*/
					if(count($split_uri)>=2)
					{
					array_pop($live_query_string);
					$section_string      = join("/", $live_query_string);
					$article_string      = join("-", explode("-", $split_uri[0]));
					if(!is_numeric($content_id_from_url)){
					$current_url         = $section_string."/".$url_seg[0].".html";
					}else{
					$current_url         = $section_string."/".$article_string.".html";
					}
					}
					$table           = "article_".$year;
			}
			
		if($content_from =="live" && $year!=''){
		$article_landing_details 	= $this->widget_model->widget_article_content_by_id($content_id, $content_type_id, $current_url);
		if(count($article_landing_details) == 0)
		{
			$article_landing_details 	= $this->widget_model->widget_article_content_by_id($content_id, $content_type_id, "");
			if(count($article_landing_details)>0)
			{
				 $newURL = base_url().$article_landing_details[0]['url'];
				 redirect($newURL,'location',301);    // redirect modified url to current formatted url..
			}
		}
		if(count($article_landing_details) == 0)
		{
			
		$article_landing_details     = $this->widget_model->widget_archive_article_content_by_id($content_id, $content_type_id, $current_url , $table, 0); // last parameter denotes ecenic or not	
		if(count($article_landing_details) == 0)
		{
			$article_landing_details     = $this->widget_model->widget_archive_article_content_by_id($content_id, $content_type_id, "" , $table, 0); // last parameter denotes ecenic or not
			if(count($article_landing_details)>0)
			{
				 $newURL = base_url().$article_landing_details[0]['url'];
				 redirect($newURL,'location',301);    // redirect modified url to current formatted url..
			}
		}
		$content_from = "archive";
		}
		}
		else if($content_from =="preview"){
		$article_landing_details 	= $this->widget_model->widget_article_content_preview($content_id, $content_type_id);	
		if(count($article_landing_details) == 0)
		{
		$current_url = '';
		$article_landing_details     = $this->widget_model->widget_archive_article_content_by_id($content_id, $content_type_id, $current_url , $table, 0); // last parameter denotes ecenic or not	
		$content_from = "archive";
		}
		//print_r($url_section_details);exit;
		}
	  }
	  else if($ecenic_article)
	  {
		  /************** Article page Ecenic articles  ******************/
		$ecenic                 = 1;
		$page_type				= "2";  //article
		$live_query_string 	    = explode("/",$this->uri->uri_string());
		$old_site_article 		= explode(".",$live_query_string[count($live_query_string)-1]);	
		$old_site_article_id 	= explode("article",$old_site_article[0]);
		$current_url            = "";
		$article_hosted_year    = "";
		if(is_numeric($old_site_article_id[1])){	
		$ecenic_id              = $old_site_article_id[1];
		$search_year            = range(date('Y'), 2008);
		foreach($search_year as $s_year)
		{
			if(array_search($s_year, $live_query_string)){
				$article_hosted_year = array_search($s_year, array_filter($live_query_string, 'is_numeric'));
				break;
			}
		}
		$sectionname            = strtolower($this->uri->segment(1));
		$sub_section_name       = strtolower($this->uri->segment(2));
		switch ($sectionname) {
			case ($sectionname == "galleries" || $sectionname == "photos"):
				$content_type_id = 3;
				$table           = "gallery_,gallery_related_images_";
				break;
			case ($sectionname == "videos" || $sub_section_name=="videos"):
				$content_type_id = 4;
				$table           = "video_";
				break;
			case "audios":
				$content_type_id = 5;
				$table           = "audio_";
				break;
			/*case "resources":
				$content_type_id = 6;
				$table           = "resources_";
				break;*/
			default:
				$content_type_id = 1;
				$table           = "article_";
		}
		if($article_hosted_year!=""){
		if($article_hosted_year >2015){
		$article_landing_details 	= $this->widget_model->widget_article_content_by_ecenic_id($ecenic_id, $content_type_id, $current_url, $ecenic);	
		}
		//print_r($article_landing_details);exit;
		if(count($article_landing_details) == 0)
		{
		$year                   = @$live_query_string[$article_hosted_year];
		if($content_type_id!=3){
        $table                  = $table.$year;
		}else if($content_type_id==3){
		$explore_table          = explode("," , $table);
        $table 		            = $explore_table[0].$year.",".$explore_table[1].$year;
		}		
		$article_landing_details     = $this->widget_model->widget_archive_article_content_by_id($ecenic_id, $content_type_id, $current_url , $table, $ecenic);
		}
		}else if($article_hosted_year==""){
		$article_landing_details 	= $this->widget_model->ecenic_content_without_year($ecenic_id, $content_type_id);
		}
		if(count($article_landing_details) == 0)
		{
		$article_landing_details 	= $this->widget_model->ecenic_content_byID($ecenic_id);	
		}
		if(count($article_landing_details)>0)
		{
			 $newURL = base_url().$article_landing_details[0]['url'];
			 redirect($newURL,'location',301);    // redirect ecenic url to current formatted url..
		}
		$content_id				 = (count($article_landing_details)>0)? $article_landing_details[0]['content_id']: "";
		$content_from            = "archive";
		$image_number			 = 1;	
		$viewmode		         = "live"; 
		}else{
		$article_landing_details = array();
		}
	}
		if(count($article_landing_details) > 0)
		{
			$url_section_id = ($content_from=="preview")? $article_landing_details[0]['Section_id']: $article_landing_details[0]['section_id'];
			$page_details 	= $this->widget_model->getArticleCommonPageDetails($url_section_id, $page_type);  
			
			 if(count($page_details)==0)
			 {
			 $page_details 	= $this->widget_model->getPageDetails("10000", $page_type); 
			 }
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
		$section_page_id = $page_details['menuid'];
		$xml				= simplexml_load_string($page_details['published_templatexml']);
		if(count($xml)>0)
		{
		$is_home_page    = 'n';
		$page_param         = ($this->input->get('pm')!='')? $this->input->get('pm'): $page_details['menuid'];
        $xml                = "";
		$xml				= simplexml_load_string($page_details['published_templatexml']);
		$tmpl_values        = "";
		$tmpl_values        = (string)$xml->attributes()->templatevalues;
		if($tmpl_values!="")
		{
		$tmpl_values 		= explode("-", $tmpl_values);	
		}else{
		// not use updated
		$template_id 	    = $page_details['templateid'];
		$template_details 	= $this->widget_model->getTemplateDetails($template_id); 
		$tmpl_values 		= explode("-", $template_details['template_values']);		
		}
		}
		//else
		//$section_details = $this->widget_model->get_sectionDetails($section_page_id , $view_mode); 
		if(count($xml)< 0 && $page_type==2)
		{
			//$section_details 	= $this->widget_model->get_sectionDetails($section_page_id , $view_mode);
			$parent_section_id	= $article_landing_details[0]['parent_section_id'];
			
			if($parent_section_id!='')
			{				
			$page_details       = $this->widget_model->getArticleCommonPageDetails($parent_section_id, $page_type); 
			$xml				= simplexml_load_string($page_details['published_templatexml']);
			$tmpl_values        = (strlen($xml)!=0)? (string)$xml->attributes()->templatevalues: "";
			if($tmpl_values!="")
			{
			$tmpl_values 		= explode("-", $tmpl_values);	
			}else{
			$template_id 	    = $page_details['templateid'];
			$template_details 	= $this->widget_model->getTemplateDetails($template_id); 
			$tmpl_values 		= explode("-", $template_details['template_values']);		
			} 		
			}
			else
			{
				$page_details       = $this->widget_model->getArticleCommonPageDetails(10000, $page_type);
				$xml				= simplexml_load_string($page_details['published_templatexml']);
				$tmpl_values        = (strlen($xml)!=0)? (string)$xml->attributes()->templatevalues: "";
				if($tmpl_values!="")
				{
				$tmpl_values 		= explode("-", $tmpl_values);	
				}else{
				$template_id 	    = $page_details['templateid'];
				$template_details 	= $this->widget_model->getTemplateDetails($template_id); 
				$tmpl_values 		= explode("-", $template_details['template_values']);		
				}	
			}
		}
		
		$data['viewmode']   = $view_mode; 
		$data['content_from']   = $content_from; 
		$header_param		= "";
		$footer_param		= "";
		$right_panel_param	= "";

		if(count($xml)!= 0)
		{
			
			$tplheader_values 	= $xml->tplcontainer;
			$page_type          = $page_details['pagetype'];
			if($page_type==2){
			$header_param 		= $tplheader_values[0];
			$body_param	        = $tplheader_values[count($tplheader_values)-3];
			$footer_param 		= $tplheader_values[count($tplheader_values)-1];

			$is_common_header   = $page_details['common_header'];
			$common_header_file = 'article_common_header.php';
			$header_file        = FCPATH.'application/views/view_template'.'/'.$common_header_file;
			$header_file_exist  = file_exists($header_file);  //get file contents from home.php
			if(($header_file_exist!= '' && $header_file_exist!=false) && $is_common_header==1){  //check file exist
			$data['header'] 	    = $this->load->view('view_template/article_common_header','', TRUE);
			$data['html_header']    = true;
			}else{
			$data['header'] 	= $this->template_library->article_xml_containers($header_param, "header", $content_id, $is_home_page, $view_mode, $image_number, $page_type, $page_param, $content_from, $content_type_id,'');
			}
			$data['body']	   = '<section class="section-content"><div class="container SectionContainer"><div class="row">';
			$template_values_body_content = explode(",",$tmpl_values[1]);
			$b_section_inc = 0;
			$loop_break_point = ($content_type_id==3 || $content_type_id == 4)? 2 : count($template_values_body_content);
			for($i=1; $i <= $loop_break_point; $i++){
			
				$body_section 	= $template_values_body_content;
				if($body_section[0]==7){ $body_section[0] = 9 ;}
				if($body_section[1]==5){ $body_section[1] = 3 ;}
				if($i==1){
					$section_cl_val	= ($content_type_id==3 || $content_type_id == 4)? 9 : $body_section[$b_section_inc] * (12 / array_sum($body_section));
				}else{
					$section_cl_val	= ($content_type_id==3 || $content_type_id == 4)? 3 : $body_section[$b_section_inc] * (12 / array_sum($body_section));
				}
				
				
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
				$pass_body_content = (($i) < count($template_values_body_content)) ? $tplheader_values[$i] : $tplheader_values[$i];			
				if($i==2 && $is_common_header==1){
				$common_right_file = 'article_common_rightpanel.php';
				$right_panel_file   = FCPATH.'application/views/view_template'.'/'.$common_right_file;
				$right_file_exist   = file_exists($right_panel_file);  //get file contents from home.php
				if(($right_file_exist!= '' && $right_file_exist!=false) && $is_common_header==1){  //check file exist
				$data['body'] 	    .= $this->load->view('view_template/article_common_rightpanel','', TRUE);
				$data['html_rightpanel']  = true;
				}else{
				$data['body'] 	  .= $this->template_library->article_xml_containers($pass_body_content, "template_body", $content_id, $is_home_page, $view_mode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $article_landing_details);
				}
				}else{
				$data['body'] 	  .= $this->template_library->article_xml_containers($pass_body_content, "template_body", $content_id, $is_home_page, $view_mode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $article_landing_details);	
				}
				$data['body']	  .= '</div>';
				
				$b_section_inc ++;
			}
			$data['body']	   .= '</div></div></section>';
			
			
			$is_common_header   = $page_details['common_header'];
			$common_footer_file = 'article_common_footer.php';
			$footer_file        = FCPATH.'application/views/view_template'.'/'.$common_footer_file;
			$footer_file_exist  = file_exists($footer_file);  //get file contents from home.php
			if(($footer_file_exist!= '' && $footer_file_exist!=false) && $is_common_header==1){  //check file exist
			$data['footer'] 	= $this->load->view('view_template/article_common_footer','', TRUE);
			}else{
			$data['footer'] 	= $this->template_library->article_xml_containers($footer_param, "footer", $content_id, $is_home_page, $view_mode, $image_number, $page_type, $page_param, $content_from, $content_type_id,'');
			}
			}else
			{
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
				
				$data['body'] 	  .= $this->template_library->section_xml_containers($pass_body_content, "template_body", $is_home_page,  $view_mode, $page_type, $page_param);			
				$data['body']	  .= '</div>';
				$b_section_inc ++;
			}
			$data['body']	  .= '</div></div></section>';

			$data['footer']   = $this->template_library->section_xml_containers($footer_param, "footer", $is_home_page, $view_mode, $page_type, $page_param);
			
			}
			
			$data['content_id']     	= $content_id;
			$data['content_type']	    = $content_type_id;
			$data['header_ad_script']	= $page_details['Header_Adscript'];
			$data['page_type']	        = $page_type;
			$data['article_details']	= $article_landing_details;
		}
		else   // if xml is not created condition will call this
		{
			$data['header'] 	= "";
			$data['body'] 		= "";
			$data['footer'] 	= "";
			
			$data['article_details']	= $article_landing_details;
		}
		  if($content_id!='' && $page_type=='2')
		  {
				$data['seotags'] = '';
				if($content_type_id==1){
					$data['seotags'] = $this->widget_model->getseotags($content_id);
				} 
				if($content_from=="live" || $content_from=="archive"){
				$this->load->view("admin/view_remodal_article", $data);
				}elseif($content_from=="preview"){
				$this->load->view("admin/view_remodal_article_preview", $data);
				}
		  }else if($page_type=='1')
		  {  //section
		        $section_details = $this->widget_model->get_sectionDetails($section_page_id , $view_mode); 
				$data['section_details']	= $section_details;
				if($data['section_details']['URLSectionName']=="404-not-found"){
				$this->output->set_status_header('404');
				}
				$this->load->view("admin/view_frontend", $data); 
		  }
	}
}
?>