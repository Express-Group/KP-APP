<style>
.rasi-title{font-size: 2.2rem;font-weight: 700;color: #f1b319;text-align: center;}
.common-rasi{position: relative;}
.common-rasi p ,.custom_predictions p{font-size: 1.6rem;}
.rasi-cover{float: left;}
.rais-img, .rasi-cover{height: 145px;width: 145px;}
.rais-img{background-image: url(<?php echo image_url; ?>images/FrontEnd/images/rasi/rasi.png);}
.numerology-img, .rais-img{background-repeat: no-repeat;position: absolute;top: 0;float: left;left: 0;}
span.rais-img.rasi_mesham{background-position:-453px -146px;}
span.rais-img.rasi_rishabam{background-position:-454px -6px;}
span.rais-img.rasi_midhunam{background-position:-298px -149px;}
span.rais-img.rasi_kadagam{background-position:0 -294px;}
span.rais-img.rasi_simmam{background-position:-148px -294px;}
span.rais-img.rasi_kanni{background-position:-450px -293px;}
span.rais-img.rasi_viruchigam{background-position:-297px -291px;}
span.rais-img.rasi_dhanusu{background-position:-146px -150px;}
span.rais-img.rasi_magaram{background-position:-147px -6px;}
span.rais-img.rasi_kumbam{background-position:0 -148px;}
span.rais-img.rasi_menam{background-position:-300px -6px;}
.custom_predictions{margin-top: 20px;width: 100%;float: left;}
.custom_heading{background-color: #f3b924!important;padding: 1px 15px;}
.panel-default, .panel-group .panel-heading+.panel-collapse>.panel-body{border-top: 1px solid #ddd;}
</style>
<?php
$widget_bg_color = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];

$param = $content['page_param']; //parent id of rasi palangal
//echo 'hfhfhfhfh';exit;

$monthNum = date ("m"); 
$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
//echo $monthName;
//$raasi_month = $this->db->query("select * from rasi_monthly_predictions where section_id='".$param."' ")->result_array();
//print_r($raasi_month);exit;
$SectionDetails = get_section_by_id($param);
//print_r($SectionDetails);exit;

if(isset($SectionDetails)) {
$rasi_id = $SectionDetails['Section_id'];
$rasi_name = $SectionDetails['Sectionname'];
}

if(isset($rasi_id) && $rasi_id != '') {
	//echo 'aa';exit;
	
	switch($rasi_name) {
		case 'ಮೇಷ':
			$ClassName = "rasi_mesham";
		break;
		case 'ವೃಷಭ':
			$ClassName = "rasi_rishabam";
		break;
		case 'ಧನು':
			$ClassName = "rasi_dhanusu";
		break;
		case 'ಕರ್ಕಟಕ':
			$ClassName = "rasi_kadagam";
		break;
		case 'ಕನ್ಯಾ':
		$ClassName = "rasi_kanni";
		break;
		case 'ಕುಂಭ':
			$ClassName = "rasi_kumbam";
		break;
		case 'ಮಕರ':
			$ClassName = "rasi_magaram";
		break;
		case 'ಮಿಥುನ':
			$ClassName = "rasi_midhunam";
		break;
		case 'ಸಿಂಹ':
			$ClassName = "rasi_simmam";
		break;
		case 'ತುಲಾ':
			$ClassName = "rasi_thulam";
		break;
		case 'ವೃಶ್ಚಿಕ':
			$ClassName = "rasi_viruchigam";
		break;
		case 'ಮೀನ':
			$ClassName = "rasi_menam";
		break;
		default:
			$ClassName = "rasi_mesham";
		break;
	}
	
	
$astrology_results = astrology_list(@$rasi_id,$monthName);
//print_r($astrology_results);exit;

?>
<div class="rasi-full">
  <h4 class="rasi-title"><?php echo @$rasi_name; ?></h4>
  <div class="common-rasi"> 
  	<div class="rasi-cover">
       <span class="rais-img <?php echo $ClassName; ?>"></span>
    </div>
    <!--<p>20 March – 19 April</p>-->
   <?php echo $astrology_results['general_details']; ?>
  </div>
</div>
  
 <div class="custom_predictions">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading custom_heading">
                <h4 class="rasi-title rasi-custom" style="text-align: left;" type="today">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">ಇಂದು</a>
                   
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                	<div class="accordion_content">
                     <?php echo $astrology_results['daily_details']; ?>
                    </div>
                </div>
            </div>
        </div>
		<?php if($astrology_results['weekly_details']!=''): ?>
        <div class="panel panel-default">
            <div class="panel-heading custom_heading">
                <h4 class="rasi-title rasi-custom"  style="text-align: left;" type="weekly">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">ಈ ವಾರ</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                	<div class="accordion_content">
                    <?php echo $astrology_results['weekly_details']; ?>
                    </div>
                </div>
            </div>
        </div>
		<?php endif; ?>
		<?php if($astrology_results['monthly_details']!=''): ?>
        <div class="panel panel-default">
            <div class="panel-heading custom_heading">
                <h4 class="rasi-title rasi-custom"  style="text-align: left;" type="monthly">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">ಈ ತಿಂಗಳು</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                	<div class="accordion_content">
					 <?php echo $astrology_results['monthly_details']; ?>
                	 <?php echo $astrology_results['tamil_monthly_details']; ?>
                    </div>
                </div>
            </div>
        </div>
		<?php endif; ?>
		<?php if($astrology_results['yearly_details']!=''): ?>
        <div class="panel panel-default">
            <div class="panel-heading custom_heading">
                <h4 class="rasi-title rasi-custom"  style="text-align: left;" type="yearly">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">ಈ ವರ್ಷ</a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse">
                <div class="panel-body">
                	<div class="accordion_content">
                     <?php echo $astrology_results['yearly_details']; ?>
                    </div>
                </div>
            </div>
        </div>
		<?php endif; ?>
    </div>
	
</div>
<?php } ?>

<script>

$('.rasi-title').on('click',function(){
	var type=$(this).attr('type');
	window.history.pushState("","",'?qe='+type);
});
$(document).ready(function(){
	var query = "<?php print $_GET['qe'] ?>";
	if(query!=''){
		
		$('.rasi-title').each(function(index){
			if($(this).attr('type')==query){
				
				$(this).find('a').trigger('click');
			}
			
		});
	}
	
});
</script>
