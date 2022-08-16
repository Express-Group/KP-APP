<?php
class Authormodel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$this->live_db = $CI->load->database('live_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
	}
	public function addauthordetails($authorid, $userid)
	{
		$set_object = new add_edit;
		$set_object->addauthordetails($authorid, $userid);
	}
	public function validate_email()
	{
		$set_object = new validation;
		$set_object->validate_email();
	}
	
	public function validate_authorname()
	{
		$set_object = new validation;
		$set_object->validate_authorname();
	}
	
	public function datatable_author()
	{
		$set_object = new author_list;
		$set_object->datatable_author();
	}
	public function check_author($input_author_id)
	{
		$set_object = new edit_delete_author;
		$set_object->check_author_id($input_author_id);
	}
	public function getauthor($id)
	{
		$set_object = new edit_delete_author;
		return $set_object->getauthor($id);
	}
	public function getagency_name()
	{
		$set_object = new agency;
		return $set_object->getagency_name();
	}
	public function gettopic_name()
	{
		$set_object = new topic;
		return $set_object->gettopic_name();
	}
	public function status_check($id)
	{
		$set_object = new agency;
		return $set_object->status_check($id);
	}
	public function do_uploads($authorid)
	{
		$class_obj = new uploads;
		return $class_obj->do_uploads($authorid);
	}
	
	public function update_archive($author_image_path, $authorname,  $authorid)
	{
		$class_obj = new archive;
		return $class_obj->update_archive($author_image_path, $authorname, $authorid);
	}
	
}
class agency extends Authormodel
{
	public function getagency_name()
	{
		$agencyid = '';
		
		$agency = $this->db->query("CALL get_agencyname()");
		return $agency->result_array();
	}
	public function status_check($id)
	{
		
		$status = $this->db->query("CALL Check_authorid('" . $id . "')");
		return $status->num_rows();
		
	}
	
}
class uploads extends Authormodel
{
	function do_uploads($authorid)
	{
		$new_name                = $authorid;
		extract($_POST);

		$oldget =  getcwd();

		chdir(destination_base_path.columinst_image_path);

		$config['upload_path']   =  getcwd();
		//$config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|PNG|GIF|JPG';
		$config['allowed_types'] = '*';
		$config['file_name']     = $new_name;
		$config['overwrite']     = TRUE;
		chdir($oldget);
		$this->load->library('upload');
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('uploaduserimage'))
		{
			$data_view['template']  = 'author_form';
			$data_view['img_error'] = array(
				'error' => $this->upload->display_errors()
			);
			$this->load->view('admin_template', $data_view);
		}
		else
		{
			$data = $this->upload->data();
			
			return $data;
			
			$this->raw_name = $data['raw_name'] . $data['file_ext'];
			$upload_path    = $data['full_path'];
		}
	}
}
class topic extends Authormodel
{
	public function gettopic_name()
	{
		$agencyid = '';
		
		$agency = $this->db->query("CALL get_columnname()");
		return $agency->result_array();
	}
	
}

class add_edit extends Authormodel
{
	
