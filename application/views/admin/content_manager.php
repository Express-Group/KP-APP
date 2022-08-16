<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	
<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href="<?php echo base_url(); ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" />-->
 <!-- tool tip begins-->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.style-my-tooltips.js"></script>

<script>
  jQuery.noConflict();
  (function($){
   $(document).ready(function(){
    $("[title]").style_my_tooltips({ 
     tip_follows_cursor:false, //boolean
     tip_delay_time:700, //milliseconds
     tip_fade_speed:500 //milliseconds
    });
    //dynamically added elements demo function
    $("a[rel='add new element']").click(function(e){
     e.preventDefault();
     $(this).attr("title","Add another element").parent().after("<p title='New paragraph title'>This is a new paragraph! Hover to see the title.</p>");
    });
   });
  })(jQuery);
 </script>
 <!-- tool tip ends -->    
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>    
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<!--calendar begind-->
<link href="<?php echo base_url(); ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<?php /*?><script src="<?php echo base_url(); ?>js/jquery.js"></script><?php */?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.js"></script>
 
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

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/w2ui-fields-1.0.min.js"></script>



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

<?php if(defined("USERACCESS_ADD".$Menu_id) && constant("USERACCESS_ADD".$Menu_id) == 'Y') { ?>
 <p class="FloatRight SaveBackTop"><a href="<?php echo base_url().$addPage_url;?>" class="btn-primary btn"><i class="fa fa-plus-circle"></i> &nbsp;<?php echo $btn_name; ?></a></p>
 <?php } ?>
 
<?php if($this->uri->segment(2) != 'image_manager') { ?>
 
<?php if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 'Y') { ?>
   <p class="FloatRight SaveBackTop PubUnpublish"><button name="btnPublish" id="publish_button" title="Publish" type="button" class="btn btn-primary FloatRight i_button"><i style="" class="fa fa fa-flag"></i></button></p>
<?php } ?>
   <?php if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 'Y') { ?>
   <p class="FloatRight SaveBackTop PubUnpublish">
<button class="btn btn-primary FloatRight i_button FlagIcon2" id="unpublish_button"  title="Unpublish"></button></p>
<?php } ?>
<?php  if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == 'Y') { ?>
<!-- <p class="FloatRight SaveBackTop PubUnpublish"><button name="btnRestore" id="restore_button" title="Restore" type="button" class="btn btn-primary FloatRight i_button"><i style="" class="fa fa fa-refresh"></i></button></p> -->
 
   <p class="FloatRight SaveBackTop PubUnpublish"><button name="btnTrash" id="trash_button" title="Trash" type="button" class="btn btn-primary FloatRight i_button"><i style="" class="fa fa fa-ban"></i></button></p>
<?php } ?>
  
<?php } ?>

</div>


<div class="Overflow DropDownWrapper">



 
 
<div class="container">

  <div class="row AskPrabuCheckBoxWrapper">
      
     
      
      <ul class="AskPrabuCheckBox FloatLeft">
      
     <li>
        
<input type="checkbox" id="search_based_date" value="yes">
<label class="include_label HeadTopAuto"  for="search_based_date">Search Based on Date Range</label>
    </li>
    <li id="checkin_checkout_div">
    <p class="CalendarWrapper" >
		<input type="text" placeholder="Start Date" id="date_timepicker_start" value="">
		<input type="text" placeholder="End Date" id="date_timepicker_end" value="">
    </p>
    </li>

         
        </ul>
       
    </div>
  
   
</div>

<div class="FloatLeft TableColumn">  

<div class="FloatLeft w2ui-field">

<?php if($this->uri->segment(2) != 'image_manager') { ?>

 <select id="article_status" class="controls">
        <option value="">Status: All</option>
        <option value="D" >Draft</option>
        <option value="A" >Ready for Approval</option>
        <option value="P" >Published</option>
        <option value="U" >Unpublished</option>
      <!--  <option value="T" >Trash</option> -->
    </select>	

<?php } else { ?>

 <select id="article_status" class="controls">
        <option value="">Status: All</option>
        <option value="P" >Active</option>
        <option value="U" >InActive</option>
        <option value="T" >Trash</option>
    </select>	  
  
  <?php } ?>
  
</div>

