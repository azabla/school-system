<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approvemark extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    $this->load->library('excel');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='approvemark' order by id ASC ");
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
  public function index($page='approvemark')
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
   
    if(isset($_POST['drop_id'])){
      $id=$this->input->post('drop_id');
      $this->main_model->inactive_staffs($id);
    }
    if(isset($_POST['post_id'])){
      $id=$this->input->post('post_id');
      $this->main_model->delete_student($id);
    }
    $data['academicyear']=$this->main_model->academic_year();
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['grade']=$this->main_model->fetch_grade($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $this->load->view('home-page/'.$page,$data);
  }
  function Filter_grade_from_branch(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('branch')){
        $branch=$this->input->post('branch');
        echo $this->main_model->fetch_grade_from_branch($branch,$max_year); 
    }
  }
  function Filtersubjectfromstaff(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;

    $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
    $rowaccessbranch = $queryAccessBranch->row_array();
    $accessbranch=$rowaccessbranch['accessbranch'];
    if($this->input->post('gradesec')){
      $gradesec=$this->input->post('gradesec');
      if($_SESSION['usertype']===trim('superAdmin') ||  $accessbranch === '1'){
        echo $this->main_model->fetch_subject_from_subjectAdmin($gradesec,$max_year);
      }else{
        echo $this->main_model->fetch_subject_from_subjectNonAdmin($gradesec,$max_year,$branch);
      }
    } 
  }
  function Fecth_grademark_4teacher(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;

    $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
    $rowaccessbranch = $queryAccessBranch->row_array();
    $accessbranch=$rowaccessbranch['accessbranch'];
    if($this->input->post('gs_gradesec')){
      $branchAdmin=$this->input->post('branch');
      $gs_gradesec=$this->input->post('gs_gradesec');
      $gs_subject=$this->input->post('gs_subject');
      $gs_quarter=$this->input->post('gs_quarter');
      if($_SESSION['usertype']===trim('superAdmin') ||  $accessbranch === '1'){
        echo $this->main_model->approveMark($branchAdmin,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
      }else{
        echo $this->main_model->approveMark($branch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
      }
    }
  }
  function approveMarkName(){
    $usertype=$this->session->userdata('usertype');
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;

    $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
    $rowaccessbranch = $queryAccessBranch->row_array();
    $accessbranch=$rowaccessbranch['accessbranch'];
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $markname=$this->input->post('markname');
      if($_SESSION['usertype']===trim('superAdmin') ||  $accessbranch === '1'){
        $this->db->where('mgrade',$gradesec);
        $this->db->where('academicyear',$year);
        $this->db->where('subname',$subject);
        $this->db->where('markname',$markname);
        $this->db->where('quarter',$quarter);
        $this->db->where('mbranch',$branch);
        $this->db->set('approved','1');
        $this->db->update('mark'.$branch.$gradesec.$quarter.$year);
        echo $this->main_model->approveMark($branch,$gradesec,$subject,$quarter,$max_year);  
      }else{
        $this->db->where('mgrade',$gradesec);
        $this->db->where('academicyear',$year);
        $this->db->where('subname',$subject);
        $this->db->where('markname',$markname);
        $this->db->where('quarter',$quarter);
        $this->db->where('mbranch',$branch_me);
        $this->db->set('approved','1');
        $this->db->update('mark'.$branch_me.$gradesec.$quarter.$year);
        echo $this->main_model->approveMark($branch_me,$gradesec,$subject,$quarter,$max_year);  
      }
    }
  }
  function fetchNewMark4Approval (){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');

    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $myBranch=$row_branch->branch;
    $usertype=$row_branch->usertype;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    $query_quarter = $this->db->query("select max(term) as mQuarter from quarter where Academic_year ='$max_year' ");
    $row_quarter = $query_quarter->row();
    $max_quarter=$row_quarter->mQuarter;
    
    
    /*if($_SESSION['usertype']!==trim('superAdmin')){*/
      if(isset($_POST['view'])){
        $show=$this->main_model->fetch_allnewMark($myBranch,$max_quarter,$max_year);
        $result['notification']=$show;
        $tot=$this->main_model->fetch_unseen_newMark($myBranch,$max_quarter,$max_year);
        $result['unseen_notification']=$tot;
        echo json_encode($result);
      }
    /*}*/
  }
}
