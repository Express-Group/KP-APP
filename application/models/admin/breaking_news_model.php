<?php
class breaking_news_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}

	public function delete_news_func($news_id)
	{
		$class_object = new news_delete;
		return $class_object->delete_news_func($news_id);
	}
	
	public function news_add($user_id)
	{
		$class_obj = new brkingNews_subclass;
		return $class_obj->news_add($user_id);
	}
	
	public function fetch_news_val($news_id)
	{
		$class_obj = new brkingNews_subclass;
		return $class_obj->fetch_news_val($news_id);
	}
	
	public function publish_breakingnews()
	{
		$class_obj = new brkingNews_subclass;
		return $class_obj->publish_breakingnews();
	}
	
	public function pagination_datatable()
	{
		$class_obj = new News_datatable;
		return $class_obj->pagination_datatable();
	}
	
	public function search_internal_article()
	{
		$class_object = new Article_search_class;
		return $class_object->search_internal_article();
	}
	
	public function changestatus() {
		$class_object = new cnage_status_class;
		$class_object->changestatus();
	}
	
	public function update_dispOrder($userid) {
		$class_object = new news_delete;
		$class_object->update_dispOrder($userid);
	}
	
	public function check_title_exists()
	{
		$news_title = trim($this->input->post('news_title'));
		$news_id = trim($this->input->post('news_id'));
		$check_title = $this->db->query("CALL check_brkngNews_exists('".strip_tags($news_title)."', '".$news_id."')");
		return $check_title->num_rows();
	}
}

class cnage_status_class extends breaking_news_model
{
	public function changestatus()
	{
		$get_news_id = $_POST['news_id'];
		$get_status = $_POST['status'];
	
		  if($get_status == 1)
		  {
			  $status = 0;
		  }
		  elseif($get_status == 0)
		  {
			  $status = 1;
		  }
		$status = $this->db->query("CALL breakingNews_status_change('".$get_news_id."', '".$status."')");
		
		if($status == TRUE)
		{
			echo "success";
		}
	}
}

class news_delete extends breaking_news_model
{
	public function delete_news_func($news_id) //delete function
	{
		$this->db->trans_begin();
		$this->live_db->trans_begin();
		$this->live_db->query("CALL delete_breaking_news_live('".$news_id."')");
		$del_query = $this->db->query("CALL delete_breaking_news('".$news_id."')");
		
		if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->live_db->trans_rollback();
			$this->session->set_flashdata('error', "Problem while deleting. Please try again");
			redirect('niecpan/breaking_news_manager');	
		} else {
			$this->db->trans_commit();
			$this->live_db->trans_commit();
			$this->session->set_flashdata('success', 'Deleted successfully');
			redirect('niecpan/breaking_news_manager');
		}
	}
	
	public function update_dispOrder($userid)
	{
		extract($_POST);
		//print_r($get_order);
		$this->db->trans_begin();
		foreach($get_order as $update_order)
		{
			$this->db->query('CALL change_breakingnewsDisplayOrder("'.$update_order['order_val'].'", "'.$update_order['news_id'].'")');
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'Failed to save display order';
		} else {
			$this->db->trans_commit();
			echo 'Display order saved succesfully';
		}
	}
}

class brkingNews_subclass extends breaking_news_model
{
	public function fetch_news_val($news_id)//function to fetch values in edit
	{
		$fetch_details = $this->db->query('CALL select_breaking_news("'.$news_id.'")');
		return $fetch_details;
	}
	
	
	public function publish_breakingnews()//move active status breaking news to live table
	{
		$select_value = $this->fetch_news_val('')->result_array();
		$this->live_db->trans_begin();
		$this->live_db->query("CALL delete_breaking_news_live('')");
		foreach($select_value as $publish_news)
		{
			if($publish_news['Content_ID'] != '')
				$article_id = $publish_news['Content_ID'];
			else
				$article_id = 'NULL';
				
			$publish_news = $this->live_db->query('CALL  publish_breaking_news("'.addslashes($publish_news['Title']).'", '.$article_id.', "'.$publish_news['Displayorder'].'", "'.$publish_news['breakingnews_id'].'")');
		}
		if ($this->live_db->trans_status() === FALSE) {
			$this->live_db->trans_rollback();
			echo 'fail';
		} else {
			$this->live_db->trans_commit();
			echo 'success';
		}
		
	}
	
	
	public function news_add($user_id) //insert and update function 
	{
		$upload_on = date("Y-m-d H:i:s"); 
		$modfied_on = date("Y-m-d H:i:s"); 
		$get_status = $this->input->post('view3');
		$news_title = trim($this->input->post('txtTitle'));
		$news_display_order = '';
		$hidden_id = $this->input->post('txtHiddenId');
		
		$hidden_display_order = $this->input->post('hidden_display_order');
		
		$news_title = trim(preg_replace('/(&nbsp;)+|\s\K\s+/','',$news_title));
		
		$hidden_contett_id = $this->input->post('hiddn_article_id');
		if($hidden_contett_id != "" && $hidden_contett_id != NULL)
		{
			$get_article_id = $this->input->post('hiddn_article_id');
		}
		else
		{
			$get_article_id = 'NULL';
		}
		$articl_title = $this->input->post('txtArticleTitle');
		
		$this->db->trans_begin();
		if($hidden_id == "" or $hidden_id == 0) //insert condition
		{
			$insrt_query = $this->db->query('CALL insert_breakingNews("'.addslashes($news_title).'", '.$get_article_id.', "'.$user_id.'" , "'.$upload_on.'", "'.$user_id.'", "'.$modfied_on.'", "'.$news_display_order.'", "'.$get_status.'", "'.strip_tags($news_title).'")');	
			
			$msg = 'Inserted Successfully';
			$fail_msg = "Problem while inserting. Please try again";
		}
		else //update condition
		{
			$update_query = $this->db->query('CALL update_breaking_news("'.addslashes($news_title).'",'.$get_article_id.', "'.$get_status.'", "'.$news_display_order.'", "'.$user_id.'","'.$modfied_on.'","'.$hidden_id .'", "'.strip_tags($news_title).'")');
			
			$msg = 'Updated Successfully';
			$fail_msg = "Problem while updating. Please try again";
		}
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $fail_msg);
			redirect(base_url().'niecpan/breaking_news_manager');
		} else {
			$this->db->trans_commit();
			$this->session->set_flashdata('success', $msg);
			redirect(base_url().'niecpan/breaking_news_manager');
		}
		
	}
}

