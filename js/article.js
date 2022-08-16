// JavaScript Document
$(document).ready(function()
{

var option = $('option:selected', '#main_section_id').attr('url_structure');

if(option  != undefined)
	$("#mainsection_breadcrumb").html("Bread Crumb : "+option);
else 
	$("#mainsection_breadcrumb").empty();

$("#section_href").click(function(){
	multiple_section_mapping();
});

$("#related_href").click(function(){
	 if($('#example').children('tbody').length == 0) {
			related_datatables();
			fill_external_link_preview();
	 }
});

// Starting Default Disabled the Select box in Article 
// IE & DM Differents

$("#home_physical_name").on('blur input',function(e) {
	var value = $("#home_physical_name").val();
	result = value.replace(/[^a-zA-Z0-9_-]/g,'');
		
	$("#home_physical_name").val(result);	
});
$("#section_physical_name").on('input',function(e) {
	var value = $("#section_physical_name").val();
	result = value.replace(/[^a-zA-Z0-9_-]/g,'');
		
	$("#section_physical_name").val(result);
});
$("#article_physical_name").on('input',function(e) {
	var value = $("#article_physical_name").val();
	result = value.replace(/[^a-zA-Z0-9_-]/g,'');
		
	$("#article_physical_name").val(result);
});

var main_section_text = $.trim($( "#main_section_id option:selected" ).text());

	/*	if(main_section_text == 'Nation') {
			$('#country_id option:contains("India")').prop('selected', true);
		} else {
			$('#country_id option:contains("-Select-")').prop('selected', true);
		} */
		
			
		var main_section_data = $('option:selected', '#main_section_id').attr('sectoin_data');
		var main_section_author = $('option:selected', '#main_section_id').attr('author_name');
		
		var option = $('option:selected', '#main_section_id').attr('url_structure');

		if(option  != undefined)
			$("#mainsection_breadcrumb").html("Bread Crumb : "+option);
		else 
			$("#mainsection_breadcrumb").empty();	
		
		if(folder_name == 'niecpan') {
		
		var if_condition = (main_section_text  == 'Editorials' || main_section_text  == 'Columns' ||  main_section_text  == 'Magazine'  ||  main_section_text  == 'The Sunday Standard'  || main_section_data == 'Columns' || main_section_data == 'Magazine' || main_section_data == 'The Sunday Standard' || main_section_text == 'Voices' || main_section_text == 'Opinions' || main_section_text == 'Spirituality'); 
	
		
		if(if_condition) {
			
			if(main_section_text  == 'Editorials') {
			$('#country_id').prop('disabled', true);
			$('#country_id option:contains("-Select-")').prop('selected', true);
			}
			
			$('#agency_id').prop('disabled', true);
			$('#agency_id option:contains("-Select-")').prop('selected', true);
			
	
			
			if(main_section_text == 'Columns' || main_section_text  == 'Magazine' || main_section_text  == 'The Sunday Standard' || main_section_text == 'Voices' || main_section_text == 'Opinions' || main_section_text == 'Spirituality') {
				
				//$('#txtByLine').prop('disabled', false);
				//$('#txtByLine').val('');
				//$('#byline_id').val('');
				$("#byline_label").html('Author');
				
				if(main_section_text == 'Voices')
				$("#byline_label").html('Byline');
			
				$('#country_id').prop('disabled', false);
				$('#country_id option:contains("-Select-")').prop('selected', true);
				
				$('#txtState').prop('disabled', false);
				$('#txtState').val('');
				$('#state_id').val('');
				
				$('#txtCity').prop('disabled', false);
				$('#txtCity').val('');
				$('#city_id').val('');
			
			} else {
				console.log("text3");
				$('#txtByLine').prop('disabled', true);
				$('#txtByLine').val('');
				$('#byline_id').val('');
				$("#byline_label").html('Byliner');	
			}
			if(main_section_text  == 'Editorials') {
				$('#txtState').prop('disabled', true);
				$('#txtState').val('');
				$('#state_id').val('');
				
				$('#txtCity').prop('disabled', true);
				$('#txtCity').val('');
				$('#city_id').val('');
			}
		} else {
			
			$('#country_id').prop('disabled', false);
			$('#agency_id').prop('disabled', false);
			
			/*$('#txtByLine').prop('disabled', false);
			$('#txtByLine').val('');
			$('#byline_id').val(''); */
			$("#byline_label").html('Byline');
			
			$('#txtState').prop('disabled', false);
			$('#txtCity').prop('disabled', false);
		}
		
		} else {
			
			
				var if_condition = ( main_section_text == 'விவசாயம்' || main_section_text == 'தலையங்கம்' ||  main_section_text == 'வேலைவாய்ப்பு' ||   main_section_text == 'ஜங்ஷன்' ||   main_section_text == 'ஆன்மிகம்' ||   main_section_text == 'ஆன்மிகம்' || main_section_text == 'மருத்துவம்' || main_section_text == 'வார இதழ்கள்' || main_section_text == 'வார இதழ்கள்'  || main_section_text == 'ஜோதிடம்'  || main_section_text == 'புத்தக மதிப்புரை' || main_section_data == 'விவசாயம்' || main_section_data == 'தலையங்கம்' ||  main_section_data == 'வேலைவாய்ப்பு' ||   main_section_data == 'ஜங்ஷன்' ||   main_section_data == 'ஆன்மிகம்' ||   main_section_data == 'ஆன்மிகம்' || main_section_data == 'மருத்துவம்' || main_section_data == 'வார இதழ்கள்' || main_section_data == 'வார இதழ்கள்'  || main_section_data == 'ஜோதிடம்'  || main_section_data == 'புத்தக மதிப்புரை' || (( main_section_text == 'திரை விமரிசனம்' || main_section_text == 'ஸ்பெஷல்' ) && main_section_data == 'சினிமா' ) || ((main_section_text == 'தொடர்கள்' || main_section_text == 'கட்டுரைகள்' ) && main_section_data == 'ஆன்மிகம்' )  ||  ((main_section_text == 'தொடர்கள்' || main_section_text == 'ஸ்பெஷல்' || main_section_text == 'அழகே அழகு' || main_section_text == 'ரசிக்க… ருசிக்க…' || main_section_text == 'ஃபேஷன்' || main_section_text == 'தொழில்நுட்பம்' || main_section_text == 'கலைகள்' || main_section_text == 'இனிய இல்லம்' || main_section_text == 'பயணம்' ) && main_section_data == 'லைஃப்ஸ்டைல்') || main_section_data == 'தொல்லியல்மணி' || ((main_section_text == 'சிறப்புக் கட்டுரைகள்') && main_section_data == 'கட்டுரைகள்') || ((main_section_text == 'நாள்தோறும் நம்மாழ்வார்' || main_section_text == 'தினந்தோறும் திருப்புகழ்' || main_section_text == 'தினம் ஒரு தேவாரம்' || main_section_text == 'சிறுகதைமணி' || main_section_text == 'பரிகாரத் தலங்கள்' || main_section_text == 'கார்ட்டூன்'  || main_section_text == 'இனிப்புகள்'  || main_section_text == 'சைவம்' || main_section_text == 'அசைவம்') && main_section_data == 'ஸ்பெஷல்ஸ்')   )
		
		if(if_condition) {
			$('#agency_id').prop('disabled', true);
			$('#agency_id option:contains("-Select-")').prop('selected', true);
			$('#agency_container').hide();
		}
		
		if(main_section_author != '' && main_section_author != undefined) {
				$breadcrumb = $("#mainsection_breadcrumb").text();
				$("#mainsection_breadcrumb").text($breadcrumb +" | Author : "+main_section_author);
			}
			
		}
// IE & DM Differents
// Ending Default Disabled the Select box in Article 

		
		/*$("#imagelibrary").change(function() {
			
			$("#LoadingSelectImageLocal").show();
			
				$("#ImageForm").ajaxForm({
				async: true, 
				dataType: "json",
				beforeSubmit:function(){
					
				},
				success:function(data){
					
				 $.each(data,function(key,value){
						
					alert(data);
					});
					
					
				},
				error:function(){
					
				} }).submit(); 	
			
	
		}); */
$("#count1").blur(function() {
	$MetaTitle = $.trim($("#count1").val());
	
	$Title = $("<div/>").html(CKEDITOR.instances.article_head_line_id.getData()).text();	
	
	if($MetaTitle != '' || $Title != '') {
		
	var postdata = "metatitle="+$MetaTitle+" "+$.trim($Title);
	$("#suggestion_div").empty();
		$("#suggestion_div").show();
			$.ajax({
				url: base_url+folder_name+"/common/get_tags_by_meta_title", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "JSON",
				success: function(data)   // A function to be called if request succeeds
				{
					if(data != '') {
						$("#suggestion_div").html('<p>  Suggestions : </p>');
				$.each(data,function(i,item) {
							$("#suggestion_div").append('<a href="javascript:void(0);" id="suggestion_tags" data-id="'+item.tag_id+'" data-value="'+item.tag_name+'">  '+item.tag_name+'  </a>');
						});
					}
				}
			});
	} else {
		$("#suggestion_div").empty();
		$("#suggestion_div").hide();
	}
});

 $(document.body).on('click', '#suggestion_tags', function(event) {
		var TagDetails = $(this).data();
		 
		 var bool = true;
		 $(".tagedit-listelement").children("input:hidden").each(function(){
			 if($(this).val() == TagDetails.value) {
				bool = false;
			 }
		 });
		 
		 if(bool == true)  {
			  var content = '<li class="tagedit-listelement tagedit-listelement-old"><span dir="ltr">'+TagDetails.value+'</span><input type="hidden" value="'+TagDetails.value+'" id="tags_id" name="txtTags['+TagDetails.id+'-a]"><a title="Remove from list." class="tagedit-close">x</a></li>';
		$(".tagedit-listelement-new").last().before(content);
		
		 }
		 
	 });
	
	
	
$("#show_list").change(function(click) {
  
    if(this.checked) {
     var Content ='<ul>';
   $("#mapping_2").empty();
     $("input:checkbox[name='cbSectionMapping[]']:checked").each(function() {
		 if($(this).attr('main_section') != '' && $(this).attr('sub_main_section') != '')
			 Content +='<li>'+$(this).attr('main_section')+' -> '+$(this).attr('sub_main_section')+' -> '+$(this).attr('rel')+'</li>';
		 else if($(this).attr('main_section') != '')
   			 Content +='<li>'+$(this).attr('main_section')+' -> '+$(this).attr('rel')+'</li>';
		else 
			Content +='<li>'+$(this).attr('rel')+'</li>';
   });
    Content +='</ul>';
     
    $("#mapping_1").hide();
    $("#mapping_2").html(Content);
    $("#mapping_2").show();
    } else {
    $("#mapping_1").show();
    $("#mapping_2").hide();
    }

 });
 $(document.body).on('click', '#MainSectionMapping', function(event) {
 $("#SubSection"+$(this).attr('val')).toggle();
 });
	
$('#count2').keyup(function() {
	var Total_Length = $("#count2").attr('maxlength');
    MetaTitle_Count = $(this).val().length;
	
	if(MetaTitle_Count > Total_Length) 
	Remain_Count  = 0;
	else 
	Remain_Count  = Total_Length - MetaTitle_Count;
	
	$('#charNum2').text(Remain_Count);
});

$("#checkout_id_icon").click(function() {
	$("#checkout_id").trigger("focus");
});
         
		 	     
$("#checkin_id_icon").click(function() {
	$("#checkin_id").trigger("focus");
});
	
	
$("#publish_starticon").click(function() {
	$("#publish_start_datetimepicker").trigger("focus");
});
         
		 	     
$("#publish_endicon").click(function() {
	$("#publish_end_datetimepicker").trigger("focus");
});
	
		/*		
$("#checkin_id").datetimepicker({
format: 'DD-MM-YYYY',
});


$("#checkout_id").datetimepicker({
format: 'DD-MM-YYYY',
});

*/
/*
$("#publish_start_datetimepicker").datetimepicker({
format: 'DD-MM-YYYY',
})
.on('dp.change', function (selected) {
		//console.log(selected.date);
		
		var date = new Date(selected.date);
	    var d = date.getDate();
    	var m = date.getMonth();
	    var y = date.getFullYear();
	    var edate= new Date(y, m, d+1);
		console.log(edate);
		
    $("#publish_end_datetimepicker").data("DateTimePicker").minDate(edate);
	 $("#publish_end_datetimepicker").data("DateTimePicker").defaultDate(edate);
});

$("#publish_end_datetimepicker").datetimepicker({
format: 'DD-MM-YYYY',
});

*/
	
	$("#search_based_check").click(function() {
		
				if(! this.checked) 
				{
					$("#checkin_checkout_div").hide();
				} else {
					$("#checkin_checkout_div").show();
					$("#checkin_id").val('');
					$("#checkout_id").val('');
				}
	});
			
	$("#publishtop_id").click(function() {			
		CKEDITOR.instances.summary.updateElement();
		CKEDITOR.instances.body_text.updateElement();
		CKEDITOR.instances.article_head_line_id.updateElement();
		$("#status_id").val('P');
				$("#publish_close").val('');
		
		
		$('[name="txtMetaTitle"], [name="txtBodyText"]').each(function () {
            $(this).rules('add', {
                required: true
			});
         });
	
		 	
		CheckSectionName();

		var bool = true
		
		if(!$("#content_form").valid()) {
			bool = false;
		}
		
		ValidForm(bool);
		
	});
	
	
	$("#publishnotclosetop_id").click(function() {			
		CKEDITOR.instances.summary.updateElement();
		CKEDITOR.instances.body_text.updateElement();
		CKEDITOR.instances.article_head_line_id.updateElement();
		$("#status_id").val('P');
		$("#publish_close").val('1');
		
		$('[name="txtMetaTitle"], [name="txtBodyText"]').each(function () {
            $(this).rules('add', {
                required: true
			});
         });
	
		 	
		CheckSectionName();

		var bool = true
		
		if(!$("#content_form").valid()) {
			bool = false;
		}
		
		ValidForm(bool);
		
	});
	
	
	$("#send_drafttop_id").click(function() {
		
		CKEDITOR.instances.summary.updateElement();
		CKEDITOR.instances.body_text.updateElement();
		CKEDITOR.instances.article_head_line_id.updateElement();
		$("#status_id").val('D');
			$("#publish_close").val('');
		
		$('[name="txtMetaTitle"], [name="txtBodyText"]').each(function () {
            $(this).rules('add', {
                required: true
			});
         });
	
		 
		 CheckSectionName();
		
		var bool = true;
		
		if(!$("#content_form").valid()) {
			bool = false;
		}
		
		ValidForm(bool);
		
	});
	
	
	$("#unpublishtop_id").click(function() {
		
		CKEDITOR.instances.summary.updateElement();
		CKEDITOR.instances.body_text.updateElement();
		CKEDITOR.instances.article_head_line_id.updateElement();
		$("#status_id").val('U');
			$("#publish_close").val('');
		
		$('[name="txtMetaTitle"], [name="txtBodyText"]').each(function () {
            $(this).rules('add', {
                required: true
			});
         });
		 
		 CheckSectionName();
			
		var bool = true
		
		if(!$("#content_form").valid()) {
			bool = false;
		}
		
		ValidForm(bool);
		
	});


	$("#imagelibrary").change(function() {
			
		var ext = $('#imagelibrary').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			console.log('Invalid Extension!');
		} else {
			$("#LoadingSelectImageLocal").show();
			var formImage = new FormData();
			formImage.append('imagelibrary',document.getElementById("imagelibrary").files[0]);
			formImage.append('popuptype',$("#current_image_popup").val());
			
			setTimeout(function(){
			uploadFormData(formImage);
			},1000);
		}
	});
	

function createFormData(image) {

	var formImage = new FormData();
	$("#LoadingSelectImageLocal").show();
	formImage.append('imagelibrary', image[0]);
	formImage.append('popuptype',$("#current_image_popup").val());
	uploadFormData(formImage);
}

function uploadFormData(formData) {

		$.ajax({
			url: base_url+folder_name+"/article_image/image_upload",
			type: "POST",
			data: formData,
			contentType:false,
			cache: false,
			processData: false,
			dataType: "json",
			success: function(data){
				
				if(typeof data.message !== "undefined") {
					alert(data.message);
					$("#LoadingSelectImageLocal").hide();
					return;
				}
		
				if($("#current_image_popup").val() == 'home') {
				$('#home_image_gallery_id').val(data.image_id);
				$('#home_image_gallery_id').attr('rel',data.imagecontent_id);
				$("#home_image_set").html('Change Image');
				$("#home_image_set").removeClass('BorderRadius3');
				$('#home_image_src').attr('src',data.image);
				$('#home_image_container').css("visibility", "visible");
				$("#home_image_set").next().show();
				$("#home_image_set").next().next().show();
				$("#home_uploaded_image").html('Image Set');

					
			   // $("#home_image_caption").val(data.caption);
				//$("#home_image_alt").val(data.alt_tag);
				$("#home_image_caption").val('');
				$("#home_image_alt").val('');
				
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
				
				$("#home_physical_name").val(physical_name);
				
				$("#home_physical_name").attr('physical_extension',data.physical_extension);
				} else if($("#current_image_popup").val() == 'article')  {
				$('#article_image_gallery_id').val(data.image_id);
				$('#article_image_gallery_id').attr('rel',data.imagecontent_id);
				$('#article_image_src').attr('src',data.image);
				$('#article_image_container').css("visibility", "visible");
				$("#article_image_set").next().show();
				$("#article_image_set").next().next().show();
				$("#article_uploaded_image").html('Image Set');
				$("#article_image_set").html('Change Image');
				$("#article_image_set").removeClass('BorderRadius3');
				
				
			    //$("#article_image_caption").val(data.caption);
				//$("#article_image_alt").val(data.alt_tag);
				$("#article_image_caption").val('');
				$("#article_image_alt").val('');
				
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
				
				$("#article_physical_name").val(physical_name);
				
				$("#article_physical_name").attr('physical_extension',data.physical_extension);
				} else  if($("#current_image_popup").val() == 'section') {
				$('#section_image_gallery_id').val(data.image_id);
				$('#section_image_gallery_id').attr('rel',data.imagecontent_id);
				$('#section_image_src').attr('src',data.image);
				$('#section_image_container').css("visibility", "visible");
				$("#section_image_set").next().show();
				$("#section_image_set").next().next().show();
				$("#section_uploaded_image").html('Image Set');
				$("#section_image_set").html('Change Image');
				$("#section_image_set").removeClass('BorderRadius3');
				
				
				//$("#section_image_caption").val(data.caption);
				//$("#section_image_alt").val(data.alt_tag);
				
				$("#section_image_caption").val('');				
			    $("#section_image_alt").val('');
				
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
				
				$("#section_physical_name").val(physical_name);
				$("#section_physical_name").attr('physical_extension',data.physical_extension);
				} else {
					CKEDITOR.instances.body_text.insertHtml('<img src="'+data.image+'" />');
				}
				CheckImageContainer();
				
				$("#LoadingSelectImageLocal").hide();
				
				var inst = $.remodal.lookup[$('[data-remodal-id=modal1]').data('remodal')];
				if(!inst) {
					$('[data-remodal-id=modal1]').remodal().close();
				 } else{
                      inst.close();
                  }
				$("#imagelibrary").val('');
				
			}
		});
	
}
	
$("#drop-area").on('dragenter', function (e){
	e.preventDefault();
	$(this).css('background', '#BBD5B8');
	});

	$("#drop-area").on('dragover', function (e){
	e.preventDefault();
	});

	$("#drop-area").on('drop', function (e){
		$("#LoadingSelectImageLocal").show();
		$(this).css('background', '#D8F9D3');
		e.preventDefault();
		var image = e.originalEvent.dataTransfer.files;
			setTimeout(function(){
				createFormData(image);
			},1000);
	});
	
	$(document.body).on('click', '#image_lists_images_id', function(event) {
		
		var ImageDetails = $(this).data();
		
			$("#textarea_alt").text(ImageDetails.image_alt);
			$("#textarea_caption").text(ImageDetails.image_caption);
			$("#image_name").html(ImageDetails.image_caption);
			$("#height_width").html(ImageDetails.image_height+" X "+ImageDetails.image_width);
			$("#image_size").html(ImageDetails.image_size+" Kb");
			$("#image_date").html(ImageDetails.image_date);
			$("#image_path").attr('src',ImageDetails.image_source);
			
			$("#browse_image_id").val(ImageDetails.content_id);
			console.log(ImageDetails.content_id);
			
			$("#browse_image_id").data("image_source",ImageDetails.image_source);
			$("#browse_image_id").data("content_id",ImageDetails.content_id);
			$("#browse_image_id").data("image_alt",ImageDetails.image_alt);
			$("#browse_image_id").data("image_caption",ImageDetails.image_caption);
			$("#browse_image_id").data("image_size",ImageDetails.image_size);
			$("#browse_image_id").data("image_date",ImageDetails.image_date);
			$("#browse_image_id").data("image_width",ImageDetails.image_width);
			$("#browse_image_id").data("image_height",ImageDetails.image_height);
			$("#browse_image_id").data("image_path",ImageDetails.image_path);
			console.log($("#browse_image_id").data());
			$("#image_lists_id img").removeClass('active');
	 		$(this).addClass('active')
			
	});
	
	
		
	$(document.body).on('click',"#browse_image_insert",function() {
		
		$("#LoadingSelectImageLibrary").show();
		
		var popuptype	 = $("#current_image_popup").val();
		
		if(popuptype == 'bodytext') {
			
			if($("#browse_image_id").val() != '' && $("#browse_image_id").val() != 0 ) {
			
			ImageData = "content_id="+$("#browse_image_id").val()+"&type=1";
			
					$.ajax({
						url		: base_url+folder_name+"/common/get_image_by_content_ajax",
						type	: "POST",
						data	: ImageData,
						dataType: "HTML",
						async	: false, 	
						success	: function(data) {
							
							CKEDITOR.instances.body_text.insertHtml('<img src="'+data+'" />');
							$("#LoadingSelectImageLibrary").hide(); 
						}
					});
			
				
			}
			
		} else {
		
			if($("#browse_image_id").val() != '' && $("#browse_image_id").val() != 0 ) {
			
				if($("#browse_image_id").data('image_source')) {
			
					var ImageDetails = $("#browse_image_id").data();
					
					
					ImageData = "alt="+ImageDetails.image_alt+"&caption="+ImageDetails.image_caption+"&date="+ImageDetails.image_date+"&height="+ImageDetails.image_height+"&width="+ImageDetails.image_width+"&size="+ImageDetails.image_size+"&path="+ImageDetails.image_path+"&content_id="+ImageDetails.content_id+"&popuptype="+popuptype;
			 
					$.ajax({
					url		: base_url+folder_name+"/article_image/Insert_temp_from_image_library",
					type	: "POST",
					data	: ImageData,
					dataType: "json",
					async	: false, 	
					success	: function(data) {
						
						console.log(data);
						
						if(popuptype == 'home') {
							$('#home_image_gallery_id').val(data.image_id);
							$('#home_image_gallery_id').attr('rel',data.imagecontent_id);
							$("#home_image_src").attr('src',data.source);
							$('#home_image_container').css("visibility", "visible");
							$("#home_image_set").next().show();
							$("#home_image_set").next().next().show();
							$("#home_image_set").html('Change Image');
							$("#home_uploaded_image").html('Image Set');
							$("#home_image_set").removeClass('BorderRadius3');
							
								$("#home_image_caption").val(data.caption);
								
								var physical_name = data.physical_name;
								physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
								
								$("#home_physical_name").val(physical_name);
								
								$("#home_physical_name").attr('physical_extension',data.physical_extension);
								$("#home_physical_name").attr('readonly',true);
								
				$("#home_image_alt").val(data.alt);
						} else if(popuptype == 'article')  {
							$('#article_image_gallery_id').val(data.image_id);
							$('#article_image_gallery_id').attr('rel',data.imagecontent_id);
							$("#article_uploaded_image").html('Image Set');
							$("#article_image_src").attr('src',$("#image_path").attr('src'));
							$('#article_image_container').css("visibility", "visible");
							$("#article_image_set").next().show();
							$("#article_image_set").next().next().show();
							$("#article_image_set").html('Change Image');
							$("#article_image_set").removeClass('BorderRadius3');
							
								$("#article_image_caption").val(data.caption);
								
								var physical_name = data.physical_name;
								physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
								
								$("#article_physical_name").val(physical_name);
								
								$("#article_physical_name").attr('physical_extension',data.physical_extension);
								$("#article_physical_name").attr('readonly',true);
								
				$("#article_image_alt").val(data.alt);
						} else { 
							$('#section_image_gallery_id').val(data.image_id);
							$('#section_image_gallery_id').attr('rel',data.imagecontent_id);
							$("#section_uploaded_image").html('Image Set');
							$("#section_image_src").attr('src',$("#image_path").attr('src'));
							$('#section_image_container').css("visibility", "visible");
							$("#section_image_set").next().show();
							$("#section_image_set").next().next().show();
							$("#section_image_set").html('Change Image');
							$("#section_image_set").removeClass('BorderRadius3');
							
								$("#section_image_caption").val(data.caption);
								
								var physical_name = data.physical_name;
								physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
								
								$("#section_physical_name").val(physical_name);
								$("#section_physical_name").attr('physical_extension',data.physical_extension);
				$("#section_image_alt").val(data.alt);
				
					$("#section_physical_name").attr('readonly',true);
				
						} 
						
						CheckImageContainer();
					
						$("#LoadingSelectImageLibrary").hide(); 
						
						}
						
						
					}); 
					
				}
			
			}
		
		}
		
	});
	
	$(document.body).on('click', '#edit_article_image' ,function(){
		var image_type = $(this).attr('rel');
		var ImageIndex = '';
		
	
		if(image_type == 'home') {
			ImageIndex = $("#home_image_gallery_id").val();
		} else if(image_type == 'section') {	
			ImageIndex = $("#section_image_gallery_id").val();
		} else {
			ImageIndex = $("#article_image_gallery_id").val();
		}
	
		if(ImageIndex != '') {
		window.open(base_url+folder_name+"/article_image_processing/"+encodeURIComponent(base64_encode(ImageIndex)), '_blank');
		
	
			/*	$("#publish_history_popup").html('<img style="width:40px; height:40px;" src="'+base_url+'images/admin/loadingroundimage.gif">')
	
			var inst = $.remodal.lookup[$('[data-remodal-id=publish_history_popup]').data('remodal')];
					if(!inst) {
						$('[data-remodal-id=publish_history_popup]').remodal().open();
					 } else{
						  inst.open();
	 				}
				 
			 
				var postdata = "content_id="+$("#content_id").val();
			$.ajax({
			url: base_url+"admin/article_image_processing/"+encodeURIComponent(base64_encode(ImageIndex)), // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			dataType: "HTML",
			async: false, 
			success: function(data)   // A function to be called if request succeeds
			{
			$("#publish_history_popup").html(data);
		
			}
		}); */
				
		}
		else  {
		alert("Invalid Action for Image Processing");
		}
		
	});
	
	$(document.body).on('click', '#delete_article_image' ,function(){
		var image_type = $(this).attr('rel');
		var ImageIndex = '';
		
		if(image_type == 'home') {
			ImageIndex = $("#home_image_gallery_id").val();
		} else if(image_type == 'section') {	
			ImageIndex = $("#section_image_gallery_id").val();
		} else {
			ImageIndex = $("#article_image_gallery_id").val();
		}
		
		if(ImageIndex != '') {
		
			var ImageData = 'image_id='+ImageIndex;

			var confirm_status = confirm('Are you sure to delete the image');

			if(confirm_status ==  true) {
		
			$.ajax({
				url: base_url+folder_name+"/article_image/delete_temp_image",
				type: "POST",
				data: ImageData,
				dataType: "json",
				async: false, 
				success: function(data) {
			
					if(image_type == 'home') {
						$('#home_uploaded_image').html('Image not set');
						$("#home_image_set").html('Set Image');
						$("#home_image_set").addClass('BorderRadius3');
						$("#home_image_gallery_id").val('');
						$("#home_image_gallery_id").attr('rel','');
						$("#home_image_src").attr('src','');
						$('#home_image_container').css("visibility", "hidden");
						$("#home_image_set").next().hide();
						$("#home_image_set").next().next().hide();
						$("#home_physical_name").val('');
						$("#home_physical_name").attr('physical_extension','');
					} else if(image_type == 'section') {
						$('#section_uploaded_image').html('Image not set');
						$("#section_image_set").addClass('BorderRadius3');
						$("#section_image_set").html('Set Image');
						$("#section_image_gallery_id").val('');
						$("#section_image_gallery_id").attr('rel','');
						$("#section_image_src").attr('src','');
						$('#section_image_container').css("visibility", "hidden");
						$("#section_image_set").next().hide();
						$("#section_image_set").next().next().hide();
						$("#section_physical_name").val('');
						$("#section_physical_name").attr('physical_extension','');
					} else if(image_type == 'article') {
						$('#article_uploaded_image').html('Image not set');
						$("#article_image_set").addClass('BorderRadius3');
						$("#article_image_set").html('Set Image');
						$("#article_image_gallery_id").val('');
						$("#article_image_gallery_id").attr('rel','');
						$("#article_image_src").attr('src','');
						$('#article_image_container').css("visibility", "hidden");
						$("#article_image_set").next().hide();
						$("#article_image_set").next().next().hide();
						$("#article_physical_name").val('');
						$("#article_physical_name").attr('physical_extension','');
					}
					CheckImageContainer();
				}
			});

			}
			
		} else {
				alert("Invalid Actions");
		}	

	});
	
		
	// IE & DM Differents
	

	$("#main_section_id").change(function(){
		
		var main_section_data = $('option:selected', '#main_section_id').attr('sectoin_data');
		var main_section_author = $('option:selected', '#main_section_id').attr('author_name');
		
		var option = $('option:selected', '#main_section_id').attr('url_structure');

		if(option  != undefined)
			$("#mainsection_breadcrumb").html("Bread Crumb : "+option);
		else 
			$("#mainsection_breadcrumb").empty();	
		
		$("#article_section").val($("#main_section_id").val());
		related_datatables();
		
		if($( "#main_section_id option:selected" ).text() == 'Nation') {
			$('#country_id option:contains("India")').prop('selected', true);
			
			$('#txtState').val('');
			$('#state_id').val('');
			
			$('#txtCity').val('');
			$('#city_id').val('');
			
		}
		
		var main_section_text = $.trim($( "#main_section_id option:selected" ).text());
		
			if(folder_name == 'niecpan') {
		
		var if_condition = (main_section_text  == 'Editorials' || main_section_text  == 'Columns' ||  main_section_text  == 'Magazine'  ||  main_section_text  == 'The Sunday Standard'  || main_section_data == 'Columns' || main_section_data == 'Magazine' || main_section_data == 'The Sunday Standard' || main_section_text == 'Voices' || main_section_text == 'Opinions' || main_section_text == 'Spirituality'); 
	
		
		if(if_condition) {
			
			if(main_section_text  == 'Editorials') {
			$('#country_id').prop('disabled', true);
			$('#country_id option:contains("-Select-")').prop('selected', true);
			}
			
			$('#agency_id').prop('disabled', true);
			$('#agency_id option:contains("-Select-")').prop('selected', true);
			
			if(main_section_text == 'Columns' || main_section_text  == 'Magazine' || main_section_text  == 'The Sunday Standard' || main_section_text == 'Voices' || main_section_text == 'Opinions' || main_section_text == 'Spirituality') {
				
				$('#txtByLine').prop('disabled', false);
				$('#txtByLine').val('');
				$('#byline_id').val('');
				$("#byline_label").html('Author');
				
				if(main_section_text == 'Voices')
				$("#byline_label").html('Byline');
			
				$('#country_id').prop('disabled', false);
				$('#country_id option:contains("-Select-")').prop('selected', true);
				
				$('#txtState').prop('disabled', false);
				$('#txtState').val('');
				$('#state_id').val('');
				
				$('#txtCity').prop('disabled', false);
				$('#txtCity').val('');
				$('#city_id').val('');
			
			} else {
				console.log("text3");
				$('#txtByLine').prop('disabled', true);
				$('#txtByLine').val('');
				$('#byline_id').val('');
				$("#byline_label").html('Byliner');	
			}
			if(main_section_text  == 'Editorials') {
				$('#txtState').prop('disabled', true);
				$('#txtState').val('');
				$('#state_id').val('');
				
				$('#txtCity').prop('disabled', true);
				$('#txtCity').val('');
				$('#city_id').val('');
			}
		} else {
			
			$('#country_id').prop('disabled', false);
			$('#agency_id').prop('disabled', false);
			
			$('#txtByLine').prop('disabled', false);
			$('#txtByLine').val('');
			$('#byline_id').val('');
			$("#byline_label").html('Byline');
			
			$('#txtState').prop('disabled', false);
			$('#txtCity').prop('disabled', false);
		}
		
	} else {

		
		var if_condition = ( main_section_text == 'விவசாயம்' || main_section_text == 'தலையங்கம்' ||  main_section_text == 'வேலைவாய்ப்பு' ||   main_section_text == 'ஜங்ஷன்' ||   main_section_text == 'ஆன்மிகம்' ||   main_section_text == 'ஆன்மிகம்' || main_section_text == 'மருத்துவம்' || main_section_text == 'வார இதழ்கள்' || main_section_text == 'வார இதழ்கள்'  || main_section_text == 'ஜோதிடம்'  || main_section_text == 'புத்தக மதிப்புரை' || main_section_data == 'விவசாயம்' || main_section_data == 'தலையங்கம்' ||  main_section_data == 'வேலைவாய்ப்பு' ||   main_section_data == 'ஜங்ஷன்' ||   main_section_data == 'ஆன்மிகம்' ||   main_section_data == 'ஆன்மிகம்' || main_section_data == 'மருத்துவம்' || main_section_data == 'வார இதழ்கள்' || main_section_data == 'வார இதழ்கள்'  || main_section_data == 'ஜோதிடம்'  || main_section_data == 'புத்தக மதிப்புரை' || (( main_section_text == 'திரை விமரிசனம்' || main_section_text == 'ஸ்பெஷல்' ) && main_section_data == 'சினிமா' ) || ((main_section_text == 'தொடர்கள்' || main_section_text == 'கட்டுரைகள்' ) && main_section_data == 'ஆன்மிகம்' )  ||  ((main_section_text == 'தொடர்கள்' || main_section_text == 'ஸ்பெஷல்' || main_section_text == 'அழகே அழகு' || main_section_text == 'ரசிக்க… ருசிக்க…' || main_section_text == 'ஃபேஷன்' || main_section_text == 'தொழில்நுட்பம்' || main_section_text == 'கலைகள்' || main_section_text == 'இனிய இல்லம்' || main_section_text == 'பயணம்' ) && main_section_data == 'லைஃப்ஸ்டைல்') || main_section_data == 'தொல்லியல்மணி' || ((main_section_text == 'சிறப்புக் கட்டுரைகள்') && main_section_data == 'கட்டுரைகள்') || ((main_section_text == 'நாள்தோறும் நம்மாழ்வார்' || main_section_text == 'தினந்தோறும் திருப்புகழ்' || main_section_text == 'தினம் ஒரு தேவாரம்' || main_section_text == 'சிறுகதைமணி' || main_section_text == 'பரிகாரத் தலங்கள்' || main_section_text == 'கார்ட்டூன்'  || main_section_text == 'இனிப்புகள்'  || main_section_text == 'சைவம்' || main_section_text == 'அசைவம்') && main_section_data == 'ஸ்பெஷல்ஸ்')   )
		
		if(if_condition)		 {
			$('#agency_id').prop('disabled', true);
			$('#agency_id option:contains("-Select-")').prop('selected', true);
			$('#agency_container').hide();
			
		} else {
			$('#agency_id').prop('disabled', false);
			$('#agency_container').show();
		}
			if(main_section_author != ''  && main_section_author != undefined) {
				$breadcrumb = $("#mainsection_breadcrumb").text();
				$("#mainsection_breadcrumb").text($breadcrumb +" | Author : "+main_section_author);
			}
	
	}
		
		 $("#main_section_id").attr("rel",$("#main_section_id").find('option:selected').attr('rel'));
		  $("#main_section_id").attr("section_data",$("#main_section_id").find('option:selected').attr('section_data'));
		  
		   $("#main_section_id").attr("rel");
	
	 $("input:checkbox[name='cbSectionMapping[]']").each(function() {
		 $(this).css("visibility", "visible");
	 });
	
	$("#section_mapping"+$("#main_section_id").val()).prop('checked', false);
	
	$("#section_mapping"+$("#main_section_id").val()).css("visibility", "hidden");
		
	}); 
	
	// IE & DM Differents
		
	$("#article_search_id").click(related_datatables);
	
	$("#clear_search_article").click(function() {
		
	$("#article_section").val('');
	$("#search_text").val('');
	$("#article_Type").val(1);
	GenerateSectionDropDown(1);
	$("#checkin_id").val('');
	$("#checkout_id").val('');
	
	 related_datatables();
	});
	
	
	function related_datatables() {

	var Section 		= $("#article_section").val();
	var Search_text 	= $("#search_text").val();
	var SearchBy		= '';
	var check_in		= $("#checkin_id").val();
	var check_out   	= $("#checkout_id").val();
	var article_Type 	= $("#article_Type").val();
	var Status			= 'P';
	
    $('#example').dataTable( {
        "processing": true,
		 "autoWidth": false,
        "bServerSide": true,
		"bDestroy": true,
		"searching": false,
		"iDisplayLength": 50,
		 "order": [[ 2 , "desc" ]],
		  "columnDefs": [
		{ "width": "20%", "targets": 1 },
		 { "orderable": false, "targets": 3 }
		 ],
		"fnDrawCallback":function(oSettings){  
		
			$("html, body").animate({ scrollTop: 0 }, "slow");
		
		   if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();
			 $("#example_length").hide();
		   } else {
			 $("#example_paginate").show();
			 $("#example_length").show();
		   }
		   
		   if($(this).find('tbody tr').text()== "No matching records found") {
			 $(oSettings.nTHead).hide(); 
			 $('.dataTables_info').hide();
     		} else {
      		$(oSettings.nTHead).show(); 
     		}
			
			 $('[data-toggle="tooltip"]').tooltip();
		   
		},
		
		"ajax": {
            "url": base_url+folder_name+"/article/search_internal_article",
			"type" : "POST",
			"data" : {
		 "Search_by" : SearchBy, "Section" : Section, "Search_text" : Search_text, "check_in" : check_in, "check_out" : check_out, "Status" : Status, "article_Type" : article_Type, "content_id" : get_content_id
		 
		 }
		 }
    } );
		
	}
	
	$("#add_to_list_id").click(function() {
			var Bool = true;
			
			$("#external_title_error").empty();
			$("#external_url_error").empty();
			
			if($.trim($("#external_title_id").val()) == '') {
				Bool = false;
				$("#external_title_error").html("Title is Required");
			
			}
		
			  var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
				
			if($.trim($("#external_url_id").val()) == '') {
				Bool = false;
				$("#external_url_error").html("URL is Required");
			} else if(regexp.test($("#external_url_id").val()) == false) {
					Bool = false;
				$("#external_url_error").html("Invalid URL");
			}
			
			if(Bool == true){
		
				if(external_array.length < 20) {
				
				$("#priority_error").empty();
				var sub_array = {};
				sub_array["type"] = 'E';
				sub_array["external_title"] = $("#external_title_id").val();
				sub_array['external_short_title'] = shortDescription($("#external_title_id").val());
				sub_array["external_url"] = $("#external_url_id").val();

				external_array.push(sub_array);
				
				$("#external_title_id").val('');
				$("#external_url_id").val('');
				
				fill_external_link_preview();
				
					Flash_message("Inserted the related Content","SessionSuccess");
				
				} else {
					Flash_message("Maximum 20 Related content","SessionError");
				}
				
				
			} else {
				console.log("Failure");
			}
			
		});

	$(document.body).on('click', '#internal_action' ,function(){
		
		if(external_array.length < 20) {
		
					if(CheckContent($(this).attr('value'))) {
				
					var sub_array = {};
					sub_array["type"] = 'I';
					sub_array["content_type"] = $(this).attr('rel');
					sub_array["content_id"] = $(this).attr('value');
					sub_array["short_title"] = $.trim($(this).attr('short_title'));
					sub_array["long_title"] = $.trim($(this).attr('long_title'));
					sub_array["bread_crumb"] = $.trim($(this).attr('bread_crumb'));
					sub_array["url"]		 = $.trim($(this).attr('url'));

					external_array.push(sub_array);
				
					fill_external_link_preview();
				
					
				Flash_message("Inserted the related Content","SessionSuccess");
					
				} else {
					
				Flash_message("Already existing related content","SessionError");
					
				}
		} else {
			Flash_message("Maximum 20 Related content","SessionError");
		}
				
				});
			
		/*$(document.body).on('change', '#priority_setting' ,function(){
				
				var priority_value = $(this).val();
				
				var position = $(this).attr('rel');
				
				if(parseInt(position)  < parseInt(priority_value)) {
					
					external_array[position][3] = parseInt(priority_value)+1;
				} else {
					external_array[position][3] = parseInt(priority_value)-1;
				}
			
				console.log(position);
				console.log(priority_value);
				
				fill_external_link_preview();
				
			}); */
	
	function ChangeDateFormat($DateString) {
		$GetEnd = $DateString.split(' ');
		
		console.log($GetEnd);
		
		$GetDateString = $GetEnd[0];
		$GetTimeString = $GetEnd[1];
		
		$GetDate = $GetDateString.split('-');
		
		var d = $GetDate[0];
		var m = $GetDate[1];
		var y = $GetDate[2];
		
		$GetTime = $GetTimeString.split(':');
		
		var h 	=  $GetTime[0];
		var i 	= $GetTime[1];
		
		var datestring = y+"/"+m+"/"+d+" "+h+":"+i;
		
	   return new Date(datestring);
	}
	
		$.validator.addMethod("greaterThan", 
	function(end, element, params) {

		if($(params).val() != '' && end != '' ) {
			
		end = ChangeDateFormat(end);
		start= ChangeDateFormat($(params).val());
		
				console.log(end);
				console.log(start);
			
			return end > start;
		} else { return true; }
	},'Must be greater than Publish starting date.');
	
		$.validator.addMethod("equalToHome", 
	function(physical_name, element, params) {
	
		var home_bool = true;

		if( $("#home_image_src").attr('src') != '' ) {
		
		if(typeof $('#article_physical_name').val() != 'undefined' && $.trim($('#article_physical_name').val()) != '' && ( $.trim($('#article_image_gallery_id').attr('rel')) == '' || $.trim($('#article_image_gallery_id').attr('rel')) == 'NULL' ) && physical_name == $.trim($('#article_physical_name').val())) {
			home_bool = false;
		}
		
		if( typeof $('#section_physical_name').val() != 'undefined' && $.trim($('#section_physical_name').val()) != '' && ( $.trim($('#section_image_gallery_id').attr('rel')) == '' || $.trim($('#section_image_gallery_id').attr('rel')) == 'NULL' ) && physical_name == $.trim($('#section_physical_name').val())) {
			home_bool = false;
		}

		postdata = "physical_name="+physical_name+'.'+$('#home_physical_name').attr('physical_extension')+"&temp_id="+$("#home_image_gallery_id").val()+"&caption="+$("#home_image_caption").val()+"&alt="+$("#home_image_alt").val();
			$.ajax({
				url: base_url+folder_name+"/common/check_image_name", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					if(data.status == 'false') {
						home_bool = false;
						$("#home_physical_name").attr("readonly",false);
					} 
				}
			});
		}
			
			return home_bool;
	
	},'Home Image name already exists');
	
		$.validator.addMethod("equalToArticle", 
	function(physical_name, element, params) {
		
		var article_bool = true;

		if( $("#article_image_src").attr('src') != '' ) {
		
		if(typeof $('#home_physical_name').val() != 'undefined' && $.trim($('#home_physical_name').val()) != '' && ( $.trim($('#home_image_gallery_id').attr('rel')) == '' || $.trim($('#home_image_gallery_id').attr('rel')) == 'NULL')  && physical_name == $.trim($('#home_physical_name').val())) {
			article_bool = false;
		}
		
		if( typeof $('#section_physical_name').val() != 'undefined' && $.trim($('#section_physical_name').val()) != '' && ($.trim($('#section_image_gallery_id').attr('rel')) == '' || $.trim($('#section_image_gallery_id').attr('rel')) == 'NULL' ) && physical_name == $.trim($('#section_physical_name').val())) {
			article_bool = false;
		}

		postdata = "physical_name="+physical_name+'.'+$('#article_physical_name').attr('physical_extension')+"&temp_id="+$("#article_image_gallery_id").val()+"&caption="+$("#article_image_caption").val()+"&alt="+$("#article_image_alt").val();
			$.ajax({
				url: base_url+folder_name+"/common/check_image_name", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					if(data.status == 'false') {
						article_bool = false;
						$("#article_physical_name").attr("readonly",false);
					} 
				}
			});
			
		}
		console.log("article:"+article_bool);
			return article_bool;
		
	},'Article Image name already exists');
	
	
		$.validator.addMethod("equalToSection", 
	function(physical_name, element, params) {
		
		var section_bool = true;

		if( $("#section_image_src").attr('src') != '' ) {
		
		if(typeof $('#home_physical_name').val() != 'undefined' && $.trim($('#home_physical_name').val()) != ''  && ( $.trim($('#home_image_gallery_id').attr('rel')) == ''  || $.trim($('#home_image_gallery_id').attr('rel')) == 'NULL' )   && physical_name == $.trim($('#home_physical_name').val())) {
			section_bool = false;
		}
		
		if( typeof $('#article_physical_name').val() != 'undefined' && $.trim($('#article_physical_name').val()) != ''  && ( ($('#article_image_gallery_id').attr('rel')) == ''  || $.trim($('#home_image_gallery_id').attr('rel')) == 'NULL')   && physical_name == $.trim($('#article_physical_name').val())) {
			section_bool = false;
		}

		postdata = "physical_name="+physical_name+'.'+$('#section_physical_name').attr('physical_extension')+"&temp_id="+$("#section_image_gallery_id").val()+"&caption="+$("#section_image_caption").val()+"&alt="+$("#section_image_alt").val();
			$.ajax({
				url: base_url+folder_name+"/common/check_image_name", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					if(data.status == 'false') {
						section_bool = false;
						$("#section_physical_name").attr("readonly",false);
					} 
				}
			});
			
		}
			console.log("section:"+section_bool);
					return section_bool;
					
	},'Section Image name already exists');
	
	$.validator.addMethod("TitleValidation", 
	function(HeadLine, element, params) {
		Title = true;
		
		 var headline = CKEDITOR.instances.article_head_line_id;
		headline_text = $("<div/>").html(headline.getData()).text();
		
		if($.trim(headline_text) == '') {
			Title = false;
		}
		console.log("Title:"+Title);
		return Title;
	},'This field is required.');
		
	  $("#content_form").validate({
		   ignore: [],
              debug: false,
		  rules: {
                        "ddMainSection" : {
							required : true
						},
						"txtArticleHeadLine" : {
							TitleValidation : true
						},
						/*"ddAgency" : {
							required : true
						}, */
						"txtCanonicalUrl" : {
							url: true
						},
						"txtPublishEndDate" : {
							greaterThan:  "#publish_start_datetimepicker"
		  				},
						"home_physical_name" : {
							equalToHome : true
						},
						"article_physical_name" : {
							equalToArticle : true
						},
						"section_physical_name" : {
							equalToSection : true
						}
						
		  },
		  errorPlacement: function (error, element)
				{
					if(element.attr("name") == 'txtPublishEndDate')
					{ 
						error.insertAfter($("#publish_error"));
					} else if(element.attr("name") == 'txtArticleHeadLine') {
						error.insertAfter($("#article_head_error"));
					}else if(element.attr("name") == 'txtBodyText') {
						error.insertAfter($("#article_body_error"));
					}else {
						error.insertAfter(element);
					}
				},
		  errorElement: "p"
		   
	  });
	  
	  $(".arrow-down").bind( "click", function() {
		
    var test = $(this).parent().parent().attr('id');
	console.log(test);
	if(test == "SortTextBox")	
	{	
		console.log('+++++++++++');
		$("#placehold").trigger("click");
		$("#placehold").trigger("focus");
		
	}
	else if(test == "StatusTextBox")
	{
		$("#placehold1").trigger("focus");
	  
	}
	
	else if(test == "SearchTextBox")
	{
		$("#placehold2").trigger("focus");
	  
	}
});

