<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">-->
<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<!--JQuery-->
<script src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/additional-methods.min.js"></script>
<style>
.error
{
	color:#F00;
	display:block;
}
#exist_msg
{
	color:#F00;
}
</style>

<?php

if(isset($dpteditdetails))
{

foreach($dpteditdetails as $data)
{
	$op_departmentid=$data['department_id'];
	$op_departmentname=$data['departmentname'];
	$op_status=$data['Status'];
}
}


 ?>
 
 <script> 
	$(document).ready(function()
	{
	
		$("#frmdepartmentMaster").validate({
		rules: 
		{			
			txtDepartmentName: 
			{ 
				required: true,
				//alphanumeric : true,
				maxlength:50,
			},
			
			
		},
		messages: 
		{
			txtDepartmentName:
			{
				required: "Please enter department name",
				//alphanumeric:"Please enter valid characters",
				maxlength:"Please do not enter more than 50 characters."
			},
			
		},
		

	});
		
		$("#btnSave").click(function() 
		{
			if($("#frmdepartmentMaster").valid())
			{
				var deptname=$('#txtDepartmentName').val();
				var deptid=$('#txthiddenid').val();
				
					$.ajax({
					type: "POST",
					data: {"dpt_name":deptname,"dept_id":deptid},
					url:"<?php echo base_url(); ?>niecpan/department_manager/check_departmentname",
					success: function(result)
					{
						if(result == "Department name already exists")
						{
							$('#exist_msg').html('Department name already exists');
							return false;
						}
						else
						{
							
							<?php
							if(isset($op_departmentid))
							{
								
							?>
							
							var r = confirm("Are you sure you want to update department details?");
							<?php } else {?>
							var r = confirm("Are you sure you want to add department details?");
							
							<?php }?>
							if(r==true)
							{
								$("#frmdepartmentMaster").submit();
							}
							else
							{
								return false;
							}
						}
					}
					
				});
			}
				
		});
		
 });

</script>

<body >
<div class="Container">
<div class="BodyWhiteBG">
<form name="frmdepartmentMaster" id="frmdepartmentMaster" method="post" action="<?php echo base_url()."niecpan/department_manager/department_details";?>" enctype="multipart/form-data" >
<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<?php if(isset($op_departmentid)) 
{?>
<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">Department Edit</a></div>
 <h2 class="FloatLeft">Department Edit</h2>
 <?php } else {?>
 <div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">Department create</a></div>
 <h2 class="FloatLeft">Department create</h2>
 <?php }?>
</div>

<!--<div class="FloatLeft Error">Error Message</div>-->
 
<p class="FloatRight save-back save_margin">
 <a class="FloatLeft back-top" href="<?php echo base_url()."niecpan/department_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
 <button class="btn-primary btn" type="button" id="btnSave"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
</p>
 </div>
 
<div class="poll_content role-depart">

<div class="role-dept">
<div class="role-first">

<div class="qnsans">
<div class="qns2"><label class="question">Department Name</label></div>
<div class="ans"><input type="text" name="txtDepartmentName" id="txtDepartmentName" class="tb_style2 box-shad box-shad1" value="<?php if(isset($op_departmentname)){ echo $op_departmentname ;}else{echo set_value('txtDepartmentName');}?>"  >
<input type="hidden" name="txthiddenid" id="txthiddenid" value="<?php if(isset($op_departmentid)){ echo $op_departmentid;}?>">
<p id="exist_msg"></p>
</div>
</div>

<div class="qnsans">
<div class="qns2"><label class="question">Status</label></div>
<div class="ans section_radio">
<div class="switch switch-blue">
      <input type="radio" class="switch-input" name="views" value="1" <?php if(isset($op_status)){ if($op_status =='1'){?>checked='checked'<?php } }else{echo set_radio('views','1');}?>  id="week4" checked="checked" >
      <label for="week4" class="switch-label switch-label-off">Active</label>
      <input type="radio" class="switch-input" name="views" value="0" <?php if(isset($op_status)){ if($op_status =='0'){?>checked='checked'<?php } }else{echo set_radio('views','0');}?>  id="month4" >
      <label for="month4" class="switch-label switch-label-on">In-active</label>
      <span class="switch-selection"></span>
    </div>
</div>

</div>


        
</div>


<div class="save_poll">
<div class="FloatRight">
<p class="FloatLeft save-back">
 <!-- <a class="FloatLeft back-top" href="#"><i class="fa fa-reply fa-2x"></i></a>
 <button class="btn-primary btn" type="button"><i class="fa fa-file-text-o"></i> &nbsp;Save</button></p>-->
</div>
</div>
</div>




</div>
 </form> 
</div> 
                         
</div> 
