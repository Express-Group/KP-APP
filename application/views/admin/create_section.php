<link href="<?php echo image_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/admin/w2ui-fields-1.0.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.8.3.js"></script>-->
<!--pop-up-js-->
<!--<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.remodal.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/w2ui-fields-1.0.min.js"></script>
--><link href="<?php echo image_url; ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo image_url; ?>includes/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/section_create.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/additional-methods.min.js"></script>
<style>
.mandatory {
	color:#F00;
}
.mandatory_field {
	color:#F00;
}
#message_change {
	color:#F00;
}
label.error {
	color:#F00;
	display:flex;
}
</style>
<?php
$op_AuthorID = 'NULL';
if(isset($editsectiondata))
{
	
	
foreach($editsectiondata as $dat)
{
	$op_Section_id = $dat['Section_id'];
	$op_Sectionname = $dat['Sectionname'];
	$op_sectioninhtml=$dat['SectionnameInHTML'];
	$op_IsSubSection = $dat['IsSubSection'];
	$op_ParentSectionID = $dat['ParentSectionID'];
	$op_AuthorID = $dat['AuthorID'];
	$op_Highlight = $dat['Highlight'];
	$op_RSSFeedAllowed = $dat['RSSFeedAllowed'];
	$op_ExternalLinkURL = $dat['ExternalLinkURL'];
	$op_DisplayOrder = $dat['DisplayOrder'];
	$op_BackGroundImage = $dat['BGImage_path'];
	$op_Status = $dat['Status'];
	$op_Visibility = $dat['MenuVisibility'];
	$op_MetaTitle = $dat['MetaTitle'];
	$op_MetaDescription = $dat['MetaDescription'];
	$op_MetaKeyword = $dat['MetaKeyword'];
	$op_Noindexed = $dat['Noindexed'];
	$op_Nofollow = $dat['Nofollow'];
	$op_Canonicalurl = $dat['Canonicalurl'];
	$op_IsSpecialSection=$dat['IsSeperateWebsite'];
	$op_Isarticleremoved=$dat['Section_landing'];
	
	$op_url_sectionnane=$dat['URLSectionName'];
	
	$section_selection_allowed=$dat['section_allowed_for_hosting'];

}
}

 ?>
