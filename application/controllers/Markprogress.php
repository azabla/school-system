<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Markprogress extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $this->load->helper('security');
    $userLevel = userLevel();
    $this->db->where('usergroup',$_SESSION['usertype']);
    $this->db->where('tableName','StudentMark');
    $this->db->where('allowed','viewstudentmark');
    $uaddMark=$this->db->get('usergrouppermission');
    if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='mark_progress')
	{
   if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
  }
  $this->load->model('main_model');
  $user=$this->session->userdata('username');
  $this->db->select('branch');
  $this->db->where('username',$user);
  $query_branch=$this->db->get('users');
  $row_branch = $query_branch->row();
  $branch=$row_branch->branch;

  $this->db->select('max(year_name) as year');
  $query=$this->db->get('academicyear');
  $row = $query->row();
  $max_year=$row->year;
  $data['branch']=$this->main_model->fetch_branch($max_year);
  $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
  $data['sessionuser']=$this->main_model->fetch_session_user($user);
  $data['academicyear']=$this->main_model->academic_year_filter();
  $data['schools']=$this->main_model->fetch_school();
  $data['posts']=$this->main_model->fetch_post();
  $this->load->view('home-page/'.$page,$data);
	}
  function fetchGradeSecFromBranch(){
    $YearName = sessionAcademicYear();
    $max_year=$YearName['year'];
    if($this->input->post('branchit')){
      $branch=$this->input->post('branchit',TRUE);
      $branch=xss_clean($branch);
      echo $this->main_model->fetch_grade_from_branchAll($branch,$max_year); 
    }
  }
  function fetchCrossGradeFromBranch(){
    $YearName = sessionAcademicYear();
    $max_year=$YearName['year'];
    if($this->input->post('branchit')){
      $branch=$this->input->post('branchit',TRUE);
      $branch=xss_clean($branch);
      echo $this->main_model->fetchCrossGradeFromBranch($branch,$max_year); 
    }
  }
  function Check_markprogress(){
    $accessbranch = sessionUseraccessbranch();
    $quarterName = sessionQuarterDetail();
    $max_quarter=$quarterName['quarter'];
    $branchName = sessionUserDetailNonStudent();
    $mybranch=$branchName['branch'];
    $YearName = sessionAcademicYear();
    $max_year=$YearName['year'];
    if($this->input->post('grade')){
      $grade=$this->input->post('grade',TRUE);
      $branch=$this->input->post('branch',TRUE);
      $grade=xss_clean($grade);
      $branch=xss_clean($branch);
      for($i=0;$i<count($grade);$i++){
        $check[]=$grade[$i];
      }
      if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->check_grade_markprogress($branch,$check,$max_quarter,$max_year); 
      }else{
        echo $this->main_model->check_grade_markprogress($mybranch,$check,$max_quarter,$max_year); 
      }
    }
  }
  function Gradecrossprogress(){
    $accessbranch = sessionUseraccessbranch();
    $quarterName = sessionQuarterDetail();
    $max_quarter=$quarterName['quarter'];
    $branchName = sessionUserDetailNonStudent();
    $mybranch=$branchName['branch'];
    $YearName = sessionAcademicYear();
    $max_year=$YearName['year'];
    if($this->input->post('grade')){
      $grade=$this->input->post('grade',TRUE);
      $branch=$this->input->post('branch',TRUE);
      $grade=xss_clean($grade);
      $branch=xss_clean($branch);
      for($i=0;$i<count($grade);$i++){
        $check[]=$grade[$i];
      }
      if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->check_cross_grade_markprogress($branch,$check,$max_quarter,$max_year); 
      }else{
        echo $this->main_model->check_cross_grade_markprogress($mybranch,$check,$max_quarter,$max_year); 
      }
    }
  }
}