<?php 
$CI = &get_instance(); 
$this->live_db = $this->load->database('live_db', TRUE);
$this->archive_db = $CI->load->database('archive_db', TRUE);
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color 		= $content['widget_bg_color'];
$widget_custom_title 	= $content['widget_title'];
$widget_instance_id 	= $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 		= "";
$is_home 				= $content['is_home_page'];
$is_summary_required 	= $content['widget_values']['cdata-showSummary'];
$widget_section_url 	= $content['widget_section_url'];
$view_mode            	= $content['mode'];
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];
$page_param	    		= $content['page_param'];
$load_more_url 			= $domain_name.'topic/?sid='.$content['page_param'].'&cid=1';
$widget_auto_count = $this->widget_model->select_setting($view_mode);
$columnist_count   = $widget_auto_count['subsection_otherstories_count_perpage'];
$max_article_count = $widget_auto_count['subsection_otherstories_autoCount'];
$SectionID = $content['sectionID'];
$widget_url=base_url().$this->uri->uri_string();
$TotalRows = 0;
$archive =  '';
if($max_article =='' ||  $max_article ==0){ $max_article = 10;}
if($render_mode == 'manual'):
	$Article = $this->live_db->query("SELECT content_id,CustomTitle,CustomSummary FROM widgetinstancecontent_live WHERE WidgetInstance_id='".$widget_instance_id."' ORDER BY DisplayOrder ASC LIMIT ".$max_article."")->result();
endif;

if($render_mode == 'auto'):
	$Limit=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
	//$Article = $this->live_db->query("SELECT content_id,section_name,title,url,summary_html,author_name,column_name,author_image_path,publish_start_date FROM article WHERE section_id='".$SectionID."' AND status='P' ORDER BY publish_start_date DESC LIMIT ".$Limit.",".$max_article."")->result();
	$query = "SELECT article.content_id, article.section_name, article.title, article.url, article.summary_html, article.author_name, article.column_name, article.author_image_path , article.publish_start_date FROM article as article JOIN article_section_mapping as mapping ON article.content_id = mapping.content_id WHERE article.status='P' AND mapping.section_id='".$SectionID."' ORDER BY article.publish_start_date DESC LIMIT ".$Limit." , ".$max_article."";
	$Article = $this->live_db->query($query)->result();
	
endif;
$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
$show_simple_tab='';
if(count($Article) > 0){
	if($render_mode=='auto'){
		$AuthorImage = $Article[0]->author_image_path;
		$AuthorName = $Article[0]->author_name;
		$ColumnName = $Article[0]->column_name;
		$content_url = $Article[0]->url;
	}else{
		$ManArticle = $this->live_db->query("SELECT section_name,title,url,summary_html,author_name,author_image_path,publish_start_date FROM article WHERE status='P' AND content_id='".$Article[0]->content_id."'")->result();
		$AuthorImage = $ManArticle[0]->author_image_path;
		$AuthorName = $ManArticle[0]->author_name;
		$ColumnName = $ManArticle[0]->column_name;
		$content_url = $ManArticle[0]->url;
	}
	
		$show_simple_tab .='<div class="author-column-wrapper">';
			if($AuthorImage!=''){
				$AuthorImage = image_url. $AuthorImage;
				$show_simple_tab .='<img src="'.$dummy_image.'"  data-src="'.$AuthorImage.'">';
			}
			if($AuthorName!=''){
				$section = $FetchedArticle->section_name;
				$author_url  = join("-", explode(" ", $AuthorName)); 
				$url_array = explode('/', $content_url);
				$get_seperation_count = count($url_array)-4;
				$section_url = ($get_seperation_count==1)? $author_url : (($get_seperation_count==2)? $url_array[0]."/".$url_array[1] : $url_array[0]."/".$url_array[1]."/".$url_array[2]);
				$author_pos = stripos($AuthorName, $section);
				if ($author_pos === false) {
					$AuthorUrl = base_url().'Author/'.$author_url;
				}else{
					$AuthorUrl = base_url().$section_url;
				}
				$show_simple_tab .= '<a href="'.$AuthorUrl.'"  ><h5>'.$ColumnName.'</h5> <h4>'.$AuthorName.'</h4></a>';
			}
			
		$show_simple_tab .='</div>';
		$show_simple_tab .='<div class="author-column-inner-wrapper">';
		$i=1;
	foreach($Article as $FetchedArticle):
		if($render_mode=='manual'){
			$ManArticle = $this->live_db->query("SELECT section_name,title,url,summary_html,author_name,author_image_path,publish_start_date FROM article WHERE status='P' AND content_id='".$FetchedArticle->content_id."'")->result();
			$ManArticle=$ManArticle[0];
		}
		$AuthorImage = image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
		$AuthorUrl = '';
		if($render_mode=='auto'){
			if($FetchedArticle->author_image_path!=''){ $AuthorImage = image_url. $FetchedArticle->author_image_path; }
		}else{
			
			if($ManArticle->author_image_path!=''){ $AuthorImage = image_url. $ManArticle->author_image_path; }
		}
		if($render_mode=='auto'){
			$AuthorName = $FetchedArticle->author_name;
		}else{
			$AuthorName = $ManArticle->author_name;		
		}
		
		if($AuthorName!=''){
			if($render_mode=='auto'){
				$section = $FetchedArticle->section_name;
			}else{
				$section = $ManArticle->section_name;
			}
			$author_url  = join("-", explode(" ", $AuthorName)); 
			if($render_mode=='auto'){
				$content_url = $FetchedArticle->url;
			}else{
				$content_url = $ManArticle->url;
			}
			$url_array = explode('/', $content_url);
			$get_seperation_count = count($url_array)-4;
			$section_url = ($get_seperation_count==1)? $author_url : (($get_seperation_count==2)? $url_array[0]."/".$url_array[1] : $url_array[0]."/".$url_array[1]."/".$url_array[2]);
			$author_pos = stripos($AuthorName, $section);
			if ($author_pos === false) {
				$AuthorUrl = base_url().'Author/'.$author_url;
			}else{
				$AuthorUrl = base_url().$section_url;
			}
			
		}
		if($render_mode=='auto'){
			$live_article_url = base_url().$FetchedArticle->url;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$FetchedArticle->title);
			$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$FetchedArticle->summary_html);
			$post_time = $this->widget_model->time2string($FetchedArticle->publish_start_date);
		}else{
			$live_article_url = base_url().$ManArticle->url;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$FetchedArticle->CustomTitle);
			if($FetchedArticle->CustomSummary==''){
				$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$ManArticle->summary_html);
			}else{
				$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$FetchedArticle->CustomSummary);
			}
			$post_time = $this->widget_model->time2string($ManArticle->publish_start_date);
			
		}
		$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
		if($i==1){
			$show_simple_tab .= '<div class="row">';
		}
		$show_simple_tab .= '<div class="col-md-6 col-sm-6 col-lg-6">';
		$show_simple_tab .= '<h5>'.$display_title.'</h5>';
		$show_simple_tab .=	'<p class="post_time">'.$post_time.' </p>'; 
		$show_simple_tab .='</div>';
		if($i==2){
			$show_simple_tab .='</div>';
			$i = 1;
		}else{
			$i++;
		}
	endforeach;
	if($i==2){
		$show_simple_tab .='</div>';
	}
	$show_simple_tab .='</div>';
	echo $show_simple_tab;
}


?>
