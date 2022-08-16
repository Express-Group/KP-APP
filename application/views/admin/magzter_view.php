<?xml version="1.0" encoding="UTF-8"?>
<?php
$page_description = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $sectionDetails['MetaDescription']));
$page_description = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$page_description);
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
<channel>
<title>Kannadaprabha</title>
<atom:link href="<?php echo current_url(); ?>" rel="self" type="application/rss+xml" />
<link><?php echo $baseUrl; ?></link>
<description><?php echo $page_description; ?></description>
<lastBuildDate><?php echo date('D, d M Y H:i:s +0530') ?></lastBuildDate>
<language>kn-IN</language>
<sy:updatePeriod>hourly</sy:updatePeriod>
<sy:updateFrequency>1</sy:updateFrequency>
<generator><?php echo $baseUrl; ?></generator>
<?php
foreach($content as $articles){
$title = strip_tags(html_entity_decode($articles['title'],ENT_QUOTES,"UTF-8"));
$title = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] , $title));
$title = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
$publishDate = new DateTime(@$articles['publish_start_date']);
$authorNname = strip_tags($articles['author_name']);
$summary = strip_tags(html_entity_decode($articles['summary_html'],ENT_QUOTES,"UTF-8"));
$content = html_entity_decode($articles['article_page_content_html'],ENT_QUOTES,"UTF-8");
$fullimage = $thumbimage = image_url.imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
$imagealt='';
if($articles['article_page_image_path']!=''){
	$fullimage = image_url.imagelibrary_image_path.$articles['article_page_image_path'];
	$thumbimage = image_url.imagelibrary_image_path. str_replace('original/' ,'w600X300/' ,$articles['article_page_image_path']);
	$imagealt = trim($articles['article_page_image_alt']);
}
$image = '<p><img src="'.$fullimage.'" alt="'.$imagealt.'" srcset="'.$fullimage.' 650w, '.$thumbimage.' 300w" sizes="(max-width: 650px) 100vw, 650px" /></p>';
?>
<item>
<title><?php echo $title; ?></title>
<link><?php echo $baseUrl.html_entity_decode($articles['url'],null,"UTF-8"); ?></link>
<comments><?php echo $baseUrl.html_entity_decode($articles['url'],null,"UTF-8"); ?></comments>
<pubDate><?php echo $publishDate->format('D, d M Y H:i:s +0000'); ?></pubDate>
<dc:creator><![CDATA[<?php echo $authorNname; ?>]]></dc:creator>
<category><![CDATA[<?php echo $articles['section_name']; ?>]]></category>
<guid isPermaLink="false"><?php echo $articles['content_id']; ?></guid>
<description><![CDATA[<?php echo $summary; ?>]]></description>
<content:encoded><![CDATA[
<?php echo $image.$content; ?>
]]></content:encoded>
<wfw:commentRss></wfw:commentRss>
<slash:comments>0</slash:comments>
</item>
<?php
}
?>
</channel>
</rss>