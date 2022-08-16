<?php $script_url = image_url; ?>
<?php 
$widget_article_css = $script_url."css/admin/";  ?>

<div class="css_and_js_files">
	<link href="<?php echo $widget_article_css; ?>prabu-styles.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $widget_article_css; ?>bootstrap.min.css" rel="stylesheet" >
	<link href="<?php echo $widget_article_css; ?>jquery-ui.css" rel="stylesheet">
	<link href="<?php echo $widget_article_css; ?>jquery.dataTables.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $script_url; ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";
</script> 
	<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js"></script> 
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>--> 
	<!--<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>--> 
	<!--<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>--> 
	
	<!--<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" type="text/javascript"></script> -->
	<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery-ui.min.js"></script> 
	
	<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script> 
	<script type="text/javascript" src="<?php echo $script_url; ?>js/w2ui-fields-1.0.min.js"></script> 
	<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script> 
	<script type="text/javascript" src="<?php echo $script_url; ?>includes/ckeditor/ckeditor.js"></script> 
	<script type="text/javascript" src="<?php echo $script_url; ?>includes/ckeditor/adapters/jquery.js"></script> 
	<script src="<?php echo $script_url; ?>js/jquery.remodal.js"></script>
	
	<link href="<?php echo $widget_article_css; ?>/template_design/css/widget-articles.css" rel="stylesheet" type="text/css" />
	<style>
.widget-img-lib{
	width:100%;
	margin:0;
}
.widget-img-lib img{
	width: 200px !important;
    height: 150px;
}

.sorting, .sorting_asc
{
background-image:none !important;
cursor:default !important;
}
</style>
</div>
<!--<script src="http://malsup.github.com/jquery.form.js"></script> -->
<?php  
$add_articles_limit = (@$widget_details['minimumContent'] =='') ? 0 : @$widget_details['minimumContent'];
?>
<script type="text/javascript">
var table;
var backto_instance_articles = "no";
var content_type = '<?php echo @$widget_details['contentType']; ?>';
var edit_flag	 = '<?php echo ($edit_flag == 0)? "Add" : "Edit"; ?>';
var temp_article_list		= "";	
var is_have_related_article = '<?php echo ((@$widget_details['isRelatedArticleAvailable'] == "y") ? true : false); ?>';

