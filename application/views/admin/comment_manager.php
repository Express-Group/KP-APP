<link href="<?php echo image_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo image_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.remodal.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.dataTables.js"></script>
<link href="<?php echo image_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/additional-methods.min.js"></script>

<style>
textarea 
{
   resize: none;
}
a#save_changes:hover{
	color:#FFF;
}
</style>
<script>
function get_date(input)
{
	if(input == '') 
	{
		return false;
	}	
	else 
	{
		// Split the date, divider is '/'
		var parts = input.match(/(\d+)/g);
		return parts[2]+'/'+parts[1]+'/'+parts[0];
	} 	
}

jQuery(function()
{
	jQuery('#date_timepicker_start').datetimepicker(
	{
	format:'d-m-Y',
	onShow:function(ct)
	{
	this.setOptions(
	{
 	maxDate:get_date($('#date_timepicker_end').val())?get_date($('#date_timepicker_end').val()):false,
	})
	},
	timepicker:false
});
	jQuery('#date_timepicker_end').datetimepicker({
	format:'d-m-Y',
	onShow:function(ct){
	this.setOptions({
 	minDate:get_date($('#date_timepicker_start').val())?get_date($('#date_timepicker_start').val()):false,
	})
	},
	timepicker:false
});
});
</script>





<script>
$(document).ready(function() 
{
	$("#comment_form").validate(
	{ 
		onclick: false, 
		rules: 
		{ 
			chk_value: 
			{ 
				required: true, 
			} 
		}, 
		messages: 
		{ 
			chk_value: "Please select atleast one comment."
		} ,
		errorPlacement: function (error, element) 
		{
			 alert(error.text());
		},
	});
	$("#btnApprove").click(function() 
	{
		if($("#comment_form").valid())
		{
			var check_all = $('#selectall').is(":checked");			
			var required_values = [];
			$("input[name='chk_value']").each(function()
			{
				if($(this).is(":checked"))
				{					
					checked_checkbox_val = $(this).val();
					$("textarea[name^=txtComments]").each(function(i,value)
					{
						id = $(this).attr('id');
						if(id == ("txtComments" + checked_checkbox_val))
						{
							d = $(this).val();
							if(d != '')
							{
							   required_values.push (checked_checkbox_val + ',' + d);
							 }
						 }
					});
				}
			});
			
			var r = confirm("Are you sure you want to approve the selected comments?");
			if(r==true)
			{
				$.ajax(
				{
					url:'<?php echo base_url().folder_name."/comment_manager/changestatus";?>',
					type:"POST",
					data:{ "required_values" : required_values, "check_all": check_all,"status":"A"  },
					success: function(data) 
					{
						comment_datatables();
						show_toastr("Selected comments Approved Successfully!", 1);
						//location.href = "<?php echo base_url();?>niecpan/comment_manager";
					}
				});
			}
		}
			
	});
		
	$("#btnReject").click(function() 
	{
		if($("#comment_form").valid())
		{
			var check_all = $('#selectall').is(":checked");			
			var required_values = [];
			$("input[name='chk_value']").each(function()
			{
				if($(this).is(":checked"))
				{					
					checked_checkbox_val = $(this).val();
					$("textarea[name^=txtComments]").each(function(i,value)
					{
						id = $(this).attr('id');
						if(id == ("txtComments" + checked_checkbox_val))
						{
							d = $(this).val();
							if(d != '')
							{
								//alert(required_values.push (checked_checkbox_val + ',' + d));
							 	required_values.push (checked_checkbox_val + ',' + d);
								
							}
						 }
					});
				}
            });
			
			
			 var r = confirm("Are you sure you want to Reject the selected comments?");
			 if(r==true)
			 {
	 			$.ajax(
				{
					url:'<?php echo base_url().folder_name."/comment_manager/changestatus";?>',
					type:"POST",
					data:{ "required_values" : required_values, "check_all": check_all,"status":"R"  },
					success: function(data) 
					{
						comment_datatables();
						show_toastr("Selected comments Rejected Successfully!", 1);
						//location.href = "<?php echo base_url();?>niecpan/comment_manager";
					}
				});
			 }
		
		}
	});	
		
	
	$('#example').dataTable( 
	{
	});
	$("#search_based_check").change(function()
    {
    	if(this.checked) 
     	{
     		$("#checkin_checkout_div").show();
    	} 
    	else 
    	{
			$("#date_timepicker_start").val('');
     		$("#date_timepicker_end").val('');
     		//$("#checkin_checkout_div").hide();
    	}
    	$("#checkin_id").val('');
        $("#checkout_id").val('');
   });
});
</script>

