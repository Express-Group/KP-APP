<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="<?php echo $script_url; ?>js/jquery-1.10.2.js"></script>
<!--<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
 
<link href="<?php echo $script_url; ?>css/admin/tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $script_url?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>	
<script type="text/javascript" src="<?php echo $script_url?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo $script_url?>js/jquery.remodal.js"></script>

<script src="<?php echo $script_url; ?>js/bootstrap/bootstrap.js"></script>
<script src="<?php echo $script_url; ?>js/tags/angular.js"></script>
<script src="<?php echo $script_url; ?>js/tags/bootstrap-tagsinput.js"></script>
<script src="<?php echo $script_url; ?>js/tags/bootstrap-tagsinput-angular.js"></script>
<script src="<?php echo $script_url; ?>js/tags/typeahead.js"></script>
  
<script type="text/javascript" src="<?php echo $script_url?>js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $script_url?>js/w2ui-fields-1.0.min.js"></script>

<script>
function uncheck()
{
	$(".checked").removeClass("checked");
}
</script>


<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $script_url?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url?>js/additional-methods.min.js"></script>	

<style>
  .error
  {
		margin: 0 auto;
		display: block;
		color:#f00;
  }
  #keyTag_error
  {
	   color:#F00;
  }
</style>  
  
<script>
$(document).ready(function() {
	
	negative_datatables();
	
	$("#neg_keyword_search").click(negative_datatables)
	
	$("#clear_search").click(function() {
	$("#txtSearch").val('');
	negative_datatables();
	});
	$(document).on('opened', '.remodal', function (e) {
		$(".bootstrap-tagsinput input").css("width", "auto");
	});
	$(document).on('closed', '.remodal', function (e) {
		$("span").remove();
	});
	
	$("#negativeKey_form").validate(
	{
		ignore:[],
		debug: false,
	});
	
	$("#btnSubmitKey").click(function()
	{
		var keyword_text = $.trim($("#txtNegativeKey").val());
		
		if(keyword_text == "")
		{
			$("#keyTag_error").html("Please enter atleast one block word");
			return false;
		}
		else
		{
			var confirm_status = confirm("Are you sure you want to add the block word?");
			if(confirm_status==true)
			{
					$("#keyTag_error").html("");
					$.ajax({
						beforeSend: function() { $("#normal_loading").show() },
						type: "POST",
						data: {"get_keyword":keyword_text},
						url: "<?php echo site_url().folder_name; ?>/negative_manager/add_keyword", 
						success: function(result)
						{
							//if(result == "success")					{
								location.href = "<?php echo base_url().folder_name;?>/negative_manager";
								//$("#normal_loading").hide();
							/*}else if(result == "fail")
							{
								location.href = "<?php //echo base_url();?>admin/negative_manager";
							}*/
						}
					});
			}
		}
	});
	
$('#txtSearch').keypress(function (e) {
  if($.trim($('#txtSearch').val()) != '') {
   var key = e.which;
   if(key == 13)  {
    negative_datatables();
    }  
  }
 });


});

