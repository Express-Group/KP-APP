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

if(isset($cityeditdetails))
{

foreach($cityeditdetails as $data)
{
	$op_cityid=$data['City_id'];
	$op_cityname=$data['CityName'];
	$op_stateid=$data['State_Id'];
	$op_statename=$data['StateName'];
}
}


 ?>
 
 <script> 
	$(document).ready(function()
	{
	
		$("#frmcityMaster").validate({
		rules: 
		{			
			ddStateName: 
			{ 
				required: true,
			},
			txtCityName: 
			{ 
				required: true,
				maxlength:50,
			},
			
			
		},
		messages: 
		{
			ddStateName:
			{
				required: "Please select state",
				
			},
			txtCityName:
			{
				required: "Please enter city name",
				maxlength:"Please do not enter more than 50 characters."
			},
			
					},
		

	});
		
		$("#btnSave").click(function() 
		{
			if($("#frmcityMaster").valid())
			{
				var cityname=$('#txtCityName').val();
				var stateid=$('#ddStateName').val();
				var cityid=$('#txthiddenid').val();
				
					$.ajax({
					type: "POST",
					data: {"city_name":cityname,"state_id":stateid,"city_id":cityid},
					url:"<?php echo base_url(); ?>niecpan/city_manager/check_cityname",
					success: function(result)
					{
						if(result == "Cityname name already exists")
						{
							$('#exist_msg').html('Cityname name already exists');
							return false;
						}
						else
						{
							
							<?php
							if(isset($op_cityid))
							{
							?>
							var r = confirm("Are you sure you want to update city details?");
							<?php } else {?>
							var r = confirm("Are you sure you want to add city details?");
							
							<?php }?>
							if(r==true)
							{
								$("#frmcityMaster").submit();
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
<form name="frmcityMaster" id="frmcityMaster" method="post" action="<?php echo base_url()."niecpan/city_manager/city_details";?>" enctype="multipart/form-data" >
<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<?php if(isset($op_cityid)) 
{?>
<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">City Edit</a></div>
 <h2 class="FloatLeft">City Edit</h2>
 <?php } else {?>
 <div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">City create</a></div>
 <h2 class="FloatLeft">City create</h2>
 <?php }?>
</div>

<!--<div class="FloatLeft Error">Error Message</div>-->
 
<p class="FloatRight save-back save_margin">
 <a class="FloatLeft back-top" href="<?php echo base_url()."niecpan/city_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
 <button class="btn-primary btn" type="button" id="btnSave"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
</p>
 </div>
 
<div class="poll_content role-depart">

<div class="role-dept">
<div class="role-first">
<div class="qnsans">
                          <div class="qns"><label class="question TextAlignRight WidthPercentage">State<span style="color:#F00">*</span></label></div>
                          <div class="ans w2ui-field">
                          	<select id="ddStateName" name="ddStateName" class="controls">
                            <option value="">- Select -</option>
                             <?php
								  foreach($state as $sdata)
								  {
									 echo $state_id = $sdata['State_Id'];
								  ?>
                                    <option value="<?php echo $state_id;?>"<?php if(isset($op_stateid)){ if($op_stateid ==$state_id){?> selected=	"selected"<?php }}else{echo set_select('ddStateName',$state_id);}?> ><?php echo $sdata['StateName'];?></option>
                                    <?php
								  }
								  ?>
                              
                            </select>
                          </div>
                      </div>

<div class="qnsans">
<div class="qns"><label class="question TextAlignRight WidthPercentage">City Name</label></div>
<div class="ans"><input type="text" name="txtCityName" id="txtCityName" class="tb_style2 box-shad box-shad1" value="<?php if(isset($op_cityname)){ echo $op_cityname ;}else{echo set_value('txtCityName');}?>"  >
<input type="hidden" name="txthiddenid" id="txthiddenid" value="<?php if(isset($op_cityid)){ echo $op_cityid ;}?>">
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
