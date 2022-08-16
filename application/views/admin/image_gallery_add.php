<?php $section_segment = $page_name = $this->uri->segment('2'); 

if($page_name == "edit_archive_gallery")
	$page_name ="edit_gallery";

?>
	<span class="css_and_js_files">
	<link href="<?php echo image_url; ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
    <link href="<?php echo image_url; ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo image_url; ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" id="contents_css" />
    <link href="<?php echo image_url; ?>css/tag/bootstrap.css" rel="stylesheet" type="text/css" />	
	<link rel="stylesheet"  href="<?php echo image_url; ?>css/admin/jquery-ui-autocomplete.css" type="text/css" /> 
	<link rel="stylesheet" href="<?php echo image_url; ?>css/admin/jquery-ui-custom.css">
	<link href="<?php echo image_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
	</span>
	<span class="previewcontainer">

</span>

    <style>
    .mandatory {
		color:red;
	}
	.error {
		color:red;
	}
	</style> 

 <?php

 if(isset($get_gallery_details['status'])) {
	$status 		= $get_gallery_details['status'];
	$get_content_id = $get_gallery_details['content_id'];
	
	$publishdate  = "";
	$unpublishdate  = "";
	
	switch($status )
	{
		
		case "P":
			$print_status = "Published";
			$publishdate  = date('d-m-Y H:i:s',strtotime($get_gallery_details['Modifiedon']));
			break;
		case "U":
			$print_status = "Unpublished";
			$unpublishdate  = date('d-m-Y H:i:s',strtotime($get_gallery_details['Modifiedon']));
			break;
		case "D":
			$print_status = "Draft";
			break;
		default:
			$print_status = "None";
	}
	
}

 ?>
 
 <?php if($section_segment  == 'edit_archive_gallery') { ?>
 
 <form action="<?php echo base_url(); ?><?php echo folder_name; ?>/gallery/update_archive_gallery/<?php echo @$archive_year; ?>/<?php echo @$get_gallery_details['content_id']; ?>" method="post" id="imagegallery" name="imagegallery" enctype="multipart/form-data">

<?php } else { 
 
if($page_name != 'edit_gallery') { ?>
<form action="<?php echo base_url();?><?php echo folder_name; ?>/gallery/add_new_gallery" method="post" name="imagegallery" id="imagegallery" enctype="multipart/form-data"  >
<?php } else { ?>
<form action="<?php echo base_url();?><?php echo folder_name; ?>/gallery/update_gallery/<?php echo $get_gallery_details['content_id']; ?>" method="post" name="imagegallery" id="imagegallery" enctype="multipart/form-data"  >
<?php } } ?>



<input type="hidden" name="txtStatus" id="status_id" value="P" />
<input type="hidden" name="txtGalleryData" id="gallery_data" value="" />
<input type="hidden" name="txtContentId" id="content_id" value="<?php if(isset($get_gallery_details['content_id'])) echo $get_gallery_details['content_id']; ?>" />
<input type="hidden" name="archive_year" id="archive_year" value="<?php echo @$archive_year; ?>" />
<input type="hidden" name="txtOldStatus" id="old_status_id" value="<?php if(isset($status)) echo $status; ?>" />
<input type="hidden" name="txtPublishStartDate" value="<?php if(isset($get_gallery_details['publish_start_date'])) { echo date('Y-m-d H:i', strtotime($get_gallery_details['publish_start_date'])); } else { echo date('Y-m-d H:i'); } ?>" />

<div class="Container" style="width:1170px;">
      <div class="BodyWhiteBG">
    <div class="BodyHeadBg Overflow clear">
	<div class="FloatLeft BreadCrumbsWrapper">
        <div class="breadcrumbs"><a href="javascript:void(0);">Dashboard</a> > <a href="javascript:void(0);"><?php if($page_name == 'edit_gallery') echo "Edit Gallery"; else echo "Create Gallery"; ?></a></div>
 <h2 class="FloatLeft"><?php if($page_name == 'edit_gallery') echo "Edit Gallery"; else echo "Create Gallery"; ?></h2>
</div>
 
           
<div class="FloatLeft SessionSuccess" id="flash_msg_id" style="display:none;top: 145px;">
<?php if($this->session->flashdata('msg')){ 
echo $this->session->flashdata('msg');
} ?></div>

<p class="FloatRight save-back save_margin article_save">
	 
	 <a class="back-top FloatLeft" href="<?php echo base_url(); ?><?php echo folder_name; ?>/gallery_manager" title="Go Back"><i class="fa fa-reply fa-2x"></i></a>
	  
	<?php if($page_name == 'edit_gallery') { ?>
	<?php if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { ?>
	<button type="button" class="btn btn-primary FloatRight i_button FlagIcon2" id="unpublishtop_id"  title="Unpublish" <?php if( isset($status) && ($status == "D") ){ ?> style="display:none" <?php } ?>></button>
	<?php } ?>
	<?php } ?>

	<?php if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) { ?>
	<button type="button" class="btn btn-primary FloatRight i_button"  id="publishtop_id" title="Publish" ><i class="fa fa fa-flag"></i></button>
	<?php } ?>

	<button type="button"  class="btn btn-primary FloatRight i_button"  id="send_drafttop_id" title="Draft"  <?php if( isset($status) && ($status == "P" or $status == "U")){ ?> style="display:none" <?php } ?> ><div class="DraftIcon"></div>
	</button>

	
<a class="back-top FloatRight top_iborder" href="#" data-remodal-target="preview_article_popup" title="Preview" id="preview_id" ><i class="fa fa-desktop i_extra"></i>
</a>


