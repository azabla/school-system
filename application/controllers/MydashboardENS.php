<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mydashboard extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('gs_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    if($this->session->userdata('username') == '' || $userLevel!='3'){
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
  public function index($page='mydashboard')
  {
    if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');

    $query_gradesec = $this->db->query("select * from users where username='$user' and academicyear='$max_year' ");
    $row_gradesec = $query_gradesec->row_array();
    $grade=$row_gradesec['grade'];
    $gradesec=$row_gradesec['gradesec'];
    $id=$row_gradesec['id'];
    $branch1=$row_gradesec['branch'];

    $query_quarter = $this->db->query("select max(term) as quarter from quarter where Academic_year ='$max_year' ");
    $row_quarter = $query_quarter->row();
    $max_quarter=$row_quarter->quarter;

    $data['fetch_term']=$this->gs_model->fetch_term_student($max_year,$grade);
    $data['sessionuser']=$this->gs_model->fetch_session_user($user);
    $data['academicyear']=$this->gs_model->academic_year_filter();
    $data['subject']=$this->gs_model->my_subject($max_year,$grade);
    $data['schools']=$this->gs_model->fetch_school();
    $this->load->view('student/'.$page,$data);
     
  }
  function fetchMyAttendance(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->gs_model->fetchDashboarAttendance($max_year,$user);
  }
  function fetchMyMark(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    $query_gradesec = $this->db->query("select * from users where username='$user' and academicyear='$max_year' ");
    $row_gradesec = $query_gradesec->row();
    $grade=$row_gradesec->grade;
    $gradesec=$row_gradesec->gradesec;
    $id=$row_gradesec->id;
    $branch1=$row_gradesec->branch;
    $fName=$row_gradesec->fname;
    $mName=$row_gradesec->mname;
    $lName=$row_gradesec->lname;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgrade='$grade' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    echo $this->gs_model->fetchDashboardMarkResultENS($branch1,$gradesec,$max_quarter,$grade,$max_year,$id,$fName,$mName,$lName); 
     
  }
  function filter_grade4branchplacement(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    $query_gradesec = $this->db->query("select * from users where username='$user' and academicyear='$max_year' ");
    $row_gradesec = $query_gradesec->row();
    $grade=$row_gradesec->grade;
    $gradesec=$row_gradesec->gradesec;
    $id=$row_gradesec->id;
    $branch1=$row_gradesec->branch;
    $fName=$row_gradesec->fname;
    $mName=$row_gradesec->mname;
    $lName=$row_gradesec->lname;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgrade='$grade' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $this->db->where('academicyear',$max_year);
    $queryCheck = $this->db->get('enableapprovemark');
    if($this->input->post('selectQuarter')){
      $max_quarter=$this->input->post('selectQuarter');
      echo $this->gs_model->fetchDashboardMarkResultENS($branch1,$gradesec,$max_quarter,$grade,$max_year,$id,$fName,$mName,$lName); 
    }
  }
}