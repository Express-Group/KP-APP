<?php header ("Content-Type:text/xml");?>
<rss version="2.0" xml:base="<?php echo $baseUrl.$sectionDetails['URLSectionStructure']; ?>">
<channel>
<?php
$page_title = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $sectionDetails['MetaTitle']));
$page_title = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$page_title);

$page_description = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $sectionDetails['MetaDescription']));
$page_description = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$page_description);
$this->load->database();
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
$title = strip_tags(html_entity_decode($articles['title'],ENT_QUOTES,"UTF-8"));
$summary = strip_tags(html_entity_decode($articles['summary_html'],ENT_QUOTES,"UTF-8"));;
$thumbimage = image_url.imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
$fullimage = image_url.imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
$publishDate = new DateTime(@$articles['publish_start_date']);
$updatedDate = new DateTime(@$articles['last_updated_on']);
$authorNname = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $articles['author_name']));
$tags = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $articles['tags']));
if($contentType==1){
	$content = html_entity_decode($articles['article_page_content_html'],ENT_QUOTES,"UTF-8");
	if($articles['article_page_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$articles['article_page_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$articles['article_page_image_path'];
	}
	$tblname ="articlemaster";
}else if($contentType==3){
	$content = html_entity_decode($articles['summary_html'],ENT_QUOTES,"UTF-8");
	if($articles['first_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$articles['first_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$articles['first_image_path'];
	}
	$tblname ="gallerymaster";
}else if($contentType==4){
	$content = html_entity_decode($articles['video_script'],ENT_QUOTES,"UTF-8");
	if($articles['video_image_path']!=''){
		$thumbimage = image_url.imagelibrary_image_path.str_replace('original/','w150X150/',$articles['video_image_path']);
		$fullimage = image_url.imagelibrary_image_path.$articles['video_image_path'];
	}
	$tblname ="videomaster";
}
$editorName = $this->db->query("SELECT Firstname , Lastname FROM usermaster WHERE User_id=(SELECT Createdby FROM ".$tblname." WHERE content_id='".$articles['content_id']."')")->row_array();
$editorName = @$editorName['Firstname'].' '.@$editorName['Lastname'];
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
<title><![CDATA[<?php echo $title; ?>]]></title>
<?php if($contentType==3 || $contentType==4):  ?>
<Headline><![CDATA[<?php echo $title; ?>]]></Headline>
<?php endif; ?>
<?php if($contentType!=3):  ?>
<excerpt><![CDATA[<?php echo $summary; ?>]]></excerpt>
<description><![CDATA[<?php echo $content; ?>]]></description>
<?php endif; ?>
<?php if($contentType!=4): ?>
<thumbimage><?php echo $thumbimage; ?></thumbimage>
<?php endif; ?>
<?php if($contentType!=3 && $contentType!=4): ?>
<fullimage><?php echo $fullimage; ?></fullimage>
<?php endif; ?>
<?php if($contentType==4): ?>
<Image><?php echo $fullimage; ?></Image>
<?php endif; ?>
<?php 
if($contentType==3): 
$galleryImages = $this->widget_model->widget_article_content_by_id($articles['content_id'], $contentType, "");
foreach($galleryImages as $images):
$galleryCaption = html_entity_decode($images['gallery_image_title'],ENT_QUOTES,"UTF-8");
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
<authorname><?php echo $editorName; ?></authorname>
<source><?php echo $articles['agency_name']; ?></source>
<link><?php echo $baseUrl.html_entity_decode($articles['url'],null,"UTF-8"); ?></link>
<tags><?php echo $tags; ?></tags>
<updatedDate><?php echo $updatedDate->format('D, d M Y H:i:s +0530') ?></updatedDate>
</item>		
<?php } ?>
</channel>
</rss>