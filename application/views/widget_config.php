<?php error_reporting(0); ?>
<?php 
$content_type_list = array();
foreach($content_type_group as $content_type_details)
{	
	$content_type_list[$content_type_details->contenttype_id] = $content_type_details->ContentTypeName;
}
if(FPM_ADDADVSCRIPTS || FPM_ADDCONFIG)
{
?>
<div id="widgetConfigWindow">
  <h4> <?php echo $widgetName?> : Widget Configuration Window</h4>
  <div class="form-container">

    <?php

	$instancemain_section = array();
	foreach($widget_instancemainsection as $wkey => $w_main_section)
	{		
		$instancemain_section_new['main_instance_id'][] = $w_main_section['WidgetInstanceMainSection_id'];
		$instancemain_section_new['main_instance_section'][]	= $w_main_section['Section_ID'];
		$instancemain_section_new['WidgetInstance_id'][]	= $w_main_section['WidgetInstance_id'];
	}
	$instancemain_section_extra = join(",",@$instancemain_section_new['main_instance_id'])."-".join(",",@$instancemain_section_new['main_instance_section'])."-".join(",",@$instancemain_section_new['WidgetInstance_id']);
	$instancesub_section = array();

	foreach($widget_instancesubsection as $wsub_key => $w_sub_section)
	{
		foreach($w_sub_section as $key => $value)
		{
			$instancesub_section[] = $value['WidgetInstanceSubSection_id']."-".$value['WidgetInstanceMainSection_id'];
			$instancesub_section_new['WidgetInstanceSubSection_id'][] = $value['WidgetInstanceSubSection_id'];
			$instancesub_section_new['WidgetInstanceMainSection_id'][] = $value['WidgetInstanceMainSection_id'];
			$instancesub_section_new['SubSection_ID'][] = $value['SubSection_ID'];
		}
		
	}
	$instancesub_section_extra = join(",",@$instancesub_section_new['WidgetInstanceSubSection_id'])."-".join(",",@$instancesub_section_new['WidgetInstanceMainSection_id'])."-".join(",",@$instancesub_section_new['SubSection_ID']);
	
	?>
    <form id="widget_config_form">
      <input type="hidden" id="typeOfConfig" value="<?php echo $widgetStyle?>"/>
      <input type="hidden" id="widget_mainsection_id_extra" name="widget_mainsection_id_extra" value="<?php echo $instancemain_section_extra; ?>"  />
      <input type="hidden" id="widget_subsection_id_extra" name="widget_subsection_id_extra" value="<?php echo $instancesub_section_extra; ?>"  />
      <ul class="widget-config-form">
      <?php
      	if(strtolower($renderingType) != "3" && FPM_ADDADVSCRIPTS) { 
	  ?>
        <li class="form-label" >Backgroud Colour</li>
        <li>
          <input type="text" name="widget_bg_color" id="widget_bg_color" value="" maxlength="10"  />
          <span id="selected_color" style="position: absolute; right: -23px; top: 14px; width:25px; height:25px;"></span>
          <div id="colorpalette1"></div>
          <script>
						  $('#colorpalette1').colorPalette()
							  .on('selectColor', function(e) {
								$('#widget_bg_color').val(e.color);
								$('#selected_color').css({"background-color" : e.color});
						  });
						</script> 
        </li>
        <?php } 
		
		if(strtolower($widgetTitleEditatble) == "1") { ?>
        <li class="form-label">Widget Title</li>
        <li>
          <input type="text" name="widgetTitle" id="widgetTitle" maxlength="50" />
		  <script type="text/javascript">
			

		  </script>
        </li>
        <?php } ?>
        <?php 
		$widget_instance_details= $this->template_design_model->getWidgetInstance('', '','', '', $widget_instance_id, 'adminview', $config_version_id);	
		$page_details 			= $this->template_design_model->load_template_by_version_id($config_version_id);							
		?>
		 <!-- start isSummary -->
        <?php if(strtolower($isSummaryAvailable) == "1") { ?>
                    <li class="form-label">Summary</li>
                    <li>                                          
                      <select name="showSummary" id="showSummary" >                        
                        <option value="1">Show</option>
                        <option value="0">Hide</option>
                      </select>                      
                    </li>                    
        <?php 
				}
		?>	
        <!-- end isSummary -->		
        <?php
				//// 1-content, 2-static, 3- iframe
				if(strtolower($renderingType) == "3" && FPM_ADDADVSCRIPTS) { 
		?>
        <li class="form-label">Advertisement Script</li>
        <li>        
          <textarea name="iframeLink" id="iframeLink"><?php echo urldecode($widget_instance_details['AdvertisementScript']); ?></textarea>
          <!-- <input type="button" name="ad_preview" id="ad_preview" value="Preview" onClick="show_adpreview()"  /> -->
          <script type="text/javascript">
				function show_adpreview()
				{
					var main_script = '';	
					var view_script = $('#iframeLink').val();
					$('#ad_preview_div').html(view_script);
					$('#ad_preview_div').css({"background-color": $('#widget_bg_color').val() });
				}													
		  </script> 
          <?php 
		  	echo urldecode($page_details['Header_Adscript']);
		  ?>
		  
        </li>
        <?php } ?>
        
        <?php if(strtolower($renderingType) == "1" && FPM_ADDCONFIG) { ?>
                    <li class="form-label">Rendering Mode</li>
                    <li>                       
					  <select name="renderingMode" id="renderingMode">
                        <option value="auto">Auto</option>
                        <option value="manual" selected >Manual</option>
                      </select>
                    </li>
                    <li class="form-label max_articles" >Max Number of Articles</li>
                    <li class="max_articles">
                      <input type="text" name="show_max_articles" id="show_max_articles" value="" maxlength="3" />
                    </li>
                    
                    
        <?php 
				}
				///// $widgetStyle = 1->Normal, 2->Simple Tab, 3->Nested tab
				if(strtolower($widgetStyle) == "1" && strtolower($renderingType) == "1" && FPM_ADDCONFIG) { ?>
        <li class="form-label">Section </li>
        <li>          
          <select name="widgetCategory" id="widgetCategory" >
            <option value="">- select a section -</option>
            <?php						
							foreach($section_group['categoryList'] as $skey => $sec_values)
							{
								
								if(count($sec_values['childCategories'][0])>1)
								{
									$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
									echo '<option value="'. $sec_values['categoryId'] .'" section_type = "'. $section_type .'" class="parent-section" >'. $sec_values['categoryName'].'</option>' ;
										if(count($sec_values['childCategories'][0])>1)
										{
											foreach($sec_values['childCategories'] as $sub_key => $sub_section)
											{
												$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
												echo '<option value="'. $sub_section['categoryId'] .'" section_type = "'. $section_type .'"> &nbsp;  '. $sub_section['categoryName'] .'</option>';
												
												if($sub_section['special_section_count'] > 0)
												{
													foreach($sec_values['special_section'][$sub_section['categoryId']] as $spl_section)
													{
														$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);														
														echo '<option value="'. $spl_section['categoryId'] .'" section_type = "'. $section_type .'"> &nbsp; &nbsp; &nbsp;  '. $spl_section['categoryName'] .'</option>';
													}
												}
											}
										}
									
									echo '</option>';
								}
								else if($sec_values['Section_landing'] != 1 && $sec_values['categoryId'] != 0 || count(@$sec_values['childCategories'][0])==1)
								{
									if(strtolower(trim($sec_values['categoryName'])) != "home" && (trim($sec_values['categoryName'])) != "முகப்பு"){
										$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
										echo '<option value="'. $sec_values['categoryId'] .'" section_type = "'. $section_type .'" class="parent-section" >  '. $sec_values['categoryName'].'</option>';
									}
								}
								
							}
						?>
          </select>
        </li>
        <?php } ?>
        <?php 
				///// 1->Normal, 2->Simple Tab, 3->Nested tab
				if(strtolower($widgetStyle) == "2" && FPM_ADDCONFIG) { ?>
        <li class="form-label">Simple Tab Section</li>
        <li>          
          <select name="simpleTabWidgetCategory" id="tabWidgetCategory" >
            <option value="">- select a section -</option>
            <?php						
						foreach($section_group['categoryList'] as $skey => $sec_values)
						{
							if(count($sec_values['childCategories'][0])>1)
								{
									$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
									echo '<option value="'. $sec_values['categoryId'] .'" section_type = "'. $section_type .'" class="parent-section" >'. $sec_values['categoryName'].'</option>' ;
									
									
									
										if(count($sec_values['childCategories'][0])>1)
										{
											foreach($sec_values['childCategories'] as $sub_section)
											{
												$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
												echo '<option value="'. $sub_section['categoryId'] .'" section_type = "'. $section_type .'"> &nbsp;  '. $sub_section['categoryName'] .'</option>';
												//if(count($sec_values['special_section']) > 0)
												if( $sub_section['special_section_count'] > 0)
												{
													foreach($sec_values['special_section'][$sub_section['categoryId']] as $spl_section)
													{
														$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
														echo '<option value="'. $spl_section['categoryId'] .'" section_type = "'. $section_type .'"> &nbsp; &nbsp; &nbsp;  '. $spl_section['categoryName'] .'</option>';
													}
												}
											}
										}
									
									echo '</optgroup>';
								}
								else if($sec_values['Section_landing'] != 1 && $sec_values['categoryId'] != 0 || count(@$sec_values['childCategories'][0])==1)
								{
									if(strtolower(trim($sec_values['categoryName'])) != "home" && (trim($sec_values['categoryName'])) != "முகப்பு"){
										$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
										echo '<option value="'. $sec_values['categoryId'] .'" section_type = "'. $section_type .'"  class="parent-section" >  '. $sec_values['categoryName'].'</option>';
									}
								}
						}
						?>
          </select>
          
          	
                
          
        </li>
        <li class="form-label SimpleTabTopic"> Tab set up           
        </li>
        <li class="SimpleTabCat">
          <div id="inputControls">
            <table class="SimpleTabTable">
			
              <!-- Start Content Type -->	
              <?php 
				if($widget_content_type == 7)
				{
			  ?>
                <tr>
              	<td class="form-label" >Content Type</td>
                <td>
					<select class="controls" name="categoryType" id="categoryType" >                    
						<?php 
							foreach($content_type_group as $content_type_details)
							{
								if(strtolower($content_type_details->ContentTypeName) != "image")
								{
									echo '<option value="'.$content_type_details->contenttype_id.'">'.$content_type_details->ContentTypeName.'</option>';
								}
								$content_type_list[$content_type_details->contenttype_id] = $content_type_details->ContentTypeName;
							}
						?>                    
                    </select>
                        
         		</td>
              </tr>
               <?php 
					}
					else
					{
						?>
                        <select class="controls" name="categoryType" id="categoryType" style="display:none;" >                    
                                <?php 
									$widget_type_name = ($widget_content_type != 2) ? $content_type_list[$widget_content_type] : "Article";
                                    foreach($content_type_group as $content_type_details)
                                    {
                                        if(strtolower($content_type_details->ContentTypeName) == strtolower($widget_type_name))
                                        {
                                            echo '<option value="'.$content_type_details->contenttype_id.'">'.$content_type_details->ContentTypeName.'</option>';
											break;
                                        }
                                    }
                                ?>                    
                            </select>
              <?php               
						
					}
				 ?>  
			 <!-- End Content Type -->
              <tr>
                <td class="form-label" >Section</td>
                <td><select id="categorySelect">
                    <option value=""></option>
                    <?php						
												foreach($section_group['categoryList'] as $skey => $sec_values)
												{
													if(count($sec_values['childCategories'][0])>1)
								{
									$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
									echo '<option value="'. $sec_values['categoryId'] .'" section_type = "'. $section_type .'" class="parent-section" >'. $sec_values['categoryName'].'</option>' ;
									
									
									
										if(count($sec_values['childCategories'][0])>1)
										{
											foreach($sec_values['childCategories'] as $sub_section)
											{
												$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
												echo '<option value="'. $sub_section['categoryId'] .'" section_type = "'. $section_type .'"> &nbsp;  '. $sub_section['categoryName'] .'</option>';
												//if(count($sec_values['special_section']) > 0)
												if($sub_section['special_section_count'] > 0)
												{
													foreach($sec_values['special_section'][$sub_section['categoryId']] as $spl_section)
													{
														$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
														echo '<option value="'. $spl_section['categoryId'] .'" section_type = "'. $section_type .'"> &nbsp; &nbsp; &nbsp;  '. $spl_section['categoryName'] .'</option>';
													}
												}
											}
										}
									
									echo '</optgroup>';
								}
								else if($sec_values['Section_landing'] != 1  && $sec_values['categoryId'] != 0 || count(@$sec_values['childCategories'][0])==1)
								{
									if(strtolower(trim($sec_values['categoryName'])) != "home" && (trim($sec_values['categoryName'])) != "முகப்பு"){
										$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
										echo '<option value="'. $sec_values['categoryId'] .'" section_type = "'. $section_type .'" class="parent-section" >  '. $sec_values['categoryName'].'</option>';
									}
								}
												}
											?>
                  </select>
                  
                  
                  
                  </td>
              </tr>
              <tr>
                <td class="form-label" > Custom Title </td>
                <td><input type="text" name="tabTitle" id="tabTitle" maxlength="50" ></td>
              </tr>
              
              <tr>
                <td colspan="2"><input type="button" id="btnWcAdd" value="Add"/>
                  <input type="button" id="btnWcUpdate" value="Update"/>
                  <input type="button" id="btnWcCancel" value="Cancel"/></td>
              </tr>
            </table>
          </div>
        </li>
        <li class="SimpleTabList">
          <div id="tabDesignWrapper">
            <ul id="tabDesignContainer">
            </ul>
          </div>
        </li>
        <?php } ?>
        <?php 
				///// 1->Normal, 2->Simple Tab, 3->Nested tab
				if(strtolower($widgetStyle) == "3" && FPM_ADDCONFIG) { ?>
        <li class="form-label SimpleTabTopic"> Set-Up Nested Tab Section 
          <!--<span style="float:right; padding-right: 155px;">Selected Section</span> --> 
        </li>
        <li class="SimpleTabCat">
          <div id="inputControls">
            <table class="SimpleTabTable">
              <tr>
                <td class="form-label" >Section</td>
                <td><select id="categorySelect">
                    <option value=""></option>
                    <?php						
											foreach($section_group['categoryList'] as $skey => $sec_values)
											{
												if(count($sec_values['childCategories'][0])>1)
								{
									$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
									echo '<option value="'. $sec_values['categoryId'] .'">'. $sec_values['categoryName'].'</option>' ;
									
									
									
										if(count($sec_values['childCategories'][0])>1)
										{
											foreach($sec_values['childCategories'] as $sub_section)
											{
												$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
												echo '<option value="'. $sub_section['categoryId'] .'"> &nbsp;  '. $sub_section['categoryName'] .'</option>';
												//if(count($sec_values['special_section']) > 0)
												if($sub_section['special_section_count'] > 0)
												{
													foreach($sec_values['special_section'][$sub_section['categoryId']] as $spl_section)
													{
														$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
														echo '<option value="'. $spl_section['categoryId'] .'"> &nbsp; &nbsp; &nbsp;  '. $spl_section['categoryName'] .'</option>';
													}
												}
											}
										}
									
									echo '</optgroup>';
								}
								else if($sec_values['Section_landing'] != 1 && $sec_values['categoryId'] != 0 || count(@$sec_values['childCategories'][0])==1)
								{
									$section_type = define_section_content_type($sec_values['categoryName'], $content_type_list);
									echo '<option value="'. $sec_values['categoryId'] .'">  '. $sec_values['categoryName'].'</option>';
								}
											}
										?>
                  </select></td>
              </tr>
              <tr>
                <td> Custom Title </td>
                <td><input type="text" name="tabTitle" id="tabTitle" maxlength="50" ></td>
              </tr>
              <tr>
                <td colspan="2"><input type="button" id="btnWcAdd" value="Add"/>
                  <input type="button" id="btnWcUpdate" value="Update"/>
                  <input type="button" id="btnWcCancel" value="Cancel"/></td>
              </tr>
            </table>
          </div>
        </li>
        <li class="SimpleTabList">
          <div id="tabDesignWrapper">
            <ul id="tabDesignContainer">
            </ul>
          </div>
        </li>
        <?php } ?>
        <li class="form-label">Publish Start Date </li>
       <li>
          <div class="input-group date">
					<input type="text" value="" id="publish_start_date" name="txtPublishStartDate" class="form-control valid" aria-invalid="false">
					<span class="input-group-addon bg-transparent"><span id="publish_starticon" class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
