<?php $script_url = image_url; ?>
<?php
$db_data            = $all_available_articles;
$table_data         = array();
$temp_article_list  = "";
$redirect_url_array = array(
	"niecpan/edit_article/" => 1,
	"niecpan/edit_gallery/" => 3,
	"niecpan/audio_video_manager/edit_data/4/" => 4,
	"niecpan/audio_video_manager/edit_data/5/" => 5
);
switch($contenttype_id)
{
	case 1:
		$article_type_redirect_url = "niecpan/edit_article/";
		break;
	
	case 3:
		$article_type_redirect_url = "niecpan/edit_gallery/";
		break;
	
	case 4:
		$article_type_redirect_url = "niecpan/edit_video/";
		break;
	
	case 5:
		$article_type_redirect_url = "niecpan/edit_audio/";
		break;
	
	case "all":
		$article_type_redirect_url = "niecpan/edit_article/";
		break;
	
	default:
		$article_type_redirect_url = "niecpan/edit_article/";
		break;
}

$data_table_row_id_increment = 0;
$live_section_articles_count = $live_section_articles_count;
					//$ajax_data['text_input'] = '<input type="text" name="selected_article_count" id="selected_article_count" value="'.count($instance_added_article).'" >';
if(count($instance_added_article) > 0)
{
	//print_r($instance_added_article);  exit;
	foreach($instance_added_article as $instance_key => $instance_article_value)
	{
		if($parent_section_id == $instance_article_value['section_id'] or ($widget_type != 1 || $widget_type != 4 ))
		{
			$data_table_row_id_increment ++;
			$article_value = $this->section_widget_article_model->required_commonwidget_content_by_id($instance_article_value['content_id'], $instance_article_value['content_type'], $section_name);
			//echo $this->db->last_query();
			//print_r($article_value); exit; 
			if(@$article_value[0]['title'] != "")
			{
				$temp_article_list = '';
				
				$selected_article_count = count($instance_added_article);
				
				
				$instance_article_value['imageID']   = '';
				$instance_article_value['Imagename'] = '';
				$instance_article_value['Image']     = '';
				
				//$temp_article_list				= $instance_article_value['instancecontent_tempID'].",".$temp_article_list;
				$custom_summary  = ($instance_article_value['CustomSummary'] != '') ? $instance_article_value['CustomSummary'] : "";
				
				$ajax_data['ID'] = $instance_article_value['content_id'];
				$ajax_data['s_no'] 				= $data_table_row_id_increment;
				
				$ajax_data['Title'] = ($instance_article_value['CustomTitle'] != '') ? $instance_article_value['CustomTitle'] : @$article_value[0]['title'];
				$show_article       = strip_tags($ajax_data['Title']);
				
				
				$ajax_data['Modifiedon'] =  @$article_value[0]['Modifiedon'];
				
				if($instance_article_value['image_id'] != "")
				{
					$ajax_data['imageID'] = $instance_article_value['image_id'];
				}
				else
				{
					$ajax_data['imageID'] = '';
				}
				$show_article_length  = (mb_strlen($show_article) <= 65) ? mb_strlen($show_article) : '65';
				$show_article         = mb_substr($show_article, 0, $show_article_length);
				//$ajax_data['Title'] 			= $instance_article_value[0]['CustomTitle'];
				//$ajax_data['Section'] 			= @$article_value[0]['Sectionname'];	
				//$ajax_data['Section'] = @$article_value[0]['URLSectionStructure'];
				
				$ajax_data['Section'] = GenerateBreadCrumbBySectionId(@$article_value[0]['Section_id']);
				
				$ajax_data['SectionID'] = $instance_article_value['section_id'];
				
				$ajax_data['Article Date'] = @$article_value[0]['Modifiedon'];
				$image_blob                = "";
				if($instance_article_value['Image'] != '')
				{
					$image_blob = $instance_article_value['Image'];
				}
				else if(@$article_value[0]['homepageimageid'] != '')
				{
					$image_blob = '';
				}
				
				if($instance_article_value['CustomTitle'] === "")
				{
					$instance_article_value['CustomTitle'] = @$article_value[0]['title'];
				}
				else
				{
					$instance_article_value['CustomTitle'];
				}
				/*if(isset($article_value[0]['homepageimageid']) && isset($article_value[0]['Sectionpageimageid']) && isset($article_value[0]['articlepageimageid']) && $article_value[0]['homepageimageid'] == "" && $article_value[0]['Sectionpageimageid'] == "" && $article_value[0]['articlepageimageid'] == "")
				{
					$media = '-';
				}
				else
				{
					$media = '<span class="wid-click" ><i class="fa fa-picture-o"></i></span>';
				}*/
				
				$Physical_extension = '';
				$Physical_name      = '';
				$Physical_URL       = '';
				$img_alt            = '';
				$img_caption        = '';
				
				
				
				if($ajax_data['imageID'] != '' && $ajax_data['imageID'] != 0)
				{
					$ImageURL     = get_image_by_contentid($ajax_data['imageID']);
					$ImageDetails = GetImageDetailsByContentId($ajax_data['imageID']);
					$Physical_URL = $ImageDetails['ImagePhysicalPath'];
					$img_alt      = $ImageDetails['ImageAlt'];
					
					$img_caption        = $ImageDetails['ImageCaption'];
					$Physical_array     = explode('.', $Physical_URL);
					$Physical_extension = $Physical_array[1];
					$Physical_name      = GetPhysicalNameFromPhysicalPath($Physical_URL);
					
					//$media			= ($Physical_URL == "") ? ' - ' : '<a href="javascript:void()" class="wid-click custom-img-hover" ><i class="fa fa-picture-o"></i><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.$Physical_URL.'" ></div></a><input type="hidden" id="img_path_'.$ajax_data['ID'].'" name="img_path_'.$ajax_data['ID'].'" value="'.image_url.imagelibrary_image_path.$Physical_URL.'">';
					
					$media			= ($Physical_URL == "") ? ' - ' : '<a href="javascript:void()" class="wid-click custom-img-hover" ><i class="fa fa-picture-o"></i><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.$Physical_URL.'" ></div></a>';
				}
				else
				{
					$media			= (@$article_value[0]['ImagePhysicalPath'] == "") ? ' - ' : '<a href="javascript:void()" class="wid-click custom-img-hover" ><i class="fa fa-picture-o"></i><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.@$article_value[0]['ImagePhysicalPath'].'" ></div></a>';
				}
				
				//$ajax_data['Media'] 			= (isset($article_value['homepageimageid']) && isset($article_value['Sectionpageimageid']) && isset($article_value['articlepageimageid']) && $article_value['homepageimageid'] == "" && $article_value['Sectionpageimageid'] == "" && $article_value['articlepageimageid'] == "") ? ' - ' : '<span class="wid-click" ><i class="fa fa-picture-o"></i></span>';
				
				$ajax_data['Media'] = $media;
				
				$ajax_data['Related Article'] = (true) ? ' - ' : '<span class="wid-click" ><i class="fa fa-th"></i></span>';
				$ajax_data['Display order']        = '<div class="w2ui-field article_priority" style="float:right; margin-right:50px; padding:5px 0 5px 0; ">
				<div class="controls">
				
				<input type="hidden" name="get_sectionId_' . $ajax_data['ID'] . '" id="get_sectionId_' . $ajax_data['ID'] . '" value="' . $parent_section_id . '" >
				<input type="hidden" name="contenttypeID'.$ajax_data['ID'].'" id="contenttypeID'.$ajax_data['ID'] . '" value="' . @$article_value[0]['content_type_id'] . '" >
				
				
				<input type="hidden" name="temp_article_list[]" id="temp_article_list[]" value="' . substr($temp_article_list, 0, -1) . '" >
				<input type="hidden" name="modified_date_' . $ajax_data['ID'] . '" id="modified_date_' . $ajax_data['ID'] . '" value="' . $ajax_data['Article Date'] . '" >
				<textarea hidden id="title_' . $ajax_data['ID'] . '" >' .$instance_article_value['CustomTitle'] . '</textarea>
				<textarea hidden id="summary_' . $ajax_data['ID'] . '" >' . $custom_summary . '</textarea>
				<textarea hidden id="uploaded_image_' . $ajax_data['ID'] . '" >' . $ajax_data['imageID'] . '</textarea>';
				
				
				$ajax_data['Display order'] .= '<input type="hidden" name="Physical_extension' . $ajax_data['ID'] . '" id="Physical_extension' . $ajax_data['ID'] . '" value="' . $Physical_extension . '" >
					<input type="hidden" name="Physical_name' . $ajax_data['ID'] . '" id="Physical_name' . $ajax_data['ID'] . '" value="' . $Physical_name . '" >
					<input type="hidden" name="Physical_path' . $ajax_data['ID'] . '" id="Physical_path' . $ajax_data['ID'] . '" value="' . $Physical_URL . '" >
					<input type="hidden" name="custom_image_alt' . $ajax_data['ID'] . '" id="custom_image_alt' . $ajax_data['ID'] . '" value="' . $img_alt . '" >
					
					<input type="hidden" name="selected_article_count" id="selected_article_count'.$ajax_data['ID'].'" value="'.$selected_article_count.'" >
					
					<input type="hidden" name="selected_article_count_live" id="selected_article_count_live'.$ajax_data['ID'].'" value="'.$live_section_articles_count.'" >
					
					
					<input type="hidden" name="custom_image_caption' . $ajax_data['ID'] . '" id="custom_image_caption' . $ajax_data['ID'] . '" value="' . $img_caption . '" >
				<input type="hidden" name="custom_image_id' . $ajax_data['ID'] . '" id="custom_image_id' . $ajax_data['ID'] . '" value="' . $ajax_data['imageID'] . '" >';
				
				
				
				if($ajax_data['imageID'] != "" && $ajax_data['imageID'] != "0")
				{
					$ajax_data['Display order'] .= '<input type="hidden" id="imageID_' . $ajax_data['ID'] . '" value="' . get_image_by_contentid($ajax_data['imageID']) . '">';
				}
				$ajax_data['Display order'] .= '<input type="hidden" id="image_path_name_' . $ajax_data['ID'] . '" value="' . $instance_article_value['Imagename'] . '" >			
				<input type="hidden" name="instancecontent_id_' . $ajax_data['ID'] . '"  id="instancecontent_id_' . $ajax_data['ID'] . '"  value="' . $instance_article_value['content_id'] . '">';
				$ajax_data['Display order'] .= '<span class="change_displayorder" id="change_displayorder'.$ajax_data['ID'].'">' . $instance_article_value['DisplayOrder'] . '</span><input type="hidden" class="updatefield" name="priority[]"  id="change_displayorder_hidden'.$ajax_data['ID'].'"  value="' . @$instance_article_value['DisplayOrder'] . '" style="width:70px; text-align:center;" maxlength="3">';
				$checked = "checked";
				
				$ajax_data['Display order'] .= '</div></div>';
				$ajax_data['Priority Hidden']   = (@$instance_article_value['DisplayOrder'] != '' && @$instance_article_value['DisplayOrder'] != '0') ? @$instance_article_value['DisplayOrder'] : "100000";
				//<input type="hidden" id="section_'.$ajax_data['ID'].'" name="section_'.$ajax_data['ID'].'" value="'.$ajax_data['SectionID'].'">		
				$ajax_data['Action']            = '<input type="hidden" id="section_' . $ajax_data['ID'] . '" name="section_' . $ajax_data['ID'] . '" value="' . $parent_section_id . '">
				
				
				<input type="checkbox" name="articles_list"  id="' . $ajax_data['ID'] . '"  value="' . $ajax_data['ID'] . '" ' . $checked . '  onclick="show_edit(this.value)"  />&nbsp;<i class="fa fa-pencil  widget_toggle" title="Edit" id="edit_' . $ajax_data['ID'] . '" ></i>';
				$is_title_long                  = (mb_strlen(strip_tags($ajax_data['Title'])) <= 65) ? '' : '...';
				//echo $article_value[0]['content_type_id'];
				//print_r($redirect_url_array);
				//$article_type_redirect_url = array_search(@$article_value[0]['content_type_id'], $redirect_url_array);						
				//echo $article_type_redirect_url;
				$ajax_data['Title']             = '<div class="width-300"><a href="' . base_url() . $article_type_redirect_url . urlencode(base64_encode($ajax_data['ID'])) . '" target="_blank" title="'.strip_tags($ajax_data['Title']).'" id="redirect_' . $ajax_data['ID'] . '" >' . $show_article . $is_title_long . '</a></div>';
				$ajax_data['temp_article_list'] = "";
				
				
				$ajax_data['ActionPriority'] = 1;
				
				//// Redirect Article in Article manager  //////			
				//$ajax_data['Action'] .= ' &nbsp; <a href="'.base_url().'admin/edit_article/'.urlencode(base64_encode($ajax_data['ID'])).'" target="_blank" title="Edit Article" id="redirect_'. $ajax_data['ID'] .'" >Edit</a>';
				
				
				$table_data[] = $ajax_data;
			}
		}
	}
}

