<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$is_home                = $content['is_home_page'];
$is_summary_required    = $content['widget_values']['cdata-showSummary'];
$view_mode              = $content['mode'];
$page_parameter         = $content['page_param'];
// widget config block ends  
//search related  terms
$tagname                = '';
$fromdate               = ($this->input->get('fdate') != '') ? $this->input->get('fdate'): "";
$todate                 = ($this->input->get('tdate') != '') ? $this->input->get('tdate'): "";
$sectionid              = "";
$content_type           = ($this->input->get('ctype') != '') ? $this->input->get('ctype'): "1";
$start_at               = ($this->input->get('per_page') != '') ? $this->input->get('per_page'): 0; 
$search_limit           = 15;
if($this->input->get('order_by')=='Title'){
$order_field = 'Title';
}else{
$order_field = 'last_updated_on';
}
if($this->input->get('order_dir') =='Asc'){
$order = 'Desc';
}elseif($this->input->get('order_dir') == 'Desc'){
$order = 'Asc';
}else
{
$order = 'Desc';	
}
$loaddata_from          = '';
$datafrom               = ($this->input->get('datafrom')== 'live') ? "live" : $this->input->get('datafrom');
$sid                    =  ($this->input->get('sid')!= '') ? $this->input->get('sid') : "";
$manual_instance        = $this->input->get('instance');
if(($datafrom == "" || $datafrom=="live" )&& $sid =='')
{
$tag_segment            = $this->uri->segment(2);
if($tag_segment!=''){
	$tag_array              = explode('_', $tag_segment);
	$search_term            = implode(" ", $tag_array);
	$tagname                = $search_term;
	$searchby               = "Tag";
	$type_search            = "All";
}elseif(isset($_GET['home_search']) && $_GET['home_search']!='') 
{
    $searchby          = ($this->input->get('home_search')!='')? $this->input->get('home_search') : "H";
	$search_term       = trim(($this->input->get('search_term')!='')? $this->input->get('search_term') : ""); //$_POST['search_term']
	$type_search       = "All";
}elseif(isset($_GET['searchbtn'])) 
{
    $searchby          = $this->input->get('Searchby');//$_POST['Searchby'];
	$sectionid         = $this->input->get('Search_section');//$_POST['Search_section'];
	$fromdate          = $this->input->get('FromDate');//$_POST['FromDate'];
	$todate            = $this->input->get('ToDate');//$_POST['ToDate'];
	$type_search       = $this->input->get('search_type');//$_POST['search_type'];
	$content_type      = ($this->input->get('content_type')!="All")? $this->input->get('content_type') : "1"; //$_POST['content_type']
	$search_term       = trim(($this->input->get('search_term_txt')!='')? $this->input->get('search_term_txt') : ""); //$_POST['search_term_txt']
}else
{
	$searchby          = ($this->input->get('searchby') != '') ? $this->input->get('searchby'): "H";
	$search_term       = trim(($this->input->get('search_term') != '') ? $this->input->get('search_term'): "");
	$type_search       = ($this->input->get('stype') != '') ? $this->input->get('stype'): 1;

}
if($search_term!=''){
$live_result_contents         = $this->widget_model->get_search_result_data($order_field, $order, $start_at, $search_limit, $fromdate, $todate, $search_term , $searchby, $sectionid, $content_type, $type_search, $datafrom);
//print_r($result_contents);exit;
$live_TotalCount              = $this->widget_model->get_search_result_data($order_field, $order, "" , "", $fromdate, $todate, $search_term , $searchby, $sectionid, $content_type, $type_search, $datafrom);
$TotalCount                   = $live_TotalCount['Search_result'];
$seacrh_result_contents  = $live_result_contents['Search_result'];
//print_r($live_result_contents);exit;
$loaddata_from                = $live_result_contents['year'];
}else
{
$TotalCount              = array();
$seacrh_result_contents  = array();
}
}else if($sid!='')
{
	
	$searchby          = ($this->input->get('searchby') != '') ? $this->input->get('searchby'): "H";
	$search_term       = ($this->input->get('search_term') != '') ? $this->input->get('search_term'): "";
	$type_search       = ($this->input->get('stype') != '') ? $this->input->get('stype'): 1;

$content_type                    = ($this->input->get('cid') != '') ? $this->input->get('cid'): 1;
$datafrom                        = ($datafrom=='')? "live" : $datafrom;//date('Y');
$manual_instance                 = ($datafrom=="live" || $datafrom=="")? $this->input->get('instance') : "";
$search_term_ids                 = "";
$searchby_section                = "";
if($manual_instance!="archive"){
$widget1_instance = $this->input->get('widget1_instance');
$widget1_mode     = $this->input->get('widget1_mode');
$widget1_max      = $this->input->get('widget1_max');
$widget2_instance = $this->input->get('widget2_instance');
$widget2_mode     = $this->input->get('widget2_mode');
$widget2_max      = $this->input->get('widget2_max');
$multiple_contentID = array();
	if($widget1_instance!=''){
	$lead_widget_instance_contents = array();
		if($widget1_mode == "manual")
		{
			$lead_widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget1_instance, '' ,"live", $widget1_max);
			if(count($lead_widget_instance_contents)>0)
			{
				$get_content_ids = array_column($lead_widget_instance_contents, 'content_id'); 
				$multiple_contentID = $get_content_ids; //implode("," ,$get_content_ids);
			}
		}
		else
		{
			$lead_widget_instance_contents = $this->widget_model->get_all_available_articles_auto($widget1_max, $sid , $content_type ,  "live");
			if(count($lead_widget_instance_contents)>0)
			{
				$get_content_ids = array_column($lead_widget_instance_contents, 'content_id'); 
				$multiple_contentID = $get_content_ids; //implode("," ,$get_content_ids);
			}
		}
		//$search_term_ids     = $search_term_ids.",".$multiple_contentID;
		$search_term_ids       = implode(",", $multiple_contentID); 
        $searchby_section        = "section";
	}
if($widget2_instance!=''){
if($widget2_mode=="manual"){	
$section_articles 	= $this->widget_model->get_widgetInstancearticles_rendering($widget2_instance , " " ,"live", '');
}else
{
$section_articles   = $this->widget_model->get_liveContents_by_sectionId($widget2_max, $sid, "live", $widget1_max, "search_mode", $search_term_ids, 1, "n");
}
if (function_exists('array_column')){ $section_content_ids = array_column($section_articles, 'content_id'); }else{
$section_content_ids     = array_map( function($element) { return $element['content_id']; }, $section_articles); }
$section_content_ids     = $section_content_ids; //implode("," ,$section_content_ids);
$search_term_ids         = $section_content_ids;
$searchby_section        = "section";
//$search_term_ids     = $search_term_ids.",".$multiple_contentID;
$search_term_ids       = array_unique(array_merge($search_term_ids, $multiple_contentID));
$search_term_ids       = implode(",", $search_term_ids); 
}
}else{
$datafrom                = date('Y');  
}

