<?php
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id      = $content['sectionID'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
$content_type        = $content['content_type_id']; // auto article content type

$sec_leadstory_rendering_mode = '';
$widget_instance_contents = array();
// widget config block ends
$domain_name     = base_url();
$show_simple_tab = "";
$show_simple_tab .= '<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-10">
            <div class="business" ' . $widget_bg_color . '>
                <fieldset class="FieldTopic">';

if($content['widget_title_link'] == 1)
{
	$show_simple_tab .= ' <legend class="topic"><a href="' . $widget_section_url . '">' . $widget_custom_title . '</a></legend>';
}
else
{
	$show_simple_tab .= ' <legend class="topic">' . $widget_custom_title . '</legend>';
}
$show_simple_tab .= '</fieldset>';

/*if($render_mode == "manual")
{		
$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " , $view_mode, $max_article); 						
}
else
{
$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $main_sction_id , $content_type ,  $view_mode);
}*/

$get_widget_instance = $this->widget_model->getWidgetInstance('', '', '', '', $widget_instance_id, $view_mode);

$pagemaster_data = $this->widget_model->get_pagemaster_live_version($main_sction_id, $get_widget_instance['Page_type'], $view_mode); //get the live version data of sub section lead stories
$live_version    = $pagemaster_data['Published_Version_Id'];

$section_widgetID = $this->widget_model->get_widget_byname('Listing Page Lead Stories', $view_mode);
$leadstory        = $this->widget_model->get_sub_sec_lead_stories_data($main_sction_id, $get_widget_instance['Page_type'], $get_widget_instance['WidgetDisplayOrder'], $section_widgetID['widgetId'], $main_sction_id, $live_version, $view_mode);
$widget_contents           = array();
if(count($leadstory) > 0)
{
	$sec_leadstory_max_article    = $leadstory['Maximum_Articles'];
	$sec_leadstory_rendering_mode = $leadstory['RenderingMode'];
	$sec_leadstory_instanceID     = $leadstory['WidgetInstance_id'];
	$sec_leadstory_sectionID      = $leadstory['WidgetSection_ID'];
	
	if($sec_leadstory_rendering_mode == "manual")
	{
		$widget_instance_contents = $this->widget_model->get_widgetInstancearticles_rendering($sec_leadstory_instanceID, '', $view_mode, $sec_leadstory_max_article);
		$get_content_ids = array_column($widget_instance_contents, 'content_id');
	$get_content_ids = implode(",", $get_content_ids);
	$render_mode = $sec_leadstory_rendering_mode;
	$widget_contents           = array();
		
		if($get_content_ids != '')
	{
		$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);
		foreach($widget_instance_contents as $key => $value)
		{
			foreach($widget_instance_contents1 as $key1 => $value1)
			{
				if($value['content_id'] == $value1['content_id'])
				{
					$widget_contents[] = array_merge($value, $value1);
				}
			}
		}
	}
	}
	elseif($sec_leadstory_rendering_mode == "auto")
	{
		$render_mode = $sec_leadstory_rendering_mode;
		$widget_contents = $this->widget_model->get_all_available_articles_auto($sec_leadstory_max_article, $main_sction_id, $content_type, $view_mode, $is_home);
	}
}
	$i = 1;
	if(count($widget_contents) > 0)
	{
		foreach($widget_contents as $get_content)
		{
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$custom_title        = "";
			$custom_summary      = "";
			//if($render_mode == "manual")
			if($render_mode == "manual")
			{
				if($get_content['custom_image_path'] != '')
				{
					$original_image_path = $get_content['custom_image_path'];
					$imagealt            = $get_content['custom_image_title'];
					$imagetitle          = $get_content['custom_image_alt'];
				}
				$custom_title   = $get_content['CustomTitle'];
				$custom_summary = $get_content['CustomSummary'];
			}
			if($original_image_path == "") // from cms imagemaster table    
			{
				$original_image_path = $get_content['ImagePhysicalPath'];
				$imagealt            = $get_content['ImageCaption'];
				$imagetitle          = $get_content['ImageAlt'];
			}
			
			$show_image = "";
			if($original_image_path != '' && get_image_source($original_image_path, 1))
			{
				$imagedetails = get_image_source($original_image_path, 2);
				$imagewidth   = $imagedetails[0];
				$imageheight  = $imagedetails[1];
				
				if($imageheight > $imagewidth)
				{
					$Image600X390 = $original_image_path;
				}
				else
				{
					$Image600X390 = str_replace("original", "w600X390", $original_image_path);
				}
				if(get_image_source($Image600X390, 1) && $Image600X390 != '')
				{
					$show_image = image_url . imagelibrary_image_path . $Image600X390;
				}
				else
				{
					$show_image = image_url . imagelibrary_image_path . 'logo/nie_logo_600X390.jpg';
				}
				
			}
			else
			{
				$show_image = image_url . imagelibrary_image_path . 'logo/nie_logo_600X390.jpg';
				
			}
			$dummy_image = image_url . imagelibrary_image_path . 'logo/nie_logo_600X390.jpg';
			
			
			$content_url      = $get_content['url'];
			$param            = $content['close_param'];
			$live_article_url = $domain_name . $content_url . $param;
			
			if($custom_title == '')
			{
				$custom_title = $get_content['title'];
			}
			
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_title); //to remove first<p> and last</p>  tag
			$display_title = '<a  href="' . $live_article_url . '" class="article_click" >' . $display_title . '</a>';
			//  Assign article links block ends hers
			
			// Assign summary block - creating links for  article summary
			
			if($custom_summary == '' && $render_mode == "auto")
			{
				$custom_summary = @$get_content['summary_html'];
			}
			$summary = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_summary); //to remove first<p> and last</p>  tag
			// Assign summary block starts here
			
			if($i == 1)
			{
				
				$show_simple_tab .= ' <div class="business1"> <a  href="' . $live_article_url . '"><img src="' . $show_image . '" title = "' . $imagetitle . '" alt = "' . $imagealt . '"></a><h4 class="subtopic">' . $display_title . '</h4>';
				if($is_summary_required == 1)
				{
					$show_simple_tab .= '<p class="para_bold summary">' . $summary . '</p>';
				}
				$show_simple_tab .= '</div>';
				
			}
			else
			{
				if($i == 2)
					$show_simple_tab .= '<div class="business2 common_p">';
				
				$show_simple_tab .= '<p> <i class="fa fa-angle-right"></i> ' . $display_title . '</p>';
				
				if($i == count($widget_contents))
					$show_simple_tab .= '</div>';
			}
			
			// display title and summary block ends here					
			//Widget design code block 1 starts here																
			//Widget design code block 1 starts here			
			$i = $i + 1;
		}
		
		// Adding content Block ends here
	}elseif($view_mode == "adminview")
{
	$show_simple_tab .= '<div class="margin-bottom-10">' . no_articles . '</div>';
}
$show_simple_tab .= ' </div>';
if($content['widget_title_link'] == 1)
{
	$show_simple_tab .= '<div class="arrow_right"><a href="' . $widget_section_url . '" class="landing-arrow">arrow</a></div>';
}

$show_simple_tab .= '  </div>
          </div>';



echo $show_simple_tab;
?>