<style>.section-strip{font-family:'Roboto Condensed', sans-serif !important;color: #000;font-size: 18px;font-weight: normal;} </style>
<?php
$CI = &get_instance();
$this->live_db = $CI->load->database('live_db',TRUE);
if($content['page_param']!=''){
	$pageId = $content['page_param'];	
}else{
	$widgetInstanceId = $content['widget_values']['data-widgetinstanceid'];
	$PageDetails = 	$this->live_db->query("SELECT Pagesection_id FROM widgetinstance_live WHERE WidgetInstance_id='".$widgetInstanceId."'")->result();
	$pageId = $PageDetails[0]->Pagesection_id;	
}
$SectionDeatils = $this->live_db->query("SELECT Sectionname FROM sectionmaster WHERE Section_id='".$pageId."'")->result();
$SectionName = $SectionDeatils[0]->Sectionname;
if($SectionName!=''):
$SectionName = (strtolower($SectionName)=='nation')?'India' : $SectionName;
echo '<h4 class="section-strip topic">'.$SectionName.' News</h4>';
endif;
?>