<style>
.mandatory
{
	color:#900;
}
</style>
<script>
function byLine()
{ 
$("#call_show1").removeClass("none");
$("#call_show1").addClass("showdiv");

//Make sure schoolDIV is not visible
$("#call_show2").removeClass("showdiv");
$("#call_show2").addClass("none");
}

function byCol()
{
	 
$("#call_show2").removeClass("none");
$("#call_show2").addClass("showdiv");

//Make sure bankDIV is not visible
$("#call_show1").removeClass("showdiv");
$("#call_show1").addClass("none");
}

function byHouse()
{
$("#externalname").addClass("none");
$("#externalname").removeClass("showdiv");
}


function byExe()
{
$("#externalname").addClass("showdiv");
$("#externalname").removeClass("none");
}
 
</script>
  <script>
 
 function emailvalidation(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };
 var values="<?php echo $this->uri->segment(4);?>";
 function validateformdata()
 {
		if($('input[name=rbtab-group-1]:checked').val()=='Byline')
		{
		 	var displaynamemessage="Please enter display name of byliner";
		}
		if($('input[name=rbtab-group-1]:checked').val()=='columnist')
		{
		 	var displaynamemessage="Please enter display name of columnist";
		}
	 
		if($("#displayname").val()=="")
		{
			$("#error_displayname").html(displaynamemessage);
			$("#displayname").focus();
			return false;
		}
		else
		{
			if (/^[a-zA-Z0-9- ]*$/.test($("#displayname").val()) == false) 
			{
				$("#error_displayname").html("Please enter valid characters only");
				$("#displayname").focus();
				return false;
			}
			else
			{
				$("#error_displayname").html("");
			}
		}
		
		var email=document.getElementById('email');
		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; 
		if($("#email").val()=="")
		{
			 
				$("#error_email").html("Please enter Email Id");
				$("#email").focus();
				return false;
		}
		else
		{
			if (emailvalidation($("#email").val())) 
			{
				
				$('#loadingsel').show();
				$.ajax({
				url:'<?php echo base_url()."admin/author_managers/emailcheck";?>',
				type:"POST",
				data:"authord="+values+"&emailcheck="+$("#email").val(),
				//data: '{ "emailcheck":' + $(this).val() + ', "id":"en" }',
				success: function(data) 
				{
					 
					if(data)
					{
						$("#error_email").html(data+" is already exist");
						$("#email").focus();
						return false;
					}
				// location.reload();
				
				} 
				
				});
				 $("#error_email").html(" ");
			}
			else
			{
					$("#error_email").html("Please enter Email Id in correct format");
					$("#email").focus();
					return false;
			}	
		}
		
		/*if($("#biography").val()=="")
		{
			$("#error_biography").html("Please enter Biography");
			$("#biography").focus();
			return false;
		}
		else
		{
		 	$("#error_biography").html(" ");
		} */
		
		if($('input[name=rbtab-group-1]:checked').val()=='Byline')
		{
			var confirmmessage="Are you sure you want to save the byliner details?";
			if($('input[name=rbbylinesource]:checked').val()=='External')
			{
				if($("#txtexternalname").val()=="")
				{
					$("#error_externalname").html("Please enter external agency name");
					$("#txtexternalname").focus();
					return false;
				}
				else
				{
					 $("#error_externalname").html(" ");
				}
			}
			else
			{
				 $("#error_externalname").html(" ");
			}
		}
		else
 		{
			if($('input[name=rbtab-group-1]:checked').val()=='columnist')
			{
				var confirmmessage="Are you sure you want to save the columnist details?";
				 
				if($("#filestatus").val()!=2)
				{  
					if($("#uploaduserimage").val()=='')
					{ 
						<?php
						if(empty($authors['0']['Displayimagepath']))
						{
						?>
							$("#error_uploaduserimage").html("Please upload author image");
							$("#uploaduserimage").focus();
							return false;	 
						<?php
						}
						?>
					}
					else
					{
						var filename=$("#uploaduserimage").val();
						var fileext=filename.substring(filename.lastIndexOf('.')+1);
						if(fileext=="jpg" || fileext=="JPG" || fileext=="jpeg" || fileext=="JPEG"  || fileext=="png"  || fileext=="PNG" || fileext=="GIF" || fileext=="GIF"  )
						{
							  
						}
						else
						{
								$("#error_uploaduserimage").html("invalid file. Please upload image file");
								$("#uploaduserimage").focus();
								return false;
						}
					}
				}
				 
				
			}
			
		}
	     
		 
		 
			 
			var emaildata='';
			$.ajax({
			url:'<?php echo base_url()."admin/author_managers/emailcheck";?>',
			type:"POST",
			data:"authord="+values+"&emailcheck="+$("#email").val(),
			//data: '{ "emailcheck":' + $(this).val() + ', "id":"en" }',
			success: function(data) 
			{
				if(data)
				{
					emaildata='exist';
					//$("#emailerror_exist").html(data+" is already exist");
					//$("#emailerror_exist").show();
					$("#error_email").html(data+" is already exist");
					$("#email").focus();
					return false;
				}
				else
				{
					$("#emailerror_exist").hide();
					var checksubmit = confirm(confirmmessage);
					if(checksubmit==true)
					{ 	
						document.authorform.submit();
						
					}
					else
					{
						return false;
					}
				}
			// location.reload();
			
			} 
			
			});
			return false;
		 
 
		 
 }
 

