<?php header ("Content-Type:text/xml");?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<channel>
		<title>newindianexpress.com Instant Articles</title>
		<link><?php print BASEURL; ?></link>
		<description>The New Indian Express brings latest breaking news on India, World, Politics, Finance, Cricket, Cinema, Technology, Automobile, Lifestyle and leading columnists.</description>
		<language>en</language>
	    <?php print $data; ?>
	</channel>
</rss>