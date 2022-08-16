<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid     = $content['sectionID'];
$main_sction_id 	 = "";
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article          = $content['show_max_article'];
$render_mode          = $content['RenderingMode'];
// widget config block ends

$get_widget_instance =  $this->widget_model->getWidgetInstance('', '','', '', $widget_instance_id, $view_mode);

$page_section_id = $content['page_param'];

$subsection_widgetID = $this->widget_model->get_widget_byname('Listing Page Lead Stories', $view_mode);
$subsection_leadstory = $this->widget_model->get_sub_sec_lead_stories_data($page_section_id, $get_widget_instance['Page_type'], $get_widget_instance['WidgetDisplayOrder'], $subsection_widgetID['widgetId'], "", $get_widget_instance['Page_version_id'], $view_mode);
$widget_auto_count = $this->widget_model->select_setting($view_mode);
//$max_article_count = $widget_auto_count['subsection_otherstories_autoCount'];
$max_article_count = 0;

//$article_count       = 15;
$article_count       = $widget_auto_count['subsection_otherstories_count_perpage'];

/*if($max_article_count < $article_count)
	$article_count = $max_article_count;*/

$sectionname = $this->widget_model->get_section_by_id($page_section_id); 
$subsec_leadstory_max_article = 0;
$subsec_leadstory_remdering_mode = ''; 
$subsec_leadstory_instanceID = '';
$subsec_leadstory_mainsection_id= '';
$subsec_leadstory_sectionID = $page_section_id;
$manual_instance                 = "&widget2_max=".$max_article."&widget2_mode=".$render_mode."&widget2_instance=".$widget_instance_id;
if(count($subsection_leadstory)>0)
{ 
	$subsec_leadstory_max_article = $subsection_leadstory ['Maximum_Articles'];
	$subsec_leadstory_remdering_mode = $subsection_leadstory ['RenderingMode'];
	$subsec_leadstory_instanceID = $subsection_leadstory ['WidgetInstance_id'];
	$subsec_leadstory_sectionID = $subsection_leadstory ['WidgetSection_ID'];
	$manual_instance = "&widget1_max=".$subsec_leadstory_max_article."&widget1_mode=".$subsec_leadstory_remdering_mode."&widget1_instance=".$subsec_leadstory_instanceID.$manual_instance;
}

// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();

$show_simple_tab = "";
$show_simple_tab .=' <div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
<div class="Other-Story" '.$widget_bg_color.'>';				

	$show_simple_tab.=	'<h4>Other Stories</h4>';


$show_simple_tab.= ' <div class="btn-group">
<a href="#" id="list_'.$widget_instance_id.'" class="btn btn-default btn-sm"><span class="fa fa-th-list"></span>List</a>
<a href="#" id="grid_'.$widget_instance_id.'" class="btn btn-default btn-sm"><span class="fa fa-th-large"></span>Grid</a>
</div>';
$show_simple_tab.='<div id="products_'.$widget_instance_id.'" class="list-group">';

$leadstory_contentID = array();
$multiple_contentID = '';
$last_content_id = '';

if($content['sectionID'] != "")
$page_section_id = $content['sectionID'];
else
$page_section_id = $content['page_param'];


if($subsec_leadstory_remdering_mode == "manual")
{
	$get_widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($subsec_leadstory_instanceID, '' ,$view_mode, $subsec_leadstory_max_article);
	
	if(count($get_widget_instance_contents)>0)
	{
		foreach($get_widget_instance_contents as $leadstory_contentid)
		{
			$leadstory_contentID[] = $leadstory_contentid['content_id'];
		}
		$multiple_contentID =implode(",",$leadstory_contentID);
	}
	else
	{
		$get_widget_instance_contents = array();
		$multiple_contentID = '';
	}
}
elseif($subsec_leadstory_remdering_mode == "auto" && $subsec_leadstory_sectionID === $content['sectionID'])
{
	$get_widget_instance_contents = $this->widget_model->get_all_available_articles_auto($subsec_leadstory_max_article, $content['sectionID'], 1 ,$content['mode'], $is_home);

	if(count($get_widget_instance_contents)>0)
	{
		foreach($get_widget_instance_contents as $leadstory_contentid)
		{
			$leadstory_contentID[] = $leadstory_contentid['content_id'];
		}
		$multiple_contentID =implode(",",$leadstory_contentID);
	}
}


