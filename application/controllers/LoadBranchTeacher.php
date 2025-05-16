<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoadBranchTeacher extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    if($this->session->userdata('username') == '' || $userLevel!='1'){
      $this->session->set_flashdata("error","Please Login first");
      $this->load->driver('cache');
      delete_cookie('username');
      unset($_SESSION);
      session_destroy();
      $this->cache->clean();
      ob_clean();
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
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('hoomroombranch')){
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $hoomroombranch=$this->input->post('hoomroombranch');
        echo $this->main_model->fetchThisBranchTeacher($hoomroombranch);
      }else{
        echo $this->main_model->fetchThisBranchTeacher($branch);
      }
    }  
  }   
}