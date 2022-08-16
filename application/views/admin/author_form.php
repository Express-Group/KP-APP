<?php $script_url = image_url; ?>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>
<?php
if(isset($authors))
{
	
foreach($authors as $data)
{
	$author_id=$data['Author_id'];
	$display_name = $data['AuthorName'];
	$email_id=$data['Email'];
	$biography=stripslashes($data['ShortBiography']);
	$status=$data['Status'];
	//$agency_name=$data['ExternalAgencyName'];
	$type=$data['authorType'];
	//$source=$data['Source'];
	//$image_path=$data['Displayimagepath'];
	//$profile_image=$data['Displayimage'];
	//$agency_name=$data['Agency_name'];
	$agencyid=$data['Agency_id'];
	$topicid=$data['column_id'];
	$contentid=$data['image_id'];
	$image_path=$data['image_path'];
	$image_alt=$data['image_alt'];
	$image_caption=$data['image_caption'];
}
}
 ?>
<script>
function byLine()
{
	$(".topic").removeClass("showdiv");
	$("#topicname_display").addClass("none");
	$("#call_show1").removeClass("none");
	$("#call_show1").addClass("showdiv");
	$("#agencyname_display").removeClass("none");
	$("#agencyname_display").addClass("showdiv");
	
	//Make sure schoolDIV is not visible
	$("#call_show2").removeClass("showdiv");
	$("#call_show2").addClass("none");
	
	$("#call_show3").removeClass("showdiv");
	$("#call_show3").addClass("none");

}

