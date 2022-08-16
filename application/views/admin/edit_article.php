<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript"> var base_url = '<?php echo site_url(); ?>' 
var external_array = [];
</script>

<link href="<?php echo base_url();?>/css/article.css" rel="stylesheet" />
<link href="<?php echo base_url();?>includes/ckeditor/contents.css" rel="stylesheet" />
<link href="<?php echo base_url();?>css/jquery.datetimepicker.css" rel="stylesheet" />
<link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet" media="screen">

<title>Edit Article</title>
</head>

<body>

  <div class="col-lg-12" style="text-align:center; height:75px;"><img src="<?php echo base_url();?>/images/logo.png"></div>

<table width="100%" border="0" cellspacing="5" cellpadding="0" style="border-bottom:1px solid #666;">
        <tbody>
        <tr>
        <td width="75%"><div class="innerhead">Edit Article</div></td>
        <td width="15%" align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td width="50%" class="rightsidebordertable"><div align="center"><input type="image" src="<?php echo base_url();?>/images/backbut.png" border="0" width="20" height="20"></div></td>
            <td width="50%"><a href="javascript:void(0);" id="publish_id"><div align="center"><input type="image" src="<?php echo base_url();?>/images/save.png" border="0" width="20" height="20"></div></a></td>
              <td width="50%"><a href="javascript:void(0);" id="send_draft_id"><div align="center"><input type="image" src="<?php echo base_url();?>/images/save.png" border="0" width="20" height="20"></div></a></td>
              <td width="50%"><a href="javascript:void(0);" id="send_approvel_id"><div align="center"><input type="image" src="<?php echo base_url();?>/images/save.png" border="0" width="20" height="20"></div></a></td>
          </tr>
          <tr>
            <td class="rightsidebordertable"><div align="center"><label>Back</label></div></td>
            <td>
            	<div align="center">
               	 <label >Publish</label>
            	</div>
            </td>
              <td>
            	<div align="center">
               	 <label >Save Draft</label>
            	</div>
               </td>
                <td>
                <div align="center">
               	 <label >Send for Approval</label>
            	</div>
           </td>
          </tr>
        </tbody></table></td>
        </tr>
    </tbody>
    </table>
    
    <table>
    <tr>
    <br />
          <td>
          <a href="javascript:void(0);" id="content_tab" >Content</a> &nbsp; &nbsp; | &nbsp; &nbsp;
          <a href="javascript:void(0);" id="multiple_section_mapping" >Multi section mapping</a> &nbsp; &nbsp; | &nbsp; &nbsp;
          <a href="javascript:void(0);" id="related_content" >Related Content</a> &nbsp; &nbsp;
          </td>
        </tr>
        <tr>
        <td>
        <br /></td>
        </tr>
</table>

<div class="main_content">
<div id="flash_msg_id" class="flash_message" style="display:none;">
<?php if($this->session->flashdata('msg')){ 
echo $this->session->flashdata('msg');
} ?>
</div>
   <form action="<?php echo base_url(); ?>niecpan/article_manager/update_article/<?php echo $get_article_details['content_id']; ?>" method="post" id="content_form" name="content_form" enctype="multipart/form-data">
	<div id="content_tab_details">
 
        <div class="left_content">
        	<input type="hidden" name="txtStatus" id="status_id" value="P" />
                <div class="input_field">
                    <label>URL Title<sup>*</sup></label>
                    <div>                    <input type="text" class="form-control" name="txtUrlTitle" value="<?php if(isset($get_article_details['TitleUsedInURL'])) echo $get_article_details['TitleUsedInURL'];  ?><?php echo set_value('txtUrlTitle'); ?>" class="form-control" />
                    <?php echo form_error('txtUrlTitle'); ?>
                    </div>
                </div>
                <div class="input_field">
                    <label>Main Section<sup>*</sup></label>

<select  name="ddMainSection" class="form-control" id="main_section_id" Value="">

 <?php if(isset($section_mapping)) { 
				 foreach($section_mapping as $mapping) {  ?>

<option class="blog_option" <?php if(set_value("ddMainSection") == $mapping['Section_id'] || $get_article_details['Section_id'] == $mapping['Section_id']) echo  "selected='selected'";  ?>  value="<?php echo $mapping['Section_id']; ?>"><h5><?php echo $mapping['Sectionname']; ?></h5>
  <?php if(!(empty($mapping['sub_section']))) { ?>
  <optgroup>
  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
    <option <?php if(set_value("ddMainSection") == $sub_mapping['Section_id']  || $get_article_details['Section_id'] == $sub_mapping['Section_id']) echo  "selected='selected'"; ?> value="<?php echo $sub_mapping['Section_id']; ?>">  <?php echo $sub_mapping['Sectionname']; ?></option>
    	<?php } ?>
  </optgroup>
  <?php   } ?>
 </option>

  <?php } } ?>

