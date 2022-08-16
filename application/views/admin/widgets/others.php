<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
// widget config block ends
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name     =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row" '.$widget_bg_color.'>
					 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="other feature" id="others_'.$widget_instance_id.'">';
										$show_simple_tab .= ' <fieldset class="FieldTopic">';
				
				if($content['widget_title_link'] == 1)
				{
					$show_simple_tab.=	'<legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend>';
				}
				else
				{
					$show_simple_tab.=	'<legend class="topic">'.$widget_custom_title.'</legend>';
				}
				$show_simple_tab.= ' </fieldset>';
				// Code Block A ends here
				
																	
				$show_simple_tab .= ' <div class="slide others_slider others_slider_change">';
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
						$custom_title        = "";
						$original_image_path = "";
						$imagealt            = "";
						$imagetitle          = "";
						$Image600X390        = "";
						if($render_mode == "manual")
						{
							if($get_content['custom_image_path'] != '')
							{
								$original_image_path = $get_content['custom_image_path'];
								$imagealt            = $get_content['custom_image_title'];	
								$imagetitle          = $get_content['custom_image_alt'];												
							}
							$custom_title            = stripslashes($get_content['CustomTitle']);
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
							$url_array = explode('/', $content_url);
							$get_seperation_count = count($url_array)-4;
							
							$sectionURL = ($get_seperation_count==1)? $domain_name.$url_array[0] : (($get_seperation_count==2)? $domain_name.$url_array[0]."/".$url_array[1] : $domain_name.$url_array[0]."/".$url_array[1]."/".$url_array[2]);
							
							$param = $content['close_param']; //page parameter
							$live_article_url = $domain_name. $content_url.$param;
							$section_name_split = explode('/', $sectionURL);
							$section_name  = join(" ", explode("-", end($section_name_split)));
						
							if( $custom_title == '')
							{
								$custom_title = stripslashes($get_content['title']);
							}	
							$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);
						 	$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
					     	//  Assign article links block ends hers
							
							 $show_simple_tab .= '<div class="other1"><a  href="'.$live_article_url.'">';
							 
							 $show_simple_tab .= '<img data-lazy="'.$show_image.'" src="'.$dummy_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">';
							 
							 $show_simple_tab .= '</a>';
							 $show_simple_tab .= '<h5 class="subtopic"><a  href="'.$sectionURL.'">'.ucfirst($section_name).'</a></h5>';				
									
										 $show_simple_tab .='<p>'.$display_title.'</p>';
									
									 $show_simple_tab .= '<span class="align_center">
									 <a href="'.$sectionURL.'"><i class="fa fa-arrow-right"></i></a></span> </div>';
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
 
 $show_simple_tab .='</div></div></div></div>';
echo $show_simple_tab;
?>
