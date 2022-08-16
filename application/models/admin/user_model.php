<?php 
class User_Model extends CI_Model 
{
	function adduserdetails($userid)
	{
		$set_object=new userdetails;
		return $set_object->adduserdetails($userid);
	}
	function get_departmentname()
	{
		$set_object=new departmentname;
		return $set_object->get_departmentname();	
	}
	function get_rolename()
	{
		$set_object=new rolename;
		return $set_object->get_rolename();	
	}
	/*function get_role_dpt()
	{
		$set_object=new rolename;
		return $set_object->get_role_dpt();	
		
	}*/
	function roledetails()
	{
		$set_object=new getroledetails;
		return $set_object->roledetails();
	}
	function datatable_menumaster()
	{
		$datatables=new datatable_usermaster;
		return $datatables->datatable_menumaster();	  
	}
	function edituserdetails($input_role_id)
	{
		$set_object=new getroledetails;
		return $set_object->edituserdetails($input_role_id);
			
	}
	function check_useremailID()
	{
		$set_object=new emailid;
		return $set_object->check_useremailID();
	}
	function check_username()
	{
		$set_object=new emailid;
		return $set_object->check_username();
	}
	function datatable_user()
	{
		$set_object=new userdatatable;
		return $set_object->datatable_user();
		
	}
	function user_delete($input_role_id)
	{
		$set_object=new getroledetails;
		return $set_object->user_delete($input_role_id);
		
	}

	function get_delete_user($input_role_id)
	{
		$set_object=new rolename;
		return $set_object->get_delete_user($input_role_id);
		
	}
	
	
	
}
class departmentname extends User_Model
{
	public function get_departmentname()
	{
		
		$dpt_name = $this->db->query("CALL get_departmentname()"); 
		return $dpt_name->result_array();
		
	}
	
}
class datatable_usermaster extends User_Model
{
	public function datatable_menumaster()//fn to list datatable details
	{
		
		$menu_select = $this->db->query("CALL menu_display()");
		return $menu_select->result_array();
	}  
}
class rolename extends User_Model
{
	public function get_rolename()
	{
		$deptid=$this->input->post('dptid');
		
		$dpt_name = $this->db->query("CALL get_rolename('".$deptid."')"); 
		return $dpt_name->result_array();
		
	}
	public function get_delete_user($input_role_id)
	{
		
		$user_delete= $this->db->query("CALL userdetails_delete('".$input_role_id."')");
		if($user_delete == TRUE)
		{
			$this->session->set_flashdata('success_delete', 'User details deleted successfully');
			redirect(base_url().folder_name.'/user_manager');
		}

	}
	/*public function get_role_dpt()
	{
		$roll= $this->db->query("CALL getrolename_by_id()");
		return $roll->result();
	}*/
	
}
class emailid extends User_Model
{
	public function check_useremailID()
	{
		$emailid= trim($this->input->post('email_id'));
		$user_id = trim($this->input->post('userid'));
		
		$dpt_name = $this->db->query("CALL check_usermailid('".$emailid."','".$user_id."')"); 
		return $dpt_name->num_rows();
		
	}
	
