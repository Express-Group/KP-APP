$("#resource_physical_name").on('input',function(e) {
	var value = $("#resource_physical_name").val();
	result = value.replace(/[^a-zA-Z0-9_-]/g,'');
		
	$("#resource_physical_name").val(result);
});
	$.validator.addMethod("equalToHome", 
	function(physical_name, element, params) {
		
		
		
		var home_bool = true;
		
		if($("#resource_image_gallery_id").val() != '' && $.trim(physical_name) != '') {

		postdata = "physical_name="+physical_name+'.'+$('#resource_physical_name').attr('physical_extension')+"&temp_id="+$("#resource_image_gallery_id").val()+"&caption="+$("#resource_image_caption").val()+"&alt="+$("#resource_image_alt").val();
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
						$("#resource_physical_name").attr("readonly",false);
					} 
				}
			});
		}	
			
			return home_bool;
	
	},'This image name already exists');

	var message = '';
	
		$.validator.addMethod("ValidateResource", 
	function(temp_name, element, params) {

		return ValidateExtension();	
	
	},"Please upload files having extensions: <b> .doc, .docx, .pdf, .xlsx, .xls </b> only.");
	
	$.validator.addMethod("TitleValidation", 
	function(HeadLine, element, params) {
		Title = true;
		
		 var headline = CKEDITOR.instances.resource_head_line_id;
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
					  "resource_head_line_id" : {
							TitleValidation : true
						},
						"resource_physical_name" : {
							equalToHome : true
						},
						"hiddn_article_id" : {
						  required : true
						},
						"imgResourceId" : {
							required :true
						},
					    "ExistingResource" : {
							required :true,
						   ValidateResource: true
					    }
				},
				errorPlacement: function (error, element)
				{
				if(element.attr("name") == 'resource_head_line_id') {
					error.insertAfter($("#title_error"));
				} else if(element.attr("name") == 'imgResourceId') { 
					error.insertAfter($("#image_error"));
				} else if(element.attr("id") == 'ExistingResource') { 
					$("#lblError").html(error.text());
				} else if(element.attr("id") == "hiddn_article_id") {
					error.insertAfter($("#link_article_error"));
				}else {
					error.insertAfter(element);
				}
			}
	 });
	 
	 $("#send_drafttop_id").click(function() {
		
		CKEDITOR.instances.resource_head_line_id.updateElement();
		
		$("#status_id").val('D');
		
		$('[name="hiddn_article_id"], [name="imgResourceId"], [name="ExistingResource"]').each(function () {
           $(this).rules('remove');
         });
		
		 ValidForm();
		
	});
	
	 
	 	$("#publishtop_id").click(function() {
		
		CKEDITOR.instances.resource_head_line_id.updateElement();
		
		$("#status_id").val('P');
		
		$('[name="hiddn_article_id"], [name="imgResourceId"], [name="ExistingResource"]').each(function () {
            $(this).rules('add', {
                required: true
			});
			$('[name="ExistingResource"]').rules('add',{
				 ValidateResource: true
			});
         });
		
		 ValidForm();
		
	});
	
	
		$("#unpublishtop_id").click(function() {
		
		CKEDITOR.instances.resource_head_line_id.updateElement();
		
		$("#status_id").val('U');
		
		$('[name="hiddn_article_id"], [name="imgResourceId"], [name="ExistingResource"]').each(function () {
            $(this).rules('add', {
                required: true
			});
			$('[name="ExistingResource"]').rules('add',{
				 ValidateResource: true
			});
         });
		
		 ValidForm();
		
	});
	
