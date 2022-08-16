<?php $section_segment = $page_name = $this->uri->segment('2'); 

if($page_name == "edit_archive_article")
	$page_name ="edit_article";

?>

<script type="text/javascript"> var base_url = '<?php	 echo site_url(); ?>' 
var external_array = [];
var page_Indexid = 1;
<?php if($page_name == 'edit_article') { ?>
var get_content_id = "<?php echo $get_article_details['content_id']; ?>";
<?php } else { ?>
var get_content_id = '';
<?php } ?>
</script>

<style type="text/css">
.remodal-overlay{ background-color:#000 !important;}
</style>
<span class="css_and_js_files">
<link href="<?php echo image_url; ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
<link href="<?php echo image_url ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />	
<link href="<?php echo image_url ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	


<link href="<?php echo image_url ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" id="contents_css" />
<link href="<?php echo image_url ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />

<link href="<?php echo image_url ?>css/admin/jquery-ui-autocomplete.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="<?php echo image_url ?>css/admin/jquery-ui-custom.css">
<link href="<?php echo image_url ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
</span>
<span class="previewcontainer">

</span>
<style>
.tabcontents sup {
	color:red;
}
.error {
	color:red;
	padding: 5px;
}
span.cke_widget_wrapper.cke_image_nocaption{
 vertical-align: text-top;
    float: left;
    margin: 10px 10px 10px 0;
}
.cke_widget_wrapper.cke_widget_block[data-cke-display-name=image] {
    float: left;
}
.cke_widget_wrapper.cke_widget_block[data-cke-display-name=image] figure {
    border: none;
    margin: 0 10px 10px 0;
    padding: 0;
}
.cke_widget_wrapper.cke_widget_block[data-cke-display-name=image] figure figcaption
    background-color: #000;
    color: #fff;
}
</style>

<div class="Container">
<div class="BodyWhiteBG">

<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<div class="breadcrumbs"><a href="javascript:void(0);">Dashboard</a> > <a href="javascript:void(0);"><?php echo $title; ?></a></div>
 <h2 class="FloatLeft"><?php echo $title; ?></h2>
 
</div>

<div class="FloatLeft SessionSuccess" id="flash_msg_id" style="display:none;top: 145px;">
<?php if($this->session->flashdata('msg')){ 
echo $this->session->flashdata('msg');
} ?></div>

<p class="FloatRight save-back save_margin article_save">

<?php
	$schedule_status = '0';
	
 if(isset($get_article_details['status'])) {
	$status 		= $get_article_details['status'];
	$get_content_id = $get_article_details['content_id'];
		
	$publishdate  	= "";
	$unpublishdate  = "";
	
	switch($status )
	{
		case "P":
			$print_status = "Published";
			$publishdate  = date('d-m-Y H:i:s',strtotime($get_article_details['publish_start_date']));
			break;
		case "U":
			$print_status = "Unpublished";
			$unpublishdate  = date('d-m-Y H:i:s',strtotime($get_article_details['Modifiedon']));
			break;
		case "D":
			$print_status = "Draft";
			break;
		default:
			$print_status = "None";
	}

if($status == 'P' && $get_article_details['publish_start_date'] == '0000-00-00 00:00:00' ) {
		$schedule_status = '0';
} else {

	if($status != 'D' && $status != 'A' && $status != 'S' && $status != 'U') {
		if( strtotime($get_article_details['publish_start_date']) <=  strtotime(date('d-m-Y H:i:s'))) {
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

<a class="back-top FloatLeft" href="<?php echo base_url().folder_name; ?>/article_manager" title="Go Back"><i class="fa fa-reply fa-2x"></i></a>
<?php if($page_name == 'edit_article') { ?>
<?php if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { ?>
<button class="btn btn-primary FloatRight i_button FlagIcon2" id="unpublishtop_id"  title="Unpublish" <?php if( isset($status) && ( $status == "D") ){ ?> style="display:none" <?php } ?>></button>
<?php }  } ?>
<?php if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) { ?>
<button class="btn btn-primary FloatRight i_button"  id="publishtop_id" title="Publish" ><i class="fa fa fa-flag"></i></button>
<?php } ?>
<?php if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) { ?>
<button class="btn btn-primary FloatRight i_button"  id="publishnotclosetop_id" title="Publish not closed" ><i class="fa fa fa-flag-checkered"></i></button>
<?php } ?>

<button class="btn btn-primary FloatRight i_button"  id="send_drafttop_id" title="Draft"  <?php if( isset($status) && ($status == "P" or $status == "U")){ ?> style="display:none" <?php } ?> ><div class="DraftIcon"></div>
</button>


<a class="back-top FloatRight top_iborder" href="#" data-remodal-target="preview_article_popup" title="Preview" id="preview_id" ><i class="fa fa-desktop i_extra"></i>

</a>


</p>


<?php if(isset($print_status) && $print_status != "")  { ?>
 
 <span class="TopStatus" id="TopStatusId"><?php echo "Status :". $print_status; ?></span>
 
 <?php } ?>

</div>


<?php if($section_segment  == 'edit_archive_article') { ?>

<form action="<?php echo base_url().folder_name; ?>/article_manager/update_archive_article/<?php echo @$archive_year; ?>/<?php echo @$get_article_details['content_id']; ?>" method="post" id="content_form" name="content_form" enctype="multipart/form-data">

<?php } else { ?>

<form <?php if($page_name == 'article') { ?> action="<?php echo base_url().folder_name; ?>/article/create_article" <?php } else {?> action="<?php echo base_url().folder_name; ?>/article_manager/update_article/<?php echo @$get_article_details['content_id']; ?>" <?php } ?>  method="post" id="content_form" name="content_form" enctype="multipart/form-data">

<?php } ?>

<input type="hidden" name="txtStatus" id="status_id" value="D" />
<input type="hidden" name="publish_close" id="publish_close" value="" />
<input type="hidden" name="schedule_article" value="<?php if(isset($get_article_details['scheduled_article'])) { echo $get_article_details['scheduled_article']; } else { echo "1"; } ?>" />
<input type="hidden" name="txtOldStatus" id="old_status_id" value="<?php if(isset($status)) echo $status; ?>" />
<input type="hidden" name="txtContentId" id="content_id" value="<?php if(isset($get_article_details['content_id'])) echo $get_article_details['content_id']; ?>" />
<input type="hidden" name="archive_year" id="archive_year" value="<?php echo @$archive_year; ?>" />
<input type="hidden" name="schedule_status" old_date="<?php echo  strtotime(date('d-m-Y H:i:s',strtotime(@$get_article_details['publish_start_date']))); ?>" new_date="<?php echo strtotime(date('d-m-Y H:i:s')); ?>" value="<?php echo $schedule_status; ?>" />


<div class="Overflow DropDownWrapper" id="main_content">

<?php 

if(isset($get_article_details['draft_on']) && $get_article_details['draft_on'] != '0000-00-00 00:00:00' && $get_article_details['draft_on'] != '') 
$draft_word = " & Drafted";
else 
$draft_word = "";	
	



if($page_name == 'edit_article' && $section_segment  != 'edit_archive_article') { ?>
  <p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($get_article_details['Createdby'])).' | '.'Created'.$draft_word.' On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($get_article_details['Modifiedby'])).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Modifiedon'])); ?></p>
<?php } elseif($section_segment  == 'edit_archive_article') { ?>
	<p><?php echo 'Created By : '.ucfirst(($get_article_details['Createdby'])).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(($get_article_details['Modifiedby'])).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_article_details['Modifiedon'])); ?></p>
<?php } ?>
  
<ul  class="tabs Article-Tab">
            <li class="selected" id="content_div"><a href="#view1">Content</a></li>
            <li class="" id="section_div"><a id="section_href" href="#view2">Multi Section Mapping</a></li>
            <li class="" id="related_div"><a id="related_href" href="#view3">Related Content</a></li>
        </ul>

 <section class="tap-main Article-Tab">
        
        <div class="tabcontents padding-right-0 article_tab_contents">
            <div id="view1" style="display: block;">
    		<div class="article_content1">
            <div class="article_url">
			
				<label  class="WidthAuto margin-top-0">Article Page Headline<sup>*</sup></label> 
					<!--<div id="cke_charcount_article_head_line_id" length="100" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_article_details['Title'])) echo mb_strlen($get_article_details['Title']); else echo "100"?></div> -->
