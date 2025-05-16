<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myperformancestatus extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='performancestatus' order by id ASC "); 
    if($this->session->userdata('username') == '' || $usergroupP->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='myperformancestatus')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['week']=$this->main_model->fetch_week($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['subjects']=$this->main_model->fetch_all_subject($max_year);
    $data['subj4merged']=$this->main_model->fetchAllSubject4Forged($max_year);
    $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
    $data['perGroup']=$this->main_model->fetchPerformanceGroup($max_year);
    $data['usertype']=$this->main_model->fetch_usertype_dailytasks();
    $this->load->view('teacher/'.$page,$data);
	} 
  function fetch_performance_onoff(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $user_division=$row_branch->status2;
    echo $this->main_model->fetch_performance_onoff_non_admin($max_year,$user_division);
  }
  function fetch_signed_nonsigned_staffs(){
    $userType=$this->session->userdata('usertype');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
    $rowaccessbranch = $queryAccessBranch->row_array();
    $accessbranch=$rowaccessbranch['accessbranch'];
    if(isset($_POST['activityYear'])){
      $activityYear=$this->input->post('activityYear');
      $activityBranch=$this->input->post('activityBranch');
      $activity_week=$this->input->post('activity_week');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->fetch_signed_nonsigned_staffs($activityYear,$activityBranch,$activity_week);
      }else{
        echo $this->main_model->fetch_signed_nonsigned_staffs_myDirector($activityYear,$branch,$activity_week,$user);
      }
    }
  }
}