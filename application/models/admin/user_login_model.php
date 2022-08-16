<?php
class user_login_model extends CI_Model
{
	public function check_login_details()
	{
		$get_username = $this->input->post('username');
		$get_password = $this->input->post('password');
		$encrypted_pwd = hash('sha512', $get_password);
		
		$login_details = $this->db->query("CALL check_login('".trim($get_username)."','".trim($encrypted_pwd)."')")->row_array();

		if(count($login_details) > 0)
		{
			if($login_details['status'] == 1) 
			{
				$get_user_id = $login_details['User_id'];
				$firstname = $login_details['Firstname'];
				$lastname = $login_details['Lastname'];
	
				$user_id = $this->session->set_userdata("userID",$get_user_id);
				$user_firstname = $this->session->set_userdata("first_name",$firstname);
				$user_lastname = $this->session->set_userdata("last_name",$lastname);
				echo "success";
			}
			else
			{
				echo "inactive";
			}
		}
		else
		{
			echo "invalid";
		}
	}
	
	
	public function forgot_password()
	{
		$get_username = $this->input->post('username');
		
		$forgot_pwd = $this->db->query("CALL check_login('".trim($get_username)."', '')")->row_array();

		if(count($forgot_pwd) > 0)
		{
			$get_user_id = $forgot_pwd['User_id'];
			$firstname = $forgot_pwd['Firstname'];
			$lastname = $forgot_pwd['Lastname'];
			$email_id = $forgot_pwd['MailId'];
			
			$from_mail = 'info@vidyutinfo.com';
			
						$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
							=== FALSE ? 'http' : 'https';
						$host     = $_SERVER['HTTP_HOST'];
						$script   = $_SERVER['SCRIPT_NAME'];
						$params   = $_SERVER['QUERY_STRING'];
						$currentUrl = $protocol . '://' . $host . $script;
						
						$mail_url = base_url().'niecpan/clog/reset_password_page/'.urlencode(base64_encode($get_user_id));
						
						$reset_url = base_url().urlencode(base64_encode('niecpan/clog/reset_password_page/')).urlencode(base64_encode($get_user_id));
							
						$body='Hi '.$firstname.' '.$lastname. ',<br/> <br/>Please click the  following link to reset your New Indian Express login password.<br/> <br/> <a href="'.$mail_url.'">'.$reset_url.'</a><br/> <br/><br/> <br/><br/> <br/>Regards,<br/> NIE Team.';
				
						$EmailSubject = 'Request to reset password';
						$mailheader = "From: ".$from_mail."\r\n";
						$mailheader .= "Reply-To: ".$from_mail."\r\n";
						$mailheader .= "Content-type: text/html; charset=iso-8859-1\r\n";
						
				$message = '
					<html>
					<head>
					  <title>Reset password</title>
					</head>
					<body>
					  <table border="0" cellpadding="5px">
					 <tr><td>'.$body.'</td></tr>
					  </table>
					</body>
					</html> ';
					
					if(@mail($email_id, $EmailSubject, $message, $mailheader))
					{
						echo 'mail sent';
					}
					else
					{
						echo  "Mail not sent. Try again later";
					}
		}
		else
		{
			echo "invalid";
		}
	}
	
	
	public function reset_user_password($user_id)
	{
		$newpassword=trim($this->input->post('password'));
		$encrypted_string = hash('sha512', $newpassword);
		$this->db->reconnect();
		$password_update=$this->db->query("CALL change_password('".$encrypted_string."','".$user_id."')");
		
		$pwd = $this->db->affected_rows();
		if($password_update)
		{
			echo 'changes password';
		}
		else
		{
			echo 'failed';
		}
		
	}
}
?>