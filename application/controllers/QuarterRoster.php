<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class QuarterRoster extends CI_Controller {
   public function __construct(){
      parent::__construct();
      $this->load->model('Reportcard_model');
      ob_start();
      $this->load->helper('cookie');
      $userLevel = userLevel();
      $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='roster' order by id ASC "); 
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
	public function index($page='quarteroster')
	{
      if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
         show_404();
      }
      $user=$this->session->userdata('username');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      $today=date('y-m-d');
      $query_quarter = $this->db->query("select max(quarter) as quarter from mark");
      $row_quarter = $query_quarter->row();
      $max_quarter=$row_quarter->quarter;
        
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
   function Fetchroster(){
      $YearName = sessionAcademicYear();
      $max_year=$YearName['year'];
      $accessbranch = sessionUseraccessbranch();
      $branchName = sessionUserDetailNonStudent();
      $mybranch=$branchName['branch'];
      if($this->input->post('gradesec')){
         $gradesec=$this->input->post('gradesec');
         $branch=$this->input->post('branch');
         $roQuarter=$this->input->post('roQuarter');
         $reportaca=$this->input->post('reportaca');
         if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->Reportcard_model->quarterosterSeattle($reportaca,$gradesec,$branch,$roQuarter); 
         }else{
            echo $this->Reportcard_model->quarterosterSeattle($reportaca,$gradesec,$mybranch,$roQuarter); 
         }
      }
   }
}