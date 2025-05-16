<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reportcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Reportcard_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('tableName','studentCard');
        $this->db->where('allowed','reportcard');
        $usergroupPermission=$this->db->get('usergrouppermission');
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
    public function index($page='reportcard')
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
    function adjustRcTable(){
        $this->load->dbforge();
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_quarter = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        echo $this->Reportcard_model->prepareRCTable($max_year);
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
            $rpQuarter=$this->input->post('rpQuarter');
            $queryCheck=$this->db->query("select * from subject_branch_enable where academicyear='$reportaca' and enable_status='1' ");
            if($queryCheck->num_rows()>0){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardByQuarter($reportaca,$gradesec,$branch,$rpQuarter,$includeBackPage);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardByQuarter($reportaca,$gradesec,$mybranch,$rpQuarter,$includeBackPage);
                        echo json_encode($data);
                    }
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardByQuarter($reportaca,$gradesec,$branch,$rpQuarter,$includeBackPage);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardByQuarter($reportaca,$gradesec,$mybranch,$rpQuarter,$includeBackPage);
                        echo json_encode($data);
                    }
                }
            }
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
            $rpQuarter=$this->input->post('rpQuarter');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomStudent($reportaca,$gradesec,$branch,$rpQuarter);
                echo json_encode($fetchCustomStudent);
            }else{
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomStudent($reportaca,$gradesec,$mybranch,$rpQuarter);
                echo json_encode($fetchCustomStudent);
            }
        }
    }
    function fetchThisStudentReportcard(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('id')){
            $id=$this->input->post('id');
            $rpQuarter=$this->input->post('quarter');
            $reportaca=$this->input->post('reportaca');
            $includeBackPage=$this->input->post('includeBackPage');
            $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username,branch from users where id='$id' ");
            $rowStudent=$queryStudent->row();
            $gradesec=$rowStudent->gradesec;
            $branch=$rowStudent->branch;
            $queryCheck=$this->db->query("select * from subject_branch_enable where academicyear='$reportaca' and enable_status='1' ");
            if($queryCheck->num_rows()>0){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->customReportCard($reportaca,$gradesec,$branch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->customReportCard($reportaca,$gradesec,$mybranch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                }
            }else{
               if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->customReportCard($reportaca,$gradesec,$branch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->customReportCard($reportaca,$gradesec,$mybranch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                } 
            }
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->Reportcard_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function grouphalfstudentreportcard(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $includeStudentBasicSkill=$this->input->post('includeStudentBasicSkill');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $rpQuarter=$this->input->post('rpQuarter');
            $queryCheck=$this->db->query("select * from subject_branch_enable where academicyear='$reportaca' and enable_status='1' ");
            if($queryCheck->num_rows()>0){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardHalfQuarter($reportaca,$gradesec,$branch,$rpQuarter,$includeStudentBasicSkill);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardHalfQuarter($reportaca,$gradesec,$mybranch,$rpQuarter,$includeStudentBasicSkill);
                        echo json_encode($data);
                    }
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardHalfQuarter($reportaca,$gradesec,$branch,$rpQuarter,$includeStudentBasicSkill);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardHalfQuarter($reportaca,$gradesec,$mybranch,$rpQuarter,$includeStudentBasicSkill);
                        echo json_encode($data);
                    }
                }
            }
        }
    }
    function fetchCustomStudentHalfReport(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $rpQuarter=$this->input->post('rpQuarter');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomStudentHalfReport($reportaca,$gradesec,$branch,$rpQuarter);
                echo json_encode($fetchCustomStudent);
            }else{
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomStudentHalfReport($reportaca,$gradesec,$mybranch,$rpQuarter);
                echo json_encode($fetchCustomStudent);
            }
        }
    }
    function customHalfstudentreportcard(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('id')){
            $id=$this->input->post('id');
            $rpQuarter=$this->input->post('quarter');
            $reportaca=$this->input->post('reportaca');
            $includeBackPage=$this->input->post('includeBackPage');
            $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username, branch from users where id='$id' ");
            $rowStudent=$queryStudent->row();
            $gradesec=$rowStudent->gradesec;
            $branch=$rowStudent->branch;
            $queryCheck=$this->db->query("select * from subject_branch_enable where academicyear='$reportaca' and enable_status='1' ");
            if($queryCheck->num_rows()>0){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardCustomHalfQuarter($reportaca,$gradesec,$branch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardCustomHalfQuarter($reportaca,$gradesec,$mybranch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardCustomHalfQuarter($reportaca,$gradesec,$branch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                    if($query){
                        $data=$this->Reportcard_model->reportcardCustomHalfQuarter($reportaca,$gradesec,$mybranch,$rpQuarter,$id,$includeBackPage);
                        echo json_encode($data);
                    }
                }
            }
        }
    }
    function fetch_assesment_report(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $includeHeader=$this->input->post('includeHeader');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $rpQuarter=$this->input->post('rpQuarter');
            $assesname=$this->input->post('assement_name');
            for($i=0;$i<count($assesname);$i++){
                $assesGsanalysis[]=$assesname[$i];
            }
            $queryCheck=$this->db->query("select * from subject_branch_enable where academicyear='$reportaca' and enable_status='1' ");
            if($queryCheck->num_rows()>0){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                    $query=$this->Reportcard_model->update_reportcard_custom_Result_subject_branch($reportaca,$gradesec,$branch,$rpQuarter,$assesGsanalysis);
                    if($query){
                        $data=$this->Reportcard_model->custom_mid_report($reportaca,$gradesec,$branch,$rpQuarter,$assesGsanalysis,$includeHeader);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcard_custom_Result_subject_branch($reportaca,$gradesec,$mybranch,$rpQuarter,$assesGsanalysis);
                    if($query){
                        $data=$this->Reportcard_model->custom_mid_report($reportaca,$gradesec,$mybranch,$rpQuarter,$assesGsanalysis,$includeHeader);
                        echo json_encode($data);
                    }
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                    $query=$this->Reportcard_model->update_reportcard_custom_Result($reportaca,$gradesec,$branch,$rpQuarter,$assesGsanalysis);
                    if($query){
                        $data=$this->Reportcard_model->custom_mid_report($reportaca,$gradesec,$branch,$rpQuarter,$assesGsanalysis,$includeHeader);
                        echo json_encode($data);
                    }
                }else{
                    $query=$this->Reportcard_model->update_reportcard_custom_Result($reportaca,$gradesec,$mybranch,$rpQuarter,$assesGsanalysis);
                    if($query){
                        $data=$this->Reportcard_model->custom_mid_report($reportaca,$gradesec,$mybranch,$rpQuarter,$assesGsanalysis,$includeHeader);
                        echo json_encode($data);
                    }
                }
            }
        }
    }
    function filter_evaluation4analysis(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade2analysis')){
          $grade2analysis=$this->input->post('grade2analysis');
          $branch=$this->input->post('branch2analysis');
          $quarter=$this->input->post('analysis_quarter');
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
              echo $this->Reportcard_model->fetch_custom_assesment_report($branch,$grade2analysis,$max_year,$quarter); 
          }else{
              echo $this->Reportcard_model->fetch_custom_assesment_report($mybranch,$grade2analysis,$max_year,$quarter); 
          }
        }
    }
}