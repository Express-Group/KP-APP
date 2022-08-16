<?php
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-10 text-center">
		<div class="section_<?php echo $widget_instance_id; ?>"></div>
	</div>
</div>
<script>
function sectionas_<?php echo $widget_instance_id ?>(){
$.ajax({
	type:'post',
	cache:false,
	url:'<?php echo BASEURL ?>user/commonwidget/fetchadv',
	data:{'widgetinstance':'<?php echo $widget_instance_id; ?>'},
	dataType:'html',
	success:function(result){
		$('.section_<?php echo $widget_instance_id; ?>').html(result);
	}
});
}
setTimeout('sectionas_<?php echo $widget_instance_id ?>()',2000);
</script>