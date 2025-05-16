<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schoolparents extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='schoolfiles' order by id ASC "); 
    if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='schoolparents')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
	}
  function fetchschoolparents(){
    if($this->input->post('branch')){
      $parentGrade=$this->input->post('parentGrade');
      $branch=$this->input->post('branch');
      $reportacaID=$this->input->post('reportacaID');
      for($i=0;$i<count($parentGrade);$i++){
        $check[]=$parentGrade[$i];
      }
      echo $this->main_model->fetchparents($check,$branch,$reportacaID); 
    }
  } 
  function Filter_grade_from_branch(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('branchit')){
        $branch=$this->input->post('branchit');
        $grands_academicyear=$this->input->post('grands_academicyear');
        echo $this->main_model->fetch_school_parents_grade($branch,$grands_academicyear); 
    }
  }
  function fetch_this_parent_child(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('username')){
      $username=$this->input->post('username');
      $year=$this->input->post('year');
      echo $this->main_model->fetch_this_parent_child($username,$year); 
    }
  }
}