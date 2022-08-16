<?php header ("Content-Type:text/xml"); ?>
<?php if(isset($xml_type) && ($xml_type == 'section_sitemap' || $xml_type == 'section_year_sitemap')) { ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php } elseif(isset($xml_type) && ($xml_type == 'section_live_sitemap')) { ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:n="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php } elseif(isset($xml_type) && ($xml_type == 'new_sitemap' || $xml_type == 'editorial_pick' )) { ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<?php } 
if(isset($xml_type) && $xml_type == 'section_sitemap') {
if(isset($sectionname_list)) {
foreach($sectionname_list as $sectionname) { 
switch($sectionname['Sectionname']) {
	case ($sectionname['Sectionname'] == "ಫೋಟೊ ಗ್ಯಾಲರಿ" || $sectionname['Sectionname'] == "photos" || $sectionname['Sectionname'] =="photogallery"):
	$content_type = 3;
	break;
	case ($sectionname['Sectionname'] == "ವಿಡಿಯೋ" || $sectionname['Sectionname']=="e-videos"  || $sectionname['Sectionname'] == "videos" ) :
	$content_type = 4;
	break;
	case "Audios" :
	$content_type = 5;
	break;
	default: 
	$content_type = 1;
	break;
}
	switch($content_type) {
			case 1:
			$tablename = "article";
			break;
			
			case 3: 
			$tablename = "gallery";
			break;
			
			case 4: 
			$tablename = "video";
			break;
			
			case 5: 
			$tablename = "audio";
			break;
		}
for($year=date('Y'); $year>=2013; $year--) { 
$end_month = 12;
	
	if($year == date('Y'))
		$end_month = date('m');
	
	for($month=$end_month; $month>= 1; $month--) { 
	
		$total_count = 0;
	
			if($year == date('Y')) { 
				
				$live_total_count = 0;
				
				$CacheID = "CONTENT-SITEMAP- SELECT COUNT(content_id) FROM ".$tablename." WHERE section_id=".$sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
			
					
				if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
	
					$this->live_db->select("content_id");
					$this->live_db->from($tablename);
					$this->live_db->where("section_id",$sectionname['Section_id']);
					$this->live_db->where("MONTH(last_updated_on)",$month);
					$this->live_db->group_by("content_id");
					
					$get = $this->live_db->get();				

					$live_total_count 	= $get->num_rows();
					
					$num_live_count_array = array("content_count" => $live_total_count);
					
					$this->memcached_library->add($CacheID,$num_live_count_array);		
					
					//echo $live_total_count; exit;
					
					
				} else {
					
					$num_live_count_array = $this->memcached_library->get($CacheID);
					$live_total_count  = $num_live_count_array['content_count'];
					
			
				}
				
				$CacheID = "CONTENT-SITEMAP- SELECT content_id FROM ".$tablename."_".$year." WHERE section_id=".$sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
				
					if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
					
					if($this->archive_db->table_exists($tablename."_".$year)) {
						
						$this->archive_db->select("content_id");
						$this->archive_db->from($tablename."_".$year);
						$this->archive_db->where("section_id",$sectionname['Section_id']);
						$this->archive_db->where("MONTH(last_updated_on)",$month);
						$get = $this->archive_db->get();
						
						$archive_total_count = $get->num_rows();
					}
					
					$total_count = $live_total_count + $archive_total_count;
					
					$num_live_count_array = array("content_count" => $total_count);
					
					$this->memcached_library->add($CacheID,$num_live_count_array);
					
					//echo $total_count; exit;
					
				} else {
					
					$num_live_count_array 	= $this->memcached_library->get($CacheID);
					$total_count 			= $num_live_count_array['content_count'];
					
					
					
					
				}
				
				
				
				
				
				} else {
					

					$CacheID = "CONTENT-SITEMAP- SELECT content_id FROM ".$tablename."_".$year." WHERE section_id=".$sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
				
				
					if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
					
						if($this->archive_db->table_exists($tablename."_".$year)) {
						
						$this->archive_db->select("content_id");
						$this->archive_db->from($tablename."_".$year);
						$this->archive_db->where("section_id",$sectionname['Section_id']);
						$this->archive_db->where("MONTH(last_updated_on)",$month);
						$get = $this->archive_db->get();
						
						$total_count = $get->num_rows();
						
						$num_live_count_array = array("content_count" => $total_count);
						
						$this->memcached_library->add($CacheID,$num_live_count_array);
						
							//echo $total_count; exit;
						
						}
						
				} else {
					
						$num_live_count_array = $this->memcached_library->get($CacheID);
						$total_count 			= $num_live_count_array['content_count'];
						
					
					
				}
				
			}
	
	if($total_count > 0) {
	
	?>

<sitemap><loc><?php echo BASEURL.$sectionname['URLSectionName'].".xml?section_id=".$sectionname['Section_id']."&amp;content_type=".$content_type."&amp;year=".$year."&amp;month=".$month; ?></loc></sitemap>

	<?php } } }

if(isset($sectionname['sub_section']) &&  count($sectionname['sub_section']) > 0) {
	
	
	foreach($sectionname['sub_section'] as $sub_sectionname) { 
	
	for($year=date('Y'); $year>=2013; $year--) { 

	$end_month = 12;
	
	if($year == date('Y'))
		$end_month = date('m');
	
	for($month=$end_month; $month>= 1; $month--) {

			$total_count = 0;
	
			if($year == date('Y')) { 
				
				$live_total_count = 0;
				
				$CacheID = "CONTENT-SITEMAP- SELECT COUNT(content_id) FROM ".$tablename." WHERE section_id=".$sub_sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
			
					
				if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
	
					$this->live_db->select("content_id");
					$this->live_db->from($tablename);
					$this->live_db->where("section_id",$sub_sectionname['Section_id']);
					$this->live_db->where("MONTH(last_updated_on)",$month);
					$this->live_db->group_by("content_id");
					
					$get = $this->live_db->get();				

					$live_total_count 	= $get->num_rows();
					
					$num_live_count_array = array("content_count" => $live_total_count);
					
					$this->memcached_library->add($CacheID,$num_live_count_array);		
					
					//echo $live_total_count; exit;
					
					
				} else {
					
					$num_live_count_array = $this->memcached_library->get($CacheID);
					$live_total_count  = $num_live_count_array['content_count'];
					
			
				}
				
				$CacheID = "CONTENT-SITEMAP- SELECT content_id FROM ".$tablename."_".$year." WHERE section_id=".$sub_sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
				
					if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
					
					if($this->archive_db->table_exists($tablename."_".$year)) {
						
						$this->archive_db->select("content_id");
						$this->archive_db->from($tablename."_".$year);
						$this->archive_db->where("section_id",$sub_sectionname['Section_id']);
						$this->archive_db->where("MONTH(last_updated_on)",$month);
						$get = $this->archive_db->get();
						
						$archive_total_count = $get->num_rows();
					}
					
					$total_count = $live_total_count + $archive_total_count;
					
					$num_live_count_array = array("content_count" => $total_count);
					
					$this->memcached_library->add($CacheID,$num_live_count_array);
					
					//echo $total_count; exit;
					
				} else {
					
					$num_live_count_array 	= $this->memcached_library->get($CacheID);
					$total_count 			= $num_live_count_array['content_count'];
					
					
					
					
				}
				
				
				
				
				
				} else {
					

					$CacheID = "CONTENT-SITEMAP- SELECT content_id FROM ".$tablename."_".$year." WHERE section_id=".$sub_sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
				
				
					if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
					
						if($this->archive_db->table_exists($tablename."_".$year)) {
						
						$this->archive_db->select("content_id");
						$this->archive_db->from($tablename."_".$year);
						$this->archive_db->where("section_id",$sub_sectionname['Section_id']);
						$this->archive_db->where("MONTH(last_updated_on)",$month);
						$get = $this->archive_db->get();
						
						$total_count = $get->num_rows();
						
						$num_live_count_array = array("content_count" => $total_count);
						
						$this->memcached_library->add($CacheID,$num_live_count_array);
						
							//echo $total_count; exit;
						
						}
						
				} else {
					
						$num_live_count_array = $this->memcached_library->get($CacheID);
						$total_count 			= $num_live_count_array['content_count'];
						
					
					
				}
				
			}
	
	if($total_count > 0) {
	
	?>

<sitemap><loc><?php echo BASEURL.$sub_sectionname['URLSectionName'].".xml?section_id=".$sub_sectionname['Section_id']."&amp;content_type=".$content_type."&amp;year=".$year."&amp;month=".$month; ?></loc></sitemap>

	<?php } } }

	if(isset($sub_sectionname['sub_sub_section']) &&  count($sub_sectionname['sub_sub_section']) > 0) {
		
		foreach($sub_sectionname['sub_sub_section'] as $sub_sub_sectionname) { 
		
			for($year=date('Y'); $year>=2013; $year--) { 

$end_month = 12;
	
	if($year == date('Y'))
		$end_month = date('m');
	
	for($month=$end_month; $month>= 1; $month--) { 
	
	
	$total_count = 0;
	
			if($year == date('Y')) { 
				
				$live_total_count = 0;
				
				$CacheID = "CONTENT-SITEMAP- SELECT COUNT(content_id) FROM ".$tablename." WHERE section_id=".$sub_sub_sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
			
					
				if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
	
					$this->live_db->select("content_id");
					$this->live_db->from($tablename);
					$this->live_db->where("section_id",$sub_sub_sectionname['Section_id']);
					$this->live_db->where("MONTH(last_updated_on)",$month);
					$this->live_db->group_by("content_id");
					
					$get = $this->live_db->get();				

					$live_total_count 	= $get->num_rows();
					
					$num_live_count_array = array("content_count" => $live_total_count);
					
					$this->memcached_library->add($CacheID,$num_live_count_array);		
					
					//echo $live_total_count; exit;
					
					
				} else {
					
					$num_live_count_array = $this->memcached_library->get($CacheID);
					$live_total_count  = $num_live_count_array['content_count'];
					
			
				}
				
				$CacheID = "CONTENT-SITEMAP- SELECT content_id FROM ".$tablename."_".$year." WHERE section_id=".$sub_sub_sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
				
					if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
					
					if($this->archive_db->table_exists($tablename."_".$year)) {
						
						$this->archive_db->select("content_id");
						$this->archive_db->from($tablename."_".$year);
						$this->archive_db->where("section_id",$sub_sub_sectionname['Section_id']);
						$this->archive_db->where("MONTH(last_updated_on)",$month);
						$get = $this->archive_db->get();
						
						$archive_total_count = $get->num_rows();
					}
					
					$total_count = $live_total_count + $archive_total_count;
					
					$num_live_count_array = array("content_count" => $total_count);
					
					$this->memcached_library->add($CacheID,$num_live_count_array);
					
					//echo $total_count; exit;
					
				} else {
					
					$num_live_count_array 	= $this->memcached_library->get($CacheID);
					$total_count 			= $num_live_count_array['content_count'];
					
					
					
					
				}
				
				
				
				
				
				} else {
					

					$CacheID = "CONTENT-SITEMAP- SELECT content_id FROM ".$tablename."_".$year." WHERE section_id=".$sub_sub_sectionname['Section_id']." AND MONTH(last_updated_on) = ".$month;
				
				
					if(!$this->memcached_library->get($CacheID) && $this->memcached_library->get($CacheID) == '') {
					
						if($this->archive_db->table_exists($tablename."_".$year)) {
						
						$this->archive_db->select("content_id");
						$this->archive_db->from($tablename."_".$year);
						$this->archive_db->where("section_id",$sub_sub_sectionname['Section_id']);
						$this->archive_db->where("MONTH(last_updated_on)",$month);
						$get = $this->archive_db->get();
						
						$total_count = $get->num_rows();
						
						$num_live_count_array = array("content_count" => $total_count);
						
						$this->memcached_library->add($CacheID,$num_live_count_array);
						
							//echo $total_count; exit;
						
						}
						
				} else {
					
						$num_live_count_array = $this->memcached_library->get($CacheID);
						$total_count 			= $num_live_count_array['content_count'];
						
					
					
				}
				
			}
	
	
	if($total_count > 0) {  ?>
		
		<sitemap><loc><?php echo BASEURL.$sub_sub_sectionname['URLSectionName'].".xml?section_id=".$sub_sub_sectionname['Section_id']."&amp;content_type=".$content_type."&amp;year=".$year."&amp;month=".$month; ?></loc></sitemap>

	<?php } } }
	}

	}	
}

}

 } }

} 

