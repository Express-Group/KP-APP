<?php
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color        = $content['widget_bg_color'];
$widget_custom_title    = $content['widget_title'];
$widget_instance_id     = $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid        = $content['sectionID'];
$main_sction_id 	    = "";
$widget_section_url     = $content['widget_section_url'];
$is_home                = $content['is_home_page'];
$view_mode              = $content['mode'];
$domain_name            =  base_url();
$show_simple_tab        = "";
$max_article            = 1;
$render_mode            = $content['RenderingMode'];
/*----widgetbconfig ends here------*/
if($content['widget_title']==''){
	$WidgetTitle='LIVE :';
}else{
	$WidgetTitle=$widget_custom_title.' :';
}
$WidgetTitle='LIVE :';
if($render_mode == "manual"){
	$content_type = $content['content_type_id'];  // manual article content type
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], $max_article); 
	$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
	$get_content_ids = implode("," ,$get_content_ids);
	
	if($get_content_ids!=''){
		$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);
		
		foreach ($widget_instance_contents as $key => $value){
		
			foreach ($widget_instance_contents1 as $key1 => $value1){
			
				if($value['content_id']==$value1['content_id']){
					$widget_contents[] = array_merge($value, $value1);
				}
			}
		}
	}
}else{
	$content_type = $content['content_type_id'];  // auto article content type
	$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);

}

?>
<style>
.livenews-container{border:1px solid #000;padding:5px;background-color:#fdffda;margin-top:1%;margin-bottom:1%;width:100%;float:left;}
.livenews-container h2{margin-top:0px;}
.livenews-title{color:#ff0000;font-weight:700;text-transform: uppercase;}
.livenews-article-title{font-weight:normal;font-family: Droid serif,sans-serif!important;font-size: 34px;}
.livenews-summary{font-size:15px;}
.livenews-readmore{width:100%;float:left;margin-top: 4px;text-align: right;font-weight: 700;}
</style>
<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<div class="livenews-container" <?php print $widget_bg_color; ?>>
			<?php
				if(count($widget_contents) > 0):
					foreach($widget_contents as $get_content):
						$Title=$get_content['CustomTitle'];
						$Summary=$get_content['CustomSummary'];
						
						if($Title==''):
							$Title=$get_content['title'];
						endif;
						
						if($Summary=='' && $render_mode="auto"):
							$Summary=$get_content['summary_html'];
						endif;
						
						$Title=preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$Title);
						$Summary=preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$Summary);
						$Url=$get_content['url'];
					endforeach;
				endif;
			?>
			<h2><span class="livenews-title"><?php //print $WidgetTitle; ?> </span><a href="<?php print $Url ?>"><span class="livenews-article-title"><?php print $Title; ?></span></a></h2>
			<span class="livenews-summary"><?php print $Summary; ?></span>
			<!--<div class="livenews-readmore"><a href="<?php print $Url ?>">Read More >>></a></div>-->
		</div>
	</div>
</div>