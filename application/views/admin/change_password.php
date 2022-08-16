<script src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/additional-methods.min.js"></script>
<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<style>
.error
{
	color:red;
}
#exist_msg
{
	color:red;
}
</style>
<script> 
$(document).ready(function()
{
	$("#frmChangePassword").validate({
	rules: 
	{			
		txtOldPassword: 
		{ 
			required: true,
			remote : "<?php echo base_url();?>niecpan/change_password/new_check_password"
		},
		txtNewPassword: 
		{ 
			required: true,
			minlength:8,
			maxlength:15,
		},
		txtConfirmPassword: 
		{ 
			required: true,
			equalTo: "#txtNewPassword"
		},
			
	},
	messages: 
	{
		txtOldPassword:
		{
			required:"Please enter the password.", 
			remote:"Please enter the correct old password"
		},
		txtNewPassword:
		{
			required:"Please enter the new password.", 
			minlength:"Please enter atleast 8 characters. ",
			maxlength:"Please do not enter more than 15 characters.",
		},
		txtConfirmPassword:
		{
			required:"Please reenter the new password.", 
			equalTo:"Please enter confirm password same as new password. ",
			
		},
			
	},

	});
		$("#btnSubmit").click(function() 
		{
			if($("#frmChangePassword").valid())
			{
					var oldpassword=$('#txtOldPassword').val();
					$.ajax({
					type: "POST",
					data: {"password":oldpassword},
					url:"<?php echo base_url(); ?>niecpan/change_password/check_password",
					success: function(result)
					{
						if(result=="Please enter the correct password")
						{
							$('#exist_msg').html(result);
							return false;
						}
						else
						{
							$('#txtConfirmPassword').keypress(function(event){
							  if(event.keyCode == 13)
							  {
								  alert('dsfsd');
								//$('#btnSubmit').click();
								$("#frmChangePassword").submit();
							  }
							});
							//$('#exist_msg').html("");
							//$("#frmChangePassword").submit();	
						}
						
					}
					});

		
			}
		});
		$('#btnSubmit').click(function(){
  $("#frmChangePassword").submit();
});
				
		});
		

</script>
           
<div class="Container">
			
<div class="BodyWhiteBG">
<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper ">
				<div class="breadcrumbs">Dashboard > Change Password</div>
 					<h2>Change Password</h2>
			</div>
            
            
		</div>
<div class="poll_content" style="padding-right:10px;">
<div class="login">
<?php 
if($this->session->flashdata("success"))
{     
?>
 <div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("success");?></div>
<?php
}
?> 	
<?php 
if($this->session->flashdata("failure"))
{     
?>
<div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("failure");?></div>
<?php
}
?> 	


<form name="frmChangePassword" id="frmChangePassword" method="post" action="<?php echo base_url()."niecpan/change_password/get_details";?>" enctype="multipart/form-data" >
<fieldset class="LoginForm">
<h3>Old Password</h3>
<p><input type="password" name="txtOldPassword" id="txtOldPassword" /></p>
<p id="exist_msg"></p>
<h3>New Password</h3>
<p><input type="password" name="txtNewPassword" id="txtNewPassword" maxlength="15"  /></p>
<h3>Confirm Password</h3>
<p><input type="password" name="txtConfirmPassword" id="txtConfirmPassword"  /></p>
<div class="save_poll">
<div class="FloatRight">
<p class="FloatLeft save-back">
 <button class="btn-primary btn" name="btnSubmit" id="btnSubmit" type="button"><i class=""></i> &nbsp;Submit</button></p>
</div>
</div>
</fieldset>
</form>
</div>
</div>  
</div>                       
</div>
 
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

<?php if($this->session->flashdata('failure')){  ?>
$("#flash_msg_id").show();
$("#flash_msg_id").slideDown(function() {
    setTimeout(function() {
        $("#flash_msg_id").slideUp();
    }, 5000);
});
<?php } ?>

});
</script>                                   

               


