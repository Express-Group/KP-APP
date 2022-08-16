<?php $script_url = image_url; ?>
<!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
<!--<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">-->
<!--<script src="http://code.jquery.com/jquery-1.10.2.js"></script>-->
<!--<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
<link href="<?php echo $script_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link href="<?php echo $script_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" /> -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->    
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>	-->
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/moment-with-locales.js"></script>-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap-datetimepicker.js"></script>
<!--<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>-->
<!--<script src="<?php echo $script_url; ?>js/jquery.remodal.js"></script>-->
<!--calendar begind-->
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<?php /*?><script src="<?php echo $script_url; ?>js/jquery.js"></script><?php */?>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; </script>
<script>
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
});

function inactive_status(value)
{
	var r = confirm("Are you sure want to inactivate this Poll Question?");
	if(r)
	{
		window.location = "<?php echo base_url().folder_name; ?>/poll_manager/inactivate_poll/"+value;
	}
}

function change_status(value, status)
{
	if(status == 1) {
		var r = confirm("Are you sure you want to inactivate the selected question?");
	} else {
		var r = confirm("Are you sure you want to activate the selected question?");
	}
	if(r)
	{
		window.location = "<?php echo base_url().folder_name; ?>/poll_manager/change_status/?id="+value+"&status="+status;
	}
}

function delete_poll(value)
{
	var r = confirm("Are you sure want to delete this Poll Question?");
	if(r)
	{
		window.location = "<?php echo base_url().folder_name; ?>/poll_manager/delete_data/"+value;
	}		
}
</script>




<!--calendar ends-->
 
