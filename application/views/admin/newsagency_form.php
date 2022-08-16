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
	$op_newsagencyid=$data['Agency_id'];
	$op_newsagencyname=$data['Agency_name'];
	$op_status=$data['Status'];
}
}


 ?>
 
 <script> 
	$(document).ready(function()
	{
	
		$("#frmnewsagencyMaster").validate({
		rules: 
		{			
			txtAgecncyName: 
			{ 
				required: true,
				//alphanumeric : true,
				maxlength:50,
			},
			
		},
		messages: 
		{
			txtAgecncyName:
			{
				required: "Please enter agency name",
				//alphanumeric:"Please enter valid characters",
				maxlength:"Please do not enter more than 50 characters."
			},
			
		},
		

	});
		
		$("#btnSave").click(function() 
		{
			if($("#frmnewsagencyMaster").valid())
			{
				var agencyname=$('#txtAgecncyName').val();
				var agencyid=$('#txthiddenid').val();
				
					$.ajax({
					type: "POST",
					data: {"agencynme":agencyname,"agency_id":agencyid},
					url:"<?php echo base_url(); ?>niecpan/newsagency_manager/check_agencyname",
					success: function(result)
					{
						if(result == "Newsagency name already exists")
						{
							$('#exist_msg').html('Newsagency name already exists');
							return false;
						}
						else
						{
							$('#exist_msg').html('');
							<?php
							if(isset($op_newsagencyid))
							{
							?>
							var r = confirm("Are you sure you want to update newsagency details?");
							<?php } else {?>
							var r = confirm("Are you sure you want to add newsagency details?");
							
							<?php }?>
							if(r==true)
							{
								$("#frmnewsagencyMaster").submit();
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
<form name="frmnewsagencyMaster" id="frmnewsagencyMaster" method="post" action="<?php echo base_url()."niecpan/newsagency_manager/newsagency_details";?>" enctype="multipart/form-data" >
<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<?php if(isset($op_newsagencyid)) 
{?>
<div class="breadcrumbs">Dashboard > Newsagency Edit</div>
 <h2 class="FloatLeft">Newsagency Edit</h2>
 <?php } else {?>
 <div class="breadcrumbs">Dashboard > Newsagency Create</div>
 <h2 class="FloatLeft">Newsagency Create</h2>
 <?php }?>
</div>

<!--<div class="FloatLeft Error">Error Message</div>-->
 
<p class="FloatRight save-back save_margin">
 <a class="FloatLeft back-top" href="<?php echo base_url()."niecpan/newsagency_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
 <button class="btn-primary btn" type="button" id="btnSave"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
</p>
 </div>
 
<div class="poll_content role-depart">

<div class="role-dept">
<div class="role-first">

<div class="qnsans">
<div class="qns2"><label class="question">Agency Name</label></div>
<div class="ans"><input type="text" name="txtAgecncyName" id="txtAgecncyName" class="tb_style2 box-shad box-shad1" value="<?php if(isset($op_newsagencyname)){ echo $op_newsagencyname ;}else{echo set_value('txtnewsagencyName');}?>"  >
<input type="hidden" name="txthiddenid" id="txthiddenid" value="<?php if(isset($op_newsagencyid)){ echo $op_newsagencyid;}?>">
<p id="exist_msg"></p>
</div>
</div>
<div class="qnsans">
<div class="qns2"><label class="question">Status</label></div>
<div class="ans section_radio">
<div class="switch switch-blue">
      <input type="radio" class="switch-input" name="views" value="1" <?php if(isset($op_status)){ if($op_status ==1){?>checked='checked'<?php } }else{echo set_radio('views','1');}?>  id="week4" checked="checked" >
      <label for="week4" class="switch-label switch-label-off">Active</label>
      <?php /*?><input type="radio" class="switch-input" name="views" <?php if(isset($status)&&($status > 0)){ ?> disabled <?php }?> value="I" <?php if(isset($op_status)){ if($op_status =='I'){?>checked='checked'<?php } }else{echo set_radio('views','I');}?>  id="month4" ><?php */?>
	   <input type="radio" class="switch-input" name="views" value="0" <?php if(isset($op_status)){ if($op_status ==0){?>checked='checked'<?php } }else{echo set_radio('views','0');}?>  id="month4" >
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
