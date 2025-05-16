<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sectiontransportreport extends CI_Controller {
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
	public function index($page='sectiontransportreport')
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
    function filterServicePlace(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->main_model->fetch_servicePlace_branchReportSection($branch,$grands_academicyear); 
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
            echo $this->main_model->fetch_thissection_from_branchNow($branch,$max_year); 
        }
    }
    function FecthThisGradeStudentService(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('summaryGSGrade')){
            $gs_branches=$this->input->post('gs_branches');
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $transportService=$this->input->post('transportService');
            $grands_academicyear=$this->input->post('grands_academicyear');
            $includeHeader=$this->input->post('includeHeader');
            for($i=0;$i<count($summaryGSGrade);$i++){
                $checkedGrade[]=$summaryGSGrade[$i];
            }
            for($i=0;$i<count($transportService);$i++){
                $checkedPlace[]=$transportService[$i];
            }
            if($includeHeader==='1'){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetch_thisTransportServiceSection($gs_branches,$checkedGrade,$checkedPlace,$grands_academicyear);
                }else{
                    echo $this->main_model->fetch_thisTransportServiceSection($branch,$checkedGrade,$checkedPlace,$grands_academicyear);
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetch_thisTransportServiceSection_count($gs_branches,$checkedGrade,$checkedPlace,$grands_academicyear);
                }else{
                    echo $this->main_model->fetch_thisTransportServiceSection_count($branch,$checkedGrade,$checkedPlace,$grands_academicyear);
                }
            }
        }
    }
    function FecthThisDivStudentNOName(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('summaryGSGrade')){
            $tot=0;$totfe=0;$totma=0;
            $gs_branches=$this->input->post('gs_branches');
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo '<div class="row">';
            foreach($summaryGSGrade as $summaryGSGrades){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetch_thisSummaryRecordNoName($gs_branches,$summaryGSGrades,$grands_academicyear);
                    $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where grade='$summaryGSGrades' and academicyear='$grands_academicyear' and branch='$gs_branches' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                    foreach ($query2->result() as $value) {
                        $tot=$value->studentcount + $tot;
                        $totma=$value->malecount + $totma;
                        $totfe=$value->femalecount + $totfe;
                    }
                }else{
                    echo $this->main_model->fetch_thisSummaryRecordNoName($branch,$summaryGSGrades,$grands_academicyear);
                    $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where grade='$summaryGSGrades' and academicyear='$grands_academicyear' and branch='$branch' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                    foreach ($query2->result() as $value) {
                        $tot=$value->studentcount + $tot;
                        $totma=$value->malecount + $totma;
                        $totfe=$value->femalecount + $totfe;
                    }
                }
            }
            echo '</div><div class="dropdown-divider"></div><div class="badge badge-light">
                <div class="alert-body">
                <i class="fas fa-check-circle"> </i> Male: '.$totma.' & Female: '.$totfe.' Total: '.$tot.'.
            </div></div>';
        }
    }
}