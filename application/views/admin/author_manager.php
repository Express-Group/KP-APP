<?php $script_url = image_url; ?>
<!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
<link href="<?php echo $script_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>	
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap/bootstrap.min.js"></script>-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<!--<script type="text/javascript" src="<?php echo $script_url; ?>js/w2ui-fields-1.0.min.js"></script>-->
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">-->

<script>
$(document).ready(function() {
	$('#example').dataTable( {
		/*"processing": true,
		"serverSide": true,
		"ajax": "scripts/server_processing.php",
		"deferLoading": 57*/
	} );
} );
</script>



</head>

<body>

<?php $check_page_func = $this->uri->segment(2);

switch($check_page_func)
{
	case "byliner_manager":
		$page_name = "view_addform/1";
		break;
	case "columnist_manager":
		$page_name = "view_addform/2";
		break;
	default:
		$page_name = '';
}

?>


<div class="Container">
<div class="BodyWhiteBG">

<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft">
<div class="breadcrumbs">Dashboard > Author</div>
 <h2><?php echo $title; ?></h2>
</div>
<a>
<?php

if($title=="Byline Manager")
$data['Menu_id'] = get_menu_details_by_menu_name('Byline');
else
$data['Menu_id'] = get_menu_details_by_menu_name('Columnist');

		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{ ?>
 <p class="FloatRight SaveBackTop"><a href="<?php echo base_url();?>niecpan/author_manager/<?php echo $page_name; ?>" type="button" class="btn-primary btn"><i class="fa fa-plus-circle"></i>Add New</a></p>
 <?php }?>
 </a>
</div>

<div class="Overflow DropDownWrapper">
<?php 
                if($this->session->flashdata("success"))
                {     
                ?>
                 <div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("success");?></div>
                <?php
                }
                ?> 
                
                <?php 
                if($this->session->flashdata("success_delete"))
                {     
                ?>
                 <div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("success_delete");?></div>
                <?php
                }
                ?> 
                 <?php 
                if($this->session->flashdata("failure_delete"))
                {     
                ?>
                 <div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("failure_delete");?></div>
                <?php
                }
                ?> 




  

<div class="container">
            <div class="row AskPrabuCheckBoxWrapper FloatLeft">
            <ul class="AskPrabuCheckBox PanchangamManager FloatLeft">
                <li><label class="include_label">Filter</label></li>
                <li class="FloatLeft w2ui-field">
                    <select name="ddFilter" id="ddFilter" class="controls">
                        <option value="All">Search By: All</option>
                        <option value="Authorname">Author Name</option>
                        <option value="Emailid">Email ID</option>
                    </select>
                </li>
                
                <li class="FloatLeft w2ui-field">
                    <select name="ddStatus" id="ddStatus" class="controls">
                        <option value="">Status: All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </li>
                
               
               
             
               <li><div class="FloatLeft"><input type="search" placeholder="Search" class="SearchInput error" name="txtSearch" id="txtSearch"> </div></li>
             <!-- <li> <i id="author_search" class="fa fa-search FloatLeft" ></i> </li>
              <li> <button class="btn btn-primary margin-left-50" type="button" id="clear_search">Clear Search</button>
              </li>-->
			  
			  <button class="btn btn-primary" type="button" id="author_search">Search</button>
			  <button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
               
      
                </ul>
                
       <p id="srch_error" style="clear: left; color:#F00;margin:0"></p>
                
            </div>
        </div>




<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Author Name</th>
						<th>Type</th>
						<th>Email ID</th>
						<th>Created On</th>
						<th>Status</th>
                        <?php if((defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1) || (defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1 )) { ?>
                        <th>Action</th>
                        <?php }?>
					</tr>
				</thead>
              
                    
				
			</table>
</div>

    
                            
</div>                            
</div>                       
 <script>
$(document).ready(function()
{
	
	
author_datatables();

$('#txtSearch').keypress(function (e) {
		if($.trim($('#txtSearch').val()) != '') {
		 var key = e.which;
		 if(key == 13)  {
			 author_datatables();
		  }  
		}
	});
	
	$("#clear_search").click(function() {
	$("#ddFilter").val('All');
	$("#ddStatus").val('');
	$("#txtSearch").val('');
	$("#srch_error").html('').hide();
	//$("#date_timepicker_start").val('');
	//$("#date_timepicker_end").val('');
	//$("#article_status").val('');
	
	author_datatables();
	});

	
});



	
function author_datatables()
 {
		
	
	var filterby=$( "#ddFilter" ).val();
	var status=$('#ddStatus').val();
	var searchbox=$( "#txtSearch").val();
	var page_name="<?php echo $type; ?>";
	
    $('#example').dataTable( {
        "processing": true,
        "bServerSide": true,
		"autoWidth": false,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 10,
		// "aaSorting": [[0,'desc']],  
		oLanguage: {
        sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },
	"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ -1 ] }
       ],

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
            "url": "<?php echo base_url(); ?>niecpan/author_manager/author_datatable",
			"type" : "POST",
			"data" : {
				//"tablecolumnvalue" : tablecolumnvalue, "author_status" : author_status, "SearchBy" : SearchBy,"Page_name" : page_name
		  "filterby" : filterby,"searchtxt" : searchbox ,"status": status,"page_name":page_name
			}
			
		 }
    } );
	
	}
</script>   
   
 <script>
$(document).ready(function()
	{
		
			$('body').keypress(function (e) {
			
			if(e.which == 13) {
				$("#author_search").click();
			}
			
		});
	
		
$("#author_search").click(function()
 {
	// console.log('ppp');
  if($('#ddFilter').val() != 'All')
  {
   if($('#txtSearch').val() == '')
   {
	   
    $("#srch_error").html("Please enter text to search").show();
    return false;
   }
   else
   {

    author_datatables();
    $("#srch_error").html("");
   }
  }
  else
  {

   author_datatables();
   $("#srch_error").html("");
  }
 });

	});
	/* $(document).on('keyup','.error',function()
 {
	
    $(this).next('.CheckError').hide();
});	*/


function delete_author(id,name)
		{
			var r = confirm("Are you sure do you want to delete?");
			if(r==true)
			{
				window.location.href="<?php echo base_url() ?>niecpan/author_manager/delete_author/"+id+"/<?php echo $type; ?>";
			}
		}




</script>                               
<script type="text/javascript">
$(document).ready(function(){
 
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
<?php if($this->session->flashdata('failure_delete')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});
<?php } ?>

});
</script>           



        