function ValidForm() {
	
		$("#resource_physical_name").attr("readonly",false);
	
		if($("#content_form").valid()) {
			
			switch($("#status_id").val()) {
				case 'D':
					status_value = 'Are you sure you want to save the details in Draft status?';
					break;
				case 'P':
					status_value = 'Are you sure you want to save the details and Publish the resource?';
					break;
				case 'U':
					status_value = 'Are you sure you want to save the details and unpublish the resource?';
					break;
				default:
					status_value = 'Are you sure you want to save the details and Publish the resource?';
			}
						
				var confirm_status = confirm(status_value);
					if(confirm_status==true)
					{
						$("#loading_msg").html("Please wait, Processing your inputs in resources");
						$("#commom_loading").show();
						
						$("#content_form").submit();
					}
				
		}
	
	
}
	 
	// Form Validation

	// LINK ARTICLE POPUP
	/*
	related_datatables();
	
	$("#article_search_id").click(related_datatables);
	
	
function related_datatables()
{
	var Section 		= $("#article_section").val();
	var Search_text 	= $("#search_text").val();
	var SearchBy		= '';
	var check_in		= $("#date_timepicker_start").val();
	var check_out   	= $("#date_timepicker_end").val();
	var article_Type 	= '1';
	var Status			= 'P';
	
    $('#example').dataTable({
		
		oLanguage: {
        sProcessing: "<img src='"+image_url+"images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },
	
        "processing": true,
		 "autoWidth": false,
        "bServerSide": true,
		"bDestroy": true,
		"searching": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"order": [[2, "desc"]],
		
		"fnDrawCallback":function(oSettings){  
			 $("#example_length").show();
		   
		   if($(this).find('tbody tr').text()== "No matching records found") {
			 $(oSettings.nTHead).hide(); 
			 $('.dataTables_info').hide();
     		} else {
      		$(oSettings.nTHead).show(); 
     		}
		},
		
		"ajax": {
            "url": base_url+folder_name+"/breaking_news_manager/search_internal_article",
			"type" : "POST",
			"data" : {
		 "Search_by" : SearchBy, "Section" : Section, "Search_text" : Search_text, "check_in" : check_in, "check_out" : check_out, "Status" : Status, "article_Type" : article_Type, "content_id" : ''}
		 }
    });
		
}
*/

	
	
	// LINK ARTICLE POPUP
	
	// CKEDITOR FUNCTIONALITY
	if(folder_name == 'niecpan') {
		CKEDITOR.replace( 'resource_head_line_id',
    {
        toolbar : [ { name: 'basicstyles', items: [] } ],
		  height:25,
		 // extraPlugins: 'charcount', 
		  MaxLength: 100,
		   forcePasteAsPlainText :true
				
    });
	} else {
		CKEDITOR.replace( 'resource_head_line_id',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor' ] } ],
		  height:25,
		//  extraPlugins: 'charcount', 
		  MaxLength: 100,
		   forcePasteAsPlainText :true
				
    });
	}
	
	var url_title = true;
	
	 if($('#txtUrlTitle').val()!=="")
	url_title = false;
	
	var headline = CKEDITOR.instances.resource_head_line_id;
 headline.on('contentDom', function() {
    headline.document.on('keyup', function(event) {
		
    var decoded_headline = $.trim($("<div/>").html(headline.getData()).text());
	
	decoded_headline = decoded_headline.substring(0,100);
	

	
   // if(url_title == true)
     $('#txtUrlTitle').val(decoded_headline);
    
    });
  });
	
	// CKEDITOR FUNCTIONALITY

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
						
							$('#resource_image_gallery_id').val(data.image_id);
				$('#resource_image_gallery_id').attr('rel',data.imagecontent_id);
				$("#resource_image_set").html('Change Image');
				$("#resource_image_set").removeClass('BorderRadius3');
				$('#resource_image_src').attr('src',data.source);
				$('#resource_image_container').show();
				$("#resource_image_set").next().show();
				$("#resource_image_set").next().next().show();
				$("#resource_uploaded_image").html('Image Set');
				
				if(folder_name == 'niecpan')
					$("#resource_image_caption").val(data.caption);
				else
					$("#resource_image_caption").val('');
				
				$("#resource_image_alt").val(data.alt);
				
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
				
				$("#resource_physical_name").val(physical_name);
				
				$("#resource_physical_name").attr('physical_extension',data.physical_extension);
				
				$("#resource_physical_name").attr("readonly",true);
				
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
			formImage.append('content_type', 6);
			
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
				
				$('#resource_image_gallery_id').val(data.image_id);
				$('#resource_image_gallery_id').attr('rel',data.imagecontent_id);
				$("#resource_image_set").html('Change Image');
				$("#resource_image_set").removeClass('BorderRadius3');
				$('#resource_image_src').attr('src',data.image);
				$('#resource_image_container').show();
				$("#resource_image_set").next().show();
				$("#resource_image_set").next().next().show();
				$("#resource_uploaded_image").html('Image Set');
								
				//$("#resource_image_caption").val(data.caption);
				//$("#resource_image_alt").val(data.alt_tag);
				
				$("#resource_image_caption").val('');
				$("#resource_image_alt").val('');
				
				var physical_name = data.physical_name;
				physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
				
				$("#resource_physical_name").val(physical_name);
				
				$("#resource_physical_name").attr('physical_extension',data.physical_extension);
				
				$("#LoadingSelectImageLocal").hide();
				
				var inst = $.remodal.lookup[$('[data-remodal-id=imagemodal]').data('remodal')];
				if(!inst) {
					$('[data-remodal-id=imagemodal]').remodal().close();
				 } else{
                      inst.close();
                  }
				$("#imagelibrary").val('');
			}
		});
	
}

