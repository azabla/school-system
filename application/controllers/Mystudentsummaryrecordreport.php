<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mystudentsummaryrecordreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('teacher_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuView=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentVE' order by id ASC ");
        if($this->session->userdata('username') == '' || $uperStuView->num_rows()<1 || $userLevel!='2'){
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
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_teacher=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_term']=$this->teacher_model->fetch_term_4teacheer($max_year);
        $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
        $data['academicyear']=$this->teacher_model->academic_year_filter();
        /*if($_SESSION['usertype']===trim('Director')){*/
          $data['gradesec']=$this->teacher_model->fetch_grade_from_staffplace($user,$max_year);
        /*}else{
          $data['gradesecTeacher']=$this->teacher_model->fetchGradeForSummaryTeacher($user,$max_year);
        }*/
        $data['schools']=$this->teacher_model->fetch_school();
        $this->load->view('teacher/'.$page,$data);
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
        if($this->input->post('summaryGSGrade')){
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            for($i=0;$i<count($summaryGSGrade);$i++){
                $summaryGSGrades[]=$summaryGSGrade[$i];
            }
            echo $this->teacher_model->fetch_thisgradeAge($mybranch,$summaryGSGrades,$max_year);   
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
        if($this->input->post('summaryGSGrade')){
            $tot=0;$totfe=0;$totma=0;
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            foreach($summaryGSGrade as $summaryGSGrades){
                echo $this->teacher_model->fetch_thisSummaryRecord($branch,$summaryGSGrades,$max_year);
                $query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where gradesec='$summaryGSGrades' and academicyear='$max_year' and branch='$branch' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
                foreach ($query2->result() as $value) {
                    $tot=$value->studentcount + $tot;
                    $totma=$value->malecount + $totma;
                    $totfe=$value->femalecount + $totfe;
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
        if($this->input->post('summaryGSGrade')){
            $tot=0;$totfe=0;$totma=0;
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            for($i=0;$i<count($summaryGSGrade);$i++){
                $summaryGSGrades[]=$summaryGSGrade[$i];
            }
            echo $this->teacher_model->fetch_thisSummaryRecordNoName($branch,$summaryGSGrades,$max_year);
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
            $summaryGSGrade=$this->input->post('summaryGSGrade');
            $summaryGSAge=$this->input->post('summaryGSAge');
            for($i=0;$i<count($summaryGSGrade);$i++){
                $summaryGSGrades[]=$summaryGSGrade[$i];
            }
            for($i=0;$i<count($summaryGSAge);$i++){
                $summaryGSAges[]=$summaryGSAge[$i];
            }
            echo $this->teacher_model->fetch_thisSummaryRecordNoNameAge($branch,$summaryGSGrades,$max_year,$summaryGSAges);
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
        if($this->input->post('gradeSection')){
            $gradeSections=$this->input->post('gradeSection');
            for($i=0;$i<count($gradeSections);$i++){
                $attGradesec[]=$gradeSections[$i];
            }
            echo $this->teacher_model->fetchStudents4_asp_Attendance_Report($attGradesec,$branch,$max_year); 
        }
    }
}