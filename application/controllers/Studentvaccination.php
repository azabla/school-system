<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentvaccination extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentVaccination' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='1'){
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
    public function index($page='studentvaccination')
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
       
        if(isset($_POST['drop_id'])){
            $id=$this->input->post('drop_id');
            $this->main_model->inactive_student($id);
        }
        if(isset($_POST['post_id'])){
            $id=$this->input->post('post_id');
            $this->main_model->delete_student($id);
        }
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['gradeGroups']=$this->main_model->studentRegistrationGrade($max_year);
        $this->load->view('home-page/'.$page,$data);
    }
    function save_new_vaccination(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('vaccinationName')){
            $vaccinationName=trim($this->input->post('vaccinationName'));
            $data=array(
                'vaccinationName'=>$vaccinationName,
                'createdby'=>$user,
                'created_at'=>date('M-d-Y')
            );
            $query=$this->main_model->register_new_vaccination($data,$vaccinationName);
            if($query){
                echo '<span class="text-success">Saved Successfully</span>';
            }else{
                echo '<span class="text-danger">Vaccination Exists.</span>';
            }
        }
    }
    function filterOnlyGradeFromBranch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->main_model->fetchOnlyGradeFromBranch_group($branch,$grands_academicyear); 
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function fecth_this_tudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        if($this->input->post('gs_branches')){
            $gs_branches=$this->input->post('gs_branches');
            $onlyGrade=$this->input->post('onlyGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_students_vaccination_info($gs_branches,$onlyGrade,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_students_vaccination_info($branch,$onlyGrade,$grands_academicyear);
            }
        } 
    }
    function editstudent_vaccination(){
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            $newAcademicYear=$this->input->post('newAcademicYear');
            echo $this->main_model->editstudent_vaccination($editedId,$newAcademicYear);
        }
    }
    function editstudent_illnessReport(){
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            $newAcademicYear=$this->input->post('newAcademicYear');
            echo $this->main_model->editstudent_illnessReport($editedId,$newAcademicYear);
        }
    }
    function updateStudents_Vaccination(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $stuAcademicYear=$this->input->post('stuAcademicYear_vaccination');
        $stuid=$this->input->post('stuStuid_vaccination');
        $username=$this->input->post('stUsername_Vaccination');
        $Allergies_Medications_Conditions=$this->input->post('Allergies_Medications_Conditions');
        $Needs_Disabilities=$this->input->post('Needs_Disabilities');
        $Hospital_Name=$this->input->post('Hospital_Name');
        $Hospital_Phone=$this->input->post('Hospital_Phone');
        $Hospital_Adress=$this->input->post('Hospital_Adress');
        $Hospital_Email=$this->input->post('Hospital_Email');
        $Hospital_Contact_Person=$this->input->post('Hospital_Contact_Person');
        $vaccinationName=$this->input->post('vaccination_Name');
        $Permit_Exceptional_Case=$this->input->post('Permit_Exceptional_Case');
        $data1=array(
            'stuid'=>$stuid,
            'Allergies_Medications_Conditions'=>$Allergies_Medications_Conditions,
            'Needs_Disabilities'=>$Needs_Disabilities,
            'Hospital_Name'=>$Hospital_Name,
            'Hospital_Phone'=>$Hospital_Phone,
            'Hospital_Adress'=>$Hospital_Adress,
            'Hospital_Email'=>$Hospital_Email,
            'Hospital_Contact_Person'=>$Hospital_Contact_Person,
            'Permit_Exceptional_Case'=>$Permit_Exceptional_Case,
            'createdby'=>$user,
            'created_at'=>date('M-d-Y'),
            'academicyear'=>$stuAcademicYear
        );
        if(!empty($vaccinationName)){
            foreach ($vaccinationName as $vaccinationNames) {            
                $data[]=array(
                    'stuid'=>$stuid,
                    'vaccinationName'=>$vaccinationNames,
                    'academicyear'=>$stuAcademicYear,
                    'createdby'=>$user,
                    'created_at'=>date('M-d-Y')
                );
            }
            if(!empty($data)){
                $query=$this->db->insert_batch('vaccination_student_list',$data);
            }
        }
        $queryvaccinationInfo=$this->db->query("SELECT * FROM vaccination_info where stuid='$stuid' ");
        if($queryvaccinationInfo->num_rows()>0){
            $this->db->where('stuid',$stuid);
            $this->db->where('academicyear',$stuAcademicYear);
            $queryUpdate=$this->db->update('vaccination_info',$data1);
            echo 'Vaccination data updated successfully.';
        }else{
            $queryInsert=$this->db->insert('vaccination_info',$data1);
            echo 'Vaccination data submitted successfully.';
        }
    }
    function updateStudents_Illness(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $stuAcademicYear=$this->input->post('stuAcademicYear_illness');
        $stuid=$this->input->post('stuStuid_illness');
        $username=$this->input->post('stUsername_illness');
        $Illness_Cause=$this->input->post('Illness_Cause');
        $date_of_checkup=$this->input->post('date_of_checkup');
        $action_Taken=$this->input->post('action_Taken');
        if(!empty($Illness_Cause)){
            $data1=array(
                'stuid'=>$stuid,
                'illness_cause'=>$Illness_Cause,
                'action_taken'=>$action_Taken,
                'date_checkup'=>$date_of_checkup,
                'checkedby'=>$user,
                'academicyear'=>$stuAcademicYear
            );
            $queryvaccinationInfo=$this->db->query("SELECT * FROM student_illness_report where stuid='$stuid' and illness_cause='$Illness_Cause' ");
            if($queryvaccinationInfo->num_rows()>0){
                $this->db->where('stuid',$stuid);
                $this->db->where('academicyear',$stuAcademicYear);
                $queryUpdate=$this->db->update('student_illness_report',$data1);
                echo 'Illness data updated successfully.';
            }else{
                $queryInsert=$this->db->insert('student_illness_report',$data1);
                echo 'Illness data submitted successfully.';
            }
        }else{
            echo 'Ooops,Please add illness cause.';
        }
    }
    /*----*/
    function previous_incident_report(){
        if($this->input->post('username')){
            $username=$this->input->post('username');    
            echo $this->main_model->previous_illness_report($username);
        }
    }
    function previous_vaccination_report(){
        if($this->input->post('username')){
            $username=$this->input->post('username');    
            echo $this->main_model->previous_vaccination_report($username);
        }
    }
}
