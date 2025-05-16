<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examform extends CI_Controller {
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
  public function index($page='examform')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
    {
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['start_editing_examName'])){
      $examname=$this->input->post('edited_exam_name');
      $grade=$this->input->post('edited_exam_grade');
      $subject=$this->input->post('edited_exam_subject');
      $year=$this->input->post('edited_exam_year');
      if($this->main_model->this_exam_name($examname,$year,$grade,$subject)){
        $dataArray=array($examname,$grade,$subject,$year);
        $data['passFeild']=$dataArray;
        $data['edit_exam']=$this->main_model->this_exam_name($examname,$year,$grade,$subject);
        $data['exam_header']=$this->main_model->this_exam_name_header_detail($examname,$year,$grade,$subject);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['posts']=$this->main_model->fetch_post();
        $this->load->view('home-page/'.$page,$data);
      }else{
        $this->session->set_flashdata('error','Exam name already exists.Please try again.');
        redirect('exam/');
      }
    }
    else{
      redirect('Exam/','refresh');
    }     
  }
  function edit_this_exam_name(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $examGroup=$this->input->post('examGroup');
      $examName=$this->input->post('examName');
      $grade=$this->input->post('grade');
      echo $this->main_model->edit_this_exam_name($subject,$examGroup,$grade,$examName);
    }
  }
  function update_exam_name(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $user=$this->session->userdata('username');
    $dataArray=array();
    if($this->input->post('examName')){
      $examname=$this->input->post('examName');
      $subject=$this->input->post('examSubject');
      $gradesec=$this->input->post('examGrade');
      $question_name=$this->input->post('question_name');
      $question_type=$this->input->post('editedquestion_type');
      $question_weight=$this->input->post('editedquestion_weight');
      $answer=$this->input->post('editedquestion_answer');
      $minute=$this->input->post('minuteAllowed');
      $hiddenexam_name=$this->input->post('hiddenexam_name');
      $terminate_exam_status=$this->input->post('terminate_exam_status');
      $startedTime_status=$this->input->post('exam_started_time');
      $can_see_resut_automatically=$this->input->post('see_resut_automatically');
      $best_correct_answer=$_POST['best_correct_answer'];
      $c_aa=$_POST['ca_gs'];
      $eid=$this->input->post('eid');
      $dataArray=array();
      $dataArray2=array();
      $ca_hiddenGroup=$this->input->post('ca_hiddenGroup');
      $name_Question=filter_var(htmlentities($_POST["question_name"]), FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
      for ($i=0; $i < count($c_aa); $i++) { 
        $id=$eid[$i];
        $caa=$c_aa[$i];
        $caa=filter_var($c_aa[$i], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
        $dataArray=array(
          'question'=>$name_Question,
          'question_type'=>$question_type,
          'question_weight'=>$question_weight,
          'answer'=>$best_correct_answer, 
          'a'=>$caa
        );
        $this->db->where('subject',$subject);
        $this->db->where('grade',$gradesec);
        $this->db->where('examname',$hiddenexam_name);
        $this->db->where('eid',$id);
        $this->db->where('examGroup',$ca_hiddenGroup);   
        $this->db->where('academicyear',$max_year);     
        $queryUpdate1=$this->db->update('exam',$dataArray);
        if($queryUpdate1){
          $dataArray2=array(
            'examname'=>$examname,
            'exam_terminate_status'=>$terminate_exam_status,
            'exam_started_time'=>$startedTime_status,
            'see_result_status'=>$can_see_resut_automatically,
            'examinute'=>$minute
          );
          $this->db->where('subject',$subject);
          $this->db->where('grade',$gradesec);
          $this->db->where('examname',$hiddenexam_name);
          /*$this->db->where('eid',$id);*/
          $this->db->where('academicyear',$max_year);    
          $queryUpdate=$this->db->update('exam',$dataArray2);
        }
      }
      if($queryUpdate){
        echo '1';
      }else{
        echo '0';
      }
    }
  }
  function deleteQuestionName(){
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $examGroup=$this->input->post('examGroup');
      $examName=$this->input->post('examName');
      $grade=$this->input->post('grade');
      $this->db->where('grade',$grade);
      $this->db->where('subject',$subject);
      $this->db->where('examname',$examName);
      $this->db->where('examGroup',$examGroup);
      $query=$this->db->delete('exam');
      if($query){
          echo '1';
      }else{
          echo '0';
      }
    }  
  } 
}