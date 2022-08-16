<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_designer extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->helper('xml');
		$this->load->library('session');		
		$this->load->library('form_validation'); 				
		$this->load->model('admin/template_design_model');		
		
		$this->load->model('admin/widget_model');		
		$this->load->driver('cache', array('adapter' => 'file'));	
	} 
	
	/* Template designer Index page */
	public function index()
	{
		/* Remove widgetinstanceTemparticles */
		$this->template_design_model->inactivate_temp_articlecustomdetails('',USERID); 		
		$data['page_id']		= ($this->uri->segment(3)=='')? '' : explode("-",$this->uri->segment(4));		
		$total_versions			= 0;
		
		/* Verify whether page is loading by click on back button or click on menu (Front Page Manager -> Template Designer) */
		if($data['page_id'] != '')
		{				
			/* Unlock widget using widgetinstance id (If available) */
			if($data['page_id'][4] != '') /* $data['page_id'][4] - widgetinstance id */
			{
				$this->lock_widget_articles_by_instanceid($data['page_id'][4]);
			}
			$this->session->set_flashdata(array("page_id" => $data['page_id'][0], "scroll_top" => $data['page_id'][1], "page_section_id" => $data['page_id'][2], "page_type" => $data['page_id'][3] ,"alert_message" =>$this->session->flashdata('alert_message'), 'current_templ_version_id' => @$data['page_id'][5], 'query_string_version_id' => @$data['page_id'][5]));
			
			/* Unlock header script if current user locked the header script */
			$this->template_design_model->update_header_script_lock_status(@$data['page_id'][5], '1');			
			redirect(folder_name."/template_designer");
			exit;
		}		
		/* Show Home page Section Template */
		/* Get Home Page Template Id */
		$homepage_details	= $this->template_design_model->get_homePage_xml();
		$template_xml 		= ( count($homepage_details) > 0 && isset($homepage_details['Template_XML']) ) ? @$homepage_details['Template_XML'] : '';
	
		if(count($homepage_details) > 0 && isset($homepage_details['Version_Id']) && @$homepage_details['Version_Id'] !='' ){			
			/* If any not saved template available in Home page section */
			$not_saved_version 	= $this->get_not_saved_version($homepage_details['Page_master_id']);
			$total_versions 	= $not_saved_version['Total_Versions'];
			if($not_saved_version['Version_Id'] != '')
			{
				$not_saved_template_details			= $this->template_design_model->load_template_by_version_id($not_saved_version['Version_Id']); /* Not saved version template */
				$homepage_details['Template_XML']	= $not_saved_template_details['Template_XML'];
				$homepage_details['Version_Id']		= $not_saved_template_details['Version_Id'];
			}			
			
			$data['page_id']			= $homepage_details['Page_master_id']; /// For Home page Section template
			$data['page_section_id']	= $homepage_details['home_section_id'];
			$data['page_type']			= 1;
			$data['current_templ_version_id'] = $homepage_details['Version_Id'];
		}
		else if(count($homepage_details) > 0)
		{
			$data['page_id']			= $homepage_details['home_page_id']; 
			$data['page_section_id']	= $homepage_details['menuid']; 
			$data['page_type']			= $homepage_details['pagetype']; 
			$data['current_templ_version_id'] = NULL;
		}
		else
		{
			$data['page_id']			= NULL; 
			$data['page_section_id']	= NULL; 
			$data['page_type']			= NULL; 
			$data['current_templ_version_id'] = NULL;
		}
		
		/* Unlock header script if current user locked the header script */
		if($data['current_templ_version_id'] != NULL){
			$this->template_design_model->update_header_script_lock_status($data['current_templ_version_id'], '1');
		}
		
		$data['section']			= $this->template_design_model->multiple_section_mapping();			
		$data['page_templates']		= $this->template_design_model->get_pagetemplates('1');
		$data['container']			= $this->template_design_model->get_containers('1');
		$data['template_versions']	= $this->get_template_versions($data['page_id'],'');		
		$data['total_versions']		= $total_versions;
		
		$data['title']				= 'Template Designer';
		$data['template'] 			= 'template_designer';
		$this->load->view('admin_template',$data);
	}
	
	/* Save Template as XML */
	public function save_xml_template()
	{
		$post		= $this->input->post();
		$pageId 	= trim(array_key_exists("pageId",$post)?$post['pageId']:'');		
		$_POST['lockTemplateid']	= $pageId;
		$_POST['savexml_method']	= "yes";  /* This parameter passed to - is_template_unlocked_by_current_user() */
		$design_lock_status = $this->is_template_unlocked_by_current_user("");				
		
		if($design_lock_status == 2) /* Template is unlocked */
		{
			$user_id  	= USERID;			
			$tempStr 	= trim(array_key_exists("tempStr",$post)?$post['tempStr']:'');			
			$templateId = trim(array_key_exists("templateId",$post)?$post['templateId']:'');			
			$publish_xml= trim(array_key_exists("publish_xml",$post)?$post['publish_xml']:'');
			
			$commit_status			= trim(array_key_exists("template_commit_status",$post)?$post['template_commit_status']:'');
			$use_common_header 		= trim(array_key_exists("header",$post)?$post['header']:'');
			$use_common_rightpanel 	= trim(array_key_exists("rightpanel",$post)?$post['rightpanel']:'');
			$use_common_footer 		= trim(array_key_exists("footer",$post)?$post['footer']:'');			
			$version_id 			= trim(array_key_exists("VersionId",$post)?$post['VersionId']:'');
			
			$use_parent_section_template = trim(array_key_exists("use_parent_section_template",$post)?$post['use_parent_section_template']:'');
			
			/* getting section ID from page_master table */
			$page_details 	= $this->template_design_model->getSectionFromPage($pageId);		
			$section_id 	= $page_details['menuid'] ;
			$page_type		= $page_details['pagetype'];			
			$xml			= simplexml_load_string($tempStr);
			$tpl_values 	= $xml->tplcontainer;
			
			$update_value['hasTemplate']	= "1";
			$update_value['templatexml']	= $tempStr;
			$update_value['templateid']		= $templateId;		
			$update_value['header_adscript']= $page_details['Header_Adscript'];
			
			if($publish_xml == 'publish')
			{
				$publish_version_xml 			= $this->template_design_model->template_rendering_by_version_id($version_id);
				$update_value['templatexml'] 	= $publish_version_xml['Template_XML'];
				$update_value['header_adscript']= $publish_version_xml['Header_Adscript'];				
				$use_common_header 				= $publish_version_xml['common_header'];
				$use_common_rightpanel 			= $publish_version_xml['common_rightpanel'];
				$use_common_footer 				= $publish_version_xml['common_footer'];
				
				$get_adv_instances 				= $this->get_all_instances_by_version_id($version_id, ''); 
													/* =1->only advertisement instances, !=1->All widget instances */
				$publishing_widget_instances 	= $get_adv_instances['version_widget_instances']; 
				$publishing_mainConfig			= $this->all_mainSectionConfig_by_version_id($version_id, ''); 
													/* =1->only advertisement instances, !=1->All widget instances */
				$publishing_instance_articles	= $this->all_instance_articles_by_version_id($version_id, ''); 
				
				$update_value['publishing_widget_instances']	= $publishing_widget_instances;
				$update_value['publishing_mainConfig']			= $publishing_mainConfig;
				$update_value['publishing_instance_articles']	= $publishing_instance_articles;
			}
			
			/* Update XML only after making new version of template - means $commit_status = 3 */
			if($commit_status != 4){
			$emsg 	= $this->template_design_model->update_pagemaster($update_value, $pageId, $publish_xml, $user_id, $use_common_header, $use_common_rightpanel, $use_common_footer, $commit_status, $version_id, $section_id, $page_type, $use_parent_section_template);
			
			}
			else if($commit_status == 4)
			{
				/* Make an empty template version */				
				$use_common_header 				= 1;
				$use_common_rightpanel 			= 0;
				$use_common_footer 				= 1;
				$use_parent_section_template	= 0;
				$publish_xml 					= '';
				$update_value['hasTemplate']	= "1";
				$update_value['templatexml']	= '';
				$update_value['templateid']		= 'NULL';		
				$update_value['header_adscript']= '';
				
				
				$emsg = $this->template_design_model->make_empty_template_version($update_value, $pageId, $publish_xml, $user_id, $use_common_header, $use_common_rightpanel, $use_common_footer, $commit_status, $version_id, $section_id, $page_type, $use_parent_section_template);
			}
			
			/* Create an .XML File */
			if($publish_xml == "publish")
			{
				$save_path		= FCPATH."uploads/page_layouts/";
				
				if($section_id!=10000 && $section_id!=10001){
					/* SAVE CONTENT */				
					$section_details        = $this->template_design_model->get_section_by_id($section_id);
					$section_structure      = explode("/", $section_details['URLSectionStructure']);
					
					/* HTML Generation to home page */
					if($page_type== 1 && (strtolower($section_details['Sectionname'])=="home" || $section_details['Sectionname'] =="முகப்பு")){ 
					$this->save_html_template($section_id , $page_type);
					}elseif($page_type == 1 && ($section_details['IsSubSection']==1 && $section_structure[0]=="Newsletter")){
						$this->save_html_template($section_id , $page_type);
					}
				}elseif($section_id==10000){
				    $this->save_html_template($section_id , $page_type);
				     }

				if($page_type=='1'){
				$file_name		= ($section_id != 10000 && $section_id!=10001) ? $section_id."_1.xml" : $section_id."_1.xml"; 
				}else{
				$file_name		= ($section_id != 10000 && $section_id!=10001) ? $section_id."_2.xml" : $section_id."_2.xml";  
				}
			}
			else
			{
			$save_path		= FCPATH."uploads/page_layouts/temp/";
				if($page_type=='1'){
				$file_name		= ($section_id != 10000 && $section_id!=10001) ? $section_id."_1.xml" : $section_id."_1_".$version_id.".xml"; 
				}else{
				$file_name		= ($section_id != 10000 && $section_id!=10001) ? $section_id."_2.xml" : $section_id."_2_".$version_id.".xml";  
				}
			}	
			
			$save_file_name	= $save_path.$file_name;		
			$txt			= $tempStr;
			if ( !write_file($save_file_name, $txt))
			{
					//echo 'Unable to write the file';
			}
			else
			{
					//echo "xml file created";
			}		
			/////////////////////////
			header("content-type: application/json"); 
			echo json_encode($emsg);
			
			/* Delete temporaryInstances */
			if( $commit_status != 2 ){
				$this->delete_temporary_instance();		
			}
		}
		else
		{
			$emsg = array("msg"=>"Current template status is open, actions will not be saved, Please lock template", "msg_type"=>2, "show_msg"=>1, "trigger_tree"=>"1", "res_status"=>"2");				
			header("content-type: application/json"); 
			echo json_encode($emsg);
		}

	}
	
	/* Load Saved XML Template */
	public function load_template()
	{
		$post				= $this->input->post();				
		$pageId 			= trim(array_key_exists("pageId",$post)?$post['pageId']:'');
		$versionId 			= trim(array_key_exists("versionId",$post)?$post['versionId']:'');		
		$total_versions		= 0;
		$not_saved_version 	= $this->get_not_saved_version($pageId);
		$total_versions		= $not_saved_version['Total_Versions'];
		$is_changes_published = 0;
		
		if($versionId == ''){
			if($not_saved_version['Version_Id'] == ''){
				 /* published template */
				$template_details 	= $this->template_design_model->load_published_version_template($pageId);
			}else{
				/* Not saved version template */
				$template_details 	= $this->template_design_model->load_template_by_version_id($not_saved_version['Version_Id']); 
			}
		}else{
			$load_version_id	= ($not_saved_version['Version_Id'] !='' )? $not_saved_version['Version_Id']:$versionId;			
			$template_details 	= $this->template_design_model->load_template_by_version_id($load_version_id); /* default */
		}
		
		if(count($template_details) > 0 && $template_details['Template_XML'] != ''){
			$template_xml 					= $template_details['Template_XML'];
			$is_template_closed_before_save = ($template_details['Not_Saved_user_id'] !='') ? '1' : '2';
			$template_lock_status 			= $template_details['locked_status'];
			$template_locked_user 			= $template_details['locked_user_id'];
			$is_changes_published 			= $template_details['is_changes_are_published'];
			$locked_user_name	  			= '';
			if($template_locked_user != ''){
			$locked_user		  = $this->template_design_model->get_userdetails_by_id(@$template_locked_user);				
			$locked_user_name	  = ($locked_user['Firstname'] != '' || $locked_user['Lastname'] != '') ? $locked_user['Firstname']." ".$locked_user['Lastname'] : $locked_user['Username'];
			}
			$template_locked_user_name 		= $locked_user_name;
		}
		elseif(count($template_details) > 0 && $template_details['Template_XML'] == '')
		{			
			$template_xml 					= $template_details['Template_XML'];
			$is_template_closed_before_save = '2';
			$template_lock_status 			= $template_details['locked_status'];
			$template_locked_user 			= $template_details['locked_user_id'];			
			$is_changes_published 			= 0;
			$locked_user_name	  			= '';
			if($template_locked_user != ''){
				$locked_user		  		= $this->template_design_model->get_userdetails_by_id(@$template_locked_user);				
				$locked_user_name	  		= ($locked_user['Firstname'] != '' || $locked_user['Lastname'] != '') ? $locked_user['Firstname']." ".$locked_user['Lastname'] : $locked_user['Username'];
			}
			$template_locked_user_name 		= $locked_user_name;			
		}		
		else
		{
			$template_xml 					= '';
			$is_template_closed_before_save = '2';
			$template_lock_status 			= '1';
			$template_locked_user 			= '';
			$template_locked_user_name 		= '';
			$is_changes_published 			= 0;
		}
		$result = array("template_xml" => $template_xml, "is_template_closed_before_save" => $is_template_closed_before_save, "template_lock_status" => $template_lock_status, "template_locked_user" => $template_locked_user, "template_locked_user_name" => $template_locked_user_name, "total_versions" => $total_versions, "is_changes_published" => $is_changes_published); 
		/* is_template_closed_before_save- 1->Yes, 2->No */
		header("content-type: application/json"); 
		echo json_encode($result);
	}
	
	/* Show available widgets */
	public function show_allwidgets()
	{		
		$return_type		= $this->input->post('return_type');
		$all_active_widgets	= $this->template_design_model->get_allwidgets('1');	
		if($return_type == 'php'){
			return $all_active_widgets;
		}else{
			header("content-type: application/json");
			echo json_encode($all_active_widgets);
		}
		
	}
	
	/* Show widget cofiguration */
	public function show_widgetconfig()
	{
		$request 						= $this->input->get();		
		$data['widgetId'] 				= trim(array_key_exists("data-widgetid",$request)?$request['data-widgetid']:'');				
		$data['widgetName'] 			= trim(array_key_exists("data-widgetname",$request)?$request['data-widgetname']:'');	
		$data['widgetTitleEditatble']	= trim(array_key_exists("data-iswidgettitleconfigurable",$request)?$request['data-iswidgettitleconfigurable']:'');		
		$data['renderingType'] 			= trim(array_key_exists("data-renderingtype",$request)?$request['data-renderingtype']:'');
		$data['separatorLine'] 			= trim(array_key_exists("data-isseparatorlineavailable",$request)?$request['data-isseparatorlineavailable']:'');		
		$data['widgetStyle'] 			= trim(array_key_exists("data-widgetstyle",$request)?$request['data-widgetstyle']:'');
		$data['clonedwidget_instance']  = trim(array_key_exists("data-clonedinstanceid",$request)?$request['data-clonedinstanceid']:'');
		
		@$data['widget_instance_id']	= ($data['clonedwidget_instance']) ? $data['clonedwidget_instance'] : (trim(array_key_exists("data-widgetinstanceid",$request)?$request['data-widgetinstanceid']:''));		
		@$data['isSummaryAvailable']	= trim(array_key_exists("data-issummaryavailable",$request)?$request['data-issummaryavailable']:'n');		
		@$data['widget_content_type']	= trim(array_key_exists("data-contenttype",$request)?$request['data-contenttype']:'');		
		$data['section_group']			= $this->get_section_group();			
				
		if($data['renderingType'] == 3){
			/* Old code - before introducing temporary instance config */
			$data['widget_instancemainsection']	= $this->template_design_model->get_widget_mainsection_config('', $data['widget_instance_id']);	
		}
		else
		{
			/* Do the changes in temporarily */
			$data['widget_instancemainsection']	= $this->template_design_model->get_temporary_widget_mainsection_config('', $data['widget_instance_id']);	
		}
		$instance_subsection_details = array();
		foreach($data['widget_instancemainsection'] as $wmain_key => $wmain_config)
		{
			if($wmain_config['status'] == 1 )
			{			
				$instance_subsection_details[]	= $this->template_design_model->get_widget_subsection_config('',$wmain_config['WidgetInstanceMainSection_id']);				
			}
		}
		$content_type_group					= $this->common_model->get_content_type("");
		$data['content_type_group']			= $content_type_group;
		$data['widget_instancesubsection'] 	= $instance_subsection_details;
		$data['config_version_id']			= $this->input->get('configVersionId'); //configVersionId
		
		$this->load->view("widget_config",$data);
	}
	
	/* Create dynamic Teamplates (Page Templates) */
	public function dynamic_templates()
	{
		$return_type		= $this->input->post('return_type');
		$template_details 	= $this->template_design_model->get_pagetemplates('1');				
		
		$individual_template_values = array_column( $template_details, "template_values" );
		$individual_template_ids	= array_column( $template_details, "templateid" );
		$template_id_values			= array_combine($individual_template_ids, $individual_template_values);
		
				$j=1;			
				$body_span_class_values["layout_details"] = $template_details;				
				$total_layouts = $body_span_class_values['layout_details'];
				
                for($i=0; $i<count($body_span_class_values['layout_details']); $i++)
                {
					$template_id = $total_layouts[$i]['templateid'];
					$tmpl_values = explode("-",$total_layouts[$i]['template_values']);
					
					 /* Header */
					 $templateString[$template_id] =  '<div class="section group"> 
							<div name="template-wrapper-top" id="tc-'.$j.'" data-tcid="'.$j.'"  class="col span_'. $tmpl_values[0] .'_of_'. $tmpl_values[0] .' template-wrapper-top tpl-col-container"></div> 
						</div>'; 
						$j++;
					
					/* Body */
					$templateString[$template_id] .= '<div class="section group"> ';
					$arr = explode(",", $tmpl_values[1]);
					$total_space = array_sum($arr);
					
					foreach($arr as $span_values)
					{
						if($span_values != '9'){ $append_class = ""; }else{ $append_class = "common-template"; }
						
						$templateString[$template_id] .= '<div name="template-wrapper-left" id="tc-'.$j.'" data-tcid="'.$j.'" class="col span_'.$span_values.'_of_'.$total_space.' template-wrapper-left tpl-col-container '.$append_class.'"></div>'; 
						$j++;
					}
					$templateString[$template_id] .= '</div> ';
					
					/* Footer */
					$templateString[$template_id] .= '<div class="section group">	
							<div id="tc-'.$j.'" data-tcid="'.$j.'" name="template-wrapper-footer" class="col span_'. $tmpl_values[2] .'_of_'. $tmpl_values[2] .' template-wrapper-footer tpl-col-container"></div> 
						</div>';		
                    $j++;					
                }
				
				$return_result = array("template_id_values" => $template_id_values, "templateString" => $templateString);
				if($return_type == 'php'){
					return $return_result;
				}else{
					header("content-type: application/json");
					$templateString_json = $return_result;
					echo json_encode($templateString_json); exit;
				}
				
	}
	
	/* Create dynamic Containers */
	public function dynamic_containers()
	{
		$return_type	= $this->input->post('return_type');
		$addWidget 		= $this->input->post('addWidget');
		$removeWidget 	= $this->input->post('removeWidget');
		$configWidget 	= $this->input->post('configWidget');
		$id			 	= $this->input->post('id');		
		$container_list = $this->template_design_model->get_containers('1');				
		
		$individual_container_values = array_column( $container_list, "container_values" );
		$individual_container_ids	= array_column( $container_list, "containerid" );
		$container_id_values		= array_combine($individual_container_ids, $individual_container_values);
		
		$i = 1;
		foreach($container_list as $key => $container_details)
		{		
			$content_id = $container_details['containerid'];
			$id 		= "#containerId#";
			$type 		= "#containerTypeId#";
			$container_content[$content_id] = '<div class="container-wrapper section group" data-type="'. $type .'" id="container-'. $id .'">' .			
				'<div class="section group">'.((FPM_ADDPAGEDESIGN) ? '<div class="handle"><span class="container-handle-id">container-'. $id .'</span><span class=" close-container fa fa-times fa-times-circle" title="Remove Container"></span></div>' : '<div class="handle-disabled"></div>').
				'</div>' .				
				
				/* Start Body content */
				'<div class="section group widget-drag">'; 
				
				$values	= explode(",",$container_details['container_values']);
				$nos  	= count($values);
				$of_class_value 	= 12;
				for($j=1; $j<=$nos; $j++)
				{
					$span_class_value 	= $values[$j-1];															
					/* previous code having modal - fancy box */					
					$container_content[$content_id] .= '<div class="col span_'. $span_class_value .'_of_'. $of_class_value .' wc">' .
								'<div class="add-widget">'.	((FPM_ADDCONFIG || FPM_ADDADVSCRIPTS) ? '<a href="javascript:;" class="config-widget-button fa fa-cog" data-target-container="' . $id .'-'. $j . '" title="'.$configWidget.'"></a>' : "").((FPM_ADDARTICLEOPTION) ? '<a href="javascript:;" class="add-widget-button fa fa-plus" id="' . $id .'-'. $j . '" data-target-container="' . $id .'-1' . '"  title="'.$addWidget.'" ></a>' : '').((FPM_ADDPAGEDESIGN) ? ' <a href="javascript:;" class="view-child-clone-button fa fa-eye" title="view child clones" id="' . $id .'-'. $j . '" data-target-container="' . $id .'-'. $j . '" style="display:none" ></a><a href="javascript:;" class="remove-widget-button fa fa-trash-o" data-target-container="' . $id .'-'. $j . '" title="'.$removeWidget.'"></a>' : "" ).
								'</div>' .
								'<div class="widget-container widget-container-' . $id .'-'. $j . '" data-widgetcontainerorderid = "'. $j .'" >' .						
								'</div>' .		
							'</div>' ;											
				}
				
				/* End of body */
				$container_content[$content_id] .= '</div>' .				
										'<div class="clear-fix"></div>' .
									'</div>';
				$i++;
		}
			$return_result = array("container_id_values" => $container_id_values, "container_content" => $container_content);
			if($return_type == 'php'){
					return $return_result;
			}else{
				header("content-type: application/json");
				$templateString_json = $return_result;
				echo json_encode($templateString_json);
			}
			
	}
		
	public function get_section_group()
	{
		$section = $this->template_design_model->multiple_section_mapping();		
		foreach($section as $key => $value)
		{	
			$childCategories = "";
			$special_section = "";
			if($value['section_allowed_for_hosting'] != 0)
			{			
				/* Sub section */
				if(@$value['sub_section']!='')
				foreach(@$value['sub_section'] as $skey => $svalue)
				{
					if($svalue['section_allowed_for_hosting'] != 0)
					{					
						$childCategories[] = array('categoryId' => $svalue['Section_id'], 'categoryName' => $svalue['Sectionname'], "Section_landing" 	=> $svalue['Section_landing'], "special_section_count"=>count(@$svalue['special_section']), "section_allowed_for_hosting" => $svalue['section_allowed_for_hosting'] );	
						
						foreach(@$svalue['special_section'] as $splkey => $spl_value)
						{	
							if($spl_value['section_allowed_for_hosting'] != 0)
							{								
								$special_section[$svalue['Section_id']][] = array('categoryId' => $spl_value['Section_id'], 'categoryName' => $spl_value['Sectionname'], "section_allowed_for_hosting" => $spl_value['section_allowed_for_hosting']);
							}
							else
							{
								$special_section = '';
							}
						}
					}
					else
					{
						$childCategories = '';
					}
				}	
				$data['categoryList'][] = array(
												"categoryId" 		=> $value['Section_id'],
												"categoryName" 		=> $value['Sectionname'],
												"childCategories" 	=> $childCategories,
												"special_section"	=> $special_section,
												"Section_landing" 	=> $value['Section_landing'], 
												"section_allowed_for_hosting" => $value['section_allowed_for_hosting']
											 );
			}
		}
		return $data;
	}
	
	public function section_group_json()
	{		
		$return_type	= $this->input->post('return_type');
		$data 			= $this->get_section_group();
		if($return_type == 'php'){
			return $data;
		}else{
			header("content-type: application/json");
			echo json_encode($data);
		}
	}
		
	public function get_template_fromdetails()
	{			
		$page_id 			= $this->input->get('pageId');
		$version_id 		= $this->input->get('versionId');
		$template_details 	= $this->template_design_model->get_pagemasterdetails_using_pageid($page_id);	
		
		if($version_id == ''){
			$template_version_details 	= $this->template_design_model->load_published_version_template($page_id); /* template version details */
		}
		else
		{
			$template_version_details 	= $this->template_design_model->get_template_versions('', $version_id); /* version based tempalte details */
			$template_version_details 	= @$template_version_details[0];	
		}
		
		/* Check whether the template is unlocked */
		$_POST['savexml_method']	= "yes";  /* This parameter passed to - is_template_unlocked_by_current_user() */
		$_POST['lockTemplateid']	= $page_id;
		$design_lock_status = $this->is_template_unlocked_by_current_user("");		
		if($design_lock_status == 2) /* Template is unlocked */
		{
			$common_header_value		= (@$template_version_details['Not_Saved_user_id'] != '') ? @$template_version_details['Not_Saved_common_header'] : @$template_version_details['common_header'];
		
			$common_rightpanel_value	= (@$template_version_details['Not_Saved_user_id'] != '') ? @$template_version_details['Not_Saved_common_rightpanel'] : @$template_version_details['common_rightpanel'];
			
			$common_footer_value		= (@$template_version_details['Not_Saved_user_id'] != '') ? @$template_version_details['Not_Saved_common_footer'] : @$template_version_details['common_footer'];
			
			$use_parent_section_template_value	= (@$template_version_details['Not_Saved_user_id'] != '') ? @$template_version_details['Not_Saved_use_parent_section_template'] : @$template_version_details['use_parent_section_template'];
		}
		else
		{
			$common_header_value		=  @$template_version_details['common_header'];		
			$common_rightpanel_value	=  @$template_version_details['common_rightpanel'];		
			$common_footer_value		=  @$template_version_details['common_footer'];			
			$use_parent_section_template_value		=  @$template_version_details['use_parent_section_template'];
		}
		$common_header_checkbox 	= (@$common_header_value == '1') ? 'checked="checked"' : ""; 
		$common_rightpanel_checkbox = (@$common_rightpanel_value == '1') ? 'checked="checked"' : ""; 
		$common_footer_checkbox 	= (@$common_footer_value == '1') ? 'checked="checked"' : ""; 		
		$use_parent_section_template_checkbox 	= (@$use_parent_section_template_value == '1') ? 'checked="checked"' : ""; 
		if($template_details[0]['pagetype']=='1' && ($template_details[0]['menuid']!=10000 && $template_details[0]['menuid']!=10001 )){
			$section_details = $this->template_design_model->get_section_by_id($template_details[0]['menuid']);		
			if($section_details['ParentSectionID'] != ''){
				$checkbox_use_parent_section_show = "";
			}else{
				$checkbox_use_parent_section_show = " hidden ";
			}
			$templateString = '<span class="FloatLeft padding-top-10"><input type="checkbox" id="use_common_header" '.$common_header_checkbox.' value="'.@$template_version_details['common_header'].'" class="use_common_header common_options_checkbox"/><label>Common Header</label> &nbsp; &nbsp; <input type="checkbox" id="use_common_rightpanel" '.$common_rightpanel_checkbox.' value="'.@$template_version_details['common_rightpanel'].'" class="use_common_rightpanel common_options_checkbox"/><label>Common Right Panel</label> &nbsp; &nbsp; <input type="checkbox" id="use_common_footer" '.$common_footer_checkbox.' value="'.@$template_version_details['common_footer'].'" class="use_common_footer common_options_checkbox"/><label>Common Footer</label> &nbsp; &nbsp; <input type="checkbox" id="use_parent_section_template" '.$use_parent_section_template_checkbox.' value="'.@$template_version_details['use_parent_section_template'].'" class="use_parent_section_template common_options_checkbox" '.$checkbox_use_parent_section_show.' /><label '.$checkbox_use_parent_section_show.'>Use Parent Section Template</label> </span>'; 
		}
		elseif($template_details[0]['pagetype']=='2' && $template_details[0]['menuid']!=10000 && $template_details[0]['menuid']!=10001){
		    $templateString = '<span class="FloatLeft padding-top-10" ><input type="checkbox" id="use_common_header" '.$common_header_checkbox.' value="'.@$template_version_details['common_header'].'" class="use_common_header common_options_checkbox"/><label>Common Article Page</label> </span>'; 
		}
		else{
		$templateString = '';
		}
		
		echo $templateString; exit;
	}
	
	public function fill_widget($widget_location ,$content)
	{		
		$string                     = '';
		$domain_name 				= base_url();
		$widget_section_url         = '';
		$widget_section_id          = '';
		$widget_sectionname_link    ='';
		$special_parent_section     = array();
		$clone_instance_id			= "";
		$show_widget				= true;
		$pass_widget_instance		= (isset($content['widget_values']['data-clonedinstanceid']) && $content['widget_values']['data-clonedinstanceid'] != "") ? $clone_instance_id = $content['widget_values']['data-widgetinstanceid'] = $content['widget_values']['data-clonedinstanceid'] : $content['widget_values']['data-widgetinstanceid'];
		if($content['mode']=="live"){
			$widget_instance_details    = $this->widget_model->getWidgetInstance('', '','', '', $pass_widget_instance, $content['mode']); /*live db */
		}else{
			$widget_instance_details    = $this->template_design_model->getWidgetInstance('', '','', '', $pass_widget_instance, $content['mode'],''); 		
		}
		if(count($widget_instance_details)>0){
				if($clone_instance_id != ''){
					if($content['widget_values']['data-widgetstyle'] == 2){
						$widget_instancemainsection	= $this->template_design_model->get_widget_mainsection_config('', $clone_instance_id);	
						if(count($widget_instancemainsection)>0){
								foreach($widget_instancemainsection as $key => $tab_values){
									$widgettab_child = $content['widget_values']->addChild('widgettab');
									$widgettab_child->addAttribute('cdata-categoryId', $tab_values['Section_ID']);
									$widgettab_child->addAttribute('cdata-categoryName', $tab_values['CustomTitle']);
									$widgettab_child->addAttribute('cdata-customTitle', $tab_values['CustomTitle']);
									$widgettab_child->addAttribute('cdata-categoryType', $tab_values['Section_Content_Type']);
									$widgettab_child->addAttribute('cdata-categoryTypeName', $tab_values['Section_Content_Type']);
							}
						}
					}
					 $content['widget_values']['cdata-showSummary'] = $widget_instance_details['isSummaryRequired'];
					 if($widget_instance_details['status'] == 1 ){
						  if( ( $widget_instance_details['publish_start_date']=='0000-00-00 00:00:00' || strtotime($widget_instance_details['publish_start_date'])=='' || ((strtotime('now') > strtotime($widget_instance_details['publish_start_date'])) )) && (((strtotime($widget_instance_details['publish_end_date'])=='' || $widget_instance_details['publish_end_date'] =='0000-00-00 00:00:00' || (strtotime('now') < strtotime($widget_instance_details['publish_end_date'])))))){
							$show_widget = true;							
						}else{
							$show_widget = false;
						} 
					 }else{
						$show_widget = false;
					} 
				 }
		if($show_widget){
		if($widget_instance_details['WidgetSection_ID'] != '' && $widget_instance_details['WidgetSection_ID'] != "0")
		{
			$widget_section_details = $this->template_design_model->get_section_by_id($widget_instance_details['WidgetSection_ID']);
			$widget_custom_title    = ($widget_instance_details['CustomTitle']!= '') ? $widget_instance_details['CustomTitle'] : $widget_section_details['Sectionname'];
			if($widget_instance_details['CustomTitle']!= '' && $widget_instance_details['WidgetSection_ID']=='0')
			{
				$widget_sectionname_link = 0;
				$widget_section_id       = '';
				$widget_section_url      = '';
			}
			else
			{
				$widget_sectionname_link = 1;
				$widget_section_id       = $widget_instance_details['WidgetSection_ID'];
				$widget_section_url      = $domain_name.$widget_section_details['URLSectionStructure'];
			}
		}
		else
		{
			$widget_custom_title = ($widget_instance_details['CustomTitle']!= '') ? $widget_instance_details['CustomTitle'] : "";
		}		
		$content_type_names			   = array("None"=>1,"Article" => 2,"Gallery" => 3,"Video" => 4,"Audio" => 5);
		$content_type_name			   = array_search($content['widget_values']['data-contenttype'], $content_type_names);		
		$content_type_id			   = $this->widget_model->get_content_type_byname($content_type_name, $content['mode']);			
		$content['content_type_id']    = (count($content_type_id) > 0) ? $content_type_id['contenttype_id'] : '' ;
		$content['widget_title'] 	   = $widget_custom_title;		
		$content['sectionID']          = $widget_section_id;
		$content['widget_title'] 	   = $widget_custom_title;
		$content['widget_title_link']  = $widget_sectionname_link;		
		$content['widget_bg_color']    = "style='background-color:".$widget_instance_details['Background_Color']. ";'";		
		$content['show_max_article']   = $widget_instance_details['Maximum_Articles'];
		$content['RenderingMode'] 	   = $widget_instance_details['RenderingMode'];				
		$mode                          = $content['mode'];		
		$widget_custom_title           = $content['widget_title'];				
		$content['widget_section_url'] = $widget_section_url;		
		$data['content']               = $content;		
		if($widget_location == "admin/widgets/article_details")
		{	
			$file_name                         = FCPATH."/application/views/admin/widgets/article_details_preview.php";
			$widget_location                   = "admin/widgets/article_details_preview";
		}else{
			$file_name                         = FCPATH.'/application/views/'.$widget_location.".php";
		}
		
		if (file_exists($file_name)) {
			$string = $this->load->view($widget_location, $data, true);			
		} else {
			$string = '<div class="row">The file '.$file_name.' does not exist</div>';
		}
	   }
	  }
		return $string;		
	}
	
	public function get_parent_article_page($parent_section_id, $page_type)
	{
		$page_details 		= $this->template_design_model->getPageDetails($parent_section_id, $page_type);		
		$xml				= simplexml_load_string($page_details['templatexml']);	
		if(strlen($xml) == 0 && $page_details['menuid'] != 10000)
		{	
			$section_details = $this->template_design_model->get_section_by_id($page_details['menuid']);
			if($section_details['IsSubSection'] == 'Y'){								
				$xml = $this->get_parent_article_page($section_details['ParentSectionID'], 2);
			}else{	
				/* Is not a Sub section */
				$page_details 		= $this->template_design_model->getPageDetails($section_details['Section_id'], $page_type);								
				$xml				= simplexml_load_string($page_details['templatexml']);
				if(strlen($xml) == 0){					
					/* Standard Article page xml content */
					return $this->get_parent_article_page(10000, "2");					
				}else{					
					return $page_details;
				}
			}			
		}else{
			return $page_details; 
		}
	}
	public function save_html_template($menuid , $pagetype)
	{
		$this->clear_cache('home');
		$this->config->set_item('base_url', BASEURL) ;
		$page_details 	= $this->widget_model->getPageDetails(@$menuid, $pagetype); 		
		$section_page_id = $menuid;
		$static_view = (@$query_string[1] =='static') ? 'static' : '';
		$is_home_page = 'y';
		if($menuid!=10000){
		$section_details = $this->widget_model->get_section_by_id($page_details['menuid']); //live db

		$is_home_page = (strtolower(@$section_details['Sectionname']) == 'home' || @$section_details['Sectionname'] =="முகப்பு") ? 'y' : 'n';
		}
		
		if($is_home_page=='y'){
		$page_param = 'home';
		}else{
		$page_param = $section_details['Section_id'];
		}
        $xml                = '';
		$xml				= simplexml_load_string($page_details['published_templatexml']);   // load from db
		$tmpl_values        = (count($xml)> 0)? (string)$xml->attributes()->templatevalues: "";
		if($tmpl_values!="")
		{
		$tmpl_values 		= explode("-", $tmpl_values);	
		}else{
		$template_id 	    = $page_details['templateid'];
		$template_details 	= $this->widget_model->getTemplateDetails($template_id); 
		$tmpl_values 		= explode("-", $template_details['template_values']);		
		}
	
		/*if(strlen($xml) == 0)
		{
			if($section_details['IsSubSection'] == '1')
			{				
				$page_details = $this->get_parent_article_page($section_details['ParentSectionID'], "2");	

				$template_id 		= $page_details['templateid'];
				$template_details 	= $this->widget_model->getTemplateDetails($template_id);  // live db
				//print_r($page_details); exit;
				$tmpl_values 		= explode("-", $template_details['template_values']);		
				$xml				= simplexml_load_string($page_details['published_templatexml']);
				
			}
			else
			{
				$page_details = $this->get_parent_article_page(10000, "2");
				$template_id 		= $page_details['templateid'];
				$template_details 	= $this->widget_model->getTemplateDetails($template_id);		// live db				
				$tmpl_values 		= explode("-", $template_details['template_values']);		
				$xml				= simplexml_load_string($page_details['published_templatexml']);	
				//print_r($page_details); exit;
			}
		}*/
		
		$viewmode		= "live";	
		$data['viewmode'] = $viewmode; 
		
		if($xml != '' && !empty($xml))
		{
			if($section_page_id!=10000){
				$this->load->library("template_library"); 
			$tplheader_values 	= $xml->tplcontainer;
			
			/*$data['header'] 	= $this->load_saved_template_data($tplheader_values, 'top', "header", $tmpl_values, '', $is_home_page, $static_view, $viewmode, '', $pagetype, $page_param, "live", $page_details);
			$data['body'] 		= $this->load_saved_template_data($tplheader_values, 'left', "template_body", $tmpl_values, '', $is_home_page, $static_view, $viewmode, '', $pagetype, $page_param,  "live", $page_details);
			$data['footer'] 	= $this->load_saved_template_data($tplheader_values, 'footer', "footer", $tmpl_values, '', $is_home_page, $static_view, $viewmode, '', $pagetype, $page_param,  "live", $page_details );*/
		    //new copy by venkatesh
			
			
			$header_param 		= $tplheader_values[0];
			$left_panel_param	= $tplheader_values[count($tplheader_values)-3];
			$right_panel_param	= $tplheader_values[count($tplheader_values)-2];
			$footer_param 		= $tplheader_values[count($tplheader_values)-1];
			$body_loop_values	= $tplheader_values[0];
			
			if($page_details['common_header']==1 || $page_details['common_footer']==1 || $page_details['common_rightpanel']==1)
			{
				$common_xml         = $this->template_library->get_parent_article_page(10000, $pagetype);
				$xml                = simplexml_load_string($common_xml['published_templatexml']);
				if(count($xml)> 1){
					$common_tplheader_values 	= $xml->tplcontainer; 
					if($page_details['common_header']==1){
					$header_param 	= $common_tplheader_values[0];
					}
					if($page_details['common_rightpanel']==1){
					$right_panel_param 	= $common_tplheader_values[count($common_tplheader_values)-2];				
					}
					if($page_details['common_footer']==1){
					$footer_param 	= $common_tplheader_values[count($common_tplheader_values)-1];
					}
				}
			}
			
			$data['header']   = $this->template_library->section_xml_containers($header_param, "header", $is_home_page, $viewmode, $pagetype, $page_param);
			
            $is_common_right   = $page_details['common_rightpanel'];
			$data['body']	  = '<section class="section-content"><div class="container SectionContainer"><div class="row">';
			$template_values_body_content = explode(",",$tmpl_values[1]);
			$b_section_inc = 0;
			$loop_break_point = count($template_values_body_content);
			for($i=1; $i<= $loop_break_point; $i++){
			
				$body_section 	= $template_values_body_content;
				$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
				
				$col_sm_val		= "12";
				$col_xs_val		= "12";
				$home_last_column = "";
				if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
				{
					if(($section_cl_val == 3 || $section_cl_val == 6 ) && array_sum($body_section) == 4){
						$home_last_column = "";
					}
					else{
						$home_last_column = "ColumnSpaceRight";
					}
				}
				
				//////  For only three column template  ////
				if(count($body_section) == 3)
				{
						if($b_section_inc == 0)
						{
							$col_sm_val		= "3";
						}
						if($b_section_inc == 1)
						{
							$col_sm_val		= "9";
						}
				}
				$c_class_value 	= " col-lg-".$section_cl_val." col-md-".$section_cl_val." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ".$home_last_column." ";
				$data['body'] .= '<div class="'. $c_class_value .'">';
				$pass_body_content = (($i) < count($template_values_body_content)) ? $tplheader_values[$i] : $right_panel_param;			
				//$pass_body_content = $tplheader_values[$i];
				$data['body'] 	  .= $this->template_library->section_xml_containers($pass_body_content, "template_body", $is_home_page,  $viewmode, $pagetype, $page_param);
				$data['body']	  .= '</div>';
				$b_section_inc ++;
			}
			$data['body']	  .= '</div></div></section>';

			$data['footer']   = $this->template_library->section_xml_containers($footer_param, "footer", $is_home_page, $viewmode, $pagetype, $page_param);
			
			$data['header_ad_script']	= $page_details['Header_Adscript'];
			
			$page_type= $page_details['pagetype'];
			
			$data['page_type']	= $page_type;

			$data['section_details']	= $this->widget_model->get_sectionDetails($section_page_id, $viewmode);
			
		   $viewpage = $this->load->view("admin/view_frontend", $data, true);
		    if((strtolower(@$section_details['Sectionname'])=="home") || (@$section_details['Sectionname'] =="முகப்பு")){        
				$file_name = "home.php"; 
			 }else{  
				$file_name = $section_details['Section_id'].".txt";
			 }
			$file = FCPATH.'application/views/view_template'.'/'.$file_name;
			// Write the contents back to the file
			$folder = FCPATH.'application/views/view_template';
			if (!is_dir($folder)) {
			mkdir($folder, 0777, TRUE);
	      	}
			file_put_contents($file, $viewpage );
           $this->curl_post($file_name, $viewpage);
		}else if($section_page_id==10000){
			$this->load->library("template_library"); 
           	$tplheader_values 	= $xml->tplcontainer;
			$right_panel_xml    = $tplheader_values[count($tplheader_values)-2];
			if($pagetype==1){
			$header_param 		= $tplheader_values[0];
			$header 	= $this->template_library->section_xml_containers($header_param, "header", $is_home_page, $viewmode, $pagetype, $page_param);
			}else{
			$header_param 		= $tplheader_values[0];
			$header 	= $this->template_library->article_xml_containers($header_param, "header", '', $is_home_page, $viewmode, '', $pagetype, $page_param, "live", '','');
			}
			if ($header!=''){
			$file_name = ($pagetype==2)? 'article_common_header.php': 'common_header.php';
			$viewpage = $this->load->view("common_template/header", $data, true);
			$file = FCPATH.'application/views/view_template'.'/'.$file_name;
			// Write the contents back to the file
			$folder = FCPATH.'application/views/view_template';
			if (!is_dir($folder)) {
			mkdir($folder, 0777, TRUE);
	      	}
			file_put_contents($file, $viewpage );
			$this->curl_post($file_name, $viewpage);
			}
			
			if($pagetype==1){
			$right_panel_param	= $tplheader_values[count($tplheader_values)-2];
			$body 	= $this->template_library->section_xml_containers($right_panel_param, "template_body", $is_home_page, $viewmode, $pagetype, $page_param);
			}else{
			$right_panel_param	= $tplheader_values[count($tplheader_values)-2];
			$body 	= $this->template_library->article_xml_containers($right_panel_param, "template_body", '', $is_home_page, $viewmode, '', $pagetype, $page_param, "live", '','');
			}
			if ($body!=''){
				$file_name = ($pagetype==2)? 'article_common_rightpanel.php' :'common_rightpanel.php';
			$viewpage = $this->load->view("common_template/template_body", $data, true);
			$file = FCPATH.'application/views/view_template'.'/'.$file_name;
			// Write the contents back to the file
			$folder = FCPATH.'application/views/view_template';
			if (!is_dir($folder)) {
			mkdir($folder, 0777, TRUE);
	      	}
			file_put_contents($file, $viewpage );
			$this->curl_post($file_name, $viewpage);
			}
			
			if($pagetype==1){
			$footer_param 		= $tplheader_values[count($tplheader_values)-1];
			$footer 	= $this->template_library->section_xml_containers($footer_param, "footer", $is_home_page, $viewmode, $pagetype, $page_param);
			}else{
			$footer_param 		= $tplheader_values[count($tplheader_values)-1];
			$footer 	= $this->template_library->article_xml_containers($footer_param, "footer", '', $is_home_page, $viewmode, '', $pagetype, $page_param, "live", '','');
			}
			//$footer 	= $this->load_saved_template_data($tplheader_values, 'footer', "footer", $tmpl_values, '', $is_home_page, $static_view, $viewmode, '', $pagetype, $page_param,  "live", $page_details );
		    if ($footer!=''){
			$file_name = ($pagetype==2)? 'article_common_footer.php':'common_footer.php';
			$viewpage = $this->load->view("common_template/footer", $data, true);
			$file = FCPATH.'application/views/view_template'.'/'.$file_name;
			// Write the contents back to the file
			$folder = FCPATH.'application/views/view_template';
			if (!is_dir($folder)) {
			mkdir($folder, 0777, TRUE);
	      	}
			file_put_contents($file, $viewpage );
			$this->curl_post($file_name, $viewpage);
			}
			return true;
			}
		 }
		 
		 }
		 
	public function curl_post($file_name, $file_data)
	{
		$post_data = array('file_name' => $file_name,'file_contents'=> $file_data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, BASEURL.'user/commonwidget/post_file_intimation');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result=curl_exec ($ch);
		curl_close ($ch);
	}
	
	public function load_saved_template()
	{				
		$this->load->model('admin/comment_model');
		$i = 1;	
		$image_number = '';
		if($this->uri->segment(1) == folder_name) //  From Admin Preview
		{
				$page_details 	= array();
				$content_id		= "";
				
				if($this->uri->segment(4) != '')
				{
					$query_string		= explode("-",$this->uri->segment(4));
					$page_id			= $query_string[0];
					$version_id			= $this->uri->segment(5); 
					$page_details 		= $this->template_design_model->get_template_xmlcontent($page_id, '');
					if($version_id == ''){
						//  Do nothing  //
					}
					else
					{
						$version_xml = $this->template_design_model->template_rendering_by_version_id($version_id);
						$page_details['templatexml'] = $version_xml['Template_XML'];
						$page_details['use_parent']  = $version_xml['use_parent_section_template'];
						$page_details['common_header'] = $version_xml['common_header'];
						$page_details['common_rightpanel'] = $version_xml['common_rightpanel'];
						$page_details['common_footer'] = $version_xml['common_footer'];
						$page_details['Version_Status'] = $version_xml['Version_Status'];
						$page_details['Header_Adscript'] = $version_xml['Header_Adscript'];
						$page_type  = $page_details['pagetype'];
						
					}

				}
				else
				{
                    redirect(folder_name."/template_designer/");     

				}
				$viewmode			= "adminview";
				
		}
		 //  by venkat
		if($page_details['Version_Status']==2){
		echo "The Version your trying to view may be deleted";exit;
		}
		
		$static_view = (@$query_string[1] =='static') ? 'static' : '';
		
		$section_details = $this->widget_model->get_sectionDetails($page_details['menuid'], $viewmode);
		
		$is_home_page = ((strtolower(@$section_details['Sectionname']) == 'home') || @$section_details['Sectionname'] =="முகப்பு" ) ? 'y' : 'n';
        $section_page_id = $page_details['menuid'];
		
		if($is_home_page=='y'){
		$page_param = 'home';
		}else{
		$page_param = $page_details['menuid'];
		}
		
	    $xml  = "";
		if($page_type==1){
		$xml				= simplexml_load_string($page_details['templatexml']);		
		$template_id 		= (int)$xml->attributes()->templateid;
		$template_details 	= $this->template_design_model->getTemplateDetails($template_id);						
		$tmpl_values 		= explode("-", $template_details['template_values']);	
		if($page_details['use_parent']==1 && $page_type==1){
		$parent_sectionid = $section_details['ParentSectionID'];
		$parent_xml = $this->get_parent_article_page($parent_sectionid, $page_type);
		$xml        = simplexml_load_string($parent_xml['templatexml']);
		$page_details['common_header']     = $parent_xml['common_header'];
		$page_details['common_rightpanel'] = $parent_xml['common_rightpanel'];
		$page_details['common_footer']     = $parent_xml['common_footer'];
		}	
		}else if($page_type ==2){
		$xml				= simplexml_load_string($page_details['templatexml']);
		if($page_details['common_header']==1){	
		$common_xml = $this->get_parent_article_page(10000, $page_type);
		$xml        = simplexml_load_string($common_xml['templatexml']);
		}
		$template_id 		= (int)$xml->attributes()->templateid;
		$template_details 	= $this->template_design_model->getTemplateDetails($template_id);						
		$tmpl_values 		= explode("-", $template_details['template_values']);	
		}

		if(strlen($xml) == 0 && $page_type==2)
		{
			if($section_details['IsSubSection'] == '1')
			{				
				
				$page_details = $this->get_parent_article_page($section_details['ParentSectionID'], "2");	
				$xml				= simplexml_load_string($page_details['templatexml']);				
				$template_id 		= (int)$xml->attributes()->templateid;
				$template_details 	= $this->template_design_model->getTemplateDetails($template_id);						
                $tmpl_values 		= explode("-", $template_details['template_values']);
			}
			else
			{
				$page_details = $this->get_parent_article_page(10000, "2");
				$xml				= simplexml_load_string($page_details['templatexml']);					
				$template_id 		= (int)$xml->attributes()->templateid;
				$template_details 	= $this->template_design_model->getTemplateDetails($template_id);						
				$tmpl_values 		= explode("-", $template_details['template_values']);		
			}
		}
				
		$data['viewmode'] = $viewmode;
		$data['content_from'] = "preview"; 
		if($xml != '' && !empty($xml))
		{

			$tplheader_values 	= $xml->tplcontainer;		
			$page_type= $page_details['pagetype'];
			if($page_type =='1'){		
			$data['header'] 	= $this->load_saved_template_data($tplheader_values, 'top', "header", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param,'', $page_details);

			$data['body'] 		= $this->load_saved_template_data($tplheader_values, 'left', "template_body", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number,  $page_type, $page_param,'', $page_details);

			$data['footer'] 	= $this->load_saved_template_data($tplheader_values, 'footer', "footer", $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number,  $page_type, $page_param,'', $page_details);

			}else
			{			
			$section_id              = $section_details['Section_id'];
			$sectionname             = strtolower($section_details['URLSectionName']);
			$parent_sectionname      = '';
			if($section_details['ParentSectionID']!=''){
			$parent_section          = $this->widget_model->get_parent_sectionmane($section_id, $viewmode);
			$parent_sectionname      = strtolower($parent_section['URLSectionName']);
			}
			switch ($sectionname || $parent_sectionname) {
				case ($sectionname == "galleries" || $sectionname == "photos" || $parent_sectionname == "galleries"):
					$content_type_id = 3;
					break;
				case ($sectionname == "videos" || $parent_sectionname == "videos"):
					$content_type_id = 4;
					break;
				case ($sectionname =="audios" || $parent_sectionname == "audios"):
					$content_type_id = 5;
					break;
				case ($sectionname == "resources"):
					$content_type_id = 6;
					break;
				default:
					$content_type_id = 1;
			}
			
			$content_det 	= $this->widget_model->widget_article_admin_preview($section_id, $content_type_id);	
			$content_details['detail_content'] = $content_det;
			$content_details['content_type']   = $content_type_id;
			
			if($content_type_id=='1' || $content_type_id=='3' || $content_type_id=='4' || $content_type_id=='5' || $content_type_id=='6'){
			$page_type = $page_details['pagetype'];
			$data['header'] 	= $this->load_saved_template_data($tplheader_values, 'top', "header", $tmpl_values, $content_details, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param,'', $page_details);
			$data['body'] 		= $this->load_saved_template_data($tplheader_values, 'left', "template_body", $tmpl_values, $content_details, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param,'', $page_details);
			$data['footer'] 	= $this->load_saved_template_data($tplheader_values, 'footer', "footer", $tmpl_values, $content_details, $is_home_page, $static_view, $viewmode, $image_number, $page_type, $page_param,'', $page_details);
			}
			$data['content_id']     	= $content_det[0]['content_id'];
			$data['content_type']	    = $content_type_id;
			}
			$data['header_ad_script']	= $page_details['Header_Adscript'];
			
			$page_type= $page_details['pagetype'];
			$data['page_type']	= $page_type;
			if($page_type=='1'){
				if($page_details['menuid']==10000){
				$page_details 	= $this->widget_model->get_sectionid_by_name("Home", $viewmode);
				$section_page_id = $page_details['Section_id'];
				}
				
			$data['section_details']	= $this->widget_model->get_sectionDetails($section_page_id, $viewmode);
			$this->load->view("admin/view_frontend", $data);
			}else{
			$data['article_details']	= $content_det;
			$this->load->view("admin/view_remodal_article_preview", $data);
			}
		}
		else
		{
			$data['header'] 	= "";
			$data['body'] 		= "";
			$data['footer'] 	= "";
		}
	}
	
	public function load_saved_template_data($tplheader_values, $file_type, $file_name, $tmpl_values, $content_details, $is_home_page, $static_view, $viewmode, $image_number,  $page_type, $page_param, $content_from, $page_details)
	{
		
		if($page_type == 1)
		{
			$templateString = $this->section_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_details, $is_home_page, $static_view, $viewmode, $image_number,  $page_type, $page_param, $content_from, $page_details);
		}else
		{
			$templateString = $this->article_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_details, $is_home_page, $static_view, $viewmode, $image_number,  $page_type, $page_param, $content_from, $page_details);
		}
		$view = $this->load->view("admin/common_template/".$file_name, $templateString, true);
		return $view;
	}
	public function section_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_id, $is_home_page, $static_view, $viewmode, $image_number,  $page_type, $page_param, $content_from, $page_details)
	{
		$b_section_inc = 0;
		$templateString['view_templ'] = '';		
		if($file_type == "left") // only for Body section content
		{
			$templateString['view_templ'] .= '<div class="row">';
		}
		if($page_details['common_header']==1 && $file_type=='top'){
			$commonTemplate_XML	= $this->common_Template_XML($page_type);
			$version_id = $commonTemplate_XML['Published_Version_Id'];
			if(file_exists(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml')){
			$xml          = simplexml_load_file(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml');   // load from folder path
			}else{
			$version_xml = $this->template_design_model->template_rendering_by_version_id($version_id);
			$xml				= simplexml_load_string($version_xml['Template_XML']);
			}
			$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
			}else if($page_details['common_footer']==1 && $file_type =='footer'){
			$commonTemplate_XML	= $this->common_Template_XML($page_type);
			$version_id = $commonTemplate_XML['Published_Version_Id'];
			if(file_exists(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml')){
			$xml = simplexml_load_file(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml');   // load from folder path
			}else{
			$version_xml = $this->template_design_model->template_rendering_by_version_id($version_id);
			$xml				= simplexml_load_string($version_xml['Template_XML']);
			}
			$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
			}
			$lvalue=0;
	 if(count($tplheader_values)>0){
		foreach($tplheader_values as $key => $values)
		{
			
			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];			
			if($find_layout == $file_type)
			{								
				
				if($file_type == "left") // only for Body section content
				{
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{
						if(($section_cl_val == 3 || $section_cl_val == 6 ) && array_sum($body_section) == 4){
							$home_last_column = "";
						}
						else{
							
							$home_last_column = "ColumnSpaceRight";
						}
					}
					
					//  For only three column template  //
					if(count($body_section) == 3)
					{
						if(($section_cl_val == 3 || $section_cl_val == 6 ) && array_sum($body_section) == 4){
						}
						else{
							if($b_section_inc == 0)
							{
								$col_sm_val		= "3";
							}
							if($b_section_inc == 1)
							{
								$col_sm_val		= "9";
							}
						}
					}
					
					if(($lvalue==1 || $lvalue==2) && $page_details['common_rightpanel']==1 && $home_last_column==''){
					$commonTemplate_XML	= $this->common_Template_XML($page_type);
					$version_id = $commonTemplate_XML['Published_Version_Id'];
					if(file_exists(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml')){
					$xml = simplexml_load_file(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml');   // load from folder path
					}else{
					$version_xml = $this->template_design_model->template_rendering_by_version_id($version_id);
					$xml				= simplexml_load_string($version_xml['Template_XML']);
					}
					$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
						
						foreach($tplheader_values as $key => $values)
							{
								$layout = explode("-", $values['name']);
					$find_layout = $layout[count($layout)-1];
					if($find_layout == $file_type)
					{
								if($file_type == "left" && ($lvalue==1 || $lvalue==2)) ///// only for Body section content
						{
								$right_panel = $values;
						}
					}
							}
							$values = $right_panel;
							
						}
				
					
					
					$c_class_value 	= " col-lg-".$section_cl_val." col-md-".$section_cl_val." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ".$home_last_column." ";
					
					$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
					$b_section_inc ++;
					$lvalue++;
				}
				$widget = 0;
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					$templateString['view_templ'] .= '<div class="row">';
					//  Chosen Container //
					//  Retrieve the Container details using container id ( type ) //
					$widgetContainer_details = $this->template_design_model->getContainer($cvalues['type']);		
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						
						if (isset($container_layout[$c_inc])) 
						{
						   $c_span_val = $container_layout[$c_inc];	
						}
						else
						{
							$c_span_val = "";	
						}
						
												
						$padding_zero = "";
						
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						//  Added Widgets  //
						if($wvalues['id'] != '' && $wvalues['cdata-widgetStatus']!=2 )   //load only status active widget 
						{	
						date_default_timezone_set("Asia/Kolkata");														
						if( (($wvalues['cdata-widgetpublishOn']=='' || $wvalues['cdata-widgetpublishOn']=='undefined') ||($wvalues['cdata-widgetpublishOn']!='undefined' && (strtotime('now') > strtotime($wvalues['cdata-widgetpublishOn'])) )) && ((($wvalues['cdata-widgetpublishOff']=='' || $wvalues['cdata-widgetpublishOff']=='undefined') || $wvalues['cdata-widgetpublishOff']!='undefined' && (strtotime('now') < strtotime($wvalues['cdata-widgetpublishOff'])) )) ) //check schedule status
						{
							
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> $image_number,
													 "page_param"       => $page_param,
													 "close_param"      => '', //"?pm=".$page_param,
													 "page_type"        => $page_type,
													 "content_from"     => $content_from,
													 "widget_position"  => $file_name,
												);	
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
							$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						
							
						}   // schedule check ends here
						}
						else if($wvalues['cdata-widgetStatus']==2 ){
						    $templateString['view_templ'] .= '';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;
						}
							
					
				$templateString['view_templ'] .= '</div>';	
				$widget++;	
				}// close - foreach($values->widgetcontainer)							
				if($file_type == "left") ///// only for Body section content
				{
					$templateString['view_templ'] .= '</div>';
				}
			}// close - if($find_layout);			
		}// close -  foreach($tplheader_values)
	 }

		if($file_type == "left") // only for Body section content
		{
			$templateString['view_templ'] .= '</div>';
		}
		return $templateString;
	}
	
	public function article_xml_containers($tplheader_values, $file_type, $file_name, $tmpl_values, $content_details, $is_home_page, $static_view, $viewmode, $image_number,  $page_type, $page_param, $content_from, $page_details)
	{
		$content_id      = (count(@$content_details['detail_content'][0])>0)? $content_details['detail_content'][0]['content_id']: "";
		$content_type_id = $content_details['content_type'];
		$content_details = $content_details['detail_content'];
		$b_section_inc = 0;
		$templateString['view_templ'] = '';
		
		if($file_type == "left") // only for Body section content
		{
			$templateString['view_templ'] .= '<div class="row">';
		}
		if($content_type_id==1){
		if($page_details['common_header']==1 && $file_type=='top'){
			$commonTemplate_XML	= $this->common_Template_XML($page_type);
			$version_id = $commonTemplate_XML['Published_Version_Id'];
			if(file_exists(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml')){
			$xml          = simplexml_load_file(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml');   // load from folder path
			}else{
			$version_xml = $this->template_design_model->template_rendering_by_version_id($version_id);
			$xml				= simplexml_load_string($version_xml['Template_XML']);
			}
			$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
			}else if($page_details['common_footer']==1 && $file_type =='footer'){
			$commonTemplate_XML	= $this->common_Template_XML($page_type);
			$version_id = $commonTemplate_XML['Published_Version_Id'];
			if(file_exists(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml')){
			$xml = simplexml_load_file(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml');   // load from folder path
			}else{
			$version_xml = $this->template_design_model->template_rendering_by_version_id($version_id);
			$xml				= simplexml_load_string($version_xml['Template_XML']);
			}
			$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
			}
			$lvalue=0;
	 if(count($tplheader_values)>0){
		foreach($tplheader_values as $key => $values)
		{
			
			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];			
			if($find_layout == $file_type)
			{								
				
				if($file_type == "left") // only for Body section content
				{
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{						
						$home_last_column = "ColumnSpaceRight";
					}
					
					//  For only three column template  //
					if(count($body_section) == 3)
					{
						if($b_section_inc == 0)
						{
							$col_sm_val		= "3";
						}
						if($b_section_inc == 1)
						{
							$col_sm_val		= "9";
						}
					}
					
					if(($lvalue==1 || $lvalue==2) && $page_details['common_rightpanel']==1 && $home_last_column==''){
					$commonTemplate_XML	= $this->common_Template_XML($page_type);
					$version_id = $commonTemplate_XML['Published_Version_Id'];
					if(file_exists(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml')){
					$xml = simplexml_load_file(FCPATH.'uploads/page_layouts/temp/'.'10000_'.$page_type.'_'.$version_id.'.xml');   // load from folder path
					}else{
					$version_xml = $this->template_design_model->template_rendering_by_version_id($version_id);
					$xml				= simplexml_load_string($version_xml['Template_XML']);
					}
					$tplheader_values 	= (count($xml)> 1)? $xml->tplcontainer : array();
						
						foreach($tplheader_values as $key => $values)
							{
								$layout = explode("-", $values['name']);
					$find_layout = $layout[count($layout)-1];
					if($find_layout == $file_type)
					{
								if($file_type == "left" && ($lvalue==1 || $lvalue==2)) ///// only for Body section content
						{
								$right_panel = $values;
						}
					}
							}
							$values = $right_panel;
							
						}
				
					
					if($content_type_id ==1){
					$c_class_value 	= " col-lg-".$section_cl_val." col-md-".$section_cl_val." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ".$home_last_column." ";
					}else{
					$c_class_value 	= " col-lg-12"." col-md-12"." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ";
					}
					$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
					$b_section_inc ++;
					$lvalue++;
				}
				
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					$templateString['view_templ'] .= '<div class="row">';
					//  Chosen Container //
					//  Retrieve the Container details using container id ( type ) //
					$widgetContainer_details = $this->template_design_model->getContainer($cvalues['type']);		
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						if (isset($container_layout[$c_inc])) 
						{
						   $c_span_val = $container_layout[$c_inc];	
						}
						else
						{
							$c_span_val = "";	
						}
						
												
						$padding_zero = "";
						
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						////////  Added Widgets  ///////////
						if($wvalues['id'] != '' && $wvalues['cdata-widgetStatus']!=2 )   //load only status active widget 
						{	
						date_default_timezone_set("Asia/Kolkata");														
						if( (($wvalues['cdata-widgetpublishOn']=='' || $wvalues['cdata-widgetpublishOn']=='undefined') ||($wvalues['cdata-widgetpublishOn']!='undefined' && (strtotime('now') > strtotime($wvalues['cdata-widgetpublishOn'])) )) && ((($wvalues['cdata-widgetpublishOff']=='' || $wvalues['cdata-widgetpublishOff']=='undefined') || $wvalues['cdata-widgetpublishOff']!='undefined' && (strtotime('now') < strtotime($wvalues['cdata-widgetpublishOff'])) )) ) //check schedule status
						{
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> 1,
													 "page_param"       => $page_param,
													 "close_param"      => '', //"?pm=".$page_param,
													 "page_type"        => $page_type,
													 "content_from"     => "preview",
													 "content_type"     => $content_type_id,
													 "detail_content"   => $content_details,
                                                     "widget_position"  => $file_name,
												);	
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
							$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						}   // schedule check ends here
						}
						else if($wvalues['cdata-widgetStatus']==2 ){
						    $templateString['view_templ'] .= '';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;	
					}
				$templateString['view_templ'] .= '</div>';		
				}// close - foreach($values->widgetcontainer)

				if($file_type == "left") ///// only for Body section content
				{
					$templateString['view_templ'] .= '</div>';
					
				}
			}// close - if($find_layout);			
		}// close -  foreach($tplheader_values)
	 }
	}
	else{
	   $lw=1;$lh=1;
	   foreach($tplheader_values as $key => $values)
		{
			
			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];			
			if($find_layout == $file_type)
			{								
				if($file_type == "top" || $file_type == "footer") // for header and footer in Content type 3 & 4
				{
					if($lh==1){
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{
						
						$home_last_column = "ColumnSpaceRight";
					}
					
					//  For only three column template  //
					if(count($body_section) == 3)
					{
						if($b_section_inc == 0)
						{
							$col_sm_val		= "3";
						}
						if($b_section_inc == 1)
						{
							$col_sm_val		= "9";
						}
					}
					
					$c_class_value 	= " col-lg-12"." col-md-12"." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ";
					$b_section_inc ++;
					
					$hw=1; 
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					if($hw==1){
					$templateString['view_templ'] .= '<div class="row">';
					
					//  Chosen Container //
												
					//  Retrieve the Container details using container id ( type ) //
					$widgetContainer_details = $this->template_design_model->getContainer($cvalues['type']);
						
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						$c_span_val = $container_layout[$c_inc];	
						
						$padding_zero = "";
						
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						//  Added Widgets  //
						
						if($wvalues['id'] != '')
						{	
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> 1,
													 "page_param"       => $page_param,
													 "close_param"      => '', //"?pm=".$page_param,
													 "content_from"     => "preview",
													 "content_type"     => $content_type_id,
													 "detail_content"   => $content_details,
                                                     "widget_position"  => $file_name,
												);	
												
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
							$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;	
					}
				$templateString['view_templ'] .= '</div>';
				}		
				}// close - foreach($values->widgetcontainer)	
				
				if($file_type == "top" || $file_type == "footer") // for header and footer in Content type 3 & 4
				{
					//$templateString['view_templ'] .= '</div>';
				}
					}
					$lh++;
				}
				
			}// close - if($find_layout);	
		
		}
		foreach($tplheader_values as $key => $values)
		{

			$layout = explode("-", $values['name']);
			$find_layout = $layout[count($layout)-1];			
			if($find_layout == $file_type)
			{								

				if($file_type == "left") // only for Body section content
				{
					if($lw==1){
					$body_section 	= explode(",",$tmpl_values[1]);
					$section_cl_val	= $body_section[$b_section_inc] * (12 / array_sum($body_section));
					
					$col_sm_val		= "12";
					$col_xs_val		= "12";
					$home_last_column = "";
					
					if($b_section_inc != (count($body_section)-1) && count($body_section) > 0)
					{
						//$home_last_column = "HomeLastColumn";
						$home_last_column = "ColumnSpaceRight";
					}
					
					//  For only three column template  //
					if(count($body_section) == 3)
					{
						if($b_section_inc == 0)
						{
							$col_sm_val		= "3";
						}
						if($b_section_inc == 1)
						{
							$col_sm_val		= "9";
						}
					}
					
					$c_class_value 	= " col-lg-12"." col-md-12"." col-sm-".$col_sm_val." col-xs-".$col_xs_val." ";
 
					$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
					$b_section_inc ++;
					
					$w=1; 
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					if($w==1){
					$templateString['view_templ'] .= '<div class="row">';
					
					/* Chosen Container */
					/* Retrieve the Container details using container id ( type ) */
					$widgetContainer_details = $this->template_design_model->getContainer($cvalues['type']);
					$container_layout = explode(",",$widgetContainer_details['container_values']);	
					$c_inc = 0;		  
					
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						$c_span_val = $container_layout[$c_inc];	
						
						$padding_zero = "";
						
						$xs_val = "12";
																			
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
																		

						////////  Added Widgets  ///////////
						if($wvalues['id'] != '')
						{															
							$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
							$widget_content = array("content_id" 		=> $content_id,
													 "widget_values" 	=> $wvalues,
													 "mode" 			=> $viewmode,
													 "is_home_page" 	=> $is_home_page,
													 "image_number" 	=> 1,
													 "page_param"       => $page_param,
													 "close_param"      => '', //"?pm=".$page_param,
													 "content_from"     => "preview",
													 "content_type"     => $content_type_id,
													 "detail_content"   => $content_details,
                                                     "widget_position"  => $file_name,
												);	
												
							$static_veiw_path = explode("/",$wvalues['data-widgetfilepath']);
							$static_veiw_path[2] = "static_widgets/".$static_veiw_path[2]; 
							$static_veiw_path = implode("/",$static_veiw_path);
							$widget_file_path = ($static_view == 'static') ? $static_veiw_path : $wvalues['data-widgetfilepath'];
							
							$view = $this->fill_widget($widget_file_path,$widget_content); 
							$templateString['view_templ'] .= $view. '</div>';
						}
						else
						{
							$templateString['view_templ'] .= ' widget is Not added';
						}						
						$templateString['view_templ'] .= '</div>';
						$c_inc ++;	

					}
				$templateString['view_templ'] .= '</div>';
				}		
				}// close - foreach($values->widgetcontainer)	
				
				if($file_type == "left") ///// only for Body section content
				{
					$templateString['view_templ'] .= '</div>';
				}
					}
					$lw++;
				}				
			}// close - if($find_layout);	
		
		}// close -  foreach($tplheader_values)
					
	}
		if($file_type == "left") ///// only for Body section content
		{
			$templateString['view_templ'] .= '</div>';
		}
		return $templateString;
	}
	
	public function clear_cache($from)
	{
		$this->load->library("memcached_library");
		$this->memcached_library->flush();
		if($from!="home"){
		echo "cache cleared successfully!";
		}
	}
	
	public function widget_instance()
	{
		$page_id		= $this->input->post("page_id");

		$page_details	= $this->template_design_model->get_template_xmlcontent($page_id, '');		
		$section_id		= $page_details['menuid'];
		$pagetype		= $page_details['pagetype'];		
		$container_id  	= $this->input->post("container_id");
		
		$widget_disp_order  	 = $this->input->post("widget_disp_order");		
		$data['widget_instance'] = $this->template_design_model->getWidgetInstance($section_id, $pagetype, $container_id, $widget_disp_order, '', 'adminview');
		header("content-type: application/json");
		echo json_encode($data);
	}
	
	public function save_widget_config($is_from_copy_widget)
	{
		//widget_configuration		
		$insert_data		= "";
		$login_user_id		= USERID;
		$widget_type		= $this->input->post("widget_type");		
		$widget_instance_id = $this->input->post("widget_instance_id");		
		$version_id			= $this->input->post("versionId");
		$page_id			= $this->input->post("page_id");
		$widget_rendering_type= $this->input->post("widget_rendering_type");
				
		$configuration 		= array_key_exists("widget_configuration",$_POST)? json_decode( $this->input->post('widget_configuration') ):'';	
		if($configuration != '' && isset($configuration->widgetCategory)){
			$widget_customtitle = trim(@$configuration->customTitle);
			$rendering_mode		= @$configuration->renderingMode;
			$widget_section_id	= @$configuration->widgetCategory;
			$widget_adscript	= trim(@$configuration->iframeUrl);
			$widget_showsummary	= @$configuration->showSummary;			
			$widget_bgcolor		= trim(@$configuration->customBgColor);
			$widget_max_articles= @$configuration->customMaxArticles;
			$is_cloned			= @$configuration->isCloned;			
			$insert_data		= "";			
			$widget_mainsection_id_extra = $this->input->post("widget_mainsection_id_extra");
			$widget_subconfig_id_extra = $this->input->post("widget_subsection_id_extra");			
			$widget_publishon = (@$configuration->widgetpublishOn != '') ? date("Y-m-d H:i:s", strtotime(@$configuration->widgetpublishOn)) : "00-00-00 00:00:00";
			$widget_publishoff = (@$configuration->widgetpublishOff != '') ? date("Y-m-d H:i:s", strtotime(@$configuration->widgetpublishOff)) : "00-00-00 00:00:00";
			
			$widget_status = @$configuration->widgetStatus;
			//////  Save widget Custom title and rendering mode values in widgetinstance-table ///////
			if($widget_instance_id != '' && !empty($widget_instance_id))
			{
				if($widget_rendering_type == 3)
				{
					//////  Old code - before introducing temporary instance config  //////
					$update_widget =  $this->template_design_model->update_widgetinstance_renderingmode($widget_instance_id, $rendering_mode, $widget_customtitle, $login_user_id, $widget_section_id, $widget_adscript, $widget_bgcolor, $widget_max_articles, $widget_showsummary, $widget_publishon, $widget_publishoff, $widget_status, $version_id, $is_cloned, "");
				}
				else
				{
					/////  Do the changes in temporary tables  ////
					$update_widget = $this->template_design_model->update_widgetinstance_renderingmode_temporarily($widget_instance_id, $rendering_mode, $widget_customtitle, $login_user_id, $widget_section_id, $widget_adscript, $widget_bgcolor, $widget_max_articles, $widget_showsummary, $widget_publishon, $widget_publishoff, $widget_status, $version_id, $is_cloned);						
				}
			}
			/////// Save widget Custom title and rendering mode values in widgetinstance-table //////
			if($widget_type == 1)
			{
				///////////  Normal  ///////////			
				$normal_tab_form_data 					= "";
				$normal_tab_form_data['Instanceid'] 	= $widget_instance_id;
				$normal_tab_form_data['custom_title'] 	= $widget_customtitle;			
				$normal_tab_form_data['section_id'] 	= $configuration->widgetCategory;
				$normal_tab_form_data['section_content_type'] = '';
				$normal_tab_form_data['display_order'] 	= "1";
				$normal_tab_form_data['created_by'] 	= $login_user_id;
				$normal_tab_form_data['sub_section'] 	= array();
				$insert_data[] = $normal_tab_form_data;
			}
			else if($widget_type == 2)
			{
				/////////// simple tab  //////
				$simpletab_list = $configuration->simpleTab->categoryList;
				if(count($simpletab_list) > 0)
				{
					$simple_inc = 1;
					$simple_tab_form_data = "";
					foreach($simpletab_list as $key => $value)
					{					
						$simpletab_data['custom_title'] 	= trim(@$value->customTitle);
						$simpletab_data['section_id'] 		= (@$value->categoryId !='') ? @$value->categoryId : '';
						$simpletab_data['section_content_type']= (@$value->categoryType !='' && @$value->categoryType !='undefined') ? @$value->categoryType : '1';
						$simpletab_data['display_order'] 	= $simple_inc;						
						$simpletab_data['Instanceid'] 		= $widget_instance_id;
						$simpletab_data['created_by'] 		= $login_user_id;
						$simpletab_data['sub_section'] 		= array();
						$simple_tab_form_data[] = $simpletab_data;
						$simple_inc ++;
						
					}
				}
				$insert_data = @$simple_tab_form_data;
			}
			else if($widget_type == 3)
			{
				/////////// nested tab  //////
				$simpletab_list = $configuration->nestedTab->categoryList;
				if(count($simpletab_list) > 0)
				{
					$nested_inc = 1;
					$simple_tab_form_data = "";
					foreach($simpletab_list as $key => $value)
					{					
						$simpletab_data['custom_title'] 	= trim(@$value->customTitle);
						$simpletab_data['section_id'] 		= (@$value->categoryId !='') ? @$value->categoryId : '';
						$simpletab_data['section_content_type']= (@$value->categoryType !='' && @$value->categoryType !='undefined') ? @$value->categoryType : '1';
						$simpletab_data['display_order'] 	= $nested_inc;					
						$simpletab_data['Instanceid'] 		= $widget_instance_id;
						$simpletab_data['created_by'] 		= $login_user_id;
						$nested_sub_inc = 1;
						$simpletab_sub_data = "";
						$simpletab_data['sub_section'] = "";
						foreach($value->childCategory as $subkey => $subCategory)
						{
							$simpletab_sub_data['custom_title'] 	= trim(@$subCategory->customTitle);
							$simpletab_sub_data['section_id'] 		= (@$subCategory->categoryId !='') ? @$subCategory->categoryId : '0';
							$simpletab_sub_data['section_content_type']= (@$subCategory->categoryType !='' && @$subCategory->categoryType !='undefined') ? @$subCategory->categoryType : '1';
							$simpletab_sub_data['display_order'] 	= $nested_sub_inc;						
							$simpletab_sub_data['Instanceid'] 		= $widget_instance_id;
							$simpletab_sub_data['created_by'] 		= $login_user_id;
							
							$simpletab_data['sub_section'][] = $simpletab_sub_data;
							$nested_sub_inc ++;
						}
						$simple_tab_form_data[] = $simpletab_data;						
						$nested_inc ++;
					}
				}				
				$insert_data = @$simple_tab_form_data;				
			}
			if($widget_rendering_type != 3 && $widget_type != 1)		
			{	
				/////  Do the changes temporary tables  //////	
				$configuration_result 	= $this->template_design_model->insert_widget_configuration_temporarily($insert_data, $widget_mainsection_id_extra, $widget_subconfig_id_extra, $widget_type, $version_id, $page_id);
				$result_msg = $configuration_result;
			}
			else
			{
				$result_msg = $update_widget;
			}
		}
		if($is_from_copy_widget != "from_copy_widget"){
			header("content-type: application/json");
			echo json_encode($result_msg);
		}		
	}
	
	public function show_articles()
	{		
		$related_content_id = '';
		$is_related_article = $this->input->post('is_related_article');
		
		if($is_related_article == "yes")
		{
			 //////  Add related article content id here  ///////////			
			$related_content_id = $this->input->post('relatedContentId');
		}		
		$from_related_article = $this->input->post('from_related_article');
		
		$mainsection_details	= array();		
		$top_div_class 			= $this->input->post("top_div_class");		
		$widget_current_container = $this->input->post("container_values");				
		$widget_instance		= $this->input->post("article_widget_instance_id");
		$widget_pageId			= $this->input->post("widget_pageid");		
		$scroll_top				= $this->input->post("scroll_top");
		
		/* To get the widget article lock details */
		$_POST['widgetinstance_id'] = $widget_instance;
		$_POST['return_type'] 		= "php";
		$_POST['from_release_article_lock'] = "from_show_articles"; // for lock_widget_articles()
		$lock_details 		= $this->lock_widget_articles();
		if($lock_details['locked_user_id'] != ''){
			$articlelocked_user	= $this->template_design_model->get_userdetails_by_id($lock_details['locked_user_id']);	
		}else{
			$articlelocked_user['Firstname'] = "";
			$articlelocked_user['Lastname'] = "";
		}
		$locked_user_name = $articlelocked_user['Firstname']." ".$articlelocked_user['Lastname'];		
		$page_type				= $this->input->post("pagetype");
		$page_sectionid			= $this->input->post("page_sectionid");
		$version_id				= $this->input->post("version_id");
		$live_version_id		= $this->input->post("live_version_id");
		$rootstructures			= $this->input->post("rootstructures");		
		$section_details 		= $this->template_design_model->get_section_by_id(@$page_sectionid);
		
		$home_value = (strtolower(@$section_details['Sectionname']) == 'home'  || @$section_details['Sectionname'] == 'முகப்பு' ) ? 'home' : 'noHome';		
		$widget_values			= json_decode($this->input->post("widget_values"));
		$widget_instance_details= $this->template_design_model->getWidgetInstance('', '','', '', $widget_instance, 'adminview', $version_id	);
		$widget_details			= $this->template_design_model->get_widgetDetails('', $widget_instance);
		$data['widget_details']	= $widget_details;
		$data['widget_instance']= $widget_instance;
		$data['widget_pageId']	= $widget_pageId;
		$data['top_div_class']	= $top_div_class;		
		$data['container_class']= $widget_current_container;
		$data['home_value']		= $home_value;
		$data['scroll_top']		= $scroll_top;		
		$data['page_type']		= $page_type;
		$data['page_sectionid']	= $page_sectionid;		
		$data['widget_values']	= $widget_values;		
		
		$data['widget_instance_details']	= $widget_instance_details;
		$data['related_content_id']			= $related_content_id;
		
		$edit_flag 	 = '0';
		
		if(($from_related_article == '' || $from_related_article == 'no') && $related_content_id == '')
		{			
			/////  Remove widgetinstanceTemparticles  ///////
			$this->template_design_model->inactivate_temp_articlecustomdetails('',USERID); 
			
			///////////  Add widget articles to temporary table using widgetinstance  ///////////////
			$temp_insert = $this->widget_articles_temp($widget_instance, $version_id);								
			$edit_flag 	 = $temp_insert->conn_id->affected_rows; 
		}
		else{
			$edit_flag 	 = 1;  /// Edit state
		}
		
		if($widget_details['widgetStyle'] == 1)
		{
			$data['parentsection_details'] 	= $this->template_design_model->get_section_by_id($widget_instance_details['WidgetSection_ID']);
			$data['main_section_config']	= array();
			$data['instance_subsection_details'] = array();
		}
		
		if($widget_details['widgetStyle'] != 1)
		{
			$main_section_config 	= $this->template_design_model->get_widget_mainsection_config('', $widget_instance);
			if(count($main_section_config) > 0 && FPM_ADDARTICLEOPTION)
			{
				$this->session->set_flashdata(array("alert_message" => ""));
				
				///// Release All locks  ////				
				$_POST['widgetinstance_id'] = $widget_instance;
				$_POST['return_type'] 		= "php";
				$_POST['from_release_article_lock'] = "from_show_articles"; // for lock_widget_articles()
				$this->lock_widget_articles(); ///  Lock only this widget add article page   ////
			}
			else
			{
				if(FPM_ADDARTICLEOPTION)
				{
					$this->session->set_flashdata(array("alert_message" => "Tab configuration is not defined."));										
				}
				else
				{
					$this->session->set_flashdata(array("alert_message" => "You are not authorised to add articles."));
				}
				//////  Unlock widget using widgetinstance id (If available)  ////////
				if($data['page_id'][4] != '') ///// $data['page_id'][4] - widgetinstance id
				{				
					$this->lock_widget_articles_by_instanceid($data['page_id'][4]);
				}
				
				//////  Release the Lock of widget articles for current instance id  //////
				$this->lock_widget_articles_by_instanceid($widget_instance);				
				redirect(folder_name.'/template_designer/index/'.$widget_pageId.'-'.$scroll_top.'-'.$page_sectionid.'-'.$page_type.'-'.$widget_instance.'-'.$version_id);		
				exit;	
			}
			
			$instance_subsection_details = array();
			$parentsection_details = array();
			foreach($main_section_config as $mainconfig_key => $mainconfig_value)
			{
				$instance_subsection_details[]	= $this->template_design_model->get_widget_subsection_config('',$mainconfig_value['WidgetInstanceMainSection_id']);
				$parentsection_details[] 		= $this->template_design_model->get_section_by_id($mainconfig_value['Section_ID']);
				//print_r($parentsection_details);  exit;
			}
			$data['parentsection_details'] 	= $parentsection_details;
			$data['main_section_config']	= $main_section_config;
			$data['instance_subsection_details'] = $instance_subsection_details;
		}
		else{
			///// Release All locks  ////			
			$_POST['widgetinstance_id'] = $widget_instance;
			$_POST['return_type'] 		= "php";
			$_POST['from_release_article_lock'] = "from_show_articles"; // for lock_widget_articles()
			$this->lock_widget_articles(); ///  Lock only this widget add article page   ////	
		}
		$data['edit_flag']				= $edit_flag;
		$data['section_group']			= $this->get_section_group();
		$content_type_group				= $this->common_model->get_content_type("");
		$image_sizes					= $this->template_design_model->get_widget_supported_image_sizes($widget_instance);		
		$supported_image_sizes					= array();
		foreach($image_sizes as $position_sizes)
		{			
			$supported_image_sizes[$position_sizes['Contentposition']] = $position_sizes;
		}
		$data['content_type_group']		= $content_type_group;
		$data['version_id'] 			= $version_id;
		$data['live_version_id'] 		= $live_version_id;
		$data['supported_image_sizes_json']	= json_encode($image_sizes);
		$data['supported_image_sizes']	= json_encode($supported_image_sizes);
		$data['rootstructures']			= $rootstructures;
		$data['current_lock_status']	= $lock_details['articlelock_status'];		
		$data['current_locked_user_id'] = $lock_details['locked_user_id'];
		$data['current_locked_user_name'] = $locked_user_name;
		$data['current_user_lock_status'] = $lock_details['res_status'];
		
		$data['title']			= 'Widget Articles';
		$data['template'] 		= 'widget_articles';
	
		$this->load->view('admin_template',$data);	
	}
	
	public function get_widgetInstancearticle()
	{
		$user_id 					= USERID;
		$edit_flag					= 0;
		$page_name					= strtolower($this->input->post("home_value"));
		$related_content_id 		= $this->input->post("related_content_id");		
		$content_type_names			= array("None"=>1,"Article" => 2,"Gallery" => 3,"Video" => 4,"Audio" => 5);
		$widget_instance 			= $this->input->post("widget_instance");
		$mainSectionConfig_Id 		= $this->input->post("mainSectionConfig_Id");
		$subSectionConfig_Id 		= $this->input->post("subSectionConfig_Id");
		$add_articles_limit 		= $this->input->post("add_articles_limit");		
		$search_bysection			= $this->input->post("search_bysection");
		$search_bytype				= $this->input->post("search_bytype");
		$search_bycontent			= $this->input->post("search_bycontent");
		$search_bytitle				= $this->input->post("search_bytitle");		
		$current_widget_section_id	= $this->input->post("parent_section_id");		
		$from_date					= "";
		$to_date 					= "";		
		$parent_section_id 			= ($search_bysection != '' && $search_bysection != 'all') ? $search_bysection : (($search_bysection == 'all') ? "" : $current_widget_section_id); 
		$content_type_name			= array_search($this->input->post("content_type"), $content_type_names);		
		$content_type_id			= $this->common_model->get_content_type_byname($content_type_name);		
		$data['contenttype_id']		= ($search_bytype != '') ? $search_bytype : (($search_bytype == 'all') ? ""  : ((count($content_type_id) > 0) ? $content_type_id['contenttype_id'] : '' ));
		
		$search_value 	= $search_bytitle;
		$search_by 		= ($search_value != '') ? "Title" : "";
		
		if($search_bycontent == 'content_title')
		{			
			/////  Old method //////			
			$search_value 	= $search_bytitle;
			$search_by 		= ($search_value != '') ? "Title" : "";			
		}
		else if($search_bycontent == 'content_id')
		{			
			$search_value 	= $search_bytitle;
			$search_by 		= ($search_value != '') ? "ContentId" : "";
		}
		$all_available_articles		= $this->template_design_model->list_out_published_articles($parent_section_id, $data['contenttype_id'], $search_value, $search_by, $from_date, $to_date, $widget_instance, $current_widget_section_id);
		$widget_instance_articles 	= $this->template_design_model->get_widgetInstanceTempArticles($widget_instance, $mainSectionConfig_Id,  $subSectionConfig_Id, '', USERID, ''); 		
		
		$instancecontent_idlist 		 = array();		
		$widget_instance_active_articles = array();
		foreach($widget_instance_articles as $key => $for_content_id)
		{	
			$instancecontent_idlist[] = $for_content_id['content_id'];
			$widget_instance_active_articles[] = $for_content_id;
		}
		$data['page_name']				= $page_name;
		$data['all_available_articles'] = @$all_available_articles;
		$data['instance_added_article'] = $widget_instance_active_articles;				
		$data['instancecontent_idlist'] = $instancecontent_idlist;
		$data['add_articles_limit'] 	= $add_articles_limit;
		$data['related_content_id'] 	= $related_content_id;
		
		/* For Edit articles flag */
		$edit_flag_articles 	= $this->template_design_model->get_widgetInstanceTempArticles_active($widget_instance, '',  '', '', USERID, '');
		if(count($edit_flag_articles)){
			$edit_flag = count($edit_flag_articles);
		}
		$data['edit_flag']				= $edit_flag;
		
		$data['related_content_id_popup']	= $this->input->post('isRelatedColumn_avail');
		$data['current_widget_section_id']	= $current_widget_section_id;
		$data['search_bysection']			= $search_bysection;
		$content = $this->load->view("admin/popup_article_details", $data, true);

		echo $content;
	}
	
	//////  Get related articles for relatedContentId
	public function get_widgetInstance_related_article()
	{
		$user_id = USERID;		
		$page_name					= strtolower($this->input->post("home_value"));		
		$related_content_id 		= $this->input->post("related_content_id");
		$content_type_names			= array("None"=>1,"Article" => 2,"Gallery" => 3,"Video" => 4,"Audio" => 5);
		$widget_instance 			= $this->input->post("widget_instance");
		$mainSectionConfig_Id 		= $this->input->post("mainSectionConfig_Id");
		$subSectionConfig_Id 		= $this->input->post("subSectionConfig_Id");
		$add_articles_limit 		= $this->input->post("add_articles_limit");		
		$search_bysection			= $this->input->post("search_bysection");
		$search_bytype				= $this->input->post("search_bytype");
		$search_bycontent			= $this->input->post("search_bycontent");
		$search_bytitle				= $this->input->post("search_bytitle");
		$current_widget_section_id	= $this->input->post("parent_section_id");		
        $from_date					= "";
		$to_date 					= "";        
        $parent_section_id 			= ($search_bysection != '' && $search_bysection != 'all') ? $search_bysection : (($search_bysection == 'all') ? "" : $current_widget_section_id);
		$content_type_name			= array_search($this->input->post("content_type"), $content_type_names);		
		$content_type_id			= $this->common_model->get_content_type_byname($content_type_name);		
		$data['contenttype_id']		= ($search_bytype != '') ? $search_bytype : (($search_bytype == 'all') ? ""  : ((count($content_type_id) > 0) ? $content_type_id['contenttype_id'] : '' ));
		
		if($search_bycontent == 'content_title')
		{
			////  Search by content title variable is - $search_bytitle ///////			
            $search_value 	= $search_bytitle;
			$search_by 		= ($search_value != '') ? "Title" : "";
		}
		else if($search_bycontent == 'content_id')
		{
			////  Search by content Id variable is - $search_bytitle ///////			
            $search_value 	= $search_bytitle;
			$search_by 		= ($search_value != '') ? "ContentId" : "";
		}
        $all_available_articles			 = $this->template_design_model->list_out_published_articles($parent_section_id, $data['contenttype_id'], $search_value, $search_by, $from_date, $to_date, $widget_instance, $current_widget_section_id);		
		
		$widget_instance_articles 		 = $this->template_design_model->get_widgetInstanceTempRelatedArticles($widget_instance, $mainSectionConfig_Id,  $subSectionConfig_Id, '', USERID, $related_content_id);		
		$instancecontent_idlist 		 = array();		
		$widget_instance_active_articles = array();
		foreach($widget_instance_articles as $key => $for_content_id)
		{	
			if($for_content_id['Status'] != '2' && $page_name != "home")
			{
				$instancecontent_idlist[] = $for_content_id['content_id'];
				$widget_instance_active_articles[] = $for_content_id;				
			}
			else if($page_name == "home")
			{
				$instancecontent_idlist[] = $for_content_id['content_id'];
				$widget_instance_active_articles[] = $for_content_id;				
			}			
		}
		$data['page_name']				= $page_name;
		$data['all_available_articles'] = @$all_available_articles;		
		$data['instance_added_article'] = $widget_instance_active_articles;				
		$data['instancecontent_idlist'] = $instancecontent_idlist;
		$data['add_articles_limit'] 	= $add_articles_limit;
		$data['related_content_id'] 	= $related_content_id;
		$edit_flag 						= 0;
		/* For Edit articles flag */
		$edit_flag_articles 	= $this->template_design_model->get_widgetInstanceTempArticles_active($widget_instance, '',  '', '', USERID, '');
		if(count($edit_flag_articles)){
			$edit_flag = count($edit_flag_articles);
		}
		$data['edit_flag']				= $edit_flag;
		
		$data['related_content_id_popup']	= $this->input->post('isRelatedColumn_avail');
		$data['current_widget_section_id']	= $current_widget_section_id;
		$data['search_bysection']			= $search_bysection;
		$content = $this->load->view("admin/popup_article_details", $data, true);				
		echo $content;		
	}
		
	public function create_widget_instance()
	{	
		$user_id			= USERID;
		$widget_post		= $this->input->post();		
		$widget_instance[] 	= array('Pagesection_id' => $widget_post['sectionId'], 'Page_type' => $widget_post['pageType'],'Container_ID' => $widget_post['containerId'], 'Widget_id' => $widget_post['widgetId'], 'WidgetDisplayOrder' => $widget_post['widgetOrderIdInContainer'], 'page_id'=>$widget_post['page_id'], "version_id" =>$widget_post['versionId'], "is_cloned"=>"", "cloned_instance_id" => $widget_post['cloned_instance_id']);
						
		$instanceId = $this->template_design_model->insert_or_update_WidgetInstance($widget_instance, USERID); 	
		$instance_id = array('widget_instance_id' => $instanceId);
		header("content-type : application/json");
		echo json_encode($instance_id);
	}
	
	public function widgetarticle_customdetails()
	{
		$user_id		= USERID;
		$version_id		= $this->input->post("version_id");
		$live_version_id= $this->input->post("live_version_id");
		
		////  Need to upload the file in folder as well as DB  /////// 
		$custom_details = array();
		$custom_details['instanceId'] 				= $this->input->post("instanceId");
		$custom_details['instanceMainSectionId'] 	= $this->input->post("instanceMainSectionId");
		$custom_details['instanceSubSectionId'] 	= $this->input->post("instanceSubSectionId");
		$custom_details['content_id'] 				= $this->input->post("content_id");
		$custom_details['contenttype_id'] 			= $this->input->post("content_type_id");
		$custom_details['Title'] 					= trim($this->input->post("Title"));
		$custom_details['Summary'] 					= trim($this->input->post("Summary"));
		$custom_details['old_image'] 				= trim($this->input->post("old_image"));
		$custom_details['old_image_name'] 			= trim($this->input->post("old_image_name"));
		$custom_details['checked_status'] 			= ($this->input->post("checked_status") == 'true') ? '1' : '2';
		$custom_details['display_order'] 			= $this->input->post("article_priority");
		$custom_details['related_content_id'] 		= $this->input->post("related_content_id");
		$custom_details['version_id'] 				= $version_id;
		$custom_details['edit_flag'] 				= 'Add';
		$custom_details['image_caption']			= trim($this->input->post("image_caption"));
		$custom_details['image_alt']				= trim($this->input->post("image_alt"));
		$custom_details['physical_name']			= trim($this->input->post("physical_name"));
		$custom_details['temp_image_id']			= $this->input->post("temp_image_id");
		$custom_details['old_image_id']				= $this->input->post("old_image_id");
		
		/* For Image move to folder from temporary table  */
		$custom_details['physical_name'] = stripslashes(preg_replace('/[^a-zA-Z0-9_-]/s', '', $custom_details['physical_name']));

		if($custom_details['temp_image_id'] != ''){
			$temp_image_save_details	= $this->template_design_model->temp_custom_image_details($custom_details['temp_image_id'], '')->row_array();
			if(count($temp_image_save_details) > 0)
			{
				if($temp_image_save_details['imagecontent_id'] != '') ////  Image From Image Library
				{
					$custom_details['moved_image_id'] = $this->template_design_model->add_image_by_temp_id($custom_details['image_caption'],$custom_details['image_alt'],$custom_details['physical_name'],$custom_details['temp_image_id']);
				}
				else ///  Image From Local system
				{
					if($temp_image_save_details['save_status'] == 1 || $temp_image_save_details['save_status'] == 2)
					{					
						$custom_details['moved_image_id'] = $this->template_design_model->add_image_by_temp_id($custom_details['image_caption'],$custom_details['image_alt'],$custom_details['physical_name'],$custom_details['temp_image_id']);
						
					}
					else
					{
						$custom_details['moved_image_id'] = '';
						$this->delete_temp_custom_image($temp_image_save_details['image_name'],$custom_details['temp_image_id']);
					}
				}
					if($custom_details['moved_image_id'] != ''){
						/* Get Image details from image library using moved_image_id */
						$image_details = $this->template_design_model->get_image_details_from_library_by_content_id($custom_details['moved_image_id']);
						$custom_details['physical_name'] = $image_details['ImagePhysicalPath'];
					}
					else
					{
						$custom_details['image_caption'] = '';
						$custom_details['image_alt']	 = '';
						$custom_details['physical_name'] = '';
						$custom_details['temp_image_id'] = '';	
						$this->delete_temp_custom_image($temp_image_save_details['image_name'],$custom_details['temp_image_id']);
					}
			}
			else if($custom_details['old_image_id'] != ''	)
			{
				$custom_details['moved_image_id'] = $custom_details['old_image_id'];
				$image_details = $this->template_design_model->get_image_details_from_library_by_content_id($custom_details['moved_image_id']);
				$custom_details['physical_name'] = $image_details['ImagePhysicalPath'];
			}		
			else{ 
				$custom_details['moved_image_id'] = ''; 
				$custom_details['physical_name'] = '';
			}
		}
		else if($custom_details['old_image_id'] != ''	)
		{
			$custom_details['moved_image_id'] = $custom_details['old_image_id'];
			$image_details = $this->template_design_model->get_image_details_from_library_by_content_id($custom_details['moved_image_id']);
			$custom_details['physical_name'] = $image_details['ImagePhysicalPath'];
		}		
		else{ 
			$custom_details['moved_image_id'] = ''; 
			$custom_details['physical_name'] = '';
		}
		
		$custom_details['instancecontent_id'] = $this->input->post('instancecontent_id');
		$widget_content_id = $this->template_design_model->addwidget_articlecustomdetails_temporary($custom_details, USERID); 
		
		if($custom_details['related_content_id'] == '')
		{

			@$related_content_id = '';
			
		}
		else
		{
			@$related_content_id = $custom_details['related_content_id'];
		}
		////////  Arranging records in sequence order  ////////
		$table_name 		 = "widgetinstancecontent_temp";
		$this->template_design_model->display_order_in_sequence($table_name, $custom_details['instanceId'], $custom_details['instanceMainSectionId'],  $custom_details['instanceSubSectionId'], USERID, @$related_content_id);
				
		/////  Move temp table to main table  ////
		 $widget_instance_articles 	= $this->template_design_model->get_widgetInstanceTempArticles_active($custom_details['instanceId'], '',  '', '', USERID);			 		 //echo $this->db->last_query(); exit;
		
		 $article_postedvalues 		= $widget_instance_articles;		
		 $first_update_incactive 	= 1;
			foreach($article_postedvalues as $widget_article_details)
			{						
				$custom_details 						= array();
				$custom_details['instanceId'] 			= $widget_article_details["WidgetInstance_id"];
				$custom_details['instanceMainSectionId']= @$widget_article_details["WidgetInstanceMainSection_id"];
				$custom_details['instanceSubSectionId'] = @$widget_article_details["WidgetInstanceSubSection_ID"];			
				$custom_details['content_id'] 			= $widget_article_details["content_id"];
				$custom_details['contenttype_id'] 	    = $widget_article_details["content_type_id"];
				$custom_details['Title'] 				= trim($widget_article_details["CustomTitle"]);
				$custom_details['Summary'] 				= trim($widget_article_details["CustomSummary"]);			
				$custom_details['instancecontent_id'] 	= "";
				$custom_details['display_order'] 		= $widget_article_details["DisplayOrder"];			
				$custom_details['checked_status'] 		= "1";
				$custom_details['uploaded_image']		= trim(@$widget_article_details['Image']);
				$custom_details['image_name']			= trim(@$widget_article_details['Imagename']);
				$custom_details['related_content_id']	= @$widget_article_details['widgetInstanceRelated_ID'];
				$custom_details['version_id']			= $version_id;
				$custom_details['image_caption']		= trim(@$widget_article_details['custom_image_title']);
				$custom_details['image_alt']			= trim(@$widget_article_details['custom_image_alt']);
				$custom_details['physical_name']		= trim(@$widget_article_details['custom_image_path']);
				$custom_details['moved_image_id']		= @$widget_article_details['customimage_id'];
				$custom_details['live_version_id']		= $live_version_id;
				if($first_update_incactive == 1)
				{
					$this->template_design_model->inactivate_articlecustomdetails($custom_details, USERID); 	
					$first_update_incactive ++;
				}
				$result = $this->template_design_model->addwidget_articlecustomdetails($custom_details, USERID); 	
			}
		
		header("content-type : application/json");
		echo json_encode($widget_content_id);
	}
	public function add_widget_article($is_save_temp, $temp_view )
	{				
		$user_id = USERID;
		$article_postedvalues = $this->input->post('required_values');		
		$widget_instance_id = $this->input->post('widget_instance_id');
		$related_content_id = $this->input->post('related_content_id'); 
		$version_id			= $this->input->post('version_id'); 
		$live_version_id	= $this->input->post('live_version_id'); 
		
		$posted_instance_id 			= $widget_instance_id;
		$posted_instance_mainsection_id = $this->input->post('instance_mainsection_id');
		$posted_instance_subsection_id 	= $this->input->post('instance_subsection_id');
		
		$posted_instance_details 		= array("instanceId"=>$posted_instance_id, "instanceMainSectionId"=>$posted_instance_mainsection_id, "instanceSubSectionId"=>$posted_instance_subsection_id, "related_content_id"=>$related_content_id);
		
		if($is_save_temp == 'publish_articles')
		{			
			if($widget_instance_id != '')
			{
				$page_menu_id = $this->input->post('menu_id'); 
				$page_type = $this->input->post('page_type'); 
				
				///////  Publish articles using widget Instance Id  //////				
				$publishing_instance_articles	= $this->all_instance_articles_by_version_id($version_id, $widget_instance_id); 				
				$published_result = $this->template_design_model->publish_individual_instance_articles($publishing_instance_articles, $widget_instance_id, $version_id);
				
				//SAVE CONTENT
				$homepage_details	= $this->template_design_model->get_homePage_xml();
				if($page_type== 1 && $page_menu_id == $homepage_details['home_section_id'])
				{
					$this->save_html_template($page_menu_id , $page_type);
				}elseif($page_menu_id==10000){
				    $this->save_html_template($page_menu_id , $page_type);
				}
				//////  Lock the widget articles for current instance id  //////				
				$this->template_design_model->change_widget_article_lockstatus($widget_instance_id, $user_id, '1');
				
				if($version_id == $live_version_id){
					// Update the value of `is_changes_are_published` in widgetinstance table
					$this->template_design_model->change_ischanges_published($widget_instance_id, 0); //1->Changes made not published
				}
			}
			else
			{
				$published_result =  array("status"=>false,"message"=>"Failed to publish","inserted_id"=>"", "instancecontent_id"=>""); 
			}
			header("content-type : application/json");
			echo json_encode($published_result);
			exit;
		}
		else if($is_save_temp == 'savepermanent' || $is_save_temp == 'savepermanent-publish_articles')
		{			
			 $widget_instance_articles 	= $this->template_design_model->get_widgetInstanceTempArticles_active($article_postedvalues[0]['widget_instance_id'], '',  '', '', $user_id);
			 $this->template_design_model->inactivate_articlecustomdetails($posted_instance_details, $user_id); 	
			 		
			 $article_postedvalues = $widget_instance_articles;
			 $first_update_incactive = 1;
			 	foreach($article_postedvalues as $widget_article_details)
				{							
					$custom_details 						= array();
					$custom_details['instanceId'] 			= $widget_article_details["WidgetInstance_id"];
					$custom_details['instanceMainSectionId']= @$widget_article_details["WidgetInstanceMainSection_id"];
					$custom_details['instanceSubSectionId'] = @$widget_article_details["WidgetInstanceSubSection_ID"];			
					$custom_details['content_id'] 			= $widget_article_details["content_id"];
					$custom_details['contenttype_id'] 		= @$widget_article_details["content_type_id"];
					$custom_details['Title'] 				= $widget_article_details["CustomTitle"];
					$custom_details['Summary'] 				= $widget_article_details["CustomSummary"];			
					$custom_details['instancecontent_id'] 	= "";
					$custom_details['display_order'] 		= $widget_article_details["DisplayOrder"];			
					$custom_details['checked_status'] 		= "1";
					$custom_details['uploaded_image']		= @$widget_article_details['Image'];
					$custom_details['image_name']			= @$widget_article_details['Imagename'];
					$custom_details['related_content_id']	= @$widget_article_details['widgetInstanceRelated_ID'];	
					$custom_details['version_id']			= $version_id;	
					$custom_details['image_caption']		= @$widget_article_details['custom_image_title'];
					$custom_details['image_alt']			= @$widget_article_details['custom_image_alt'];
					$custom_details['physical_name']		= @$widget_article_details['custom_image_path'];
					$custom_details['moved_image_id']		= @$widget_article_details['customimage_id'];
					$custom_details['live_version_id']		= $live_version_id;
					
					if($first_update_incactive == 1)
					{					
						$first_update_incactive ++;
					}
					if($temp_view == "view" || $is_save_temp == 'savepermanent' || $is_save_temp == 'savepermanent-publish_articles')
					{
						$result = $this->template_design_model->addwidget_articlecustomdetails($custom_details, $user_id);
						echo json_encode($result);									
					}
				}
				
				if($version_id == $live_version_id){
					// Update the value of `is_changes_are_published` in widgetinstance table
					$this->template_design_model->change_ischanges_published($widget_instance_id, 1); //1->Changes made not published
				}
				if($is_save_temp == 'savepermanent-publish_articles')
				{
					$this->add_widget_article("publish_articles", '');
					exit;
				}
		}
		else
		{
			$is_save_temp_exp = explode("-",$is_save_temp);			
			$first_update_incactive = 1;
			$previous_item_displayorder = "";
			
			$temp_table_articles = $this->template_design_model->get_widgetInstanceTempArticles_active($article_postedvalues[0]['widget_instance_id'], @$article_postedvalues[0]['instance_mainsection_id'],  @$article_postedvalues[0]['instance_subsection_id'], '', $user_id);
			
			$edit_flag_status = (count($temp_table_articles) > 0) ? "Edit" : "Add";
			$this->template_design_model->inactivate_instance_temparticles_status($posted_instance_details, $user_id);
			
			if(count($article_postedvalues[0]) > 0)
			foreach($article_postedvalues as $widget_article_details)
			{
				$custom_details 						= array();
				$custom_details['instanceId'] 			= @$widget_article_details["widget_instance_id"];
				$custom_details['instanceMainSectionId']= @$widget_article_details["instance_mainsection_id"];
				$custom_details['instanceSubSectionId'] = @$widget_article_details["instance_subsection_id"];			
				$custom_details['content_id'] 			= @$widget_article_details["article_id"];
				$custom_details['contenttype_id'] 		= @$widget_article_details["content_type_id"];
				$custom_details['Title'] 				= @$widget_article_details["custom_title"];
				$custom_details['Summary'] 				= @$widget_article_details["custom_summary"];			
				$custom_details['instancecontent_id'] 	= @$widget_article_details["instancecontent_id"];				
				$custom_details['display_order'] 		= $first_update_incactive;
				$custom_details['version_id']			= $version_id;
				$custom_details['checked_status'] 		= "1";
				$custom_details['uploaded_image']		= @$widget_article_details['old_image'];
				$custom_details['image_name']			= @$widget_article_details['old_image_name'];
				$custom_details['modified_date']		= @$widget_article_details['modified_date'];
				$custom_details['edit_flag']			= @$edit_flag_status;
				$custom_details['related_content_id']	= @$related_content_id;
				$custom_details['image_caption']		= @$widget_article_details['image_caption'];
				$custom_details['image_alt']			= @$widget_article_details['image_alt'];
				$custom_details['physical_name']		= @$widget_article_details['physical_name'];
				$custom_details['old_image_id']			= @$widget_article_details['old_image_id'];
				
				/* For Image move to folder from temporary table  */
				$custom_details['physical_name'] = stripslashes(preg_replace('/[^a-zA-Z0-9_-]/s', '', $custom_details['physical_name']));
				if($custom_details['old_image_id'] != ''	)
				{
					$custom_details['moved_image_id'] = $custom_details['old_image_id'];
					$image_details = $this->template_design_model->get_image_details_from_library_by_content_id($custom_details['moved_image_id']);
					$custom_details['physical_name'] = $image_details['ImagePhysicalPath'];
				}		
				else{ 
					$custom_details['moved_image_id'] = ''; 
					$custom_details['physical_name'] = '';
				}
				if($is_save_temp_exp[0] == 'saveTemporary')
				{
					$result = $this->template_design_model->addwidget_articlecustomdetails_temporary($custom_details, $user_id);
				}
				else if(@$is_save_temp == 'savepermanent')
				{
					// Permanent save //
					if($first_update_incactive == 1)
					{
						$this->template_design_model->inactivate_articlecustomdetails($custom_details, $user_id); 	
						$first_update_incactive ++;
					}
					
					if($temp_view == "view")
					{						
						$result = $this->template_design_model->addwidget_articlecustomdetails($custom_details, $user_id); 	
						header("content-type : application/json");
						echo json_encode($result);									
					}
				}			
				$first_update_incactive ++;
			}
			/////////  Remove Unchecked article's Related articles in temporary table  /////////			
			$this->template_design_model->inactivate_temp_related_articles($posted_instance_details, $user_id);
			
			////////  Arranging records in sequence order  ////////
				$table_name = "widgetinstancecontent_temp";
				$this->template_design_model->display_order_in_sequence($table_name, $article_postedvalues[0]['widget_instance_id'], @$article_postedvalues[0]['instance_mainsection_id'],  @$article_postedvalues[0]['instance_subsection_id'], $user_id, @$related_content_id); 
			if($is_save_temp == "saveTemporary-savepermanent")
			{				
				$this->add_widget_article("savepermanent", '');
			}
			else if($is_save_temp == "saveTemporary-savepermanent-publish_articles")
			{				
				$this->add_widget_article("savepermanent-publish_articles", '');
			}
		}
	}	
	
	public function upload_image($upload_path, $image_control_name )
	{		
		$imagefile 			= $_FILES[$image_control_name]['tmp_name'];
		$image_file_name 	= date("YmdHis")."_".$_FILES["uploaded_image"]['name'];
		$config = array(
			'upload_path' 		=> $upload_path,
			'allowed_types' 	=> "gif|jpg|png|jpeg",
			'file_name'			=> join("",explode(" ",trim($image_file_name)))			
		);
		$this->upload->initialize($config);
		$result_data = array();
		if (!$this->upload->do_upload($image_control_name))
		{
			$error = array(
				'error' => $this->upload->display_errors()
			);
			$result_data['message'] 		= strip_tags($error['error']);
			$result_data['status'] 			= 0;
			$result_data['image_file_name']  = $image_file_name;			
		}
		else
		{
			$data = array(
				'upload_data' => $this->upload->data()
			);
			
			ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], image_resolution);
			
			$result_data['message'] 		= "Image Uploaded Successfully";
			$result_data['status'] 			= 1;
			$result_data['image_file_name'] = $image_file_name;
		}
		
		return $result_data;	
	}
	
	public function get_instanceSubsectionConfig()
	{		
		$mainsection_configId			= $this->input->post("mainsectionConfigId_post"); 
		$instance_subsection_details	= $this->template_design_model->get_widget_subsection_config('',$mainsection_configId);
		$section_details['section_details'] = array();
		foreach($instance_subsection_details as $key => $subsectionconfig)
		{
			
			if($subsectionconfig['status'] == 1)
			{
				$instance_subsection_details[$key]['subsection_details'] = $this->template_design_model->get_section_by_id($subsectionconfig['SubSection_ID']);;
			}
			
		}
		header("content-type : application/json");
		echo json_encode($instance_subsection_details);
	}
	
	public function widget_articles_temp($current_widgetinstance, $version_id)
	{		
		$user_id  = USERID;
		/////////  Add current widget all articles to Temporary instance articles table
		$widget_instance_temparticles 	= $this->template_design_model->insert_temporary_from_instance_articles ($current_widgetinstance, USERID, $version_id); 	
		return $widget_instance_temparticles;
	}
	 
	public function view_temparticles()
	{
		$this->add_widget_article('saveTemporary', '');		
	}
	
	public function delete_widget_instance()
	{
		if(FPM_ADDPAGEDESIGN)
		{
			$_POST['widgetinstance_id']	= $this->input->post("delete_instance_id");			
			$_POST['lockTemplateid']	= $this->input->post("page_id");			
			$lock_status				= '';
			$delete_option_status		= $this->container_lock_status("php");			
			if($delete_option_status)
			{			
				$user_id  = USERID;
				if($this->input->post("deleteall") == 'y')
				{
					$page_id = $this->input->post("page_id");
					$this->db->trans_begin();
					
					$widget_instance_list 	= explode(",",substr($this->input->post("delete_instance_id"),0,-1));			
					foreach($widget_instance_list as $widget_instance)
					{						
						/////  Do the changes in temporary tables  ////
						$deleted_widget = $this->template_design_model->remove_widget_temporarily($widget_instance, $page_id);
					}
					
					if ($this->db->trans_status() === FALSE ) 
						$this->db->trans_rollback();					
					else
						$this->db->trans_commit();
					
					header("content-type: application/json");
					echo json_encode($deleted_widget);
					exit;					
				}
				else if($this->input->post("deleteTemplate") == 'y')
				{
					$section_pageid = $this->input->post("delete_instance_pageid");
					$page_details 	= $this->template_design_model->get_template_xmlcontent($section_pageid, '');							
					$section_id 	= $page_details['menuid']; 
					$page_type		= $page_details['pagetype'];
					$deleted_widget	= $this->template_design_model->remove_widget_instance('', $section_id, $page_type, USERID); 	
					header("content-type: application/json");
					echo json_encode($deleted_widget);
					exit;
				}
				else
				{					
					$widget_instance 	= $this->input->post("delete_instance_id");
					$page_id			= $this->input->post("page_id");					
					////  Delete instance temporarily  //////
					/////  Do the changes in temporary tables  ////
					$deleted_widget = $this->template_design_model->remove_widget_temporarily($widget_instance, $page_id);						
					header("content-type: application/json");
					echo json_encode($deleted_widget);
					exit;
				}
			}			
			else
			{
				$widget_lock_status["widget_name"] = "";				
				header("content-type: application/json");
				echo json_encode($widget_lock_status);
				exit;
			}
		}
		else
		{
			$unable_to_delete = array("widget_name"=>"You are not authorised to delete ", "status"=>2);
			header("content-type: application/json");
			echo json_encode($unable_to_delete);
		}
	}
	
	public function add_header_script()
	{		
		if(FPM_ADDADVSCRIPTS)
		{
			if(isset($_POST['versionId'])){
				$pagesection_name		= $this->input->post("pagesection_name");
				$page_sectionid			= $this->input->post("page_sectionid");
				$pagetype				= $this->input->post("pagetype");
				$scroll_top				= $this->input->post("scroll_top");
				$version_id				= $this->input->post("versionId"); 
				$page_id				= $this->input->post("page_id");
				/* Lock the header script button for current user */
				$this->template_design_model->update_header_script_lock_status($version_id, '2');				
				$template_version_detail= $this->template_design_model->get_header_script_details($version_id);			
	
				$updated_msg			= "";			
				$updated_msg_type		= '';
				$updated_show_msg		= 0;
				if(@$this->input->post("save_script") == "save_script")
				{			
					$script_text			= trim($this->input->post("add_header_script_save"));
					$version_id				= $this->input->post("versionId");
					$page_id				= $this->input->post("page_id");
					$update_script_text		= $this->template_design_model->update_pagemaster_header_script($script_text, $page_id, $version_id);
					$updated_msg 			= '<span>'.$update_script_text['msg'].'</span>';
					$updated_msg_type		= $update_script_text['msg_type'];
					$updated_show_msg		= 1;
					$template_version_detail= $this->template_design_model->get_header_script_details($version_id);
				}		
				$header_script			= $template_version_detail['Header_Adscript'];
				
				$data['pagesection_name']= $pagesection_name;
				$data['page_sectionid']	= $page_sectionid;				
				$data['pagetype']		= $pagetype;
				$data['scroll_top']		= $scroll_top;
				$data['page_id']		= $page_id;
				$data['header_script']	= $header_script;
				$data['updated_msg']	= $updated_msg;
				$data['msg_type']		= $updated_msg_type;
				$data['show_msg']		= $updated_show_msg;
				$data['version_id']		= $version_id;
				$data['title']			= 'Template Designer';
				$data['template'] 		= 'add_header_script';						
				$this->load->view('admin_template',$data);
			}
			else
			{
				redirect(folder_name."/template_designer");
			}
		}
		else
		{
			$this->session->set_flashdata(array("alert_message" => "You are not authorised to add header script."));
			redirect(folder_name.'/template_designer/', 'refresh');
		}
	}
	
	////////  Add / Edit / Show related articles  ///////
	public function add_related_articles()
	{
		//////  Below input post variables are sent to the show_articles() for Add / Edit / Show related articles  //////		
		//////  related article post values also will sent to this method  ///////
		$this->show_articles();
	}
	public function searchbyarticle()
	{
		$searchby= $this->input->post("Searchby");
		$searchtext= $this->input->post("wordstxt");		
		
		$searchby= $this->template_design_model->articles_searchby_results($searchby,$searchtext);
	}
	
	//////  Common Section page Template XML Details  ////////
	public function common_sectionTemplate_XML()
	{
		$page_details 	= $this->template_design_model->getPageDetails('10000', "1");
		return $page_details;
	}
	
	public function common_Template_XML($page_type)
	{
		$page_details 	= $this->template_design_model->getPageDetails('10000', $page_type);
		return $page_details;
	}
	
	public function update_template_lock()
	{
		$page_id		= $this->input->post('lockTemplateid');
		$lock_status	= $this->input->post('lock_status');
		$user_id 		= USERID;		
		$return_result	= array("msg"=>"","res_status"=>0,"show_msg"=>"0","passed_params"=>"page id:".$page_id.", lock status:".$lock_status, "res_loced_user_id"=>NULL, "res_loced_user_name"=>NULL,"msg_type"=>2);
		
		////// Get lock status of the template using page_id  /////
		$template_lock_details = $this->template_design_model->get_locked_template_details('', @$page_id, '');				
		if(count($template_lock_details) > 0)
		{
			$locked_user_id = $template_lock_details[0]['locked_user_id'];
			$template_lock_status = $template_lock_details[0]['locked_status'];
			/////  Template is unlocked by some other user  ////
			if($template_lock_status == 2 && $locked_user_id != $user_id && $locked_user_id !='') 
			{
				//////  Template can't update the lock to current user  //////				
				$locked_user		  = $this->template_design_model->get_userdetails_by_id(@$locked_user_id);				
				$locked_user_name	  = ($locked_user['Firstname'] != '' || $locked_user['Lastname'] != '') ? $locked_user['Firstname']." ".$locked_user['Lastname'] : $locked_user['Username'];
				
				$return_result['msg'] = "The template is already locked by user '".$locked_user_name."'. You are accessing the template in 'read only mode'. Please wait until the lock is released.";
				$return_result['res_status'] = 0;
				$return_result['show_msg'] 	 = 1;
				$return_result['msg_type'] 	 = 2;
				$return_result['res_loced_user_id'] 	 = @$locked_user_id;
				$return_result['res_loced_user_name'] 	 = @$locked_user_name;
				
			}
			elseif(($locked_user_id == $user_id ) || ($locked_user_id == '') || $template_lock_status == 1) 
			{
				//////  Template can update the lock to current user  //////
				$updated_lockstatus = $this->template_design_model->change_template_lockstatus($lock_status, $page_id, $user_id);	
				if($updated_lockstatus)
				{
					$locked_user		  = $this->template_design_model->get_userdetails_by_id(@$user_id);					
					$locked_user_name	  = ($locked_user['Firstname'] != '' || $locked_user['Lastname'] != '') ? $locked_user['Firstname']." ".$locked_user['Lastname'] : $locked_user['Username'];
					/////  Updated the lock status  /////
					$return_result['msg'] 		 = ($lock_status == 2) ? "Template is locked successfully. You are allowed to do changes" : "Template is unlocked successfully";
					$return_result['res_status'] = 1;
					$return_result['show_msg'] 	 = 1;
					$return_result['msg_type'] 	 = 1;
					$return_result['res_loced_user_id'] 	 = @$user_id;
					$return_result['res_loced_user_name'] 	 = @$locked_user_name;
				}
				else
				{
					/////  DB error while updating lock status  /////
					$return_result['msg'] 		 = "Internal Server Error";
					$return_result['res_status'] = 0;
					$return_result['show_msg'] 	 = 1;
					$return_result['msg_type'] 	 = 2;
					$return_result['res_loced_user_id'] 	 = @$user_id;
				}
			}
		}
		else
		{
			/////////  Page id is doesn't exists  /////
			$return_result['msg'] 		 = "Page id is doesn't exists";
			$return_result['res_status'] = 0;
			$return_result['show_msg'] 	 = 0;
			$return_result['msg_type'] 	 = 2;	
			$return_result['res_loced_user_id'] 	 = @$user_id;		
		}
		header("content-type: application/json");
		echo json_encode($return_result);		
	}
	
	public function is_template_unlocked_by_current_user($from_configuration)
	{
		$page_id				= $this->input->post('lockTemplateid');
		$user_id				= ($from_configuration == "From-Configuration") ? USERID : "";
		$template_lock_details 	= $this->template_design_model->get_locked_template_details('', @$page_id, $user_id);						
		$from_savexml			= $_POST['savexml_method'];
		
		if(count($template_lock_details) > 0)
		{
			$locked_user_id 		= $template_lock_details[0]['locked_user_id'];
			$template_lock_status 	= $template_lock_details[0]['locked_status'];						
			if($template_lock_status == 2 && $locked_user_id == USERID && $locked_user_id != '')
			{
				$res = $template_lock_status; ////  Current user is unlocked the template
			}
			else
			{				
				$lock_update = $this->lock_templates_by_userid(USERID);
				$res = false; ////  Except this template, lock all templates for current user
			}
		}
		else
		{
			/////////  Page id is doesn't exists  /////
			$res = false; ///// No action for this.			
		}
		
		if(@$from_savexml == "yes")
		{
			return $res;		
		}
		else
		{
			echo $res;
		}
	}
	
	public function is_current_user_configuration_locked()
	{
		$page_id				= $this->input->post('locked_widget_instance');
		$config_lock_details 	= $this->template_design_model->get_locked_template_details('', @$page_id, '');						
		$from_savexml			= $_POST['savexml_method'];
		
		if(count($template_lock_details) > 0)
		{
			$locked_user_id 		= $template_lock_details[0]['locked_user_id'];
			$template_lock_status 	= $template_lock_details[0]['locked_status'];						
			if($template_lock_status == 2 && $locked_user_id == USERID && $locked_user_id != '')
			{
				$res = $template_lock_status; ////  Current user is unlocked the template
			}
			else
			{				
				$lock_update = $this->lock_templates_by_userid(USERID);
				$res = false; ////  Except this template, lock all templates for current user
			}
		}
		else
		{
			/////////  Page id is doesn't exists  /////
			$res = false; ///// No action for this.			
		}
		
		if(@$from_savexml == "yes")
		{
			return $res;		
		}
		else
		{
			echo $res;
		}
	}
	
	/////  Locks All templates for the user /////
	public function lock_templates_by_userid($user_id)
	{		
		return $page_lock = $this->template_design_model->lock_templates(@$user_id, '');				
	}
	
	public function lock_widget_articles()
	{
		$widgetinstance_id 		= $this->input->post('widgetinstance_id');
		$return_type			= $this->input->post('return_type');
		$user_id				= USERID;
		$is_changes_published 	= 0;
		$return_result	= array("msg"=>"","res_status"=>0,"show_msg"=>"0", "passed_params"=>"Instance Id:".$widgetinstance_id, "msg_type"=>2, "articlelock_status"=>"", "locked_user_id"=>"", "locked_user_name"=>"", "is_changes_published"=> $is_changes_published);
		$is_from_release_widget_article_lock = $this->input->post("from_release_article_lock");

		//////  Get widget article lock status  ///////
		$widget_article_lock_details = $this->template_design_model->get_locked_widget_article($widgetinstance_id, '');
		
		if(count($widget_article_lock_details) > 0)
		{
			$article_locked_user_id 	= $widget_article_lock_details[0]['article_locked_userid'];
			$article_lock_status 		= $widget_article_lock_details[0]['article_locked_status'];		
			$config_lock_status			= $widget_article_lock_details[0]['config_locked_status'];		
			if($article_lock_status == 1)
			{
				
				$widget_commit_status = $this->is_widget_saved_in_version($widgetinstance_id);								
				if($widget_commit_status)
				{
					///////  Unlock the widget article for current user  //////
					$articlelock_update = $this->template_design_model->change_widget_article_lockstatus($widgetinstance_id, $user_id, '2');
					$articlelocked_user		  	 = $this->template_design_model->get_userdetails_by_id(USERID);	
					$return_result['msg'] 		 = "Add articles is locked";
					$return_result['res_status'] = 1;
					$return_result['msg_type'] 	 = 1;
					$return_result['show_msg'] 	 = 0;
					
					$return_result['articlelock_status'] = 2;
					$return_result['locked_user_id'] 	 = USERID;
					$return_result['locked_user_name'] 	 = $articlelocked_user['Firstname']." ".$articlelocked_user['Lastname'];
				}
				else
				{
					$return_result['msg'] 		= "Please save current template version";
					$return_result['res_status'] = 0;
					$return_result['msg_type'] 	 = 2;
					$return_result['show_msg'] 	 = 1;
				}
			}
			elseif($article_lock_status == 2 && $config_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id != $user_id)
			{				
				/////  Block the current user  /////
				$articlelocked_user		  = $this->template_design_model->get_userdetails_by_id(@$article_locked_user_id);				
				$return_result['msg'] = "This widget is being configured by user '".$articlelocked_user['Firstname']." ".$articlelocked_user['Lastname']."'. Please wait until the user saves the configuration changes.";
				$return_result['res_status'] = 0;
				$return_result['msg_type'] 	 = 2;
				$return_result['show_msg'] 	 = 1;
				
				$return_result['articlelock_status'] = $article_lock_status;
				$return_result['locked_user_id'] 	 = $article_locked_user_id;
				$return_result['locked_user_name'] 	 = $articlelocked_user['Firstname']." ".$articlelocked_user['Lastname'];
				
			}			
			 else{
				 if($is_from_release_widget_article_lock == "from_release_article_lock"){
					$articlelocked_user		  = $this->template_design_model->get_userdetails_by_id(@$article_locked_user_id);
					$return_result['articlelock_status'] = $article_lock_status;
					$return_result['locked_user_id'] 	 = $article_locked_user_id;
					$return_result['locked_user_name'] 	 = $articlelocked_user['Firstname']." ".$articlelocked_user['Lastname'];
					
					if($article_lock_status == 2 && $config_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id == $user_id)
					{				
						/////  Block the current user  /////
						$return_result['msg'] = "This widget configuration is open. Please wait until the user '".$articlelocked_user['Firstname']." ".$articlelocked_user['Lastname']."' saves the configuration changes.";
						$return_result['res_status'] = 0;
						$return_result['msg_type'] 	 = 2;
						$return_result['show_msg'] 	 = 1;
					}
					elseif($article_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id != $user_id ){				
						/////  Block the current user  /////
						$return_result['msg'] = "The article is being added by user '".$articlelocked_user['Firstname']." ".$articlelocked_user['Lastname']."'. Please wait until the user saves the changes.";
						$return_result['res_status'] = 0;
						$return_result['msg_type'] 	 = 2;
						$return_result['show_msg'] 	 = 1;
						
					}elseif($article_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id == $user_id){
						$return_result['msg'] = "Widget article is locked by same user";
						$return_result['res_status'] = 1;
						$return_result['msg_type'] 	 = 1;
						$return_result['show_msg'] 	 = 0;
					} 
				}
				else{
					$res_status = 0;
					$articlelocked_user		  = $this->template_design_model->get_userdetails_by_id(@$article_locked_user_id);
					$return_result['articlelock_status'] = $article_lock_status;
					$return_result['locked_user_id'] 	 = $article_locked_user_id;
					$return_result['locked_user_name'] 	 = $articlelocked_user['Firstname']." ".$articlelocked_user['Lastname'];
					
					if($article_lock_status == 2 && $config_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id == $user_id)
					{				
						/////  Block the current user  /////
						$return_result['msg'] = "This widget configuration is open. Please wait until the user '".$articlelocked_user['Firstname']." ".$articlelocked_user['Lastname']."' saves the configuration changes.";
						$res_status = 0;
						$return_result['msg_type'] 	 = 2;
						$return_result['show_msg'] 	 = 1;
						
					}
					elseif($is_from_release_widget_article_lock == "from_show_articles"){
						if($article_lock_status == 2 && $config_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id != $user_id){		
							$res_status = 0;
						}elseif($article_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id == $user_id)
						{
							$res_status = 1;
						}
					}else{
						$res_status = 1;
					}
					$return_result['msg'] 		 = "";
					$return_result['res_status'] = $res_status;
					$return_result['msg_type'] 	 = 1;
					$return_result['show_msg'] 	 = 0;
					
				}
			}
			$return_result['is_changes_published'] = $widget_article_lock_details[0]['is_changes_are_published']; 	
		} 
		else
		{
			/////////  widgetinstanceid is doesn't exists  /////
			$return_result = false; ///// No action for this.			
		}
		
		if($return_type == "json")
		{
			header("content-type: application/json");
			echo json_encode($return_result);
		}
		else
		{
			return $return_result;
		}
		
	}
	
	/////  Release Locked widget article using widgetinstace id  ///////
	public function lock_widget_articles_by_instanceid($widgetinstance_id)
	{
		return $page_lock = $this->template_design_model->change_widget_article_lockstatus(@$widgetinstance_id, USERID, '1');
	}
	
	//////  Lock widget configuration (if other users not used)  //////
	public function check_widget_config_status()
	{
		$return_type		= $this->input->post('return_type');
		$widgetinstance_id 	= $this->input->post('widgetinstance_id');
		$page_id			= $this->input->post('lockTemplateid');
		$lock_status		= $this->input->post('lock_status');
		$check_renderingtype= $this->input->post('check_renderingtype');
		$user_id			= USERID;
		$current_user_detail= $this->template_design_model->get_userdetails_by_id(USERID);
		$return_result		= array("msg"=>"","res_status"=>0,"show_msg"=>"0", "passed_params"=>"Instance Id:".$widgetinstance_id, 'msg_type'=>2, "design_lock_status"=>"1", "current_user_name"=>$current_user_detail['Firstname']." ".$current_user_detail['Lastname']);
		//////  Check whether the template is unlocked  //////
		$received_savexml_method	= $this->input->post('savexml_method');
		
		$_POST['savexml_method']	= "yes";  /////  This parameter passed to - is_template_unlocked_by_current_user()  ////
		if($check_renderingtype != 3){
		$design_lock_status = $this->is_template_unlocked_by_current_user("From-Configuration");				
		}else{ 
			$design_lock_status = 2; //// For Advertisement widget no need to check template lock
		}
		$_POST['savexml_method']	= $received_savexml_method;
		
		if($design_lock_status == 2) ////  Template is unlocked  ////
		{			
			$widget_article_lock_details = $this->template_design_model->get_locked_widget_article($widgetinstance_id, '');
			
			if(count($widget_article_lock_details) > 0)
			{
				$article_locked_user_id 	= $widget_article_lock_details[0]['article_locked_userid'];
				$article_lock_status 		= $widget_article_lock_details[0]['article_locked_status'];				
				if($article_lock_status == 2 && $article_locked_user_id != '' && $article_locked_user_id != $user_id)
				{
					//////// Widget Add article Locked by other user  //////
					$articlelocked_user		  	= $this->template_design_model->get_userdetails_by_id(@$article_locked_user_id);
					$return_result['msg'] 		= "This widget is already locked by '".$articlelocked_user['Firstname']." ".$articlelocked_user['Lastname']."'. You are accessing widget in 'read only mode'. Please wait until the lock is released.";
					$return_result['res_status']= 0;
					$return_result['msg_type'] 	= 2;
					$return_result['show_msg'] 	= 1;	
					$return_result['design_lock_status'] 	= 1;					
				}
				else
				{		
					$config_locked_user_id 	= $widget_article_lock_details[0]['config_locked_userid'];
					$config_lock_status 	= $widget_article_lock_details[0]['config_locked_status'];	
					if($config_lock_status == 2 && $config_locked_user_id != $user_id && $config_locked_user_id != '')
					{
						//////  This widget config is locked by other user  //////
						$locked_user	  = $this->template_design_model->get_userdetails_by_id(@$config_locked_user_id);				
					$return_result['msg'] = "This widget is already locked by '".$locked_user['Firstname']." ".$locked_user['Lastname']."' user. You are accessing the widget in 'read only mode'. Please wait until the lock is released.";
						$return_result['res_status']= 0;
						$return_result['msg_type'] 	= 2;
						$return_result['show_msg'] 	= 1;
						$return_result['design_lock_status'] 	= 1;
						
					}
					else
					{
						/////  Now current user can update the widget config  //////
						if($lock_status != '')  //// this is from ajax call  //////
						{
							if($article_lock_status == 2 && $config_lock_status == 1)
							{
								$locked_user	  = $this->template_design_model->get_userdetails_by_id(@$article_locked_user_id);				
								$return_result['msg'] = "This widget is already locked by '".$locked_user['Firstname']." ".$locked_user['Lastname']."' for arranging articles. Please wait until the lock is released.";
								$return_result['res_status']= 0;
								$return_result['msg_type'] 	= 2;
								$return_result['show_msg'] 	= 1;
								$return_result['design_lock_status'] 	= 1;
							}
							else
							{
								//////  Check whether the widget is new and committed or not (Available in temporary instance table)  //////
								$widget_commit_status = $this->is_widget_saved_in_version($widgetinstance_id);								
								if($widget_commit_status)
								{
									$update_status = $this->change_widget_config_status($widgetinstance_id, $user_id, $lock_status);
									
									if($update_status)
									{
										$return_result['msg'] 		= "Widget Config lock status changed by same user";
										$return_result['res_status']= 1;
										$return_result['msg_type'] 	= 1;
										$return_result['show_msg'] 	= 0;
										$return_result['design_lock_status'] 	= 1;
									}
									else
									{
										$return_result['msg'] 		= "Internal server error";
										$return_result['res_status']= 0;
										$return_result['msg_type'] 	= 2;
										$return_result['show_msg'] 	= 0;
										$return_result['design_lock_status'] 	= 1;
									}
								}
								else
								{
									$return_result['msg'] 		= "Please save current template version";
									$return_result['res_status']= 0;
									$return_result['msg_type'] 	= 2;
									$return_result['show_msg'] 	= 1;
									$return_result['design_lock_status'] 	= 2;								
								}
								
							}
						}
						else
						{
							$return_result['msg'] 		= "This widget is in use, unable to delete";								
							
							if($config_lock_status == 2 || $article_lock_status == 2 ){
							$return_result['show_msg'] 	= 1;
							$return_result['res_status']= 0;
							$return_result['msg_type'] 	= 2;
							$return_result['design_lock_status'] 	= 1;
							}
							else
							{
								$return_result['show_msg'] 	= 0;
								$return_result['res_status']= 1;
								$return_result['msg_type'] 	= 1;
								$return_result['design_lock_status'] 	= 1;
							}
							$return_result['config_lock_status'] 	= $config_lock_status;							
							$return_result['article_lock_status'] 	= $article_lock_status;
						}
					}					
				}/// else
			  }
		}///// if() -  Check template is unlocked ////
		else
		{
			$page_id				= $this->input->post('lockTemplateid');
			$template_lock_details 	= $this->template_design_model->get_locked_template_details('', @$page_id, '');						
			if(count($template_lock_details) > 0)
			{
				$locked_user_id 		= $template_lock_details[0]['locked_user_id'];
				$template_lock_status 	= $template_lock_details[0]['locked_status'];
				if($template_lock_status == 1)
				{
					$return_result['msg'] = "Please lock template";
					$return_result['res_status']	= 0;
					$return_result['show_msg'] 		= 1;
					$return_result['msg_type'] 		= 3;
					$return_result['design_lock_status'] 	= $design_lock_status;
				}
				elseif($template_lock_status == 2 && $locked_user_id != $user_id && $locked_user_id != '')
				{
					//////  This widget config template is locked by other user  //////
					$locked_user		  = $this->template_design_model->get_userdetails_by_id(@$locked_user_id);				
					$return_result['msg'] = "The template is already locked by user '".$locked_user['Firstname']." ".$locked_user['Lastname']."'. You are accessing the template in 'read only mode'. Please wait until the lock is released.";
					$return_result['res_status']	= 0;
					$return_result['show_msg'] 		= 1;
					$return_result['msg_type'] 		= 2;
					$return_result['design_lock_status'] 	= $design_lock_status;
				}
			}
		}
		if($return_type == "json")
		{
			header("content-type: application/json");
			echo json_encode($return_result);
		}
		else
		{
			return $return_result;
		}
		
	}
	
	public function change_widget_config_status($widgetinstance_id, $user_id, $lock_status)
	{
		////// First Lock widget article for current widget  //////
		$article_lock = $this->template_design_model->change_widget_article_lockstatus($widgetinstance_id, $user_id, $lock_status);
		////// And then lock widget config  //////
		if($article_lock)
		{
			return 	$config_lock = $this->template_design_model->change_widget_config_lockstatus(@$widgetinstance_id, $user_id, $lock_status);
		}
		else
		{
			return false;
		}
		
	}
	
	public function get_widget_lock_status($widgetinstance_id, $page_id, $lock_status, $return_type, $check_widget_renderingtype)
	{
		//////  Check widget Lock  //////		
		$_POST['widgetinstance_id'] = $widgetinstance_id;
		$_POST['lockTemplateid'] 	= $page_id;
		$_POST['lock_status'] 		= $lock_status;
		$_POST['return_type'] 		= $return_type;
		$_POST['check_renderingtype'] = $check_widget_renderingtype; ////  If ==3, then it is advertisement widget  /////
		$widget_lock_status_details = $this->check_widget_config_status();		
		return $widget_lock_status_details;
	}
	
	public function container_lock_status($return_type)
	{
		$user_id				= USERID;
		$post_instance_id		= $this->input->post("widgetinstance_id");
		$widgetinstance_id_list	= ($post_instance_id=='')? array() :explode(",",$post_instance_id);			
		$page_id	   			= $this->input->post("lockTemplateid");			
		$lock_status			= '';
		$check_renderingtype	= $this->input->post("check_renderingtype");			
		$delete_option_status	= true;
		$current_user_detail  	= $this->template_design_model->get_userdetails_by_id(USERID);
		$widget_lock_status		= array("msg"=>"","res_status"=>0,"show_msg"=>"0", "passed_params"=>"Instance Id:".$post_instance_id, 'msg_type'=>2, "current_user_name"=>$current_user_detail['Firstname']." ".$current_user_detail['Lastname']);
		
		if(count($widgetinstance_id_list) > 0)
		{
			foreach($widgetinstance_id_list as $widgetinstance_id)
			{
				if($widgetinstance_id != '')
				{
					$widget_lock_status =  $this->get_widget_lock_status($widgetinstance_id, $page_id, $lock_status, "php", $check_renderingtype);
					if($widget_lock_status['res_status'] != 1)
					{
						$delete_option_status = false;
						break;
					}
				}
				
			}			
		}
		else
		{
			
			//////  Check whether the template is unlocked  //////
			$_POST['savexml_method']	= "yes";  /////  This parameter passed to - is_template_unlocked_by_current_user()  ////
			$design_lock_status = $this->is_template_unlocked_by_current_user("From-Configuration");			
			if($design_lock_status == 2) ////  Template is unlocked  ////
			{
				$widget_lock_status['msg'] 			= "Delete Container";
				$widget_lock_status['res_status']	= 1;
				$widget_lock_status['show_msg'] 	= 0;
				$widget_lock_status['msg_type'] 	= 1;
			}
			else
			{
				$page_id				= $this->input->post('lockTemplateid');
				$template_lock_details 	= $this->template_design_model->get_locked_template_details('', @$page_id, '');						
				
				if(count($template_lock_details) > 0)
				{
					$locked_user_id 		= $template_lock_details[0]['locked_user_id'];
					$template_lock_status 	= $template_lock_details[0]['locked_status'];
					if($template_lock_status == 1)
					{
						$widget_lock_status['msg'] 		 = "Please lock template";
						$widget_lock_status['res_status']= 0;
						$widget_lock_status['show_msg']  = 1;
						$widget_lock_status['msg_type']  = 3;						
					}
					elseif($template_lock_status == 2 && $locked_user_id != $user_id && $locked_user_id != '')
					{
						//////  This widget config template is locked by other user  //////
						$locked_user		  = $this->template_design_model->get_userdetails_by_id(@$locked_user_id);				
						$widget_lock_status['msg'] = "The template is already locked by user '".$locked_user['Firstname']." ".$locked_user['Lastname']."'. You are accessing the template in 'read only mode'. Please wait until the lock is released.";
						$widget_lock_status['res_status']	= 0;
						$widget_lock_status['show_msg'] 	= 1;
						$widget_lock_status['msg_type'] 	= 2;
					}
				}
			}			
		}		
		if($return_type == "php")		
		return $delete_option_status;
		else
		{
			header("content-type: application/json");
			echo json_encode($widget_lock_status);
		}
	}
	
	///////  Get current template is commited or not  ///////
	public function is_template_commited() 
	{
		$page_id				= $this->input->post("page_id");
		$return_type			= $this->input->post("return_type");/////  return type is either json or php code

		$template_commit_status	= $this->template_design_model->get_template_commit_status(@$page_id);								
		$is_current_user_locked = ($template_commit_status['locked_status'] == 2 && $template_commit_status['locked_user_id'] == USERID); 
		if($template_commit_status['locked_status'] == 2 && $template_commit_status['locked_user_id'] == USERID)
		{
			$template_commit_status	= $template_commit_status;			
		}
		else
		{
			$template_commit_status	=  array("tempalte_commit_status" =>1, "locked_status"=>$template_commit_status['locked_status']);
		}
		if($return_type == 'json')
		{
			header("content-type: application/json");
			echo json_encode($template_commit_status);
			exit;
		}
		else
		{
			return $template_commit_status;
		}
	}
	
	//////  Get template versions details  /////
	public function get_template_versions($page_id, $version_id)
	{		
		$template_version_details	= $this->template_design_model->get_template_versions(@$page_id, $version_id);
		return $template_version_details;
	}
	
	////  Load Template versions  /////
	public function load_template_versions()
	{		
		$page_id					= $this->input->post("template_id");
		$default_version			= $this->input->post("default_version");
		$requested_version_id		= $this->input->post("requested_version_id");
		$template_version_details 	= $this->get_template_versions($page_id,'');
		$page_master 				= $this->template_design_model->get_template_xmlcontent($page_id, '');
		$current_published_version 	= $page_master['Published_Version_Id'];
		$work_space_version_id		= $page_master['workspace_version_id'];
		$version_inc = 1;
		$current_version = "";
		
		$_POST['savexml_method']	= "yes";  /////  This parameter passed to - is_template_unlocked_by_current_user()  ////
		$_POST['lockTemplateid']	= $page_id;  /////  This parameter passed to - is_template_unlocked_by_current_user()  ////
		$is_template_unlocked_by_current_user = $this->is_template_unlocked_by_current_user(""); // 2-> Tempalte is unlocked by current user
		if(count($template_version_details) > 0)
		{
			$version_drop_down = '<select name="show_template_version" id="show_template_version" class="" >';
			foreach($template_version_details as $template_version)
			{
				$version_id		= $template_version['Version_Id'];
				if($current_published_version == $version_id)
				{					
					$published_identity	= "live";
				}					
				else
				{					
					$published_identity	= "";
				}	
				/////  For selected option //////			
				if($template_version['NotSavedVersionId'] != '' && $is_template_unlocked_by_current_user == 2)
				{
					if($template_version['NotSavedVersionId'] != $version_id)
					{
						$current_version	= "";
					}
					else
					{
						$current_version	= "selected";
					}					
				}
				else
				{
					if($requested_version_id != '' && !is_null($requested_version_id))
					{
						if($requested_version_id == $version_id)
						{
							$current_version	= "selected";
						}
						else
						{
							$current_version	= "";
						}
					}
					else if($default_version == "new" || $default_version != "")
					{
						if($template_version['Not_Saved_user_id'] != '')
						{
							$current_version	= "selected";
						}
						elseif($current_published_version == $version_id && $current_published_version != '')
						{
							$current_version	= "selected";					
						}					
						elseif($version_inc == count($template_version_details) && $current_published_version == '')
						{
							$current_version	= "selected";
						}
						else
						{
							$current_version	= "";
						}
					}					
					elseif($work_space_version_id == $version_id)
					{
						$current_version	= "selected";
					}
					else
					{
						$current_version	= "";
					}
				}
				$version_name = ($template_version['Version_Name'] != '') ? $template_version['Version_Name'] : 'Version-'.$version_inc;
				$version_drop_down .= '<option '.$current_version.' value="'.$version_id.'">'.$version_name.' &nbsp; '.$published_identity;
				$version_inc ++;
			}
			$version_drop_down .= '</select>';		
		}
		else
		{
			$version_drop_down = '';
		}
		echo $version_drop_down.'<input type="hidden" name="live_version_id" id="live_version_id" value="'.$current_published_version.'" >';

	}
	
	////  Get xml data from version table using version id and update pagemaster table and load page template ///
	public function load_template_by_version_id()
	{
		$version_id = $this->input->post("version_id");
		$update_page_master = $this->template_design_model->load_template_by_version_id($version_id);
		$load_template =($update_page_master) ? "1" : "2";
		header("content-type : application/json"); 
		echo json_encode(array("load_template"=>$load_template));
		
	}
	
	//////  Remove temporary instances from temporaryinstances table using page id  /////
	public function delete_temporary_instance()
	{
		$page_id 		= $this->input->post("pageId");
		$save_status 	= $this->input->post("saveTemplateStatus"); ////  1->Save widget instances, 2->Delete widget instances  ///////
		$delete_temporary_instances = $this->template_design_model->delete_temporary_instance($page_id, $save_status);		
		return $delete_temporary_instances;
		
	}
	
	///////  Publish only advertisements  /////
	public function publish_only_advertisements()
	{
		$version_id 		= $this->input->post("versionId");
		$get_adv_instances 	= $this->get_all_instances_by_version_id($version_id, '1');	///// =1->only advertisement instances, !=1->All widget instances	
		
		$header_adv_script					= $get_adv_instances['version_header_adv_script'];
		$publishing_widget_instances 		= $get_adv_instances['version_widget_instances']; 
		$delete_published_page_section_id 	= $get_adv_instances['version_page_section_id'];
		$delete_published_page_section_type	= $get_adv_instances['version_page_section_type'];
		
		$get_adv_mainConfig	= $this->all_mainSectionConfig_by_version_id($version_id, '1');		
		$publish_advertisements = $this->template_design_model->publish_only_advertisements($version_id, $header_adv_script, $publishing_widget_instances, $get_adv_mainConfig,  $delete_published_page_section_id, $delete_published_page_section_type);

		$publish_results		= array();
		if($publish_advertisements){
			$publish_results['res_status']	= 1;
			$publish_results['show_msg'] 	= "Header Script and all advertisements published successfully";
			$publish_results['msg_type'] 	= 1;
		}
		else
		{
			$publish_results['res_status']	= 0;
			$publish_results['show_msg']	= "Internal server error";
			$publish_results['msg_type'] 	= 2;
		}
		header("content-type : application/json"); 
		echo json_encode($publish_results);
		
	}
	
	public function get_all_instances_by_version_id($version_id, $widget_type) ///  $widget_type is either only advertisement widgets or all widgets
	{
		$get_adv_instances 	= $this->template_design_model->get_adv_instance_by_versionId($version_id, $widget_type);	///// =1->only advertisement instances, !=1->All widget instances			

		$widget_instance_id_list 			= "";
		$publishing_widget_instances 		= $this->convert_db_result_into_insert_query_string($get_adv_instances, "yes");
		$delete_published_page_section_id 	= @$get_adv_instances[0]['Pagesection_id'];
		$delete_published_page_section_type = @$get_adv_instances[0]['Page_type'];			
		$header_adv_script 					= @$get_adv_instances['header_adv_script']['@header_adv_script'];
		
		$return_result  							= array();
		$return_result['version_widget_instances'] 	= $publishing_widget_instances;
		$return_result['version_page_section_id']	= @$get_adv_instances['header_adv_script']['@page_section_id'];
		$return_result['version_page_section_type'] = @$get_adv_instances['header_adv_script']['@page_type'];
		$return_result['version_header_adv_script'] = $header_adv_script;
		return $return_result;
	}
	
	public function all_mainSectionConfig_by_version_id($version_id, $widget_type)
	{
		///// $widget_type=1->only advertisement instances, $widget_type!=1->All widget instances	
		$get_adv_mainSection_config	= $this->template_design_model->get_adv_mainSectionConfig_by_versionId($version_id, $widget_type);	
		$publishing_widget_instances= $this->convert_db_result_into_insert_query_string($get_adv_mainSection_config, "no");		
		return $publishing_widget_instances;
	}
	
	public function all_instance_articles_by_version_id($version_id, $widget_instance_id)
	{
		$get_version_instance_articles	= $this->template_design_model->get_instance_articles_by_versionId($version_id, $widget_instance_id);	
		$publishing_instances_articles	= $this->convert_db_result_into_insert_query_string($get_version_instance_articles, "no");		
		return $publishing_instances_articles;
	}
	
	public function convert_db_result_into_insert_query_string($get_adv_instances, $is_having_headerscript)
	{
		$publishing_widget_instances 		= "";
		if(count($get_adv_instances))
		{		
			$i = 0;									
			foreach($get_adv_instances as $instances)
			{							
				$condition1= ($is_having_headerscript == "yes") ? $i < count($get_adv_instances)-2 : $i < count($get_adv_instances)-1;
				$i ++;
				$condition2 = ($is_having_headerscript == "yes") ? $i < count($get_adv_instances) : true;			
				
				if(count($instances)>0 && ($condition2) )
				{
					
					$j = 0;
					$publishing_widget_instances 	.= "(my";
					$widget_instance_values 		 = "";
					foreach($instances as $join_instances)
					{
						$inst_concat_string =($j==0)?"":",my ";
						$widget_instance_values = $widget_instance_values.$inst_concat_string.addslashes($join_instances); 
						$j ++;
					}
					$pub_concat_close_string = ($condition1) ? ")my,":")my";
					$publishing_widget_instances .= $widget_instance_values.$pub_concat_close_string;				
				}			
			}	
		}
		return $publishing_widget_instances;
	}
	
	public function is_widget_saved_in_version($widget_instance_id)
	{
		$widget_status = $this->template_design_model->is_widget_saved_in_version($widget_instance_id);
		
		if($widget_status)
		{
			$result_status = $widget_status['temp_instanceId'];
			return false;
		}
		else
		{
			return true;
		}
		
	}
	
	public function delete_currentTemplateVersion()
	{
		$version_id		= $this->input->post("versionId");
		$version_status	= $this->template_design_model->delete_currentTemplateVersion($version_id);
		if($version_status)
		{
			$return_msg = array("msg"=>"Template version '".$version_status['Version_Name']."' deleted successfully","res_status"=>1,"show_msg"=>"1", "passed_params"=>"version:".$version_id, 'msg_type'=>1);
		}
		else
		{
			$return_msg = array("msg"=>"Unable to delete this version","res_status"=>0,"show_msg"=>"1", "passed_params"=>"version:".$version_id, 'msg_type'=>2);
		}
		header("content-type : application/json"); 
		echo json_encode($return_msg);
	}
	
	public function update_version_name()
	{
		$version_id		= $this->input->post("versionId");
		$version_name	= $this->input->post("version_name");
		$version_status	= $this->template_design_model->update_version_name($version_id, $version_name);
		if($version_status['status'])
		{
			$return_msg = array("msg"=>"Template version name from '".$version_status['oldversion_name']."' to '".$version_name."' updated successfully","res_status"=>1,"show_msg"=>"1", "passed_params"=>"version:".$version_id.", version_name:".$version_name, 'msg_type'=>1);
		}
		else
		{
			$return_msg = array("msg"=>"Unable to update version name from '".$version_status['oldversion_name']."' to '".$version_name."'","res_status"=>0,"show_msg"=>"1", "passed_params"=>"version:".$version_id.", version_name:".$version_name, 'msg_type'=>2);
		}
		header("content-type : application/json"); 
		echo json_encode($return_msg);
	}
	
	public function get_header_script_lock_status()
	{
		$version_id 				= $this->input->post("versionId");
		$template_version_detail	= $this->template_design_model->get_header_script_details($version_id);		
		$header_lock_status			= array("msg"=>"", "show_msg"=>"2", "msg_type"=>2, "lock_status"=>"1", "locked_user_id"=>"");
		
		$headerscript_locked_user_id= $template_version_detail['HeaderScript_Lock_UserId'];
		$lock_status				= $template_version_detail['HeaderScript_Lock_Status'];
		if($headerscript_locked_user_id != '' && $headerscript_locked_user_id != USERID && $lock_status == 2) /* Header script locked by another user */
		{
			$locked_user		  				= $this->template_design_model->get_userdetails_by_id(@$headerscript_locked_user_id);				
			$header_lock_status['msg'] 			= "The header script is already locked by '".$locked_user['Firstname']." ".$locked_user['Lastname']."' user. Please wait until the lock is released.";
			$header_lock_status['lock_status']	= $lock_status;
			$header_lock_status['show_msg'] 	= 1;
			$header_lock_status['msg_type'] 	= 2;
			$header_lock_status['res_status'] 	= 0;
			$header_lock_status['locked_user_id']= @$headerscript_locked_user_id;
		}
		else if($headerscript_locked_user_id != '' && $headerscript_locked_user_id == USERID)
		{
			$header_lock_status['msg'] 			= "Current user using the header script ";
			$header_lock_status['lock_status']	= $lock_status;
			$header_lock_status['show_msg'] 	= 0;
			$header_lock_status['msg_type'] 	= 1;
			$header_lock_status['res_status'] 	= 1;
			$header_lock_status['locked_user_id']= @$headerscript_locked_user_id;
		}
		else if($headerscript_locked_user_id == '')
		{
			$header_lock_status['msg'] 			= "No user locked";
			$header_lock_status['lock_status']	= $lock_status;
			$header_lock_status['show_msg'] 	= 0;
			$header_lock_status['msg_type'] 	= 1;
			$header_lock_status['res_status'] 	= 1;
			$header_lock_status['locked_user_id']= @$headerscript_locked_user_id;
		}
		header("content-type : application/json"); 
		echo json_encode($header_lock_status);
	}
	
	public function is_lock_free_template_version()
	{
		$version_id 					= $this->input->post("versionId");		
		$template_widgets_lock_detail	= $this->template_design_model->get_version_whole_widgets_lock_details($version_id, '2');
		$template_header_script_lock 	= $this->template_design_model->get_header_script_details($version_id);		
		$is_lock_free_template = 1;
		$version_locked_users_list = array();
		foreach($template_widgets_lock_detail as $widget_locked)
		{
			$config_status 	= $widget_locked['config_locked_status'];
			$config_user_id	= $widget_locked['config_locked_userid'];
			$article_status	= $widget_locked['article_locked_status'];
			$article_user_id= $widget_locked['article_locked_userid'];
						
			if( $config_status == 2 )
			{
				$config_locked_user 		= $this->template_design_model->get_userdetails_by_id(@$config_user_id);
				$version_locked_users_list[]= $config_locked_user['Firstname']." ".$config_locked_user['Lastname']." - Advetisement Widget";
				$is_lock_free_template 		= 2;
			}
			if( $article_status == 2 &&  @$article_user_id != @$config_user_id && $config_status == 1 )
			{
				$article_locked_user 		= $this->template_design_model->get_userdetails_by_id(@$article_user_id);
				$version_locked_users_list[]= $article_locked_user['Firstname']." ".$article_locked_user['Lastname']." - Widget Add Articles";
				$is_lock_free_template 		= 2;			
			}
		}
		if($template_header_script_lock['HeaderScript_Lock_Status'] == 2)
		{
			$header_locked_user 		= $this->template_design_model->get_userdetails_by_id(@$template_header_script_lock['HeaderScript_Lock_UserId']);
			$version_locked_users_list[]= $header_locked_user['Firstname']." ".$header_locked_user['Lastname']." - Adding Header Script";
			$is_lock_free_template 		= 2;
		}
		$version_locked_users_list		= join( "<br>",array_unique($version_locked_users_list) );
		if($is_lock_free_template == 2)
		{
			$header_lock_status			= array("msg"=>"This template version is in use <br> by <br>","res_status"=>$is_lock_free_template, "show_msg"=>"1", "msg_type"=>2, "lock_status"=>$is_lock_free_template, "locked_user_list"=>$version_locked_users_list);
		}
		else
		{
			$header_lock_status			= array("msg"=>"Template version is Lock free","res_status"=>$is_lock_free_template, "show_msg"=>"0", "msg_type"=>1, "lock_status"=>$is_lock_free_template, "locked_user_list"=>$version_locked_users_list);
		}
		 
		header("content-type : application/json"); 
		echo json_encode($header_lock_status);
	}
	
	public function is_lock_free_advertisements_version()
	{
		$version_id 					= $this->input->post("versionId");		
		$template_widgets_lock_detail	= $this->template_design_model->get_version_whole_widgets_lock_details($version_id, '1');
		$template_header_script_lock 	= $this->template_design_model->get_header_script_details($version_id);		
		$is_lock_free_template = 1;
		$version_locked_users_list = array();
		foreach($template_widgets_lock_detail as $widget_locked)
		{
			$config_status 	= $widget_locked['config_locked_status'];
			$config_user_id	= $widget_locked['config_locked_userid'];						
			if( $config_status == 2 )
			{
				$config_locked_user 		= $this->template_design_model->get_userdetails_by_id(@$config_user_id);
				$version_locked_users_list[]= $config_locked_user['Firstname']." ".$config_locked_user['Lastname']." - Advetisement Widget";
				$is_lock_free_template 		= 2;
			}
		}
		if($template_header_script_lock['HeaderScript_Lock_Status'] == 2)
		{
			$header_locked_user 		= $this->template_design_model->get_userdetails_by_id(@$template_header_script_lock['HeaderScript_Lock_UserId']);
			$version_locked_users_list[]= $header_locked_user['Firstname']." ".$header_locked_user['Lastname']." - Adding Header Script";
			$is_lock_free_template 		= 2;
		}
		$version_locked_users_list		= join( "<br>",array_unique($version_locked_users_list) );
		if($is_lock_free_template == 2)
		{
			$header_lock_status			= array("msg"=>"This template version is in use <br> by <br>","res_status"=>$is_lock_free_template, "show_msg"=>"1", "msg_type"=>2, "lock_status"=>$is_lock_free_template, "locked_user_list"=>$version_locked_users_list);
		}
		else
		{
			$header_lock_status			= array("msg"=>"Template version is Lock free","res_status"=>$is_lock_free_template, "show_msg"=>"0", "msg_type"=>1, "lock_status"=>$is_lock_free_template, "locked_user_list"=>$version_locked_users_list);
		}
		 
		header("content-type : application/json"); 
		echo json_encode($header_lock_status);
	}
	
	public function custom_image_upload()
	{		
		extract($_POST);
		$result = array();
		$oldget =  getcwd();
		chdir(source_base_path.article_temp_image_path);			
		$config = array(
			'upload_path' 		=> getcwd(),
			'allowed_types' 	=> "gif|jpg|png|jpeg",
			'encrypt_name' 		=> TRUE
		);
		
		chdir($oldget);
		$this->upload->initialize($config);
		$result_data = array();
		if (!$this->upload->do_upload('imagelibrary'))
		{
			$error = array(
				'error' => $this->upload->display_errors()
			);			
			$result_data['message'] = $error['error'];
			$result_data['status'] 	= 0;
		}
		else
		{
			$data = array(
				'upload_data'		=> $this->upload->data()
			);

		ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], image_resolution);	
		$imagefile 			= $_FILES['imagelibrary']['tmp_name'];
		$caption_array 		= explode('.', $data['upload_data']['orig_name']);
		$caption 			= $caption_array[0];
		$content_type 		= 1;
		$Null_value			= "NULL";
		
		//$result 	= $this->image_model->addimages(USERID,$Null_value , $content_type,$caption, $caption,  $caption, $data['upload_data']['file_name'],0,0,0,0,1);
		$result 	= $this->template_design_model->custom_image_upload(USERID,$Null_value , $content_type,$caption, $caption,  $caption, $data['upload_data']['file_name'],0,0,0,0,1, $article_id, $instance_id, $mainSectionConfig_id);
		}		
		echo json_encode($result);
	}
	
	public function search_image_library()
	{
		extract($_POST);
		$this->session->set_userdata('image_caption',$Caption);			
		$search_image_library_result = $this->template_design_model->search_image_library($Caption);
		echo json_encode($search_image_library_result);
	}
	
	public function search_image_library_scroll()
	{		
		$data['pages']				= $this->uri->segment('4');
		$data['image_library'] 		= $this->template_design_model->search_image_library_scroll($data['pages']);
		$data['nextpages']			=  $this->uri->segment('4')+1;
		
		if(count($data['image_library']) > 0){
			echo $this->load->view('admin/custom_image_scroll', $data);
		}
		else{
			echo "";
		}
	}
	
	public function Insert_temp_from_image_library()
	{
		extract($_POST);
		if($content_id != '')
		{
			$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
			$SourceURL  		= imagelibrary_image_path;
			$DestinationURL		= article_temp_image_path;		
			$ImageDetails 		= GetImageDetailsByContentId($content_id);		
			$path 				= $ImageDetails['ImagePhysicalPath'];		
			$NewPath 			= GenerateNewImageName($path, $NewImageName);		
			ImageLibraryCopyToTemp($path,$NewPath, $SourceURL, $DestinationURL);
			$path 				= $NewPath;	
			$contenttype 		= $contentType;
			if (isset($caption))
			{			
				$temp_image_details = $this->template_design_model->Insert_temp_from_image_library($ImageDetails, $content_id, $caption, $alt, $path, $contenttype, $article_id, $instance_id, $mainSectionConfig_id );
				echo json_encode($temp_image_details);
			}
			else
			{
				echo json_encode(array(""));
			}
		}
		else
		{
			//tempImageIndex 
			$temp_image_details = $this->common_image_processing($tempImageIndex);
			echo json_encode($temp_image_details);
		}
		
	}
	
	public function custom_image_processing()
	{
		try {		
				$ImageType = $this->uri->segment('2'); 			
				if($ImageType != '') 
				{
					$data['image_path']	 = article_temp_image_path;										
						$TempImageId 			= base64_decode(urldecode($this->uri->segment('4')));
												
						$supported_image_width 	= $this->uri->segment('5');
						$supported_image_height	= $this->uri->segment('6');
						
							$viewimage = $this->template_design_model->temp_custom_image_details('', $TempImageId)->row_array();
							if(count($viewimage) > 0)
							{
								///  Do nothing 
							}
							else
							{
								////  Search id in temporary table  ////
								$viewimage = $this->template_design_model->temp_custom_image_details($TempImageId, '')->row_array();
							}
							
							$data['temp_images'] = $viewimage;
							$this->template_design_model->common_resize_all_images($viewimage);
					
							if(isset($viewimage['image_name'])) {
								
								$Tempdata['imgsrc'] 			= image_url.$data['image_path'].$viewimage['image_name'];
														
								$ImageName = $viewimage['image_name'];									
								$Image600X390 	= str_replace(".","_600_390.", $ImageName);
								$Image600X300 	= str_replace(".","_600_300.", $ImageName);
								$Image100X65 	= str_replace(".","_100_65.", $ImageName);
								$Image150X150 	= str_replace(".","_150_150.", $ImageName);
								
								$Tempdata['image_binary_file600X390']		= image_url.$data['image_path'].$Image600X390;
								$Tempdata['image_binary_file600X300']		=  image_url.$data['image_path'].$Image600X300;
								$Tempdata['image_binary_file150X150']		=  image_url.$data['image_path'].$Image150X150;
								$Tempdata['image_binary_file100X65']		=  image_url.$data['image_path'].$Image100X65;
								
								$Tempdata['imgtype600X390']		= $viewimage['image1_type'];
								$Tempdata['imgtype600X300']		= $viewimage['image2_type'];
								$Tempdata['imgtype100X65']		= $viewimage['image3_type'];
								$Tempdata['imgtype150X150']		= $viewimage['image4_type'];
								
								$Tempdata['imagenames'] 		= $viewimage['image_name'];
								$Tempdata['imageid'] 			= $viewimage['imageid'];
								$Tempdata['image_caption'] 		= $viewimage['caption'];
								$Tempdata['image_alt'] 			= $viewimage['alt_tag'];
								$Tempdata['imagecontent_id'] 	= $viewimage['imagecontent_id'];
								
								$data['last_crop_image'] = $Tempdata;
								
								$data['supported_image_width'] 	= $supported_image_width;
								$data['supported_image_height'] = $supported_image_height;
								
								$data['title']		 	= 'Edit Custom Image';
								$data['page_name'] 		= 'add_image';
								$data['template'] 		= 'edit_custom_image';
								$this->load->view('admin_template', $data);
							
							} else {
								redirect(folder_name.'/');
							}
				
				} else {
					redirect(folder_name.'/');
				}
			
			} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
	}
	
	public function crop_custom_image()
	{
		try
		{
			extract($_POST);			
			$data = '';
			if (!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->template_design_model->temp_custom_image_details($crop_image_id, '')->row_array();
			
			if (isset($ImageDetails['image_name']))
			{
				$image_src_path		= '';
				$image_src_path		= source_base_path.article_temp_image_path;
				$source 			= image_url.article_temp_image_path;
				$src  				= $image_src_path. $ImageDetails['image_name'];
				
					switch($image_type) {
						case "image_600_390":
							$dst 		= $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
					case "image_600_300":
							$dst 		= $image_src_path. str_replace(".","_600_300.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_300.",$ImageDetails['image_name']);
					break;
					
					case "image_100_65":
							$dst 		= $image_src_path. str_replace(".","_100_65.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_100_65.",$ImageDetails['image_name']);
					break;
					
					case "image_150_150":
							$dst 		= $image_src_path. str_replace(".","_150_150.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_150_150.",$ImageDetails['image_name']);
					break;
					
					default:
							$dst 		= $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
				}
	
				
				$ImageDetails 				= getimagesize($src);
				
				$ImageExtension = explode("/",$ImageDetails['mime']);
				$extType 		= strtolower($ImageExtension[1]);
				
				if (!empty($src) && !empty($dst) && !empty($data))
				{
					switch ($extType)
					{
					case 'gif':
						$src_img = imagecreatefromgif($src);
						break;

					case 'jpg':
						$src_img = imagecreatefromjpeg($src);
						break;
						
					case 'jpeg':
						$src_img = imagecreatefromjpeg($src);
						break;

					case 'png':
						$src_img = imagecreatefrompng($src);
						break;
					}
					if (!$src_img)
					{
						$result_value['status'] = 'error';
						$result_value['msg'] 	= "Failed to read the image file";
						return json_encode($result_value);
					}
					$size 		= getimagesize($src);
					$size_w 	= $size[0]; // natural width
					$size_h 	= $size[1]; // natural height
					$src_img_w 	= $size_w;
					$src_img_h 	= $size_h;
					$degrees 	= $data->rotate;
					// Rotate the source image
					if (is_numeric($degrees) && $degrees != 0)
					{
						// PHP's degrees is opposite to CSS's degrees
						$new_img 	= imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));
						imagedestroy($src_img);
						$src_img 	= $new_img;
						$deg 		= abs($degrees) % 180;
						$arc 		= ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
						$src_img_w 	= $size_w * cos($arc) + $size_h * sin($arc);
						$src_img_h 	= $size_w * sin($arc) + $size_h * cos($arc);
						// Fix rotated image miss 1px issue when degrees < 0
						$src_img_w-= 1;
						$src_img_h-= 1;
					}
					$tmp_img_w 	= $data->width;
					$tmp_img_h 	= $data->height;
					$dst_img_w 	= $crop_width;
					$dst_img_h 	= $crop_height;
					$src_x 		= $data->x;
					$src_y 		= $data->y;
					
					if ($src_x <= - $tmp_img_w || $src_x > $src_img_w)
					{
						$src_x = $src_w = $dst_x = $dst_w = 0;
					}
					else if ($src_x <= 0)
					{
						$dst_x = - $src_x;
						$src_x = 0;
						$src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
					}
					else if ($src_x <= $src_img_w)
					{
						$dst_x = 0;
						$src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
					}
					if ($src_w <= 0 || $src_y <= - $tmp_img_h || $src_y > $src_img_h)
					{
						$src_y = $src_h = $dst_y = $dst_h = 0;
					}
					else if ($src_y <= 0)
					{
						$dst_y = - $src_y;
						$src_y = 0;
						$src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
					}
					else if ($src_y <= $src_img_h)
					{
						$dst_y = 0;
						$src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
					}
					// Scale to destination position and size
					$ratio = $tmp_img_w / $dst_img_w;
					$dst_x/= $ratio;
					$dst_y/= $ratio;
					$dst_w/= $ratio;
					$dst_h/= $ratio;
					$dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
					// Add transparent background to destination image
					imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
					imagesavealpha($dst_img, true);
					$result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					if ($result)
					{
						if (!imagejpeg($dst_img, $dst))
						{
							$result_value['status'] = 'error';
							$result_value['msg'] 	= "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg'] 	= "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
						$ImageDetails 	= getimagesize($dst);
						$width 			= $ImageDetails[0];
						$height 		= $ImageDetails[1];
						$size 			= $ImageDetails['bits'];
						$type 			= $ImageDetails['mime'];
						$Imagedata  = '';
						$modifiedon 	= date('Y-m-d H:i:s');
						
						
						$Null_value 		= 'NULL';
						$PathArray 			= explode('/', $dst);
						$TempPath 			= end($PathArray);

						$content_id = 'NULL';
						
						switch($image_type) {
					case "image_600_390":
							
							$image_type_1_value = 2;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
						
					break;
					
					case "image_600_300":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 2;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
					break;
					
					case "image_100_65":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = 2;
							$image_type_4_value = $Null_value;
							
					break;
					
					case "image_150_150":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = 2;
							
					break;
					
					default:
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 2;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
								
					break;
						
						}
					
					$this->template_design_model->update_custom_crop_image( $content_id, $crop_caption, $crop_alt, $image_type_1_value,$image_type_2_value,$image_type_3_value,$image_type_4_value, $modifiedon, $crop_image_id, $image_type, '0');
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
					$viewimage 				= $this->template_design_model->temp_custom_image_details($crop_image_id, '')->row_array();
					if(isset($viewimage['image_binary_file'])) 
					{	
					
					$result_value['image1_type']	= $viewimage['image1_type'];
					$result_value['image2_type']	= $viewimage['image2_type'];
					$result_value['image3_type']	= $viewimage['image3_type'];
					$result_value['image4_type']	= $viewimage['image4_type'];
					}
							
					$result_value['status'] 	= 'success';
					$result_value['msg'] 		= "Successfully cropped the image";
					$result_value['source'] 	= $source;
					$result_value['imageid'] 	= $crop_image_id;
					echo json_encode($result_value);
				}
			}
			else
			{
				$result_value['status'] 	= 'error';
				$result_value['msg'] 		= "Invalid Image";
				echo json_encode($result_value);
			}
		}
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg'] 	= 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	}
	
	public function resize_custom_image() {
	
	try
		{

			extract($_POST);
			$data = '';
			if (!empty($crop_data))
			{
				$data = json_decode(stripslashes($crop_data));
			}
			$ImageDetails = $this->template_design_model->temp_custom_image_details($crop_image_id, '')->row_array();
			
			if (isset($ImageDetails['image_name']))
			{
				
				$image_src_path	= source_base_path.article_temp_image_path;
				$src  			= $image_src_path. $ImageDetails['image_name'];
				$source 		= image_url.article_temp_image_path;	
				
					switch($image_type) {
						case "image_600_390":
							$dst 		=  $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
					case "image_600_300":
							$dst 		=  $image_src_path. str_replace(".","_600_300.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_300.",$ImageDetails['image_name']);
					break;
					
					case "image_100_65":
							$dst 		=  $image_src_path. str_replace(".","_100_65.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_100_65.",$ImageDetails['image_name']);
					break;
					
					case "image_150_150":
							$dst 		=  $image_src_path. str_replace(".","_150_150.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_150_150.",$ImageDetails['image_name']);
					break;
					
					default:
							$dst 		=  $image_src_path. str_replace(".","_600_390.",$ImageDetails['image_name']);
							$source = $source.str_replace(".","_600_390.",$ImageDetails['image_name']);
					break;
					
				}
				
				
							
				$ImageDetails 				= getimagesize($src);
				
				$ImageExtension = explode("/",$ImageDetails['mime']);
				$extType 		= strtolower($ImageExtension[1]);
			
				
				if (!empty($src) && !empty($dst) && !empty($data))
				{
					switch ($extType)
					{
					case 'gif':
						$src_img = imagecreatefromgif($src);
						break;

					case 'jpg':
						$src_img = imagecreatefromjpeg($src);
						break;
						
					case 'jpeg':
						$src_img = imagecreatefromjpeg($src);
					break;

					case 'png':
						$src_img = imagecreatefrompng($src);
						break;
					}
					if (!$src_img)
					{
						$result_value['status'] = 'error';
						$result_value['msg'] 	= "Failed to read the image file";
						return json_encode($result_value);
					}
					$size 		= getimagesize($src);
					$src_w 	= $size[0]; // natural width
					$src_h	= $size[1]; // natural height	
					
					$dst_w 	= $crop_width;
					$dst_h 	= $crop_height;
					
					// Load
					
					$dst_img  = imagecreatetruecolor($dst_w, $dst_h);
					
			
					//$result = imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
					$source_ratio = $src_w / $src_h; 
					   $destination_ratio = $dst_w / $dst_h; 
					
					  // crop to fit 
					  if ($source_ratio > $destination_ratio) { 
					   // source has a wider ratio 
					   $temp_width = (int)($src_h * $destination_ratio); 
					   $temp_height = $src_h; 
					   $source_x = (int)(($src_w - $temp_width) / 2); 
					   $source_y = 0; 
					  } else { 
					   // source has a taller ratio 
					   $temp_width = $src_w; 
					   $temp_height = (int)($src_w / $destination_ratio); 
					   $source_x = 0; 
					   $source_y = (int)(($src_h - $temp_height) / 2); 
					  } 
					  $destination_x = 0; 
					  $destination_y = 0; 
					  $source_width = $temp_width; 
					  $source_height = $temp_height; 
					  $new_destination_width = $dst_w; 
					  $new_destination_height = $dst_h; 
					  
					 
					  $result =  imagecopyresampled($dst_img, $src_img, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height);
					if ($result)
					{
						if (!imagejpeg($dst_img, $dst))
						{
							$result_value['status'] = 'error';
							$result_value['msg'] 	= "Failed to save the cropped image file";
							echo json_encode($result_value);
						}
					}
					else
					{
						$result_value['status'] = 'error';
						$result_value['msg'] 	= "Failed to crop the image file";
						echo json_encode($result_value);
					}
					
						$ImageDetails 	= getimagesize($dst);
						$width 			= $ImageDetails[0];
						$height 		= $ImageDetails[1];
						$size 			= $ImageDetails['bits'];
						$type 			= $ImageDetails['mime'];
						$Imagedata 		= '';
						$modifiedon 	= date('Y-m-d H:i:s');
						
						
						$Null_value 		= 'NULL';
						$contenttype 		= 'ImageLibrary';
						$PathArray 			= explode('/', $dst);
						$TempPath 			= end($PathArray);
		
						$content_id = 'NULL';
						
						switch($image_type) {
					case "image_600_390":
												
							$image_type_1_value = 1;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
					break;
					
					case "image_600_300":
							
							$image_type_1_value = $Null_value;
							$image_type_2_value = 1;
							$image_type_3_value = $Null_value;
							$image_type_4_value = $Null_value;
							
					break;
					
					case "image_100_65":

							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = 1;
							$image_type_4_value = $Null_value;
							
					break;
					
					case "image_150_150":

							$image_type_1_value = $Null_value;
							$image_type_2_value = $Null_value;
							$image_type_3_value = $Null_value;
							$image_type_4_value = 1;
							
					break;
					
					default:
						$image_type_1_value = $Null_value;
						$image_type_2_value = 1;
						$image_type_3_value = $Null_value;
						$image_type_4_value = $Null_value;
					break;
						
						}
					
					$this->template_design_model->update_custom_crop_image( $content_id, $crop_caption, $crop_alt, $image_type_1_value,$image_type_2_value,$image_type_3_value,$image_type_4_value, $modifiedon, $crop_image_id, $image_type, '0');
					
					imagedestroy($src_img);
					imagedestroy($dst_img);
					
								
					$viewimage 				= $this->template_design_model->temp_custom_image_details($crop_image_id, '')->row_array();
					
					if(isset($viewimage['image_binary_file'])) 
					{	
					
					$result_value['image1_type']	= $viewimage['image1_type'];
					$result_value['image2_type']	= $viewimage['image2_type'];
					$result_value['image3_type']	= $viewimage['image3_type'];
					$result_value['image4_type']	= $viewimage['image4_type'];
					}
					
					$result_value['status'] 	= 'success';
					$result_value['msg'] 		= "Successfully cropped the image";
					$result_value['source'] 	= $source;
					$result_value['imageid'] 	= $crop_image_id;
					echo json_encode($result_value);
				}
			}
			else
			{
				$result_value['status'] 	= 'error';
				$result_value['msg'] 		= "Invalid Image";
				echo json_encode($result_value);
			}
		}
		catch(Exception $e)
		{
			$result_value['status'] = 'error';
			$result_value['msg'] 	= 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	
	}

	public function update_custom_image_changes()
	{
		/* Update the -- table save status to '1' */
		$temp_image_id 				= $this->input->post('tempimageid');
		$update_img 				= $this->input->post('update_img');
		$physical_name				= $this->input->post('physical_name');
		$content_id					= $this->input->post('content_id');
		
		$temp_image_save_details	= $this->template_design_model->temp_custom_image_details($temp_image_id, '')->row_array();
		if(count($temp_image_save_details) > 0)
		{
			if($update_img == 'save')
			{
				$update_save_status = ($temp_image_save_details['save_status'] == 0) ? '2' : '1';
				$this->template_design_model->update_custom_crop_image( '', '', '', '', '', '', '', '', $temp_image_id, '', $update_save_status );
			}			
			elseif($update_img == 'cancel')
			{
				
				$content_type 		= $temp_image_save_details['contenttype'];
				$imagecontent_id 	= $temp_image_save_details['imagecontent_id'];
				$image_name 		= explode(".",$temp_image_save_details['image_name']);				
				$image_name 		= $image_name[0];
				if(isset($content_type)) {
					
					if($imagecontent_id != ''){
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= article_temp_image_path;		
						$ImageDetails 		= GetImageDetailsByContentId($imagecontent_id);		
						$path 				= $ImageDetails['ImagePhysicalPath'];								
						ImageLibraryCopyToTemp($path,$temp_image_save_details['image_name'], $SourceURL, $DestinationURL);
						
						$path				= $temp_image_save_details['image_name'];
						$this->template_design_model->Insert_temp_from_image_library($ImageDetails, $imagecontent_id, $temp_image_save_details['caption'], $temp_image_save_details['alt_tag'], $path, $temp_image_save_details['contenttype'], $temp_image_save_details['Articlecontent_id'], $temp_image_save_details['WidgetInstance_id'], $temp_image_save_details['WidgetInstanceMainSection_id'] );
						
						$this->template_design_model->update_custom_crop_image( '', '', '', '', '', '', '', '', $temp_image_id, '', '1' );
						
					}
					else
					{
						$this->template_design_model->update_custom_crop_image( '', '', '', '0', '0', '0', '0', '', $temp_image_id, 'cancel', '1' );
						
						$viewimage 			= $this->template_design_model->temp_custom_image_details($temp_image_id, '')->row_array();						
						$this->template_design_model->common_resize_all_images_again($viewimage);
					}
					
					$msg		= "Custom image changes are cancelled";			
					$msg_type 	= 1;
					$show_msg 	= 1;
					$res_status = 1;
					
				} else {
					$msg		= "Internal server error while cancelling changes";			
					$msg_type 	= 2;
					$show_msg 	= 1;
					$res_status = 2;
				}
		
			}
		}
		 
		if(mysql_error() == ''){
			if($update_img != 'cancel')
			{
				$msg		= "Images saved successfully";			
				$msg_type 	= 1;
				$show_msg 	= 1;
				$res_status = 1;
			}
			else
			{
				$msg		= "Custom image changes are cancelled";
				$msg_type 	= 1;
				$show_msg 	= 1;
				$res_status = 1;
			}
		}
		else{
			$msg		= "Internal server error";
			$msg_type 	= 2;
			$show_msg 	= 1;
			$res_status = 0;
		}
		$emsg = array("msg"=>$msg, "msg_type"=>$msg_type, "show_msg"=>$show_msg, "res_status"=>$res_status);				
		header("content-type: application/json"); 
		echo json_encode($emsg);
	}

	public function check_custom_image_name() {
			extract($_POST);
					 
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
			
		create_image_folder( $Year, $Month, $Day);
		
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		
		$temp_image = $this->template_design_model->temp_custom_image_details($temp_id, '')->row_array();

		$ArrayPhysical = explode('.',$physical_name);
		
		if((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ( $temp_image['save_status'] == 2 ) || (  trim($temp_image['caption']) != trim($caption) || trim($temp_image['alt_tag']) != trim($alt) || trim($temp_image['physical_name']) != trim($ArrayPhysical[0]) ) )	{


			if (file_exists(source_base_path . imagelibrary_image_path . $FolderMapping .trim($physical_name))) {
					$data['status'] = 'false';
				} else  {
					$data['status'] = 'true';
				}
				
		} else {
				$data['status'] = 'true';
		}
		
			echo json_encode($data);
		}
		
	public function delete_temp_custom_image($temp_table_image_name, $temp_image_id)
	{
		$TempSourceURL		= article_temp_image_path;
		/* Delete existed temporary images */
		DeleteTempImage($temp_table_image_name,$temp_image_id, $TempSourceURL);
		$this->template_design_model->delete_temp_custom_image($temp_image_id);
	}
	
	public function release_locks_by_user_id()
	{
		$result = $this->template_design_model->release_locks_by_user_id();
		echo json_encode($result);
	}

	public function get_not_saved_version($page_id)
	{
		$not_saved_version 						= array('Version_Id'=>'', 'Total_Versions'=> 0);
		$template_version_details 				= $this->get_template_versions($page_id,'');			
		$not_saved_version['Total_Versions'] 	= count($template_version_details);
		
		if($not_saved_version['Total_Versions'] > 0)
		{
			foreach($template_version_details as $template_version)
			{			
				if($template_version['Not_Saved_user_id'] != '')
				{
					$not_saved_version['Version_Id'] = $template_version['Version_Id'];
					break;
				}
			}
		}
		return $not_saved_version;
	}
	public function common_image_processing($temporary_image_table_id) 
	{		

		$data['image_path']	 	= article_temp_image_path;				
		$TempImageId 			= $temporary_image_table_id;

		$viewimage 				= $this->template_design_model->temp_custom_image_details($TempImageId, '')->row_array();
		$data['temp_images'] 	= $viewimage;
		$this->template_design_model->common_resize_all_images($viewimage);

		if(isset($viewimage['image_name'])) {
			
			$Tempdata['imgsrc'] 			= image_url.$data['image_path'].$viewimage['image_name'];
									
			$ImageName = $viewimage['image_name'];									
			$Image600X390 	= str_replace(".","_600_390.", $ImageName);
			$Image600X300 	= str_replace(".","_600_300.", $ImageName);
			$Image100X65 	= str_replace(".","_100_65.", $ImageName);
			$Image150X150 	= str_replace(".","_150_150.", $ImageName);
			
			$Tempdata['image_binary_file600X390']		= image_url.$data['image_path'].$Image600X390;
			$Tempdata['image_binary_file600X300']		=  image_url.$data['image_path'].$Image600X300;
			$Tempdata['image_binary_file150X150']		=  image_url.$data['image_path'].$Image150X150;
			$Tempdata['image_binary_file100X65']		=  image_url.$data['image_path'].$Image100X65;
			
			$Tempdata['imgtype600X390']		= $viewimage['image1_type'];
			$Tempdata['imgtype600X300']		= $viewimage['image2_type'];
			$Tempdata['imgtype100X65']		= $viewimage['image3_type'];
			$Tempdata['imgtype150X150']		= $viewimage['image4_type'];
			
			$Tempdata['imagenames'] 		= $viewimage['image_name'];
			$Tempdata['imageid'] 			= $viewimage['imageid'];
			$Tempdata['image_caption'] 		= $viewimage['caption'];
			$Tempdata['image_alt'] 			= $viewimage['alt_tag'];
			$Tempdata['imagecontent_id'] 	= $viewimage['imagecontent_id'];
			
			$data['image_id'] 	= $TempImageId;
			$data['source'] 	= image_url.article_temp_image_path;
			
			$Physical_extension_array = explode(".",$viewimage['image_name']);
			
			$data['caption'] 	= $viewimage['caption'];
			$data['alt'] 		= $viewimage['alt_tag'];
			
			$data['physical_name'] 		= $viewimage['physical_name'];
			$data['physical_extension'] = $Physical_extension_array[1];
			
			$data['imagecontent_id'] 		= $viewimage['imagecontent_id'];
			$data['image1_type'] = $viewimage['image1_type'];
			$data['image2_type'] = $viewimage['image2_type'];
			$data['image3_type'] = $viewimage['image3_type'];
			$data['image4_type'] = $viewimage['image4_type'];
		} else {
			$data = "";
		}
			return $data;
		
	}

	public function copy_widget_instance()
	{				
		$widget_post				= $this->input->post();
		$reference_widget_instance 	= $widget_post["reference_widget_instance"];
		$clone_reference_id			= $widget_post["clone_reference_id"];
		$widget_instance 	= array('Pagesection_id' => $widget_post['sectionId'], 'Page_type' => $widget_post['pageType'],'Container_ID' => $widget_post['containerId'], 'Widget_id' => $widget_post['widgetId'], 'WidgetDisplayOrder' => $widget_post['widgetOrderIdInContainer'], 'page_id'=>$widget_post['page_id'], "version_id" =>$widget_post['versionId'] );
		
		$instanceId 					= $this->template_design_model->copy_WidgetInstance($widget_instance, $reference_widget_instance, $clone_reference_id); 		
		$_POST['widget_instance_id'] 	= $instanceId;
		
		//Preparing details for update the widget configuration
		$_POST["widget_mainsection_id_extra"] 	= "--";
		$_POST["widget_subsection_id_extra"] 	= "--";		
		$widget_rendering_type = $this->input->post("widget_rendering_type");
		if($widget_rendering_type != 3){
			//Call widget configuration function
			$this->save_widget_config("from_copy_widget");
		}
		$instance_id = array('widget_instance_id' => $instanceId);
		header("content-type : application/json");
		echo json_encode($instance_id);
	}
	public function release_widget_article_lock()
	{	
		$widget_instance_id  	= $this->input->post("widgetinstance_id");		
		$update_lock_status		= $this->input->post("update_lock_status");		
		$lock_label				= $this->input->post("lock_label");
		
		$_POST['return_type'] 	= "php"; // for lock_widget_articles()
		$_POST['from_release_article_lock'] = "from_release_article_lock"; // for lock_widget_articles()
		$result 				= "";	
		if($widget_instance_id != ''){			
			$lock_details = $this->lock_widget_articles();				
			if($update_lock_status == 1){
				$this->lock_widget_articles_by_instanceid($widget_instance_id);						
				$lock_details['msg'] 		= ($lock_details['res_status'] == 1) ? "Unlocked successfully" : $lock_details['msg']; //Lock released
				$lock_details['show_msg']	= "1";
				$result 			 		= $lock_details;
			}else if($update_lock_status == 2){							
				if($lock_label == "Release Lock"){
					$this->lock_widget_articles_by_instanceid($widget_instance_id);
					$lock_details = $this->lock_widget_articles();	
					$lock_details['res_status'] = 1;
					$lock_details['msg_type'] = 1;
				}
				$lock_details['msg'] 		= ($lock_details['res_status'] != 1) ? $lock_details['msg'] : (($lock_label != "Release Lock") ? "Locked successfully" : "Lock released successfully"); //Locked by current user
				$lock_details['show_msg']	= "1";
				$result 			 		=	$lock_details;
			}
			
			header("content-type : application/json");
			echo json_encode($result);
		}
	}
	
	public function clone_widget_instance()
	{
		$cloned_widget_instance = $this->input->post("cloned_widget_instance");
		$cloned_widget_id		= $this->input->post("cloned_widget_id");
		
		//Create clone widget list
		$result = $this->template_design_model->clone_widget_instance($cloned_widget_instance, $cloned_widget_id);
		header("content-type : application/json");
		echo json_encode($result);
	}
	public function get_session_details()
	{
		$result = $this->session->userdata;
		header("content-type : application/json");
		echo json_encode($result);
	}
	
	public function verify_clone_mapping(){		
		$clone_instance_id 	= $this->input->post("clone_instance_id");
		$called_from 		= $this->input->post("called_from");
		$result				= array('clone_instance_id' => $clone_instance_id, 'map_count' => 0,'res_status' => 0, 'show_msg' => 0, 'msg' => "", "msg_type"=>"");
		
		//check whether this clone widget instance stataus is active in 'cloned_widget_instances' table
		$is_active_clone 		= $this->template_design_model->get_cloned_instance_details($clone_instance_id);
		if($is_active_clone['status'] == 1){
			if($called_from == 'create'){
				$result				= array('clone_instance_id' => $clone_instance_id, 'map_count' => 0,'res_status' => 1, 'show_msg' => 0, 'msg' => "Parent clone widget is not deleted", "msg_type"=>"");
			}else{
				$clone_map_details_cms 	= $this->template_design_model->get_clone_mapping_details($clone_instance_id);
				$clone_map_details_live = $this->template_design_model->get_frontend_clone_mapping_details($clone_instance_id);
				
				$clone_map_details = (count($clone_map_details_cms) + count($clone_map_details_live));
				if($clone_map_details > 0){
					$result	= array('clone_instance_id' => $clone_instance_id, 'map_count' => $clone_map_details,'res_status' => 1, 'show_msg' => 1, 'msg' => "You can't ".$called_from." this widget, <br>This parent clone widget is mapped in other template(s).<br>Delete cloned child widgets  in mapped templates (publish if template is LIVE version template) and try again.", "msg_type"=>"2");
				}
			}
			
		}else{
			$result	= array('clone_instance_id' => $clone_instance_id, 'map_count' => 0,'res_status' => 0, 'show_msg' => 1, 'msg' => "You can't ".$called_from." this widget, This parent clone widget was deleted in 'Cloned widgets Template'", "msg_type"=>"2");
		}
		header("content-type : application/json");
		echo json_encode($result);
	}
	public function restore_last_updated_design(){
		$page_id 		= $this->input->post("pageId");
		$version_id		= $this->input->post("version_id");
		
		$emsg 			= array("msg"=>"Failed to restore template design", "msg_type"=>2, "show_msg"=>1, "res_status"=>"2");				

		$restore_last_updated_template = $this->template_design_model->restore_last_updated_design($page_id, $version_id);
		if($restore_last_updated_template){			
			
			$save_status 	= $this->input->post("saveTemplateStatus"); ////  1->Save widget instances, 2->Delete widget instances  ///////
			$this->delete_temporary_instance();
			
			$emsg = array("msg"=>"Last updated template design restored successfully", "msg_type"=>1, "show_msg"=>1, "res_status"=>"1");				
		}
		header("content-type : application/json");
		echo json_encode($emsg);		
	}
	
	public function dynamic_templates_containers()
	{
		$_POST['return_type'] = "php";
		$template_obj	= $this->dynamic_templates();
		$container_obj	= $this->dynamic_containers();
		$categories		= $this->section_group_json();
		$widgets		= $this->show_allwidgets();
		$return_obj		= array("templ" => $template_obj, "container_obj" => $container_obj, "categories" => $categories, "all_active_widgets" => $widgets );
		header("content-type: application/json");
		echo json_encode($return_obj); exit;
	}
	
}
?>