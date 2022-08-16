<?php
Class scrolling_data extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function fetch_scrolling_data(){
		$content=$this->db->query("SELECT sid,content,created_on FROM scrolling_newsmaster WHERE status=1 ORDER BY created_on DESC")->result();
		return $content;
	}
	
	public function save_scrolling_data($data){
		return $this->db->insert('scrolling_newsmaster',array('content'=>$data,'status'=>1));
	}
	
	public function save_edit_scrolling_data($data,$sid){
		$this->db->where('sid',$sid);
		return $this->db->update('scrolling_newsmaster',array('content'=>$data));
	}
	
	public function delete_data($sid){
		$this->db->where('sid',$sid);
		return $this->db->update('scrolling_newsmaster',array('status'=>0));
	}

}
?>