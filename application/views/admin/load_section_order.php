


<div id="Section_order" >
							<div class="ans">
								<div class="FloatLeft">
									<div class="w2ui-field">
                                     <?php
									
									 if(!empty($orderdata))
									 {
											foreach($orderdata as $key=>$value)
											{
												foreach($value as $values)
												{
													$checkvalue[]=$values;
												}
											}
									 }
									?>
                                    <select id="ddDisplayOrder1" name="ddDisplayOrder1" class="controls Width-75 margin-bottom-0">
                                         <option value="">- Select-</option>
                                    <?php
									if(!empty($parent_id) && $key = "child")
									{
										// echo "<option>". $key." -> ". $parent_id."</option>";
										?>
                                        
                                          <?php
                                           for($i=1; $i<=100; $i++)
										   {
										   	  if(isset($op_DisplayOrder))
											  {
												 if(!in_array($i, $checkvalue) || $op_DisplayOrder == $i)
												 {
										  ?>	
													<option value="<?php echo $i;?>" <?php if(isset($op_DisplayOrder)){ if($op_DisplayOrder ==$i){?> selected="selected"<?php }}else{echo set_select('ddDisplayOrder1',$i);}?>><?php echo $i;?></option>
										  <?php
															 
												  }
												
												}
												else if(!in_array($i, $checkvalue))
												{
											?>	
													<option value="<?php echo $i;?>" <?php if(isset($op_DisplayOrder)){ if($op_DisplayOrder ==$i){?> selected="selected"<?php }}else{echo set_select('ddDisplayOrder',$i);}?>><?php echo $i;?></option>
											<?php
												 }
												 
										   }
											?>
                     						
                                        	<?php
										}
										
										else if($key == "child" && $parent_id !='')
										{
											// echo "<option>". $key." -> ". $parent_id."</option>";
											?>
                                            
                                             <?php
                                           for($i=1; $i<=100; $i++)
										   {
										   	  if(isset($op_DisplayOrder))
											  {
												 if(!in_array($i, $checkvalue) || $op_DisplayOrder == $i)
												 {
										  ?>	
													<option value="<?php echo $i;?>" <?php if(isset($op_DisplayOrder)){ if($op_DisplayOrder ==$i){?> selected="selected"<?php }}else{echo set_select('ddDisplayOrder1',$i);}?>><?php echo $i;?></option>
										  <?php
															 
												  }
												
												}
												else if(!in_array($i, $checkvalue))
												{
											?>	
													<option value="<?php echo $i;?>" <?php if(isset($op_DisplayOrder)){ if($op_DisplayOrder ==$i){?> selected="selected"<?php }}else{echo set_select('ddDisplayOrder',$i);}?>><?php echo $i;?></option>
											<?php
												 }
												 
										   }
										
										}
										
										?>
                                    </select>
                                    
                                     
										<div class="w2ui-field-helper"></div>
										
                                        <p id="error_ddDisplayOrder1" class="mandatory"></p>
									
								</div>
							</div><br>
                            <p style="margin-left:172px;"><?php echo form_error('ddDisplayOrder');?></p></div>
                            </div>
                            
                            
                            
                           
