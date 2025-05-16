<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStuAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentAttendance' order by id ASC ");  
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
        $this->load->view('home-page/'.$page,$data);
    }
    function fetchStudents4Attendance(){
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
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetchGradeFromBranchTransport($branch,$max_year); 
        }
    }
    function fetchCustomStudentsAttendance(){
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
    function fetchStudentsAttendanceFormat(){
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
                if($attendanceType=='Absent'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Absent',
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                else if($attendanceType=='Late'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Late',
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Permission'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Permission',
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
                if($attendanceType=='Absent'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Absent',
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                else if($attendanceType=='Late'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Late',
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Permission'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Permission',
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
    function fetchAttendanceReport(){
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
            echo $this->main_model->fetch_attendance($max_year);
        }else{
            echo $this->main_model->fetch_mattendance($max_year,$branch);
        }
    } 
    function deleteAttendance(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $this->main_model->delete_Staffattendance($attendanceId,$max_year);
        }
    }
    function searchAttendance(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->searchAttendance($searchItem,$max_year);
        }
    }
    function attendanceNotification(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $myBranch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
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
}