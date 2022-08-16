<?php 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
if($widget_custom_title == "")
{
$widget_custom_title = "Gallery";
}

// widget config block ends
$domain_name         =  base_url();
$show_simple_tab     = "";
$show_simple_tab    .= '<div class="row">
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                       <div class="Entertainment " '.$widget_bg_color.'>';
                
$show_simple_tab    .= '<fieldset class="FieldTopic"> <legend class="topic">';
if($content['widget_title_link'] == 1)
{
$show_simple_tab.=	'<a href="'.$widget_section_url.'"  >'.$widget_custom_title.'</a>';
}
else
{
$show_simple_tab.=	$widget_custom_title;
}
		$show_simple_tab.=	'</legend></fieldset>';	                                    												
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual")
{
$content_type = $content['content_type_id'];
$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article); 	
			     $get_content_ids = array_column($widget_instance_contents, 'content_id'); 
		         $get_content_ids = implode("," ,$get_content_ids);
				 $widget_contents = array();
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
														
	}
					
}
else
{
$content_type = $content['content_type_id'];  // auto article content type
$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'], $content_type ,  $view_mode, $is_home);		
}

$i =1;
$k = 1;	
if(count($widget_contents)>0)
{
	foreach($widget_contents as $get_content)
	{
	// Code Block B - if rendering mode is manual then if custom image is available then assigning the imageid to a variable
	// Code Block B starts here - Do not change
	
	// Assign block ends here
	// Assign article links block - creating links for  article summary Display article																
	
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
		if($original_image_path =="")                         // from cms imagemaster table    
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

	if ($imageheight > $imagewidth)
	{
		$Image600X390 	= $original_image_path;
	}
	else
	{
		 $Image600X390  = str_replace("original","w600X390", $original_image_path);
	}
	
	if ($Image600X390 != '' && get_image_source($Image600X390, 1))
	{
	$show_image = image_url. imagelibrary_image_path . $Image600X390;
	}
	
	else {
	$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
	}
	$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
	}
	else
	{
	$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
	$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
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
	
	//  Assign article links block ends hers
	$play_gallery_image = image_url. imagelibrary_image_path.'gallery-icon.png';
	
	if($i<=4)
	{
		if($i==1){
		$show_simple_tab .= '<div class="feature" id="entertainment_gallery_'.$widget_instance_id.'">
							 <div class="slide Enter_Gallery EnterLeadStyle">';
		}
		$show_simple_tab .= '<div class="item">';
		$show_simple_tab .='<a  href="'.$live_article_url.'" class="article_click" >
		
		<figure class="PositionRelative">
		<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">
		<img src="'.$play_gallery_image.'" class="GalleryListing">
		</figure>
		
		</a>';
		$show_simple_tab .='<div class="Entergallery_Caption"> <p>'.$display_title.'</p></div>';
		
		$show_simple_tab.='</div>';
		if($i==4 || $i == count($widget_contents))
		{
		$show_simple_tab.=' </div></div>';
		
		}
	}
	else 
	{
			if($i==5)
			{
			$show_simple_tab .= '<div class="EnterGallery">'; 
			}
		if($k<=8)
		{
			if($k==5)
			{
			$show_simple_tab.='<div class="WidthFloat_L">';
			}
		$show_simple_tab.='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 EnterGallery_1">
		<a  href="'.$live_article_url.'"  class="article_click" >
		<figure class="PositionRelative">
		<img src="'.$show_image.'"  title = "'.$imagetitle.'" alt = "'.$imagealt.'"> <img src="'.$play_gallery_image.'" class="GalleryListing">
		</figure>
		<p>'.$display_title.'
		</a></p></div>';
			if($k==8 || $i == count($widget_contents))
			{ 
			$k=4;
			$show_simple_tab.='</div>';
			}
		}
		if($i == count($widget_contents))
		{
		$show_simple_tab.='</div>';
		}
		
	}
	//display title and summary block ends here					
	//Widget design code block 1 starts here																
	//Widget design code block 1 starts here			
	$k= $k+1;
	$i =$i+1;							  
	}
																											// content list iteration block ends here
}
 elseif($view_mode=="adminview") 
{
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
	     
	if($content['widget_title_link'] == 1)
{
$show_simple_tab .=' <div class="arrow_right"><a href="'.$widget_section_url.'" class="landing-arrow">arrow</a></div>';
}
		 $show_simple_tab .='
          </div></div></div>';
echo $show_simple_tab;
?>
