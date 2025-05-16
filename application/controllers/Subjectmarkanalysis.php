<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subjectmarkanalysis extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='viewstudentmark' order by id ASC ");
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
	public function index($page='subjectmarkanalysis')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
  }
  $user=$this->session->userdata('username');
  $query_branch = $this->db->query("select * from users where username='$user'");
  $row_branch = $query_branch->row();
  $branch=$row_branch->branch;
  $query = $this->db->query("select max(year_name) as year from academicyear");
  $row = $query->row();
  $max_year=$row->year;
  $data['fetch_term']=$this->main_model->fetch_term($max_year);
  $data['branch']=$this->main_model->fetch_branch($max_year);
  $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
  $data['sessionuser']=$this->main_model->fetch_session_user($user);
  $data['academicyear']=$this->main_model->academic_year_filter();
  $data['schools']=$this->main_model->fetch_school();
  $data['posts']=$this->main_model->fetch_post();
  $this->load->view('home-page/'.$page,$data);
	} 
  function fetch_analysis(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
     $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branch')){
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $evaluation=$this->input->post('evaluation');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $data1=$this->main_model->fetchSubjectMarkAnalysis($branch,$gradesec,$quarter,$evaluation,$max_year); 
        $record= $this->main_model->fetchSubjectMarkAnalysisGraph($branch,$gradesec,$quarter,$evaluation,$max_year);
      }else{
        $data1= $this->main_model->fetchSubjectMarkAnalysis($mybranch,$gradesec,$quarter,$evaluation,$max_year);
        $record= $this->main_model->fetchSubjectMarkAnalysisGraph($mybranch,$gradesec,$quarter,$evaluation,$max_year);
      }
      $data2 =array();
      foreach($record as $row) {
        $data2[] = array(
          'language'    =>  $row["fname"],
          'total'     =>  $row["total"],
          'color'     =>  '#' . rand(100000, 999999) . ''
        );
      }
      $variable = array('data1' => $data1,'data2' => $data2 );
      echo json_encode($variable);
    }
  }
  function fetchGradeSubject(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('grade2analysis')){
      $gradesec=$this->input->post('grade2analysis');
      $branch=$this->input->post('branch2analysis');
      $quarter=$this->input->post('analysis_quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->filterSubject4Analysis($branch,$gradesec,$max_year,$quarter); 
      }else{
        echo $this->main_model->filterSubject4Analysis($mybranch,$gradesec,$max_year,$quarter); 
      }
    }
  }
}