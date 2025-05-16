<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewmylessonplan extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $upViewLplan=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='lessonplan' and allowed='viewlessonplan' order by id ASC ");  
    if($this->session->userdata('username') == '' || $upViewLplan->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='viewlessonplan')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }

    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;

    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(trim($_SESSION['usertype'])===trim('Director'))
    {
      $data['lessonplan']=$this->main_model->viewLessonPlanDirector($max_year,$user);
    }else{
      $data['lessonplan']=$this->main_model->viewLessonPlanTeacher($max_year,$user);
    }
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $data['fetch_gradesec']=$this->main_model->fetch_mybranch_gradesec($branch,$max_year);
    $this->load->view('teacher/'.$page,$data);
	} 
  function deleteViewLessonPlan(){
    if($this->input->post('deletelessonplanid')){
      $lessonID=$this->input->post('deletelessonplanid');
      $this->main_model->deleteLessonId($lessonID);
    }
  }
  function EditLessonPlan(){
    if($this->input->post('editlessonplan')){
      $lessonID=$this->input->post('editlessonplan');
      echo $this->main_model->editLessonId($lessonID);
    }
  }
  function updateLessonPlan(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    if($this->input->post('lesson_objective')){
      $lessonPlanId=$this->input->post('lessonPlanId');
      $lesson_objective=$this->input->post('lesson_objective');
      $teachers_guide=$this->input->post('teachers_guide');
      $students_guide=$this->input->post('students_guide');
      $materials_needed=$this->input->post('materials_needed');
      $data=array(
        'lesson_objective'=>$lesson_objective,
        'teacher_guide'=>$teachers_guide,
        'student_guide'=>$students_guide,
        'material_needed'=>$materials_needed
      );
      $this->db->where('id',$lessonPlanId);
      $query=$this->db->update('lessonplan',$data);
      if($query){
        echo '<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Changes saved successfully.
            </div></div>';
      }else{
        echo '<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Ooops, Please try again.
            </div></div>';
      }
    }
  }
  function viewLessonPlanHere(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('viewlessonplan')){
      $lessonID=$this->input->post('viewlessonplan');
      echo $this->main_model->viewLessonId($lessonID,$max_year);
    }
  }
}