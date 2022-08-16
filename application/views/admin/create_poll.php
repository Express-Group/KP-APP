<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="<?php echo $script_url; ?>js/jquery.remodal.js"></script>
<!--<script src="<?php echo $script_url; ?>js/modernizr.js"></script>-->
<!--data tables-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<!--calendar begind-->
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/poll_js.js"></script>
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; var folder_name = '<?php echo folder_name; ?>'; </script>
<link href="<?php echo $script_url; ?>css/admin/template_design/css/widget-articles.css" rel="stylesheet" type="text/css" />

<style>
.widget-img-lib{
	width:100%;
	margin:0;
}
.widget-img-lib img{
	width: 200px !important;
    height: 150px;
}

</style>
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


</script>
<style>
.error {
	color:#F00;
	display: table-row;
}
</style>
<?php $page_name = $this->uri->segment(3); 

if($page_name == "create_poll_page")
 	$set_page_name = "Create poll";
elseif($page_name == "update_poll")
 	$set_page_name = "Edit poll";
?>
<div class="Container">
	<div class="BodyWhiteBG">
		<form action="<?php echo base_url().folder_name; ?>/poll_manager/insrt_poll" id="poll_form" name="poll_form" method="post" enctype="multipart/form-data">
			<div class="BodyHeadBg Overflow clear">
				<div class="breadcrumbs">Dashboard > <?php echo $set_page_name; ?></div>
				<h2 class="FloatLeft"><?php echo $set_page_name; ?></h2>
				<p class="FloatRight save-back"> <a class="FloatLeft back-top" href="<?php echo base_url().folder_name;?>/poll_manager/"><i class="fa fa-reply fa-2x"></i></a>
					<button class="btn-primary btn" type="button" name="btnPollSubmit" value="submit" id="btnPollSubmit"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
				</p>
			</div>
			<div class="poll_content">
				<div class="create_poll">
					<div class="qnsans">
						<div class="qns">
							<label class="question">Poll Question<span style="color:#F00">*</span></label>
						</div>
						<div class="ans">
							<?php 
						  if(isset($fetch_details['PollQuestion']) && $fetch_details['PollQuestion'] != '' ){
						  $question = $fetch_details['PollQuestion'];
						//  $question = str_replace ("&quot;", "'" ,$question );
						  }
						   ?>
							<input type="text" name="txtQuestion" id="txtQuestion" maxlength="252" value="<?php if(isset($fetch_details['PollQuestion']) && $fetch_details['PollQuestion'] != '' ){ echo stripslashes(($question)); } echo set_value('txtQuestion'); ?>" class="tb_style box-shad box-shad1">
							<br />
							<?php echo form_error('txtQuestion'); ?>
							<input type="hidden" name="txtHiddenId" id="txtHiddenId" value="<?php if(isset($fetch_details['Poll_id']) && $fetch_details['Poll_id'] != '' ){ echo $fetch_details['Poll_id']; } echo set_value('txtHiddenId'); ?>"  />
						</div>
					</div>
					<div class="qnsans">
						<div class="qns">
							<label class="image">Image</label>
						</div>
						<div class="ans">
							<div class="article_image poll_image"> 
								<!--<span>Browse</span>-->
								<!--<input class="upload" type="file" name="btnImageUPload" id="btnImageUPload">-->
								<span>
									<a class="set_image browse-image" id="home_image_set"  data-remodal-target="modal2" href="#" style="color:#fff;"><?php if(isset($fetch_details['image_id']) && $fetch_details['image_id'] != '')  echo 'Change Image'; else echo 'Browse'; ?></a>
								</span>
								<span id="edit_image_span">
								<?php if(isset($fetch_details['image_id']) && $fetch_details['image_id'] != '')  { ?>
									<a style="color:#fff;" href="javascript:void(0);" rel="home" id="edit_article_image" onclick="set_content_id_in_remodal('');" class="del_image delbtn_border"><i class="fa fa-pencil"></i></a>
								
								<?php  } ?>
								</span>
								<span id="remove_image_span">
								<?php if(isset($fetch_details['image_id']) && $fetch_details['image_id'] != '')  { ?>
									<a  href="javascript:void(0);"  onclick="return remove_custom_image()" class="del_image"><i class="fa fa-trash-o"></i></a>
								<?php  } ?>
								</span>
							</div>
							
							<?php /*?><span class="FloatLeft" style="margin:0 10px;">
							<?php
                                if(isset($fetch_details['image_id']) && $fetch_details['image_id'] != '')
                                {
									$ImageURL = get_image_by_contentid($fetch_details['image_id']);
			
									$ImageDetails = GetImageDetailsByContentId($fetch_details['image_id']);
									
									$Physical_URL = $ImageDetails['ImagePhysicalPath'];
									
									$Physical_array = explode('.',$Physical_URL );
									$Physical_extension = $Physical_array[1];
									//print_r($Physical_URL);
									$Physical_name = GetPhysicalNameFromPhysicalPath($Physical_URL);
                                ?>
							<img id="preview_image" class="PollImgPreview"  name="preview_image" src="<?php echo @$ImageURL; ?>" alt="" border="0"  />
							<?php
                                } 
                                else
                                {
                                ?>
							<img id="preview_image" name="preview_image" class="PollImgPreview" src="#" alt="" style="display:none" border="0" />
							<?php } ?>
							<input type="hidden" name="img_id" id="img_id" value="<?php echo @$fetch_details['image_id']; ?>" />
							<input type="hidden" name="img_path" id="img_path" value="<?php echo @$fetch_details['image_path']; ?>" />
							<input type="hidden" name="img_caption" id="img_caption" value="<?php echo @$fetch_details['image_title']; ?>" />
							<input type="hidden" name="hidden_img_name" id="hidden_img_name" rel="<?php echo @$fetch_details['image_id']; ?>" physical_extension="<?php echo @$Physical_extension ?>" value="<?php echo @$Physical_name; ?>" />
							</span> <?php */?>
							<?php
                                if(isset($fetch_details['image_id']) && $fetch_details['image_id'] != '')
                                {
									$ImageDetails = GetImageDetailsByContentId($fetch_details['image_id']);
									$Physical_URL = $ImageDetails['ImagePhysicalPath'];
									$Physical_array = explode('.',$Physical_URL );
									$Physical_extension = $Physical_array[1];
									$Physical_name = GetPhysicalNameFromPhysicalPath($Physical_URL);
								}
                                ?>
							<span class="FloatLeft" style="margin:0 10px;" id="poll_image">
							<img id="preview_image" class="PollImgPreview"  name="preview_image" src="<?php if(isset($fetch_details['image_path']) && $fetch_details['image_path'] != "") { echo image_url.imagelibrary_image_path.$fetch_details['image_path']; } else echo "#"; ?>" alt="" border="0"  />
							</span> 
							
							
							<span id="image_error"></span>
							<!--<button class="btn-primary btn FloatLeft" type="button" name="btnremove_image" id="btnremove_image" style="display:none">Remove Image</button>-->
						</div>
					</div>
					<div class="qnsans">
						<div class="qns">
							<label>Image Name</label>
						</div>
						<div class="ans">
							<input type="textbox" name="physical_name" id="physical_name" value="<?php echo @$Physical_name; ?>"  physical_extension="<?php echo @$Physical_extension ?>"  />
							<span id="physical_error"></span> </div>
							
							<input type="hidden" name="img_id" id="img_id" value="<?php echo @$fetch_details['image_id']; ?>" />
							<input type="hidden" name="img_path" id="img_path" value="<?php echo @$fetch_details['image_path']; ?>" />
							<input type="hidden" name="full_path" id="full_path"  />
							<input type="hidden" name="temp_image_id" id="temp_image_id"  />
							<input type="hidden" name="image_library_id" id="image_library_id"  />
							<input type="hidden" value="1" id="is_image_from_library" name="is_image_from_library">
					</div>
					
					<div class="qnsans">
						<div class="qns">
							<label>Image Alt</label>
						</div>
						<div class="ans">
							<input type="textbox" name="image_alt" id="image_alt" value="<?php echo @$fetch_details['image_alt']; ?>"   />
						</div>
					</div>
					
					<div class="qnsans">
						<div class="qns">
							<label>Image Caption</label>
						</div>
						<div class="ans">
							<input type="text" name="image_caption" id="image_caption" value="<?php echo @$fetch_details['image_title']; ?>" />
						</div>
					</div>
					
					<div class="qnsans">
						<div class="qns">
							<label class="question">Number of Options<span style="color:#F00">*</span></label>
						</div>
						<div class="ans w2ui-field">
							<select name="ddOptions" id="ddOptions" class="controls">
								<option value="1" <?php if(isset($fetch_details['NumberOfOptions']) && $fetch_details['NumberOfOptions'] == '1' ){ ?> selected="selected"<?php } echo set_select('myselect', '1'); ?>>1</option>
								<option value="2" <?php if(isset($fetch_details['NumberOfOptions']) && $fetch_details['NumberOfOptions'] == '2' ){ ?> selected="selected"<?php }elseif(!isset($fetch_details['NumberOfOptions']) ) { echo 'selected'; } echo set_select('myselect', '2');?>>2</option>
								<option value="3" <?php if(isset($fetch_details['NumberOfOptions']) && $fetch_details['NumberOfOptions'] == '3' ){ ?> selected="selected"<?php } echo set_select('myselect', '3'); ?>>3</option>
								<option value="4" <?php if(isset($fetch_details['NumberOfOptions']) && $fetch_details['NumberOfOptions'] == '4' ){ ?> selected="selected"<?php } echo set_select('myselect', '4');?>>4</option>
								<option value="5" <?php if(isset($fetch_details['NumberOfOptions']) && $fetch_details['NumberOfOptions'] == '5' ){ ?> selected="selected"<?php } echo set_select('myselect', '5'); ?>>5</option>
							</select>
						</div>
					</div>
					<div class="qnsans w2ui-field" style="display:none" id="div_option_1">
						<div class="qns">
							<label class="question">Option text 1<span style="color:#F00">*</span></label>
						</div>
						<div class="ans">
							<input type="text" name="txtOption1" id="txtOption1" maxlength="50" value="<?php if(isset($fetch_details['OptionText1']) && $fetch_details['OptionText1'] != '' ){ echo $fetch_details['OptionText1']; } echo set_value('txtOption1'); ?>" class="box-shad box-shad1 controls">
							<?php echo form_error('txtOption1'); ?> </div>
					</div>
					<div class="qnsans w2ui-field" style="display:none" id="div_option_2">
						<div class="qns">
							<label class="question">Option text 2<span style="color:#F00">*</span></label>
						</div>
						<div class="ans">
							<input type="text" name="txtOption2" id="txtOption2" maxlength="50" value="<?php if(isset($fetch_details['OptionText2']) && $fetch_details['OptionText2'] != '' ){ echo $fetch_details['OptionText2']; } echo set_value('txtOption2'); ?>" class="box-shad box-shad1 controls">
							<?php echo form_error('txtOption2'); ?> </div>
					</div>
					<div class="qnsans w2ui-field" style="display:none" id="div_option_3">
						<div class="qns">
							<label class="question">Option text 3<span style="color:#F00">*</span></label>
						</div>
						<div class="ans">
							<input type="text" name="txtOption3" id="txtOption3" maxlength="50" value="<?php if(isset($fetch_details['OptionText3']) && $fetch_details['OptionText3'] != '' ){ echo $fetch_details['OptionText3']; } echo set_value('txtOption3'); ?>" class="box-shad box-shad1 controls">
							<?php echo form_error('txtOption3'); ?> </div>
					</div>
					<div class="qnsans w2ui-field" style="display:none" id="div_option_4">
						<div class="qns">
							<label class="question">Option text 4<span style="color:#F00">*</span></label>
						</div>
						<div class="ans">
							<input type="text" name="txtOption4" id="txtOption4" maxlength="50" value="<?php if(isset($fetch_details['OptionText4']) && $fetch_details['OptionText4'] != '' ){ echo $fetch_details['OptionText4']; }  echo set_value('txtOption4'); ?>" class="box-shad box-shad1 controls">
							<?php echo form_error('txtOption4'); ?> </div>
					</div>
					<div class="qnsans w2ui-field" style="display:none" id="div_option_5">
						<div class="qns">
							<label class="question">Option text 5<span style="color:#F00">*</span></label>
						</div>
						<div class="ans">
							<input type="text" name="txtOption5" id="txtOption5" maxlength="50" value="<?php if(isset($fetch_details['OptionText5']) && $fetch_details['OptionText5'] != '' ){ echo $fetch_details['OptionText5']; }  echo set_value('txtOption5'); ?>" class="box-shad box-shad1 controls">
							<?php echo form_error('txtOption5'); ?> </div>
					</div>
					<div class="qnsans">
						<div class="qns">
							<label>Link Article</label>
						</div>
						<div class="ans" id="link_article"> <a class="btn btn-primary poll-preview"  href="#modal1"><i class="fa fa-desktop"></i></a>
							<button class="btn-primary btn" type="button" name="btnunlink_article" id="btnunlink_article" style="display:none">Unlink Article</button>
						</div>
					</div>
					<div class="qnsans" id="article_id_div" <?php if(is_null(@$fetch_details['Content_ID']) ) { ?>  style="display:none"  <?php  } ?> >
						<div class="qns">
							<label>Article ID</label>
						</div>
						<div class="ans">
							<label id="get_article_id" for="get_article_id">
								<?php if(isset($fetch_details['Content_ID']) && trim($fetch_details['Content_ID']) != '' ){ echo $fetch_details['Content_ID']; } echo set_value('get_article_id'); ?>
							</label>
							<input type="hidden" name="hiddn_article_id" id="hiddn_article_id" value="<?php if(isset($fetch_details['Content_ID']) && $fetch_details['Content_ID'] != '' ){ echo $fetch_details['Content_ID']; } echo set_value('hiddn_article_id'); ?>" />
						</div>
					</div>
					<div class="qnsans" id="article_title_div"  <?php if(is_null(@$fetch_details['Content_ID']) ) { ?>  style="display:none" <?php  } ?>>
						<div class="qns">
							<label>Article Title</label>
						</div>
						<div class="ans">
							<label id="get_article_title" for="get_article_title">
								<?php if(isset($fetch_details['article_title']) && $fetch_details['article_title'] != '' ){ echo strip_tags($fetch_details['article_title']); } echo set_value('get_article_id'); ?>
							</label>
							<!--<input type="hidden" name="hiddn_article_title" id="hiddn_article_title" />--> 
						</div>
					</div>
					<div class="qnsans">
						<div class="qns">
							<label class="question">Status<span style="color:#F00">*</span></label>
							<input type="hidden" name="hidden_prev_status" id="hidden_prev_status" value="<?php if(isset($fetch_details['Status']) && $fetch_details['Status'] != '' ){ echo $fetch_details['Status']; } echo set_value('Status'); ?>" />
						</div>
						<div class="ans section_radio">
							<div class="switch switch-yellow">
								<input type="radio" class="switch-input" name="view3" <?php if(isset($fetch_details['Status']) && $fetch_details['Status'] == '1') { ?> checked="checked" <?php } echo set_radio('view3', '1', TRUE);?>  value="1" id="week3">
								<label for="week3" class="switch-label switch-label-off">Active</label>
								<input type="radio" class="switch-input" name="view3" <?php if(isset($fetch_details['Status']) && $fetch_details['Status'] == '0') { ?> checked="checked" <?php } echo set_radio('view3', '0');?> value="0"id="month3">
								<label for="month3" class="switch-label switch-label-on">Inactive</label>
								<span class="switch-selection"></span> </div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="remodal" data-remodal-id="modal1" style="position:relative;"> 
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
					<div class="FloatLeft TableColumnSearch">
						<input type="search" name="search_text" id="search_text" placeholder="Search" class="SearchInput">
					</div>
					<i class="fa fa-search FloatLeft SearchStyle"  id="article_search_id"></i> </div>
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

