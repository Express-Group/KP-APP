<?php
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color            = $content['widget_bg_color'];
$widget_custom_title        = $content['widget_title'];
$widget_instance_id         = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id             = "";
$widget_section_url         = $content['widget_section_url'];
$is_home                    = $content['is_home_page'];
$is_summary_required        = $content['widget_values']['cdata-showSummary'];
$view_mode                  = $content['mode']; 
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];
// widget config block ends
//getting tab list for hte widget

// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name     = base_url();
$show_simple_tab = "";
$show_simple_tab .= '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10"><div class="AskPrabu777" ' . $widget_bg_color . '>';

if($content['widget_title_link'] == 1)
{
	$show_simple_tab .= ' <h2 class="PrabhuPower"><a href="' . $widget_section_url . '">' . $widget_custom_title . '</a></h2>';
}
else
{
	$show_simple_tab .= ' <h2 class="PrabhuPower">' . $widget_custom_title . '</h2>';
}


$show_simple_tab .= '<div class="PrabhuPowerList" ' . $widget_bg_color . '><div>';

$j = 0;
// Adding content Block starts here

$content_type             	= $content['content_type_id']; // manual article content type
$widget_contents 			=array();	
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual")
{	
	$widget_instance_contents = $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $content['mode'], $max_article);
	
	$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
	$get_content_ids = implode("," ,$get_content_ids); 

	if($get_content_ids!='')
	{
		$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
		foreach ($widget_instance_contents as $key => $value) {
			foreach ($widget_instance_contents1 as $key1 => $value1) {
				if($value['content_id']==$value1['content_id']){
					$widget_contents[] = array_merge($value, $value1);
				}
			}
		}
	}
}
else
{
	//$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'], $content_type, $content['mode']);
	$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $view_mode, $is_home);
}
//getting content block ends here
/*
// content list iteration block - Looping through content list and adding it the list
// content list iteration block starts here
if(function_exists('array_column'))
{
	$get_content_ids = array_column($widget_instance_contents, 'content_id');
}
else
{
	$get_content_ids = array_map(function($element)	{ return $element['content_id']; }, $widget_instance_contents);
}
$get_content_ids = implode(",", $get_content_ids);

if($get_content_ids != '')
{
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);
	$widget_contents           = array();
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
	*/
	$i     = 1;
	$count = 1;
	if(count($widget_contents) > 0)
	{
		//print_r($widget_contents); exit;
		foreach($widget_contents as $get_content)
		{
			
			
			$custom_title        = "";
			$custom_summary      = "";
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$Image600X300        = "";
			if($render_mode == "manual")
			{
				$custom_title   = stripslashes($get_content['CustomTitle']);
				$custom_summary = $get_content['CustomSummary'];
				
			}
			
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
			
			
			if($custom_summary == '' && $render_mode=="auto")
			{
				$custom_summary = $get_content['summary_html'];
			}
			$summary = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_summary); //to remove first<p> and last</p>  tag
			// Assign summary block starts here
			
			//  Assign article links block ends hers																																																					
			$show_simple_tab .= '<div class="PrabhuPowerList-1"><p>' . $display_title . '</p><p>' . date("d-m-Y", strtotime($get_content['last_updated_on'])) . '</p><p>' . $summary . '</p></div>';
			$i = $i + 1;
			//$show_simple_tab .='</li>';
			
		}
	//}
	
	// content list iteration block ends here
	$j++;
	
	$show_simple_tab .= '</div>';
	// Adding content Block ends here													
	if($content['widget_title_link'] == 1)
	{
		$show_simple_tab .= '<div class="arrow_right"><a href="' . $widget_section_url . '"  class="landing-arrow"></a></div>';
	}
}
 elseif($view_mode=="adminview")
{
	$show_simple_tab .= '<div class="margin-bottom-10">' . no_articles . '</div></div>';
}
$show_simple_tab .= '</div>'; ///// closing <div class="PrabhuPowerList"> //////

$show_simple_tab .= '</div></div></div>';



echo $show_simple_tab;
?>