$archive_result_contents         = $this->widget_model->get_search_result_data($order_field, $order, $start_at, $search_limit, "", "", $search_term_ids , $searchby_section, $sid, $content_type, "", $datafrom);

$archive_TotalCount                     = $this->widget_model->get_search_result_data($order_field, $order, "" , "", "", "", $search_term_ids, $searchby_section, $sid, $content_type, "", $datafrom);
$TotalCount              = $archive_TotalCount['Search_result'];            
$seacrh_result_contents  = $archive_result_contents['Search_result'];
$loaddata_from                = $archive_result_contents['year'];
$datafrom                = $loaddata_from; 
//print_r($loaddata_from);exit;
$section_details = $this->widget_model->get_section_by_id($sid); //live db
$section_name    = $section_details['Sectionname'];
}
else
{
	$searchby          = ($this->input->get('searchby') != '') ? $this->input->get('searchby'): "H";
	$search_term       = ($this->input->get('search_term') != '') ? $this->input->get('search_term'): "";
	$type_search       = ($this->input->get('stype') != '') ? $this->input->get('stype'): 1;

$archive_result_contents         = $this->widget_model->get_search_result_data($order_field, $order, $start_at, $search_limit, $fromdate, $todate, $search_term , $searchby, $sectionid, $content_type, $type_search, $datafrom);

$archive_TotalCount                     = $this->widget_model->get_search_result_data($order_field, $order, "" , "", "", "", $search_term , $searchby, $sectionid, $content_type, $type_search, $datafrom);
$TotalCount              = $archive_TotalCount['Search_result'];            
$seacrh_result_contents  = $archive_result_contents['Search_result'];
$loaddata_from           = $archive_result_contents['year'];
$result_year             = $archive_result_contents['year'];
}
$instance        = ($manual_instance!='' && $manual_instance!="archive") ? "&instance=".$manual_instance : ""; 
$sid_url         = ($sid !='')? "&sid=".$sid.$instance: "";
if($fromdate!='' || $todate!=''){
$query_string_segment          = "&search_term=".$search_term."&searchby=".$searchby."&ctype=".$content_type."&stype=".$type_search."&datafrom=".$datafrom."&fdate=".$fromdate."&tdate=".$todate;
}else
{
$query_string_segment          = "&search_term=".$search_term."&searchby=".$searchby."&ctype=".$content_type."&stype=".$type_search."&datafrom=".$datafrom.$sid_url;
}