</p> 
        
        
<?php if(isset($print_status) && $print_status != "")  { ?>
 
 <span class="TopStatus" id="TopStatusId"><?php echo "Status :". $print_status;  if($publishdate != '') { echo " | Published on  : ".$publishdate; }  if($unpublishdate != '') { echo " | Unpublished on  : ".$unpublishdate; }  ?></span>
 
 <?php } ?>
         
		 
		 <?php if($page_name == 'edit_article' && $section_segment  != 'edit_archive_article') { ?>
  <p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($get_article_details['Createdby'])).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($get_article_details['Modifiedby'])).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Modifiedon'])); ?></p>
<?php } elseif($section_segment  == 'edit_archive_article') { ?>
	<p><?php echo 'Created By : '.ucfirst(($get_article_details['Createdby'])).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(($get_article_details['Modifiedby'])).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Modifiedon'])); ?></p>
<?php } ?>
		 
        </div>
        
    <div class="Overflow DropDownWrapper">
     <div>
        
              <section class="form2">
                <div class="form_list_new">
				   
<?php if($page_name == 'edit_gallery'  && $section_segment  != 'edit_archive_gallery') { ?>
  <p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($get_gallery_details['Createdby'])).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_gallery_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($get_gallery_details['Modifiedby'])).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_gallery_details['Modifiedon'])); ?></p>
<?php } elseif($section_segment  == 'edit_archive_gallery') { ?>
  <p><?php echo 'Created By : '.ucfirst($get_gallery_details['Createdby']).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_gallery_details['Createdon'])).' | '.'Last Updated By : '.ucfirst($get_gallery_details['Modifiedby']).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_gallery_details['Modifiedon'])); ?></p>
<?php } ?>
<table class="image_table1 gallery-url">
<tr>
                      <td class="MediumInputBox video_label"><label>Gallery Headline <span class="mandatory">*</span></label></td>
                      <td class="GalleryNameCount PositionRelative">
                        	<!--<div id="cke_charcount_txtgalleryname" length="100" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_gallery_details['title'])) echo mb_strlen($get_gallery_details['title']); else echo "100"?></div> -->
                        <textarea class="ckeditor" id="txtgalleryname" name="txtgalleryname"><?php echo set_value('txtgalleryname'); ?><?php  if(isset($get_gallery_details['title'])) echo $get_gallery_details['title']; ?></textarea>
                     <?php echo form_error('txtgalleryname'); ?>
					 <span id="gallery_name_error"></span>
                        </td>
                      </tr>
                      <tr>
                       <td class="MediumInputBox video_label"><label>URL Title</label></td>
                      <td> <input type="text" class="box-shad input_height WidthPercentage" name="txtUrlTitle" id="txtUrlTitle" <?php if(isset($get_gallery_details['url_title'])) { echo "readonly"; } ?>  value="<?php if(isset($get_gallery_details['content_id'])) echo $get_gallery_details['url_title']; echo set_value('txtUrlTitle'); ?>"/>
                        <?php echo form_error('txtUrlTitle'); ?></td>
                        </tr>   
			
						<?php if(isset($get_gallery_details['url'])) { ?>
							<tr>
								<td><label>URL Structure </label></td>
								<td> <a href="<?php  echo BASEURL.$get_gallery_details['url']; ?>"><?php  echo BASEURL.$get_gallery_details['url']; ?></a></td>
								</td>
							</tr>
                       <?php } ?>
					  
					  <tr>
	 	<td class="MediumInputBox video_label"><label>Summary</label></td>
		<td class="VideoCk GalleryNameCount  PositionRelative">
		<div id="cke_charcount_txtSummary" length="2000" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_gallery_details['summaryHTML'])) echo mb_strlen($get_gallery_details['summaryHTML']); else echo "2000"?></div>
		<textarea class="ckeditor" id="txtSummary"  name="txtSummary"><?php if(isset($get_gallery_details['summaryHTML'])) echo $get_gallery_details['summaryHTML']; echo set_value('txtSummary'); ?></textarea></td>
		
	</tr>
