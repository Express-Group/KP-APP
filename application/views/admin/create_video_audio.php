<link href="<?php echo base_url(); ?>css/admin/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/admin/drop-down-nav.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />	
<script src="<?php echo base_url(); ?>js/tabcontent.js" type="text/javascript"></script>

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
  
<link href="<?php echo base_url(); ?>css/admin/jquery-ui-autocomplete.css" rel="stylesheet" type="text/css" />
 <link href="<?php echo base_url(); ?>css/admin/jquery-ui-custom.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.10.2-autocomplete.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui-autocomplete.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>js/tags/angular.js"></script>
<script src="<?php echo base_url(); ?>js/tags/bootstrap-tagsinput.js"></script>
<script src="<?php echo base_url(); ?>js/tags/bootstrap-tagsinput-angular.js"></script>
<script src="<?php echo base_url(); ?>js/tags/typeahead.js"></script>
<link href="<?php echo base_url(); ?>css/admin/tag/bootstrap.css" rel="stylesheet" type="text/css" />	
<link href="<?php echo base_url(); ?>css/admin/tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />	
<!--dropdown link-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>js/w2ui-fields-1.0.min.js"></script>
<!--video light box-->
<script type="text/javascript" src="<?php echo base_url(); ?>includes/ckeditor/ckeditor.js"></script>
<link href="<?php echo base_url(); ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/additional-methods.min.js"></script>	
<script type="text/javascript" src="<?php echo base_url();?>js/create_audio_video_js.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.remodal.js"></script>

<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; </script>
<!--check box-->


 
<style>
  label.error
  {
	  color:#F00;
	  display: table;
  }
 #tag_error
  {
	  color:#F00;
	  display: table;
  }
 
</style>  

 
<?php $check_page_func = $this->uri->segment(3);
$check_content_type = $this->uri->segment(4); 

switch($check_content_type)
{
	case "4":
		$page_name = "Create Video";
		if($check_page_func == "create_page")
			$page_name = "Create Video";
		elseif($check_page_func == "edit_data")
			$page_name = "Edit Video";
		$page_redirect = "video_manager/";
		break;
	case "5":
		$page_name = "Create Audio";
		if($check_page_func == "create_page")
			$page_name = "Create Audio";
		elseif($check_page_func == "edit_data")
			$page_name = "Edit Audio";
		$page_redirect = "audio_manager/";
		break;
	default:
		$page_name = '';
		$page_redirect = '';
}

?>
<script type="text/javascript"> var check_content = '<?php echo $check_content_type; ?>'; </script>

<div class="Container">
<div class="BodyWhiteBG Overflow">

<form  name="video_audio_upload" id="video_audio_upload" action="<?php echo site_url();?>niecpan/audio_video_manager/insert_update" method="post"  novalidate="novalidate"  enctype="multipart/form-data" >

<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#"><?php echo $page_name; ?></a></div>
 <h2 class="FloatLeft"><?php echo $page_name; ?></h2>
 
 <div class="FloatLeft SessionError" id="flash_msg_id" style="display:none;top: 145px;">
<?php if($this->session->flashdata('msg')){ 
echo $this->session->flashdata('msg');
} ?></div>

</div>

<?php
if(isset($fetch_values) && !empty($fetch_values))
{
	 foreach($fetch_values as $displ_data)
	 {
		$get_content_id = $displ_data->content_id;
		$URLTitle = stripslashes($displ_data->url_title);
		$Title = stripslashes($displ_data->Title);
		$Description = stripslashes($displ_data->Description);
		$Tags = stripslashes($displ_data->Tags);
		
		if($check_content_type == '4')
			$Script = stripslashes($displ_data->VideoScript);
		elseif($check_content_type == '5')
			$Script = stripslashes($displ_data->AudioScript);	
			
		$MetaTitle = stripslashes($displ_data->MetaTitle);
		$MetaDescription = stripslashes($displ_data->MetaDescription);
		$opengraph = $displ_data->Addto_opengraphtags;	 
		$twitter = $displ_data->Addto_twittercards;	 
		$schema = $displ_data->Addto_schemeorggplus;	 
		$Author_id = $displ_data->Author_ID;	 
		$SectionID = $displ_data->Section_id;	
		$Country_ID = $displ_data->Country_ID;
		$State_ID = $displ_data->State_ID;
		$City_ID = $displ_data->City_ID;
		$status = $displ_data->status;
		$socialbutton =  $displ_data->Allowsocialbutton;
		$cbComments = $displ_data->Allowcomments;
		$Canonicalurl =  stripslashes($displ_data->Canonicalurl);
		$cbNoIndex = $displ_data->Noindexed;
		$cbNoFollows =  $displ_data->Nofollow;
		$urlStructure =  stripslashes($displ_data->url);
		$publishedon = $displ_data->Lastpublishedon;
		$unpublishedon= $displ_data->Lastunpublishedon;
		$contentversion_id= $displ_data->contentversion_id;
		$VersionNumber= $displ_data->VersionNumber;
		$Agency_ID= $displ_data->Agency_ID;
		$Createdby = $displ_data->Createdby;
		$Createdon = $displ_data->Createdon;
		$Modifiedby = $displ_data->Modifiedby;
		$Modifiedon = $displ_data->Modifiedon;
		$get_image_id = $displ_data->image_id;
		$get_summarytext = $displ_data->SummaryHTML;
	 }
	 
	
     
	$publishdate  = '';
	$unpublishdate  = '';
	 
	switch($status)
	{
		case "P":
			$print_status = "Published";
			$publishdate  = date('d-m-Y H:i:s',strtotime($publishedon));
			break;
		case "U":
			$print_status = "Unpublished";
			$unpublishdate  = date('d-m-Y H:i:s',strtotime($unpublishedon));
			break;
		case "A":
			$print_status = "Ready for Approval";
			break;
		case "D":
			$print_status = "Draft";
			break;
		default:
			$print_status = "None";
			break;
	}
}
?>

