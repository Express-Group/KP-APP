<?php
//print_r($supported_image_sizes); exit;
$content_type_list = array();
$widget_article_css = image_url."css/admin/";  
$widget_article_js = image_url."js/";  
?>

<div class="css_and_js_files">
  <link href="<?php echo $widget_article_css; ?>prabu-styles.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo $widget_article_css; ?>bootstrap.min.css" rel="stylesheet" >  
  <link rel="stylesheet" href="<?php echo $widget_article_css.'template_design/css/jquery-ui.css'; ?>">

  <link href="<?php echo $widget_article_css; ?>jquery.dataTables.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo image_url; ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";
var admin_url ="<?php echo base_url().folder_name."/"; ?>"; 
var admin_folder = "<?php echo folder_name; ?>"; 
</script> 
  <script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery-1.7.2.min.js" ></script> 
  <script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery-ui.min-1.8.18.js"></script>   
  <script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery.dataTables.js"></script> 
  <script type="text/javascript" src="<?php echo $widget_article_js; ?>w2ui-fields-1.0.min.js"></script> 
  <script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery.validate.min.js"></script> 
  <script type="text/javascript" src="<?php echo image_url; ?>includes/ckeditor/ckeditor.js"></script> 
  <script type="text/javascript" src="<?php echo image_url; ?>includes/ckeditor/adapters/jquery.js"></script> 
  <script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery.remodal.js"></script> 
  
  <link href="<?php echo $widget_article_css; ?>/template_design/css/widget-articles.css" rel="stylesheet" type="text/css" />
  
</div>
<?php  
//print_r($widget_details); exit;
/////// $widget_details['minimumContent'], $widget_details['maximumContent'], 
//$add_articles_limit = ($widget_details['maximumContent'] >= $widget_details['minimumContent']) ? $widget_details['maximumContent'] : $widget_details['minimumContent'];
$add_articles_limit = (@$widget_details['minimumContent'] =='') ? 0 : @$widget_details['minimumContent'];
?>
<script type="text/javascript">
var user_id						= '<?php echo USERID; ?>';
var current_lock_status			= '<?php echo $current_lock_status; ?>';
var pagesection_id 				= '<?php echo $page_sectionid;?>';
var page_type 					= '<?php echo $page_type;?>';
var table;
var backto_instance_articles 	= 'no';
var content_type 				= '<?php echo @$widget_details['contentType']; ?>';
var edit_flag	 				= '<?php echo ($edit_flag <= 0)? "Add" : "Edit"; ?>';
var temp_article_list			= '';	
var is_have_related_article 	= '<?php echo ((@$widget_details['isRelatedArticleAvailable'] == "1") ? true : false); ?>';

var related_content_id			= '<?php echo @$related_content_id; ?>';
var version_id					= '<?php echo @$version_id ?>';
var live_version_id				= '<?php echo @$live_version_id ?>';

var parsed						= JSON.parse('<?php echo $supported_image_sizes; ?>');
var supported_image_sizes		= JSON.parse('<?php echo $supported_image_sizes_json; ?>');

var isUserDoneAnyAction 		= false;
if(related_content_id != '')
{
	var dataTableAjaxUrl = admin_url + "template_designer/get_widgetInstance_related_article";
}
else
{
	var dataTableAjaxUrl = admin_url + "template_designer/get_widgetInstancearticle";
}
//alert(related_content_id);
function show_edit(checkbox_value)
{
	$("#example .ui-sortable" ).sortable("enable");
	//alert($("input[type='checkbox'][name='articles_list'][id="+checkbox_value+"]").is(":checked") + checkbox_value);
	var checked_checkbox_obj = $("input[type='checkbox'][name='articles_list'][id="+checkbox_value+"]");
	if(checked_checkbox_obj.is(":checked"))
	{
		$('#edit_'+checkbox_value).show();	
		
		
		if(is_have_related_article && related_content_id == 0)
		{			
			$('#add_related_'+checkbox_value).show();	
		}
		//temp_save_individual(checked_checkbox_obj);	
	}
	else
	{
		$(".close_article").trigger('click');
		$('#edit_'+checkbox_value).hide();
		
		
		if(is_have_related_article  && related_content_id == 0)
		{
			$('#add_related_'+checkbox_value).hide();	
		}
		$('.custom-row-show').remove();  
		$('tr.even').removeClass('shown');
		$('tr.odd').removeClass('shown');
	}
	
	isUserDoneAnyAction = true;
}

function back_to_add_widget_articles()
{	
	backto_instance_articles = "yes";
	$(".show_related_articles").trigger( "click" );	
}
function save_related_articles()
{	
	temp_save_articles("");	
}

var remove_image = false;
//////  Remove existed Image  ///////
function remove_custom_image(current_article_id)
{
	remove_image = true;
	//$(".save_custom_details").trigger( "click" );			
	if(confirm("Are you sure you want to remove image?"))
	{
		$('.ArticleImageContainerId'+ current_article_id).hide();
		$('#home_image_set'+ current_article_id).text('Browse');
		$('#home_image_set'+ current_article_id).addClass('browse-image');
		$('#edit_image_span').hide();
		$('#remove_image_span').hide();
		return true;
	}
	else
	{
		return false;
	}
}

/////  Show Toastr message  /////
function show_toastr(message, toastr_type)
{
	if(message != ''){
	///////  toastr_type = 1 means success message, toastr_type = 2 means Failure message /////
	(toastr_type == 1) ? toastr.success(message) : ((toastr_type == 2) ? toastr.error(message) : toastr.info(message));
	}
}				

/////// Uploading Image preview  /////
var openFile = function(event, image_id, image_width, image_height) {

	var input = event.target;
	var reader = new FileReader();
	if (!(/\.(gif|jpg|jpeg|tiff|png)$/i).test(input.value)) { 
		//alert('You must select an image file only');               
		show_toastr('You must select an image file only', 2);
	}
	else
	{
		var u = URL.createObjectURL(image_id.files[0]);
		var img = new Image;
		img.onload = function() {
		   //////  image size is available in DB  /////
		   if(image_width != '0' && image_height != '0')
		   {
			   if( img.width != image_width || img.height != image_height ) 
			   {
				   //if(false) {
					//alert("Please upload valid dimension image(width*height) \n Valid : (" + image_width + "*" + image_height + ") \n Uploaded : (" + img.width + "*" + img.height + ")") ;
					show_toastr("Please upload valid dimension image(width*height) \n Valid : (" + image_width + "*" + image_height + ") \n Uploaded : (" + img.width + "*" + img.height + ")", 2);
					$("input[name='customImage']").val('');
				}
				else{
						var reader = new FileReader();
						reader.onload = function(){
							  var dataURL = reader.result;
							  var output = document.getElementById(image_id.id+"_view");
							  output.src = dataURL;
						};
						reader.readAsDataURL(input.files[0]);
					}
		   }
		   else //////  image size is NOT available in DB  /////
		   {
				var reader = new FileReader();
				reader.onload = function(){
					  var dataURL = reader.result;
					  var output = document.getElementById(image_id.id+"_view");
					  output.src = dataURL;
				};
				reader.readAsDataURL(input.files[0]);
		   }
		   
		};
			
		img.src = u;
  }// else - image is upload
};

//////////////////////////////////////

//////  $add_articles_limit  /////
var add_articles_limit 		= '<?php echo $add_articles_limit; ?>';
var widget_style			= '<?php echo $widget_details['widgetStyle']; ?>';
var isRelatedColumn_avail 	= '<?php echo $widget_details['isRelatedArticleAvailable']; ?>';
var top_div_class			= '<?php echo "col-lg-". $top_div_class; ?>';
var widget_parent_container_class = '<?php $col_num = explode("_",$container_class); echo "col-lg-". $col_num[1]; ?>';
var home_value				= '<?php echo $home_value; ?>';
var widget_values			= unescape('<?php echo json_encode($widget_values); ?>');
//console.log(widget_values);
//alert($('#current_widgetinstance').val());
var widget_instance 		= ($('#current_widgetinstance').val() != '' && typeof $('#current_widgetinstance').val() != 'undefined') ? $('#current_widgetinstance').val() : '<?php echo $widget_instance; ?>';

//alert(widget_instance);
var mainSectionConfig_Id 	= $('#mainSectionConfig_Id').val();
var subSectionConfig_Id 	= $('#subSectionConfig_Id').val();	
$(document).ready(function() {
	//alert(widget_style);
	if(widget_style == 1){
		controll_contentType_by_section(); 
	}
	verify_widget_lock();
	toastr.options = {
					  "closeButton": false,					  
					  "newestOnTop": true,					  
					  "positionClass": "toast-top-center",
					  /*"showDuration": "10000",
					  "hideDuration": "10000",
					  "timeOut": "50000",
					  "extendedTimeOut": "10000"*/					  
					};
	$('.is-template-version-saved').click(function(){
		var current_anchor_title = $(this).attr('title');
		current_anchor_title	 = (typeof current_anchor_title === "undefined" || current_anchor_title === "undefined") ? "" : current_anchor_title;
		var after_action 		 = (current_anchor_title != "Go Back") ? $(this).attr("href") : $(this).attr("back_location");
		var current_time_stamp	 = new Date().getTime();
		if(isUserDoneAnyAction){		
				var from_related_articles = (isRelatedColumn_avail) ? 'back_to_add_widget_articles()' : '';			
				//////  Prompt user to save template or not save  ///			
				$("#widget-add-article-confirm").dialog( {
						resizable	: false,
						modal		: true,
						title		: "Do you want to save changes?",
						height		: 250,
						width		: 325,
						buttons		: {									
										"Save": function() {											
											var do_below_functions = {
												"" : savePermanent('from-confirm-dialog-saveTemporary-savepermanent'), "": (current_anchor_title != "Go Back")? release_locks_by_user_id() : "", "": from_related_articles, "": isUserDoneAnyAction = false, "": window.location.href = after_action
												};														
											dialog_callback(1, $(this), do_below_functions);
										},
										"Don't save": function() {																							
											var do_below_functions = { "": (current_anchor_title != "Go Back")? release_locks_by_user_id() : "", "": from_related_articles, "":isUserDoneAnyAction = false, "": window.location.href = after_action
											};
											dialog_callback(2, $(this), do_below_functions);	
										},
										Cancel: function() {																						
											dialog_callback(3, $(this), '');	
										}
									  }
			  });	
			  $(".ui-widget-content").addClass("save-dialog-box");
			  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
									  
			return false;

		}else{
			if(current_anchor_title != "Go Back"){
				release_locks_by_user_id();			
			}
			window.location.href = after_action
			return false;
		}
	});
	
	////  To related article page  ///
	
	$('.is-template-version-saved-related').click(function(){
		if(isUserDoneAnyAction){		
				//////  Prompt user to save template or not save  ///			
				$("#widget-add-article-confirm").dialog( {
						resizable	: false,
						modal		: true,
						title		: "Do you want to save changes?",
						height		: 250,
						width		: 325,
						buttons		: {									
										"Save": function() {
											var do_below_functions = {
												"" : savePermanent('from-confirm-dialog-saveTemporary-savepermanent'), "": back_to_add_widget_articles()
												};			  												
											dialog_callback(1, $(this), do_below_functions);
										},
										"Don't save": function() {		
											//alert("Dont save template");
											var do_below_functions = { "": back_to_add_widget_articles()
											};
											dialog_callback(2, $(this), do_below_functions);	
										},
										Cancel: function() {										
											dialog_callback(3, $(this), '');	
										}
									  }
			  });	
			  $(".ui-widget-content").addClass("save-dialog-box");
			  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
									  
			return false;

		}
		else
		{
			back_to_add_widget_articles();
			return true;
		}
	});
	
	
	//////////////  change mainsectionCofigId()  ///////
	$('#parent_section').change(function() {
		temp_save_articles("");
		var mainsectionConfigId = $(this).find('option:selected').val();
		$('#mainSectionConfig_Id').val(mainsectionConfigId);		
		//alert($(this).find('option:selected').attr('data-numberOfSubsections'));
		//var number_ofsubConfig = $(this).find('option:selected').attr('data-numberOfSubsections');
		if(widget_style == 3)
		{			
			get_subsections(mainsectionConfigId)		
		}
		else
		{
			//dataTableAjaxReload('');
		}
		
		//alert(mainsectionConfigId);
		var selected_parent_section = $(this).find('option:selected').attr('data-parentsectionid');
		selected_parent_section		= (selected_parent_section != '') ? selected_parent_section : 'all';		
		$('#search_bysection option[value='+ (selected_parent_section)+']').attr('selected', true);
		
		/////  To show the content type of the current tab in Search Dropdown  //////////		
		var show_option = $(this).find('option:selected').attr('data-section_content_type');
		if(show_option != '')
		{
		  $("#search_bytype").find('option:selected').removeAttr("selected");
		  $("#search_bytype").find('option').removeAttr("disabled");
		  if(show_option != 'all')
		  {
			  $('#search_bytype option[value!='+show_option+']').attr('disabled','disabled'); 
		  }
		  $('#search_bytype option[value='+show_option+']').attr('selected', true);
		  controll_sections_by_conten_id();
		}
		
	});
	
	/*$(".close_article").click(function(){
        $(".add_widget").hide();
		
    });*/
	
	if($('#parent_section').find('option:selected').attr('data-numberOfSubsections') > 0)
	{
		get_subsections($('#parent_section').val());	
	}

});

