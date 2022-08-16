<?php
// $instance_added_article -- object having instance added articles ///
/*header("content-type: application/json");
echo json_encode($instance_added_article);
exit;*/

//print_r($all_available_articles); 
$db_data = $all_available_articles;
$table_data = array();
$temp_article_list = "";
//echo $related_content_id; exit;
$redirect_url_array = array(folder_name."/edit_article/"  => 1, folder_name."/edit_gallery/" => 3, folder_name."/edit_video/" => 4, folder_name."/edit_audio/" => 5, folder_name."/edit_resources/" => 6);
switch($contenttype_id)
{
	case 1:
		$article_type_redirect_url = folder_name."/edit_article/";
	break;		
	
	case 3:
		$article_type_redirect_url = folder_name."/edit_gallery/";
	break;
	
	case 4:
		$article_type_redirect_url = folder_name."/edit_video/";
	break;
	
	case 5:
		$article_type_redirect_url = folder_name."/edit_audio/";
	break;
	
	case 6:
		$article_type_redirect_url = folder_name."/edit_resources/";
	break;
	
	case "all":
		$article_type_redirect_url = folder_name."/edit_article/";
	break;
	
	default :
	break;	
}


//////  Find whether current widget section have any subsections  /////////
//$subsection_ids			= array();
/* if(@$current_widget_section_id != '')
{
	$subsection_details 	= $this->widget_model->get_section_menudisplay(@$current_widget_section_id);		
	foreach($subsection_details as $subsection_id)
	{
		$subsection_ids[] = $subsection_id['Section_id'];
	}
} */

