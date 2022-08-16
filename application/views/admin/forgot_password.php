<link href="<?php echo base_url(); ?>css/admin/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/additional-methods.min.js"></script>	

<style>
.error, #login_error{
	color:#F00;
	display:block;
}
</style>
<script>
$(document).ready(function()
{
	$("#loginPage_form").validate({
		ignore: [],
		debug: false,
		rules:
		{
			txtName: 
				{
					required: true,
					email: true,
				},
		},
		messages:
		{
		}
	});
	
	
	$("#btnforgotPwd").click(function()
	{
		if($("#loginPage_form").valid())
		{
			var username = $("#txtName").val();
			
			$.ajax({
					beforeSend: function() {
						$("#loading_msg").html('Please wait while sending email is in progress...');
						$("#commom_loading").show();
					 },
					url: "<?php echo base_url(); ?>niecpan/clog/check_frgt_password",
					data: {"username":username},
					type: "POST",
					success:function(data)
					{
						if(data == "mail sent")
						{
							$("#loading_msg").empty();
							$("#loginPage_form").hide();
							$("#forgot_pwd_div").html("The password reset link has been sent to your registered email address.");
							$("#commom_loading").hide();
						}
						else
						{
							$("#loading_msg").empty();
							$("#commom_loading").hide();
							$("#login_error").html("Invalid Email ID. The entered email address doesn't exists");
							$("#txtPassword").val("");
						}
					},
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
                <form action="#" name="loginPage_form" id="loginPage_form" onsubmit="return false" enctype="multipart/form-data" method="post">
                    <fieldset class="LoginForm">
                        <h3>Forgot Password</h3>
                        <span id="login_error"></span>
						
                        <p><input type="text" name="txtName" id="txtName" placeholder="Enter your Email ID here" autofocus/></p>
                        <p class="FloatLeft"><input type="submit" name="btnforgotPwd" id="btnforgotPwd" value="Submit"></p>
                    </fieldset>
                </form>
            </div>
        </div>  
    </div>                       
</div>

<div class="MainTitle" id="forgot_pwd_div"></div>