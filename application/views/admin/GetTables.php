
<?php
function ColorCode($Name){
	if($Name=='SAD-BJP'){
		echo '#f97d09';
	}else if($Name=='BJP'){
		echo '#f97d09';
	}else if($Name=='BSP'){
		echo '#22409a';
	}else if($Name=='SP + CONG' || $Name=='SP - CONG'  || $Name=='SP' || $Name=='CONG'){
		echo '#138808';
	}else if($Name=='Left' || $Name=='LEFT'){
		echo '#ff0000';
	}else if($Name=='AAP'){
		echo '#CCCC99';
	}else if($Name=='OTHERS'){
		echo '#CCCCCC';
	}else{
		echo '#000';
	}
}
foreach($data as $View):
	$JsonTable  =   json_decode($View->table_properties,true);
	$JsonTable  =   $JsonTable['data'];
	$Percentage1=($JsonTable[0]['field2']/$View->total)/100;
	$Percentage2=($JsonTable[1]['field2']/$View->total)/100;
	$Percentage3=($JsonTable[2]['field2']/$View->total)/100;
	$Percentage4=($JsonTable[3]['field2']/$View->total)/100;
	?>
		<div class=" chart-svg chart_<?php print $View->tid ?>" id="chart_<?php print $View->tid ?>"></div>
		<script>
Highcharts.chart('chart_<?php print $View->tid ?>', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php print $View->table_name ?>'
    },
    tooltip: {
        pointFormat: '<b>{point.percentage:.1f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} ',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: '',
        colorByPoint: true,
        data: [{
            name: '<?php print $JsonTable[0]['field1'] ?>',
            y: <?php print $Percentage1 ?>,
			color:'<?php  ColorCode($JsonTable[0]['field1'] ) ?>'
        }, {
            name: '<?php print $JsonTable[1]['field1'] ?>',
            y: <?php print $Percentage2 ?>,
            sliced: true,
            selected: true,
			color:'<?php  ColorCode($JsonTable[1]['field1'] ) ?>'
        }, {
            name: '<?php print $JsonTable[2]['field1'] ?>',
            y: <?php print $Percentage3 ?>,
			color:'<?php  ColorCode($JsonTable[2]['field1'] ) ?>'
        }, {
            name: '<?php print $JsonTable[3]['field1'] ?>',
            y: <?php print $Percentage4 ?>,
			color:'<?php  ColorCode($JsonTable[3]['field1'] ) ?>'
        }]
    }]
});
</script>
	<?php
endforeach;


?>
<div class="chart-content">
	<span class="party" style="background:<?php  ColorCode($JsonTable[0]['field1'] ) ?>;"></span>
	<div class="chart-title" style="color:<?php  ColorCode($JsonTable[0]['field1'] ) ?>;"><?php print $JsonTable[0]['field1'] ?></div>
	<span class="party" style="background:<?php  ColorCode($JsonTable[1]['field1'] ) ?>;"></span>
	<div  class="chart-title" style="color:<?php  ColorCode($JsonTable[1]['field1'] ) ?>;"><?php print $JsonTable[1]['field1'] ?></div>
	<span class="party" style="background:<?php  ColorCode($JsonTable[2]['field1'] ) ?>;"></span>
	<div  class="chart-title" style="color:<?php  ColorCode($JsonTable[2]['field1'] ) ?>;"><?php print $JsonTable[2]['field1'] ?></div>
	<span class="party" style="background:<?php  ColorCode($JsonTable[3]['field1'] ) ?>;"></span>
	<div  class="chart-title" style="color:<?php  ColorCode($JsonTable[3]['field1'] ) ?>;"><?php print $JsonTable[3]['field1'] ?></div>
</div>

