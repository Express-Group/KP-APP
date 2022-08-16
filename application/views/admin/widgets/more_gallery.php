<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
// widget config block ends
$domain_name         =  base_url();
$show_simple_tab     = "";
$show_simple_tab    .='<div class="row">
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10">
                       <fieldset class="FieldTopic">';
					   	
if($content['widget_title_link'] == 1)
{
$show_simple_tab.=	' <legend class="topic"><a href="'.$widget_section_url.'" >'.$widget_custom_title.'</a></legend>';
}
else
{
$show_simple_tab.=	' <legend class="topic">'.$widget_custom_title.'</legend>';
}
$show_simple_tab .= '</fieldset>';

$show_simple_tab .='<div class="most" '.$widget_bg_color.'>';

//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
$content_type = $content['content_type_id'];           
$widget_contents = array();
	
if($render_mode == "manual")
{

	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article); 	

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
	//$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'], $content_type ,  $view_mode);
	$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
		
}

/*
if (function_exists('array_column')) 
				{
			$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
				}
				else
				{
			$get_content_ids = array_map( function($element) { return $element['content_id']; }, $widget_instance_contents);
				}
		$get_content_ids = implode("," ,$get_content_ids);

if($get_content_ids!='')
	{
		$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
		$widget_contents = array();
			foreach ($widget_instance_contents as $key => $value) 
			{
				foreach ($widget_instance_contents1 as $key1 => $value1)
				 {
					if($value['content_id']==$value1['content_id'])
					{
					   $widget_contents[] = array_merge($value, $value1);
					}
				 }
			}
*/
$i =1;
$count = 1;
if(count($widget_contents)>0)
{
	foreach($widget_contents as $get_content)
	{
		
		$custom_title        = "";
		$original_image_path = "";
		$imagealt            = "";
		$imagetitle          = "";
		$Image600X300        = "";
		$custom_title        = "";
		
		if($render_mode == "manual")
		{
			if($get_content['custom_image_path'] != '')
			{
			$original_image_path = $get_content['custom_image_path'];
			$imagealt            = $get_content['custom_image_title'];	
			$imagetitle          = $get_content['custom_image_alt'];												
			}
			$custom_title        = stripslashes($get_content['CustomTitle']);
		
		}	
		
		// Code block C - if rendering mode is auto then this code blocks retrieve required image from article related image if content type is article (This widget uses only article- Do not change
		// Code block C  starts here
		if($view_mode == "live")
		{
			if($original_image_path =='')
			{
			$original_image_path = $get_content['ImagePhysicalPath'];
			$imagealt            = $get_content['ImageAlt'];	
			$imagetitle          = $get_content['ImageCaption'];
			}
		}
		else
		{
			if($original_image_path =="")                         // from cms imagemaster table    
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
				$Image100X65 	= $original_image_path;
			}
			else
			{
				$Image100X65  = str_replace("original","w100X65", $original_image_path);
			}
			
			if (get_image_source($Image100X65, 1) && $Image100X65 != '')
			{
			$show_image = image_url. imagelibrary_image_path . $Image100X65;
			}
			else {
			$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
			}
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
		}
		else
		{
		$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
		$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
		}		
		
		$content_url = $get_content['url'];
		$param = $content['close_param']; //page parameter
		$live_article_url = $domain_name. $content_url.$param;
		if( $custom_title == '')
		{
		$custom_title = stripslashes($get_content['title']);
		}	
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);
		$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
		$icon ='<img class="more_gv_icon" src="'.image_url. imagelibrary_image_path.'gallery-icon.png">';
			
		$show_simple_tab.= '<div class="most1">
		<a  href="'.$live_article_url.'" class="article_click"   ><figure class="PositionRelative">
		<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">'.$icon.'</figure>
		<p>'.$display_title.'</p></a>
		</div>';
		
		$i =$i+1;
		}
	// content list iteration block ends here
    //}
}
 elseif($view_mode=="adminview")
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
$show_simple_tab .='</div>';

$show_simple_tab .='</div></div>';
echo $show_simple_tab;
?>
