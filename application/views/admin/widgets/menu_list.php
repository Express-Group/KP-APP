 <?php 
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$domain_name         = base_url();
$view_mode           = $content['mode'];
$mega_menu           = "drop_menu"; // EMPTY THE VARIABLE TO GET MEGA MENU
//if($this->uri->segment(1)!= "niecpan"){ 
if($view_mode == "live"){ 
$page_details	     = $this->widget_model->get_template_xmlcontent($content['widget_values']['data-widgetpageid'], '');
$page_menu_id        =  $page_details['menuid']; //$content['page_param']; //$page_details['menuid'];
}else{
$page_id 		     = $this->uri->segment('4');
$page_details	     = $this->widget_model->get_template_xmlcontent($page_id , '');
$page_menu_id        =  $page_details['menuid'];
}
$activesectionid     = $page_menu_id;
if($activesectionid == 10000){
/*$menu_url_segment 	= explode("/",$this->uri->uri_string());
$segment_part       = (count($menu_url_segment)> 1) ? 2 : 1;
$page_name	        = $this->uri->segment($segment_part);

$page_details 	     = $this->widget_model->get_section_by_urlname($page_name , $view_mode);
*/
if($page_details['pagetype']!=2){
$activesectionid     = $content['page_param'];
$page_menu_id        = $content['page_param'];
}else{
$menu_url_segment 	= explode("/",$this->uri->uri_string());
$segment_part       = (count($menu_url_segment)-4);	
//print_r($segment_part);exit;
$special_section	= '';
$url_parent_section = '';
$url_sub_section 	= '';
switch($segment_part)
	{
		case 1:
			$special_section	= '';
			$url_parent_section = '';
			$url_sub_section 	= $menu_url_segment[0];
		break;
		case 2:
			$special_section	 = '';
			$url_parent_section  = $menu_url_segment[0];
			$url_sub_section 	 = $menu_url_segment[1];
		break;
		case 3:
			$special_section	= $menu_url_segment[0];
			$url_parent_section = $menu_url_segment[1];
			$url_sub_section 	= $menu_url_segment[2];
	}	
	//var_dump($url_parent_section,$url_sub_section, $special_section);exit;
	$url_section_details = $this->widget_model->get_sectionid_with_names($url_sub_section, $url_parent_section, $special_section);	 //live db	
if(count($url_section_details)>0){
$page_details = $url_section_details[0];
$activesectionid     = $page_details['Section_id'];
$page_menu_id        = $page_details['Section_id'];
}else{
$activesectionid = "home";
}
}
}
if($activesectionid=="home")
{
$page_details 	     = $this->widget_model->get_section_by_urlname("Home" , $view_mode);	
$activesectionid     = $page_details['Section_id'];
$page_menu_id        = $page_details['Section_id'];
}
$parent_section = $this->widget_model->get_parent_sectionmane($activesectionid, $view_mode);	
			
$parent_sectionname = "";
if(count($parent_section)>0)
{
	$parent_sectionname = $parent_section['Sectionname'];
}
				
/*function CheckTopParentSection($section_id) {
	 $widget_model = new Widget_model;
     $Section_Details = $widget_model->get_section_by_id($section_id);
	//$Section_Details = $this->widget_model->get_section_by_id($section_id); 
	
	if(isset($Section_Details['ParentSectionID']))
		return $Section_Details['ParentSectionID'];
	else 
		return 0;
}*/

	if(isset($activesectionid)) {
	
		if($activesectionid != '') {
              $Section_Details =  $this->widget_model->get_sectionDetails($activesectionid, $view_mode); // live db
				if($Section_Details['IsSubSection'] == '0' && $Section_Details['IsSeperateWebsite'] == '1') {
				$section_mapping 	= $this->widget_model->multiple_section_mapping_by_section_id($page_menu_id, $view_mode);
				$home_section 	= array($this->widget_model->get_sectionid_by_name("Home", $view_mode));
				$section_mapping = array_merge($home_section, $section_mapping);
				}else if((count($parent_section)>0 && $parent_section['IsSeperateWebsite'] == '1') &&( $Section_Details['IsSeperateWebsite'] == '0' ||  $Section_Details['IsSeperateWebsite'] == '1')) { 
				$CheckTopParentSection = $this->widget_model->CheckTopParentSection($Section_Details['ParentSectionID'], $view_mode);
				if($CheckTopParentSection == 0)
				{
				$section_mapping 	= $this->widget_model->multiple_section_mapping_by_section_id($Section_Details['ParentSectionID'], $view_mode);
				$home_section 	= array($this->widget_model->get_sectionid_by_name("Home", $view_mode));
				$section_mapping = array_merge($home_section, $section_mapping);
				}
				else 
				$section_mapping 	= $this->widget_model->multiple_section_mapping_by_section_id($CheckTopParentSection, $view_mode);
				}  
			else {
				$section_mapping 	= $this->widget_model->multiple_section_mapping_by_section_id("", $view_mode);
			}
			
		}
	
	}
