<?php $section_segment = $this->uri->segment('2'); ?>
<link href="<?php echo base_url(); ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />
 <style>
  label.error
  {
	  color:#F00;
	  display: table-row;
  }
  
  .margin-left-0 {
	  margin-left:0 !important;
  }
  .tabcontents label {
    font-weight: normal;
}
  </style>  

<div class="Container">
    <div class="BodyWhiteBG">
    	<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<div class="breadcrumbs"><a href="javascript:void(0);">Dashboard</a> > <a href="javascript:void(0);"><?php if($page_name == 'edit_resource') echo "Edit Resource"; else echo "Create Resource"; ?></a></div>
 <h2 class="FloatLeft"><?php if($page_name == 'edit_resource') echo "Edit Resource"; else echo "Create Resource"; ?></h2>
 
 </div>
 
 <div class="FloatLeft SessionError" id="flash_msg_id" style="display:none;top: 145px;">
<?php if($this->session->flashdata('msg')){ 
echo $this->session->flashdata('msg');
} ?></div>



<p class="FloatRight save-back save_margin article_save">

<?php
	
 if(isset($get_resources_details['status'])) {
	$status 		= $get_resources_details['status'];
	$get_content_id = $get_resources_details['content_id'];
		
	$publishdate  	= "";
	$unpublishdate  = "";
	
	switch($status )
	{
		case "P":
			$print_status = "Published";
			$publishdate  = date('d-m-Y H:i:s',strtotime($get_resources_details['publish_start_date']));
			break;
		case "U":
			$print_status = "Unpublished";
			$unpublishdate  = date('d-m-Y H:i:s',strtotime($get_resources_details['Modifiedon']));
			break;
		case "D":
			$print_status = "Draft";
			break;
		default:
			$print_status = "None";
	}


	if($status != 'D' && $status != 'U') {
		if( strtotime($get_resources_details['publish_start_date']) <=  strtotime(date('d-m-Y H:i:s'))) {
			$print_status = "Published";
	}
	
}
}



 ?>

<a class="back-top FloatLeft" href="<?php echo base_url().folder_name; ?>/resources_manager" title="Go Back"><i class="fa fa-reply fa-2x"></i></a>
<?php  if($page_name == 'edit_resource') { ?>
<?php if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { ?>
<button class="btn btn-primary FloatRight i_button FlagIcon2" id="unpublishtop_id"  title="Unpublish" <?php if( isset( $get_resources_details['status']) && ( $get_resources_details['status'] == "U" ||  $get_resources_details['status'] == "D") or !isset( $get_resources_details['content_id'])){ ?> style="display:none" <?php } ?>></button>
<?php  } 
 } ?>
<?php  if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {  ?>
<button class="btn btn-primary FloatRight i_button"  id="publishtop_id" title="Publish" ><i class="fa fa fa-flag"></i></button>
<?php  }  ?>

<button class="btn btn-primary FloatRight i_button"  id="send_drafttop_id" title="Draft"  <?php if( isset($status) && ($status == "P" or $status == "U")){ ?> style="display:none" <?php } ?> ><div class="DraftIcon"></div>
</button>

</p>


<?php if(isset($print_status) && $print_status != "")  { ?>
 
 <span class="TopStatus" id="TopStatusId"><?php echo "Status :". $print_status; ?></span>
 
 <?php } ?>

</div>

		<?php if($section_segment == 'edit_archive_resources') { ?>
		
				<form action="<?php echo base_url().folder_name.'/resources_manager/update_archive_resources/'.$archive_year.'/'.$get_resources_details['content_id'];  ?>" id="content_form" name="content_form" method="post" enctype="multipart/form-data">
		
		<?php } else { ?>

		<form action="<?php if($page_name == 'edit_resource') { echo base_url().folder_name.'/resources_manager/update_resources/'.$get_resources_details['content_id'];  } else { echo base_url().folder_name.'/resources/create_resources'; } ?>" id="content_form" name="content_form" method="post" enctype="multipart/form-data">

		<?php } ?>
		
		<input type="hidden" name="txtStatus" id="status_id" value=""/ >
		<input type="hidden" name="archive_year" id="archive_year" value="<?php echo @$archive_year; ?>" />
		<input type="hidden" name="txtOldStatus" id="old_status_id" value="<?php if(isset($get_resources_details['status'])) echo $get_resources_details['status']; ?>" />
