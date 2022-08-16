<?php
header("Content-type: text/xml");
$base_url = base_url();
$url =  "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$atom_url= htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
echo "<?xml version='1.0' encoding='UTF-8'?> 
<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>
<channel>
<title>Kannadaprabha</title>
<link>$base_url</link>
<atom:link href=\"$atom_url\" rel=\"self\" type=\"application/rss+xml\"/>
<description>RSS Feed from Kannadaprabha</description>
<language>kn-in</language>
<copyright>Copyright ".date('Y')." Kannadaprabha. All rights reserved.</copyright>\n";
foreach($list as $article){
	$title = html_entity_decode($article['title']);
	$title = strip_tags(str_replace(array('&#39;' , '&'), array("'" , '&amp;') , $title)); 
	$title = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
	$author_name = ($article['author_name']!='') ? htmlspecialchars($article['author_name']) : '';
	$agency_name = ($article['agency_name']!='') ? htmlspecialchars($article['agency_name']) : '';
	$published_date = date("l, F j, Y h:i A", strtotime($article['publish_start_date']));
	$description = $article['summary_html'];
	$sectionId = $article['section_id'];
	$tags = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] ,$article['tags']));
	$link = base_url().$article['url'];
	$section_name = $article['section_name'];
	$image_path = '<image> </image>';
	$image_caption = '<imagecaption> </imagecaption>';
	if($article['image']!=''){
		$Image600X390  = str_replace("original","w600X390", $article['image']);
		if($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390)){
			$image_path = "<image>".image_url. imagelibrary_image_path . $Image600X390."</image>";
			$caption =	preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$article['imagetitle']);
			$caption = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] ,$caption));
			$image_caption= "<imagecaption>".$caption."</imagecaption>";
		}

	}
	if($article['content_type_id']==1):
		$story = '<story><![CDATA['.html_entity_decode(str_replace(['&#39;'],["'"],$article['content'])).']]></story>';
		$id= "<id>".$article['content_id']."</id>";
		echo "<item>
		$id
		<category>$section_name</category> 
		<categoryId>$sectionId</categoryId> 
		<title>$title</title>
		<author>$author_name</author>
		<source>$agency_name</source>
		<pubDate>$published_date +0530</pubDate>
		<description><![CDATA[$description]]></description>
		$story
		<tags>$tags</tags>
		$image_path
		$image_caption
		<link>$link</link>
		</item>"; 
	endif;
	if($article['content_type_id']==3):
		$story = '';
		$gallery_images = $this->widget_model->get_gallery_images_by_id($article['content_id']);
		$image_path='';
		$i=1;
		foreach($gallery_images as $gallery_image){
			$gallery_caption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallery_image['gallery_image_title']);
			$gallery_caption = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$gallery_caption);
			$gallery_caption = strip_tags(str_replace(['&', '&#39;'], ['&amp;', "'"] ,$gallery_caption));
			$Image600X390 = str_replace("original","w600X390", $gallery_image['gallery_image_path']);
			$Image150X150 = str_replace("original","w150X150", $gallery_image['gallery_image_path']);
			if($i==1){
				if($Image150X150 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image150X150)){
					$image_path.= "<thumbimage>".image_url. imagelibrary_image_path . $Image150X150."</thumbimage>";	
				}
				$image_path.= "<items>";
				$image_path.= "<img>".image_url. imagelibrary_image_path . $Image600X390."</img>";
				$image_path.= "<description>".$gallery_caption."</description>";
				$image_path.= "</items>";
			}else{
				if($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390)){
					$image_path.= "<items>";
					$image_path.= "<img>".image_url. imagelibrary_image_path . $Image600X390."</img>";
					$image_path.= "<description>".$gallery_caption."</description>";
					$image_path.= "</items>";	
				}
			}
			$i++;
		}
		$image_caption="<imagecaption> </imagecaption>";
		$id = "<Galleryid>".$article['content_id']."</Galleryid>";
		echo "<item>
		$id
		<category>$section_name</category> 
		<categoryId>$sectionId</categoryId>
		<title>$title</title>
		<author>$author_name</author>
		<source>$agency_name</source>
		<pubDate>$published_date +0530</pubDate>
		<description><![CDATA[$description]]></description>
		$story
		<tags>$tags</tags>
		$image_path
		$image_caption
		<link>$link</link>
		</item>";
	endif;
	if($article['content_type_id']==4):
	$video_script = $article['content'];
	$video_script = str_replace(['allowfullscreen' , 'allowfullscreen="true"'] ,['allowfullscreen="true"' ,'allowfullscreen="true"'] , $video_script);
	$video_script ='<![CDATA['. $video_script.']]>';
	$story = '';
	$id = "<Videoid>'".$article['content_id']."'</Videoid>";
	echo "<item>
	$id
	<category>$section_name</category> 
	<categoryId>$sectionId</categoryId>
	<title>$title</title>
	<author>$author_name</author>
	<source>$agency_name</source>
	<pubDate>$published_date +0530</pubDate>
	<description><![CDATA[$description]]></description>
	$story
	<tags>$tags</tags>
	$image_path
	$image_caption
	<video_script>$video_script</video_script>
	<link>$link</link>
	</item>"; 
	endif;

}
echo "</channel></rss>";
?> 