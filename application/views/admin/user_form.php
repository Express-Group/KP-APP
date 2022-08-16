<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="<?php echo $script_url; ?>js/tabcontent.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>
<style>
label.error, #error_txtEmailID, #error_txtUsername, #checkbox_error {
	color:#F00;
	display:block;
}
#mandatory {
	color:#F00;
}
</style>
<?php 

if(isset($editdetails))
{
	$menu_details = array();
foreach($editdetails as $data)
{
	//$op_user_id = $data['User_id'];
	$op_username = $data['Username'];
	$op_password = $data['Password'];
	$op_firstname = $data['Firstname'];
	$op_lastname = $data['Lastname'];
	$op_employeecode = $data['Employeecode'];
	$op_roleid =$data['role_id'];
	$op_mobileno = $data['Mobileno'];
	$op_mailid = $data['MailId'];
	$op_status = $data['status'];
	$page_design = $data['AddPageDesign'];
	$article_opt = $data['AddArticleOption'];
	$Ad_option = $data['AddAdvScripts'];
	$Config_option = $data['AddConfig'];
	$Publish_option = $data['AddPublish'];
	
	$addreleaselocks = $data['addreleaselocks'];
	
	$Menu_id = $data['Menu_Id'];
	$menu_details[$Menu_id]['IsViewAllowed'] = $data['IsViewAllowed'];
	$menu_details[$Menu_id]['IsAddAllowed'] = $data['IsAddAllowed'];
	$menu_details[$Menu_id]['IsEditAllowed'] = $data['IsEditAllowed'];
	$menu_details[$Menu_id]['IsDeleteAllowed'] = $data['IsDeleteAllowed'];
	$menu_details[$Menu_id]['IsPublishAllowed'] = $data['IsPublishAllowed'];
	$menu_details[$Menu_id]['IsUnPublishAllowed'] = $data['IsUnPublishAllowed'];
	
	$op_user_id = $user_id;

}

}
?>
<script>
$(document).ready(function()
{
	$('#txtAccessRights').click(function() 
	{
		 if ($('.select_check:checked').length == $('.select_check').length)
	{
		$('#chkSelectAll').prop('checked', true);
    }
		
	});
	 
	<?php if(isset($op_user_id)) {?>
    if ($('.select_check:checked').length == $('.select_check').length)
	{
		$('#chkSelectAll').prop('checked', true);
    }
	
<?php } ?>

	<?php if(isset($op_image_name) && $op_image_name!="" ) {?>
	$("#image_show").html('Change');
	<?php }?>
	
	
	$("#ddRole").change(function()
	{
		if ($('.select_check:checked').length == $('.select_check').length)
			{
				$('#chkSelectAll').prop('checked', true);
			}
	});
	
	
	$("#fileProfileImage").change(function()
	{
		var file = $('input[type="file"]').val();
		var exts = ['jpg','jpeg','png','GIF','JPG','JPEG','PNG','gif'];
		if ( file )// first check if file field has any value
		{
			var get_ext = file.split('.');// split file name at dot
			get_ext = get_ext.reverse();// reverse name to check extension
			if ($.inArray ( get_ext[0].toLowerCase(), exts ) > -1 )// check file type is valid as given in 'exts' array
			{
				display_image(this);
			} 
		}
	});
	
	
	 $.validator.addMethod("ValidatePassword", function (value) {
        return /[@#]/.test(value)
    }, "The password must contain any one special character @ #");
    
	
	/*$.validator.addMethod("ValidatePassword", function(value, element) {
		//var pattern = new RegExp(/^(?=.*[!@#])[A-Za-z\d][A-Za-z\d!@#]$/);

   return this.optional(element) || value == value.match(/^[a-zA-Z0-9!@#]+$/);
 	//return this.optional(element) || value == value.match(pattern);
	}, "The password must contain any one special character like ! @ #");*/
 

		$("#frmUserMaster").validate(
		{
			
		  ignore: [],
		  debug: false,
		  
		 
		rules: 
		{
			txtUserName: 
			{ 
				required: true,
				//alphanumeric : true,
				maxlength:20,
			},
			txtPassword:
			{
				required: true,
				minlength:8,
				maxlength:15,
				ValidatePassword: true
			},
			ddStatus:
			{
				required: true,	
			},
		
			txtLastName:
			{
				required: true,	
				maxlength:50,
			},
			txtFirstName:
			{
				required: true,	
				maxlength:50,
			},
			ddRole:
			{
				//required: true,	
			},
			
			txtMobileNo:
			{
				//required:true,
				number:true,
				minlength:10,
				maxlength:10,
    		},
			txtContactNo:
			{
				number:true,
				minlength:10,
				maxlength:10,
			},
			txtMobileNoRM:
			{
				number:true,
				minlength:10,
				maxlength:10,	
			},
			txtContactNoRM:
			{
				number:true,
				minlength:10,
				maxlength:10,
			},
			txtEmailID:
			{
				//required: true,
				email:true
			},
			txtAltEmailID:
			{
				email:true
			},
			txtEmailIDRM:
			{
				email:true
			},
			
		},
		messages: 
		{
			txtUserName:
			{
				required: "Please enter user name",
				maxlength:"Please do not enter more than 50 characters."
			},
			txtPassword:
			{
				required:"Please enter the password", 
				minlength:"Please enter atleast 8 characters ",
				maxlength:"Please do not enter more than 15 characters.",
			},
			ddStatus:"Please select status",
			txtFirstName:
			{
				required:"Please enter firstname",
				maxlength:"Please do not enter more than 50 characters."
			},
			txtLastName:
			{
				required:"Please enter lastname",
				maxlength:"Please do not enter more than 50 characters."
			},
			
			ddRole:
			{
				required:"Please select rolename",
			},
			
			txtMobileNo:
			{
				required:"Please enter the mobile number",
				minlength:"Please enter a valid mobile number",
				number:"Please enter valid numbers",
			},
			txtEmailID:
			{
				required:"Please enter the email id",
				email:"Please enter valid email id",
			},
			txtContactNo:
			{
				number:"Please enter valid numbers",
				minlength:"Please enter a valid contact number",
			},
			txtMobileNoRM:
			{
				number:"Please enter valid numbers",
				minlength:"Please enter a valid mobile number",
			},
			txtContactNoRM:
			{
				number:"Please enter valid numbers",
				minlength:"Please enter a valid contact number",
			},
			txtAltEmailID:
			{
				email:"Please enter valid email id",
			},
			txtEmailIDRM:
			{
				email:"Please enter valid email id",
			},
		},
		
	});
		
		
		$("#btnSaveTop").click(function() 
		{
			$('#txtPassword').rules("add",{
				required: true,
				minlength:8,
				maxlength:15,
				ValidatePassword: true
			});
			<?php
			if(isset($op_user_id)&& $op_user_id!="" )
			{
			?>
				$('[name="txtPassword"]').each(function()
				{
					$(this).rules('remove','required');
				});
				
				var pwd_text = $.trim($("#txtPassword").val());
				if(pwd_text == "") {
					$('[name="txtPassword"]').each(function()
					{
						$(this).rules('remove','minlength');
						$(this).rules('remove','maxlength');
						$(this).rules('remove','ValidatePassword');
					});
				}
			<?php } ?>
						
			if($("#frmUserMaster").valid())
			{
					var emailid=$('#txtEmailID').val();
					var user_id=$('#txthiddenid').val();
					var user_name=$('#txtUserName').val();
					$.ajax({
					type: "POST",
					data: {"email_id":emailid,"userid":user_id,"username":user_name},
					url:"<?php echo base_url().folder_name; ?>/user_manager/check_emailID",
					success: function(result)
					{
						if(result == "Username already exist")
						{
							$("#error_txtUsername").html(result);
							$("#error_txtEmailID").html('');
							$("#main_content>ul>li.selected").removeClass("selected");	
							$("#user_menu").addClass('selected');
							$("#view3").show();
							$("#view2").hide();
							return false;
						}
						else if(result == "Email ID already exist" && emailid != "")
						{
							$("#error_txtEmailID").html(result);
							$("#error_txtUsername").html('');
							$("#main_content>ul>li.selected").removeClass("selected");	
							$("#user_menu").addClass('selected');
							$("#view3").show();
							$("#view2").hide();
							return false;
						}
						else
						{
							if($('.select_check:checked').size() == 0)
							{
								$("#main_content>ul>li.selected").removeClass("selected");	
								$("#accessrights_content").addClass('selected');
								$("#view2").show();
								$("#view3").hide();
								$("#checkbox_error").html("please select atleast one checkbox");
							}
							else
							{
								$("#checkbox_error").html('');//alert('no');
								$("#error_txtUsername").html('');
								$("#error_txtEmailID").html('');
								<?php if(isset($op_user_id)) { ?>
						var r = confirm("Are you sure you want to update user details?");
						<?php } else {?>
						var r = confirm("Are you sure you want to add user details?");
						<?php }?>
							if(r==true)
							{
								$("#frmUserMaster").submit();
							}
							else
							{
								return false;
							}
								
							
							}
						}
					}
					});
			}
			else
			{
					$("#main_content>ul>li.selected").removeClass("selected");	
					$("#user_menu").addClass('selected');
					$("#view3").show();
					$("#view2").hide();
					if($('.select_check:checked').size() == 0)
					{
						$("#checkbox_error").html("please select atleast one checkbox");
					}
			}
		});
		
		
		$('#chkSelectAll').click(function(event) 
		{
			if(this.checked) 
			{ 
				$('.select_check').each(function() 
				{
					this.checked = true;                 
				});
			}
			else
			{
				$('.select_check').each(function()
				{ 
				   this.checked = false;                      
				});         
			}
    	});
	 
	 
	<?php if(isset($op_user_id)) { ?>
	
	var dptname=$('#txthdndptname').val();
	var dptid=$('#txthdndptid').val();
	if($.trim(dptname)!='')
	
	$("#ddDepartment").append($("<option selected=selected></option>").val(dptid).html(dptname));
	
	<?php }?>
});
	
</script>

<form name="frmUserMaster" id="frmUserMaster" method="post" action="<?php echo base_url().folder_name."/user_manager/user_details";?>" enctype="multipart/form-data" autocomplete="off" >
	<div class="Container">
		<div class="BodyWhiteBG">
			<div class="BodyHeadBg Overflow clear">
				<div class="FloatLeft BreadCrumbsWrapper">
					<div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
					<h2 class="FloatLeft"><?php echo $title; ?></h2>
				</div>
				<p class="FloatRight save-back save_margin"> <a class="FloatLeft back-top" href="<?php echo base_url().folder_name."/user_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
					<button class="btn-primary btn" type="button" id="btnSaveTop"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
				</p>
				<input style="display:none" type="text"/>
				<input style="display:none" type="password"/>
			</div>
			<div class="Overflow DropDownWrapper create-main" id="main_content">
				<ul class="tabs">
					<li class="selected" id="user_menu"><a href="#" onclick="userdata();" >User Data</a></li>
					<li class="" id="accessrights_content"><a href="#" onclick="accessrights();" id="txtAccessRights">Access-Rights</a></li>
				</ul>
				<section class="tap-main">
					<div class="tabcontents">
						<div id="view3" style="display: block;">
							<div class="user-top">
								<div class="qnsans">
									<div class="qns-use">
										<label class="question">User Name<span id="mandatory">*</span></label>
									</div>
									<div class="ans-use">
										<input type="hidden" name="txthiddenid" id="txthiddenid" value="<?php if(isset($op_user_id)){ echo $op_user_id;}?>"  />
										<input type="text" name="txtUserName" id="txtUserName" maxlength="20" value="<?php if(isset($op_username)){ echo $op_username;}else{echo set_value('txtSectionName');}?>" class="user-style box-shad box-shad1" autocomplete="off" >
										<p id="error_txtUsername"></p>
									</div>
									<div class="qns-use" id="hide_pwd">
										<label class="question" > Password <span id="mandatory">*</span></label>
									</div>
									<div class="ans-use">
										<input type="password" name="txtPassword" maxlength="100" id="txtPassword"  class="user-style box-shad box-shad1"  autocomplete="new-password">
									</div>
									<div class="qns-use">
										<label class="question">Status</label>
									</div>
									<div class="right031">
										<div class="w2ui-field">
											<div class="FloatLeft w2ui-field">
												<select id="ddStatus" name="ddStatus" class="controls">
													<option value="1" <?php if(isset($op_status)){ if($op_status==1){?> selected="selected"<?php }}else{echo set_select('ddStatus',1);}?> >Active</option>
													<option value="0" <?php if(isset($op_status)){ if($op_status==0){?> selected="selected"<?php }}else{echo set_select('ddStatus',0);}?> >In Active</option>
												</select>
											</div>
											<div class="w2ui-field-helper"> </div>
										</div>
									</div>
								</div>
							</div>
							<fieldset class="create-use-set">
								<legend><b>User Details</b></legend>
								<div class="qnsans">
									<div class="qns2">
										<label class="question">Full Name<span id="mandatory">*</span></label>
									</div>
									<div class="use-detail">
										<input type="text" name="txtFirstName" id="txtFirstName" maxlength="50" value="<?php if(isset($op_firstname)){ echo $op_firstname;}else{echo set_value('txtSectionName');}?>" class="user-style box-shad box-shad1" placeholder="First Name">
									</div>
									<div class="use-detail">
										<input type="text" name="txtLastName" id="txtLastName" maxlength="50"  class="user-style box-shad box-shad1" value="<?php if(isset($op_lastname)){ echo $op_lastname;}else{echo set_value('txtSectionName');}?>" placeholder="Last Name">
									</div>
								</div>
								<div class="qnsans">
									<div class="qns2">
										<label>Employee code</label>
									</div>
									<div class="ans">
										<input type="text" name="txtEmployeeCode" id="txtEmployeeCode" maxlength="20" value="<?php if(isset($op_employeecode)){ echo $op_employeecode;}else{echo set_value('txtSectionName');}?>" class="article_id tb_style box-shad box-shad1">
									</div>
								</div>
								<div class="qnsans">
									<div class="qns2">
										<label>Role</label>
									</div>
									<div class="right031">
										<div class="w2ui-field">
											<div class="FloatLeft w2ui-field">
												<select id="ddRole" name="ddRole" class="controls" onchange="role_rights();">
													<option value="">-Select-</option>
													<?php 
													if(isset($rolename)) 
													{ 
														foreach($rolename as $role)
														{
													?>
													<option class="unavailable" data-name="k" value="<?php echo $role['role_id']; ?>" <?php if(isset($op_roleid) && $op_roleid ==  $role['role_id'] ) echo  "selected='selected'";  ?> > <?php echo ucfirst($role['rolename']); ?></option>
													<?php } } ?>
												</select>
												<input type="hidden" id="txthdnrolename" name="txthdnrolename" value="<?php if(isset($op_user_id)){ echo get_rolename_by_id($op_roleid);} ?>">
												<input type="hidden" id="txthdnroleid" name="txthdnroleid" value='<?php echo $op_roleid; ?>'>
											</div>
											<div class="w2ui-field-helper"> </div>
										</div>
									</div>
								</div>
								<div class="qnsans">
									<div class="qns2">
										<label>Mobile Number</label>
									</div>
									<div class="ans">
										<input type="text" name="txtMobileNo" id="txtMobileNo" maxlength="10" value="<?php if(isset($op_mobileno)){ echo $op_mobileno;}else{echo set_value('txtSectionName');}?>" class="article_link tb_style box-shad box-shad1">
									</div>
								</div>
								<div class="qnsans">
									<div class="qns2">
										<label>Email ID</label>
									</div>
									<div class="ans-use1">
										<input type="text" name="txtEmailID" id="txtEmailID" value="<?php if(isset($op_mailid)){ echo $op_mailid;}else{echo set_value('txtSectionName');}?>" class="article_link user-style1 box-shad box-shad1">
										<p id="error_txtEmailID"></p>
									</div>
								</div>
							</fieldset>
						</div>
						<div id="view2" style="display:none" >
							<div class="create-tab">
								<div class="CompareArticle">
									<div class="CompareTop"> </div>
									<label id="checkbox_error" ></label>
									<div  class="FloatRight">
										<input type="checkbox" name="chkSelectAll" id="chkSelectAll" >
										<label for="chkSelectAll" >Select All</label>
									</div>
									<table cellpadding="0" cellspacing="0">
										<tr>
											<th>Department</th>
											<th>View</th>
											<th>Add</th>
											<th>Edit</th>
											<th>Delete</th>
											<th>Publish</th>
											<th>UnPublish</th>
										</tr>
										<tr>
											<?php
											$i = 0;
											foreach($rawdata as $temp_data)
											{
												$i++;
												$Menu_id = $temp_data['Menu_id'];
												$add_opt=$temp_data['IsAddOptionAvailable'];
												$edit_opt=$temp_data['IsEditOptionAvailable'];
												$delete_opt=$temp_data['IsDeleteOptionAvailable'];
												$publish_opt=$temp_data['IsPublishOptionAvailable'];
												$unpublish_opt=$temp_data['IsUnPublishOptionAvailable'];
												
												$checked_val = 'class="select_check"';
											 ?>
										<tr <?php  if($i%2 != 0) {  $bgcolor = " background-color: #c5e7f6;"; } else {$bgcolor = "background-color: #dff5ff;"; }
										
										 if($temp_data['MenuName'] == "Top content") { $checked_val = 'checked="checked"'; echo "style='display:none; ".$bgcolor."'" ; $i--; } else { echo "style='".$bgcolor."'"; } ?>>
											<td><input type="hidden" name="txtMenuId" value="<?php echo $temp_data['Menu_id'];?>">
												<?php echo $temp_data['MenuName'];?></td>
											<td><input type="checkbox" <?php echo $checked_val; ?> id="chkView_<?php echo $temp_data['Menu_id'];?>" onClick="uncheck_checkbox(<?php echo $temp_data['Menu_id'];?>)"   name="rights[<?php echo $temp_data['Menu_id'];?>][view]"  value=1 <?php if(isset($menu_details[$Menu_id]['IsViewAllowed'])){ if( $menu_details[$Menu_id]['IsViewAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',1);}?>  /></td>
											<td><?php if($add_opt==1) { ?>
												<input type="checkbox" <?php echo $checked_val; ?> id="chkAdd_<?php echo $temp_data['Menu_id'];?>" onclick="validate(<?php echo $temp_data['Menu_id'];?>)"   name="rights[<?php echo $temp_data['Menu_id'];?>][add]" value=1  <?php if(isset($menu_details[$Menu_id]['IsAddAllowed'])){ if($menu_details[$Menu_id]['IsAddAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',1);}?>/>
												<?php } ?></td>
											<td><?php if($edit_opt==1) { ?>
												<input type="checkbox" <?php echo $checked_val; ?> id="chkEdit_<?php echo $temp_data['Menu_id'];?>" onclick="validate(<?php echo $temp_data['Menu_id'];?>)"   name="rights[<?php echo $temp_data['Menu_id'];?>][edit]" value=1 <?php if(isset($menu_details[$Menu_id]['IsEditAllowed'])){ if($menu_details[$Menu_id]['IsEditAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',1);}?> />
												<?php } ?></td>
											<td><?php if($delete_opt==1) { ?>
												<input type="checkbox" <?php echo $checked_val; ?> id="chkDelete_<?php echo $temp_data['Menu_id'];?>" onclick="validate(<?php echo $temp_data['Menu_id'];?>)"  name="rights[<?php echo $temp_data['Menu_id'];?>][delete]" value=1 <?php if(isset($menu_details[$Menu_id]['IsDeleteAllowed'])){ if($menu_details[$Menu_id]['IsDeleteAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',1);}?>/>
												<?php }?></td>
											<td><?php if($publish_opt==1) {?>
												<input type="checkbox" <?php echo $checked_val; ?>  id="chkPublish_<?php echo $temp_data['Menu_id'];?>" onclick="validate(<?php echo $temp_data['Menu_id'];?>)" name="rights[<?php echo $temp_data['Menu_id'];?>][publish]" value=1 <?php if(isset($menu_details[$Menu_id]['IsPublishAllowed'])){ if($menu_details[$Menu_id]['IsPublishAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',1);}?> />
												<?php } ?></td>
											<td><?php if($unpublish_opt==1) { ?>
												<input type="checkbox" <?php echo $checked_val; ?>  id="chkUnPublish_<?php echo $temp_data['Menu_id'];?>" onclick="validate(<?php echo $temp_data['Menu_id'];?>)" name="rights[<?php echo $temp_data['Menu_id'];?>][unpublish]" value=1 <?php if(isset($menu_details[$Menu_id]['IsUnPublishAllowed'])){ if($menu_details[$Menu_id]['IsUnPublishAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',1);}?>   />
												<?php }?></td>
										</tr>
										<?php } ?>
									</tr>
										
									</table>
									<div class="roleaccess_rights">
										<h2>Front Page Manager Access Rights</h2>
										<ul>
											<li>
												<input type="checkbox" name="chkPageDesign" id="chkPageDesign" class="select_check"  value=1 <?php if(isset($page_design)){ if( $page_design == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkPageDesign',1);}?>>
												<label for="chkPageDesign">Create/Delete Page Design, Add Configuration & Publish Template</label>
											</li>
											<!--<li>
												<input type="checkbox" name="chkConfiguration" id="chkConfiguration" class="select_check" value=1 <?php if(isset($Config_option)){ if( $Config_option == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkConfiguration',1);}?> >
												<label for="chkConfiguration">Add configuration</label>
											</li>-->
											<li>
												<input type="checkbox" name="chkAddArticle" id="chkAddArticle" class="select_check" value=1 <?php if(isset($article_opt)){ if( $article_opt == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkAddArticle',1);}?> >
												<label for="chkAddArticle">Add article</label>
											</li>
											<li>
												<input type="checkbox" name="chkAdvertisement" id="chkAdvertisement" class="select_check" value=1 <?php if(isset($Ad_option)){ if( $Ad_option == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkAdvertisement',1);}?>>
												<label for="chkAdvertisement">Add Advertisement Script</label>
											</li>
											<!--<li>
												<input type="checkbox" name="chkPublish" id="chkPublish" class="select_check" value=1 <?php if(isset($Publish_option)){ if( $Publish_option == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkPublish',1);}?>>
												<label for="chkPublish">Publish</label>
											</li>-->
											<li>
												<input type="checkbox" name="chkreleasetemplate" id="chkreleasetemplate" class="select_check" value=1 <?php if(isset($addreleaselocks)){ if( $addreleaselocks == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkreleasetemplate',1);}?>>
												<label for="chkreleasetemplate">Release Template Locks</label>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript" language="javascript">


function display_image(input)
{
	if (input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function (e)
		{
			$('#imgprofileimage').attr('src', e.target.result);
			$("#imgprofileimage").show();
			$("#image_show").html('Change');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function role_rights()
{
	$('input[type="checkbox"]').prop('checked', false);
	
	var selected_roleid=$('#ddRole').val();
	$.ajax({
			type: "POST",
			data: {"role_id":selected_roleid},
			dataType: "json",
			url: "<?php echo base_url().folder_name; ?>/user_manager/edit_details",
			success: function(data)
			{
				var IsViewAllowed=''
				var IsAddAllowed=''
				var IsEditAllowed=''
				var IsDeleteAllowed=''
				var IsPublishAllowed=''
				var IsUnPublishAllowed=''
				$.each(data, function(i, item)
				{
					if(data[i].IsViewAllowed == 1)
					{
						$("input[ id='chkView_" + data[i].Menu_Id + "'").prop('checked', true);
					}
					else
						$("input[ id='chkView_" + data[i].Menu_Id + "'").prop('checked', false);
					if(data[i].IsAddAllowed == 1)
					{
						$("input[ id='chkAdd_" + data[i].Menu_Id + "'").prop('checked', true);
					}
					else
						$("input[ id='chkAdd_" + data[i].Menu_Id + "'").prop('checked', false);
					if(data[i].IsEditAllowed == 1)
					{
						$("input[ id='chkEdit_" + data[i].Menu_Id + "'").prop('checked', true);
					}
					else
						$("input[ id='chkEdit_" + data[i].Menu_Id + "'").prop('checked', false);
					if(data[i].IsDeleteAllowed == 1)
					{
						$("input[ id='chkDelete_" + data[i].Menu_Id + "'").prop('checked', true);
					}
					else
						$("input[ id='chkDelete_" + data[i].Menu_Id + "'").prop('checked', false);
					if(data[i].IsPublishAllowed == 1)
					{
						$("input[ id='chkPublish_" + data[i].Menu_Id + "'").prop('checked', true);
					}
					else
						$("input[ id='chkPublish_" + data[i].Menu_Id + "'").prop('checked', false);
					if(data[i].IsUnPublishAllowed == 1)
					{
						$("input[ id='chkUnPublish_" + data[i].Menu_Id + "'").prop('checked', true);
					}
					else
						$("input[ id='chkUnPublish_" + data[i].Menu_Id + "'").prop('checked', false);
					if(data[i].AddPageDesign == 1)
					{
						$("#chkPageDesign").prop('checked', true);
					}
					else
						$("#chkPageDesign").prop('checked', false);					
					
					if(data[i].AddConfig == 1)
					{
						$("#chkConfiguration").prop('checked', true);
					}
					else
						$("#chkConfiguration").prop('checked', false);
					
					if(data[i].AddArticleOption == 1)
					{
						$("#chkAddArticle").prop('checked', true);
					}
					else
						$("#chkAddArticle").prop('checked', false);
					if(data[i].AddAdvScripts == 1)
					{
						$("#chkAdvertisement").prop('checked', true);
					}
					else
						$("#chkAdvertisement").prop('checked', false);
					if(data[i].AddPublish == 1)
					{
						$("#chkPublish").prop('checked', true);
					}
					else
						$("#chkPublish").prop('checked', false);
						
					if(data[i].addreleaselocks == 1)
					{
						$("#chkreleasetemplate").prop('checked', true);
					}
					else
						$("#chkreleasetemplate").prop('checked', false);	
				});
			}
	});
}



function accessrights()
{
	/*if($('#ddRole').val()=="")
	{
		alert('please select role name');
		$("#main_content>ul>li.selected").removeClass("selected");
		$("#user_menu").addClass('selected');
		$("#view3").show();
		$("#view2").hide();
		return false;
	}
	else
	{*/
		$("#main_content>ul>li.selected").removeClass("selected");
		$("#accessrights_content").addClass('selected');
		$("#view3").hide();
		$("#view2").show();
	//}
}

function userdata()
{
	$("#main_content>ul>li.selected").removeClass("selected");
	$("#user_menu").addClass('selected');
	$("#view3").show();
	$("#view2").hide();
}


function validate(id)
{	

	var add = document.getElementById('chkAdd_'+id);
	add = (add == null) ? '' : add.checked;
	var edit= document.getElementById('chkEdit_'+id);
	edit = (edit == null) ? '' : edit.checked;
	var del= document.getElementById('chkDelete_'+id);
	del = (del == null) ? '' : del.checked;
	var publish= document.getElementById('chkPublish_'+id);
	publish = (publish == null) ? '' : publish.checked;
	var unpublish= document.getElementById('chkUnPublish_'+id);
	unpublish = (unpublish == null) ? '' : unpublish.checked;
	
	
	if(add || edit || del || publish || unpublish)
	{
		$('#chkView_'+id+'').prop('checked', true);
	}
	else
	{
		$('#chkView_'+id+'').prop('checked', false);
	}
}

function uncheck_checkbox(id)
{
	var view = $('#chkView_'+id).is(':checked');
	
	var add = document.getElementById('chkAdd_'+id);
	add = (add == null) ? '' : add.checked;
	var edit= document.getElementById('chkEdit_'+id);
	edit = (edit == null) ? '' : edit.checked;
	var del= document.getElementById('chkDelete_'+id);
	del = (del == null) ? '' : del.checked;
	var publish= document.getElementById('chkPublish_'+id);
	publish = (publish == null) ? '' : publish.checked;
	var unpublish= document.getElementById('chkUnPublish_'+id);
	unpublish = (unpublish == null) ? '' : unpublish.checked;
	
	if(view == false)
	{
		$('#chkAdd_'+id+'').prop('checked', false);
		$('#chkEdit_'+id+'').prop('checked', false);
		$('#chkDelete_'+id+'').prop('checked', false);
		$('#chkPublish_'+id+'').prop('checked', false);
		
		$('#chkUnPublish_'+id+'').prop('checked', false);
	}
}

</script> 
