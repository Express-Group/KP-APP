<?php

/**
 * Release_template_locks Model Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	Template Designer Developer
 */

class  Release_template_locks_model extends CI_Model {

	public function list_all_admin_users()
	{
		$class_object = new AdminUsers;
		return $class_object->list_all_admin_users();
	}
	public function multiple_section_mapping()
	{
		$class_object = new SectionList;
		return $class_object->multiple_section_mapping();
	}
	public function list_out_template_locks($search_by_user_id, $search_by_lock_type, $search_by_section_id, $search_by_page_type)
	{
		$class_object = new ReleaseLockedTemplates;
		return $class_object->list_out_template_locks($search_by_user_id, $search_by_lock_type, $search_by_section_id, $search_by_page_type);
	}
	public function release_locks_by_lock_type($release_post_object)
	{
		$class_object = new ReleaseLockedTemplates;
		return $class_object->release_locks_by_lock_type($release_post_object);
	}
	public function create_release_lock_log($request_url, $release_post_object, $emsg, $last_query_executed, $locked_user_id, $release_lock_type, $release_lock_section_id, $release_lock_section_type, $release_lock_instance_id, $release_lock_section_version_id)
	{
		$class_object = new LogHistoryOfReleaseLocks;
		return $class_object->create_release_lock_log($request_url, $release_post_object, $emsg, $last_query_executed, $locked_user_id, $release_lock_type, $release_lock_section_id, $release_lock_section_type, $release_lock_instance_id, $release_lock_section_version_id);
	}
	
	
}/* End of Release_template_locks_model - Class */

class AdminUsers extends Release_template_locks_model
{
	public function list_all_admin_users()
	{
		$admin_users_list = $this->db->query("SELECT `User_id`, IF(`Firstname` = '' AND `Lastname` = '', Username, CONCAT(`Firstname`, ' ', `Lastname`)) AS admin_user_name  FROM `usermaster` ORDER BY `User_id` ASC");
		return $admin_users_list->result_array();
	}
}/* End of AdminUsers - Class */

class SectionList extends Release_template_locks_model
{
	////////////// Get all available menus / Sections from sectionmaster table   //////////
	public function multiple_section_mapping()
	{		
		$empty_val = '';		  
		$list_multi_sectn 	= $this->db->query('CALL get_section_template_designer("'.$empty_val.'")');		
		$get_result 		= $list_multi_sectn->result_array();
		
		foreach($get_result as $key => $get_multi_section)
		{			
			$get_sec_id 					= $get_multi_section['Section_id'];
			$list_multi_sectn 				= $this->db->query('CALL get_section_template_designer("'.$get_sec_id.'")');					
			$subsection_page['special_section'] = '';
			$get_multi_section['sub_section'] = '';
			if($list_multi_sectn->num_rows() > 0)
			{
				$get_multi_section['sub_section'] 	= $list_multi_sectn->result_array();
				
				foreach($get_multi_section['sub_section'] as $subkey => $subsection_page)
				{				
					$get_subsec_id 					= $subsection_page['Section_id'];
					///// Is Special Menu /// 				
					$special_section_details		= $this->db->query('CALL get_seperatemenu ("'.$subsection_page['IsSeperateWebsite'].'", "'.$get_subsec_id.'")')->result_array();
					$specila_list = array();
					foreach($special_section_details as $splkey => $special_section)
					{
						$get_splsec_id 					= $special_section['Section_id']; 
						///////  Add Specialsection to main Subsection array object  /////
						$specila_list[$splkey] = $special_section;	
					}				
					$subsection_page['special_section'] = $specila_list;
					///////  Add Subsection to main section array object  /////
					$get_multi_section['sub_section'][$subkey] = $subsection_page;			
							
				}				
			}
			
			$get_result[$key] = $get_multi_section;
		}
		
		/////  for Standard Article page  ////////		  
		/* From CMS db */
		array_push($get_result,array(
										"Section_id" =>'10000',
										"Sectionname" =>'Standard Article',
										"DisplayOrder" =>'0',
										"Section_landing" =>'2',
										"IsSeperateWebsite" =>'',
										"LinkedToColumnist" =>'',										
										));												
		return $get_result;
	}
}

