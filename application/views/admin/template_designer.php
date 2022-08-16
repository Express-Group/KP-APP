<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 

if($this->session->flashdata("page_id") != '')
{
$page_id 				= $this->session->flashdata("page_id");
$page_section_id 		= $this->session->flashdata("page_section_id");
$page_type 				= $this->session->flashdata("page_type");
$scroll_top				= $this->session->flashdata("scroll_top");
$current_templ_version_id = $this->session->flashdata("current_templ_version_id");
$query_string_version_id =  $this->session->flashdata("query_string_version_id");
}
else ////  Show Default Template (Home Section page) //////
{	
	$scroll_top				= 0;
	$query_string_version_id=NULL;
}
$template_design_css 	= image_url."css/admin/template_design/";
$template_design_js 	= image_url."js/admin_view/template_design/";
$template_design_images	= image_url."images/admin/template_design/";
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/reset.css" media="screen" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Stylesheets -->
    
    <link rel="stylesheet" href="<?php echo $template_design_css; ?>css/Overide.css" media="all">
    
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/html5reset.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/col.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/2cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/3cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/4cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/5cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/6cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/7cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/8cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/9cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/10cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/11cols.css" media="all">
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/gridsystem/12cols.css" media="all">
	
    <link rel="stylesheet" href="<?php echo $template_design_css; ?>css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/template-manager.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/template-designer.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/jqModal.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/iexp-core.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/skin-xp/ui.fancytree.css" >
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/jquery.fancybox.css?v=2.1.5" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $template_design_css; ?>css/jquery-ui-override.css?v=2.1.5" media="screen" />
	
	<link rel="stylesheet" href="<?php echo $template_design_css; ?>css/font-awesome.min.css" />

</head>

<div class="Container">

<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper ">
				<div class="breadcrumbs"><a href="<?php echo base_url().folder_name; ?>" class="is-template-version-saved" >Dashboard</a> > <a href="<?php echo base_url().folder_name."/template_designer" ?>" class="is-template-version-saved" >Template Designer</a></div>
 					<h2>Template Designer</h2> 
			</div>
           
            <span class="FloatRight save_margin article_save template_preview"> 
            <a class="back-top FloatLeft widget-preview dynamic_design_link" href="#" id="preview" target="_blank" title="CMS Preview"><i class="fa fa-desktop i_extra"></i></a> 
            </span>
                        
            <span class="FloatRight save-wid save-back save_margin article_save design_view_span" style="display:none;">             	
                
            <?php if(FPM_ADDPAGEDESIGN){ ?>
            <span class="lock_panel">
            	<?php if(FPM_AddPagePublish){ ?>
         <button name="publish_template" id="publish_template" title="Publish Template" type="button" class="btn btn-primary FloatRight i_button"><i style="" class="fa fa fa-flag"></i></button>  
         		<?php } ?>
                
         <!-- Edit Version Name -->       
         <button id="edit_version_name" name="edit_version_name" title="Edit current version name" type="button" class="btn btn-primary FloatLeft i_button" ><i class="fa fa-pencil"></i></button>
         <!-- End Edit Version Name -->
         
         <!-- Template Version Delete -->       
         <button id="delete_currentVersion_template" name="delete_currentVersion_template" title="Delete current template version" type="button" class="btn btn-primary FloatLeft i_button" ><i class="fa fa-trash-o"></i></button>
         <!-- End Template Version Delete -->
                
  		<button class="btn-primary FloatLeft right-wid save-margin-right" id="saveTemplate" style="display:none;"><i class="fa fa-floppy-o" data-original-title="Active"></i> &nbsp;Update version</button>
        
        <!--<button class="btn-primary FloatLeft right-wid save-margin-right" id="make_version"><i class="fa fa-clipboard" data-original-title="Active"></i> Make New Version </button>-->
        <button class="btn-primary FloatLeft right-wid save-margin-right" id="make_version"><i class="fa fa-clipboard" data-original-title="Active"></i> Copy Current Version </button>
        	</span>
        <?php } ?>
            </span>            
            <?php if(FPM_ADDPAGEDESIGN){ ?>
            <!-- Template Lock / Unlock Icon -->
            <span class="FloatRight save_margin article_save ">                
                <button id="lock_templateDesign" name="lock_templateDesign" title="Lock template" class="btn btn-primary FloatLeft right-wid save-margin-right lock_template ">Lock</button>
            </span>
            <span class="FloatRight save_margin article_save template_preview">  <a style="height:39px;display:none;" href="#" data-remodal-target="modal1" id="copy_source" class="copy_source btn btn-primary FloatLeft right-wid save-margin-right">Copy Source</a></span> 
            <!-- End Template Lock -->
            <?php } ?>
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
				margin-top: 7px;
				box-shadow: 0 1px 1px 2px #ccc;
				padding: 0 10px;
				margin-left: 3px;
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
            
            
            <!-- Template versions dropdown -->
             <span id="template_version_dropdown"  class="template_version">
             
             </span>
             <!-- End Template versions dropdown -->
          
             
		</div>
 <div class="Overflow DropDownWrapper FrontPageManager">       
        
