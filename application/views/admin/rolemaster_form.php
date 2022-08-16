<?php $script_url = image_url; ?>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">-->
<link href="<?php echo $script_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<!--JQuery-->
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<!--<script src="<?php echo $script_url; ?>js/jquery-1.10.2.js" type="text/javascript"></script>-->
<!--<script src="<?php echo $script_url; ?>js/jquery.remodal.js"></script>-->
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>

<style>
label.error
{
	color:#F00;
	display:block;	
}
#mandatory,#exist_msg
{
color:#F00;	
}


</style>
<?php
//print_r($roleeditdetails);
if(isset($roleeditdetails))
{
	$menu_details = array();
foreach($roleeditdetails as $data)
{
	$op_roleid=$data['role_id'];
	$Menu_id = $data['Menu_Id'];
	$page_design = $data['AddPageDesign'];
	$article_opt = $data['AddArticleOption'];
	$Ad_option = $data['AddAdvScripts'];
	$Config_option = $data['AddConfig'];
	$Publish_option = $data['AddPublish'];
	
	$addreleaselocks = $data['addreleaselocks'];
	
	$menu_details[$Menu_id]['role_id'] = $data['role_id'];
	$menu_details[$Menu_id]['rolename'] = $data['rolename'];
	//$menu_details[$Menu_id]['Department_id'] = $data['Department_id'];
	$menu_details[$Menu_id]['Status'] = $data['Status'];
	$menu_details[$Menu_id]['Createdby'] = $data['Createdby'];
	$menu_details[$Menu_id]['Createdon'] =$data['Createdon'];
	$menu_details[$Menu_id]['Menu_Id'] = $data['Menu_Id'];
	$menu_details[$Menu_id]['IsViewAllowed'] = $data['IsViewAllowed'];
	$menu_details[$Menu_id]['IsAddAllowed'] = $data['IsAddAllowed'];
	$menu_details[$Menu_id]['IsEditAllowed'] = $data['IsEditAllowed'];
	$menu_details[$Menu_id]['IsDeleteAllowed'] = $data['IsDeleteAllowed'];
	$menu_details[$Menu_id]['IsPublishAllowed'] = $data['IsPublishAllowed'];
	$menu_details[$Menu_id]['IsUnPublishAllowed'] = $data['IsUnPublishAllowed'];


}
}
//echo $op_Section_id;

 ?>


