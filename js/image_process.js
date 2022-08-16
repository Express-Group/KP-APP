// JavaScript Document
$(document).ready(function() {
	
	$(document.body).on("blur","#image_alt_id",function() {
		var image_temp_id = $(this).attr('rel');
		var alt_tag		  = $.trim($(this).val());	
		
		 if($.trim(SelectImage[image_temp_id]['image_alt']) != alt_tag) {
			 SelectImage[image_temp_id]['image_alt'] = alt_tag;
			 $("#image_data").val(JSON.stringify(SelectImage));
		 }
	});
	
	$(document.body).on("blur","#image_caption_id",function() {
		var image_temp_id = $(this).attr('rel');
		var alt_tag		  = $.trim($(this).val());	
		
		 if($.trim(SelectImage[image_temp_id]['image_caption']) != alt_tag) {
			 SelectImage[image_temp_id]['image_caption'] = alt_tag;
			  $("#image_data").val(JSON.stringify(SelectImage));
		 }
	});
	
	$(document.body).on("blur input","#physical_name",function() {
		var image_temp_id = $(this).attr('rel');
		var physical_name		  = $.trim($(this).val());	
		
		physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
		
		$(this).val(physical_name);
		
		 if( $.trim(SelectImage[image_temp_id]['physical_name']) != physical_name) {
			 SelectImage[image_temp_id]['physical_name'] = physical_name;
			 $("#image_data").val(JSON.stringify(SelectImage));
		 }
		
		CheckImageName();
	});
	
	
	$(document.body).on('click',"#edit_image", function(){
		$image_id = $(this).attr('rel');
		if($image_id != '')
		window.open(base_url+folder_name+"/image_processing/"+encodeURIComponent(base64_encode($image_id)), '_blank');
		else 
		alert("Invalid Image process");
		
	});
	

	 		$("#image_data").val(JSON.stringify(SelectImage));
	
	  		$("#drop-area").on('dragenter', function (e){
			e.preventDefault();
			$(this).css('background', '#BBBBBB');
			});
			
			$("#drop-area").on('dragover', function (e){
			e.preventDefault();
			});
			
			$("#drop-area").on('drop', function (e)
			{	
			
			$(this).css('background', '#D1D1D1');
			e.preventDefault();
			var image = e.originalEvent.dataTransfer.files;
			$("#LoadingSelectImageLocal").show();
			setTimeout(function(){
				createFormData(image);
			},1000);
			
		
			});	
			
			$("#add_to_edit").click(function() {
				var inst = $.remodal.lookup[$('[data-remodal-id=modal1]').data('remodal')];
					if(!inst) {
						$('[data-remodal-id=modal1]').remodal().close();
					 } else{
						  inst.close();
	 				}
			});
			

$(document.body).on('click', '#ImageRemove', function(event) {
	
	var ImageDetails 	= $(this).data();
	var ImageIndex 		= ImageDetails.image_id;
	
	var ImageData = 'image_id='+ImageIndex;
	
		$.ajax({
			url: base_url+folder_name+"/image/delete_temp_image",
			type: "POST",
			data: ImageData,
			dataType: "json",
			async: false, 
			success: function(data) {
					delete SelectImage[ImageIndex];
					$("#SetImage"+ImageIndex).remove();
					$("#image_data").val(JSON.stringify(SelectImage));
					$("#gallery_image"+ImageIndex).remove();
			}
			
			
		});
			if(Object.keys(SelectImage).length == 0) 
				$("#crop_container").hide();
	
});

$(document.body).on('click', '#deletetempimage', function(event) {
	
	var confirm_status = confirm('Are you sure delete this image from image?');
	if(confirm_status==true) 
	{
		var ImageIndex 		= $(this).attr('rel');
		var ImageData 	= 'image_id='+$(this).attr('rel');
		
			$.ajax({
				url: base_url+folder_name+"/image/delete_temp_image",
				type: "POST",
				data: ImageData,
				dataType: "json",
				async: false, 
				success: function(data) {
						delete SelectImage[ImageIndex];
						$("#SetImage"+ImageIndex).remove();
						$("#image_data").val(JSON.stringify(SelectImage));
						$("#gallery_image"+ImageIndex).remove();
				}
				
				
			});
			
			if(Object.keys(SelectImage).length == 0) 
				$("#crop_container").hide();
	}
});
	
		$("#addimagelibrary").change(function() {
			
			$("#LoadingSelectImageLocal").show();
			
				$("#ImageForm").ajaxForm({
				async: true, 
				dataType: "json",
				beforeSubmit:function(){
					
				},
				success:function(data){
					
					$("#addimagelibrary").val('');
					
				 $.each(data,function(key,value){
					 
					 	physical_name = data[key].physical_name;
						physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
						
						if(folder_name != 'niecpan') 
							data[key].caption = '';
						
					  var display_order = parseInt(Object.keys(SelectImage).length)+1
					  SelectImage[data[key].image_id] = {
						  'image_id' 			: data[key].image_id,
						  'image_caption' 		: data[key].caption,
						  'image_alt' 			: data[key].alt_tag,
						  'physical_name'		: physical_name,
						  'physical_extension' 	: data[key].physical_extension,
						  'image_content_id' 	: '',
						 'image1_type' 			: data[key].image1_type,
						  'image2_type' 		: data[key].image2_type,
						  'image3_type' 		: data[key].image3_type,
						  'image4_type' 		: data[key].image4_type
						};
						  
						   if(display_order%2 != 0)
							var Class = 'class = "odd" role="row"';
						 else 
							var Class = 'class = "even" role="row"';
						  
						  	$("#ImageContainerSet").append('<div class="ImageClose" id="SetImage'+data[key].image_id+'"><img src="'+data[key].image+'" /><img  id="ImageRemove" data-image_id="'+data[key].image_id+'" src="'+image_url+'images/admin/close-button.png" /></div>');
						  
							var	Content = "<tr "+Class+" id='gallery_image"+data[key].image_id+"'><td><img class='gallery_dataimage' src='"+data[key].image+"'/></td><td><div align='center'><input type='textbox' style='width:200px; text-align:center;'  name='image_caption' id='image_caption_id' rel='"+data[key].image_id+"' value='' /></div></td><td><div align='center'><input type='textbox' style='width:200px; text-align:center;'  name='image_alt' id='image_alt_id' rel='"+data[key].image_id+"' value='' /></div></td><td><div align='center'><input type='textbox' style='width:100%; text-align:center;' physical_extension='"+data[key].physical_extension+"'   rel='"+data[key].image_id+"'    name='physical_name' id='physical_name' rel='"+data[key].image_id+"' value='"+physical_name+"' /></div><span class='error' id='error_"+data[key].image_id+"'></span></td><td><div class='article_table_delete'>  <a  id='edit_image' rel='"+data[key].image_id+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' id='deletetempimage' rel='"+data[key].image_id+"' ><i class='fa fa-trash-o'></i></a></div></td></tr>";
					
							$("#link_preview_body").append(Content);
							$("#crop_container").show();
						   
						  $("#image_data").val(JSON.stringify(SelectImage));
						  $("#LoadingSelectImageLocal").hide();
						  
					});
					
			
					
				},
				error:function(){
					
				} }).submit(); 
			
		});

	$("#active_id").click(function() {
		 
				$("#status_id").val(1);
				var total_count  = $("#image_check").val();
				
					if(CheckImageName() == false) 
					return false;	
				
							if($("#image_data").val() != '{}' && $("#image_data").val() != '') { 
								
									if(total_count == 0)
										var confirm_status = confirm('Are you sure you want to save the details and Active the images?');
									else 
										var confirm_status = confirm('Already use this image in other contents, Are you sure you want to Active the images?');
								
								if(confirm_status==true) {
									$("#imagelibrary").submit();
								} else {
									return false;
								}
							} else {
							  Flash_message("Please add atleast one image","SessionError");
							  return false;
							}
						
	 });
	 
	 	$("#inactive_id").click(function() {
		 
				$("#status_id").val(0);
				var total_count  = $("#image_check").val();
		 
				if(CheckImageName() == false)
				return false;	
		 
							
							if($("#image_data").val() != '{}' && $("#image_data").val() != '') { 
								if(total_count == 0)
									var confirm_status = confirm('Are you sure you want to save the details and Inactive the images?');
								else 
									var confirm_status = confirm('Already use this image in other contents, Are you sure you want to Inactive the images?');
								if(confirm_status==true) {
									$("#imagelibrary").submit();
								} else {
									return false;
								}
							} else {
							  Flash_message("Please add atleast one image","SessionError");
							  return false;
							}
						
	 });

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
			
 });
 
 function CheckImageName() {
	
	bool = true;

		$('[id^="physical_name"]').each(function(){
                    if ($(this).val().length>0){
						
						temp_id = $(this).attr('rel');
						image_caption = $("#image_caption_id").val();
						image_alt = $("#image_alt_id").val();
						
			postdata = "physical_name="+$(this).val()+'.'+$(this).attr('physical_extension')+"&temp_id="+$(this).attr('rel')+"&caption="+image_caption+"&alt="+image_alt;
			$.ajax({
				url: base_url+folder_name+"/common/check_image_name", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					if(data.status == 'false') {
						$("#error_"+temp_id).html('Image name already exists');
						bool = false;
					} else {
						$("#error_"+temp_id).html('');
					}
				}
			});
                        
					}
		}); 
		
		var inputs = $('[id^="physical_name"]');

			
