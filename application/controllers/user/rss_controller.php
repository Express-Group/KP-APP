<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rss_controller extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
		$this->load->model("admin/widget_model");
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
	} 
	
	public function index()
	{
		$view_mode           = "live";
		$page_type		     = "1"; //rss section
		$this->load->library("memcached_library");
		//$this->memcached_library->flush();  // To clear the Memacache  , Un Comment this line
		$this->load->library("template_library");  // custom library to load xml and widgets;
		$section_id  =  $this->input->get('id');
	    $section_id  =  $this->widget_model->get_sectionDetails($section_id, $view_mode);
		$parent_section_name = '';
	   if(count($section_id)>0)
	   {
			if($section_id['ParentSectionID']!=''&& $section_id['ParentSectionID']!=0 ){
				 $parent_section     = $this->widget_model->get_sectionDetails($section_id['ParentSectionID'], $view_mode);
				 $data['Parentsection'] = ($parent_section['Sectionname']!='')? $parent_section['Sectionname'] : '';
				 $parent_section_name   = strtolower($parent_section['URLSectionName']);
			}else{
			$data['Parentsection'] = '';
			}
			$data['url_section'] = base_url().$section_id['URLSectionStructure'];
			$sectionname         = strtolower($section_id['URLSectionName']);
			switch ($sectionname) {
				case ($sectionname == "galleries" || $sectionname == "photos" || $parent_section_name=="photogallery" ||  $parent_section_name=="photos"):
					$content_type = 3;
					break;
				case ($sectionname == "videos" || $parent_section_name=="videos"):
					$content_type = 4;
					break;
				case "audios":
					$content_type = 5;
					break;
				/*case "resources":
					$content_type = 6;
					break;*/
				default:
					$content_type = 1;
			}
			$data['viewmode']     = $view_mode; 
			$data['content_type'] = $content_type;
			$data['Section']      = $section_id['Sectionname'];
			if($section_id['Section_id']==220){
				$content_type	=7;
			}
			$data['Content_type']      = $content_type;
			$data['rss_article']  = $this->widget_model->rss_section_articles($section_id['Section_id'], $content_type);
			$this->load->view("admin/rssfeed", $data);
			unset($data);
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
		$section_page_id      = $page_details['menuid'];
		$section_details      = $this->widget_model->get_sectionDetails($section_page_id, $view_mode); 
		$page_param           = ($this->input->get('pm')!='')? $this->input->get('pm'): $page_details['menuid'];
		$is_home_page         = 'n';
		$xml                  = "";
		$xml				  = simplexml_load_string($page_details['published_templatexml']);   // home Xml details
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
		}
		$this->load->view("admin/view_frontend", $data); 
	  }	
	}
	public function sitemap() {
		
		$data['sectionname_list'] 	= $this->widget_model->rss_section_mapping($view_mode = 'LIVE'); 
		$data['xml_type']			= "section_sitemap";
		echo $this->load->view("admin/section_sitemap",$data,true); 
		
	}
	public function section_year_sitemap() {
		extract($_GET);
		
		if(isset($year) && isset($section_id) && isset($content_type) && isset($month)) {
			
			$result = array();
		
		switch($content_type) {
			case 1:
			$tablename = "article";
			break;
			
			case 3: 
			$tablename = "gallery";
			break;
			
			case 4: 
			$tablename = "video";
			break;
			
			case 5: 
			$tablename = "audio";
			break;
		}
			

			if($year == date('Y')) { 
				
				$this->live_db->select("last_updated_on,url");
				$this->live_db->from($tablename);
				$this->live_db->where("section_id",$section_id);
				$this->live_db->where("MONTH(last_updated_on)",$month);
				$this->live_db->where("publish_start_date < NOW()");		
				$this->live_db->order_by("last_updated_on","desc");
				
				$get = $this->live_db->get();
				
				$live_result = $get->result_array();
				
				$archive_result =array();
				
				if($this->archive_db->table_exists($tablename."_".$year)) {
					
					$this->archive_db->select("last_updated_on,url");
					$this->archive_db->from($tablename."_".$year);
					$this->archive_db->where("section_id",$section_id);
					$this->archive_db->where("MONTH(last_updated_on)",$month);
					$this->archive_db->where("publish_start_date < NOW()");		
					$this->archive_db->order_by("last_updated_on","desc");
					$get = $this->archive_db->get();
					
					$archive_result = $get->result_array();
				}
				
				$result = array_merge($live_result,$archive_result);
				
				
				
				
				
				} else {
					
					
					if($this->archive_db->table_exists($tablename."_".$year)) {
					
					$this->archive_db->select("last_updated_on,url");
					$this->archive_db->from($tablename."_".$year);
					$this->archive_db->where("section_id",$section_id);
					$this->archive_db->where("MONTH(last_updated_on)",$month);
					$this->archive_db->where("publish_start_date < NOW()");		
					$this->archive_db->order_by("last_updated_on","desc");
					$get = $this->archive_db->get();
					
					$result = $get->result_array();
				}
				
			}
		
		
		$data['live_articles'] 	= $result; 
		$data['xml_type']			= "section_live_sitemap";
		
		echo $this->load->view("admin/section_sitemap",$data,true); 
		
		} 
			
		
	}
	
	public function new_sitemap() {
		
				$this->live_db->select("title,publish_start_date,tags,last_updated_on,url,article_page_image_path");
				$this->live_db->from("article");
				$this->live_db->where("publish_start_date < NOW()");		
				$this->live_db->order_by("last_updated_on","desc");
				$this->live_db->limit("1000");
				
				$get = $this->live_db->get();
				
				$live_result = $get->result_array();
				
				$data['new_articles'] 		= $live_result; 
				$data['xml_type']			= "new_sitemap";
		
				echo $this->load->view("admin/section_sitemap",$data,true); 
				
				
		
	}
	
	public function editors_pick() {
		$this->live_db->select("content_id,content_type,CustomTitle,CustomSummary,image_path");	
		$this->live_db->from("sectionwidgetarticle");
		$this->live_db->where("widget_type",2);	
		$this->live_db->order_by("DisplayOrder","asc");
		$Result=$this->live_db->get();
		$Result = $Result->result();
		$data['new_articles'] 		= $Result; 
		$data['xml_type']			= "editorial_pick";
		echo $this->load->view("admin/editorpick_sitemap",$data,true); 
	}
	
	public function latest_sitemap(){
		//$SectionIds = array(478,336,337,338,481,334,485,388,387,333,339,401,541,471,502);
		$SectionIds = array(170,171,172,173,174,175,177,178,179,340,341,342,343,362,180,181,182,183,184,185,361,186,187,188,189,190,191,193,194,195,198,212,213);
		$Response ='';
		for($i=0;$i<count($SectionIds);$i++):
			$GetArticle = $this->live_db->query("SELECT content_id,title,summary_html,article_page_content_html,publish_start_date,tags,last_updated_on,url,article_page_image_path FROM article WHERE section_id='".$SectionIds[$i]."' AND publish_start_date< NOW() AND status='P' ORDER BY last_updated_on DESC LIMIT 15");
			if($GetArticle->num_rows()!=0){
				foreach($GetArticle->result_array() as $ArticleDetails):
				
					if($ArticleDetails['article_page_image_path']==''){
						$imagePath = image_url.imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
						$thumbimage = image_url.imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
					}else{
						$imagePath = image_url.imagelibrary_image_path.$ArticleDetails['article_page_image_path'];
						$thumbimage = image_url.imagelibrary_image_path.$ArticleDetails['article_page_image_path'];
						$thumbimage = str_replace('original/','w150X150/',$thumbimage);
					}
					$title         = html_entity_decode($ArticleDetails['title'],null,"UTF-8");
					$search        = array('&', '&#39;');
					$replace       = array('&amp;', "'");
					$title         = strip_tags(str_replace($search, $replace , $title)); 
					$title			= preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
					$publish_date_custom = new DateTime(@$ArticleDetails['publish_start_date']);
					$publish_updated_date_custom = new DateTime(@$ArticleDetails['last_updated_on']);
					$search1        = array('&', '&#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />');
					$replace1       = array('&amp;', "'",' ',' ',' ','','','');
					$content         	= str_replace($search1, $replace1 , $ArticleDetails['article_page_content_html']); 
					$content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content);
					$EncodedContent 	='<![CDATA[<!doctype html><html><head><link rel="canonical" href="'.BASEURL. html_entity_decode($ArticleDetails['url'],null,"UTF-8").'"/><meta charset="utf-8"/></head><body><article><header><figure><img src="'.$imagePath.'"/></figure><h1>'.$title.'</h1><time class="op-published" datetime="'.$publish_date_custom->format('Y-m-d\TH:i:s+05:30').'">'.$publish_date_custom->format('Y-m-d\TH:i:s+05:30').'</time><time class="op-modified" datetime="'.$publish_updated_date_custom->format('Y-m-d\TH:i:s+05:30').'">'.$publish_updated_date_custom->format('Y-m-d\TH:i:s+05:30').'</time><address><a href="'.BASEURL. html_entity_decode($ArticleDetails['url'],null,"UTF-8").'">newindianexpress.com</a></address></header>'.$content;
					$EncodedContent 	.='<footer><small>Copyright - '.date("Y").' newindianexpress.com. All rights reserved.</small></footer></article></body></html>]]>';
					$Description         	= strip_tags(str_replace($search1, $replace1 , $ArticleDetails['summary_html']));
					
					$Response .='<item>';
					$Response .='<articleid>'.$ArticleDetails['content_id'].'</articleid>';
					$Response .='<title>'.$title.'</title>';
					$Response .='<link>'.BASEURL. html_entity_decode($ArticleDetails['url'],null,"UTF-8").'</link>';
					$Response .='<content:encoded>'.$EncodedContent.'</content:encoded>';
					$Response .='<guid isPermaLink="false">'.BASEURL. html_entity_decode($ArticleDetails['url'],null,"UTF-8").'</guid>';
					$Response .='<description>'.$Description.'</description>';
					$Response .='<thumbimage>'.$thumbimage.'</thumbimage>';
					$Response .='<fullimage>'.$imagePath.'</fullimage>';
					$Response .='<pubDate>'.$publish_date_custom->format('Y-m-d\TH:i:s+05:30').'</pubDate>';
					$Response .='<modDate>'.$publish_updated_date_custom->format('Y-m-d\TH:i:s+05:30').'</modDate>';
					$Response .='</item>';
					$EncodedContent = '';
				endforeach;	
			}
		endfor;
		echo $this->load->view("admin/latestnews_sitemap",['data'=>$Response],true); 
	}
	
	public function latest_feed(){

		$Response ='';
		if($this->input->get('category')!='' && is_numeric($this->input->get('category'))){
			$category = (int) $this->input->get('category');
			$SectionIds = array($category);
			$limit = 40;
			$sectionDetails  =  $this->widget_model->get_sectionDetails($category, "live");
		}else{
			$SectionIds = array(478,336,337,338,481,334,485,388,387,333,339,401,541,471,502);
			$limit = 1;
		
		}
		
		for($i=0;$i<count($SectionIds);$i++):
			if($this->input->get('category')!='' && is_numeric($this->input->get('category'))){
				$CACHEID  = "LATESTFEED3-".$SectionIds[$i];
			}else{
				$CACHEID  = "LATESTFEED2-".$SectionIds[$i];
			}
			 $LatestQuery = "SELECT article.content_id, article.title,article.summary_html,article.article_page_content_html, article.url, article.article_page_image_path, article.publish_start_date,article.tags,article.last_updated_on FROM article_section_mapping as mapping LEFT JOIN article as article ON article.content_id = mapping.content_id WHERE ( mapping.section_id IN ( SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = '".$SectionIds[$i]."' OR `Section_id` = '".$SectionIds[$i]."' ) ) AND article.status='P' AND article.publish_start_date <=NOW() GROUP BY article.content_id  ORDER BY article.publish_start_date DESC LIMIT ".$limit."";
			if(!$this->memcached_library->get($CACHEID) && $this->memcached_library->get($CACHEID) == '') {
				$GetArticle = $this->live_db->query($LatestQuery)->result_array();
				$this->memcached_library->add($CACHEID,$GetArticle);
			}else{
				$GetArticle = $this->memcached_library->get($CACHEID);
			}


		//$SectionIds = array(170,171,172,173,174,175,177,178,179,340,341,342,343,362,180,181,182,183,184,185);
		//$Response ='';
		//for($i=0;$i<count($SectionIds);$i++):
			//$GetArticle = $this->live_db->query("SELECT content_id,title,summary_html,article_page_content_html,publish_start_date,tags,last_updated_on,url,article_page_image_path FROM article WHERE section_id='".$SectionIds[$i]."' AND publish_start_date <=NOW() AND status='P' ORDER BY last_updated_on DESC LIMIT 1");


			//if($GetArticle->num_rows()!=0){
				foreach($GetArticle as $ArticleDetails):
					if($ArticleDetails['article_page_image_path']==''){
					$imagePath = image_url.imagelibrary_image_path.'logo/dinamani_logo_600X300.jpg';
					$thumbimage = image_url.imagelibrary_image_path.'logo/dinamani_logo_150X150.jpg';
				}else{
					$imagePath = image_url.imagelibrary_image_path.$ArticleDetails['article_page_image_path'];
					$thumbimage = image_url.imagelibrary_image_path.$ArticleDetails['article_page_image_path'];
					$thumbimage = str_replace('original/','w150X150/',$thumbimage);
				}
				$title         = html_entity_decode($ArticleDetails['title'],null,"UTF-8");
				$search        = array('&', '&#39;');
				$replace       = array('&amp;', "'");
				$title         = strip_tags(str_replace($search, $replace , $title)); 
				$title			= preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
				$publish_date_custom = new DateTime(@$ArticleDetails['publish_start_date']);
				$publish_updated_date_custom = new DateTime(@$ArticleDetails['last_updated_on']);
				$search1        = array('&#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />','&zwnj;');
				$replace1       = array( "'",' ',' ',' ','','','','');
				$content         	= str_replace($search1, $replace1 , $ArticleDetails['article_page_content_html']); 
				$content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content);
				$content = str_replace('<p> </p>', '', $content);

				
					/*if($ArticleDetails['article_page_image_path']==''){
						$imagePath = image_url.imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
						$thumbimage = image_url.imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
					}else{
						$imagePath = image_url.imagelibrary_image_path.$ArticleDetails['article_page_image_path'];
						$thumbimage = image_url.imagelibrary_image_path.$ArticleDetails['article_page_image_path'];
						$thumbimage = str_replace('original/','w150X150/',$thumbimage);
					}
					$title         = html_entity_decode($ArticleDetails['title'],null,"UTF-8");
					$search        = array('&', '&#39;');
					$replace       = array('&amp;', "'");
					$title         = strip_tags(str_replace($search, $replace , $title)); 
					$title			= preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
					$publish_date_custom = new DateTime(@$ArticleDetails['publish_start_date']);
					$publish_updated_date_custom = new DateTime(@$ArticleDetails['last_updated_on']);
					$search1        = array('&', '&#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />');
					$replace1       = array('&amp;', "'",' ',' ',' ','','','');
					$content         	= str_replace($search1, $replace1 , $ArticleDetails['article_page_content_html']); 
					$content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content); */
					
					$EncodedContent 	='<![CDATA['.$content;
				$EncodedContent 	.=']]>';
				$Description         	= strip_tags(str_replace($search1, $replace1 , $ArticleDetails['summary_html']));
				$Description = str_replace(['&lsquo;','&rsquo;'],['',''],$Description);

				
				
				$Description 	='<![CDATA['.$Description;
				$Description 	.=']]>';



				$Response .='<item>';
				$Response .='<articleid>'.$ArticleDetails['content_id'].'</articleid>';
				$Response .='<title>'.$title.'</title>';
				$Response .='<link>'.BASEURL. html_entity_decode($ArticleDetails['url'],null,"UTF-8").'</link>';
				$Response .='<content:encoded>'.$EncodedContent.'</content:encoded>';
				$Response .='<guid isPermaLink="false">'.BASEURL. html_entity_decode($ArticleDetails['url'],null,"UTF-8").'</guid>';
				//$Response .='<description>'.$Description.'</description>';
				$Response .='<description>'.$Description.'</description>';
				$Response .='<thumbimage>'.$thumbimage.'</thumbimage>';
				$Response .='<fullimage>'.$imagePath.'</fullimage>';
				$Response .='<pubDate>'.$publish_date_custom->format('Y-m-d\TH:i:s+05:30').'</pubDate>';
				$Response .='<modDate>'.$publish_updated_date_custom->format('Y-m-d\TH:i:s+05:30').'</modDate>';
				$Response .='</item>';
				$EncodedContent = '';
					
				endforeach;	
			
		endfor;
		echo $this->load->view("admin/latestnews_feed",['data'=>$Response],true); 
	}
	public function uc_news(){
		header('Content-type: application/xml');
		$type = strtolower($this->uri->segment(1));
		switch ($type){
			case ($type=='gallery'):
				$query = "SELECT content_id , section_name , publish_start_date , last_updated_on , title , url , summary_html , first_image_path as article_page_image_path , first_image_title as img_title , first_image_alt as img_alt  , summary_html as story FROM gallery WHERE status='P' AND publish_start_date < NOW() ORDER BY publish_start_date DESC LIMIT 700";
			break;
			case ($type=='video'):
				$query = "SELECT content_id , section_name , publish_start_date , last_updated_on , title , url , summary_html , video_image_path as article_page_image_path , video_image_title as img_title , video_image_alt as img_alt , video_script as story FROM video WHERE status='P' AND publish_start_date < NOW() ORDER BY publish_start_date DESC LIMIT 700";
			break;
			default:
				$query = "SELECT content_id , section_name , publish_start_date , last_updated_on , title , url , summary_html , article_page_image_path , article_page_image_title as img_title , article_page_image_alt as img_alt , article_page_content_html as story FROM article WHERE status='P' AND publish_start_date < NOW() ORDER BY publish_start_date DESC LIMIT 700";
			break;
		}
		if(!$this->memcached_library->get($query) && $this->memcached_library->get($query) == ''){
			$data['content'] = $this->live_db->query($query)->result_array();
			$this->memcached_library->add($query,$data['content']);
		}else{
			$data['content']  = $this->memcached_library->get($query);
		}
		$data['baseUrl'] = base_url();
		$data['type'] = $type;
		echo $this->load->view('admin/ucxml_view',$data ,true);
	}
	public function magzterfeed(){
		header ("Content-Type:application/rss+xml; charset=UTF-8");
		$sectionId = $this->uri->segment(2);
		if($sectionId=='article' || $sectionId=='gallery' || $sectionId=='video'){
			$sectionDetails  =  $this->widget_model->get_sectionDetails(1, "live");
		}else{
			$sectionDetails  =  $this->widget_model->get_sectionDetails($sectionId, "live");
		}
		$parentSectionName ='';
		if(count($sectionDetails) > 0){
			if($sectionDetails['ParentSectionID']!=''&& $sectionDetails['ParentSectionID']!=0 ){
				$parentSectionDetails = $this->widget_model->get_sectionDetails($sectionDetails['ParentSectionID'], "live");
				$parentSectionName = strtolower($parentSectionDetails['URLSectionName']);
			}
			$sectionName = strtolower($sectionDetails['URLSectionName']);
			$galquery = "b.section_id IN (SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = ".$sectionId." OR Section_id = ".$sectionId.") AND";
			switch ($sectionName){
				case ($sectionName == "galleries" || $sectionName == "photos" || $parentSectionName=="galleries" ||  $parentSectionName=="photos" || $sectionId=="gallery"):
					$contentType = 3;
					if($sectionId=='gallery'){
						$galquery ="";
					}
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.first_image_path, a.first_image_title, a.first_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM gallery AS a LEFT JOIN gallery_section_mapping AS b ON a.content_id=b.content_id WHERE ".$galquery." a.status='P' AND a.publish_start_date < NOW() GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 100";
					break;
				case ($sectionName == "videos" || $parentSectionName=="videos" || $sectionId=="video"):
					$contentType = 4;
					if($sectionId=='video'){
						$galquery ="";
					}
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.video_script, a.video_image_path, a.video_image_title, a.video_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM video AS a LEFT JOIN video_section_mapping AS b ON a.content_id=b.content_id WHERE ".$galquery." a.status='P' AND a.publish_start_date < NOW() GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 100"; 
					break;
				default:
					$contentType = 1;
					if($sectionId=='article'){
						$galquery ="";
					}
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.article_page_content_html, a.article_page_image_path, a.article_page_image_title, a.article_page_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM article AS a LEFT JOIN article_section_mapping AS b ON a.content_id=b.content_id WHERE ".$galquery." a.status='P' AND a.publish_start_date < NOW() GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 100";
			}
			if(!$this->memcached_library->get($query) && $this->memcached_library->get($query) == ''){
				$data['content'] = $this->live_db->query($query)->result_array();
				$this->memcached_library->add($query,$data['content']);
			}else{
				$data['content']  = $this->memcached_library->get($query);
			}
			$data['sectionDetails'] = $sectionDetails;
			$data['contentType'] = $contentType;
			$data['baseUrl'] = base_url();
			echo $this->load->view('admin/magzter_view',$data ,true);
			
		}else{
			show_404();
		}
	}
	public function sitemap_list(){
		$this->load->view("admin/sitemap_list");
	}
	public function sitemap_main(){
		$query = "SELECT Section_id , ParentSectionID , URLSectionStructure , Sectionname FROM sectionmaster WHERE Status=1 AND section_allowed_for_hosting=1 AND RSSFeedAllowed=1 AND ParentSectionID IS NULL ORDER BY Section_id ASC";
		if(!$this->memcached_library->get($query) && $this->memcached_library->get($query) == ''){
			$section = $this->live_db->query($query)->result();
			$this->memcached_library->add($query,$section);
		}else{
			$section = $this->memcached_library->get($query);
		}
		$date = date('Y-m-d');
		$result ='';
		foreach($section as $sec){
			$result .='<url>';
			$result .='<loc>'.BASEURL.$sec->URLSectionStructure.'</loc>';
			$result .='<lastmod>'.$date.'</lastmod>';
			$result .='<changefreq>daily</changefreq>';
			$result .='<priority>.5</priority>';
			$result .='</url>';
			$subsection = $this->live_db->query("SELECT Section_id , ParentSectionID , URLSectionStructure , Sectionname FROM sectionmaster WHERE Status=1 AND section_allowed_for_hosting=1 AND RSSFeedAllowed=1 AND ParentSectionID='".$sec->Section_id."' ORDER BY Section_id ASC")->result();
			foreach($subsection as $sec1){
				$result .='<url>';
				$result .='<loc>'.BASEURL.$sec1->URLSectionStructure.'</loc>';
				$result .='<lastmod>'.$date.'</lastmod>';
				$result .='<changefreq>daily</changefreq>';
				$result .='<priority>.5</priority>';
				$result .='</url>';
				$subsection1 = $this->live_db->query("SELECT Section_id , ParentSectionID , URLSectionStructure , Sectionname FROM sectionmaster WHERE Status=1 AND section_allowed_for_hosting=1 AND RSSFeedAllowed=1 AND ParentSectionID='".$sec1->Section_id."' ORDER BY Section_id ASC")->result();
				foreach($subsection1 as $sec2){
					$result .='<url>';
					$result .='<loc>'.BASEURL.$sec2->URLSectionStructure.'</loc>';
					$result .='<lastmod>'.$date.'</lastmod>';
					$result .='<changefreq>daily</changefreq>';
					$result .='<priority>.5</priority>';
					$result .='</url>';
					$subsection2 = $this->live_db->query("SELECT Section_id , ParentSectionID , URLSectionStructure , Sectionname FROM sectionmaster WHERE Status=1 AND section_allowed_for_hosting=1 AND RSSFeedAllowed=1 AND ParentSectionID='".$sec2->Section_id."' ORDER BY Section_id ASC")->result();
					foreach($subsection2 as $sec3){
						$result .='<url>';
						$result .='<loc>'.BASEURL.$sec3->URLSectionStructure.'</loc>';
						$result .='<lastmod>'.$date.'</lastmod>';
						$result .='<changefreq>daily</changefreq>';
						$result .='<priority>.5</priority>';
						$result .='</url>';
					}
				}
						
			}
		}
		$this->load->view("admin/sitemap_main" , ['data' => $result]);
	}
	public function homefeed(){
		$homeId = 1;
		$list = [];
		$query = "SELECT published_templatexml FROM page_master WHERE menuid='".$homeId."' AND pagetype=1";
		if(!$this->memcached_library->get($query) && $this->memcached_library->get($query) == ''){
			$homeXml = $this->live_db->query($query)->row_array();
			$this->memcached_library->add($query,$homeXml);
		}else{
			$homeXml = $this->memcached_library->get($query);
		}
		if(count($homeXml) > 0 && strlen($homeXml['published_templatexml'])!= 0){
			$xml = simplexml_load_string($homeXml['published_templatexml']);
			if(isset($xml->tplcontainer[1])){
				$Containers = $xml->tplcontainer[1];
				$widgetContainers = $Containers->widgetcontainer;
				for($i=0;$i<count($widgetContainers);$i++){
					$widgets = $widgetContainers[$i]->widget;
					for($j=0;$j<count($widgets);$j++){
						$renderType = (int) $widgets[$j]['data-renderingtype'];
						if($renderType==1){
							$contentType = (int) $widgets[$j]['data-contenttype'];
							$widgetStyle = (int) $widgets[$j]['data-widgetstyle'];
							$widgetInstanceId = (int) $widgets[$j]['data-widgetinstanceid'];
							$maxArticles = (int) $widgets[$j]['cdata-customMaxArticles'];
							$maxArticles = ($maxArticles!='' && $maxArticles!=0) ? $maxArticles : 1;
							$renderMode = (string) $widgets[$j]['cdata-renderingMode'];
							$widgetStatus = (int) $widgets[$j]['cdata-widgetStatus'];
							if($widgetStatus==1){
								switch($widgetStyle){
									case 1:
										if($renderMode=='manual'){
											$articleListQuery = "SELECT content_id , CustomTitle , CustomSummary , content_type_id , custom_image_path , custom_image_title , custom_image_alt FROM widgetinstancecontent_live WHERE WidgetInstance_id='".$widgetInstanceId."' AND Status=1 AND widgetInstanceRelated_ID=0 ORDER BY DisplayOrder ASC LIMIT ".$maxArticles."";
											if(!$this->memcached_library->get($articleListQuery) && $this->memcached_library->get($articleListQuery) == ''){
												$articleList = $this->live_db->query($articleListQuery)->result_array();
												$this->memcached_library->add($articleListQuery,$articleList);
											}else{
												$articleList = $this->memcached_library->get($articleListQuery);
											}
											foreach($articleList as $article){
												switch($article['content_type_id']){
													case 3:
														$tblName = 'gallery';
														$field = " , ecenic_id as content , first_image_path as image , first_image_title as imagetitle , first_image_alt as imagealt";
													break;
													case 4:
														$tblName = 'video';
														$field = " , video_script as content , video_image_path as image , video_image_title as imagetitle , video_image_alt as imagealt";
													break;
													default:
														$tblName = 'article';
														$field = " , article_page_content_html as content , article_page_image_path as image , article_page_image_title as imagetitle , article_page_image_alt as imagealt";
													break;													
												
												}
												$articleDetailQuery = "SELECT content_id , section_id , url , section_name , title , author_name , publish_start_date , summary_html , agency_name , tags ".$field." FROM ".$tblName." WHERE status='P' AND content_id='".$article['content_id']."' AND publish_start_date < NOW() LIMIT 1";
												if(!$this->memcached_library->get($articleDetailQuery) && $this->memcached_library->get($articleDetailQuery) == ''){
													$articleDetail = $this->live_db->query($articleDetailQuery)->row_array();
												$this->memcached_library->add($articleDetailQuery,$articleDetail);
												}else{
													$articleDetail = $this->memcached_library->get($articleDetailQuery);
												}
												$articleDetail['content_type_id'] = $article['content_type_id'];
												array_push($list , $articleDetail);
											}
										}
									break;
								}
							}
						}
					}
				}
			}
			
			
		}
		$data['list'] = $list;
		$this->load->view("admin/homefeed", $data);
	}
}
?>