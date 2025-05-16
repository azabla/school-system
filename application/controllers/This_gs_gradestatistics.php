<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class This_gs_gradestatistics extends CI_Controller {
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
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branchMe=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('gradesec')){
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $gradesec=$this->input->post('gradesec');
        $branch=$this->input->post('branch');
        $grands_academicyear=$this->input->post('grands_academicyear');
        echo $this->main_model->gener_report_bygrade($gradesec,$branch,$grands_academicyear); 
      }else{
        echo $this->main_model->gener_report_bygrade($gradesec,$branchMe,$grands_academicyear); 
      }
    }  
	}   
}