<?php header ("Content-Type:text/xml");?>
<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0" >
<channel>
<?php
$page_title = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $sectionDetails['MetaTitle']));
$page_title = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$page_title);

$page_description = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $sectionDetails['MetaDescription']));
$page_description = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$page_description);

?>
<title><?php echo $page_title; ?></title>
<link><?php echo $baseUrl.$sectionDetails['URLSectionStructure']; ?></link>
<description><?php echo $page_description; ?></description>
<language>kn</language>
<copyright>Copyright: (C) 2019 Kannadaprabha.com. All Rights Reserved.</copyright>
<?php if(count($parentSectionDetails) > 0): ?>
<ParentSection><?php echo $parentSectionDetails['Sectionname']; ?></ParentSection>
<?php endif; ?>
<SectionName><?php echo $sectionDetails['Sectionname']; ?></SectionName>
<?php
foreach($content as $articles){
$title = html_entity_decode($articles['title'],null,"UTF-8");
 $title = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $title));
$title = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
$summary = strip_tags(str_replace(['&', '&#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />'], ['&amp;', "'",' ',' ',' ','','',''] , $articles['summary_html']));
$summary = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$summary);
$thumbimage = image_url.imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
$fullimage = image_url.imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
$publishDate = new DateTime(@$articles['publish_start_date']);
$updatedDate = new DateTime(@$articles['last_updated_on']);
$authorNname = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $articles['author_name']));
$tags = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $articles['tags']));
if($contentType==1){
	$content = str_replace(['&', '&#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />'], ['&amp;', "'",' ',' ',' ','','',''] , $articles['article_page_content_html']);
	if($articles['article_page_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$articles['article_page_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$articles['article_page_image_path'];
	}
}else if($contentType==3){
	$content = str_replace(['&', '&#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />'], ['&amp;', "'",' ',' ',' ','','',''] , $articles['summary_html']);
	if($articles['first_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w600X300/',$articles['first_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$articles['first_image_path'];
	}
}else if($contentType==4){
	$content = str_replace(['&', '&#39;','&amp;','&nbsp;','nbsp;','<br>','</br>','<br />'], ['&amp;', "'",' ',' ',' ','','',''] , $articles['video_script']);
	if($articles['video_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$articles['video_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$articles['video_image_path'];
	}
}

?>
<item>
<?php if($contentType==3):  ?>
<GalleryID><?php echo $articles['content_id']; ?></GalleryID>
<?php elseif($contentType==4):  ?>
<VideoID><?php echo $articles['content_id']; ?></VideoID>
<?php else: ?>
<Articleid><?php echo $articles['content_id']; ?></Articleid>
<?php endif; ?>
<Category><?php echo $sectionDetails['Sectionname']; ?></Category>
<title><?php echo $title; ?></title>
<?php if($contentType==3 || $contentType==4):  ?>
<Headline><?php echo $title; ?></Headline>
<?php endif; ?>
<?php if($contentType!=3 && $contentType!=4):  ?>
<description><![CDATA[<?php echo $summary; ?>]]></description>
<story><![CDATA[<?php echo $content; ?>]]></story>
<?php endif; ?>
<?php if($contentType!=3): ?>
<image><?php echo $fullimage; ?></image>
<?php 
endif;
if($contentType==3): ?>
<Description>
<?php
$galleryImages = $this->widget_model->widget_article_content_by_id($articles['content_id'], $contentType, "");
foreach($galleryImages as $images):
$galleryCaption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $images['gallery_image_title']);
$galleryCaption = str_replace("&nbsp;",' ',$galleryCaption);
$galleryCaption = strip_tags(str_replace(['&', '&#39;'], ['', "'",] , $galleryCaption));
$galleryCaption = htmlspecialchars($galleryCaption, ENT_XML1 | ENT_COMPAT, 'UTF-8');
$galleryCaption = trim($galleryCaption);
$galleryImage= str_replace(' ', "%20",$images['gallery_image_path']);
?>
<Photo PhotoUrl="<?php echo image_url.imagelibrary_image_path.$galleryImage;?>" Caption="<?php echo $galleryCaption;?>"/>
<?php endforeach;?>
</Description>
<ThumbImage><?php echo $thumbimage; ?></ThumbImage>
<?php endif; ?>
<?php if($contentType==4): ?>
<Description><![CDATA[<?php echo $content; ?>]]></Description>
<?php endif; ?>
<pubDate><?php echo $publishDate->format('d M Y H:i A') ?></pubDate>
<authorname><?php echo $authorNname; ?></authorname>
<link><?php echo $baseUrl.html_entity_decode($articles['url'],null,"UTF-8"); ?></link>
<tags><?php echo $tags; ?></tags>
<updatedDate><?php echo $updatedDate->format('d M Y H:i A') ?></updatedDate>
</item>		
<?php } ?>
</channel>
</rss> 