<script type="text/javascript">   
 $(document).ready(function(){

	$(".arrow-down").bind( "click", function() {
    var test = $(this).parent().parent().attr('id');
	console.log(test);
	if(test == "SortTextBox")	
	{	
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


});

</script>

<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/w2ui-fields-1.0.min.js"></script>-->
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />



<div class="Container">



<div class="BodyWhiteBG">

<div class="BodyHeadBg Overflow clear">

 
<div class="FloatLeft  BreadCrumbsWrapper PollResult">
<div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
 <h2><?php echo $title; ?></h2>
</div> 

<?php if(($this->session->flashdata("success"))) { ?>
 <div id="flash_msg_id" class="FloatLeft SessionSuccess"><?php echo $this->session->flashdata("success");?></div>
<?php } ?> 
<?php if(($this->session->flashdata("error"))) { ?>
 <div id="flash_msg_id" class="FloatLeft SessionError"><?php echo $this->session->flashdata("error");?></div>
<?php } ?>
 
 <div id="activatedmessage" class="FloatLeft SessionSuccess" style="display:none">Activated Successfully.</div>
 <div id="deactivatedmessage" class="FloatLeft SessionSuccess" style="display:none">Deactivated Successfully.</div>
 <div id="deletedmessage" class="FloatLeft SessionSuccess" style="display:none ">Deleted Successfully.</div>


<?php if(defined("USERACCESS_ADD".$Menu_id) && constant("USERACCESS_ADD".$Menu_id) == 1) { ?>
 <p class="FloatRight SaveBackTop"><a href="<?php echo base_url().folder_name;?>/poll_manager/create_poll_page" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Create Poll</a></p>
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
		<input type="text" placeholder="Start Date" id="date_timepicker_start" readonly="readonly" value="">
		<input type="text" placeholder="End Date" id="date_timepicker_end" value="" readonly="readonly">
    </p>
    </li>

         
        </ul>
       
    </div>
  
   
</div>

<div class="FloatLeft TableColumn">  

<div class="FloatLeft w2ui-field">
    <select id="article_status" class="controls">
        <option value="">Status: All</option>
		 <option value="1">Active</option>
		  <option value="0">Inactive</option>
    </select>	
</div>

<div class="FloatLeft w2ui-field">
  <select id="search_by" class="controls">
        <option value="">Search By: All</option>
        <option value="question" >Question</option>
        <option value="created_by" >Created By</option>
    </select>
</div>



<div class="FloatLeft TableColumnSearch"><input type="search" placeholder="Search" class="SearchInput" id="search_text" ></div>
<button class="btn btn-primary" type="button" id="article_search_id">Search</button>
<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>

<p id="srch_error" style="clear: left; color:#F00;margin:0"></p>
</div>


<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<!--<th>ID</th>-->
						<th>Title</th>
						<th>Linked article</th>
                        <th>Created by</th>
                        <th>No of votes</th>
                        <th>Poll date</th>
						<th>Status</th>
                         <?php if((defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) || (defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1 ) ||   (defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1 ) ) { ?>
                        <th>Action</th>
                        <?php } ?>
						<th>Poll result</th>
					</tr>
				</thead>
	</table>
    
</div>

  
<script type="text/javascript">

$(document).ready(function() {

$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});

	$("#clear_search").click(function() {
	$("#article_status").val('');
	$("#search_by").val('');
	$("#search_text").val('');
	$("#srch_error").html('').hide();
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');	
	
	pollmanager_datatable();
	});
	
	$('body').keypress(function (e) {
			
			if(e.which == 13) {
				$("#article_search_id").click();
			}
			
		});


	function pollmanager_datatable() {
		
		 $("#example_length").hide();
		 
	var Search_text = $("#search_text").val();
	var SearchBy	= $("#search_by").val();
	var check_in	= $("#date_timepicker_start").val();
	var check_out   = $("#date_timepicker_end").val();
	var Status		= $("#article_status").val();
	
    $('#example').dataTable( {
		oLanguage: {
        sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },
        "processing": true,
		 "autoWidth": false,
        "bServerSide": true,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 10,
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ -1, -2 ] }
       ],

		 "order": [[ 5 , "desc" ]],
		"fnDrawCallback":function(oSettings){
      $("html, body").animate({ scrollTop: 0 }, "slow");
		   if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();
		   } else {
			 $("#example_paginate").show();
		   }
		  
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
            "url": "<?php echo base_url().folder_name; ?>/poll_manager/pollmanager_datatable",
			"type" : "POST",
			"data" : {
		 "search_by" : SearchBy, "searchtxt" : Search_text, "from_date" : check_in, "to_date" : check_out, "Status" : Status}
		 }
    } );
	
	 
		
	}
			pollmanager_datatable();
			
	
	
	
	$("#article_search_id").click(function()
	{
		if($('#search_by').val() != '')
		{
			if($('#search_text').val() == '')
			{
				$("#srch_error").html("Please enter text to search").show();
				return false;
			}
			else
			{
				pollmanager_datatable();
				$("#srch_error").html("");
			}
		}
		else
		{
			pollmanager_datatable();
			$("#srch_error").html("");
		}
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
				case "article_manager":	
					var alert_name = 'article';
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
				var id = $(this).attr('content_id');	
				var set_status = $(this).attr('status');	
				var set_name = $(this).attr('name');			
				$.ajax({ 
				url:'<?php echo base_url().folder_name."/poll_manager/changestatus";?>',
				type:"POST",
				data:{"contentid":id, "status":$(this).attr('status')},
				success: function(data) 
				{
					if(data == 'success')
					{
						if(set_status == 'U')
						{
							var publish_status = confirm("Are you sure you want to publish the "+alert_name+"  - "+set_name+"?");
							if(publish_status == true)
							{
								$("#status"+id).removeClass('fa-caret-right').addClass('fa-pause');
								$("#status"+id).parent('a').attr('status', 'P');
								$("#status"+id).parent('a').attr('title', 'Unpublish');
								
								$("#status_img"+id).removeClass('fa-times').addClass('fa-check');
								$("#img_change"+id).attr('title', 'Published');
							}
						}
						else if(set_status == 'P')
						{
							var unpublish_status = confirm("Are you sure you want to unpublish the "+alert_name+"  - "+set_name+"?");
							if(unpublish_status == true)
							{
								$("#status"+id).removeClass('fa-pause').addClass('fa-caret-right');
								$("#status_img"+id).removeClass('fa-check').addClass('fa-times');
								$("#status"+id).parent('a').attr('title', 'Publish');
								$("#status"+id).parent('a').attr('status', 'U');;
								$("#img_change"+id).attr('title', 'Unpublished');
							}
						}
					}
	   
  				}  
   });
 
				 
			 });
			
	$('#search_text').keypress(function (e) {
  if($.trim($('#search_text').val()) != '') {
   var key = e.which;
   if(key == 13)  {
    pollmanager_datatable();
    }  
  }
 });
	});
	
	
	
</script>




       
      
                            
</div>                            
</div>                       
  