function byCol()
{
	$("#call_show2").removeClass("none");
	$("#call_show2").addClass("showdiv");
	$("#call_show3").removeClass("none");
	$("#call_show3").addClass("showdiv");
	$("#agencyname_display").removeClass("showdiv");
	$("#agencyname_display").addClass("none");
	//Make sure bankDIV is not visible
	$("#call_show1").removeClass("showdiv");
	$("#call_show1").addClass("none");
}


 
</script>
<style>
label.error {
	color:#F00;
	display:block;
}
.mandatory {
	color:#F00;
}
</style>
<script>
$(document).ready(function()
{
	/*$("#physical_name").on('input blur',function(e) {
		var value = $("#physical_name").val();
		result = value.replace(/[^a-zA-Z0-9_-]/g,'');
			
		$("#physical_name").val(result);
	});*/
	
	
	$.validator.addMethod("equalToImage", 
	function(physical_name, element, params) {
		
		var home_bool = true;

		if($("#physical_name").val() != '') {
		postdata = "physical_name="+physical_name+'.'+$('#physical_name').attr('physical_extension')+"&temp_id="+$("#physical_name").attr('rel');
			$.ajax({
				url:'<?php echo base_url().folder_name."/common/check_image_name";?>',
				type: "POST",             // Type of request to be send, called as method
				data:  postdata,
				dataType: "json",
				async: false, 
				success: function(data)
				{
					if(data.status == 'false') {
						home_bool = false;
					} 
				}
			});
		}
			return home_bool;
	},'Image name already exists');
	
	$.validator.addMethod("CheckAuthorName", function() {
		
		var return_value = true;
		if($("#displayname").val() != '')
		{
			var columnst_name = $("#displayname").val();
			var columnst_id = $("#txtAuthorId").val();
			$.ajax({
				url:'<?php echo base_url().folder_name."/author_manager/check_authorname";?>',
				type: "POST",             // Type of request to be send, called as method
				data:  {"field_input":columnst_name, "author_id":columnst_id},
				//dataType: "json",
				async: false, 
				success: function(data)
				{
					if($.trim(data) == "exist") {
						return_value = false;
					} 
				}
			});
		}
		return return_value;
	},'Columinst Name already exists.');
	
	$.validator.addMethod("Imagesize", function(){
		var return_val = true;
		var image = $('#imgProfileImage');
		if($('#imgProfileImage').attr("src") != "" && $('#imgProfileImage').attr("src") != "#") 
		{
			var originalWidth = image[0].naturalWidth; 
			var originalHeight = image[0].naturalHeight;
			if(originalWidth != 150 && originalHeight != 150)
			{
				return_val = false;
			}
		}
		return return_val;
	 }, 'Please upload 150X150 image.');
	 
	 
	
	$("#authorform").validate({
	rules: 
	{			
		txtdisplayname: 
		{ 
			required: true,
			CheckAuthorName: true,
		},
		txtemail:
		{
			//required: true,
			email:true,
		},
		
		txtexternalname:
		{
			required: true,
		},
		ddagencyname:
		{
			required: true,
		},
		//physical_name: {  equalToImage : true},
		//uploaduserimage: {accept: "jpg|jpeg|png|gif", Imagesize: true },
		uploaduserimage: {accept: "jpg|jpeg|png|gif", },
		},
	messages: 
	{
		txtemail:
		{
			required: "Please enter email id ",
			email:"Please enter email id in correct format",
		},
		uploaduserimage:
		{
			 required:"Please select profile image",
			 accept: "Please select only png, jpg or gif file types",
		},
		ddagencyname:
		{
			required: "Please select agency name",
		},
		/*ddtopicname:
		{
			required: "Please select topic name",
		}*/
		txtexternalname:"Please enter external agency name",
		txtdisplayname:
		{	
			required: function() 
			{
            	var myValue = $('input[name=rbtab-group-1]:checked').val();
            	if (myValue == 'columnist') 
				{
                	return "Please enter display name of columnist";
            	} 
				else if (myValue =='Byline') 
				{
                	return "Please enter display name of byliner";
            	}
        	}
		}
	},
		errorPlacement: function (error, element) 
		{
			 if(element.attr("name") == 'uploaduserimage')
			 {   
			 	error.insertAfter($("#error_uploaduserimage"));
			 }
			 else
			 {
				error.insertAfter(element);
			 }
		 },

	});
		
		$("#btnSaveTop, #btnAuthorAdd").click(function() 
		{
			//if($("#physical_name").val() != '' && $("#img_id").val() == "")	
			if($("#uploaduserimage").val() != '')	
			{
				$('[name="uploaduserimage"]').each(function()
				{
					$(this).rules('add',
					{
						Imagesize : true,
					});
				});
			}
			else
			{
				/*$('[name="uploaduserimage"]').each(function(){
					$(this).rules('remove',
					{
						Imagesize : true,
					});
				});*/
			}
		
		  	/*if($("#img_id").val() != "" && $("#txtAuthorId").val() != "")	
			{
				$('[name="physical_name"]').each(function()
				{
					$(this).rules('remove', 'equalToImage');
				});
			}*/
				  
			if($("#authorform").valid())
			{
				var emailid=$('#txtemailID').val();
				var author_id=$('#txtAuthorId').val();
		 		$.ajax({
				url:'<?php echo base_url().folder_name."/author_manager/emailcheck";?>',
				type:"POST",
				data:{"field_input":emailid,"author_id":author_id},
				success: function(data) 
				{
					if(data=="Already exist" && emailid != "")
					{
						$('#error_email').html('Email ID already exist');
						return false;
					}
					else
					{
						$('#error_email').html('');
						<?php
						if(isset($authors[0]['Author_id']))
						{
						?>
						if($('input[name=rbtab-group-1]:checked').val()=='Byline')
						{
							var r = confirm("Are you sure you want to update byliner details?");
							if(r==true)
							{
								$("#authorform").submit();
							}
							else
							{
								return false;
							}
						}
						
						else
						{
							var r = confirm("Are you sure you want to update columnist details?");
							if(r==true)
							{
								$("#authorform").submit();
							}
							else
							{
								return false;
							}
						}
						<?php } else { ?>
						
						if($('input[name=rbtab-group-1]:checked').val()=='Byline')
						{
							var r = confirm("Are you sure you want to add byliner details?");
							if(r==true)
							{
								$("#authorform").submit();
							}
							else
							{
								return false;
							}
						}
						else
						{
							var r = confirm("Are you sure you want to add columnist details?");
							if(r==true)
							{
								$("#authorform").submit();
							}
							else
							{
								return false;
							}
							
						}
						<?php }?>
					}
				}
				});
			} 
	});
			
			
if($('input:radio[name="rbbylinesource"]:checked').val() == "External")
{
	$("#externalname").addClass("showdiv");
	$("#externalname").removeClass("none");
}
if($('input:radio[name="rbtab-group-1"]:checked').val() == "columnist")
{
	$("#call_show2").removeClass("none");
	$("#call_show2").addClass("showdiv");
	
	$("#call_show3").removeClass("none");
	$("#call_show3").addClass("showdiv");
	
	//Make sure bankDIV is not visible
	$("#call_show1").removeClass("showdiv");
	$("#call_show1").addClass("none");
	
	$("#agencyname_display").removeClass("showdiv");
	$("#agencyname_display").addClass("none");
	
	$("#topicname_display").removeClass("none");
	$("#topicname_display").addClass("showdiv");
	
}

<?php if(isset($author_id)) { ?>


var agencyname=$('#txthdnagency').val();
var agnyid=$('#txthdnagencyid').val();
//alert(dptname);
if($.trim(agencyname)!='')

$("#ddagencyname").append($("<option selected=selected></option>").val(agnyid).html(agencyname));

<?php }?>	
	
				
				
$("#removeimage").click(function()
{
	$('#imgProfileImage').attr("src", "<?php echo base_url();?>images/admin/author.jpg");
	$("#image_show").html('Upload');
	$("#img_id").val('');
	$("#image_alt").val('');
	$("#image_path").val('');
	$("#image_caption").val('');
	$("#physical_name").val('');
	$("#uploaduserimage").val('');
	$("#removeimage").hide();
	
	$('#uploaduserimage').rules('remove', "Imagesize");
	
});	

			
});
		
		