<textarea class="ckeditor"  id="article_head_line_id" name="txtArticleHeadLine"><?php echo set_value('txtArticleHeadLine'); ?><?php  if(isset($get_article_details['title'])) echo $get_article_details['title']; ?></textarea>
			
                     <?php echo form_error('txtArticleHeadLine'); ?>
					 <span id="article_head_error"></span>
		
            <label>URL Title</label>
		
            <input type="text" class="box-shad1 full-width" id="txtUrlTitle" name="txtUrlTitle"  	<?php if(isset($get_article_details['url_title'])) { echo "readonly"; } ?>  value="<?php if(isset($get_article_details['url_title'])) { echo htmlentities($get_article_details['url_title']); } echo set_value('txtUrlTitle'); ?>" />
             <?php echo form_error('txtUrlTitle'); ?>
			 
			 <?php if(isset($get_article_details['url'])) { ?>
			 <label>URL Structure : <a href="<?php  echo BASEURL.$get_article_details['url']; ?>"><?php  echo BASEURL.$get_article_details['url']; ?></a></label>
			 <?php } ?>
				
            <label>Main Section<sup>*</sup></label>
            <div class="w2ui-field">

  <select name="ddMainSection" class="controls" id="main_section_id" section_data="<?php echo $select_parent_name; ?>" Value="<?php echo set_value('ddMainSection'); ?><?php echo @$get_article_details['Section_id']; ?>">
   <option id="MainSectionOption" value="">-Select-</option>
  
 <?php if(isset($section_mapping)) { 
 
				$section_bool = true;
 
				 foreach($section_mapping as $mapping) {  
				 
				 
				 $condition = $mapping['Sectionname'] != 'Galleries' && $mapping['Sectionname'] != 'Videos' && $mapping['Sectionname'] != 'Audios' &&  $mapping['Sectionname'] != 'Resources';
				 
				 if($condition) { ?>

<option id="MainSectionOption" style="color:#933;font-size:18px;"<?php /* if($mapping['Section_landing'] == 1 && $mapping['Sectionname'] != 'Columns' && $mapping['Sectionname'] != 'Magazine' && $mapping['Sectionname'] != 'The Sunday Standard' && $mapping['Sectionname'] != 'Editorials' ) { ?> disabled='disabled' <?php } */ ?>  class="blog_option" <?php if(set_value("ddMainSection") == $mapping['Section_id'] || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $mapping['Section_id']  ) || ($page_name != 'edit_article' && $this->session->userdata('main_section') == $mapping['Section_id'] && folder_name == 'dmcpan')) { echo  "selected"; $section_bool=false; } ?> sectoin_data="<?php echo @$mapping['Sectionname']; ?>" rel="<?php echo @$mapping['LinkedToColumnist']; ?>"  value="<?php echo $mapping['Section_id']; ?>" url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim(@$mapping['BreadCrumb'])))); ?>" author_name="<?php echo (isset($mapping['AuthorID']) && $mapping['AuthorID'] != '')? get_authorname_by_id($mapping['AuthorID']) : '' ; ?>"><?php echo strip_tags($mapping['Sectionname']); ?></option>
  <?php if(!(empty($mapping['sub_section'])) ) { ?>
 
  <?php foreach($mapping['sub_section'] as $key=>$sub_mapping) { ?>
  
  <option  id="MainSectionOption" <?php if(set_value("ddMainSection") == $sub_mapping['Section_id']  || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_mapping['Section_id'] ) || ($page_name != 'edit_article' && $this->session->userdata('main_section') == $sub_mapping['Section_id'] && folder_name == 'dmcpan')) { echo  "selected";  $section_bool=false; } ?>  sectoin_data="<?php echo @$mapping['Sectionname']; ?>"  rel="<?php echo @$sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>" url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim(@$sub_mapping['BreadCrumb'])))); ?>" author_name="<?php echo (isset($sub_mapping['AuthorID']) && $sub_mapping['AuthorID'] != '')? get_authorname_by_id($sub_mapping['AuthorID']) : '' ; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
		
		 <?php 
		 if(!(empty($sub_mapping['sub_sub_section']))) { ?>
		 
		   <?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
    <option id="MainSectionOption" <?php if($sub_sub_mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?>  <?php if(set_value("ddMainSection") == $sub_sub_mapping['Section_id']  || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_sub_mapping['Section_id'] ) || ($page_name != 'edit_article' && $this->session->userdata('main_section') == $sub_sub_mapping['Section_id'] && folder_name == 'dmcpan'))  { echo  "selected";  $section_bool=false; }  ?>  rel="<?php echo @$sub_sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_sub_mapping['Section_id']; ?>"  sectoin_data="<?php echo @$sub_mapping['Sectionname']; ?>" url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim(@$sub_sub_mapping['BreadCrumb'])))); ?>" author_name="<?php echo (isset($sub_sub_mapping['AuthorID']) && $sub_sub_mapping['AuthorID'] == '')? get_authorname_by_id($sub_sub_mapping['AuthorID']) : '' ; ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
		 
		<?php } } ?>
  <?php  } } ?>
				 <?php } } 
				 
				 if($section_bool == true && isset($get_article_details['Section_id']) && $get_article_details['Section_id'] != 0) { ?>
					 <option id="MainSectionOption" section_data="<?php echo $select_parent_name; ?>"  value="<?php echo $get_article_details['Section_id']; ?>"url_structure="<?php echo mb_ucwords(str_replace("-"," ",str_replace("/"," > ",trim(GenerateBreadCrumbBySectionId($get_article_details['Section_id']))))); ?>"  selected> <?php echo $select_section_name; ?> </option>				 <?php }
				 
				 } ?>

</select>



 <?php echo form_error('ddMainSection'); ?>
 
  <input type="checkbox" name="cbSectionPromotion" id="SectionPromotion" class="HeightAuto"  <?php if((set_value("cbSectionPromotion") == 'on' && $page_name != 'edit_article') ||   (isset($get_article_details['section_promotion']) && $get_article_details['section_promotion'] == 1 ) ) echo  "checked"; ?>  > Section promotion
  
  <p id="mainsection_breadcrumb"> </p>

</div>
		 </div>
			
            <div class="article_summary">
			<label>Summary</label>
			<div id="cke_charcount_summary" length="200" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_article_details['summaryHTML'])) echo 200 - mb_strlen($get_article_details['summaryHTML']); else echo "200"?></div>
<textarea class="ckeditor" id="summary"  name="txtSummary"><?php if(isset($get_article_details['summaryHTML'])) echo $get_article_details['summaryHTML']; echo set_value('txtSummary'); ?></textarea>
 <?php echo form_error('txtSummary'); ?>
			
           <div class="WidthPercentage PositionRelative">
		   <label class="WidthAuto">Body Text<sup>*</sup></label>
              <a class="btn-primary btn FloatRight EmbedImage" href="#" data-remodal-target="modal1"  onclick="ChangePopup('bodytext')" >Embed Image</a>
		</div>
            
