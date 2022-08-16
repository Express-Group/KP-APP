<?php
class Section_model extends CI_Model 
{	  
	
	public function __construct()

	{
		parent::__construct();
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	
	function get_section_details()
	{
		$getsectiondetails=new section_author_details;
		return $getsectiondetails->get_section_details();
	}
	function get_author_details()
	{
		$getauthordetails=new section_author_details;
		return $getauthordetails->get_author_details();	
	}
	function get_order_details()
	{
		$getorederdetails=new order_details;
		return $getorederdetails->get_displayorder_details();	
	}
	function get_order_details_sub()
	{
		$getorederdetailssub=new order_details;
		return $getorederdetailssub->get_displayorder_details_subsection();	
	}
	function get_order_details_sub_new($parent_id)
	{
		$getorederdetailssub=new order_details;
		return $getorederdetailssub->get_displayorder_details_subsection_new($parent_id);	
	}
	function add_section($user_id)
	{
		$add_section_details=new add_section_details;
		return $add_section_details->add_section_data($user_id);	 
	}
	function create_page($section_id, $landing_no)
	{
		$add_section_details=new add_section_details;
		return $add_section_details->create_page($section_id, $landing_no);	 
	}
	function update_page($section_id, $status, $pagemaster_count)
	{
		$add_section_details=new add_section_details;
		return $add_section_details->update_page($section_id, $status, $pagemaster_count);	 
	}
	function get_section_data()
	{
		$get_section_details=new section_author_details;
		return $get_section_details->getdatas();	
	}
	function get_delete_section($input_section_id)
	{
		$get_section_delete=new section_author_details;
		return $get_section_delete->delete_section_model($input_section_id);
	}
	function get_edit_section_details($input_section_id)
	{
		$geteditsectiondetails=new section_author_details;
		return $geteditsectiondetails->get_editsection_details($input_section_id);	
	}
	function datatable_section()
	{
		$datatables=new datatable;
		return $datatables->pagination_datatable();	  
	}
	function get_check_section($input_section_id)
	{
		$check_id=new order_details;
		return $check_id->check_section_id($input_section_id);	  
	}
	function get_section_name()
	{
		$check_sectionname=new section_details;
		return $check_sectionname->check_section_name();	  
	}
	function get_section_name_edit()
	{
		$check_sectionname=new section_details;
		return $check_sectionname->check_section_name_edit();	  
	}
	function get_subsection_name()
	{
		$check_subsectionname=new section_details;
		return $check_subsectionname->check_subsection_name();	  
	}
	function get_subsection_name_edit()
	{
		$check_subsectionname=new section_details;
		return $check_subsectionname->check_subsection_nameedit();	  
	}
	function check_url_sectionname()
	{
		$check_subsectionname=new section_details;
		return $check_subsectionname->check_url_sectionname();	  
	}
	function checksectionid_details($hid_txtSectionId)
	{
		$check_subsectionname=new checktables;
		return $check_subsectionname->checksectionid_details($hid_txtSectionId);	  
	}
	function get_seperate_menu()
	{
		$set_object= new seperatemenu;
		return $set_object->get_seperate_menu(); 
	}
	function get_main_menus()
	{
		$set_object= new seperatemenu;
		return $set_object->get_main_menus();   
	}
	function get_displaorders()
	{
		$set_object= new display_order;
		return $set_object->get_displaorders();  
	}
	function get_displaordersedit()
	{
		$set_object= new display_order;
		return $set_object->get_displaordersedit();  
	}
	function get_sectionrecords()
	{
		$set_object= new checktables;
		return $set_object->get_sectionrecords(); 
		
	}
	function check_childrows($input_section_id)
	{
		$set_object= new order_details;
		return $set_object->check_childrows($input_section_id); 	
	}
	function get_menu() 
	{
		$set_object= new menu_structure;
		return $set_object->get_menu(); 
	}
	function get_display_order()
	{
		$set_object= new get_orders;
		return $set_object->get_display_order(); 
	}
	function get_sections()
	{
		$set_object= new sections;
		return $set_object->get_sections(); 
	}
	function get_section_by_id($input_section_id)
	{
		$set_object= new sections;
		return $set_object->get_section_by_id($input_section_id); 
		
	}
	function getlast_sectionname()
	{
		$set_object= new sections;
		return $set_object->getlast_sectionname(); 
	}
	function get_parent_sectionname()
	{
		$set_object= new sections;
		return $set_object->get_parent_sectionname(); 
	}
	function get_author_by_id()
	{
		$set_object= new sections;
		return $set_object->get_author_by_id(); 
	}
	function getPageDetails()
	{
		$set_object= new sections;
		return $set_object->getPageDetails(); 
	}
}
class section_author_details extends Section_model
{
	function getdatas() // To Get List Of The Sections	
	{		
		
		$section_select_pro = $this->db->query("CALL section_select()");
		return $section_select_pro->result_array();
	}  
	function get_section_details()  // To get section Details For Listing in Dropdown
	{
		
		$section_pro = $this->db->query("CALL section_namedetails()"); 
		return $section_pro->result_array();
	}
	function get_author_details()// To get Author Details For Listing in Dropdown
	{
		
		$section_author= $this->db->query("CALL section_getauthorname()");
		return $section_author->result_array(); 
	}
	function delete_section_model($input_section_id)//to delete section details
	{
		
		$section_getdedetails= $this->db->query("CALL section_editdetails('".$input_section_id."')")->row_array();
		$Displayorder= $section_getdedetails['DisplayOrder'];
		$section_id= $section_getdedetails['ParentSectionID'];
		$article_pge= $section_getdedetails['Article_page'];
		
		
		$section_rows= $this->db->query("CALL get_section('".$section_id."')")->num_rows();
		//echo $section_rows;exit;
		
		
		//echo "CALL section_delete('".$input_section_id."','".$Displayorder."','".$section_id."'";exit;
		$section_delete_pro = $this->db->query("CALL section_delete('".$input_section_id."','".$Displayorder."','".$section_id."','".$section_rows."','".$article_pge."')");
		if($section_delete_pro == TRUE)
		{
			//echo $section_check_child = $this->db->query("CALL checksection_child('".$input_section_id."')")->num_rows();exit;
			$this->session->set_flashdata('success_delete', 'Section details deleted successfully');
			redirect(base_url().'niecpan/section_manager');
		}
	}
	function get_editsection_details($input_section_id)//to edit the section details
	{
		//echo $input_section_id;exit;
		
		$section_edit= $this->db->query("CALL section_editdetails('".$input_section_id."')");
		return $section_edit->result_array(); 
	}
}
class order_details extends Section_model
{
	function get_displayorder_details()
	{
		
		$section_order= $this->db->query("CALL section_getsectionorder()");
		return $section_order->result_array(); 
	}
	function get_displayorder_details_subsection()
	{
		
		$subsection_order= $this->db->query("CALL section_getsubsectionorder()");
		return $subsection_order->result_array(); 
	}
	function get_displayorder_details_subsection_new($parent_id)
	{
		$subsection_order= $this->db->query("CALL section_getsubsectionorder_new('".$parent_id."')");
		return $subsection_order->result_array(); 
	}
	function check_section_id($input_section_id)
	{
		
		$section_check_pro = $this->db->query("CALL Check_sectionid('".$input_section_id."')")->num_rows();
		
		$section_check_child = $this->db->query("CALL checksection_child('".$input_section_id."')")->num_rows();
		if($section_check_pro > 0)
		{
			$this->session->set_flashdata('failure_delete', 'Section already mapped in other tables. It cannot be deleted');
			redirect(base_url().'niecpan/section_manager'); 
		}
		elseif($section_check_child > 0)
		{
			$this->session->set_flashdata('failure_delete', 'Child exists for this Section.It cannot be deleted');
			redirect(base_url().'niecpan/section_manager'); 
		}
		else
		{
				$this->get_delete_section($input_section_id); 
			
		}
	}
	function check_childrows($input_section_id)
	{
		
		$section_check_child = $this->db->query("CALL checksection_child('".$input_section_id."')");
		return $section_check_child->num_rows();
	}
}
class section_details extends Section_model
{
	public function check_section_name()//fn to check existance of section name
	{
		$section_name = addslashes(trim($this->input->post('get_sec_name'))); 
		$section_name = $this->db->query("CALL check_sectionname('".$section_name."')");
		return $section_name->num_rows();
	}
	public function check_section_name_edit()
	{
		$section_name = addslashes(trim($this->input->post('get_sec_name')));
		$section_id   = $this->input->post("section_id");
		$section_name = $this->db->query("CALL check_sectionnameedit('".$section_name."','".$section_id."')");
		return $section_name->num_rows();
	}
	public function check_subsection_name()//fn to check existance of sub section name
	{
		$section_name = addslashes(trim($this->input->post('get_sec_name')));
		$subsection_name = $this->input->post('check_parnt_sectn');
		//echo "CALL check_subsectionname('".$subsection_name."','".$section_name."','".$seperatewebsite."')";exit;
		$subsection_name = $this->db->query("CALL check_subsectionname('".$subsection_name."','".$section_name."')");
		return $subsection_name->num_rows();
	}
	
