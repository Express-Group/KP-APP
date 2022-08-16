<?php
/**
 * Common Helper Class
 *
 * @package		NewIndianExpress
 * @category	News
 * @author		IE Team
 */
  function get_parentsectiondetails_by_id($section_id) {
	 $CI = & get_instance();
	
	$SectionDetails = $CI->db->query('CALL get_parentsectiondetails_by_id(' . $section_id . ')');
	
	return $SectionDetails->row_array();
 }
function add_bylinertxt($byliner_txt, $ddAgency,$AuthorType, $user_id)
	{
		$CI = & get_instance();
		
		$add_txt = $CI->db->query("CALL addbyline_content('".$AuthorType."', '".$byliner_txt."', 1, '".$user_id."','".date("Y-m-d H:i:s")."','".$user_id."','".date("Y-m-d H:i:s")."',".$ddAgency.", @insert_id)");
echo $CI->db->last_query();		
		
		
		$result = $CI->db->query("SELECT @insert_id")->result_array();
		
		return $content_id = $result[0]['@insert_id'];
	}
	
function GetImageDetailsByContentId($ContentId)
	{
	$CI = & get_instance();
	
	$ImageDetails = $CI->db->query('CALL get_imagedetails_by_contentid(' . $ContentId . ')');
	
	return $ImageDetails->row_array();
	}
function check_article_id($content_id)
	{
		$CI = & get_instance();
		if (is_numeric($content_id))
		{
			$query = $CI->db->query('CALL check_article_content(' . $content_id . ')');
			$row_count = $query->row_array();
			
			return $row_count['count(content_id)'];
		}
		else
		{
			return 0;
		}
	}
	
function MoveDataAndImageToTemp($image_id) {
			$CI = & get_instance();
			
			$Images 	= GetImageDetailsByContentId($image_id);		
	
			if (isset($Images['ImagePhysicalPath']) && $Images['ImagePhysicalPath'] != '')
				{
					
						$CI->db->trans_begin();
					
						$Physical_name 		= GetPhysicalNameFromPhysicalPath($Images['ImagePhysicalPath']);
				
						$OldTempName 			= $Images['ImagePhysicalPath'];
						$SourceURL  		= imagelibrary_image_path;
						$DestinationURL		= article_temp_image_path;
						$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
						
							$createdon 		= $modifiedon = date('Y-m-d H:i:s');
							
							$TempName = GenerateNewImageName($OldTempName,$NewImageName);
					
							
							ImageLibraryCopyToTemp($OldTempName,$TempName ,$SourceURL,$DestinationURL);
						
							$query 				= $CI->db->query("CALL insert_temp_images('" . USERID . "'," . $Images['content_id'] . ",1,'" . addslashes($Images['ImageCaption']) . "','" . addslashes($Images['ImageAlt']) . "','".addslashes($Physical_name)."','".addslashes($TempName)."',".$Images['Image1Type'].",".$Images['Image2Type'].",".$Images['Image3Type'].",".$Images['Image4Type'].",1,'" . $createdon . "','" . $modifiedon . "',@insert_id)");
			
			
							$query 				= $CI->db->query("SELECT @insert_id");
							$returnid 			= $query->result_array();

							if ($CI->db->trans_status() === FALSE)
								$CI->db->trans_rollback();
							else
								$CI->db->trans_commit();
							
							if(isset($returnid[0]['@insert_id']) && $returnid[0]['@insert_id'] != '' ) {
							
								$data['temp_id']		= $returnid[0]['@insert_id'];
								$data['temp_image']  	= image_url.article_temp_image_path.$TempName;
								
								$Physical_extension_array = explode('.',addslashes($TempName));
								
								$data['temp_alt']			=  $Images['ImageAlt'];
								$data['physical_name']		=  $Physical_name;
								$data['temp_caption']		=  $Images['ImageCaption'];
								$data['imagecontent_id']	=  $Images['content_id'];
								$data['physical_extension'] = $Physical_extension_array[1];
								
								return $data;
							
							} else  {
								return;
							}
							
				} else {
					return;
				}
					
	}
	
function GenerateNewImageName($OldName, $NewName) {
	$OldExplode = explode('.',$OldName);
	return $NewName.'.'.@$OldExplode[1];
}
	
function ImageLibraryCopyToTemp($ImageName,$NewImageName,$SourceURL, $DestinationURL) 
{

	$Image600X390 	= str_replace("original","w600X390", $ImageName);
	$Image600X300 	= str_replace("original","w600X300", $ImageName);
	$Image100X65 	= str_replace("original","w100X65", $ImageName);
	$Image150X150 	= str_replace("original","w150X150", $ImageName);	
	
	$NewImage600X390 	= str_replace(".","_600_390.", $NewImageName);
	$NewImage600X300 	= str_replace(".","_600_300.", $NewImageName);
	$NewImage100X65 	= str_replace(".","_100_65.", $NewImageName);
	$NewImage150X150 	= str_replace(".","_150_150.", $NewImageName);	
	
	  //  Copy Original Library image To  Temp image //
					
					  
	  if (file_exists(destination_base_path . $SourceURL . $ImageName))
		copy(destination_base_path .$SourceURL . $ImageName, source_base_path . $DestinationURL . $NewImageName);
		// ImageJPEG(ImageCreateFromString( file_get_contents(source_base_path . $DestinationURL . $NewImageName)), source_base_path . $DestinationURL . $NewImageName, 45);
	  //}
					  
	   //  Copy Normal Library image To  Temp image //
	  
	   if (file_exists(destination_base_path . $SourceURL . $Image600X390))
		 copy(destination_base_path .$SourceURL . $Image600X390, source_base_path . $DestinationURL . $NewImage600X390);
		// ImageJPEG(ImageCreateFromString(file_get_contents( source_base_path . $DestinationURL . $NewImage600X390)), source_base_path . $DestinationURL . $NewImage600X390, 45);
	   //}
	  
	    //  Copy Thumb Library image To  Temp image //
	  
	    if (file_exists(destination_base_path . $SourceURL . $Image600X300))
		 copy(destination_base_path .$SourceURL . $Image600X300, source_base_path . $DestinationURL . $NewImage600X300);
		// ImageJPEG(ImageCreateFromString(file_get_contents( source_base_path . $DestinationURL . $NewImage600X300)), source_base_path . $DestinationURL . $NewImage600X300, 45);
		//}
	 
	     //  Copy Thumb Library image To  Temp image //
	  
	   if (file_exists(destination_base_path . $SourceURL . $Image100X65))
		 copy(destination_base_path .$SourceURL . $Image100X65, source_base_path . $DestinationURL .$NewImage100X65);
		//  ImageJPEG(ImageCreateFromString(file_get_contents( source_base_path . $DestinationURL . $NewImage100X65)), source_base_path . $DestinationURL . $NewImage100X65, 45);
		//}
	 
	     //  Copy Thumb Library image To  Temp image //
	  
	    if (file_exists(destination_base_path . $SourceURL . $Image150X150))
		 copy(destination_base_path .$SourceURL . $Image150X150, source_base_path . $DestinationURL . $NewImage150X150);
		// ImageJPEG(ImageCreateFromString(file_get_contents( source_base_path . $DestinationURL . $NewImage150X150)), source_base_path . $DestinationURL . $NewImage150X150, 45);
		//}

	
}