<div class="FloatLeft w2ui-field">
  <select id="search_by" class="controls">
        <option value="">Search By: All</option>
        <option value="Title" >Title</option>
        <option value="created_by" >Created By</option>
        <option value="ByLine" >Byliner</option>
        
    </select>
</div>

<div class="FloatLeft w2ui-field">
    <select id="article_section" class="controls">
        <option value="">Section: All</option>
		<?php if(isset($section_mapping)) { 
                       foreach($section_mapping as $mapping) {  ?>
                       <?php if(!(empty($mapping['sub_section']))) { ?>
                       
     <option class="blog_option" style="color:#933;font-size:18px;" label="<?php echo $mapping['Sectionname']; ?>" value="<?php echo $mapping['Section_id']; ?>">   </option>
                    
                              
                              <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
                <option  value="<?php echo $sub_mapping['Section_id']; ?>">&nbsp;&nbsp;<?php echo $sub_mapping['Sectionname']; ?></option>
                                    <?php } ?>
                              
                            <?php   } else {
								if($mapping['Sectionname'] != 'Home') { ?>
							
                            <option  class="blog_option" style="color:#933;font-size:18px;" value="<?php echo $mapping['Section_id']; ?>">&nbsp;&nbsp;<?php echo $mapping['Sectionname']; ?></option>
								<?php } } } }?>
    </select>
</div>

<div class="FloatLeft TableColumnSearch"><input type="search" placeholder="Search" class="SearchInput" id="search_text" ></div>
<button class="btn btn-primary" type="button" id="article_search_id">Search</button>
<button class="btn btn-primary" id="clear_search">Clear Search</button>

<p id="srch_error" style="clear: left; color:#F00;margin:0"></p>
</div>


<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<!--<th>ID</th>-->
						<th>Title</th>
						<th>Section</th>
						<th>Parent-Section</th>
                        <!--<th>By Line</th>-->
                        <?php if($this->uri->segment(2) != 'image_manager') { 
						$asc = 6;
						?>
                        <th>Image</th>
                        <?php } else { $asc = 5; } ?>
						<th>Author</th>
                        <th>Created By</th>
                        <th>Modified On</th>
						<th>Status</th>
                        <th>Hits</th>
                        <?php if((defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 'Y') || (defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 'Y' ) ||   (defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 'Y' ) ) { ?>
                        <th>Action</th>
                        <?php } ?>
					</tr>
				</thead>
	</table>
    