<script type="text/javascript">
	 var content_id = '<?php if(isset($get_content_id))  echo $get_content_id; ?>';
	 var status_img_check = '<?php if(isset($get_image_id))  echo $get_image_id; ?>';
</script>

<script>
<?php if(isset($get_content_id) && $get_content_id != "") { ?>

		function display_status(print_status,publish_date)
		{
		var postdata = "get_content_id="+<?php echo $get_content_id ?>+"&contentversion_id="+<?php echo $contentversion_id ?>+"&print_status="+print_status+"&publishdate="+publish_date+"&unpublishdate="+"<?php echo $unpublishdate ?>"+"&content_type="+<?php echo $check_content_type ?>;
		
			$.ajax({
					url: base_url+"niecpan/common/status_statement", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data:  postdata,
					dataType: "HTML",
					async: false, 
					success: function(data)   // A function to be called if request succeeds
					{
						//console.log(data.indexOf("Status"));
						if (data.indexOf("LIVE | Status") >= 0) 
							$("#ddSection").attr('disabled',true);
						else
							$("#ddSection").attr('disabled',false);
							
						$("#TopStatusId").html(data);
						
						var Status_string = "<p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($Createdby)).' | '.'Created On : '. date('d-m-Y H:i:s',strtotime($Createdon)).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($Modifiedby)).' | '.'Last Updated On : '. date('d-m-Y H:i:s',strtotime($Modifiedon)); ?></p>";
					
						if (data.indexOf("LIVE | Status") >= 0) 
							$("#TopStatusId").append(Status_string);
						
						$("#main_content").css("margin-top","177px");
					}
			});
		}
<?php } ?>
</script>

<p class="FloatRight save-back save_margin article_save">
 <a class="back-top FloatLeft" href="<?php echo site_url();?>niecpan/<?php echo $page_redirect; ?>" title="Go Back"><i class="fa fa-reply fa-2x"></i></a>
 
<?php if(isset($get_content_id) && $get_content_id != '') { ?>
<?php if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 'Y') { ?>
<button class="btn btn-primary FloatRight i_button FlagIcon2" type="button" id="btnUnpublish" name="btnUnpublish"  title="Unpublish" <?php if( isset($status) && ($status == "U" || $status == "A" or $status == "D") or !isset($get_content_id)){ ?> style="display:none" <?php } ?>></button>
<?php } ?>
<?php } ?>

<?php if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 'Y') { ?>
<button class="btn btn-primary FloatRight i_button" type="button"  id="btnPublish" name="btnPublish" title="Publish" ><i class="fa fa fa-flag"></i></button>
<?php } ?>

<?php if(isset($get_content_id) && $get_content_id != '') { ?>
<button class="btn btn-primary FloatRight i_button" type="button" id="btnSaveStatus" name="btnSaveStatus"  title="save" <?php if( isset($status) && ($status == "A" or $status == "D")){ ?> style="display:none" <?php } ?>><i class="fa fa-floppy-o"></i></button>
<?php } ?>

<button class="btn btn-primary FloatRight i_button" type="button" id="btnApproval" name="btnApproval" title="Ready for Approval" <?php if( isset($status) && ($status == "P" or $status == "U")){ ?> style="display:none" <?php } ?> ><i class="fa fa-share-square-o"></i></button>

<button class="btn btn-primary FloatRight i_button" type="button"  id="btnDraft" name="btnDraft" title="Draft"  <?php if( isset($status) && ($status == "P" or $status == "U")){ ?> style="display:none" <?php } ?> ><div class="DraftIcon"></div>
</button>

<?php if(isset($get_content_id) && $get_content_id != '' && isset($get_publish_history) && !empty($get_publish_history)) { ?>
  <a class="back-top FloatRight top_iborder" href="javascript:void(0);" id="publish_history" title="View Publish History"><i class="fa fa-history fa-2x"></i></a>
<?php } ?>

<a class="back-top FloatRight" href="#" title="Preview"><i class="fa fa-desktop i_extra"></i></a>

</p>

<?php if(isset($print_status) && $print_status != "")  { ?>
 <span class="TopStatus" id="TopStatusId"></span>
 <?php } ?>
 
</div>