var related_content_id		= '<?php echo @$related_content_id; ?>';
if(related_content_id != '')
{
	var dataTableAjaxUrl = base_url + "niecpan/section_widget_articles/get_widgetInstance_related_article";
}
else
{
	var dataTableAjaxUrl = base_url + "niecpan/section_widget_articles/get_widgetInstancearticle";
}
//alert(related_content_id);
function show_edit(checkbox_value)
{
	
	$("#example .ui-sortable" ).sortable("enable");
	
	var checked_checkbox_obj = $("input[type='checkbox'][name='articles_list'][id="+checkbox_value+"]");
	if(checked_checkbox_obj.is(":checked"))
	{
		$('#edit_'+checkbox_value).show();	
		
		if(is_have_related_article && related_content_id == 0)
		{			
			$('#add_related_'+checkbox_value).show();	
		}
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
var openFile = function(event, image_id, content_ID) {

	var input = event.target;
	var reader = new FileReader();
	if (!(/\.(gif|jpg|jpeg|tiff|png)$/i).test(input.value)) { 
		alert('You must select an image file only');               
	}
	else
	{
		var u = URL.createObjectURL(image_id.files[0]);
		var img = new Image;
		img.onload = function() {
		
				var reader = new FileReader();
				reader.onload = function(){
					  var dataURL = reader.result;
					  var output = document.getElementById(image_id.id+"_view");
					  output.src = dataURL;
					  
					physical = image_id.files[0].name;
					physical_array = physical.split('.');
					
					physical_name = physical_array[0];
					physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');
					
					$("#physical_name"+content_ID).val(physical_name);
					$("#physical_name"+content_ID).attr('physical_extension',physical_array[1]);
					$("#physical_name"+content_ID).attr('rel','');
					$("#hidden_image_id"+content_ID).val('');
				};
				reader.readAsDataURL(input.files[0]);
		};
			
		img.src = u;
  }// else - image is upload
};

function change_image_name(contentID)
{
		var value = $("#physical_name"+contentID).val();
		result = value.replace(/[^a-zA-Z0-9_-]/g,'');
		$("#physical_name"+contentID).val(result);
}
//////////////////////////////////////

//////  $add_articles_limit  /////
var add_articles_limit 		= '<?php echo $add_articles_limit; ?>';

var widget_values			= '';
//alert($('#current_widgetinstance').val());
var widget_instance 		= '';

$(document).ready(function() {

var widget_instance 		= $('#current_widgetinstance').val();
var mainSectionConfig_Id 	= $('#mainSectionConfig_Id').val();
var subSectionConfig_Id 	= $('#subSectionConfig_Id').val();	

	function format ( d ) {	
	
	$("#example .ui-sortable" ).sortable("disable");
	
	var image_id = $('#imageID_'+d.ID).val();
		var show_uploaded_image = "";
		//if($('#imageID_'+d.ID).val() != '')
		var uploaded_image = $('#uploaded_image_'+d.ID).val();
		//var ImageURL = $('#ImageURL'+d.ID).val();
		//var ImageDetails = $('#ImageDetails'+d.ID).val();
		//var Physical_URL = $('#Physical_URL'+d.ID).val();
		//var Physical_array = $('#Physical_array'+d.ID).val();
		
		if(uploaded_image != '' && uploaded_image != '0' && (typeof(uploaded_image) != "undefined") )
		{
			var Physical_extension = $('#Physical_extension_'+d.ID).val();
			var Physical_name = $('#Physical_name_'+d.ID).val();
			
			var show_uploaded_image = '<img src="'+ image_id + '" name="customImage_view" id="customImage_' + d.ID + '_view" >';
			var upload_button_label = 'Change Image';
			//var remove_button		= ' &nbsp; <button type="button" class="btn btn-primary" onclick="return remove_custom_image(' +d.ID + ')" >Remove Image</button> &nbsp; ';
			 
			//var edit_image			= ' &nbsp; <a class="BorderRadius3 fileUpload btn btn-primary custom-image-browse" onclick="set_content_id_in_remodal(' + d.ID + ')" id="edit_article_image" rel="home" href="javascript:void(0);" style="color:#fff; margin-left: 10px !important"  >Edit Image</a> &nbsp; ';
			
			var remove_button		= '<span id="remove_image_span"><a class="del_image" onclick="return remove_custom_image(' +d.ID + ')" ><i class="fa fa-trash-o"></i></a></span>';			
			
			var edit_image			= '<span id="edit_image_span"><a class="del_image delbtn_border" onclick="set_content_id_in_remodal('+d.ID+');" id="edit_article_image" rel="home" href="javascript:void(0);" style="color:#fff;" ><i class="fa fa-pencil"></i></a></span>';
			
			var upload_button_label_class 	= 'set_image';
			
			var show_image_div_style = '';			
			var custom_image_caption = $('#custom_image_caption'+d.ID).val();
			var custom_image_alt 	 = $('#custom_image_alt'+d.ID).val();
			var custom_image_name 	 = $('#Physical_name'+d.ID).val();
			var custom_image_path	 = '<?php echo image_url.imagelibrary_image_path; ?>' + $('#Physical_path'+d.ID).val();
			var custom_image_id 	 = $('#custom_image_id'+d.ID).val();
			var read_only			 = 'readonly="readonly"';
		}
		else
		{
			var Physical_extension = '';
			var Physical_name = '';
			
			var show_uploaded_image = '<img src="'+ base_url + 'images/FrontEnd/images/no-image-old.png" name="customImage_view" id="customImage_' + d.ID + '_view" >';
			var upload_button_label = 'Browse';
			var upload_button_label_class 	= 'set_image browse-image';
			var remove_button				= '<span id="remove_image_span"></span>';
			var edit_image					= '<span id="edit_image_span"></span>';
			var show_image_div_style 		= 'style="display:none;"';
			var custom_image_caption = '';
			var custom_image_alt 	 = '';
			var custom_image_name 	 = '';
			var custom_image_path	 = '';
			var custom_image_id 	 = '';
		}
		
		//var image_button_preview = '<span><a class="'+ upload_button_label_class +'" onclick="set_content_id_in_remodal(' + d.ID + ')" id="home_image_set' + d.ID + '" href="#modal1" style="color:#fff;"  >' + upload_button_label + '</a></span>'+ edit_image + remove_button +'	<div class = "ArticleImageContainer ArticleImageContainerId' + d.ID + '" '+ show_image_div_style +'><div class="ArticleImageContainer1 widget-img-lib" id="home_image_container' + d.ID + '" style="visibility: visible;"><img id="customImage_' + d.ID + '_view" src="'+ custom_image_path +'"></div></div>';
		
		
		var image_button_preview = '<span> <a class="'+ upload_button_label_class +'" onclick="set_content_id_in_remodal(' + d.ID + ')" id="home_image_set' + d.ID + '"  data-remodal-target="modal1" href="#" style="color:#fff;"  >' + upload_button_label + '</a></span>'+ edit_image + remove_button + ' <div class = "ArticleImageContainer ArticleImageContainerId' + d.ID + '" '+ show_image_div_style +'><div class="ArticleImageContainer1 widget-img-lib" id="home_image_container' + d.ID + '" style="visibility: visible;"><img id="customImage_' + d.ID + '_view" src="'+ custom_image_path +'"></div></div>';
		
		/*var img_caption = '<input type="text" id="home_image_caption' + d.ID + '" name="home_image_caption' + d.ID + '" value="'+ custom_image_caption + '" class="margin-top-5 WidthFull"'+ read_only +'>';
		var img_alt		= '<input type="text" id="home_image_alt' + d.ID + '" name="home_image_alt' + d.ID + '" value="'+ custom_image_alt + '" class="margin-top-5 WidthFull"'+ read_only +'>';
		var img_name	= '<input type="text" id="home_physical_name' + d.ID + '" name="home_physical_name' + d.ID + '" physical_extension="jpg" value="'+ custom_image_name + '" class="margin-top-5 WidthFull"'+ read_only +' >';*/
		
		var img_caption = '<input type="text" id="home_image_caption' + d.ID + '" name="home_image_caption' + d.ID + '" value="'+ custom_image_caption + '" class="margin-top-5 WidthFull"  >';
		var img_alt		= '<input type="text" id="home_image_alt' + d.ID + '" name="home_image_alt' + d.ID + '" value="'+ custom_image_alt + '" class="margin-top-5 WidthFull"   >';
		var img_name	= '<input type="text" id="home_physical_name' + d.ID + '" name="home_physical_name' + d.ID + '" physical_extension="jpg" value="'+ custom_image_name + '" class="margin-top-5 WidthFull"  maxlength="80">';
		
		
		/*return '<form action="#" id="form' + d.ID + '" method="post" enctype="multipart/form-data" style="background:#cce9f6;"> <span class="close_article close_span"> <button type="button" class="btn-primary btn FloatLeft right-wid"><i class="fa fa-times"></i> Close</button> </span>\
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
				<input type="hidden" name="content_id" id="content_id" value="' + d.ID + '"> <input type="hidden" name="section_id_value" value="' + $('#get_sectionId_'+d.ID).val() + '"> <textarea name="Title"  class="ckeditor" >'+$('#title_'+d.ID).val()+'</textarea></td>'+
			'</tr>'+
			'<tr style="display:none;">'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Custom Summary:</span></td>'+
				'<td class="SectionCk" ><textarea name="Summary" id="txtArticleHeadLine" class="ckeditor" >' + $('#summary_'+d.ID).val() + '</textarea></td>'+
			'</tr>'+
			'<tr>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Custom Image:</span></td>'+
				'<td><input type="hidden" name="is_image_from_library' + d.ContentId + '" id="is_image_from_library' + d.ContentId + '" value="1" /><input type="hidden" name="Image_content_id' + d.ContentId + '" id="Image_content_id' + d.ContentId + '" value="" /><input type="hidden" name="custom_image_old_id' + d.ContentId + '" id="custom_image_old_id' + d.ContentId + '" value="'+ custom_image_id +'" /> ' + image_button_preview +'</td>'+
			'</tr>'+
			'<tr>'+
			'<tr>'+
				'<td>Custom Image Name:</td>'+
				'<td><input type="text" onkeyup="change_image_name('+d.ID+')" name="physical_name' + d.ID + '" id="physical_name' + d.ID + '" rel="'+uploaded_image+'" physical_extension="'+$('#Physical_extension'+d.ID).val()+'" value="'+$('#Physical_name'+d.ID).val()+'" /><input type="hidden" id="hidden_image_id'+d.ID+'" name="hidden_image_id'+d.ID+'" value="'+$('#Physical_name'+d.ID).val()+'" /></td>'+
			'</tr>'+
			'<tr>'+ 
				'<td>&nbsp;</td>'+
				'<td>&nbsp;</td>'+
				'<td><button type="button" class="btn-primary btn FloatLeft right-wid save_custom_details"><i data-original-title="Active" class="fa fa-file-text-o"></i> Save</button> '+ remove_button +' </td>'+
			'</tr>'+
		'</table></form>';*/
		
		return '<form action="#" id="form' + d.ID + '" method="post" enctype="multipart/form-data" style="background:#cce9f6;"> <span class="close_article close_span"> <button type="button" class="btn-primary btn FloatLeft right-wid" id="close_button" ><i class="fa fa-times"></i> Close</button> </span>\
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
				<input type="hidden" name="content_id" value="' + d.ID + '"> <input type="hidden" name="section_id_value" value="' + $('#get_sectionId_'+d.ID).val() + '"><textarea name="EditCustomTitle"  class="ckeditor" >'+$('#title_'+d.ID).val()+'</textarea></td>'+
			'</tr>'+
			'<tr style="display:none;">'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Custom Summary:</span></td>'+
				'<td class="SectionCk" ><textarea name="txtArticleHeadLine" id="txtArticleHeadLine" class="ckeditor" >' + $('#summary_'+d.ID).val() + '</textarea></td>'+
			'</tr>'+
			'<tr>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Custom Image:</span></td>'+
				'<td><input type="hidden" name="is_image_from_library' + d.ID + '" id="is_image_from_library' + d.ID + '" value="1" /><input type="hidden" name="Image_content_id' + d.ID + '" id="Image_content_id' + d.ID + '" value="" /><input type="hidden" name="image_library_id' + d.ID + '" id="image_library_id' + d.ID + '" value="" /><input type="hidden" name="custom_image_old_id' + d.ID + '" id="custom_image_old_id' + d.ID + '" value="'+ custom_image_id +'" /><input type="hidden" name="orig_name' + d.ID + '" id="orig_name' + d.ID + '" value="" /><input type="hidden" name="full_path' + d.ID + '" id="full_path' + d.ID + '" value="" /><input type="hidden" name="filename' + d.ID + '" id="filename' + d.ID + '" value="" /> <input type="hidden" name="temp_name' + d.ID + '" id="temp_name' + d.ID + '" value="" /><div class="article-custom-image">'+ image_button_preview +'</div></td>'+
			'</tr>'+
			'<tr class = "ArticleImageContainerId' + d.ID + '" '+ show_image_div_style +'>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Caption:</span></td>'+
				'<td>' + img_caption +'</td>'+
			'</tr>'+
			'<tr class = "ArticleImageContainerId' + d.ID + '" '+ show_image_div_style +'>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Alt:</span></td>'+
				'<td>' + img_alt +'</td>'+
			'</tr>'+
			'<tr class = "ArticleImageContainerId' + d.ID + '" '+ show_image_div_style +'>'+
				'<td>&nbsp;</td>'+
				'<td><span class="FloatRight"> Name:</span></td>'+
				'<td>' + img_name +'</td>'+
			'</tr>'+
			'<tr>'+
			'<td>&nbsp;</td>'+
				'<td>&nbsp;</td>'+
				'<td><button type="button" class="btn-primary btn FloatLeft right-wid save_custom_details"><i data-original-title="Active" class="fa fa-file-text-o"></i> Save</button><span class="close_article"> <button type="button" class="btn-primary btn FloatLeft right-wid">Cancel</button> </span></td>'+
			'</tr>'+
		'</table></form>';
	
	}
	

	table = $('#example').DataTable( {
		"oLanguage": {
			"sProcessing" : "<img src='"+base_url +"images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
		},
		"paging": false,
        "ajax": { 
					
					"url"		: dataTableAjaxUrl,
					"type"		: "post",
					"async" 	: false,
					"data" 		: function(d) {																		
												var parentsection_data_attr = "";
												var subsection_data_attr 	= "";	
												var get_sectionname = '';											
												//parentsection_data_attr 	= $('#parent_section').find('option:selected').attr('data-parentSectionId');
												parentsection_data_attr 	= '';
												subsection_data_attr 		= $('#subsection_config_list').find('option:selected').attr('data-subSectionId');
												
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
												
													d.search_bytype			= $('#search_bytype').find('option:selected').val();
													d.search_bysection		= $('#search_bysection').find('option:selected').val();
													
													if($('#search_bysection').find('option:selected').attr('parent_name'))
														d.get_sectionname		= $('#search_bysection').find('option:selected').attr('parent_name');
													else
														d.get_sectionname		= $('#search_bysection').find('option:selected').text();
											d.add_articles_limit 	= add_articles_limit;
											//d.home_value			= home_value;
											d.parent_section_id		= filter_with_section;
											d.content_type			= content_type;
											d.hidden_widgettype		= $('#hidden_widgettype').val();
								        }					
					
				},
				
        "columns": [
            { "data": "s_no" },			
            { "data": "Title"},
            { "data": "Section" },
            { "data": "Modifiedon" },
            { "data": "Media" },
			// { "data": "Modifiedon" },
            { "data": "Related Article" },
            { "data": "Display order" },								
			{ "data": "Priority Hidden", "type":"num"},
            { "data": "Action" },
			{ "data": "ActionPriority" }
        ],
		//"pageLength": 50,
		//"order": [[ 7, "asc" ],[3, "desc"]],
		"order": [ 7, "asc" ],
		//"aoColumnDefs": [ { "bSortable": false, "aTargets": [0] } ],
		
		//7 - column is "Hidden Priority" in ASC /////  "order": [[ 7, "asc" ],[3, "desc"]],
		"createdRow": function(row, data, dataIndex){
			 $(row).attr('ID', 'row-' + dataIndex);		
			 //isUserDoneAnyAction = true;	 
			 //alert('adfas');
		  },
		  
		"fnDrawCallback": function() {
			disablekeys();		
		},
    });
	
	table.rowReordering();
	
	////// Hidden priority column is hide - column Number starts from 0
	//table.column( 0 ).visible( false );	/////  Content_id 	
	//table.column( 3 ).visible( false );	/////  Article Modified Date 	 
	table.column( 7 ).visible( false );	//// 	Hidden priority 
	table.column( 9 ).visible( false );	//// 	Image size
	table.column( 5 ).visible( false );	//// 	Related article
	
	temp_article_list_obj = $('input[id^="temp_article_list"]');
	$.each(temp_article_list_obj, function( index, value ) {
	  //alert( index + ": " + $('[id^=temp_article_list]').eq(index).val() );
	  temp_article_list = $('[id^=temp_article_list]').eq(index).val()
	});
				
    // Add event listener for opening and closing details
    $('#example tbody').on('click', '.widget_toggle', function () {
		var scrolltop_value = $(window).scrollTop();
		
		var current_object = $(this);
		
        var tr = $(this).closest('tr');
        var row = table.row( tr );	 	
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
			
			//if(ImageIndex != '' && content_id != ''){	
			if(content_id != '' && content_id != 0){	
				var image_alt 		= $('#custom_image_caption' + current_article_id).val();
				var image_caption 	= $('#custom_image_alt' + current_article_id).val();
				//var image_path 		= $('#custom_image_path' + current_article_id + '_view').val();
				var image_path 		= '';
				var content_id 		= $('#custom_image_id' + current_article_id).val();			
				//var temporary_imageid = ImageIndex;
				var temporary_imageid = '';
				
				var widget_instance_id 			= '';
				var instance_mainsection_id 	= '';
				var instance_subsection_id 		= '';
				
				ImageData = "alt="+image_alt+"&caption="+image_caption+"&date=''&height=''&width=''&size=''&path="+image_path+"&content_id="+content_id+"&contentType=1&article_id= " + current_article_id + "&instance_id= " + widget_instance_id + "&mainSectionConfig_id= " + instance_mainsection_id + "&tempImageIndex="+ temporary_imageid;
				//console.log(content_id);
				if(content_id != '' && content_id != 0 && typeof content_id != 'undefined'){	
				insertImageIntocustomTempToEdit(ImageData, true, current_article_id);
				}
			}
			
			$('.shown').closest('tr').next('tr').addClass("custom-row-show");
			$(".close_article").click(function(){
												
				$('#home_image_set'+ current_article_id).text('Browse');
				$('#home_image_set'+ current_article_id).addClass('browse-image');
				$('#edit_image_span').hide();
				$('#remove_image_span').hide();
				current_object.trigger('click');
				$("#example .ui-sortable" ).sortable("enable");
				$(window).scrollTop(scrolltop_value);
			});	
		
		
		
				
		////////  Save the Text editor content (Custom Title, Custom Summary) 	//////
		$(".save_custom_details").on("click", function (e) {
						
			var content_id 		= $(this).closest('form').find("input[name='content_id']").val();			
			var tempContent_id	= $('#Image_content_id' + content_id).val();
			var image_library_id	= $('#image_library_id' + content_id).val();
			var custom_image_old_id	= $('#custom_image_old_id' + content_id).val();
			
			
			var elem 			= $(".save_custom_details");
			var is_image_from_libarary = $("#is_image_from_library"+content_id).val();
			
			if(tempContent_id != '' && !remove_image)
			//if(!remove_image)
			{
					//if((custom_image_old_id == "" && image_library_id == '') || (tempContent_id != '' && image_library_id == ''))
					if( $("#customImage_" + content_id + "_view").attr('src') != '' )
					{
						postdata = "physical_name="+$('#home_physical_name'+ content_id).val()+'.'+$('#home_physical_name'+ content_id).attr('physical_extension')+"&temp_id="+tempContent_id +"&is_image_from_libarary="+ is_image_from_libarary;
						
						$("#loading_msg").html("Please wait...");
						$("#commom_loading").show();
						
						$.ajax({
							url: base_url+"niecpan/section_widget_articles/check_custom_image_name", // Url to which the request is send
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
					else
					{
						saveCustomDetails(elem);
					}
				
			}
			else
			{
				saveCustomDetails(elem);
			}
		   	
		});		
		
		function check_image_name_exists()
			{
				var get_content_id = $("#content_id").val();
				var physical_name= $("#physical_name"+get_content_id).val();
			var home_bool = true;
			if($("#physical_name"+get_content_id).val() != '' && $("#hidden_image_id"+get_content_id).val() == '') {	
			postdata = "physical_name="+physical_name+'.'+$("#physical_name"+get_content_id).attr('physical_extension')+"&temp_id="+$("#physical_name"+get_content_id).attr('rel');
				$.ajax({
					url: base_url+"niecpan/common/check_image_name", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data:  postdata,
					dataType: "json",
					async: false, 
					success: function(data)
					{
						if(data.status == 'false') {
							home_bool = false;
							alert('Image name already exists');
						} 
					}
				});
			}
			return home_bool;
			}
	
		CKEDITOR.replace( 'EditCustomTitle',
		{
			toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'TextColor' ] } ],
			forcePasteAsPlainText :true,
		});
		
		CKEDITOR.replace( 'txtArticleHeadLine',
		{
			toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'TextColor' ] } ],
			forcePasteAsPlainText :true,
		});
		
		$('.ckeditor' ).ckeditor();
			//tr.fadeIn('slow');
        }
    });
	
	//////////////  change mainsectionCofigId()  ///////
	/*$('#parent_section').change(function() {
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
			table.ajax.reload();
		}
		
	});*/
	
	$(".close_article").click(function(){
        $(".add_widget").hide();
	  // search_articles();
    });
	
	/*if($('#parent_section').find('option:selected').attr('data-numberOfSubsections') > 0)
	{
		get_subsections($('#parent_section').val());	
	}*/
	
	$('#search_bytitle').keypress(function (e) {
  if($.trim($('#search_bytitle').val()) != '') {
   var key = e.which;
   if(key == 13)  {
    search_articles();
    }  
  }
 });


	/*var fixHelperModified = function(e, tr) {
		var $originals = tr.children();
		var $helper = tr.clone();
		$helper.children().each(function(index) {
			$(this).width($originals.eq(index).width())
		});
		return $helper;
	},
		updateIndex = function(e, ui) {
			
			var j = 1;
			$("input[name^=articles_list]:checked").each(function () {
				
				var content_id = $(this).val();
						$('td span#change_displayorder'+content_id+'', ui.item.parent()).each(function (i) {
							$(this).text(j);
							//$(this).text(i + 1);
						});
						$('td input[name="priority[]"].updatefield', ui.item.parent()).each(function (i) {
							//$(this).val(i + 1);
							$(this).text(j);
							//console.log( $(this).val(i + 1));
						});
					j++;
			});
			
		};

	$("#example tbody").sortable({
		helper: fixHelperModified,
		 placeholder:'must-have-class',
		stop: updateIndex
	});*/

 

});

