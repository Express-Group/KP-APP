
$("#audio_video_physical_name").on('input',function(e) {
	var value = $("#audio_video_physical_name").val();
	result = value.replace(/[^a-zA-Z0-9_-]/g,'');
		
	$("#audio_video_physical_name").val(result);
});

var option = $('option:selected', '#main_section_id').attr('url_structure');

if(option  != undefined)
	$("#mainsection_breadcrumb").html("Bread Crumb : "+option);
else 
	$("#mainsection_breadcrumb").empty();
	
	$("#btnAudioSource").change(function(){
	
	if(ValidateExtension()) {
		
			var formAudio = new FormData();
			formAudio.append('audio_file',document.getElementById("btnAudioSource").files[0]);
			
			$.ajax({
			url: base_url+folder_name+"/audio_video_manager/audio_upload",
			type: "POST",
			data: formAudio,
			contentType:false,
			cache: false,
			processData: false,
			dataType: "json",
			success: function(data){
				if(data.audio_path != '') {
					$("#audio_script").val(data.audio_path);
				} else {
					lblError.innerHTML = "Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.";
					return false;
				}
			}
			});
			
		}
	
	});
	 

	 	// IE & DM Differents
	

	$("#main_section_id").change(function(){
		
		var main_section_data = $('option:selected', '#main_section_id').attr('sectoin_data');
		
		var option = $('option:selected', '#main_section_id').attr('url_structure');

		if(option  != undefined)
			$("#mainsection_breadcrumb").html("Bread Crumb : "+option);
		else 
			$("#mainsection_breadcrumb").empty();	
		
		$("#article_section").val($("#main_section_id").val());
		
		if($.trim($( "#main_section_id option:selected" ).text()) == 'Nation') {
			$('#country_id option:contains("India")').prop('selected', true);
			
			$('#txtState').val('');
			$('#state_id').val('');
			
			$('#txtCity').val('');
			$('#city_id').val('');
			
		}
		
		var main_section_text = $.trim($( "#main_section_id option:selected" ).text());
		
		 $("#main_section_id").attr("rel",$("#main_section_id").find('option:selected').attr('rel'));
		  $("#main_section_id").attr("section_data",$("#main_section_id").find('option:selected').attr('section_data'));
		  
		   $("#main_section_id").attr("rel");
	
	 $("input:checkbox[name='cbSectionMapping[]']").each(function() {
		 $(this).css("visibility", "visible");
	 });
	
	$("#section_mapping"+$("#main_section_id").val()).prop('checked', false);
	
	$("#section_mapping"+$("#main_section_id").val()).css("visibility", "hidden");
		
	}); 
	 
