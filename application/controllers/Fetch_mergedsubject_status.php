<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_mergedsubject_status extends CI_Controller {
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
    $query= $this->main_model->fetch_mergedSubject_status($max_year);
    if(count($query)>0){
      $names_str = implode(" , ",$query);
      echo '<div class="col-md-12 col-12">
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle "></i> Missed percentage for Grade '.$names_str.'
        </div>
      </div>';
    }
  } 
}