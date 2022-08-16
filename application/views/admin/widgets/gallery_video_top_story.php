<style>
.VideoGalleryLead,{
    width: 100%;
    border-bottom: 1px solid #ccc;
    overflow: hidden;
	margin-botton:10px;
}

.VideoGallerySplit {
    margin-bottom: 10px;
}

.VideoGallerySplit a
{
	width:100%;
}

.VideoGalleryLead .WidthFloat_L .VideoGallerySplit{
    padding: 0 10px 0 0;
}

.VideoGalleryLead .WidthFloat_L .subtopic
{
	margin: 5px 0 10px 0px!important;
    font-size: 25px;
    line-height: 1.2;
    font-family: Droid Serif !important;
	font-weight: 700;
}

.GalleryVideoIcon_click
{
	position:relative;
	float:left;
}


.GalleryVideoIcon
{
	top: 5px;
    color: #fff;
    position: absolute;
    left: 5px;
    width: 25px!important;
    height: auto!important;	
}
</style>


<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
$domain_name =  base_url();
$show_simple_tab = "";
if($content['widget_title_link'] == 1){
	$widget_title=	'<a href="'.$widget_section_url.'">'.$widget_custom_title.'</a>';
}else{
	$widget_title=	$widget_custom_title;
}					
$show_simple_tab 	   .=	'<div class="row">'; // Row Started 
$show_simple_tab 	   .=	'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
//$show_simple_tab	   .=	'<fieldset class="FieldTopic"><legend class="topic">'.$widget_title.'</legend></fieldset>';	
$section_details = $this->widget_model->get_section_by_id($content['page_param']); // live db
if (strpos(strtolower($section_details['URLSectionStructure']), 'photogallery') !== false) 
{			
	$content_type = 3;
}
else
{
	$content_type = 4;
}

$widget_contents = array();
if($render_mode == "manual"){
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article); 						
}else{
	 $widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
}
$i = 1;
$count = 1;
if(count($widget_instance_contents)>0){			
	foreach($widget_instance_contents as $get_content){
		
        if($render_mode == "manual"){
			//$content_type = $get_content['content_type_id'];  // from widgetinstancecontent table
			$content_details = $this->widget_model->get_contentdetails_from_database($get_content['content_id'], $content_type, $is_home, $view_mode);
			$sectionname = $content_details[0]['section_name'];
		}else{
			//$content_type = $content['content_type_id'];  // from xml
			$sectionname ='';
		}
		$original_image_path = "";
		$imagealt            = "";
		$imagetitle          = "";
		$custom_title        = "";
		$custom_summary      = "";
		if($render_mode == "manual"){
			if($get_content['custom_image_path'] != ''){
				$original_image_path = $get_content['custom_image_path'];
				$imagealt = $get_content['custom_image_title'];	
				$imagetitle= $get_content['custom_image_alt'];												
			}
			$custom_title   = $get_content['CustomTitle'];
			$custom_summary = $get_content['CustomSummary'];
			$content_url = $content_details[0]['url'];
		}else{
			$content_url    = $get_content['url'];
			$custom_title   = $get_content['title'];
			$custom_summary = $get_content['summary_html'];
		}
		if($original_image_path =="" && $render_mode =="manual"){
			$original_image_path  = $content_details[0]['ImagePhysicalPath'];
			$imagealt             = $content_details[0]['ImageCaption'];	
			$imagetitle           = $content_details[0]['ImageAlt'];	
		}else if($original_image_path =="" && $render_mode =="auto"){
			$original_image_path  = $get_content['ImagePhysicalPath'];
			$imagealt             = $get_content['ImageCaption'];	
			$imagetitle           = $get_content['ImageAlt'];	
		}
		$show_image="";
		$is_image = false;
		if($original_image_path !='' &&  get_image_source($original_image_path, 1)){
			$imagedetails =  get_image_source($original_image_path, 2);
			$imagewidth = $imagedetails[0];
			$imageheight = $imagedetails[1];				
			if ($imageheight > $imagewidth){
				$Image600X300 	= $original_image_path;
			}else{
				$Image600X300  = str_replace("original","w600X300", $original_image_path);
			}
			if ($Image600X300 != '' && get_image_source($Image600X300, 1)){
				$show_image = image_url. imagelibrary_image_path . $Image600X300;
				$is_image = true;
			}else{
				$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			}
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
		}else{
			$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
			$is_image = false;
		}
		$param = $content['close_param']; //page parameter
		$live_article_url = $domain_name. $content_url.$param;			
		if( $custom_title == '' && $render_mode=="manual" ){
			$custom_title = $content_details[0]['title'];
		}	
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);			
		$display_title = '<a  href="'.$live_article_url.'"  class="article_click" >'.$display_title.'</a>';
		$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);
		$play_video_image = image_url. imagelibrary_image_path.'play-circle.png';
		$gallery_icon= image_url. imagelibrary_image_path.'gallery-icon.png';

			
		$show_simple_tab 	   .=	'<div class="VideoGalleryLead" '.$widget_bg_color.'>';	
		$show_simple_tab.= '<div class="WidthFloat_L">'; 
		$show_simple_tab.= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 VideoGallerySplit">';
		$show_simple_tab.= '<h4 class="subtopic">'.$display_title.'</h4><a  href="'.$live_article_url.'" class="GalleryVideoIcon_click"  >
		<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">';
		if($content_type == 3){
					$show_simple_tab.='<img class="GalleryVideoIcon"  src="'.$gallery_icon.'">';
				}else if($content_type == 4){
					$show_simple_tab.='<img class="GalleryVideoIcon"  src="'.$play_video_image.'">';
				}
		$show_simple_tab .='</a>';
		if($is_summary_required== 1)
		{
		$show_simple_tab.='<p class="summary">'.$summary.'</p>';
		}
		$show_simple_tab.= '</div></div></div>';
	
		$i =$i+1;							  
		} // For Get Content Start Here 	
		// content list iteration block ends here
      //}
  }
 elseif($view_mode=="adminview")
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
$show_simple_tab .='</div>';// col-lg-12
$show_simple_tab .='</div>';
// Row End 

echo $show_simple_tab;
?>
