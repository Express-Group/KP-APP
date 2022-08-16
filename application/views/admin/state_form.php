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

if(isset($stateeditdetails))
{

foreach($stateeditdetails as $data)
{
	$op_stateid=$data['State_Id'];
	$op_statename=$data['StateName'];
	$op_countryid=$data['Country_id'];
	$op_countryname=$data['CountryName'];
}
}


 ?>
 
 <script> 
	$(document).ready(function()
	{
	
		$("#frmstateMaster").validate({
		rules: 
		{			
			ddCountryName: 
			{ 
				required: true,
			},
			txtStateName: 
			{ 
				required: true,
				maxlength:50,
			},
			
			
		},
		messages: 
		{
			ddCountryName:
			{
				required: "Please select country",
				
			},
			txtStateName:
			{
				required: "Please enter state name",
				maxlength:"Please do not enter more than 50 characters."
			},
			
					},
		

	});
		
		$("#btnSave").click(function() 
		{
			if($("#frmstateMaster").valid())
			{
				var statename=$('#txtStateName').val();
				var countryid=$('#ddCountryName').val();
				var stateid=$('#txthiddenid').val();
				
					$.ajax({
					type: "POST",
					data: {"state_name":statename,"country_id":countryid,"state_id":stateid},
					url:"<?php echo base_url(); ?>niecpan/state_manager/check_statename",
					success: function(result)
					{
						if(result == "Statename name already exists")
						{
							$('#exist_msg').html('Statename name already exists');
							return false;
						}
						else
						{
							
							<?php
							if(isset($op_stateid))
							{
							?>
							var r = confirm("Are you sure you want to update state details?");
							<?php } else {?>
							var r = confirm("Are you sure you want to add state details?");
							
							<?php }?>
							if(r==true)
							{
								$("#frmstateMaster").submit();
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
<form name="frmstateMaster" id="frmstateMaster" method="post" action="<?php echo base_url()."niecpan/state_manager/state_details";?>" enctype="multipart/form-data" >
<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<?php if(isset($op_departmentid)) 
{?>
<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">State Edit</a></div>
 <h2 class="FloatLeft">State Edit</h2>
 <?php } else {?>
 <div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">State create</a></div>
 <h2 class="FloatLeft">State create</h2>
 <?php }?>
</div>

<!--<div class="FloatLeft Error">Error Message</div>-->
 
<p class="FloatRight save-back save_margin">
 <a class="FloatLeft back-top" href="<?php echo base_url()."niecpan/state_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
 <button class="btn-primary btn" type="button" id="btnSave"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
</p>
 </div>
 
<div class="poll_content role-depart">

<div class="role-dept">
<div class="role-first">
<div class="qnsans">
                          <div class="qns"><label class="question TextAlignRight WidthPercentage">Country<span style="color:#F00">*</span></label></div>
                          <div class="ans w2ui-field">
                          	<select id="ddCountryName" name="ddCountryName" class="controls">
                            	<option value="">- Select -</option>
                               <?php
								  foreach($country as $sdata)
								  {
									 echo $country_id = $sdata['Country_id'];
								  ?>
                                    <option value="<?php echo $country_id;?>"<?php if(isset($op_countryid)){ if($op_countryid ==$country_id){?> selected=	"selected"<?php }}else{echo set_select('ddCountryName',$country_id);}?> ><?php echo $sdata['CountryName'];?></option>
                                    <?php
								  }
								  ?>
                            </select>
                          </div>
                      </div>

<div class="qnsans">
<div class="qns"><label class="question TextAlignRight WidthPercentage">State Name</label></div>
<div class="ans"><input type="text" name="txtStateName" id="txtStateName" class="tb_style2 box-shad box-shad1" value="<?php if(isset($op_statename)){ echo $op_statename ;}else{echo set_value('txtStateName');}?>"  >
<input type="hidden" name="txthiddenid" id="txthiddenid" value="<?php if(isset($op_stateid)){ echo $op_stateid ;}?>">
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