</head>
<body>

<div class="Container">
	<div class="BodyWhiteBG">
    <form id="comment_form">
		<div class="BodyHeadBg Overflow clear">
            <div class="FloatLeft">
                <div class="breadcrumbs">Dashboard > Comment</div>
                <h2>Comment</h2>
            </div>
           
            <p class="FloatRight SaveBackTop">
                <a href="#"><button class="btn-primary btn" type="button" id="btnReject" ><i class="fa fa-times"></i>&nbsp;Batch Reject</button></a>
            </p>
            <p class="FloatRight SaveBackTop">
                <a href="#"><button class="btn-primary btn" id="btnApprove" type="button" ><i class="fa fa-check"></i>&nbsp;Batch Approve</button></a>
            </p>

		</div>

		<div class="Overflow DropDownWrapper">
		<?php $data['Menu_id'] = get_menu_details_by_menu_name('Comments');?>
<div style=" font-size: 15px; margin: 10px 0 ;">
                <div>
                	<span style="color: #1c69ad; font-weight: bold; margin-right: 5px;" class="FloatLeft">Note: </span>
					<span style="color: #F30;" class="FloatLeft">Comments can be able to modify by double clicking the textarea. </span>
                    <span style="color: #F30;" class="FloatLeft"> &nbsp;&nbsp;Except Blocked comments can able to edit! </span>
                </div>
               </div>
               
			<div class="container">
 	 		<div class="row AskPrabuCheckBoxWrapper">
      			<ul class="AskPrabuCheckBox FloatLeft" style="margin: 10px 0 0;">
                    <!--<li><input type="checkbox" id="search_based_check" />
                        
                    </li>-->
                    <li><p class="CalendarWrapper" id="checkin_checkout_div"><label class="include_label">Search Based on Date Range</label><input type="text" value="" id="date_timepicker_start" placeholder="Start Date"><input type="text" value="" id="date_timepicker_end" placeholder="End Date"></p>
                    </li>
                    <li class="block-words">  
                    <p class="FloatRight SaveBackTop">
                <a href="<?php echo base_url().folder_name; ?>/negative_manager"><button class="btn-primary btn" type="button" id="btnblockwords">Block Words</button></a>
                    </p></li>
        		</ul>
       
    		</div>
			</div>

            <div class="FloatLeft TableColumn">  
            <div class="FloatLeft w2ui-field" >
                <select class="controls" id="ddFilterBy" name="ddFilterBy">
                <option value="All">Search By:All</option>
                <option value="1">Content Type</option>
                <option value="2">Submitted By</option>
                <option value="3">Email ID</option>
                <option value="4">IP Address</option>
               </select>
            </div>
            <div class="FloatLeft w2ui-field" >
                <select class="controls" id="ddStatus" name="ddStatus">
                <option value="">Status : All</option>
                <option value="P">Pending</option>
                <option value="A">Approved</option>
                <option value="R">Rejected</option>
                <option value="B">Blocked</option>
                </select>
            </div>
            <div class="FloatLeft TableColumnSearch"><input type="search" placeholder="Search" id="txtSearch" class="SearchInput"></div>
            <!--<i class="fa fa-search FloatLeft" id="comment_search"></i>
            <button class="btn-primary btn margin-left-50" type="button" id="clear_search">Clear Search</button>-->
			
			<button class="btn btn-primary" type="button" id="comment_search">Search</button>
			<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
            </div>
             <p id="srch_error" class="CheckError" style=" padding-right: 631px !important;"></p>

			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
                     	<th><input type="checkbox" id="selectall"></th>
						<th>Content Type</th>
                        <th>Title</th>
						<!--<th>Original Comments</th>-->
                     	<th>Modify Comments</th>
						<th>Commented By</th>
						<th>Submitted On</th>
						<th>Respond On</th>
						<th>Status</th>
                        <?php if((defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == '1') || (defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == '1' )) { ?><th>Action</th><?php }?>
                        
					</tr>
				</thead>
				
			</table>
		</div>
        </form>
	</div>                            
