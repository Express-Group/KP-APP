<?php
$widget_bg_color = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$widgetsectionid = $content['sectionID'];
$is_home              = $content['is_home_page'];
$view_mode            = $content['mode'];
$main_sction_id 	= "";
//getting tab list for hte widget
//$editor_pick_articles 	= $this->widget_model->get_section_article_for_common_widgets(0, $content['mode'], 2);    // last parameter indicates editor pick
$domain_name =  base_url();
?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="accordion" <?php echo $widget_bg_color;?>>
			<div class="panel-group" id="accordion_<?php echo $widget_instance_id;?>">
				<!--<div class="panel panel-default">
					<div class="panel-heading" data-toggle="collapse" id="editor_pick" data-parent="#accordion_<?php echo $widget_instance_id;?>" data-target="#collapse1_<?php echo $widget_instance_id;?>">
						<h3 class="panel-title"> <a class="accordion-toggle" > Editor Pick <i class="fa fa fa-chevron-up pull-right"></i> <i class="fa fa fa-chevron-down pull-right"></i> </a> </h3>
					</div>
					<div id="collapse1_<?php echo $widget_instance_id;?>" class="panel-collapse collapse in">
						<div class="panel-body common_p" id="editor_pick_content">
							<?php /*?><?php 
							if(count($editor_pick_articles)>0){
								$count=1;
								foreach($editor_pick_articles as $article_content)
								{ 
								$content_type = '1';
						
								$content_details = $this->widget_model->get_contentdetails_from_database($article_content['content_id'], $content_type,$is_home, $view_mode);																
						
								$content_url = $content_details[0]['url'];  //article url
								$param = $content['page_param']; //page parameter
								if($article_content['CustomTitle'] != "")
								{
									$display_title = $article_content['CustomTitle'];
								}
								else {
									$display_title = $content_details[0]['title'];
								}
								$live_article_url = $domain_name.$content_url."?pm=".$param;
								  echo '<p><i class="fa fa-angle-right"></i>
									<a  href="'.$live_article_url.'"  class="article_click" >'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $display_title).'</a></p>';
									$count = $count+1;
								}
								} elseif($view_mode=="adminview"){
								   echo '<div class="margin-bottom-10">'.no_articles.'</div>';
								}?><?php */?>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading collapsed" data-toggle="collapse" id="trending" onclick="show_accordian_articles('trending')" data-parent="#accordion_<?php echo $widget_instance_id;?>" data-target="#collapse2_<?php echo $widget_instance_id;?>">
						<h3 class="panel-title"> <a class="accordion-toggle"> Trending Now <i class="fa fa fa-chevron-up pull-right"></i> <i class="fa fa fa-chevron-down pull-right"></i> </a> </h3>
					</div>
					<div id="collapse2_<?php echo $widget_instance_id;?>" class="panel-collapse collapse ">
						<div class="panel-body common_p" id="trending_content">
							<?php /*?><?php 
								if(count($trending_now)>0){
									$count=1;
									foreach($trending_now as $article_content)
									{ 
									$content_type = '1';
							
									$content_details = $this->widget_model->get_contentdetails_from_database($article_content['content_id'],$content_type,$is_home, $view_mode);																
									$content_url = $content_details[0]['url'];  //article url
									$param = $content['page_param']; //page parameter
									if($article_content['CustomTitle'] != "")
									{
										$display_title = $article_content['CustomTitle'];
									}
									else {
										$display_title = $content_details[0]['title'];
									}
									$live_article_url = $domain_name.$content_url."?pm=".$param;
									  echo '<p><i class="fa fa-angle-right"></i>
										<a  href="'.$live_article_url.'"  class="article_click" >'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $display_title).'</a></p>';
										$count = $count+1;
									}
									}else{
									   echo '<div class="margin-bottom-10">'.no_articles.'</div>';
									}?><?php */?>
							<div class="cssload-container">
								<div class="cssload-zenith"></div>
							</div>
						</div>
					</div>
				</div>-->
				<div class="panel panel-default">
					<div class="panel-heading " data-toggle="" id="most_read" onclick="show_accordian_articles('most_read')" data-parent="#accordion_<?php echo $widget_instance_id;?>" data-target="#collapse3_<?php echo $widget_instance_id;?>">
						<h3 class="panel-title"> <a class="accordion-toggle"> ಹೆಚ್ಚು ಓದಿದ್ದು<!--<i class="fa fa fa-chevron-up pull-right"></i> <i class="fa fa fa-chevron-down pull-right"></i>--> </a> </h3>
					</div>
					<div id="collapse3_<?php echo $widget_instance_id;?>" class="panel-collapse collapse in">
						<div class="panel-body common_p" id="most_read_content">
							<div class="cssload-container">
								<div class="cssload-zenith"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/#accordion1--> 
		</div>
	</div>
</div>
<script>
 function show_accordian_articles(content_type){
		 $.ajax({
			url			: '<?php echo base_url(); ?>user/commonwidget/get_editor_pick_content',
			method		: 'post',
			data		: { type: content_type, param: '<?php echo $content['close_param'];?>',mode:'<?php echo $view_mode;?>'},
			beforeSend	: function() {				
			},
			success		: function(result){ 
			console.log(result);
			//alert(result);
				   $('#'+content_type+'_content').html(result).hide().fadeIn({ duration: 1000 }); 
				  $('#'+content_type).removeAttr('onclick');
				 //  console.clear();
				   }			
		});
	   }

$(document).ready(function(){
show_accordian_articles('most_read');
});	   
</script>