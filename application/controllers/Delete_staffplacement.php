<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete_staffplacement extends CI_Controller {
  public function __construct(){
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login');
    }     
  }
  public function index()
  {
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('staff_placement')){
      $staff_placement=$this->input->post('staff_placement');
      $this->main_model->delete_placement($staff_placement);
    }
  } 
}