if(isset($xml_type) && ($xml_type == 'section_sitemap' || $xml_type == 'section_year_sitemap')) { ?>
</sitemapindex>
<?php } elseif(isset($xml_type) && ($xml_type == 'section_live_sitemap')) { 

if(isset($live_articles) && count($live_articles)) {
	
	foreach($live_articles as $articles) { 
	
	
	?>
		
<url>
<loc><?php echo BASEURL.html_entity_decode(@$articles['url'], null, "UTF-8"); ?></loc>
<lastmod><?php echo gmdate('Y-m-d\TH:i:s+00:00',strtotime(@$articles['last_updated_on'])); ?></lastmod>
<changefreq>monthly</changefreq>
<priority>0.7</priority>
</url>
		
	<?php }
	
}  else { ?>


<url>
<loc><?php echo BASEURL; ?></loc>
<lastmod><?php echo gmdate('Y-m-d\TH:i:s+00:00',strtotime(date('Y/m/d H:i:s'))); ?></lastmod>
<changefreq>monthly</changefreq>
<priority>0.7</priority>
</url>
	
<?php  } ?>
	
	
	
	</urlset>
	
<?php } elseif(isset($xml_type) && ($xml_type == 'new_sitemap')) {  

if(isset($new_articles) && count($new_articles)) {
	
	foreach($new_articles as $articles) { 
	?>

	<url>
    <loc><?php echo BASEURL. html_entity_decode($articles['url'],null,"UTF-8"); ?></loc>
	<?php
	  $publish_updated_date_custom = new DateTime(@$articles['last_updated_on']);
	  ?>
	 <lastmod><?php echo $publish_updated_date_custom->format('Y-m-d\TH:i:s+05:30'); ?></lastmod>
    <news:news>
      <news:publication>
        <news:name>ಕನ್ನಡ ಪ್ರಭ</news:name>
        <news:language>kn</news:language>
      </news:publication>
	  <?php
	  $publish_date_custom = new DateTime(@$articles['publish_start_date']);
	  ?>
      <news:publication_date><?php echo $publish_date_custom->format('Y-m-d\TH:i:s+05:30'); ?></news:publication_date> 
	 
      <news:title><?php 

 $title         = html_entity_decode($articles['title'],null,"UTF-8"); //html_entity_decode
 $search        = array('&', '&#39;');
 $replace       = array('&amp;', "'");
 $title         = strip_tags(str_replace($search, $replace , $title)); 
 $title			= preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);


//$content = "tst";
	$articles['tags']=str_replace('&','&amp;',$articles['tags']);
echo  $title; ?></news:title>
      <news:keywords><?php echo preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$articles['tags']); ?></news:keywords>
      </news:news>
	  <?php if($articles['article_page_image_path'] !=''){ ?>
	  <image:image>
		<image:loc> <?php echo image_url.imagelibrary_image_path.$articles['article_page_image_path'] ;?> </image:loc>
	</image:image>
	<?php } ?>

  </url>
  
<?php }  ?>

