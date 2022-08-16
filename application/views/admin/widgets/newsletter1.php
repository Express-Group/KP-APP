<script type="application/x-javascript"> 
addEventListener("load", function(){ 
setTimeout(hideURLbar, 0); }, false);
function hideURLbar(){ window.scrollTo(0,1); } 
</script>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<link href='<?php echo base_url(); ?>/css/FrontEnd/css/newsletter.css' rel='stylesheet' type='text/css'>
<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color 		= $content['widget_bg_color'];
$widget_custom_title 	= $content['widget_title'];
$widget_instance_id 	=  $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid 		= $content['sectionID'];
$main_sction_id 		= "";
$is_home 				= $content['is_home_page'];
$is_summary_required 	= $content['widget_values']['cdata-showSummary'];
$widget_section_url 	= $content['widget_section_url'];
$view_mode            	= $content['mode'];
$page_section_id        = $content['page_param'];
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];
// widget config block ends

$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .= '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 election-result">';
$sectionname = $this->widget_model->get_sectionDetails($page_section_id, $view_mode); 
$view_it_in_browser = base_url().$sectionname['URLSectionStructure'];
												
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual"){
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
else{
	$content_type = $content['content_type_id'];  // auto article content type
	
	$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
}

	$i =1;
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
					$Image600X390 	= $original_image_path;
				}
				else
				{
				     $Image600X390  = str_replace("original","w600X390", $original_image_path);	
	             }
				if ($Image600X390 != '' && get_image_source($Image600X390, 1)){
					$show_image = image_url. imagelibrary_image_path . $Image600X390;
				}
				else {
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
			else{
				$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			}
			
			$content_url = $get_content['url'];
			$param = $content['close_param'];
			$live_article_url = $domain_name.$content_url.$param;
			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? $get_content['title']: '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);
			//$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
			if( $custom_summary == '' && $render_mode=="auto")
			{
				$custom_summary =  $get_content['summary_html'];
			}
			$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_summary);
			
			////////  For first Article  /////////
			if($count == 1){
			?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#434952" class="moduleSeperatorLineDark">
<tbody>
<tr>
<td class="color-white" valign="top" bgcolor="#434952" align="center" >

<?php
if($content['widget_title_link'] == 1)
{
echo 	' <h3 class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></h3>';
}
else
{
echo 	' <h3 class="topic">'.$widget_custom_title.'</h3>';
}
?>
 </td>
</tr>

<tr>
<td valign="top" align="center" bgcolor="#434952">
<table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#434952" class="eraseForMobile2">
<tbody><tr>
<td valign="middle" height="10" align="center" bgcolor="#434952" style="font-size:0; line-height:0;">&nbsp;</td>
</tr>
</tbody></table>
<table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#434952" class="table600">
<tbody><tr>
<td valign="middle" height="30" align="center" bgcolor="#434952" style="font-size:0; line-height:0;">&nbsp;</td>
</tr>
</tbody></table>
<table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="table600">
<tbody><tr>
<td valign="top" bgcolor="#434952">
<table align="left" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" style="border:1px solid #434952;">
<tbody><tr>
<td valign="top" bgcolor="#434952">
<table width="326" align="center" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="table280">    
<tbody><tr>																																																					
<td valign="top" align="center" height="10" bgcolor="#434952" class="sectionsHeaderDarkTD"><?php echo $display_title ?></td>

</tr>
</tbody></table>
</td>
<td valign="top" align="center" bgcolor="#434952">
<table align="center" width="20" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="eraseForMobile">
<tbody><tr>
<td valign="top" align="center" height="10" bgcolor="#434952" style="font-size:0; line-height:0;">&nbsp;</td>
</tr>
</tbody></table>
</td>
</tr>
</tbody></table>
<table width="230" align="right" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="table280" style="border:1px solid #434952;">
<tbody><tr>
<td valign="top" align="center" bgcolor="#434952">
<table width="230" align="center" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="table280"> 
<tbody><tr>
<td valign="top" align="right" height="10" bgcolor="#434952" class="table280DarkButton2"><a href="<?php echo $view_it_in_browser; ?>" target="_blank" class="buttonsAndImagesLink"><img src="<?php echo base_url(); ?>/images/FrontEnd/images/button_lightBG.jpg" style="display:block;" alt="IMG" border="0" align="top" hspace="0" vspace="0" width="230" height="50"></a></td>

</tr>
</tbody></table>
</td>
</tr>
</tbody></table>
</td>
</tr>
</tbody></table>
<table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="table600">
<tbody><tr>
<td valign="top" align="center" height="25" bgcolor="#434952" style="font-size:0; line-height:0;" class="table600Height">&nbsp;</td>
</tr>
</tbody></table>
<table align="center" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="image600">
<tbody><tr>

<td valign="top" align="center" bgcolor="#434952" class="pictureAlternativeTextTD"><a href="<?php echo $live_article_url; ?>" target="_blank" class="buttonsAndImagesLink"><img src="<?php echo $show_image; ?>" style="display:block;" alt="<?php echo $imagealt; ?>" border="0" align="top" hspace="0" vspace="0" width="600" height="270"></a></td>
</tr>
</tbody></table>
<table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#434952" border="0" class="table600">
<tbody><tr>
<td valign="middle" height="15" align="center" bgcolor="#434952" style="font-size:0; line-height:0;">&nbsp;</td>
</tr>
<tr>																																																					
<td valign="middle" align="center" bgcolor="#434952" height="10" class="sectionRegularInfoTextDarkTD"><?php echo $summary ?> <a href="<?php echo $live_article_url; ?>" target="_blank" class="sectionRegularInfoTextDarkTDLink">Readmore</a></td>

</tr>
</tbody></table>
<table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#434952" class="table600">
<tbody><tr>
<td valign="middle" height="25" align="center" bgcolor="#434952" style="font-size:0; line-height:0;">&nbsp;</td>
</tr>
</tbody></table>
<table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#434952" class="eraseForMobile2">
<tbody><tr>
<td valign="middle" height="10" align="center" bgcolor="#434952" style="font-size:0; line-height:0;">&nbsp;</td>
</tr>
</tbody></table>
</td>
</tr>
</tbody></table>
<?php
}		
			// display title and summary block ends here			
			$i =$i+1;
		}// content list iteration block ends here
	//}
}
 elseif($view_mode=="adminview"){
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
	$show_simple_tab = '';
	return false;
}


$show_simple_tab .='</div></div>';
echo $show_simple_tab;
?>
