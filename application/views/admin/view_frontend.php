<?php
	//$ExpireTime = 60; // seconds (= 1 mins)
	$ExpireTime = 240; // seconds (= 4 mins)

	$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	$this->output->set_header("Cache-Control: cache, must-revalidate");
	$this->output->set_header("Cache-Control: max-age=".$ExpireTime);
	$this->output->set_header("Pragma: cache");
	$this->output->set_header("Access-Control-Allow-Origin: *");
?>
<?php
$css_path 		= image_url."css/FrontEnd/";
$js_path 		= image_url."js/FrontEnd/";
$images_path	= image_url."images/FrontEnd/";
$settings = $this->widget_model->select_setting($viewmode);
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
if(count($section_details) > 0){
$index             = ($section_details['Noindexed']=='1')? 'NOINDEX' : 'INDEX';
$follow            = ($section_details['Nofollow'] == '1') ? 'NOFOLLOW' : 'FOLLOW';
$Canonicalurl      = (strtolower($section_details['URLSectionStructure'])=='home')? base_url() : base_url().$section_details['URLSectionStructure'];//($section_details['Canonicalurl']!='') ? $section_details['Canonicalurl'] : '';
$meta_title        = $section_details['MetaTitle'];
$meta_description  = $section_details['MetaDescription'];
$meta_keywords     = $section_details['MetaKeyword'];
$section_name      = $section_details['Sectionname'];
$section_img_path  = ($section_details['BGImage_path']!='')? image_url.$section_details['BGImage_path']: '';
}else{
	$index            = "";
	$follow           = "";
	$Canonicalurl     = "";
	$meta_title       = "";
	$meta_description = "";
	$meta_keywords    = "";
	$section_name     = "Clone widget Template";
	$section_img_path = "";
}
$page_variable = $this->input->get('per_page');
if($page_variable!='')
{
 if($this->uri->segment(1)!='topic'){
 $per_page = $settings['subsection_otherstories_count_perpage'];
 }else
 {
 $page_variable = $this->input->get('per_page');
 $per_page      = 15;
 }
 $page_variable = ($page_variable/$per_page)+1;
}

$background_image = ($section_img_path!='')? 'style="background:url('.$section_img_path.') left;"' : "";
?><!DOCTYPE HTML>
<html lang="kn">
  <head>
  <link rel="alternate" href="<?php echo rtrim(current_url(), "/");?>" hreflang="en"/>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- for-mobile-apps -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="title" content="<?php echo strip_tags($meta_title);?>" />
  <meta name="description" content="<?php echo $meta_description;?>">
  <meta name="keywords" content="<?php echo $meta_keywords;?>">
  <meta name="news_keywords" content="<?php echo $meta_keywords;?>">
  <meta name="msvalidate.01" content="E3846DEF0DE4D18E294A6521B2CEBBD2" />
  <link rel="canonical" href="<?php echo $Canonicalurl;?>" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?php echo strip_tags($meta_title);?>"/>
  <meta property="og:image" content="<?php echo $section_img_path;?>"/>
  <meta property="og:site_name" content="Kannadaprabha"/>
  <meta property="og:description" content="<?php echo $meta_description;?>"/>
  <!--<meta property="fb:pages" content="872984479441941" />-->
  <meta name="twitter:site" content="@KannadaPrabha" />
  <meta name="twitter:title" content="<?php echo strip_tags($meta_title);?>" />
  <meta name="twitter:description" content="<?php echo $meta_description;?>" />
  <meta name="twitter:image" content="<?php echo $section_img_path;?>" />
  <meta name="robots" content="<?php echo $index;?>, <?php echo $follow;?>">
  <?php $title_tag = ($section_name=="Home")? "Latest News, Breaking News, India News, Bollywood, World, Business, Sports &amp; Politics" : $section_name; ?>
  <?php if($section_name=="Home"){?> 
  <meta name="google-site-verification" content="PNYjuNZT6nWdRuUIdIahwBshpoR-Jg43qZCOfemOC10" />
  <!--<meta name="google-site-verification" content="kq62qNfBVMPIHNNc5z9EtDxRdqA64bs4Z_Y6zoTMrp4" />
  <meta name="google-site-verification" content="b2kgMjuHVoLQcx-EKiY0gvMUlSl0jCrxoK1Mg-OnsU4" />-->
  <meta name="p:domain_verify" content="e0001e5ce89ed0c9a5e34a79adbf7c93" />
  <!--<meta name="msvalidate.01" content="281F8BAF23ACF515E82E9CAD6292308D" />-->
  <meta name="msvalidate.01" content="7AE7E46B8B1979D40D9ED0E6E7C9FDF4" />
  <!--<meta name="google-site-verification" content="m-XsLT25bStFxhVhGgHyCl9JC1GmEcFuGaZUBEyTrfo" />-->
  <meta name="fb:app_id" content="114452938652867" />
  <meta name="svg-x-verification" content="28ce15a5f25454ac21d27910e3bc837973c7486b" />
  <meta name="alexaVerifyID" content="9iadfF2zj9QGy-0RQMdlqPsWyIU" />
  <meta name="author" content="Kannadaprabha" />
  <!--<meta name="robots" content="noodp, noydir" />
  <meta name="slurp" content="noindex, nofollow" />-->
  <?php }?>
  <title><?php echo strip_tags($meta_title);?><?php echo ($page_variable!='')? "- page".$page_variable: "";?></title>
  <link rel="shortcut icon" href="<?php echo $images_path; ?>images/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php echo $css_path; ?>css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo $css_path; ?>css/combine_style.css?version=2.6" type="text/css">


  <!--<link rel="dns-prefetch" href="//d3.zedo.com/jsc/d3/fo.js" />
  <link rel="dns-prefetch" href="http://cricket.sportzdeck.stats.com/v3/widgets/newindianexpress-othermatches.html" />
  <link rel="dns-prefetch" href="//widget.crowdynews.com/NewIndianExpress_news.js" /> -->


  <script src="<?php echo $js_path; ?>js/jquery-1.11.3.min.js"></script>
  <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); }; </script>

   <script type="application/ld+json">
	{
	  "@context" : "http://schema.org",
	  "@type" : "WebSite", 
	  "name" : "kannadaprabha",
	  "url" : "https://www.kannadaprabha.com",
	  "potentialAction" : {
		"@type" : "SearchAction",
		"target" : "https://www.kannadaprabha.com/topic?term={search_term}&request=ALL&search=short",
		"query-input" : "required name=search_term"
	  }                     
	}
  </script>
  <script type="application/ld+json">
	{
	  "@context" : "http://schema.org",
	  "@type" : "Organization",
	  "name" : "kannadaprabha",
	  "url" : "https://www.kannadaprabha.com",
	  "sameAs" : [
		"https://www.facebook.com/kannadaPrabhaOnline",
		"https://twitter.com/KannadaPrabha",
		 "https://en.wikipedia.org/wiki/Kannada_Prabha",
		"https://www.youtube.com/user/kannadaprabhaonline"
	  ]
	}
  </script>

