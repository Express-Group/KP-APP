<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Move To Archive Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

class Move_to_archive extends CI_Controller 
{	

	public function __construct() 
	{	
		parent::__construct();
		$this->load->model('admin/move_to_archive_model');
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->migration_db = $CI->load->database('migration_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
		
	}
	
	public function index() {
		
		$data['title'] 				= 'Move To Archive';
		$data['template'] 			= 'move_to_archive';
		$data['get_content_type'] 	= $this->common_model->get_content_type();
		
		$this->load->view('admin_template', $data);
		
	}
	
	public function flat_move_to_archive() {
		
		$Status = array();
		
		extract($_POST);
		
		if($start_date != '' && $end_date != '') {
			$Status  = $this->move_to_archive_model->content_move_to_archive($content_type, $start_date, $end_date);	
	
			if(count($Status) != 0) {
	
					$LogPath = source_base_path . "/archive_log/".date('Y-m-d').".txt";
				
					if(!file_exists($LogPath)) 
					$fp = fopen($LogPath,"wb");
				
						if(file_exists($LogPath))  {
								
							foreach($Status as $key => $Value) {
							
								$myfile = fopen($LogPath , "a") or die("Unable to open file!");
								$txt = 	"Content Type 	: ".$this->input->post('content_type')."\n";
								$txt .=  "Date			: ".date('Y-m-d H:i:s')."\n";
								$txt .= "Content Id 	: ".$key."\n";
								$txt .= "Status 		: ".$Value['status']."\n";
								$txt .= "Message 		: ".$Value["Message"]."\n";
								$txt .= "------------------------------------------\n";
								
								fwrite($myfile, $txt);
								
							}
							fclose($myfile);
						}
			}
			
			if(count($Status) == 1 && isset($Status[0])  &  isset($Status[0]['status']) == "Failure" ) {
				$this->session->set_flashdata('error',"Archive is facing issues...please try again");
				redirect(folder_name."/move_to_archive");
			} else {
				$this->session->set_flashdata('success', "Contents have been archived");
				redirect(folder_name."/move_to_archive");
			}
		
		} else {
			
			$this->session->set_flashdata('error', "Please choose the date");
			redirect(folder_name."/move_to_archive");
		}
		
	
		
	}
	
	public function move_to_archive_datatable() {
		
		extract($_POST);
		
		$data['data'] = array();
		
		$StatusArray = array();
		
		$start_date 	= 	date('Y-m-d', strtotime($check_in));
		$end_date 		= 	date('Y-m-d', strtotime($check_out));	
		
		$Query = $this->db->query("SET @content_ids='';CALL get_content_publish_date(".$content_type.",'".$start_date."','".$end_date."',@content_ids);");
	
		$ContentCollections = $this->db->query("SELECT @content_ids")->row_array();	
		
		
		if(isset($ContentCollections['@content_ids']) && trim($ContentCollections['@content_ids']) != '') {
			
			$ArrayContent = array_filter(explode(",",$ContentCollections['@content_ids']));
			$NotExistContent = array();
			
			foreach($ArrayContent as $Contentid) {
				$InstanceCount = $this->live_db->query("CALL check_widgetinstancecontentlive(".$Contentid.",".$content_type.",@count_content)");
				
				$ContentCount = $this->live_db->query("SELECT @count_content")->row_array();	
				
				$InstanceCount =  $ContentCount['@count_content'];
				
				if($InstanceCount == 0) 
					$NotExistContent[] = $Contentid;
			}
			
					/*
					echo "<pre>";
					print_r($NotExistContent);
					exit;
					*/
			
			
			if(!empty($NotExistContent)) {
		
				$Field = $order[0]['column'];
				$order = $order[0]['dir'];
		
				switch ($Field) {
					case 0:
						$order_field = 'title';
						break;
					case 1:
						$order_field = 'section_id';
						break;
					case 2:
					   $order_field = 'publish_start_date';
						break;
				}

		
				$ContentCollections = implode(",",$NotExistContent);
				
				$OrderCondition = " ORDER BY ".$order_field." ".$order." LIMIT ".$start .",".$length;
				
				$FlatArticleMaster = $this->move_to_archive_model->get_content_with_check($ContentCollections,$content_type,$OrderCondition);
				
				$AllFlatArticleMaster = $this->move_to_archive_model->get_content_with_check($ContentCollections,$content_type,"");
				
				$TotalRows = count($AllFlatArticleMaster);
				
				$data['draw'] = $draw;
				$data["recordsTotal"] = $TotalRows;
				$data["recordsFiltered"] = $TotalRows;
				$data['data'] = array();
				$Count = 0;
				
					foreach($FlatArticleMaster as $article) {
						
						 switch($content_type) {
						 case 1:
						 $edit_url = "edit_article/".urlencode(base64_encode($article['content_id']));
						 break;
						 break;
						 case 3; 
						 $edit_url = "edit_gallery/".urlencode(base64_encode($article['content_id']));
						 break;
						 case 4; 
						 $edit_url = "audio_video_manager/edit_data/4/".urlencode(base64_encode($article['content_id']));
						 break;
						 case 5; 
						 $edit_url = "audio_video_manager/edit_data/5/".urlencode(base64_encode($article['content_id']));			
						 break;
						 case 6; 
						 $edit_url = "edit_resources/".urlencode(base64_encode($article['content_id']));			
						 break;
						 default: 
						 $edit_url = "";
						}
						
						$subdata = array();
						
						$subdata[] = '<div align="center"><p title="' . stripslashes(strip_tags($article['title'])) . '" ><a href="'.base_url().folder_name."/".$edit_url.'" >' . stripslashes(shortDescription(strip_tags($article['title']))) . '</a></p></div>';
						$URLSectionStructure = (isset($article['section_id']))? GenerateBreadCrumbBySectionId($article['section_id']) : "-";
						$subdata[] = $URLSectionStructure;
						$subdata[] = date('d-m-Y H:i:s', strtotime($article['publish_start_date']));
						
						
						$data['data'][$Count] = $subdata;
						$Count++;
					}
				
					echo json_encode($data);
					exit;
				
			} 
		}
		
				if(count($data['data'])  == 0) {	
		
					$data['draw'] = $draw;
					$data["recordsTotal"] =0;
					$data["recordsFiltered"] = 0;
					$data['data'] = array();
					
					echo json_encode($data);
					exit;
					
				}
	}
	
}
/* End of file migration.php */
/* Location: ./application/controllers/niecpan/migration.php */