<div class="remodal" id="image_library_div" data-remodal-id="modal2" data-remodal-options="hashTracking: false" style="position:relative;">
	<div class="article_popup GalleryPopup ArticlePopup" style="height: 467px;">
		<div class="article_popup1">
			<ul class="article_popup_tabs">
				<li class="active img_upload">From Local System</li>
				<li class="img_browse">From Library</li>
			</ul>
		</div>
		<div class="article_popup2">
			<div class="article_upload">
				<form  name="ImageForm" id="ImageForm" action="<?php echo base_url().folder_name; ?>/poll_manager/custom_image_upload" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="Image_content_id" id="Image_content_id" value="" />
					<div class="popup_addfiles">
						<div class="fileUpload btn btn-primary WidthAuto"> <span>+ Select Image</span>
							<input type="file" id="imagelibrary" name="imagelibrary" accept="image" class="upload" style="width:100%;">
						</div>
						<!--<div id="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
							Please wait while image is being uploaded </div>-->
						<div class="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
							Please wait while image is being uploaded </div>
					</div>
				</form>
			</div>
			<div class="GalleryDrag"  id="drop-area"> Drop files anywhere here to upload or click on the "Select Image" button above </div>
		</div>
		<div class="article_browse">
			<h3>Pick the item to insert</h3>
			<div class="article_browse1">
				<div class="article_browse_drop">
					<div class="w2ui-field FloatLeft"> </div>
					<input type="text" placeholder="Search" id="search_caption" name="txtBrowserSearch"  class="box-shad1 FloatLeft BrowseInput" />
					<i id="image_search_id" class="fa fa-search FloatLeft BrowseSearch"></i> <a  class="btn btn-primary margin-left-10" id="clear_search" href="javascript:void(0);" style="display:none;">Clear Search</a> </div>
				<div class="popup_images transitions-enabled infinite-scroll clearfix"  id="image_lists_id"> </div>
				<?php if(isset($image_library)) { 
					$count = 0;
					foreach($image_library as $image) {
						$active_class = "";
						if($count==0) {
							$first_image_caption 	= $image->ImageCaption;
							$first_image_alt 		= $image->ImageAlt;
							$first_image_date 		= $image->Modifiedon;
							$first_image_pathname	= $image->ImagePhysicalPath;
							$first_image_id 		= $image->content_id;
						
							$active_class 			= "active";
						}
                         ++$count; } 						 						 
						 }
						 else
						 {
							$first_image_caption 	= "";
							$first_image_alt 		= "";
							$first_image_date 		= "";
							$first_image_pathname	= "";;
							$first_image_id 		= "";;
						
							$active_class 			= "";
						 }
						  ?>
				<nav id="page-nav"> <a href="<?php echo base_url().folder_name; ?>/poll_manager/search_image_library_scroll/2"></a> </nav>
			</div>
			<div class="article_browse2">
				<h4>Image Details</h4>
				<img id="image_path" src="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" />
				<h4 id="image_name"> <?php echo $first_image_caption; ?> </h4>
				<p>Date:<span id="image_date"> <?php echo $first_image_date; ?> </span></p>
				<input type="hidden" value="<?php echo $first_image_id; ?>" data-content_id="<?php echo $first_image_id; ?>" data-image_alt="<?php echo $first_image_alt; ?>" data-image_caption="<?php echo $first_image_caption; ?>"  data-image_date="<?php echo $first_image_date; ?>" data-image_source="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" data-image_path="<?php echo  image_url.imagelibrary_image_path.$first_image_pathname; ?>" id="browse_image_id" name="browse_image_id" />
				<div class="article_browse2_input">
					<label>Image Alt</label>
					: <span id="textarea_alt"><?php echo $first_image_alt; ?></span> <br  />
					<label>Caption</label>
					: <span id="textarea_caption"><?php echo $first_image_caption; ?></span> </div>
			</div>
			<div class="FloatRight popup_insert insert-fixed">
				<button type="button" class="btn btn-primary remodal-confirm"id="browse_image_insert"  >Insert</button>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo $script_url; ?>js/poll_image_library.js"></script> 