$('#count1').keyup(function() {
	var Total_Length = $("#count1").attr('maxlength');
    MetaTitle_Count = $(this).val().length;
	
	if(MetaTitle_Count > Total_Length) 
	Remain_Count  = 0;
	else 
	Remain_Count  = Total_Length - MetaTitle_Count;
	
	$('#charNum1').text(Remain_Count);
});

if(folder_name == 'niecpan') {

  	CKEDITOR.replace( 'article_head_line_id',
    {
        toolbar : [ { name: 'basicstyles', items: [ ] } ],
		  height:50,
		    forcePasteAsPlainText :true
		 /* extraPlugins: 'charcount', 
		  MaxLength: 100 */
				
    });
	
} else {
	
	
  	CKEDITOR.replace( 'article_head_line_id',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor' ] } ],
		  height:50,
		   forcePasteAsPlainText :true
		 /* extraPlugins: 'charcount', 
		  MaxLength: 100 */
				
    });
	
}
	
    CKEDITOR.replace( 'summary', {
		  toolbar : [ {  items: [ 'Bold', 'Italic', 'TextColor'] } ],
		   height:100,
		    extraPlugins: 'charcount', 
		  MaxLength: 200,
		   forcePasteAsPlainText :true
    });

		
		CKEDITOR.replace( 'body_text', {
		  toolbar : [ 
		  {items: [ 'TextColor','BGColor','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Bold','Italic','Underline','Embed','Html5audio'] },
			
		  {items: ['Cut', 'Copy','Paste','PasteText','PasteFromWord','Undo','Redo','Find','Replace']},
		  {items : [ 'Format']},
		  {items: ['Link','Unlink','Image']},
		  {items: [ 'Source','Strike','Subscript','Superscript','NumberedList','BulletedList','Outdent','Indent','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe','FontAwesome'] }
		  ],  
		    extraPlugins: 'autogrow,html5audio,embed,colordialog,fontAwesome', 
			contentsCss : image_url+'includes/ckeditor/plugins/fontAwesome/css/font-awesome.min.css',
			allowedContent : true,
			removePlugins : 'magicline',
			autoGrow_maxHeight : 1000,
			extraAllowedContent : 'audio(*){*}[*];img(*){*}[*];object(*){*}[*];embed(*){*}[*];param(*){*}[*];script(*){*}[*];blockquote(*){*}[*];p(*){*}[*]'
		});
		
		CKEDITOR.on("instanceReady", function(event) {
			$(".cke_button__justifyblock").trigger("click");
		});

	$(document.body).on('click', '#external_edit' ,function(){  
		var position = $(this).attr('rel');
		
		$("#external_title_error").empty();
		$("#external_url_error").empty();
		
		console.log(external_array[position]);
		
		$("#external_title_id").val(external_array[position]["external_title"]);
		$("#external_url_id").val(external_array[position]["external_url"]);
		$("#update_external_value").val(position);
		
		$("#update_to_list_div").show();
		$("#add_to_list_div").hide();
				
		if ($('#article_external').not(':visible')) {
			articleExternal();
			$('#month3').trigger('click'); 
		}
		
		
	});
	
	$("#cancel_to_list_id").click(function() {
		
		$("#external_title_id").val('');
		$("#external_url_id").val('');
		$("#update_external_value").val('');
		
		$("#external_title_error").empty();
		$("#external_description_error").empty();
		$("#external_url_error").empty();
			
		
		$("#update_to_list_div").hide();
				$("#add_to_list_div").show();
		
	});
	
	var url_title = true;
 var meta_title = true;
 $('#txtUrlTitle').keyup(function(event){
  url_title = false;
 });
 
 if($('#txtUrlTitle').val()!=="")
  url_title = false;
 if($('#count1').val()!=="")
  meta_title = false;
  
 $('#count1').keypress(function(){
  meta_title = false;
 });
   
 var headline = CKEDITOR.instances.article_head_line_id;
 headline.on('contentDom', function() {
	 
    headline.document.on('keyup', function(event) {
   
    var decoded_headline = $.trim($("<div/>").html(headline.getData()).text());
	
	decoded_headline = decoded_headline.substring(0,100);
	
	Remain_letters = $("#count1").attr('maxlength') - decoded_headline.length;
	
   // if(url_title == true)
     $('#txtUrlTitle').val(decoded_headline);
     
    if(meta_title == true) {
		$('#count1').val(decoded_headline); 
		var cs = $('#count1').val().length;
		$('#charNum1').text(Remain_letters);
	//	$('#count1').blur();
	}
    
    });
	
  });
  CKEDITOR.instances.article_head_line_id.on('blur', function() {
	$('#count1').blur();
  });
	
	$("#update_to_list_id").click(function() {
		
			var Bool = true;
			
			$("#external_title_error").empty();
			$("#external_url_error").empty();
			
			if($.trim($("#external_title_id").val()) == '') {
				Bool = false;
				$("#external_title_error").html("Title is Required");
			
			}
		
			  var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
				
			if($.trim($("#external_url_id").val()) == '') {
				Bool = false;
				$("#external_url_error").html("URL is Required");
			} else if(regexp.test($("#external_url_id").val()) == false) {
					Bool = false;
				$("#external_url_error").html("Invalid URL");
			}
			
			if(Bool == true){
		
				if(external_array.length < 20) {
				
				$("#priority_error").empty();
				
				position = $("#update_external_value").val();
				
				external_array[position]["external_title"] = $("#external_title_id").val();
				external_array[position]["external_short_title"] = shortDescription($("#external_title_id").val());
				external_array[position]["external_url"] = $("#external_url_id").val();
	
				$("#external_title_id").val('');
				$("#external_url_id").val('');
				$("#update_external_value").val('');
				
				fill_external_link_preview();
				
				$("#update_to_list_div").hide();
				$("#add_to_list_div").show();
				
					Flash_message("Updated the related Content","SessionSuccess");
				
				} else {
					Flash_message("Maximum 20 Related content","SessionError");
				}
				
				
			} else {
				console.log("Failure");
			}

	});

	  
});