</table>
  
                <div class="FirstGallery">
              
                      <table class="image_table1">
                      
	
	
                    <tr>
                          <td class="MediumInputBox video_label"><label>Section <span class="mandatory">*</span></label></td>
                          <td><div class="FloatLeft"> 
                              <!--<div class="w2ui-label"> List: </div>-->
                              <div class="w2ui-field">
                             
                              
                              
     
  <select name="ddMainSection" class="controls" id="main_section_id" section_data="<?php echo $select_parent_name; ?>" Value="<?php echo set_value('ddMainSection'); ?><?php echo @$get_gallery_details['Section_id']; ?>">
   <option id="MainSectionOption" value="">-Select-</option>
  
 <?php if(isset($section_mapping)) { 
			$section_bool = true;
				 foreach($section_mapping as $mapping) {  
				 
				  if(folder_name == 'niecpan') 
				 $condition_value = 'Galleries';
				else 
				  $condition_value = 'புகைப்படங்கள்';
				 
				 if($mapping['Sectionname'] == $condition_value ) {   ?>

				 <option id="MainSectionOption" style="color:#933;font-size:18px;" <?php /* if($mapping['Section_landing'] == 1 && $mapping['Sectionname'] != 'Columns' && $mapping['Sectionname'] != 'Magazine' && $mapping['Sectionname'] != 'The Sunday Standard' && $mapping['Sectionname'] != 'Editorials' ) { ?> disabled='disabled' <?php }  */ ?> class="blog_option" <?php if(set_value("ddMainSection") == $mapping['Section_id'] || (isset($get_gallery_details['content_id']) && $get_gallery_details['Section_id'] == $mapping['Section_id'] )) { echo  "selected";  $section_bool=false;  } ?> sectoin_data="<?php echo @$mapping['Sectionname']; ?>" rel="<?php echo @$mapping['LinkedToColumnist']; ?>"  value="<?php echo $mapping['Section_id']; ?>"  url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim($mapping['BreadCrumb'])))); ?>" ><?php echo strip_tags($mapping['Sectionname']); ?></option>
  <?php if(!(empty($mapping['sub_section'])) ) { ?>
 
  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
    <option  id="MainSectionOption" <?php if(set_value("ddMainSection") == $sub_mapping['Section_id']  || (isset($get_gallery_details['content_id']) && $get_gallery_details['Section_id'] == $sub_mapping['Section_id'] )) { echo  "selected"; $section_bool=false; }?>  sectoin_data="<?php echo @$mapping['Sectionname']; ?>"  rel="<?php echo @$sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>"  url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_mapping['BreadCrumb'])))); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
		
		 <?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
		 
		   <?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
    <option id="MainSectionOption" <?php if($sub_sub_mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?>  <?php if(set_value("ddMainSection") == $sub_sub_mapping['Section_id']  || (isset($get_gallery_details['content_id']) && $get_gallery_details['Section_id'] == $sub_sub_mapping['Section_id'] )) { echo  "selected"; $section_bool=false; } ?>  rel="<?php echo @$sub_sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_sub_mapping['Section_id']; ?>"  sectoin_data="<?php echo @$mapping['Sectionname']; ?>"  url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_sub_mapping['BreadCrumb'])))); ?>"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
		 
		<?php } } ?>
  <?php  } } ?>

				 <?php } }
		
		
		
				 		 if($section_bool == true && isset($get_gallery_details['Section_id']) && $get_gallery_details['Section_id'] != 0 && isset($select_parent_name)) { 
					
						 ?>
					 <option id="MainSectionOption" section_data="<?php echo $select_parent_name; ?>"  value="<?php echo $get_gallery_details['Section_id']; ?>"url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim(GenerateBreadCrumbBySectionId($get_gallery_details['Section_id']))))); ?>"  selected> <?php echo $select_section_name; ?> </option>				 <?php }
					 
				  } ?>

</select>

 <?php echo form_error('ddSection'); ?>
 
  <p id="mainsection_breadcrumb"> </p>        
  
                            </div>
                            </div>
                       </td>
                      
                      </tr>
                       <tr>

							 <td class="MediumInputBox video_label"><label class="FloatRight">Country <!--<span class="mandatory">*</span>--></label></td>
                          <td><div class="FloatLeft"> 
                              <!--<div class="w2ui-label"> List: </div>-->
                              <div class="w2ui-field">
                             <div class="w2ui-field" id="Published">

   <select name="ddCountry"  class="controls" id="country_id" value="<?php echo set_value('ddCountry'); ?>">
    <option value="">-Select-</option>
                     <?php if(isset($get_country)) { 
					  foreach($get_country as $country) {?>
                     <option  <?php if(set_value("ddCountry") == $country->Country_id || (isset($get_gallery_details['Country_ID']) && $get_gallery_details['Country_ID'] == $country->Country_id )) echo  "selected"; ?> value="<?php echo $country->Country_id; ?>"><?php echo ucfirst($country->CountryName); ?></option>
                      <?php } } ?>                     
                    </select>  
                    
 <?php echo form_error('ddCountry'); ?>