<div id="center-wrapper">	

	 <?php if(FPM_ADDPAGEDESIGN){ ?>
               <!-- Template lock info -->
               <div class="lock-status-info ">
                <div class="lock-status-border">
                	<span class="FloatLeft">Note: <span id="not-published-info" style="color:#F30" ></span> </span>
					
                    <span class="unsaved_template_msg">&nbsp</span>                    
                    <div class="FloatRight" >
					<span>Status:&nbsp;<span id="lock_info"></span>
                    <span  id="locked_by_info" ></span>
					</div>
                </div>
               </div>
               <!-- Template lock info -->
          <?php } else{
			echo '<style>
			.wrapper{
				margin-top:0 !important;
				}
			</style>';  
		  }?>
	
	
	<div class="wrapper">
		<div class="section group">
					<div class="col span_2_of_12  FrontPageLeft Tree">
						<h2 class="section-title SectionTitle"> Section Map</h2> 
                        <div style="background:#f2f2f2;text-align:center;border-bottom:1px solid #ccc">                        
        	            <input type="button" name="btnCollapseTree" id="btnCollapseTree" value="Collapse All" class="active" style="width: auto; margin-bottom: 13px; margin-top: 10px;"/>
                        <input type="button" name="btnExpandTree" id="btnExpandTree" value="Expand All" style="width: auto; margin-bottom: 13px; margin-top: 10px;"/>&nbsp;
                        </div>
						<div id="tree">
							<ul>
							<?php
							//////// Using sectionmaster table  ////////
							
							function page_landing($val, $attribs_array_details = NULL)
							{
								
								////////  If we want Section page and article page $val = 2, Only Section page then below $val value should be Comment  //////
								
								$landing	= "";
								foreach($attribs_array_details as $attribs_array)
								{
									$attribs[@$attribs_array['pagetype']] = 'id="'.@$attribs_array['id'].'" data-sectionId="'.@$attribs_array['menuid'].'" data-pageType="'.@$attribs_array['pagetype'].'" data-pageId="'.@$attribs_array['id'].'"  data-hasTemplate ="'.@$attribs_array['hasTemplate'].'" ';
								}
								switch($val)
								{
									case 1:
											$landing .= '';											
											break;									
									case 2:											
											$landing .= '<li '. @$attribs[2] .'>article</li>';
											break;
								}
								
								return $landing;
							}
							$have_child_sections = "";
							if(count($section) > 0)
							{
								$common_template_page = @$section[count($section)-2]['page_det'][0]; /// only for article page
								if($common_template_page['menuid'] == 10000)
								{																								
								echo '<li class="" id="'.@$common_template_page['id'].'" data-sectionId="'.@$common_template_page['menuid'].'" data-pageType="1" data-pageId="'.@$common_template_page['id'].'" data-hasTemplate ="'.@$common_template_page['hasTemplate'].'" >Common Templates</li>';
								
								$standard_article_page = @$section[count($section)-2]['page_det'][1]; /// only for article page
								echo '<li class="" id="'.@$standard_article_page['id'].'" data-sectionId="'.@$standard_article_page['menuid'].'" data-pageType="2" data-pageId="'.@$standard_article_page['id'].'" data-hasTemplate ="'.@$standard_article_page['hasTemplate'].'" >Common Article Page</li>';
								}
								
								/* for clone widget template */
								$clone_template_page = @$section[count($section)-1]['page_det'][0]; /// only for article page
								if($clone_template_page['menuid'] == 10001)
								{																								
								echo '<li class="" id="'.@$clone_template_page['id'].'" data-sectionId="'.@$clone_template_page['menuid'].'" data-pageType="1" data-pageId="'.@$clone_template_page['id'].'" data-hasTemplate ="'.@$clone_template_page['hasTemplate'].'" >Clone widgets Templates</li>';								
								}

								$inc = 0;
								$last_inc = 0;
								foreach($section as $section_value)
								{
								  if($last_inc < (count($section)-2))
								  {
									
									$attribs_array = "";
									$attribs_array = @$section_value['page_det'][0];
									
									$attribs[@$attribs_array['pagetype']] = 'id="'.@$attribs_array['id'].'" data-sectionId="'.@$attribs_array['menuid'].'" data-pageType="'.@$attribs_array['pagetype'].'" data-pageId="'.@$attribs_array['id'].'"  data-hasTemplate ="'.@$attribs_array['hasTemplate'].'" ';
									
									$sub_menu 				= $section_value['sub_section'];
									$have_child_sections 	= (count($sub_menu)>0) ? "have-child-section" : "";
									echo '<li class="'.$have_child_sections.'" '. @$attribs[1] .'>'.$section_value['Sectionname'];
									if($inc > 1)
									{
										$inc = 0;
									}
									
									if(count($sub_menu)>0)
									{
										
										echo '<ul>';		
																	 
										//////////  Parent folder having child folders -  Landing pages  ///////////
										$landing = page_landing($section_value['Section_landing'],@$section_value['page_det']);
										
										//////  dont comment below echo  //////
										echo $landing;	 								
										foreach($sub_menu as $submenu_value)
										{										
											$submenu_value['pagetype'] 		= "section";
											$submenu_value['id']			= $submenu_value['Section_id'];
											$section_value['menucategory']	= $section_value['Sectionname'];
											$submenu_value['hasTemplate'] 	= "";
											
											$sub_attribs_array = "";
											$sub_attribs_array = @$submenu_value['page_det'][0];
											
											$sub_attribs[@$sub_attribs_array['pagetype']] = 'id="'.@$sub_attribs_array['id'].'" data-sectionId="'.@$sub_attribs_array['menuid'].'" data-pageType="'.@$sub_attribs_array['pagetype'].'" data-pageId="'.@$sub_attribs_array['id'].'" data-hasTemplate ="'.@$sub_attribs_array['hasTemplate'].'" ';
											
											$special_menu_details = @$submenu_value['special_section'];
											$have_child_sections 	= (isset($special_menu_details[0]['Section_id'])) ? "have-child-section" : "";
											echo '<li class="'.$have_child_sections.'" '. @$sub_attribs[1] .'>'. $submenu_value['Sectionname'];											
												$landing = page_landing($submenu_value['Section_landing'],@$submenu_value['page_det']);
												
												echo "<ul>".$landing;	
												
												
												if(isset($special_menu_details))
												{
													foreach($special_menu_details as $special_menu)
													{
														
														$special_menu['pagetype'] 		= "section";
														$special_menu['id']				= $special_menu['Section_id'];
														$special_menu['menucategory']	= $special_menu['Sectionname'];
														$special_menu['hasTemplate'] 	= "";
														
														$special_menu_attribs_array = "";
														$special_menu_attribs_array = @$special_menu['page_det'][0];
														
														$special_menu_attribs[@$special_menu_attribs_array['pagetype']] = 'id="'.@$special_menu_attribs_array['id'].'" data-sectionId="'.@$special_menu_attribs_array['menuid'].'" data-pageType="'.@$special_menu_attribs_array['pagetype'].'" data-pageId="'.@$special_menu_attribs_array['id'].'" data-hasTemplate ="'.@$special_menu_attribs_array['hasTemplate'].'" ';
														
														echo '<li class="" '. @$special_menu_attribs[1] .'>'. $special_menu['Sectionname'];											
														$landing = page_landing($special_menu['Section_landing'],@$special_menu['page_det']);
														
														echo "<ul>".$landing."</ul>";	
													}
												}
												echo "</ul>";
																													
											echo '</li>';										
										}									
										echo '</ul>';																		
									}									
									else
									{
										//////////  Parent folder dont having child folders -  Landing pages  ///////////
										$landing = page_landing($section_value['Section_landing'],@$section_value['page_det']);
										echo "<ul>".$landing."</ul>";	
									}
										echo '</li>';					                 
								  }
								$last_inc ++;
								}
							}
							else
							{
								echo '<li class="folder"> No Sections </li>';		
							}											
							?>								
							</ul>
						</div>
					</div>
                    
                    <?php 					
					$col_span_values = (FPM_ADDPAGEDESIGN) ? 'span_6_of_12' : 'span_10_of_12';
					?>
                    
					<div class="col <?php echo $col_span_values; ?> FrontPageMiddle">
						<div class="iexp-example-section" >
                            <?php 
							if(FPM_ADDPAGEDESIGN){
							?>
                            <!-- Edit version Name Text field -->
                            <style>
							
							button#lock_templateDesign {
								width: auto;								
							}
							.edit-version-name{
								width:100%;
								float:left;
								border-bottom: 2px solid #68A;
								margin-bottom:5px;
								margin-top:5px;
								position: relative;
							}
							.edit-version-name ul{
								width:100%;
								float:left;
							}
							.edit-version-name li{
								border:none !important;
								float:left;
								width:100%;
								color: #404244;
								font-weight: normal;
							}
							.edit-version-name input{
								width:210px;
							}
							.edit-version-name li span{
								margin: 0 3px;
								color: #F44336;
								vertical-align:middle;
							}
							</style>
                            <div class="edit-version-name">
                            <ul>
                            <li class="iexp-h3 iexp-example-title" id="rootstructures"></li><!-- This li-ID is used for Show root structure of tree  -->
                            <li class="iexp-example-description">Drag and drop items within a list.</li>
                            </ul>
							<span id="edit_versionName_fields"  style="display:none; float: left; margin-bottom: 10px;">
                            	<input type="text" name="edit_version_name_input" id="edit_version_name_input" value="" placeholder = "Enter Version name"  />
                                <button class="btn-primary right-wid" name="saveVersionName" id="saveVersionName" title="Save version name"><i class="fa fa-floppy-o" data-original-title="Active"></i></button>
                                <button class="btn-primary right-wid" name="cancel_version_name" id="cancel_version_name" title="Cancel"><i class="fa fa-times" data-original-title="Active"></i></button>
                            </span>
                            <!-- End Edit version Name Text field -->
							<!-- Restore -->
							<span id="roll_back_buttons" style="float: right; margin-left:5px; margin-bottom: 10px" >								
								<button class="right-wid restore-button" name="reset_rollback" id="reset_rollback" title="Restore" data-rollforwardid = "" style="display:none;"><img src="<?php echo $template_design_images."images/restore_xml.png"; ?>" /></button>
								
							</span>
							<style>
							.restore-button{
								border: 2px solid #3c8dbc;
								background: none;
								padding: 3px;
								border-radius: 4px;
								position: absolute;
								bottom: 5px;
								right: 0;
							}
							.restore-button img{
								width: 35px;
							}
							</style>
							<!-- End Restore -->
							
							
                            <?php } ?>
                            
						
                            
							</div>
                            
                            
                            <!-- Header Script Button -->                            
                            <span class="header_script_publish FloatRight" style="display:none;">
								 <?php if(FPM_ADDADVSCRIPTS){ ?>
                                 
                                 	<div class="SaveBackTop FloatLeft margin-top-0">
	                                 <a href="javascript:;" class="btn-primary btn save_changes_in_version margin-right-5" id="addheader_script">Header Script</a>
                                     </div>
                                    
                                 <span id="header_script_form"></span>
                                 <!-- End Header Script Button -->
                                  <span class="save-back article_save">
                                  <div class="SaveBackTop PubUnpublish FloatLeft margin-top-0">
                                  <button name="publish_advertisements" id="publish_advertisements" title="Publish advertisements only" type="button" class="btn btn-primary i_button"  style="display:none;"><i style="" class="fa fa fa-flag"></i></button> 
                                  </div>
                                  </span>
                            <?php } ?>                             	
                             </span>
                            
                            <div class="lock_panel locked" id="common_options">
                            
                            </div>
                            
                            
							<div id="IExpWorkSpace" class="FloatLeft" style="width:100%">
							
							</div>							
						</div>
					</div>
					<?php 
					if(FPM_ADDPAGEDESIGN){
					?>
                    	<div class="col span_4_of_12 margin-top-0" >
						<div id="fm-left-panel" class="top-fix locked">
							<h2 class="action-bar-title margin-top-0">Action Bar</h2>
							
							<div id="fmLeftTabs" class="design-panel">
                            
								<ul>
									<li class="ui-state-active"><a href="#TemplateTypeHolder" class="-left-panel-tabs">Templates</a></li>
									<li><a href="#widgetContainerTypeHolder" class="-left-panel-tabs">Containers</a></li>
									<li><a href="#widgetHolder" class="-left-panel-tabs">Widgets</a></li>			
								</ul>  
								<div id="TemplateTypeHolder" class="left-panel-holders">	
									<div id="addTemplatesHolder" style="padding:4px;">
										<div class="template-holder">
											<span class="section-title">PAGE TEMPLATES: </span><br>
											<ul>
												<?php 
													
													foreach($page_templates as $templates)
													{
														echo '<li>
																	<a class="button add-template-button" id="addColTemplate_'.$templates['templateid'].'">
																		<img class="add-template-img" data-templateId='.$templates['templateid'].'   src="'.image_url.$templates['template_imagepath'].'"/>
																	</a> 
															  </li>';	
														
													}
												?>                                               
											</ul>									
										</div>
										<button class="btn-primary btn FloatLeft right-wid" id="new_empty_version" style="font-size:12px;" ><i class="fa fa-file-o" data-original-title="Active"></i> Make Empty Template Version</button>
                                       	
									</div>														
								</div>
  
								<div id="widgetContainerTypeHolder" class="left-panel-holders">
									<div id="addContainersHolder" style="padding:4px;">
									<span class="section-title">CONTAINERS:  </span><br>
									<?php
										$i = 1;
										foreach($container as $show_container)
										{
											echo '<a class="button" id="addContainer_'.$show_container['containerid'].'">
													<img class="add-widget-container-img"  data-typeId='.$show_container['containerid'].' src="'.image_url.$show_container['container_imagepath'].'"/>
												  </a> ';
												  $i++;
										}
									?>                                   
									</div>
								</div>
								<div id="widgetHolder" class="left-panel-holders">									
								  <div class="widget_list_header">	
                                        <span class="section-title">WIDGETS:  </span>                                         
										<div class="align-clone-widget-image">
											<!-- Show cloned check box -->
											<span class="">
											<input type="checkbox" name="show_cloned_widgets" class="show-cloned-widgets"  id="show_cloned_widgets" /> <label for="show_cloned_widgets">Show Cloned</label>                                         
											</span>
											<!-- End Show cloned check box -->
											<span class="FloatLeft">
											<input type="checkbox" name="collapse_widget_images" id="collapse_widget_images" /> Show Images                                         
											</span>
										</div>
                                        <input class="search_widgets" id="widget_name_autocomplete" name="widget_name_autocomplete" placeholder = "Search widget name"/>
                                  </div>
                                  <style>
								  .cloned-widget{
									  display:none;
								  }
								  .align-clone-widget-image{
									width: 40%;
									float: right;
									margin-top: 12px;
								  }
								  .align-clone-widget-image span:first-child{
										width: 100%;
										float: left;
										text-align: left;
								  }
								  .align-clone-widget-image span:last-child{
										text-align: left;
								  }
								  </style>
                                    <br>
                                   
									<div class="widgetListContainer">

									</div>																
								</div>								
							</div>
						</div>
					</div>
                    
                   <?php 
					}
				   ?> 
		</div>	
	</div>