<div class="Overflow DropDownWrapper"  id="main_content">

<?php if(isset($VersionNumber) && $VersionNumber != "") { ?>
	<p><?php echo 'Current version: '.$VersionNumber.'.0'; ?></p>
	<p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($Createdby)).' | '.'Created On : '. date('d-m-Y H:i:s',strtotime($Createdon)).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($Modifiedby)).' | '.'Last Updated On : '. date('d-m-Y H:i:s',strtotime($Modifiedon)); ?></p>
<?php } ?>
  
 
<ul class="tabs margin-top-0 Article-Tab">
            <li class="selected" id="content_div"><a href="#view1">Details</a></li>
            <li class=""><a href="#view2">Multi Section Mapping</a></li>
            <?php if(isset($get_content_id) && $get_content_id != '') { 
			if(isset($get_version_details) && count($get_version_details) > 1) { ?>
            <li class="" id="version_div"><a href="#view4">Version</a></li>
            <?php  } } ?>
        </ul>

 <section class="tap-main Article-Tab">
        
        <div class="tabcontents">
            <div id="view1" style="display: block;">
                
    <section class="form2">
    <table class="VideoTable">
    <col width="auto" />
    <col width="750px" />
    
     <tr>
     <input type="hidden" id="hidden_status" name="hidden_status" value="" />
     
     <input type="hidden" name="txtOldStatus" id="old_status_id" value="<?php if(isset($status)) echo $status; ?>" />
	<input type="hidden" name="txtContentVersionId" id="content_version_id" value="<?php if(isset($contentversion_id)) echo $contentversion_id; ?>" />
    <input type="hidden" name="txtContentId" id="content_id" value="<?php if(isset($get_content_id)) echo $get_content_id; ?>" />
	<input type="hidden" name="txtVersionNumber" value="<?php if(isset($VersionNumber)) echo $VersionNumber; ?>" />
	<input type="hidden" name="txtContentType" id="txtContentType" value="<?php echo $check_content_type; ?>" />

		
      <td class="video_label"><label>URL Title</label></td>
      <td><input type="text" class="box-shad" style="width:99.8% " name="txtUrlTitle" id="txtUrlTitle" maxlength="255" value="<?php if(isset($URLTitle) && $URLTitle != "") { echo $URLTitle; } echo htmlentities(set_value('txtUrlTitle')); ?>"  />
       	<input type="hidden" name="hiddn_fld" id="hiddn_fld" value="<?php if(isset($get_content_id) && $get_content_id != "") {  echo $get_content_id; } echo set_value('hiddn_fld'); ?>" />

       <?php echo form_error('txtUrlTitle'); ?>
      </td>
     </tr>
     
   	 <tr>
      <td class="video_label"><label>Article Page Title<span style="color:#F00">*</span></label></td>
      <td class="VideoCk GalleryNameCount  PositionRelative">
      <div id="cke_charcount_txtTitle" length="100" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($Title)) echo mb_strlen($Title); else echo "100"?></div>
      
      	<textarea class="ckeditor" name="txtTitle" id="txtTitle"><?php if(isset($Title) && $Title != "") { echo $Title; } echo set_value('txtTitle'); ?></textarea>

       <?php echo form_error('txtTitle'); ?>
       <p id="title_error" style="color:#F00"></p>
      </td>
     </tr>
     
	 <tr>
	 	<td class="video_label"><label>Summary</label></td>
		<td class="VideoCk GalleryNameCount  PositionRelative">
		<div id="cke_charcount_txtSummary" length="200" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_summarytext)) echo mb_strlen($get_summarytext); else echo "200"?></div>
		<textarea class="ckeditor" id="txtSummary"  name="txtSummary"><?php if(isset($get_summarytext)) echo $get_summarytext; echo set_value('txtSummary'); ?></textarea></td>
	</tr>
     
       <tr>
      <td class="video_label"><label>Description<span style="color:#F00">*</span></label></td>
      <td>
        <textarea class="box-shad"  name="txtDescription" id="txtDescription" maxlength="252"><?php if(isset($Description) && $Description != "") { echo $Description;} echo set_value('txtDescription');?></textarea>
     <?php echo form_error('txtDescription'); ?>
      </td>
     </tr>
     
       <tr>
      <td class="video_label"><label>Tags<span style="color:#F00">*</span></label></td>
      <td>
             
       <div class="FloatLeft">
       
<!--<div class="w2ui-label"> List: </div>-->

<!--<div class="video_lang Overflow">

<div class="VideoLangBG FloatLeft"></div>
<div class="w2ui-field video_lang1 FloatLeft">
<div class="controls">
  <select name="category" id="category">
    <option value="">- Select a category -</option>
    <option class="unavailable" data-name="k" value="33" > kdssdfsdfs</option>
    <option data-name="text-1" value="35"> text sddsfsssss</option>
  </select>
</div>

<div class="w2ui-field-helper video_dropdown2">


</div>
</div>

