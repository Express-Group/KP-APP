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

//$article_count       = 15;
// widget config block ends

$get_widget_instance =  $this->widget_model->getWidgetInstance('', '','', '', $widget_instance_id, $view_mode);
//$page_section_id = $get_widget_instance['Pagesection_id']; 
 $page_section_id = $content['page_param'];

$subsection_widgetID = $this->widget_model->get_widget_byname('Gallery and Video Lead', $view_mode);
$subsection_leadstory = $this->widget_model->get_sub_sec_lead_stories_data($page_section_id, $get_widget_instance['Page_type'], $get_widget_instance['WidgetDisplayOrder'], $subsection_widgetID['widgetId'],"", $get_widget_instance['Page_version_id'], $view_mode);

$widget_auto_count = $this->widget_model->select_setting($view_mode);
//$max_article_count = $widget_auto_count['subsection_otherstories_autoCount'];

$max_article_count = 0;

$article_count       = $widget_auto_count['subsection_otherstories_count_perpage'];

$sectionname = $this->widget_model->get_section_by_id($page_section_id); 
$subsec_leadstory_max_article = 0;
$subsec_leadstory_remdering_mode = ''; 
$subsec_leadstory_instanceID = '';
$subsec_leadstory_mainsection_id= '';

if(count($subsection_leadstory)>0)
{ 
	$subsec_leadstory_max_article = $subsection_leadstory ['Maximum_Articles'];
	$subsec_leadstory_remdering_mode = $subsection_leadstory ['RenderingMode'];
	$subsec_leadstory_instanceID = $subsection_leadstory ['WidgetInstance_id'];
}

//die($subsec_leadstory_max_article);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .=' <div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="Other-Story">';

$show_simple_tab.=	'<h4 class="MagazinesTitle SiteColor">Other Galleries</h4>';

/*$show_simple_tab.= ' <div class="btn-group">
<a href="#" id="list_'.$widget_instance_id.'" class="btn btn-default btn-sm"><span class="fa fa-th-list"></span>List</a>
<a href="#" id="grid_'.$widget_instance_id.'" class="btn btn-default btn-sm"><span class="fa fa-th-large"></span>Grid</a>
</div>';*/
$show_simple_tab.='<div id="products_'.$widget_instance_id.'" class="list-group">';

$leadstory_contentID = '';
$last_content_id = '';

$multiple_contentID = '';
if($subsec_leadstory_remdering_mode == "manual")
{
	$get_widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($subsec_leadstory_instanceID, '' ,$view_mode, $subsec_leadstory_max_article);
	$leadstory_contentID = array();		
	if(count($get_widget_instance_contents)>0)
	{
		foreach($get_widget_instance_contents as $leadstory_contentid)
		{
			$leadstory_contentID[] = $leadstory_contentid['content_id'];
		}
		$multiple_contentID =implode(",",$leadstory_contentID);
		$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page'):0;
		$widget_instance_contents = $this->widget_model->get_liveContents_by_sectionId($article_count, $page_section_id, $view_mode, $article_limit, $subsec_leadstory_remdering_mode, $multiple_contentID, 3, $is_home);	
		$total_data = $this->widget_model->get_liveContents_by_sectionId($max_article_count, $page_section_id, $view_mode, $subsec_leadstory_max_article, $subsec_leadstory_remdering_mode, $multiple_contentID, 3, $is_home);
		$TotalCount = count($total_data);
	
	}
	else
	{
		$leadstory_contentID = '';
		$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page'):0;
		$widget_instance_contents = $this->widget_model->get_liveContents_by_sectionId($article_count, $page_section_id, $view_mode, $article_limit, $subsec_leadstory_remdering_mode, $leadstory_contentID, 3, $is_home);		
		$total_data = $this->widget_model->get_liveContents_by_sectionId($max_article_count, $page_section_id, $view_mode, $subsec_leadstory_max_article, $subsec_leadstory_remdering_mode, $multiple_contentID, 3, $is_home);
		$TotalCount = count($total_data);
	}
}
else
{
	$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : 0; //to set article start  count for each page
	
	if($subsec_leadstory_remdering_mode == "auto")
	{
		$get_widget_instance_contents = $this->widget_model->get_all_available_articles_auto($subsec_leadstory_max_article, $page_section_id , 3 ,  $content['mode'], $is_home);
		if(count($get_widget_instance_contents)>0)
		{
			$leadstory_contentID = array();	
			foreach($get_widget_instance_contents as $leadstory_contentid)
			{
				$leadstory_contentID[] = $leadstory_contentid['content_id'];
			}
			$multiple_contentID =implode(",",$leadstory_contentID);
		}
	}
	
	$widget_instance_contents = $this->widget_model->get_liveContents_by_sectionId($article_count, $page_section_id, $view_mode, $article_limit, $subsec_leadstory_remdering_mode, $multiple_contentID, 3, $is_home);
	
	$total_data = $this->widget_model->get_liveContents_by_sectionId($max_article_count, $page_section_id, $view_mode, $subsec_leadstory_max_article, $subsec_leadstory_remdering_mode, $multiple_contentID, 3, $is_home);
	$TotalCount = count($total_data);
}
$last_content_id = @$total_data[$TotalCount-1]['content_id'];
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
		
		
$manual_instance = "&instance=archive";
$load_more_url = $domain_name.'topic/?sid='.$content['page_param'].'&cid=3'.$manual_instance;

