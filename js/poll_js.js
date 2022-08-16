$(document).ready(function()
{
	$("#physical_name").on('input blur',function(e) {
		var value = $("#physical_name").val();
		result = value.replace(/[^a-zA-Z0-9_-]/g,'');
			
		$("#physical_name").val(result);
	});
	
	$("#link_article").click(function()
	{
		related_datatables();
	});
	
	$("#checkin_checkout_div").hide();
	$("#search_based_date").change(function()
			{
				if(this.checked) 
				{
					$("#checkin_checkout_div").show();
				} 
				else 
				{
					$("#checkin_checkout_div").hide();
				}
				$("#date_timepicker_start").val('');
     			$("#date_timepicker_end").val('');
			});
			
	var hide_show_img = $("#preview_image").attr('src');
	if(hide_show_img == '#')
	{
		$("#poll_image").hide();
	}
	
	$("#btnImageUPload").change(function(){
		$("#img_id").val("");
		image_preview(this);
		$("#btnremove_image").show();
	});
	
	$.validator.addMethod("equalToImage_old", 
	function(physical_name, element, params) {
		var home_bool = true;
		if($("#preview_image").attr('src') != '' && $('#preview_image').attr("src") != "#") {	
		postdata = "physical_name="+physical_name+'.'+$('#physical_name').attr('physical_extension')+"&temp_id="+$("#physical_name").attr('rel');
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
					} 
				}
			});
		}
			return home_bool;
	},'Image name already exists');
	
	
	$.validator.addMethod("equalToImage", 
	function(physical_name, element, params) {
		var home_bool = true;
		if($("#preview_image").attr('src') != '' && $('#preview_image').attr("src") != "#") 
		{
			postdata = "physical_name="+$('#physical_name').val()+'.'+$('#physical_name').attr('physical_extension')+"&temp_id="+$('#temp_image_id').val()+"&is_image_from_libarary="+$('#is_image_from_library').val();
			
			$("#loading_msg").html("Please wait...");
			$("#commom_loading").show();
			
			$.ajax({
				url: base_url+folder_name+"/poll_manager/check_custom_image_name", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					$("#loading_msg").html("");
					$("#commom_loading").hide();
					if(data.status == 'false') {
						home_bool = false;
					}
				},
			});
			}
			return home_bool;
	},'Image name already exists');
	
	
	$.validator.addMethod("Imagesize", function(){
		var return_val = true;
		var image = $('#preview_image');
		if($('#preview_image').attr("src") != "" && $('#preview_image').attr("src") != "#") 
		{
			var originalWidth = image[0].naturalWidth; 
			var originalHeight = image[0].naturalHeight;
			if(originalWidth != 150 && originalHeight != 150)
			{
				return_val = false;
			}
		}
		return return_val;
	 }, 'Please upload 150X150 image.');
		 
	
	
	$("#poll_form").validate(
	{
		//ignore:[],
		debug: false,
		rules:
		{
			txtQuestion: { required: true, },
			//btnImageUPload: {accept: "jpg|jpeg|png|gif", Imagesize: true },
			btnImageUPload: {accept: "jpg|jpeg|png|gif"},
			ddOptions: { required: true, },
			txtOption1: { required: true, },
			txtOption2: { required: true, },
			txtOption3: { required: true, },
			txtOption4: { required: true, },
			txtOption5: { required: true, },
			//physical_name: { equalToImage : true, required: true }
		},
		messages: 
			{
				txtQuestion: { required: "Please enter poll option text",},
				txtOption1: { required: "Please enter poll option text", },
				txtOption2: { required: "Please enter poll option text", },
				txtOption3: { required: "Please enter poll option text", },
				txtOption4: { required: "Please enter poll option text", },
				txtOption5: { required: "Please enter poll option text", },
				btnImageUPload:
				{ 
					accept: "Upload image with JPEG, JPG, PNG, GIF format",
				},
			},
		errorPlacement: function (error, element)
			{
				if(element.attr("name") == 'btnImageUPload')
				{ 
					error.insertAfter($("#image_error"));
				}
				
				else
				{
					error.insertAfter($("#"+element.attr("name")));
				}
			}
	});


	$("#btnPollSubmit").click(function() 
	{
		$('[name="physical_name"]').each(function()
		{
			$(this).rules('add',
			{
				equalToImage : true,
				required: true
			});
		});
		
		var preview_img = $("#preview_image").attr('src');	
		
		if(preview_img == "#" || ($("#img_id").val() != "" && $("#txtHiddenId").val() != ""))
		{
			$('[name="physical_name"]').each(function()
				{
					$(this).rules('remove', 'equalToImage');
					$(this).rules('remove', 'required');
				});
		}
		
		if($("#poll_form").valid())
		{
			var confirm_msg = $("#txtHiddenId").val();
			if(confirm_msg !="")
				var confirm_status = confirm("Are you sure you want to edit the poll details?");
			else
				var confirm_status = confirm("Are you sure you want to add the poll details?");
			if(confirm_status==true)
			{
				$("#poll_form").submit();
			}
		}
	});	
	
	
	$("#article_search_id").click(related_datatables);
	
	show_option_text();
	
	$("#ddOptions").change(function()
	{
		show_option_text();
	});
	

	$("#btnunlink_article").click(function()
	{
		var cnfrm_box = confirm("Are you sure you want to unlink this article");
		if(cnfrm_box==true)
		{
			$("#hiddn_article_id").val('');
			$("#article_title_div").hide();
			$("#article_id_div").hide();
			$("#btnunlink_article").hide();
		}
	});
	
	if($("#hiddn_article_id").val() != "")
	{
		$("#btnunlink_article").show();
	}
	
	$("#btnremove_image").click(function()
	{
		var cnfrm_box = confirm("Are you sure you want to remove the image");
		if(cnfrm_box==true)
		{
			$("#preview_image").attr("src", "#");
			$("#preview_image").hide();
			$("#img_id").val('');
			$("#btnremove_image").hide();
			$("#btnImageUPload").val('');
			$("#physical_name").val('');
			$("#img_path").val('');
			$("#img_caption").val('');
			$("#hidden_img_name").val('');
		}
	});
	
	if($("#img_id").val() != "")
	{
		$("#btnremove_image").show();
	}

});

