<?php
$css_path 		= image_url."css/FrontEnd/";
$js_path 		= image_url."js/FrontEnd/";
$images_path	= image_url."images/FrontEnd/";
///if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
$content_id      = @$content_id;
$content_from    = $content_from;
$content_type_id = @$content_type;
$viewmode        = $viewmode;
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
	
	if ($imageheight > $imagewidth)
	{
		$Image600X390 	= $Image600X390;
	}
	else
	{				
		$Image600X390 	= str_replace("original","w600X390", $Image600X390);
	}
	$image_path = '';
	$image_path = image_url. imagelibrary_image_path . $Image600X390;
	}
else
{
	$image_path	   = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
	$image_caption = '';	
}
$content      = strip_tags($page_det['summary_html']);
$current_url  = explode('?', Current_url());
$share_url    = base_url().$page_det['url'];
$index        = ($page_det['no_indexed']==1)? 'NOINDEX' : 'INDEX';
$follow       = ($page_det['no_follow'] == 1) ? 'NOFOLLOW' : 'FOLLOW';
$Canonicalurl = $share_url;//($page_det['canonical_url']!='') ? $page_det['canonical_url'] : '';
$meta_title   = stripslashes(str_replace('\\', '', $page_det['meta_Title']));//($page_det['meta_Title']);
$meta_description = stripslashes($page_det['meta_description']);
$tags         = count($page_det['tags'])? $page_det['tags'] : '';

$query_string = ($_SERVER['QUERY_STRING']!='') ? "?".$_SERVER['QUERY_STRING'] : "";
?>
<?php
    $ExpireTime = ($content_from=="live") ? 120 : 86400; // seconds (= 2 mins)
	//$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	$this->output->set_header("Cache-Control: cache, must-revalidate");
	$this->output->set_header("Cache-Control: max-age=".$ExpireTime);
	$this->output->set_header("Pragma: cache");
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="alternate" href="<?php echo Current_url().$query_string;?>" hreflang="en"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="title" content="<?php echo strip_tags($meta_title);?>" />
<meta name="description" content="<?php echo $meta_description;?>">
<meta name="keywords" content="<?php echo $tags;?>">
<meta name="news_keywords" content="<?php echo $tags;?>">
<meta name="msvalidate.01" content="E3846DEF0DE4D18E294A6521B2CEBBD2" />
<link rel="canonical" href="<?php echo $Canonicalurl;?>" />
<meta name="robots" content="<?php echo $index;?>, <?php echo $follow;?>">
<meta property="og:url" content="<?php echo $share_url;?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo strip_tags($page_det['title']);?>"/>
<meta property="og:image" content="<?php echo $image_path;?>"/>
<meta property="og:image:width" content="450"/>
<meta property="og:image:height" content="298"/>
<meta property="og:site_name" content="The New Indian Express"/>
<meta property="og:description" content="<?php echo $content;?>"/>
<!--<meta name="twitter:card" content="<?php echo $content;?>" /> -->
<meta name="twitter:card" content="summary_large_image" /> 
<meta name="twitter:creator" content="NewIndianXpress" />
<meta name="twitter:site" content="@newindianexpress.com" />
<meta name="twitter:title" content="<?php echo strip_tags($page_det['title']);?>" />
<meta name="twitter:description" content="<?php echo $content;?>" />
<meta name="twitter:image" content="<?php echo $image_path;?>" />
<title><?php echo strip_tags($meta_title);?>- The New Indian Express</title>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); }; </script>
<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="<?php echo $images_path; ?>images/favicon.ico" type="image/x-icon" />

<link rel="dns-prefetch" href="//d3.zedo.com/jsc/d3/fo.js" />
  <link rel="dns-prefetch" href="http://cricket.sportzdeck.stats.com/v3/widgets/newindianexpress-othermatches.html" />
  <link rel="dns-prefetch" href="//widget.crowdynews.com/NewIndianExpress_news.js" /> 