function verify_widget_lock(){	
	var article_lock_obj = {};
	$.ajax({					
			url: admin_url +"/template_designer/lock_widget_articles",
			type: 'post',
			async:false,
			data: "widgetinstance_id="+ widget_instance +"&return_type=json"+"&from_release_article_lock",
			success: function (data) {													
				if(data.locked_user_id == user_id){
					$("#admin_save").css({"pointer-events" : "auto", "opacity" : "inherit" });
					$("#publish_articles_button").css({"pointer-events" : "auto", "opacity" : "inherit" });
				}else{
					$("#admin_save").css({"pointer-events" : "none", "opacity" : "0.5" });
					$("#publish_articles_button").css({"pointer-events" : "none", "opacity" : "0.5" });
				}
				if(data.show_msg == 1){
					show_toastr(data.msg, data.msg_type);
				}
				article_lock_obj = data;
				show_not_published_info(data.is_changes_published);
			},
			error: function (e) {
				alert("Internal sever error");											
				}
		});	
	return article_lock_obj;
}
function show_not_published_info(is_changes_published)
{	
	if(version_id == live_version_id){
		if(is_changes_published != 1){
			$('#not-published-info').text("");
		}else{
			$('#not-published-info').text("Article changes are not published");
		}
	}else{
		$('#not-published-info').text("");
	}
}
function dialog_callback(result, dialog_object, save_function){
	dialog_object.dialog("close");
	$.each(save_function,function(index, function_name){ function_name; });			
	if(result == 3)
	{
		return false;
	}
	else
	{
		return result;
	}
}

function get_subsections(mainsectionConfigId)
{
	
	//alert(mainsectionConfigId);
	//////  For getting Subsections - From InstanceSubsectionConfig
	$.ajax({			
		url: admin_url + "template_designer/get_instanceSubsectionConfig",
		type: 'post',
		async: false,
		data: { "mainsectionConfigId_post" : mainsectionConfigId },				
		beforeSend: function() {
			// setting a timeout					
			$("#loading_msg").html("Please wait, Sub sections are loading...");
			$("#commom_loading").show();
		},
		success: function(data)
		{ 
			//alert(data);
			$("#loading_msg").html("");
			$("#commom_loading").hide();
			
			//console.log(data);			
			$subsection_list = 	'<select id="subsection_config_list" name="subsection_config_list" onchange="changeSubConfigId(this.value)"  class="WidgetSelect" >';
			var result = JSON.parse(data);
			$.each(result,function(){
				//console.log("Subsection custom title: ", this.CustomTitle);
				
				if(this.status == 1)
				{
					//alert(this.status);
					var section_id	 = this.SubSection_ID;
					var section_name = (this.subsection_details['Section_ID']) ?  this.subsection_details['Sectionname'] : this.CustomTitle ;					
					$subsection_list = $subsection_list + '<option value="'+ this.WidgetInstanceSubSection_id +'" data-subSectionId ="' + section_id + '">' + section_name +'</option>';																
				}
			});
			$subsection_list = $subsection_list + '</select>';
			//alert(result.length);
			if(result.length > 0)
			{
				$('#subsection_label').show();
				$('#subsection_config_list_span').show();
				$('#subsection_config_list_span').html($subsection_list);
				$('#subSectionConfig_Id').val($('#subsection_config_list').val());
				
				temp_save_articles("");
				/////  To reload the dataTable  /////
				//dataTableAjaxReload('');
			}
			else
			{
				$('#subsection_label').hide();
				$('#subsection_config_list_span').html("");
			}
			
		},
		complete : function (){
			$("#loading_msg").html("");
			$("#commom_loading").hide();
		}
	});
}

function changeSubConfigId(subconfigId)
{
	temp_save_articles("");
	$('#subSectionConfig_Id').val(subconfigId);
	/////  To reload the dataTable  /////
	//dataTableAjaxReload('');
}

function savePermanent(istemporary)
{	
	
	if(!is_widget_article_locked_by_current_user()){
		return false;		
	}else{
	confirm_string = "";
	success_string = "";
	loading_string = "";
	atleast_select = "";
	var save_url = "";
	var is_published = false;
	if(istemporary == 'saveTemporary')
	{
		save_url = admin_url + "template_designer/add_widget_article/saveTemporary";
	}
	else if(istemporary == 'saveTemporary-savepermanent-publish_articles')
	{
		save_url = admin_url + "template_designer/add_widget_article/saveTemporary-savepermanent-publish_articles/s";
		confirm_string = "Are sure you want to publish?";
		success_string = "Articles published successfully";
		loading_string = "Please wait, Article(s) are processing to publish....";
		atleast_select = "Please select at least one article to publish";
		is_published   = true;
	}
	else
	{
		save_url = admin_url + "template_designer/add_widget_article/saveTemporary-savepermanent/s";
		confirm_string = "Are sure you want to save?";
		success_string = "Articles saved successfully";
		loading_string = "Please wait, Article(s) are processing to save....";
		atleast_select = "Please select at least one article to save";
	}
   var required_values 			= [];
   var widget_instance_id 		= $('#current_widgetinstance').val();
   var instance_mainsection_id 	= $('#mainSectionConfig_Id').val();
   var instance_subsection_id 	= $('#subSectionConfig_Id').val();
   var priority_val_check		= false;
   var priority_val_count		= 0;   
   var checkIds = [];
   table.$('tr').each(function(index,rowhtml){
	
    checked= $('input[type="checkbox"][name^=articles_list]:checked',rowhtml).length;
	newcheckbox_checked = $('input[type="checkbox"][name^=articles_list]:checked',rowhtml);
	if (checked==1){		
	checkIds.push(newcheckbox_checked.val());	
	//priority_val = $('input[type="text"][name^=priority]',rowhtml).val();
	checked_checkbox_val = newcheckbox_checked.val();
	id = checked_checkbox_val;	
	   //var old_image				= document.getElementById('customImage_'+content_id).files[0];;
	   //old_image					= (old_image == '') ?  $('#uploaded_image_'+id).html() : old_image ;
	   //alert(old_image);
	   
	   var old_image				= "";
	   old_image					= (old_image == '') ?  $('#uploaded_image_'+id).html() : old_image ;			
	   var old_image_name			= (old_image == '') ?  $('#uploaded_image_'+id).html() : old_image ;
	   
	   
	   var image_caption 	= $('#custom_image_caption' + id).val();
	   //var image_caption 	= $('#custom_image_caption' + id).html();
		image_caption		= (typeof image_caption === 'undefined') ? '' : image_caption;			
		
		var image_alt 		= $('#custom_image_alt' + id).val();
		image_alt			= (typeof image_alt === 'undefined') ? '' : image_alt;
		
		var physical_name 	= $('#custom_image_name' + id).val();
		physical_name		= (typeof physical_name === 'undefined') ? '' : physical_name;
		
		var old_image_id	= $('#custom_image_id' + id).val();
	   
	   
	   
	   required_values.push({
			instancecontent_id		: $('#instancecontent_id_'+id).val(),
			widget_instance_id 		: widget_instance_id,
			instance_mainsection_id : instance_mainsection_id,
			instance_subsection_id 	: instance_subsection_id,
			custom_title			: $('#title_'+id).val(),
			custom_summary			: $('#summary_'+id).val(),
			article_id				: checked_checkbox_val,
			content_type_id         : $('#contenttypeID'+id).val(), 
			//article_priority		: priority_val,
			article_priority		: 0,
			uploaded_image			: $('#uploaded_image_'+id).val(),
			old_image				: old_image,
			//old_image_name			: $('#old_image_name'+id).val() 
			old_image_name			: old_image_name,
			
			image_caption			: image_caption,
			image_alt				: image_alt,
			physical_name			: physical_name,			
			old_image_id			: old_image_id
			
			
		});
		priority_val_check = true;
		priority_val_count ++;				
	}
   });   
	//var checkbox = $("input[name='articles_list']").serializeArray();	
	var checkbox = checkIds;	
	//alert(add_articles_limit);edit_flag
	//if ((checkbox.length == 0 || checkbox.length < add_articles_limit)) 
	//if (false)	
	if (checkbox.length == 0 && edit_flag == "Add") 
	{ 		
		show_toastr(atleast_select, 3); 
		// cancel submit
		return false;
	} 
	/*else if ((checkbox.length > add_articles_limit)) 
	{ 
		alert('You can select maximum of '+ add_articles_limit +' Article(s), you have selected ' + checkbox.length + ' Article(s)'); 
		// cancel submit
		return false;
	} 
	else if(priority_val_check == false || priority_val_count < add_articles_limit)
	{
		alert('Please select corresponding priorities'); 
		return false;
	}*/
	else 
	{ 
		//console.log(edit_article_status);
		var need_to_confirm = (istemporary != 'from-confirm-dialog-saveTemporary-savepermanent') ? confirm(confirm_string) : true;
		if(need_to_confirm)
		{
			
			    $.ajax({
					url			: save_url,
					type		: 'post',
					async		: false,
					//data		: { "required_values": required_values, "related_content_id" :related_content_id, "menu_id" : pagesection_id, "page_type" : page_type, "version_id":version_id },
					data		: { "required_values": required_values, "related_content_id" :related_content_id, "menu_id" : pagesection_id, "page_type" : page_type, "version_id":version_id, "widget_instance_id": widget_instance_id, "instance_mainsection_id" : instance_mainsection_id, "instance_subsection_id": instance_subsection_id, "live_version_id" : live_version_id },
					beforeSend	: function() {
										// setting a timeout					
										$("#loading_msg").html(loading_string);
										$("#commom_loading").show();
									},
					success		: function(result){ 
										//alert(" Posted values: " + result); 
										$("#commom_loading").hide();
										$("#loading_msg").html("");										
										show_toastr(success_string, 1);
										isUserDoneAnyAction = false;				
										
										if(!is_published){
											/////  To reload the dataTable  /////
											dataTableAjaxReload('');
											show_not_published_info(1);
										}else{
											show_not_published_info(0);
											setTimeout("self.close()", 2000 ) // after 2 seconds
										}
									}			
				});			
			   return false;
			   //window.close();	
		}	  	   
	}
  }
}/////savePermanent()

////  Widget Articles save Temp and view ////
function saveTemp_view()
{	
	savePermanent('saveTemporary');
}

function disablekeys()
{
  //called when key is pressed in textbox
  $('input[name^=priority]').unbind("keypress").keypress(function (e) {
	  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {        
			   return false;
	}	
  });
  
  $('input[name^=priority]').unbind("keyup").keyup(function(){
	current_id = $(this).attr("id");
	if($(this).val() != '')
	{
		
		$("input[type='checkbox'][name='articles_list'][id="+current_id+"]").prop("checked", true);
		show_edit(current_id);
	}
	else
	{
		$("input[type='checkbox'][name='articles_list'][id="+current_id+"]").prop("checked", false);
		show_edit(current_id);
	}		 
  });  
}


