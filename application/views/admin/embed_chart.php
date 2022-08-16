<?php if($error==false):
$randomnumber = rand(100,10000);
$scriptname = $wid.$randomnumber;
?>
<!DOCTYPE html>
<html lan="en">
	<head>
		<meta charset="UTF-8">
		<title>PieChart</title>
		<style>
		.cht_lgd{text-align: center;margin-left: 10px;float:left;width:calc(100% - 10px);display:flex;margin-bottom: 10px;}
		.cht_lgd div{font-size:76%;margin-top:1px;margin-right:5px;color:#515151;font-weight:bold;}
		.cht_lgd div span{display:inline-block;width:13px;border-radius: 50%;}
		.cht_lgd1{float: left;margin-bottom: auto;position: absolute;bottom: 11%;left: 14%;font-weight: bold;}
		.cht_lgd1 div{font-size:76%;margin-top:1px;margin-right:5px;margin-bottom: 10px;color:#515151;}
		.cht_lgd1 div span{display:inline-block;width:4px;}
		.site-logo{position: absolute;bottom: 6%;right: 0;opacity: 0.1;width: 43%;}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body style="position:relative;">
		<h4 class="text-center chart_title" id="charttitle_<?php echo $scriptname; ?>" style="margin:0;font-size: 14px;font-weight: bold;width: 100%;text-align: center;"></h4>
		<canvas id="chart_<?php echo $scriptname; ?>"></canvas>
		<div id="chartlegend_<?php echo $scriptname; ?>" class="cht_lgd"></div>
		<img class="site-logo" src="http://images.newindianexpress.com/images/FrontEnd/images/NIE-logo21.jpg">
		<script>var i,p,c<?php echo $scriptname ?>,fe,chart,m,l;i = 'chart_<?php echo $scriptname ?>';p = $($('#'+i).parent()).parent().width();c<?php echo $scriptname ?> = document.getElementById(i);c<?php echo $scriptname ?>.width = p;c<?php echo $scriptname ?>.height = (c<?php echo $scriptname ?>.width /2);fe = (typeof drawPieSlice === 'function') ;if(!fe){function drawPieSlice(ctx,centerX, centerY, radius, startAngle, endAngle, color, widgettype ){		ctx.globalAlpha = 1;ctx.fillStyle = color;ctx.beginPath();ctx.moveTo(centerX , centerY);ctx.arc(centerX	, centerY, radius-10, startAngle, endAngle , false);ctx.fill(); if(widgettype==1){ctx.moveTo(10, centerY);ctx.lineTo((centerX*2)-10, centerY );}ctx.strokeStyle='#ddd';ctx.stroke();}}chart<?php echo $scriptname ?> = function(options){this.options = options;this.canvas = options.canvas;this.ctx = this.canvas.getContext("2d");this.colors = options.colors;this.total_value = options.totalvalue;this.widgettype = options.type;this.canvas.style.cursor="pointer";this.draw<?php echo $scriptname ?> = function(){ 	var total_value = 0;var color_index = 0;for (var categ in this.options.data){  var val = this.options.data[categ]; total_value += val;}var start_angle = 1 * Math.PI; var legendHTML='';for (categ in this.options.data){ val = this.options.data[categ];if(this.widgettype==1){ var slice_angle = 1 * Math.PI * val / this.total_value;drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height,this.canvas.height,start_angle,start_angle + slice_angle,this.colors[color_index%this.colors.length] , this.widgettype);}else{var slice_angle = 2 * Math.PI * val / this.total_value;drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height/2,Math.min(this.canvas.width/2,this.canvas.height/2),start_angle, start_angle + slice_angle,this.colors[color_index%this.colors.length] ,this.widgettype);}if(this.widgettype==1){var pieRadius = this.canvas.height;}else{var pieRadius = Math.min(this.canvas.width/2,this.canvas.height/2);}		var labelX = this.canvas.width/2 + (pieRadius / 2) * Math.cos(start_angle + slice_angle/2);if(this.widgettype==1){var labelY = this.canvas.height+ (pieRadius / 2) * Math.sin(start_angle + slice_angle/2);}else{var labelY = this.canvas.height/2 + (pieRadius / 2) * Math.sin(start_angle + slice_angle/2);}	var labelText = Math.round(100 * val / this.total_value);this.ctx.fillStyle = "#fff";this.ctx.fontSize  = "20px";this.ctx.fontFamily  = "Roboto";		this.ctx.fillText(labelText+"%", labelX-10,labelY);legendHTML += "<div><span style='background-color:"+this.colors[color_index]+";'>&nbsp;</span> "+categ+"</div>";start_angle += slice_angle;color_index++;}if(total_value!=this.total_value){if(this.widgettype==1){var point = this.total_value - total_value;	slice_angle = 1 * Math.PI * point / this.total_value;drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height,this.canvas.height,start_angle,start_angle + slice_angle,options.defaultcolor, this.widgettype);}else{var point = this.total_value - total_value;	slice_angle = 2 * Math.PI * point / this.total_value;drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height/2,Math.min(this.canvas.width/2,this.canvas.height/2),start_angle,start_angle + slice_angle,options.defaultcolor , this.widgettype);}}if (this.options.legend){if(this.widgettype==0){$(this.options.legend).removeClass('cht_lgd').addClass('cht_lgd1');}this.options.legend.innerHTML = legendHTML;}document.getElementById('charttitle_<?php echo $scriptname; ?>').innerHTML = total_value +'/' +this.total_value;}}; var load<?php echo $scriptname ?> = 0;function run<?php echo $scriptname ?>(){$.ajax({	type:'post',cache:false,data:{'wid' : '<?php echo $wid ?>'},url:'<?php echo BASEURL ?>user/commonwidget/piechart_data',	dataType:'json',success:function(result){if(!result.error){	var l<?php echo $scriptname ?> = document.getElementById("chartlegend_<?php echo $scriptname; ?>");	m = new chart<?php echo $scriptname ?>({canvas:c<?php echo $scriptname ?>,data:JSON.parse(result.data),colors:result.color,totalvalue : result.total,defaultcolor:'#fff',legend:l<?php echo $scriptname ?> ,type : result.type});m.draw<?php echo $scriptname ?>();}},error:function(errstatus , err){}	});	clearInterval(load<?php echo $scriptname ?>);load<?php echo $scriptname ?> = setInterval('run<?php echo $scriptname ?>()',15000);}run<?php echo $scriptname ?>();</script>
	</body>
</html>

<?php else: ?>
<!DOCTYPE html>
<html lan="en">
	<head>
		<meta charset="UTF-8">
		<title>PieChart</title>
	</head>
	<body>
		<h5 style="text-align:center;">No chart found</h5>
	</body>
</html>
<?php endif; ?>