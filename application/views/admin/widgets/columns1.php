<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = $content['sectionID'];
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
// widget config block ends
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name     =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10">
            <div class="WidthFloat_L" id="columns_'.$widget_instance_id.'" '.$widget_bg_color.'>
			<fieldset class="FieldTopic">';	
			if($content['widget_title_link'] == 1)
			{
				$show_simple_tab.=	' <legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend>';
			}else
			{
				$show_simple_tab.=	'<legend class="topic">'.$widget_custom_title.'</legend>';
			}
			
			$show_simple_tab.='</fieldset> <div class="most">';
				
				//getting content block - getting content list based on rendering mode
				//getting content block starts here . Do not change anything
				if($render_mode == "manual")
				{
				$content_type = $content['content_type_id'];  
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
// end changed newly on 10-09-2016
				}
				else
				{
				$content_type = $content['content_type_id'];  // auto article content type
				//$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $main_sction_id , $content_type ,  $content['mode']);
				
				$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
				
				
			}
			
	/*
				// content list iteration block - Looping through content list and adding it the list
				// content list iteration block starts here
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
			foreach ($widget_instance_contents as $key => $value) {
				foreach ($widget_instance_contents1 as $key1 => $value1) {
					if($value['content_id']==$value1['content_id']){
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
					$author_name         = ""; 
					$original_image_path = "";
					$imagealt            = "";
					$imagetitle          = "";
					$custom_title        = "";
					$Author_image_path   = "";
					if($render_mode == "manual")
					{
						/*if($get_content['custom_image_path'] != '')
						{
							$original_image_path = $get_content['custom_image_path'];
							$imagealt            = $get_content['custom_image_title'];	
							$imagetitle          = $get_content['custom_image_alt'];												
						}*/
						$custom_title = $get_content['CustomTitle'];
					}
                    if($view_mode =="live"){
						if($original_image_path =='')
						{
							if($get_content['author_name']!='')
							{
								if($get_content['author_image_path'] !='')
								{	
									$Author_image_path = $get_content['author_image_path'];
									$imagealt            = $get_content['author_image_alt'];	
									$imagetitle          = $get_content['author_image_title'];
								}
							}	
						}
						$author_name = $get_content['author_name'];
					}
					else
					{
						$author_id = $get_content['AuthorID']; 
						$Author_image_path = "";
						/*$image_id= $get_content['image_id'] ;
						
						if($image_id!='')
						{
						$author_details       = $this->widget_model->get_image_by_contentid($image_id);
						$Author_image_path    = $author_details['ImagePhysicalPath'];
						$imagealt             = $author_details['ImageCaption'];	
						$imagetitle           = $author_details['ImageAlt'];
						}*/
						
						if($author_id !='')
						{
							$author_name = $get_content['AuthorName']; 
							$image_path=$get_content['image_path'] ;
							if($image_path!='')
							{
								$Author_image_path  = $get_content['image_path'];
								$imagealt             =  $get_content['image_alt'];
								$imagetitle           =  $get_content['image_caption'];
							}	
						}
						else
						{
							$author_name = $get_content['URLSectionName'] ;
						}
					}
					//$original_image_path = ($Author_image_path!='')? $Author_image_path : $original_image_path;
					$show_image="";
						if($Author_image_path !='')
						{
							 /* $Image150X150  = str_replace("original","w150X150", $original_image_path);
								if (getimagesize(image_url . imagelibrary_image_path . $Image150X150) && $Image150X150 != '')
								{
									$show_image = image_url. imagelibrary_image_path . $Image150X150;
								}
								else {
									$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
								}*/
								if (getimagesize(image_url_no . $Author_image_path) && $Author_image_path != '')
								{ 
								$show_image = image_url. $Author_image_path;
								}
								else
								{
								$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
								}
								$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
						}
						else
						{
								$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
								$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
						}
						
						$content_url      = $get_content['url'];  //article url
						$param            = $content['close_param']; //page parameter
						$live_article_url = $domain_name.$content_url.$param;
						
						$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? $get_content['title']: '' ) ;
						$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);  //to remove first<p> and last</p>  tag
						$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';					
						if($view_mode=="live"){
						$url_array = explode('/', $content_url);
						$get_seperation_count = count($url_array)-4;
						$sectionURL = ($get_seperation_count==1)? $domain_name.$url_array[0] : (($get_seperation_count==2)? $domain_name.$url_array[0]."/".$url_array[1] : $domain_name.$url_array[0]."/".$url_array[1]."/".$url_array[2]);
						$url_section_value = $sectionURL;
						}else
						{
							$url_section_value = $domain_name.$get_content['URLStructure'];
						}
						if ($author_name == "")
						{
							$author_name = $get_content['section_name'] ;
						}
										
					//  Assign article links block ends hers
						// display title and summary block starts here
						$show_simple_tab.='<div class="most1">';

								$show_simple_tab .='<a  href="'.$live_article_url.'" class="article_click" ><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';
								$show_simple_tab.='<h6><a  href="'.$url_section_value.'">'.$author_name.'</a></h6>';
									 $show_simple_tab .='<p>'.$display_title.'</p>';
								     $show_simple_tab .= '</div>';
						
						// display title and summary block ends here					
						//Widget design code block 1 starts here																
					//Widget design code block 1 starts here			
					$i =$i+1;							  
				}
				
				// content list iteration block ends here
	//}
	} elseif($view_mode=="adminview")
	{
		$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
	}
 
 $show_simple_tab .='</div></div></div></div>';
echo $show_simple_tab;
?>

       