</div>
<div class="remodal" data-remodal-id="removemegssage" style="position:relative;"> <label><p id="hdn_originalcmts"></p></label> </div>
<style>
.CommentPopup textarea{ width:auto !important;}
</style>
<script>
$(document).ready(function()
{
	check_records();
	 
	comment_datatables();
	$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 comment_datatables();
		  }  
		}
	});
	
		$('body').keypress(function (e) {
			
			if(e.which == 13) {
				$("#comment_search").click();
			}
			
		});
	
	
	
	$("#comment_search").click(function()
 	{
  		if($('#ddFilterBy').val() != 'All')
  		{
   			if($('#txtSearch').val() == '')
   			{
    			$("#srch_error").html("Please enter text to search");
    			return false;
   			}
   			else
   			{
    			comment_datatables();
    			$("#srch_error").html("");
   			}
  		}
  		else
  		{
	 		$("#srch_error").html("");
   			comment_datatables();
  		}
 	});
	$('#selectall').click(function(event) 
	{  
        if(this.checked) 
		{ // check select status
            $('.select_check').each(function() 
			{ //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }
		else
		{
            $('.select_check').each(function()
			{ //loop through each checkbox
               this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
     });
	 
	  $(document.body).on('click', '#status_change', function(event)
	  {
		  var id = $(this).attr('comment_id');	
		  var comments = $('#txtComments'+id+'').val();
		  var r = confirm("Are you sure you want to approve?");
		  if(r==true)
		  {
			  $.ajax(
			  { 
				  url:'<?php echo base_url().folder_name."/comment_manager/changestatus";?>',
				  type:"POST",
				  data:{"commentid":id,"comment":comments,"status":"A"},
				  success: function(data) 
				  {
					  if(data == 'success')
					  {
						  comment_datatables();
						   show_toastr("Comment Approved Successfully!", 1);
						  //location.href = "<?php echo base_url();?>niecpan/comment_manager";
					  }
				  }  
			   });
		   }
	   });
	 
	  $(document.body).on('click', '#status_reject', function(event)
	  {
		  var id = $(this).attr('comment_id');	
		  var comments = $('#txtComments'+id+'').val();
		  var r = confirm("Are you sure you want to Reject?");
		  if(r==true)
		  {
			  $.ajax(
			  { 
				  url:'<?php echo base_url().folder_name."/comment_manager/changestatus";?>',
				  type:"POST",
				  data:{"commentid":id,"comment":comments,"status":"R"},
				  success: function(data) 
				  {
					  if(data == 'success')
					  {
						  comment_datatables();
						   show_toastr("Comment Rejected Successfully!", 1);
						  //location.href = "<?php echo base_url();?>niecpan/comment_manager";
					  }
				  }  
			   });
		  }
	   });

	    $(document.body).on('click', '#save_changes', function(event)
	  {
		  var id = $(this).attr('comment_id');	
		  var comments = $('#txtComments'+id+'').val();
		  var comment_status = $(this).attr('comment_status');
		  var r = confirm("Are you sure you want to Update this Comment?");
		  if(r==true)
		  {
			  $.ajax(
			  { 
				  url:'<?php echo base_url().folder_name."/comment_manager/changestatus";?>',
				  type:"POST",
				  data:{"commentid":id,"comment":comments,"status": comment_status},
				  success: function(data) 
				  {
					  if(data == 'success')
					  {
						  //comment_datatables();
						   show_toastr("Comment Updated Successfully!", 1);
						   $('.save_cancel_'+id).hide();
						   $('#UpdComments'+id).val($("#txtComments"+id).val());
						  //location.href = "<?php echo base_url();?>dmcpan/comment_manager";
					  }
				  }  
			   });
		   }
	   });

});

function comment_datatables()
{
	var fromdate=$( "#date_timepicker_start").val();
	var todate=$( "#date_timepicker_end").val();
	var filterby=$( "#ddFilterBy" ).val();
	var status=$('#ddStatus').val();
	var searchbox=$( "#txtSearch").val();
	//var page_name   = "<?php echo $this->uri->segment(2); ?>";
    $('#example').dataTable(
	{
        "processing": true,
        "bServerSide": true,
		"autoWidth": false,
		"bDestroy": true,
		"searching": false,
		"iDisplayLength": 10,
		//"order": [[ 4, 'desc' ]]
		"aoColumnDefs": [ { "bSortable": false, "aTargets": [0, 3, -1] } ] ,
		 "aaSorting": [[5,'desc']],  
		 oLanguage:
		 {
         	sProcessing: "<img src='<?php echo image_url; ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    	 },
		 
		"fnDrawCallback":function(oSettings)
		{
			   $("html, body").animate({ scrollTop: 0 }, "slow");
		   if($('span a.paginate_button').length <= 1) 
		   {
		   		$("#example_paginate").hide();
		   } 
		   else 
		   {
				 $("#example_paginate").show();
				 $("#example_length").show();
		   }
		   if($(this).find('tbody tr').text()== "No matching records found")
		   {
				 $(oSettings.nTHead).hide(); 
				 $('.dataTables_info').hide();
				 $("#example_length").hide();
				 $("#example").find('tbody tr')
    .append($('<td class="BackArrow"><a href="<?php  echo base_url().folder_name; ?>/comment_manager" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
		   }
		   else
		   {
			   $(oSettings.nTHead).show(); 
		   }
		},
		
		"ajax": 
		{
            "url": "<?php echo base_url().folder_name; ?>/comment_manager/comment_datatable",
			"type" : "POST",
			"data" : {"from_date" : fromdate, "to_date" : todate, "filterby" : filterby,"searchtxt" : searchbox ,"status": status}
		 }
    });
}

$("#clear_search").click(function() {
	$("#ddFilterBy").val('All');
	$("#ddStatus").val('');
	$("#txtSearch").val('');
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');
	//$("#article_status").val('');
	$("#srch_error").html("");
	comment_datatables();
	});

/*function comments_edit(id)
{
	document.getElementById('txtComments'+id+'').removeAttribute('readOnly');
}*/
function ToggleReadOnlyState (id)
{
	var textarea = document.getElementById('txtComments'+id+'');
	textarea.readOnly = !textarea.readOnly;
	$('.save_cancel_'+id).toggle();
	$('#txtComments'+id).val($("#UpdComments"+id).val());
}
function show_comments(comments_id)
{
	$('#hdn_originalcmts').html($("#OrgComments"+comments_id).val());
}


function check_records()
{
	$.ajax(
	{
		url: "<?php echo base_url().folder_name; ?>/comment_manager/check_records", 
		type: "post",
		data: {},
		dataType: "json",
		success: function(data)
		{
			if(data=="0")
			{
				$('#btnReject').hide();
				$('#btnApprove').hide();
			}
			//
			//alert(data);
		}
	});
	
}


</script>
       
<script type="text/javascript">
/////  Show Toastr message  /////
function show_toastr(message, toastr_type)
{
	if(message != ''){
	///////  toastr_type = 1 means success message, toastr_type = 2 means Failure message /////
	(toastr_type == 1) ? toastr.success(message) : (toastr_type == 2)? toastr.error(message) : toastr.info(message);
	}
}
$(document).ready(function(){
 
<?php if($this->session->flashdata('success')){  ?>
var flash_success = '<?php echo $this->session->flashdata('success'); ?>';
	show_toastr(flash_success, 1);
<?php } ?>

<?php if($this->session->flashdata('Fail')){  ?>
var flash_fail = '<?php echo $this->session->flashdata('Fail'); ?>';
	show_toastr(flash_fail, 2);
<?php } ?>

});
</script>