</div>


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
				var version_data = { 'contentversion_id' : []};
				
				 $.each(publish_object,function(key,value){
							 content_data['content_id'].push(value.content_id);
						 version_data['contentversion_id'].push(value.contentversion_id);
				});
			
				$.ajax({ 
					url:'<?php echo base_url()."niecpan/common/publish_content";?>',
					type:"POST",
					data:{"contentids" : content_data,"contentversionids" : version_data},
					success: function(data) 
						{
							clear_all_checkbox();
							article_datatables();
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
				var version_data = { 'contentversion_id' : []};
				
				 $.each(unpublish_object,function(key,value){
						 content_data['content_id'].push(value.content_id);
						 version_data['contentversion_id'].push(value.contentversion_id);
				});
			
				$.ajax({ 
					url:'<?php echo base_url()."niecpan/common/unpublish_content";?>',
					type:"POST",
					data:{"contentids" : content_data,"contentversionids" : version_data},
					success: function(data) 
						{
							clear_all_checkbox();
							article_datatables();
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

	function article_datatables() {
		
		 $("#example_length").hide();
		 
	var Section 	= $("#article_section").val();
	var Search_text = $("#search_text").val();
	var SearchBy	= $("#search_by").val();
	var check_in	= $("#date_timepicker_start").val();
	var check_out   = $("#date_timepicker_end").val();
	var Status		= $("#article_status").val();
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
		"iDisplayLength": 10,
		 "order": [[ <?php echo $asc; ?> , "desc" ]],
		"fnDrawCallback":function(oSettings){
   
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
            "url": "<?php echo base_url(); ?>niecpan/common/content_datatable",
			"type" : "POST",
			"data" : {
		 "Search_by" : SearchBy, "Section" : Section, "Search_text" : Search_text, "check_in" : check_in, "check_out" : check_out, "Status" : Status, "Page_name" : page_name  
			}
		 }
    } );
	
	 
		
	}
			article_datatables();
			
	
	$('#search_text').keypress(function (e) {
		if($.trim($('#search_text').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 clear_all_checkbox();
			 article_datatables();
		  }  
		}
	});
	$("#clear_search").click(function() {
	$("#article_section").val('');
	$("#search_text").val('');
	$("#search_by").val('');
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');
	$("#article_status").val('');
	
	clear_all_checkbox();
	
	 article_datatables();
	});
	
	$("#article_search_id").click(function()
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
				
				article_datatables();
				$("#srch_error").html("");
			}
		}
		else
		{
			article_datatables();
			$("#srch_error").html("");
		}
		
		clear_all_checkbox();
	});
		
			$("#checkin_checkout_div").hide();
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
				var boolean_value 	= false;
				var id 				= $(this).attr('content_id');	
				var set_status 		= $(this).attr('status');	
				var set_name		= $(this).attr('name');	
				
				 if(set_status == 'U') {
					 
					var publish_status = confirm("Are you sure you want to publish the "+alert_name+"  - "+set_name+"?");
					
						if(publish_status == true) {
							boolean_value = true;
						}
				} else if(set_status == 'P'){
					
					var unpublish_status = confirm("Are you sure you want to unpublish the "+alert_name+"  - "+set_name+"?");
					
						if(unpublish_status == true) {
							boolean_value = true;
						}
				}
				 
				 if(boolean_value == true) {
				 
							
				$.ajax({ 
				url:'<?php echo base_url()."niecpan/common/changestatus";?>',
				type:"POST",
				data:{"contentid":id, "status":$(this).attr('status')},
				success: function(data) 
					{
						if(data == 'success')
						{
							if(set_status == 'U')
							{
									$("#status"+id).removeClass('fa-caret-right').addClass('fa-pause');
									$("#status"+id).parent('a').attr('status', 'P');
									$("#status"+id).parent('a').attr('title', 'Unpublish');
									
									$("#status_img"+id).removeClass('fa-times').addClass('fa-check');
									$("#img_change"+id).attr('title', 'Published');
									
									$("#change_trash").attr('change_status'+id, 'P');
									
									article_datatables();
							}
							else if(set_status == 'P')
							{
									$("#status"+id).removeClass('fa-pause').addClass('fa-caret-right');
									$("#status_img"+id).removeClass('fa-check').addClass('fa-times');
									
									$("#status"+id).parent('a').attr('title', 'Publish');
									$("#status"+id).parent('a').attr('status', 'U');;
									$("#img_change"+id).attr('title', 'Unpublished');
									
									$("#change_trash").attr('change_status'+id, 'U');
									
									article_datatables();
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
				url:'<?php echo base_url()."niecpan/common/changestatus";?>',
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
								article_datatables();
						}
						else if(set_status == 'P')
						{
								$("#status"+id).removeClass('fa fa-times').addClass('fa fa-check');
								$("#status_img"+id).removeClass('fa-check').addClass('fa-times');
								$("#status"+id).parent('a').attr('title', 'Active');
								
								$("#status"+id).parent('a').attr('status', 'U');;
								$("#img_change"+id).attr('title', 'Inactive');
								
								$("#change_trash").attr('change_status'+id, 'U');	
								article_datatables();
						}
					}
	   
  				}  
   });
 
	}
			 });
			 
		
		
		 $(document.body).on('click', '#change_trash, #change_restore', function(event)
		 {
				var id 				= $(this).attr('content_id');	
				var set_status 		= $(this).attr('status');	
				var set_name		= $(this).attr('name');	
				
				if(set_status=="T")
					var publish_status = confirm("Are you sure you want to move the "+alert_name+"  - "+set_name+" to trash?");
				else if(set_status=="R")
						var publish_status = confirm("Are you sure you want to restore the "+alert_name+"  - "+set_name+" from trash?");
						
				if(publish_status == true) 
				{
					$.ajax({ 
					url:'<?php echo base_url()."niecpan/common/changestatus";?>',
					type:"POST",
					data:{"contentid":id, "status":$(this).attr('status'), "prev_status":$(this).attr('change_status'+id)},
					success: function(data) 
						{	
							if(data == 'success')
							{
								
								article_datatables();
							}
						}  
					});
			}
		});
		
		
		$("#trash_button").click(function() {
			
			var  publishCheckbox = false;
			var  UnpublishCheckbox = false; 
			var  TrashCheckbox = false; 
			var RestoreCheckbox = false; 
		
			if(parseInt(Object.keys(publish_object).length) != 0) { 
				  publishCheckbox = true;
			 }	
			 if(parseInt(Object.keys(unpublish_object).length) != 0) { 
				  UnpublishCheckbox = true; 
			 }	
			 
			/* if($("input:checkbox[name='trash_checkbox[]']:checked").length != 0) {
				 TrashCheckbox = true; 
			 }
			
			if($("input:checkbox[name='restore_checkbox[]']:checked").length != 0) 
			{
				RestoreCheckbox = true; 
			}
			*/
		//	if(( publishCheckbox == true ||  UnpublishCheckbox == true || TrashCheckbox == true )&& RestoreCheckbox == false)
			
			if(publishCheckbox == true ||  UnpublishCheckbox == true )
			{
			var trash_status = confirm("Are you sure you want to move the selected content to trash?");
					
			if(trash_status == true) {
			
				$("#normal_loading").show();
				var content_data = { 'content_id' : []};
				var changeStatus = { 'change_status' : []};
				
			/*	$("input:checkbox[name='trash_checkbox[]']:checked").each(function(){
						 content_data['content_id'].push($(this).val());
						 changeStatus['change_status'].push($(this).attr('status'));
				}); */
				
				$.each(publish_object,function(key,value){		 
						 content_data['content_id'].push(value.content_id);
						 changeStatus['change_status'].push(value.status);
				});
				
				 $.each(unpublish_object,function(key,value){
						 content_data['content_id'].push(value.content_id);
						 changeStatus['change_status'].push(value.status);
				});
			
				$.ajax({ 
					url:'<?php echo base_url()."niecpan/common/trash_content";?>',
					type:"POST",
					data:{"contentids" : content_data, "Change_Status":changeStatus},
					success: function(data) 
						{
							clear_all_checkbox();
							article_datatables();
							$("#normal_loading").hide();
						}
				});  
			}
		} 
		else {
			alert('Please select trash status checkbox');
		}
	});
	
	
	
	
	$("#restore_button").click(function() {
			
			
			var  publishCheckbox = false;
			var  UnpublishCheckbox = false; 
			var  TrashCheckbox = false; 
			var RestoreCheckbox = false; 
		
			if($("input:checkbox[name='publish_checkbox[]']:checked").length != 0) { 
				  publishCheckbox = true;
			 }	
			 if($("input:checkbox[name='unpublish_checkbox[]']:checked").length != 0) { 
				  UnpublishCheckbox = true; 
			 }	
			 
			 if($("input:checkbox[name='trash_checkbox[]']:checked").length != 0) {
				 TrashCheckbox = true; 
			 }
			if($("input:checkbox[name='restore_checkbox[]']:checked").length != 0) 
			{
				RestoreCheckbox = true; 
			}
			
			if(publishCheckbox == false && UnpublishCheckbox == false && TrashCheckbox == false && RestoreCheckbox == true)
			{
					var restore_status = confirm("Are you sure you want to restore the selected content from trash?");
							
					if(restore_status == true) {
						$("#normal_loading").show();
						var content_data = { 'content_id' : []};
						var changeStatus = { 'change_status' : []};
						
						$("input:checkbox[name='restore_checkbox[]']:checked").each(function(){
								 content_data['content_id'].push($(this).val());
								 changeStatus['change_status'].push($(this).attr('previous_status'));
						});
						
						$.ajax({ 
							url:'<?php echo base_url()."niecpan/common/restore_content";?>',
							type:"POST",
							data:{"contentids" : content_data, "Change_Status":changeStatus},
							success: function(data) 
								{
									article_datatables();
									$("#normal_loading").hide();
								}
						});  
					}
		} else {
			alert('Please select restore status checkbox');
		}
	});
	
		$('body').keypress(function (e) {
			
			if(e.which == 13) {
				$("#article_search_id").click();
			}
			
		});
		
	});
	
	
	
</script>




       
      
                            
</div>                            
</div>                       
  
