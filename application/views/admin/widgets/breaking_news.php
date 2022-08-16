<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!--<div class="line" style="visibility:hidden;">-->
		<div class="breakingNews line" style="display:none;">
            <h3 class="breaking">LATEST NEWS</h3>
        <div class="demo top-breaking" id="breaking_news">
			<?php /*?><?php 
			$view_mode = $content['mode'];
			$param = $content['page_param'];
			$breaking_news = $this->widget_model->get_widget_breakingNews_content($view_mode);
			
			$scroll_speed = $this->widget_model->select_setting($view_mode);
			$news = "";
			$domain_name =  base_url();
			foreach($breaking_news as $news_content)
			{
				$news_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $news_content['Title']);   //to remove first<p> and last</p>  tag
				if($news_content['Content_ID'] != '')
				{
					$content_type = 1;
					$is_home = $content['is_home_page'];
					if($view_mode=='live'){
					$content_details = $this->widget_model->get_contentdetails_from_database($news_content['Content_ID'], $content_type,$is_home,$view_mode);
					$content_url = $content_details[0]['url'];
					}else{
					$content_url = $news_content['url'];
					}
					$live_article_url = $domain_name.$content_url."?pm=".$param;					
					$news_content = '<a  href="'.$live_article_url.'" class="article_click"  >'.$news_title.'</a>';
				}
				else
				{
					$news_content = $news_title;
				}
				
				$news .=  ' &nbsp; &nbsp; '.$news_content.'&nbsp; &nbsp; | ';
			}		

			?>
			  <marquee cbehavior="scroll" direction="left" height="26px" scrollamount="<?php if($scroll_speed['breakingNews_scrollSpeed'] != "" && $scroll_speed['breakingNews_scrollSpeed'] != 0) { echo $scroll_speed['breakingNews_scrollSpeed']; } else { echo 5; } ?>"> 
             <?php echo $news; ?>
            </marquee><?php */?>
			<!--<div style="display: block-inline; height: 26pxpx; overflow: hidden;" class="pointer"><div style="float: left; white-space: nowrap; padding: 0px 638px;"> 
            </div></div>-->
            
			  </div>
      </div>
          </div>
      </div>
<script>
$(document).ready(function(){
 $.ajax({
			url			: '<?php echo base_url(); ?>user/commonwidget/get_breaking_news_content',
			method		: 'get',
			dataType    : 'json',
			data		: { type: "1", param: '<?php echo $content['close_param'];?>',mode:'<?php echo $content['mode'];?>', is_home: '<?php echo $content['is_home_page'];?>',},
			beforeSend	: function() {				
			},
			success		: function(result){ 
			console.log(result);
			if(result.news.trim()!='no_news'){
				   //$(".line").css("visibility", "visible");
				   $(".line").css("display", "block");
				   $(".line").fadeIn();
				   $('#breaking_news').html(result.news).hide().fadeIn({ duration: 1000 });
				    $('.top-breaking').slick({       
					        dots: false,
							infinite: true,
							speed: 1000,
							autoplaySpeed: parseInt(result.scroll_amount),
							slidesToShow: 1,
							autoplay: true,
					        arrows: true,
							slidesToScroll: 1
						});
					$('.slick-prev').addClass('fa fa-chevron-left');
					$('.slick-next').addClass('fa fa-chevron-right');	
			}else{
			 //$(".line").css("visibility", "hidden");
			 $(".line").css("display", "none");
			}
				   }			
		});
});
</script>