<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$view_mode           = $content['mode'];
$render_mode         = $content['RenderingMode'];
$max_article            = ($content['show_max_article'] != 'undefined' ) ? $content['show_max_article'] : 5 ;
if($widget_custom_title == "")
{
$widget_custom_title = "Video";
}
// widget config block ends
$domain_name         =  base_url();
$show_simple_tab     = "";
$show_simple_tab    .='<div class="row">
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                       <div class="Entertainment Enter_Video" '.$widget_bg_color.'>';
                
 $show_simple_tab   .= '<fieldset class="FieldTopic"> <legend class="topic">';
					   														
if($content['widget_title_link'] == 1)
{
$show_simple_tab.=	'<a href="'.$widget_section_url.'"   >'.$widget_custom_title.'</a>';
}
else
{
$show_simple_tab.=	$widget_custom_title;
}

$show_simple_tab.=	'</fieldset> '; 

//$show_simple_tab.='<div class="row">';

$content_type = $content['content_type_id'];  // auto article content type
$widget_contents = array();

//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual")
{

$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], $max_article); 	

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
	$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $view_mode, $is_home);

}
/*
					if (function_exists('array_column')) 
					{
				    $get_content_ids = array_column($widget_instance_contents, 'content_id'); 
					}else
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
if(count($widget_contents)>0)
{
	foreach($widget_contents as $get_content)
	{
	$custom_title        = "";
	$original_image_path = "";
	$imagealt            = "";
	$imagetitle          = "";
	$Image600X390        = "";
	if($render_mode == "manual")            // from widgetinstancecontent table    
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
	$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
	$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
	
	// Assign summary block starts here
	
	$play_video_image = image_url. imagelibrary_image_path.'play-circle.png';
	if($i==1)
	
	{
		$show_simple_tab.='<div class="row">';
	$show_simple_tab .= '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 EnterGalleryPadding">
	
	<figure>
	<a  href="'.$live_article_url.'" class="article_click" >
	<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"> 
	<img src="'.$play_video_image .'" class="GalleryIcon Enter-video-Icon ">
	 </a>
	 </figure>
	 <p class="EnterVideoColor">'.$display_title.'</p> 
	
	</div>';
	
	
	}	
	else
	{
			if($i==2)
			{
			$show_simple_tab .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">  ';  
			                    
			$show_simple_tab .= ' <div class="EnterVerticalSlide">
			                      <div id="carousel-example-vertical" class="carousel vertical slide">';
			$show_simple_tab .= '<div class="carousel-inner" role="listbox">';
			}
				if ($i==2 )
				{
				$show_simple_tab .= '<div class="item active">';
				
				}
					else
					{
					$result = fmod($i,2);
						//if($i==6){echo $i;exit;}																	
						if ($result == 0)
						{ 
						$show_simple_tab .= '<div class="item"> ';
						}
					}
		$show_simple_tab .= '<a  href="'.$live_article_url.'" class="article_click" >
		<figure class="PositionRelative">
		<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">
		<img src="'.$play_video_image.'" class="GalleryListing">
		</figure>
		<p>'.$display_title.'
		</p></a>';	
		
		$result1 = fmod($i,2);
				if($result1 == 1 || $i == count($widget_contents))
				{
				$show_simple_tab .= '</div>';
				}
		}	
	if($i == count($widget_contents))
	{
		if($i==1 )
		{
			
			$show_simple_tab .=' </div>';
		}
		else
		{  
		    if($i==2)
			{
				$show_simple_tab .='</div>';
				                    
									
									$show_simple_tab .=' </div></div></div></div>';
				
			}
				if ($i==2)
					{
					//$show_simple_tab .= '</div>';
					}
					else
					{
					$result = fmod($i,2);
																						
						if ($result == 0)
						{
							$show_simple_tab .='</div>';
												$show_simple_tab .=' 
												<a class="up carousel-control fa fa-caret-up" title="Next" href="#carousel-example-vertical" role="button" data-slide="prev">
												<span class="sr-only">Previous</span>
												</a>
												<a class="down carousel-control fa fa-caret-down" title="Prev" href="#carousel-example-vertical" role="button" data-slide="next">
												<span class="sr-only">Next</span>
												</a>';
												$show_simple_tab .=' </div></div></div>';
						$show_simple_tab .= '</div> ';
						}
						if($i%2==1)
						{
							
							if($i==3)
							{
								$show_simple_tab .='</div>';
											
												$show_simple_tab .=' </div></div></div></div>';
								
							}else{
								$show_simple_tab .='</div>
												<a class="up carousel-control fa fa-caret-up" title="Next" href="#carousel-example-vertical" role="button" data-slide="prev">
												<span class="sr-only">Previous</span>
												</a>
												<a class="down carousel-control fa fa-caret-down" title="Prev" href="#carousel-example-vertical" role="button" data-slide="next">
												<span class="sr-only">Next</span>
												</a>
												</div></div></div></div>';
							}
							
							
						}
					}
           }
	  }
	//echo $i;
	$i =$i+1;							  
	}
 //}
// content list iteration block ends here
}
else
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
$show_simple_tab .='</div></div></div>';																			  
echo $show_simple_tab;
$js_path 		= base_url()."js/FrontEnd/";

?>
<script src="<?php echo $js_path; ?>js/vertical-list-slider.js" type="text/javascript"></script>