///////  Show articles not added to the widget instance  ////////

//print_r($db_data); exit;
//if($parent_section_id != "all"{
if(count($db_data) > 0)
{
	//print_r($db_data);  exit;
	//////  Find whether current widget section have any subsections  /////////
	$subsection_ids = array();
	if(@$current_widget_section_id != '')
	{
		$subsection_details = $this->widget_model->get_section_menudisplay(@$current_widget_section_id);
		foreach($subsection_details as $subsection_id)
		{
			$subsection_ids[] = $subsection_id['Section_id'];
		}
	}
	//$count = 0;
	foreach($db_data as $key => $article_value)
	{
		//print_r($instancecontent_idlist); 
		$selected_article = array_search($article_value['content_id'], $instancecontent_idlist);
		//echo $selected_article.'****';
		//if(@$instance_added_article[$selected_article]['Status'] == '2' && $page_name != 'home')
		if($selected_article == '' && $selected_article != 0)
		{
			$selected_article = false;
		}
		$image_blob = "";
		if(is_bool($selected_article)) /////  Loading Articles from ArticleRelatedData (Not in WidgetInstanceContent)
			
		//if($instancecontent_idlist[$count] != $article_value['content_id'])
		{
			$data_table_row_id_increment ++;
			$ajax_data['ID'] = $article_value['content_id'];
			$ajax_data['s_no'] 				= $data_table_row_id_increment;
			$ajax_data['Title']   = $article_value['title'];
			$show_article         = strip_tags($ajax_data['Title']);
			$show_article_length  = (mb_strlen($show_article) <= 65) ? mb_strlen($show_article) : '65';
			$show_article         = mb_substr($show_article, 0, $show_article_length);
			//$ajax_data['Section'] 			= $article_value['Sectionname'];
			//$ajax_data['Section'] = $article_value['URLSectionStructure'];
			
			$ajax_data['Section'] = GenerateBreadCrumbBySectionId(@$article_value['Section_id']);
			
			$ajax_data['Article Date']    = $article_value['Modifiedon'];
			//$ajax_data['content_type_id'] = $article_value['content_type_id'];
			$ajax_data['content_type_id'] = '';
			$image_blob                   = '';
			
			$Physical_extension = '';
			$Physical_name      = '';
			$Physical_URL       = '';
			$img_alt            = '';
			
			$ajax_data['imageID'] = '';
			
			if($ajax_data['imageID'] != '' && $ajax_data['imageID'] != 0)
			{
				$ImageURL           = get_image_by_contentid($ajax_data['imageID']);
				$ImageDetails       = GetImageDetailsByContentId($ajax_data['imageID']);
				$Physical_URL       = $ImageDetails['ImagePhysicalPath'];
				$img_alt            = $ImageDetails['ImageAlt'];
				$Physical_array     = explode('.', $Physical_URL);
				$Physical_extension = $Physical_array[1];
				$Physical_name      = GetPhysicalNameFromPhysicalPath($Physical_URL);
			}
			
			
			if(isset($article_value['homepageimageid']) && isset($article_value['Sectionpageimageid']) && isset($article_value['articlepageimageid']) && $article_value['homepageimageid'] == "" && $article_value['Sectionpageimageid'] == "" && $article_value['articlepageimageid'] == "")
			{
				$media = '-';
			}
			else
			{
				$media = '<span class="wid-click" ><i class="fa fa-picture-o"></i></span>';
			}
			
			
			//$ajax_data['Media'] = $media;
			
			$ajax_data['Media'] 			= ($article_value['ImagePhysicalPath'] == "") ? ' - ' : '<a href="javascript:void()" class="wid-click custom-img-hover" ><i class="fa fa-picture-o"></i><div class="img-hover"><img src="'.image_url.imagelibrary_image_path.$article_value['ImagePhysicalPath'].'" ></div></a>';
			
			//$ajax_data['Media'] 			= (isset($article_value['homepageimageid']) && isset($article_value['Sectionpageimageid']) && isset($article_value['articlepageimageid']) && $article_value['homepageimageid'] == "" && $article_value['Sectionpageimageid'] == "" && $article_value['articlepageimageid'] == "") ? ' - ' : '<span class="wid-click" ><i class="fa fa-picture-o"></i></span>';
			
			$ajax_data['Related Article'] = (@$article_value['RelatedArticleId'] == '') ? ' - ' : '<span class="wid-click" ><i class="fa fa-th"></i></span>';
			
			/////  content version ID : $article_value['contentversion_id'] ////
			$show_action_checkbox = true;
			$mapped_sections      = array();
			$is_sub_section       = in_array($article_value['Section_id'], $subsection_ids);
			
			$section_id = $article_value['Section_id'];
			
			if($show_action_checkbox)
			{
				//$ajax_data['imageID'] = ''; 
				$priority_column = '<div class="w2ui-field article_priority" style="float:right; margin-right:50px; padding:5px 0 5px 0; ">
				<div class="controls"> <span class="change_displayorder" id="change_displayorder'.$ajax_data['ID'].'"></span>
				<input type="hidden" name="modified_date_' . $ajax_data['ID'] . '" id="modified_date_' . $ajax_data['ID'] . '" value="' . $ajax_data['Article Date'] . '" >
				<textarea hidden id="title_' . $ajax_data['ID'] . '" >'.$ajax_data['Title'].'</textarea>
				<textarea hidden id="summary_' . $ajax_data['ID'] . '" ></textarea>
				<textarea hidden id="uploaded_image_' . $ajax_data['ID'] . '" >' . $ajax_data['imageID'] . '</textarea>
				
				<input type="hidden" id="image_path_name_' . $ajax_data['ID'] . '" value="" >		
				<input type="hidden" name="instancecontent_id_' . $ajax_data['ID'] . '"  id="instancecontent_id_' . $ajax_data['ID'] . '"  value=""><input type="hidden" class="updatefield" name="priority[]"  id="change_displayorder_hidden'.$ajax_data['ID'].'"  value="" style="width:70px; text-align:center;" maxlength="3"></div></div>
				
				<input type="hidden" name="selected_article_count" id="selected_article_count'.$ajax_data['ID'].'" value="" >
				<input type="hidden" name="selected_article_count_live" id="selected_article_count_live'.$ajax_data['ID'].'" value="'.$live_section_articles_count.'" >';
				
				$action_column = '<input type="hidden" id="section_' . $ajax_data['ID'] . '" name="section_' . $ajax_data['ID'] . '" value="' . $section_id . '"><input type="hidden" id="section_' . $ajax_data['ID'] . '" name="section_id_unique" value="' . $section_id . '"><input type="hidden" name="contenttypeID' . $ajax_data['ID'] . '" id="contenttypeID' . $ajax_data['ID'] . '" value="' . @$ajax_data['content_type_id'] . '" ><input type="checkbox" name="articles_list" id="' . $ajax_data['ID'] . '"  value="' . $ajax_data['ID'] . '"  onclick="show_edit(this.value)" />&nbsp;<i class="fa fa-pencil  widget_toggle" title="Edit" id="edit_' . $ajax_data['ID'] . '" style="display:none;"></i> &nbsp;<i class="fa fa-plus show_related_articles" title="Add Related Articles" data-relatedContentName = "' . strip_tags(@$ajax_data['Title']) . '" data-relatedContentId = "' . @$ajax_data['ID'] . '" id="add_related_' . $ajax_data['ID'] . '" style="display:none;" ></i>';
				
				
				$priority_column .= '<input type="hidden" name="Physical_extension' . $ajax_data['ID'] . '" id="Physical_extension' . $ajax_data['ID'] . '" value="' . $Physical_extension . '" >
				<input type="hidden" name="Physical_name' . $ajax_data['ID'] . '" id="Physical_name' . $ajax_data['ID'] . '" value="' . $Physical_name . '" >
				<input type="hidden" name="Physical_path' . $ajax_data['ID'] . '" id="Physical_path' . $ajax_data['ID'] . '" value="' . $Physical_URL . '" >
				<input type="hidden" name="img_alt' . $ajax_data['ID'] . '" id="img_alt' . $ajax_data['ID'] . '" value="' . $img_alt . '" >';
				
				$action_priority = 1;
			}
			else
			{
				$priority_column = '';
				$action_column   = '';
				$action_priority = 0;
			}
			
			$ajax_data['Modifiedon'] =  $article_value['Modifiedon'];
			$ajax_data['Display order']        = $priority_column;
			$ajax_data['Priority Hidden'] = "100000";
			
			$ajax_data['Action']         = $action_column;
			$ajax_data['ActionPriority'] = $action_priority;
			//$ajax_data['Imagesize']	= "0,0";
			$is_title_long               = (mb_strlen(strip_tags($ajax_data['Title'])) <= 65) ? '' : '...';
			
			/*if($contenttype_id == "all")
			{
			$article_type_redirect_url = array_search($article_value['content_type_id'], $redirect_url_array);
			}*/
			
			$ajax_data['Title'] = '<div class="width-300"><a href="' . base_url() . $article_type_redirect_url . urlencode(base64_encode($ajax_data['ID'])) . '" target="_blank" title="'.strip_tags($ajax_data['Title']).'" id="redirect_' . $ajax_data['ID'] . '" >' . $show_article . $is_title_long . '</a></div>';
			
			
			
			$table_data[] = $ajax_data;
		}
		//$count++;
	}
	
}

//print_r($table_data); exit;
$json_data['data'] = $table_data;
echo json_encode($json_data);

?>                   
					
 