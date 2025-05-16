<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Communicationbook extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    $this->load->helper('security');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $this->db->where('usergroup',$_SESSION['usertype']);
    $this->db->where('tableName','CommunicationBook');
    $this->db->where('allowed','sendcommunicationbook');
    $usergroupPermission=$this->db->get('usergrouppermission'); 
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
	public function index($page='communicationbook')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['academicyear']=$this->main_model->academic_year();
    $this->load->view('home-page/'.$page,$data);
	} 
  function filterGradesecfromBranch(){
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear',TRUE);
      $academicyear=xss_clean($academicyear);
      echo $this->main_model->filterGradesecfromBranch($academicyear); 
    }
  }
  function fetch_gradesec_frombranch_markresult(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('branchit')){
      $branch=$this->input->post('branchit',TRUE);
      $max_year=$this->input->post('academicyear',TRUE);
      $max_year=xss_clean($max_year);
      $branch=xss_clean($branch);
      echo $this->main_model->fetch_grade_from_branch($branch,$max_year); 
    }
  }
  function fecth_subject(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row_array();
    $branch=$row_branch['branch'];

    $this->db->select('accessbranch');
    $this->db->where('uname',$usertype);
    $queryAccessBranch=$this->db->get('usegroup');
    $rowaccessbranch = $queryAccessBranch->row_array();
    $accessbranch=$rowaccessbranch['accessbranch'];
    if($this->input->GET('gs_branches')){
      $gs_branches=$this->input->GET('gs_branches',TRUE);
      $gs_gradesec=$this->input->GET('gs_gradesec',TRUE);
      $academicyear=$this->input->GET('academicyear',TRUE);
      $gs_branches=xss_clean($gs_branches);
      $gs_gradesec=xss_clean($gs_gradesec);
      $academicyear=xss_clean($academicyear);
      if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){     
        $show=$this->main_model->fetch_subject_of_thisGrade($gs_branches,$gs_gradesec,$academicyear); 
        echo $show; 
      }else{
        $show=$this->main_model->fetch_subject_of_thisGrade($branch,$gs_gradesec,$academicyear); 
        echo $show;
      }
    }
  }
  function fetch_comBookhistory_of_thisGrade(){
    $user=$this->session->userdata('username');
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject',TRUE);
      $gradesec=$this->input->post('grade',TRUE);
      $branch=$this->input->post('branch',TRUE);
      $year=$this->input->post('year',TRUE);

      $subject=xss_clean($subject);
      $gradesec=xss_clean($gradesec);
      $branch=xss_clean($branch);
      $year=xss_clean($year);
      $this->db->select('max(term) as quarter');
      $this->db->where('Academic_Year',$year);
      $query2=$this->db->get('quarter');
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;

      $this->db->select('*');
      $this->db->where('academicyear',$year);
      $queryApproval=$this->db->get('enableapprovecommubook');

      if($queryApproval->num_rows()>0){
        echo $this->main_model->fetchCommunicationBookTeacherApprove($subject,$gradesec,$branch,$year,$max_quarter); 
      }else{
        echo $this->main_model->fetchCommunicationBookTeacher($subject,$gradesec,$branch,$year,$max_quarter); 
      }
    }
  }
  function viewComBookId(){
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('viewlessonplan')){
      $lessonID=$this->input->post('viewlessonplan',TRUE);
      $lessonID=xss_clean($lessonID);
      echo $this->main_model->viewComBookId($lessonID,$max_year);
    }
  }
  function fetchCustomText(){
    echo $this->main_model->fetchCustomText();
  }
  function postCustomText(){
    $user=$this->session->userdata('username');
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('customTextName')){
      $customTextName=$this->input->post('customTextName',TRUE);
      $customTextName=xss_clean($customTextName);
      $data=array(
        'comtext'=>$customTextName,
        'academicyear'=>$max_year,
        'datecreated'=>date('M-d-Y'),
        'createdby'=>$user
      );
      $queryInsert= $this->db->insert('customcomtext',$data);
      if($queryInsert){
        echo '<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Text saved successfully.
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
  function deleteCustomText(){
    $user=$this->session->userdata('username');
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['textId'])){
      $id=$this->input->post('textId' ,TRUE);
      $id=xss_clean($id);
      $this->db->where('id',$id);
      $query=$this->db->delete('customcomtext');
    }
  }
}