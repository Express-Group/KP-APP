<div class="row">
<?php $social_urls = $this->widget_model->select_setting()->row_array(); ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="social_icons SocialCenter"> <a class="fb" href="<?php echo $social_urls['facebook_url'];?>" target="_blank"><i class="fa fa-facebook"></i></a> <a class="google" href="<?php echo $social_urls['google_plus_url'];?>" target="_blank"><i class="fa fa-google-plus"></i></a> <a class="twit" href="<?php echo $social_urls['twitter_url'];?>" target="_blank"><i class="fa fa-twitter"></i></a> <a class="rss" href="<?php echo $social_urls['rss_url'];?>" target="_blank"><i class="fa fa-rss"></i></a> </div>
            <div class="search1">
               <form class="navbar-form formb" action="<?php echo base_url(); ?>topic"  name="SimpleSearchForm" id="SimpleSearchForm" method="post" role="form">
                <div class="input-group">
                    <input type="text" class="form-control tbox" placeholder="Search" name="search_term" id="srch-term" value="<?php echo @$_POST['search_term'];?>">
                    <div class="input-group-btn">
					<input type="hidden" class="form-control tbox"  name="home_search" value="H" id="home_search">
                    <button class="btn btn-default btn-bac" id="search-submit" type="submit"><i class="fa fa-search"></i></button>
                  </div>
                  </div>
              </form>
			   <label id="error_throw"></label>
              </div>
              </div>
              </div>