<!-- Start Advertisement Script --> 
<?php 
if(SHOWADS):
	echo urldecode(@$header_ad_script); 
	if(strtolower($section_details['URLSectionStructure'])=='home'){
		echo rawurldecode(stripslashes($settings['home_header_script']));
	}else{
		echo rawurldecode(stripslashes($settings['section_header_script']));
	}
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
  <body <?php echo $background_image;?>>
  <?php 
	if($viewmode != "" && $viewmode == "live")
	{
	?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-2311935-3', 'auto');
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
  <img src="https://sb.scorecardresearch.com/p?c1=2&c2=16833363&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->

<?php	
	}
?>
<!--
<style>
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
	<!--<div id='div-gpt-ad-1653561561506-0' style='min-width: 640px; min-height: 480px;'>
		<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1653561561506-0'); });
		</script>
	</div>
	<?php endif; ?>
<!--</div>-->
<?php
echo $header;
echo $body. $footer; ?>
<script src="<?php echo $js_path; ?>js/slider-custom-lazy.min.js?version=1" type="text/javascript"></script> 
<script type="text/javascript">
	  <?php 
	  $section_id = $section_details['Section_id'];
	  $parent_section_id = $section_details['ParentSectionID'];
	  $mode = $content['mode']; ?>
	  var Section_id = '<?php echo $section_id;?>';
	  var PSection_id = '<?php echo $parent_section_id;?>';
	  var view_mode = '<?php echo $mode;?>';
	<?php  if(isset($html_header)&& $html_header==true){ ?>
	  var call_breadcrumb = 1;
	  <?php }else{ ?>
	    var call_breadcrumb = 0;
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
	  
    $('.menu').affix({
	  offset: {
		top: $('header').height()
	  }
    });	
	$('.article_click').click(function(){localStorage.setItem("callback_section", window.location);});
});
</script>
<?php if($this->uri->segment(1)==folder_name || $this->uri->segment(1)=='topic'){ ?>
<!--<script src="<?php echo $js_path; ?>js/bootstrap-datepicker.js" type="text/javascript"></script>-->
<?php } ?>
<script type="text/javascript">
 var base_url = "<?php echo base_url(); ?>";
 var css_url  = "<?php echo $css_path; ?>";
</script> 
<script src="<?php echo $js_path; ?>js/custom.js" type="text/javascript"></script>

<!--<script src="<?php echo $js_path; ?>js/postscribe.min.js"></script>-->
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
<!--
<script type="text/javascript">
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
