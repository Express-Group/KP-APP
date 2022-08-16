<?php 

$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
// widget config block ends
//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id, $content['mode']);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
$show_simple_tab = "";

$show_simple_tab .='<div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10 GalleryDetailList"><h4 class="MagazinesTitle SiteColor">Other Videos</h4>';													
													// Adding content Block starts here

														//$content_id= $content['content_id'];
														
														//$content_det = $this->widget_model->get_content_details($content_id)->result_array();
														$url_structure = $content['url_structure'];
														//$section_id = $content_det[0]['Section_id'];
														$widget_instance_contents = $this->widget_model->get_all_available_video_auto($content['show_max_article'], $widget_instancemainsection[$j]['Section_ID'], $content['mode']); 
														
														$i =1;
														$count = 1;
														$firstcount = 1;
														$closed = false;
														$firstclosed = false;
														foreach($widget_instance_contents as $get_content)
														{
															//$show_simple_tab .= '<li class="allvideo1">';
															$show_image = "";
															// Code Block B - if rendering mode is manual then if custom image is available then assigning the image to a variable
															// Code Block B starts here - Do not change															
															
															// Code Block B ends here
															// getting content details from database - Do not change
															$from_contents_table = $this->template_design_model->required_widget_content_by_id($get_content['content_id'], '4');	
															
															$SourceURL = $content['widget_img_phy_path'];
															// Code block C - if rendering mode is auto then this code blocks retrieve required image from article related image if content type is article (This widget uses only article- Do not change
															// Code block C  starts here
																if($show_image =='')
																{
																	$image_data = $this->widget_model->get_image_data($get_content['content_id']);

																	
																	  
                  
                  $Image600X390  = str_replace("original","w600X390", @$image_data['ImagePhysicalPath']);
                
		
																			$imagealt ="";
																	$imagetitle="";
																			if (get_image_source($Image600X390, 1) && $Image600X390 != '')
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
																$dummy_image = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
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
																
																$string_value = $contentID.",".$section_ID.", 'article', this, '".$url_structure."'";
																$custom_title = $get_content['CustomTitle'];
																
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
																$content_url_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $content_url_title);
																$param = $content['close_param'];
 															$live_string_value = $domain_name.$url_section_value."/". $content_url_title."-". $contentID.$param;
															
															
																if( trim(strip_tags($custom_title)) != '')
																{
																	//$custom_title = '<a href="javascript:;" >'.$custom_title.'</a>';
																	$display_title = $custom_title;
																}
																else
																{
																		$article_title = str_replace('<p','<span',$from_contents_table[0]['Title']);
																		$article_title = str_replace('</p>','</span>',$article_title);
																		
																	//$custom_title = '<a href="javascript:;" >'.$article_title.'</a>';
																	$display_title = $article_title;
																		
																}
																
																$play_video_image = image_url. imagelibrary_image_path.'play-circle.png';	
																if($i ==1 || $i == 2)
																{																	
																	if($firstcount == 1)
																	{
																		$show_simple_tab.= '<div class="row">';
																	}
																	if($firstcount <= 2)
																	{																		
																		$show_simple_tab .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
																							  <div class="GalleryDetailList1">
																							  <figure class="PositionRelative">

																							  <a  href="'.$live_string_value.'" class="article_click" ><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">';

																		
																		$show_simple_tab .='<img class="GalleryIcon" src="'.$play_video_image.'"></a>
																							  </figure>
																							  <p><a  href="'.$live_string_value.'" class="article_click" >'.$display_title .'</a></p>
																							  </div>
																							  </div>';										
																		if($firstcount ==2)
																		{
																			$firstcount = 0;
																			$show_simple_tab.= '</div>';
																			$firstclosed = true;
																		}								
																	 $firstcount ++;
																	 }				  
																
																}
																else
																{																	
																	if($count == 1)
																	{
																		$show_simple_tab.= '<div class="row">';
																	}
																	if($count <= 3)
																	{
																		$closed = false;
																		$show_simple_tab .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																							  <div class="GalleryDetailList1">
																							   <figure class="PositionRelative"><a  href="'.$live_string_value.'" class="article_click" >

																							  <img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">';

																		$show_simple_tab .='<img class="GalleryIcon" src="'.$play_video_image.'"></a>
																							  </figure>
																							  <a  href="'.$live_string_value.'" class="article_click" ><p>'.$custom_title .'</p></a>
																							  </div>
																							  </div>';										
																		if($count ==3)
																		{
																			$count = 0;
																			$show_simple_tab.= '</div>';
																			$closed = true;
																		}								
																	$count ++;
																	}	  																					  
																}
																$i =$i+1;
																//$show_simple_tab .='</li>';
															
														}														
														if($firstclosed == false && $i >1 )
														{
															$show_simple_tab .= '</div>';
														}
														if(!$closed && $i >3 )
														{
															$show_simple_tab .= '</div>';
														}																												
														// content list iteration block ends here														
												
												////  below code for pagination  /////
												
												/*$show_simple_tab .= '<div class="pagina">
                 <div>
                            <a href="#"><i class="fa fa-angle-double-left"></i></a>
                            <a href="#"><i class="fa fa-angle-left"></i></a>
                            <a class="active" href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                            <a href="#"><i class="fa fa-angle-double-right"></i></a>
                            </div>
      			</div>';*/	
              $show_simple_tab .='</div></div>';
																			  
												
																			  
echo $show_simple_tab;
?>
