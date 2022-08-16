$(function() {
    $("#search-submit").click(function() {
        x = document.SimpleSearchForm, input = x.search_term.value;
        var e = /[^\w\s]/gi;
        return 0 == input.trim().length ? ($("#srch-term").addClass("error"), $("#error_throw").addClass("error").text("Please provide search keyword(s)").show(), !1) : 1 == e.test(input) ? ($("#srch-term").addClass("error"), $("#error_throw").addClass("error").text("Please enter alphanumeric search keyword(s)").show(), !1) : input.length > 200 ? ($("#srch-term").addClass("error"), $("#error_throw").text("Please do not enter more than 200 characters!").show(), !1) : ($("#error_throw").text(""), !0)
    }), $("#srch-term").keyup(function() {
        $("#error_throw").text(""), $("#srch-term").removeClass("error")
    });
	               
var gallery_url = content_url.split('.html');
localStorage.setItem("galleryurl", gallery_url[0]);
//localStorage.removeItem("galleryurl");
console.log(localStorage.getItem("galleryurl"));
var parseGalleryurl = localStorage.getItem("galleryurl");
$('.slick-next').click(function(){
var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
console.log(currentSlide);
var index = currentSlide+1;
$('#gallery_pagination').twbsPagination("show", currentSlide+1);
window.history.pushState('', '', parseGalleryurl+'-'+index+'.html');
ga('send', {
hitType: 'pageview',
page: location.pathname
});
});
$('.slick-prev').click(function(){
var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
console.log(currentSlide);
if(currentSlide==0){
$('#gallery_pagination').twbsPagination("show", currentSlide+1);
}else{
$('#gallery_pagination').twbsPagination("show", currentSlide+1);
}
var index = currentSlide+1;
window.history.pushState('', '', parseGalleryurl+'-'+index+'.html');
ga('send', {
hitType: 'pageview',
page: location.pathname
});
});
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
$('#gallery_pagination').twbsPagination("show", nextSlide+1);
var index = nextSlide+1;
window.history.pushState('', '', parseGalleryurl+'-'+index+'.html');
ga('send', {
  hitType: 'pageview',
  page: location.pathname
});
});
clicked = true;
}
});
$('.GalleryDetailSlide').on('swipe', function(event, slick, direction){
console.log(direction);
console.log(slick);
if(direction=='left'){
var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
console.log(currentSlide);
$('#gallery_pagination').twbsPagination("show", currentSlide+1); 
}else if(direction=='right'){
var currentSlide = $('.GalleryDetailSlide').slick('slickCurrentSlide');
console.log(currentSlide);
if(currentSlide==0){
$('#gallery_pagination').twbsPagination("show", currentSlide+1);
}else{
$('#gallery_pagination').twbsPagination("show", currentSlide+1);
} 
}
var index = currentSlide+1;
window.history.pushState('', '', parseGalleryurl+'-'+index+'.html');
ga('send', {
hitType: 'pageview',
page: location.pathname
});
});	
 $('#gallery_pagination').twbsPagination({
        totalPages: TotalIndex,
		startPage: parseInt(currentimageIndex),
        visiblePages: 5,
		initiateStartPageClick: true,
		loop: true,
        onPageClick: function (event, page) {
          $('.GalleryDetailSlide').slick('slickGoTo', page-1);
				  window.history.pushState('', '', parseGalleryurl+'-'+page+'.html');
			   ga('send', {
						  hitType: 'pageview',
						  page: location.pathname
						});
        }
    });	
});