</div>
<div class="clear-fix"></div>

<!-- JavaScript files -->
<script type="text/javascript">
var base_url 					= "<?php echo base_url(); ?>";
var admin_folder 				= "<?php echo folder_name; ?>";
var template_design_images_url 	= "<?php echo image_url; ?>";

</script>
<script type="text/javascript" src="<?php echo $template_design_js; ?>js/lib/jquery.js"></script>
<script src="<?php echo $template_design_js; ?>js/jquery-ui.custom.js"></script>
<script src="<?php echo $template_design_js; ?>js/jquery.fancytree.js" type="text/javascript"></script>

<!-- JqModal -->
<script type="text/javascript" src="<?php echo $template_design_js; ?>js/jqDnR.js"></script>
<script type="text/javascript" src="<?php echo $template_design_js; ?>js/jqModal.js"></script>

<!--  fancebox -->
<script type="text/javascript" src="<?php echo $template_design_js; ?>js/jquery.fancybox.js?v=2.1.5"></script>

<!--  Block UI -->
<script type="text/javascript" src="<?php echo $template_design_js; ?>js/jquery.blockUI.min.js?v=2.1.5"></script>

<script type="text/javascript" src="<?php echo $template_design_js; ?>js/jquery.xml2json.js"></script>
<script type="text/javascript" src="<?php echo $template_design_js; ?>js/search-json.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $template_design_js; ?>js/jquery.ui-contextmenu.min.js"></script>