<script> 
	$(document).ready(function()
	{
	
		$("#frmroleaccessMaster").validate({
		rules: 
		{			
			txtRoleName: 
			{ 
				required: true,
				//alphanumeric : true,
				maxlength:50,
			},
			ddDepartment:
			{
				required: true,
			}
			
		},
		messages: 
		{
			txtRoleName:
			{
				required: "Please enter role name",
				alphanumeric:"Please enter valid characters",
				maxlength:"Please do not enter more than 50 characters."
			},
			ddDepartment:
			{
				required:"Please select department name",
			},
					},
		

	});
		
		
		$("#btnSave").click(function() 
		{
			if($("#frmroleaccessMaster").valid())
			{
					var rolename=$('#txtRoleName').val();
					//var departmentname=$('#ddDepartment').val();
					var role_id=$('#txthiddenid').val();
					//alert(departmentname);
					$.ajax({
					type: "POST",
					data: {"role_name":rolename,"rollid":role_id},
					url:"<?php echo base_url().folder_name; ?>/rolemaster_manager/check_rolename",
					success: function(result)
					{
						if(result == "Role name already exist for this department")
						{
							$('#exist_msg').html('Role name already exist.');
							return false;
						}
						else
						{
							$('#exist_msg').html('');
							<?php
							  if(isset($op_roleid))
							  {
							  ?>
							  var r = confirm("Are you sure you want to update role details?");
							  <?php } else {?>
							  var r = confirm("Are you sure you want to add role details?");
							  
							  <?php }?>
							  if(r==true)
							  {
								  $("#frmroleaccessMaster").submit();
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
<form name="frmroleaccessMaster" id="frmroleaccessMaster" method="post" action="<?php echo base_url().folder_name."/rolemaster_manager/role_details";?>" enctype="multipart/form-data" >
<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">

 
 <div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
 <h2 class="FloatLeft"><?php echo $title; ?></h2>
</div>

<!--<div class="FloatLeft Error">Error Message</div>-->
 
<p class="FloatRight save-back save_margin">
 <a class="FloatLeft back-top" href="<?php echo base_url().folder_name."/rolemaster_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
 <button class="btn-primary btn" type="button" id="btnSave"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
</p>
 </div>
 
<div class="poll_content role-depart">

<div class="role-dept">
<div class="role-first">

<div class="qnsans">
<div class="qns2"><label class="question">Role Name <span id="mandatory">*</span></label></div>
<div class="ans"><input type="text" maxlength="50" name="txtRoleName" id="txtRoleName" class="tb_style2 box-shad box-shad1" value="<?php if(isset($menu_details[@$Menu_id]['rolename'])){ echo $menu_details[$Menu_id]['rolename'];}else{echo set_value('txtRoleName');}?>"  >
<input type="hidden" name="txthiddenid" id="txthiddenid" value="<?php if(isset($op_roleid)){ echo $op_roleid;}?>">
<p id="exist_msg"></p>
</div>
</div>

<div class="qnsans">
<div class="qns2"><label class="question">Status</label></div>
<div class="ans section_radio">
<div class="switch switch-blue">
      <input type="radio" class="switch-input" name="views" value=1 <?php if(isset($menu_details[@$Menu_id]['Status'])){ if($menu_details[@$Menu_id]['Status']==1){?>checked='checked'<?php } }else{echo set_radio('views',1);}?>  id="week4" checked="checked" >
      <label for="week4" class="switch-label switch-label-off">Active</label>
      <input type="radio" class="switch-input" name="views"  value=0 <?php if(isset($menu_details[@$Menu_id]['Status'])){ if($menu_details[@$Menu_id]['Status']==0){?>checked='checked'<?php } }else{echo set_radio('views',0);}?>  id="month4" >
      <label for="month4" class="switch-label switch-label-on">In-active</label>
      <span class="switch-selection"></span>
    </div>
</div>

</div>

<div class="CompareArticle">
            <div class="CompareTop">
            
            <!--<h1 class="CompareOld">Comaparision Between Old and Current Version</h1>
            <h1 class="CurrentVersion">Current Version Preview</h1>-->
            </div>
               <div >
               <h2>Access Rights</h2><br>
               <div  class="FloatRight">
               <input type="checkbox" name="" id="chkSelectAll" >
               <label >Select All</label>
               </div>
            <table cellpadding="0" cellspacing="0">
            <thead>
            <tr> 
            <th>Department</th>
            <th>View</th>
            <th>Add</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Publish</th>
            <th>UnPublish</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <?php

 
 	foreach($rawdata as $temp_data)
	{
		$Menu_id = $temp_data['Menu_id'];
		$add_opt=$temp_data['IsAddOptionAvailable'];
		$edit_opt=$temp_data['IsEditOptionAvailable'];
		$delete_opt=$temp_data['IsDeleteOptionAvailable'];
		$publish_opt=$temp_data['IsPublishOptionAvailable'];
		$unpublish_opt=$temp_data['IsUnPublishOptionAvailable'];
		
		if($temp_data['MenuName'] != "Top content") {
	 ?>		
        <tr>
        <td><input type="hidden" name="txtMenuId" value="<?php echo $temp_data['Menu_id'];?>"><?php echo $temp_data['MenuName'];?></td>
        <td> 
        <input type="checkbox" id="chkView_<?php echo $temp_data['Menu_id'];?>" class="select_check" onClick="uncheck_checkbox(<?php echo $temp_data['Menu_id'];?>)"  name="rights[<?php echo $temp_data['Menu_id'];?>][view]" <?php if(isset($menu_details[$Menu_id]['IsViewAllowed'])){ if( $menu_details[$Menu_id]['IsViewAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',0);}?>  value=1  />
        </td>
      <td><?php if($add_opt==1) { ?> 
      <input type="checkbox" id="chkAdd_<?php echo $temp_data['Menu_id'];?>"  onclick="validate(<?php echo $temp_data['Menu_id'];?>)" class="select_check" name="rights[<?php echo $temp_data['Menu_id'];?>][add]" value=1 <?php if(isset($menu_details[$Menu_id]['IsAddAllowed'])){ if($menu_details[$Menu_id]['IsAddAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',0);}?>/>
	  <?php } ?></td>
        <td><?php if($edit_opt==1) { ?>
        <input type="checkbox" id="chkEdit_<?php echo $temp_data['Menu_id'];?>" onClick="validate(<?php echo $temp_data['Menu_id'];?>)" class="select_check" name="rights[<?php echo $temp_data['Menu_id'];?>][edit]" value=1 <?php if(isset($menu_details[$Menu_id]['IsEditAllowed'])){ if($menu_details[$Menu_id]['IsEditAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',0);}?> />
		<?php } ?></td>
        <td><?php if($delete_opt==1) { ?>
        <input type="checkbox" id="chkDelete_<?php echo $temp_data['Menu_id'];?>" onClick="validate(<?php echo $temp_data['Menu_id'];?>)" class="select_check" name="rights[<?php echo $temp_data['Menu_id'];?>][delete]" value=1 <?php if(isset($menu_details[$Menu_id]['IsDeleteAllowed'])){ if($menu_details[$Menu_id]['IsDeleteAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',0);}?> />
		<?php }?></td>
        <td> <?php if($publish_opt==1) {?> 
        <input type="checkbox" class="select_check" id="chkPublish_<?php echo $temp_data['Menu_id'];?>" onClick="validate(<?php echo $temp_data['Menu_id'];?>)" name="rights[<?php echo $temp_data['Menu_id'];?>][publish]" value=1 <?php if(isset($menu_details[$Menu_id]['IsPublishAllowed'])){ if($menu_details[$Menu_id]['IsPublishAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',0);}?>/><?php } ?></td>
        <td> <?php if($unpublish_opt==1) { ?>
        <input type="checkbox" class="select_check" id="chkUnPublish_<?php echo $temp_data['Menu_id'];?>" onClick="validate(<?php echo $temp_data['Menu_id'];?>)" name="rights[<?php echo $temp_data['Menu_id'];?>][unpublish]" value=1 <?php if(isset($menu_details[$Menu_id]['IsUnPublishAllowed'])){ if($menu_details[$Menu_id]['IsUnPublishAllowed']== 1){?>checked='checked'<?php }}else{echo set_checkbox('rights',0);}?>  /><?php }?></td> 
        
        </tr>
        
  <?php
	} }
 ?>
            </tr>
            </tbody>
           
            </table>
            </div>
            </div>
        <div class="roleaccess_rights">
        <h2>Front Page Manager Access Rights</h2>
        <ul>
        <li><input type="checkbox" name="chkPageDesign" class="select_check" value="1" <?php if(isset($page_design)){ if( $page_design == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkPageDesign',1);}?>><label>Create/Delete Page Design, Add Configuration & Publish Template</label></li>
		<!--<li><input type="checkbox" name="chkConfiguration" class="select_check" id="chkConfiguration" value=1 <?php if(isset($Config_option)){ if( $Config_option == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkConfiguration',1);}?> ><label>Add configuration</label></li>-->
        <li><input type="checkbox" name="chkAddArticle" class="select_check" value=1 <?php if(isset($article_opt)){ if( $article_opt == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkAddArticle',1);}?> ><label>Adding article</label></li>
        <li><input type="checkbox" name="chkAdvertisement" class="select_check" value=1 <?php if(isset($Ad_option)){ if( $Ad_option == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkAdvertisement',1);}?>><label>Adding Advertisement Script</label></li>
		 <!--<li><input type="checkbox" name="chkPublish" class="select_check" value=1 <?php if(isset($Publish_option)){ if( $Publish_option == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkPublish',1);}?>><label>Publish</label></li>-->
		 
		 <li><input type="checkbox" name="chkreleasetemplate" class="select_check" value=1 <?php if(isset($addreleaselocks)){ if( $addreleaselocks == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkreleasetemplate',1);}?>><label>Release Template Locks</label></li>
		
        </ul>

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
<script>

$(document).ready(function()
{
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
		
		
		//$("#ddDepartment").append($("<option></option>").val(value.CountryId).html(value.CountryName));
		
     });
	

<?php if(isset($op_roleid)) {?>
    if ($('.select_check:checked').length == $('.select_check').length)
	{
		$('#chkSelectAll').prop('checked', true);
    }
<?php } ?>

<?php if(isset($op_roleid)) { ?>


var dptname=$('#txtinactivedpt').val();
var dptid=$('#txtinactivedptid').val();
//alert(dptname);
if($.trim(dptname)!='')

$("#ddDepartment").append($("<option selected=selected></option>").val(dptid).html(dptname));

<?php }?>
	});
function validate(id)
{
	//var add = (document.getElementById('chkAdd_'+id).checked);
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


               