</div>
                            </div>
                            </div></td>

                        </tr>
                        <tr>
                          <td class="MediumInputBox video_label"><label>State </label></td>
                          <td><div class="FloatLeft"> 
                              <!--<div class="w2ui-label"> List: </div>-->
                       
  <!-- <select name="ddState"  class="controls" id="state_id" value="<?php echo set_value('ddState'); ?>" >
    <option value="">-Select-</option>
                           <?php if(isset($get_state)) { 
					  foreach($get_state as $state) {?>
                     <option  <?php if(set_value("ddState") == $state->State_Id || (isset($get_gallery_details['State_ID']) && $get_gallery_details['State_ID'] == $state->State_Id )) echo  "selected";  ?> value="<?php echo $state->State_Id; ?>"><?php echo ucfirst($state->StateName); ?></option>
                      <?php } } ?>                 
                    </select> -->
					
		<input type="text" name="txtState" id="txtState" value="<?php if(isset($select_state) && $select_state != "") {  echo $select_state; } echo set_value('txtState'); ?>"  />
         <input type="hidden" id="state_id" name="ddState" value="<?php if(isset($get_gallery_details['State_ID']) && $get_gallery_details['State_ID'] != "") {  echo $get_gallery_details['State_ID']; } echo set_value('ddState'); ?>" />
	
    <?php echo form_error('ddState'); ?>
                    
 <?php echo form_error('ddState'); ?>
                            </div>
                       </td>
                        </tr>
                        <tr>
                        
							 <td class="MediumInputBox video_label"><label class="FloatRight">City</label></td>
                          <td><div class="FloatLeft"> 
                              <!--<div class="w2ui-label"> List: </div>-->
                              
        <div class="w2ui-field" id="Published">
		<input type="text" name="txtCity" id="txtCity" value="<?php if(isset($select_city) && $select_city != "") {  echo $select_city; } echo set_value('txtCity'); ?>"  />
         <input type="hidden" id="city_id" name="ddCity" value="<?php if(isset($get_gallery_details['City_ID']) && $get_gallery_details['City_ID'] != "") {  echo $get_gallery_details['City_ID']; } echo set_value('ddCity'); ?>" />
					
		<?php echo form_error('ddCity'); ?>
		</div>         
                            </div></td>
                        </tr>
                      <tr>
                        <td class="MediumInputBox video_label"><label>
                              <div align="left">Agency
                              </div>
                            </label></td>
                            <td><div class="FloatLeft"> 
                              <div align="left">
                                <!--<div class="w2ui-label"> List: </div>-->
                                
                              </div>
                               <div class="w2ui-field" id="Published">
                            
                                 <div align="left">
                                   <select name="ddAgency"  class="controls" id="agency_id"  >
                                     <option value="">-Select-</option>
                                     <?php if(isset($get_agency)) { 
									 $boolagency = true;
                        foreach($get_agency as $agency) {?>
                                     <option value="<?php echo $agency->Agency_id; ?>" <?php if(set_value("ddAgency") == $agency->Agency_id || (isset($get_gallery_details['Agency_ID']) && $get_gallery_details['Agency_ID'] == $agency->Agency_id)) { echo  "selected='selected'"; $boolagency = false; } if($page_name != 'edit_gallery' && $agency->Agency_name == 'New Indian Express' ||  $agency->Agency_name == 'DIN') { echo "selected='selected'"; $boolagency = false;  } ?>  ><?php echo ucfirst($agency->Agency_name); ?></option>
                                     <?php } }   
									 
									 if($boolagency == true &&  isset($get_gallery_details['Agency_ID']) && $get_gallery_details['Agency_ID'] != 0) { ?>
									  <option value="<?php echo $get_gallery_details['Agency_ID']; ?>" selected ><?php echo @$select_agency; ?></option>
								  <?php } ?>              
                                   </select>
                                   
                                   <?php echo form_error('ddAgency'); ?>
                                   
                                 </div>
                  
            </div>
                  
                              </div></td>
                              </tr>
                            <tr>
                            <td class="MediumInputBox video_label"><label>
                              <div align="left">ByLine 
                              </div>
                            </label></td>
                            <td><div class="FloatLeft"> 
                              <div align="left">
                                <!--<div class="w2ui-label"> List: </div>-->
                                
                              </div>
                               <div class="w2ui-field" id="Published">
                            
                                 <div align="left">
                                   
                                    <input type="text" name="txtByLine" id="txtByLine" value="<?php if(isset($select_author) && $select_author != "") {  echo $select_author; } echo set_value('txtbyline'); ?>"  />
         <input type="hidden" id="byline_id" name="ddByLine" value="<?php if(isset($get_gallery_details['Author_ID']) && $get_gallery_details['Author_ID'] != "") {  echo $get_gallery_details['Author_ID']; } echo set_value('hidden_byine_id'); ?>" />
                                   
                                   
                                   <?php echo form_error('ddByLine'); ?>
                                   
                                 </div>
                  
            </div>
                  
                              </div></td>
                          </tr>
                        
                   </table>
                </div>     
                <div class="SecondGallery">
                	 <table class="FloatLeft metatitle1 image_table_2">
                     <tr>
                          <td class="video_label"><label>Tags</label></td>
                          <td class="margin-bottom-0">
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
    					<tr>
      <td class="video_label"><label>Meta Title <span class="mandatory">*</span></label></td>
      <td>
	  <div class="FloatLeft">
	  <input type="text" class="box-shad" maxlength="100" id="count1" name="txtMetaTitle" value="<?php if(isset($get_gallery_details['MetaTitle'])) echo htmlentities($get_gallery_details['MetaTitle']); echo htmlentities(set_value('txtMetaTitle')); ?>" />
	  </div>
	    <div id="charNum1" class="charNum"><?php if(isset($get_gallery_details['MetaTitle'])) echo 100 - mb_strlen($get_gallery_details['MetaTitle']); else echo "100"?></div>
      <?php echo form_error('txtMetaTitle'); ?>
      </td>
     </tr>
     <tr>
      <td valign="top" class="video_label"><label>Meta Description </label></td>
      <td  valign="top">
	   <div class="FloatLeft">
	  <textarea class="box-shad" maxlength="200" id="count2" name="txtMetaDescription"><?php if(isset($get_gallery_details['MetaDescription'])) echo htmlentities($get_gallery_details['MetaDescription']); echo htmlentities(set_value('txtMetaDescription')); ?></textarea>
	   </div>
	  <div id="charNum2" class="charNum"><?php if(isset($get_gallery_details['MetaDescription'])) echo 200 - mb_strlen($get_gallery_details['MetaDescription']); else echo "200"?></div>
      <?php echo form_error('txtmetadescription'); ?>
      </td>
     </tr>
   </table>
                    <div class="MetaDescription WidthAuto FloatLeft">
                    <label>Social Meta Tag</label>
                   <ul class="MetaDescriptionList">
                     <li>
                   <input type="checkbox" id="cbNoIndex" name="cbNoIndex" <?php if(set_value("cbNoIndex") == 'on' ||   (isset($get_gallery_details['Noindexed']) && $get_gallery_details['Noindexed'] == 1 ) ) echo  "checked"; ?> />
                <label class="include_label" >No Index</label>
                    </li>
                    <li>
                  <input type="checkbox" id="cbNoFollows" name="cbNoFollows" <?php if(set_value("cbNoFollows") == 'on' ||   (isset($get_gallery_details['Nofollow']) && $get_gallery_details['Nofollow'] == 1 )  ) echo  "checked"; ?> />
                <label class="include_label" >No Follow</label>
                    </li>
                   </ul>
                  </div>
                  
                   <table class="FloatLeft metatitle1 image_table_2">
    				 <tr>
      <td valign="top" class="video_label GalleryCanonic"><label>Canonical URL</label></td>
      <td  valign="top"><input type="text" class="input_height box-shad1 FloatNone" name="txtCanonicalUrl"  value="<?php echo set_value('txtCanonicalUrl'); ?><?php if(isset($get_gallery_details['Canonicalurl'])) echo $get_gallery_details['Canonicalurl']; ?>" />
      <?php echo form_error('txtCanonicalUrl'); ?>
      </td>
     </tr>
   					</table>
                  
                </div>
                      
            	</div>
              </section>   
            
        <ul class="tabs GalleryTabs" >
        <li class="selected"><a href="#view1" >Upload</a></li> 
        <li class=""><a id="section_href" href="#view2" >Multi Section Mapping</a></li>
        </ul>
       
          <section class="GalleryTabsContent">

              <div class="tabcontents img_tab_contents">
            
            <div id="view1" style="display: block;">
            <div class="img_gallery">
			
            <div class="addFiles margin-top-0 add_files_new">
                  <a href="#" data-remodal-target="modal1" id="BrowsePopup" class="fileUpload btn btn-primary">Browse</a>
				</div>
			</div>	
			
            <div class="CropperWrapper" id="crop_container" <?php if(isset($temp_images) && count($temp_images) > 0) {} else{ ?> style="display:none;" <?php } ?>>
			
			<table width="100%" cellspacing="0" class="display article_table dataTable no-footer gallerydatatable" id="sort" role="grid" aria-describedby="link_preview_table_info" style="width: 100%;"> 
				<thead id="link_preview_head" style="" >
				
					<tr role="row">
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 100px;" aria-label="Type: activate to sort column ascending">Display Order</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 120px;" aria-label="Type: activate to sort column ascending">Image</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 300px;" aria-sort="ascending" aria-label="">Caption</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 300px;" aria-sort="ascending" aria-label="">Alt Tag</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">Image Name</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 100px;" aria-label="Action: activate to sort column ascending">Action</th>
					
					</tr>
				</thead>
				<tbody id="link_preview_body" class="ui-sortable">
			<?php if(isset($temp_images)) {  
			$Count = 0;
			?>
					
				 <?php foreach($temp_images as $key=>$values)  { 
				 if($Count%2 == 0)
					$Class = 'class = "odd" role="row"';
				else 
					$Class = 'class = "even" role="row"';
				
				$Physical_array = explode('.', $values->image_name);
				 ?>
						<tr <?php echo $Class; ?> id='gallery_image<?php echo $values->imageid; ?>'>
						
						<td id='index<?php echo $Count; ?>' class='index' data-image_id='<?php echo $values->imageid; ?>' data-image_caption="<?php echo $values->caption; ?>" data-image_alt="<?php echo $values->alt_tag; ?>" data-physical_name='<?php echo $values->physical_name; ?>' data-physical_extension='<?php echo $Physical_array[1]; ?>' data-image1_type='<?php echo $values->image1_type; ?>'  data-image2_type='<?php echo $values->image2_type; ?>'  data-image3_type='<?php echo $values->image3_type; ?>'   data-image4_type='<?php echo $values->image4_type; ?>'   data-imagecontent_id='<?php echo $values->imagecontent_id; ?>'  data-source='<?php echo image_url.gallery_temp_image_path.$values->image_name; ?>'  ><?php echo $Count+1; ?></td>
						
						<td><img class="gallery_dataimage" src='<?php echo image_url.gallery_temp_image_path.$values->image_name; ?>'/></td>
						
						<td><div align='center'><textarea style='width:300px; text-align:center;'  name="image_caption" id="image_caption_id"  rel="<?php echo $Count; ?>" value="" ><?php echo str_replace('"',"'",$values->caption); ?></textarea></div></td>
						
						<td><div align='center'><textarea style='width:300px; text-align:center;'  name="image_alt" id="image_alt_id"  rel="<?php echo $Count; ?>"  ><?php echo str_replace('"',"'",$values->alt_tag); ?></textarea></div></td>
						
						<td><div align='center'><input type="textbox" style='width:100%; text-align:center;'  name="physical_name" id="physical_name" maxlength=80 rel="<?php echo $Count; ?>" physical_extension="<?php echo $Physical_array[1]; ?>" value='<?php echo str_replace("'",'"',$values->physical_name); ?>' temp_id="<?php echo $values->imageid; ?>" /></div><span class='error' id="error_<?php echo $values->imageid; ?>"></span></td>
						
						<td><div class='article_table_delete'>  <a  id='edit_image' rel='<?php echo $values->imageid; ?>' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete'  index_value="<?php echo $Count; ?>" id='deletetempimage' rel='<?php echo $values->imageid; ?>' ><i class='fa fa-trash-o'></i></a></div></td>
						</tr>
				 <?php $Count++; }  ?>
					
			<?php } ?>
			</tbody>
			</table>
                </div> 
				  </div>
            <div id="view2">
              <div class="mapping_section">
      <div class="tree_check"><input type="checkbox" id="show_list" /><label for="show_list">Show the mapped section</label></div>
         <div class="mapping_contents">
         <h1>Section and Sub-Section Mapping</h1>
      <div class="tree_head" id="mapping_1">
      </div>
	  
      <div id="mapping_2" class="tree_head_dup">
      </div>
      </div>
            </div>
            </div>
             
      </section>
      </div>
        </div>
  </div>
    </div>
    
   
    
    </form>
	
	<div class="remodal" id="preview_article_popup" data-remodal-options="hashTracking:false" data-remodal-id="preview_article_popup" style="position:relative;">
 <div id="preview_article_popup_loading">
 </div>
 <div id="preview_article_popup_container"  class="container" style="display:none;">
 </div>
 </div>

    
     <div class="remodal" data-remodal-id="modal1" data-remodal-options="hashTracking:false" style="position:relative;">
            <div class="article_popup GalleryPopup">
            <div class="article_popup1">
            <ul class="article_popup_tabs">
            <li onclick="articleUpload()" class="active img_upload">From Local System</li>
            <li onclick="articleBrowse()" class="img_browse">From Library</li>
            </ul>
            </div>
            <div class="article_popup2">
            <div class="article_upload">
       
          <form  name="ImageForm" id="ImageForm" action="<?php echo base_url(); ?><?php echo folder_name; ?>/image/multiple_image_upload" method="POST" enctype="multipart/form-data">
		  <input type="hidden" name="content_type" value="3" />
              <div class="popup_addfiles">
                <div class="fileUpload btn btn-primary WidthAuto">
                    <span>+ Select Images</span>
                    <input type="file" id="addimagelibrary" multiple name="imagelibrary[]" accept="image/*" class="upload" style="width:100%;">
            </div>
            
             <div id="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
			Please wait while the selected files are added to the container
            </div>
            
        </form>
          </div>
            <div class="GalleryDrag"  id="drop-area">
