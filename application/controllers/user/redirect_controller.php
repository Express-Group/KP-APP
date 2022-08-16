<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class redirect_controller extends CI_Controller {
	public $live_db;
	
	public function __construct(){		
		parent::__construct();
		$this->load->helper('url');
		$this->live_db = $this->load->database('live_db' , TRUE);
	}
	
	public function index(){
		$totalSegment = $this->uri->total_segments();
		$ecenic_id  = $this->uri->segment($totalSegment);
		$ecenic_id = explode('.' ,$ecenic_id);
		$yearRange = range(2013  ,date('Y') ,1);
		$details = $this->live_db->query("SELECT url FROM article WHERE ecenic_id='".$ecenic_id[0]."'")->row_array();
		if(count($details) > 0){
			$newUrl = base_url($details['url']);
			redirect($newUrl,'location',301);
			exit;
		}else{
			$this->archive_db = $this->load->database('archive_db' , TRUE);
			for($i=0;$i<count($yearRange);$i++){
				if($this->archive_db->table_exists('article_'.$yearRange[$i])){
					$details = $this->archive_db->query("SELECT url FROM article_".$yearRange[$i]." WHERE ecenic_id='".$ecenic_id[0]."'")->row_array();
					if(count($details) > 0){
						$newUrl = base_url($details['url']);
						redirect($newUrl,'location',301);
						exit;
					}
				}
			}
		}
	}
	
	public function video(){
		$ecenic_id  = $this->uri->segment(4);
		$ecenic_id = explode('.' ,$ecenic_id);
		$details = $this->live_db->query("SELECT url FROM video WHERE ecenic_id='".$ecenic_id[0]."'")->row_array();
		if(count($details) > 0){
			$newUrl = base_url($details['url']);
			redirect($newUrl,'location',301);
		}
		
	}
	
	public function gallery(){
		$ecenic_id  = $this->uri->segment(3);
		$ecenic_id = explode('.' ,$ecenic_id);
		$details = $this->live_db->query("SELECT url FROM gallery WHERE ecenic_id='".$ecenic_id[0]."'")->row_array();
		if(count($details) > 0){
			$newUrl = base_url($details['url']);
			redirect($newUrl,'location',301);
		}
	}
	
	
}
?> 