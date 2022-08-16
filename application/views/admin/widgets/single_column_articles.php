<?php 
$widget_instance_id 	= $content['widget_values']['data-widgetinstanceid'];
$page_section_id 		= $content['page_param'];
$is_home 				= $content['is_home_page'];
$is_summary_required 	= $content['widget_values']['cdata-showSummary'];
$widget_section_url 	= $content['widget_section_url'];
$view_mode            	= $content['mode'];
//$max_article 			= ($content['show_max_article'] > 0) ? $content['show_max_article'] : '10';
$widget_auto_count = $this->widget_model->select_setting($view_mode);
$max_article = '';
//$author_details = get_author_by_name($this->uri->segment(3));
//$article_count       	= 15;
$article_count       = $widget_auto_count['subsection_otherstories_count_perpage'];
$domain_name 			=  base_url();

$section_details = $this->widget_model->get_sectionDetails($page_section_id, $view_mode);
$section_id = $section_details['Section_id'];
$author_id = '';

$GetArchiveDetails=$this->widget_model->GetAuthorArchiveDetails($section_details['Sectionname'],$content['page_param'],1);

$content_type = 1;  // auto article content type
/*if($section_details['AuthorID'] != '')
{
	//$widget_instance_contents_pagination = $this->widget_model->get_all_available_articles_auto($max_article, $page_section_id  , $content_type ,  $view_mode); 
	
	$widget_instance_contents_pagination = $this->widget_model->get_all_available_articles_auto_totalcount($max_article, $page_section_id , $content_type ,  $view_mode);
}
else{
	$author_name_in_url = ( $this->uri->segment(2) != '') ? $this->uri->segment(2) : ''; 
	if($author_name_in_url != ''){
	   $widget_instance_contents_pagination = $this->widget_model->get_author_content_auto($max_article, $author_name_in_url , '' , $content_type ,  $view_mode); 
	}
	else{
	   $widget_instance_contents_pagination = $this->widget_model->get_author_content_auto($max_article, '' , '' , $content_type ,  $view_mode); 
	}	
}*/

$widget_instance_contents_pagination = $this->widget_model->get_all_available_articles_auto_totalcount($max_article, $page_section_id , $content_type ,  $view_mode); // to get the total count of articles

// Pagination start								 			

if(isset($_GET['type']) && $_GET['type']=='archive'){
	$this->load->library('pagination');
	$TotalCount=$this->widget_model->GetAuthorArchiveDetails($section_details['Sectionname'],$content['page_param'],2);
	$config['total_rows'] = $TotalCount;
	$config['base_url']=$domain_name.'opinions/columns/'.$this->uri->segment(3);
	$config['per_page'] = 20; 
	$config['custom_num_links'] = 5;
	$config['page_query_string'] = TRUE;
	$config['enable_query_strings']=TRUE;
	$config['use_page_numbers'] = TRUE;
	$config['suffix']='&type=archive';
	$config['cur_tag_open'] = "<a href='javascript:void(0);' class='active'>";
	$config['cur_tag_close'] = "</a>";
	$this->pagination->initialize($config);
	$PaginationLink = $this->pagination->create_links();
	$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : 0;
	$widget_contents=$this->widget_model->GetContentBasedOnArchive($article_limit,$section_details['Sectionname'],$config['per_page']);
}else{
$this->load->library('pagination');
$TotalCount= count($widget_instance_contents_pagination);
$config['total_rows'] = $TotalCount; 
$config['per_page'] = $article_count; 
$config['custom_num_links'] = 5;
$config['page_query_string'] = TRUE;
$config['enable_query_strings']=TRUE;
$config['use_page_numbers'] = TRUE;
$config['uri_segment']=$_GET['per_page'];
$config['cur_tag_open'] = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close'] = "</a>";
$page_num = $config['use_page_numbers'];
$article_limit = ($this->input->get('per_page') != '')?$this->input->get('per_page'):0;

$start = $article_limit; 
$page_number = $this->input->get('per_page')/$config['per_page'] + 1 ;
$limit = $article_count;
$config['use_page_numbers'] = TRUE;
$this->pagination->initialize($config); 
$PaginationLink = $this->pagination->custom_create_links();
}
// pagination end	

$load_more_url = $domain_name.'topic/?sid='.$content['page_param'].'&cid=1';

/*if($section_details['AuthorID'] != '')
{
$widget_instance_contents = $this->widget_model->get_all_available_articles_auto_page($max_article, $page_section_id  , $content_type ,  $view_mode, $start, $limit, $page_number,$TotalCount);  
$author_id = $section_details['AuthorID'];
}
else{
	$author_name_in_url = ( $this->uri->segment(2) != '') ? $this->uri->segment(2) : '';
	if($author_name_in_url != ''){ 
		$author_id_details = $this->widget_model->get_author_by_name($author_name_in_url);
		$author_id = $author_id_details[0]['Author_id'];
		$widget_instance_contents = $this->widget_model->get_author_content_auto_page($max_article, $author_name_in_url , $content_type ,  $view_mode,$start,$limit,'' ); 
		
	}  
	else{
		$widget_instance_contents = $this->widget_model->get_author_content_auto_page($max_article, '' , $content_type ,  $view_mode,$start,$limit,'' ); 
	}
}*/
if(!isset($_GET['type']) && $_GET['type']!='archive'):
$widget_instance_contents = $this->widget_model->get_all_available_articles_auto_page($max_article, $page_section_id  , $content_type ,  $view_mode, $start, $limit, $page_number,$TotalCount);  
// get the content id of articles for every page
$author_id = $section_details['AuthorID'];