</div>-->

			
            <section id="examples">
               	<div class="example example_markup">

    <div id="tagform-full"  class="bs-example">
		<?php if(isset($get_tags)) { 
				foreach($get_tags as $tags) { ?>
			<input type="text" name="txtTags[<?php echo $tags->tag_id; ?>-a]" id="txtTags"  value="<?php echo $tags->tag_name; ?>" class="tag"/>
       			<?php }  } else { ?>
            <input type="text" name="txtTags[]" id="txtTags"  value="" class="tag"/>
                <?php } ?>
                
                <span id="tag_error"></span>
    </div>
                </div>
		</section>
        
         <div id="suggestion_div" class="TagSuggestion">
        </div>
</div>
     
      </td>
     </tr>
     
      <tr>
      <td  class="video_label"><label>Script<span style="color:#F00">*</span></label></td>
      <td><textarea class="box-shad VideoFramePreview" id="txtScript" name="txtScript" ><?php if(isset($Script) && $Script != "") { echo ($Script);  } echo set_value('txtScript');?></textarea>
      
      <div  id="check_popup" >
            <a href="#" id="popup_event" class="btn btn-primary"  title="Preview"><i class="fa fa-desktop"></i></a>
            </div>
     
            <div class="remodal" data-remodal-id="modal2" style="position:relative;">
            <div id="play_video_div">
            <?php if(isset($Script) && $Script != "") { echo $Script;  }?>
            </div>
            </div>
            
			   <?php echo form_error('txtScript'); ?><br />
               <p id="error_txtScript" class="WidthPercentage FloatLeft" style="color:#F00"></p>
              
      </td>
      <td>
  
    </td>
     </tr>
     
    </table>
    
    
    
    <table class="VideoTable02">
      
       <tr>
       
             <td class="MediumInputBox video_label"><label>Section<span style="color:#F00">*</span></label></td>
              <td class="ValignTop"><div class="FloatLeft"> 
                  <div class="w2ui-field">
                  <div >
                  <select name="ddSection" class="controls" id="ddSection" Value="<?php echo set_value('ddSection'); ?><?php echo @$get_article_details['Section_id']; ?>">
   <option value="">-Select-</option>
  
 <?php if(isset($get_result)) { 
				 foreach($get_result as $mapping) {  
				 
				 if($check_content_type == 4)
					 $page_section_name = 'Videos';
				 else 
					 $page_section_name = 'Audios';
				 
				 if($mapping['Sectionname'] == $page_section_name && $mapping['Sectionname'] != 'Contact Us' && $mapping['Sectionname'] != 'Advertise With Us' && $mapping['Sectionname'] != 'Terms of Use' && $mapping['Sectionname'] != 'Privacy Policy' && $mapping['Sectionname'] != 'Contact Us'  && $mapping['Sectionname'] != 'About Us'  && strtolower($mapping['Sectionname']) != 'search') {
				 
				 
				 ?>

<option <?php if($mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?> class="blog_option" <?php if(set_value("ddMainSection") == $mapping['Section_id'] || (isset($SectionID) && $SectionID == $mapping['Section_id'] )) echo  "selected";  ?> section_data="<?php echo @$mapping['Sectionname']; ?>" rel="<?php echo @$mapping['LinkedToColumnist']; ?>"  value="<?php echo $mapping['Section_id']; ?>"><?php echo strip_tags($mapping['Sectionname']); ?></option>
  <?php if(!(empty($mapping['sub_section']))) { ?>
 
  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
    <option  <?php if(set_value("ddMainSection") == $sub_mapping['Section_id']  || (isset($SectionID) && $SectionID == $sub_mapping['Section_id'] )) echo  "selected"; ?>  section_data="<?php echo @$mapping['Sectionname']; ?>"  rel="<?php echo @$sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
		
		 <?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
		 
		   <?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
    <option <?php if($sub_sub_mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?>  <?php if(set_value("ddMainSection") == $sub_sub_mapping['Section_id']  || (isset($SectionID) && $SectionID == $sub_sub_mapping['Section_id'] )) echo  "selected"; ?>  rel="<?php echo @$sub_sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_sub_mapping['Section_id']; ?>"  section_data="<?php echo @$mapping['Sectionname']; ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
		 
		<?php } } ?>
  <?php  } } ?>


				 <?php } } } ?>

</select>
        
                    </div>
                    </div>
                    </div>
                
        <?php echo form_error('ddSection'); ?> 
             </td>
       </tr>



    <tr>
     <td class="MediumInputBox video_label"><label>Agency<span style="color:#F00">*</span></label></td>
                          <td class="ValignTop">
                          <div class="FloatLeft"> 
                              <div class="w2ui-field">
                              <div >
	<select name="ddAgency"  class="controls" id="ddAgency"  >
   <option value="">-Select-</option>
    <?php if(isset($get_agency)) { 
					  foreach($get_agency as $agency) {?>
                      
                       <option value="<?php echo $agency->Agency_id; ?>" <?php if(set_value("ddAgency") == $agency->Agency_id || (isset($Agency_ID) && $Agency_ID == $agency->Agency_id)) echo  "selected='selected'";  ?> ><?php echo ucfirst($agency->Agency_name); ?></option>
                      
                      <?php } } ?>               
   </select>
  
			 </div>
                            </div>
                            </div>
                            
             <?php echo form_error('ddAgency'); ?> 
                       </td>
                       
                       
                       <td id="byline_row" class="video_label video_label22">
         <label>Byline</label>
         </td>
         
         <td>
         
         <input type="text" name="txtbyline" id="txtbyline" value="<?php if(isset($select_author) && $select_author != "") {  echo htmlentities($select_author); } echo htmlentities(set_value('txtbyline')); ?>"  />
         <input type="hidden" id="byline_ID" name="byline_ID" value="<?php if(isset($Author_id) && $Author_id != "") {  echo $Author_id; } echo set_value('byline_ID'); ?>" />
       
          <p id="byline_error" style="color:#F00"></p>
         </td>
       </tr>


