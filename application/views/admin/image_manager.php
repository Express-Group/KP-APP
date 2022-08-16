<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link href="<?php echo base_url(); ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />

<div class="Container">
<div class="BodyWhiteBG">
<div class="BodyHeadBg Overflow clear"> 
<div class="FloatLeft  BreadCrumbsWrapper PollResult">
<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#"><?php echo $title; ?></a></div>
 <h2><?php echo $title; ?></h2>
</div> 

<?php
if($this->session->flashdata("success"))
{     
?>
 <div id="flash_msg_id" class="FloatLeft SessionSuccess"><?php echo $this->session->flashdata("success");?></div>
<?php
}
?> 
<?php
if($this->session->flashdata("error"))
{
?>
 <div id="flash_msg_id" class="FloatLeft SessionError"><?php echo $this->session->flashdata("error");?></div>
<?php
}
?>
 
 <div id="activatedmessage" class="FloatLeft SessionSuccess" style="display:none">Activated Successfully.</div>
 <div id="deactivatedmessage" class="FloatLeft SessionSuccess" style="display:none">Deactivated Successfully.</div>
 <div id="deletedmessage" class="FloatLeft SessionSuccess" style="display:none ">Deleted Successfully.</div>

<?php if(defined("USERACCESS_ADD".$Menu_id) && constant("USERACCESS_ADD".$Menu_id) == 1) { ?>
 <p class="FloatRight SaveBackTop"><a href="<?php echo base_url().$addPage_url;?>" class="btn-primary btn"><i class="fa fa-plus-circle"></i> &nbsp;<?php echo $btn_name; ?></a></p>
 <?php } ?>
 

<?php if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) { ?>
   <p class="FloatRight SaveBackTop PubUnpublish"><button name="btnPublish" id="publish_button" title="Publish" type="button" class="btn btn-primary FloatRight i_button"><i style="" class="fa fa fa-flag"></i></button></p>
<?php } ?>
   <?php if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { ?>
   <p class="FloatRight SaveBackTop PubUnpublish">
<button class="btn btn-primary FloatRight i_button FlagIcon2" id="unpublish_button"  title="Unpublish"></button></p>
<?php } ?>
  
</div>

<div class="Overflow DropDownWrapper">

<div class="container">

  <div class="row AskPrabuCheckBoxWrapper">
      
     
      
      <ul class="AskPrabuCheckBox FloatLeft">
      
     <li>
        
<!--<input type="checkbox" id="search_based_date" value="yes">-->
<label class="include_label HeadTopAuto"  for="search_based_date">Search Based on Date Range</label>
    </li>
    <li id="checkin_checkout_div">
    <p class="CalendarWrapper" >
		<input type="text" placeholder="Start Date" id="date_timepicker_start" readonly value="">
		<input type="text" placeholder="End Date" id="date_timepicker_end" readonly value="">
    </p>
    </li>
        </ul>
    </div>
</div>

<div class="FloatLeft TableColumn">  

<div class="FloatLeft w2ui-field">
 <select id="image_status" class="controls">
        <option value=100>Status: All</option>
        <option value=1 >Active</option>
        <option value=0 >InActive</option>
    </select>	
  
</div>

<div class="FloatLeft w2ui-field">
  <select id="search_by" class="controls">
        <option value="">Search By: All</option>
        <option value="caption" >Caption</option>
		<option value="alt_tag" >Alt Tag</option>
		<option value="ContentId" >Content Id</option>
        <option value="created_by" >Created By</option>
    </select>
</div>

<div class="FloatLeft TableColumnSearch"><input type="search" placeholder="Search" class="SearchInput" id="search_text" ></div>
<button class="btn btn-primary" type="button" id="image_search_id">Search</button>
<button class="btn btn-primary" id="clear_search">Clear Search</button>

<p id="srch_error" style="clear: left; color:#F00;margin:0"></p>
</div>



<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Content ID</th>
						<th>Caption</th>
                        <th>Alt Tag</th>
						 <th>Image</th>
						<th>Created By</th>
                        <th>Modified On</th>
						<th>Status</th>
                        <?php if((defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) || (defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1 ) ||   (defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1) ) { ?>
                        <th>Action</th>
                        <?php } ?>
					</tr>
				</thead>
	</table>
    