</select>
 <?php echo form_error('ddMainSection'); ?>

        </div>
                <hr/>
                
                <div class="input_field" >
                    <label>Article Page HeadLine<sup>*</sup></label>
                 	<div><textarea class="ckeditor" id="article_head_line_id" name="txtArticleHeadLine" class="text_box textbox"><?php echo set_value('txtArticleHeadLine'); ?><?php echo $get_article_details['Title']; ?></textarea>
                     <?php echo form_error('txtArticleHeadLine'); ?>
                    </div>
                </div>
				<hr/>
                
               <div class="input_field" >
                    <label>Meta Title<sup>*</sup></label>
                 	<div><input type="text" name="txtMetaTitle" class="form-control" value="<?php echo set_value('txtMetaTitle'); ?><?php echo $get_article_details['MetaTitle']; ?>" onkeypress="textCounter(this,'meta_title_counter',25);" />  <?php echo form_error('txtMetaTitle'); ?><span id="meta_title_counter">25</span></div>
                </div>
                
                <div class="input_field" >
                    <label>Meta Description</label>
                 	<div><textarea name="txtMetaDescription"  cols="50" rows="7" onkeypress="textCounter(this,'meta_description_counter',50);" class="form-control"><?php echo set_value('txtMetaDescription'); ?><?php echo $get_article_details['MetaDescription']; ?></textarea><span id="meta_description_counter">50</span></div>
                </div>
                
                 <div class="input_field" >
                    <label>URL Structure</label>
                 	<div><input type="text" name="txtUrlStructure" class="form-control" value="<?php echo set_value('txtUrlStructure'); ?><?php echo $get_article_details['URL']; ?>" /></div>
                </div>
                
				<hr/>
                
                 <div class="input_field" >
                    <label>Summary<sup>*</sup></label>
                 	<div><textarea class="ckeditor"  id="summary"  name="txtSummary" cols="30" rows="19" ><?php echo set_value('txtSummary'); ?><?php echo $get_article_details['SummaryHTML']; ?></textarea> <?php echo form_error('txtSummary'); ?></div>
                </div>
                
                 <div class="input_field" >
                    <label>Body Text<sup>*</sup></label>
                 	<div><textarea class="ckeditor" id="body_text" name="txtBodyText" cols="70" rows="19"><?php echo set_value('txtBodyText'); ?><?php echo $get_article_details['ArticlePageContentHTML']; ?></textarea> <?php echo form_error('txtBodyText'); ?></div>
                </div>
 
 				<hr/>
                
                <div class="image_section">
                    <div  class="home_image">
                   		<p class="upload_header">Home Page</p>
                        <div id="upload_image"  class="upload_image_class">
                        <p   id="home_uploaded_image"><?php if(set_value('imgHomeImageId') == '' && $get_article_details['homepageimageid'] == '') echo "Image not set"; else echo "Image set"; ?></p>
                       <div class="upload_content">
                         <div class="choose_file">
                     <button type="button" data-toggle="modal" data-target="#ImagePopup" class="btn btn-primary" onclick="ChangePopup('home')">Set Image</button>
              			 </div>
                           <a href="javascript:void(0);" rel="home" id="delete_image_id">  <span class="delete_image" rel="home" id="delete_image">Del</span></a>
                    <input type="hidden" class="image-group"  name="imgHomeImageId" value="<?php echo set_value('imgHomeImageId'); ?><?php echo $get_article_details['homepageimageid']; ?>" id="home_image_gallery_id"/>
                       </div>
                        </div>
                    </div>
                    <div  class="section_image">
                    <p  class="upload_header">Section Page</p>
                        <div id="upload_image"  class="upload_image_class">
                        <p  id="section_uploaded_image"><?php if(set_value('imgSectionImageId') == ''  && $get_article_details['Sectionimageid'] == '') echo "Image not set"; else echo "Image set"; ?></p>
                          <div class="upload_content">
                           <div class="choose_file">
                               <button type="button" data-toggle="modal"  class="btn btn-primary" data-target="#ImagePopup" onclick="ChangePopup('section')">Set Image</button>
                           </div>
                            <a href="javascript:void(0);" rel="section" id="delete_image_id"> <span class="delete_image" rel="section" id="delete_image">Del</span></a>
                            <input type="hidden" class="image-group" name="imgSectionImageId"  value="<?php echo set_value('imgSectionImageId'); ?><?php echo $get_article_details['Sectionimageid']; ?>" id="section_image_gallery_id" />
                       </div>
                        </div>
                    </div>
                   	<div  class="article_image">
                
                    <p  class="upload_header">Article Page</p>
                        <div id="upload_image" class="upload_image_class">
                        <p  id="article_uploaded_image"><?php if(set_value('imgArticleImageId') == ''  && $get_article_details['articleimageid'] == '') echo "Image not set"; else echo "Image set"; ?></p>
                          <div class="upload_content">
                             <div class="choose_file">
                              <button type="button" data-toggle="modal"  class="btn btn-primary" data-target="#ImagePopup" onclick="ChangePopup('article')">Set Image</button>
                              </div>
                                <a href="javascript:void(0);" rel="article" id="delete_image_id"> <span class="delete_image"  >Del</span></a>
                               <input type="hidden" class="image-group" value="<?php echo set_value('imgArticleImageId'); ?><?php echo $get_article_details['articleimageid']; ?>" name="imgArticleImageId" id="article_image_gallery_id" />    
                               <?php echo form_error('imgArticleImageId'); ?>            
                         </div>
                        <div >
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <div class="right_content">
        
            <div class="attribute_box">
            	<div class="right_header">Attribute
                </div>
        		<div class="right_sub_content">
                  <div class="input_field">
                    <ul>
                    <li><input type="checkbox" name="cbAllowSocial" <?php if(set_value("cbAllowSocial") == 'on'  || $get_article_details['Allowsocialbutton'] == 'A') echo  "checked"; ?> /> Allow Social Button</li>
                    <li><input type="checkbox" name="cbAllowComments" <?php if(set_value("cbAllowComments") == 'on'  || $get_article_details['Allowcomments'] == 'A') echo  "checked"; ?> /> Allow Comments</li>
                    </ul>
                </div>
                <hr/>
                  <div class="input_field">
                    <label>Byline<sup>*</sup></label>
                    <select name="ddByLine" id="byline_id"  value="<?php echo set_value('ddByLine'); ?>"   class="form-control">
                      <?php if(isset($get_byline)) { 
					  foreach($get_byline as $byline) {?>
                      <option value="<?php echo $byline->Author_id; ?>" <?php if(set_value("ddByLine") == $byline->Author_id || $get_article_details['Author_ID'] == $byline->Author_id) echo  "selected='selected'";  ?> ><?php echo ucfirst($byline->AuthorName); ?></option>
                      <?php } } ?>               
                    </select>
                     <?php echo form_error('ddByLine'); ?>
                </div>
                <hr/>
                  <div class="input_field">
                    <label>Country<sup>*</sup></label>
                    <select name="ddCountry" id="country_id" value="<?php echo set_value('ddCountry'); ?>"  class="form-control">
                    <option value="">Select</option>
                     <?php if(isset($get_country)) { 
					  foreach($get_country as $country) {?>
                     <option  <?php if(set_value("ddCountry") == $country->Country_id || $get_article_details['Country_ID'] == $country->Country_id ) echo  "selected";  ?> value="<?php echo $country->Country_id; ?>"><?php echo ucfirst($country->CountryName); ?></option>
                      <?php } } ?>                     
                    </select>
                     <?php echo form_error('ddCountry'); ?>
                </div>
                  <div class="input_field">
                    <label>State<sup>*</sup></label>
                    <select name="ddState" id="state_id" value="<?php echo set_value('ddState'); ?>"  class="form-control" >
                           <option value="">Select</option>
                           <?php if(isset($get_state)) { 
					  foreach($get_state as $state) {?>
                     <option  <?php if(set_value("ddState") == $state->State_Id || $get_article_details['State_ID'] == $state->State_Id ) echo  "selected";  ?> value="<?php echo $state->State_Id; ?>"><?php echo ucfirst($state->StateName); ?></option>
                      <?php } } ?>                 
                    </select>
                    <?php echo form_error('ddState'); ?>
                </div>
                  <div class="input_field">
                    <label>City<sup>*</sup></label>
                    <select name="ddCity" id="city_id" value="<?php echo set_value('ddCity'); ?>" class="form-control" >
                     <option value="">Select</option>   
                     <?php if(isset($get_city)) { 
					  foreach($get_city as $city) {?>
                     <option  <?php if(set_value("ddCity") == $city->City_id || $get_article_details['City_ID'] == $city->City_id ) echo  "selected";  ?> value="<?php echo $city->City_id; ?>"><?php echo ucfirst($city->CityName); ?></option>
                      <?php } } ?>                 
                    </select>
                    <?php echo form_error('ddCity'); ?>
                </div>
                
                </div>
            </div>
            
            <div class="schedule_box">
            	<div class="right_header">Schedule
                </div>
                <div class="right_sub_content">
                  <div class="input_field">
                    <label>Publish Start Date</label>
                 	<div><input type="text" class="form-control" id="publish_start_datetimepicker" readonly="readonly" name="txtPublishStartDate" value="<?php echo set_value('txtPublishStartDate'); ?><?php echo $get_article_details['publishstartdate']; ?>"/>Server Hours : <?php echo date('Y-m-d H:i:s'); ?></div>  
                  </div>
                   <div class="input_field">
                    <label>Publish End Date</label>
                 	<div><input type="text" class="form-control" id="publish_end_datetimepicker" readonly="readonly" name="txtPublishEndDate" value="<?php echo set_value('txtPublishEndDate'); ?><?php echo $get_article_details['Publishenddate']; ?>" /></div>  
                  </div>
                </div>
            </div>
            
            <div class="seo_box">
            	<div class="right_header">SEO
                </div>
                <div class="right_sub_content">
                  <div class="input_field">
                  <label style="font-weight: bold;"> Social Media Tags</label>
                    <ul>
                    <li><input type="checkbox" name="cbOpenGraphTags" <?php if(set_value("cbOpenGraphTags") == 'on'  || $get_article_details['Addto_opengraphtags'] == 'A') echo  "checked"; ?> /> OpenGraph Tags</li>
                    <li><input type="checkbox" name="cbTwitterCards" <?php if(set_value("cbTwitterCards") == 'on'  || $get_article_details['Addto_twittercards'] == 'A') echo  "checked"; ?> /> Twitter Cards</li>
                    <li><input type="checkbox" name="cbSchemaOrg" <?php if(set_value("cbSchemaOrg") == 'on'  || $get_article_details['Addto_schemeorggplus'] == 'A') echo  "checked"; ?> /> Schema Org (G+)</li>
                    </ul>
                </div>
                <hr/>
                
                  <div class="input_field">
                  <label style="font-weight: bold;"> Crawler</label>
                    <ul>
                    <li><input type="checkbox" name="cbNoIndex" <?php if(set_value("cbNoIndex") == 'on'  || $get_article_details['Noindexed'] == 'A') echo  "checked"; ?> /> No Index</li>
                    <li><input type="checkbox" name="cbNoFollows" <?php if(set_value("cbNoFollows") == 'on'  || $get_article_details['Nofollow'] == 'A') echo  "checked"; ?> /> No Follows</li>
                    </ul>
                </div>
                
                 <div class="input_field">
                  <label> Canonical URL</label>
                  <input type="text" class="form-control" name="txtCanonicalUrl"  value="<?php echo set_value('txtCanonicalUrl'); ?><?php echo $get_article_details['Canonicalurl']; ?>" />
                 <div>
                </div>
                
                
                </div>
            </div>
            </div>
            
            <div class="tags_box">
            	<div class="right_header">Tags
                <span class="tag_language">
                <select name="tag_language" value="English"  class="form-control" id="tag_language_id">
                	<option>English</option>
                                <option>Tamil</option>
                </select>
                </span>
                </div>
                 <div class="right_sub_content">
                <div><textarea name="txtTags" id="tags_id" class="form-control" cols="33" rows="7"><?php echo set_value('txtTags'); ?><?php echo $get_article_details['Tags']; ?></textarea></div>
                </div>
            </div>
           
            <div class="remark_box">
            	<div class="right_header">Remark
                </div>
                      <div class="right_sub_content">
                <div><textarea name="txtRemark" class="form-control" cols="33" rows="7"><?php echo set_value('txtRemark'); ?><?php echo $get_article_details['Remarks']; ?></textarea></div>
                </div>
            </div>
          
        </div>
         
    </div>
    <div id="multiple_section_mapping_details" style="display:none;">
   		<div class="mapping_content">
    		<div class="show_the_map">
            <input type="checkbox" name="show_the_map" id="show_the_map_id" />Show the Mapped Section
            </div>
            <div class="section_mapping_content">
                <div class="section_mapping_header">Section & Sub-Section
                </div>
                 <div class="section_mapping_sub_content" id="section_mapping_first">
                 <ul>
                    <?php if(isset($section_mapping)) { 
				 foreach($section_mapping as $mapping) {  ?>
                    <li><input type="checkbox" id="section_mapping" name="cbSectionMapping[]" rel="<?php echo $mapping['Sectionname']; ?>" value="<?php echo $mapping['Section_id']; ?>" <?php if(in_array($mapping['Section_id'],$mapping_section)) echo  "checked"; ?>/>
                     <?php echo $mapping['Sectionname']; ?>
                    </li>
                      <?php if(!(empty($mapping['sub_section']))) { ?>
                      	<ul>
                        	<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
			     <li><input type="checkbox"  id="section_mapping"  name="cbSectionMapping[]" rel="<?php echo $sub_mapping['Sectionname']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>"  <?php if(in_array($sub_mapping['Section_id'],$mapping_section)) echo  "checked"; ?>/>	<?php echo $sub_mapping['Sectionname']; ?>
                  </li>
							<?php } ?>
                            </ul>
					<?php   } ?>
                  <?php } } ?>
                 </ul>
             
                </div>
                
                  <div class="section_mapping_sub_content" id="section_mapping_second" style="display:none;">
             
                </div>
           
      	</div>
    </div>
    </div>
    <div id="related_content_details"  style="display:none;">
      
		<a href="javascript:void(0);" id="internal_search">Internal Search</a> &nbsp; &nbsp; | &nbsp; &nbsp;
         <a href="javascript:void(0);" id="external_link" >External Link</a> &nbsp; &nbsp; | &nbsp; &nbsp;
       <div class="related_content">
            <div class="internal_search_content" id="internal_search_content_id">
				<br/>				<br/>
                <div class="col-md-12">
                <div class="col-md-6">
                  	<div class="col-md-2">
                  	<input type="checkbox" class="form-control" name="search_by_date" id="search_by_date_id"/> 
                    </div>
                    <div class="col-md-10">Search Based On Date Range</div>
					</div>
               
                  <div class="col-md-6" id="search_by_date_content" style="display:none;">
                  <div class="col-md-6">
                            <input type="text" class="form-control" name="checkin" placeholder="From Date" id="checkin_id" readonly="readonly"/>
                   </div>
                   <div class="col-md-6">
                            <input type="text" class="form-control" name="checkout" placeholder="To Date" id="checkout_id" readonly="readonly"/>
                   </div>
                    </div>
               
                </div>
                <br/>
                 <br/>
                  <br/>
                <div class="search_by_type">
                 <div class="col-md-12">
                   <div class="col-md-3">
                    <select id="article_Type" class="form-control">
	                    <option value="">Type</option>
                              <?php if(isset($get_content_type)) {
						  foreach($get_content_type as $type) { ?>
							  <option value="<?php echo $type->contenttype_id; ?>"><?php echo $type->ContentTypeName; ?></option>
						  <?php }
					  }?>
                        
                    </select>
                    </div>
                   <div class="col-md-3">
                    <select id="article_section" class="form-control">
                    <option value="">Section</option>
					<?php if(isset($section_mapping)) { 
                             foreach($section_mapping as $mapping) {  ?>
     <option class="blog_option" value="<?php echo $mapping['Section_id']; ?>"><?php echo $mapping['Sectionname']; ?>
                    <?php if(!(empty($mapping['sub_section']))) { ?>
                              <optgroup>
                              <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
                <option value="<?php echo $sub_mapping['Section_id']; ?>">  <?php echo $sub_mapping['Sectionname']; ?></option>
                                    <?php } ?>
                              </optgroup>
                              <?php   } ?>
                             </option>
                            
                              <?php } } ?>
                    </select>
                    </div>
                       <div class="col-md-3">
                    <input type="text" name="search_text" placeholder="TextBox" class="form-control" id="search_text"/>
                    </div>
                     <div class="col-md-3">
                    <input type="button" name="article_search" value="Search" class="btn btn-primary" id="article_search_id"/>
                     </div>
                </div>
                  <br/>
                <hr/>
                 <br/>

             <table class="table table-hover" id="internal_search_table">
         		<tr><th>Title</th><th>Section</th><th>Priority</th><th>Action</th></tr>
				<?php if(isset($related_article)) { 
			
						foreach($related_article as $related) { ?>
                        <tr id="<?php echo $related->content_id; ?>">
                        <td><?php echo character_limiter(strip_tags($related->Title),20); ?></td>
                        <td><?php echo $related->Sectionname; ?></td>
                        <td>  
                        <select id="internal_priority_<?php echo $related->content_id; ?>" name="internal_priority" class="form-control">
					<?php for($count =1; $count <= 100; $count++) { ?>
                    <option value="<?php echo $count; ?>"><?php echo $count; ?></option>
                    <?php } ?>
                		</select></td>
                        <td>
                       <input type="checkbox" title="<?php echo strip_tags($related->Title); ?>" value="<?php echo $related->content_id; ?>" rel="article" name="internal_action" id="internal_action"/>
                        </td>
              </tr>
                <?php } } ?>
            </table>
                <input type="button" name="internal_add_to_list" id="internal_add_to_list" class="btn btn-primary" value="Add To List"/>
            </div>
             </div>
     
            <div class="external_link_content" id="external_link_content_id" style="display:none;">

            	<div class="row">
                <div class="col-md-3">
                	Title<sup>*</sup>
                </div>
                <div class="col-md-9">
                <input type="text" name="external_title" id="external_title_id" class="form-control" />
                <p class="error" id="external_title_error"></p>
                </div>
                <div class="row">
                <div class="col-md-3">
                	Description<sup>*</sup>
                </div>
                <div class="col-md-9">
       <textarea name="external_description" id="external_description_id" class="form-control" cols="50" rows="10"></textarea>
                <p class="error" id="external_description_error"></p>
                </div>
                <div class="row">
                <div class="col-md-3">
                	URL<sup>*</sup>
                </div>
                <div class="col-md-9">
                <input type="text" name="external_url" id="external_url_id" class="form-control" />
                <p class="error" id="external_url_error"></p>
                </div>
                </div>
                <div class="row">
                <div class="col-md-3">
                	Priority
                </div>
                <div class="col-md-9">
                <select id="external_priority_id" name="external_priority" class="form-control">
					<?php for($count =1; $count <= 100; $count++) { ?>
                    <option value="<?php echo $count; ?>"><?php echo $count; ?></option>
                    <?php } ?>
                </select>
                </div>
                 </div>
                <div class="row">
                 <div class="col-md-9">
                </div>
                <div class="col-md-3">
                	<input type="button" name="add_to_list" id="add_to_list_id" class="btn btn-primary" value="Add To List"/>
                </div>
                </div>
                   </div>
            </div>
          
       </div>
       <span id="priority_error" style="color:red;"></span>
          <div class="link_preview">
       	<h5>	Link Preview</h5>
           <table class="table table-hover" id="link_preview_table">
         		<tr><th>Title</th><th>Type</th><th>Priority</th><th>Action</th></tr>
            </table>
       </div>
      
   </div>
   </div>
   
  
  
   </div>
		<input type="hidden" name="hide_external_link" id="hide_external_link_id" value="" />
   
    </form>
   
    </div>
