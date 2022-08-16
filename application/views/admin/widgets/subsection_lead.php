<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = $content['page_param'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$section_widgetID    = $content['widget_values']['data-widgetid'];  // current widget id
$content_type        = $content['content_type_id'];  // auto article content type
$widget_section_url  = $content['widget_section_url'];
$max_article          = $content['show_max_article'];
$render_mode          = $content['RenderingMode'];

// widget config block ends
//getting tab list for hte widget
if($render_mode == "manual")
{
$content_type = $content['content_type_id'];  // manual article content type
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article);
	// changed newly on 10-09-2016
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
//	$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type, $view_mode);	
$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
}
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="city_lead" '.$widget_bg_color.'>';

//$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
//$get_content_ids = implode("," ,$get_content_ids);
	//echo '<pre>'; echo $get_content_ids; die();	
//if($get_content_ids!='')
//{
	/*
	$content_type = $content['content_type_id'];
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
	$widget_contents = array();
	//echo '<pre>'; print_r($widget_instance_contents1); die();	
	foreach ($widget_instance_contents as $key => $value) {
		foreach ($widget_instance_contents1 as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
				$widget_contents[] = array_merge($value, $value1);
			}
		}
	}
	*/
				// content list iteration block - Looping through content list and adding it the list
				// content list iteration block starts here
				
				$i =1;
	if(count($widget_contents)>0)
	{
				$i =1;
				foreach($widget_contents as $get_content)
				{
					$original_image_path = "";
					$imagealt            = "";
					$imagetitle          = "";
					$custom_title        = "";
					$custom_summary      = "";
					$Image600X390        = "";
					if($render_mode == "manual")
					{
						if($get_content['custom_image_path'] != '')
						{
							$original_image_path = $get_content['custom_image_path'];
							$imagealt            = $get_content['custom_image_title'];	
							$imagetitle          = $get_content['custom_image_alt'];												
						}
						$custom_title   = $get_content['CustomTitle'];
					}
					if($original_image_path =="")                                                // from cms imagemaster table    
					{
					   $original_image_path  = $get_content['ImagePhysicalPath'];
					   $imagealt             = $get_content['ImageCaption'];	
					   $imagetitle           = $get_content['ImageAlt'];	
					}
				
					$show_image="";
					if($original_image_path !=''  && get_image_source($original_image_path, 1))
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
						$param       = $content['close_param'];
						$live_article_url = $domain_name.$content_url.$param;
						
						if( $custom_title == '')
						{
							$custom_title = $get_content['title'];
						}	
						$lastpublishedon = $get_content['last_updated_on'];
						$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
						$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
					//  Assign article links block ends hers
					
						$time = '';															
						if($i == 1) {
						
						$show_simple_tab.=	' <div class="city_lead1"> <a   href="'.$live_article_url.'"  class="article_click" ><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a><h4 class="subtopic">'.$display_title.'</h4>
						</div>';
					  
						} else {
							if($i == 2) 
								$show_simple_tab.=	'<div class="city_lead2 common_p">';
							
							$show_simple_tab.=	'<p> <i class="fa fa-angle-right"></i> '.$display_title.'</p>';
							$time = $lastpublishedon; 
							$post_time= $this->widget_model->time2string($time); 
							$show_simple_tab.=	'<p class="post_time br_bottom">'.$post_time.' </p>';
							
							 
							 if($i == count($widget_contents))
								$show_simple_tab.=	'</div>'; 
						}
						
						// display title and summary block ends here					
						//Widget design code block 1 starts here																
					//Widget design code block 1 starts here			
					$i =$i+1;							  
				}
				
				// content list iteration block ends here
		}
		 elseif($view_mode=="adminview")
		{
			$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
		}
			$show_simple_tab .='</div>
          </div>
          </div>';
																			  

																			  
echo $show_simple_tab;
?>
