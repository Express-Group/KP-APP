// JavaScript Document
$(document).ready(function()
{
	
var option = $('option:selected', '#main_section_id').attr('url_structure');

if(option  != undefined)
	$("#mainsection_breadcrumb").html("Bread Crumb : "+option);
else 
	$("#mainsection_breadcrumb").empty();


$('#main_section_id').change(function(){
	
	//alert("vairam");
	
	var option = $('option:selected', '#main_section_id').attr('url_structure');
	
		if($.trim($( "#main_section_id option:selected" ).text()) == 'Nation') {
			
			$('#country_id option:contains("India")').prop('selected', true);
			
			$('#txtState').val('');
			$('#state_id').val('');
			
			$('#txtCity').val('');
			$('#city_id').val('');
			
		}
		
	

if(option  != undefined)
	$("#mainsection_breadcrumb").html("Bread Crumb : "+option);
else 
	$("#mainsection_breadcrumb").empty();
});
	
$("#section_href").click(function(){
	multiple_section_mapping();
});
	
/*
*
* Get the multiple section mapping 
* @access Public
* @param content_id, content type
* @return HTML format from multiple_section_mapping view file
*
*/


function multiple_section_mapping_before_submit() {
		if($.trim($("#mapping_1").html()) == '') {
	
		var postdata = "content_id="+$("#content_id").val()+"&content_type=3&archive_year="+$("#archive_year").val();
			$.ajax({
					url: base_url+folder_name+"/common/multiple_section_mapping", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data:  postdata,
					sync: false,
					dataType: "HTML",
					success: function(data)   // A function to be called if request succeeds
					{
						$("#mapping_1").html(data);
						$("#imagegallery").submit();
					}
			});	
			
	} else {
		$("#imagegallery").submit();
	}
}

function multiple_section_mapping() {
		if($.trim($("#mapping_1").html()) == '') {
		
		$("#loading_msg").html("Please wait, Loading the multiple section mapping");
		$("#commom_loading").show();
	
		var postdata = "content_id="+$("#content_id").val()+"&content_type=3&archive_year="+$("#archive_year").val();
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


	
	$(document.body).on("blur","#image_alt_id",function() {
		var Index 			= $(this).attr('rel');
		var alt_tag		  	= $.trim($(this).val());	
		
		 if($.trim(SelectImage[Index]['image_alt']) != alt_tag) {
			 SelectImage[Index]['image_alt'] = alt_tag;
			 $("#index"+Index).attr("data-image_alt",alt_tag);
			 $( 'textarea[name$="image_alt"][rel="'+Index+'"]' ).text(alt_tag);
			 $("#gallery_data").val(JSON.stringify(SelectImage));
		 }
	});
	
		$(document.body).on("blur","#physical_name",function() {
		var Index = $(this).attr('rel');
		var physical_name		  = $.trim($(this).val());	
		
		physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
		
		$(this).val(physical_name);
		
		 if( $.trim(SelectImage[Index]['physical_name']) != physical_name) {
			 SelectImage[Index]['physical_name'] = physical_name;
			 $("#index"+Index).attr("data-physical_name",physical_name);
			 $("#gallery_data").val(JSON.stringify(SelectImage));
		 }

		var inputs = $('[id^="physical_name"]');

			inputs.filter(function(i,el){
				 inputs.not(this).filter(function() {
					 temp_id =$(this).attr('temp_id');
					 if(this.value === el.value) {
							$("#error_"+temp_id).html('Image name already exists');
					 } 
				}).length !== 0;
			});
		
				if ($(this).val().length>0){
						temp_id = $(this).attr('temp_id');
						
						var index = $(this).attr('rel');
						
						image_caption = $("#index"+index).attr("data-image_caption");
						image_alt = $("#index"+index).attr("data-image_alt");
						
						
			postdata = "physical_name="+$(this).val()+'.'+$(this).attr('physical_extension')+"&temp_id="+temp_id+"&caption="+image_caption+"&alt="+image_alt;
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
					} else {
						$("#error_"+temp_id).html('');
					}
				}
			});
                        
					}
	});
	
	
	$(document.body).on("blur","#image_caption_id",function() {
		var Index = $(this).attr('rel');
		var caption		  = $.trim($(this).val());	
		
		 if($.trim(SelectImage[Index]['image_caption']) != caption) {
			 SelectImage[Index]['image_caption'] = caption;
			  $("#index"+Index).attr("data-image_caption",caption);
			  //$( "textarea[rel=]" ).find('#image_caption_id').text(caption);
			  $( 'textarea[name$="image_caption"][rel="'+Index+'"]' ).text(caption);

			  $("#gallery_data").val(JSON.stringify(SelectImage));
		 }
	});

	
	$(document.body).on('change',"#display_order",function(){
		$image_id = $(this).attr('rel');
		display_order = $(this).val();
		SelectImage[$image_id]['display_order'] 			= display_order;
		 $("#gallery_data").val(JSON.stringify(SelectImage));
	});
	
	$(document.body).on('click',"#edit_image", function(){
		$image_id = $(this).attr('rel');
		if($image_id != '')
		window.open(base_url+folder_name+"/gallery_image_processing/"+encodeURIComponent(base64_encode($image_id)), '_blank');
		else 
		alert("Invalid Image process");
		
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

$('#count2').keyup(function() {
	var Total_Length = $("#count2").attr('maxlength');
    MetaTitle_Count = $(this).val().length;
	
	if(MetaTitle_Count > Total_Length) 
	Remain_Count  = 0;
	else 
	Remain_Count  = Total_Length - MetaTitle_Count;
	
	$('#charNum2').text(Remain_Count);
});
	
	$("#txtMetaTitle").blur(function() {
	$MetaTitle = $.trim($("#txtMetaTitle").val());
	
	if($MetaTitle != '') {
		
	var postdata = "metatitle="+$MetaTitle;
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
			  var content = '<li class="tagedit-listelement tagedit-listelement-old"><span dir="ltr">'+TagDetails.value+'</span><input type="hidden" value="'+TagDetails.value+'" name="txtTags['+TagDetails.id+'-a]"><a title="Remove from list." class="tagedit-close">x</a></li>';
		$(".tagedit-listelement-new").last().before(content);
		
		 }
		 
	 });
	
	/* Section Show & Hide Functionality */
	
	 $("input:checkbox[name='cbSectionMapping[]']").each(function() {
		 $(this).css("visibility", "visible");
	 });
	
	$("#section_mapping"+$("#ddMainSection").val()).prop('checked', false);
	$("#section_mapping"+$("#ddMainSection").val()).css("visibility", "hidden");
	
	
	$("#ddMainSection").change(function(){
	
		 $("input:checkbox[name='cbSectionMapping[]']").each(function() {
			 $(this).css("visibility", "visible");
		 });
		
		$("#section_mapping"+$("#ddMainSection").val()).prop('checked', false);
		$("#section_mapping"+$("#ddMainSection").val()).css("visibility", "hidden");
			
	});
		
	/* Section Show & Hide Functionality */
	if(folder_name == 'niecpan') {
			CKEDITOR.replace( 'txtgalleryname',
			{
				toolbar : [ { name: 'basicstyles', items: [ ] } ],
			 // extraPlugins: 'charcount', 
				MaxLength: 100,
				height : 100,
				 forcePasteAsPlainText :true
			});
	} else {
		CKEDITOR.replace( 'txtgalleryname',
			{
				toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor' ] } ],
			//  extraPlugins: 'charcount', 
				MaxLength: 100,
				height : 100,
				 forcePasteAsPlainText :true
			});
	}		
	
			 CKEDITOR.replace( 'txtSummary', {
				  toolbar : [ {  items: [ 'Bold', 'Italic', 'TextColor'] } ],
				   height:100,
					extraPlugins: 'charcount', 
				  MaxLength: 2000,
				 forcePasteAsPlainText :true
			});
	
	
	 		$("#gallery_data").val(JSON.stringify(SelectImage));
	
	  		$("#drop-area").on('dragenter', function (e){
			e.preventDefault();
			$(this).css('background', '#BBBBBB');
			});
			
			$("#drop-area").on('dragover', function (e){
			e.preventDefault();
			});
			
			$("#drop-area").on('drop', function (e)
			{	
			$("#LoadingSelectImageLocal").show();
			$(this).css('background', '#D1D1D1');
			e.preventDefault();
			var image = e.originalEvent.dataTransfer.files;
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
			
		
			
	
$(document.body).on('click', '#image_lists_images_id', function(event) {
	
	$("#LoadingSelectImageLibrary").show();
	
	if($(this).data('image_source')) {
		
		var ImageDetails = $(this).data();
	
		ImageData = "alt="+ImageDetails.image_alt+"&caption="+ImageDetails.image_caption+"&date="+ImageDetails.image_date+"&height="+ImageDetails.image_height+"&width="+ImageDetails.image_width+"&size="+ImageDetails.image_size+"&path="+ImageDetails.image_path+"&content_id="+ImageDetails.content_id;
	 
			$.ajax({
			url		: base_url+folder_name+"/gallery/Insert_temp_from_image_library",
			type	: "POST",
			data	: ImageData,
			dataType: "json",
			async	: false, 	
			success	: function(data) {
			
			var Index = parseInt(Object.keys(SelectImage).length);
			
			var Bool = true;
			
			for(Count = 0; Count < Index ; Count++) {
				var SetImageDetails = SelectImage[Count];
				if(SetImageDetails.imagecontent_id == ImageDetails.content_id) 
					Bool = false;
			}
			
					if(Bool)  
					{
						
						physical_name = data.physical_name;
						physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
						
					  	  SelectImage[Index] = {
						  'image_id' 			: data.image_id,
						  'image_caption' 		: data.caption,
						  'image_alt' 			: data.alt,
						  'physical_name'		: physical_name,
						  'source'				: data.source,
						  'physical_extension' 	: data.physical_extension,
						  'image1_type' 		: data.image1_type,
						  'image2_type' 		: data.image2_type,
						  'image3_type' 		: data.image3_type,
						  'image4_type' 		: data.image4_type,
						  'imagecontent_id' 	: ImageDetails.content_id
						  };
						
						 if(parseInt(Index+1)%2 != 0)
							var Class = 'class = "odd" role="row"';
						 else 
							var Class = 'class = "even" role="row"';
						
							$("#gallery_data").val(JSON.stringify(SelectImage));
						  
							$("#ImageContainerSet").append('<div class="ImageClose" id="SetImage'+data.image_id+'"><img src="'+data.source+'" /><img id="ImageRemove" data-image_id="'+data.image_id+'" src="'+base_url+'images/admin/close-button.png" /></div>'); 
						  
							var	Content = "<tr  "+Class+" id='gallery_image"+data.image_id+"'><td id='index"+Index+"' class='index' data-image_id='"+data.image_id+"' data-image_caption='"+data.caption+"' data-image_alt='"+data.alt+"' data-physical_name='"+data.physical_name+"' data-physical_extension='"+data.physical_extension+"' data-image1_type='"+data.image1_type+"'  data-image2_type='"+data.image2_type+"'  data-image3_type='"+data.image3_type+"'   data-image4_type='"+data.image4_type+"'   data-imagecontent_id='"+ ImageDetails.content_id+"' data-source='"+data.source+"' >"+parseInt(Index+1)+"</td><td><img class='gallery_dataimage' src='"+data.source+"'/></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_caption' id='image_caption_id' rel='"+Index+"'  >"+data.caption+ "</textarea></div></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_alt' id='image_alt_id'  rel='"+Index+"' >"+data.alt+"</textarea></div></td><td><div align='center'><input type='textbox' style='width:100%; text-align:center;'  name='physical_name' maxlength=80 temp_id='"+data.image_id+"' id='physical_name' physical_extension='"+data.physical_extension+"'   rel='"+Index+"'  value='"+physical_name+"' /></div><span class='error' id='error_"+data.image_id+"'></span></td><td><div class='article_table_delete'>  <a  id='edit_image' rel='"+data.image_id+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' id='deletetempimage' index_value='"+Index+"' rel='"+data.image_id+"' ><i class='fa fa-trash-o'></i></a></div></td></tr>";
								
							$("#link_preview_body").append(Content);
							$("#crop_container").show();
							
						  
						  $("#LoadingSelectImageLibrary").hide(); 
					} else {
						  alert("The selected image is already added");
						  $("#LoadingSelectImageLibrary").hide(); 
					}
				}
			}); 
	}
	
});

$(document.body).on('click', '#ImageRemove', function(event) {
	
	var ImageDetails 	= $(this).data();
	var ImageIndex 		= ImageDetails.image_id;
	
	var Index 			= $(this).attr('index_value');
	
	var ImageData = 'image_id='+ImageIndex;
	
		$.ajax({
			url: base_url+folder_name+"/gallery/delete_temp_image",
			type: "POST",
			data: ImageData,
			dataType: "json",
			async: false, 
			success: function(data) {
					delete SelectImage[Index];
					$("#SetImage"+ImageIndex).remove();
					$("#gallery_data").val(JSON.stringify(SelectImage));
					$("#gallery_image"+ImageIndex).remove();
					rearrange_gallery_container();
			}
			
			
		});
			if(Object.keys(SelectImage).length == 0) 
				$("#crop_container").hide();
});
	
	$(document.body).on('click', '#deletetempimage', function(event) {
	
	var confirm_status = confirm('Are you sure delete this image from gallery?');
	if(confirm_status==true) 
	{
		var ImageIndex 		= $(this).attr('rel');
		var ImageData 	= 'image_id='+$(this).attr('rel');
		
		var Index = $(this).attr('index_value');
		
			$.ajax({
				url: base_url+folder_name+"/gallery/delete_temp_image",
				type: "POST",
				data: ImageData,
				dataType: "json",
				async: false, 
				success: function(data) {					
						delete SelectImage[Index];
						$("#SetImage"+ImageIndex).remove();
						$("#gallery_data").val(JSON.stringify(SelectImage));
						$("#gallery_image"+ImageIndex).remove();
						rearrange_gallery_container();
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
						
					  var Index = parseInt(Object.keys(SelectImage).length);
					  
					  if(folder_name != 'niecpan') 
						  data[key].caption = '';
					  
					  SelectImage[Index] = {
						  'image_id' 			: data[key].image_id,
						  'image_caption' 		: data[key].caption,
						  'image_alt' 			: data[key].alt_tag,
						  'physical_name'		: physical_name,
						  'physical_extension' 	: data[key].physical_extension,
						  'source'				: data[key].image,
						 'image1_type' 			: data[key].image1_type,
						  'image2_type' 		: data[key].image2_type,
						  'image3_type' 		: data[key].image3_type,
						  'image4_type' 		: data[key].image4_type,
						   'image_content_id' 	: ''
						};
						  
						   if(parseInt(Index+1)%2 != 0)
							var Class = 'class = "odd" role="row"';
						 else 
							var Class = 'class = "even" role="row"';
						  
						  	$("#ImageContainerSet").append('<div class="ImageClose" id="SetImage'+data[key].image_id+'"><img src="'+data[key].image+'" /><img  id="ImageRemove" data-image_id="'+data[key].image_id+'" src="'+image_url+'images/admin/close-button.png" /></div>');
						  
							var	Content = "<tr "+Class+" id='gallery_image"+data[key].image_id+"'><td id='index"+Index+"' class='index' data-image_id='"+data[key].image_id+"' data-image_caption='' data-image_alt='' data-physical_name='"+data[key].physical_name+"' data-physical_extension='"+data[key].physical_extension+"' data-image1_type='"+data[key].image1_type+"'  data-image2_type='"+data[key].image2_type+"'  data-image3_type='"+data[key].image3_type+"'   data-image4_type='"+data[key].image4_type+"'   data-imagecontent_id='"+data[key].imagecontent_id+"'  data-source='"+data[key].image+"'  >"+parseInt(Index+1)+"</td><td><img class='gallery_dataimage' src='"+data[key].image+"'/></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_caption' id='image_caption_id' rel='"+Index+"'   ></textarea></div></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_alt' id='image_alt_id'  rel='"+Index+"' ></textarea></div></td><td><div align='center'><input type='textbox' style='width:100%; text-align:center;' physical_extension='"+data[key].physical_extension+"'   rel='"+Index+"'    name='physical_name' maxlength=80  id='physical_name' temp_id='"+data[key].image_id+"' rel='"+data[key].image_id+"' value='"+physical_name+"' /></div><span class='error' id='error_"+data[key].image_id+"'></span></td><td><div class='article_table_delete'>  <a  id='edit_image' rel='"+data[key].image_id+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' id='deletetempimage' index_value="+Index+" rel='"+data[key].image_id+"' ><i class='fa fa-trash-o'></i></a></div></td></tr>";
					
							$("#link_preview_body").append(Content);
							$("#crop_container").show();
						   
						$("#gallery_data").val(JSON.stringify(SelectImage));
						  $("#LoadingSelectImageLocal").hide();
						  
					});
					
			
					
				},
				error:function(){
					
				} }).submit(); 
			
			
			
		});
		/*
		$(".remodal-close").click(function(e){
			 if (jqXHR) {
			jqXHR.abort(); 
			jqXHR = null;
			 }
		});
	*/
	
	$.validator.addMethod("TitleValidation", 
	function(HeadLine, element, params) {
		Title = true;
		
		 var headline = CKEDITOR.instances.txtgalleryname;
		headline_text = $("<div/>").html(headline.getData()).text();
		
		if($.trim(headline_text) == '') {
			Title = false;
		}
		console.log("Title:"+Title);
		return Title;
	},'This field is required.');
	
	 $("#imagegallery").validate({
		   ignore: [],
              debug: false,
		  rules: {
                        "txtgalleryname" : {
							TitleValidation : true
						},
						"ddMainSection" : {
							required : true
						},
						/*"ddAgency" : {
							required : true
						},*/
						"txtCanonicalUrl" : {
							url: true
						},
		  },
		errorPlacement: function (error, element)
				{
					if(element.attr("name") == 'txtgalleryname')
					{ 
					error.insertAfter($("#gallery_name_error"));
					}else {
						error.insertAfter(element);
					}
				},
		  errorElement: "p"
		   
	  });
	  
	
	  
function valid_crop_resize_image() {
		
		 bool = true;
 
 $.each(SelectImage,function(key,value){
	
	 if(value.image1_type != 1 && value.image1_type != 2) {
		 bool = false;
	 }
	  if(value.image2_type != 1 && value.image2_type != 2) {
		 bool = false;
	 }
	  if(value.image3_type != 1 && value.image3_type != 2) {
		 bool = false;
	 }
	  if(value.image4_type != 1 && value.image4_type != 2) {
		 bool = false;
	 }

 });
 
 return bool;
}
 
 $("#publishtop_id").click(function() {
	 	CKEDITOR.instances.txtgalleryname.updateElement();

	 $("#status_id").val('P');
	 
$('[name="txtMetaTitle"]').each(function () {
            $(this).rules('add', {
                required: true
			});
			
			
});
$("#imagegallery").valid();
 	if(CheckImageName() == false) 
					return false;	
 
	  if($("#imagegallery").valid()) {	
	  
				if($("#gallery_data").val() != '{}' && $("#gallery_data").val() != '') { 
					
						var confirm_status = confirm('Are you sure you want to save the details and Publish the gallery?');
						if(confirm_status==true) {
							$("#ddMainSection").attr('disabled',false);
							multiple_section_mapping_before_submit();
						} else {
							return false;
						}
						
					
				} else {
					Flash_message("Please add atleast one image","SessionError");
					return false;
				}
			 } 
	
 });
 
 $("#send_drafttop_id").click(function() {
	 	CKEDITOR.instances.txtgalleryname.updateElement();
	 
	 $("#status_id").val('D');
	 
$('[name="txtMetaTitle"]').each(function () {
           $(this).rules('remove');
         });
		 
			$("#imagegallery").valid();
					if(CheckImageName() == false) 
					return false;	
			
			 if($("#imagegallery").valid()) {

		
			 
				var confirm_status = confirm('Are you sure you want to save the details in Draft status?');
					if(confirm_status==true) {
						$("#ddMainSection").attr('disabled',false);
						multiple_section_mapping_before_submit();
					} else {
						return false;
					}
				
			 } 
			
 		});
	
	$("#unpublishtop_id").click(function() {
			CKEDITOR.instances.txtgalleryname.updateElement();
		
		$("#status_id").val('U');
		
		$('[name="txtMetaTitle"], [name="txtBodyText"]').each(function () {
           $(this).rules('remove');
         });
		 
		 $("#imagegallery").valid();
		 
		 		if(CheckImageName() == false) 
					return false;		
		
	  if($("#imagegallery").valid()) {		
				
			
				
				if($("#gallery_data").val() != '{}' && $("#gallery_data").val() != '') { 
			 		var confirm_status = confirm('Are you sure you want to save the details and unpublish the gallery?');
					if(confirm_status==true) {
						$("#ddMainSection").attr('disabled',false)
						multiple_section_mapping_before_submit();
					} else {
						return false;
					}
				} else {
				  Flash_message("Please add atleast one image","SessionError");
				  return false;
				}
		} 
		
	});
	
	$("#count1").blur(function() {
	$MetaTitle = $.trim($("#count1").val());
	
	$Title = $("<div/>").html(CKEDITOR.instances.txtgalleryname.getData()).text();	
	
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
   
 var headline = CKEDITOR.instances.txtgalleryname;
 headline.on('contentDom', function() {
    headline.document.on('keyup', function(event) {
   
    var decoded_headline = $.trim($("<div/>").html(headline.getData()).text());
	
	Remain_letters = $("#count1").attr('maxlength') - decoded_headline.length;
	
	console.log( decoded_headline.length);
	
		decoded_headline = decoded_headline.substring(0,100);
    
   // if(url_title == true)
     $('#txtUrlTitle').val(decoded_headline);
     
    if(meta_title == true) {
     $('#count1').val(decoded_headline); 
 	$('#charNum1').text(Remain_letters);
	$("#count1").blur();
	}
	
    });
  });
 
   CKEDITOR.instances.txtgalleryname.on('blur', function() {
	$('#count1').blur();
  });
	
 
	$("#btnImage").change(function(){
		display_image(this);
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
 
 });
 
 
	
 
	$("#country_id").change(function() {
		if($("#country_id").val() != '') {
			
			var postdata = "country_id="+$("#country_id").val();
			$.ajax({
			url: base_url+folder_name+"/common/get_state", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			 dataType: "json",
			success: function(data)   // A function to be called if request succeeds
			{
				
				$("#txtState").val('');
				$("#state_id").val('');
				
				$("#txtCity").val('');
				$("#city_id").val('');
				
				
	}
		});
	  }
	});
	
	/*$("#agency_id").change(function() {
		if($("#agency_id").val() != '') {
			$("#byline_id").html('<option value="">-Select-</option>');
			var postdata = "agency_id="+$("#agency_id").val();
			$.ajax({
			url: base_url+"admin/common/get_author_agency_id", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			 dataType: "json",
			success: function(data)   // A function to be called if request succeeds
			{
				var OptionValue = '';
				$.each(data, function(i, item) {
    			OptionValue += '<option value='+ data[i].Author_id +'>'+ data[i].AuthorName +'</option>';
				});
				$("#byline_id").append(OptionValue);
				
	}
		});
	  }
	});
	*/
	
	
		$("#state_id").change(function() {
		if($("#country_id").val() != '' && $("#state_id").val() != '' ) {
		
			var postdata = "country_id="+$("#country_id").val()+"&state_id="+$("#state_id").val();
			$.ajax({
			url: base_url+folder_name+"/common/get_city", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			 dataType: "json",
			success: function(data)   // A function to be called if request succeeds
			{
				var OptionValue = '<option value="">-Select-</option>';
				$.each(data, function(i, item) {
    			OptionValue += '<option value='+ data[i].City_id +'>'+ data[i].CityName	+'</option>';
				});
				$("#city_id").html(OptionValue);
				
	}
		});
	  }
	});
function display_image(input)
{
	if (input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function (e)
		{
			$('#preview_image').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function CheckImageName() {
	
	bool = true;

		$('[id^="physical_name"]').each(function(){
                    if ($(this).val().length>0){
						temp_id = $(this).attr('temp_id');
						
						var index = $(this).attr('rel');
						
						image_caption 	= $(this).closest('tr').find("#image_caption_id").text();
						image_alt 		= $(this).closest('tr').find("#image_alt_id").text();
						
			postdata = "physical_name="+$(this).val()+'.'+$(this).attr('physical_extension')+"&temp_id="+temp_id+"&caption="+image_caption+"&alt="+image_alt;
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
		 temp_id =$(this).attr('temp_id');

         if(this.value === el.value) {
			 	$("#error_"+temp_id).html('Image name already exists');
				bool = false;
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
		formImage.append('content_type', 3);
		
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
					
						var Index = parseInt(Object.keys(SelectImage).length);
				 
					 	physical_name = data.physical_name;
						physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
						
						if(folder_name != 'niecpan') 
							data.caption = '';
					
					  	  SelectImage[Index] = {
						  'image_id' 			: data.image_id,
						  'image_caption' 		: data.caption,
						  'image_alt' 			: data.alt_tag,
						  'physical_name'		: physical_name,
						  'physical_extension' 	: data.physical_extension,
						  'source'				: data.image,
						  'image1_type' 		: data.image1_type,
						  'image2_type' 		: data.image2_type,
						  'image3_type' 		: data.image3_type,
						  'image4_type' 		: data.image4_type,
						  'imagecontent_id' 	: ''
						  };
						
						 if(parseInt(Index+1)%2 != 0)
							var Class = 'class = "odd" role="row"';
						 else 
							var Class = 'class = "even" role="row"';
						
							$("#image_data").val(JSON.stringify(SelectImage));
						  
							$("#ImageContainerSet").append('<div class="ImageClose" id="SetImage'+data.image_id+'"><img src="'+data.image+'" /><img id="ImageRemove" data-image_id="'+data.image_id+'" src="'+image_url+'images/admin/close-button.png" /></div>'); 
						  
							var	Content = "<tr "+Class+" id='gallery_image"+data.image_id+"'><td id='index"+Index+"'  class='index' data-image_id='"+data.image_id+"' data-image_caption='' data-image_alt='' data-physical_name='"+data.physical_name+"' data-physical_extension='"+data.physical_extension+"' data-image1_type='"+data.image1_type+"'  data-image2_type='"+data.image2_type+"'  data-image3_type='"+data.image3_type+"'   data-image4_type='"+data.image4_type+"'   data-imagecontent_id='"+data.imagecontent_id+"'  data-source='"+data.image+"' >"+parseInt(Index+1)+"</td><td><img class='gallery_dataimage' src='"+data.image+"'/></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_caption' id='image_caption_id' rel='"+Index+"' ></textarea></div></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_alt' id='image_alt_id' rel='"+Index+"' ></textarea></div></td><td><div align='center'><input type='textbox' style='width:100%; text-align:center;'  maxlength=80  name='physical_name' temp_id='"+data.image_id+"' id='physical_name' physical_extension='"+data.physical_extension+"'   rel='"+Index+"'  value='"+physical_name+"' /></div><span class='error' id='error_"+data.image_id+"'></span></td><td><div class='article_table_delete'>  <a  id='edit_image' rel='"+data.image_id+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' id='deletetempimage' index_value="+Index+" rel='"+data.image_id+"' ><i class='fa fa-trash-o'></i></a></div></td></tr>";
								
							$("#link_preview_body").append(Content);
							$("#crop_container").show();
			
								$('input[name^=display_order]').keypress(function (e) {
								 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {        
										   return false;
								}
							   });
							
						  								   
						  $("#gallery_data").val(JSON.stringify(SelectImage));
						  $("#LoadingSelectImageLocal").hide();
										
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

function rearrange_gallery_container() {
	
	delete SelectImage;
	
	SelectImage  = [];
	
		$('td.index').each(function (i) {
							
							var data = $(this).data();
							
							SelectImageset = [];
							
							SelectImageset[0] = $(this).closest('tr').find("#image_caption_id").text();
							
							  SelectImage[i] = {
										  'image_id' 			: data.image_id,
										  'image_caption' 		: $(this).closest('tr').find("#image_caption_id").text(),
										  'image_alt' 			: $(this).closest('tr').find("#image_alt_id").text(),
										  'physical_name'		: data.physical_name,
										  'physical_extension' 	: data.physical_extension,
										  'source'				: data.source,
										  'image1_type' 		: data.image1_type,
										  'image2_type' 		: data.image2_type,
										  'image3_type' 		: data.image3_type,
										  'image4_type' 		: data.image4_type,
										  'imagecontent_id' 	: data.imagecontent_id
										  };
										  
										  console.log(SelectImage[i]);
										  
						});
						
						console.log("test"+SelectImage);
						
	$("#gallery_data").val(JSON.stringify(SelectImage));
	
	$("#link_preview_body").empty();
	
	Length_SelectImage  = parseInt(Object.keys(SelectImage).length);
	
	console.log(SelectImage);
	console.log(Length_SelectImage);
	
	if(Length_SelectImage!= 0) {

		for(Count = 0; Count < Length_SelectImage ; Count++) {
			var Content = '';
			if(Count%2 == 0)
			var Class = 'class = "odd" role="row"';
			else 
			var Class = 'class = "even" role="row"';
			
			var Index = Count;
			
			var data = SelectImage[Count];
			
			//caption 			=  $.trim(data.image_caption);
			//data.image_caption =  caption.replace(/'/g,"&#39;");
			
			//image_alt 			=  $.trim(data.image_alt);
			//data.image_alt =  image_alt.replace(/'/g,"&#39;");
			
			
							var	Content = "<tr  "+Class+" id='gallery_image"+data.image_id+"'><td  id='index"+Index+"' class='index' data-image_id='"+data.image_id+"' data-image_caption='"+data.image_caption+"' data-image_alt='"+data.image_alt+"' data-physical_name='"+data.physical_name+"' data-physical_extension='"+data.physical_extension+"' data-image1_type='"+data.image1_type+"'  data-image2_type='"+data.image2_type+"'  data-image3_type='"+data.image3_type+"'   data-image4_type='"+data.image4_type+"'   data-imagecontent_id='"+data.imagecontent_id+"'  data-source='"+data.source+"' >"+parseInt(Index+1)+"</td><td><img class='gallery_dataimage' src='"+data.source+"'/></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_caption' id='image_caption_id' rel='"+Index+"'  >"+data.image_caption+ "</textarea></div></td><td><div align='center'><textarea style='width:300px; text-align:center;'  name='image_alt' id='image_alt_id' rel='"+Index+"'   >"+data.image_alt+"</textarea></div></td><td><div align='center'><input type='textbox' style='width:100%; text-align:center;'  name='physical_name'  maxlength=80 id='physical_name' physical_extension='"+data.physical_extension+"' temp_id='"+data.image_id+"'  rel='"+Index+"'  value='"+data.physical_name+"' /></div><span class='error' id='error_"+data.image_id+"'></span></td><td><div class='article_table_delete'>  <a  id='edit_image' rel='"+data.image_id+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' id='deletetempimage' index_value='"+Count+"' rel='"+data.image_id+"' ><i class='fa fa-trash-o'></i></a></div></td></tr>";
								
		$("#crop_container").show();
		$("#link_preview_head").show();
		$("#link_preview_body").append(Content);
		
		}
		
	} else {
		
		$("#crop_container").hide();
	}
	
}

