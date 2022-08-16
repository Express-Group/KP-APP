 <!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="https://media.kannadaprabha.com/images/FrontEnd/images/favicon.ico" type="image/x-icon" />

  <link rel="stylesheet" href="<?php echo image_url; ?>special_page/bootstrap.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
   <link href="<?php echo image_url; ?>special_page/jquerysctipttop.css" rel="stylesheet" type="text/css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="<?php echo image_url; ?>special_page/bootstrap.min.js"></script>
   
  <style>
	@import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');
	body{
		position: relative;
    margin: 0;
    padding: 0;
    color: #fff;
    font-size: 18px;
    font-weight: 400;
    overflow-x: hidden;
  font-family: 'Roboto', sans-serif;

   background: url('<?php echo image_url; ?>special_page/bg-fest8.png');
   background-attachment:fixed;
	}
	.main-logo{
		margin-left: 7%;
		width:15%;
	}
	.header{
		padding: 5px;
		background: #fff;
		border-bottom: 1px solid #e6e4e4;
		padding-bottom: 10px;
		text-align:center;
	}
	.group-logo{
		width: 5.5%;
		border: 1px solid #337ab7;
	}
	section{margin-top:2%;}
	.hovereffect{
		background: #fff;
		color: #000;
		box-shadow: 1px 1px 3px 3px #00000029;
		float:left;
		position:relative;
		width:100%;
		overflow: hidden;
		z-index: 999999;
		border-radius: 10px;
		margin-bottom: 10px;
	}
	.overlay{
		padding: 10px;
		float:left;
	}
	.overlay h2{
		font-weight: 700;
		border-bottom: 1px solid #eee;
		padding-bottom: 9px;
		float:left;
		font-size: 24px;
		margin-top: 5px;
	}
	.overlay h2 a{
		color:#000;
		text-decoration:none !important;
	}
	.overlay .info{
		float:left;
		font-size: 14px;
	}
	.overlay div {
		float:left;
		text-align:left;
		width:100%;
		margin: 2% 0% 0% 0%;
	}
	.readmore{
		padding: 10px 20px 10px;
		background: #dbad07;
		color: #fff;
		margin-bottom: 2%;
		box-shadow: 1px 1px 1px #dbad07;
		text-decoration: none !important;
		border-radius: 50%;
		float: right;
	}
	.news-icon{
		position: absolute;
		width: 21%;
		top: 40%;
		left: 37%;
	}
	.news-icon1{
		position: absolute;
		width: 21%;
		top: 44%;
		left: 37%;
	}
	.news-icon2{
		position: absolute;
		width: 21%;
		top: 36%;
		left: 37%;
	}
	.social_icons {
    float: right;
    width: 25%;
    margin-top: 1%;
    text-align: right;
    position: absolute;
    right: 1%;
    top: 0;
}

.social_icons span {
    border-right: 1px solid #ccc;
    float: left;
}

.social_icons .android i {
    color: #a4c639;
}

.social_icons span i {
    font-size: 25px;
}
.social_icons .fb {
    background-color: #4672db;
    padding: 8px 15px 2px;
    border-radius: 50%;
}

.social_icons .google {
    background-color: #e41919;
    padding: 7px 8px 3px 10px;
	border-radius: 50%;
}

.social_icons .twit {
    background-color: #54bcf2;
    padding: 8px 13px 3px;
	border-radius: 50%;
}
.FontWhite, .btn-bac, .social_icons a {
    color: #fff!important;
}

.hovereffect > a > .img-responsive {
  -webkit-transition: 0.6s ease;
  transition: 0.6s ease;
  border-bottom: 5px solid #dbad07;
  width: 100%;
}

/* .hovereffect > a > .img-responsive:hover {
  -webkit-transform: scale(1.2);
  transform: scale(1.2);
} */
.uc-browser{width:9%;margin-top:2.5%;}
.banner-img {
   /*  animation: swing ease-in-out 1s infinite alternate; */
    transform-origin: center -20px;
    float:left;
    box-shadow: 5px 5px 10px rgba(0,0,0,0.5);
}
@keyframes swing {
    0% { transform: rotate(3deg); }
    100% { transform: rotate(-3deg); }
}



@media only screen and (max-width: 768px){
    .news-icon1,.news-icon2 {position: absolute; width: 21%;top: 56%;left: 37%;}
	.news-icon { position: absolute;width: 21%;top: 54%;left: 37%;}			
}

