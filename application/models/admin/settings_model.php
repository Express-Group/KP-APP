<?php 
class settings_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	
	public function fetch_data()
	{
		$select_setting = $this->db->query("CALL select_setting()")->row_array();
		return $select_setting;
	}

	
	public function do_uploads($name)
	{
		$config['upload_path'] = destination_base_path.'images/FrontEnd/images/';
		print_r($config);exit;
		//$config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|PNG|GIF|JPG';
		$config['allowed_types'] = '*';
		$this->load->library('upload');
		$this->upload->initialize($config);

		if (!$this->upload->do_upload($name))
		{
			$data_view['template'] 	= 'settings';
			$data_view['img_error'] = array('error' => $this->upload->display_errors());
			print_r($data_view); exit;
			$this->load->view('admin_template',$data_view);
		}
		else 
		{
			$data = $this->upload->data();
			return $data;
		}
	}
	
	public function insert_update_func($userid)
	{
		extract($_POST);
		
		$dest_path = destination_base_path;
		
		if($hddn_logo == '') {
			$logo_data = $this->do_uploads('site_logo');
			//$logo_path = substr($logo_data['full_path'], strpos($logo_data['full_path'], "images"));   
			$logo_path = str_replace($dest_path, '', $logo_data['full_path']);   
		}
		else {
			$logo_path = $hddn_logo;
		}
		if($hddn_icon == '') {
			$favicon_data = $this->do_uploads('fav_icon');
			//$fav_icon_path = substr($favicon_data['full_path'], strpos($favicon_data['full_path'], "images"));   
			$fav_icon_path = str_replace($dest_path, '', $favicon_data['full_path']);   
		}
		else {
			$fav_icon_path = $hddn_icon;
		}
		
		$subsection_count = '';
		$this->db->trans_begin();
		$this->live_db->trans_begin();
		if($hddn_settngid == '')
		{
			$this->db->query('CALL settings_insert("'.$hidden_val.'", "'.$other_stories.'", "'.$trending_now.'", "'.$subsection_count.'", "'.$trending_time.'", "'.$mostread_time.'", "'.$facebook_url.'", "'.$twitter_url.'", "'.$google_url.'", "'.$rss_url.'", "'.$logo_path.'", "'.$fav_icon_path.'", "'.$otherstories_perpage.'", "'.$newsletter_mail_on.'", "'.$email_to.'", "'.$magazine_perpage.'","'.$slide_count.'" )');
		}
		else
		{
			$this->db->query('CALL settings_update("'.$hidden_val.'", "'.$other_stories.'", "'.$trending_now.'", "'.$subsection_count.'", "'.$trending_time.'", "'.$mostread_time.'", "'.$facebook_url.'", "'.$twitter_url.'", "'.$google_url.'", "'.$rss_url.'", "'.$logo_path.'", "'.$fav_icon_path.'", "'.$otherstories_perpage.'", "'.$newsletter_mail_on.'", "'.$email_to.'", "'.$magazine_perpage.'","'.$slide_count.'"  )');
		}
		
		$count_rows = $this->live_db->query("CALL select_setting()")->num_rows();
		if($count_rows==0)
		{
			$this->live_db->query('CALL settings_insert("'.$hidden_val.'", "'.$other_stories.'", "'.$trending_now.'", "'.$subsection_count.'", "'.$trending_time.'", "'.$mostread_time.'", "'.$facebook_url.'", "'.$twitter_url.'", "'.$google_url.'", "'.$rss_url.'", "'.$logo_path.'", "'.$fav_icon_path.'" , "'.$otherstories_perpage.'", "'.$newsletter_mail_on.'", "'.$email_to.'", "'.$magazine_perpage.'","'.$slide_count.'"   )');
		}
		else
		{
			$this->live_db->query('CALL settings_update("'.$hidden_val.'", "'.$other_stories.'", "'.$trending_now.'", "'.$subsection_count.'", "'.$trending_time.'", "'.$mostread_time.'", "'.$facebook_url.'", "'.$twitter_url.'", "'.$google_url.'", "'.$rss_url.'", "'.$logo_path.'", "'.$fav_icon_path.'", "'.$otherstories_perpage.'", "'.$newsletter_mail_on.'", "'.$email_to.'", "'.$magazine_perpage.'","'.$slide_count.'"  )');
		}
		
		if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->live_db->trans_rollback();
			$this->session->set_flashdata('message', 'Failed to update settings');
		} else {
			$this->db->trans_commit();
			$this->live_db->trans_commit();
			redirect(base_url().'niecpan/settings');
			$this->session->set_flashdata('message', '');
		}
	}
	
}

?>