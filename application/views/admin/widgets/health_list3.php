<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color 		= $content['widget_bg_color'];
$widget_custom_title 	= $content['widget_title'];
$widget_instance_id 	= $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid 		= $content['sectionID'];
$main_sction_id 		= "";
$is_home 				= $content['is_home_page'];
$is_summary_required 	= $content['widget_values']['cdata-showSummary'];
$widget_section_url 	= $content['widget_section_url'];
$view_mode            	= $content['mode'];
$widget_section_url     = $content['widget_section_url'];
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];
// widget config block ends

$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .= '<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="row">';

if($content['widget_title_link'] == 1)
{
	if($widget_custom_title != ''){
		$show_simple_tab.=	'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><fieldset class="FieldTopic"><legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend></fieldset></div>';
	}
}
else
{
	if($widget_custom_title != ''){
		$show_simple_tab.=	'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><fieldset class="FieldTopic"><legend class="topic">'.$widget_custom_title.'</legend></fieldset></div>';
	}
}
$content_type	 = $content['content_type_id'];  // manual article content type				
$widget_contents = array();										
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual")
{
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article);
	$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
	$get_content_ids = implode("," ,$get_content_ids); 

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
	//$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $view_mode);
	$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
}
//getting content block ends here
/*
if (function_exists('array_column')) 
{
	$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
}
else
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
	$i = 1;
	$count = 1;
	if(count($widget_contents) > 0 ){	
		// content list iteration block - Looping through content list and adding it the list
		// content list iteration block starts here
		foreach($widget_contents as $get_content)
		{
					
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$custom_title        = "";
			$custom_summary      = "";
			if($render_mode == "manual"){
				if($get_content['custom_image_path'] != ''){
					$original_image_path = $get_content['custom_image_path'];
					$imagealt            = $get_content['custom_image_title'];	
					$imagetitle          = $get_content['custom_image_alt'];												
				}
				$custom_title   = $get_content['CustomTitle'];
				$custom_summary = $get_content['CustomSummary']; 
			}
			if($original_image_path ==""){  // from cms || live table   
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
					$Image150X150 	= $original_image_path;
				}
				else
				{
				     $Image150X150  = str_replace("original","w150X150", $original_image_path);
				}
				if (get_image_source($Image150X150, 1) && $Image150X150 != ''){
					$show_image = image_url. imagelibrary_image_path . $Image150X150;
				}
				else {
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
				}
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
			}
			else{
				$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
			}
			
			$content_url = $get_content['url'];
			$param = $content['close_param'];
			$live_article_url = $domain_name.$content_url.$param;
			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? $get_content['title']: '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
			if( $custom_summary == '' && $render_mode=="auto")
				{
					$custom_summary =  $get_content['summary_html'];
				}
		 $summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);			
		 // Assign summary block starts here
			////////  For first Article  /////////
			if($i<=3)
			{
				$show_simple_tab .='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margin-top-15 margin-bottom-15">';
				$show_simple_tab .='<div class="papers"> <a href="'.$live_article_url.'" class="article_click">';
				$show_simple_tab .='<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt= "'.$imagealt.'">';
				$show_simple_tab .='<h4 class="subtopic">'.$display_title.'</h4>';
				if($is_summary_required== 1){
				$show_simple_tab .='<p class="para_bold summary">'.$summary.'</p>';
				}
				$show_simple_tab .='</div></div>';
			}
			$i =$i+1;							  
		}// content list iteration block ends here
	//}
}
 elseif($view_mode=="adminview"){
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}


$show_simple_tab .='</div></div></div>';
echo $show_simple_tab;
?>