<script type="text/javascript" src="<?php echo $template_design_js; ?>js/template-manager.js?<?php echo time(); ?>"></script>

<!-- jQuery code (JavaScript)  -->
<script type="text/javascript">
/////  Show Toastr message  /////
function show_toastr(message, toastr_type)
{
	if(message != ''){
	///////  toastr_type = 1 means success message, toastr_type = 2 means Failure message, toastr_type = 3 means information message /////
	(toastr_type == 1) ? toastr.success(message) : (toastr_type == 2)? toastr.error(message) : toastr.info(message);
	}
}				

///////  Lock Add widget article button (if locked by other user)  ////////
function lock_widget_articles(widgetinstance_id)
{
	var article_lock_obj = {};
	$.ajax({					
			url: base_url + admin_folder +"/template_designer/lock_widget_articles",
			type: 'post',
			async:false,
			data: "widgetinstance_id="+ widgetinstance_id +"&return_type=json"+"&from_release_article_lock",
			success: function (data) {									
				article_lock_obj = data
			},
			error: function (e) {
				alert("Internal sever error");											
				}
		});		
		return article_lock_obj;
}

///////  Lock Add widget config button (if locked by other user)  ////////
function lock_widget_config(widgetinstance_id, page_id, lock_status, check_renderingtype)
{	
	var config_lock_obj = {};
	$.ajax({					
			url: base_url + admin_folder +"/template_designer/check_widget_config_status",
			type: 'post',
			async:false,
			data: "widgetinstance_id="+widgetinstance_id+"&lockTemplateid="+page_id+"&lock_status="+lock_status+"&return_type=json"+"&savexml_method=no&check_renderingtype="+check_renderingtype,
			success: function (data) {									
				config_lock_obj = data
				if(data.res_status == 1 && check_renderingtype != 3){
					$(".lock_template").html("Unlock");					
					$('.change_lock').removeClass("fa-lock");
					$('.change_lock').addClass("fa-unlock");				
					
					$('#fm-left-panel').removeClass("locked");
					$('.lock_panel').removeClass("locked");	
					$('.ui-sortable-handle').removeClass("locked");	
					$('.lock_template').attr("data-lockStatus", "1");	
					$('.design_view_span').show();
					show_lock_info(2, data.current_user_name);
				}
			},
			error: function (e) {
				alert("Internal sever error");											
				}
		});		
		return config_lock_obj;
}