<input type="hidden" id="current_image_popup" value="" />

 <div  id="ImagePopup"  class="modal fade bs-example-modal-lg" style="display:none;" tabindex="-1" role="dialog"  aria-hidden="true" >
        <div class="modal-dialog modal-lg">
    <div class="modal-content">
             <div id="imagepopup_content">
               <div class="modal-header">
        <a href="javascript:void(0);" id="image_upload_popup">Upload</a> &nbsp; &nbsp; |
        <a href="javascript:void(0);" id="browse_upload_popup">Browse</a>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        <div class="imagepopup_subcontent">
        
            <div id="browser_content_id" class="browser_content">
            
                <div class="browser_content_left">
                
                    <div class="browser_content_left_header">
                        <h3> Pick the item to insert</h3>
                            <div class="browser_content_search">
                                <div class="browser_input_fields">
                                    <select id="search_months"  class="form-control" style="width:30%" >
                                            <option value=''>--All Month--</option>
                                            <option value='1'>Janaury</option>
                                            <option value='2'>February</option>
                                            <option value='3'>March</option>
                                            <option value='4'>April</option>
                                            <option value='5'>May</option>
                                            <option value='6'>June</option>
                                            <option value='7'>July</option>
                                            <option value='8'>August</option>
                                            <option value='9'>September</option>
                                            <option value='10'>October</option>
                                            <option value='11'>November</option>
                                            <option value='12'>December</option>
                                    </select>
                                </div>
                                <div class="browser_input_fields">
                         <input type="text" value="" placeholder="Search" id="search_caption" class="form-control" style="width:30%" name="browser_search" />	 </div>
                            </div>
                            </div>
                            <ul class="image_lists" id="image_lists_id">
                            <?php if(isset($image_library)) { 
							$count = 0;
							foreach($image_library as $image) {
								if($count==0) {
									$first_image_caption 	= $image->Title;
									$first_image_alt 		= $image->ImageAlt;
									$first_image_height		= $image->Height." X ".$image->Width;
									$first_image_size 		= $image->image_size." Kb";
									$first_image_date 		= $image->Modifiedon;
									$first_image_url 		= $image->ImageBinaryData;
									$first_image_id 		= $image->content_id;
								}
							?>
                               <li id="image_lists_id" rel="<?php echo $image->content_id; ?>"><a href="javascript:void(0);"   id="image_lists_images_id" rel="<?php echo $image->content_id; ?>" image_height="<?php echo $image->Height; ?>" image_width="<?php echo $image->Width; ?>" image_caption="<?php echo $image->Title; ?>" image_alt="<?php echo $image->ImageAlt; ?>" image_size="<?php echo $image->image_size; ?>" image_date="<?php echo $image->Modifiedon; ?>"><img  height="200" width="150" class="image_lists_images" src="<?php echo $image->ImageBinaryData; ?>"   /></a></li>
                               <?php  ++$count; } } ?>
                      	  </ul>
                      </div>
             
            
                <div class="browser_content_right">
                 <?php if(isset($image_library)) {  ?>
                	<div class="image_details">
                    <h5>Thumbnail Details</h5>
                    <?php if(isset($first_image_url)) { ?>
                    	<img id="image_path" src="<?php echo $first_image_url; ?>" height='150' width='200'  />
                        
                     	<h5 id="image_name"><?php echo $first_image_caption; ?></h5>
                     	<p id="height_width"><?php echo $first_image_height; ?></p>
                    	<p id="image_size"><?php echo $first_image_size; ?></p>
                  		<p id="image_date"><?php echo $first_image_date; ?></p>
                  	<input type="hidden" value="<?php echo $first_image_id; ?>" id="browse_image_id" name="browse_image_id" />
                      
                        <h5> Image Alt : </h5>
                        <p id="textarea_alt"><?php echo $first_image_alt; ?></p>
                        <h5> Caption : </h5>
                        <p id="textarea_caption"><?php echo $first_image_caption; ?></p>
                        <?php } ?>
                      </div>
                      
                      <?php } ?>
                </div>
                <button type="button" id="browse_image_insert" style="float:right;" class="btn btn-primary">Insert</button>
           </div>
             </div>
            <div class="image_upload_content" id="image_upload_content_id"  style="display:none;">
        		 <div class="browser_content_search">
                                <div class="browser_input_fields">
                                <label> Alt Tag<sup>*</sup> </label>
                             <input type="text" value="" id="alt_tag" name="alt_tag" class="form-control" style="width:30%" />			 </div>
                                <div class="browser_input_fields">
                                 <label> Caption<sup>*</sup> </label>
                             <input type="text" value="" id="caption" name="caption" class="form-control" style="width:30%" />
                                </div>
                                
                                 <div class="upload_content">
                             <div class="choose_file" style="height: 30px;">
                          		+ &nbsp; Add File
 								<input type="file" name="imagelibrary" class="btn btn-primary" id="imagelibrary" />
                            </div>
                                <div id="drop-area"><p id="drop-text_id" class="drop-text" style="padding: 85px 2px 2px 80px;">Drop files anywhere here to upload or click on the "Add File..." button above</p></div>
                            </div>
            </div>
        
        
        </div>

 </div>
  </div>
