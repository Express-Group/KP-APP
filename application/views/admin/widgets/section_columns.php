<?php 
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

// widget config block ends

$widget_auto_count = $this->widget_model->select_setting($view_mode);
$columnist_count   = $widget_auto_count['subsection_otherstories_count_perpage'];
$max_article_count = $widget_auto_count['subsection_otherstories_autoCount'];
$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="all_column" id="columns_'.$widget_instance_id.'" '.$widget_bg_color.'>';
						

if($render_mode == "manual")
{
	$content_type = $content['content_type_id'];  // manual article content type
	$widget_instance_contents_pageination = $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article);
}
else
{
	$content_type = $content['content_type_id'];  // auto article content type
	$widget_instance_contents_pageination = $this->widget_model->get_column_list_auto_totalcount($max_article, $content['sectionID'] , $content_type ,  $view_mode);
}

//print_r($widget_instance_contents_pageination); 
if (function_exists('array_column')) 
{
	$get_content_ids = array_column($widget_instance_contents_pageination, 'content_id'); 
}
else
{
	$get_content_ids = array_map( function($element) { return $element['content_id']; }, $widget_instance_contents);
}
$get_content_ids = implode("," ,$get_content_ids);

/*$widget_contents_pagination = array();
$content_id = '';
if($get_content_ids!='')
{
	$widget_instance_article = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
	$content_id = array();
	foreach ($widget_instance_contents_pageination as $key => $value) {
		foreach ($widget_instance_article as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
			   $widget_contents_pagination[] = array_merge($value, $value1);
			   $content_id[] = $value1['content_id'];
			}
		}
	}
	$content_id = implode("," ,$content_id);
}*/

$TotalCount= count($widget_instance_contents_pageination); 
$last_content_id = @$widget_instance_contents_pageination[$TotalCount-1]['content_id'];

// Pagination start											
$this->load->library('pagination');
$config['total_rows'] = $TotalCount; 
$config['per_page'] = $columnist_count; 
$config['custom_num_links'] = 5;
$config['page_query_string'] = TRUE;
$config['enable_query_strings']=TRUE;

$config['use_page_numbers'] = TRUE;
$config['cur_tag_open'] = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close'] = "</a>";

$page_num = $config['use_page_numbers'];
$columnist_limit = ($this->input->get('per_page') != '')?$this->input->get('per_page'):0;


$start = $columnist_limit; 
$page_number = $this->input->get('per_page')/$config['per_page'] + 1 ;
$limit = $columnist_count;
$config['use_page_numbers'] = TRUE;
//$offset = $this->uri->segment(4);
$this->pagination->initialize($config); 
//$PaginationLink = $this->pagination->create_links();
$PaginationLink = $this->pagination->custom_create_links();

$load_more_url = $domain_name.'topic/?sid='.$content['page_param'].'&cid=1';
// pagination end	

$archive =  '';

