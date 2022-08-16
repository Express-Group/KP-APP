<?php
$widget_bg_color     = $content['widget_bg_color'];
$typeclass = str_replace(["style='background-color:",";'"],"",$widget_bg_color);
$typeclass = trim($typeclass);
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = $content['sectionID'];
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
$content_id			 = $content['content_id'];
$content_type		 = $content['content_type'];
$page_section_id     = $content['page_param'];
$domain_name = base_url();
if($typeclass=='null'){
	$type = 1;
}else{
$type = (int) $typeclass;
}
if($content_type ==3 || $content_type ==4 || $content_type ==1 ):
	$CI = &get_instance();
	$this->live_db = $CI->load->database('live_db',true);
	$max_article =($max_article ==0) ? 6 : $max_article; 
	$sectionDetails  = $this->widget_model->get_section_by_id($page_section_id);
	$currentSectionID = $sectionDetails['Section_id'];
	$currentSectionName = $sectionDetails['Sectionname'];
	if($content_type ==3){
		$gallery = $this->live_db->query("SELECT content_id,title,url,first_image_path,first_image_title,first_image_alt FROM gallery WHERE content_id!='".$content_id."' AND section_id='".$currentSectionID."' AND status='P' ORDER BY publish_start_date DESC LIMIT ".$max_article ." ");
	}elseif($content_type ==4){
		$gallery = $this->live_db->query("SELECT content_id,title,url,video_image_path as first_image_path,video_image_title as first_image_title,video_image_alt as first_image_alt FROM video WHERE content_id!='".$content_id."' AND section_id='".$currentSectionID."' AND status='P' ORDER BY publish_start_date DESC LIMIT ".$max_article ." ");
	}else{
		$gallery = $this->live_db->query("SELECT content_id,title,url,article_page_image_path as first_image_path,article_page_image_title as first_image_title,article_page_image_alt as first_image_alt FROM article WHERE content_id!='".$content_id."' AND section_id='".$currentSectionID."' AND status='P' ORDER BY publish_start_date DESC LIMIT ".$max_article ." ");
	}
	$template = "";
	if($gallery->num_rows() > 0){
		$template .='<fieldset class="FieldTopic"><legend class="topic">'.$widget_custom_title.'</legend></fieldset>';
		$i=1;
		foreach($gallery->result() as $gallerylist):
			$live_article_url = $domain_name . $gallerylist->url;
			$displayTitle = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallerylist->title);
			$displayTitle = '<a  href="' . $live_article_url . '" class="article_click" >' . $displayTitle . '</a>';
			$image = $gallerylist->first_image_path;
			if($image != '' && get_image_source($image, 1)){
				if($type==1):
					$displayimage = image_url . imagelibrary_image_path.str_replace("original", "w100X65", $image);
				else:
					$displayimage = image_url . imagelibrary_image_path.str_replace("original", "w600X300", $image);
				endif;
			}else{
				if($type==1):
					$displayimage = image_url . imagelibrary_image_path . 'logo/nie_logo_100X65.jpg';
				else:
					$displayimage = image_url . imagelibrary_image_path . 'logo/nie_logo_600X300.jpg';
				endif;
			}
			if($type==1){
				$template .='<div class="most1">';
				$template .='<a  href="' . $live_article_url . '" ><img src="' . $displayimage . '"  title = "' . $gallerylist->first_image_title . '" alt = "' . $gallerylist->first_image_alt . '"></a>';
				$template .='<p>'.$displayTitle.'</p>';
				$template .='</div>';
			}
			if($type==2){
				if($i==1){
					$template .='<div class="row">';
				}
				$template .='<div class="col-md-6 col-lg-6 col-sm-12">';
				$template .='<a  href="' . $live_article_url . '" ><img src="' . $displayimage . '"  title = "' . $gallerylist->first_image_title . '" alt = "' . $gallerylist->first_image_alt . '"></a>';
				$template .='<p>'.$displayTitle.'</p>';
				$template .='</div>';
				if($i==2){
					$template .='</div>';
					$i=1;
				}else{
					$i++;
				}
				
			}
			if($type==3){
				if($i==1){
					$template .='<div class="row">';
				}
				$template .='<div class="col-md-4 col-lg-4 col-sm-12">';
				$template .='<a  href="' . $live_article_url . '" ><img src="' . $displayimage . '"  title = "' . $gallerylist->first_image_title . '" alt = "' . $gallerylist->first_image_alt . '"></a>';
				$template .='<p>'.$displayTitle.'</p>';
				$template .='</div>';
				if($i==3){
					$template .='</div>';
					$i=1;
				}else{
					$i++;
				}
				
			}
			if($type==4){
				if($i==1){
					$template .='<div class="row">';
				}
				$template .='<div class="col-md-3 col-lg-3 col-sm-12">';
				$template .='<a  href="' . $live_article_url . '" ><img src="' . $displayimage . '"  title = "' . $gallerylist->first_image_title . '" alt = "' . $gallerylist->first_image_alt . '"></a>';
				$template .='<p>'.$displayTitle.'</p>';
				$template .='</div>';
				if($i==4){
					$template .='</div>';
					$i=1;
				}else{
					$i++;
				}
				
			}
			
		endforeach;	
		if($type==2 && $i==2 ){
			$template .='</div>';
		}
		if($type==3 && ($i==2 || $i==3) ){
			$template .='</div>';
		}
		if($type==4 && ($i==2 || $i==3 || $i==4) ){
			$template .='</div>';
		}
	}else{
		$template .='<fieldset class="FieldTopic"><legend class="topic">'.$widget_custom_title.'</legend></fieldset>';
		$template .='<h6>No more galleries</h6>';
	}
	echo $template;
endif;
?>