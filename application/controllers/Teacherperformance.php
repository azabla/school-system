<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacherperformance extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='manageperformance' order by id ASC "); 
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
	public function index($page='teacherperformance')
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
    $branch=$row_branch->branch;
    $user_division=$row_branch->status2;
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['subjects']=$this->main_model->fetch_all_subject($max_year);
    $data['subj4merged']=$this->main_model->fetchAllSubject4Forged($max_year);
    $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
    $data['usertype']=$this->main_model->fetch_usertype();
    $data['perGroup']=$this->main_model->fetchPerformanceGroup($max_year);
    $data['week']=$this->main_model->fetch_week($max_year);
    $data['staffs']=$this->main_model->fetch_my_staffs($user,$branch,$user_division);
    $data['my_staffs']=$this->main_model->fetch_my_staffs_performance($user,$branch,$user_division,$max_year);
    $this->load->view('teacher/'.$page,$data);
	}
  function fetchStaffToPerformActivity(){
    $userType=$this->session->userdata('usertype');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    if(isset($_POST['activityYear'])){
      $activityYear=$this->input->post('activityYear');
      $activity_week=$this->input->post('activity_week');
      $activity_staffs=$this->input->post('activity_staffs');
      echo $this->main_model->fetchStaffToPerformActivity($activityYear,$branch,$activity_week,$activity_staffs,$userType);
    }
  }
  function updateTeacherPerformance(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['value'])){
      $value=trim($this->input->post('value'));
      $teid=trim($this->input->post('teid'));
      $acname=trim($this->input->post('acname'));
      $groupName=trim($this->input->post('GroupName'));
      $activity_week=trim($this->input->post('activity_week'));
      $data=array(
        'teid'=>$teid,
        'acid'=>$acname,
        'pervalue'=>$value,
        'per_week'=>$activity_week,
        'actiGroup'=>$groupName,
        'academicyear'=>$max_year,
        'datecreated'=>date('M-d-Y'),
        'valueby'=>$user
      );
      $queryCheck=$this->db->query("select acid,pervalue from teacherperfvalue where academicyear='$max_year' and acid='$acname' and teid='$teid' and per_week='$activity_week' ");
      if($queryCheck->num_rows()>0){
        $this->db->where('teid',$teid);
        $this->db->where('acid',$acname);
        $this->db->where('actiGroup',$groupName);
        $this->db->where('per_week',$activity_week);
        $this->db->where('academicyear',$max_year);
        $this->db->set('pervalue',$value);
        $queryUpdated=$this->db->update('teacherperfvalue');
      }else{
        $queryInserted=$this->db->insert('teacherperfvalue',$data);
      }
      $querySume=$this->db->query("select sum(pervalue) as totalSumVale from teacherperfvalue where academicyear='$max_year' and teid='$teid' and actiGroup='$groupName' and per_week='$activity_week' ");
      $row=$querySume->row();
      $TotalValue=$row->totalSumVale;
      echo $TotalValue;
    }
  }
  function fetchStaffPerformResult(){
    $user=$this->session->userdata('username');
    $userType=$this->session->userdata('usertype');
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
      $activityViewWeek=$this->input->post('activityViewWeek');
      $activityViewStaffs=$this->input->post('activityViewStaffs');
      echo $this->main_model->fetchStaffPerformResult($activityYear,$branch,$activityViewWeek,$activityViewStaffs);
    }
  }

}