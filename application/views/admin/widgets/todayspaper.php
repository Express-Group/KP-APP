<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<style>
.todayspaper{
	margin-top:2%;
}

.todayspaper .section-active{
	background: #337ab7;
}
.todayspaper .section-active a{
	color: #fff;
}
.todayspaper .nomore{
    margin-top: 3%;
    float: left;
    width: 100%;s
}
.todayspaper .date{
	width:33%;
	float:right;
	margin-top: 0px;
}
.todayspaper-title{
	float: left;
    margin-top: 6px;
    margin-bottom: 0;
	font-size: 19px;
}
.todayspaper-container{
    float: left;
    background: #09155e;
    padding: 5px;
    color: #fff;
    border-bottom: 2px solid #09155e;
}
.todayspaper .add-on{
	background: #eceaea;
}
.todayspaper-section,.todayspaper-article{
	float:left;
	width:100%;
	padding: 5px;
}
.todayspaper-article{
	padding-top: 2%;
    border-top: 3px solid #09155e;
    margin-top: 2%;
   
}
.todayspaper-section h5{ 
	float: left;
    margin-right: 7px;
    border: 1px solid #c5c5c5;
    padding: 5px;
    color: #337ab7;
    cursor: pointer;
    font-weight: bolder;
    font-size: 12px;
}
.todayspaper-section a{ 
	color:#000;
}
.todayspaper-section .badge{
	background:#09155ea3;
}
.todayspaper-list{
	width: 100%;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
	background: whitesmoke;
    padding: 5px;
}
.todayspaper-article h4{ font-size:14px;}
</style>
<?php
$widget_custom_title 	=	$content['widget_title'];
$widget_section_url     = $content['widget_section_url'];
$widgetsectionid 		= 	$content['sectionID'];
$max_article            = $content['show_max_article'];
$CI = &get_instance();
extract($_GET);
$sectionArticleCount = 'NOENTRY';
$sectiontemplate ='';
$SectionName = (isset($sectionname) && $sectionname!='')? str_replace('_',' ',$sectionname) : '';
$currentdate = date('Y-m-d');
if(isset($date) && $date!=''){
$newdate = explode('-',$date);
$startdate = $newdate[2].'-'.$newdate[0].'-'.$newdate[1].' 00:00:00';
$enddate = $newdate[2].'-'.$newdate[0].'-'.$newdate[1].' 23:59:59';
$responsedate = str_replace('-','/',$date);
}else{
$startdate = $currentdate.' 00:00:00';
$enddate = $currentdate.' 23:59:59';
$responsedate = date('m/d/Y');
}
$this->live_db = $CI->load->database("live_db",true);
$sectionlist = $this->live_db->query("SELECT section_name, count(tid) as article_count FROM todayspaper WHERE section_name is not null AND status='P' AND publish_start_date BETWEEN '".$startdate."' AND '".$enddate."' GROUP BY section_name order by section_id ASC")->result();
foreach($sectionlist as $section):
	$activeclass='';
	if($section->section_name == $SectionName){$sectionArticleCount=$section->article_count;  $activeclass='section-active';}
	$sectiontemplate .='<h5 class="'.$activeclass.'"><a href="?sectionname='.str_replace(' ','_',$section->section_name).'&date='.str_replace('/','-',$responsedate).'">'.$section->section_name.' <span class="badge">'.$section->article_count.'</span></a></h5>';
endforeach;
$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
$perpage = 10;
if($sectionArticleCount=='NOENTRY' && $SectionName==''){
	$totalrows = $this->live_db->query("SELECT tid  FROM todayspaper WHERE status='P' AND publish_start_date BETWEEN '".$startdate."' AND '".$enddate."'")->num_rows();
	$article = $this->live_db->query("SELECT tid,content_id,title,section_name,url,publish_start_date  FROM todayspaper WHERE status='P' AND publish_start_date BETWEEN '".$startdate."' AND '".$enddate."' ORDER BY publish_start_date DESC LIMIT ".$row." , ".$perpage." ")->result();
	$pagetype=1;
}else{
	$totalrows = $sectionArticleCount;
	$article = $this->live_db->query("SELECT tid,content_id,title,section_name,url,publish_start_date  FROM todayspaper WHERE status='P' AND  section_name='".$SectionName."' AND publish_start_date BETWEEN '".$startdate."' AND '".$enddate."' ORDER BY publish_start_date DESC LIMIT ".$row." , ".$perpage." ")->result();
	$pagetype=2;
}
$config=['base_url'=>$widget_section_url,'total_rows'=>$totalrows,'per_page'=>$perpage,'num_links'=>5,'page_query_string'=>TRUE,'reuse_query_string'=>FALSE,'suffix'=>'&sectionname='.$SectionName.'&date='.str_replace('/','-',$responsedate),'cur_tag_open'=>'<a class="active">','cur_tag_close'=>'</a>','use_page_numbers'=>TRUE,'first_url'=>$widget_section_url,'first_link'=>FALSE,'last_link'=>FALSE];
$this->pagination->initialize($config);
$pagination=$this->pagination->create_links();
$template ='';

$template .='<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 todayspaper"><div class="todayspaper-container">';
$template .='<h3 class="todayspaper-title">'.$widget_custom_title.'</h3>';
$template .=' <div class="input-group input-append date" id="datePicker"><input type="text" class="form-control" name="date"  value="'.$responsedate.'" id="todayspaperdate"/><span class="input-group-addon add-on"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span></div></div>';
if(count($sectionlist) > 0){
	$template .='<div class="todayspaper-section">';
	$template .=$sectiontemplate;
	$template .='</div>';
}
$baseurl = base_url();
if(count($article) > 0){
	$template .='<div class="todayspaper-article">';
	foreach($article as $articlelist):
		$title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$articlelist->title);
		$publisheddate    = date('jS F Y', strtotime($articlelist->publish_start_date));
		$template .='<div class="result-section todayspaper-list">';
		$template .='<h4><a href="'.$baseurl.$articlelist->url.'"><span><i class="fa fa-angle-right" aria-hidden="true"></i></span> '.$title.'</a></h4>';
		$template .='<date>'.$publisheddate.'</date>';
		$template .='</div>';
	endforeach;
	$template .='<div class="pagina">'.$pagination.'</div>';
	$template .='</div>';
}else{
	$template .='<h4 class="text-center nomore">No more articles found</h4>';	
}
$template .='</div></div>';
echo $template;
?>
<script>
var section_name = "<?php echo  $sectionname ?>";
var widget_url = "<?php echo $widget_section_url ?>";
$(document).ready(function() {
     $('#datePicker').datepicker({
        autoclose: true,
		startDate: '01/19/2018',
		endDate: '<?php echo date('m/d/Y'); ?>',
        format: 'mm/dd/yyyy'
      });
	  $('#todayspaperdate').on('change',function(){
		var date = $(this).val();
		if(date!=''){
			date = date.replace(/\//g,'-');
			//window.location.href = widget_url+'?sectionname='+section_name+'&date='+date;
			window.location.href = widget_url+'?date='+date;
		}
	  })
});
</script>