if($content['RenderingMode'] == "manual") // check widget rendering type manual
{
	$content_type = $content['content_type_id'];  // manual article content type
	$widget_instance_contents_rendering 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], ''); 
	
	$widget_instance_contents = array();
	if(count($widget_instance_contents_rendering) > 0)
	{
		$contentId = array();	
		foreach($widget_instance_contents_rendering as $leadstory_contentid)
		{
			$contentId[] = $leadstory_contentid['content_id'];
		}
	
		$checkid_exists = array_diff($contentId, $leadstory_contentID);
		
		foreach($widget_instance_contents_rendering as $val => $article)
		{
			if(@$checkid_exists[$val] == $article['content_id']) {
				$widget_instance_contents[] = $article;
			}
		}
	}
}
else
{
	$widget_instance_contents = $this->widget_model->get_liveContents_by_sectionId($max_article_count, $page_section_id, $view_mode, $subsec_leadstory_max_article, $subsec_leadstory_remdering_mode, $multiple_contentID, 1, $is_home);
	//$TotalCount = count($widget_instance_contents);
	//$last_content_id = @$widget_instance_contents[$TotalCount-1]['content_id'];
}

$widget_instance_contents = array_slice($widget_instance_contents, 0, $max_article);
$TotalCount = count($widget_instance_contents);
$last_content_id = @$widget_instance_contents[$TotalCount-1]['content_id'];

$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page'):0;

$config['total_rows'] = $TotalCount;
$config['per_page'] = $article_count; 

$config['custom_num_links'] = 5;

$config['page_query_string'] = TRUE;
$config['enable_query_strings']=TRUE;
$config['cur_tag_open'] = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close'] = "</a>";
$this->pagination->initialize($config);
//$PaginationLink = $this->pagination->create_links();
$PaginationLink = $this->pagination->custom_create_links();

//getting content block ends here

//Widget code block - code required for simple tab structure creation. Do not delete
//Widget code block Starts here	
$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
$get_content_ids = implode("," ,$get_content_ids);	

