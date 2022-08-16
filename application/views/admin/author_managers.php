<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap.js"></script>
<link href="<?php echo base_url(); ?>css/admin/tag/bootstrap.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>    
<script type="text/javascript">
$(document).ready(function() {
	$('#example').dataTable( {
	} );
	 $('[data-toggle="tooltip"]').tooltip();
	$('.deleteauthor').click(function(){
     var authorname=$(this).attr('name');
	  
	 
    var status = confirm('Are you sure you want to delete the author – "'+authorname+'"');
    if(status==true)
    { 
     $('#loadingsel').show();
    $.ajax({
    url:'<?php echo base_url()."admin/author_managers/deleteauthors";?>',
    type:"POST",
    data:"author_id="+$(this).attr('id'),
    success: function(data) {
     $('#loadingsel').hide();
	 
      
	 var rowid=data+'rowid';
	 $("#"+rowid).hide();
	 $("#deletedmessage").fadeIn().show();
	 $("#activatedmessage").fadeIn().hide();
	 $("#deactivatedmessage").fadeIn().hide();
   // location.reload();
      
        } 
    
    });
    return false;
    }
    else
    {
    return false;
    }
    
    });	 
    
	$('#searchauthor').ajaxForm({ 
	beforeSubmit:  validates,
	success: function(data)
		{
			$("#loadingimage").hide(); 
			$("#tablesearchinner").html(data);
		}      
		
	})
	<?php
	if(count($authors)<11)
	{
		?>
		$(".current.paginate_button, .previous.paginate_button, .next.paginate_button").hide(); 
		<?php
	}
	?>
	
} );

function actionscript(id)
{ 
  
	       var idvalue = parseInt(id, 10); 
			$.ajax({ 
			url:'<?php echo base_url()."admin/author_managers/changestatus";?>',
			type:"POST",
			data:"authorid="+id,
			success: function(data) 
			{
				 
				$('#loadingsel').hide();  
				 if(data=="deactivated")
				 {  
					var deactivateimg=idvalue+"deactivateimg";  
					var activateimg=idvalue+"activateimg";  
					var activate=idvalue+"activate";
					$("#"+id).html('<i class="fa fa-pause" ></i>');   
					$("#"+id).attr("id",""+activate+"");    
					$("#"+activateimg).html('<i   class="fa fa-times"></i>');
					$("#"+activateimg).attr("id",""+deactivateimg+"");
					$("#deactivatedmessage").fadeIn().show();
					 
					 $("#activatedmessage").fadeIn().hide();
					 $("#deletedmessage").fadeIn().hide();   
				 }
				 else if(data=="activated")
				 {
					var deactivateimg=idvalue+"deactivateimg";  
					var activateimg=idvalue+"activateimg";  
					var deactivate=idvalue+"deactivate";; 
					$("#"+id).html('<i class="fa fa-caret-right"></i> ');   
					$("#"+id).attr("id",""+deactivate+"");    
					$("#"+deactivateimg).html('<i   class="fa fa-check"></i>');
					$("#"+deactivateimg).attr("id",""+activateimg+""); 
					$("#activatedmessage").fadeIn().show();  
					$("#deactivatedmessage").fadeIn().hide();
					 $("#deletedmessage").fadeIn().hide(); 
				 }
				 else
				 {
					 alert("Problem while updating poll status");
				 }
			}  
			});
 
	
}

function validates()
{
	
	var alphaExp = /^[a-zA-Z0-9][ .a-zA-Z0-9@]{0,60}$/i;
	if($('#searchfield').val()=='')
	{
		$('#error_searchfield').html("<span  >Please enter text for search.</span>"); 
		$('#searchfield').focus(); 
		return false;
	}
	else
	{ 
		if($('#searchfield').val().match(alphaExp))
		{
			$('#error_searchfield').html(' ');  
		}
		else
		{ 
			$('#error_searchfield').html("<span>Search Name should contain only letters and numbers</span>"); 
			$('#searchfield').focus();
			return false;
		}
	}
	 
	$("#loadingimage").show();
	function author_datatables() {
		 $("#loadingimage").hide();
	var tablecolumnvalue 	= $("#tablecolumnvalue").val();
	var author_status = $("#author_status").val();
	var SearchBy	= $("#searchfield").val();  
	var page_name   = "<?php echo $this->uri->segment(2); ?>";
	
    $('#example').dataTable( {
        "processing": true,
        "bServerSide": true,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 10,
		"fnDrawCallback":function(){
   
		   if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();
			 $("#example_length").hide();
		   } else {
			 $("#example_paginate").show();
			 $("#example_length").show();
		   }
		},
		
		"ajax": {
            "url": "<?php echo base_url(); ?>admin/author_managers/author_datatables",
			"type" : "POST",
			"data" : {
		 "tablecolumnvalue" : tablecolumnvalue, "author_status" : author_status, "SearchBy" : SearchBy,"Page_name" : page_name  
			}
		 }
    } );
	
	$(".tooltip-1").tooltip(
	{
		items: "img, [data-geo], [title]",
		  content: function() 
		  {
				return '<table width="100%" border="1" class="TooltipTable"> <tr>   <td>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry</td>     </tr></table>';
		  }
	});
	} 
}
</script> 
<link href="<?php echo base_url(); ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" /> 
<div class="Container">
<div class="BodyWhiteBG">

