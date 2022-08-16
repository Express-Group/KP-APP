<?php
$sec=30;
$page=Current_url();
$readwhereQStr ='';
if(count($_GET) > 0){
	$readwhereQStr ='?'.$this->input->server('QUERY_STRING');
}
$css_path 		= image_url."css/FrontEnd/";
$js_path 		= image_url."js/FrontEnd/";
$images_path	= image_url."images/FrontEnd/";
///if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
$content_id      = @$content_id;
$content_from    = $content_from;
$content_type_id = @$content_type;
$viewmode        = $viewmode;
$settings = $this->widget_model->select_setting($viewmode);
//$page_det = $this->widget_model->widget_article_content_by_id($content_id, $content_type_id);
$page_det        = $article_details;
$page_det        = $page_det[0];
$Image600X390    = "";
$Image600X390 	 = ($content_type_id==1)? $page_det['article_page_image_path']: (($content_type_id==3)? $page_det['first_image_path']: (($content_type_id==4)? $page_det['video_image_path']: $page_det['audio_image_path']));
if ($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390))
	{
	$imagedetails = getimagesize(image_url_no . imagelibrary_image_path.$Image600X390);
	$imagewidth   = $imagedetails[0];
	$imageheight  = $imagedetails[1];
	
	/* if ($imageheight > $imagewidth)
	{
		$Image600X390 	= $Image600X390;
	}
	else
	{				
		$Image600X390 	= str_replace("original","w600X390", $Image600X390);
	} */
	$image_path = '';
	$image_path = image_url. imagelibrary_image_path . $Image600X390;
	}
else
{
	$image_path	   = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
	$image_caption = '';
	$imagewidth   = 600;
	$imageheight  = 390;
}
$content      = strip_tags($page_det['summary_html']);
$current_url  = explode('?', Current_url());
$share_url    = base_url().$page_det['url'];
$index        = ($page_det['no_indexed']==1)? 'NOINDEX' : 'INDEX';
$follow       = ($page_det['no_follow'] == 1) ? 'NOFOLLOW' : 'FOLLOW';
$AMP=0;
$Canonicalurl = $share_url;//($page_det['canonical_url']!='') ? $page_det['canonical_url'] : '';
$StandoutStatus=0;
$meta_title   = stripslashes(str_replace('\\', '', $page_det['meta_Title']));//($page_det['meta_Title']);
$meta_description = stripslashes($page_det['meta_description']);
$tags         = count($page_det['tags'])? $page_det['tags'] : '';
$seo_tags = $tags;
if($content_type_id==1){
	$seo_tags	= ($seotags !='')? $seotags :$tags;	
}

$query_string = ($_SERVER['QUERY_STRING']!='') ? "?".$_SERVER['QUERY_STRING'] : "";

if($content_from =='archive')
{
$index        = 'INDEX';
$follow       = 'FOLLOW';
if($meta_description=='')
$meta_description	= $meta_title;
}
$pubDate = date_format(date_create($page_det['publish_start_date']),"Y-m-d\TH:i:s\+05:30");
$LastUpDate = date_format(date_create($page_det['last_updated_on']),"Y-m-d\TH:i:s\+05:30");
$content_url = $page_det['url'];
$url_array = explode('/', $content_url);
$get_seperation_count = count($url_array)-4;
$sectionURL = ($get_seperation_count==1)? $url_array[0] : (($get_seperation_count==2)? $url_array[0]."/".$url_array[1] : $url_array[0]."/".$url_array[1]."/".$url_array[2]);
$section_url = base_url().$sectionURL."/";
$ampUrl = $mobileArticleUrl='';
$ampUrl = MOBILEURL. str_replace('.html' , '.amp' , $content_url);
/* if($content_type_id==1){
	$uri = urldecode($this->uri->segment($this->uri->total_segments()));
	$uriPos = strrpos($uri, "-");
	$uri = substr($uri , 0 , $uriPos);
	$ampUrl = MOBILEURL.'article/'.$uri.'/'.$content_id.'/amp'.$readwhereQStr;
	$mobileArticleUrl = MOBILEURL.'article/'.$this->uri->segment(1).'/'.$uri.'/'.$content_id.$readwhereQStr;
} */
?>
<?php
    //$ExpireTime = ($content_from=="live") ? 60 : 86400; // seconds (= 2 mins)
	$ExpireTime = ($content_from=="live") ? 240 : 86400; // seconds (= 4 mins)
	//$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	$this->output->set_header("Cache-Control: cache, must-revalidate");
	$this->output->set_header("Cache-Control: max-age=".$ExpireTime);
	$this->output->set_header("Pragma: cache");
	$this->output->set_header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE HTML>
