<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<link href="<?php echo $script_url; ?>css/admin/bootstrap.min.css" rel="stylesheet" >
<?php /*?><link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css"><?php */?>
<script type="text/javascript" src="<?php echo $script_url; ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap-datetimepicker.js"></script>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>	
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap/bootstrap.min.js"></script>-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.style-my-tooltips.js"></script>-->

<style>
.report-note{
	color:#1c69ad;
	margin-left:320px  !important;
}
.report-note li{
	width:100%;
}
.report-note li:first-child{
	font-weight:bold;
	}
	.report-note span{
		color:#F00;
	}
</style>
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
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/w2ui-fields-1.0.min.js"></script>-->
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">-->

<style>
.productivity-report p span{
     margin:0 10px;
}
.productivity-report td p span{
     margin:0 13px;
}
</style>
<script>
$(document).ready(function() 
{
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
	
	
	
});

</script>
</head><body>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper ">
				<div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
				<h2><?php echo $title; ?></h2>
			</div>
			<p class="FloatRight SaveBackTop remoda1-bg">
				
				<a href="#" id="export_excel" target="_blank" type="button" class="btn-primary btn"><i class="fa fa-plus-circle"></i>Export to CSV</a></p>
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="container">
				<div id="" class="row AskPrabuCheckBoxWrapper">
					<ul class="AskPrabuCheckBox FloatLeft">
						<!--<li class="has-pretty-child">
							<div class="clearfix prettycheckbox labelright  red">
								<input type="checkbox" id="search_based_check"  class="myClass" value="yes" name="answer">
								<a class=" " href="#"></a>
								<label for="search_based_check">Search Based on Date Range</label>
							</div>
							<a href="#" class=""></a> </li>-->
						<li>
							<p class="CalendarWrapper" id="checkin_checkout_div">
								<label for="search_based_check">Search Based on Date Range</label>
								<input type="text" value="" id="date_timepicker_start" placeholder="Start Date">
								<input type="text" value="" id="date_timepicker_end" placeholder="End Date">
							</p>
						</li>
						
						<li style=" position: absolute; left: 45%; z-index: -9999;">
							<ul class="report-note">
							<li>Note:</li>
							<li><span>D</span> - Draft</li>
							<li><span>P</span> - Published</li>
							<li><span>UP</span> - Un Published</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class="FloatLeft Module02" >
				<div class="FloatLeft w2ui-field">

<label>Role</label>
<!--<select id="ddFilterBy" name="ddFilterBy" placeholder="Sort By: All" class="controls">
-->
<select id="ddRole" name="ddRole"  class="controls">


<!--<option value="">- Status -</option>-->
<option value="">All</option>
              <?php
			  if(isset($role_name)) {
				  foreach ($role_name as $role)
				  {?>
					<option id="MainSectionOption" value="<?php  echo $role['role_id'] ?>"> <?php  echo $role['rolename'] ?></option>
            <?php }
									}
								   ?>
</select>
</div>
				<div class="FloatLeft w2ui-field">
					<label>Status</label>
					<select id="ddStatus" name="ddStatus" class="controls">
						<!--<option value="">- Status -</option>-->
						<option value="">Status- All</option>
						<option value="1">Active</option>
						<option value="0">In active</option>
					</select>
				</div>
				<input placeholder="Search by username" class="SearchInput" name="txtSearch" id="txtSearch" type="text">
				<button class="btn btn-primary" type="button" id="article_search">Search</button>
				<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
			</div>
			<div id="container_datatable" class="display"  style="width:100%; float:left; ">
				<div id="work_list" class="display">
					<div class="role-dpt">
						<table id="example" class="display productivity-report comments_report" cellspacing="0" width="100%">
							<thead>
								<tr> 
									<!--<th>User ID</th>-->
									<th>User Name</th>
									<th>Status</th>
									<th>Article
										<p><span title="Draft">D</span><span title="Published">P</span><span title="Un Published">UP</span> <!---| <span>AP</span><span>U-AP</span>---></p>
									</th>
									<th>Gallery
										<p><span title="Draft">D</span><span title="Published">P</span><span title="Un Published">UP</span></p>
									</th>
									<th>Video
										<p><span title="Draft">D</span><span title="Published">P</span><span title="Un Published">UP</span></p>
									</th>
									
									<!--<th>Audio
										<p><span title="Draft">D</span><span title="Published">P</span><span title="Un Published">UP</span></p>
									</th>
									<th>resource
										<p><span title="Draft">D</span><span title="Published">P</span><span title="Un Published">UP</span></p>
									</th>-->
									<th>Total
										<p><span title="Draft">D</span><span title="Published">P</span><span title="Un Published">UP</span><!---| <span>AP</span><span>U-AP</span>---></p>
									</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<script>
$(document).ready(function() {
	$('#example').dataTable( 
{
} );
	
	user_report_datatable();
	
	$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 user_report_datatable();
		  }  
		}
	});
	
	$("#clear_search").click(function() {
		$("#ddRole").val('');
		$("#ddStatus").val('');
		$("#date_timepicker_start").val('');
		$("#date_timepicker_end").val('');
		$("#txtSearch").val('');
		user_report_datatable();
	});

	});
	
		
	function user_report_datatable() {
	
	
	var fromdate=$( "#date_timepicker_start").val();
	var todate=$( "#date_timepicker_end").val();
	var role_id=$( "#ddRole" ).val();
	var status=$( "#ddStatus" ).val();
	
	var txtSearch=$( "#txtSearch" ).val();
	
	
    $('#example').dataTable( {
		
        "processing": true,
        "bServerSide": true,
		 "bDestroy": true,
		 "autoWidth": false,
		  "searching": false,
		"iDisplayLength": 10,
		
				oLanguage: {
        sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },

	
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [1, 2, 3, 4, 5] }
       ],	
		
		"fnDrawCallback":function(oSettings )
		{
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
			  $("#example_length").hide();
			   $("#example").find('tbody tr')
    .append($('<td class="BackArrow"><a href="<?php  echo base_url().folder_name; ?>/user_productivity_report" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
		   }
		   else
		   {
			   $(oSettings.nTHead).show(); 
		   }
		  
		},
		
		"ajax": {
            "url": "<?php echo base_url().folder_name; ?>/user_productivity_report/user_report_datatable",
			"type" : "POST",
			//"data" : { "from_date" : fromdate, "to_date" : todate, "cntent_type" : cntent_type, "status":status, "section_id":section_id },
	 		"data" : {"from_date" : fromdate, "to_date" : todate, "roleid" : role_id, "status":status, "txtSearch":txtSearch},
		
		/*success:function(data)
		{
       
      	 	alert(data);
    	}*/
			
		 },
    } );
		
	}
		
	

</script> 
			<script>
$(document).ready(function()
{
	
		$('body').keypress(function (e) {
			
			if(e.which == 13) {
				$("#article_search").click();
			}
			
		});
	
	
	$("#article_search").click(function()
 	{
    	user_report_datatable();
 	});
	
	$("#export_excel").click(function()
	{
		var from_date=$( "#date_timepicker_start").val();
		var to_date=$( "#date_timepicker_end").val();
		
		$(this).attr("href", "<?php echo base_url().folder_name;?>/user_productivity_report/user_report_excel?from="+from_date+"&to="+to_date);
	});
});
</script> 
		</div>
	</div>
</div>
</body>
</html>