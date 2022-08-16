<?php
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$sectionid 			 = $content['widget_values']['cdata-widgetCategory'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
echo '<h3 class="text-center pti-feedtitle"><span></span><span>'.$widget_custom_title.'</span></h3>';
$max_article = ($max_article=='' || $max_article==null || $max_article==0)? 15 : $max_article;
$this->load->library('pagination');
$CI = &get_instance();
$this->live_db = $CI->load->database('live_db', TRUE);
$totalrows= $this->live_db->query("SELECT content_id FROM article WHERE status='P' AND section_id='".$sectionid."'")->num_rows();
$config['base_url'] = base_url().$this->uri->uri_string();
$config['total_rows'] = $totalrows;
$config['per_page'] = $max_article;
$config['use_page_numbers'] = TRUE;
$config['page_query_string'] = TRUE;
$this->pagination->initialize($config);
@$_GET['per_page'] = (@$_GET['per_page']=='' || @$_GET['per_page']==null )? 0 : $_GET['per_page'];
$ArticleData = $this->live_db->query("SELECT content_id,title,publish_start_date,url,summary_html FROM article WHERE  section_id='".$sectionid."' AND status='P' ORDER BY publish_start_date DESC LIMIT ".@$_GET['per_page'].",".$max_article."")->result();
$Pagination =  str_replace('<a>&nbsp;</a>','',$this->pagination->create_links());
$domain_name         =  base_url();
foreach($ArticleData as $article){
	$live_article_url = $domain_name.$article->url;
	$Title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$article->title);
	$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$article->summary_html);
	$publisheddate   = date('jS F Y h:i A', strtotime($article->publish_start_date));
	$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$Title.'</a>';
	echo '<div class="search-row_type ptifeed-main-container">';
	echo '<h4 style="font-weight:normal;font-family: Droid Serif !important;">'.$display_title.'</h4>';
	if($is_summary_required ==1){
		echo '<p style="color:#8d8d8d;">'.strip_tags($summary).'</p>';
	}
	echo '<div class="col-md-6 col-sm-6 col-xs-6 ptifeed-pubdate"><span><i class="fa fa-clock-o" aria-hidden="true"></i></span> <date style="color:#f36f0e;">'.$publisheddate.'</date></div><div class="col-md-6 col-sm-6 col-xs-6 text-right ptifeed-socialicons"><a class="sub_share" data-title="'.strip_tags($article->title).'" data-url="'.$live_article_url.'"><img src="'.image_url.'images/FrontEnd/images/fb.png" style="width:21px;margin-right: 3px;"><a><a class="sub_share" data-title="'.strip_tags($article->title).'" data-url-twitter="'.$live_article_url.'" ><img src="'.image_url.'images/FrontEnd/images/twitter.png" style="width:21px;margin-right: 3px;"><a><a class="sub_share"  data-url="'.$live_article_url.'" ><img src="'.image_url.'images/FrontEnd/images/google+.png" style="width:21px;margin-right: 3px;"><a></div></div>';
}
echo '<div class="pagina">'.$Pagination.'</div>';
?> 
<script>
	$(document).ready(function(){
		$('.pagina').find('a').each(function(index){
			if($(this).html()=='&nbsp;'){
				$(this).remove();
			}
		});
		$('.sub_share').on('click',function(e){
			$url =$(this).attr('data-url');
			$title =$(this).attr('data-title');
			if($url==undefined){
				$.ajax({
					type:'POST',
					url:'<?php echo BASEURL; ?>user/commonwidget/get_shorten_url',
					data:{'article_url':$(this).attr('data-url-twitter')},
					cache:false,
					dataType:'json',
					success:function(result){
						var twiiterUrlPath = 'https://twitter.com/intent/tweet?original_referer=' + encodeURIComponent(result.id) + '&text=' + encodeURIComponent($title) + '&url=' + encodeURIComponent(result.id);

						window.open(twiiterUrlPath, "", "width=670,height=340");
					}
				});
			}else if($title==undefined){
				window.open("https://plus.google.com/share?url="+$url, "", "width=670,height=340");
			}else{
				window.open("https://www.facebook.com/sharer/sharer.php?u="+$url, "", "width=670,height=340");

			}
		});
	})
</script>