$widget_contents = $widget_instance_contents;

//$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
//$get_content_ids = implode("," ,$get_content_ids);	

$archive =  '';

if(count($widget_contents) > 0)
{
	/*$content_type = $content['content_type_id'];
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, 3, $is_home, $view_mode);	
	$widget_contents = array();
	//echo '<pre>'; print_r($widget_instance_contents1); die();	
	foreach ($widget_instance_contents as $key => $value) {
		foreach ($widget_instance_contents1 as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
				$widget_contents[] = array_merge($value, $value1);
			}
		}
	}*/

	$i =1;
	$count = 1;
	
	// content list iteration block - Looping through content list and adding it the list
	// content list iteration block starts here
	foreach($widget_contents as $get_content)
	{
		//$content_details = $this->widget_model->get_contentdetails_from_database($get_content['content_id'], $content_type, $is_home, $view_mode);	
		$original_image_path = "";
		$imagealt            = "";
		$imagetitle          = "";
		if($original_image_path =='')
		{
			if($view_mode == 'live')
			{
				if($get_content['first_image_path'] !='')
				{
					$original_image_path  = $get_content['first_image_path'];
					$imagealt             = $get_content['first_image_title'];	
					$imagetitle           = $get_content['first_image_alt'];	
				}
			}
			else
			{
				if($get_content['ImagePhysicalPath'] !='')
				{
					$original_image_path  = $get_content['ImagePhysicalPath'];
					$imagealt             = $get_content['ImageCaption'];	
					$imagetitle           = $get_content['ImageAlt'];	
				}
			}	
		}
		
		//$SourceURL = $content['widget_img_phy_path'];
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
		
		$custom_title = $get_content['title'];
		$lastpublishedon = $get_content['publish_start_date'];
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
		$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
		//$summary =  $get_content['summary_html'];
		//$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$summary);	
		// Assign summary block starts here	
		if($i <= 2)
		{
			if($i==1){
				$show_simple_tab.= '<div class="row">'; 
			} 
			$show_simple_tab.= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><div class="GalleryDetailList1">';
			$show_simple_tab.= '<figure class="PositionRelative">'; 
			$show_simple_tab.= '<a  href="'.$live_article_url.'" class="article_click" ><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';
			$show_simple_tab.= '<img class="GalleryListing " src="'.image_url. imagelibrary_image_path.'/gallery-icon.png"> </figure>';
			$show_simple_tab.= '<p>'.$display_title.'</p>';
			$show_simple_tab.='</div></div>';
			
			if(($TotalCount < $article_count) && ($i == count($widget_contents))  || ($last_content_id == $get_content['content_id']))
			{
				//$show_simple_tab.= '<div class="col-sm-12"><p class="load_more_archive" style="margin-bottom:10px;"><a href="'.$load_more_url.'">More from Archieve</a></p></DIV>';
				$archive .= '<a class="load_more_archive" href="'.$load_more_url.'">More</a>';
			}
			
			if($i==2 || ($i == count($widget_contents)))
			{ 
				$show_simple_tab.= '</div>';
			} 
		}
		else
		{
			if($count==1)
			{
				$show_simple_tab.= '<div class="row">'; 
			}
			$show_simple_tab.= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><div class="GalleryDetailList1">';
			$show_simple_tab.= '<figure class="PositionRelative">';
			$show_simple_tab.= '<a  href="'.$live_article_url.'" class="article_click" ><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';
			$show_simple_tab.= '<img class="GalleryListing " src="'.image_url. imagelibrary_image_path.'/gallery-icon.png"> </figure>';
			$show_simple_tab.= '<p>'.$display_title.'</p>';
			$show_simple_tab.='</div></div>';		
			
			if(($TotalCount < $article_count) && ($i == count($widget_contents))  || ($last_content_id == $get_content['content_id']))
			{
				//$show_simple_tab.= '<div class="col-sm-12"><p class="load_more_archive" style="margin-bottom:10px;"><a href="'.$load_more_url.'">More from Archieve</a></p></div>';
				$archive .= '<a class="load_more_archive" href="'.$load_more_url.'">More</a>';
			}
				
			if($count == 3 || ($i == count($widget_contents)))
			{
				$show_simple_tab.= '</div>';
			}
			$count ++;
			if($count>3)
			{
				$count=1;
			}
		}					
		$i =$i+1;	
	}// content list iteration block ends here
	
}
 elseif($view_mode=="adminview")
{
  $show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
$show_simple_tab .='</div></div>';
echo $show_simple_tab;
echo '<div class="pagina">';
echo $PaginationLink ;
echo $archive.'</div></div></div>';
?>
<script>  
$(document).ready(function() {
/* $('#products_<?php echo $widget_instance_id; ?> .item').addClass('list-group-item');*/
$('.Other-Story #grid_<?php echo $widget_instance_id; ?>').addClass('active');
});

</script>