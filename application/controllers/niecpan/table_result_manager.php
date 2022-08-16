<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class table_result_manager extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/table_result_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Table Result");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Table Result Manager';
			$rawdata['template'] 	= 'table_result_manager';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/table_result_manager');
		}
	}
	public function table_result_table_view()
	{
		$set_object=new table_result_table;
		$set_object->table_result_table_view();
	}

	public function table_details()
	{
		$set_object=new add_tabledetails ;
		$set_object->table_details();	
	}
	public function edit_details()
	{
		$set_object=new edit_tableresultdetails ;
		$set_object->edit_details();	
	}
	public function table_result_datatable()
	{
		$this->table_result_model->table_result_datatable();
	}
	public function table_result_delete()
	{
		$set_object=new table_result_table;
		$set_object->table_result_delete();
	}
	public function delete_record()
	{
		$set_object=new table_result_table;
		$set_object->delete_record();
	}
	public function check_tabletypename()
	{
		$dptexist= $this->table_result_model->table_data_name_check();
		if($dptexist > 0)
		{
			echo "Table data name already exists";
			return FALSE;
		}	
		else
		{
			return TRUE;
		}

	}
	
}

class table_result_table extends table_result_manager
{
	public function table_result_table_view()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Table Result');
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Create Table data';
			$rawdata['template'] 	= 'table_data_create';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect('niecpan/common/access_permission/create_tableresultdata');
		}
	}
	public function table_result_delete()
	{
		$input_t_id = $this->uri->segment(4);
		$this->table_result_model->tableresult_delete($input_t_id);
	}
	public function delete_record()
	{
		return "Ok";
	}
}


class add_tabledetails extends table_result_manager
{
	public function table_details()
	{
		  //print_r($_POST['table_data']);exit;
		  $hdn_t_id= $this->input->post('t_id');
		  $table_name = $this->input->post('table_data_name');
		  $table_heading = $this->input->post('table_data_heading');
		  $no_of_column = $this->input->post('noofcolumns');
		  
		  $this->form_validation->set_rules('table_data_name','Table Name required','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('table_data_heading','Table Heading required','trim|required|strip_tags|xss_clean');
		  $this->form_validation->set_rules('noofcolumns','maximum 10 columns allowed','integer|required|max_length[10]');
		   
		  if($this->form_validation->run() == FALSE)
		  { 
			  $data['title']		= 'Create Table data';
			  $data['template'] 	= 'table_data_create';
			  $this->load->view('admin_template',$data);
		  }	
		  else
		  {
			 $result = $this->table_result_model->add_table_details(USERID);
			echo $result;
		  }
	}
}
class edit_tableresultdetails extends table_result_manager
{
	public function edit_details()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Table Result');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1) 
		{
			$t_id = $this->uri->segment(4);
			$final_array['table_details']= $this->table_result_model->edittableresultdetails($t_id); 
			//$final_array = array_merge($rawdata,$roleeditdetails,$topic);
			$final_array['title']		= 'Edit Table Result data ';
			$final_array['template'] 	= 'table_data_create';
			$this->load->view('admin_template',$final_array);
		}
		else 
		{
			redirect('niecpan/common/access_permission/edit_tableresultdata');
		}
	}
	
}

?>