</div>
</div>
     
<script type="text/javascript" src="<?php echo $this->config->item('base_url_js'); ?>jquery-1.10.2.min.js"></script>
<script src="<?php echo $this->config->item('base_url_js'); ?>bootstrap.js"></script>
  
<script type="text/javascript" src="<?php echo base_url(); ?>includes/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url_js');?>jquery.validate.min.js"></script>
<script src="<?php echo $this->config->item('base_url_js');?>additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url_js');?>jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url_js');?>json2.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url_js');?>article.js"></script>

<script type="text/javascript">

$('#publish_start_datetimepicker').datetimepicker({
format:'Y-m-d H:i:s',
minDate: '<?php echo date('Y-m-d H:i:s'); ?>'
});

$("#checkin_id").datetimepicker({
format:'Y-m-d H:i:s'
});


$('#publish_end_datetimepicker').datetimepicker({
format:'Y-m-d H:i:s',
 onShow:function( ct ){
   this.setOptions({
    minDate:$('#publish_start_datetimepicker').val()?$('#publish_start_datetimepicker').val():false,
	StartDate:$('#publish_start_datetimepicker').val()?$('#publish_start_datetimepicker').val():false,
   })
 }
});

$('#checkout_id').datetimepicker({
format:'Y-m-d H:i:s'
});