Drop files anywhere here to upload or click on the "Select Images" button above</div>
            </div>
            
            <div class="article_browse">
            <h3>Pick the item to insert</h3>
            <div class="article_browse_drop">
           
<input type="text" class="box-shad1 FloatLeft BrowseInput" name="txtBrowserSearch" id="search_caption" placeholder="Search">
<i class="fa fa-search FloatLeft BrowseSearch" id="image_search_id"></i>
<a  class="btn btn-primary margin-left-10" id="clear_search" href="javascript:void(0);" style="display:none;">Clear Search</a>
 <div id="LoadingSelectImageLibrary" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
			Please wait while the selected images are added to the container
            </div>

            </div>
         
            <div class="popup_images transitions-enabled infinite-scroll clearfix"  id="image_lists_id">
            </div>
			<nav id="page-nav">
			  <a href="<?php echo base_url(); ?><?php echo folder_name; ?>/article_image/search_image_library_scroll/2"></a>
			</nav>
            </div>
            <div class="ImageContainer" id="ImageContainerSet">
          
            </div>
            <div class="FloatRight popup_insert">
       <button type="button" class="btn btn-primary"id="add_to_edit" >Add</button>
            </div>
            </div>
            </div>
            </div>
            
            <div class="remodal" id="version_content_model" data-remodal-id="version_model" style="position:relative;">
        
  </div>
  
  
