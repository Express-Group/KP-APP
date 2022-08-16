<?php
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
?>
<style>
.election_module{
	background:#fff9eb;
	float:left;
	margin-top:1%;
	padding:5px;
	width:100%;
	border: 1px solid #e4e4e4;
}
.election_module_col{
	padding:0;
}
.election_module_col img:nth-of-type(1){ box-shadow: 1px 1px 1px 1px #a29b9b;}
.split-25,.split-75{float:left;}
.split-25{width:25%;}
.split-75{width:75%;padding-left: 6px;}
.split-75 h5{font-size: 10.5px;margin: 0;}
.split-75 h4{margin: 0;margin-top: 3px;font-size: 14px;font-weight: bold;}
.split-75 h4 span{font-weight: normal; font-size: 12px;color: #808080;}
</style>
<fieldset class="FieldTopic"><legend class="topic">RK Nagar bypoll counting Live</legend> </fieldset>
<div class="election_module section_<?php echo $widget_instance_id; ?>">
	<span class="text-center" style="float:left;width:100%;"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></span>
</div>
<script>

function elec_response(){
setTimeout(function(){
$.ajax({
	type:'post',
	cache:false,
	url:'<?php echo BASEURL ?>user/commonwidget/fetchelectionmodule',
	data:{'widgetinstance':'<?php echo $widget_instance_id; ?>'},
	dataType:'html',
	success:function(result){
		$('.section_<?php echo $widget_instance_id; ?>').html(result);
		elec_response();
	}
});

}, 4000);
}
elec_response();
</script>
