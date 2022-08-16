<?php
$release_lock_css = image_url.'css/admin/';
$release_lock_js  = image_url.'js/';

?>
    <!-- Start Stylesheets -->
    <link rel="stylesheet" href="<?php echo $release_lock_css; ?>bootstrap.min_3_3_4.css">
    <link href="<?php echo $release_lock_css; ?>bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $release_lock_css; ?>jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $release_lock_css; ?>jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <!-- End Stylesheets -->

<div class="Container">
<div class="BodyWhiteBG">
<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper ">
				<div class="breadcrumbs"><a href="<?php echo base_url().folder_name; ?>" class="is-template-version-saved" >Dashboard</a> > <a href="<?php echo base_url().folder_name."/release_template_locks" ?>" class="is-template-version-saved" >Release Template Locks</a></div>
 					<h2>Release Template Locks <?php //echo 'rights:'. FPM_AddReleaseLocks.":"; ?></h2> 
			</div>             
		</div>
<div class="Overflow DropDownWrapper">
			<!-- Template lock info -->
               <div style = " font-size: 15px; margin: 10px 0 ;">
                <div>
                	<span class="FloatLeft"  style = "color: #1c69ad; font-weight: bold; margin-right: 5px;" >Note: </span>
					<span class="FloatLeft" style = "color: #F30;">Release lock option(s) are not included for the locks mapped to your account </span>
                </div>
               </div>
			   
               <!-- Template lock info -->
			<div class="container">
				
				
			</div>

			<div class="FloatLeft TableColumn">
				<div class="FloatLeft w2ui-field">
					<select id="search_by_user" class="controls">
                    	<?php 
						if(count($all_admin_users) >0){ 
							echo '<option value="">All users</option>';
							foreach($all_admin_users as $admin_user)
							{
								echo '<option value="'.$admin_user['User_id'].'">'.$admin_user['admin_user_name'].'</option>';
							}
						}
						else
						{
							echo '<option value="0">No user available</option>';
						}
						?>
					</select>
				</div>
                
                <div class="FloatLeft w2ui-field">
					<select id="search_by_lock_type" class="controls">
						<option value="">All locks </option>
						<option value="1">Template Design Lock</option>
						<option value="2">Widget Configuration Lock</option>
                        <option value="3">Widget Add article Lock</option>
                        <option value="4">Header Script Lock</option>
                        <option value="5">Advertisement widget Lock</option>
					</select>
				</div>
                
                <div class="FloatLeft w2ui-field">
					<select id="search_by_section" class="controls">
						<?php						
						if(count($section) > 0)
						{
							echo '<option value="">All Sections </option>';
							echo '<option value="10000" is_common_section = "1" is_common_article = "0" >Common Template</option>' ;
							echo '<option value="10000" is_common_section = "0" is_common_article = "1" >Common Article Page</option>' ;
							echo '<option value="10001" is_common_section = "0" is_common_article = "0" >Clone Widgets Templates</option>' ;
							foreach($section as $skey => $sec_values)
							{
								if($sec_values['Section_id'] != 10000)
								{																	
									echo '<option value="'. $sec_values['Section_id'] .'" is_common_section = "0" is_common_article = "0" class="parent-section" >'. $sec_values['Sectionname'].'</option>' ;
									if(count($sec_values['sub_section']) > 0 && $sec_values['sub_section'] != '')
									{
										foreach($sec_values['sub_section'] as $sub_key => $sub_section)
										{
											$parent_section_class = "";
											$parent_section_class = (count($sub_section['special_section']) > 0) ? "parent-section" : "";
											
											echo '<option value="'. $sub_section['Section_id'] .'" is_common_section = "0" is_common_article = "0" class="'.$parent_section_class.'" > &nbsp;  '. $sub_section['Sectionname'] .'</option>';
											if(count($sub_section['special_section']) > 0)
											{
												foreach($sub_section['special_section'] as $spl_section)
												{														
													echo '<option value="'. $spl_section['Section_id'] .'" is_common_section = "0" is_common_article = "0"> &nbsp; &nbsp; &nbsp;  '. $spl_section['Sectionname'] .'</option>';
												}
											}
										}
									}
								}								
							}
							
						}
						else
						{
							echo '<option value="-1" >No Sections Available </option>';
						}
						?>                       
					</select>
					<style>
					.parent-section {
						color: #933;
						font-size: 16px;
					}
					</style>
				</div>
                
                <div class="FloatLeft w2ui-field">
					<select id="search_by_section_type" class="controls">						
                        <option value="">All Pages</option>
                        <option value="1">Section Page</option>
                        <option value="2">Article Page</option>
					</select>
				</div>
                
				
				<!--<div class="FloatLeft TableColumnSearch">
					<input type="search" placeholder="Search" class="SearchInput" id="search_text" >
				</div>-->
				<button class="btn btn-primary" type="button" id="article_search_id">Search</button>
				<!--<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>-->
				<button class="btn btn-primary" type="button" id="release_all_lock">Release All</button>
                <button class="btn btn-primary" type="button" id="release_lock">Release Selected</button>
                <button class="btn btn-primary" type="button" id="selectAll">Select All</button>
                <p id="srch_error" style="clear: left; color:#F00;margin:0"></p>
				
                
				
				
				
			</div>
			<!--<form id="breaking_news_manager" name="breaking_news_manager" onsubmit="return false" method="post">-->
			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr> 
						<th>S.No</th>
						<th>User Name</th>                        
                        <th>Lock Type</th>
                        <th>Section Name</th>
                        <th>Widget Name</th>
						<th>Page Type</th>
                        <th>Version</th>
						<th>Design Saved Status</th>                        					
						<th>Action</th>
					</tr>
				</thead>
			</table>
            <!--</form>-->
		</div>
            