	public function check_subsection_nameedit()
	{
		$section_name = addslashes(trim($this->input->post('get_sec_name')));
		$section_id   = $this->input->post('section_id');
		$subsection_name = $this->input->post('check_parnt_sectn');
		
		$subsection_name = $this->db->query("CALL check_subsectionnameedit('".$subsection_name."','".$section_name."','".$section_id."')");
		return $subsection_name->num_rows();
	}
	public function check_url_sectionname(){
	   	$url_section_name = addslashes(trim($this->input->post('url_section')));
		$section_id       = $this->input->post('section_id');
		if($section_id==''){
		$urlsection_count = $this->db->query("CALL check_url_sectionname('".$url_section_name."')")->num_rows();
		}else{
		$urlsection_count = $this->db->query("CALL check_url_sectionnameedit('".$url_section_name."','".$section_id."')")->num_rows();
		}
		return $urlsection_count;
	}
}

class add_section_details extends Section_model
{
	 function do_uploads()//Function to upload the images
	  {
		  	$config['upload_path'] = './uploads/section/';
		  	$config['allowed_types'] = 'gif|jpg|png';
		  	$config['max_size'] = '2000';
	
		  	$this->load->library('upload');
			$this->upload->initialize($config);
	
		  	if (!$this->upload->do_upload('fileBackgroundImage'))
		  	{
					$error_msg = array('error' => $this->upload->display_errors());
					$data['title']		= 'Section create';
					$data['template'] 	= 'create_section';
					$this->load->view('admin_template',$data);
		  	}
		  	else
		  	{
					$data = $this->upload->data();
					//ImageJPEG(ImageCreateFromString(file_get_contents($data['full_path'])),$data['full_path'], 45);
					$file_path= 'uploads/section/';
					return $file_path.$data['raw_name'].$data['file_ext'];
		  	}
			
	  }
	  public function add_section_data($user_id)//to add and update the section details
  	  {
		   $txtSectionId = $this->input->post('txtSectionId');
		   
		   $current_displayorder = $this->db->query("CALL get_section_by_id('".$txtSectionId."')")->row_array();
		   if(count($current_displayorder)>0)
				$update_do=$current_displayorder['DisplayOrder'];
			else
				$update_do='';
		  
		  	$get_image = $_FILES['fileBackgroundImage']['tmp_name'];
			$image_path = "";
			if($get_image != "")
			{
				$image_path = $this->do_uploads(); // upload section page image to file folder
			}
			else
			{
				$image_path ="";
			}
			$url_sectionname = trim(strip_tags($this->input->post('txturlsectionname')));
			$txturlsectionname = join( "-",( explode(" ", $url_sectionname ) ) );
			
			$txtSectionId = $this->input->post('txtSectionId');
			$txtSectionName = trim($this->input->post('txtSectionName'));
			$htmlSectionName = $this->input->post('txtSectionName');
			$editor_values = trim($this->input->post('txtSectionName'));
			$html_sectionvalues= trim($this->input->post('txtSectionName'));
			$plain_sectionvalues= trim(strip_tags($editor_values));
			$image= trim($this->input->post('imgRemoved'));
			$section_url_structure_name = trim(strip_tags($txturlsectionname));
			$section_url_structure = join( "-",( explode(" ", $section_url_structure_name ) ) );
			$chkSubSection = $this->input->post('chkSubSection'); 
			$chkSeperateSection = $this->input->post('chkSeperateMenu');
			$articleremoved =trim($this->input->post('txtArticle'));
			$pagemaster_count = $this->input->post('pagecount'); 
			$ckb_hosting="0";
			if($this->input->post('ckb_hosting')==1)
			{
				$ckb_hosting="1";
			}
			
			
			
			if($articleremoved==1)
			{
				$section_landing="1";
			}
			else
			{
				$section_landing="2";	
			}
			
			$ddSectionName = 'NULL';
			if($chkSubSection == 1) 
			{ 
				 $ddSectionName= addslashes($this->input->post('ddSectionName'));
				 $ddParentsection = trim(strip_tags($this->input->post('txtParentName')));
				// $txtgrandParentName = trim(strip_tags($this->input->post('txtgrandParentName')));
				 if($ddParentsection!= 'noParent'){
				 $section_url_structure = $ddParentsection."/".$section_url_structure;
				 }
			}
			//print_r($section_url_structure);exit;
			
			if($chkSubSection== 1)
			{
				$chkSubSection=1;
			}
			else
			{
				$chkSubSection=0;
				
			}
			
	
			$chklinkedColumn = $this->input->post('chkColumnist'); 
			$ddColumnist = "";
			if($chklinkedColumn == 1) 
			{ 
				 $ddColumnist= $this->input->post('ddColumnist'); 	
				 $author_img_path = $this->input->post('columnist_img');
				 $author_bio_graph = trim($this->input->post('chkColumnist_biograph'));
				 
			}
			else
			{
				$ddColumnist= 'NULL';
				$author_img_path = '';
				$author_bio_graph = '';
			}
	        
			$optHighLight = $this->input->post('optHighLight');
			$optRss = $this->input->post('optRss');
			
			$optLinkType = $this->input->post('view3');
			
			$txtMetaTitle = ""; $txtMetaDesc = ""; $txtMetaKeyword = ""; 
			$chkSocialOpenGraphTag = ""; $chkSocialTwitterTag = ""; $chkSocialSchemaTag = "";
			$chkCrawlerNoIndex = ""; $chkCrawlerNoFollow = "";
			$txtCanonicalUrl = "";
			$txtExternalLink = "";
			if($optLinkType == "I") 
			{
				$txtMetaTitle= addslashes($this->input->post('txtMetaTitle'));
				$txtMetaDesc =$this->db->escape_str($this->input->post('txtMetaDesc'));	
				$txtMetaKeyword =$this->db->escape_str($this->input->post('txtMetaKeyword')); 
				$chkSocialOpenGraphTag = $this->input->post('chkSocialOpenGraphTag');
				$chkSocialTwitterTag = $this->input->post('chkSocialTwitterTag');
				$chkSocialSchemaTag = $this->input->post('chkSocialSchemaTag');
				$chkCrawlerNoIndex=$this->input->post('chkCrawlerNoIndex');
				$chkCrawlerNoFollow=$this->input->post('chkCrawlerNoFollow');
				$txtCanonicalUrl = $this->input->post('txtCanonicalUrl');
			}
			else
			{
				$txtMetaTitle= addslashes($this->input->post('txtMetaTitle'));
				$txtMetaDesc = $this->db->escape_str($this->input->post('txtMetaDesc'));	
				$txtMetaKeyword = $this->db->escape_str($this->input->post('txtMetaKeyword'));
				$chkSocialOpenGraphTag = $this->input->post('chkSocialOpenGraphTag');
				$chkSocialTwitterTag = $this->input->post('chkSocialTwitterTag');
				$chkSocialSchemaTag = $this->input->post('chkSocialSchemaTag');
				$chkCrawlerNoIndex=$this->input->post('chkCrawlerNoIndex');
				$chkCrawlerNoFollow=$this->input->post('chkCrawlerNoFollow');
				$txtCanonicalUrl = $this->input->post('txtCanonicalUrl');
				$txtExternalLink = $this->input->post('txtExternalLink');
			}
			
   			$section_land="1";
			$ddDisplayOrder = $this->input->post('ddDisplayOrder');
			$optStatus = $this->input->post('view4');
			$optVisibility = $this->input->post('view5');
			$Createdby = 1;
			date_default_timezone_set('Asia/Calcutta');
			$Createdon=date('Y-m-d h:i:s');
			$Modifiedby = 1;
			date_default_timezone_set('Asia/Calcutta');
			$Modifiedon = date('Y-m-d h:i:s');
			
			if($txtSectionId == "")   // insert if section id  is empty 
			{
				$optdisplayoption = $this->input->post('view9');
                   if($ddDisplayOrder!="")
				      {
						$displayorder = $this->db->query("CALL checkdisplayorder('".$ddDisplayOrder."',".$ddSectionName.")")->row_array();
						$Do=$displayorder['DisplayOrder'];
						if($Do == $ddDisplayOrder)
						{
							$displayOrder = $ddDisplayOrder;
							$do=$this->db->query("CALL max_order(".$ddSectionName.")")->row_array();
							$order= $do['MAX(DisplayOrder)'];
						}
					}else{
						$displayOrder = $this->input->post('ddDisplayOrderHidden');
						$optdisplayoption="";
						$order = '';	
					}
					
					$this->db->trans_begin();
					$this->live_db->trans_begin();

					$section_insertquery = $this->db->query("CALL section_insert('".$plain_sectionvalues."','".$chkSubSection."',".$ddSectionName.",".$ddColumnist.",'".$optHighLight."','".$optRss."','".$txtExternalLink."','".$displayOrder."','".$image_path."','".$optStatus."','".$txtMetaTitle."','".$txtMetaDesc."','".$txtMetaKeyword."','".$chkCrawlerNoIndex."','".$chkCrawlerNoFollow."','".trim($txtCanonicalUrl)."','".$user_id."','".$Createdon."','".$user_id."','".$Modifiedon."', '".$chkSeperateSection."','".$optVisibility."','".$section_landing."','".addslashes($htmlSectionName)."','".$order."','".$optdisplayoption."', '".$section_url_structure."', @insert_id, '".$txturlsectionname."', '".$ckb_hosting."')");	
					   $result 	= $this->db->query("SELECT @insert_id")->row_array();
			            $section_id = $result['@insert_id'];

						if($chkSubSection== 1)
						{
							$section_land_update =  $this->db->query("CALL sectionland_update(".$ddSectionName.",'".$section_land."')"); 
						}
						if($section_landing && $section_id)
						{
							$this->create_page($section_id, $section_landing);
						}
						// insert section details to live database
					$section_live_insertquery = $this->live_db->query("CALL section_insert('".$plain_sectionvalues."','".$chkSubSection."',".$ddSectionName.",".$ddColumnist.",'".$optHighLight."','".$optRss."','".$txtExternalLink."','".$displayOrder."','".$image_path."','".$optStatus."','".$txtMetaTitle."','".$txtMetaDesc."','".$txtMetaKeyword."','".$chkCrawlerNoIndex."','".$chkCrawlerNoFollow."','".trim($txtCanonicalUrl)."','".$user_id."','".$Createdon."','".$user_id."','".$Modifiedon."', '".$chkSeperateSection."','".$optVisibility."','".$section_landing."','".addslashes($htmlSectionName)."','".$order."','".$optdisplayoption."', '".$section_url_structure."', '".$author_img_path."', '".$author_bio_graph."', '".$txturlsectionname."', '".$section_id."', '".$ckb_hosting."')");	
						
						if($chkSubSection== 1)
						{
							$section_land_update =  $this->live_db->query("CALL sectionland_update(".$ddSectionName.",'".$section_land."')"); 
						}
						
						if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							$this->live_db->trans_rollback();
							return FALSE;
						}
						else {
							$this->db->trans_commit();
							$this->live_db->trans_commit();
							return TRUE;
						}
			}
			else    // update section in section master
			{
				if($ddDisplayOrder=="")
				{
					$ddDisplayOrder=$update_do;
				}
				
				$optdisplayoption = $this->input->post('view8');
				$displayorder = $this->db->query("CALL checkdisplayorderedit('".$ddDisplayOrder."',".$ddSectionName.",'".$txtSectionId."')")->row_array();
				if(count($displayorder)>0){
					$Do=$displayorder['DisplayOrder'];
					$id=$displayorder['Section_id'];
					$order = '';
					if($Do == $ddDisplayOrder && ($optdisplayoption=="R" || $optdisplayoption=="C" ) )
					{
						$do=$this->db->query("CALL max_order(".$ddSectionName.")")->row_array();
						$order= $do['MAX(DisplayOrder)'];
					}
				  }else{
					    $optdisplayoption="";
						$order = '';
					}
					
					$this->db->trans_begin();
					$this->live_db->trans_begin();

					$section_updatequery = $this->db->query("CALL section_update('".$txtSectionId."','".$plain_sectionvalues."','".$chkSubSection."',".$ddSectionName.",".$ddColumnist.",'".$optHighLight."','".$optRss."','".$txtExternalLink."','".$ddDisplayOrder."','".$image_path."','".$optStatus."','".$txtMetaTitle."','".$this->db->escape_str($txtMetaDesc)."','".$this->db->escape_str($txtMetaKeyword)."','".$chkCrawlerNoIndex."','".$chkCrawlerNoFollow."','".trim($txtCanonicalUrl)."','".$user_id."','".$Modifiedon."', '".trim($chkSeperateSection)."','".$optVisibility."','".$section_landing."','".addslashes($htmlSectionName)."','".$order."','".$optdisplayoption."','".$image."','".$update_do."', '".$section_url_structure."', '".$txturlsectionname."', '".$ckb_hosting."')");
					
					// update section details to live database
					$section_live_updatequery = $this->live_db->query("CALL section_update('".$txtSectionId."','".$plain_sectionvalues."','".$chkSubSection."',".$ddSectionName.",".$ddColumnist.",'".$optHighLight."','".$optRss."','".$txtExternalLink."','".$ddDisplayOrder."','".$image_path."','".$optStatus."','".$txtMetaTitle."','".$this->live_db->escape_str($txtMetaDesc)."','".$this->live_db->escape_str($txtMetaKeyword)."','".$chkCrawlerNoIndex."','".$chkCrawlerNoFollow."','".trim($txtCanonicalUrl)."','".$user_id."','".$Modifiedon."', '".trim($chkSeperateSection)."','".$optVisibility."','".$section_landing."','".addslashes($htmlSectionName)."','".$order."','".$optdisplayoption."','".$image."','".$update_do."', '".$section_url_structure."', '".$author_img_path."', '".$this->live_db->escape_str($author_bio_graph)."', '".$txturlsectionname."', '".$ckb_hosting."')");
					
					
					$update_structure = $this->db->query("CALL section_urlstructure_cursor('".$txtSectionId."','".$section_url_structure."', ".USERID.")");
					$update_structure = $this->live_db->query("CALL section_urlstructure_cursor('".$txtSectionId."','".$section_url_structure."', ".USERID.")");
					//echo $this->db->last_query();
					//exit;
					if(($pagemaster_count == 1 || $pagemaster_count == 0) ){
					$this->update_page($txtSectionId, 'create', $pagemaster_count);
					}else if($section_landing == 1 && ($pagemaster_count == 2) ){
					$this->update_page($txtSectionId,'update', $pagemaster_count);
					}else if($section_landing == 2 && ($pagemaster_count == 2) ){
					$this->update_page($txtSectionId,'active', $section_landing);
					}
					
					if ($this->db->trans_status() === FALSE || $this->live_db->trans_status() === FALSE) 
					   {
						$this->db->trans_rollback();
						$this->live_db->trans_rollback();
						return FALSE;
						}
						else {
							$this->db->trans_commit();
							$this->live_db->trans_commit();
							return TRUE;
						}
			}
	  } 

