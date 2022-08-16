<?php
$style 		= str_replace(["style='background-color:" ,";'"] ,"" ,$content['widget_bg_color']);
$widget_instance_id	 	= $content['widget_values']['data-widgetinstanceid'];
$view_mode              = $content['mode'];
$widget_instance_details= $this->widget_model->getWidgetInstance('', '','', '', $widget_instance_id, $content['mode']);
$widget_position = $content['widget_position'];	
?>
<div class="row" id="section_content_check_<?php echo $widget_instance_id;?>" style="display:none;">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-10">
    <div class="scc section_content_check_<?php echo $widget_instance_id;?>">
		<span class="scc-span">ADVERTISEMENT</span>
		<div class="scc-div">
		 <?php echo $widget_instance_details['AdvertisementScript']; ?>
		</div>
    </div>
  </div>
</div>
<script>
<?php if($style==2): ?>
if(screen.width > 800 || document.documentElement.clientWidth > 800) {
	document.getElementById("section_content_check_<?php echo $widget_instance_id?>").parentNode.parentNode.parentNode.removeChild(document.getElementById("section_content_check_<?php echo $widget_instance_id?>").parentNode.parentNode);
}else{
	document.getElementById("section_content_check_<?php echo $widget_instance_id?>").style.display ="block";
}
<?php else: ?>
if(screen.width < 800 || document.documentElement.clientWidth < 800) {
	document.getElementById("section_content_check_<?php echo $widget_instance_id?>").parentNode.parentNode.parentNode.removeChild(document.getElementById("section_content_check_<?php echo $widget_instance_id?>").parentNode.parentNode);
}else{
	document.getElementById("section_content_check_<?php echo $widget_instance_id?>").style.display ="block";
}
<?php endif; ?>
</script>