@media only screen and (max-width: 500px) {
    .group-logo{width:12%;}
	.main-logo{width:30%;margin-left: 4%;}
	.social_icons{display:none;}
	.second-col{margin-top:1%;}
	.uc-browser {width: 25%;margin-top: 2%;}
	.overlay h2{font-size:22px;}
}	
@media only screen and (max-width: 480px){
	.news-icon1{position: absolute; width: 21%;top: 42%;left: 37%;}
	.news-icon2 {position: absolute; width: 21%;top: 36%;left: 37%;}
	.news-icon { position: absolute;width: 21%;top: 41%;left: 37%;}		
}
@media only screen and (min-width : 1550px) {
	.group-logo{width:6%;}
	.header { padding: 5px; background: #fff; border-bottom: 1px solid #e6e4e4; padding-bottom: 20px;text-align: center;}
	.social_icons{margin-top:2%;}
	.uc-browser {width: 8%;margin-top: 3%;}
}
.bg-background{position:absolute;z-index:9;top:0;}
.pagination{width: 100%;text-align: center;margin-top: 3%;}
.pagination a{margin: 0 7px 0;background: #dbad07;color: #fff;text-decoration: none;padding: 6px 10px 0px;border-radius: 5px;border:1px solid #dbad07;overflow:scroll;}
.pagination strong{margin: 0 7px 0;background: #fff;color: #dbad07;text-decoration: none;padding: 6px 10px 0px;border-radius: 5px;border:1px solid #dbad07;}
footer{width:100%;background:#fff;color:#000;font-size: 16px;margin-top: 2%;padding: 1%;border-top: 2px solid #dbad07;}
footer p a {color: #7e7979 !important;}
  </style>
  <script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-2311935-3', 'auto');
	ga('send', 'pageview');
	setTimeout("ga('send','event','adjusted bounce rate','page visit 120 seconds or more')",120000);
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
</head>
<body id="imgs">
	<header class="header">
		<a href="https://www.kannadaprabha.com" rel="nofollow" target="_blank"> <img src="https://media.kannadaprabha.com/images/FrontEnd/images/NIE-logo21.jpg" class="main-logo"> </a>
		 <img class="pull-left group-logo" src="https://images.dinamani.com/images/FrontEnd/images/group.jpg"> 
		<img class="pull-right uc-browser"  src="<?php echo image_url; ?>special_page/ucbrowser.png">
		
		<div class="social_icons SocialCenter"> <a class="fb" href="https://www.facebook.com/kannadaPrabhaOnline" rel="nofollow" target="_blank"><i class="fa fa-facebook"></i></a> <!--<a class="google" href="https://plus.google.com/116307064799770204456/" rel="nofollow" target="_blank"><i class="fa fa-google-plus"></i></a>--> <a class="twit" href="https://twitter.com/KannadaPrabha" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i></a> 
		</div>
		
	</header>
	 <canvas id="canvas" class="bg-background"></canvas>
	<section>
		<div class="container">
		<div class="row" style="margin-bottom:5%;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<img src="<?php echo image_url; ?>special_page/fest-top-banner_new.png" class="img-responsive img-thumbnail banner-img">
			</div>
		</div>
		<?php
		$i=1;
		$domain_name = BASEURL;
		$logo_prefix= 'nie';
		foreach($data as $article){
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $article->title);
			$summary = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $article->summary_html);
			$live_article_url = $domain_name. $article->url;
			$original_image_path = $article->article_page_image_path;
			if($original_image_path !='' && get_image_source($original_image_path, 1))
			{
				$imagedetails = get_image_source($original_image_path, 2);
				$imagewidth = $imagedetails[0];
				$imageheight = $imagedetails[1];	
				$Image600X390 	= str_replace("original","w600X390", $original_image_path);
				$show_image = image_url. imagelibrary_image_path . $Image600X390;
				
			}else{
				$show_image	= image_url. imagelibrary_image_path.'logo/'.$logo_prefix.'_logo_600X390.jpg';
			}
			if(strlen($summary) > 100){
				$description = mb_substr($summary , 0 ,97).'...';
			}else{
				$description =$summary;
			}
			if($i==1){
				echo '<div class="row">';
			}
			echo '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
			echo '<div class="hovereffect">';
			echo '<a target="_BLANK" href="'.$live_article_url.'"><img class="img-responsive" src="'.$show_image.'" alt="'.$article->article_page_image_alt.'" title="'.$article->article_page_image_title.'"></a>';
			echo ' <div class="overlay" id="over">';
			echo '<h2><a href="'.$live_article_url.'">'.$display_title.'</a></h2>';
			echo '<p class="info">'.$description.' </p>';
			echo '<div><a target="_BLANK" href="'.$live_article_url.'" class="readmore"><i class="fa fa-angle-right" aria-hidden="true"></i></a></div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			if($i==3){
				echo '</div>';
				$i=1;
			}else{
				$i++;
			}
		}
		?> 
		<div class="pagination">
			<?php echo $pagination; ?>
		</div>
		</div>
	</section>
	<footer class="text-center">
		<p><b>Copyright - kannadaprabha.com <?php echo date('Y');?></a></p>
		<p> <a class="AllTopic" href="http://www.newindianexpress.com/" rel="nofollow" target="_blank">The New Indian Express | </a> <a class="AllTopic" href="http://www.kannadaprabha.com" rel="nofollow" target="_blank">Kannada Prabha | </a>  <a class="AllTopic" href="http://www.samakalikamalayalam.com/" rel="nofollow" target="_blank">Samakalika Malayalam | </a><a class="AllTopic" href="http://www.indulgexpress.com" rel="nofollow" target="_blank">Indulgexpress  | </a>  <a class="AllTopic" href="http://www.edexlive.com" rel="nofollow" target="_blank">Edex Live  | </a> <a class="AllTopic" href="http://www.cinemaexpress.com" rel="nofollow" target="_blank">Cinema Express | </a> <a class="AllTopic" href="http://www.eventxpress.com" rel="nofollow" target="_blank">Event Xpress  </a></p>
		<p> <a class="AllTopic" href="https://www.kannadaprabha.com/Contact-Us">Contact Us | </a> <a class="AllTopic" href="https://www.kannadaprabha.com.com/About-Us">About Us | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/Privacy-Policy">Privacy Policy | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/Terms-of-Use">Terms of Use | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/Advertise-With-Us">Advertise With Us </a></p>
		<p> <a class="AllTopic" href="https://www.kannadaprabha.com/">Home | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/nation">Nation | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/world">World | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/cities">Cities | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/business">Business | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/politics">Politics | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/entertainment">Entertainment | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/sports">Sports | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/photogallery">Photo Gallery | </a> <a class="AllTopic" href="https://www.kannadaprabha.com/videos">Videos  </a></p>
	</footer>
</body>
</html>