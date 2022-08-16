<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$widget_section_url  = $content['widget_section_url'];
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
// widget config block ends
//getting tab list for hte widget
$widget_instancemainsection	= $this->widget_model->get_widget_mainsection_config_rendering('', $widget_instance_id, $view_mode);

// Code block A - this code block is needed for creating simple tab widget. 

$domain_name =  base_url();
$show_simple_tab = "";
$show_simple_tab .='<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom-space-15" '.$widget_bg_color.'> 						
							<div id="parentVerticalTab_'.$widget_instance_id.'">';
							$show_simple_tab.='<fieldset class="FieldTopic">';
								
		if($content['widget_title_link'] == 1)
		{
			$show_simple_tab.=	'<legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend>';
		}
		else
		{
			$show_simple_tab.=	'<legend class="topic">'.$widget_custom_title.'</legend>';
		}
		$show_simple_tab.= ' </fieldset>
				<div class="state1">';
		$show_simple_tab .= '<ul class="resp-tabs-list hor_1" id="vertical-ticker_'.$widget_instance_id.'">';
		// Code Block A ends here
		
		// Tab Creation Block- Below code gets the record from windgetinstancemainsection table to create tabs for this widget 
		// Tab Creation Block Starts here
		$j = 0;
		
		 
		$instance_id = "";
		$widget_main_section_id = "";
		$l=1;
		foreach($widget_instancemainsection as $get_section)
		{
			$tab_id  = 	$get_section['Section_ID'];//($content['RenderingMode'] == "manual") ? $get_section['WidgetInstanceMainSection_id'] : $get_section['Section_ID'];
			$add_active=($l==1)?'class="relate resp-tab-active"' : '';
            $add_attr = ($l>1)?'id="state'.$tab_id.'" data-url="'.$get_section['URLSectionStructure'].'" ' : '';
			$show_simple_tab .= '<li '.$add_active.' '.$add_attr.'>'.$get_section['CustomTitle'].'<i class="fa fa-chevron-right"></i></li>';
			$l++;
		}
		$show_simple_tab .='</ul>
		<div class="top-down"><i class="fa fa-chevron-up" id="ticker-previous_'.$widget_instance_id.'"></i><i class="fa fa-chevron-down" id="ticker-next_'.$widget_instance_id.'"></i></div>
								</div>';
		
		
		$show_simple_tab .= '<div class="resp-tabs-container hor_1 state23">';
		// Tab Creation Block Ends here
		// Adding content Block - to add contents for each tab
		// Adding content Block starts here
		foreach($widget_instancemainsection as $get_section)
		{
			if($j==0){
				$main_sction_id = $get_section['Section_ID'];
				$main_sction_url = $get_section['URLSectionStructure'];
			}
			$show_simple_tab .='<div id="state_content'.$get_section['Section_ID'].'">
			<div class="cssload-container cssload-container-states" id="add_article_process_img'.$get_section['Section_ID'].'" style="display:none;"><figure>
  <img src="'.base_url().'images/FrontEnd/images/loader-Nie.png"><div class="cssload-zenith"></div></figure></div>
<div class=" ajaxStatus ajaxFailed noDisplay" style="display: none;">Failed to load the content...</div></div>';
			
			
			$j++;
		}
		// Adding content Block ends here
													
 $show_simple_tab .='</div></div></div></div>';
echo $show_simple_tab;
$js_path 		= base_url()."js/FrontEnd/";
?>
  <style>
.cssload-container-states img{
position: absolute;
    right:0;
    top: 0;
    width: 45px;
}
.cssload-container-states .cssload-zenith {
    height: 45px;
    width: 45px;
}
.cssload-container-states figure{ 
    left: 50%;
    position: absolute;
    top: 50%;
}
</style>
<script>	
// script used for tab creation
	
$(document).ready(function() {
	//$('#vertical-ticker_<?php echo $widget_instance_id; ?>').totemticker({	
	//			next		:	'#ticker-next_<?php echo $widget_instance_id; ?>',
	//			previous	:	'#ticker-previous_<?php echo $widget_instance_id; ?>',
	//		});
	 
show_states_articles(<?php echo $main_sction_id;?>, 'state<?php echo $main_sction_id;?>', '<?php echo $main_sction_url;?>');	

	$('#parentVerticalTab_<?php echo $widget_instance_id; ?>').easyResponsiveTabs({activate: function(event, tab){ 
	//accordion load
	 var list =$('#parentVerticalTab_<?php echo $widget_instance_id; ?> .resp-tab-item').attr('aria-controls');
var accord=$('#parentVerticalTab_<?php echo $widget_instance_id; ?> .resp-accordion').attr('aria-controls');
var itemCount = 0;
$( "#parentVerticalTab_<?php echo $widget_instance_id; ?> .resp-tab-item" ).each(function() {
if(list==accord){
	var idattr = $(this).attr('id');
    $('#parentVerticalTab_<?php echo $widget_instance_id; ?> .resp-accordion:eq(' + itemCount + ')').attr('id',idattr);
}
 itemCount++;
});
	
	var id = $(this).attr('id');
	if ($(this).attr('id')){
		var stateid= id.substring(5);
		var tab_url = $(this).attr('data-url');
		show_states_articles(stateid, id, tab_url);
		}
      },
	});
	$('#parentVerticalTab_<?php echo $widget_instance_id; ?> .resp-accordion[aria-controls="hor_1_tab_item-0"]').addClass('resp-tab-active').css('background','#337ab7');
$('#parentVerticalTab_<?php echo $widget_instance_id; ?> .resp-tab-content[aria-labelledby="hor_1_tab_item-0"]').addClass('resp-tab-content-active');
  $('#vertical-ticker_<?php echo $widget_instance_id; ?>').totemticker({ 
    next  : '#ticker-next_<?php echo $widget_instance_id; ?>',
    previous : '#ticker-previous_<?php echo $widget_instance_id; ?>',
   });	 
	  
	});
	function show_states_articles(stateid, id, tab_url_structure){
	$('#add_article_process_img'+stateid).css('display','block');
		 $.ajax({
			url			: '<?php echo base_url(); ?>user/commonwidget/get_states_content',
			method		: 'post',
			data		: { stateid: stateid,widgetinstanceid: '<?php echo $widget_instance_id;?>',mode: '<?php echo $content['mode'];?>', 'rendermode' : '<?php echo $content['RenderingMode'];?>', is_home : '<?php echo $is_home;?>', max_article : '<?php echo $content['show_max_article'];?>', summary_option: '<?php echo $is_summary_required;?>',param : '<?php echo $content['close_param'];?>',tab_url: tab_url_structure, },
			beforeSend	: function() {				
				
			},
			success		: function(result){ 
			if(id){
			$('#'+id).removeAttr('id');
			}
		   $('#state_content'+stateid).html(result).hide().fadeIn({ duration: 1000 });
		   console.clear();
		   }			
		});
	}
</script>