<tr>
         <td class="video_label">
         <label>Country<span style="color:#F00">*</span></label>
         </td>
         <td class="ValignTop">
         <div class="FloatLeft">
<div class="w2ui-field" id="SelectCountry">
<div>
 <select id="country_id" name="country_id" class="controls">
    <option value="">- Select -</option>
    
    <?php 
		foreach($get_country as $disp_country)
		{
			$cntry_id = $disp_country->Country_id;
			$cntry_name = $disp_country->CountryName;
		?>
    <option class="unavailable" data-name="k" value="<?php echo $cntry_id; ?>"  <?php if(isset($Country_ID) && $Country_ID == $cntry_id) { ?> selected="selected" <?php } echo set_select('country_id', $cntry_id);?> >  <?php echo $cntry_name; ?></option>
      <?php } ?>
  </select>
</div>

</div>
</div>
<?php echo form_error('country_id');?>
</td>
   
         <td class="video_label  video_label22">
         <label>State</label>
         </td>
         <td class="ValignTop">
         <div class="FloatLeft">
<div class="w2ui-field" id="SelectAuthor">
<div>
  
   <input type="text" name="txtState" id="txtState" value="<?php if(isset($select_state) && $select_state != "") {  echo $select_state; } echo set_value('txtState'); ?>"  />
    <input type="hidden" id="state_id" name="ddState" value="<?php if(isset($State_ID) && $State_ID != "") {  echo $State_ID; } echo set_value('ddState'); ?>" />
         
</div>

</div>
</div>
<?php echo form_error('state_id');?>
</td>
       </tr>
       
<tr>
         <td class="video_label">
         <label>City</label>
         </td>
         <td>
         <div class="FloatLeft">
<div class="w2ui-field" id="SelectAuthor">
<div>

         <input type="text" name="txtCity" id="txtCity" value="<?php if(isset($select_city) && $select_city != "") {  echo $select_city; } echo set_value('txtCity'); ?>"  />
         <input type="hidden" id="city_id" name="ddCity" value="<?php if(isset($City_ID) && $City_ID != "") {  echo $City_ID; } echo set_value('ddCity'); ?>" />

</div>

