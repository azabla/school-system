<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Copymovemark extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='viewstudentmark' order by id ASC ");
        if($this->session->userdata('username') == '' ||  $uaddMark->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='copymovemark')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function copyAssesmentQuarterMark(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gs_gradesec')){
            $gs_branches=trim($this->input->post('gs_branches'));
            $gs_gradesec=$this->input->post('gs_gradesec');
            $gs_subject=trim($this->input->post('gs_subject'));
            $fromQuarter=trim($this->input->post('fromQuarter'));
            $toQuarter=trim($this->input->post('toQuarter'));
            $grands_academicyear=trim($this->input->post('grands_academicyear'));
            for($i=0;$i<count($gs_gradesec);$i++){
                $check[]=$gs_gradesec[$i];
            }
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->copyAssesmentQuarterlyMark($gs_branches,$check,$gs_subject,$fromQuarter,$toQuarter,$grands_academicyear); 
                
            }else{
                echo $this->main_model->copyAssesmentQuarterlyMark($branch,$check,$gs_subject,$fromQuarter,$toQuarter,$grands_academicyear); 
            }
        }
    }
    function copyAssesmentSubjectMark(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gs_gradesec')){
            $gs_branches=trim($this->input->post('gs_branches'));
            $gs_gradesec=$this->input->post('gs_gradesec');
            $toSubject=trim($this->input->post('toSubject'));
            $fromSubject=trim($this->input->post('fromSubject'));
            $toQuarter=trim($this->input->post('toQuarter'));
            $grands_academicyear=trim($this->input->post('grands_academicyear'));
            for($i=0;$i<count($gs_gradesec);$i++){
                $check[]=$gs_gradesec[$i];
            }
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->copyAssesmentSubjectlyMark($gs_branches,$check,$fromSubject,$toSubject,$toQuarter,$grands_academicyear); 
                
            }else{
                echo $this->main_model->copyAssesmentSubjectlyMark($branch,$check,$fromSubject,$toSubject,$toQuarter,$grands_academicyear); 
            }
        }
    }
    function fetchThisGradeSubjectQuarterly(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade')){
            $academicyear=$this->input->post('academicyear');
            $grade=$this->input->post('grade');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeSubjectQuarterly($academicyear,$check);
            }else{
                echo $this->main_model->fetchThisGradeSubjectQuarterly($academicyear,$check);
            }
        }
    }
    function fetchThisGradeSubjectSubjectly(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade')){
            $academicyear=$this->input->post('academicyear');
            $grade=$this->input->post('grade');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeSubjectQuarterly($academicyear,$check);
            }else{
                echo $this->main_model->fetchThisGradeSubjectQuarterly($academicyear,$check);
            }
        }
    }
    function fetchThisGradeSubjectFromark(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade')){
            $academicyear=$this->input->post('academicyear');
            $toQuarter=$this->input->post('toQuarter');
            $grade=$this->input->post('grade');
            $gs_branches=$this->input->post('gs_branches');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeSubjectFromark($gs_branches,$academicyear,$check,$toQuarter);
            }else{
                echo $this->main_model->fetchThisGradeSubjectFromark($gs_branches,$academicyear,$check,$toQuarter);
            }
        }
    }
    function copyMarkStudentListQuarter(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('branch')){
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeStudentMoveCopyMarkQuarterly($academicyear,$branch);
            }else{
                echo $this->main_model->fetchThisGradeStudentMoveCopyMarkQuarterly($academicyear,$mybranch);
            }
        }
    }
    function fetchGradeWithInSubject(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('branch')){
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeStudentMoveCopyMarkSubject($academicyear,$branch);
            }else{
                echo $this->main_model->fetchThisGradeStudentMoveCopyMarkSubject($academicyear,$mybranch);
            }
        }
    }
    function copyMarkStudentList(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('branch')){
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeStudentMoveCopyMark($academicyear,$branch);
            }else{
                echo $this->main_model->fetchThisGradeStudentMoveCopyMark($academicyear,$mybranch);
            }
        }
    }
    function fetchThisGradeSubject(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade')){
            $academicyear=$this->input->post('academicyear');
            $grade=$this->input->post('grade');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeSubjectMoveCopyMark($academicyear,$check);
            }else{
                echo $this->main_model->fetchThisGradeSubjectMoveCopyMark($academicyear,$check);
            }
        }
    }
    function copyAssesmentMark(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gs_gradesec')){
            $gs_branches=trim($this->input->post('gs_branches'));
            $gs_gradesec=$this->input->post('gs_gradesec');
            $gs_subject=trim($this->input->post('gs_subject'));
            $gs_quarter=trim($this->input->post('gs_quarter'));
            $grands_academicyear=trim($this->input->post('grands_academicyear'));
            for($i=0;$i<count($gs_gradesec);$i++){
                $check[]=$gs_gradesec[$i];
            }
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->copyAssesmentMark($gs_branches,$check,$gs_subject,$gs_quarter,$grands_academicyear); 
                
            }else{
                echo $this->main_model->copyAssesmentMark($branch,$check,$gs_subject,$gs_quarter,$grands_academicyear); 
            }
        }
    } 
    function searchStudentsToCopyMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->searchStudentsToCopyMark($searchItem,$max_year);
            }else{
                echo $this->main_model->searchStudentsToCopyMarkNotAccess($searchItem,$branch,$max_year);
            }
            
        }
    }
    function copyAssesmentStudentMark(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('stuIdArray')){
            $stuIdArray=$this->input->post('stuIdArray');
            $fromQuarter=trim($this->input->post('fromQuarter'));
            $toQuarter=trim($this->input->post('toQuarter'));
            for($i=0;$i<count($stuIdArray);$i++){
                $check[]=$stuIdArray[$i];
            }
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->copyAssesmentStudentMark($check,$fromQuarter,$toQuarter,$max_year); 
                
            }else{
                echo $this->main_model->copyAssesmentStudentMark($check,$fromQuarter,$toQuarter,$max_year); 
            }
        }
    }
}