class ReleaseLockedTemplates extends Release_template_locks_model
{
	public function list_out_template_locks($search_by_user_id, $search_by_lock_type, $search_by_section_id, $search_by_page_type)
	{
		////  List all templates which are in lock status = 2  //////
		$all_locks_details = $this->db->query("CALL list_out_template_locks('".$search_by_user_id."','".$search_by_lock_type."','".$search_by_section_id."','".$search_by_page_type."')");		
		return $all_locks_details->result_array();
	}
	public function release_locks_by_lock_type($release_post_object)
	{
		$request_url 			= uri_string();
		$this->db->trans_begin();
		foreach($release_post_object as $key => $object)
		{
			$this->db->query("CALL release_locks_by_lock_type('".$object['release_lock_type']."', '".$object['release_lock_section_id']."', '".$object['release_lock_section_type']."', '".$object['release_lock_instance_id']."', '".$object['release_lock_section_version_id']."', '".USERID."')");
			
			////  Add log of release lock functionality  ////		
			$last_query_executed 	= $this->db->last_query();
			$this->create_release_lock_log($request_url, '', '', $last_query_executed, $object['locked_userId'], $object['release_lock_type'], $object['release_lock_section_id'], $object['release_lock_section_type'], $object['release_lock_instance_id'], $object['release_lock_section_version_id']);
		}
		
		if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$emsg	= array("msg"=>"Failed to release locks", "msg_type"=>2, "show_msg"=>1, "access_rights"=>"");
		}		
		else {
			$this->db->trans_commit();			
			$emsg	= array("msg"=>"Successfully lock(s) released", "msg_type"=>1, "show_msg"=>1, "access_rights"=>"");
		}
		
		////  Add log of release lock functionality  ////		
		$last_query_executed 	= ($last_query_executed) ? $last_query_executed : $this->db->last_query();
		$this->create_release_lock_log($request_url, $release_post_object, $emsg, $last_query_executed, '', '', '', '', '', '');
		
		return $emsg;
	}
}/* End of WidgetAddArticles - Class */

class LogHistoryOfReleaseLocks extends Release_template_locks_model
{
	public function create_release_lock_log($request_url, $release_post_object, $response_msg, $last_query_executed, $locked_user_id, $release_lock_type, $release_lock_section_id, $release_lock_section_type, $release_lock_instance_id, $release_lock_section_version_id )
	{
		$user_acces_rights 	= (FPM_AddReleaseLocks) ? '1' : '2';
		$date_time 			= date('Y-m-d H:i:s', time());
		$this->db->query("CALL create_release_lock_log('".USERID."','".addslashes($request_url)."', '".addslashes(json_encode($release_post_object))."', '".addslashes(json_encode($response_msg))."', '".$date_time."', '".$user_acces_rights."', '".addslashes($last_query_executed)."','', '".$locked_user_id."', '".$release_lock_type."', '".$release_lock_section_id."', '".$release_lock_section_type."', '".$release_lock_instance_id."', '".$release_lock_section_version_id."' )");
		if(mysql_error() == '')
		{
			//return true;
		}
		else
		{
			$database_error = mysql_error();
			$this->db->query("CALL create_release_lock_log('' ,'' ,'' ,'' ,'' ,'','".addslashes($last_query_executed)."' ,'".addslashes($database_error)."','' ,'' ,'' ,'' ,'', '' )");
			//return false;
		}
	}
}/* End of WidgetAddArticles - Class */
/* End of release_template_locks_model.php File */
/* Location: ./application/models/admin/release_template_locks_model.php */
?>
