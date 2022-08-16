<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class readwhere_controller extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model("admin/widget_model");
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db', TRUE);
		$this->load->library("memcached_library");
	}
	
	public function index(){
		$sectionId = $this->uri->segment(2);
		$sectionDetails  =  $this->widget_model->get_sectionDetails($sectionId, "live");
		$parentSectionName ='';
		$parentSectionDetails=[];
		if(count($sectionDetails) > 0){
			if($sectionDetails['ParentSectionID']!=''&& $sectionDetails['ParentSectionID']!=0 ){
				$parentSectionDetails = $this->widget_model->get_sectionDetails($sectionDetails['ParentSectionID'], "live");
				$parentSectionName = strtolower($parentSectionDetails['URLSectionName']);
			}
			$sectionName = strtolower($sectionDetails['URLSectionName']);
			switch ($sectionName){
				case ($sectionName == "photogallery" || $sectionName == "photogallery" || $parentSectionName=="photogallery" ||  $parentSectionName=="photogallery"):
					$contentType = 3;
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.first_image_path, a.first_image_title, a.first_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM gallery AS a LEFT JOIN gallery_section_mapping AS b ON a.content_id=b.content_id WHERE b.section_id IN (SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = ".$sectionId." OR Section_id = ".$sectionId.") AND a.status='P' AND a.publish_start_date < NOW() GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 500"; 
					break;
				case ($sectionName == "videos" || $parentSectionName=="videos"):
					$contentType = 4;
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.video_script, a.video_image_path, a.video_image_title, a.video_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM video AS a LEFT JOIN video_section_mapping AS b ON a.content_id=b.content_id WHERE b.section_id IN (SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = ".$sectionId." OR Section_id = ".$sectionId.") AND a.status='P' GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 500"; 
					break;
				default:
					$contentType = 1;
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.article_page_content_html, a.article_page_image_path, a.article_page_image_title, a.article_page_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM article AS a LEFT JOIN article_section_mapping AS b ON a.content_id=b.content_id WHERE b.section_id IN (SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = ".$sectionId." OR Section_id = ".$sectionId.") AND a.status='P' GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 500";
			}
			if(!$this->memcached_library->get($query) && $this->memcached_library->get($query) == ''){
				$data['content'] = $this->live_db->query($query)->result_array();
				$this->memcached_library->add($query,$data['content']);
			}else{
				$data['content']  = $this->memcached_library->get($query);
			}
			$data['sectionDetails'] = $sectionDetails;
			$data['parentSectionDetails'] = $parentSectionDetails;
			$data['contentType'] = $contentType;
			$data['baseUrl'] = base_url();
			$this->load->view('admin/readwhere_view',$data);
			
		}else{
			show_404();
		}
	}
	
	public function app(){
		$sectionId = $this->uri->segment(2);
		$sectionDetails  =  $this->widget_model->get_sectionDetails($sectionId, "live");
		$parentSectionName ='';
		$parentSectionDetails=[];
		if(count($sectionDetails) > 0){
			if($sectionDetails['ParentSectionID']!=''&& $sectionDetails['ParentSectionID']!=0 ){
				$parentSectionDetails = $this->widget_model->get_sectionDetails($sectionDetails['ParentSectionID'], "live");
				$parentSectionName = strtolower($parentSectionDetails['URLSectionName']);
			}
			$sectionName = strtolower($sectionDetails['URLSectionName']);
			switch ($sectionName){
				case ($sectionName == "photogallery" || $sectionName == "photogallery" || $parentSectionName=="photogallery" ||  $parentSectionName=="photogallery"):
					$contentType = 3;
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.first_image_path, a.first_image_title, a.first_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM gallery AS a LEFT JOIN gallery_section_mapping AS b ON a.content_id=b.content_id WHERE b.section_id IN (SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = ".$sectionId." OR Section_id = ".$sectionId.") AND a.status='P' AND a.publish_start_date < NOW() GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 100"; 
					break;
				case ($sectionName == "videos" || $parentSectionName=="videos"):
					$contentType = 4;
					$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.video_script, a.video_image_path, a.video_image_title, a.video_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM video AS a LEFT JOIN video_section_mapping AS b ON a.content_id=b.content_id WHERE b.section_id IN (SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = ".$sectionId." OR Section_id = ".$sectionId.") AND a.status='P' GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 100"; 
					break;
				default:
					$contentType = 1;
					if($sectionId==158){
						$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.article_page_content_html, a.article_page_image_path, a.article_page_image_title, a.article_page_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM article AS a INNER JOIN article_section_mapping AS b ON a.content_id=b.content_id WHERE b.section_id='".$sectionId."' GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 15";
					}else{
						$query = "SELECT a.content_id, a.section_id, a.section_name, a.title, a.url, a.summary_html, a.article_page_content_html, a.article_page_image_path, a.article_page_image_title, a.article_page_image_alt, a.publish_start_date, a.last_updated_on, a.agency_name, a.author_name, a.tags FROM article AS a LEFT JOIN article_section_mapping AS b ON a.content_id=b.content_id WHERE b.section_id IN (SELECT Section_id FROM sectionmaster WHERE IF(ParentSectionID !='0', ParentSectionID, Section_id) = ".$sectionId." OR Section_id = ".$sectionId.") AND a.status='P' GROUP BY a.content_id ORDER BY a.publish_start_date DESC LIMIT 100";
					}
					
			}
			if(!$this->memcached_library->get($query) && $this->memcached_library->get($query) == ''){
				$data['content'] = $this->live_db->query($query)->result_array();
				$this->memcached_library->add($query,$data['content']);
			}else{
				$data['content']  = $this->memcached_library->get($query);
			}
			$data['sectionDetails'] = $sectionDetails;
			$data['parentSectionDetails'] = $parentSectionDetails;
			$data['contentType'] = $contentType;
			$data['baseUrl'] = base_url();
			$data['controller'] = $this;
			$this->load->view('admin/appxml_view',$data);
			
		}else{
			show_404();
		}
	}
	
	function sanitizeXML($string){
		if (!empty($string)) 
		{
			// remove EOT+NOREP+EOX|EOT+<char> sequence (FatturaPA)
			$string = preg_replace('/(\x{0004}(?:\x{201A}|\x{FFFD})(?:\x{0003}|\x{0004}).)/u', '', $string);
	 
			$regex = '/(
				[\xC0-\xC1] # Invalid UTF-8 Bytes
				| [\xF5-\xFF] # Invalid UTF-8 Bytes
				| \xE0[\x80-\x9F] # Overlong encoding of prior code point
				| \xF0[\x80-\x8F] # Overlong encoding of prior code point
				| [\xC2-\xDF](?![\x80-\xBF]) # Invalid UTF-8 Sequence Start
				| [\xE0-\xEF](?![\x80-\xBF]{2}) # Invalid UTF-8 Sequence Start
				| [\xF0-\xF4](?![\x80-\xBF]{3}) # Invalid UTF-8 Sequence Start
				| (?<=[\x0-\x7F\xF5-\xFF])[\x80-\xBF] # Invalid UTF-8 Sequence Middle
				| (?<![\xC2-\xDF]|[\xE0-\xEF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4]|[\xF0-\xF4][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF]{2})[\x80-\xBF] # Overlong Sequence
				| (?<=[\xE0-\xEF])[\x80-\xBF](?![\x80-\xBF]) # Short 3 byte sequence
				| (?<=[\xF0-\xF4])[\x80-\xBF](?![\x80-\xBF]{2}) # Short 4 byte sequence
				| (?<=[\xF0-\xF4][\x80-\xBF])[\x80-\xBF](?![\x80-\xBF]) # Short 4 byte sequence (2)
			)/x';
			$string = preg_replace($regex, '', $string);
	 
			$result = "";
			$current;
			$length = strlen($string);
			for ($i=0; $i < $length; $i++)
			{
				$current = ord($string{$i});
				if (($current == 0x9) ||
					($current == 0xA) ||
					($current == 0xD) ||
					(($current >= 0x20) && ($current <= 0xD7FF)) ||
					(($current >= 0xE000) && ($current <= 0xFFFD)) ||
					(($current >= 0x10000) && ($current <= 0x10FFFF)))
				{
					$result .= chr($current);
				}
				else
				{
					$ret;    // use this to strip invalid character(s)
					// $ret .= " ";    // use this to replace them with spaces
				}
			}
			$string = $result;
		}
		return $string;
	}
}
?> 