function CheckImageContainer() {
if(	$("#section_image_gallery_id").val() == '' && $("#article_image_gallery_id").val() == '' && $("#home_image_gallery_id").val() == '') {
	$("#ArticleImageContainerId").hide();
} else {
	$("#ArticleImageContainerId").show();
}
	
	
}


function fill_external_link_preview() {
	
	$("#link_preview_body").empty();
	
	if(external_array.length != 0) {
		
		//WITH PRIORIY COLUMN
		/*external_array = external_array.sort(function(a,b) {
		 return parseInt(a[4]) > parseInt(b[4]);
		}); */

		for(Count = 0; Count < external_array.length ; Count++) {
			var Content = '';
			if(Count%2 == 0)
			var Class = 'class = "odd" role="row"';
			else 
			var Class = 'class = "even" role="row"';
			
			var breadcrumb = (external_array[Count]['bread_crumb'] == '')? "-" : external_array[Count]['bread_crumb'];
			
			var Index = Count+1;
			
		if(external_array[Count]["type"] == 'E') {
			var	Content = "<tr "+Class+" id='external_data"+Count+"' ><td class='index' data-type='"+external_array[Count]['type']+"' data-external_title='"+external_array[Count]['external_title']+"'  data-external_short_title='"+external_array[Count]['external_short_title']+"' data-external_url='"+external_array[Count]['external_url']+"'>"+Index+"</td><td><div align='center'><p title='"+external_array[Count]['external_title']+"' href='' >"+external_array[Count]['external_short_title']+"</p></div></td><td>-</td><td>External</td><td><div class='article_table_delete'>  <a  id='external_edit' rel='"+Count+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' onclick='external_action("+Count+")' id='external_action' rel='"+Count+"'><i class='fa fa-trash-o'></i></a></div></td>";
		}
		
		if(external_array[Count]["type"] == 'I') {
			var	Content = "<tr  "+Class+" id='external_data"+Count+"'><td class='index' data-type='"+external_array[Count]['type']+"'   data-content_type='"+external_array[Count]['content_type']+"' data-content_id='"+external_array[Count]['content_id']+"' data-short_title='"+external_array[Count]['short_title']+"' data-long_title='"+external_array[Count]['long_title']+"' data-bread_crumb='"+external_array[Count]['bread_crumb']+"'  data-url='"+external_array[Count]['url']+"'  >"+Index+"</td><td><div align='center'><p title='"+external_array[Count]['long_title']+"' href='' >"+external_array[Count]['short_title']+"</p></div></td><td><div align='center'><p title='"+external_array[Count]['bread_crumb']+"' url='"+external_array[Count]['url']+"' href='' >"+breadcrumb+"</p></div></td><td>Internal</td><td><div class='article_table_delete'> <a class='button cross' href='javascript:void(0)' title='Remove' onclick='external_action("+Count+")' id='external_action' rel='"+Count+"'  data-toggle='tooltip'   title='Delete' data-original-title='Delete' ><i class='fa fa-trash-o'></i></a> </div></td>";
		}
		
		$("#link_preview_head").show();
		$("#link_preview_body").append(Content);
		
		}
		
	} else {
		
		$("#link_preview_head").hide();
		$("#link_preview_body").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No data available in table</td></tr>');
		
	}
	
	console.log(external_array);
	$("#hide_external_link_id").val(JSON.stringify(external_array));
	
	 $('[data-toggle="tooltip"]').tooltip();
}