</div>
</div>
<?php echo form_error('city_id');?>
</td>
       </tr>              
     
     <tr style="display:none">
    <td></td>
    <td>
    <input type="checkbox" id="cbSocialButtons" name="cbSocialButtons" value="A" <?php if((isset($socialbutton)) && $socialbutton == 'A') { ?> checked="checked" <?php } echo set_checkbox('cbSocialButtons', 'A',TRUE);  ?>><label class="include_label" for="cbSocialButtons">Allow Social Buttons</label></td>
  </tr>
  
  <tr>
    <td></td>
    <td><input type="checkbox" id="cbComments" name="cbComments" value="A" <?php if((isset($cbComments)) && $cbComments == 'A') { ?> checked="checked" <?php } echo set_checkbox('cbComments', 'A', TRUE);  ?>><label class="include_label" for="cbComments">Allow Comments</label></td>
  </tr>
     
       
    </table>
    
   <table class="VideoTable03 VideoImage" style="position:relative">   
     <tr>
      <td class="video_label"><label>Display Image<span style="color:#F00">*</span></label></td>
      <td> <div class="fileUpload btn btn-primary">
    <span>Browse</span>
    <input type="file"  class="upload" id="btnImage" name="btnImage" />
   </div>
    <span id="image_error"></span>
    <?php echo form_error('btnImage'); ?>
    <?php  if(isset($data)) { echo $data; } ?>
    </td>
   
    <span style="position:absolute; right:56%;">
		
        <?php
        if(isset($get_image_id) && $get_image_id != "")
        {
			$ImageURL = get_image_by_contentid($get_image_id);
			
			$ImageDetails = GetImageDetailsByContentId($get_image_id);
			
			$Physical_URL = $ImageDetails['ImagePhysicalPath'];
			
			$Physical_array = explode('.',$Physical_URL );
			$Physical_extension = $Physical_array[1];
			
			$Physical_name = GetPhysicalNameFromPhysicalPath($Physical_URL);
			
        ?>   
        	<img id="preview_image" name="preview_image" src="<?php echo @$ImageURL ?>" border="0" style="width:70px; height:70px;"/>
        <?php
        } 
		else
		{
        ?> 
         	<img id="preview_image" name="preview_image" src="#" alt="" border="0" style="width:70px; height:70px;" />
        <?php } ?> 
        
		
		
        <input type="hidden" id="hidden_image_id" name="hidden_image_id" value="<?php if(isset($get_image_id) && $get_image_id != "") { echo $get_image_id; }?>" />
   </span>
   
   <tr>
   <td class="video_label"><label>Image Name<span style="color:#F00">*</span></label></td> 
   <td><input type="textbox" name="physical_name" id="physical_name" rel="<?php echo @$get_image_id; ?>" physical_extension="<?php echo @$Physical_extension ?>" value="<?php echo @$Physical_name; ?>" />
   <span id="physical_error"></span>
   </td>
   
    </tr>
   </table>
   
    <div class="Overflow FloatLeft MetaTitleWrapper">
   
   <table class="FloatLeft metatitle1">
    <tr style="display:none;">
      <td class="video_label"><label>URL structure</label></td>
      <td>
      <textarea class="box-shad"  maxlength="252" name="txtUrlStructure" readonly="readonly" id="txtUrlStructure"><?php if(isset($urlStructure) && $urlStructure != "") { echo $urlStructure;} else {?> URL struture not yet finalised. Will be implemented later<?php } ?></textarea>
       <?php echo form_error('txtUrlStructure'); ?>
      </td>
     </tr>
     
     <tr>
      <td class="video_label"><label>Meta Title<span style="color:#F00">*</span></label></td>
      <td><input type="text" class="box-shad"  maxlength="100" name="txtMetaTitle" id="txtMetaTitle" value="<?php if(isset($MetaTitle) && $MetaTitle != "") {  echo htmlentities($MetaTitle);  } echo htmlentities(set_value('txtMetaTitle'));?>"  />
       <?php echo form_error('txtMetaTitle'); ?>
       <td>
        <div id="charNum1" class="charNum"><?php if(isset($MetaTitle)) echo 100 - mb_strlen($MetaTitle); else echo "100"?></div>
        </td>
      </td>
     </tr>
     
     <tr>
      <td class="video_label"><label>Meta Description<span style="color:#F00">*</span></label></td>
      <td><textarea class="box-shad"  name="txtMetaDescription" maxlength="200" id="txtMetaDescription"><?php if(isset($MetaDescription) && $MetaDescription != "") { echo htmlentities($MetaDescription);} echo htmlentities(set_value('txtMetaDescription'));?></textarea>
      <?php echo form_error('txtMetaDescription'); ?>
      <td>
      <div id="charNum2" class="charNum"><?php if(isset($MetaDescription)) echo 200 - mb_strlen($MetaDescription); else echo "200"?></div>
      </td>
      </td>
     </tr>
     
     <tr>
      <td class="video_label"><label>Canonical URL</label></td>
      <td><input type="text" class="box-shad"  maxlength="252" name="txtCanonicalURL" id="txtCanonicalURL" value="<?php if(isset($Canonicalurl) && $Canonicalurl != "") {  echo $Canonicalurl;  } echo set_value('txtCanonicalURL');?>"  />
       <?php echo form_error('txtCanonicalURL'); ?>
      </td>
     </tr>
   </table>
   

   <div class="MetaDescription FloatLeft">
   
    <h3>Social Meta Tag</h3>
   <ul>

    <li>
    <input type="checkbox" id="test1" name="cbOpengraph" value="Y" <?php if((isset($opengraph)) && $opengraph == 'Y') { ?> checked="checked" <?php } echo set_checkbox('cbOpengraph', 'Y');  ?>>
<label class="include_label" for="test1">OpenGraph Tags</label>
    </li>
    
   
    <li>
    <input type="checkbox" id="test2" name="cbTwitter" value="Y" <?php if(isset($twitter) && $twitter == 'Y') { ?> checked="checked" <?php } echo set_checkbox('cbTwitter', 'Y'); ?> >
<label class="include_label" for="test2">Twitter Cards</label>
    </li>
    
    <li>
    <input type="checkbox" id="test3" name="cbSchema" value="Y" <?php if(isset($schema) && $schema == 'Y') { ?> checked="checked" <?php } echo set_checkbox('cbSchema', 'Y'); ?> >
<label class="include_label" for="test3">Schema.Org(G+)</label>
    </li>
    
   
   </ul>
   
   
    <h3>Crawler</h3>
   <ul>

    <li>
    <input type="checkbox" id="cbNoIndex" name="cbNoIndex" value="Y" <?php if((isset($cbNoIndex)) && $cbNoIndex == 'Y') { ?> checked="checked" <?php } echo set_checkbox('cbNoIndex', 'Y');  ?>>
<label class="include_label" for="cbNoIndex">No index</label>
    </li>
    
   
    <li>
    <input type="checkbox" id="cbNoFollows" name="cbNoFollows" value="Y" <?php if(isset($cbNoFollows) && $cbNoFollows == 'Y') { ?> checked="checked" <?php } echo set_checkbox('cbNoFollows', 'Y'); ?> >
