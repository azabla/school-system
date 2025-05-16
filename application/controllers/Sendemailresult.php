<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sendemailresult extends CI_Controller {
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
    public function index($page='sendemail')
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
    function filterQuarterfromAcademicYear(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->Reportcard_model->fetch_quarter_from_academicYear($academicyear); 
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
            $rpQuarter=$this->input->post('rpQuarter');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomStudentResultEmail($reportaca,$gradesec,$branch,$rpQuarter);
                echo json_encode($fetchCustomStudent);
            }else{
                $fetchCustomStudent=$this->Reportcard_model->fetchCustomStudentResultEmail($reportaca,$gradesec,$mybranch,$rpQuarter);
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
            $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username,branch ,email,optional_email,grade from users where id='$id' ");
            if($queryStudent->num_rows()>0){
                $rowStudent=$queryStudent->row();
                $gradesec=$rowStudent->gradesec;
                $branch=$rowStudent->branch;
                $studenteMail=trim($rowStudent->email);
                $studenteMail2=trim($rowStudent->optional_email);
                $fname=$rowStudent->fname;
                $grade=$rowStudent->grade;
                if($studenteMail!='' || !empty($studenteMail)){
                    if($grade=='KG3' || $grade=='KG4' || $grade=='KG5' || $grade=='PREP' || $grade=='Prep' | $grade=='Kg3' || $grade=='Kg4' || $grade=='Kg5')
                    {
                        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                            $data=$this->Reportcard_model->sendEmailThisReportCardKelemKG($reportaca,$gradesec,$branch,$rpQuarter,$id,$studenteMail,$studenteMail2);
                            echo json_encode($data); 
                        }else{
                            $data=$this->Reportcard_model->sendEmailThisReportCardKelemKG($reportaca,$gradesec,$mybranch,$rpQuarter,$id,$studenteMail,$studenteMail2);
                            echo json_encode($data);  
                        } 
                        
                    }else
                    {
                        $queryCheck=$this->db->query("select * from subject_branch_enable where academicyear='$reportaca' and enable_status='1' ");
                        if($queryCheck->num_rows()>0){
                            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                                $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$branch,$rpQuarter);
                                if($query){
                                    $data=$this->Reportcard_model->sendEmailThisReportCardKelem($reportaca,$gradesec,$branch,$rpQuarter,$id,$studenteMail,$studenteMail2);
                                    echo json_encode($data);
                                }
                            }else{
                                $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$mybranch,$rpQuarter);
                                if($query){
                                    $data=$this->Reportcard_model->sendEmailThisReportCardKelem($reportaca,$gradesec,$mybranch,$rpQuarter,$id,$studenteMail,$studenteMail2);
                                    echo json_encode($data);
                                }
                            }
                        }else{
                           if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                                $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                                if($query){
                                    $data=$this->Reportcard_model->sendEmailThisReportCardKelem($reportaca,$gradesec,$branch,$rpQuarter,$id,$studenteMail,$studenteMail2);
                                    echo json_encode($data);
                                }
                            }else{
                                $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                                if($query){
                                    $data=$this->Reportcard_model->sendEmailThisReportCardKelem($reportaca,$gradesec,$mybranch,$rpQuarter,$id,$studenteMail,$studenteMail2);
                                    echo json_encode($data);
                                }
                            } 
                        }
                    }
                }else{
                    $data='<span class="text-danger">Please set correct email for student '.$fname.' .</span>';
                    echo json_encode($data);
                }
            }else{
                $data='<span class="text-danger">No student found.</span>';
                echo json_encode($data);
            }
        }
    }
    function fetchThisGradeReportcard(){
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $rpQuarter=$this->input->post('rpQuarter');
            $queryStudent=$this->db->query(" Select grade from users where gradesec='$gradesec' and branch='$branch' and academicyear='$reportaca' group by grade ");
            if($queryStudent->num_rows()>0){
                $rowStudent=$queryStudent->row();
                $grade=$rowStudent->grade;
                if($grade=='KG3' || $grade=='KG4' || $grade=='KG5' || $grade=='PREP' || $grade=='Prep' | $grade=='Kg3' || $grade=='Kg4' || $grade=='Kg5')
                {
                    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                        $data=$this->Reportcard_model->sendEmailGroupReportCardKelemKG($reportaca,$gradesec,$branch,$rpQuarter);
                        echo $data; 
                    }else{
                        $data=$this->Reportcard_model->sendEmailGroupReportCardKelemKG($reportaca,$gradesec,$mybranch,$rpQuarter);
                        echo $data;  
                    } 
                    
                }else
                {
                    $queryCheck=$this->db->query("select * from subject_branch_enable where academicyear='$reportaca' and enable_status='1' ");
                    if($queryCheck->num_rows()>0){
                        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                            $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$branch,$rpQuarter);
                            if($query){
                                $data=$this->Reportcard_model->sendGroupReportcard_KelemGrade($reportaca,$gradesec,$branch,$rpQuarter);
                                echo json_encode($data);
                            }
                        }else{
                            $query=$this->Reportcard_model->update_reportcardResult_branch($reportaca,$gradesec,$mybranch,$rpQuarter);
                            if($query){
                                $data=$this->Reportcard_model->sendGroupReportcard_KelemGrade($reportaca,$gradesec,$mybranch,$rpQuarter);
                                echo json_encode($data);
                            }
                        }
                    }else{
                       if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                            $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                            if($query){
                                $data=$this->Reportcard_model->sendGroupReportcard_KelemGrade($reportaca,$gradesec,$branch,$rpQuarter);
                                echo json_encode($data);
                            }
                        }else{
                            $query=$this->Reportcard_model->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                            if($query){
                                $data=$this->Reportcard_model->sendGroupReportcard_KelemGrade($reportaca,$gradesec,$mybranch,$rpQuarter);
                                echo json_encode($data);
                            }
                        } 
                    }
                }
                
            }else{
                $data='<span class="text-danger">No student found.</span>';
                echo json_encode($data);
            }
        }
    }

}