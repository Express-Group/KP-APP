<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class embed extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function chart($widgetInstanceId=''){
		$widgetInstanceId = base64_decode($widgetInstanceId);
		$filename = $widgetInstanceId.'.json';
		$filepath = FCPATH.'application/views/piechart/';
		if(file_exists($filepath.$filename)){
			$response = [];
			$response['error'] = false;
			$response['wid'] = $widgetInstanceId;
			$this->load->view('admin/embed_chart',$response);
		}else{
			$this->load->view('admin/embed_chart',['error'=>true , 'wid'=>$widgetInstanceId]);
		}
	}
	
	public function table($tableid){
		$tid = (int) base64_decode(trim($tableid));
		if($tid!='' && is_numeric($tid)){
			$response =[];
			$this->load->database();
			$data = $this->db->query("SELECT tid , table_name , total , table_properties FROM tablemaster WHERE tid='".$tid."' LIMIT 1");
			if($data->num_rows()==0){
				$response['error'] = true;
				$response['data'] = [];
			}else{
				$response['error'] = false;
				$response['data'] = $data->result_array();
			}
			$this->load->view('admin/embed_table',$response);
		}else{
			$this->load->view('admin/embed_table',['error'=>true , 'tid'=>$tid]);
		}
	}
	
	public function highlight($id){
		$hid = (int) base64_decode(trim($id));
		$response ='<!doctype HTML>';
		$response .='<html>';
		$response .='<head><link rel="stylesheet" href="'.image_url.'css/FrontEnd/css/font-awesome.min.css" type="text/css"><style>@font-face{font-family:Droid-regular; src:url("'.image_url.'css/FrontEnd/fonts/DroidSerifFonts/droid-serif.regular.ttf");}.hcenter{text-align:center;}.highlight-n{width: 96%;margin: 1%;float: left;/* box-shadow: 0px 0px 7px 1px #0000001f; */border: 1px solid #ddd;padding: 1%;border-radius: 5px;font-family:Droid-regular;font-size: 18px;    line-height: 30px;} .highlight-n p{margin: 0;}.highlight-n .h-time span:first-of-type{color: #1DA1F2;font-size: 15px;}.highlight-n .h-time span:last-of-type{float: right;width: 30%;text-align: right;}</style><meta charset="UTF-8"><title>Highlights</title>';
		$response .='</head><body style="margin:0;">';
		if($hid!='' && is_numeric($hid)){
			$this->load->database();
			$data = $this->db->query("SELECT content , DATE_FORMAT(created_on ,'%D %M %Y %r') as created  FROM scrolling_newsmaster WHERE sid='".$hid."' AND status=1 LIMIT 1");
			if($data->num_rows() > 0){
				$result = $data->row_array();
				$response .='<div class="highlight-n">';
				$response .='<div class="h-time"><span><i class="fa fa-clock-o" aria-hidden="true"></i> '.$result['created'].'</span><span><a onclick="hshare(1);" style="    margin-right: 7px;color: #1DA1F2;cursor:pointer;"><i class="fa fa-facebook" aria-hidden="true"></i></a><a onclick="hshare(2);" style="color: #1DA1F2;cursor:pointer;"><i class="fa fa-twitter" aria-hidden="true"></i></a></span></div>';
				$response .='<div class="h-content">'.$result['content'].'</div>';
				$response .='</div>';
			}
		}else{
			$response .='<h5 class="hcenter">No records</h5>';
		}
		$response .='<script>function hshare(type){
			var durl = document.referrer;
			if(type==1){
				window.open("https://www.facebook.com/sharer/sharer.php?u="+durl,"", "width=670,height=340");
			}else{
				window.open("https://twitter.com/intent/tweet?text="+encodeURIComponent(durl) +" via @KannadaPrabha","", "width=550,height=420");
			}
		}</script></body></html>';
		echo $response;
	}

	public function highlights($sectionId){
		$sid = (int) base64_decode(trim($sectionId));
		$response['result'] = false;
		if($sid!='' && is_numeric($sid)){
			$this->load->model('admin/scrolling_data');
			$rendered=$this->scrolling_data->fetch_scrolling_data('',$sid);
			$Template='<ul style="float:left;">';
			foreach($rendered as $data){
				$date=explode(' ',$data->created_on);
				$date=explode(':',$date[1]);
				$date=$date[0].':'.$date[1];
				$Template .='<li><span class="date-color">'.$date.' :<br>
					<a target="_blank" href="" class="fb_share"><i class="fa fa-facebook custom_social" ></i></a>
					<br><a target="_blank" href="" class="twitter_share"><i class="fa fa-twitter custom_social"></i></a>
					</span> <span class="content-color">'.$data->content.'</span></li>';
			}
			$Template .='</ul>';
			$response['result'] = true;
			$response['data'] = $Template;
			
		}
		$this->load->view('admin/highlights',$response);
	} 
}
?> 