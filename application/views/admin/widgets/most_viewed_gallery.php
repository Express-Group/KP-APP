<?php
$widget_bg_color = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid = $content['sectionID'];
$main_sction_id 	= "";

//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
$most_viewed_video	= $this->widget_model->get_most_read_article('3');  ////  Parameter - content type - image -3, Video - 4

$show_simple_tab = "";

if($widget_custom_title == "")
	$widget_custom_title = "Most Popular Gallery";

$show_simple_tab .='<div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10">
            <fieldset class="FieldTopic">';
					   	
													$url_structure = $content['url_structure'];
													$section_landing =  "0,".$content['sectionID'].", 'section', this, '".$url_structure."'";		
													if($content['widget_title_link'] == 1)
													{
														$show_simple_tab.=	' <legend class="topic"><a href="#" onclick="call_url('.$section_landing.')"  >'.$widget_custom_title.'</a></legend>';
													}
													else
													{
														$show_simple_tab.=	' <legend class="topic">'.$widget_custom_title.'</legend>';
													}
													
													
													$show_simple_tab .= '</fieldset><div class="most">';
													
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
																$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($get_section['WidgetInstance_id'], $get_section['WidgetInstanceMainSection_id']); 
															}
															else
															{
																$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($get_section['WidgetInstance_id'], $get_section['WidgetInstanceMainSection_id']); 																
															}
														}
														else
														{
															//$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($content['show_max_article'], $widget_instancemainsection[$j]['Section_ID']); 
															$widget_instance_contents = $this->widget_model->get_all_available_gallery_auto($content['show_max_article'], $widget_instancemainsection[$j]['Section_ID']); 
														}
														
														$i =1;
														
														foreach($most_viewed_video as $get_content)
														{
															//$show_simple_tab .= '<li class="allvideo1">';
															$show_image = "";
															// Code Block B - if rendering mode is manual then if custom image is available then assigning the image to a variable
															// Code Block B starts here - Do not change
															/*if($content['RenderingMode'] == "manual")
															{
																if($get_content['Image'] != '')
																{
																	$show_image = $get_content['Image'] ;
																}
															}*/
															// Code Block B ends here
															// getting content details from database - Do not change
															$from_contents_table = $this->template_design_model->required_widget_content_by_id($get_content['content_id'], '3');	
															$SourceURL = $content['widget_img_phy_path'];
															// Code block C - if rendering mode is auto then this code blocks retrieve required image from article related image if content type is article (This widget uses only article- Do not change
															// Code block C  starts here
																if($show_image =='')
																{
																	$image_data = $this->widget_model->get_gallery_image_data($get_content['content_id']);

																	
																	$image_size = $this->widget_model->get_widget_image_size($widget_instance_id, $i);
																	
																	if(count($image_size) > 0 ) // if image size supported for each the content display order exist in DB
																	{
																			// assigning correct sized images
																			if($image_size[0]['image_width'] == 600 && $image_size[0]['image_height'] == 390)
																			{
																				$Image600X390 	= str_replace(".","_600_390.", @$image_data['ImagePhysicalPath']);
																				if (getimagesize(image_url_no . imagelibrary_image_path . $Image600X390) && $Image600X390 != '')
																					$show_image = image_url. imagelibrary_image_path . $Image600X390;
																				elseif(@$image_data['ImageBinaryData1'] != '')
																					$show_image = $image_data['ImageBinaryData1']; 
																				else
																					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
																								
																			}
																			elseif($image_size[0]['image_width'] == 600 && $image_size[0]['image_height'] == 300)
																			{
																				$Image600X300 	= str_replace(".","_600_300.", @$image_data['ImagePhysicalPath']);
																				if (getimagesize(image_url_no . imagelibrary_image_path . $Image600X300) && $Image600X300 != '')
																					$show_image = image_url. imagelibrary_image_path . $Image600X300;
																				elseif(@$image_data['ImageBinaryData2'] != '')
																					$show_image = $image_data['ImageBinaryData2']; 
																				else
																					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
																			}
																			elseif($image_size[0]['image_width'] == 150 && $image_size[0]['image_height'] == 150)
																			{
																				$Image150X150 	= str_replace(".","_150_150.", @$image_data['ImagePhysicalPath']);
																				if (getimagesize(image_url_no . imagelibrary_image_path . $Image150X150) && $Image150X150 != '')
																					$show_image = image_url. imagelibrary_image_path . $Image150X150;
																				elseif(@$image_data['ImageBinaryData4'] != '')
																					$show_image = $image_data['ImageBinaryData4']; 
																				else
																					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
																			}
																			elseif($image_size[0]['image_width'] == 100 && $image_size[0]['image_height'] == 65)
																			{
																				$Image100X65 	= str_replace(".","_100_65.", @$image_data['ImagePhysicalPath']);
																				if (getimagesize(image_url_no . imagelibrary_image_path . $Image100X65) && $Image100X65 != '')
																					$show_image = image_url. imagelibrary_image_path . $Image100X65;
																				elseif(@$image_data['ImageBinaryData3'] != '')
																					$show_image = $image_data['ImageBinaryData3']; 
																				else
																					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
																			}
																	}
																	else // if image size details not available for the display order
																	{
																	
																				$Image600X390 	= str_replace(".","_600_390.", @$image_data['ImagePhysicalPath']);
																				if (getimagesize(image_url_no . imagelibrary_image_path . $Image600X390) && $Image600X390 != '')
																					$show_image = image_url. imagelibrary_image_path . $Image600X390;
																				elseif(@$image_data['ImageBinaryData1'] != '')
																					$show_image = $image_data['ImageBinaryData1']; 
																				else
																					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
																		
																	}
																}
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
																
																$string_value = $contentID.",".$section_ID.", 'article', this, '".$url_structure."', 1";
																$custom_title = @$get_content['CustomTitle'];
																
																$custom_title = str_replace('<p','<span',$custom_title);
																$custom_title = str_replace('</p>','</span>',$custom_title);
																
																$content_url_title = join( "-",( explode(" ",$from_contents_table[0]['url_title']) ) );
																
																$special_parent_section = array();
																if(@$parent_section['ParentSectionID'] != 0)
																{
																	$special_parent_section 	= $this->widget_model->get_section_by_id($parent_section['ParentSectionID']);	
																	//echo $this->db->last_query();
																}
																
																if(@$special_parent_section['Sectionname'] != '')
																{
																 $url_section_value = join( "-",( explode(" ",$special_parent_section['Sectionname'] ) ) )."/".join( "-",( explode(" ",$parent_sectionname ) ) ).join( "-",( explode(" ",$from_contents_table[0]['Sectionname'] ) ) ); 

																}																
																else if($parent_sectionname != '')
																{
																 $url_section_value = join( "-",( explode(" ",$parent_sectionname ) ) ).join( "-",( explode(" ",$from_contents_table[0]['Sectionname'] ) ) ); 
																}
																else
																{
																 $url_section_value = join( "-",( explode(" ",$from_contents_table[0]['Sectionname'] ) ) ); 																 
																}
																$content_url_title = preg_replace('/[^A-Za-z0-9\-]/', '', $content_url_title);
																
																$live_string_value = $domain_name.$url_section_value."/". $content_url_title."-". $contentID."-1";
																
																if( trim(strip_tags($custom_title)) != '')
																{
																	$custom_title = '<a href="javascript:;" >'.$custom_title.'</a>';
																	$display_title = $custom_title;
																}
																else
																{
																		$article_title = str_replace('<p','<span',$from_contents_table[0]['Title']);
																		$article_title = str_replace('</p>','</span>',$article_title);
																		
																	$custom_title = '<a href="'.$live_string_value.'" >'.$article_title.'</a>';
																	$display_title = $article_title;
																		
																}	
																																	
																	//  Assign article links block ends hers
																	$play_video_image = image_url. imagelibrary_image_path.'play-circle.png';																	
																	$show_simple_tab .= '<div class="most1">';
																	$show_simple_tab .='<a href="'.$live_string_value.'" >';
																	$show_simple_tab .='<img src="'.$show_image.'"></a>
																			<p>'.$custom_title .'</p>																			
																			</div>';				  
																$i =$i+1;
																//$show_simple_tab .='</li>';
															
														}														
														// content list iteration block ends here
														$j++;
														
													}
												
													// Adding content Block ends here
													$show_simple_tab .='</div>';
													
              $show_simple_tab .='</div>
          </div>';
																			  
												
																			  
echo $show_simple_tab;
?>
