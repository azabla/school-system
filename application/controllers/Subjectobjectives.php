<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subjectobjectives extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
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
	public function index($page='subjectobjectives')
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
    $data['subjects']=$this->main_model->fetch_KG_subject($max_year);
    $data['subj4merged']=$this->main_model->fetchAllSubject4Forged($max_year);
    $data['kgsubjects']=$this->main_model->fetchOnlyKgSubjects($max_year);
    $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
    $this->load->view('home-page/'.$page,$data);
	}
  function saveNewSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;

    if(isset($_POST['subjectName'])){
      if(!empty($this->input->post('subjectName'))){
        $subjectName=$this->input->post('subjectName');
        $osubject=$this->input->post('osubject');
        $linksubject=$this->input->post('linksubject');
        $subjectGrade=$this->input->post('subjectGrade');
        $date_created=date('M-d-Y');
        for($i=0;$i<count($subjectGrade);$i++){
          $grade=$subjectGrade[$i];
          for ($j=0; $j<count($osubject) ; $j++) { 
            # code...
            $subject=$osubject[$j];
            $data=array(
                'subid'=>$subject,
                'ograde'=>$grade,
                'subobjective'=>$subjectName,
                'datecreated'=>$date_created,
                'academicyear'=>$max_year,
                'linksubject'=>$linksubject,
                'quarter'=>$max_quarter
            );
            $this->main_model->add_KG_subject_objective($subjectName,$subject,$grade,$max_year,$max_quarter,$data);
          }
        } 
      }
    }
  } 
  function fetchSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    echo $this->main_model->fetchAllKGSubjetsObjectives($max_year,$max_quarter);
  }
  function fetchSubjectToEdit(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['edtisub'])){
      $edtisub=$this->input->post('edtisub');
      echo $this->main_model->edit_KG_subject_Objective($edtisub,$max_year);
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
      $data=array(
        'subobjective'=>$newsubjName
      );
      $this->main_model->update_KG_subject_objective($oldsubjName,$data,$max_year);
    }
  }
  function subjectDelete(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['post_id'])){
      $id=$this->input->post('post_id');
      $this->main_model->delete_KG_subject_objective($id,$max_year);
    }
  }
  function deleteOneSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['gradename'])){
      $gradename=$this->input->post('gradename');
      $subjname=$this->input->post('subjname');
      $this->db->where('ograde',$gradename);
      $this->db->where('subid',$subjname);
      $this->db->where('academicyear',$max_year);
      $query=$this->db->delete('kgsubjectobjective');
      if($query){
        echo '<span class="text-info">Deleted</span>';
      }else{
        echo 'oops';
      }
    }
  }

}