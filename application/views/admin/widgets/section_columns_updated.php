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
if($max_article < 10 ){ $max_article = 10;}
function pager($parameter=[]){
	$config=['base_url'=>$parameter['base_url'],'total_rows'=>$parameter['total_rows'],'per_page'=>$parameter['per_page'],'num_links'=>5,'page_query_string'=>TRUE,'reuse_query_string'=>FALSE,'suffix'=>$parameter['suffix'],'cur_tag_open'=>'<a class="active">','cur_tag_close'=>'</a>','use_page_numbers'=>TRUE,'first_url'=>$parameter['first_url'],'first_link'=>TRUE,'last_link'=>FALSE];
	return $config;
}
if($render_mode == 'manual'):
	$Article = $this->live_db->query("SELECT content_id,CustomTitle,CustomSummary FROM widgetinstancecontent_live WHERE WidgetInstance_id='".$widget_instance_id."' ORDER BY DisplayOrder ASC LIMIT ".$max_article."")->result();
endif;

if($render_mode == 'auto'):

	$check_archive=@$_COOKIE['archivelist_'.$SectionID];
	if($check_archive==''){
		$hasarchive['archive_result']=[];
		$range=range(2009,date('Y')-1);
		foreach($range as $ranger):
			$table='article_'.$ranger;
			if($this->archive_db->table_exists($table)){
				$archive_pattern="SELECT content_id FROM ".$table." WHERE section_id='".$SectionID."'";
				$temp_query=$this->archive_db->query($archive_pattern);
				$data['table']=$table;
				$data['count']=$temp_query->num_rows();
				if($temp_query->num_rows() !=0):
					$hasarchive['archive_result'][]=$data;
				endif;
			}
		endforeach;
		setcookie('archivelist_'.$SectionID,json_encode($hasarchive),time() + (60 * 15));
		$archivelist=$hasarchive;
	}else{
		$archivelist=json_decode($check_archive,true);
	}
	
	if($this->input->get('archive')=='true' && $this->input->get('year')!=''){
		$year = $this->input->get('year');
		$currentyear = date('Y');
		if($year > $currentyear){ $year = 2009;	}
		if($year < 2009){ $year = 2009;	}
		$list = array_reverse($archivelist['archive_result']);
		for($a=0;$a<count($list);$a++):
		if($list[$a]['table']=='article_'.$year){
			$TotalRows = $list[$a]['count'];
			break;
		}
		endfor;
		$firsturl = $widget_url.'?archive=true&year='.$year;
		$suffix = '&archive=true&year='.$year;
		$this->pagination->initialize(pager(['total_rows'=>$TotalRows,'per_page'=>$max_article,'base_url'=>$widget_url,'suffix'=>$suffix,'first_url'=>$firsturl]));
		$Limit=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
		$pagination=$this->pagination->create_links();
		$tblname = "article_".$year;
		$Article = $this->archive_db->query("SELECT content_id,section_name,title,url,summary_html,author_name,author_image_path,publish_start_date FROM ".$tblname." WHERE section_id='".$SectionID."' AND status='P' ORDER BY publish_start_date DESC LIMIT ".$Limit.",".$max_article."")->result();
		
		
	}else{
		if(@$_COOKIE['op_auto_count_'.$SectionID]!=''){
		$TotalRows =@$_COOKIE['op_auto_count_'.$SectionID];
		}else{
			$TotalRows = $this->live_db->query("SELECT content_id FROM article WHERE status='P' AND section_id='".$SectionID."'")->num_rows();
			setcookie('op_auto_count_'.$SectionID, $TotalRows,time() + (60 * 15));	
		}
		$this->pagination->initialize(pager(['total_rows'=>$TotalRows,'per_page'=>$max_article,'base_url'=>$widget_url,'suffix'=>'','first_url'=>$widget_url]));
		$Limit=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
		$pagination=$this->pagination->create_links();
		$Article = $this->live_db->query("SELECT content_id,section_name,title,url,summary_html,author_name,author_image_path,publish_start_date FROM article WHERE section_id='".$SectionID."' AND status='P' ORDER BY publish_start_date DESC LIMIT ".$Limit.",".$max_article."")->result();
	}
endif;
$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
$show_simple_tab='';
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
	$show_simple_tab .= '<div class="sub_column sub_column_main">';
	$show_simple_tab .='<a  href="'.$live_article_url.'"  ><img src="'.$dummy_image.'"  data-src="'.$AuthorImage.'"></a>';
	$show_simple_tab .= '<a href="'.$AuthorUrl.'"  ><h4>'.$AuthorName.'</h4></a>';
	$show_simple_tab .= '<h5>'.$display_title.'</h5>';
	$show_simple_tab .='<p class="column_det summary">'.$summary.'</p>';
	$show_simple_tab .=	'<p class="post_time">'.$post_time.' </p>'; 
	$show_simple_tab .= '</div>';
endforeach;

echo $show_simple_tab;
if($render_mode == 'auto'){
	
	echo '<div class="pagina">'.$pagination.'</div>';
	echo '<div class="pagina">';
	$lactive = (!isset($_GET['year']) && $_GET['year']=='')? 'active' : '';
	echo '<a href="'.$widget_url.'" class="'.$lactive.'">latest </a>'; 
	$list = array_reverse($archivelist['archive_result']);
	for($j=0;$j<count($list);$j++):
		$link = explode('_',$list[$j]['table']);
		$url = $widget_url."?archive=true&year=".$link[1];
		$active = (isset($_GET['year']) && $_GET['year']==$link[1])? 'active' : '';
		echo '<a href="'.$url.'" class="'.$active.'">'.$link[1].'</a>'; 
	endfor;
	echo '</div>';
}


?>
