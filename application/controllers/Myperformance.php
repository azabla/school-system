<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myperformance extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPV=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='viewmyperformance' order by id ASC ");
    if($this->session->userdata('username') == '' || $usergroupPV->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='myperformance')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $teid=$row_branch->id;
    $user_division=$row_branch->status2;
    $data['week']=$this->main_model->fetch_permitted_week($max_year,$user_division);
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['subjects']=$this->main_model->fetch_all_subject($max_year);
    $data['subj4merged']=$this->main_model->fetchAllSubject4Forged($max_year);
    $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
    $data['usertype']=$this->main_model->fetch_usertype();
    $data['perGroup']=$this->main_model->fetchPerformanceGroup($max_year);
    $this->load->view('teacher/'.$page,$data);
	} 
  function loadMyPerformance(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $teid=$row_branch->id;
    $user_division=$row_branch->status2;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;  
    if(isset($_POST['activityYear'])){
      $activityYear=$this->input->post('activityYear');
      $activity_week=$this->input->post('activity_week');
      echo $this->main_model->fetchStaffPerformResultTeacher($activityYear,$activity_week,$max_year,$teid,$user_division);
    }
  }
  function sign_agreement(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('agreed_week')){
      $agreed_week=$this->input->post('agreed_week');
      $teaid=$this->input->post('teaid');
      $data=array(
        'teaid'=>$teaid,
        'week_agreed'=>$agreed_week,
        'agreed_status'=>'1',
        'academicyear'=>$max_year,
        'date_agreed'=>date('M-d-Y')
      );
      echo $this->db->insert('teacher_performance_agremment',$data);
    }
  }

}