var IExp = {};
IExp.userRole = {};
IExp.pageMaster = {pageId:null, templateId:null, pageType:null, sectionId:null, editInProgress:true, versionId:null, rootstructures: null, total_versions: null, query_string_versionId: null };
IExp.LabelConfig = {
	widgetTooltips :{
		addWidget : 'Add Article',
		removeWidget : 'Remove Widget',
		configWidget : 'Configure Widget'
	},
	widgetCofig_msg :{
		custom_title_save 	: 'Custom title successfully saved',
		confirm_config_save : 'Are sure you want to save configuration',
		config_saved		: 'Widget Config Successfully Saved',
		config_form_error 	: {
			
			required 	: 'This field is required',
			min_require	: 'Please setup atleast one section'
		}
	},
	template_msg :{
		empty_template 	 : 'No template is created for this page. Would you like to create one?',
		remove_container : 'Are you sure want to remove?',
		delete_widget	 : 'Are you sure you want to delete?'
		
	}
};
IExp.categories = {};
IExp.templateMaster = {};
IExp.templateMasterValues = {};
IExp.widgetContainerValues= {};
IExp.widgetContainerMaster = {};
IExp.templateManager = function () {
	var TemplateManagerObj = { 			
		$templateWrapper : null,				
		$selectedTemplateColumnContainer : null,
		$widgetsJSON : null,
		$masterContainer : $("div#IExpWorkSpace"),
		$pageId : null,
		$templateColumnContainer : $("div.tpl-col-container"),
		$fancyTreeCurrentNode: null,
		$widgetConfigBaseURL: base_url + admin_folder +"/template_designer/show_widgetconfig",
		$widgetConfigRelativeURL: base_url + admin_folder +"/template_designer/show_widgetconfig",
		init: function() {			
			//populate the js template object
			$.ajax({ 
					type: 'GET', 					
					url: base_url + admin_folder +"/template_designer/dynamic_templates_containers",				
					data: { get_param: 'value', return_type : "json" },
					dataType: 'json',
					async: false,					
					success: function (data) {
						//console.log(data.container_obj.container_id_values);
						IExp.templateMaster 		= data.templ.templateString;	
						IExp.templateMasterValues	= data.templ.template_id_values;	
						IExp.widgetContainerMaster 	= data.container_obj.container_content;
						IExp.widgetContainerValues 	= data.container_obj.container_id_values;
						IExp.categories 			= data.categories;
						
						///////  Load Widgets  //////
						TemplateManagerObj.$widgetsJSON = data.all_active_widgets;
						var loadWidgetStr = "",  loadWidgetStr = loadWidgetStr  +  "<ul id ='widget_list'>";
						$.each(data.all_active_widgets.widgetHolder,function(key,widgetList){							
							obj = widgetList;
							var dataAttributes = "";
							for (var key in obj) {
								var widgetid 		= ""; 
								var widgetid_string = "";
								var widget_path 	= ""; 
								dataAttributes 		= dataAttributes + ' data-' + key + ' ="' + obj[key] + '"';
								
							}									
								widgetid 			= obj['widgetId'];  
								widgetid_string 	= "'widgetmodal"+ obj['widgetId'] +"'";  
								widget_path 		= obj['widgetfilePath'].split('/'); 
								widget_path			= widget_path[widget_path.length-1];
								widget_path_string	= "'" +widget_path +"'";
								class_name			= (typeof obj['clonedStatus'] == "undefined" || obj['clonedStatus'] == "" ) ? 'regular-widget' : 'cloned-widget';
								
								cloned_widget_instance_id = (typeof obj['clonedStatus'] == "undefined" || obj['clonedStatus'] == "" ) ? '' : obj['cloned_instance_id'];
								cloned_from	= (cloned_widget_instance_id) ? ((obj['clonedfrom'] === 'undefined' || typeof obj['clonedfrom'] === 'undefined')? "" : " ("+ obj['clonedfrom'] +")" ) : "";
						var widgetThumbnailPath = 'images/admin/template_design/images/widget_images/'+widget_path+ '-small.jpg';
						dataAttributes = dataAttributes + ' data-widgetThumbnailPath="' + widgetThumbnailPath + '"'+' data-clonedinstanceid="'+ cloned_widget_instance_id + '"'; 
						loadWidgetStr = loadWidgetStr  + '<li ' + dataAttributes + ' class="'+ class_name +'">'+obj['widgetName']+ cloned_from +'<a href="#widgetmodal'+ widgetid +'" onclick="show_widget_image('+widgetid_string +','+ widget_path_string + ')" ><i class="fa fa-desktop i_extra"></i></a> <br> <img src="'+template_design_images_url+'images/admin/template_design/images/widget_images/'+widget_path+ '-small.jpg"  style="display:none;" /></li>';														
						});
					 	loadWidgetStr =  loadWidgetStr  + "</ul><div class='widgetListContainer_no_values' ></div>";	
						$("#widgetHolder .widgetListContainer").html(loadWidgetStr);						
						$("#widgetHolder ul li" ).draggable({
							appendTo: "body",
							helper: "clone"
						});	
					},
					error: function(){						
						
					}
			});	 
			$("#btnExpandTree").bind("click", function() {
				$("#tree").fancytree("getRootNode").visit(function(node){
					node.setExpanded(true);
					$('#btnExpandTree').addClass("active");
					$("#btnCollapseTree").removeClass("active");
				});				
			});
			$("#btnCollapseTree").bind("click", function() {
				 $("#tree").fancytree("getRootNode").visit(function(node){
						node.setExpanded(false);
						$('#btnExpandTree').removeClass("active");
						$("#btnCollapseTree").addClass("active");
				  });			
			});
			
			$("#tree").fancytree({				 									
				click:function(event, data){
					 IExp.qParamScrollTop = 0;
					 TemplateManagerObj.fancyTreeCurrentNode = data.node;

				// A node is about to be selected: prevent this, for folder-nodes:						
					if( $.ui.fancytree.getEventTargetType(event.originalEvent) == "expander"){
						return true;
					} else {
																
						//////////  Save Dialog box  //////
						var commit_status = TemplateManagerObj.get_template_commit_status();		
						if(commit_status.tempalte_commit_status != 1) //////  !=1 means template is not commited  /////
						{										
							//////  Prompt user to save template or not save  ///			
							$( "#confirm-1" ).dialog( {
									resizable	: false,
									modal		: true,
									title		: "Do you want to save changes?",
									height		: 250,
									width		: 325,
									buttons		: {
													"Save": function() {
														var do_below_functions = {
															"" : $("#saveTemplate").trigger( "click", "Advertisement" ),
															"" : TemplateManagerObj.fancy_tree_click_function(data)
															};			  												
														TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);															
														TemplateManagerObj.fancyTreeCurrentNode.setActive();
														
													},
													"Don't save": function() {		
														var do_below_functions = { "" : TemplateManagerObj.dont_save_template(IExp.pageMaster.pageId), 
																				   "" : TemplateManagerObj.fancy_tree_click_function(data)
														 						  };
														TemplateManagerObj.dialog_callback(2, $(this), do_below_functions);			
														TemplateManagerObj.fancyTreeCurrentNode.setActive();
													},
													Cancel: function() {
														$("#tree").fancytree("getActiveNode").focus ;
														TemplateManagerObj.dialog_callback(3, $(this), '');															
														return false;
													}
												  }
						  });	
						  $(".ui-dialog-buttons").addClass("save-dialog-box");
						  $(".ui-dialog-titlebar-close").addClass("fa fa-times");						  
							return false;
						}						
						else
						{
							TemplateManagerObj.fancy_tree_click_function(data);														 
						}
					}									
				}		
			});
			//activate tree and load template based on query param
			if (IExp.qParamPageId != ""){
				
				TemplateManagerObj.trigger_fancy_tree_click();
			}
			this.$templateWrapper = $("#template-wrapper");				
			//Add New templates new containers // left panel event li
			$("#fm-left-panel").on("click", function(e){
				var $elem = $(e.target);
				var node = $("#tree").fancytree("getActiveNode");
				var pageId;
				if(node && node.data.pageid){					
					pageId =  node.data.pageid;					
					if ($elem.is("a img.add-template-img")) {								
							var is_template_available;
							if(TemplateManagerObj.$masterContainer.html().length > 0)
							{
								////  When we want to replace the current template, then below comment will be removed  /////
								/*if(confirm("Do you want to remove current template?"))
								{
									is_template_available = true;
								}
								else
								{
									is_template_available = false;
								}*/
							}
							else
							{
								is_template_available = true;
							}
							if(is_template_available)
							{
								if(TemplateManagerObj.$masterContainer.html().length > 0)
								{
									// Pass IExp.pageMaster.pageId to remove the template from back end. 
									$.ajax({					
											url: base_url + admin_folder +"/template_designer/delete_widget_instance",				
											type: 'post',
											async:false,
											data: "deleteTemplate=y&delete_instance_pageid="+pageId,
											success: function (data) {											
												//console.log (" resu: " + data.status);
												if(data.status == 1)
												{																						
													TemplateManagerObj.$masterContainer.html(TemplateManagerObj.loadTemplates($elem.attr("data-templateId"), pageId));
													IExp.pageMaster.templateId = $elem.attr("data-templateId");
													$("#saveTemplate").trigger( "click", ''); /// Auto Save				
													alert("Template replaced successfully");
												}
												else
												{
													//alert("Widget is unable to delete");
													if(data.show_msg == 1 && data.widget_name == '')
													{
														show_toastr(data.msg, data.msg_type);
													}
													else
													{
														alert(data.widget_name +" unable to delete.");
													}
												}
												
											},
											error: function (e) {
												alert("Internal sever error");
												
											}
										});	
									}
									else
									{
										TemplateManagerObj.$masterContainer.html(TemplateManagerObj.loadTemplates($elem.attr("data-templateId"), pageId));
										IExp.pageMaster.templateId = $elem.attr("data-templateId");
										$("#saveTemplate").trigger( "click", 'Autosave_add_template_img'); /// Auto Save
										
										if(typeof IExp.pageMaster.versionId === 'undefined')
										{
											TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, 'new'); ////  Show template version dropdown 
										}
										IExp.pageMaster.versionId = $( "#show_template_version :selected" ).val();
										$("a.dynamic_design_link").attr("href", base_url+admin_folder +"/template_designer/load_saved_template/" + pageId + "/"+ IExp.pageMaster.versionId);
										var show_html_obj =  ['#common_options', '#edit_version_name', '#addheader_script'] ;
										var hide_html_obj = ['#delete_currentVersion_template' ];
										
										//$('#common_options').show();
										TemplateManagerObj.showCommonTemplate_options(IExp.pageMaster.pageId, IExp.pageMaster.versionId, '', '');
										//$('#edit_version_name').show();
										if(IExp.userRole.canAddAdv){
											//$('.header_script_publish').show();	
											var condition_show_obj = ['.header_script_publish'];		
											TemplateManagerObj.show_html_elements(condition_show_obj);  // Parameter is must be an object
										}
										
										TemplateManagerObj.show_html_elements(show_html_obj);
										TemplateManagerObj.hide_html_elements(hide_html_obj);
									}
							}
					} else if ($elem.is("a img.add-widget-container-img")) {	
							if (TemplateManagerObj.$selectedTemplateColumnContainer){
								var id = (new Date()).getTime() ;
								$(TemplateManagerObj.loadTemplateContainers(id, $elem.attr("data-typeId"))).appendTo(TemplateManagerObj.$selectedTemplateColumnContainer);							
								TemplateManagerObj.attachDroppableEvent(id);	
								
								$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
								$('#IExpWorkSpace .tpl-col-container').sortable( "refreshPositions" );
								$('#IExpWorkSpace .tpl-col-container').sortable({ handle:".handle", update:function(event, ui){ 
									//this is just a definition to save the template								
								}});
								
								if(IExp.pageMaster.sectionId != 10001){
										$(".container-handle-id").text("");
									 }
								$( "#IExpWorkSpace .tpl-col-container").unbind("sortupdate").on( "sortupdate", function( event, ui ) {						
									$("#saveTemplate").trigger( "click", 'Autosave' ); // Auto Save							
								});								
							} else {
								alert('Please select a container');
							}												
					}
						if ($elem.is("input#applyTemplate")) {		
						if(IExp.pageMaster.templateId > 0) {
							//$("div.left-panel-holders").hide();
							//$("#widgetContainerTypeHolder").show();						
						} else {
							alert('Please select a template first');
						}
					}else if ($elem.is("button#clearTemplate")) {						
						TemplateManagerObj.$masterContainer.empty();						
						IExp.pageMaster.templateId = 0;						
					} else if ($elem.is("input#exportTemplate")) {			
						$("#xmlContainer").text(TemplateManagerObj.getItems2());			
					} else if ($elem.is("input#show_cloned_widgets")) {									
						if($elem.is(':checked')){
							
							var cloned_elements = $('#widget_list').children("li.cloned-widget"); 
							if(cloned_elements.length != 0){
								$("li.cloned-widget").show();								
								$("li.regular-widget").hide();	
								// from view file
								filter_widget_list($('#widget_name_autocomplete').val().toLowerCase());	
							}else{
								var all_elements = $('#widget_list').children();
								$($('#widget_list').children()).hide();								
								$(".widgetListContainer_no_values").html('<ul id="no_values"><li>No records found</li></ul>');
							}
						} else {
							$("li.cloned-widget").hide();
							$("li.regular-widget").show();
							// from view file
							filter_widget_list($('#widget_name_autocomplete').val().toLowerCase());	
							$("#no_values").remove();							
						}						
					} 
				} else {
					alert("Please select a page from left side 'Section Map'");
				}					 
				if(IExp.userRole.canDesignPage) {
					//makes the template container sortable
					$('#IExpWorkSpace .tpl-col-container').sortable({
						handle:".handle",
						update:function(event, ui){ 
							//Sorting completed
						}					
					});
				}
			});											

			/////  Publish the saved template  /////
			$("#publish_template").bind('click',function(){
					/////  verify is current templates is locked by the user  /////
					var template_lock_status = tree_based_locktemplate(IExp.pageMaster.pageId); ///// From template_designer.php (view)  /////
					if(template_lock_status != false)
					{
						var commit_status = TemplateManagerObj.get_template_commit_status();
						if(commit_status.tempalte_commit_status == 1) //////  !=1 means template is not commited  /////
						{
							if($(this).hasClass("page-loaded")){
								TemplateManagerObj.publish_template();
							}else{
								show_toastr("please wait while the template is loading...", 2)
							}
						}
						else
						{
							/////  Commit template first and do publish template  ////
							show_toastr("Please save the template", 2);							
						}
					}
			});
			//////  For Auto save and update template version //////
			$("#saveTemplate").bind('click',function(event, string){						
				var update_commit_status = (typeof string === 'undefined') ? "1" : (string == 'Autosave' || string == 'Autosave_add_template_img')? "2" : "adv"; ///// 1->Commit template, 2->Don't Commit, adv->commit if widget is advetisement					
				var from_add_tempalte_img = (string == 'Autosave_add_template_img') ? true : false;	
				if(update_commit_status == 1)
				{
					/////  verify is current templates is locked by the user  /////
					var template_lock_status = tree_based_locktemplate(IExp.pageMaster.pageId); ///// From template_designer.php (view)  /////
					if(template_lock_status != false)
					{
						if(confirm("Are you sure you want to update current version?")){
							$("#loading_msg").html("Please wait....");
							$("#commom_loading").show();
							TemplateManagerObj.save_template(update_commit_status, from_add_tempalte_img);
							$('.template_preview').show(); 		
							if(IExp.pageMaster.sectionId != 10001){ $('#make_version').show(); }
							$('#publish_template').show();  
							
						}
						else
						return false;
					}
					else
					{
						return false;
					}
				}
				else if(update_commit_status == "adv"){
					$("#loading_msg").html("Please wait....");
					$("#commom_loading").show();
					update_commit_status = 1;
					if(string != "Advertisement_widget"){
						TemplateManagerObj.save_template(update_commit_status, from_add_tempalte_img);
					}
					$("#loading_msg").html("");
					$("#commom_loading").hide();
					$('.template_preview').show(); 
				}
				else{
					$("#loading_msg").html("Please wait....");
					$("#commom_loading").show();
					var is_committed = TemplateManagerObj.save_template(update_commit_status, from_add_tempalte_img);
					$('.template_preview').hide(); 
					$('#saveTemplate').show(); 
					
					if(is_committed != 2){
						TemplateManagerObj.show_hide_unsaved_template_msg(2); // 1->saved, 2->unsaved
					}
					

				}
			});
						
			//////  For making new template version //////
			$("#make_version").bind('click',function(event, string){
				/////  verify is current templates is locked by the user  /////
				var template_lock_status = tree_based_locktemplate(IExp.pageMaster.pageId); ///// From template_designer.php (view)  /////
				if(template_lock_status != false)
				{
						var template_commit_status = (typeof string === 'undefined') ? "3" : "2"; ///// 3->Make new version and Commit template, 2->Don't Commit, 1->save current version			
						if(template_commit_status == 3)
						{
							var commit_status = TemplateManagerObj.get_template_commit_status();							
							if(false)
							{
								alert("No changes found in current template design to make a new version");
							}
							//else if(commit_status.tempalte_commit_status == 2)
							else if(true)
							{
								if(confirm("Do you want to make current template as new version?"))
								{
									TemplateManagerObj.save_template(template_commit_status);	
									TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, ''); ////  Show template version dropdown 
									IExp.pageMaster.versionId = $( "#show_template_version :selected" ).val();
									TemplateManagerObj.load_version_template(IExp.pageMaster.versionId, IExp.pageMaster.pageId);
									$('#delete_currentVersion_template').show();
									TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
								}
								else
								{
									return false;
								}
							}								
						}
				}
			});
			
			
			//////  For making new version with empty template //////
			$("#new_empty_version").bind('click',function(event, string){
				/////  verify is current templates is locked by the user  /////
				var template_lock_status = tree_based_locktemplate(IExp.pageMaster.pageId); ///// From template_designer.php (view)  /////
				if(template_lock_status != false)
				{
						var update_template_commit_status = (typeof string === 'undefined') ? "4" : "2"; ///// 4->Make new version with empty template and Commit template, 2->Don't Commit, 1->save current version			
						if(update_template_commit_status == 4)
						{
							var commit_status = TemplateManagerObj.get_template_commit_status();
							if(commit_status.tempalte_commit_status == 1)
							{
								/* Here the code for make new empty version  */
								if(confirm("Do you want to make new version with empty template?"))
								{
									TemplateManagerObj.save_template(update_template_commit_status);	
									TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, ''); ////  Show template version dropdown 
									IExp.pageMaster.versionId = $( "#show_template_version :selected" ).val();
									//TemplateManagerObj.load_version_template(IExp.pageMaster.versionId, IExp.pageMaster.pageId);
									TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, IExp.pageMaster.versionId);
									$('#delete_currentVersion_template').show();
									$('.template_preview').hide();
									$('#common_options').hide();
									
									if(IExp.userRole.canAddAdv){
										$('.header_script_publish').hide();		
										$('#make_version').hide();			
										$('#publish_template').hide();
									}
								}
								else
								{
									return false;
								}								
							}
							else if(commit_status.tempalte_commit_status == 2)
							{
								show_toastr("Please save current template", 3);
								return false;
							}								
						}
				}
			});
			
			this.initializeTemplateContainer();	
			$("#fmLeftTabs").tabs();		
			
			//init Context Menu for the widget containers
			this.initContextMenu();			
		},
		initializeTemplateContainer : function(){				
			$("#IExpWorkSpace").on("click", function(e){
				var $elem = $(e.target);
				if ($elem.is("span.close-container")) {						
					var widgetList =  $elem.parents("div.container-wrapper").find("div.widget");
					
					var widgetInstanceListStr = "";
					$.each(widgetList,function(){
						widgetInstanceListStr =  $(this).attr("data-widgetinstanceid") + "," + widgetInstanceListStr ;						
					});
					if(IExp.pageMaster.sectionId == 10001){
						// Verify if this widget is parent of clone widgets and used in any other templates.
						var is_mapped_with_other_template = TemplateManagerObj.verify_clone_mapping(widgetInstanceListStr.slice(0, -1), "delete");
						
					}else{
						var is_mapped_with_other_template = false;
					}
					
					if(is_mapped_with_other_template){ 
						return false;
					}else{						
							var delete_lock_obj = {};
							//////////  Verify this widget is lock or unlock mode  ///////
							delete_lock_obj = lock_delete_container(widgetInstanceListStr, IExp.pageMaster.pageId, '');	
							if(delete_lock_obj.res_status == 0)
							{
								if(delete_lock_obj.show_msg == 1)
								{
									show_toastr(delete_lock_obj.msg, delete_lock_obj.msg_type);
								}		
							}
							else
							{
								if(confirm("Are you sure want to remove?"))
								{
									IExp.pageMaster.editInProgress = true;						
									if(widgetInstanceListStr != '')
									{						
										$.ajax({					
												url: base_url + admin_folder +"/template_designer/delete_widget_instance",				
												type: 'post',
												async:false,
										data: "deleteall=y&delete_instance_id="+widgetInstanceListStr+"&page_id="+IExp.pageMaster.pageId,
												success: function (data) {									
													if(data.status == 1)
													{										
														$elem.closest("div.container-wrapper").remove();
														$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
														alert("Container removed successfully");
													}
													else
													{
														alert(" Unable to delete.");
													}											
												},
												error: function (e) {
													alert("Internal sever error");											
												}
											});
									}
									else
									{
										$elem.closest("div.container-wrapper").remove();
										$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
									}
								}
							}
						}	
				}else if($elem.is("div.template-wrapper-top")){
					TemplateManagerObj.$selectedTemplateColumnContainer = $elem;
					TemplateManagerObj.doHightlight($elem);					
				}else if($elem.is("div.template-wrapper-mid")){
					TemplateManagerObj.$selectedTemplateColumnContainer = $elem;
					TemplateManagerObj.doHightlight($elem);					
				}else if($elem.is("div.template-wrapper-left")){
					TemplateManagerObj.$selectedTemplateColumnContainer = $elem;
					TemplateManagerObj.doHightlight($elem);
				}else if($elem.is("div.template-wrapper-right")){
					TemplateManagerObj.$selectedTemplateColumnContainer = $elem;
					TemplateManagerObj.doHightlight($elem);
				}else if($elem.is("div.template-wrapper-footer")){
					TemplateManagerObj.$selectedTemplateColumnContainer = $elem;
					TemplateManagerObj.doHightlight($elem);
				}else if($elem.is("a.add-widget-button")){				
					//console.log($elem.get(0).id);
					/* Widget Add article click  - Plus button */
						var current_id = $elem.get(0).id;						
						var commit_status = TemplateManagerObj.get_template_commit_status();		
						if(commit_status.tempalte_commit_status != 1) //////  !=1 means template is not commited  /////
						{			
							//////  Prompt user to save template or not save  ///			
							$( "#confirm-1" ).dialog( {
									resizable	: false,
									modal		: true,
									title		: "Do you want to save changes?",
									height		: 250,
									width		: 325,
									buttons		: {									
													"Save": function() {
														var do_below_functions = {
															"" : $("#saveTemplate").trigger( "click", "Advertisement" ), "": TemplateManagerObj.show_articles(current_id)//, "": TemplateManagerObj.release_locks_by_user_id()
															};			  												
														TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);
													},
													"Don't save": function() {		
														//alert("Dont save template");
														var do_below_functions = { "" : TemplateManagerObj.dont_save_template(IExp.pageMaster.pageId), "": TemplateManagerObj.show_articles(current_id)//, "": TemplateManagerObj.release_locks_by_user_id() 
														};
														TemplateManagerObj.dialog_callback(2, $(this), do_below_functions);	
													},
													Cancel: function() {										
														TemplateManagerObj.dialog_callback(3, $(this), '');	
													}
												  }
						  });	
						  $(".ui-dialog-buttons").addClass("save-dialog-box");
						  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
						}
						else
						{
							//TemplateManagerObj.release_locks_by_user_id();
							TemplateManagerObj.show_articles(current_id);
						}							
				}else if($elem.is("a.view-child-clone-button")){
					
					var current_id = $elem.get(0).id;	
					var commit_status = TemplateManagerObj.get_template_commit_status();	
					if(commit_status.tempalte_commit_status != 1) //////  !=1 means template is not commited  /////
					{			
						//////  Prompt user to save template or not save  ///			
						$( "#confirm-1" ).dialog( {
								resizable	: false,
								modal		: true,
								title		: "Do you want to save changes?",
								height		: 250,
								width		: 325,
								buttons		: {									
												"Save": function() {
													var do_below_functions = {
														"" : $("#saveTemplate").trigger( "click", "Advertisement" ), "": TemplateManagerObj.show_cloned_children(current_id)
														};			  												
													TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);
												},
												"Don't save": function() {															
													var do_below_functions = { "" : TemplateManagerObj.dont_save_template(IExp.pageMaster.pageId), "": TemplateManagerObj.show_cloned_children(current_id)//, "": TemplateManagerObj.release_locks_by_user_id() 
													};
													TemplateManagerObj.dialog_callback(2, $(this), do_below_functions);	
												},
												Cancel: function() {										
													TemplateManagerObj.dialog_callback(3, $(this), '');	
												}
											  }
					  });	
					  $(".ui-dialog-buttons").addClass("save-dialog-box");
					  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
					}
					else
					{						
						TemplateManagerObj.show_cloned_children(current_id);
					}
				}else if($elem.is("a.remove-widget-button")){				
					var delete_instance_id 	= $("div.widget-container-"+$elem.attr("data-target-container")).find(".widget").attr("data-widgetinstanceid");	
					check_renderingtype		= $("div.widget-container-"+$elem.attr("data-target-container")).find(".widget").attr("data-renderingtype");	

					//////////  Verify this widget is lock or unlock mode  ///////
					var delete_lock_obj = lock_widget_config(delete_instance_id, IExp.pageMaster.pageId, '', "");	
					//console.log(config_lock_obj);
					if(delete_lock_obj.res_status == 0)
					{
						if(delete_lock_obj.show_msg == 1)
						{
							show_toastr(delete_lock_obj.msg, delete_lock_obj.msg_type);							
						}		
					}
					else
					{
						var widgetObject = $elem.parent().siblings(".widget-container").find("div.widget");
						var delete_widget_title = widgetObject.find("h4").text();
						if(IExp.pageMaster.sectionId == 10001){
							// Verify if this widget is parent of clone widgets and used in any other templates.
							var is_mapped_with_other_template = TemplateManagerObj.verify_clone_mapping(delete_instance_id, "delete");
						}else{
							var is_mapped_with_other_template = false;
						}
						
						if(is_mapped_with_other_template){ 
							return false;
						}else{
								if(confirm("Are you sure you want to delete '"+ delete_widget_title +"' widget?"))
								{
									if(delete_instance_id !='')
									{
										$.ajax({					
											url: base_url + admin_folder +"/template_designer/delete_widget_instance",				
											type: 'post',
											async:false,
											data: "&delete_instance_id="+delete_instance_id+"&page_id="+IExp.pageMaster.pageId + "&check_renderingtype=" + check_renderingtype,
											success: function (data) {
												$('#IExpWorkSpace').unblock();	
												if(data.status == 1)
												{											
													$("div.widget-container-"+$elem.attr("data-target-container")).empty("");
													$("div.widget-container-"+$elem.attr("data-target-container")).parent(".wc").find(".add-widget").find("a.add-widget-button").removeClass("added");					
													$("div.widget-container-"+$elem.attr("data-target-container")).parent(".wc").find(".add-widget").find("a.add-widget-button").hide();					
													$("div.widget-container-"+$elem.attr("data-target-container")).parent(".wc").find(".add-widget").find("a.config-widget-button").hide();					
													$("div.widget-container-"+$elem.attr("data-target-container")).parent(".wc").find(".add-widget").find("a.view-widget-button").hide();					
													$("div.widget-container-"+$elem.attr("data-target-container")).parent(".wc").find(".add-widget").find("a.view-child-clone-button").hide();					
																										
													$elem.hide();
													
													if(IExp.pageMaster.sectionId == 10001){
														// Hide this widget in the Widget list in Action Bar
														$("#widget_list").find("[data-clonedinstanceid='" + delete_instance_id + "']").hide();
													}
													
													$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
													show_toastr(data.widget_name + " widget deleted successfully.", 1);
												}
												else
												{
													if(data.show_msg == 1 && data.widget_name == '')
													{
														show_toastr(data.msg, data.msg_type);
													}
													else
													{
														show_toastr(data.widget_name +" unable to delete.", 2);
													}
												}										
											},
											error: function (e) {
												show_toastr("Internal sever error", 2);										
											}
										});							
									}
									else
									{								
										if(delete_lock_obj.show_msg == 1)
										{
											show_toastr(delete_lock_obj.msg, delete_lock_obj.msg_type);
										}
									}
								}
							}
					}										
				}else if($elem.is("a.add-content")){	
					$(".jqmWindow").jqmHide();					
					$('#widgetConfigWindow').jqmShow();	
					$( "#contentWindow #site-contents li" ).draggable({
						appendTo: "body",
						helper: "clone"
					});	
				}else if($elem.is("a.config-widget-button")){
					var widgetObject = $elem.parent().siblings(".widget-container").find("div.widget");
					
					var check_renderingtype = $(widgetObject).data("renderingtype");
					//console.log($(widgetObject).data("renderingtype"));
					//alert(IExp.pageMaster.versionId);
					var widgetId = $elem.parent().siblings(".widget-container").find("div.widget").attr("id");					
					

					var cloned_instance_id 	= $(widgetObject).data("clonedinstanceid");
					cloned_instance_id		= (typeof cloned_instance_id === 'undefined' || cloned_instance_id === 'undefined') ? "" : cloned_instance_id;
					//console.log(cloned_instance_id);
					var widget_instance_Id = (cloned_instance_id) ? cloned_instance_id : $elem.parent().siblings(".widget-container").find("div.widget").data("widgetinstanceid");
					
					//////////  Verify this widget is lock or unlock mode  ///////
					var config_lock_obj = lock_widget_config(widget_instance_Id, IExp.pageMaster.pageId, '2', check_renderingtype);	
					// function from template designer view
					//console.log(config_lock_obj);
					if(config_lock_obj.res_status == 0)
					{
						if(config_lock_obj.show_msg == 1)
						{
							if(config_lock_obj.design_lock_status == 2){
							
								//////  Prompt user to save template or not save  ///			
								$( "#confirm-1" ).dialog( {
										resizable	: false,
										modal		: true,
										title		: "Do you want to save changes?",
										height		: 250,
										width		: 325,
										buttons		: {
														"Save": function() {
															var do_below_functions = {
																"" : $("#saveTemplate").trigger( "click", "Advertisement" ),
																"" : TemplateManagerObj.show_widget_configuration(widgetObject, $elem, check_renderingtype, widgetId, widget_instance_Id, IExp.pageMaster.pageId)
																};			  												
															TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);
														},
														/* "Don't save": function() {		
															//alert("Dont save template");
															var do_below_functions = { "" : TemplateManagerObj.dont_save_template(IExp.pageMaster.pageId), "": TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, '') };
															TemplateManagerObj.dialog_callback(2, $(this), do_below_functions);	
														},*/														
														Cancel: function() {
															TemplateManagerObj.dialog_callback(3, $(this), '');	
														}
													  }
							  });	
							  $(".ui-dialog-buttons").addClass("save-dialog-box");
							  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
							  
							 // show_toastr(config_lock_obj.msg, config_lock_obj.msg_type);
							}
							else{
									show_toastr(config_lock_obj.msg, config_lock_obj.msg_type);
							}
							
						}		
					}
					else ////// Free to unlock Widget configuration  /////
					{
						TemplateManagerObj.show_widget_configuration(widgetObject, $elem, check_renderingtype, widgetId, widget_instance_Id, IExp.pageMaster.pageId);
					
					}//////  Verifying widget config lock  /////
				}else if($elem.is("a.add-tab")){	
					//console.log("Adding tab....");								
				}else if($elem.is("a.remove-content")){				
					$elem.parent().remove();					
				}
			});			
		},		
		initContextMenu: function(e){
			 		
			 var local_storage_copy 	= admin_folder+"currentWidget-copy";
			 var local_storage_clone 	= admin_folder+"currentWidget-clone";
			 var menu_obj 				= [{title: "Copy", cmd: "copy"},{title: "Paste", cmd: "paste"}];
			 
					if(IExp.userRole.canDesignPage){						
						$("#IExpWorkSpace").contextmenu({
							delegate	: ".add-widget",
							menu		: menu_obj,
							beforeOpen	: function(event, ui) {
									/* For adding clone option into contextmenu */
									 if(IExp.pageMaster.sectionId == 10001){
										menu_obj = [{title: "Copy", cmd: "copy"},{title: "Paste", cmd: "paste"},{title: "Clone", cmd: "clone"}];	
									}else{
										menu_obj = [{title: "Copy", cmd: "copy"},{title: "Paste", cmd: "paste"}];
									}	
									$("#IExpWorkSpace").contextmenu("replaceMenu", menu_obj); 
									/*From view*/
									var template_lock_status= tree_based_locktemplate(IExp.pageMaster.pageId);
									var widgetObject 		=  ui.target.siblings(".widget-container").find(".widget");
									var customConfig 		= widgetObject.data("customConfig") ? widgetObject.data("customConfig") : '';
									var widget_contenttype		= widgetObject.attr("data-contenttype");
									var widget_rendering_type 	= widgetObject.attr("data-renderingtype");									
									var is_cloned_widget		= widgetObject.attr("data-clonedinstanceid");
									is_cloned_widget		= (typeof is_cloned_widget === 'undefined' || is_cloned_widget === 'undefined') ? "" : is_cloned_widget;
									
									$("#IExpWorkSpace").contextmenu("enableEntry", "copy", true);
									$("#IExpWorkSpace").contextmenu("enableEntry", "paste", true);
									$("#IExpWorkSpace").contextmenu("enableEntry", "clone", false);
									
									//validation for copy, clone
									if(widgetObject.length == 0 ){
										$("#IExpWorkSpace").contextmenu("enableEntry", "copy", false);
										$("#IExpWorkSpace").contextmenu("enableEntry", "clone", false);
									}
									
									//validation for paste	
									if( !(localStorage.getItem(local_storage_copy)) || typeof localStorage.getItem(local_storage_copy) == 'undefined' || template_lock_status !=2 ){
										$("#IExpWorkSpace").contextmenu("enableEntry", "paste", false);										
									}
									//console.log("widget_contenttype", widget_contenttype);
									//validation for clone
									if(is_cloned_widget){
										$("#IExpWorkSpace").contextmenu("enableEntry", "clone", false);
									}else{
										if(widget_rendering_type == 1 || widget_rendering_type == 3){
											if(widget_contenttype != 1 && widget_contenttype != 6){
												$("#IExpWorkSpace").contextmenu("enableEntry", "clone", true);
											}
										}else{
											$("#IExpWorkSpace").contextmenu("enableEntry", "clone", false);
										}	
									}
									
							},
							select: function(event, ui) {
								
								var widgetContainer =  ui.target.siblings(".widget-container");			 
								if(ui.cmd == "copy") {
									var widgetDataStr = TemplateManagerObj.copyWidgetConfig(ui);
									localStorage.setItem(local_storage_copy, widgetDataStr);
									wobj = JSON.parse(widgetDataStr);									
									show_toastr(wobj.widget_title +" widget copied", 1);	
									
								} else if (ui.cmd == "paste"){						
									var isWidgetDrop = true;
									if(widgetContainer.find("div.widget").length != 0){ 
										isWidgetDrop = (confirm("This container is already having a widget. Are you sure you want to replace it with copied widget?")) ? true : false;									
									} 
									if (isWidgetDrop){										
										var o = JSON.parse(localStorage.getItem(local_storage_copy));
										//console.log(o.attributeAry['data_clonedinstanceid']);
										var reference_widget_instance = o.attributeAry['data_widgetinstanceid'];
										var clone_reference_id		  = (typeof o.attributeAry['data_clonedinstanceid'] === 'undefined' || o.attributeAry['data_clonedinstanceid'] === 'undefined' || o.attributeAry['data_clonedinstanceid'] == '') ? "" : o.attributeAry['data_clonedinstanceid'];
										widgetContainer.html(o.widgetHTML);
										widgetContainer.siblings(".add-widget").find(".add-widget-button").hide();
										widgetContainer.siblings(".add-widget").find(".config-widget-button").hide();
										widgetContainer.siblings(".add-widget").find(".remove-widget-button").hide();
										
										widgetContainer.siblings(".add-widget").find(".add-widget-button").attr({"style": o.addButtonState});
										widgetContainer.siblings(".add-widget").find(".config-widget-button").attr({"style": o.configButtonState});
										widgetContainer.siblings(".add-widget").find(".remove-widget-button").attr({"style": o.removeButtonState});
										
										var  widgetDiv = widgetContainer.find(".widget");							
										widgetDiv.attr("data-widgetcontainerorderid",$(widgetContainer).attr("data-widgetcontainerorderid"));
										widgetDiv.attr("data-widgetpageId",IExp.pageMaster.pageId);							
										
										var widget_type 			= widgetDiv.attr("data-widgetstyle");
										var widget_rendering_type 	= widgetDiv.attr("data-renderingtype");
										//console.log(widget_type + "" +widget_rendering_type);
										var widget_configuration = JSON.stringify(o.customConfig);
										//console.log(o);
										$.ajax({					
											url: base_url + admin_folder +"/template_designer/copy_widget_instance",				
											type: 'post',
											async:false,
											data: "page_id=" + IExp.pageMaster.pageId + "&pageType=" + IExp.pageMaster.pageType + "&sectionId=" + IExp.pageMaster.sectionId + "&containerId=" +  $(widgetContainer).parents("div.container-wrapper").attr("id") + "&widgetId=" + widgetDiv.attr("data-widgetid")+ "&widgetOrderIdInContainer=" + $(widgetContainer).attr("data-widgetcontainerorderid") + "&widgetStyle=" + widgetDiv.attr("data-widgetstyle") + "&versionId=" + IExp.pageMaster.versionId +"&reference_widget_instance="+ reference_widget_instance +"&widget_configuration="+ widget_configuration +"&widget_type="+ widget_type +"&widget_rendering_type="+ widget_rendering_type +"&clone_reference_id="+ clone_reference_id,
											beforeSend: function (data) {									
													$.blockUI({ 
													message: '<h1 style="padding:5px;font-size:12px">Loading Template...</h1>',
													css: { border: '1px solid #a00' } 
												});
											},
											success: function (data) {												
												jsonData = JSON.parse(data);												
												widgetDiv.attr("data-widgetinstanceid",jsonData.widget_instance_id);
												if(jsonData.widget_instance_id){
													show_toastr("Widget successfully pasted", 1);
												}else{
													show_toastr("Widget pasting failed", 2);
												}
												$.unblockUI();	
											},
											complete: function(){
												$.unblockUI();								
											},
											error: function (e) {
												//alert("error");	
												$.unblockUI();
											}
										});
										
										//will be useful for simple and nested tab widgets
										widgetDiv.data("customConfig", o.customConfig);
										$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
									}						
								}else if (ui.cmd == "clone"){
									var widgetDataStr = TemplateManagerObj.copyWidgetConfig(ui);									
									localStorage.setItem(local_storage_clone, widgetDataStr);
									var o = JSON.parse(widgetDataStr);
									var reference_widget_instance 	= (typeof o.attributeAry["data_clonedinstanceid"] === 'undefined' || o.attributeAry["data_clonedinstanceid"] === 'undefined' || o.attributeAry["data_clonedinstanceid"] == '') ? o.attributeAry['data_widgetinstanceid'] : o.attributeAry["data_clonedinstanceid"];
									var cloned_widget_id 			= o.attributeAry['data_widgetid'];
									var widget_data_attr			= o.attributeAry;
									var custom_attr					= o.customAttributeAry;

										$.ajax({					
											url			: base_url + admin_folder +"/template_designer/clone_widget_instance",				
											type		: 'post',
											async		:false,
											data		: "cloned_widget_instance="+ reference_widget_instance +"&cloned_widget_id="+ cloned_widget_id,
											dataType	: "json",
											beforeSend	: function (data) {									
															$("#loading_msg").html("Please wait...");
															$("#commom_loading").show();	
											},
											success		: function (data) {												
												//console.log(data.cloned_widget_from);
												$("#loading_msg").html("");
												$("#commom_loading").hide();
												if(data.show_msg == 1){
													show_toastr(data.msg, data.msg_type);	
												}
												if(data.res_status == 1){
													widget_path 		= widget_data_attr['data_widgetfilepath'].split('/'); 
													widget_path_string	= widget_path[widget_path.length-1];
													cloned_widget_name	= data.cloned_widget_from;
													
													var clone_content = '<li data-widgetid="'+ widget_data_attr['data_widgetid'] +'" data-widgetname="'+ widget_data_attr['data_widgetname'] +'" data-minimumcontent="'+ widget_data_attr['data_minimumcontent'] +'" data-maximumcontent="'+ widget_data_attr['data_maximumcontent'] +'" data-contenttype="'+ widget_data_attr['data_contenttype'] +'" data-widgetfilepath="'+ widget_data_attr['data_widgetfilepath'] +'" data-widgetstyle="'+ widget_data_attr['data_widgetstyle'] +'" data-renderingtype="'+ widget_data_attr['data_renderingtype'] +'" data-isrelatedarticleavailable="'+ widget_data_attr['data_isrelatedarticleavailable'] +'" data-iswidgettitleconfigurable="'+ widget_data_attr['data_iswidgettitleconfigurable'] +'" data-issummaryavailable="'+ widget_data_attr['data_issummaryavailable'] +'" data-createdby="'+ widget_data_attr['data_createdby'] +'" data-createdon="'+ widget_data_attr['data_createdon'] +'" data-modifiedby="'+ widget_data_attr['data_modifiedby'] +'" data-modifiedon="'+ widget_data_attr['data_modifiedon'] +'" data-status="1" data-clonedstatus="1" data-widgetthumbnailpath="'+ widget_data_attr['data_widgetthumbnailpath'] +'"  data-clonedinstanceid="'+ reference_widget_instance +'" data-cloned_instance_id="'+ reference_widget_instance +'" data-clonedFrom="'+ cloned_widget_name +'" class="clone-widget ui-draggable ui-draggable-handle" style="display: list-item;">'+ widget_data_attr['data_widgetname'] +"(" + cloned_widget_name + ")" +'<a href="#widgetmodal21" onclick="show_widget_image(\'widgetmodal21\',\''+ widget_path_string +'\')"><i class="fa fa-desktop i_extra"></i></a> <br> <img src="'+ template_design_images_url + widget_data_attr['data_widgetthumbnailpath'] +'" style="display:none;"></li>';   
													
													$('#widget_list').append(clone_content);
													$("#widgetHolder ul li" ).draggable({
														appendTo: "body",
														helper: "clone"
													});
												}
													
											},
											error: function (e) {
												//alert("error");	
												$("#loading_msg").html("");
												$("#commom_loading").hide();	
											}
										});
								}
							}
					});
				}			
		},
		copyWidgetConfig: function(ui){
			var widgetObject =  ui.target.siblings(".widget-container").find(".widget");
			var widgetObjectButtons =  ui.target.siblings(".widget-container").siblings(".add-widget");										
			var widgetClone = widgetObject.clone(true);
			var attributeSetStr = [].filter.call($(widgetObject)[0].attributes, function(at) { return /^data-/.test(at.name); });
			var customAttributeStr = [].filter.call($(widgetObject)[0].attributes, function(at) { return /^cdata-/.test(at.name); });
			var widgetTabStr = [].filter.call($(widgetObject)[0].attributes, function(at) { return /^widgettab/.test(at.name); });		
			var customConfig = widgetObject.data("customConfig") ? widgetObject.data("customConfig") : '';			
			var widgetData  = {};
			widgetData.customAttributeAry = {};
			$.each($(widgetObject)[0].attributes, function(i,v){
						if (/^cdata-/.test(this.name)) {
							var name = (this.name).replace("-","_");
							var value = this.value;
							widgetData.customAttributeAry[name]= value;
						}
			});
			widgetData.attributeAry = {};
			$.each($(widgetObject)[0].attributes, function(i,v){
						if (/^data-/.test(this.name)) {
							var name = (this.name).replace("-","_");
							var value = this.value;
							widgetData.attributeAry[name]= value;
						}
			}); 
			widgetData.customConfig = customConfig;
			widgetData.widgetHTML =  ui.target.siblings(".widget-container").html();
			
			widgetData.addButtonState = widgetObjectButtons.find(".add-widget-button").attr("style");
			widgetData.configButtonState = widgetObjectButtons.find(".config-widget-button").attr("style");
			widgetData.removeButtonState = widgetObjectButtons.find(".remove-widget-button").attr("style");		
			
			widgetData.widget_title		 = (widgetData.customConfig.customTitle &&  widgetData.customConfig.customTitle != "undefined" )? widgetData.customConfig.customTitle : widgetData.attributeAry['data_widgetname'];
			
			var  widgetDataStr = JSON.stringify(widgetData);			
			return widgetDataStr;
			//console.log(admin_folder);
		},
		loadWidgets: function() {			
			//Yet to be defined			
		},
		searchOnWidgetJson: function(widgetId){				
			//console.log(TemplateManagerObj.$widgetsJSON);
			var source = TemplateManagerObj.$widgetsJSON;
			//var source = JSON.parse(s);				
			var results = [];
			var searchField = "widgetId";
			var searchVal = widgetId;
			for (var i=0 ; i < source.widgetHolder.length ; i++)
			{
				if (source.widgetHolder[i][searchField] == searchVal) {
					results.push(source.widgetHolder[i]);
					break;
				}
			}
			source = null;
			return results;		
		},
		
		
		// Drag and drop widget into container - create widget instance
		attachDroppableEvent: function(id){			
			//$("div.tpl-col-container .widget-container" )
				//	TemplateManagerObj.$selectedTemplateColumnContainer.find("#container-" + id + " .widget-container").droppable({
				TemplateManagerObj.$masterContainer.find(".widget-container").droppable({
				activeClass: "ui-state-default",
				hoverClass: "ui-state-hover",
				//accept: "div.widget-item",
				accept: "div.widgetListContainer ul li",
				// accept: ":not(.ui-sortable-helper)",
				drop: function( event, ui ) {
					//alert(this);
					
				  var isWidgetDrop;				  
				  var check_cloned_instance_id 		=  ui.draggable[0].attributes['data-clonedinstanceid'].value;
				  check_cloned_instance_id			= (typeof check_cloned_instance_id === 'undefined' || check_cloned_instance_id === 'undefined') ? "" : check_cloned_instance_id;
				  if(check_cloned_instance_id){
					  // Verify if this cloned-widget widget is active or not.						
					  var can_create_widget = TemplateManagerObj.verify_clone_mapping(check_cloned_instance_id, "create");
					  if(can_create_widget == 0){
						  isWidgetDrop = false;
						  return false;
					  }else{
						  isWidgetDrop = true;
					  }
					}else{
					  isWidgetDrop = true;
					}
					
				  if($(this).find("div.widget").length != 0){ 
					
					if(IExp.pageMaster.sectionId == 10001){						
						// Verify if this widget is parent of clone widgets and used in any other templates.
						var replace_instance_id = $(this).find("div.widget").attr("data-widgetinstanceid");
						var is_mapped_with_other_template = TemplateManagerObj.verify_clone_mapping(replace_instance_id, "replace");						
					}else{
						var is_mapped_with_other_template = false;
					}					
					if(is_mapped_with_other_template){ 
						return false;
					}else{
						if(confirm("This container having widget, do you want to replace")){
							isWidgetDrop = true;
						}else{
							isWidgetDrop = false;
						}
					}					
				  }
				  else
				  {
					isWidgetDrop = true;
				  }
					
				  if(isWidgetDrop)
				  {					
					//Drop the widget after creating widget instance
					$(this).empty();	
					var current_div_elem = $(this);	
					var widgetContentType = "";
					var renderingType = "";
					var attributeSetStr = [];
					var attributeSetStr = [].filter.call(ui.draggable[0].attributes, function(at) { return /^data-/.test(at.name); }); 
					$("<div></div>").addClass("widget").appendTo(this);
					$.each(attributeSetStr, function() {
						var name = this.nodeName;
						var value = this.nodeValue;
						if(name == "data-contenttype" )
						{
							widgetContentType = this.nodeValue;
							//alert(this.nodeValue);
						}
						if(name == "data-renderingtype" )
						{
							renderingType = this.nodeValue;
							//alert(this.nodeValue);
						}
					});	
					if (renderingType == 3){
						$(this).siblings("div.add-widget").find(".remove-widget-button").show().end().find(".config-widget-button").show().end().find(".view-widget-button").show();
					} else {
						
						if(widgetContentType == 1) ///// Means widget ContentType is None
						{
							$(this).siblings("div.add-widget").find(".remove-widget-button").show();
						}
						else {
							$(this).siblings("div.add-widget").find(".add-widget-button").css({"pointer-events": "auto", "color":"#000"});							
							$(this).siblings("div.add-widget").find(".remove-widget-button").show().end().find(".config-widget-button").show().end().find(".add-widget-button").show().end().find(".view-widget-button").show();
						}
					}
					$(this).find(".widget").append('<h4>' + ui.draggable.text() + '</h4>');
						
					var widgetDiv =  $(this).find(".widget"); 
					$.each(attributeSetStr, function() {
						var name = this.nodeName;
						var value = this.nodeValue;
						widgetDiv.attr(name,value);					
					});			
					widgetDiv.attr("data-widgetcontainerorderid",$(this).attr("data-widgetcontainerorderid"));					
					widgetDiv.attr("data-widgetpageId",IExp.pageMaster.pageId);					
					//Load thumbnail image for widget
					$(this).find(".widget").append('<img src="' + template_design_images_url + widgetDiv.attr("data-widgetthumbnailpath") + '">' );
					var that = $(this).parents("div.wc");
					var cloned_instance_id 	= widgetDiv.attr("data-clonedinstanceid");
					cloned_instance_id		= (typeof cloned_instance_id === 'undefined' || cloned_instance_id === 'undefined') ? "" : cloned_instance_id;
					//console.log(cloned_instance_id);
					
					if(cloned_instance_id){
						$(this).siblings("div.add-widget").find(".remove-widget-button").show().end().find(".config-widget-button").hide().end().find(".add-widget-button").hide();
					}
					
					$.ajax({					
					url: base_url + admin_folder +"/template_designer/create_widget_instance",				
					type: 'post',
					async:false,
					data: "page_id=" + IExp.pageMaster.pageId + "&pageType=" + IExp.pageMaster.pageType + "&sectionId=" + IExp.pageMaster.sectionId + "&containerId=" +  $(this).parents("div.container-wrapper").attr("id") + "&widgetId=" + widgetDiv.attr("data-widgetid")+ "&widgetOrderIdInContainer=" + $(this).attr("data-widgetcontainerorderid") + "&widgetStyle=" + widgetDiv.attr("data-widgetstyle") + "&versionId=" + IExp.pageMaster.versionId +"&cloned_instance_id="+ cloned_instance_id,
					beforeSend: function (data) {						
							$.blockUI({ 
							message: '<h1 style="padding:5px;font-size:12px">Loading Template...</h1>',
							css: { border: '1px solid #a00' } 
						});
					},
					success: function (data) {												
						jsonData = JSON.parse(data);
						if(jsonData.widget_instance_id != ''){
							widgetDiv.attr("data-widgetinstanceid",jsonData.widget_instance_id);
							
						}else{
							current_div_elem.empty();
							current_div_elem.siblings("div.add-widget").find(".remove-widget-button").remove().end().find(".config-widget-button").remove().end().find(".add-widget-button").remove();
						}												
					},
					complete: function(){
						$.unblockUI();
					
					},
					error: function (e) {
						show_toastr("Error in creating new widget, please don't save this version template", 2);	
						//current_div_elem.empty();
						//current_div_elem.siblings("div.add-widget").find(".remove-widget-button").remove().end().find(".config-widget-button").remove().end().find(".add-widget-button").remove();	
					}
					});
					$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
				 }
			   }
				
			});
			
			
			
		},		
		attachDroppableEvent2 : function(id){
			//$("div.tpl-col-container .widget-container" )
				//	TemplateManagerObj.$selectedTemplateColumnContainer.find("#container-" + id + " .widget-container").droppable({
					
				TemplateManagerObj.$selectedTemplateColumnContainer.find(".widget-container ul ").droppable({
				activeClass: "ui-state-default",
				hoverClass: "ui-state-hover",
				accept: "#site-contents .content-item",
				// accept: ":not(.ui-sortable-helper)",
				drop: function( event, ui ) {
					//alert(this);
					//$(this).empty();
					$("<li>").addClass("content").attr("data-content-id", ui.draggable.attr('data-content-id')).attr("data-content-title", ui.draggable.attr('data-content-title')).html( ui.draggable.text() +'<a class="remove-content fa fa-remove"></a></li>' ).appendTo( this );
				}
			});
			
		},		
		publish_template: function(){
			
				/* Verify is any widget (configuration or add widget articles) is locked */
				var is_lock_free_template_version = TemplateManagerObj.is_lock_free_template_version();					
				if(is_lock_free_template_version == 1)
				{
					var live_version_id = $('#live_version_id').val();
					
					var confirm_msg = (IExp.pageMaster.versionId == live_version_id || live_version_id == '')? "Are sure you want to publish current template?" : "You have selected an another version of the template. Please make sure all widgets are updated with latest content while publishing. Are you sure you want to publish current template?";				
					 
					 if(confirm(confirm_msg))
					 {				
							var templateXMLData = TemplateManagerObj.getItems2();				
							if (templateXMLData.length > 0 && IExp.pageMaster.pageId && IExp.pageMaster.templateId ){								
							var common_header				= ($('#use_common_header').prop('checked'))? 1 : 0;
							var common_rightpanel 			= ($('#use_common_rightpanel').prop('checked'))? 1 : 0;
							var common_footer				= 	($('#use_common_footer').prop('checked'))? 1 : 0;
							var use_parent_section_template	= 	($('#use_parent_section_template').prop('checked'))? 1 : 0;	
							$.ajax({
								url: base_url + admin_folder +"/template_designer/save_xml_template",	
								type: 'post',
								data: "tempStr=" + encodeURI(templateXMLData) + "&pageId=" + IExp.pageMaster.pageId + "&templateId=" + IExp.pageMaster.templateId + "&publish_xml=publish&header="+ common_header + "&rightpanel=" + common_rightpanel + "&footer=" + common_footer+"&template_commit_status=1&VersionId=" + IExp.pageMaster.versionId + "&use_parent_section_template=" + use_parent_section_template,
								beforeSend: function() {
								$("#loading_msg").html("Please wait, publishing template...");
								$("#commom_loading").show();
								}, 						
								success: function (data) {
									$("#commom_loading").hide();					
									$("#loading_msg").html("");
									var node = $("#tree").fancytree("getActiveNode");						
									node.data.hastemplate = 1;
									IExp.pageMaster.editInProgress = false; //check for edit in progress
									
									/////  After published lock the current template design
									TemplateManagerObj.update_template_lock(1)	
									if(IExp.pageMaster.sectionId != 10001){ TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, 'new'); }
									if(data.show_msg == 1){
										show_toastr(data.msg, data.msg_type);
										
										
										var pagesection_name ='';
										var active_section_name = '';
										var pagesection_name = $(".fancytree-active").parents('li').find('.fancytree-expanded').text();
										var active_section_name = $(".fancytree-active").find('.fancytree-title:first').text();	
										if((pagesection_name=="Newsletter")&&(active_section_name!="Newsletter")){				 
											 if(live_version_id == IExp.pageMaster.versionId && live_version_id != ''){				
												$('#copy_source').show();
											}else{
												$('#copy_source').hide();
											}				
										}else
										{
											$('#copy_source').hide();	
										}
										
									}
									TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
									TemplateManagerObj.show_not_published_info(0);//1->not-published, 0->published
								},
								error: function (e) {
									//alert("error");						
									TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
									TemplateManagerObj.show_not_published_info(1);//1->not-published, 0->published
								}
							});															
							}else{
								$("#xmlContainer").text("Please start building the template.");					
							}
						}
						else
						{
							return false;
						}
					}
		},
		//////  Load Page Template versions  //////
		loadTemplateVersions: function(page_id, default_version){			
			$.ajax({
					url 	: base_url + admin_folder +"/template_designer/load_template_versions",
					type 	: 'post',
					async	: false,
					data 	: 'template_id=' + page_id + "&default_version=" + default_version +"&requested_version_id="+ IExp.pageMaster.query_string_versionId,
					success	: function(data){
								IExp.pageMaster.query_string_versionId = '';
								//if(IExp.userRole.canDesignPage){																		
									$('#template_version_dropdown').html(data);				
									var live_version_id = $('#live_version_id').val();
									if(live_version_id == '')
									 {
										 $('#delete_currentVersion_template').hide();
									 }					
									IExp.pageMaster.versionId = $( "#show_template_version :selected" ).val();									
									$('#saveTemplate').hide();
									///////  Template doesn't having version / template xml
									if( typeof IExp.pageMaster.versionId === 'undefined'){ $('#saveTemplate').hide(); $('#publish_template').hide(); $('#new_empty_version').hide(); }
									else{$('#publish_template').show(); $('.template_preview').show(); $('#common_options').show(); 
										//$('#saveTemplate').show(); 
									} 
									//alert(live_version_id +", current_page_version: " +IExp.pageMaster.versionId);
									///////  Template having versiong  //////
									if(live_version_id == IExp.pageMaster.versionId){$('#publish_advertisements').show(); $("#make_version").show(); $('#delete_currentVersion_template').hide();
									 		
									}
									else{ 
											
											$('#publish_advertisements').hide(); 											
											if(live_version_id){ $("#make_version").show(); $('#delete_currentVersion_template').show(); }
											else{ $("#make_version").hide(); $('#delete_currentVersion_template').hide();  }
										}
		//publish_advertisements									
									$( "#show_template_version" ).change(function() {										
										var commit_status = TemplateManagerObj.get_template_commit_status();		
										if(commit_status.tempalte_commit_status == 1) //////  1 means template is commited  /////
										{
											$('#saveTemplate').hide();
											IExp.pageMaster.versionId = this.value;																				
											if(live_version_id == IExp.pageMaster.versionId){$('#publish_advertisements').show(); $('#delete_currentVersion_template').hide();  }
											else{$('#publish_advertisements').hide(); $('#delete_currentVersion_template').show();}
											//////  Load Version based XML template  /////
											TemplateManagerObj.load_version_template(this.value, page_id);
										}
										else
										{
											$('.template_preview').hide(); 											
											//////  Prompt user to save template or not save  ///			
											$( "#confirm-1" ).dialog( {
													resizable	: false,
													modal		: true,
													title		: "Do you want to save changes?",
													height		: 250,
													width		: 325,
													buttons		: {
																	"Save": function() {
																		IExp.pageMaster.versionId = $( "#show_template_version :selected" ).val();
																		var do_below_functions = {"" : $("#saveTemplate").trigger( "click", "Advertisement" ), "": TemplateManagerObj.load_version_template(IExp.pageMaster.versionId, page_id)};
																		TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);
																	},
																	"Don't save": function() {		
																		//alert("Dont save template");
																		IExp.pageMaster.versionId = $( "#show_template_version :selected" ).val();																			
																		var do_below_functions = { "" : TemplateManagerObj.dont_save_template(page_id), "": TemplateManagerObj.load_version_template(IExp.pageMaster.versionId, page_id)};
																		TemplateManagerObj.dialog_callback(2, $(this), do_below_functions);	
																	},
																	Cancel: function() {
																		
																		TemplateManagerObj.dialog_callback(3, $(this), $('#show_template_version option[value='+IExp.pageMaster.versionId+']').prop('selected', true));	
																	}
																  }
										  });	
										  $(".ui-dialog-buttons").addClass("save-dialog-box");
										  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
										  
										}
										
									});
								  //}
								},
					error	: function(e){
								//// Loading version internal error ////
								IExp.pageMaster.query_string_versionId = '';
								}
			});
		},		
		loadTemplates: function(templateId, PageId){
			var templateString = null;
			templateString = IExp.templateMaster[templateId];		
			$("a.dynamic_design_link").attr("href", base_url+admin_folder +"/template_designer/load_saved_template/" + PageId + "/"+ IExp.pageMaster.versionId);					
			$("a.static_design_link").attr("href", base_url+admin_folder +"/template_designer/load_saved_template/" + PageId + '-static');					
			
			return templateString;			
		},
		loadTemplateContainers: function(id, type){			
			var templateString = null; 			
			var str = IExp.widgetContainerMaster[type];
			var pattern1 = /\#containerId\#/gi;			
			var pattern2 = /\#containerTypeId\#/gi;			
			str = str.replace(pattern1, id);			
			str = str.replace(pattern2, type);		
			templateString = str;		
			return templateString;		
		},				
		doHightlight: function($elem){
			//TemplateManagerObj.$templateColumnContainer.removeClass('highlighted');
			$("div.tpl-col-container").removeClass('highlighted');
			$elem.addClass('highlighted');		
		},	
		capitalizeFirstLetter: function(string)	{
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
		loadWidget: function(widgetObject,widgetContainerId, newsIdList){
				var pane = "";
				var attributeStr = "";
				//console.log(widgetObject);
				var h4_show_max_articles = ( typeof widgetObject.cdata_renderingMode === 'undefined' || widgetObject.cdata_renderingMode === 'undefined' || widgetObject.data_renderingtype == 3 ) ? "" : " - "+ TemplateManagerObj.capitalizeFirstLetter(widgetObject.cdata_renderingMode) +" Mode("+ ((widgetObject.cdata_customMaxArticles != '') ? widgetObject.cdata_customMaxArticles +")" : '0)');
				
				var h4_publish_on = (typeof widgetObject.cdata_widgetpublishOn === 'undefined' || widgetObject.cdata_widgetpublishOn === 'undefined' || widgetObject.cdata_widgetpublishOn == '') ? "" : "-P On("+ widgetObject.cdata_widgetpublishOn +")";
														 
				var h4_publish_off = (typeof widgetObject.cdata_widgetpublishOff === 'undefined' || widgetObject.cdata_widgetpublishOff === 'undefined' || widgetObject.cdata_widgetpublishOff == '') ? "" : "-P Off("+ widgetObject.cdata_widgetpublishOff +")";
				 
				var h4_active_status = (typeof widgetObject.cdata_widgetStatus === 'undefined' || widgetObject.cdata_widgetStatus === 'undefined' || widgetObject.data_renderingtype == 3) ? "" :((widgetObject.cdata_widgetStatus == '1') ? "- <span style='color:#008000; font-weight:bold;'>Active</span>" : "- <span style='color:#d0401c; font-weight:bold;'>Inactive</span>");
				var h4_combine_text = "";
				h4_combine_text = h4_show_max_articles + h4_publish_on + h4_publish_off + h4_active_status;
				
				
				var widgetTitle = (widgetObject.cdata_customTitle &&  widgetObject.cdata_customTitle != "undefined" )? widgetObject.cdata_customTitle + h4_combine_text : widgetObject.data_widgetname + h4_combine_text;
								
				var widgetThumbnailPath = (widgetObject.data_widgetthumbnailpath  &&  widgetObject.data_widgetthumbnailpath != "undefined" ) ? widgetObject.data_widgetthumbnailpath  : "";	
				
				
				switch(widgetObject.data_widgetstyle){
					//// Widget style: Normal
					case "1":					
						$.each(widgetObject, function(index,value){
							attributeStr = attributeStr + index.replace(/_/g,"-") + '="' + value + '" ';
						});							
						pane = pane + '<div class="widget" '+ attributeStr + ' >';
						pane = pane + '<h4>'+ widgetTitle + '</h4>' + ((widgetThumbnailPath) ? '<img src="' + template_design_images_url + widgetThumbnailPath + '"/>' : '') + '<ul></ul>' ;
						pane = pane + '</div>' ; 
							
					// $(this).siblings("div.widget").find(".remove-widget-button").show().end().find(".config-widget-button").show();
					
					// $(this).find(".widget").append('<h4>' + ui.draggable.text() + '</h4>');
					// $(this).find(".widget").append('<ul class="sortable news-content">' + '</ul>');
					// var widgetDiv =  $(this).find(".widget"); 
					// $.each(attributeSetStr, function() {
						// var name = this.nodeName;
						// var value = this.nodeValue;
						// widgetDiv.attr(name,value);					
					// });			
						
						
						
					break;
					//// Widget style: simple tab
					case "2":
						$.each(widgetObject, function(index,value){
							attributeStr = attributeStr + index.replace(/_/g,"-") + '="' + value + '" ';
						});							
						pane = pane + '<div class="widget" '+ attributeStr + ' >';
						//pane = pane + '<h4>'+ widgetTitle + '</h4><ul></ul>' ;
						pane = pane + '<h4>'+ widgetTitle + '</h4>' + ((widgetThumbnailPath) ? '<img src="' + template_design_images_url + widgetThumbnailPath + '"/>' : '') + '<ul></ul>' ;
						pane = pane + '</div>' ; 
					break;
					//// Widget style: nested tab
					case "3":
						$.each(widgetObject, function(index,value){
							attributeStr = attributeStr + index.replace(/_/g,"-") + '="' + value + '" ';
						});							
						pane = pane + '<div class="widget" '+ attributeStr + ' >';
						//pane = pane + '<h4>'+ widgetTitle + '</h4><ul></ul>' ;
						pane = pane + '<h4>'+ widgetTitle + '</h4>' + ((widgetThumbnailPath) ? '<img src="' + template_design_images_url + widgetThumbnailPath + '"/>' : '') + '<ul></ul>' ;
						pane = pane + '</div>' ; 
					break;					
				}
				return pane;
			
		},
		addWidget: function(widgetId, widgetTitle, widgetContainerId, newsIdList,widgetType){		
			
			//var newsIdList = ['NewsID:1234','NewsID:4567','NewsID:8910'];		
			//var newsIdList = [];	
			var classname ="add-content";
			if (widgetType == "tab") classname = "add-tab";
			
			var pane = '<div class="widget" id="' + widgetContainerId +'" data-widget-id="'+widgetId +'" data-widget-title="'+widgetTitle +'">'+
					'<h4>' + widgetTitle + '<a class="' + classname + ' fa fa-plus-square" data-parent-container-id ="'+ widgetContainerId +'"></a></h4>' ;
			if (widgetType == "tab"){				
				pane +=  '<div class="tab-container"></div>';				
			} else {
				pane = pane + '<ul class="sortable news-content">';
				if(newsIdList){
					$.each(newsIdList, function(index, value ) {
						pane = pane + '<li data-content-id="' + value[0] + '" data-content-title="' + value[1] + '">' + value[1] + '<a class="remove-content fa fa-remove"></a></li>';
					});
				}
				pane += '</ul>';
			}

			pane += '</div>';		
			
			//$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
			//TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, '');
			
			return pane;
		},
		getItems2: function(){	
			var columns = [];
			var str = "";
			var $tplContainer =$("div.tpl-col-container");
			str = str + '<?xml version="1.0" encoding="UTF-8"?>\n';
			str = str + '<template templateid = "' + IExp.pageMaster.templateId + '" pageid = "' + IExp.pageMaster.pageId +'" templatevalues="'+ IExp.templateMasterValues[IExp.pageMaster.templateId] +'">';	
			$.each($tplContainer, function(i,value){				
				str = str + '<tplcontainer name="' + $($tplContainer[i]).attr("name") + '" master-tcid="' + $($tplContainer[i]).attr("data-tcid") + '">\n';
				var $container = $($tplContainer[i]).children('.container-wrapper');
				$.each($container, function(j,value){
					str = str + '<widgetcontainer type = "'+ $($container[j]).attr("data-type") +'" id ="' + $($container[j]).attr("id") + '"  containervalue="'+ IExp.widgetContainerValues[$($container[j]).attr("data-type")] +'">\n';
					var $column = $($container[j]).find('.wc');					
					$.each($column, function(k, value){	
						//var  widgetOrderId = $($column[k]).find('div.widget-container').attr("data-widgetcontainerorderid") ;
						//str = str + "<widgetwrapper orderno='"+ widgetOrderId+"'>";
						var $widget = $($column[k]).find('div.widget');					
						$.each($widget, function(l, value){							
							//var widgetObject = $elem.parent().siblings(".widget-container").find("div.widget");
							
							var attributeSetStr = [].filter.call($($widget[l])[0].attributes, function(at){ 
							return /^data-/.test(at.name); });
							var attrStr = " ";
							$.each(attributeSetStr, function(index, value){
								var name = this.nodeName;
								var value = (this.nodeValue) ? this.nodeValue : '';
								attrStr = attrStr + name + '="' + value + '" ';								
							});
							str = str + '<widget id ="' + $($widget[l]).attr("data-widgetid") + '" widgetTitle ="' + $($widget[l]).attr("data-widgetname") + '" widgetorder_in_container ="' +  (k+1) + '"';
							
							//alert($($widget[l]).attr("data-widget-instance-id"));
							widget_instance = "";
							//alert("container id: "+ $($container[j]).attr("id") + " == widget id: " +$($widget[l]).attr("data-widgetid") + "widget-order: " + (k+1));
							if(typeof($($widget[l]).attr("data-widgetinstanceid")) === 'undefined')
							{

								/*$.ajax(
								{
									url: base_url + "admin/template_designer/widget_instance",
									type: "post",
									dataType: "json",
									async: false,
									data: { "page_id": IExp.pageMaster.pageId, "container_id" : $($container[j]).attr("id"), "widget_id": $($widget[l]).attr("data-widgetid"), "widget_disp_order": (k+1) },
									success: function(result){
										console.log(result);								
									widget_instance =  ' data-widget-instance-id ="' +  result['widget_instance'] + '"';
								}});*/
							
							}
							else 
							{
								
								
								// instance initialized
								//alert('Instance Id inialized : ' + $($widget[k]).attr("data-widget-instance-id"));								
							}
							
							
							str = str + widget_instance;
							
							str = str + attrStr;		
							var customAttributeStr = "";		
							var customConfig;
							if ($($widget[l]).data("customConfig")){
								var customConfig = $($widget[l]).data("customConfig");		
								//console.log($widget[l]);
								customAttributeStr = customAttributeStr + 'cdata-customBgColor="' + customConfig.customBgColor + '" ' + 'cdata-customMaxArticles="' + customConfig.customMaxArticles + '" ' + 'cdata-customTitle="' + customConfig.customTitle + '" ' + 'cdata-showSummary="' + customConfig.showSummary + '" ' + 'cdata-iframeUrl="' + escape(customConfig.iframeUrl) + '" '+'cdata-renderingMode="' + customConfig.renderingMode + '" ' + 'cdata-separatorRequired="' + customConfig.separatorRequired + '" ' + 'cdata-widgetCategory="' + customConfig.widgetCategory + '" ' + 'cdata-widgetpublishOn="' + customConfig.widgetpublishOn + '" ' + 'cdata-widgetpublishOff="' + customConfig.widgetpublishOff + '" ' + 'cdata-widgetStatus="' + customConfig.widgetStatus + '" ' + 'cdata-isCloned="' + customConfig.isCloned + '" ';
							}								
							
							str = str + customAttributeStr;
							str = str + ' >\n';
							
							if ($($widget[l]).data("customConfig")){
									
								if(	customConfig.simpleTab.categoryList.length > 0){
									//console.log(" &*&*&&*& : ", customConfig.simpleTab.categoryList);
									
									$.each(customConfig.simpleTab.categoryList, function(index,value){
										str = str + '<widgettab cdata-categoryId="' + this.categoryId	+ '" cdata-categoryName="' + this.categoryName	+ '" cdata-customTitle="' + this.customTitle + '" cdata-categoryType="' + this.categoryType	+ '" cdata-categoryTypeName="' + this.categoryTypeName	+ '" ></widgettab>';	
									});								
								}
								if(	customConfig.nestedTab.categoryList.length > 0){
									$.each(customConfig.nestedTab.categoryList, function(index,value){
										str = str + '<widgettab cdata-categoryId="' + this.categoryId	+ '" cdata-categoryName="' + this.categoryName	+ '" cdata-customTitle="' + this.customTitle	+ '" cdata-categoryType="' + this.categoryType	+ '" cdata-categoryTypeName="' + this.categoryTypeName	+ '"  >';
											if(this.childCategory.length > 0){
												$.each(this.childCategory, function(index,value){
													str = str + '<widgetchildtab cdata-categoryId="' + this.categoryId	+ '" cdata-categoryName="' + this.categoryName	+ '" cdata-customTitle="' + this.customTitle	+ '" cdata-categoryType="' + this.categoryType	+ '" cdata-categoryTypeName="' + this.categoryTypeName	+ '"  ></widgetchildtab>';
												});
											}
										str = str + '</widgettab>';	
									});								
								}						
							}
							var $widgetNewsCollection = $($widget[l]).find('ul.sortable li');
							if($widgetNewsCollection.length > 0){
								$.each($widgetNewsCollection, function(m, value){						
										str = str + '<article articleId="' + $($widgetNewsCollection[m]).attr('data-content-id') + '"  articleTitle="' + $($widgetNewsCollection[m]).text() + '">' + '</article>\n';
								});
							}
							str = str + '</widget>\n';
						});
						//str = str + '</widgetwrapper>\n';

					});					
					str = str + '</widgetcontainer>\n';							
				});
				str = str + '</tplcontainer>\n';					
							
			});
			str = str + '</template>\n';		
			customConfig = null;			
			return str;			
		},
		getItems : function(){	
			var columns = [];
			var str = "";
			var $tplContainer =$("div.tpl-col-container");
			str = str + '<?xml version="1.0" encoding="UTF-8"?>\n';
			str = str + '<template>';	
			$.each($tplContainer, function(i,value){
				str = str + '<tplcontainer name="' + $($tplContainer[i]).attr("name") + '">\n';
				var $container = $($tplContainer[i]).children('.container-wrapper');
				$.each($container, function(i,value){
					str = str + '<widgetcontainer id ="' + $($container[i]).attr("id") + '">\n';
					var $column = $($container[i]).children('.column');					
					$.each($column, function(i, value){	
						var $widget = $($column[i]).find('div.widget');					
						$.each($widget, function(i, value){
							str = str + '<widget id ="' + $($widget[i]).attr("id") + '" widgetTitle ="' + $($widget[i]).find("h4").text() + '">\n';
							var $widgetNewsCollection = $($widget[i]).find('ul.sortable li');
							$.each($widgetNewsCollection, function(i, value){						
									str = str + '<article>' + $($widgetNewsCollection[i]).text() + '</article>\n';
							});
							str = str + '</widget>\n';
						});

					});					
					str = str + '</widgetcontainer>\n';							
				});
				str = str + '</tplcontainer>\n';					
							
			});
			str = str + '</template>\n';		
			$("#xmlContainer").text(str);		
			
			// $(exampleNr + ' div.container-wrapper').each(function(){			
				// columns.push($(this).attr('id'));				
			// });
			// return columns.join('|');	
		},
		showCommonTemplate_options: function(pageId, versionId, xml_string, is_from_loadtemplate)
		{
			var show_html_obj = [];
			var hide_html_obj = [];
			if(IExp.pageMaster.sectionId != 10001){
				if(xml_string == '' && is_from_loadtemplate == 'from_loadtemplate'){
					$('#common_options').html("");
					hide_html_obj.push('#common_options', '.header_script_publish', '#edit_version_name', '#make_version', '#delete_currentVersion_template',  '#new_empty_version');
					TemplateManagerObj.hide_html_elements(hide_html_obj);
				}else{
					
					$.ajax({ // template checkbox part start
								url: base_url + admin_folder +"/template_designer/get_template_fromdetails",				
								data: "pageId=" + pageId +"&versionId=" + versionId,
								dataType: 'html',
								async: false,
								cache: false,
								success: function(result){
									//console.log("hello world: "+result);
									//$('#common_options').html("");
									//alert("afasdf");
								$('#common_options').html(result);
									$('.common_options_checkbox').click(function(){								
										$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save	
									});
								} 
					}); // template checkbox part end
					
					show_html_obj.push('#common_options', '.header_script_publish', '#edit_version_name', '#make_version', '#show_template_version', '#new_empty_version');		
					TemplateManagerObj.show_html_elements(show_html_obj);
				}
			}else{
				$('#common_options').html("");
				hide_html_obj.push('#common_options', '.header_script_publish', '#edit_version_name', '#make_version', '#delete_currentVersion_template', '#show_template_version', '#new_empty_version');
				TemplateManagerObj.hide_html_elements(hide_html_obj);
				//console.log(IExp.pageMaster.sectionId," adfasdf");
			}
			var live_version_id = $('#live_version_id').val();
			var pagesection_name ='';
			var active_section_name = '';
			var pagesection_name = $(".fancytree-active").parents('li').find('.fancytree-expanded').text();
			var active_section_name = $(".fancytree-active").find('.fancytree-title:first').text();	
			if((pagesection_name=="Newsletter")&&(active_section_name!="Newsletter")){				 
				 if(live_version_id == IExp.pageMaster.versionId && live_version_id != ''){				
					$('#copy_source').show();
				}else{
					$('#copy_source').hide();
				}				
			}else
			{
				$('#copy_source').hide();	
			}
		},
		show_hide_reset_xml: function(template_commit_status, is_new_template){
			if(template_commit_status  == 2){
				if(is_new_template === "undefined" || is_new_template === "" || typeof is_new_template === "undefined"){
					$("#reset_rollback").hide();
				}
				else{
					$("#reset_rollback").show();
				}				
			}else{
				$("#reset_rollback").hide();
			}
		},
		loadTemplate: function(pageId, versionId){
			
			$("#publish_template").removeClass("page-loaded");	
			var show_html_obj = [];
			var hide_html_obj = [];
			
			TemplateManagerObj.$masterContainer.html("");	
			//console.log(IExp.qParamPageId);
			
			$('#IExpWorkSpace').block({ 
				message: '<img src="'+template_design_images_url+'/images/admin/loadingroundimage.gif" style="width:40px; height:40px;" /><br>Progress...',
				css: { border: '1px solid #fff' } 
			}); 
			
			
			$.ajax({
				url: base_url + admin_folder +"/template_designer/load_template",				
				type: 'post',
				data: "pageId=" + pageId+"&versionId=" + versionId,
				//dataType: "xml",
				//async: false,
				dataType: "json",
				success: function(result){	
						var current_version_id 	= $('#show_template_version').val();
						//console.log(result);
						if(result.template_xml)
						{															
						    try{
								isXml = $.parseXML(result.template_xml);
							}catch(e){
								isXml = false;
							}
						if(isXml){
						var xmlString = $.xml2json(result.template_xml, true);
						//var xmlString = result.template_xml;
						var jsonObj = xmlString;						
						//$('.template_preview').show();
						show_html_obj.push('.template_preview', '#common_options', '#addheader_script', '#make_version', '#publish_template');						
						TemplateManagerObj.$masterContainer.html(TemplateManagerObj.loadTemplates(jsonObj.templateid,jsonObj.pageid));
						
						IExp.pageMaster.templateId = jsonObj.templateid;
						IExp.pageMaster.pageId = jsonObj.pageid;
						$.each(jsonObj.tplcontainer, function(i,value){							
							TemplateManagerObj.$selectedTemplateColumnContainer = $("div#tc-"+ this.master_tcid);							
							if(jsonObj.tplcontainer[i].widgetcontainer){
								$.each(jsonObj.tplcontainer[i].widgetcontainer, function(j, value){	
							
									$(TemplateManagerObj.loadTemplateContainers((this.id).split("-")[1], this.type)).appendTo(TemplateManagerObj.$selectedTemplateColumnContainer);		

									if(jsonObj.tplcontainer[i].widgetcontainer[j].widget){
										$.each(jsonObj.tplcontainer[i].widgetcontainer[j].widget, function(k, value){
											var articleAry = [];											
											var r = this;
											var customAttributeAry = [];
											$.each(r, function(i,v){
												if (/^cdata_/.test(i)) {
													var name = i.split("_")[1];
													var value = v;
													customAttributeAry[name] = value;
												}
											}); 

											var additionalProperties = {
												customTitle : '',
												showSummary : '',
												renderingMode :'',
												separatorRequired :'',
												widgetCategory :'',
												iframeUrl :'',
												widgetpublishOn : '',
												widgetpublishOff : '',
												widgetStatus : '',
												simpleTab:{
												categoryList:[]
												},
												nestedTab:{
												categoryList:[]
												}
											};
											
											additionalProperties.customMaxArticles = customAttributeAry["customMaxArticles"];
											additionalProperties.customBgColor = customAttributeAry["customBgColor"];
											additionalProperties.customTitle = customAttributeAry["customTitle"];
											additionalProperties.showSummary = customAttributeAry["showSummary"];
											additionalProperties.renderingMode = customAttributeAry["renderingMode"];
											additionalProperties.widgetCategory = customAttributeAry["widgetCategory"];
											additionalProperties.iframeUrl = customAttributeAry["iframeUrl"];
											additionalProperties.widgetpublishOn = customAttributeAry["widgetpublishOn"];
											additionalProperties.widgetpublishOff = customAttributeAry["widgetpublishOff"];
											additionalProperties.widgetStatus = customAttributeAry["widgetStatus"];
											additionalProperties.isCloned	  = customAttributeAry["isCloned"];

											var jsonWidgetObject = this;
											//Simple Tab
											if (this.data_widgetstyle == "2"){													
												if (jsonWidgetObject.widgettab && jsonWidgetObject.widgettab.length){
													additionalProperties.simpleTab = {};
													additionalProperties.simpleTab.categoryId = jsonWidgetObject.cdata_widgetCategory;
													additionalProperties.simpleTab.categoryList=[];													
													$.each(jsonWidgetObject.widgettab, function(index,value){
															var temp ={};
															temp.categoryId = this.cdata_categoryId;
															temp.categoryName = this.cdata_categoryName;
															temp.customTitle = this.cdata_customTitle;
															temp.categoryType = this.cdata_categoryType;
															temp.categoryTypeName = this.cdata_categoryTypeName;
															additionalProperties.simpleTab.categoryList.push(temp);	
													})
												}
											}											
											//Nested Tab
											if (this.data_widgetstyle == "3"){							
												if (jsonWidgetObject.widgettab && jsonWidgetObject.widgettab.length){
													additionalProperties.nestedTab = {};
													additionalProperties.nestedTab.categoryList=[];
													$.each(jsonWidgetObject.widgettab, function(index,value){
															var temp ={};
															temp.categoryId = this.cdata_categoryId;
															temp.categoryName = this.cdata_categoryName;
															temp.customTitle = this.cdata_customTitle;
															temp.categoryType = this.cdata_categoryType;
															temp.categoryTypeName = this.cdata_categoryTypeName;
															temp.childCategory =[];																
															if (this.widgetchildtab &&  this.widgetchildtab.length > 0 ){
																var widgetChildTab = this.widgetchildtab;		
																$.each(widgetChildTab, function(index,value){
																	var temp2 ={};
																	temp2.categoryId = this.cdata_categoryId;
																	temp2.categoryName = this.cdata_categoryName;
																	temp2.customTitle = this.cdata_customTitle;
																	temp2.categoryType = this.cdata_categoryType;
																	temp2.categoryTypeName = this.cdata_categoryTypeName;
																	temp.childCategory.push(temp2);																		
																});												
															}
															additionalProperties.nestedTab.categoryList.push(temp);
													});
												}
											}
											var widgetObject =  $(TemplateManagerObj.loadWidget(jsonObj.tplcontainer[i].widgetcontainer[j].widget[k]," .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (k+1), articleAry ));
											var widgetContainerOrderId = this.data_widgetcontainerorderid
											widgetObject.appendTo($("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + widgetContainerOrderId));																					
											widgetObject.data("customConfig",additionalProperties);
											//console.log(widgetObject.attr("data-clonedinstanceid"));


											var cloned_instance_id 	= widgetObject.attr("data-clonedinstanceid");
											cloned_instance_id		= (typeof cloned_instance_id === 'undefined' || cloned_instance_id === 'undefined') ? "" : cloned_instance_id;
											//console.log(cloned_instance_id);
											if(IExp.pageMaster.sectionId == 10001){												
												$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.view-child-clone-button").show();
											 }
											
											
											if(cloned_instance_id){
												$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings("div.add-widget").find(".remove-widget-button").show().end().find(".config-widget-button").hide().end().find(".add-widget-button").hide();
												
												var cloned_widget_name 	= widgetObject.attr("data-clonedfrom");
												cloned_widget_name		= (typeof cloned_widget_name === 'undefined' || cloned_widget_name === 'undefined') ? "" : cloned_widget_name;
												if(cloned_widget_name !=""){
													widgetObject.find("h4").append(" ("+cloned_widget_name+ ")");
												}
											}else{	
												
												if(this.data_contenttype == 1) ///// Means widget ContentType is None
												{																																		
													$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.remove-widget-button").show();											
												}
												else
												{												
													if(additionalProperties.renderingMode == 'auto')
													{
														var disable_add_button = {"pointer-events" : "none", "color" : "#ccc"};
													}
													else if(additionalProperties.renderingMode == 'manual')
													{
														var disable_add_button = {"pointer-events" : "auto", "color" : "#000"};
													}
													else
													{
														var disable_add_button = {"":""};
													}
													if($("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.remove-widget-button").size()){
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.remove-widget-button").show();
													}												
													if (this.data_renderingtype != 3){
														if($("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.add-widget-button").size()){
															$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.add-widget-button").show();														
															$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.add-widget-button").css(disable_add_button);
														}
													}												
													if($("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.remove-widget-button").size()){
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.remove-widget-button").show();
													}												
													if(IExp.userRole.canConfigWidget && this.data_renderingtype != 3){													
														if($("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.config-widget-button").size()){
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.config-widget-button").show();	
														}
													}								
													if (IExp.userRole.canAddAdv && IExp.userRole.canConfigWidget){ 																							
														if (this.data_renderingtype != 3){
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.add-widget-button").show();
														
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.add-widget-button").css(disable_add_button);
														}																							
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.remove-widget-button").show();													
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.config-widget-button").show();																	
														$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.view-widget-button").show();															
													} else {
														if (IExp.userRole.canAddAdv &&  IExp.userRole.canConfigWidget == false){										
																if (this.data_renderingtype == 3){
																	$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.config-widget-button").show();
																	$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.add-widget-button").hide();
																	
																	$("#" + jsonObj.tplcontainer[i].widgetcontainer[j].id +" .widget-"+jsonObj.tplcontainer[i].widgetcontainer[j].id + "-" + (widgetContainerOrderId)).siblings(".add-widget").find("a.add-widget-button").css(disable_add_button);
																}														
														} else {
															//no config menus will be showed to user .. [config, remove widget, add article]
														}													
													}
												}
											}
											
										});
									}																			
									if(IExp.pageMaster.sectionId != 10001){
										$(".container-handle-id").text("");
										$('.view-child-clone-button').hide();
									 }else{
										 
										//$('.view-child-clone-button').show();
									 }
									 
								});			
							}							
							TemplateManagerObj.attachDroppableEvent(jsonObj.tplcontainer[i].master_tcid);	
						});															
						$('#IExpWorkSpace .tpl-col-container').sortable({handle:".handle",update:function(event, ui){ 
									//alert(" Sorting on Loading Template");
									$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save
								}	
						});
						$('ul.sortable.news-content').sortable({placeholder: "sortable-placeholder",connectWith:'.widget-container .widget ul'});
						$("#xmlContainer").html("<pre>"+JSON.stringify(xmlString)+"</pre>");
						
						$('#IExpWorkSpace').unblock();
						if (IExp.qParamScrollTop != ""){
							$(window).scrollTop(IExp.qParamScrollTop);			
						}
				   	    //console.log(result);
						IExp.pageMaster.total_versions = result.total_versions;
						/*if(IExp.pageMaster.total_versions < 10)
						{
							$('#delete_currentVersion_template').show();
						}
						else
						{
							$('#delete_currentVersion_template').hide();
						}*/
						if(result.template_locked_user == IExp.currentUserId && result.is_template_closed_before_save == 1 && result.template_lock_status == 2) ///// is_template_closed_before_save- 1->Yes, 2->No
						{
							//template_closed_before_save - Yes
							//show_toastr("Unsaved template loaded template_locked_user:"+result.template_locked_user+" currentUserId:"+IExp.currentUserId+" is_template_closed_before_save:"+result.is_template_closed_before_save+" template_lock_status"+result.template_lock_status, 2);
							hide_html_obj.push('.template_preview');
							//$('#saveTemplate').show();
							show_html_obj.push('#saveTemplate');
							//console.log(show_html_obj);
							show_toastr("Unsaved template loaded", 2);
							
							TemplateManagerObj.show_hide_unsaved_template_msg(2); // 1->saved, 2->unsaved
						}
						else
						{
							//template_closed_before_save - No		
							$('#IExpWorkSpace').unblock();					
						}
						
						/*if(result.template_locked_user_name != '')
						{
							$('#lock_info').text('Locked');
							$('#locked_by_info').html('Locked By &nbsp;&nbsp;: '+ result.template_locked_user_name);
							$('#locked_by_info').show();
						}
						else
						{
							$('#lock_info').text('Open');
							$('#locked_by_info').html('');
							$('#locked_by_info').hide();
						}*/
						var pagesection_name ='';
						var active_section_name = '';
						var pagesection_name = $(".fancytree-active").parents('li').find('.fancytree-expanded').text();
						var active_section_name = $(".fancytree-active").find('.fancytree-title:first').text();	
						if((pagesection_name=="Newsletter")&&(active_section_name!="Newsletter")){
							
						}else
						{
							$('#copy_source').hide();	
						}
					  }//is valid XML	
					  else{
						  if(IExp.userRole.canDesignPage){							
							  //console.log("Not valid xml");
							  //Restore last saved xml - Enable 'reset_rollback' button
							  $('#reset_rollback').show();  
							  show_toastr("This version template is crashed, click to restore button to restore last saved template design", 2);
						  }
					  }
				   }
				   else
				   {
					   /*$('#common_options').hide();
					   $('#addheader_script').hide();
					   $('.template_preview').hide();
					   $('#publish_template').hide();
					   $('#saveTemplate').hide();*/
					   //TemplateManagerObj.showCommonTemplate_options(pageId, versionId);
					   //hide_html_obj.push('#common_options', '#addheader_script', '.template_preview', '#publish_template', '#saveTemplate');					   
					   hide_html_obj.push('#common_options', '#addheader_script', '.template_preview', '#make_version', '#publish_template');			

					   $('#IExpWorkSpace').unblock();
					   
					   /*$('#lock_info').text('Open');
					   $('#locked_by_info').html('');
					   $('#locked_by_info').hide();*/
					   
				   }
				   
				TemplateManagerObj.show_html_elements(show_html_obj);
				TemplateManagerObj.hide_html_elements(hide_html_obj);
				
				/// show_lock_info ///
				show_lock_info(result.template_lock_status, result.template_locked_user_name);
				
				
				current_version_id		= (typeof current_version_id === 'undefined') ? "" : current_version_id;
				TemplateManagerObj.showCommonTemplate_options(pageId, current_version_id, result.template_xml, 'from_loadtemplate');	
				$('#IExpWorkSpace').unblock();
				
				//alert(IExp.pageMaster.total_versions);
				$("#publish_template").addClass("page-loaded");
				TemplateManagerObj.show_not_published_info(result.is_changes_published);//not-published-info
				
				}		
					
			});			
			
		},
		load_version_template: function(version_id, page_id)
		{
			////  Need to manupulate xml data with version id and load page id ///
			$.ajax({
					url 	: base_url + admin_folder +"/template_designer/load_template_by_version_id",	
					type	: 'post',
					async	: false,
					data	: "version_id=" +version_id,
					dataType: "json",
					beforeSend: function()
								{											
									$("#loading_msg").html("Please wait....");
									$("#commom_loading").show();
								},
					success : function(data){
								$("#commom_loading").hide();
								$("#loading_msg").html("");	
								$('#edit_versionName_fields').hide();						
								if(data.load_template == 1)
								{
									TemplateManagerObj.loadTemplate(page_id, version_id);
								}
								else
								{
									alert("Error in loading template.");
								}
								
								},
					error	: function(e){
								/////  Error in getting template commit status  ////
								}	
			});							
		},
		//////  Get template commit status  /////
		get_template_commit_status: function()
		{
			var commit_status = {};			
			if(IExp.pageMaster.pageId != '' && IExp.pageMaster.pageId != null){
			$.ajax({
					url 	: base_url + admin_folder +"/template_designer/is_template_commited",	
					type	: 'post',
					async	: false,
					data	: "return_type=json&page_id=" +IExp.pageMaster.pageId,
					beforeSend: function()
								{											
									$("#loading_msg").html("Please wait....");
									$("#commom_loading").show();
								},
					success : function(data){
									$("#commom_loading").hide();
									$("#loading_msg").html("");
									commit_status = data;								
								},
					error	: function(e){
								/////  Error in getting template commit status  ////
								}	
			});
			
			}
			else
			{
				commit_status = {tempalte_commit_status : '1' };
			}
			
			return commit_status
		},
		update_template_lock: function(lock_value)
		{
			//TemplateManagerObj.validate_login();	
			$.ajax({					
				url: base_url + admin_folder +"/template_designer/update_template_lock",
				type: 'post',
				async:false,
				data: "lockTemplateid="+IExp.pageMaster.pageId+"&lock_status="+lock_value,			
				success: function (data) {	
					//console.log(data);
					$('.lock_template').removeClass("locked"); //// Enable Lock Image  ////
					$("#loading_msg").html("");
					$("#commom_loading").hide();
					if(data.show_msg == 1)
					{
						show_toastr(data.msg, data.msg_type);	
						if(data.res_status == 0){
							show_lock_info(2, data.res_loced_user_name);
						}	
					}
					if(data.res_status == 1 && lock_value == 1)
					{
						$(".lock_template").attr("title","Lock template");				
						$(".lock_template").html("Lock");
						$('.change_lock').removeClass("fa-unlock");
						$('.change_lock').addClass("fa-lock");				
						
						$('#fm-left-panel').addClass("locked");
						$('.lock_panel').addClass("locked");
						$('.ui-sortable-handle').addClass("locked");
						$('.lock_template').attr("data-lockStatus", "2");
						$('.design_view_span').hide();
						$('#edit_versionName_fields').hide();
						
						/* $('#lock_info').text('Open');
						$('#locked_by_info').html('');
						$('#locked_by_info').hide(); */
						show_lock_info(lock_value, data.res_loced_user_name);
					}
					else if(data.res_status == 1 && lock_value == 2)
					{
						/////  Reloading template  /////
						var version_id 							= (IExp.pageMaster.versionId) ? IExp.pageMaster.versionId : '';					
						IExp.pageMaster.query_string_versionId	=  version_id;
						//TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, '');						
						TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, version_id);						
						TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, 'new'); ////  Show template version dropdown 
						
						$(".lock_template").attr("title","Unlock template");				
						$(".lock_template").html("Unlock");
						$('.change_lock').removeClass("fa-lock");
						$('.change_lock').addClass("fa-unlock");				
						
						$('#fm-left-panel').removeClass("locked");
						$('.lock_panel').removeClass("locked");		
						$('.ui-sortable-handle').removeClass("locked");
						$('.lock_template').attr("data-lockStatus", "1");						
						$('.design_view_span').show();	
						
						/* $('#lock_info').text('Locked');
						$('#locked_by_info').html('Locked By &nbsp;&nbsp;: '+ data.res_loced_user_name);
						$('#locked_by_info').show(); */
						show_lock_info(lock_value, data.res_loced_user_name);
					}
					
					TemplateManagerObj.initContextMenu();
				},
				error: function (e) {
					alert("Internal sever error");											
					}
			});
		},
		show_hide_unsaved_template_msg: function(save_staus){
			if(save_staus == 2){				
				if(IExp.pageMaster.versionId === "undefined" || IExp.pageMaster.versionId === "" || typeof IExp.pageMaster.versionId === "undefined"){
					$('.unsaved_template_msg').html('&nbsp').show();
					$("#reset_rollback").hide();
				}
				else{
					$('.unsaved_template_msg').html('Unsaved Template').show();
					$("#reset_rollback").show();
				}
			}
			else{
				$('.unsaved_template_msg').html('&nbsp').show();
				$("#reset_rollback").hide();
			}
		},
		///////  Save Template (This function block is devided from $("#saveTemplate").bin() - ) ///
		save_template: function(template_commit_status, from_add_tempalte_img)
		{			
			//$('.design_view_span').show();
			//console.log("from_add_tempalte_img:",from_add_tempalte_img,":");
				var return_save_status = "";
				var templateXMLData = TemplateManagerObj.getItems2();
				var is_new_template = IExp.pageMaster.versionId; 
				
				//alert("XMLlength:" + templateXMLData.length + " - " + IExp.pageMaster.pageId + " - " + IExp.pageMaster.templateId);
				//if (templateXMLData.length > 0 && IExp.pageMaster.pageId && IExp.pageMaster.templateId ){
				if (templateXMLData.length > 0 && IExp.pageMaster.pageId ){	
				
					var common_header				= (from_add_tempalte_img) ? 1 : (($('#use_common_header').prop('checked'))? 1 : 0);
					var common_rightpanel 			= (from_add_tempalte_img && IExp.pageMaster.templateId != 3) ? 1 : (($('#use_common_rightpanel').prop('checked'))? 1 : 0);
					var common_footer				= (from_add_tempalte_img) ? 1 : (($('#use_common_footer').prop('checked'))? 1 : 0);	
					var use_parent_section_template	= ($('#use_parent_section_template').prop('checked'))? 1 : 0;	
				$.ajax({		
					url: base_url + admin_folder +"/template_designer/save_xml_template",				
					type: 'post',
					async: false,
					cache: false,
					data: "tempStr=" + encodeURI(templateXMLData) + "&pageId=" + IExp.pageMaster.pageId + "&templateId=" + IExp.pageMaster.templateId + "&publish_xml&header="+ common_header + "&rightpanel=" + common_rightpanel + "&footer=" + common_footer + "&template_commit_status=" + template_commit_status + "&saveTemplateStatus=1&VersionId=" + IExp.pageMaster.versionId + "&use_parent_section_template=" + use_parent_section_template,
					success: function (data) {
						$("#commom_loading").hide();
						$("#loading_msg").html("");
						TemplateManagerObj.show_hide_reset_xml(template_commit_status, is_new_template);
						var node = $("#tree").fancytree("getActiveNode");						
						//console.log(data);
						node.data.hastemplate = 1;
						IExp.pageMaster.editInProgress = false; //check for edit in progress
						if(template_commit_status == 1)
						{
							data.msg = " Current template version updated successfully";							 						
							data.show_msg = 1;
							data.msg_type = 1;
							$('#saveTemplate').hide(); 
							TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
							TemplateManagerObj.show_not_published_info(1);//1->not-published, 0->published
						}
						if(data.show_msg == 1){
							show_toastr(data.msg, data.msg_type);
						}
						
						if(data.trigger_tree == 1)
						{
							IExp.qParamPageId = IExp.pageMaster.pageId
							TemplateManagerObj.trigger_fancy_tree_click();
							$('#edit_version_name').trigger('click');
							if(data.res_status != 2){
								TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
							}
							
						}
						
						return_save_status = data.res_status;						
					},
					error: function (e) {
						//alert("error");						
						show_toastr("Failed to save template", 2);
						$('#saveTemplate').show(); 
						TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
						return_save_status = 2;			
					}
				});			
				}else{
					$("#xmlContainer").text("Please start building the template..");					
				}
				return return_save_status;
		},//// End save_template()	
		///// This is call back function using for confirm dialog box  /////				
		dialog_callback: function(result, dialog_object, save_function){
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
		},
		validate_login: function(){
			$.ajax({					
					url			: base_url + admin_folder +"/template_designer/get_session_details",				
					type		: 'post',
					async		: false,
					data		: "",
					dataType	: "json",
					success		: function (data) {
						console.log(data.userID);
						console.log(base_url+admin_folder);
						
						if(data.userID === "undefined" || typeof data.userID === "undefined"){
							window.location = base_url+admin_folder;
						}
					},
					error:  function (jqXHR, timeout, message) {
							var contentType = jqXHR.getResponseHeader("Content-Type");
							if (jqXHR.status === 200 && contentType.toLowerCase().indexOf("text/html") != 0) {
								// assume that our login has expired - reload our current page
								window.location.reload();
							}
					} 
					
				});
		},
		dont_save_template: function(page_id){
			$.ajax({
					//url: "inc/save-xml-template.php",				
					url: base_url + admin_folder +"/template_designer/delete_temporary_instance",				
					type: 'post',
					async: false,
					data: "&pageId=" + page_id + "&saveTemplateStatus=2",
					success: function (data) {
						$("#commom_loading").hide();
						$("#loading_msg").html("");	
						$('#saveTemplate').hide();	
						TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
						$('#lock_info').text('Open');
						$('#locked_by_info').html('');
						$('#locked_by_info').hide();
						$("#reset_rollback").hide();
					},
					error: function (e) {
						//alert("error");	
						$("#reset_rollback").show();
						$('#saveTemplate').show();					
					}
				});
			
		},
		publish_only_advertisements: function(){
			$.ajax({				
				url: base_url + admin_folder +"/template_designer/publish_only_advertisements",
				type: 'post',
				async: false,
				data: "versionId=" + IExp.pageMaster.versionId,
				dataType: "json",
				beforeSend: function() {
								$("#loading_msg").html("Please wait, publishing advertisements...");
								$("#commom_loading").show();
								}, 						
				success: function (data) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");						
					show_toastr(data.show_msg, data.msg_type);
				},
				error: function (e) {
					alert("error");						
				}
			});
		},
		delete_currentTemplateVersion: function(){
			$.ajax({				
				url: base_url + admin_folder +"/template_designer/delete_currentTemplateVersion",
				type: 'post',
				async: false,
				data: "versionId=" + IExp.pageMaster.versionId,				
				dataType: 'json',
				beforeSend: function() {
								$("#loading_msg").html("Please wait...");
								$("#commom_loading").show();
								}, 						
				success: function (data) {
					$('#edit_versionName_fields').hide();
					$("#commom_loading").hide();
					$("#loading_msg").html("");																
					show_toastr(data.msg, data.msg_type);
					if(data.res_status == 1){
						TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, 'new'); ////  Show template version dropdown 
						//////  Load Version based XML template  /////
						TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, '');
					}
				},
				error: function (e) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");	
					alert("error");						
				}
			});
		},
		update_versionName: function(version_name){
			$.ajax({				
				url: base_url + admin_folder +"/template_designer/update_version_name",
				type: 'post',
				async: false,
				data: "versionId=" + IExp.pageMaster.versionId + "&version_name=" + version_name,				
				dataType: 'json',
				beforeSend: function() {
								$("#loading_msg").html("Please wait...");
								$("#commom_loading").show();
								}, 						
				success: function (data) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");																
					show_toastr(data.msg, data.msg_type);
					if(data.res_status == 1){
						var n_index = $('#show_template_version :selected').text().indexOf("live");
						var updated_version_name = (n_index == -1) ? version_name : version_name + " &nbsp; live";
						$('#show_template_version :selected').html(updated_version_name);
						$('#edit_versionName_fields').hide();
					}
				},
				error: function (e) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");	
					show_toastr("Failed to save version name", 2);
					//alert("error");						
				}
			});
		},
		show_tree_hierarchy_of_current_node: function(tempnode){
			//var tempnode = data.node;
               var tempstructure = [];
               tempstructure.push(tempnode.title);  
               while(tempnode.getParent().getParent()){
                  tempstructure.push(tempnode.getParent().title);
                  tempnode = tempnode.getParent();
               }
               tempstructure.reverse();
               IExp.pageMaster.rootstructures	= tempstructure.join(' <span><i class="fa fa-angle-right" aria-hidden="true"></i></span> ');
			   
			   return IExp.pageMaster.rootstructures;
		},
		fancy_tree_click_function: function(data){
			var show_html_obj = ['.lock_template'];
			var hide_html_obj = ['#edit_versionName_fields'];
			
		//console.log(data);	
			//$('#edit_versionName_fields').hide();
			//$('.lock_template').show();
			$(window).scrollTop(0);							
				IExp.pageMaster.pageId 		= data.node.data.pageid;
				IExp.pageMaster.pageType 	= data.node.data.pagetype;
				IExp.pageMaster.sectionId 	= data.node.data.sectionid;							
			    var rootstructures 			= TemplateManagerObj.show_tree_hierarchy_of_current_node(data.node);
				$('#rootstructures').html(rootstructures);
				
				
				/////  unlock all templates for the user  /////
				tree_based_locktemplate(IExp.pageMaster.pageId); ///// From template_designer.php (view)  /////
				
				if (data.node.data.hastemplate) {							
						TemplateManagerObj.loadTemplate(data.node.data.pageid, '');									
						TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, 'new'); ////  Show template version dropdown 
						IExp.pageMaster.versionId = $( "#show_template_version :selected" ).val();								
						
						//TemplateManagerObj.showCommonTemplate_options(IExp.pageMaster.pageId, IExp.pageMaster.versionId);
						if(typeof IExp.pageMaster.versionId === "undefined"){ 
							//$('#new_empty_version').hide();
							hide_html_obj.push('#new_empty_version');
						}
						else{ 
							//$('#new_empty_version').show();
							show_html_obj.push('#new_empty_version');
						}
						
						$("a.dynamic_design_link").attr("href", base_url+admin_folder +"/template_designer/load_saved_template/" + data.node.data.pageid+ "/"+IExp.pageMaster.versionId);
						
						if(IExp.pageMaster.versionId == '')
						{
							if(IExp.userRole.canAddAdv){								
								//$('.header_script_publish').hide();			
								hide_html_obj.push('.header_script_publish');
							}
							//$('#edit_version_name').hide();
							//$('#delete_currentVersion_template').hide();
							hide_html_obj.push('#edit_version_name', '#delete_currentVersion_template');
						}
						else
						{
							var live_version_id = $('#live_version_id').val();
							if(IExp.userRole.canAddAdv){
								//$('.header_script_publish').show();	
								show_html_obj.push('.header_script_publish', '#addheader_script', '.template_preview');		
							}	
							else
							{
								hide_html_obj.push('.header_script_publish', '#addheader_script');
							}
							//$('#edit_version_name').show();
							//$('#delete_currentVersion_template').show();
							if(live_version_id != IExp.pageMaster.versionId && live_version_id != '')
							{								
								if($("#show_template_version").length > 1)
								show_html_obj.push('#delete_currentVersion_template');		
							}
							else
							{
								hide_html_obj.push('#delete_currentVersion_template');
							}
							show_html_obj.push('#edit_version_name');		
						}
					} else {
					
					IExp.pageMaster.versionId = '';					
					TemplateManagerObj.$masterContainer.empty();						
					IExp.pageMaster.templateId = 0;
					show_toastr("No template is created for this page, Please start building the template.", 3);
					TemplateManagerObj.loadTemplate(data.node.data.pageid, '');
					//$('#edit_version_name').hide();
					//$('#delete_currentVersion_template').hide();
					//$('#edit_versionName_fields').hide();
					//$('.design_view_span').hide();
					//$('.template_preview').hide();
					//$('#common_options').hide();
					hide_html_obj.push('#edit_version_name', '#delete_currentVersion_template', '#edit_versionName_fields', '.design_view_span', '.template_preview', '#common_options');
					$('#template_version_dropdown').html('');
					if(IExp.userRole.canAddAdv){
						//$('.header_script_publish').hide();		
						hide_html_obj.push('.header_script_publish');	
					}
					//TemplateManagerObj.showCommonTemplate_options(IExp.pageMaster.pageId, '');
				}
													
				TemplateManagerObj.show_html_elements(show_html_obj);				
				TemplateManagerObj.hide_html_elements(hide_html_obj);
				if(IExp.pageMaster.sectionId == "10000"){ $('#preview').hide(); }else{  $('#preview').show(); }
				
				
		},		
		get_currentVersion_headerScript_lockStatus: function()
		{
			var lock_details = {"header_lock_status":"", "header_locked_user_id":"", "access_status" : 0};
			$.ajax({				
				url: base_url + admin_folder +"/template_designer/get_header_script_lock_status",
				type: 'post',
				async: false,
				data: "versionId=" + IExp.pageMaster.versionId,				
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
				},
				error: function (e) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");	
					alert("error");						
				}
			});
			return lock_details;
		},		
		is_lock_free_template_version: function(){			
			var lock_free_tempalte_status = "";
			$.ajax({				
				url: base_url + admin_folder +"/template_designer/is_lock_free_template_version",
				type: 'post',
				async: false,
				data: "versionId=" + IExp.pageMaster.versionId,				
				dataType: 'json',
				beforeSend: function() {
						$("#loading_msg").html("Please wait...");
						$("#commom_loading").show();
						}, 						
				success: function (data) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");
					if(data.show_msg == 1){ show_toastr(data.msg + data.locked_user_list, data.msg_type); }
					lock_free_tempalte_status = data.lock_status; 
				},
				error: function (e) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");	
					alert("error");						
				}
			});
			return lock_free_tempalte_status;		
		},
		is_lock_free_advertisements_version: function(){			
			var lock_free_adv_status = "";
			$.ajax({				
				url: base_url + admin_folder +"/template_designer/is_lock_free_advertisements_version",
				type: 'post',
				async: false,
				data: "versionId=" + IExp.pageMaster.versionId,				
				dataType: 'json',
				beforeSend: function() {
						$("#loading_msg").html("Please wait...");
						$("#commom_loading").show();
						}, 						
				success: function (data) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");
					if(data.show_msg == 1){ show_toastr(data.msg + data.locked_user_list, data.msg_type); }
					lock_free_adv_status = data.lock_status; 
				},
				error: function (e) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");	
					alert("error");						
				}
			});
			return lock_free_adv_status;				
		}, 
		show_widget_configuration: function(widgetObject, $elem, check_renderingtype, widgetId, widget_instance_Id, page_master_page_id){
					//$(widgetObject) = widgetObject;
			//var widgetObject = $elem.parent().siblings(".widget-container").find("div.widget");
			IExp.pageMaster.pageId = page_master_page_id
					var attributeSetStr = [].filter.call($(widgetObject)[0].attributes, function(at) { return /^data-/.test(at.name); });
					var customAttributeStr = [].filter.call($(widgetObject)[0].attributes, function(at) { return /^cdata-/.test(at.name); });					
					var widgetTabStr = [].filter.call($(widgetObject)[0].attributes, function(at) { return /^widgettab/.test(at.name); });					 
					
					var customAttributeAry = [];
					$.each($(widgetObject)[0].attributes, function(i,v){
						if (/^cdata-/.test(this.name)) {
							var name = (this.name).replace("-","_");
							var value = this.value;
							customAttributeAry[name] = value;
						}
					}); 					
					if(widgetObject.data("customConfig")){
						//alert("exist");										
					}else {
							var additionalProperties = {
								customTitle : '',
								showSummary : '',
								renderingMode :'',
								separatorRequired :'',
								widgetCategory :'',
								iframeUrl :'',
								widgetpublishOn : '',
								widgetpublishOff : '',
								widgetStatus : '',
								simpleTab:{
									categoryList:[]
								},
								nestedTab:{
									categoryList:[]
								}
							};
							additionalProperties.customMaxArticles = customAttributeAry["cdata_custommaxarticles"];
							additionalProperties.customBgColor = customAttributeAry["cdata_custombgcolor"];
							additionalProperties.customTitle = customAttributeAry["cdata_customtitle"];
							additionalProperties.showSummary = customAttributeAry["cdata_showsummary"];
							additionalProperties.renderingMode = customAttributeAry["cdata_renderingmode"];
							additionalProperties.separatorRequired = customAttributeAry["cdata_rseparatorrequired"];
							additionalProperties.widgetCategory = customAttributeAry["cdata_widgetcategory"];
							additionalProperties.iframeUrl = customAttributeAry["cdata_iframeurl"];
							additionalProperties.widgetpublishOn = customAttributeAry["widgetpublishOn"];
							additionalProperties.widgetpublishOff = customAttributeAry["widgetpublishOff"];
							additionalProperties.widgetStatus = customAttributeAry["widgetStatus"];
							additionalProperties.isCloned	  = customAttributeAry["isCloned"];
							widgetObject.data("customConfig",additionalProperties);					
					}
					
					var cloned_instance_id 	= $(widgetObject).data("clonedinstanceid");
					cloned_instance_id		= (typeof cloned_instance_id === 'undefined' || cloned_instance_id === 'undefined') ? "" : cloned_instance_id;
					TemplateManagerObj.$widgetConfigRelativeURL = "";										
					TemplateManagerObj.$widgetConfigRelativeURL = TemplateManagerObj.$widgetConfigBaseURL + "?configVersionId=" + IExp.pageMaster.versionId + "&" + $.param(attributeSetStr);										
					$.fancybox({						
						'href': TemplateManagerObj.$widgetConfigRelativeURL,
						'type':'ajax',
						'width': 650,						
						'minWidth': 650,						
						'padding': 0,
						'modal': true,
						'margin': 0,
						'autoSize': true,
						'hideOnContentClick': false,
						'closeBtn': true,
						'closeClick': true,						
						'afterShow': function () {								
							var additionalProperties;
							
							
							if (widgetObject.data("customConfig")) {
								additionalProperties = widgetObject.data("customConfig");						
								var widget_instance_Id = (cloned_instance_id) ? cloned_instance_id : $elem.parent().siblings(".widget-container").find("div.widget").data("widgetinstanceid");
								if(additionalProperties.renderingMode == "auto")
								{
									$('.max_articles').show();
								}
								if(additionalProperties.customBgColor != '')
								{
									$('#selected_color').css({"background-color" : additionalProperties.customBgColor});
								}								
								($("#widget_bg_color").length && $("#widget_bg_color").length > 0 && !(jQuery.type(additionalProperties.customBgColor) == "string" && additionalProperties.customBgColor == "undefined") )  ? $("#widget_bg_color").val($.trim(additionalProperties.customBgColor)) : '';
								
								var max_articles_show_val = (typeof additionalProperties.customMaxArticles === 'undefined' || additionalProperties.customMaxArticles == '' || additionalProperties.customMaxArticles === 'undefined') ? 0 : parseInt(additionalProperties.customMaxArticles);
								
								$("#show_max_articles").val(max_articles_show_val);
								
								/* ($("#show_max_articles").length != '' && $("#show_max_articles").length && $("#show_max_articles").length > 0 && !(jQuery.type(additionalProperties.customMaxArticles) === "string" && additionalProperties.customMaxArticles === "undefined") )  ? $("#show_max_articles").val(max_articles_show_val) : 0; */
								
								($("#widgetTitle").length && $("#widgetTitle").length > 0 && !(jQuery.type( additionalProperties.customTitle ) === "string" && additionalProperties.customTitle === "undefined") )  ? $("#widgetTitle").val($.trim(additionalProperties.customTitle)) : '';
								(additionalProperties.widgetpublishOn !='' && additionalProperties.widgetpublishOn !='undefined') ?$("#publish_start_date").val(additionalProperties.widgetpublishOn) : '';	
								(additionalProperties.widgetpublishOff !='' && additionalProperties.widgetpublishOff !='undefined') ?$("#publish_end_date").val(additionalProperties.widgetpublishOff) : '';	
								(additionalProperties.widgetStatus !='' && additionalProperties.widgetStatus !='undefined') ?$("input[name='status'][value='" + additionalProperties.widgetStatus + "']").attr('checked','checked') : $("input[name='status'][value='1']").attr('checked','checked');
								//console.log("additionalProperties.isCloned",additionalProperties.isCloned);
								(additionalProperties.isCloned == '0' || typeof additionalProperties.isCloned == 'undefined' || additionalProperties.isCloned === 'undefined') ? $("input[name='is_cloned']").attr('checked', false) : $("input[name='is_cloned']").attr('checked', true);
								
									
								$("#showSummary option[value='" + additionalProperties.showSummary + "']").attr("selected", true);		
									//console.log(additionalProperties.iframeUrl);
								($("#widgetCategory").length && $("#widgetCategory").length > 0 && $("#widgetCategory").val() !== '' )  ? $('#widgetCategory option:selected').val() : '';								
								$("#widgetCategory option[value='" + additionalProperties.widgetCategory + "']").attr("selected", true);									
								//simple tab selection
								$("#tabWidgetCategory option[value='" + additionalProperties.simpleTab.categoryId + "']").attr("selected", true);									
								$("#separatorRequired option[value='" + additionalProperties.separatorRequired + "']").attr("selected", true);																										
								$("#renderingMode option[value='" + additionalProperties.renderingMode + "']").attr("selected", true);		
								//new tab config Code
								$("#categorySelect").change(function(){	
									if (!$(this).hasClass("edit-mode")) {
									$("#tabTitle").val($.trim($("#categorySelect option:selected").text()));
									}					
								});																							
								$('#tabDesignWrapper').click(function(e){
									var $elem = $(e.target);
									if ($elem.is("span.close")){
										if(confirm("Are you sure you want to remove?"))
										{
											$elem.closest("li").remove();
											$("#tabDesignContainer li").removeClass("edit");										
											$("#tabTitle").val("");
											$("#categorySelect").val("");											
											$("#categoryType").val($("#categoryType option:first").val());
											$("#btnWcCancel").hide();
											$("#btnWcUpdate").hide();
											$("#btnWcAdd").show();
											$("#categorySelect").removeClass("edit-mode");
											$("#categorySelect").removeClass("edit-mode");
										}										
									}else if ($elem.is("span.edit-tab")){
										
										var categoryId = $elem.closest("li").attr("data-categoryId");
										var categoryType = $elem.closest("li").attr("data-categoryType");
										var categoryTypeName = $elem.closest("li").attr("data-categoryTypeName");
										var customTitle = $.trim($elem.closest("li").attr("data-customTitle"));
										$(".SimpleTabOpt").removeClass("edit");
										$elem.closest("li").addClass("edit");										
										$("#tabTitle").val(customTitle);
										$("#categorySelect").val(categoryId);
			(categoryType !='' && categoryType !='undefined') ?$("#categoryType").val(categoryType) : $("#categoryType").val('1');	
										$("#btnWcCancel").show();
										$("#btnWcUpdate").show();
										$("#btnWcAdd").hide();
										$("#categorySelect").addClass("edit-mode");
										controll_sections_by_conten_id();
									}
								});								
								$('#inputControls').click(function(e){
									var $elem = $(e.target);
									if ($elem.is("input#btnWcAdd")){
										if ($("#tabTitle").val().length != 0 ){
											var str = '<li class="SimpleTabOpt" data-categoryId="'+ $("#categorySelect option:selected").val()+'" data-categoryName="'+ $.trim($("#categorySelect option:selected").text())+'" data-customTitle="'+ $.trim($("#tabTitle").val())+'" data-categoryType="'+ $("#categoryType option:selected").val()+'" data-categoryTypeName="'+ $("#categoryType option:selected").text()+'" ><div class="category-handler"><span class="sort"></span></div><div class="category-details"><span class="customTitle">Custom Title:'+ $.trim($("#tabTitle").val())+'</span> [<span class="categoryTitle"> Section:'+ $.trim($("#categorySelect option:selected").text()) +'</span>] [<span class="categoryTypeName"> Content Type:'+ $.trim($("#categoryType option:selected").text()) +'</span>] </div>  <div class="category-edit-close"><span class="edit-tab" title="Edit">Edit</span><span class="fa fa-times-circle close"></span></div></li>';	
											if ($("#tabDesignContainer li").length > 0) {												
												if($("#tabDesignContainer li.ui-selected").length > 0 && $("#typeOfConfig").val() == "3") {		
													if($("#tabDesignContainer li.ui-selected ul").length > 0 ) {					
														$("#tabDesignContainer li.ui-selected ul").append($(str));								
													} else {
														if($("#typeOfConfig").val() =="3"){
															var str = "<ul>" + str + "</ul>";
															$("#tabDesignContainer li.ui-selected ").append($(str));		
															$("#tabDesignContainer li.ui-selected ul").sortable();
														}
													}				
												} else {	
												
													$("#tabDesignContainer ").append($(str));			
												}	
											} else {		
												$("#tabDesignContainer ").append($(str));		
											}
											$("#tabTitle").val("");
											$("#categorySelect").val("");
											//$("#categoryType").val($("#categoryType option:first").val());
											
										} else {
											alert("please select category or enter a custom title");		
										}
									}else if ($elem.is("input#btnWcCancel")){
										$("#tabDesignContainer li").removeClass("edit");										
										$("#tabTitle").val("");
										$("#categorySelect").val("");
										//$("#categoryType").val("all");	
										$("#categoryType").val($("#categoryType option:first").val());
										$("#btnWcCancel").hide();
										$("#btnWcUpdate").hide();
										$("#btnWcAdd").show();
										$("#categorySelect").removeClass("edit-mode");
									}else if ($elem.is("input#btnWcUpdate")){
										var tab = $("#tabDesignContainer li.edit");
										tab.attr("data-categoryId",$("#categorySelect option:selected").val());
										tab.attr("data-categoryType",$("#categoryType option:selected").val());
										tab.attr("data-categoryTypeName",$("#categoryType option:selected").text());
										tab.attr("data-categoryName",$("#categorySelect option:selected").text());
										tab.attr("data-customTitle", $("#tabTitle").val());							
										tab.find("span.customTitle").text("Custom Title:" + $("#tabTitle").val());
										tab.find("span.categoryTitle").text("Section:" + $.trim($("#categorySelect option:selected").text()));										
										tab.find("span.categoryType").text("Content Type:" + $.trim($("#categoryType option:selected").text()));
										tab.find("span.categoryTypeName").text("Content Type:" + $.trim($("#categoryType option:selected").text()));										
										alert("Updated successfully - " + $("#tabTitle").val());
										$("#tabDesignContainer li").removeClass("edit");	
										$("#btnWcCancel").hide();
										$("#btnWcUpdate").hide();
										$("#btnWcAdd").show();	
										
										$("#tabTitle").val("");
										$("#categorySelect").val("");										
										$("#categoryType").val($("#categoryType option:first").val());
										$("#categorySelect").removeClass("edit-mode");	
									}
								});
									var addAttrObject = additionalProperties.simpleTab.categoryList;									
									$.each(addAttrObject,function(){
								var categoryType = (this.categoryType === 'undefined')? 1 : this.categoryType; 
								var categoryTypeName = (this.categoryTypeName === 'undefined')? 'Article' : this.categoryTypeName; 
								var str = '<li class="SimpleTabOpt" data-categoryId="'+this.categoryId+'" data-categoryName="'+ this.categoryName+'" data-customTitle="'+ $.trim(this.customTitle)+'" data-categoryType="'+ categoryType +'" data-categoryTypeName="'+ categoryTypeName +'" ><div class="category-handler"><span class="sort"></span></div><div class="category-details"><span class="customTitle">Custom Title:'+ $.trim(this.customTitle)+'</span>[<span class="categoryTitle"> Section:'+ $.trim(this.categoryName)+'</span>] [<span class="categoryTypeName"> Content Type:'+ $.trim(this.categoryTypeName) +'</span>]</div> <div class="category-edit-close"><span  class="edit-tab" title="Edit">Edit</span><span class="fa fa-times-circle close"></span></div></li>';
											$("#tabDesignContainer").append($(str));													
									});
								//add Categories
								if(additionalProperties.nestedTab.categoryList){
									var addAttrObject = additionalProperties.nestedTab.categoryList;
									$.each(addAttrObject,function(){
								var categoryType = (this.categoryType === 'undefined')? 1 : this.categoryType; 
								var categoryTypeName = (this.categoryTypeName === 'undefined')? 'Article' : this.categoryTypeName; 
								var str = '<li class="SimpleTabOpt" data-categoryId="'+this.categoryId+'" data-categoryName="'+ $.trim(this.categoryName) +'" data-customTitle="'+ $.trim(this.customTitle) +'" data-categoryType="'+ categoryType +'" data-categoryTypeName="'+ this.categoryTypeName+'" ><div class="category-handler"><span class="sort"></span></div><div class="category-details"><span class="customTitle">Custom Title:'+ $.trim(this.customTitle)+'</span>[<span class="categoryTitle"> Section:'+ $.trim(this.categoryName) +'</span>] [<span class="categoryTypeName"> Content Type:'+ categoryTypeName +'</span>]</div>  <div class="category-edit-close"><span class="edit-tab" title="Edit">Edit</span><span class="fa fa-times-circle close"></span></div>';
											if(this.childCategory){
												var childCat = this.childCategory;
												str = str + "<ul>";
												$.each(childCat,function(){
								var categoryType = (this.categoryType === 'undefined')? 1 : this.categoryType; 
								var categoryTypeName = (this.categoryTypeName === 'undefined')? 'Article' : this.categoryTypeName; 
								str = str + '<li class="SimpleTabOpt" data-categoryId="'+this.categoryId+'" data-categoryName="'+ $.trim(this.categoryName)+'" data-customTitle="'+ $.trim(this.customTitle)+'" data-categoryType="'+ categoryType +'" data-categoryTypeName="'+ $.trim(this.categoryTypeName)+'" ><div class="category-handler"><span class="sort"></span></div><div class="category-details"><span class="customTitle">Custom Title:'+ $.trim(this.customTitle)+'</span>[<span class="categoryTitle"> Section:'+ $.trim(this.categoryName) +'</span>] [<span class="categoryTypeName"> Content Type:'+ categoryTypeName +'</span>]</div>  <div class="category-edit-close"><span  class="edit-tab" title="Edit">Edit</span><span class="fa fa-times-circle close"></span></div></li>';
												});
												str = str + "</ul>";											
											}
											str = str +"</li>";											
											$("#tabDesignContainer").append($(str));											
									});
									$("#tabDesignContainer li ul").sortable();									
								}						
							}					
							$("#tabDesignContainer ").selectable({
								filter: 'li',
								cancel: 'span.sort, span.close, span.edit-tab',
								selected: function (event, ui) {
									if ($(ui.selected).hasClass('click-selected')) {
										$(ui.selected).removeClass('ui-selected click-selected');
									} else {
										$(ui.selected).addClass('click-selected');
										$("#tabTitle").val($(ui.selected).attr("data-customTitle"));
									}
								},
								unselected: function (event, ui) {
									$(ui.unselected).removeClass('click-selected');
								},
								stop: function(event, ui) {
									//console.log(event,ui);
								}
							}).sortable({
								delay: 100,
								axis: 'y',
								placeholder: 'ui-state-highlight',
								handle: 'span.sort',
								helper: function(e, ui) {
									ui.children().each(function() {
									$(this).width($(this).width());
									}); 
									return ui;
								},
								start: function(event, ui) {
									//ui.placeholder.html('<td colspan="99">&nbsp;</td>');
								},
								update: function(event, ui) {
									document.body.style.cursor = 'wait';
									var arraied = $('table#uber tbody').sortable('toArray');
									$('#info').html('sort order: ');
									$.each(arraied, function(key, value) {
									$('#info').append(value);
									});
									// Ajax call to php file
									// On success
									document.body.style.cursor = 'default';
								},
								stop: function(event, ui) { /*  Reset and add odd and even classes     */
								}
							}).disableSelection();
							///////  Validate the Widget Configuration form values  /////////
							$.validator.addMethod("simpleTabSelect", function(){								
							  return $("#stabSelectedCategoryList > option").length != 0;
							 }, "");
							 
							 $.validator.addMethod("nestedTabSelect", function(){								
							  return $("#ntabselectedCategoryList > option").length != 0;
							 }, "");
							 
							 $.validator.addMethod("childnestedTabSelect", function(){								
							  return $("#nestedSelectedChildCategoryList > option").length != 0;
							 }, "");
							
							 /* $.validator.addMethod("show_max_articles", function(){								
							  return $("#show_max_articles").val() != 0;
							 }, ""); */									

							$('#widget_config_form').validate({
										rules: {											
											stabSelectedCategoryList : {  
														simpleTabSelect: "default",
											},
											ntabselectedCategoryList : {  
														nestedTabSelect: "default",
											},
											
											nestedSelectedChildCategoryList : {  
														childnestedTabSelect: "default",
											},
											/* show_max_articles : {
														show_max_articles: "default",
											} */	
										},
										messages: {											 
											 stabSelectedCategoryList: {												 
												 simpleTabSelect: "Please setup atleast one section"
											},
											
											ntabselectedCategoryList:  {												 
												 nestedTabSelect: "Please setup atleast one section"
											},
											
											nestedSelectedChildCategoryList:  {												 
												 childnestedTabSelect: "Please setup atleast one section"
											},
											/* show_max_articles: {
												show_max_articles: "Required filed"
											} */
										 }
							});
							///////////////  End of validate method  ///////////
							
							//Simple Tab
							function byValue(a, b) {
								return a.value > b.value ? 1 : -1;
							};

							function rearrangeList(list) {
								$(list).find("option").sort(byValue).appendTo(list);
							};
							
							$("#tabWidgetCategory").change(function(){								
								/*var categoryObject = getObjects(IExp.categories, 'categoryId', $(this).val());	
								//alert(categoryObject.length);	
								if(categoryObject.length == 1)
								{
									$("div.tab-config-container select.leftList").empty();
									$("div.tab-config-container select.rightList").empty();
									 $.each(categoryObject[0].childCategories, function(index,value){
										$("<option>").attr("value", this.categoryId ).text(this.categoryName).appendTo($("div.tab-config-container select.leftList"));							
									 });
								}
								else
								{
									$("div.tab-config-container select.leftList").empty();
									$("div.tab-config-container select.rightList").empty();
								}*/
							});						
							
							
							$("div.form-container").on("click",function(e){								
								var $elem = $(e.target);									
								if ($elem.is("input.moveRight")){
									//$("div.nested-tab-step2 select.leftList > option").remove();
									//$("div.nested-tab-step2 select.rightList > option").remove();									
									
									var $parentEl = $elem.parents("div.tab-config-container");
									$parentEl.find("select.leftList >option:selected").each(function () {
										$(this).remove().appendTo($parentEl.find("select.rightList"));
										rearrangeList($parentEl.find("select.leftList"));
										$('#tabCustomTitle').val("");
									});							
									
									if ($parentEl.hasClass("nested-tab-step2")){
										var temp=[];
										$parentEl.find("select.rightList >option").each(function () {
											var tempObj={};
											tempObj.categoryId = $(this).val();
											tempObj.categoryName = $(this).text();
											temp.push(tempObj);										
										});	
										$("div.nested-tab-step1 select.rightList >option:selected").data("childCategory", temp)	;
									}
									
								} else if($elem.is("input.moveLeft")){
									//console.log("moveLeft", $elem);
									
									
									var $parentEl = $elem.parents("div.tab-config-container");
									$parentEl.find("select.rightList >option:selected").each(function () {
										
										var selected_child_no = $("div.nested-tab-step2 select.rightList > option").length;
										var find_is_child = ($parentEl.find("select.rightList").attr("id") == "nestedSelectedChildCategoryList");
										//alert(find_is_child);
										
										if(selected_child_no > 0 && !find_is_child )
										{
											if(confirm("'" + $(this).text() + "' is having " + selected_child_no + " 'Selected Subsctions', Are sure you want to move back?"))
											{
												$("div.nested-tab-step2 select.leftList > option").remove();
												$("div.nested-tab-step2 select.rightList > option").remove();
												$(this).remove().appendTo($parentEl.find("select.leftList"));	
												$('#tabCustomTitle').val("");																					
											}											
										}
										else if(find_is_child )
										{
											$(this).remove().appendTo($parentEl.find("select.leftList"));	
										}
										else 
										{
											$("div.nested-tab-step2 select.leftList > option").remove();
											$("div.nested-tab-step2 select.rightList > option").remove();
											$(this).remove().appendTo($parentEl.find("select.leftList"));
											$('#tabCustomTitle').val("");																						
										}
										rearrangeList($parentEl.find("select.rightList"));	
																													
									});
									
									
											
									if ($parentEl.hasClass("nested-tab-step2")){
										var temp=[];
										$parentEl.find("select.rightList >option").each(function () {
											var tempObj={};
											tempObj.categoryId = $(this).val();
											tempObj.categoryName = $(this).text();
											temp.push(tempObj);												
										});	
										$("div.nested-tab-step1 select.rightList >option:selected").data("childCategory", temp)	;
									}								
								} else if($elem.is("select.rightList > option")){									
									//console.log("right List Click ", $elem);
									//alert(typeof($elem.attr("customTitle")));			 						
									//alert("click on right select");
									var $parentEl = $elem.parents("div.tab-config-container");						
									if($elem.attr("customTitle") === 'undefined')
									{
										$parentEl.find("input.tabCustomTitle").val("");
										$elem.attr("customTitle") = "";
										//alert($elem.attr("customTitle"));
									}
									else
									{
										$parentEl.find("input.tabCustomTitle").val($elem.attr("customTitle"));
									}
									
									var po = $elem;
									if ($parentEl.hasClass("nested-tab-step1")){
										//alert("subsection");
										var categoryObject = getObjects(IExp.categories, 'categoryId', $elem.val());
										//console.log($elem.val());
										$("div.nested-tab-step2 select.leftList > option").remove();
										$("div.nested-tab-step2 select.rightList > option").remove();
										$.each(categoryObject[0].childCategories, function(index,value){
											var o = po.data("childCategory");
											var co = this;
											if(o){
												var status = false;
												$.each(o, function(){ 
												
													if(this.categoryId == co.categoryId ){
														status = true; 
														to = this;
													}		
												});
												if (status == false) {
													$("<option>").attr("value", co.categoryId ).text(co.categoryName).appendTo($("div.nested-tab-step2 select.leftList"));													
												} else {
													$("<option>").attr("value", co.categoryId ).attr("customTitle", to.customTitle ).text(co.categoryName).appendTo($("div.nested-tab-step2 select.rightList"));
												}
											} else {
											
													$("<option>").attr("value", co.categoryId ).text(co.categoryName).appendTo($("div.nested-tab-step2 select.leftList"));
											}
										});										
										
										
										
									}
								} else if($elem.is("input.btnTabCustomTitle1")){									
									var $parentEl = $elem.parents("div.tab-config-container");		
									$parentEl.find("select.rightList").find("option:selected").attr("customTitle", $parentEl.find(".tabCustomTitle").val());
									
									if($parentEl.find(".tabCustomTitle").val() != '')
									{
										$('#customTitle_saved').html("Custom title saved successfully");
										$('#customTitle_saved').fadeIn("fast");
										setTimeout(function () {											
											$('#customTitle_saved').fadeOut("slow");	
											$('#tabCustomTitle').val("");
											}, 1 * 1000); /// for 1 second 
									}
																	
								}else if($elem.is("input.btnTabCustomTitle2")){									
									var $parentEl = $elem.parents("div.tab-config-container");		
									$parentEl.find("select.rightList").find("option:selected").attr("customTitle", $parentEl.find(".tabCustomTitle").val());	
									if($parentEl.find(".tabCustomTitle").val() != '')
									{
										$('#customTitle_saved_2').html("Custom title saved successfully");
										$('#customTitle_saved_2').fadeIn("fast");
										setTimeout(function () {											
											$('#customTitle_saved_2').fadeOut("slow");	
											$('#tabChildCustomTitle').val("");
											}, 1 * 1000); /// for 1 second 
									}
									
									if ($parentEl.hasClass("nested-tab-step2")){
										var temp=[];
										$parentEl.find("select.rightList >option").each(function () {
											var tempObj={};
											tempObj.categoryId = $(this).val();
											tempObj.categoryName = $(this).text();
											tempObj.customTitle = $(this).attr('customTitle');
											temp.push(tempObj);												
										});	
										$("div.nested-tab-step1 select.rightList >option:selected").data("childCategory", temp)	;
									}	
									
								}
											
								
							});
							
							//Fancybox Config Apply button click
							$("#configApply").on("click", function(){
								//console.log( "validate min = " + $('#widget_config_form').valid());
								if($('#widget_config_form').valid())
								{
									var typeOfConfig = $("#typeOfConfig").val();
									var widget_mainsection_id_extra = $("#widget_mainsection_id_extra").val();
									var widget_subsection_id_extra = $("#widget_subsection_id_extra").val();
									var form_error = "";
									additionalProperties.customBgColor = ($("#widget_bg_color").length && $("#widget_bg_color").length > 0)  ? $("#widget_bg_color").val() : '';
									additionalProperties.customMaxArticles = ($("#show_max_articles").length && $("#show_max_articles").length > 0)  ? parseInt($("#show_max_articles").val()) : '0';
									additionalProperties.customTitle = ($("#widgetTitle").length && $("#widgetTitle").length > 0)  ? $("#widgetTitle").val() : '';
									
									additionalProperties.showSummary = ($("#showSummary").length && $("#showSummary").length > 0)  ? $('#showSummary option:selected').val() : '';
									
									additionalProperties.iframeUrl = ($("#iframeLink").length && $("#iframeLink").length > 0)  ? $("#iframeLink").val() : '';
									
									additionalProperties.widgetCategory = ($("#widgetCategory").length && $("#widgetCategory").length > 0)  ? $('#widgetCategory option:selected').val() : '';
									
									additionalProperties.separatorRequired = ($("#separatorRequired").length && $("#separatorRequired").length > 0)  ? $('#separatorRequired option:selected').val() : '';
									
									additionalProperties.renderingMode = ($("#renderingMode").length && $("#renderingMode").length > 0)  ? $('#renderingMode option:selected').val() : '';
									
									additionalProperties.widgetpublishOn = $('#publish_start_date').val();
									additionalProperties.widgetpublishOff = $('#publish_end_date').val();
									additionalProperties.widgetStatus = $('input[name=status]:checked').val();
									additionalProperties.isCloned = ($('#is_cloned').is(':checked')) ? "1" : "0";
									//var is_cloned = ($('#is_cloned').is(':checked')) ? "1" : "0";
									
									//additionalProperties.renderingMode = ($("#renderingMode").length && $("#renderingMode").length > 0)  ? $('#renderingMode checked').val() : 'manual';
									if((additionalProperties.customMaxArticles == 0 || additionalProperties.customMaxArticles == '' || $("#show_max_articles").val() == '' ) && (check_renderingtype != 3)){
										alert("Max Number Of Articles field should not be empty and should be greater than 0");
										$("#show_max_articles").focus();
										//console.log(check_renderingtype);
										return false;
									}
										//alert(typeOfConfig);
									switch(typeOfConfig){
										//// 1->normal, 2->simple tab, 3->nested tab
										case '1':										
											break;
										//// 1->normal, 2->simple tab, 3->nested tab	
										case '2':
										
											additionalProperties.simpleTab = {};
											additionalProperties.simpleTab.categoryList = [];
											additionalProperties.widgetCategory = $("#tabWidgetCategory option:selected").val();
											additionalProperties.simpleTab.categoryId = $("#tabWidgetCategory option:selected").val();
											additionalProperties.simpleTab.categoryTypeName = $("#categoryType option:selected").text();											
											//add selected child categories 
											if ($("#tabDesignContainer > li").length > 0) {
												additionalProperties.simpleTab.categoryList = [];
												$("#tabDesignContainer > li").each(function () {													
													additionalProperties.simpleTab.categoryList.push({'categoryId':$(this).attr("data-categoryId"), 'customTitle' : $(this).attr('data-customTitle'),'categoryName' : $(this).attr("data-categoryName"),'categoryType' : $(this).attr("data-categoryType"),'categoryTypeName' : $(this).attr("data-categoryTypeName")});
													//$(this).remove().appendTo("#parentCategoryList");
													//rearrangeList("#parentCategoryList");
												});
											} else {
												alert("Please add Tab category");
												return false;
											}																										
											break;
										//// 1->normal, 2->simple tab, 3->nested tab
										case '3':		
											additionalProperties.nestedTab = {};
											additionalProperties.nestedTab.categoryList = [];
											$("#tabDesignContainer > li").each(function () {
												var nTabObject={};
												nTabObject.categoryId = $(this).attr('data-categoryId');
												nTabObject.customTitle = $(this).attr('data-customTitle');
												nTabObject.categoryName = $(this).attr('data-categoryName');
												nTabObject.childCategory = [];					
												//var o = $(this).data("childCategory");
												var o = $(this).find("ul > li");
												$.each(o,function(){
													nTabObject.childCategory.push ({'categoryId':$(this).attr("data-categoryId"), 'customTitle' : $(this).attr('data-customTitle'),'categoryName' : $(this).attr("data-categoryName"),'categoryType' : $(this).attr("data-categoryType"),'categoryTypeName' : $(this).attr("data-categoryTypeName")});						
												});												
												additionalProperties.nestedTab.categoryList.push(nTabObject);													
												//$(this).remove().appendTo("#parentCategoryList");
												//rearrangeList("#parentCategoryList");
											});																						
											break;									
									}									
								//console.log("my: ",additionalProperties['renderingMode']);
								//if(confirm("Are sure you want to save Configuration"))
								//{
									widgetObject.data("customConfig", additionalProperties);									
									//alert(additionalProperties.iframeUrl);
									//console.log("&&&&&&&&&&&&&&& : ", widgetObject);
									if(confirm("Are sure you want to save Configuration"))
								    {
									
									var widget_rendering_type = $('#widget_rendering_type').val();
										////////  save additional properties in widgetinstance, MainSection, Subsetion tables in DB  ////////////
										$.ajax({											
												 url: base_url + admin_folder +"/template_designer/save_widget_config",				
												 type: 'post',
												 data: { "widget_configuration" : JSON.stringify( additionalProperties ), "widget_instance_id" : widget_instance_Id, "widget_type" : typeOfConfig, "widget_mainsection_id_extra" : widget_mainsection_id_extra, "widget_subsection_id_extra" : widget_subsection_id_extra, "versionId": IExp.pageMaster.versionId, "page_id" : IExp.pageMaster.pageId, "widget_rendering_type" : check_renderingtype },
												 //dataType: 'json',
												 //global: false,
												 
												 async:false,
												 beforeSend: function() {
													// setting a timeout					
													//$("#loading_msg").html("Please wait....");
													//$("#commom_loading").show();
												},
												 success: function (data) {
													 //alert(data);
													//console.log(data);
													//$("#commom_loading").hide();
													 //$("#loading_msg").html("");
													 //////  Release the widget config Lock  //////
													 var widget_lock =  lock_widget_config(widget_instance_Id, IExp.pageMaster.pageId, '1', check_renderingtype);
													 if(data['status'] == "success")
													 {
														 form_error = "";
														 form_error_msg = "";
														 
														 var h4_show_max_articles = (additionalProperties['renderingMode'] === '' || additionalProperties['renderingMode'] == 3 ) ? "" : " - "+ TemplateManagerObj.capitalizeFirstLetter(additionalProperties['renderingMode']) +" Mode("+ ((additionalProperties.customMaxArticles != '') ? additionalProperties.customMaxArticles +")" : '0)') ;
														 
														 var h4_publish_on = (check_renderingtype == 3 || additionalProperties['widgetpublishOn'] == '') ? "" : "-P On("+ additionalProperties['widgetpublishOn'] +")";
														 
														 var h4_publish_off = (check_renderingtype == 3 || additionalProperties['widgetpublishOff'] == '') ? "" : "-P Off("+ additionalProperties['widgetpublishOff'] +")";
														 
														 var h4_active_status = (additionalProperties['widgetStatus'] ==='undefined' || check_renderingtype == 3)? "" : ((additionalProperties['widgetStatus'] == '1') ? "- <span style='color:#008000; font-weight:bold;'>Active</span>" : "- <span style='color:#d0401c; font-weight:bold;'>Inactive</span>");
														 var h4_combine_text = "";
														 h4_combine_text = h4_show_max_articles + h4_publish_on + h4_publish_off + h4_active_status;
														 
														 (additionalProperties.customTitle && additionalProperties.customTitle.length > 0) ? widgetObject.find("h4").html(additionalProperties.customTitle + h4_combine_text) : widgetObject.find("h4").html(widgetObject.attr("data-widgetname") + h4_combine_text);
														 
														 
													 }
													 else if(data['status'] == "fail")
													 {
														 form_error = "error";
														 form_error_msg = data['message'];
													 }
													// var $elem = $(e.target);
													 var add_button_id = $elem.attr("data-target-container");
													 if(additionalProperties['renderingMode'] == 'auto')
													 {
														$('a#'+add_button_id).css({"pointer-events" : "none", "color" : "#ccc"}); 
													 }
													 else
													 {
														$('a#'+add_button_id).css({"pointer-events" : "auto", "color" : "#000"});
													 }
													 
													// return data;
													
												 },
												 error: function (request, status, error) {
													 //alert(request +" - "+ status + " - " + error);
													 //alert("error");
													form_error = "error";
													form_error_msg = error;
													//return false;
												 }
										
											});
										if(form_error == "error")
										{
											alert(form_error_msg );
											form_error = "";										
											return false;
											
										}
										else
										{											
											if(check_renderingtype != 3)
											{
												$("#saveTemplate").trigger( "click", 'Autosave' ); /// Auto Save and template is not commit
											}
											else
											{
												$("#saveTemplate").trigger( "click", "Advertisement_widget" ); 
												/// Auto Save advertisement widget and template is committed
											}
											//alert("Widget Config Successfully Saved");											
											parent.$.fancybox.close();
											show_toastr("Widget configuration successfully saved", 1);
											//$("#saveTemplate").trigger( "click", 'Autosave' );											
										}
									}
									else
									{
										return false;
										//parent.$.fancybox.close();
									}								
								}
							});
						}
					   
					  });
					//console.log(TemplateManagerObj.$widgetConfigURL);
					
		},
		show_articles: function(anchor_id)
		{
			var d 					= new Date();
			var current_time_stamp 	= d.getTime();
			var widget_details 	= {};
			var widget_obj 		= $('.widget-container-'+anchor_id+' .widget');
			
			$.each(widget_obj, function(){
				$.each(this.attributes, function() {
					widget_details[this.name] = escape(this.value);	 
				  });
			});
			$('#'+anchor_id).attr("disabled", true);
			var scroll_top 					= $(window).scrollTop();	
			var top_div		  				= $($('.widget-container-'+anchor_id)).parent().parent().parent().parent().attr('class').split(" ");	
			var top_div_class_values 		= top_div[1].split("_");
			var top_div_class 				= (top_div_class_values[1] * 12 ) / top_div_class_values[3];		
			var article_widget_instance_id 	= $('.widget-container-'+anchor_id+' .widget').data('widgetinstanceid');
			var live_version_id 			= $('#live_version_id').val();
			///////  Verify Widget article is locked or unlocked  ////
			var article_lock_obj = lock_widget_articles(article_widget_instance_id);	
			if(article_lock_obj.res_status == 0)			
			{
				if(article_lock_obj.show_msg == 1)
				{
					show_toastr(article_lock_obj.msg, article_lock_obj.msg_type);
				}		
			}
			else
			{
				$("#loading_msg").html("Please wait...");
				$("#commom_loading").show();
				
				var widget_pageid 				= $('.widget-container-'+anchor_id+' .widget').data('widgetpageid');	
				var page_sectionid				= IExp.pageMaster.sectionId;
				var pagetype					= IExp.pageMaster.pageType;
				var container_values 			= $($('.widget-container-'+anchor_id)).parent().attr('class');	
				var ishome						= $(".fancytree-active").parents('li').find('.fancytree-expanded').text().substring(0, 4).toLowerCase();
				var home_value					= "";
				if(ishome == "home")
				{
					home_value = ishome;
				}
				else
				{
					home_value = "";
				}
				$('.widget-container-'+anchor_id+' .widget').append(
					$('<form />', { action: base_url + admin_folder +"/template_designer/show_articles/", method: 'POST', style: "display: none", id: 'addWidgetArticles'+current_time_stamp, "target":"_blank" }).append(
						$('<input />', { id: 'article_widget_instance_id', name: 'article_widget_instance_id', type: 'hidden', value: article_widget_instance_id }),
						$('<input />', { id: 'widget_pageid', name: 'widget_pageid', type: 'hidden', value: widget_pageid }),
						$('<input />', { id: 'page_sectionid', name: 'page_sectionid', type: 'hidden', value: page_sectionid }),
						$('<input />', { id: 'page_pagetype', name: 'pagetype', type: 'hidden', value: pagetype }),
						$('<input />', { id: 'container_values', name: 'container_values', type: 'hidden', value: container_values }),
						$('<input />', { id: 'top_div_class', name: 'top_div_class', type: 'hidden', value: top_div_class }),
						$('<input />', { id: 'home_value', name: 'home_value', type: 'hidden', value: home_value }),			
						$('<input />', { id: 'scroll_top', name: 'scroll_top', type: 'hidden', value: scroll_top }),			
						$('<input />', { id: 'widget_values', name: 'widget_values', type: 'hidden', value: JSON.stringify(widget_details) }),
						$('<input />', { id: 'is_related_article', name: 'is_related_article', type: 'hidden', value: 'no' }),			
						$('<input />', { id: 'version_id', name: 'version_id', type: 'hidden', value: IExp.pageMaster.versionId }),							
						$('<input />', { id: 'live_version_id', name: 'live_version_id', type: 'hidden', value: live_version_id }),
						$('<input />', { id: 'rootstructures', name: 'rootstructures', type: 'hidden', value: IExp.pageMaster.rootstructures }),
						$('<input />', { id: 'add_wdigetarticles'+current_time_stamp, type: 'submit', value: 'Add Widget Articles', style: "display: none" })
					)
				);
				$('#addWidgetArticles'+current_time_stamp).submit();
				$("#loading_msg").html("");
				$("#commom_loading").hide();
			}
			
		},
		show_cloned_children: function(anchor_id, cloned_parent_instance_id){
			var d 					= new Date();
			var current_time_stamp 	= d.getTime();
			var cloned_parent_instance_id 	= $('.widget-container-'+anchor_id+' .widget').data('widgetinstanceid');
			$("#loading_msg").html("Please wait...");
			$("#commom_loading").show();			
			$('.widget-container-'+anchor_id+' .widget').append(
				$('<form />', { action: base_url + admin_folder +"/cloned_widgets/", method: 'POST', style: "display: none", id: 'cloned_widgets'+current_time_stamp, "target":"_blank" }).append(
					
					$('<input />', { id: 'cloned_parent_instance_id', name: 'cloned_parent_instance_id', type: 'hidden', value: cloned_parent_instance_id }),
					$('<input />', { id: 'redirect_child_clone'+current_time_stamp, type: 'submit', value: 'Redirect to Child Clone', style: "display: none" })
				)
			);
			$('#cloned_widgets'+current_time_stamp).submit();
			$("#loading_msg").html("");
			$("#commom_loading").hide();
		},
		release_locks_by_user_id: function()
		{									
			$.ajax({				
				//url: base_url + admin_folder +"/template_designer/is_lock_free_advertisements_version",
				url: base_url + admin_folder +"/template_designer/release_locks_by_user_id",
				type: 'post',
				async: false,
				data: "versionId=" + IExp.pageMaster.versionId,				
				dataType: 'json',
				beforeSend: function() {
						$("#loading_msg").html("Please wait...");
						$("#commom_loading").show();
						}, 						
				success: function (data) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");
					/* Locks released based on user id */
					if(data){ 
						//show_toastr("Locks released", 1); 
					}
					else{ show_toastr("Failed to release locks", 2); }
				},
				error: function (e) {
					$("#commom_loading").hide();
					$("#loading_msg").html("");	
					alert("error");						
				}
			});
			
		
		},
		verify_clone_mapping: function(clone_instance_id, called_from){
			var return_clone_map_status = 0;
			$.ajax({
				url		: base_url + admin_folder +"/template_designer/verify_clone_mapping",	
				type	: "post",
				data	: "clone_instance_id="+ clone_instance_id +"&called_from="+ called_from,
				dataType: "json",
				async	: false,
				beforeSend : function(){
					
				},
				success	: function(result){
							if(result.show_msg == 1){
								show_toastr(result.msg, result.msg_type);
							}
							return_clone_map_status = result.res_status;
							
				},
				error	: ""
			});
			return return_clone_map_status;
		},
		show_html_elements: function(html_element_list)
		{
			//console.log("show");
			//console.log(html_element_list);
			$.each(html_element_list,function(index, element_id_or_class)
			{
				 $( element_id_or_class ).show(); 
			});
		},
		hide_html_elements: function(html_element_list)
		{
			//console.log("hide");
			//console.log(html_element_list);
			$.each(html_element_list,function(index, element_id_or_class)
			{ 
				$(element_id_or_class).hide(); 
			});
		},
		show_not_published_info: function(is_changes_published)
		{
			var live_version_id = $('#live_version_id').val();			
			if(IExp.pageMaster.versionId == live_version_id){
				if(is_changes_published != 1){
					$('#not-published-info').text("");
				}else{
					$('#not-published-info').text("Design changes are not published");
				}
			}else{
				$('#not-published-info').text("");
			}
		},
		trigger_fancy_tree_click: function()
		{
				
				var show_html_obj = ['.lock_template'];
				var hide_html_obj = ['#edit_versionName_fields'];
				
				$('#edit_versionName_fields').hide();
				$("#tree").fancytree("getTree").activateKey(IExp.qParamPageId);
				 $("#tree").fancytree("getTree").getNodeByKey(IExp.qParamPageId).makeVisible({expandParents:true});
				IExp.pageMaster.pageId = IExp.qParamPageId;
				//TemplateManagerObj.loadTemplate(IExp.qParamPageId, '');	
				//alert(IExp.pageMaster.versionId);
				TemplateManagerObj.loadTemplate(IExp.qParamPageId, IExp.pageMaster.versionId);	
				TemplateManagerObj.loadTemplateVersions(IExp.pageMaster.pageId, IExp.pageMaster.versionId); ////  Show template version dropdown 
				$('.lock_template').show();
				
				var rootstructures = TemplateManagerObj.show_tree_hierarchy_of_current_node($("#tree").fancytree("getActiveNode"));				
				$('#rootstructures').html(rootstructures);								
				
				if(typeof IExp.pageMaster.versionId === 'undefined' || IExp.pageMaster.versionId == '')
				{
					if(IExp.userRole.canAddAdv){								
						//$('.header_script_publish').hide();			
						hide_html_obj.push('.header_script_publish');
					}
					//$('#edit_version_name').hide();
					//$('#delete_currentVersion_template').hide();
					hide_html_obj.push('#edit_version_name', '#delete_currentVersion_template');
				}
				else
				{
					var live_version_id = $('#live_version_id').val();
					if(IExp.userRole.canAddAdv){					
						show_html_obj.push('.header_script_publish', '#addheader_script');
					}	
					else
					{
						hide_html_obj.push('.header_script_publish', '#addheader_script');
					}
					if(live_version_id != IExp.pageMaster.versionId && live_version_id != '')
					{								
						if($("#show_template_version").length > 1)
						show_html_obj.push('#delete_currentVersion_template');		
					}
					else
					{
						hide_html_obj.push('#delete_currentVersion_template');
					}
					show_html_obj.push('#edit_version_name', '#common_options');
				}
				
				
				TemplateManagerObj.show_html_elements(show_html_obj);
				TemplateManagerObj.hide_html_elements(hide_html_obj);	
				//alert(IExp.pageMaster.sectionId);
				if(IExp.pageMaster.sectionId == "10000"){ $('#preview').hide(); }else{  $('#preview').show(); }
					
		}
		
	};	
	$().ready(function() {
		TemplateManagerObj.init();				
		//TemplateManagerObj.validate_login();
	});	
	
	//////  Publish only advertisements and Header script version based  ////
	$('#publish_advertisements').click(function(){
		/* Verify is any widget (configuration or add widget articles) is locked */
		var is_lock_free_adv_version = TemplateManagerObj.is_lock_free_advertisements_version();					
		if(is_lock_free_adv_version == 1)
		{
			if(confirm("Are you sure you want to publish all advertisements?")){			
				TemplateManagerObj.publish_only_advertisements();		
			}else
			{
				return false;
			}
		}
		
	});
	
	/////////////////////////////////// Template Lock functions Start ///////////////////////////////////////
	////  Lock / Unlock template  /////
	$('.lock_template').on("click", function(){
		
		var lock_value = $(this).attr("data-lockStatus");
		var confirm_msg= (lock_value == 2) ? "Do you want to unlock the template design?" : "Do you want to lock the template design?";
		
		$('.lock_template').addClass("locked"); ////  Disable Lock Image  /////
		$("#loading_msg").html("Please wait....");
		$("#commom_loading").show();
		var commit_status = TemplateManagerObj.get_template_commit_status();	
		//alert(commit_status.locked_status);	
		if(commit_status.tempalte_commit_status != 1) //////  !=1 means template is not commited  /////
		{			
			//////  Prompt user to save template or not save  ///			
			$( "#confirm-1" ).dialog( {
					resizable	: false,
					modal		: true,
					title		: "Do you want to save changes?",
					height		: 250,
					width		: 325,
					buttons		: {
									"Save": function() {
										var do_below_functions = {
											"" : $("#saveTemplate").trigger( "click", "Advertisement" ),
											"" : TemplateManagerObj.update_template_lock(lock_value)
											};			  												
										TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);
									},
									"Don't save": function() {		
										//alert("Dont save template");
										var do_below_functions = { "" : TemplateManagerObj.dont_save_template(IExp.pageMaster.pageId), "": TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, ''), "" : TemplateManagerObj.update_template_lock(lock_value) };
										TemplateManagerObj.dialog_callback(2, $(this), $('.lock_template').removeClass("locked"));
									},
									Cancel: function() {
										TemplateManagerObj.dialog_callback(3, $(this), $('.lock_template').removeClass("locked"));	
									}
								  }
		  });	
		  $(".ui-dialog-buttons").addClass("save-dialog-box");
		  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
		  $(".ui-dialog-titlebar-close").click(function(){
			  $('.lock_template').removeClass("locked");
		  });
		}
		else
		{
			TemplateManagerObj.update_template_lock(lock_value);
		}
	});
	////////////////////////////// Template Lock functions end /////////////////////////////////
	
	
	
	////  Edit current version Name  /////
	$('#edit_version_name').click(function(){
		$(window).scrollTop(IExp.qParamScrollTop);
		//edit_versionName_fields
		/////  verify is current templates is locked by the user  /////
		var template_lock_status = tree_based_locktemplate(IExp.pageMaster.pageId); ///// From template_designer.php (view)  /////
		if(template_lock_status != false)
		{
			$('#edit_versionName_fields').toggle();
			var old_version_name = $('#show_template_version :selected').text().replace("live", "").trim();
			$('#edit_version_name_input').val(old_version_name);
			$('#edit_version_name_input').focus();
		}
		else
		{
			$('#edit_versionName_fields').hide();
		}
		//console.log(window.scrollTop);
		//window.scrollTop = 0;
	});
	
	$('#cancel_version_name').click(function(){
		$('#edit_versionName_fields').hide();
	});
	
	$('#saveVersionName').click(function(){
		var version_name = $('#edit_version_name_input').val().trim();
		if(version_name != '')
		{
			if(confirm("Are you sure you want to update version name?"))
			{			
				TemplateManagerObj.update_versionName(version_name);
			}
			else
			{
				return false;
			}
		}
		else
		{
			show_toastr("Please enter the version name", 2);
		}
		
		
	});
	
	////  Delete Current version template  /////
	$('#delete_currentVersion_template').click(function(){
		/////  verify is current templates is locked by the user  /////
		var template_lock_status = tree_based_locktemplate(IExp.pageMaster.pageId); ///// From template_designer.php (view)  /////
		if(template_lock_status != false)
		{
			if($('#live_version_id').val() != IExp.pageMaster.versionId )
			{
				var commit_status = TemplateManagerObj.get_template_commit_status();		
				if(commit_status.tempalte_commit_status == 1) //////  !=1 means template is not commited  /////
				{
					var current_version_name = $('#show_template_version option:selected').text().trim();
					//alert(current_version_name);
					if(confirm("Are you sure you want to delete '"+ current_version_name +"'?"))
					{
						TemplateManagerObj.delete_currentTemplateVersion();
					}
					else
					{
						return false;
					}
				}
				else
				{
					show_toastr("Please save the template", 2);
				}
				
			}
			else
			{
				show_toastr("This version is in live, so you can't delete this version", 2);
			}
		}
	});
	
	//////  Logout, CMS menus  ////
	$('.is-template-version-saved').click(function(){
		var commit_status = TemplateManagerObj.get_template_commit_status();		
		if(commit_status.tempalte_commit_status != 1) //////  !=1 means template is not commited  /////
		{
			var after_action = $(this).attr("href")			
			//////  Prompt user to save template or not save  ///			
			$( "#confirm-1" ).dialog( {
					resizable	: false,
					modal		: true,
					title		: "Do you want to save changes?",
					height		: 250,
					width		: 325,
					buttons		: {									
									"Save": function() {
										var do_below_functions = {
											"" : $("#saveTemplate").trigger( "click", "Advertisement" ), "":TemplateManagerObj.update_template_lock(1), "": TemplateManagerObj.release_locks_by_user_id(), "": window.location.href = after_action
											};			  												
										TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);
									},
									"Don't save": function() {		
										//alert("Dont save template");
										var do_below_functions = { "" : TemplateManagerObj.dont_save_template(IExp.pageMaster.pageId), "":TemplateManagerObj.update_template_lock(1), "": TemplateManagerObj.release_locks_by_user_id(), "": window.location.href = after_action};
										TemplateManagerObj.dialog_callback(2, $(this), do_below_functions);	
									},
									Cancel: function() {										
										TemplateManagerObj.dialog_callback(3, $(this), '');	
									}
								  }
		  });	
		  $(".ui-dialog-buttons").addClass("save-dialog-box");
		  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
		}
		else
		{
			TemplateManagerObj.release_locks_by_user_id();
			return true;
		}
		return false;
	});
	
	/* Header Script button */
	$('.save_changes_in_version').click(function(){
		var current_id = $(this).attr("id");
		var commit_status = TemplateManagerObj.get_template_commit_status();		
		if(commit_status.tempalte_commit_status != 1) //////  !=1 means template is not commited  /////
		{			
			//////  Prompt user to save template or not save  ///			
			$( "#confirm-1" ).dialog( {
					resizable	: false,
					modal		: true,
					title		: "Do you want to save changes?",
					height		: 250,
					width		: 325,
					buttons		: {									
									"Save": function() {
										var do_below_functions = {
											"" : $("#saveTemplate").trigger( "click", "Advertisement" ), "": $('#'+current_id).trigger("change"), "": TemplateManagerObj.release_locks_by_user_id()
											};			  												
										TemplateManagerObj.dialog_callback(1, $(this), do_below_functions);
									},
									"Don't save": function() {		
										//alert("Dont save template");
										var do_below_functions = { "" : TemplateManagerObj.dont_save_template(IExp.pageMaster.pageId), "": $('#'+current_id).trigger("change"), "": TemplateManagerObj.release_locks_by_user_id() };
										TemplateManagerObj.dialog_callback(2, $(this), do_below_functions);	
									},
									Cancel: function() {										
										TemplateManagerObj.dialog_callback(3, $(this), '');	
									}
								  }
		  });	
		  $(".ui-dialog-buttons").addClass("save-dialog-box");
		  $(".ui-dialog-titlebar-close").addClass("fa fa-times");
		}
		else
		{
			TemplateManagerObj.release_locks_by_user_id();
			$('#'+current_id).trigger("change");
		}
		return false;
	});
	
	///////  Adding Header advertisement script  ////////
	$('#addheader_script').change(function(){
		/* Get the header script lock status */
		var header_script_lock_status = TemplateManagerObj.get_currentVersion_headerScript_lockStatus();
		if(header_script_lock_status.access_status)
		{
			$("#loading_msg").html("Please wait...");
			$("#commom_loading").show();
			var scroll_top 		= $(window).scrollTop();	
			var page_sectionid	= IExp.pageMaster.sectionId;
			var pagetype		= IExp.pageMaster.pageType;
			var page_id			= IExp.pageMaster.pageId;
			//var pagesection_name= $(".fancytree-active").parents('li').find('.fancytree-title:first').text();			
			if(pagetype == "1")
			{
				var pagesection_name= $(".fancytree-active").find('.fancytree-title:first').text();	
			}
			else
			{
				//find('.fancytree-title').text();	
				var pagesection_name= $(".fancytree-active").parent('li').parent('ul').parent('li').children('span').find('span:last').html();
			}		
			$('#header_script_form').html(
				$('<form />', { action: base_url + admin_folder +"/template_designer/add_header_script/", method: 'POST', style: "display: none", id: 'addHeaderScript' }).append(			
					$('<input />', { id: 'page_sectionid', name: 'page_sectionid', type: 'hidden', value: page_sectionid }),
					$('<input />', { id: 'pagetype', name: 'pagetype', type: 'hidden', value: pagetype }),
					$('<input />', { id: 'scroll_top', name: 'scroll_top', type: 'hidden', value: scroll_top }),
					$('<input />', { id: 'pagesection_name', name: 'pagesection_name', type: 'hidden', value: pagesection_name }),	
					$('<input />', { id: 'versionId', name: 'versionId', type: 'hidden', value: IExp.pageMaster.versionId }),						
					$('<input />', { id: 'page_id', name: 'page_id', type: 'hidden', value: page_id }),
					$('<input />', { id: 'add_header_script', type: 'submit', value: 'Add Header Script', style: "display: none" })
				)
			);	
			$('#addHeaderScript').submit();
		}
		else
		{
			return false;
		}
	});
	
	$("#reset_rollback").click(function(event, string){
		
		var need_to_confirm = (typeof string === "undefined" || string === "undefined") ? confirm("You will lose the changes \n Are you sure want to restore this version?") : true;
		if(need_to_confirm){
			$.ajax({
					//url: "inc/save-xml-template.php",				
					url: base_url + admin_folder +"/template_designer/restore_last_updated_design",	//delete_temporary_instance			
					type: 'post',
					async: false,
					data: "&pageId=" + IExp.pageMaster.pageId + "&saveTemplateStatus=2" + "&version_id=" + IExp.pageMaster.versionId,
					dataType: 'json',
					beforeSend : function(){
						$("#commom_loading").show();
						$("#loading_msg").html("Please wait...");	
					},
					success: function (data) {
						$("#commom_loading").hide();
						$("#loading_msg").html("");							
						if(data.res_status == 1){
							TemplateManagerObj.loadTemplate(IExp.pageMaster.pageId, IExp.pageMaster.versionId);	
							TemplateManagerObj.show_hide_unsaved_template_msg(1); // 1->saved, 2->unsaved
							$('#saveTemplate').hide();
							$("#reset_rollback").hide();
							if(data.show_msg == 1){ show_toastr(data.msg,data.msg_type); }
							
						}else{							
							if(data.show_msg == 1){ show_toastr(data.msg,data.msg_type); }
							$("#reset_rollback").show();
						}
					},
					error: function (e) {
						
						$("#commom_loading").hide();
						$("#loading_msg").html("");	
						$('#saveTemplate').show();	
						$("#reset_rollback").show();
					}
				});
		}
	});
};