	public function addauthordetails($authorid, $userid)
	{
		extract($_POST);
		
		$image_content_id       = 'NULL';
		$data['section']        = 'NULL';
		$ddAgency               = 'NULL';
		$data['byliner']        = 'NULL';
		$data['get_Country_ID'] = 'NULL';
		$data['get_State_ID']   = 'NULL';
		$data['get_City_ID']    = 'NULL';
		$get_auhorid = $this->input->post("txtAuthorId");
		if(!empty($authorid))
		{
			$authors      = $this->db->query("CALL getauthordetails('" . $authorid . "')");
			$authodetails = $authors->result_array();
		}
		$source             = '';
		$externalagencyname = '';
		$this->input->post('rbbylinesource');
		if($this->input->post('rbtab-group-1') == 'Byline')
		{
			$types          = 1;
			$blobimg        = '';
			$imgpath        = '';
			$encode_image   = '';
			$get_image_name = '';
			$img_ext        = '';
		}
		else if($this->input->post('rbtab-group-1') == 'columnist')
		{
			$types              = 2;
			$source             = '';
			$externalagencyname = '';
		}
		
		$full_path = $this->input->post('image_path');
		$img_alt   = $this->input->post('image_alt');
		
		$get_image = $_FILES['uploaduserimage']['tmp_name'];
		if($this->input->post('img_id') == "" && $get_image != '' && $get_auhorid != "")
		{
			$get_image_path = $this->do_uploads($get_auhorid);
			
			$full_path = columinst_image_path . $get_image_path['orig_name'];
			$img_alt   = $get_image_path['raw_name'];
		}
		
		if($this->input->post("ddagencyname") != '')
		{
			$agency_name = $this->input->post("ddagencyname");
		}
		else
		{
			$agency_name = 'NULL';
		}
		$topic_name = $this->input->post("ddtopicname");
		if($topic_name != '')
		{
			$topic_id = $this->input->post("ddtopicname");
		}
		else
		{
			$topic_id = 'NULL';
		}
		
		$authorname = htmlspecialchars($this->input->post('txtdisplayname'));
		$authorname = addslashes(str_replace("'", "&#039;", $authorname));
		
		$email     = $this->input->post('txtemail');
		$biography = addslashes($this->input->post('txtbiography'));
		$Status    = $this->input->post('status');
		
		$Createdon  = date('Y-m-d H:i:s');
		$Modifiedon = date('Y-m-d H:i:s');
		
		$columinst_name = htmlspecialchars($this->input->post('columinst_name'));
		$columinst_name = addslashes(str_replace("'", "&#039;", $columinst_name));
		
		if($get_auhorid == "")
		{
			$this->db->trans_begin();
			//$this->live_db->trans_begin();
			
			$query = $this->db->query("CALL  addauthordetails('" . $types . "','" . $authorname . "','" . $email . "','" . $Status . "','" . addslashes($biography) . "','" . $userid . "','" . $Createdon . "','" . $userid . "','" . $Modifiedon . "'," . $agency_name . "," . $topic_id . ", '', '', @last_insert_id)");
			
			$insert_id_result = $this->db->query("SELECT @last_insert_id")->result_array();
			$last_insert_id = $insert_id_result[0]['@last_insert_id'];
			
			if($this->input->post('img_id') == "" && $get_image != '' && $last_insert_id != "")
			{
				$get_image_path = $this->do_uploads($last_insert_id);
				
				$full_path = columinst_image_path . $get_image_path['orig_name'];
				$img_alt   = $get_image_path['raw_name'];
			}
			
			$this->db->query("CALL  update_author_image_path('" . $full_path . "', '" . $authorname . "', '" . $last_insert_id . "')");
			
			//$this->live_db->query("CALL  update_article_author('" . $full_path . "', '" . $authorname . "', '" . ($authorname) . "' , '" . trim($columinst_name) . "')");
			
			if($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				//$this->live_db->trans_rollback();
				$this->session->set_flashdata('error', 'Problem while Inserting.Please try again');
			}
			else
			{
				$this->db->trans_commit();
				//$this->live_db->trans_commit();
				$this->session->set_flashdata('success', 'Inserted Successfully');
			}
			
		}
		else if($get_auhorid != "")
		{
			$this->db->trans_begin();
			$this->live_db->trans_begin();
			$this->archive_db->trans_begin();
			$query = $this->db->query("CALL  updateauthordetails('" . $types . "','" . $authorname . "','" . $email . "','" . addslashes($biography) . "','" . $userid . "','" . $Modifiedon . "','" . $get_auhorid . "','" . $Status . "'," . $agency_name . "," . $topic_id . ", '" . $full_path . "', '" . $authorname . "')");
			
			$this->live_db->query("CALL  update_article_author('" . $full_path . "', '" . $authorname . "', '" . ($authorname) . "' , '" . trim($columinst_name) . "')");
			
			$this->update_archive($full_path, $authorname, $get_auhorid); 
			if($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE || $this->archive_db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				$this->archive_db->trans_rollback();
				$this->session->set_flashdata('error', 'Problem while updating');
			}
			else
			{
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				$this->archive_db->trans_commit();
				$this->session->set_flashdata('success', 'Updated Successfully');
			}
		}
		return true;
	}
}

