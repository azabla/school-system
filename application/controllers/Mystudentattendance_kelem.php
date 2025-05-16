<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mystudentattendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStuAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentAttendance' order by id ASC ");  
        if($this->session->userdata('username') == '' || $userPerStuAtt->num_rows()<1 || $userLevel!='2'){
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
    public function index($page='mystudentattendance')
    {
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_today_attendance']=$this->main_model->fetch_mattendance($max_year,$branch);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['gradesecs']=$this->main_model->fetcHrGradesec($max_year,$user,$branch);
        $data['gradesec']=$this->main_model->fetch_mygradesec2($user,$max_year,$branch);
        $this->load->view('teacher/'.$page,$data);
    } 
    function filterGradesecForTeachers(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $dateAttendance=$this->input->post('fetchDate');
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->filterGradesecForTeachers_kelem($gradesec,$max_year,$branch,$dateAttendance,$user);    
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
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $attendanceDate=$this->input->post('attendanceDate');
            $attendanceMinute=$this->input->post('attendanceMinute');
            $attendanceType=$this->input->post('attendanceType');
            $timestamp=strtotime($attendanceDate);
            $newDateEnd=date('d/m/y',$timestamp);
            $query=$this->main_model->insert_absent($stuid,$attendanceDate,$max_year,$user);
            if($query){
                if($attendanceType=='Unexcused Absence'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Unexcused Absence',
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                else if($attendanceType=='Tardy'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Tardy',
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Excused Absence'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Excused Absence',
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else{
                    $this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $queryD=$this->db->delete('attendance');
                    if($queryD){
                        if($queryD){
                            $data['notification']='<span class="text-success"><small><i class="fas fa-check-circle"></i></small></span>';
                        }
                    }else{
                        $data['notification']='<span class="text-danger"><i class="fas fa-times-circle"></i></span>';
                    }
                    echo json_encode($data);
                }
                if(!empty($data)){
                    $query=$this->db->insert_batch('attendance',$data);
                    if($query){
                        if($query){
                            $data['notification']='<span class="text-success"><small><i class="fas fa-check-circle"></i></small></span>';
                        }
                    }else{
                        $data['notification']='<span class="text-danger"><i class="fas fa-times-circle"></i></span>';
                    }
                    echo json_encode($data);
                } 
            }else{
                if($attendanceType=='Unexcused Absence'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Unexcused Absence',
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                else if($attendanceType=='Tardy'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Tardy',
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Excused Absence'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Excused Absence',
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else{
                    $this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $queryD=$this->db->delete('attendance');
                    if($queryD){
                        if($queryD){
                            $data['notification']='<span class="text-success"><small><i class="fas fa-check-circle"></i></small></span>';
                        }
                    }else{
                        $data['notification']='<span class="text-danger"><i class="fas fa-times-circle"></i></span>';
                    }
                    echo json_encode($data);
                }
                if(!empty($data1)){
                    $this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $query=$this->db->update_batch('attendance',$data1,'stuid');
                    if($query){
                        if($query){
                            $data['notification']='<span class="text-success"><small><i class="fas fa-check-circle"></i></small></span>';
                        }
                    }else{
                        $data['notification']='<span class="text-danger"><i class="fas fa-times-circle"></i></span>';
                    }
                    echo json_encode($data);
                }
            } 
        }        
    }
    function feedAbsentAttendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $absentDate=$this->input->post('absentDate');
            $timestamp=strtotime($absentDate);
            $newDateEnd=date('d/m/y',$timestamp);
            $abseType='Absent';
            $this->main_model->feedAbsentAttendance($attendanceId,$absentDate,$abseType,$max_year,$user);
        }
    }
    function lateAttendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $absentDate=$this->input->post('absentDate');
            $teaStuAbsentMin=$this->input->post('teaStuAbsentMin');
            $abseType='Late';
            $timestamp=strtotime($absentDate);
            $newDateEnd=date('d/m/y',$timestamp);
            $this->main_model->feedLateAttendance($attendanceId,$absentDate,$abseType,$max_year,$user,$teaStuAbsentMin);
        }
    }
    function permissionAttendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $absentDate=$this->input->post('absentDate');
            $teaStuAbsentMin=$this->input->post('teaStuAbsentMin');
            $abseType='Permission';
            $timestamp=strtotime($absentDate);
            $newDateEnd=date('d/m/y',$timestamp);
            $this->main_model->feedPermissionAttendance($attendanceId,$absentDate,$abseType,$max_year,$user);
        }
    }
    function fetchStuAttendance(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        
        if($_SESSION['usertype']===trim('Director')){
            echo $this->main_model->fetchMyStuAttendanceDirector($max_year,$branch,$user);
        }else{
            $queryHrGrade = $this->db->query("select * from hoomroomplacement where teacher='$user' and academicyear='$max_year' ");
            if($queryHrGrade->num_rows()>0){
                $rowHrGradeBranch = $queryHrGrade->row();
                $HrGrade=$rowHrGradeBranch->roomgrade;
                echo $this->main_model->fetchMyStuAttendance($max_year,$branch,$user); 
            }else{
                echo '<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-check-circle"> </i> You are not home room teacher.
                </div></div>'; 
            }
        }
    }
    function deleteAttendance(){
        if($this->input->post('attendanceId')){
            $staffId=$this->input->post('attendanceId');
            $absentDate=$this->input->post('absentDate');
            $timestamp=strtotime($absentDate);
            $newDateEnd=date('d/m/y',$timestamp);
            echo $this->main_model->delete_attendance($staffId,$absentDate);
        }
    }
    function removeAttendance(){
        if($this->input->post('attendanceId')){
            $staffId=$this->input->post('attendanceId');
            $absentDate=$this->input->post('absentDate');
            $timestamp=strtotime($absentDate);
            $newDateEnd=date('d/m/y',$timestamp);
            echo $this->main_model->delete_attendance($staffId,$absentDate);
        }
    }
    function searchAttendance(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $myBranch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->searchAttendanceDirector($searchItem,$myBranch,$max_year);
        }
    }
    function attendanceNotification(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $myBranch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['view'])){
            $show=$this->main_model->fetch_allnewAttendanceDirector($myBranch,$max_year,$user);
            $result['notification']=$show;
            $tot=$this->main_model->fetch_unseen_newAttendanceDirector($myBranch,$max_year,$user);
            $result['unseen_notification']=$tot;
            echo json_encode($result);
        }
    }
    function sign_agreement(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('agreed_date')){
          $agreed_date=$this->input->post('agreed_date');
          $user_id=$this->input->post('user_id');
          $gradesec=$this->input->post('gradesec');
          $data=array(
            'signed_for_date'=>$agreed_date,
            'signed_user'=>$user_id,
            'signed_grade'=>$gradesec,
            'agreed_status'=>'1',
            'academicyear'=>$max_year,
            'date_signed'=>date('M-d-Y')
          );
          echo $this->db->insert('attendance_sign',$data);
        }
    }
}