<textarea class="ckeditor" id="body_text" name="txtBodyText"><?php if(isset($get_article_details['ArticlePageContentHTML'])) echo $get_article_details['ArticlePageContentHTML']; echo set_value('txtBodyText'); ?></textarea>
 <?php echo form_error('txtBodyText'); ?>
 <span id="article_body_error"></span>
            </div>
            <div class="article_image">
            <p>Images</p>
            <div class="article_image1 remoda1-bg">
            <h3>Home Page</h3>
            
            <p id="home_uploaded_image"><?php if((isset($get_article_details['homepageimageid']['temp_id']) && $get_article_details['homepageimageid']['temp_id'] != '') ) echo "Image set"; else  echo "Image not set"; ?></p>
      
            <a class="set_image <?php if((isset($get_article_details['homepageimageid']['temp_id']) && $get_article_details['homepageimageid']['temp_id'] != '') ) echo " "; else  echo  " BorderRadius3"; ?>" id="home_image_set" onClick="ChangePopup('home')" data-remodal-target="modal1" href="#"  ><?php if((isset($get_article_details['homepageimageid']['temp_id']) && $get_article_details['homepageimageid']['temp_id'] != '') ) echo "Change Image"; else  echo "Set Image"; ?></a>
            
			 <a  id="edit_article_image" rel="home" class="del_image delbtn_border" href="javascript:void(0);" <?php if((isset($get_article_details['homepageimageid']['temp_id']) && $get_article_details['homepageimageid']['temp_id'] != '') ) { ?>  <?php } else {  ?> style="display:none;"  <?php  } ?>><i class="fa fa-pencil"></i></a>
			
           <a id="delete_article_image" rel="home" class="del_image" href="javascript:void(0);"  <?php if((isset($get_article_details['homepageimageid']['temp_id']) && $get_article_details['homepageimageid']['temp_id'] != '') ) { ?>  <?php } else {  ?>   style="display:none;" <?php  } ?> ><i class="fa fa-trash-o"></i></a> 
		   
		  
           
              <input type="hidden" class="image-group"  name="imgHomeImageId" rel="<?php echo @$get_article_details['homepageimageid']['imagecontent_id']; ?>" value="<?php if(isset($get_article_details['homepageimageid']['temp_id'])) echo $get_article_details['homepageimageid']['temp_id']; echo set_value('imgHomeImageId'); ?>" id="home_image_gallery_id"/>
              
           
            </div>
          
            <div class="article_image1">
            <h3>Section Page</h3>
            
            <p id="section_uploaded_image"><?php if((set_value('imgSectionImageId') != '') || (isset($get_article_details['Sectionimageid']['temp_id']) && $get_article_details['Sectionimageid']['temp_id'] != '') ) echo "Image set"; else  echo "Image not set";  ?> </p>
            
            <a class="set_image <?php if((set_value('imgSectionImageId') != '') || (isset($get_article_details['Sectionimageid']['temp_id'] ) && $get_article_details['Sectionimageid']['temp_id'] != '') ) echo " "; else  echo " BorderRadius3";  ?>"  id="section_image_set" data-remodal-target="modal1" href="#"  onClick="ChangePopup('section')"><?php if((isset($get_article_details['Sectionimageid']['temp_id']) && $get_article_details['Sectionimageid']['temp_id'] != '') ) echo "Change Image"; else  echo "Set Image"; ?></a> 
            
				<a  id="edit_article_image" rel="section" class="del_image delbtn_border" href="javascript:void(0);" <?php if((isset($get_article_details['Sectionimageid']['temp_id']) && $get_article_details['Sectionimageid']['temp_id'] != '') ) { ?>  <?php } else {  ?> style="display:none;"  <?php  } ?>><i class="fa fa-pencil"></i></a>
			
            <a  id="delete_article_image" rel="section" class="del_image" href="javascript:void(0);" <?php if((isset($get_article_details['Sectionimageid']['temp_id']) && $get_article_details['Sectionimageid']['temp_id'] != '') ) { ?>  <?php } else {  ?>   style="display:none;" <?php  } ?>><i class="fa fa-trash-o"></i></a>
			
              <input type="hidden" class="image-group" name="imgSectionImageId"   rel="<?php echo @$get_article_details['Sectionimageid']['imagecontent_id']; ?>"  value="<?php if(isset($get_article_details['Sectionimageid']['temp_id'])) echo $get_article_details['Sectionimageid']['temp_id']; ?><?php  echo set_value('imgSectionImageId'); ?>" id="section_image_gallery_id" />
              
            
            </div>
            <div class="article_image1 margin-right-0">
            <h3>Article Page</h3>
            
            <p id="article_uploaded_image"><?php if((set_value('imgArticleImageId') != '') || (isset($get_article_details['articleimageid']['temp_id']) && $get_article_details['articleimageid']['temp_id'] != '')) echo "Image set"; else  echo "Image not set";  ?></p>
            
            <a class="set_image <?php if((set_value('imgArticleImageId') != '') || (isset($get_article_details['Articleimageid']) && $get_article_details['Articleimageid'] != '')) echo " "; else  echo "BorderRadius3";  ?>" data-remodal-target="modal1" href="#"   id="article_image_set" onClick="ChangePopup('article')"><?php if((isset($get_article_details['articleimageid']['temp_id']) && $get_article_details['articleimageid']['temp_id'] != '') ) echo "Change Image"; else  echo "Set Image"; ?></a>
            
			 <a  id="edit_article_image" rel="article" class="del_image delbtn_border" href="javascript:void(0);" <?php if((isset($get_article_details['articleimageid']['temp_id']) && $get_article_details['articleimageid']['temp_id'] != '') ) { ?>  <?php } else {  ?> style="display:none;"  <?php  } ?>><i class="fa fa-pencil"></i></a>
			
             <a  id="delete_article_image" rel="article" class="del_image" href="javascript:void(0);" <?php if((isset($get_article_details['articleimageid']['temp_id']) && $get_article_details['articleimageid']['temp_id'] != '') ) { ?>  <?php } else {  ?>   style="display:none;" <?php  } ?>><i class="fa fa-trash-o"></i></a>
             
             <input type="hidden" class="image-group"     rel="<?php echo @$get_article_details['articleimageid']['imagecontent_id']; ?>"   value="<?php if(isset($get_article_details['articleimageid']['temp_id'])) echo $get_article_details['articleimageid']['temp_id']; ?><?php  echo set_value('imgArticleImageId'); ?>" name="imgArticleImageId" id="article_image_gallery_id" />  
             
             
            </div>
            <?php echo form_error('imgArticleImageId'); 
			$display_none = ' style="display:none;" ';
			?>          
       	 	</div>
            <div id="ArticleImageContainerId" class="ArticleImageContainer" <?php if($page_name == 'edit_article' && isset($get_article_details['homepageimageid']['temp_id']) == '' && isset($get_article_details['Sectionimageid']['temp_id']) == '' && isset($get_article_details['articleimageid']['temp_id']) == ''  ) { echo $display_none; }  if($page_name != 'edit_article') {  echo $display_none;   } ?> >
          <div class="ArticleImageContainer1" id="home_image_container" <?php if(!isset($get_article_details['homepageimageid']['temp_id'])) { ?> style="visibility:hidden;" <?php } ?> >
          <p>Preview</p>
            <img  id="home_image_src" src="<?php if(isset($get_article_details['homepageimageid']['temp_id']) && $get_article_details['homepageimageid']['temp_id'] != '') { echo $get_article_details['homepageimageid']['temp_image'];  } ?>"  />
			<label>Caption :</label>
			<input type="text" id="home_image_caption"   name="home_image_caption"  value="<?php echo htmlentities(@$get_article_details['homepageimageid']['temp_caption']);   ?>" class="margin-top-5 WidthFull" />
			<label>Image Alt :</label>
			<input type="text" id="home_image_alt"  name="home_image_alt"  value="<?php echo htmlentities(@$get_article_details['homepageimageid']['temp_alt']);   ?>" class="margin-top-5 WidthFull">
			<label>Image Name :</label>
			<input type="text" id="home_physical_name"  name="home_physical_name" maxlength=100  physical_extension="<?php echo @$get_article_details['homepageimageid']['physical_extension'];   ?>" value="<?php echo @$get_article_details['homepageimageid']['physical_name'];   ?>" class="margin-top-5 WidthFull" <?php if(isset($get_article_details['homepageimageid']['temp_id'])) { ?> readonly <?php } ?>>
          </div>
          <div class="ArticleImageContainer1" id="section_image_container" <?php if(!isset($get_article_details['Sectionimageid']['temp_id'])) { ?> style="visibility:hidden;" <?php } ?>>
        <p style="">Preview</p>
           <img  id="section_image_src" src="<?php if(isset($get_article_details['Sectionimageid']['temp_id']) && $get_article_details['Sectionimageid']['temp_id'] != '') { echo $get_article_details['Sectionimageid']['temp_image'] ;  } ?>"   />
		   <label>Caption :</label>
		   <input type="text" value="<?php echo htmlentities(@$get_article_details['Sectionimageid']['temp_caption']);   ?>" name="section_image_caption"  id="section_image_caption"  class="margin-top-5 WidthFull" />
		   <label>Image Alt :</label>
		   <input type="text" value="<?php echo htmlentities(@$get_article_details['Sectionimageid']['temp_alt']);   ?>" name="section_image_alt"    class="margin-top-5 WidthFull"  id="section_image_alt"    />
		   <label>Image Name :</label>
			<input type="text" id="section_physical_name"  name="section_physical_name" maxlength=100 physical_extension="<?php echo @$get_article_details['Sectionimageid']['physical_extension'];   ?>"  value="<?php echo @$get_article_details['Sectionimageid']['physical_name'];   ?>" class="margin-top-5 WidthFull" <?php if(isset($get_article_details['Sectionimageid']['temp_id'])) { ?> readonly <?php } ?>>
          </div>
          <div class="ArticleImageContainer1 margin-right-0" id="article_image_container" <?php if(!isset($get_article_details['articleimageid']['temp_id'])) { ?> style="visibility:hidden;" <?php } ?>>
        <p style="">Preview</p>
          <img  id="article_image_src" src="<?php if(isset($get_article_details['articleimageid']['temp_id']) && $get_article_details['articleimageid']['temp_id'] != '') { echo $get_article_details['articleimageid']['temp_image'];  } ?>"  />
		  <label>Caption :</label>
		   <input type="text" value="<?php echo htmlentities(@$get_article_details['articleimageid']['temp_caption']);   ?>" name="article_image_caption"    class="margin-top-5 WidthFull" id="article_image_caption" />
		   <label>Image Alt :</label>
		   <input type="text" value="<?php echo htmlentities(@$get_article_details['articleimageid']['temp_alt']);   ?>" name="article_image_alt"   class="margin-top-5 WidthFull"  id="article_image_alt" />
		   <label>Image Name :</label>
			<input type="text" id="article_physical_name"  name="article_physical_name" maxlength=80 physical_extension="<?php echo @$get_article_details['articleimageid']['physical_extension'];   ?>"  value="<?php echo @$get_article_details['articleimageid']['physical_name'];   ?>" class="margin-top-5 WidthFull"  <?php if(isset($get_article_details['articleimageid']['temp_id'])) { ?> readonly <?php } ?>>
          </div>
        </div>
          

		    <div class="article_meta">
			<label>Meta Title<sup>*</sup></label>
            <div class="FloatLeft">
            <input id="count1" maxlength="100" name="txtMetaTitle" type="text" value="<?php if(isset($get_article_details['MetaTitle'])) echo htmlentities($get_article_details['MetaTitle']); echo htmlentities(set_value('txtMetaTitle')); ?>" class="box-shad1"/>
              <?php echo form_error('txtMetaTitle'); ?>
            </div>
            <div id="charNum1" class="charNum"><?php if(isset($get_article_details['MetaTitle'])) echo 100 - mb_strlen($get_article_details['MetaTitle']); else echo "100"?></div>
			<label>Meta Description</label>
            
             <textarea id="count2" maxlength="200"  name="txtMetaDescription" class="article_textarea box-shad1"  ><?php if(isset($get_article_details['MetaDescription'])) echo htmlentities($get_article_details['MetaDescription']); echo htmlentities(set_value('txtMetaDescription')); ?></textarea>
             <?php echo form_error('txtMetaDescription'); ?>
    <div id="charNum2" class="charNum"><?php if(isset($get_article_details['MetaDescription'])) echo 200 - mb_strlen($get_article_details['MetaDescription']); else echo "200"?></div>
            
			<label style="display:none;">URL Structure</label>
            <input style="display:none;" name="txtUrlStructure" type="text" class="box-shad1" value="" />
            </div>
			
		  </div>
             
            <div class="article_content2">
            <div class="article_attribute">
            <h3>Attributes</h3>
            <div class="article_attribute1">
            
            
    
            <div class="w2ui-field" id="agency_container"> 