$(document).ready(function () {
	
	disablekeys();	
	
	//$('#search_bysection option[value='+ ($('#parent_section').find('option:selected').attr('data-parentsectionid') )+']').attr('selected', true);
	var show_option = $('#parent_section').find('option:selected').attr('data-section_content_type');	
	//alert(show_option);
	if(show_option != '' && typeof show_option !== 'undefined')
	{
	  $('#search_bysection option[value='+ ($('#parent_section').find('option:selected').attr('data-parentsectionid') )+']').attr('selected', true);
	  $("#search_bytype").find('option:selected').removeAttr("selected");
	  $("#search_bytype").find('option').removeAttr("disabled");
	  if(show_option != 'all')
	  {
	 	 $('#search_bytype option[value!='+show_option+']').attr('disabled','disabled');
	  }
	  $('#search_bytype option[value='+show_option+']').attr('selected', true);
	}
	var show_image_size			= '';
	///////////////
	function format ( d ) {		
		$("#example .ui-sortable" ).sortable("disable"); ////  Disable the table row reordering  ////
		
		var imageSizeObjLength 		= supported_image_sizes.length;
		
		if(imageSizeObjLength > 0)
		{	
			var imageSizeObjHighest		= supported_image_sizes[imageSizeObjLength-1].Contentposition ;
			//var article_display_order	= 0;
			var $checked_check_boxes 	= $('input[name=articles_list]:checked');
			var article_display_order 	= -1;
			$checked_check_boxes.each(function(key, obj){
				if(obj.value == d.ContentId)
				{
					article_display_order ++;
					return false;
				}
				else
				{
					article_display_order ++;
				}
			});		
			$.each(parsed, function(key, val){
					if(key == (article_display_order+1))
					{
						show_image_size = key;
					}
					else if(key > (article_display_order+1)){						
						return false;
					}
					else
					{
						show_image_size = key;
					}													
				});
			var available_position 	= show_image_size;
			//console.log(parsed);
			//console.log("article_display_order: " + article_display_order +" available postition: "+ available_position +" image width: " +parsed[available_position].ImageWidth +" image height: " +parsed[available_position].ImageHeight);
			var current_article_image_width = parsed[available_position].ImageWidth;
			var current_article_image_height= parsed[available_position].ImageHeight;			
			var show_image_width_height		= '<p class="supported-image-size">Suported image size for the current widget: <span>' + current_article_image_width + ' X ' +current_article_image_height + '</span></p>';
		}
		else
		{
			var current_article_image_width = '0';
			var current_article_image_height= '0';			
			var show_image_width_height		= '<p class="supported-image-size"></p>';
		}
		
		var show_uploaded_image = "";

		//if($('#uploaded_image_'+d.ContentId).val() != '')
		if($('#custom_image_id'+d.ContentId).val() != '')
		{
			var show_uploaded_image 		= '<img src="'+ base_url + $('#uploaded_image_'+d.ContentId).val() + '" name="customImage_view" id="customImage_' + d.ContentId + '_view" >';
			var upload_button_label 		= 'Change Image';
			var upload_button_label_class 	= 'set_image';
			//var remove_button		= ' &nbsp; <span id="remove_image_span"><button type="button" class="btn btn-primary" onclick="return remove_custom_image(' + d.ContentId + ')" style="margin-left:10px;" >Remove Image</button></span> &nbsp;';			
			
			//var edit_image			= ' &nbsp; <span id="edit_image_span"><a class="BorderRadius3 fileUpload btn btn-primary custom-image-browse" onclick="set_content_id_in_remodal(' + d.ContentId + ', ' + current_article_image_width + ', ' + current_article_image_height + ')" id="edit_article_image" rel="home" href="javascript:void(0);" style="color:#fff; margin-left: 10px !important"  >Edit Image</a></span> &nbsp; ';
			
			var remove_button		= '<span id="remove_image_span"><a class="del_image" onclick="return remove_custom_image(' + d.ContentId + ')" ><i class="fa fa-trash-o"></i></a></span>';			
			
			var edit_image			= '<span id="edit_image_span"><a class="del_image delbtn_border" onclick="set_content_id_in_remodal(' + d.ContentId + ', ' + current_article_image_width + ', ' + current_article_image_height + ')" id="edit_article_image" rel="home" href="javascript:void(0);" style="color:#fff;" ><i class="fa fa-pencil"></i></a></span>';
			
			
			var show_image_div_style = '';						
			var custom_image_caption = $('#custom_image_caption'+d.ContentId).val().replace(/"/g, "&quot;");			
			var custom_image_alt 	 = $('#custom_image_alt'+d.ContentId).val().replace(/"/g, "&quot;");
			var custom_image_name 	 = $('#custom_image_name'+d.ContentId).val().replace(/"/g, "&quot;");
			var custom_image_path	 = '<?php echo image_url.imagelibrary_image_path; ?>' + $('#custom_image_path'+d.ContentId).val();
			var custom_image_id 	 = $('#custom_image_id'+d.ContentId).val();
			var read_only			 = 'readonly="readonly"';
			
		}
		else
		{
			var show_uploaded_image 		= '<img src="'+ base_url + 'images/FrontEnd/images/no-image-old.png" name="customImage_view" id="customImage_' + d.ContentId + '_view" >';
			var upload_button_label 		= 'Browse';
			var upload_button_label_class 	= 'set_image browse-image';
			var remove_button				= '<span id="remove_image_span"></span>';
			var edit_image					= '<span id="edit_image_span"></span>';
			var show_image_div_style 		= 'style="display:none;"';
			var custom_image_caption 		= '';
			var custom_image_alt 	 		= '';
			var custom_image_name 	 		= '';
			var custom_image_path	 		= '';
			var custom_image_id 	 		= '';
			
		}
		//alert(custom_image_caption);
		// width:500% !important;
		//<a class="set_image  BorderRadius3 fileUpload btn btn-primary custom-image-browse" onclick="set_content_id_in_remodal(' + d.ContentId + ', ' + current_article_image_width + ', ' + current_article_image_height + ')" id="home_image_set' + d.ContentId + '"  data-remodal-target="modal1" href="#" style="color:#fff;"  >' + upload_button_label + '</a>
		var image_button_preview = '<span> <a class="'+ upload_button_label_class +'" onclick="set_content_id_in_remodal(' + d.ContentId + ', ' + current_article_image_width + ', ' + current_article_image_height + ')" id="home_image_set' + d.ContentId + '"  data-remodal-target="modal1" href="#" style="color:#fff;"  >' + upload_button_label + '</a></span>'+ edit_image + remove_button + show_image_width_height +' <div class = "ArticleImageContainer ArticleImageContainerId' + d.ContentId + '" '+ show_image_div_style +'><div class="ArticleImageContainer1 widget-img-lib" id="home_image_container' + d.ContentId + '" style="visibility: visible;"><img id="customImage_' + d.ContentId + '_view" src="'+ custom_image_path +'"></div></div>';
		
		var img_caption = '<input type="text" id="home_image_caption' + d.ContentId + '" name="home_image_caption' + d.ContentId + '" value="'+ custom_image_caption + '" class="margin-top-5 WidthFull"  >';
		var img_alt		= '<input type="text" id="home_image_alt' + d.ContentId + '" name="home_image_alt' + d.ContentId + '" value="'+ custom_image_alt + '" class="margin-top-5 WidthFull"   >';
		var img_name	= '<input type="text" id="home_physical_name' + d.ContentId + '" name="home_physical_name' + d.ContentId + '" physical_extension="jpg" value="'+ custom_image_name + '" class="margin-top-5 WidthFull"'+ read_only +'  maxlength="80">';
		
		return '<form action="#" id="form' + d.ContentId + '" method="post" enctype="multipart/form-data" style="background:#cce9f6;"> <span class="close_article close_span"> <button type="button" class="btn-primary btn FloatLeft right-wid"><i class="fa fa-times"></i> Close</button> </span>\
		<table class="add_widget" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; background:#cce9f6;">'+
	 	 '<tr>'+ 
		 '<td></td>'+
		 
		 '</tr>'+
			'<tr>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Custom Title:</span></td>'+
				'<td class="SectionCk"> \
				<input type="hidden" name="instanceId" value="'+ $('#current_widgetinstance').val() + '"> \
				<input type="hidden" name="instanceMainSectionId" value="'+ $('#mainSectionConfig_Id').val() + '"> \
				<input type="hidden" name="instanceSubSectionId" value="'+ $('#subSectionConfig_Id').val() + '"> \
				<input type="hidden" name="content_id" value="' + d.ContentId + '"> <textarea name="EditCustomTitle"  class="ckeditor" >'+$('#title_'+d.ContentId).val()+'</textarea></td>'+
			'</tr>'+
			'<tr>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Custom Summary:</span></td>'+
				'<td class="SectionCk" ><textarea name="Summary" id="txtArticleHeadLine" class="ckeditor" >' + $('#summary_'+d.ContentId).val() + '</textarea></td>'+
			'</tr>'+
			'<tr>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Custom Image:</span></td>'+
				'<td><input type="hidden" name="is_image_from_library' + d.ContentId + '" id="is_image_from_library' + d.ContentId + '" value="1" /> <input type="hidden" name="Image_content_id' + d.ContentId + '" id="Image_content_id' + d.ContentId + '" value="" /><input type="hidden" name="custom_image_old_id' + d.ContentId + '" id="custom_image_old_id' + d.ContentId + '" value="'+ custom_image_id +'" /> <div class="article-custom-image">'+ image_button_preview +'</div></td>'+
			'</tr>'+
			'<tr class = "ArticleImageContainerId' + d.ContentId + '" '+ show_image_div_style +'>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Caption:</span></td>'+
				'<td>' + img_caption +'</td>'+
			'</tr>'+
			'<tr class = "ArticleImageContainerId' + d.ContentId + '" '+ show_image_div_style +'>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Alt:</span></td>'+
				'<td>' + img_alt +'</td>'+
			'</tr>'+
			'<tr class = "ArticleImageContainerId' + d.ContentId + '" '+ show_image_div_style +'>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Name:</span></td>'+
				'<td>' + img_name +'</td>'+
			'</tr>'+
			'<tr>'+
			'<td>&nbsp;</td>'+
				'<td>&nbsp;</td>'+
				'<td><button type="button" class="btn-primary btn FloatLeft right-wid save_custom_details"><i data-original-title="Active" class="fa fa-file-text-o"></i> Save</button> <span class="close_article"> <button type="button" class="btn-primary btn FloatLeft right-wid">Cancel</button> </span></td>'+
			'</tr>'+
		'</table></form>';
	}
	
	table = $('#example').DataTable( {
		/*"oLanguage": {
			"sProcessing" : "<img src='"+base_url +"images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
		},*/		
		"paging": false,
        "ajax": { 
					
					"url"		: dataTableAjaxUrl,
					"type"		: "post",
					"async" 	: false,
					//"data"		: { "widget_instance": widget_instance, "mainSectionConfig_Id": mainSectionConfig_Id, "subSectionConfig_Id": subSectionConfig_Id, "add_articles_limit": add_articles_limit},
					"data" 		: function(d) {		

												var parentsection_data_attr = "";
												var subsection_data_attr 	= "";												
												parentsection_data_attr 	= $('#parent_section').find('option:selected').attr('data-parentSectionId');
												subsection_data_attr 		= $('#subsection_config_list').find('option:selected').attr('data-subSectionId');
												
												//alert(subsection_data_attr + " : " + parentsection_data_attr);
												if(subsection_data_attr && parentsection_data_attr)
												{
													filter_with_section 	= subsection_data_attr;
													subsection_data_attr	= "";
												}
												else
												{
													filter_with_section 	= parentsection_data_attr;
													parentsection_data_attr = "";
												}
												d.search_bytitle		= $('#search_bytitle').val();
												d.search_bycontent		= $('#search_bycontent').find('option:selected').val();
												
												/*if(d.search_bytitle != '')
												{
													d.search_bytype			= 'all';
													d.search_bysection		= 'all';													
												}
												else
												{
													d.search_bytype			= $('#search_bytype').find('option:selected').val();
													d.search_bysection		= $('#search_bysection').find('option:selected').val();
												}*/
												
												//d.search_bytype			= $('#search_bytype').find('option:selected').val();
												d.search_bytype		= (content_type == 7) ? $('#search_bytype option:selected').val() : ((content_type == 2) ? 1 :content_type);
												d.search_bysection		= $('#search_bysection').find('option:selected').val();
												//console.log(d.search_bytype);
												

												
           									d.widget_instance 		= $('#current_widgetinstance').val();
											d.mainSectionConfig_Id 	= $('#mainSectionConfig_Id').val();
											d.subSectionConfig_Id 	= $('#subSectionConfig_Id').val();
											d.add_articles_limit 	= add_articles_limit;
											d.home_value			= home_value;
											d.parent_section_id		= filter_with_section;
											d.content_type			= content_type;
											
											
											d.related_content_id	= related_content_id;
											d.isRelatedColumn_avail	= isRelatedColumn_avail;
											//alert(d.search_bytype + " : " + d.search_bysection + " : " + d.search_bytitle);
											//console.log(d);
											$(window).scrollTop(0);
								        }					
					/*,"success": function(response){
					//alert(response);
					console.log(response);
					//alert($('input[name^=priority]').length);
					//disablekeys();	
				  }*/
				},
        "columns": [
            { "data": "ID" 								},			
            { "data": "Title"							},
            { "data": "Section" 						},
			//{ "data": "Parent Section" 					},
            { "data": "Article Date" 					},
            { "data": "Media" 							},
            { "data": "Related Article" 				},
			{ "data": "Published Date" 					},
            { "data": "Priority" 						},								
			{ "data": "Priority Hidden", "type":"num"	},
            { "data": "Action" 							},
			{ "data": "Imagesize" 						},
			{ "data": "ActionPriority" 					},
			{ "data": "ContentId", "sorting":"desc"		},
			{ "data": "Edit Status" 					},
		
        ],
		//"pageLength": 50,
		//"order": [[ 11, "desc" ],[ 8, "asc" ],[3, "desc"]],
		//"order": [[12, "desc"],[ 11, "desc" ],[ 8, "asc" ]],
		//"order": [[7, "asc"],[12, "desc"]],
		//8 - column is "Hidden Priority" in ASC /////  "order": [[ 8, "asc" ],[4, "desc"]],
		
      	"createdRow": function(row, data, dataIndex){
			 $(row).attr('ID', 'row-' + dataIndex);		
			 //isUserDoneAnyAction = true;	 
			 //alert('adfas');
		  },
		"fnDrawCallback": function() {
			disablekeys();		
			//////  Add / Edit related articles for a relatedContentId  ////////
			$(".show_related_articles").click(function(){
				//alert($(this).attr('data-relatedContentId'));
				var widget_details = '<?php echo json_encode($widget_values); ?>';
				//alert(widget_details);
				var related_this = $(this);
				var scroll_top 		= $(window).scrollTop();
				if(backto_instance_articles == "yes")
				{
					var post_url = admin_url + "template_designer/show_articles/";
					is_related_article = "no";
					from_related_article = "yes";
					
				}
				else
				{
					var post_url = admin_url + "template_designer/add_related_articles/";
					is_related_article = "yes";
					from_related_article = "no";
					//// before submit form add articles to temp table  /////
					temp_save_articles("");
				}
					
				$('#'+related_this.attr('id')).after(
				$('<form />', { action: post_url, method: 'POST', style: "display: none", id: 'addRelatedArticles' }).append(						
					$('<input />', { id: 'scroll_top', name: 'scroll_top', type: 'hidden', value: scroll_top }),	
					$('<input />', { id: 'relatedContentId', name: 'relatedContentId', type: 'hidden', value: related_this.attr('data-relatedContentId') }),
					$('<input />', { id: 'relatedContentName', name: 'relatedContentName', type: 'hidden', value: related_this.attr('data-relatedContentName') }),
					$('<input />', { id: 'is_related_article', name: 'is_related_article', type: 'hidden', value: is_related_article }),			
					$('<input />', { id: 'from_related_article', name: 'from_related_article', type: 'hidden', value: from_related_article }),			
					$('<input />', { id: 'btn_realatedarticle', type: 'submit', value: 'Add Related Article', style: "display: none" }),
					
					
					$('<input />', { id: 'article_widget_instance_id', name: 'article_widget_instance_id', type: 'hidden', value: widget_instance }),
					$('<input />', { id: 'widget_pageid', name: 'widget_pageid', type: 'hidden', value: '<?php echo $widget_pageId; ?>' }),
					$('<input />', { id: 'page_sectionid', name: 'page_sectionid', type: 'hidden', value: '<?php echo $page_sectionid; ?>' }),
					$('<input />', { id: 'page_pagetype', name: 'pagetype', type: 'hidden', value: '<?php echo $page_type; ?>' }),
					$('<input />', { id: 'container_values', name: 'container_values', type: 'hidden', value: '<?php echo $container_class; ?>' }),
					$('<input />', { id: 'top_div_class', name: 'top_div_class', type: 'hidden', value: '<?php echo $top_div_class; ?>' }),
					$('<input />', { id: 'home_value', name: 'home_value', type: 'hidden', value: home_value }),			
					$('<input />', { id: 'scroll_top', name: 'scroll_top', type: 'hidden', value: scroll_top }),
					$('<input />', { id: 'widget_values', name: 'widget_values', type: 'hidden', value: widget_details }),
					$('<input />', { id: 'parent_section', name: 'parent_section', type: 'hidden', value: $('#parent_section').find('option:selected').attr('data-parentsectionid') }),
					$('<input />', { id: 'version_id', name: 'version_id', type: 'hidden', value: version_id }),
					$('<input />', { id: 'live_version_id', name: 'live_version_id', type: 'hidden', value: live_version_id }),
					$('<input />', { id: 'subsection_config_list', name: 'subsection_config_list', type: 'hidden', value: $('#subsection_config_list').find('option:selected').attr('data-subSectionId')})
				));	
				///////  Submit the form here  /////
				$('#addRelatedArticles').submit();
			}); 	
			/////  End of Add / Edit related articles  ///// 
		},

    });	
	table.rowReordering({
	   fnUpdateCallback: function(row){
		  //console.log('Row has been reordered', row);
		  isUserDoneAnyAction = true;	 
	   }
	});

	////// Hidden priority column is hide - column Number starts from 0
	//table.column( 0 ).visible( false );		/////  Row increment Id 	
	table.column( 3 ).visible( false );		/////  Article Modified Date 	 
	table.column( 5 ).visible( false );	/////  Related Article 	
	//table.column( 7 ).visible( false );	/////  Priority checkbox
	table.column( 8 ).visible( false );		////   Hidden priority 
	table.column( 10 ).visible( false );	////   Image size
	table.column( 11 ).visible( false ); 	////   Action Priority
	table.column( 12 ).visible( false ); 	////   Content Id	
	table.column( 13 ).visible( false ); 	////   Edit Status
	
	if(isRelatedColumn_avail == 'n')
	{
		table.column( 5 ).visible( false );	/////  Related Article 	 
	}

	temp_article_list_obj = $('input[id^="temp_article_list"]');
	$.each(temp_article_list_obj, function( index, value ) {
	  //alert( index + ": " + $('[id^=temp_article_list]').eq(index).val() );
	  temp_article_list = $('[id^=temp_article_list]').eq(index).val()
	});
	//alert(temp_article_list);
	
	
	
				
    // Add event listener for opening and closing details
    $('#example tbody').on('click', '.widget_toggle', function () {
		var scrolltop_value = $(window).scrollTop();

		var tr = $(this).closest('tr');
        var row = table.row( tr );	 	
		//alert($(this).attr('id'));
		var current_object = $(this);
		/////// if any child row created, remove all child rows of class having custom-row-show ///		
 		$('.custom-row-show').remove();        
		if ( row.child.isShown() ) 
		{
            // This row is already open - close it
            row.child.hide();						
            //tr.removeClass('shown');			
			$('tr.even').removeClass('shown');
			$('tr.odd').removeClass('shown');
			$("#example .ui-sortable" ).sortable("enable");
        }
        else {
            // Open this row
			//row.child();
			
			$('tr.even').removeClass('shown');
			$('tr.odd').removeClass('shown');
			
			
			
            row.child( format(row.data()) ).show();			           
			tr.addClass('shown');	
			
			var current_article_id 	= $(this).attr('id').split('edit_');
			current_article_id		= current_article_id[1];
			
			var ImageIndex 		= $("#home_image_gallery_id" + current_article_id).val();					
			var content_id 		= $('#custom_image_id' + current_article_id).val();			
			if(ImageIndex != '' && content_id != ''){	
				var image_caption 	= $('#custom_image_caption' + current_article_id).val();
				var image_alt 		= $('#custom_image_alt' + current_article_id).val();
				var image_path 		= $('#custom_image_path' + current_article_id + '_view').val();
				var content_id 		= $('#custom_image_id' + current_article_id).val();			
				var temporary_imageid = ImageIndex;
				
				var widget_instance_id 			= $('#current_widgetinstance').val();
				var instance_mainsection_id 	= $('#mainSectionConfig_Id').val();
				var instance_subsection_id 		= $('#subSectionConfig_Id').val();
				
				ImageData = "alt="+image_alt+"&caption="+image_caption+"&date=''&height=''&width=''&size=''&path="+image_path+"&content_id="+content_id+"&contentType=1&article_id= " + current_article_id + "&instance_id= " + widget_instance_id + "&mainSectionConfig_id= " + instance_mainsection_id + "&tempImageIndex="+ temporary_imageid;
				//console.log(ImageData);
				insertImageIntocustomTempToEdit(ImageData, true, current_article_id);
			}
			
			
			$('.shown').closest('tr').next('tr').addClass("custom-row-show");
			$(".close_article").click(function(){								
				$('#home_image_set'+ current_article_id).text('Browse');
				$('#home_image_set'+ current_article_id).addClass('browse-image');
				$('#edit_image_span').hide();
				$('#remove_image_span').hide();
				current_object.trigger('click');
				//$('.shown').closest('tr').next('tr').addClass("custom-row-show").remove();
				$("#example .ui-sortable" ).sortable("enable");
				$(window).scrollTop(scrolltop_value);
			// This row is already open - close it


			});	
		
		////////  Save the Text editor content (Custom Title, Custom Summary) 	//////
		$(".save_custom_details").on("click", function (e) {
						
			var content_id 		= $(this).closest('form').find("input[name='content_id']").val();			
			var tempContent_id	= $('#Image_content_id' + content_id).val();
			var elem 			= $(".save_custom_details");
			var is_image_from_libarary = $("#is_image_from_library"+ content_id).val();
			
			//if(tempContent_id != '')
			if(tempContent_id != '' && !remove_image)			
			{
				/*if(is_image_from_libarary == '')
				{*/
					if( $("#customImage_" + content_id + "_view").attr('src') != '' ) {
						postdata = "physical_name="+$('#home_physical_name'+ content_id).val().trim()+'.'+$('#home_physical_name'+ content_id).attr('physical_extension')+"&temp_id="+tempContent_id +"&is_image_from_libarary="+ is_image_from_libarary +"&caption="+$("#home_image_caption"+ content_id).val()+"&alt="+$("#home_image_alt"+ content_id).val();
						
						$("#loading_msg").html("Please wait...");
						$("#commom_loading").show();
						$.ajax({
							url: admin_url+"template_designer/check_custom_image_name", // Url to which the request is send
							type: "POST",             // Type of request to be send, called as method
							data:  postdata,
							dataType: "json",
							async: false, 							
							success: function(data)
							{
								$("#loading_msg").html("");
								$("#commom_loading").hide();
								if(data.status == 'false') {
									show_toastr("Image name already exists", 2);
									$("#home_physical_name"+content_id).attr("readonly", false);
									return false;							
								}
								else
								{
									saveCustomDetails(elem);
								}
							},
							error: function(){
									$("#loading_msg").html("");
									$("#commom_loading").hide();
									$("#example .ui-sortable" ).sortable("enable");
							}
							
						});
						
					}
				/*}
				else
				{
					saveCustomDetails(elem);
				}*/
			}
			else
			{
				saveCustomDetails(elem);
			}
		   	
		});				
		
		 
		
		CKEDITOR.replace( 'EditCustomTitle',
		{
			toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'TextColor', 'Italic' ] } ],
			forcePasteAsPlainText :true
		});
		
		CKEDITOR.replace( 'txtArticleHeadLine',
		{
			toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'TextColor', 'Italic' ] } ],
			forcePasteAsPlainText :true
		});
		
		$('.ckeditor' ).ckeditor();
			//tr.fadeIn('slow');
        }
    });
	
	
	
	//////////////
	
	
	
});
</script> 
<!--<script type="text/javascript" src="https://mpryvkin.github.io/jquery-datatables-row-reordering/1.2.3/jquery.dataTables.rowReordering.js"></script> -->
<script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery.dataTables.rowReordering.js"></script>

