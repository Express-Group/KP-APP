<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="<?php echo $script_url; ?>js/jquery.remodal.js"></script>
<!--<script src="<?php echo $script_url; ?>js/modernizr.js"></script>-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>includes/ckeditor/ckeditor.js"></script>
<link href="<?php echo $script_url; ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>	
<script type="text/javascript" src="<?php echo $script_url; ?>js/breaking_news_js.js"></script>
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; </script>


<script>
function get_date(input) {
if(input == '') {
return false;
}	else {
// Split the date, divider is '/'
var parts = input.match(/(\d+)/g);
return parts[2]+'/'+parts[1]+'/'+parts[0];
} 
}

jQuery(function(){
 jQuery('#date_timepicker_start').datetimepicker({
  format:'d-m-Y',
  onShow:function(ct){
   this.setOptions({
	   maxDate:get_date($('#date_timepicker_end').val())?get_date($('#date_timepicker_end').val()):false,
   })
  },
  timepicker:false
 });
 jQuery('#date_timepicker_end').datetimepicker({
  format:'d-m-Y',
  onShow:function(ct){
   this.setOptions({
	   minDate:get_date($('#date_timepicker_start').val())?get_date($('#date_timepicker_start').val()):false,
   })
  },
  timepicker:false
 });
});


/*$(document).ready(function()
{
	$("#ddDisplayOrder").change(function(){
		$("#hidden_display_order").val("");
	});
});*/
</script>
 <style>
  label.error
  {
	  color:#F00;
	  display: table-row;
  }
  </style>  
<?php $page_name = $this->uri->segment(3); 

if($page_name == "create_breaking_news")
 	$set_page_name = "Create Breaking News";
elseif($page_name == "update_brkng_news")
 	$set_page_name = "Edit Breaking News";
?> 
<div class="Container">
    <div class="BodyWhiteBG">
    	<form action="<?php echo base_url(); ?>niecpan/breaking_news_manager/brkingNews_func" id="brkng_news_form" name="brkng_news_form" method="post" enctype="multipart/form-data">
            <div class="BodyHeadBg Overflow clear">
                <div class="breadcrumbs">Dashboard > <?php echo $set_page_name; ?></div>
                 <h2 class="FloatLeft"><?php echo $set_page_name; ?></h2>
                 <p class="FloatRight save-back">
                 <a class="FloatLeft back-top" href="<?php echo base_url();?>niecpan/breaking_news_manager/"><i class="fa fa-reply fa-2x"></i></a>
                 <button class="btn-primary btn" type="button" name="btnNewsSubmit" value="submit" id="btnNewsSubmit"><i class="fa fa-file-text-o"></i> &nbsp;Save</button></p>
            </div>
    
            <div class="poll_content">
                <div class="create_poll">
                      <div class="qnsans">
                          <div class="qns"><label class="question">Title<span style="color:#F00">*</span></label></div>
                          <div class="ans VideoCk GalleryNameCount  PositionRelative">
                          
                          <textarea class="ckeditor" name="txtTitle" id="txtTitle" maxlength="255"><?php if(isset($fetch_details['Title']) && $fetch_details['Title'] != '' ){ echo $fetch_details['Title']; } echo set_value('txtTitle'); ?></textarea>
                          
                                <?php echo form_error('txtTitle'); ?>
                                <p id="title_error" style="color:#F00"></p>
                                <input type="hidden" name="txtHiddenId" id="txtHiddenId" value="<?php if(isset($fetch_details['breakingnews_id']) && $fetch_details['breakingnews_id'] != '' ){ echo $fetch_details['breakingnews_id']; } echo set_value('txtHiddenId'); ?>"  />
                          </div>
                      </div>
                          
                      
                      <div class="qnsans">
                          <div class="qns"><label>Link Article</label></div>
                          <div class="ans">
                          <a class="btn btn-primary poll-preview"  href="#modal1"><i class="fa fa-desktop"></i></a> 
						  
						  <button class="btn-primary btn" type="button" name="btnunlink_article" id="btnunlink_article" style="display:none">Unlink Article</button>
                          </div>
                      </div>
                  
                     <div class="qnsans" id="article_title_div"  <?php if(is_null(@$fetch_details['Content_ID']) ) { ?>  style="display:none" <?php  } ?>>
                     
                          <div class="qns"><label>Article Title</label></div>
                          <div class="ans">
                          	<label id="get_article_title" for="get_article_title"><?php if(isset($fetch_details['article_title']) && $fetch_details['article_title'] != '' ){ echo strip_tags($fetch_details['article_title']); } ?></label>
                            
                            <input type="hidden" name="hiddn_article_id" id="hiddn_article_id" value="<?php if(isset($fetch_details['Content_ID']) && $fetch_details['Content_ID'] != '' ){ echo $fetch_details['Content_ID']; } echo set_value('hiddn_article_id'); ?>" />
                            
                           </div>
                      </div>
                      
                       <?php /*?><div class="qnsans">
                          <div class="qns"><label>Display Order<span style="color:#F00">*</span></label></div>
                          <div class="ans w2ui-field">
                          	<select id="ddDisplayOrder" name="ddDisplayOrder" class="controls">
                            	<option value="">- Select -</option>
                                <?php
								for($i=1; $i<=20; $i++){
								?>
                                	<option value="<?php echo $i; ?>" <?php if(isset($fetch_details['Displayorder']) && $fetch_details['Displayorder'] == $i) { ?> selected="selected" <?php } echo set_select('ddDisplayOrder', $i);?>><?php echo $i; ?></option>
                                <?php }?>
                            </select>
                            
                            <input type="hidden" name="hidden_display_order" id="hidden_display_order" value="<?php if(isset($fetch_details['Displayorder']) && $fetch_details['Displayorder'] != "") { echo $fetch_details['Displayorder'];} echo set_value('hidden_display_order');?>" />
                          </div>
                      </div><?php */?>
					  
                      <div class="qnsans">
                          <div class="qns"><label class="question">Status<span style="color:#F00">*</span></label></div>
                          <div class="ans section_radio">
                              <div class="switch switch-yellow">
                                  <input type="radio" class="switch-input" name="view3" <?php if(isset($fetch_details['status']) && $fetch_details['status'] == 1) { ?> checked="checked" <?php } echo set_radio('view3', '1', TRUE);?>  value="1" id="week3">
                                  <label for="week3" class="switch-label switch-label-off">Active</label>
                                  <input type="radio" class="switch-input" name="view3" <?php if(isset($fetch_details['status']) && $fetch_details['status'] == 0) { ?> checked="checked" <?php } echo set_radio('view3', '0');?> value="0"id="month3">
                                  <label for="month3" class="switch-label switch-label-on">Inactive</label>
                                  <span class="switch-selection"></span>
                              </div>
                          </div>
                      </div>
                      
                </div>
            </div>
		</form>        
    </div>                            
