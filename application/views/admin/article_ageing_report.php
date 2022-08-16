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
				<h2><?php echo $title.$Username; ?></h2>
			</div>
			<p class="FloatRight SaveBackTop remoda1-bg">
				<?php if($get_user_id != "")  { ?>
				<a class="FloatLeft back-top" title="Go Back" href="<?php echo base_url().folder_name;?>/article_ageing_report/"><i class="fa fa-reply fa-2x"></i></a>
				<?php } ?>
				<a href="#" id="export_excel" target="_blank" type="button" class="btn-primary btn"><i class="fa fa-plus-circle"></i>Export to CSV</a></p>
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="container" <?php if($get_user_id == "")  { ?> style="display:none" <?php } ?>>
				<div id="" class="row AskPrabuCheckBoxWrapper">
					<ul class="AskPrabuCheckBox FloatLeft">
						<li class="has-pretty-child">
							<!--<div class="clearfix prettycheckbox labelright  red">
								<input type="checkbox" id="search_based_check"  class="myClass" value="yes" name="answer">
								<a class=" " href="#"></a>
								<label for="search_based_check">Search Based on Date Range</label>
							</div>-->
							<!--<a href="#" class=""></a> --></li>
						<li>
							<p class="CalendarWrapper" id="checkin_checkout_div">
								<label for="search_based_check">Search Based on Date Range</label>
								<input type="text" value="" id="date_timepicker_start" placeholder="Start Date">
								<input type="text" value="" id="date_timepicker_end" placeholder="End Date">
							</p>
						</li>
					</ul>
				</div>
			</div>
			<div class="FloatLeft Module02" >
				<div class="FloatLeft w2ui-field" <?php if($get_user_id == "")  { ?> style="display:none" <?php } ?> >
					<label>Section</label>
					<select id="ddsection" class="controls">
						<option value="">All</option>
						<?php if(isset($section_mapping)) { 
				 foreach($section_mapping as $mapping) {   
				 
				 
				$condition = $mapping['Sectionname'] != 'Galleries' && $mapping['Sectionname'] != 'Videos' && $mapping['Sectionname'] != 'Audios' &&  $mapping['Sectionname'] != 'Resources';
				 
				 if($condition) {
					 
				 ?>
						<option id="MainSectionOption" style="color:#933;font-size:18px;" <?php if($mapping['Section_landing'] == 1 && $mapping['Sectionname'] != 'Columns' && $mapping['Sectionname'] != 'Magazine' && $mapping['Sectionname'] != 'The Sunday Standard' && $mapping['Sectionname'] != 'Editorials' ) { ?> disabled='disabled' <?php } ?> class="blog_option" <?php if(set_value("ddMainSection") == $mapping['Section_id'] || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $mapping['Section_id'] )) echo  "selected";  ?> sectoin_data="<?php echo @$mapping['Sectionname']; ?>" rel="<?php echo @$mapping['LinkedToColumnist']; ?>"  value="<?php echo $mapping['Section_id']; ?>" url_structure="<?php echo ucwords(str_replace("-"," ",str_replace("/"," > ",trim($mapping['URLSectionStructure'])))); ?>"><?php echo strip_tags($mapping['Sectionname']); ?></option>
						<?php if(!(empty($mapping['sub_section'])) ) { ?>
						<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
						<option  id="MainSectionOption" <?php if(set_value("ddMainSection") == $sub_mapping['Section_id']  || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_mapping['Section_id'] )) echo  "selected"; ?>  sectoin_data="<?php echo @$mapping['Sectionname']; ?>"  rel="<?php echo @$sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>" url_structure="<?php echo ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_mapping['URLSectionStructure'])))); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
						<?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
						<?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
						<option id="MainSectionOption" <?php if($sub_sub_mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?>  <?php if(set_value("ddMainSection") == $sub_sub_mapping['Section_id']  || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_sub_mapping['Section_id'] )) echo  "selected"; ?>  rel="<?php echo @$sub_sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_sub_mapping['Section_id']; ?>"  sectoin_data="<?php echo @$mapping['Sectionname']; ?>" url_structure="<?php echo ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_sub_mapping['URLSectionStructure'])))); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
						<?php } } ?>
						<?php  } } ?>
						<?php  } }  }?>
					</select>
				</div>
				<div class="FloatLeft w2ui-field"  <?php if($get_user_id != "")  { ?> style="display:none" <?php } ?>>
					<label>Status</label>
					<select id="ddStatus" name="ddStatus" class="controls">
						<option value="">All</option>
						<option value="1">Active</option>
						<option value="0">Inactive</option>
					</select>
				</div>
				<button class="btn btn-primary" type="button" id="article_search">Search</button>
				<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
				
				
			</div>
			<p id="srch_error" class="CheckError" style=" padding-right: 631px !important;"></p>
			<div id="container_datatable" class="display"  style="width:100%; float:left; ">
				<div id="work_list" class="display">
					<div class="role-dpt">
						<table id="example" class="display" cellspacing="0" width="100%">
							<thead>
								<?php if($get_user_id != "")  { ?>
								<tr>
									<th>Title</th>
									<th>Status</th>
									<th>Section Name </th>
									<th>Created On</th>
									<th>Ageing</th>
								</tr>
								<?php  } else {?>
								<tr>
									<th>Username</th>
									<th>Status</th>
									<th>1 Day < </th>
									<th>3 Day < </th>
									<th>5 Day < </th>
									<th>30 Day < </th>
									<th>More than 30 Days</th>
									<th>Total</th>
								</tr>
								<?php } ?>
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
	
	article_ageing_datatable();
	
	$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 article_ageing_datatable();
		  }  
		}
	});
	
	$("#clear_search").click(function() {
		$("#ddsection").val('');
		$("#ddStatus").val('');
		$("#date_timepicker_start").val('');
		$("#date_timepicker_end").val('');
		
		article_ageing_datatable();
	});

	});
	
		
	function article_ageing_datatable() {
	
	//var archieve_total_count = "";
	var userid = "";	
	var get_username = '';
	<?php if($get_user_id != "")  { ?>
	var userid = <?php echo $get_user_id; ?>;	
	
	var get_username = '<?php echo str_replace(" - ", "", $Username); ?>';
	
	//if(typeof($(".archieve_total_count").val()) != "undefined")
		//var archieve_total_count = $( ".archieve_total_count" ).val();
	//else if(typeof($(".archieve_total_count_from").val()) != "undefined")
		//var archieve_total_count = $( ".archieve_total_count_from" ).val();
	
	<?php  } ?>
	var fromdate=$( "#date_timepicker_start").val();
	var todate=$( "#date_timepicker_end").val();
	//var cntent_type=$( "#ddcontent_type" ).val();
	var status=$( "#ddStatus" ).val();
	
	var section_id=$( "#ddsection" ).val();
	
//	alert($(".archieve_total_count").val());
	//alert(archieve_total_count);
    $('#example').dataTable( {
		
        "processing": true,
        "bServerSide": true,
		 "bDestroy": true,
		 "autoWidth": false,
		  "searching": false,
		"iDisplayLength": 50,
		
				oLanguage: {
        sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },

		
		//var postdata = "archieve_total_count=0&from_date=01-01-2016&to_date=26-07-2016&section_id=177&status=&userid=35";
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
    .append($('<td class="BackArrow"><a href="<?php  echo base_url().folder_name; ?>/article_ageing_report" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
		   }
		   else
		   {
			   
			   $(oSettings.nTHead).show(); 
		   }
		  
		},
		
		"ajax": {
            "url": "<?php echo base_url().folder_name; ?>/article_ageing_report/article_ageing_datatable",
			"type" : "POST",
			//"data" : { "from_date" : fromdate, "to_date" : todate, "cntent_type" : cntent_type, "status":status, "section_id":section_id },
	 		//"data" : {"from_date" : fromdate, "to_date" : todate, "section_id":section_id,"status":status, "userid": userid, "archieve_total_count": $( "#sample" ).val(), "sample":$( "#sample" ).val() },
			"data"	: function(d){
					d.from_date = fromdate; 
					d.to_date = todate; 
					d.section_id = section_id; 
					d.status = status; 
					d.userid = userid; 
					d.username = get_username; 
					d.archieve_total_count =  $(".archieve_total_count").val();
					d.archieve_previous_count =  $(".archieve_previous_count").val();
				}
		
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
    	article_ageing_datatable();
 	});
	
	$("#export_excel").click(function()
	{
		var userid = "";
		
		var from_date=$( "#date_timepicker_start").val();
		var to_date=$( "#date_timepicker_end").val();
		var section_id=$( "#ddsection" ).val();
	
	
		<?php if($get_user_id != "")  { ?>
		var userid = <?php echo $get_user_id; ?>;	
		<?php  } ?>
		//alert(content_type);
		$(this).attr("href", "<?php echo base_url().folder_name;?>/article_ageing_report/article_excel?userid="+userid+"&from="+from_date+"&to="+to_date+"&section="+section_id+"");
	});
});
</script> 
		</div>
	</div>
</div>
</body>
</html>