    public function create_page($get_sec_id, $section_landing_no)
	{
		$this->db->query('CALL insert_section_page("'.$get_sec_id.'", "'.$section_landing_no.'", '.USERID.', @inserted_id)');
		$page_insert 	= $this->db->query("SELECT @inserted_id")->row_array();
		$page_id = explode(',', $page_insert['@inserted_id']);
		$page_id1 = $page_id[0];
		$page_id2 = $page_id[1];
		$this->live_db->query('CALL insert_section_page("'.$get_sec_id.'", "'.$section_landing_no.'" , '.USERID.', "'.$page_id1.'", "'.$page_id2.'")');
		return TRUE;
	}
	 public function update_page($get_sec_id, $status, $pagemaster_count)
	{
		$this->db->query('CALL update_section_page("'.$get_sec_id.'","'.$status.'", '.USERID.', "'.$pagemaster_count.'", @inserted_id)');
		$page_insert 	= $this->db->query("SELECT @inserted_id")->row_array();
		$page_id = explode(',', $page_insert['@inserted_id']);
		if($pagemaster_count==0)
		{
		$page_ids  = implode(',',$page_id);
		}elseif($pagemaster_count==1)
		{
		$page_ids = $page_id[0];
		}
		$this->live_db->query('CALL update_section_page("'.$get_sec_id.'","'.$status.'", '.USERID.', "'.$pagemaster_count.'", "'.$page_ids.'")');
		return TRUE;
	}
}

