<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myngmark extends CI_Controller {
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
	public function index($page='ngmarkresult')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
     show_404();
    }
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(quarter) as quarter from mark");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $today=date('y-m-d');
    $data['gre']=$this->main_model->fetch_grade_from_staffplaceDir($user,$max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $this->load->view('teacher/'.$page,$data);
	}
  function filterSubjectToNGMarkShow(){
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
      $grade2analysis=$this->input->post('grade2analysis');
      for($i=0;$i<count($grade2analysis);$i++){
        $gradeGsanalysis[]=$grade2analysis[$i];
      }
      echo $this->main_model->filterSubjectToNGMarkShow($mybranch,$gradeGsanalysis,$max_year); 
    }
  }
  function filterQuarterToNGMarkShow(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('grade2analysis')){
      $grade2analysis=$this->input->post('grade2analysis');
      for($i=0;$i<count($grade2analysis);$i++){
        $grade[]=$grade2analysis[$i];
      }
      echo $this->main_model->filterQuarterToNGMarkShow($grade,$max_year); 
    } 
  }
  function fetchNullMark(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    if($this->input->post('gs_gradesec')){
      $gs_gradesec=$this->input->post('gs_gradesec');
      $gs_subject=$this->input->post('gs_subject');
      $gs_quarter=$this->input->post('gs_quarter');

      for($i=0;$i<count($gs_gradesec);$i++){
        $grade[]=$gs_gradesec[$i];
      }
      for($i=0;$i<count($gs_subject);$i++){
        $subject[]=$gs_subject[$i];
      }

      if(trim($_SESSION['usertype'])===trim('Director')){
        echo $this->main_model->outof_error($branch,$grade,$subject,$gs_quarter,$max_year); 
      }else{
        echo $this->main_model->outof_error($branch,$grade,$subject,$gs_quarter,$max_year); 
      }
    }
  }
}