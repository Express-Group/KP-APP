<div class="row">
<?php
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$is_home = $content['is_home_page'];
$view_mode = $content['mode'];
$header_details = $this->widget_model->select_setting($view_mode);
$search_term    = $this->input->get('search_term');
?>
<script src="<?php print image_url ?>js/FrontEnd/js/highcharts.js"></script>
<div class="MobileInput"><form class="" action="<?php echo base_url(); ?>topic"  name="SimpleSearchForm" id="mobileSearchForm" method="get" role="form">
<input type="text" placeholder="Search..." name="search_term" id="mobile_srch_term" value="<?php echo $search_term;?>"/> <a href="javascript:void(0);" id="mobile_search"><img src="<?php echo image_url; ?>images/FrontEnd/images/search-mob.png" /></a></form>
</div>
<div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 share-padd-right-0">
<div class="social_icons SocialCenter mobile-share"><span> <a class="android" href="https://play.google.com/store/apps/details?id=com.newindianexpress.news" rel="nofollow" target="_blank"><i class="fa fa-android" aria-hidden="true"></i></a> <a class="apple" href="https://itunes.apple.com/in/app/new-indian-express-official/id968640811?mt=8" rel="nofollow" target="_blank" ><i class="fa fa-apple" aria-hidden="true"></i></a></span> <a class="fb" href="<?php echo $header_details['facebook_url'];?>" rel="nofollow" target="_blank"><i class="fa fa-facebook"></i></a> <a class="google" href="<?php echo $header_details['google_plus_url'];?>" rel="nofollow" target="_blank"><i class="fa fa-google-plus"></i></a> <a class="twit" href="<?php echo $header_details['twitter_url'];?>" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i></a><!--<a class="pinterest" href="http://www.pinterest.com/newindianexpres" rel="nofollow" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
          <a class="instagram" href="https://instagram.com/newindianexpress/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>--> <a class="rss" href="<?php echo $header_details['rss_url'];?>" rel="nofollow" target="_blank"><i class="fa fa-rss"></i></a> </div>
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
  <div class="col-lg-6 col-md-6 col-sm-7 col-xs-6">
    <div class="main_logo">
      <?php 
echo '<a href="'.base_url().'">
<img src="'.image_url.$header_details['sitelogo'].'"></a>';
?>
<!--<div class="loc" id="current_time">
				<?php 
				$day = date('l');
				$month = date('F');
	echo '<span>'.$day.', '.$month.', '.date('d').', '.date('Y').' &nbsp;&nbsp;'.date('h:i:s A ').'</span>';
			    ?>
      </div>-->
	  <div class="loc" id="current_time" style="font-size: 12px;"><?php echo '<span>'.$day.', '.$month.', '.date('d').', '.date('Y').'</span>'; ?></div>
   <?php //echo '<div id="mobile_date">'.date('d')." <span>".$month."</span> ".date('Y').'</div>'; ?>
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
        <form class="navbar-form formb hide-search-custom" action="<?php echo base_url(); ?>topic"  name="SimpleSearchForm" id="SimpleSearchForm" method="get" role="form">
            <div class="input-group">
			<input name="serach-next" type="hidden" value="1" /> 
              <input type="text"  class="form-control tbox" placeholder="Search" name="q" id="srch-term" value="<?php echo $search_term;?>">
              <div class="input-group-btn">
                <input type="hidden" class="form-control tbox"  name="home_search" value="H" id="home_search">
                <button class="btn btn-default btn-bac" id="search-submit" type="submit"><i class="fa fa-search"></i></button>
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
					}else{
						$('#error_throw').html('').removeClass('error');
						$('#srch-term').removeClass('error');
						window.location.href=base_url+'topic?term='+$('#srch-term').val()+'&request=ALL&search=short';
					}
					
					
				});
				$('#mobileSearchForm').submit(function(e){
					e.preventDefault();
					if($('#mobile_srch_term').val().trim()==''){
						$('#mobile_srch_term').addClass('error');
					}else{
						$('#mobile_srch_term').removeClass('error');
						window.location.href=base_url+'topic?term='+$('#mobile_srch_term').val()+'&request=ALL&search=short';
					}
					
					
				});
			});
		</script>
        </div>
      </div>
    </div>
  </div>
</div>