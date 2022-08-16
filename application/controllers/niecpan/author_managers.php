<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Author_managers extends CI_Controller {
 
 /*constructor*/
 
 
 

	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url'); 
		$this->load->model('admin/authormodel');
		$this->load->library('form_validation');
		$this->load->library('session'); 		
	} 
	
	
	public function index()
	{
		 
		$data['title']		= 'Author Manager';
		$data['template'] 	= 'Author_managers';
		$data['authors']=$this->authormodel->viewauthors();
		$this->load->view('admin_template',$data);
	}
	
	public function addauthor()
	{
		$data['title']		= 'Author Manager';
		$data['template'] 	= 'author-form';
		$userid='1';
		$type='Author';
		$data['image_value']=$this->authormodel->viewtempimage($userid, $type);
		$this->load->view('admin_template',$data);
	}
	
	public function editauthor()
	{
		$data['title']		= 'Author Manager';
		$data['template'] 	= 'edit_author';
		$userid='1';
		$type='Author';
		$data['image_value']=$this->authormodel->viewtempimage($userid, $type);
		$data['authors']= $this->authormodel->getauthor($this->uri->segment(4));
		$this->load->view('admin_template',$data);
		
	}
	
	public function author_details()
	{
		$details=new author_details();
		$details->addauthordetails();
	}
	public function upload_author_images()
	{ 
		$uploadimage=new upload_author_images();
		$uploadimage->upload_new_images(); 
	}
	
	public function deleteauthorimage()
	{
		$deleteauthorimage=new  upload_author_images();
		$deleteauthorimage->deleteauthorimage();
	}
	
	public function deletetempauthorimage()
	{
		 
		$deletetempimage=new  upload_author_images();
		echo $deletetempimage->deletetempauthorimage();
	}
	
	public function changestatus()
	{
		$status=new updatestatus();
		$status->change_author_status();
		
	}
	
	public function deleteauthors()
	{
		$status=new updatestatus();
		$status->delete_author_details();
	}
	public function searchresult()
	{
		$search=new  search_author_record();
		$search->search_record();
	}
	
	public function emailcheck()
	{
		$search=new  check_email_author();
		$search->checkemail();
	}
	
	public function author_datatables()
	{
		$authortable=new author_content_table();
		$authortable->get_content_table();
		
	}
}

class author_details extends Author_managers
{
	public function addauthordetails()
	{
			$displayname=$this->input->post('txtdisplayname');
			$email=$this->input->post('txtemail');
			$biography=$this->input->post('txtbiography');
			$this->form_validation->set_rules('txtdisplayname','Display Name','trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('txtemail','Email Address','required|xss_clean');
			$this->form_validation->set_rules('txtbiography','Biography','xss_clean');
			 
			if($this->form_validation->run() == FALSE)
			{ 
				$data['title']		= 'Author Manager';
				$data['template'] 	= 'author-form';
				$this->load->view('admin_template',$data);
			}
			else
			{  
				if($this->input->post('page')=="addauthor")
				{
					$authorids='';	
					$validate_email=$this->authormodel->validate_email($authorids, $email);
					 
					if(!empty($validate_email))
					{
						$this->db->reconnect(); 
						$error_author_data = array(
								'displayname' => $displayname,
								'email' => $email,
								'biography' => $biography, 
							);
							$this->session->set_flashdata('error', '"'.$validate_email['0']['Email'].'" is already exist');
							$this->session->set_flashdata($error_author_data);
							$data['title']		= 'Author Manager';
							$data['template'] 	= 'author-form';
							$userid='1';
							$type='Author';
							$data['image_value']=$this->authormodel->viewtempimage($userid, $type); 
							redirect('admin/Author_managers/addauthor',$data);
					}
					else
					{
							 
							$authorid='';
							$type="Author";
							$addauthor=$this->authormodel->addauthordetails($_POST, $authorid, $type); 
							$authors['authors']=$this->authormodel->viewauthors(); 
							redirect('admin/Author_managers',$authors);
					}
				} 
				else if($this->input->post('page')=="editauthor")
				{
					$authorids=$this->uri->segment(4);
					$this->db->reconnect(); 
					$validate_email=$this->authormodel->validate_email($authorids, $email);
					
					if(!empty($validate_email))
					{
							
							$this->session->set_flashdata('error', '"'.$validate_email['0']['Email'].'" is already exist'); 
							redirect('admin/Author_managers/editauthor/'.$authorids);
					}
					else
					{
							 
							$authorid=$this->uri->segment(4);
							$type="Author";
							$addauthor=$this->authormodel->addauthordetails($_POST, $authorid, $type); 
							$authors['authors']=$this->authormodel->viewauthors(); 
							redirect('admin/Author_managers',$authors);

					}
					
				}
			} 
	}
}
class upload_author_images extends Author_managers
{
		/*uploading new image function*/
		public function upload_new_images()
		{
			
				$imagefile = $_FILES['uploaduserimage']['tmp_name'];
				 
				$config = array( 
				 'upload_path' => "uploads/profile/author",				
				 'allowed_types' => "gif|jpg|png|jpeg",				
				 'encrypt_name' => TRUE				
				 );
				 
				 $this->upload->initialize($config);				
				 $result_data = array();				
				  if ( ! $this->upload->do_upload('uploaduserimage'))				
				  {				
					   $error = array('error' => $this->upload->display_errors());				
					   $result_data['message']  = $error['error'];				
					   $result_data['status']  = 0;	    	
				  }				
				  else				
				  {				
					  $data = array('upload_data' => $this->upload->data());				
					  $userid='1';  
					  $type="Author";
					  
					  ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], 45);
					   
