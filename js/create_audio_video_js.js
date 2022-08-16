/*function validate_script()
{
		var str = $.trim($("#txtScript").val());
		var scriptExists = str.indexOf('<iframe');
		if(str != "")
		{
			if(scriptExists > -1)
			{
				var i = scriptExists + 10;
				str = str.substr(i);
				str = str.substr(0, str.indexOf('</iframe>'));

				if(str == '')
				{
					$("#error_txtScript").html('Please enter a valid iframe script');
					return false;
				} 
				else
				{
					var src_str = $.trim($("#txtScript").val());
					var srcExists = src_str.indexOf('src="');
						if (srcExists > -1)
						{
							var j = srcExists + 5;
							src_str = src_str.substr(j);
							src_str = src_str.substr(0, src_str.indexOf('"'));
							if(src_str == '')
							{
								$("#error_txtScript").html('Iframe source is empty');
								return false;
							}
							else
							{
								 var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
								 if(regexp.test(src_str) == false)
								 {
									$("#error_txtScript").html('Please enter the valid source url');
									return false;
								 }
							}
						} 
						else 
						{
							$("#error_txtScript").html("Missing the video source in iframe tag");
							return false;
						}
						$("#error_txtScript").html("");
						return true;
				}
			}
			else
			{
				$("#error_txtScript").html("Please enter a valid iframe script");
				return false;
			}
		}
		else
		{
			$("#error_txtScript").html("");
			return true;
		}
}*/

