<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$widget_section_url  = $content['widget_section_url'];
$main_sction_id 	 = "";
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$view_mode           = $content['mode'];
$max_article         = ($content['show_max_article'] == 'undefined') ? 5 : $content['show_max_article'] ;
$render_mode         = $content['RenderingMode'];
// widget config block ends
//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id, $content['mode']);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name     =  base_url();
$show_simple_tab = "";
// Code Block A ends here

// Tab Creation Block Starts here
$j = 0;

// // Tab Creation Block- Below code gets the record from windgetinstancemainsection table to create tabs for this widget 
// Adding content Block - to add contents for each tab
// Adding content Block starts here
foreach($widget_instancemainsection as $get_section)
{
	$content_type = $content['content_type_id'];  // manual article content type
	$widget_contents = array();
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual")
{
$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($get_section['WidgetInstance_id'], $get_section['WidgetInstanceMainSection_id'],$view_mode, $max_article); 

					$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
					$get_content_ids = implode("," ,$get_content_ids); 
                      
					if($get_content_ids!='')
					{
						$content_type = $widget_instance_contents[0]['content_type_id']; // manual mode content type
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
$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $get_section['Section_ID'] , $get_section['Section_Content_Type'] ,  $content['mode'], $is_home);
}

//getting content block ends here
//Widget code block - code required for simple tab structure creation. Do not delete
//Widget code block Starts here

$show_simple_tab .='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 galleries features" id="gallery_video-'.$widget_instance_id.'">';
$widget_title = $get_section['CustomTitle'];
if($j==0){
$widget_section_url = $domain_name.$get_section['URLSectionStructure'];
$content_type = 3;  
}
elseif($j==1)
{
$widget_section_url = $domain_name.$get_section['URLSectionStructure'];
$content_type = 4; 
}

$show_simple_tab.=	'<h4 class="topic"><a href="'.$widget_section_url.'">'.$widget_title.'</a></h4>';



$show_simple_tab .='<div class="slide HomeGallery">';                     
//Widget code block ends here

// content list iteration block - Looping through content list and adding it the list
// content list iteration block starts here
$i =1;$k=1;

if(count($widget_contents)>0)
{
 $slider_count_det = $this->widget_model->select_setting($view_mode);
 $slider_count = $slider_count_det['slider_count'];
foreach($widget_contents as $get_content)
{
		
	$original_image_path = "";
	$imagealt            = "";
	$imagetitle          = "";
	$custom_title        = "";
	if($render_mode == "manual")
	{
		if($get_content['custom_image_path'] != '')
		{
			$original_image_path = $get_content['custom_image_path'];
			$imagealt = $get_content['custom_image_title'];	
			$imagetitle= $get_content['custom_image_alt'];												
		}
		$custom_title = $get_content['CustomTitle'];
	}

			if($original_image_path =='')
			{
				$original_image_path = $get_content['ImagePhysicalPath'];
				$imagealt            = $get_content['ImageCaption'];	
				$imagetitle          = $get_content['ImageAlt'];
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
			$Image600X390  = str_replace("original","w600X300", $original_image_path);
			}
			if ($Image600X390 != '' && get_image_source($Image600X390, 1))
			{
			$show_image = image_url. imagelibrary_image_path . $Image600X390;
			}
			else
			{
			$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			}
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			}
			else {
			$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			}
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
				// Code block C ends here
				
				// Assign block - assigning values required for opening the article in light box
				// Assign block starts here
				
				$content_url = $get_content['url'];
				$param = $content['close_param']; //page parameter
				$live_article_url = $domain_name. $content_url.$param;
				
				if( $custom_title == '')
				{
				$custom_title = $get_content['title'];
				}	
				
				$display_title =  preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_title);
				//  Assign article links block ends hers


// display title and summary block starts here
if($k<=$slider_count){
if($j==0){
$icon ='<span class="icon-gl-vd icon-gl"></span>';	
}else{
$icon ='<span class="icon-gl-vd icon-vd"></span>';		
}

$title = $display_title;

if($k==1){

$show_simple_tab .='<div class="gal_img item">';

$show_simple_tab .='<div class="gallery1"><a   href="'.$live_article_url.'" class="article_click" >'.$icon.'<img src="'.$dummy_image.'" data-lazy="'.$show_image.'"   title = "'.$imagetitle.'" alt = "'.$imagealt.'">
<div class="gallery_cap"><p>'.$display_title.'</p></div></a>
</div>'; 
if($i == count($widget_contents) || ($k==$slider_count))
{
$show_simple_tab .= '</div>';
$k=0;
}
}else{
$show_simple_tab .='<div class="gallery1"><a   href="'.$live_article_url.'" class="article_click" >'.$icon.'<img src="'.$dummy_image.'" data-lazy="'.$show_image.'"  title = "'.$imagetitle.'" alt = "'.$imagealt.'">
<div class="gallery_cap"><p>'.$display_title.'</p></div></a>
</div>';
if($i == count($widget_contents) || ($k==$slider_count))
{
$show_simple_tab .= '</div>';
$k=0;
}
}
}

if($i == count($widget_contents))
{
	
$show_simple_tab .= '</div>
			</div>
		  </div>';
}
// display title and summary block ends here					
//Widget design code block 1 starts here																
//Widget design code block 1 starts here			
$i =$i+1;	
$k =$k+1;							  
}
} elseif($view_mode=="adminview")
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
$show_simple_tab .= '</div>
	</div>
  </div>';
}else
{
$show_simple_tab .= '</div>
	</div>
  </div>';
	
}

// content list iteration block ends here
//$show_simple_tab .= '</div>';
$j++;
}
echo $show_simple_tab;
?>
