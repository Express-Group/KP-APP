<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Commonwidget extends CI_Controller 
{
	
	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('cookie');	
		//$this->load->model('admin/comment_model');
		$this->load->model('admin/widget_model');
		$this->load->driver('cache', array('adapter' => 'file'));	
	} 
	public function geo_country(){
		echo @$_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'];
	}
	
	public function get_poll_results()
	{
		$class_object = new poll_result;
		$class_object->get_poll_results();
	}
	
	public function select_poll_results()
	{
		$class_object = new poll_result;
		$class_object->select_poll_results();
	}
	public function share_article_via_email()
	{
		$class_object = new email_section;
		$class_object->share_article_via_email();
	}
	function post_comment()
	{
		$set_object=new view;
		$set_object->post_comment();
	}
	public function Search_datatable() {
		$class_object = new view;
		$class_object->Search_datatable();
	}
	public function get_states_content(){
	    $class_object = new view;
		$class_object->get_states_content();
	}
	public function get_states_content_up(){
	    $class_object = new view;
		$class_object->get_states_content_up();
	}
	public function get_menu_content(){
	    $class_object = new view;
		$class_object->get_menu_content();
	}	
	public function get_editor_pick_content(){
	    $class_object = new view;
		$class_object->get_editor_pick_content();
	}
	public function get_add_widget(){
	    $class_object = new view;
		$class_object->get_add_widget();
	}
	public function update_hits()
	{
		$class_object = new view;
		$class_object->update_hits();
	}
	public function subscribe_newsletter()
	{
		$class_object = new view;
		$class_object->subscribe_newsletter();
	}
	public function get_breaking_news_content()
	{
		$class_object = new view;
		$class_object->get_breaking_news_content();
	}
	public function get_breadcrumb(){
	extract($_POST);
	$content['mode'] 	    = $mode;
	$content['page_param']  = $section;
	$single['content']      = $content;
	$breadcrumb    = $this->load->view('admin/widgets/breadcrumb.php', $single, true);
	echo $breadcrumb;exit;
	}
	
	public function piechart_data(){
		$data='';
		$widgetInstanceId = $this->input->post('wid');
		if($widgetInstanceId!='' && is_numeric($widgetInstanceId)==true){
			$filename = $widgetInstanceId.'.json';
			$filepath = FCPATH.'application/views/piechart/';
			if(file_exists($filepath.$filename)){
				$data='';
				$data = json_decode(file_get_contents($filepath.$filename) , TRUE);
				if($data['clonestatus']!=''){
					$filename = $data['clonestatus'].'.json';
					$data='';
					$data = json_decode(file_get_contents($filepath.$filename) , TRUE);
				}
				$response = [];
				$response['error'] = false;
				$response['name'] = $data['chartname'];
				$response['type'] = $data['charttype'];
				$response['clone'] = $data['clonestatus'];
				$response['data'] = json_encode(array_combine($data['partyname'] ,array_map(function($value) { return (int)$value; },$data['partyvalue'])));
				$response['color'] = $data['partycolor'];
				$response['total'] = $data['total'];
				echo json_encode($response);
			}else{
				echo json_encode(['error'=>true]);
			}
		}else{
			echo json_encode(['error'=>true]);
		}
	
	}
	
	public function get_rightside_stories(){
	extract($_POST);
	$content['mode'] 	    = $mode;
	$content['page_param']  = $section;
	$content['close_param'] = '';
	$content['page_type']   = $type;
	if($type==2){
	$content['content_id']  = $contentid;
	$content['section_id']  = $section;
	}
	$main_sction_id = 10000;
	$pagemaster_data = $this->widget_model->get_pagemaster_live_version($main_sction_id, $type, $mode); //get the live version data of sub section lead stories
    $live_version    = $pagemaster_data['Published_Version_Id'];
	$section_widgetID = $this->widget_model->get_widget_byname('Other Stories (Right Side)', $mode);
    $right_story_instance        = $this->widget_model->get_sub_sec_lead_stories_data($main_sction_id, $type, '', $section_widgetID['widgetId'], 0, $live_version, $mode);
    if(count($right_story_instance) > 0)
    {
	$content['widget_title'] = $right_story_instance['CustomTitle'];	
	$single['content']      = $content;
	$right_stories    = $this->load->view('admin/widgets/other_stories.php', $single, true);
	}else{
	$right_stories = '';
	}
	echo $right_stories;exit;
	}

	public function getsponsorarticles(){
		$template ='';
		$articledetails = $this->widget_model->get_sponsorarticles();
		foreach($articledetails as $sponsorarticles):
			 $content_title = stripslashes(strip_tags($sponsorarticles->title, '</p>'));
			 $template .='<p><i class="fa fa-circle" aria-hidden="true" style="color:#f00; font-size:6px;"></i><a href="'.base_url().$sponsorarticles->url.'">'.$content_title.'</a></p>';
		endforeach;
		echo $template; 
	}
	
	public function chart_data(){
		$filepath = FCPATH.'application/views/view_template/chart.txt';
		if(file_exists($filepath)){
			$response['data'] = json_decode(file_get_contents($filepath),true); 
			$response['hasfile'] = 1; 
		}else{
			$response['data'] = ''; 
			$response['hasfile'] = 0; 
		}
		echo json_encode($response);
	}
	
	public function post_file_intimation()
	{
		$file_name = $_POST['file_name'];
		$contents  = $_POST['file_contents'];
		$file_to_save = FCPATH.'application/views/view_template/'.$file_name;
		$handle = fopen($file_to_save , 'w+');
		if(flock($handle, LOCK_EX))
		{
			fwrite($handle, $contents);
			flock($handle, LOCK_UN);
		}
		return true;
	}
	//to save article page in article folder
	public function save_article_content()
	{
		$file_name = $_POST['file_name'];
		$contents  = $_POST['file_contents'];
		if($contents!=''){
			$file_to_save = FCPATH.'application/views/view_template/article/'.$file_name;
			$handle = fopen($file_to_save , 'w+');
			if(flock($handle, LOCK_EX))
			{
				fwrite($handle, $contents);
				flock($handle, LOCK_UN);
			}
			chmod($file_to_save,0777);
		}
		return true;
	}
	// end to save article page in article folder
	
	//to save adv in adv folder
	public function post_scriptfile_intimation()
	{
		$file_name = $_POST['file_name'];
		$contents  = $_POST['file_contents'];
		$type  = $_POST['type'];
		if($type==1){
			$file_to_save = FCPATH.'application/views/adv/'.$file_name;
			$handle = fopen($file_to_save , 'w+');
			if(flock($handle, LOCK_EX))
			{
				fwrite($handle, $contents);
				flock($handle, LOCK_UN);
			}
			chmod(FCPATH.'application/views/adv/'.$file_name,0777);
		}
		if($type==2){
			$file_to_save = FCPATH.'application/views/adv/'.$file_name;
			if(file_exists($file_to_save)){
				unlink($file_to_save);
			}
		}
		return true;
	}
	
	public function save_flashnews()
	{
		$file_name = $_POST['file_name'];
		$contents  = $_POST['file_contents'];
		$type  = $_POST['type'];
		$file_to_save = FCPATH.'application/views/flash/'.$file_name;
		$handle = fopen($file_to_save , 'w+');
		if(flock($handle, LOCK_EX))
		{
			fwrite($handle, $contents);
			flock($handle, LOCK_UN);
		}
		chmod($file_to_save);
		return true;
	}
	
	public function store_piechart()
	{
		$file_name = $_POST['file_name'];
		$contents  = $_POST['file_contents'];
		$type  = $_POST['type'];
		$file_to_save = FCPATH.'application/views/piechart/'.$file_name;
		$handle = fopen($file_to_save , 'w+');
		if(flock($handle, LOCK_EX))
		{
			fwrite($handle, $contents);
			flock($handle, LOCK_UN);
		}
		chmod($file_to_save ,0777);
		return true;
	}
	
	public function fetchadv()
	{
		$widgetins = $_POST['widgetinstance'];
		$file =FCPATH.'application/views/adv/'.$widgetins.'.adv';
		if(file_exists($file)){
			$response = json_decode(file_get_contents($file),true);
			echo $response['content'];
		}else{
			echo '';
		}
	}
	public function fetchelectionmodule()
	{
		$widgetins = $_POST['widgetinstance'];
		$file =FCPATH.'application/views/adv/'.$widgetins.'.adv';
		if(file_exists($file)){
			$template ='';
			$response = json_decode(file_get_contents($file),true);
			if($response['content']!='' && $response['content']!='0,0,0,0'){
				$content = explode(',',$response['content']);
				if( isset($content[0])){ $admk= $content[0];}else{ $admk= 0;}
				if( isset($content[1])){ $dmk= $content[1];}else{ $dmk= 0;}
				if( isset($content[2])){ $bjp= $content[2];}else{ $bjp= 0;}
				if( isset($content[3])){ $ind= $content[3];}else{ $ind= 0;}
				$data =[];
				$data['admk'] = [image_url.'images/static_img/election/AIADMK-candi.jpg','Madhusudhanan.E','AIADMK'];
				$data['dmk'] = [image_url.'images/static_img/election/DMK-Candi.jpg','Maruthu Ganesh.H','DMK'];
				$data['bjp'] = [image_url.'images/static_img/election/BJP-Candi.jpg','Nagarajan.K','BJP'];
				$data['ind'] = [image_url.'images/static_img/election/IND-Candi.jpg','Dinankaran.TTV','Independent'];
				$sortdata = ['admk'=>$admk,'dmk'=>$dmk,'bjp'=>$bjp,'ind'=>$ind];
				arsort($sortdata);
				$j=1;
				foreach($sortdata as $key=>$value):
					if($j==1){
						$arrow = image_url.'images/static_img/election/p-arrow.png';
					}else{
						$arrow = image_url.'images/static_img/election/down-arrow.png';
					}
					$template .='<div class="col-md-3 col-sm-12 col-xs-12 election_module_col">'; 
					$template .='<div class="split-25"><img src="'.$data[$key][0].'" class="img-responsive" ><img src="'.$arrow.'" class="img-responsive"  style="margin-top: 3px;"></div>';
					$template .='<div class="split-75"><h5>'.$data[$key][1].'</h5><h4>'.$data[$key][2].'</h4><h4>'.$value.' <span>VOTES</span></h4></div>';
					$template .='</div>';
					$j++;
				endforeach;
				echo $template;
			}else{
				echo '';
			}
		}else{
			echo '';
		}
	}

	public function Content_file_intimation()
	{
		$file_name = $_POST['file_name'];
		$contents  = $_POST['file_contents'];
		$file_to_save = FCPATH.'application/views/LIVENOW/'.$file_name;
		$handle = fopen($file_to_save , 'w+');
		if(flock($handle, LOCK_EX))
		{
			fwrite($handle, $contents);
			flock($handle, LOCK_UN);
		}
		return true;
	}
	
	public function captcha()
	{
		session_start();
		$random_alpha = (rand(10000, 100000));
		$captcha_code = substr($random_alpha, 0, 6);
		$_SESSION["captcha_code"] = $captcha_code;
		$target_layer = imagecreatetruecolor(70,30);
		$captcha_background = imagecolorallocate($target_layer, 0, 53, 79);
		imagefill($target_layer,0,0,$captcha_background);
		$captcha_text_color = imagecolorallocate($target_layer, 255, 255, 255);
		imagestring($target_layer, 5, 5, 5, $captcha_code, $captcha_text_color);
		header("Content-type: image/jpeg");
		imagejpeg($target_layer);
		
	}
	public function check_captcha()
	{
		session_start();
		//echo $_SESSION["captcha_code"];
		$entered_capicha=$this->input->post('capichatext');
		
		if($entered_capicha==$_SESSION["captcha_code"])
		{
			echo "correct";
		}
		else
		{
			echo "incorrect";
		}
	}
	public function add_askprabhuquestion()
	{
		$this->load->model('admin/askprabhu_model');
		$this->askprabhu_model->add_askprabhuquestion();
	}
	
	public function get_shorten_url(){
		$article_url  = $_POST['article_url'];
		$bitly        = getSmallLink($article_url); 
		$value['id']  = $bitly['id'];
		$value['msg'] = $bitly['msg'];
		echo json_encode($value);
	}
	
	public function getLivenowContent(){
		print_R($this->widget_model->GetLiveNowContent(3,$this->input->post('article_id')));
	}
	public function getLivenowContentStatic(){
		$articleId=$this->input->post('article_id');
		$articleurl=$this->input->post('article_url');
		$image_url=$this->input->post('image_url');
		$image_url=($image_url=='')?image_url.imagelibrary_image_path.'logo/nie_logo_600X390.jpg':$this->input->post('image_url');
		$FileName= $articleId.'.json';
		$path=FCPATH.'application/views/LIVENOW/';
		$Result=file_get_contents($path.$FileName);
		$Result=json_decode($Result,true);
		$Result=array_reverse($Result['details']);
		$i=0;
		$Template='';
		$pinTemplate='';
		foreach($Result as $Data):
			if($Data['status']==1):
				$Date=explode(' ',$Data['date']);
				$Date=explode(':',$Date[1]);
				$Date=$Date[0].':'.$Date[1];
				$Time=strtotime($Data['date']);
				$Time=Date('M j',$Time);
				
				if(isset($Data['pin']) && $Data['pin']=='1'){
					$pinTemplate .='<div style="box-shadow: 0px 2px 6px 2px #00000096;" class="live-inner-content lid_'.$i.'">';
					$pinTemplate .='<span class="livenow-title">'.$Date.' '.$Time.' <i class="fa fa-thumb-tack" aria-hidden="true"></i></span>';
					$pinTemplate .='<div class="livenow-socialicons"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$articleurl.'&title='.$Data['title'].'&picture='.$image_url.'"><i class="fa fa-facebook" aria-hidden="true"></i></a><a target="_blank" href="https://twitter.com/intent/tweet?text='.$Data['title'].$articleurl.' via @NewIndianXpress"><i class="fa fa-twitter" aria-hidden="true"></i></a><a class="whatsapp1" style="padding:0;"  data-link="'.$articleurl.'" data-txt="'.$Data['title'].'" data-count="true"><i class="fa fa-whatsapp fa_social"></i></a></div>';
					if($Data['title']!=''):
						$pinTemplate .='<h3 class="livenow_h3">'.$Data['title'].'</h3>';
					endif;
					$pinTemplate .='<div class="livenow-description">'.$Data['content'].'</div>';
					$pinTemplate .='<input type="hidden" id="l_'.$i.'" value="'.$i.'">';
					//$Template .='<hr class="livenow-hr">';
					$pinTemplate .='</div>'; 
				}else{
					$Template .='<div class="live-inner-content lid_'.$i.'">';
					$Template .='<span class="livenow-title">'.$Date.' '.$Time.' </span>';
					$Template .='<div class="livenow-socialicons"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$articleurl.'&title='.$Data['title'].'&picture='.$image_url.'"><i class="fa fa-facebook" aria-hidden="true"></i></a><a target="_blank" href="https://twitter.com/intent/tweet?text='.$Data['title'].$articleurl.' via @NewIndianXpress"><i class="fa fa-twitter" aria-hidden="true"></i></a><a class="whatsapp1" style="padding:0;"  data-link="'.$articleurl.'" data-txt="'.$Data['title'].'" data-count="true"><i class="fa fa-whatsapp fa_social"></i></a></div>';
					if($Data['title']!=''):
						$Template .='<h3 class="livenow_h3">'.$Data['title'].'</h3>';
					endif;
					$Template .='<div class="livenow-description">'.$Data['content'].'</div>';
					$Template .='<input type="hidden" id="l_'.$i.'" value="'.$i.'">';
					//$Template .='<hr class="livenow-hr">';
					$Template .='</div>'; 
				}
				
			endif;
			$i++;
		endforeach;
		echo $pinTemplate.$Template;
	}
	
	 public function app_livecontent(){
		$articleId = $this->input->post("articleId");
		$from = $this->input->post("from");
		$to = $this->input->post("to");
		$response = [];
		$return =[];
		$app['response'] =[];
		if($articleId !='' && $from != '' && $to != '' ){
			$FileName= $articleId.'.json';
			$path=FCPATH.'application/views/LIVENOW/';
			$Result=file_get_contents($path.$FileName);
			$Result=json_decode($Result,true);
			$Result=array_reverse($Result['details']);
			if(count($Result) < $to){ $to = count($Result);}
			if($from >= count($Result) || $to < $from){
				$response['status_code'] = 0;
				$response['status_message'] = 'maximum  level exceed';
				$response['content'] = null;
				$response['content_count'] = count($Result); 
			}else{
				for($i=(int)$from;$i<(int)$to;$i++){
					if($Result[$i]['status'] == 1){
						$return['date'] = $Result[$i]['date'];	
						$return['title'] = $Result[$i]['title'];	
						$return['content'] = $Result[$i]['content'];	
						$app['response'][] = $return;
					}
				}
				$response['status_code'] = 1;
				$response['status_message'] = 'success';
				$response['content'] = $app;
				$response['content_count'] = count($Result);
			}
			
		}else{
			$response['status_code'] = 0;
			$response['status_message'] = 'Invalid inputs';
			$response['content'] = null;
			$response['content_count'] = 0;
			
		}
		
		echo json_encode($response);
	
	} 
	public function getLivenowContentUpdate(){
		echo $this->widget_model->GetLiveNowContent(4,$this->input->post('lid'));
	}
	public function GetDynamicTables(){
		$Data= $this->widget_model->Get_dynamic_tables();
		$Template='';
		$flipTemplate='';
		if($Data=='NO ROWS'){
			$Template='<div>No tables found.</div>';
		}else{
			foreach($Data as $Datavalue):
				$Tid   =   $Datavalue->tid;
				$TableName   =   $Datavalue->table_name;
				$JsonTable  =   json_decode($Datavalue->table_properties,true);
				$JsonTable  =   $JsonTable['data'];
				$Total   =   $Datavalue->total;
				$Template .='<div class="col-md-12 dynamic-table-width">';
				if($TableName=='Uttar Pradesh'){
					$Template .='<span class="span_bg" style="background:url(http://www.newindianexpress.com/static_img/maps/uttar_pradesh.png)0 0 no-repeat;"></span>';
				}else if($TableName=='Punjab'){
					$Template .='<span class="span_bg" style="background:url(http://www.newindianexpress.com/static_img/maps/Punjab.png)0 0 no-repeat;"></span>';
				}else if($TableName=='Goa'){
					$Template .='<span class="span_bg" style="background:url(http://www.newindianexpress.com/static_img/maps/Goa.png)0 0 no-repeat;"></span>';
				}else if($TableName=='Uttarakhand'){
					$Template .='<span class="span_bg" style="background:url(http://www.newindianexpress.com/static_img/maps/Uatarakhand.png)0 0 no-repeat;"></span>';
				}else if($TableName=='Manipur'){
					$Template .='<span class="span_bg" style="background:url(http://www.newindianexpress.com/static_img/maps/Manipur.png)0 0 no-repeat;"></span>';
				}
				$Template .='<table class="table table-bordered common-table-election">';
				$Template .='<tr class="table-header-wrapper"><td colspan="3" style="border-color:#000;font-weight:bold;">'.$TableName.'</td><td colspan="1" class="table-header-wrapper-second" style="border-color:#000;" >'.$Total.'</td></tr>';
				$Template .='<tr class="table-header-title"><td>Party</td><td>Lead</td><td>Won</td><td>Total</td></tr>';
						for($i=0;$i<count($JsonTable);$i++){
							$totalVal = $JsonTable[$i]['field2'] + $JsonTable[$i]['field3'];
							$finalTotal = str_repeat('0' , (3- strlen($totalVal))).$totalVal;
							$Template .='<tr class="table-body-content">';
							$Template .='<td>'.$JsonTable[$i]['field1'].'</td>';
							$Template .='<td>'.$JsonTable[$i]['field2'].'</td>';
							$Template .='<td>'.$JsonTable[$i]['field3'].'</td>';
							//$Template .='<td>'.$JsonTable[$i]['field4'].'</td>';
							$Template .='<td>'.$finalTotal.'</td>';
							$Template .='</tr>';
						}
				$Template .='</table>';
				$Template .='</div>';
				if($this->input->get('type')=='flip'){
					$flipTemplate .='<div class="side">';
					$flipTemplate .='<h6>'.$TableName.' - '.$Total.'<span><i class="fa fa-times"></i></span></h6>';
					$flipTemplate .='<table>';
					$flipTemplate .='<thead><tr><th>Party</th><th>L+W</th></tr></thead>';
					$flipTemplate .='<tbody>';
					for($i=0;$i<count($JsonTable);$i++){
						$flipTemplate .='<tr><td>'.$JsonTable[$i]['field1'].'</td><td>'.$JsonTable[$i]['field4'].'</td></tr>';
					}
					$flipTemplate .='</tbody>';
					$flipTemplate .='</table>';
					$flipTemplate .='<h5><a>Full Result Here</a></h5>';
					$flipTemplate .='</div>';
				}
			endforeach;
		}
		
		if($this->input->get('type')=='flip'){
			echo $flipTemplate;
		}else{
			echo $Template;
		}
	}
	
	public function piechart(){
		$Data['data']=$this->widget_model->Get_Dynamic_Table_Data($this->input->post('tid'));
		$Template=$this->load->view('admin/GetTables',$Data,TRUE);
		echo $Template;
	
	}
	public function fifa_data(){
		$template ='';
		$filepath = FCPATH.'application/views/view_template/tab.table';
		if(file_exists($filepath)){
			$tabContent = file_get_contents($filepath);
			if($tabContent!=''){
				$tabdata = json_decode($tabContent,true);
				if(is_array($tabdata)){
					$template .= '<ul class="nav nav-tabs" role="tablist">';
					for($i=0;$i<count($tabdata);$i++):
						$template .='<li role="presentation" class="'.(($i==0)?'active':'').'"><a data-toggle="tab" href="#Section'.$i.'">'.$tabdata[$i][1].'</a></li>';
					endfor;
					$template .= '</ul><div class="tab-content">';
					for($i=0;$i<count($tabdata);$i++):
						$template .='<div role="tabpanel" class="tab-pane '.(($i==0)?'active':'').'" id="Section'.$i.'"><div id="fifa-table">';
						$template .='<table class="table table-bordered table-striped table-hover table-condensed table-responsive">';
						$template .='<thead><tr class="Resulthead"><th></th><th>Team</th><th>P</th><th>W</th><th>GD</th><th>Pts</th></tr></thead><tbody>';
						$content = $tabdata[$i][0];
						for($j=0;$j<count($content);$j++):
							$template .= '<tr>';
							$template .= '<td>'.((isset($content[$j][0]))? $content[$j][0] : '' ).'</td>';
							$template .= '<td>'.((isset($content[$j][1]))? $content[$j][1] : '' ).'</td>';
							$template .= '<td>'.((isset($content[$j][2]))? $content[$j][2] : '' ).'</td>';
							$template .= '<td>'.((isset($content[$j][3]))? $content[$j][3] : '' ).'</td>';
							$template .= '<td>'.((isset($content[$j][4]))? $content[$j][4] : '' ).'</td>';
							$template .= '<td>'.((isset($content[$j][5]))? $content[$j][5] : '' ).'</td>';
							$template .= '</tr>';
						endfor;
						$template .='</tbody></table>';
						$template .='</div></div>';
					endfor;
					$template .= '</div>';
					
				}else{
					$template ='';
				}
			}else{
				$template ='';
			}
		}else{
			$template ='';
		}
		echo $template;
    }
	
	public function external_api(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->input->post('url'));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		$Result = curl_exec($ch);
		$error=curl_error($ch);
		curl_close($ch);
		echo $Result;
	}
	
	public function external_api1(){
		$ch = curl_init($this->input->post('url'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$Result = curl_exec($ch);
		$error=curl_error($ch);
		curl_close($ch);
		print_r($error);
		echo $Result;
	}
	
}


class poll_result extends Commonwidget
{
	public function get_poll_results()
	{
		$this->widget_model->insert_poll_results();
	}
	
	public function select_poll_results()
	{
		extract($_POST);
		$poll_count = $this->widget_model->select_poll($get_poll_id)->row_array();
		echo json_encode($poll_count);
	}
}

class email_section extends Commonwidget
{
	public function share_article_via_email()
	{
    //load email helper
    $this->load->helper('email');
    //load email library
    $this->load->library('email');
    
    //read parameters from $_POST using input class
	$content_id = $this->input->post('content_id');
	$section_id = $this->input->post('section_id'); 
	$content_type = $this->input->post('content_type_id'); 
	$name = $this->input->post('name');  
	$share_email = $this->input->post('share_email',true);
	$refer_email = $this->input->post('refer_email',true);
	$share_content = $this->input->post('share_content');
	$share_url =  $this->input->post('share_url'); 
	$message =  $this->input->post('message'); 
	$body_text = $message.'</br>'.'shared url :'.$share_url;
  
    // check is email addrress valid or no
    if (valid_email($share_email)&&valid_email($refer_email)){  
      // compose email
      $this->email->from($share_email , $name);
      $this->email->to($share_email); 
	  $this->email->cc($refer_email);
      $this->email->subject($share_content);
      $this->email->message($body_text);  
      
      // try send mail ant if not able print debug
      if ( ! $this->email->send())
      {
        echo "Email not sent \n".$this->email->print_debugger();      
      }
	  
       $this->widget_model->update_most_hits_and_emailed('E', $content_type, $content_id, $share_content, $section_id," ");
	   
	   $insert_array = array(
							"content_id"	=> $content_id,
							"content_type" 	=> $content_type,
							"name"			=> addslashes($name),
							"from_email"	=> addslashes($share_email),
							"to_email"		=> addslashes($refer_email),
							"message"  		=> addslashes($message)
							);
	   
	   $this->widget_model->insert_share_email_details($insert_array);
	   
	     // successfull message
        echo "Email was successfully sent to $share_email";
      
    } else {

      echo "Email address ($share_email) is not correct.";
    }
	
	}
	
	
}
class view extends Commonwidget
{
	public function post_comment(){
		//$posted_comments =array();
		//$comments=new Comment_model();
		$this->load->model('admin/comment_model');
		if(count($_POST)==8 && $this->input->post('name')!='' && $this->input->post('email')!='' && $this->input->post('comment')!='' && $this->input->post('content_id')!='' && $this->input->post('section_id')!=''  && $this->input->post('content_type_id')!='' && $this->input->post('article_title')!='' && is_numeric($this->input->post('content_id'))){
			$GetArticleDetials = $this->comment_model->ArticleDetails($this->input->post('content_id'));
			if($GetArticleDetials['count']!=0){
				$data = $GetArticleDetials['data'];
				$content_title = html_entity_decode(stripslashes(strip_tags($data[0]->title, '</p>')), ENT_QUOTES, "UTF-8");
				if($content_title==$this->input->post('article_title') && $data[0]->section_id==$this->input->post('section_id')){
					$posted_comments = $this->comment_model->insert_article_comment();
				}else{
					$posted_comments['view_comments'] = "Enter Valid Comments";
				}
			}else{
				$posted_comments['view_comments'] = "Enter Valid Comments";
			}	
		}else{
			$posted_comments['view_comments'] = "Enter Valid Comments";
		}
		/*$show_comments='<div>';
		foreach($posted_comments as $article_comment)
		{
		$show_comments .='<div class="ArticlePosts">
		<span class="UserIcon"><i class="fa fa-user"></i></span>
		<div class="ArticleUser">';
		$show_comments .='<h4>'.$article_comment['Guestname'].'</h4>';
		$show_comments .='<p>'.($article_comment['UpdatedComment']!='')? $article_comment['UpdatedComment'] : $article_comment['OriginalComment'].'</p>';
		$time= $article_comment['Createdon']; $post_time= $this->comment_model->time2string($time);
		 $show_comments .='<p class="PostTime">'.$post_time.'ago<span class="SiteColor"> reply(0)</span> <i class="fa fa-flag"></i></p>';
		$show_comments .='</div>
		</div>';
		 } 
		 $show_comments .='</div>';*/
		 //print_r($posted_comments);exit;
		//$show_comments;
		 
		echo $posted_comments['view_comments'];	
	}
	
	public function Search_datatable()
	{
		$this->widget_model->get_search_result_data();
	}
	
	public function get_states_content(){
	if(isset($_POST['stateid'])!=''&& isset($_POST['stateid'])!=0){
		$domain_name = base_url();
		$summary_required  = ($_POST['summary_option']); //? 'y': 'n'
		if($_POST['rendermode'] == "manual")
		{
			$content_type= 1;
			/*$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($_POST['widgetinstanceid'], $_POST['stateid'] ,$_POST['mode'], $_POST['max_article']); */		
			$main_sction_id = $_POST['stateid'];
			$view_mode      = $_POST['mode'];				
		}
		else
		{
			$content_type= 1;
			/*$widget_instance_contents = $this->widget_model->get_all_available_articles_auto($_POST['max_article'], $_POST['stateid'] ,$content_type, $_POST['mode']);*/
			$main_sction_id = $_POST['stateid'];
			$view_mode      = $_POST['mode'];	
		}
		$widget_instance_contents = array();$widget_contents = array();
		$get_widget_instance = $this->widget_model->getWidgetInstance('', '', '', '', $_POST['widgetinstanceid'], $view_mode);
		$pagemaster_data = $this->widget_model->get_pagemaster_live_version($main_sction_id, $get_widget_instance['Page_type'], $view_mode); //get the live version data of sub section lead stories
		$live_version    = $pagemaster_data['Published_Version_Id'];
		
		$section_widgetID = $this->widget_model->get_widget_byname('Listing Page Lead Stories', $view_mode);
		$leadstory        = $this->widget_model->get_sub_sec_lead_stories_data($main_sction_id, $get_widget_instance['Page_type'], $get_widget_instance['WidgetDisplayOrder'], $section_widgetID['widgetId'], $main_sction_id, $live_version, $view_mode);
		if(count($leadstory) > 0)
		{
			$sec_leadstory_max_article    = $leadstory['Maximum_Articles'];
			$sec_leadstory_rendering_mode = $leadstory['RenderingMode'];
			$sec_leadstory_instanceID     = $leadstory['WidgetInstance_id'];
			$sec_leadstory_sectionID      = $leadstory['WidgetSection_ID'];
			//$summary_required             = $leadstory['isSummaryRequired'];
			
			if($sec_leadstory_rendering_mode == "manual")
			{
				$widget_instance_contents = $this->widget_model->get_widgetInstancearticles_rendering($sec_leadstory_instanceID, '', $view_mode, $sec_leadstory_max_article);
$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
$get_content_ids = implode("," ,$get_content_ids);
if($get_content_ids!='')
{
$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, "n", $_POST['mode']);	 // 4th param  $_POST['is_home']
		$widget_contents = array();
			foreach ($widget_instance_contents as $key => $value) {
				foreach ($widget_instance_contents1 as $key1 => $value1) {
					if($value['content_id']==$value1['content_id']){
					   $widget_contents[] = array_merge($value, $value1);
					}
				}
			}
}
			}
			else if($sec_leadstory_rendering_mode == "auto")
			{
				$is_home = $_POST['is_home'];
				$widget_contents = $this->widget_model->get_all_available_articles_auto($sec_leadstory_max_article, $main_sction_id, $content_type, $view_mode, $is_home);
			}
		}
	$show_simple_tab = '';
			$i =1; 
if(count($widget_contents)>0)
		     {				
 		foreach($widget_contents as $get_content)
		{
		// Code Block B - if rendering mode is manual then if custom image is available then assigning the imageid to a variable
		// Code Block B starts here - Do not change
		$show_image = "";
		$imageid ="";
			$original_image_path = "";
			$imagealt ="";
			$imagetitle="";
			$custom_title = "";
			$custom_summary = '';
			if($sec_leadstory_rendering_mode == "manual")  // $_POST['rendermode']
			{
				if($get_content['custom_image_path'] != '')
				{
					$original_image_path = $get_content['custom_image_path'];
					$imagealt            = $get_content['custom_image_title'];	
					$imagetitle          = $get_content['custom_image_alt'];												
				}
			  $custom_title = $get_content['CustomTitle'];
			  $custom_summary = $get_content['CustomSummary'];

			}
			if($original_image_path =="")                                                // from cms imagemaster table    
				{
					   $original_image_path  = $get_content['ImagePhysicalPath'];
					   $imagealt             = $get_content['ImageCaption'];	
					   $imagetitle           = $get_content['ImageAlt'];	
				}
			
		if($original_image_path !='' && get_image_source($original_image_path, 1))
		{
		$imagedetails = get_image_source($original_image_path, 2);
		$imagewidth = $imagedetails[0];
		$imageheight = $imagedetails[1];	
	
		if ($imageheight > $imagewidth)
		{
			$Image600X390 	= $original_image_path;
		}
		else
		{
		    $Image600X390  = str_replace("original","w600X390", $original_image_path);
		}
		$imagealt ="";
		$imagetitle="";
		if ($Image600X390 != '' && get_image_source($Image600X390, 1))
		{
		$show_image = image_url. imagelibrary_image_path . $Image600X390;
		}
		else {
		$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
		}
		}
		else
		$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
		// Code block C ends here
		
		// Assign block - assigning values required for opening the article in light box
		// Assign block starts here
		
		$content_url = $get_content['url'];
		$sectionURL = $domain_name.$_POST['tab_url'];
		
		$param = $_POST['param'];
		$live_article_url = $domain_name.$content_url.$param;
		// Assign block ends here
		// Assign article links block - creating links for  article summary Display article																
		
		if( $custom_title == '')
		{
		$custom_title = $get_content['title'];
		}	
		$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
		$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
		
		//  Assign article links block ends hers
		
		// Assign summary block - creating links for  article summary
		// Assign summary block starts here
		if( $custom_summary == '' && $sec_leadstory_rendering_mode == "auto")
		{
		$custom_summary =  $get_content['summary_html'];
		}
		
		$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);   //to remove first<p> and last</p>  tag
		
		// display title and summary block starts here
		if($i == 1)
		{
		$show_simple_tab .= '<div>
					 <div class="state2"><a  href="'.$live_article_url.'" class="article_click" ><img src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>';
		
		 $show_simple_tab .='<h4 class="subtopic">'.$display_title.'</h4>';
		if($summary_required==1){
		$show_simple_tab .= '<p class="para_bold summary">'.$summary.'</p>';  // Assign summary block 		
		}
		$show_simple_tab .= '</div>';
		}
		else
		{
		if($i == 2)
		{
		$show_simple_tab .='<div class="state3 common_p">';
		}
		$show_simple_tab .='<p> <i class="fa fa-angle-right"></i>'.$display_title.'</p>';
		if($i == count($widget_contents))
		{
		$show_simple_tab .='</div>';
		}
		} 
		if($i == count($widget_contents))
		{
		$show_simple_tab .='<div class="arrow_right"><a href="'.$sectionURL.'" class="landing-arrow">arrow</a></div></div>';
		}
			
		//Widget design code block 1 starts here																
		//Widget design code block 1 starts here			
		$i =$i+1;							  
		}
	}
	elseif($_POST['mode']=="adminview")
	{
		 $show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
	}
	echo $show_simple_tab;							
    }

}

	public function get_states_content_up(){
	if(isset($_POST['stateid'])!=''&& isset($_POST['stateid'])!=0){
		$domain_name = base_url();
		$summary_required  = ($_POST['summary_option']); //? 'y': 'n'
		if($_POST['rendermode'] == "manual"){		
			$main_sction_id = $_POST['stateid'];
			$view_mode      = $_POST['mode'];				
		}else{
			$content_type= 1;
			$main_sction_id = $_POST['stateid'];
			$view_mode      = $_POST['mode'];	
		}
		$max_article = $_POST['max_article'];
		$widget_instance_contents = array();$widget_contents = array();
		$get_widget_instance = $this->widget_model->getWidgetInstance('', '', '', '', $_POST['widgetinstanceid'], $view_mode);
		$pagemaster_data = $this->widget_model->get_pagemaster_live_version($main_sction_id, $get_widget_instance['Page_type'], $view_mode); //get the live version data of sub section lead stories
		$live_version    = $pagemaster_data['Published_Version_Id'];
		$section_widgetID = $this->widget_model->get_widget_byname('Section main widget', $view_mode);
		$leadstory        = $this->widget_model->get_sub_sec_lead_stories_data($main_sction_id, $get_widget_instance['Page_type'], $get_widget_instance['WidgetDisplayOrder'], $section_widgetID['widgetId'], $main_sction_id, $live_version, $view_mode);
		if(count($leadstory) > 0){
			$sec_leadstory_max_article    = $leadstory['Maximum_Articles'];
			$sec_leadstory_rendering_mode = $leadstory['RenderingMode'];
			$sec_leadstory_instanceID     = $leadstory['WidgetInstance_id'];
			$sec_leadstory_sectionID      = $leadstory['WidgetSection_ID'];			
			if($sec_leadstory_rendering_mode == "manual"){
				$widget_instance_contents = $this->widget_model->get_widgetInstancearticles_rendering($sec_leadstory_instanceID, '', $view_mode, $sec_leadstory_max_article);
				$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
				$get_content_ids = implode("," ,$get_content_ids);
				if($get_content_ids!=''){
					$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, "n", $_POST['mode']);	 // 4th param  $_POST['is_home']
					$widget_contents = array();
					foreach ($widget_instance_contents as $key => $value) {
						foreach ($widget_instance_contents1 as $key1 => $value1) {
							if($value['content_id']==$value1['content_id']){
								$widget_contents[] = array_merge($value, $value1);
							}
						}
					}
				}
			}else if($sec_leadstory_rendering_mode == "auto"){
				$is_home = $_POST['is_home'];
				$widget_contents = $this->widget_model->get_all_available_articles_auto($sec_leadstory_max_article, $main_sction_id, $content_type, $view_mode, $is_home);
			}
		}
		$show_simple_tab = '';
		$i =1;
		$count = 1;
		if(count($widget_contents)>0){	
			if($max_article!='' || $max_article!='0'){
				if(is_numeric($max_article) && $max_article <=count($widget_contents)){
					$widget_contents = array_slice($widget_contents, 0, $max_article, true);
				}
			}
			$CI = &get_instance();
			$this->live_db = $CI->load->database('live_db',TRUE);
			foreach($widget_contents as $get_content){
				$sectionname = $get_content['section_name'];
				$custom_title        = "";
				$original_image_path = "";
				$imagealt            = "";
				$imagetitle          = "";
				$Image600X300        = "";
				$custom_title        = "";
				if($render_mode == "manual"){
					if($get_content['custom_image_path'] != ''){
						$original_image_path = $get_content['custom_image_path'];
						$imagealt            = $get_content['custom_image_title'];	
						$imagetitle          = $get_content['custom_image_alt'];												
					}
					$custom_title            = stripslashes($get_content['CustomTitle']);
				}
				if($view_mode == "live"){
					if($original_image_path ==''){
						$original_image_path = $get_content['ImagePhysicalPath'];
						$imagealt            = $get_content['ImageAlt'];	
						$imagetitle          = $get_content['ImageCaption'];
					}
				}else{
					if($original_image_path ==""){
						$original_image_path  = $get_content['ImagePhysicalPath'];
						$imagealt             = $get_content['ImageCaption'];	
						$imagetitle           = $get_content['ImageAlt'];	
					}
				}
				$show_image="";
				if($original_image_path !='' && get_image_source($original_image_path, 1)){
					$imagedetails = get_image_source($original_image_path, 2);
					$imagewidth = $imagedetails[0];
					$imageheight = $imagedetails[1];	
		
					if ($imageheight > $imagewidth){
						$Image600X300 	= $original_image_path;
					}else{
						$Image600X300  = str_replace("original","w600X390", $original_image_path);
					}
					if(get_image_source($Image600X300, 1) && $Image600X300 != ''){
						$show_image = image_url. imagelibrary_image_path . $Image600X300;
					}else{
						$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
					}
					$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}else{
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
					$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}
				if( $custom_title == ''){
					$custom_title = stripslashes($get_content['title']);
				}
				$content_url = $get_content['url'];
				$param = $content['close_param']; //page parameter
				$live_article_url = $domain_name. $content_url.$param;
				$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);
				$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
				$SectionDetails = $this->live_db->query("SELECT URLSectionStructure FROM sectionmaster WHERE Section_id='".$get_content['section_id']."'")->result();
				$sectionUrl = $domain_name.@$SectionDetails[0]->URLSectionStructure;
				if($i==1){$show_simple_tab 	   .=	'<div class="SundaySecond" '.$widget_bg_color.'>';}
		
		if($count <= 3)
		{
			if($count==1)
			{
			
			$show_simple_tab.= '<div class="WidthFloat_L">'; 
			} 
			
		$show_simple_tab.= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 SundaySecondSplit">';
		$show_simple_tab.= '<div class="states_image"><a  href="'.$live_article_url.'" class="article_click"  >
		<img src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a></div>';
		$show_simple_tab .='<div class="states_title"><div class="state-up-section"><a href="'.$sectionUrl.'">'.$sectionname.'</a></div><h4 class="subtopic">'.$display_title.'</h4></div>';
		$show_simple_tab.= '</div>';
		
		
		if($count==3 )
		{
			 
		$show_simple_tab.=  '</div>';
		//$show_simple_tab .='</div>';
		
		$count=0;
		
		} 
			if($i == count($widget_contents))
			{
				if($i%3!=0)
				{
				//$show_simple_tab.=  '</div>';
				$show_simple_tab .='</div>';
				} 
			$show_simple_tab .='</div>';
			}
		$count ++;	
		}
				$i =$i+1;
										  
		}
	}elseif($_POST['mode']=="adminview"){
		 $show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
	}
	echo $show_simple_tab;							
    }
	}
	
	public function get_menu_content(){
	$count=0;$show_simple_tab ='';
	if($_POST){
    $widget_instance_contents = $this->widget_model->get_section_article_for_common_widgets($_POST['menuid'], $_POST['mode'], 1); // last parammeter indicates jumbo menu
	if(count($widget_instance_contents)>0)
	{
	$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
	$get_content_ids = implode("," ,$get_content_ids);
	$get_content_types = array_column($widget_instance_contents, 'content_type'); 
	$content_type = $get_content_types[0];
	$show_image= "";
	$view_mode = $_POST['mode'];
    $menu_count = ($_POST['menu_type']=="main")? 5 : 2;
	if($get_content_ids!='')
	{
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, "n", $view_mode);	
	
	$widget_contents = array();
		foreach ($widget_instance_contents as $key => $value) {
			foreach ($widget_instance_contents1 as $key1 => $value1) {
				if($value['content_id']==$value1['content_id']){
			       $widget_contents[] = array_merge($value1, $value); // reverse the argument to avoid override
				}
			}
		}
		
	if(isset($widget_contents)) { 
		foreach($widget_contents  as $get_content) {
		    $show_image = "";
			$image_path = $get_content['image_path'];
			if($image_path!= '')
			{
				$Image600X390 	= str_replace("original","w600X390", $image_path);
				$imagealt ="";
				$imagetitle="";
				if ($Image600X390 != '' && get_image_source($Image600X390, 1))
				{
					$show_image = image_url. imagelibrary_image_path . $Image600X390;
					if ($get_content['image_alt'] != '')
					$imagealt = $get_content['image_alt'];
					if($get_content['image_caption'] != '')
					$imagetitle = $get_content['image_caption'];
				}
			}else{
				$content_type = $get_content['content_type'];
				$image_path  = $get_content['ImagePhysicalPath'];
				$Image600X390 = str_replace("original","w600X390", $image_path);
			    $imagealt             = $get_content['ImageCaption'];	
			    $imagetitle           = $get_content['ImageAlt'];	

				if ( $Image600X390 != '' && get_image_source($Image600X390, 1))
				{
					$show_image = image_url. imagelibrary_image_path . $Image600X390;
				}else{
					$show_image = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}
			}
			
			$parent_sectionname = ($_POST['mode']=="live" && $content_type ==1)? $get_content['parent_section_name'] : '';
			$parent_sectionname = ($parent_sectionname!='')? $parent_sectionname.'/' : '';
			
			$content_url = $get_content['url'];  //article url
			$param = $_POST['param']; //page parameter
			$domain_name =  base_url();
			$live_article_url = $domain_name.$content_url.$param;
				
			// Assign block ends here
			// Assign article links block - creating links for  article 
			$custom_title = $get_content['CustomTitle'];
			if( $custom_title != '')
			{																	
				$display_title = $custom_title;
			}
			else
			{
				$display_title = $get_content['title'];
			}	
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$display_title);
			$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';

	if($count<=$menu_count && $show_image!='' && $parent_sectionname !='Columns/'){
	
	   if($count==0){
			$show_simple_tab .='<div class="MultiImageContent bigmenu-first">';
			$show_simple_tab .='<a  href="'.$live_article_url.'" class="article_click">';
			$show_simple_tab .='<img src="'.$show_image.'"  title = "'.$imagetitle.'" alt = "'.$imagealt.'"></a>'.$display_title.'</div>';
		}else{
			$show_simple_tab .=	'<span class="bigmenu-second"><i></i>'.$display_title.'</span>';
		}
	
	$count++;
	}else if($parent_sectionname =='Columns/'){ 
	$show_simple_tab .='<div class="MultiImageContent">
		
	 '.$display_title.'</div>';
	$count++;
	}
	} 
	}
	}
	}
	}
	echo $show_simple_tab;
	}
	
