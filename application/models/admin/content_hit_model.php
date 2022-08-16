<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class content_hit_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$CI = &get_instance();
		$this->live_db = $this->load->database('live_db', TRUE);
		
	}
	public function datatable_content_hit()
	{
		$set_object=new datatables;
		$set_object->datatable_content_hit();
	}
	
	public function fetch_email_address()
	{
		$set_object=new datatables;
		$set_object->fetch_email_address();
	}
	
}

class datatables  extends content_hit_model
{
	/*public function fetch_email_address()
	{
		extract($_GET);
		$email_address = $this->live_db->query('CALL get_shared_email_details("'.$type.'", "'.$id.'")')->result_array();

	}*/
	
	
	public function datatable_content_hit()
	{
		extract($_POST);
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
		if($content_type == "" && $content_id == '')
		{
			switch ($Field) 
			{
				case 0:
					$order_field = 't1.title';
					break;
				case 1:
					$order_field = 't1.content_type';
					break;
				case 2:
					$order_field = 't1.hits';
					break;
				case 3:
					$order_field = 't1.emailed';
					break;
				case 4:
					$order_field = 't1.created_on';
					break;
				default:
					$order_field = 't1.content_id';
			}
			
			$Total_rows = $this->live_db->query('CALL content_hit_datatable("","","","")')->num_rows();
	
			if($from_date != '')  
			{
				$check_in_date 	= new DateTime($from_date);
				$from_date = $check_in_date->format('Y-m-d');
			}
			if($to_date != '')
			{
				$check_out_date = new DateTime($to_date);
				$to_date = $check_out_date->format('Y-m-d');
			}
			$searchtxt =  addslashes($searchtxt);
			
			$content_hit_manager =  $this->live_db->query('CALL content_hit_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'")')->result_array();	
			
			
			$recordsFiltered =  $this->live_db->query('CALL content_hit_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'")')->num_rows();
			$data['draw'] = $draw;
			$data["recordsTotal"] = $Total_rows;
			$data["recordsFiltered"] = $recordsFiltered ;
			$data['data'] = array();
			$Count = 0;
			
			foreach($content_hit_manager as $content_hit)
			{
				switch($content_hit['content_type'])
				{
					case '1':
						$content_type = 'Article';
						break;
					case '3':
						$content_type = 'Gallery';
						break;
					case '4':
						$content_type = 'Video';
						break;
					default:
						$content_type = 'Article';
						break;
				}
				$subdata = array();
				$subdata[]= $content_hit['title'];
				
				$subdata[] = $content_type;
				$subdata[] = $content_hit['hits']; 
				
				if($content_hit['emailed'] != "" && $content_hit['emailed'] != 0)
					$email_count = '<a  href="'.base_url().folder_name.'/content_hit_manager/fetch_email_details?id='.$content_hit['content_id'].'&type='.$content_hit['content_type'].'" target="_blank" ">'.$content_hit['emailed'].'</a>'; 
				else
					$email_count = $content_hit['emailed']; 
				
				$subdata[] = $email_count;
					
				$subdata[] = date("d-m-Y h:i:s",strtotime($content_hit['created_on']));
				
				$data['data'][$Count] = $subdata;
				$Count++;
			 }
		}
		elseif($content_type != "" && $content_id != '')
		{
			switch ($Field) 
			{
				case 0:
					$order_field = 'name';
					break;
				case 1:
					$order_field = 'from_email';
					break;
				case 2:
					$order_field = 'to_email';
					break;
				default:
					$order_field = 'name';
			}
			
			$Total_rows = $this->live_db->query('CALL get_shared_email_details("'.$content_type.'","'.$content_id.'","")')->num_rows();
			
			$content_email_manager =  $this->live_db->query('CALL get_shared_email_details("'.$content_type.'","'.$content_id.'", " ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'")')->result_array();	
						

			$recordsFiltered =  $this->live_db->query('CALL get_shared_email_details("'.$content_type.'","'.$content_id.'","")')->num_rows();

			$data['draw'] = $draw;
			$data["recordsTotal"] = $Total_rows;
			$data["recordsFiltered"] = $recordsFiltered ;
			$data['data'] = array();
			$Count = 0;
			
			foreach($content_email_manager as $content_email)
			{
				$subdata = array();
				$subdata[]= $content_email['name'];
				$subdata[] = $content_email['from_email']; 
				$subdata[] = $content_email['to_email']; 
				$data['data'][$Count] = $subdata;
				$Count++;
			 }
		
		}
		
		if($recordsFiltered == 0) 
		{
		}
		echo json_encode($data);
		exit;
	  }
	
}
?>