function PrepareTagInputValue($txtTags) {
	
		$TagArray = array();
		if(isset($txtTags)) {
		foreach($txtTags as $key=>$Tags) {
		
		 	if(preg_match('/([0-9]*)-?(a|d)?$/', $key, $keyparts) === 1) {
				if(isset($keyparts[2]) && $keyparts[2] =='a') {
				 		$TagArray[] = 'IE'.$keyparts[1].'IE';
				} else {
						$CI = & get_instance();
						
						$Tags = str_replace('"',"'",$Tags);
						
						$query = $CI->db->query('CALL insert_tags("' . addslashes($Tags) . '",1,"'.USERID.'",@insert_id)');
						$result = $CI->db->query("SELECT @insert_id")->row_array();
						
						$TagArray[] = 'IE'.$result['@insert_id'].'IE';
				}
		 	} 
		}
		
		}
		
		
		return implode(',',$TagArray);
	
}

function update_content_status($contentid,$content_type, $status_value, $modifiedby, $modifiedon)
{
		
	$CI = & get_instance();
	
	$query = $CI->db->query('CALL update_content_status("' . $contentid . '","' . $status_value . '","' . $modifiedby . '","' . $modifiedon . '","'.$content_type.'")');

	return $query;
	
}

	// New Coding Checking 
 


function get_countryid_by_articleid($article_id)
{
	$CI = & get_instance();
	
	$article_details = $CI->db->query('CALL get_article_by_id(' . $article_id . ')');
	$row_array = $article_details->row_array();
	
	return $row_array['Country_ID'];
}
function get_image_by_contentid($content_id)
{
	$CI = & get_instance();
	
	$article_details = $CI->db->query('CALL get_imagedetails_by_contentid(' . $content_id . ')');
	$row_array = $article_details->row_array();
	
	return  image_url.imagelibrary_image_path. @$row_array['ImagePhysicalPath'];
}
function get_imagedetails_by_contentid($content_id)
{
	$CI = & get_instance();
	
	$article_details = $CI->db->query('CALL get_imagedetails_by_contentid(' . $content_id . ')');
	$row_array = $article_details->row_array();
	
	return $row_array;
}
function get_image_by_contentid_type($content_id,$type)
{
	$CI = & get_instance();
	
	$article_details = $CI->db->query('CALL get_imagedetails_by_contentid(' . $content_id . ')');
	$row_array = $article_details->row_array();
	

	if(isset($row_array['ImagePhysicalPath'])) {
	
	$Image600X390 	= str_replace("original","w600X390", $row_array['ImagePhysicalPath']);
	$Image600X300 	= str_replace("original","w600X300", $row_array['ImagePhysicalPath']);
	$Image100X65 	= str_replace("original","w100X65", $row_array['ImagePhysicalPath']);
	$Image150X150 	= str_replace("original","w150X150", $row_array['ImagePhysicalPath']);	
	
	if($type == 1) 
	return image_url.imagelibrary_image_path. $row_array['ImagePhysicalPath'];
	else if($type == 2) 
	return image_url.imagelibrary_image_path.$Image600X390;
	else if($type == 3) 
	return image_url.imagelibrary_image_path.$Image600X300;
	else if($type == 4) 
	return image_url.imagelibrary_image_path.$Image100X65;
	else
	return image_url.imagelibrary_image_path.$Image150X150;

	} else {
		
	return '';
	
	}
	
}
function get_stateid_by_articleid($article_id)
{
	$CI = & get_instance();
	
	$article_details = $CI->db->query('CALL get_article_by_id(' . $article_id . ')');
	$row_array = $article_details->row_array();
	
	return $row_array['City_ID'];
}
function get_statename_by_id($stateid)
{
	$CI = & get_instance();
	
	$state_details = $CI->db->query('CALL get_state_by_id(' . $stateid . ')');
	$row_array = $state_details->row_array();
	
	return $row_array['StateName'];
}
function get_countryname_by_id($countryid)
{
	$CI = & get_instance();
	
	$country_details = $CI->db->query('CALL get_country_by_id(' . $countryid . ')');
	$row_array = $country_details->row_array();
	
	return @$row_array['CountryName'];
}
function get_cityname_by_id($cityid)
{
	$CI = & get_instance();
	
	$city_details = $CI->db->query('CALL get_city_by_id(' . $cityid . ')');
	$row_array = $city_details->row_array();
	
	return @$row_array['CityName'];
}
function get_author_by_name($authorname) {

	
	$CI = & get_instance();
	
	$city_details = $CI->db->query('CALL get_author_by_name("' . $authorname . '")');
	$row_array = $city_details->row_array();
	
	return $row_array;
}
/*
* Get Author Name by Author Id
*
* @access public
* @param Author Id
* @return AuthorName or Empty
*/
function get_authorname_by_id($authorid)
{
	$CI = & get_instance();
	
	$author_details = $CI->db->query('CALL get_authorname_by_id(' . $authorid . ')');
	$row_array = $author_details->row_array();
	
	return @$row_array['AuthorName'];
}
/*
* Get Author Details by Author Id
*
* @access public
* @param Author Id
* @return Author Details Array format
*/
function get_authordetails_by_id($authorid)
{
	$CI = & get_instance();
	
	$author_details = $CI->db->query('CALL get_author_by_id(' . $authorid . ')');
	$row_array = $author_details->row_array();
	
	return $row_array;
}
function get_agencyname_by_id($agencyid) 
	{
		$CI = & get_instance();
		
		$agency = $CI->db->query('CALL get_agency("'.$agencyid.'")')->row_array();
		
		return @$agency['Agency_name'];
	}
