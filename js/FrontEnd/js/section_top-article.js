$(function() {
	if(Gallery_pagination_count > 1){
	/*	$('.slick-next').click(function(){
					var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
				  console.log(currentSlide);
				  var index = currentSlide+1;
				  $('#gallery_pagination').bootstrapPaginator("show", currentSlide+1);
				});
				$('.slick-prev').click(function(){
				var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
				  console.log(currentSlide);
				  if(currentSlide==0){
				  $('#gallery_pagination').bootstrapPaginator("show", currentSlide+1);
				  }else{
				   $('#gallery_pagination').bootstrapPaginator("show", currentSlide+1);
				  }
				  var index = currentSlide+1;
				});*/

				var clicked = false;
				$("#auto-play").click(function(){
					if (clicked) {
					   $(this).find('i').toggleClass('fa-play fa-pause');
						$('.GalleryDetailSlide').slick('slickPause');
						$('.GalleryDetailSlide').unbind('beforeChange');
						clicked = false;
					}
					else {
					   $(this).find('i').toggleClass('fa-play fa-pause');
						$('.GalleryDetailSlide').slick('slickPlay', true);
						$('.GalleryDetailSlide').on('beforeChange', function(event, slick, currentSlide, nextSlide){
							console.log(nextSlide+1);
							// $('#gallery_pagination').bootstrapPaginator("show", nextSlide+1);
							 var index = nextSlide+1;
						});
						  clicked = true;
					}
				});
			/*	$('.GalleryDetailSlide').on('swipe', function(event, slick, direction){
				  console.log(direction);
				  console.log(slick);
				  if(direction=='left'){
					 var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
				  console.log(currentSlide);
				  $('#gallery_pagination').bootstrapPaginator("show", currentSlide+1); 
				  }else if(direction=='right'){
					 var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
				  console.log(currentSlide);
				  if(currentSlide==0){
				  $('#gallery_pagination').bootstrapPaginator("show", currentSlide+1);
				  }else{
				   $('#gallery_pagination').bootstrapPaginator("show", currentSlide+1);
				  } 
				  }
				 var index = currentSlide+1;
				});*/
				
			/*					var element = $('#gallery_pagination');
         var options = {
		  bootstrapMajorVersion:3,
            currentPage: 1,
            totalPages: Gallery_pagination_count,
			useBootstrapTooltip:true,
			tooltipTitles: function (type, page, current) {
                switch (type) {
                case "first":
                    return "Go To First Image <i class='icon-fast-backward icon-white'></i>";
                case "prev":
                    return "Go To Previous Image <i class='icon-backward icon-white'></i>";
                case "next":
                    return "Go To Next Image <i class='icon-forward icon-white'></i>";
                case "last":
                    return "Go To Last Image <i class='icon-fast-forward icon-white'></i>";
                case "page":
                    return "Go to Image " + page + "";
                }
            },
			itemContainerClass: function (type, page, current) {
                return (page === current) ? "active" : "cursor-pointer";
            },
			onPageClicked: function(e,originalEvent,type,page){
               $('.GalleryDetailSlide').slick('slickGoTo', page-1);
            }
        }
            element.bootstrapPaginator(options);*/
	}
	 $("[data-toggle=popover]").popover({
	 html: true, 
	 content: function() {
			  return $('.popover-content').html();
			}
	});
	
	$('#popoverId').popover({
	 html: true, 
	 title: 'Share via Email',
	 content: function() {
			  return $('.popover-content').html();
			}
	});
	$('#popoverId').click(function (e) {
	e.stopPropagation();
	});
	$(document).click(function (e) {
	if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
	$('#popoverId').popover('hide');
	}
	});
	
   setTimeout(function(){ update_hits(); }, 2000);
});
function mail_form_validate() {
	var error_free=true;
	var name = $('.popover input[name=sender_name]').val();
	var share_email = $('.popover input[name=share_email]').val();
	var refer_email = $('.popover input[name=refer_email]').val();
	
	if(name==''){
	$('.popover input[name=sender_name]').addClass('error');
	var error_free=false;
	}
	else if(share_email==''){
	$('.popover input[name=share_email]').addClass('error');
	var error_free=false;
	}else if(share_email!=''){
	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	var is_email=re.test(share_email);
	if(!is_email){
	$('.popover input[name=share_email]').addClass('error');
	var error_free=false;
	}
	else if(refer_email==''){
	$('.popover input[name=refer_email]').addClass('error');
	var error_free=false;
	}else if(refer_email!=''){
	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	var is_email=re.test(refer_email);
	if(!is_email){
	$('.popover input[name=refer_email]').addClass('error');
	var error_free=false;
	}else{
		var formData = {
			'content_id'        : $('input[name=content_id]').val(),
			'content_type_id'   : $('input[name=content_type_id]').val(),
            'name'              : $('.popover input[name=sender_name]').val(),
            'share_email'       : $('.popover input[name=share_email]').val(),
            'refer_email'       : $('.popover input[name=refer_email]').val(),
			'share_content'     : $('#content_head').text(),
			'message'           : $('.popover textarea[name=message]').val(),
			'share_url'         : content_url,
        };
		$.ajax({
			url			: base_url+'user/commonwidget/share_article_via_email',
			method		: 'post',
			data		: formData,
			beforeSend	: function() {				
			$('.popover-title').html('Share Via Email <span><img style="width:15px"  src="'+base_url+'images/FrontEnd/images/ajax-loader.gif" ></span>');
			},
			success		: function(result){
			$('.popover-title').html('Share Via Email');
			$('.popover  #message').after('<span id="share_success" style="color:green;">Mail sent</span>');
            $('form[name="mail_share"]')[0].reset();
			setTimeout(function(){ $('#share_success').hide();$(".popover").removeClass('mail_sharing_open'); }, 5000);
			var mail_share_count= $(".PrintSocial .csbuttons-count:eq(0)").text();
				   if(mail_share_count == ''){
					   var mail_share_count= 0;
				   }else{
				    var mail_share_count = mail_share_count.replace(/[\(\)-]/g, "");
				   }
			$(".PrintSocial .csbuttons-count:eq(0)").text((parseInt(mail_share_count)+1));
			}
			});
		return false;
	}
	}
	}
	
	$("textarea,input").keyup(function(){
        $("input,textarea").removeClass("error");
    });
    
}

function update_hits()
{
	var formData = {
			'update_emailed_count'  : "section",
			'content_id'            : $('input[name=content_id]').val(),
			'content_type_id'       : $('input[name=content_type_id]').val(),
        };
		$.ajax({
			url			: base_url+'user/commonwidget/update_hits',
			method		: 'post',
			data		: formData,
			dataType    : 'json',
			success		: function(result){
			$(".PrintSocial .csbuttons-count:eq(0)").text(parseInt(result.emailed));
			},
		});
}