$('#ImagePopup').on('shown', function(){
    $('body').css('overflow', 'hidden');
}).on('hidden', function(){
    $('body').css('overflow', 'auto');
})

	  
function textCounter(field,field2,maxlimit) {
 var countfield = document.getElementById(field2);
 if ( field.value.length > maxlimit ) {
  field.value = field.value.substring( 0, maxlimit );
  return false;
 } else {
  $("#"+field2).html(maxlimit - field.value.length);
 }
}

$(document).ready(function() {
	
<?php if($this->session->flashdata('msg')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});
<?php } 

 if(isset($get_related_article)) { 

				foreach($get_related_article as $related) { 
					if($related['Type'] == 'E') { ?>
					
				var sub_array = [];
				sub_array[0] = 'E';
				sub_array[1] = "<?php echo character_limiter(strip_tags($related['ExternalArticletitle']),20); ?>";
				sub_array[2] = "<?php echo $related['ExternalArticleDescription']; ?>";
				sub_array[3] = "<?php echo $related['ExternalArticleURL']; ?>";
				sub_array[4] = <?php echo $related['DisplayPriorty']; ?>;
				sub_array[5] = <?php echo $related['RelatedArticleId']; ?>;
			
				external_array.push(sub_array);
	<?php 			} else { 
			if(isset($related['internal_article'][0]['contenttype_id'])) { ?>
						
				var sub_array = [];
				sub_array[0] = 'I';
				sub_array[1] =  <?php echo $related['internal_article'][0]['contenttype_id']; ?>;
				sub_array[2] =  <?php echo $related['internal_article'][0]['content_id']; ?>;
				sub_array[3] = "<?php echo character_limiter(strip_tags($related['internal_article'][0]['Title']),20); ?>";
				sub_array[4] = <?php echo $related['DisplayPriorty']; ?>;
				sub_array[5] = <?php echo $related['RelatedArticleId']; ?>;
		
				external_array.push(sub_array);
	
<?php	} }
				} 
		} ?>
		
		console.log(external_array);

