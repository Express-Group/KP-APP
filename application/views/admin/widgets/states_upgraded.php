<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$sectionid 	 = $content['sectionID'];
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
$CI = &get_instance();
$this->live_db= $CI->load->database('live_db',true);
$getSubsections = $this->live_db->query("SELECT Sectionname, Section_id, URLSectionStructure FROM sectionmaster WHERE ParentSectionID='".$sectionid."' AND Status='1' AND IsSeperateWebsite='0' ORDER BY DisplayOrder ASC")->result_array();
// widget config block ends
//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id, $view_mode);

// Code block A - this code block is needed for creating simple tab widget. 

$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" '.$widget_bg_color.'> 						
							<div class="state-up-wrapper" id="parentVerticalTab_'.$widget_instance_id.'">';
							$show_simple_tab.='<fieldset class="FieldTopic">';
								
		if($content['widget_title_link'] == 1)
		{
			$show_simple_tab.=	'<legend class="topic statestitle-'.$widget_instance_id.'"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a>';
			if(count($getSubsections) > 0){
			$show_simple_tab.= '<select id="state_'.$widget_instance_id.'" class="state-list-up">';
			if(strtolower(trim($widget_custom_title))=='states'){
				$show_simple_tab .='<option value ="common">Choose Your State</option>';
			}else if(strtolower(trim($widget_custom_title))=='cities'){
				$show_simple_tab .='<option value ="common">Choose Your City</option>';
			}else{
				$show_simple_tab .='<option value ="common">Choose Your Option</option>';
			}
			foreach($getSubsections as $get_section)
			{
				$tab_id  = 	$get_section['Section_id'];
				$show_simple_tab .= '<option value="'.$tab_id.'" data-id="state'.$tab_id.'" data-url="'.$get_section['URLSectionStructure'].'">'.$get_section['Sectionname'].'</option>';
				$l++;
			}
			$show_simple_tab.= '</select>';
			}
			$show_simple_tab.= '</legend>';
		}
		else
		{
			$show_simple_tab.=	'<legend class="topic statestitle-'.$widget_instance_id.'">'.$widget_custom_title;
			if(count($getSubsections) > 0){
			$show_simple_tab.= '<select id="state_'.$widget_instance_id.'" class="state-list-up">';
			if(strtolower(trim($widget_custom_title))=='states'){
				$show_simple_tab .='<option value ="common">Choose Your State</option>';
			}else if(strtolower(trim($widget_custom_title))=='cities'){
				$show_simple_tab .='<option value="common">Choose Your City</option>';
			}else{
				$show_simple_tab .='<option value ="common">Choose Your Option</option>';
			}
			foreach($getSubsections as $get_section)
			{
				$tab_id  = 	$get_section['Section_id'];
				$show_simple_tab .= '<option value="'.$tab_id.'" data-id="state'.$tab_id.'" data-url="'.$get_section['URLSectionStructure'].'">'.$get_section['Sectionname'].'</option>';
				$l++;
			}
			$show_simple_tab.= '</select>';
			}
			$show_simple_tab.=	'</legend>';
		}
		$show_simple_tab.= ' </fieldset>';
		if($render_mode == "manual"){
			$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article); 	
			$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
			$get_content_ids = implode("," ,$get_content_ids); 
	
			if($get_content_ids!=''){
				$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
				foreach ($widget_instance_contents as $key => $value) {
					foreach ($widget_instance_contents1 as $key1 => $value1) {
						if($value['content_id']==$value1['content_id']){
							$widget_contents[] = array_merge($value, $value1);
						}
					}
				}
			}	
	
		}else{	
			$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $content['mode'], $is_home);	
		}
		$show_simple_tab .='<div class="state-up-list state-up-ajx-'.$widget_instance_id.'">';
		$i =1;
		$count = 1;
		if(count($widget_contents)>0){
			$CI = &get_instance();
			$this->live_db = $CI->load->database('live_db',TRUE);
			foreach($widget_contents as $get_content){
				$sectionname = $get_content['section_name'];
				$custom_title        = "";
				$original_image_path = "";
				$imagealt            = "";
				$imagetitle          = "";
				$Image600X300        = "";
				$custom_title        = "";
				if($render_mode == "manual"){
					if($get_content['custom_image_path'] != ''){
						$original_image_path = $get_content['custom_image_path'];
						$imagealt            = $get_content['custom_image_title'];	
						$imagetitle          = $get_content['custom_image_alt'];												
					}
					$custom_title            = stripslashes($get_content['CustomTitle']);
				}
				if($view_mode == "live"){
					if($original_image_path ==''){
						$original_image_path = $get_content['ImagePhysicalPath'];
						$imagealt            = $get_content['ImageAlt'];	
						$imagetitle          = $get_content['ImageCaption'];
					}
				}else{
					if($original_image_path ==""){
						$original_image_path  = $get_content['ImagePhysicalPath'];
						$imagealt             = $get_content['ImageCaption'];	
						$imagetitle           = $get_content['ImageAlt'];	
					}
				}
				$show_image="";
				if($original_image_path !='' && get_image_source($original_image_path, 1)){
					$imagedetails = get_image_source($original_image_path, 2);
					$imagewidth = $imagedetails[0];
					$imageheight = $imagedetails[1];	
		
					if ($imageheight > $imagewidth){
						$Image600X300 	= $original_image_path;
					}else{
						$Image600X300  = str_replace("original","w600X390", $original_image_path);
					}
					if(get_image_source($Image600X300, 1) && $Image600X300 != ''){
						$show_image = image_url. imagelibrary_image_path . $Image600X300;
					}else{
						$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
					}
					$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}else{
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
					$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}
				if( $custom_title == ''){
					$custom_title = stripslashes($get_content['title']);
				}
				$content_url = $get_content['url'];
				$param = $content['close_param']; //page parameter
				$live_article_url = $domain_name. $content_url.$param;
				$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);
				$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
				$SectionDetails = $this->live_db->query("SELECT URLSectionStructure FROM sectionmaster WHERE Section_id='".$get_content['section_id']."'")->result();
				$sectionUrl = $domain_name.@$SectionDetails[0]->URLSectionStructure;
				if($i==1){$show_simple_tab 	   .=	'<div class="SundaySecond" '.$widget_bg_color.'>';}
		
		if($count <= 3)
		{
			if($count==1)
			{
			
			$show_simple_tab.= '<div class="WidthFloat_L">'; 
			} 
			
		$show_simple_tab.= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 SundaySecondSplit">';
		$show_simple_tab.= '<div class="states_image"><a  href="'.$live_article_url.'" class="article_click"  >
		<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a></div>';
		$show_simple_tab .='<div class="states_title"><div class="state-up-section"><a href="'.$sectionUrl.'">'.$sectionname.'</a></div><a><h4 class="subtopic">'.$display_title.'</h4></a></div>';
		$show_simple_tab.= '</div>';
		
		
		if($count==3 )
		{
			 
		$show_simple_tab.=  '</div>';
		//$show_simple_tab .='</div>';
		
		$count=0;
		
		} 
			if($i == count($widget_contents))
			{
				if($i%3!=0)
				{
				//$show_simple_tab.=  '</div>';
				$show_simple_tab .='</div>';
				} 
			$show_simple_tab .='</div>';
			}
		$count ++;	
		}
				$i =$i+1;
				
			}
		
		}elseif($view_mode=="adminview"){
			$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
		}
	    $show_simple_tab .='</div>';												
		$show_simple_tab .='</div></div></div>';
