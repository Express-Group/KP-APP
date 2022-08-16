<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        xmlns:media="http://search.yahoo.com/mrss/" >
<channel>
	<title>ಕನ್ನಡ ಪ್ರಭ  |  Kannada Prabha</title>
	<link><?php echo $baseUrl; ?></link>
	<description>Kannada Prabha – Leading Kannada online news website delivers the Top News, Cinema News, Sports, Cricket, Karnataka, Politics, Photo Gallery, Videos in Kannada</description>
	<atom:link rel="self" href="<?php echo current_url(); ?>"/>
	<language>kn</language>
	<lastBuildDate><?php echo date('D, d M Y H:i:s +0000') ?></lastBuildDate>
	<?php
	foreach($content as $article):
	$title = strip_tags(html_entity_decode($article['title'],ENT_QUOTES,"UTF-8"));
	$publishDate = new DateTime(@$article['publish_start_date']);
	$image = ($article['article_page_image_path']!='') ? $article['article_page_image_path'] : 'logo/dinamani_logo_600X390.jpg';
	$content = html_entity_decode($article['story'],ENT_QUOTES,"UTF-8");
	if($type=='article'){
		$content = '<p><img src="'.image_url.imagelibrary_image_path.$image.'" title="'.$article['img_title'].'" alt="'.$article['img_alt'].'"></p>' .$content;
	}else if($type=='gallery'){
		$story ='<p>';
		$galleryImages = $this->widget_model->widget_article_content_by_id($article['content_id'], 3, $article['url']);
		foreach($galleryImages as $images):
			$galleryCaption = html_entity_decode($images['gallery_image_title'],ENT_QUOTES,"UTF-8");
			$galleryImage= str_replace(' ', "%20",$images['gallery_image_path']);
			$story .='<img src="'.image_url.imagelibrary_image_path.$galleryImage.'" title="'.$galleryCaption.'">';
		endforeach;
		$story .='</p>';
		$content = $story.$content;
	}
	?>
	<item>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<link><![CDATA[<?php echo $baseUrl.$article['url']; ?>]]></link>
	<content:encoded>
		<![CDATA[
		<?php echo $content; ?>
		]]>
	</content:encoded>
	<pubDate><?php echo $publishDate->format('D, d M Y H:i:s +0000') ?></pubDate>
	<guid><?php echo end(explode('/',$article['url'])); ?></guid>
	<image><?php echo image_url.imagelibrary_image_path.$image; ?></image>
	</item>
	<?php
	endforeach;
	?>
</channel>
</rss> 