<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color      = $content['widget_bg_color'];
$widget_custom_title  = $content['widget_title'];
$widget_instance_id   = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	  = "";
$widget_section_url   = $content['widget_section_url'];
$is_home              = $content['is_home_page'];
$view_mode            = $content['mode'];
$max_article          = $content['show_max_article'];
$render_mode          = $content['RenderingMode'];

// widget config block ends
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();

$content_details = array();
if($render_mode == "manual")
{
	$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article); 	
	//print_r($widget_instance_contents[0]['content_type_id']);exit;
	$content_details = array();
	if(count($widget_instance_contents)>0){
	$content_id = $widget_instance_contents[0]['content_id'];
	$content_type = $widget_instance_contents[0]['content_type_id'];  // manual article content type
	if($content_type!=1)
	{
	$content_details = $this->widget_model->get_section_top_story($content_id, $content_type, $content['sectionID'], $content['mode']);
	}
	}
	
}
else
{
	$section_details = $this->widget_model->get_section_by_id($content['page_param']); // live db
	if (strpos(strtolower($section_details['URLSectionStructure']), 'galleries') !== false) 
	{
    	$content_type = 3;
		$gallery_id = $this->widget_model->get_section_top_story('', $content_type, $content['sectionID'], $content['mode']);
		if(count($gallery_id) > 0)
		{
	 		$content_details = $this->widget_model->get_section_top_story($gallery_id[0]['content_id'] , $content_type, '', $content['mode']);
		}
    }else
	{
	$content_type = 4;
	 $content_details = $this->widget_model->get_section_top_story("" , $content_type, $content['sectionID'], $content['mode']);
	}
	if(count($content_details) > 0)
 	  $content_id = $content_details[0]['content_id'];
	 else
	 	$content_id = '';
}


$widget_contents = $content_details;

if($content['RenderingMode'] == "manual")
{
	$widget_contents = array();
	if (function_exists('array_column')) 
	{
		$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
	}
	else
	{
		$get_content_ids = array_map( function($element) { return $element['content_id']; }, $widget_instance_contents);
	}
	foreach ($widget_instance_contents as $key => $value) 
	{
		foreach ($content_details as $key1 => $value1) 
		{
			if($value['content_id']==$value1['content_id'])
			{
				$widget_contents[] = array_merge($value, $value1);
			}
		}
	}
}

if(count($widget_contents)>0){

	if($content['RenderingMode'] == "manual")
	{
		if($widget_contents[0]['CustomTitle'] != "")
			$content_title  = stripslashes(preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $widget_contents[0]['CustomTitle']));
		else
			$content_title  = stripslashes(preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $widget_contents[0]['title']));
	}
	else	 
	{
		$content_title  = stripslashes(preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $widget_contents[0]['title']));
	}

$get_gallery_images	 = $widget_contents;
$article_url = base_url().$content_details[0]['url'];
$js_path 		= image_url."js/FrontEnd/"; 

$param = $content['close_param'];
$live_article_url = $article_url.$param;


