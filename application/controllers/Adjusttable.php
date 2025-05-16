<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Adjusttable extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");
    if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='1' ){
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
  public function index($page='adjustable')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear ");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');

    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
  }
  function prepareTable(){
    $this->load->dbforge();
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $markTable=$this->main_model->prepareMarkTable($max_quarter,$max_year);
    if($markTable==='1'){
      $queryBS= $this->main_model->prepareBSTable($max_quarter,$max_year);
      if($queryBS){
        echo '<div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> Table created successfully.
       </div></div>';
     }else{
      echo '<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> Ooops Please try later.
       </div></div>';
     }
    }else if($markTable==='2'){
      echo '<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> Please set student grade , section and season to add result.
       </div></div>';
    }else if($markTable==='3'){
      echo '<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> Please set your school branch.
       </div></div>';
    }else{
      $query= $this->main_model->prepareBSTable($max_quarter,$max_year);
      if($query){
        echo '<div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> Table created successfully.
       </div></div>';
     }else{
      echo '<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> Ooops Please try later.
       </div></div>';
     }
    }
  } 
  // function prepareTable(){
  //   $this->load->dbforge();
  //   $query = $this->db->query("select max(year_name) as year from academicyear");
  //   $row = $query->row();
  //   $max_year=$row->year;
  //   $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
  //   $row2 = $query2->row();
  //   $max_quarter=$row2->quarter;
  //       $queryC='';
  //     $queryCheckUser=$this->db->query("select * from users where academicyear='$max_year' and gradesec='9A' and usertype='Student' ");
  //     foreach($queryCheckUser->result() as $row){
  //       $id=$row->id;
  //       $queryCheck=$this->db->query("select * from manualreportcardcomments where academicyear='$max_year' and stuid='$id' and subject='Social Studies (SS)' ");
  //       if($queryCheck->num_rows()<1){
  //         $queryCheck1=$this->db->query("select * from manualreportcardcomments where academicyear='$max_year' and stuid='$id' and subject='Social Studies' ");
  //         if($queryCheck1->num_rows()>0){
  //           $this->db->where('stuid',$id);
  //           $this->db->where('quarter','Term1');
  //           $this->db->where('subject','Social Studies');
  //           $this->db->set('subject','Social Studies (SS)');
  //           $queryC=$this->db->update('manualreportcardcomments');
  //         }
  //       }
  //     }
  //     if($queryC){
  //       echo 'Updated';
  //     }else{
  //       echo 'Not Updated';
  //     }
  // }
}