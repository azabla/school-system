<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuView=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPhone' order by id ASC ");
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
	public function index($page='staffreport')
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
        if($this->input->post('gs_branches')){
            $tot=0;$totfe=0;$totma=0;
            $gs_branches=$this->input->post('gs_branches');
            $grands_academicyear=$this->input->post('grands_academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_thisStaff_SummaryRecordNoName($gs_branches,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_thisStaff_SummaryRecordNoName($branch,$grands_academicyear);
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
        if($this->input->post('gs_branches')){
            $tot=0;$totfe=0;$totma=0;
            $gs_branches=$this->input->post('gs_branches');
            $grands_academicyear=$this->input->post('grands_academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->FecthThisDivStaffAgeWithName($gs_branches,$grands_academicyear);

                $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE'  then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where usertype!='Student' and branch='$gs_branches' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                foreach ($query2->result() as $value) {
                    $tot=$value->studentcount + $tot;
                    $totma=$value->malecount + $totma;
                    $totfe=$value->femalecount + $totfe;
                }
            }else{
                echo $this->main_model->FecthThisDivStaffAgeWithName($branch,$grands_academicyear);
                $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where usertype!='Student' and branch='$branch' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                foreach ($query2->result() as $value) {
                    $tot=$value->studentcount + $tot;
                    $totma=$value->malecount + $totma;
                    $totfe=$value->femalecount + $totfe;
                } 
            }
            echo '<div class="badge badge-info text-center">
                <div class="alert-body">
                <i class="fas fa-check-circle"> </i> Male: '.$totma.' & Female: '.$totfe.' Total: '.$tot.'.
            </div></div>';
        }
    }
    function FecthThisDivStaffQualificationReport(){
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
        if($this->input->post('gs_branches')){
            $gs_branches=$this->input->post('gs_branches');
            $grands_academicyear=$this->input->post('grands_academicyear');
            $includeHeader=$this->input->post('includeHeader');
            if($includeHeader==='1'){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetch_thisStaff_QualificationReport_Name($gs_branches,$grands_academicyear);
                }else{
                    echo $this->main_model->fetch_thisStaff_QualificationReport_Name($branch,$grands_academicyear);
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetch_thisStaff_QualificationReport_noName($gs_branches,$grands_academicyear);
                }else{
                    echo $this->main_model->fetch_thisStaff_QualificationReport_noName($branch,$grands_academicyear);
                }
            }
        }
    }
}