<?php
/**
 * Image_model Class
 *
 * @package		ENPL
 * @subpackage	NewIndianExpress
 * @category	Image
 * @author		IE Team
 * @link		http://cms.newindianexpress.com/niecpan
 */
 defined('BASEPATH') OR exit('No direct script access allowed');
class Image_model extends CI_Model

{
	 /**
	 * Image manager datatable function
	 *
	 * @access	public
	 * @param	Post values from image manager page
	 * @return	Json string
	 */
	public function get_image_datatables() 
		{
				extract($_POST);
		
		$Search_text = trim($Search_text);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

			$content_type = "1"; 
			$menu_name		= "Image Library";
		 
		 $Menu_id = get_menu_details_by_menu_name($menu_name);

		
		switch ($Field) {
    case 1:
        $order_field = 'im.ImageCaption';
        break;
	case 2:
       $order_field = 'im.ImageAlt';
        break;
	case 3:
		$order_field = 'im.ImagePhysicalPath';	
		break;
	case 4:
       $order_field = 'im.Createdby';
		break;
	case 5:
       $order_field = 'im.Modifiedon';
		break;
	case 6:
       $order_field = 'im.status';
		break;	
    default:
        $order_field = 'im.content_id';
		}

		$Total_rows = 250;

		$Search_value = $Search_text;
		
				
		if($Search_by == 'ContentId') {
		$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		$Search_value = $Search_result;
		} else {
		$Search_value = $Search_text;
		}
		
		if($check_in != '')  {
		$check_in_date 	= strtotime($check_in);
		$check_in = date('Y-m-d',$check_in_date);
		}
		
		if($check_out != '')  {
		$check_out_date = strtotime($check_out);
		$check_out = date('Y-m-d',$check_out_date); 
		}
				
		$Search_value = htmlentities($Search_value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		
		$Search_value =  str_replace("&#039","&#39",$Search_value);

		$image_manager =  $this->db->query('CALL image_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Status.'")')->result_array();	
		

		$recordsFiltered = $this->db->query('CALL image_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT 0, 250 ","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Status.'")')->num_rows();
		
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;

		foreach($image_manager as $image) {
			
			$image_image = '';

			 $edit_url = "edit_image/".urlencode(base64_encode($image['content_id']));

			$subdata = array();
			$subdata[] = $image['content_id'];
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($image['ImageCaption']).'">'.shortDescription(strip_tags($image['ImageCaption'])).'</p>';
			
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($image['ImageAlt']).'">'.shortDescription(strip_tags($image['ImageAlt'])).'</p>';
			
			$Image150X150 	= str_replace("original","w150X150", $image['ImagePhysicalPath']);
			$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
			
			
			$subdata[] = $image['Username'];
			$change_date_format = date('d-m-Y H:i:s', strtotime($image['Modifiedon']));
			$subdata[] = $change_date_format;
			
			switch($image["status"])
			{
			case(1):
				$status_icon = '<span data-toggle="tooltip" title="Active" href="javascript:void()" id="img_change'.$image['content_id'].'" data-original-title="Active"><i id="status_img'.$image['content_id'].'"  class="fa fa-check"></i></span>';
				break;
			case(0):	
				$status_icon = '<span data-toggle="tooltip" title="Inactive" href="javascript:void()" id="img_change'.$image['content_id'].'"  data-original-title="Active"><i id="status_img'.$image['content_id'].'" class="fa fa-times"></i></span>';
				break;
			default;
				$status_icon = '';
			}
			
			$subdata[] = $status_icon;
			

			$set_status ='<div class="buttonHolder">';
			
				if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
					$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a>'. '';
				}
				else
					$set_status .= '';
				
			
				if($image["status"]==1)
                {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { 
					$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Inactive" content_id = '.$image['content_id'].' status ="'.$image["status"].'" name="'.strip_tags($image['ImageCaption']).'" id="status_change"><i id="status'.$image['content_id'].'" class="fa fa-pause"></i></a>'.'';
					}
				}
                elseif($image["status"]==0)
                { 
				 	if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<a data-toggle="tooltip" href="#" title="Active" class="button heart" data-original-title="" content_id = '.$image['content_id'].' status ="'.$image["status"].'" name="'.strip_tags($image['ImageCaption']).'" id="status_change"><i id="status'.$image['content_id'].'" class="fa fa-caret-right"></i></a>'.'';
					}
				}
				
			
				if($image["status"]==1 ) {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="unpublish_checkbox[]" value="'.$image['content_id'].'" id="unpublish_checkbox_id" status ="'.$image["status"].'"    ></span>';
					}
				}
				
				if($image["status"]==0 ) {
					if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"    title="Select"   name="publish_checkbox[]" value="'.$image['content_id'].'"   status ="'.$image["status"].'"    id="publish_checkbox_id" ></span>';
					}
				}
				
			
			if($set_status != '') {			  
			$set_status .= '</div>';
			$subdata[] = $set_status ;
			}
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
		echo json_encode($data);
		exit;
		}
	