/*function get_subsections(mainsectionConfigId)
{
	
	//alert(mainsectionConfigId);
	//////  For getting Subsections - From InstanceSubsectionConfig
	$.ajax({			
		url: base_url + "niecpan/section_widget_articles/get_instanceSubsectionConfig",
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
				
				/////  To reload the dataTable  /////
				table.ajax.reload();
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
	table.ajax.reload();
}*/

function savePermanent(istemporary)
{
	var checkbox_count= $('input[type="checkbox"][name^=articles_list]:checked').length;
	var article_count = $("input[name=selected_article_count]").val();
	
	var article_count_live = $("input[name=selected_article_count_live]").val();
	
	atleast_select = "Plese select at least one article to publish";
	
	
	//if(checkbox_count == 0 && article_count == checkbox_count) 
	if(checkbox_count == 0 && (article_count == checkbox_count && istemporary == "saveTemporary-savepermanent") || ( checkbox_count == 0 && article_count_live == checkbox_count && istemporary == "saveTemporary-savepermanent-publish_articles")) 
	{ 		
		show_toastr(atleast_select, 3); 
		// cancel submit
		return false;
	} 	
	else {
		
		var check_widgettype = '<?php echo $check_widget_type; ?>';
		
		//alert(istemporary);
		confirm_string = "";
		success_string = "";
		loading_string = "";
		var save_url = "";
		if(istemporary == 'saveTemporary')
		{
			save_url = base_url + "niecpan/section_widget_articles/add_widget_article/saveTemporary";
		}
		else if(istemporary == 'saveTemporary-savepermanent-publish_articles')
		{
			save_url = base_url + "niecpan/section_widget_articles/add_widget_article/publish/s";
			confirm_string = "Are sure you want to publish?";
			success_string = "Articles published successfully";
			loading_string = "Please wait, Article(s) are processing to publish....";
		}
		else
		{
			save_url = base_url + "niecpan/section_widget_articles/add_widget_article/saveTemporary-savepermanent/s";
			confirm_string = "Are sure you want to save?";
			success_string = "Articles saved successfully";
			loading_string = "Please wait, Article(s) are processing to save temporarily....";
		}
	   var required_values 			= [];
	   var widget_instance_id 		= $('#current_widgetinstance').val();
	   var instance_mainsection_id 	= $('#mainSectionConfig_Id').val();
	   var instance_subsection_id 	= $('#subSectionConfig_Id').val();
	   var priority_val_check		= false;
	   var priority_val_count		= 0;
	   
	   var total= $('input[type="checkbox"][name^=articles_list]:checked').length;
	   
	   var checkIds = [];
	   table.$('tr').each(function(index,rowhtml){
		checked= $('input[type="checkbox"][name^=articles_list]:checked',rowhtml).length;
		newcheckbox_checked = $('input[type="checkbox"][name^=articles_list]:checked',rowhtml);
		if (checked==1){
				
		checkIds.push(newcheckbox_checked.val());	
		
		
		checked_checkbox_val = newcheckbox_checked.val();
		id = checked_checkbox_val;	
		   var old_image				= "";
		   old_image					= (old_image == '') ?  $('#uploaded_image_'+id).html() : old_image ;
		
		if(article_count > total || article_count < total)
			priority_val = '';
		else
			priority_val = $('#change_displayorder'+id+'').text();
			
		var get_sectionname		=  '';   
	   if($('#search_bysection').find('option:selected').attr('parent_name'))
			get_sectionname		= $('#search_bysection').find('option:selected').attr('parent_name');
		else
			get_sectionname		= $('#search_bysection').find('option:selected').text();
					
		   required_values.push({
				
				section_id : $('#section_'+id).val(),
				custom_title			: $('#title_'+id).val(),
				custom_summary			: $('#summary_'+id).val(),
				article_id				: checked_checkbox_val, 
				article_priority		: priority_val,
				uploaded_image			: $('#uploaded_image_'+id).val(),
				old_image				: old_image,
				old_image_name			: $('#old_image_name'+id).val(),
				checkWigetType  : check_widgettype,
				getSectionID  : $("#search_bysection").val(),
				contentType_id : $('#contenttypeID'+id).val(),
	
				get_sectionname: get_sectionname,
				//img_alt : $('#img_alt'+id).val(),
				img_alt : $('#custom_image_alt'+id).val(),
				img_caption : $('#custom_image_caption'+id).val(),
				img_path : $('#Physical_path'+id).val()
			});
			priority_val_check = true;
			priority_val_count ++;				
		}
		
	   });   
		var checkbox = checkIds;	
		if (false)
		{ 
			alert('Plese select '+ add_articles_limit + ' article(s)'); 
			// cancel submit
			return false;
		} 
		else 
		{ 
			if(confirm(confirm_string))
			{
					$.ajax({
						url			: save_url,
						method		: 'post',
						data		: { "required_values": required_values, "related_content_id" :related_content_id, "checkWigetType": check_widgettype, "getSectionID": $("#search_bysection").val(), },
						beforeSend	: function() {
											// setting a timeout					
											$("#loading_msg").html(loading_string);
											$("#commom_loading").show();
										},
						success		: function(result){ 
											//alert(" Posted values: " + result); 
											$("#commom_loading").hide();
											$("#loading_msg").html("");
											alert(success_string);
																					
											/////  To reload the dataTable  /////
											$('#example').DataTable().ajax.reload();
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
});



</script>

<!--<script type="text/javascript" src="https://mpryvkin.github.io/jquery-datatables-row-reordering/1.2.3/jquery.dataTables.rowReordering.js"></script>-->

	<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.rowReordering.js"></script> 
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper ">
				<div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
				<?php //if(@$related_content_id == 0){ ?>
				<h2><?php echo $title; ?></h2>
				<?php 
							//$is_display = 'style="display:block"'; 
							//$related_article_space = "";
					//	} 
					?>
			</div>
			<span class="FloatRight save-wid save-back save_margin article_save"> 
			
			<!-- Preview --> 
			<!--<a class="back-top FloatLeft widget-preview" data-remodal-target="modal" href="#modal" id="preview"><i class="fa fa-desktop i_extra"></i></a>--> 
			<!-- End Priview -->
			
			<button class="btn-primary btn " type="submit" onclick="return savePermanent('saveTemporary-savepermanent')" ><i class="fa fa-file-text-o" data-original-title="Active"></i>&nbsp;Admin view save</button>
			
			<?php if(count($homepage_id) > 0 && $homepage_id['Version_Id'] != '' && $homepage_id['Page_master_id'] != '') { ?>
			<a class="back-top FloatRight" target="_blank" href="<?php echo base_url(); ?>niecpan/template_designer/load_saved_template/<?php echo $homepage_id['Page_master_id'].'/'.$homepage_id['Version_Id']; ?>" title="Preview"><i class="fa fa-desktop i_extra"></i></a>
			<?php } ?>
			
			<button class="btn-primary btn FloatRight" type="submit" onclick="return savePermanent('saveTemporary-savepermanent-publish_articles')" ><i class="fa fa-file-text-o" data-original-title="Active"></i> Publish</button>
			
			
			</span> </div>
		<div class="Overflow DropDownWrapper">
			<div class="previewcontainer wid-main">
				<form name="article_popup_form" id="article_popup_form" action="<?php echo base_url()."niecpan/section_widget_articles/add_widget_article/saveTemporary"; ?>" method="post" enctype="multipart/form-data">
					<div class="article_table1">
						<div class="FloatLeft TableColumn"> 
							<!--<div class="FloatLeft w2ui-field" style="display:none" >
                  
                  <select class="controls" name="search_bytype" id="search_bytype" >
                    <option value="" >Select by type</option>
                    <option value="all" >All</option>
                    <?php 
						foreach($content_type_group as $content_type_details)
						{
							if(strtolower($content_type_details->ContentTypeName) != "image")
							{
								echo '<option value="'.$content_type_details->contenttype_id.'">'.$content_type_details->ContentTypeName.'</option>';
							}
							
						}
					?>                    
                  </select>
                </div>-->
							
							<div class="FloatLeft w2ui-field" >
								<input type="hidden" name="hidden_widgettype" id="hidden_widgettype" value="<?php echo $check_widget_type; ?>" />
								<?php if($check_widget_type == "jumbo_widget_articles" || $check_widget_type == "related_articles") {  ?>
								<label> Select by section</label>
								<?php } ?>
								<?php /*?><select class="controls" name="search_bysection" id="search_bysection" <?php if($check_widget_type == "jumbo_widget_articles") {  ?> onchange="search_articles();" <?php } ?>>
									<?php if($check_widget_type == "editor_pick_articles" or $check_widget_type == "trending_now_articles") {  ?>
									<!--<option value="" >Search by section</option>-->
									<option value="all" >All</option>
									<?php } ?>
									<?php 						
						foreach($section_group['categoryList'] as $skey => $sec_values)
						{ 
							if(($sec_values['categoryName']!='Galleries' && $sec_values['categoryName']!='Videos' && $sec_values['categoryName']!='Audios' && $check_widget_type != 'jumbo_widget_articles' ) or $check_widget_type == 'jumbo_widget_articles')
							{
								if(count($sec_values['childCategories'][0])>1 && $sec_values['Section_landing'] == 1)
								{
									echo '<option value="'. $sec_values['categoryId'] .'" disabled="disabled">'. $sec_values['categoryName'].'</option>' ;
									
									if(count($sec_values['childCategories'][0])>1)
									{
										foreach($sec_values['childCategories'] as $sub_section)
										{
											echo '<option value="'. $sub_section['categoryId'] .'" parent_name="'.$sec_values['categoryName'].'"> &nbsp;  '. $sub_section['categoryName'] .'</option>';
											if(count($sec_values['special_section']) > 0)
											{
												foreach($sec_values['special_section'] as $spl_section)
												{
													echo '<option value="'. $spl_section['categoryId'] .'" parent_name="'.$sec_values['categoryName'].'"> &nbsp; &nbsp; &nbsp;  '. $spl_section['categoryName'].'</option>';
												}
											}
										}
									}
								}
								else if(count($sec_values['childCategories'][0]==0) && $sec_values['Section_landing'] == 1 && $sec_values['categoryName'] != "Home")
								{
										echo '<option value="'. $sec_values['categoryId'] .'" >'. $sec_values['categoryName'].'</option>' ;
								}
								else if($sec_values['Section_landing'] != 1)
								{
									echo '<option value="'. $sec_values['categoryId'] .'">  '. $sec_values['categoryName'].'</option>';
								}
							}
						}
					?>
								</select><?php */?>
								
								<select class="controls" name="search_bysection" id="search_bysection" <?php if($check_widget_type == "jumbo_widget_articles" || $check_widget_type == "related_articles") {  ?> onchange="search_articles();" <?php } ?>>
									<?php if($check_widget_type == "editor_pick_articles" or $check_widget_type == "trending_now_articles") {  ?>
									<!--<option value="" >Search by section</option>-->
									<option value="all" >All</option>
									<?php } ?>
									<?php if(isset($section_mapping)) { 
										foreach($section_mapping as $mapping) {  
									
											if(($mapping['Sectionname']!='Galleries' && $mapping['Sectionname']!='Videos' && $mapping['Sectionname']!='Audios' && $check_widget_type != 'jumbo_widget_articles' ) or $check_widget_type == 'jumbo_widget_articles' or $check_widget_type == 'related_articles') { ?>
											
											<option style="color:#933;font-size:18px;" value="<?php echo $mapping['Section_id']; ?>" parent_name= "<?php echo $mapping['Sectionname']; ?>" ><?php echo strip_tags($mapping['Sectionname']); ?></option>
											<?php if(!(empty($mapping['sub_section'])) ) { ?>
											<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
											<option  value="<?php echo $sub_mapping['Section_id']; ?>" parent_name= "<?php echo $mapping['Sectionname']; ?>" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
											<?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
											<?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
											<option  <?php if($sub_sub_mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?>    value="<?php echo $sub_sub_mapping['Section_id']; ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
									<?php } } ?>
									<?php  } } ?>
									<?php } }  } ?>
					</select>
							</div>
							<div class="FloatLeft w2ui-field" <?php if($check_widget_type == "jumbo_widget_articles" || $check_widget_type == "related_articles") {  ?>style="display:none" <?php } ?>  >
								<select class="controls" name="search_bycontent" id="search_bycontent" onchange="change_to_content_id()">
									<option value="content_title" >Search by title</option>
									<option value="content_id" >Search by content ID</option>
								</select>
							</div>
							<div class="FloatLeft TableColumnSearch" <?php if($check_widget_type == "jumbo_widget_articles" || $check_widget_type == "related_articles") {  ?>style="display:none" <?php } ?> >
								<input type="search" name="search_bytitle" id="search_bytitle" placeholder="Search title" class="SearchInput">
							</div>
							<?php /*?><i class="fa fa-search FloatLeft" onclick="search_articles()" <?php if($check_widget_type == "jumbo_widget_articles") {  ?>style="display:none" <?php } ?>  ></i><?php */?>
							<button class="btn btn-primary" type="button" onclick="search_articles()" <?php if($check_widget_type == "jumbo_widget_articles" || $check_widget_type == "related_articles") {  ?>style="display:none" <?php } ?> >Search</button>
							<button class="btn btn-primary" type="button" id="clear_search" <?php if($check_widget_type == "jumbo_widget_articles" || $check_widget_type == "related_articles") {  ?>style="display:none" <?php } ?>  >Clear Search</button>
						</div>
						<table id="example"  class="display wid-article-table " cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>S.no</th>
									<th class="width-300">Headline</th>
									<th>Breadcrumb</th>
									<th>Published On</th>
									<th>Media</th>
									<th>Related Article</th>
									<th>Display order</th>
									<th>Priority Hidden</th>
									<th>Action</th>
									<th>ActionPriority</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<?php 
$frontend_css = base_url()."css/FrontEnd/"; 
$frontend_js  = base_url()."js/FrontEnd/";

?>
			<script type="text/javascript">

$(document).ready(function(){
	
		
	$("#clear_search").click(function() {
		
	var search_bysection	= $('#search_bysection').val($('#search_bysection option:first').val());
	var search_bycontent	= $('#search_bycontent').val('content_title');
	var search_bytitle		= $('#search_bytitle').val('');	
		
		temp_save_articles();
	});

});


//var widget_instance_title = '<?php //echo ($widget_instance_details['CustomTitle'] == '') ? $widget_details['widgetName'] : $widget_instance_details['CustomTitle']; ?>';
$('#preview').click(function(){	
	temp_save_articles("show_temp");	
});

$(document).on('open', '.remodal', function () {
	Image_Search('');
	$('#browse_image_insert').hide();
	scroll_down_inc = 2;
	reached_last 	= true;	
	scroll_up_direction = false;
});

$(document).on('closed', '.remodal', function () {
	scroll_down_inc = 2;
	reached_last 	= true;
	scroll_up_direction = false;
	//alert(scroll_down_inc);
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
		priority_val = $('input[type="text"][name^=priority]',rowhtml).val();
		checked_checkbox_val = newcheckbox_checked.val();
		id = checked_checkbox_val;	
	 //$("select[name^=priority]").each(function(i,value)
	 
				//priority_val = $(this).val();
				//if(priority_val != '')
//				{
				   //var old_image				= document.getElementById('customImage_'+id).files[0];
				   var old_image				= "";
				   old_image					= (old_image == '') ?  $('#uploaded_image_'+id).html() : old_image ;
				   //alert(old_image);
				   required_values.push({
					    instancecontent_id		: $('#instancecontent_id_'+id).val(),
						widget_instance_id 		: widget_instance_id,
						instance_mainsection_id : instance_mainsection_id,
						instance_subsection_id 	: instance_subsection_id,
						custom_title			: $('#title_'+id).val(),
						custom_summary			: $('#summary_'+id).val(),
						article_id				: checked_checkbox_val, 
						article_priority		: priority_val,
						uploaded_image			: $('#uploaded_image_'+id).val(),
						old_image				: old_image,
						old_image_name			: $('#old_image_name'+id).val(),
						modified_date			: $('#modified_date_'+id).val(),
						edit_flag				: edit_flag,
						temp_article_list		: temp_article_list,
						individual				: ""
					});
					priority_val_check = true;
					priority_val_count ++;
					//console.log(required_values);
					//console.log( " : " ,rowhtml);
				// }
//				 else
//				 {
//					 priority_val_check = false;
//				 }
			 
	}
   });  
	//var checkbox = $("input[name='articles_list']").serializeArray();	
	var checkbox = checkIds;	
	//alert(add_articles_limit);
	//if ((checkbox.length == 0 || checkbox.length < add_articles_limit)) 
	if (false) 
	{ 
		//alert('Plese select '+ add_articles_limit + ' article(s) and select corresponding priorities'); 
		alert('Plese select '+ add_articles_limit + ' article(s)'); 
		// cancel submit

		return false;
	} 	
	else 
	{ 
	//console.log(required_values);
		$.ajax({
			url			: base_url+"niecpan/section_widget_articles/view_temparticles",
			method		: 'post',
			async		: async_status,
			data		: { "required_values": required_values, "widget_instance_id" : widget_instance_id, "instance_mainsection_id" : instance_mainsection_id,						"instance_subsection_id" : instance_subsection_id,  "show_temp":show_temp, "related_content_id" :related_content_id },
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
								/*$('.remodal').addClass("container");
								$('.remodal').show();
								$('.remodal').html(result);	*/	
								
								/////  To reload the dataTable  /////
								$('#example').DataTable().ajax.reload();																						
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
	
	if(search_bytype != '' || search_bysection != '' || search_bytitle != '')
	{
		//alert(search_bytype);	
		temp_save_articles("");
		/////  To reload the dataTable  /////
		//$('#example').DataTable().ajax.reload();
	}
	
																							
}

function change_to_content_id()
{
  var search_bycontent_value = $('#search_bycontent').val();
  if(search_bycontent_value == 'content_id')
  { 
	$('#search_bytitle').val('');
	$('#search_bytitle').attr('placeholder', 'Search content ID') 
	//called when key is pressed in textbox
	$('#search_bytitle').unbind("keypress").keypress(function (e) {
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
		  return true;	
	  });
  }
  
}

</script>
			<!--<div class="remodal" data-remodal-id="modal" style="position:relative;"> </div>-->
		</div>
	</div>
</div>
<style type="text/css">
.overlay-black{ background-color:#000;}
</style>
<script src="<?php echo $script_url; ?>js/section_widget_article_js.js"></script> 
<script type="text/javascript">
function set_content_id_in_remodal(content_id_val)
{
	WidgetCustomImage.content_id 			= content_id_val;
	WidgetCustomImage.widget_instance_id 	= widget_instance;
	WidgetCustomImage.mainSection_config_id = $('#mainSectionConfig_Id').val();
	
	WidgetCustomImage.support_image_width 	= 600;
	WidgetCustomImage.support_image_height 	= 390;
}

function insertImageIntocustomTempToEdit(ImageData, is_from_save, current_article_id)
{		
	$.ajax({
			url		: base_url+"niecpan/section_widget_articles/Insert_temp_from_image_library",
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
									$("#home_image_caption"+ current_article_id).attr('readonly',true);	
									$("#home_image_alt"+ current_article_id).attr('readonly',true);											
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
				<form  name="ImageForm" id="ImageForm" action="<?php echo base_url(); ?>niecpan/section_widget_articles/custom_image_upload" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="Image_content_id" id="Image_content_id" value="" />
					<div class="popup_addfiles">
						<div class="fileUpload btn btn-primary WidthAuto"> <span>+ Select Image</span>
							<input type="file" id="imagelibrary" name="imagelibrary" accept="image" class="upload" style="width:100%;">
						</div>
						<!--<div id="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
							Please wait while image is being uploaded </div>-->
						<div class="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
              Please wait while image is being uploaded </div>
					</div>
				</form>
			</div>
			<div class="GalleryDrag"  id="drop-area"> Drop files anywhere here to upload or click on the "Select Image" button above </div>
		</div>
		<div class="article_browse">
			<h3>Pick the item to insert</h3>
			<div class="article_browse1">
				<div class="article_browse_drop">
					<div class="w2ui-field FloatLeft"> </div>
					<input type="text" placeholder="Search" id="search_caption" name="txtBrowserSearch"  class="box-shad1 FloatLeft BrowseInput" />
					<i id="image_search_id" class="fa fa-search FloatLeft BrowseSearch"></i> <a  class="btn btn-primary margin-left-10" id="clear_search" href="javascript:void(0);" style="display:none;">Clear Search</a> </div>
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
				<nav id="page-nav"> <a href="<?php echo base_url(); ?>niecpan/section_widget_articles/search_image_library_scroll/2"></a> </nav>
			</div>
			<div class="article_browse2">
				<h4>Image Details</h4>
				<img id="image_path" src="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" />
				<h4 id="image_name"> <?php echo $first_image_caption; ?> </h4>
				<p>Date:<span id="image_date"> <?php echo $first_image_date; ?> </span></p>
				<input type="hidden" value="<?php echo $first_image_id; ?>" data-content_id="<?php echo $first_image_id; ?>" data-image_alt="<?php echo $first_image_alt; ?>" data-image_caption="<?php echo $first_image_caption; ?>"  data-image_date="<?php echo $first_image_date; ?>" data-image_source="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" data-image_path="<?php echo  image_url.imagelibrary_image_path.$first_image_pathname; ?>" id="browse_image_id" name="browse_image_id" />
				<div class="article_browse2_input">
					<label>Image Alt</label>
					: <span id="textarea_alt"><?php echo $first_image_alt; ?></span> <br  />
					<label>Caption</label>
					: <span id="textarea_caption"><?php echo $first_image_caption; ?></span> </div>
			</div>
			<div class="FloatRight popup_insert insert-fixed">
				<button type="button" class="btn btn-primary remodal-confirm"id="browse_image_insert"  >Insert</button>
			</div>
		</div>
	</div>
</div>
<!-- End Browse or upload Image Module / Popup --> 

<!-- Mansory & Infinite Scroll Script --> 
<script src="<?php echo $script_url; ?>js/jquery-1.7.1.min.js"></script> 
<script src="<?php echo $script_url; ?>js/jquery.masonry.min.js"></script> 
<script src="<?php echo $script_url; ?>js/jquery.infinitescroll.min.js"></script> 
<script>
var jqis = $.noConflict();
$(document).ready(function() {
	//call_infinite_scroll();
	$('.popup_images').click(function(){
		$('#browse_image_insert').show();
	});
	 
	
	
	jqis(".set_image").click(function(){
			
	 var $container = jqis('.popup_images');
		
		if(jqis.trim($container.html()) == '') {
			
		$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		
				$.ajax({
				url: base_url+"niecpan/section_widget_articles/get_image_library_scroll/1", // Url to which the request is send
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
	jqis("#clear_search").show();
	}
		jqis("#clear_search").click(function() {
			jqis("#search_caption").val('');
	 var $container = jqis('.popup_images');
			 $container.empty();
		if(jqis.trim($container.html()) == '') {
			
		$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		
				$.ajax({
				url: base_url+"niecpan/section_widget_articles/get_image_library_scroll/1", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				dataType: "HTML",
				success: function(data)   // A function to be called if request succeeds
				{
					
					$container.html(data);
					jqis("#clear_search").hide();
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
		
		
		
		
		jqis("#image_search_id").click(function() {
			var caption = jqis("#search_caption").val();
			Image_Search(caption);
		});
		
		jqis("#search_caption").keyup(function(e){
			if(e.keyCode == 13){
				var caption = jqis("#search_caption").val();
				Image_Search(caption);
			  }
		});
});

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
		  img: '<?php echo base_url(); ?>images/admin/loadingimage.gif',
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

function Image_Search(Caption) {

 var $container = jqis('.popup_images');
 $container.empty();
if(jqis.trim($container.html()) == '') {
$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
}	
	//var Caption = jqis("#search_caption").val();
	
	postdata = "Caption="+Caption;
	jqis.ajax({
		url: base_url+"niecpan/section_widget_articles/search_image_library",
		type: "POST",
		data: postdata,
		dataType: "json",
		success: function(data){
			
			var Content = '';
			var Count 	= 0;
			var Image_URL = "<?php echo image_url.imagelibrary_image_path;?>";
			var active_image_id = null;
			jqis.each(data, function(i, item) {
				var active_class = "";
				//alert();
				//console.log(item);
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
			});
			if(Content != "") {
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
		}
	});
}


function validate_image_name(content_id) {

	
	var home_bool = true;

	if( $("#customImage_" + content_id + "_view").attr('src') != '' ) {
		postdata = "physical_name="+$('#home_physical_name'+ content_id).val()+'.'+$('#home_physical_name'+ content_id).attr('physical_extension')+"&temp_id="+$("#home_image_gallery_id_" + content_id).val();
		$.ajax({
			url: base_url+"niecpan/section_widget_articles/check_custom_image_name", // Url to which the request is send
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
   CKEDITOR.instances.EditCustomTitle.updateElement();	
   CKEDITOR.instances.txtArticleHeadLine.updateElement();	
   var confirm_text = (remove_image) ? "Are you sure you want to remove image?" : "Are you sure you want to save?";
   if(confirm(confirm_text))
	  {	
		//var instanceId 				= elem.closest('form').find("input[name='instanceId']").val();
		//var instanceMainSectionId 	= elem.closest('form').find("input[name='instanceMainSectionId']").val();
		//var instanceSubSectionId 	= elem.closest('form').find("input[name='instanceSubSectionId']").val();
		var content_id 				= elem.closest('form').find("input[name='content_id']").val();
		var Title 					= elem.closest('form').find("textarea[name='EditCustomTitle']").val();
		var Summary 				= elem.closest('form').find("textarea[name='Summary']").val();
		var section_id_value =  $(this).closest('form').find("input[name='section_id_value']").val();
		var section_id = $('#section_'+content_id).val();
		
		
		var is_checked				= $("input[type='checkbox'][name='articles_list'][id="+content_id+"]").is(":checked");

		var priority				= $("label[name^='priority'][id="+content_id+"]").text();
		
		var check_widgettype = '<?php echo $check_widget_type; ?>';	
		var orig_name 		= $('#orig_name'+content_id).val();		
		var full_path 		= $('#full_path'+content_id).val();		
		var filename 		= $('#filename'+content_id).val();		
		var temp_name 		= $('#temp_name'+content_id).val();		
		var image_library_id 	= $('#image_library_id'+content_id).val();	
		
		//var old_image				= document.getElementById('customImage_'+content_id).files[0];
			//old_image					= (old_image == '') ? old_image : $('#uploaded_image_'+content_id).html();	
		
		
		//var instancecontent_id 		= $('#instancecontent_id_'+content_id).val();			

		var contentType_id = $('#contenttypeID'+content_id).val();	
		var physical_image_name = $('#home_physical_name'+content_id).val();	
		
		var get_sectionname		= '';
		if($('#search_bysection').find('option:selected').attr('parent_name'))
			get_sectionname		= $('#search_bysection').find('option:selected').attr('parent_name');
		else
			get_sectionname		= $('#search_bysection').find('option:selected').text();
														
		var priority_val = $('#change_displayorder_hidden'+content_id).val();
				
		if(remove_image == true)
		{
			var image_caption 	= '';
			var image_alt 		= '';				
			var physical_name 	= '';				
			var temp_image_id	= '';				
			var old_image_id	= '';
			var old_image = 'remove';
		}
		else
		{	
			var old_image = 'add_image';		
			var image_caption 	= $('#home_image_caption' + content_id).val();
			image_caption		= (typeof image_caption === 'undefined') ? '' : image_caption;			
			
			var image_alt 		= $('#home_image_alt' + content_id).val();
			image_alt			= (typeof image_alt === 'undefined') ? '' : image_alt;
			
			var physical_name 	= $('#home_physical_name' + content_id).val();
			physical_name		= (typeof physical_name === 'undefined') ? '' : physical_name;
			
			var temp_image_id	= $('#Image_content_id' + content_id).val();
			temp_image_id		= (typeof temp_image_id === 'undefined') ? '' : temp_image_id;
			
			//var old_image_id	= $('#custom_image_old_id' + content_id).val();
			
			var old_image_id	= $('#uploaded_image_'+content_id).val();
		}
		
		var fd = new FormData();
		//fd.append("uploaded_image", document.getElementById('customImage_'+content_id).files[0]);
		//fd.append("old_image", old_image);
		//fd.append("old_image_name", old_image_name);						
		//fd.append("instanceId", instanceId);
		//fd.append("instanceMainSectionId", instanceMainSectionId);
		fd.append("content_id", content_id);
		fd.append("content_type_id", contentType_id);
		fd.append("Title", Title);
		fd.append("Summary", Summary);
		//fd.append("instancecontent_id", instancecontent_id);
		fd.append("checked_status", is_checked);
		fd.append("article_priority", priority);
		fd.append("old_image", old_image);
		
		fd.append("display_order", priority_val);
		
		fd.append("get_sectionname", get_sectionname);
		
		
		fd.append("image_library_id", image_library_id);
		fd.append("temp_name", temp_name);
		fd.append("orig_name", orig_name);
		fd.append("full_path", full_path);
		fd.append("filename", filename);
		fd.append("section_id_value", section_id);
		fd.append("image_caption", image_caption);
		fd.append("image_alt", image_alt);
		fd.append("physical_name", physical_name);
		fd.append("temp_image_id", temp_image_id);
		fd.append("old_image_id", old_image_id);
		fd.append("check_widget_Type", check_widgettype);
		fd.append("physical_imagename", physical_image_name);
		
		
		
		//console.log('-------'+temp_image_id);
		//console.log("fd: " +fd);	
		//alert(old_image_name);					
		$.ajax({
				url: base_url + "niecpan/section_widget_articles/widgetarticle_customdetails",
				type: 'post',
				processData: false,
				contentType: false,
				async: false,
				//data: { "instanceId": instanceId, "instanceMainSectionId": instanceMainSectionId, "content_id":content_id, "Title":Title, "Summary": Summary, "instancecontent_id":instancecontent_id, "image": fd},
				data:  fd,
				beforeSend: function() {
					// setting a timeout					
					$("#loading_msg").html("Please wait, Custom details are processing to save temporarily....");
					$("#commom_loading").show();
				},
				success: function(data)
				{ 
					//console.log("widgetarticle_customdetails: ",data);						
					$("#commom_loading").hide();
					$("#loading_msg").html("");
					var result= $.parseJSON( data);
					//alert(result.message);
					show_toastr(result.message, 1);
					
					if(result.inserted_id != ''){
						/////  Add or insert the inserted_instancecontentId into the hidden instancecontent_id text box
						$('#instancecontent_id_'+content_id).val(result.inserted_id);					
					}
					if(result.status == true){							
						//elem.closest('form').find("textarea[name='Title']").val(Title);
						$('#title_'+content_id).html(Title)
						$('#summary_'+content_id).html(Summary)
					}
				}
			});						
			/////  To reload the dataTable  /////
			$("#example .ui-sortable" ).sortable("enable");
			
			$('#example').DataTable().ajax.reload();	
			remove_image = false;
	}
	else
	{
		remove_image = false;
		return false;
	}		
		
}
</script> 
