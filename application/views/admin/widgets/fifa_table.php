<style>
.fifa_score_table a:hover,a:focus{text-decoration: none;outline: none;}
.fifa_score_table .nav-tabs {position: relative;}
.fifa_score_table .nav-tabs > li:hover:after,.fifa_score_table .nav-tabs .active:after{    content: "";border-top: 10px solid #09155e; border-left: 9px solid transparent;border-right: 9px solid transparent;position: absolute;top:32px;right:28%;}
.fifa_score_table .nav-tabs > li > a{margin-right: 1px;border-radius: 0px;background: #efefec;  padding:6px 12px ;color:#000;}
.fifa_score_table .nav-tabs > li > a:hover{border-color:transparent;background: #09155e;transition:0.3s ease;color:#fff !important;}
.fifa_score_table .nav-tabs > li.active > a,.fifa_score_table .nav-tabs > li.active > a:focus,.fifa_score_table .nav-tabs > li.active > a:hover{background:#09155e;color:#fff;}
.fifa_score_table .tab-content > .tab-pane{border: 1px solid #c4c4c4;border-top: 0px none; padding: 20px 10px 0px 10px;line-height: 22px;}
@media only screen and (max-width: 360px){
	fifa_score_table .nav-tabs > li > a{margin-right: 1px;border-radius: 0px;background: #efefec;
		padding: 6px 13px;color: #000; }
}
</style>
<?php
$widget_instance_id	 	= $content['widget_values']['data-widgetinstanceid'];
?>
<div class="fifa_score_table margin-top-10 margin-bottom-15" id="fifa-<?php echo $widget_instance_id; ?>"></div>
<script>
$(document).ready(function(e){
	$.ajax({
		type:'post',
		url : '<?php echo base_url(); ?>user/commonwidget/fifa_data',
		cache:false,
		success:function(result){
			$('#fifa-<?php echo $widget_instance_id; ?>').html(result);
		},
		error:function(err,errcode){
			console.log('Something went wrong..please try again');
		}
	});
});
</script>