<script>
$(document).ready(function()
{
	WidgetCustomImage.support_image_width 	= 150;
	WidgetCustomImage.support_image_height 	= 150;
	
	$(document).on('open', '.remodal', function () {
		Image_Search('');
		$('#browse_image_insert').hide();
		scroll_down_inc = 2;
		reached_last 	= true;	
		scroll_up_direction = false;
	});
	
	$(document).on('closed', '.remodal', function () {
		scroll_down_inc = 2;
		reached_last 	= true;
		scroll_up_direction = false;
	});


	<?php if(isset($fetch_details['image_id']) && $fetch_details['image_id'] != '') {	?>
		var image_alt 		= $('#image_alt').val();
		var image_caption 	= $('#image_caption').val();
		//var image_path 		= $('#custom_image_path'  + '_view').val();
		var image_path 		= '';
		var content_id 		= $('#img_id').val();			
		//var temporary_imageid = ImageIndex;
		var temporary_imageid = '';
	
		ImageData = "alt="+image_alt+"&caption="+image_caption+"&date=''&height=''&width=''&size=''&path="+image_path+"&content_id="+content_id+"&contentType=1&tempImageIndex="+ temporary_imageid;
		//console.log(ImageData);
		insertImageIntocustomTempToEdit(ImageData, true);
	<?php } ?>
	
	$("#image_search_id").click(function() {
			var caption = $("#search_caption").val();
			Image_Search(caption);
		});
		
		$("#search_caption").keyup(function(e){
			if(e.keyCode == 13){
				var caption = $("#search_caption").val();
				Image_Search(caption);
			  }
		});
	
	$('.popup_images').click(function(){
		$('#browse_image_insert').show();
	});
	
			$("#clear_search").click(function() {
			$("#search_caption").val('');
	 var $container = $('.popup_images');
			 $container.empty();
		if($.trim($container.html()) == '') {
			
		$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		
				$.ajax({
				url: base_url+folder_name+"/poll_manager/get_image_library_scroll/1", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				dataType: "HTML",
				success: function(data)   // A function to be called if request succeeds
				{
					
					$container.html(data);
					$("#clear_search").hide();
					setTimeout(function(){
					//call_mansory();
					},1000);
				}
			});
			
		} else {
			//console.log("test");
			//call_mansory();
		}
	});


	/*$(".set_image").click(function(){
			
	 var $container = $('.popup_images');
		
		if($.trim($container.html()) == '') {
			
		$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		
				$.ajax({
				url: base_url+folder_name+"/poll_manager/get_image_library_scroll/1", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				dataType: "HTML",
				success: function(data)   // A function to be called if request succeeds
				{
					
					$container.html(data);
					setTimeout(function(){
					},1000);
				}
			});
			
		} 
	});*/
	
});