///////  Lock container delete button (if locked by other user)  ////////
function lock_delete_container(widgetinstance_id, page_id, lock_status)
{	
	var config_lock_obj = {};
	$.ajax({					
			url: base_url + admin_folder +"/template_designer/container_lock_status",
			type: 'post',
			async:false,
			data: "widgetinstance_id="+widgetinstance_id+"&lockTemplateid="+page_id+"&lock_status="+lock_status+"&return_type=json"+"&savexml_method=no",
			success: function (data) {									
				config_lock_obj = data
				if(data.res_status == 1){
					$(".lock_template").html("Unlock");					
					$('.change_lock').removeClass("fa-lock");
					$('.change_lock').addClass("fa-unlock");				
					
					$('#fm-left-panel').removeClass("locked");
					$('.lock_panel').removeClass("locked");	
					$('.ui-sortable-handle').removeClass("locked");	
					$('.lock_template').attr("data-lockStatus", "1");	
					$('.design_view_span').show();
					show_lock_info(2, data.current_user_name);
				}
			},
			error: function (e) {
				alert("Internal sever error");											
				}
		});		
		return config_lock_obj;
}
	
function tree_based_locktemplate(page_id)
{
	var template_lock_status = "";
	/////  lock all templates for the user  /////
	$.ajax({					
			url: base_url + admin_folder +"/template_designer/is_template_unlocked_by_current_user",
			type: 'post',
			async:false,
			data: "lockTemplateid="+page_id+"&savexml_method=no", 
			success: function (data) {
				template_lock_status = 	data;				
				if(data == false)
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
					show_lock_info(1, "");					
				}	
				else if(data == 2)
				{
					$(".lock_template").attr("title","Unlock template");				
					$(".lock_template").html("Unlock");
					
					$('.change_lock').removeClass("fa-lock");
					$('.change_lock').addClass("fa-unlock");				
					
					$('#fm-left-panel').removeClass("locked");
					$('.lock_panel').removeClass("locked");	
					$('.ui-sortable-handle').removeClass("locked");	
					$('.lock_template').attr("data-lockStatus", "1");	
					$('.design_view_span').show();
					
				}				
			},
			error: function (e) {
				
				}
		});
	return template_lock_status;
}

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
	$(document).ready(function(){		
	$('.lock_panel').addClass("locked");	
	toastr.options = {
					  "closeButton": false,					  
					  "newestOnTop": true,					  
					  "positionClass": "toast-top-center",
					  			  
					};
	
		window.onload = function () { 
		
		};
		
		$("#fmLeftTabs").tabs();		
		IExp.qParamPageId 				=  "<?php echo @$page_id; ?>";	
		IExp.pageMaster.sectionId 		=  "<?php echo @$page_section_id; ?>";	
 		IExp.pageMaster.pageType 		=  "<?php echo @$page_type; ?>";			
		IExp.qParamScrollTop 			=  "<?php echo @$scroll_top; ?>";	
		IExp.userRole.canDesignPage 	=  "<?php echo FPM_ADDPAGEDESIGN; ?>";	
		IExp.userRole.canAddAdv 		=  "<?php echo FPM_ADDADVSCRIPTS; ?>";	
		IExp.userRole.canConfigWidget 	=  "<?php echo FPM_ADDCONFIG; ?>";	
		IExp.currentUserId				=  "<?php echo USERID; ?>";
		IExp.pageMaster.versionId		=  "<?php echo $current_templ_version_id; ?>";
		
		IExp.pageMaster.query_string_versionId		=  "<?php echo $query_string_version_id; ?>";		
		IExp.templateManager();		
		//////  Verify template is locked or not  //////
		tree_based_locktemplate(IExp.qParamPageId);
	});	