$config['total_rows']           = count($TotalCount);
$config['per_page']             = 15; 
$config['page_query_string']    = TRUE;
$config['enable_query_strings'] = TRUE;
$config['custom_num_links']      = 5;
$config['suffix']               = $query_string_segment;
$config['cur_tag_open']         = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close']        = "</a>";
$this->pagination->initialize($config); 
//$PaginationLink                 = $this->pagination->create_links();
$PaginationLink                 = $this->pagination->custom_search_create_links();

$section_mapping = $this->widget_model->multiple_section_mapping();
$show_hide       = 'style="display:none;"';

?>
<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<!--<h4>Search</h4>-->
         <div class="WidthFloat_L">
		 <button class="reveal search-show">Show Advance Search</button>
		 </div>
        <div class="toggle_container" <?php echo $show_hide;?>>
        <div class="block"> 
        <div class="archives-form" id="advanced_search">
        <h4>Advanced Search</h4>
		<span style="display: none;color:red;" id="emptySearchExpressionError">Please provide search keyword(s)</span>
        <div>
		<form name="searchform" id="searchform" method="get" action="<?php echo base_url()."topic/";?>"  enctype="multipart/form-data" role="form">
        <ul id="title_form">
        <li class="odd">Key Words</li>
        <li class="single-form even"><input type="text" name="search_term_txt" class="validate" id="search_term_txt" value="<?php if(isset($search_term)){ echo $search_term;}else{echo set_value('search_term');}?>"/></li>
        <li class="odd double-form">Type</li>
<li class="even">
		<select id="search_type" class="controls type_validate" name="search_type" Value="<?php echo set_value('search_type'); ?>">
      <option value="All">Type of Search</option>
	  <option <?php if(isset($_GET)){ if($type_search ==1){?> selected="selected" <?php }}?> value="1">With At Least One Of The Words</option>
      <option <?php if(isset($_GET)){ if($type_search ==2){?> selected="selected" <?php }}?> value="2">With The Exact Phrase</option>
	  <option <?php if(isset($_GET)){ if($type_search ==3){?> selected="selected" <?php }}?> value="3">Without The Words</option>
		</select>
