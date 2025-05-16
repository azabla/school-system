<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aspattendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStuAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentASP' order by id ASC ");  
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
	public function index($page='aspattendance')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
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
        $data['grade']=$this->main_model->fetch_grade($max_year);
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
            echo $this->main_model->fetch_grade_from_branch_forStudentASP($branch,$max_year); 
        }
    }
    function Filter_asp_program_from_grade(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetch_asp_program($branch,$max_year); 
        }
    }
    function addremove_studentASP_Attendance(){
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
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $mybranch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->addremove_studentASP_Attendance($grade,$branch,$max_year); 
            }else{
                echo $this->main_model->addremove_studentASP_Attendance($grade,$mybranch,$max_year); 
            }
        }
    }
    function update_studentASPAttendance(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $attendanceProgram=$this->input->post('attendanceProgram');
            $data=array(
                'user_id'=>$stuid,
                'student_program'=>$attendanceProgram,
                'academicyear'=>$max_year
            );
            $queryCheck=$this->db->query("select * from users_remote_program where academicyear='$max_year' and user_id='$stuid' and student_program='$attendanceProgram' ");
            if($queryCheck->num_rows()>0){
                $this->db->where('academicyear',$max_year);
                $this->db->where('student_program',$attendanceProgram);
                $this->db->where('user_id',$stuid);
                $query=$this->db->delete('users_remote_program');
                if($query){
                    echo 'Student status updated successfully.';
                }else{
                    echo 'Ooops Please try again.';
                }
            }else{
                $query=$this->db->insert('users_remote_program',$data);
                if($query){
                    echo 'Student data inserted successfully.';
                }else{
                    echo 'Ooops Please try again.';
                }
            }            
        }
    }
    function fetchStudents4_asp_Attendance(){
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
            $attendanceDate=$this->input->post('attendanceDate');
            $aspProgram=$this->input->post('attendanceProgram');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchStudents4_asp_Attendance($gradeSections,$attBranches,$max_year,$attendanceDate,$aspProgram); 
            }else{
                echo $this->main_model->fetchStudents4_asp_Attendance($gradeSections,$branch,$max_year,$attendanceDate,$aspProgram); 
            }
        }
    }
    function saveAttendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        $data1=array();
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $attendanceDate=$this->input->post('attendanceDate');
            $attendanceMinute=$this->input->post('attendanceMinute');
            $attendanceType=$this->input->post('attendanceType');
            $attendanceProgram=$this->input->post('attendanceProgram');
            $timestamp=strtotime($attendanceDate);
            $newDateEnd=date('d/m/y',$timestamp);
            $query=$this->main_model->insert_asp_absent($stuid,$attendanceDate,$max_year,$user,$attendanceProgram);
            if($query){
                if($attendanceType=='Late'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>$attendanceType,
                        'attendance_program'=>$attendanceProgram,
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else{
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>$attendanceType,
                        'attendance_program'=>$attendanceProgram,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                if(!empty($data)){
                    $this->db->insert_batch('aspattendance',$data);
                } 
            }else{
                if($attendanceType=='Late'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>$attendanceType,
                        'attendance_program'=>$attendanceProgram,
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else{
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>$attendanceType,
                        'attendance_program'=>$attendanceProgram,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                if(!empty($data1)){
                    $this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $this->db->where('attendance_program',$attendanceProgram);
                    $query=$this->db->update_batch('aspattendance',$data1,'stuid');
                }
            }
        }
    }
    function fetchasp_AttendanceReport(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_asp_attendance($max_year);
        }else{
            echo $this->main_model->fetch_asp_mattendance($max_year,$branch);
        }
    } 
    function deleteAttendance(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $this->main_model->delete_asp_attendance($attendanceId,$max_year);
        }
    }
    function searchAttendance(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->search_asp_Attendance($searchItem,$max_year);
        }
    }
    function postAttendanceType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        $data=array();
        if(isset($_POST['attendanceType'])){
            $attendanceType = $this->input->post('attendanceType');
            $attendanceDesc=$this->input->post('attendanceDesc');
            $grade = $this->input->post('grade');
            foreach ($grade as $grades) {
                $query=$this->main_model->add_attendance_type($attendanceType,$grades,$max_year);
                if($query){
                    $data[]=array(
                        'attendance_name'=>$attendanceType,
                        'attendance_grade'=>$grades,
                        'attendance_desc'=>$attendanceDesc,
                        'academicyear'=>$max_year,
                        'created_by'=>$user,
                        'date_created'=>date('M-d-Y')
                    );
                }
            }
            $this->db->insert_batch('attendance_type',$data);
        }
    }
    function fetch_attendance_type(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_attendance_type($max_year);
    }
    function Delete_attendance_Type(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceType')){
          $attendanceType=$this->input->post('attendanceType');
          $this->main_model->Delete_attendance_Type($attendanceType,$max_year);
        }
    }
    function postProgramType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        $data=array();
        if(isset($_POST['attendanceType'])){
            $attendanceType = $this->input->post('attendanceType');
            $attendanceDesc=$this->input->post('attendanceDesc');
            $grade = $this->input->post('grade');
            foreach ($grade as $grades) {
                $query=$this->main_model->add_attendance_program($attendanceType,$grades,$max_year);
                if($query){
                    $data[]=array(
                        'program_name'=>$attendanceType,
                        'program_grade'=>$grades,
                        'program_desc'=>$attendanceDesc,
                        'academicyear'=>$max_year,
                        'created_by'=>$user,
                        'date_created'=>date('M-d-Y')
                    );
                }
            }
            $this->db->insert_batch('attendance_program',$data);
        }
    }
    function fetch_attendance_program(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_attendance_program($max_year);
    }
    function fetch_attendance_program_toedit(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('programName')){
            $programName=$this->input->post('programName');
            echo $this->main_model->fetch_attendance_program_toedit($programName,$max_year);
        } 
    }
    function updateThisProgram(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('programOld')){
            $programOld=$this->input->post('programOld');
            $programNew=$this->input->post('programNew');
            $grade=$this->input->post('grade');
            $data=array(
                'program_name'=>$programNew
            );
            foreach ($grade as $grades) {
                $this->db->where('program_name',$programOld);
                $this->db->where('program_grade',$grades);
                $this->db->where('academicyear',$max_year);
                $query=$this->db->update('attendance_program',$data);
                if($query){
                   $queryCheck=$this->db->query("SELECT st.stuid from aspattendance as st cross join users as us where st.stuid=us.username and st.academicyear ='$max_year' and us.academicyear='$max_year' and us.grade='$grades' GROUP BY st.stuid ORDER BY st.stuid ASC");
                    if($queryCheck->num_rows()>0){
                        foreach($queryCheck->result() as $row){
                            $stuid=$row->stuid;
                            $this->db->where('stuid',$stuid);
                            $this->db->where('attendance_program',$programOld);
                            $this->db->where('academicyear',$max_year);
                            $this->db->set('attendance_program',$programNew);
                            $query=$this->db->update('aspattendance');  
                        }
                    }
                }
            }
        }
    }
    function deleteSpecificProgram(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['grade']))
        {
            $grade=$this->input->post('grade');
            $program=$this->input->post('program');
            $this->db->where(array('program_name'=>$program));
            $this->db->where(array('academicyear'=>$max_year));
            $this->db->where(array('program_grade'=>$grade));
            $this->db->delete('attendance_program');
        }
    }
    function Delete_attendance_program(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceType')){
          $attendanceType=$this->input->post('attendanceType');
          $this->main_model->Delete_attendance_program($attendanceType,$max_year);
        }
    }
}