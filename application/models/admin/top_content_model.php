<?php 
class top_content_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	
	function top_content_datatable()
	{
		$set_object=new top_content_datatable;
		return $set_object->top_content_datatable();	
	}
	
	function top_content_excel_report($cntent_type, $status, $from, $to)
	{
		$set_object=new top_content_excel;
		return $set_object->top_content_excel_report($cntent_type, $status, $from, $to);	
	}
	
	public function get_content_hit_history($cntent_type, $from_date, $to_date)
	{
		$live_db = $this->load->database('live_db', TRUE);
		$get_article_hit = $live_db->query("CALL get_content_hit_history('".$cntent_type."', '', '', '".$from_date."', '".$to_date."')")->result_array();
		$live_db->close();
		//$get_article_hit = $this->live_db->query("CALL get_content_hit_history('".$cntent_type."')")->result_array();
		return $get_article_hit;
	}
}

class top_content_datatable extends top_content_model
{
	public function top_content_datatable()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		
		case 0:
		   $order_field = 'title';
			break;
		case 1:
		   $order_field = 'status';
			break;
		case 2:
		   $order_field = 'parent_section_name';
			break;
		case 3:
		   $order_field = 'section_name';
			break;
		case 4:
		   $order_field = 'Createdon';
			break;
		case 5:
		   $order_field = 'Username';
			break;
   		default:
       		$order_field = 'content_id';
		}
		
		$CheckOutYear = ''; 
		$CheckInYear = '';
		
		$CurrentYear = date('Y');
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		$CheckInYear 	=  $check_in_date->format('Y');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		$CheckOutYear 		=  $check_out_date->format('Y');
		}
		
		$get_article_hit = $this->get_content_hit_history($cntent_type, $from_date, $to_date);
		
		$hit_content_id = array();
		foreach($get_article_hit as $content_val)
		{
			$hit_content_id[] = $content_val['content_id'];
		}
		$content_id = implode(',', $hit_content_id);
		
		$top_content_manager = '';
		
		$Total_rows = $this->db->query('CALL get_content_on_hit_count("'.$content_id.'","'.$cntent_type.'" ,"'.$status.'", "", "", "", "")')->num_rows();
		
		//$recordsFiltered =  $this->db->query('CALL get_content_on_hit_count("'.$content_id.'","'.$cntent_type.'" ,"'.$status.'", "'.$section_id.'", "'.$from_date.'", "'.$to_date.'", " ")')->num_rows();
		
		$recordsFiltered =  $this->db->query('CALL get_content_on_hit_count("'.$content_id.'","'.$cntent_type.'" ,"'.$status.'", "'.$section_id.'", "", "", " ")')->num_rows();
		
		$archieve_result = array();
		
		if(count($hit_content_id) > 0)	
		{	
			$top_content_manager = $this->db->query('CALL get_content_on_hit_count("'.$content_id.'","'.$cntent_type.'" ,"'.$status.'", "'.$section_id.'", "", "", " ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'")')->result_array();	
			
			$article_count =  abs(count($top_content_manager) - $length);
			
			if(!isset($archieve_total_count))
				$archieve_total_count = 0;
				
			if($start == 0) 
				$archieve_start = $start;
			elseif($start != 0) {
				$archieve_total_count = $archieve_previous_count + $archieve_total_count;
				$archieve_start = $archieve_total_count;
			}
				
			if($from_date != '' && $to_date != '') 
			{
				switch($Field)
				{
					case 0:
						$order_field = 'title';
						break;
					
					case 1:
					   $order_field = 'status';
						break;
					case 2:
					   $order_field = 'parent_section_name';
						break;
					case 3:
					   $order_field = 'section_name';
						break;
					case 4:
					   $order_field = 'created_on';
						break;
					case 5:
					   $order_field = 'created_by';
						break;
					default:
						$order_field = 'title';
						break;
				}
				
				if($CheckInYear <= $CurrentYear) {
					
					switch($cntent_type)
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
	
					$section_str = "";
					$status_string =  '';
					if($status != "")
						$status_string = ' and status =  "'.$status.'" ';
	
					if($section_id != "")
						$section_str = ' and section_id =  '.$section_id.' ';
					
					$archive_db = $this->load->database('archive_db', TRUE);
											
					if ($archive_db->table_exists($TableName))
					{
						$archiev_total_rows =  $archive_db->query('select content_id, publish_start_date as published_on, title,  status, created_on as Createdon, section_name,  created_by,  parent_section_name from '.$TableName.' WHERE content_id IN("'.$content_id.'") ')->num_rows();
						
						$Total_rows      += $archiev_total_rows;
						$recordsFiltered_archieve = $archive_db->query('select content_id, publish_start_date as published_on, title,  status, created_on as Createdon, section_name,  created_by,  parent_section_name from '.$TableName.' WHERE content_id IN("'.$content_id.'") ')->num_rows();
						
						$archieve_result = $archive_db->query('select content_id, publish_start_date as published_on,  title,  status, created_on as Createdon, section_name,  created_by,  parent_section_name from '.$TableName.' WHERE content_id IN("'.$content_id.'")  '.$section_str.' '.$status_string.'  ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.','.$length.' ')->result_array();
						
						$recordsFiltered += $recordsFiltered_archieve;
					}
					$archive_db->close();
				}
			}
			
		}
		
		$data['data'] = array();
		
		$Count = 0;
		
		$disp_cntn_type = '';
		switch($cntent_type)
		{
			case 1:
				$disp_cntn_type = 'Article';
				break;
			case 3:
				$disp_cntn_type = 'Gallery';
				break;
			case 4:
				$disp_cntn_type = 'Video';
				break;
			case 5:
				$disp_cntn_type = 'Audio';
				break;
		}
		$hits = '-';
		
		if(count($hit_content_id)>0)
		{
			foreach($top_content_manager as $key=> $top_content) 
			{
				$subdata = array();
				
				$subdata[] = strip_tags($top_content['title']).'<input type="hidden" class="archieve_total_count"  value="'.count($archieve_result).'"><input type="hidden" class="archieve_previous_count" value="'.$archieve_total_count.'"><input type="hidden" name="contentID[]" id="contentID" value="'.$top_content['content_id'].'">';
				if($top_content['parent_section_name'] != "") {
					$parent_section = $top_content['parent_section_name'];
					$section_name = $top_content['section_name'];
				} else {
					$parent_section = $top_content['section_name'];
					$section_name = '-';
				}
				
				if($top_content['status']== 'P')
					$subdata[] = '<td><i title="Published" class="fa fa-check"></i></td>';
				else
					$subdata[] = '<td><i title="Unpublished"  class="fa fa-times"></i></td>';
							
				$subdata[] = $parent_section;
				$subdata[] = $section_name;
				
				$subdata[] = date("d-m-Y h:i:s",strtotime($top_content['published_on']));
				$subdata[] = $top_content['Username'];
				$subdata[] = $disp_cntn_type;
				
				foreach($get_article_hit as $get_hits) 
				{
					if($top_content['content_id'] ==  $get_hits['content_id'])
					{
						$hits =  $get_hits['hits'];
					}
				}
				$subdata[] =  $hits;
				$data['data'][$Count] = $subdata;
				$Count++;
			}
		}
		
		if(count($archieve_result) > 0)
		{
			foreach($archieve_result as $archieve_article)
			{
				$subdata   = array();
				
				$subdata[] = $archieve_article['title'].'<input type="hidden" class="archieve_total_count"  value="'.count($archieve_result).'"><input type="hidden" class="archieve_previous_count" value="'.$archieve_total_count.'">';
				if($archieve_article['status']== 'P')
					$subdata[] = '<td><i title="Published" class="fa fa-check"></i></td>';
				else
					$subdata[] = '<td><i title="Unpublished"  class="fa fa-times"></i></td>';
				
				$subdata[] = $archieve_article['parent_section_name'];
				$subdata[] = $archieve_article['section_name'];
				$subdata[] = date("d-m-Y h:i:s",strtotime($archieve_article['published_on']));
				$subdata[] = $archieve_article['created_by'];
				$subdata[] = $disp_cntn_type;
				
				foreach($get_article_hit as $get_hits) 
				{
					if($archieve_article['content_id'] ==  $get_hits['content_id'])
					{
						$hits =  $get_hits['hits'];
					}
				}
				$subdata[] =  $hits;
				
				$data['data'][$Count] = $subdata;
				$Count++;
			}
		}
				
		if($recordsFiltered == 0) {}
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		echo json_encode($data);
		exit;

	}
	
}

