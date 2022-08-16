<?php
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id      = "";
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$widget_article_count = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
// widget config block ends
$domain_name         = base_url();

$widget_auto_count = $this->widget_model->select_setting($view_mode);

//$article_count   = 6;

$article_count = $widget_auto_count['magazine_list_count_perpage'];

$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : 0;
$content_type  = $content['content_type_id'];
//$article_count       = $widget_auto_count['subsection_otherstories_count_perpage'];

$archive         = '';
$show_simple_tab = "";

$show_simple_tab .= '<div class="row">
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
//<div class="MagazinesList">';
// Adding content Block starts here
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual")
{
	$content_type                         = $content['content_type_id']; // manual article content type
	$widget_instance_contents_pageination = $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $widget_article_count);
}
else
{
	$get_widget_instance = $this->widget_model->getWidgetInstance('', '', '', '', $widget_instance_id, $view_mode);
	
	$page_section_id = $content['page_param'];
	
	$widgetID  = $this->widget_model->get_widget_byname('Magazine Lead', $view_mode);
	$leadstory = $this->widget_model->get_sub_sec_lead_stories_data($page_section_id, $get_widget_instance['Page_type'], $get_widget_instance['WidgetDisplayOrder'], $widgetID['widgetId'], "", $get_widget_instance['Page_version_id'], $view_mode);
	
	if(count($leadstory) > 0)
	{
		$max_article    = $leadstory['Maximum_Articles'];
		$rendering_mode = $leadstory['RenderingMode'];
		$instanceID     = $leadstory['WidgetInstance_id'];
		
		if($rendering_mode == "manual")
		{
			$get_widget_instance_contents = $this->widget_model->get_widgetInstancearticles_rendering($instanceID, '', $view_mode, $widget_article_count);
			
			$leadstory_contentID = array();
			if(count($get_widget_instance_contents) > 0)
			{
				foreach($get_widget_instance_contents as $leadstory_contentid)
				{
					$leadstory_contentID[] = $leadstory_contentid['content_id'];
				}
				$multiple_contentID                   = implode(",", $leadstory_contentID);
				$widget_instance_contents_pageination = $this->widget_model->get_liveContents_by_sectionId('', $page_section_id, $view_mode, $article_limit, $rendering_mode, $multiple_contentID, 1, $is_home);
			}
			else
			{
				$widget_instance_contents_pageination = $this->widget_model->get_all_available_articles_auto_totalcount($widget_article_count, $content['sectionID'], $content_type, $view_mode);
			}
		}
		else
		{
			$get_widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $page_section_id, 1, $content['mode']);
			if(count($get_widget_instance_contents) > 0)
			{
				$leadstory_contentID = array();
				foreach($get_widget_instance_contents as $leadstory_contentid)
				{
					$leadstory_contentID[] = $leadstory_contentid['content_id'];
				}
				$multiple_contentID = implode(",", $leadstory_contentID);
			}
			$widget_instance_contents_pageination = $this->widget_model->get_liveContents_by_sectionId('', $page_section_id, $view_mode, $article_limit, $rendering_mode, $multiple_contentID, 1, $is_home);
		}
	}
	else
	{
		$widget_instance_contents_pageination = $this->widget_model->get_all_available_articles_auto_totalcount($widget_article_count, $content['sectionID'], $content_type, $view_mode);
	}
	
}

if(function_exists('array_column'))
{
	$get_content_ids = array_column($widget_instance_contents_pageination, 'content_id');
}
else
{
	$get_content_ids = array_map(function($element) { return $element['content_id']; }, $widget_instance_contents);
}
$get_content_ids = implode(",", $get_content_ids);

/*$widget_contents_pagination = array();
$content_id                 = '';
if($get_content_ids != '')
{
	$widget_instance_article = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);
	$content_id              = array();
	foreach($widget_instance_contents_pageination as $key => $value)
	{
		foreach($widget_instance_article as $key1 => $value1)
		{
			if($value['content_id'] == $value1['content_id'])
			{
				$widget_contents_pagination[] = array_merge($value, $value1);
				$content_id[]                 = $value1['content_id'];
			}
		}
	}
	$content_id = implode(",", $content_id);
}
*/

//getting content block ends here
//Widget code block - code required for simple tab structure creation. Do not delete
//Widget code block Starts here
// content list iteration block - Looping through content list and adding it the list
// content list iteration block starts here
// Pagination start											
$this->load->library('pagination');
$TotalCount                     = count($widget_instance_contents_pageination);
$last_content_id                = @$widget_instance_contents_pageination[$TotalCount - 1]['content_id'];
$config['total_rows']           = $TotalCount;
$config['per_page']             = $article_count;
$config['page_query_string']    = TRUE;
$config['enable_query_strings'] = TRUE;
$config['custom_num_links']     = 5;