class Article_search_class extends breaking_news_model 
{
	public function search_internal_article() //get article datatable function
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		$article_start = 0;
		$article_length = 70;
		$content_type = $article_Type; 
		
		switch ($Field = 2) {
    case 0:
        $order_field = 'm.title';
        break;
    case 1:
        $order_field = 'm.Section_id';
        break;
	case 2:
        $order_field = 'm.Modifiedon';
        break;
    default:
        $order_field = 'm.content_id';
		}
		
		if($content_id != '') {
			$content_where_condition = " AND m.content_id != ".$content_id." ";
		} else {
			$content_where_condition = "";
		}
		
		
		$Total_rows = $this->db->query('CALL get_link_article_content ("'.$content_where_condition.' ","","","","","")')->num_rows();
		
		$Search_value = $Search_text;
		
		if($Search_by == 'article_id') {
		$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		
		if($Search_result == '')
			$Search_value = $Search_text;
		else
			$Search_value = $Search_result;
		}
		
		if($check_in != '')  {
		$check_in_date 	= new DateTime($check_in);
		$check_in = $check_in_date->format('Y-m-d');
		}
		
		if($check_out != '')  {
		$check_out_date = new DateTime($check_out);
		$check_out = $check_out_date->format('Y-m-d');
		}
		
		$article_manager =  $this->db->query('CALL get_link_article_content ("'.$content_where_condition.'ORDER BY '.$order_field.' '.$order.' LIMIT '.$article_start.', '.$article_length.'","'.$check_in.'","'.$check_out.'","'.$Search_value.'","'.$Section.'","'.$Status.'")')->result_array();	
		
		$recordsFiltered =  $this->db->query('CALL get_link_article_content ("'.$content_where_condition.' ","'.$check_in.'","'.$check_out.'","'.$Search_value.'", "'.$Section.'","'.$Status.'")')->num_rows();
		
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		foreach($article_manager as $article) 
		{
			$subdata = array();
			
			$subdata[] ='<div align="center"><p title="'.strip_tags($article['title']).'" href="">'.shortDescription(strip_tags($article['title'])).'</p></div>';
			$URLSectionStructure = (isset($article['Section_id']))? GenerateBreadCrumbBySectionId($article['Section_id']) : "-";
				$subdata[] = $URLSectionStructure;
			$subdata[] =  	$article['Modifiedon'];
			$subdata[] =  '<input type="hidden" id="hidden_txt'.$article['content_id'].'" value="'.strip_tags($article['title']).'"><a href="javascript:void(0);" id="article_111" long_title ="'.strip_tags($article['title']).'" short_title="'. shortDescription(strip_tags($article['title'])).'" value="'. $article['content_id'].'" rel="article"  onclick="get_content_id('.$article['content_id'].',  hidden_txt'.$article['content_id'].'.value)"  title="Link"  data-toggle="tooltip" class="button tick" data-original-title="Add" id="internal_action" ><i class="fa fa-plus"></i></a>'; 
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
				if($recordsFiltered == 0) {

				}
		
		echo json_encode($data);
		exit;
	}
}


