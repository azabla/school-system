<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetchroster extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('login/');
    }  
  }
	public function index()
	{
    $this->load->model('Reportcard_model');
    $this->load->library('pdf');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('gradesec')){
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->Reportcard_model->roster($max_year,$gradesec,$branch); 
      }else{
        echo $this->Reportcard_model->roster($max_year,$gradesec,$mybranch); 
      }
    }  
	}   
}