class validation extends Authormodel
{
	public function validate_email()
	{
		$author_id = trim($this->input->post('author_id'));
		$field_input     = trim($this->input->post('field_input'));
		
		if($author_id == "")
			$author_id = '0';
		else
			$author_id = $this->input->post('author_id');
		
		$query = $this->db->query("CALL  check_author('" . $author_id . "','" . $field_input . "', 'Email', 2)")->num_rows();
		
		if($query > 0)
		{
			echo "Already exist";
			return FALSE;
		}
		else
		{
			return TRUE;
		}
		return $query->result_array();
	}
	
	
	public function validate_authorname()
	{
		$author_id = trim($this->input->post('author_id'));
		$field_input     = trim($this->input->post('field_input'));
		
		if($author_id == "")
			$author_id = '0';
		else
			$author_id = $this->input->post('author_id');
		
		$query = $this->db->query("CALL  check_author('" . $author_id . "','" . $field_input . "', 'AuthorName', 2)")->num_rows();
		
		if($query > 0)
		{
			echo "exist";
			//return FALSE;
		}
		else
		{
			echo "not exists";
			//return TRUE;
		}
	}
	
}
class author_list extends Authormodel
{
	public function datatable_author()
	{
		/*$check_page_func = $this->uri->segment(2);
		switch($check_page_func)
		{
		case "byliner_manager":
		$form_name = "view_addform/B";
		break;
		case "columnist_manager":
		$form_name = "view_addform/C";
		break;
		default:
		$form_name = '';
		//$data['Menu_id']='';
		}*/
		
		
		
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
		switch($Field)
		{
			case 0:
				$order_field = 'AuthorName';
				break;
			case 1:
				$order_field = 'authorType';
				break;
			case 2:
				$order_field = 'Email';
				break;
			case 3:
				$order_field = 'Createdon';
				break;
			case 4:
				$order_field = 'Status';
				break;
			default:
				$order_field = 'Author_id';
		}
		
		$TypeAND = " AND authorType='" . $page_name . "'";
		
		
		$searchtxt = htmlspecialchars(trim($searchtxt));
		$searchtxt = addslashes(str_replace("'", "&#039;", $searchtxt));
		
		$Total_rows = $this->db->query('CALL author_datatable("' . $TypeAND . '","","' . $filterby . '","")')->num_rows();
		
		$order_query = " AND authorType='" . $page_name . "' ORDER BY " . $order_field . " " . $order . " LIMIT " . $start . ", " . $length;
		
		$author_manager = $this->db->query('CALL author_datatable("' . $order_query . '","' . $status . '","' . $filterby . '","' . $searchtxt . '")')->result_array();
		
		$recordsFiltered         = $this->db->query('CALL author_datatable("' . $TypeAND . '","' . $status . '","' . $filterby . '","' . $searchtxt . '")')->num_rows();
		//echo $this->db->last_query(); exit;	
		$data['draw']            = $draw;
		$data["recordsTotal"]    = $Total_rows;
		$data["recordsFiltered"] = $recordsFiltered;
		$data['data']            = array();
		$Count                   = 0;
		
		//echo $this->uri->segment('').'sdasd';exit;
		/*if($this->uri->segment('2') == 'byliner_manager')
		{
		$data['Menu_id'] = get_menu_details_by_menu_name("Byline");
		}
		else
		{
		$data['Menu_id'] = get_menu_details_by_menu_name("Columnist");
		}*/
		
		if($page_name == 1)
			$data['Menu_id'] = get_menu_details_by_menu_name("Byline");
		else
			$data['Menu_id'] = get_menu_details_by_menu_name("Columnist");
		
		foreach($author_manager as $author)
		{
			$subdata = array();
			$subdata[] = $author['AuthorName'];
			if($author['authorType'] == 1)
			{
				$subdata[] = "Byline";
			}
			else if($author['authorType'] == 2)
			{
				$subdata[] = "Columnist";
			}
			$subdata[] = $author['Email'];
			$subdata[] = date('d-m-Y H:i:s', strtotime($author['Createdon']));
			
			if($author['Status'] == 1)
			{
				$subdata[] = '<td><i title="Active" class="fa fa-check"></i></td>';
			}
			else
			{
				$subdata[] = '<td><i title="Inactive"  class="fa fa-times"></i></td>';
			}
			
			/*$subdata[] ='<a class="button tick" href="'.base_url().'admin/author_manager/editauthor/'.$author['Author_id'].'" data-toggle="tooltip" title="Edit"   > <i class="fa fa-pencil" ></i> </a>
			<a class="button tick" href="#" data-toggle="tooltip" title="Move to Thrash" onclick="delete_author('.$author['Author_id'].')"  id="'.$author['Author_id'].'"> <i class="fa fa-trash-o"></i> </a>';*/
			
			
			$set_rights = "";
			
			if(defined("USERACCESS_EDIT" . $data['Menu_id']) && constant("USERACCESS_EDIT" . $data['Menu_id']) == 1)
			{
				$set_rights .= '<a class="button tick" href="' . base_url() . folder_name . '/author_manager/editauthor/' . $author['Author_id'] . '/' . $page_name . '" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			}
			else
			{
				$set_rights .= "";
			}
			
			if(defined("USERACCESS_DELETE" . $data['Menu_id']) && constant("USERACCESS_DELETE" . $data['Menu_id']) == 1)
			{
				$set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Thrash" onclick="delete_author(' . $author['Author_id'] . ')"  id="' . $author['Author_id'] . '"> <i class="fa fa-trash-o"></i> </a>';
			}
			else
			{
				$set_rights .= "";
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
}
class edit_delete_author extends Authormodel
{
	public function check_author_id($input_author_id)
	{
		
		$author_check_pro = $this->db->query("CALL Check_authorid('" . $input_author_id . "')")->num_rows();
		if($author_check_pro > 0)
		{
			$this->session->set_flashdata('failure_delete', 'Author mapped in other tables. It cannot be deleted');
			if($this->uri->segment(5) == "B")
				redirect(folder_name . '/byliner_manager');
			else
				redirect(folder_name . '/columnist_manager');
			//redirect(base_url().'admin/author_manager'); 
		}
		else
		{
			$this->delete_authorid($input_author_id);
		}
	}
	public function delete_authorid($input_author_id)
	{
		
		$author_delete = $this->db->query("CALL author_delete('" . $input_author_id . "')");
		if($author_delete == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'Deleted successfully');
			if($this->uri->segment(5) == "B")
				redirect(folder_name . '/byliner_manager');
			else
				redirect(folder_name . '/columnist_manager');
		}
	}
	public function getauthor($id)
	{
		$query = $this->db->query("CALL getauthordetails('" . $id . "')");
		return $query->result_array();
	}
	
}


class archive extends Authormodel
{
	public function update_archive($author_image_path, $authorname, $authorid)
	{
		$table_range = range('2009', date('Y'));
		$CI = &get_instance();
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
		foreach($table_range as $table_year) 
		{
			$table_name = 'article_'.$table_year;
			//var_dump($this->archive_db->table_exists('article_'.$table_year));
			$result_array = $this->archive_db->query('SHOW TABLES LIKE "'.$table_name.'%"')->row_array();
			
			if(count($result_array) > 0) {
				$result = array_values($result_array);
				
				if($table_name == $result[0])
				{
					$this->archive_db->query('UPDATE '.$table_name.' SET author_image_path = "'.$author_image_path.'", author_image_title = "'.$authorname.'", author_image_alt  = "'.$authorname.'", author_name =  "'.$authorname.'"  WHERE author_id =  '.$authorid.' ');
				}
			}
		}
	}
}

?>