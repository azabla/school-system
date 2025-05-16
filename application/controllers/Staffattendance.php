<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffattendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='staffAttendance' order by id ASC "); 
        if($this->session->userdata('username') == '' || $userPerStaAtt->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='staffattendance')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $this->load->view('home-page/'.$page,$data);
	} 
    function fetchStaffsToAttendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $mydivision=$row_branch->status2;
        $today=date('d/m/Y');
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('staffBranche')){
            $staffBranche=$this->input->post('staffBranche');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchStaffsToAttendanceSuper($staffBranche,$today);
            }else{
                echo $this->main_model->fetchStaffsToAttendance($branch,$today,$mydivision);
            }
        } 
    }
    function absentAttendance(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffId')){
            $staffId=$this->input->post('staffId');
            $dateAbsent=$this->input->post('dateAbsent');
            $timestamp=strtotime($dateAbsent);
            $newDateEnd=date('d/m/Y',$timestamp);
            $query= $this->main_model->insert_absent($staffId,$newDateEnd,$max_year,$user);
            if($query){
                $data=array(
                    'stuid'=>$staffId,
                    'absentdate'=>$newDateEnd,
                    'absentype'=>'Absent',
                    'academicyear'=>$max_year,
                    'attend_by'=>$user
                );
                $this->db->insert('attendance',$data);
            }
        }
    }
    function lateAttendance(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffId')){
            $staffId=$this->input->post('staffId');
            $dateAbsent=$this->input->post('dateAbsent');
            $lateMin=$this->input->post('lateMin');
            $timestamp=strtotime($dateAbsent);
            $newDateEnd=date('d/m/Y',$timestamp);
            $query=$this->main_model->insert_absent($staffId,$newDateEnd,$max_year,$user);
            if($query){
                $data=array(
                    'stuid'=>$staffId,
                    'absentdate'=>$newDateEnd,
                    'absentype'=>'Late',
                    'latemin'=>$lateMin,
                    'academicyear'=>$max_year,
                    'attend_by'=>$user
                );
                $this->db->insert('attendance',$data);
            }
        }
    }
    function permissionAttendance(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffId')){
            $staffId=$this->input->post('staffId');
            $dateAbsent=$this->input->post('dateAbsent');
            $timestamp=strtotime($dateAbsent);
            $newDateEnd=date('d/m/Y',$timestamp);
            $query= $this->main_model->insert_absent($staffId,$newDateEnd,$max_year,$user);
            if($query){
                $data=array(
                    'stuid'=>$staffId,
                    'absentdate'=>$newDateEnd,
                    'absentype'=>'Permission',
                    'academicyear'=>$max_year,
                    'attend_by'=>$user
                );
            }
            $this->db->insert('attendance',$data);
        }
    }
    function fetchStaffsAttendance(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
         $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $mydivision=$row_branch->status2;
        $today=date('d/m/Y');
        $accessbranch = sessionUseraccessbranch();
        $postData = $this->input->post();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data= $this->main_model->fetch_staffattendance($max_year,$postData);
            echo json_encode($data);
        }else{
            echo $this->main_model->fetch_mystaffattendance($max_year,$branch,$mydivision);
        }
    }
    function deleteAttendance(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffId')){
            $staffId=$this->input->post('staffId');
            echo $this->main_model->delete_Staffattendance($staffId,$max_year);
        }
    }
    function fetchCustomStaffsAttendance(){
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
            $attBranches=$this->input->post('attBranches');
            $customToDate=$this->input->post('customToDate');
            $customFromDate=$this->input->post('customFromDate');
            $includeDate=$this->input->post('includeDate');
            if($includeDate=='0'){
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetchCustomStaffsAttendanceCount($attBranches,$customToDate,$customFromDate,$max_year); 
                }else{
                    echo $this->main_model->fetchCustomStaffsAttendanceCount($branch,$customToDate,$customFromDate,$max_year); 
                }
            }else{
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetchCustomStaffsAttendance($attBranches,$customToDate,$customFromDate,$max_year); 
                }else{
                    echo $this->main_model->fetchCustomStaffsAttendance($branch,$customToDate,$customFromDate,$max_year); 
                }
            }
        }
    }
}