?>
<?php if($content_type==3){ ?>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features">
<?php if(count($get_gallery_images)> 1){ ?>
<div class="text-center play-pause-icon">
  <span id="auto-play" class="cursor-pointer"><i class="fa fa-play"></i>
</span></div>
<?php } ?>

 <h1 class="ArticleHead" id="content_head" itemprop="name"><a  href="<?php echo $live_article_url; ?>" class="article_click" ><?php echo $content_title;?></a></h1>
  <div class="slide GalleryDetail section_top_gallery GalleryDetailSlide" style="width:100% !important">
    <?php 
	$width = '';
	foreach($get_gallery_images as $gallery_image){ 
				  
                  if($content['RenderingMode'] == "manual")
				  {
					  	if($content['mode'] == 'live')
				  		{
							$gallery_caption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallery_image['gallery_image_title']);
							$gallery_alt =  $gallery_image['gallery_image_alt'];
							$image_path = $gallery_image['gallery_image_path'];
						}
						else
						{
							$gallery_caption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallery_image['ImageCaption']);
							$gallery_alt =  $gallery_image['ImageAlt'];
							$image_path = $gallery_image['ImagePhysicalPath'];
						}
				  }
				  else
				  {
						if($content['mode'] == 'live')
				  		{
							$gallery_caption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallery_image['gallery_image_title']);
							$gallery_alt =  $gallery_image['gallery_image_alt'];
							$image_path = $gallery_image['gallery_image_path'];
						}
						else
						{
							$gallery_caption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallery_image['ImageCaption']);
							$gallery_alt =  $gallery_image['ImageAlt'];
							$image_path = $gallery_image['ImagePhysicalPath'];
						}

				  }
				  
				  if (get_image_source($image_path, 1) && $image_path != '')
					{
				  $imagedetails = get_image_source($image_path, 2);
					$imagewidth = $imagedetails[0];
                    $imageheight = $imagedetails[1];
					if ($imageheight > $imagewidth)
					{
						$Image600X390 	= $image_path;
						$width          = "";
					}
					else
					{				
						
						$Image600X390 	= str_replace("original","w600X390", $image_path);
						$width          = "";
					}
																	
					
						$show_gallery_image = image_url. imagelibrary_image_path . $Image600X390;
						
					}
					else {

						$show_gallery_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
					}
					
                  ?>
    <div class="item">
      <figure class="PositionRelative"><a  href="<?php echo $live_article_url; ?>" class="article_click" > <img src="<?php echo $show_gallery_image;?>" style="width:<?php echo $width;?>;" title="<?php echo $gallery_caption;?>" alt="<?php echo $gallery_alt;?>"></a>
      <?php if($gallery_caption!='') { ?>
        <div class="TransLarge Font14"><?php echo $gallery_caption;?></div>
        <?php } ?>
      </figure>
    </div>
    <?php 
					 } ?>
  </div>
  	<?php if(count($get_gallery_images)> 1){ ?>
  <div class="text-center" style="display:none;">
    <ul class="gallery_pagination" id="gallery_pagination">
    </ul>
  </div>
  <script src="<?php echo $js_path; ?>js/bootstrap-paginator.js" type="text/javascript"></script>
  <script>
	var Gallery_pagination_count = "<?php echo count($get_gallery_images);?>";
  </script>
<?php }else{ ?>
<script>
var Gallery_pagination_count = 0;
</script>
<?php } ?>
</div>
</div>
    <?php } elseif($content_type==4){ 
	 $video_scipt       = htmlspecialchars_decode(stripslashes($content_details[0]['VideoScript']));
	 $video_description = $content_details[0]['summaryHTML'];
					
	?>
    <div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h1 class="ArticleHead" id="content_head" itemprop="name"><a  href="<?php echo $live_article_url; ?>" class="article_click" ><?php echo $content_title;?></a></h1>
    <div class="GalleryDetail section_top_video"> <?php echo $video_scipt;?>
      <p> <?php echo $video_description;?></p>
    </div>
  </div></div>
    <script>
	var Gallery_pagination_count = 0;
  </script>

	<?php } ?>
      <?php 
     		$allow_social_btn= 1; 
			$email_shared = 0; 
			if ($email_shared > 999 && $email_shared <= 999999) {
				$email_shared = round($email_shared / 1000, 1).'K';
			} else if ($email_shared > 999999) {
			  $email_shared = round($email_shared / 1000000, 1).'M';
			} else {
				$email_shared = $email_shared;
			} ?>
            <script src="<?php echo $js_path; ?>js/jquery.csbuttons.js" type="text/javascript"></script>
<script src="<?php echo $js_path; ?>js/section_top-article.js"></script>

<script>
var content_url     = "<?php echo $article_url; ?>";
$(document).ready(function(){
	$('.csbuttons').cSButtons({total : "#total","url" 	: "<?php echo $article_url;?>",});
	$('.PrintSocial.SectionTopStory').css('visibility','visible').hide().fadeIn("slow");
	setTimeout(function(){if($('.csbuttons-count').eq(3).text()=="NaN"){
	$('.csbuttons-count').eq(3).text(0);
	}}, 500);
});
</script>
	<div class="Social_Fonts" >
      <div class="PrintSocial SectionTopStory" style="visibility:hidden;"> <span class="Share_Icons PositionRelative"><i class="fa fa-envelope-o fa_social" id="popoverId"></i><span class="csbuttons-count"><?php echo $email_shared;?></span></span> <span class="Share_Icons"><a href="javascript:;" class="csbuttons" data-type="twitter" data-txt="<?php echo strip_tags($content_title);?>" data-via="NewIndianXpress" data-count="true"><i class="fa fa-twitter fa_social"></i></a></span> <span  class="Share_Icons"><a href="javascript:;" class="csbuttons" data-type="facebook" data-count="true"><i class="fa fa-facebook fa_social"></i></a></span> <span class="Share_Icons"><a href="javascript:;" class="csbuttons" data-type="google" data-lang="en" data-count="true"><i class="fa fa-google-plus fa_social"></i></a></span>
        <div id="popover-content" class="popover_mail_form fade right in ">
          <div class="arrow"></div>
          <h3 class="popover-title">Share Via Email</h3>
          <div class="popover-content">
            <form class="form-inline Mail_Tooltip" action="<?php echo base_url(); ?>user/commonwidget/share_article_via_email" name="mail_share" method="post" id="mail_share" role="form">
              <div class="form-group">
                <input type="text" placeholder="Name" name="sender_name" id="name" class="form-control">
                <input type="text" placeholder="Your Mail" name="share_email" id="share_email" class="form-control">
                <input type="text" placeholder="Friends's Mail" name="refer_email" id="refer_email" class="form-control">
                <textarea placeholder="Type your Message" class="form-control" name="message" id="message"></textarea>
                <input type="hidden"  class="content_id" name="content_id" value="<?php echo $content_id;?>" />
                 <input type="hidden"  class="content_type_id" name="content_type_id" value="<?php echo $content_type;?>" />
                <input type="reset" value="Reset" class="submit_to_email submit_post">
                <!--<input type="submit" value="share" class="submit_to_email submit_post" name="submit">-->
                <input type="button" value="Share" id="share_submit" class="submit_to_email submit_post" onclick="mail_form_validate();" name="submit">
              </div>
            </form>
          </div>
        </div>
      </div>
      
    </div>
    <?php 
}
elseif($view_mode=="adminview")
{ 
echo '<div class="margin-bottom-10">'.no_articles.'</div>';
} ?>