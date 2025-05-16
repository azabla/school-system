<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('tableName','Attendance');
        $this->db->where('allowed','studentAttendance');
        $userPerStuAtt=$this->db->get('usergrouppermission');  
        if($this->session->userdata('username') == '' || $userPerStuAtt->num_rows()<1 || $userLevel!='1'){
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
    public function index($page='attendance')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        }else{
            $data['gradesec']=$this->main_model->fetch_mygradesec($max_year,$branch);
        }
        $this->load->view('home-page/'.$page,$data);
    }
    function fetchStudents4Attendance(){
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('attBranches')){
            $attGradesec=$this->input->post('attGradesec');
            $attBranches=$this->input->post('attBranches');
            $dateAttendance=$this->input->post('attendanceDate');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_gradesec_student($attGradesec,$attBranches,$max_year,$dateAttendance,$user); 
            }else{
                echo $this->main_model->fetch_gradesec_student($attGradesec,$branch,$max_year,$dateAttendance,$user); 
            }
        }
    }
    function Filter_grade_from_branch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetchGradeFromBranchTransport($branch,$max_year); 
        }
    }
    function fetchCustomStudentsAttendance(){
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;

        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('attBranches')){
            $grade=$this->input->post('grade');
            $attBranches=$this->input->post('attBranches');
            $customToDate=$this->input->post('customToDate');
            $customFromDate=$this->input->post('customFromDate');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchCustomStudentsAttendance($check,$attBranches,$customToDate,$customFromDate,$max_year); 
            }else{
                echo $this->main_model->fetchCustomStudentsAttendance($check,$branch,$customToDate,$customFromDate,$max_year); 
            }
        }
    }
    function fetchSectionStudentsAttendance(){
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;

        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('attBranches')){
            $grade=$this->input->post('grade');
            $attBranches=$this->input->post('attBranches');
            $customFromDateSection=$this->input->post('customFromDateSection');
            $customToDateSection=$this->input->post('customToDateSection');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchSectionStudentsAttendance($grade,$attBranches,$max_year,$customToDateSection,$customFromDateSection); 
            }else{
                echo $this->main_model->fetchSectionStudentsAttendance($grade,$branch,$max_year,$customToDateSection,$customFromDateSection); 
            }
        }
    }
    function fetchStudentsAttendanceFormat(){
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attBranches')){
            $attGradesec=$this->input->post('attGradesec');
            $attBranches=$this->input->post('attBranches');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchStudentsAttendanceFormat($attGradesec,$attBranches,$max_year); 
            }else{
                echo $this->main_model->fetchStudentsAttendanceFormat($attGradesec,$branch,$max_year); 
            }
        }
    }
    function saveAttendance(){
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        $data1=array();
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $attendanceDate=$this->input->post('attendanceDate');
            /*$attendanceMinute=$this->input->post('attendanceMinute');*/
            $attendanceType=$this->input->post('attendanceType');
            $timestamp=strtotime($attendanceDate);
            $newDateEnd=date('d/m/y',$timestamp);
            $query=$this->main_model->insert_absent($stuid,$attendanceDate,$max_year,$user);
            if($query){
                $data[]=array(
                    'stuid'=>$stuid,
                    'absentdate'=>$attendanceDate,
                    'absentype'=>$attendanceType,
                    'academicyear'=>$max_year,
                    'attend_by'=>$user
                );
                if(!empty($data)){
                    $query=$this->db->insert_batch('attendance',$data);
                    if($query){
                        $data['notification']='1';
                    }else{
                        $data['notification']='2';
                    }
                    echo json_encode($data);
                } 
            }else{
                $data1=array(
                    'stuid'=>$stuid,
                    'absentdate'=>$attendanceDate,
                    'absentype'=>$attendanceType,
                    'academicyear'=>$max_year,
                    'attend_by'=>$user
                );
                if(!empty($data1)){
                    $this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $query=$this->db->update('attendance',$data1);
                    if($query){
                        $data['notification']='3';
                    }else{
                        $data['notification']='4';
                    }
                    echo json_encode($data);
                }
            } 
        }        
    }
    function deleteAttendance(){
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $this->main_model->delete_Staffattendance($attendanceId,$max_year);
        }
    }
    function attendanceNotification(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');

        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $myBranch=$row_branch->branch;
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['view'])){
            $show=$this->main_model->fetch_allnewAttendance($myBranch,$max_year);
            $result['notification']=$show;
            $tot=$this->main_model->fetch_unseen_newAttendance($myBranch,$max_year);
            $result['unseen_notification']=$tot;
            echo json_encode($result);
        }
    }
    function sign_agreement(){
        $user=$this->session->userdata('username');
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('agreed_date')){
            $agreed_date=$this->input->post('agreed_date');
            $user_id=$this->input->post('user_id');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1' ){
                $data=array(
                    'signed_for_date'=>$agreed_date,
                    'signed_user'=>$user_id,
                    'signed_grade'=>$gradesec,
                    'signed_branch'=>$branch,
                    'agreed_status'=>'1',
                    'academicyear'=>$max_year,
                    'date_signed'=>date('M-d-Y')
                );
            }else{
                $data=array(
                    'signed_for_date'=>$agreed_date,
                    'signed_user'=>$user_id,
                    'signed_grade'=>$gradesec,
                    'signed_branch'=>$mybranch,
                    'agreed_status'=>'1',
                    'academicyear'=>$max_year,
                    'date_signed'=>date('M-d-Y')
                );
            }
          echo $this->db->insert('attendance_sign',$data);
        }
    }
    function fetch_attendance_report_smart(){
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $postData = $this->input->post();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data= $this->main_model->fetch_attendance_report_smart($max_year,$postData);
        }else{
            $data= $this->main_model->fetch_attendance_report_smart_nonadmin($max_year,$branch,$postData);
        }
        echo json_encode($data);
    }
    function load_attendance_typedata(){
        echo $this->main_model->load_attendance_typedata();
    }
    function save_new_attendance_Type(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendance_Type')){
            $attendance_Type=trim($this->input->post('attendance_Type'));
            $data=array(
                'attendance_name'=>$attendance_Type,
                'attendance_desc'=>'',
                'attendance_grade'=>'',
                'academicyear'=>$max_year,
                'created_by'=>$user,
                'date_created'=>date('M-d-Y')
            );
            $query=$this->main_model->register_new_attendanceType($data,$attendance_Type);
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function removeBook_this_attendance_type(){
        $user=$this->session->userdata('username');
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('id',$userid);
            $query=$this->db->delete('attendance_type');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
}