<script> 
	$(document).ready(function()
	{
		
		<?php if(isset($op_Section_id)&&($op_Status==0)){ ?>
		$('#menu_visibility').hide();
		<?php }else {?>
		$('#menu_visibility').show();
		<?php }?>
		
		<?php if(isset($disable)&&($disable > 0)&& ($op_Isarticleremoved!=1) ) { ?>
		$('#remove_atcl').hide();
		
		<?php } else {?>
		$('#remove_atcl').show();
		<?php }?>
		$('#deleteimages').click(function()
							{
								//alert('sdd');
								$('#imgRemoved').val('Y');
							});

		CKEDITOR.replace( 'txtSectionName',
  		{
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor', 'BackgroundColor', 'BGColor' ] } ]
	    });	
		
		<?php if((isset($op_BackGroundImage)) && $op_BackGroundImage!='')
		{?>
		
		$("#preview_image").show();	
		$("#deleteimages").show();
		$("#imgBackgndimage").show();
		$("#image_show").html('Change');
			
		<?php } else { ?>
		
		$("#preview_image").hide();	
		$("#deleteimages").hide();
		$("#imgBackgndimage").hide();
		$("#image_show").html('Browse');
		<?php } ?>
		
		$("#frmSectionMaster").validate({
			ignore: ':hidden:not("#txtSectionName")',
		rules: 
		{			
			
			txtSectionName:
			{
				//required: true,
			   required: function() { CKEDITOR.instances.txtSectionName.updateElement();}
			},
			ddDisplayOrder:
			{
				required: true,
			},
			ddSectionName:
			{
				required: true,
			},
			ddColumnist:
			{
				required: true,	
			},
			txtExternalLink:
			{
				required: true,	
				url: true,
			},
			txtCanonicalUrl:
			{
				url: true,
			},
			txturlsectionname: { required: true, maxlength: 255 },
			txtMetaTitle:{ maxlength: 255 },
			txtMetaDesc: { maxlength: 500 },
			txtMetaKeyword: { maxlength: 600 },
			fileBackgroundImage:
			{
				// required: true,	 
     			accept: "image/*"
    		},
			
		},
		messages: 
		{
			txtSectionName:
			{
				required: "Please enter section name",
				//alphanumeric:"Please enter valid characters",
				maxlength:"You cannot enter more than 50 characters"
			},
			txturlsectionname:
			{
				required: "Please enter URL Section Name",
			},
			ddDisplayOrder:"Please select the display order", 
			ddSectionName:"Please select parent section",
			ddColumnist:"Please select columnist",
			txtExternalLink:
			{
				required:"Please enter external link URL",
				//url:"Please enter a Valid URL",
			},
			txtCanonicalUrl:
			{
					//url: "Please enter a Valid URL",
			},
			fileBackgroundImage:
			{
     			accept: "Please select only png, jpg or gif file types",
    		},
		},
		//errorElement: "p",
		errorPlacement: function (error, element) 
		{
        	if(element.attr("name") == 'fileBackgroundImage')
         	{   
				error.insertAfter($("#image_error"));
		 	}
			if(element.attr("name") == 'optRevert')
         	{   
				error.insertAfter($("#error_checkbox"));
		 	}
			if(element.attr("name") == 'txtSectionName')
         	{   
				error.insertAfter($("#error_txtSectionName"));
		 	}
		 	else
		 	{
			 	//alert(element.attr("name"));
			 	error.insertAfter($("#"+element.attr("name")));
		 	}
		},

	});
		
		$("#btnSaveTop").click(function() 
		{
			var sectionname= $('#txtSectionName').val();
			var hdnplainsectionname= $('#txtPlainSection').val();
			var section=$('#txthdnsection').val();
			var currentsection=$('#txtSectionName').val();
			var url_sectionname = $('#txturlsectionname').val();
			
			if($("#frmSectionMaster").valid())
			{
				var get_sec_name = $("#txtSectionName").val();
				var editor_val = $.trim(CKEDITOR.instances.txtSectionName.document.getBody().getChild(0).getText());
				
				var section_id   = $("#txtSectionId").val();
				//var checkbox_val = $("#chkSubSection").val();
				var check_parnt_sectn = $("#ddSectionName").val();
							
				console.log(check_parnt_sectn);
				if((typeof check_parnt_sectn !== "undefined")&&(check_parnt_sectn !="") ) 
				{
				  var url_fun = "check_sub_sectionname";
				  //alert('123');
				}
				else
				{
				 var url_fun = "check_sectionname";
				 //alert('456');
				} 
				
				$.ajax({
				type: "POST",
				data: {"get_sec_name":editor_val,"check_parnt_sectn":check_parnt_sectn,"section_id":section_id},
				url: "<?php echo base_url().folder_name; ?>/section_manager/"+url_fun+"",
				success: function(result)
				{
					console.log(result);
					if(result == "Section name already exist")
					{
						$("#error_txtSectionName").html(result);
					}
					else if(result == "Sub section name already exist for this section")
					{
						$("#error_txtSectionName").html(result);
					}
					else
					{
						$("#error_txtSectionName").html('');
						$.ajax({
						type: "POST",
						data: {"url_section":url_sectionname,"check_parnt_sectn":check_parnt_sectn,"section_id":section_id},
						url: "<?php echo base_url().folder_name; ?>/section_manager/check_url_sectionname",
						success: function(result)
						{
						if(result == "URL Section Name already exist")
					    {
							(($('#error_url').length) == 0)? $('#txturlsectionname').after('<div id="error_url" style="color:red;">'+result+'</div>'): $('#error_url').htm(result);
						}else{
							$('#error_url').remove();
						<?php
						if(isset($editsectiondata[0]['Section_id']))
						{
						?>
						var displayorder_replacemsg='';
						var selected_value= $('input[name=view8]:checked').val();
						//alert(selected_value);
						if(selected_value == "R")
						{
							selected_value= "Move After";
						}
						else
						{
							selected_value= "Move Before";
						}
						if($('input[name=view8]').attr("disabled") == "disabled")
						{
							//alert('1');
							displayorder_replacemsg ="";
						}
						else
						{
							//alert('qq');
							displayorder_replacemsg = "and" +' '+ selected_value+' '+ "the selected section";
						}
						
						var r = confirm("Are you sure you want to update section details "+ displayorder_replacemsg+" ?");
						if(r==true)
						{
							$("#frmSectionMaster").submit();
						}
						else
						{
							return false;
						}
					
						<?php } else {?>
						var displayorder_replacemsg='';
						var selected_value= $('input[name=view9]:checked').val();
						//alert(selected_value);
						if(selected_value == "R")
						{
							selected_value= "Replace";
						}
						else
						{
							selected_value= "Move Forward";
						}
						if($('input[name=view9]').attr("disabled") == "disabled")
						{
							//alert('1');
							displayorder_replacemsg ="";
						}
						else
						{
							//alert('qq');
							displayorder_replacemsg = "and" +' '+ selected_value+' '+ "the selected section";
						}
						
					
						var x = confirm("Are you sure you want to add section details "+ displayorder_replacemsg+" ?");
						if(x==true)
						{
							
							$("#frmSectionMaster").submit();
						}
						else
						{
							return false;
						}
				
						<?php }?>
						}
						}
						});
					}
				}
				});
			}
			
			
						  
		});
		
		
		$("#month3, #week3").click(function()
		{
			if($("#month3").is(':checked')==true)
			{
				$("#ckb_hosting").attr("checked", false);
				$("#section_hosting_div").hide();
			}
			else
			{
				$("#ckb_hosting").attr("checked", true);
				$("#section_hosting_div").show();
			}
		});
		
		<?php if(isset($op_ExternalLinkURL) && $op_ExternalLinkURL!=""){?>
			$("#section_hosting_div").hide();
		<?php } ?>
		
 });