function set_content_id_in_remodal(content_id_val)
{
	WidgetCustomImage.content_id 			= content_id_val;
	//WidgetCustomImage.widget_instance_id 	= widget_instance;
	//WidgetCustomImage.mainSection_config_id = $('#mainSectionConfig_Id').val();
	
	WidgetCustomImage.support_image_width 	= 150;
	WidgetCustomImage.support_image_height 	= 150;
	$("#img_id").val("");
}

function insertImageIntocustomTempToEdit(ImageData, is_from_save)
{		
	$.ajax({
			url		: base_url+folder_name+"/poll_manager/Insert_temp_from_image_library",
			type	: "POST",
			data	: ImageData,
			dataType: "json",
			async	: false, 	
			beforeSend: function() {
						$("#loading_msg").html("Please wait...");
						$("#commom_loading").show();
						},
			success	: function(data) {
				$("#loading_msg").html("");
				$("#commom_loading").hide();
				
				//$('#edit_article_image').hide();
				$('#poll_image').show();
				$('#home_image_container' ).css("visibility", "visible");
				$("#home_image_set").next().show();
				$("#home_image_set").next().next().show();
				$("#home_image_set" ).html('Change Image');
				$('#preview_image').attr('src',data.image);
				//$("#home_uploaded_image").html('Image Set');
				$("#home_image_set").removeClass('BorderRadius3');										
				$("#image_caption" ).val(data.caption);											
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');											
				$("#physical_name").val(physical_name);											
				$("#physical_name").attr('physical_extension',data.physical_extension);
				$("#physical_name").attr('readonly',true);	
				$("#image_caption").attr('readonly',true);	
				$("#image_alt").attr('readonly',true);											
				$("#image_alt" ).val(data.alt);
				$('#temp_image_id').val(data.image_id);
				//$("#img_id").val('');
				CheckImageContainer();
				$("#LoadingSelectImageLibrary").hide(); 
				$("#is_image_from_library").val('1');
				
				$('#Image_content_id').val(data.image_id);
				//$("#physical_name").attr('readonly',false);
				},
				error: function(){
							$("#loading_msg").html("");
							$("#commom_loading").hide();
						}
			}); 				

}


