<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Askprabhu_model extends CI_Model
{
	function __construct()//to initialise the variables
	{
		parent::__construct();
		$this->load->database();
	}
	public function calldisplay_class()//calls displaymodel subclass fn to list
	{
		$displaydetails= new displaymodel();
		return $displaydetails->get_user_details();
	}
	public function callupdate_model($Id,$userid)//calls displaymodel subclass fn to upadte
	{
		$update_objectmodel=new displaymodel;
		return $update_objectmodel->update_user($Id,$userid);
	}
	public function calldelete_model($Id)//calls displaymodel subclass fn to delete
	{
		$delete_objectmodel=new displaymodel;
		return $delete_objectmodel->delete_user($Id);
	}
	public function callgetuser_model($id)//calls displaymodel subclass fn to edit 
	{
		$getuser_objectmodel=new displaymodel();
		return $getuser_objectmodel->get_user($id);
	}
	public function datatable_askprabhu()
	  {
		$datatables=new datatable;
		return $datatables->pagination_datatable();	  
	  }
	  public function add_askprabhuquestion()
	{
		$class_object = new insert_question;
		return $class_object->add_askprabhuquestion();
	}
	public function listpagination()
	{
		$class_object = new askprabhu_pagination;
		return $class_object->listpagination();
	}
	
	public function fetch_word_data($question_id)
	{
		$class_object = new askprabu_word_doc;
		return $class_object->fetch_word_data($question_id);
	}
}

class askprabu_word_doc extends askprabhu_model
{
	public function fetch_word_data($question_id)
	{
		$this->db->reconnect();
		$gen_word= $this->db->query('CALL  edit_askprabhu_qnlist ("'.$question_id.'")')->result_array(); 
		return $gen_word;
	}
}
class displaymodel extends askprabhu_model
{
	public function get_user($id)//to display the desired record details of particular id// 
	{	
		$this->db->reconnect();
		$getvalue= $this->db->query('CALL  edit_askprabhu_qnlist ("'.$id.'")'); 
		return $getvalue->result();
	}
	public function update_user($Id,$userid)//to edit the desired records
	{
		date_default_timezone_set('Asia/Calcutta');
		$modified_date=date("Y-m-d h:i:s");
		$id_value=$this->input->get('id');
		$editor_values = $this->input->post('txtCkeditor');
		$html_values= trim($this->input->post('txtCkeditor'));
		$plain_textvalues= strip_tags($editor_values);
		$status=trim($this->input->post('ddStatus'));
		$user_name =  $this->input->post('u_name');
		$editor_qsn =  $this->input->post('txtCkeditor_qsn');
		
		$data=array(
		'AnswerInHTML'=>trim($this->input->post('txtCkeditor')),
		'AnswerInPlainText'=>$plain_textvalues,);
		$this->db->reconnect();
		//echo 'CALL  update_askprabhu_qnlist ("'.$plain_textvalues.'","'.addslashes($html_values).'","'.$status.'","'.$Id.'","'.$modified_date.'","'.$userid.'")';exit;
		$this->db->query('CALL  update_askprabhu_qnlist ("'.$plain_textvalues.'","'.addslashes($html_values).'","'.$status.'","'.$Id.'","'.$modified_date.'","'.$userid.'", "'.$user_name.'", "'.$editor_qsn.'")'); 
	}
	public function delete_user($id) //to delete the desired records//
	{	
		$this->db->reconnect();
		$this->db->query('CALL  delete_askprabhu_qnlist ("'.$id.'")'); 
	}
	public function get_user_details() //to display the available records//
	{
		/*$this->db->reconnect();
		$getvalue= $this->db->query('CALL  display_askprabhu_qnlist ()'); 
		$config = array(
		'root' => 'root',
		'element' => 'element',
		'newline' => "\n",
		'tab' => "\t"
		);
		echo  $this->dbutil->xml_from_result($getvalue, $config);*/
	}
}

