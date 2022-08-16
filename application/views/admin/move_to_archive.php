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

 <div id="flash_msg_id" class="FloatLeft SessionError" style="display:none"></div>
 
</div>

<div class="Overflow DropDownWrapper">

<div class="container">

<form name="archive_form" action="<?php echo base_url().folder_name.'/move_to_archive/flat_move_to_archive'; ?>" method="POST">
  
<div class="FloatLeft TableColumn">  
<div class="FloatLeft w2ui-field">
 	 <select  id="article_Type" name="content_type" class="controls">
                              <?php if(isset($get_content_type)) {
						  foreach($get_content_type as $type) { 
						  if($type->contenttype_id == 1 || $type->contenttype_id == 3  || $type->contenttype_id == 4  || $type->contenttype_id == 5 || $type->contenttype_id == 6) { ?>
						  	 <option <?php if($type->contenttype_id == 1){ ?> selected="selected" <?php } ?> value="<?php echo $type->contenttype_id; ?>"><?php echo $type->ContentTypeName; ?></option>
                              <?php } ?>
	  <?php }  } ?>
 </select>
</div>
<div class="FloatLeft TableColumnSearch"><input type="text" name="start_date" placeholder="Start Date"  class="SearchInput" id="date_timepicker_start" value="" readonly></div>
<div class="FloatLeft TableColumnSearch"><input type="text" name="end_date" placeholder="End Date"  class="SearchInput" id="date_timepicker_end" value="" readonly></div>
<button class="btn btn-primary" type="button"  id="article_search_id">Search</button>
<button class="btn btn-primary" type="submit" style="display:none;"  id="move_to_archive_id">Move To Archive</button>
</div>
</form>
  </div>
  
 <div id="example_wrapper" class="dataTables_wrapper no-footer"><div id="example_processing" class="dataTables_processing" style="display: none;"><img src="http://localhost:81/newniecms/images/admin/loadingroundimage.gif" style="width:40px; height:40px;"></div><table id="example" class="display no-footer dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_info">
				<thead>
					<tr role="row"><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 20%;" aria-label="HEADLINE: activate to sort column ascending">HEADLINE</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="BREADCRUMB : activate to sort column ascending">BREADCRUMB </th><th class="sorting_desc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="PUBLISHED DATE: activate to sort column ascending" aria-sort="descending">PUBLISHED DATE</th></tr>
				</thead>
	<tbody><tr class="odd"><td valign="top" colspan="3" class="dataTables_empty">No data available in table</td></tr></tbody></table></div>
  
</div>
</div>                            
</div> 
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>

<!--calendar begind-->
<script  type="text/javascript">


<?php if($this->session->flashdata("success")) {  ?>
Flash_message("<?php echo $this->session->flashdata("success"); ?>","SessionSuccess");
<?php } ?>

<?php if($this->session->flashdata("error")) {  ?>
Flash_message("<?php echo $this->session->flashdata("error"); ?>","SessionError");
<?php } ?>

function move_to_archive_datatables() {
		
		 $("#example_length").hide();
		 
	var content_type = $("#article_Type").val();
	var check_in	 = $("#date_timepicker_start").val();
	var check_out    = $("#date_timepicker_end").val();
	
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
		"order": [[ 2 , "desc" ]],
		"columnDefs": [
		{ "width": "20%", "targets": 0 }],
		"fnDrawCallback":function(oSettings){
   
		$("html, body").animate({ scrollTop: 0 }, "slow");
   
		   if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();
		   } else {
			 $("#example_paginate").show();
		   }
		  
		   if($(this).find('tbody tr').text()== "No data available in table")
			{
			 //$(oSettings.nTHead).hide(); 
			 //$('.dataTables_info').hide();
			 $("#move_to_archive_id").hide();
			 }
			 else
			 {
				$("#move_to_archive_id").show();
			  $(oSettings.nTHead).show(); 
			 }
	 
		},
		
		"ajax": {
            "url": "<?php echo base_url().folder_name; ?>/move_to_archive/move_to_archive_datatable",
			"type" : "POST",
			"data" : {
			"content_type" : content_type, "check_in" : check_in, "check_out" : check_out
			}
		 }
    } );
	
	 
		
	}
			//move_to_archive_datatables();
			
$("#move_to_archive_id").click(function(){			
if(confirm("Are you want for move to archive")) {
	$("#loading_msg").html("Please wait...Archive is in process");
	$("#commom_loading").show();
	return true;
} else {
	return false;
}

	
});

$("#article_search_id").click(function(){
	
	var check_in	 = $("#date_timepicker_start").val();
	var check_out    = $("#date_timepicker_end").val();
	
	if($.trim(check_in) != '' && $.trim(check_out) != '') {
		move_to_archive_datatables();
	} else {
		Flash_message("Please choose the date","SessionError");
	}
	//$("#loading_msg").html("Please wait, Processing your inputs");
	//	$("#commom_loading").show();
});

function Flash_message(msg,type) {
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