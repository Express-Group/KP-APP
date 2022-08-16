<?php $section_segment = $this->uri->segment('2');  ?>
<span class="css_and_js_files">
<link href="<?php echo image_url; ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
<link href="<?php echo image_url; ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />	
<link href="<?php echo image_url; ?>css/admin/jquery-ui-autocomplete.css" rel="stylesheet" type="text/css" />	
<link href="<?php echo image_url; ?>css/admin/jquery-ui-custom.css" rel="stylesheet" type="text/css" />
<link href="<?php echo image_url; ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" id="contents_css" />
</span>
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; content_type = "<?php echo $content_type; ?>"</script>

<span class="previewcontainer">

</span>

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
  .margin-left-0 {
	  margin-left:0 !important;
  }
  .padding-10 {
	  padding:10px !important;
  }
 
</style>  

<div class="Container">
<div class="BodyWhiteBG Overflow">

<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<div class="breadcrumbs"><a href="javascript:void(0);">Dashboard</a> > <a href="javascript:void(0);"><?php  echo $title;  ?></a></div>
 <h2 class="FloatLeft"><?php echo $title; ?></h2>
 
 </div>
 
 <div class="FloatLeft SessionError" id="flash_msg_id" style="display:none;top: 145px;">
<?php if($this->session->flashdata('msg')){ 
echo $this->session->flashdata('msg');
} ?></div>



<p class="FloatRight save-back save_margin article_save">

<?php
	$schedule_status = '';
	
 if(isset($get_audio_video_details['status'])) {
	$status 		= $get_audio_video_details['status'];
	$get_content_id = $get_audio_video_details['content_id'];
		
	$publishdate  	= "";
	$unpublishdate  = "";
	
	switch($status )
	{
		case "P":
			$print_status = "Published";
			$publishdate  = date('d-m-Y H:i:s',strtotime($get_audio_video_details['publish_start_date']));
			break;
		case "U":
			$print_status = "Unpublished";
			$unpublishdate  = date('d-m-Y H:i:s',strtotime($get_audio_video_details['Modifiedon']));
			break;
		case "D":
			$print_status = "Draft";
			break;
		default:
			$print_status = "None";
	}

if($status == 'P' && $get_audio_video_details['publish_start_date'] == '0000-00-00 00:00:00' ) {
		$schedule_status = '0';
} else {

	if($status != 'D' && $status != 'U') {
		if( strtotime($get_audio_video_details['publish_start_date']) <=  strtotime(date('d-m-Y H:i:s'))) {
			$schedule_status = '0';	
			$print_status = "Published";
		} else {
			$schedule_status = '1';
			$print_status  = "Scheduled";
		}
	}
	
}
}



 ?>

<a class="back-top FloatLeft" href="<?php echo base_url().folder_name; ?>/<?php if($content_type == 4) echo "video_manager"; else  echo "audio_manager";  ?>" title="Go Back"><i class="fa fa-reply fa-2x"></i></a>
<?php if($page_name == 'edit_audio' || $page_name == 'edit_video') { ?>
<?php if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { ?>
<button class="btn btn-primary FloatRight i_button FlagIcon2" id="unpublishtop_id"  title="Unpublish" <?php if( isset($status) &&$status == "D"){ ?> style="display:none" <?php } ?>></button>
<?php }  } ?>
<?php  if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {  ?>
<button class="btn btn-primary FloatRight i_button"  id="publishtop_id" title="Publish" ><i class="fa fa fa-flag"></i></button>
<?php  }  ?>

<button class="btn btn-primary FloatRight i_button"  id="send_drafttop_id" title="Draft"  <?php if( isset($status) && ($status == "P" or $status == "U")){ ?> style="display:none" <?php } ?> ><div class="DraftIcon"></div>
</button>

<!--
<a class="back-top FloatRight top_iborder" href="#preview_resource_popup" title="Preview" id="preview_id" ><i class="fa fa-desktop i_extra"></i> -->

<a class="back-top FloatRight top_iborder" href="#" data-remodal-target="preview_article_popup" title="Preview" id="preview_id" ><i class="fa fa-desktop i_extra"></i>
</a>


</a>
</p>


<?php if(isset($print_status) && $print_status != "")  { ?>
 
 <span class="TopStatus" id="TopStatusId"><?php echo "Status :". $print_status; if($publishdate != '') { echo " | Published on  : ".$publishdate; } if($unpublishdate != '') { echo " | Unpublished on  : ".$unpublishdate; } ?></span>
 
 <?php } ?>

</div>


 <?php if($section_segment  == 'edit_archive_audio' || $section_segment  == 'edit_archive_video') { ?>
 
 <form action="<?php echo base_url().folder_name; ?>/audio_video_manager/update_archive_audio_video/<?php echo @$archive_year; ?>/<?php echo @$get_audio_video_details['content_id']; ?>" method="post" id="content_form" name="content_form" enctype="multipart/form-data">

<?php } else { ?>

<form <?php if($page_name == 'create_video' || $page_name == 'create_audio' ) { ?> action="<?php echo base_url().folder_name; ?>/audio_video/add_audio_video" <?php } else {?> action="<?php echo base_url().folder_name; ?>/audio_video_manager/update_audio_video/<?php echo @$get_audio_video_details['content_id']; ?>" <?php } ?>  method="post" id="content_form" name="content_form" enctype="multipart/form-data">

<?php } ?>

<input type="hidden" name="txtStatus" id="status_id" value="" />
<input type="hidden" name="txtOldStatus" id="old_status_id" value="<?php if(isset($status)) echo $status; ?>" />
<input type="hidden" name="txtContentId" id="content_id" value="<?php if(isset($get_audio_video_details['content_id'])) echo $get_audio_video_details['content_id']; ?>" />
<input type="hidden" name="archive_year" id="archive_year" value="<?php echo @$archive_year; ?>" />
<input type="hidden" name="txtContentType" id="content_type" value="<?php echo $content_type; ?>" />
<input type="hidden" name="txtContentName" id="content_name" value="<?php echo $content_name; ?>" />
<input type="hidden" name="txtPublishStartDate" value="<?php if(isset($get_audio_video_details['publish_start_date'])) { echo date('Y-m-d H:i', strtotime($get_audio_video_details['publish_start_date'])); } else { echo date('Y-m-d H:i'); } ?>" />