function get_sectionname_by_id($sectionid)
{
	if($sectionid != '') {
	$CI = & get_instance();	
	
	$section_details = $CI->db->query('CALL get_sectionname_by_id(' . $sectionid . ')');
	$row_array = $section_details->row_array();
	
	return @$row_array['Sectionname'];
	} else {
	return '';
	}
}
function get_section_by_id($sectionid)
{
	if($sectionid != '') {
	$CI = & get_instance();
	
	$section_details = $CI->db->query('CALL get_section_by_id(' . $sectionid . ')');
	$row_array = $section_details->row_array();
	
	return $row_array;
	} else {
	return '';
	}
}
function get_departmentname_by_id($departmentid)
{
	$CI = & get_instance();
	
	$department = $CI->db->query('CALL get_dptname_by_id("'.$departmentid.'")')->row_array();
	
	return @$department['departmentname'];
	
}
function get_rolename_by_id($roleid)
{
	$CI = & get_instance();
	
	$role = $CI->db->query('CALL get_rolename_by_id("'.$roleid.'")')->row_array();
	
	return @$role['rolename'];
	
}
function get_agency_by_id($agencyid)
{
	$CI = & get_instance();
	
	$agency = $CI->db->query('CALL get_agencyname_by_id("'.$agencyid.'")')->row_array();
	
	return @$agency['Agency_name'];
	
}
function shortDescription($fullDescription)
{
	$shortDescription = "";
	$fullDescription = htmlspecialchars_decode(trim(strip_tags($fullDescription)));
	$fullDescription = str_replace('&nbsp;', ' ', $fullDescription);
	
	if ($fullDescription)
	{
		$initialCount = 23;
		if (mb_strlen($fullDescription) > $initialCount)
		{
			 $shortDescription = mb_substr($fullDescription,0, $initialCount,'UTF-8')."...";
		}
		else
		{
			return $fullDescription;
		}
	}
	return $shortDescription;
}
function get_last_publish_history_details($ContentVersionId) {
	$CI = & get_instance();
	
	$publish_history = $CI->db->query('CALL get_last_publish_history ("' . $ContentVersionId . '")')->result_array();
	
	return $publish_history;
}
function get_last_publish_history($ContentVersionId)
{
	$CI = & get_instance();
	
	$publish_history = $CI->db->query('CALL get_last_publish_history ("' . $ContentVersionId . '")')->result_array();
	
	if (isset($publish_history[0]['ContentVersionPublishHistory_Id'])) return $publish_history[0]['ContentVersionPublishHistory_Id'];
	else return '';
}
function update_content_version_publish_history($content_version_id, $publishedon, $unpublishon, $publishedby, $unpublishby, $publish_history_id)
{
	$CI = & get_instance();
	
	$query = $CI->db->query('CALL update_content_version_publish_history("' . $content_version_id . '","' . $publishedon . '","' . $unpublishon . '","' . $publishedby . '","' . $unpublishby . '","' . $publish_history_id . '")');
	
	return $query;
	
}
function update_content_version_publish_date($content_version_id, $Lastpublishedon, $Lastunpublishedon)
{
	$CI = & get_instance();
	
	
	$query = $CI->db->query('CALL update_content_version_publish_date("' . $content_version_id . '","' . $Lastpublishedon . '","' . $Lastunpublishedon . '")');
	
	return $query;
}
function update_content_mapping($contentid, $contentversionid, $Modifiedby, $Modifiedon)
{
	$CI = & get_instance();
	
	$query = $CI->db->query('CALL update_content_mapping ("' . $contentid . '","' . $contentversionid . '","' . $Modifiedby . '","' . $Modifiedon . '")');
	
	return $query; 
}

