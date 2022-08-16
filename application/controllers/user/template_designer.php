<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_designer extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url'); 		
		$this->load->helper('file');
		$this->load->helper('xml');
		
		$this->load->model('admin/comment_model');
		
       	$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archieve_db = $CI->load->database('archive_db', TRUE);
		
		$this->load->driver('cache', array('adapter' => 'file'));

	} 
	
	public function index()
	{
	  $this->load_saved_template();
	}

	public function clear_cache() {
		$this->cache->clean();
	}
		
	public function fill_widget($widget_location ,$content)
	{			
		$string                     = '';
		$domain_name 				= base_url();
		$widget_section_url         = '';
		$widget_section_id          = '';
		$widget_sectionname_link    ='';
		$special_parent_section     = array();
		$clone_instance_id			= "";
		$show_widget				= true;
		$pass_widget_instance		= (isset($content['widget_values']['data-clonedinstanceid']) && $content['widget_values']['data-clonedinstanceid'] != "") ? $clone_instance_id = $content['widget_values']['data-widgetinstanceid'] = $content['widget_values']['data-clonedinstanceid'] : $content['widget_values']['data-widgetinstanceid'];
		$widget_instance_details    = $this->widget_model->getWidgetInstance('', '','', '', $pass_widget_instance, $content['mode']);	 //live db
		//print_r($widget_instance_details);
		if(count($widget_instance_details)>0){
			
			if($clone_instance_id != ''){
				if($content['widget_values']['data-widgetstyle'] == 2){
						$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $clone_instance_id, 'live');	
						//print_r($widget_instancemainsection);
						if(count($widget_instancemainsection)>0){
								foreach($widget_instancemainsection as $key => $tab_values){
									$widgettab_child = $content['widget_values']->addChild('widgettab');
									$widgettab_child->addAttribute('cdata-categoryId', $tab_values['Section_ID']);
									$widgettab_child->addAttribute('cdata-categoryName', $tab_values['CustomTitle']);
									$widgettab_child->addAttribute('cdata-customTitle', $tab_values['CustomTitle']);
									$widgettab_child->addAttribute('cdata-categoryType', $tab_values['Section_Content_Type']);
									$widgettab_child->addAttribute('cdata-categoryTypeName', $tab_values['Section_Content_Type']);
							}
						}
					}
					//print_r($widget_instancemainsection);
					//print_r($content['widget_values']); 
					 $content['widget_values']['cdata-showSummary'] = $widget_instance_details['isSummaryRequired'];
					 if($widget_instance_details['status'] == 1 ){
						  if( ( $widget_instance_details['publish_start_date']=='0000-00-00 00:00:00' || strtotime($widget_instance_details['publish_start_date'])=='' || ((strtotime('now') > strtotime($widget_instance_details['publish_start_date'])) )) && (((strtotime($widget_instance_details['publish_end_date'])=='' || $widget_instance_details['publish_end_date'] =='0000-00-00 00:00:00' || (strtotime('now') < strtotime($widget_instance_details['publish_end_date'])))))){
							$show_widget = true;							
						}else{
							$show_widget = false;
						} 
					 }else{
						$show_widget = false;
					} 
				 }
		if($show_widget){
		if($widget_instance_details['WidgetSection_ID'] != '' && $widget_instance_details['WidgetSection_ID'] != "0")
		{
			$widget_section_details = $this->widget_model->get_section_by_id($widget_instance_details['WidgetSection_ID']); //live db
			$widget_custom_title    = ($widget_instance_details['CustomTitle']!= '') ? $widget_instance_details['CustomTitle'] : $widget_section_details['Sectionname'];
			if($widget_instance_details['CustomTitle']!= '' && $widget_instance_details['WidgetSection_ID']=='0')
			{
				$widget_sectionname_link = 0;
				$widget_section_id       = '';
				$widget_section_url      = '';
			}
			else
			{
				$widget_sectionname_link = 1;
				$widget_section_id       = $widget_instance_details['WidgetSection_ID'];
				$widget_section_url      = $domain_name.$widget_section_details['URLSectionStructure'];
			}
		}
		else
		{
			$widget_custom_title = ($widget_instance_details['CustomTitle']!= '') ? $widget_instance_details['CustomTitle'] : "";
		}
		
		$content_type_names			       = array("None"=>1,"Article" => 2,"Gallery" => 3,"Video" => 4,"Audio" => 5);
		$content_type_name			       = array_search($content['widget_values']['data-contenttype'], $content_type_names);
		
		
		$content_type_id			       = $this->widget_model->get_content_type_byname($content_type_name, $content['mode']);	
		$content['content_type_id']        = (count($content_type_id) > 0) ? $content_type_id['contenttype_id'] : '' ;
		$content['widget_title'] 	       = $widget_custom_title;
		
		$content['sectionID']              = $widget_section_id;
		$content['widget_title'] 	       = $widget_custom_title;
		$content['widget_title_link']      = $widget_sectionname_link;
		
		$content['widget_bg_color']        = "style='background-color:".@$widget_instance_details['Background_Color']. ";'";		
		$content['show_max_article']       = $widget_instance_details['Maximum_Articles'];
		$content['RenderingMode'] 	       = $widget_instance_details['RenderingMode'];
				
		$mode                              = $content['mode'];		
		$widget_custom_title               = $content['widget_title'];
				
		$content['widget_section_url']     = $widget_section_url;
		
		$data['content'] = $content;
		if($content['content_from']== "live"){		
		$file_name                         = FCPATH.'/application/views/'.$widget_location.".php";
		}else if($widget_location=="admin/widgets/article_details" && $content['content_from']== "preview"){	
		$file_name                         = FCPATH."/application/views/admin/widgets/article_details_preview.php";
		$widget_location                   = "admin/widgets/article_details_preview";
		}else{
		$file_name                         = FCPATH.'/application/views/'.$widget_location.".php";
		}

		if (file_exists($file_name)) {
			$string = $this->load->view($widget_location, $data, true);			
		} else {
			$string = '<div class="row">The file '.$file_name.' does not exist</div>';
		}
		} 
	  }
		return $string;
		
	}
	
	public function get_parent_article_page($parent_section_id, $page_type)
	{
		//$page_details 		= $this->template_design_model->getPageDetails($parent_section_id, 'article');	// cms db
		$page_details 		= $this->widget_model->getPageDetails($parent_section_id, $page_type);	// live db	
		$xml				= (count($page_details)>0)? simplexml_load_string($page_details['published_templatexml']) : "";
		if(count($page_details)>0 && strlen($xml)!= 0){
		$xml				= simplexml_load_string($page_details['published_templatexml']);	
		if(strlen($xml) == 0 && $page_details['menuid'] != 10000)
		{	
			
			//$section_details = $this->template_design_model->get_section_by_id($page_details['menuid']); // cms db
			$section_details = $this->widget_model->get_section_by_id($page_details['menuid']); // live db
			//print_r($section_details); exit;
			
			if($section_details['IsSubSection'] == '1')
			{	
							
				$xml = $this->get_parent_article_page($section_details['ParentSectionID'], $page_type);
			}
			else
			{	
				/////  Is not a Sub section
				//$page_details 		= $this->template_design_model->getPageDetails($section_details['Section_id'], 'article');	//cms db
				$page_details 		= $this->widget_model->getPageDetails($section_details['Section_id'], $page_type);	//live db							
				$xml				= simplexml_load_string($page_details['published_templatexml']);
				if(strlen($xml) == 0)
				{					
					//////  Standard Article page xml content  ///
					return $this->get_parent_article_page(10000, $page_type);					
				}
				else
				{					
					return $page_details;
				}
			}			
		}
		else
		{
			return $page_details; 
		}
		}elseif($page_type==2)
		{
			return $this->get_parent_article_page(10000, $page_type);	
		}
				
		
	}

	public function load_saved_template()
	{				
		$this->load->library("memcached_library");
		$this->memcached_library->flush();

		$this->load->model('admin/widget_model');
		//print_r(current_url());exit;
		$uri_string= explode('/',$this->uri->uri_string());
		$total = $this->uri->total_segments();
		$last_segment = $this->uri->segment($total);
		$last_seg = explode('-',$last_segment);
        $pos = strrpos(end($last_seg), ".html");  //$last_seg[0]
		$content_exist = false;
		$content_id = '';
		//print_r(strrpos($last_segment, ".html"));exit;
		$url_section_details = array();
		if ($pos != false) { 
		$content_exist = true;
		}
		if((($uri_string[count($uri_string)-1])!='rssfeed'))
		{
		$i = 1;	
		$image_number = '';
        $content_from ="";
	if($this->uri->segment(1) != "niecpan") /////  For LIVE URL Content
	{			
		//////  Load Home page Template details  ///
		if($this->uri->segment(1) == "")
		{
			$page_type		     = "1"; //section
			$url_section_details = $this->widget_model->get_sectionid_with_names("Home", "" , "");
			if(count($url_section_details)>0)
			{	 
			$page_details 		= $this->widget_model->getPageDetails($url_section_details[0]['Section_id'], $page_type);  
			}
			$content_id		    = "";
			$is_home_page       = "home";
			$viewmode			= "live";
		}
		else if($this->uri->segment(1) != "niecpan")
		{
			$live_query_string		= explode("-",$this->uri->uri_string());	
			$uri_string= explode('/',$live_query_string[count($live_query_string)-1]);
			$last_uri = $uri_string[count($uri_string)-1];
			if($this->uri->segment(1)=='topic'){
				$page_type		= "1"; //section
				$special_section	= '';
				$url_parent_section = '';
				$url_sub_section 	= $this->uri->segment(1);
				$url_section_details= $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);   //live db	
			} 
			else if(strtolower($this->uri->segment(1)) == 'author') 
			{
				$page_type		= "1";  //section
				$special_section	= '';
				$url_parent_section = '';
				$url_sub_section 	= $this->uri->segment(1);
				if($total == 1)
				{
				 $newURL = base_url()."columns";
				 redirect($newURL,'location',301);    // redirect Author page to columns page..
				}
				$url_section_details= $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	 //live db
			}
			else if($content_exist && !empty($last_uri))
			{
				          /******************** It is article page **************************/
				$page_type		         = "2";		//article
				$image_number	         = 1;	
				$url_seg                 = explode(".", $last_segment);
				$split_uri 	             = preg_split('~--(?=[^--]*$)~', $url_seg[0]);
				$content_id_from_url     = (count($split_uri)>=2)? end(explode("-", $split_uri[0])):  end(explode("-", $split_uri[0]));
				$content_id              = (!is_numeric($content_id_from_url))? $split_uri[1] :$content_id_from_url; 
				$image_number	         = (count($split_uri)>=2)? ((!is_numeric($content_id_from_url))? 1: $split_uri[1]): 1;
				$content_from            = "live";

			  //  print_r($this->uri->uri_string());exit;
			    $current_url      = trim(str_replace(base_url(), " ", current_url()));
				
				$preview_article  = isset($_GET['page']) ? $_GET['page'] : '';
				$content_from     = ($preview_article!='')? 'preview' : 'live';
				
				$live_query_string = explode("/",$this->uri->uri_string());
				$year              = $live_query_string[count($live_query_string)-4];
				
				$sectionname       = strtolower($this->uri->segment(1));
				switch ($sectionname) {
					case ($sectionname == "galleries" || $sectionname == "photos"):
						$content_type_id = 3;
						$table           = "gallery_".$year.","."gallery_related_images_".$year;
						/*if(is_numeric(prev($split_uri)))
						{
						$content_id      = $split_uri[count($split_uri)-2];
						$image_number    = end($split_uri);	
						
						array_pop($live_query_string);
						$last_segment_array  = explode("-", $url_seg[0]);
						array_pop($last_segment_array);
						$section_string      = join("/", $live_query_string);
						$article_string      = join("-",($last_segment_array));
						$current_url = $section_string."/".$article_string.".html";
						}*/
						/* Generate url if page index available*/
						if(count($split_uri)>=2)
						{
						array_pop($live_query_string);
						$section_string      = join("/", $live_query_string);
						$article_string      = join("-", explode("-", $split_uri[0]));
						$current_url         = $section_string."/".$article_string.".html";
						}
						break;
					case "videos":
						$content_type_id = 4;
						$table           = "video_".$year;
						break;
					case "audios":
						$content_type_id = 5;
						$table           = "audio_".$year;
						break;
					case "resources":
						$content_type_id = 6;
						$table           = "resources_".$year;
						break;
					default:
						$content_type_id = 1;
						$table           = "article_".$year;
						/*if(is_numeric(prev($split_uri)))
						{
						$content_id          = $split_uri[count($split_uri)-2];
						$image_number        = end($split_uri);	
						array_pop($live_query_string);
						$last_segment_array  = explode("-", $url_seg[0]);
						array_pop($last_segment_array);
						$section_string      = join("/", $live_query_string);
						$article_string      = join("-",($last_segment_array));
						$current_url         = ($image_number < 50)? $section_string."/".$article_string.".html" : $current_url;
						$content_id          = ($content_id < $image_number)? $image_number : $content_id;
						}*/
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

				}
					
			if($content_from =="live" || $content_from ==""){
			$url_section_details 	= $this->widget_model->widget_article_content_by_id($content_id, $content_type_id, $current_url);	
			//print_r($url_section_details);exit;
			if(count($url_section_details) == 0)
			{
			$url_section_details     = $this->widget_model->widget_archive_article_content_by_id($content_id, $content_type_id, $current_url , $table, 0); // last parameter denotes ecenic or not	
			$content_from = "archive";
			}
			}
			else if($content_from =="preview"){
			$url_section_details 	= $this->widget_model->widget_article_content_preview($content_id, $content_type_id);	
			//print_r($url_section_details);exit;
			}
			$viewmode		= "live"; 
		  }
		  else if(substr($this->uri->uri_string(),-3) == 'ece')
		  {
			$ecenic                 = 1;
			$page_type				= "2";  //article
			$live_query_string 	    = explode("/",$this->uri->uri_string());
			$old_site_article 		= explode(".",$live_query_string[count($live_query_string)-1]);	
			$old_site_article_id 	= explode("article",$old_site_article[0]);
			$current_url            = "";
			if(is_numeric($old_site_article_id[1])){	
			$ecenic_id              = $old_site_article_id[1];
			$year                   = $live_query_string[count($live_query_string)-4];
		   $sectionname             = strtolower($this->uri->segment(1));
			switch ($sectionname) {
				case ($sectionname == "galleries" || $sectionname == "photos"):
					$content_type_id = 3;
					$table           = "gallery_".$year.","."gallery_related_images_".$year;
					break;
				case "videos":
					$content_type_id = 4;
					$table           = "video_".$year;
					break;
				case "audios":
					$content_type_id = 5;
					$table           = "audio_".$year;
					break;
				case "resources":
					$content_type_id = 6;
					$table           = "resources_".$year;
					break;
				default:
					$content_type_id = 1;
					$table           = "article_".$year;
			}
			
			$url_section_details     = $this->widget_model->widget_archive_article_content_by_id($ecenic_id, $content_type_id, $current_url , $table, $ecenic);
			
			if(count($url_section_details)>0)
			{
				 $newURL = base_url().$url_section_details[0]['url'];
				 redirect($newURL,'location',301);    // redirect ecenic url to current formatted url..
			}
			$content_id				 = (count($url_section_details)>0)? $url_section_details[0]['content_id']: "";
			$content_from            = "archive";
			$image_number			 = 1;	
			$viewmode		         = "live"; 
			}else{
			$url_section_details     = array();
			}
		}
		else
		{
			////  It is section Page  ////
			$page_type		= "1";    //section
			$live_query_string 	= explode("/",$this->uri->uri_string());
			
				switch(count($live_query_string))
				{
					case 1:
						////  Find the section Id with parent section name, sub section name  //////
						////  Parent section name = $live_query_string[0], sub section name = $live_query_string[1]  /////
						$special_section	= '';
						$url_parent_section = '';
						$url_sub_section 	= $live_query_string[0];
						$url_section_details= $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	//live db
					break;
					
					case 2:
						 ////  Find the section Id with parent section name, sub section name  //////
						////  Parent section name = $live_query_string[0], sub section name = $live_query_string[1]  /////
						$special_section	 = '';
						$url_sub_section     = '';
						$url_parent_section  = $live_query_string[0];
						$url_sub_section 	 = $live_query_string[1];
						$url_section_details = $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	//live db	
					break;
					
					case 3:
						////  Find the section Id with parent section name, sub section name  //////
						////  Parent section name = $live_query_string[0], sub section name = $live_query_string[1]  /////
						$special_section	= $live_query_string[0];
						$url_parent_section = $live_query_string[1];
						$url_sub_section 	= $live_query_string[2];
						$url_section_details= $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	 //live db					
				}	
			}	
       }
		if(count($url_section_details) > 0)
		{
			if($content_id!='')
			{
			$url_section_id = ($content_from =="live" || $content_from =="" || $content_from =="archive")? $url_section_details[0]['section_id'] : $url_section_details[0]['Section_id'] ;
			$page_details 	= $this->widget_model->getPageDetails($url_section_id, $page_type);  
			 if(count($page_details)==0)
			 {
			 $page_details 	= $this->widget_model->getPageDetails("10000", $page_type); 
			 }
			 $is_home_page  = "";
			}
			else
			{
			$page_details 	= $this->widget_model->getPageDetails($url_section_details[0]['Section_id'], $page_type);   
			$is_home_page       = "no";
			}
			$viewmode		= "live";	
		}
		else
		{
			$url_section_details= $this->widget_model->get_sectionid_with_names("404-not-found", "", "");	//live db
			if(count($url_section_details) > 0)
		    {
			$page_details 	= $this->widget_model->getPageDetails($url_section_details[0]['Section_id'], "1");	
			$is_home_page       = "no";
			$viewmode		= "live";	
			$page_type		= "1";    //section
			}else
			{
			 /******  Section Page Not Found  ********/
			 echo $this->load->view("admin/page_not_found_404", '', true);
			 exit;
			}
		}

	    }
		
		if($page_details['pagetype']=='1'){   // section
		$section_file = join( "-",( explode(" ", $page_details['menuid']))).'.php';
		$file = FCPATH.'application/views/view_template'.'/'.$section_file;
		$check_file_exist = file_exists($file);
		}
		if($page_details['pagetype']=='1' && $check_file_exist!='' && $check_file_exist!='0'){  //section
		$ExpireTime = 600; // seconds (= 10 mins)
		$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		$this->output->set_header("Cache-Control: cache, must-revalidate");
		$this->output->set_header("Cache-Control: max-age=".$ExpireTime);
		$this->output->set_header("Pragma: cache");
		$this->load->view("view_template/".$section_file, ''); 	
		}else{
		$static_view = (isset($_GET['type']) =='static') ? 'static' : '';
		$section_details = $this->widget_model->get_section_by_id($page_details['menuid']); //live db
		$section_page_id = $page_details['menuid'];
		$is_home_page = ($is_home_page!='') ? strtolower($section_details['Sectionname']) : 'common';
		$is_home_page = ($is_home_page == 'home') ? 'y' : 'n';
		
		if($is_home_page=='y'){
		$page_param = 'home';
		}else if(isset($_GET['pm'])!=''){
		$page_param = $_GET['pm'];
		}else{
		$page_param = $page_details['menuid'];
		}
        $xml                = "";
		$template_id 		= $page_details['templateid'];
		
		$template_details 	= $this->widget_model->getTemplateDetails($template_id); //live db
		$tmpl_values 		= explode("-", $template_details['template_values']);		
		$xml				= simplexml_load_string($page_details['published_templatexml']);   // load from db
		if($page_details['common_header']==1 && $page_type ==2){
		$common_xml         = $this->get_parent_article_page(10000, $page_type);
		$xml                = simplexml_load_string($common_xml['published_templatexml']);
		$template_id        = $common_xml['templateid'];
		$template_details 	= $this->widget_model->getTemplateDetails($template_id); //live db
		$tmpl_values 		= explode("-", $template_details['template_values']);		
		}
		
		if(strlen($xml) == 0 && $page_type==2)
		{
			if($section_details['IsSubSection'] == '1')
			{				
				$page_details = $this->get_parent_article_page($section_details['ParentSectionID'], $page_type);	
				$template_id 		= $page_details['templateid'];
				$template_details 	= $this->widget_model->getTemplateDetails($template_id);  // live db
				$tmpl_values 		= explode("-", $template_details['template_values']);		
				$xml				= simplexml_load_string($page_details['published_templatexml']);
				
			}
			else
			{
				$page_details = $this->get_parent_article_page(10000, $page_type);
				$template_id 		= $page_details['templateid'];
				$template_details 	= $this->widget_model->getTemplateDetails($template_id);		// live db				
				$tmpl_values 		= explode("-", $template_details['template_values']);		
				$xml				= simplexml_load_string($page_details['published_templatexml']);	
			}
		}
		
		$data['viewmode'] = $viewmode; 
		if(strlen($xml)!= 0)
		{
			$tplheader_values 	= $xml->tplcontainer;
			$page_type= $page_details['pagetype'];
			if($page_type =='1'){	 //section
			$content_id ='';
			$content_type_id='';
			$data['header'] 	= $this->load_saved_template_data($tplheader_values, 'top', "header", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details,'');
			$data['body'] 		= $this->load_saved_template_data($tplheader_values, 'left', "template_body", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details,'');
			$data['footer'] 	= $this->load_saved_template_data($tplheader_values, 'footer', "footer", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details,'');
			}else
			{
			if($content_type_id=='1' || $content_type_id=='3' || $content_type_id=='4' || $content_type_id=='5' || $content_type_id=='6'){
			$data['header'] 	= $this->load_saved_template_data($tplheader_values, 'top', "header", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, $url_section_details);
			$data['body'] 		= $this->load_saved_template_data($tplheader_values, 'left', "template_body", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, $url_section_details);
			$data['footer'] 	= $this->load_saved_template_data($tplheader_values, 'footer', "footer", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, $url_section_details);
			}
			}
			$data['header_ad_script']	= $page_details['Header_Adscript'];
			
			$page_type= $page_details['pagetype'];
			$data['page_type']	= $page_type;
			
			if($page_type=='1'){  //section
			
			/*if($content_from =="live" || $content_from ==""){
			$this->widget_model->update_pagecount($section_details['Section_id']);
			$this->widget_model->update_section_hithistory($section_details['Section_id']);
			}*/
			$data['section_details']	= $this->widget_model->get_sectionDetails($section_page_id, $viewmode);
			if($data['section_details']['URLSectionName']=="404-not-found"){
			$this->output->set_status_header('404');
			}
			$this->load->view("admin/view_frontend", $data); 
			}else{
			$data['article_details']	= $url_section_details;
			//print_r($data);exit;
			if($content_from=="live" || $content_from=="archive"){
			$this->load->view("admin/view_remodal_article", $data);
			}elseif($content_from=="preview"){
			$this->load->view("admin/view_remodal_article_preview", $data);
			}
			}
		}
		else   // if xml is not created condition will call this
		{
			$data['header'] 	= "";
			$data['body'] 		= "";
			$data['footer'] 	= "";
		}
		}
		}else{
		$uri_string= explode('/',$this->uri->uri_string());
		//print_r($uri_string[count($uri_string)-2]);exit;
		$section_name = join( " ",( explode("-", $uri_string[count($uri_string)-2] ) ) );
		if($uri_string[count($uri_string)-2] == "Shampa-Dhar-Kamath")
		{ 
		$section_name 	= "Shampa Dhar-Kamath";
		 }
		//$section_id = $this->widget_model->get_sectionid_by_name($section_name);
		$section_id = @$_GET['id'];
		if($section_id == '')
		{
		 echo $this->load->view("admin/page_not_found_404", '', true);
		exit;
		}
		$section_id =  $this->widget_model->get_section_by_id($section_id);

		if($section_id['ParentSectionID']!=''&& $section_id['ParentSectionID']!=0 ){
		$parent_section        = $this->widget_model->get_section_by_id($section_id['ParentSectionID']);
		$data['Parentsection'] = ($parent_section['Sectionname']!='')? $parent_section['Sectionname'] : '';
		}else{
		$data['Parentsection'] = '';
		}
		$data['url_section'] = base_url().$section_id['URLSectionStructure'];
		$sectionname = strtolower($this->uri->segment(1));
		switch ($sectionname) {
			case ($sectionname == "galleries" || $sectionname == "photos"):
				$content_type = 3;
				break;
			case "videos":
				$content_type = 4;
				break;
			case "audios":
				$content_type = 5;
				break;
			case "resources":
				$content_type = 6;
				break;
			default:
				$content_type = 1;
		}
		$data['content_type'] = $content_type;
		$data['Section']     = $section_id['Sectionname'];
		$data['rss_article'] = $this->widget_model->rss_section_articles($section_id['Section_id'], $content_type);
		$this->load->view("admin/rssfeed", $data);
		unset($data);
		}
		
		
	}
	
	public function load_saved_template_data($tplheader_values, $file_type, $file_name, $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, $content_details)
	{				
		if($page_type == 1)
		{
		$templateString = $this->section_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, "");	
		}else
		{
		$templateString = $this->article_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, $content_details);
		}	
		//$this->load->view("admin/common_template/".$file_name, $templateString, false);
		$view = $this->load->view("admin/common_template/".$file_name, $templateString, true);
		return $view;
	}
	
	public function article_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, $content_details)
	{
	 $b_section_inc = 0;
		$templateString['view_templ'] = '';
		if($file_type == "left") ///// only for Body section content
		{
			$templateString['view_templ'] .= '<div class="row">';
		}
		if($content_type_id =='1'){	

		foreach($tplheader_values as $key => $values)
		{
			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];			
			if($find_layout == $file_type)
			{								
				
				if($file_type == "left") ///// only for Body section content
				{
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					//if($b_section_inc != (count($body_section)-1) && $b_section_inc > 0 && count($body_section) > 0)
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{
						//$home_last_column = "HomeLastColumn";
						$home_last_column = "ColumnSpaceRight";
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
                    
					
					$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
					$b_section_inc ++;
				}
				//print_r($values->widgetcontainer); 
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					$templateString['view_templ'] .= '<div class="row">';
					
					/////////////  Chosen Container ////////////////							
					//////  Retrieve the Container details using container id ( type ) ///////
					
					//$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']);	// cms db
					$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']);	// live db
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					//print_r($cvalues->widget);
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						$c_span_val = $container_layout[$c_inc];	
						
						$padding_zero = "";
						/*if($c_inc > 0 && $c_inc <(count($container_layout)-1))
						{
							$padding_zero = "padding-0";
						}*/
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						////////  Added Widgets  ///////////
						if($wvalues['id'] != '')
						{															
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> $image_number,
													 "page_param"       => $page_param,
													 "content_from"     => $content_from,
													 "content_type"     => $content_type_id,
													 "detail_content"   => $content_details,

												);	
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
					     	$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;	
					}
				$templateString['view_templ'] .= '</div>';		
				}// close - foreach($values->widgetcontainer)							
				if($file_type == "left") ///// only for Body section content
				{
					$templateString['view_templ'] .= '</div>';
				}
			}// close - if($find_layout);			
		}// close -  foreach($tplheader_values)
       
	   }else{
	   $lw=1;$lh=1;
	   foreach($tplheader_values as $key => $values)
		{
			
			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];			
			if($find_layout == $file_type)
			{								
				if($file_type == "top" || $file_type == "footer") // for header and footer in Content type 3 & 4
				{
					if($lh==1){
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					//if($b_section_inc != (count($body_section)-1) && $b_section_inc > 0 && count($body_section) > 0)
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{
						//$home_last_column = "HomeLastColumn";
						$home_last_column = "ColumnSpaceRight";
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
					
					$c_class_value 	= " col-lg-12"." col-md-12"." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ";
 
					//$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
					$b_section_inc ++;
					
					$hw=1; 
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					if($hw==1){
					$templateString['view_templ'] .= '<div class="row">';
					
					/////////////  Chosen Container ////////////////
												
					//////  Retrieve the Container details using container id ( type ) ///////
					//$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']); // cms db
					$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']); //live db	
						
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						$c_span_val = $container_layout[$c_inc];	
						
						$padding_zero = "";
						/*if($c_inc > 0 && $c_inc <(count($container_layout)-1))
						{
							$padding_zero = "padding-0";
						}*/
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						////////  Added Widgets  ///////////
						
						if($wvalues['id'] != '')
						{	
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> $image_number,
                                                     "page_param"       => $page_param,
													 "content_from"     => $content_from,
													 "content_type"     => $content_type_id,
													 "detail_content"   => $content_details,
												);	
												
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
							$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;	
					}
				$templateString['view_templ'] .= '</div>';
				}		
				}// close - foreach($values->widgetcontainer)	
				
				if($file_type == "top" || $file_type == "footer") // for header and footer in Content type 3 & 4
				{
					//$templateString['view_templ'] .= '</div>';
				}
					}
					$lh++;
				}
				//print_r($values->widgetcontainer);
										
				
			}// close - if($find_layout);	
		
		}
		foreach($tplheader_values as $key => $values)
		{

			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];			
			if($find_layout == $file_type)
			{								

				if($file_type == "left") ///// only for Body section content
				{
					if($lw==1){
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					//if($b_section_inc != (count($body_section)-1) && $b_section_inc > 0 && count($body_section) > 0)
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{
						//$home_last_column = "HomeLastColumn";
						$home_last_column = "ColumnSpaceRight";
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
					
					$c_class_value 	= " col-lg-12"." col-md-12"." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ";
 
					$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
					$b_section_inc ++;
					
					$w=1; 
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					if($w==1){
					$templateString['view_templ'] .= '<div class="row">';
					
					/////////////  Chosen Container ////////////////							
					//////  Retrieve the Container details using container id ( type ) ///////
					//$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']);	//cms db
					$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']);	 // live db	
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						$c_span_val = $container_layout[$c_inc];	
						
						$padding_zero = "";
						/*if($c_inc > 0 && $c_inc <(count($container_layout)-1))
						{
							$padding_zero = "padding-0";
						}*/
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
																		

						////////  Added Widgets  ///////////
						if($wvalues['id'] != '')
						{															
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> $image_number,
                                                     "page_param"       => $page_param,
													 "content_from"     => $content_from,
													 "content_type"     => $content_type_id,
													 "detail_content"   => $content_details,
												);	
												
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
							$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;	

					}
				$templateString['view_templ'] .= '</div>';
				}		
				}// close - foreach($values->widgetcontainer)	
				
				if($file_type == "left") ///// only for Body section content
				{
					$templateString['view_templ'] .= '</div>';
				}
					}
					$lw++;
				}
				//print_r($values->widgetcontainer);
										
				
			}// close - if($find_layout);	
		
		}// close -  foreach($tplheader_values)
					
	   }
		if($file_type == "left") ///// only for Body section content
		{
			$templateString['view_templ'] .= '</div>';
		}
		
		return $templateString;
	}
	public function section_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $page_details, $content_details){
	$b_section_inc = 0;
		$templateString['view_templ'] = '';
		if($file_type == "left") ///// only for Body section content
		{
			$templateString['view_templ'] .= '<div class="row">';
		}
		if($page_details['common_header']==1 && $file_type=='top'){
			if(file_exists(FCPATH.'uploads/page_layouts/'.'10000_'.$page_type.'.xml')){
			$xml          = simplexml_load_file(FCPATH.'uploads/page_layouts/'.'10000_'.$page_type.'.xml');   // load from folder path
			}else{
			$common_xml   = $this->get_parent_article_page(10000, $page_type);
		    $xml          = simplexml_load_string($common_xml['published_templatexml']);
			}
			$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
			}else if($page_details['common_footer']==1 && $file_type =='footer'){
			if(file_exists(FCPATH.'uploads/page_layouts/'.'10000_'.$page_type.'.xml')){
			$xml = simplexml_load_file(FCPATH.'uploads/page_layouts/'.'10000_'.$page_type.'.xml');   // load from folder path
			}else{
			$common_xml   = $this->get_parent_article_page(10000, $page_type);
		    $xml          = simplexml_load_string($common_xml['published_templatexml']);
			}
			$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
			}
			$lvalue=0;
	if(count($tplheader_values)>0)
	{
		foreach($tplheader_values as $key => $values)
		{
			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];
			if($find_layout == $file_type)
			{								
				
				if($file_type == "left") ///// only for Body section content
				{
					
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					//if($b_section_inc != (count($body_section)-1) && $b_section_inc > 0 && count($body_section) > 0)
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{
						//$home_last_column = "HomeLastColumn";
						//$home_last_column = "ColumnSpaceRight";
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
						/* if($b_section_inc == 0)
						{
							$col_sm_val		= "3";
						}
						if($b_section_inc == 1)
						{
							$col_sm_val		= "9";
						} */
						if(($section_cl_val == 3 || $section_cl_val == 6 ) && array_sum($body_section) == 4){
						}
						else{
							if($b_section_inc == 0)
							{
								$col_sm_val		= "3";
							}
							if($b_section_inc == 1)
							{
								$col_sm_val		= "9";
							}
						}
					}
					
					if(($lvalue==1 || $lvalue==2)  && $page_details['common_rightpanel']==1 && $home_last_column==''){
					if(file_exists(FCPATH.'uploads/page_layouts/'.'10000_'.$page_type.'.xml')){
					$xml = simplexml_load_file(FCPATH.'uploads/page_layouts/'.'10000_'.$page_type.'.xml');   // load from folder path
					}else{
					$common_xml   = $this->get_parent_article_page(10000, $page_type);
					$xml          = simplexml_load_string($common_xml['published_templatexml']);
					}
					$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
					
					foreach($tplheader_values as $key => $values)
						{
							$layout = explode("-", $values['name']);
				$find_layout = $layout[count($layout)-1];
				if($find_layout == $file_type)
				{
							if($file_type == "left" && ($lvalue==1 || $lvalue==2)) ///// only for Body section content
					{
							$right_panel = $values;
					}
				}
						}
						$values = $right_panel;
						
					}
				
					$c_class_value 	= " col-lg-".$section_cl_val." col-md-".$section_cl_val." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ".$home_last_column." ";
                    
					
					$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
					$b_section_inc ++;
					$lvalue++;
				}
				//print_r($values->widgetcontainer); 
								
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					$templateString['view_templ'] .= '<div class="row">';
					
					/////////////  Chosen Container ////////////////							
					//////  Retrieve the Container details using container id ( type ) ///////
					//$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']); // cms db
					$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']); // live db		
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					//print_r($cvalues->widget);
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						if (isset($container_layout[$c_inc])) 
						{
						   $c_span_val = $container_layout[$c_inc];	
						}
						else
						{
							$c_span_val = "";	
						}
						//$c_span_val = $container_layout[$c_inc];	
						
						$padding_zero = "";
						/*if($c_inc > 0 && $c_inc <(count($container_layout)-1))
						{
							$padding_zero = "padding-0";
						}*/
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						////////  Added Widgets  ///////////
						if($wvalues['id'] != '' && $wvalues['cdata-widgetStatus']!=2 )   //load only status active widget 
						{	
						date_default_timezone_set("Asia/Kolkata");														
						if( (($wvalues['cdata-widgetpublishOn']=='' || $wvalues['cdata-widgetpublishOn']=='undefined') ||($wvalues['cdata-widgetpublishOn']!='undefined' && (strtotime('now') > strtotime($wvalues['cdata-widgetpublishOn'])) )) && ((($wvalues['cdata-widgetpublishOff']=='' || $wvalues['cdata-widgetpublishOff']=='undefined') || $wvalues['cdata-widgetpublishOff']!='undefined' && (strtotime('now') < strtotime($wvalues['cdata-widgetpublishOff'])) )) ) //check schedule status
						{														
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> $image_number,
                                                     "page_param"       => $page_param,
													 "content_from"     => $content_from,
												);	
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
							$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						}   // schedule check ends here
						}
						else if($wvalues['cdata-widgetStatus']==2 ){
						    $templateString['view_templ'] .= '';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;	
					}
				$templateString['view_templ'] .= '</div>';		
				}// close - foreach($values->widgetcontainer)							
				if($file_type == "left") ///// only for Body section content
				{
					$templateString['view_templ'] .= '</div>';
				}
			}// close - if($find_layout);			
		}// close -  foreach($tplheader_values)
  }
       
	   if($file_type == "left") ///// only for Body section content
		{
			$templateString['view_templ'] .= '</div>';
		}
		
		return $templateString;
					
	}
	
	public function page_not_found_404()
	{
		//echo $this->load->view("admin/page_not_found_404", '', true);
		show_404();
		//show_403();
		exit;
	}
}?>