<?php
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id      = "";
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article           = $content['show_max_article'];
$render_mode           = $content['RenderingMode'];
$section_id 		= $content['sectionID'];
$current_date = date('Y-m-d');
$CI =&get_instance();
$this->live_db = $CI->load->database('live_db', TRUE);
$show_simple_tab = "";
$domain_name=base_url();
$sectionlength = ($section_id == 318)? 5 : 7;

for ($i=0; $i<$sectionlength; $i++)
{  
	$opinion_date = ($section_id == 318)? date("Y-m-d", strtotime('-'.$i.' Sunday')) : date("Y-m-d", strtotime($i." days ago"));
	$new_date = date('d.m.Y', strtotime($opinion_date));
	$today = date('Y-m-d');
	
	$stm = " 00:00:00";
	$etm = " 23:59:59";
	$start_date = $opinion_date.$stm;
	$end_date = $opinion_date.$etm;
	if($opinion_date <= $today):
	{
	$show_simple_tab 	   .=	'<div class="row">'; // Row Started 
	$show_simple_tab 	   .=	'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	$show_simple_tab 	   .=	'<div class="all_opinion">';
	$show_simple_tab 	   .=	'<div class="section_opinion_date"><date>'.$new_date.'</date></div>';
	$show_simple_tab 	   .=   '<div class="section_opinion_list_wrap">';
	$res = $this->live_db->query("SELECT a.content_id, a.section_id, a.section_name, a.last_updated_on, a.publish_start_date, a.title, a.url, a.summary_html, a.author_name, a.author_image_path, a.author_image_title, a.author_image_alt , a.column_name FROM article_section_mapping as b LEFT JOIN article as a ON a.content_id = b.content_id WHERE a.status = 'P' AND ( b.section_id IN ( SELECT Section_id FROM sectionmaster WHERE Section_id = '".$section_id."' )) AND a.publish_start_date >= '".$start_date."' AND a.publish_start_date <= '".$end_date."' AND a.publish_start_date <= NOW()")->result_array();
	//print_r($res);
	if(!empty($res))
	{
	$m = 1;
	foreach($res as $get_content){
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$custom_title        = "";
			$custom_summary      = "";
			$author_name         = ""; 
			$Author_image_path   ="";
			if($get_content['author_image_path'] !="")
			{
					$Author_image_path  =  $get_content['author_image_path'];
					$imagealt           = $get_content['author_image_alt'];	
					$imagetitle         = $get_content['author_image_title'];
			}
			if($Author_image_path !='')
			{
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
			$section = $get_content['section_name'];
			$content_url = $get_content['url'];
			$author_name = $get_content['author_name'];
			$author_url  = join("-", explode(" ", $author_name)); 
			$url_array = explode('/', $content_url);
			
			 $get_seperation_count = count($url_array)-4;
			$section_url = ($get_seperation_count==1)? $author_url : (($get_seperation_count==2)? $url_array[0]."/".$url_array[1] : $url_array[0]."/".$url_array[1]."/".$url_array[2]);
			
			
			$author_pos = stripos($author_name, $section);
            if ($author_pos === false) {
			$author_new_url = $domain_name.'Author/'.$author_url;
			}else
			{
			$author_new_url = $domain_name.$section_url;
			}
			$param = $content['close_param'];
			$live_article_url = $domain_name.$content_url.$param;
			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? $get_content['title']: '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);   //to remove first<p> and last</p>  tag
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
			$custom_summary = ( $custom_summary == '' && $render_mode=="auto") ? $get_content['summary_html']: '';
			$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);
			$lastpublishedon = $get_content['publish_start_date'];
			if($m==1){
				$show_simple_tab.= '<div class="row">'; 
			} 
			$show_simple_tab 	   .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="sub_opinion sub_opinion_main">';
										
			$show_simple_tab .='<a  href="'.$live_article_url.'"  ><img src="'.$dummy_image.'"  data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';
			$show_simple_tab .= '<a href="'.$author_new_url.'"  ><h4>'.$author_name.'</h4></a>';
			$show_simple_tab .= '<h5>'.$display_title.'</h5>';
			$time = $lastpublishedon; 
			$post_time = $this->widget_model->time2string($time);
			$show_simple_tab .=	'<p class="post_time">'.$post_time.' </p>'; 
			$show_simple_tab 	   .='</div> </div>';
			 if($m ==2){
				$show_simple_tab 	   .='</div>';
				$m=1;
			 }else{
				 $m++;
			 }
				 
	}
	if($m ==2){
		$show_simple_tab 	   .='</div>';
	}
	}
	else{
		$show_simple_tab 	   .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
		$show_simple_tab 	   .='<div class="opinion_no_article">no articles</div>';
		$show_simple_tab 	   .='</div>';
	}
	$res='';																								
	$show_simple_tab .='</div>';
	$show_simple_tab .='</div>';
	$show_simple_tab .='</div>'; // col-lg-12
	$show_simple_tab .='</div>'; // Row Started 
	}
	endif;

}

echo $show_simple_tab;
?>