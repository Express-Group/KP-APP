
<style>
.common_p .fontcircle {
    height: 21px;
    padding: 6px 10px 0 0;
    position: absolute;
    left: -15px;
    font-size: 7px;
}
</style>
<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. 
$widget_bg_color        = $content['widget_bg_color'];
$widget_section_url     = $content['widget_section_url'];
$widget_custom_title    = $content['widget_title'];
$widget_instance_id     =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	    = "";
$is_home                = $content['is_home_page'];
$is_summary_required    = $content['widget_values']['cdata-showSummary'];
$view_mode              = $content['mode'];
$domain_name            =  base_url();
$show_simple_tab        = "";
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];

                                        /****************** ASSIGN WIDGET TITLE HERE ************************/
$show_simple_tab       .='<div class="">
				<div class="top_news common_p margin-bottom-15" '.$widget_bg_color.'>';
				if($content['widget_title_link'] == 1)
				{
				     $show_simple_tab.=	'
					<fieldset class="FieldTopic"><legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend></fieldset>';
				}
				else
				{
				     $show_simple_tab.=	'<fieldset class="FieldTopic"><legend class="topic">'.$widget_custom_title.'</legend></fieldset>';
				}
		
                                        /************** getting content list based on rendering mode **********************/
			if($content['RenderingMode'] == "manual")
			{
			$content_type = $content['content_type_id'];  // manual article content type
			$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], $max_article); 
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
		if(count($widget_contents)>0)
		{
			foreach($widget_contents as $get_content)
			{
					$content_url = $get_content['url'];  //article url
					$param = $content['close_param']; //page parameter
					$live_article_url = $domain_name.$content_url.$param;
				
					$custom_title = '';
					if($render_mode =='manual'){
					$custom_title = $get_content['CustomTitle'];
					}
					if( $custom_title == '')
					{
						$custom_title = $get_content['title'];
					}	
					$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);  //to remove first<p> and last</p>  tag
					
				//  Assign article links block ends hers
				 $show_simple_tab .='<p> <i class="fa fa-circle fontcircle"></i> <a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a></p>';
				$i++;						  
			}
			// content list iteration block ends here
		}
		elseif($view_mode=="adminview")
		{
			$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
		}
		// Adding content Block ends here
		$show_simple_tab .='</div>';
		/*
		if($content['widget_title_link'] == 1)
		{
		$show_simple_tab .='<div class="arrow_right"><a href="'.$widget_section_url.'" class="landing-arrow">arrow</a></div>';
		}
		*/
		$show_simple_tab .='</div>';
																			  
echo $show_simple_tab;
?>
<script type="text/javascript">
$(document).ready(function(){
  $('.top_news').parent('.col-lg-12').addClass('top_news_padding');	
});
</script>