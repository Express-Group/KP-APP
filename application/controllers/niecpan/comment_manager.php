<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class comment_manager extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/comment_model');
	}
	public function index()
	{
		$set_object=new view;
		$set_object->index();
	}
	public function comment_datatable()
	{
		$set_object=new datatable;
		$set_object->comment_datatable();
	}
	public function changestatus()
	{
		$set_object=new status;
		$set_object->changestatus();
	}
	function check_records()
	{
		$set_object=new view;
		$set_object->check_records();
	}
	function post_comment()
	{
		$set_object=new view;
		$set_object->post_comment();
	}
}

class view extends comment_manager
{
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Comments");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') 
		{
			$data['title']		= 'Comment Manager';
			$data['template'] 	= 'comment_manager';
			//$data['comments']=$this->comment_model->datatable_comment();
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect('niecpan/common/access_permission/comment_manager');	
		}
		
	}
	public function check_records()
	{
		$comment['rows']=$this->comment_model->get_commentrecords();	
		echo $comment['rows'];exit; 
	}
	
	public function post_comment(){
		//$posted_comments =array();
		$posted_comments = $this->comment_model->insert_article_comment();	
		$show_comments='<div>';
		foreach($posted_comments as $article_comment)
		{
		$show_comments .='<div class="ArticlePosts">
		<span class="UserIcon"><i class="fa fa-user"></i></span>
		<div class="ArticleUser">';
		$show_comments .='<h4>'.$article_comment['Guestname'].'</h4>';
		$show_comments .='<p>'.($article_comment['UpdatedComment']!='')? $article_comment['UpdatedComment'] : $article_comment['OriginalComment'].'</p>';
		$time= (time()-strtotime($article_comment['Createdon'])); $post_time= $this->comment_model->time2string($time);
		 $show_comments .='<p class="PostTime">'.$post_time.'ago<span class="SiteColor"> reply(0)</span> <i class="fa fa-flag"></i></p>';
		$show_comments .='</div>
		</div>';
		 } 
		 $show_comments .='</div>';
		 //print_r($posted_comments);exit;
		//$show_comments;
		 
		echo $show_comments;	
	}
	
}
class datatable extends comment_manager
{
	public function comment_datatable()//to list comments
	{
		$comments = $this->comment_model->datatable_comment();
		print_r($comments);exit;
	}

}
class status extends comment_manager
{
	public function changestatus()//to change status
	{
//		print_r($_POST); exit;
		if(isset($_POST['required_values']))
		{
			$post = $_POST['required_values'];		
			$get_status = $_POST['status'];
			$modified = date("Y-m-d H:i:s", time());
			foreach($post as $key => $values)
			{
				$post_values = explode(",",$values);
				$status = $this->comment_model->statuschange($post_values[0],$get_status,$post_values[1],$modified,USERID);
			}
		}
		else
		{
			$get_comment_id = $_POST['commentid'];
			$get_status = $_POST['status'];
			$user_comment=$_POST['comment'];
			date_default_timezone_set('Asia/Calcutta');
			$modified=date("Y-m-d H:i:s");
			$status=$this->comment_model->statuschange($get_comment_id,$get_status,$user_comment,$modified,USERID);
			
		}
	
	}
}

?>