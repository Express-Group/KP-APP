<?php 
$widget_article_css = base_url()."css/admin/";  ?>
<div class="css_and_js_files">
<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";
var admin_url= "<?php echo base_url().folder_name."/"; ?>"; 
var version_id = '<?php echo $version_id; ?>';
var user_id = '<?php echo USERID; ?>';
</script>
<script src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js"></script>
</div>

<div class="Container">
	<div class="BodyWhiteBG">
<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper ">
				<div class="breadcrumbs">Front Page Manager</div>
                <?php $page_type_name = ($pagetype == 1) ? "section":"article"; ?>
 					<h2>Add Header Script to <?php echo "' ".$pagesection_name." ' ".$page_type_name. " landing page"; ?> </h2> 
			</div>

            <span class="FloatRight save-wid save-back save_margin article_save">
            <a class="back-top FloatLeft" title="Go Back" id="go_back" href="<?php echo base_url().folder_name; ?>/template_designer/index/<?php echo @$page_id."-".$scroll_top."-".$page_sectionid."-".$pagetype."-"."-".$version_id; ?>"> 			
            <i class="fa fa-reply fa-2x"></i>
            </a>
            <button class="btn-primary btn FloatRight" type="submit" onclick="return saveHeaderScript()" ><i class="fa fa-file-text-o" data-original-title="Active"></i> Save</button>
            </span>            
            <span id="show_alert" style="color:#F00;"></span>            
		</div>
        
 <div class="Overflow DropDownWrapper">       
   <form name="save_header_script_form" id="save_header_script_form" action="<?php echo base_url().folder_name."/template_designer/add_header_script"; ?>" method="post">
   
			<table class="header-addscript" >
               <tr>
                	<td><h4>Header Advertisement Script</h4></td>
                  	<td>
                          <textarea id="add_header_script_save" name="add_header_script_save"><?php echo $header_script; ?></textarea>
                          <span style="color:#F00;" id="add_header_script_save_span"></span>
                          <input type="hidden" name="pagesection_name" id="pagesection_name" value="<?php echo $pagesection_name; ?>"  />
                          <input type="hidden" name="page_sectionid" id="page_sectionid" value="<?php echo $page_sectionid; ?>"  />
                          <input type="hidden" name="pagetype" id="pagetype" value="<?php echo $pagetype; ?>"  />
                          <input type="hidden" name="scroll_top" id="scroll_top" value="<?php echo $scroll_top; ?>"  />
                          <input type="hidden" name="versionId" id="versionId" value="<?php echo $version_id; ?>"  />
                          <input type="hidden" name="page_id" id="page_id" value="<?php echo $page_id; ?>"  />
                          
                          <input type="hidden" name="save_script" id="save_script" value="save_script"  />
                  </td>
               </tr>
        </table>
   
   </form>
    </div>

 </div>
</div> 
<script type="text/javascript">

$(document).ready(function(){		
	toastr.options = {
					  "closeButton": false,					  
					  "newestOnTop": true,					  
					  "positionClass": "toast-top-center"					
					};
	$('.is-template-version-saved').click(function(){
		release_locks_by_user_id();
	});
	
	if('<?php echo $updated_msg ?>' != '' && '<?php echo $show_msg ?>' == '1'){
		show_toastr('<?php echo $updated_msg ?>', '<?php echo $msg_type ?>');
	}
});
//$("#show_alert").html('<?php //echo $updated_msg ?>').fadeIn("slow").delay(4000).fadeOut("slow");

function saveHeaderScript()
{
	$('#add_header_script_save_span').html("");
	///  Please verify this page is locked or not  ///
	var lock_details = {"header_lock_status":"", "header_locked_user_id":"", "access_status" : 0};
	$.ajax({				
		url: admin_url + "template_designer/get_header_script_lock_status",
		type: 'post',
		async: false,
		data: "versionId= "+ version_id,				
		dataType: 'json',
		beforeSend: function() {
				$("#loading_msg").html("Please wait...");
				$("#commom_loading").show();
				}, 						
		success: function (data) {
			$("#commom_loading").hide();
			$("#loading_msg").html("");
			
			if(data.show_msg == 1){ show_toastr(data.msg, data.msg_type); }
			lock_details = {"header_lock_status" : data.lock_status, "header_locked_user_id" : data.locked_user_id, "access_status" : data.res_status}; 
			if(data.locked_user_id != '' && data.locked_user_id == user_id){
				if(confirm("Are you sure to save?"))
				{						
					if(true){
						$('#save_header_script_form').submit();
						return true;
					}
					else
					{
						return false;
					}		
				}
				else
				{
					return false;
				}
			}
			else
			{
				
				setTimeout(function() {
							window.location.href = $("#go_back").attr('href');
				}, 200);
				show_toastr('Lock released forcefully ', 2);
				
			}
			
		},
		error: function (e) {
			$("#commom_loading").hide();
			$("#loading_msg").html("");	
			//alert("error");										
		}
	});
}
function release_locks_by_user_id()
{									
	$.ajax({						
		url: admin_url + "template_designer/release_locks_by_user_id",
		type: 'post',
		async: false,
		data: "",				
		dataType: 'json',
		beforeSend: function() {
				$("#loading_msg").html("Please wait...");
				$("#commom_loading").show();
				}, 						
		success: function (data) {
			$("#commom_loading").hide();
			$("#loading_msg").html("");
			/* Locks released based on user id */
			if(data){ show_toastr("Locks released", 1); }
			else{ show_toastr("Failed to release locks", 2); }
		},
		error: function (e) {
			$("#commom_loading").hide();
			$("#loading_msg").html("");	
			show_toastr("Failed to release locks", 2);
			//alert("error");						
		}
	});
}
/////  Show Toastr message  /////
function show_toastr(message, toastr_type)
{
	if(message != ''){
	///////  toastr_type = 1 means success message, toastr_type = 2 means Failure message /////
	(toastr_type == 1) ? toastr.success(message) : (toastr_type == 2)? toastr.error(message) : toastr.info(message);
	}
}
				
</script>    