</div>
</div>                            
</div> 

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>

<!--calendar begind-->
<script  type="text/javascript">
function get_date(input) {
if(input == '') {
return false;
}	else {
// Split the date, divider is '/'
var parts = input.match(/(\d+)/g);
return parts[2]+'/'+parts[1]+'/'+parts[0];
} 
}

jQuery(function(){

 jQuery('#date_timepicker_start').datetimepicker({
  format:'d-m-Y',
  onShow:function(ct){
   this.setOptions({
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
 
  jQuery("#date_timepicker_start").change(function(){
if (jQuery('#date_timepicker_end').val()=="") {
jQuery('#date_timepicker_end').val(jQuery('#date_timepicker_start').val());
}
});
jQuery("#date_timepicker_end").change(function(){
if (jQuery('#date_timepicker_start').val()=="") {
jQuery('#date_timepicker_start').val(jQuery('#date_timepicker_end').val());
}
});
 
});

</script>

<script type="text/javascript">

var unpublish_object = {};
var publish_object = {};

$(document).ready(function() {
	
	$(document.body).on("click","#unpublish_checkbox_id",function(){
		var content_id 			= $(this).val();
		var contentversion_id 	= $(this).attr('rel');
		var status 				= $(this).attr('status');
		
		if($(this).is(":checked")) {
			unpublish_object[content_id] = {
				'content_id' 		: content_id,
				'contentversion_id' : contentversion_id,
				'status'			: status
			};
		} else {
			delete unpublish_object[content_id];
		}
		
	});
	
		$(document.body).on("click","#publish_checkbox_id",function(){
		var content_id 			= $(this).val();
		var contentversion_id 	= $(this).attr('rel');
		var status 				= $(this).attr('status');
		
		if($(this).is(":checked")) {
			publish_object[content_id] = {
				'content_id' 		: content_id,
				'contentversion_id' : contentversion_id,
				'status'			: status
			};
		} else {
			delete publish_object[content_id];
		}
		
	});
	
	$("#publish_button").click(function() {
		
		if(parseInt(Object.keys(publish_object).length) != 0) {
			
			var publish_status = confirm("Are you sure you want to publish the selected content?");
					
			if(publish_status == true) {
			
				$("#normal_loading").show();
				var content_data = { 'content_id' : []};
				
				 $.each(publish_object,function(key,value){
							 content_data['content_id'].push(value.content_id);
				});
			
				$.ajax({ 
					url:'<?php echo base_url().folder_name."/common/publish_content";?>',
					type:"POST",
					data:{"contentids" : content_data,"content_type" : 2},
					success: function(data) 
						{
							clear_all_checkbox();
							image_datatables();
							$("#normal_loading").hide();
						}
				});  
			}
		
		} else {
			if(parseInt(Object.keys(publish_object).length) == 0)
				alert('Atleast Select one content checkbox');
			else 
				alert('Published contents cannot be published again');
		}
	});
	
	$("#unpublish_button").click(function() {
		if(parseInt(Object.keys(unpublish_object).length) != 0) {
			
			var publish_status = confirm("Are you sure you want to unpublish the selected content?");
					
			if(publish_status == true) {
			
				$("#normal_loading").show();
				var content_data = { 'content_id' : []};
				
				 $.each(unpublish_object,function(key,value){
						 content_data['content_id'].push(value.content_id);
				});
			
				$.ajax({ 
					url:'<?php echo base_url().folder_name."/common/unpublish_content";?>',
					type:"POST",
					data:{"contentids" : content_data,"content_type" : 2},
					success: function(data) 
						{
							clear_all_checkbox();
							image_datatables();
							$("#normal_loading").hide();
						}
				});  
			}
		
		} else {
			if(parseInt(Object.keys(unpublish_object).length) == 0)
				alert('Atleast Select one content checkbox');
			else 
				alert('Published contents cannot be published again');
		}
	});

$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});

	function clear_all_checkbox() {
		$.each(publish_object,function(key,value){
			var content_id = value.content_id;
			$('input:checkbox[value="' + content_id + '"]').prop('checked', false);
			delete publish_object[content_id];
		});
		$.each(unpublish_object,function(key,value){
			var content_id = value.content_id;
			$('input:checkbox[value="' + content_id + '"]').prop('checked', false);
			delete unpublish_object[content_id];
		});
	}

	function image_datatables() {
		
		 $("#example_length").hide();
	
	var Search_text = $("#search_text").val();
	var SearchBy	= $("#search_by").val();
	var check_in	= $("#date_timepicker_start").val();
	var check_out   = $("#date_timepicker_end").val();
	var Status		= $("#image_status").val();
	var page_name   = "<?php echo $this->uri->segment(2); ?>";
	
    $('#example').dataTable( {
		oLanguage: {
        sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },
        "processing": true,
		 "autoWidth": false,
        "bServerSide": true,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 50,
		 "order": [[ 0 , "desc" ]],
		 "columnDefs": [
		 { "targets": [ 0 ], "visible": false },
		{ "width": "20%", "targets": 1 },
		 { "orderable": false, "targets": 3 },
		 { "orderable": false, "targets": 7 },
		 ],
		"fnDrawCallback":function(oSettings){
			
			$("html, body").animate({ scrollTop: 0 }, "slow");
   
		   if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();	
		   } else {
			 $("#example_paginate").show();
		   }
		   
		   $("input[type=checkbox]").each(function() {
			   var content_id = $(this).val();
			  if(publish_object[content_id] != undefined)
				  $(this).prop('checked',true);
			
			  if(unpublish_object[content_id] != undefined)
				  $(this).prop('checked',true);
			
			  
		   });
		  
		   if($(this).find('tbody tr').text()== "No matching records found")
			{
			 $(oSettings.nTHead).hide(); 
			 $('.dataTables_info').hide();
			  $("#example").find('tbody tr').html($('<td valign="top" colspan="10" class="dataTables_empty BackArrow">No matching records found <a href="" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
			 }
			 else
			 {
			  $(oSettings.nTHead).show(); 
			 }
	 
		},
		
		"ajax": {
            "url": "<?php echo base_url().folder_name; ?>/image_manager/image_datatable",
			"type" : "POST",
			"data" : {
		 "Search_by" : SearchBy, "Search_text" : Search_text, "check_in" : check_in, "check_out" : check_out, "Status" : Status, "Page_name" : page_name  
			}
		 }
    } );
	
	 
		
	}
			image_datatables();
			
	
	$('#search_text').keypress(function (e) {
		if($.trim($('#search_text').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 clear_all_checkbox();
			 image_datatables();
		  }  
		}
	});
	$("#clear_search").click(function() {
	$("#image_section").val('');
	$("#search_text").val('');
	$("#search_by").val('');
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');
	$("#image_status").val(100);
	
	clear_all_checkbox();
	
	 image_datatables();
	  $("#srch_error").html("");
	});
	
	$("#image_search_id").click(function()
	{
		if($('#search_by').val() != '')
		{
			if($('#search_text').val() == '')
			{
				$("#srch_error").html("Please enter text to search");
				return false;
			}
			else
			{
				
				image_datatables();
				$("#srch_error").html("");
			}
		}
		else
		{
			image_datatables();
			$("#srch_error").html("");
		}
		
		clear_all_checkbox();
	});
		
			//$("#checkin_checkout_div").hide();
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
			
			var pathname = "<?php echo $this->uri->segment(2); ?>"; 
			
			switch(pathname)
			{
				case "video_manager":
					var alert_name = 'video';
					break;
				case "audio_manager":	
					var alert_name = 'audio';
					break;
				case "image_manager":	
					var alert_name = 'image';
					break;
				case "image_manager":	
					var alert_name = 'image';
					break;
				case "gallery_manager":	
					var alert_name = 'gallery';
					break;
				default:
					var alert_name = '';
			}
			
			 $(document.body).on('click', '#status_change', function(event)
			 {
				var boolean_value 	= false;
				var id 				= $(this).attr('content_id');	
				var set_status 		= $(this).attr('status');	
				var set_name		= $(this).attr('name');	
				
				 if(set_status == 0) {
					 
					var publish_status = confirm("Are you sure you want to active the "+alert_name+"  - "+set_name+"?");
					
						if(publish_status == true) {
							boolean_value = true;
						}
						
						var final_status = 1;
				} else if(set_status == 1){
					
					var unpublish_status = confirm("Are you sure you want to inactive the "+alert_name+"  - "+set_name+"?");
					
						if(unpublish_status == true) {
							boolean_value = true;
						}
						var final_status = 0;
				}
				 
				 if(boolean_value == true) {
				 
							
				$.ajax({ 
				url:'<?php echo base_url().folder_name."/common/changestatus";?>',
				type:"POST",
				data:{"contentid":id, "status":final_status,"content_type":2},
				success: function(data) 
					{
						if(data == 'success')
						{
							if(set_status == 0)
							{
									$("#status"+id).removeClass('fa-caret-right').addClass('fa-pause');
									$("#status"+id).parent('a').attr('status', 1);
									$("#status"+id).parent('a').attr('title', 'Inacitve');
									
									$("#status_img"+id).removeClass('fa-times').addClass('fa-check');
									$("#img_change"+id).attr('title', 'Active');
									
									$("#change_trash").attr('change_status'+id, 1);
									
									image_datatables();
							}
							else if(set_status == 1)
							{
									$("#status"+id).removeClass('fa-pause').addClass('fa-caret-right');
									$("#status_img"+id).removeClass('fa-check').addClass('fa-times');
									
									$("#status"+id).parent('a').attr('title', 'Active');
									$("#status"+id).parent('a').attr('status', 0);;
									$("#img_change"+id).attr('title', 'Inactive');
									
									$("#change_trash").attr('change_status'+id, 0);
									
									image_datatables();
							}
						}
		   
					}  
   				});
			}
 
				 
			 });
			
			
			
			 $(document.body).on('click', '#image_status_change', function(event)
			 {
				 
				var boolean_value 	= false;
				var id 				= $(this).attr('content_id');	
				var set_status 		= $(this).attr('status');	
				var set_name		= $(this).attr('name');	
				 
				 if(set_status == 'U') {
					 
					var publish_status = confirm("Are you sure you want to active the "+alert_name+"  - "+set_name+"?");
					
						if(publish_status == true) {
							boolean_value = true;
						}
				} else if(set_status == 'P'){
					
					var unpublish_status = confirm("Are you sure you want to inactive the "+alert_name+"  - "+set_name+"?");
					
						if(unpublish_status == true) {
							boolean_value = true;
						}
				}
				 
				 if(boolean_value == true) {
				 
					
				$.ajax({ 
				url:'<?php echo base_url().folder_name."/common/changestatus";?>',
				type:"POST",
				data:{"contentid":id, "status":$(this).attr('status')},
				success: function(data) 
				{
					if(data == 'success')
					{
						if(set_status == 'U')
						{
								$("#status"+id).removeClass('fa fa-check').addClass('fa fa-times');
								$("#status"+id).parent('a').attr('status', 'P');
								$("#status"+id).parent('a').attr('title', 'Inactive');
								
								$("#status_img"+id).removeClass('fa-times').addClass('fa-check');
								$("#img_change"+id).attr('title', 'Active');
								
								$("#change_trash").attr('change_status'+id, 'P');
								image_datatables();
						}
						else if(set_status == 'P')
						{
								$("#status"+id).removeClass('fa fa-times').addClass('fa fa-check');
								$("#status_img"+id).removeClass('fa-check').addClass('fa-times');
								$("#status"+id).parent('a').attr('title', 'Active');
								
								$("#status"+id).parent('a').attr('status', 'U');;
								$("#img_change"+id).attr('title', 'Inactive');
								
								$("#change_trash").attr('change_status'+id, 'U');	
								image_datatables();
						}
					}
	   
  				}  
   });
 
	}
			 });
	
		$('body').keypress(function (e) {
			
			if(e.which == 13) {
				$("#image_search_id").click();
			}
			
		});
		
	});
	
	
	
</script>      