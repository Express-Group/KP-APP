<?php
/**
 * Resources Model Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */


header('Content-Type: text/html; charset=utf-8');
class Resources_model extends CI_Model

{
	public function __construct()

	{
		parent::__construct();
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
		
		
		$this->load->model('admin/live_content_model');
	}
	
	public function insert_resources() {
		
		extract($_POST);	
	
		$resources_details 	= $this->get_additional_resource_details();

		$this->db->trans_begin();
		$this->live_db->trans_begin();
		
		$this->db->query('SET @contentid = 0; CALL add_update_resourcemaster(NULL,"'.addslashes($resources_details['UrlTitle']).'","'.addslashes($resource_head_line_id).'","'.addslashes($resources_details['url']).'","'.addslashes($resources_details['resource_path']).'",'.$resources_details['hiddn_article_id'].','.$resources_details['imgResourceId'].',"'.$resources_details['PublishStartDate'].'","'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'. date("Y-m-d H:i:s").'",@contentid)');
	
		$result 		= $this->db->query("SELECT @contentid")->result_array();
		$resources_id 	= $result[0]['@contentid'];
		
		$resources_details['url'] = $resources_details['url']."-".$resources_id.".html";
				
		$this->db->query('CALL update_url_structure('.$resources_id.',"'.addslashes($resources_details['url']).'",6)');
		
		if ($txtStatus == 'P') {
			$resources_details['LiveResourceDetails']['url'] 			= $resources_details['url'];
			$resources_details['LiveResourceDetails']['content_id'] 	= $resources_id;
			$this->insert_live_resources($resources_details['LiveResourceDetails']);
		} 
		
	
		if ($this->db->trans_status() === FALSE && $this->live_db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				return FALSE;
		} else {
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				return TRUE;	
		}
	
	}
	
	public function insert_live_resources($resources_details) {
		$this->live_db->query('CALL add_resources(NULL,"'.addslashes($resources_details['title']).'","'.addslashes($resources_details['url']).'","'.addslashes($resources_details['resource_url']).'",'.$resources_details['article_id'].',"'.addslashes($resources_details['image_path']).'","'.addslashes($resources_details['image_caption']).'","'.addslashes($resources_details['image_alt']).'","'.$resources_details['publish_start_date'].'","'.$resources_details['last_updated_on'].'","P",'.$resources_details['content_id'].')');
	}
	
	public function update_live_resources($resources_details) {
		$this->live_db->query('CALL update_resources ("'.addslashes($resources_details['title']).'","'.addslashes($resources_details['url']).'","'.addslashes($resources_details['resource_url']).'",'.$resources_details['article_id'].',"'.addslashes($resources_details['image_path']).'","'.addslashes($resources_details['image_caption']).'","'.addslashes($resources_details['image_alt']).'","'.$resources_details['publish_start_date'].'","'.$resources_details['last_updated_on'].'","P",'.$resources_details['content_id'].')');
	}
	
	
	public function update_resources($resources_id) {
		
		extract($_POST);	
	
		$resources_details 	= $this->get_additional_resource_details();

		$this->db->trans_begin();
		$this->live_db->trans_begin();
		
		$resources_details['url'] = $resources_details['url']."-".$resources_id.".html";
		
		$this->db->query('SET @contentid = '.$resources_id.'; CALL add_update_resourcemaster(NULL,"'.addslashes($resources_details['UrlTitle']).'","'.addslashes($resource_head_line_id).'","'.addslashes($resources_details['url']).'","'.addslashes($resources_details['resource_path']).'",'.$resources_details['hiddn_article_id'].','.$resources_details['imgResourceId'].',"'.$resources_details['PublishStartDate'].'","'.$txtStatus.'","'.USERID.'","'. date("Y-m-d H:i:s").'","'.USERID.'","'. date("Y-m-d H:i:s").'",@contentid)');

		$resources_details['LiveResourceDetails']['content_id'] 	= $resources_id;
		
		if($txtStatus == 'P') {
		
		$Livecount = $this->live_content_model->check_livecontents($resources_id, 6);
					
			if($Livecount <= 0) 
				$this->insert_live_resources($resources_details['LiveResourceDetails']);
			else
				$this->update_live_resources($resources_details['LiveResourceDetails']);	
		
		}
		
		if($txtStatus == 'U')
			$this->delete_livecontents($resources_id,6);
		
		if ($this->db->trans_status() === FALSE && $this->live_db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->live_db->trans_rollback();
				return FALSE;
		} else {
				$this->db->trans_commit();
				$this->live_db->trans_commit();
				return TRUE;
		}
	
	}
	
	
	public function update_archive_resources($year, $resources_id) {
		
		extract($_POST);	
	
		$resources_details 	= $this->get_additional_resource_details();

		$this->archive_db->trans_begin();
		
		$update_archive_details = $resources_details['LiveResourceDetails'];
			
		$update_archive_details['url'] = $resources_details['url']."-".$resources_id.".html";
			
			unset($update_archive_details['ecenic_id']);
			
			$update_archive_details['image_id'] 	= $resources_details['imgResourceId'];
			$update_archive_details['modified_by'] 	=  get_userdetails_by_id(USERID);
			$update_archive_details['modified_on'] 	= $update_archive_details['last_updated_on'];
		
			$this->archive_db->where("content_id",$resources_id);
			$this->archive_db->update("resources_".$year,$update_archive_details);
		
		if ($this->archive_db->trans_status() === FALSE ) {
				$this->archive_db->trans_rollback();
				return FALSE;
		} else {
				$this->archive_db->trans_commit();
				return TRUE;
		}
	
	}
	
	public function get_additional_resource_details()

	{
		/*
		echo "<pre>";
		print_r($_POST);
		exit;
		*/
		extract($_POST);
		
		$data['resource_path'] = $ExistingResource;
		
		/*
		if($hiddn_article_id != '')
			$data['hiddn_article_id'] = $hiddn_article_id;
		else*/
		
			$data['hiddn_article_id'] = 'NULL';
		
		if ($txtPublishStartDate != '')
			$data['PublishStartDate'] = date('Y-m-d H:i', strtotime($txtPublishStartDate));
	
		if(trim($txtUrlTitle) == '')
			$data['UrlTitle'] = addslashes(trim(strip_tags($resource_head_line_id)));
		else
			$data['UrlTitle'] = trim($txtUrlTitle);
		
			$data['UrlTitle'] = RemoveSpecialCharacters($data['UrlTitle']);
			$data['UrlTitle'] = mb_strtolower(join( "-",( explode(" ",$data['UrlTitle']) )));
		
		$data['title'] = addslashes(strip_tags($resource_head_line_id));
		
			$Year =  date('Y', strtotime($data['PublishStartDate']));
			$Month =  date('M', strtotime($data['PublishStartDate']));
			$Date =  date('d', strtotime($data['PublishStartDate']));
	
			$data['url']   = $Year."/".$Month."/".$Date."/".$data['UrlTitle'];
		
			$resource_physical_name = stripslashes(RemoveSpecialCharacters($resource_physical_name));
	
		if($imgResourceId != '')
		$imgResourceId = $this->common_model->add_image_by_temp_id($resource_image_caption,$resource_image_alt,$resource_physical_name,$imgResourceId);
		
		
		if(isset($_FILES['btnResource']['tmp_name']) && $_FILES['btnResource']['tmp_name'] != '' && isset($_FILES['btnResource']['name']) && $_FILES['btnResource']['name'] != '') {
			
			$oldget =  getcwd();
			
			$Extension = strtolower(pathinfo($_FILES['btnResource']['name'], PATHINFO_EXTENSION));

			switch($Extension) {
				case 'doc':
				case 'docx':
				$Resource_Path = resource_worddocument_path;
				break;
				case 'pdf':
				$Resource_Path = resource_pdf_path;
				break;
				case 'xlsx':
				case 'xls':
				$Resource_Path = resource_excel_path;
				break;
				case 'ppt':
				case 'pptx':
				$Resource_Path = resource_ppt_path;
				break;
			}
			
			$Year = date('Y');
			$Month = date('n');
			$Day =  date('j');
						
			create_image_folder_resource( $Year, $Month, $Day,resource_path.$Resource_Path);
			
			$Resource_Path = $Resource_Path.$Year."/".$Month."/".$Day;
			
			chdir(source_base_path.resource_path.$Resource_Path);
			
			$config = array(
				'upload_path' 		=> getcwd(),
				'allowed_types' 	=> "DOC|doc|DOCX|docx|PDF|pdf|XLSX|xlsx|XLS|xls|PPT|ppt|PPTX|pptx",
				'overwrite'			=> false
			);
			
			chdir($oldget);
			$this->upload->initialize($config);
			$result_data = array();
			if (!$this->upload->do_upload('btnResource'))
			{
				$upload_data = array(
					'error' => $this->upload->display_errors()
				);
				
				$data['resource_path'] = "";
				
			}
			else
			{
				$upload_data = array(
					'upload_data' => $this->upload->data()
				);
				$data['resource_path'] = $Resource_Path."/".$upload_data['upload_data']['file_name'];
			}
			
		}
		
		if ($imgResourceId == '' || $imgResourceId == 0 ) $data['imgResourceId'] = "NULL";
		else $data['imgResourceId'] = $imgResourceId;
		
		
		$data = array_map('trim',$data);
		
		//if($txtStatus == 'P') {

			$LiveResourceDetails = array();		
						
			$LiveResourceDetails['resource_url']							= $data['resource_path'];
			
			$LiveResourceDetails['article_id']								= $data['hiddn_article_id'];	
			
			$LiveResourceDetails['image_path'] 						= '';
			$LiveResourceDetails['image_caption'] 						= '';
			$LiveResourceDetails['image_alt'] 							= '';
			
			$LiveResourceDetails['status'] 									= $txtStatus;
			$LiveResourceDetails['url']										= $data['url'];
			$LiveResourceDetails['title']									= $data['title'];
			
			$LiveResourceDetails['last_updated_on']							= date('Y-m-d H:i');
			
			if($data['imgResourceId'] != 'NULL') {
			
				$ResourceImageDetails = GetImageDetailsByContentId($data['imgResourceId']);
				
				$LiveResourceDetails['image_path'] 					= $ResourceImageDetails['ImagePhysicalPath'];
				$LiveResourceDetails['image_caption'] 					= $ResourceImageDetails['ImageCaption'];
				$LiveResourceDetails['image_alt'] 						= $ResourceImageDetails['ImageAlt'];
			}
			
			$LiveResourceDetails['publish_start_date']						= $data['PublishStartDate'];
			
			$data['LiveResourceDetails'] = array_map('trim',$LiveResourceDetails);
			
		//}
		
		return $data;

	}
	
	public function get_resources_details($resources_id) {
		$resources_manager = $this->db->query('CALL get_resources_by_id(' . $resources_id . ')');
		return $resources_manager;
	}
	
	/*
	*
	* Get the article data table using article manager page
	*
	* @access public
	* @param POST values from article manager view file
	* @return JSON format output to article manager page
	*
	*/
	public function get_resource_datatables() {
			extract($_POST);
		
		$Search_text = trim($Search_text);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

			$content_type = "1"; 
			  $menu_name		= "Resources";
		 
		 $Menu_id = get_menu_details_by_menu_name($menu_name);

		
		switch ($Field) {
    case 1:
        $order_field = 'm.title';
		$archive_field 	= 'title';
        break;
   /* case 1:
        $order_field = 'articletitle';
        break; */
	case 2:
		$order_field = 'm.resource_url';
		$archive_field = 'resource_url';
	   break;
	case 3:
		$order_field = 'im.ImagePhysicalPath';
		$archive_field = 'image_path';
	     break;
	case 4:
		$order_field = 'um.Username';
		$archive_field = 'created_by';
        break;
	case 5:
		$order_field = 'm.Modifiedon';
		$archive_field = 'modified_on';
        break;
	case 6:
		$order_field = 'm.status';
		$archive_field = 'status';
        break;
		
    default:
        $order_field = 'm.content_id';
		$archive_field = 'content_id';
		}

		$Total_rows = 250;

		$Search_value = $Search_text;
				
		if($Search_by == 'ContentId') {
		$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		$Search_value = $Search_result;
		} else {
		$Search_value = $Search_text;
		}
		
		$CurrentYear = date('Y');
		
		if ($check_in != '')
		{
			$check_in_date 	= new DateTime($check_in);
			$check_in 		= $check_in_date->format('Y-m-d');
			$CheckInYear 	=  $check_in_date->format('Y');
		}
		if ($check_out != '')
		{
			$check_out_date 	= new DateTime($check_out);
			$check_out	 		= $check_out_date->format('Y-m-d')." 23:59:59";
			$CheckOutYear 		=  $check_out_date->format('Y');
		}
				
		$Search_value = htmlentities($Search_value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		
		$Search_value =  str_replace("&#039","&#39",$Search_value);

		$article_manager =  $this->db->query('CALL resources_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Status.'")')->result_array();	
		
		$recordsFiltered = $this->db->query('CALL resources_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT 0, 250 ","'.$check_in.'","'.$check_out.'","'.addslashes($Search_value).'","'.$Search_by.'","'.$Status.'")')->num_rows();
		
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;

		foreach($article_manager as $article) {
			
			$article_image = '';

			 $edit_url = "edit_resources/".urlencode(base64_encode($article['content_id']));
			
			$subdata = array();
			$subdata[] = $article['content_id'];
			
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($article['title']).'">'.shortDescription(strip_tags($article['title'])).'</p>';
/*
			if($article['articletitle'] != '') {
			$subdata[] = '<p class="tooltip_cursor" title="'.strip_tags($article['articletitle']).'"><a href="'.$edit_article_url.'"> '.shortDescription(strip_tags($article['articletitle'])).'</a></p>';
			} else {
			$subdata[] = '-';
			}
			*/
			
			if($article['resource_url'] != '') {
				$resource_url = explode("/",$article['resource_url']);
				$subdata[] 		=  strtoupper($resource_url[0]);
			} else {
				$subdata[] = '-';
			}
			
			if($article['image_id'] != '' ) {
					if($article['ImagePhysicalPath'] != '') {
						$Image150X150 	= str_replace("original","w150X150", $article['ImagePhysicalPath']);
						$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
					} else {
						$subdata[] = '<td><i class="fa fa-picture-o"></i></td>';	
					}
				} else  {
				$subdata[] = '<td>-</td>';
				}	
			
			$subdata[] = $article['Username'];
			$change_date_format = date('d-m-Y H:i:s', strtotime($article['Modifiedon']));
			$subdata[] = $change_date_format;
			
			switch($article["status"])
			{
			case("P"):
				$status_icon = '<span data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$article['content_id'].'" data-original-title="Active"><i id="status_img'.$article['content_id'].'"  class="fa fa-check"></i></span>';
				break;
			case("U"):	
				$status_icon = '<span data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-times"></i></span>';
				break;
			case("D"):			
				$status_icon = '<span data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-floppy-o"></i></span>';
				break;	
			default;
				$status_icon = '';
			}
			
			$subdata[] = $status_icon;
			
			$set_status ='<div class="buttonHolder">';
			
				if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
					$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a>'. '';
				}
				else
					$set_status .= '';
			
				if($article["status"]=="P")
                {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { 
					$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Unpublish" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-pause"></i></a>'.'';
					}
				}
                elseif($article["status"]=="U")
                { 
			 	if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<a data-toggle="tooltip" href="#" title="Publish" class="button heart" data-original-title="" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-caret-right"></i></a>'.'';
			}
				}
				
				if($article["status"]=="P" ) {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="unpublish_checkbox[]" value="'.$article['content_id'].'" id="unpublish_checkbox_id" status ="'.$article["status"].'"    ></span>';
					}
				}
				
				if($article["status"]=="U" ) {
					if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"    title="Select"   name="publish_checkbox[]" value="'.$article['content_id'].'"   status ="'.$article["status"].'"    id="publish_checkbox_id" ></span>';
					}
				}
				
			
			if($set_status != '') {			  
			$set_status .= '</div>';
			$subdata[] = $set_status ;
			}
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
			
		if($check_in != '' && $check_out != '') {
			
			
			
			if($CheckInYear <= $CurrentYear) {
				
				$TableName = "resources_".$CheckInYear;

				
				if ($this->archive_db->table_exists($TableName)) {
					
					$ArchiveRecordsFiltered = 0;
					
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
							
							if(trim($Status) != '') 
								$this->archive_db->like("status",$Status);
							
							switch(trim($Search_by)) {
								case "Title":
								$this->archive_db->like("title",$Search_value);
								break;
								case "ContentId":
								$this->archive_db->where("content_id",$Search_value);
								break;
								case "created_by":
								$this->archive_db->like("created_by",$Search_value);
								break;
								default:
								$this->archive_db->where("( title LIKE '%".$Search_value."%' OR  created_by LIKE '%".$Search_value."%' )");
								break;
							}
							
						
							$this->archive_db->limit($length,$start);
							$this->archive_db->order_by($archive_field,$order);
							
							$Get = $this->archive_db->get();
							$archive_content_manager 	= $Get->result_array();
						
							$this->archive_db->select("*");
							$this->archive_db->from($TableName);
							$this->archive_db->where('publish_start_date >=', $check_in);
							$this->archive_db->where('publish_start_date <=', $check_out);
											
							
							if(trim($Status) != '') 
								$this->archive_db->like("status",$Status);
							
							switch(trim($Search_by)) {
								case "Title":
								$this->archive_db->like("title",$Search_value);
								break;
								case "ContentId":
								$this->archive_db->where("content_id",$Search_value);
								break;
								case "created_by":
								$this->archive_db->like("created_by",$Search_value);
								break;
								default:
								$this->archive_db->where("( title LIKE '%".$Search_value."%' OR  created_by LIKE '%".$Search_value."%' )");
								break;
							}
							
						
							$this->archive_db->limit(250,0);
							
							$Get = $this->archive_db->get();
							$ArchiveRecordsFiltered =  $Get->num_rows();
							
							
					if($ArchiveRecordsFiltered != 0 ){
						foreach($archive_content_manager as $article) {
			
			$article_image = '';

			 $edit_url = "edit_archive_resources/".$CheckInYear."/".urlencode(base64_encode($article['content_id']));
			
			$subdata = array();
			
			
			$subdata[] ='<p class="tooltip_cursor" title="'.strip_tags($article['title']).'">'.shortDescription(strip_tags($article['title'])).'</p>';

		
			if($article['resource_url'] != '') {
				$resource_url = explode("/",$article['resource_url']);
				$subdata[] 		=  strtoupper($resource_url[0]);
			} else {
				$subdata[] = '-';
			}
			

					if($article['image_path'] != '') {
						$Image150X150 	= str_replace("original","w150X150", $article['image_path']);
						$subdata[] = '<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
					} else {
						$subdata[] = '<td><a href="javascript:void()">-</a></td>';
					}
				
			
			$subdata[] = $article['created_by'];
			$change_date_format = date('d-m-Y H:i:s', strtotime($article['modified_on']));
			$subdata[] = $change_date_format;
			
			switch($article["status"])
			{
			case("P"):
				$status_icon = '<a data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$article['content_id'].'" data-original-title="Active"><i id="status_img'.$article['content_id'].'"  class="fa fa-check"></i></a>';
				break;
			case("U"):	
				$status_icon = '<a data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-times"></i></a>';
				break;
			case("D"):			
				$status_icon = '<a data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$article['content_id'].'"  data-original-title="Active"><i id="status_img'.$article['content_id'].'" class="fa fa-floppy-o"></i></a>';
				break;	
			default;
				$status_icon = '';
			}
			
			$subdata[] = $status_icon;
			
			$set_status ='<div class="buttonHolder">';
			
				if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
					$set_status .= '<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a>'. '';
				}
				else
					$set_status .= '';
			/*
				if($article["status"]=="P")
                {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { 
					$set_status .= '<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Unpublish" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-pause"></i></a>'.'';
					}
				}
                elseif($article["status"]=="U")
                { 
			 	if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<a data-toggle="tooltip" href="#" title="Publish" class="button heart" data-original-title="" content_id = '.$article['content_id'].' status ="'.$article["status"].'" name="'.strip_tags($article['title']).'" id="status_change"><i id="status'.$article['content_id'].'" class="fa fa-caret-right"></i></a>'.'';
			}
				}
				
				if($article["status"]=="P" ) {
					if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="unpublish_checkbox[]" value="'.$article['content_id'].'" id="unpublish_checkbox_id" status ="'.$article["status"].'"    ></span>';
					}
				}
				
				if($article["status"]=="U" ) {
					if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$set_status .= '<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"    title="Select"   name="publish_checkbox[]" value="'.$article['content_id'].'"   status ="'.$article["status"].'"    id="publish_checkbox_id" ></span>';
					}
				}*/
				
			
			if($set_status != '') {			  
			$set_status .= '</div>';
			$subdata[] = $set_status ;
			}
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
						
						$recordsFiltered += $ArchiveRecordsFiltered;
						
					}
				}
			}
		}
		
				
		$data['draw'] 				= $draw;
		$data["recordsTotal"] 		= $Total_rows;
		$data["recordsFiltered"] 	= $recordsFiltered;
		
		
		echo json_encode($data);
		exit;
		
	}
	
		/*
	*
	* Delete the live article details in all article based table
	*
	* @access public
	* @param content id and type (1)
	* @return TRUE
	*
	*/
	public function delete_livecontents($content_id, $type) {
		$query = $this->live_db->query("CALL delete_livecontents (". $content_id.",".$type.")");
		return $query;
	}
	
	
	
}
/* End of file resources_model.php */
/* Location: ./application/models/admin/resources_model.php */