<div class="Overflow DropDownWrapper"  id="main_content" <?php if($page_name == 'edit_video' || $page_name == 'edit_audio') { ?> style="margin-top: 177px;" <?php } ?>>

<?php if(($page_name == 'edit_video' || $page_name == 'edit_audio') && ($section_segment  != 'edit_archive_video' && $section_segment  != 'edit_archive_audio')) { ?>
  <p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($get_audio_video_details['Createdby'])).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_audio_video_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($get_audio_video_details['Modifiedby'])).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_audio_video_details['Modifiedon'])); ?></p>
<?php } elseif($section_segment  == 'edit_archive_video' || $section_segment  == 'edit_archive_audio') { ?>
	<p><?php echo 'Created By : '.ucfirst(($get_audio_video_details['Createdby'])).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_audio_video_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(($get_audio_video_details['Modifiedby'])).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_audio_video_details['Modifiedon'])); ?></p>
<?php } ?>
  
 
<ul class="tabs margin-top-0 Article-Tab">
            <li class="selected" id="content_div"><a href="#view1">Content</a></li>
            <li class=""><a id="section_href" href="#view2">Multi Section Mapping</a></li>
        </ul>

 <section class="tap-main Article-Tab">
        
        <div class="tabcontents">
            <div id="view1" style="display: block;">
                
    <section class="form2">
    <table class="VideoTable">
    <col width="auto" />
    <col width="750px" />
    
   
   	 <tr>
      <td class="video_label"><label><?php echo $content_name; ?> Headline<span style="color:#F00">*</span></label></td>
      <td class="VideoCk GalleryNameCount  PositionRelative">
      <!--<div id="cke_charcount_audio_video_head_line_id" length="100" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_audio_video_details['title'])) echo mb_strlen($get_audio_video_details['title']); else echo "100"?></div> -->
      
      	<textarea class="ckeditor"  id="audio_video_head_line_id" name="txtAudioVideoHeadLine"><?php echo set_value('txtAudioVideoHeadLine'); ?><?php  if(isset($get_audio_video_details['title'])) echo $get_audio_video_details['title']; ?></textarea>

       <?php echo form_error('txtAudioVideoHeadLine'); ?>
       <p id="title_error" style="color:#F00"></p>
      </td>
     </tr>
	 
	   <tr>
		
      <td class="video_label"><label>URL Title</label></td>
	  
      <td><input type="text" class="box-shad" style="width:99.8% " id="txtUrlTitle" name="txtUrlTitle" maxlength="255" <?php if(isset($get_audio_video_details['url_title'])) { echo "readonly"; } ?>   value="<?php if(isset($get_audio_video_details['url_title']) && $get_audio_video_details['url_title'] != "") { echo $get_audio_video_details['url_title']; } echo set_value('txtUrlTitle'); ?>"  />
	   <?php echo form_error('txtUrlTitle'); ?>
	    <?php if(isset($get_audio_video_details['url'])) { ?>
			 <label>URL Structure : <a href="<?php  echo BASEURL.$get_audio_video_details['url']; ?>"><?php  echo BASEURL.$get_audio_video_details['url']; ?></a></label>
		<?php } ?>
      </td>
     </tr>
     
     
	 <tr>
	 	<td class="video_label"><label>Summary</label></td>
		<td class="VideoCk GalleryNameCount  PositionRelative">
		<div id="cke_charcount_summary" length="200" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_audio_video_details['summaryHTML'])) echo mb_strlen($get_audio_video_details['summaryHTML']); else echo "200"?></div>
		<textarea class="ckeditor" id="summary"  name="txtSummary"><?php if(isset($get_audio_video_details['summaryHTML'])) echo $get_audio_video_details['summaryHTML']; echo set_value('txtSummary'); ?></textarea>
 <?php echo form_error('txtSummary'); ?></td>
	</tr>
     
       <tr>
      <td class="video_label"><label>Tags</label></td>
      <td>
             
       <div class="FloatLeft">
       

         <section id="examples">
               	<div class="example example_markup">

    <div id="tagform-full"  class="bs-example">
		<?php if(isset($get_tags)) { 
				foreach($get_tags as $tags) { ?>
			<input type="text" name="txtTags[<?php echo $tags->tag_id; ?>-a]" id="tags_id"  value="<?php echo $tags->tag_name; ?>" class="tag"/>
       			<?php }  } else { ?>
            <input type="text" name="txtTags[]" id="tags_id"  value="" class="tag"/>
                <?php } ?>
    </div>
                </div>
		</section> 
        
         <div id="suggestion_div" class="TagSuggestion">
        </div>