fill_external_link_preview();

$(document.body).on('click', '#delete_image_id' ,function(){
		var ImageType = $(this).attr('rel');
		if(ImageType == 'article') {
			$('#article_uploaded_image').html('Image not set');
			$("#article_image_gallery_id").val('');
		} else if(ImageType == 'section'){
			$('#section_uploaded_image').html('Image not set');
				$("#section_image_gallery_id").val('');
		} else {
			$('#home_uploaded_image').html('Image not set');
			$("#home_image_gallery_id").val('');
		}
	});
	
	$("#country_id").change(function() {
		if($("#country_id").val() != '') {
			var postdata = "country_id="+$("#country_id").val();
			$.ajax({
			url: base_url+"niecpan/common/get_state", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			 dataType: "json",
			success: function(data)   // A function to be called if request succeeds
			{
				var OptionValue = '<option value="">Select</option>'
				$.each(data, function(i, item) {
    			OptionValue += '<option value='+ data[i].State_Id +'>'+ data[i].StateName +'</option>';
				});
				$("#state_id").html(OptionValue);
				$("#city_id").html('<option value="">Select</option>');
				
	}
		});
	  }
	});
	
		$("#state_id").change(function() {
		if($("#country_id").val() != '' && $("#state_id").val() != '' ) {
			var postdata = "country_id="+$("#country_id").val()+"&state_id="+$("#state_id").val();
			$.ajax({
			url: base_url+"niecpan/common/get_city", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			 dataType: "json",
			success: function(data)   // A function to be called if request succeeds
			{
				var OptionValue = '<option value="">Select</option>'
				$.each(data, function(i, item) {
    			OptionValue += '<option value='+ data[i].City_id +'>'+ data[i].CityName	+'</option>';
				});
				$("#city_id").html(OptionValue);
				
	}
		});
	  }
	});

		$("#publish_id").click(function() {
		CKEDITOR.instances.summary.updateElement();
		CKEDITOR.instances.body_text.updateElement();
		CKEDITOR.instances.article_head_line_id.updateElement();
		$("#status_id").val('P');
		if($("#content_form").valid()) {
		$("#content_form").submit();
		}
	});
	
		$("#send_draft_id").click(function() {
		CKEDITOR.instances.summary.updateElement();
		CKEDITOR.instances.body_text.updateElement();
		CKEDITOR.instances.article_head_line_id.updateElement();
		$("#status_id").val('D');
		$("#content_form").submit();
	});
	
	
		$("#send_approvel_id").click(function() {
		CKEDITOR.instances.summary.updateElement();
		CKEDITOR.instances.body_text.updateElement();
		CKEDITOR.instances.article_head_line_id.updateElement();
		$("#status_id").val('R');
		$("#content_form").submit();
	});
	
	$("#browse_upload_popup").click(function() {
		$("#browser_content_id").show();
		$("#image_upload_content_id").hide();
	});
	
	$("#image_upload_popup").click(function() {
		$("#browser_content_id").hide();
		$("#image_upload_content_id").show();
	});

$("#drop-area").on('dragenter', function (e){
	e.preventDefault();
	$(this).css('background', '#BBD5B8');
	});

	$("#drop-area").on('dragover', function (e){
	e.preventDefault();
	});

	$("#drop-area").on('drop', function (e){
	$(this).css('background', '#D8F9D3');
	e.preventDefault();
	var image = e.originalEvent.dataTransfer.files;
	createFormData(image);
	});
	
	$("#imagelibrary").change(function() {
			
		var ext = $('#imagelibrary').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			alert('Invalid Extension!');
		} else {
			var formImage = new FormData();
			formImage.append('userImage',document.getElementById("imagelibrary").files[0]);
			formImage.append('alt_tag',$("#alt_tag").val());
			formImage.append('caption',$("#caption").val());
			formImage.append('main_section',$("#main_section_id").val());
			formImage.append('country',$("#country_id").val());
			formImage.append('state',$("#state_id").val());
			formImage.append('city',$("#city_id").val());
			formImage.append('tags',$("#tags_id").val());
			formImage.append('author',$("#byline_id").val());
			uploadFormData(formImage);
		}
	});
	
	$(document.body).on('click', '#image_lists_images_id', function(event) {
			$("#textarea_alt").text($(this).attr('image_alt'));
			$("#textarea_caption").text($(this).attr('image_caption'));
			$("#image_name").html($(this).attr('image_caption'));
			$("#height_width").html($(this).attr('image_height')+" X "+$(this).attr('image_width'));
			$("#image_size").html($(this).attr('image_size')+" Kb");
			$("#image_date").html($(this).attr('image_date'));
			$("#image_path").attr('src',$(this).children('img').attr('src'));
			$("#browse_image_id").val($(this).attr('rel'));
			
	});
	
	$("#browse_image_insert").click(function() {
		
		if($("#browse_image_id").val() != '' && $("#browse_image_id").val() != 0 ) {
			
			var image_id = $("#browse_image_id").val();
		
				if($("#current_image_popup").val() == 'home') {
				$('#home_image_gallery_id').val(image_id);
				$("#home_uploaded_image").html('Image Set');
				} else if($("#current_image_popup").val() == 'article')  {
				$('#article_image_gallery_id').val(image_id);
				$("#article_uploaded_image").html('Image Set');
				} else {
				$('#section_image_gallery_id').val(image_id);
				$("#section_uploaded_image").html('Image Set');
				}
				 $('#ImagePopup').modal('hide');
		}
		
	});
	
