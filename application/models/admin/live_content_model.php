<?php
/**
 * Live Content Model Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */


header('Content-Type: text/html; charset=utf-8');
class Live_content_model extends CI_Model {
	
	public function __construct() {
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	
		# Start article live model coding
	
	public function insert_article($article_details) {
	
		$this->live_db->query('CALL add_article ('.$article_details["content_id"].','.$article_details["ecenic_id"].','.$article_details["section_id"].',"'.addslashes($article_details["section_name"]).'",'.$article_details["parent_section_id"].',"'.addslashes($article_details["parent_section_name"]).'",'.$article_details["grant_section_id"].',"'.addslashes($article_details["grant_parent_section_name"]).'","'.$article_details["linked_to_columnist"].'","'.$article_details["publish_start_date"].'","'.$article_details["publish_end_date"].'","'.$article_details["last_updated_on"].'","'.addslashes($article_details["title"]).'","'.addslashes($article_details["url"]).'","'.addslashes($article_details["summary_html"]).'","'.addslashes($article_details['article_page_content_html']).'","'.addslashes($article_details["home_page_image_path"]).'","'.addslashes($article_details["home_page_image_title"]).'","'.addslashes($article_details["home_page_image_alt"]).'","'.addslashes($article_details["section_page_image_path"]).'","'.addslashes($article_details["section_page_image_title"]).'","'.addslashes($article_details["section_page_image_alt"]).'","'.addslashes($article_details["article_page_image_path"]).'","'.addslashes($article_details["article_page_image_title"]).'","'.addslashes($article_details["article_page_image_alt"]).'","'.addslashes($article_details["column_name"]).'",'.$article_details["hits"].',"'.addslashes($article_details["tags"]).'",'.$article_details["allow_comments"].','.$article_details["allow_pagination"].',"'.addslashes($article_details["agency_name"]).'","'.addslashes($article_details["author_name"]).'","'.addslashes($article_details["author_image_path"]).'","'.addslashes($article_details["author_image_title"]).'","'.addslashes($article_details["author_image_alt"]).'","'.addslashes($article_details["country_name"]).'","'.addslashes($article_details["state_name"]).'","'.addslashes($article_details["city_name"]).'",'.$article_details["no_indexed"].','.$article_details["no_follow"].',"'.addslashes($article_details["canonical_url"]).'","'.addslashes($article_details["meta_Title"]).'","'.addslashes($article_details["meta_description"]).'",'.$article_details["section_promotion"].',"'.$article_details["status"].'")');
	
		$this->live_db->query('CALL add_short_content_details ('.$article_details["content_id"].',"'.addslashes(strip_tags($article_details["title"])).'","'.addslashes($article_details["tags"]).'","'.addslashes($article_details["summary_html"]).'","'.addslashes(strip_tags($article_details['article_page_content_html'])).'",'.$article_details["section_id"].',1)');
		
	}
	
	public function update_article($article_details) {
	
		$this->live_db->query('CALL update_article ('.$article_details["content_id"].','.$article_details["section_id"].',"'.addslashes($article_details["section_name"]).'",'.$article_details["parent_section_id"].',"'.addslashes($article_details["parent_section_name"]).'",'.$article_details["grant_section_id"].',"'.addslashes($article_details["grant_parent_section_name"]).'","'.$article_details["linked_to_columnist"].'","'.$article_details["publish_start_date"].'","'.$article_details["publish_end_date"].'","'.$article_details["last_updated_on"].'","'.addslashes($article_details["title"]).'","'.addslashes($article_details["url"]).'","'.addslashes($article_details["summary_html"]).'","'.addslashes($article_details['article_page_content_html']).'","'.addslashes($article_details["home_page_image_path"]).'","'.addslashes($article_details["home_page_image_title"]).'","'.addslashes($article_details["home_page_image_alt"]).'","'.addslashes($article_details["section_page_image_path"]).'","'.addslashes($article_details["section_page_image_title"]).'","'.addslashes($article_details["section_page_image_alt"]).'","'.addslashes($article_details["article_page_image_path"]).'","'.addslashes($article_details["article_page_image_title"]).'","'.addslashes($article_details["article_page_image_alt"]).'","'.addslashes($article_details["column_name"]).'","'.addslashes($article_details["tags"]).'",'.$article_details["allow_comments"].','.$article_details["allow_pagination"].',"'.addslashes($article_details["agency_name"]).'","'.addslashes($article_details["author_name"]).'","'.addslashes($article_details["author_image_path"]).'","'.addslashes($article_details["author_image_title"]).'","'.addslashes($article_details["author_image_alt"]).'","'.addslashes($article_details["country_name"]).'","'.addslashes($article_details["state_name"]).'","'.addslashes($article_details["city_name"]).'",'.$article_details["no_indexed"].','.$article_details["no_follow"].',"'.addslashes($article_details["canonical_url"]).'","'.addslashes($article_details["meta_Title"]).'","'.addslashes($article_details["meta_description"]).'",'.$article_details["section_promotion"].',"'.$article_details["status"].'")');
	
		$this->live_db->query('CALL update_short_content_details ('.$article_details["content_id"].',"'.addslashes(strip_tags($article_details["title"])).'","'.addslashes($article_details["tags"]).'","'.addslashes($article_details["summary_html"]).'","'.addslashes(strip_tags($article_details['article_page_content_html'])).'",'.$article_details["section_id"].',1)');
		
	}
	# End article live model coding
	
	# Start gallery live model coding
	
	public function insert_gallery($gallery_details) {

		$this->live_db->query('CALL add_gallery('.$gallery_details["content_id"].',NULL,'.$gallery_details["section_id"].',"'.addslashes($gallery_details["section_name"]).'",'.$gallery_details["parent_section_id"].',"'.addslashes($gallery_details["parent_section_name"]).'",'.$gallery_details["grant_section_id"].',"'.addslashes($gallery_details["grant_parent_section_name"]).'","'.$gallery_details["publish_start_date"].'","'.$gallery_details["last_updated_on"].'","'.addslashes($gallery_details["title"]).'","'.addslashes($gallery_details["url"]).'","'.addslashes($gallery_details["summary_html"]).'","'.addslashes($gallery_details["first_image_path"]).'","'.addslashes($gallery_details["first_image_title"]).'","'.addslashes($gallery_details["first_image_alt"]).'",'.$gallery_details["hits"].',"'.addslashes($gallery_details["tags"]).'",'.$gallery_details["allow_comments"].',"'.addslashes($gallery_details["agency_name"]).'","'.addslashes($gallery_details["author_name"]).'","'.addslashes($gallery_details["country_name"]).'","'.addslashes($gallery_details["state_name"]).'","'.addslashes($gallery_details["city_name"]).'",'.$gallery_details["no_indexed"].','.$gallery_details["no_follow"].',"'.addslashes($gallery_details["canonical_url"]).'","'.addslashes($gallery_details["meta_Title"]).'","'.addslashes($gallery_details["meta_description"]).'","'.$gallery_details['status'].'")');

		return $this->live_db->query('CALL add_short_content_details ('.$gallery_details["content_id"].',"'.addslashes(strip_tags($gallery_details["title"])).'","'.addslashes($gallery_details["tags"]).'","'.addslashes($gallery_details["summary_plain_text"]).'","",'.$gallery_details["section_id"].',3)');
			
	}
	
		public function update_gallery($gallery_details) {
			
		$this->live_db->query('CALL update_gallery('.$gallery_details["content_id"].','.$gallery_details["section_id"].',"'.addslashes($gallery_details["section_name"]).'",'.$gallery_details["parent_section_id"].',"'.addslashes($gallery_details["parent_section_name"]).'",'.$gallery_details["grant_section_id"].',"'.addslashes($gallery_details["grant_parent_section_name"]).'","'.$gallery_details["publish_start_date"].'","'.$gallery_details["last_updated_on"].'","'.addslashes($gallery_details["title"]).'","'.addslashes($gallery_details["url"]).'","'.addslashes($gallery_details["summary_html"]).'","'.addslashes($gallery_details["first_image_path"]).'","'.addslashes($gallery_details["first_image_title"]).'","'.addslashes($gallery_details["first_image_alt"]).'","'.addslashes($gallery_details["tags"]).'",'.$gallery_details["allow_comments"].',"'.addslashes($gallery_details["agency_name"]).'","'.addslashes($gallery_details["author_name"]).'","'.addslashes($gallery_details["country_name"]).'","'.addslashes($gallery_details["state_name"]).'","'.addslashes($gallery_details["city_name"]).'",'.$gallery_details["no_indexed"].','.$gallery_details["no_follow"].',"'.addslashes($gallery_details["canonical_url"]).'","'.addslashes($gallery_details["meta_Title"]).'","'.addslashes($gallery_details["meta_description"]).'","'.$gallery_details['status'].'")');

		return $this->live_db->query('CALL update_short_content_details ('.$gallery_details["content_id"].',"'.addslashes(strip_tags($gallery_details["title"])).'","'.addslashes($gallery_details["tags"]).'","'.addslashes($gallery_details["summary_plain_text"]).'","",'.$gallery_details["section_id"].',3)');
			
	}
	
	public function insert_gallery_related_images($gallery_details) {
		return $this->live_db->query('CALL  add_gallery_related_images ("'.$gallery_details["content_id"].'","'.addslashes($gallery_details["gallery_image_path"]).'","'.addslashes($gallery_details["gallery_image_title"]).'","'.addslashes($gallery_details["gallery_image_alt"]).'","'.$gallery_details["display_order"].'")');
	}
	
	public function delete_gallery_related_images($content_id) {
		$query = $this->live_db->query("CALL delete_gallery_related_images (". $content_id.")");
	}
	
	
	# End gallery live model coding
	
	
	
	public function insert_resources($resources_details) {
		$this->live_db->query('CALL add_resources('.$resources_details['ecenic_id'].',"'.addslashes($resources_details['title']).'","'.addslashes($resources_details['url']).'","'.addslashes($resources_details['resource_url']).'",'.$resources_details['article_id'].',"'.$resources_details['image_path'].'","'.$resources_details['image_caption'].'","'.$resources_details['image_alt'].'","'.$resources_details['publish_start_date'].'","'.$resources_details['last_updated_on'].'","P",'.$resources_details['content_id'].')');
	}
	
	public function update_resources($resources_details) {
		$this->live_db->query('CALL update_resources ("'.addslashes($resources_details['title']).'","'.addslashes($resources_details['url']).'","'.addslashes($resources_details['resource_url']).'",'.$resources_details['article_id'].',"'.$resources_details['image_path'].'","'.$resources_details['image_caption'].'","'.$resources_details['image_alt'].'","'.$resources_details['publish_start_date'].'","'.$resources_details['last_updated_on'].'","P",'.$resources_details['content_id'].')');
	}
	
	# Start video live model coding
		
	/*
	*
	* Insert the live video details in video and short video details
	*
	* @access public
	* @param Array of video details
	* @return TRUE
	*
	*/
	
	public function insert_update_live_video($audio_video_details) {
	
	$this->live_db->query('CALL add_update_video('.$audio_video_details["content_id"].',NULL,'.$audio_video_details["section_id"].',"'.addslashes($audio_video_details["section_name"]).'",'.$audio_video_details["parent_section_id"].',"'.addslashes($audio_video_details["parent_section_name"]).'",'.$audio_video_details["grant_section_id"].',"'.addslashes($audio_video_details["grant_parent_section_name"]).'","'.$audio_video_details["publish_start_date"].'","'.$audio_video_details["last_updated_on"].'","'.addslashes($audio_video_details["title"]).'","'.addslashes($audio_video_details["url"]).'","'.addslashes($audio_video_details["summary_html"]).'","'.addslashes($audio_video_details["script"]).'","'.addslashes($audio_video_details['video_site']).'","'.addslashes($audio_video_details["audio_video_image_path"]).'","'.addslashes($audio_video_details["audio_video_image_title"]).'","'.addslashes($audio_video_details["audio_video_image_alt"]).'",'.$audio_video_details["hits"].',"'.addslashes($audio_video_details["tags"]).'",'.$audio_video_details["allow_comments"].',"'.addslashes($audio_video_details["agency_name"]).'","'.addslashes($audio_video_details["author_name"]).'","'.addslashes($audio_video_details["country_name"]).'","'.addslashes($audio_video_details["state_name"]).'","'.addslashes($audio_video_details["city_name"]).'",'.$audio_video_details["no_indexed"].','.$audio_video_details["no_follow"].',"'.addslashes($audio_video_details["canonical_url"]).'","'.addslashes($audio_video_details["meta_Title"]).'","'.addslashes($audio_video_details["meta_description"]).'","'.$audio_video_details['status'].'")');
		
		return TRUE;
		
	}
	
	# End video live model coding
	
		
	# Start audio live model coding
		
	/*
	*
	* Insert the live audio details in video and short audio details
	*
	* @access public
	* @param Array of audio details
	* @return TRUE
	*
	*/
	
	public function insert_update_live_audio($audio_video_details) {
	
	$this->live_db->query('CALL add_update_audio('.$audio_video_details["content_id"].',NULL,'.$audio_video_details["section_id"].',"'.addslashes($audio_video_details["section_name"]).'",'.$audio_video_details["parent_section_id"].',"'.addslashes($audio_video_details["parent_section_name"]).'",'.$audio_video_details["grant_section_id"].',"'.addslashes($audio_video_details["grant_parent_section_name"]).'","'.$audio_video_details["publish_start_date"].'","'.$audio_video_details["last_updated_on"].'","'.addslashes($audio_video_details["title"]).'","'.addslashes($audio_video_details["url"]).'","'.addslashes($audio_video_details["summary_html"]).'","'.addslashes($audio_video_details['audio_path']).'","'.addslashes($audio_video_details["audio_video_image_path"]).'","'.addslashes($audio_video_details["audio_video_image_title"]).'","'.addslashes($audio_video_details["audio_video_image_alt"]).'",'.$audio_video_details["hits"].',"'.addslashes($audio_video_details["tags"]).'",'.$audio_video_details["allow_comments"].',"'.addslashes($audio_video_details["agency_name"]).'","'.addslashes($audio_video_details["author_name"]).'","'.addslashes($audio_video_details["country_name"]).'","'.addslashes($audio_video_details["state_name"]).'","'.addslashes($audio_video_details["city_name"]).'",'.$audio_video_details["no_indexed"].','.$audio_video_details["no_follow"].',"'.addslashes($audio_video_details["canonical_url"]).'","'.addslashes($audio_video_details["meta_Title"]).'","'.addslashes($audio_video_details["meta_description"]).'","'.$audio_video_details['status'].'")');
		
		return TRUE;
		
	}
	
	# End audio live model coding
	
	//add author image in article table
	public function add_author_image($image_path, $authorname, $alt_tag, $img_height, $img_width) {
		$this->live_db->query('CALL update_author_image("'.trim($authorname).'", "'.trim($image_path).'","'.trim($alt_tag).'","'.trim($img_height).'","'.trim($img_width).'")');
	}
	
	public function delete_breakingnews()//delete breaking news from live table
	{
		$this->live_db->query("CALL delete_breaking_news_live()");
	}
	
	public function publish_breakingnews($news_title, $Content_ID,  $news_display_order, $status)//move active status breaking news to live table
	{
		$publish_news = $this->live_db->query('CALL  publish_breaking_news("'.addslashes($news_title).'", "'.$Content_ID.'", "'.USERID.'" , "'.date("Y-m-d H:i:s").'", "'.USERID.'", "'.date("Y-m-d H:i:s").'", "'.$news_display_order.'", "'.$status.'", "'.strip_tags($news_title).'")');
	}
	
	public function delete_commonwidget_article($get_SectionID, $checkWiget_Type, $user_id)//delete jumbo menu and editor pick news from live table
	{	
		if($checkWiget_Type   == "jumbo_widget_articles")
		{
			$widgetType = "J";
			$get_section_id = $get_SectionID;
		}
		else
		{
			$widgetType = "E";
			$get_section_id =  '';
		}
		$insert_result 			= $this->live_db->query("CALL  delete_commonwidget_articles ('".$get_section_id."', '".$user_id."', '".$widgetType."')");	
	}
	
	public function addwidget_articlecustomdetails_permanent($custom_details, $user_id)//move active status jumbo menu and editor pick news to live table
	{	
		$checkWiget_Type = $custom_details['checkWiget_Type'];
		
		if($checkWiget_Type   == "jumbo_widget_articles")
		{
			$widgetType = "J";
			$get_section_id = $custom_details['get_SectionID'];
		}
		else
		{
			$widgetType = "E";
			$get_section_id =  '';
		}
		
		$content_id 						= $custom_details['content_id'];
		$custom_title 						= addslashes($custom_details['Title']);
		$custom_summary 					= addslashes($custom_details['Summary']);
		$uploaded_image 					= ($custom_details['uploaded_image']);	
		$uploaded_image_name				= @$custom_details['image_name'];
		$uploaded_image_id = $custom_details['old_image_id'];		
		$update_displayorder 				= (@$custom_details['display_order'] == '')? '' : @$custom_details['display_order'];
	 	$update_contet_status 				= (@$custom_details['checked_status'] == '')? '2' : @$custom_details['checked_status']; 
		$user_id 							= $user_id;
		$insert_date 						= @$custom_details['modified_date'];
		$contentTypeID 						= @$custom_details['contentType_id'];
		$checkWiget_Type = $custom_details['checkWiget_Type'];
		$physical_path = $custom_details['get_image_name'];
			$img_height = $custom_details['img_height'];
			$img_width = $custom_details['img_width'];
			$alt_tag =	$custom_details['alt_tag'];
			if($uploaded_image_id!="")
			{
				$get_image_id = $uploaded_image_id;
			}
			else if($uploaded_image != "")
			{
				$get_image_id = $uploaded_image;
			}
			else
			{
				$get_image_id = 'NULL';
			}
			$insert_date 	= date("Y-m-d H:i:s", time());
			$insert_result 			= $this->live_db->query("CALL publish_commonwidget_articles('".$content_id."', '".$custom_title."', '".$custom_summary."', '".$physical_path."', '".$user_id."', '".$update_contet_status ."', '".$update_displayorder."', '".$insert_date."','".$uploaded_image_name."', '".$get_section_id."', '".$get_image_id."', '".$widgetType."', '".$contentTypeID."', '".$img_height."', '".$img_width."', '".$alt_tag."')");	
			if($insert_result == true)
			{
				$return_msg =  array("status"=>true,"message"=> " Article successfully saved"); 
			}
			else
			{
				$return_msg =  array("status"=>false,"message"=>"Internal error to save in ".$content_id." Article","inserted_id"=>""); 
			}														
						
		return $return_msg;						
	}
	
	public function delete_askprabu_live($Id)
	{
		$this->live_db->query('CALL delete_askprabhu_qnlist("'.$Id.'")'); 
	}
	
	public function insert_askprabu_live($UserName, $EmailID, $SubmittedOn_txt, $IPAddress_txt, $qstn_txt,$answer, $place, $status, $Id, $modified_date, $userid)
	{
		$this->live_db->query('CALL  insert_askprabhu_qnlist("'.$UserName.'", "'.addslashes($qstn_txt).'","'.addslashes($answer).'","'.strip_tags($answer).'","'.$place.'","'.$EmailID.'","'.$status.'", "'.$SubmittedOn_txt.'","'.$modified_date.'", "'.$Id.'","'.$userid.'")'); 
	}
	
	public function insert_section_mapping($live_insert_array,$type) {
			$result = implode('","', $live_insert_array);
			$article_mapping = $this->live_db->query('CALL insert_section_mapping("' . $result . '",'. $type .')');
	}
	
	public function delete_section_mapping($content_id,$type) {
		$query = $this->live_db->query("CALL delete_section_mapping (". $content_id.",".$type.")");
	}
	
	public function check_livecontents($content_id, $type) {
		$query = $this->live_db->query("CALL check_livecontents (". $content_id.",".$type.")");
		return $query->num_rows();
	}
	
	public function delete_livecontents($content_id, $type) {
		$query = $this->live_db->query("CALL delete_livecontents (". $content_id.",".$type.")");
	}
	
	public function get_livecontentsdetails($content_id, $type) {
		$query = $this->live_db->query("CALL remodal_content_details_live(".$content_id.",".$type.")")->row_array();
		return $query;
	}
	
	
}