</script>
</head><body onLoad="uncheck()">
<div class="Container">
  <form name="frmSectionMaster" id="frmSectionMaster" method="post" action="<?php echo base_url().folder_name."/section_manager/class_section_add";?>" enctype="multipart/form-data" >
    <div class="BodyWhiteBG">
      <div class="BodyHeadBg Overflow clear">
      
		<div class="FloatLeft BreadCrumbsWrapper">
          <div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
          <h2 class="FloatLeft"><?php echo $title; ?></h2>
        </div>
		
        <p class="FloatRight save-back save_margin"> <a class="FloatLeft back-top" href="<?php echo base_url().folder_name."/section_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
          <button class="btn-primary btn" id="btnSaveTop" type="button"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
        </p>
      </div>
      
      <!--<div class="section_content">-->
      <div class=" section_content">
        <div class="section_form">
          <div class="section1">
            <div class="qnsans" id="spl_menu" >
              <div class="qns">
                <label></label>
              </div>
              <div class="ans padding-top-6">
                <input type="checkbox"  <?php if(isset($disable)&&($disable > 0)) { ?> disabled <?php } ?>    value="1" id="chkSeperateMenu" name="chkSeperateMenu" <?php if(isset($op_IsSpecialSection)){ if($op_IsSpecialSection == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkSeperateMenu','1');}?>>
                <label class="TopAuto" for="optHighLight">Allow this section to have seperate menu structure</label>
                <?php if(isset($disable)&&($disable > 0)) { ?>
                <input type="hidden" name="chkSeperateMenu" value=" <?php if(isset($op_IsSpecialSection)){ if($op_IsSpecialSection == 1 && $op_IsSubSection == 0){ echo 1;?><?php }}else{ echo 0;?><?php }?>" id="txtDisableValue">
                <?php } ?>
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question"> Name<span class="mandatory_field">*</span></label>
              </div>
              <div class="ans SectionCk">
                <textarea  name="txthdnsection" style="display:none;" id="txthdnsection" ><?php if(isset($op_Section_id) && $op_Section_id!="") echo $op_sectioninhtml; ?>
</textarea>
                <input type="hidden" id="txtPlainSection" value="<?php if(isset($op_Section_id) && $op_Section_id!="") echo $op_Sectionname; ?>">
                <input type="hidden" name="txtSectionId" id="txtSectionId" value="<?php if(isset($op_Section_id)){ echo $op_Section_id;}?>" size="2">
                <textarea  name="txtSectionName" id="txtSectionName" ><?php if(isset($op_Section_id) && $op_Section_id!="") echo $op_sectioninhtml; ?>
</textarea>
                <?php /*?>  <input type="text" <?php if(isset($disable)&&($disable > 0) ) { ?> readonly="readonly" <?php }?> class="tb_style box-shad" name="txtSectionName" id="txtSectionName"  maxlength="50" value="<?php if(isset($op_Sectionname)){ echo $op_Sectionname;}else{echo set_value('txtSectionName');}?>" > <?php */?>
                <p id="error_txtSectionName" class="mandatory"></p>
                <p id="message_change"></p>
                <p class="next-error"><?php echo form_error('txtSectionName');?></p>
              </div>
            </div>
            <br>
            <br>
			
            <div class="qnsans" <?php if(isset($op_ParentSectionID)&& ($childrows > 0) ) { ?> style="display:none;" <?php }?>id="remove_atcl">
              <div class="qns">
                <label></label>
              </div>
              <div class="ans">
                <input type="checkbox" value="1" <?php if(isset($disable)&&($disable > 0) ) { ?> disabled <?php } ?> <?php if(isset($op_Isarticleremoved)&&($op_Isarticleremoved == 1)){?>checked='checked'<?php }else{echo set_checkbox('txtArticle','1');}?> id="txtArticle" name="txtArticle" >
                <label>Do you want to hide article page template creation in front page manager</label>
                <?php if(isset($disable)&&($disable > 0)) { ?>
                <input type="hidden" name="txtArticle" value=" <?php if(isset($op_Isarticleremoved)&&($op_Isarticleremoved == 1)){ echo 1;?><?php }else{ echo 0;?><?php }?>" id="txtArticle">  
                <?php } ?>
                 <input type="hidden" name="pagecount" value="" id="pagecount">
              </div>
			  
			  
            </div>
			
			<div class="qnsans" id="section_hosting_div">
              <div class="qns">
                <label></label>
              </div>
              <div class="ans">
                <input type="checkbox" value="1" name="ckb_hosting" id="ckb_hosting" <?php if(isset($section_selection_allowed)&&($section_selection_allowed == 1) or (!isset($section_selection_allowed))){?>checked='checked'<?php } else{echo set_checkbox('ckb_hosting','1');}?>>
                <label>Allow this section to be displayed for content hosting</label>
               
              </div>
			  
			  
            </div>
			
			<div class="qnsans">
              <div class="qns TextAlignRight">
                <label class="margin-top-0">URL Section Name</label>
              </div>
              <div class="ans">
                <input type="text" id="txturlsectionname" maxlength="255" pattern="^[ a-zA-Z0-9-]+$" name="txturlsectionname" value="<?php if(isset($op_url_sectionnane)){ echo $op_url_sectionnane;}else{echo set_value('txturlsectionname');}?>">
              </div>
            </div>
			
            <?php if((isset($op_ParentSectionID) && ($op_ParentSectionID != "")) or (!isset($op_ParentSectionID))) {  ?>
            <div class="qnsans" id="hide_spldd">
              <div class="qns">
                <label class="include_label">Sub Section</label>
              </div>
              <div class="ans">
                <div class="right01 padding-top-10" id="check_in">
                  <?php if(isset($op_ParentSectionID) && $op_IsSpecialSection == 1) { ?>
                  <input type="checkbox" <?php if(isset($disable)&&($disable > 0) ) { ?> disabled <?php } ?> id="chkSubSection" name="chkSubSection"  value="1"   <?php if(isset($op_IsSubSection)){ if($op_IsSubSection == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkSubSection','1');}?> />
                  <?php if(isset($disable)&&($disable > 0)) { ?>
                  <input type="hidden" name="chkSubSection" value=" <?php if(isset($op_IsSubSection)){ if($op_IsSubSection == 1){ echo "1";?><?php }}else{ echo "0";?><?php }?>" >
                  <?php } ?>
                  <?php } else { ?>
                  <input type="checkbox" <?php if(isset($disable)&&($disable > 0) ) { ?> disabled <?php } ?> id="chkSubSection" name="chkSubSection" value="1"   <?php if(isset($op_IsSubSection)){ if($op_IsSubSection == 1 && $op_IsSpecialSection == 0 ){?>checked='checked'<?php }}else{echo set_checkbox('chkSubSection','1');}?> />
                  <?php if(isset($disable)&&($disable > 0)) { ?>
                  <input type="hidden" name="chkSubSection" value=" <?php if(isset($op_IsSubSection)){ if($op_IsSubSection == 1 && $op_IsSpecialSection == 0){ echo "1";?><?php }}else{ echo "0";?><?php }?>" >
                  <?php } ?>
                  <?php } ?>
                </div>
                <div id="parent_chkbox" style="display:none;">
                  <div class="right02">
                    <label class="question right_label section_right1" >Parent Section</label>
                  </div>
                  <div class="right03">
                    <div class="w2ui-field">
                      <?php if(isset($disable)&&($disable > 0) ) { ?>
                      <input type="text" name="ddSectionName" readonly value="<?php echo get_sectionname_by_id($op_ParentSectionID); ?>" >
                      <?php } ?>
                       <input type="hidden" name="txtgrandParentName" id="txtgrandParentName" value="">
                      <input type="hidden" name="txtParentName" id="txtParentName" value="">
                      <input type="hidden" name="txtParentID" id="txtParentID" value="<?php if(isset($op_ParentSectionID))  echo $op_ParentSectionID ?>">
                      <!--<select <?php if(isset($disable)&&($disable > 0) ) { ?>  style="display:none;" <?php } ?> name="ddSectionName" id="ddSectionName" onChange="get_display_order(); display_order(); get_last_sectionname();" class="controls">
												<option value="">- Select -</option>
													<?php
													foreach($sectiondata as $sdata)
													{
													   echo $sec_id = $sdata['Section_id'];
													?>
													<option value="<?php echo $sec_id;?>" <?php if(isset($op_ParentSectionID)){ if($op_ParentSectionID ==$sec_id){?> selected=	"selected"<?php }}else{echo set_select('ddSectionName',$sec_id);}?>><?php echo $sdata['Sectionname'];?></option>
													<?php
													}
													?>
												</select>-->
                      <select <?php if(isset($disable)&&($disable > 0) ) { ?>  style="display:none;" <?php } ?> name="ddSectionName" id="ddSectionName" onChange="get_display_order(); display_order(); get_last_sectionname();" class="controls">
                        <option value="">- Select -</option>
                        <?php if(isset($sectiondata)) { 
						$total_count = (count($sectiondata)-1); 
                                                            foreach($sectiondata as $val => $sdata) {  ?>
                        <?php if(!(empty($sdata['sub_section']))) { ?>
                        <option class="blog_option" style="color:#933;font-size:18px;" label="<?php echo $sdata['Sectionname']; ?>" value="<?php echo $sdata['Section_id']; ?>" <?php if(isset($op_ParentSectionID)){ if($op_ParentSectionID ==$sdata['Section_id']){?> selected="selected"<?php }}else{echo set_select('ddSectionName',$sec_id);}?>> <?php echo $sdata['Sectionname']; ?> </option>
                        <?php foreach($sdata['sub_section'] as $ssdata) { ?>
                        <option  value="<?php echo $ssdata['Section_id']; ?>" <?php if(isset($op_ParentSectionID)){ if($op_ParentSectionID ==$ssdata['Section_id']){?> selected=	"selected"<?php }}else{echo set_select('ddSectionName',$sec_id);}?>>&nbsp;&nbsp;<?php echo $ssdata['Sectionname']; ?></option>
                        <?php } ?>
                        <?php   } else {
							         if($sdata['Sectionname'] != 'Home') { ?>
                        <option  class="blog_option" style="color:#933;font-size:18px;" value="<?php echo $sdata['Section_id']; ?>" <?php if(isset($op_ParentSectionID)){ if($op_ParentSectionID ==$sdata['Section_id']){?> selected=	"selected"<?php }}else{echo set_select('ddSectionName',$sec_id);}?>>&nbsp;&nbsp;<?php echo $sdata['Sectionname']; ?></option>
                        <?php } }
						
						
						if(isset($op_ParentSectionID) && $op_ParentSectionID != $sdata['Section_id'] && $val == $total_count ) { ?>
                        <option  class="blog_option" value="<?php echo $op_ParentSectionID; ?>"  selected="selected" <?php echo set_select('ddSectionName',$sec_id); ?>>&nbsp;&nbsp;<?php echo $parentSectionName; ?></option>
                        <?php }
						
						 } }?>
                      </select>
                      <!--<p id="error_ddSectionName" class="mandatory"></p>-->
                      <p>
                        <?php  echo form_error('ddSectionName'); ?>
                      </p>
                      <div class="w2ui-field-helper"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php } else { ?>
            <div class="qnsans" id="hide_spldd">
              <div class="qns">
                <label class="include_label">Sub Section</label>
              </div>
              <div class="ans">
                <div class="right01 padding-top-10" id="check_in">
                  <input type="checkbox" <?php if(isset($disable)&&($disable > 0) ) { ?> disabled <?php } ?> id="chkSubSection" name="chkSubSection"  value="1"   <?php if(isset($op_IsSubSection)){ if($op_IsSubSection == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkSubSection','1');}?> />
                </div>
                <div id="parent_chkbox" style="display:none;">
                  <div class="right02">
                    <label class="question right_label section_right1" >Parent Section</label>
                  </div>
                  <div class="right03">
                    <div class="w2ui-field">
                      <input type="hidden" name="txtParentID" id="txtParentID" value="<?php if(isset($op_ParentSectionID))  echo $op_ParentSectionID ?>">
                      <select <?php if(isset($disable)&&($disable > 0) ) { ?>  style="display:none;" <?php } ?> name="ddSectionName" id="ddSectionName" onChange="get_display_order(); display_order(); get_last_sectionname();" class="controls">
                        <option value="">- Select -</option>
                        <?php
                                                foreach($sectiondata as $sdata)
                                                {
                                                   echo $sec_id = $sdata['Section_id'];
                                                ?>
                        <option value="<?php echo $sec_id;?>" <?php if(isset($op_ParentSectionID)){ if($op_ParentSectionID ==$sec_id){?> selected=	"selected"<?php }}else{echo set_select('ddSectionName',$sec_id);}?>><?php echo $sdata['Sectionname'];?></option>
                        <?php
                                                }
                                                ?>
                      </select>
                      <!--<p id="error_ddSectionName" class="mandatory"></p>-->
                      <p>
                        <?php  echo form_error('ddSectionName'); ?>
                      </p>
                      <div class="w2ui-field-helper"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php }?>
            <div class="qnsans" id="TxtDisplay" <?php if(isset($op_Section_id)&& $op_Section_id!=""){ ?> style="display:none;" <?php }?>>
              <div class="qns TextAlignRight">
                <label class="margin-top-0">Display Order :</label>
              </div>
              <div class="ans">
                <label class="margin-top-0" for="optRss" id="txtLastSection">This section will be added after last section </label>
                <input type="hidden" id="txtLastSection" name="txtLastSection">
              </div>
            </div>
            <div class="qnsans" id="changeOrder">
              <div class="qns TextAlignRight padding-right-0"> </div>
              <div class="ans" style="float:right;">
                <input type="checkbox" name="chkChangeOrder" id="chkChangeOrder" value="Y">
                <label class="include_label margin-top-0" for="chkChangeOrder">Change Order</label>
              </div>
            </div>
            <?php if(isset($op_Section_id)&& $op_Section_id!="") {?>
            <div class="qnsans" id="display_order" >
              <div class="qns">
                <label class="question">Display Order<span class="mandatory_field">*</span></label>
              </div>
              <div id="Section_order">
                <div class="ans">
                  <div class="FloatLeft">
                    <div class="w2ui-field">
                      <select id="ddDisplayOrder" name="ddDisplayOrder" class="controls margin-bottom-0" onChange="validate();">
                        <option value="">- Select -</option>
                        <?php
										//print_r($orders_display);
                                                foreach($slted_order as $sdata)
                                                {
                                                   echo $order = $sdata['DisplayOrder'];
                                                ?>
                        <option value="<?php echo $order;?>" <?php if(isset($op_DisplayOrder)){ if($op_DisplayOrder ==$order){?> selected=	"selected"<?php }}else{echo set_select('ddSectionName',$sec_id);}?>><?php echo $sdata['Sectionname'];?></option>
                        <?php
                                                }
                                                ?>
                      </select>
                      <input type="hidden" name="txtDisplayOrder" id="txtDisplayOrder" value="<?php if(isset($op_DisplayOrder)) echo $op_DisplayOrder  ?>">
                      <p id="mapped_section" class="DOMapping"></p>
                      <p id="error_ddDisplayOrder" class="mandatory"></p>
                    </div>
                  </div>
                  <p style="margin-left:172px;"><?php echo form_error('ddDisplayOrder');?></p>
                </div>
              </div>
            </div>
            <?php }else { ?>
            <div class="qnsans" id="display_order" >
              <div class="qns">
                <label class="question">Display Order<span class="mandatory_field">*</span></label>
              </div>
              <div id="Section_order">
                <div class="ans">
                  <div class="FloatLeft">
                    <div class="w2ui-field">
                      <select id="ddDisplayOrder" name="ddDisplayOrder" class="controls margin-bottom-0" onChange="validate();">
                        <option value="">- Select -</option>
                        <?php 
										 
										 for($i=1; $i<=100; $i++)
										 {
											 ?>
                        <option value="<?php echo $i;?>" <?php if(isset($op_DisplayOrder)){ if($op_DisplayOrder ==$i){?> selected="selected"<?php }}else{echo set_select('ddDisplayOrder',$i);}?>><?php echo $i;?></option>
                        <?php } ?>
                      </select>
                      <input type="hidden" name="txtDisplayOrder" id="txtDisplayOrder" value="<?php if(isset($op_DisplayOrder)) echo $op_DisplayOrder  ?>">
                      <input type="hidden" name="ddDisplayOrderHidden" id="ddDisplayOrderHidden" >
                      <p id="mapped_section" class="DOMapping"></p>
                      <p id="error_ddDisplayOrder" class="mandatory"></p>
                    </div>
                  </div>
                  <p style="margin-left:172px;"><?php echo form_error('ddDisplayOrder');?></p>
                </div>
              </div>
            </div>
            <?php }?>
            <div class="qnsans" id="rever_replaceedit" style="display:none";>
              <div class="qns">
                <label class="question">Display Option</label>
              </div>
              <div class="ans section_radio">
                <div class="switch switch-yellow SwitchDisplay">
                  <input type="radio"  class="switch-input" name="view8" value="C" <?php /*?><?php if(isset($op_displayoption)){ if($op_displayoption=='R'){?>checked='checked'<?php }}else{echo set_radio('view8','R');}?><?php */?> id="week8" checked="checked">
                  <label for="week8" class="switch-label switch-label-off">Move Before</label>
                  <input type="radio"  class="switch-input" name="view8" value="R" <?php /*?><?php if(isset($op_displayoption)){ if($op_displayoption=='C'){?>checked='checked'<?php }}else{echo set_radio('view8','C');}?><?php */?>  id="month8" >
                  <label for="month8" class="switch-label switch-label-on">Move After</label>
                  <span class="switch-selection"></span> </div>
              </div>
            </div>
            <div class="qnsans" id="rever_replaceAdd" <?php if(isset($op_Section_id)&& $op_Section_id!="") { ?> style="display:none"; <?php }?>>
              <div class="qns">
                <label class="question">Display Option</label>
              </div>
              <div class="ans section_radio">
                <div class="switch switch-yellow SwitchDisplay">
                  <input type="radio"  class="switch-input" name="view9" value="R"  id="week9" checked="checked">
                  <label for="week9" class="switch-label switch-label-off">Replace</label>
                  <input type="radio"  class="switch-input" name="view9" value="F"   id="month9" >
                  <label for="month9" class="switch-label switch-label-on">Move Forward</label>
                  <span class="switch-selection"></span> </div>
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question">Status</label>
              </div>
              <div class="ans section_radio">
                <div class="switch switch-yellow">
                  <input type="radio" class="switch-input" name="view4" value="1" <?php if(isset($op_Status)){ if($op_Status==1){?>checked='checked'<?php }}else{echo set_radio('view4','1');}?> id="week4" checked="checked" >
                  <label for="week4" class="switch-label switch-label-off">Active</label>
                  <input type="radio" class="switch-input" name="view4"  value="0" <?php if(isset($op_Status)){ if($op_Status==0){?>checked='checked'<?php }}else{echo set_radio('view4','0');}?>id="day4" >
                  <label for="day4" class="switch-label switch-label-on">Inactive</label>
                  <span class="switch-selection"></span> </div>
              </div>
            </div>
            <br>
            <p><?php echo form_error('view4');?></p>
            <div class="qnsans" id="menu_visibility">
              <div class="qns">
                <label class="question">Visibility</label>
              </div>
              <div class="ans section_radio">
                <div class="switch switch-yellow">
                  <input type="radio" class="switch-input" name="view5" value="1" <?php if(isset($op_Visibility)){ if($op_Visibility==1){?>checked='checked'<?php }}else{echo set_radio('view5','1');}?>  id="week5" checked="checked" >
                  <label for="week5" class="switch-label switch-label-off">Show</label>
                  <input type="radio" class="switch-input" name="view5"  value="0" <?php if(isset($op_Visibility)){ if($op_Visibility==0){?>checked='checked'<?php }}else{echo set_radio('view5','0');}?> id="day5" >
                  <label for="day5" class="switch-label switch-label-on">Hide</label>
                  <span class="switch-selection"></span> </div>
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question">Linked to Columnist</label>
              </div>
              <div class="ans">
                <div class="right01 padding-top-10" id="column_check">
                  <input type="checkbox" id="chkColumnist" name="chkColumnist" value="1" <?php if(count($authordata)>0){ if($op_AuthorID != 'NULL' && $op_AuthorID != ''){?>checked='checked'<?php }}else{echo set_checkbox('chkColumnist','1');}?>/>
                  <label class="TopAuto" for="chkColumnist"></label>
                </div>
                <div id="parent_columnist" style="display:none;">
                  <div class="right02">
                    <label class="question right_label section_right1" for="chkColumnist">columnist</label>
                  </div>
                  <div class="right03">
                    <div class="w2ui-field">
                    <input type="hidden" id="columnist_img" name="columnist_img" value="">
                    <input type="hidden" id="chkColumnist_biograph" name="chkColumnist_biograph" value="">
                      <select name="ddColumnist" id="ddColumnist" onChange="get_author_details();" class="controls" >
                        <option value="">- Select -</option>
                        <?php
													foreach($authordata as $adata)
													{
														$aut_id = $adata['Author_id'];
													?>
                        <option value="<?php echo $aut_id;?>" <?php if(isset($op_AuthorID)){ if($op_AuthorID ==$aut_id){?> selected="selected"<?php }}else{echo set_select('ddColumnist',$aut_id);}?>><?php echo $adata['AuthorName'];?></option>
                        <?php
													}
													?>
                      </select>
                      <div class="w2ui-field-helper"> </div>
                      <p>
                        <?php  echo form_error('ddColumnist'); ?>
                      </p>
                      <p  class="error"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="qnsans"> 
              <!--<div class="qns"><label  class="include_label" for="optHighLight">Highlight</label></div>-->
              <div class="qns"> 
                <!--<div class="right01 padding-top-10"><input type="checkbox" name="optHighLight" id="optHighLight" value="Y" <?php if(isset($op_Highlight)&&($op_Highlight==1)){?>checked='checked'<?php }else{echo set_checkbox('optHighLight','1');}?> /><label class="TopAuto" for="optHighLight"></label></div>-->
                <label  class="include_label margin-top-0" for="optRss">RSS</label>
              </div>
              <div class="ans">
                <input type="checkbox" name="optRss" id="optRss" value="1" <?php if(isset($op_RSSFeedAllowed)&&($op_RSSFeedAllowed==1)){?>checked='checked'<?php }else{echo set_checkbox('optRss','1');}?> />
                <label class="TopAuto" for="optRss"></label>
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question">Link Type</label>
              </div>
              <div class="ans section_radio">
                <div class="switch switch-yellow">
                  <input type="radio" class="switch-input" name="view3" value="I" <?php if(isset($op_ExternalLinkURL)){ if($op_ExternalLinkURL==""){?>checked='checked'<?php } }else{echo set_radio('view3','I');}?>  id="week3" checked="checked">
                  <label for="week3" class="switch-label switch-label-off">Internal</label>
                  <input type="radio" class="switch-input" name="view3" value="E" <?php if(isset($childrows)&&($childrows > 0) ) { ?> disabled <?php } ?>  <?php if(isset($op_ExternalLinkURL)){ if($op_ExternalLinkURL!=""){?>checked='checked'<?php }}else{echo set_radio('view3','E');}?> id="month3" >
                  <label for="month3" class="switch-label switch-label-on">External</label>
                  <span class="switch-selection"></span> </div>
              </div>
            </div>
            <div class="qnsans" id="external_link" style="display:none;">
              <div class="qns">
                <label class="question">External Link URL</label>
              </div>
              <div class="ans">
                <input type="text" class="tb_style box-shad" name="txtExternalLink" id="txtExternalLink" value="<?php if(isset($op_ExternalLinkURL)){ echo $op_ExternalLinkURL;}else{echo set_value('txtExternalLink');}?>" >
                <p id="error_txtExternalLink" class="mandatory"></p>
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question">Section Page Background Image</label>
              </div>
              <div class="ans Overflow">
                <div  class="fileUpload btn btn-primary FloatLeft"><span id="image_show">Browse</span>
                  <input class="upload" type="file" name="fileBackgroundImage" id="fileBackgroundImage" value="Click">
                  <p id="message_image"></p>
                </div>
                <input type="hidden" name="imgRemoved"  id="imgRemoved" value="">
                <div class="margin-left-5 FloatLeft"> <a href="#removemegssage"  id="deleteimages" style="display:none" class="btn btn-primary fileUpload" title="Remove">Remove</a>
                  <div class="remodal" data-remodal-id="removemegssage" style="position:relative;">Selected Image removed</div>
                </div>
                <div class="WidthPercentage margin-top-5 FloatLeft"> <img style="width:187px; height:100px; display:none"  id="imgBackgndimage" name="imgBackgndimage"  <?php if(isset($op_BackGroundImage)  && $op_BackGroundImage != '') { ?> src="<?php echo base_url().$op_BackGroundImage; ?>" <?php } ?> > <span id="image_error"></span> </div>
                <p id="error_imgBackgndimage" class="mandatory FloatLeft WidthPercentage"></p>
              </div>
              <br>
              <p style="margin-left:172px;"><?php echo form_error('fileBackgroundImage');?></p>
            </div>
          </div>
          <div class="section2">
            <div class="section_seo_head">SEO Tags</div>
            <div class="qnsans">
              <div class="qns">
                <label class="question">Meta Tile</label>
              </div>
              <div class="ans">
                <input type="text" class="tb_style box-shad" name="txtMetaTitle" maxlength="255" id="txtMetaTitle" value="<?php if(isset($op_MetaTitle)){ echo $op_MetaTitle;}else{echo set_value('txtMetaTitle');}?>">
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question">Meta Description</label>
              </div>
              <div class="ans">
                <textarea class="box-shad" name="txtMetaDesc" id="txtMetaDesc" ><?php if(isset($op_MetaDescription)){ echo $op_MetaDescription;}else{echo set_value('txtMetaDesc');}?>
</textarea >
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question">Meta Keyword</label>
              </div>
              <div class="ans">
                <textarea class="box-shad" id="txtMetaKeyword" name="txtMetaKeyword"  ><?php if(isset($op_MetaKeyword)){ echo $op_MetaKeyword;}else{echo set_value('txtMetaKeyword');}?>
</textarea>
              </div>
            </div>
            <div class="section_seo">
              <h3>Crawler</h3>
              <div class="qnsans">
                <div class="include">
                  <input type="checkbox" name="chkCrawlerNoIndex" id="chkCrawlerNoIndex" value="1" <?php if(isset($op_Noindexed)){ if($op_Noindexed == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkCrawlerNoIndex','1');}?>/>
                  <label class="include_label" for="chkCrawlerNoIndex">No Index</label>
                </div>
                <div class="include">
                  <input type="checkbox" name="chkCrawlerNoFollow" id="chkCrawlerNoFollow" value="1" <?php if(isset($op_Nofollow)){ if($op_Nofollow == 1){?>checked='checked'<?php }}else{echo set_checkbox('chkCrawlerNoFollow','1');}?> data-color="green" />
                  <label class="include_label" for="chkCrawlerNoFollow">No Follow</label>
                </div>
              </div>
              <div class="qnsans section_canonic">
                <label>Canonical URL</label>
                <input type="text" name="txtCanonicalUrl" id="txtCanonicalUrl" class="tb_style box-shad" value="<?php if(isset($op_Canonicalurl)){ echo $op_Canonicalurl;}else{echo set_value('txtCanonicalUrl');}?>" />
              </div>
            </div>
            <p class="FloatRight save-back"> 
              <!--<a class="FloatLeft back-top" href="<?php echo base_url().folder_name."/section_manager";?>" data-toggle="tooltip" title="Hooray!"><i class="fa fa-reply fa-2x"></i></a>
 				<button class="btn-primary btn" type="button" name="btnSectionAdd" id="btnSectionAdd"><i class="fa fa-file-text-o"></i> &nbsp;Save</button></p>--> 
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
function get_parent_section()
{
	//var Psection_id = $('#txtParentID').val();
	var Psection_id = $('#ddSectionName option:selected').val();
	$.ajax(
	{
		url: "<?php echo base_url().folder_name; ?>/section_manager/get_parent_url_structure", 
		type: "post",
		data: {"Psection_id":Psection_id},
		success: function(data)
		{
			//$('#txtgrandParentName').val(data);
			$('#txtParentName').val(data.trim());
		}
	});
}
function get_author_details()
{
	var columnist_id= $('#ddColumnist option:selected').val();
	$.ajax(
	{
		url: "<?php echo base_url().folder_name; ?>/section_manager/get_author_by_id", 
		type: "post",
		data: {"columnist_id":columnist_id},
		dataType: "json",
		success: function(data)
		{
			$("#chkColumnist_img").val('');
			$("#chkColumnist_biograph").val(data.ShortBiography);
			
		}
	});
	
}
function get_pagemaster_details()
{
	var section_id= $('#txtSectionId').val();
	$.ajax(
	{
		url: "<?php echo base_url().folder_name; ?>/section_manager/getPageDetails", 
		type: "post",
		data: {"section_id":section_id},
		success: function(data)
		{
			$("#pagecount").val(data);
		}
	});

}
function display_order()
{
	var ddorder=$('#ddDisplayOrder').val();
	
	//$('#txtDisplayOrder').val();
	var section_id=$('#txtSectionId').val();
	
	if($('#chkSubSection').prop("checked"))
	{
		chek_val = "child"
	}
	else
	{
		chek_val = "parent"
	}
	var menuid=$('#ddSectionName').val();
	var section_id   = $("#txtSectionId").val();
	$.ajax(
	{
		//url: "<?php echo base_url(); ?>admin/section_manager/display_order", 
		url: "<?php echo base_url().folder_name; ?>/section_manager/get_sectionname", 
		type: "post",
		data: {"chek_val":chek_val,"menu_id":menuid,"section_id":section_id,"order":ddorder,"section_id": section_id},
		dataType: "json",
		success: function(data)
		{
			
			 	var OptionValue = '<option value="" >Select</option>'
				$.each(data, function(x, item)
				{
					OptionValue += '<option value='+ data[x].DisplayOrder + '>'+ data[x].Sectionname +'</option>';
						  // arrayorder.push(data[x].DisplayOrder);
				});
				$("#ddDisplayOrder").html(OptionValue);
				//$("#ddSectionName").val(parentID);

		}
	});

}
function get_display_order()
{
	var ddorder=$('#ddDisplayOrder').val();
	
	//$('#txtDisplayOrder').val();
	var section_id=$('#txtSectionId').val();
	
	if($('#chkSubSection').prop("checked"))
	{
		chek_val = "child"
		get_parent_section();
		//$('#txtParentName').val($('#ddSectionName option:selected').text().trim());
	}
	else
	{
		chek_val = "parent"
	}
	var menuid=$('#ddSectionName').val();
	var section_id   = $("#txtSectionId").val();	
	
	$.ajax(
	{
		url: "<?php echo base_url().folder_name; ?>/section_manager/get_maxdisplay_order", 
		type: "post",
		data: {"chek_val":chek_val,"menu_id":menuid,"section_id":section_id,"order":ddorder,"section_id": section_id},
		dataType: "json",
		success: function(data)
		{
			$('#ddDisplayOrderHidden').val(data+1);
		}
	});

}
function get_last_sectionname()
{
	var ddorder=$('#ddDisplayOrder').val();
	//$('#txtDisplayOrder').val();
	var section_id=$('#txtSectionId').val();
	if($('#chkSubSection').prop("checked"))
	{
		chek_val = "child"
	}
	else
	{
		chek_val = "parent"
	}
	
	var menuid=$('#ddSectionName').val();
	var section_id   = $("#txtSectionId").val();	
	
	$.ajax(
	{
		url: "<?php echo base_url().folder_name; ?>/section_manager/get_lastsectionname", 
		type: "post",
		data: {"chek_val":chek_val,"menu_id":menuid,"section_id":section_id,"order":ddorder,"section_id": section_id},
		//dataType: "json",
		success: function(data)
		{
			if(data!="no")
			{
				$('#txtLastSection').html('This section will be added after last section '+data);
				$('#changeOrder').show();
				<?php if(isset($op_Section_id)&& $op_Section_id!=""){ ?>
						$('#TxtDisplay').hide();
						<?php } else {?>
						$('#TxtDisplay').show();
						<?php }?>
			}
			else
			{
				$('#changeOrder').hide();
				$('#TxtDisplay').hide();
				//$('#txtLastSection').html('There is no any subsection available for selected section');
				
			}
			//alert(data);
			
		}
	});
	
}

function check_records()
{
	$.ajax(
	{
		url: "<?php echo base_url().folder_name; ?>/section_manager/check_records", 
		type: "post",
		data: {},
		dataType: "json",
		success: function(data)
		{
			if(data=="0")
			{
				$('#hide_spldd').hide();
				$('#optReplace').hide();
				$('#optRevert').hide();
			}
		
		}
	});
	
}
function validate()
{
$("#optRevert").change(function()
{
	if(this.checked) 
	{
		$('#optReplace').prop('checked', false);
		//alert('s');
		//spl_main_menus();
		
	} 
});

$("#optReplace").change(function()
{
	if(this.checked) 
	{
		$('#optRevert').prop('checked', false);
		//alert('s');
		//spl_main_menus();
		
	} 
});

	
}




</script> 
<script>
$(document).ready(function()
{
	
	<?php if($op_AuthorID != 'NULL' && $op_AuthorID != ''){ ?>
		get_author_details();
	<?php } ?>
	get_last_sectionname();
	display_order();
	get_display_order();
	check_records();
	<?php if(isset($op_Section_id)){ ?>
		get_pagemaster_details();
	<?php } ?>
	$(".next-error + p").css("color", "#f00");

 var meta_title = true;
 var section_urltitle = true;
 $('#txtSectionName').keyup(function(event){
  meta_title = false;
   section_urltitle = false;
 });
 
 if($('#txtMetaTitle').val()!=="")
  meta_title = false;
  
 $('#txtMetaTitle').keypress(function(){
  meta_title = false;
 });
 
  if($('#txturlsectionname').val()!=="")
  section_urltitle = false;
  
 $('#txturlsectionname').keypress(function(){
  section_urltitle = false;
  (($('#error_url').length) != 0)? $('#error_url').remove(): '';
 });
   
 var headline = CKEDITOR.instances.txtSectionName;
 headline.on('contentDom', function() {
    headline.document.on('keyup', function(event) {
   
    var decoded_headline = $("<div/>").html(headline.getData()).text();
    
    if(meta_title == true)
     $('#txtMetaTitle').val(decoded_headline); 
	 
	if(section_urltitle == true)
     $('#txturlsectionname').val(decoded_headline); 
	 
	 <?php if(isset($disable)&&($disable > 0) ){  ?>  
	 var section=$('#txthdnsection').val();
	
	  var regex = /(<([^>]+)>)/ig
	 // var editedsection =$('#txtSectionName').val();
	  	  var editedsection =CKEDITOR.instances.txtSectionName.getData();

	  var result1 = editedsection.replace(regex, "");
	  var result2 = section.replace(regex, "");
	  if($.trim(result1)!= $.trim(result2) )
	  {
			  CKEDITOR.instances.txtSectionName.setData(section);
			  $('#message_change').html('This section is already mapped either in Frontpage manager or Contents module. Name cannot be changed');
	  }
	 
	  <?php }?>
    
    });
  });	
  	
});
<?php /*?><?php if(isset($op_IsSubSection))
{ 

	if($op_IsSubSection == 1 && $op_IsSpecialSection== 1) 
	{
?>
	 $('#spl_menu').hide();
<?php }} ?><?php */?>

<?php /*?><?php if(isset($op_Section_id)){
	if($op_IsSpecialSection== 0)
	{ ?>
 $('#spl_menu').hide();
<?php }}?><?php */?>


<?php /*?><?php if(isset($op_Section_id)&& ($op_IsSpecialSection == 1)) { ?>

$('#hide_spldd').hide();
<?php } else {?>
 <?php }?><?php */?>
 
<?php if(isset($disable)&&($disable > 0) && ($op_IsSubSection!=1)  ) { ?> $('#hide_spldd').hide(); <?php } ?>
<?php if(isset($op_Section_id)){ ?> 
$('#rever_replaceedit').hide();
$('#display_order').hide();

$("#chkChangeOrder").change(function()
{
	if(this.checked) 
	{
		$("#rever_replaceedit").show();
		$("#display_order").show();
		$('input[name=view8]').attr("disabled",false);
	} 
	else 
	{
		$('#rever_replaceedit').hide();
		$('#display_order').hide();
		$('input[name=view8]').attr("disabled",true);
		$('#ddDisplayOrder').val('');
	}
});

$('input[name=view8]').attr("disabled",true);
<?php  } else {?>

$('#rever_replaceAdd').hide();
$('#display_order').hide();

$("#chkChangeOrder").change(function()
{
	if(this.checked) 
	{
		$("#rever_replaceAdd").show();
		$("#display_order").show();
		$('input[name=view9]').attr("disabled",false);
	} 
	else 
	{
		$('#rever_replaceAdd').hide();
		$('#display_order').hide();
		$('input[name=view9]').attr("disabled",true);
		$('#ddDisplayOrder').val('');
	}
});

$('input[name=view8]').attr("disabled",true);
$('input[name=view9]').attr("disabled",true);

<?php }?>
</script>