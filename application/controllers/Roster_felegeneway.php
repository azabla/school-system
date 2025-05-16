<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roster extends CI_Controller {
   public function __construct(){
      parent::__construct();
      $this->load->model('Reportcard_model');
      ob_start();
      $this->load->helper('cookie');
      $userLevel = userLevel();
      $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='roster' order by id ASC ");
      if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1')
      {
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
      if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
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
      $this->load->view('home-page/'.$page,$data);
   } 
   function filterGradefromBranch(){
      if($this->input->post('branchit')){
         $branch=$this->input->post('branchit');
         $academicyear=$this->input->post('academicyear');
         echo $this->Reportcard_model->fetch_grade_from_branch($branch,$academicyear); 
      }
   }
   function filterQuarterfromAcademicYear(){
      if($this->input->post('academicyear')){
         $academicyear=$this->input->post('academicyear');
         echo $this->Reportcard_model->fetch_quarter_from_academicYear($academicyear); 
      }
   }
   function Fetch_quarter_roster(){
      $YearName = sessionAcademicYear();
      $max_year=$YearName['year'];
      $accessbranch = sessionUseraccessbranch();
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];
      if($this->input->post('gradesecQuarter')){
         $gradesec=$this->input->post('gradesecQuarter');
         $branch=$this->input->post('branchQuarter');
         $roQuarter=$this->input->post('roQuarterQuarter');
         $reportaca=$this->input->post('reportacaQuarter');
         if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$roQuarter);
                  if($query){
                echo $this->Reportcard_model->quarteroster($reportaca,$gradesec,$branch,$roQuarter); 
            }
         }else{
            $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$roQuarter);
            if($query){
                echo $this->Reportcard_model->quarteroster($reportaca,$gradesec,$mybranch,$roQuarter); 
            }
         }
      }
   }
   function Fetchroster(){
      $YearName = sessionAcademicYear();
      $max_year=$YearName['year'];
      $accessbranch = sessionUseraccessbranch();
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];

      $query_quarter = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
      $row_quarter = $query_quarter->row();
      $rpQuarter=$row_quarter->quarter;
      if($this->input->post('gradesec')){
         $gradesec=$this->input->post('gradesec');
         $branch=$this->input->post('branch');
         $reportaca=$this->input->post('reportaca');
         $page=$this->input->post('pageBreak');
         $includeLetterTranscript=$this->input->post('includeLetterTranscript');
         if($includeLetterTranscript=="Letter"){
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                  if($query){
                     echo $this->Reportcard_model->rosterLetter($reportaca,$gradesec,$branch,$page);
                  }
            }else{
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
               if($query){
                  echo $this->Reportcard_model->rosterLetter($reportaca,$gradesec,$mybranch,$page); 
               }
            }
         }else if($includeLetterTranscript=="Both"){
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                  if($query){
                     echo $this->Reportcard_model->roster($reportaca,$gradesec,$branch,$page);
                  }
            }else{
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
               if($query){
                  echo $this->Reportcard_model->roster($reportaca,$gradesec,$mybranch,$page); 
               }
            }
         }else{
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                  if($query){
                     echo $this->Reportcard_model->rosterNumber($reportaca,$gradesec,$branch,$page);
                  }
            }else{
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
               if($query){
                  echo $this->Reportcard_model->rosterNumber($reportaca,$gradesec,$mybranch,$page); 
               }
            }
         }
      }
   }
   function filterGradesecfromBranch(){
      if($this->input->post('academicyear')){
         $academicyear=$this->input->post('academicyear');
         echo $this->Reportcard_model->filterGradesecfromBranch($academicyear); 
      }
   }
}