</li>
		</ul>
		<ul>
        <li class="odd double-form">Date Interval</li>
        <li class="hero-unit even double-form" >
         <input  type="text" placeholder="From" name="FromDate" class="datefield"  value="<?php if(isset($fromdate)){ echo $fromdate;}else{echo set_value('FromDate');}?>"   id="example1" readonly="readonly">
         <input  type="text" placeholder="To" name="ToDate" class="datefield"  value="<?php if(isset($todate)){ echo $todate;}else{echo set_value('ToDate');}?>"   id="example2" readonly="readonly">
        </li>
        <li class="odd">Section</li>
        <li class="even"> 
        <select name="Search_section" class="controls" id="main_section_id" Value="<?php echo set_value('Search_section'); ?>">
   <option value="All">-All-</option>
  
 <?php if(isset($section_mapping)) { 
				$Except=array("ad_check","testing","Search","404","Newsletter","Privacy Policy","Terms of Use","rss","Advertise With Us","Contact Us","About Us","Careers");
				 foreach($section_mapping as $mapping) {  ?>
				<?Php
					if(!in_array(strip_tags($mapping['Sectionname']),$Except)):
				?>
<option  <?php if(isset($sectionid)){ if($sectionid ==$mapping['Section_id']){?> selected=	"selected"<?php }}?> class="blog_option"  sectoin_data="<?php echo $mapping['Sectionname']; ?>"   value="<?php echo $mapping['Section_id']; ?>"><?php echo strip_tags($mapping['Sectionname']); ?></option>
  <?php if(!(empty($mapping['sub_section'])) &&  $mapping['Sectionname'] != 'Columns') { ?>
 
  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
    <option <?php if(isset($sectionid)){ if($sectionid ==$sub_mapping['Section_id']){?> selected="selected"<?php }}?>   sectoin_data="<?php echo $mapping['Sectionname']; ?>"   value="<?php echo $sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
		
		 <?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
		 
		   <?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
    <option <?php if(isset($sectionid)){ if($sectionid ==$sub_sub_mapping['Section_id']){?> selected="selected"<?php }}?> value="<?php echo $sub_sub_mapping['Section_id']; ?>"  sectoin_data="<?php echo $mapping['Sectionname']; ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
		 
		<?php } } ?>
  <?php  } } ?>


  <?php endif;  } } ?>

</select>        </li>
<li class="odd double-form"> Content Type</li>
<li class="even">
		<select id="content_type" class="controls" name="content_type" Value="<?php echo set_value('content_type'); ?>">
         <option value="All">-All-</option>
		<option <?php if(isset($_GET)){ if($content_type ==1){?> selected="selected" <?php }}?> value="1">Article</option>
		<option <?php if(isset($_GET)){ if($content_type ==3){?> selected="selected" <?php }}?> value="3">Gallery</option>
		<option <?php if(isset($_GET)){ if($content_type ==4){?> selected="selected" <?php }}?> value="4">Video</option>
		<option <?php if(isset($_GET)){ if($content_type ==5){?> selected="selected" <?php }}?> value="5">Audio</option>
		</select>
