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
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];
// widget config block ends

$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .= '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 election-result">';


													
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything
if($render_mode == "manual"){
	$content_type = $content['content_type_id'];  // manual article content type
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article);
}
else{
	$content_type = $content['content_type_id'];  // auto article content type
$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);
	
}
//getting content block ends here
$content_id ='';
if(count($widget_instance_contents)>0){
$content_id = $widget_instance_contents[0]['content_id'];
$content_type = $content_type;  
}


if($content_id !='' )
{
	/*if($view_mode == "live")
	{
		$widget_instance_contents1 = $this->widget_model->widget_article_content_by_id($content_id, $content_type,"");
	}
	else{
		$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($content_id, $content_type, $is_home, $view_mode);
	}
	//$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
	$widget_contents = array();
	foreach ($widget_instance_contents as $key => $value) {
		foreach ($widget_instance_contents1 as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
			   $widget_contents[] = array_merge($value, $value1);
			}
		}
	}*/
	$widget_contents = array();
	if($view_mode=="live"){
		$widget_instance_contents1 = $this->widget_model->widget_article_content_by_id($content_id, $content_type, '');	
	}else{
	$widget_instance_contents1 = $this->widget_model->widget_article_content_preview($content_id, $content_type);
	}
	foreach ($widget_instance_contents as $key => $value) {
		foreach ($widget_instance_contents1 as $key1 => $value1) {
			if($value['content_id']==$value1['content_id']){
				$widget_contents[] = array_merge($value, $value1);
			}
		}
	}
	$i =1;
	$count = 1;
	if(count($widget_contents) > 0 ){	
		// content list iteration block - Looping through content list and adding it the list
		// content list iteration block starts here
		foreach($widget_contents as $get_content)
		{	
			if($render_mode == "manual"):
				$custom_title = $get_content['CustomTitle'];
				$custom_summary = $get_content['CustomSummary'];
			else:
				$custom_title = '';
				$custom_summary = '';
			endif;
		
			$content_url = $get_content['url'];
			$param = $content['close_param'];
			$live_article_url = $domain_name.$content_url.$param;
			$display_title = ( $custom_title != '') ? $custom_title : ( ($get_content['title'] != '') ? $get_content['title']: '' ) ;
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
			$custom_summary = ( $custom_summary == '' && $render_mode=="auto") ? $get_content['summary_html']: '';
			$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);
			//  Assign article links block ends hers
			$table_content_summary = ($view_mode == 'live') ? $get_content['article_page_content_html'] : $get_content['ArticlePageContentHTML'] ;
			////////  For first Article  /////////
			if($count == 1){

				$show_simple_tab .= '<p>' . $display_title .'</p>';
				$show_simple_tab.= $table_content_summary;
				$count ++;	
			}		
			// display title and summary block ends here			
			$i =$i+1;
		}// content list iteration block ends here
	}
}
 elseif($view_mode=="adminview"){
	$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
}


$show_simple_tab .='</div></div>';
echo $show_simple_tab;
?>
