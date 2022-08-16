 <div class="transitions-enabled infinite-scroll clearfix"  id="image_lists_id">
            <?php if(isset($image_library)) { 
			foreach($image_library as $image) { 
			
			if(file_exists(source_base_path.imagelibrary_image_path.$image->ImagePhysicalPath)) {
			
			?>
                    <img  id="image_lists_images_id" data-content_id="<?php echo $image->content_id; ?>" data-image_path="<?php echo $image->ImagePhysicalPath; ?>" data-image_caption="<?php echo $image->ImageCaption; ?>" data-image_alt="<?php echo $image->ImageAlt; ?>" data-image_date="<?php echo $image->Modifiedon; ?>"  data-image_source="<?php echo image_url.imagelibrary_image_path.$image->ImagePhysicalPath; ?>"  src="<?php echo image_url.imagelibrary_image_path.$image->ImagePhysicalPath; ?>" />
			<?php }  } } ?>
            </div>
			<nav id="page-nav">
			  <a href="article_image/get_image_library_scroll/<?php echo $nextpages; ?>"></a>
			</nav>
			