function is_current_user_configuration_locked()
{
	var config_lock_obj = {};
	$.ajax({					
			url: base_url + admin_folder +"/template_designer/is_current_user_configuration_locked",
			type: 'post',
			async:false,
			data: "widgetinstance_id="+widgetinstance_id+"&lockTemplateid="+page_id+"&lock_status="+lock_status+"&return_type=json"+"&savexml_method=no&check_renderingtype="+check_renderingtype,
			success: function (data) {									
				config_lock_obj = data
			},
			error: function (e) {
				alert("Internal sever error");											
				}
		});		
		return config_lock_obj;
}
</script>


<div class="jqmWindow" id="widgetWindow">	
	<div class="modal-header jqDrag" >
		<h4>Widget Mapping</h4>
		<a href="#" class="jqmClose" style="float:right">Close</a>
	</div>
	<div id="widget-ajax-container">
	Please wait... <!--img src="inc/busy.gif" alt="loading" /-->
	</div>
	<div class="action-bar">
	<input type="hidden" id="addwidgethidden" value =""/>
	<input type="button" name="addwidget"  id ="addWidgetBtn" value="Add">	
	</div>
</div>

<div class="jqmWindow" id="widgetConfigWindow">	
	<div class="modal-header jqDrag">
		<h4>Wiget Configuration Mapping</h4>
		<a href="#" class="jqmClose" style="float:right">Close</a>
	</div>
	<div id="content-ajax-container">
		<ul>
		
		</ul>	
	</div>
	<div class="action-bar">
		<input type="hidden" id="addwidgethidden" value =""/>
		<input type="button" name="addwidget"  id ="addWidgetBtn" value="Add">	
	 </div>
