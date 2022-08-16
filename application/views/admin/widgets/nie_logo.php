<style>
.main_logo{	text-align: left; }
.social-icons-wrapper {	width:100% !important;text-align: center;margin-top: 15%; }
.large-screen-search { float: left; }
.social-icon-set { float:none; margin-right: 1px; cursor: pointer; display: inline-block; }
.social-icons-wrapper .social-icon-set-youtube { margin-right: 0; }
.social-icons-wrapper .social-icon-set-fb { margin: 0; }
.search1 { height: 40px !important;  float: left; width: 100%;}
.search1 .navbar-form { margin-top: 0px !important; width: 100%; padding: 0;}
.search1 .input-group {width: 100%;}
.search1 .input-group-btn {width: 1% !important;}
#current_time{width:100%;}

</style>

<div class="row">
<?php
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$is_home = $content['is_home_page'];
$view_mode = $content['mode'];
$header_details = $this->widget_model->select_setting($view_mode);
$search_term    = $this->input->get('search_term');
?>
<div class="MobileInput"> <form class="" action="<?php echo base_url(); ?>topic"  name="SimpleSearchForm" id="mobileSearchForm" method="get" role="form">
<input type="text" placeholder="Search..." name="search_term" id="mobile_srch_term" value="<?php echo $search_term;?>"/> <a href="javascript:void(0);" id="mobile_search"><img src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo image_url ?>images/FrontEnd/images/social-icon-set/search-mob.png" /></a></form>
</div>
<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 share-padd-right-0">
		  <div class="social_icons SocialCenter mobile-share">
				<div class="dropdown menucat">
					 <button class="btn btn-default dropdown-toggle menu-default" type="button" data-toggle="dropdown"><span><i class="fa fa-bars"></i></span></button>
					 <ul class="dropdown-menu">
							<li class="all-menu-sub"><a href="<?php echo base_url()."karnataka"; ?>"><?php echo "ರಾಜ್ಯ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."nation"; ?>"><?php echo "ರಾಷ್ಟ್ರೀಯ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."world"; ?>"><?php echo "ಅಂತಾರಾಷ್ಟ್ರೀಯ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."sports"; ?>"><?php echo "ಕ್ರೀಡೆ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."cricket"; ?>"><?php echo "ಕ್ರಿಕೆಟ್"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."business"; ?>"><?php echo "ವಾಣಿಜ್ಯ"; ?></a></li>
						   <li class="dropdown-submenu all-menu">
								<a  href="<?php echo base_url()."cinema"; ?>"><?php echo "ಸಿನಿಮಾ"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."cinema/news"; ?>"><?php echo "ಸುದ್ದಿ"; ?></a></li>
									<li><a href="<?php echo base_url()."cinema/bollywood"; ?>"><?php echo "ಬಾಲಿವುಡ್"; ?></a></li>
									<li><a href="<?php echo base_url()."cinema/review"; ?>"><?php echo "ವಿಮರ್ಶೆ"; ?></a></li>
									<li><a href="<?php echo base_url()."cinema/feature"; ?>"><?php echo "ಸಿನಿಮಾ ಲೇಖನ"; ?></a></li>
								</ul>
							</li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."politics"; ?>"><?php echo "ರಾಜಕೀಯ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."health"; ?>"><?php echo "ಆರೋಗ್ಯ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."lifestyle"; ?>"><?php echo "ಜೀವನಶೈಲಿ"; ?></a></li>
							<li class="dropdown-submenu all-menu">
								<a  href="<?php echo base_url()."photogallery"; ?>"><?php echo "ಫೋಟೊ ಗ್ಯಾಲರಿ "; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."photogallery/cinema"; ?>"><?php echo "ಸಿನಿಮಾ"; ?></a></li>
									<li><a href="<?php echo base_url()."photogallery/nation"; ?>"><?php echo "ದೇಶ"; ?></a></li>
									<li><a href="<?php echo base_url()."photogallery/miscellany"; ?>"><?php echo "ಇತರೆ"; ?></a></li>
									<li><a href="<?php echo base_url()."photogallery/sports"; ?>"><?php echo "ಕ್ರೀಡೆ"; ?></a></li>
								</ul>
							</li>
							<li class="dropdown-submenu all-menu">
								<a  href="<?php echo base_url()."videos"; ?>"><?php echo "ವಿಡಿಯೋ"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."videos/news"; ?>"><?php echo "ಸುದ್ದಿ"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/entertainment"; ?>"><?php echo "ಮನರಂಜನೆ"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/food-dining"; ?>"><?php echo "ಆಹಾರ ವಿಹಾರ "; ?></a></li>
									<li><a href="<?php echo base_url()."videos/health"; ?>"><?php echo "ಆರೋಗ್ಯ"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/sports"; ?>"><?php echo "ಕ್ರೀಡೆ"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/travel-automobile"; ?>"><?php echo "ಪ್ರವಾಸ & ವಾಹನ "; ?></a></li>
									<li><a href="<?php echo base_url()."videos/fashion-lifestyle"; ?>"><?php echo "ಫ್ಯಾಷನ್ & ಜೀವನಶೈಲಿ"; ?></a></li>
								</ul>
							</li>
							<li class="dropdown-submenu all-menu">
								<a  href="<?php echo base_url()."astrology"; ?>"><?php echo "ಭಕ್ತಿ-ಭವಿಷ್ಯ"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."astrology/rashi-bhavishya"; ?>"><?php echo "ರಾಶಿ ಭವಿಷ್ಯ"; ?></a></li>
								</ul>
							</li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."columns"; ?>"><?php echo "ಅಂಕಣಗಳು"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."food"; ?>"><?php echo "ಅಡುಗೆ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."astrology"; ?>"><?php echo "ಆಧ್ಯಾತ್ಮ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."science-technology"; ?>"><?php echo "ತಂತ್ರಜ್ಞಾನ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."travel-automobile"; ?>"><?php echo "ವಾಹನ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."specials"; ?>"><?php echo "ವಿಶೇಷ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."sanchaya"; ?>"><?php echo "ಸಂಚಯ"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."looking-back-2021"; ?>"><?php echo "ಹಿನ್ನೋಟ 2021"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."budget-2022"; ?>"><?php echo "ಬಜೆಟ್ 2022"; ?></a></li>	
					 </ul>
				</div>
		  </div>
            <ul class="MobileNav">
            <li class="MobileShare dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span><i class="fa fa-share-alt" aria-hidden="true"></i><i class="fa fa-caret-down" aria-hidden="true"></i></span></a><ul class="dropdown-menu">
          <li><a href="<?php echo $header_details['facebook_url'];?>" rel="nofollow" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
          <li><a href="<?php echo $header_details['google_plus_url'];?>" rel="nofollow" target="_blank"><i class="fa fa-google-plus"></i></a></li>
          <li><a href="<?php echo $header_details['twitter_url'];?>" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i></a></li>
          <!--<li><a href="http://www.pinterest.com/newindianexpres" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
          <li><a href="https://instagram.com/newindianexpress/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>-->  
          <li><a href="<?php echo $header_details['rss_url'];?>" target="_blank"><i class="fa fa-rss"></i></i></a></li>
          
        </ul></li>
            </ul>