<html lang="kn">
<head>
<link rel="alternate" href="<?php echo Current_url().$query_string;?>" hreflang="en"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-control" content="max-age=600, public">
<title><?php echo strip_tags($meta_title);?>- Kannada Prabha</title>
<!-- for-mobile-apps -->
<meta content="News" name="classification" />
<meta name="Distribution" content="Global" />
<meta http-equiv="content-language" content="kn" />
<meta property="fb:pages" content="396142997103510" />

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="title" content="<?php echo strip_tags($meta_title);?>" />
<meta name="description" content="<?php echo $meta_description;?>">
<meta name="keywords" content="<?php echo $tags;?>">
<meta name="news_keywords" content="<?php echo $seo_tags;?>">
<meta name="msvalidate.01" content="7AE7E46B8B1979D40D9ED0E6E7C9FDF4" />
<link rel="canonical" href="<?php echo $Canonicalurl;?>" />
<?php if($ampUrl!=''):?>
<link rel="amphtml" href="<?php echo $ampUrl;?>"></link>
<!--<link rel="alternate" media="only screen and (max-width: 480px)" href="<?php echo $mobileArticleUrl;?>"></link>-->
<?php endif; ?>
<meta name="robots" content="<?php echo $index;?>, <?php echo $follow;?>">
<meta property="og:url" content="<?php echo $share_url;?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo strip_tags($page_det['title']);?>"/>
<meta property="og:image" content="<?php echo $image_path;?>"/>
<meta property="og:image:width" content="<?php echo $imagewidth;?>"/>
<meta property="og:image:height" content="<?php echo $imageheight;?>"/>
<meta property="og:site_name" content="Kannadaprabha"/>
<meta property="og:description" content="<?php echo $content;?>"/>
<!--<meta name="twitter:card" content="<?php echo $content;?>" /> -->
<meta name="twitter:card" content="summary_large_image" /> 
<meta name="twitter:creator" content="Kannadaprabha" />
<meta name="twitter:site" content="@Kannadaprabha.com" />
<meta name="twitter:title" content="<?php echo strip_tags($page_det['title']);?>" />
<meta name="twitter:description" content="<?php echo $content;?>" />
<meta name="twitter:image" content="<?php echo $image_path;?>" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); }; </script>
<link rel="shortcut icon" href="<?php echo $images_path; ?>images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="<?php echo $css_path; ?>css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $css_path; ?>css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $css_path; ?>css/style.min.css?version=2.5" type="text/css">
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" defer async></script>-->
<script src="<?php echo $js_path; ?>js/jquery-1.11.3.min.js"></script>
<script src="<?php echo $js_path; ?>js/slider-custom-lazy.min.js?version=1" type="text/javascript"></script>
<script type="text/javascript">
<?php 
	  $section_id              = $page_det['section_id'];
	  $parent_section_id       = $page_det['parent_section_id'];
	  $grand_parent_section_id = $page_det['grant_section_id'];
	  $mode = $viewmode; ?>
	  var Section_id = '<?php echo $section_id;?>';
	  var PSection_id = '<?php echo $parent_section_id;?>';
	  var GPSection_id = '<?php echo $grand_parent_section_id;?>';
	  var view_mode = '<?php echo $mode;?>';
	  var css_path = '<?php echo $css_path; ?>';
	  <?php if(isset($html_header)&& $html_header==true){ ?>
	   var call_active_menu = 1;
	   <?php }else{ ?>
	   var call_active_menu = 0;
	   <?php }  
	   if(isset($html_rightpanel)&& $html_rightpanel==true){ ?>
	    var call_otherstories = 1;
	  <?php }else{ ?>
	    var call_otherstories = 0;
	<?php  }?>
