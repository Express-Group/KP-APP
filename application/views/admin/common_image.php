	<link href="<?php echo base_url(); ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>css/admin/tag/bootstrap.css" rel="stylesheet" type="text/css" />	
    <link href="<?php echo base_url(); ?>css/admin/cropper.css"  rel="stylesheet" type="text/css" >
    <link href="<?php echo base_url(); ?>css/admin/main.css"  rel="stylesheet" type="text/css" >
    <link href="<?php echo base_url(); ?>css/admin/elastislide.css"  rel="stylesheet" type="text/css">
    <link rel="stylesheet"  href="<?php echo base_url(); ?>css/admin/jquery-ui-autocomplete.css" type="text/css" /> 
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/admin/jquery-ui-custom.css">
	<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	</script>

<?php $PageName = $this->uri->segment(2); ?>

<input type="hidden" name="page_name" id="page_name" value="<?php echo @$PageName; ?>" />

<div class="Container" style="width:1170px;">
      <div class="BodyWhiteBG">
    <div class="BodyHeadBg Overflow clear">
        <div class="breadcrumbs"><a href="javascript:void(0);">Dashboard</a> > <a href="javascript:void(0);"><?php  echo "Resize & Crop Image"; ?></a></div>
 <h2 class="FloatLeft"><?php echo "Resize & Crop Image"; ?></h2>

<p class="FloatRight save-back save_margin article_save">

<button type="button" class="btn btn-primary FloatRight i_button" id="inactive_id"  title="Cancel"><i class="fa fa-times"></i></button>

<button type="button" class="btn btn-primary FloatRight i_button"  id="active_id" title="Save" ><i class="fa fa-floppy-o"></i></i>
</button>