</script>

<script type="text/javascript">
$(document).ready(function(){
 
/*     $('#authorform').ajaxForm({ 
      beforeSubmit:  validateformdata,
    	success: function(data)
    		{ 
				alert(data);  
            }      
            
    	});*/
		
		 /*$('.removeimage').click(function(){
    
			var status = confirm("Are you sure to remove this image?");
			if(status==true)
			{ 
			 $('#loadingsel').show();
			$.ajax({
			url:'<?php //echo base_url()."admin/author_managers/deleteauthorimage";?>',
			type:"POST",
			data:"author_id="+$(this).attr('id'),
			success: function(data) {
			 $('#loadingsel').hide();
			 if(data==3)
			 {
			 alert("Sorry you dont have permission to delete");
			  
			 }
			 else
			 {
			  
			location.reload();
			}
				} 
			
			});
			return false;
			}
			else
			{
			return false;
			}
			
			});	*/
		 
		$("#uploaduserimage").on('change', function(e) { 
	   
		var filename=$("#uploaduserimage").val();
		var ext = filename.substring(filename.lastIndexOf('.') + 1);
		if(ext == "jpg" || ext == "png" || ext == "jpeg" || ext == "JPEG" || ext == "JPG" || ext == "PNG" || ext == "GIF" || ext == "gif")
		{ 
				 var formData = new FormData();
				formData.append('uploaduserimage', $('input[type=file]')[0].files[0]);
				 
				e.preventDefault(); 
				$.ajax({
				url: "<?php echo base_url()."admin/author_managers/upload_author_images";?>",
				type: "POST",      				 
				data:  formData,
				contentType: false,       	 
				cache: false,					 
				processData:false,  			 
				success: function(data)  		 
				{
					 
					$("#loadingimage").hide();
					$('#call_show2').html(data); 
				}	        
				});
				 
		} 
		else
		{
				$("#error_uploaduserimage").html("invalid file. Please upload image file");
				$("#uploaduserimage").focus();
				$("#uploaduserimage").val("");
				return false;
		}
	}); 
	
	
	$('#email').change(function(){
    if($('#email').val()!='')
	{ 
			var values="<?php echo $this->uri->segment(4);?>";
			$('#loadingsel').show();
			$.ajax({
			url:'<?php echo base_url()."admin/author_managers/emailcheck";?>',
			type:"POST",
			data:"authord="+values+"&emailcheck="+$(this).val(),
			//data: '{ "emailcheck":' + $(this).val() + ', "id":"en" }',
			success: function(data) 
			{
			  	if(data)
				{
					$("#error_email").html(data+" is already exist");
					$("#email").focus();
				}
			// location.reload();
			
			} 
			
			});
    
	}
    });	  
    
});
</script>