<input type="hidden" name="txtContentId" id="content_id" value="<?php if(isset($get_resources_details['content_id'])) echo $get_resources_details['content_id']; ?>" />
<input type="hidden" name="txtPublishStartDate" value="<?php if(isset($get_resources_details['publish_start_date'])) { echo date('Y-m-d H:i', strtotime($get_resources_details['publish_start_date'])); } else { echo date('Y-m-d H:i'); } ?>" />


	
            <div class="poll_content" 	<?php if($page_name == 'edit_resource') { ?> style="margin-top:130px;" <?php } ?>>
			
			<?php if($page_name == 'edit_resource') { ?>
  <p><?php echo 'Created By : '.ucfirst($get_resources_details['CreatedName']).' | '.'Created On : '.  date('d-m-Y H:i:s',strtotime($get_resources_details['Createdon'])).' | '.'Last Updated By : '.ucfirst($get_resources_details['ModifiedName']).' | '.'Last Updated On : '.  date('d-m-Y H:i:s',strtotime($get_resources_details['Modifiedon'])); ?></p>
<?php } ?>
			
			
                <div class="create_poll">
                      <div class="qnsans">
                          <div class="qns"><label class="question">Title<span style="color:#F00">*</span></label></div>
                          <div class="ans VideoCk GalleryNameCount  PositionRelative">
                           <!--<div id="cke_charcount_resource_head_line_id" length="100" class="charNum FloatRight" style="float:right;margin-bottom: 0;"><?php if(isset($get_resources_details['title'])) echo mb_strlen($get_resources_details['title']); else echo "100"?></div> -->
                          <textarea class="ckeditor" name="resource_head_line_id" id="resource_head_line_id" maxlength="255"><?php echo set_value('resource_head_line_id'); ?><?php  if(isset($get_resources_details['title'])) echo $get_resources_details['title']; ?></textarea>
						  <?php echo form_error('resource_head_line_id'); ?>
                                <p id="title_error" style="color:#F00"></p>
                          </div>
                      </div>
                          
                      <div class="qnsans">
                          <div class="qns">
						  <label>URL Title</label></div>
                          <div class="ans">
						 <input type="text" class="box-shad1" id="txtUrlTitle" name="txtUrlTitle"   value="<?php if(isset($get_resources_details['content_id'])) echo $get_resources_details['url_title']; echo set_value('txtUrlTitle'); ?>" />
           
				   <?php echo form_error('txtUrlTitle'); ?>
                          </div>
                      </div>
					<!--  <?php if(isset($get_resources_details['url'])) { ?>
					   <div class="qnsans">
                          <div class="qns">
						  <label>URL Structure</label></div>
                          <div class="ans"><a href="<?php  echo base_url().$get_resources_details['url']; ?>">
						 <?php  echo base_url().$get_resources_details['url']; ?></a>
                          </div>
                      </div>
					  <?php } ?>
                      -->
                      <div class="qnsans">
                          <div class="qns"><label>Resource<span style="color:#F00">*</span></label></div>
                          <div class="ans">
						  <div class="fileUpload btn btn-primary">
    <span>Browse</span>
    <input type="file" style="width:92px;"  class="upload" id="btnResource" name="btnResource" value="<?php if(isset($get_resources_details['resource_url']) && isset($get_resources_details['resource_url']) != '' ) { echo $get_resources_details['resource_url'];  } ?>" / />
	
	
	<input type="hidden" name="ExistingResource" id="ExistingResource" value="<?php if(isset($get_resources_details['resource_url']) && isset($get_resources_details['resource_url']) != '' ) { echo $get_resources_details['resource_url'];  } ?>" />
	
   </div>
   
   <?php if(isset($get_resources_details['resource_url']) && isset($get_resources_details['resource_url']) != '' ) { ?>
	<a href="<?php echo image_url.resource_path.$get_resources_details['resource_url']; ?>" class="btn-primary btn  poll-preview" > <i class="fa fa-download"></i> </a>
	<?php } ?>
	
	 <p id="SourceName" style="font-weight:bold;"><?php if(isset($get_resources_details['resource_url']) && isset($get_resources_details['resource_url']) != '' ) { //$Array = explode("/",@$get_resources_details['resource_url']);  echo @$Array[count($Array)-1];
	 
	 echo image_url.resource_path.$get_resources_details['resource_url'];
	 } ?></p>
	
            <p style="color:red;" id="lblError"></p>  
                          </div>
                      </div>
					  <!--
                      <div class="qnsans">
                          <div class="qns"><label>Link Article<span style="color:#F00">*</span></label></div>
                          <div class="ans">
                          <a class="btn btn-primary poll-preview"  href="#" data-remodal-target="modal1"><i class="fa fa-desktop"></i></a> 
						  
						  <button class="btn-primary btn" type="button" name="btnunlink_article" id="btnunlink_article" style="display:none">Unlink Article</button>
						   <p id="link_article_error"></p>
                          </div>
						 
                      </div>
					   <div class="qnsans" id="article_title_div"  <?php if(is_null(@$get_resources_details['article_id']) ) { ?>  style="display:none" <?php  } ?>>
                     
                          <div class="qns"><label>Article Title</label></div>
                          <div class="ans">
                          	<label id="get_article_title" for="get_article_title"><?php if(isset($get_resources_details['articletitle']) && $get_resources_details['articletitle'] != '' ){ echo strip_tags($get_resources_details['articletitle']); } echo set_value('get_article_id'); ?></label>
                           	<input type="hidden" name="hiddn_article_title" id="hiddn_article_title" />
                            
                            <input type="hidden" name="hiddn_article_id" id="hiddn_article_id" value="<?php if(isset($get_resources_details['article_id']) && $get_resources_details['article_id'] != '' ){ echo $get_resources_details['article_id']; } echo set_value('hiddn_article_id'); ?>" />
                            
                           </div>
                      </div> 
					  -->
					  
                      <div class="qnsans">
                          <div class="qns">
	  <label>Display Image<span style="color:#F00">*</span></label></div>
                          <div class="ans">
		 <div class="article_image BorderBoxNone margin-top-0">
          
            <a class="set_image margin-left-0 <?php if((isset($get_resources_details['image_details']['image_id']) && $get_resources_details['image_details']['image_id'] != '') ) echo " "; else  echo  " BorderRadius3"; ?>" id="resource_image_set"  href="#" data-remodal-target="imagemodal" ><?php if((isset($get_resources_details['image_details']['image_id']) && $get_resources_details['image_details']['image_id'] != '') ) echo "Change Image"; else  echo "Set Image"; ?></a>
            
			 <a id="edit_resource_image" rel="resource" class="del_image delbtn_border" href="javascript:void(0);" <?php if((isset($get_resources_details['image_details']['image_id']) && $get_resources_details['image_details']['image_id'] != '') ) { ?>  <?php } else {  ?> style="display:none;"  <?php  } ?>><i class="fa fa-pencil"></i></a>
			
           <a id="delete_resource_image" rel="resource" class="del_image" href="javascript:void(0);" <?php if((isset($get_resources_details['image_details']['image_id']) && $get_resources_details['image_details']['image_id'] != '') ) { ?>  <?php } else {  ?>   style="display:none;" <?php  } ?> ><i class="fa fa-trash-o"></i></a> 
		    
              <input type="hidden" class="image-group" name="imgResourceId" rel="<?php echo @$get_resources_details['image_details']['imagecontent_id']; ?>" value="<?php if(isset($get_resources_details['image_details']['image_id'])) echo $get_resources_details['image_details']['image_id']; echo set_value('imgHomeImageId'); ?>" id="resource_image_gallery_id">
              
           
                      
       	 	</div>
			 <p id="image_error"></p>
  

	<div class="ArticleImageContainer1" id="resource_image_container" style="float:none; width:200px;<?php if(!isset($get_resources_details['image_details']['image_id'])) { echo "display:none;"; } else { echo "display:block;"; } ?>">
      
            <img id="resource_image_src" src="<?php if(isset($get_resources_details['image_details']['image_id']) && $get_resources_details['image_details']['image_id'] != '') { echo $get_resources_details['image_details']['image'];  } ?>"/>
			<label>Caption :</label>
			<input type="text" id="resource_image_caption" name="resource_image_caption" value="<?php echo htmlentities(@$get_resources_details['image_details']['caption']);   ?>" class="margin-top-5 WidthFull valid" aria-invalid="false">
			<label>Image Alt :</label>
			<input type="text" id="resource_image_alt" name="resource_image_alt" value="<?php echo htmlentities(@$get_resources_details['image_details']['alt_tag']);   ?>" class="margin-top-5 WidthFull valid" aria-invalid="false">
			<label>Image Name :</label>
			<input type="text" id="resource_physical_name" name="resource_physical_name" physical_extension="<?php echo @$get_resources_details['image_details']['physical_extension'];   ?>" value="<?php echo @$get_resources_details['image_details']['physical_name'];   ?>" class="margin-top-5 WidthFull valid" aria-invalid="false" <?php if(isset($get_resources_details['image_details']['image_id'])) { ?> readonly <?php } ?> >
          </div>
                          </div>
                      </div>
                  
                    
                </div>
            </div>
		</form>        
    </div>                            