<div class="Container">
  <div class="BodyWhiteBG">
    <div class="BodyHeadBg Overflow clear">
      <div class="FloatLeft BreadCrumbsWrapper widget-add-articles ">
        <div class="breadcrumbs"><p><?php echo $rootstructures; ?></p></div>
        <?php if(@$related_content_id == 0){ ?>
        <h2>Add article(s) to '<?php echo (@$widget_instance_details['CustomTitle'] == '') ? @$widget_details['widgetName'] : @$widget_instance_details['CustomTitle']; ?>' Widget </h2>
        <?php 
							$is_display = 'style="display:block"'; 
							$go_back = 'href="#" back_location="'.base_url().folder_name.'/template_designer/backto/?qry='.$widget_pageId.'-'.$scroll_top.'-'.$page_sectionid.'-'.$page_type.'-'.$widget_instance.'-'.$version_id.'"';
							$is_template_version_saved = "is-template-version-saved";
							$related_article_space = "";
							//$related_article_save_button = '';							
						} 

					if(@$related_content_id != 0){ ?>
        <h2>Add related article(s) to '<?php echo substr(strip_tags($this->input->post('relatedContentName')), 0, 40)."..."; ?>' </h2>
        <?php 
							$is_display = 'style="display:none"'; 							
							$go_back = 'href="javascript:;"';
							$is_template_version_saved = "is-template-version-saved-related";
							
							if(@$parentsection_details[0]['Sectionname'] != '')
							{
								$show_section_name = "<h3 class='NormalTitle' > Section Name : ". @$parentsection_details[0]['Sectionname']."</h3>";
							}
							else
							{
								$show_section_name = "<h3> &nbsp; </h3>";
							}
							
							$related_article_space = $show_section_name;
						} 
						//$related_article_save_button = '<button class="btn-primary btn FloatRight" onclick="save_related_articles()" ><i class="fa fa-file-text-o" data-original-title="Active"></i> Temporary Save</button>';
					?>
      </div>
      <span class="FloatRight save-wid save-back save_margin article_save">  
	  <?php 
		$save_button_style = "";
	  if(@$related_content_id == 0){ 
				$lock_label = ($current_locked_user_id != USERID) ? "Release Lock" : (($current_user_lock_status == 1) ? "Unlock" : "Lock");
				$save_button_style = ($current_locked_user_id != USERID) ? 'style="pointer-events:none; opacity:0.5"' : (($current_user_lock_status == 1) ? "" : 'style="pointer-events:none; opacity:0.5"');
				$lock_value = ($current_user_lock_status == 1) ? "1" : "2";
	  ?>		  
			<!-- Release current widget article lock -->
			<button class="btn-primary btn " id="release_widget_article_lock" lock_value="<?php echo $lock_value; ?>" ><?php echo $lock_label; ?></button>
	  <?php }else if(@$related_content_id != 0){ ?>
			<a class="back-top FloatLeft <?php echo $is_template_version_saved; ?>" title="Go Back" <?php echo $go_back; ?> ><i class="fa fa-reply fa-2x"></i> </a> 
	  <?php	
	  } ?>
	  
      <!-- Preview --> 
      <!--<a class="back-top FloatLeft widget-preview" data-remodal-target="modal" href="#modal" id="preview"><i class="fa fa-desktop i_extra"></i></a>--> 
      <!-- End Priview -->
      
      <button class="btn-primary btn " type="submit" onclick="return savePermanent('saveTemporary-savepermanent')" id="admin_save" <?php echo $save_button_style; ?> ><i class="fa fa-file-text-o" data-original-title="Active"></i> Admin View Save</button>
      <?php if($live_version_id != '' && $live_version_id == $version_id){ ?>
      <!--<button class="btn-primary btn FloatRight" type="submit" onclick="return savePermanent('saveTemporary-savepermanent-publish_articles')" ><i class="fa fa fa-flag" data-original-title="Active"></i>Publish</button>-->
      <button title="Publish Article(s)" type="submit" class="btn btn-primary FloatRight i_button" onclick="return savePermanent('saveTemporary-savepermanent-publish_articles')" id="publish_articles_button" <?php echo $save_button_style; ?> ><i style="" class="fa fa fa-flag"></i></button>
      <?php } ?>
      <?php 
				///////  Show Save realted article button here  ///////
				//echo $related_article_save_button; 
			?>
      </span> </div>	 
    <div class="Overflow DropDownWrapper">
	<!-- Template lock info -->
	<div class="lock-status-info ">
		<div class="lock-status-border">
			<span class="FloatLeft">Note: <span id="not-published-info" style="color:#F30" ></span> </span>
			
			<span class="unsaved_template_msg">&nbsp</span>
			<!--<span>Status:&nbsp;&nbsp;&nbsp;<span id="lock_info"></span><span id="divide_pipe"> | </span></span>                -->
			<div class="FloatRight" >
			<span>Status:&nbsp;<span id="lock_info"></span>
			<span  id="locked_by_info" ></span>
			</div>
		</div>
	</div>
	<style>
	.lock-status-info{
		float: left;
		color: #1c69ad;
		font-weight: bold;
		width: 100%;
		text-align: right;
		background: #fff;
		position: fixed;
		width: 1168px;
		z-index: 999;		
		box-shadow: 0 1px 1px 2px #ccc;
		padding: 0 10px;
		margin-left: -15px;
	}
	.lock-status-info p{
		margin-top:0 !important;
	}
	#locked_by_info{
		color: #F30;
	}
	.lock-status-border{
		border: 1px solid #a7d6e9;
		background: #d7eff9;
		padding: 4px;
		margin-top: 12px;
		margin-bottom: 10px;
		text-align:center;
	}
	#divide_pipe{
		color: #1c69ad;
	}
		  
		
	</style>
	<script type="text/javascript">
	function show_lock_info(lock_status, locked_user_name)
	{
		if(lock_status == '1')
		{		
			$('#lock_info').text('Open');
			$('#locked_by_info').html('');
			$('#divide_pipe').hide();
			$('#locked_by_info').hide();	
			
		}	
		else if(lock_status == '2')
		{
			$('#lock_info').text('Locked');
			$('#locked_by_info').html('&nbsp; By:&nbsp;'+ locked_user_name);
			$('#divide_pipe').show();
			$('#locked_by_info').show();
		}
	}
	</script>
   <!-- Template lock info -->
      <div class="previewcontainer wid-main">
      <form name="article_popup_form" id="article_popup_form" action="<?php echo base_url().folder_name."/template_designer/add_widget_article/saveTemporary"; ?>" method="post" enctype="multipart/form-data">
        <!-- Widget Instance : -->
        <input type="hidden" name="current_widgetinstance" id="current_widgetinstance" value="<?php echo $widget_instance; ?>"  />
        <div class="article_table1" style="margin-top:60px;" >
          <?php 
			  //echo $widget_details['widgetStyle'];
			  //print_r($parentsection_details); 
			  //echo count($instance_subsection_details);
			  //echo count($parentsection_details); 			  
			  
			  if($widget_details['widgetStyle'] == 1)
			  {
				  if(@$parentsection_details['Sectionname'] != '')
				  {
					  echo "<h3 class='NormalTitle' ".$is_display."	> Section Name : ". @$parentsection_details['Sectionname']."</h3>";
				  }
				  else
				  {
					  if($related_article_space == '')
					  {
						  //echo "<h3> &nbsp; </h3>";
					  }
					  
				  }
				  echo '<input type="hidden" name="mainSectionConfig_Id" id="mainSectionConfig_Id" value="" />';
				  echo '<select id="parent_section" hidden><option selected data-parentSectionId="'.@$parentsection_details['Section_id'].'">parentsection Id</option> </select>';
				  ?>
          <input type="hidden" name="subSectionConfig_Id" id="subSectionConfig_Id" value="" />
          <?php
			  }
			  else if(count($parentsection_details) > 0  && $widget_details['widgetStyle'] == 2)
			  {

				  //echo $related_article_space;
				  echo '<div class="TabArticleAdd"'.$is_display.' >';
				  echo '<span> Select a section : </span>';
				  //echo '<select id="parent_section" onchange="change_mainsectionCofigId(this.value)">';
				  //print_r($parentsection_details); 
				  echo '<select id="parent_section" class="WidgetSelect">';
				  //$main_inc = 0;
				  foreach($parentsection_details as $main_key => $main_config_value)
				  {
					  if($main_section_config[$main_key]['status'] == 1)
					  {						  	
						$selected = ($this->input->post("parent_section") == @$main_config_value['Section_id'] && $this->input->post("parent_section") != '') ? "selected" : "";						
						$parent_title = ($main_section_config[$main_key]['CustomTitle'] != '') ? $main_section_config[$main_key]['CustomTitle'] : @$main_config_value['Sectionname'];
						echo "<option value='".$main_section_config[$main_key]['WidgetInstanceMainSection_id']."' data-numberOfSubsections ='".count($instance_subsection_details[$main_key])."' data-parentSectionId ='".@$main_config_value['Section_id']."' ".$selected ." data-Section_Content_Type ='".$main_section_config[$main_key]['Section_Content_Type']."' >".$parent_title."</option>";
					  //$main_inc ++;
					  }
				  }
				  echo "</select>";
				  echo '</div>';
				  ?>
          <span id="subsection_config_list_span" name="subsection_config_list_span"></span>
          <input type="hidden" name="mainSectionConfig_Id" id="mainSectionConfig_Id" value="" />
          <input type="hidden" name="subSectionConfig_Id" id="subSectionConfig_Id" value="" />
          <script type="text/javascript">
				  //////  set default value.. /////
				  $('#mainSectionConfig_Id').val($('#parent_section').val());				  
				  </script>
          <?php
			  }
			  else if(count($parentsection_details) > 0  && $widget_details['widgetStyle'] == 3)
			  {
				  //echo $related_article_space;
				  echo '<div class="TabArticleAdd" '.$is_display.'>';
				  echo '<span> Select a section : </span>';
				  //echo '<select id="parent_section" onchange="change_mainsectionCofigId(this.value)">';
				  echo '<select id="parent_section"  class="WidgetSelect">';
				  //$main_inc = 0;
				  foreach($parentsection_details as $main_key => $main_config_value)
				  {
					  if($main_section_config[$main_key]['status'] == 1)
					  {
						  $parent_title = ($main_section_config[$main_key]['CustomTitle'] != '') ? $main_section_config[$main_key]['CustomTitle'] : $main_config_value['Sectionname'];
						  echo "<option value='".$main_section_config[$main_key]['WidgetInstanceMainSection_id']."' data-numberOfSubsections ='".count($instance_subsection_details[$main_key])."' data-parentSectionId ='".$main_config_value['Section_id']."' >".$parent_title."</option>";
						  //$main_inc ++;
					  }
				  }
				  echo "</select>";
  				  echo '<span id="subsection_label" style="display:none;"> Select sub section : </span>';
				  ?>
          <span id="subsection_config_list_span" name="subsection_config_list_span"></span>
          <input type="hidden" name="mainSectionConfig_Id" id="mainSectionConfig_Id" value="" />
          <input type="hidden" name="subSectionConfig_Id" id="subSectionConfig_Id" value="" />
        </div>
        <script type="text/javascript">
				  //////  set default value.. /////
				  $('#mainSectionConfig_Id').val($('#parent_section').val());				  
				  </script>
        <?php
			  }
			  ?>
        <?php //echo $related_article_space;
		//print_r($widget_instance_details); 
		 ?>
        <?php 
			  	//print_r($main_section_config);
				if($widget_details['contentType'] == 7)
				{
					$search_by_type_display = " style='display:block;' ";
				}
				else
				{
					$search_by_type_display = " style='display:none;' ";
				}
				
			  ?> 
        <div class="FloatLeft TableColumn">
          <div class="FloatLeft w2ui-field" <?php echo $search_by_type_display; ?> >
            <select class="controls" name="search_bytype" id="search_bytype" >
             <!-- <option value="" >Search by type</option>
              <option value="all" >All</option>-->
              <?php
			  	   
						foreach($content_type_group as $content_type_details)
						{
							if(strtolower($content_type_details->ContentTypeName) != "image")
							{
								$select_content_type = ($widget_details['contentType'] == $content_type_details->contenttype_id) ? " selected " : "";
								echo '<option value="'.$content_type_details->contenttype_id.'" '.$select_content_type.' >'.$content_type_details->ContentTypeName.'</option>';
							}							
							$content_type_list[$content_type_details->contenttype_id] = $content_type_details->ContentTypeName;
						}
					?>
            </select>
          </div>
          <div class="FloatLeft w2ui-field" >
          <?php
		  
		/*  $sectoin_nam3e = 'வீடியோக்கள்';
		  
echo define_section_content_type($sectoin_nam3e, $content_type_list);
		  exit; */
		   //print_r($content_type_list); 
		  function define_section_content_type($section_name, $content_type_list)
		  {
			  //$content_type_list;
			  
			  $tamil_section_names_list 	= array("Gallery"=>"புகைப்படங்கள்", "Video"=>'வீடியோக்கள்', "Audio"=>"ஆடியோக்கள்", "Resources"=>'Resources'); // Except these sections are Articles 
			  $english_section_name_list 	= array("Gallery"=>'Galleries', "Video"=>'Videos', "Audio"=>'Audios', "Resources"=>'Resources');			  
			  $content_type_name 			= array_search($section_name, $tamil_section_names_list);
			  $content_type_name 			= ($content_type_name != '') ? $content_type_name : array_search($section_name, $english_section_name_list);
			  $content_type_id 				= array_search($content_type_name, $content_type_list);
			  return $content_type_id 		= ($content_type_id == '') ? array_search("Article", $content_type_list) : $content_type_id; 
		  }
		  ?>
            <select class="controls" name="search_bysection" id="search_bysection">
              <!-- <option value="" >Search by section</option> -->
			  <?php $section_type = define_section_content_type('all', $content_type_list); ?>
              <option value="all" section_type = "<?php echo $section_type; ?>" >All</option>
              <?php			  
			  //  If normal widget - WidgetSection_ID
			  $selected_section = "";						
			  $widget_selected_section = $widget_instance_details['WidgetSection_ID'];
						foreach($section_group['categoryList'] as $skey => $sec_values)
						{ 
							if(count(@$sec_values['childCategories'][0])>1)
							{
								$selected_section = ($widget_selected_section == $sec_values['categoryId']) ? ' selected ' : '';								
								$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
								//if(trim($sec_values['categoryName']) == 'வீடியோக்கள்'){ $section_type = 4; }else{  $section_type = 4000; }
								echo '<option value="'. $sec_values['categoryId'] .'" '.$selected_section.' section_type = "'. $section_type .'" class="parent-section"  >'. $sec_values['categoryName'].'</option>' ;
									if(count($sec_values['childCategories'][0])>1)
									{
										foreach($sec_values['childCategories'] as $sub_section)
										{
											$selected_section = ($widget_selected_section == $sub_section['categoryId']) ? ' selected ' : '';
											$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
											echo '<option value="'. $sub_section['categoryId'] .'" '.$selected_section.' section_type = "'. $section_type .'"  > &nbsp;  '. $sub_section['categoryName'] .'</option>';
											//if(count($sec_values['special_section'][$sub_key]) > 0)
												if($sub_section['special_section_count'] > 0)
												{
													foreach($sec_values['special_section'][$sub_section['categoryId']] as $spl_section)
													{		
														$selected_section = ($widget_selected_section == $spl_section['categoryId']) ? ' selected ' : '';												
														$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
														echo '<option value="'. $spl_section['categoryId'] .'" '.$selected_section.' section_type = "'. $section_type .'"  > &nbsp; &nbsp; &nbsp;  '. $spl_section['categoryName'] .'</option>';
													}
												}
										}
									}
								
								//echo '</optgroup>';
							}
							else if(@$sec_values['Section_landing'] != 1 && $sec_values['categoryId'] != 0 || count(@$sec_values['childCategories'][0])==1)
							{
								if(strtolower(trim($sec_values['categoryName'])) != "home" && (trim($sec_values['categoryName'])) != "முகப்பு"){
									$selected_section = ($widget_selected_section == $sec_values['categoryId']) ? ' selected ' : '';
									$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
									echo '<option value="'. $sec_values['categoryId'] .'" '.$selected_section.' section_type = "'. $section_type .'" class="parent-section" >  '. $sec_values['categoryName'].'</option>';
								}
							}							
							
						}
					?>
            </select>
			<style>
			.parent-section{
				color: #933;
				font-size: 16px;
			}
			.article_save .i_button{
				font-size: 20px;
			}
			</style>
          </div>
          <div class="FloatLeft w2ui-field" >
            <select class="controls" name="search_bycontent" id="search_bycontent" onchange="change_to_content_id()">
              <option value="content_title" >Search by title</option>
              <option value="content_id" >Search by content ID</option>
            </select>
          </div>
          <div class="FloatLeft TableColumnSearch">
            <input type="search" name="search_bytitle" id="search_bytitle" placeholder="Search title" class="SearchInput">
          </div>
          <!--<i class="fa fa-search FloatLeft" onclick="search_articles()"></i>-->
          <button class="btn btn-primary" type="button" onclick="search_articles()" >Search</button>
          <button class="btn btn-primary margin-left" type="button" id="clear_search">Clear Search</button>
        </div>
        <table id="example" class="display wid-article-table " cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>S.No</th>
              <th>Title</th>
              <th>Section</th>
              <th>Article Date</th>
              <th>Media</th>
              <th>Related Article</th>
              <th>Publish Date Time</th>
              <th>Display Order</th>
              <th>Priority Hidden</th>
              <th>Action</th>
              <th>Imagesize</th>
              <th>ActionPriority</th>
              <th>ContentId</th>
			  <th>Edit Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        </div>
      </form>
    </div>
    <?php 
