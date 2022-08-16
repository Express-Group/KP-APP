<?php
Class scroll_data extends CI_Controller{

	public function  __construct(){
		parent::__construct();
		
	}
	
	public function render_news(){
		$section_id = $this->input->post('section_id');
		$this->load->model('admin/scrolling_data');
		$rendered=$this->scrolling_data->fetch_scrolling_data('' ,$section_id);
		$Template='<ul style="float:left;">';
		foreach($rendered as $data){
			$date=explode(' ',$data->created_on);
			$date=explode(':',$date[1]);
			$date=$date[0].':'.$date[1];
			$Template .='<li><span class="date-color">'.$date.' <br>
				<a target="_blank" href="" class="fb_share"><i class="fa fa-facebook custom_social" ></i></a>
			<br><a target="_blank" href="" class="twitter_share"><i class="fa fa-twitter custom_social"></i></a></span> <span class="content-color">'.$data->content.'</span></li>';
		}
		$Template .='</ul>';
		echo $Template;
	
	}

}
?>