</p> 
        
  </div>
  
    <div class="Overflow DropDownWrapper">
     <div class="form_list_new">
	 <p>Select size to be used  for resizing original image</P>
	   <div class="CroppingSize">
            <input type="checkbox" name="image_resize" id="image_resize_id" class="WidthAuto margin-right-10" value="image_600_390" <?php if($last_crop_image['imgtype600X390'] != 2) { echo "checked"; } ?> /> <label>600px X 390px</label>
			<?php if($this->uri->segment(2) != 'gallery_image_processing') { ?>
          <input type="checkbox" name="image_resize" id="image_resize_id" class="WidthAuto" value="image_600_300" <?php if($last_crop_image['imgtype600X300'] != 2) { echo "checked"; } ?>  /> <label>600px X 300px</label>
		
		  <input type="checkbox" name="image_resize" id="image_resize_id" class="WidthAuto" value="image_150_150"  <?php if($last_crop_image['imgtype100X65'] != 2) { echo "checked"; } ?>  /> <label>150px X 150px</label>
			<?php } ?>
		  
		    <input type="checkbox" name="image_resize" id="image_resize_id" class="WidthAuto" value="image_100_65" <?php if($last_crop_image['imgtype150X150'] != 2) { echo "checked"; } ?>  /> <label>100px X 65px</label>
            </div>
        
        
        <ul class="tabs GalleryTabs" >
        <li class="selected"><a href="#view1" >600px X 390px</a></li> 
		<?php if($this->uri->segment(2) != 'gallery_image_processing') { ?>
        <li class=""><a href="#view2" >600px X 300px</a></li>
        <li class=""><a href="#view3">150px X 150px</a></li>
		<?php } ?>
		<li class=""><a href="#view4">100px X 65px</a></li>
        </ul>
		
		
		<section class="GalleryTabsContent">
              <div class="tabcontents margin-top-10">
            
            <div id="view1" style="display: block;">
			 <div class="AutoCropper">
				<img id="resize_image_content" style="width:600px;height:390px;" <?php if(!isset($last_crop_image['image_binary_file600X390']) || $last_crop_image['image_binary_file600X390'] == '' || $last_crop_image['image_binary_file600X390'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file600X390'])) { echo $last_crop_image['image_binary_file600X390']; } ?>"  />
				</div>
			</div>
			<?php if($this->uri->segment(2) != 'gallery_image_processing') { ?>
            <div id="view2" style="display:none;">
			 <div class="AutoCropper">
				<img id="resize_image_content"  style="width:600px;height:300px;" <?php if(!isset($last_crop_image['image_binary_file600X300']) || $last_crop_image['image_binary_file600X300'] == '' || $last_crop_image['image_binary_file600X300'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file600X300'])) { echo $last_crop_image['image_binary_file600X300']; } ?>"  />
				</div>
			</div>
			<div id="view3" style="display:none;">
			 <div class="AutoCropper">
				<img id="resize_image_content"  style="width:150px;height:150px;" <?php if(!isset($last_crop_image['image_binary_file150X150']) || $last_crop_image['image_binary_file150X150'] == '' || $last_crop_image['image_binary_file150X150'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file150X150'])) { echo $last_crop_image['image_binary_file150X150']; } ?>" />
				</div>
			</div>
			<?php } ?>
            <div id="view4" style="display:none;">
			 <div class="AutoCropper">
				<img id="resize_image_content"  style="width:100px;height:65px;" <?php if(!isset($last_crop_image['image_binary_file100X65']) || $last_crop_image['image_binary_file100X65'] == '' || $last_crop_image['image_binary_file100X65'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file100X65'])) { echo $last_crop_image['image_binary_file100X65']; } ?>" />
				</div>
			</div>
			</div>
			</section>
       
          <section class="GalleryTabsContent BorderBoxNone margin-top-0">
              <div class="tabcontents img_tab_contents">	
            
            <div class="img_gallery">
            
             <div align="center" style="display:none;" id="loadingcrop"><img src="<?php echo base_url();?>images/loadingimage.gif" style="border:none; width:30px; height:30px;" /><br />
			Please wait while we cropping the image.......
            </div>
            
            <div align="center" id="imageuploading" style="display:none;">Loading Please wait</div>
              

          <div id="error_uploaduserimage" class="mandatory"></div>
          <div align="center" style="display:none;" id="loadingdragimage"><img src="<?php echo base_url();?>images/loadingimage.gif" style="border:none; width:30px; height:30px;" /><br />
			Please wait while we add the images.......
            </div>
            </div>
            
           <div class="CropperWrapper" id="crop_container" <?php if(isset($last_crop_image['imgsrc']) && $last_crop_image['imgsrc'] != '') {} else{ ?> style="display:none;" <?php } ?>>
		    <p>Select the size to crop the particular part of the image and use it as article image</P>
            <div class="CroppingSize">
            <input type="radio" name="image_type" id="image_type_id" class="WidthAuto margin-right-10" value="image_600_390" checked /> <label>600px X 390px</label>
			<?php if($this->uri->segment(2) != 'gallery_image_processing') { ?>
          <input type="radio" name="image_type" id="image_type_id" class="WidthAuto" value="image_600_300" /> <label>600px X 300px</label>
		
		  <input type="radio" name="image_type" id="image_type_id" class="WidthAuto" value="image_150_150" /> <label>150px X 150px</label>
			<?php } ?>
		    <input type="radio" name="image_type" id="image_type_id" class="WidthAuto" value="image_100_65" /> <label>100px X 65px</label>
            </div>
            <div class="tools_pack">
			
                  
                   
                <div align="center" id="cropimageloading" style="display:none;"><img src="<?php echo base_url();?>images/loadingimage.gif" border="0"  style="width:64px; height:64px;" /><br />
Loading
</div>
				  <div id="imagedatasection"> 
                    
    <div class="edit_image">
	 <div class="edit_tools">
     
     
		 
                <div id="rightsidescrollimagecontainer">
            	 <div id="loadingrightimage" align="center" style="display:none;">
            <img src="<?php echo base_url();?>images/loadingroundimage.gif" border="0" style="width:18px; height:18px;" /><br /> loading</div>
            <?php if(isset($temp_images)) { ?>
         
                <?php } ?>
            </div>
             <div class="docs-buttons"> 
     
                    <div class="btn-group WidthPercentage">
                    
                    <button  class="btn btn-primary" data-method="zoom" data-option="0.1" type="button" title="Zoom In"> <span class="docs-tooltip" data-toggle="tooltip"> <i class="fa fa-search-plus"></i> </span> </button>
                    <button class="btn btn-primary" data-method="zoom" data-option="-0.1" type="button" title="Zoom Out"> <span class="docs-tooltip" data-toggle="tooltip"> <i class="fa fa-search-minus"></i> </span> </button>
                    <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button" title="Rotate Left"> <span class="docs-tooltip" data-toggle="tooltip" > <i class="fa fa-reply"></i> </span> </button>
                    <button  class="btn btn-primary" data-method="rotate" data-option="45" type="button" title="Rotate Right"> <span class="docs-tooltip" data-toggle="tooltip" > <i class="fa fa-share"></i> </span> </button>
                    <button class="btn btn-primary" type="button" id="reset_image" title="Reset"> <span class="docs-tooltip" data-toggle="tooltip"> <i class="fa fa-refresh"></i> </span> </button>
                    <button  class="btn btn-primary" data-method="getCroppedCanvas" type="button"><span class="docs-tooltip" data-toggle="tooltip"><i class="fa fa-desktop"></i></span></button>
                   <button class="btn btn-primary SaveCrop"  type="button" title="Apply" id="apply_crop"  ><span class="docs-tooltip"> Crop </span> </button>
				    
                 <!--  <button class="btn btn-primary SaveCrop floatright"  type="button" title="Delete" id="deletetempimage"  > <span class="docs-tooltip" data-toggle="tooltip" > <i class="fa fa-trash-o"></i> Delete</span></button>  
				 -->
          
           
          <div class="modal fade docs-cropped cropper-modal-fade" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
          <div class="modal-dialog cropper-modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button  class="close" data-dismiss="modal" type="button" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
              </div>
              <div class="modal-body"></div>
             </div>
          </div>
        </div>
          
                        </div>
                  </div>
				   </div>
				    
        <div class="img-container">
         <img id="crop_image_src" src="<?php echo $last_crop_image['imgsrc'];?>"  class="rotatecontrol"  border="0"   />
        </div></div>
		
                 </div>  
                           
                </div>
					
                <div class="PreviewCroppped">
                <h4>Preview :</h4>
                      <figure class="GalleryImageSize1">
                      <p>Size : 600px X 390px</p>	
                     <img id="image_600_390" class="MainImage1" <?php if(!isset($last_crop_image['image_binary_file600X390']) || $last_crop_image['image_binary_file600X390'] == '' || $last_crop_image['image_binary_file600X390'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file600X390'])) { echo $last_crop_image['image_binary_file600X390']; } ?>" >	
                     </figure>
					 <?php if($this->uri->segment(2) != 'gallery_image_processing') { ?>
                     <figure  class="GalleryImageSize1">
                     <p>Size : 600px X 300px</p>
                         <img id="image_600_300" class="MainImage2" <?php if(!isset($last_crop_image['image_binary_file600X300']) || $last_crop_image['image_binary_file600X300'] == '' || $last_crop_image['image_binary_file600X300'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file600X300'])) { echo $last_crop_image['image_binary_file600X300']; } ?>" > 
						 </figure>
                         <figure  class="GalleryImageSize1">
                         <p>Size : 150px X 150px</p>
						   <img id="image_150_150" class="SquareImage" <?php if(!isset($last_crop_image['image_binary_file150X150']) || $last_crop_image['image_binary_file150X150'] == '' || $last_crop_image['image_binary_file150X150'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file150X150'])) { echo $last_crop_image['image_binary_file150X150']; } ?>" > 
						    </figure>
					 <?php } ?>
                            <figure class="GalleryImageSize1">
                            <p>Size : 100px X 65px</p>
                            <img id="image_100_65" class="ThumbImage" <?php if(!isset($last_crop_image['image_binary_file100X65']) || $last_crop_image['image_binary_file100X65'] == '' || $last_crop_image['image_binary_file100X65'] == 'NULL') { ?> style="display:none;" <?php } ?> src="<?php if(isset($last_crop_image['image_binary_file100X65'])) { echo $last_crop_image['image_binary_file100X65']; } ?>" > 
              </figure>
                  	</div>
                
                <div class="img_table_3">
                      
                     <table>
                     
                    <tr>
                          <td><label>Image&nbsp;Alt :&nbsp;&nbsp; </label></td>
						  <td><label><?php echo @$last_crop_image['image_alt'];?></label></td>
                         
                          <input type="hidden" id="crop_image_id" value="<?php echo $last_crop_image['imageid'];?>" />
						   <input type="hidden" id="image_content_id" value="<?php echo $last_crop_image['imagecontent_id'];?>" />
                           <input type="hidden" id="crop_data" value="" />
						     <input type="hidden" id="crop_alt" value="<?php echo @$last_crop_image['image_alt'];?>" />
							   <input type="hidden" id="crop_caption" value="<?php echo @$last_crop_image['image_caption'];?>" />
                         
                        </tr>

                    <tr>
                          <td><label>Caption :&nbsp;&nbsp;</label></td>
                          <td><label ><?php echo @$last_crop_image['image_caption'];?> </label></td>
                        </tr>
                    <tr> 
      </tr>
      </table>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
    </div>
  </div>
  
	<script src="<?php echo base_url(); ?>js/tabcontent.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/cropper.js"  type="text/javascript" ></script>
	 <script src="<?php echo base_url(); ?>js/main.js"  type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/bootstrap/bootstrap.min.js"  type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/image.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function () {
	
	options = {};
	crop_box_size = {'left': 50,'top':50,'height':390,'width':600};
	
	 options.built = function () {
         $('.img-container > img').cropper('setCropBoxData', crop_box_size);
      };

	options['dragCrop'] 		= false;	
	options['cropBoxResizable'] = false;
	
	/*options['minContainerWidth'] 	= 951;
	options['minContainerHeight'] 	= 650;
	options['minCanvasWidth'] 		= 951;
    options['minCanvasHeight'] 		= 650; */
	$('.img-container > img').cropper('destroy');
	 $('.img-container > img').cropper('destroy').cropper(options);
	
	
 });
</script>  