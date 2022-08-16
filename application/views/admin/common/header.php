<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $title; ?></title>
<link rel="shortcut icon" href="<?php echo image_url; ?>images/FrontEnd/images/favicon.ico" type="image/x-icon" />
<!--<link href="<?php echo image_url ?>css/admin/font-awesome.min.css" rel="stylesheet" type="text/css" />
--><link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo image_url ?>css/admin/dashboard-style.css" rel="stylesheet" type="text/css" />
<script src="<?php echo image_url ?>js/jquery-1.11.3.min.js"></script>
<script>
var folder_name = "<?php echo folder_name; ?>";
var image_url = "<?php echo image_url; ?>";
</script>

<!-- Message Toaster Start -->
<link href="<?php echo image_url ?>css/admin/toastr.css" rel="stylesheet" type="text/css" />
<script src="<?php echo image_url ?>js/toastr.js"></script>
<script type="text/javascript">
toastr.options = {
					  "closeButton": false,					  
					  "newestOnTop": true,					  
					  "positionClass": "toast-top-center",					  
				}
</script>
<!-- Message Toaster Start -->

</head>

<body>
<div class="loading" id="body_loading">
  <div>
    <div class="c1"></div>
    <div class="c2"></div>
    <div class="c3"></div>
    <div class="c4"></div>
  </div>
  <span>Please wait while cropping is in progress</span>
</div>

<div class="loading" id="commom_loading">
  <div>
    <div class="c1"></div>
    <div class="c2"></div>
    <div class="c3"></div>
    <div class="c4"></div>
  </div>
  <span id="loading_msg"></span>
</div>

<div class="loading" id="normal_loading">
  <div>
    <div class="c1"></div>
    <div class="c2"></div>
    <div class="c3"></div>
    <div class="c4"></div>
  </div>
  <span>Loading...</span>
</div>
<div style="position:relative;">
<div class="HeaderWrapper">
<div class="Container HeaderContainer">
<div class="FloatLeft LogoWrapper"><a href="<?php echo base_url().folder_name; ?>">ENPL - CMS </a></div>
 
<div class="main-top FloatLeft">
<?php $MenuDetails = header_menu(); 

foreach($MenuDetails as $main_menu )
{
	$menu_name=$main_menu['MenuName'];
	$urlname = $main_menu['URLName'];
	$menu_id = $main_menu['Menu_id'];
?>
<ul class="menu-list">
<?php if(!empty($main_menu['sub_menu'])) 
{ 

$SubBoolean = false;
foreach($main_menu['sub_menu'] as $get_sub_menu) { 
 $sub_menu_id 	=  $get_sub_menu['Menu_id'];
 if(defined("USERACCESS_VIEW".$sub_menu_id) && constant("USERACCESS_VIEW". $sub_menu_id) == 1) {
	 $SubBoolean = true;	
 }
 // Template Designer
 if($get_sub_menu['MenuName'] == 'Template Designer') {
	  if(defined("FPM_ADDPAGEDESIGN") && ( constant("FPM_ADDPAGEDESIGN") == true ||   constant("FPM_ADDARTICLEOPTION") == true  ||   constant("FPM_ADDADVSCRIPTS") == true ||   constant("FPM_ADDCONFIG") == true) ) {
		   $SubBoolean = true;
	  }
 }
 // Template Designer
}

if($SubBoolean == true) { ?>
   <li>
        <?php echo $menu_name; ?>
     	<ul>
     		<?php foreach($main_menu['sub_menu'] as $get_sub_menu) 
			{ 
				 $sub_nemu_name = $get_sub_menu['MenuName'];
				 $sub_menu_urlname = $get_sub_menu['URLName'];
				 $sub_menu_id 	=  $get_sub_menu['Menu_id'];
				 
			if(defined("USERACCESS_VIEW".$sub_menu_id) && constant("USERACCESS_VIEW". $sub_menu_id) == 1) { ?>
        	<a href="<?php echo base_url().folder_name;?>/<?php echo $sub_menu_urlname; ?>" class="is-template-version-saved" ><?php echo $sub_nemu_name; ?></a>
        	<?php }
					
										
 if($sub_nemu_name == 'Template Designer') {
	 if(defined("FPM_ADDPAGEDESIGN") && ( constant("FPM_ADDPAGEDESIGN") == true ||   constant("FPM_ADDARTICLEOPTION") == true  ||   constant("FPM_ADDADVSCRIPTS") == true ||   constant("FPM_ADDCONFIG") == true) ) {?>
     <a href="<?php echo base_url().folder_name;?>/<?php echo $sub_menu_urlname;?>" class="is-template-version-saved" ><?php echo $sub_nemu_name; ?></a>
	 <?php
	 }
 }
			
			 } ?>
 		</ul>
  	</li>
 <?php } }
  else 
  {  
 
  if(defined("USERACCESS_VIEW".$menu_id) && constant("USERACCESS_VIEW". $menu_id) == 1) { ?>
  		<a href="<?php echo base_url().folder_name;?>/<?php echo $urlname;?>"  class="is-template-version-saved" ><?php echo $menu_name; ?></a>
 <?php  }  
 
 
 } ?>
</ul>
<?php } ?>





<ul class="FloatRight menu-list LogOut">
<?php  if($this->session->userdata('userID')) {?>
  <li>
   Welcome <?php echo $this->session->userdata('first_name')." ". $this->session->userdata('last_name'); ?>

  </li>
  <li title="Logout"> <a href="<?php echo base_url().folder_name; ?>/clog/logout" class="is-template-version-saved"><i class="fa fa-sign-out"></i></a></li>
<?php } ?> 
 <!-- <li><i class="fa fa-bell-o"></i><span class="Flag">10</span></li>
 <li><i class="fa fa-flag-o"></i><span class="Alarm">9</span></li>
 <li class="UserImg"><img src="<?php //echo image_url ?>images/admin/user.jpg"></li>
 <li class="logoff"><a href="<?php //echo base_url(); ?>admin/user_login/logout"><i class="fa fa-power-off"></i></a></li>-->
 
</ul>
<ul class="FloatRight menu-list">

  <li><a class="padding-0 is-template-version-saved" href="<?php echo base_url().folder_name; ?>/change_password">Change Password</a></li>
</ul>


 
 </div>
</div>
</div>
</div>