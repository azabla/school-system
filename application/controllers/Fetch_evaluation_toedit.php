<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_evaluation_toedit extends CI_Controller {
  public function __construct(){
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login');
    }     
  }
  public function index($page='evaluation')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['post_id'])){
      $id=$this->input->post('post_id');
      $quarter=$this->input->post('quarter');
      $evname=$this->input->post('evname');
      echo $this->main_model->edit_evaluation($id,$quarter,$evname,$max_year);
    }
  } 
}