class datatable extends Askprabhu_model
{
	public function pagination_datatable()
	{
		
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
	
		 
		 
		
		switch ($Field) {
     case 0:
        $order_field = 'Question_id';
        break;
    case 1:
        $order_field = 'Questiontext';
        break;
    case 2:
        $order_field = 'EmailID';
        break;
   /* case 3:
       $order_field = 'IPAddress';
        break;*/
	case 3:
       $order_field = 'SubmittedOn';
        break;
	case 4:
       $order_field = 'AnswerInPlainText';
        break;
	case 5:
  	   $order_field = 'Status';
	break;
	
		
    default:
        $order_field = 'Question_id';
		}
		
		$this->db->reconnect();
		$Total_rows = $this->db->query('CALL askprabhu_datatable("","","","","'.$filterby.'","")')->num_rows();
		
		
		/*$Search_value = $Search_text;
		
		if($Search_by == 'article_id') {
		$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		
		if($Search_result == '')
			$Search_value = $Search_text;
		else
			$Search_value = $Search_result;
		}*/
		
		$this->db->reconnect();
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$searchtxt= htmlspecialchars(trim($searchtxt));
		$searchtxt = addslashes(str_replace("'", "&#039;", $searchtxt));
		
		$article_manager =  $this->db->query('CALL askprabhu_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->result_array();	



		$this->db->reconnect();
		
		$recordsFiltered =  $this->db->query('CALL askprabhu_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->num_rows();
	//echo $this->db->last_query(); exit;	
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name("Ask Prabhu Answer");
		foreach($article_manager as $section) {
			
			$subdata = array();
	        $subdata[] = $section['Question_id'];
			if(strlen($section['Questiontext']) > 30)
			{
	
		//	$subdata[] = '<div align="left"><a class="Headtooltip" href="" data-toggle="tooltip" title="Edit">'.substr($section['Questiontext'], 0, 20).'...'.' <span>'.$section['Questiontext'].'</span> </a></div>';
			$subdata[] ='<p class="tooltip_cursor" href="#" title="'.htmlspecialchars($section['Questiontext']).'" style="line-height:1.5">'.substr($section['Questiontext'], 0, 100).'...'.'</p>';
			
			}
			else
			{
			$subdata[] = $section['Questiontext'];	
				
			}
			//$subdata[] = $section['Questiontext'];
			$subdata[] = $section['UserName'].'<br>'.$section['EmailID'].'<br>'.$section['IPAddress']; 
			$subdata[] = date("d-m-Y h:i:s",strtotime($section['SubmittedOn']));
			if(strlen($section['AnswerInPlainText']) > 30)
			{
			$subdata[] ='<p class="tooltip_cursor" href="#" title="'.$section['AnswerInPlainText'].'">'.substr($section['AnswerInPlainText'], 0, 20).'...'.'</p>';
			
			}
			else
			{
				$subdata[] = $section['AnswerInPlainText'];
			}
			if($section['Status']==1)
			$subdata[] = '<td><i title="Approve" class="fa fa-check"></i></td>';
			elseif($section['Status']==3)
			$subdata[] = '<td><i title="Reject"  class="fa fa-times"></i></td>';
			else
			$subdata[] = '<td><i title="Pending" class="fa fa-exclamation-triangle"></i></td>';
			
			/* $subdata[] ='<a class="button tick" href="'.base_url().'admin/askprabhu/call_edit_class/'.$section['Question_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>
			  <a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_askprabhu('.$section['Question_id'].')"  id="'.$section['Question_id'].'"> <i class="fa fa-trash-o"></i> </a>'; */
			  
			  $set_rights = "";
			  
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<a class="button tick" href="'.base_url().'niecpan/askprabhu/call_edit_class/'.$section['Question_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_askprabhu('.$section['Question_id'].')"  id="'.$section['Question_id'].'"> <i class="fa fa-trash-o"></i> </a>'; 
			  }
			  else 
			  { 
			  	$set_rights.="";
			  }
			   $set_rights .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="question_id[]" id="question_id" value="'.$section['Question_id'].'" ></span>';
			    
   			  $subdata[] = $set_rights;
			  
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
				if($recordsFiltered == 0) {

				}
		
		echo json_encode($data);
		exit;
		
	}
		
		
	
	
}
class insert_question extends Askprabhu_model
{
	public function add_askprabhuquestion()
	{
		$question=$this->input->post('question');
		$name=$this->input->post('username');
		$place=$this->input->post('place');
		$email=$this->input->post('mailid');
		$modified=$SubmittedOn=date("Y-m-d H:i:s");
		$this->db->reconnect();

		$question_askprabhu= $this->db->query('CALL insert_askprabhu_qnlist("'.$name.'","'.addslashes($question).'","","","'.$place.'","'.$email.'","2","'.$SubmittedOn.'","'.$modified.'")'); 
		if($question_askprabhu)
		{
			echo "success";
			
		}
		else
		{
			echo "failed";
		}
		//redirect(base_url().'admin/askprabhu');
		//return $question_askprabhu;
		
	}
	
	
}
class askprabhu_pagination extends Askprabhu_model
{
	public function listpagination()
	{
		if(isset($_POST["page"])){
		$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
		if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
}else{
	$page_number = 1;
}
$position = (($page_number-1) * $item_per_page);

$Order = "ORDER BY Question_id LIMIT ".$position.", ".$item_per_page."";
//Limit our results within a specified range. 
//$results = mysqli_query($connecDB, "SELECT id, name, message FROM paginate ORDER BY id ASC LIMIT $position, $item_per_page");
		$this->db->reconnect();
		//echo 'CALL display_askprabhu_qnlist("'.$Order.'" )';exit;
		$getvalue= $this->db->query('CALL display_askprabhu_qnlist("'.$Order.'" )');
		return $getvalue->result_array();
	}
	
	
	
	
}




