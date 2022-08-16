<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Askprabhu extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/askprabhu_model');
		$this->load->dbutil();
	}
	public function index()//calls the subclass function to list
	{
		$indexobject=new Questionlist();
		$indexobject->call_index();
	}
	public function capicha()
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
	public function check_capicha()
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
	public function ajax_call()//ajax call to display
	{
		$data['userdetails'] = $this->askprabhu_model->calldisplay_class();
	}
	public function call_edit_class()//calls the subclass function to edit
	{
		$editobject=new Questionlist;
		$editobject->edit();
	}
	public function call_update_class()//calls the subclass function to update
	{
		$updateobject=new editquestions;
		$updateobject->updatedetails();
	}	
	public function call_delete_class()//calls the subclass function to delete
	{
		$deleteobject=new Questionlist;
		$deleteobject->delete();
	}	
	public function check_validate()
	{
		$this->input->post('replycheck');
	}
	public function askprabhu_datatable()
	{
		$this->askprabhu_model->datatable_askprabhu();
	}
	public function add_askprabhuquestion()
	{
		$this->askprabhu_model->add_askprabhuquestion();
	}
	public function pagination()
	{
		 $this->askprabhu_model->listpagination();
	}
	
	public function question_document()
	{
		$updateobject=new generate_word;
		$updateobject->question_document();
	}	 
}


class generate_word extends Askprabhu
{
	public function question_document()
	{
		$question_id_split = $this->uri->segment(4);
		$str = str_replace('-',',',$question_id_split);
		$question_id= explode(",",$str);
		
		$get_date = date('d-m-Y');
		header("Content-Type: application/vnd.ms-word");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-disposition: attachment; filename=\"askprabhu_".$get_date.".doc\"");
		
		$word_data = '<html><body><h1>Ask Prabhu - Questions</h1><br>';
		foreach($question_id as $question_id_spliit)
		{
			$askprabu_question = $this->askprabhu_model->fetch_word_data($question_id_spliit);
			foreach($askprabu_question as $fetch_askprabhu)
			{
				$word_data .= '<strong>Name: </strong>'.$fetch_askprabhu['UserName'].'<br>
				<strong>Location: </strong>'.$fetch_askprabhu['Place'].'<br>
				<strong>Email: </strong>'.$fetch_askprabhu['EmailID'].'<br>
				<strong>Question: </strong>'.$fetch_askprabhu['Questiontext'].'<br><br>
				<strong>Answers: </strong><br><br><br><br><br>';
			}
		}
		
		$word_data .= '</body></html>';
		echo $word_data;
		exit;
	}
}
class Questionlist extends Askprabhu
{
	public function call_index()//listing the questionlist
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Ask Prabhu Answer");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$data['title']		= 'Ask Prabhu';
			$data['template'] 	= 'askprabhu_manager';
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect('niecpan/common/access_permission/askprabhu_manager');	
		}
	}
	public function edit()//calls the model to edit
	{	
		$data['Menu_id'] = get_menu_details_by_menu_name('Ask Prabhu Answer');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1)
		{
			$data['title']		= 'Ask Prabhu Reply';
			$data['template'] 	= 'ask_prabu_reply';
			$id = $this->uri->segment('4');
			$data['userdetails'] = $this->askprabhu_model->callgetuser_model($id);
			$this->load->view('admin_template',$data);
		}
		else 
		{
			redirect('niecpan/common/access_permission/edit_askprabhu');
		}
	}
	public function delete()//calls the model for deleting the records
	{
		$Id = $this->uri->segment('4');
		$this->askprabhu_model->calldelete_model($Id);
		$this->session->set_flashdata('success_delete', 'Deleted successfully');
		redirect(base_url().'niecpan/askprabhu');
		//$this->index();
	}
}
	
class editquestions extends Questionlist
{
	public function updatedetails()// calls the model for updating the details
	{
		 $Id = $this->uri->segment('4');
		$this->askprabhu_model->callupdate_model($Id,USERID);
		$this->session->set_flashdata('success', 'Updated successfully');
		redirect(base_url().'niecpan/askprabhu');
		//$this->index(); 
	}
	
}

