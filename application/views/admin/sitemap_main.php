<?php header("Content-type: text/xml"); ?>
<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
<loc><?php echo BASEURL; ?></loc>
<lastmod><?php echo date('Y-m-d');?></lastmod>
<changefreq>daily</changefreq>
<priority>.5</priority>
</url>
<?php echo $data; ?>
</urlset>