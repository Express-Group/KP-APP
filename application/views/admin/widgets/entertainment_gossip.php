<?php
/*
Finame 		: 	entertainment_gossip
Created On 	: 	16-10-2015
Purpose for	:	Display the gossip at entertainment_gossip
*/
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color 		=	$content['widget_bg_color'];
$widget_custom_title 	=	$content['widget_title'];
$widget_instance_id 	=	$content['widget_values']['data-widgetinstanceid'];
$widgetsectionid 		= 	$content['sectionID'];
$main_sction_id 		= 	"";
$widget_section_url     = $content['widget_section_url'];
$is_home                = $content['is_home_page'];
$is_summary_required    = $content['widget_values']['cdata-showSummary'];
$view_mode              = $content['mode'];
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];
// widget config block ends
// Here the Design Start 
if($widget_custom_title == "")
{
$widget_custom_title = "Gossip";
}
$domain_name 		=	base_url();
$show_simple_tab 	= 	"";
$show_simple_tab 	.=	' <div class="row">'; // Row Started 
$show_simple_tab 	.=	'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10">';
//$show_simple_tab 	.=	'<fieldset class="FieldTopic">';

if($content['widget_title_link'] == 1)
{
	if(strtolower(strip_tags($widget_custom_title))!='none'):
		$show_simple_tab.=	' <fieldset class="FieldTopic"><legend class="topic"><a href="'.$widget_section_url.'" >'.$widget_custom_title.'</a></legend></fieldset>';
	endif;
}
else
{	if(strtolower(strip_tags($widget_custom_title))!='none'):
		if(strip_tags($widget_custom_title)=='Karnataka Power Struggle'){
			$show_simple_tab.=	' <fieldset class="FieldTopic"><legend class="topic"><a href="http://www.newindianexpress.com/elections/Karnataka-Polls-2018">'.$widget_custom_title.'</a></legend></fieldset>';
		}else{
			$show_simple_tab.=	' <fieldset class="FieldTopic"><legend class="topic">'.$widget_custom_title.'</legend></fieldset>';
		}
	endif;
}

//$show_simple_tab.= ' </fieldset>';
$show_simple_tab.= '<div class="most" '.$widget_bg_color.'>'; 

    //getting content block - getting content list based on rendering mode
	//getting content block starts here . Do not change anything
if($render_mode == "manual")
{
//$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], $max_article); 
	$content_type = $content['content_type_id'];  // manual article content type
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article);
	// changed newly on 10-09-2016
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
//$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode']);
$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
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
		   $widget_contents = array();
			foreach ($widget_instance_contents as $key => $value) 
			{
				foreach ($widget_instance_contents1 as $key1 => $value1) 
				{
					if($value['content_id']==$value1['content_id'])
					{
					   $widget_contents[] = array_merge($value, $value1);
					}
				}
			}
			
*/
$i =1;
$count = 1;
if(count($widget_contents)>0)
{
	foreach($widget_contents as $get_content) // For Get Content Start Here 
	{
	// Code Block B - if rendering mode is manual then if custom image is available then assigning the imageid to a variable
	// Code Block B starts here - Do not change
		                              
	$original_image_path = "";
	$imagealt            = "";
	$imagetitle          = "";
	$custom_title        = "";
	if($render_mode == "manual")
	{
		if($get_content['custom_image_path'] != '')
		{
		$original_image_path = $get_content['custom_image_path'];
		$imagealt            = $get_content['custom_image_title'];	
		$imagetitle          = $get_content['custom_image_alt'];												
		}
		$custom_title   = $get_content['CustomTitle'];
	}
	if($original_image_path =="")                                                // from cms || live table    
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
			$Image100X65 	= $original_image_path;
		}
		else
		{
		    $Image100X65  = str_replace("original","w100X65", $original_image_path);
		}
		if ($Image100X65 != '' && get_image_source($Image100X65, 1))
		{
		$show_image = image_url. imagelibrary_image_path . $Image100X65;
		}
		else 
		{
		$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
		}
		$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
	}
	else
	{
	$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
	$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_100X65.jpg';
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
	
	// Assign summary block starts here
	$section_name= $get_content['section_name'];
	// Assign summary block starts here
	/*
	<div class="most1">
	<a href="#"><img data-src="images/money.jpg"></a>
	<p><a href="#">To celebrate a year of the Narendra Modi government</a></p>
	</div>
	//////  For first Article  //////// */
	$show_simple_tab.= '<div class="most1">';
	$show_simple_tab.= '<a href="'.$live_article_url.'" class="article_click" >
	<img src="'.$dummy_image.'" data-src="'.$show_image.'"  title = "'.$imagetitle.'" alt = "'.$imagealt.'" width="100" height="65"></a>';
	$show_simple_tab .=$display_title;
	$show_simple_tab.= '</div>';
	$count ++;	
	// display title and summary block ends here					
	//Widget design code block 1 starts here																
	//Widget design code block 1 starts here			
	$i =$i+1;							  
	}   //  // Get Section End
// }
}
 elseif($view_mode=="adminview")
{
$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}
//if($content['widget_title_link'] == 1)
//{
//$show_simple_tab .=' <div class="arrow_right"><a href="'.$widget_section_url.'" class="landing-arrow">arrow</a></div>';
//}
// Adding content Block ends here
$show_simple_tab .='</div>';// Most
$show_simple_tab .='</div>';// col-lg-12
$show_simple_tab .='</div>';// Row End 

echo $show_simple_tab;
?>
