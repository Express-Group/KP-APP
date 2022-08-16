 <ul class="tree_head_ul">
                    <?php if(isset($section_mapping)) { 
			
				 foreach($section_mapping as $mapping) {
					 
					 		
				switch($content_type) {
					case 1:
						 if(folder_name == 'niecpan')
						 $condition = $mapping['Sectionname'] != 'Galleries' && $mapping['Sectionname'] != 'Videos' && $mapping['Sectionname'] != 'Audios' ;
						 else 
						$condition = $mapping['Sectionname'] != 'புகைப்படங்கள்' && $mapping['Sectionname'] != 'வீடியோக்கள்' && $mapping['Sectionname'] != 'ஆடியோக்கள்' ;		
						
					break;
					case 3:
						if(folder_name == 'niecpan')
						$condition =  $mapping['Sectionname'] == 'Galleries';
						else 
						$condition =  $mapping['Sectionname'] == 'புகைப்படங்கள்';	
					break;
					case 4:
						if(folder_name == 'niecpan')
							$condition =  $mapping['Sectionname'] == 'Videos';
						else 
							$condition =  $mapping['Sectionname'] == 'வீடியோக்கள்';		
					break;
					case 5:
						if(folder_name == 'niecpan')
							$condition =  $mapping['Sectionname'] == 'Audios';
						else 
							$condition =  $mapping['Sectionname'] == 'ஆடியோக்கள்';	
					break;
					default:
						$condition = TRUE;
					break;
					
				} 
				 
				 if($condition) {
				 
				 ?>
                    <li>
					
					 <?php /* if(empty($mapping['sub_section'])) {  */ ?>
					<input class="myClass" type="checkbox" id="section_mapping<?php echo $mapping['Section_id']; ?>" name="cbSectionMapping[]" main_section="" sub_main_section="" rel="<?php echo $mapping['Sectionname']; ?>" value="<?php echo $mapping['Section_id']; ?>" <?php if(isset($mapping_section) && in_array($mapping['Section_id'],$mapping_section)) echo  "checked"; if(isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $mapping['Section_id'] ) {  ?> style="visibility:hidden;" <?php } ?> <?php /* if($mapping['Section_landing'] == 1) { echo "disabled"; } */ ?>/>
					 <?php /* } */ ?>
										
					 <label class="tree_list_color" for="section_mapping<?php echo $mapping['Section_id']; ?>"><i class="fa fa-caret-right" val="1" id="MainSectionMapping"></i> <?php echo $mapping['Sectionname']; ?></label>
                    
                      <?php if(!(empty($mapping['sub_section']))) { ?>
                      	<ul class="tree_sub_ul" >
                        	<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
			     <li><input  class="myClass" type="checkbox"   id="section_mapping<?php echo $sub_mapping['Section_id']; ?>"  name="cbSectionMapping[]" main_section="<?php echo $mapping['Sectionname']; ?>"  sub_main_section="" rel="<?php echo $sub_mapping['Sectionname']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>" <?php if(isset($mapping_section) && in_array($sub_mapping['Section_id'],$mapping_section)) echo  "checked"; if(isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_mapping['Section_id'] ) {  ?> style="visibility:hidden;" <?php } ?> <?php if($sub_mapping['Section_landing'] == 1) { echo "disabled"; } ?> />	<label for="section_mapping<?php echo $sub_mapping['Section_id']; ?>"><?php echo $sub_mapping['Sectionname']; ?></label>
                  </li>
				  
					  <?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
							<ul class="tree_sub_ul" >
								<?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
					 <li><input  class="myClass" type="checkbox"   id="section_mapping<?php echo $sub_sub_mapping['Section_id']; ?>"  name="cbSectionMapping[]" main_section="<?php echo $mapping['Sectionname']; ?>" sub_main_section="<?php  echo $sub_mapping['Sectionname']; ?>" rel="<?php echo $sub_sub_mapping['Sectionname']; ?>" value="<?php echo $sub_sub_mapping['Section_id']; ?>" <?php if(isset($mapping_section) && in_array($sub_sub_mapping['Section_id'],$mapping_section)) echo  "checked"; if(isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_sub_mapping['Section_id'] ) {  ?> style="visibility:hidden;" <?php } ?> <?php if($sub_sub_mapping['Section_landing'] == 1) { echo "disabled"; } ?> />	<label for="section_mapping<?php echo $sub_sub_mapping['Section_id']; ?>"><?php echo $sub_sub_mapping['Sectionname']; ?></label>
					  </li>
								<?php } ?>
								</ul> </li>
						<?php   } ?>
				  
							<?php } ?>
                            </ul> </li>
					<?php   } ?>
				 <?php } } } ?>
                 </ul>