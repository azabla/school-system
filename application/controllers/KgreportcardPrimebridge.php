<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kgreportcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Reportcard_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='reportcard' order by id ASC ");
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
    public function index($page='kgreportcard')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
          show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $query_quarter = $this->db->query("select max(term) as quarter from quarter where Academic_year ='$max_year' ");
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
    function adjustRcTable(){
        $this->load->dbforge();
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->Reportcard_model->prepareRCTable($max_year);
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
    function fetchstudentreportcard(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $includeBackPage=$this->input->post('includeBackPageDefault');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $rpQuarter=$this->input->post('rpQuarter');
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$reportaca' and term='$rpQuarter' ");
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                if($query){
                    $data=$this->Reportcard_model->reportcardKg_primebridge($reportaca,$gradesec,$branch,$rpQuarter,$includeBackPage);
                    echo json_encode($data);
                }
            }else{
               $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                if($query){
                    $data=$this->Reportcard_model->reportcardKg_primebridge($reportaca,$gradesec,$mybranch,$rpQuarter,$includeBackPage);
                    echo json_encode($data);
                }
            }
        }
    }
}