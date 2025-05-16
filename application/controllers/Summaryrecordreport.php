<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summaryrecordreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuView=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentVE' order by id ASC ");
        if($this->session->userdata('username') == '' || $uperStuView->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='summaryrecordreport')
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
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function Filter_grade_from_branch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetch_asp_student_list($branch,$max_year); 
        }
    }
    function fetchStudents4_asp_Attendance_Report(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('attBranches')){
            $gradeSections=$this->input->post('gradeSection');
            $attBranches=$this->input->post('attBranches');
            for($i=0;$i<count($gradeSections);$i++){
                $attGradesec[]=$gradeSections[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchStudents4_asp_Attendance_Report($attGradesec,$attBranches,$max_year); 
            }else{
                echo $this->main_model->fetchStudents4_asp_Attendance_Report($attGradesec,$branch,$max_year); 
            }
        }
    }
    function filterGrade(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->fetch_thisgrade_from_branchNow($branch,$academicyear); 
        }
    }
    function fetchThisGradeAge(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('summaryGSGrade')){
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $gs_branches=$this->input->post('gs_branches');
            $grands_academicyear=$this->input->post('grands_academicyear');
            for($i=0;$i<count($summaryGSGrade);$i++){
                $summaryGSGrades[]=$summaryGSGrade[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_thisgradeAge($gs_branches,$summaryGSGrades,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_thisgradeAge($mybranch,$summaryGSGrades,$grands_academicyear);
            }
        }
    }
    function FecthThisDivStudent(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('summaryGSGrade')){
            $tot=0;$totfe=0;$totma=0;
            $gs_branches=$this->input->post('gs_branches');
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            foreach($summaryGSGrade as $summaryGSGrades){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetch_thisSummaryRecord($gs_branches,$summaryGSGrades,$grands_academicyear);
                    $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE'  then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where grade='$summaryGSGrades' and academicyear='$grands_academicyear' and branch='$gs_branches' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                    foreach ($query2->result() as $value) {
                        $tot=$value->studentcount + $tot;
                        $totma=$value->malecount + $totma;
                        $totfe=$value->femalecount + $totfe;
                    }
                }else{
                    echo $this->main_model->fetch_thisSummaryRecord($branch,$summaryGSGrades,$grands_academicyear);
                    $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where grade='$summaryGSGrades' and academicyear='$grands_academicyear' and branch='$branch' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                    foreach ($query2->result() as $value) {
                        $tot=$value->studentcount + $tot;
                        $totma=$value->malecount + $totma;
                        $totfe=$value->femalecount + $totfe;
                    }
                }
            }
            echo '<div class="badge badge-light">
                <div class="alert-body">
                <i class="fas fa-check-circle"> </i> Male: '.$totma.' & Female: '.$totfe.' Total: '.$tot.'.
            </div></div>';
        }
    }
    function FecthThisDivStudentNOName(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('summaryGSGrade')){
            $tot=0;$totfe=0;$totma=0;
            $gs_branches=$this->input->post('gs_branches');
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            for($i=0;$i<count($summaryGSGrade);$i++){
                $summaryGSGrades[]=$summaryGSGrade[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_thisSummaryRecordNoName($gs_branches,$summaryGSGrades,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_thisSummaryRecordNoName($branch,$summaryGSGrades,$grands_academicyear);
            }
        }
    }
    function FecthThisDivStudentAgeWithName(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('summaryGSGrade')){
            $tot=0;$totfe=0;$totma=0;
            $gs_branches=$this->input->post('gs_branches');
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            $summaryGSAge=$this->input->post('summaryGSAge');
            for($i=0;$i<count($summaryGSAge);$i++){
                $summaryGSAges[]=$summaryGSAge[$i];
            }
            for($i=0;$i<count($summaryGSGrade);$i++){
                $summaryGSGrades[]=$summaryGSGrade[$i];
            }
            foreach($summaryGSGrade as $summaryGSGrades){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                   
                    foreach($summaryGSAge as $summaryGSAges){
                        echo $this->main_model->FecthThisDivStudentAgeWithName($gs_branches,$summaryGSGrades,$grands_academicyear,$summaryGSAges);
                        $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE'  then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where grade='$summaryGSGrades' and academicyear='$grands_academicyear' and branch='$gs_branches' and age='$summaryGSAges' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                        foreach ($query2->result() as $value) {
                            $tot=$value->studentcount + $tot;
                            $totma=$value->malecount + $totma;
                            $totfe=$value->femalecount + $totfe;
                        }
                    }
                }else{
                    
                    foreach($summaryGSAges as $summaryGSAges){
                        echo $this->main_model->FecthThisDivStudentAgeWithName($branch,$summaryGSGrades,$grands_academicyear,$summaryGSAges);
                        $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where grade='$summaryGSGrades' and academicyear='$grands_academicyear' and branch='$branch' and age='$summaryGSAges' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                        foreach ($query2->result() as $value) {
                            $tot=$value->studentcount + $tot;
                            $totma=$value->malecount + $totma;
                            $totfe=$value->femalecount + $totfe;
                        }
                    }
                }
            }
            echo '<div class="badge badge-light">
                <div class="alert-body">
                <i class="fas fa-check-circle"> </i> Male: '.$totma.' & Female: '.$totfe.' Total: '.$tot.'.
            </div></div>';
        }
    }
    function FecthThisDivStudentNoNameAge(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('summaryGSGrade')){
            $tot=0;$totfe=0;$totma=0;
            $gs_branches=$this->input->post('gs_branches');
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            $summaryGSAge=$this->input->post('summaryGSAge');
            for($i=0;$i<count($summaryGSGrade);$i++){
                $summaryGSGrades[]=$summaryGSGrade[$i];
            }
            for($i=0;$i<count($summaryGSAge);$i++){
                $summaryGSAges[]=$summaryGSAge[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_thisSummaryRecordNoNameAge($gs_branches,$summaryGSGrades,$grands_academicyear,$summaryGSAges);
            }else{
                echo $this->main_model->fetch_thisSummaryRecordNoNameAge($branch,$summaryGSGrades,$grands_academicyear,$summaryGSAges);
            }
        }
    }
}