/* Show if any custom articles list available */
$data_table_row_id_increment = 0;
if(count($instance_added_article) > 0)
{
	foreach($instance_added_article as $instance_key => $instance_article_value)
	{
		//print_r($instance_article_value); 
		
		$subsection_ids			= array();
		
		
		if($instance_article_value['Status'] == '1')
		{
			
			//$article_value					= $this->template_design_model->required_widget_content_by_id($instance_article_value['content_id'], "");
			$article_value					= $this->template_design_model->list_out_published_articles('', $instance_article_value['content_type_id'], $instance_article_value['content_id'], 'ContentId', '', '', $instance_article_value['WidgetInstance_id'], $current_widget_section_id);			
			
			if(count($article_value)>0){
			$data_table_row_id_increment ++;	
			$search_bysection_local = ($search_bysection == '') ? $article_value[0]['Section_id'] : (( $search_bysection != '' ) ? $search_bysection : @$current_widget_section_id);				
			
			if(@$search_bysection_local != '')
			{
				
				$subsection_details 	= $this->widget_model->get_section_menudisplay(@$search_bysection_local);		
				foreach($subsection_details as $subsection_id)
				{
					$subsection_ids[] = $subsection_id['Section_id'];
				}
			}
			$search_bysection_local = (count($subsection_ids) > 0) ? $article_value[0]['Section_id'] : $search_bysection_local;
			
			$temp_article_list				= $instance_article_value['instancecontent_tempID'].",".$temp_article_list;
			$custom_summary					= ($instance_article_value['CustomSummary'] !='')? $instance_article_value['CustomSummary'] :'';
			$ajax_data['ID'] 				= $data_table_row_id_increment;
			$ajax_data['ContentId']			= $instance_article_value['content_id'];	
			$ajax_data['Title'] 			= ($instance_article_value['CustomTitle'] !='')? $instance_article_value['CustomTitle'] :  @$article_value[0]['Title'];
			
			$custom_title = $ajax_data['Title'];
		
			$show_article					= strip_tags($ajax_data['Title']);
			$show_article_length			= (mb_strlen($show_article) <= 60) ? mb_strlen($show_article) : '60';
			$show_article					= mb_substr($show_article, 0, $show_article_length);
			//$ajax_data['Title'] 			= $instance_article_value[0]['CustomTitle'];
			$section_bread_crumb			= GenerateBreadCrumbBySectionId(@$article_value[0]['Section_id']);
			//$ajax_data['Section'] 			= @$article_value[0]['URLSectionStructure'];	
			$ajax_data['Section'] 			= @$section_bread_crumb;	
			//$ajax_data['Article Date'] 		= $instance_article_value[0]['Unpublishedon'];
			$ajax_data['Article Date'] 		= @$article_value[0]['Modifiedon'];
			$image_blob						= "";
			if($instance_article_value['Imagename'] != '')
			{
				$image_blob					= $instance_article_value['Imagename'];
			}
			/*else if(@$article_value[0]['homepageimageid'] != '')
			{
				//$image_blob					= (@$article_value[0]['homepageimageid'] != '') ? get_image_by_contentid(@$article_value[0]['homepageimageid']) : '';	
				$image_blob					= '';	
			}*/								
			
			
			////// Hover on media button Image will appear  ////////
			//$ajax_data['Media'] 			= ($instance_article_value['Imagename'] == '' && $instance_article_value['Image'] == '' && @$article_value[0]['ImageAvailable'] != "Y") ? ' - ' : '<span class="wid-click" ><i class="fa fa-picture-o"></i></span>';
			$custom_image_path	= $instance_article_value['custom_image_path'];
			$custom_image_id	= ($instance_article_value['customimage_id'] == 0) ? '' : $instance_article_value['customimage_id'];
			if($custom_image_id != ''){
				$custom_image_caption 	= htmlspecialchars($instance_article_value['custom_image_title'], ENT_QUOTES);				 
				//$custom_image_caption 	= urlencode($instance_article_value['custom_image_title']);				
				$custom_image_alt 		= htmlspecialchars($instance_article_value['custom_image_alt'], ENT_QUOTES);
				$custom_image_name 		= explode("/", $custom_image_path);
				$custom_image_name		= explode(".", $custom_image_name[count($custom_image_name) -1 ]);
				$custom_image_name		= htmlspecialchars($custom_image_name[0], ENT_QUOTES);
			}
			else
			{
				$custom_image_caption = '';
				$custom_image_alt = '';				
				$custom_image_name = '';
			}
			
			$ajax_data['Media'] 			= ($custom_image_id != '') ? '<a href="javascript:void()" class="wid-click custom-img-hover" ><i class="fa fa-picture-o"></i><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.$custom_image_path.'" ></div></a>' : (($article_value[0]['ImagePhysicalPath'] != "") ? '<a href="javascript:void()" class="wid-click custom-img-hover" ><i class="fa fa-picture-o"></i><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.$article_value[0]['ImagePhysicalPath'].'" ></div></a>' : ' - ');
			
	
			
			//$ajax_data['Media'] 			= '<a href="javascript:void()" class="wid-click" ><i class="fa fa-picture-o"></i></a><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.$custom_image_path.'" ></div>';
			
			
			//<div class="img-hover"><img src="
			
			$ajax_data['Related Article'] 	= (true) ? ' - ' : '<span class="wid-click" ><i class="fa fa-th"></i></span>';
			
			/////  content version ID : $article_value['contentversion_id'] ////			
			$mapped_sections				= array();
			$is_sub_section					= in_array(@$article_value[0]['Section_id'], $subsection_ids);
			if($is_sub_section)
			{
				//// do nothing - means show action column ///
			}
			else
			{				
				///////  Find this article is have multimapping with this section  /////
				if($current_widget_section_id != '' && $current_widget_section_id != $article_value[0]['Section_id'])
				{
					$subsection_mapped 		= false;				
					$section_details 		= $this->template_design_model->get_section_by_id(@$current_widget_section_id);				
					$get_article_mapping 	= $this->template_design_model->get_content_mapping($article_value[0]['Section_id'], $article_value[0]['content_type_id'], $ajax_data['ContentId'])->result();	
					foreach($get_article_mapping as $multiple_mapping)
					{
						// Mapped section Id variable - $multiple_mapping->Section_ID
						// Article section Id variable - $multiple_mapping->Section_id
						$mapped_sections[] 	  = $multiple_mapping->Section_ID;
						$is_subsection_mapped = in_array($multiple_mapping->Section_ID, $subsection_ids);
						if($is_subsection_mapped)
						{
							$subsection_mapped = true;
						}
					}
					if(( in_array( $current_widget_section_id, $mapped_sections )) || $subsection_mapped )
					{											
						//$ajax_data['Section'] = $article_value[0]['URLSectionStructure']." <br> (Mapped to : ".$section_details['URLSectionStructure']." )";
						$ajax_data['Section'] = $section_bread_crumb." <br> (Mapped to : ".GenerateBreadCrumbBySectionId( $section_details['Section_id'] )." )";
					}
					else
					{						
						$ajax_data['Section'] = $section_bread_crumb;
					}
				}
			}

			//<input type="hidden" name="custom_image_caption'.$ajax_data['ContentId'].'" id="custom_image_caption'.$ajax_data['ContentId'].'" value="'.htmlspecialchars(stripslashes($custom_image_caption)).'" data-hiddenValue= "'.$custom_image_caption.'">
			
			$ajax_data['Priority'] 			= '<div class="w2ui-field article_priority" style="float:right; margin-right:50px; padding:5px 0 5px 0; ">
			<div>
			<input type="hidden" class="image-group" name="imgHomeImageId" rel="'.$custom_image_id.'" value="'.$custom_image_id.'" id="home_image_gallery_id'.$ajax_data['ContentId'].'">
			<input type="hidden" name="custom_image_caption'.$ajax_data['ContentId'].'" id="custom_image_caption'.$ajax_data['ContentId'].'" value="'.$custom_image_caption.'">
			
			<input type="hidden" name="custom_image_alt'.$ajax_data['ContentId'].'" id="custom_image_alt'.$ajax_data['ContentId'].'" value="'.$custom_image_alt.'" >
			<input type="hidden" name="custom_image_name'.$ajax_data['ContentId'].'" id="custom_image_name'.$ajax_data['ContentId'].'" value="'.$custom_image_name.'" >
			<input type="hidden" name="custom_image_id'.$ajax_data['ContentId'].'" id="custom_image_id'.$ajax_data['ContentId'].'" value="'.$custom_image_id.'" >
			<input type="hidden" name="custom_image_path'.$ajax_data['ContentId'].'" id="custom_image_path'.$ajax_data['ContentId'].'" value="'.$custom_image_path.'" >
			
			<input type="hidden" name="temp_article_list[]" id="temp_article_list[]" value="'.substr($temp_article_list,0,-1).'" >
			<input type="hidden" name="contenttypeID'.$ajax_data['ContentId'].'" id="contenttypeID'.$ajax_data['ContentId'].'" value="'.@$article_value[0]['content_type_id'].'" >
			<input type="hidden" name="modified_date_'.$ajax_data['ContentId'].'" id="modified_date_'.$ajax_data['ContentId'].'" value="'.$ajax_data['Article Date'].'" >
			<textarea hidden id="title_'.$ajax_data['ContentId'].'" >'.$custom_title.'</textarea>
			<textarea hidden id="summary_'.$ajax_data['ContentId'].'" >'.$custom_summary.'</textarea>
			<textarea hidden id="uploaded_image_'.$ajax_data['ContentId'].'" >'.$image_blob.'</textarea>
			<input type="hidden" id="image_path_name_'.$ajax_data['ContentId'].'" value="'.$instance_article_value['Imagename'].'" >			
			<input type="hidden" name="instancecontent_id_'.$ajax_data['ContentId'].'"  id="instancecontent_id_'.$ajax_data['ContentId'].'"  value="'.$instance_article_value['WidgetInstanceContent_ID'].'">';
			$ajax_data['Priority']  .= '<label name="priority[]" id="'. $ajax_data['ContentId'] .'">'.@$instance_article_value['DisplayOrder'].'</label>';
			$checked  = "checked";
			
			$ajax_data['Priority']  .= '</div>
			</div>';
			$ajax_data['Priority Hidden'] = (@$instance_article_value['DisplayOrder'] != '' && @$instance_article_value['DisplayOrder'] != '0') ? @$instance_article_value['DisplayOrder'] : "100000" ;			
			$ajax_data['Action'] = '<input type="checkbox" name="articles_list" id="'. $ajax_data['ContentId'] .'" value="'. $ajax_data['ContentId'] .'" '.$checked.'  onclick="show_edit(this.value)"  />&nbsp;<i class="fa fa-pencil  widget_toggle" title="Edit" id="edit_'. $ajax_data['ContentId'] .'" style="margin-right: 5px;" ></i>';			
			$is_title_long		= (mb_strlen(strip_tags($ajax_data['Title'])) <= 60) ? '' : '...';
			
			if(time() <= strtotime(@$article_value[0]['publish_start_date']))
			{
				$future_article_class = "Future-Article";
			}
			else
			{
				$future_article_class = "";
			}
			
			$article_type_redirect_url = array_search(@$article_value[0]['content_type_id'], $redirect_url_array);						
			$ajax_data['Title'] = '<a href="'.base_url().$article_type_redirect_url.urlencode(base64_encode($ajax_data['ContentId'])).'" target="_blank" title="'.strip_tags($custom_title).'" id="redirect_'. $ajax_data['ContentId'] .'" class="'.$future_article_class.'" >'.$show_article. $is_title_long.'</a>';
			$ajax_data['temp_article_list']	= "";
			
			//$image_size 					= $this->widget_model->get_widget_image_size($instance_article_value['WidgetInstance_id'], @$instance_article_value['DisplayOrder']);			
			$image_size 					= array();			
			if(count($image_size) > 0)
			{
				$ajax_data['Imagesize']	= $image_size[0]['image_width'].",".$image_size[0]['image_height'];
			}
			else
			{
				$ajax_data['Imagesize']	= "0,0";
			}
			
			if( $related_content_id_popup == '1' && $related_content_id == '')			
			{
				$ajax_data['Action'] .= '&nbsp;<i class="fa fa-plus show_related_articles" title="Add Related Articles" data-relatedContentName = "'. strip_tags(@$ajax_data['Title']) .'" data-relatedContentId = "'. @$ajax_data['ContentId'] .'" id="add_related_'. $ajax_data['ContentId'] .'" ></i>';
			}
			else if( $related_content_id_popup == '1')
			{
				$ajax_data['Action'] .= '&nbsp;<i class="fa fa-plus show_related_articles" title="Add Related Articles" data-relatedContentName = "'. strip_tags(@$ajax_data['Title']) .'" data-relatedContentId = "'. @$ajax_data['ContentId'] .'" id="add_related_'. $ajax_data['ContentId'] .'" style="display:none !important;" ></i>';
			}
			
			$ajax_data['ActionPriority'] = 1;
			
			//// Redirect Article in Article manager  //////			
			//$ajax_data['Action'] .= ' &nbsp; <a href="'.base_url().'admin/edit_article/'.urlencode(base64_encode($ajax_data['ContentId'])).'" target="_blank" title="Edit Article" id="redirect_'. $ajax_data['ContentId'] .'" >Edit</a>';
			
				/*$publish_history = get_publish_history_by_content_id($article_value[0]['content_id']);
			   foreach($publish_history as $history) {
				if(isset($history->publishedon))
				$data['publishdate'] = $history->publishedon;
			   
				break;
			   }*/
			   $data['publishdate'] = @$article_value[0]['publish_start_date'];
			
			//$ajax_data['Parent Section'] 			= @$parent_section_name;
			
			$ajax_data['Published Date']  			= date('d-m-Y H:i:s', strtotime( @$data['publishdate'])).'<label hidden id ="edit_status" >'.$edit_flag.'</label>';
			$ajax_data['Edit Status']  				= $edit_flag;
			$table_data[] = $ajax_data;
			
		  }		
		}
		
		 
	}
}

