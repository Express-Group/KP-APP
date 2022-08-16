<style>#niechart svg > g > g:last-child { pointer-events: none; } </style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript"> 
    var res ={};    
    function drawChart() {
	   var colorCodes =[];  
       var data = new google.visualization.DataTable();
       data.addColumn('string', 'Party');
       data.addColumn('number', 'Votes');
	   for($i=0;$i<res.length;$i++){
		
			var colorValues ={};
			data.addRow([res[$i]['name'], parseInt(res[$i]['value'])]);
			colorValues['color'] = '#'+res[$i]['color'];
			colorCodes.push(colorValues);
		}
        var options = {
            legend:  { position: 'bottom', alignment: 'end' },
            title: '',
			pieSliceText: 'percentage',
			pieStartAngle: 90,
			slices: colorCodes,
			pieSliceTextStyle:{color:'white', fontSize:11},
            chartArea:{top:0,width:'75%',height:'75%'},
			is3D: true
        };
        var chart = new google.visualization.PieChart(document.getElementById('niechart'));
        chart.draw(data, options);
             
    }
	var loaderid =0;
	function getchartdetails(){
		$.ajax({
			type:'post',
			url : '<?php echo BASEURL ?>/user/commonwidget/chart_data',
			dataType:'json',
			cache:false,
			success:function(result){
				if(result.hasfile==1){
				res = result.data;
					//drawChart(result.data);
					google.charts.load('current', {'packages':['corechart']});
					google.charts.setOnLoadCallback(drawChart);
				}
			},
			error:function(err,errcode){
				console.log('404');
			}
		});
		clearInterval(loaderid);
		loaderid = setInterval('getchartdetails()',15000);
	}
	getchartdetails();
	
</script>
<p style="font-weight: bold;width: 100%;text-align: center;margin: 0;"><a href="http://www.newindianexpress.com/elections/karnataka-polls-2018/2018/may/15/karnataka-assembly-election-results-2018-heres-the-partywise-vote-share-1815015.html">Partywise Vote Share</a></p>
<div id="niechart"></div>

