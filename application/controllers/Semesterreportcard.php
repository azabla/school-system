<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Semesterreportcard extends CI_Controller {
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
    public function index($page='semesterreportcard')
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
        $data['grade']=$this->Reportcard_model->fetch_grade($max_year);/*New*/
        $this->load->view('home-page/'.$page,$data);
    } 
    function loadSavedCalculation(){ /*New*/
        echo $this->Reportcard_model->loadSavedCalculation();
    }
    function postCustomEvaluation(){ /*New*/
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['grade'])){
            $grades = $this->input->post('grade');
            $evnames=$this->input->post('evalname');
            $semester=trim($this->input->post('semester'));
            foreach ($evnames as $evname) {
                foreach ($grades as $grade) {
                    $query=$this->Reportcard_model->addSemesterCalculationMethod($grade,$max_year,$evname);
                    if($query){
                        $data=array(
                            'assesment_name'=>$evname,
                            'season'=>$semester,
                            'grade'=>$grade,
                            'academicyear'=>$max_year,
                            'created_by'=>$user,
                            'date_created'=>date('M-d-Y')
                        );
                        $query2=$this->db->insert('semester_calculation_method',$data);
                        if($query2){
                            echo '1';
                        }else{
                            echo '0';
                        }
                    }                    
                }
            }
            
        }
    }
    function remove_loadSavedCalculation(){ /*New*/
        $user=$this->session->userdata('username');
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('id',$userid);
            $query=$this->db->delete('semester_calculation_method');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function filterAssesmentCustomEvaluation(){ /*New*/
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->Reportcard_model->filterAssesmentCustomEvaluation($gradeGsanalysis,$max_year);   
        }
    }
    function filterGradefromBranch(){
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $academicyear=$this->input->post('academicyear');
            echo $this->Reportcard_model->fetch_grade_from_branch($branch,$academicyear); 
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->Reportcard_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function fetchStudentforCustom(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomSemesterCardStudent($reportaca,$gradesec,$branch);
                echo $fetchCustomStudent;
            }else{
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomSemesterCardStudent($reportaca,$gradesec,$mybranch);
                echo $fetchCustomStudent;
            }
        }
    }
    function filterQuarterfromAcademicYear(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->Reportcard_model->fetch_quarter_from_academicYear($academicyear); 
        }
    }
    function fetchThisStudentReportcard(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('id')){
            $id=$this->input->post('id');
            $reportaca=$this->input->post('reportaca');
            $includeBackPage=$this->input->post('includeBackPage');
            $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username,branch from users where id='$id' ");
            $rowStudent=$queryStudent->row();
            $gradesec=$rowStudent->gradesec;
            $branch=$rowStudent->branch;
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $data=$this->Reportcard_model->custom_ReportCard_Term($reportaca,$gradesec,$branch,$id,$includeBackPage);
                echo $data;
                
            }else{
                $data=$this->Reportcard_model->custom_ReportCard_Term($reportaca,$gradesec,$mybranch,$id,$includeBackPage);
                echo $data;
            }
        }
    }
    function Fetch_studentreportcard(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $includeBackPage=$this->input->post('includeBackPageDefault');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                $data=$this->Reportcard_model->default_ReportCard_Term($reportaca,$gradesec,$branch,$includeBackPage);
                echo $data;
            }else{
                $data=$this->Reportcard_model->default_ReportCard_Term($reportaca,$gradesec,$mybranch,$includeBackPage);
                echo $data;
            }
        }
    }
    function Fetch_studentreportcard_quarter(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $includeBackPage=$this->input->post('includeBackPageDefault');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                $data=$this->Reportcard_model->default_ReportCard_Quarter($reportaca,$gradesec,$branch,$includeBackPage);
                echo $data;
            }else{
                $data=$this->Reportcard_model->default_ReportCard_Quarter($reportaca,$gradesec,$mybranch,$includeBackPage);
                echo $data;
            }
        }
    }
    function fetchThisStudentReportcard_quarter(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('id')){
            $id=$this->input->post('id');
            $reportaca=$this->input->post('reportaca');
            $includeBackPage=$this->input->post('includeBackPage');
            $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username,branch from users where id='$id' ");
            $rowStudent=$queryStudent->row();
            $gradesec=$rowStudent->gradesec;
            $branch=$rowStudent->branch;
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $data=$this->Reportcard_model->custom_ReportCard_Quarter($reportaca,$gradesec,$branch,$id,$includeBackPage);
                echo $data;
                
            }else{
                $data=$this->Reportcard_model->custom_ReportCard_Quarter($reportaca,$gradesec,$mybranch,$id,$includeBackPage);
                echo $data;
            }
        }
    }
    function Fetch_studentreportcard_Term(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $includeBackPage=$this->input->post('includeBackPageDefault');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                $data=$this->Reportcard_model->default_ReportCard_Term($reportaca,$gradesec,$branch,$includeBackPage);
                echo $data;
            }else{
                $data=$this->Reportcard_model->default_ReportCard_Term($reportaca,$gradesec,$mybranch,$includeBackPage);
                echo $data;
            }
        }
    }
    function fetchThisStudentReportcard_Term(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('id')){
            $id=$this->input->post('id');
            $reportaca=$this->input->post('reportaca');
            $includeBackPage=$this->input->post('includeBackPage');
            $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username,branch from users where id='$id' ");
            $rowStudent=$queryStudent->row();
            $gradesec=$rowStudent->gradesec;
            $branch=$rowStudent->branch;
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $data=$this->Reportcard_model->custom_ReportCard_Term($reportaca,$gradesec,$branch,$id,$includeBackPage);
                echo $data;
                
            }else{
                $data=$this->Reportcard_model->custom_ReportCard_Term($reportaca,$gradesec,$mybranch,$id,$includeBackPage);
                echo $data;
            }
        }
    }
    function Fetch_student_semester_roster(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $pageBreak=$this->input->post('pageBreak');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                $data=$this->Reportcard_model->Fetch_student_semester_roster($reportaca,$gradesec,$branch,$pageBreak);
                echo $data;
            }else{
                $data=$this->Reportcard_model->Fetch_student_semester_roster($reportaca,$gradesec,$mybranch,$pageBreak);
                echo $data;
            }
        }
    }
}