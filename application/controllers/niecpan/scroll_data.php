<?php
Class scroll_data extends CI_Controller{

	public function  __construct(){
		parent::__construct();
		
	}
	
	public function render_news(){
		$this->load->model('admin/scrolling_data');
		$rendered=$this->scrolling_data->fetch_scrolling_data();
		$Template='<ul>';
		foreach($rendered as $data){
			$date=explode(' ',$data->created_on);
			$date=explode(':',$date[1]);
			$date=$date[0].':'.$date[1];
			$Template .='<li><span class="date-color">'.$date.' :</span> <span class="content-color">'.$data->content.'</span></li>';
		}
		$Template .='</ul>';
		echo $Template;
	
	}


}
?>