function get_parentid_by_sectionid($section_id)
{
	$CI = & get_instance();
	
	$query = $CI->db->query('CALL get_section_by_id ("' . $section_id . '")');
	
	$row_count = $query->row_array();
	return @$row_count['ParentSectionID'];
}
function update_content_url_title($url_title, $url, $id)
{

	
	$query = $CI->db->query('CALL update_content_url_title("' . $url_title . '","' . $url . '","' . $id . '")');
	
	return $query;
}
function CheckGalleryVersionImage($galleryversion_id, $imagecontent_id)
{
	$CI = & get_instance();
	
	$query = $CI->db->query('CALL check_gallery_version_image(' . $galleryversion_id . ',' . $imagecontent_id . ')');
	
	$row_count = $query->row_array();
	return $row_count['count(GalleryRelatedId)'];
}
function CheckImageContentIdInTemp($ImageContentId)
{
	$CI = & get_instance();
	
	$query = $CI->db->query("CALL tempimagedetails('','" . $ImageContentId . "')");
	
	return $query->row_array();
}
function get_userdetails_by_id($User_id)
{
	$CI = & get_instance();
	
	$query = $CI->db->query("CALL get_userdetails_by_id ('" . $User_id . "')");
	
	$result = $query->row_array();
	return @$result['Username'];
}
function update_temp_images($ContentImageId, $crop_caption, $crop_alt, $image_600X390_type,$image_600X300_type,$image_100X65_type,$image_150X150_type, $modifiedon, $crop_image_id, $imagetype,$save_status,$crop_resize_status)
{
	$CI = & get_instance();
	
	$crop_caption = str_replace("'",'"',$crop_caption);
	$crop_alt	=	 str_replace("'",'"',$crop_alt);
	
	$query =  $CI->db->query("CALL update_temp_images(". $ContentImageId .",'" . $crop_caption . "','" . $crop_alt . "','" . $image_600X390_type . "','" . $image_600X300_type . "','" . $image_100X65_type . "','" . $image_150X150_type . "','" . $modifiedon . "','" . $crop_image_id . "','" . $imagetype . "',".$save_status.",".$crop_resize_status.")");
	 
	 return $query;
	
}
function ImageDeleteAndUpdateToLibrary($ImageName, $NewImageName,$SourceURL, $DestinationURL, $FolderMapping) 
{
	
	$Image600X390 	= str_replace(".","_600_390.", $ImageName);
	$Image600X300 	= str_replace(".","_600_300.", $ImageName);
	$Image100X65 	= str_replace(".","_100_65.", $ImageName);
	$Image150X150 	= str_replace(".","_150_150.", $ImageName);
	
		$ImageFolder600X390 	=  str_replace("original","w600X390",$FolderMapping);
		$ImageFolder600X300   	= str_replace("original","w600X300",$FolderMapping);
		$ImageFolder150X150   	= str_replace("original","w150X150",$FolderMapping);
		$ImageFolder100X65   	= str_replace("original","w100X65",$FolderMapping);
	
	  //  Copy & Delete Original Temp image //
					  
	  if (file_exists(source_base_path . $SourceURL . $ImageName))
	  {
		  if (copy(source_base_path . $SourceURL . $ImageName, destination_base_path . $DestinationURL.$FolderMapping. $NewImageName))
			unlink(source_base_path . $SourceURL .$ImageName);
		chmod(destination_base_path . $DestinationURL.$FolderMapping. $NewImageName,0777);
		
	  }
					  
	  //  Copy & Delete Image600X390 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image600X390))
	  {
		  if (copy(source_base_path . $SourceURL . $Image600X390, destination_base_path . $DestinationURL.$ImageFolder600X390 . $NewImageName))
			unlink(source_base_path . $SourceURL .$Image600X390);
		chmod(destination_base_path . $DestinationURL.$ImageFolder600X390. $NewImageName,0777);
	  }
	  
	  // Copy & Delete Image600X300 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image600X300))
	  {
	
		  
		  if (copy(source_base_path . $SourceURL . $Image600X300, destination_base_path . $DestinationURL.$ImageFolder600X300. $NewImageName))
			unlink(source_base_path . $SourceURL .$Image600X300);
		chmod(destination_base_path . $DestinationURL.$ImageFolder600X300. $NewImageName,0777);
	  }
	  
	   // Copy & Delete Image100X65 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image100X65))
	  {
		  if (copy(source_base_path . $SourceURL . $Image100X65, destination_base_path . $DestinationURL.$ImageFolder100X65. $NewImageName))
			unlink(source_base_path . $SourceURL .$Image100X65);
		chmod(destination_base_path . $DestinationURL.$ImageFolder100X65. $NewImageName,0777);
	  }
	  
	   // Copy & Delete Image150X150 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image150X150))
	  {
		  if (copy(source_base_path . $SourceURL . $Image150X150, destination_base_path . $DestinationURL.$ImageFolder150X150. $NewImageName))
			unlink(source_base_path . $SourceURL .$Image150X150);
		chmod(destination_base_path . $DestinationURL.$ImageFolder150X150. $NewImageName,0777);
	  }
}
function ImageDeleteAndPasteToLibrary($ImageName, $NewImageName,$SourceURL, $DestinationURL, $FolderMapping) 
{
	
	$Image600X390 	= str_replace(".","_600_390.", $ImageName);
	$Image600X300 	= str_replace(".","_600_300.", $ImageName);
	$Image100X65 	= str_replace(".","_100_65.", $ImageName);
	$Image150X150 	= str_replace(".","_150_150.", $ImageName);
	
		$ImageFolder600X390 	=  str_replace("original","w600X390",$FolderMapping);
		$ImageFolder600X300   	= str_replace("original","w600X300",$FolderMapping);
		$ImageFolder150X150   	= str_replace("original","w150X150",$FolderMapping);
		$ImageFolder100X65   	= str_replace("original","w100X65",$FolderMapping);

	  //  Copy & Delete Original Temp image //
	  
	  //echo source_base_path . $SourceURL . $ImageName. destination_base_path . $DestinationURL.$FolderMapping. $NewImageName;
	  
	  
	  if (file_exists(source_base_path . $SourceURL . $ImageName))
	  {
		  if (copy(source_base_path . $SourceURL . $ImageName, destination_base_path . $DestinationURL.$FolderMapping. $NewImageName))
			unlink(source_base_path . $SourceURL .$ImageName);
			chmod(destination_base_path . $DestinationURL.$FolderMapping. $NewImageName, 0777);
	  }


			//echo source_base_path . $SourceURL . $Image600X390. destination_base_path . $DestinationURL.$ImageFolder600X390 . $NewImageName;
					  
	  //  Copy & Delete Image600X390 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image600X390))
	  {
		  if (copy(source_base_path . $SourceURL . $Image600X390, destination_base_path . $DestinationURL.$ImageFolder600X390 . $NewImageName)) {
			unlink(source_base_path . $SourceURL .$Image600X390);
			chmod(destination_base_path . $DestinationURL.$ImageFolder600X390. $NewImageName, 0777);
			}
	  }

	 // echo source_base_path . $SourceURL . $Image600X390. destination_base_path . $DestinationURL.$ImageFolder600X390 . $NewImageName;
	  
	  // Copy & Delete Image600X300 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image600X300))
	  {
	
		  
		  if (copy(source_base_path . $SourceURL . $Image600X300, destination_base_path . $DestinationURL.$ImageFolder600X300. $NewImageName))
			unlink(source_base_path . $SourceURL .$Image600X300);
			chmod(destination_base_path . $DestinationURL.$ImageFolder600X300. $NewImageName, 0777);
	  }
	  
	   // Copy & Delete Image100X65 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image100X65))
	  {
		  if (copy(source_base_path . $SourceURL . $Image100X65, destination_base_path . $DestinationURL.$ImageFolder100X65. $NewImageName))
			unlink(source_base_path . $SourceURL .$Image100X65);
			chmod(destination_base_path . $DestinationURL.$ImageFolder100X65. $NewImageName, 0777);
	  }
	  
	   // Copy & Delete Image150X150 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $Image150X150))
	  {
		  if (copy(source_base_path . $SourceURL . $Image150X150, destination_base_path . $DestinationURL.$ImageFolder150X150. $NewImageName))
			unlink(source_base_path . $SourceURL .$Image150X150);
			chmod(destination_base_path . $DestinationURL.$ImageFolder150X150. $NewImageName, 0777);
	  }
	  

	  return true;
	  
}

function DeleteTempImage($TempName,$ImageId,$SourceURL) 
{
			
	$Image600X390 	= str_replace(".","_600_390.", $TempName);
	$Image600X300 	= str_replace(".","_600_300.", $TempName);
	$Image100X65 	= str_replace(".","_100_65.", $TempName);
	$Image150X150 	= str_replace(".","_150_150.", $TempName);						
	//  Delete Original Temp image //
				
	if (file_exists(source_base_path .$SourceURL . $TempName))
		unlink(source_base_path . $SourceURL .$TempName);
				
	//  Delete Normal Temp image //
				
	if (file_exists(source_base_path .$SourceURL . $Image600X390))
		unlink(source_base_path . $SourceURL .$Image600X390);
				
	// Delete Thumb Temp image //
				
	if (file_exists(source_base_path .$SourceURL . $Image600X300))
		unlink(source_base_path . $SourceURL .$Image600X300);
	
		// Delete Thumb Temp image //
				
	if (file_exists(source_base_path .$SourceURL . $Image100X65))
		unlink(source_base_path . $SourceURL .$Image100X65);
	
		// Delete Thumb Temp image //
				
	if (file_exists(source_base_path .$SourceURL . $Image150X150))
		unlink(source_base_path . $SourceURL .$Image150X150);

	///// Delete the Temp Images in Table /////
	
	$CI = & get_instance();
	
	$CI->db->query("CALL deletetempimage('" .$ImageId . "')");
	
	return true;
	
}
function CheckImageInOtherContent($ContentId)
{
	$CI = & get_instance();
	
	$ImageDetails 	= $CI->db->query('CALL check_image_in_other_content (' . $ContentId . ',@total_count)')->result_array();
	$result 		= $CI->db->query("SELECT @total_count")->row_array();
	
	
	return 			$result['@total_count'];
	
}

