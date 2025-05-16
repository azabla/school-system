<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_evaluation_status extends CI_Controller {
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
    $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
    if($queryCheck->num_rows()>0){
      foreach($queryCheck->result() as $maxQuarter){
        $termgroup=$maxQuarter->termgroup;
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' and termgroup='$termgroup' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $query= $this->main_model->fetch_evaluation_status($max_year,$max_quarter);
        if(count($query)>0){
          $names_str = implode(" , ",$query);
          echo '
            <span class="text-danger">
              <i class="fas fa-exclamation-triangle "></i> Sum of evaluation percentage must be `100` for grade '.$names_str.'
            </span>';
        }
      }
    }
  } 
}