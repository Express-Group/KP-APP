	<link href="<?php echo base_url(); ?>css/tag/bootstrap.css" rel="stylesheet" type="text/css" />	
	<link href="<?php echo base_url(); ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <style>
	.error {
		color:red;
	}
	.DropDownWrapper {
		min-height:500px;
	}
	.ImageTab {
		margin-top:50px;
	}
	</style> 
<?php
	if(isset($get_image_details['status'])) {
		$status 		= $get_image_details['status'];
		$get_content_id = $get_image_details['content_id'];
		
		$publishdate  = "";
		$unpublishdate  = "";
		
		switch($status )
		{
			case 1:
				$publishdate  = date('d-m-Y H:i:s',strtotime($get_image_details['Modifiedon']));
				$print_status = "Active | Activated on : ".$publishdate;
				break;
			case 0:
				$unpublishdate  = date('d-m-Y H:i:s',strtotime($get_image_details['Modifiedon']));
				$print_status = "Inactive | Inactivated on : ".$unpublishdate;
				break;
		}
		
	}

 ?>

<?php if($page_name != 'edit_image') { ?>
<form action="<?php echo base_url().folder_name;?>/image/add_new_image" method="post" name="imagelibrary" id="imagelibrary" enctype="multipart/form-data"  >
<?php } else { ?>
<form action="<?php echo base_url().folder_name;?>/image_manager/update_image/<?php echo $get_image_details['content_id']; ?>" method="post" name="imagelibrary" id="imagelibrary" enctype="multipart/form-data"  >
<?php } ?>

<input type="hidden" name="txtStatus" id="status_id" value=1 />
<input type="hidden" name="txtImageData" id="image_data" value="" />

<div class="Container" style="width:1170px;">
      <div class="BodyWhiteBG">
    <div class="BodyHeadBg Overflow clear">
	
<div class="FloatLeft BreadCrumbsWrapper">
        <div class="breadcrumbs"><a href="javascript:void(0);">Dashboard</a> > <a href="javascript:void(0);"><?php if($page_name == 'edit_image') echo "Edit Image"; else echo "Create Image"; ?></a></div>
 <h2 class="FloatLeft"><?php if($page_name == 'edit_image') echo "Edit Image"; else echo "Create Image"; ?></h2>
 </div>
 
           
<div class="FloatLeft SessionSuccess" id="flash_msg_id" style="display:none;top: 145px;">
<?php if($this->session->flashdata('msg')){ 
echo $this->session->flashdata('msg');
} ?></div>

<p class="FloatRight save-back save_margin article_save">
 
 <a class="back-top FloatLeft" href="<?php echo base_url().folder_name; ?>/image_manager" title="Go Back"><i class="fa fa-reply fa-2x"></i></a>

<button type="button" class="btn btn-primary FloatRight i_button" id="inactive_id"  title="Inactive"><i class="fa fa-times"></i></button>

<button type="button" class="btn btn-primary FloatRight i_button"  id="active_id" title="Save/Active" ><i class="fa fa-check" style="color:#fff;"></i>
</button>