class top_content_excel extends top_content_model
{
	public function top_content_excel_report($cntent_type, $status, $from_date, $to_date)
	{
		$article_result = array();
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$get_article_hit = $this->get_content_hit_history($cntent_type, $from_date, $to_date);
		
		$CheckOutYear = ''; 
		$CheckInYear = '';
		
		$CurrentYear = date('Y');
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		$CheckInYear 	=  $check_in_date->format('Y');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		$CheckOutYear 		=  $check_out_date->format('Y');
		}
		
		$hit_content_id = array();
		foreach($get_article_hit as $content_val)
		{
			$hit_content_id[] = $content_val['content_id'];
		}
		$content_id = implode(',', $hit_content_id);
		
		
		$query = $this->db->query('CALL get_content_on_hit_count("'.$content_id.'","'.$cntent_type.'" ,"'.$status.'", "", "", "", " ")')->result_array();
		
		
		$archieve_result = array();
		if($from_date != '' && $to_date != '') 
		{
			if($CheckInYear <= $CurrentYear) 
			{
				$year_range = range($CheckOutYear, $CheckInYear);	
				$archive_db = $this->load->database('archive_db', TRUE);
				foreach($year_range as $val => $year)
				{
					switch($cntent_type)
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
	
					$status_string =  '';
					if($status != "")
						$status_string = ' and status =  "'.$status.'" ';
										
					if ($archive_db->table_exists($TableName))
					{
						$archieve_result[$val] = $archive_db->query('select content_id, title, publish_start_date as published_on,  status, created_on as Createdon, section_name,  created_by as Username,  parent_section_name from '.$TableName.' WHERE content_id IN("'.$content_id.'") '.$status_string.' ')->result_array();
						//echo $archive_db->last_query();
					}
				}
				if(count($archieve_result) > 0) 
					$archieve_result = call_user_func_array('array_merge', array_map('array_values', $archieve_result));
				
				$archive_db->close();
			}
		}
		$article_result = array_merge($article_result, $query, $archieve_result);	
		
