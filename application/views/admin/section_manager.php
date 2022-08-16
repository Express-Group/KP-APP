<link href="<?php echo image_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
--><!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.2.min.js"></script>-->
<!--<link href="<?php echo image_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />-->
<link href="<?php echo image_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />






<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
--><?php /*?><link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css"><?php */?>


<!--<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
-->
<!--<script type="text/javascript" src="<?php echo base_url(); ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap.min.js"></script>-->
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.datetimepicker.js"></script>
     


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


<!--<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.remodal.js"></script>
<script src="<?php echo base_url(); ?>js/tags/angular.js"></script>
<script src="<?php echo base_url(); ?>js/tags/bootstrap-tagsinput.js"></script>
<script src="<?php echo base_url(); ?>js/tags/bootstrap-tagsinput-angular.js"></script>
<script src="<?php echo base_url(); ?>js/tags/typeahead.js"></script>

<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
-->
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.dataTables.js"></script>
<!--<script type="text/javascript" src="<?php echo base_url(); ?>js/w2ui-fields-1.0.min.js"></script>
--><link href="<?php echo image_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo image_url; ?>css/admin/w2ui-fields-1.0.min.css">
-->






<script>
$(document).ready(function()
	{
		
			$('body').keypress(function (e) {
			
			if(e.which == 13) {
				$("#section_search").click();
			}
			
		});
	
		
$("#section_search").click(function()
 {
	// console.log('ppp');
  if($('#ddSectionFilter').val() != '1')
  {
   if($('#txtSearch').val() == '')
   {
	   //alert($('#txtSearch').val());
	  // alert('fdgdfg');
	   //console.log('in');
    $("#srch_error").html("Please enter text to search");
    return false;
   }
   else
   {
	  // console.log('++++');
    section_datatables();
    $("#srch_error").html("");
   }
  }
  else
  {
	//  console.log('else');
   section_datatables();
   $("#srch_error").html("");
  }
 });

	});
	 $(document).on('keyup','.error',function()
 {
	
    $(this).next('.CheckError').hide();
});	


</script>

<div class="Container">
    <div class="BodyWhiteBG">
            <div class="BodyHeadBg Overflow clear">
	    		<div class="FloatLeft BreadCrumbsWrapper">
    				<div class="breadcrumbs">Dashboard > Section Manager</div>
    				<h2 class="FloatLeft">Section Manager</h2>
    			</div>
				 <?php $data['Menu_id'] = get_menu_details_by_menu_name("Section");
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD". $data['Menu_id']) == 1) 
		{ ?>
                <p class="FloatRight SaveBackTop remoda1-bg"><a href="<?php echo base_url().folder_name;?>/section_manager/class_add_section_form" type="button" class="btn-primary btn"><i class="fa fa-plus-circle"></i>Add New</a></p>
                <?php }?>
                <p class="FloatRight SaveBackTop remoda1-bg"><a href="#" type="button" onclick="window.open('<?php echo base_url().folder_name;?>/section_manager/view_sitemap', '_blank');" class="btn-primary btn"><i class="fa fa-sitemap"></i>  Site Map</a></p>
        		<!--<div class="remodal" data-remodal-id="modal1" style="position:relative;">
            	<div class="ask_prabhu2 FloatLeft"><textarea class="ckeditor" name="editor1"></textarea> <h5>Text Showed at the bottom of your page. Usually used for copyright notice.</h5></div>
                <div class="FloatLeft SchueduleWrapper"><h3>Schedule</h3>
         <h4>Publilcation Start Date:</h4>
         <section id="examples">
            <div class="example example_markup">
            <div class="bs-example PanchangamTextBox">
              <input style="display: none;" value="fgdfgd" data-role="tagsinput" type="text">
         </div>
         </div>
         </section>
              <div>Server Hour: 2015-02-26 16:33:41</div>
            <p class="FloatRight save-back save_margin">
                <a href="#" class="FloatLeft back-top"><i class="fa fa-reply fa-2x"></i></a>
                <button type="button" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
            </p>
        </div>
            </div>-->
        </div>
    		<div class="Overflow DropDownWrapper">
            <div class="container">
            <div class="row AskPrabuCheckBoxWrapper">
            <ul class="AskPrabuCheckBox PanchangamManager FloatLeft">
                <li><label class="include_label">Filter</label></li>
                <li class="FloatLeft w2ui-field">
                    <select name="ddSectionFilter" id="ddSectionFilter" class="controls">
                        <option value="1">All</option>
                        <option value="2">Section Name</option>
                        <option value="3">Parent Section</option>
                    </select>
                </li>
                
                <li>
                 <p class="CalendarWrapper"><input type="text" value="" id="date_timepicker_start" placeholder="Start Date"><input type="text" value="" id="date_timepicker_end" placeholder="End Date"></p>
      
                  
                </li>
               
             
               <li><div class="FloatLeft"><input type="search" placeholder="Search" class="SearchInput error" name="txtSearch" id="txtSearch"> </div></li>
               <!--<li><i id="section_search" class="fa fa-search FloatLeft" ></i> </li>
               <li><button class="btn btn-primary margin-left-50" type="button" id="clear_search">Clear Search</button>-->
			   
			   <button class="btn btn-primary" type="button" id="section_search">Search</button>
				<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
              </li>
              
              
               <!--<li><p class="FloatLeft"><input type="search" placeholder="Search" class="SearchInput error" name="txtSearch" id="txtSearch"> </p>
              
              </li>
  <li>
   <i id="section_search" class="fa fa-search FloatLeft"></i> </li>