function CheckSectionName() {
	
	if($.trim($("#txtByLine").val()) == '') {
		$("#byline_id").val('');
	}
	
	var main_section_value = $.trim($("#main_section_id option:selected").attr('sectoin_data')).toLowerCase();
	//alert(main_section_value2);
	

		if(main_section_value == 'columns' || main_section_value == 'magazine' || main_section_value == 'life style') {
			
		/*	$('[name="ddAgency"]').each(function () {
			   $(this).rules('remove');
			 }); */
		} else  {
			
		/*	$('[name="ddAgency"]').each(function() {
				$(this).rules('add', {
                required: true
				});
			}); */
		}
			
	
}
	
function ValidForm(bool) {
	
	var validbool = true;
		
		$("#home_physical_name").attr("readonly",false);
		$("#section_physical_name").attr("readonly",false);
		$("#article_physical_name").attr("readonly",false);
		
		if(!$("#content_form").valid()) {
			bool= false;
		}
		
	//	validbool = ValidRelatedContent();	
		
		console.log(validbool);
		console.log(bool);
		
		if(bool == false && validbool == true) {
				$("#main_content>ul>li.selected").removeClass("selected");
				$("#content_div").addClass('selected');
			   	$("#view1").show();
			    $("#view2").hide();
			 	$("#view3").hide();
			   
			   console.log("1");
			   Flash_message("Please fill the mandatory fields","SessionError");
		} else if(bool == true && validbool == false) {
				$("#main_content>ul>li.selected").removeClass("selected");
				$("#related_div").addClass('selected');
			 $("#view1").hide();
			 $("#view2").hide();
			 $("#view3").show();
			 Flash_message("Please check the related content priority settings","SessionError");
		} else if(bool == false && validbool == false ){
			$("#main_content>ul>li.selected").removeClass("selected");
			$("#content_div").addClass('selected');
			
			$("#view1").show();
			    $("#view2").hide();
			 	$("#view3").hide();
			Flash_message("Please fill the mandatory fields","SessionError");
		} else {
			
		switch($("#status_id").val()) {
			case 'D':
				status_value = 'Are you sure want to save the details in Draft status?';
				break;
			case 'P':
				status_value = 'Are you sure want to save the details and Publish the article?';
				break;
			case 'U':
				status_value = 'Are you sure want to save the details and unpublish the article?';
				break;
			break;
			default:
				status_value = 'Are you sure want to save the details and Publish the article?';
		}
					
				
					
			var confirm_status = confirm(status_value);
				if(confirm_status==true)
				{
					
						alert($("#status_id").val());
					
				$("#main_section_id").attr('disabled',false)
				
					if($.trim($("#mapping_1").html()) == '') {
						
						var postdata = "content_id="+$("#content_id").val()+"&content_type=1&archive_year="+$("#archive_year").val();
							$.ajax({
									url: base_url+folder_name+"/common/multiple_section_mapping", // Url to which the request is send
									type: "POST",             // Type of request to be send, called as method
									data:  postdata,
									sync: false,
									dataType: "HTML",
									success: function(data)   // A function to be called if request succeeds
									{
										$("#mapping_1").html(data);
										
										$("#loading_msg").html("Please wait, Processing your inputs in article");
										$("#commom_loading").show();
										$("#content_form").submit();
										
									}
							});	
							
					} else {
						$("#loading_msg").html("Please wait, Processing your inputs in article");
						$("#commom_loading").show();
						$("#content_form").submit();
					}
					
					
				}
				}
	
}
/*
function ValidRelatedContent() {
	
if(external_array.length >= 1 ) {
	
	if(external_array[0][4] == 1) {
	
				var PriorityValue = external_array[0][4];			
				for(var Count = 1; Count < external_array.length; Count++) {
					
					console.log(external_array[Count][4]);
					console.log(PriorityValue);
					if(parseInt(external_array[Count][4]  - PriorityValue) > 1) {
						
						Flash_message("Priority "+ parseInt(Count+1) +" missing","SessionError");
						return false;
					} else if(parseInt(external_array[Count][4] - PriorityValue) == 0)	{
					
						Flash_message("Priority "+PriorityValue+" is duplicate","SessionError");
						return false;
					} else {
						var PriorityValue = external_array[Count][4];
					}
				}
			
			return true;
			
	} else {
		Flash_message("Priority 1 missing","SessionError");
		return false;
	}
		} else {
			return true;
		}
} */

