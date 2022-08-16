<div class="row">
<?php
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$is_home = $content['is_home_page'];
$view_mode = $content['mode'];
$header_details = $this->widget_model->select_setting($view_mode);
?>
<div class="MobileInput">  <form class="" action="<?php echo base_url(); ?>topic"  name="SimpleSearchForm" id="mobileSearchForm" method="get" role="form">
<input type="text" placeholder="Search..." name="search_term" id="mobile_srch_term" value="<?php echo @$_GET['search_term'];?>"/> <a href="javascript:void(0);" id="mobile_search"><img src="<?php echo image_url; ?>images/FrontEnd/images/search-mob.png" /></a></form></div>
<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 share-padd-right-0">
<div class="social_icons SocialCenter mobile-share">
	<div class="dropdown menucat">
					 <button class="btn btn-default dropdown-toggle menu-default" type="button" data-toggle="dropdown"><span><i class="fa fa-bars"></i></span></button>
					 <ul class="dropdown-menu">
							<li class="all-menu-sub"><a href="<?php echo base_url()."nation"; ?>"><?php echo "Nation"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."world"; ?>"><?php echo "World"; ?></a></li>
						   <li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "States"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."states/tamil-nadu"; ?>"><?php echo "Tamil Nadu"; ?></a></li>
									<li><a href="<?php echo base_url()."states/kerala"; ?>"><?php echo "Kerala"; ?></a></li>
									<li><a href="<?php echo base_url()."states/karnataka"; ?>"><?php echo "Karnataka"; ?></a></li>
									<li><a href="<?php echo base_url()."states/andhra-pradesh"; ?>"><?php echo "Andhra Pradesh"; ?></a></li>
									<li><a href="<?php echo base_url()."states/telangana"; ?>"><?php echo "Telangana"; ?></a></li>
									<li><a href="<?php echo base_url()."states/odisha"; ?>"><?php echo "Odisha"; ?></a></li>
								</ul>
							</li>
							<li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "Cities"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."cities/chennai"; ?>"><?php echo "Chennai"; ?></a></li>
									<li><a href="<?php echo base_url()."cities/delhi"; ?>"><?php echo "Delhi"; ?></a></li>
									<li><a href="<?php echo base_url()."cities/bengaluru"; ?>"><?php echo "Bengaluru"; ?></a></li>
									<li><a href="<?php echo base_url()."cities/hyderabad"; ?>"><?php echo "Hyderabad"; ?></a></li>
									<li><a href="<?php echo base_url()."cities/kochi"; ?>"><?php echo "Kochi"; ?></a></li>
									<li><a href="<?php echo base_url()."cities/thiruvananthapuram"; ?>"><?php echo "Thiruvananthapuram"; ?></a></li>
								</ul>
							</li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."business"; ?>"><?php echo "Business"; ?></a></li>
							<li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "Sport"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."sport/cricket"; ?>"><?php echo "Cricket"; ?></a></li>
									<li><a href="<?php echo base_url()."sport/football"; ?>"><?php echo "Football"; ?></a></li>
									<li><a href="<?php echo base_url()."sport/tennis"; ?>"><?php echo "Tennis"; ?></a></li>
									<li><a href="<?php echo base_url()."sport/other"; ?>"><?php echo "Other"; ?></a></li>
								</ul>
							</li>
							<li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "Entertainment"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."entertainment/english"; ?>"><?php echo "English"; ?></a></li>
									<li><a href="<?php echo base_url()."entertainment/hindi"; ?>"><?php echo "Hindi"; ?></a></li>
									<li><a href="<?php echo base_url()."entertainment/kannada"; ?>"><?php echo "Kannada"; ?></a></li>
									<li><a href="<?php echo base_url()."entertainment/malayalam"; ?>"><?php echo "Malayalam"; ?></a></li>
									<li><a href="<?php echo base_url()."entertainment/tamil"; ?>"><?php echo "Tamil"; ?></a></li>
									<li><a href="<?php echo base_url()."entertainment/telugu"; ?>"><?php echo "Telugu"; ?></a></li>
									<li><a href="<?php echo base_url()."entertainment/review"; ?>"><?php echo "Review"; ?></a></li>
								</ul>
							</li>
							<li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "Galleries"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."galleries/nation"; ?>"><?php echo "Nation"; ?></a></li>
									<li><a href="<?php echo base_url()."galleries/world"; ?>"><?php echo "World"; ?></a></li>
									<li><a href="<?php echo base_url()."galleries/sport"; ?>"><?php echo "Sport"; ?></a></li>
									<li><a href="<?php echo base_url()."galleries/Entertainment"; ?>"><?php echo "Entertainment"; ?></a></li>
									<li><a href="<?php echo base_url()."galleries/0ther"; ?>"><?php echo "Other"; ?></a></li>
								</ul>
							</li>
							<li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "Videos"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."videos/videos-nation"; ?>"><?php echo "Nation"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/videos-world"; ?>"><?php echo "World"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/videos-sport"; ?>"><?php echo "Sport"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/Entertainment"; ?>"><?php echo "Entertainment"; ?></a></li>
									<li><a href="<?php echo base_url()."videos/videos-other"; ?>"><?php echo "Other"; ?></a></li>
								</ul>
							</li>
							<li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "Lifestyle"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."lifestyle/tech"; ?>"><?php echo "Tech"; ?></a></li>
									<li><a href="<?php echo base_url()."lifestyle/health"; ?>"><?php echo "Health"; ?></a></li>
									<li><a href="<?php echo base_url()."lifestyle/travel"; ?>"><?php echo "Travel"; ?></a></li>
									<li><a href="<?php echo base_url()."lifestyle/food"; ?>"><?php echo "Food"; ?></a></li>
									<li><a href="<?php echo base_url()."lifestyle/books"; ?>"><?php echo "Books"; ?></a></li>
									<li><a href="<?php echo base_url()."lifestyle/spirituality"; ?>"><?php echo "Spirituality"; ?></a></li>
								</ul>
							</li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."Specials"; ?>"><?php echo "Specials"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."opinions"; ?>"><?php echo "Opinions"; ?></a></li>							
							<li class="all-menu-sub"><a href="<?php echo base_url()."opinions/editorials"; ?>"><?php echo "Editorials"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."prabhu-chawla/ask-prabhu"; ?>"><?php echo "Ask Prabhu"; ?></a></li>
							<li class="dropdown-submenu all-menu">
								<a  href="#"><?php echo "Columns"; ?> <i class="fa fa-angle-right"></i></a>
								<ul class="dropdown-menu all-sub-menu">
									<li><a href="<?php echo base_url()."prabhu-chawla/column"; ?>"><?php echo "Prabhu Chawla"; ?></a></li>
									<li><a href="<?php echo base_url()."opinions/columns/t-j-s-george"; ?>"><?php echo "T J S George"; ?></a></li>
									<li><a href="<?php echo base_url()."opinions/columns/s-gurumurthy"; ?>"><?php echo "S Gurumurthy"; ?></a></li>
									<li><a href="<?php echo base_url()."opinions/columns/ravi-shankar"; ?>"><?php echo "Ravi Shankar"; ?></a></li>
									<li><a href="<?php echo base_url()."opinions/columns/shankkar-aiyar"; ?>"><?php echo "Shankkar Aiyar"; ?></a></li>
									<li><a href="<?php echo base_url()."opinions/columns/shampa-dhar-kamath"; ?>"><?php echo "Shampa Dhar-Kamath"; ?></a></li>
									<li><a href="<?php echo base_url()."opinions/columns/karamatullah-k-ghori"; ?>"><?php echo "Karamatullah K Ghori"; ?></a></li>
								</ul>
							</li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."magazine"; ?>"><?php echo "Magazine"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."thesundaystandard"; ?>"><?php echo "The Sunday Standard"; ?></a></li>
							<li class="all-menu-sub"><a href="http://www.edexlive.com/" target="_blank"><?php echo "Edex"; ?></a></li>
							<li class="all-menu-sub"><a href="http://www.indulgexpress.com/" target="_blank"><?php echo "Indulge"; ?></a></li>
							<li class="all-menu-sub"><a href="<?php echo base_url()."todays-paper"; ?>"><?php echo "Today's paper"; ?></a></li>
							<li class="all-menu-sub"><a href="http://epaper.newindianexpress.com" target="_blank" ><?php echo "E-paper"; ?></a></li>
					 </ul>
				</div>