$frontend_css = image_url."css/FrontEnd/"; 
$frontend_js  = image_url."js/FrontEnd/";

?>
    <script type="text/javascript">
var current_locked_user_name = '<?php echo $current_locked_user_name; ?>';
var current_user_lock_status = '<?php echo ($current_user_lock_status == 1) ? "2" : (($current_lock_status == 2) ? "2": "1") ; ?>';

$(document).ready(function(){
	
	show_lock_info(current_user_lock_status, current_locked_user_name);
	
	$('#release_widget_article_lock').click(function(){
		var widget_instance_id 		= $('#current_widgetinstance').val();
		var update_lock_status		= $(this).attr("lock_value");
		var lock_label				= $(this).text();
		//if(lock_label == "Unlock"){}
		var article_lock_obj = verify_widget_lock();
		if(article_lock_obj.locked_user_id != user_id && lock_label != "Release Lock"){					
				show_toastr("The article is being added by user '"+ article_lock_obj.locked_user_name +"'. Please wait until the user saves the changes.", 2);			
			$('#release_widget_article_lock').text("Release Lock");
			$('#release_widget_article_lock').attr("lock_value", "2");
			show_lock_info(article_lock_obj.articlelock_status, article_lock_obj.locked_user_name);		
			/////  To reload the dataTable  /////
			//dataTableAjaxReload('');
		}else{		
		//alert(update_lock_status);
		var confirm_text = (lock_label == "Release Lock") ? "Do you want to release lock" : ((update_lock_status == 1) ? "Do you want to Unlock" : "Do you want to Lock");
		if(confirm(confirm_text)){
			$.ajax({
				url			: admin_url+"template_designer/release_widget_article_lock",
				type		: "post",
				data		: {"widgetinstance_id" : widget_instance_id, "update_lock_status" : update_lock_status, "lock_label" : lock_label},
				async		: false,
				dataType	: "json",
				beforeSend	: function() {								
								$("#loading_msg").html("Please wait...");
								$("#commom_loading").show();						
						},
				success		: function(result){ 
								$("#commom_loading").hide();
								$("#loading_msg").html("");											
								if(result.res_status == 1){
									if(update_lock_status == 2){
										$("#admin_save").css({"pointer-events" : "auto", "opacity" : "inherit" });
										$("#publish_articles_button").css({"pointer-events" : "auto", "opacity" : "inherit" });
										$('#release_widget_article_lock').text("Unlock");
										$('#release_widget_article_lock').attr("lock_value", "1");
										show_lock_info(2, result.locked_user_name);
									}else{
										$("#admin_save").css({"pointer-events" : "none", "opacity" : "0.5" });
										$("#publish_articles_button").css({"pointer-events" : "none", "opacity" : "0.5" });
										$('#release_widget_article_lock').text("Lock");
										$('#release_widget_article_lock').attr("lock_value", "2");
										show_lock_info(1, "");
									} 
								}else{
									$("#admin_save").css({"pointer-events" : "none", "opacity" : "0.5" });
									$("#publish_articles_button").css({"pointer-events" : "none", "opacity" : "0.5" });
								}
								
									if(result.show_msg == 1){
										show_toastr(result.msg, result.msg_type);
									}								
								},
				error		: function(){
								$("#commom_loading").hide();
								$("#loading_msg").html("");
							}					
				
			});
		}else{
			if(update_lock_status == 1){
				$("#admin_save").css({"pointer-events" : "auto", "opacity" : "inherit" });
				$("#publish_articles_button").css({"pointer-events" : "auto", "opacity" : "inherit" });
				$('#release_widget_article_lock').text("Unlock");
				$('#release_widget_article_lock').attr("lock_value", "1");
				
			}else{
				
				$("#admin_save").css({"pointer-events" : "none", "opacity" : "0.5" });
				$("#publish_articles_button").css({"pointer-events" : "none", "opacity" : "0.5" });
				$('#release_widget_article_lock').text(lock_label);
				$('#release_widget_article_lock').attr("lock_value", "2");
				
			}
		}
		}
	});

	$('#search_bytitle').keypress(function (e) {
			
			if(e.which == 13) {
				search_articles();
			}
			
		});
		
	$("#clear_search").click(function() {
		
	var search_bysection	= $('#search_bysection').val('');
	var search_bycontent	= $('#search_bycontent').val('content_title');
	var search_bytitle		= $('#search_bytitle').val('');	
		
		temp_save_articles("");
	});

});
var scroll_down_inc;
var reached_last;	
var scroll_up_direction;
$(document).on('open', '.remodal', function () {
	Image_Search();
	$('#browse_image_insert').hide();
	scroll_down_inc = 2;
	reached_last 	= true;	
	scroll_up_direction = false;
	//alert(scroll_down_inc);
});