<li>
<button class="btn btn-primary margin-left-10" id="clear_search" style="float: left;">Clear Search</button>
</li>-->
               
      
                </ul>
              
               
      
                </ul>
                
		<p id="srch_error" style="clear: left; color:#F00;margin:0"></p>

                
            </div>
        </div>
    <div id="container_datatable" class="display"  style="width:100%; float:left; ">
            <div id="work_list" class="display">
            <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Section Name</th>
								<th>URL section name</th>
                                <th>Parent Section</th>
                                <th>Created by</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <?php if((defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1) || (defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1 )) { ?><th>Action</th><?php }?>
                            </tr>
                        </thead>
            </table>
        </div>
    </div>                   
    </div>
    </div>                            
</div>                       
<script>
$(document).ready(function()
{
	
	
$('#example').dataTable( 
{
} );

//$("#section_search").click(section_datatables);
section_datatables();

$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 section_datatables();
		  }  
		}
	});
	$("#clear_search").click(function() {
	$("#ddFilterBy").val('All');
	$("#ddStatus").val('');
	$("#txtSearch").val('');
	$("#date_timepicker_start").val('');
	$("#date_timepicker_end").val('');
	//$("#article_status").val('');
	
	section_datatables();
	$("#srch_error").html("");
	});
	
});

function delete_section(section_id,name)
{
	var r = confirm("Are you sure want to delete this section - "+name+"?");
	if(r)
	{
		window.location = "<?php echo base_url().folder_name;?>/section_manager/class_delete_section/"+section_id;
	}		
}
	

	
function section_datatables()
 {
		
	var fromdate=$( "#date_timepicker_start").val();
	var todate=$( "#date_timepicker_end").val();
	var filterby=$( "#ddSectionFilter" ).val();
	var searchbox=$( "#txtSearch").val();
	//var page_name   = "<?php echo $this->uri->segment(2); ?>";
	
    $('#example').dataTable( {
        "processing": true,
        "bServerSide": true,
		"autoWidth": false,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 10,
		"order": [[ 3, 'desc' ]],
		"aoColumnDefs": [ { "bSortable": false, "aTargets": [-1] } ] ,
		oLanguage: {
        sProcessing: "<img src='<?php echo image_url; ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },
		"fnDrawCallback":function(oSettings)
		{
				$("html, body").animate({ scrollTop: 0 }, "slow");
   
		   if($('span a.paginate_button').length <= 1) 
		   {
			 $("#example_paginate").hide();
			 //$("#example_length").hide();
		   } else 
		   {
			 $("#example_paginate").show();
			 $("#example_length").show();
		   }
		    if($(this).find('tbody tr').text()== "No matching records found")
		   {
			  
			  $(oSettings.nTHead).hide(); 
			  $('.dataTables_info').hide();
			
			  $("#example_length").hide();
			   $("#example").find('tbody tr').html($('<td valign="top" colspan="10" class="dataTables_empty BackArrow">No matching records found <a href="" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
			   
		   }
		   else
		   {
			   
			   $(oSettings.nTHead).show(); 
		   }
		},
		
		"ajax": {
            "url": "<?php echo base_url().folder_name; ?>/section_manager/section_datatable",
			"type" : "POST",
			"data" : {
		  "from_date" : fromdate, "to_date" : todate, "filterby" : filterby,"searchtxt" : searchbox  
			}
			
		 }
    } );
	
	}
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
<?php if($this->session->flashdata('success')){  ?>
	var flash_success = '<?php echo $this->session->flashdata('success'); ?>';
	show_toastr(flash_success, 1);
<?php } ?>
<?php if($this->session->flashdata('success_delete')){  ?>
	var flash_success = '<?php echo $this->session->flashdata('success_delete'); ?>';
	show_toastr(flash_success, 1);
<?php } ?>
<?php if($this->session->flashdata('failure_delete')){  ?>
	var flash_delete = '<?php echo $this->session->flashdata('failure_delete'); ?>';
	show_toastr(flash_delete, 3);
<?php } ?>

});
</script>