<?php
/**
 * Article Image Model Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

class Article_image_model extends CI_Model

{
	public function get_image_library_scroll($page)
	{
		$offset = ($page*16) - 16;
		
		$Order = "ORDER BY Modifiedon desc LIMIT ".$offset.", 16";
		$image = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		return $image->result();
	}
	public function get_image_library()
	{
		
		$Order = "ORDER BY Modifiedon desc LIMIT 0, 16";
		$image = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		return $image->result();
	}
	public function Insert_temp_from_image_library()
	{
		extract($_POST);
		
				$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
				$SourceURL  		= imagelibrary_image_path;
				$DestinationURL		= article_temp_image_path;
				
				$ImageDetails 	= GetImageDetailsByContentId($content_id);
				
				$path = $ImageDetails['ImagePhysicalPath'];
				
				$NewPath = GenerateNewImageName($path, $NewImageName);
				
				ImageLibraryCopyToTemp($path,$NewPath, $SourceURL, $DestinationURL);
				$path = $NewPath;
			
				$createdon 		= $modifiedon = date('Y-m-d H:i:s');
				
				if (isset($caption))
				{
				
					$Image1Type 	= $ImageDetails['Image1Type'];
					$Image2Type 	= $ImageDetails['Image2Type'];
					$Image3Type 	= $ImageDetails['Image3Type'];
					$Image4Type 	= $ImageDetails['Image4Type'];
					
					$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
					
					$query 				= $this->db->query("CALL insert_temp_images('" . USERID . "'," . $content_id . ",1,'" . addslashes($caption) . "','" . addslashes($alt) . "','".addslashes($PhysicalName)."','".addslashes($path)."',".$Image1Type.",".$Image2Type.",".$Image3Type.",".$Image4Type.",1,'" . $createdon . "','" . $modifiedon . "',@insert_id)");
					
					$result 			= $this->db->query("SELECT @insert_id")->result_array();
					$image_temp_id 		= $result[0]['@insert_id'];
					$data['image_id'] 	= $image_temp_id;
					$data['source'] 	= image_url.article_temp_image_path.$path;
					
					$Physical_extension_array = explode(".",$path);
					
					$data['caption'] 	= $caption;
					$data['alt'] 		= $alt;
					
					$data['physical_name'] 		= $PhysicalName;
					$data['physical_extension'] = $Physical_extension_array[1];
					
					$data['imagecontent_id'] 		= $content_id;
					$data['image1_type'] = $Image1Type; 
					$data['image2_type'] = $Image2Type;
					$data['image3_type'] = $Image3Type;
					$data['image4_type'] = $Image4Type;
					
				}
		
		echo json_encode($data);
	
	}
	public function search_image_library_scroll($page)

	{
		
		$offset = ($page*16) - 16;
		$Caption =	$this->session->userdata('image_caption');
		
		$Order = "ORDER BY Modifiedon desc LIMIT ".$offset.", 16";
		
		if(	$Caption != '')
		$search = $this->db->query('CALL search_image_related_data("' . $Caption . '","'.$Order.'")');
		else
		$search = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		
		return $search->result();
	}
	
	public function search_image_library()

	{
			extract(array_map('trim',$_POST));
	
			$this->session->set_userdata('image_caption',$Caption);
		
		$Order = "ORDER BY Modifiedon desc LIMIT 0, 16";
		
		if( $Caption != '')
		$search = $this->db->query('CALL search_image_related_data("' . $Caption . '","'.$Order.'")');
		else
		$search = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		
		echo json_encode($search->result());
	}
}
/* End of file article_image_model.php */
/* Location: ./application/models/admin/article_image_model.php */
?>