</p> 
        
        
<?php if(isset($print_status) && $print_status != "")  { ?>
 
 <span class="TopStatus" id="TopStatusId"><?php echo "Status : ".$print_status; ?></span>
 
 <?php } ?>
         
  </div>
  
    <div class="Overflow DropDownWrapper">
    
       
          <section class="ImageTab">
		  <div <?php if($page_name == 'edit_image') {  ?> style="display:none;" <?php } ?>>
		  <label> Upload : </label>
                  <a href="#" data-remodal-target="modal1" id="BrowsePopup" class="fileUpload btn btn-primary">Browse</a>
				  </div>
		  
            <div class="CropperWrapper" id="crop_container" <?php if($page_name != 'edit_image') {  ?> style="display:none;" <?php } ?>>
			
			<table width="100%" cellspacing="0" class="display article_table dataTable no-footer gallerydatatable" id="link_preview_table" role="grid" aria-describedby="link_preview_table_info" style="width: 100%;">
				<thead id="link_preview_head" style="">
				
					<tr role="row">
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-label="Type: activate to sort column ascending">Image</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">Caption</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">Alt Tag</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-sort="ascending" aria-label="">Image Name</th>
					<th class="sorting_disabled" tabindex="0" aria-controls="link_preview_table" rowspan="1" colspan="1" style="width: 179px;" aria-label="Action: activate to sort column ascending">Action</th>
					
					</tr>
				</thead>
			<?php if(isset($temp_images)) {  
			$Count = 0;
			?>
					<tbody id="link_preview_body">
				 <?php foreach($temp_images as $key=>$values)  { 
				 if($Count%2 == 0)
					$Class = 'class = "odd" role="row"';
				else 
					$Class = 'class = "even" role="row"';
				
				$Physical_array = explode('.', $values->image_name);
				 ?>
						<tr <?php echo $Class; ?> id='gallery_image<?php echo $values->imageid; ?>'>
						<td><img class="gallery_dataimage" src='<?php echo image_url.imagelibrary_temp_image_path.$values->image_name; ?>'/></td>
						
						<td><div align='center'><input type="textbox" style='width:200px; text-align:center;'  name="image_caption" id="image_caption_id" rel="<?php echo $values->imageid; ?>" value='<?php  echo str_replace("'",'"',$values->caption);  ?>' /></div></td>
						
						<td><div align='center'><input type="textbox" style='width:200px; text-align:center;'  name="image_alt" id="image_alt_id" rel="<?php echo $values->imageid; ?>" value='<?php  echo str_replace("'",'"',$values->alt_tag); ?>' /></div></td>
						
						<td><div align='center'><input type="textbox" style='width:100%; text-align:center;'  name="physical_name" id="physical_name" rel="<?php echo $values->imageid; ?>" physical_extension="<?php echo $Physical_array[1]; ?>" value='<?php echo str_replace("'",'"',$values->physical_name); ?>' /></div><span class='error' id="error_<?php echo $values->imageid; ?>"></span></td>
						
						<td><div class='article_table_delete'>  <a  id='edit_image' rel='<?php echo $values->imageid; ?>' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><!--<a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' id='deletetempimage' rel='<?php echo $values->imageid; ?>' ><i class='fa fa-trash-o'></i></a>--></div></td>
						
						</tr>
				 <?php $Count++; }  ?>
					</tbody>
			<?php } ?>
			</table>
			    
              </div>
           
      </section>
    
        </div>
  </div>
    </div>
    
   
    
    </form>
    
     <div class="remodal" data-remodal-id="modal1" data-remodal-options="hashTracking: false" style="position:relative;">
            <div class="article_popup GalleryPopup">
            <div class="article_popup1">
            <ul class="article_popup_tabs">
            <li onclick="articleUpload()" class="active img_upload img_upload">From Local System</li>
            </ul>
            </div>
            <div class="article_popup2">
            <div class="article_upload">
       
          <form  name="ImageForm" id="ImageForm" action="<?php echo base_url().folder_name; ?>/image/multiple_image_upload" method="POST" enctype="multipart/form-data">
		   <input type="hidden" name="content_type" value="2" />
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
				<div class="ImageContainer" id="ImageContainerSet">
			  
				</div>
            <div class="FloatRight popup_insert">
			<button type="button" class="btn btn-primary"id="add_to_edit" >Add</button>
            </div>
            </div>
            </div>
            </div>
            
<script type="text/javascript"> 

var base_url = '<?php echo base_url(); ?>';

var SelectImage = {};
<?php if(isset($temp_images)) { 
foreach($temp_images as $image) { 

$Caption = str_replace("'",'"',$image->caption);
$Alt 	 = str_replace("'",'"',$image->alt_tag);

	$Physical_array = explode('.', $values->image_name);

if($image->imagecontent_id != '') { ?>

  					 SelectImage["<?php echo $image->imageid; ?>"] = {
					  'image_id' 		: "<?php echo $image->imageid; ?>",
					  'image_caption' 	: '<?php echo $image->caption; ?>',
					  'image_alt' 		: '<?php echo $image->alt_tag; ?>',
					  'physical_name' 		: '<?php echo $image->physical_name; ?>',
					  'physical_extension' 		: '<?php echo $Physical_array[1]; ?>',
					  'image1_type'		: "<?php echo $image->image1_type; ?>",
					  'image2_type'		: "<?php echo $image->image2_type; ?>",
					  'image3_type'		: "<?php echo $image->image3_type; ?>",
					  'image4_type'		: "<?php echo $image->image4_type; ?>",
					  'imagecontent_id' : "<?php echo $image->imagecontent_id; ?>"
					  };
					  
			 $("#image_data").val(JSON.stringify(SelectImage));
	
<?php } else { ?>

					 SelectImage["<?php echo $image->imageid; ?>"] = {
					  'image_id' 		: "<?php echo $image->imageid; ?>",
					  'image_caption' 	: '<?php echo $image->caption; ?>',
					  'image_alt' 		: '<?php echo $image->alt_tag; ?>',
					  'physical_name' 		: '<?php echo $image->physical_name; ?>',
					  'physical_extension' 		: '<?php echo $Physical_array[1]; ?>',,
					  'image1_type'		: "<?php echo $image->image1_type; ?>",
					  'image2_type'		: "<?php echo $image->image2_type; ?>",
					  'image3_type'		: "<?php echo $image->image3_type; ?>",
					  'image4_type'		: "<?php echo $image->image4_type; ?>"
					   };
					 				
			 $("#image_data").val(JSON.stringify(SelectImage));
<?php } } } ?>


console.log(SelectImage);

</script>

	<script src="<?php echo base_url(); ?>js/jquery.form.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/jquery.remodal.js" type="text/javascript" ></script>
	<script src="<?php echo base_url(); ?>js/image_process.js" type="text/javascript"></script>