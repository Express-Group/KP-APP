<?php $script_url = image_url; ?>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">-->
<link href="<?php echo $script_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<!--JQuery-->
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<!--<script src="<?php echo $script_url; ?>js/jquery.remodal.js"></script>-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>
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
	$op_Topicid=$data['column_id'];
	$op_Topicname=$data['column_name'];
}
}


 ?>
 
 <script> 
	$(document).ready(function()
	{
	
		$("#frmTopicMaster").validate({
		rules: 
		{			
			txtTopicName: 
			{ 
				required: true,
				//alphanumeric : true,
				maxlength:50,
			},
			
		},
		messages: 
		{
			txtTopicName:
			{
				required: "Please enter column name",
				//alphanumeric:"Please enter valid characters",
				maxlength:"Please do not enter more than 50 characters."
			},
			
		},
		
	});
		
		$("#btnSave").click(function() 
		{
			if($("#frmTopicMaster").valid())
			{
				var deptname=$('#txtTopicName').val();
				var deptid=$('#txthiddenid').val();
				
					$.ajax({
					type: "POST",
					data: {"dpt_name":deptname,"dept_id":deptid},
					url:"<?php echo base_url(); ?>niecpan/topic_manager/check_Topicname",
					success: function(result)
					{
						if(result == "topic name already exists")
						{
							$('#exist_msg').html('Column name already exists');
							return false;
						}
						else
						{
							$('#exist_msg').html('');
							<?php
							if(isset($op_Topicid))
							{
							?>
							var r = confirm("Are you sure you want to update Column?");
							<?php } else {?>
							var r = confirm("Are you sure you want to add Column?");
							
							<?php }?>
							if(r==true)
							{
								$("#frmTopicMaster").submit();
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
<form name="frmTopicMaster" id="frmTopicMaster" method="post" action="<?php echo base_url()."niecpan/topic_manager/topic_details";?>" enctype="multipart/form-data" >
<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">

 <div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
 <h2 class="FloatLeft"><?php echo $title; ?></h2>
</div>

<!--<div class="FloatLeft Error">Error Message</div>-->
 
<p class="FloatRight save-back save_margin">
 <a class="FloatLeft back-top" href="<?php echo base_url()."niecpan/topic_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
 <button class="btn-primary btn" type="button" id="btnSave"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
</p>
 </div>
 
<div class="poll_content role-depart">

<div class="role-dept">
<div class="role-first">

<div class="qnsans">
<div class="qns2"><label class="question">Column Name</label></div>
<div class="ans"><input type="text" name="txtTopicName" maxlength="50" id="txtTopicName" class="tb_style2 box-shad box-shad1" value="<?php if(isset($op_Topicname)){ echo $op_Topicname ;}else{echo set_value('txtTopicName');}?>"  >
	<input type="hidden" name="txthiddentopic" id="txthiddentopic" value="<?php if(isset($op_Topicname)){ echo $op_Topicname;}?>">
<input type="hidden" name="txthiddenid" id="txthiddenid" value="<?php if(isset($op_Topicid)){ echo $op_Topicid;}?>">
<p id="exist_msg"></p>
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