$(document).ready(function () {
<!--replace slick preview as arrow-->
$('.slick-prev').addClass('fa fa-chevron-left');
$('.slick-next').addClass('fa fa-chevron-right');	
});
</script>
<!--
<script type="application/ld+json">
{
   "@context": "https://schema.org",
   "@type": "WebSite",
   "url": "https://www.kannadaprabha.com/",
   "potentialAction": {
   "@type": "SearchAction",
   "target": "https://www.kannadaprabha.com/search?s={search_term}",
   "query-input": "required name=search_term_string"
   }
}
</script> -->

<?php
$schematitle = strip_tags($page_det['title']);
$schematitle = (count($schematitle) >= 110) ? $schematitle : mb_substr($schematitle , 0 , 107).'...';
?>
<script type="application/ld+json">
{
	"@context":"http:\/\/schema.org",
	"@type":"NewsArticle",
	"mainEntityOfPage":{
		"@type":"WebPage",
		"@id":"<?php echo $share_url ?>"
	},
	"headline":"<?php echo $schematitle; ?>",
	"description":"<?php echo str_replace('\"' ,'&quot;' , strip_tags($page_det['summary_html'])); ?>",
	<?php if($content_type_id==1): ?>
	"articleBody":"<?php echo htmlentities(strip_tags($page_det['article_page_content_html'])); ?>",
	<?php endif; ?>
	"articleSection" : "<?php echo $page_det['section_name'] ?>",
	<?php if($content_type_id==1): ?>
	"wordCount" : "<?php echo strlen(strip_tags($page_det['article_page_content_html'])); ?>",
	<?php endif; ?>
	"datePublished":"<?php echo $pubDate ?>",
	"dateModified":"<?php echo $LastUpDate ?>",
	"publisher":{
		"@type":"Organization",
		"name":"kannadaprabha",
		"logo":{
			"@type":"ImageObject",
			"url":"<?php echo image_url ?>images/FrontEnd/images/NIE-logo21.jpg",
			"width":"165",
			"height":"60"
			}
	},
	"inLanguage": "kn",
	"keywords": "<?php echo strip_tags($page_det['tags']); ?>",
	"author":{
		"@type":"Person",
		"name":"<?php echo ($page_det['author_name']!='') ? $page_det['author_name'] : $page_det['agency_name']; ?>"
	},
	"image":{
		"@type":"ImageObject",
		"url":"<?php echo $image_path; ?>?w=1200&h=800&dpr=1.3",
		"width":"1200",
		"height":"800"
	}
}
</script>
<?php
	if($viewmode != "" && $viewmode == "live")
	{
	?>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-2311935-3', 'auto');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');
  setTimeout("ga('send','event','adjusted bounce rate','page visit 60 seconds or more')",60000);
</script>
<!-- Begin comScore Tag -->
<script>
var _comscore = _comscore || [];
_comscore.push({ c1: "2", c2: "16833363" });
(function() {
var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
el.parentNode.insertBefore(s, el);
})();
</script>
<noscript>
<img src="http://b.scorecardresearch.com/p?c1=2&c2=16833363&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->



<?php	
	}
?>
<!-- Start Advertisement Script -->
<?php 
if(SHOWADS):
	echo urldecode($header_ad_script);
	echo rawurldecode(stripslashes($settings['article_header_script']));