$(document.body).on('click', '#delete_resource_image' ,function(){
		var image_type = $(this).attr('rel');
		var ImageIndex = '';
		
		ImageIndex = $("#resource_image_gallery_id").val();
		
		
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
						$("#resource_image_set").html('Set Image');
						$("#resource_image_set").addClass('BorderRadius3');
						$("#resource_image_gallery_id").val('');
						$("#resource_image_gallery_id").attr('rel','');
						$("#resource_image_src").attr('src','');
						$('#resource_image_container').hide();
						$("#resource_image_set").next().hide();
						$("#resource_image_set").next().next().hide();
						$("#resource_physical_name").val('');
						$("#resource_physical_name").attr('physical_extension','');
						$("#resource_image_caption").val('');
						$("#resource_image_alt").val('');
					
				}
			});

			}
			
		} else {
				alert("Invalid Actions");
		}	

	});
	
	$(document.body).on('click', '#edit_resource_image' ,function(){
		var image_type = $(this).attr('rel');
	
		ImageIndex = $("#resource_image_gallery_id").val();
		
		if(ImageIndex != '') {
			window.open(base_url+folder_name+"/resource_image_processing/"+encodeURIComponent(base64_encode(ImageIndex)), '_blank');
		}
		else  {
			alert("Invalid Action for Image Processing");
		}
		
	});
	
	 // Image upload popup
	 
	 function get_content_id(id, title)
{
	var get_title = title.replace(/_/g, " ");
	
	var confirm_box = confirm("Are you sure you want to link this article - "+get_title+"");
	if(confirm_box == true)
	{
		var inst = $.remodal.lookup[$('[data-remodal-id=modal1]').data('remodal')];
		inst.close();
	}
	$("#hiddn_article_id").val(id);
	$("#article_title_div").show();
	$("#get_article_title").html(get_title);
	$("#hiddn_article_title").val(get_title);
	$("#btnunlink_article").show();
}

	$("#btnunlink_article").click(function()
	{
		var cnfrm_box = confirm("Are you sure you want to unlink this article");
		if(cnfrm_box==true)
		{
			$("#hiddn_article_id").val('');
			$("#article_title_div").hide();
			$("#btnunlink_article").hide();
		}
	});
	
	if($("#hiddn_article_id").val() != "")
	{
		$("#btnunlink_article").show();
	}
	
	
	$("#btnResource").change(function(){
	ValidateExtension();	
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
		
              	var allowedFiles = ["doc", "docx", "pdf", "xlsx", "xls","ppt","pptx"];
        var fileUpload = document.getElementById("btnResource");
		
		if(fileUpload.value.toLowerCase() != '')
		$("#ExistingResource").val(fileUpload.value.toLowerCase());
        var lblError = document.getElementById("lblError");
		$Source = $("#ExistingResource").val();
		var last = $Source.substring($Source.lastIndexOf("\\") + 1, $Source.length);
		
		 var fileNameExt = $Source.substr($Source.lastIndexOf('.') + 1);
		$("#SourceName").html($Source);
       if ($.inArray(fileNameExt, allowedFiles) == -1){
            lblError.innerHTML = "Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.";
				$("#ResourceURL").val('');
            return false;
        }
        lblError.innerHTML = "";
        return true;
    }

	
// Resource format validation 