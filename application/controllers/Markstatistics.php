<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Markstatistics extends CI_Controller {
  public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='Statistics' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='markstatistics')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');
    $data['grade']=$this->main_model->fetch_grade($max_year);
    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $data['fetch_termGrade']=$this->main_model->fetch_term($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
	}
  function fetchGradeFromBranch(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
     $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('branch_statistics')){
      $branch=$this->input->post('branch_statistics');
      echo $this->main_model->fetchGradeFromBranch($branch,$max_year); 
    }
  } 
  function filterOnlyGradeFromBranch(){
      $user=$this->session->userdata('username');
      $usertype=$this->session->userdata('usertype');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('branchit')){
          $branch=$this->input->post('branchit');
          echo $this->main_model->fetchOnlyGradeFromBranch($branch,$max_year); 
      }
  }
  function fetch_subject_from_gradeSecFilter(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('gradesec')){
      $grade=$this->input->post('gradesec');
      echo $this->main_model->fetch_subject_from_gradeSecFilter($grade,$max_year); 
    } 
  }
  function fetch_subject_from_gradeFilter(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('gradesec')){
      $grade=$this->input->post('gradesec');
      for($i=0;$i<count($grade);$i++){
          $check[]=$grade[$i];
      }
      echo $this->main_model->fetch_subject_from_gradeFilter($check,$max_year); 
    } 
  }
  function thisMarkStatistics(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('gradeStatistics')){
      $quarter=$this->input->post('quarterStatistics');
      $grade=$this->input->post('gradeStatistics');
      $subject=$this->input->post('subStatistics');
      foreach($subject as $subjects){
        $subjectItems[] = $subjects;
      }
      foreach($grade as $grades){
        $gradeItems[] = $grades;
      }
      /*if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){*/
        echo $this->main_model->mark_statistics($max_year,$gradeItems,$subjectItems,$quarter);
      /*}else{
        echo $this->main_model->mark_statisticsAdmin($max_year,$mybranch,$gradeItems,$subjectItems,$quarter);
      }*/
    } 
  } 
  function saveRange(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('lessThan')){
      $less_than=$this->input->post('lessThan');
      $greater_than=$this->input->post('greaterThan');
      $data=array(
        'minvaluerange'=>$greater_than,
        'maxvaluerange'=>$less_than,
        'academicyear'=>$max_year
      );
      $query=$this->db->insert('reportstatistics',$data);
      if($query){
        $queryFetch=$this->db->query("select * from reportstatistics ");
        if($queryFetch->num_rows()>0){
          foreach($queryFetch->result() as $staName){
            echo '<p id="row" class="dynamic-added">Between '.$staName->minvaluerange.' & '.$staName->maxvaluerange.'<button class="btn btn-default btnRemove" id="'.$staName->maxvaluerange.'"  value="'.$staName->minvaluerange.'"><i class="fas fa-times-circle"></i></button></p>';      
          }
        }
      }
    }
  }
  function removeRange(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
     if($this->input->post('lessThan')){
      $less_than=$this->input->post('lessThan');
      $greater_than=$this->input->post('greaterThan');
      $this->db->where('minvaluerange',$greater_than);
      $this->db->where('maxvaluerange',$less_than);
      $query=$this->db->delete('reportstatistics');
      if($query){
        $queryFetch=$this->db->query("select * from reportstatistics ");
        if($queryFetch->num_rows()>0){
          foreach($queryFetch->result() as $staName){
            echo '<p id="row" class="dynamic-added">Between '.$staName->minvaluerange.' & '.$staName->maxvaluerange.'<button class="btn btn-default btnRemove" id="'.$staName->maxvaluerange.'"  value="'.$staName->minvaluerange.'"><i class="fas fa-times-circle"></i></button></p>';      
          }
        }
      }
    }
  }
  function fetchRange(){
    $queryFetch=$this->db->query("select * from reportstatistics ");
    if($queryFetch->num_rows()>0){
      foreach($queryFetch->result() as $staName){
        echo '<p id="row" class="dynamic-added">Between '.$staName->minvaluerange.' & '.$staName->maxvaluerange.'<button class="btn btn-default btnRemove" id="'.$staName->maxvaluerange.'"  value="'.$staName->minvaluerange.'"><i class="fas fa-times-circle"></i></button></p>';      
      }
    }
  }
  function thisGradeMarkStatistics(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $countQuarter=0;$countSubject=0;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('grade_statistics')){
      $this->db->empty_table('reportvaluestatistics');
      $quarters=$this->input->post('quarterStatistics');
      $grade=$this->input->post('grade_statistics');
      $branch=$this->input->post('branch_statistics');
      $subject=$this->input->post('subStatistics');
      $nameChecked=$this->input->post('nameChecked');
      for($i=0;$i<count($quarters);$i++){
        $countQuarter=$countQuarter + 1;
        $quarter[]=$quarters[$i];
      }
      if(!empty($subject)){
        for($i=0;$i<count($subject);$i++){
          $countSubject=$countSubject + 1;
          $subjects[]=$subject[$i];
        }
        if($nameChecked==1){
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->gradeMarkStatisticswithName($max_year,$branch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
          }else{
            echo $this->main_model->gradeMarkStatisticswithName($max_year,$mybranch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
          }
        }else{
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->gradeMarkStatistics($max_year,$branch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
          }else{
            echo $this->main_model->gradeMarkStatistics($max_year,$mybranch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
          }
        }
      }else{
        if($nameChecked==1){
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->averageMarkStatisticsWithName($max_year,$branch,$grade,$quarter,$countQuarter,$countSubject);
          }else{
            echo $this->main_model->averageMarkStatisticsWithName($max_year,$mybranch,$grade,$quarter,$countQuarter,$countSubject);
          }
        }else{
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->averageMarkStatistics($max_year,$branch,$grade,$quarter,$countQuarter,$countSubject);
          }else{
            echo $this->main_model->averageMarkStatistics($max_year,$mybranch,$grade,$quarter,$countQuarter,$countSubject);
          }
        }
      }
    } 
  }
}