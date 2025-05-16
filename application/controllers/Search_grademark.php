<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_grademark extends CI_Controller {
  public function __construct(){
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('login/');
    }    
  }
	public function index()
	{
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(quarter) as quarter from mark");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('form')){
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $form=$this->input->post('form');
        echo $this->main_model->search_grademark($form,$max_year,$max_quarter); 
      }else{
        $grade2place=$this->input->post('grade2place');
        echo $grade2place; 
      }
    }  
	}   
}