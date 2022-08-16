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
$show_simple_tab .='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
if(strtolower(strip_tags($widget_custom_title))!='none'){
	$show_simple_tab.='<fieldset class="FieldTopic"><legend class="topic">'.$widget_title.'</legend></fieldset>';
}				
$content_type = $content['content_type_id'];
$widget_contents = array();
if($render_mode == "manual"){
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article); 						
}else{
	 $widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
}
$i =1;
$l=1;
$show_simple_tab .='<div class="entertainment_upgraded SundaySecond">';
if(count($widget_instance_contents)>0){		
	$CI = &get_instance();
	$this->live_db = $CI->load->database('live_db',TRUE);
	foreach($widget_instance_contents as $get_content){
		
        if($render_mode == "manual"){
			$content_type = $get_content['content_type_id'];  // from widgetinstancecontent table
			$content_details = $this->widget_model->get_contentdetails_from_database($get_content['content_id'], $content_type, $is_home, $view_mode);
			$sectionname = $content_details[0]['section_name'];
		}else{
			$content_type = $content['content_type_id'];  // from xml
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
		
		if($i==1 && $l==1){
			$show_simple_tab .='<div class="WidthFloat_L">';
			$show_simple_tab .='<div class="col-md-12 col-lg-12 col-sm-12 entertainment-first-block">';
			$show_simple_tab .='<div class="ent-up-first">';
			$show_simple_tab .='<a href="'.$live_article_url.'"><img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';
			$show_simple_tab .='</div>';
			$show_simple_tab .='<div class="ent-up-second">';
			$show_simple_tab .='<h4 class="subtopic">'.$display_title.'</h4>';
			$show_simple_tab .='<p class="summary">'.$summary.'</p>';
			$show_simple_tab .='</div>';
			$show_simple_tab .='</div>';
			$show_simple_tab .='</div>';
		}
		if($l >=2 && $l <4){
			$wtype =1;
		}else if($l >=5 && $l <7){
			$wtype =2;
		}
		if($l==2){
			$show_simple_tab .='<div class="WidthFloat_L margin-top-10">';
		}
		if($l >=2){
		$show_simple_tab .='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 SundaySecondSplit ent-gal" '.(($wtype==2)? ' style="position:relative;"':"").'>';
			if($wtype==1){
				$SectionDetails = $this->live_db->query("SELECT URLSectionStructure FROM sectionmaster WHERE Section_id='".$content_details[0]['section_id']."'")->result();
				$sectionUrl = $domain_name.@$SectionDetails[0]->URLSectionStructure;
				if($content_type == '3'){
					$display_title = '<a  href="'.$live_article_url.'"  class="article_click" >'.$display_title.' <i class="fa fa-image" style="color: #000;font-size: 12px;opacity: 0.7;"></i></a>';
				}else if($content_type == '4'){
					$display_title = '<a  href="'.$live_article_url.'"  class="article_click" >'.$display_title.' <i class="fa fa-video-camera" style="color: #000;font-size: 12px;opacity: 0.7;"></i></a>';
				}else{
					$display_title = '<a  href="'.$live_article_url.'"  class="article_click" >'.$display_title.'</a>';
				}
				$show_simple_tab .='<div class="special_main_img"><span class="special-main-tag"></span></div>';
				$show_simple_tab .='<p class="special-article-title"><a href="'.$sectionUrl.'"><span>'.$sectionname.'</span></a>'.$display_title.'</p>';
			}
			if($wtype==2){
				$show_simple_tab .='<a href="'.$live_article_url.'">';
				$dummy_image = str_replace('300','390',$dummy_image);
				$show_image = str_replace('300','390',$show_image);
				$show_simple_tab .='<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">';
				if($content_type == '3'){
					$show_simple_tab.='<img class="GalleryIcon"  src="'.$gallery_icon.'">';
				}else if($content_type == '4'){
					$show_simple_tab.='<img class="GalleryIcon"  src="'.$play_video_image.'">';
				}
				$show_simple_tab .'</a>';
				$show_simple_tab .='<h4 class="subtopic">'.$display_title.'</h4>';
				
			}
		$show_simple_tab .='</div>';
		}
		if($l==4){
			$show_simple_tab .='</div><div class="WidthFloat_L">';
			$l++;
		}else if($l==7){
			$show_simple_tab .='</div>';
			$l=2;
		}else{
		    $l++;
		}
			
		
		$i++;

	}
}elseif($view_mode=="adminview"){
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
if($l!=2 && count($widget_instance_contents)>0){
	$show_simple_tab .='</div>';
}

$show_simple_tab .='</div></div></div>';
																			  
												
																			  
echo $show_simple_tab;
?>