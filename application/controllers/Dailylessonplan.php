<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dailylessonplan extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $upAddLplan=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='lessonplan' and allowed='addlessonplan' order by id ASC ");
    if($this->session->userdata('username') == '' || $upAddLplan->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='dailylessonplan')
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
    $accessbranch = sessionUseraccessbranch();
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $data['grade']=$this->main_model->fetch_grade($max_year);
    if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1')
    {
      $data['fetch_gradesec']=$this->main_model->fetch_myschool_gradesec($max_year);
    }else{
      $data['fetch_gradesec']=$this->main_model->fetch_mybranch_gradesec($branch,$max_year);
    }
    $this->load->view('home-page/'.$page,$data);
	} 
  function savelessonplan(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('lesson_grade')){
      $lessonGrade=$this->input->post('lesson_grade');
      $lesson_subject=$this->input->post('lesson_subject');
      $lesson_objective=$this->input->post('lesson_objective');
      $teachers_guide=$this->input->post('teachers_guide');
      $students_guide=$this->input->post('students_guide');
      $materials_needed=$this->input->post('materials_needed');
      $checkGrade=$this->main_model->checkLessonPlan($lessonGrade,$lesson_subject,$max_year);
      if($checkGrade){
        $data=array(
          'grade'=>$lessonGrade,
          'subject'=>$lesson_subject,
          'lesson_objective'=>$lesson_objective,
          'teacher_guide'=>$teachers_guide,
          'student_guide'=>$students_guide,
          'material_needed'=>$materials_needed,
          'academicyear'=>$max_year,
          'postby'=>$user,
          'dateposted'=>date('M-d-Y')
        );
        $checkSaved=$this->db->insert('lessonplan',$data);
      }
      if(!empty($checkSaved)){
        echo '<div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
              <i class="fas fa-check-circle"> </i> Saved Successfully.
        </div></div>';
      }else{
        echo '<div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
              <i class="fas fa-check-circle"> </i> Lesson plan already exists.
        </div></div>';
      }
    }
  }
}