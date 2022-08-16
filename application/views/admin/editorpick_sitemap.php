<?php header ("Content-Type:text/xml"); ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
		<title>New Indian Express</title>
		<link><?php print BASEURL; ?></link>
		<?php
			$sitedescription = "Latest News, Breaking News, India News, Bollywood, World, Business, Sports & Politics";
			$search        = array('&', '&#39;');
			$replace       = array('&amp;', "'");
			$stitle         = strip_tags(str_replace($search, $replace , $sitedescription)); 
			$stitle =  preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$stitle);
		?>
		<description><?php print $stitle; ?></description>
		<language>en-gb</language>
		<copyright>Copyrights New Indian Express.2017</copyright>
		<image>
			<title>New Indian Express</title>
			<link><?php print BASEURL; ?></link>
			<url><?php print image_url ; ?>images/FrontEnd/images/nie_200x40.png</url>
		</image>
		<?php
		foreach($new_articles as $ArticleDetails):
			$GetArticleDetails=$this->live_db->query("SELECT url,summary_html,publish_start_date,last_updated_on,tags,article_page_image_path FROM article WHERE content_id='".$ArticleDetails->content_id."'")->result();
			$publish_updated_date_custom = new DateTime(@$GetArticleDetails[0]->last_updated_on);
			$publish_date_custom = new DateTime(@$GetArticleDetails[0]->publish_start_date);
			$title         = html_entity_decode($ArticleDetails->CustomTitle,null,"UTF-8");
			$search        = array('&', '&#39;');
			$replace       = array('&amp;', "'");
			$title         = strip_tags(str_replace($search, $replace , $title)); 
			$title			= preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
			$GetArticleDetails[0]->tags =str_replace('&','&amp;',$GetArticleDetails[0]->tags );
			$summary=strip_tags($GetArticleDetails[0]->summary_html);
			$summary=preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$summary);
			$search        = array('&', '&#39;');
			$replace       = array('&amp;', "'");
			$summary         = strip_tags(str_replace($search, $replace , $summary)); 
			?>
			<item>
				<title><?php echo  $title; ?></title>
				<description><?php echo  $summary; ?></description>
				<link><?php echo BASEURL. html_entity_decode($GetArticleDetails[0]->url,null,"UTF-8"); ?></link>
				<guid><?php echo BASEURL. html_entity_decode($GetArticleDetails[0]->url,null,"UTF-8"); ?></guid>
				<pubDate><?php echo $publish_date_custom->format('Y-m-d\TH:i:s+05:30'); ?></pubDate>
			</item>
			<?php
		endforeach;
		?>
	</channel>
</rss>