///////  Show articles not added in the widget instance articles ////////

if(count($db_data) > 0)
{
		
	//print_r($db_data); exit;
	foreach($db_data as $key => $article_value)
	{
		$subsection_ids			= array();
		$search_bysection_local = ($current_widget_section_id != '' && ($search_bysection == '' || $search_bysection == 'all') )? $current_widget_section_id :(($search_bysection == '' || $search_bysection == 'all' ) ? $article_value['Section_id'] : (( $search_bysection != '' ) ? $search_bysection : @$current_widget_section_id));				
		//echo "-".$search_bysection_local."-".$article_value['Section_id']; 	
		if(@$current_widget_section_id != ''){
			$subsection_details 	= $this->widget_model->get_section_menudisplay(@$current_widget_section_id);			
			foreach($subsection_details as $subsection_id)
			{
				$subsection_ids[] = $subsection_id['Section_id'];
			}
			//print_r($subsection_details);
			//$search_bysection_local = (!count($subsection_ids) > 0) ? $article_value['Section_id'] : $search_bysection_local;
		}
		//$search_bysection_local = (count($subsection_ids) > 0 && $current_widget_section_id == '' && $search_bysection == '') ? $article_value['Section_id'] : $search_bysection_local;
		//echo $search_bysection; 
		
		//echo "-".$search_bysection_local."-"; 	
		$selected_article 	= array_search($article_value['content_id'], $instancecontent_idlist);		
		//if(@$instance_added_article[$selected_article]['Status'] == '2' && $page_name != 'home')
		if(@$instance_added_article[$selected_article]['Status'] == '2')
		{
			$selected_article = false;
		}
		if($article_value['content_id'] == $related_content_id)
		{
			$selected_article = $related_content_id;
			
		}
		$image_blob			= "";
		if(is_bool($selected_article)) /////  Loading Articles from ArticleRelatedData (Not in WidgetInstanceContent)
		{
			$data_table_row_id_increment ++;						
			$content_title 					= 	 $article_value['Title'];
			$ajax_data['Title'] 			= $article_value['Title'];
			$ajax_data['ID'] 				= $data_table_row_id_increment;
			$ajax_data['ContentId']			= $article_value['content_id'];						
			$show_article					= strip_tags($ajax_data['Title']);
			$show_article_length			= (mb_strlen($show_article) <= 60) ? mb_strlen($show_article) : '60';
			$show_article					= mb_substr($show_article, 0, $show_article_length);	
			//$ajax_data['Section'] 			= $article_value['URLSectionStructure'];		
			$section_bread_crumb			= GenerateBreadCrumbBySectionId($article_value['Section_id']);
			$ajax_data['Section'] 			= $section_bread_crumb;			
			$ajax_data['Article Date'] 		= $article_value['Modifiedon'];
			$image_blob						= '';				
			$ajax_data['Media'] 			= ($article_value['ImagePhysicalPath'] == "") ? ' - ' : '<a href="javascript:void()" class="wid-click custom-img-hover" ><i class="fa fa-picture-o"></i><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.$article_value['ImagePhysicalPath'].'" ></div></a>';

			$ajax_data['Related Article'] 	= ' - ';
			/////  content version ID : $article_value['contentversion_id'] ////
			$show_action_checkbox			= true;
			$mapped_sections				= array();
			$is_sub_section					= in_array($article_value['Section_id'], $subsection_ids);

			if($current_widget_section_id == '')				
			{
				//// show action column ///
				$get_article_mapping 	= $this->template_design_model->get_content_mapping($search_bysection_local, $article_value['content_type_id'], $ajax_data['ContentId'])->result();				
				if(strtolower($article_value['URLSectionStructure']) != 'resources')
				{					
					$mapped_status = show_mapped_section($get_article_mapping, $search_bysection_local, $article_value['content_type_id'], $ajax_data['ContentId'], $section_bread_crumb, $article_value['Section_id'],$subsection_ids, $current_widget_section_id, '');
					//$show_action_checkbox = $mapped_status['show_action_checkbox'];						
					$show_action_checkbox = true;						
					$ajax_data['Section'] = $mapped_status['URLSectionStructure'];
					$subsection_mapped 	  = $mapped_status['subsection_mapped'];					
				}				
				else{
					$show_action_checkbox = true;						
					$ajax_data['Section'] = $section_bread_crumb;
					$subsection_mapped 	  = false;					
				}
			}
			else
			{
				if($current_widget_section_id){
					$current_section_details 		= $this->template_design_model->get_section_by_id(@$current_widget_section_id);	
				}
				else{
					$current_section_details['Sectionname'] = '';
				}
				if($search_bysection_local){
					$search_section_details 		= $this->template_design_model->get_section_by_id(@$search_bysection_local);	
					if(count($search_section_details) == 0){
						$search_section_details['Sectionname'] = '';
					}
				}
				else{
					$search_section_details['Sectionname'] = '';
				}
				 
				if(strtolower($article_value['URLSectionStructure']) != 'resources'){
					$get_article_mapping 	= $this->template_design_model->get_content_mapping($search_bysection_local, $article_value['content_type_id'], $ajax_data['ContentId'])->result();
					if($current_section_details['Sectionname'] == $article_value['Sectionname'] || $current_section_details['Sectionname'] == "தற்போதைய செய்திகள்"){
							$show_action_checkbox = true;						
							if($current_section_details['Sectionname'] != $search_section_details['Sectionname'])
							{
								$subsection_mapped 		= false;
								$mapped_status = show_mapped_section($get_article_mapping, $search_bysection_local, $article_value['content_type_id'], $ajax_data['ContentId'], $section_bread_crumb, $article_value['Section_id'],$subsection_ids, $current_widget_section_id, $search_section_details['ParentSectionID']);
								//$show_action_checkbox = $mapped_status['show_action_checkbox'];						
								$ajax_data['Section'] = $mapped_status['URLSectionStructure'];
								$subsection_mapped 	  = $mapped_status['subsection_mapped'];	
							}
					}elseif($current_section_details['Sectionname'] == $search_section_details['Sectionname'] || in_array($search_bysection, $subsection_ids) ){						
						//if(in_array($search_bysection, $subsection_ids)){
							$subsection_mapped 		= false;																		
							$mapped_status = show_mapped_section($get_article_mapping, $search_bysection_local, $article_value['content_type_id'], $ajax_data['ContentId'], $section_bread_crumb, $article_value['Section_id'],$subsection_ids, $current_widget_section_id, $search_section_details['ParentSectionID']);
							$show_action_checkbox = $mapped_status['show_action_checkbox'];						
							$ajax_data['Section'] = $mapped_status['URLSectionStructure'];
							$subsection_mapped 	  = $mapped_status['subsection_mapped']; 
						//}
					}
					else{	
							$subsection_mapped 		= false; 
							if(in_array($search_bysection, $subsection_ids)){
								$for_mapped_section = $current_section_details['Section_id'];
							}
							else{
								$for_mapped_section = $search_bysection_local;
							}
							$mapped_status = show_mapped_section($get_article_mapping, $for_mapped_section, $article_value['content_type_id'], $ajax_data['ContentId'], $section_bread_crumb, $article_value['Section_id'],$subsection_ids, $current_widget_section_id, $search_section_details['ParentSectionID']);
							if(in_array($search_bysection, $subsection_ids) || $mapped_status['subsection_mapped']){
								$show_action_checkbox = $mapped_status['show_action_checkbox'];						
							}
							else{
								$show_action_checkbox = false;						
							}
							$ajax_data['Section'] = $mapped_status['URLSectionStructure'];
							$subsection_mapped 	  = $mapped_status['subsection_mapped']; 
					}
				}else{					
					// Resource content-type
					if($current_section_details['Sectionname'] == $search_section_details['Sectionname'] && (strtolower($search_section_details['Sectionname']) == 'resources'))					
					{
						$show_action_checkbox = true;
					}else{
						$show_action_checkbox = false;
					}
					
				}
			}
			
			if($show_action_checkbox)
			{
				
				$priority_column = '<div class="w2ui-field article_priority" style="float:right; margin-right:50px; padding:5px 0 5px 0; ">
			<div>
			<input type="hidden" class="image-group" name="imgHomeImageId" rel="" value="" id="home_image_gallery_id'.$ajax_data['ContentId'].'">
			<input type="hidden" name="custom_image_caption'.$ajax_data['ContentId'].'" id="custom_image_caption'.$ajax_data['ContentId'].'" value="" >
			<input type="hidden" name="custom_image_alt'.$ajax_data['ContentId'].'" id="custom_image_alt'.$ajax_data['ContentId'].'" value="" >
			<input type="hidden" name="custom_image_name'.$ajax_data['ContentId'].'" id="custom_image_name'.$ajax_data['ContentId'].'" value="" >
			<input type="hidden" name="custom_image_id'.$ajax_data['ContentId'].'" id="custom_image_id'.$ajax_data['ContentId'].'" value="" >
			<input type="hidden" name="custom_image_path'.$ajax_data['ContentId'].'" id="custom_image_path'.$ajax_data['ContentId'].'" value="" >
			
			<input type="hidden" name="modified_date_'.$ajax_data['ContentId'].'" id="modified_date_'.$ajax_data['ContentId'].'" value="'.$ajax_data['Article Date'].'" >
			<textarea hidden id="title_'.$ajax_data['ContentId'].'" >'.$article_value['Title'].'</textarea>';
					
			$priority_column .= '<textarea hidden id="summary_'.$ajax_data['ContentId'].'" >'.$article_value['articleSummaryHTML'].'</textarea>';
		
			$priority_column .= '<textarea hidden id="uploaded_image_'.$ajax_data['ContentId'].'" ></textarea>
			<input type="hidden" id="image_path_name_'.$ajax_data['ContentId'].'" value="" >		
			<input type="hidden" name="instancecontent_id_'.$ajax_data['ContentId'].'"  id="instancecontent_id_'.$ajax_data['ContentId'].'"  value=""><label name="priority[]" id="'. $ajax_data['ContentId'] .'">'.$data_table_row_id_increment.'</label> </div></div>';
			
				$action_column = '<input type="hidden" name="contenttypeID'.$ajax_data['ContentId'].'" id="contenttypeID'.$ajax_data['ContentId'].'" value="'.@$article_value['content_type_id'].'" ><input type="checkbox" name="articles_list" id="'. $ajax_data['ContentId'] .'" value="'. $ajax_data['ContentId'] .'"  onclick="show_edit(this.value)" />&nbsp;<i class="fa fa-pencil  widget_toggle" title="Edit" id="edit_'. $ajax_data['ContentId'] .'" style="display:none; margin-right: 5px;"></i> &nbsp;<i class="fa fa-plus show_related_articles" title="Add Related Articles" data-relatedContentName = "'. strip_tags(@$ajax_data['Title']) .'" data-relatedContentId = "'. @$ajax_data['ContentId'] .'" id="add_related_'. $ajax_data['ContentId'] .'" style="display:none;" ></i>';
				$action_priority = 1;
			}
			else
			{
				$priority_column = '';
				$action_column = '';
				$action_priority = 0;
			}
			
			$ajax_data['Priority']  = $priority_column;		
			$ajax_data['Priority Hidden'] = "100000";
			
			$ajax_data['Action'] = $action_column;
			$ajax_data['ActionPriority'] = $action_priority;
			$ajax_data['Imagesize']	= "0,0";
			$is_title_long		= (mb_strlen(strip_tags($ajax_data['Title'])) <= 60) ? '' : '...';
			
			//if($contenttype_id == "all")
			//{
				$article_type_redirect_url = array_search($article_value['content_type_id'], $redirect_url_array);
			//}
			
			if(time() <= strtotime($article_value['publish_start_date']))
			{
				$future_article_class = "Future-Article";
			}
			else
			{
				$future_article_class = "";
			}
			
			$ajax_data['Title'] = '<a href="'.base_url().$article_type_redirect_url.urlencode(base64_encode($ajax_data['ContentId'])).'" target="_blank" title="'.strip_tags($content_title).'" id="redirect_'. $ajax_data['ContentId'] .'" class="'.$future_article_class.'" >'.$show_article. $is_title_long.'</a>';	
			
			//$ajax_data['Parent Section'] 			= $parent_section_name;
			
			/*$publish_history = get_publish_history_by_content_id($article_value['content_id']);
			   foreach($publish_history as $history) {
				if(isset($history->publishedon))
				$data['publishdate'] = $history->publishedon;
			   
				break;
			   }*/
			   
			   $data['publishdate'] = $article_value['publish_start_date'];
			
			
			$ajax_data['Published Date']  	= date('d-m-Y H:i:s', strtotime( @$data['publishdate'])).'<label hidden id ="edit_status" >'.$edit_flag.'</label>';
			$ajax_data['Edit Status']  		= $edit_flag;
			$table_data[] = $ajax_data;
		}				
		//print_r($ajax_data);
		//$i ++;
	}

}
else
{
	//$table_data[] = $ajax_data;
}