	public function check_username()
	{
		$user=htmlspecialchars($this->input->post('username'));
		$user = addslashes(str_replace("'", "&#039;", $user));
		$user_id = $this->input->post('userid');
		//$altemailid=$this->input->post('altemail_id');
		
		$dpt_name = $this->db->query("CALL check_username('".trim(addslashes($user))."','".$user_id."')"); 
		return $dpt_name->num_rows();
	}
	
	
}
class getroledetails extends User_Model
{
	public function roledetails()
	{
		$input_role_id=$this->input->post('role_id');
		
		$section_edit= $this->db->query("CALL role_editdetails('".$input_role_id."')");
		return $section_edit->result_array(); 
	}
	public function edituserdetails($input_role_id)
	{
		
		$section_edit= $this->db->query("CALL user_editdetails('".$input_role_id."')");
		return $section_edit->result_array(); 
		
	}
	public function user_delete($input_role_id)
	{
		
		//$user_delete_check=$this->db->query("CALL userdetails_delete_check('".$input_role_id."')")->row_array();
		$user_delete_check=$this->db->query("CALL check_userdetails('".$input_role_id."')")->num_rows();
		
		
		$user_delete_single=$this->db->query("CALL check_userdetails('".$input_role_id."')")->num_rows();
		
		//echo( $user_delete_check['Mapped']);exit;
		
		if($user_delete_check > 0 or $user_delete_single > 0)
		{
			$this->session->set_flashdata('failure_delete', 'User details mapped in other tables cannot be deleted ');
			redirect(base_url().folder_name.'/user_manager');
		}
		else
		{
			$this->get_delete_user($input_role_id); 
		}
		
	}
	
}
class userdetails extends User_Model
{
	/*
	function do_uploads()//Function to upload the images
	  {
		  	$config['upload_path'] = './uploads/user/';
		  	$config['allowed_types'] = 'gif|jpg|png';
		  	$config['max_size'] = '2000';
	
		  	$this->load->library('upload');
			$this->upload->initialize($config);
	
		  	if (!$this->upload->do_upload('fileProfileImage'))
		  	{
					$error_msg = array('error' => $this->upload->display_errors());
					$data['title']		= 'create user';
					$data['template'] 	= 'user_form';
					$this->load->view('admin_template',$data);
		  	}
		  	else
		  	{
					$data = $this->upload->data();
					ImageJPEG(ImageCreateFromString(file_get_contents($data['full_path'])),$data['full_path'], 45);
			
					$this->raw_name =  $data['raw_name'].$data['file_ext'];
		  	}
	  }
	*/
	
	
	function adduserdetails($userid)
	{
		
		/*	$get_image = $_FILES['fileProfileImage']['tmp_name'];
			$encode_image = "";
			if($get_image != "")
			{
					$this->do_uploads();
					$encode_image = addslashes(file_get_contents($get_image));
					echo $get_image_name = $_FILES['fileProfileImage']['name'];
  					$img_ext = pathinfo($get_image_name, PATHINFO_EXTENSION);
			}
			else
			{
				$encode_image ="";
				$get_image_name = '';
				$img_ext = '';
			} */
	
		$hdn_user_id=$this->input->post('txthiddenid');
		$username= htmlspecialchars($this->input->post('txtUserName'));
		$username = addslashes(str_replace("'", "&#039;", $username));
		//$username= addslashes($username);
		$password = trim($this->input->post('txtPassword'));
		/*$encrypted_string = $this->encrypt->encode($password);*/
		if($password != "")
			$encrypted_string = hash('sha512', $password);
		else
			$encrypted_string = '';
		
		//echo $sss= hash('sha512', $password);
		 //echo $hash = $this->passwordhash->HashPassword( $password );
		$status=$this->input->post('ddStatus');
		//$profileimage=$this->input->post('fileProfileImage');
		
		//$firstname=$this->input->post('txtFirstName'); 
		
		$firstname= htmlspecialchars($this->input->post('txtFirstName'));
		$firstname = addslashes(str_replace("'", "&#039;", $firstname));
		
		//$lastname=$this->input->post('txtLastName');
		
		$lastname= htmlspecialchars($this->input->post('txtLastName'));
		$lastname = addslashes(str_replace("'", "&#039;", $lastname));
		
		$employeecode=$this->input->post('txtEmployeeCode');
		//$nickname=$this->input->post('txtNickName');
		$roleid=$this->input->post('ddRole');
		$departmentid=$this->input->post('ddDepartment');
		$mobileno=$this->input->post('txtMobileNo');
		//$contactno=$this->input->post('txtContactNo');
		$emailid=$this->input->post('txtEmailID');
	//	$altemailid=$this->input->post('txtAltEmailID');
		
		$RM_name=$this->input->post('txtNameRM');
		$RM_jobtitle=$this->input->post('txtJobTitleRM');
		$RM_department=$this->input->post('txtDptRM');
		$RM_mobileno=$this->input->post('txtMobileNoRM');
		$RM_contactno=$this->input->post('txtContactNoRM');
		$RM_emailid=$this->input->post('txtEmailIDRM');
		
		$rights=$this->input->post('rights');
		$create_delete=$this->input->post('chkPageDesign');
		
		//$addconfiguration=$this->input->post('chkConfiguration');
		//$addpublish=$this->input->post('chkPublish');
		$addconfiguration=$create_delete;
		$addpublish=$create_delete;
		
		$add_article=$this->input->post('chkAddArticle');
		$add_advertisement=$this->input->post('chkAdvertisement');
		
		$addreleaselock=$this->input->post('chkreleasetemplate');
		
		if($this->input->post('ddRole'))
			$roleid = $this->input->post('ddRole');
		else
			$roleid = 'NULL';

		$createdby="1";
		$modifiedby="1";
		date_default_timezone_set('Asia/Calcutta');
		$createdon=date("Y-m-d:H:i:s");
		$modifiedon=date("Y-m-d:H:i:s");
		
		if($hdn_user_id=="")
		{
			
			$this->db->trans_begin();
	
		$user_insert_pro = $this->db->query("CALL userdetails_insert('".$username."','".$encrypted_string."','".$firstname."','".$lastname."',".$roleid.", '".$mobileno."','".$emailid."',".$status.",'".$userid."','".$createdon."','".$employeecode."',@insert_id)"); 
		
		if($user_insert_pro == TRUE)
				{
					$slct_lst_id=$this->db->query("SELECT @insert_id");	
					$last_id = $slct_lst_id->row_array();
					 $user_id = $last_id['@insert_id'];
					
					 foreach($rights as $menuid => $values)
						{
							$this->db->query("CALL  useraccess_rights_insert('".$user_id."', '".$menuid."','".@$values['view']."','".@$values['add']."','".@$values['edit']."','".@$values['delete']."','".@$values['publish']."','".@$values['unpublish']."', '".$userid."','".$createdon."','".$userid."','".$modifiedon."')");
							
						}
						$fpm_insert_pro = $this->db->query("CALL fpmuserrights_insert('".$user_id."','".$create_delete."','".$add_article."','".$add_advertisement."','".$userid."','".$createdon."','".$addconfiguration."','".$addpublish."', '".$addreleaselock."')");	
						
				}
				
					if ($this->db->trans_status() === FALSE)
						$this->db->trans_rollback();
					else
						$this->db->trans_commit();
		
		return true;
		}
		else
		{
			
			$this->db->trans_begin();
		
		$user_update_pro = $this->db->query("CALL userdetails_update('".$username."','".$encrypted_string."','".$firstname."','".$lastname."',".$roleid.",'".$mobileno."','".$emailid."','".$status."','".$userid."','".$modifiedon."','".$employeecode."','".$hdn_user_id."')"); 
		
		if($user_update_pro == TRUE)
				{
					$this->db->query("CALL  useraccess_rights_delete('".$hdn_user_id."')");
					
					foreach($rights as $menuid => $values)
					{
						$this->db->query("CALL  useraccess_rights_insert('".$hdn_user_id."', '".$menuid."','".@$values['view']."','".@$values['add']."','".@$values['edit']."','".@$values['delete']."','".@$values['publish']."','".@$values['unpublish']."', '".$userid."','".$createdon."','".$userid."','".$modifiedon."')");
					}	
						$fpm_insert_pro = $this->db->query("CALL fpmuserrights_update('".$hdn_user_id."','".$create_delete."','".$add_article."','".$add_advertisement."','".$userid."','".$modifiedon."', '".$addconfiguration."', '".$addpublish."', '".$addreleaselock."')");	
				}
		
					if ($this->db->trans_status() === FALSE)
						$this->db->trans_rollback();
					else
						$this->db->trans_commit();
		
		
		return true;
			
	
		}
		
		
	}
	
	
}
class userdatatable extends User_Model
{
	public function datatable_user()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
	
