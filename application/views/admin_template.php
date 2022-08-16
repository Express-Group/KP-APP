<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('admin/common/header');

$this->load->view('admin/'.$template);

$this->load->view('admin/common/footer');

?>