function header_menu()
{
	$CI =& get_instance();
	 
	$empty_val = '';
	$main_menu = $CI->db->query('CALL header_menudisplay("'.$empty_val.'")');
	
	$get_result = $main_menu->result_array();
	foreach($get_result as $key => $get_sub_menus)
	{
		$CI =& get_instance();
		
		$get_menu_id = $get_sub_menus['Menu_id'];
		$list_sub_menus = $CI->db->query('CALL header_menudisplay("'.$get_menu_id.'")');
		
		
		$get_sub_menus['sub_menu'] = $list_sub_menus->result_array();
		$get_result[$key] = $get_sub_menus;
	}
	return $get_result;
}	

function get_pagemaster_using_sectionid($section_id,$page_type) {
	$CI = & get_instance();
	
	$query = $CI->db->query("CALL get_pagemaster_using_sectionid('" . $section_id . "','". $page_type ."')");
	$result = $query->row_array();
	
	if(isset($result['templatexml']) && $result['templatexml'] != '')
	return $result['id'];
	else 
	return '';
		
	
}
function get_xmlpage_details($page_id) {
	$CI = & get_instance();
	
	$PageDetails = $CI->db->query('CALL get_xmlpage_details("' . $page_id . '")');
	
	return $PageDetails->row_array();
}
function logic_filter($result) {

 $result = array_values($result);

$num_array = count($result);

$mod = $num_array % 2;

$floor_value = floor($num_array / 2);

$j_value = 0;

$value_add = $floor_value;

$boolean_value = FALSE;

for ($i = 1; $i <= 2; $i++) {

if ($mod != 0 && $boolean_value != 1 && $i < $mod || $i == $mod)

{
 $floor_value = $floor_value+1;
 $boolean_value = TRUE;
 }

$boolean = FALSE;
 $static = 0;

for ($j = $j_value; $j < $floor_value; $j++)    {

if (isset($result[$j])) {

if ($boolean != 1) {

$static = $j;
 $boolean = TRUE;

}

$l_static = $j - $static;
 $final_result[$i][$l_static] = $result[$j]." ";

}

$boolean_value = FALSE;

}
 $boolean = FALSE;
 $j_value = $j;
 $floor_value = $floor_value + $value_add;

}
 return $final_result;

}

function GenerateTagNamesByTagIds($Tags) {
		$CI = & get_instance();
		
		$select_query 	= $CI->db->query("CALL get_tags('".$Tags."','')");		
		$result			= $select_query->result();	
			
		$tag_array 		= array();
		if(isset($result)) {
			foreach($result as $key=>$value) {
				$tag_array[$key] = $value->tag_name;
			}
		}
		echo implode(', ',$tag_array);
}
function GetTagNamesBasedMetaTitle($MetaTitle) {
	
	$MetaArray = array_filter(explode(' ',$MetaTitle),"clean");
	$MetaArray 	= array_values(array_filter($MetaArray,function($v){ return strlen($v) > 4; }));
	$MetaArray = array_map('strtolower', $MetaArray);

	
	$PreventedWords = "is are has get see need know would find take want does become come add bring begin exist tend occur seem rely vary differ react send owe shut rid insist warn inform pour remind shall submit be have use make look help go being think read keep start give play feel put set change say cut show try check call move pay let turn ask buy hold offer mean fall talk upset tell cost drive remove return run rest stay visit mix stop teach fly born gain save stand fail lead listen worry meet release sell finish press ride wait flow hit cancel cry dump push select die eat fill jump kick pass pitch treat abuse beat burn print raise sleep fix kill sit tap win pull seek sing slide strip wish dig hang hunt tie hurt laugh lay fold grab hide miss roll sink slip rush kiss marry pop pray quit reply resist rip rub smile spell tear wake wrap was like even film water been well were own study must form air place part field fish heat hand job book end point type value body price size card list mind trade line care group risk word name piece web boss sport page term test answer focus oil open range rate case cause coast age boat cash class dry plan store tax side space rule man rock act birth dog object scale sun fit note profit rent speed style war bank content craft bus eye fire box step cycle face metal paint review room screen view account ball bit pot sign ice perfect post star voice friend warm brush couple debate exit lack plant spot summer taste theme track wing brain button click correct desire fixed foot gas notice rain wall base pair staff sugar target text author file phase secure sky stage stick title trouble bowl club edge fan letter lock pack park skin sort baby carry dish exact factor fruit traffic trip chart gear land log lost net season spirit tree wave belt copy drop firm stuff tour angle blue dot essay fee limit luck milk mixed mouth pipe please seat stable bat beach blank busy catch chain cream crew detail kid mark match pain score screw sex sharp shop suit tone wise band block bone cap coat court cup hole hook layer lie nose rice tip bag bed bill cake code curve ease farm fight gap grade horse host loan mistake nail noise pause phrase race sand string arm bet blow chip coach cross draft dust floor golf habit iron knife mail pin pool shoe tackle tank trust assist bake bar bell bike clue devil diet fear fuel glove nurse pace panic peak reward row till bite clerk harm knee load ruin tired trash tune bid boot bug camp cat cow guy leg lip pen sail sock toe";
	
	$PreventedTags = explode(" ",$PreventedWords);
	
	$MetaArray = array_values(array_diff($MetaArray,$PreventedTags));
	
	if(isset($MetaArray) && count($MetaArray) > 0){
		$Like_string = '';
		foreach($MetaArray as $key=>$Meta)
		{
			if($key == 0) {
				$Like_string .= 'tag_name LIKE "%'.addslashes($Meta).'%"';
			} else {
				$Like_string .= ' OR tag_name LIKE "%'.addslashes($Meta).'%"';
			}
		}
		
		$CI = & get_instance();
		
		$select_query 	= $CI->db->query("CALL get_tags('','".$Like_string."')");		
		$result			= $select_query->result_array();
		
		/*echo $CI->db->last_query();
		exit;*/
		
	} else {
		$result = array();
	}
		
		echo json_encode($result);
}

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}


function get_menu_details_by_menu_name($menu_name) {
	
	$CI = & get_instance();
	
	$query = $CI->db->query("CALL get_menu_details_by_menu_name('" . $menu_name . "')");
	$result = $query->row_array();

	if(isset($result['Menu_id']) && $result['Menu_id'] != '')
	return $result['Menu_id'];
	else 
	show_404();
}


