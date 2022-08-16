<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Template_library
{
	public function __construct()
    {
		$CI = & get_instance();
		$CI->load->model('admin/widget_model');
		$this->widget_model = $CI->widget_model;
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
		if($clone_instance_id!=''){
     $widget_instance_details    = $this->widget_model->getWidgetInstance('', '','', '', $pass_widget_instance, $content['mode']);	 
		}else{
		$widget_instance_details =	array('isSummaryRequired'=> (string)$content['widget_values']['cdata-showSummary'],'status'=> (string)$content['widget_values']['cdata-widgetStatus'],'publish_start_date'=> (string)$content['widget_values']['cdata-widgetpublishOn'],'publish_end_date'=> (string)$content['widget_values']['cdata-widgetpublishOff'],'WidgetSection_ID'=> ((string)$content['widget_values']['cdata-widgetCategory']=='undefined')? '': (string)$content['widget_values']['cdata-widgetCategory'],'CustomTitle'=> ((string)$content['widget_values']['cdata-customTitle']=='undefined')? '' : (string)$content['widget_values']['cdata-customTitle'],'Maximum_Articles'=> (string)((isset($content['widget_values']['cdata-customMaxArticles']) && $content['widget_values']['cdata-customMaxArticles'] != "" && $content['widget_values']['cdata-customMaxArticles'] != "undefined") ? $content['widget_values']['cdata-customMaxArticles'] = $content['widget_values']['cdata-customMaxArticles'] : 0),'Background_Color'=> (string)$content['widget_values']['cdata-customBgColor'],'RenderingMode'=> (string)((isset($content['widget_values']['cdata-renderingMode']) && $content['widget_values']['cdata-renderingMode'] != "" && $content['widget_values']['cdata-renderingMode'] != "undefined") ? $content['widget_values']['cdata-renderingMode'] = $content['widget_values']['cdata-renderingMode'] : "manual"));
		}

		if(count($widget_instance_details)>0){
			
			if($clone_instance_id != ''){
				if($content['widget_values']['data-widgetstyle'] == 2){
						//$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $clone_instance_id, 'live');
						$widget_instancemainsection	= $content['widget_values']->widgettab;	
						//print_r($widget_instancemainsection);
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
			$widget_custom_title    = ($widget_instance_details['CustomTitle']!= '') ? $widget_instance_details['CustomTitle'] : " ";
		if($widget_instance_details['CustomTitle']!= '' && $widget_instance_details['WidgetSection_ID']=='0')
		{
			$widget_sectionname_link = 0;
			$widget_section_id       = '';
			$widget_section_url      = '';
		}
		else if($widget_instance_details['WidgetSection_ID'] != '' && $widget_instance_details['WidgetSection_ID'] != "0"){
				$widget_sectionname_link = 1;
				$widget_section_id       = $widget_instance_details['WidgetSection_ID'];
			    $widget_section_details  = $this->widget_model->get_section_by_id($widget_instance_details['WidgetSection_ID']); //live db
			   	$widget_section_url      = $domain_name.$widget_section_details['URLSectionStructure'];

			$widget_custom_title         = ($widget_instance_details['CustomTitle']!= '') ? $widget_instance_details['CustomTitle']: (($widget_section_details['Sectionname']!= '') ? $widget_section_details['Sectionname'] : "");
		}
		
		$content_type_names			     = array("None"=>1,"Article" => 2,"Gallery" => 3,"Video" => 4,"Audio" => 5);
		$content_type_name			     = array_search($content['widget_values']['data-contenttype'], $content_type_names);
		//$content_type_id			       = $this->widget_model->get_content_type_byname($content_type_name, $content['mode']);	
        $content_type_array	             =	array("1"=> 'Article',"2" =>'Image',"3" => 'Gallery',"4" => 'Video',"5" =>'Audio');
		$content_type_id	              =	array_search($content_type_name,$content_type_array);
		$content['content_type_id']        = $content_type_id;
		$content['widget_title'] 	       = $widget_custom_title;
		
		$content['sectionID']              = $widget_section_id;
		$content['widget_title'] 	       = $widget_custom_title;
		$content['widget_title_link']      = $widget_sectionname_link;
		
		$content['widget_bg_color']        = "style='background-color:".@$widget_instance_details['Background_Color']. ";'";		
		$content['show_max_article']       = $widget_instance_details['Maximum_Articles'];
		$content['RenderingMode'] 	       = $widget_instance_details['RenderingMode'];
				
		$mode                              = $content['mode'];		
		$widget_custom_title               = $content['widget_title'];
				
		$content['widget_section_url']     = $widget_section_url;
		
		$data['content'] = $content;
		if($content['content_from']== "live"){		
		$file_name                         = FCPATH.'/application/views/'.$widget_location.".php";
		}else if($widget_location=="admin/widgets/article_details" && $content['content_from']== "preview"){	
		$file_name                         = FCPATH."/application/views/admin/widgets/article_details_preview.php";
		$widget_location                   = "admin/widgets/article_details_preview";
		}else{
		$file_name                         = FCPATH.'/application/views/'.$widget_location.".php";
		}

		if (file_exists($file_name)) {
			$this->ci = & get_instance();
			$string = $this->ci->load->view($widget_location, $data, true);			
		} else {
			$string = '<div class="row">The file '.$file_name.' does not exist</div>';
		}
		} 
	  }
		return $string;
		
	}
	
	public function get_parent_article_page($parent_section_id, $page_type)
	{
		$page_details 		= $this->widget_model->getPageDetails($parent_section_id, $page_type);	// live db	
		$xml				= (count($page_details)>0)? simplexml_load_string($page_details['published_templatexml']) : "";
		if(count($page_details)>0 && strlen($xml)!= 0){
		$xml				= simplexml_load_string($page_details['published_templatexml']);	
		if(strlen($xml) == 0 && $page_details['menuid'] != 10000)
		{	
			$section_details = $this->widget_model->get_section_by_id($page_details['menuid']); // live db
			if($section_details['IsSubSection'] == '1')
			{	
				$xml = $this->get_parent_article_page($section_details['ParentSectionID'], $page_type);
			}
			else
			{	
				/////  Is not a Sub section
				$page_details 		= $this->widget_model->getPageDetails($section_details['Section_id'], $page_type);	//live db							
				$xml				= simplexml_load_string($page_details['published_templatexml']);
				if(strlen($xml) == 0)
				{					
					//////  Standard Article page xml content  ///
					return $this->get_parent_article_page(10000, $page_type);					
				}
				else
				{					
					return $page_details;
				}
			}			
		}
		else
		{
			return $page_details; 
		}
		}elseif($page_type==2)
		{
			return $this->get_parent_article_page(10000, $page_type);	
		}
	}
	             //************************ Section Containers will call this ********************** //
	public function section_xml_containers($tplheader_values, $file_name, $is_home_page, $viewmode, $page_type, $page_param){
		$templateString['view_templ'] = '';
		$lvalue=0;
	if(count($tplheader_values)>0)
	{
			$values = $tplheader_values;
			//$layout = explode("-", $values['name']);
			//$find_layout = $layout[count($layout)-1];
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					$templateString['view_templ'] .= '<div class="row">';
                    if(isset($cvalues['containervalue']) && $cvalues['containervalue']!=''){
					    $container_layout        = explode(",", $cvalues['containervalue']);	
					}else{
						$widgetContainer_details = $this->widget_model->getContainer($cvalues['type']);
						$container_layout        = explode(",",$widgetContainer_details['container_values']);	
					}
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
						if(implode(',',$container_layout)=='2,2,2,2,2'){ $padding_zero = "grid-5"; }
						$xs_val = "12";
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						////////  Added Widgets  ///////////
						if($wvalues['id'] != '' && $wvalues['cdata-widgetStatus']!=2 )   //load only active status widget 
						{	
						date_default_timezone_set("Asia/Kolkata");														
						if( (($wvalues['cdata-widgetpublishOn']=='' || $wvalues['cdata-widgetpublishOn']=='undefined') ||($wvalues['cdata-widgetpublishOn']!='undefined' && (strtotime('now') > strtotime($wvalues['cdata-widgetpublishOn'])) )) && ((($wvalues['cdata-widgetpublishOff']=='' || $wvalues['cdata-widgetpublishOff']=='undefined') || $wvalues['cdata-widgetpublishOff']!='undefined' && (strtotime('now') < strtotime($wvalues['cdata-widgetpublishOff'])) )) ) //check schedule status
						{
							if((int) $wvalues['data-renderingtype'] == 3):
								if(SHOWADS):
								$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
								$widget_content = array( "widget_values" 	=> $wvalues,
														 "mode" 			=> $viewmode,
														 "is_home_page" 	=> $is_home_page,
														 "page_param"       => $page_param,
														 "close_param"      => '', //"?pm=".$page_param,
														 "page_type"        => $page_type,
														 "content_from"     => "live",
														 "widget_position"  => $file_name,
													);	
								$widget_file_path = $wvalues['data-widgetfilepath'];
								
								$view = $this->fill_widget($widget_file_path,$widget_content); 
								$templateString['view_templ'] .= $view. '</div>';
								endif;
							else:
								$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';					
								$widget_content = array( "widget_values" 	=> $wvalues,
														 "mode" 			=> $viewmode,
														 "is_home_page" 	=> $is_home_page,
														 "page_param"       => $page_param,
														 "close_param"      => '', //"?pm=".$page_param,
														 "page_type"        => $page_type,
														 "content_from"     => "live",
														 "widget_position"  => $file_name,
													);	
								$widget_file_path = $wvalues['data-widgetfilepath'];
								
								$view = $this->fill_widget($widget_file_path,$widget_content); 
								$templateString['view_templ'] .= $view. '</div>';
							endif;
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
           }
		//return $templateString;
		$this->ci = & get_instance();
		$view = $this->ci->load->view("common_template/".$file_name, $templateString, true);
		return $view;
					
	}
	
	//************************ Article Containers will call this ************* //
	
	public function article_xml_containers($tplheader_values, $file_name, $content_id, $is_home_page, $viewmode, $image_number, $page_type, $page_param, $content_from, $content_type_id, $content_details)
	{
	 $b_section_inc = 0;
		$templateString['view_templ'] = '';
				if(count($tplheader_values)>0)
				{
					$values = $tplheader_values;
				foreach($values->widgetcontainer as $ckey => $cvalues )
				{
					$templateString['view_templ'] .= '<div class="row">';
					if(isset($cvalues['containervalue']) && $cvalues['containervalue']!=''){
					   $container_layout = explode(",", $cvalues['containervalue']);	
					}else{
						// After Publish Article template Not USE
					   $widgetContainer_details = $this->widget_model->getContainer($cvalues['type']);
					   $container_layout = explode(",",$widgetContainer_details['container_values']);	
					}
					$c_inc = 0;	
					foreach($cvalues->widget as $wkey => $wvalues)
					{
						$c_span_val = $container_layout[$c_inc];	
						$padding_zero = "";
						if(implode(',',$container_layout)=='2,2,2,2,2'){ $padding_zero = "grid-5"; }
						$xs_val = "12";
						$c_class_value = " col-lg-".$c_span_val." col-md-".$c_span_val." col-sm-".$c_span_val." col-xs-".$xs_val." ".$padding_zero." ";		
						$templateString['view_templ'] .= '<div class="'. $c_class_value .'">';
						
						
						
						////////  Added Widgets  ///////////
						if($wvalues['id'] != '' && $wvalues['cdata-widgetStatus']!=2 )   //load only status active widget 
						{	
						if( (($wvalues['cdata-widgetpublishOn']=='' || $wvalues['cdata-widgetpublishOn']=='undefined') ||($wvalues['cdata-widgetpublishOn']!='undefined' && (strtotime('now') > strtotime($wvalues['cdata-widgetpublishOn'])) )) && ((($wvalues['cdata-widgetpublishOff']=='' || $wvalues['cdata-widgetpublishOff']=='undefined') || $wvalues['cdata-widgetpublishOff']!='undefined' && (strtotime('now') < strtotime($wvalues['cdata-widgetpublishOff'])) )) ) //check schedule status
						{
							if((int) $wvalues['data-renderingtype'] == 3):
								if(SHOWADS):
								$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';		
								$allow_comments  = ($file_name =='template_body')? (($content_from=="preview")? @$content_details[0]['Allowcomments'] : @$content_details[0]['allow_comments'] ): '';
								$detail_content = ($wvalues['data-widgetfilepath']=="admin/widgets/article_details" || $wvalues['data-widgetfilepath'] =="admin/widgets/comments")? $content_details : array('comments'=>$allow_comments) ;	
								
								$widget_content = array("content_id" 		=> $content_id,
														 "widget_values" 	=> $wvalues,
														 "mode" 			=> $viewmode,
														 "is_home_page" 	=> $is_home_page,
														 "image_number" 	=> $image_number,
														 "page_param"       => $page_param,
														 "close_param"      => '', //"?pm=".$page_param,
														 "page_type"        => $page_type,
														 "content_from"     => $content_from,
														 "content_type"     => $content_type_id,
														 "detail_content"   => $detail_content,
														 "widget_position"  => $file_name,
													);	
								$widget_file_path = $wvalues['data-widgetfilepath'];
								
								$view = $this->fill_widget($widget_file_path,$widget_content); 
								$templateString['view_templ'] .= $view. '</div>';
								endif;
							else:
								$templateString['view_templ'] .= '<div class="widget-container widget-container-' .  $wvalues['id'] . '">';		
								$allow_comments  = ($file_name =='template_body')? (($content_from=="preview")? @$content_details[0]['Allowcomments'] : @$content_details[0]['allow_comments'] ): '';
								$detail_content = ($wvalues['data-widgetfilepath']=="admin/widgets/article_details" || $wvalues['data-widgetfilepath'] =="admin/widgets/comments")? $content_details : array('comments'=>$allow_comments) ;	
								
								$widget_content = array("content_id" 		=> $content_id,
														 "widget_values" 	=> $wvalues,
														 "mode" 			=> $viewmode,
														 "is_home_page" 	=> $is_home_page,
														 "image_number" 	=> $image_number,
														 "page_param"       => $page_param,
														 "close_param"      => '', //"?pm=".$page_param,
														 "page_type"        => $page_type,
														 "content_from"     => $content_from,
														 "content_type"     => $content_type_id,
														 "detail_content"   => $detail_content,
														 "widget_position"  => $file_name,
													);	
								$widget_file_path = $wvalues['data-widgetfilepath'];
								
								$view = $this->fill_widget($widget_file_path,$widget_content); 
								$templateString['view_templ'] .= $view. '</div>';
							endif;
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
			}
				
		//return $templateString;
		$this->ci = & get_instance();
		$view = $this->ci->load->view("common_template/".$file_name, $templateString, true);
		return $view;

	}
		
}
?>