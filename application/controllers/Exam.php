<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='studentexam' order by id ASC ");
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
  public function index($page='exam')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    
    $accessbranch = sessionUseraccessbranch();
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1')
    {
      $data['fetch_gradesec']=$this->main_model->fetch_myschool_gradesec($max_year);
    }
    else{
      $data['fetch_gradesec']=$this->main_model->fetch_mybranch_gradesec($branch,$max_year);
    }
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $this->load->view('home-page/'.$page,$data);
  } 
  function check_examValidation(){
    date_default_timezone_set("Africa/Addis_Ababa");
    $dtz = new DateTimeZone('UTC');
    $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
    $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $user=$this->session->userdata('username');
    $return='';
    if($this->input->post('examName')){
      $examName=trim($this->input->post('examName'));
      $examGrade=$this->input->post('examGrade');
      $examSubject=$this->input->post('examSubject');
      $minuteAllowed=$this->input->post('minuteAllowed');
      $numberQuestion=$this->input->post('numberQuestion');
      $terminate_exam_status=$this->input->post('terminate_exam_status');
      $exam_started_time=$this->input->post('exam_started_time');
      $see_resut_automatically=$this->input->post('see_resut_automatically');
      $queryCheck=$this->db->query("select * from exam where subject='$examSubject' and grade='$examGrade' and examname='$examName' and academicyear='$max_year' and exam_started_time!='$exam_started_time' ");
      if($queryCheck->num_rows()>0){
        echo '0';
      }else{
        echo $this->main_model->insert_online_exam($examName,$examGrade,$examSubject,$minuteAllowed,$numberQuestion,$terminate_exam_status,$exam_started_time,$see_resut_automatically,$max_year);
      }
    }
  }
  function save_insert_exam(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $user=$this->session->userdata('username');
    $group_name=1;
    if($this->input->post('exam_name')){
      $queryCheck=$this->db->query("select max(examGroup) as groupName from exam order by eid DESC ");
      if($queryCheck->num_rows()>0){
          $row=$queryCheck->row();
          $grooupName=$row->groupName;
          $group_name=$grooupName + 1;
      } else{
          $group_name=$group_name;
      }
      $count_option = count($_POST["name_question_choice"]);
      $examname=$this->input->post('exam_name');
      $subject=$this->input->post('exam_subject_name');
      $gradesec=$this->input->post('exam_grade_name');
      $name_Question=$this->input->post('name_Question');
      $question_type=$this->input->post('question_type');
      $question_weight=$this->input->post('question_weight');
      $answer_is=$this->input->post('select_correct_answer');
      $minute=$this->input->post('minute_Allowed');
      $terminate_status=$this->input->post('terminate_status');
      $startedTime_status=$this->input->post('startedTime_status');
      $can_see_resut_automatically=$this->input->post('can_see_resut_automatically');
      $caa=$this->input->post('ca');
      $cbb=$this->input->post('cb');
      $ccc=$this->input->post('cc');
      $cdd=$this->input->post('cd');
      $name_Question=filter_var(htmlentities($_POST["name_Question"]), FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
      for($i=0; $i<$count_option; $i++) {
        if(trim($_POST["name_question_choice"][$i] != '')){
          /*$optionName[]= $_POST["name_question_choice"][$i];*/
          $optionName[]=filter_var($_POST["name_question_choice"][$i], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
          if($answer_is==$i){
            $answer=$_POST["name_question_choice"][$answer_is];
          }else{
            $answer='';
          }
        }
      }
      echo $this->main_model->insert_exam($user,$subject,$gradesec,$examname,$answer,$name_Question,$question_type,$question_weight,$minute,$terminate_status,$startedTime_status,$can_see_resut_automatically,$optionName,$group_name,$max_year);
    }
  }
  function fetch_exams(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $postData = $this->input->post();
    $data= $this->main_model->fetch_exams($max_year,$postData);
    echo json_encode($data);
  }
  function copy_this_exam_name(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $academicyear=$this->input->post('academicyear');
      $examName=$this->input->post('examName');
      $grade=$this->input->post('grade');
      echo $this->main_model->copy_this_exam_name($subject,$academicyear,$grade,$examName);
    }
  }
  function save_thiscopy_exam_name(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $user=$this->session->userdata('username');
    if($this->input->post('examName')){
      $examname=$this->input->post('examName');
      $academicyear=$this->input->post('academicyear');
      $examSubject=$this->input->post('examSubject');
      $examGrade=$this->input->post('examGrade');
      $newCopiedGrade=$this->input->post('newCopiedGrade');
      $newCopiedSubject=$this->input->post('newCopiedSubject');
      echo $this->main_model->insert_save_copied_exam($examname,$academicyear,$examSubject,$examGrade,$newCopiedGrade,$newCopiedSubject);
    }
  }
  function deleteExamName(){
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $academicyear=$this->input->post('academicyear');
      $examName=$this->input->post('examName');
      $grade=$this->input->post('grade');
      $this->db->where('grade',$grade);
      $this->db->where('subject',$subject);
      $this->db->where('examname',$examName);
      $this->db->where('academicyear',$academicyear);
      $query=$this->db->delete('exam');
      if($query){
          echo '<span class="text-success">Deleted successfully</span>';
      }else{
          echo '<span class="text-danger">Please try later</span>';
      }
    }  
  }
}