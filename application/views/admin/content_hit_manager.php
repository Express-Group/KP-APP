<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.remodal.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>
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
	$("#content_hit_table_form").validate(
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
			chk_value: "Please select atleast one Email Address."
		} ,
		errorPlacement: function (error, element) 
		{
			 alert(error.text());
		},
	});
	/*$("#bulkremove").click(function() 
	{
		if($("#content_hit_table_form").valid())
		{
			var check_all = $('#selectall').is(":checked");			
			var required_values = [];
			$("input[name='chk_value']").each(function()
			{
				if($(this).is(":checked"))
				{					
					checked_checkbox_val = $(this).val();
					required_values.push (checked_checkbox_val);
				}
            });
			
			
			 var r = confirm("Are you sure you want to Delete the selected emails?");
			 if(r==true)
			 {
	 			$.ajax(
				{
					url:'<?php echo base_url().folder_name."/content_hit_manager/content_hit_bulk_delete";?>',
					type:"POST",
					data:{ "emails" : required_values},
					success: function(data) 
					{
						content_hit_datatables();
						//location.href = "<?php echo base_url().folder_name; ?>/content_hit_manager";
					}
				});
			 }
		
		}
	});	*/
		
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
     		$("#checkin_checkout_div").hide();
    	}
    	$("#checkin_id").val('');
        $("#checkout_id").val('');
   });
  /* $("#export_excel").click(function()
	{
		var selected_ids = [];
			$("input[name='chk_value']").each(function()
			{
				if($(this).is(":checked"))
				{					
					checked_checkbox_val = $(this).val();
					selected_ids.push (checked_checkbox_val);
				}
            });
		$(this).attr("href", "<?php echo base_url().folder_name; ?>/content_hit_manager/content_hit_export_excel/?ids="+selected_ids);
	});*/
	
	
});

function fetch_emailed_to_details(content_id, content_type)
{
	$.ajax({
		
		type: 'POST',
		dataType: "JSON",
		url: '<?php echo base_url().folder_name;?>/content_hit_manager/fetch_email_details',
		data: {'content_id':content_id, 'content_type':content_type},
		success:function(data)
		{
			
		}
	});
}
</script>

</head>
<body>

<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
            <div class="FloatLeft">
                <div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
                <h2><?php echo $title; ?></h2>
            </div>
           <!--<p class="FloatRight SaveBackTop remoda1-bg"><a href="#" id="export_excel" target="_blank" type="button" class="btn-primary btn"><i class="fa fa-download"></i>Export to CSV</a></p>-->
            <!--<p class="FloatRight SaveBackTop">
                <a href="javascript:void(0);"><button class="btn-primary btn" id="bulkremove" type="button" ><i class="fa fa-trash-o"></i>&nbsp;Bulk Delete</button></a>
            </p>-->

		</div>

		<div class="Overflow DropDownWrapper">
        
			<div class="container">
 	 		<div class="row AskPrabuCheckBoxWrapper">
      			<ul class="AskPrabuCheckBox FloatLeft" id="date_checkbox">
                    <li><input type="checkbox" id="search_based_check" />
                        <label class="include_label">Search Based on Date Range</label>
                    </li>
                    <li><p class="CalendarWrapper" style="display:none" id="checkin_checkout_div"><input type="text" value="" id="date_timepicker_start" placeholder="Start Date"><input type="text" value="" id="date_timepicker_end" placeholder="End Date"></p>
                    </li>
        		</ul>
       
    		</div>
			</div>

            <div class="FloatLeft TableColumn">  
            <!--<div class="FloatLeft w2ui-field" >
                <select class="controls" id="ddFilterBy" name="ddFilterBy">
                <option value="All">Search By:All</option>
                <option value="1">Email</option>
                <option value="2">IP Address</option>
               </select>
            </div>-->
            <div class="FloatLeft TableColumnSearch"><input type="search" placeholder="Search Based On Title" id="txtSearch" class="SearchInput"></div>
			
			<button class="btn btn-primary" type="button" id="content_hit_search">Search</button>
			<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
            </div>

             <p id="srch_error" class="CheckError" style=" padding-right: 631px !important;"></p>

			<?php if(isset($content_id) && $content_id == "") { ?>
			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
                     	<th>Title</th>
						<th>Content Type</th>
						<th>Hit count</th>
						<th>Emailed Count</th>
                        <th>Created On</th>
					</tr>
				</thead>
			</table>
			<?php } elseif(isset($content_id) && $content_id != ""){ ?>
			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Name</th>
						<th>From Email</th>
                        <th>To Email</th>
					</tr>
				</thead>
			</table>
			<?php } ?>
			
			
		</div>
	</div>                            
</div>
<div class="remodal" data-remodal-id="modal1" style="position:relative;"> <label id="email_details"></label> </div>
<script>
$(document).ready(function()
{
	<?php if(isset($content_id) && $content_id != "") { ?>
		$( "#date_timepicker_start").hide();
		$( "#date_timepicker_end").hide();
		$( "#txtSearch").hide();
		$( "#content_hit_search").hide();
		$( "#clear_search").hide();
		$("#date_checkbox").hide();
	<?php } ?>
	
   content_hit_datatables();
	$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 content_hit_datatables();
		  }  
		}
	});
	
 $("#content_hit_search").click(function()
 	{
  		/*if($('#ddFilterBy').val() != 'All')
  		{
   			if($('#txtSearch').val() == '')
   			{
    			$("#srch_error").html("Please enter text to search");
    			return false;
   			}
   			else
   			{
    			content_hit_datatables();
    			$("#srch_error").html("");
   			}
  		}
  		else
  		{*/
	 		$("#srch_error").html("");
   			content_hit_datatables();
  		//}
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
	 

});

function content_hit_datatables()
{
	var fromdate  =  $( "#date_timepicker_start").val();
	var todate    =  $( "#date_timepicker_end").val();
	//var filterby  =  $( "#ddFilterBy" ).val();
	var content_id = '';
	var content_type = '';
	var searchbox =  $( "#txtSearch").val();
	<?php if(isset($content_id) && $content_id != ""){ ?>
		var content_id = '<?php echo $content_id; ?>';
		var content_type = '<?php echo $content_type; ?>';
	<?php } ?>
	
	
    $('#example').dataTable(
	{
        "processing": true,
        "bServerSide": true,
		"autoWidth": false,
		"bDestroy": true,
		"searching": false,
		"iDisplayLength": 10,
		//"order": [[ 4, 'desc' ]]
		"aoColumnDefs": [ ] ,
		 "aaSorting": [[0,'desc']],  
		 oLanguage:
		 {
         	sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
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
    .append($('<td class="BackArrow"><a href="<?php  echo base_url().folder_name; ?>/content_hit_manager" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
		   }
		   else
		   {
			   $(oSettings.nTHead).show(); 
		   }
		},
		
		"ajax": 
		{
            "url": "<?php echo base_url().folder_name; ?>/content_hit_manager/content_hit_datatable",
			"type" : "POST",
			"data" : {"from_date" : fromdate, "to_date" : todate, "searchtxt" : searchbox, "content_type":content_type, "content_id":content_id }
		 }
    });
}



$("#clear_search").click(function() {
	//$("#ddFilterBy").val('All');
	$("#txtSearch").val('');
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');
	
	content_hit_datatables();
	});
</script>
       
<script type="text/javascript">
$(document).ready(function(){
 
<?php if($this->session->flashdata('success_delete')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});
<?php } ?>

<?php if($this->session->flashdata('fail_delete')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});
<?php } ?>

});
</script>