function ValidForm() {
	
		bool = true;
		
		$("#audio_video_physical_name").attr("readonly",false);
		
		if(!$("#content_form").valid()) {
			bool= false;
		}
		
		if(bool == true) {
			
		
			
		switch($("#status_id").val()) {
			case 'D':
				status_value = 'Are you sure you want to save the details in Draft status?';
				break;
			case 'P':
				status_value = 'Are you sure you want to save the details and Publish the '+$("#content_name").val()+'?';
				break;
			case 'U':
				status_value = 'Are you sure you want to save the details and unpublish the '+$("#content_name").val()+'?';
				break;
			default:
				status_value = 'Are you sure you want to save the details and Publish the '+$("#content_name").val()+'?';
		}
					
			var confirm_status = confirm(status_value);
				if(confirm_status==true)
				{
			$("#main_section_id").attr('disabled',false)
			
			if($.trim($("#mapping_1").html()) == '') {
				
				var content_type = $("#content_type").val();
			
				var postdata = "content_id="+$("#content_id").val()+"&content_type="+content_type+"&archive_year="+$("#archive_year").val();
					$.ajax({
							url: base_url+folder_name+"/common/multiple_section_mapping", // Url to which the request is send
							type: "POST",             // Type of request to be send, called as method
							data:  postdata,
							async: false,
							dataType: "HTML",
							success: function(data)   // A function to be called if request succeeds
							{
								$("#mapping_1").html(data);
								$("#content_form").submit();
							}
					});	
						
			} else {
				$("#content_form").submit();
			}
			
				}
			
		}
	
	
}
	
	// Draft button event
	
	// Form Validation

	
		$.validator.addMethod("equalToHome", 
	function(physical_name, element, params) {
		
		var home_bool = true;
		
		if($("#status_id").val() == 'D' && $.trim(physical_name) == '' && $("#audio_video_image_gallery_id").val() == '') {
			return home_bool;
		}
		
		if($.trim(physical_name) == '' && $("#audio_video_image_gallery_id").val() != '') {
			return false;
		}
		
		postdata = "physical_name="+physical_name+'.'+$('#audio_video_physical_name').attr('physical_extension')+"&temp_id="+$("#audio_video_image_gallery_id").val()+"&caption="+$("#audio_video_image_caption").val()+"&alt="+$("#audio_video_image_alt").val();
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
						$("#audio_video_physical_name").attr("readonly",false);
					} 
				}
			});
			
			return home_bool;
	
	},'This image name already exists');

	var message = '';
	
	$.validator.addMethod("TitleValidation", 
	function(HeadLine, element, params) {
		Title = true;
		
		 var headline = CKEDITOR.instances.audio_video_head_line_id;
		headline_text = $("<div/>").html(headline.getData()).text();
		
		if($.trim(headline_text) == '') {
			Title = false;
		}
		console.log("Title:"+Title);
		return Title;
	},'This field is required.');
		
	
	if($("#content_type").val() == 5) {
		
				$.validator.addMethod("ValidateResource", 
			function(temp_name, element, params) {
			
				return ValidateExtension();
			
			},"Please upload files having extensions: <b> mp3, wav </b> only.");
			
			 $("#content_form").validate({
				   ignore: [],
					  debug: false,
				  rules: {
							  "txtAudioVideoHeadLine" : {
									TitleValidation : true
								},
								"ddMainSection" : {
									required : true
								},
								"txtCanonicalUrl" : {
									url: true
								},
								"txtScript" : {
									required : true
								},
								"audio_video_physical_name" : {
									equalToHome : true
								},
								"imgAudioVideoId" : {
									required :true
								},
								"ExistingAudioSource" : {
									required :true,
								   ValidateResource: true
								}
						},
						errorPlacement: function (error, element)
						{
						if(element.attr("name") == 'txtAudioVideoHeadLine') {
							error.insertAfter($("#title_error"));
						} else if(element.attr("name") == 'btnImage') { 
							error.insertAfter($("#image_error"));
						} else if(element.attr("id") == 'ExistingAudioSource') { 
							$("#lblError").html(error.text());
						} else if(element.attr("id") == 'txtScript') { 
							error.appendTo($("#script_error"));	
						} else {
							error.insertAfter(element);
						}
					}
			 });
			 
			 // Draft button event
			
				$("#send_drafttop_id").click(function() {
				
				CKEDITOR.instances.audio_video_head_line_id.updateElement();
				CKEDITOR.instances.summary.updateElement();
				
				$("#status_id").val('D');
				
				$('[name="txtMetaTitle"],[name="imgAudioVideoId"], [name="ExistingAudioSource"]').each(function () {
				   $(this).rules('remove');
				 });
				 
				 $('[name="ExistingAudioSource"]').rules('add',{
						 ValidateResource: true
				});
				
				 ValidForm();
				
			});
			
			$("#publishtop_id").click(function() {
				
				CKEDITOR.instances.audio_video_head_line_id.updateElement();
				CKEDITOR.instances.summary.updateElement();
				
				$("#status_id").val('P');
				
				$('[name="txtMetaTitle"],[name="txtScript"],[name="imgAudioVideoId"], [name="ExistingAudioSource"]').each(function () {
					$(this).rules('add', {


						required: true
					});
				 });
				 
				$('[name="ExistingAudioSource"]').rules('add',{
						 ValidateResource: true
				});
				
				 ValidForm();
				
			});
			
				$("#unpublishtop_id").click(function() {
				
				CKEDITOR.instances.audio_video_head_line_id.updateElement();
				CKEDITOR.instances.summary.updateElement();
				
				$("#status_id").val('U');
				
				$('[name="txtMetaTitle"],[name="txtScript"], [name="ExistingAudioSource"]').each(function () {
					$(this).rules('add', {
						required: true
					});
				 });
				 
				 $('[name="ExistingAudioSource"]').rules('add',{
						 ValidateResource: true
				});
				
				 ValidForm();
				
			});
	
	} else {
			
			 $("#content_form").validate({
				   ignore: [],
					  debug: false,
				  rules: {
							  "txtAudioVideoHeadLine" : {
									TitleValidation : true
								},
								"ddMainSection" : {
									required : true
								},
								"txtCanonicalUrl" : {
									url: true
								},
								"txtScript" : {
									required : true
								},
								"audio_video_physical_name" : {
									equalToHome : true
								},
								"imgAudioVideoId" : {
									required :true
								}
						},
						errorPlacement: function (error, element)
						{
						if(element.attr("name") == 'txtAudioVideoHeadLine') {
							error.insertAfter($("#title_error"));
						} else if(element.attr("name") == 'btnImage') { 
							error.insertAfter($("#image_error"));
						} else if(element.attr("id") == 'txtScript') { 
							error.appendTo($("#script_error"));	
						} else {
							error.insertAfter(element);
						}
					}
			 });
			 
			 // Draft button event
			
				$("#send_drafttop_id").click(function() {
				
				CKEDITOR.instances.audio_video_head_line_id.updateElement();
				CKEDITOR.instances.summary.updateElement();
				
				$("#status_id").val('D');
				
				$('[name="txtMetaTitle"],[name="imgAudioVideoId"]').each(function () {
				   $(this).rules('remove');
				 });
				
				 ValidForm();
				
			});
			
			$("#publishtop_id").click(function() {
				
				CKEDITOR.instances.audio_video_head_line_id.updateElement();
				CKEDITOR.instances.summary.updateElement();
				
				$("#status_id").val('P');
				
				$('[name="txtMetaTitle"],[name="txtScript"],[name="imgAudioVideoId"]').each(function () {
					$(this).rules('add', {
						required: true
					});
				 });
				 
				 ValidForm();
				
			});
			
				$("#unpublishtop_id").click(function() {
				
				CKEDITOR.instances.audio_video_head_line_id.updateElement();
				CKEDITOR.instances.summary.updateElement();
				
				$("#status_id").val('U');
				
				$('[name="txtMetaTitle"],[name="txtScript"]').each(function () {
					$(this).rules('add', {
						required: true
					});
				 });
				
				 ValidForm();
				
			});
		
	}
	
	 
	// Form Validation
	
	// CKEDITOR coding
