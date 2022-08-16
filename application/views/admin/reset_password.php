<script src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/additional-methods.min.js"></script>
<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<style>
.error {
	color:red;
}
#exist_msg {
	color:red;
}
</style>
<script> 
$(document).ready(function()
{
	$("#frmChangePassword").validate({
	rules: 
	{			
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
					var reset_pwd=$('#txtNewPassword').val();
					$.ajax({
					beforeSend: function() {
						$("#loading_msg").html('Please wait while resetting your password...');
						$("#commom_loading").show();
					 },
					type: "POST",
					data: {"password":reset_pwd},
					url:"<?php echo base_url(); ?>niecpan/clog/reset_password/<?php echo $userID; ?>",
					
					success: function(result)
					{
						if(result=="changes password")
						{
							$("#loading_msg").empty();
							$("#frmChangePassword").hide();
							$("#reset_pwd_div").show();
							$("#commom_loading").hide();
							//$('#exist_msg').html(result);
						}
						else
						{
							$("#loading_msg").empty();
							$("#commom_loading").hide();
							alert("Failed to reset password.. Try again");
						}
					}
					});
			}
		});
		
				
		});
		

</script>

<style>
.MainTitle{
	 font-size: 40px;
    text-align: center;
	height:500px;
	}
.MainTitle p:first-child{
	padding-top:300px;
	}
.MainTitle p{
	line-height:50px;
	font-family:Arial, Helvetica, sans-serif;
	}
</style>

<div style="position:relative;">
	<div class="HeaderWrapper">
		<div class="Container HeaderContainer">
			<div class="FloatLeft LogoWrapper"><a href="javascript:void">ENPL - CMS</a></div>
		</div>
	</div>
</div>
<div class="Container">
	<div class="BodyWhiteBG BorderBoxNone">
		<div class="poll_content" style="padding-right:10px;">
			<div class="login">
				<!--<form name="frmChangePassword" id="frmChangePassword" method="post" action="<?php //echo base_url(); ?>niecpan/clog/reset_password/<?php //echo $userID; ?>" enctype="multipart/form-data" >-->
				<form name="frmChangePassword" id="frmChangePassword" method="post"  enctype="multipart/form-data" >
					<fieldset class="LoginForm">
						<p id="exist_msg"></p>
						<h3>New Password</h3>
						<p>
							<input type="password" name="txtNewPassword" id="txtNewPassword" maxlength="15"  />
						</p>
						<h3>Confirm Password</h3>
						<p>
							<input type="password" name="txtConfirmPassword" id="txtConfirmPassword"  />
						</p>
						<div class="save_poll">
							<div class="FloatRight">
								<p class="FloatLeft save-back">
									<button class="btn-primary btn" name="btnSubmit" id="btnSubmit" type="button"><i class=""></i> &nbsp;Submit</button>
								</p>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="MainTitle" id="reset_pwd_div" style="display:none" >
Password has been reset succesfully. You can <a href="<?php echo base_url(); ?>niecpan/clog/" style="text-decoration: underline;">Click here</a> to login
</div>