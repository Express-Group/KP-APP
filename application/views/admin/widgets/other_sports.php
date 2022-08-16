<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid = $content['sectionID'];
$main_sction_id 	= "";
if($widget_custom_title == "")
$widget_custom_title = "Other Sports";
// widget config block ends
//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .=' <div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
													
													$url_structure = $content['url_structure'];
											  		$section_landing =  "0,".$content['sectionID'].", 'section', this, '".$url_structure."'";
													if($content['widget_title_link'] == 1)
													{
														$show_simple_tab.=	'<h4 class="SportsTitle"><div class="right_arrow"></div><a href="#" onclick="call_url('.$section_landing.')">'.$widget_custom_title.'</a></h4>';
													}
													else
													{
														$show_simple_tab.='<h4 class="SportsTitle"><div class="right_arrow"></div><a href="#">'.$widget_custom_title.'</a></h4>';
													}
													$show_simple_tab.= '<div class=" OtherSports">';
				
													
													$j = 0;
													// Adding content Block starts here
													foreach($widget_instancemainsection as $get_section)
													{													
														//getting content block - getting content list based on rendering mode
														//getting content block starts here . Do not change anything
														if($content['RenderingMode'] == "manual")
														{
															if($content['mode'] == "temporary")
															{
																$widget_instance_contents 	= $this->widget_model->get_WidgetInstancearticles_temporary_rendering($get_section['WidgetInstance_id'], $get_section['WidgetInstanceMainSection_id']); 
															}
															else
															{
																$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($get_section['WidgetInstance_id'], $get_section['WidgetInstanceMainSection_id']); 																
															}
														}
														else
														{
															$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($content['show_max_article'], $widget_instancemainsection[$j]['Section_ID']); 
														}
														//print_r($widget_instance_contents);exit;
														//getting content block ends here
														//Widget code block - code required for simple tab structure creation. Do not delete
														//Widget code block Starts here
													
														// content list iteration block - Looping through content list and adding it the list
														// content list iteration block starts here
														$i =1;
														$count = 1;
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
																	
																	$image_data = $this->widget_model->get_image_data_widget($get_content['content_id'],$is_home);
																	//$imagecaption = $this->template_design_model->required_widget_content_by_id($image_data['contentversion_id'], '2');	
																	  
																	  
																	  $Image600X390  = str_replace("original","w600X390", @$image_data['ImagePhysicalPath']);
																	

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
																	
																	$custom_title = '<a href="javascript:;" class="article_click"  >'.$custom_title.'</a>';
																	$display_title = $custom_title;
																}
																else
																{
																		$article_title = str_replace('<p','<span',$from_contents_table[0]['Title']);
																		$article_title = str_replace('</p>','</span>',$article_title);
																		
																	$custom_title = '<a href="javascript:;" class="article_click"  >'.$article_title.'</a>';
																	$display_title = $article_title;
																		
																}
															//  Assign article links block ends hers
															
															// Assign summary block - creating links for  article summary
																// Assign summary block starts here
																$custom_summary = $get_content['CustomSummary'];
																if( $custom_summary != '')
																{
																	$custom_summary = $custom_summary;
																}
																else
																{
																	$custom_summary = $from_contents_table[0]['SummaryPlaintext'];
																}
																// Assign summary block starts here
																
																	////////  For first Article  /////////
															if($count <= 3)
															{
																if($count==1)
																{
															   		$show_simple_tab.= '<div class="WidthFloat_L">'; 
															    } 
																	$show_simple_tab.= '<p class="OtherSports_1"><i class="fa fa-angle-right"></i> <a href="#">'.$display_title.'</a></p>';
															 
															  if(($count==3)||  $i == count($widget_instance_contents))
															   { 
																 	$show_simple_tab.=  '</div>';
																 	$count=0;
																} 
															
															$count ++;	
															}
																							
																// display title and summary block ends here					
																//Widget design code block 1 starts here																
															//Widget design code block 1 starts here			
															$i =$i+1;							  
														}
														 
														// content list iteration block ends here
														$j++;
													}
													//$show_simple_tab .='</div>';
													
													// Adding content Block ends here
													$show_simple_tab .='</div></div></div>';
echo $show_simple_tab;
?>
