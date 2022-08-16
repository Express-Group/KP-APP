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
				{ required: true, },
			txtPassword:
				{ required: true,},
		},
		messages:
		{
			txtName: 
				{ required: "Please enter username", 
				  email: "Please enter a valid email id",
				},
			txtPassword:
				{ required: "Please enter password", },
		}
	});
	
	
	$("#btnLoginSubmit").click(function()
	{
		if($("#loginPage_form").valid())
		{
			var username = $("#txtName").val();
			var password = $("#txtPassword").val();
			
			$.ajax({
					beforeSend: function() { $("#normal_loading").show() },
					url: "<?php echo base_url(); ?>niecpan/clog/check_username",
					data: {"username":username,"password":password, "page_detail":"login"},
					type: "POST",
					success:function(data)
					{
						if(data == "success")
						{
							window.location.replace("<?php echo base_url();?>niecpan/");
						}
						else if(data == "inactive")
						{
							$("#normal_loading").hide();
							$("#login_error").html("User is in inactive status. Please contact administrator");
							$("#txtPassword").val("");
						}
						else
						{
							$("#normal_loading").hide();
							$("#login_error").html("Invalid username/password. Please try again");
							$("#txtPassword").val("");
						}
					},
			});
		}
	});
});


</script>


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
                        <h3>Login</h3>
                        <span id="login_error"></span>
                        <p><input type="text" name="txtName" id="txtName" placeholder="Username" autofocus/></p>
                        <p><input type="password" name="txtPassword" id="txtPassword"  placeholder="Password" /></p>
                        <p class="FloatLeft"><input type="submit" name="btnLoginSubmit" id="btnLoginSubmit" value="Login"></p>
                        <p class="FloatLeft ForgotPass"> <a href="<?php echo base_url(); ?>niecpan/clog/forgot_pwd">Forgot Password?</a></p>
                    </fieldset>
                </form>
            </div>
        </div>  
    </div>                       
</div>