</div>




<div class="remodal" data-remodal-id="modal1" style="position:relative;">
<!--<p>Light Box</p>-->
    <div class="article_related">
        <div class="article_internal article_popup">
            <div class="article_table1">
                <ul class="AskPrabuCheckBox FloatLeft WidthPercentage">
                        <li>
                            <input type="checkbox" id="search_based_date" value="yes">
                            <label class="include_label HeadTopAuto"  for="search_based_date">Search Based on Date Range</label>
                        </li>
                        <li id="checkin_checkout_div">
                            <p class="CalendarWrapper" >
                            <input type="text" placeholder="Start Date" id="date_timepicker_start" value="">
                            <input type="text" placeholder="End Date" id="date_timepicker_end" value="">
                            </p>
                        </li>
                </ul>
                
                <div class="FloatLeft TableColumn">  
                    <div class="FloatLeft w2ui-field">
                            <select  id="article_section"  class="controls">
                                    <option value="">Section</option>
                                    <?php if(isset($section_mapping)) { foreach($section_mapping as $mapping) {  ?>
                                    <option  style="color:#933;font-size:18px;" class="blog_option" value="<?php echo $mapping['Section_id']; ?>"><?php echo $mapping['Sectionname']; ?> </option>
                                    <?php if(!(empty($mapping['sub_section']))) {  foreach($mapping['sub_section'] as $sub_mapping) { ?>
                                    <option value="<?php echo $sub_mapping['Section_id']; ?>"> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_mapping['Sectionname']; ?></option>
                                    <?php } ?>
                                    <?php   } ?>
                                    <?php } } ?>
                            </select>
                    </div>
                	<div class="FloatLeft TableColumnSearch"><input type="search" name="search_text" id="search_text" placeholder="Search" class="SearchInput"></div><i class="fa fa-search FloatLeft SearchStyle"  id="article_search_id"></i>
                    
                </div>
                <table id="example" class="display article_table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Bread Crumb</th>
                            <th>Published Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