<!--
<link rel="stylesheet" href="<?php echo $css_path; ?>css/combine_style.css" type="text/css">
 -->
<link rel="stylesheet" href="<?php echo $css_path; ?>css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $css_path; ?>css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $css_path; ?>css/style.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?php echo $js_path; ?>js/slider-custom-lazy.min.js" type="text/javascript"></script>
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
<!-- Start Advertisement Script -->
<?php echo urldecode($header_ad_script); ?>
<!-- End Advertisement Script -->
</head>
<?php
$content_url = $page_det['url'];

$url_array = explode('/', $content_url);
$get_seperation_count = count($url_array)-4;

$sectionURL = ($get_seperation_count==1)? $url_array[0] : (($get_seperation_count==2)? $url_array[0]."/".$url_array[1] : $url_array[0]."/".$url_array[1]."/".$url_array[2]);
$section_url = base_url().$sectionURL."/";
/*if($content_from=="live"){
$section_url =  $section_url; 
}*/
?>
<body class="article_body" itemscope itemtype="<?php echo $section_url;?>">
<?php 
	if($viewmode == "live")
	{
	?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-2311935-30', 'auto');
ga('send', 'pageview');
</script>
<?php	
	}
?>
<div class="remodal-main-overlay"> </div>
<div class="CenterMargin CenterMarginBg"> </div>
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
<div class="remodal" data-remodal-id="article" data-remodal-options="hashTracking: false, closeOnOutsideClick: false" role="dialog"  id="openmodal" style="position:relative;"> <?php echo  $header.$body .$footer; ?> </div>
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
<script src="<?php echo $js_path; ?>js/jquery.csbuttons.js" type="text/javascript"></script> 
<script src="<?php echo $js_path; ?>js/remodal.js" type="text/javascript"></script>
<?php if($content_type_id==1){ ?>
<script src="<?php echo $js_path; ?>js/article-pagination.js" type="text/javascript"></script>
<?php } ?>
<?php if($content_type_id==1 || $content_type_id==3){ ?>
<script src="<?php echo $js_path; ?>js/jquery.twbsPagination.min.js" type="text/javascript"></script>
<?php } ?>
<script>
var close_url = "<?php echo $close_url;?>";
$( document ).ready(function() {
/*$("html, body").animate({
	scrollTop: 0
});*/
//$('html').addClass('loading_time');
var inst = $('[data-remodal-id=article]').remodal();
inst.open();
 //$('[data-remodal-id=article]').remodal();

$(document).on('opened', '.remodal', function () {
  console.log('Modal is opened');
   $('.SectionContainer').append('<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>');
 $('.CenterMarginBg').hide();
  $('#load_spinner').hide();
  $('.side-bar-overlay').show();
   $('.menu').affix({
	offset: {
	top: $('header').height()
	}
	});	
	$('.remodal-close').affix({
	offset: {
	top: $('header').height()
	}
	});
});

   $(document).on('closed', '.remodal', function () {	
	<?php /*?><?php if($close_url =='home'){ ?>
	window.location.href = '<?php echo base_url();?>';
    <?php } else {	?>
	window.location.href = '<?php echo $close_url;?>';
	 <?php  }?><?php */?>

	 var bck = localStorage.getItem("callback_section");
	 if(bck =='null'||bck ==null)
	   {
		window.location.href ="http://www.newindianexpress.com/";
	   }
	 else
	   {
	 window.location.href = localStorage.getItem("callback_section");
	   }
	 //window.location.href = (localStorage.getItem("callback_section")!="null")? localStorage.getItem("callback_section"): //window.location.origin;
   });

$('.remodal-main-overlay:not(.container)').click(function(){
inst.close();
});
  $('.LeftArrow').click(function(){
  //inst.close();
  $('#load_spinner').show();
 });
  $('.RightArrow').click(function(){
  //inst.close();
  $('#load_spinner').show();
 });
});
</script>

<script src="<?php echo $js_path; ?>js/postscribe.min.js"></script>
</body>
</html>