function update_previous_content_status($contentid, $prev_status)
{
	$CI = & get_instance();
	
	$CI->db->query("CALL update_content_previous_status('".$contentid."', '".$prev_status."')");
	
	return true;
}
function add_image_master($temp_image) {

	$CI = & get_instance();
	
								
								$temp_image['caption'] = str_replace(array("\r", "\n"), ' ', $temp_image['caption']);
								$temp_image['alt_tag'] = str_replace(array("\r", "\n"), ' ', $temp_image['alt_tag']);
							
								
								$insert_array 	= array();
								$insert_array[] = addslashes(str_replace('"',"'",$temp_image['caption']));	
								$insert_array[] = addslashes(str_replace('"',"'",$temp_image['alt_tag']));	
								$insert_array[] = $temp_image['image_name'];
								$insert_array[] = $temp_image['image1_type'];
								$insert_array[] = $temp_image['image2_type'];
								$insert_array[] = $temp_image['image3_type'];
								$insert_array[] = $temp_image['image4_type'];
								if(isset($temp_image['status'])) {
									$insert_array[] = $temp_image['status'];
								} else {
								$insert_array[] = 1;
								}
								$insert_array[] = USERID;
								$insert_array[] = date("Y-m-d H:i:s");
								$insert_array[] = USERID;
								$insert_array[] = date("Y-m-d H:i:s");
								
			$result 			= implode('","', $insert_array);
			
			$image_gallery 		= $CI->db->query('CALL add_image_master(NULL,"' . $result .'" ,@insert_id)');

			$result 			= $CI->db->query("SELECT @insert_id")->row_array();
		
	return $image_library_id 	= $result['@insert_id'];
								
}



	
	function update_content_exists_status($status, $content_id)
	{
		$CI = & get_instance();
		

		$update_status = $CI->db->query("CALL update_content_exists_status('".$status."', '".$content_id."')");
		
	}
	
	function GetPhysicalNameFromPhysicalPath($ImagePhysicalPath) {
		
			$Physical_array 	= end(explode('/',$ImagePhysicalPath));
	
			$Physical 			= explode('.',$Physical_array);
			return  @$Physical[0];
	}
	
	function create_image_folder_ckeditor($year,$month,$date) {

			if(!file_exists(source_base_path.ckeditor_image_path.$year)) {
				mkdir(source_base_path.ckeditor_image_path.$year,0777);
				chmod(source_base_path.ckeditor_image_path.$year, 0777);
			}
			
			if(!file_exists(source_base_path.ckeditor_image_path.$year."/".$month)) {
				mkdir(source_base_path.ckeditor_image_path.$year."/".$month,0777);
				chmod(source_base_path.ckeditor_image_path.$year."/".$month, 0777);
			}
			
			if(!file_exists(source_base_path.ckeditor_image_path.$year."/".$month."/".$date)) {
				mkdir(source_base_path.ckeditor_image_path.$year."/".$month."/".$date,0777);
				chmod(source_base_path.ckeditor_image_path.$year."/".$month."/".$date, 0777);
				
			}
				
	}
	
	function create_image_folder_resource($year,$month,$date,$resource_path) {
		

			if(!file_exists(source_base_path.$resource_path.$year)) {
				mkdir(source_base_path.$resource_path.$year,0777);
				chmod(source_base_path.$resource_path.$year, 0777);
			}
			
			if(!file_exists(source_base_path.$resource_path.$year."/".$month)) {
				mkdir(source_base_path.$resource_path.$year."/".$month,0777);
				chmod(source_base_path.$resource_path.$year."/".$month, 0777);
			}
			
			if(!file_exists(source_base_path.$resource_path.$year."/".$month."/".$date)) {
				mkdir(source_base_path.$resource_path.$year."/".$month."/".$date,0777);
				chmod(source_base_path.$resource_path.$year."/".$month."/".$date, 0777);
				
			}
				
	}
	
	function create_image_folder($year,$month,$date) {
		
			if(!file_exists(destination_base_path.imagelibrary_image_path.$year)) {
				mkdir(destination_base_path.imagelibrary_image_path.$year,0777);
				chmod(destination_base_path.imagelibrary_image_path.$year, 0777);
			}
			
			if(!file_exists(destination_base_path.imagelibrary_image_path.$year."/".$month)) {
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month,0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month, 0777);
			}
			
			if(!file_exists(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date)) {
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date,0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date, 0777);
				
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/original",0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/original", 0777);
				
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X390",0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X390", 0777);
				
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X300",0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w600X300", 0777);
				
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w150X150",0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w150X150", 0777);
				
				mkdir(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w100X65",0777);
				chmod(destination_base_path.imagelibrary_image_path.$year."/".$month."/".$date."/w100X65", 0777);
				
			}
				
	}
	
	function RenameImageLibraryName($physical_name,$content_id) {
		$CI = & get_instance();
		
				$ImageDetails = get_imagedetails_by_contentid($content_id);
				
				$OldPhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
				
				$NewPhysicalPath = str_replace($OldPhysicalName,$physical_name,$ImageDetails['ImagePhysicalPath']);
				
				if(file_exists(destination_base_path . imagelibrary_image_path . $NewPhysicalPath)) {
						for($count = 1; $count <= 20; $count++) {
						$NewPhysicalPathRand = str_replace(".","_".$count.".",$NewPhysicalPath);
							
							if(!file_exists(destination_base_path . imagelibrary_image_path . $NewPhysicalPathRand)) {
								$NewPhysicalPath = $NewPhysicalPathRand;
								break;
							}	
						}
				
				}
				
				if(ImageLibraryRename($ImageDetails['ImagePhysicalPath'],$NewPhysicalPath)) {				
				
				$insert_array 	= array();
				$insert_array[] = $ImageDetails['contentversion_id'];
				$insert_array[] = $ImageDetails['ImageAlt'];
				$insert_array[] = $ImageDetails['Height'];
				$insert_array[] = $ImageDetails['Width'];
				$insert_array[] = $ImageDetails['image_size'];
				$insert_array[] = addslashes($ImageDetails['ImageBinaryData']);
				$insert_array[] = $NewPhysicalPath;
				$insert_array[] = addslashes($ImageDetails['ImageBinaryData1']);
				$insert_array[] = addslashes($ImageDetails['ImageBinaryData2']);
				$insert_array[] = addslashes($ImageDetails['ImageBinaryData3']);
				$insert_array[] = addslashes($ImageDetails['ImageBinaryData4']);
				$insert_array[] = ($ImageDetails['Image1Type'] == 0 ? 1 : $ImageDetails['Image1Type']);
				$insert_array[] = ($ImageDetails['Image2Type'] == 0 ? 1 : $ImageDetails['Image2Type']);
				$insert_array[] = ($ImageDetails['Image3Type'] == 0 ? 1 : $ImageDetails['Image3Type']);
				$insert_array[] = ($ImageDetails['Image4Type'] == 0 ? 1 : $ImageDetails['Image4Type']);
				$insert_array[] = USERID;
				$insert_array[] = date("Y-m-d H:i:s");
				$insert_array[] = USERID;
				$insert_array[] = date("Y-m-d H:i:s");
				$result_array   = implode('","', $insert_array);
				
				
				$image_gallery = $CI->db->query('CALL update_image_related_data("' . $result_array . '")');		
				
				
				}
	
	}
	
	function ImageLibraryRename($ImageNamePath,$NewImageNamePath) 
{
			$bool1_orginal = false;
			$bool2_orginal = false;
			$bool3_orginal = false;
			$bool4_orginal = false;
			$bool5_orginal = false;
	
		$DestinationURL = $SourceURL = imagelibrary_image_path;
		
		$SourceImageFolder600X390 	=  str_replace("original","w600X390",$ImageNamePath);
		$SourceImageFolder600X300   	= str_replace("original","w600X300",$ImageNamePath);
		$SourceImageFolder150X150   	= str_replace("original","w150X150",$ImageNamePath);
		$SourceImageFolder100X65   	= str_replace("original","w100X65",$ImageNamePath);

		$DistinationImageFolder600X390 	=  str_replace("original","w600X390",$NewImageNamePath);
		$DistinationImageFolder600X300   	= str_replace("original","w600X300",$NewImageNamePath);
		$DistinationImageFolder150X150   	= str_replace("original","w150X150",$NewImageNamePath);
		$DistinationImageFolder100X65   	= str_replace("original","w100X65",$NewImageNamePath);
		
	  //  Copy & Delete Original Temp image //
	  
	  echo source_base_path . $SourceURL . $ImageNamePath. destination_base_path . $DestinationURL. $NewImageNamePath;
	  
	  
	  if (file_exists(source_base_path . $SourceURL . $ImageNamePath))
	  {
		  if (rename(source_base_path . $SourceURL . $ImageNamePath, destination_base_path . $DestinationURL. $NewImageNamePath))
			chmod(destination_base_path . $DestinationURL.$NewImageNamePath, 0777);
		$bool1_orginal = true;
	  }
					  
	  //  Copy & Delete Image600X390 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $SourceImageFolder600X390))
	  {
		  if (rename(source_base_path . $SourceURL . $SourceImageFolder600X390, destination_base_path . $DestinationURL.$DistinationImageFolder600X390))
			chmod(destination_base_path . $DestinationURL.$DistinationImageFolder600X390, 0777);
		$bool2_orginal = true;
	  }
	  
	  // Copy & Delete Image600X300 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $SourceImageFolder600X300))
	  {  
		  if (rename(source_base_path . $SourceURL . $SourceImageFolder600X300, destination_base_path . $DestinationURL.$DistinationImageFolder600X300))
			chmod(destination_base_path . $DestinationURL.$DistinationImageFolder600X300, 0777);
		$bool3_orginal = true;
	  }
	  
	   // Copy & Delete Image100X65 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $SourceImageFolder100X65))
	  {
		  if (rename(source_base_path . $SourceURL . $SourceImageFolder100X65, destination_base_path . $DestinationURL.$DistinationImageFolder100X65))
			chmod(destination_base_path . $DestinationURL.$DistinationImageFolder100X65, 0777);
		$bool4_orginal = true;
	  }
	  
	   // Copy & Delete Image150X150 Temp image //
	  
	   if (file_exists(source_base_path . $SourceURL . $SourceImageFolder150X150))
	  {
		  if (rename(source_base_path . $SourceURL . $SourceImageFolder150X150, destination_base_path . $DestinationURL.$DistinationImageFolder150X150))
			chmod(destination_base_path . $DestinationURL.$DistinationImageFolder150X150, 0777);
		$bool5_orginal = true;
	  }
	  
	  if($bool1_orginal == true && $bool2_orginal == true && $bool3_orginal == true && $bool4_orginal == true && $bool5_orginal == true ) 
		  return true;
	  else
		  return false;
	  
	
}