class News_datatable extends breaking_news_model
{
	public function pagination_datatable() //manager page datatable function
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field=4)
			{
			case 0:
				$order_field = 'Title';
				break;
			case 1:
				$order_field = 'Displayorder';
				break;
			case 2:
				$order_field = 'Username';
				break;
			case 3:
				$order_field = 'Createdon';
				break;
			case 4:
				$order_field = 'status , Displayorder';
				break;
			default:
			$order_field = 'status';
			}
			
		$Total_rows = $this->db->query('CALL breakingNews_datatable("","","","","","")')->num_rows();
		
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
		
		$news_values =  $this->db->query('CALL breakingNews_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$search_by.'","'.$Status.'")')->result_array();	
		
		$recordsFiltered =  $this->db->query('CALL breakingNews_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$search_by.'","'.$Status.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		$Menu_id = get_menu_details_by_menu_name('Breaking News');
		
		foreach($news_values as $news)
		{
			$subdata = array();
			
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($news['Title']).'">'.mb_substr(strip_tags($news['Title']), 0, 20).'...'.'</p>';
			
			if($news['Content_ID'] != "")
			{
				$subdata[] = '<p class="tooltip_cursor" title="'.strip_tags($news['article_title']).'">'.mb_substr(strip_tags($news['article_title']), 0, 20).'...'.'</p>'; 
			}
			else
			{
				$subdata[] = '-';
			}
			/*if($news['status'] == "0")
			{
				$display_order = $news['Displayorder'];
			}
			else
			{
				$display_order = "<input type='text' id='change_order".$news['breakingnews_id']."' name='change_order".$news['breakingnews_id']."' class='news_order' value='".$news['Displayorder']."'/>";
			}*/
			$display_order = $news['Displayorder'];
			$subdata[] = '<span class="change_displayorder">'.$display_order.'</span><input type="hidden" class="updatefield" name="hidden_order[]" news_id ="'.$news['breakingnews_id'].'"  id="hidden_order_'.$news['breakingnews_id'].'" value="'. $news['Displayorder'].'">';
			//$subdata[] = $news['Displayorder'];
			$subdata[] = $news['Username'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($news['Createdon']));
			
			if($news['status'] == 1)
			{
				//$status_icon = '<a data-toggle="tooltip" title="Active" href="javascript:void()" id="img_change'.$news['breakingnews_id'].'" data-original-title="Active"><i id="status_img'.$news['breakingnews_id'].'"  class="fa fa-check"></i></a>';
				$status_icon = '<i title="Active" id="status_img'.$news['breakingnews_id'].'"  class="fa fa-check"></i>';
			}
			elseif($news['status'] == 0)
			{
				//$status_icon = '<a data-toggle="tooltip" title="Inactive" href="javascript:void()" id="img_change'.$news['breakingnews_id'].'"  data-original-title="Active"><i id="status_img'.$news['breakingnews_id'].'" class="fa fa-times"></i></a>';
				
				$status_icon = '<i title="Inactive" id="status_img'.$news['breakingnews_id'].'" class="fa fa-times"></i>';
			}
			$subdata[] = $status_icon;
			
			
			$set_status = '<div class="buttonHolder">';
			
			if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == '1'){
				/*if($news['status'] == 1)
				{
					$set_status .='<a class="button tick"  href="#"  onclick="update_disp_order('.$news['breakingnews_id'].', '.$news['Displayorder'].')" data-toggle="tooltip" title="Save"> <i class="fa fa-file-text-o" ></i> </a>';
				}*/
				$set_status .= '<a class="button tick"  href="'.base_url().'niecpan/breaking_news_manager/update_brkng_news/'.urlencode(base64_encode($news['breakingnews_id'])).'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			
			} else { 
				$set_status .= ""; 
			}
			
			if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == '1')
			{
			$set_status .= '<a class="button tick" href="#" data-toggle="tooltip" onclick="delete_news_func('.$news['breakingnews_id'].')" title="Move to Trash"  id=""> <i class="fa fa-trash-o"></i> </a>';
			} else { 
				$set_status .= ""; 
			}
				 
			if($news['status'] == "1")
                {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == '1') { 
						$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Inactive" news_id = '.$news['breakingnews_id'].' status = '.$news['status'].' name="'.strip_tags($news['Title']).'" id="status_change"><i id="status'.$news['breakingnews_id'].'" class="fa fa-pause"></i></a> '.'';
					}else { 
						$set_status .= ""; 
					}
					
				}
                elseif($news['status'] == "0")
                { 
					if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == '1') {
						$set_status .= '<a data-toggle="tooltip" href="#" title="Active" class="button heart" data-original-title="" news_id = '.$news['breakingnews_id'].' status = '.$news['status'].' name="'.strip_tags($news['Title']).'" id="status_change"><i id="status'.$news['breakingnews_id'].'" class="fa fa-caret-right"></i></a>'.'';
					}else { 
						$set_status .= ""; 
					}
				}
				$set_status .= '</div>';
			 	$subdata[] = $set_status;
				$data['data'][$Count] = $subdata;
				$Count++;
		}
		
				if($recordsFiltered == 0) {

				}
		
		echo json_encode($data);
		exit;
		
	}
}
?>