$("#show_the_map_id").change(function(event) {
		
		  if(this.checked) {
				 var Content ='<ul>';
			$("#section_mapping_second").empty();
		  	$("input:checkbox[name='cbSectionMapping[]']:checked").each(function() {
				Content +='<li>'+$(this).attr('rel')+'</li>';
			});
				Content +='</ul>';
					
				$("#section_mapping_first").hide();
				$("#section_mapping_second").html(Content);
				$("#section_mapping_second").show();
		  } else {
				$("#section_mapping_first").show();
				$("#section_mapping_second").hide();
		  }

	});
	
	$("#search_by_date_id").change(function(event){
		if(this.checked) 
		{
			$("#search_by_date_content").show();
			$("#checkin_id").val('');
			$("#checkout_id").val('');
		}
		else
		{
			$("#search_by_date_content").hide();
		}
	});
	
	$("#search_months").change(function() {
		
			var Caption = $("#search_caption").val();
			var Month	= $("#search_months").val();

			postdata = "Caption="+Caption+"&Month="+Month;
			$.ajax({
				url: "<?php echo base_url(); ?>niecpan/article_image/search_image_library",
				type: "POST",
				data: postdata,
				dataType: "json",
				success: function(data){
					var Content = '';
					var Count 	= 0;
					console.log(data);
					$.each(data, function(i, item) {
						if(Count == 0) {
								
							$("#textarea_alt").text(item.ImageAlt);
							$("#textarea_caption").text(item.Title);
							$("#image_name").html(item.Title);
							$("#height_width").html(item.Height+" X "+item.Width);
							$("#image_size").html(item.image_size+" Kb");
							$("#image_date").html(item.Modifiedon);
							$("#image_path").attr('src',item.ImageBinaryData);
							$("#browse_image_id").val(item.content_id);
										
						}
						
						Content +='<li id="image_lists_id" rel="'+item.content_id+'"><a href="javascript:void(0);"   id="image_lists_images_id" rel="'+item.content_id+'" image_height="'+item.Height+'" image_width="'+item.Width+'" image_caption="'+item.Title+'" image_alt="'+item.ImageAlt+'" image_size="'+item.image_size+'" image_date="'+item.Modifiedon+'>"><img  height="200" width="150" class="image_lists_images" src="'+item.ImageBinaryData+'"   /></a></li>';
						Count++;
					});
					$("#image_lists_id").html(Content);
				}
			});
		
	});
	
	$("#search_caption").keyup(function(e){
	    if(e.keyCode == 13){
			var Caption = $("#search_caption").val();
			var Month	= $("#search_months").val();
			console.log(Month);
			postdata = "Caption="+Caption+"&Month="+Month;
			$.ajax({
				url: "<?php echo base_url(); ?>niecpan/article_image/search_image_library",
				type: "POST",
				data: postdata,
				dataType: "json",
				success: function(data){
					var Content = '';
					var Count 	= 0;
					$.each(data, function(i, item) {
						if(Count == 0) {
								
							$("#textarea_alt").text(item.ImageAlt);
							$("#textarea_caption").text(item.Caption);
							$("#image_name").html(item.Caption);
							$("#height_width").html(item.Height+" X "+item.Width);
							$("#image_size").html(item.image_size+" Kb");
							$("#image_date").html(item.Modifiedon);
							$("#image_path").attr('src',item.ImageBinaryData);
							$("#browse_image_id").val(item.Image_id);
										
						}
						
						Content +='<li id="image_lists_id" rel="'+item.Image_id+'"><a href="javascript:void(0);"   id="image_lists_images_id" rel="'+item.Image_id+'" image_height="'+item.Height+'" image_width="'+item.Width+'" image_caption="'+item.Caption+'" image_alt="'+item.ImageAlt+'" image_size="'+item.image_size+'" image_date="'+item.Modifiedon+'>"><img  height="200" width="150" class="image_lists_images" src="'+item.ImageBinaryData+'"   /></a></li>';
						Count++;
					});
					$("#image_lists_id").html(Content);
				}
			});
		  }
		});
		
		$("#internal_search").click(function() {
			$("#internal_search_content_id").show();
						$("#external_link_content_id").hide();
		});
		
		$("#external_link").click(function() {
				$("#internal_search_content_id").hide();
						$("#external_link_content_id").show();
		});
		
		$("#internal_add_to_list").click(function() {
			var priority_bool = true;
			$("#priority_error").empty();
		$("input:checkbox[name='internal_action']:checked").each(function() {

			if(CheckPriority($("#internal_priority_"+$(this).val()).val())) {
			
  				var sub_array = [];
				sub_array[0] = 'I';
				sub_array[1] = $(this).attr('rel');
				sub_array[2] = $(this).val();
				sub_array[3] = $(this).attr('title');
				sub_array[4] = $("#internal_priority_"+sub_array[2]).val();
				sub_array[5] = '';	
				external_array.push(sub_array);
				
			} else {
				priority_bool = false;
			}
			
			$(this).prop('checked', false);
			
	   	});	
			
			if(priority_bool == false ) {
				$("#priority_error").html("Already existing priority value");
			}
					
				$("#link_preview_table").html('<tr><th>Title</th><th>Type</th><th>Priority</th><th>Action</th></tr>');
				fill_external_link_preview();
	});
		
		$("#add_to_list_id").click(function() {
			var Bool = true;
			
			$("#external_title_error").empty();
			$("#external_description_error").empty();
			$("#external_url_error").empty();
			
			if($.trim($("#external_title_id").val()) == '') {
				Bool = false;
				$("#external_title_error").html("Title is Required");
			
			}
			if($.trim($("#external_description_id").val()) == '') {
				Bool = false;
				$("#external_description_error").html("Description is Required");
			}
			if($.trim($("#external_url_id").val()) == '') {
				Bool = false;
				$("#external_url_error").html("URL is Required");
			}
			
			if(Bool == true){
				console.log("Ok");
				$("#link_preview_table").empty();
				
				if(CheckPriority($("#external_priority_id").val())) {
				$("#priority_error").empty();
				var sub_array = [];
				sub_array[0] = 'E';
				sub_array[1] = $("#external_title_id").val();
				sub_array[2] = $("#external_description_id").val();
				sub_array[3] = $("#external_url_id").val();
				sub_array[4] = $("#external_priority_id").val();
				sub_array[5] = '';
			

				external_array.push(sub_array);
				
				$("#external_title_id").val('');
				$("#external_description_id").val('');
				$("#external_url_id").val('');
				$("#external_priority_id").val(1);
				
				} else {
					
					$("#priority_error").html("Already existing priority value");
					
				}
			
				$("#link_preview_table").html('<tr><th>Title</th><th>Type</th><th>Priority</th><th>Action</th></tr>');
				
				fill_external_link_preview();
				
			} else {
				console.log("Failure");
			}
			
		});
		
		$("#article_search_id").click(function() {
	
	var Type 		= $("#article_Type").val();
	var Section 	= $("#article_section").val();
	var search_text = $("#search_text").val();
	var check_in	= $("#checkin_id").val();
	var check_out   = $("#checkout_id").val();
	
	var postdata = "type="+Type+"&section="+Section+"&search_text="+search_text+"&check_in="+check_in+"&check_out="+check_out;
	
		$.ajax({
			url: base_url+"niecpan/article/search_internal_article", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			dataType: "json",
			success: function(data)   // A function to be called if request succeeds
				{
					if(data != '') {
				$("#internal_search_table").html('<tr><th>Title</th><th>Section</th><th>Priority</th><th>Action</th></tr>');
				
				$.each(data, function(i, item) {
					var Content = '';
					
			 Content +='<tr id="'+item.content_id+'"><td>'+item.Title+'</td><td>'+item.Sectionname+'</td><td>';
			 Content +='<select id="internal_priority_'+item.content_id+'" name="internal_priority" class="form-control">';
					for(Count =1; Count <= 100; Count++) { 
                     Content +='<option value="'+Count+'">'+Count+'</option>';
                     } 
                		Content +='</select></td>';
                        Content +='<td><input type="checkbox" title="'+item.Title+'" value="'+item.content_id+'" rel="article" name="internal_action" id="internal_action"/> </td></tr>';	
						
					
							$("#internal_search_table").append(Content);
						});
					} else {
						$("#internal_search_table").html("No Data");
					}
						
				}
		});
			
			});
		
	});
	