					   $insertimage=$this->authormodel->addauthorimage($data['upload_data']['file_name'], $imagefile, $data['upload_data']['full_path'], $data['upload_data']['file_type'],$data['upload_data']['image_width'],$data['upload_data']['image_height'],$userid,$type) ;		
					    
					   if(!empty($insertimage)) 
						{
							 
							 
							$tempimages=$this->authormodel->viewauthorimage($insertimage);
							 
								?>
								  
                                    <figure>
                                    Profile Image<br />
                                   <?php
                                   echo '<img src="data:image/jpeg;base64,'.base64_encode( $tempimages['0']['image_name'] ).'"  style="width: 130px; "    border="0" />';
								   ?>
                                  
                                    </figure>
                                    <div  style="clear:both;"></div>
                                    <div class="fileUpload btn btn-primary">
                                        <span>Change</span>
                                        <input type="file" class="upload" id="uploaduserimage" name="uploaduserimage" />
                                    </div>
                                                         
                                    <input type="hidden" name="tempimageid" id="tempimageid" value="<?php echo $insertimage?>" />
									<input type="hidden" name="filestatus" id="filestatus" value="2" />
                                   
                                   
								<script>
								$(document).ready(function () {
								
								
								$("#uploaduserimage").on('change', function(e) { 
								
									var filename=$("#uploaduserimage").val();
									var ext = filename.substring(filename.lastIndexOf('.') + 1);
									if(ext == "jpg" || ext == "png" || ext == "jpeg" || ext == "JPEG" || ext == "JPG" || ext == "PNG" || ext == "GIF" || ext == "gif")
									{ 
											 var formData = new FormData();
											formData.append('userImage', $('input[type=file]')[0].files[0]);
											 
											e.preventDefault(); 
											$.ajax({
											url: "<?php echo base_url()."admin/Author_managers/upload_author_images";?>",
											type: "POST",      				 
											data:  formData,
											contentType: false,       	 
											cache: false,					 
											processData:false,  			 
											success: function(data)  		 
											{
												$("#loadingimage").hide();
												$('#call_show2').html(data); 
											}	        
											});
											 
									} 
									else
									{
											alert("Invalid file , Please upload image file"); 
											$("#uploaduserimage").focus();
											$("#uploaduserimage").val("");
											return false;
									}
								});
								
								 
								
								});
								</script>
								 
							<?php 
							 
				  }				  
				
		}
				 
		}
		
		
	public function deleteauthorimage()
	{ 
		$delelete=$this->authormodel->deleteauthorimage($this->input->post('author_id'));
		if($delelete)
		{
			echo "0";
		}
		else
		{
			echo "3";
		}
	}
	 
	public function deletetempauthorimage()
	{
		 
		$delelete=$this->authormodel->deletetempauthorimage($this->input->post('author_id'));
		if($delelete)
		{ 
			?>
            <script>
            $(document).ready(function  () { 
			
					$("#uploaduserimage").on('change', function(e) { 
	
						var filename=$("#uploaduserimage").val();
						var ext = filename.substring(filename.lastIndexOf('.') + 1);
						if(ext == "jpg" || ext == "png" || ext == "jpeg" || ext == "JPEG" || ext == "JPG" || ext == "PNG" || ext == "GIF" || ext == "gif")
						{ 
								 var formData = new FormData();
								formData.append('uploaduserimage', $('input[type=file]')[0].files[0]);
								 
								e.preventDefault(); 
								$.ajax({
								url: "<?php echo base_url()."admin/Author_managers/upload_author_images";?>",
								type: "POST",      				 
								data:  formData,
								contentType: false,       	 
								cache: false,					 
								processData:false,  			 
								success: function(data)  		 
								{
									$("#loadingimage").hide();
									$('#call_show2').html(data); 
								}	        
								});
								 
						} 
						else
						{
								alert("Invalid file , Please upload image file"); 
								$("#uploaduserimage").focus();
								$("#uploaduserimage").val("");
								return false;
						}
					});
			
			});
            </script>
			 <figure>
             Profile Image<br />
            <img src="<?php echo base_url();?>images/admin/author.jpg">
            </figure>
            
            <div class="fileUpload btn btn-primary">
                <span>Upload</span>
                <input type="file" class="upload" id="uploaduserimage" name="uploaduserimage" />
            </div>
		 <?php
		}
		else
		{
			echo "3";
		}
	}
}
 
 /*changing status*/
  /*activating and deactivating author status*/
 class updatestatus extends Author_managers
 {
	 public function change_author_status()
	 { 
				$changeid=str_replace("deactivate",'',$this->input->post('authorid'));
				if(ctype_digit($changeid))
				{  
					$value="I";
					$status=$this->authormodel->changestatus($changeid,$value);
					if($status)
					{
						echo "deactivated";
					}
					else
					{
						echo "0";
					}
				}
				else
				{
					$changeid=str_replace("activate",'',$this->input->post('authorid'));
					$value="A"; 
					$status=$this->authormodel->changestatus($changeid,$value);
					if($status)
					{
						echo "activated";
					}
					else
					{
						echo "0";
					}
				}  
	 }
	 
	 public function delete_author_details()
	 {
		 
		$changeid=$this->input->post('author_id');
		$value="D"; 
		$status=$this->authormodel->changestatus($changeid,$value);
		if($status)
		{
			echo $changeid; 
		}
		else
		{
			echo "3";
		}
	 }
 } 
 
 
 class search_author_record extends Author_managers
 {
	 public function search_record()
	 {
		$tableid=$this->input->post('tableid');
		$tablestatus=$this->input->post('tablestatus');
		$txtsearch=$this->input->post('txtsearch');
		$this->form_validation->set_rules('tableid','Search by Field Name','strip_tags|xss_clean');
		$this->form_validation->set_rules('tablestatus','Search by status','strip_tags|xss_clean');
		$this->form_validation->set_rules('txtsearch','Search Name','strip_tags|xss_clean');
		
		if($this->form_validation->run() == FALSE)
		{ 
			 echo "3";
		}
		else
		{   
			$authors=$this->authormodel->searchrecord($tableid,$tablestatus,$txtsearch); 
			if(!empty($authors))
			{
				?> 
                  
					<p class="SaveBackTop" style="float:left;"><a href="<?php echo base_url()."admin/author_managers";?>"><button class="btn-primary btn" type="button"><i class="fa fa-file-text-o"></i> &nbsp;Back To List</button></a></p>
				<table id="example" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th width="31%">Author Name</th>
					<th width="12%">Type</th>
					<th width="23%">Email ID</th>
					<th width="18%">Created On</th>
					<th width="8%">Status</th>
					<th width="8%"><div align="center">Action</div></th>
				</tr>
			</thead> 
			<tbody>
			<?php
			 
			if(!empty($authors))
			{
			if(is_array($authors))
			{
				foreach($authors as $key=>$values)
				{
				?>
				<tr>
					<td align="left"><div align="left"><?php echo $values['AuthorName'];?></div></td>
					<td><?php 
										if($values['Type']=='B')
										{
											echo "Byline";
										}
										else if($values['Type']=='C')
										{
											echo "Columnist";
										}
							?>
					</td>
					<td><?php echo $values['Email'];?></td>
					<td><?php echo $values['Createdon'];?></td>
					<td  class="CrossMark">
					<?php 
					if($values['Status']=='A')
					{
					?> 
					 <a href="#" id="<?php echo $values['Author_id']."activateimg"; ?>"><i   class="fa fa-check"></i> </a>
					
					<?php  }
					else
					{
					?> 
					<a href="#" id="<?php echo $values['Author_id']."deactivateimg"; ?>" ><i class="fa fa-times"></i></a>
					<?php } ?>
					</td>
					<td  class="buttonHolder">
						 
						 <a class="button heart" href="<?php echo base_url()."admin/Author_managers/editauthor/".$values['Author_id'];?>" data-toggle="tooltip" title="Edit"   > <i class="fa fa-pencil" ></i> </a>
						 <a class="button heart deleteauthor" href="#" data-toggle="tooltip" title="Move to Thrash" id="<?php echo $values['Author_id']; ?>"> <i class="fa fa-trash-o"></i> </a>
						 
						   
						
					 
				</td>
				</tr> 
				<?php
				}
			}
			}
			?>   
				  
			</tbody>
		</table> 
<script>
				$(document).ready(function () {
					
					$('.deleteauthor').click(function(){
 
						var status = confirm("Are you sure to delete author?");
						if(status==true)
						{ 
						 $('#loadingsel').show();
						$.ajax({
						url:'<?php echo base_url()."admin/author_managers/deleteauthors";?>',
						type:"POST",
						data:"author_id="+$(this).attr('id'),
						success: function(data) {
						 $('#loadingsel').hide();
						 if(data==3)
						 {
						 alert("Sorry you dont have permission to delete"); 
						 }
						 else
						 {
						  
						location.reload();
						}
							} 
						
						});
						return false;
						}
						else
						{
						return false;
						}
						
						});	
						 
						$('#example').DataTable();
						<?php
						if(count($authors)<11)
						{
							?>
							$(".current.paginate_button, .previous.paginate_button, .next.paginate_button").hide(); 
							<?php
						}
						?>
					 });
				</script>
				<?php
			}
			else
			{
				?>
				<tr>
					<td colspan="7" align="center"><span style="color:#900;">There is no record available for your search</span> 
                    <br />
					<p class="SaveBackTop" style="float:left;"><a href="<?php echo base_url()."admin/author_managers";?>"><button class="btn-primary btn" type="button"><i class="fa fa-file-text-o"></i> &nbsp;Back To List</button></a></p>
                    </td>
				</tr>
				<?php
			} 	    
		}
	 }
 }
 class check_email_author extends Author_managers
 {
	 public function checkemail()
	 {
		  
		 if(!empty($this->input->post("authord")))
		 {
			 $authorids=$this->input->post("authord");
		 }
		 else
		 {
			 $authorids="";
		 }
		  
		 
		 $email=$this->input->post('emailcheck');
		 
		$validate_email=$this->authormodel->validate_email($authorids, $email);
		 	 
		if(!empty($validate_email))
		{
			 echo $validate_email['0']['Email'];
		}
		else
		{
			echo "";
		}
	 }
 }
 class author_content_table extends Author_managers
 {
	 public function get_content_table()
	 {
		 $this->authormodel->get_content_datatables();
	 }
 }