	 /**
	 * Delete temp image based on Image Type function
	 *
	 * @access	public
	 * @param	Image type (1,2,3)
	 * @return	TRUE
	 */
	public function DeleteTempAllTypeImages()

		{
			for($TempType = 1;$TempType <= 6;$TempType++) {
				
			$TempImages = $this->viewtempimage(USERID, $TempType);
	
			if (isset($TempImages))
			{
				foreach($TempImages as $Images)
				{
					
					$ImageDetails 	= $this->get_image($Images->imageid);
					if (isset($ImageDetails))
					{
						if (isset($ImageDetails['image_name']) && $ImageDetails['image_name'] != '')
							
						{
							if($TempType == 2)
							$SourceURL 	= imagelibrary_temp_image_path;
							else if($TempType == 1)
							$SourceURL 	= article_temp_image_path;
							else if($TempType == 3)
							$SourceURL 	= gallery_temp_image_path;
							else if($TempType == 4)
							$SourceURL 	= video_temp_image_path;
							else if($TempType == 5)
							$SourceURL 	= audio_temp_image_path;
							else if($TempType == 6)
							$SourceURL 	= resource_temp_image_path;
						
							$ImageId 	= $Images->imageid;
							$TempName 	= $ImageDetails['image_name'];
							
							DeleteTempImage($TempName,$ImageId,$SourceURL);
						}
					}
				}
			}
			
			}
			return TRUE;
			
		}
	
	 /**
	 * Delete temp image based on Image Type function
	 *
	 * @access	public
	 * @param	Image type (1,2,3)
	 * @return	TRUE
	 */
	public function DeleteTempAllImages($TempType)

		{
			
			$TempImages = $this->viewtempimage(USERID, $TempType);
	
			if (isset($TempImages))
			{
				foreach($TempImages as $Images)
				{
					
					$ImageDetails 	= $this->get_image($Images->imageid);
					if (isset($ImageDetails))
					{
						if (isset($ImageDetails['image_name']) && $ImageDetails['image_name'] != '')
							
						{
							if($TempType == 2)
							$SourceURL 	= imagelibrary_temp_image_path;
							else if($TempType == 1)
							$SourceURL 	= article_temp_image_path;
							else if($TempType == 3)
							$SourceURL 	= gallery_temp_image_path;
							else if($TempType == 4)
							$SourceURL 	= video_temp_image_path;
							else if($TempType == 5)
							$SourceURL 	= audio_temp_image_path;
							else if($TempType == 6)
							$SourceURL 	= resource_temp_image_path;
						
							$ImageId 	= $Images->imageid;
							$TempName 	= $ImageDetails['image_name'];
							
							DeleteTempImage($TempName,$ImageId,$SourceURL);
						}
					}
				}
			}
			
			return TRUE;
			
		}
	/**
	 * View the temp image
	 *
	 * @access	public
	 * @param	User ID and Image Type
	 * @return	temp table result
	 */
	public function viewtempimage($userid, $type)

		{
			$query = $this->db->query('CALL view_temp_user_image("' . $userid . '",' . $type . ')');
			return $query->result();
		}
	/**
	 * Get the temp image 
	 *
	 * @access	public
	 * @param	Image ID
	 * @return	temp table row array
	 */
	public function get_image($imageid)

		{
			$query = $this->db->query("CALL tempimagedetails('" . $imageid . "','')");
			return $query->row_array();
		}
	/**
	 * Add the new image in temp table
	 *
	 * @access	public
	 * @param	temp table fields
	 * @return	JSON string
	 */
	public function addimages($userid, $imagecontent_id,$contenttype, $caption, $alt_tag, $physical_name, $file_name, $image1_type, $image2_type, $image3_type, $image4_type, $display_order)