function external_action(Index) {
	
	external_array.splice(Index,1);
	$("#link_preview_table").html('<tr><th>Title</th><th>Type</th><th>Priority</th><th>Action</th></tr>');
	fill_external_link_preview();
}

function CheckPriority(priority) {
	var bool = true;
	for(Count = 0; Count < external_array.length ; Count++) {
		console.log(external_array[Count][4]);
		console.log(priority);
		if(external_array[Count][4]  == priority) {
			bool = false;
		} 	
	}
	return bool;
}

function fill_external_link_preview() {
	
	console.log(external_array);
	

	for(Count = 0; Count < external_array.length ; Count++) {
		var Content = '';
		console.log(external_array[Count][0]);
		if(external_array[Count][0] == 'E') {
	var	Content = "<tr id='external_data"+Count+"'><td>"+external_array[Count][1]+"</td><td>External</td><td>"+external_array[Count][4]+"</td><td><a href='javascript:void(0)' onclick='external_action("+Count+")' id='external_action' rel="+Count+">OFF</a></td>";
	}
	
	if(external_array[Count][0] == 'I') {
	var	Content = "<tr id='external_data"+Count+"'><td>"+external_array[Count][3]+"</td><td>Internal</td><td>"+external_array[Count][4]+"</td><td><a href='javascript:void(0)' onclick='external_action("+Count+")' id='external_action' rel="+Count+">OFF</a></td>";
	}
	
	$("#link_preview_table").append(Content);
	
	}
	$("#hide_external_link_id").val(JSON.stringify(external_array));
	}

function createFormData(image) {

	var formImage = new FormData();
	formImage.append('userImage', image[0]);
	formImage.append('alt_tag',$("#alt_tag").val());
	formImage.append('caption',$("#caption").val());
	formImage.append('main_section',$("#main_section_id").val());
	formImage.append('country',$("#country_id").val());
	formImage.append('state',$("#state_id").val());
	formImage.append('city',$("#city_id").val());
	formImage.append('tags',$("#tags_id").val());
	formImage.append('author',$("#byline_id").val());
	uploadFormData(formImage);
}

function uploadFormData(formData) {

	if($.trim($("#alt_tag").val()) != '' &&  $.trim($("#caption").val()) != '' && $("#country_id").val() != '' && $("#state_id").val() != '' && $("#city_id").val() != '') {
	
		$.ajax({
			url: "<?php echo base_url(); ?>niecpan/article_image/image_upload",
			type: "POST",
			data: formData,
			contentType:false,
			cache: false,
			processData: false,
			dataType: "json",
			success: function(data){

				$('#drop-text_id').html('Drop files anywhere here to upload or click on the "Add File..." button above <br/>');
				
				if(data.status == 1)
				$('#drop-text_id').append('<span style="color:blue;">'+data.message+'</span>');
				else 
				$('#drop-text_id').append('<span style="color:red;">'+data.message+'</span>');
				
				if($("#current_image_popup").val() == 'home') {
				$('#home_image_gallery_id').val(data.image_id);
				$("#home_uploaded_image").html('Image Set');
				} else if($("#current_image_popup").val() == 'article')  {
				$('#article_image_gallery_id').val(data.image_id);
				$("#article_uploaded_image").html('Image Set');
				} else {
				$('#section_image_gallery_id').val(data.image_id);
				$("#section_uploaded_image").html('Image Set');
				}
				
				
				$("#alt_tag").val('');
				$("#caption").val('');
				
				if(data.status == 1)
				$('#ImagePopup').modal('hide');
			}
		});
	} else {
		$('#drop-text_id').html('Drop files anywhere here to upload or click on the "Add File..." button above <br/>');
		$('#drop-text_id').append('<span style="color:red;">Please Fill the all Fields</span>');
	}
}

function ChangePopup(popup_name) {
	$("#current_image_popup").val(popup_name);
}
</script>
<script type="text/javascript">
    CKEDITOR.replace( 'article_head_line_id',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','BGColor', 'Strike', 'Subscript', 'Superscript' ] } ]
    });
    CKEDITOR.replace( 'summary', {
    });
	CKEDITOR.replace( 'body_text', {
    });
</script>
</body>
</html>