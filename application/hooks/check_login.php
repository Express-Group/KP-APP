<?php
class Check_Login extends CI_Controller{


        function IsLoggin(){
			
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
			$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
			$this->output->set_header('Pragma: no-cache');

			$set_uset_id = $this->session->userdata('userID'); 

			
    		if($this->uri->segment('1') == folder_name) {
				
				$is_user_login_page = $this->uri->segment(2); 
				
				if($set_uset_id == "") {
					define('USERID','');
					 if(@$is_user_login_page !="clog")
					redirect(folder_name.'/clog');
				} else  {
					define('USERID',$set_uset_id);
					$this->load->model('admin/user_model');
					
					$UserDetails 			= $this->user_model->edituserdetails($set_uset_id);
				
					$FPM_MenuId 			= get_menu_details_by_menu_name('Template Designer');
					
		
					if(isset($UserDetails)) {
						foreach($UserDetails as $User) {
							
							
							
							
							if(!defined('USERACCESS_VIEW'.$User['Menu_Id']))
							define('USERACCESS_VIEW'.$User['Menu_Id'],$User['IsViewAllowed']);
							if(!defined('USERACCESS_ADD'.$User['Menu_Id']))
							define('USERACCESS_ADD'.$User['Menu_Id'],$User['IsAddAllowed']);	
							if(!defined('USERACCESS_EDIT'.$User['Menu_Id']))
							define('USERACCESS_EDIT'.$User['Menu_Id'],$User['IsEditAllowed']);	
							if(!defined('USERACCESS_DELETE'.$User['Menu_Id']))
							define('USERACCESS_DELETE'.$User['Menu_Id'],$User['IsDeleteAllowed']);	
							if(!defined('USERACCESS_PUBLISH'.$User['Menu_Id']))
							define('USERACCESS_PUBLISH'.$User['Menu_Id'],$User['IsPublishAllowed']);	
							if(!defined('USERACCESS_UNPUBLISH'.$User['Menu_Id']))
							define('USERACCESS_UNPUBLISH'.$User['Menu_Id'],$User['IsUnPublishAllowed']);
							
							
							
							$FPM_AddPageDesign 		= ($User['AddPageDesign'] == 1) ? true : false;
							$FPM_AddArticleOption 	= ($User['AddArticleOption'] == 1) ? true : false;
							$FPM_AddAdvScripts 		= ($User['AddAdvScripts'] == 1) ? true : false;
							$FPM_AddPagePublish		= ($User['AddPublish'] == 1) ? true : false;
							$FPM_AddConfig 			= ($User['AddConfig'] == 1) ? true : false;
							$FPM_AddReleaseLocks	= (isset($User['addreleaselocks']) && $User['addreleaselocks'] == 1) ? true : false;
							
							
							
							
							
						}
					}
					
		
					
					if($FPM_MenuId != '') {
					if(!defined('FPM_ADDPAGEDESIGN'))
					define('FPM_ADDPAGEDESIGN',@$FPM_AddPageDesign );
					if(!defined('FPM_ADDARTICLEOPTION'))
					define('FPM_ADDARTICLEOPTION',@$FPM_AddArticleOption); 
					if(!defined('FPM_ADDADVSCRIPTS'))
					define('FPM_ADDADVSCRIPTS',@$FPM_AddAdvScripts); 
					if(!defined('FPM_ADDCONFIG'))
					define('FPM_ADDCONFIG',@$FPM_AddConfig);
					if(!defined('FPM_AddPagePublish'))
					define('FPM_AddPagePublish',@$FPM_AddPagePublish); 
					if(!defined('FPM_AddReleaseLocks'))
					define('FPM_AddReleaseLocks',@$FPM_AddReleaseLocks); 
					} 
					
					
					
				}
			}
		 }
}
?>