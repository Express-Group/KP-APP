<?php
class Strip_slasses{
	
  private $CI;

  public function __construct(){
    $this->CI =& get_instance();
  }
  
  public function common(){
	
	$output = $this->CI->output->get_output();
	$output = stripslashes($output);
	$output = str_replace(['/logo/nie_logo_600X390.jpg' , '/logo/nie_logo_600X300.jpg' , '/logo/nie_logo_150X150.jpg' , '/logo/nie_logo_100X65.jpg' , '/logo/nie_logo_900X450.jpg'] , ['/logo/nie_logo_600X390.webp' , '/logo/nie_logo_600X300.webp' , '/logo/nie_logo_150X150.webp' , '/logo/nie_logo_100X65.webp' , '/logo/nie_logo_900X450.webp'] , $output);
	$this->CI->output->_display($output);
  }
}
?>