<label>Agency<!--<sup>*</sup> --></label>
  <select name="ddAgency"  class="controls" id="agency_id"  >
   <option value="">-Select-</option>
    <?php if(isset($get_agency)) { 
	$boolagency = true;
					  foreach($get_agency as $agency) {?>
                      <option value="<?php echo $agency->Agency_id; ?>" <?php if(set_value("ddAgency") == $agency->Agency_id || (isset($get_article_details['Agency_ID']) && $get_article_details['Agency_ID'] == $agency->Agency_id)) { echo  "selected='selected'"; $boolagency = false; } if($page_name != 'edit_article' && ( $agency->Agency_name == 'New Indian Express' ||  $agency->Agency_name == 'DIN')) { echo "selected='selected'"; $boolagency = false;  }?> ><?php echo ucfirst($agency->Agency_name); ?></option>
                      <?php } } 
					  
					  if($boolagency == true &&  isset($get_article_details['Agency_ID']) && $get_article_details['Agency_ID'] != 0 ) { ?>
						  <option value="<?php echo $get_article_details['Agency_ID']; ?>" selected ><?php echo @$select_agency; ?></option>
					  <?php } ?>
				             
   </select>

    <?php echo form_error('ddAgency'); ?>
</div>
            
           
            <div class="w2ui-field attribute_br1">
			 <label id="byline_label">ByLine</label>
<!--
  <select name="ddByLine"  class="controls" id="byline_id"  >
   <option value="">-Select-</option>
    <?php if(isset($get_byline)) { 
					  foreach($get_byline as $byline) {?>
                      <option value="<?php echo $byline->Author_id; ?>" <?php if(set_value("ddByLine") == $byline->Author_id || (isset($get_article_details['Author_ID']) && $get_article_details['Author_ID'] == $byline->Author_id)) echo  "selected='selected'";  ?> ><?php echo ucfirst($byline->AuthorName); ?></option>
                      <?php } } ?>               
   </select>
-->

 <input type="text" name="txtByLine" id="txtByLine"  value="<?php if(isset($select_author) && $select_author != "") {  echo htmlentities($select_author); } echo set_value('txtbyline'); ?>" maxlength="50" />
         <input type="hidden" id="byline_id" name="ddByLine" value="<?php if(isset($get_article_details['Author_ID']) && $get_article_details['Author_ID'] != "") {  echo $get_article_details['Author_ID']; } echo set_value('hidden_byine_id'); ?>" />
	
    <?php echo form_error('ddByLine'); ?>
</div>


            <div class="w2ui-field">
<label>Country</label>
  <select name="ddCountry"  class="controls" id="country_id" value="<?php echo set_value('ddCountry'); ?>">
    <option value="">-Select-</option>
                     <?php if(isset($get_country)) { 		
					  foreach($get_country as $country) {?>
                     <option  <?php if(set_value("ddCountry") == $country->Country_id || (isset($get_article_details['Country_ID']) && $get_article_details['Country_ID'] == $country->Country_id ) || ($page_name !="edit_article" && $country->CountryName == 'India')) echo  "selected";  ?> value="<?php echo $country->Country_id; ?>"><?php echo ucfirst($country->CountryName); ?></option>
                      <?php } } ?>                     
                    </select>  

  <?php echo form_error('ddCountry'); ?>
</div>

            <div class="w2ui-field margin-bottom-7">
			<label>State</label>
<!--
  <select name="ddState"  class="controls" id="state_id" value="<?php echo set_value('ddState'); ?>" >
    <option value="">-Select-</option>
                           <?php if(isset($get_state)) { 
					  foreach($get_state as $state) {?>
                     <option  <?php if(set_value("ddState") == $state->State_Id || (isset($get_article_details['State_ID']) && $get_article_details['State_ID'] == $state->State_Id )) echo  "selected";  ?> value="<?php echo $state->State_Id; ?>"><?php echo ucfirst($state->StateName); ?></option>
                      <?php } } ?>                 
                    </select>

   -->
   
   <input type="text" name="txtState" id="txtState" value="<?php if(isset($select_state) && $select_state != "") {  echo $select_state; } echo set_value('txtState'); ?>"  />
         <input type="hidden" id="state_id" name="ddState" value="<?php if(isset($get_article_details['State_ID']) && $get_article_details['State_ID'] != "") {  echo $get_article_details['State_ID']; } echo set_value('ddState'); ?>" />
	
    <?php echo form_error('ddState'); ?>
   
   
   
</div>


            <div class="w2ui-field attribute_br1">
			<label>City</label>

					
		<input type="text" name="txtCity" id="txtCity" value="<?php if(isset($select_city) && $select_city != "") {  echo $select_city; } echo set_value('txtCity'); ?>"  />
         <input type="hidden" id="city_id" name="ddCity" value="<?php if(isset($get_article_details['City_ID']) && $get_article_details['City_ID'] != "") {  echo $get_article_details['City_ID']; } echo set_value('ddCity'); ?>" />

    <?php echo form_error('ddCity'); ?>
	
	
	
