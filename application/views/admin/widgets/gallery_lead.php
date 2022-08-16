<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color       = $content['widget_bg_color'];
$widget_custom_title   = $content['widget_title'];
$widget_instance_id    =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	   = "";
$widget_section_url    = $content['widget_section_url'];
$is_home               = $content['is_home_page'];
$is_summary_required   = $content['widget_values']['cdata-showSummary'];
$view_mode             = $content['mode'];
$max_article           = $content['show_max_article'];
$render_mode           = $content['RenderingMode'];

// widget config block ends

$domain_name           =  base_url();
$is_home               = $content['is_home_page'];

if($widget_custom_title == "")
	$widget_custom_title = "Gallery";
	
$show_simple_tab       = "";
$show_simple_tab      .='<div class="row">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
						 
$content_type = $content['content_type_id'];           
$widget_contents = array();              
					   	//getting content block - getting content list based on rendering mode
						//getting content block starts here . Do not change anything
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
	foreach( $widget_contents as $get_content)
	{
	$custom_title        = "";
	$original_image_path = "";
	$imagealt            = "";
	$imagetitle          = "";
	$Image600X300        = "";
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
		$custom_title            = stripslashes($get_content['CustomTitle']);
		$custom_summary = $get_content['CustomSummary'];
	}
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
	
	
	// Code block C - if rendering mode is auto then this code blocks retrieve required image from article related image if content type is article (This widget uses only article- Do not change
	// Code block C  starts here
	
	$show_image="";
	if($original_image_path !='' && get_image_source($original_image_path, 1))
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
			$Image600X300  = str_replace("original","w600X300", $original_image_path);
		}
	if(get_image_source($Image600X300, 1) && $Image600X300 != '')
	{
	$show_image = image_url. imagelibrary_image_path . $Image600X300;
	}
	else 
	{
	$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
	}
	$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
	}
	else
	{
	$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
	$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
	}
	// Code block C ends here
	
	// Assign block - assigning values required for opening the article in light box
	// Assign block starts here
	$content_url = $get_content['url'];
	$param = $content['close_param']; //page parameter
	$live_article_url = $domain_name. $content_url.$param;
	if( $custom_title == '')
	{
	$custom_title = stripslashes($get_content['title']);
	}	
	$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);
	$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
	// Assign summary block starts here
	if( $custom_summary == '' && $render_mode=="auto")
	{
	$custom_summary =  $get_content['summary_html'];
	}
	$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);  //to remove first<p> and last</p>  tag
	
	//  Assign article links block ends hers
	$play_gallery_image = image_url. imagelibrary_image_path.'gallery-icon.png';
	
	//echo '<br>sdfsf: <img data-src="'.$show_image.'"/>';
	if($i==1)
	{
	$show_simple_tab.= '<div class="GallerySlider features"><div class="slide GalleryLeadSlider">';
	}
	$show_simple_tab.= '<div class="item">
	<div class="GallerySliderLeft">
	<figure class="PositionRelative">
	<a  href="'.$live_article_url .'"  class="article_click" >
	<img data-lazy="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">
	<img src="'.$play_gallery_image.'" class="GalleryIcon"></a>
	</figure>
	</div>
	<div class="GallerySliderRight">
	<p><a  href="'.$live_article_url .'" class="article_click" >'.$display_title.'</a></p><p>'.$custom_summary.'
	</div>
	</div>';
	if($i==count($widget_contents))
	{
		$show_simple_tab .='</div></div>';
	}
	
	$i =$i+1;
	//$show_simple_tab .='</div>';
	}
	// content list iteration block ends here
	
//}

} elseif($view_mode=="adminview") {
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}	
	// Adding content Block ends here

$show_simple_tab .='</div></div>';
echo $show_simple_tab;
?>