?>

<div class="row">
  <div class="col-lg-12">
    <div class="navbar navbar-inverse navbar-fixed-top main-menu menu" role="navigation" style="margin-bottom:0px; position:relative; color:#fff;">
    <div class="navbar-header">
    <?php if(strtolower($Section_Details['Sectionname']) == "home")
				 { ?>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand home_logo" rel="home" href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a> 
        
        <?php } ?>
        </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav menus">
          <?php
		  if(isset($section_mapping)) { 
			 $Section_Count  = 0;

				 foreach($section_mapping as $mapping) { 
				 if($mapping['MenuVisibility'] === '1')  
	             { 
				 $Section_Count++;

			   $url_section_value = $mapping['URLSectionStructure'];
			   $MainSectionPageURL = base_url(). $url_section_value;												
					 				
				 if(strtolower($mapping['Sectionname']) != "home")
				 {
					
					 
					 	if(((strtolower($Section_Details['Sectionname']) == strtolower($mapping['Sectionname'])) && $Section_Details['IsSubSection'] == '0') || (($Section_Details['Sectionname'] != "Technology" ) && strtolower($parent_sectionname) == strtolower($mapping['Sectionname'])))
						{  
				 ?>
          <li class="<?php  if(!empty( $mapping['sub_section']) && $Section_Details['Sectionname'] != "Magazine" ) { echo "CitiesHover";  } else { echo "StatesHover";  } ?>" id="tab<?php echo $mapping['Section_id']; ?>"><a class="MenuItem active" id="maintabs-<?php echo $mapping['Section_id']; ?>" <?php if(count($mapping['sub_section']) == 0) { ?> onmouseover="show_main_menu('<?php echo $mapping['Section_id'];?>', 'main')" <?php } else { ?> onmouseover="show_main_menu('<?php echo $mapping['sub_section'][0]['Section_id']; ?>','<?php echo $mapping['Section_id'];?>')"  <?php }?> href="<?php echo $MainSectionPageURL; ?>"><?php echo preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$mapping['SectionnameInHTML']); ?></a>
            <?php
						}
						elseif((strtolower($Section_Details['Sectionname']) == strtolower($mapping['Sectionname'])) && $Section_Details['IsSubSection'] == '1' && $parent_section['IsSeperateWebsite'] == '1')
						{ 
				 ?>
          <li class="<?php  if(!empty( $mapping['sub_section']) && $Section_Details['Sectionname'] != "Magazine") { echo "CitiesHover";  } else { echo "StatesHover";  } ?>" id="tab<?php echo $mapping['Section_id']; ?>"><a class="MenuItem active"  id="maintabs-<?php echo $mapping['Section_id']; ?>" <?php if(count($mapping['sub_section']) == 0) { ?> onmouseover="show_main_menu('<?php echo $mapping['Section_id'];?>', 'main')" <?php } else { ?> onmouseover="show_main_menu('<?php echo $mapping['sub_section'][0]['Section_id']; ?>','<?php echo $mapping['Section_id'];?>')"  <?php }?> href="<?php echo $MainSectionPageURL; ?>"><?php echo preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$mapping['SectionnameInHTML']); ?></a>
            <?php
						}else
						{ 
				 ?>
          <li class="<?php  if(!empty( $mapping['sub_section']) && $Section_Details['Sectionname'] != "Magazine") { echo "CitiesHover";  } else { echo "StatesHover";  } ?>" id="tab<?php echo $mapping['Section_id']; ?>"><a class="MenuItem"  id="maintabs-<?php echo $mapping['Section_id']; ?>" <?php if(count($mapping['sub_section']) == 0) { ?> onmouseover="show_main_menu('<?php echo $mapping['Section_id'];?>', 'main')" <?php } else { ?> onmouseover="show_main_menu('<?php echo $mapping['sub_section'][0]['Section_id']; ?>','<?php echo $mapping['Section_id'];?>')"  <?php }?> href="<?php echo $MainSectionPageURL; ?>"><?php echo preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$mapping['SectionnameInHTML']); ?></a>
            <?php
						}
					
				 ?>
            <?php
				 	if ($Section_Details['Sectionname'] == "Magazine" )
					{
                     ?>
            <div class="MultiStatesContents MultiCitiesCont" id="maintabs_content-78">
               <!--  Magazine menu content appear here -->
            </div>
            <?php
					}
					
				 if(!empty( $mapping['sub_section']) && strtolower($mapping['Sectionname']) != "lifestyle" && strtolower($mapping['Sectionname']) != "columns" && strtolower($mapping['Sectionname']) != "magazine") { ?>
            <div class="MultiCities <?php echo $mega_menu;?>" id="tabs<?php echo $mapping['Section_id']; ?>">
              <ul class="MultiCitiesList">
                <?php $i=1; foreach($mapping['sub_section'] as $key=>$sub_section) {
					if($sub_section['MenuVisibility'] === '1')  
                    {
					$subSectionPageURL = base_url().$sub_section['URLSectionStructure'];
							 ?>
                <li class="<?php echo ($i==1)?'active':'';?>" data-target="#tabs-<?php echo $sub_section['Section_id']; ?>"><a href="<?php echo $subSectionPageURL;?>" id="subtabs-<?php echo $sub_section['Section_id']; ?>" onmouseover="show_main_menu('<?php echo $sub_section['Section_id']; ?>','')" data-id="<?php echo $sub_section['Section_id']; ?>" ><?php echo preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$sub_section['SectionnameInHTML']); ?><i class="fa fa-chevron-right"></i></a></li>
                <?php 	
					$i++;
						if($key  == 6)
							break;
					}
					} ?>
              </ul>
              <?php if(!empty( $mapping['sub_section'])) { ?>
              <div class="MultiCitiesContents tab-content">
                <?php $i=1; foreach($mapping['sub_section'] as $key=>$sub_section) { 
					if($sub_section['MenuVisibility'] === '1')  
                    { ?>
                <div id="tabs-<?php echo $sub_section['Section_id']; ?>" class="MultiCitiesCont tab-pane <?php echo ($i==1)?'active':'';?>">
                  <!--  Sub tabs menu content appear here -->
                </div>
                <?php 
					$i++;
					} 
					}?>
              </div>
              <?php 
					
					} ?>
            </div>
            <?php } else { ?>
            <div class="MultiStatesContents MultiCitiesCont" id="maintabs_content-<?php echo $mapping['Section_id'];?>">
            <!--  Main Menu content appear here -->
            </div>
            <?php } ?>
          </li>
          <?php
				 }
				 else
				 {
					 if(strtolower($Section_Details['Sectionname']) == 'home')
					 {
				 ?>
          <li class="StatesHover active"><a class="MenuItem home-ic" href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
          <?php 
                     }
                     else
                     { ?>
          <li class="StatesHover "><a class="MenuItem home-ic" href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
          <?php }
					 }
				if ($Section_Count == 12 ) 
				break;   
				
				}
				 }
			 } 
			 
			
			 
			 ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
   <?php if(isset($section_mapping)) { 

				 foreach($section_mapping as $mapping) {  ?>
     <!--Dropdown Menu--> 
	$( "#tabs<?php echo $mapping['Section_id']; ?> li" ).hover( function(){
      $(this).tab('show'); 
    });
		  <?php } } ?>
		  setTimeout(function(){sessionStorage.clear(); }, 240000);  //clear Session Storage every 4 mins
});
//$('.menus li:nth-last-child(2)').addClass('jumbo_full');
//$('.menus li:nth-last-child(3)').addClass('jumbo_full');

	   function show_main_menu(menuId, type){
		   var storage_name = "menu_content_"+menuId;
		   if (sessionStorage.getItem(storage_name)) { 	// Code for localStorage/sessionStorage.
		 var sessiondata = sessionStorage.getItem(storage_name);
			if(type=='main'){
			   $('#maintabs_content-'+menuId).html(sessiondata);
			   $('#maintabs-'+menuId).removeAttr('onmouseover');
			   }else{
				$('#tabs-'+menuId).html(sessiondata);
			   $('#maintabs-'+type).removeAttr('onmouseover');
			   $('#subtabs-'+menuId).removeAttr('data-id');
				$('#subtabs-'+menuId).removeAttr('onmouseover');
			   }
			} else { // Sorry! No Web Storage support..
		 $.ajax({
			url			: '<?php echo base_url(); ?>user/commonwidget/get_menu_content',
			method		: 'post',
			data		: { menuid: menuId, mode: '<?php echo $content['mode'];?>', 'rendermode' : '<?php echo $content['RenderingMode'];?>', is_home : '<?php echo $is_home;?>', param : '<?php echo $content['close_param'];?>', menu_type : type},
			beforeSend	: function() {				
				console.log(menuId);
				if(type=='main'){
				  document.getElementById('maintabs_content-'+menuId).innerHTML = '<figure style="text-align: center;"><img src="<?php echo base_url();?>images/FrontEnd/images/menu-loader.gif" style="width: 70px;"></figure>';
				   }else{
				   document.getElementById('tabs-'+menuId).innerHTML = '<figure style="text-align: center;"><img src="<?php echo base_url();?>images/FrontEnd/images/menu-loader.gif" style="width: 70px;position: absolute;top: 43%;left: 57%;"></figure>';
				   }
			},
			success		: function(result){ 
			       if(type=='main'){
				   $('#maintabs_content-'+menuId).html(result);
				   $('#maintabs-'+menuId).removeAttr('onmouseover');
				   }else{
				    $('#tabs-'+menuId).html(result);
				   $('#maintabs-'+type).removeAttr('onmouseover');
				   $('#subtabs-'+menuId).removeAttr('data-id');
				    $('#subtabs-'+menuId).removeAttr('onmouseover');
				   }
				   sessionStorage.setItem('menu_content_'+menuId, result);
                   console.clear();
				   }			
		});
		}
	   }
</script>