	switch ($Field) {
    case 0:
        $order_field = 't1.Username';
        break;
    case 1:
        $order_field = 't1.Firstname';
        break;
 
	case 2:
  	   $order_field = 't1.Mobileno';
		break;
	case 3:
  	   $order_field = 't1.Createdon';
	break;
	case 4:
  	   $order_field = 't1.Status';
	break;
    default:
        $order_field = 't1.User_id';
		}
		
		
		$Total_rows = $this->db->query('CALL user_datatable("","","","","'.$filterby.'",2)')->num_rows();
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$searchtxt  = htmlspecialchars(trim($searchtxt));
		
		$searchtxt = str_replace("'", "&#039;", $searchtxt);
		
		$user_manager =  $this->db->query('CALL user_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.addslashes($searchtxt).'","'.$filterby.'","'.$status.'")')->result_array();	

		$recordsFiltered =  $this->db->query('CALL user_datatable("","'.$from_date.'","'.$to_date.'","'.addslashes($searchtxt).'","'.$filterby.'","'.$status.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name("User");
		foreach($user_manager as $user) {
			
			$subdata = array();
	
			
			$subdata[] = stripslashes(htmlspecialchars_decode($user['Username']));	
			$subdata[] = $user['Firstname'].' '.$user['Lastname'];	
			$subdata[] = $user['Mobileno'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($user['Createdon']));
			if($user['status']==1)
			$subdata[] = '<td><i title="Active" class="fa fa-check"></i></td>';
			else
			$subdata[] = '<td><i title="Inactive"  class="fa fa-times"></i></td>';
			  
			 $set_rights = "";
			  
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().folder_name.'/user_manager/getuser_details/'.$user['User_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Trash" onclick="delete_userdetails('.$user['User_id'].')"  id="'.$user['User_id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
			  }
			  else 
			  { 
			  	$set_rights.="";
			  }
   			 $subdata[] = $set_rights; 
	   
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
				if($recordsFiltered == 0) {

				}
		
		echo json_encode($data);
		exit;

	
		
		
		
		
		
	}
	
	
	
}
