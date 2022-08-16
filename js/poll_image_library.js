var WidgetCustomImage = {content_id:null, widget_instance_id: null, mainSection_config_id: null, support_image_width: null, support_image_height:null };
$().ready(function() {		
		
		$('.article_popup_tabs li.img_browse').click(function(){
				//alert(WidgetCustomImage.content_id);
				articleBrowse();			
				$('.GalleryDrag').hide();
		});
		
		$('.article_popup_tabs li.img_upload').click(function(){
			articleUpload();
			$('.GalleryDrag').show();			
		});
		
		
		// Starting Default Disabled the Select box in Article 
		// IE & DM Differents
		
		$("#physical_name").on('blur input',function(e) {
			var value = $("#physical_name").val();
			result = value.replace(/[^a-zA-Z0-9_-]/g,'');
				
			$("#physical_name").val(result);	
		});
		
		
		$("#imagelibrary").change(function() {
			
			var ext = $('#imagelibrary').val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
				//console.log('Invalid Extension!');
			} else {
				$(".LoadingSelectImageLocal").show();
				var formImage = new FormData();
				formImage.append('imagelibrary',document.getElementById("imagelibrary").files[0]);
				formImage.append('popuptype',$("#current_image_popup").val());
				
				formImage.append('article_id', WidgetCustomImage.content_id);	
				//formImage.append('instance_id', WidgetCustomImage.widget_instance_id);
				//formImage.append('mainSectionConfig_id', WidgetCustomImage.mainSection_config_id);
				
				setTimeout(function(){
				uploadFormData(formImage);
				},1000);
			}
		});
		
		function createFormData(image) {

			var formImage = new FormData();
			$(".LoadingSelectImageLocal").show();
			formImage.append('imagelibrary', image[0]);
			formImage.append('popuptype',$("#current_image_popup").val());
			
			formImage.append('article_id', WidgetCustomImage.content_id);	
			//formImage.append('instance_id', WidgetCustomImage.widget_instance_id);
			//formImage.append('mainSectionConfig_id', WidgetCustomImage.mainSection_config_id);
			
			uploadFormData(formImage);
		}
		
		function uploadFormData(formData) {
				
				$.ajax({
					url: base_url+folder_name+"/poll_manager/custom_image_upload",
					type: "POST",					
					data: formData,
					contentType:false,
					cache: false,
					processData: false,
					dataType: "json",
					success: function(data){
						//$('#edit_article_image').hide();
						if(typeof data.message !== "undefined") {
							//alert(data.message);
							$(".LoadingSelectImageLocal").hide();
							return;
						}

						//$('#home_image_gallery_id' ).val(data.image_id);
						//$('#home_image_gallery_id' ).attr('rel',data.imagecontent_id);
						$('#poll_image').show();
						$('#edit_image_span').show();
						$('#remove_image_span').show();
						$("#home_image_set").html('Change Image');
						
						$('#home_image_set').removeClass('browse-image');
						//$("#home_image_set").removeClass('BorderRadius3');
						$('#preview_image').attr('src',data.image);
						//$('#home_image_container' ).css("visibility", "visible");
						$("#home_image_set").next().show();
						$("#home_image_set").next().next().show();
						//$("#home_uploaded_image").html('Image Set');
						//$("#image_caption" ).val(data.caption);
						//$("#image_alt" ).val(data.alt_tag);
						
						//$("#orig_name" ).val(data.orig_name);
						$("#full_path" ).val(data.full_path);
						//$("#filename" ).val(data.physical_name);
						//$("#temp_name" ).val(data.file_name);
						$("#temp_image_id" ).val(data.image_id);
						
						$('#image_library_id').val('');
						
						$("#img_id").val('');
						var physical_name = data.physical_name;
						physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
						
						$("#physical_name").val(physical_name);
						
						//$("#image_caption").val(physical_name);
						//$("#image_alt").val(physical_name);
						$("#image_caption").val('');
						$("#image_alt").val('');
						
						$("#physical_name").attr("readonly", false);
						
						$("#image_caption").attr("readonly", false);
						$("#image_alt").attr("readonly", false);
						
						$("#physical_name").attr('physical_extension',data.physical_extension);
						$('#temp_image_id').val(data.image_id);
						$("#is_image_from_library").val('');						
						
					
						CheckImageContainer();
						
						$(".LoadingSelectImageLocal").hide();
						
						var inst = $.remodal.lookup[$('[data-remodal-id=modal2]').data('remodal')];
						if(!inst) {
							$('[data-remodal-id=modal2]').remodal().close();
						 } else{
							  inst.close();
						  }
						$("#imagelibrary").val('');
						
						////  Show Edit & Remove Image buttons  /////
						$('.set_image').text('Change Image');
						$('#edit_image_span').html('<a class="del_image delbtn_border" onclick="set_content_id_in_remodal('  + ', ' + WidgetCustomImage.support_image_width + ', ' + WidgetCustomImage.support_image_height + ')" id="edit_article_image" rel="home" href="javascript:void(0);" style="color:#fff;" ><i class="fa fa-pencil"></i></a>');
												
						$('#remove_image_span').html('<a class="del_image" href="javascript:void(0);" onclick="return remove_custom_image('  + ')" ><i class="fa fa-trash-o"></i></a>');
						
						$('#edit_image_span').show();
						$('#remove_image_span').show();
						remove_image = false;
					},
					error: function() {
						remove_image = false;
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
			$(".LoadingSelectImageLocal").show();
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
				
				var image_name 	= '';
				image_name		= ImageDetails.image_source.split('/');		
				image_name		= image_name[image_name.length-1];
				image_name		= image_name.split('.');
				image_name		= image_name[0];

				$("#image_name").html(image_name);
				$("#height_width").html(ImageDetails.image_height+" X "+ImageDetails.image_width);
				$("#image_size").html(ImageDetails.image_size+" Kb");
				$("#image_date").html(ImageDetails.image_date);
				$("#image_path").attr('src',ImageDetails.image_source);
				
				$("#browse_image_id").val(ImageDetails.content_id);
				//console.log(ImageDetails.content_id);
				
				$("#browse_image_id").data("image_source",ImageDetails.image_source);
				$("#browse_image_id").data("content_id",ImageDetails.content_id);
				$("#browse_image_id").data("image_alt",ImageDetails.image_alt);
				$("#browse_image_id").data("image_caption",ImageDetails.image_caption);
				$("#browse_image_id").data("image_size",ImageDetails.image_size);
				$("#browse_image_id").data("image_date",ImageDetails.image_date);
				$("#browse_image_id").data("image_width",ImageDetails.image_width);
				$("#browse_image_id").data("image_height",ImageDetails.image_height);
				$("#browse_image_id").data("image_path",ImageDetails.image_path);
				//console.log($("#browse_image_id").data());
				$("#image_lists_id img").removeClass('active');
				$(this).addClass('active')
				
		});
		
		
			
		$(document.body).on('click',"#browse_image_insert",function() {			
			$("#LoadingSelectImageLibrary").show();	
			var ImageDetails = null;
			//var contentType = $('#contenttypeID' ).val();
			var contentType = '1';

				if($("#browse_image_id").val() != '' && $("#browse_image_id").val() != 0 ) {
				
					if($("#browse_image_id").data('image_source')) {
				
						ImageDetails = $("#browse_image_id").data();
						
						ImageData = "alt="+ImageDetails.image_alt+"&caption="+ImageDetails.image_caption+"&date="+ImageDetails.image_date+"&height="+ImageDetails.image_height+"&width="+ImageDetails.image_width+"&size="+ImageDetails.image_size+"&path="+ImageDetails.image_path+"&content_id="+ImageDetails.content_id+"&contentType="+contentType + "&tempImageIndex=";
				 		
						insertImageIntocustomTemp(ImageData, true);
						
						////  Show Edit & Remove Image buttons  /////
						$('.set_image').text('Change Image');
						$('#edit_image_span').html('<a class="del_image delbtn_border" onclick="set_content_id_in_remodal('  + ', ' + WidgetCustomImage.support_image_width + ', ' + WidgetCustomImage.support_image_height + ')" id="edit_article_image" rel="home" href="javascript:void(0);" style="color:#fff;" ><i class="fa fa-pencil"></i></a>');
						
						'<a class="del_image delbtn_border" onclick="set_content_id_in_remodal('  + ', ' + WidgetCustomImage.support_image_width + ', ' + WidgetCustomImage.support_image_height + ')" id="edit_article_image" rel="home" href="javascript:void(0);" style="color:#fff;" ><i class="fa fa-pencil"></i></a>'
						
						$('#remove_image_span').html('<a class="del_image"  href="javascript:void(0);"  onclick="return remove_custom_image('  + ')" ><i class="fa fa-trash-o"></i></a>');
														
					}						
				}
		});
				
				$(document.body).on('click', '#edit_article_image' ,function(){
				var image_type = $(this).attr('rel');
				var ImageIndex = '';
				/*if(image_type == 'home') {
					ImageIndex = $("#home_image_gallery_id_" ).val();
				} */
				
				ImageIndex = $("#temp_image_id" ).val();
				
				//ImageIndex = $('#uploaded_image_').val();
				//console.log(ImageIndex111);
				if(ImageIndex != '') {
									
					$("#physical_name").attr('readonly',false);
					
					window.open(base_url+folder_name+"/poll_manager/custom_image_processing/"+encodeURIComponent(base64_encode(ImageIndex))+"/"+WidgetCustomImage.support_image_width+"/"+WidgetCustomImage.support_image_height, '_blank');		
			
						
				}
				else  {
				alert("Invalid Action for Image Processing");
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
				
				if($( "#main_section_id option:selected" ).text() == 'Nation') {
					$('#country_id option:contains("India")').prop('selected', true);
					
					$('#txtState').val('');
					$('#state_id').val('');
					
					$('#txtCity').val('');
					$('#city_id').val('');
					
				}
				
				var main_section_text = $.trim($( "#main_section_id option:selected" ).text());
				
				if(main_section_text  == 'Editorials' || main_section_text  == 'Columns' ||  main_section_text  == 'Magazine'  ||  main_section_text  == 'The Sunday Standard'  || main_section_data == 'Columns' || main_section_data == 'Magazine' || main_section_data == 'The Sunday Standard' || main_section_text == 'Voices' ) {
					
					if(main_section_text  == 'Editorials') {
					$('#country_id').prop('disabled', true);
					$('#country_id option:contains("-Select-")').prop('selected', true);
					}
					
					$('#agency_id').prop('disabled', true);
					$('#agency_id option:contains("-Select-")').prop('selected', true);
					
					if(main_section_text == 'Columns' || main_section_text  == 'Magazine' || main_section_text  == 'The Sunday Standard' || main_section_text == 'Voices') {
						
						$('#txtByLine').prop('disabled', false);
						$('#txtByLine').val('');
						$('#byline_id').val('');
						$("#byline_label").html('Author');
						
						if(main_section_text == 'Voices')
						$("#byline_label").html('Byline');
					
					} else {
						//console.log("text3");
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
				
				 $("#main_section_id").attr("rel",$("#main_section_id").find('option:selected').attr('rel'));
				  $("#main_section_id").attr("section_data",$("#main_section_id").find('option:selected').attr('section_data'));
				  
				   $("#main_section_id").attr("rel");
			
			 $("input:checkbox[name='cbSectionMapping[]']").each(function() {
				 $(this).css("visibility", "visible");
			 });
			
			$("#section_mapping"+$("#main_section_id").val()).prop('checked', false);
			
			$("#section_mapping"+$("#main_section_id").val()).css("visibility", "hidden");
				
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
					
				
			function ChangeDateFormat($DateString) {
				$GetEnd = $DateString.split(' ');
				
				//console.log($GetEnd);
				
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
			
			  
			  $(".arrow-down").bind( "click", function() {
				
			var test = $(this).parent().parent().attr('id');
			//console.log(test);
			if(test == "SortTextBox")	
			{	
				//console.log('+++++++++++');
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
		
		
		
		
		
		$(document.body).on('click', '#external_edit' ,function(){  
				var position = $(this).attr('rel');
				
				$("#external_title_error").empty();
				$("#external_url_error").empty();
				
				//console.log(external_array[position]);
				
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
					//console.log("Failure");
				}
	
		});
		
		
		/* Mouse wheel */
			
		$('.popup_images').bind('mousewheel DOMMouseScroll', function(e){			
				if(e.originalEvent.wheelDelta /120 > 0) {
					//$(this).text('scrolling up !');
					//console.log('scrolling up !');
					scroll_up_direction = true;
				}
				else{
					//console.log('scrolling down !');					
					var $container = $(this); 
					
					if(reached_last){					
					
						if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
							$container.append('<div class="LoadingSelectImageLocal"><img src="'+ base_url +'images/admin/loadingimage.gif"><br>Loading ...</div>');							
								$.ajax({
									url: base_url+folder_name+"/poll_manager/search_image_library_scroll/"+ scroll_down_inc, // Url to which the request is send
									type: "POST",             // Type of request to be send, called as method
									dataType: "HTML",
									complete: 	function(){													
													$('.LoadingSelectImageLocal').fadeOut("slow");	
												},
									success: function(data)   // A function to be called if request succeeds
									{																		
										$('.LoadingSelectImageLocal').fadeOut("slow");	
										if($.trim(data) != '')
										{																		
											$container.append(data);											
											if(!scroll_up_direction)
											{
												var last_element = $( ".popup_images img#image_lists_images_id:last-child" ); 
												offset = last_element.offset();
												scroll_position = offset.top;
												$('.popup_images img').click(function(){
													if($('#image_lists_id').html() != 'No Data'){
														$('#browse_image_insert').show();
													}
													else
													{
														$('#browse_image_insert').hide();
													}
												});
												current_value = scroll_down_inc
												
											}
										}
										else
										{
											if(reached_last){
												$container.append('<div id="no-more-images">No more images to load</div>');
												reached_last = false;										
											}
										}
									},
									error: function(){
											//$('.LoadingSelectImageLocal').remove();
											$('.LoadingSelectImageLocal').hide();	
											}
								}); 							
						scroll_down_inc ++;
					  }
					}
					
				}
			});		

		
		});
		
		


function CheckImageContainer() {
	if(	$("#home_image_gallery_id" ).val() == '') {
		$(".ArticleImageContainerId" ).hide();
	} else {
		$(".ArticleImageContainerId" ).show();
		
		
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
			
			var Index = Count+1;
			
		if(external_array[Count]["type"] == 'E') {
			var	Content = "<tr "+Class+" id='external_data"+Count+"' ><td class='index' data-type='"+external_array[Count]['type']+"' data-external_title='"+external_array[Count]['external_title']+"' data-external_url='"+external_array[Count]['external_url']+"'>"+Index+"</td><td><div align='center'><p title='"+external_array[Count]['external_title']+"' href='' >"+external_array[Count]['external_title']+"</p></div></td><td>-</td><td>External</td><td><div class='article_table_delete'>  <a  id='external_edit' rel='"+Count+"' href='javascript:void(0);' class='button tick tooltip-2' data-toggle='tooltip'   title='Edit' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a class='button cross' href='javascript:void(0)' data-toggle='tooltip'   title='Delete' data-original-title='Delete' onclick='external_action("+Count+")' id='external_action' rel='"+Count+"'><i class='fa fa-trash-o'></i></a></div></td>";
		}
		
		if(external_array[Count]["type"] == 'I') {
			var	Content = "<tr  "+Class+" id='external_data"+Count+"'><td class='index' data-type='"+external_array[Count]['type']+"'   data-content_type='"+external_array[Count]['content_type']+"' data-content_id='"+external_array[Count]['content_id']+"' data-short_title='"+external_array[Count]['short_title']+"' data-long_title='"+external_array[Count]['long_title']+"' data-bread_crumb='"+external_array[Count]['bread_crumb']+"'  >"+Index+"</td><td><div align='center'><p title='"+external_array[Count]['long_title']+"' href='' >"+external_array[Count]['short_title']+"</p></div></td><td><div align='center'><p title='"+external_array[Count]['bread_crumb']+"' href='' >"+external_array[Count]['bread_crumb']+"</p></div></td><td>Internal</td><td><div class='article_table_delete'> <a class='button cross' href='javascript:void(0)' title='Remove' onclick='external_action("+Count+")' id='external_action' rel='"+Count+"'  data-toggle='tooltip'   title='Delete' data-original-title='Delete' ><i class='fa fa-trash-o'></i></a> </div></td>";
		}
		
		$("#link_preview_head").show();
		$("#link_preview_body").append(Content);
		
		}
		
	} else {
		
		$("#link_preview_head").hide();
		$("#link_preview_body").html('<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No data available in table</td></tr>');
		
	}
	
	//console.log(external_array);
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
	
function ValidForm(bool) {}

function CheckContent(content_id) {
		var bool = true;
	
		for(var Count = 0; Count < external_array.length ; Count++) {
			
		if(external_array[Count]["content_id"]  == content_id) {
				bool = false;
			} 	 
		}
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
	$('.GalleryDrag').show();
}
function articleBrowse() {	
	$('.article_upload').hide();
	$('.article_browse').show();
	$('.img_browse').addClass('active');
	$('.img_upload').removeClass('active');
	$('.GalleryDrag').hide();
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

function insertImageIntocustomTemp(ImageData, is_from_save)
{
	//console.log(image_id);
	$.ajax({
			url		: base_url+folder_name+"/poll_manager/Insert_temp_from_image_library",
			type	: "POST",
			data	: ImageData,
			dataType: "json",
			async	: false, 	
			success	: function(data) {
							if(is_from_save)
							{
								$('#edit_article_image').hide();
								$('#home_image_gallery_id' ).val(data.image_id);
								$('#home_image_gallery_id' ).attr('rel',data.imagecontent_id);
								$("#preview_image").attr('src',data.source);
								$('#home_image_container' ).css("visibility", "visible");
								$("#home_image_set").next().show();
								$("#home_image_set").next().next().show();
								$("#home_image_set" ).html('Change Image');
								//$("#home_uploaded_image").html('Image Set');
								$("#home_image_set").removeClass('BorderRadius3');										
								$("#image_caption" ).val(data.caption);											
									var physical_name = data.physical_name;
									physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');											
									$("#physical_name").val(physical_name);											
									$("#physical_name").attr('physical_extension',data.physical_extension);
									$("#physical_name").attr('readonly',true);
									$("#image_caption").attr("readonly", true);
									$("#image_alt").attr("readonly", true);											

								$("#image_alt" ).val(data.alt);
								$('#temp_image_id').val(data.image_id);
								
								$('#poll_image').show();
								$('#edit_image_span').show();
								$('#remove_image_span').show();
									
								$('#image_library_id').val(data.imagecontent_id);
								$("#img_id").val('');
								//$('#orig_name').val('');	
								$('#full_path').val('');	
								//$('#filename').val('');	
								//$('#temp_name').val('');
								
								CheckImageContainer();
								$("#LoadingSelectImageLibrary").hide(); 
								$("#is_image_from_library").val('1');
							}
							else
							{
								$('#temp_image_id').val(data.image_id);
								$("#physical_name").attr('readonly',false);
								
								$("#image_caption").attr("readonly", false);
								$("#image_alt").attr("readonly", false);
								
								$('#image_library_id').val('');
								//$('#orig_name').val(data.orig_name);		
								$('#full_path').val(data.source);		
								//$('#filename').val(data.physical_name);		
								//$('#temp_name').val(data.temp_name);
							}
							remove_image = false;
							
					},
					error:function() {
						remove_image = false;
					}
			}); }