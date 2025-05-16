<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentincident extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentIncident' order by id ASC ");  
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
    public function index($page='studentincident')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['gradeGroups']=$this->main_model->studentRegistrationGrade($max_year);
        $this->load->view('home-page/'.$page,$data);
    }
    function save_new_incident(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('incidentName')){
            $incidentName=trim($this->input->post('incidentName'));
            $incidentCategory=trim($this->input->post('incidentTypeCategory'));
            $data=array(
                'incident_name'=>$incidentName,
                'incident_category'=>$incidentCategory,
                'createdby'=>$user,
                'date_created'=>date('M-d-Y')
            );
            $query=$this->main_model->register_new_incident($data,$incidentName,$incidentCategory);
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->filterGradesecfromBranch($academicyear); 
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
    function fecth_this_tudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        if($this->input->post('gs_branches')){
            $gs_branches=$this->input->post('gs_branches');
            $onlyGrade=$this->input->post('onlyGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_students_incident_info($gs_branches,$onlyGrade,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_students_incident_info($branch,$onlyGrade,$grands_academicyear);
            }
        } 
    }
    function reportIncident_student(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select branch,fname,mname from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $tname=$row_branch->fname;
        $mname=$row_branch->mname;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            $newAcademicYear=$this->input->post('newAcademicYear');
            echo $this->main_model->reportIncident_student($editedId,$max_year,$tname,$mname,$user);
        }
    }
    function previous_incident_report(){
        if($this->input->post('username')){
            $username=$this->input->post('username');    
            echo $this->main_model->previous_incident_report($username);
        }
    }
    function save_incident(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $previous_conse='';
        $data1=array();
        $data=array();
        $maxID='1';
        if($this->input->post('incident_teacher')){
            $teacher=$this->input->post('incident_teacher');  
            $student=$this->input->post('incident_student');
            $date_import=$this->input->post('incident_date');
            $incident_location=$this->input->post('incident_location');
            $incident_category=$this->input->post('incidentTypeCategoryChoose');
            $incident_type=$this->input->post('incident_type');  
            $incident_description=trim($this->input->post('incident_description'));
            $is_offense=$this->input->post('is_offense');
            $previous_conse=$this->input->post('previous_conse');  
            $admin_action=$this->input->post('admin_action');
            $date_suspension_outschool=$this->input->post('date_suspension_outschool');
            $reentry_date_outschool=$this->input->post('reentry_date_outschool');
            $date_suspensionIn=$this->input->post('date_suspension_inschool');
            $reentry_dateIn=$this->input->post('reentry_date_inschool');
            $reportDate = date('d/m/Y', strtotime($date_import));
            if(!empty($date_suspensionIn)){
                $new_out_suspensionDate = date('d/m/Y', strtotime($date_suspension_outschool));
                $new_outreentry_date = date('d/m/Y', strtotime($reentry_date_outschool));
                $new_in_suspensionDate = date('d/m/Y', strtotime($date_suspensionIn));
                $new_reentry_indate = date('d/m/Y', strtotime($reentry_dateIn));
            }else{
                $new_out_suspensionDate='';
                $new_outreentry_date='';
                $new_in_suspensionDate='';
                $new_reentry_indate='';
            }
            $data =array(
                'stuid'=>$student,
                'incident_type'=>$incident_category,
                'incident_location'=>$incident_location,
                'incidet_desc'=>$incident_description,
                'is_offense'=>$is_offense,
                'previous_conse'=>$previous_conse,
                'admin_action'=>$admin_action,
                'date_in_suspension'=>$new_in_suspensionDate,
                'in_reentry_date'=>$new_reentry_indate,
                'date_out_suspension'=>$new_out_suspensionDate,
                'out_reentry_date'=>$new_outreentry_date,
                'report_by'=>$teacher,
                'date_report'=>$reportDate,
                'academicyear'=>$max_year
            );
            $query= $this->db->insert('incident_report',$data);
            if($query){
                $queryMax=$this->db->query("select max(id) as max_ID from incident_report ");
                $queryRow=$queryMax->row();
                $maxID=$queryRow->max_ID;
                for($i=0;$i<count($incident_type);$i++) {
                    $incident_types=$incident_type[$i];            
                    $data1[]=array(
                        'stuid'=>$student,
                        'incident_type'=>$incident_types,
                        'incident_id'=>$maxID,
                        'academicyear'=>$max_year,
                        'inserted_by'=>$teacher,
                        'date_inserted'=>$reportDate
                    );
                }
                if(!empty($data1)){
                    $query=$this->db->insert_batch('incident_student_type',$data1);
                    if($query){
                        echo '1';
                    }else{
                        echo '2';
                    }
                }
            }else{
                echo '2';
            }
        }
    }
    function fetch_incident_form(){
        echo $this->main_model->fetch_incident_form();
    }
    function save_new_incident_form(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('incidentCategory')){
            $incidentCategory=trim($this->input->post('incidentCategory'));
            $data=array(
                'category_name'=>$incidentCategory,
                'createdby'=>$user,
                'date_created'=>date('M-d-Y')
            );
            $query=$this->main_model->register_new_incident_category($data,$incidentCategory);
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function removeBook_this_incident_category(){
        $user=$this->session->userdata('username');
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('id',$userid);
            $query=$this->db->delete('incident_category');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function fetch_incident_type(){
        echo $this->main_model->fetch_incident_type();
    }
    function removeBook_this_incident_type(){
        $user=$this->session->userdata('username');
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('id',$userid);
            $query=$this->db->delete('incident_type');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function fetch_this_incidentform_type(){
        if($this->input->post('incidentCategory')){
            $incidentCategory=$this->input->post('incidentCategory');
            echo $this->main_model->fetch_this_incidentform_type($incidentCategory);
        }
    }
    function fetch_this_incidentform_type_level(){
        if($this->input->post('incidentCategory')){
            $incidentCategory=$this->input->post('incidentCategory');
            echo $this->main_model->fetch_this_incidentform_type_level($incidentCategory);
        }
    }
    function fetch_student_incident_report(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $postData = $this->input->post();
        $data= $this->main_model->fetch_student_incident_report($max_year,$postData);
        echo json_encode($data);
    }
    function viewSingleidentReport(){
        if($this->input->post('reportID')){
            $reportID=$this->input->post('reportID');    
            $reportUsername=$this->input->post('reportUsername');
            echo $this->main_model->viewSingleidentReport($reportID,$reportUsername);
        }
    }
    function fetch_consequence_type(){
        echo $this->main_model->fetch_consequence_type();
    }
    function save_new_consequence(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('consequenceName')){
            $consequenceName=trim($this->input->post('consequenceName'));
            $data=array(
                'consequence_name'=>$consequenceName,
                'createdby'=>$user,
                'date_created'=>date('M-d-Y')
            );
            $query=$this->main_model->register_new_incident_consequence_name($data,$consequenceName);
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function removeBook_this_consequence_name(){
        $user=$this->session->userdata('username');
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('id',$userid);
            $query=$this->db->delete('incident_consequence');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function save_final_decision(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('add_final_decision')){
            $adddecision=trim($this->input->post('add_final_decision'));
            $studentID=trim($this->input->post('student_id_decision'));
            $incidentID=trim($this->input->post('inciden_id_decision'));
            $data=array(
                'stuid'=>$studentID,
                'incident_decision'=>$adddecision,
                'incident_id'=>$incidentID,
                'academicyear'=>$max_year,
                'inserted_by'=>$user,
                'date_inserted'=>date('M-d-Y')
            );
            $queryCheck=$this->db->query("select * from incident_decision where stuid='$studentID' and incident_id='$incidentID' ");
            if($queryCheck->num_rows()>0){
                $this->db->where('stuid',$studentID);
                $this->db->where('incident_id',$incidentID);
                $queryUpdate=$this->db->update('incident_decision',$data);
                if($queryUpdate){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                $queryInsert=$this->db->insert('incident_decision',$data);
                if($queryInsert){
                    echo '2';
                }else{
                    echo '3';
                }
            }
        }
    }
    function deleteSingleidentReport(){
        if($this->input->post('reportID')){
            $reportID=$this->input->post('reportID');    
            $reportUsername=$this->input->post('reportUsername');
            $this->db->where('id',$reportID);
            $query= $this->db->delete('incident_report');
            if($query){
                $this->db->where('incident_id',$reportID);
                $query1= $this->db->delete('incident_student_type');
                
                $this->db->where('incident_id',$reportID);
                $query2= $this->db->delete('incident_decision');
            }
        }
    }
}
