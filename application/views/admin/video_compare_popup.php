 <div id="compare_popup" class="CompareArticle VideoCompareArticle">
            <div class="CompareTop">
            <div>
            </div>
             <?php if($version_type == 'single_version'  ) { ?>
            <h1 class="CurrentVersion">Current Version Preview</h1>
             <?php } else { ?>
             <h1 class="CurrentVersion">Compare Version Preview</h1>
              <?php } ?>
            </div>
            <?php if($version_type == 'single_version'  ) { ?>
            <table cellpadding="0" cellspacing="0">
            <tr>
            <th>Fields</th>
            <th>Current Version : <!--<span class="Version1Flag fa fa-flag"></span>--><?php if(isset($get_version_details['VersionNumber'])) echo $get_version_details['VersionNumber'].'.0'; ?></th>
            </tr>
            <tr>
            <td><label>Main Section</label></td>
             <?php if(isset($get_version_details['Section_id'])) { ?> 
            <td><label><?php $parent_id = get_parentid_by_sectionid($get_version_details['Section_id']); 
			if($parent_id != 0) { echo get_sectionname_by_id($parent_id)." -> "; }   echo get_sectionname_by_id($get_version_details['Section_id']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            </tr>
            
           <?php /*?><tr>
            <td><label>URL Structure</label></td>
            <?php if(isset($get_version_details['url'])) { ?> 
            <td><label><?php if(isset($get_version_details['url'])) { echo $get_version_details['url']; } ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
          
            </tr><?php */?>
            
            <tr>
            <td><label>Title</label></td>
            <td><textarea id="current_version_headline" name="current_version_headline"><?php if(isset($get_version_details['Title'])) { echo $get_version_details['Title']; } ?></textarea></td>
            </tr>
            
            <tr class="ActiveArea">
            <td><label>Description</label></td>
            <td><textarea><?php if(isset($get_version_details['Description'])) echo $get_version_details['Description']; ?></textarea></td>
            </tr>
            
            <tr class="ActiveArea">
            <td><label>Script</label></td>
            <td><label><?php if(isset($get_version_details['VideoScript'])) echo htmlspecialchars_decode($get_version_details['VideoScript']); ?></label></td>
            </tr>
            
            
            <tr class="ActiveArea">
            <td><label>Meta Title</label></td>
            <td><textarea><?php if(isset($get_version_details['MetaTitle'])) echo $get_version_details['MetaTitle']; ?></textarea></td>
            </tr>
            <tr class="ActiveArea">
            <td><label>Meta Description</label></td>
            <td><textarea><?php if(isset($get_version_details['MetaDescription'])) echo $get_version_details['MetaDescription']; ?></textarea></td>
            </tr>
            
            <tr>
            <td><label>Image</label></td>
            <?php if(isset($get_version_details['image_type']) && $get_version_details['image_type'] != '') { ?>
            <td><label>
            <img src="data:image/<?php echo $get_version_details['image_type'];?>;base64,<?php echo base64_encode($get_version_details['ThumbnailImage']); ?>" alt="" border="0" style="width:70px; height:70px;"/>
            </label></td>
			<?php } else { ?>
            <td> - </td>
            <?php } ?>
            </tr>
            
            
            <tr>
            <td><label>Allow Social Buttons</label></td>
            <td><label><?php if(isset($get_version_details['Allowsocialbutton']) && $get_version_details['Allowsocialbutton'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>Allow Comments</label></td>
            <td><label><?php if(isset($get_version_details['Allowcomments']) && $get_version_details['Allowcomments'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>ByLine</label></td>
            <?php if(isset($get_version_details['Author_ID'])) { ?> 
            <td><label><?php echo get_authorname_by_id($get_version_details['Author_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
           
            </tr>
            <tr>
            <td><label>Country</label></td>
            <?php if(isset($get_version_details['Country_ID'])) { ?> 
            <td><label><?php echo get_countryname_by_id($get_version_details['Country_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
          
            </tr>
           
            <tr>
            <td><label>State</label></td>
            <?php if(isset($get_version_details['State_ID'])) { ?> 
            <td><label><?php echo get_statename_by_id($get_version_details['State_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
         
            </tr>
            <tr>
            <td><label>City</label></td>
            <?php if(isset($get_version_details['City_ID'])) { ?> 
            <td><label><?php echo get_cityname_by_id($get_version_details['City_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
          
            </tr>
            <tr>
            <td><label>Open Graph Tags</label></td>
            <td><label><?php if(isset($get_version_details['Addto_opengraphtags']) && $get_version_details['Addto_opengraphtags'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
            
            </tr>
            <tr>
            <td><label>Twitter Cards</label></td>
            <td><label><?php if(isset($get_version_details['Addto_twittercards']) && $get_version_details['Addto_twittercards'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>Schema.Org(G+)</label></td>
            <td><label><?php if(isset($get_version_details['Addto_schemeorggplus']) && $get_version_details['Addto_schemeorggplus'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>No Index</label></td>
            <td><label><?php if(isset($get_version_details['Noindexed']) && $get_version_details['Noindexed'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>No Follow</label></td>
            <td><label><?php if(isset($get_version_details['Nofollow']) && $get_version_details['Nofollow'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>Canonical URL</label></td>
            <?php if(isset($get_version_details['Canonicalurl']) &&  $get_version_details['Canonicalurl'] != '') { ?>
            <td><label><?php  echo $get_version_details['Canonicalurl']; ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            </tr>
            
            <tr>
            <td><label>Tags</label></td>
            <?php if(isset($get_version_details['Tags']) &&  $get_version_details['Tags'] != '') { ?>
            <td><label>
				
			<?php
			$str = $get_version_details['Tags'];
			$str = str_replace(",", "<br>", $str);
			
			 echo  $str;?></label></td>
             <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            </tr>
            
            <tr>
            <td><label>Multi-Section Mapping</label></td>
            
            <td><?php if(isset($get_version_mapping) && !empty($get_version_mapping)) { 
			foreach($get_version_mapping as $mapping) { ?>
			
            <label><?php echo $mapping->Sectionname; ?></label>
            <?php } } else { echo "-"; } ?>
            </td>
            
          
            </tr>
            
            </table>
            <?php } else { ?>
            <table cellpadding="0" cellspacing="0">
            <tr>
            <th>Fields</th>
            <th>   Version :<!--<span class="Version1Flag fa fa-flag"> </span>--> <?php if(isset($get_first_version_details['VersionNumber'])) echo $get_first_version_details['VersionNumber'].'.0'; ?></th>
             <th>  Version :<!--<span class="Version1Flag fa fa-flag"></span>--> <?php if(isset($get_second_version_details['VersionNumber']))  echo $get_second_version_details['VersionNumber'].'.0'; ?></th>
            </tr>
            <tr>
            <td><label>Main Section<?php if(isset($get_first_version_details['Section_id']) && isset($get_second_version_details['Section_id']) && strip_tags($get_first_version_details['Section_id']) !=  strip_tags($get_second_version_details['Section_id'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
             <?php if(isset($get_first_version_details['Section_id'])) { ?> 
            <td><label><?php $parent_id = get_parentid_by_sectionid($get_first_version_details['Section_id']); 
			if($parent_id != 0) { echo get_sectionname_by_id($parent_id)." -> "; }   echo get_sectionname_by_id($get_first_version_details['Section_id']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
              <?php if(isset($get_second_version_details['Section_id'])) { ?> 
            <td><label><?php $parent_id = get_parentid_by_sectionid($get_second_version_details['Section_id']); 
			if($parent_id != 0) { echo get_sectionname_by_id($parent_id)." -> "; }   echo get_sectionname_by_id($get_second_version_details['Section_id']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            </tr>
            <tr>
            <td><label>Title<?php if(isset($get_first_version_details['Title']) && isset($get_second_version_details['Title']) && addslashes($get_first_version_details['Title']) !=  addslashes($get_second_version_details['Title'])) { ?> <i class="fa fa-pencil"></i> <?php } ?> </label></td>
            
            <td><textarea class="ckeditor" id="first_version_headline" name="first_version_headline"><?php if(isset($get_first_version_details['Title'])) { echo $get_first_version_details['Title']; } ?></textarea></td>
            <td><textarea  class="ckeditor" id="second_version_headline" name="second_version_headline"><?php if(isset($get_second_version_details['Title'])) { echo $get_second_version_details['Title']; } ?></textarea></td>
            </tr>
            
            
            <tr class="ActiveArea">
            <td><label>Description<?php if(isset($get_first_version_details['Description']) && isset($get_second_version_details['Description']) && ($get_first_version_details['Description']) !=  ($get_second_version_details['Description'])) { ?><i class="fa fa-pencil"></i><?php } ?></label></td>
            
            <td><textarea><?php if(isset($get_first_version_details['Description'])) echo $get_first_version_details['Description']; ?></textarea></td>
             <td><textarea><?php if(isset($get_second_version_details['Description'])) echo $get_second_version_details['Description']; ?></textarea></td>
            </tr>
            
            <tr class="ActiveArea">
            <td><label>Script<?php if(isset($get_first_version_details['VideoScript']) && isset($get_second_version_details['VideoScript']) && strip_tags($get_first_version_details['VideoScript']) !=  strip_tags($get_second_version_details['VideoScript'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['VideoScript'])) echo htmlspecialchars_decode($get_first_version_details['VideoScript']); ?></label></td>
             <td><label><?php if(isset($get_second_version_details['VideoScript'])) echo htmlspecialchars_decode($get_second_version_details['VideoScript']); ?></label></td>
            </tr>
            
            
            
            <tr>
            <td><label>Image<?php if(isset($get_first_version_details['imagename']) && isset($get_second_version_details['imagename']) && strip_tags($get_first_version_details['imagename']) !=  strip_tags($get_second_version_details['imagename'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <?php if(isset($get_first_version_details['imagename']) != '' && $get_first_version_details['imagename'] != '') { ?>
            <td><label><img src="data:image/<?php echo $get_first_version_details['image_type'];?>;base64,<?php echo base64_encode($get_first_version_details['ThumbnailImage']); ?>" alt="" border="0" style="width:70px; height:70px;"/></label></td>
			<?php } else { ?>
            <td> - </td>
            <?php } ?>
             <?php if(isset($get_second_version_details['imagename']) && $get_second_version_details['imagename'] != '') { ?>
            <td><label><img src="data:image/<?php echo $get_second_version_details['image_type'];?>;base64,<?php echo base64_encode($get_second_version_details['ThumbnailImage']); ?>" alt="" border="0" style="width:70px; height:70px;"/></label></td>
			<?php } else { ?>
            <td> - </td>
            <?php } ?>
            </tr>
            
            
            <tr class="ActiveArea">
            <td><label>Meta Title<?php if(isset($get_first_version_details['MetaTitle']) && isset($get_second_version_details['MetaTitle']) && strip_tags($get_first_version_details['MetaTitle']) !=  strip_tags($get_second_version_details['MetaTitle'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><textarea><?php if(isset($get_first_version_details['MetaTitle'])) echo $get_first_version_details['MetaTitle']; ?></textarea></td>
             <td><textarea><?php if(isset($get_second_version_details['MetaTitle'])) echo $get_second_version_details['MetaTitle']; ?></textarea></td>
            </tr>
            <tr class="ActiveArea">
            <td><label>Meta Description<?php if(isset($get_first_version_details['MetaDescription']) && isset($get_second_version_details['MetaDescription']) && strip_tags($get_first_version_details['MetaDescription']) !=  strip_tags($get_second_version_details['MetaDescription'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><textarea><?php if(isset($get_first_version_details['MetaDescription'])) echo $get_first_version_details['MetaDescription']; ?></textarea></td>
             <td><textarea><?php if(isset($get_second_version_details['MetaDescription'])) echo $get_second_version_details['MetaDescription']; ?></textarea></td>
            </tr>
            <tr>
            <td><label>Allow Social Buttons<?php if(isset($get_first_version_details['Allowsocialbutton']) && isset($get_second_version_details['Allowsocialbutton']) && strip_tags($get_first_version_details['Allowsocialbutton']) !=  strip_tags($get_second_version_details['Allowsocialbutton'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['Allowsocialbutton']) && $get_first_version_details['Allowsocialbutton'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
             <td><label><?php if(isset($get_second_version_details['Allowsocialbutton']) && $get_second_version_details['Allowsocialbutton'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
            </tr>
            <tr>
            <td><label>Allow Comments<?php if(isset($get_first_version_details['Allowcomments']) && isset($get_second_version_details['Allowcomments']) && strip_tags($get_first_version_details['Allowcomments']) !=  strip_tags($get_second_version_details['Allowcomments'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['Allowcomments']) && $get_first_version_details['Allowcomments'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
            <td><label><?php if(isset($get_second_version_details['Allowcomments']) && $get_second_version_details['Allowcomments'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
            </tr>
            <tr>
            <td><label>ByLine<?php if(isset($get_first_version_details['Author_ID']) && isset($get_second_version_details['Author_ID']) && strip_tags($get_first_version_details['Author_ID']) !=  strip_tags($get_second_version_details['Author_ID'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <?php if(isset($get_first_version_details['Author_ID'])) { ?> 
            <td><label><?php echo get_authorname_by_id($get_first_version_details['Author_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            <?php if(isset($get_second_version_details['Author_ID'])) { ?> 
            <td><label><?php echo get_authorname_by_id($get_second_version_details['Author_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            </tr>
            <tr>
            <td><label>Country<?php if(isset($get_first_version_details['Country_ID']) && isset($get_second_version_details['Country_ID']) && strip_tags($get_first_version_details['Country_ID']) !=  strip_tags($get_second_version_details['Country_ID'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            <?php if(isset($get_first_version_details['Country_ID'])) { ?> 
            <td><label><?php echo get_countryname_by_id($get_first_version_details['Country_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            <?php if(isset($get_second_version_details['Country_ID'])) { ?> 
            <td><label><?php echo get_countryname_by_id($get_second_version_details['Country_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            </tr>
           
            <tr>
            <td><label>State<?php if(isset($get_first_version_details['State_ID']) && isset($get_second_version_details['State_ID']) && strip_tags($get_first_version_details['State_ID']) !=  strip_tags($get_second_version_details['State_ID'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            <?php if(isset($get_first_version_details['State_ID'])) { ?> 
            <td><label><?php echo get_statename_by_id($get_first_version_details['State_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
          <?php if(isset($get_second_version_details['State_ID'])) { ?> 
            <td><label><?php echo get_statename_by_id($get_second_version_details['State_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            </tr>
            <tr>
            <td><label>City<?php if(isset($get_first_version_details['City_ID']) && isset($get_second_version_details['City_ID']) && strip_tags($get_first_version_details['City_ID']) !=  strip_tags($get_second_version_details['City_ID'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            <?php if(isset($get_first_version_details['City_ID'])) { ?> 
            <td><label><?php echo get_cityname_by_id($get_first_version_details['City_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            <?php if(isset($get_second_version_details['City_ID'])) { ?> 
            <td><label><?php echo get_cityname_by_id($get_second_version_details['City_ID']); ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
          
            </tr>
            <tr>
            <td><label>Open Graph Tags<?php if(isset($get_first_version_details['Addto_opengraphtags']) && isset($get_second_version_details['Addto_opengraphtags']) && strip_tags($get_first_version_details['Addto_opengraphtags']) !=  strip_tags($get_second_version_details['Addto_opengraphtags'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['Addto_opengraphtags']) && $get_first_version_details['Addto_opengraphtags'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
              <td><label><?php if(isset($get_second_version_details['Addto_opengraphtags']) && $get_second_version_details['Addto_opengraphtags'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
            
            </tr>
            <tr>
            <td><label>Twitter Cards<?php if(isset($get_first_version_details['Addto_twittercards']) && isset($get_second_version_details['Addto_twittercards']) && strip_tags($get_first_version_details['Addto_twittercards']) !=  strip_tags($get_second_version_details['Addto_twittercards'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['Addto_twittercards']) && $get_first_version_details['Addto_twittercards'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
            <td><label><?php if(isset($get_second_version_details['Addto_twittercards']) && $get_second_version_details['Addto_twittercards'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>Schema.Org(G+)<?php if(isset($get_first_version_details['Addto_schemeorggplus']) && isset($get_second_version_details['Addto_schemeorggplus']) && strip_tags($get_first_version_details['Addto_schemeorggplus']) !=  strip_tags($get_second_version_details['Addto_schemeorggplus'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['Addto_schemeorggplus']) && $get_first_version_details['Addto_schemeorggplus'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           <td><label><?php if(isset($get_second_version_details['Addto_schemeorggplus']) && $get_second_version_details['Addto_schemeorggplus'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>No Index<?php if(isset($get_first_version_details['Noindexed']) && isset($get_second_version_details['Noindexed']) && strip_tags($get_first_version_details['Noindexed']) !=  strip_tags($get_second_version_details['Noindexed'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['Noindexed']) && $get_first_version_details['Noindexed'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
             <td><label><?php if(isset($get_second_version_details['Noindexed']) && $get_second_version_details['Noindexed'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>No Follow<?php if(isset($get_first_version_details['Nofollow']) && isset($get_second_version_details['Nofollow']) && strip_tags($get_first_version_details['Nofollow']) !=  strip_tags($get_second_version_details['Nofollow'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><label><?php if(isset($get_first_version_details['Nofollow']) && $get_first_version_details['Nofollow'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           <td><label><?php if(isset($get_second_version_details['Nofollow']) && $get_second_version_details['Nofollow'] == 'A' )echo "YES"; else  echo "NO"; ?></label></td>
           
            </tr>
            <tr>
            <td><label>Canonical URL<?php if(isset($get_first_version_details['Canonicalurl']) && isset($get_second_version_details['Canonicalurl']) && strip_tags($get_first_version_details['Canonicalurl']) !=  strip_tags($get_second_version_details['Canonicalurl'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <?php if(isset($get_first_version_details['Canonicalurl'])  &&  $get_first_version_details['Canonicalurl'] != '') { ?>
            <td><label><?php echo $get_first_version_details['Canonicalurl']; ?></label></td>
             <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            
            <?php if(isset($get_second_version_details['Canonicalurl'])   &&  $get_second_version_details['Canonicalurl'] != '') { ?>
             <td><label><?php echo $get_second_version_details['Canonicalurl']; ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            
            </tr>
            <tr>
            <td><label>Tags<?php if(isset($get_first_version_details['Tags']) && isset($get_second_version_details['Tags']) && strip_tags($get_first_version_details['Tags']) !=  strip_tags($get_second_version_details['Tags'])) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <?php  if(isset($get_first_version_details['Tags'])  &&  $get_first_version_details['Tags'] != '') { ?>
            <td><label><?php 
			$first_str = $get_first_version_details['Tags'];
			$first_str = str_replace(",", "<br>", $first_str);
			echo $first_str; ?></label></td>
            <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
            
             <?php  if(isset($get_second_version_details['Tags'])   &&  $get_second_version_details['Tags'] != '') { ?>
            <td><label><?php if(isset($get_second_version_details['Tags'])) { $second_str = $get_second_version_details['Tags'];
			$second_str = str_replace(",", "<br>", $second_str);
			echo $second_str; } ?></label></td>
             <?php } else { ?>
             <td><label>-</label></td>
            <?php } ?>
           
            </tr>
            
            <tr>
            <td><label>Multi-Section Mapping<?php if($mapping_compare == false) { ?> <i class="fa fa-pencil"></i> <?php } ?></label></td>
            
            <td><?php if(isset($get_first_version_mapping) && !(empty($get_first_version_mapping))) { 
			foreach($get_first_version_mapping as $mapping) { ?>
			
            <label><?php $parent_id = get_parentid_by_sectionid($mapping->Section_id); 
			if($parent_id != 0) { echo get_sectionname_by_id($parent_id)." -> "; }   echo $mapping->Sectionname; ?></label>
            <?php } } else { echo "-"; } ?>
            </td>
             
            <td><?php if(isset($get_second_version_mapping) && !(empty($get_second_version_mapping))) { 
			foreach($get_second_version_mapping as $mapping) { ?>
			
            <label><?php $parent_id = get_parentid_by_sectionid($mapping->Section_id); 
			if($parent_id != 0) { echo get_sectionname_by_id($parent_id)." -> "; }   echo $mapping->Sectionname; ?></label>
            <?php } } else { echo "-"; } ?>
            </td>
          
            </tr>
            
            </table>
            <?php } ?>
            </div>
            <a href="#" class="remodal-close"></a>