endif;
?> 
<!-- End Advertisement Script -->
<script type="text/javascript">
	window.GUMLET_CONFIG = {
		hosts: [{
			current: "media.kannadaprabha.com",
			gumlet: "media.kannadaprabha.com"
		}],
		lazy_load: true
	};
	(function(){d=document;s=d.createElement("script");s.src="https://cdn.gumlet.com/gumlet.js/2.0/gumlet.min.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
</script>
</head>
<body class="article_body" itemscope itemtype="<?php echo $section_url;?>">
<!--<style>
.ins-adv{width: 100%; height: 100%; position: absolute; top: 0; left: 0; display: flex; align-items: center; justify-content: center; background: #222222d6; z-index: 99;}
.ins-adv p{position: absolute; right: 3%; top: 3%; background: white; padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold; text-transform: capitalize; cursor: pointer;}
</style>
<div class="ins-adv" id="ins-adv">
	<p id="progressbar">Close</p>
	<?php if(isset($_SERVER['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER']) && @$_SERVER['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER']=="true"): ?>
	<!-- /3167926/KNP_Interstitial_320x480 -->
	<!--<div id='div-gpt-ad-1653561483042-0' style='min-width: 320px; min-height: 480px;'>
		<script>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1653561483042-0'); });
		</script>
	</div>
	<?php else: ?>
	<!-- /3167926/KNP_Interstitial_640x480 -->
	<!-- <div id='div-gpt-ad-1653561561506-0' style='min-width: 640px; min-height: 480px;'>
		<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1653561561506-0'); });
		</script>
	</div>
	<?php endif; ?>
</div>-->
<!--<div class="CenterMargin CenterMarginBg"> </div>-->
<style>
.cssload-container-article img{
position: absolute;
    right:0;
    top: 0;
    width: 70px;
}
.cssload-container-article .cssload-zenith {
    height: 70px;
    width: 70px;
}
.cssload-container-article figure{ 
    left: 50%;
    position: fixed;
    top: 50%;
}

.CenterMarginBg{
	z-index:0;
}

@media only screen and (max-width: 1550px) and (min-width: 1297px){

.main-menu {
	 margin-left: 0 !important;
	  width: 71% ;
}
.widget-container-30 .col-lg-12{
	padding:0 !important;
}

.RightArrow {
    margin-left: 1085px;
    top: 360px;
}
.LeftArrow{
left: 38px;
}
.PrintSocial{
	left:1%;
}
}

.article-col .col-md-4{
	margin-top:3%;
}
.LeftArrow,.RightArrow{
	display:none !important;
}
.section-header,.section-content,.section-footer{
	background:transparent;
}
<?php if($content_type_id!=1): ?>
@media only screen and (max-width: 1550px) and (min-width: 1297px){ li.index_hide{padding-bottom: 6px !important;} }
@media only screen and (min-width: 1551px){ li.index_hide{padding-bottom: 0.7% !important;}}
<?php endif; ?>
</style>
<div class="cssload-container cssload-container-article" id="load_spinner">
  <figure> <img src="<?php echo $images_path; ?>images/loader-Nie.png" />
    <div class="cssload-zenith"></div>
  </figure>
</div>
<div class="container side-bar-overlay">
  <div class="left-trans"></div>
  <div class="right-trans"></div>
</div>
<?php //echo $header; ?>
<!--<div class="wait" id="load_spinner">
   <i class="wait-spinner wait-spin centerZone"></i>
  </div>-->
<div class="" role="dialog"  id="" style="position:relative;"> <?php echo  $header.$body .$footer; ?> </div>
<?php 
if(isset($_GET['pm'])!=0 && is_numeric($_GET['pm'])){
$section_details = $this->widget_model->get_sectionDetails($_GET['pm'], $viewmode); //live db
$close_url       = (count($section_details)>0)? base_url().$section_details['URLSectionStructure']: "home";
}else{
$close_url ="home";
}

?>
<!--<script src="<?php echo $js_path; ?>js/remodal_custom.min.js" type="text/javascript"></script>
--> 
<script src="<?php echo image_url; ?>js/FrontEnd/js/remodal-article_updated.js?version=2.1"></script>
<script src="<?php echo $js_path; ?>js/jquery.csbuttons.js" type="text/javascript"></script> 
<?php if($content_type_id==1){ ?>
<script src="<?php echo $js_path; ?>js/article-pagination.js" type="text/javascript"></script>
<?php } ?>
<?php if($content_type_id==1 || $content_type_id==3){ ?>
<script src="<?php echo $js_path; ?>js/jquery.twbsPagination.min.js" type="text/javascript"></script>
<?php } ?>
<script>
var close_url = "<?php echo $close_url;?>";
$( document ).ready(function() {
	$('#load_spinner').hide();
	$('.menu').affix({
	offset: {
	top: $('header').height()
	}
	});
});
</script>

<!--<script src="<?php echo $js_path; ?>js/postscribe.min.js"></script>-->
<div class="mobile_share">
	<!--<span id="mbp" style="display:none;" onclick="mfb('prev')"><img src="<?php echo image_url ?>images/FrontEnd/images/social-article/prev.png?v=1"></span>-->
	<span class="mfb" onclick="mfb('flipboard')"><svg aria-hidden="true" data-prefix="fab" data-icon="flipboard" class="" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="font-size: 24px;width: 23px;float: left;margin: 0 32%;box-shadow: 2px 2px #2c457c;"><path fill="#fff" d="M0 32v448h448V32H0zm358.4 179.2h-89.6v89.6h-89.6v89.6H89.6V121.6h268.8v89.6z"></path></svg> flipboard</span>
	<span class="mf" onclick="mfb('facebook')"><i class="fa fa-facebook-square" aria-hidden="true"></i> facebook</span>
	<span class="mt" onclick="mfb('twitter')"><i class="fa fa-twitter-square" aria-hidden="true"></i> twitter</span>
	<span class="mw" onclick="mfb('whatsapp')"><i class="fa fa-whatsapp" aria-hidden="true"></i> whatsapp</span>
	<span class="mbn" id="mbn" style="display:none;" onclick="mfb('next')"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i> Next</span> 
	<script>
		var mb_prev = $('#mb_prev').val();
		var mb_next = $('#mb_next').val();
		if(mb_prev!='' && mb_prev!=undefined){
			$('#mbn').show();
		}
		/* if(mb_next!='' && mb_next!=undefined){
			$('#mbn').show();
		} */
		function mfb(type){
			if(type=='whatsapp'){
				$('.whatsapp').click();
			}else if(type=='email'){
				var sub =$('a[data-type="twitter"]').attr('data-txt');
				var body  =$('meta[property="og:url"]').attr('content');
				window.open('mailto:?subject='+sub+'&body='+body);
			}else if(type=='prev'){
				window.location.href= mb_prev;
			}else if(type=='next'){
				window.location.href= mb_next;
			}else{
				$('a[data-type="'+type+'"]').click();
			}
		}
	</script>
</div>
<?php
$countryCode = ['US','EU'];
if(in_array(@$_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'] , $countryCode) && SHOWADS==true):
?>
<script type="text/javascript">
    (function (){ var s,m,n,h,v,se,lk,lk1,bk; n=false; s= decodeURIComponent(document.cookie); m = s.split(';'); for(h=0;h<m.length;h++){ if(m[h]==' cookieagree=1'){n=true;break;}}if(n==false){v = document.createElement('div');v.setAttribute('style','position: fixed;left: 0px;right: 0px;height: auto;min-height: 15px;z-index: 2147483647;background: linear-gradient(90deg, #070707 0%, #92000b 35%, #1c0406 100%);line-height: 15px;padding: 8px 18px;font-size: 14px;text-align: left;bottom: 0px;opacity: 1;font-family: "Roboto Condensed";color: #fff;');v.setAttribute('id','ckgre');se = document.createElement('span');se.setAttribute('style','padding: 5px 0 5px 0;float:left;');lk =document.createElement('button');lk.setAttribute('onclick','ckagree()');lk.setAttribute('style' , 'float: right;display: block;padding: 5px 8px;min-width: 100px;margin-left: 5px;border-radius: 25px;cursor: pointer;color: rgb(0, 0, 0);background: rgb(241, 214, 0);text-align: center;border: none;font-weight: bold;outline: none;');lk.appendChild(document.createTextNode("Agree"));	se.appendChild(document.createTextNode("We use cookies to enhance your experience. By continuing to visit our site you agree to our use of cookies."));lk1 = document.createElement('a');lk1.href=document.location.protocol+"//"+document.location.hostname+"/cookies-info";lk1.setAttribute('style','text-decoration: none;color: rgb(241, 214, 0);margin-left: 5px;');lk1.setAttribute('target','_BLANK');lk1.appendChild(document.createTextNode("More info"));se.appendChild(lk1);v.appendChild(se);v.appendChild(lk);bk = document.getElementsByTagName('body')[0];bk.insertBefore(v,bk.childNodes[0]);}})();function ckagree(){ document.cookie = "cookieagree=1;path=/";$('#ckgre').hide(1000, function(){ $(this).remove();});}
</script>
<?php
endif;
?>
<script type="text/javascript">
	var stickyRight = {};stickyRight.isDesktop = "<?php echo (isset($_SERVER['HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER']) && $_SERVER['HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER']=='true') ? 1 : 0 ?>";stickyRight.advClass = $( ".sticky-right" ).last();	stickyRight.advInnerClass = $(stickyRight.advClass).children(".sticky");stickyRight.articleContainer = $('.SectionContainer');stickyRight.offset = {top : 50 , left: 0 , right : 10 , bottom : 25};	stickyRight.execute = function(){if(this.isDesktop=='1' && this.advClass.length > 0 && this.articleContainer.length > 0){window.addEventListener("scroll", function(){			var fh = stickyRight.articleContainer.height() + stickyRight.articleContainer.offset().top - stickyRight.advInnerClass.height() - stickyRight.offset.top - stickyRight.offset.bottom;var wh = $(window).scrollTop() | $("body").scrollTop();var jh = stickyRight.advClass.offset().top;stickyRight.offset.left = stickyRight.offset.left + stickyRight.advInnerClass.offset().left;if (wh  > jh - stickyRight.offset.top && wh < fh){		stickyRight.advClass.removeAttr("style");stickyRight.advInnerClass.css({ position: "fixed", top: stickyRight.offset.top + "px", bottom: "auto" ,zIndex :1});}else{if(wh > jh - stickyRight.offset.top && wh > fh){						stickyRight.advClass.css({ position: "absolute", left: "auto", bottom: stickyRight.offset.bottom + "px", top: "auto" });stickyRight.advInnerClass.removeAttr("style");}else{if(wh < jh){stickyRight.advClass.removeAttr("style");				stickyRight.advInnerClass.removeAttr("style");}}}});}};stickyRight.execute();
</script>
<!--<script type="text/javascript">
$(document).ready(function(e){
	$('body').css('overflow' , 'hidden');
	$('#progressbar').click(function(e){
		$('.ins-adv').hide();
		$('body').css('overflow' , 'auto');
	});
});
var downloadTimer = setInterval(function(){
if(timeleft <= 0){
	clearInterval(downloadTimer);
	const box = document.getElementById('ins-adv');
	box.style.display = 'none';
	document.getElementsByTagName("body")[0].style.overflow = "auto";
}
timeleft -= 1;
}, 1000);
var timeleft = 10;	
	
	
</script>-->
</body>
</html>