<label class="include_label" for="cbNoFollows">No follows</label>
    </li>
    
   
   </ul>
   </div>
  </div>
  </section>
   
   </div>
   
   <div id="view2" style="display: none;">
          
      <div class="mapping_section">
      <div class="tree_check"><input type="checkbox" id="cbMappedSec" name="cbMappedSec"  class="show_list">
      	<label for="cbMappedSec">Show the mapped section</label>
      </div>
      <div class="mapping_contents">
         <h1>Section and Sub-Section Mapping</h1>
      <div class="tree_head tree_head_ul" id="mapping_1">
      
      <?php
            foreach($get_result as $disp_multi_sect)
            { 
                $multi_sec_id = $disp_multi_sect['Section_id'];
                $multi_sec_name = $disp_multi_sect['Sectionname'];
				$sec_landing = $disp_multi_sect['Section_landing'];
				if(!empty($disp_multi_sect['sub_section']))
				{
      ?>
            
      <ul class="tree_head_ul">
      
      <li>
        <label class="tree_list_color" for="test5[<?php echo $multi_sec_id; ?>]"><i class="" val="1" id="MainSectionMapping"></i><?php echo $multi_sec_name; ?> </label>
        
          <ul class="tree_sub_ul" id="SubSection1">
          
           <?php
                foreach($disp_multi_sect['sub_section'] as $sub_sec_list)
                {
                    $multi_sub_sec_id = $sub_sec_list['Section_id'];
                    $multi_sub_sec_name = $sub_sec_list['Sectionname'];
					$multi_sub_sec_landing = $sub_sec_list['Section_landing'];
            ?> 
            
                <li>
                <input type="checkbox" value="<?php echo $multi_sub_sec_id; ?>"  rel="<?php echo $multi_sub_sec_name; ?>" main_section="<?php echo $multi_sec_name; ?>" name="cbSectionMapping[]" class="sect_mappping" id="test6[<?php echo $multi_sub_sec_id; ?>]"                    
                    		 <?php 
							if(isset($fetch_mappng_data) && !empty($fetch_mappng_data))
							{
                                foreach($fetch_mappng_data as $disp_sub_mapping)
                                {
                                    $disp_sub_sec_id = $disp_sub_mapping->Section_ID;
                                    if($disp_sub_sec_id ==$multi_sub_sec_id )
                                    {
                             ?> checked="checked"
                            <?php 	} 
                             	}
							} echo set_checkbox('cbSectionMapping[]', $multi_sub_sec_id);
							?> 
                            <?php if(isset($multi_sub_sec_landing) && $multi_sub_sec_landing == 1) { echo "disabled"; } ?>
                     />
                	<label for="test6[<?php echo $multi_sub_sec_id; ?>]"><?php echo $multi_sub_sec_name; ?></label>
                </li>
         	 <?php } ?>
          </ul>
         
      </li>
      	<?php } else {
			  ?> 
              <li>
              <input type="checkbox" value="<?php echo $multi_sec_id; ?>" main_section=""  rel="<?php echo $multi_sec_name; ?>" class="sect_mappping"  name="cbSectionMapping[]" id="test5[<?php echo $multi_sec_id; ?>]"
				<?php 
					if(isset($fetch_mappng_data) && !empty($fetch_mappng_data))
					{
						foreach($fetch_mappng_data as $disp_mapping)
						{
						$disp_sec_id = $disp_mapping->Section_ID;
						if($disp_sec_id ==$multi_sec_id )
						{
					?> checked="checked"
					<?php
						} 
						}
					} echo set_checkbox('cbSectionMapping[]', $multi_sec_id); 
				?> 
                <?php if(isset($sec_landing) && $sec_landing == 1) { echo "disabled"; } ?>
        />
              <label class="tree_list_color" for="test5[<?php echo $multi_sec_id; ?>]"><i class="" val="1" id="MainSectionMapping"></i><?php echo $multi_sec_name; ?> </label>
              </li>
      
      </ul>
      
      <?php } }?>
      </div>
      
      <div id="mapping_2" class="tree_head_dup">
      
      </div>
      </div>
            </div>
	</div>
         
         
         <div id="view4" style="display:none;">
        <div class="ArticleVersions CompareArticle">
 <?php if(isset($get_version_details)) { ?>
 
  <button class="btn-primary btn FloatRight Compare-Articles" type="button" style="margin-right:8px;margin-bottom:5px;" contentType="<?php echo $check_content_type; ?>" name="compare_version" id="compare_version">&nbsp;Compare</button>
    <table width="100%" cellspacing="0" class="display article_table no-footer" id="version_table" role="grid" aria-describedby="link_preview_table_info" style="width: 100%;">
   
		<thead id="link_preview_head" >
			<tr role="row">
            <th class="" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">Title</th>
            <th class="" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-label="Type: activate to sort column ascending">Version Number</th>
            <th class="" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-label="Priority: activate to sort column ascending">Action</th>
            <th class="" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-label="Action: activate to sort column ascending">Compare</th>
            </tr>
		</thead>
        
       		<tbody id="link_preview_body">
            <?php foreach($get_version_details as $version)  { ?>
              <tr class="odd">
              		<td><a href="javascript:void(0);" title="<?php echo strip_tags($version->Title); ?>"><?php echo shortDescription(strip_tags($version->Title)); ?></a></td>
                            <td><?php echo $version->VersionNumber.".0"; ?></td>
                             	<td>
                                <div class='article_table_delete'> 
                                
                     
                 <a href='javascript:void(0);' class='button tick tooltip-2' title="Publish" id="version_publish_<?php echo $version->contentversion_id; ?>" onclick="version_publish(<?php echo $version->content_id; ?>,<?php echo $version->contentversion_id; ?>)" <?php if(check_version_publish($version->content_id,$version->contentversion_id) == TRUE ) { ?> style="display:none;"  <?php } ?>><i class='fa fa-flag'></i></a>
			                
                 <a class='button cross' href="javascript:void(0);" id="view_version" contentType="<?php echo $check_content_type; ?>" rel="<?php echo $version->contentversion_id; ?>" title="View"><i class='fa fa-eye'></i></a>
                 				</div>
                                </td>
                                	<td><input type="checkbox" name="version[]" rel="<?php echo $version->contentversion_id; ?>" id="version_compare" /></td>
              </tr>
			  <?php } ?>
                </tbody>
			</table>
   <?php } ?>
        </div>
        </div>
        


               
            
            
        </div>