</div>
     
      </td>
     </tr>
     
     
     <!-- <td><div class="fileUpload btn btn-primary">
    <span>Browse</span>
    <input type="file" style="width:92px;"  class="upload" id="btnResource" name="btnResource" value=""   existing_resource="" / />
	<span id="btnResource_error"></span>
	<input type="hidden" name="ExistingResource" id="ExistingResource" value="" />
	
   </div>
            <p style="color:red;" id="lblError"></p>  
      </td>
 -->
	<?php if($content_name == 'Video') { ?>
	 <tr>
		 <td  class="video_label"><label>Script<span style="color:#F00">*</span></label></td>
			 <td>
			<textarea class="box-shad VideoFramePreview valid" id="txtScript" name="txtScript" aria-invalid="false"><?php if(isset($get_audio_video_details['VideoScript']) && $get_audio_video_details['VideoScript'] != '') { echo $get_audio_video_details['VideoScript']; } ?></textarea>
			<input type="hidden" name="video_site" id="video_site" value="<?php if(isset($get_audio_video_details['VideoSite']) && $get_audio_video_details['VideoSite'] != '') { echo $get_audio_video_details['VideoSite']; } ?>" />
		<!--	<div id="check_popup">
            <a href="javascript:void(0);" id="popup_event" class="btn btn-primary" title="Preview"><i class="fa fa-desktop"></i></a>
            </div> -->
			<p class="WidthPercentage FloatLeft" id="script_error"></p>
		
     </tr>
	 
	<?php } else { ?>
	<tr>
		 <td  class="video_label"><label>Audio Source<span style="color:#F00">*</span></label></td>
			 <td>
	<div class="fileUpload btn btn-primary FloatLeft">
    <span>Browse</span>
    <input type="file" style="width:92px;"  class="upload" id="btnAudioSource" name="btnAudioSource" value="<?php if(isset($get_audio_video_details['Audio_path']) && isset($get_audio_video_details['Audio_path']) != '' ) { echo $get_audio_video_details['Audio_path'];  } ?>" / />
	
	
	<input type="hidden" name="ExistingAudioSource" id="ExistingAudioSource" value="<?php if(isset($get_audio_video_details['Audio_path']) && isset($get_audio_video_details['Audio_path']) != '' ) { echo $get_audio_video_details['Audio_path'];  } ?>" />
	
	
   </div>
  
	<?php if(isset($get_audio_video_details['Audio_path']) && isset($get_audio_video_details['Audio_path']) != '' ) { ?>
		<audio class="margin-left-10 margin-top-5" controls id="audio_player"  src="<?php echo image_url.audio_source_path.$get_audio_video_details['Audio_path']; ?>">
		Your browser does not support the audio element.
		</audio>
	<?php } ?>
	<?php if($page_name != 'edit_video' && $page_name != 'edit_audio') { ?>
	<br/><br/>
	<?php } ?>
	 <p id="SourceName" style="font-weight:bold;"><?php if(isset($get_audio_video_details['Audio_path']) && isset($get_audio_video_details['Audio_path']) != '' ) { $Array = explode("/",@$get_audio_video_details['Audio_path']);  echo @$Array[count($Array)-1];  
	 } ?></p>
	  <p style="color:red;" id="lblError"></p>  
	  
			 </td>
			 
	</tr>
	<?php } ?>
	
	<?php if(isset($get_audio_video_details['Audio_path']) && isset($get_audio_video_details['Audio_path']) != '' ) { ?>
		<tr>
		 <td  class="video_label"><label>Audio Source Path</label></td>
		 <td><?php echo image_url.audio_source_path.$get_audio_video_details['Audio_path']; ?>
		
		 </td>
		</tr>
	 <?php } ?>
 
     
    </table>
    
     <input type="hidden" id="audio_script" value="<?php if(isset($get_audio_video_details['Audio_path']) && isset($get_audio_video_details['Audio_path']) != '' ) { echo image_url.audio_source_path.$get_audio_video_details['Audio_path']; } ?>" />
    
    <table class="VideoTable02">
      
       <tr>
       
             <td class="MediumInputBox video_label"><label>Section<span style="color:#F00">*</span></label></td>
              <td class="ValignTop"><div class="FloatLeft"> 
                  <div class="w2ui-field">
                  <div >
                
  <select name="ddMainSection" class="controls" id="main_section_id" section_data="<?php echo $select_parent_name; ?>" Value="<?php echo set_value('ddMainSection'); ?><?php echo @$get_audio_video_details['Section_id']; ?>">
   <option id="MainSectionOption" value="">-Select-</option>
  
 <?php if(isset($section_mapping)) { 
 $section_bool = true;
				 foreach($section_mapping as $mapping) {  
				 
				 if(folder_name == 'niecpan') {
				 $condition_value =$content_name."s";
				 } else {
					 if(strtolower($content_name) == 'video') {
					 $condition_value = 'வீடியோக்கள்';
				 }  else {
					  $condition_value = 'ஆடியோக்கள்';
				 }
				 }
				 
				 if($mapping['Sectionname'] == $condition_value) {
					 
				 ?>

<option id="MainSectionOption" style="color:#933;font-size:18px;" class="blog_option" <?php if(set_value("ddMainSection") == $mapping['Section_id'] || (isset($get_audio_video_details['content_id']) && $get_audio_video_details['Section_id'] == $mapping['Section_id'] )) { echo  "selected";  $section_bool=false;  } ?> sectoin_data="<?php echo @$mapping['Sectionname']; ?>" rel="<?php echo @$mapping['LinkedToColumnist']; ?>"  value="<?php echo $mapping['Section_id']; ?>" url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim($mapping['BreadCrumb'])))); ?>" ><?php echo strip_tags($mapping['Sectionname']); ?></option>
  <?php if(!(empty($mapping['sub_section'])) ) { ?>
 
  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
  
    <option  id="MainSectionOption" <?php if(set_value("ddMainSection") == $sub_mapping['Section_id']  || (isset($get_audio_video_details['content_id']) && $get_audio_video_details['Section_id'] == $sub_mapping['Section_id'] )) { echo  "selected";  $section_bool=false;  } ?>  sectoin_data="<?php echo @$mapping['Sectionname']; ?>"  rel="<?php echo @$sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>" url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_mapping['BreadCrumb'])))); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
		
		 <?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
		 
		   <?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
    <option id="MainSectionOption" <?php if($sub_sub_mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?>  <?php if(set_value("ddMainSection") == $sub_sub_mapping['Section_id']  || (isset($get_audio_video_details['content_id']) && $get_audio_video_details['Section_id'] == $sub_sub_mapping['Section_id'] ))  { echo  "selected";  $section_bool=false;  } ?>  rel="<?php echo @$sub_sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_sub_mapping['Section_id']; ?>"  sectoin_data="<?php echo @$mapping['Sectionname']; ?>" url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_sub_mapping['BreadCrumb'])))); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
		 
		<?php } } ?>
  <?php  } } ?>
				 <?php } }

				  if($section_bool == true && isset($get_audio_video_details['Section_id']) && $get_audio_video_details['Section_id'] != 0&& isset($select_parent_name)) { 
					
						 ?>
					 <option id="MainSectionOption" section_data="<?php echo $select_parent_name; ?>"  value="<?php echo $get_audio_video_details['Section_id']; ?>"url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim(GenerateBreadCrumbBySectionId($get_audio_video_details['Section_id']))))); ?>"  selected> <?php echo $select_section_name; ?> </option>				 <?php }
					 

				 } ?>

