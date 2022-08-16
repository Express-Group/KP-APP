<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-15">
<div class="WidthFloat_L">
<h4 class="header3 margin-bottom-0 margin-top-15">What is RSS?</h4>
<p>RSS (Rich Site Summary) is a format for delivering regularly changing web content. Many news-related sites, weblogs and other online publishers syndicate their content as an RSS Feed to whoever wants it.</p>
<h4 class="header3 margin-0">Why RSS?</h4>
<p>RSS solves a problem for people who regularly use the web. It allows you to easily stay informed by retrieving the latest content from the sites you are interested in. You save time by not needing to visit each site individually. You ensure your privacy, by not needing to join each site's email newsletter. The number of sites offering RSS feeds is growing rapidly.</p>
<p>For more information on RSS, please follow this link <a class="link" href="http://www.whatisrss.com/">http://www.whatisrss.com/</a></p>
</div>
 <div class="WidthFloat_L rss_section">
<?php 
$view_mode            = $content['mode'];
$sectionname_list = $this->widget_model->rss_section_mapping($view_mode); 
 //print_r($sectionname_list);exit;
foreach($sectionname_list as $section_list) {  
$feed_icon = base_url().'images/FrontEnd/images/rss_icon.png';
//echo '<img width="15" height="16" border="0" src="'.$feed_icon.'" title="'.$section_list['Sectionname'].'-newindianexpress.com" alt="rss feed newindianexpress.com" \>';

$section_url = join( "-",( explode(" ",$section_list['Sectionname']) ) );
$section_url = str_replace('&' , 'and' , $section_url);
if(!(empty($section_list['sub_section'])) ) { 
echo '<h5 class="rss_topic">'.$section_list['Sectionname'].'</h5>';
}else{
echo ' <p class="rss_link"> <i class="fa fa-rss-square"></i>';
echo '<a class="SiteColor" href="'.base_url().$section_url.'/rssfeed/?id='.$section_list['Section_id'].'&getXmlFeed=true">'.$section_list['Sectionname'].'</a></p>';
}
if(!(empty($section_list['sub_section'])) ) { 

foreach($section_list['sub_section'] as $sub_sectionlist) { 
//echo '<img src="'.$feed_icon.'" title="'.$section_list['Sectionname'].'-newindianexpress.com" alt="rss feed newindianexpress.com" ';
echo '<p class="rss_link"> <i class="fa fa-rss-square"></i>';
$section_url = join( "-",( explode(" ",$section_list['Sectionname']) ) ).'/'.join( "-",( explode(" ",$sub_sectionlist['Sectionname']) ) );
$section_url = str_replace('&' , 'and' , $section_url);
echo '<a class="SiteColor" href="'.base_url().$section_url.'/rssfeed/?id='.$sub_sectionlist['Section_id'].'&getXmlFeed=true">'.$sub_sectionlist['Sectionname'].'</a></p>';
if(!(empty($sub_sectionlist['sub_sub_section']))) {
		 
foreach($sub_sectionlist['sub_sub_section'] as $sub_section_child) { 
//echo '<img src="'.$feed_icon.'" title="'.$section_list['Sectionname'].'-newindianexpress.com" alt="rss feed newindianexpress.com" ';
echo '<p class="rss_link"> <i class="fa fa-rss-square"></i>';
$section_url = join( "-",( explode(" ",$section_list['Sectionname']) ) ).'/'.join( "-",( explode(" ",$sub_sectionlist['Sectionname']) ) ).'/'.join( "-",( explode(" ",$sub_section_child['Sectionname']) ) );
$section_url = str_replace('&' , 'and' , $section_url);
echo '<a class="SiteColor" href="'.base_url().$section_url.'/rssfeed/?id='.$sub_section_child['Section_id'].'&getXmlFeed=true">'.$sub_section_child['Sectionname'].'</a></p>';
}
}
}
}
}
?>
</div>
</div>
</div>