<script type="text/javascript"> 


	 $("#preview_id").click(function() {
			
			
			$("#preview_article_popup_container").hide();
			$("#preview_article_popup_loading").hide();
			
				publishdate =  '';
				last_update = '';
			<?php if(isset($get_gallery_details['publish_start_date'])) { ?>
				publishdate = "<?php echo date('dS  F Y h:i A',strtotime($get_gallery_details['publish_start_date'])); ?>";
			<?php } ?>
	
			/*
			<?php if(isset($get_gallery_details['Modifiedon'])) { ?>
				last_update = "<?php echo date('dS  F Y h:i A',strtotime($get_gallery_details['Modifiedon'])); ?>";
			<?php } ?>
			*/
		
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
				
				var content_type 			= 3;

			head_line = encodeURIComponent(CKEDITOR.instances.txtgalleryname.getData());
			body_text = encodeURIComponent(CKEDITOR.instances.txtSummary.getData());
			
			gallery_images 		= JSON.stringify(SelectImage);
			
			var Index = parseInt(Object.keys(SelectImage).length);
			
			var tags 					= JSON.stringify(tags);
	
			var agency_id 				= $("#agency_id").val();
			var section_id 				= encodeURIComponent($.trim($("#main_section_id").val()));
	
			var postdata = { "head_line" : head_line,"body_text" :body_text,"gallery_images":gallery_images,"tags" : tags,"publishdate" :publishdate,"last_update" : last_update,"section_id" : section_id,"agency_id" :agency_id,"content_type": content_type};
			$.ajax({
			url: base_url+folder_name+"/gallery_manager/get_gallery_preview_popup", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			dataType: "HTML",
			async: false, 
			success: function(data)   // A function to be called if request succeeds
			{
				
		
		setTimeout(function(){
		
		$("#contents_css").remove();

		$(".previewcontainer").append('<link rel="stylesheet" href="'+base_url+'css/FrontEnd/css/style.css" type="text/css">');
		
		if(folder_name == 'dmcpan') {
			$(".previewcontainer").append('<link rel="stylesheet" href="'+base_url+'css/FrontEnd/css/media.css" type="text/css"><link rel="stylesheet" href="'+base_url+'css/FrontEnd/css/slick.css" type="text/css">'); 
		}
		


	},1000);
	
	setTimeout(function(){

		
	$('.remodal-close').show();
	$("#preview_article_popup_container").html(data);
		
		$("#preview_article_popup_loading").hide();
	$("#preview_article_popup_container").show();
	
	
	setTimeout(function(){
		
/* */
	
	if(folder_name == 'dmcpan') {
		var previous = "முந்தைய  புகைப்படம்";
		var next = "அடுத்த புகைப்படம்";
	} else {
		var previous = "Previous Image";
		var next = "Next Image";
	}
		
		$('.GalleryDetailSlide').slick({
        dots: true,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
		prevArrow: '<button type="button" data-role="none" class="slick-prev" title="'+previous+'">Previous</button>', nextArrow: '<button type="button" data-role="none" class="slick-next" title="'+next+'">Next</button>',
        slidesToShow: 1,
        slidesToScroll: 1,
		adaptiveHeight: true
    });
	

				 $('#gallery_pagination').twbsPagination({
						totalPages: Index,
						//startPage: parseInt(1),
						visiblePages: 5,
						initiateStartPageClick: false,
						loop: true,
						onPageClick: function (event, page) {
							$('.GalleryDetailSlide').slick('slickGoTo', page-1);
						}
				 });

	
	//$('.GalleryDetailSlide').slick();
	$('.slick-prev').addClass('fa fa-chevron-left');
	$('.slick-next').addClass('fa fa-chevron-right');
	},100);
	
		},1000);
		
		
			}
		});
				
		
	}); 
	
	$(document).on('close', '#preview_article_popup', function () {  

		$(".css_and_js_files").append($('<link rel="stylesheet" href="'+base_url+'includes/ckeditor/contents.css" type="text/css"  id="contents_css">'));  
				
		//$("script[src='"+base_url+"js/FrontEnd/js/bootstrap-paginator.js']").remove();
		//$("script[src='"+base_url+"js/FrontEnd/js/slick.js']").remove();
			
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/style.css"]').remove();
		if(folder_name == 'dmcpan') {
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/media.css"]').remove();
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/slick.css"]').remove();
		}
 
}); 
	