function show_mapped_section($get_article_mapping, $search_section_id, $article_content_type_id, $article_content_id, $article_url_structure, $article_section_id, $subsection_ids, $current_widget_section_id, $search_section_parentsection_id)
{
	$subsection_mapped = false;
	$return_result = array("show_action_checkbox" => false, "URLSectionStructure" => "", "subsection_mapped" => false);
	
	if(!empty($get_article_mapping)){
		$mapped_sections		= array();
		$mapped_content_id		= array();
		$mapped_section_url		= array();
		foreach($get_article_mapping as $multiple_mapping)
		{							
			$mapped_sections[] 	  = $multiple_mapping->Section_ID;
			$mapped_content_id[]  = $multiple_mapping->content_id;
			$mapped_section_url[] = GenerateBreadCrumbBySectionId( $multiple_mapping->Section_ID );
			$is_subsection_mapped = in_array($multiple_mapping->Section_ID, $subsection_ids);			
			//$is_subsection_mapped = ($multiple_mapping->Section_ID == $subsection_ids);			
			if($is_subsection_mapped)
			{
				$subsection_mapped = true;
			}
		}
	}
	else{
		$mapped_sections		= array();
		$mapped_content_id		= array();
		$mapped_section_url		= array();
	}		
//echo "current_widget_section_id:".$current_widget_section_id;	
//print_r($mapped_sections);	
	

	if((in_array($search_section_id, $mapped_sections) && $search_section_id != $article_section_id))
	{			
		$mapped_content_index = array_search($search_section_id, $mapped_sections);		
		if($article_url_structure != $mapped_section_url[$mapped_content_index]){
			$return_result['URLSectionStructure'] 	= $article_url_structure." <br> (Mapped to : ".$mapped_section_url[$mapped_content_index]." )";		
		}
		else{
			$return_result['URLSectionStructure'] 	= $article_url_structure;
		}
		
		$return_result['show_action_checkbox'] 	= true;
	}
	elseif(in_array($current_widget_section_id, $mapped_sections) && $current_widget_section_id != '' && $search_section_parentsection_id != '' )
	{
		$mapped_content_index = array_search($current_widget_section_id, $mapped_sections);		
		if($article_url_structure != $mapped_section_url[$mapped_content_index]){
			$return_result['URLSectionStructure'] 	= $article_url_structure." <br> (Mapped to : ".$mapped_section_url[$mapped_content_index]." )";		
		}
		else{
			$return_result['URLSectionStructure'] 	= $article_url_structure;
		}
		$return_result['show_action_checkbox'] 	= true;
		$subsection_mapped = true;		
	}
	elseif(in_array($current_widget_section_id, $mapped_sections) && $current_widget_section_id != '' && $search_section_parentsection_id == '' )
	{
		$mapped_content_index = array_search($current_widget_section_id, $mapped_sections);		
		if($article_url_structure != $mapped_section_url[$mapped_content_index]){
			$return_result['URLSectionStructure'] 	= $article_url_structure." <br> (Mapped to : ".$mapped_section_url[$mapped_content_index]." )";		
		}
		else{
			$return_result['URLSectionStructure'] 	= $article_url_structure;
		}
		$return_result['show_action_checkbox'] 	= true;
		$subsection_mapped = true;		
	}
	elseif(in_array($search_section_id, $mapped_sections)){
		$return_result['show_action_checkbox'] = true;		
		$return_result['URLSectionStructure'] 	= $article_url_structure;
	}
	elseif(in_array($article_section_id, $subsection_ids)){
		$return_result['show_action_checkbox'] = true;	
		$return_result['URLSectionStructure'] 	= $article_url_structure;		
	}	
	else
	{		
		$return_result['show_action_checkbox'] = false;				
		$return_result['URLSectionStructure'] 	= $article_url_structure;
	}
	$return_result['subsection_mapped'] = $subsection_mapped;			
	return $return_result;
}

//print_r($table_data); exit;
$json_data['data'] = $table_data;
echo json_encode($json_data);

?>                   
					
 