</li>

        </ul>
		<div class="search-button">
		 <input  type="hidden" name="Searchby" value="<?php echo ($tagname!='')? 'Tag' : 'Title'; ?>"   id="Searchby">
		<input type="submit" id="searchbtn" name="searchbtn" value="search" /></div>

		</form>
        </div>
		
        </div>
	
		</div>
        </div>
        <?php 
		//print_r($datafrom);exit;

		if(count($seacrh_result_contents)>0) 
		{ 
		$query_url = current_url()."?search_term=".$search_term."&searchby=".$searchby."&ctype=";
		$order_by  = ($order_field =="Title")? "&order_by=Title" : "&order_by=Date" ;
		if($type_search!=3){
		$search_term = ($sid!='')? $section_name : $search_term;
		?>
        <ul class="ascending" id="table_sorter"><li style="float:left;">Search results for <span class="active"><?php echo $search_term;?></span></li></ul>
        <?php } ?>
		<ul class="ascending" id="table_sorter">
        <li>Show in <span id="ordering" class="active"><a href="<?php echo $query_url.$content_type."&stype=".$type_search."&datafrom=".$datafrom."&order_dir=".$order.$order_by.$sid_url;?>"><?php if(isset($order)){ if($order =="Asc"){?> Descending <?php }else{?> Ascending <?php }}?></a></span> order</li>
        <?php if($sid ==''){ ?>
        <li>
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==1){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==1){ ?> javascript:void(0); <?php }else { echo $query_url."1"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Article</a></span>| 
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==3){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==3){ ?> javascript:void(0); <?php }else { echo $query_url."3"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Gallery</a></span> | 
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==4){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==4){ ?> javascript:void(0); <?php }else { echo $query_url."4"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Video</a></span>| 
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==5){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==5){ ?> javascript:void(0); <?php }else {echo $query_url."5"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Audio</a></span>
        </li>
        <?php } ?>
        <li>Order by : <span id="orderbydate" <?php if(isset($order_field)){ if($order_field !="Title"){?> class="active" <?php }}?>><a href="<?php if(isset($order_field)){ if($order_field !="Title"){ ?> javascript:void(0); <?php }else { echo $query_url.$content_type."&stype=".$type_search."&datafrom=".$datafrom."&order_dir=".$order."&order_by=Date".$sid_url; }}?>">Date</a></span> | <span id="orderbytitle" <?php if(isset($order_field)){ if($order_field =="Title"){?> class="active" <?php }}?>><a href="<?php if(isset($order_field)){ if($order_field =="Title"){ ?> javascript:void(0); <?php }else { echo $query_url.$content_type."&stype=".$type_search."&datafrom=".$datafrom."&order_dir=".$order."&order_by=Title".$sid_url; }}?>">Title</a></span></li>
        </ul>

		<table id="example" class="display result-section" cellspacing="0" width="100%">
				<thead>
					<tr>
					    <th>Image</th>
						<th>Title</th>
					</tr>
				</thead>
                <tbody>
                <?php 
				$Count = 0;
				$load_more = '';
				$last_content_id = '';
				$total_result = count($seacrh_result_contents);
			foreach($seacrh_result_contents as $article) {
			$image_path = $article['ImagePhysicalPath'];
			$image_title = $article['ImageCaption'];
			$image_alt = $article['ImageAlt'];
			$subdata = array();
			$Image600X390 	= $article['ImagePhysicalPath'];
			if (get_image_source($Image600X390, 1) && $Image600X390 != '')
			{
			$imagedetails = get_image_source($Image600X390, 2);
			$imagewidth = $imagedetails[0];
			$imageheight = $imagedetails[1];
			
			if ($imageheight > $imagewidth)
			{
				$Image600X390 	= $article['ImagePhysicalPath'];
			}
			else
			{				
				$Image600X390 	= str_replace("original","w600X390", $article['ImagePhysicalPath']);
			}
			$image_path='';
				$image_path = image_url. imagelibrary_image_path . $Image600X390;
			}
			else{
			$image_path = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
			$image_title = '';	
			$image_alt = '';	
			}
			
			echo  '<tr role="row"><td><figure class="result-section-figure"><img  src="'.$image_path.'" title="'.$image_title.'" alt="'.$image_alt.'" /></figure></td>';	
			
	 $domain_name      = base_url();
	 $content_url      = $article['url'];
	 $param            = $content['close_param'];
	 $live_article_url = $domain_name.$content_url.$param;
     $custom_title     = strip_tags($article['title']);
	 $summary          = $article['summary_html'];
	 $publisheddate    = date('jS F Y', strtotime($article['publish_start_date']));
     $custom_title     = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);

		echo  '<td><div class="search-content">
        <h4><a  href="'.$live_article_url.'"  class="article_click">'.$custom_title.'</a></h4><p>'.$summary.'</p>
		<date>Published on '.$publisheddate.'</date>
        </div><td></tr>';
		$last_content_id = @$TotalCount[count($TotalCount)-1]['content_id'];
		if((count($seacrh_result_contents) <= $search_limit) && (($Count == count($seacrh_result_contents)- 1)|| ($Count == count($seacrh_result_contents))) && ($last_content_id == $article['content_id']) )
		{
			//$result_year = ($this->input->get('datafrom')!='')? $this->input->get('datafrom') : $datafrom;
			//$datafrom = ($datafrom=="live" || $datafrom=="")? date('Y') : ((($result_year-$datafrom)>1)? $datafrom :$datafrom-1);
			$live_data_from = $this->input->get('datafrom');
			$loaddata_from = ($sid!='' && $live_data_from== '')? $loaddata_from: $loaddata_from-1;
			$datafrom = ($datafrom=="live" || $datafrom=="")? date('Y') : $loaddata_from;
			$acrchive_url = $query_url.$content_type."&stype=".$type_search."&datafrom=".$datafrom.$sid_url;
			if($loaddata_from!="table_not_exist"){
			$load_more = '<a class="load_more_archive" href="'.$acrchive_url.'">More</a>';
			}
		}
		$Count++;
		}
		?>
                </tbody>
	</table>