</select>
        
                    </div>
                    </div>
                    </div>
                
        <?php echo form_error('ddMainSection'); ?> 
		  
  <p id="mainsection_breadcrumb"> </p>
             </td>
			 
       </tr>



    <tr>
     <td class="MediumInputBox video_label"><label>Agency<!--<span style="color:#F00">*</span>--></label></td>
                          <td class="ValignTop">
                          <div class="FloatLeft"> 
                              <div class="w2ui-field">
                              <div >
	  <select name="ddAgency"  class="controls" id="agency_id"  >
   <option value="">-Select-</option>
    <?php if(isset($get_agency)) { 
	 $boolagency = true;
					  foreach($get_agency as $agency) {?>
                      <option value="<?php echo $agency->Agency_id; ?>" <?php if(set_value("ddAgency") == $agency->Agency_id || (isset($get_audio_video_details['Agency_ID']) && $get_audio_video_details['Agency_ID'] == $agency->Agency_id)) { echo  "selected='selected'"; $boolagency = false;  } if($page_name != 'edit_audio' && $page_name != 'edit_video' && $agency->Agency_name == 'New Indian Express' ||  $agency->Agency_name == 'DIN') { echo "selected='selected'"; $boolagency = false;   } ?> ><?php echo ucfirst($agency->Agency_name); ?></option>
                      <?php } } 
								if($boolagency == true &&  isset($get_audio_video_details['Agency_ID']) && $get_audio_video_details['Agency_ID'] != 0 ) { ?>
									  <option value="<?php echo $get_audio_video_details['Agency_ID']; ?>" selected ><?php echo @$select_agency; ?></option>
								  <?php } ?>   
   </select>
  
			 </div>
                            </div>
                            </div>
                            
             <?php echo form_error('ddAgency'); ?> 
                       </td>
                       
                 
                       <td id="byline_row" class="video_label video_label22">
         <label>Byline</label>
         </td>
         
         <td class="ValignTop">
         
        <input type="text" name="txtByLine" id="txtByLine" value="<?php if(isset($select_author) && $select_author != "") {  echo $select_author; } echo set_value('txtbyline'); ?>"  />
         <input type="hidden" id="byline_id" name="ddByLine" value="<?php if(isset($get_audio_video_details['Author_ID']) && $get_audio_video_details['Author_ID'] != "") {  echo $get_audio_video_details['Author_ID']; } echo set_value('hidden_byine_id'); ?>" />
	
		<?php echo form_error('ddByLine'); ?>
         </td>
       </tr>


<tr>
         <td class="video_label">
         <label>Country<!--<span style="color:#F00">*</span>--></label>
         </td>
         <td class="ValignTop">
         <div class="FloatLeft">
<div class="w2ui-field" id="SelectCountry">
<div>
<select name="ddCountry"  class="controls" id="country_id" value="<?php echo set_value('ddCountry'); ?>">
    <option value="">-Select-</option>
                     <?php if(isset($get_country)) { 		
					  foreach($get_country as $country) {?>
                     <option  <?php if(set_value("ddCountry") == $country->Country_id || (isset($get_audio_video_details['Country_ID']) && $get_audio_video_details['Country_ID'] == $country->Country_id )) echo  "selected"; ?> value="<?php echo $country->Country_id; ?>"><?php echo ucfirst($country->CountryName); ?></option>
                      <?php } } ?>                     
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
<div class="FloatLeft" id="SelectAuthor">
  
   <input type="text" name="txtState" id="txtState" value="<?php if(isset($select_state) && $select_state != "") {  echo $select_state; } echo set_value('txtState'); ?>"  />
         <input type="hidden" id="state_id" name="ddState" value="<?php if(isset($get_audio_video_details['State_ID']) && $get_audio_video_details['State_ID'] != "") {  echo $get_audio_video_details['State_ID']; } echo set_value('ddState'); ?>" />
	
    <?php echo form_error('ddState'); ?>
   
         
</div>
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
         <input type="hidden" id="city_id" name="ddCity" value="<?php if(isset($get_audio_video_details['City_ID']) && $get_audio_video_details['City_ID'] != "") {  echo $get_audio_video_details['City_ID']; } echo set_value('ddCity'); ?>" />

    <?php echo form_error('ddCity'); ?>

</div>

</div>
</div>
</td>
       </tr>              

  <tr>
    <td></td>
    <td>   <input type="checkbox"  name="cbAllowComments" id="cbAllowComments"  <?php if(set_value("cbAllowComments") == 'on' || $page_name != 'edit_video' || $page_name != 'edit_audio'  ||  (isset($get_audio_video_details['Allowcomments']) && $get_audio_video_details['Allowcomments'] == 1 ) ) echo  "checked"; ?> />
            <label for="cbAllowComments" class="margin-bottom-0">Allow Comments</label></td>
  </tr>
     
       
    </table>
    
   <table class="VideoTable03 VideoImage" style="position:relative">   
     <tr>
      <td class="video_label">
	  <label>Display Image<span style="color:#F00">*</span></label></td>
      <td> 
		 <div class="article_image BorderBoxNone margin-top-0">
			  
			   <a class="set_image margin-left-0 <?php if((isset($get_audio_video_details['image_details']['image_id']) && $get_audio_video_details['image_details']['image_id'] != '') ) echo " "; else  echo  " BorderRadius3"; ?>" id="audio_video_image_set"  href="#" data-remodal-target="modal1" ><?php if((isset($get_audio_video_details['image_details']['image_id']) && $get_audio_video_details['image_details']['image_id'] != '') ) echo "Change Image"; else  echo "Set Image"; ?></a>
            
			 <a id="edit_audio_video_image" rel="resource" class="del_image delbtn_border" href="javascript:void(0);" <?php if((isset($get_audio_video_details['image_details']['image_id']) && $get_audio_video_details['image_details']['image_id'] != '') ) { ?>  <?php } else {  ?> style="display:none;"  <?php  } ?>><i class="fa fa-pencil"></i></a>
			
           <a id="delete_audio_video_image" rel="resource" class="del_image" href="javascript:void(0);" <?php if((isset($get_audio_video_details['image_details']['image_id']) && $get_audio_video_details['image_details']['image_id'] != '') ) { ?>  <?php } else {  ?>   style="display:none;" <?php  } ?> ><i class="fa fa-trash-o"></i></a> 
		    <br/>
              <input type="hidden" class="image-group" name="imgAudioVideoId" rel="<?php echo @$get_audio_video_details['image_details']['imagecontent_id']; ?>" value="<?php if(isset($get_audio_video_details['image_details']['image_id'])) echo $get_audio_video_details['image_details']['image_id']; echo set_value('imgHomeImageId'); ?>" id="audio_video_image_gallery_id">
              
       	 	</div>
  