$config['use_page_numbers'] = TRUE;
$config['cur_tag_open']     = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close']    = "</a>";

$page_num = $config['use_page_numbers'];

$start                      = $article_limit;
$page_number                = $this->input->get('per_page') / $config['per_page'] + 1;
$limit                      = $article_count;
$config['use_page_numbers'] = TRUE;
//$offset = $this->uri->segment(4);
$this->pagination->initialize($config);
//$PaginationLink = $this->pagination->create_links();
$PaginationLink = $this->pagination->custom_create_links();

$load_more_url = $domain_name . 'topic/?sid=' . $content['page_param'] . '&cid=1';

// pagination end										


if($get_content_ids != '')
{
	$widget_instance_contents2 = $this->widget_model->get_contentdetails_from_database_per_page($get_content_ids, $content_type, $is_home, $view_mode, $start, $limit, '');
	$widget_contents           = array();
	foreach($widget_instance_contents_pageination as $key => $value)
	{
		foreach($widget_instance_contents2 as $key1 => $value1)
		{
			if($value['content_id'] == $value1['content_id'])
			{
				$widget_contents[] = array_merge($value, $value1);
			}
		}
	}
	$i = 1;
	$j = 1;
	if(count($widget_contents) > 0)
	{
		foreach($widget_contents as $get_content)
		{
			// Code Block B - if rendering mode is manual then if custom image is available then assigning the imageid to a variable
			// Code Block B starts here - Do not change
			
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$custom_title        = "";
			$custom_summary      = "";
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
			
			if($original_image_path == "") // from cms || live table    
			{
				$original_image_path = $get_content['ImagePhysicalPath'];
				$imagealt            = $get_content['ImageCaption'];
				$imagetitle          = $get_content['ImageAlt'];
			}
			
			$show_image = "";
			if($original_image_path != ''  && get_image_source($original_image_path, 1))
			{
				$imagedetails = get_image_source($original_image_path, 2);
				$imagewidth = $imagedetails[0];
				$imageheight = $imagedetails[1];
				if ($imageheight > $imagewidth)
				{
					$Image600X300 	= $original_image_path;
				}
				else
				{
				    $Image600X300 = str_replace("original", "w600X300", $original_image_path);
				}
				
				if(get_image_source($Image600X300, 1) && $Image600X300 != '')
				{
					$show_image = image_url . imagelibrary_image_path . $Image600X300;
				}
				else
				{
					$show_image = image_url . imagelibrary_image_path . 'logo/nie_logo_600X300.jpg';
				}
				$dummy_image = image_url . imagelibrary_image_path . 'logo/nie_logo_600X300.jpg';
			}
			else
			{
				$show_image  = image_url . imagelibrary_image_path . 'logo/nie_logo_600X300.jpg';
				$dummy_image = image_url . imagelibrary_image_path . 'logo/nie_logo_600X300.jpg';
			}
			$content_url      = $get_content['url'];
			$param            = $content['close_param'];
			$live_article_url = $domain_name . $content_url . $param;
			
			if($custom_title == '')
			{
				$custom_title = $get_content['title'];
			}
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_title); //to remove first<p> and last</p>  tag
			$display_title = '<a  href="' . $live_article_url . '" class="article_click" >' . $display_title .' </a>';
			// Assign summary block starts here
			if($custom_summary == '' && $render_mode=="auto")
			{
				$custom_summary = $get_content['summary_html'];
			}
			$summary = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_summary); //to remove first<p> and last</p>  tag
			// Assign summary block starts here
			
			
			if($i <= 2)
			{
				if($i == 1)
				{
					$show_simple_tab .= '<div class="MagazinesList">';
					$show_simple_tab .= '<div class="MagazinesListLeft">';
					$show_simple_tab .= '<div class="Row"> <a  href="' . $live_article_url . '" class="article_click"  ><img src="' . $dummy_image . '" data-src="' . $show_image . '" class="MagazineListImg" title = "' . $imagetitle . '" alt = "' . $imagealt . '"></a><div class="MagazinesList-1"><h4>' . $display_title . '
		</h4>';
					if($is_summary_required == 1)
					{
						$show_simple_tab .= '<p class="summary">' . $summary . '</p>';
					}
					$show_simple_tab .= '</div></div></div>';
				}
				
				else if($i == 2)
				{
					$show_simple_tab .= '<div class="MagazinesListRight">';
					$show_simple_tab .= '<div class="Row"> <div class="MagazinesList-1"><h4>' . $display_title . '
		</h4>';
					if($is_summary_required == 1)
					{
						$show_simple_tab .= '<p class="summary">' . $summary . '</p>';
					}
					$show_simple_tab .= '</div><a  href="' . $live_article_url . '" class="article_click"  ><img src="' . $dummy_image . '" data-src="' . $show_image . '" class="MagazineListImg" title = "' . $imagetitle . '" alt = "' . $imagealt . '"></a></div>';
					$show_simple_tab .= '</div>';
					$show_simple_tab .= '</div>';
				}
			}
			
			else if($i == 3 or $i == 4)
			{
				if($i == 3)
				{
					$show_simple_tab .= '<div class="MagazinesList">';
					$show_simple_tab .= '<div class="MagazinesListLeft">';
					$show_simple_tab .= '<div class="Row"> <div class="MagazinesList-1"><h4>' . $display_title . '
			</h4>';
					if($is_summary_required == 1)
					{
						$show_simple_tab .= '<p class="summary">' . $summary . '</p>';
					}
					$show_simple_tab .= '</div><a  href="' . $live_article_url . '" class="article_click"  ><img src="' . $dummy_image . '" data-src="' . $show_image . '" class="MagazineListImg" title = "' . $imagetitle . '" alt = "' . $imagealt . '"></a></div>';
					$show_simple_tab .= '</div>';
				}
				
				else if($i == 4)
				{
					$show_simple_tab .= '<div class="MagazinesListRight">';
					$show_simple_tab .= '<div class="Row"><a  href="' . $live_article_url . '" class="article_click"  ><img src="' . $dummy_image . '" data-src="' . $show_image . '" class="MagazineListImg" title = "' . $imagetitle . '" alt = "' . $imagealt . '"></a><div class="MagazinesList-1"><h4> ' . $display_title . '
			</h4>';
					if($is_summary_required == 1)
					{
						$show_simple_tab .= '<p class="summary">' . $summary . '</p>';
					}
					
					$show_simple_tab .= '</div></div>';
					$show_simple_tab .= '</div>';
					$show_simple_tab .= '</div>';
				}
				
			}
			
			else if($i >= 5)
			{
				if($i == 5)
				{
					$show_simple_tab .= '<div class="MagazinesList">';
					$show_simple_tab .= '<div class="MagazinesListLeft">';
					$show_simple_tab .= '<div class="Row"> <a  href="' . $live_article_url . '" class="article_click"  ><img src="' . $dummy_image . '" data-src="' . $show_image . '" class="MagazineListImg" title = "' . $imagetitle . '" alt = "' . $imagealt . '"></a><div class="MagazinesList-1"><h4>' . $display_title . '
		</h4>';
					if($is_summary_required == 1)
					{
						$show_simple_tab .= '<p class="summary">' . $summary . '</p>';
					}
					$show_simple_tab .= '</div></div></div>';
				}
				
				else if($i == 6)
				{
					$show_simple_tab .= '<div class="MagazinesListRight">';
					$show_simple_tab .= '<div class="Row"> <div class="MagazinesList-1"><h4>' . $display_title . '
		</h4>';
					if($is_summary_required == 1)
					{
						$show_simple_tab .= '<p class="summary">' . $summary . '</p>';
					}
					$show_simple_tab .= '</div><a  href="' . $live_article_url . '" class="article_click"  ><img src="' . $dummy_image . '" data-src="' . $show_image . '" class="MagazineListImg" title = "' . $imagetitle . '" alt = "' . $imagealt . '"></a></div></div>';
					$show_simple_tab .= '</div>';
				}
			}
			
			if(($TotalCount < $article_count) && ($i == count($widget_contents)) || ($last_content_id == $get_content['content_id']))
			{
				$archive .= '<a class="load_more_archive" href="' . $load_more_url . '">More</a>';
			}
			
			if($j == count($widget_contents))
			{
				if($i == 1 || $i == 3 || $i == 5)
				{
					$show_simple_tab .= '</div>';
				}
			}
			
			// display title and summary block ends here					
			//Widget design code block 1 starts here																
			//Widget design code block 1 starts here			
			$i = $i + 1;
			$j++;
			if($i > 6)
			{
				$i = 1;
			}
		}
	}
}
/*elseif($view_mode == "adminview")
{
$show_simple_tab .= '<div class="margin-bottom-10">' . no_articles . '</div>';
}*/
// content list iteration block ends here
// Adding content Block ends here
$show_simple_tab .= '</div></div>';
echo $show_simple_tab;

echo '<div class="pagina">';
echo $PaginationLink;
echo $archive . '</div>';
?>