</div>
            <ul class="MobileNav">
            <li class="MobileShare dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span><i class="fa fa-share-alt" aria-hidden="true"></i><i class="fa fa-caret-down" aria-hidden="true"></i></span></a><ul class="dropdown-menu">
          <li><a href="<?php echo $header_details['facebook_url'];?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
          <li><a href="<?php echo $header_details['google_plus_url'];?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
          <li><a href="<?php echo $header_details['twitter_url'];?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
          <!--<li><a href="http://www.pinterest.com/newindianexpres" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
          <li><a href="https://instagram.com/newindianexpress/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>-->  
          <li><a href="<?php echo $header_details['rss_url'];?>" target="_blank"><i class="fa fa-rss"></i></i></a></li>
          
        </ul></li>
            </ul>
</div>
  <div class="col-lg-6 col-md-6 col-sm-7 col-xs-6">
    <div class="main_logo">
      <?php 
echo '<a href="'.base_url().'">
<img src="'.image_url.'images/FrontEnd/images/sunday_std_logo.jpg"></a>';
?>
<!--<div class="loc" id="current_time">
				<?php 
				$day = date('l');
				$month = date('F');
	echo '<span>'.$day.', '.$month.', '.date('d').', '.date('Y').' &nbsp;&nbsp;'.date('h:i:s A ').'</span>';
			    ?>
      </div>-->
   <?php //echo '<div id="mobile_date">'.date('d')." <span>".$month."</span> ".date('Y').'</div>'; ?>
   <div class="social-icons-wrapper margin-bottom-10">
		 <a class="social-icon-set" href="https://play.google.com/store/apps/details?id=com.newindianexpress.news" rel="nofollow" target="_blank"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/AND.png"></a>
		 <a class="social-icon-set" href="https://itunes.apple.com/in/app/the-new-indian-express-app/id1239342233?mt=8" rel="nofollow" target="_blank"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/Apple.png"></a>
		 <a class="social-icon-set" href="<?php echo $header_details['rss_url'];?>" rel="nofollow" target="_blank"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/RSS.png"></a>
		 <a href="https://www.youtube.com/user/thenewindianxpress" target="_blank" class="social-icon-set social-icon-set-youtube"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/Youtube.png"></a>
	 <div class="loc" id="current_time" style="font-size: 12px;"><?php echo '<span>'.$day.', '.$month.', '.date('d').', '.date('Y').'</span>'; ?></div>
   <?php //echo '<div id="mobile_date">'.date('d')." <span>".$month."</span> ".date('Y').'</div>'; ?>
   <a class="social-icon-set social-icon-set-fb" href="<?php echo $header_details['facebook_url'];?>" rel="nofollow" target="_blank"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/FB.png"></a>
		 <a class="social-icon-set" href="<?php echo $header_details['google_plus_url'];?>" rel="nofollow" target="_blank"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/GP.png"></a>
		 <a class="social-icon-set" href="<?php echo $header_details['twitter_url'];?>" rel="nofollow" target="_blank"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/Twitter.png"></a>
		 <a class="social-icon-set" href="https://instagram.com/newindianexpress/" target="_blank"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-icon-set/Insta.png"></a>
    </div>
    </div>
  </div>
 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 search-padd-left-0">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <ul class="MobileNav">
                   <?php if($content['page_param']!="home") { ?>
                   <li>
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button></li><?php } ?>
                   <li class="MobileSearch"><a class="SearchHide" href="javascript:void(0);"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                   
                  </ul>
       <div class="large-screen-search">
        <div class="search1">
          <form class="navbar-form formb" action="<?php echo base_url(); ?>topic"  name="SimpleSearchForm" id="SimpleSearchForm" method="get" role="form">
            <div class="input-group">
              <input type="text" style="display:none;" class="form-control tbox" placeholder="Search" name="search_term" id="srch-term" value="<?php echo @$_GET['search_term'];?>">
              <div class="input-group-btn">
                <input type="hidden" class="form-control tbox"  name="home_search" value="H" id="home_search">
                <button style="display:none;" class="btn btn-default btn-bac" id="search-submit" type="submit"><i class="fa fa-search"></i></button>
				<button class="btn btn-default btn-bac" id="search_btn"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
		  <script type="text/javascript">
			$(document).ready(function() {
				$('#search_btn').on('click',function(e){
					e.preventDefault();
					$('#srch-term').show();
					$('#search-submit').show();
					$(this).hide();
				});
				$('#SimpleSearchForm').submit(function(e){
					e.preventDefault();
					if($('#srch-term').val().trim()==''){
						$('#error_throw').html('Please provide search keyword(s)').addClass('error');
						$('#srch-term').addClass('error');
					}else{
						$('#error_throw').html('').removeClass('error');
						$('#srch-term').removeClass('error');
						window.location.href=base_url+'topic?term='+$('#srch-term').val()+'&request=ALL&search=short';
					}
					
					
				});
			});
		</script>
          <label id="error_throw"></label>
        </div>
        
        </div>
      </div>
    </div>
  </div>
</div> 