</div>

		
            <div class="padding-bottom-5">
           <input type="checkbox"  name="cbAllowComments" id="cbAllowComments"  <?php if(set_value("cbAllowComments") == 'on' || $page_name != 'edit_article' ||  (isset($get_article_details['Allowcomments']) && $get_article_details['Allowcomments'] == 1 ) ) echo  "checked"; ?> />
            <label for="cbAllowComments" class="margin-bottom-0">Allow Comments</label>
            </div>
			
			  <div class="padding-bottom-5">
           <input type="checkbox"  name="cbAllowPagination" id="cbAllowPagination"  <?php if(set_value("cbAllowPagination") == 'on' || $page_name != 'edit_article' ||  (isset($get_article_details['allow_pagination']) && $get_article_details['allow_pagination'] == 1 ) ) echo  "checked"; ?> />
            <label for="cbAllowPagination" class="margin-bottom-0">Allow Pagination</label>
            </div>

            </div>
            </div>
         <div class="article_attribute">
            <h3>Schedule</h3>
           <div class="article_schedule">
          <!--  <label>Publication Start Date</label>
              <div class='input-group date'>
                    <input type='text' class="form-control"  placeholder="Publish Start Date" id="publish_start_datetimepicker" name="txtPublishStartDate" value="<?php echo set_value('txtPublishStartDate'); ?><?php  if(isset($get_article_details['publish_start_date']) && $get_article_details['publish_start_date'] != '0000-00-00 00:00:00') { echo date('d-m-Y H:i:s', strtotime($get_article_details['publish_start_date'])); } ?>" />
                    
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar" id='publish_starticon'></span>
                    </span>
                </div>
            
            
            
            <label>Publication End Date</label>
       
             <div class='input-group date'>
                    <input type='text' class="form-control" placeholder="Publish End Date" id="publish_end_datetimepicker" name="txtPublishEndDate" value="<?php echo set_value('txtPublishEndDate'); ?><?php if(isset($get_article_details['publish_end_date'])  && $get_article_details['publish_end_date'] != '0000-00-00 00:00:00') { echo date('d-m-Y H:i:s', strtotime($get_article_details['publish_end_date'])); } ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar" id='publish_endicon'></span>
                    </span>
                </div>
            -->
            
            
             <label>Schedule Date</label>
             
             	<div class='input-group date' id="schedule_date_id">
					<input type='text' class="form-control" name="txtPublishStartDate1"  readonly id="publish_start_datetimepicker" value="<?php echo set_value('txtPublishStartDate'); ?><?php  if(isset($get_article_details['scheduled_article']) && $get_article_details['scheduled_article'] == 1  && $get_article_details['publish_start_date'] != '0000-00-00 00:00:00' && ( $get_article_details['publish_start_date'] !=  $get_article_details['Modifiedon'] || $get_article_details['scheduled_article'] == 1 ))  { echo date('d-m-Y H:i', strtotime($get_article_details['publish_start_date'])); } ?>" />
					
			
				<input type='hidden' class="form-control" name="txtPublishStartDate"  value="<?php echo set_value('txtPublishStartDate'); ?><?php  if(isset($get_article_details['publish_start_date']) && $get_article_details['publish_start_date'] != '0000-00-00 00:00:00') { echo date('d-m-Y H:i', strtotime($get_article_details['publish_start_date'])); } ?>" /> 
					
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar" id='publish_starticon' ></span>
					</span>
				</div>
             
             
               <label style="display:none;">Publication End Date</label>
               
				<div class='input-group date' style="display:none;">
					<input type='text' class="form-control" name="txtPublishEndDate" id="publish_end_datetimepicker" value="<?php echo set_value('txtPublishEndDate'); ?><?php if(isset($get_article_details['publish_end_date'])  && $get_article_details['publish_end_date'] != '0000-00-00 00:00:00') { echo date('d-m-Y H:i', strtotime($get_article_details['publish_end_date'])); } ?>" />
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"  id='publish_endicon'></span>
					</span>
				</div>
            
            </div> 
     		 <span id="publish_error"></span>
            
            
            
            </div>
            <div class="article_attribute">
            <h3>SEO</h3>
            <div class="article_seo">
          <!--  <p>Social Meta Tag</p>
            <div class="padding-bottom-5">
            <input type="checkbox" id="cbOpenGraphTags" name="cbOpenGraphTags" <?php if(set_value("cbOpenGraphTags") == 'on' || $page_name != 'edit_article' ||   (isset($get_article_details['Addto_opengraphtags']) && $get_article_details['Addto_opengraphtags'] == 'A' ) ) echo  "checked"; ?>  />
            <label for="cbOpenGraphTags">Open Grapgh Tags</label>
            </div>
            <div class="padding-bottom-5">
            <input type="checkbox" id="cbTwitterCards" name="cbTwitterCards" <?php if(set_value("cbTwitterCards") == 'on' || $page_name != 'edit_article' ||   (isset($get_article_details['Addto_twittercards']) && $get_article_details['Addto_twittercards'] == 'A' ) ) echo  "checked"; ?> />
            <label for="cbTwitterCards">Twitter cards</label>
            </div>
            <div class="padding-bottom-5 attribute_br1">
            <input type="checkbox" id="cbSchemaOrg" name="cbSchemaOrg" <?php if(set_value("cbSchemaOrg") == 'on' || $page_name != 'edit_article' ||   (isset($get_article_details['Addto_schemeorggplus']) && $get_article_details['Addto_schemeorggplus'] == 'A' ) ) echo  "checked"; ?>  />
            <label for="cbSchemaOrg">Schema.Org(G+)</label>
            </div> -->
            <p>Social Meta Tag</p>
            <div class="padding-bottom-5">
            <input type="checkbox" id="cbNoIndex" name="cbNoIndex" <?php if(set_value("cbNoIndex") == 'on'  ||   (isset($get_article_details['Noindexed']) && $get_article_details['Noindexed'] == 1 ) ) echo  "checked"; ?> />
            <label for="cbNoIndex">No Index</label>
            </div>
            <div class="padding-bottom-5">
            <input type="checkbox" id="cbNoFollows" name="cbNoFollows" <?php if(set_value("cbNoFollows") == 'on' ||   (isset($get_article_details['Nofollow']) && $get_article_details['Nofollow'] == 1 ) ) echo  "checked"; ?> />
            <label for="cbNoFollows">No Follow</label>
            </div>
            <label>Canonical URL</label>
            <input type="text" class="input_height box-shad1" name="txtCanonicalUrl"  value="<?php echo set_value('txtCanonicalUrl'); ?><?php if(isset($get_article_details['Canonicalurl'])) echo $get_article_details['Canonicalurl']; ?>" />
            </div>
            </div>
            
<div class="article_attribute">
<h3>Tags</h3>
<div class="article_tag"> 
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



<!-- <div class="taglang_bg Overflow">

<div class="FloatLeft"></div>
<div class="w2ui-field video_lang1 lang_drop FloatLeft">

   <select name="tag_language"  class="controls" value="English"  id="tag_language_id">
                	<option>English</option>
                                <option>Tamil</option>
                </select>
</div>

</div> -->
  <!--<section id="examples">
               	<div class="example example_markup">
          		<div class="bs-example">
            		<input style="display: none;" name="txtTags" id="tags_id"  value="<?php echo set_value('txtTags'); ?><?php if(isset($get_article_details['Tags'])) { echo $get_article_details['Tags']; } ?>"  data-role="tagsinput" type="text" class="tags_input">
                  
          		</div>
                </div>
		</section>  -->
</div>
</div>
			<!--
<div class="article_attribute">
<h3>Remarks</h3>
<div class="article_remark">
<textarea name="txtRemark" class="box-shad1" ><?php echo set_value('txtRemark'); ?><?php if(isset($get_article_details['Remarks'])) { echo $get_article_details['Remarks']; } ?></textarea>
</div>
</div> -->

            </div>         
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
            <div id="view3" style="display: none;">
            <div class="article_related">
<div class="switch switch-yellow">
      <input type="radio" class="switch-input" name="view3" value="week3" id="week3" checked="checked" onClick="articleInternal()">
      <label for="week3" class="switch-label switch-label-off">Internal</label>
      <input type="radio" class="switch-input" name="view3" value="month3" id="month3" onClick="articleExternal()">
      <label for="month3" class="switch-label switch-label-on">External</label>
      <span class="switch-selection"></span>
    </div>
    <div class="article_internal">
	<div class="article_table1">
    
      <ul class="AskPrabuCheckBox FloatLeft WidthPercentage">
      
        <li>
<div id="search_based_date">
<!--<input type="checkbox" id="search_based_check" name="answer" value="yes" /> -->
<label for="search_based_check">Search Based on Date Range</label>
</div>
    </li>

       
         
              <li class="form-group" id="checkin_checkout_div" >
                <div class='input-group date'>
                    <input type='text' class="form-control" id='checkin_id' readonly name="checkin" placeholder="From Date"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar" id='checkin_id_icon'></span>
                    </span>
                </div>
                
                 <div class='input-group date'>
                    <input type='text' class="form-control" name="checkout" readonly placeholder="To Date" id="checkout_id" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar" id='checkout_id_icon'></span>
                    </span>
                </div>
            </li>
            
           
        </ul>
       
       <div class="FloatLeft TableColumn">  

