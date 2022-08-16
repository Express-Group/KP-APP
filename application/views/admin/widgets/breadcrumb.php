<?php 
$section_id         = $content['page_param'];   // from template_designer content variable
$view_mode          = $content['mode'];
$domain_name =  base_url();
$home_section_name = 'ಮುಖಪುಟ';
   if(($section_id !='home'))
   {
		$section_details = $this->widget_model->get_sectionDetails($section_id, $view_mode);
		$home_section_name = 'ಮುಖಪುಟ'; 
		$child_section_name = $section_details['Sectionname'];
		$child_section_name1 = $section_details['URLSectionStructure'];
		$sub_section_name = 'ಮುಖಪುಟ';
		if($section_details['IsSubSection'] =='1'&& $section_details['ParentSectionID']!=''&& $section_details['ParentSectionID']!=0 ){
		$sub_section = $this->widget_model->get_sectionDetails($section_details['ParentSectionID'], $view_mode);
		$sub_section_name = ($sub_section['Sectionname']!='')? $sub_section['Sectionname'] : '' ;
		$sub_section_name1= $sub_section['URLSectionStructure'];
		 if($sub_section['IsSubSection'] =='1'&& $sub_section['ParentSectionID']!=''&& $sub_section['ParentSectionID']!=0 ){
		$grand_sub_section = $this->widget_model->get_sectionDetails($sub_section['ParentSectionID'], $view_mode);
			if($grand_sub_section['IsSubSection'] =='1'&& $grand_sub_section['ParentSectionID']!=''&& $grand_sub_section['ParentSectionID']!=0 ){
				$grand_sub_section1 = $this->widget_model->get_sectionDetails($grand_sub_section['ParentSectionID'], $view_mode);
				$grand_parent_section_name = $grand_sub_section['Sectionname'];
				$grand_parent_section_name1 = $grand_sub_section['URLSectionStructure'];
				$grand_subparent_section_name = $grand_sub_section1['Sectionname'];
				$grand_subparent_section_name1 = $grand_sub_section1['URLSectionStructure'];
				$section_link = '<a href="'.$domain_name.'">'.$home_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$grand_subparent_section_name1.'">'.$grand_subparent_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$grand_parent_section_name1.'">'.$grand_parent_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$sub_section_name1.'">'.$sub_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$child_section_name1.'">'.$child_section_name.'</a>';
			}else{
				$grand_parent_section_name = $grand_sub_section['Sectionname'];
				$grand_parent_section_name1 = $grand_sub_section['URLSectionStructure'];
				$section_link = '<a href="'.$domain_name.'">'.$home_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$grand_parent_section_name1.'">'.$grand_parent_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$sub_section_name1.'">'.$sub_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$child_section_name1.'">'.$child_section_name.'</a>';
			}
		
		}else{
		$section_link = '<a href='.$domain_name.' >'.$home_section_name.'</a> <i class="fa fa-angle-right"></i> <a href='.$domain_name.$sub_section_name1.' >'.$sub_section_name.'</a> <i class="fa fa-angle-right"></i> <a href='.$domain_name.$child_section_name1.' >'.$child_section_name.'</a>';
		}
		}elseif(strtolower($child_section_name) != "Home"){
		$section_link = '<a href= '.$domain_name.' >'.$home_section_name.'</a> <i class="fa fa-angle-right"></i> <a href='.$domain_name.$child_section_name1.' >'.$child_section_name.'</a>';
		}
		else
		{
		$section_link = '<a href= '.$domain_name.' >'.$home_section_name.'</a>';
		}
   }else
   {
   $section_link = '<a href= '.$domain_name.' >'.$home_section_name.'</a>';
   }
   
?>
<div id="bcrum_section">
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="bcrums"> <?php echo $section_link;?></div>
</div>
</div>
</div>