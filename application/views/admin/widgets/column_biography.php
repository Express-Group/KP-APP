<?php 
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$page_section_id    = $content['page_param'];
$view_mode          = $content['mode'];
$author_id = '';
$section_details = $this->widget_model->get_sectionDetails($page_section_id, $view_mode);
$linked_to_columnist = $section_details['AuthorID'];
if($linked_to_columnist != '' || $linked_to_columnist != NULL){
	$author_id = $section_details['AuthorID'];
}
else
{
	$author_name_in_url = ( $this->uri->segment(2) != '' ) ? $this->uri->segment(2) : ''; 
	if($author_name_in_url != ''){
		$author_id_details = $this->widget_model->get_author_by_name($author_name_in_url);
		$author_id = $author_id_details[0]['Author_id'];
	}
}
$show_image = "";
if($author_id != '') {
$author_det = $this->widget_model->get_author($author_id);
$author_name = $author_det[0]['AuthorName'];
//$author_image= $author_det[0]['Displayimage'];
$ShortBiography= $author_det[0]['ShortBiography'];
$column_id= $author_det[0]['column_id'];
//$author_image_id = $author_det[0]['Content_id'];
$author_image = "";
$imagealt = "";
$imagetitle  = "";
$image_path=$author_det[0]['image_path'] ;
if($image_path !='')
{
	$author_image  = $author_det[0]['image_path']; 
	$imagealt             = $author_det[0]['image_alt'];	
	$imagetitle           = $author_det[0]['image_caption'];
}	
$topic_id = ($column_id!='')? $this->widget_model->get_author_topicvalues($column_id): array();

$topicname   = (count($topic_id)>0 ) ?$topic_id['column_name']: '';					
$Image150X150 	= str_replace("original","w150X150", $author_image);
$show_image = "";
if (getimagesize(image_url_no . $author_image ) && $author_image  != '')
{	
	$show_image = image_url. $author_image ;
}
?>
<div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="AskPrabhu">
      <table>
      <tbody><tr>
      <td class="AskPrabhuLeft">
      <h2 class="topic WhiteTopic"><?php echo $author_name;?></h2>
      <?php if($topicname!='') { ?><h4 class="Italic"><?php echo $topicname;?></h4><?php } ?>
      <p><?php echo stripslashes($ShortBiography);?></p>
      </td>
      <td class="AskPrabhuRight">
	  <?php if($show_image != '') { ?>
      <figure>
      <img src="<?php echo $show_image;?>" data-src="<?php echo $show_image;?>" title="<?php echo $imagetitle; ?>" alt="<?php echo $imagealt; ?>">
      </figure>
	  <?php } ?>
      </td>
      </tr>
      </tbody></table>
      </div>
</div>
</div>
<?php } ?>