<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStupro=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPr' order by id ASC "); 
        if($this->session->userdata('username') == '' || $uperStupro->num_rows() < 1 || $userLevel!='1'){
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
    public function index($page='registration')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academicYear4Registration();
        $data['fetch_grade_fromsp_toadd_neweaxm']=$this->main_model->fetch_grade_from_staffplace($user,$max_year);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
    } 
    function fetch_grade_non_registration(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('academicyear')){
            $grade=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $year=$this->input->post('academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_grade_non_registration($branch,$grade,$year); 
            }else{
                echo $this->main_model->fetch_grade_non_registration($mybranch,$grade,$year); 
            }
        }
    }
    function fetch_grade_4registration(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('gradesec_rg')){
            $grade=$this->input->post('gradesec_rg');
            $branch=$this->input->post('branch');
            $year=$this->input->post('academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->student_registration($branch,$grade,$year); 
            }else{
                echo $this->main_model->student_registration($mybranch,$grade,$year); 
            }
        }
    }
    function Fetch_academicyear_branch(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->fetch_academicyear_branch($academicyear); 
        } 
    }
    function filtergrade_4registeration(){
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['branchRegistration'])){
            $academicyear=$this->input->post('academicyear');
            $branchRegistration=$this->input->post('branchRegistration');
            echo $this->main_model->filtergrade_4branch($academicyear,$branchRegistration); 
        }
    }
    function studentPromotionPromoted(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            foreach($stuid as $stuidNow){
                echo $this->main_model->studentPromotionPromoteDiamond($stuidNow,$academicyear); 
            }
        } 
    }
    function studentPromotionPromotedN(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            foreach($stuid as $stuidNow){
                echo $this->main_model->studentPromotionPromoteDiamondN($stuidNow,$academicyear); 
            }
        } 
    }
    function studentPromotionPromotedS(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            foreach($stuid as $stuidNow){
                echo $this->main_model->studentPromotionPromoteDiamondS($stuidNow,$academicyear); 
            }
        } 
    }
    function studentPromotionDetained(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            foreach($stuid as $stuidNow){
                echo $this->main_model->studentPromotionDetained($stuidNow,$academicyear); 
            }
        } 
    }
    function clearRegistration(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->clearRegistration($stuid,$academicyear); 
        }
    }
    function startAutoPromotion(){
        if($this->input->post('toAcademicYear')){
            $toAcademicYear=$this->input->post('toAcademicYear');
            $fromAcademicYear=$this->input->post('fromAcademicYear');
            if($fromAcademicYear >= $toAcademicYear){
                echo '<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Please select correct academic year.
            </div></div>';
            }else{
               echo $this->main_model->startAutoPromotionDiamond($fromAcademicYear,$toAcademicYear); 
            }
        }
    }
}