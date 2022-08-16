    <!-- Start Stylesheets -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link href="<?php echo base_url(); ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <!-- End Stylesheets -->

<div class="Container">
<div class="BodyWhiteBG">
<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper ">
				<div class="breadcrumbs"><a href="<?php echo base_url().folder_name; ?>" class="is-template-version-saved" >Dashboard</a> > <a href="<?php echo base_url().folder_name."/cloned_widgets" ?>" class="is-template-version-saved" >Cloned Child Widgets</a></div>
 					<h2>Cloned Child Widgets</h2> 
			</div>             
		</div>
<div class="Overflow DropDownWrapper">
			<!-- Clone Note -->
               <div style = " font-size: 15px; margin: 10px 0 ;">
                <div>
                	<span class="FloatLeft"  style = "color: #1c69ad; font-weight: bold; margin-right: 5px;" >Note:  </span>
					<span class="FloatLeft" style = "color: #F30;">The Parent widgets are available in Template Designer at "Cloned widgets template" </span>
                </div>
               </div>
			   
               <!-- End Clone Note -->
			<div class="container">
				
				
			</div>

			<div class="FloatLeft TableColumn">
                <div class="FloatLeft w2ui-field">
					<select id="search_by_cloned_parent" class="controls">
						<?php
							if(count($cloned_parents) > 0){
								?>
								<option value="all">All cloned parent widgets</option>
								<?php
									foreach($cloned_parents as $widget_name){
										$selected = ($cloned_parent_instance_id == $widget_name['cloned_instance_id']) ? "selected" : "";
										echo '<option value="'. $widget_name['cloned_instance_id'] .'" '.$selected.'>'. $widget_name['widgetName'] .' ('. $widget_name['clonedfrom'] .') </option>';
										
									}
								?>
						<?php		
							}else{
								echo '<option value="">No Cloned widgets</option>';
							}
						?>
						
					</select>
				</div>
				<button class="btn btn-primary" type="button" id="clone_search_id">Search</button>
				<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>				
                <p id="srch_error" style="clear: left; color:#F00;margin:0"></p>
				
                
				
				
				
			</div>
			
			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr> 
						<th>S.No</th>
						<th style="min-width:130px !important;">Widget Name</th>                        
						<th style="min-width:140px !important;">Section Name</th>                        
						<th style="min-width:80px !important;">Page Type</th>                        
						<th>version</th>                        
                        <th style="min-width:30px !important;">No of children</th>
                       <!-- <th style="min-width:30px !important;">No of children (LIVE)</th> -->
                        <th>Image</th>
                        <th>Parent container id</th>
						<!--<th>Rendering Mode (max article)</th> -->
						<th>Published on</th>
                        <th>Published off</th>
						<th>Active status</th>					
					</tr>
				</thead>
			</table>
            <style>
			table.dataTable tr td:nth-child(2), table.dataTable tr td:nth-child(3){
				
				text-align: left;
				padding-left: 5px;
			}
			</style>
		</div>
            
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>

<script>
var base_url = "<?php echo base_url(); ?>";
var admin_url = "<?php echo base_url().folder_name; ?>"; 
var table;
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

	$('#clone_search_id').click(function(){		
		reload_dataTable();
	});
	
	$('#clear_search').click(function(){		
		$('#search_by_cloned_parent option').removeAttr('selected');
		$("#search_by_cloned_parent option:first").attr('selected','selected');
		reload_dataTable();
	});
	
	table = $('#example').DataTable( {
		oLanguage		: {
        					sProcessing: "<img src='"+ base_url +"images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    					  },
		"aoColumnDefs"	: [ { 'bSortable': false, 'aTargets': [ -1 ] } ],		
		"autoWidth"		: false,       
		"searching"		: false,		
		"fnDrawCallback": function(oSettings){
							$("html, body").animate({ scrollTop: 0 }, "slow");
							if($('span a.paginate_button').length <= 1) {
								 $("#example_paginate").hide();
						    }else{
								 $("#example_paginate").show();
							}

						  },
		"ajax"			: {
            "url"		: admin_url +"/cloned_widgets/show_cloned_children",
			"type" 		: "POST",
			"cache"		: false,
			"data" 		: function(d){
							d.search_by_cloned_parent			= $('#search_by_cloned_parent').find('option:selected').val();							
						  }
						},
			"success"	: function(result){
								//console.log(result);
							},			
		 "columns"		: [
							{ "data": "ID" 						},			
							{ "data": "Widget Name" 			},
							{ "data": "Section Name" 			},
							{ "data": "Page Type" 				},
							{ "data": "Version" 				},
							{ "data": "No of children(cms)"		},
						//	{ "data": "No of children(live)"	},
							{ "data": "Image" 					},							
							{ "data": "Parent container id"		},															
						//	{ "data": "Rendering max article"	},															
							{ "data": "Published on"			},															
							{ "data": "Published off"			},															
							{ "data": "Active status"			}
						 ]
    });
	
});


function reload_dataTable()
{	
	/////  To reload the dataTable  /////
	//$('#example').dataTable()._fnAjaxUpdate();
	$('#example').DataTable().ajax.reload();	
	
}

</script>
