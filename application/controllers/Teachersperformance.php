<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teachersperformance extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='manageperformance' order by id ASC "); 
    if($this->session->userdata('username') == '' || $usergroupP->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='teachersperformance')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
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
    $data['user_division']=$this->main_model->user_division($max_year);
    $this->load->view('home-page/'.$page,$data);
	} 
  function fetch_group_ondivision_change(){
    $user=$this->session->userdata('username');
    $userType=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('select_group')){
      $select_group=$this->input->post('select_group');
      echo $this->main_model->fetch_group_ondivision_change($select_group,$max_year);  
    }
  }
  function fetch_staffs_onbranch_change(){
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
    if($this->input->post('staffs_list')){
      $staffs_list_branch=$this->input->post('staffs_list');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->fetchStaffs_For_performance($staffs_list_branch);
      }else{
        echo $this->main_model->fetchStaffs_For_performance_directors($branch,$user,$max_year);
      }
    }
  }
  function fetchCustomGroup(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    /*if($_SESSION['usertype']===trim('superAdmin')){*/
      echo $this->main_model->fetchCustomGroup($max_year,$user);
    /*}else{
       echo '<div class="alert alert-warning alert-dismissible show fade">
          <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
              </button>
          <i class="fas fa-check-circle"> </i> Ooops Please contact system admin.
      </div></div>';
    }*/
  }
  function postCustomGroup(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $data=array();
    if($this->input->post('customGroupName')){
      $customGroupName=trim($this->input->post('customGroupName'));
      $customGroupWeight=trim($this->input->post('customGroupWeight'));
      $customGroupDvision=trim($this->input->post('customGroupDvision'));
      if($customGroupDvision=='All Division'){
        $this->db->order_by('dname','ASC');
        $this->db->group_by('dname');
        $queryDivision=$this->db->get('schooldivision');
        foreach($queryDivision->result() as $row){
          $dName=$row->dname;
          $queryCheck=$this->db->query("select * from teacherperfogroup where pername='$customGroupName' and quarter='$max_quarter' and per_division='$dName' and academicyear='$max_year' ");
          if($queryCheck->num_rows()<1){
            $data[]=array(
              'pername'=>$customGroupName,
              'quarter'=>$max_quarter,
              'perweight'=>$customGroupWeight,
              'per_division'=>$dName,
              'academicyear'=>$max_year,
              'datecreated'=>date('M-d-Y'),
              'createdby'=>$user
            );
          } 
        }
        $queryInsert= $this->db->insert_batch('teacherperfogroup',$data);
      }
      else{
        $queryCheck=$this->db->query("select * from teacherperfogroup where pername='$customGroupName' and quarter='$max_quarter' and per_division='$customGroupDvision' and academicyear='$max_year' ");
        if($queryCheck->num_rows()<1){
          $data[]=array(
            'pername'=>$customGroupName,
            'quarter'=>$max_quarter,
            'perweight'=>$customGroupWeight,
            'per_division'=>$customGroupDvision,
            'academicyear'=>$max_year,
            'datecreated'=>date('M-d-Y'),
            'createdby'=>$user
          );
          $queryInsert= $this->db->insert_batch('teacherperfogroup',$data);
        }
      }
      if($queryInsert){
        echo '<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Group saved successfully.
            </div></div>';
      }else{
        echo '<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Ooops Please try again.
            </div></div>';
      }
    }
  }
  function deleteCustomGroup(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['textId'])){
      $id=$this->input->post('textId');
      $this->db->where('tid',$id);
      $query=$this->db->delete('teacherperfogroup');
      if($query){
        $this->db->where('pergroup',$id);
        $query1=$this->db->delete('teacherperactivity');
        if($query1){
          $this->db->where('actiGroup',$id);
          $query1=$this->db->delete('teacherperfvalue');
        }
      }
    }
  }
  function fetchCustomActivity(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetchCustomActivity($max_year,$user);
  }
  function postCustomActivity(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    if($this->input->post('selectperGroup')){
      $selectperGroup=trim($this->input->post('selectperGroup'));
      $customActivitiesName=trim($this->input->post('customActivitiesName'));
      $customActivitiesPercent=trim($this->input->post('customActivitiesPercent'));
      $activity_for=trim($this->input->post('activity_for'));
      $selectperDivision=trim($this->input->post('selectperDivision'));
      $queryCheck=$this->db->query("select * from teacherperactivity where academicyear='$max_year' and acname='$customActivitiesName' and pergroup='$selectperGroup' and activity_for='$activity_for' and activity_division='$selectperDivision' and quarter='$max_quarter' ");
      if($queryCheck->num_rows()<1){
        $data=array(
          'acname'=>$customActivitiesName,
          'pergroup'=>$selectperGroup,
          'aweight'=>$customActivitiesPercent,
          'activity_for'=>$activity_for,
          'activity_division'=>$selectperDivision,
          'quarter'=>$max_quarter,
          'academicyear'=>$max_year,
          'datecreated'=>date('M-d-Y'),
          'createdby'=>$user
        );
        $queryInsert= $this->db->insert('teacherperactivity',$data);
        if($queryInsert){
          echo '<div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                  <i class="fas fa-check-circle"> </i> Activity saved successfully.
              </div></div>';
        }else{
          echo '<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                  <i class="fas fa-check-circle"> </i> Ooops Please try again.
              </div></div>';
        }
      }
    }
    
  }
  function deleteCustomActivity(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['textId'])){
      $id=$this->input->post('textId');
      $this->db->where('aid',$id);
      $query=$this->db->delete('teacherperactivity');
      if($query){
        $this->db->where('acid',$id);
        $query1=$this->db->delete('teacherperfvalue');
      }
    }
  }
  function fetchStaffToPerformActivity(){
    $userType=$this->session->userdata('usertype');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
    $rowaccessbranch = $queryAccessBranch->row_array();
    $accessbranch=$rowaccessbranch['accessbranch'];
    if(isset($_POST['activityYear'])){
      $activityYear=$this->input->post('activityYear');
      $activityBranch=$this->input->post('activityBranch');
      $activity_week=$this->input->post('activity_week');
      $activity_staffs=$this->input->post('activity_staffs');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->fetchStaffToPerformActivity_all($activityYear,$activityBranch,$activity_week,$activity_staffs);
      }else{
        echo $this->main_model->fetchStaffToPerformActivity($activityYear,$activityBranch,$activity_week,$activity_staffs,$userType);
      }
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
      $activityBranch=$this->input->post('activityBranch');
      $activityViewWeek=$this->input->post('activityViewWeek');
      $activityViewStaffs=$this->input->post('activityViewStaffs');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->fetchStaffPerformResult($activityYear,$activityBranch,$activityViewWeek,$activityViewStaffs);
      }else{
        echo $this->main_model->fetchStaffPerformResult($activityYear,$branch,$activityViewWeek,$activityViewStaffs);
      }
    }
  }
  function fetch_Perform_Result(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['activityYear'])){
      $activityYear=$this->input->post('activityYear');
      $activityBranch=$this->input->post('activityBranch');
      $staff_activity_Week=$this->input->post('staff_activity_Week');
      $staff_activity_Division=$this->input->post('staff_activity_Division');
      echo $this->main_model->fetch_Perform_Result($activityYear,$activityBranch,$staff_activity_Week,$staff_activity_Division);
    }
  }
  function edit_activity_name(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('idName')){
      $idName=$this->input->post('idName');
      echo $this->main_model->fetch_edit_activity_name($idName);
    }
  }
  function edit_group_name(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('idName')){
      $idName=$this->input->post('idName');
      echo $this->main_model->fetch_edit_group_name($idName);
    }
  }
  function update_activity_name(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('activityNameNew')){
      $activityNameNew=$this->input->post('activityNameNew');
      $activityWeightNew=$this->input->post('activityWeightNew');
      $activityIDNew=$this->input->post('activityIDNew');
      echo $this->main_model->update_activity_name($activityNameNew,$activityWeightNew,$activityIDNew);
    }
  }
  function update_group_name(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('groupNameNew')){
      $groupNameNew=$this->input->post('groupNameNew');
      $groupWeightNew=$this->input->post('groupWeightNew');
      $groupIDNew=$this->input->post('groupIDNew');
      echo $this->main_model->update_group_name($groupNameNew,$groupWeightNew,$groupIDNew);
    }
  }
  function saveLockMarkAuto(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('lockmark')){
      $lockmark=$this->input->post('lockmark');
      $per_week=$this->input->post('per_week');
      $user_division=$this->input->post('user_division');
      $data=array(
        'permission_status'=>$lockmark,
        'permission_week'=>$per_week,
        'user_divison'=>$user_division,
        'academicyear'=>$max_year,
        'lockby'=>$user,
        'datelocked'=>date('M-d-Y')
      );
      $this->db->insert('teacher_performance_view_permission',$data); 
    }
  }
  function deleteLockMarkAuto(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
      $lockmark=$this->input->post('lockmark');
      $per_week=$this->input->post('per_week');
      $user_division=$this->input->post('user_division');
      $this->db->where('academicyear',$max_year);
      $this->db->where('user_divison',$user_division);
      $this->db->where('permission_week',$per_week);
      $this->db->delete('teacher_performance_view_permission'); 
    
  }
}