<?php 
class general_report_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
	}
	
	public function general_excel_report()
	{
		extract($_POST);
		
		$fetch_data = array();
		foreach($ddsection as $key => $section_id)
		{
			$select_query = $this->db->query("CALL get_section_by_id('" . $section_id . "')")->row_array();
			$parent_section = $select_query['URLSectionStructure'];
			$split_section_url = explode("/", $parent_section);
			$parent_sectionname = strtolower($split_section_url[0]);
			$from_date = '';
			$to_date = '';
			$CheckOutYear = ''; 
			$CheckInYear = '';
			
			$CurrentYear = date('Y');
			
			if($date_timepicker_start != '')  {
				$check_in_date 	= new DateTime($date_timepicker_start);
				$from_date = $check_in_date->format('Y-m-d');
				$CheckInYear 	=  $check_in_date->format('Y');
			}
			
			if($date_timepicker_end != '')  {
				$check_out_date = new DateTime($date_timepicker_end);
				$to_date = $check_out_date->format('Y-m-d');
				$CheckOutYear 		=  $check_out_date->format('Y');
			}
			
			if($parent_sectionname == "galleries")
				$cntent_type = 3;
			else if($parent_sectionname == "videos")
				$cntent_type = 4;
			else if($parent_sectionname == "audios")
				$cntent_type = 5;
			else
				$cntent_type = 1;
			
			$query = $this->db->query('CALL get_content_on_hit_count("","'.$cntent_type.'" ,"'.$article_status.'", "'.$section_id.'", "'.$from_date.'", "'.$to_date.'", "")')->result_array();	
			
			// to get data from archieve db
			$query_archive = array();
			if($from_date != '' && $to_date != '') 
			{
				$status_string =  '';
				if($CheckInYear <= $CurrentYear) {
					$string =  "";
					$qry_str = "";
					
					if($article_status != "")
						$status_string = 'and status =  "'.$article_status.'" ';
					
					$year_range = range($CheckOutYear, $CheckInYear);	
					
					foreach($year_range as $val => $year)
					{
						switch($cntent_type)
						{
							case 3:
								$TableName = "gallery_".$year;
								$string =  " first_image_path as image_id , ";
								break;	
							case 4:
								$TableName = "video_".$year;
								$string =  " video_image_path as image_id , ";
								break;
							case 5:
								$TableName = "audio_".$year;
								$string =  " audio_image_path as image_id , ";
								break;
							default:
								$TableName = "article_".$year;
								$string = "(CASE WHEN homepageimageid!='' && homepageimageid is NOT NULL THEN homepageimageid WHEN Sectionpageimageid!='' && Sectionpageimageid is NOT NULL THEN Sectionpageimageid ELSE articlepageimageid END) as  image_id , ";
								$qry_str = ' , author_name as AuthorName ';	
								break;
						}
											
						if ($this->archive_db->table_exists($TableName))
						{
							$query_archive[$val] = $this->archive_db->query('select content_id, title, meta_Title as MetaTitle, tag_ids as Tags,  meta_description as MetaDescription, no_indexed as Noindexed, no_follow as Nofollow, '.$string.' canonical_url as Canonicalurl, status, created_on as Createdon, url, section_id as Section_id , section_name,  created_by as Username, parent_section_id as ParentSectionID, parent_section_name , agency_name as Agency_name '.$qry_str.'  from '.$TableName.' WHERE date(publish_start_date) >= "'.$from_date.'" and date(publish_start_date) <= "'.$to_date.'" and section_id =  "'.$section_id.'" '.$status_string.' ')->result_array();
						}
					}
					if(count($query_archive) > 0) 
						$query_archive = call_user_func_array('array_merge', array_map('array_values', $query_archive));
				}
			}
			
			$fetch_data =  array_merge($fetch_data, $query, $query_archive);
		}
		
		header('Content-Encoding: UTF-8');
		header("Content-type: application/csv;charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"general_report_".date('H-i-s d-m-Y').".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
	
		$handle = fopen('php://output', 'w');
		fputcsv($handle, array(
			'Title',
			'URL',
			'Section',
			'Sub section',
			'Status',
			'Agency Name',
			'Byline Name',
			'Meta Title',
			'Meta Description',
			'Meta Keyword',
			'SEO No Index',
			'SEO No Follow',
			'SEO Canonical URL',
			'Created Date',
			'Created By',
			'Image Status(Y/N)',
			'Approved Comments',
			'Pending Comments',
			'Rejected Comments',
			'No of Comments',
			'Total Hits',
		));
		
		foreach($fetch_data as $export_excel) 
		{
			if($export_excel['parent_section_name'] != "") {
				$parent_section = $export_excel['parent_section_name'];
				$section_name = $export_excel['section_name'];
			} else {
 				$parent_section = $export_excel['section_name'];
				$section_name = '';
			}
			$Section_id = $export_excel['Section_id'];
			$content_id = $export_excel['content_id'];
			$Createdon = date("d-m-Y h:i:s",strtotime($export_excel['Createdon']));
			$url = base_url(). $export_excel['url'];
			$status = '';
			$Noindexed = '';
			$Nofollow = '';
			$image_status = '';
			$hits = '';
			
			$get_tags				=  array();
			$select_tags 	= array();
			$tags = $export_excel['Tags'];
			if(isset($tags) && trim($tags) != '')
				$select_tags 	= $this->db->query("CALL get_tags('".$tags."','')")->result_array();
			
			foreach($select_tags as  $tags)
			{
				$get_tags[] = $tags['tag_name'];
			}
			$Tags = implode(" , ", $get_tags);	
			
			if(isset($export_excel['Username']) && $export_excel['Username'] != "")
				$Username = $export_excel['Username'];
			else if(isset($export_excel['created_by']) && $export_excel['created_by'] != "")
			{
				//$user_result = $this->db->query("CALL get_userdetails_by_id('".$export_excel['created_by']."')")->row_array();
				$Username = $export_excel['created_by'];
			}
				
			if(strtolower($parent_section) == "galleries")
				$cntent_type = 3;
			else if(strtolower($parent_section) == "videos")
				$cntent_type = 4;
			else if(strtolower($parent_section) == "audios")
				$cntent_type = 5;
			else
				$cntent_type = 1;
			
			if($export_excel['status'] == 'P')
				$status = 'Published';
			elseif($export_excel['status'] == 'D')
				$status = 'Draft';
			elseif($export_excel['status'] == 'U')
				$status = 'Un Published';
			
			if($export_excel['Noindexed'] == 1)
				$Noindexed = 'Yes';
			else
				$Noindexed = 'No';	
				
			if($export_excel['image_id'] != "")
				$image_status = 'Yes';
			else
				$image_status = 'No';	
				
			if($export_excel['Nofollow'] == 1)
				$Nofollow = 'Yes';
			else
				$Nofollow = 'No';	

			$live_db = $this->load->database('live_db', TRUE);
			$get_article_hit = $live_db->query("CALL get_content_hit_history('".$cntent_type."', '".$Section_id."', '".$content_id."', '', '')")->result_array();
			$live_db->close();
			
			foreach($get_article_hit as $get_hits)
			{
				if($export_excel['content_id'] ==  $get_hits['content_id'])
				{
					$hits =  $get_hits['hits'];
				}
			}
			
			$comments_data = $this->db->query("CALL get_comments_by_content('".$content_id."', '".$cntent_type."')");
			$fetch_comments = $comments_data->result_array();
			
			$total_comments = $comments_data->num_rows();
			
			$approved_comments = array();
			$rejected_comments = array();
			$pending_comments = array();
			
			$cmnt_id = '';
			$i = 1;
			foreach($fetch_comments as  $get_comments)
			{
				if($export_excel['content_id'] ==  $get_comments['Content_Id'] && $get_comments['Status'] == 'A')
				{
					$approved_comments[] =  $get_comments['UpdatedComment'];
				}
				
				if($export_excel['content_id'] ==  $get_comments['Content_Id'] && $get_comments['Status'] == 'R')
				{
					$rejected_comments[] =  $get_comments['UpdatedComment'];
				}
				
				if($export_excel['content_id'] ==  $get_comments['Content_Id'] && $get_comments['Status'] == 'P')
				{
					$pending_comments[] = $get_comments['UpdatedComment'];
				}
				$i++;
			}
			$apprvd_cmnt = implode("\n\n", $approved_comments);
			$rejctd_cmnts = implode("\n\n", $rejected_comments);
			$pending_comments = implode("\n\n", $pending_comments);
			
			$title = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $export_excel['title']); 
			
			$title = trim(strip_tags($title));
			fputcsv($handle, array(
				$title,
				$url,
				$parent_section,
				$section_name,
				$status,
				$export_excel['Agency_name'],
				@$export_excel['AuthorName'],
				$export_excel['MetaTitle'],
				$export_excel['MetaDescription'],
				$Tags,
				$Noindexed,
				$Nofollow,
				$export_excel['Canonicalurl'],
				$Createdon,
				@$Username,
				$image_status,
				$apprvd_cmnt,
				$rejctd_cmnts,
				$pending_comments,
				$total_comments,
				$hits
			));
			
		}
		fclose($handle);
		exit;
	}
	
	
}
