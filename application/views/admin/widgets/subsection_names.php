<?php
$widget_instance_id 	= $content['widget_values']['data-widgetinstanceid'];
$view_mode            	= $content['mode'];
$page_type 				= 'section';
$domain_name 			=  base_url();

//$get_widget_instance = $this->widget_model->getWidgetInstance('', '','', '', $widget_instance_id, $content['mode']);
//$page_section_id = $get_widget_instance['Pagesection_id'];
$page_section_id 		= $content['page_param'];
if($page_section_id != "")
{	
	$sectionname = $this->widget_model->get_sectionDetails($page_section_id, $view_mode);
	
	// This condition is for display subsection names under section name in subsection pages.
	if($sectionname['ParentSectionID'] != 0 || $sectionname['ParentSectionID'] != '') 
	{
		$get_result = $this->widget_model->get_subsection_menudisplay($sectionname['ParentSectionID'], $view_mode);				
		$parent_section = $this->widget_model->get_parent_sectionmane($page_section_id, $view_mode);				
		$section_name = $parent_section['Sectionname'];
		$url_structure = $parent_section['URLSectionStructure'];																						
		$MainSectionPageURL = $domain_name.$url_structure;
	}
	else // This condition is for display subsection names under section name in section pages.
	{
		$get_result = $this->widget_model->get_subsection_menudisplay($page_section_id, $view_mode);
		$section_name = $sectionname['Sectionname'];
		$url_structure = $sectionname['URLSectionStructure'];
		$MainSectionPageURL = $domain_name.$url_structure;	
	}
	?>
	<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="SubSections">
	<ul class="SubSections">
	<li class="topic"><a href="<?php echo $MainSectionPageURL  ?>" ><?php echo $section_name;  ?></a></li>
	<?php 
	foreach($get_result as $section){
		if($section['MenuVisibility'] == 1){
			$name=$section['Sectionname'];		 
			$parent_section = $this->widget_model->get_parent_sectionmane($section['Section_id'], $view_mode);				
			$url_structure = $section['URLSectionStructure'];
			$SubSectionPageURL = $domain_name.$url_structure;
			?>
			<li <?php if($page_section_id==$section['Section_id']){ echo 'class="active"';}?>>
				<a href="<?php echo $SubSectionPageURL?>"><i class="fa fa-angle-right"></i><?php echo $name; ?></a>
			</li>
			<?php 		 
		}
	}
	
}
?>
</ul>
</div>
</div>
</div>