function CheckContent(content_id) {
		var bool = true;
	
		for(var Count = 0; Count < external_array.length ; Count++) {
			
		if(external_array[Count]["content_id"]  == content_id) {
				bool = false;
			} 	 
		}
		return bool;
	}
	/*
	function CheckPriority(priority) {
		var bool = true;
		for(Count = 0; Count < external_array.length ; Count++) {
		
			if(external_array[Count][4]  == priority) {
				bool = false;
			} 	
		}
		return bool;
	}
	*/
	function Flash_message(msg,type) {
		
		$("#flash_msg_id").removeClass('SessionError');
		$("#flash_msg_id").removeClass('SessionSuccess');
		
		$("#flash_msg_id").html(msg);
		$("#flash_msg_id").addClass(type);
					
		
		$("#flash_msg_id").show();
					
		$("#flash_msg_id").slideDown(function() {
			setTimeout(function() {
			$("#flash_msg_id").slideUp();
			}, 1000);
		});
		
	}
	
function external_action(Index) {
	external_array.splice(Index,1);
	fill_external_link_preview();
	Flash_message("Related article Delete Successfully","SessionSuccess");
}

function ChangePopup(popup_name) {
	$("#current_image_popup").val(popup_name);
}

function articleUpload() {
	$('.article_upload').css({"display" : "block"});
	$('.article_browse').css({"display" : "none"});
	$('.img_upload').addClass('active');
	$('.img_browse').removeClass('active');
}
function articleBrowse() {
	$('.article_upload').css({"display" : "none"});
	$('.article_browse').css({"display" : "block"});
	$('.img_browse').addClass('active');
	$('.img_upload').removeClass('active');
}


