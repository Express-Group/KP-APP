<?php 

// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid     = $content['sectionID'];
$main_sction_id 	 = "";
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$domain_name         =  base_url();
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
// widget config block ends

// Code block A - this code block is needed for creating simple tab widget. Do not delete
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							 <div class="WidthFloat_L" '.$widget_bg_color.'>
								<fieldset class="FieldTopic">';
					   	
			if($content['widget_title_link'] == 1)
			{
				$show_simple_tab.=	'<legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend>';
			}
			else
			{
				$show_simple_tab.=	'<legend class="topic">'.$widget_custom_title.'</legend>';
			}
			
			$show_simple_tab.= '</fieldset><div class="edit">';
			
				//getting content block - getting content list based on rendering mode
				//getting content block starts here . Do not change anything
				if($render_mode == "manual")
				{
						$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " , $view_mode, $max_article); 						
				}
				else
				{
			    $content_type = $content['content_type_id'];  // auto article content type
				$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode']);
				}
				
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
				$i =1;
		
			if(count($widget_contents)>0)
		     {		
				foreach($widget_contents as $get_content)
				{
						$content_url      = $get_content['url'];
						$param            = $content['close_param'];
						$live_article_url = $domain_name. $content_url.$param;
						$custom_title     = "";
						if($render_mode == "manual")
						$custom_title     = $get_content['CustomTitle'];
						
						if( $custom_title == '')
						{
							$display_title = $get_content['title'];
						}
						
						$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);   //to remove first<p> and last</p>  tag
						$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';	
					//  Assign article links block ends hers
					
						 $show_simple_tab .='<p> <i class="fa fa-chevron-right"></i>'.$display_title.'</p>';
											  
				}
				 
				// content list iteration block ends here
				}
	}
	 elseif($view_mode=="adminview")
	 {
		 $show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
	 }									
$show_simple_tab .=' </div></div>
          </div>
          </div>';
																			  
echo $show_simple_tab;
?>