<div class="ArticleImageContainer1" id="audio_video_image_container" style="float:none; width:200px;<?php if(!isset($get_audio_video_details['image_details']['image_id'])) { echo "display:none;"; } else { echo "display:block;"; } ?>">
      
            <img id="audio_video_image_src" src="<?php if(isset($get_audio_video_details['image_details']['image_id']) && $get_audio_video_details['image_details']['image_id'] != '') { echo $get_audio_video_details['image_details']['image'];  } ?>"/>
			<label>Caption :</label>
			<input type="text" id="audio_video_image_caption" name="audio_video_image_caption"  value="<?php echo htmlentities(@$get_audio_video_details['image_details']['caption']);   ?>" class="margin-top-5 WidthFull valid" aria-invalid="false">
			<label>Image Alt :</label>
			<input type="text" id="audio_video_image_alt" name="audio_video_image_alt"  value="<?php echo htmlentities(@$get_audio_video_details['image_details']['alt_tag']);   ?>" class="margin-top-5 WidthFull valid" aria-invalid="false">
			<label>Image Name :</label>
			<input type="text" id="audio_video_physical_name" name="audio_video_physical_name" maxlength=80 physical_extension="<?php echo @$get_audio_video_details['image_details']['physical_extension'];   ?>" value="<?php echo @$get_audio_video_details['image_details']['physical_name'];   ?>" class="margin-top-5 WidthFull valid" aria-invalid="false" <?php  if(isset($get_audio_video_details['image_details']['image_id'])) { ?> readonly <?php } ?>>
          </div>
	
    <?php echo form_error('btnImage'); ?>
  
    </td>
  
   </table>
   
    <div class="Overflow FloatLeft MetaTitleWrapper">
   
   <table class="FloatLeft metatitle1">
     
     <tr>
      <td class="video_label"><label>Meta Title<span style="color:#F00">*</span></label></td>
      <td> <div class="FloatLeft">
            <input id="count1" maxlength="100" name="txtMetaTitle" type="text" value="<?php if(isset($get_audio_video_details['MetaTitle'])) echo $get_audio_video_details['MetaTitle']; echo set_value('txtMetaTitle'); ?>" class="box-shad1"/>
              <?php echo form_error('txtMetaTitle'); ?>
            </div>
            <div id="charNum1" class="charNum"><?php if(isset($get_audio_video_details['MetaTitle'])) echo 100 - mb_strlen($get_audio_video_details['MetaTitle']); else echo "100"?></div>
        </td>
      </td>
     </tr>
     
     <tr>
      <td class="video_label"><label>Meta Description</label></td>
      <td> <textarea id="count2" maxlength="200"  name="txtMetaDescription"  class="article_textarea box-shad1"  ><?php if(isset($get_audio_video_details['MetaDescription'])) echo $get_audio_video_details['MetaDescription']; echo set_value('txtMetaDescription'); ?></textarea>
             <?php echo form_error('txtMetaDescription'); ?>
    <div id="charNum2" class="charNum"><?php if(isset($get_audio_video_details['MetaDescription'])) echo 200 - mb_strlen($get_audio_video_details['MetaDescription']); else echo "200"?></div>
      </td>
      </td>
     </tr>
     
     <tr>
      <td class="video_label"><label>Canonical URL</label></td>
      <td><input type="text" class="box-shad"  maxlength="252" name="txtCanonicalUrl" id="txtCanonicalUrl" value="<?php if(isset($get_audio_video_details['Canonicalurl'])) echo $get_audio_video_details['Canonicalurl'];  echo set_value('txtCanonicalUrl');?>"  />
       <?php echo form_error('txtCanonicalUrl'); ?>
      </td>
     </tr>
   </table>
   

   <div class="MetaDescription FloatLeft">
   
    <h3>Social Meta Tag</h3>
   <ul>

    <li>
    <input type="checkbox" id="cbNoIndex" name="cbNoIndex" <?php if((isset($get_audio_video_details['Noindexed']) && $get_audio_video_details['Noindexed'] == 1)  ) { ?>  checked="checked" <?php } ?>>
<label class="include_label" for="cbNoIndex">No index</label>
    </li>
    
   
    <li>
    <input type="checkbox" id="cbNoFollows" name="cbNoFollows" <?php if((isset($get_audio_video_details['Nofollow']) && $get_audio_video_details['Nofollow'] == 1 ) ) { ?>  checked="checked" <?php } ?> >
<label class="include_label" for="cbNoFollows">No follows</label>
    </li>
    
   
   </ul>
   </div>
  </div>
  </section>
   
   </div>
   
    <div id="view2" style="display: none;">
          
      <div class="mapping_section">
      <div class="tree_check"><input type="checkbox" id="show_list" /><label for="show_list">Show the mapped section</label></div>
         <div class="mapping_contents">
         <h1>Section and Sub-Section Mapping</h1>
      <div class="tree_head" id="mapping_1"></div>
      
      <div id="mapping_2" class="tree_head_dup">
      
      </div>
      </div>
            </div>

            </div> 
           
        </div>
		   <div class="remodal" data-remodal-id="modal2"  data-remodal-options="hashTracking:false"  style="position:relative;">
		      <div id="play_video_div">
              </div>
		   </div>
		 <div class="remodal" data-remodal-id="modal1" data-remodal-options="hashTracking:false" style="position:relative;">
            <div class="article_popup GalleryPopup ArticlePopup" style="height: 467px;">
            <div class="article_popup1">
            <ul class="article_popup_tabs">
            <li onclick="articleUpload()" class="active img_upload">From Local System</li>
            <li onclick="articleBrowse()" class="img_browse">From Library</li>
            </ul>
            </div>	
            <div class="article_popup2">
            <div class="article_upload">
       
          <form  name="ImageForm" id="ImageForm" action="<?php echo base_url().folder_name; ?>/image/multiple_image_upload" method="POST" enctype="multipart/form-data">
              <div class="popup_addfiles">
                <div class="fileUpload btn btn-primary WidthAuto">
                    <span>+ Select Image</span>
                    <input type="file" id="imagelibrary" name="imagelibrary" accept="image/*" class="upload" style="width:100%;">
            </div>
            
             <div id="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
			Please wait while image is being uploaded
            </div>
            
        </form>
          </div>
            <div class="GalleryDrag"  id="drop-area">
Drop files anywhere here to upload or click on the "Select Image" button above</div>
            </div>
            
                <div class="article_browse">
            <h3>Pick the item to insert</h3>
            <div class="article_browse1">
            <div class="article_browse_drop">
            <div class="w2ui-field FloatLeft">

