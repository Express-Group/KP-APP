<?php
class article_ageing_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
	}
	
	function article_ageing_datatable()
	{
		$set_object = new article_ageing_datatable;
		return $set_object->article_ageing_datatable();
	}
	
	function article_excel_report($userid, $from, $to, $section)
	{
		$set_object = new article_excel;
		return $set_object->article_excel_report($userid, $from, $to, $section);
	}
	
	public function get_userdetails_by_id($userid)
	{
		$username = $this->db->query("CALL get_userdetails_by_id('".$userid."')")->row_array();
		return $username;
	}
}

class article_ageing_datatable extends article_ageing_model
{
	public function article_ageing_datatable()
	{
		extract($_POST);
		
		//	print_r($_POST); exit;
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
		if($userid != "")
		{
			switch($Field)
			{
				case 0:
					$order_field = 'm.title';
					break;
				case 1:
					$order_field = 'ard.Section_id';
					break;
				case 2:
					$order_field = 'm.Createdon';
					break;
				case 3:
					$order_field = 'DiffDate';
					break;
				default:
					$order_field = 'm.title';
			}
		}
		else
		{
			switch($Field)
			{
				case 0:
					$order_field = 'Username';
					break;
				case 1:
					$order_field = 'status';
					break;
				case 2:
					$order_field = 'before_one_day';
					break;
				case 3:
					$order_field = 'days_less_than_three';
					break;
				case 4:
					$order_field = 'days_less_than_five';
					break;
				case 5:
					$order_field = 'days_before_month';
					break;
				case 6:
					$order_field = 'days_more_than_month';
					break;
				case 7:
					$order_field = 'total';
					break;	
				default:
					$order_field = 'Username';
			}
		}
		
		$CheckOutYear = ''; 
		$CheckInYear = '';
		
		$CurrentYear = date('Y');
			
		if($from_date != '')
		{
			$check_in_date = new DateTime($from_date);
			$from_date     = $check_in_date->format('Y-m-d');
			$CheckInYear 	=  $check_in_date->format('Y');
		}
		
		if($to_date != '')
		{
			$check_out_date = new DateTime($to_date);
			$to_date        = $check_out_date->format('Y-m-d');
			$CheckOutYear 		=  $check_out_date->format('Y');
		}
		
		$Total_rows      = $this->db->query('CALL  article_aging_report ("'.$userid.'", "", "", "", "", "")')->num_rows();
		$recordsFiltered = $this->db->query('CALL  article_aging_report("'.$userid.'", "", "'.$status.'", "'.$section_id.'", "'.$from_date.'", "'.$to_date.'")')->num_rows();
		$article         = $this->db->query('CALL  article_aging_report ("'.$userid.'", " ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.','.$length.'", "'.$status.'", "'.$section_id.'", "'.$from_date.'", "'.$to_date.'")')->result_array();
		
		$article_count =  abs(count($article) - $length);
		
		if(!isset($archieve_total_count))
			$archieve_total_count = 0;
			
		if(!isset($archieve_previous_count))
			$archieve_previous_count = 0;
			
		if($start == 0) 
			$archieve_start = $start;
		elseif($start != 0) {
			$archieve_total_count = $archieve_previous_count + $archieve_total_count;
			$archieve_start = $archieve_total_count;
		}
				
		$archieve_result = array();
		if($from_date != '' && $to_date != '') 
		{
			switch($Field)
			{
				case 0:
					$order_field = 'title';
					break;
				case 1:
					$order_field = 'section_name';
					break;
				case 2:
					$order_field = 'created_on';
					break;
				case 3:
					$order_field = 'DiffDate';
					break;
				default:
					$order_field = 'title';
			}
			
			if($CheckInYear <= $CurrentYear) {
				
				$TableName = "article_".$CheckInYear;

				$section_str = "";

				if($section_id != "")
					$section_str = ' and section_id =  '.$section_id.' ';
				
				$archive_db = $this->load->database('archive_db', TRUE);
										
				if ($archive_db->table_exists($TableName))
				{
					$archiev_total_rows =  $archive_db->query('select content_id, title,  status, created_on as Createdon, section_name,  created_by,  parent_section_name, (DATEDIFF(publish_start_date, created_on)) AS DiffDate  from '.$TableName.' WHERE created_by= "'.$username.'" ')->num_rows();
					
					$Total_rows      += $archiev_total_rows;
					$recordsFiltered_archieve = $archive_db->query('select content_id, title,  status, created_on as Createdon, section_name,  created_by,  parent_section_name, (DATEDIFF(publish_start_date, created_on)) AS DiffDate  from '.$TableName.' WHERE created_by= "'.$username.'" '.$section_str.' ')->num_rows();
					
					$archieve_result = $archive_db->query('select content_id, title,  status, created_on as Createdon, section_name,  created_by,  parent_section_name, (DATEDIFF(publish_start_date, created_on)) AS DiffDate  from '.$TableName.' WHERE created_by= "'.$username.'" and date(publish_start_date) >= "'.$from_date.'" and date(publish_start_date) <= "'.$to_date.'"  '.$section_str.'  ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.','.$length.' ')->result_array();
					
					$recordsFiltered += $recordsFiltered_archieve;
				}
				$archive_db->close();
			}
		}
		
		
		$data['data']            = array();
		
		$Count = 0;
		
		if($userid != "")
		{
			foreach($article as $article)
			{
				$subdata   = array();
				
				$subdata[] = $article['title'].'<input type="hidden" class="archieve_total_count" value="'.count($archieve_result).'"><input type="hidden" class="archieve_previous_count" value="'.$archieve_total_count.'">';
				if($article['status']== 'P')
					$subdata[] = '<td><i title="Published" class="fa fa-check"></i></td>';
				else
					$subdata[] = '<td><i title="Unpublished"  class="fa fa-times"></i></td>';
				$subdata[] = GenerateBreadCrumbBySectionId($article['Section_id']);
				$subdata[] = date("d-m-Y h:i:s",strtotime($article['Createdon']));
				
				if($article['DiffDate'] <= 1 )
					$datediff = '1 day';
				else
					$datediff = $article['DiffDate'].' days';
				
				$subdata[] = $datediff;

				$data['data'][$Count] = $subdata;
				$Count++;
			}
			
			if(count($archieve_result) > 0)
			{
				foreach($archieve_result as $archieve_article)
				{
					$parent_section = '';
					if($archieve_article['parent_section_name'] != "")
						$parent_section = $archieve_article['parent_section_name'].'/';
					$subdata   = array();
					
					$subdata[] = $archieve_article['title'].'<input type="hidden" class="archieve_total_count"  value="'.count($archieve_result).'"><input type="hidden" class="archieve_previous_count" value="'.$archieve_total_count.'">';
					if($archieve_article['status']== 'P')
						$subdata[] = '<td><i title="Published" class="fa fa-check"></i></td>';
					else
						$subdata[] = '<td><i title="Unpublished"  class="fa fa-times"></i></td>';
					$subdata[] = $parent_section.$archieve_article['section_name'];
					$subdata[] = date("d-m-Y h:i:s",strtotime($archieve_article['Createdon']));
					
					if($archieve_article['DiffDate'] <= 1 )
						$datediff = '1 day';
					else
						$datediff = $archieve_article['DiffDate'].' days';
					
					$subdata[] = $datediff;
	
					$data['data'][$Count] = $subdata;
					$Count++;
				}
			}
		}
		else
		{
			foreach($article as $article)
			{
				$subdata = array();
				$subdata[] = '<a href="'.base_url().folder_name.'/article_ageing_report/userdata/'.urlencode(base64_encode($article['User_id'])).'">'.$article['Username'].'</a></div>';		
				if($article['status']==1)
					$subdata[] = '<td><i title="Active" class="fa fa-check"></i></td>';
				else
					$subdata[] = '<td><i title="Inactive" class="fa fa-times"></i></td>';			
				$subdata[] = $article['before_one_day'];
				$subdata[] = $article['days_less_than_three'];
				$subdata[] = $article['days_less_than_five'];
				$subdata[] = $article['days_before_month'];
				$subdata[] = $article['days_more_than_month'];
				//$subdata[] = ($article['days_more_than_month'] == 0 ) ? '-' : $article['days_more_than_month'];
				$subdata[] = $article['total'];
				$data['data'][$Count] = $subdata;
				$Count++;
			}
		}
		
		$data['draw']            = $draw;
		$data["recordsTotal"]    = $Total_rows;
		$data["recordsFiltered"] = $recordsFiltered;
		
		if($recordsFiltered == 0){}
		echo json_encode($data);
		exit;
	}
}

class article_excel extends article_ageing_model
{
	public function article_excel_report($userid, $from_date, $to_date, $section)
	{
		$article_result = array();
		$archieve_result = array();
		$merge_array = array();
		$CheckOutYear = ''; 
		$CheckInYear = '';
		
		$CurrentYear = date('Y');
			
		if($from_date != '')
		{
			$check_in_date = new DateTime($from_date);
			$from_date     = $check_in_date->format('Y-m-d');
			$CheckInYear 	=  $check_in_date->format('Y');
		}
		
		if($to_date != '')
		{
			$check_out_date = new DateTime($to_date);
			$to_date        = $check_out_date->format('Y-m-d');
			$CheckOutYear 		=  $check_out_date->format('Y');
		}
		
		//$query     = $this->db->query('CALL  article_aging_report ("'.$userid.'", " ", "", "", "", "")')->result_array();
		
		$query     = $this->db->query('CALL  article_aging_report("'.$userid.'", "", "", "'.$section.'", "'.$from_date.'", "'.$to_date.'")')->result_array();
		
		
		if($CheckInYear <= $CurrentYear) {
			
			$year_range = range($CheckOutYear, $CheckInYear);			
			
			$section_str = "";
			if($section != "")
				$section_str = ' and section_id =  '.$section.' ';

			$archive_db = $this->load->database('archive_db', TRUE);
			
			foreach($year_range as $val => $year)
			{
				$TableName = "article_".$year;
				
				$user_result = $this->db->query("CALL get_userdetails_by_id('".$userid."')")->row_array();
				$username = $user_result['Username'];
				
				if ($archive_db->table_exists($TableName))
				{
					$merge_array[$val] = $archive_db->query('select content_id, title,  status, created_on as Createdon, section_name, section_id as Section_id, created_by,  parent_section_name, (DATEDIFF(publish_start_date, created_on)) AS DiffDate  from '.$TableName.' WHERE created_by= "'.$username.'" and date(publish_start_date) >= "'.$from_date.'" and date(publish_start_date) <= "'.$to_date.'"  '.$section_str.' ')->result_array();
				}
			}
			
			//print_r($merge_array);
			
			if(count($merge_array) > 0) 
				$archieve_result = call_user_func_array('array_merge', array_map('array_values', $merge_array));
	
			$archive_db->close();
		}
		
		$article_result = array_merge($query, $archieve_result);	
		
		//print_r($article_result); exit;	
		header('Content-Encoding: UTF-8');
		header("Content-type: application/csv;charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"article_ageing_" . date('H-i-s d-m-Y') . ".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$handle = fopen('php://output', 'w');
		
		if($userid == "" && $userid == 0)
		{
			fputcsv($handle, array(
				'Username',
				'Status',
				'1 Day < ',
				'3 Day < ',
				'5 Day < ',
				'30 Day < ',
				'More than 30 Days',
				'Total'
			));
			foreach($query as $export_excel)
			{
				if($export_excel['status'] == 1)
					$status = 'Active';
				else 
					$status = 'In Active';
					
				fputcsv($handle, array(
					$export_excel['Username'],
					$status,
					$export_excel['before_one_day'],
					$export_excel['days_less_than_three'],
					$export_excel['days_less_than_five'],
					$export_excel['days_before_month'],
					$export_excel['days_more_than_month'],
					$export_excel['total']
				));
			}
		} 
		else 
		{
			fputcsv($handle, array(
				'Title',
				'Status',
				'Section Name',
				'Created On',
				'Created By',
				'Ageing'
			));
				
			foreach($article_result as $export_excel)
			{
				$Createdon = date("d-m-Y h:i:s",strtotime($export_excel['Createdon']));
				$section_name =  GenerateBreadCrumbBySectionId($export_excel['Section_id']);
				
				$title = preg_replace_callback("/(&#[0-9]+;)/",function($m){return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $export_excel['title']);
				
				if(isset($export_excel['Username']) && $export_excel['Username'] != "")
					$Username = $export_excel['Username'];
				else if(isset($export_excel['created_by']) && $export_excel['created_by'] != "")
				{
					//$user_result = $this->db->query("CALL get_userdetails_by_id('".$export_excel['created_by']."')")->row_array();
					$Username = $export_excel['created_by'];
				}
				
				if($export_excel['status'] == 'P')
					$status = 'Published';
				elseif($export_excel['status'] == 'U')
					$status = 'Un Published';
					
				if($export_excel['DiffDate'] <= 1 )
					$datediff = '1 day';
				else
					$datediff = $export_excel['DiffDate'].' days';
					
				$title = trim(strip_tags($title));
				fputcsv($handle, array(
					$title,
					$status,
					$section_name,
					$Createdon,
					$Username,
					$datediff
				));
			}
		}
		
		fclose($handle);
		exit;
		
	}
}
