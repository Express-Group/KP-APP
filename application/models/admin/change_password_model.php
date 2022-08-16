<?php 
class change_password_model extends CI_Model 
{
	function get_details($userid)
	{
		$set_object=new password ;
		return $set_object->get_details($userid);
	}
	public function check_oldpassword($id)
	{
		$set_object=new password ;
		return $set_object->check_oldpassword($id);
	}
	
}
class password extends change_password_model
{
	public function get_details($userid)
	{
		echo $newpassword=$this->input->post('txtNewPassword');
		$encrypted_string = hash('sha512', $newpassword);
		//echo $encrypted_string."1111";exit;
		$this->db->reconnect();
		$password_update=$this->db->query("CALL change_password('".$encrypted_string."','".$userid."')");
		return true;
	}
	public function check_oldpassword($id)
	{
		$this->db->reconnect();
		$password_check=$this->db->query("CALL check_password('".$id."')");
		return $password_check->row_array();
		
	}
}
?>