<div class="Container">
<div class="BodyWhiteBG">
<?php
if(!empty($authors))
{
?> 
<form action="<?php echo base_url()."admin/author_managers/author_details/".$this->uri->segment(4);?>"  name="authorform" id="authorform" method="post" enctype="multipart/form-data" onsubmit="return validateformdata();">
     <input type="hidden" name="page" value="editauthor" />
<div class="BodyHeadBg Overflow clear">     
<div class="FloatLeft BreadCrumbsWrapper PollResult">
<div class="breadcrumbs"><a href="#">Dashboard</a> &gt; <a href="#">Edit Author</a></div>
<h2 class="FloatLeft">Edit Author</h2>
</div>
<?php
if(!empty($this->session->flashdata("success")))
{
?>
 <div class="FloatLeft Success"><?php echo $this->session->flashdata("success");?></div>
<?php
}
?> 
<?php
if(!empty($this->session->flashdata("error")))
{
?>
 <div class="FloatLeft Error"><?php echo $this->session->flashdata("error");?></div>
<?php
}
?>

<div class="FloatLeft Error" id="emailerror_exist" style="display:none"></div>

 <p class="FloatRight save-back">
 <a href="<?php echo base_url()."admin/author_managers";?>" class="FloatLeft back-top"><i class="fa fa-reply fa-2x"></i></a>
 <button type="submit" class="btn-primary btn"  ><i class="fa fa-file-text-o"></i> &nbsp;Save</button></p>
</div>




<div class="tabs Overflow" style="margin-top:10%;">
        
       <div class="tab">
       <div class="switch switch-blue">
        <?php
    if($authors['0']['Type']=="B")
	{
	?>
        <input type="radio" id="tab-1 week2" checked="checked"  name="rbtab-group-1" onclick="byLine()" class="switch-input"  value="Byline">
        <label for="tab-1 week2" class="tab-12 switch-label switch-label-off">Byline</label>
        
        <input type="radio" id="tab-2 month2" name="rbtab-group-1" onclick="byCol()" class="switch-input" value="columnist" >
        <label for="tab-2 month2" class="tab-12 switch-label switch-label-on">Columnist</label> 
    <?php
	}
	else
	{
		?>
        
        <input type="radio" id="tab-1 week2"   name="rbtab-group-1"  onclick="byLine()" class="switch-input"  value="Byline">
        <label for="tab-1 week2" class="tab-12 switch-label switch-label-off">Byline</label>
        
        <input type="radio" id="tab-2 month2" checked="checked"  name="rbtab-group-1" onclick="byCol()" class="switch-input" value="columnist" >
        <label for="tab-2 month2" class="tab-12 switch-label switch-label-on">Columnist</label>
            
        <?php
	}
	?>
       
       
           
          <span class="switch-selection"></span>
           </div>
           </div>
            
           <div class="content">
           <div class="columnist1">
    		<p><label class="txtdisplayname" id="name">Display Name</label> <span class="mandatory">*</span></p>
      		<p><input id="displayname" class="box-shad box-shad1" name="txtdisplayname" type="text" value="<?php echo $authors['0']['AuthorName']?>" maxlength="50" ></p>
            <p class="mandatory" id="error_displayname">
			<?php
            if(!empty(form_error('displayname')))
            {
            	echo form_error('displayname');
            } 
            ?>
            </p>
    		<p><label name="txtemail" id="txtemail">Email</label> <span class="mandatory">*</span></p>
      		<p><input id="email" class="box-shad box-shad1" name="txtemail"  type="text" placeholder="test@example.com" maxlength="255" value="<?php echo $authors['0']['Email']?>"></p>
            <p class="mandatory" id="error_email"><?php
			if(!empty(form_error('email')))
			{
				echo form_error('email');
			} 
			?></p> 
            
            <p ><label class="biography" id="biographys">Short Biography</label> </p>
            <p class="TextAreaWidth"><textarea id="biography" name="txtbiography" class="biography1 box-shad box-shad1"><?php echo $authors['0']['ShortBiography'];?></textarea></p>
            <p class="mandatory" id="error_biography">
            <?php
            if(!empty(form_error('biography')))
            {
            echo form_error('biography');
            }
            
            ?>
            </p>
 
            </div>
			<?php
            if($authors['0']['Type']=="B")
            {
				?>
				<div class="columnist2">
					<div class="columnist2a" id="call_show1" >
					<h3>Source</h3>
						<div class="source1">
							<div class="switch switch-yellow">
                            <?php
							if($authors['0']['Source']=="E")
							{
							?>
								<input type="radio" class="switch-input" name="rbbylinesource" value="inhouse" id="week3"  onclick="byHouse()">
								<label for="week3" class="switch-label switch-label-off">In-House</label>
								<input type="radio" class="switch-input" name="rbbylinesource" value="External" id="month3" checked="checked" onclick="byExe()">
								<label for="month3" class="switch-label switch-label-on">External</label>
                            <?php
							}
							else
							{
								?>
                                <input type="radio" class="switch-input" name="rbbylinesource" value="inhouse" id="week3"  onclick="byHouse()">
								<label for="week3" class="switch-label switch-label-off">In-House</label>
								<input type="radio" class="switch-input" name="rbbylinesource" value="External" id="month3"   onclick="byExe()">
								<label for="month3" class="switch-label switch-label-on">External</label>
                                <?php
							}
							?>
								<span class="switch-selection"></span>
							</div>
						</div>
							
                            <?php
							if($authors['0']['Source']=="E")
							{
							?>	<div style="width:100%; float:left; text-align:left;"  id="externalname"  class="showdiv" >
                            	Agency Name
                                <input type="text" id="txtexternalname" maxlength="100"  name="txtexternalname"class="source_tb box-shad box-shad1 " value="<?php echo $authors['0']['ExternalAgencyName'];?>"   />
                                <p class="mandatory" id="error_externalname"><?php
								if(!empty(form_error('externalname')))
								{
									echo form_error('externalname');
								} 
                                ?>
                                </p>
                                </div>
                            <?php
							}
							else
							{
								?>
                                <div style="width:100%; float:left; text-align:left;"  id="externalname"  class="none" >
                                Agency Name
                                <input type="text" id="txtexternalname" maxlength="100"  name="txtexternalname"class="source_tb box-shad box-shad1 "     />
                                <p class="mandatory" id="error_externalname"><?php
                                if(!empty(form_error('externalname')))
                                {
                                    echo form_error('externalname');
                                } 
                                ?>
                                </p>
                                </div>
                                <?php
							}
							?>  
							
					</div>
							<div class="columnist2a none" id="call_show2">
							<figure>
                            Profile Image<br />
							<img src="<?php echo base_url();?>images/admin/author.jpg">
							</figure>
							
							<div class="fileUpload btn btn-primary">
								<span>Upload</span>
								<input type="file" class="upload" id="uploaduserimage" name="uploaduserimage" />
							</div>
							<p class="mandatory" id="error_uploaduserimage"></p>  	 
				
				
							</div>
			   </div>
			   <?php
			}
			else
			{
				?>
                <div class="columnist2">
                <div class="columnist2a none" id="call_show1"  >
                <h3>Source</h3>
                    <div class="source1">
                        <div class="switch switch-yellow">
                        
                        <input type="radio" class="switch-input" name="rbbylinesource" value="inhouse" id="week3" checked="checked" onclick="byHouse()">
                        <label for="week3" class="switch-label switch-label-off">In-House</label>
                        <input type="radio" class="switch-input" name="rbbylinesource" value="External" id="month3"   onclick="byExe()">
                        <label for="month3" class="switch-label switch-label-on">External</label>
                        
                            
                            <span class="switch-selection"></span>
                        </div>
                    </div>
                            <div style="width:100%; float:left; text-align:left;"  id="externalname"  class="none" >
                            Agency Name
                         
							<input type="text" id="externalname" maxlength="100"  name="txtexternalname"class="source_tb box-shad box-shad1"   />
							<p class="mandatory" id="error_externalname"><?php
							if(!empty(form_error('externalname')))
							{
								echo form_error('externalname');
							}
							 
							?></p> 
							 
                        </div>
                </div>
                        <div class="columnist2a showdiv" id="call_show2"  > 
                         <?php
						if(!empty($authors['0']['Displayimagepath']))
						{
							?>
                            <figure> 
                            Profile Image<br />
                            <?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $authors['0']['Displayimage'] ).'"   style="width: 130px;  "    border="0"  />';?>
                            </figure> 
                            <div class="fileUpload btn btn-primary">
                            <span>Change</span>
                            <input type="file" class="upload" id="uploaduserimage" name="uploaduserimage" />
                            </div>
                             <p class="mandatory" id="error_uploaduserimage"></p>  
                            <?php 
							
						}
						else
						{
							?>
                            <figure> 
                            Profile Image<br /> 
                            <img src="<?php echo base_url();?>images/admin/author.jpg">
                            </figure>
                            
                            <div class="fileUpload btn btn-primary">
                                <span>Upload</span>
                                <input type="file" class="upload" id="uploaduserimage" name="uploaduserimage" />
                            </div>
                             <p class="mandatory" id="error_uploaduserimage"></p>  
                            <?php
						}
					  ?> 
                        
                             
            
            
                        </div>
           </div>
                <?php
			}
		    ?>
           
             
            
           <div class="tab">
       <div class="switch switch-blue">
       		<?php
            if($authors['0']['Status']=='A')
			{
				
			?>
           <input type="radio" id="status1" name="status" checked="checked"   class="switch-input"  value="A">
           <label for="status1" class="tab-12 switch-label switch-label-off">Active</label>
           
           <input type="radio" id="status2" name="status"  class="switch-input" value="I" >
           <label for="status2" class="tab-12 switch-label switch-label-on">Inactive</label>
           <?php
			}
			else  
			{
				?>
                   <input type="radio" id="status1" name="status"  class="switch-input"  value="A">
                   <label for="status1" class="tab-12 switch-label switch-label-off">Active</label>
                   
                   <input type="radio" id="status2" name="status"  checked="checked"   class="switch-input" value="I" >
                   <label for="status2" class="tab-12 switch-label switch-label-on">Inactive</label>
                <?php
			}
		   ?>
          <span class="switch-selection"></span>
           </div>
           </div>   
           </div> 
           <p class="FloatRight save-back">
 <a href="<?php echo base_url()."admin/author_managers";?>" class="FloatLeft back-top"><i class="fa fa-reply fa-2x"></i></a>
 <button type="submit" class="btn-primary btn" ><i class="fa fa-file-text-o"></i> &nbsp;Save</button></p> 
    </div>
 </form>     
<?php
}
?>                            
</div>                            
</div>                       
                       