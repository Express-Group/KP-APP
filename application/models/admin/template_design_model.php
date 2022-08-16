<?php
class  Template_design_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();	
		$CI 			= &get_instance();
		$this->live_db 	= $this->load->database('live_db', TRUE);
	}
	
	////////////  Update or insert Template XML content into the page_master table  ///////
	public function update_pagemaster($data, $pageId, $publish_xml, $user_id, $use_common_header, $use_common_rightpanel, $use_common_footer, $commit_status, $version_id, $section_id, $page_type, $use_parent_section_template)
	{
		$this->db->trans_begin();
		$date_time 	= date('Y-m-d H:i:s', time());
		$result 	= $this->db->query("CALL `update_pagemaster`('". $pageId ."','".$data['hasTemplate'] ."','".addslashes($data['templatexml']) ."','".$data['templateid'] ."', '".$publish_xml."', '".$use_common_header."','".$use_common_rightpanel."', '".$use_common_footer."','".$commit_status."','".USERID."', '".$date_time."', '".$use_parent_section_template."') ");		

		if($publish_xml == "publish"){
			$this->live_db->trans_begin();			
			////  Update LIVE table with version Id  //////
			////////  For Live Site Update or Insert widgetInstance_live, MainsectionConfig_live, SubsectionConfig_live table  /////
			$result = $this->live_db->query("CALL `update_pagemaster`('". $pageId ."','".$data['hasTemplate'] ."','".addslashes($data['templatexml']) ."','".$data['templateid'] ."', '".$publish_xml."', '".$use_common_header."','".$use_common_rightpanel."' , '".$use_common_footer."','".$commit_status."','".$version_id."','".$section_id."','".$page_type."','".addslashes($data['header_adscript'])."','".addslashes($data['publishing_widget_instances'])."','".addslashes($data['publishing_mainConfig'])."','".addslashes($data['publishing_instance_articles'])."', '".$use_parent_section_template."') ");
			
			$inc				= 1;
			$all_instances		= "";
			$get_all_instances 	= $this->get_adv_instance_by_versionId($version_id, "");// =1->only advertisement instances, ""->All widget instances			
			foreach($get_all_instances as $individual_instance){
				if((count($get_all_instances)) > $inc ){
					$all_instances = $individual_instance['WidgetInstance_id'].",".$all_instances;
				}
				$inc ++;
			}
			$all_instances = substr($all_instances, 0,-1);
			// Update the value of `is_changes_are_published` in widgetinstance table
			$this->change_ischanges_published($all_instances, 0); //1->Changes made not published

		}
		if ($this->db->trans_status() === FALSE && $publish_xml != "publish") {
				$this->db->trans_rollback();
				$emsg	= ($commit_status == 3)? array("msg"=>"Failed to create new version template", "msg_type"=>2, "show_msg"=>1, "trigger_tree"=>"", "res_status"=>"2") : array("msg"=>"Failed to update current version template", "msg_type"=>2, "show_msg"=>1, "trigger_tree"=>"", "res_status"=>"2");
		}else if ( ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) && $publish_xml == "publish") {
				$this->db->trans_rollback();			
				$this->live_db->trans_rollback();
				$emsg	= array("msg"=>"Template failed to publish", "msg_type"=>2, "show_msg"=>1, "trigger_tree"=>"");						
			
		}else {
			$this->db->trans_commit();
			if($publish_xml == "publish"){
				$this->live_db->trans_commit();
				$emsg	= array("msg"=>"Template published successfully.", "msg_type"=>1, "show_msg"=>1, "trigger_tree"=>"", "res_status"=>"1");
			}else{
				$emsg	= ($commit_status == 3)? array("msg"=>"New version template is created successfully.", "msg_type"=>1, "show_msg"=>1, "res_status"=>"1") : array("msg"=>"updated temporarily", "msg_type"=>1, "show_msg"=>0, "trigger_tree"=>"", "res_status"=>"1");
			}
		}
		return $emsg;
	}
	
	public function make_empty_template_version($data, $pageId, $publish_xml, $user_id, $use_common_header, $use_common_rightpanel, $use_common_footer, $commit_status, $version_id, $section_id, $page_type, $use_parent_section_template)
	{
		$this->db->trans_begin();
		$date_time = date('Y-m-d H:i:s', time());
		$result = $this->db->query("CALL `make_empty_template_version`('". $pageId ."','".$data['hasTemplate'] ."','".addslashes($data['templatexml']) ."','".$data['templateid'] ."', '".$publish_xml."', '".$use_common_header."','".$use_common_rightpanel."', '".$use_common_footer."','".$commit_status."','".USERID."', '".$date_time."', '".$use_parent_section_template."') ");
		
		if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$emsg	= array("msg"=>"Failed to create empty template version", "msg_type"=>2, "show_msg"=>1, "trigger_tree"=>"");
		}		
		else {
			$this->db->trans_commit();			
			$emsg	= array("msg"=>"New empty template version is created successfully", "msg_type"=>1, "show_msg"=>1, "trigger_tree"=>"");
		}
		return $emsg;
	}
	
	////////////  Update or insert Template XML content into the page_master table  ///////
	public function update_pagemaster_header_script($script_text, $id, $version_id)
	{		
		$result = $this->db->query("CALL `update_pagemaster_header_script`('". $id ."','".addslashes($script_text)."', '". $version_id ."', '". USERID ."') ");				
		if(mysql_error() == '')
			$emsg	= array("msg" => "Header advertisement script saved successfully", "msg_type" => 1);		
		else
			$emsg	= array("msg" => "Failed to save script", "msg_type" => 2);
		
		return $emsg;
	}
	
	//////////////  Get home page section details from section master  //////
	public function get_homePage_xml(){
		$home_page_section = $this->db->query("CALL get_homePage_template_versionId()");	
		$home_page_details = $home_page_section->row_array();
		return $home_page_details;
	}
	
	//////////////  Retrieve the saved template XML content from page_master table  ////////
	public function get_template_xmlcontent($pageId, $data)
	{	
		$result 	 = $this->db->query("CALL get_xmlpage_details('". $pageId."')");
		
		if($data == "xmldata")
		{
			foreach ($result->result() as $row)
			{			
			  $xml_content = $row->templatexml;		  
			}		
		}
		else
		{
			$xml_content = $result->row_array();
		}
		return  $xml_content;
		
	}
	 
	//////////  Retrieve all available widgets from widget_master table  ///////
	public function get_allwidgets($status)
	{
		$result  = $this->db->query("CALL get_allwidgets('".$status."', '')");	
			
		if($result)
		{			
			$widgets['widgetHolder']	= $result->result_array();
			$widgets['status']			= true;
			$widgets['serviceMessage']	= "Successful";
		}
		else
		{
			$widgets['widgetHolder']	= "";
			$widgets['status']			= false;
			$widgets['serviceMessage']	= "Fail";
		}
		return $widgets;
	}
	
	/////////  Retrieve all available page templates from template_master  ///////
	public function get_pagetemplates($status)
	{
		$result  = $this->db->query("CALL get_pagetemplates('".$status."', '')");		
		return $result->result_array();
	}
	
	/////////  Retrieve all available page templates from container_master  ///////
	public function get_containers($status)
	{	
		$result  = $this->db->query("CALL get_containers('".$status."', '')");
		return $result->result_array();
	}
	
	////////////// Get all available menus / Sections from sectionmaster table   //////////
	public function multiple_section_mapping()
	{		
		$empty_val = '';		  
		$list_multi_sectn 	= $this->db->query('CALL get_section_template_designer("'.$empty_val.'")');		
		$get_result 		= $list_multi_sectn->result_array();
		
		foreach($get_result as $key => $get_multi_section)
		{
			
			$get_sec_id 					= $get_multi_section['Section_id'];
			$page_details 					= $this->db->query('CALL get_pagemaster_sections("'.$get_sec_id.'")');			
			$get_multi_section['page_det'] 	= $page_details->result_array();			
			if(count($get_multi_section['page_det']) == 0 || $get_multi_section['Section_landing'] > count($get_multi_section['page_det']))
			{				
				$this->create_page($get_sec_id, 2);
			}
			
			//////  For insert / create article page to Parent Sections  /////
			if(count($get_multi_section['page_det']) == 1)
			{
				$this->create_page($get_sec_id, 3);
			}
			$list_multi_sectn 					= $this->db->query('CALL get_section_template_designer("'.$get_sec_id.'")');					
			$get_multi_section['sub_section'] 	= $list_multi_sectn->result_array();
			
			foreach($get_multi_section['sub_section'] as $subkey => $subsection_page)
			{
				
				$get_subsec_id 					= $subsection_page['Section_id'];
				$subsec_page_details 			= $this->db->query('CALL get_pagemaster_sections("'.$get_subsec_id.'")');
				$subsection_page['page_det'] 	= $subsec_page_details->result_array();
				
				if(count($subsection_page['page_det']) == 0  || $subsection_page['Section_landing'] > count($subsection_page['page_det']))
				{					
					$this->create_page($get_subsec_id, 2);
				}
				
				//////  For insert / create article page to Parent Sections  /////
				if(count($subsection_page['page_det']) == 1)
				{
					$this->create_page($get_subsec_id, 3);
				}
				
				///// Is Special Menu /// 
				
				$special_section_details		= $this->db->query('CALL get_seperatemenu ("'.$subsection_page['IsSeperateWebsite'].'", "'.$get_subsec_id.'")')->result_array();
				$specila_list = array();
				foreach($special_section_details as $splkey => $special_section)
				{
					if($special_section['Status'] == 1){
					
						$get_splsec_id 					= $special_section['Section_id']; 
						$splsec_page_details 			= $this->db->query('CALL get_pagemaster_sections("'.$get_splsec_id.'")');
						
						$special_section['page_det'] 	= $splsec_page_details->result_array();
						
						if(count($special_section['page_det']) == 0  || $special_section['Section_landing'] > count($special_section['page_det']))
						{
							$this->create_page($get_splsec_id, 2);
						}
						
						//////  For insert / create article page to Parent Sections  /////
						if(count($special_section['page_det']) == 1)
						{
							$this->create_page($get_splsec_id, 3);
						}
						
						///////  Add Specialsection to main Subsection array object  /////
						$specila_list[$splkey] = $special_section;
					}
						
				}				
				$subsection_page['special_section'] = $specila_list;
				///////  Add Subsection to main section array object  /////
				$get_multi_section['sub_section'][$subkey] = $subsection_page;			
						
			}
			$get_result[$key] = $get_multi_section;
		}
		
		/////  for Standard Article page  ////////		  
		/* From CMS db */
		$standard_article 					  = $this->db->query('CALL get_pagemaster_sections("10000")');		
		$standard_article_section['page_det'] = $standard_article->result_array();
		if(count($standard_article_section['page_det']) == 0 )
		{
			$this->create_page('10000', 2);			
		}
		array_push($get_result,array(
										"Section_id" =>'0',
										"Sectionname" =>'Standard Article',
										"DisplayOrder" =>'0',
										"Section_landing" =>'2',
										"IsSeperateWebsite" =>'',
										"LinkedToColumnist" =>'',
										"page_det" =>$standard_article_section['page_det'],
										"section_allowed_for_hosting" => '0'
										
										));	
		/* for Clone Template */
		$clone_template 					  = $this->db->query('CALL get_pagemaster_sections("10001")');		
		$clone_template_section['page_det']   = $clone_template->result_array();
		if(count($clone_template_section['page_det']) == 0 )
		{
			$this->create_page('10001', 2);			
		}
		array_push($get_result,array(
										"Section_id" =>'0',
										"Sectionname" =>'Clone Widgets Template',
										"DisplayOrder" =>'0',
										"Section_landing" =>'1',
										"IsSeperateWebsite" =>'',
										"LinkedToColumnist" =>'',
										"page_det" =>$clone_template_section['page_det'],
										"section_allowed_for_hosting" => '0'
										
										));	
		
		return $get_result;
	}
	
	public function create_page($get_sec_id, $section_landing_no)
	{		
		$this->db->query('CALL insert_section_page("'.$get_sec_id.'", "'.$section_landing_no.'", '.USERID.', @inserted_id)');
		if($get_sec_id == 10000)
		{
			$standard_article 			 = $this->live_db->query('CALL get_pagemaster_using_sectionid
("10000", "")');		
			$live_table_article_rows	 = $standard_article->num_rows();
			if($live_table_article_rows == 0 )
			{
				$page_insert 	= $this->db->query("SELECT @inserted_id")->row_array();
				$page_id = explode(',', $page_insert['@inserted_id']);
				$page_id1 = $page_id[0];
				$page_id2 = $page_id[1];
				$this->live_db->query('CALL insert_section_page("'.$get_sec_id.'", "'.$section_landing_no.'", '.USERID.', "'.$page_id1.'", "'.$page_id2.'" )');
			}
			
		}elseif($get_sec_id == 10001){
			$standard_article 			 = $this->live_db->query('CALL get_pagemaster_using_sectionid
("10001", "")');		
			$live_table_article_rows	 = $standard_article->num_rows();
			if($live_table_article_rows == 0 )
			{
				$page_insert 	= $this->db->query("SELECT @inserted_id")->row_array();
				$page_id = explode(',', $page_insert['@inserted_id']);
				$page_id1 = $page_id[0];
				$page_id2 = $page_id[1];
				$this->live_db->query('CALL insert_section_page("'.$get_sec_id.'", "'.$section_landing_no.'", '.USERID.', "'.$page_id1.'", "'.$page_id2.'" )');
			}
			
		}
	}
	
	//////////  Retrieve Saved Template details condition of section_id and page_type from page_master table
	public function getPageDetails($section_id, $page_type)
	{
		$sec_page_details = $this->db->query("CALL get_pagemaster_using_sectionid('".$section_id."', '".$page_type."')");
		return $sec_page_details->row_array();
	}
	
	/////////  Retrieve all available page templates from template_master  ///////
	public function getTemplateDetails($template_id)
	{
		$result  = $this->db->query("CALL get_pagetemplates('1', '".$template_id."')");	
		return $result->row_array(); 
	}	

	public function getContainer($containerId)
	{
		$result  = $this->db->query("CALL get_containers('', '".$containerId."')");	
		return $result->row_array();
	}
	
	public function getWidgetContent($widget_id)
	{
		$result  = $this->db->query("CALL get_allwidgets('', '".$widget_id."')");			
		return $result->row_array();
	}
	public function get_pagemasterdetails_using_pageid($page_id)
	{
		$article_manager 	=  $this->db->query('CALL  get_pagemasterdetails_using_pageid('.$page_id.')')->result_array();
		return $article_manager;
	}
	public function insert_or_update_WidgetInstance($widget_instance, $login_user_id)
	{			
		
		$user_id = $login_user_id;
		foreach($widget_instance as $key => $value)
		{	
						
			$Pagesection_id 	= $value['Pagesection_id'];
			$Page_type 			= $value['Page_type'];
			$Container_ID 		= $value['Container_ID'];
			$WidgetDisplayOrder = $value['WidgetDisplayOrder'];
			$Widget_id 			= $value['Widget_id'];
			$page_id			= $value['page_id'];
			$version_id			= $value['version_id'];
			
			$is_cloned			= $value['is_cloned'];
			$cloned_instance_id	= $value['cloned_instance_id'];
	
			$query 	= $this->db->query("CALL get_widget_instance('".$Pagesection_id."', '".$Page_type."', '".$Container_ID."', '".$WidgetDisplayOrder."', '', 'adminview', '".$version_id."')");					
			
			$result = $query->row_array();			
			if(!empty($result['WidgetInstance_id']))
			{
				
				$Modifiedby 		= $user_id;
				$WidgetInstance_id	= $result['WidgetInstance_id'];				
				$upd_result 		= $this->db->query("CALL update_widget_instance('".$WidgetInstance_id."', '".$Pagesection_id."', '".$Page_type."', '".$Container_ID."', '".$Widget_id."', '".$WidgetDisplayOrder."', '".$Modifiedby."','','','','','','','','','','', '".$page_id."', '".$version_id."', '".$is_cloned."', '".$cloned_instance_id."' )");			
						
				//return $WidgetInstance_id;
				$last_insert_query 		= $this->db->query("SELECT @inserted_instance_id");  
				
				$last_insert_result 	= $last_insert_query->row_array();
				return $last_insert_result['@inserted_instance_id']; 
			}
			else
			{
				
				$Createdby		= $user_id;				
				$insert_result 	= $this->db->query("CALL insert_widget_instance('".$Pagesection_id."', '".$Page_type."', '".$Container_ID."', '".$Widget_id."', '".$WidgetDisplayOrder."', '".$Createdby."', @inserted_instance_id, '".$page_id."', '".$version_id."', '".$cloned_instance_id."')");									
				
				$last_insert_query 		= $this->db->query("SELECT @inserted_instance_id");  
				$last_insert_result 	= $last_insert_query->row_array();
				return $last_insert_result['@inserted_instance_id']; 
			}
			
		}
				
	}
	
	public function copy_WidgetInstance($widget_instance_details, $reference_widget_instance, $clone_reference_id)
	{			
		$Pagesection_id 	= $widget_instance_details['Pagesection_id'];
		$Page_type 			= $widget_instance_details['Page_type'];
		$Container_ID 		= $widget_instance_details['Container_ID'];
		$WidgetDisplayOrder = $widget_instance_details['WidgetDisplayOrder'];
		$Widget_id 			= $widget_instance_details['Widget_id'];
		$page_id			= $widget_instance_details['page_id'];
		$version_id			= $widget_instance_details['version_id'];	
		$Createdby			= USERID;	
		$createdOn			= date("Y-m-d H:i:s");
		$insert_result 		= $this->db->query("CALL copy_widget_instance('".$Pagesection_id."', '".$Page_type."', '".$Container_ID."', '".$Widget_id."', '".$WidgetDisplayOrder."', '".$Createdby."', @inserted_instance_id, '".$page_id."', '".$version_id."', '".$reference_widget_instance."', '".$createdOn."', '".$clone_reference_id."')");
		
		$last_insert_query 	= $this->db->query("SELECT @inserted_instance_id");  
		$last_insert_result = $last_insert_query->row_array();
		return $last_insert_result['@inserted_instance_id']; 
		
		
		
				
	}
	
	public function update_widgetinstance_renderingmode($widget_instance_id, $rendering_mode, $widget_customtitle, $login_user_id, $widget_section_id, $widget_adscript, $widget_bgcolor, $widget_max_articles, $widget_showsummary, $widget_publishon, $widget_publishoff, $widget_status, $version_id, $is_cloned, $cloned_instance_id)
	{
				
		$Modifiedby 		= $login_user_id;		
		$upd_result 		= $this->db->query("CALL update_widget_instance('".$widget_instance_id."', '', '', '', '', '', '".$Modifiedby."', '".$rendering_mode."', '".addslashes($widget_customtitle)."', '".$widget_section_id."', '".addslashes($widget_adscript)."', '".addslashes($widget_bgcolor)."', '".$widget_max_articles."', '".$widget_showsummary."', '".$widget_publishon."', '".$widget_publishoff."', '".$widget_status."', '', '".$version_id."', '".$is_cloned."', '".$cloned_instance_id."')");	
		
		if(mysql_error() == '')
		$result_msg = array("message" => "Successfully saved", "status" => "success");
		else
		$result_msg = array("message" => "Internal server error", "status" => "fail");
		
		return $result_msg;	
	}
	
	//////////////  Get section Id from page_master table  ///////
	public function getSectionFromPage($pageId)
	{				
		$query		 = $this->db->query("CALL get_xmlpage_details('". $pageId."')");		
		$result		 = $query->row_array();		
		return $result;		
	}
	
	public function getWidgetInstance($section_id, $pagetype, $container_id, $widget_disp_order, $widget_instance, $mode, $config_version_id )
	{
		$query 	= $this->db->query("CALL get_widget_instance('".$section_id."', '".$pagetype."', '".$container_id."', '".$widget_disp_order."', '".$widget_instance."', '".$mode."', '".$config_version_id."')");			
				
		$result = $query->row_array();			
		if($widget_instance != '')
		{
			return @$result;
		}
		else
		{
			return @$result['WidgetInstance_id'];
		}
	}
	
	public function getWidgetInstance_archieve($section_id, $pagetype, $container_id, $widget_disp_order, $widget_instance, $mode )
	{
		
		$query 	= $this->archieve_db->query("CALL get_widget_instance('".$section_id."', '".$pagetype."', '".$container_id."', '".$widget_disp_order."', '".$widget_instance."', '".$mode."')");
							
		$result = $query->row_array();			
		if($widget_instance != '')
		{
			return @$result;
		}
		else
		{
			return @$result['WidgetInstance_id'];
		}
	}
	
	public function insert_widget_configuration($configuration_data, $widget_mainsection_id_extras, $widget_subconfig_id_extras, $widget_type, $page_version_id )
	{
		
		$result_msg = array("message" => "Error in saving configuration", "status" => "fail");
		if($configuration_data != "" && !empty($configuration_data))
		{
			$widget_mainsection_id_extra = explode("-", $widget_mainsection_id_extras); ///// list of widget_mainsection_config_id-Section_id separated with '-'
			$mainsection_id_extra_key	 = explode(",",$widget_mainsection_id_extra[0]);
			$section_id_extra			 = explode(",",$widget_mainsection_id_extra[1]);
			$instance_id_extra			 = explode(",",$widget_mainsection_id_extra[2]);
			
			/////////////  set status 'Inactive / 2' to all available widgetInstanceMainsections using $instance_id_extra ( widgetInstanceID)  //////
			$active_status					= '1';
			$inactive_status				= '2';
			
		 	$this->db->query("CALL update_widget_mainsection_config('', '', '', '', '', '$widget_mainsection_id_extra[2]', '".$inactive_status."', '')");			
			
			
			$widget_subconfig_id_extra	 	= explode("-", $widget_subconfig_id_extras); ///// list of widget_mainsection_config_id-Section_id separated with '-'
			$widget_subconfig_id_extra_key	= explode(",",$widget_subconfig_id_extra[0]);
			$main_config_id_extra			= explode(",",$widget_subconfig_id_extra[1]);
			$sub_section_id_extra			= explode(",",$widget_subconfig_id_extra[2]);
			
			/////////////  set status 'Inactive / 2' to all available widgetInstanceSubsections using $main_config_id_extra (widgetInstanceMainSectionID) //////
			
			$this->db->query("CALL update_widget_subsection_config('', '', '', '', '', '', '".$widget_subconfig_id_extra[1]."', '".$inactive_status."')");
		     
			$j = 0;
			foreach($configuration_data as $key => $form_data)
			{
				$Instanceid 				= $form_data['Instanceid']; 
				$custom_title 				= $form_data['custom_title'];
				$section_id 				= ($form_data['section_id'] == '') ? 'null' : $form_data['section_id'];
				$section_content_type		= $form_data['section_content_type']; ////  Content type(1->article,... all->All)
				$display_order 				= $form_data['display_order'];
				$created_by 				= $form_data['created_by'];
				$page_id					= @$form_data['page_id'];
				
				$search_key 				= "";
				$instance_search_key		= "";
				
				if($widget_type == 1)
				{
					$update_main_key			= array_search($Instanceid,$instance_id_extra);

				}
				else
				{
					$update_main_key			= array_search($section_id,$section_id_extra);					
				}
				if($update_main_key >= 0 && !is_bool($update_main_key))
				{
					$search_key 			= $mainsection_id_extra_key[$update_main_key];
					$instance_search_key	= $instance_id_extra[$update_main_key];
				}
				else
				{
					$search_key 			= "";
					$instance_search_key	= "";
				}
				$select_query 				= $this->db->query("CALL get_widget_mainsection_config('".$search_key."', '".$instance_search_key."')");
				
				if($select_query->num_rows() > 0)
				{
					$select_result			= $select_query->row_array();
					$widget_mainsection_id 	= $select_result['WidgetInstanceMainSection_id'];					
						
						$query 				= $this->db->query("CALL update_widget_mainsection_config('".$custom_title."', ".$section_id.", '".$display_order."', '".$created_by."', '".$widget_mainsection_id."', '', '".$active_status."', '".$section_content_type."')");	
															
						$result_msg 		= array("message" => "Updated successfully", "status" => "success");					
				}
				else
				{
					
					$query 					= $this->db->query("CALL insert_widget_configuration('".$Instanceid."', '".$custom_title."', ".$section_id.", '".$display_order."', '".$created_by."', @last_insert_id, '".$section_content_type."', '".$page_id."', '".$page_version_id ."')");
						
					
					$last_insert_query 		= $this->db->query("SELECT @last_insert_id");  
                    
					$last_insert_result 	= $last_insert_query->row_array();
					$widget_mainsection_id	= $last_insert_result['@last_insert_id']; 
					
					$result_msg = array("message" => "Successfully saved", "status" => "success");						
					
				}////////  Widget_mainsection_config				
				
				if(count(@$form_data['sub_section']) > 0)
				{					
					foreach($form_data['sub_section'] as $sub_key => $subsection)
					{
						$mainsection_id				= $widget_mainsection_id;   /////// mainsection_config ID						
						$custom_title 				= $subsection['custom_title']; //// Sub section custom title
						$section_id 				= ($form_data['section_id'] == '') ? 'null' : $form_data['section_id']; //////  Main section ID
						$sub_section_id 			= $subsection['section_id']; /////  Sub section ID
						$display_order 				= $subsection['display_order'];  // Sub section display order
						$created_by 				= $form_data['created_by'];
						
						$sub_config_search_key		= "";
						$main_config_search_key 	= ""; 
						$update_main_key			= array_search($sub_section_id,$sub_section_id_extra);
						if($update_main_key >= 0 && !is_bool($update_main_key))
						{
							$sub_config_search_key  = $widget_subconfig_id_extra_key[$update_main_key];
							$main_config_search_key = $main_config_id_extra[$update_main_key];
						}
						else
						{
							$sub_config_search_key = "";
							$main_config_search_key = "";
						}

						$query 					= $this->db->query("CALL insert_widget_subsection_config('".$mainsection_id."', '".$custom_title."', ".$section_id.", '".$sub_section_id."', '".$display_order."', '".$created_by."')");				
						$result_msg = array("message" => "Successfully saved", "status" => "success");	
					}
					}////////  Widget_subsection_config				
			} 
		}		
		return $result_msg;
		
	}
	
	public function get_widget_mainsection_config($widget_mainsection_config_id, $widget_instance_id)
	{
		$select_query 	= $this->db->query("CALL get_widget_mainsection_config('".$widget_mainsection_config_id."', '".$widget_instance_id."')");	
				
		$result			= $select_query->result_array();		
		return $result;
	}
	
	public function get_widget_subsection_config($widget_subsection_config_id, $widget_mainsection_config_id)
	{
		$select_query 	= $this->db->query("CALL get_widget_subsection_config('".$widget_subsection_config_id."', '".$widget_mainsection_config_id."')");		
		
		$result			= $select_query->result_array();
		return $result;
	}
	
	public function addwidget_articlecustomdetails($custom_details, $user_id)
	{		
		
		$return_msg =  array("status"=>false,"message"=>"Internal error","inserted_id"=>"", "msg_type"=>"2"); 
		
		$instance_id 						= $custom_details['instanceId'];
		$instance_maisection_id 			= ($custom_details['instanceMainSectionId'] == '' || $custom_details['instanceMainSectionId'] == 'undefined')? 'NULL' : $custom_details['instanceMainSectionId'];
		$instance_subsection_id 			= ($custom_details['instanceSubSectionId'] == '')? 'NULL' : $custom_details['instanceSubSectionId'];
		
		$content_id 						= $custom_details['content_id'];
		$content_type_id 					= $custom_details['contenttype_id'];
		$custom_title 						= addslashes($custom_details['Title']);
		$custom_summary 					= addslashes($custom_details['Summary']);
		$uploaded_image 					= addslashes(@$custom_details['uploaded_image']);	
		$uploaded_image_name				= @$custom_details['image_name'];		
				
		$update_displayorder 				= (@$custom_details['display_order'] == '')? '' : @$custom_details['display_order'];
		$update_contet_status 				= (@$custom_details['checked_status'] == '')? '2' : @$custom_details['checked_status'];
		$user_id 							= $user_id;
		$insert_date 						= date("Y-m-d H:i:s", time());
		
		$instancecontent_id 				= ($custom_details['instancecontent_id'] == '')? false : $custom_details['instancecontent_id'];
		$related_content_id					= $custom_details['related_content_id'];
		$version_id 						= $custom_details['version_id'];
		$live_version_id					= $custom_details['live_version_id'];
				
		$image_caption						= $custom_details['image_caption'];
		$image_alt							= $custom_details['image_alt'];
		$physical_name						= $custom_details['physical_name'];
		$moved_image_id						= $custom_details['moved_image_id'];
		if(!$instancecontent_id)
		{
			
			$insert_result 			= $this->db->query("CALL insert_widgetinstance_content('".$instance_id."', ".$instance_maisection_id.", ".$instance_subsection_id.", '".$content_id."', '".$custom_title."', '".$custom_summary."', '".$uploaded_image."', '".$user_id."', '".$update_contet_status ."', '".$update_displayorder."', '".$insert_date."', @inserted_id,'".$uploaded_image_name."','".$related_content_id."','".$content_type_id."','".$version_id."', '".addslashes($image_caption)."', '".addslashes($image_alt)."', '".addslashes($physical_name)."', '".$moved_image_id."')");				
			$last_insert_query 		= $this->db->query("SELECT @inserted_id") or die(mysql_error());  

			$last_insert_result 	= $last_insert_query->row_array();
			if($last_insert_result['@inserted_id'] != '')
			{				
				$return_msg =  array("status"=>true,"message"=> " Article successfully saved","inserted_id"=>$last_insert_result['@inserted_id'], "instancecontent_id"=>$instancecontent_id, "msg_type"=>"1"); 
			}
			else
			{
				$return_msg =  array("status"=>false,"message"=>"Internal error to save in ".$content_id." Article","inserted_id"=>"", "msg_type"=>"2"); 
			}
		}
		else
		{
			$updated_date = date("Y-m-d H:i:s", time());			
			 
			$insert_result 	= $this->db->query("CALL update_widgetinstance_content('".$instancecontent_id."', '".$custom_title."', '".$custom_summary."', '".$uploaded_image."', '".$update_displayorder."','".$update_contet_status."', '".$user_id."', '".$updated_date."','".$uploaded_image_name."','".$version_id."', '".addslashes($image_caption)."', '".addslashes($image_alt)."', '".addslashes($physical_name)."', '".$moved_image_id."')");				
			
			if(!mysql_error())
			{
				$return_msg =  array("status"=>true,"message"=> " Article successfully updated","inserted_id"=>"", "instancecontent_id"=>$instancecontent_id, "msg_type"=>"1"); 
			}
			else
			{
				$return_msg =  array("status"=>false,"message"=>"Failed to update","inserted_id"=>"", "instancecontent_id"=>$instancecontent_id, "msg_type"=>"2"); 
			}			
		}		
		return $return_msg;		
	}
	
	public function get_section_by_id($section_id)
	{		
			
		
		$select_query 	= $this->db->query("CALL get_section_by_id('".$section_id."')");		
		
		$result			= $select_query->row_array();		
		return $result;
	}
	
	public function get_widgetInstanceArticles($widget_instance, $mainSectionConfig_Id,  $subSectionConfig_Id)
	{
		$select_query 	= $this->db->query("CALL get_WidgetInstancearticles('','','".$widget_instance."','".$mainSectionConfig_Id."','".$subSectionConfig_Id."')");		
		$result			= $select_query->result_array();		
		return $result;
	}
	
	public function get_all_available_articles($section_id, $content_type, $search_bytitle)
	{
		 	
		 $order_field 	 	= "Modifiedon";
		 $order				= "DESC";
		 $start_limt		= "0";
		 $length			= "60";
		 $check_in			= "";
		 $check_out			= "";
		 $Search_value		= $search_bytitle;
		 $Search_by			= ($Search_value != '')? "Title" : "";
		 $Section			= $section_id;
		 $Status			= "P";
		 $content_type		= $content_type;
		 
		 $article_manager 	=  $this->db->query('CALL required_widget_content_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start_limt.', '.$length.'","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'")');
		 
		 return $result = $article_manager->result_array();
	}
	
	public function articles_search_results($section_id, $content_type, $search_bytitle,$tagid)
	{
		 $order_field 	 	= "Modifiedon";
		 $order				= "DESC";
		 $start_limt		= "0";
		 $length			= "60";
		 $check_in			= "";
		 $check_out			= "";
		 $Search_value		= '';
		 $Search_by			= ($Search_value != '')? "Title" : "";
		 $Section			= '';
		 $Status			= "P";
		 $content_type		= '1';
		 $tag_id			='IE'.$tagid.'IE';
		 
		 $article_manager 	=  $this->db->query('CALL articles_search_results(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start_limt.', '.$length.'","'.$check_in.'","'.$check_out.'","'.$Search_value.'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'","'.$tag_id.'")');
		 
		 return $result = $article_manager->result_array();		 
	}
	
	public function get_tags_by_name($search_text) {
		
		$search = 'tag_name LIKE "'.$search_text.'"';
		$select_query 	= $this->db->query("CALL get_tags('','".$search."')");		
		
		$result			= $select_query->result_array();		
		return $result;
	}
	
	public function articles_searchby_results($searchby,$searchtext,$sectionid,$fromdate,$todate, $tag_id)
	{
		if($tag_id!=''&&$tag_id!=0)
		{
		    $tagid='IE'.$tag_id.'IE';		 
		}
		else if($searchby=="G")
		{
			$get_tagnames= $this->get_tags_by_name($searchtext);
			$tagid = @$get_tagnames[0]['tag_id'];
			
			if($tagid!='')
			{
				$tagid='IE'.$tagid.'IE';
			}
			else
			{
				$tagid='IE0IE';
			}
		}else if($searchby=="H")
		{
		   $searchby = $searchby;
		   $tagid='';
		}
		else
		{
			$tagid='';
		}
		if($fromdate!="")
		{
			$fromdate=date("Y-m-d",strtotime($fromdate));
		}
		else
		{
			$fromdate='';
		}
		if($todate!="")
		{
			$todate=date("Y-m-d",strtotime($todate));
		}
		else
		{
			$todate='';
		}
		 $order_field 	 	= "cv.Modifiedon";
		 $order				= "DESC";
		 $start_limt		= "0";
		 $length			= "60";
		 $check_in			= $fromdate;
		 $check_out			= $todate;
		 $Search_value		= $searchtext;
		 $Search_by			= $searchby;
		 $Section			= $sectionid;
		 $Status			= "P";
		 $content_type		= '1';
		 $tag_id			=$tagid;
		 
		 $article_manager 	=  $this->db->query('CALL articles_searchby_results(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start_limt.', '.$length.'","'.$check_in.'","'.$check_out.'","'.$Search_value.'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'","'.$tag_id.'","'.$searchby.'")')->result_array();
		
		 return $article_manager;
		
	}

	public function required_widget_content_by_id($contennt_ids, $content_type_id)
	{
		 	
		 $order_field 	 	= "Modifiedon";
		 $order				= "DESC";
		 $start_limt		= "0";
		 $length			= "60";
		 $check_in			= "";
		 $check_out			= "";
		 $Search_value		= "";
		 $Search_by			= "";
		 $Section			= "";
		 $Status			= "P";
		 $content_type		= $content_type_id;
		 
		 $article_manager 	=  $this->db->query('CALL required_widget_content_by_id(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start_limt.', '.$length.'","'.$check_in.'","'.$check_out.'","'.$Search_value.'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'", "'.$contennt_ids.'")');
		 return $result = $article_manager->result_array();
	}
	public function required_widget_content_by_id_archieve($contennt_ids, $content_type_id)
	{
		 	
		 $order_field 	 	= "Modifiedon";
		 $order				= "DESC";
		 $start_limt		= "0";
		 $length			= "60";
		 $check_in			= "";
		 $check_out			= "";
		 $Search_value		= "";
		 $Search_by			= "";
		 $Section			= "";
		 $Status			= "P";
		 $content_type		= $content_type_id;
		
		 $article_manager 	=  $this->archieve_db->query('CALL required_widget_content_by_id(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start_limt.', '.$length.'","'.$check_in.'","'.$check_out.'","'.$Search_value.'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'", "'.$contennt_ids.'")');
		
		 return $result = $article_manager->result_array();
	}
	
	public function widget_article_content_by_id($contennt_ids)
	{
		 	
		 $order_field 	 	= "Modifiedon";
		 $order				= "DESC";
		 $start_limt		= "0";
		 $length			= "60";
		 $check_in			= "";
		 $check_out			= "";
		 $Search_value		= "";
		 $Search_by			= "";
		 $Section			= "";
		 $Status			= "P";
		 $content_type		= "1";
		 
		 $article_manager 	=  $this->db->query('CALL widget_article_content_by_id(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start_limt.', '.$length.'","'.$check_in.'","'.$check_out.'","'.$Search_value.'","'.$Search_by.'","'.$Section.'","'.$Status.'","'.$content_type.'", "'.$contennt_ids.'")');
		
		 return $result = $article_manager->result_array();
	}
	
	public function get_image_by_contentid($content_id)
	{
		$article_details 	= $this->db->query('CALL get_imagedetails_by_contentid(' . $content_id . ')');
		$row_array 			= $article_details->row_array();
		return $row_array;
	}
	
	public function inactivate_articlecustomdetails($custom_details, $user_id)
	{
		
		$instance_id 						= $custom_details['instanceId'];
		$instance_maisection_id 			= ($custom_details['instanceMainSectionId'] == '' || $custom_details['instanceMainSectionId'] == 'undefined')? 'NULL' : $custom_details['instanceMainSectionId'];
		$instance_subsection_id 			= ($custom_details['instanceSubSectionId'] == '')? 'NULL' : $custom_details['instanceSubSectionId'];
		
		$update_contet_status 				= "2";
		$user_id 							= $user_id;
		$instancecontent_id 				= "";
		$updated_date = date("Y-m-d H:i:s", time());			
		
		 
		$insert_result 	= $this->db->query("CALL inactivate_instance_articles('".$instancecontent_id."', '".$instance_id."', '".$instance_maisection_id."', '".$instance_subsection_id."','".$update_contet_status."', '".$user_id."', '".$updated_date."')");	
	}
	
	public function get_widgetDetails($widget_id, $widget_instance)
	{
		$select_query 	= $this->db->query("CALL get_widgetDetails('".$widget_id."','".$widget_instance."')");		
		$result			= $select_query->row_array();		
		return $result;
	}
	
	public function addwidget_articlecustomdetails_temporary($custom_details, $user_id)
	{				
		
		$return_msg =  array("status"=>false,"message"=>"Internal error","inserted_id"=>"", "msg_type"=>"2", "image_id"=>''); 
		
		$instance_id 						= $custom_details['instanceId'];
		$instance_maisection_id 			= ($custom_details['instanceMainSectionId'] == '' || $custom_details['instanceMainSectionId'] == 'undefined')? 'NULL' : $custom_details['instanceMainSectionId'];
		$instance_subsection_id 			= ($custom_details['instanceSubSectionId'] == '')? 'NULL' : $custom_details['instanceSubSectionId'];
		
		$content_id 						= $custom_details['content_id'];
		$content_type_id 					= $custom_details['contenttype_id'];
		$custom_title 						= addslashes($custom_details['Title']);
		$custom_summary 					= addslashes($custom_details['Summary']);		
		$uploaded_image 					= "";	
		$uploaded_image_name				= "";	
		
		$image_caption						= trim($custom_details['image_caption']);
		$image_alt							= trim($custom_details['image_alt']);
		$physical_name						= trim($custom_details['physical_name']);
		$moved_image_id						= $custom_details['moved_image_id'];
		$update_displayorder 				= (@$custom_details['display_order'] == '')? '' : @$custom_details['display_order'];
	 	$update_contet_status 				= (@$custom_details['checked_status'] == '')? '2' : @$custom_details['checked_status']; 
		$user_id 							= $user_id;
		$insert_date 						= @$custom_details['modified_date'];
		$instancecontent_id 				= ($custom_details['instancecontent_id'] == '')? false : $custom_details['instancecontent_id'];
		$related_content_id 				= ($custom_details['related_content_id'] == '' || $custom_details['related_content_id'] == '0') ? '0' : $custom_details['related_content_id'];
		$version_id 						= $custom_details['version_id'];
		$temp_articles = $this->get_widgetInstanceTempArticles($instance_id, $instance_maisection_id,  $instance_subsection_id, $content_id, $user_id, $related_content_id );
		if(count($temp_articles) == 0)		
		{
			if($custom_details['edit_flag'] == "Edit")
			{
				$insert_date 	= date("Y-m-d H:i:s", time());
			}
			
			$insert_result 			= $this->db->query("CALL insert_temporary_instance_articles('".$instance_id."', ".$instance_maisection_id.", ".$instance_subsection_id.", '".$content_id."', '".$custom_title."', '".$custom_summary."', '".$uploaded_image."', '".$user_id."', '".$update_contet_status ."', '".$update_displayorder."', '".$insert_date."', @inserted_id,'".$uploaded_image_name."', '".$related_content_id."', '".$content_type_id."', '".$version_id."', '".addslashes($image_caption)."', '".addslashes($image_alt)."', '".addslashes($physical_name)."', '".addslashes($moved_image_id)."')");	
				
			$last_insert_query 		= $this->db->query("SELECT @inserted_id") or die(mysql_error());
				  
			$last_insert_result 	= $last_insert_query->row_array();
			if($last_insert_result['@inserted_id'] != '')
			{				
				$return_msg =  array("status"=>true,"message"=> " Article successfully saved","inserted_id"=>$last_insert_result['@inserted_id'], "instancecontent_id"=>$instancecontent_id, "msg_type"=>"1", "image_id"=>$moved_image_id); 
			}
			else
			{
				$return_msg =  array("status"=>false,"message"=>"Internal error to save in ".$content_id." Article","inserted_id"=>"", "msg_type"=>"2", "image_id"=>''); 
			}														
		}
		else
		{			
				$temp_table_id = $temp_articles[0]['instancecontent_tempID'];
				if($temp_articles[0]['DisplayOrder'] == $update_displayorder)
				{
					$updated_date = $temp_articles[0]['publishedon'];
				}
				else
				{
					
					/////  Check whether the updating display order is available in instance content temporary table  //////////
					
					if($update_displayorder > $temp_articles[0]['DisplayOrder'])
					{
						
						/////  Updating display order is greater than current display order  ///////
						
						$available_display_order_details = $this->db->query("CALL get_InstanceTempArticles_active_displayorder ('','','".$instance_id."','".$instance_maisection_id."','".$instance_subsection_id."', '', '".$user_id."', '".$update_displayorder."')")->row_array();	
						$updated_date = date("Y-m-d H:i:s", (strtotime(@$available_display_order_details['publishedon'])-1));
					}
					else
					{
						/////  Updating display order is less than current display order  ///////
						$updated_date = date("Y-m-d H:i:s", time());
					}
					
				}
				$update_contet_status = '1';
				
				$insert_result 	= $this->db->query("CALL update_widgetinstance_tempcontent('".$instancecontent_id."', '".$custom_title."', '".$custom_summary."', '".$uploaded_image."', '".$update_displayorder."','".$update_contet_status."', '".$user_id."', '".$updated_date."','".$uploaded_image_name."', '".$temp_table_id."', '".$content_type_id."', '".$version_id."', '".addslashes($image_caption)."', '".addslashes($image_alt)."', '".addslashes($physical_name)."', '".$moved_image_id."')");				
					
				if(!mysql_error())
				{
					$return_msg =  array("status"=>true,"message"=> " Article updated successfully","inserted_id"=>"", "instancecontent_id"=>$instancecontent_id, "msg_type"=>"1", "image_id"=>$moved_image_id); 
				}
				else
				{
					$return_msg =  array("status"=>false,"message"=>"Failed to update","inserted_id"=>"", "instancecontent_id"=>$instancecontent_id, "msg_type"=>"2", "image_id"=>$moved_image_id); 
				}
		   
		}
		return $return_msg;					
	}
	
	public function insert_temporary_from_instance_articles($current_widgetinstance, $user_id, $version_id)
	{
		$insert_result 			= $this->db->query("CALL insert_temporary_instance_articles('".$current_widgetinstance."', '', '', '', '', '', '', '".$user_id."', '', '', '', @inserted_id,'', '','','".$version_id."', '', '', '', '')");	
		return $insert_result;
	}
	
	public function get_widgetInstanceTempArticles($widget_instance, $mainSectionConfig_Id,  $subSectionConfig_Id, $content_id, $user_id, $related_content_id )
	{
		$select_query 	= $this->db->query("CALL get_WidgetInstanceTempArticles ('','','".$widget_instance."','".$mainSectionConfig_Id."','".$subSectionConfig_Id."', '".$content_id."', '".$user_id."', '".$related_content_id."')");
		$result			= $select_query->result_array();
		return $result;
	}
	
	public function get_widgetInstanceTempArticles_active($widget_instance, $mainSectionConfig_Id,  $subSectionConfig_Id, $content_id, $user_id )
	{
		$select_query 	= $this->db->query("CALL get_WidgetInstanceTempArticles_active ('','','".$widget_instance."','".$mainSectionConfig_Id."','".$subSectionConfig_Id."', '".$content_id."', '".$user_id."')");		
		$result			= $select_query->result_array();		
		return $result;
	}
	
	public function inactivate_temp_articlecustomdetails($custom_details,$user_id)
	{					
		if($custom_details != '')
		{
			$instance_id 						= $custom_details['instanceId'];
			$instance_maisection_id 			= ($custom_details['instanceMainSectionId'] == '' || $custom_details['instanceMainSectionId'] == 'undefined')? 'NULL' : $custom_details['instanceMainSectionId'];
			$instance_subsection_id 			= ($custom_details['instanceSubSectionId'] == '')? 'NULL' : $custom_details['instanceSubSectionId'];				
			$user_id 							= $user_id;
			$instancecontent_id 				= "";
		}
		else
		{
			$instance_id 						= "";
			$instance_maisection_id 			= "";
			$instance_subsection_id 			= "";				
			$user_id 							= $user_id;
			$instancecontent_id 				= "";
		}
		$user_id		= $user_id;
		$insert_result 	= $this->db->query("CALL inactivate_instance_temparticles('', '".$instance_id."', '".$instance_maisection_id."', '".$instance_subsection_id."','', '".$user_id."', '')");	
	}
	
	public function inactivate_instance_temparticles_status($custom_details,$user_id)
	{
					
		if($custom_details != '')
		{
			$instance_id 						= $custom_details['instanceId'];
			$instance_maisection_id 			= ($custom_details['instanceMainSectionId'] == '' || $custom_details['instanceMainSectionId'] == 'undefined')? 'NULL' : $custom_details['instanceMainSectionId'];
			$instance_subsection_id 			= ($custom_details['instanceSubSectionId'] == '')? 'NULL' : $custom_details['instanceSubSectionId'];				
			$user_id 							= $user_id;
			$instancecontent_id 				= "";
			$related_content_id 				= ($custom_details['related_content_id'] == '' || $custom_details['related_content_id'] == '0') ? '0' : $custom_details['related_content_id'];
		}
		else
		{
			$instance_id 						= "";
			$instance_maisection_id 			= "";
			$instance_subsection_id 			= "";				
			$user_id 							= $user_id;
			$instancecontent_id 				= "";
		}
		$user_id		= $user_id;
		$insert_result 	= $this->db->query("CALL inactivate_instance_temparticles_status('', '".$instance_id."', '".$instance_maisection_id."', '".$instance_subsection_id."','', '".$user_id."', '', '".$related_content_id."')");
	}
	
	
	public function remove_widget_instance($widget_instance, $section_id, $page_type, $user_id)
	{		
		$modified_date = date("Y-m-d H:i:s", time());
		if($widget_instance != '')
		{
			$insert_result 	= $this->db->query("CALL delete_widgetinstance('".$widget_instance."',@widget_name, '', '', '".$user_id."', '".$modified_date."')");	
				
			$delete_instance= $this->db->query("SELECT @widget_name") or die(mysql_error());  
				
			$instance_name 	= $delete_instance->row_array();
			if(!mysql_error())
			{
				return array("widget_name"=>$instance_name['@widget_name'], "status"=>1);
			}
			else
			{
				return array("widget_name"=>"", "status"=>2);
			}
		}
		else if($section_id != '' && $page_type != '')
		{
			$insert_result 	= $this->db->query("CALL delete_widgetinstance('',@widget_name, '".$section_id."', '".$page_type."', '".$user_id."', '".$modified_date."')");	
				
			if(!mysql_error())
			{
				return array("widget_name"=>"", "status"=>1);
			}
			else
			{
				return array("widget_name"=>"", "status"=>2);
			}
		}
		
	}
	public function display_order_in_sequence($table_name, $widget_instance_id, $instance_mainsection_id,  $instance_subsection_id, $user_id, $related_content_id)
	{		
			
		$instance_mainsection_id	= ($instance_mainsection_id == '')? 'NULL' : $instance_mainsection_id;
		$instance_subsection_id		= ($instance_subsection_id == '')? 'NULL' : $instance_subsection_id;
		
		
		$this->db->query("CALL display_order_in_sequence('".$table_name."', '".$widget_instance_id."', ".$instance_mainsection_id.", ".$instance_subsection_id.",'".$user_id."')");	
			
		if($related_content_id != 0)
		{
			
			$this->db->query("CALL display_order_in_sequence_related('".$table_name."', '".$widget_instance_id."', ".$instance_mainsection_id.", ".$instance_subsection_id.",'".$user_id."')");
					
		}

		if(!mysql_error())
		{
			return array("status" => true);	
		}
		else
		{
			return array("status" => false);	
		}
	}
	public function get_author_by_id($authorid)
	{
			
		
		$author_details = $this->db->query('CALL get_author_by_id(' . $authorid . ')');
		
		$row_array = $author_details->row_array();
		return $row_array['AuthorName'];
	}
	public function get_author_details($authorid) {
			
		
		$authors =$this->db->query("CALL getauthordetails('".$authorid."')");
			
		return $authors->result_array(); 
	}
	
	////////  For related articles  /////
	public function get_widgetInstanceTempRelatedArticles($widget_instance, $mainSectionConfig_Id,  $subSectionConfig_Id, $content_id, $user_id, $related_content_id)
	{	
		$select_query 	= $this->db->query("CALL get_widgetInstanceTempRelatedArticles ('','','".$widget_instance."','".$mainSectionConfig_Id."','".$subSectionConfig_Id."', '".$content_id."', '".$user_id."', '".$related_content_id."')");		
		$result			= $select_query->result_array();		
		return $result;
	}
	
	public function inactivate_temp_related_articles($custom_details, $user_id)
	{
		$instance_id 						= $custom_details['instanceId'];
		$instance_maisection_id 			= ($custom_details['instanceMainSectionId'] == '' || $custom_details['instanceMainSectionId'] == 'undefined')? 'NULL' : $custom_details['instanceMainSectionId'];
		$instance_subsection_id 			= ($custom_details['instanceSubSectionId'] == '')? 'NULL' : $custom_details['instanceSubSectionId'];				
		$updated_date = date("Y-m-d H:i:s", time());
		
		$query 	= $this->db->query("CALL inactivate_temp_related_articles ('".$instance_id."','".$instance_maisection_id."','".$instance_subsection_id."', '".$user_id."', '".$updated_date."')");		
		
		if(!mysql_error())
		{
			return array("status" => true);	
		}
		else
		{
			return array("status" => false);	
		}
	}
	
	public function get_content_mapping($mapped_section_id, $content_type_id, $content_id) {
		
		$type = $this->db->query("CALL article_content_mapping ('".$content_type_id."','".$mapped_section_id."','".$content_id."')");
		return $type;
	}
	
	//////////  Get sectionId with section names (Specialsection name, parent section name, section name)
	public function get_sectionid_with_names($subsection_name, $parent_section_name, $special_section_name) 
	{
		
		$query = $this->db->query( "CALL get_sectionid_with_names ( '".$subsection_name."', '".$parent_section_name."', '".$special_section_name."' )" );
			
		return $query->result_array();
	}
	
	//////  Publish widgent instance articles  /////	
	public function publish_widgetinstance_articles($widget_instance_id, $user_id) 
	{
		
		
		$delete_live_instance_content = $this->live_db->query("DELETE FROM `widgetinstancecontent_live` WHERE `WidgetInstance_id` = '".$widget_instance_id."'" );
		  
		$widget_instance_contents 	 = $this->db->query("CALL get_WidgetInstance_content('". $widget_instance_id."')")->result_array();

			if(count($widget_instance_contents)>0){
				foreach($widget_instance_contents as $widget_instance_content){
				$WidgetInstanceContent_ID = $widget_instance_content['WidgetInstanceContent_ID'];
                $widgetInstanceRelated_ID  = $widget_instance_content['widgetInstanceRelated_ID'];
                $WidgetInstance_id  = $widget_instance_content['WidgetInstance_id'];
                $WidgetInstanceMainSection_id  = $widget_instance_content['WidgetInstanceMainSection_id'];
                $WidgetInstanceSubSection_ID  = $widget_instance_content['WidgetInstanceSubSection_ID'];
                $content_id  = $widget_instance_content['content_id'];
                $content_type_id  = $widget_instance_content['content_type_id'];
                $CustomTitle  = addslashes($widget_instance_content['CustomTitle']);
                $CustomSummary = addslashes($widget_instance_content['CustomSummary']);
                $Image = addslashes($widget_instance_content['Image']);
                $Imagename = $widget_instance_content['Imagename'];
                $DisplayOrder = $widget_instance_content['DisplayOrder'];
                $Publishedby = $widget_instance_content['Publishedby']; 
                $publishedon = $widget_instance_content['publishedon'];
                $UnPublishedby = $user_id;
                $Status = $widget_instance_content['Status'];
				
			 $insert_instancecontent_tolive = $this->live_db->query("INSERT  INTO `widgetinstancecontent_live`(
                `WidgetInstanceContent_ID`,
                `widgetInstanceRelated_ID`, 
                `WidgetInstance_id`, 
                `WidgetInstanceMainSection_id`,
                `WidgetInstanceSubSection_ID`,
                `content_id`,
                `content_type_id`,
                `CustomTitle`, 
                `CustomSummary`, 
                `Image`, 
                `Imagename`, 
                `DisplayOrder`, 
                `Publishedby`, 
                `publishedon`, 
                `UnPublishedby`, 
                `Unpublishedon`, 
                `Status`                        
                 )                        
                 VALUES ('".$WidgetInstanceContent_ID."', '".$widgetInstanceRelated_ID."', '".$WidgetInstance_id."' ,'".$WidgetInstanceMainSection_id."' ,'".$WidgetInstanceSubSection_ID."' , '".$content_id."' , '".$content_type_id."' , '".$CustomTitle."', '".$CustomSummary."', '".$Image."', '".$Imagename."', '".$DisplayOrder."', '".$Publishedby."', '".$publishedon."' , '".$UnPublishedby."',  now(), '".$Status."')");
						
				}
			}
	
		if(!mysql_error())
		{
			$return_msg =  array("status"=>true,"message"=> " Article successfully published", "inserted_id"=>$this->db->last_query(), "instancecontent_id"=>""); 
		}
		else
		{
			$return_msg =  array("status"=>false,"message"=>"Failed to publish","inserted_id"=>"", "instancecontent_id"=>""); 
		}
		return $return_msg;
	}
	
	/////  Get the content details using ECENIC article ID  ////////
	public function get_content_by_ecenic_id($old_site_article_id)
	{
		$result = $this->db->query("CALL get_content_by_ecenic_id ('".$old_site_article_id."')");			
		return $result->row_array();
	}	
	
	/////  Change template Lock status  ///////
	public function change_template_lockstatus($lock_status, $page_id, $user_id)
	{
		$class_object = new TemplateLock;
		return $class_object->change_template_lockstatus($lock_status, $page_id, $user_id);
	}
	
	/////  Get locked templates using status, template id, locked user id  ///////
	public function get_locked_template_details($lockstatus, $page_id, $user_id)
	{
		$class_object = new TemplateLock;
		return $class_object->get_locked_template_details($lockstatus, $page_id, $user_id);
	}
	
	/////  Lock All templates for the user  ///////
	public function lock_templates($user_id, $page_id)
	{
		$class_object = new TemplateLock;
		return $class_object->lock_templates($user_id, $page_id);
	}
	
	///////  Get current template is commited or not  ///////
	public function get_template_commit_status($page_id)
	{
		$class_object = new TemplateLock;
		return $class_object->get_template_commit_status($page_id);
	}
	
	public function get_version_whole_widgets_lock_details($version_id, $is_advertisement)
	{
		$class_object = new TemplateLock;
		return $class_object->get_version_whole_widgets_lock_details($version_id, $is_advertisement);
	}
	
	////  Get User details using user id  /////
	public function get_userdetails_by_id($user_id)
	{
		$class_object = new LockedUserDetails;
		return $class_object->get_userdetails_by_id($user_id);
	}
	
	//////  get lock status is 2 in add widget article details  /////
	public function	get_locked_widget_article($widgetinstance_id, $user_id)
	{
		$class_object = new WidgetArticleLock;
		return $class_object->get_locked_widget_article($widgetinstance_id, $user_id);
	}
	
	//////  Change the add widget article lock status  /////
	public function	change_widget_article_lockstatus($widgetinstance_id, $user_id, $lock_status)
	{
		$class_object = new WidgetArticleLock;
		return $class_object->change_widget_article_lockstatus($widgetinstance_id, $user_id, $lock_status);
	}
	
	//////  Change the widget configuration lock status  /////
	public function change_widget_config_lockstatus($widgetinstance_id, $user_id, $lock_status)
	{
		$class_object = new WidgetConfigLock;
		return $class_object->change_widget_config_lockstatus($widgetinstance_id, $user_id, $lock_status);
	}
	
	public function is_widget_saved_in_version($widgetinstance_id)
	{
		$class_object = new WidgetConfigLock;
		return $class_object->is_widget_saved_in_version($widgetinstance_id);
	}
	
	
	//////  Get template versions from page_template_version table  ////
	public function get_template_versions($page_id, $version_id)
	{
		$class_object = new TemplateVersion;
		return $class_object->get_template_versions($page_id, $version_id);
	}
	
	/////  Get Templates published versions details  (If not published, then the last version is default) /////
	public function get_default_versionTemplate($page_id, $return_data)
	{
		$class_object = new TemplateVersion;
		return $class_object->get_default_versionTemplate($page_id, $return_data);
	}
	
	////  Get xml data from version table using version id and update pagemaster table and load page template ///
	public function load_template_by_version_id($version_id)
	{
		$class_object = new TemplateVersion;
		return $class_object->load_template_by_version_id($version_id);
	}
	public function template_rendering_by_version_id($version_id)
	{
		$class_object = new TemplateVersion;
		return $class_object->template_rendering_by_version_id($version_id);
	}
	public function load_published_version_template($page_id)
	{
		$class_object = new TemplateVersion;
		return $class_object->load_published_version_template($page_id);
	}
	
	public function delete_currentTemplateVersion($version_id)
	{
		$class_object = new TemplateVersion;
		return $class_object->delete_currentTemplateVersion($version_id);
	}
	
	public function update_version_name($version_id, $version_name)
	{
		$class_object = new TemplateVersion;
		return $class_object->update_version_name($version_id, $version_name);
	}
	
	public function publish_only_advertisements($version_id, $header_adv_script, $publishing_widget_instances, $get_adv_mainConfig,  $delete_published_page_section_id, $delete_published_page_section_type)
	{		
		$class_object = new Advertisement;
		return $class_object->publish_only_advertisements($version_id, $header_adv_script, $publishing_widget_instances, $get_adv_mainConfig,  $delete_published_page_section_id, $delete_published_page_section_type);	
	}
	
	public function get_adv_instance_by_versionId($version_id, $is_advertisement_widget )
	{
		$class_object = new Advertisement;
		return $class_object->get_adv_instance_by_versionId($version_id, $is_advertisement_widget );
	}
	
	public function get_adv_mainSectionConfig_by_versionId($version_id, $is_advertisement_widget)
	{
		$class_object = new Advertisement;
		return $class_object->get_adv_mainSectionConfig_by_versionId($version_id, $is_advertisement_widget);
	}
	
	public function get_instance_articles_by_versionId($version_id, $widget_instance_id)
	{
		$class_object = new Advertisement;
		return $class_object->get_instance_articles_by_versionId($version_id, $widget_instance_id);
	}
	public function get_header_script_details($version_id)
	{
		$class_object = new Advertisement;
		return $class_object->get_header_script_details($version_id);
	}
	
	public function update_header_script_lock_status($version_id, $lock_status)
	{
		$class_object = new Advertisement;
		return $class_object->update_header_script_lock_status($version_id, $lock_status);
	}
	
	
	//////  Delete temporaryInstances from temporary_instances table using page id and Don't save button status //////
	public function delete_temporary_instance($page_id, $save_status)
	{
		$class_object = new TemplateTemporaryInstance;
		return $class_object->delete_temporary_instance($page_id, $save_status);
	}
	
	public function insert_widget_configuration_temporarily($configuration_data, $widget_mainsection_id_extras, $widget_subconfig_id_extras, $widget_type, $page_version_id, $page_id )
	{
		$class_object = new TemplateTemporaryInstance;
		return $class_object->insert_widget_configuration_temporarily($configuration_data, $widget_mainsection_id_extras, $widget_subconfig_id_extras, $widget_type, $page_version_id, $page_id );
	}
	
	public function update_widgetinstance_renderingmode_temporarily($widget_instance_id, $rendering_mode, $widget_customtitle, $login_user_id, $widget_section_id, $widget_adscript, $widget_bgcolor, $widget_max_articles, $widget_showsummary, $widget_publishon, $widget_publishoff, $widget_status, $version_id, $is_cloned)
	{
		$class_object = new TemplateTemporaryInstance;
		return $class_object->update_widgetinstance_renderingmode_temporarily($widget_instance_id, $rendering_mode, $widget_customtitle, $login_user_id, $widget_section_id, $widget_adscript, $widget_bgcolor, $widget_max_articles, $widget_showsummary, $widget_publishon, $widget_publishoff, $widget_status, $version_id, $is_cloned);
	}
	public function remove_widget_temporarily($widget_instance_id, $page_id)
	{
		$class_object = new TemplateTemporaryInstance;
		return $class_object->remove_widget_temporarily($widget_instance_id, $page_id);
	}
	
	public function get_temporary_widget_mainsection_config($widget_mainsection_config_id, $widget_instance_id)
	{
		$class_object = new TemplateTemporaryInstance;
		return $class_object->get_temporary_widget_mainsection_config($widget_mainsection_config_id, $widget_instance_id);
	}
	
	public function list_out_published_articles($section_id, $contentype_id, $search_value, $search_by, $from_date, $to_date, $widget_instance_id, $current_widget_section_id)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->list_out_published_articles($section_id, $contentype_id, $search_value, $search_by, $from_date, $to_date, $widget_instance_id, $current_widget_section_id);
	}
	
	public function publish_individual_instance_articles($publishing_instance_articles, $widget_instance_id, $version_id )
	{
		$class_object = new WidgetAddArticles;
		return $class_object->publish_individual_instance_articles($publishing_instance_articles, $widget_instance_id, $version_id );
	}
	
	public function custom_image_upload($userid, $imagecontent_id,$contenttype, $caption, $alt_tag, $physical_name, $file_name, $image1_type, $image2_type, $image3_type, $image4_type, $display_order, $article_id, $instance_id, $mainSectionConfig_id)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->custom_image_upload($userid, $imagecontent_id,$contenttype, $caption, $alt_tag, $physical_name, $file_name, $image1_type, $image2_type, $image3_type, $image4_type, $display_order, $article_id, $instance_id, $mainSectionConfig_id);
	}
	
	public function Insert_temp_from_image_library($ImageDetails, $content_id, $caption, $alt, $path, $contenttype, $article_id, $instance_id, $mainSectionConfig_id )
	{
		$class_object = new WidgetAddArticles;
		return $class_object->Insert_temp_from_image_library($ImageDetails, $content_id, $caption, $alt, $path, $contenttype, $article_id, $instance_id, $mainSectionConfig_id );
	}
	
	public function search_image_library($Caption)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->search_image_library($Caption);
	}
	
	public function search_image_library_scroll($page)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->search_image_library_scroll($page);
	}
	
	public function get_image_library()
	{
		$class_object = new WidgetAddArticles;
		return $class_object->get_image_library();
	}
	
	public function add_image_by_temp_id($caption,$alt,$home_physical_name,$tempid) 
	{
		$class_object = new WidgetAddArticles;
		return $class_object->add_image_by_temp_id($caption,$alt,$home_physical_name,$tempid);
	}
	
	public function get_image_details_from_library_by_content_id($image_library_id)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->get_image_details_from_library_by_content_id($image_library_id);
	}
	
	public function temp_custom_image_details($temp_table_image_id, $saved_image_id)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->temp_custom_image_details($temp_table_image_id, $saved_image_id);
	}
	public function common_resize_all_images($ImageDetails) 
	{
		$class_object = new WidgetAddArticles;
		return $class_object->common_resize_all_images($ImageDetails);
	}
	public function common_resize_all_images_again($ImageDetails) 
	{
		$class_object = new WidgetAddArticles;
		return $class_object->common_resize_all_images_again($ImageDetails);
	}
	
	public function update_custom_crop_image($ContentImageId, $crop_caption, $crop_alt, $image_600X390_type,$image_600X300_type,$image_100X65_type,$image_150X150_type, $modifiedon, $crop_image_id, $imagetype, $commit_status)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->update_custom_crop_image($ContentImageId, $crop_caption, $crop_alt, $image_600X390_type,$image_600X300_type,$image_100X65_type,$image_150X150_type, $modifiedon, $crop_image_id, $imagetype, $commit_status);
	}
	public function delete_temp_custom_image($custom_image_tempid)
	{
		$class_object = new WidgetAddArticles;
		return $class_object->delete_temp_custom_image($custom_image_tempid);
	}
	
	public function change_ischanges_published($instance_id, $flag_status){
		$class_object = new WidgetAddArticles;
		return $class_object->change_ischanges_published($instance_id, $flag_status);
	}
	
	public function release_locks_by_user_id()
	{
		$class_object = new ReleaseLocks;
		return $class_object->release_locks_by_user_id();
	}
	
	public function get_widget_supported_image_sizes($widget_instance_id)
	{		
		$class_object = new SupportedImageSizes;
		return $class_object->get_widget_supported_image_sizes($widget_instance_id);	
	}
	public function clone_widget_instance($cloned_widget_instance, $cloned_widget_id)
	{
		$class_object = new WidgetCloning;
		return $class_object->clone_widget_instance($cloned_widget_instance, $cloned_widget_id);
	}
	
	public function get_cloned_instance_details($cloned_widget_instance)
	{
		$class_object = new WidgetCloning;
		return $class_object->get_cloned_instance_details($cloned_widget_instance);
	}
	
	public function get_clone_mapping_details($clone_instance_id){
		$class_object = new WidgetCloning;
		return $class_object->get_clone_mapping_details($clone_instance_id);
	}
	
	public function get_frontend_clone_mapping_details($clone_instance_id){
		$class_object = new WidgetCloning;
		return $class_object->get_frontend_clone_mapping_details($clone_instance_id);
	}
	public function restore_last_updated_design($page_id, $version_id)
	{
		$class_object = new RestoreTemplate;
		return $class_object->restore_last_updated_design($page_id, $version_id);
	}
	
}/* End of Template_design_model - Class */

