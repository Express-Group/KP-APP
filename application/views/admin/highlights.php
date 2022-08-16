<?php
$randnum = rand(100,10000);
?>
<!DOCTYPE html>
<html lan="en">
<head>
<link rel="stylesheet" href="<?php echo image_url; ?>css/FrontEnd/css/font-awesome.min.css" type="text/css">
<style>
	@font-face{font-family:Droid-regular; src:url("<?php echo image_url; ?>css/FrontEnd/fonts/DroidSerifFonts/droid-serif.regular.ttf");}
	.container1 ul, .container1 ul li {font-family:Droid-regular;margin: 0;list-style: none;clear:both;width:100%;color:#000;padding: 0 2px 0;margin-bottom:4%;}
	.container1 ul{background:#f2f2f2;}
	.container1 {height:430px;line-height: 18px; border-radius:0px; overflow: Hidden;color:#fff; padding: 2px 0;}
	.date-color{font-size: 13px;color: #000;float:left;width:18.4%;font-weight:bold;text-align:center;}
	.content-color{float:left;width:81.6%;} 
	.container1::-webkit-scrollbar {width: 12px;}
	.container1::-webkit-scrollbar-track { -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); border-radius: 10px;}
	.container1::-webkit-scrollbar-thumb {border-radius: 10px; -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);}
	.container1 ul{background:#f2f2f2;float:left;}
	.container1 ul li{background:#fff;margin: 7px 6px 7px;width: 92%;padding: 2%;box-shadow: 1px 1px 1px #00000052;float: left;}
	.date-color a{float: left;width: 100%;font-size: 15px;margin-top: 4px;color: #1DA1F2;}
	.container1:hover{overflow-y:scroll;}
	.scroll-static-<?php echo $randnum ?> ul{background:#f2f2f2;}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
<div class="container1 scroll-static-<?php echo $randnum ?>"><?php echo $data ?></div>
<script type="text/javascript" src="<?php print image_url.'js/jQuery.scrollText.js' ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var documentheight = $(window).height();
	$(".container1").css('height',documentheight);
	$(".container1").scrollText({
		'duration': 1500,
		'ulheight': documentheight
	});
	$(document).on('click','.custom_social',function(){
		var url= encodeURIComponent(document.referrer);
		$(".fb_share").attr("href", "https://www.facebook.com/sharer/sharer.php?u="+url);
		$(".twitter_share").attr("href", "https://twitter.com/intent/tweet?text="+ url+'  '+'via @KannadaPrabha');
	});
});
</script>
</body>
</html>