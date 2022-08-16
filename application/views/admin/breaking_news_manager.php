<?php $script_url = image_url; ?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
<link href="<?php echo $script_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" /> -->
<!-- tool tip begins-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>

<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap-datetimepicker.js"></script>
<!--calendar begind-->
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script>

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

</script>

<!--calendar ends-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/w2ui-fields-1.0.min.js"></script>-->
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />

<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>-->
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>	-->

<style>
.error
{
	color:#F00;
}
</style>
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
			<?php	if(($this->session->flashdata("error"))) { ?>
				<div id="flash_msg_id" class="FloatLeft SessionError"><?php echo $this->session->flashdata("error");?></div>
			<?php } ?>
			
			<div id="activatedmessage" class="FloatLeft SessionSuccess" style="display:none">Activated Successfully.</div>
			<div id="deactivatedmessage" class="FloatLeft SessionSuccess" style="display:none">Deactivated Successfully.</div>
			<div id="deletedmessage" class="FloatLeft SessionSuccess" style="display:none ">Deleted Successfully.</div>
			
			<p class="FloatRight SaveBackTop article_save">
			<a href="#" onclick="update_disp_order();" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Save Display order</a>
			<?php if(defined("USERACCESS_ADD".$Menu_id) && constant("USERACCESS_ADD".$Menu_id) == '1') { ?>
			<a href="<?php echo base_url();?>niecpan/breaking_news_manager/create_breaking_news" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Add Breaking News</a>
			<?php } ?>
			
			
			<a href="#" id="news_publish" name="news_publish" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Publish</a>
			
			<?php if(isset($homepage_id) && count($homepage_id) > 0 && $homepage_id['Version_Id'] != '' && $homepage_id['Page_master_id'] != '') { ?>
			<a class="back-top FloatRight" target="_blank" href="<?php echo base_url(); ?>niecpan/template_designer/load_saved_template/<?php echo $homepage_id['Page_master_id'].'/'.$homepage_id['Version_Id']; ?>" title="Preview"><i class="fa fa-desktop i_extra"></i></a>
			<?php } ?>
			
			</p>

		</div>
		<div class="Overflow DropDownWrapper">
			<div class="container">
				<div class="row AskPrabuCheckBoxWrapper">
					<ul class="AskPrabuCheckBox FloatLeft">
						<!--<li>
							<input type="checkbox" id="search_based_date" value="yes">
							<label class="include_label HeadTopAuto"  for="search_based_date">Search Based on Date Range</label>
						</li>-->
						<li id="checkin_checkout_div">
							<p class="CalendarWrapper" >
								<label class="include_label HeadTopAuto"  for="search_based_date">Search Based on Date Range</label>
								<input type="text" placeholder="Start Date" id="date_timepicker_start" value="" readonly="readonly">
								<input type="text" placeholder="End Date" id="date_timepicker_end" value="" readonly="readonly">
							</p>
						</li>
						
						<li class="ScrollSpeed">
						<table>
						<tr>
							<td>
								<label class="padding-0">Scroll speed : <span id="result"><?php if(isset($scroll_speed['breakingNews_scrollSpeed']) && $scroll_speed['breakingNews_scrollSpeed'] != "") { echo $scroll_speed['breakingNews_scrollSpeed']; } ?></span></label>
							</td>
							<td>
								<input class="BorderBoxNone" id="price" type="range" min="1" max="10" value="<?php if(isset($scroll_speed['breakingNews_scrollSpeed']) && $scroll_speed['breakingNews_scrollSpeed'] != "") { echo $scroll_speed['breakingNews_scrollSpeed']; } ?>" />
							</td>
							<td>
								<button type="button" name="btnScrollSpeed" id="btnScrollSpeed" class="btn-primary">Save</button>
							</td>
						</tr>
						</table>
						</li>
					</ul>
				</div>
				
			</div>
			<div class="FloatLeft TableColumn">
				<div class="FloatLeft w2ui-field">
					<select id="article_status" class="controls">
						<option value="">Status: All</option>
						<option value="A">Active</option>
						<option value="I">Inactive</option>
					</select>
				</div>
				<div class="FloatLeft w2ui-field">
					<select id="search_by" class="controls">
						<option value="">Search By: All</option>
						<option value="news" >Breaking News</option>
						<option value="created_by" >Created By</option>
					</select>
				</div>
				<div class="FloatLeft TableColumnSearch">
					<input type="search" placeholder="Search" class="SearchInput" id="search_text" >
				</div>
				<button class="btn btn-primary" type="button" id="article_search_id">Search</button>
				<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
				<p id="srch_error" style="clear: left; color:#F00;margin:0"></p>
				
				
				
				
			</div>
			<!--<form id="breaking_news_manager" name="breaking_news_manager" onsubmit="return false" method="post">-->
			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr> 
						<th>Title</th>
						<th>Linked article</th>
						<th>Display Order</th>
						<th>Created By</th>
						<th>Created On</th>
						<th>Status</th>
						<?php if((defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == '1') || (defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == '1' ) ||   (defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == '1' ) ) { ?>
						<th>Action</th>
						<?php } ?>
					</tr>
				</thead>
			</table>
            <!--</form>-->
		</div>

