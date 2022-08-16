<?php
$widget_bg_color 		=	$content['widget_bg_color'];
$widget_custom_title 	=	$content['widget_title'];
$widget_instance_id 	=	$content['widget_values']['data-widgetinstanceid'];
$widgetsectionid 		= 	$content['sectionID'];
$main_sction_id 		= 	"";
$is_home                = $content['is_home_page'];
$is_summary_required    = $content['widget_values']['cdata-showSummary'];
$widget_section_url     = $content['widget_section_url'];
$view_mode              = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
$domain_name 		    =	base_url();
$show_simple_tab 	    = 	"";
$show_simple_tab 	   .=	' <div class="row">'; // Row Started 
$show_simple_tab 	   .=	'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-10 margin-top-10">';
if($content['widget_title_link'] == 1 && trim($widget_custom_title)!='none' && strtolower(trim($widget_custom_title)) !='the sunday standard'  && strtolower(trim($widget_custom_title)) !='magazine')
{
	$show_simple_tab.=	'<fieldset class="FieldTopic"><legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend></fieldset>';
}else{
	if(trim($widget_custom_title)!='none' && strtolower(trim($widget_custom_title)) !='the sunday standard' && strtolower(trim($widget_custom_title)) !='magazine'){
		$show_simple_tab.=	'<fieldset class="FieldTopic"><legend class="topic">'.$widget_custom_title.'</legend></fieldset>';
	}
}

if(strtolower(trim($widget_custom_title)) =='the sunday standard'){
	$show_simple_tab.='<div class="magazine-up-title">';
	$show_simple_tab.='<a href="'.$widget_section_url.'"><img  class="sunday-standard-img" src="'.image_url.'images/FrontEnd/images/logo/sunday_std_logo.jpg"></a>';
	$show_simple_tab.='</div>';
}
if(strtolower(trim($widget_custom_title)) =='magazine'){
	$show_simple_tab.='<div class="magazine-up-title">';
	$show_simple_tab.='<a href="'.$widget_section_url.'"><img class="magazine-s" src="'.image_url.'images/FrontEnd/images/logo/magazine_logo.jpg"></a>';
	$show_simple_tab.='</div>';
}
$content_type = $content['content_type_id'];
$widget_contents = array();
//getting content block - getting content list based on rendering mode
	//getting content block starts here . Do not change anything
if($render_mode == "manual")
{
$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], $max_article); 	

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
	$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
//$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode']);
}
//getting content block ends here
//Widget code block - code required for simple tab structure creation. Do not delete
//Widget code block Starts here
// content list iteration block - Looping through content list and adding it the list
// content list iteration block starts here
/*
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
		
			foreach ($widget_instance_contents as $key => $value) 
			{
				foreach ($widget_instance_contents1 as $key1 => $value1) 
				{
					if($value['content_id']==$value1['content_id'])
					{
					   $widget_contents[] = array_merge($value, $value1);
					}
				}
			} */

$i =1;
$count = 1;
if(count($widget_contents)>0)
{
	foreach($widget_contents as $get_content) // For Get Content Start Here 
	{
		                              
		$original_image_path = "";
		$imagealt            = "";
		$imagetitle          = "";
		$custom_title        = "";
		$custom_summary      = "";
		if($render_mode == "manual")
		{
			if($get_content['custom_image_path'] != '')
			{
			$original_image_path = $get_content['custom_image_path'];
			$imagealt            = $get_content['custom_image_title'];	
			$imagetitle          = $get_content['custom_image_alt'];												
			}
			$custom_title   = $get_content['CustomTitle'];
			$custom_summary = $get_content['CustomSummary'];
		}
		if($original_image_path =="")                                                // from cms || live table    
		{
		$original_image_path  = $get_content['ImagePhysicalPath'];
		$imagealt             = $get_content['ImageCaption'];	
		$imagetitle           = $get_content['ImageAlt'];	
		}
		$logo_prefix = ($is_home=='y') ? 'nie' : 'nie';
	    $show_image="";
		if($original_image_path !='' && get_image_source($original_image_path, 1))
		{
			$imagedetails = get_image_source($original_image_path, 2);
			$imagewidth = $imagedetails[0];
			$imageheight = $imagedetails[1];	
		
			if ($imageheight > $imagewidth)
			{
				$Image600X300 	= $original_image_path;
			}
			else
			{
				$Image600X300  = str_replace("original","w600X390", $original_image_path);
			}
			if (get_image_source($Image600X300, 1) && $Image600X300 != '')
			{
			$show_image = image_url. imagelibrary_image_path . $Image600X300;
			}
			else 
			{
			$show_image	= image_url. imagelibrary_image_path.'logo/'.$logo_prefix.'_logo_600X390.jpg';
			}
			$dummy_image	= image_url. imagelibrary_image_path.'logo/'.$logo_prefix.'_logo_600X390.jpg';
		}
		else
		{
		$show_image	= image_url. imagelibrary_image_path.'logo/'.$logo_prefix.'_logo_600X390.jpg';
		$dummy_image	= image_url. imagelibrary_image_path.'logo/'.$logo_prefix.'_logo_600X390.jpg';
		}
		$content_url = $get_content['url'];
		$param = $content['close_param'];
		$live_article_url = $domain_name.$content_url.$param;
		if( $custom_title == '')
		{
		$custom_title = $get_content['title'];
		}	
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
		$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
		//  Assign article links block ends hers
		// Assign summary block - creating links for  article summary
		// Assign summary block starts here
		if( $custom_summary == '' && $render_mode=="auto")
		{
		$custom_summary =  $get_content['summary_html'];
		}
		$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);
		if($i==1){
			$show_simple_tab.='<div class="magazine-up-first">';
			$show_simple_tab.='<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'">';
			$show_simple_tab.='<h4 class="subtopic">'.$display_title.'</h4>';
			$show_simple_tab.='</div>';
		}
		if($i!=1){
			$show_simple_tab.='<div class="magazine-up-second">';
			$show_simple_tab.='<h4 class="subtopic"><i class></i>'.$display_title.'</h4>';
			$show_simple_tab.='</div>';
		}
		
		$i =$i+1;							  
	} 
  }
 elseif($view_mode=="adminview")
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}

// Adding content Block ends here
//$show_simple_tab.=  '</div>'; //  WidthFloat_L for Last Dic close 
//$show_simple_tab .='</div>';// SundaySecond
$show_simple_tab .='</div>';// col-lg-12
$show_simple_tab .='</div>';
// Row End 

echo $show_simple_tab;
?>
 