if(folder_name == 'niecpan') {
	CKEDITOR.replace( 'audio_video_head_line_id',
    {
        toolbar : [ { name: 'basicstyles', items: [ ] } ],
		  height:25,
		//  extraPlugins: 'charcount', 
		  MaxLength: 100,
		  forcePasteAsPlainText :true
				
    });
} else {
	CKEDITOR.replace( 'audio_video_head_line_id',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor' ] } ],
		  height:25,
		//  extraPlugins: 'charcount', 
		  MaxLength: 100,
		  forcePasteAsPlainText :true
				
    });
}	
	
    CKEDITOR.replace( 'summary', {
		  toolbar : [ {  items: [ 'Bold', 'Italic', 'TextColor'] } ],
		   height:100,
		    extraPlugins: 'charcount', 
		  MaxLength: 200,
		  forcePasteAsPlainText :true
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
   
	
var headline = CKEDITOR.instances.audio_video_head_line_id;
 headline.on('contentDom', function() {
    headline.document.on('keyup', function(event) {
		
    var decoded_headline = $.trim($("<div/>").html(headline.getData()).text());
	
	decoded_headline = decoded_headline.substring(0,100);
	
	Remain_letters = $("#count1").attr('maxlength') - decoded_headline.length;
	
    //if(url_title == true)
     $('#txtUrlTitle').val(decoded_headline);
     
    if(meta_title == true) {
		$('#count1').val(decoded_headline); 
		var cs = $('#count1').val().length;
		$('#charNum1').text(Remain_letters);
		$('#count1').blur();
	}
    
    });
  });
  
  CKEDITOR.instances.audio_video_head_line_id.on('blur', function() {
	$('#count1').blur();
  });
  
	// CKEDITOR coding
	
	// Meta title and description Script 
	
$('#count1').keyup(function() {
	var Total_Length = $("#count1").attr('maxlength');
    MetaTitle_Count = $(this).val().length;
	
	if(MetaTitle_Count > Total_Length) 
	Remain_Count  = 0;
	else 
	Remain_Count  = Total_Length - MetaTitle_Count;
	
	$('#charNum1').text(Remain_Count);
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

$("#count1").blur(function() {
	
	$MetaTitle = $.trim($("#count1").val());
		
		$Title = $("<div/>").html(CKEDITOR.instances.audio_video_head_line_id.getData()).text();	
	
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

	// Meta title and description Script 
	
	
	//  Tag Suggestion Coding 
	
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
	 
	 //  Tag Suggestion Coding 
	 
	 // Section Coding
	 
$("#section_href").click(function(){
	multiple_section_mapping();
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
		
			var content_type = $("#content_type").val();
	
		var postdata = "content_id="+$("#content_id").val()+"&content_type="+content_type+"&archive_year="+$("#archive_year").val();
			$.ajax({
					url: base_url+folder_name+"/common/multiple_section_mapping", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data:  postdata,
					async: false,
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
	 // Section Coding
	 
	 // Image upload popup
	 
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
	
		if($("#browse_image_id").val() != '' && $("#browse_image_id").val() != 0 ) {
			
				if($("#browse_image_id").data('image_source')) {
			
					var ImageDetails = $("#browse_image_id").data();
					var content_type = $("#content_type").val();
					
					
					ImageData = "content_id="+ImageDetails.content_id+"&content_type="+content_type;
			 
					$.ajax({
					url		: base_url+folder_name+"/image/Insert_temp_from_image_library",
					type	: "POST",
					data	: ImageData,
					dataType: "json",
					async	: false, 	
					success	: function(data) {
						
							$('#audio_video_image_gallery_id').val(data.image_id);
				$('#audio_video_image_gallery_id').attr('rel',data.imagecontent_id);
				$("#audio_video_image_set").html('Change Image');
				$("#audio_video_image_set").removeClass('BorderRadius3');
				$('#audio_video_image_src').attr('src',data.source);
				$('#audio_video_image_container').show();
				$("#audio_video_image_set").next().show();
				$("#audio_video_image_set").next().next().show();
				
				if(folder_name == 'niecpan')
					$("#audio_video_image_caption").val(data.caption);
				else 
					$("#audio_video_image_caption").val('');
				
				$("#audio_video_image_alt").val(data.alt);
				
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
				
				$("#audio_video_physical_name").val(physical_name);
				
				$("#audio_video_physical_name").attr('physical_extension',data.physical_extension);
				
					$("#audio_video_physical_name").attr("readonly",true);
				
				$("#LoadingSelectImageLocal").hide();
				
				var inst = $.remodal.lookup[$('[data-remodal-id=image1]').data('remodal')];
				if(!inst) {
					$('[data-remodal-id=image1]').remodal().close();
				 } else{
                      inst.close();
                  }
				$("#imagelibrary").val('');
						}
						
						
					}); 
					
				}
			
			}
		});

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


	$("#imagelibrary").change(function() {
			$("#LoadingSelectImageLocal").show();
		var ext = $('#imagelibrary').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			console.log('Invalid Extension!');
		} else {
			
			var formImage = new FormData();
			formImage.append('userImage',document.getElementById("imagelibrary").files[0]);
			formImage.append('content_type',$("#content_type").val());
			
			setTimeout(function(){
			uploadFormData(formImage);
			},1000);
		}
	});
	

function createFormData(image) {

	var formImage = new FormData();
	$("#LoadingSelectImageLocal").show();
	formImage.append('userImage', image[0]);
	formImage.append('content_type', document.getElementById("content_type").value());
	uploadFormData(formImage);
}

function uploadFormData(formImage) {

			$.ajax({
			url: base_url+folder_name+"/image/upload_new_images",
			type: "POST",
			data: formImage,
			dataType: 'json',
			contentType:false,
			cache: false,
			processData: false,
			async: false, 
			success: function(data)	{
				
				$('#audio_video_image_gallery_id').val(data.image_id);
				$('#audio_video_image_gallery_id').attr('rel',data.imagecontent_id);
				$("#audio_video_image_set").html('Change Image');
				$("#audio_video_image_set").removeClass('BorderRadius3');
				$('#audio_video_image_src').attr('src',data.image);
				$('#audio_video_image_container').show();
				$("#audio_video_image_set").next().show();
				$("#audio_video_image_set").next().next().show();
				$("#resource_uploaded_image").html('Image Set');
									
			    //$("#audio_video_image_caption").val(data.caption);
				//$("#audio_video_image_alt").val(data.alt_tag);
				
				$("#audio_video_image_caption").val('');
				$("#audio_video_image_alt").val('');
				
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
				
				$("#audio_video_physical_name").val(physical_name);
				
				$("#audio_video_physical_name").attr('physical_extension',data.physical_extension);
				
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

$(document.body).on('click', '#delete_audio_video_image' ,function(){
		var image_type = $(this).attr('rel');
		var ImageIndex = '';
		
		ImageIndex = $("#audio_video_image_gallery_id").val();
		
		
		if(ImageIndex != '') {
		
			var ImageData = 'image_id='+ImageIndex;

			var confirm_status = confirm('Are you sure to delete the image');

			if(confirm_status ==  true) {
		
			$.ajax({
				url: base_url+folder_name+"/resources/delete_temp_image",
				type: "POST",
				data: ImageData,
				dataType: "json",
				async: false, 
				success: function(data) {

			
				
						$('#resource_uploaded_image').html('Image not set');
						$("#audio_video_image_set").html('Set Image');
						$("#audio_video_image_set").addClass('BorderRadius3');
						$("#audio_video_image_gallery_id").val('');
						$("#audio_video_image_gallery_id").attr('rel','');
						$("#audio_video_image_src").attr('src','');
						$('#audio_video_image_container').hide();
						$("#audio_video_image_set").next().hide();
						$("#audio_video_image_set").next().next().hide();
						$("#audio_video_physical_name").val('');
						$("#audio_video_physical_name").attr('physical_extension','');
						$("#audio_video_image_caption").val('');
						$("#audio_video_image_alt").val('');
					
				}
			});

			}
			
		} else {
				alert("Invalid Actions");
		}	

	});
	
	$(document.body).on('click', '#edit_audio_video_image' ,function(){
		var image_type = $(this).attr('rel');
	
		ImageIndex = $("#audio_video_image_gallery_id").val();
		
		if(ImageIndex != '') {
					
			if(content_type == 4)
			window.open(base_url+folder_name+"/video_image_processing/"+encodeURIComponent(base64_encode(ImageIndex)), '_blank');
			else
			window.open(base_url+folder_name+"/audio_image_processing/"+encodeURIComponent(base64_encode(ImageIndex)), '_blank');	
		}
		else  {
			alert("Invalid Action for Image Processing");
		}
		
	});
	 
	 // Image upload popup
	 
	 // Video Script popup
	 
	  $("#popup_event").click(function()
  {
	  if($.trim($("#txtScript").val()) != "")
	  {
			iframe_script();
			var inst = $.remodal.lookup[$('[data-remodal-id=modal2]').data('remodal')];
			if(!inst)
			{
				$('[data-remodal-id=modal1]').remodal().open();
			}
			else
			{
				inst.open();
			}
	  }	 
  });
  
  function iframe_script()
{
	var get_script = document.getElementById("txtScript").value;
	if(get_script != '')
	{
		if($.trim($("#video_site").val()) == 'ventunovideo'){
			document.getElementById("play_video_div").innerHTML = '<object width="630" height="441" id="ventuno_player_0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param name="movie" value="http://cfplayer.ventunotech.com/player/vtn_player_2.swf?vID=='+get_script+'"/>  <param name="allowscriptaccess" value="always"/>     <param name="allowFullScreen" value="true"/>     <param name="wmode" value="transparent"/>     <embed src="http://cfplayer.ventunotech.com/player/vtn_player_2.swf?vID=='+get_script+'" width="630" height="441" wmode="transparent" allownetworking="all" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
		} else {
			document.getElementById("play_video_div").innerHTML = get_script;
		}
	}
	else
	{
		document.getElementById("play_video_div").innerHTML = "Please enter a valid iframe script";
	}
}

  // Video Script popup
  
$(document).on('closed', '.remodal', function (e) {
 $("#play_video_div iframe").attr("src","");
});
	 
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
	// Resource format validation
    function ValidateExtension() {
		
              	var allowedFiles = ["mp3", "wav"];
        var fileUpload = document.getElementById("btnAudioSource");
		
		if(fileUpload.value.toLowerCase() != '')
		$("#ExistingAudioSource").val(fileUpload.value.toLowerCase());
        var lblError = document.getElementById("lblError");
		$Source = $("#ExistingAudioSource").val();
		var last = $Source.substring($Source.lastIndexOf("\\") + 1, $Source.length);
		
		 var fileNameExt = $Source.substr($Source.lastIndexOf('.') + 1);
		$("#SourceName").html(last);
       if ($.inArray(fileNameExt, allowedFiles) == -1){
            lblError.innerHTML = "Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.";
            return false;
        } 
        lblError.innerHTML = "";
        return true;
    }
// Resource format validation 