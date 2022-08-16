<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color 		= $content['widget_bg_color'];
$widget_custom_title 	= $content['widget_title'];
$widget_instance_id 	= $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 		= "";
$is_home 				= $content['is_home_page'];
$is_summary_required 	= $content['widget_values']['cdata-showSummary'];
$widget_section_url 	= $content['widget_section_url'];
$view_mode           	= $content['mode'];
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];

// widget config block ends
//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id, $content['mode']);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
$is_home = $content['is_home_page'];

$show_simple_tab = "";
$show_simple_tab .='<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="GallerySlider features">
<div class="slide GalleryLeadSlider">';

$j = 0;
// Adding content Block starts here

if($render_mode == "manual")
{
$content_type = $content['content_type_id'];  // manual article content type
$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article);
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
$count = 1;
if(count($widget_contents)>0)
	{
foreach($widget_contents as $get_content)
{
$custom_title        = "";
$original_image_path = "";
$imagealt            = "";
$imagetitle          = "";
$Image600X390        = "";
$custom_summary      = "";
if($render_mode == "manual")
{
if($get_content['custom_image_path'] != '')
{
$original_image_path = $get_content['custom_image_path'];
$imagealt            = $get_content['custom_image_title'];	
$imagetitle          = $get_content['custom_image_alt'];												
}
$custom_title            = stripslashes($get_content['CustomTitle']);
$custom_summary = $get_content['CustomSummary'];
}
if($original_image_path =="")                         // from cms imagemaster table    
{
$original_image_path  = $get_content['ImagePhysicalPath'];
$imagealt             = $get_content['ImageCaption'];	
$imagetitle           = $get_content['ImageAlt'];	
}
$show_image="";
if($original_image_path !='')
{
$Image600X390  = str_replace("original","w600X390", @$original_image_path);
											
if (get_image_source($Image600X390, 1) && $Image600X390 != '')
{
$show_image = image_url. imagelibrary_image_path . $Image600X390;
}
else {
$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
}
$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
}
else {
$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
}
$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
// Code block C ends here

// Assign block - assigning values required for opening the article in light box
// Assign block starts here
$content_url = $get_content['url'];
$param = $content['close_param']; //page parameter
$live_article_url = $domain_name. $content_url.$param;

$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? stripslashes($get_content['title']) : '' ) ;
$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);
if( $custom_summary == '' && $render_mode=="auto")
{
$custom_summary =  $get_content['summary_html'];
}
$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);  //to remove first<p> and last</p>  tag
$play_gallery_image = image_url. imagelibrary_image_path.'play-circle.png';

$show_simple_tab.= '<div class="item">
<div class="GallerySliderLeft">
<figure class="PositionRelative">

<a  href="'.$live_article_url.'"  class="article_click" ><img data-lazy="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"><img src="'.$play_gallery_image.'" class="GalleryIcon"></a>

</figure>
</div>
<div class="GallerySliderRight">
<p><a  href="'.$live_article_url.'" class="article_click subtopic" >'.$display_title.'</a></p>';
if($is_summary_required== 1)
{
$show_simple_tab.='<p class="summary">'.$summary.'</p>';
}
$show_simple_tab.='</div>
</div>';


$i =$i+1;
}
}elseif($view_mode=="adminview")
{
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
// content list iteration block ends here

// Adding content Block ends here
$show_simple_tab .=' </div>
';
															
													
$show_simple_tab .='</div>
</div>
</div>
';
																			  
												
																			  
echo $show_simple_tab;
?>

