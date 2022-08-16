<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
// widget config block ends
$domain_name         =  base_url();
$show_simple_tab     = "";
if($widget_custom_title == "")
{
$widget_custom_title = "Food";
}
$show_simple_tab    .='<div class="row">
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10">
                       <div class="LifeStyles_2" '.$widget_bg_color.'>
                       <fieldset class="FieldTopic">';
if($content['widget_title_link'] == 1)
{
$show_simple_tab.=	' <legend class="topic"><a href="'.$widget_section_url.'" >'.$widget_custom_title.'</a></legend>';
}
else
{
$show_simple_tab.=	' <legend class="topic">'.$widget_custom_title.'</legend>';
}
$show_simple_tab.= '</fieldset>';
// Adding content Block starts here			
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($content['RenderingMode'] == "manual")
{
$content_type = $content['content_type_id'];  // manual article content type
$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], $max_article); 
	$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
	$get_content_ids = implode("," ,$get_content_ids); 
$widget_contents = array();
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
$content_type = $content['content_type_id'];  // auto article content type
$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
}
$i =1;
if(count($widget_contents)>0)
		     {			
	foreach($widget_contents as $get_content)
				{	
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
		if($original_image_path =="")              // from cms || live table    
		{
		$original_image_path  = $get_content['ImagePhysicalPath'];
		$imagealt             = $get_content['ImageCaption'];	
		$imagetitle           = $get_content['ImageAlt'];	
		}
		$show_image="";
		if($original_image_path !='' && get_image_source($original_image_path, 1))
		{
			$imagedetails = get_image_source($original_image_path, 2);
				$imagewidth = $imagedetails[0];
				$imageheight = $imagedetails[1];
			if($i<=3)
			{
				if ($imageheight > $imagewidth)
				{
					$Image600X300 	= $original_image_path;
				}
				else
				{
			        $Image600X300  = str_replace("original","w600X300", $original_image_path);
				}
				if (get_image_source($Image600X300, 1) && $Image600X300 != '')
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
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
		}
		else
		{
			if($i<=3)
			{
			$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			}
			else
			{
			$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
		}
		$content_url = $get_content['url'];
		$param = $content['close_param'];
		$live_article_url = $domain_name.$content_url.$param;
		if( $custom_title == '')
		{
		$custom_title = $get_content['title'];
		}	
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
		$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
		//  Assign article links block ends hers
		// Assign summary block - creating links for  article summary
		// Assign summary block starts here
		if( $custom_summary == '' && $render_mode=="auto")
		{
		$custom_summary =  $get_content['summary_html'];
		}
		$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);  //to remove first<p> and last</p>  tag
		// Assign summary block starts here
			if($i <= 3) 
			{
				if($i == 1) 
				{
				$show_simple_tab.=	'<div class="LifeStylesRight">';
				}
				
				$show_simple_tab.=	' <div class="WidthFloat_L"> <div class="BgBlue"><h2 class="subtopic">'.$display_title.'</h2>';
			
				if($is_summary_required== 1)
				{
				$show_simple_tab.='<p class="summary">'.$summary.'</p>';
				}
				$show_simple_tab.='</div>     
				<div class="LifeStylesRightImg"><a  href="'.$live_article_url.'"  class="article_click" >
				<img src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a> 
				</div></div>';
				if($i == 3) 
				{
				$show_simple_tab.=	'</div>';
				}
				
				if(count($widget_contents)== $i && $i<3)
				{  
				$show_simple_tab.=	'</div>';
				}
				
				
			} 
			else 
			{
				$show_simple_tab.=	'<div class="LifeStylesLeft"> <figure><a  href="'.$live_article_url.'"  class="article_click" >
				<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a></figure>';
				$show_simple_tab.=	'<div class="BgBlue"><h2 class="subtopic">'.$display_title.'</h2>';
				if($is_summary_required== 1)
				{
				$show_simple_tab.='<p class="summary">'.$summary.'</p>';
				}
				$show_simple_tab.='</div></div>';
			}
		// display title and summary block ends here					
		//Widget design code block 1 starts here																
		//Widget design code block 1 starts here			
		$i =$i+1;							  
		}
      }
 elseif($view_mode=="adminview")
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
		// content list iteration block ends here
		// Adding content Block ends here
$show_simple_tab .='</div>';
if($content['widget_title_link'] == 1)
{
$show_simple_tab .=' <div class="arrow_right"><a  href="'.$widget_section_url.'"  class="landing-arrow">arrow</a></div>';
}
$show_simple_tab .='</div></div>';
echo $show_simple_tab;
?>
