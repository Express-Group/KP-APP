$(document).ready(function() {
    $('.galleries_slider').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
        slidesToShow: 3,
		lazyLoad: 'ondemand',
        slidesToScroll: 1,
		 responsive: [{
            breakpoint: 450,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }]
    });
	$('.others_slider').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 4,
        slidesToScroll: 1,
		 responsive: [{
            breakpoint: 767,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 450,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 370,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });
	
/*	$('.others_slider_mobile').slick({
        dots: false,
        infinite: false,
        speed: 500,
        slidesToShow: 2,
        slidesToScroll: 1
    });*/
	

    $('.MagazinesVoiceSlide').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 3,
        slidesToScroll: 3,
		 responsive: [{
            breakpoint: 500,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }, {
            breakpoint: 350,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });
/*	 $('.MagazinesVoiceSlide-Mobile').slick({
        dots: false,
        infinite: false,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1
    });*/
	/*$('.Entertain-Lead-Slide').slick({
        dots: false,
        infinite: false,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1
    });*/
	
	
	$('.GalleryDetailSlide').slick({
        dots: true,
        infinite: false,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1
    });
	$('.HomeGallery').slick({
        dots: false,
        infinite: false,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1
    });
	$('.HomeVideo').slick({
        dots: false,
        infinite: false,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1
    });
	$('.GalleryLeadSlider').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1
    });
	$('.VideoLeadSlider').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1
    });
	$('.HomeLeadStories').slick({
        dots: true,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
		arrows: false,
        autoplay: true,
        slidesToScroll: 1
		
    });
	$('.HealthSlider').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        autoplay: true,
        slidesToScroll: 1
		
    });
	$('.EnterLeadSlider').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        autoplay: true,
		arrows: true,
        slidesToScroll: 1
    });
	$('.Enter_Gallery').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        autoplay: true,
		arrows: true,
        slidesToScroll: 1
    });
	$('.HomeSportsSlider').slick({       dots: true,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        autoplay: true,
		arrows: false,
        slidesToScroll: 1
    });
	$('.HomeEntertainment').slick({       
		dots: true,
        infinite: true,
        speed: 500,
        autoplayspeed: 500,
		lazyLoad: 'ondemand',
        slidesToShow: 1,
        autoplay: true,
		arrows: false,
        slidesToScroll: 1
    });
	
	
	
/*$('.GalleryDetailSlide .slick-dots li').mouseenter(function(){  
    $(this).trigger('click');  
}); 
	
	$('.GalleryDetailSlide .slick-dots li').bind('mouseover', function(){ 
    $(this).trigger('click'); 
});
	*/

	/*
    $('.variable-width').slick({
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        centerMode: true,
        variableWidth: true
    });
    $('.data').slick();
    $('.one-time').slick({
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        adaptiveHeight: true
    });
    $('.uneven').slick({
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 4
    });
    $('.responsive').slick({
        dots: true,
        infinite: false,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });

    $('.center').slick({
        centerMode: true,
        infinite: true,
        centerPadding: '60px',
        slidesToShow: 3,
        speed: 500,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 3
            }
        }, {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1
            }
        }]
    });
    $('.lazy').slick({
        lazyLoad: 'ondemand',
        slidesToShow: 3,
        slidesToScroll: 1,
        speed: 500
    });
    $('.autoplay').slick({
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000
    });

    $('.fade').slick({
        dots: true,
        infinite: true,
        speed: 500,
        fade: true,
        slide: 'div',
        cssEase: 'linear'
    });

    $('.add-remove').slick({
        dots: true,
        slidesToShow: 3,
        speed: 500,
        slidesToScroll: 3
    });
    var slideIndex = 1;
    $('.js-add-slide').on('click', function() {
        slideIndex++;
        $('.add-remove').slick('slickAdd','<div><h3>' + slideIndex + '</h3></div>');
    });

    $('.js-remove-slide').on('click', function() {
        $('.add-remove').slick('slickRemove',slideIndex - 1);
        if (slideIndex !== 0){
            slideIndex--;
        }
    });

    $('.filtering').slick({
        dots: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 4
    });
    var filtered = false;
    $('.js-filter').on('click', function() {
        if (filtered === false) {
            $('.filtering').slick('slickFilter',':even');
            $(this).text('Unfilter Slides');
            filtered = true;
        } else {
            $('.filtering').slick('slickUnfilter');
            $(this).text('Filter Slides');
            filtered = false;
        }
    });

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 500,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        speed: 500,
        asNavFor: '.slider-for',
        dots: true,
        centerMode: true,
        focusOnSelect: true,
        slide: 'div'
    });

    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 166) {
            $('.fixed-header').show();
        } else {
            $('.fixed-header').hide();
        }
    });

    $('ul.nav a').on('click', function(event) {
        event.preventDefault();
        var targetID = $(this).attr('href');
        var targetST = $(targetID).offset().top - 48;
        $('body, html').animate({
            scrollTop: targetST + 'px'
        }, 300);
    });

    $('.single-item-rtl').slick({
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        rtl: true
    });
    $('.multiple-items-rtl').slick({
        dots: true,
        infinite: true,
        slidesToShow: 3,
        speed: 500,
        slidesToScroll: 3,
        rtl: true
    });*/
	
	
	
	//marquee
	

});
