<?php
/**
 * Article Image Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Article_image extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/article_image_model');
		$this->load->model('admin/image_model');
	}
	public function image_upload()

	{
		$image_upload_object = new Article_imagesub_class;
		$image_upload_object->image_upload();
	}
	public function get_image_library_scroll() {
		
		$Section = $this->session->set_userdata('image_section','');
		$Caption =	$this->session->set_userdata('image_caption','');
		
		$data['pages']				= $this->uri->segment('4');
		$data['image_library'] 		= $this->article_image_model->get_image_library_scroll($data['pages']);
		$data['nextpages']			=  $this->uri->segment('4')+1;
		
		
		echo $this->load->view('admin/image_scroll', $data);
		
	}
	public function search_image_library()

	{
		$this->article_image_model->search_image_library();
	}
	public function search_image_library_scroll()

	{
		$create_article_object = new Article_imagesub_class;
		$create_article_object->search_image_library_scroll();
	}
	
	public function Insert_temp_from_image_library()

	{
		$this->article_image_model->Insert_temp_from_image_library();
	}
	public function delete_temp_image() 
	
	{
			$this->image_model->delete_temp_image(1);
	}
	
}
class Article_imagesub_class extends Article_image

{
	public function image_upload()

	{
			extract($_POST);
			$result = array();
	
			if($popuptype == 'bodytext') {
				
				$Year = date('Y');
				$Month = date('n');
				$Day =  date('j');
						
				create_image_folder_ckeditor( $Year, $Month, $Day);
				$FolderMapping = $Year."/".$Month."/".$Day."/";

				$oldget =  getcwd();
				chdir(source_base_path.ckeditor_image_path.$FolderMapping);
				
				$config = array(
					'upload_path' 		=> getcwd(),
					'allowed_types' 	=> "gif|jpg|png|jpeg",
					'overwrite'			=> false
				);
				
				chdir($oldget);
				$this->upload->initialize($config);
				$result = array();
				if (!$this->upload->do_upload('imagelibrary')) {
					$error = array(
						'error' => $this->upload->display_errors()
					);
					$result['message'] = $error['error'];
					$result['status'] 	= 0;
					
				} else {
					$data = array(
						'upload_data'		=> $this->upload->data()
					);
					
			ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], image_resolution);
					
				$result['image']  = image_url.ckeditor_image_path.$FolderMapping.$data['upload_data']['file_name'];
					
				}
				
				
			} else {
					
				$oldget =  getcwd();
				chdir(source_base_path.article_temp_image_path);
					
				$config = array(
					'upload_path' 		=> getcwd(),
					'allowed_types' 	=> "gif|jpg|png|jpeg",
					'encrypt_name' 		=> TRUE
				);
				
				chdir($oldget);
				$this->upload->initialize($config);
				$result = array();
				if (!$this->upload->do_upload('imagelibrary'))
				{
					$error = array(
						'error' => $this->upload->display_errors()
					);
					
					$result['message'] = $error['error'];
					$result['status'] 	= 0;
				}
				else
				{
					$data = array(
						'upload_data'		=> $this->upload->data()
					);
					
				ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], image_resolution);
			
				$imagefile 			= $_FILES['imagelibrary']['tmp_name'];
				$caption_array 		= explode('.', $data['upload_data']['orig_name']);
				$caption 			= $caption_array[0];
				$content_type 		= 1;
				$Null_value			= "NULL";
				
				$result 	= $this->image_model->addimages(USERID,$Null_value , $content_type,$caption, $caption,  $caption, $data['upload_data']['file_name'],0,0,0,0,1);
						
					
				}
				
			
		}
		
				echo json_encode($result);
				exit;
		
	}
	public function search_image_library()

	{
		$this->article_image_model->search_image_library();
	}
	
		public function search_image_library_scroll()

	{
		$data['pages']				= $this->uri->segment('4');
		$data['image_library'] 		= $this->article_image_model->search_image_library_scroll($data['pages']);
		$data['nextpages']			=  $this->uri->segment('4')+1;
		
		
		echo $this->load->view('admin/image_scroll', $data);
	}
	
}

/* End of file article_image.php */
/* Location: ./application/controllers/article_image.php */