function show_option_text()
{
	if($("#ddOptions").val() == '1')
		{
			$("#div_option_1").show();
			$("#div_option_2").hide();
			$("#div_option_3").hide();
			$("#div_option_4").hide();
			$("#div_option_5").hide();
			
			$("#txtOption2").val("");
			$("#txtOption3").val("");
			$("#txtOption4").val("");
			$("#txtOption5").val("");
		}
		else if($("#ddOptions").val() == '2')
		{
			$("#div_option_1").show();
			$("#div_option_2").show();
			$("#div_option_3").hide();
			$("#div_option_4").hide();
			$("#div_option_5").hide();
			
			$("#txtOption3").val("");
			$("#txtOption4").val("");
			$("#txtOption5").val("");
		}
		else if($("#ddOptions").val() == '3')
		{
			$("#div_option_1").show();
			$("#div_option_2").show();
			$("#div_option_3").show();
			$("#div_option_4").hide();
			$("#div_option_5").hide();
			
			$("#txtOption4").val("");
			$("#txtOption5").val("");
		}
		else if($("#ddOptions").val() == '4')
		{
			$("#div_option_1").show();
			$("#div_option_2").show();
			$("#div_option_3").show();
			$("#div_option_4").show();
			$("#div_option_5").hide();
			
			$("#txtOption5").val("");
		}
		else if($("#ddOptions").val() == '5')
		{
			$("#div_option_1").show();
			$("#div_option_2").show();
			$("#div_option_3").show();
			$("#div_option_4").show();
			$("#div_option_5").show();
		}
}
function get_content_id(id, title)
{
	var get_title = title.replace(/_/g, " ");
	var confirm_box = confirm("Are you sure you want to link this article - "+get_title+"");
	if(confirm_box == true)
	{
		var inst = $.remodal.lookup[$('[data-remodal-id=modal1]').data('remodal')];
		inst.close();
	}
	$("#article_id_div").show();
	$("#get_article_id").html(id);
	$("#hiddn_article_id").val(id);
	$("#article_title_div").show();
	$("#get_article_title").html(get_title);
	//$("#hiddn_article_title").val(get_title);
	$("#btnunlink_article").show();
}

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
        sProcessing: "<img src='"+base_url+"images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },
	
        "processing": true,
		 "autoWidth": false,
        "bServerSide": true,
		"bDestroy": true,
		"searching": false,
		"iDisplayLength": 10,
		"order": [[2, "desc"]],
		"fnDrawCallback":function(oSettings){  
		  /* if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();
			 $("#example_length").hide();
		   } else {
			 $("#example_paginate").show();
			 $("#example_length").show();
		   }*/
		  // $("#example_length").show();
		   
		   if($(this).find('tbody tr').text()== "No matching records found") {
			 $(oSettings.nTHead).hide(); 
			 $('.dataTables_info').hide();
     		} else {
      		$(oSettings.nTHead).show(); 
     		}
			
			// $('[data-toggle="tooltip"]').tooltip();
		   
		},
		
		"ajax": {
            "url": base_url+folder_name+"/poll_manager/search_internal_article",
			"type" : "POST",
			"data" : {
		 "Search_by" : SearchBy, "Section" : Section, "Search_text" : Search_text, "check_in" : check_in, "check_out" : check_out, "Status" : Status, "article_Type" : article_Type, "content_id" : ''}
		 }
    });
		
}

function image_preview(input)
{
	var image_preview = $("#preview_image").attr('src');
	$("#preview_image").show();
	if(input.files && input.files[0])
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

function delete_poll(value)
{
	var r = confirm("Are you sure want to delete this Poll Question?");
	if(r)
	{
		window.location = base_url+folder_name+"/poll_manager/delete_data/"+value;
	}		
}