$author_image = "";
$imagealt = "";
$imagetitle  = "";
endif;
//$topicname = $this->widget_model->gettopic_name();

?>

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="main_column">        

<div class="current all_column column-article-list">
<?php
if(!isset($_GET['type']) && $_GET['type']!='archive'):
if (function_exists('array_column')) 
{
	$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
}
else
{
	$get_content_ids = array_map( function($element) { return $element['content_id']; }, $widget_instance_contents);
}
$get_content_ids = implode("," ,$get_content_ids);

$archive = '';
if($get_content_ids!='')
{
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
	//echo '<pre>'; print_r($widget_instance_contents1); die();
	$widget_contents = array();
	foreach ($widget_instance_contents as $key => $value) {
		foreach ($widget_instance_contents1 as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
				//$author_name_compare = ($view_mode == 'live') ? $value1['author_name'] : $value1['AuthorName'];
				//if($author_name_compare == $author_name )
			   $widget_contents[] = array_merge($value, $value1);
			}
		}
	}
	
	$last_content_id = @$widget_contents[count($widget_contents)-1]['content_id'];

	$i =1;
	if(count($widget_contents) > 0 )
	{	
		// content list iteration block - Looping through content list and adding it the list
		// content list iteration block starts here
		foreach($widget_contents as $get_content)
		{ 
			echo $_GET['type'];
			if($_GET['type']=='archive'){
				echo 'test';
				$live_article_url = $domain_name.$get_content->url.$param;
				$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$get_content->title);   //to remove first<p> and last</p>  tag
				$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
				$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$get_content->summary_html);
				$lastpublishedon = $get_content->last_updated_on;
			}else{
			
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$custom_title        = "";
			$custom_summary      = "";
			$author_name         = ""; 
			$Author_image_path   ="";
				
			
			if($view_mode == "adminview")
			{
				$author_id = $get_content['AuthorID']; 
				$author_name = $get_content['AuthorName'];				
			}
			else 
			{
				$author_name=$get_content['author_name'];
			}
			
			$content_url = $get_content['url'];
			$author_new_url = 'Author/'.$author_name;
			$url_array = explode('/', $content_url);
			$get_seperation_count = count($url_array)-5;
			$author_url = ($get_seperation_count==1)? $domain_name.$author_new_url : (($get_seperation_count==2)? $domain_name.$url_array[0]."/".$url_array[1] : $domain_name.$url_array[0]."/".$url_array[1]."/".$url_array[2]);
			
			$param = $content['close_param'];
			$live_article_url = $domain_name.$content_url.$param;
			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? $get_content['title']: '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);   //to remove first<p> and last</p>  tag
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
			$summary = ( $custom_summary != '') ? $custom_summary : ( ($get_content['summary_html'] != '') ? $get_content['summary_html']: '' ) ;
			$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$summary);
			$lastpublishedon = $get_content['publish_start_date'];
			}
			?>
<div class="sub_column">
<i class=""></i>
<div class="ColumnList">
<?php if($author_id == ''){ 
if ($author_name!="Prabhu Chawla"){ ?>
<a href=<?php echo $author_url; ?>><h4><?php echo $author_name; ?></h4></a>
<?php } } ?>
<h5><a class="article_click" href="<?php echo $live_article_url; ?>" ><?php echo $display_title;?></a></h5>
<p class="column_det summary"><?php echo $summary;?></p>
<p class="post_time"><?php $time = $lastpublishedon; 
$post_time= $this->widget_model->time2string($time); echo $post_time;?> </p>
</div>
<?php 
/* if((($TotalCount < $article_count) && ($i == count($widget_contents)) ) || ($last_content_id == $get_content['content_id']))
{
	//echo  '<div class="col-sm-12"><p class="load_more_archive" style="margin-bottom:10px; margin-top:10px;"><a href="'.$load_more_url.'">More from Archieve</a></p></div>';
	$archive .= '<a class="load_more_archive" href="'.$load_more_url.'">More</a>';
} */

?>			
</div>
<?php 
$i++;			
}
	}
}

endif;
if(isset($_GET['type']) && $_GET['type']=='archive'):
	foreach($widget_contents as $get_content)
		{ 

				$live_article_url = $domain_name.$get_content->url.$param;
				$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$get_content->title);   //to remove first<p> and last</p>  tag
				$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
				$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$get_content->summary_html);
				$lastpublishedon = $get_content->last_updated_on;
			
			?>
<div class="sub_column">
<i class=""></i>
<div class="ColumnList">
<?php if($author_id == ''){ 
if ($author_name!="Prabhu Chawla"){ ?>
<a href=<?php echo $author_url; ?>><h4><?php echo $author_name; ?></h4></a>
<?php } } ?>
<h5><a class="article_click" href="<?php echo $live_article_url; ?>" ><?php echo $display_title;?></a></h5>
<p class="column_det summary"><?php echo $summary;?></p>
<p class="post_time"><?php $time = $lastpublishedon; 
$post_time= $this->widget_model->time2string($time); echo $post_time;?> </p>
</div>		
</div>
<?php 
$i++;			
}
endif;

?>

</div>
</div>
 <?php
			$archive='';
			if(($GetArchiveDetails > 0)  && $_GET['type']!='archive'){
				$load_more_url = $domain_name.'opinions/columns/'.$this->uri->segment(3).'?type=archive';
				$archive = '<a class="load_more_archive" href="'.$load_more_url.'">More from archive</a>';
			}
		  ?>
<?php
echo '<div class="pagina">'.$PaginationLink.$archive.'</div>';
?>
</div>
</div>