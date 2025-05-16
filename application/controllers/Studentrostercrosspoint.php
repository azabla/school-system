<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Studentroster extends CI_Controller {
   public function __construct(){
        parent::__construct();
        $this->load->model('Reportcard_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='roster' order by id ASC ");
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
	public function index($page='roster')
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
   function Fetch_quarter_roster(){
      $YearName = sessionAcademicYear();
      $max_year=$YearName['year'];
      $accessbranch = sessionUseraccessbranch();
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];
      if($this->input->post('gradesecQuarter')){
         $gradesec=$this->input->post('gradesecQuarter');
         $roQuarter=$this->input->post('roQuarterQuarter');
         $reportaca=$this->input->post('reportacaQuarter');
         echo $this->Reportcard_model->quarterosterCrosspoint($reportaca,$gradesec,$mybranch,$roQuarter); 
      }
   }
   function filterQuarterfromAcademicYear(){
      if($this->input->post('academicyear')){
         $academicyear=$this->input->post('academicyear');
         echo $this->Reportcard_model->fetch_quarter_from_academicYear($academicyear); 
      }
   }
   function filterGradefromBranch(){
      $user=$this->session->userdata('username');
      $userType=$this->session->userdata('usertype');
      $query_branch = $this->db->query("select * from users where username='$user'");
      $row_branch = $query_branch->row();
      $mybranch=$row_branch->branch;
      $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
      $rowaccessbranch = $queryAccessBranch->row_array();
      $accessbranch=$rowaccessbranch['accessbranch'];
      if($this->input->post('branchit')){
         $branch=$this->input->post('branchit');
         $academicyear=$this->input->post('academicyear');
         echo $this->Reportcard_model->fetch_grade_from_branch($mybranch,$academicyear); 
      }
    }
   function Fetchroster(){
      $user=$this->session->userdata('username');
      $userType=$this->session->userdata('usertype');
      $query_branch = $this->db->query("select * from users where username='$user'");
      $row_branch = $query_branch->row();
      $mybranch=$row_branch->branch;
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;

      $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
      $rowaccessbranch = $queryAccessBranch->row_array();
      $accessbranch=$rowaccessbranch['accessbranch'];
      if($this->input->post('gradesec')){
         $gradesec=$this->input->post('gradesec');
         $branch=$this->input->post('branch');
         $reportaca=$this->input->post('reportaca');
         $page=$this->input->post('pageBreak');
         $includeLetterTranscript=$this->input->post('includeLetterTranscript');
         if($includeLetterTranscript=="Letter"){
            echo $this->Reportcard_model->rosterCrosspoint($reportaca,$gradesec,$mybranch,$page); 
         }else if($includeLetterTranscript=="Both"){
            echo $this->Reportcard_model->rosterCrosspoint($reportaca,$gradesec,$mybranch,$page); 
         }else{
            echo $this->Reportcard_model->rosterCrosspoint($reportaca,$gradesec,$mybranch,$page);
         }
      }
   }
}