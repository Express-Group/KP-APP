<?php
//$content_from       = @$content['content_from'];
$view_mode          = $content['mode'];
//$widget_instance_id = $content['widget_values']['data-widgetinstanceid'];
/*	$get_widget_instance = $this->widget_model->getWidgetInstance('', '', '', '', $widget_instance_id, $view_mode);
	$page_section_id     = $get_widget_instance['Pagesection_id'];
	$page_type           = $get_widget_instance['Page_type'];
*/
$page_section_id     = $content['page_param'];
$page_type           = $content['page_type'];

/*if($page_type == 'section')
	$page_type = 1;
elseif($page_type == 'article')
	$page_type = 2;*/

if($page_section_id == '10000') //get current page section id for adding widget in common template 
	$page_section_id = $content['page_param'];
else
	$page_section_id;

$section_id             = '';
$section_name           = '';
$parentsection_id       = '';



//$widget_bg_color     = $content['widget_bg_color'];
//$widget_custom_title = $content['widget_title'];
//$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];


$main_sction_id      = "";
//$is_home             = $content['is_home_page'];
//$is_summary_required = $content['widget_values']['cdata-showSummary'];
//$widget_section_url  = $content['widget_section_url'];
// widget config block ends

$widget_instance_contents_array = array();
$widget_custom_title = '';		
if($page_type == 1)
	{
		$section_details        = $this->widget_model->get_section_by_id($page_section_id);
		$section_id             = @$section_details['Section_id'];
		$section_name           = @$section_details['Sectionname'];
		$parentsection_id       = @$section_details['ParentSectionID'];
		$parent_section_details = $this->widget_model->get_section_by_id($parentsection_id);
		
		//$widget_instance_contents_array = array();
		$type1stories                   = array();
		
		if($parentsection_id != "")
		{
			$widget_custom_title = "More from " . $parent_section_details['Sectionname'];
			$sections            = $this->widget_model->get_subsection_menudisplay($parentsection_id, $view_mode);
			
			foreach($sections as $r => $f)
			{
				$id = $f['Section_id'];
				$io = str_replace($section_id, "999999999999999999", $id);
				if($parent_section_details['Sectionname'] != 'Sport')
				{
					//$widget_instance_contents_array[$r] = $this->widget_model->get_RightSide_OtherStories_Contents_Type3('1', $content['mode'], $io, $parentsection_id);
					$widget_instance_contents_array[$r] = $this->widget_model->get_RightSide_OtherStories_Contents_Type1('1', $content['mode'], $io, $parentsection_id);
				}
				else
				{
					//$widget_instance_contents_array[$r] = $this->widget_model->get_RightSide_OtherStories_Contents_Type3('2', $content['mode'], $io, $parentsection_id);
					$widget_instance_contents_array[$r] = $this->widget_model->get_RightSide_OtherStories_Contents_Type1('2', $content['mode'], $io, $parentsection_id);
				}
			}
			
			if($content['widget_title'] != "")
				$widget_custom_title = $content['widget_title'];
		}
		else
		{
			if($parentsection_id == "")
			{
				if(trim($section_name) != 'Cities' && trim($section_name) != 'States' && trim($section_name) != 'Opinions')
				{
					$section1 = 'Nation';
					$section1 = $this->widget_model->get_sectionid_by_name($section1, $view_mode);
					$type1    = array_push($type1stories, $section1);
					$section2 = 'World';
					$section2 = $this->widget_model->get_sectionid_by_name($section2, $view_mode);
					$type2    = array_push($type1stories, $section2);
					/*$section3 = 'Sport';
					$section3 = $this->widget_model->get_sectionid_by_name($section3, $view_mode);
					$type3    = array_push($type1stories, $section3);*/
					$section4 = 'Business';
					$section4 = $this->widget_model->get_sectionid_by_name($section4, $view_mode);
					$type4    = array_push($type1stories, $section4);
					/*$section5 = 'Entertainment';
					$section5 = $this->widget_model->get_sectionid_by_name($section5, $view_mode);
					$type5    = array_push($type1stories, $section5);
					$section6 = 'Auto';
					$section6 = $this->widget_model->get_sectionid_by_name($section6, $view_mode);
					$type6    = array_push($type1stories, $section6);*/
					/*$section7 = 'Tech';
					$section7 = $this->widget_model->get_sectionid_by_name($section7, $view_mode);
					$type7    = array_push($type1stories, $section7);
					$section8 = 'Cricket';
					$section8 = $this->widget_model->get_sectionid_by_name($section8, $view_mode);
					$type8    = array_push($type1stories, $section8);
					$section9 = 'Tennis';
					$section9 = $this->widget_model->get_sectionid_by_name($section9, $view_mode);
					$type9    = array_push($type1stories, $section9);*/
					$sport_det = $this->widget_model->get_sectionid_by_name('Sport', $view_mode);
					$sections            = $this->widget_model->get_subsection_menudisplay($sport_det['Section_id'], $view_mode);
					$sport_instance_contents_array = array();
					foreach($sections as $r => $f)
					{
						$id = $f['Section_id'];
						$io = str_replace($section_id, "999999999999999999", $id);
						$sport_instance_contents = $this->widget_model->get_RightSide_OtherStories_Contents_Type1('1', $content['mode'], $io, $parentsection_id);
						if(!empty($sport_instance_contents)){
						$sport_instance_contents_array[$r] = $sport_instance_contents;
						}
						if(count($sport_instance_contents_array)>1){
							break;
						}
					}
					//print_r($sport_instance_contents_array);exit;
					
					$i = 1;
					foreach($type1stories as $r => $f)
					{
						$id        = $f['Section_id'];
						$lifestyle = 'LifeStyle';
						if($section_name == 'Columns')
							$io = str_replace($section7['Section_id'], "999999999999999999", $id);
						elseif($section_name == trim($lifestyle))
							$io = str_replace($section7['Section_id'], "999999999999999999", $id);
						else
							$io = str_replace($section_id, "999999999999999999", $id);
						$max_article = ($f['Sectionname']=='Cricket' || $f['Sectionname']=='Tennis')? 1:2;//(($i==2)?  2 : 1);
						if($io != '999999999999999999')
							$widget_instance_contents_array[$r] = $this->widget_model->get_RightSide_OtherStories_Contents_Type1($max_article, $view_mode, $io, '');
							
							$i++;
					}
					
					$widget_custom_title = "From other Sections";
					
					if($content['widget_title'] != "")
						$widget_custom_title = $content['widget_title'];
					
					$widget_instance_contents_array = array_merge($widget_instance_contents_array, $sport_instance_contents_array);
					//print_r($sport_instance_contents_array);exit;
					$i = 1;
				}
				else
				{
					if(trim($section_name) == 'States')
					{
						$widget_custom_title = "More from Cities";
						$section_name1       = 'Cities';
						$section             = $this->widget_model->get_sectionid_by_name($section_name1, $view_mode);
						$section_id          = $section['Section_id'];
						$city_state          = $this->widget_model->get_subsection_menudisplay($section_id, $view_mode);
						$max_limit = 1;
					}
					if(trim($section_name) == 'Cities')
					{
						$widget_custom_title = "More from States";
						$section_name2       = 'States';
						$section             = $this->widget_model->get_sectionid_by_name($section_name2, $view_mode);
						$section_id          = $section['Section_id'];
						$city_state          = $this->widget_model->get_subsection_menudisplay($section_id, $view_mode);
						$max_limit = 1;
					}
					
					if(trim($section_name) == 'Opinions')
					{
						$widget_custom_title = "More from Opinions";
						$section_name2       = 'Opinions';
						$section             = $this->widget_model->get_sectionid_by_name($section_name2, $view_mode);
						$section_id          = $section['Section_id'];
						$city_state          = $this->widget_model->get_subsection_menudisplay($section_id, $view_mode);
						$max_limit = 3;
					}
					foreach($city_state as $r => $f)
					{
						$id = $f['Section_id'];
						
						if($id != '')
							$widget_instance_contents_array[$r] = $this->widget_model->get_RightSide_OtherStories_Contents_Type2($max_limit, $content['mode'], $id);
					}
					
					if($content['widget_title'] != "")
						$widget_custom_title = $content['widget_title'];
					// content list iteration block ends here
				}
			}
		}
		//$widget_instance_contents_array = array_map(function($a) {  return array_pop($a); }, $widget_instance_contents_array);
		
		$widget_instance_contents_array = call_user_func_array('array_merge', array_map('array_values', $widget_instance_contents_array));
		//print_r($widget_instance_contents_array); exit;
	}
	elseif($page_type == 2)
	{
		$widget_instance_contents_array = array();
		$content_id = $content['content_id'];
		if($content_id!=''){
		/*if($content_from == "live" || $content_from == "")
		{
		$content_det = $this->widget_model->get_content_details($content_id)->result_array();
		}
		$artsection_id       = $content_det[0]['Section_id'];
		$secname             = $this->widget_model->get_section_by_id($artsection_id);*/
		if(isset($content['section_id']) && $content['section_id']!=''){
		$section_id = $content['section_id'];		
		}else{
		$sectionid  = $this->widget_model->get_sectionid_by_article($content_id, $view_mode);
		if(count($sectionid) > 0) {
			$section_id = $sectionid[0]['Section_id'];
		}
		else {
			$section_id = @$content['detail_content'][0]['section_id'];	
		}
		}
		
		$widget_custom_title      = "ಈ ವಿಭಾಗದ ಇತರ ಸುದ್ದಿ";
		$get_time      = $this->widget_model->select_setting($view_mode);
		$article_limit = $get_time['articlecountfortotherstories']; // manage no of articles displayed in article page 
		$widget_instance_contents_array = $this->widget_model->rightside_otherstories_articlepage($article_limit, $view_mode, $section_id, $content_id);
		
		$i = 1;
		
		}
		// content list iteration block ends here
		
	}

// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name = base_url();
$j = 0;
$show_simple_tab = "";
?>
<div id="other_stories_right">
<?php 
if(count(array_filter($widget_instance_contents_array)) > 0) {
$show_simple_tab .= '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-10"><div class="most">';
$show_simple_tab .= '<fieldset class="FieldTopic">';

$show_simple_tab .= '<legend class="topic">' . $widget_custom_title . '</legend>';

$show_simple_tab .= '</fieldset>';
$i = 1;
if(count(array_filter($widget_instance_contents_array)) > 0)
{
	foreach(array_filter($widget_instance_contents_array) as $from_get_content)
	{
		$original_image_path = "";
		$imagealt            = "";
		$imagetitle          = "";
		
		if(@$from_get_content['ImagePhysicalPath'] != '')
		{
			$original_image_path = $from_get_content['ImagePhysicalPath'];
			$imagealt            = $from_get_content['ImageCaption'];
			$imagetitle          = $from_get_content['ImageAlt'];
		}
		
		$show_image = "";
		if($original_image_path != '' && get_image_source($original_image_path, 1))
		{
			$imagedetails = get_image_source($original_image_path, 2);
			$imagewidth = $imagedetails[0];
			$imageheight = $imagedetails[1];	
		
			if ($imageheight > $imagewidth)
			{
				$Image100X65 	= $original_image_path;
			}
			else
			{
				$Image100X65 = str_replace("original", "w100X65", $original_image_path);
			}
			
			if(get_image_source($Image100X65, 1) && $Image100X65 != '')
			{
				$show_image = image_url . imagelibrary_image_path . $Image100X65;
			}
			else
			{
				$show_image = image_url . imagelibrary_image_path . 'logo/nie_logo_100X65.jpg';
			}
			$dummy_image = image_url . imagelibrary_image_path . 'logo/nie_logo_100X65.jpg';
		}
		else
		{
			$show_image  = image_url . imagelibrary_image_path . 'logo/nie_logo_100X65.jpg';
			$dummy_image = image_url . imagelibrary_image_path . 'logo/nie_logo_100X65.jpg';
		}
		
		$content_url      = @$from_get_content['url'];
		$param            = $content['close_param'];
		$live_article_url = $domain_name . $content_url . $param;
		
		$custom_title  = @$from_get_content['title'];
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_title); //to remove first<p> and last</p>  tag
		$display_title = '<a  href="' . $live_article_url . '" class="article_click" >' . $display_title . '</a>';
		
		$show_simple_tab .= '<div class="most1"> ';
		$show_simple_tab .= '<a  href="' . $live_article_url . '" ><img src="' . $dummy_image . '" data-src="'.$show_image.'"  title = "' . $imagetitle . '" alt = "' . $imagealt . '" width="34" height="34"></a><p>' . $display_title . '</p></div>';
		
		$i = $i + 1;
		
		$j++;
	}
}
/*elseif($view_mode=="adminview")
{
	$show_simple_tab .= '<div class="margin-bottom-10">' . no_articles . '</div>';
}*/

$j++;

$show_simple_tab .= '</div></div></div>';

}

echo $show_simple_tab;
?>
</div>