class TemplateLock extends Template_design_model
{	
	public function change_template_lockstatus($lockstatus, $page_id, $user_id)
	{		
		$this->db->query("CALL change_template_lockstatus('". $lockstatus."', '". $page_id."','". $user_id."')");
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function get_locked_template_details($lockstatus, $page_id, $user_id)
	{
		$result_obj = $this->db->query("CALL get_locked_template_details('". $lockstatus."', '". $page_id."','". $user_id."')");
		$result		= $result_obj->result_array();
		return $result;
	}
	
	public function lock_templates($user_id, $page_id)
	{				
		$this->db->query("CALL lock_template_design('". $user_id."', '". $page_id."')");		
		if(mysql_error() == '')
		{
			return true;
		}
		else{
			return false;
		}
	}
	
	public function get_template_commit_status($page_id)
	{
		$result_obj = $this->get_template_xmlcontent($page_id, ''); 
		$commit_status = array("tempalte_commit_status" =>$result_obj['Is_Template_Committed'], "locked_status"=>$result_obj['locked_status'], "locked_user_id"=>$result_obj['locked_user_id']);
		return $commit_status;
	}
	
	public function get_version_whole_widgets_lock_details($version_id, $is_advertisement)
	{
		/* $is_advertisement: 1->Yes, 2->Not */
		$result_obj = $this->db->query("CALL get_version_whole_widgets_lock_details('". $version_id."', '".$is_advertisement."')");
		$result		= $result_obj->result_array();		
		return $result;
	}
} /* End of TemplateLock - Class */

class WidgetArticleLock extends Template_design_model
{
	public function change_widget_article_lockstatus($widgetinstance_id, $user_id, $lock_status)
	{
		$this->db->query("CALL change_widget_article_lockstatus('". $widgetinstance_id."', '". $user_id."', '". $lock_status."')");
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function get_locked_widget_article($widgetinstance_id, $user_id)
	{
		$result_obj = $this->db->query("CALL get_locked_widget_article('". $widgetinstance_id."', '". $user_id."')");
		$result		= $result_obj->result_array();
		return $result;
	}
}

class WidgetConfigLock extends Template_design_model
{
	public function change_widget_config_lockstatus($widgetinstance_id, $user_id, $lock_status)
	{
		$this->db->query("CALL change_widget_config_lockstatus('". $widgetinstance_id."', '". $user_id."', '". $lock_status."')");
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function is_widget_saved_in_version($widgetinstance_id)
	{
		$widget_temporary = $this->db->query("CALL is_widget_saved_in_version('". $widgetinstance_id."')");
		$widget_temporary_details = $widget_temporary->row_array();
		if(count($widget_temporary_details)>0)
		{
			return $widget_temporary_details;
		}
		else
		{
			return false;
		}
	}
	
	/////  For getting the widget Config & Article lock status use - 	get_locked_widget_article() function  //////
	
}

class LockedUserDetails extends Template_design_model
{
	public function get_userdetails_by_id($user_id)
	{
		/////  Get User details  ////
		$user_details = $this->db->query("CALL get_userdetails_by_id('".$user_id."')")->row_array();	
		return $user_details;
	}
}/* End of  LockedUserDetails- Class */

class ReleaseLocks extends Template_design_model
{
	public function release_locks_by_user_id()
	{
		$release_lock_details = $this->db->query("CALL release_locks_by_user_id('".USERID."')")->row_array();	
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}/* End of  LockedUserDetails- Class */

class TemplateVersion extends Template_design_model
{
	public function get_template_versions($page_id, $version_id)
	{
		/////  Get Templates all versions details  ////
		$version_details = $this->db->query("CALL get_pageTemplate_versions('".$page_id."', '".$version_id."')");	
		return $version_details->result_array();
	}
	
