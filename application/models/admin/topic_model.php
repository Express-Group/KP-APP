<?php 
class topic_model extends CI_Model 
{
	
	function addtopicdetails($user_id)
	{
		$set_object=new detailsdpt;
		return $set_object->addtopicdetails($user_id);
	}
	function edittopicdetails($input_dpt_id)
	{
		$set_object=new gettopicdetails;
		return $set_object->edittopicdetails($input_dpt_id);
	}
	function topic_datatable()
	{
		$set_object=new datatable;
		return $set_object->topic_datatable();	
	}
	function topicdetails_delete($input_dpt_id)
	{
		$set_object=new gettopicdetails;
		return $set_object->topicdetails_delete($input_dpt_id);	
		
	}
	function departmnt_check()
	{
		$set_object=new check_dpt;
		return $set_object->departmnt_check();	
		
	}
	
}
class detailsdpt extends topic_model
{
	public function addtopicdetails($user_id)//fn to list datatable details
	{
			$hdn_topic_id=$this->input->post('txthiddenid');
			//$topicname=$this->input->post('txtTopicName');
			
						
			$oldtopicname= htmlspecialchars($this->input->post('txthiddentopic'));
			$oldtopicname = addslashes(str_replace("'", "&#039;", $oldtopicname));
			
			$topicname= htmlspecialchars($this->input->post('txtTopicName'));
			$topicname = addslashes(str_replace("'", "&#039;", $topicname));
		
			$status=$this->input->post('views');
			$createdby="1";
			$modifiedby="1";
			date_default_timezone_set('Asia/Calcutta');
			$createdon=date("Y-m-d H:i:s");
			$modifiedon=date("Y-m-d H:i:s");
			if($hdn_topic_id=="")
			{
				$this->db->trans_begin();
				$column_insert_pro = $this->db->query("CALL column_insert('".$topicname."','".$user_id."','".$user_id."','".$createdon."')"); 
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}
			}
			else
			{
				
				$CI = &get_instance();
				$this->archive_db = $CI->load->database('archive_db', TRUE);
				$this->live_db = $CI->load->database('live_db', TRUE);
				
				$this->db->trans_begin();
				$this->live_db->trans_begin();
				$this->archive_db->trans_begin();
				
				$column_update_pro = $this->db->query("CALL column_update('".$topicname."','".$user_id."','".$modifiedon."','".$hdn_topic_id."')"); 
				
				$this->live_db->query("CALL  update_article_topic('" . trim($oldtopicname) . "', '" . trim($topicname) . "')");
				
				$table_range = range('2009', date('Y'));
						
				foreach($table_range as $table_year) {
					
					$table_name = 'article_'.$table_year;
					$result_array = $this->archive_db->query('SHOW TABLES LIKE "'.$table_name.'%"')->row_array();
					
					if(count($result_array) > 0) {
						$result = array_values($result_array);
						
						if($table_name == $result[0])
						{
							$this->archive_db->query('UPDATE '.$table_name.' SET column_name ="'.$topicname.'" WHERE column_name =  "'.$oldtopicname.'"');
						}
					}
				}
					
				if ($this->db->trans_status() === FALSE || $this->live_db->trans_status === FALSE || $this->archive_db->trans_status === FALSE) {
					$this->db->trans_rollback();
					$this->live_db->trans_rollback();
					$this->archive_db->trans_rollback();
				} else {
					$this->db->trans_commit();
					$this->live_db->trans_commit();
					$this->archive_db->trans_commit();
				}
			}
	}

}
class gettopicdetails extends topic_model
{
	public function edittopicdetails($input_dpt_id)
	{
		$section_edit= $this->db->query("CALL column_editdetails('".$input_dpt_id."')");
		return $section_edit->result_array(); 
	}
	public function topicdetails_delete($input_dpt_id)
	{
		$dpt_check_pro = $this->db->query("CALL checktopicid('".$input_dpt_id."')")->num_rows();
		if($dpt_check_pro > 0) 
		{
			$this->session->set_flashdata('fail_delete', 'Selected Column mapped in other tables cannot be deleted');
			redirect(base_url().'niecpan/topic_manager');
		}
		else
		{
			$this->remove_topic($input_dpt_id); 
			
		}
	}
	public function remove_topic($input_dpt_id)
	{
		$dpt_delete_pro = $this->db->query("CALL column_delete('".$input_dpt_id."')"); 
		if($dpt_delete_pro == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'Column deleted successfully');
			redirect(base_url().'niecpan/topic_manager');
		}
	}
	
}

class datatable extends topic_model
{
	
	public function topic_datatable()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		
		case 0:
		   $order_field = 't1.column_name';
			break;
		case 1:
		   $order_field = 't1.Createdby';
			break;
		default:
        $order_field = 't1.column_id';
		}
		
		
		$Total_rows = $this->db->query('CALL column_datatable("","","","","'.$filterby.'")')->num_rows();
		
		
		
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
		
		$topic_manager =  $this->db->query('CALL column_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->result_array();	

		$recordsFiltered =  $this->db->query('CALL column_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Column');
		foreach($topic_manager as $topic) 
		{
			$subdata = array();
			/*$subdata[] = $rolemaster['User_id'];*/
			$subdata[] = $topic['column_name'];
			$subdata[] = $topic['Username'];
			$subdata[] = date("d-m-Y H:i:s",strtotime($topic['Createdon']));
			
			/*$subdata[] ='<a class="button tick" href="'.base_url().'admin/topic_manager/edit_details/'.$topic['topic_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>
			  <a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_topic('.$topic['topic_id'].')"  id="'.$topic['topic_id'].'"> <i class="fa fa-trash-o"></i> </a>'; */
			  
			  $set_rights = "";
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().'niecpan/topic_manager/edit_details/'.$topic['column_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_topic('.$topic['column_id'].')"  id="'.$topic['column_id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
			  }
			  else 
			  { 
			  	$set_rights.="";
			  }
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
class check_dpt extends topic_model
{
	public function departmnt_check()
	{
		//$topic=$this->input->post('dpt_name');
		
		$topic=htmlspecialchars($this->input->post('dpt_name'));
		$topic = addslashes(str_replace("'", "&#039;", $topic));
		
		$dptid=$this->input->post('dept_id');
		
		$dpt_name = $this->db->query("CALL check_columnname('".trim($topic)."','".$dptid."')"); 
		return $dpt_name->num_rows();
		
	}
	
	
	
}