inputs.filter(function(i,el){
     inputs.not(this).filter(function() {
		 temp_id = $(this).attr('rel');

         if(this.value === el.value) {
			 	$("#error_"+temp_id).html('Image name already exists');
		 } 
    }).length !== 0;
});
		
		return bool;
}
 
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
	
    function createFormData(image) {
        
		var Error 			= true;
		var ErrorMessage 	= '';
		var Count 			= 0;
		
		for (var i = 0; i < image.length; i++) {
			
        var formImage = new FormData(); 
        formImage.append('userImage', image[i]);
		formImage.append('content_type', 2);
		
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
					
					if(data.status == 1) {
					
						var display_order = parseInt(Object.keys(SelectImage).length)+1;
				 
					 	physical_name = data.physical_name;
						physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
						
								
						if(folder_name != 'niecpan') 
							data.caption = '';
						
					if(SelectImage[data.image_id] == undefined)  
					{
					  	  SelectImage[data.image_id] = {
						  'image_id' 			: data.image_id,
						  'image_caption' 		: data.caption,
						  'image_alt' 			: data.alt_tag,
						  'physical_name'		: physical_name,
						  'physical_extension' 	: data.physical_extension,
						  'image1_type' 		: data.image1_type,
						  'image2_type' 		: data.image2_type,
						  'image3_type' 		: data.image3_type,
						  'image4_type' 		: data.image4_type,
						  'imagecontent_id' 	: '',
						  'display_order'   	: display_order
						  };
						
						 if(display_order%2 != 0)
							var Class = 'class = "odd" role="row"';
						 else 
							var Class = 'class = "even" role="row"';
						
							$("#image_data").val(JSON.stringify(SelectImage));
						  
							$("#ImageContainerSet").append('<div class="ImageClose" id="SetImage'+data.image_id+'"><img src="'+data.image+'" /><img id="ImageRemove" data-image_id="'+data.image_id+'" src="'+image_url+'images/admin/close-button.png" /></div>'); 
						  
							var	Content = "<tr "+Class+" id='gallery_image"+data.image_id+"'><td><img class='gallery_dataimage' src='"+data.image+"'/></td><td><div align='center'><input type='textbox' style='width:200px; text-align:center;'  name='image_caption' id='image_caption_id' rel='"+data.image_id+"' value='' /></div></td><td><div align='center'><input type='textbox' style='width:200px; text-align:center;'  name='image_alt' id='image_alt_id' rel='"+data.image_id+"' value='' /></div></td><td><div align='center'><input type='textbox' style='width:100%; text-align:center;'  name='physical_name' id='physical_name' physical_extension='"+data.physical_extension+"'   rel='"+data.image_id+"'  value='"+physical_name+"' /></div><span class='error' id='error_"+data.image_id+"'></span></td><td><div class='article_table_delete'>  <a  id='edit_image' rel='"+data.image_id+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' id='deletetempimage' rel='"+data.image_id+"' ><i class='fa fa-trash-o'></i></a></div></td></tr>";
								
							$("#link_preview_body").append(Content);
							$("#crop_container").show();
			
								$('input[name^=display_order]').keypress(function (e) {
								 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {        
										   return false;
								}
							   });
							
						  								   
						  $("#image_data").val(JSON.stringify(SelectImage));
						  $("#LoadingSelectImageLocal").hide();
					} else {
						  alert("The selected image is already added");
						  $("#LoadingSelectImageLibrary").hide(); 
					}
										
				} else {
					Error = false;
					ErrorMessage 	= data.message;
					Count++;
				}
	
				}
					
			});
		 }  

		if(Error == false) {
			$("#normal_loading").hide();
			alert(Count+" File(s) Error : "+ErrorMessage);
		} else {
			$("#normal_loading").hide();
		}
	}