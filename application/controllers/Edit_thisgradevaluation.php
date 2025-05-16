<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_thisgradevaluation extends CI_Controller {
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

    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    
    if(isset($_POST['percent'])){
      $percent=$this->input->post('percent');
      $evname=$this->input->post('evname');
      $new_percent=$this->input->post('new_percent');
      $new_evname=$this->input->post('new_evname');
      $query=$this->main_model->edit_thisgradevaluation($percent,$max_quarter,$evname,$max_year,$new_percent,$new_evname);
      if($query){
        echo '<span class="text-success">Saved</span>';
      }else{
        echo'Oooops, Try again';
      }
    }
  } 
}