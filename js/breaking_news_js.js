$(document).ready(function()
{
	related_datatables();
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

	CKEDITOR.replace( 'txtTitle',
    {
        toolbar : [ { name: 'basicstyles', items: ['TextColor'] } ],
		forcePasteAsPlainText :true,
    });
	
	$("#brkng_news_form").validate(
	{
		ignore:[],
		debug: false,
		rules:
		{
			txtTitle:
				{
				  required: function() { CKEDITOR.instances.txtTitle.updateElement();}
				},
			//ddDisplayOrder: { required: true },	
		},
		messages: 
		{
			txtTitle: { required: "Please enter title",},
			//ddDisplayOrder: { required: "Please select display order",},
		},
		errorPlacement: function (error, element)
		{
			if(element.attr("name") == 'txtTitle')
			{ 
				error.insertAfter($("#title_error"));
			}
			else
			{
				error.insertAfter($("#"+element.attr("name")));
			}
		}
	});

	$("#article_search_id").click(related_datatables);
	
	
	$("#btnNewsSubmit").click(function() 
	{
		if($("#brkng_news_form").valid())
		{
			var news_title = $("#txtTitle").val();
			var news_id = $("#txtHiddenId").val();
			
			$.ajax({
				type: "POST",
				data: {"news_title":news_title, "news_id":news_id},
				url: base_url+"niecpan/breaking_news_manager/check_news_title",
				success: function(data)
				{
					if(data == "exists")
					{
						$("#title_error").html("Breaking News title already exist");
					}
					else
					{
						$("#title_error").html("");
						
						var confirm_msg = $("#txtHiddenId").val();
						if(confirm_msg !="")
							var confirm_status = confirm("Are you sure you want to edit the breaking news?");
						else
							var confirm_status = confirm("Are you sure you want to add the breaking news?");
						if(confirm_status==true)
						{
							$("#brkng_news_form").submit();
						}
						//$("#brkng_news_form").submit();
					}
				}
			});
				
		}
	});	
	
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
	
	
});

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
		"bLengthChange": false,
		"iDisplayLength": 10,
		"order": [[2, "desc"]],
		
		"fnDrawCallback":function(oSettings){  
		   /*if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();
			 $("#example_length").hide();
		   } else {*/
			 //$("#example_paginate").show();
			 $("#example_length").show();
		  //}
		   
		   if($(this).find('tbody tr').text()== "No matching records found") {
			 $(oSettings.nTHead).hide(); 
			 $('.dataTables_info').hide();
     		} else {
      		$(oSettings.nTHead).show(); 
     		}
		},
		
		"ajax": {
            "url": base_url+"niecpan/breaking_news_manager/search_internal_article",
			"type" : "POST",
			"data" : {
		 "Search_by" : SearchBy, "Section" : Section, "Search_text" : Search_text, "check_in" : check_in, "check_out" : check_out, "Status" : Status, "article_Type" : article_Type, "content_id" : ''}
		 }
    });
		
}