</script>

<div class="Container">
	<div class="BodyWhiteBG" >
		<form action="<?php echo base_url().folder_name."/author_manager/addauthordetails";?>"  name="authorform" id="authorform" method="post" enctype="multipart/form-data" >
			<input type="hidden" name="page" value="addauthor" />
			<div class="BodyHeadBg Overflow clear">
				<?php if(isset($author_id) ){?>
				<div class="FloatLeft BreadCrumbsWrapper PollResult">
					<div class="breadcrumbs">Dashboard > <?php echo "Edit ".$title?></div>
					<h2 class="FloatLeft"><?php echo "Edit ".$title?></h2>
				</div>
				<?php } else {?>
				<div class="FloatLeft BreadCrumbsWrapper PollResult">
					<div class="breadcrumbs">Dashboard > <?php echo "Add ".$title ?></div>
					<h2 class="FloatLeft"><?php echo "Add ".$title; ?></h2>
				</div>
				<?php }?>
				<?php
				if(($this->session->flashdata("success")))
				{
				?>
				<div class="FloatLeft Success"><?php echo $this->session->flashdata("success");?></div>
				<?php
				}
				?>
				<?php
				if(($this->session->flashdata("error")))
				{
				?>
				<div class="FloatLeft Error"><?php echo $this->session->flashdata("error");?></div>
				<?php
				}
				?>
				<div class="FloatLeft Error" id="emailerror_exist" style="display:none"></div>
				<?php 
				if($type == 1)
					$manager_page = "byliner_manager"; 
				elseif($type == 2)
					$manager_page = "columnist_manager"; 
				?>
				<p class="FloatRight save-back"> <a href="<?php echo base_url().folder_name."/".$manager_page;?>" class="FloatLeft back-top"><i class="fa fa-reply fa-2x"></i></a>
					<button type="button" id="btnSaveTop" class="btn-primary btn"  ><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
				</p>
			</div>
			<div class="tabs Overflow" style="margin-top:10%;">
				<div class="tab" style="display:none;">
					<div class="switch switch-blue">
						<input type="radio" id="tab-1 week2" name="rbtab-group-1" <?php if( (isset($type) && $type==1) ){ ?>  checked="checked" <?php }?>onclick="byLine()" class="switch-input"  value="Byline" >
						<label for="tab-1 week2" class="tab-12 switch-label switch-label-off">Byline</label>
						<input type="radio" id="tab-2 month2" name="rbtab-group-1" <?php if( (isset($type) && $type==2) ){ ?> checked="checked" <?php }?>  onclick="byCol()"  class="switch-input" value="columnist" >
						<label for="tab-2 month2" class="tab-12 switch-label switch-label-on">Columnist</label>
						<span class="switch-selection"></span> </div>
				</div>
				<div class="content">
					<div class="columnist1">
						<p>
							<input type="hidden" name="txtAuthorId" id="txtAuthorId" value="<?php if(isset($author_id)){ echo $author_id;}?>" size="2">
						</p>
						<div id="agencyname_display" class="AuthorAgency">
							<p>
								<label class="txtAgencyname" id="name">Agency Name</label>
								<span class="mandatory">*</span></p>
							<p></p>
							
							<!--<div class="w2ui-label"> List: </div>-->
							<div class="w2ui-field">
								<select id="ddagencyname" name="ddagencyname" class="controls">
									<option value="">-Select-</option>
									<?php
									foreach($agencyname as $data)
									{
									   echo $agency_id = $data['Agency_id'];
									?>
									<option value="<?php echo $agency_id;?>" <?php if(isset($agencyid)){ if($agencyid == $agency_id){?> selected="selected"<?php }}else{echo set_select('ddagencyname',$agency_id);}?>   ><?php echo $data['Agency_name'];?></option>
									<?php  }  ?>
								</select>
								<input type="hidden" id="txthdnagency" name="txthdnagency" value="<?php if(isset($author_id)){ echo get_agency_by_id($agencyid);} ?>">
								<input type="hidden" id="txthdnagencyid" name="txthdnagencyid" value='<?php echo @$agencyid; ?>'>
							</div>
							<p></p>
						</div>
						<p>
							<label class="txtdisplayname" id="name">Display Name</label>
							<span class="mandatory">*</span></p>
						<p>
							<input id="displayname" class="box-shad box-shad1" name="txtdisplayname" type="text" maxlength="50" value="<?php if(isset($display_name)){ echo $display_name;}else{echo set_value('txtdisplayname');}?>" >
							
							<input id="columinst_name" name="columinst_name" type="hidden" maxlength="50" value="<?php if(isset($display_name)){ echo $display_name;}else{echo set_value('txtdisplayname');}?>" >
						</p>
						<p class="mandatory" id="error_displayname"><?php echo form_error('txtdisplayname');?></p>
						<div id="topicname_display" class="none">
							<p>
								<label class="txtTopicname" id="name2">Topic Name</label>
							</p>
							<p></p>
							
							<!--<div class="w2ui-label"> List: </div>-->
							<div class="w2ui-field">
								<select id="ddtopicname" name="ddtopicname" class="controls">
									<option value="">-Select-</option>
									<?php
									foreach($topicname as $data)
									{
									   $id = $data['column_id'];
									?>
									<option value="<?php echo $id;?>" <?php if(isset($topicid)){ if($topicid == $id){?> selected="selected"<?php }}else{echo set_select('ddagencyname',$id);}?>   ><?php echo $data['column_name'];?></option>
									<?php } ?>
								</select>
							</div>
							<p></p>
						</div>
						<p>
							<label name="txtemail" id="txtemail">Email</label>
						</p>
						<p>
							<input id="txtemailID" class="box-shad box-shad1" name="txtemail"  type="text" placeholder="test@example.com" maxlength="100" value="<?php if(isset($email_id)){ echo $email_id;}else{echo set_value('txtemail');}?>">
						</p>
						<p class="mandatory" id="error_email">
							<?php	echo form_error('txtemail'); ?>
						</p>
						<p class="Biogrpaphy">
							<label class="biography" id="biographys">Short Biography</label>
						</p>
						<p class="TextAreaWidth">
							<textarea  id="biography" name="txtbiography" class="biography1 box-shad box-shad1"><?php if(isset($biography)){ echo $biography;}else{echo set_value('txtbiography');}?>
