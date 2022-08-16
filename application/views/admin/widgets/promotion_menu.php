<?php
$widget_bg_color 		= $content['widget_bg_color'];
$widget_instance_id	 	= $content['widget_values']['data-widgetinstanceid'];
$view_mode              = $content['mode'];
$widget_instance_details= $this->widget_model->getWidgetInstance('', '','', '', $widget_instance_id, $content['mode']);	
?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="static_menu<?php echo $widget_instance_id;?>" id="static_menu<?php echo $widget_instance_id;?>">
      <?php echo urldecode($widget_instance_details['AdvertisementScript']); ?>
      <?php //echo $widget_instance_details['AdvertisementScript']; ?>
    </div>
  </div>
</div>
