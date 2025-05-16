<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Studentranscript extends CI_Controller {
   public function __construct(){
        parent::__construct();
        $this->load->model('Reportcard_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='transcript' order by id ASC ");
        if($this->session->userdata('username') == '' || $userPerStaAtt->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='transcript')
	{
      if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
         show_404();
      }
      $user=$this->session->userdata('username');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      $today=date('y-m-d');
        
      $data['fetch_term']=$this->Reportcard_model->fetch_term($max_year);
      $data['sessionuser']=$this->Reportcard_model->fetch_session_user($user);
      $data['academicyear']=$this->Reportcard_model->academic_year();
      $data['gradesec']=$this->Reportcard_model->fetch_gradesec($max_year);
      $data['branch']=$this->Reportcard_model->fetch_branch($max_year);
      $data['schools']=$this->Reportcard_model->fetch_school();
      if($_SESSION['usertype']===trim('Director')){
         $data['gradesec']=$this->Reportcard_model->fetch_grade_from_staffplace($user,$max_year);
      }else{
         $data['gradesecTeacher']=$this->Reportcard_model->fetch_session_gradesec($user,$max_year);
      }
      $this->load->view('teacher/'.$page,$data);
	} 
   function saveReasonIssue(){
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('reasonIssue')){
         $reasonIssue=$this->input->post('reasonIssue');
         $data=array(
            'leavingreason'=>$reasonIssue,
            'academicyear'=>$max_year,
            'datecreated'=>date('M-d-Y')
         );
         $queryCheck=$this->db->query("select * from leavingreason where leavingreason='$reasonIssue' ");
         if($queryCheck->num_rows()<1){
            $query=$this->db->insert('leavingreason',$data);
         }
      }
   }
   function fetchReasonIssue(){
      echo $this->Reportcard_model->fetchReasonIssue();
   }
   function deleteReasonIssue(){
      if($this->input->post('reasonIssueID')){
         $reasonIssueID=$this->input->post('reasonIssueID');
         $this->db->where('id',$reasonIssueID);
         $this->db->delete('leavingreason');
      }
   }
   function filterGradefromBranch(){
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];
      $accessbranch = sessionUseraccessbranch();
      if($this->input->post('academicyear')){
         $academicyear=$this->input->post('academicyear');
         echo $this->Reportcard_model->fetch_grade_from_branch($mybranch,$academicyear); 
      }
   }
   function fetchStudentForEdit(){
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];
      $accessbranch = sessionUseraccessbranch();
      if($this->input->post('academicyear')){
         $academicyear=$this->input->post('academicyear');
         $gradesec=$this->input->post('gradesec');
         echo $this->Reportcard_model->fetchStudentForEdit($mybranch,$academicyear,$gradesec); 
      }
   }
   function updateStudentLeavingStatus(){
      $user=$this->session->userdata('username');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('reasonIssue')){
         $id=$this->input->post('id');
         $reasonIssue=$this->input->post('reasonIssue');
         $stuid=$this->input->post('stuid');
         if($reasonIssue =='backToDefaultReason'){
            echo $this->Reportcard_model->backToDefaultReason($reasonIssue,$stuid,$max_year,$user);
         }else{
            echo $this->Reportcard_model->updateStudentLeavingStatus($reasonIssue,$stuid,$max_year,$user);
         }  
      }
   }
   function fetchStudentforCustom(){
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];
      $accessbranch = sessionUseraccessbranch();
      if($this->input->post('gradesec')){
         $gradesec=$this->input->post('gradesec');
         $academicyear=$this->input->post('academicyear');
         $fetchCustomStudent=$this->Reportcard_model->fetchCustomTranscriptStudent($academicyear,$gradesec,$mybranch);
         echo json_encode($fetchCustomStudent);
      }
   }
   function fetchThisStudentTranscript(){
      $accessbranch = sessionUseraccessbranch();
      $user=$this->session->userdata('username');
      $query_branch = $this->db->query("select * from users where username='$user'");
      $row_branch = $query_branch->row();
      $mybranch=$row_branch->branch;
      if($this->input->post('id')){
         $this->db->empty_table('transcript_list');
         $id=$this->input->post('id');
         $username=$this->input->post('username');
         $academicyear=$this->input->post('reportaca');
         $includeLetterTranscript=$this->input->post('includeBackPage');
         $noGrade=$this->input->post('noGrade');
         $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username,branch from users where username='$username' ");
         $rowStudent=$queryStudent->row();
         $gradesec=$rowStudent->gradesec;
         $branch=$rowStudent->branch;
         if($includeLetterTranscript==1){
            echo $this->Reportcard_model->letterCustomTranscriptENS($academicyear,$gradesec,$mybranch,$username,$noGrade);
         }else{
            echo $this->Reportcard_model->transcriptCustomENS($academicyear,$gradesec,$mybranch,$username,$noGrade);
         }
      }
   }
   function Fetchtranscript(){
      $accessbranch = sessionUseraccessbranch();
      $user=$this->session->userdata('username');
      $query_branch = $this->db->query("select * from users where username='$user'");
      $row_branch = $query_branch->row();
      $mybranch=$row_branch->branch;
      if($this->input->post('gradesec')){
         $this->db->empty_table('transcript_list');
         $academicyear=$this->input->post('academicyear');
         $gradesec=$this->input->post('gradesec');
         $noGrade=$this->input->post('noGrade');
         $includeLetterTranscript=$this->input->post('includeLetterTranscript');
         if($includeLetterTranscript==1){
            echo $this->Reportcard_model->letterTranscriptENS($academicyear,$gradesec,$mybranch,$noGrade);
         }else{
            echo $this->Reportcard_model->transcriptENS($academicyear,$gradesec,$mybranch,$noGrade);
         }
      }
   }
}