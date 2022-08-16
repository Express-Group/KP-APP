<?php
$widgetinstanceid = $content['widget_values']['data-widgetinstanceid'];
?>
<style>
.cht_lgd{text-align: center;margin-left: 10px;float:left;width:calc(100% - 10px);display:flex;margin-bottom: 10px;}
.cht_lgd div{font-size:76%;margin-top:1px;margin-right:5px;color:#515151;font-weight:bold;}
.cht_lgd div span{display:inline-block;width:13px;border-radius: 50%;}

.cht_lgd1{float: left;margin-bottom: auto;position: absolute;bottom: 11%;left: 14%;font-weight: bold;}
.cht_lgd1 div{font-size:76%;margin-top:1px;margin-right:5px;margin-bottom: 10px;color:#515151;}
.cht_lgd1 div span{display:inline-block;width:4px;}
</style>
<h4 class="text-center chart_title" id="charttitle_<?php echo $widgetinstanceid; ?>" style="margin:0;font-size: 14px;font-weight: bold;"></h4>
<canvas id="chart_<?php echo $widgetinstanceid; ?>"></canvas>
<div id="chartlegend_<?php echo $widgetinstanceid; ?>" class="cht_lgd"></div>
<script>
var i,p,c<?php echo $widgetinstanceid ?>,fe,chart,m,l;
i = 'chart_<?php echo $widgetinstanceid ?>';
p = $($('#'+i).parent()).parent().width();
c<?php echo $widgetinstanceid ?> = document.getElementById(i);
c<?php echo $widgetinstanceid ?>.width = p;
c<?php echo $widgetinstanceid ?>.height = (c<?php echo $widgetinstanceid ?>.width /2);
fe = (typeof drawPieSlice === 'function') ;
if(!fe){
	function drawPieSlice(ctx,centerX, centerY, radius, startAngle, endAngle, color, widgettype ){
		ctx.globalAlpha = 1;
		ctx.fillStyle = color;
		ctx.beginPath();
		ctx.moveTo(centerX , centerY);
		ctx.arc(centerX	, centerY, radius-10, startAngle, endAngle , false);
		ctx.fill(); 
		if(widgettype==1){
			ctx.moveTo(10, centerY);
			ctx.lineTo((centerX*2)-10, centerY );			
		}
		ctx.strokeStyle='#ddd';
		ctx.stroke();
	}
}

chart<?php echo $widgetinstanceid ?> = function(options){
    this.options = options;this.canvas = options.canvas;this.ctx = this.canvas.getContext("2d");this.colors = options.colors;this.total_value = options.totalvalue;this.widgettype = options.type;
	this.canvas.style.cursor="pointer";
    this.draw<?php echo $widgetinstanceid ?> = function(){ 
		var total_value = 0;var color_index = 0;
		for (var categ in this.options.data){  var val = this.options.data[categ]; total_value += val;}
		var start_angle = 1 * Math.PI; 
		var legendHTML='';
        for (categ in this.options.data){
            val = this.options.data[categ];
			if(this.widgettype==1){
				   var slice_angle = 1 * Math.PI * val / this.total_value;
				 drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height,/*Math.min(this.canvas.width/2,this.canvas.height/2),*/this.canvas.height,start_angle,              start_angle + slice_angle,this.colors[color_index%this.colors.length] , this.widgettype);
			}else{
				  var slice_angle = 2 * Math.PI * val / this.total_value;
				drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height/2,Math.min(this.canvas.width/2,this.canvas.height/2),start_angle, start_angle + slice_angle,this.colors[color_index%this.colors.length] ,this.widgettype);
			}
           if(this.widgettype==1){
			var pieRadius = this.canvas.height;
		   }else{
			 var pieRadius = Math.min(this.canvas.width/2,this.canvas.height/2);
		   }
			var labelX = this.canvas.width/2 + (pieRadius / 2) * Math.cos(start_angle + slice_angle/2);
			if(this.widgettype==1){
				var labelY = this.canvas.height+ (pieRadius / 2) * Math.sin(start_angle + slice_angle/2);
			}else{
				var labelY = this.canvas.height/2 + (pieRadius / 2) * Math.sin(start_angle + slice_angle/2);
			}
			var labelText = Math.round(100 * val / this.total_value);
			this.ctx.fillStyle = "#fff";
			this.ctx.fontSize  = "20px";
			this.ctx.fontFamily  = "Roboto";
			this.ctx.fillText(labelText+"%", labelX-10,labelY);
			legendHTML += "<div><span style='background-color:"+this.colors[color_index]+";'>&nbsp;</span> "+categ+"</div>";
            start_angle += slice_angle;
            color_index++;
        }
		if(total_value!=this.total_value){
			
			if(this.widgettype==1){
				var point = this.total_value - total_value;
				slice_angle = 1 * Math.PI * point / this.total_value;
				drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height,this.canvas.height,start_angle,start_angle + slice_angle,options.defaultcolor, this.widgettype);
			}else{
				var point = this.total_value - total_value;
				slice_angle = 2 * Math.PI * point / this.total_value;
				drawPieSlice(this.ctx,this.canvas.width/2,this.canvas.height/2,Math.min(this.canvas.width/2,this.canvas.height/2),start_angle,start_angle + slice_angle,options.defaultcolor , this.widgettype);
			}
		}
		if (this.options.legend){
			if(this.widgettype==0){
				$(this.options.legend).removeClass('cht_lgd').addClass('cht_lgd1');
			}
			this.options.legend.innerHTML = legendHTML;
		}
		document.getElementById('charttitle_<?php echo $widgetinstanceid; ?>').innerHTML = total_value +'/' +this.total_value;
 
    }
	
	
	
}
var load<?php echo $widgetinstanceid ?> = 0;
function run<?php echo $widgetinstanceid ?>(){
	$.ajax({
		type:'post',
		cache:false,
		data:{'wid' : '<?php echo $widgetinstanceid ?>'},
		url:'<?php echo BASEURL ?>user/commonwidget/piechart_data',
		dataType:'json',
		success:function(result){
			console.log(result);
			if(!result.error){
				var l<?php echo $widgetinstanceid ?> = document.getElementById("chartlegend_<?php echo $widgetinstanceid; ?>");
				m = new chart<?php echo $widgetinstanceid ?>({canvas:c<?php echo $widgetinstanceid ?>,data:JSON.parse(result.data),colors:result.color,totalvalue : result.total,defaultcolor:'#fff',legend:l<?php echo $widgetinstanceid ?> ,type : result.type});
				m.draw<?php echo $widgetinstanceid ?>();
			}
		},
		error:function(errstatus , err){
			//alert('error');	
		}
	});
	clearInterval(load<?php echo $widgetinstanceid ?>);
	load<?php echo $widgetinstanceid ?> = setInterval('run<?php echo $widgetinstanceid ?>()',15000);
}
run<?php echo $widgetinstanceid ?>();

</script>