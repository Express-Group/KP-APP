<!DOCTYPE html>
<html lang="en">
<head>
<title>Database Error</title>
<style type="text/css">

::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	-webkit-box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
</head>
<body>
	<div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>

<?php 

$ShortMessage = $message;

$Position = strpos($ShortMessage, "Unable to connect to your database server");

$root = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	if ($Position === false) {

						$CI = & get_instance();

						$ErrorLog = $CI->db->query("CALL get_errorlogdetails('".addslashes($root)."','".addslashes($ShortMessage)."')")->row_array();
						
						$Settings = $CI->db->query('CALL select_setting()')->row_array();
						
						$BoolValue = 0;
						
						if(!isset($ErrorLog['error_id'])) {
							$BoolValue = 1;
						} else {
							
							$hourdiff = round((strtotime(date("d-m-Y h:i:s")) - strtotime($ErrorLog['errordatetime']))/3600, 1);
						
							if($hourdiff >= $Settings['timeperioderrornotification'])
								$BoolValue = 1;
						}
						
						if($BoolValue == 1) {

								$from_mail 	= $Settings['errornoitificationmailid'];
								$email_id 	= $Settings['errornoitificationmailid'];
					
								$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
									=== FALSE ? 'http' : 'https';
								$host     = $_SERVER['HTTP_HOST'];
								$script   = $_SERVER['SCRIPT_NAME'];
								$params   = $_SERVER['QUERY_STRING'];
								$currentUrl = $protocol . '://' . $host . $script;			
						
								$EmailSubject = 'Database Error in NIE Site';
								$mailheader = "From: ".$from_mail."\r\n";
								$mailheader .= "Reply-To: ".$from_mail."\r\n";
								$mailheader .= "Content-type: text/html; charset=iso-8859-1\r\n";
								
								$email_message = '
								<html>
								<head>
								  <title>Reset password</title>
								</head>
								Hi Admin, <br/> Please find the Database error in Newindianexpress Site <br/>
								<div id="container">
									<h1>'.$heading.'</h1>
										'.$message.'
								</div>
								URL : '.$root .'
								<br/> Regards,
								<br/> ENPL TEAM
								</html> ';
						
								if(mail($email_id, $EmailSubject, $email_message, $mailheader)) {
									$CI->db->query("CALL insert_errorlogdetails('".addslashes($root)."','".addslashes($ShortMessage)."','".date("Y-m-d H:i:s")."')");
									
								}
					}
	}			
?>