function tag_validate()
{
	var bool = false;
	$('.tagedit-listelement').each(function(){
		if($(this).children('input').attr('type') == 'hidden') {
			bool = true 
		}
	});
	
	if(bool == false)
	{
		$("#tag_error").html("Please enter tags");
		return false;
	}
	else
	{
		$("#tag_error").html("");
		return true;
	} 
}
function CheckImageName() {
	/*
	postdata = "physical_name="+$('#physical_name').val()+'.'+$('#physical_name').attr('physical_extension')+"&temp_id="+$('#physical_name').attr('rel');
			$.ajax({
				url: base_url+"niecpan/common/check_image_name", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					if(data.status == 'false') {
						$("#physical_error").html('Image name already exists');
						bool = false;
					} else {
						$("#physical_error").html('');
					}
				}
			}); */
}
function Flash_message(msg,type) 
{
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

function CheckSectionName() {
	
	var main_section_value = $.trim($("#ddSection option:selected").attr('section_data')).toLowerCase();
	
		if(main_section_value == 'columns' || main_section_value == 'magazine' || main_section_value == 'the sunday standard') {
			$('[name="ddAgency"]').each(function () {
			   $(this).rules('remove');
			 });
		} else  {
			$('[name="ddAgency"]').each(function() {
				$(this).rules('add', {
                required: true
				});
			});
		}
}

	
$(document).ready(function()
{
	
$("#physical_name").on('input blur',function(e) {
	var value = $("#physical_name").val();
	result = value.replace(/[^a-zA-Z0-9_-]/g,'');
		
	$("#physical_name").val(result);
});

	
$("#txtMetaTitle").blur(function() {
	$MetaTitle = $.trim($("#txtMetaTitle").val());
	
	if($MetaTitle != '') {
		
	var postdata = "metatitle="+$MetaTitle;
	$("#suggestion_div").empty();
		$("#suggestion_div").show();
			$.ajax({
				url: base_url+"niecpan/common/get_tags_by_meta_title", // Url to which the request is send
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
			  var content = '<li class="tagedit-listelement tagedit-listelement-old"><span dir="ltr">'+TagDetails.value+'</span><input type="hidden" value="'+TagDetails.value+'" name="txtTags['+TagDetails.id+'-a]"><a title="Remove from list." class="tagedit-close">x</a></li>';
		$(".tagedit-listelement-new").last().before(content);
		
		 }
		 
	 });
	 
	 
	 
	
	CKEDITOR.replace( 'txtTitle',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','FontSize' ] } ],
		 height:75,
		  extraPlugins: 'charcount', 
		  MaxLength: 100
    });
	
	 CKEDITOR.replace( 'txtSummary', {
		  toolbar : [ {  items: [ 'Bold', 'Italic', 'TextColor'] } ],
		   height:100,
		    extraPlugins: 'charcount', 
		  MaxLength: 200
    });
	
		$.validator.addMethod("equalToImage", 
	function(physical_name, element, params) {
		
		var home_bool = true;

		if($("#preview_image").attr('src') != '') {	

		postdata = "physical_name="+physical_name+'.'+$('#physical_name').attr('physical_extension')+"&temp_id="+$("#physical_name").attr('rel');
			$.ajax({
				url: base_url+"niecpan/common/check_image_name", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					if(data.status == 'false') {
						home_bool = false;
					} 
				}
			});
			
		}
	
			
			return home_bool;
	
	},'Image name already exists');
	
	$("#video_audio_upload").validate(
	{
		 	ignore: [],
			//ignore: ':hidden:not("#byline_ID , #txtTitle")',
            debug: false,
			rules:
			{
				txtTitle:
				{
				  required: function() { CKEDITOR.instances.txtTitle.updateElement();}
				},
				ddSection: { required: true },
				ddAgency: { required: true },
				//byline_ID: { required: true },
				//txtTags[]: { required: true }
				//txtbyline: { required: true },
				txtCanonicalURL: { url: true, },
				physical_name: { equalToImage : true}
			},
			
			messages: 
			{
				txtTitle: { required: "Please enter title",},
				txtDescription: { required: "Please enter description"},
				ddSection: { required: "Please select section"},
				//txtbyline: { required: "Please select byline"},
				//byline_ID: { required: "Please select byline"},
				ddAgency: { required: "Please select agency"},
				txtTags: { required: "Please enter tags"},
				txtScript: { required: "Please enter script"},
				txtMetaTitle:{ required: "Please enter meta title"},
				txtMetaDescription: { required: "Please enter meta description"},
				country_id: { required: "Please select country"},
				state_id: { required: "Please select state"},
				city_id: { required: "Please select city"},
				txtCanonicalURL: {
					 required: "Please enter canonical URL",
					 url: "Please enter a valid URL"
					 },
				//txtUrlStructure: { required: "Please enter URL structure"},
				btnImage:
				{ 
					required: "Please select thumbnail image",
					accept: "Upload image with JPEG, JPG, PNG, GIF format",
				},
			},
			
			errorPlacement: function (error, element)
			{
				if(element.attr("name") == 'btnImage')
				{ 
					error.insertAfter($("#image_error"));
				}
				else if(element.attr("id") == 'txtTags')
				{ 
					error.insertAfter($("#tag_error"));
				}
				else if(element.attr("id") == 'txtTitle')
				{ 
					error.insertAfter($("#title_error"));
				}
				/*else if(element.attr("id") == 'txtbyline')
				{ 
					error.appendTo($("#byline_error"));
				}*/
				else if(element.attr("id") == 'txtScript')
				{ 
					error.appendTo($("#error_txtScript"));
				}
				else
				{
					error.insertAfter($("#"+element.attr("name")));
				}
			}
	});
	
	CheckSectionName();
	CheckImageName();
	
	if(check_content=='5')
		var content_type = "audio";
	else if(check_content=='4')
		var content_type = "video";
	
	var hide_show_img = $("#preview_image").attr('src');
	if(hide_show_img == '#')
	{
		$("#preview_image").hide();
	}
	
	$("#hidden_status").val('');
	$("#btnDraft").click(function()
	{
		$("#tag_error").html("");
		
		$('[name="txtDescription"], [name="txtTags"], [name="txtScript"], [name="txtMetaTitle"], [name="txtMetaDescription"], [name="country_id"],[name="state_id"], [name="city_id"], [name="txtUrlStructure"], [name="btnImage"]').each(function () {
           $(this).rules('remove');
         });
		 
		 	if(status_img_check != "")	
		{	
			$('[name="btnImage"]').each(function(){
				 $(this).rules('remove', 'required');
			});
			$('[name="physical_name"]').each(function()
				{
					$(this).rules('remove', 'required');
					$(this).rules('remove','equalToImage');
				});
		} else {
			$('[name="physical_name"]').each(function()
				{
					$(this).rules('remove','equalToImage');
				});
		}
		 
		 CheckSectionName();
		 $("#video_audio_upload").valid();
		 
			if($("#video_audio_upload").valid()) 
			{
				var confirm_status = confirm("Are you sure you want to save the details in Draft status?");
				if(confirm_status==true)
				{
					CKEDITOR.instances.txtTitle.updateElement();
					$("#hidden_status").val('D');
					$("#ddSection").attr('disabled',false);
					$("#video_audio_upload").submit();
				}
			}
			else
			{
				Flash_message("Some validation error exist. Please check all fields","SessionError");
				$("#main_content>ul>li.selected").removeClass("selected");
				$("#content_div").addClass('selected');
				$("#view1").show();
				$("#view2").hide();
				//return false;
			}
			
	});
	
	$("#btnSaveStatus").click(function()
	{
		$('[name="txtDescription"], [name="txtTags"], [name="txtScript"], [name="txtMetaTitle"], [name="txtMetaDescription"], [name="country_id"]').each(function ()
		{ 
            $(this).rules('add',
			{
                required: true,
   			});
		});
				
		$('[name="btnImage"]').each(function()
				{
					$(this).rules('add',
					{
						required: true,
						accept: "jpg|jpeg|png|gif|JPEG|GIF|PNG|JPG",
					});
				});
				
				$('[name="physical_name"]').each(function()
				{
					$(this).rules('add',
					{
						required: true,
						equalToImage : true
					});
				});
		if(status_img_check != "")	
		{	
			$('[name="btnImage"]').each(function(){
				 $(this).rules('remove', 'required');
			});
			$('[name="physical_name"]').each(function()
				{
					$(this).rules('remove', 'required');
					$(this).rules('remove','equalToImage');
				});
		}
		 
		 tag_validate();
		 
		 CheckSectionName();
		 
			if($("#video_audio_upload").valid() && tag_validate()) 
			{
				var confirm_status = confirm("Are you sure you want to save the details and publish the "+content_type+"?");
				if(confirm_status==true)
				{
					CKEDITOR.instances.txtTitle.updateElement();
					$("#hidden_status").val('S');
					$("#ddSection").attr('disabled',false);
					$("#video_audio_upload").submit();
				}	
			}
			else
			{
				Flash_message("Some validation error exist. Please check all fields","SessionError");
				$("#main_content>ul>li.selected").removeClass("selected");
				$("#content_div").addClass('selected');
				$("#view1").show();
				$("#view2").hide();
			}
	});
	
	
	$("#btnPublish").click(function()
	{
		$('[name="txtDescription"], [name="txtTags[]"], [name="txtScript"], [name="txtMetaTitle"], [name="txtMetaDescription"], [name="country_id"]').each(function ()
		{ 
            $(this).rules('add',
			{
                required: true,
   			});
		});
			
		$('[name="btnImage"]').each(function()
				{
					$(this).rules('add',
					{
						required: true,
						accept: "jpg|jpeg|png|gif|JPEG|GIF|PNG|JPG",
					});
				});
				
				$('[name="physical_name"]').each(function()
				{
					$(this).rules('add',
					{
						required: true,
						equalToImage : true
					});
				});
		if(status_img_check != "")	
		{	
			$('[name="btnImage"]').each(function(){
				 $(this).rules('remove', 'required');
			});
			$('[name="physical_name"]').each(function()
				{
					$(this).rules('remove', 'required');
					$(this).rules('remove','equalToImage');
				});
		}
		 
		 
		 tag_validate();
		 CheckSectionName();
			if($("#video_audio_upload").valid() && tag_validate()) 
			{
				var confirm_status = confirm("Are you sure you want to save the details and publish the "+content_type+"?");
				if(confirm_status==true)
				{
					CKEDITOR.instances.txtTitle.updateElement();
					$("#hidden_status").val('P');
					$("#ddSection").attr('disabled',false);
					$("#video_audio_upload").submit();
				}	
			}
			else
			{
				Flash_message("Some validation error exist. Please check all fields","SessionError");
				$("#main_content>ul>li.selected").removeClass("selected");
				$("#content_div").addClass('selected');
				$("#view1").show();
				$("#view2").hide();
			}
	});
	
	$("#btnApproval").click(function()
	{	
			$('[name="txtDescription"], [name="txtTags"], [name="txtScript"], [name="txtMetaTitle"], [name="txtMetaDescription"], [name="country_id"]').each(function ()
			{
				$(this).rules('add',
				{
					required: true
				});
			});
		
				
		$('[name="btnImage"]').each(function()
				{
					$(this).rules('add',
					{
						required: true,
						accept: "jpg|jpeg|png|gif|JPEG|GIF|PNG|JPG",
					});
				});
				
				$('[name="physical_name"]').each(function()
				{
					$(this).rules('add',
					{
						required: true,
						equalToImage : true
					});
				});
		if(status_img_check != "")	
		{	
			$('[name="btnImage"]').each(function(){
				 $(this).rules('remove', 'required');
			});
			$('[name="physical_name"]').each(function()
				{
					$(this).rules('remove', 'required');
					$(this).rules('remove', 'equalToImage');
				});
		}
		 
		 
				
		 tag_validate();
		 CheckSectionName();
			if($("#video_audio_upload").valid() && tag_validate()) 
			{
				var confirm_status = confirm("Are you sure you want to save the details and  send for approval?");
				if(confirm_status==true)
				{
					CKEDITOR.instances.txtTitle.updateElement();
					$("#hidden_status").val('A');
					$("#ddSection").attr('disabled',false);
					$("#video_audio_upload").submit();
				}
			}
			else
			{
				Flash_message("Some validation error exist. Please check all fields","SessionError");
				$("#main_content>ul>li.selected").removeClass("selected");
				$("#content_div").addClass('selected');
				$("#view1").show();
				$("#view2").hide();
			}
	});
	
	$("#btnUnpublish").click(function()
	{
		$("#tag_error").html("");
		
		var confirm_status = confirm("Are you sure you want to unpublish the "+content_type+"?");
		if(confirm_status==true)
		{
			$("#hidden_status").val('U'); 
			$("#ddSection").attr('disabled',false);
			$("#video_audio_upload").submit();
		}
	});
	
	if(status_img_check != "")
		$('.VideoImage').css('margin-top','20px');
	
	$("#btnImage").change(function(){
		$("#hidden_image_id").val("");
		display_image(this);
		$('.VideoImage').css('margin-top','20px');
		
	});
	
	$("#country_id").change(function()
	{
		   get_state_name();
	});
 
	$("#state_id").change(function()
	{
	 		get_city_name();
	});
	
	$(".show_list").click(function()
	{
		if(this.checked) 
		{
			var Content ='<ul>';
			$("#mapping_2").empty();
			$("input:checkbox[name='cbSectionMapping[]']:checked").each(function()
			{
				if($(this).attr('main_section') != '')
					Content +='<li>'+$(this).attr('main_section')+' -> '+$(this).attr('rel')+'</li>';
				else 
					Content +='<li>'+$(this).attr('rel')+'</li>';
			});
			Content +='</ul>';
			$("#mapping_1").hide();
			$("#mapping_2").html(Content);
			$("#mapping_2").show();
		}
		else
		{
			$("#mapping_1").show();
			$("#mapping_2").hide();
		}
 });
 $(document.body).on('click', '#MainSectionMapping', function(event) {
 $("#SubSection"+$(this).attr('val')).toggle();
 });
 
 
	 $("#compare_version").click(function()
	 {
			if($("input:checkbox[name='version[]']:checked").length == 2)
			{
				version_array = []
				count = 0;
				 $("input:checkbox[name='version[]']:checked").each(function() {
					version_array[count] = $(this).attr('rel');
					count++;
				 });
				 
				 if(version_array[0] != '' && version_array[1] != '')
				 {
						$("#version_content_model").html('<img style="width:40px; height:40px;" src="'+base_url+'images/admin/loadingroundimage.gif">')
						var inst = $.remodal.lookup[$('[data-remodal-id=version_model]').data('remodal')];
						if(!inst) {
							$('[data-remodal-id=version_model]').remodal().open();
						 } else{
							  inst.open();
						}
					 
						var contenttype = $(this).attr('contentType');
						var postdata = "first_contentversion_id="+version_array[0]+"&second_contentversion_id="+version_array[1]+"&contenttype="+contenttype;
						$.ajax({
								url: base_url+"niecpan/audio_video_manager/view_version_compare", // Url to which the request is send
								type: "POST",             // Type of request to be send, called as method
								data:  postdata,
								dataType: "HTML",
								success: function(data)   // A function to be called if request succeeds
								{
									$("#version_content_model").html(data);
									
									CKEDITOR.replace( 'first_version_headline',
									{
									   toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','FontSize' ] } ]
									});
									
									CKEDITOR.replace( 'second_version_headline',
									{
									   toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','FontSize' ] } ]
									});
									
									CKEDITOR.replace( 'first_version_summary', {
												 toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor' ] } ],
												// height: 150
									});
										
									CKEDITOR.replace( 'second_version_summary', {
												 toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor' ] } ],
												// height: 150
									});
								}
						});
				 }
			}
			else
			{
				Flash_message("Maximum 2 article version to compare","SessionError");
			}
	});
 
 	$("#publish_history").click(function()
	{
			$("#publish_history_popup").html('<img style="width:40px; height:40px;" src="'+base_url+'images/admin/loadingroundimage.gif">')
			var inst = $.remodal.lookup[$('[data-remodal-id=publish_history_popup]').data('remodal')];
			if(!inst)
			{
				$('[data-remodal-id=publish_history_popup]').remodal().open();
			} else
			{
				  inst.open();
	 		}
			var postdata = "content_id="+$("#hiddn_fld").val();
			$.ajax({
					url: base_url+"niecpan/audio_video_manager/get_publish_history_popup", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data:  postdata,
					dataType: "HTML",
					async: false, 
					success: function(data)   // A function to be called if request succeeds
					{
						$("#publish_history_popup").html(data);
					}
			});
	});
	
	$("#ddAgency").change(function() 
	{
			$("#txtbyline").val("");
			$("#byline_ID").val("");
			if($("#ddAgency").val() != '') 
				$("#txtbyline").attr("disabled", false);
			else
				$("#txtbyline").attr("disabled", true);
	});
	
	$("#txtbyline").keyup(function()
	{
		if($("#agency_id").val() != '') 
		{
			var agency_id = $("#ddAgency").val();
			var availableTags = base_url+"niecpan/common/get_author_name/"+agency_id;
			$("#txtbyline").autocomplete({
				source: function(request, response)
				{
					$.ajax({
						  url: availableTags,
						  data: "term="+$("#txtbyline").val(),
						  dataType: "json",
						  success:  function (msg) 
						  {
							  if(msg != '')
								  response(msg);
							  else 
								  $("#byline_ID").val('');
						  }
					});
				},
				select: function( event, ui ) {
					$( "#txtbyline" ).val( ui.item.label );
					$("#byline_ID").val(ui.item.text);
					return false;
				},
			});
		}
	});
	

	$("#txtState").keyup(function()
	{
		if($("#country_id").val() != '') 
		{
					var country_id = $("#country_id").val();
					var availableTags = base_url+"niecpan/common/get_state_name/"+country_id;
					
					console.log(availableTags);
					
					$("#txtState").autocomplete({
						source: function(request, response){
							$.ajax({
								 url: availableTags,
								 data: "term="+$("#txtState").val(),
								 dataType: "json",
								 success:  function (msg) {
									
									 if(msg != '')
									response(msg);
									else 
									$("#state_id").val('');
								 }
								
							});
						},
						select: function( event, ui ) {
							$( "#txtState" ).val( ui.item.label );
							$("#state_id").val(ui.item.text);
							return false;
						},
					});
	 	}
		
	});
	
	$("#txtCity").keyup(function()
	{
		if($("#country_id").val() != '' && $("#state_id").val() != '') 
		{
					var country_id = $("#country_id").val();
					var state_id = $("#state_id").val();
					var availableTags = base_url+"niecpan/common/get_city_name/"+country_id+"/"+state_id;
					
					console.log(availableTags);
					
					$("#txtCity").autocomplete({
						source: function(request, response){
							$.ajax({
								 url: availableTags,
								 data: "term="+$("#txtCity").val(),
								 dataType: "json",
								 success:  function (msg) {
									
									 if(msg != '')
									response(msg);
									else 
									$("#city_id").val('');
								 }
								
							});
						},
						select: function( event, ui ) {
							$( "#txtCity" ).val( ui.item.label );
							$("#city_id").val(ui.item.text);
							return false;
						},
					});
	 	}
		
	});
	
	
	var url_title = true;
	var meta_title = true;
	$('#txtUrlTitle').keyup(function(event){
		url_title = false;
	});
	
	if($('#txtUrlTitle').val()!=="")
		url_title = false;
	if($('#txtMetaTitle').val()!=="")
		meta_title = false;
		
	$('#txtMetaTitle').keypress(function(){
		meta_title = false;
	});
			
	var headline = CKEDITOR.instances.txtTitle;
	headline.on('contentDom', function() {
		  headline.document.on('keyup', function(event) {
			
				var decoded_headline = $("<div/>").html(headline.getData()).text();
				
				Remain_letters = $("#txtMetaTitle").attr('maxlength') - decoded_headline.length;
				
				if(url_title == true)
					$('#txtUrlTitle').val(decoded_headline);
					
				if(meta_title == true)
				{
					$('#txtMetaTitle').val(decoded_headline);	
					var cs = $('#txtMetaTitle').val().length;
					$('#charNum1').text(Remain_letters);
				}
	
			 });
		});




	$('#txtMetaTitle').keyup(function() {
		var Total_Length = $("#txtMetaTitle").attr('maxlength');
		MetaTitle_Count = $(this).val().length;
		if(MetaTitle_Count > Total_Length) 
			Remain_Count  = 0;
		else 
			Remain_Count  = Total_Length - MetaTitle_Count;
		
		$('#charNum1').text(Remain_Count);
	});

	
	$('#txtMetaDescription').keyup(function() {
		var Total_Length = $("#txtMetaDescription").attr('maxlength');
		MetaTitle_Count = $(this).val().length;
		
		if(MetaTitle_Count > Total_Length) 
			Remain_Count  = 0;
		else 
			Remain_Count  = Total_Length - MetaTitle_Count;
		
		$('#charNum2').text(Remain_Count);
	});
	
	
}); //document.ready ends



function byline_hide_show()
{
	  if($("#ddAgency").val() != '') {
		 /* $("#byline_row").show();
		  $("#txtbyline").show();
		  $("#byline_error").show();*/
		  $("#txtbyline").attr("disabled", false);
	  }
	  else
	  {
		 /* $("#byline_row").hide();
		  $("#txtbyline").hide();
		  $("#byline_error").hide();*/
		  $("#txtbyline").attr("disabled", true);
	  }
}
  
function get_state_name()
{
	if($("#country_id").val() != '')
		  {
			   var postdata = "country_id="+$("#country_id").val();
			   $.ajax({
			   url: base_url+"niecpan/common/get_state", // Url to which the request is send
			   type: "POST",             // Type of request to be send, called as method
			   data:  postdata,
			   dataType: "json",
			   success: function(data)   // A function to be called if request succeeds
			   		{
						var OptionValue = '<option value="" >Select</option>'
						$.each(data, function(i, item)
						{
						   OptionValue += '<option value='+ data[i].State_Id + '>'+ data[i].StateName +'</option>';
						});
						$("#state_id").html(OptionValue);
						$("#city_id").html('<option value="">Select</option>');
			 		}
			  });
		 }			 
}

function get_city_name()
{
	 if($("#country_id").val() != '' && $("#state_id").val() != '' )
	  {
			var postdata = "country_id="+$("#country_id").val()+"&state_id="+$("#state_id").val();
			$.ajax({
			url: base_url+"niecpan/common/get_city", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			dataType: "json",
			success: function(data)   // A function to be called if request succeeds
				{
					var OptionValue = '<option value="">Select</option>'
					$.each(data, function(i, item)
					{
						OptionValue += '<option value='+ data[i].City_id +'>'+ data[i].CityName +'</option>';
					});
					$("#city_id").html(OptionValue);
				}
			});
	 }
}


function iframe_script()
{
	var get_script = document.getElementById("txtScript").value;
	if(get_script != '')
	{
		document.getElementById("play_video_div").innerHTML = get_script;
	}
	else
	{
		document.getElementById("play_video_div").innerHTML = "Please enter a valid iframe script";
	}
}
	
function display_image(input)
{

	var image_old = $("#preview_image").attr('src');
	$("#preview_image").show();
	if (input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function (e)
		{
			physical = input.files[0].name;
			physical_array = physical.split('.');
			
			physical_name = physical_array[0];
			physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
			
			$('#preview_image').attr('src', e.target.result);
			$("#physical_name").val(physical_name);
			$("#physical_name").attr('physical_extension',physical_array[1]);
			$("#physical_name").attr('rel','');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function version_publish(content_id,contentversion_id) 
{
	if(content_id != '' && contentversion_id != '')
	{
		var postdata = "content_id="+content_id+"&contentversion_id="+contentversion_id;
		$.ajax({
				url: base_url+"niecpan/common/version_publish", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				success: function(data)   // A function to be called if request succeeds
				{
					console.log(data.previous_version_id);
					
					if(contentversion_id == data.previous_version_id)
					{
						$("#version_publish_"+contentversion_id).hide();
					}
					else
					{
						$("#version_publish_"+contentversion_id).hide();
						$("#version_publish_"+data.previous_version_id).show();
					}
						display_status("Published",data.publish_date);
						
						if(contentversion_id == data.previous_version_id)
						{
							$("#btnUnpublish").show();
						}
						
						
				}
		});
	}
}

$(document.body).on('click', '#view_version' ,function()
{  
	  var contentversion_id = $(this).attr('rel');
	  var contenttype = $(this).attr('contentType');
	  $("#version_content_model").html('<img style="width:40px; height:40px;" src="'+base_url+'images/admin/loadingroundimage.gif">')
	  
	  var inst = $.remodal.lookup[$('[data-remodal-id=version_model]').data('remodal')];
	  if(!inst)
	  {
		  $('[data-remodal-id=version_model]').remodal().open();
	  }
	  else
	  {
		  inst.open();
	  }
  
	  if(contentversion_id != '')
	  {
		  $.ajax({
			  url: base_url+"niecpan/audio_video_manager/view_version", // Url to which the request is send
			  type: "POST",             // Type of request to be send, called as method
			  data:  {"contentversion_id":contentversion_id, "contenttype":contenttype},
			  dataType: "HTML",
			  success: function(data)   // A function to be called if request succeeds
			  {
				  $("#version_content_model").html(data);
				  
				  CKEDITOR.replace( 'current_version_summary', {
						toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor'] } ],
						//height:150
					});
	
				  CKEDITOR.replace( 'current_version_headline',
				  {
						  toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','FontSize' ] } ]
				  });
			  }
		  });
	  }
});



