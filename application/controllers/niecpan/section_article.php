<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Section_article extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/section_model');
	}
	public function index()
	{
		$set_object=new sitemap;
		$set_object->view_sitemap();
	}
}
class sitemap extends Section_article
{
	public function view_sitemap()
	{
		$section['menu'] 		= $this->section_model->get_menu();
		$section['template'] 	= 'section_article';
		$section['title']		= 'Section Article';
		
		$this->load->view('admin_template',$section);
	}

}