<div class="FloatLeft w2ui-field">
 <select  id="article_Type" class="controls">
                              <?php if(isset($get_content_type)) {
						  foreach($get_content_type as $type) { 
						  if($type->contenttype_id == 1 || $type->contenttype_id == 3  || $type->contenttype_id == 4  || $type->contenttype_id == 5 || $type->contenttype_id == 6) { ?>
						  	 <option <?php if($type->contenttype_id == 1){ ?> selected="selected" <?php } ?> value="<?php echo $type->contenttype_id; ?>"><?php echo $type->ContentTypeName; ?></option>
                              <?php } ?>
	  <?php }  } ?>
 </select>
</div>

<div class="FloatLeft w2ui-field">
 <select  id="article_section"  class="controls">
  <option value="">Section</option>
					<?php if(isset($section_mapping)) { 
                             foreach($section_mapping as $mapping) {  ?>
     <option  style="color:#933;font-size:18px;" class="blog_option" value="<?php echo $mapping['Section_id']; ?>"><?php echo $mapping['Sectionname']; ?> </option>
                    <?php if(!(empty($mapping['sub_section']))) { ?>
                            
                              <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
                <option value="<?php echo $sub_mapping['Section_id']; ?>"> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_mapping['Sectionname']; ?></option>
				
										<?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
                            
                              <?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
                <option value="<?php echo $sub_sub_mapping['Section_id']; ?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_sub_mapping['Sectionname']; ?></option>
                                    <?php } ?>
                             
                              <?php   } ?>
				
                                    <?php } ?>
                             
                              <?php   } ?>
                            
                            
                              <?php } } ?>
 </select>
</div>

<div class="FloatLeft TableColumnSearch"><input type="search" name="search_text" id="search_text" placeholder="Search" class="SearchInput"></div>
<button class="btn btn-primary" type="button" id="article_search_id">Search</button>
<button class="btn btn-primary" id="clear_search_article">Clear Search</button>
</div>


                        
<table id="example" class="display article_table" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>HEADLINE</th>
						<th>BreadCrumb</th>
                        <th>Published Date</th>
                        <th>Action</th>
					</tr>
				</thead>
	</table>
    
           
           </div>
            
    </div>
    <div class="article_external">
    <div class="related_form">
    <table>
    <tr>
    <td><label>Headline<sup>*</sup></label></td>
    <td><input type="text" class="box-shad1" name="external_title" id="external_title_id"/>  
    <div class="error" id="external_title_error"></div></td>
    </tr>
    <tr>
    <td><label>URL<sup>*</sup></label></td>
    <td><input type="text" class="box-shad1"  name="external_url" id="external_url_id" /><div class="error" id="external_url_error"></div></td>
    </tr>
    </table>
    </div>
    <div class="FloatRight article_add_list" id="add_to_list_div">
 <button class="btn-primary btn FloatLeft" type="button"  name="add_to_list" id="add_to_list_id"><i class="fa fa-file-text-o"></i> &nbsp;Add to List</button>
 </div>
   <div class="FloatRight article_add_list" id="update_to_list_div" style="display:none;">
 
 <button class="btn-primary btn FloatLeft IconButton" type="button" name="update_to_list" id="update_to_list_id"><i class="fa fa-pencil"></i> &nbsp;Update to List</button>
 <button class="btn-primary btn FloatLeft" type="button"  name="cancel_to_list" id="cancel_to_list_id"><i class="fa fa-times"></i>&nbsp;Cancel</button>
 
  <input type="hidden" name="update_external" id="update_external_value" />
 </div>
    </div>
     <span id="priority_error" style="color:red;"></span>
    <div class="article_table2">
     <h3>Related Content Priority Settings</h3>
    <table width="100%" cellspacing="0" class="display article_table dataTable no-footer" id="sort" role="grid" aria-describedby="link_preview_table_info" style="width: 100%;">
				<thead id="link_preview_head" style="display:none;">
					<tr role="row"><th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">Display Order</th><th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">Headline</th><th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">BreadCrumb</th><th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-label="Type: activate to sort column ascending">Type</th><th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-label="Action: activate to sort column ascending">Action</th></tr>
				</thead>

				<tbody id="link_preview_body" class="ui-sortable">
              <tr class="odd"><td valign="top" colspan="3" class="dataTables_empty">No data available in table</td></tr>
                </tbody>
			</table>
    </div>
    
    </div>
            
            
        </div>
       
<input type="hidden" name="hide_external_link" id="hide_external_link_id" value="" />
<input type="hidden" id="current_image_popup" value="" />


</form>

 <div class="remodal" id="preview_article_popup" data-remodal-id="preview_article_popup" data-remodal-options="hashTracking: false" style="position:relative;">
 <div id="preview_article_popup_loading">
 
 </div>
 <div id="preview_article_popup_container"  class="container" style="display:none;">
 
 </div>
 </div>

 
        
		   <div class="remodal" data-remodal-id="modal1" data-remodal-options="hashTracking: false"  style="position:relative;">
            <div class="article_popup GalleryPopup ArticlePopup" style="height: 467px;">
            <div class="article_popup1">
            <ul class="article_popup_tabs">
            <li onclick="articleUpload()" class="active img_upload">From Local System</li>
            <li onclick="articleBrowse()" class="img_browse">From Library</li>
            </ul>
            </div>
            <div class="article_popup2">
            <div class="article_upload">
       
          <form  name="ImageForm" id="ImageForm" action="<?php echo base_url().folder_name; ?>/article_image/image_upload" method="POST" enctype="multipart/form-data">
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
<!--
  <select name="ddMainSectionSearch"  class="controls" id="image_section_search">
   <option value="">Section: All</option>
  
 <?php if(isset($section_mapping)) { 
				 foreach($section_mapping as $mapping) {  ?>

<option style="color:#933;font-size:18px;"  class="blog_option" value="<?php echo $mapping['Section_id']; ?>"><?php echo $mapping['Sectionname']; ?> 
  <?php if(!(empty($mapping['sub_section']))) { ?>

  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
    <option  value="<?php echo $sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_mapping['Sectionname']; ?></option>
    	<?php } ?>
  <?php   } ?>
 </option>

  <?php } } ?>

</select> -->
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
            <label>Image Alt</label>
            <h5 id="textarea_alt"><?php echo $first_image_alt; ?></h5>
            <label>Caption</label>
            <h5  id="textarea_caption"><?php echo $first_image_caption; ?></h5>
              <?php } ?>
            
            </div>
    
            </div>
            <?php if(isset($first_image_pathname)) { ?>
            <div class="FloatRight popup_insert insert-fixed">
       <button type="button" class="btn btn-primary remodal-confirm"id="browse_image_insert"  >Insert</button>
            </div>
			 <?php } } ?>
            </div>
			
            </div>
            </div>
            </div>
</section>
  
</div>
        
  </div>
                     
</div>                            
</div>                       

<script type="text/javascript" src="<?php echo image_url ?>js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/tabcontent.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.form.js" ></script>    
<script type="text/javascript" src="<?php echo image_url ?>js/jquery-ui.min.1.8.16.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/tags/angular.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/tags/bootstrap-tagsinput.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/tags/bootstrap-tagsinput-angular.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/tags/typeahead.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.remodal.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/modernizr.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>includes/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/menu.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/article.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/article-pagination.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.twbsPagination.min.js"></script>
<script>CKEDITOR.dtd.$removeEmpty['span'] = false;</script>
<?php if(folder_name != 'niecpan')  { ?>
<script>CKEDITOR.dtd.$removeEmpty['span'] = false;</script>
<?php } ?>



<script  type="text/javascript">

<?php if(isset($print_status)) { ?>

function display_status(print_status,publish_date)
{
var postdata = "get_content_id="+<?php echo $get_article_details['content_id'] ?>+"&print_status="+print_status+"&publishdate="+publish_date+"&unpublishdate="+"<?php echo $unpublishdate ?>"+"&content_type=1&schedule_status="+<?php echo $schedule_status; ?>;

 $.ajax({
   url: base_url+folder_name+"/common/status_statement", // Url to which the request is send
   type: "POST",             // Type of request to be send, called as method
   data:  postdata,
   dataType: "HTML",
   async: false, 
   success: function(data)   // A function to be called if request succeeds
   {
	   
   if (data.indexOf("LIVE | Status") >= 0) 
	$("#main_section_id").attr('disabled',true)
	else
	$("#main_section_id").attr('disabled',false)
	 
    $("#TopStatusId").html(data);
	
	<?php if($schedule_status != '0') { ?>
	
	 var Status_string = "<p><?php echo 'Created By : '.ucfirst(get_userdetails_by_id($get_article_details['Createdby'])).' | '.'Created On : '. date('d-m-Y H:i:s',strtotime($get_article_details['Createdon'])).' | '.'Last Updated By : '.ucfirst(get_userdetails_by_id($get_article_details['Modifiedby'])).' | '.'Last Updated On : '. date('d-m-Y H:i:s',strtotime($get_article_details['Modifiedon'])); ?></p>";
	 
	 if (data.indexOf("LIVE | Status") >= 0) 
	  $("#TopStatusId").append(Status_string);
  
	<?php } ?>
  
		$("#main_content").css("margin-top","177px");
	
   }
 });
}


<?php } ?>