</div>


	 <div class="remodal" data-remodal-id="imagemodal" data-remodal-options="hashTracking: false" style="position:relative;">
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
			<input type="hidden" id="content_type" name="content_type" value="6">
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
		

<div class="remodal" data-remodal-id="modal1" data-remodal-options="hashTracking: false" style="position:relative;">
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
                            <input type="text" placeholder="Start Date" id="date_timepicker_start" readonly value="">
                            <input type="text" placeholder="End Date" id="date_timepicker_end" readonly value="">
                            </p>
                        </li>
                </ul>
                
                <div class="FloatLeft TableColumn">  
                    <div class="FloatLeft w2ui-field">
                            <select  id="article_section"  class="controls">
						   <option value="">Section</option>
											<?php if(isset($section_mapping)) { 
													 foreach($section_mapping as $mapping) {  ?>
												<option  style="color:#933;font-size:18px;" class="blog_option" value="<?php echo $mapping['Section_id']; ?>"><?php echo $mapping['Sectionname']; ?> </option>
											<?php if(!(empty($mapping['sub_section']))) { ?>
													
													  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
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
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; </script>
<script src="<?php echo base_url(); ?>js/jquery.remodal.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/additional-methods.min.js"></script>	
<script type="text/javascript" src="<?php echo base_url();?>js/resources.js"></script>
<script type="text/javascript">
function get_date(input) {
if(input == '') {
return false;
}	else {
// Split the date, divider is '/'
var parts = input.match(/(\d+)/g);
return parts[2]+'/'+parts[1]+'/'+parts[0];
} 
}

$(document).ready(function(){
 $('#date_timepicker_start').datetimepicker({
  format:'d-m-Y',
  onShow:function(ct){
   this.setOptions({
	   maxDate:get_date($('#date_timepicker_end').val())?get_date($('#date_timepicker_end').val()):false,
   })
  },
  timepicker:false
 });
 $('#date_timepicker_end').datetimepicker({
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

<!-- Mansory & Infinite Scroll Script -->
<script src="<?php echo base_url(); ?>js/jquery-1.7.1.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.masonry.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.infinitescroll.min.js"></script>
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
          img: '<?php echo base_url(); ?>images/admin/loadingimage.gif',
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
		
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
	
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
		
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
	
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
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
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