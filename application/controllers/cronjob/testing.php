<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Move To Archive Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

class Testing extends CI_Controller 
{	
	public function curl_check() {
		echo "Testing";
		print_r($_POST);
		exit;
	}
}