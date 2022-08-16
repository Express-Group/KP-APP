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
	$("#newsletter_table_form").validate(
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
	$("#bulkremove").click(function() 
	{
		if($("#newsletter_table_form").valid())
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
					url:'<?php echo base_url()."niecpan/newsletter_manager/newsletter_bulk_delete";?>',
					type:"POST",
					data:{ "emails" : required_values},
					success: function(data) 
					{
						newsletter_datatables();
						//location.href = "<?php echo base_url();?>niecpan/newsletter_manager";
					}
				});
			 }
		
		}
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
     		$("#checkin_checkout_div").hide();
    	}
    	$("#checkin_id").val('');
        $("#checkout_id").val('');
   });
   $("#export_excel").click(function()
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
		$(this).attr("href", "<?php echo base_url();?>niecpan/newsletter_manager/newsletter_export_excel/?ids="+selected_ids);
	});
});
function delete_newsletter(id)
		{
			var r = confirm("Are you sure you want to delete?");
			if(r==true){
			window.location.href="<?php echo base_url() ?>niecpan/newsletter_manager/newsletter_delete/"+id;
		}
	}
</script>

</head>
<body>

<div class="Container">
	<div class="BodyWhiteBG">
    <form id="newsletter_table_form">
		<div class="BodyHeadBg Overflow clear">
            <div class="FloatLeft">
                <div class="breadcrumbs">Dashboard > News Letter</div>
                <h2>News Letter</h2>
            </div>
           <p class="FloatRight SaveBackTop remoda1-bg"><a href="#" id="export_excel" target="_blank" type="button" class="btn-primary btn"><i class="fa fa-download"></i>Export to CSV</a></p>
            <p class="FloatRight SaveBackTop">
                <a href="javascript:void(0);"><button class="btn-primary btn" id="bulkremove" type="button" ><i class="fa fa-trash-o"></i>&nbsp;Bulk Delete</button></a>
            </p>

		</div>

		<div class="Overflow DropDownWrapper">
		<?php $data['Menu_id'] = get_menu_details_by_menu_name('News letter');?>
        <?php 
                if($this->session->flashdata("success_delete"))
                {     
                ?>
                 <div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("success_delete");?></div>
                <?php
                }
                ?> 
                
                 <?php 
                if($this->session->flashdata("fail_delete"))
                {     
                ?>
                 <div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("fail_delete");?></div>
                <?php
                }
                ?> 
        
        
        
        
			<div class="container">
 	 		<div class="row AskPrabuCheckBoxWrapper">
      			<ul class="AskPrabuCheckBox FloatLeft">
                    <li><input type="checkbox" id="search_based_check" />
                        <label class="include_label">Search Based on Date Range</label>
                    </li>
                    <li><p class="CalendarWrapper" style="display:none" id="checkin_checkout_div"><input type="text" value="" id="date_timepicker_start" placeholder="Start Date"><input type="text" value="" id="date_timepicker_end" placeholder="End Date"></p>
                    </li>
        		</ul>
       
    		</div>
			</div>

            <div class="FloatLeft TableColumn">  
            <div class="FloatLeft w2ui-field" >
                <select class="controls" id="ddFilterBy" name="ddFilterBy">
                <option value="All">Search By:All</option>
                <option value="1">Email</option>
                <option value="2">IP Address</option>
               </select>
            </div>
            <div class="FloatLeft TableColumnSearch"><input type="search" placeholder="Search" id="txtSearch" class="SearchInput"></div>
			
			<button class="btn btn-primary" type="button" id="newsletter_search">Search</button>
			<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
            </div>
             <p id="srch_error" class="CheckError" style=" padding-right: 631px !important;"></p>

			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
                     	<th><input type="checkbox" id="selectall"></th>
						<th>Email Address</th>
                        <th> User Ip Address</th>
						<th>Created On</th>
                        <?php if((defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == '1') || (defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == '1' )) { ?><th>Action</th><?php }?>
                        
					</tr>
				</thead>
				
			</table>
		</div>
        </form>
	</div>                            
</div>
<div class="remodal" data-remodal-id="removemegssage" style="position:relative;"> <label><p id="hdn_originalcmts"></p></label> </div>
<script>
$(document).ready(function()
{
   newsletter_datatables();
	$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 newsletter_datatables();
		  }  
		}
	});
	
 $("#newsletter_search").click(function()
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
    			newsletter_datatables();
    			$("#srch_error").html("");
   			}
  		}
  		else
  		{
	 		$("#srch_error").html("");
   			newsletter_datatables();
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
	 

});

function newsletter_datatables()
{
	var fromdate  =  $( "#date_timepicker_start").val();
	var todate    =  $( "#date_timepicker_end").val();
	var filterby  =  $( "#ddFilterBy" ).val();
	var searchbox =  $( "#txtSearch").val();
    $('#example').dataTable(
	{
        "processing": true,
        "bServerSide": true,
		"autoWidth": false,
		"bDestroy": true,
		"searching": false,
		"iDisplayLength": 10,
		//"order": [[ 4, 'desc' ]]
		"aoColumnDefs": [ { "bSortable": false, "aTargets": [0, -1] } ] ,
		 "aaSorting": [[3,'desc']],  
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
				 $('#export_excel').hide();
				 $('#bulkremove').hide();
				 $("#example").find('tbody tr')
    .append($('<td class="BackArrow"><a href="<?php  echo base_url(); ?>niecpan/newsletter_manager" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
		   }
		   else
		   {
			   $(oSettings.nTHead).show(); 
			   $('#export_excel').show();
			   $('#bulkremove').show();

		   }
		},
		
		"ajax": 
		{
            "url": "<?php echo base_url(); ?>niecpan/newsletter_manager/newsletter_datatable",
			"type" : "POST",
			"data" : {"from_date" : fromdate, "to_date" : todate, "filterby" : filterby,"searchtxt" : searchbox }
		 }
    });
}

$("#clear_search").click(function() {
	$("#ddFilterBy").val('All');
	$("#txtSearch").val('');
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');
	
	newsletter_datatables();
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