echo $show_simple_tab;
$js_path 		= base_url()."js/FrontEnd/";
?>
<script>
	 var stateshtml<?php echo $widget_instance_id  ?> = $('.state-up-ajx-<?php echo $widget_instance_id  ?>').html();
	 var statestitle<?php echo $widget_instance_id  ?> = $('.statestitle-<?php echo $widget_instance_id  ?>').find('a').html();
	  var statestitlehref<?php echo $widget_instance_id  ?> = $('.statestitle-<?php echo $widget_instance_id  ?>').find('a').attr('href');
	 
	$('#state_<?php echo $widget_instance_id  ?>').on('change',function(e){
		if($(this).val().trim()!='common'){
			$.ajax({
				url			: '<?php echo base_url(); ?>user/commonwidget/get_states_content_up',
				method		: 'post',
				data		: { stateid: $(this).val(),widgetinstanceid: '<?php echo $widget_instance_id;?>',mode: '<?php echo $content['mode'];?>', 'rendermode' : 'live', is_home : '<?php echo $is_home;?>', max_article : '<?php echo $content['show_max_article'];?>', summary_option: '<?php echo $is_summary_required;?>',param : '<?php echo $content['close_param'];?>',tab_url: $(this).attr('data-url'), },
				beforeSend : function(){
					$('.state-up-ajx-<?php echo $widget_instance_id  ?>').html('<span style="  width: 100%;float: left;text-align: center;font-size: 24px;color: #09155E;"><i class="fa fa-refresh fa-spin"></i></span>');
				},
				success		: function(result){ 
					$('.state-up-ajx-<?php echo $widget_instance_id  ?>').html(result);
					$('.state-up-section').hide();
					$('.statestitle-<?php echo $widget_instance_id  ?>').find('a').html(statestitle<?php echo $widget_instance_id  ?>+' - '+$('#state_<?php echo $widget_instance_id  ?>').find('option:selected').html());
					$('.statestitle-<?php echo $widget_instance_id  ?>').find('a').attr('href',$('#state_<?php echo $widget_instance_id  ?>').find('option:selected').attr('data-url'));
				}	
			});
		}else{
			$('.state-up-ajx-<?php echo $widget_instance_id  ?>').html(stateshtml<?php echo $widget_instance_id  ?>);
			$('.state-up-ajx-<?php echo $widget_instance_id  ?>').find('img').each(function(index){
				var t_src =$(this).attr('data-src');
				$(this).attr('src',t_src);
			});
			$('.statestitle-<?php echo $widget_instance_id  ?>').find('a').html(statestitle<?php echo $widget_instance_id  ?>);
			$('.statestitle-<?php echo $widget_instance_id  ?>').find('a').attr('href',statestitlehref<?php echo $widget_instance_id  ?>);
			$('.state-up-section').show();
		}
	});
</script>