</urlset>
<?php } }

//created for editorial pick 
elseif(isset($xml_type) && ($xml_type == 'editorial_pick')) {
	if(isset($new_articles) && count($new_articles)) {
		foreach($new_articles as $ArticleDetails):
			$GetArticleDetails=$this->live_db->query("SELECT url,publish_start_date,last_updated_on,tags,article_page_image_path FROM article WHERE content_id='".$ArticleDetails->content_id."'")->result();
			$publish_updated_date_custom = new DateTime(@$GetArticleDetails[0]->last_updated_on);
			$publish_date_custom = new DateTime(@$GetArticleDetails[0]->publish_start_date);
			$title         = html_entity_decode($ArticleDetails->CustomTitle,null,"UTF-8");
			$search        = array('&', '&#39;');
			$replace       = array('&amp;', "'");
			$title         = strip_tags(str_replace($search, $replace , $title)); 
			$title			= preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$title);
			$GetArticleDetails[0]->tags =str_replace('&','&amp;',$GetArticleDetails[0]->tags );
			?>
			<url>
			 <loc><?php echo BASEURL. html_entity_decode($GetArticleDetails[0]->url,null,"UTF-8"); ?></loc>
			 <lastmod><?php echo $publish_updated_date_custom->format('Y-m-d\TH:i:s+05:30'); ?></lastmod>
			 <news:news>
			  <news:publication>
				<news:name>ಕನ್ನಡ ಪ್ರಭ</news:name>
				<news:language>kn</news:language>
			  </news:publication>
			  <news:publication_date>
				<?php echo $publish_date_custom->format('Y-m-d\TH:i:s+05:30'); ?>
			  </news:publication_date> 
			  <news:title>
				<?php echo  $title; ?>
			  </news:title>
			  <news:keywords><?php echo preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$GetArticleDetails[0]->tags); ?></news:keywords>
			 </news:news>
			 <?php if($GetArticleDetails[0]->article_page_image_path !=''){ ?>
			 <image:image>
				<image:loc>
					<?php echo image_url.imagelibrary_image_path.$GetArticleDetails[0]->article_page_image_path ;?> 
				</image:loc>
			 </image:image>
			 <?php } ?>
			</url>
			<?php
		endforeach;
		?></urlset><?php
	}

}