</div>
</div>


<!-- popup code -->
<?php 
$frontend_css = image_url."css/admin/";  
$frontend_js  = image_url."js/";  
?>
<link href="<?php echo $frontend_css; ?>font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $frontend_css; ?>dashboard-style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $frontend_css; ?>prabu-styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $frontend_css; ?>bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="<?php echo $frontend_css; ?>bootstrap-datetimepicker.css" />

<link href="<?php echo $frontend_css; ?>bootstrap-colorpalette.css" rel="stylesheet" type="text/css" media="all"  />
<script src="<?php echo $frontend_js; ?>bootstrap-colorpalette.js" type="text/javascript"  charset="utf-8"></script>

<link href="<?php echo $frontend_css; ?>jquery-ui.css" rel="stylesheet">
<script src="<?php echo $frontend_js; ?>jquery.remodal.js"></script>
<script type="text/javascript" src="<?php echo $frontend_js; ?>jquery.dataTables.js"></script>
<link href="<?php echo $frontend_css; ?>jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $frontend_js; ?>w2ui-fields-1.0.min.js"></script>
<script src="<?php echo $frontend_js; ?>jquery.remodal.js"></script>
<script type="text/javascript" src="<?php echo $frontend_js; ?>moment-with-locales.js"></script> 
<script type="text/javascript" src="<?php echo $frontend_js; ?>bootstrap-datetimepicker.min.4.14.30.js"></script>

  
<script type="text/javascript">
	