</div>
<input type="text" placeholder="Search" id="search_caption" name="txtBrowserSearch"  class="box-shad1 FloatLeft BrowseInput" />
<i id="image_search_id" class="fa fa-search FloatLeft BrowseSearch"></i>
<a  class="btn btn-primary margin-left-10" id="clear_search" href="javascript:void(0);" style="display:none;">Clear Search</a>
            </div>
            <div class="popup_images transitions-enabled infinite-scroll clearfix"  id="image_lists_id">
            </div>
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
		 ++$count; } } ?>
			<nav id="page-nav">
			  <a href="<?php echo base_url().folder_name; ?>/article_image/search_image_library_scroll/2"></a>
			</nav>
            </div>
            <div class="article_browse2">
            <?php if(isset($image_library)) {  ?>
            <h4>Image Details</h4>
            <?php if(isset($first_image_pathname)) { ?>
            <img id="image_path" src="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" />
            <h4 id="image_name"><?php echo $first_image_caption; ?></h4>
            <p>Date:<span id="image_date"><?php echo $first_image_date; ?></span></p>
            <input type="hidden" value="<?php echo $first_image_id; ?>" data-content_id="<?php echo $first_image_id; ?>" data-image_alt="<?php echo $first_image_alt; ?>" data-image_caption="<?php echo $first_image_caption; ?>"  data-image_date="<?php echo $first_image_date; ?>" data-image_source="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" data-image_path="<?php echo  image_url.imagelibrary_image_path.$first_image_pathname; ?>" id="browse_image_id" name="browse_image_id" />
            <div class="article_browse2_input">
            <label><strong>Image Alt</strong></label>
            <h4 id="textarea_alt"><?php echo $first_image_alt; ?></h4>
			<br/>
            <label><strong>Caption</strong></label>
            <h4  id="textarea_caption"><?php echo $first_image_caption; ?></h4>
              <?php } ?>
            
            </div>
    
            </div>
            <?php if(isset($first_image_pathname)) { ?>
            <div class="FloatRight popup_insert insert-fixed">
       <button type="button" class="padding-10 btn-primary remodal-confirm"id="browse_image_insert"  >Insert</button>
            </div>
			 <?php } } ?>
            </div>
			
            </div>
            </div>
            </div>
		

</section>
  </div>
  
  
  
</form>

<div class="remodal" id="preview_article_popup" data-remodal-options="hashTracking:false" data-remodal-id="preview_article_popup" style="position:relative;">
 <div id="preview_article_popup_loading">
 </div>
 <div id="preview_article_popup_container"  class="container" style="display:none;">
 </div>
 </div>


</div>
                     
</div>    
		

<script src="<?php echo image_url; ?>js/tabcontent.js" type="text/javascript"></script>
<script src="<?php echo image_url; ?>js/jquery.form.js" type="text/javascript"></script>
<script src="<?php echo image_url; ?>js/jquery-1.10.2-autocomplete.js" type="text/javascript" ></script>
<script src="<?php echo image_url; ?>js/jquery-ui-autocomplete.js"></script>
<script src="<?php echo image_url; ?>includes/ckeditor/ckeditor.js"  type="text/javascript" ></script>
<script src="<?php echo image_url; ?>js/bootstrap/bootstrap.min.js"  type="text/javascript" ></script>
<script src="<?php echo image_url;?>js/jquery.validate.min.js"  type="text/javascript" ></script>
<script src="<?php echo image_url;?>js/additional-methods.min.js"  type="text/javascript" ></script>	
<script src="<?php echo image_url;?>js/audio_video.js"  type="text/javascript" ></script>
<script src="<?php echo image_url; ?>js/jquery.remodal.js"></script>
                 
<script type="text/javascript">

$(document).ready(function() {   
  
<?php if($this->session->flashdata('msg')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 20000);
});
<?php } ?>

  
});


</script>