function call_infinite_scroll() {
	 var $container = $('.popup_images');
		
	 $container.infinitescroll({
	  navSelector  : '#page-nav',    // selector for the paged navigation 
	  nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
	  itemSelector : '#image_lists_images_id',
	   binder :  $container ,
	  debug : true,
		  // selector for all items you'll retrieve
	  loading: {
		  
		  finishedMsg: 'No more images to load.',
		  img: '<?php echo base_url(); ?>images/admin/loadingimage.gif',
		  msgText: "<em>Loading the next set of images...</em>"
		},
		state: { isDone:false }
	  },
	  // trigger Masonry as a callback
	  function( newElements ) {
		// hide new items while they are loading
		var $newElems = $( newElements ).css({ opacity: 0 });
		// ensure that images load before adding to masonry layout
		$newElems.imagesLoaded(function(){
		  // show elems now they're ready
		  $newElems.animate({ opacity: 1 });
		  //console.log("container add");
			$container.masonry( 'appended', $newElems, true );	
		});
	  }
	);
	
}


function Image_Search(Caption) {

 var $container = $('.popup_images');
 $container.empty();
if($.trim($container.html()) == '') {
$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
}	
	//var Caption = $("#search_caption").val();
	
	postdata = "Caption="+Caption;
	$.ajax({
		url: base_url+folder_name+"/poll_manager/search_image_library",
		type: "POST",
		data: postdata,
		dataType: "json",
		success: function(data){
			
			var Content = '';
			var Count 	= 0;
			var Image_URL = "<?php echo image_url.imagelibrary_image_path;?>";
			var active_image_id = null;
			$.each(data, function(i, item) {
				var active_class = "";
				//alert();
				//console.log(item);
				if(Count == 0) {
					var image_name 	= '';
					image_name		= item.ImagePhysicalPath.split('/');		
					image_name		= image_name[image_name.length-1];
					image_name		= image_name.split('.');
					image_name		= image_name[0];
					
					$("#textarea_alt").text(item.ImageAlt);
					$("#textarea_caption").text(item.ImageCaption);
					$("#image_name").html(image_name);
					$("#image_date").html(item.Modifiedon);
					$("#image_path").attr('src',Image_URL+item.ImagePhysicalPath);
					$("#browse_image_id").val(item.content_id);
					
					$("#browse_image_id").val($(this).attr('rel'));
					
					$("#browse_image_id").data("image_source",Image_URL+item.ImagePhysicalPath);
					$("#browse_image_id").data("content_id",item.content_id);
					$("#browse_image_id").data("image_alt",item.ImageAlt);
					$("#browse_image_id").data("image_caption",item.ImageCaption);
					$("#browse_image_id").data("image_date",item.Modifiedon);
					$("#browse_image_id").data("image_path",Image_URL+item.ImagePhysicalPath);
					
					active_class = 'active';							
					active_image_id = item;
				}
				else
				{
					active_class = '';
				}
			Content +='<img id="image_lists_images_id" data-content_id="'+item.content_id+'"  data-image_caption="'+item.ImageCaption+'" data-image_alt="'+item.ImageAlt+'"  data-image_date="'+item.Modifiedon+'" data-image_source="'+Image_URL+item.ImagePhysicalPath+'"  src="'+Image_URL+item.ImagePhysicalPath+'" class="'+ active_class +'" />';
				Count++;
			});
			if(Content != "") {
				$('.popup_images').html(Content);
				$('#browse_image_id').val(active_image_id.content_id);
				$("#browse_image_id").attr("data-image_source", Image_URL + active_image_id.ImagePhysicalPath);
				$("#browse_image_id").attr("data-content_id", active_image_id.content_id);
				$("#browse_image_id").attr("data-image_alt", active_image_id.ImageAlt);
				$("#browse_image_id").attr("data-image_caption", active_image_id.ImageCaption);
				$("#browse_image_id").attr("data-image_date", active_image_id.Modifiedon);
				$("#browse_image_id").attr("data-image_path", Image_URL + active_image_id.ImagePhysicalPath);
			} else {
			$("#image_lists_id").html("No Data");
			}
			
			
			
			//$('.popup_images').masonry('reload');
			//$('.popup_images').infinitescroll('destroy'); // Destroy
			
			// Undestroy
			/*$('.popup_images').infinitescroll({ 				
				state: {                                              
						isDestroyed: false,
						isDone: false                           
				}
			});*/
			//console.log("destory");	
			//$('.popup_images').infinitescroll('bind');
			//$('.popup_images').infinitescroll('retrieve');
			//$("#clear_search").show(); 
		}
	});
}
var remove_image = false;
//////  Remove existed Image  ///////
function remove_custom_image()
{
	remove_image = true;
	//$(".save_custom_details").trigger( "click" );
							
	if(confirm("Are you sure you want to remove image?"))
	{
		$('#poll_image').hide();
		$('#preview_image').attr("src", "#");
		$('#home_image_set').text('Browse');
		$('#home_image_set').addClass('browse-image');
		$('#edit_image_span').hide();
		$('#remove_image_span').hide();
		$("#image_library_id").val("");
		
		$("#img_id").val("");
		$("#img_path").val("");
		$("#temp_image_id").val("");

		$("#physical_name").val("");
		$("#image_alt").val("");
		$("#image_caption").val("");
		return true;
	}
	else
	{
		return false;
	}
			
}

</script>