		{
			$createdon 			= date('Y-m-d H:i:s');
			$modifiedon 		= date('Y-m-d H:i:s');
			
			
			$query 				= $this->db->query("CALL insert_temp_images('" . $userid . "'," . $imagecontent_id . ",'".$contenttype."','" . addslashes($caption) . "','" . addslashes($alt_tag) . "','".addslashes($physical_name)."','".addslashes($file_name)."',".$image1_type.",".$image2_type.",".$image3_type.",".$image4_type.",".$display_order.",'" . $createdon . "','" . $modifiedon . "',@insert_id)");
			
			$query 				= $this->db->query("SELECT @insert_id");
			$returnid 			= $query->result_array();
			
			if(isset($returnid[0]['@insert_id']) && $returnid[0]['@insert_id'] != '' ) {
			
			$data['image_id'] 				= $returnid[0]['@insert_id'];
			$data['imagecontent_id'] 		= $imagecontent_id;
			$data['caption'] 				= $caption;
			$data['alt_tag'] 				= $alt_tag;
			$data['physical_name'] 			= $physical_name;
			

			switch($contenttype) {
				case 1:
				$data['image'] 			= image_url.article_temp_image_path.$file_name;
				break;
				case 2:
				$data['image'] 			= image_url.imagelibrary_temp_image_path.$file_name;
				break;
				case 3:
				$data['image'] 			= image_url.gallery_temp_image_path.$file_name;
				break;
				case 4:
				$data['image'] 			= image_url.video_temp_image_path.$file_name;
				break;
				case 5:
				$data['image'] 			= image_url.audio_temp_image_path.$file_name;
				break;
				case 6:
				$data['image'] 			= image_url.resource_temp_image_path.$file_name;
				break;
			}
			
			$PhysicalExtension_array = explode('.',$file_name);
			$data['physical_extension'] = $PhysicalExtension_array[1];
			
			$data['image1_type'] = $image1_type; $data['image2_type'] =  $image1_type; $data['image3_type'] =   $image1_type; $data['image4_type'] =   $image1_type;
			
			} else {
				echo '{"type":1,"message":"Invalid image, please try again","line":0}';
				exit;
			}
			return $data;
		}
	/**
	 * Delete images records in temp table
	 *
	 * @access	public
	 * @param   Image Type (1,2,3)
	 * @return	JSON string
	 */
	public function delete_temp_image($ImageType)
	
		{
			extract($_POST);

			$ImageDetails 	= $this->get_image($image_id);
			
			if(isset($ImageDetails['image_name'])) 
			{
				if($ImageType == 2)
					$SourceURL = imagelibrary_temp_image_path;
				else if($ImageType == 1)
					$SourceURL = article_temp_image_path;
				else if($ImageType == 3)
					$SourceURL = gallery_temp_image_path;	
				else if($ImageType == 6)
					$SourceURL = resource_temp_image_path;	
					
				DeleteTempImage($ImageDetails['image_name'],$image_id, $SourceURL);
				
				$data['message'] = 'success';
			} else {
				$data['message'] = 'failure';	
			}
			
			echo json_encode($data);
		}
	/**
	 * Get Temp images from temp table
	 *
	 * @access	public
	 * @param   image id
	 * @return	temp table result
	 */
	public function GetTempImages($ImageId)

