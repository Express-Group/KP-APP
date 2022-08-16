<?php header ("Content-Type:text/xml");?>
<rss version="2.0" xml:base="<?php echo $baseUrl.'ARTICLE'; ?>">
<channel>
<?php
//$article_landing_details[0]['title']
$page_title = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $article_landing_details[0]['title']));
$page_title = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$page_title);

$page_description = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $article_landing_details[0]['summary_html']));
$page_description = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$page_description);

?>
<title><?php echo $page_title; ?></title>
<link><?php echo $baseUrl.$article_landing_details[0]['url']; ?></link>
<description><?php echo $page_description; ?></description>
<language>en</language>
<?php
$title = strip_tags(html_entity_decode($article_landing_details[0]['title'],ENT_QUOTES,"UTF-8"));
/* $title = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $title));
$title = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title); */
$summary = strip_tags(html_entity_decode($article_landing_details[0]['summary_html'],ENT_QUOTES,"UTF-8"));
//$summary = str_replace(['zwj;'],[''],preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$summary));
$thumbimage = image_url.imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
$fullimage = image_url.imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
$publishDate = new DateTime(@$article_landing_details[0]['publish_start_date']);
//$updatedDate = date('Y-m-d H:i:s', strtotime('+19 minutes', strtotime(@$articles['last_updated_on'])));
$updatedDate = new DateTime(@$article_landing_details[0]['last_updated_on']);
//$updatedDate = new DateTime(@$updatedDate);

$authorNname = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $article_landing_details[0]['author_name']));
$tags = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $article_landing_details[0]['tags']));
if($contentType==1){
	//$content = str_replace(['&', '#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />'], ['&amp;', "'",' ',' ',' ','','',''] , $articles['article_page_content_html']);
	$content = html_entity_decode($article_landing_details[0]['article_page_content_html'],ENT_QUOTES,"UTF-8");
	if($article_landing_details[0]['article_page_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$article_landing_details[0]['article_page_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$article_landing_details[0]['article_page_image_path'];
	}
}else if($contentType==3){
	$content = html_entity_decode($article_landing_details[0]['summary_html'],ENT_QUOTES,"UTF-8");
	if($article_landing_details[0]['first_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$article_landing_details[0]['first_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$article_landing_details[0]['first_image_path'];
	}
}else if($contentType==4){
	$content = html_entity_decode($article_landing_details[0]['video_script'],ENT_QUOTES,"UTF-8");
	if($article_landing_details[0]['video_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$article_landing_details[0]['video_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$article_landing_details[0]['video_image_path'];
	}
}

?>
<item>
<Articleid><?php echo $article_landing_details[0]['content_id']; ?></Articleid>
<title><![CDATA[<?php echo $title; ?>]]></title>
<excerpt><![CDATA[<?php echo $summary; ?>]]></excerpt>
<description><![CDATA[<?php echo $content; ?>]]></description>
<thumbimage><?php echo $fullimage; ?></thumbimage>
<?php if($contentType!=3): ?>
<fullimage><?php echo $fullimage; ?></fullimage>
<?php endif; ?>
<?php 
if($contentType==3): 
$galleryImages = $this->widget_model->widget_article_content_by_id($article_landing_details[0]['content_id'], $contentType, "");
foreach($galleryImages as $images):
//$galleryCaption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $images['gallery_image_title']);
$galleryCaption = html_entity_decode($images['gallery_image_title'],ENT_QUOTES,"UTF-8");
//$galleryCaption = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $galleryCaption));
$galleryImage= str_replace(' ', "%20",$images['gallery_image_path']);
?>
<gallery>
<gallery_url><?php echo image_url.imagelibrary_image_path.$galleryImage; ?></gallery_url>
<gallery_caption><![CDATA[<?php echo $galleryCaption; ?>]]></gallery_caption>
</gallery>
<?php endforeach; endif; ?>
<?php if($contentType==4): ?>
<video_url></video_url>
<?php endif; ?>
<pubDate><?php echo $publishDate->format('D, d M Y H:i:s +0530') ?></pubDate>
<authorname><?php echo $authorNname; ?></authorname>
<link><?php echo $baseUrl.html_entity_decode($article_landing_details[0]['url'],null,"UTF-8"); ?></link>
<tags><?php echo $tags; ?></tags>
<updatedDate><?php echo $updatedDate->format('D, d M Y H:i:s +0530') ?></updatedDate>
<?php if($contentType!=3 && $contentType!=4 && ($article_landing_details[0]['section_id']==4 || $article_landing_details[0]['parent_section_id']==4)):
$movieDetails = $this->live_db->query("SELECT movie_name , movie_director , movie_cast FROM review_master WHERE content_id='".$article_landing_details[0]['content_id']."'")->row_array();
 ?>
<review>
<itemtype>CreativeWork</itemtype>
<reviewauthor><?php echo $authorNname; ?></reviewauthor>
<reviewdate><?php echo $publishDate->format('Y-m-d') ?></reviewdate>
<reviewname></reviewname>
<reviewbody></reviewbody>
<worstRating>5</worstRating>
<bestRating>5</bestRating>
<ratingValue><?php echo $article_landing_details[0]['review']; ?></ratingValue>
</review>
<?php if(count($movieDetails) >0):
$actorDetails = json_decode($movieDetails['movie_cast']);
if(count($actorDetails) > 0){
	$castDetails= implode(',' ,$actorDetails);
}else{
	$castDetails='';
}
 ?>
<movie>
<moviename><![CDATA[<?php echo trim(html_entity_decode($movieDetails['movie_name'],ENT_QUOTES,"UTF-8")); ?>]]></moviename>
<genre></genre>
<director><![CDATA[<?php echo html_entity_decode($movieDetails['movie_director'],ENT_QUOTES,"UTF-8"); ?>]]></director>
<actor><![CDATA[<?php echo html_entity_decode($castDetails,ENT_QUOTES,"UTF-8"); ?>]]></actor>
<description></description>
</movie>
<?php endif; ?>
<?php endif; ?>
</item>		

</channel>
</rss>