$(document).ready(function()
{
	$('#collapse_widget_images').attr('checked', false);

	$('#collapse_widget_images').click(function(){
		if($('#collapse_widget_images').is(":checked"))										
		$('.widgetListContainer ul li img').show();											
		else
		$('.widgetListContainer ul li img').hide();													
	});
});

</script>
<script type="text/javascript" language="javascript">
function show_widget_image(modalId, image_path)
{	
	$('.widget_images_popup').attr("data-remodal-id",modalId );
	$('.widget_images_popup img').attr("src",base_url+"images/admin/template_design/images/widget_images/"+image_path+ "-small.jpg" );
}
</script> 
 <div class="remodal widget_images_popup" data-remodal-id="widgetmodal1" style="position:relative;">
       <img src="" />   
 </div>           
 <div class="remodal" id="modal1" data-remodal-id="modal1" data-remodal-options="hashTracking: false" style="position:relative;">
 <script>
 $(document).on('open', '#modal1.remodal', function () {	 
	 var page_sectionid		= IExp.pageMaster.sectionId;	 
	 var section_name = $('#rootstructures').text().split(" ");	 
	 var is_news_letter_sub = (section_name[2] == 'Newsletter-sub') ? true : false;
	 if(is_news_letter_sub){
		$.ajax({
			  url: base_url+"application/views/view_template/"+page_sectionid+".txt", 
			  type: "get",	
			  cache: false,
			  success: function(data){
				document.getElementById("source_content").innerHTML = data;
				document.getElementById("download_newsletter_source").href= base_url+"application/views/view_template/"+page_sectionid+".txt";
			  },
			  error : ""
			});
		
	 }
 });
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}
</script>
<div class="tools_icons">
<span id="copy_clipboard" onclick="copyToClipboard('#source_content');"><i class="fa fa-copy"></i> Copy clipboard</span>  
<span id="download_source"><i class="fa fa-download"></i> <a href="" download id="download_newsletter_source">Download Source</a></span></div>
 <textarea id="source_content" class="copy_source_textarea" readonly="readonly"></textarea>
</div>           
</div>
<style>
.copy_source_textarea{
width: 700px  !important;
min-height: 500px !important;
}
.tools_icons span{
	 cursor: pointer;
    padding: 16px;
}
.have-child-section .fancytree-title{	
	font-weight:bold;
	 color: #3C8DBC !important; 
}
</style>
<script type="text/javascript">
$('#widget_name_autocomplete').on('keyup',function(e){	
	filter_widget_list($(this).val().toLowerCase());	
});

function filter_widget_list(current_object_value){
	var all_elements = $('#widget_list').children();
	var cloned_checkbox_val = $("#show_cloned_widgets").is(":checked");
	var tagElems = (cloned_checkbox_val) ?  $('#widget_list').children("li.cloned-widget") : $('#widget_list').children("li.regular-widget");
	var found_elems = 0;
	if($('#tags').val() != '')
	{			
		$(all_elements).hide();
		for(var i = 0; i < tagElems.length; i++){
			var tag = $(tagElems).eq(i);
			if(($(tag).text().toLowerCase()).indexOf(current_object_value) > -1 ){
				widgetclass_name = $(tag)[0]["className"].split(" ");
				if(cloned_checkbox_val){
					if(widgetclass_name[0] == "cloned-widget"){
						$(tag).show();
					}
				}else{
					if(widgetclass_name[0] == "regular-widget"){
						$(tag).show();
					}
				}
				found_elems ++;
			}
		}

		if(found_elems == 0)
		{					
			$(".widgetListContainer_no_values").html('<ul id="no_values"><li>No records found</li></ul>');
		}
		else
		{
			$("#no_values").remove();
			
		}
	}	
}


///////  Change template xml using template version  ////
function load_version_xml(version_id)
{
	load_version_template(version_id);	
}

var session_flash_data = '<?php echo $this->session->flashdata('alert_message'); ?>';
if(session_flash_data){
show_toastr(session_flash_data, 2);
}

</script>

<!-- Start confirm with three buttons -->
<div class="prompt_box">
<div id="confirm-1" ></div>
</div>
<!-- End confirm with three buttons -->