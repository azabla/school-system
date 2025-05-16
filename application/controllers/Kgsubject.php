<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kgsubject extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='gradeSubject' order by id ASC "); 
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
	public function index($page='kgsubject')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['grade']=$this->main_model->fetchKgGrade($max_year);
    $data['subjects']=$this->main_model->fetch_all_subject($max_year);
    $data['subj4merged']=$this->main_model->fetchAllSubject4Forged($max_year);
    $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
    $this->load->view('home-page/'.$page,$data);
	}
  function saveNewSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['subjectName'])){
      if(!empty($this->input->post('subjectName'))){
        $subjectName=$this->input->post('subjectName');
        $subjectLetter=$this->input->post('subjectLetter');
        $subjectGrade=$this->input->post('subjectGrade');
        $onReportCard=1;
        $date_created=date('M-d-Y');
        for($i=0;$i<count($subjectGrade);$i++){
          $check=$subjectGrade[$i];
          $letteri=$subjectLetter[$i];
          $data=array(
              'subname'=>$subjectName,
              'subgrade'=>$check,
              'letter'=>$letteri,
              'datecreated'=>$date_created,
              'academicyear'=>$max_year,
              'onreportcard'=>$onReportCard,
              'percentage'=>'100'
            );
          $this->main_model->add_KG_subject($subjectName,$check,$max_year,$data);
        }
        
      }
    }
  } 
  function fetchSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetchAllKGSubjets($max_year);
  }
  function updateSubjectOrder(){
  	$user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('suborder')){
      $suborder=$this->input->post('suborder');
      $subject=$this->input->post('subject');
      $this->db->where('academicyear',$max_year);
      $this->db->where('subname',$subject);
      $this->db->set('suborder',$suborder);
      $this->db->update('kgsubject');
    }
  }
  function fetchSubjectToEdit(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['edtisub'])){
      $edtisub=$this->input->post('edtisub');
      echo $this->main_model->edit_KG_subject($edtisub,$max_year);
    }
  }
  function updateSubjectName(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['oldsubjName'])){
      $date_created=date('M-d-Y');
      $oldsubjName=$this->input->post('oldsubjName');
      $newsubjName=$this->input->post('newsubjName');
      $percent=$this->input->post('percent');
      $data=array(
        'subname'=>$newsubjName,
        'percentage'=>$percent
      );
      $this->main_model->update_KG_subject($oldsubjName,$data,$max_year,$newsubjName);
    }
  }
  function updateSubjectForLetter(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['gradejoss'])){
      $gradejoss=$this->input->post('gradejoss');
      $letterjoss=$this->input->post('letterjoss');
      $subjjoss=$this->input->post('subjjoss');
      $this->db->where('subgrade',$gradejoss);
      $this->db->where('subname',$subjjoss);
      $this->db->where('academicyear',$max_year);
      $this->db->set('letter',$letterjoss);
      $query=$this->db->update('kgsubject');
      if($query){
        echo '<span class="text-info">Saved</span>';
      }else{
        echo 'oops';
      }
    }
  }
  function subjectDelete(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['post_id'])){
      $id=$this->input->post('post_id');
      $this->main_model->delete_KG_subject($id,$max_year);
    }
  }
  function onreportcard(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['onreportcard'])){
      $onreportcard=$this->input->post('onreportcard');
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $this->db->where('subname',$subject);
      $this->db->where('subgrade',$grade);
      $this->db->where('academicyear',$max_year);
      $this->db->set('onreportcard',$onreportcard);
      $query=$this->db->update('kgsubject');
      if($query){
        echo '<span class="text-success">Saved </span>';
      }else{
        echo 'oops';
      }
    }
  }
  function deleteOneSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['gradename'])){
      $gradename=$this->input->post('gradename');
      $subjname=$this->input->post('subjname');
      $this->db->where('subgrade',$gradename);
      $this->db->where('subname',$subjname);
      $this->db->where('academicyear',$max_year);
      $query=$this->db->delete('kgsubject');
      if($query){
        echo '<span class="text-info">Deleted</span>';
      }else{
        echo 'oops';
      }
    }
  }

}