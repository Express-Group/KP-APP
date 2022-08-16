<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Commonwidget extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('cookie');		
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
	public function update_most_hits_and_emailed($type, $content_id)
	{
		$class_object = new email_section;
		$class_object->update_most_hits_and_emailed($type, $content_id);
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
	  
       $this->widget_model->update_most_hits_and_emailed('E', $content_id);
	     // successfull message
        echo "Email was successfully sent to $share_email";
      
    } else {

      echo "Email address ($share_email) is not correct.";
    }
	
	}
}