var base_url = '<?php echo base_url(); ?>';

<?php if(isset($print_status)) { ?>

function display_status(print_status,publish_date)
{
var postdata = "get_content_id="+<?php echo $get_gallery_details['content_id'] ?>+"&print_status="+print_status+"&publishdate="+publish_date+"&unpublishdate="+"<?php echo $unpublishdate ?>"+"&content_type=3";

 $.ajax({
   url: base_url+folder_name+"/common/status_statement", // Url to which the request is send
   type: "POST",             // Type of request to be send, called as method
   data:  postdata,
   dataType: "HTML",
   async: false, 
   success: function(data)   // A function to be called if request succeeds
   {
		
	if (data.indexOf("LIVE | Status") >= 0) 
	$("#ddMainSection").attr('disabled',true)
	else
	$("#ddMainSection").attr('disabled',false)
	
    $("#TopStatusId").html(data);
	
	var Status_string = "<p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($get_gallery_details['Createdby'])).' | '.'Created On : '. date('d-m-Y H:i:s',strtotime($get_gallery_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($get_gallery_details['Modifiedby'])).' | '.'Last Updated On : '. date('d-m-Y H:i:s',strtotime($get_gallery_details['Modifiedon'])); ?></p>";
	 
	 if (data.indexOf("LIVE | Status") >= 0) 
	  $("#TopStatusId").append(Status_string);
  
	$(".form_list_new").css("margin-top","65px");
	
   }
 });
}


<?php } ?>

function articleUpload() {
	$('.article_upload').css({"display" : "block"});
	$('.article_browse').css({"display" : "none"});
	$('.img_upload').addClass('active');
	$('.img_browse').removeClass('active');
}
function articleBrowse() {
	$('.article_upload').css({"display" : "none"});
	$('.article_browse').css({"display" : "block"});
	$('.img_browse').addClass('active');
	$('.img_upload').removeClass('active');
}

var SelectImage = {};
<?php if(isset($temp_images)) {
$Count =0;	
foreach($temp_images as $image) { 

$Caption = str_replace('"',"'",$image->caption);
$Alt 	 = str_replace('"',"'",$image->alt_tag);

$Physical_array = explode('.', $values->image_name);
	
if($image->imagecontent_id != '') { 
?>

  					 SelectImage["<?php echo $Count; ?>"] = {	
					  'image_id' 					: "<?php echo $image->imageid; ?>",
					  'image_caption' 				: "<?php echo $Caption; ?>",
					  'image_alt' 					: "<?php echo $Alt; ?>",
					  'physical_name' 				: '<?php echo $image->physical_name; ?>',
					  'physical_extension' 			: '<?php echo $Physical_array[1]; ?>',
					  'source'						: '<?php echo image_url.gallery_temp_image_path.$image->image_name; ?>',
					  'image1_type'					: "<?php echo $image->image1_type; ?>",
					  'image2_type'					: "<?php echo $image->image2_type; ?>",
					  'image3_type'					: "<?php echo $image->image3_type; ?>",
					  'image4_type'					: "<?php echo $image->image4_type; ?>",
					  'imagecontent_id' 			: "<?php echo $image->imagecontent_id; ?>",
					  'display_order'				: "<?php echo $image->display_order; ?>"
					  };
					  
			
	
<?php } else { ?>

					 SelectImage["<?php echo $Count; ?>"] = {
					  'image_id' 				: "<?php echo $image->imageid; ?>",
					  'image_caption' 			: '<?php echo $Caption; ?>',
					  'image_alt' 				: '<?php echo $Alt; ?>',
					  'physical_name' 			: '<?php echo $image->physical_name; ?>',
					  'physical_extension' 		: '<?php echo $Physical_array[1]; ?>',
					  'source'					: '<?php echo image_url.gallery_temp_image_path.$image->image_name; ?>',
					  'image1_type'				: "<?php echo $image->image1_type; ?>",
					  'image2_type'				: "<?php echo $image->image2_type; ?>",
					  'image3_type'				: "<?php echo $image->image3_type; ?>",
					  'image4_type'				: "<?php echo $image->image4_type; ?>",
					  'display_order'			: "<?php echo $image->display_order; ?>"
					   };
					 				
			
<?php } $Count++; } } ?>

 $("#gallery_data").val(JSON.stringify(SelectImage));

