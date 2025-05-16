<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mymarkprogress extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='viewstudentmark' order by id ASC ");
    if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='2'){
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
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data['grade']=$this->main_model->fetch_mygradeDirector($user,$max_year,$branch);
    $data['grade_dir']=$this->main_model->fetch_mygradeDirector_progress($user,$max_year,$branch);
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $data['gre']=$this->main_model->fetch_grade_from_staffplaceDir($user,$max_year);
    $this->load->view('teacher/'.$page,$data);
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
      $grade=$this->input->post('grade');
      $branch=$this->input->post('branch');
      for($i=0;$i<count($grade);$i++){
        $check[]=$grade[$i];
      }
      echo $this->main_model->check_grade_markprogress($mybranch,$check,$max_quarter,$max_year); 
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
      $grade=$this->input->post('grade');
      for($i=0;$i<count($grade);$i++){
        $check[]=$grade[$i];
      }
      if(trim($_SESSION['usertype'])===trim('superAdmin')){
        echo $this->main_model->check_cross_grade_markprogress($branch,$check,$max_quarter,$max_year); 
      }else{
        echo $this->main_model->check_cross_grade_markprogress($mybranch,$check,$max_quarter,$max_year); 
      }
    }
  }
}