if($get_content_ids!='')
{
	//$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database_per_page($get_content_ids, $content_type, $is_home, $view_mode, $start,$limit, '');	
	$widget_contents = array();
	foreach ($widget_instance_contents_pageination as $key => $value) {
		foreach ($widget_instance_contents1 as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
			   $widget_contents[] = array_merge($value, $value1);
			}
		}
	}

	$i =1;
	if(count($widget_contents) > 0 )
	{	
		// content list iteration block - Looping through content list and adding it the list
		// content list iteration block starts here
		foreach($widget_contents as $get_content)
		{
			// Code Block B starts here - Do not change
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$custom_title        = "";
			$custom_summary      = "";
			$author_name         = ""; 
			$Author_image_path   ="";
			if($render_mode == "manual")
			{
				$custom_title   = $get_content['CustomTitle'];
				$custom_summary = $get_content['CustomSummary'];
			}
			if($view_mode == "adminview")
			{	
				$image_path=$get_content['image_path'] ;
				if($image_path !='')
				{
					$Author_image_path  = $get_content['image_path'];
					$imagealt             =  $get_content['image_alt'];
					$imagetitle           =  $get_content['image_caption'];
				}
				$section = $get_content['section_name'];
			}
			else if($view_mode =="live")
			{	
				if($get_content['author_image_path'] !="")
				{
					$Author_image_path  =  $get_content['author_image_path'];
					$imagealt           = $get_content['author_image_alt'];	
					$imagetitle         = $get_content['author_image_title'];
				}
				$section = $get_content['section_name'];
			}
			$show_image="";
			if($Author_image_path !='')
			{
				if (getimagesize(image_url_no . $Author_image_path) && $Author_image_path != '')
				{ 
					$show_image = image_url. $Author_image_path;
				}
				else
				{
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
				}
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
			}
			else
			{
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
					$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
			}
			
			if($view_mode == "adminview")
			{
				$author_id   = $get_content['AuthorID']; 
				$author_name = $get_content['AuthorName'];	
				$author_url  = join("-", explode(" ", $author_name)); 			
			}
			else 
			{
				$author_name = $get_content['author_name'];
				$author_url  = join("-", explode(" ", $author_name)); 
			}
			
			
			$content_url = $get_content['url'];
			
			$url_array = explode('/', $content_url);
			$get_seperation_count = count($url_array)-4;
			$section_url = ($get_seperation_count==1)? $author_url : (($get_seperation_count==2)? $url_array[0]."/".$url_array[1] : $url_array[0]."/".$url_array[1]."/".$url_array[2]);
			
			//$author_pos = strpos($section_url, "columns");
			//$author_pos = stripos("columns", $section_url);
			$author_pos = stripos($author_name, $section);
			//echo "</pre>";print_r($section)."</br>";
            if ($author_pos === false) {
			$author_new_url = $domain_name.'Author/'.$author_url;
			}else
			{
			$author_new_url = $domain_name.$section_url;
			}
			$param = $content['close_param'];
			$live_article_url = $domain_name.$content_url.$param;
			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? $get_content['title']: '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);   //to remove first<p> and last</p>  tag
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
			$custom_summary = ( $custom_summary == '' && $render_mode=="auto") ? $get_content['summary_html']: '';
			$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);
			$lastpublishedon = $get_content['publish_start_date'];
			//  Assign article links block ends hers
			
			// display title and summary block starts here
			$show_simple_tab .= '<div class="sub_column sub_column_main">';
			$show_simple_tab .='<a  href="'.$live_article_url.'"  ><img src="'.$dummy_image.'"  data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';
			$show_simple_tab .= '<a href="'.$author_new_url.'"  ><h4>'.$author_name.'</h4></a>';
			$show_simple_tab .= '<h5>'.$display_title.'</h5>';
			$show_simple_tab .='<p class="column_det summary">'.$summary.'</p>';
			$time = $lastpublishedon; 
			$post_time = $this->widget_model->time2string($time);
			
			$show_simple_tab .=	'<p class="post_time">'.$post_time.' </p>'; 
			$show_simple_tab .= '</div>';
			
			if(($TotalCount < $columnist_count) && ($i == count($widget_contents))  || ($last_content_id == $get_content['content_id']))
			{
				//$show_simple_tab.= '<div class="col-sm-12"><p class="load_more_archive" style="margin-bottom:10px; margin-top:10px;"><a href="'.$load_more_url.'">More from Archieve</a></p></div>';
				$archive .= '<a class="load_more_archive" href="'.$load_more_url.'">More</a>';
			}
			if($i == count($widget_contents))
			{
				$show_simple_tab .='</div>';
			}
					
			$i =$i+1;							  
		}// content list iteration block ends here
	}
}
 elseif($view_mode=="adminview")
{
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div></div>';
}
 elseif($view_mode=="live")
{
	$show_simple_tab .='</div>';
}
$show_simple_tab .='</div><div class="pagina">';
$show_simple_tab .= $PaginationLink ;
$show_simple_tab .=$archive.'</div></div>';
echo $show_simple_tab;
?>
