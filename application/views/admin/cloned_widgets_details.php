<?php

$db_data = $cloned_children_list;
$table_data = array();
$data_table_row_id_increment = 0;
///////  Show articles not added in the widget instance articles ////////
if(count($db_data) > 0)
{
	foreach($db_data as $key => $cloned_children_value)
	{
		$data_table_row_id_increment ++;		
		$section_name	= ($cloned_children_value['Pagesection_id'] == '10000' || $cloned_children_value['Pagesection_id'] == '10001') ? $cloned_children_value['Sectionname'] : GenerateBreadCrumbBySectionId($cloned_children_value['Pagesection_id']);
		$parent_config	= explode(",",$cloned_children_value['Cloned_config']);
		$rendering_mode	= $parent_config[0];
		$max_articles	= $parent_config[1];
		$published_on	= ($parent_config[2] == "0000-00-00 00:00:00") ? "-" : $parent_config[2];
		$published_off	= ($parent_config[3] == "0000-00-00 00:00:00") ? "-" : $parent_config[3];
		$active_status	= $parent_config[4];
		
		$ajax_data['ID'] 					= $data_table_row_id_increment;		
		$ajax_data['Widget Name'] 			= $cloned_children_value['widgetName'];				
		$ajax_data['Section Name'] 			= $section_name;		
		$ajax_data['Page Type']				= $cloned_children_value['Page_type'];
		$ajax_data['Version']				= $cloned_children_value['PageVersionName'];			
		$ajax_data['No of children(cms)']	= $cloned_children_value['TotalCount'];					
		//$ajax_data['No of children(live)']	= $cloned_children_value['TotalCount'];					
		$ajax_data['Image']					= '<a href="javascript:void()" class="wid-click custom-img-hover"><i class="fa fa-picture-o"></i><div class="img-hover"><img title="'.$ajax_data['Widget Name'].'" src="'.base_url().'images/admin/template_design/images/widget_images/'.$cloned_children_value['widgetfilePath'].'-small.jpg"></div></a>';
		
		
		
		$ajax_data['Parent container id']	= $cloned_children_value['cloned_from'];
		//$ajax_data['Rendering max article']	= ucfirst($rendering_mode)." (".$max_articles.")";
		$ajax_data['Published on']			= $published_on;
		$ajax_data['Published off']			= $published_off;
		$ajax_data['Active status']			= ($active_status == '1')? '<a title="Active" href="javascript:;" style="cursor: default;" ><i class="fa fa-check"></i></a>' : '<a  title="Inactive" href="javascript:;" style="cursor: default;"><i class="fa fa-times"></i></a>';;
		
		$table_data[] = $ajax_data;
	}

}

$db_published_children = $published_children_list;

if(count($db_published_children) > 0)
{
	//$data_table_row_id_increment = 0;
	foreach($db_published_children as $key => $cloned_children_value)
	{
		$data_table_row_id_increment ++;		
		$section_name	= ($cloned_children_value['Pagesection_id'] == '10000' || $cloned_children_value['Pagesection_id'] == '10001') ? $cloned_children_value['Sectionname'] : GenerateBreadCrumbBySectionId($cloned_children_value['Pagesection_id']);
		$parent_config	= explode(",",$cloned_children_value['Cloned_config']);
		$rendering_mode	= $parent_config[0];
		$max_articles	= $parent_config[1];
		$published_on	= ($parent_config[2] == "0000-00-00 00:00:00") ? "-" : $parent_config[2];
		$published_off	= ($parent_config[3] == "0000-00-00 00:00:00") ? "-" : $parent_config[3];
		$active_status	= $parent_config[4];
		
		$ajax_data['ID'] 					= $data_table_row_id_increment;		
		$ajax_data['Widget Name'] 			= $cloned_children_value['widgetName'];				
		$ajax_data['Section Name'] 			= $section_name;		
		$ajax_data['Page Type']				= $cloned_children_value['Page_type'];
		$ajax_data['Version']				= "<span style='color:#F30'>".$cloned_children_value['PageVersionName']."</span>";
		$ajax_data['No of children(cms)']	= $cloned_children_value['TotalCount'];					
		//$ajax_data['No of children(live)']	= $cloned_children_value['TotalCount'];					
		$ajax_data['Image']					= '<a href="javascript:void()" class="wid-click custom-img-hover"><i class="fa fa-picture-o"></i><div class="img-hover"><img title="'.$ajax_data['Widget Name'].'" src="'.base_url().'images/admin/template_design/images/widget_images/'.$cloned_children_value['widgetfilePath'].'-small.jpg"></div></a>';
		
		
		
		$ajax_data['Parent container id']	= $parent_config[5];		
		$ajax_data['Published on']			= $published_on;
		$ajax_data['Published off']			= $published_off;
		$ajax_data['Active status']			= ($active_status == '1')? '<a title="Active" href="javascript:;" style="cursor: default;" ><i class="fa fa-check"></i></a>' : '<a  title="Inactive" href="javascript:;" style="cursor: default;"><i class="fa fa-times"></i></a>';;
		
		$table_data[] = $ajax_data;
	}

}




$json_data['data'] = $table_data;
echo json_encode($json_data);

?>                   