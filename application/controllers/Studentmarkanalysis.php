<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentmarkanalysis extends CI_Controller {
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
	public function index($page='subjectmarkanalysis')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
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
    if($_SESSION['usertype']===trim('Director')){
      $data['gradesec']=$this->main_model->fetch_grade_from_staffplace4Director($user,$max_year);
    }else{
      $data['gradesecTeacher']=$this->main_model->fetch_session_gradesec($user,$max_year);
    }
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $this->load->view('teacher/'.$page,$data);
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
    if($this->input->post('gradesec')){
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $evaluation=$this->input->post('evaluation');
      $data1=$this->main_model->fetchSubjectMarkAnalysis($mybranch,$gradesec,$quarter,$evaluation,$max_year); 
      $record= $this->main_model->fetchSubjectMarkAnalysisGraph($mybranch,$gradesec,$quarter,$evaluation,$max_year);
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
    if($this->input->post('grade2analysis')){
      $gradesec=$this->input->post('grade2analysis');
      $quarter=$this->input->post('analysis_quarter');
      if($_SESSION['usertype']===trim('Director')){
        echo $this->main_model->filterSubject4Analysis($mybranch,$gradesec,$max_year,$quarter);
      }else{
        echo $this->main_model->filterSubject4TeAnalysis($mybranch,$gradesec,$max_year,$user);
      } 
    }
  }
}
