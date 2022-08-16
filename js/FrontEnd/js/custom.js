$(function() {
	date_time('current_time');
	if(call_breadcrumb==1){ // calling Breadcrumb here
	  $('.navbar-nav li').removeClass('active');
      if ($('#tab'+Section_id).length) {
	  $('#tab'+Section_id).addClass('active');
	  }
	  else if ($('#tab'+PSection_id).length) 
	  {
	     $('#tab'+PSection_id).addClass('active');
	  }

	$.ajax({
	  url			: base_url+'user/commonwidget/get_breadcrumb',
	  method		: 'post',
	  dataType: 'html',
	  data : {section: Section_id, mode: view_mode},
	  success:function(response){
	  $("#bcrum_section").html(response);
	  }
	  });
	}
	if(call_otherstories==1){ // calling Right Side Other stories here
	$.ajax({
	  url			: base_url+'user/commonwidget/get_rightside_stories',
	  method		: 'post',
	  dataType: 'html',
	  data : {section: Section_id, mode: view_mode, type:1},
	  success:function(response){
	  $("#other_stories_right").html(response);
	  }
	  });
	}
    $("#search-submit").click(function() {
	 var term = $('#srch-term').val();
     var re = /^[ A-Za-z0-9_@.,#&+-:;'"/]*$/;
	if (term.trim().length==0) {
		$("#srch-term").addClass("error");
		$("#error_throw").addClass("error").text("Please provide search keyword(s)").show();
		return false;
		}
		else if(!re.test(term))
		{
		$("#srch-term").addClass("error");
		$("#error_throw").addClass("error").text("Please enter alphanumeric search keyword(s)").show();
		return false;
		}
		else {
		if(term.trim().length > 200)
		{
		$("#srch-term").addClass("error");
		$("#error_throw").text("Please do not enter more than 200 characters!").show();
		return false;
		}
			return true; 
		}
});
	$("#srch-term").keyup(function() {
        $("#error_throw").text(""), $("#srch-term").removeClass("error")
    });
	 $("#mobile_search").click(function() {
	 var term = $('#mobile_srch_term').val();
    // var re = /^[ A-Za-z0-9_@.,#&+-:;'"/]*$/;
	if (term.trim().length==0) {
		$("#mobile_srch_term").addClass("error");
		alert("Please provide search keyword(s)!");
		return false;
		}
		else {
		if(term.trim().length > 200)
		{
		$("#mobile_srch_term").addClass("error");
		alert("Please do not enter more than 200 characters!");
		return false;
		}
			$( "#mobileSearchForm" ).submit();
		}
});

      $(".MobileSearch .SearchHide").click(function(){
	  $(".MobileInput").toggle();
	  $(".SearchHide").toggleClass("SearchFade")
	 });
	
	 $("#submit_newsletter").click(function() {
		 subscribe_newsletter();
	 });
	 $('#newsletter_form').submit(function(e){
		 subscribe_newsletter();
		e.preventDefault(); // Prevent the original submit
	});
	function subscribe_newsletter()
	 {
	  x = document.newsletter_form, email_address = x.email_newsletter.value;
	 if(email_address.trim().length){
	   var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	var is_email=re.test(email_address);
	if(is_email){
	  		var formData = {
			'email_newsletter'   : $('[name=email_newsletter]').val(),
        };
		$.ajax({
			url			: base_url+'user/commonwidget/subscribe_newsletter',
			method		: 'post',
			data		: formData,
			beforeSend	: function() {				
			},
			success		: function(result){
			$("#news_error_throw").text(result); $("#news_error_throw").css({"color":"green","float": "left", "width":"233px"});	
			setTimeout(function(){$("#email-newsletter").val('');$("#news_error_throw").text(''); $("#news_error_throw").css("color","");	},2000);
			},
		});
      }else
	  {
		$("#email-newsletter").addClass("error");
		$("#news_error_throw").css("color","red").text("Please provide Valid Email address").show();
	  }
	 }else
	 {
		$("#email-newsletter").addClass("error");
		$("#news_error_throw").css("color","red").text("Please provide Email address").show();
	 }
	 $("#email-newsletter").keyup(function() {
        $("#news_error_throw").text(""); $("#email-newsletter").removeClass("error");
    });
	 }
	
});
function date_time(id)
	{
		// Display the time in 24 or 12 hour time?
	    // 0 = 24, 1 = 12
	     var my12_hour = 1;
		date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
		// Set up the hours for either 24 or 12 hour display:
		if (my12_hour) {
			dn = "AM";
			if (h > 12) { dn = "PM"; h = h - 12; }
			if (h == 0) { h = 12; }
		} else {
			dn = "";
		}
		if(d<10)
        {
                d = "0"+d;
        }
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
		result = '';
		result += '<span>'+days[day]+', '+months[month]+', '+d+', '+year+'&nbsp;&nbsp;'+h+':'+m+':'+s+ ' ' +dn+ '</span>';
		if(document.getElementById(id)) {
        document.getElementById(id).innerHTML = result;
		document.getElementById("mobile_date").innerHTML = ''+d+' <span>'+months[month]+'</span> '+year;
		}
        setTimeout('date_time("'+id+'");','1000');
        return true;
	}