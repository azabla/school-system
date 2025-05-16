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
            echo $this->Reportcard_model->quarterosterNewayChallenge($reportaca,$gradesec,$branch,$roQuarter); 
         }else{
            echo $this->Reportcard_model->quarterosterNewayChallenge($reportaca,$gradesec,$mybranch,$roQuarter); 
         }
      }
   }
   function Fetchroster(){
      $YearName = sessionAcademicYear();
      $max_year=$YearName['year'];
      $accessbranch = sessionUseraccessbranch();
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];
      if($this->input->post('gradesec')){
         $gradesec=$this->input->post('gradesec');
         $branch=$this->input->post('branch');
         $reportaca=$this->input->post('reportaca');
         $page=$this->input->post('pageBreak');
         $queryQuarter=$this->db->query("select max(term) as quarter,q.termgroup from quarter as q cross join users as us where Academic_year='$reportaca' and q.termgrade=us.grade and us.gradesec='$gradesec' and us.academicyear='$reportaca' group by us.grade,q.termgroup ");
         if($queryQuarter->num_rows()>0){
            $row_quarter = $queryQuarter->row();
            $rpQuarter=$row_quarter->quarter;
            $termgroups=$row_quarter->termgroup;
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                  if($query){
                     echo $this->Reportcard_model->rosterNeway_challenge($reportaca,$gradesec,$branch,$page);
                  }
            }else{
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
               if($query){
                  echo $this->Reportcard_model->rosterNeway_challenge($reportaca,$gradesec,$mybranch,$page);
               }
            }
         }else{
            echo 'No data found'; 
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