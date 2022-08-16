<?php

$db_data = $all_locked_templates_list;
$table_data = array();

///////  Show articles not added in the widget instance articles ////////
if(count($db_data) > 0)
{
	$data_table_row_id_increment = 0;
	foreach($db_data as $key => $template_lock_value)
	{
		$data_table_row_id_increment ++;		
		
		/* For Hidden valuse */
		$lock_type 			= $template_lock_value['row_lock_type'];
		$section_id 		= $template_lock_value['RowSectionId'];
		$section_page_type 	= $template_lock_value['RowSectionType'];
		$instance_id		= $template_lock_value['RowInstanceId'];
		$version_id			= $template_lock_value['RowVersionId'];
		
		$section_name	= ($section_id == '10000' || $section_id == '10001') ? $template_lock_value['Sectionname'] : GenerateBreadCrumbBySectionId($section_id);
		
		$page_name	= $section_name;
		$page_type  = $template_lock_value['LockedPageType'];
									
		$ajax_data['ID'] 					= $data_table_row_id_increment;
		$check_box_id						= $ajax_data['ID'];
		$ajax_data['User Name'] 			= $template_lock_value['Locked_username'];		
		$ajax_data['Lock Type'] 			= $template_lock_value['TypeOfLock'];
		$ajax_data['Section Name'] 			= $page_name;
//		$ajax_data['Widget Name'] 			= ($lock_type == 2 || $lock_type == 3 || $lock_type == 5 ) ? $template_lock_value['LockWidgetName']." -ID:".$instance_id : "-";		
		$ajax_data['Widget Name'] 			= ($lock_type == 2 || $lock_type == 3 || $lock_type == 5 ) ? $template_lock_value['LockWidgetName'] : "-";		
		$ajax_data['Page Type']				= $page_type;
		$ajax_data['Version']				= $template_lock_value['PageVersionName'];			
		$ajax_data['Design Commit Status'] 	= ($template_lock_value['DesignCommitStatus'] == '1')? '<a title="Saved" href="javascript:;" style="cursor: default;" ><i class="fa fa-check"></i></a>' : '<a  title="Not saved" href="javascript:;" style="cursor: default;"><i class="fa fa-times"></i></a>';				
		
		if(USERID == $template_lock_value['current_locked_user_id']){
			$ajax_data['Action'] 			= '';			
		}
		else{
			
			$ajax_data['Action'] 			= '
			<input type="hidden" 	name="lock_type_'.$check_box_id.'" 			id="lock_type_'.$check_box_id.'" 		value="'.$lock_type.'" />
			<input type="hidden" 	name="section_id_'.$check_box_id.'" 		id="section_id_'.$check_box_id.'" 		value="'.$section_id.'" />
			<input type="hidden" 	name="page_type_'.$check_box_id.'" 			id="page_type_'.$check_box_id.'" 		value="'.$section_page_type.'" />
			<input type="hidden" 	name="instance_id_'.$check_box_id.'" 		id="instance_id_'.$check_box_id.'" 		value="'.$instance_id.'" />
			<input type="hidden" 	name="version_id_'.$check_box_id.'" 		id="version_id_'.$check_box_id.'" 		value="'.$version_id.'" />
			<input type="hidden" 	name="designcommit_status" 					id="designcommit_status'.$check_box_id.'"value="'.$template_lock_value['DesignCommitStatus'].'" />
			<input type="hidden" 	name="locked_userId" 						id="locked_userId'.$check_box_id.'"		 value="'.$template_lock_value['current_locked_user_id'].'" />
			<input type="checkbox" 	name="release_check_id" 					id="release_check_id_'.$check_box_id.'"  value="'.$ajax_data['ID'].'" class="release_check_box"> 
			<button type="button" 	name="individual'.$check_box_id.'" 			id="'.$check_box_id.'" class="btn btn-primary individual_release_lock">Release Lock</button>
			';
		}
		
		$table_data[] = $ajax_data;
	}

}


$json_data['data'] = $table_data;
echo json_encode($json_data);

?>                   