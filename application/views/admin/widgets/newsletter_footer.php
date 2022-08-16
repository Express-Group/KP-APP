<?php
$is_home = $content['is_home_page'];
$view_mode              = $content['mode'];
$social_urls            = $this->widget_model->select_setting($view_mode); 
$widget_bg_color 		= $content['widget_bg_color'];
$widget_custom_title 	= $content['widget_title'];
$widget_instance_id 	= $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 		= "";
$page_type 				= 'section';


$header_details = $this->widget_model->select_setting($view_mode);
?>
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
  function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

<center>
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#337ab7">
<tbody><tr>
<td valign="middle" align="center" bgcolor="#337ab7">
<table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#337ab7" class="table600">
<tbody><tr bgcolor="#337ab7">
<td valign="top" align="center" height="10" bgcolor="#337ab7" style="font-size:0; line-height:0;">&nbsp;</td>
</tr> 
<tr>
<td valign="top" bgcolor="#337ab7"> 
<table width="296" align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#337ab7" class="table600AnnouncementText" style="border:1px solid #337ab7;">
<tbody><tr bgcolor="#337ab7">
<td valign="top" align="center" height="0" bgcolor="#337ab7" style="font-size:0; line-height:0;" class="logoMargin2">&nbsp;</td>
</tr>
<tr bgcolor="#337ab7">																	
<td valign="middle" align="center" height="42" bgcolor="#337ab7" class="socialTextTD">Get social with NewIndianExpress</td>
</tr>
<tr bgcolor="#337ab7">
<td valign="top" align="center" height="0" bgcolor="#337ab7" style="font-size:0; line-height:0;" class="logoMargin">&nbsp;</td>
</tr>
</tbody></table>
<table width="280" align="right" cellpadding="0" cellspacing="0" border="0" bgcolor="#337ab7" class="socialiconsection" style="border:1px solid #337ab7;">
<tbody><tr>
<td valign="middle" align="right" height="42" bgcolor="#337ab7">
<table cellpadding="0" cellspacing="0" border="0" bgcolor="#337ab7" class="socialiconsectionin">
<tbody><tr>
<td valign="top" align="center" height="42" bgcolor="#337ab7"><a href="<?php echo $social_urls['facebook_url'];?>" target="_blank" class="buttonsAndImagesLink"><img src="<?php echo image_url; ?>/images/FrontEnd/images/socialFacebook.jpg" style="display:block;" alt="" border="0" align="top" hspace="0" vspace="0" width="42" height="42"></a></td>

<td valign="top" align="center" height="42" bgcolor="#337ab7"><a href="<?php echo $social_urls['google_plus_url'];?>" target="_blank" class="buttonsAndImagesLink"><img src="<?php echo image_url; ?>/images/FrontEnd/images/socialGoogle.jpg" style="display:block;" alt="" border="0" align="top" hspace="0" vspace="0" width="42" height="42"></a></td>

<td valign="top" align="center" height="42" bgcolor="#337ab7"><a href="<?php echo $social_urls['twitter_url'];?>" target="_blank" class="buttonsAndImagesLink"><img src="<?php echo image_url; ?>/images/FrontEnd/images/socialTwitter.jpg" style="display:block;" alt="" border="0" align="top" hspace="0" vspace="0" width="42" height="42"></a></td>

<td valign="top" align="center" height="42" bgcolor="#337ab7"><a href="<?php echo $social_urls['rss_url'];?>" target="_blank" class="buttonsAndImagesLink"><img src="<?php echo image_url; ?>/images/FrontEnd/images/socialRss.jpg" style="display:block;" alt="" border="0" align="top" hspace="0" vspace="0" width="42" height="42"></a></td>


</tr>
</tbody></table>
</td>
</tr>
<tr bgcolor="#337ab7">
<td valign="top" align="center" height="0" bgcolor="#337ab7" style="font-size:0; line-height:0;" class="logoMargin3">&nbsp;</td>
</tr>
</tbody></table>
</td>
</tr>
<tr bgcolor="#337ab7">
<td valign="top" align="center" height="10" bgcolor="#337ab7" style="font-size:0; line-height:0;">&nbsp;</td>
</tr> 
</tbody></table>
</td>
</tr>
</tbody>
</table>
</center>