<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	= "";
$widget_section_url = $content['widget_section_url'];
// widget config block ends
//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
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
													
													
													$show_simple_tab .= '</fieldset>';
													
													$j = 0;
													// Adding content Block starts here
													foreach($widget_instancemainsection as $get_section)
													{													
														//getting content block - getting content list based on rendering mode
														//getting content block starts here . Do not change anything
														if($content['RenderingMode'] == "manual")
														{
																$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($get_section['WidgetInstance_id'], $get_section['WidgetInstanceMainSection_id']); 																
														}
														else
														{
															$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($content['show_max_article'], $widget_instancemainsection[$j]['Section_ID']); 
														}
														// content list iteration block starts here
														$i =1;
														
														foreach($widget_instance_contents as $get_content)
														{
															
															$show_image = "";
															// Code Block B - if rendering mode is manual then if custom image is available then assigning the image to a variable
															// Code Block B starts here - Do not change
															if($content['RenderingMode'] == "manual")
															{
																if($get_content['Image'] != '')
																{
																	$show_image = $get_content['Image'] ;
																}
															}
															// Code Block B ends here
															// getting content details from database - Do not change
															$from_contents_table = $this->template_design_model->required_widget_content_by_id($get_content['content_id'], '1');	
															$SourceURL = $content['widget_img_phy_path'];
															// Code block C - if rendering mode is auto then this code blocks retrieve required image from article related image if content type is article (This widget uses only article- Do not change
															// Code block C  starts here
																if($show_image =='')
																{
																	$image_data = $this->widget_model->get_image_data($get_content['content_id']);

																	$Image600X390="";
																	 $imageheight = @$image_data['Height'];
																	 $imagewidth = @$image_data['Width'];
																	 if ($imageheight > $imagewidth)
																	 {
																	  $Image600X390  = @$image_data['ImagePhysicalPath'];
																	 }
																	
																	 else
																	 {    
																	  
																	  $Image600X390  = str_replace("original","w600X390", @$image_data['ImagePhysicalPath']);
																	 }
																	 
																	$imagealt ="";
																	$imagetitle="";																	
																	if (getimagesize(image_url_no . imagelibrary_image_path. $Image600X390) && $Image600X390 != '')
																	{
																		$show_image = image_url. imagelibrary_image_path . $Image600X390;
																		if (@$image_data['ImageAlt'] != '')
																		$imagealt = $image_data['ImageAlt'];
																		if(@$image_data['Title'] != '')
																		$imagetitle = $image_data['Title'];
																		
																	}

																	else {

																		$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
																	}

																}
																
																$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
																// Code block C ends here
																
																// Assign block - assigning values required for opening the article in light box
																// Assign block starts here
																
																
																$parent_section = $this->widget_model->get_parent_sectionmane($from_contents_table[0]['Section_id'])->row_array();	
																$parent_sectionname = "";
																if(count($parent_section)>0)
																{
																	$parent_sectionname = $parent_section['Sectionname'].'/';
																}
																
																$contentID = $get_content['content_id'];
																$section_ID = $from_contents_table[0]['Section_id'];
																$contentTypeID = @$get_content['content_type_id'];
																	
																// Assign block ends here
																// Assign article links block - creating links for  article summary Display article																$custom_title = $get_content['CustomTitle'];
																
																$string_value = $contentID.",".$section_ID.", 'article', this, '".$url_structure."'";
																$custom_title = $get_content['CustomTitle'];
																
																$custom_title = str_replace('<p','<span',$custom_title);
																$custom_title = str_replace('</p>','</span>',$custom_title);
																
																if( trim(strip_tags($custom_title)) != '')
																{
																	
																	$custom_title = '<a href="javascript:;" class="article_click" '.$custom_title.'</a>';
																	$display_title = $custom_title;
																}
																else
																{
																		$article_title = str_replace('<p','<span',$from_contents_table[0]['Title']);
																		$article_title = str_replace('</p>','</span>',$article_title);
																		
																	$custom_title = '<a href="javascript:;" class="article_click" '.$article_title.'</a>';
																	$display_title = $article_title;
																		
																}	
															//  Assign article links block ends hers
															
															// Assign summary block - creating links for  article summary
																// Assign summary block starts here
																$custom_summary = $get_content['CustomSummary'];
																if( $custom_summary != '')
																{
																	$custom_summary =  str_replace('<p','<span',$custom_summary);
																	$custom_summary =  str_replace('</p','</span',$custom_summary);
																}
																else
																{
																	$custom_summary =  str_replace('<p','<span',$from_contents_table[0]['SummaryPlaintext']);
																	$custom_summary =  str_replace('</p','</span',$from_contents_table[0]['SummaryPlaintext']);

																}
																// Assign summary block starts here
																
																if($i <= 3) {
																	if($i == 1) {
																		$show_simple_tab.=	'<div class="LifeStylesRight">';
																	}

																$show_simple_tab.=	' <div class="WidthFloat_L"><div class="BgBlue"><h2 class="subtopic"><a href="javascript:;" 		                                                               class="article_click" >'.$custom_title.'</h2><p class="summary">'.$custom_summary .'</p></div>      <div class="LifeStylesRightImg"><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a> 
                                                               
																</div></div>';
															  		if($i == 3) {
																		$show_simple_tab.=	'</div>';

																	}
															  
																} else {
																	
																		$show_simple_tab.=	'<div class="LifeStylesLeft"> <figure><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></figure>';
																	
																	 $show_simple_tab.=	'<div class="BgBlue"><h2 class="subtopic"><a href="javascript:;" class="article_click" ></a> '.$custom_title.'</h2><p class="summary">'.$custom_summary .'</p></div></div>';
																	 
																	// if($i == count($widget_instance_contents))
																		//$show_simple_tab.=	'</div>'; 
																		
																}
																
																// display title and summary block ends here					
																//Widget design code block 1 starts here																
															//Widget design code block 1 starts here			
															$i =$i+1;							  
														}
														 
														// content list iteration block ends here
														$j++;
													}
												
													// Adding content Block ends here
													
              $show_simple_tab .='</div>';
			  if($content['widget_title_link'] == 1)
													{
													$show_simple_tab .=' <div class="arrow_right"><a href="#" onclick="call_url('.$section_landing.')" class="landing-arrow">arrow</a></div>';
													}
           $show_simple_tab .='</div>
          </div>';
																			  
												
																			  
echo $show_simple_tab;
?>
