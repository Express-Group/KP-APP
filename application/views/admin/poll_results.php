<?php $script_url = image_url; ?>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/w2ui-fields-1.0.min.js"></script>-->

<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">-->
<link href="<?php echo $script_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<!--Pie chats-->
<script src="<?php echo $script_url; ?>js/amcharts.js"></script>
<script src="<?php echo $script_url; ?>js/pie.js"></script>
<script src="<?php echo $script_url; ?>js/light.js"></script>
     <script>
   var chart = AmCharts.makeChart( "chartdiv", {
  "type": "pie",
  "theme": "light",
  "dataProvider": [
   <?php if($fetch_details['OptionText1']!="") {  ?>
   {
    "country": "<?php echo $fetch_details['OptionText1']; ?>",
    "value": <?php echo $sel_poll_result['textvalue1']; ?>
  }, 
  <?php }  if($fetch_details['OptionText2']!="") {?>
  {
    "country": "<?php echo $fetch_details['OptionText2']; ?>",
    "value": <?php echo $sel_poll_result['textvalue2']; ?>
  }, 
  <?php }  if($fetch_details['OptionText3']!="") {?>
   {
    "country": "<?php echo $fetch_details['OptionText3']; ?>",
    "value": <?php echo $sel_poll_result['textvalue3']; ?>
  }, 
   <?php }  if($fetch_details['OptionText4']!="") {?>
  {
    "country": "<?php echo $fetch_details['OptionText4']; ?>",
    "value": <?php echo $sel_poll_result['textvalue4']; ?>
  },
   <?php }  if($fetch_details['OptionText5']!="") {?>
   {
    "country": "<?php echo $fetch_details['OptionText5']; ?>",
    "value": <?php echo $sel_poll_result['textvalue5']; ?>
  },
   <?php } ?> 
 ],
  "valueField": "value",
  "titleField": "country",
  "outlineAlpha": 0.4,
  "depth3D": 15,
  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
  "angle": 30,
  "export": {
    "enabled": true
  }
} );
jQuery( '.chart-input' ).off().on( 'input change', function() {
  var property = jQuery( this ).data( 'property' );
  var target = chart;
  var value = Number( this.value );
  chart.startDuration = 10;

  if ( property == 'innerRadius' ) {
    value += "%";
  }

  target[ property ] = value;
  chart.validateNow();
} );
    </script>







<div class="Container">
<div class="BodyWhiteBG">

<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper PollResult">
<div class="breadcrumbs"><a href="#">Dashboard</a> &gt; <a href="#">Poll Results</a></div>
 <h2 class="FloatLeft">Poll Results</h2>
</div>
 
<p class="FloatRight save-back">
 <a class="FloatLeft back-bottom" href="<?php echo base_url();?>niecpan/poll_manager/"><i class="fa fa-reply fa-2x"></i></a></p>
 
</div>





<div class="poll_content">
<h3 class="poll_res_topic"><?php  echo $fetch_details['PollQuestion']; ?></h3>
<div class="create_poll">
<div class="text_result">
<?php if(count($sel_poll_result)>0) { ?>
Total vote: <?php echo $sel_poll_result['textvalue1']+$sel_poll_result['textvalue2']+$sel_poll_result['textvalue3']+$sel_poll_result['textvalue4']+$sel_poll_result['textvalue5']; ?>

<?php }  else { ?>
Total vote: 0
<?php } ?>

</div>
<div id="chartdiv"></div>
</div>

</div>  

                          
</div>                       
   </div>
