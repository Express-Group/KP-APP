<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
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


<script type="text/javascript">var foldername = "<?php echo folder_name; ?>";</script>
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
			<p class="FloatRight SaveBackTop remoda1-bg"><a href="#" id="export_excel" target="_blank" type="button" class="btn-primary btn"><i class="fa fa-plus-circle"></i>Export to CSV</a></p>
		</div>
		<div class="Overflow DropDownWrapper">
			<?php 
			if($this->session->flashdata("success"))
			{     
			?>
			<div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("success");?></div>
			<?php }	?>
			<?php 
			if($this->session->flashdata("success_delete"))
			{     
			?>
			<div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("success_delete");?></div>
			<?php }	?>
			<?php 
			if($this->session->flashdata("fail_delete"))
			{     
			?>
			<div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("fail_delete");?></div>
			<?php }	?>
			<div class="container">
				<div id="" class="row AskPrabuCheckBoxWrapper">
					<ul class="AskPrabuCheckBox FloatLeft">
						<li class="has-pretty-child">
							<!--<div class="clearfix prettycheckbox labelright  red">
								<input type="checkbox" id="search_based_check"  class="myClass" value="yes" name="answer">
								<a class=" " href="#"></a>
								<label for="search_based_check">Search Based on Date Range</label>
							</div>-->
							<!--<a href="#" class=""></a>--> </li>
						<li>
							<p class="CalendarWrapper"  id="checkin_checkout_div">
								<label for="search_based_check">Search Based on Date Range</label>
								<input type="text" value="" id="date_timepicker_start" placeholder="Start Date">
								<input type="text" value="" id="date_timepicker_end" placeholder="End Date">
							</p>
						</li>
					</ul>
				</div>
			</div>
			<div class="FloatLeft Module02">
				<div class="FloatLeft w2ui-field">
					<label>Section</label>
					<select id="ddsection" class="controls">
					<option value="">All</option>
						<?php if(isset($section_mapping)) { 
				 foreach($section_mapping as $mapping) {   
				 
				 
				$condition = $mapping['Sectionname'] != 'Galleries' && $mapping['Sectionname'] != 'Videos' && $mapping['Sectionname'] != 'Audios' &&  $mapping['Sectionname'] != 'Resources';
				 
				 //if($condition) {
					 
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
						<?php  } } ?>
					</select>
				</div>
				<div class="FloatLeft w2ui-field">
					<label>Type</label>
					<select id="ddcontent_type" name="ddcontent_type" placeholder="Sort By: All" class="controls">
						<!--<option value="">- Status -</option>-->
						<option value="1"> Article</option>
						<option value="3">Gallery</option>
						<option value="4">Video</option>
						<option value="5">Audio</option>
					</select>
				</div>
				<div class="FloatLeft w2ui-field">
					<label>Status</label>
					<select id="ddStatus" name="ddStatus" class="controls">
						<option value="">All</option>
						<option value="P">Published</option>
						<option value="U">Unpublished</option>
					</select>
				</div>
				
				<!--<P class="FloatLeft">
					<input type="search" placeholder="Search" class="SearchInput" name="txtSearch" id="txtSearch">
				</P>--> 
				
				<!--<i  id="top_content_search" class="fa fa-search FloatLeft" ></i>
<button class="btn-primary btn margin-left-50" type="button" id="clear_search">Clear Search</button>-->
				
				<button class="btn btn-primary" type="button" id="top_content_search">Search</button>
				<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
			</div>
			<p id="srch_error" class="CheckError" style=" padding-right: 631px !important;"></p>
			<div id="container_datatable" class="display"  style="width:100%; float:left; ">
				<div id="work_list" class="display">
					<div class="role-dpt">
						<table id="example" class="display" cellspacing="0" width="100%">
							<thead>
								<tr> 
									<!--<th>User ID</th>-->
									<th>Title</th>
									<th>Status</th>
									<th>Section</th>
									<th>Sub Section</th>
									<th>Published On</th>
									<th>Created By</th>
									<th>Type</th>
									<th>Total hits</th>
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
	
	top_content_datatable();
	
	$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 top_content_datatable();
		  }  
		}
	});
	
	$("#clear_search").click(function() {
	$("#ddcontent_type").val('1');
	$("#ddsection").val('');
	$("#ddStatus").val('');
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');
	
	top_content_datatable();
	});

	});
	
		
	function top_content_datatable() {
		
	var fromdate=$( "#date_timepicker_start").val();
	var todate=$( "#date_timepicker_end").val();
	var cntent_type=$( "#ddcontent_type" ).val();
	var status=$( "#ddStatus" ).val();
	
	var section_id=$( "#ddsection" ).val();
	
	
	
    $('#example').dataTable( {
		 
		
		/*fnInitComplete : function() {
      if ($(this).find('tbody tr').length<=1) {
         $(this).parent().hide();
      }
   } */
		
		
        "processing": true,
        "bServerSide": true,
		 "bDestroy": true,
		 "autoWidth": false,
		  "searching": false,
		"iDisplayLength": 50,
		
				oLanguage: {
        sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },

		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [-1] }
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
    .append($('<td class="BackArrow"><a href="<?php  echo base_url().folder_name; ?>/top_content_report" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
		   }
		   else
		   {
			   
			   $(oSettings.nTHead).show(); 
		   }
		  
		},
		
		"ajax": {
            "url": "<?php echo base_url().folder_name; ?>/top_content_report/top_content_datatable",
			"type" : "POST",
			"data" : function(d){
				d.from_date =  fromdate;
				d.to_date =  todate;
				d.cntent_type =  cntent_type;
				d.status = status;
				d.section_id = section_id;
				d.archieve_total_count =  $(".archieve_total_count").val();
				d.archieve_previous_count =  $(".archieve_previous_count").val();
			},
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
				$("#top_content_search").click();
			}
			
		});
	
	
	$("#top_content_search").click(function()
 	{
	// console.log('ppp');
  		if($('#ddcontent_type').val() != 'All')
  		{
   			if($('#txtSearch').val() == '')
   			{
    			$("#srch_error").html("Please enter text to search");
    			return false;
   			}
   			else
   			{
    			top_content_datatable();
    			$("#srch_error").html("");
   			}
  		}
  		else
  		{
	 		$("#srch_error").html("");
   			top_content_datatable();
  		}
 	});
	
	$("#export_excel").click(function()
	{
		var content_type = $("#ddcontent_type").val();
		
		var status = $("#ddStatus").val();
		
		var from_date=$( "#date_timepicker_start").val();
		var to_date=$( "#date_timepicker_end").val();
	
		//alert(content_type);
		$(this).attr("href", "<?php echo base_url().folder_name;?>/top_content_report/top_content_excel?type="+content_type+"&status="+status+"&from="+from_date+"&to="+to_date+"");
	});
});
</script> 
			<script type="text/javascript">
$(document).ready(function()
{
<?php if($this->session->flashdata('success')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});
<?php } ?>
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
		</div>
	</div>
</div>
</body>
</html>