		{
			$query = $this->db->query("CALL tempimagedetails('" . $ImageId . "','')");
			return $query->result();
		}
	/**
	 * Insert image in Image library
	 *
	 * @access	public
	 * @param   POST values from image library form
	 * @return	TRUE
	 */
	public function insert_image()
		{
			extract($_POST);
			
			$temp_images = $this->viewtempimage(USERID, 2);
			$this->common_model->common_resize_all_images($temp_images);
			
			$null_value 		= "NULL";
			
			if ($txtImageData != '{}' && $txtImageData != '')
			{
					$data 				= json_decode($txtImageData);
					$SourceURL  		= imagelibrary_temp_image_path;
					$DestinationURL 	= imagelibrary_image_path;
					
			
					foreach($data as $result)
					{
						
						$query 			= $this->db->query("CALL tempimagedetails('" . $result->image_id . "','')");
						$temp_image 	= $query->row_array();
						
						$Year = date('Y');
						$Month = date('n');
						$Day =  date('j');
						
						create_image_folder( $Year, $Month, $Day);
						$FolderMapping = $Year."/".$Month."/".$Day."/original/";
						
						if(isset($temp_image['image_name'])) {  
							
						$image_name = explode('.',$temp_image['image_name']);
						$NewImageName = trim($result->physical_name).'.'.$image_name[1];
						
						ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $SourceURL, $DestinationURL, $FolderMapping);

						///// Delete the Temp Images in Table /////
						
						$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
						
						/* New coding */
						
						$temp_image['alt_tag'] 		= trim($result->image_alt);
						$temp_image['caption'] 		= trim($result->image_caption);
						$temp_image['image_name']   = $FolderMapping.$NewImageName;
					
						
						$image_library_id = add_image_master($temp_image);
								
						} else {
							$this->db->trans_rollback();
							$this->db->query("CALL deletetempimage('" . $result->image_id . "')");
							$this->session->set_flashdata('error', "Problem while inserting. Please try again");
							redirect(folder_name.'/image_manager');
						}
					}
			}
			return TRUE;
		}
	/**
	 * Update images in image library table
	 *
	 * @access	public
	 * @param   POST values from image form
	 * @return	TRUE
	 */
		public function update_image($image_id)

	{

			$_POST = array_map('trim', $_POST);
			extract($_POST);
		
			$temp_images = $this->viewtempimage(USERID, 2);
			$this->common_model->common_resize_all_images($temp_images);
		
						if ($txtImageData != '{}' && $txtImageData != '')
						{
							$null_value 		= "NULL";
							$data 				= json_decode($txtImageData);
							
							$SourceURL  		= imagelibrary_temp_image_path;
							$DestinationURL 	= imagelibrary_image_path;
							
							$this->db->trans_begin();

							foreach($data as $result)
							{
								
								$query 		= $this->db->query("CALL tempimagedetails('" . $result->image_id . "','')");
								$temp_image = $query->row_array();
								
								if(isset($temp_image['image_name']))  {
								
									$image_name = explode('.',$temp_image['image_name']);
									$NewImageName = $physical_name.'.'.$image_name[1];
							
									IF((isset($temp_image['imageid']) && ($temp_image['imagecontent_id'] == "NULL" || $temp_image['imagecontent_id'] == "" || $temp_image['imagecontent_id'] == 0)) || ( $temp_image['crop_resize_status'] == 1 && $temp_image['save_status'] == 1 ) || (  trim($temp_image['caption']) != trim($image_caption) || trim($temp_image['alt_tag']) != trim($image_alt) || trim($temp_image['physical_name']) != trim($physical_name) ) )	{
										
										$query 		= $this->db->query("CALL tempimagedetails('" . $result->image_id . "','')");
										$temp_image = $query->row_array();
										
										$Year = date('Y');
										$Month = date('n');
										$Day =  date('j');
										
										create_image_folder( $Year, $Month, $Day);
										$FolderMapping = $Year."/".$Month."/".$Day."/original/";
										
										ImageDeleteAndPasteToLibrary($temp_image['image_name'],$NewImageName, $SourceURL, $DestinationURL, $FolderMapping);

										///// Delete the Temp Images in Table /////
										
										$query = $this->db->query("CALL deletetempimage('" . $result->image_id . "')");
										
										/* New coding */
										
										$temp_image['alt_tag'] 		= $image_alt;
										$temp_image['caption'] 		= $image_caption;
										$temp_image['image_name']   = $FolderMapping.$NewImageName;
										
										$image_library_id = add_image_master($temp_image);
										
									} else {
										
										$ImageDetails = GetImageDetailsByContentId($temp_image['imagecontent_id']);
								
										$PhysicalName = end(explode("/",$ImageDetails['ImagePhysicalPath']));
										
										$PhysicalPath = str_replace($PhysicalName,"",$ImageDetails['ImagePhysicalPath']);
										
										ImageDeleteAndPasteToLibrary($temp_image['image_name'],$PhysicalName, $SourceURL, $DestinationURL, $PhysicalPath);
										
										$this->db->query("CALL deletetempimage('" . $result->image_id . "')");
									}
								} else {
									
									$this->db->trans_rollback();
									$this->db->query("CALL deletetempimage('" . $result->image_id . "')");
									$this->session->set_flashdata('error', "Problem while updating. Please try again");
									redirect(folder_name.'/image_manager');
									
								}
						
							}
							
								if ($this->db->trans_status() === FALSE)
									$this->db->trans_rollback();
								else
									$this->db->trans_commit();
							
						}
						if($txtStatus == 1)
							$this->session->set_flashdata('success', "Image Activated Successfully");
						else 
							$this->session->set_flashdata('success', "Image Inactivated Successfully");
							
						redirect(folder_name.'/image_manager');
					
						return TRUE;
	}
	/**
	 * Get image details from image library table
	 *
	 * @access	public
	 * @param   content_id
	 * @return	TRUE or FALSE
	 */
	public function get_imagedetails_imageId($content_id)

	{
		$images = $this->db->query('CALL get_imagedetails_by_contentid(' . $content_id . ')');
		return $images;
	}
	
}

/* End of file image_model.php */
/* Location: ./application/models/admin/image_model.php */
?>