</section>
  </div>
  
</form>



</div>
                     
</div>                            
  
<script>

 $('.remodal').remodal()

$(document).on('closed', '.remodal', function (e) {
	$("#play_video_div iframe").attr("src","");
	$("#compare_popup iframe").attr("src","");
});


$(document).ready(function() {
	<?php if(isset($get_content_id) && $get_content_id != "") { ?>
	display_status("<?php echo $print_status ?>","<?php echo $publishdate; ?>");
	<?php } ?>
   
  
   <?php if($this->session->flashdata('msg')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 20000);
});
<?php } ?>

  $("#popup_event").click(function()
  {
	  if($.trim($("#txtScript").val()) != "")
	  {
		  //if(validate_script()) {
			iframe_script();
			var inst = $.remodal.lookup[$('[data-remodal-id=modal2]').data('remodal')];
			if(!inst)
			{
				$('[data-remodal-id=modal1]').remodal().open();
			}
			else
			{
				inst.open();
			}
		 // }
	  }
	 /* else
	  {
			$("#error_txtScript").html("Please enter a valid iframe script");
			return false;
	  }*/
  });
  
});


</script>

<script>
 $(document).ready(function(){
	$(".bootstrap-tagsinput input").css("width", "400px");
	$(".cke_1_contents").css("height", "119px"); 
	
	
	
	<?php if(isset($print_status)) { ?>
	display_status("<?php echo $print_status; ?>","<?php echo $publishdate; ?>");
	
	$MetaTitle = $.trim($("#txtMetaTitle").val());
	
	if($MetaTitle != '') {
		
	var postdata = "metatitle="+$MetaTitle;
			$.ajax({
				url: base_url+"niecpan/common/get_tags_by_meta_title", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "JSON",
				success: function(data)   // A function to be called if request succeeds
				{
					if(data != '[]') {
						$.each(data,function(i,item) {
							$("#suggestion_div").show();
							$("#suggestion_div").html('<p>  Suggestions : </p>');
							$("#suggestion_div").append('<a href="javascript:void(0);" id="suggestion_tags" data-id="'+item.tag_id+'" data-value="'+item.tag_name+'">  '+item.tag_name+'  </a>');
						});
					} else {
						$("#suggestion_div").hide();
					}
				}
			});
	} else {
		$("#suggestion_div").hide();
	}
	
<?php } ?>
	
	
 });
 
 
</script>

 <link rel="StyleSheet" href="<?php  echo base_url(); ?>includes/Tagedit-master/css/jquery.tagedit.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?php echo  base_url(); ?>includes/Tagedit-master/js/jquery.autoGrowInput.js"></script>
<script type="text/javascript" src="<?php  echo base_url(); ?>includes/Tagedit-master/js/jquery.tagedit.js"></script>


<script type="text/javascript">
	//var jqs = $.noConflict();
	$(function() {
		$( "#tagform-full" ).find('input.tag').tagedit({
			autocompleteURL: "<?php echo base_url(); ?>niecpan/common/tags",
			allowEdit: false, // Switch on/off edit entries
 			allowDelete: false // Switch on/off deletion of entries. Will be ignored if allowEdit = false
		});
	
	});
	
	
	function add_byliner(name)
	{
		var agency_id = $("#ddAgency").val();
		$.ajax({
				url: base_url+"niecpan/audio_video_manager/add_txtbyliner", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  {"byliner_txt":name, "agency":agency_id},
				dataType: "JSON",
				success: function(data)   // A function to be called if request succeeds
				{
				}
			});
	}
</script>

<script src="<?php echo base_url(); ?>js/modernizr.js"></script>
<!--tree script-->