function column_editdetails($topic) {
	$CI = & get_instance();
	
	
	$query = $CI->db->query("CALL column_editdetails (". $topic.")")->result_array();
	
	
	return $query;
}
function GetBreadCrumbByURL($BreadCrumbURL) {
	$ArrayElement = explode("/",$BreadCrumbURL);
	$Items = array_slice($ArrayElement, -4);

	return implode("/",array_diff( $ArrayElement, $Items ));
	
}

function GenerateBreadCrumbBySectionId($Section_id) {
	$MainSection = get_section_by_id(@$Section_id);

	if(isset($MainSection)) {
		$MainSectionName = $MainSection['Sectionname'];
		
		if(isset($MainSection['ParentSectionID']) && $MainSection['ParentSectionID'] != '') {
					
					$ParentMainSection = get_section_by_id($MainSection['ParentSectionID']);
					$ParentMainSectionName = $ParentMainSection['Sectionname'];
					
					if(isset($ParentMainSection['ParentSectionID']) && $ParentMainSection['ParentSectionID'] != '') {
						$GrantMainSection = get_section_by_id($ParentMainSection['ParentSectionID']);
						$GrantMainSectionName = $GrantMainSection['Sectionname'];
					}	
		}
	}
	
	$BreadCrumbURL = @$GrantMainSectionName;
	
	if(isset($ParentMainSectionName) && $BreadCrumbURL != '') 
		$BreadCrumbURL .="/".$ParentMainSectionName;
	else 
		$BreadCrumbURL .= @$ParentMainSectionName;	
	
	if(isset($MainSectionName) && $BreadCrumbURL != '') 
		$BreadCrumbURL .="/".$MainSectionName;
	else 
		$BreadCrumbURL .= @$MainSectionName;	
	
	return @$BreadCrumbURL;
	
}

	
	function exception_handler($exception) {
	  echo '<div class="alert alert-danger">';
	  echo '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . '\' with message ';
	  echo $exception->getMessage() . '<br>';
	  echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
	  echo 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>';
	  echo '</div>';
	  exit;
	}
	function RemoveSpecialCharacters($data) {
		 $replace = array("`","~","!","@","#","$",'%',"^","&","*","(",")","+","=","[","]","{","}","|","/","\\",":",";","'",'"',"?","<",">",",",".","’","‘","”","“","—");
 
		return str_replace($replace,"",$data);	
	}
	
	function mb_ucwords($str)
	{
		return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
	}
	function getSmallLink_bitly($longurl){  

	$url = "http://api.bit.ly/shorten?version=2.0.1&longUrl=$longurl&login=tnieonline&apiKey=R_084c835af7c253a8955ed5f4430c1a81&format=json&history=1";  

	$s = curl_init();  
	curl_setopt($s,CURLOPT_URL, $url);  
	curl_setopt($s,CURLOPT_HEADER,false);  
	curl_setopt($s,CURLOPT_RETURNTRANSFER,1);  
	$result = curl_exec($s);  
	curl_close( $s );  

	$obj = json_decode($result, true);  
	return $obj["results"]["$longurl"]["shortUrl"];  
}
function getSmallLink($longurl){  
    $apiURL = 'https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyCeeQHget_bZHy3P_qI2wnQWhGItnbudgU'; //AIzaSyBoXB8LqB7x_nFA4VizAzFpPqNp0FRk9pw //AIzaSyCeeQHget_bZHy3P_qI2wnQWhGItnbudgU

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$apiURL);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$longurl)));
	curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	// Execute the post
	$result = curl_exec($ch);
	// Close the connection
	curl_close($ch);
	// Return the result
	$response = json_decode($result,true);
	$ret_response['id'] = isset($response['id']) ? $response['id'] : "";
	$ret_response['msg'] = isset($response['error']['code']) ? $response['error']['code']."-".$response['error']['message'] : false;
	//return isset($response['id']) ? $response['id'] : false;
	return $ret_response;
}
function get_image_source($image_path, $condition_check){ //$condition_check=> 1 - file_exists, 2 - height,wieght
	//on file uploading to live change this file_exists => getimagesize and destination_base_path => image_url
	if($condition_check==1){
		return @getimagesize(image_url_no .imagelibrary_image_path.$image_path); //getimagesize - image_url
	}else if($condition_check==2){
		return @getimagesize(image_url_no . imagelibrary_image_path.$image_path); //image_url
	}
}
function astrology_list($rasi_id,$monthName) {
	$CI = & get_instance();
	
	$general_qry =  $CI->db->query('SELECT prediction_details FROM rasi_general_predictions WHERE section_id='.$rasi_id);
	$result['general_details'] = '';
	if($general_qry->num_rows() > 0){
		$general_result = $general_qry->row_array();
		$result['general_details'] = ($general_result['prediction_details'] != '') ? $general_result['prediction_details'] : '';
	}
	
	$daily_qry =  $CI->db->query('SELECT prediction_details FROM rasi_daily_predictions WHERE prediction_id IN (CASE WHEN (SELECT `prediction_id` FROM rasi_daily_predictions WHERE prediction_date = CURDATE() and section_id='.$rasi_id.') THEN (SELECT `prediction_id` FROM rasi_daily_predictions WHERE prediction_date = CURDATE() and section_id='.$rasi_id.') ELSE (SELECT `prediction_id` FROM rasi_daily_predictions WHERE prediction_date < CURDATE() and section_id='.$rasi_id.' ORDER BY prediction_date DESC LIMIT 1) END)');
	$result['daily_details'] = '';
	if($daily_qry->num_rows() > 0){
		$daily_result = $daily_qry->row_array();
		$result['daily_details'] = ($daily_result['prediction_details'] != '') ? $daily_result['prediction_details'] : '';
	}
	
	$date = date('Y-m-d');
	
	$weekly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_weekly_predictions WHERE section_id='".$rasi_id."' AND prediction_date <= '".$date."' AND prediction_end_date >= '".$date."' ");
	$result['weekly_details'] = '';
	if($weekly_qry->num_rows() > 0){
		$weekly_result = $weekly_qry->row_array();
		$result['weekly_details'] = ($weekly_result['prediction_details'] != '') ? $weekly_result['prediction_details'] : '';
	}
	else{
		$weekly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_weekly_predictions WHERE prediction_date IN (SELECT max(prediction_date) FROM rasi_weekly_predictions WHERE section_id='".$rasi_id."') AND prediction_date <= '".$date."' ");	
		$result['weekly_details'] = '';
		if($weekly_qry->num_rows() > 0){
			$weekly_result = $weekly_qry->row_array();
			$result['weekly_details'] = ($weekly_result['prediction_details'] != '') ? $weekly_result['prediction_details'] : '';
		}
		
	}
	
	//$monthly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_monthly_predictions WHERE section_id='".$rasi_id."' AND prediction_date <= '".$date."' AND prediction_end_date >= '".$date."' ");
	$monthly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_monthly_predictions WHERE section_id='".$rasi_id."' AND prediction_date <= '".$date."' AND prediction_month = '".$monthName."' ");
	//$monthly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_monthly_predictions WHERE section_id='".$rasi_id."' AND prediction_date = '".$date."' ");
	
	$result['monthly_details'] = '';
	if($monthly_qry->num_rows() > 0){
		$monthly_result = $monthly_qry->row_array();
		$result['monthly_details'] = ($monthly_result['prediction_details'] != '') ? $monthly_result['prediction_details'] : '';
	}
	else{
		//echo 'aa';exit;
		//$monthly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_monthly_predictions WHERE prediction_date IN (SELECT max(prediction_date) FROM rasi_monthly_predictions WHERE section_id='".$rasi_id."') AND prediction_date <= '".$date."' ");
		$monthly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_monthly_predictions WHERE prediction_date IN (SELECT max(prediction_date) FROM rasi_monthly_predictions WHERE section_id='".$rasi_id."') AND prediction_date <= '".$date."' AND section_id='".$rasi_id."' ");	
		$result['monthly_details'] = '';
		if($monthly_qry->num_rows() > 0){
			$monthly_result = $monthly_qry->row_array();
			$result['monthly_details'] = ($monthly_result['prediction_details'] != '') ? $monthly_result['prediction_details'] : '';
		}
	}
	
	$result['tamil_monthly_details'] = '';
	//$tamil_monthly =  $CI->db->query("SELECT prediction_details FROM rasi_monthly_predictions WHERE section_id='".$rasi_id."' AND prediction_date <= '".$date."' AND prediction_end_date >= '".$date."' ");
	$tamil_monthly =  $CI->db->query("SELECT prediction_details FROM rasi_monthly_predictions WHERE section_id='".$rasi_id."'  AND  '".$date."'  BETWEEN `prediction_date` AND `prediction_end_date` ");
	if($tamil_monthly->num_rows() > 0){
	$tamil_monthly_res = $tamil_monthly->row_array();
	//echo $CI->db->last_query();exit;
	$result['tamil_monthly_details'] = ($tamil_monthly_res['prediction_details'] != '') ? $tamil_monthly_res['prediction_details'] : '';
	}
	
	$yearly_qry =  $CI->db->query("SELECT prediction_details FROM rasi_yearly_predictions WHERE section_id='".$rasi_id."' AND YEAR(prediction_date) = '".date('Y')."'  ");
	$result['yearly_details'] = '';
	if($yearly_qry->num_rows() > 0){
		$yearly_result = $yearly_qry->row_array();
		$result['yearly_details'] = ($yearly_result['prediction_details'] != '') ? $yearly_result['prediction_details'] : '';
	}
	
	return $result;
}
/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */
?>