$(document).on('closed', '.remodal', function () {
	scroll_down_inc = 2;
	reached_last 	= true;
	scroll_up_direction = false;
	//alert(scroll_down_inc);
});



var widget_instance_title = decodeURIComponent('<?php echo ($widget_instance_details['CustomTitle'] == '') ? rawurlencode($widget_details['widgetName']) : rawurlencode($widget_instance_details['CustomTitle']); ?>');
//console.log(widget_instance_title);
$('#preview').click(function(){	
	temp_save_articles("show_temp");	
});

function temp_save_articles(show_temp)
{
	//alert(is_related_article);
	if(is_have_related_article)
	{
		var async_status = false;
	}
	else
	{
		var async_status = true;
	}
   var required_values 			= [];
   var widget_instance_id 		= $('#current_widgetinstance').val();
   var instance_mainsection_id 	= $('#mainSectionConfig_Id').val();
   var instance_subsection_id 	= $('#subSectionConfig_Id').val();
   var priority_val_check		= false;
   var priority_val_count		= 0;  
   var checkIds = [];

   table.$('tr').each(function(index,rowhtml){
   checked= $('input[type="checkbox"][name^=articles_list]:checked',rowhtml).length;
   newcheckbox_checked = $('input[type="checkbox"][name^=articles_list]:checked',rowhtml);
	if (checked==1){	
	 	
		checkIds.push(newcheckbox_checked.val());	
		//priority_val = $('input[type="text"][name^=priority]',rowhtml).val();
		checked_checkbox_val = newcheckbox_checked.val();
		id = checked_checkbox_val;	
	 //$("select[name^=priority]").each(function(i,value)
	 
				//priority_val = $(this).val();
				/*if(priority_val != '')
				{*/
				   //var old_image				= document.getElementById('customImage_'+id).files[0];
				   var old_image				= "";
				   old_image					= (old_image == '') ?  $('#uploaded_image_'+id).html() : old_image ;
				   var old_image_name			= (old_image == '') ?  $('#uploaded_image_'+id).html() : old_image ;
				   
				  	var image_caption 	= $('#custom_image_caption' + id).val();		
					//var image_caption 	= $('#custom_image_caption' + id).html();
				   	image_caption		= (typeof image_caption === 'undefined') ? '' : image_caption;			
					var image_alt 		= $('#custom_image_alt' + id).val();		
					image_alt			= (typeof image_alt === 'undefined') ? '' : image_alt;
					var physical_name 	= $('#custom_image_name' + id).val();		
					physical_name		= (typeof physical_name === 'undefined') ? '' : physical_name;
					var old_image_id	= $('#custom_image_id' + id).val();
				   
				   //alert(old_image);
				   required_values.push({
					    instancecontent_id		: $('#instancecontent_id_'+id).val(),
						widget_instance_id 		: widget_instance_id,
						instance_mainsection_id : instance_mainsection_id,
						instance_subsection_id 	: instance_subsection_id,
						custom_title			: $('#title_'+id).val(),
						custom_summary			: $('#summary_'+id).val(),
						article_id				: checked_checkbox_val, 
						content_type_id         : $('#contenttypeID'+id).val(),
						//article_priority		: priority_val,
						article_priority		: 0,
						uploaded_image			: $('#uploaded_image_'+id).val(),
						old_image				: old_image,
						//old_image_name			: $('#old_image_name'+id).val(),
						old_image_name			: old_image_name,
						modified_date			: $('#modified_date_'+id).val(),
						edit_flag				: edit_flag,
						temp_article_list		: temp_article_list,
						individual				: "",
						
						image_caption			: image_caption,
						image_alt				: image_alt,
						physical_name			: physical_name,
						old_image_id			: old_image_id
						
					});
					priority_val_check = true;
					priority_val_count ++;
					
					//console.log(required_values);
					//console.log( " : " ,rowhtml);
				 /*}
				 else
				 {
					 priority_val_check = false;
				 }*/
			 
	}
   });  
	//var checkbox = $("input[name='articles_list']").serializeArray();	
	var checkbox = checkIds;	
	//alert(add_articles_limit);
	//if ((checkbox.length == 0 || checkbox.length < add_articles_limit)) 
	if (false) 
	{ 
		//alert('Please select '+ add_articles_limit + ' article(s) and select corresponding priorities'); 
		alert('Please select '+ add_articles_limit + ' article(s)'); 
		// cancel submit

		return false;
	} 	
	else 
	{ 
	//console.log(required_values);
		$.ajax({
			url			: admin_url+"template_designer/view_temparticles",
			type		: 'post',
			async		: async_status,
			data		: { "required_values": required_values, "widget_instance_id" : widget_instance_id, "instance_mainsection_id" : instance_mainsection_id,						"instance_subsection_id" : instance_subsection_id, "top_div_class" : top_div_class ,"widget_container_class" : widget_parent_container_class, "widget_instance_title" : widget_instance_title, "widget_values":widget_values, "show_temp":show_temp, "related_content_id" :related_content_id, "version_id":version_id },
			beforeSend	: function() {		
						//$('.remodal-overlay').append("<span id='add_article_process_img' style='color:#fff;' ><img src='"+base_url +"images/admin/loadingroundimage.gif' style='width:40px; height:40px;'> &nbsp; Processing...</span>");				
						$("#loading_msg").html("Please wait, loading....");
						$("#commom_loading").show();
						//$('.remodal').hide();
					},
			success		: function(result){ 
								//$('.remodal-overlay #add_article_process_img').remove();
								$("#commom_loading").hide();
								$("#loading_msg").html("");
								//$('.remodal').addClass("container");
								//$('.remodal').show();
								//$('.remodal').html(result);		
								/////  To reload the dataTable  /////
								dataTableAjaxReload('tempSave');																						
							}			
		});			
	}

}



function search_articles()
{
	var search_bytype		= $('#search_bytype').find('option:selected').val();
	var search_bysection	= $('#search_bysection').find('option:selected').val();
	var search_bycontent	= $('#search_bycontent').find('option:selected').val();
	var search_bytitle		= $('#search_bytitle').val();	
	//alert(search_bytype);
	if(search_bytype != '' || search_bysection != '' || search_bytitle != '')
	{
		//alert(search_bytype);	
		temp_save_articles("");
		/////  To reload the dataTable  /////
		//dataTableAjaxReload('');
	}
	
																							
}

