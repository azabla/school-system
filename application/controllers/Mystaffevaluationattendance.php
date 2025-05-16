<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mystaffevaluationattendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStuAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='staffEvaluationAttendance' order by id ASC ");  
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
	public function index($page='mystaffevaluationattendance')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
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

        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $this->load->view('teacher/'.$page,$data);
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
        if($this->input->post('attendanceDate')){
            $dateAttendance=$this->input->post('attendanceDate');
            $attendace_type=$this->input->post('attendace_type');
            /*if($accessbranch === '1'){
                echo 'Oooop Please contact your system Admin.';
                echo $this->main_model->fetch_evaluation_staff_nonAdminAllBranch($max_year,$dateAttendance,$attendace_type); 
            }else{
                echo $this->main_model->fetch_evaluation_staff_nonAdmin($branch,$max_year,$dateAttendance,$attendace_type); 
            }*/
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo 'Oooop Please contact your system Admin.';
            }else{
                echo $this->main_model->fetch_evaluation_staff($branch,$max_year,$dateAttendance,$attendace_type); 
            }
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
        if($this->input->post('customToDate')){
            $customToDate=$this->input->post('customToDate');
            $customFromDate=$this->input->post('customFromDate');
            $customattendace_type=$this->input->post('customattendace_type');
            if($accessbranch === '1'){
                echo $this->main_model->fetchCustomStaffEvaluationAttendanceNonBranch($customToDate,$customFromDate,$max_year,$customattendace_type); 
            }else{
                echo $this->main_model->fetchCustomStaffEvaluationAttendance($branch,$customToDate,$customFromDate,$max_year,$customattendace_type); 
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
            $attendace_type=$this->input->post('attendace_type');
            $substituteBy=$this->input->post('substituteBy');
            $timestamp=strtotime($attendanceDate);
            $newDateEnd=date('d/m/y',$timestamp);
            $query=$this->main_model->insert_Staff_evaluation_Attendance($stuid,$attendanceDate,$max_year,$user,$attendace_type);
            if($query){
                if($attendanceType=='Unexcused Absence'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Unexcused Absence',
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                else if($attendanceType=='Tardy'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Tardy',
                        'attendance_period'=>$attendace_type,
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Excused Absence'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Excused Absence',
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Substitution'){
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'substitute_by'=>$substituteBy,
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else{
                    $data[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Present',
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                    /*$this->db->where('attendance_period',$attendace_type);
                    $this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $queryD=$this->db->delete('attendance_evaluation');
                    if($queryD){
                        if($queryD){
                            $data['notification']='<span class="text-success"><small><i class="fas fa-check-circle"></i></small></span>';
                        }
                    }else{
                        $data['notification']='<span class="text-danger"><i class="fas fa-times-circle"></i></span>';
                    }
                    echo json_encode($data);*/
                }
                if(!empty($data)){
                    $query=$this->db->insert_batch('attendance_evaluation',$data);
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
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }
                else if($attendanceType=='Tardy'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Tardy',
                        'attendance_period'=>$attendace_type,
                        'latemin'=>$attendanceMinute,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Excused Absence'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Excused Absence',
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else if($attendanceType=='Substitution'){
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'substitute_by'=>$substituteBy,
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                }else{
                    $data1[]=array(
                        'stuid'=>$stuid,
                        'absentdate'=>$attendanceDate,
                        'absentype'=>'Present',
                        'attendance_period'=>$attendace_type,
                        'academicyear'=>$max_year,
                        'attend_by'=>$user
                    );
                    /*$this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $this->db->where('attendance_period',$attendace_type);
                    $queryD=$this->db->delete('attendance_evaluation');
                    if($queryD){
                        if($queryD){
                            $data['notification']='<span class="text-success"><small><i class="fas fa-check-circle"></i></small></span>';
                        }
                    }else{
                        $data['notification']='<span class="text-danger"><i class="fas fa-times-circle"></i></span>';
                    }
                    echo json_encode($data);*/
                }
                if(!empty($data1)){
                    $this->db->where('academicyear',$max_year);
                    $this->db->where('stuid',$stuid);
                    $this->db->where('absentdate',$attendanceDate);
                    $this->db->where('attendance_period',$attendace_type);
                    $query=$this->db->update_batch('attendance_evaluation',$data1,'stuid');
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
        $postData = $this->input->post();
        if($accessbranch === '1'){
            $data = $this->main_model->fetch_attendance_staffEvaluation($max_year,$postData);
            echo json_encode($data);
        }else{
            $data = $this->main_model->fetch_mattendance_staffEvaluation($max_year,$branch,$postData);
            echo json_encode($data);
        }
    } 
    function deleteAttendance(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $this->main_model->delete_StaffEvaluation_attendance($attendanceId,$max_year);
        }
    }
    function searchAttendance(){
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
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            if($accessbranch === '1'){
                echo $this->main_model->searchStaff_Evaluation_Attendance($searchItem,$max_year);
            }else{
                echo $this->main_model->searchStaff_Evaluation_AttendanceNonAdmin($branch,$searchItem,$max_year);
            }
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
            $show=$this->main_model->fetch_allnewEvaluationStaffAttendance($myBranch,$max_year);
            $result['notification']=$show;
            $tot=$this->main_model->fetch_unseen_newStaffEvaluationAttendance($myBranch,$max_year);
            $result['unseen_notification']=$tot;
            echo json_encode($result);
        }
    }
    function add_comment_tostaff_supervision(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('username')){
            $username=$this->input->post('username');
            $year=$this->input->post('year');
            $dateattendance=$this->input->post('dateattendance');
            $attendanceType=$this->input->post('attendanceType');
            echo $this->main_model->add_comment_tostaff_supervision($username,$year,$dateattendance,$attendanceType);
        }
    }
    function save_supervision_attendance_comment(){
        $user=$this->session->userdata('username');
        $data1=array();
        if($this->input->post('supervision_comment_year')){
            $supervision_comment_year=$this->input->post('supervision_comment_year');
            $supervision_comment_staff=$this->input->post('supervision_comment_staff');
            $supervision_comment_date=$this->input->post('supervision_comment_date');
            $supervision_comment_type=$this->input->post('supervision_comment_type');
            $teacher_supervision_comment_gs=$this->input->post('teacher_supervision_comment_gs');
            $queryCheck=$this->db->query("select * from attendance_evaluation where stuid='$supervision_comment_staff' and academicyear='$supervision_comment_year' and absentdate='$supervision_comment_date' and attendance_period='$supervision_comment_type' ");
            if($queryCheck->num_rows()>0){
                $this->db->where('stuid',$supervision_comment_staff);
                $this->db->where('absentdate',$supervision_comment_date);
                $this->db->where('attendance_period',$supervision_comment_type);
                $this->db->where('academicyear',$supervision_comment_year);
                $this->db->set('staff_comment',$teacher_supervision_comment_gs);
                $query=$this->db->update('attendance_evaluation');
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                $data1=array(
                    'stuid'=>$supervision_comment_staff,
                    'absentdate'=>$supervision_comment_date,
                    'absentype'=>'Present',
                    'substitute_by'=>'',
                    'attendance_period'=>$supervision_comment_type,
                    'academicyear'=>$supervision_comment_year,
                    'staff_comment'=>$teacher_supervision_comment_gs,
                    'attend_by'=>$user
                );
                $query=$this->db->insert('attendance_evaluation',$data1);
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }
        }
    }
}