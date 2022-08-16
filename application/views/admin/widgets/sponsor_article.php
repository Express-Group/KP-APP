<?php
$widgetInstanceId  =  $content['widget_values']['data-widgetinstanceid'];
?>
<style>
.top_news1 {
    width: 100%;
    border: 1px solid #eee;
	margin-top: 10px;
	background-color: #d7f1fd;
}
.top_news1 .topic1 {
    margin-bottom: 5px !important;
    margin-top: 5px;
	font-size:10px;
	font-weight:bold;
	color:#fff;
	padding: 3px 3px;
}


div#sponsor_outer_layer {
    border-top: 0px solid #337ab7;
}

.common_p1 p {
    border-bottom: 1px solid #ccc;
    margin: 5px 0 0 20px;
    padding: 6px 0;
    position: relative;
}
.common_p1 p i {
    height: 29px;
    padding: 6px 10px 0 4px;
    position: absolute;
    left: -15px;
}

.common_p1 p:last-child {
    border: none;
    padding-bottom: 2px;
}
</style>
<div class="top_news1 common_p1 margin-bottom-15" id="sponsor_layer" style="display:none;">
	<h4 class="topic1"><span style="background-color:#337ab7;padding:2px;">Sponsored Articles</span></h4>
	<div id="sponsor_outer_layer">
	</div>
</div>
<script>
$(document).ready(function(){
	$.ajax({
		type:"post",
		cache:false,
		url:"<?php echo BASEURL ?>user/commonwidget/getsponsorarticles",
		success:function(result){
			
			if (result !='')
			{
				
			$("#sponsor_layer").attr("style", "display:block");
			$('#sponsor_outer_layer').html(result);
			}
			else
			{
				
		    $("#sponsor_layer").attr("style", "display:none");
			}
				
		},
		error:function(errcode,errstatus){
			//alert('erro');
		}
	});
});
</script>