function ck_width(){
	$("#cke_editor1").css({"width": "99%"});
}
function articleInternal() {
	$('.article_internal').css({"display" : "block"});
	$('.article_external').css({"display" : "none"});
	$("#priority_error").empty();
}
function articleExternal() {
	$('.article_internal').css({"display" : "none"});
	$('.article_external').css({"display" : "block"});
	$("#priority_error").empty();
}

<!--image light box-->

function articleUpload() {
	$('.article_upload').css({"display" : "block"});
	$('.article_browse').css({"display" : "none"});
	$('.img_upload').addClass('active');
	$('.img_browse').removeClass('active');
}
function articleBrowse() {
	$('.article_upload').css({"display" : "none"});
	$('.article_browse').css({"display" : "block"});
	$('.img_browse').addClass('active');
	$('.img_upload').removeClass('active');
}

/*
*
* Base64 conversion function
* 
* @access Public
* @param input data
* @return converted string
*
*/

function base64_encode(data) {

  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

/*
*
* Get the multiple section mapping 
* @access Public
* @param content_id, content type
* @return HTML format from multiple_section_mapping view file
*
*/

function multiple_section_mapping() {
		if($.trim($("#mapping_1").html()) == '') {
		
		$("#loading_msg").html("Please wait, Loading the multiple section mapping");
		$("#commom_loading").show();
	
		var postdata = "content_id="+$("#content_id").val()+"&content_type=1&archive_year="+$("#archive_year").val();
			$.ajax({
					url: base_url+folder_name+"/common/multiple_section_mapping", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data:  postdata,
					sync: false,
					dataType: "HTML",
					success: function(data)   // A function to be called if request succeeds
					{
						$("#mapping_1").html(data);
						$("#commom_loading").hide();
					}
			});	
			
	}
	return true;
}

function shortDescription(fullDescription)
{
	var shortDescription = "";
	fullDescription = ($.trim(fullDescription));
	
	if (fullDescription != '') {
		
		var initialCount = 23;
		if (fullDescription.length > initialCount) {
			shortDescription = fullDescription.substr(0, initialCount)+"...";
		} else {
			return fullDescription;
		}
	}
	return shortDescription;
}