<script>
 $(document).ready(function(){
	 
	 
	 $("#preview_id").click(function() {
				
			$("#audio_player").trigger('pause');
			
			$("#preview_article_popup_container").hide();
			$("#preview_article_popup_loading").hide();
			
				publishdate =  '';
				last_update = '';
				var script = '';
			<?php if(isset($get_audio_video_details['publish_start_date'])) { ?>
				publishdate = "<?php echo date('dS  F Y h:i A',strtotime($get_audio_video_details['publish_start_date'])); ?>";
			<?php } ?>

				
			/*<?php if(isset($get_audio_video_details['Modifiedon'])) { ?>
				last_update = "<?php echo date('dS  F Y h:i A',strtotime($get_audio_video_details['Modifiedon'])); ?>";
			<?php } ?>*/
		
			$("#preview_article_popup_loading").html('<img style="width:40px; height:40px;" src="'+base_url+'images/admin/loadingroundimage.gif">')
			$('.remodal-close').hide();
			$("#preview_article_popup_loading").show();
			
			
			var inst = $.remodal.lookup[$('[data-remodal-id=preview_article_popup]').data('remodal')];
				if(!inst) {
					$('[data-remodal-id=preview_article_popup]').remodal().open();
				 } else{
                      inst.open();
				} 
				
					var tags = [];
				
				$(".tagedit-listelement").children("input:hidden").each(function(){
					tags.push($(this).val());
				});
				
				var content_type 			= $("#content_type").val();

			head_line = encodeURIComponent(CKEDITOR.instances.audio_video_head_line_id.getData());
			body_text = encodeURIComponent(CKEDITOR.instances.summary.getData());
			
			var tags 					= JSON.stringify(tags);
			
			if(content_type == 4) {
				if($.trim($("#video_site").val()) == 'ventunovideo'){
					script 				= 	 encodeURIComponent('<object width="630" height="441" id="ventuno_player_0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param name="movie" value="http://cfplayer.ventunotech.com/player/vtn_player_2.swf?vID=='+encodeURIComponent($("#txtScript").val())+'"/>  <param name="allowscriptaccess" value="always"/>     <param name="allowFullScreen" value="true"/>     <param name="wmode" value="transparent"/>     <embed src="http://cfplayer.ventunotech.com/player/vtn_player_2.swf?vID=='+encodeURIComponent($("#txtScript").val())+'" width="630" height="441" wmode="transparent" allownetworking="all" allowscriptaccess="always" allowfullscreen="true"></embed></object>');
				} else {
					script 				= encodeURIComponent($("#txtScript").val());
				}
				
			} else {
				script 				= encodeURIComponent($("#audio_script").val());
			}
		
			var agency_id 				= $("#agency_id").val();
			var section_id 				= encodeURIComponent($.trim($("#main_section_id").val()));
	
			var postdata = { "head_line" : head_line,"body_text" :body_text,"tags" : tags,"publishdate" :publishdate,"last_update" : last_update,"section_id" : section_id,"agency_id" :agency_id,"content_type": content_type,"script": script};
			$.ajax({
			url: base_url+folder_name+"/audio_video_manager/get_audio_video_preview_popup", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			dataType: "HTML",
			async: false, 
			success: function(data)   // A function to be called if request succeeds
			{
				
		
				
		
		
		setTimeout(function(){
			
		//$('link[rel=stylesheet][href~="'+base_url+'css/admin/dashboard-style.css"]').remove();
		$('link[rel=stylesheet][href~="'+base_url+'includes/ckeditor/contents.css"]').remove();

		$(".previewcontainer").append($('<link rel="stylesheet" href="'+base_url+'css/FrontEnd/css/style.css" type="text/css">')); 
		
		//$(".previewcontainer").append($("<script type='text/javascript' src='"+base_url+"js/FrontEnd/js/remodal-article.js'>"));
		//$(".previewcontainer").append($('<link rel="stylesheet" href="'+base_url+'css/FrontEnd/css/easy-responsive-tabs.css" type="text/css">'));  
		//$(".previewcontainer").append($("<script type='text/javascript' src='"+base_url+"js/FrontEnd/js/jquery-ui.js'>")); 
		//$(".previewcontainer").append($("<script type='text/javascript' src='"+base_url+"js/FrontEnd/js/easyResponsiveTabs.js'>"));  
	},1000);
	
	setTimeout(function(){
		$('.remodal-close').show();
	$("#preview_article_popup_container").html(data);
	$("#preview_article_popup_loading").hide();
	$("#preview_article_popup_container").show();
		},1000);
		
		
			}
		});
				
		
	}); 
	
		
$(document).on('close', '#preview_article_popup', function () {  

		$(".css_and_js_files").append($('<link rel="stylesheet" href="'+base_url+'includes/ckeditor/contents.css" type="text/css"  id="contents_css">'));  
				
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/style.css"]').remove();

}); 
	
	 
	$(".bootstrap-tagsinput input").css("width", "400px");
	$(".cke_1_contents").css("height", "119px"); 
	
	
	<?php if(isset($print_status)) { ?>
//	display_status("<?php echo $print_status; ?>","<?php echo $publishdate; ?>");
	
	$MetaTitle = $.trim($("#txtMetaTitle").val());
	
	if($MetaTitle != '') {
		
	var postdata = "metatitle="+$MetaTitle;
			$.ajax({
				url: base_url+folder_name+"/common/get_tags_by_meta_title", // Url to which the request is send
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
  
<link rel="StyleSheet" href="<?php  echo image_url; ?>includes/Tagedit-master/css/jquery.tagedit.css" type="text/css" media="all"/>    
<script type="text/javascript" src="<?php  echo image_url; ?>includes/Tagedit-master/js/jquery-1.10.2.min.js"></script>
<script src="<?php echo  image_url; ?>js/jquery_ui.js"></script>
<script type="text/javascript" src="<?php echo  image_url; ?>includes/Tagedit-master/js/jquery.autoGrowInput.js"></script>
<script type="text/javascript" src="<?php  echo image_url; ?>includes/Tagedit-master/js/jquery.tagedit.js"></script>
    
    <script type="text/javascript">

	var jqs = $.noConflict();
	jqs(function() {
		jqs( "#tagform-full" ).find('input.tag').tagedit({
			autocompleteURL: "<?php echo base_url().folder_name; ?>/common/tags",
			allowEdit: false, // Switch on/off edit entries
 			allowDelete: false // Switch on/off deletion of entries. Will be ignored if allowEdit = false
		});
		
		/*
		jqs("#agency_id").change(function() {
			jqs("#txtByLine").val('');
							jqs("#byline_id").val('');
		}); */

		
		jqs("#txtByLine").keyup(function() {
		
		jqs("#byline_id").val('');
// IE & DM Differents		
		var main_section_text = $.trim($( "#main_section_id option:selected" ).text());
		
		if( main_section_text  == 'Columns' ||  main_section_text  == 'Voices')
			var author_type = 2;
		else 
			var author_type = 1; 
		
		console.log(author_type);
// IE & DM Differents
		
		if(author_type != '' || parseInt(author_type) == 0) {
					var availableTags = base_url+folder_name+"/common/get_author_name/"+author_type;
					
					console.log(availableTags);
					
					jqs("#txtByLine").autocomplete({
						source: function(request, response){
							jqs.ajax({
								 url: availableTags,
								 data: "term="+jqs("#txtByLine").val(),
								 dataType: "json",
								 success:  function (msg) {
									console.log(msg);
									 if(msg != '')
									response(msg);
									else 
									jqs("#byline_id").val('');
								 }
								
							});
						},
						select: function( event, ui ) {
							jqs( "#txtByLine" ).val( ui.item.label );
							jqs("#byline_id").val(ui.item.text);
							return false;
						},
					});
	 	}
		
	});
	
		jqs("#txtState").keyup(function()
	{
		
		jqs("#txtCity").val('');
		jqs("#city_id").val('');
		
		if(jqs("#country_id").val() != '') 
		{
					var country_id = jqs("#country_id").val();
					var availableTags = base_url+folder_name+"/common/get_state_name/"+country_id;
					
					console.log(availableTags);
					
					jqs("#txtState").autocomplete({
						source: function(request, response){
							jqs.ajax({
								 url: availableTags,
								 data: "term="+jqs("#txtState").val(),
								 dataType: "json",
								 success:  function (msg) {
									
									 if(msg != '')
									response(msg);
									else 
									jqs("#state_id").val('');
								 }
								
							});
						},
						select: function( event, ui ) {
							jqs( "#txtState" ).val( ui.item.label );
							jqs("#state_id").val(ui.item.text);
							return false;
						},
					});
	 	}
		
	});
	
	jqs("#txtCity").keyup(function()
	{
		if(jqs("#country_id").val() != '' && jqs("#state_id").val() != '') 
		{
					var country_id = jqs("#country_id").val();
					var state_id = jqs("#state_id").val();
					var availableTags = base_url+folder_name+"/common/get_city_name/"+country_id+"/"+state_id;
					
					console.log(availableTags);
					
					jqs("#txtCity").autocomplete({
						source: function(request, response){
							jqs.ajax({
								 url: availableTags,
								 data: "term="+jqs("#txtCity").val(),
								 dataType: "json",
								 success:  function (msg) {
									
									 if(msg != '')
									response(msg);
									else 
									jqs("#city_id").val('');
								 }
								
							});
						},
						select: function( event, ui ) {
							jqs( "#txtCity" ).val( ui.item.label );
							jqs("#city_id").val(ui.item.text);
							return false;
						},
					});
	 	}
		
	});
	
});
	
	</script>
	
<!-- Mansory & Infinite Scroll Script -->
<script src="<?php echo image_url; ?>js/jquery-1.7.1.min.js"></script>
<script src="<?php echo image_url; ?>js/jquery.masonry.min.js"></script>
<script src="<?php echo image_url; ?>js/jquery.infinitescroll.min.js"></script>
<script>
var jqis = $.noConflict();
call_infinite_scroll();
function call_mansory() {
	
	var $container = jqis('.popup_images');
	
		$container.imagesLoaded(function(){
				  $container.masonry({
					itemSelector: '#image_lists_images_id',
					columnWidth: 1
				  });
				}); 
				
}

function call_infinite_scroll() {
	
	 var $container = jqis('.popup_images');
		
	 $container.infinitescroll({
      navSelector  : '#page-nav',    // selector for the paged navigation 
      nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      itemSelector : '#image_lists_images_id',
	   binder :  $container ,
	  debug : true,
		  // selector for all items you'll retrieve
      loading: {
		  
          finishedMsg: 'No more images to load.',
          img: '<?php echo image_url ?>images/admin/loadingimage.gif',
		  msgText: "<em>Loading the next set of images...</em>"
        },
		state: { isDone:false }
      },
      // trigger Masonry as a callback
      function( newElements ) {
        // hide new items while they are loading
        var $newElems = jqis( newElements ).css({ opacity: 0 });
        // ensure that images load before adding to masonry layout
        $newElems.imagesLoaded(function(){
          // show elems now they're ready
          $newElems.animate({ opacity: 1 });
		  console.log("container add");
			$container.masonry( 'appended', $newElems, true );	
        });
      }
    );
    
}

jqis(".set_image, .EmbedImage").click(function(){
		
 var $container = jqis('.popup_images');
	
	if(jqis.trim($container.html()) == '') {
		
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
	
			$.ajax({
			url: base_url+folder_name+"/article_image/get_image_library_scroll/1", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			dataType: "HTML",
			success: function(data)   // A function to be called if request succeeds
			{
				
				$container.html(data);
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
var show_clear_link =  jqis("#search_caption").val();
if(show_clear_link!=''){
jqis("#clear_search").show();
}
	jqis("#clear_search").click(function() {
		jqis("#search_caption").val('');
 var $container = jqis('.popup_images');
		 $container.empty();
	if(jqis.trim($container.html()) == '') {
		
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
	
			$.ajax({
			url: base_url+folder_name+"/article_image/get_image_library_scroll/1", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			dataType: "HTML",
			success: function(data)   // A function to be called if request succeeds
			{
				
				$container.html(data);
				jqis("#clear_search").hide();
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

	/*function ImageExist(url) 
	{
	   var img = new Image();
	   img.src = url;
	   return img.height != 0;
	} */
	
	function ImageExist(image_url){

		var http = new XMLHttpRequest();

		http.open('HEAD', image_url, false);
		http.send();

		return http.status != 404;

	}
	
	
	function Image_Search() {
		
		 var $container = jqis('.popup_images');
		 $container.empty();
	if(jqis.trim($container.html()) == '') {
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url; ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		}	
			var Caption = jqis("#search_caption").val();
			
			postdata = "Caption="+Caption;
			jqis.ajax({
				url: base_url+folder_name+"/article_image/search_image_library",
				type: "POST",
				data: postdata,
				dataType: "json",
				success: function(data){
					var Content = '';
					var Count 	= 0;
					var Image_URL = "<?php echo image_url.imagelibrary_image_path
					;?>";
					
					jqis.each(data, function(i, item) {
						
						if(ImageExist(Image_URL+item.ImagePhysicalPath)) {
						
						var active_class = "";
						if(Count == 0) {
								
							jqis("#textarea_alt").text(item.ImageAlt);
							jqis("#textarea_caption").text(item.ImageCaption);
							jqis("#image_name").html(item.ImageCaption);
							jqis("#image_date").html(item.Modifiedon);
							jqis("#image_path").attr('src',Image_URL+item.ImagePhysicalPath);
							jqis("#browse_image_id").val(item.content_id);
							
							jqis("#browse_image_id").val(jqis(this).attr('rel'));
							
							jqis("#browse_image_id").data("image_source",Image_URL+item.ImagePhysicalPath);
							jqis("#browse_image_id").data("content_id",item.content_id);
							jqis("#browse_image_id").data("image_alt",item.ImageAlt);
							jqis("#browse_image_id").data("image_caption",item.ImageCaption);
							jqis("#browse_image_id").data("image_date",item.Modifiedon);
							jqis("#browse_image_id").data("image_path",Image_URL+item.ImagePhysicalPath);
							
							active_class = 'active';		
						}
						
						
						
					Content +='<img id="image_lists_images_id" data-content_id="'+item.content_id+'"  data-image_caption="'+item.ImageCaption+'" data-image_alt="'+item.ImageAlt+'"  data-image_date="'+item.Modifiedon+'" data-image_source="'+Image_URL+item.ImagePhysicalPath+'"  src="'+Image_URL+item.ImagePhysicalPath+'" />';
						Count++; 
						}
					});
					if(Content != "") {
					jqis('.popup_images').html(Content);
					} else {
					jqis("#image_lists_id").html("No Data");
					}
					
					jqis('.popup_images').masonry('reload');
					jqis('.popup_images').infinitescroll('destroy'); // Destroy
					
					// Undestroy
					jqis('.popup_images').infinitescroll({ 				
						state: {                                              
								isDestroyed: false,
								isDone: false                           
						}
					});
					console.log("destory");	
					jqis('.popup_images').infinitescroll('bind');
					jqis('.popup_images').infinitescroll('retrieve');
			        jqis("#clear_search").show(); 
				}
			});
		
		
	}
	
	
	jqis("#image_search_id").click(function() {
		Image_Search();
	});
	
	jqis("#search_caption").keyup(function(e){
	    if(e.keyCode == 13){
			Image_Search();
		  }
	});

</script>
<!-- Mansory & Infinite Scroll Script -->