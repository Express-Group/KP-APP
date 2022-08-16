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
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                       <div class="GallerySection gallery-type2" '.$widget_bg_color.'>
					   <div class="WidthFloat_L">';
					   				
if($content['widget_title_link'] == 1)
{
$show_simple_tab.=	' <legend class="topic"><a href="'.$widget_section_url.'" >'.$widget_custom_title.'</a></legend>';
}
else
{
$show_simple_tab.=	' <legend class="topic">'.$widget_custom_title.'</legend>';
}
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

$i =1;
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
				if($i==1){
					$Image600X300  = str_replace("original","w600X390", $original_image_path);
				}else{
					$Image600X300  = str_replace("original","w600X300", $original_image_path);
				}
				
		    }
			if(get_image_source($Image600X300, 1) && $Image600X300 != '')
			{
			$show_image = image_url. imagelibrary_image_path . $Image600X300;
			}
			else 
			{	
				if($i==1){
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}else{
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
				}
				
			}
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			if($i==1){
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}else{
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			}
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
		// Assign summary block starts here
		
		if($i ==1)
		{
		//  Assign article links block ends hers
		$play_video_image = image_url. imagelibrary_image_path.'gallery-icon.png';
		
		$show_simple_tab .= '<div class="GallerySectionLeft">
		<figure class="PositionRelative">';
		$show_simple_tab .='<a  href="'.$live_article_url.'" class="article_click"   >';
		$show_simple_tab .='<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"  width="600" height="390">
		<img data-src="'.$play_video_image.'" src="'.image_url.'images/FrontEnd/images/social-icon-set/social_icon.png" class="GalleryIcon">
		</figure>
		<p class="subtopic" >'.$display_title .'</p></a>';
		if($is_summary_required== 1)
		{
		$show_simple_tab.='<p class="summary">'.$summary.'</p>';
		}
		$show_simple_tab .='</div>';				  
		
		}
		else
		{
				if($i == 2)
				{
				$show_simple_tab .= '<div class="GallerySectionRight">';
				}
			
			$show_simple_tab .= '<div class="GallerySectionRight1">
			
			<div>
			<figure class="PositionRelative">';
			$show_simple_tab .='<a   href="'.$live_article_url.'" class="article_click" >';
			$show_simple_tab .='<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"  width="600" height="300"><img data-src="'.$play_video_image.'" src="'.image_url.'images/FrontEnd/images/social-icon-set/social_icon.png" class="GalleryIcon">
			</figure>
			<p>'.$display_title .'</p></a>
			</div>			
			</div>';	
			
			
			
						  
		}
		
		if($i==count($widget_contents))
		{
			if($i>=2)
			{ 
				$show_simple_tab .='</div>';
			}
		}
		$i =$i+1;
		
   }
 //}
}
 elseif($view_mode=="adminview")
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
/*if($i >2)
{
$show_simple_tab .='</div>';
}*/
// content list iteration block ends here
// Adding content Block ends here
$show_simple_tab .='</div>';
$show_simple_tab .='</div>
</div>
</div>';
echo $show_simple_tab;
?>
 