<?php
class comments_ageing_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	
	function comments_datatable()
	{
		$set_object = new comments_datatable;
		return $set_object->comments_datatable();
	}
	
	function comments_excel($sectionid, $type, $from, $to)
	{
		$set_object = new article_excel;
		return $set_object->comments_excel($sectionid, $type, $from, $to);
	}
	
	public function get_section_by_id($sectionid)
	{
		$select_query = $this->db->query("CALL get_section_by_id('" . $sectionid . "')")->row_array();
		return $select_query;
	}
}

class comments_datatable extends comments_ageing_model
{
	public function comments_datatable()
	{
		extract($_POST);
		
		//	print_r($_POST); exit;
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
		if($sectionid != "")
		{
			switch($Field)
			{
				case 0:
					$order_field = 'm.title';
					break;
				case 1:
					$order_field = 'm.status';
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
					$order_field = 'm.title';
			}
		}
		else
		{
			switch($Field)
			{
				case 0:
					$order_field = 'Sectionname';
					break;
				case 1:
					$order_field = 'parent_sectionname';
					break;
				case 2:
					$order_field = 'status';
					break;
				case 3:
					$order_field = 'before_one_day';
					break;
				case 4:
					$order_field = 'days_less_than_three';
					break;
				case 5:
					$order_field = 'days_less_than_five';
					break;
				case 6:
					$order_field = 'days_before_month';
					break;
				case 7:
					$order_field = 'days_more_than_month';
					break;
				case 8:
					$order_field = 'total';
					break;		
				default:
					$order_field = 'Sectionname';
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
		
		$Total_rows      = $this->db->query('CALL  comments_aging_report ("'.$sectionid.'", "", "", "", "", "'.$content_type.'", "")')->num_rows();
		$recordsFiltered = $this->db->query('CALL  comments_aging_report("'.$sectionid.'", "", "'.$status.'",  "'.$from_date.'", "'.$to_date.'",  "'.$content_type.'", "")')->num_rows();
		$comments         = $this->db->query('CALL  comments_aging_report ("'.$sectionid.'", " ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.','.$length.'", "'.$status.'", "'.$from_date.'", "'.$to_date.'",  "'.$content_type.'", "")')->result_array();
		
		
		$article_count =  abs(count($comments) - $length);
		
		if(!isset($archieve_total_count))
			$archieve_total_count = 0;
			
		if($start == 0) 
			$archieve_start = $start;
		elseif($start != 0) {
			$archieve_total_count = $archieve_previous_count + $archieve_total_count;
			$archieve_start = $archieve_total_count;
		}
		
		$merge_array = array();
		$archeive_comments = array();
		$archeive_content = array();
		$archieve_query = array();
		
		if($from_date != '' && $to_date != '') 
		{
			switch($Field)
			{
				case 0:
					$order_field = 't1.title';
					break;
				default:
					$order_field = 't1.title';
			}
			
			
			if($CheckInYear <= $CurrentYear) {
				
				$archive_db = $this->load->database('archive_db', TRUE);
				
				$status_string =  '';
				if($status != "")
						$status_string = ' and status =  "'.$status.'" ';
						
										
				switch($content_type)
				{
					case 3:
						$TableName = "gallery_".$CheckInYear;
						break;	
					case 4:
						$TableName = "video_".$CheckInYear;
						break;
					case 5:
						$TableName = "audio_".$CheckInYear;
						break;
					default:
						$TableName = "article_".$CheckInYear;
						break;
				}
									
				if ($archive_db->table_exists($TableName))
				{
					$archieve_query = $archive_db->query('select content_id, status, title from '.$TableName.' WHERE date(publish_start_date) >= "'.$from_date.'" and date(publish_start_date) <= "'.$to_date.'" and section_id =  "'.$sectionid.'" '.$status_string.' ')->result_array();
				}
				if(count($archieve_query) > 0)
				{
					foreach($archieve_query as $archieve_data)
					{
						$archeive_content[] = $archieve_data['content_id'];
					}
					$get_contentId = implode(",", $archeive_content);
					
					$archeive_comments = $this->db->query('CALL comments_aging_report ("","ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.','.$length.'","","","","'.$content_type.'", "'.$get_contentId.'")')->result_array();
					
					$Total_rows += count($archeive_comments);
					$recordsFiltered += count($archeive_comments);
				}
				
				if(count($archeive_comments) > 0)
				{
					foreach($archeive_comments as  $comments_content)
					{
						foreach($archieve_query as  $archieve_data)
						{
							if($comments_content['Content_Id'] == $archieve_data['content_id'])
							{
								$merge_array[] = array_merge($comments_content, $archieve_data);
							}
						}
					}
				}
			}
			$archive_db->close();
		}
		
		$data['data'] = array();
		
		$Count = 0;
		
		if($sectionid != "")
		{
			foreach($comments as $comments)
			{
				$subdata   = array();
				$subdata[] = $comments['title'].'<input type="hidden" class="archieve_total_count"  value="'.count($merge_array).'"><input type="hidden" class="archieve_previous_count" value="'.$archieve_total_count.'">';
				if($comments['status']== 'P')
					$subdata[] = '<td><i title="Published" class="fa fa-check"></i></td>';
				else
					$subdata[] = '<td><i title="Unpublished"  class="fa fa-times"></i></td>';
				$subdata[] = $comments['before_one_day'];
				$subdata[] = $comments['days_less_than_three'];
				$subdata[] = $comments['days_less_than_five'];
				$subdata[] = $comments['days_before_month'];
				$subdata[] = $comments['days_more_than_month'];
				//$subdata[] = ($comments['days_more_than_month'] == 0 ) ? '-' : $comments['days_more_than_month'];
				$subdata[] = $comments['total'];

				$data['data'][$Count] = $subdata;
				$Count++;
			}
			
			if(count($merge_array) > 0)
			{
				foreach($merge_array as $archive_comments)
				{
					$subdata   = array();
					$subdata[] = $archive_comments['title'].'<input type="hidden" class="archieve_total_count"  value="'.count($merge_array).'"><input type="hidden" class="archieve_previous_count" value="'.$archieve_total_count.'">';
					if($archive_comments['status']== 'P')
						$subdata[] = '<td><i title="Published" class="fa fa-check"></i></td>';
					else
						$subdata[] = '<td><i title="Unpublished"  class="fa fa-times"></i></td>';
					$subdata[] = $archive_comments['before_one_day'];
					$subdata[] = $archive_comments['days_less_than_three'];
					$subdata[] = $archive_comments['days_less_than_five'];
					$subdata[] = $archive_comments['days_before_month'];
					$subdata[] = $archive_comments['days_more_than_month'];
					//$subdata[] = ($comments['days_more_than_month'] == 0 ) ? '-' : $comments['days_more_than_month'];
					$subdata[] = $archive_comments['total'];
	
					$data['data'][$Count] = $subdata;
					$Count++;
				}
			}
		}
		else
		{
			foreach($comments as $comments)
			{
				$subdata = array();
				
				//$subdata[] = '<a href="'.base_url().folder_name.'/comments_ageing_report/userdata/'.urlencode(base64_encode($comments['section_id'])).'">'.$comments['Sectionname'].'</a></div>';		
				
				$subdata[] = '<a href="'.base_url().folder_name.'/comments_ageing_report/userdata?secid='.$comments['section_id'].'&type='.$comments['content_type_id'].'">'.$comments['Sectionname'].'</a></div>';	
				
				$subdata[] =$comments['parent_sectionname'];
				if($comments['status']==1)
					$subdata[] = '<td><i title="Active" class="fa fa-check"></i></td>';
				else
					$subdata[] = '<td><i title="Inactive" class="fa fa-times"></i></td>';		
				$subdata[] = $comments['before_one_day'];
				$subdata[] = $comments['days_less_than_three'];
				$subdata[] = $comments['days_less_than_five'];
				$subdata[] = $comments['days_before_month'];
				$subdata[] = $comments['days_more_than_month'];
				//$subdata[] = ($comments['days_more_than_month'] == 0 ) ? '-' : $comments['days_more_than_month'];
				$subdata[] = $comments['total'];
				$data['data'][$Count] = $subdata;
				$Count++;
			}
		}
		
		if($recordsFiltered == 0){}
		
		$data['draw']            = $draw;
		$data["recordsTotal"]    = $Total_rows;
		$data["recordsFiltered"] = $recordsFiltered;
		
		echo json_encode($data);
		exit;
	}
}

class article_excel extends comments_ageing_model
{
	public function comments_excel($sectionid, $type, $from_date, $to_date)
	{
		$query     = $this->db->query('CALL  comments_aging_report ("'.$sectionid.'", "", "", "", "",  "'.$type.'", "")')->result_array();
		
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
		
		$archieve_result = array();	
		$archeive_comments = array();
		$archieve_query = array();
		$merge_array = array();
		
		if($from_date != '' && $to_date != '') 
		{
			if($CheckInYear <= $CurrentYear) {
				
				$archive_db = $this->load->database('archive_db', TRUE);
				$year_range = range($CheckOutYear, $CheckInYear);	
				foreach($year_range as $val => $year)
				{
					switch($type)
					{
						case 3:
							$TableName = "gallery_".$year;
							break;	
						case 4:
							$TableName = "video_".$year;
							break;
						case 5:
							$TableName = "audio_".$year;
							break;
						default:
							$TableName = "article_".$year;
							break;
					}
										
					if ($archive_db->table_exists($TableName))
					{
						$archieve_query = $archive_db->query('select content_id, status, title, section_id as Section_id, created_by from '.$TableName.' WHERE date(publish_start_date) >= "'.$from_date.'" and date(publish_start_date) <= "'.$to_date.'" and section_id =  "'.$sectionid.'" ')->result_array();
					}
					if(count($archieve_query) > 0)
					{
						$archeive_content = array();
						foreach($archieve_query as $archieve_data)
						{
							$archeive_content[] = $archieve_data['content_id'];
						}
						$get_contentId = implode(",", $archeive_content);
						
						$archeive_comments = $this->db->query('CALL comments_aging_report ("","","","","","'.$type.'", "'.$get_contentId.'")')->result_array();
					}
					
					if(count($archeive_comments) > 0)
					{
						foreach($archeive_comments as  $comments_content)
						{
							foreach($archieve_query as  $archieve_data)
							{
								if($comments_content['Content_Id'] == $archieve_data['content_id'])
								{
									$merge_array[] = array_merge($comments_content, $archieve_data);
								}
							}
						}
					}
				}
			}
			//$archieve_result = call_user_func_array('array_merge', array_map('array_values', $merge_array));
			$archive_db->close();
		}
		$article_result = array_merge($query, $merge_array);
		
		//print_r($article_result); exit;
		header('Content-Encoding: UTF-8');
		header("Content-type: application/csv;charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"comments_ageing_" . date('H-i-s d-m-Y') . ".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$handle = fopen('php://output', 'w');
		
		if($sectionid == "" && $sectionid == 0)
		{
			fputcsv($handle, array(
				'Section Name',
				'Parent Section Name',
				'1 Day < ',
				'3 Day < ',
				'5 Day < ',
				'30 Day < ',
				'More than 30 Days',
				'Total'
			));
			foreach($query as $export_excel)
			{
				fputcsv($handle, array(
					$export_excel['Sectionname'],
					$export_excel['parent_sectionname'],
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
				'Section Name',
				'Created By',
				'1 Day < ',
				'3 Day < ',
				'5 Day < ',
				'30 Day < ',
				'More than 30 Days',
				'Total'
			));
				
			foreach($article_result as $export_excel)
			{
				if(isset($export_excel['Username']) && $export_excel['Username'] != "")
					$Username = $export_excel['Username'];
				else if(isset($export_excel['created_by']) && $export_excel['created_by'] != "")
				{
					//$user_result = $this->db->query("CALL get_userdetails_by_id('".$export_excel['created_by']."')")->row_array();
					$Username = $export_excel['created_by'];
				}
				
				$title = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $export_excel['title']); 

				$title = strip_tags($title);
				$sectionname = GenerateBreadCrumbBySectionId($export_excel['Section_id']);
				fputcsv($handle, array(
					$title,
					$sectionname,
					$Username,
					$export_excel['before_one_day'],
					$export_excel['days_less_than_three'],
					$export_excel['days_less_than_five'],
					$export_excel['days_before_month'],
					$export_excel['days_more_than_month'],
					$export_excel['total']
				));
			}
		}
		
		fclose($handle);
		exit;
		
	}
}
