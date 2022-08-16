<?php

/**
 * Cloned_widgets_model Model Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	Template Designer Developer
 */

class  Cloned_widgets_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();	
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.		
		$this->live_db = $this->load->database('live_db', TRUE);
	}
	
	public function list_all_cloned_parent_widgets()
	{
		$class_object = new CloneParentWidgets;
		return $class_object->list_all_cloned_parent_widgets();
	}
	public function list_out_cloned_children($parent_clone_id)
	{
		$class_object = new CloneChildWidgets;
		return $class_object->list_out_cloned_children($parent_clone_id);
	}
	
	public function list_out_published_cloned_children($parent_clone_id)
	{
		$class_object = new CloneChildWidgets;
		return $class_object->list_out_published_cloned_children($parent_clone_id);
	}
	
}/* End of Cloned_widgets_model - Class */

class CloneParentWidgets extends Cloned_widgets_model
{
	function list_all_cloned_parent_widgets()
	{
		$list_all_parent_clone 	= $this->db->query('CALL list_all_cloned_parent_widgets()');		
		$get_result 			= $list_all_parent_clone->result_array();
		return $get_result;
	}
}/* End of CloneParentWidgets - Class */
class CloneChildWidgets extends Cloned_widgets_model
{
	function list_out_cloned_children($parent_clone_id)
	{
		$list_clone_children 	= $this->db->query('CALL list_out_cloned_children("'.$parent_clone_id.'")');		
		$get_result 			= $list_clone_children->result_array(); 				
		return $get_result;
	}
	
	function list_out_published_cloned_children($parent_clone_id)
	{
		if($parent_clone_id == 'all'){
			$all_cloned_parents	= $this->cloned_widgets_model->list_all_cloned_parent_widgets();			
			$cloned_parents = join(',',array_column($all_cloned_parents, "cloned_instance_id"));
		}else{
			$cloned_parents	= $parent_clone_id;
		}
		$publishe_clone_children 	= $this->live_db->query('CALL list_out_published_cloned_children("'.$cloned_parents.'")');		
		$get_result 				= $publishe_clone_children->result_array();		
		
		return $get_result;
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
}/* End of CloneChildWidgets - Class */
/* End of Cloned_widgets_model.php File */
/* Location: ./application/models/admin/Cloned_widgets_model.php */
?>