</textarea>
						</p>
						<p class="mandatory" id="error_biography"> <?php echo form_error('txtbiography');	?></p>
					</div>
					<div class="columnist2">
						<div class="columnist2a" id="call_show2">
							<figure> Profile Image<br />
								<input type="hidden" name="image_path" id="image_path" value="<?php if(isset($image_path) && $image_path != '') echo $image_path; ?>" />
								<input type="hidden" name="image_alt" id="image_alt" value="<?php if(isset($image_alt) && $image_alt != '') echo $image_alt; ?>" />
								<input type="hidden" name="image_caption" id="image_caption" value="<?php if(isset($image_caption) && $image_caption != '') echo $image_caption; ?>" />
								<?php   
								/*if(isset($contentid) && (trim($contentid) != "0") ) { 
								$ImageURL = get_image_by_contentid($contentid);
								$ImageDetails = GetImageDetailsByContentId($contentid);
								$Physical_URL = @$ImageDetails['ImagePhysicalPath'];
								$Physical_array = explode('.',$Physical_URL );
								$Physical_extension =@$Physical_array[1];
								
								$Physical_name = GetPhysicalNameFromPhysicalPath($Physical_URL);
								}*/
								
								if(isset($image_path) && $image_path != "")  { 
									$get_image_path = image_url.$image_path;
								}
								else
								{
									$get_image_path = base_url()."images/admin/author.jpg";
								}
								?>
								
								<img src="<?php echo $get_image_path;?>" alt="<?php echo @$image_alt; ?>" width="120px;" height="120px;" id="imgProfileImage" >
								<p>Supported image size 150X150 pixels</p>
								<input type="hidden" name="img_id" id="img_id" value="<?php if(isset($image_path) && $image_path != '') echo $image_path; ?>" />
								
								
							</figure>
							<div class="fileUpload btn btn-primary"> <span id="image_show">Upload</span>
								<input type="file" class="upload" id="uploaduserimage" name="uploaduserimage" />
							</div>
							<?php   
							if(isset($image_path) && $image_path != "") {  ?>
							<button class="btn-primary btn" id="removeimage" type="button">Remove</button>
							<?php }?>
							<button class="btn-primary btn" id="removeimage" type="button" style="display:none">Remove</button>
							<p class="mandatory" id="error_uploaduserimage"></p>
							
							<input type="hidden" name="physical_name" id="physical_name" value="<?php echo @$image_alt; ?>"/>
						</div>
						<!--<div class="qnsans" >
							<div class="columnist2a" id="call_show3">
								<div class="qns">
									<label>Image Name</label>
								</div>
								<div class="ans">
									<input style="width: 219px!important;"  type="textbox" name="physical_name" id="physical_name" rel="<?php echo @$contentid; ?>" physical_extension="<?php echo @$Physical_extension ?>" <?php if(isset($Physical_URL)){ ?> value="<?php echo @$Physical_name; ?>" <?php }?>/>
									<span style="color:#F00" class="WidthPercentage FloatLeft" id="physical_error"></span> </div>
							</div>
						</div>-->
					</div>
					<div class="tab">
						<div class="switch switch-blue">
							<input type="radio" id="status1" name="status" checked="checked"   class="switch-input"  value="1" <?php if(isset($status)){ if($status==1){?>checked='checked'<?php }}else{echo set_radio('status','1');}?>>
							<label for="status1" class="tab-12 switch-label switch-label-off">Active</label>
							<input type="radio" id="status2" name="status"  class="switch-input" <?php if(isset($status_check)&&($status_check > 0)){ ?> disabled <?php }?> value="0"  <?php if(isset($status)){ if($status==0){?>checked='checked'<?php }}else{echo set_radio('status','0');}?>>
							<label for="status2" class="tab-12 switch-label switch-label-on">Inactive</label>
							<span class="switch-selection"></span> </div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script>


