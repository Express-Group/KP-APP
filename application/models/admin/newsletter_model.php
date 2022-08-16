<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class newsletter_model extends CI_Model
{
	public function datatable_newsletter()
	{
		$set_object=new datatables;
		$set_object->datatable_newsletter();
	}
	public function remove_email($email_id)
	{
		$set_object=new datatables;
		$set_object->remove_email($email_id);
	}
	public function newsletter_email_report()
	{
		$set_object=new datatables;
		$set_object->newsletter_email_report();
	}
}

class datatables  extends newsletter_model//datatable search
{
	public function datatable_newsletter()
	{
		extract($_POST);
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
		switch ($Field) 
		{
			
			case 1:
				$order_field = 't1.email';
				break;
			case 2:
				$order_field = 't1.frontenduserIP';
				break;
			case 3:
				$order_field = 't1.Createdon';
				break;
    		default:
        		$order_field = 't1.id';
		}
		
		$Total_rows = $this->db->query('CALL newsletter_datatable("","","","","'.$filterby.'")')->num_rows();
		
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
		
		$newsletter_manager =  $this->db->query('CALL newsletter_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->result_array();	
		
		
		$recordsFiltered =  $this->db->query('CALL newsletter_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		$data['Menu_id'] = get_menu_details_by_menu_name("News letter");
		
		foreach($newsletter_manager as $newsletter)
		{
			$subdata = array();
			$subdata[]= '<input type="checkbox" name="chk_value" id="chk_value" class="select_check"  value="'.$newsletter['id'].'">';
			
			$subdata[] = $newsletter['email'];
			$subdata[] = $newsletter['frontenduserIP']; 
			$subdata[] = date("d-m-Y h:i:s",strtotime($newsletter['Createdon']));
			
			  $set_rights = "";
			
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button cross" href="#" data-toggle="tooltip" title="Delete" onclick="delete_newsletter('.$newsletter['id'].')"  newsletter_id = '.$newsletter['id'].'   id="delete_newsletter"><i id="status'.$newsletter['id'].'" class="fa fa-trash-o"></i></a>'; 
			  }
			  else 
			  { 
			  	$set_rights.="";
			  }
   			 $subdata[] = $set_rights;
			  
			  
			  
			  
	   
			$data['data'][$Count] = $subdata;
			$Count++;
		 }
		
			if($recordsFiltered == 0) 
			{
			}
		echo json_encode($data);
		exit;
	  }
	  public function remove_email($input_email_id)
		{
			$delete_email = $this->db->query("CALL delete_newsletter_email('".$input_email_id."')"); 
			//echo $this->db->last_query();exit;
			if($delete_email == TRUE)
			{
				$this->session->set_flashdata('success_delete', 'Email details deleted successfully');
				redirect(base_url().'niecpan/newsletter_manager');
			}else
			{
				$this->session->set_flashdata('fail_delete', 'Deleting failed. please try again.');
				redirect(base_url().'niecpan/newsletter_manager');
			}
		}
		public function newsletter_email_report()
		{
			extract($_GET);
		$ids = $ids;
		$subscribed_emails = $this->db->query("CALL subscribed_emails('".$ids."')")->result_array();	
		//print_r($subscribed_emails);exit;
		
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"Newsletter_subscribed_".date('H-i-s d-m-Y').".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
	
		$handle = fopen('php://output', 'w');
		fputcsv($handle, array(
			'Email',
			'User Ip',
			'Created On',
		));
		
		foreach ($subscribed_emails as $export_excel) {
			
			$email    = $export_excel['email'];
			$userip   = $export_excel['frontenduserIP'];
			$Createdon = date("d-m-Y h:i:s",strtotime($export_excel['Createdon']));
			
			fputcsv($handle, array(
				$email,
				$userip,
				$Createdon,
			));
		}
		fclose($handle);
		exit;
		}
	  
}
?>