</div>
  <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
    <div class="main_logo">
      <?php 
		echo '<a href="'.base_url().'">
		<img src="'.image_url.$header_details['sitelogo'].'" data-src="'.image_url.$header_details['sitelogo'].'" width="358" height="120" alt="site_logo"></a>';
		$day = date('l');
		$month = date('F');
		?>
	</div>
  </div>
 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 search-padd-left-0">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <ul class="MobileNav">
		   <?php if($content['page_param']!="home") { ?>
		   <li style="display:none;">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button></li><?php } ?>
		   <li class="MobileSearch"><a class="SearchHide" href="javascript:void(0);"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                   
        </ul>
       <div class="large-screen-search">
        <div class="social-icons-wrapper margin-bottom-10">
					 <a class="social-icon-set" href="https://play.google.com/store/apps/details?id=com.winjit.kannadaprabha" rel="nofollow" target="_blank"><img data-src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/Android_new.png" src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png"></a>
					 
					 <a class="social-icon-set" href="https://itunes.apple.com/us/app/kannada-prabha/id948224805?mt=8" rel="nofollow" target="_blank"><img data-src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/Apple_new.png" src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png"></a>
					 
					 <a class="social-icon-set" href="<?php echo $header_details['facebook_url'];?>" rel="nofollow" target="_blank"><img data-src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/Fb_new.png" src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png"></a>
					 
					 <a href="<?php echo $header_details['twitter_url'];?>" target="_blank" class="social-icon-set social-icon-set-youtube"><img data-src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/twitter_new.png" src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png"></a>
   
					 <a class="social-icon-set" href="https://instagram.com/kannadaprabha/" rel="nofollow" target="_blank"><img data-src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/Insta_new.png" src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png"></a>
					 
					 <a class="social-icon-set" href="https://www.youtube.com/user/kannadaprabhaonline" rel="nofollow" target="_blank"><img data-src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/YT_new.png" src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png"></a>
					 
					 <a class="social-icon-set" href="<?php echo $header_details['rss_url'];?>" target="_blank"><img data-src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/rss_new.png" src="<?php echo image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png"></a>
		 
					<div class="loc" id="current_time" style="font-size: 10px;"><?php echo '<span>'.$day.', '.$month.', '.date('d').', '.date('Y').'</span>'; ?></div>
				</div>
	   
				<div class="search1"> 
					<form class="navbar-form formb hide-search-custom" action="<?php echo base_url(); ?>topic"  name="SimpleSearchForm" id="SimpleSearchForm" method="get" role="form">
						<div class="input-group">
							<input name="serach-next" type="hidden" value="1" /> 
							<input type="text"   class="form-control tbox" placeholder="Search" name="q" id="srch-term" value="<?php echo $search_term;?>">
							<div class="input-group-btn">
								<input type="hidden" class="form-control tbox"  name="home_search" value="H" id="home_search">
								<button  class="btn btn-default btn-bac" id="search-submit" type="submit"><i class="fa fa-search"></i></button>
								<!--<button class="btn btn-default btn-bac" id="search_btn"><i class="fa fa-search"></i></button>-->
							</div>
						</div>
						<label id="error_throw"></label>
					 </form>
				</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#SimpleSearchForm').submit(function(e){
					e.preventDefault();
					if($('#srch-term').val().trim()==''){
						$('#error_throw').html('Please provide search keyword(s)').addClass('error');
						$('#srch-term').addClass('error');
					}else if($('#srch-term').val().trim().length < 3){
						$('#error_throw').html('Please Enter more than 2 letters').addClass('error');
						$('#srch-term').addClass('error');
					}else{
						$('#error_throw').html('').removeClass('error');
						$('#srch-term').removeClass('error');
						window.location.href=base_url+'topic?term='+$('#srch-term').val()+'&request=ALL&search=short';
					}
					
					
				});
			});
		</script>
        
        </div>
      </div>
    </div>
  </div>
</div> 