$archive =  '';
if($get_content_ids!='')
{
	$content_type = $content['content_type_id'];
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database_per_page($get_content_ids, $content_type, $is_home, $view_mode, $article_limit,$article_count, 'article_html_text');
	$widget_contents = array();
	foreach ($widget_instance_contents as $key => $value) {
		foreach ($widget_instance_contents1 as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
				$widget_contents[] = array_merge($value, $value1);
			}
		}
	}
	$i =1;
	

	$load_more_url = $domain_name.'topic/?sid='.$content['page_param'].'&cid=1'.$manual_instance;
	$count = 1;
	if(count($widget_contents) > 0)
	{
	// content list iteration block - Looping through content list and adding it the list
	// content list iteration block starts here
	foreach($widget_contents as $key_value => $get_content)
	{
		$original_image_path = "";
		$imagealt            = "";
		$imagetitle          = "";
		$Image600X390        = "";
		
		$custom_title = $get_content['title'];
		$custom_summary =  $get_content['summary_html'];
		if($render_mode == "manual")
		{
			if($get_content['custom_image_path'] != '')    // rendering based on view_mode 
			{
				$original_image_path = $get_content['custom_image_path'];
				$imagealt            = $get_content['custom_image_title'];	
				$imagetitle          = $get_content['custom_image_alt'];												
			}
				$custom_title        = stripslashes($get_content['CustomTitle']);
				$custom_summary      = $get_content['CustomSummary'];
		}
		if($original_image_path =='')
		{
			if($get_content['ImagePhysicalPath'] !='')
			{
				$original_image_path  = $get_content['ImagePhysicalPath'];
				$imagealt             = $get_content['ImageCaption'];	
				$imagetitle           = $get_content['ImageAlt'];	
			}	
		}
		$show_image="";
		if($original_image_path !='' && get_image_source($original_image_path, 1))
		{
			$imagedetails = get_image_source($original_image_path, 2);
			$imagewidth = $imagedetails[0];
			$imageheight = $imagedetails[1];	
		
			if ($imageheight > $imagewidth)
			{
				$Image600X390 	= $original_image_path;
			}
			else
			{
			   $Image600X390  = str_replace("original","w600X390", $original_image_path);
			}
			if (get_image_source($Image600X390, 1) && $Image600X390 != '')
			{
				$show_image = image_url. imagelibrary_image_path . $Image600X390;
			}
			else
			{
				$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}	
		}
		else
		{
			$show_image	  = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
		}
		$dummy_image  = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
		
		$content_url = $get_content['url'];
		$param = $content['close_param'];
		$live_article_url = $domain_name.$content_url.$param;
		
		
		$lastpublishedon = $get_content['publish_start_date'];
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
		$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
		$custom_summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);	
		
		if(trim($custom_summary) == ''&& $render_mode=="auto") {
			
			$bodycontent = trim(strip_tags($get_content['article_page_content_html']));
			$headline_position = strpos($bodycontent, ":");
			
			if($headline_position < 50) {
			$bodycontent = substr($bodycontent, $headline_position + 1);  
			}
			
			$custom_summary  = character_limiter($bodycontent,150);
			
		}
		
		// Assign summary block starts here	
		
		
		if($count <= 3)
		{
			if($count==1){
				$show_simple_tab.= '<div class="row">'; 
			} 
			$show_simple_tab.= '<div class="item  col-lg-4 col-md-4 col-sm-4 col-xs-12 list-group-item">';		
			$show_simple_tab.= '<div class="thumbnail"> <a  href="'.$live_article_url.'" class="article_click" >';
			$show_simple_tab.= '<img class="group list-group-image" src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';		
			$show_simple_tab.= '<div class="caption"><h5 class="group inner list-group-item-heading">'. $display_title.'</h5>';
			$show_simple_tab.= '<p class="group inner list-group-item-text summary">'.stripslashes($custom_summary).'</p>';
			$time= $lastpublishedon; 
			$post_time= $this->widget_model->time2string($time);
			$show_simple_tab.=	'<p class="post_time">'.$post_time.' </p>';		
			$show_simple_tab.='</div></div></div>';
			
			if(($TotalCount < $article_count) && ($i == count($widget_contents))  || ($last_content_id == $get_content['content_id']))
			{
				//$show_simple_tab.= '<p class="load_more_archive" style="margin-bottom:10px;"><a href="'.$load_more_url.'">More</a></p>';
				$archive .= '<a class="load_more_archive" href="'.$load_more_url.'">More</a>';
			}
			
			if($count==3 || ($i == count($widget_contents)))
			{ 
				$show_simple_tab.= '</div>';
				$count=0;
			}
			$count ++;	
		}				
		$i =$i+1;	
	}// content list iteration block ends here
	}
	else{
		$show_simple_tab ='';
		return false;
	}
}
 elseif($view_mode=="adminview")
{
  $show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
$show_simple_tab .='</div></div>';

$show_simple_tab .='<div class="pagina">';
$show_simple_tab .= $PaginationLink ;
$show_simple_tab .=$archive.'</div></div></div>';
echo $show_simple_tab;
?>
<script>  
$(document).ready(function() {
/* $('#products_<?php echo $widget_instance_id; ?> .item').addClass('list-group-item');*/
$('.Other-Story #list_<?php echo $widget_instance_id; ?>').addClass('active');
$('#list_<?php echo $widget_instance_id; ?>').click(function(event){
event.preventDefault();$('#products_<?php echo $widget_instance_id; ?> .item').addClass('list-group-item');$('#products_<?php echo $widget_instance_id; ?> .item').removeClass('grid-group-item');
$('.Other-Story #list_<?php echo $widget_instance_id; ?>').removeClass('active').addClass('active');
$('.Other-Story #grid_<?php echo $widget_instance_id; ?>').removeClass('active');
});
$('#grid_<?php echo $widget_instance_id; ?>').click(function(event){
event.preventDefault();$('#products_<?php echo $widget_instance_id; ?> .item').removeClass('list-group-item');$('#products_<?php echo $widget_instance_id; ?> .item').addClass('grid-group-item');
$('.Other-Story #list_<?php echo $widget_instance_id; ?>').removeClass('active');
$('.Other-Story #grid_<?php echo $widget_instance_id; ?>').addClass('active');
});
});

</script>