	public function get_default_versionTemplate($page_id, $return_data)
	{
		/////  Get Templates published versions details  (If not published, then the last version is default) /////
		$version_details = $this->db->query("CALL get_defaultTemplate_version('".$page_id."')");	
		
		if($return_data == "xmldata")
		{
			foreach ($version_details->result() as $row)
			{			
			  $xml_content = $row->Template_XML;		  
			}		
		}
		else
		{
			$xml_content = $version_details->row_array();
		}
		return $xml_content;
	}
	
	public function load_template_by_version_id($version_id)
	{
		////  Get xml data from version table using version id and update pagemaster table and load page template ///
		$template_by_version_id = $this->db->query("CALL load_template_by_version_id('".$version_id."', '".USERID."')");			
		return $template_by_version_id->row_array();		
	}
	public function template_rendering_by_version_id($version_id)
	{
		////  Get xml data from version table using version id and update pagemaster table and load page template ///
		$template_by_version_id = $this->db->query("CALL template_rendering_by_version_id('".$version_id."')");			
		return $template_by_version_id->row_array();		
	}
	
	public function load_published_version_template($page_id)
	{
		//////  Get the published version id from page master table and load it by version id  /////
		$page_details = $this->get_default_versionTemplate($page_id, '');
		
		if(!empty($page_details))
		{			
			$template_page_details = $this->load_template_by_version_id($page_details['Version_Id']); /////  This is will update the current version template xml		
		}
		else
		{						
			$no_version_page_details 				= $this->get_template_xmlcontent($page_id, '');			
			@$template_page_details					= $no_version_page_details;
			@$template_page_details['Template_XML'] = $no_version_page_details['templatexml'];
		}
		
		return $template_page_details;
		
	}
	
