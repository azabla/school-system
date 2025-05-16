<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Examstudents extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='taskspage' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='examstudents')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
           show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $query_quarter = $this->db->query("select max(quarter) as quarter from mark");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        if(isset($_POST['startscheduler'])){
           $data['scheduler']=$this->main_model->examschedule($max_year,$max_quarter);
        }
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_grades($max_year);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['subject']=$this->main_model->fetchSubjectExam($max_year);
        $this->load->view('home-page/'.$page,$data);    
	} 
    function filterOnlyGradeFromBranch(){
      $user=$this->session->userdata('username');
      $usertype=$this->session->userdata('usertype');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('branchit')){
          $branch=$this->input->post('branchit');
          echo $this->main_model->fetchOnlyGradeFromBranch_group($branch,$max_year); 
      }
    }
    function filterAssesmentCustomEvaluation(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            $quarter=$this->input->post('quarter');
            echo $this->main_model->filterAssesmentStudentsExam($grade2analysis,$max_year,$quarter);   
        }
    } 
    function saveRange(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('noOfStudent')){
          $noOfStudent=$this->input->post('noOfStudent');
          $className=$this->input->post('className');
          $data=array(
            'classname'=>$className,
            'nostudent'=>$noOfStudent,
            'academicyear'=>$max_year
          );
          $query=$this->db->insert('studentexamlist',$data);
          if($query){
            $queryFetch=$this->db->query("select * from studentexamlist ");
            if($queryFetch->num_rows()>0){
              foreach($queryFetch->result() as $staName){
                echo '<p id="row" class="dynamic-added">Class Name '.$staName->classname.' & Student Number '.$staName->nostudent.'<button class="btn btn-default btnRemove" id="'.$staName->id.'"  value="'.$staName->id.'"><i class="fas fa-times-circle"></i></button></p>';      
              }
            }
          }
        }
    }
    function removeRange(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
         if($this->input->post('id')){
          $id=$this->input->post('id');
          $this->db->where('id',$id);
          $query=$this->db->delete('studentexamlist');
          if($query){
            $queryFetch=$this->db->query("select * from studentexamlist ");
            if($queryFetch->num_rows()>0){
              foreach($queryFetch->result() as $staName){
                echo '<p id="row" class="dynamic-added">Class Name '.$staName->classname.' & Student Number '.$staName->nostudent.'<button class="btn btn-default btnRemove" id="'.$staName->id.'"  value="'.$staName->id.'"><i class="fas fa-times-circle"></i></button></p>';      
              }
            }
          }
        }
    }
    function fetchRange(){
        $queryFetch=$this->db->query("select * from studentexamlist ");
        if($queryFetch->num_rows()>0){
          foreach($queryFetch->result() as $staName){
            echo '<p id="row" class="dynamic-added">Class Name '.$staName->classname.' & Student Number '.$staName->nostudent.'<button class="btn btn-default btnRemove" id="'.$staName->id.'"  value="'.$staName->id.'"><i class="fas fa-times-circle"></i></button></p>';      
          }
        }
    }
    function shuffleStudent(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade_statistics')){
            $gradeEvaluation=$this->input->post('gradeEvaluation');
            $grade=$this->input->post('grade_statistics');
            $branch=$this->input->post('branch_statistics');
            $nameChecked=$this->input->post('nameChecked');
            $term=$this->input->post('term');
            for($i=0;$i<count($gradeEvaluation);$i++){
                $gradeGsanalysis[]=$gradeEvaluation[$i];
            }
            if($nameChecked==1){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->shuffleExamStudentWithResult($max_year,$branch,$grade,$gradeGsanalysis,$term);
                }else{
                    echo $this->main_model->shuffleExamStudentWithResult($max_year,$mybranch,$grade,$gradeGsanalysis,$term);
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->shuffleExamStudent($max_year,$branch,$grade,$gradeGsanalysis,$term);
                }else{
                    echo $this->main_model->shuffleExamStudent($max_year,$mybranch,$grade,$gradeGsanalysis,$term);
                }
            }  
        } 
    }  
}