		$disp_cntn_type = '';
		switch($cntent_type)
		{
			case 1:
				$disp_cntn_type = 'Article';
				break;
			case 3:
				$disp_cntn_type = 'Gallery';
				break;
			case 4:
				$disp_cntn_type = 'Video';
				break;
			case 5:
				$disp_cntn_type = 'Audio';
				break;
		}
		
		header('Content-Encoding: UTF-8');
		header("Content-type: application/csv;charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"top_content_".date('H-i-s d-m-Y').".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
	
		$handle = fopen('php://output', 'w');
		fputcsv($handle, array(
			'Title',
			'Section',
			'Sub section',
			'Published On',
			'Created By',
			'Type',
			'total Hits',
		));
		
		foreach ($article_result as $export_excel) {
			if($export_excel['parent_section_name'] != "") {
				$parent_section = $export_excel['parent_section_name'];
				$section_name = $export_excel['section_name'];
			} else {
 				$parent_section = $export_excel['section_name'];
				$section_name = '-';
			}
			
			$hits = '';
			foreach($get_article_hit as $get_hits) 
			{
				if($export_excel['content_id'] ==  $get_hits['content_id'])
				{
					$hits =  $get_hits['hits'];
				}
			}
			$Createdon = date("d-m-Y h:i:s",strtotime($export_excel['published_on']));
			
			$title = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $export_excel['title']); 

			$title = strip_tags($title);
			fputcsv($handle, array(
				$title,
				$parent_section,
				$section_name,
				$Createdon,
				$export_excel['Username'],
				$disp_cntn_type,
				$hits
			));
		}
		
		fclose($handle);
		exit;
		
	}
}

