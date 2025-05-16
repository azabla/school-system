<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approvecommunicationbook extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('teacher_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='CommunicationBook' and allowed='approvecommunicationbook' order by id ASC "); 
    if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='approvecommunicationbook')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');
    $data['fetch_term']=$this->teacher_model->fetch_term_4teacheer($max_year);
    $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
    $data['academicyear']=$this->teacher_model->academic_year_filter();
    $data['schools']=$this->teacher_model->fetch_school();
    $this->load->view('teacher/'.$page,$data);
	}
  function load_grade_to_appprovecommbook(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->teacher_model->load_grade_to_appprovecommbook($user,$mybranch,$max_year);
  }
  function fetch_subject_of_thisGrade_toapprove(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear');
      $gradesec=$this->input->post('grade');
      $branch=$this->input->post('branch');
      echo $this->teacher_model->fetch_subject_of_thisGrade_toapprove($user,$academicyear,$gradesec,$branch);
    }
  }
  function fetch_comBookhistory_of_thisGrade_toapprove(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('grade');
      $branch=$this->input->post('branch');
      $year=$this->input->post('year');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      $queryApproval=$this->db->query("select * from enableapprovecommubook where academicyear='$year'  ");
      if($queryApproval->num_rows()>0){
        echo $this->teacher_model->fetch_comBookhistory_of_thisGrade_toapprove($user,$subject,$gradesec,$mybranch,$year,$max_quarter); 
      }else{
        echo '<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Approve communicationbook setting is OFF.
            </div></div>';
        /*echo $this->teacher_model->fetch_comBookhistory_of_thisGrade_nottoapprove($user,$subject,$gradesec,$mybranch,$year,$max_quarter);*/ 
      }
    }
  }
  function approvethis_CommunicationBook(){
    if($this->input->post('stuID')){
      $stuID=$this->input->post('stuID');
      $this->db->set('approvecom','1');
      $this->db->where('id',$stuID);
      $this->db->update('communicationbook');
    }
  }
  function rejectCommunicationBook(){
    if($this->input->post('stuID')){
      $stuID=$this->input->post('stuID');
      $this->db->where('id',$stuID);
      $this->db->delete('communicationbook');
    }
  }
  function approvethis_replayCommunicationBook(){
    if($this->input->post('stuID')){
      $stuID=$this->input->post('stuID');
      $this->db->set('approvereplay','1');
      $this->db->where('id',$stuID);
      $this->db->update('combookreplaystudent');
    }
  }
  function rejectthis_replayCommunicationBook(){
    if($this->input->post('stuID')){
      $stuID=$this->input->post('stuID');
      $this->db->where('id',$stuID);
      $this->db->delete('combookreplaystudent');
    }
  }
}