function negative_datatables()
{
	var searchbox=$("#txtSearch").val();
	
    $('#example').dataTable( {
        "processing": true,
        "bServerSide": true,
		"autoWidth": false,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 10,
		oLanguage: {
        sProcessing: "<img src='<?php echo image_url; ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
    },
	"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ -1 ] }
       ],
		"fnDrawCallback":function(oSettings)
		{
		   if($('span a.paginate_button').length <= 1) 
		   {
			 $("#example_paginate").hide();
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
			  $("#example").find('tbody tr')
    .append($('<td class="BackArrow"><a href="<?php  echo base_url().folder_name;?>/negative_manager" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
		   }
		   else
		   {
			   $(oSettings.nTHead).show(); 
		   }
		},
		"ajax": {
            "url": "<?php echo base_url().folder_name;?>/negative_manager/negativeKeyword_datatable",
			"type" : "POST",
			"data" : {"searchtxt" : searchbox}
		 }
    });
}

function delete_neg_key(value)
{
	var r = confirm("Are you sure want to delete this block word?");
	if(r)
	{
		window.location = "<?php echo base_url().folder_name;?>/negative_manager/delete_data/"+value;
	}		
}

function update_neg_key(get_id)
{
	$('[name="EditName'+get_id+'"]').rules( "add", {
                required: true,
				 messages: {
					required: "This field cannot be empty. Please enter a value"
				  }
	});
		
	if($("#negativeKey_form").valid())
	{
			var confirm_box = confirm("Are you sure want to edit this block word?");
			if(confirm_box)
			{
				var get_keyword =  $.trim($("#EditName"+get_id).val());
				$.ajax({
						//beforeSend: function() { $("#normal_loading").show() },
						type: "POST",
						data: {"get_keyword":get_keyword, "get_id":get_id},
						url: "<?php echo site_url().folder_name;?>/negative_manager/update_keyword", 
						success: function(data)
						{
							
							//if(data == "success")							{
								location.href = "<?php echo base_url().folder_name;?>/negative_manager";
								//$("#normal_loading").hide();
							/*}else if(data == "fail")
							{
								location.href = "<?php //echo base_url();?>admin/negative_manager";
							}*/
						}
				});
			}
	}
}
</script>

</head>

<div class="Container">
<div class="BodyWhiteBG">

<div class="BodyHeadBg Overflow clear">
    
    <div class="FloatLeft">
        <div class="breadcrumbs">Dashboard > Block Words</div>
        <h2>Block Words</h2>
    </div>
    
    
    <p class="FloatRight SaveBackTop remoda1-bg">
    	<a class="back-top FloatLeft" href="<?php echo site_url().folder_name;?>/comment_manager" title="Go Back"><i class="fa fa-reply fa-2x"></i></a>
		<?php if(defined("USERACCESS_ADD".$Menu_id) && constant("USERACCESS_ADD".$Menu_id) == 1) { ?>
    	<a href="#modal1" type="button" class="btn-primary btn"><i class="fa fa-plus-circle"></i>Add New</a>
		  <?php  } ?>
    </p>
  
     
    <div class="remodal" data-remodal-id="modal1" style="position:relative;">
        <div class="FloatLeft SchueduleWrapper NegativeTextBox">
            <div class="Overflow">
                <!--<h3 class="FloatLeft">Create Negative Keyword</h3>-->
                <p class="FloatRight save-back save_margin">
                    <button type="button" id="btnSubmitKey" name="btnSubmitKey" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
                </p>
            </div>
          
            <div id="examples">
                    <div class="example example_markup">
                        <div class="bs-example PanchangamTextBox ">
                            <h4 class="FloatLeft">Block Word</h4>
                            <div class="FloatLeft"> <input style="display: none;" value="" maxlength="10" name="txtNegativeKey" id="txtNegativeKey" data-role="tagsinput" type="text"><br /><span id="keyTag_error"></span></div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="Overflow DropDownWrapper PanchangamWrapper">

	<div class="container">
		<div class="row AskPrabuCheckBoxWrapper NegativeKeyword">
			<ul class="AskPrabuCheckBox FloatLeft">
				<li>
                	<P class="FloatLeft"><input type="search" id="txtSearch" name="txtSearch" placeholder="Search" class="SearchInput"></P>
					<button class="btn btn-primary" type="button" id="neg_keyword_search">Search</button>
					<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>

                </li>
			</ul>
		</div>
    </div>

	<form id="negativeKey_form" name="negativeKey_form" onsubmit="return false" method="post">
        <table id="example" class="display" cellspacing="0" width="100%" border="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Keyword</th>
                    <th>Created On</th>
                    <?php if((defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) || (defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1 ) ||   (defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1 ) ) { ?>
                        <th>Action</th>
                    <?php } ?>
                </tr>
            </thead>
        </table>
	</form>
    
        
</div>

                            
</div>                            
</div>                       
   
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
	
	<?php if($this->session->flashdata('error')){  ?>
		var flash_error = '<?php echo $this->session->flashdata('error'); ?>';
		show_toastr(flash_error, 2);
	<?php } ?>
});

</script>   
<style>
.remodal{
width: 500px;
}
</style>                                