</div>
</div>

<script type="text/javascript" src="<?php echo $release_lock_js; ?>jquery-1.7.2.min.js" ></script> 
<script type="text/javascript" src="<?php echo $release_lock_js; ?>jquery-ui.min-1.8.18.js"></script>
<script type="text/javascript" src="<?php echo $release_lock_js; ?>moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo $release_lock_js; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $release_lock_js; ?>jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $release_lock_js; ?>jquery.dataTables.js"></script>

<script>
var base_url = "<?php echo base_url(); ?>";
var admin_url = "<?php echo base_url().folder_name; ?>"; 
var table;
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

<script type="text/javascript">


/////  Show Toastr message  /////
function show_toastr(message, toastr_type)
{
	if(message != ''){
	///////  toastr_type = 1 means success message, toastr_type = 2 means Failure message /////
	(toastr_type == 1) ? toastr.success(message) : (toastr_type == 2)? toastr.error(message) : toastr.info(message);
	}
}	

$(document).ready(function(){		
	toastr.options = {
					  "closeButton": false,					  
					  "newestOnTop": true,					  
					  "positionClass": "toast-top-center",
					  /*"showDuration": "10000",
					  "hideDuration": "10000",
					  "timeOut": "50000",
					  "extendedTimeOut": "10000"*/					  
					};

	$('#article_search_id').click(function(){		
		reload_dataTable();
	});
	
	table = $('#example').DataTable( {
		oLanguage		: {
        					sProcessing: "<img src='<?php echo image_url; ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    					  },
	 	"paging"		: false,
		"aoColumnDefs"	: [ { 'bSortable': false, 'aTargets': [ -1 ] } ],		
		"autoWidth"		: false,       
		"searching"		: false,		
		"fnDrawCallback": function(oSettings){
							
							var avilable_checkboxes = $("input[type='checkbox'][name='release_check_id']").length;										
							if(avilable_checkboxes == 0){
								$('#selectAll').text('Select All');
								$('#selectAll').hide();
								$('#release_all_lock').hide();
								$('#release_lock').hide();
							}
							else
							{								
								$('#selectAll').show();
								$('#release_all_lock').show();
								$('#release_lock').show();
							}
							// Release Individual locks
						 	$('.individual_release_lock').click(function(){
								var all_avilable_checkboxes = $("input[type='checkbox'][name='release_check_id']");
								all_avilable_checkboxes.prop('checked', false);
								var checked_boxes = $('.release_check_box:checkbox:checked').length;
								if(all_avilable_checkboxes.length == checked_boxes)
								{
									$('#selectAll').addClass('checkedAll');
									$('#selectAll').text('Un Select All');
								}
								else
								{
									$('#selectAll').removeClass('checkedAll');
									$('#selectAll').text('Select All');
								}
								
								$('#release_check_id_'+this.id).prop('checked', true);
								release_selected_locks(false, '');
							});
							
							$('.release_check_box').click(function(){								
								var all_avilable_checkboxes = $("input[type='checkbox'][name='release_check_id']").length;
								var checked_boxes = $('.release_check_box:checkbox:checked').length;
								if(all_avilable_checkboxes == checked_boxes)
								{
									$('#selectAll').addClass('checkedAll');
									$('#selectAll').text('Un Select All');
								}
								else
								{
									$('#selectAll').removeClass('checkedAll');
									$('#selectAll').text('Select All');
								}
							});						  	
						  },
		
		"ajax"			: {
            "url"		: admin_url +"/release_template_locks/show_locks",
			"type" 		: "POST",
			"cache"		: false,
			"data" 		: function(d){
							d.search_by_user			= $('#search_by_user').find('option:selected').val();
							d.search_by_lock_type		= $('#search_by_lock_type').find('option:selected').val();
							d.search_by_section			= $('#search_by_section').find('option:selected').val();
							d.search_by_section_type	= $('#search_by_section_type').find('option:selected').val();							
						  }
						},
			"success"	: function(result){
								//console.log(result);
							},			
		 "columns"		: [
							{ "data": "ID" 						},			
							{ "data": "User Name" 				},
							{ "data": "Lock Type" 				},
							{ "data": "Section Name" 			},
							{ "data": "Widget Name" 			},
							{ "data": "Page Type" 				},
							{ "data": "Version" 				},							
							{ "data": "Design Commit Status"	},															
							{ "data": "Action"					}
						 ]
    });
	
	$('#release_lock').click(function(){
		release_selected_locks(true, '');
	});	
	
	$('#release_all_lock').click(function(){
		release_selected_locks(true, 'release_all_at_once');		
	});	
	
});

function release_selected_locks(all_checked, release_all_at_once)
{
   if(release_all_at_once == 'release_all_at_once')
   {
		var all_avilable_checkboxes = $("input[type='checkbox'][name='release_check_id']").length;
		var checked_boxes = $('.release_check_box:checkbox:checked').length;
		if(all_avilable_checkboxes != 0)
		{ 
		  $('input').prop('checked', true);
		  $('#selectAll').addClass('checkedAll');
		  $('#selectAll').text('Un Select All');
		}
   }
   var save_url 				= admin_url + "/release_template_locks/release_selected_locks";
   var confirm_string 			= (all_checked) ? "Are sure you want to release selected locks?" : "Are sure you want to release selected lock?" ;
   var loading_string 			= "Please wait...";   
   var release_required_values 	= [];  

   var checkIds 				= [];
   table.$('tr').each(function(index,rowhtml){
		checked				= $('input[type="checkbox"][name^=release_check_id]:checked',rowhtml).length;
		newcheckbox_checked = $('input[type="checkbox"][name^=release_check_id]:checked',rowhtml);
		if (checked == 1){	
			checkIds.push(newcheckbox_checked.val());	
			checked_checkbox_val 	= newcheckbox_checked.val();
			id 						= checked_checkbox_val;	
			var LockType 			= $('#lock_type_' + id).val();
			var SectionId 			= $('#section_id_' + id).val();
			var SectionType 		= $('#page_type_' + id).val(); // 1->Section Page, 2->Article Page
			var InstanceId 			= $('#instance_id_' + id).val();
			var SectionVersionId 	= $('#version_id_' + id).val();
			var designcommit_status = $('#designcommit_status' + id).val();
			var locked_userId 		= $('#locked_userId' + id).val();
			release_required_values.push({
				release_lock_type 				: LockType,
				release_lock_section_id 		: SectionId,
				release_lock_section_type 		: SectionType,
				release_lock_instance_id 		: InstanceId,
				release_lock_section_version_id : SectionVersionId,
				locked_userId					: locked_userId
				//release_lock_design_commit_status: designcommit_status
				
			});
		}
   });
  // console.log(release_required_values);
//return false;
	var checkbox = checkIds;	
	if (checkbox.length == 0) 
	{ 
		show_toastr('Plese select atleast one check box to release lock', 2); 		
		// cancel submit
		return false;
	} 	
	else 
	{ 
		var need_to_confirm = confirm(confirm_string);
		if(need_to_confirm)
		{
			    $.ajax({
					url			: save_url,
					type		: 'post',					
					data		: { "release_required_values": release_required_values},
					dataType	: 'json',
					async		: false,
					beforeSend	: function() {
										// setting a timeout					
										$("#loading_msg").html(loading_string);
										$("#commom_loading").show();
									},
					success		: function(result){ 
										$("#commom_loading").hide();
										$("#loading_msg").html("");										
										if(result.show_msg == 1){
											show_toastr(result.msg, result.msg_type);
										}
										
										if(result.access_rights != 2){										
											/////  To reload the dataTable  /////
											reload_dataTable();		
										}
										else
										{
											setTimeout("window.location.reload()", 3000 ) // after 2 seconds
											//window.location.reload();
										}
									},
					error		: function(){
								  	$("#commom_loading").hide();
									$("#loading_msg").html("");	
								  }												
				});			
			   return false;
		}	  	   
	}

}

function reload_dataTable()
{
	/////  To reload the dataTable  /////
	$('#example').DataTable().ajax.reload();
	var avilable_checkboxes = $("input[type='checkbox'][name='release_check_id']").length;											
	if(avilable_checkboxes == 0){
		$('#selectAll').text('Select All');
		$('#selectAll').hide();
		$('#release_all_lock').hide();
		$('#release_lock').hide();
	}
	else
	{
		$('#selectAll').show();
		$('#release_all_lock').show();
		$('#release_lock').show();
	}
}
$('#selectAll').click(function(e) {
    if($(this).hasClass('checkedAll')) {
      $('input').prop('checked', false);   
      $(this).removeClass('checkedAll');
	  $(this).text('Select All');
    } else {
      $('input').prop('checked', true);
      $(this).addClass('checkedAll');
	  $(this).text('Un Select All');
    }
});

</script>
