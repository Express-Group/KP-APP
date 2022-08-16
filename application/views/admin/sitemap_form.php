<link href="<?php echo image_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link rel="stylesheet" href="<?php echo image_url; ?>css/admin/section_mapping.css">
<!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
-->
<div class="container section_site_map">
<ul style="margin-bottom:50px;">
<?php 
//print_r($menu);exit;
foreach($menu as $main_menu )
{
	$IsSeperatesite=$main_menu['IsSeperateWebsite'];
	$menu_name=$main_menu['Sectionname'];
	$status = $main_menu['Status'];
	$structure = $main_menu['URLSectionStructure'];
	
	$Section_id = $main_menu['Section_id'];
	
	$set_color = '';
	/*if($IsSeperatesite==1 && $status==1) {
		$set_color = 'style="color:#093"';
	} elseif($IsSeperatesite==0  && $status==0) { 
		$set_color = 'style="color:#F00"';
	} */
	
	if($status==0) { 
		$set_color = 'style="color:#F00"';
	}
	
	?>
	<li><a <?php  echo $set_color; ?> href="<?php echo base_url().$structure; ?>" data_Section_id = "<?php  echo $Section_id; ?>" target="_blank"><?php echo $menu_name; ?> </a>
		<ul>
			<?php 
				foreach($main_menu['sub_section'] as $get_sub_menu) 
                 { 
                    $sub_nemu_name = $get_sub_menu['Sectionname'];
					$sub_nemu_do = $get_sub_menu['DisplayOrder'];
					$sub_sec_status = $get_sub_menu['Status'];
					$sub_structure = $get_sub_menu['URLSectionStructure'];
					
					$sub_Section_id = $get_sub_menu['Section_id'];
             ?>
            			<li><a href="<?php echo base_url().$sub_structure; ?>" target="_blank" data_Section_id = "<?php  echo $sub_Section_id; ?>"  <?php if($sub_sec_status==0) { ?>  style="color:#F00" <?php }?>><?php echo $sub_nemu_name; ?>  </a>
           
			
							<ul>
                            <?php foreach($get_sub_menu['special_section'] as $get_splsub_menu) 
                                        { 
											 $sub_splnemu_name = $get_splsub_menu['Sectionname'];
											 $sub_splnemu_do = $get_splsub_menu['DisplayOrder'];
											 $sub_spl_status = $get_splsub_menu['Status'];
											 $sub_spl_structure = $get_splsub_menu['URLSectionStructure'];
											 
											 $sub_Section_id = $get_splsub_menu['Section_id'];
                                        ?>
                            
                            <li><a href="<?php echo base_url().$sub_spl_structure; ?>" data_Section_id = "<?php  echo $sub_Section_id; ?>" target="_blank" <?php if($sub_spl_status==0) { ?> style="color:#F00" <?php }?>><?php echo $sub_splnemu_name; ?></a></li>
                            <?php } ?>
                    
                            </ul>
			 <?php 
            	   }
			?>
                            
                        </li>
              </ul>
		</li>

<?php 
}?>
</ul>

</div>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
--><script src="<?php echo image_url; ?>js/MultiNestedList.js"></script>
