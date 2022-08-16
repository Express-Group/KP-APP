<style>
.dynamic-table-rendered-container{padding-left:15px;}
.dynamic-table-width{width:18.9%;float:left;padding-right:0;padding-left:0;margin-right:1%;}	
.table-header-wrapper{background:#000;color:#fff;}
.table-header-wrapper-second{color:#fff; text-align:right;}
.table-header-title{background: #7a0025;color: #fff;font-weight: 700;}
.common-table-election tbody td{padding:5px !important;font-size:14px;}
.common-table-election tr td:nth-child(2), .common-table-election tr td:nth-child(3), .common-table-election tr td:last-child{ text-align:center;}
.span_bg{ margin:0; padding:0; position:absolute; z-index:99;float:left; width:100%; height:100%; opacity:0.4;top: 38px;left: 62px; }
.common-table-election>tbody>tr>td, .common-table-election>tbody>tr>th, .common-table-election>tfoot>tr>td, .common-table-election>tfoot>tr>th, .common-table-election>thead>tr>td, .common-table-election>thead>tr>th{border:unset;}
.common-table-election{border: 1px solid #143b5a;}
.table-body-content{background: #ebebeb;}
@media only screen and (max-width: 768px){
	.dynamic-table-rendered-container{padding-right: 15px;}
	.dynamic-table-width{width: 100%;margin-right: 0%;}
}
</style>

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
$max_article            = $content['show_max_article'];
$render_mode            = $content['RenderingMode'];
/*----widgetbconfig ends here------*/
$Template .='<div class="row dynamic-table-rendered-container"><span class="col-md-12 text-center" style="font-size: 22px;"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i></span>';
$Template .='</div>';
echo $Template;
?>
<script>
$(document).ready(function(){
	$.ajax({
		type:'post',
		cache:false,
		url:'<?php print HOMEURL ?>user/commonwidget/GetDynamicTables',
		success:function(result){
			$('.dynamic-table-rendered-container').html(result);
		}
	});

});
/* setInterval(function(){
console.log(1);
$.ajax({
		type:'post',
		cache:false,
		url:'<?php print HOMEURL ?>user/commonwidget/GetDynamicTables',
		success:function(result){
			$('.dynamic-table-rendered-container').html(result);
		}
	});
},15000); */
</script>