function display_image(input)
{
if (input.files && input.files[0])
{
var reader = new FileReader();
reader.onload = function (e)
{
	physical = input.files[0].name;
			physical_array = physical.split('.');
			physical_name = physical_array[0];
			physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
			
			$('#imgProfileImage').attr('src', e.target.result);
			$("#physical_name").val(physical_array[0]);
			//$("#physical_name").attr('physical_extension',physical_array[1]);
			//$("#physical_name").attr('rel','');
			$("#imgProfileImage").show();
			$("#image_show").html('Change');
			$("#removeimage").show();
}
reader.readAsDataURL(input.files[0]);
}
}


$("#uploaduserimage").change(function()
{
	$("#img_id").val("");
	var file = $('input[type="file"]').val();
	var exts = ['jpg','jpeg','png','GIF','JPG','JPEG','PNG','gif'];
	if ( file )// first check if file field has any value
	{
		var get_ext = file.split('.');// split file name at dot
		get_ext = get_ext.reverse();// reverse name to check extension
		if ($.inArray ( get_ext[0].toLowerCase(), exts ) > -1 )// check file type is valid as given in 'exts' array
		{
			$('#error_uploaduserimage').html('');
			display_image(this);
		} 
		else 
		{
			$('#imgProfileImage').attr('src', "<?php echo base_url();?>images/admin/author.jpg");
			$("#image_show").html('Upload');
		}
	}

});
<?php if(isset($image_path)  && ($image_path != '') )
{
	?>
$("#image_show").html('Change');

<?php }?>


</script> 