<script type="text/javascript">
//var jq = $.noConflict();
$(document).ready(function() {
	
	$('input').unbind("keypress").keypress(function (e) {		  
		  if (e.which == 34) {        
		  		toastr.info('" is not allowed');
				   return false;
		}	
  	});
    
    ////  (Input accepts only numbers )Disable character keys  ////
	
    $('input[name^=show_max_articles]').unbind("keypress").keypress(function (e) {          
		  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {        
                   return false;
        }	
    });
    
    $('#publish_start_date').unbind("keypress").keypress(function (e) {
         return false;	
    });
    
    $('#publish_end_date').unbind("keypress").keypress(function (e) {
         return false;	
    });			
        $('#publish_start_date').datetimepicker({
      format:'DD-MM-YYYY H:mm'
    });
    $('#publish_end_date').datetimepicker({
      format:'DD-MM-YYYY H:mm',
      useCurrent: false 
    });
    $("#publish_start_date").on("dp.change",function (e) {
    $('#publish_end_date').data("DateTimePicker").minDate(e.date);
    });
    $("#publish_end_date").on("dp.change",function (e) {
    $('#publish_start_date').data("DateTimePicker").maxDate(e.date);
    });
    
	$(document).on("click", "#publish_starticon", function (e) {
        $('#publish_start_date').trigger("focus");
    });
    $(document).on("click", "#publish_endicon", function (e) {
        $('#publish_end_date').trigger("focus");
    });
    
    controll_sections_by_conten_id();
	$('#categoryType').change(function(){
		controll_sections_by_conten_id();
	});
    
    /* Identify '"' (Double quotes) while paste the content into the above text field */
	$("#widgetTitle, #tabTitle").bind('paste', function(e) {			   
	   var pasted_str = e.originalEvent.clipboardData.getData('Text');
	   if (pasted_str.indexOf('"') >= 0){				
		   var element = this;
			setTimeout(function () {
				var text = $(element).val().replace(/\"/g, ' ');
				$(element).val(text);
				toastr.info('Your copied text have " (double quote) has been replaced with space');
			}, 1);   
	   }
	});
	
	});
    
    function controll_sections_by_conten_id()
    {
        var controll_sections_by_content_type 	= ($('#categoryType').find('option:selected').val());		
		if(controll_sections_by_content_type === 'undefined' || typeof controll_sections_by_content_type === 'undefined'){
			var widget_content_type_list			= ['','', '1', '3', '4', '5', '', 'all']
			controll_sections_by_content_type 	= widget_content_type_list['<?php echo $widget_content_type; ?>'];
		}
        
        var widget_style						= '<?php echo $widgetStyle; ?>'; // 1->Normal widget, 2->Simple tab widget
        if(controll_sections_by_content_type != "")
        {					
            if(widget_style == 1)
            {
                show_hide_sections_by_selectbox_id('widgetCategory', controll_sections_by_content_type);
            }
            else if(widget_style == 2)
            {
                if(controll_sections_by_content_type === 'undefined' || typeof controll_sections_by_content_type === 'undefined'){
					show_hide_sections_by_selectbox_id('tabWidgetCategory', controll_sections_by_content_type);
				}
                show_hide_sections_by_selectbox_id('categorySelect', controll_sections_by_content_type);
            }
            
        }
    }
    
    function show_hide_sections_by_selectbox_id(select_box_id_string, controll_sections_by_content_type)
    {
        $('#'+select_box_id_string+' option').each(function()
            {
                var child_records		= $(this).attr('section_type');				
                child_records			= (typeof child_records === 'undefined') ? '' : $(this).attr('section_type'); 
                
                if(controll_sections_by_content_type =="1")
                {
                    $('#search_bysection').find('option[value="all"]').show(); 
                    $('#search_bysection').find('option[value="all"]').attr("disabled", false)
                    if((child_records != controll_sections_by_content_type && child_records != ''))
                    {				
                        $(this).hide();
                        $(this).attr("disabled", true); 
                    }
                    else
                    {
                        $(this).show();
                        $(this).attr("disabled", false);
                    }			
                }						
                else
                {							
                    if( child_records != controll_sections_by_content_type && child_records != '' && controll_sections_by_content_type != 'all')
                    {				
                        $(this).hide();
                        $(this).attr("disabled", true); 
                    }
                    else
                    {
                        $(this).show();
                        $(this).attr("disabled", false);
                    }
                }
            });	
    }
</script>
        </li>
        <li class="form-label">Publish End Date  </li>
       <li>
         <div class="input-group date">
					<input type="text" value="" id="publish_end_date" name="txtPublishEndDate" class="form-control valid" aria-invalid="false">
					<span class="input-group-addon bg-transparent"><span id="publish_endicon" class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
                <script type="text/javascript">
               $('#publish_end_date').datetimepicker({
			  format:'DD-MM-YYYY H:mm'
			});
			$("#publish_end_date").on("dp.change",function (e) {			   
			    $('#publish_start_date').data("DateTimePicker").maxDate(e.date);
			});
			$('#publish_endicon').click(function(){
				$('#publish_end_date').click();
			});
        </script>
        </li>
        <li class="form-label">Status </li>
        <li><div class="tab">
           <div class="switch switch-blue">
               <input type="radio" value="1" class="switch-input" checked="checked" name="status" id="status1"> 
               <label class="tab-12 switch-label switch-label-off" for="status1">Active</label>
               
               <input type="radio" value="2" class="switch-input" name="status" id="status2">
               <label class="tab-12 switch-label switch-label-on" for="status2">Inactive</label>
              <span class="switch-selection"></span>
               </div>
           </div></li>
		   
		<li class="form-label" style="display:none;" >Allow this widget to clone</li>
		<li  style="display:none;">
			<input type="checkbox" name="is_cloned" id="is_cloned" <?php if($widget_instance_details['is_cloned'] == 1){ echo "checked"; } ?> /> 
		</li>
      </ul>
	  
      <div class="popup-action-bar">
      <input type="hidden" name="widget_rendering_type" id="widget_rendering_type" value="<?php echo $renderingType; ?>"  />
        <input type="button" id="configApply" value="Apply" />
        &nbsp;
        <input type="button" id="configCancel" value="Cancel" onClick="javascript:parent.$.fancybox.close(); lock_widget_config('<?php echo $widget_instance_id; ?>', '<?php echo $page_details['Page_master_id']; ?>', '1', '<?php echo $renderingType; ?>'); return false;"/>
        
      </div>
      
    </form>
    
  </div>
  
	
</div>

<div id="ad_preview_div" style="text-align:center; margin:10px;"> </div>


<?php 
}
else
{
	echo '<div id="widgetConfigWindow"><h4> You are not authorised to configure. <a href="javascript:parent.$.fancybox.close();" class="fa fa-times"></a></h4></div>';
}

