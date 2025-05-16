<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordersubject extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='gradeSubject' order by id ASC "); 
    if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='ordersubject')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['grade']=$this->main_model->fetch_grade($max_year);
    $data['subjects']=$this->main_model->fetch_all_subject($max_year);
    $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
    $this->load->view('home-page/'.$page,$data);
	}
  function fetchNoGrades(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetchNoGradesForOrderSubject($max_year);
  }
  function updateSubjectOrder(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('suborder')){
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $suborder=$this->input->post('suborder');
      $this->db->where('Academic_Year',$max_year);
      $this->db->where('Subj_name',$subject);
      $this->db->where('Grade',$grade);
      $this->db->set('suborder',$suborder);
      $querySubject=$this->db->update('subject');
      if($querySubject){
        echo $this->main_model->updateReportCardSubjectOrder($subject,$grade,$suborder,$max_year);
      }
    }
  }
  function fetch_subject_placement(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetch_subject_placement($max_year);
  }
  function updateSubjectPlacement(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('suborder')){
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $suborder=$this->input->post('suborder');
      $this->db->where('Academic_Year',$max_year);
      $this->db->where('Subj_name',$subject);
      $this->db->where('Grade',$grade);
      $this->db->set('subject_placement',$suborder);
      $querySubject=$this->db->update('subject');
      if($querySubject){
        echo $this->main_model->updateReportCardSubjectPlacement($subject,$grade,$suborder,$max_year);
      }
    }
  }
}