<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentaspattendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuPl=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentASP' order by id ASC "); 
        if($this->session->userdata('username') == '' || $uperStuPl->num_rows() < 1 || $userLevel!='2'){
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
    public function index($page='studentaspattendance')
    {
        
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
      $user=$this->session->userdata('username');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      $today=date('y-m-d');
        
      $data['fetch_term']=$this->main_model->fetch_term($max_year);
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year();
      $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
      $data['branch']=$this->main_model->fetch_branch($max_year);
      $data['schools']=$this->main_model->fetch_school();
        if($_SESSION['usertype']===trim('Director')){
          $data['gradesec']=$this->main_model->fetchGradeForSummaryDirector($user,$max_year);
        }else{
          $data['gradesecTeacher']=$this->main_model->fetchGradeForSummaryTeacher($user,$max_year);
        }
      $this->load->view('teacher/'.$page,$data);
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
        if($this->input->post('gradeSection')){
            $gradeSections=$this->input->post('gradeSection');
            $attendanceDate=$this->input->post('attendanceDate');
            $attendanceProgram=$this->input->post('attendanceProgram');
            echo $this->main_model->fetchStudents4_asp_Attendance($gradeSections,$branch,$max_year,$attendanceDate,$attendanceProgram); 
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
        echo $this->main_model->fetch_asp_mattendance($max_year,$branch); 
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
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->search_asp_Attendance_director($searchItem,$branch,$max_year);
        }
    } 
}