<script type="text/javascript">

function Flash_message(msg,type) 
{
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
 
$(document).ready(function() {

var p = document.getElementById("price"),
 res = document.getElementById("result");

p.addEventListener("input", function() {
    res.innerHTML = p.value;
}, false); 

/*$("#txtScrollSpeed").on('input blur',function(e) {
		var value = $("#txtScrollSpeed").val();
		result = value.replace(/[^0-9_-]/g,'');
		$("#txtScrollSpeed").val(result);
	});*/
	
/*$("#breaking_news_manager").validate(
	{
		ignore:[],
		debug: false,
	});*/
	
$("#btnScrollSpeed").click(function()
{
	//var scroll_txt = $("#txtScrollSpeed").val();
	var scroll_txt = $("#result").html();
		var confirm_box = confirm("Are you sure want to change the scroll speed of Breaking News?");
		if(confirm_box)
		{
			$.ajax({
					url:"<?php echo base_url(); ?>niecpan/breaking_news_manager/news_scroll_speed", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data:  {scroll_txt: scroll_txt},
					success: function(data)   // A function to be called if request succeeds
					{
						 if(data == "success")
						 {
							 alert("Scroll speed updated succesfully");
						 }
						 else
						 {
							  alert("Failed to update scroll speed");
						 }
					}
				});
		}
});


$("#news_publish").click(function()
{
	var confirm_box = confirm("Are you sure want to publish the Breaking News?");
	if(confirm_box)
	{
		$.ajax({
				url:"<?php echo base_url(); ?>niecpan/breaking_news_manager/publish_breakingnews", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data:  { },
				success: function(data)   // A function to be called if request succeeds
				{
					 if(data == "success")
					 {
						 alert("Breaking News published succesfully");
					 }
					 else
					 {
						  alert("Failed to publish breaking news");
					 }
				}
			});
		}
	
});


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
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');	
	$("#srch_error").html('').hide();
	breakingNews_datatable();
	});
	
	$('body').keypress(function (e) {
		if(e.which == 13) {
			$("#article_search_id").click();
		}
	});

	function breakingNews_datatable() {
		
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
	 //"order": [1,"asc"],
        "processing": true,
		 "autoWidth": false,
        "bServerSide": true,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 10,
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ -1 ] }
       ],

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
			  $("#example").find('tbody tr').append($('<td class="BackArrow"><a href="<?php echo base_url(); ?>niecpan/breaking_news_manager" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
			 }
			 else
			 {
			  $(oSettings.nTHead).show(); 
			 }
		},
		
		"ajax": {
            "url": "<?php echo base_url(); ?>niecpan/breaking_news_manager/breakingNews_datatable",
			"type" : "POST",
			"data" : {
		 "search_by" : SearchBy, "searchtxt" : Search_text, "from_date" : check_in, "to_date" : check_out, "Status" : Status}
		 }
    });
	}
	breakingNews_datatable();
			
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
				breakingNews_datatable();
				$("#srch_error").html("");
			}
		}
		else
		{
			breakingNews_datatable();
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
	
	$(document.body).on('click', '#status_change', function(event)
	{
		var id = $(this).attr('news_id');	
		var set_status = $(this).attr('status');	
		var set_name = $(this).attr('name');			
		
		switch(set_status)
		{
			case "I":
				var status_alert = confirm("Are you sure you want to activate the breaking news  - "+set_name+"?");
				$("#loading_msg").html("Please wait while activation is in process");
				break;
			case "A":
				var status_alert = confirm("Are you sure you want to deactivate the breaking news   - "+set_name+"?");
				 $("#loading_msg").html("Please wait while deactivation is in process");
				break;
		}	
			if(set_status)
			{
				if(status_alert == true)
				{
					
					$("#commom_loading").show();
							$.ajax({ 
								url:'<?php echo base_url()."niecpan/breaking_news_manager/changestatus";?>',
								type:"POST",
								data:{"news_id":id, "status":$(this).attr('status')},
								success: function(data) 
								{
									if(data == 'success')
									{
										  if(set_status == 'I')
										  {
											  $("#status"+id).removeClass('fa-caret-right').addClass('fa-pause');
											  $("#status"+id).parent('a').attr('status', 'A');
											  $("#status"+id).parent('a').attr('title', 'Inactive');
											  $("#status_img"+id).removeClass('fa-times').addClass('fa-check');
											  $("#img_change"+id).attr('title', 'Active');
										  }
										  else if(set_status == 'A')
										  {
											  $("#status"+id).removeClass('fa-pause').addClass('fa-caret-right');
											  $("#status_img"+id).removeClass('fa-check').addClass('fa-times');
											  $("#status"+id).parent('a').attr('title', 'Active');
											  $("#status"+id).parent('a').attr('status', 'I');;
											  $("#img_change"+id).attr('title', 'Inactive');
										  }
									}
									breakingNews_datatable();
									$("#commom_loading").hide();
								}  
							});
				}
				else
				{
					$("#commom_loading").hide();
					return false;
				}
			}
	});
	
	$('#search_text').keypress(function (e) {
		  if($.trim($('#search_text').val()) != '') {
		   var key = e.which;
		   if(key == 13)  {
			breakingNews_datatable();
			}  
		  }
	 });


	var fixHelperModified = function(e, tr) {
		var $originals = tr.children();
		var $helper = tr.clone();
		$helper.children().each(function(index) {
			$(this).width($originals.eq(index).width())
		});
		return $helper;
	},
		updateIndex = function(e, ui) {
			$('td span.change_displayorder', ui.item.parent()).each(function (i) {
				$(this).text(i + 1);
			});
			$('td input[name="hidden_order[]"].updatefield', ui.item.parent()).each(function (i) {
				$(this).val(i + 1);
				//console.log( $(this).val(i + 1));
			});
		};
	
	$("#example tbody").sortable({
		helper: fixHelperModified,
		 placeholder:'must-have-class',
		stop: updateIndex
	}).disableSelection();

});
	
function delete_news_func(value)
{
	var r = confirm("Are you sure want to delete this Breaking News?");
	if(r)
	{
		window.location = "<?php echo base_url(); ?>niecpan/breaking_news_manager/delete_data/"+value;
	}		
}	

function update_disp_order()
{
	var get_order = [];
	$("input[name='hidden_order[]']").each(function ()
	{
		get_order.push({order_val:($(this).val()), news_id:($(this).attr('news_id'))});
	});
	
	$.ajax({
		url: "<?php echo base_url(); ?>niecpan/breaking_news_manager/update_dispOrder",
		type: "POST",
		data: { "get_order":get_order },
		success: function(data)
		{
			alert(data);
		}
	});
	//console.log(get_order);

}
</script> 
	</div>
</div>