function change_to_content_id()
{
  /*$('#search_bytitle').keypress(function (e) {			
		if(e.which == 13) {
			search_articles();
		}		
	});*/
	
  var search_bycontent_value = $('#search_bycontent').val();
  if(search_bycontent_value == 'content_id')
  { 
	$('#search_bytitle').val('');
	$('#search_bytitle').attr('placeholder', 'Search content ID') 
	//called when key is pressed in textbox
	$('#search_bytitle').unbind("keypress").keypress(function (e) {
		
		if(e.which == 13)
		{
			search_articles();
		}
		
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {        
				   return false;
		}	
		
	  });
  } 
  else
  {
	$('#search_bytitle').val(''); 
	$('#search_bytitle').attr('placeholder', 'Search title')  
	$('#search_bytitle').unbind("keypress").keypress(function (e) {
		if(e.which == 13)
		{
			search_articles();
		}
		  return true;	
	  });
  }
  
}



</script>
    <!--<div class="remodal" data-remodal-id="modal" style="position:relative;"> </div>-->
  </div>
</div>
</div>

<!-- Start Browse or upload Image Module / Popup - Js file - widget-article-custom-image --> 
<script type="text/javascript">
var reached_last	= true;
</script>

<script type="text/javascript" src="<?php echo $widget_article_js."admin_view/template_design/js/widget-article-custom-image.js" ?>" ></script>
<script type="text/javascript">
function set_content_id_in_remodal(content_id_val, image_width, image_height)
{
	//alert(content_id_val+":"+ image_width+":"+ image_height);
	WidgetCustomImage.content_id 			= content_id_val;
	WidgetCustomImage.widget_instance_id 	= widget_instance;
	WidgetCustomImage.mainSection_config_id = $('#mainSectionConfig_Id').val();
	WidgetCustomImage.support_image_width 	= image_width;
	WidgetCustomImage.support_image_height 	= image_height;
}

function insertImageIntocustomTempToEdit(ImageData, is_from_save, current_article_id)
{		

	$.ajax({
			url		: admin_url+"template_designer/Insert_temp_from_image_library",
			type	: "POST",
			data	: ImageData,
			dataType: "json",
			async	: false, 	
			beforeSend: function() {
						$("#loading_msg").html("Please wait...");
						$("#commom_loading").show();
						},
			success	: function(data) {
						$("#loading_msg").html("");
						$("#commom_loading").hide();
						
							//console.log(data);
								//$('#edit_article_image').hide();
								$('#home_image_gallery_id' + current_article_id).val(data.image_id);
								$('#home_image_gallery_id' + current_article_id).attr('rel',data.imagecontent_id);
								$("#customImage_" + current_article_id + "_view").attr('src',data.source);
								$('#home_image_container' + current_article_id).css("visibility", "visible");
								$("#home_image_set").next().show();
								$("#home_image_set").next().next().show();
								$("#home_image_set" + current_article_id).html('Change Image');
								//$("#home_uploaded_image").html('Image Set');
								$("#home_image_set").removeClass('BorderRadius3');										
								$("#home_image_caption" + current_article_id).val(data.caption);											
									var physical_name = data.physical_name;
									physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');											
									$("#home_physical_name"+ current_article_id).val(physical_name);											
									$("#home_physical_name"+ current_article_id).attr('physical_extension',data.physical_extension);
									$("#home_physical_name"+ current_article_id).attr('readonly',true);											
								$("#home_image_alt" + current_article_id).val(data.alt);
								$('#Image_content_id'+ current_article_id).val(data.image_id);
								
								CheckImageContainer();
								$("#LoadingSelectImageLibrary").hide(); 
								$("#is_image_from_library"+ current_article_id).val('1');
								
								
									$('#Image_content_id'+ current_article_id).val(data.image_id);
									//$("#home_physical_name"+ current_article_id).attr('readonly',false);
								
													
					},
					error: function(){
								$("#loading_msg").html("");
								$("#commom_loading").hide();
							}
			}); 				

}
</script>


<div class="remodal" data-remodal-id="modal1" data-remodal-options="hashTracking: false" style="position:relative;">
  <div class="article_popup GalleryPopup ArticlePopup" style="height: 467px;">
    <div class="article_popup1">
      <ul class="article_popup_tabs">
        <li class="active img_upload">From Local System</li>
        <li class="img_browse">From Library</li>
      </ul>
    </div>
    <div class="article_popup2">
      <div class="article_upload">
        <form  name="ImageForm" id="ImageForm" action="<?php echo base_url().folder_name; ?>/template_designer/custom_image_upload" method="POST" enctype="multipart/form-data">
        	<!--<input type="hidden" name="Image_content_id" id="Image_content_id" value="" />-->
          <div class="popup_addfiles">
            <div class="fileUpload btn btn-primary WidthAuto"> <span>+ Select Image</span>
              <input type="file" id="imagelibrary" name="imagelibrary" accept="image/*" class="upload" style="width:100%;">
            </div>
            <!--<div id="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
              Please wait while image is being uploaded </div>-->
              <div class="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo image_url;?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
              Please wait while image is being uploaded </div>
          </div>
        </form>
      </div>
      <div class="GalleryDrag"  id="drop-area"> Drop files anywhere here to upload or click on the "Select Image" button above </div>
      <div class="article_browse">
      <h3>Pick the item to insert</h3>
      <div class="article_browse1">
      		<div class="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo image_url;?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
              Please wait while image is being uploaded </div>
        <div class="article_browse_drop">
          <div class="w2ui-field FloatLeft"> </div>
          
          <input type="text" placeholder="Search" id="search_caption" name="txtBrowserSearch"  class="box-shad1 FloatLeft BrowseInput" />
          <i id="image_search_id" class="fa fa-search FloatLeft BrowseSearch"></i> <!--<a  class="btn btn-primary margin-left-10" id="clear_search" href="javascript:void(0);" style="display:none;">Clear Search</a>--> </div>
        <div class="popup_images transitions-enabled infinite-scroll clearfix"  id="image_lists_id"> </div>
        <?php if(isset($image_library)) { 
                                            $count = 0;
                                            
                                            foreach($image_library as $image) {
                                                $active_class = "";
                                                if($count==0) {
                                                    $first_image_caption 	= $image->ImageCaption;
                                                    $first_image_alt 		= $image->ImageAlt;
                                                    $first_image_date 		= $image->Modifiedon;
                                                    $first_image_pathname	= $image->ImagePhysicalPath;
                                                    $first_image_id 		= $image->content_id;
                                                
                                                    $active_class 			= "active";
                                                }
                         ++$count; } 						 						 
						 }
						 else
						 {
							$first_image_caption 	= "";
							$first_image_alt 		= "";
							$first_image_date 		= "";
							$first_image_pathname	= "";;
							$first_image_id 		= "";;
						
							$active_class 			= "";
						 }
						  ?>
        <nav id="page-nav"> <a href="<?php echo base_url().folder_name; ?>/template_designer/search_image_library_scroll/2"></a> </nav>
      </div>
      
      <div class="article_browse2" style="display:none;">      
        <h4>Image Details</h4>
        	
        <img id="image_path" src="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" />
        <h4 id="image_name">
          <?php echo $first_image_caption; ?>
        </h4>
        <p>Date:<span id="image_date">
          <?php echo $first_image_date; ?>
          </span></p>
        <input type="hidden" value="<?php echo $first_image_id; ?>" data-content_id="<?php echo $first_image_id; ?>" data-image_alt="<?php echo $first_image_alt; ?>" data-image_caption="<?php echo $first_image_caption; ?>"  data-image_date="<?php echo $first_image_date; ?>" data-image_source="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" data-image_path="<?php echo  image_url.imagelibrary_image_path.$first_image_pathname; ?>" id="browse_image_id" name="browse_image_id" />
        <div class="article_browse2_input">
          <label>Image Alt</label> : <span id="textarea_alt"><?php echo $first_image_alt; ?></span>
          <br  />
          <label>Caption</label> : <span id="textarea_caption"><?php echo $first_image_caption; ?></span>        
          
        </div>
      </div>
      
      <div class="FloatRight popup_insert insert-fixed">
        <button type="button" class="btn btn-primary remodal-confirm"id="browse_image_insert"  >Insert</button>
      </div>
      
    </div>
    </div>
    
  </div>
</div>
<!-- End Browse or upload Image Module / Popup --> 