class datatable extends Section_model
{
	public function pagination_datatable()//fn to list datatable details
	{
		
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		case 0:
			$order_field = 't1.Sectionname';
			break;
		case 1:
			$order_field = 't1.URLSectionName';
			break;
		case 2:
			$order_field = 't2.Sectionname';
			break;
		case 3:
		   $order_field = 'Username';
			break;
		case 4:
		   $order_field = 't1.Createdon';
			break;
		case 5:
		   $order_field = 't1.Status';
			break;
   		default:
        $order_field = 't1.section_id';
		}
		
		
		$Total_rows = $this->db->query('CALL section_datatable("","","","'.$filterby.'","")')->num_rows();
		
		
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$searchtxt= htmlspecialchars(trim($searchtxt));
		$searchtxt = addslashes(str_replace("'", "''", $searchtxt));
		
		$article_manager =  $this->db->query('CALL section_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->result_array();	

        // echo $this->db->last_query();

		
		$recordsFiltered =  $this->db->query('CALL section_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'")')->num_rows();
	//echo $this->db->last_query(); exit;	
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		$data['Menu_id'] = get_menu_details_by_menu_name("Section");
		
		foreach($article_manager as $section)
		{
			$subdata = array();
			$subdata[] = $section['Sectionname'];
			$subdata[] = $section['URLSectionName'];
			$subdata[] = $section['parentName'];
			$subdata[] = $section['Username'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($section['Createdon']));
			if($section['Status']==1)
			$subdata[] = '<td><i title="Active"  class="fa fa-check"></i></td>';
			else
			$subdata[] = '<td><i title="Inactive" class="fa fa-times"></i></td>';
			
			/*$subdata[] ='<a class="button tick"  href="'.base_url().'admin/section_manager/class_add_section_view/'.$section['Section_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>
			  <a class="button tick" href="#" data-toggle="tooltip" title="Move to Thrash" onclick="delete_section('.$section['Section_id'].')"  id="'.$section['Section_id'].'"> <i class="fa fa-trash-o"></i> </a>'; 
			  
			  
			  ***********************************/
			  
			  $set_rights = "";
			  
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick"  href="'.base_url().'niecpan/section_manager/class_add_section_view/'.$section['Section_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1)
			  {
			  $set_rights .= '<a class="button tick" href="#" data-toggle="tooltip" title="Move to Thrash" onclick="delete_section('.$section['Section_id'].',\''.$section['Sectionname'].'\')"  id="'.$section['Section_id'].'"> <i class="fa fa-trash-o"></i> </a></div>'; 
			  }
			  else 
			  { 
			  	$set_rights.="";
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
class checktables extends Section_model
{
	function checksectionid_details($hid_txtSectionId)
	{
		
		$subsection_name = $this->db->query("CALL check_section('".$hid_txtSectionId."')");
		return $subsection_name->num_rows();
	}
	function get_sectionrecords()
	{
		
		$subsection_name = $this->db->query("CALL get_sectionrows()");
		return $subsection_name->num_rows();
		
	}
	
	function get_displayorder()
	{
		$ddDisplayOrder = $this->input->post('ddDisplayOrder');
		
		$displayorder = $this->db->query("CALL checkdisplayorder('".$ddDisplayOrder."',".$ddSectionName.")")->row_array();
		$Do=$displayorder['DisplayOrder'];
		$id=$displayorder['Section_id'];
		
	}
}
class seperatemenu extends Section_model
{
	
	function get_seperate_menu()//fn to get menu list
	{
		
		$get_id_spl=$this->input->post('spl_menu_id');
		if($get_id_spl!="0")
		{
			$get_id=$this->input->post('spl_menu_id');
		}
		else
		{
			$get_id="";
		}
		$specila=1;
		
		$special_menu = $this->db->query('CALL get_seperatemenu("'.$specila.'","'.$get_id.'")'); 
		$get_result= $special_menu->result_array();
		foreach($get_result as $key => $get_main_splsection)
		{
			
			$get_sec_id = $get_main_splsection['Section_id'];
			$spl_main= $this->db->query('CALL get_seperatemenu("'.$specila.'","'.$get_sec_id.'")');
			$get_main_splsection['spl_main_section'] = $spl_main->result_array();
			$get_result[$key] = $get_main_splsection;
		}
		return $get_result;
	}
	function get_main_menus()
	{
		$get_id_spl=$this->input->post('spl_menu_id');
		$specila=1;
		
		$special_menu = $this->db->query("CALL get_seperatemenu('".$specila."','".$get_id_spl."')"); 
		$get_result= $special_menu->result_array();
		return $get_result;
	}
}
class display_order extends Section_model
{
	public function get_displaorders()//fn to get display order
	{
		$displayorder=$this->input->post('order');
		$hdn_sectionid=$this->input->post('section_id');
		$check_values=$this->input->post('chek_val');
		$menu_id=$this->input->post('menu_id');
		if($check_values=="parent")
		{
			$get_id_spl="0";
		}
		else
		{
			$get_id_spl = $this->input->post('menu_id');
		}
		 $displayorder = $this->db->query("CALL checkdisplayorder('".$displayorder."','".$get_id_spl."')");
		 $get_result= $displayorder->row_array();
		 return $get_result;
	}
	
	
	public function get_displaordersedit()//fn to get display order
	{
		$displayorder=$this->input->post('order');
		$hdn_sectionid=$this->input->post('section_id');
		$check_values=$this->input->post('chek_val');
		$menu_id=$this->input->post('menu_id');
		if($check_values=="parent")
		{
			$get_id_spl="0";
		}
		else
		{
			$get_id_spl=$menu_id=$this->input->post('menu_id');
		}
				 
		 $displayorder = $this->db->query("CALL checkdisplayorder('".$displayorder."','".$get_id_spl."')");
		 $get_result= $displayorder->row_array();
		 return $get_result;
	}
	
	
	
	
	
	
}
class menu_structure extends Section_model
{
		
	public function get_menu()
	{
		  
		$empty_val = '';    
		$list_multi_sectn  = $this->db->query('CALL get_section_sitemap("'.$empty_val.'")');
		$get_result   = $list_multi_sectn->result_array();
	
		foreach($get_result as $key => $get_multi_section)
		{
			 
			 $get_sec_id = $get_multi_section['Section_id'];
			 $list_multi_sectn      = $this->db->query('CALL get_section_sitemap("'.$get_sec_id.'")');  
			 $get_multi_section['sub_section']  = $list_multi_sectn->result_array();
			 foreach($get_multi_section['sub_section'] as $subkey => $subsection_page)
			 {
				$get_subsec_id = $subsection_page['Section_id'];
				$get_special= $subsection_page['IsSeperateWebsite'];
			
				  
				  //echo 'CALL get_seperatemenu ("'.$get_special.'", "'.$get_subsec_id.'")';
				  $special_section_details  = $this->db->query('CALL get_seperatemenu ("'.$get_special.'", "'.$get_subsec_id.'")')->result_array();
				  $specila_list = array();
				  $subsection_page['special_section'] = $special_section_details;
				  $get_multi_section['sub_section'][$subkey] = $subsection_page;
				// print_r( $$special_section_details );   
			 }
			 $get_result[$key] = $get_multi_section;
		}
		

   return $get_result;
	}
	
}
class get_orders extends Section_model
{
	public function get_display_order()
	{
		$displayorder=$this->input->post('order');
		$hdn_sectionid=$this->input->post('section_id');
		$check_values=$this->input->post('chek_val');
		$menu_id=$this->input->post('menu_id');
		if($check_values=="parent")
		{
			$get_id_spl="";
		}
		else
		{
			$get_id_spl= $this->input->post('menu_id');
		}
		 
		 //echo 'CALL get_max_order("'.$get_id_spl.'","'.$hdn_sectionid.'")';exit;
		 $get_do = $this->db->query('CALL get_max_order("'.$get_id_spl.'","'.$hdn_sectionid.'")');
		 $get_result= $get_do->row_array();
		 return $get_result;  
	}
	
	
}
class sections extends Section_model
{
	public function get_sections()
	{
		$displayorder=$this->input->post('order');
		$hdn_sectionid=$this->input->post('section_id');
		$check_values=$this->input->post('chek_val');
		$menu_id=$this->input->post('menu_id');
		if($check_values=="parent")
		{
			$get_id_spl="";
		}
		else
		{
			$get_id_spl=$menu_id=$this->input->post('menu_id');
		}
				  
		$empty_val = '';    
		$list_multi_sectn  = $this->db->query('CALL get_section_displayorder("'.$get_id_spl.'")');
		$get_result   = $list_multi_sectn->result_array();
		return $get_result; 
		
	}
	public function get_section_by_id($input_section_id)
	{
		   $txtSectionId = $this->input->post('section_id');
		   
		   $current_displayorder = $this->db->query("CALL get_section_by_id('".$input_section_id."')");
		   return $current_displayorder->result_array();
		  // $update_do=$current_displayorder['DisplayOrder'];
	}
	
	public function getlast_sectionname()
	{
		
		$displayorder=$this->input->post('order');
		$hdn_sectionid=$this->input->post('section_id');
		$check_values=$this->input->post('chek_val');
		$menu_id=$this->input->post('menu_id');
		if($check_values=="parent")
		{
			$get_id_spl="";
		}
		else
		{
			$get_id_spl=$menu_id=$this->input->post('menu_id');
		}
				 
		 //echo 'CALL get_max_order("'.$get_id_spl.'","'.$hdn_sectionid.'")';exit;
		 $get_do = $this->db->query('CALL get_last_sectionname("'.$get_id_spl.'")');
		 $get_result= $get_do->row_array();
		 return $get_result;  
	}
	
	public function get_parent_sectionname()
	{
		$child_section_id = $this->input->post('Psection_id');
		 $get_do = $this->db->query('CALL get_parent_sectionname("'.$child_section_id.'")');
		 $get_result= $get_do->row_array();
		 return $get_result;
	}
	public function get_author_by_id()
	{
		$authorid = $this->input->post('columnist_id');
		$author_details = $this->db->query('CALL get_author_by_id(' . $authorid . ')');
		$row_array = $author_details->row_array();
		return $row_array;
	}
	public function getPageDetails()
	{
		$section_id = $this->input->post('section_id');
		$sec_page_details = $this->db->query("CALL get_pagemaster_record_count('".$section_id."')");
		return $sec_page_details->row_array();
	}
}