public function get_editor_pick_content(){
  $domain_name =  base_url();
  //$type        = $this->input->get('type');
  $type        = $_REQUEST['type'];
 // $view_mode   = $this->input->get('mode');
  $view_mode   = $_REQUEST['mode'];
	if($type=='editor_pick'){
  $editor_pick_articles = $this->widget_model->get_section_article_for_common_widgets(0, $view_mode, 2);    // last parameter indicates trending now
  
 // print_r($editor_pick_articles); exit;
  }
  elseif($type=='trending'){
  $editor_pick_articles = $this->widget_model->get_section_article_for_common_widgets(0, $view_mode, 3);    // last parameter indicates trending now
  }else if($type=='most_read'){
  $get_time       = $this->widget_model->select_setting($view_mode);
  $time_mostread  = $get_time['timeintervalformostreadarticle'];
  $mostread_limit = $get_time['articlecountformostreadnow'];
  $time_mostread  = strtotime($time_mostread) - strtotime('today');
  $editor_pick_articles = $this->widget_model->get_content_by_hit_count($time_mostread,$mostread_limit);
  }
 //$most_read_articles = $this->widget_model->get_content_by_most_commented($time_mostread,$mostread_limit); 
		
	  if (function_exists('array_column')) 
		{
	$get_content_ids = array_column($editor_pick_articles, 'content_id'); 
		}else
		{
	$get_content_ids = array_map( function($element) { return $element['content_id']; }, $editor_pick_articles);
		}
	$get_content_ids = implode("," ,$get_content_ids);
	$content_type = 1;
	if($get_content_ids!='')
	{
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, 'n', $view_mode);	
	
	$widget_contents = array();
		foreach ($editor_pick_articles as $key => $value) {
			foreach ($widget_instance_contents1 as $key1 => $value1) {
				if($value['content_id']==$value1['content_id']){
			       $widget_contents[] = array_merge($value, $value1);
				}
			}
		}
		
	if(count($widget_contents)>0)
		     {				
 		foreach($widget_contents as $get_content)
		{
		 $custom_title = '';
		 $content_url = $get_content['url'];  //article url
		// $param = $this->input->get('param');; //page parameter
		 $param = $_REQUEST['param']; //page parameter
		 $domain_name =  base_url();
		 $live_article_url = $domain_name.$content_url.$param;
		  if($type=='trending'){
		 $custom_title    = $get_content['CustomTitle'];
		  }
			if( $custom_title != '')
			{																	
				$display_title = $custom_title;
			}
			else
			{
				$display_title = $get_content['title'];
			}
			$display_title  =stripcslashes($display_title);
		 echo '<p><i class=""></i>
		  <a  href="'.$live_article_url.'"  class="article_click" >'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $display_title).'</a></p>'; 
		  
		}
	}
		}elseif($view_mode=="adminview"){
		 echo '<div class="margin-bottom-10">'.no_articles.'</div>';
		}
  }
  
  public function get_add_widget(){
  $widget_instance_id = $_POST['instance_id'];
  $view_mode = $_POST['mode'];
   $widget_instance_details = $this->widget_model->getWidgetInstance('', '','', '', $widget_instance_id, $view_mode);		
   echo $widget_instance_details['AdvertisementScript'];exit;
  }
  
  public function update_hits()
  {
	  $domain_name            = base_url();
	  $email_count_update_for = $this->input->post('update_emailed_count');
	  $content_id      = $this->input->post('content_id');
	  $content_type_id = $this->input->post('content_type_id');
	  
	  if($email_count_update_for== "article"){
	  $view_mode       = ($this->input->post('content_from')=="preview")? "adminview": "live";
	  $title           = addslashes($this->input->post('title'));
	  $section_id      = $this->input->post('section_id');
	  $page_param      = '';//$this->input->post('page_param');
	  $content_created_on = $this->input->post('article_created');
	                       /* --------- increase hits in content_hit_history -------------*/ 
	// $this->widget_model->update_most_hits_and_emailed('H' , $content_type_id,  $content_id, $title, $section_id, $content_created_on);
      $this->widget_model->update_trending_read_hits($content_id, $content_type_id); 
	                     /* ------------------------ end hits adding ------------------- */
						 
						  /* ------------------------ Get Recent Article ------------------- */
	$show_recent_article = "No_News";
	$recent_articles = $this->widget_model->get_section_article_for_common_widgets($section_id, $view_mode, 4); // last parammeter indicates Recent related articles
	if(count($recent_articles)>0)
	{
	if (function_exists('array_column')) 
		{
	$get_content_ids = array_column($recent_articles, 'content_id'); 
		}else
		{
	$get_content_ids = array_map( function($element) { return $element['content_id']; }, $recent_articles);
		}
		shuffle($get_content_ids);
	$get_content_id = array_rand($get_content_ids, 2);
	if (function_exists('array_column')) 
		{
	$get_content_types = array_column($recent_articles, 'content_type'); 
		}else
		{
	$get_content_types = array_map( function($element) { return $element['content_type']; }, $recent_articles);
		}
	$content_type = $get_content_types[0];
	$article_id   = $get_content_ids[$get_content_id[0]];
	if($article_id!='')
	{
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($article_id, $content_type, "n", $view_mode);	
	
	$widget_contents = array();
		foreach ($recent_articles as $key => $value) {
			foreach ($widget_instance_contents1 as $key1 => $value1) {
				if($value['content_id']==$value1['content_id']){
			       $widget_contents[] = array_merge($value1, $value);
				}
			}
		}
	}
	if(count($widget_contents)>0)
		     {				
 		foreach($widget_contents as $get_content)
		{
	        $content_type = $get_content['content_type'];
			$original_image_path            = $get_content['image_path'];
			$image_alt                      = $get_content['image_alt'];
			$image_title                    = $get_content['image_caption'];
			$custom_title                   = stripslashes($get_content['CustomTitle']);
			$custom_summary                 = stripslashes($get_content['CustomSummary']);
			/*if($view_mode == "live" && $original_image_path=='')
			{
				if($original_image_path =='')
				{
						$original_image_path = ($content_type==3)? $get_content['first_image_path']: (($content_type==4)? $get_content['video_image_path']: (($content_type==5)? $get_content['audio_image_path']: $get_content['ImagePhysicalPath']));
						$image_alt            = ($content_type==3)? $get_content['first_image_alt']: (($content_type==4)? $get_content['video_image_alt']: (($content_type==5)? $get_content['audio_image_alt']:  $get_content['ImageAlt']));	
						$image_title          = ($content_type==3)? $get_content['first_image_title']: (($content_type==4)? $get_content['video_image_title']: (($content_type==5)? $get_content['audio_image_title']: $get_content['ImageCaption']));
				}
			}
			else */if($original_image_path =="")     // from cms imagemaster table    
				{
						
					   $original_image_path  = $get_content['ImagePhysicalPath'];
					   $image_alt             = $get_content['ImageCaption'];	
					   $image_title           = $get_content['ImageAlt'];	
				}
			
			if($original_image_path!= '' && getimagesize(image_url_no . imagelibrary_image_path .$original_image_path))
			{
				$Image600X300 	= str_replace("original","w600X300", $original_image_path);
				if (getimagesize(image_url_no . imagelibrary_image_path . $Image600X300) && $Image600X300 != '')
				{
					$recent_article_img = image_url. imagelibrary_image_path . $Image600X300;
				}else{
					$recent_article_img	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
					}
			   }else{
					$recent_article_img	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
					}			
$content_url         = $get_content['url'];
$param               = $page_param; //page parameter
$recent_string_value = $domain_name. $content_url.$param;

if( $custom_title == '')
{
	$custom_title = stripslashes($get_content['title']);
}	
$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
if( $custom_summary == '')
{
	$custom_summary = stripslashes($get_content['summary_html']);
}	
$custom_summary = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary);   //to remove first<p> and last</p>  tag
$section_name   = $get_content['section_name'];
$recent_text    = ($content_type==3)? "Gallery" : (($content_type==4)? "Video": (($content_type==5)? "Audio" : "Article" ));
$read_text    = ($content_type==3)? "View more" : (($content_type==4)? "Watch": (($content_type==5)? "Listen more" : "Read more" ));

$show_recent_article = '<div id="slidebox" class="slide-box"> <i class="fa fa-times slide-close"></i>';
 $show_recent_article.='<h4 class="SiteColor article-recent">Recent '.$recent_text.' in '.$section_name.'</h4>';
  $show_recent_article.='<div class="slide-headlines"> <img src="'.$recent_article_img.'" title="'.$image_title.'" alt="'.$image_alt.'" />
    <h4 class="subtopic"><a href="'.$recent_string_value.'">'.$display_title.'</a></h4>';
    $show_recent_article.='<div class="slider-lead">
      '.substr($custom_summary, 0, 150).'
      <a href="'.$recent_string_value.'"><span class="arrows SiteColor">'.$read_text.' <i class="fa fa-angle-double-right"></i> </span></a> </div>
  </div>
</div>';
//echo $show_recent_article;
		}
			 }
	}else{
$recent_article = $this->widget_model->get_section_recent_article($content_id, $section_id,$content_type_id, "live");  
if(count($recent_article)> 0){ 
$recent_text = "Article";	
if($content_type_id=='3'){
$recent_text = "Gallery";	
$img_path = $recent_article['first_image_path']; 
$img_title = $recent_article['first_image_path']; 
$img_alt = $recent_article['first_image_alt']; 
$read_text = "View more";
}elseif($content_type_id=='4'){
$recent_text = "Video";
$img_path = $recent_article['video_image_path']; 
$img_title = $recent_article['video_image_title']; 
$img_alt = $recent_article['video_image_alt']; 
$read_text = "Watch";
}elseif($content_type_id=='5'){
$recent_text = "Audio";
$img_path = $recent_article['audio_image_path']; 
$img_title = $recent_article['audio_image_title']; 
$img_alt = $recent_article['audio_image_alt']; 
$read_text = "listen more";
}else{
$img_path = $recent_article['article_page_image_path']; 
$img_title = '';
$img_alt = '';
$read_text = "Read more";
}

$recent_string_value = $domain_name.$recent_article['url'].$page_param;
if (getimagesize(image_url_no . imagelibrary_image_path . $img_path) && $img_path != '')
{
$img_path = str_replace("original","w600X300", $img_path);
$recent_article_img = image_url. imagelibrary_image_path.$img_path;	
}else{
$recent_article_img = image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
}
$show_recent_article = '<div id="slidebox" class="slide-box"> <i class="fa fa-times slide-close"></i>';
 $show_recent_article.='<h4 class="SiteColor article-recent">Recent '.$recent_text.' in '.$recent_article['section_name'].'</h4>';
  $show_recent_article.='<div class="slide-headlines"> <img src="'.$recent_article_img.'" title="'.$img_title.'" alt="'.$img_alt.'" />
    <h4 class="subtopic"><a href="'.$recent_string_value.'">'.$recent_article['title'].'</a></h4>';
    $show_recent_article.='<div class="slider-lead">
      '.substr($recent_article['summary_html'], 0, 150).'
      <a href="'.$recent_string_value.'"><span class="arrows SiteColor">'.$read_text.' <i class="fa fa-angle-double-right"></i> </span></a> </div>
  </div>
</div>';
//echo $show_recent_article;
}
	}
$return_value['recent_news'] = $show_recent_article;
}
$hit_value = $this->widget_model->get_hit_for_content_by_id($content_id, $content_type_id);  
$return_value['emailed']     = (count($hit_value)>0)? $hit_value['emailed']: 0 ; 
echo json_encode($return_value);
						  
						    /* ------------------------ Get Recent Article END------------------- */
						  
						 
						 
  }
  
  public function subscribe_newsletter()
  {
	$email  = $this->input->post('email_newsletter');
	$result =  $this->widget_model->insert_subscribed_email($email);
	if($result!=0)
	{
		echo "Your Email is subscribed with our Newsletter service..";
		$settings       = $this->widget_model->select_setting("live");
		$email_on = $settings['send_email'];
		$email_to = $settings['email_to'];
		//load email helper
		$this->load->helper('email');
		//load email library
		$this->load->library('email');
		  if (($email_on==1)&&valid_email($email_to)&&valid_email($email_to)){  
		  // compose email
		  $body_text = "Hi , Here is a new subscriber ".$email;
		  $this->email->from($email , "News Letter user");
		  $this->email->to($email_to); 
		  $this->email->subject("New Subscriber For News Letter!");
		  $this->email->message($body_text);  
		  
		  // try send mail ant if not able print debug
		  if ( ! $this->email->send())
		  {
			echo "Email not sent \n".$this->email->print_debugger();      
		  }
		  }
		exit;
	}else
	{
		echo "Sorry Email already subscribed!";exit;
	}
	 	  
  }
	 
	 public function get_breaking_news_content()
	 {
			$view_mode = $this->input->get('mode');
			$param = $this->input->get('param');
			$breaking_news = $this->widget_model->get_widget_breakingNews_content($view_mode);
			
			$scroll_speed = $this->widget_model->select_setting($view_mode);
			$news = "";
			$domain_name =  base_url();
			foreach($breaking_news as $news_content)
			{
				$news_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $news_content['Title']);   //to remove first<p> and last</p>  tag
				if($news_content['Content_ID'] != '')
				{
					$content_type = $this->input->get('type');
					$is_home = $this->input->get('is_home');
					if($view_mode=='live'){
					$content_details = $this->widget_model->get_contentdetails_from_database($news_content['Content_ID'], $content_type,$is_home,$view_mode);
					$content_url = $content_details[0]['url'];
					}else{
					$content_url = $news_content['url'];
					}
					$live_article_url = $domain_name.$content_url.$param;					
					$news_content = '<a  href="'.$live_article_url.'" class="article_click"  >'.$news_title.'</a>';
				}
				else
				{
					$news_content = $news_title;
				}
				
				$news .=  '<div><p>'.$news_content.'</p></div>';
			}		
             $scroll_amount = ($scroll_speed['breakingNews_scrollSpeed'] != "" && $scroll_speed['breakingNews_scrollSpeed'] != 0) ?  $scroll_speed['breakingNews_scrollSpeed']*1000 : 5*1000 ;  
			 			
			if(count($breaking_news)>0){
				 $value['news']= $news;
				 $value['scroll_amount']= $scroll_amount;
			}else{
			     $value['news']= "no_news";
				 $value['scroll_amount']= $scroll_amount;

			}
			echo json_encode($value);

	 }
}
?>