<!-- Mansory & Infinite Scroll Script -->
<script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery.masonry.min.js"></script>
<script type="text/javascript" src="<?php echo $widget_article_js; ?>jquery.infinitescroll.min.js"></script>
<script type="text/javascript">
var jqis = $.noConflict();
$(document).ready(function() {
	//call_infinite_scroll();
	$('.popup_images img').click(function(){
		if($('#image_lists_id').html() != 'No Data'){
			$('#browse_image_insert').show();
		}
		else
		{
			$('#browse_image_insert').hide();
		}
	});
	 
	
	
	jqis(".set_image").click(function(){
			
	 var $container = jqis('.popup_images');
		
		if(jqis.trim($container.html()) == '') {
			
		$container.html('<div class="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url; ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		
				$.ajax({
				url: admin_url+"template_designer/get_image_library_scroll/1", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				dataType: "HTML",
				success: function(data)   // A function to be called if request succeeds
				{
					
					$container.html(data);
					setTimeout(function(){
					//call_mansory();
					},1000);
				}
			});
			
		} else {
			//console.log("test");
			//call_mansory();
		}
	});
	var show_clear_link =  jqis("#search_caption").val();
	if(show_clear_link!=''){
	//jqis("#clear_search").show();
	}
		
		jqis("#image_search_id").click(function() {
			Image_Search();
		});
		
		jqis("#search_caption").keyup(function(e){
			if(e.keyCode == 13){
				Image_Search();
			  }
		});
		
		if(widget_style > 1){			
			controll_sections_by_conten_id('');			
		}
		$('#search_bytype').change(function(){
			controll_sections_by_conten_id('change');
		});  
		
		


});

function controll_sections_by_conten_id(is_changed)
{	
	//var controll_sections_by_content_type = ($('#search_bytype').find('option:selected').val());
	//var controll_sections_by_content_type = ($('#search_bytype option:selected').val());
	var controll_sections_by_content_type = (content_type == 7) ? $('#search_bytype option:selected').val() : ((content_type == 2) ? 1 :content_type);
	//alert(controll_sections_by_content_type);
	var active_option = "";
	if(controll_sections_by_content_type != "")
	{
		if(is_changed != 'change'){
			$('#search_bysection').find('option[value=""]').prop('selected', true); 
		}
		else{
			$('#search_bysection').find('option[value="all"]').prop('selected', true); 
		}
		$("#search_bysection option").each(function()
		{
			var child_records		= $(this).attr('section_type');				
			child_records			= (typeof child_records === 'undefined') ? '' : $(this).attr('section_type'); 			
			if(controll_sections_by_content_type =="1")
			{				
				if((child_records != controll_sections_by_content_type && child_records != ''))
				{				
					$(this).hide();
					$(this).attr("disabled", true); 
				}
				else
				{
					$(this).show();
					$(this).attr("disabled", false);
					
					/* if(active_option == ''){						
						active_option = $(this).val();
						$("#search_bysection").val(active_option);
					} */
				}			
				//$('#search_bysection').find('option[value="all"]').show(); 
				//$('#search_bysection').find('option[value="all"]').attr("disabled", false);
			}			
			else
			{
				//$('#search_bysection').find('option[value="all"]').hide(); 
				//$('#search_bysection').find('option[value="all"]').attr("disabled", true)
				
				if(( child_records != controll_sections_by_content_type && child_records != ''))
				{				
					$(this).hide();
					$(this).attr("disabled", true); 
					
				}
				else
				{
					$(this).show();
					$(this).attr("disabled", false);
					if(widget_style == 1){
						if(active_option == ''){						
							active_option = $(this).val();
							$("#search_bysection").val(active_option);
						}
					}
				}
			}
			
			//$('#search_bysection').find('option[value="all"]').hide(); 
			//$('#search_bysection').find('option[value="all"]').attr("disabled", true)
			
		});	
	}
}
function controll_contentType_by_section()
{
	
	var searched_section_type = $("#search_bysection option:selected").attr("section_type");
	var searched_section_val  = $("#search_bysection option:selected").val();
	$('#search_bytype').find('option[value='+ searched_section_type +']').prop('selected', true); 
	controll_sections_by_conten_id('');
	
	var searched_section_type = $("#search_bysection option:selected").attr("section_type");	
	$('#search_bysection').find('option[value='+ searched_section_val +']').prop('selected', true); 
}
function call_mansory() {
		
	var $container = jqis('.popup_images');
	
		$container.imagesLoaded(function(){
				  $container.masonry({
					itemSelector: '#image_lists_images_id',
					columnWidth: 1
				  });
				}); 
				
}

function call_infinite_scroll() {
	 var $container = jqis('.popup_images');
		
	 $container.infinitescroll({
	  navSelector  : '#page-nav',    // selector for the paged navigation 
	  nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
	  itemSelector : '#image_lists_images_id',
	   binder :  $container ,
	  debug : true,
		  // selector for all items you'll retrieve
	  loading: {
		  
		  finishedMsg: 'No more images to load.',
		  img: '<?php echo image_url; ?>images/admin/loadingimage.gif',
		  msgText: "<em>Loading the next set of images...</em>"
		},
		state: { isDone:false }
	  },
	  // trigger Masonry as a callback
	  function( newElements ) {
		// hide new items while they are loading
		var $newElems = jqis( newElements ).css({ opacity: 0 });
		// ensure that images load before adding to masonry layout
		$newElems.imagesLoaded(function(){
		  // show elems now they're ready
		  $newElems.animate({ opacity: 1 });
		  //console.log("container add");
			$container.masonry( 'appended', $newElems, true );	
		});
	  }
	);
	
}
function ImageExist(image_url){

		var http = new XMLHttpRequest();

		http.open('HEAD', image_url, false);
		http.send();

		return http.status != 404;

}

function Image_Search() {

 var $container = jqis('.popup_images');
 $container.empty();
if(jqis.trim($container.html()) == '') {
$container.html('<div class="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo image_url; ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
}	
	var Caption = jqis("#search_caption").val();
	
	postdata = "Caption="+Caption;
	jqis.ajax({
		url: admin_url+"template_designer/search_image_library",
		type: "POST",
		data: postdata,
		dataType: "json",
		success: function(data){
			
			var Content = '';
			var Count 	= 0;
			var Image_URL = "<?php echo image_url.imagelibrary_image_path
			;?>";
			var active_image_id = null;
			jqis.each(data, function(i, item) {
				if(ImageExist(Image_URL+item.ImagePhysicalPath)) {
					var active_class = "";					
					if(Count == 0) {
						var image_name 	= '';
						image_name		= item.ImagePhysicalPath.split('/');		
						image_name		= image_name[image_name.length-1];
						image_name		= image_name.split('.');
						image_name		= image_name[0];
						
						jqis("#textarea_alt").text(item.ImageAlt);
						jqis("#textarea_caption").text(item.ImageCaption);
						jqis("#image_name").html(image_name);
						jqis("#image_date").html(item.Modifiedon);
						jqis("#image_path").attr('src',Image_URL+item.ImagePhysicalPath);
						jqis("#browse_image_id").val(item.content_id);
						
						jqis("#browse_image_id").val(jqis(this).attr('rel'));
						
						jqis("#browse_image_id").data("image_source",Image_URL+item.ImagePhysicalPath);
						jqis("#browse_image_id").data("content_id",item.content_id);
						jqis("#browse_image_id").data("image_alt",item.ImageAlt);
						jqis("#browse_image_id").data("image_caption",item.ImageCaption);
						jqis("#browse_image_id").data("image_date",item.Modifiedon);
						jqis("#browse_image_id").data("image_path",Image_URL+item.ImagePhysicalPath);
						
						active_class = 'active';							
						active_image_id = item;
					}
					else
					{
						active_class = '';
					}
					Content +='<img id="image_lists_images_id" data-content_id="'+item.content_id+'"  data-image_caption="'+item.ImageCaption+'" data-image_alt="'+item.ImageAlt+'"  data-image_date="'+item.Modifiedon+'" data-image_source="'+Image_URL+item.ImagePhysicalPath+'"  src="'+Image_URL+item.ImagePhysicalPath+'" class="'+ active_class +'" />';
					Count++;
				}
			});
			if(Content != "") {
				$('.article_browse2').show();
				jqis('.popup_images').html(Content);
				$('#browse_image_id').val(active_image_id.content_id);
				$("#browse_image_id").attr("data-image_source", Image_URL + active_image_id.ImagePhysicalPath);
				$("#browse_image_id").attr("data-content_id", active_image_id.content_id);
				$("#browse_image_id").attr("data-image_alt", active_image_id.ImageAlt);
				$("#browse_image_id").attr("data-image_caption", active_image_id.ImageCaption);
				$("#browse_image_id").attr("data-image_date", active_image_id.Modifiedon);
				$("#browse_image_id").attr("data-image_path", Image_URL + active_image_id.ImagePhysicalPath);
			} else {
			jqis("#image_lists_id").html("No Data");
			$('.article_browse2').hide();
			}
			
			
			
			//jqis('.popup_images').masonry('reload');
			//jqis('.popup_images').infinitescroll('destroy'); // Destroy
			
			// Undestroy
			/*jqis('.popup_images').infinitescroll({ 				
				state: {                                              
						isDestroyed: false,
						isDone: false                           
				}
			});*/
			//console.log("destory");	
			//jqis('.popup_images').infinitescroll('bind');
			//jqis('.popup_images').infinitescroll('retrieve');
			//jqis("#clear_search").show(); 
			
			$('.popup_images img').click(function(){
				if($('#image_lists_id').html() != 'No Data'){
					$('#browse_image_insert').show();
				}
				else
				{
					$('#browse_image_insert').hide();
				}
			});
		}
	});
}


function validate_image_name(content_id) {

	
	var home_bool = true;

	if( $("#customImage_" + content_id + "_view").attr('src') != '' ) {		
		postdata = "physical_name="+$('#home_physical_name'+ content_id).val().trim()+'.'+$('#home_physical_name'+ content_id).attr('physical_extension')+"&temp_id="+$("#home_image_gallery_id" + content_id).val();
		$.ajax({
			url: admin_url+"template_designer/check_custom_image_name", // Url to which the request is send
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

}
			
function saveCustomDetails(elem)
{
	if(!is_widget_article_locked_by_current_user()){
		return false;
	}else{	
   CKEDITOR.instances.EditCustomTitle.updateElement();	
   CKEDITOR.instances.txtArticleHeadLine.updateElement();	
   //var confirm_text = (remove_image) ? "Are you sure you want to remove image?" : "Are you sure you want to save?";
   var confirm_text = "Are you sure you want to save?";
   if(confirm(confirm_text))
	  {	
		var instanceId 				= elem.closest('form').find("input[name='instanceId']").val();
		var instanceMainSectionId 	= elem.closest('form').find("input[name='instanceMainSectionId']").val();
		var instanceSubSectionId 	= elem.closest('form').find("input[name='instanceSubSectionId']").val();
		var content_id 				= elem.closest('form').find("input[name='content_id']").val();
		var Title 					= elem.closest('form').find("textarea[name='EditCustomTitle']").val();
		var Summary 				= elem.closest('form').find("textarea[name='Summary']").val();
		//var uploaded_image			= elem.closest('form').find("input[name='customImage']");
		//alert($("input[type='checkbox'][name='articles_list'][id="+content_id+"]").is(":checked"));
		//alert(Title);
		var is_checked				= $("input[type='checkbox'][name='articles_list'][id="+content_id+"]").is(":checked");
		//var priority				= $("select[name^='priority'][id="+content_id+"]").val();
		var priority				= $("label[name^='priority'][id="+content_id+"]").text();
		//var old_image				= document.getElementById('customImage_'+content_id).files[0];
		//old_image					= (old_image == '') ? old_image : $('#uploaded_image_'+content_id).html();
		
		//var old_image_name			= $('#image_path_name_'+content_id).val();
		//alert(old_image + " " +$('#uploaded_image_' + content_id).html());
		var instancecontent_id 		= $('#instancecontent_id_'+content_id).val();			
		//alert(priority);
		var contentType_id = $('#contenttypeID'+content_id).val();	
		if(remove_image)
		{
			var image_caption 	= '';
			var image_alt 		= '';				
			var physical_name 	= '';				
			var temp_image_id	= '';				
			var old_image_id	= '';
		}
		else
		{			
			var image_caption 	= $('#home_image_caption' + content_id).val();
			image_caption		= (typeof image_caption === 'undefined') ? '' : image_caption;			
			
			var image_alt 		= $('#home_image_alt' + content_id).val();
			image_alt			= (typeof image_alt === 'undefined') ? '' : image_alt;
			
			var physical_name 	= $('#home_physical_name' + content_id).val();
			physical_name		= (typeof physical_name === 'undefined') ? '' : physical_name;
			
			var temp_image_id	= $('#Image_content_id' + content_id).val();
			temp_image_id		= (typeof temp_image_id === 'undefined') ? '' : temp_image_id;
			
			var old_image_id	= $('#custom_image_old_id' + content_id).val();
		}
		
		var fd = new FormData();
		//fd.append("uploaded_image", document.getElementById('customImage_'+content_id).files[0]);
		//fd.append("old_image", old_image);
		//fd.append("old_image_name", old_image_name);						
		fd.append("instanceId", instanceId);
		fd.append("instanceMainSectionId", instanceMainSectionId);
		fd.append("content_id", content_id);
		fd.append("content_type_id", contentType_id);
		fd.append("Title", Title);
		fd.append("Summary", Summary);
		fd.append("instancecontent_id", instancecontent_id);
		fd.append("checked_status", is_checked);
		fd.append("article_priority", priority);
		fd.append("related_content_id", related_content_id);			
		fd.append("version_id", version_id);
		
		fd.append("image_caption", image_caption);
		fd.append("image_alt", image_alt);
		fd.append("physical_name", physical_name);
		fd.append("temp_image_id", temp_image_id);
		fd.append("old_image_id", old_image_id);
		
		//console.log("fd: " +fd);	
		//alert(old_image_name);				
		$.ajax({
				url: admin_url + "template_designer/widgetarticle_customdetails",
				type: 'post',
				processData: false,
				contentType: false,
				async: false,
				//data: { "instanceId": instanceId, "instanceMainSectionId": instanceMainSectionId, "content_id":content_id, "Title":Title, "Summary": Summary, "instancecontent_id":instancecontent_id, "image": fd},
				data:  fd,
				beforeSend: function() {
					// setting a timeout					
					$("#loading_msg").html("Please wait, Custom details are processing...");
					$("#commom_loading").show();
				},
				success: function(data)
				{ 
					//console.log("widgetarticle_customdetails: ",data);						
					//$("#commom_loading").hide();
					//$("#loading_msg").html("");
					var result= $.parseJSON( data);
					//alert(result.message);
					//show_toastr(result.message, 1);
					
					if(result.inserted_id != ''){
						/////  Add or insert the inserted_instancecontentId into the hidden instancecontent_id text box
						$('#instancecontent_id_'+content_id).val(result.inserted_id);					
					}
					if(result.status == true){							
						//elem.closest('form').find("textarea[name='Title']").val(Title);
						$('#title_'+content_id).html(Title);
						$('#summary_'+content_id).html(Summary);
						
						$('#custom_image_caption' + content_id).val(image_caption);
						$('#custom_image_alt' + content_id).val(image_alt);
						$('#custom_image_name' + content_id).val(physical_name);
						$('#custom_image_id' + content_id).val(result.image_id);
						
						// Save all selected checkboxes						
						savePermanent('from-confirm-dialog-saveTemporary-savepermanent');
					}
				},
				error: function()
						{
							$("#commom_loading").hide();
							$("#loading_msg").html("");
						}
				
				
			});						
			/////  To reload the dataTable  /////
			//dataTableAjaxReload('');	
			remove_image = false;
			$("#example .ui-sortable" ).sortable("enable");
	}
	else
	{
		remove_image = false;
		return false;
	}		
	}
}
</script>
<!-- Mansory & Infinite Scroll Script -->

<script type="text/javascript">
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
			if(data){ //show_toastr("Locks released", 1); 
			}
			else{ show_toastr("Failed to release locks", 2); }
		},
		error: function (e) {
			$("#commom_loading").hide();
			$("#loading_msg").html("");	
			alert("error");						
		}
	});
}

function dataTableAjaxReload(is_temp_save){
	$('#example').DataTable().ajax.reload();
	if(is_temp_save != 'tempSave'){
		if($('#edit_status').text() != 0 && edit_flag == 'Edit'){
			edit_flag = 'Edit';		
		}
		else if($('#edit_status').text() == 0 && edit_flag == 'Edit'){
			edit_flag = 'Add';		
		}
		else if($('#edit_status').text() != 0 && edit_flag == 'Add'){
			edit_flag = 'Edit';
		}
		else{											
			edit_flag = 'Add';
		}
	}
	else{
		if($('#edit_status').text() != 0 && edit_flag == 'Add'){
			edit_flag = 'Edit';
		}		
	}
}

function is_widget_article_locked_by_current_user()
{
	var article_lock_obj = verify_widget_lock();
	if(article_lock_obj.locked_user_id != user_id){
		//if(article_lock_obj.show_msg == 1){
			show_toastr("The article is being added by user '"+ article_lock_obj.locked_user_name +"'. Please wait until the user saves the changes.", 2);
		//}		
		$('#release_widget_article_lock').text("Release Lock");
		$('#release_widget_article_lock').attr("lock_value", "2");
		show_lock_info(article_lock_obj.articlelock_status, article_lock_obj.locked_user_name);		
		return false;
	}
	else{
		$('#release_widget_article_lock').text("Unlock");
		$('#release_widget_article_lock').attr("lock_value", "1");
		$("#admin_save").css({"pointer-events" : "auto", "opacity" : "inherit" });
		$("#publish_articles_button").css({"pointer-events" : "auto", "opacity" : "inherit" });		
		return true;
	}
}


/*$('#search_bytitle').keypress(function (e) {
//alert('key pressed');
});*/
</script>

<div id="widget-add-article-confirm" ></div>
</div>