function define_section_content_type($section_name, $content_type_list)
{
  $tamil_section_names_list 	= array("Gallery"=>"புகைப்படங்கள்", "Video"=>"வீடியோக்கள்", "Audio"=>"ஆடியோக்கள்", "Resources"=>'Resources'); // Except these sections are Articles
  $english_section_name_list 	= array("Gallery"=>'Galleries', "Video"=>'Videos', "Audio"=>'Audios', "Resources"=>'Resources');			  
  $content_type_name 			= array_search($section_name, $tamil_section_names_list);
  $content_type_name 			= ($content_type_name != '') ? $content_type_name : array_search($section_name, $english_section_name_list);
  $content_type_id 				= array_search($content_type_name, $content_type_list);
  return $content_type_id 		= ($content_type_id == '') ? array_search("Article", $content_type_list) : $content_type_id;
}

?>
<style>
.fancybox-opened .fancybox-skin{width:600px; margin:auto;}
	.widget-config-form {
		list-style:none;
		float:left;	
	}
	#widgetConfigWindow h4{
		padding:8px;
		background:#444;
		color:white;
		font-size:14px;
		font-weight:bold;				
		width:629px;
		margin:10px;
	}
	#widgetConfigWindow h4 a{
		/*right: 0;
		  position: relative; */
		float: right;
		color: #FFF;
		text-decoration: none;		
	}
	.form-container{
		margin:10px;
		font-size:13px;
		width:625px;
	}
	.form-container form li {
		float:left;
		width:225px;
		line-height:32px;		
		padding: 10px 18px 10px 0px;
		text-align:right;
		position:relative;
	}
	.form-container form .popup-action-bar{
		padding:10px;
		background:#ccc;
	}
	.form-container form .form-label{
		/*background:#efefef;*/
		font-weight:bold;
		text-transform: capitalize;
	}
	select{
		width:220px;
	}
	label.error{
		color:#F00;
	}
	.custom_title_msg{display:none; color:#066DA0; font-weight:bold;}
	
	/* New widget config 30-Aug-2015 */
	#tabDesignContainer {
		list-style:none;
		margin:0;
		padding:0;
	}
	#tabDesignContainer li{
		margin:2px 0;
		padding:5px;
		border:1px solid black !important;
		background :#ccc;			
	}
	#tabDesignContainer li span.close{
		line-height: 25px;
		float: right;
	}
	.sort{
		display:inline-block;
		background:#555;
		width:25px;				
		height:10px;
		margin-right:10px;
		cursor:move;
	}
	#tabDesignContainer li.ui-selected {
		background:orange;
	}
	#tabDesignContainer li.edit {
		background:pink;
	}
	#inputControls #btnWcUpdate,
	#inputControls #btnWcCancel{
		display:none;	
	}
	.max_articles { /* display:none; */ }
	.bg-transparent{ background-color:transparent !important }
	/*Section Category details*/
	.category-handler{
		float: left;
		width: auto;
		margin: 2% 0;
	}
	.category-details{
		float: left;
		width: 83%;
		line-height: 18px;
		margin: 1% 0;
	}
	.parent-section
	{
		color: #933;
		font-size: 16px;
	}
</style>