</script>

    <script src="<?php echo image_url; ?>js/tabcontent.js" type="text/javascript"></script>
	<script src="<?php echo image_url; ?>js/jquery.form.js" type="text/javascript"></script>
    <script src="<?php echo image_url; ?>includes/ckeditor/ckeditor.js"  type="text/javascript"></script>
	<script src="<?php echo image_url; ?>js/bootstrap/bootstrap.min.js"  type="text/javascript"></script>
 	<script src="<?php echo image_url; ?>js/jquery.validate.min.js" type="text/javascript" ></script>
	<script src="<?php echo image_url; ?>js/additional-methods.min.js"  type="text/javascript"></script>
	<script src="<?php echo image_url; ?>js/jquery.remodal.js" type="text/javascript" ></script>
	<script src="<?php echo image_url; ?>js/gallery-process_js.js" type="text/javascript"></script>
	<script src="<?php echo image_url; ?>js/FrontEnd/js/slick.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.twbsPagination.min.js"></script>
	


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
					columnWidth: 25
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

jqis("#BrowsePopup").click(function(){
		
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
		
	}
		});
	
	/*
	function ImageExist(url) 
	{
	   var img = new Image();
	   img.src = url;
	   return img.height != 0;
	}
	*/
	
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
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		}	
			var Caption = jqis("#search_caption").val();
			var Section	= jqis("#image_section_search").val();
			
			postdata = "Caption="+Caption+"&Section="+Section;
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
						
						
						
					Content +='<img id="image_lists_images_id" data-content_id="'+item.content_id+'"  data-image_caption="'+item.ImageCaption+'" data-image_alt="'+item.ImageAlt+'" data-image_date="'+item.Modifiedon+'" data-image_source="'+Image_URL+item.ImagePhysicalPath+'"  src="'+Image_URL+item.ImagePhysicalPath+'" />';
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
 <link rel="StyleSheet" href="<?php  echo image_url; ?>includes/Tagedit-master/css/jquery.tagedit.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?php  echo image_url; ?>includes/Tagedit-master/js/jquery-1.10.2.min.js"></script>
<script src="<?php echo  image_url; ?>js/jquery_ui.js"></script>
<script type="text/javascript" src="<?php echo  image_url; ?>includes/Tagedit-master/js/jquery.autoGrowInput.js"></script>
<script type="text/javascript" src="<?php  echo image_url; ?>includes/Tagedit-master/js/jquery.tagedit.js"></script>
    
    <script type="text/javascript">

	var jqs = $.noConflict();
	jqs(function() {
		jqs( "#tagform-full" ).find('input.tag').tagedit({
			autocompleteURL: "<?php echo base_url(); ?>"+folder_name+"/common/tags",
			allowEdit: false, // Switch on/off edit entries
 			allowDelete: false // Switch on/off deletion of entries. Will be ignored if allowEdit = false
		});
		
		
	/*	jqs("#agency_id").change(function() {
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
		
		jqs( "#txtCity" ).val('');
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
	
		jqis("#clear_search").click(function() {
		jqis("#search_caption").val('');
 var $container = jqis('.popup_images');
		 $container.empty();
	if(jqis.trim($container.html()) == '') {
		
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url; ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
	
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
	
	
<script src="<?php echo image_url; ?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="<?php echo image_url; ?>js/jquery-ui.min-1.8.18.js" type="text/javascript"></script>

<script type="text/javascript">

var jqs_datatable = $.noConflict();

jqs_datatable(document).ready(function(){
var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
},
    updateIndex = function(e, ui) {
		
			SelectImage  = [];
		
        jqs_datatable('td.index', ui.item.parent()).each(function (i) {
            jqs_datatable(this).html(i + 1);
			
		
			var data = $(this).data();
			
	
			  SelectImage[i] = {
						  'image_id' 			: data.image_id,
						  'image_caption' 		: $(this).closest('tr').find("#image_caption_id").text(),
						  'image_alt' 			: $(this).closest('tr').find("#image_alt_id").text(),
						  'physical_name'		: data.physical_name,
						  'physical_extension' 	: data.physical_extension,
						  'source' 				: data.source,
						  'image1_type' 		: data.image1_type,
						  'image2_type' 		: data.image2_type,
						  'image3_type' 		: data.image3_type,
						  'image4_type' 		: data.image4_type,
						  'imagecontent_id' 	: data.imagecontent_id
						  };
			
        });
			rearrange_gallery_container();
			$("#gallery_data").val(JSON.stringify(SelectImage));
			
		
		jqs_datatable('td input[type=text].updatefield', ui.item.parent()).each(function (i) {
            jqs_datatable(this).val(i + 1);
			console.log( $(this).val(i + 1));
        });
    };

jqs_datatable("#sort tbody").sortable({
    helper: fixHelperModified,
	 placeholder:'must-have-class',
    stop: updateIndex
});
});

</script>