<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper PollResult">
<div class="breadcrumbs"><a href="#">Dashboard</a> &gt; <a href="#">Author Manager</a></div>
<h2 class="FloatLeft">Author Manager</h2>
</div>
 
 <div id="activatedmessage" class="FloatLeft Success" style="display:none">Activated Successfully.</div>
 <div id="deactivatedmessage" class="FloatLeft Success" style="display:none">Deactivated Successfully.</div>
 <div id="deletedmessage" class="FloatLeft Success" style="display:none ">Deleted Successfully.</div>
<?php
if(!empty($this->session->flashdata("success")))
{     
?>
 <div class="FloatLeft Success"><?php echo $this->session->flashdata("success");?></div>
<?php
}
?> 
<?php
if(!empty($this->session->flashdata("error")))
{
?>
 <div class="FloatLeft Error"><?php echo $this->session->flashdata("error");?></div>
<?php
}
?>

 <p class="FloatRight SaveBackTop"><a href="<?php echo base_url()."admin/author_managers/addauthor";?>"><button class="btn-primary btn" type="button"><i class="fa fa-file-text-o"></i> &nbsp;New Author</button></a></p>
</div>
 
<div class="Overflow DropDownWrapper">
<div align="center" id="loadingimage" style="display:none;"  ><img src="<?php echo base_url();?>/images/admin/loader.gif"  width="16" height="16" style="width:16px; height:18px; " border="0" /></div>
<form action="#" method="post" id="searchauthor"   name="searchauthor">  
<div class="FloatLeft TableColumn FloatLeft">  
<div class="FloatLeft TableSelect" >
<!--<div class="w2ui-label"> List: </div>-->
 
    <select name="tableid" id="tablecolumnvalue" >
        <option value="">Search By: All</option>
        <option value="Author Name">Author Name</option> 
        <option value="Email Id">Email Id</option>
    </select>   
 
</div>
<div class="FloatLeft TableSelect" >
<!--<div class="w2ui-label"> Combo: </div>-->
 
<select name="tablestatus" id="author_status" >
        <option value="">Status: All</option>
        <option value="Publish">Active</option>
        <option value="UnPublish">Inactive</option> 
    </select>  
   
</div>


<div class="FloatLeft"><input type="search" name="txtsearch" maxlength="100" placeholder="Search" id="searchfield" class="SearchInput"  ></div>
<i class="fa fa-search FloatLeft"  id="searchrecords"  ></i>

</div>

</form>
<div id="error_searchfield" class="mandatory" style="clear:both; color:#900;"></div>
<div id="tablesearchinner"> 
<table id="example" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th width="31%">Author Name</th>
					<th width="12%">Type</th>
					<th width="23%">Email ID</th>
					<th width="18%">Created On</th>
					<th width="8%">Status</th>
					<th width="8%"><div align="center">Action</div></th>
				</tr>
			</thead> 
			 
		</table>
<script>
$(document).ready(function () {
	
	$("#searchrecords").click(author_datatables); 
	function author_datatables() {
		 $("#loadingimage").hide();
	var tablecolumnvalue 	= $("#tablecolumnvalue").val();
	var author_status = $("#author_status").val();
	var SearchBy	= $("#searchfield").val();  
	var page_name   = "<?php echo $this->uri->segment(2); ?>";
	
    $('#example').dataTable( {
        "processing": true,
        "bServerSide": true,
		 "bDestroy": true,
		  "searching": false,
		"iDisplayLength": 10,
		"fnDrawCallback":function(){
   
		   if($('span a.paginate_button').length <= 1) {
			 $("#example_paginate").hide();
			  
		   } else {
			 $("#example_paginate").show();
			 $("#example_length").show();
		   }
		},
		
		"ajax": {
            "url": "<?php echo base_url(); ?>admin/author_managers/author_datatables",
			"type" : "POST",
			"data" : {
		 "tablecolumnvalue" : tablecolumnvalue, "author_status" : author_status, "SearchBy" : SearchBy,"Page_name" : page_name  
			}
		 }
    } );
	
	$(".tooltip-1").tooltip(
	{
		items: "img, [data-geo], [title]",
		  content: function() 
		  {
				return '<table width="100%" border="1" class="TooltipTable"> <tr>   <td>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry</td>     </tr></table>';
		  }
	});
	}
	author_datatables();
	
	});
</script>        
</div>            
</div>

  



       
      
                            
</div>                            
</div>