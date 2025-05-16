<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_thisgrade_paymentcategory extends CI_Controller {
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
    $this->load->model('main_model');
    $this->load->library('pdf');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('postgrade')){
      $gradesec=$this->input->post('postgrade');
      echo $this->main_model->fetch_gradepayment_category($max_year,$gradesec); 
    }  
	}   
}