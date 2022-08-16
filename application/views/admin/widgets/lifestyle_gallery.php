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
if($widget_custom_title == "")
	$widget_custom_title = "Gallery"; 
// widget config block ends

$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10">
<div class="slider LifeGallery" id="lifestyle_gallery_'.$widget_instance_id.'"><fieldset class="FieldTopic">';

if($content['widget_title_link'] == 1)
{
	$show_simple_tab.=	'<legend class="topic"><a href="'.$widget_section_url.'" >'.$widget_custom_title.'</a></legend>';
}
else
{
	if(trim($widget_custom_title) != '')
		$show_simple_tab.=	'<legend class="topic">'.$widget_custom_title.'</legend>';
	else 
		$show_simple_tab.=	'<legend class="topic">&nbsp</legend>';
}
$show_simple_tab.=	'</fieldset>';
// Code Block A ends here


//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
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
	// content list iteration block - Looping through content list and adding it the list
	// content list iteration block starts here
	if(count($widget_contents)>0)
	{			
		foreach($widget_contents as $get_content)
		{			
			$custom_title        = "";
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$Image600X390        = "";
			if($render_mode == "manual")
			{
				if($get_content['custom_image_path'] != '')
				{
					$original_image_path = $get_content['custom_image_path'];
					$imagealt            = $get_content['custom_image_title'];	
					$imagetitle          = $get_content['custom_image_alt'];												
				}
				$custom_title            = stripslashes($get_content['CustomTitle']);
			}
				if($original_image_path =="")                         // from cms imagemaster table    
				{
				$original_image_path  = $get_content['ImagePhysicalPath'];
				$imagealt             = $get_content['ImageCaption'];	
				$imagetitle           = $get_content['ImageAlt'];	
				}
			
			$left_side_show_image="";
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
				    $Image600X390  = str_replace("original","w600X390", $original_image_path); 
				}
				if($Image600X390 != '' && get_image_source($Image600X390, 1))
				{
					$left_side_show_image = image_url. imagelibrary_image_path . $Image600X390; 
				}
				else{
					$left_side_show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
			else {
				$left_side_show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
			$dummy_image = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			
			$content_url = $get_content['url'];
			$param = $content['close_param']; //page parameter
			$live_article_url = $domain_name. $content_url.$param;

			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? stripslashes($get_content['title']) : '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
			//  Assign article links block ends hers
			$active ='';
			if($i==1)
			{
				$show_simple_tab.= '<div id="carousel_'.$widget_instance_id.'" class="flexslider LifeGalleryLeft" '.$widget_bg_color.'><ul class="slides">';
				$active = 'active';
			}
				$show_simple_tab .='<li class="'.$active.'" data-target="#slide-'.$i.'"><figure class="PositionRelative">';
				$show_simple_tab .='<a  href="javascript:void(0);"  class="article_click" ><img src="'.$left_side_show_image.'">';
				$show_simple_tab .='<img class="GalleryListing lifestyle_gallery_thumb" src="'.image_url. imagelibrary_image_path.'/gallery-icon.png"></a></figure></li>';
			if($i == count($widget_contents))
			{
				$show_simple_tab .='</ul></div>';	
			}// display title and summary block ends here		
			$i =$i+1;							  
		} // 1st foreach loop ends here
	
		$k =1;
		foreach($widget_contents as $get_content)
		{
	
			$custom_title        = "";
			$original_image_path = "";
			$imagealt            = "";
			$imagetitle          = "";
			$Image600X390        = "";
			if($render_mode == "manual")
			{
				if($get_content['custom_image_path'] != '')
				{
					$original_image_path = $get_content['custom_image_path'];
					$imagealt            = $get_content['custom_image_title'];	
					$imagetitle          = $get_content['custom_image_alt'];												
				}
				$custom_title            = stripslashes($get_content['CustomTitle']);
			}
				if($original_image_path =="")                         // from cms imagemaster table    
				{
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
					$Image600X390 	= $original_image_path;
				}
				else
				{
				    $Image600X390  = str_replace("original","w600X390", $original_image_path);
				}
				if($Image600X390 != '' && get_image_source($Image600X390, 1))
				{
					$show_image = image_url. imagelibrary_image_path . $Image600X390;
				}
				else{
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
			else {
				$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
			$dummy_image = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			
			$content_url = $get_content['url'];
			$param = $content['close_param']; //page parameter
			$live_article_url = $domain_name. $content_url.$param;
		
			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? stripslashes($get_content['title']) : '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
	        $active = '';
			//  Assign article links block ends hers
			if($k==1)
			{
				$show_simple_tab.= '<div id="slider_'.$widget_instance_id.'" class="LifeGalleryRight tab-content"> ';
				$active = 'active';
			}
				
				$show_simple_tab .='<div id="slide-'.$k.'" class="tab-pane '.$active.'"><figure class="PositionRelative"><a  href="'.$live_article_url.'"  class="article_click" ><img src="'.$show_image.'">';
				$show_simple_tab .='<img class="GalleryListing " src="'.image_url. imagelibrary_image_path.'gallery-icon.png"> </a></figure>';
				$show_simple_tab .='<p class="subtopic"><a  href="'.$live_article_url.'"  class="article_click" >'.$display_title.'</a></p></div>';
				
			// display title and summary block ends here	
			if($k == count($widget_contents))
			{
				$show_simple_tab .='</ul></div>';
				
				if($content['widget_title_link'] == 1)
				{
				$show_simple_tab .='<div class="arrow_right"><a href="'.$widget_section_url.'" class="landing-arrow">arrow</a></div>';
				}
			}	
			$k =$k+1;							  
		}
	//}
}
 elseif($view_mode=="adminview")
{
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}

// content list iteration block ends here
$show_simple_tab .='</div></div></div>';
echo $show_simple_tab;



?>
<script type="text/javascript">
$(document).ready(function(){
$('#lifestyle_gallery_<?php echo $widget_instance_id;?> li').click( function(){
    $(this).tab('show');
  });
});
</script>