<div class="search_count">
<?php 
$count_from             = ($start_at==0)? 1 : $start_at;
$count_to               = (($count_from!=1)? $count_from: "")+count($seacrh_result_contents);
?>
<p>Search results <?php echo $count_from;?> - <?php echo $count_to;?> of <?php echo count($TotalCount);?></p>
</div>   
<div class="pagina">
<?php echo $PaginationLink.$load_more;?>
</div>
<?php }else{ 
if($loaddata_from!="" && $loaddata_from!="table_not_exist"){
$query_url = current_url()."?search_term=".$search_term."&searchby=".$searchby."&ctype=";
$archive_datafrom = ($datafrom=="live" || $datafrom=="")? date('Y') : $datafrom-1;  //date('Y')
$datafrom = ($datafrom=="live" || $datafrom=="")? "" : $datafrom-1;  //date('Y')
$acrchive_url = $query_url.$content_type."&stype=".$type_search."&datafrom=".$archive_datafrom;
$search_term = ($sid!='')? $section_name : $search_term;
$order_by  = ($order_field =="Title")? "&order_by=Title" : "&order_by=Date" ;
?>
 <ul class="ascending" id="table_sorter"><li style="float:left;">Search results for <span class="active"><?php echo $search_term;?></span></li></ul>
		<ul class="ascending" id="table_sorter">
        <li>Show in <span id="ordering" class="active"><a href="<?php echo $query_url.$content_type."&stype=".$type_search."&datafrom=".$datafrom."&order_dir=".$order.$order_by.$sid_url;?>"><?php if(isset($order)){ if($order =="Asc"){?> Descending <?php }else{?> Ascending <?php }}?></a></span> order</li>
         <?php if($sid ==''){ ?>
        <li>
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==1){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==1){ ?> javascript:void(0); <?php }else { echo $query_url."1"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Article</a></span>| 
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==3){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==3){ ?> javascript:void(0); <?php }else { echo $query_url."3"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Gallery</a></span> | 
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==4){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==4){ ?> javascript:void(0); <?php }else { echo $query_url."4"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Video</a></span>| 
        <span id="ordering" <?php if(isset($content_type)){ if($content_type ==5){?> class="active" <?php }}?>><a href="<?php if(isset($content_type)){ if($content_type ==5){ ?> javascript:void(0); <?php }else {echo $query_url."5"."&stype=".$type_search."&datafrom=".$datafrom; }}?>">Audio</a></span>
        </li>
        <?php } ?>
        <li>Order by : <span id="orderbydate" <?php if(isset($order_field)){ if($order_field !="Title"){?> class="active" <?php }}?>><a href="<?php if(isset($order_field)){ if($order_field !="Title"){ ?> javascript:void(0); <?php }else { echo $query_url.$content_type."&stype=".$type_search."&datafrom=".$datafrom."&order_dir=".$order."&order_by=Date".$sid_url; }}?>">Date</a></span> | <span id="orderbytitle" <?php if(isset($order_field)){ if($order_field =="Title"){?> class="active" <?php }}?>><a href="<?php if(isset($order_field)){ if($order_field =="Title"){ ?> javascript:void(0); <?php }else { echo $query_url.$content_type."&stype=".$type_search."&datafrom=".$datafrom."&order_dir=".$order."&order_by=Title".$sid_url; }}?>">Title</a></span></li>
        </ul>
        <?php } ?>
        <div class="search-result">
		<div class="col-md-6 col-xs-12">
       <?php if($sid ==''){ ?>
        <p>Your search did not match any content</p>
        <h4>Suggestions:</h4>
        <ul>
        <li><i class="fa fa-angle-right"></i> Make sure all words are spelled correctly.</li>
        <li><i class="fa fa-angle-right"></i> Try different keywords.</li>
        <li><i class="fa fa-angle-right"></i> Try more general keywords.</li>
        </ul>
      <?php }else{ ?>
         <p>No More section Articles Available to show!</p>
       <?php  }  ?>
        </div>
        <?php if($loaddata_from!="" && $loaddata_from!="table_not_exist"){ ?>
        <div class="col-md-6 col-xs-12">
			<p class="load_more_archive">
			<a href="<?php echo $acrchive_url;?>">More</a>
			</p>
        </div>
        <?php } ?>
        </div>
        <?php } ?>
		</div>
		</div>
         <script>
        $(document).ready(function(){
$("button.reveal").click(function(){
    $(".toggle_container").slideToggle("slow");
    
    if ($.trim($(this).text()) === 'Hide Advance Search') {
        $(this).text('Show Advance Search');
    } else {
        $(this).text('Hide Advance Search');        
    }
    
    return false; 
});
});
</script>
	<script>
		$(document).ready(function () {
        var base_url = "<?php echo base_url(); ?>";
		
		var dpOptions = {
        format: 'dd-mm-yyyy',
		endDate: '+0d',
        autoclose: true
     };

		var datePicker1 = $("#example1").datepicker(dpOptions).
			on('changeDate', function (e) {
					datePicker2.datepicker('setStartDate', e.date);
					datePicker2.datepicker('update');
			});
		
    var datePicker2 = $("#example2").datepicker(dpOptions).
			on('changeDate', function (e) {
					datePicker1.datepicker('setEndDate', e.date);
					datePicker1.datepicker('update');
			});
					
		$("#searchbtn").click(function() 
		{
		   var search_term = $('input[name=search_term_txt]').val();
		   var search_type = $('input[name=search_type]').val();
			if(search_term.trim() =='')
			{
				$('#emptySearchExpressionError').text("Please provide search keyword(s)").show();
				$('.validate').addClass('error');
				return false;
			}else
			{
				if(search_term.trim().length > 200)
				{
				$('#emptySearchExpressionError').text("Please do not enter more than 200 characters!").show();
				$('.validate').addClass('error');
				return false;
				}
				if(search_type ==''){
				$('#emptySearchExpressionError').text('Please choose type of search').show();
				$('.type_validate').addClass('error');
                 return false;
				}
				$('#emptySearchExpressionError').hide();
				$('.validate').removeClass('error');
				return true;
			}
		});
		$('input').keyup(function(){
		$(this).removeClass('error');
		$('#emptySearchExpressionError').hide();
		});
		
	            });
	 </script>