$(document).ready(function() {
	
<?php if(isset($print_status)) { ?>

	display_status("<?php echo $print_status; ?>","<?php echo $publishdate; ?>");
	
	$MetaTitle = $.trim($("#count1").val());
	
	if($MetaTitle != '') {
		
	var postdata = "metatitle="+$MetaTitle;
	$("#suggestion_div").html('<p>  Suggestions : </p>');
	$("#suggestion_div").show();
			$.ajax({
				url: base_url+folder_name+"/common/get_tags_by_meta_title", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "JSON",
				success: function(data)   // A function to be called if request succeeds
				{
					if(data != '[]') {
						$.each(data,function(i,item) {
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
	
	
 $('#checkin_id').datetimepicker({
  format:'d-m-Y',
  maxDate: new Date(),
  timepicker:false,
   onClose:function(ct) {
	  	 // $('#checkout_id').val('');
  },
   onChangeDateTime:function(ct) {
	    $('#checkout_id').val('');
  }
 });
 $('#checkout_id').datetimepicker({
  format:'d-m-Y',
  maxDate: new Date(),
  onShow:function( ct ){
	  
	  var start = $('#checkin_id').val();
	  console.log(start);
	 if(start != '') { 
	  
		  var startdate = new Date( start.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3") );
		 
			var date = new Date(startdate);
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			var CurrFulldate = new Date();
			var Cy = CurrFulldate.getFullYear();
			var Cd = CurrFulldate.getDate();
			var Cm = CurrFulldate.getMonth();
			//var edate= new Date(y, m, d+1);
			 var startdate = new Date(y, m, d);
			  var max_date = new Date(Cy, Cm, Cd);


			  
			
	   this.setOptions({
		minDate:startdate,
		maxDate:max_date,
	//	defaultDate: edate
	   })
	   
	 }
   
  },
  timepicker:false
 });
 
 
 $("#checkin_id").change(function(){
if ($('#checkout_id').val()=="") {
$('#checkout_id').val($('#checkin_id').val());
}
});
$("#checkout_id").change(function(){
if ($('#checkin_id').val()=="") {
$('#checkin_id').val($('#checkout_id').val());
}
});
 
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	
<?php if($this->session->flashdata('msg')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 20000);
});
<?php } ?>
<?php
/*
echo "<pre>";
print_r($get_related_article);
exit;
*/

 if(isset($get_related_article) && !empty($get_related_article)) { 

				foreach($get_related_article as $related) { 
					if($related['Related_content_id'] == '' || $related['Related_content_id'] == 'NULL' || $related['Related_content_id'] == 0) { ?>
					
			var sub_array = {};
			sub_array["type"] 					= 'E';
			sub_array["external_title"] 		= "<?php echo trim(strip_tags($related['ExternalArticletitle'])); ?>";
			sub_array["external_short_title"] 	= "<?php echo shortDescription(trim(strip_tags($related['ExternalArticletitle']))); ?>";
			sub_array["external_url"] 			= "<?php echo $related['ExternalArticleURL']; ?>";
		
			external_array.push(sub_array);
	<?php 			} else {  
	$BreadCrumb = GetBreadCrumbByURL( trim($related['ExternalArticleURL']));
	?>
						
			var sub_array = {};
			sub_array["type"] 			= 'I';
			sub_array["content_type"] 	=  <?php echo $related['contentType']; ?>;
			sub_array["content_id"] 	=  <?php echo $related['Related_content_id']; ?>;
			sub_array["short_title"] 	= "<?php echo shortDescription(trim(strip_tags($related['ExternalArticletitle']))); ?>";
			sub_array["long_title"] 	= "<?php echo trim(strip_tags($related['ExternalArticletitle'])); ?>";
			sub_array["bread_crumb"] 	= "<?php echo trim($BreadCrumb); ?>";
			sub_array["url"]			= "<?php echo $related['ExternalArticleURL']; ?>";	
			
		
				external_array.push(sub_array);
		
<?php	 }
				}  ?>
				console.log(external_array);
				$("#hide_external_link_id").val(JSON.stringify(external_array));
<?php		} ?>

$('#view1').tabs('load', 0);
		
});
</script>  
<style>
.popup_images img {
    height: 95px;
    width: 146px;
}
</style>
<!-- Mansory & Infinite Scroll Script -->
<script src="<?php echo image_url ?>js/jquery-1.7.1.min.js"></script>
<script src="<?php echo image_url ?>js/jquery.masonry.min.js"></script>
<script src="<?php echo image_url ?>js/jquery.infinitescroll.min.js"></script>
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
	/*
	function ImageExist(url) 
	{
	   var img = new Image();
	   img.src = url;
	   return img.complete;
	}
	*/
	
	function ImageExist(image_url){

		var http = new XMLHttpRequest();

		http.open('HEAD', image_url, false);
		http.send();

		return http.status != 404;

	}	
	
	function Image_Search() {
		
			 jqis("#image_lists_id").empty();
		 var $container = jqis('.popup_images');
		 $container.empty();
	if(jqis.trim($container.html()) == '') {
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		}	
			var Caption = jqis("#search_caption").val();
			
			postdata = "Caption="+Caption;
			jqis.ajax({
				url: base_url+folder_name+"/article_image/search_image_library",
				type: "POST",
				data: postdata,
				dataType: "json",
				success: function(data){
					console.log("test");
					var Content = '';
					var Count 	= 0;
					var Image_URL = "<?php echo image_url.imagelibrary_image_path;?>";
					
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
 <link type="text/css" rel="stylesheet" href="<?php echo image_url ?>css/admin/datepicker.min.css" />
<script type="text/javascript" src="<?php echo image_url ?>js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap-datetimepicker.min.4.14.30.js"></script>

</head>
<script type="text/javascript">
var jq = $.noConflict();
jq(document).ready(function() {

	jq('#schedule_date_id').click(function(){
		
		//alert($(this).children('#publish_start_datetimepicker').prop("disabled"));
		
		if($(this).children('#publish_start_datetimepicker').prop("disabled") == true)
			Flash_message("Article is already published, can not schedule it again",'SessionError')
	
	});
	
	jq('#publish_start_datetimepicker').datetimepicker({
	  format:'DD-MM-YYYY H:mm',
	  useCurrent: false,
	  ignoreReadonly : true
	});
	/*jq('#publish_end_datetimepicker').datetimepicker({
	  format:'DD-MM-YYYY H:mm'
	}); */
	jq("#publish_start_datetimepicker").on("dp.change",function (e) {
    moment.min(e.date);
	});
	jq("#publish_end_datetimepicker").on("dp.change",function (e) {
       moment.max(e.date);
	});
	jq('#publish_starticon').click(function(){
		//jq('#publish_start_datetimepicker').click();
			jq('#publish_start_datetimepicker').datetimepicker({
			  format:'DD-MM-YYYY H:mm',
			  ignoreReadonly : true
			});
	});
	/*jq('#publish_endicon').click(function(){
		jq('#publish_end_datetimepicker').click();
	}); */
});
</script>
   
<link rel="StyleSheet" href="<?php  echo image_url ?>includes/Tagedit-master/css/jquery.tagedit.css" type="text/css" media="all"/>    
<script type="text/javascript" src="<?php  echo image_url ?>includes/Tagedit-master/js/jquery-1.10.2.min.js"></script>
<!--<script type="text/javascript" src="<?php echo  image_url ?>includes/Tagedit-master/js/jquery-ui-1.9.2.custom.min.js"></script>
-->  <script src="<?php echo  image_url ?>js/jquery_ui.js"></script>
<script type="text/javascript" src="<?php echo  image_url ?>includes/Tagedit-master/js/jquery.autoGrowInput.js"></script>
<script type="text/javascript" src="<?php  echo image_url ?>includes/Tagedit-master/js/jquery.tagedit.js"></script>
    
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
		});
		*/

		
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
	
	
		$("#preview_id").click(function() {
			
			
			$("#preview_article_popup_container").hide();
			$("#preview_article_popup_loading").hide();
			
				publishdate =  '';
				last_update = '';
			<?php if(isset($print_status) == "Published") { ?>
				publishdate = "<?php echo date('dS  F Y h:i A',strtotime($get_article_details['publish_start_date'])); ?>";
			<?php } ?>
			
			if(publishdate == '' ) {
				publishdate = $("#publish_start_datetimepicker").val();
			}
			
			if(folder_name == 'dmcpan'  && publishdate != '')
				last_update =  publishdate;
			
			
			/*<?php if(isset($get_article_details['Modifiedon'])) { ?>
				last_update = "<?php echo date('dS  F Y h:i A',strtotime($get_article_details['Modifiedon'])); ?>";
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

			head_line = encodeURIComponent(CKEDITOR.instances.article_head_line_id.getData());
			body_text = encodeURIComponent(CKEDITOR.instances.body_text.getData());
			
			related_article 		= JSON.stringify(external_array);
			article_image_id  		= $("#article_image_gallery_id").val();
			article_image_caption  	= encodeURIComponent($("#article_image_caption").val());
			tags 					= JSON.stringify(tags);
				
				
				AuthorName = '';
			if($("#main_section_id").attr('rel') == 'Y') {
				
				AuthorName = $.trim($("#main_section_id").find('option:selected').text());
			} else  {
				if($("#agency_id").find('option:selected').text() != '-Select-') 
				AuthorName = $.trim($("#agency_id").find('option:selected').text());
			}
			
			var Byliner 	= $.trim($("#txtByLine").val());
			var author_id 	= $("#byline_id").val();
			var agency_id 	= $("#agency_id").val();
			
			var section_id = encodeURIComponent($.trim($("#main_section_id").val()));
			
			if(AuthorName == '-Select-') {
				AuthorName = '';
			}
	
			var postdata = { "head_line" : head_line,"body_text" :body_text,"related_article" : related_article,"article_image_id" : article_image_id,"article_image_caption" : article_image_caption ,"tags" : tags,"AuthorName" : AuthorName,"Byliner" : Byliner,"publishdate" :publishdate,"last_update" : last_update,"section_id" : section_id,"author_id" : author_id,"agency_id" :agency_id,"get_content_id" :get_content_id };
			$.ajax({
			url: base_url+folder_name+"/article_manager/get_article_preview_popup", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			dataType: "HTML",
			async: false, 
			success: function(data)   // A function to be called if request succeeds
			{
				
		setTimeout(function(){
			
		//$('link[rel=stylesheet][href~="'+base_url+'css/admin/dashboard-style.css"]').remove();
		$("#contents_css").remove();

		$(".previewcontainer").append($('<link rel="stylesheet" href="'+base_url+'css/FrontEnd/css/style.css" type="text/css">')); 
		//$(".previewcontainer").append($("<script type='text/javascript' src='"+base_url+folder_name+"js/article-pagination.js'>"));
	
	},1000);
	
	setTimeout(function(){
		$('.remodal-close').show();
	$("#preview_article_popup_container").html(data);	

	$("#preview_article_popup_loading").hide();
	$("#preview_article_popup_container").show();
	
	$("#storyContent p:eq(2)").after($('.RelatedArticle').show());

	setTimeout(function(){
		if(document.getElementById('cbAllowPagination').checked) {
			if(folder_name == 'dmcpan')
				$('#storyContent').MyPagination({height: 2600, fadeSpeed: 400});
			else
				$('#storyContent').MyPagination({height: 900, fadeSpeed: 400});
		}
		$('#storyContent .page').css("float", "none");
		// $('#content').css("height", "900");
     //$('#content').height($('.page').eq(0).height());

	// showPage(1);
	 },10);

	
	
	
		},1000);
		
		
			}
		});
				
		
	}); 
	
	
$(document).on('close', '#preview_article_popup', function () {  
	 
		$(".css_and_js_files").append($('<link rel="stylesheet" href="'+base_url+'includes/ckeditor/contents.css" type="text/css"  id="contents_css">'));  
		
		//$("script[src='"+base_url+folder_name+"js/article-pagination.js']").remove();
		
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/style.css"]').remove();
	
 
}); 
	
		
	
	});
	</script>
	
<script src="<?php echo image_url ?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="<?php echo image_url ?>js/jquery-ui.min-1.8.18.js" type="text/javascript"></script>

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
        jqs_datatable('td.index', ui.item.parent()).each(function (i) {
            jqs_datatable(this).html(i + 1);
			
	
			var related_data = $(this).data();
			
			if(related_data.type == 'E') {
				external_array[i]['type'] 					= related_data.type;
				external_array[i]['external_title'] 		= related_data.external_title;
				external_array[i]['external_short_title'] 	= related_data.external_short_title;
				external_array[i]['external_url'] 			= related_data.external_url;
				
				delete external_array[i]['content_type'];
				delete external_array[i]['content_id'];
				delete external_array[i]['short_title'];
				delete external_array[i]['long_title'];
				
			} else {
				external_array[i]['type'] 			= related_data.type;
				external_array[i]['content_type'] 	= related_data.content_type;
				external_array[i]['content_id'] 	= related_data.content_id;
				external_array[i]['short_title'] 	= related_data.short_title;
				external_array[i]['long_title'] 	= related_data.long_title;
				external_array[i]['bread_crumb'] 	= related_data.bread_crumb;
				external_array[i]['url']			= related_data.url;	
				
				delete external_array[i]['external_title'];
				delete external_array[i]['external_short_title'];
				delete external_array[i]['external_url'];
			}
	
			
			
        });
		jqs_datatable('td input[type=text].updatefield', ui.item.parent()).each(function (i) {
            jqs_datatable(this).val(i + 1);
			console.log( $(this).val(i + 1));
        });
		fill_external_link_preview();
    };

jqs_datatable("#sort tbody").sortable({
    helper: fixHelperModified,
	 placeholder:'must-have-class',
    stop: updateIndex
}).disableSelection();
});

$("#article_Type").change(function(){
	
GenerateSectionDropDown($(this).val());

});

//GenerateSectionDropDown(1);

function GenerateSectionDropDown(content_type) {

			$.ajax({
			url: base_url+folder_name+"/common/get_json_section", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  "content_type="+content_type,
			dataType: "JSON",
			async: false, 
			success: function(section_json)   // A function to be called if request succeeds
			{	
				
				Content = '<option value="">-Section-</option>';
			$.each(section_json,function(index,value){
				
				if(content_type == 3) {
					  if(folder_name == 'niecpan')	
						var condition = value.Sectionname == 'Galleries' ;
					else
						var condition = value.Sectionname == 'புகைப்படங்கள்' ;
				} else if(content_type == 4) {
						if(folder_name == 'niecpan')	
					  var condition = value.Sectionname == 'Videos' ;	  
						else 
					var condition = value.Sectionname == 'வீடியோக்கள்' ;	  		
				} else if(content_type == 5) {
						if(folder_name == 'niecpan')	
					  var condition = value.Sectionname == 'Audios' ;	
					else 
					var condition = value.Sectionname == 'ஆடியோக்கள்' ;			  
				} else {	
					if(folder_name == 'niecpan')	
					  var condition =  value.Sectionname != 'Galleries' && value.Sectionname != 'Videos' && value.Sectionname != 'Audios'
				  else 
					   var condition =  value.Sectionname != 'புகைப்படங்கள்' && value.Sectionname != 'வீடியோக்கள்' && value.Sectionname != 'ஆடியோக்கள்'
					
				}
				
				if(condition) {
				
				  Content += '<option  style="color:#933;font-size:18px;" class="blog_option" value="'+value.Section_id+'">'+value.Sectionname+'</option>'
				  $.each(value.sub_section,function(sub_index,sub_value) {
						Content += '<option  value="'+sub_value.Section_id+'"> &nbsp;&nbsp;&nbsp;&nbsp;'+sub_value.Sectionname+'</option>';
						if(typeof sub_value.sub_sub_section != 'undefined') {
						$.each(sub_value.sub_sub_section,function(sub_sub_index,sub_sub_value) {
							Content += '<option  value="'+sub_sub_value.Section_id+'"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+sub_sub_value.Sectionname+'</option>';
						});
						}
						
					  });
					  
				}
					  
			});

			$("#article_section").html(Content);

			}
			
			});
}

</script>