	public function delete_currentTemplateVersion($version_id)
	{
		////  Delete / update status of curent template version ///
		$template_version_delete_status = $this->db->query("CALL delete_currentTemplateVersion('".$version_id."', '".USERID."')");			
		if(mysql_error() == '')
		{
			return $template_version_delete_status->row_array();
		}
		else
		{
			return false;
		}
	}
	
	public function update_version_name($version_id, $version_name)
	{
		////  update name of curent template version ///
		$template_version_delete_status = $this->db->query("CALL update_version_name('".$version_id."', '".addslashes($version_name)."', '".USERID."', @old_version_name)");			
		$old_version 					= $this->db->query("SELECT @old_version_name");  		
		$oldversion_name_result 		= $old_version->row_array();
		$oldversion_name				= $oldversion_name_result['@old_version_name'];
		
		if(mysql_error() == '')
		{
			return array("status"=>true, "oldversion_name" => $oldversion_name);
		}
		else
		{
			return array("status"=>false, "oldversion_name" => $oldversion_name);
		}
	}	
	
}/* End of  TemplateVersion- Class */
class Advertisement extends Template_design_model{
	
	public function publish_only_advertisements($version_id, $header_adv_script, $publishing_widget_instances, $get_adv_mainConfig,  $delete_published_page_section_id, $delete_published_page_section_type)
	{
		$publish_advertisements = $this->live_db->query("CALL publish_only_advertisements('".$version_id."', '".addslashes($header_adv_script)."', '".addslashes($publishing_widget_instances)."', '".addslashes($get_adv_mainConfig)."',  '".$delete_published_page_section_id."', '".$delete_published_page_section_type."')");
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	
	public function get_adv_instance_by_versionId($version_id, $is_advertisement_widget )
	{		
		$adv_details = $this->db->query("CALL get_adv_instance_by_versionId('".$version_id."', @header_adv_script, '".$is_advertisement_widget."', @page_section_id, @page_type)");
		$result 	 = $adv_details->result_array();
		$out_prams 	 = $this->db->query("SELECT @header_adv_script, @page_section_id, @page_type")->row_array();
		
		$result['header_adv_script'] 	= $out_prams;
		return $result;
	}
	
	public function get_adv_mainSectionConfig_by_versionId($version_id, $is_advertisement_widget)
	{
		$adv_details = $this->db->query("CALL get_adv_mainSectionConfig_by_versionId('".$version_id."', '".$is_advertisement_widget."')");
		$result = $adv_details->result_array();		
		return $result;
	}
	
	public function get_instance_articles_by_versionId($version_id, $widget_instance_id)
	{
		$adv_details = $this->db->query("CALL get_instance_articles_by_versionId('".$version_id."', '".$widget_instance_id."')");
		$result = $adv_details->result_array();		
		return $result;
	}
	
	public function get_header_script_details($version_id)
	{
		$adv_details = $this->db->query("CALL get_header_script_details('".$version_id."')");
		$result = $adv_details->row_array();		
		return $result;
	}
	public function update_header_script_lock_status($version_id, $lock_status)
	{
		$this->db->query("CALL update_headerScriptLockStatus('".$version_id."', '".$lock_status."', '".USERID."')");
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
}/* End of  TemplateVersion- Class */

class TemplateTemporaryInstance extends Template_design_model
{
	public function delete_temporary_instance($page_id, $save_status)
	{
		$this->db->query("CALL delete_temporary_instance('". $page_id."', '". $save_status."', '".USERID."')");
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function insert_widget_configuration_temporarily($configuration_data, $widget_mainsection_id_extras, $widget_subconfig_id_extras, $widget_type, $page_version_id, $page_id )
	{
		$result_msg = array("message" => "Error in saving configuration", "status" => "fail");
		if($configuration_data != "" && !empty($configuration_data))
		{
			$widget_mainsection_id_extra = explode("-", $widget_mainsection_id_extras); ///// list of widget_mainsection_config_id-Section_id separated with '-'
			$mainsection_id_extra_key	 = explode(",",$widget_mainsection_id_extra[0]);
			$section_id_extra			 = explode(",",$widget_mainsection_id_extra[1]);
			$instance_id_extra			 = explode(",",$widget_mainsection_id_extra[2]);
			
			/////////////  set status 'Inactive / 2' to all available widgetInstanceMainsections using $instance_id_extra ( widgetInstanceID)  //////
			$active_status					= '1';
			$inactive_status				= '2';
			
		 	$this->db->query("CALL update_widget_mainsection_config_temporarily('', '', '', '', '', '".$widget_mainsection_id_extra[2]."', '".$inactive_status."', '')");			
			
			
			$widget_subconfig_id_extra	 	= explode("-", $widget_subconfig_id_extras); ///// list of widget_mainsection_config_id-Section_id separated with '-'
			$widget_subconfig_id_extra_key	= explode(",",$widget_subconfig_id_extra[0]);
			$main_config_id_extra			= explode(",",$widget_subconfig_id_extra[1]);
			$sub_section_id_extra			= explode(",",$widget_subconfig_id_extra[2]);
			
			/////////////  set status 'Inactive / 2' to all available widgetInstanceSubsections using $main_config_id_extra (widgetInstanceMainSectionID) //////
			
			$j = 0;
			foreach($configuration_data as $key => $form_data)
			{
				$Instanceid 				= $form_data['Instanceid']; 
				$custom_title 				= $form_data['custom_title'];
				$section_id 				= ($form_data['section_id'] == '') ? 'null' : $form_data['section_id'];
				$section_content_type		= $form_data['section_content_type']; ////  Content type(1->article,... all->All)
				$display_order 				= $form_data['display_order'];
				$created_by 				= $form_data['created_by'];
				$page_id					= ($page_id != '')? $page_id : @$form_data['page_id'];
				
				$search_key 				= "";
				$instance_search_key		= "";
				
				if($widget_type == 1)
				{
					$update_main_key			= array_search($Instanceid,$instance_id_extra);

				}
				else
				{					
					$update_main_key = $j;
				}
				if($update_main_key >= 0 && !is_bool($update_main_key))
				{
					$search_key 			= isset($mainsection_id_extra_key[$update_main_key]) ? $mainsection_id_extra_key[$update_main_key] : "";
					$instance_search_key	= isset($instance_id_extra[$update_main_key]) ? $instance_id_extra[$update_main_key] : "";
				}
				else
				{
					$search_key 			= "";
					$instance_search_key	= "";
				}
				$select_query 				= $this->db->query("CALL get_temporary_widget_mainsection_config('".$search_key."', '".$instance_search_key."', '".USERID."')");
				
				if($select_query->num_rows() > 0)
				{
					$select_result			= $select_query->row_array();
					$widget_mainsection_id 	= $select_result['WidgetInstanceMainSection_id'];					
						
						$query 				= $this->db->query("CALL update_widget_mainsection_config_temporarily('".addslashes($custom_title)."', ".$section_id.", '".$display_order."', '".$created_by."', '".$widget_mainsection_id."', '', '".$active_status."', '".$section_content_type."')");	
															
						$result_msg 		= array("message" => "Updated successfully", "status" => "success");					
				}
				else
				{
					
					$query 					= $this->db->query("CALL insert_widget_configuration('".$Instanceid."', '".addslashes($custom_title)."', ".$section_id.", '".$display_order."', '".$created_by."', @last_insert_id, '".$section_content_type."', '".$page_id."', '".$page_version_id ."')");
						
					
					$last_insert_query 		= $this->db->query("SELECT @last_insert_id");  
                    
					$last_insert_result 	= $last_insert_query->row_array();
					$widget_mainsection_id	= $last_insert_result['@last_insert_id']; 
					
					$result_msg = array("message" => "Successfully saved", "status" => "success");						
					
				}////////  Widget_mainsection_config				
				
				if(count(@$form_data['sub_section']) > 0)
				{					
					foreach($form_data['sub_section'] as $sub_key => $subsection)
					{
						$mainsection_id				= $widget_mainsection_id;   /////// mainsection_config ID						
						$custom_title 				= $subsection['custom_title']; //// Sub section custom title
						$section_id 				= ($form_data['section_id'] == '') ? 'null' : $form_data['section_id']; //////  Main section ID
						$sub_section_id 			= $subsection['section_id']; /////  Sub section ID
						$display_order 				= $subsection['display_order'];  // Sub section display order
						$created_by 				= $form_data['created_by'];
						
						$sub_config_search_key		= "";
						$main_config_search_key 	= ""; 
						$update_main_key			= array_search($sub_section_id,$sub_section_id_extra);
						if($update_main_key >= 0 && !is_bool($update_main_key))
						{
							$sub_config_search_key  = $widget_subconfig_id_extra_key[$update_main_key];
							$main_config_search_key = $main_config_id_extra[$update_main_key];
						}
						else
						{
							$sub_config_search_key = "";
							$main_config_search_key = "";
						}
						
						$result_msg = array("message" => "Successfully saved", "status" => "success");	
					}
					}////////  Widget_subsection_config				
				$j ++;
			} 
		}		
		return $result_msg;			
	}
	
	public function update_widgetinstance_renderingmode_temporarily($widget_instance_id, $rendering_mode, $widget_customtitle, $login_user_id, $widget_section_id, $widget_adscript, $widget_bgcolor, $widget_max_articles, $widget_showsummary, $widget_publishon, $widget_publishoff, $widget_status, $version_id, $is_cloned)
	{		
		
		//update_widget_instance_temporarily
		$Modifiedby 		= $login_user_id;		
		$upd_result 		= $this->db->query("CALL update_widget_instance_temporarily('".$widget_instance_id."', '', '', '', '', '', '".$Modifiedby."', '".$rendering_mode."', '".addslashes($widget_customtitle)."', '".$widget_section_id."', '".addslashes($widget_adscript)."', '".addslashes($widget_bgcolor)."', '".addslashes($widget_max_articles)."', '".$widget_showsummary."', '".$widget_publishon."', '".$widget_publishoff."', '".$widget_status."', '', '".$version_id."', '".$is_cloned."')");					
		if(mysql_error() == '')
		$result_msg = array("message" => "Successfully saved", "status" => "success");
		else
		$result_msg = array("message" => "Internal server error", "status" => "fail");
		
		return $result_msg;	
	}
	
	public function remove_widget_temporarily($widget_instance_id, $page_id)
	{		
		//delete_widget_instance_temporarily
		$modified_on		= date("Y-m-d H:i:s");
		$upd_result 		= $this->db->query("CALL remove_widget_instance_temporarily('".$widget_instance_id."', '".USERID."', '2', '".$modified_on."', @widget_name, '".$page_id."')");							
		$delete_instance	= $this->db->query("SELECT @widget_name") or die(mysql_error());  				
		$instance_name 		= $delete_instance->row_array();
		if(!mysql_error())
		{
			return array("widget_name"=>$instance_name['@widget_name'], "status"=>1);
		}
		else
		{
			return array("widget_name"=>"", "status"=>2);
		}
	}
	
	public function get_temporary_widget_mainsection_config($widget_mainsection_config_id, $widget_instance_id)
	{
		$select_query 	= $this->db->query("CALL get_temporary_widget_mainsection_config('".$widget_mainsection_config_id."', '".$widget_instance_id."', '".USERID."')");					
		$result			= $select_query->result_array();		
		return $result;
	}
	
	
}/* End of TemplateTemporaryInstance - Class */

class WidgetAddArticles extends Template_design_model
{
	public function list_out_published_articles($section_id, $contentype_id, $search_value, $search_by, $from_date, $to_date, $widget_instance_id, $current_widget_section_id)
	{		
		$is_have_section_mapping = false;
		switch($contentype_id)
		{
			case 1:
					$section_mapping_table = "articlesectionmapping";
					$is_have_section_mapping = true;
					break;			
			case 3:
					$section_mapping_table = "gallerysectionmapping";
					$is_have_section_mapping = true;
					break;
			case 4:
					$section_mapping_table = "videosectionmapping";
					$is_have_section_mapping = true;
					break;
			case 5:
					$section_mapping_table = "audiosectionmapping";
					$is_have_section_mapping = true;
					break;
			case 6:
					$section_mapping_table = "";
					$is_have_section_mapping = false;
					break;
			default:
					$section_mapping_table = "";
					$is_have_section_mapping = false;
					break;
		}
		
		$order_section_condition= ($section_id !='' && $is_have_section_mapping) ? " m.publish_start_date DESC, csp.content_id IN ( SELECT content_id FROM ".$section_mapping_table." WHERE Section_ID = ".$section_id." ORDER BY FIELD(Section_ID, ".$section_id.") DESC )  " : (($current_widget_section_id !='' && $is_have_section_mapping)? " m.publish_start_date DESC, csp.content_id IN ( SELECT content_id FROM ".$section_mapping_table." WHERE Section_ID = ".$current_widget_section_id." ORDER BY FIELD(Section_ID, ".$current_widget_section_id.") DESC )  " : " m.content_id desc ");
		
		$order_field 			= $order_section_condition;
		$order_by_type 			= " ";
		$starting_limit 		= " 0";
		$limit_upto				= " 60";
		$rows_from_date 		= $from_date;
		$rows_to_date 			= $to_date;
		$article_search_value 	= addslashes($search_value);
		$article_search_by 		= $search_by;  /////  $search_by -> Article Head Line(value:Title) or ContentId(value: ContentId)  //////
		$article_section_id 	= $section_id;
		$row_status 			= ($article_search_value =='') ? "P" : "";
		
		$article_manager =  $this->db->query('CALL widget_article_datatable(" '.addslashes($order_field).' '.$order_by_type.' LIMIT '.$starting_limit.', '.$limit_upto.'","'.$rows_from_date.'","'.$rows_to_date.'","'.addslashes($article_search_value).'","'.$article_search_by.'","'.$article_section_id.'","'.$row_status.'", "'.$contentype_id.'", "'.$widget_instance_id.'")');	
		//echo $this->db->last_query();exit;
		return $article_manager->result_array();
	}
	
	public function publish_individual_instance_articles($publishing_instance_articles, $widget_instance_id, $version_id )
	{
		$this->live_db->trans_begin();
		$this->live_db->query("CALL publish_individual_instance_articles('".addslashes($publishing_instance_articles)."', '".$widget_instance_id."', '".$version_id."')" ); 
		
		if( $this->live_db->trans_status() === FALSE ) 
		{
				$this->db->trans_rollback();			
				$this->live_db->trans_rollback();
				$return_msg =  array("status"=>false,"message"=>"Failed to publish","inserted_id"=>"", "instancecontent_id"=>""); 
		}
		else
		{
			$this->live_db->trans_commit();
			$return_msg =  array("status"=>true,"message"=> " Article successfully published", "inserted_id"=>'', "instancecontent_id"=>""); 
		}
		return $return_msg;
	}

	public function custom_image_upload($userid, $imagecontent_id,$contenttype, $caption, $alt_tag, $physical_name, $file_name, $image1_type, $image2_type, $image3_type, $image4_type, $display_order, $article_id, $instance_id, $mainSectionConfig_id)
	{
		
			$createdon 				= date('Y-m-d H:i:s');
			$modifiedon 			= date('Y-m-d H:i:s');
			$mainSectionConfig_id 	= ($mainSectionConfig_id == '') ? 'NULL' : $mainSectionConfig_id;
			$query 				= $this->db->query("CALL insert_custom_images_temporary('" . $userid . "'," . $imagecontent_id . ",'".$contenttype."','" . addslashes($caption) . "','" . addslashes($alt_tag) . "','".addslashes($physical_name)."','".addslashes($file_name)."',".$image1_type.",".$image2_type.",".$image3_type.",".$image4_type.",".$display_order.",'" . $createdon . "','" . $modifiedon . "',@insert_id,'" . $instance_id . "','" . $mainSectionConfig_id . "','" . $article_id . "')");
			
			$query 				= $this->db->query("SELECT @insert_id");
			$returnid 			= $query->result_array();
			
			if(isset($returnid[0]['@insert_id']) && $returnid[0]['@insert_id'] != '' ) {
			
			$data['image_id'] 				= $returnid[0]['@insert_id'];
			$data['imagecontent_id'] 		= $imagecontent_id;
			$data['caption'] 				= $caption;
			$data['alt_tag'] 				= $alt_tag;
			$data['physical_name'] 			= $physical_name;
			

			switch($contenttype) {
				case 1:
				$data['image'] 			= image_url.article_temp_image_path.$file_name;
				break;
				case 2:
				$data['image'] 			= image_url.imagelibrary_temp_image_path.$file_name;
				break;
				case 3:
				$data['image'] 			= image_url.gallery_temp_image_path.$file_name;
				break;
				case 4:
				$data['image'] 			= image_url.video_temp_image_path.$file_name;
				break;
				case 5:
				$data['image'] 			= image_url.audio_temp_image_path.$file_name;
				break;
				case 6:
				$data['image'] 			= image_url.resource_temp_image_path.$file_name;
				break;
			}
			
			$PhysicalExtension_array = explode('.',$file_name);
			$data['physical_extension'] = $PhysicalExtension_array[1];
			
			$data['image1_type'] = $image1_type; $data['image2_type'] =  $image1_type; $data['image3_type'] =   $image1_type; $data['image4_type'] =   $image1_type;
			
			} else {
				echo '{"type":1,"message":"Invalid image, please try again","line":0}';
				exit;
			}
			return $data;				
	}
	
	public function Insert_temp_from_image_library($ImageDetails, $content_id, $caption, $alt, $path, $contenttype, $article_id, $instance_id, $mainSectionConfig_id )
	{
		$Image1Type 	= $ImageDetails['Image1Type'];
		$Image2Type 	= $ImageDetails['Image2Type'];
		$Image3Type 	= $ImageDetails['Image3Type'];
		$Image4Type 	= $ImageDetails['Image4Type'];
		$createdon 		= $modifiedon = date('Y-m-d H:i:s');
		$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
		
		$query 				= $this->db->query("CALL insert_custom_images_temporary('" . USERID . "'," . $content_id . ",'".$contenttype."','" . addslashes($caption) . "','" . addslashes($alt) . "','".addslashes($PhysicalName)."','".addslashes($path)."',".$Image1Type.",".$Image2Type.",".$Image3Type.",".$Image4Type.",1,'" . $createdon . "','" . $modifiedon . "',@insert_id,'" . $instance_id . "','" . $mainSectionConfig_id . "','" . $article_id . "')");		
		
		$result 			= $this->db->query("SELECT @insert_id")->result_array();
		$image_temp_id 		= $result[0]['@insert_id'];
		$data['image_id'] 	= $image_temp_id;
		$data['source'] 	= image_url.article_temp_image_path.$path;
		
		$Physical_extension_array = explode(".",$path);
		
		$data['caption'] 	= $caption;
		$data['alt'] 		= $alt;
		
		$data['physical_name'] 		= $PhysicalName;
		$data['physical_extension'] = $Physical_extension_array[1];
		
		$data['imagecontent_id'] 		= $content_id;
		$data['image1_type'] = $Image1Type; 
		$data['image2_type'] = $Image2Type;
		$data['image3_type'] = $Image3Type;
		$data['image4_type'] = $Image4Type;
		
		return $data;
	}
	
	public function search_image_library($Caption)
	{				
		$Order = "ORDER BY Modifiedon desc LIMIT 0, 12";
		if( $Caption != '')
		$search = $this->db->query('CALL search_image_related_data("' . $Caption . '","'.$Order.'")');
		else
		$search = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		
		return $search->result();
	}
	
	public function search_image_library_scroll($page)
	{		
		$offset = ($page*12) - 12;
		$Caption =	$this->session->userdata('image_caption');
		$Order = "ORDER BY Modifiedon desc LIMIT ".$offset.", 12";
		if(	$Caption != '')
		$search = $this->db->query('CALL search_image_related_data("' . addslashes($Caption) . '","'.$Order.'")');
		else
		$search = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		
		return $search->result();
	}
	
	public function get_image_library()
	{
		
		$Order = "ORDER BY Modifiedon desc LIMIT 0, 16";
		$image = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		return $image->result();
	}
	
	public function add_image_by_temp_id($caption,$alt,$home_physical_name,$tempid) {

		$NewImageName		= '';
		$DestinationURL 	= imagelibrary_image_path;
							
		$Year = date('Y');
		$Month = date('n');
		$Day =  date('j');
			
		create_image_folder( $Year, $Month, $Day);
		$FolderMapping = $Year."/".$Month."/".$Day."/original/";
		
		$query = $this->temp_custom_image_details($tempid, '');
		$temp_image = $query->row_array();
		
		if(isset($temp_image['contenttype'])) {
			$TempSourceURL = article_temp_image_path;
		
			$query = $this->temp_custom_image_details($tempid, '');						
			$TempObject = $query->result();
			
			$Resize_Class = new Common_Resize_class();
			$Resize_Class->common_resize_all_images($TempObject);
		
		if((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ($temp_image['save_status'] == 2 ) || (  trim($temp_image['caption']) != trim($caption) || trim($temp_image['alt_tag']) != trim($alt) || trim($temp_image['physical_name']) != trim($home_physical_name) ) )
		{	
			$image_name = explode('.',$temp_image['image_name']);
			$NewImageName = $home_physical_name.'.'.$image_name[1];
			
			ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $TempSourceURL, $DestinationURL, $FolderMapping);
			
			$query = $this->temp_custom_image_details($tempid, '');
			
			$temp_image = $query->row_array();
		
			$temp_image['caption']      = trim($caption);
			$temp_image['alt_tag'] 		= trim($alt);
			$temp_image['image_name'] 	= $FolderMapping.$NewImageName;
			
			$image_details =  add_image_master($temp_image);						
			
			///// Delete the Temp Images in Table /////						
			$query = $this->db->query("CALL delete_temp_custom_image ('" . $tempid . "')");
			return $image_details ;
			
		} else {
			
			$ImageDetails = get_imagedetails_by_contentid($temp_image['imagecontent_id']);
			$PhysicalName = end(explode("/",$ImageDetails['ImagePhysicalPath']));
			
			$PhysicalPath = str_replace($PhysicalName,"",$ImageDetails['ImagePhysicalPath']);
			
			if(ImageDeleteAndPasteToLibrary($temp_image['image_name'],$PhysicalName, $TempSourceURL, $DestinationURL, $PhysicalPath)) {
				DeleteTempImage($temp_image['image_name'],$tempid, $TempSourceURL);
			}

			return $temp_image['imagecontent_id'];
			
		}
		
	}

	}
	
	public function get_image_details_from_library_by_content_id($image_library_id)
	{
		$image_details_query = $this->db->query("CALL get_imagedetails_by_contentid ('" . $image_library_id . "')");						
		$image_details		 = $image_details_query->row_array();
		return $image_details;
	}
	
	public function temp_custom_image_details($temp_table_image_id, $saved_image_id)
	{
		return $query = $this->db->query("CALL temp_custom_image_details('" . $temp_table_image_id . "','".$saved_image_id."')");
	}
	
	public function common_resize_all_images($ImageDetails) 
	{		
		try
		{
				$ArrayImage 		= $ImageDetails;	

				$physical_name     	= $ArrayImage['physical_name'];
				$image1_type 		= $ArrayImage['image1_type'];
				$image2_type 		= $ArrayImage['image2_type'];
				$image3_type 		= $ArrayImage['image3_type'];
				$image4_type 		= $ArrayImage['image4_type'];
				$image_name  		= $ArrayImage['image_name'];
				$imageid	 		= $ArrayImage['imageid'];
				$imagecontent_id 	= $ArrayImage['imagecontent_id'];
				$contenttype		= $ArrayImage['contenttype'];
				$caption   			= $ArrayImage['caption'];
				$alt_tag   			= $ArrayImage['alt_tag'];
				
				$TempSourceURL = article_temp_image_path;

				
				$Image600X390 	= str_replace(".","_600_390.", $image_name);
				$Image600X300 	= str_replace(".","_600_300.", $image_name);
				$Image100X65 	= str_replace(".","_100_65.", $image_name);
				$Image150X150 	= str_replace(".","_150_150.", $image_name);
				
				
				$image_binary_bool1 = false;
				$image_binary_bool2 = false;
				$image_binary_bool3 = false;
				$image_binary_bool4 = false;
				
				if (isset($image_name))
				{		
				
					$ImagePath = source_base_path.article_temp_image_path;
				
					$src 			= $ImagePath . $image_name;
					
					if(file_exists($src)) {
					$ImageDetails 				= getimagesize($src);
				
					$ImageExtension = explode("/",$ImageDetails['mime']);
					$extType 		= strtolower($ImageExtension[1]);
					
					if (!empty($src))
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
						$src_w 		= $size[0]; // natural width
						$src_h		= $size[1]; // natural height	
						
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image600X390)) {
					
						$dst_w 		= 600;
						$dst_h 		= 390;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_600_390.",$image_name);
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
							if($result) {
							
							if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype					= $ImageDetails['mime'];
								
									$image_binary_bool1 = true;
									
								
									$image1_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
				
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image600X300)) {
						
					
						$dst_w 		= 600;
						$dst_h 		= 300;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_600_300.",$image_name);
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
							if($result) {
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									$image_binary_bool2 = true;
									$image2_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image150X150)) {
					
						$dst_w 		= 150;
						$dst_h 		= 150;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_150_150.",$image_name);
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
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									
									$image_binary_bool4 = true;
									$image3_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if (!file_exists(source_base_path . $TempSourceURL . $Image100X65)) {
					
						$dst_w 		= 100;
						$dst_h 		= 65;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_100_65.",$image_name);
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
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									$image_binary_bool3 = true;
									$image4_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if($image_binary_bool1 == true || $image_binary_bool2 == true || $image_binary_bool3 == true || $image_binary_bool4 == true) {
							
							$imagecontent_id = 'NULL';
						
							$caption = str_replace("'",'"',$caption);
							$alt 	 =  str_replace("'",'"',$alt_tag);
					
							
							$createdon =  $modifiedon =  date('Y-m-d H:i:s');
							
							$query = $this->db->query("CALL update_full_temp_images('".$imageid."','" . USERID . "'," . $imagecontent_id . ",'".$contenttype."','" . addslashes($caption) . "','" . addslashes($alt_tag) . "','".addslashes($physical_name)."','".addslashes($image_name)."',".$image1_type.",".$image2_type.",".$image3_type.",".$image4_type.",1,'" . $createdon . "','" . $modifiedon . "')");
							
							imagedestroy($src_img);
							
						} 
					
					}
				  }
				  else{
						redirect(folder_name."/");
				  }
				}
				else
				{
					$result_value['status'] 	= 'error';
					$result_value['msg'] 		= "Invalid Image";
					echo json_encode($result_value);
				}
				
			
			return true;
		} catch(Exception $e){
			$result_value['status'] = 'error';
			$result_value['msg'] 	= 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	
	}
	
	public function common_resize_all_images_again($ImageDetails) 
	{		
		try
		{
				$ArrayImage 		= $ImageDetails;	

				$physical_name     	= $ArrayImage['physical_name'];
				$image1_type 		= $ArrayImage['image1_type'];
				$image2_type 		= $ArrayImage['image2_type'];
				$image3_type 		= $ArrayImage['image3_type'];
				$image4_type 		= $ArrayImage['image4_type'];
				$image_name  		= $ArrayImage['image_name'];
				$imageid	 		= $ArrayImage['imageid'];
				$imagecontent_id 	= $ArrayImage['imagecontent_id'];
				$contenttype		= $ArrayImage['contenttype'];
				$caption   			= $ArrayImage['caption'];
				$alt_tag   			= $ArrayImage['alt_tag'];
				
				$TempSourceURL = article_temp_image_path;

				
				$Image600X390 	= str_replace(".","_600_390.", $image_name);
				$Image600X300 	= str_replace(".","_600_300.", $image_name);
				$Image100X65 	= str_replace(".","_100_65.", $image_name);
				$Image150X150 	= str_replace(".","_150_150.", $image_name);
				
				
				$image_binary_bool1 = false;
				$image_binary_bool2 = false;
				$image_binary_bool3 = false;
				$image_binary_bool4 = false;
				
				if (isset($image_name))
				{		
				
					$ImagePath = source_base_path.article_temp_image_path;
				
					$src 			= $ImagePath . $image_name;
					
					if(file_exists($src)) {
					$ImageDetails 				= getimagesize($src);
				
					$ImageExtension = explode("/",$ImageDetails['mime']);
					$extType 		= strtolower($ImageExtension[1]);
					
					if (!empty($src))
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
						$src_w 		= $size[0]; // natural width
						$src_h		= $size[1]; // natural height
						if (true) {	
					
						$dst_w 		= 600;
						$dst_h 		= 390;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_600_390.",$image_name);
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
							if($result) {
							if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype					= $ImageDetails['mime'];
								
									$image_binary_bool1 = true;
									
								
									$image1_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
				
						if (true) {	
						
					
						$dst_w 		= 600;
						$dst_h 		= 300;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_600_300.",$image_name);
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
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									$image_binary_bool2 = true;
									$image2_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if (true) {	
					
						$dst_w 		= 150;
						$dst_h 		= 150;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_150_150.",$image_name);
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
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									
									$image_binary_bool4 = true;
									$image3_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if (true) {	
					
						$dst_w 		= 100;
						$dst_h 		= 65;
						
						$dst_img  	= imagecreatetruecolor($dst_w, $dst_h);
						$dst 		= $ImagePath. str_replace(".","_100_65.",$image_name);
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
							if($result) {
							
								if(imagejpeg($dst_img, $dst)) {
									$ImageDetails 				= getimagesize($dst);
									$width 						= $ImageDetails[0];
									$height 					= $ImageDetails[1];
									$size 						= $ImageDetails['bits'];
									$imagetype 					= $ImageDetails['mime'];
									
									$image_binary_bool3 = true;
									$image4_type 		= 1;
									
									imagedestroy($dst_img);	
								}
							}
						}
						
						if($image_binary_bool1 == true || $image_binary_bool2 == true || $image_binary_bool3 == true || $image_binary_bool4 == true) {
							
							$imagecontent_id = 'NULL';
						
							$caption = str_replace("'",'"',$caption);
							$alt 	 =  str_replace("'",'"',$alt_tag);
					
							
							$createdon =  $modifiedon =  date('Y-m-d H:i:s');
							
							$query = $this->db->query("CALL update_full_temp_images('".$imageid."','" . USERID . "'," . $imagecontent_id . ",'".$contenttype."','" . addslashes($caption) . "','" . addslashes($alt_tag) . "','".addslashes($physical_name)."','".addslashes($image_name)."',".$image1_type.",".$image2_type.",".$image3_type.",".$image4_type.",1,'" . $createdon . "','" . $modifiedon . "')");
							
							imagedestroy($src_img);
							
						} 
					
					}
				  }
				  else{
						redirect(folder_name."/");
				  }
				}
				else
				{
					$result_value['status'] 	= 'error';
					$result_value['msg'] 		= "Invalid Image";
					echo json_encode($result_value);
				}
				
			
			return true;
		} catch(Exception $e){
			$result_value['status'] = 'error';
			$result_value['msg'] 	= 'Caught exception: ' . $e->getMessage() . "\n";
			echo json_encode($result_value);
		}
	
	}
	
	public function update_custom_crop_image($ContentImageId, $crop_caption, $crop_alt, $image_600X390_type,$image_600X300_type,$image_100X65_type,$image_150X150_type, $modifiedon, $crop_image_id, $imagetype, $commit_status)
	{
		$CI = & get_instance();
		
		$crop_caption = str_replace("'",'"',$crop_caption);
		$crop_alt	=	 str_replace("'",'"',$crop_alt);
		
		$query =  $CI->db->query("CALL update_custom_crop_image('". $ContentImageId ."','" . $crop_caption . "','" . $crop_alt . "','" . $image_600X390_type . "','" . $image_600X300_type . "','" . $image_100X65_type . "','" . $image_150X150_type . "','" . $modifiedon . "','" . $crop_image_id . "','" . $imagetype . "','" . $commit_status . "')");
		 
		 return $query;
		
	}
	
	public function delete_temp_custom_image($custom_image_tempid)
	{
		$query = $this->db->query("CALL delete_temp_custom_image ('" . $custom_image_tempid . "')");
	}
	
	public function change_ischanges_published($instance_id, $flag_status)
	{
		$insert_date = date("Y-m-d H:i:s", time());
		// Update the value of `is_changes_are_published` in widgetinstance table
		$this->db->query("CALL change_ischanges_published('".$instance_id."', '".USERID."', '".$flag_status."', '".$insert_date."' )");  
	}
	
}/* End of WidgetAddArticles - Class */

class SupportedImageSizes extends Template_design_model
{
	function get_widget_supported_image_sizes($widget_instance_id)
	{
		$query = $this->db->query("CALL get_widget_supported_image_sizes ('" . $widget_instance_id . "')");
		return $query->result_array();
	}
}/* End of SupportedImageSizes - Class */

class WidgetCloning extends Template_design_model
{
	function get_cloned_instance_details($cloned_widget_instance)
	{
		//Verify current widget instance is cloned or not
		$get_clone = $this->db->query("CALL get_cloned_instance_details ('" . $cloned_widget_instance . "')");
		$clone_instance_details =  $get_clone->row_array();	
		return $clone_instance_details;
	}
	function clone_widget_instance($cloned_widget_instance, $cloned_widget_id)
	{
		//Verify current widget instance is cloned or not		
		$clone_instance_details = $this->get_cloned_instance_details($cloned_widget_instance);
		
		if(count($clone_instance_details) == 0){
			$curent_date_time = date("Y-m-d H:i:s", time());
			$create_clone = $this->db->query("CALL create_clone_instance ('" . $cloned_widget_instance . "', '" . USERID . "', '" . $curent_date_time . "', '1', '" . $cloned_widget_id . "', @out_cloned_from)");
			
			if(mysql_error() == ''){
				$cloned_from 	= $this->db->query("SELECT @out_cloned_from")->row_array();				
				$clone_msg	= array("msg" => "This widget cloned successfully", "msg_type" => 1, "show_msg"=>1, "res_status"=>1, "cloned_widget_from" => $cloned_from['@out_cloned_from']);		
			}
			else
				$clone_msg	= array("msg" => "Failed to clone this widget", "msg_type" => 2, "show_msg"=>1, "res_status"=>0, "cloned_widget_from" => "");
		}else{
				 if($clone_instance_details['status'] == 1)
					$clone_msg	= array("msg" => "This widget is already cloned", "msg_type" => 2, "show_msg"=>1, "res_status"=>0, "cloned_widget_from" => "");
				 else
					$clone_msg	= array("msg" => "This cloned widget is inactivated", "msg_type" => 2, "show_msg"=>1, "res_status"=>0, "cloned_widget_from" => "");
		}
		return $clone_msg; 		
		
	}
	
	function get_clone_mapping_details($clone_instance_id){
		//CMS DB
		$get_clone_mapping = $this->db->query("CALL get_clone_mapping_details ('" . $clone_instance_id . "')");	
		$clone_map_details =  $get_clone_mapping->result_array();
		return $clone_map_details;
	}
	function get_frontend_clone_mapping_details($clone_instance_id){		
		//FRONTEND DB
		$get_clone_mapping_live = $this->live_db->query("CALL get_clone_mapping_details_live ('" . $clone_instance_id . "')");		
		$clone_map_details_live =  $get_clone_mapping_live->result_array();
	
		return $clone_map_details_live;
	}
}/* End of WidgetCloning - Class */

class RestoreTemplate extends Template_design_model
{
	public function restore_last_updated_design($page_id, $version_id)
	{
		$date_time = date('Y-m-d H:i:s', time());
		$this->db->query("CALL restore_last_updated_design('". $page_id."', '". $version_id."', '".USERID."', '".$date_time."')");		
		if(mysql_error() == '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}/* End of RestoreTemplate - Class */

/* End of template_design_model.php File */
?>
