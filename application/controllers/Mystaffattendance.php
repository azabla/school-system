<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mystaffattendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='staffAttendance' order by id ASC ");
        if($this->session->userdata('username') == '' || $userPerStaAtt->num_rows()<1 || $userLevel!='2'){
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
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
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
        $this->load->view('teacher/'.$page,$data);
	} 
    function fetchStaffsToAttendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $mydivision=$row_branch->status2;
        $today=date('d/m/Y');
        echo $this->main_model->fetchStaffsToAttendance($branch,$today,$mydivision);
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
        echo $this->main_model->fetch_mystaffattendance($max_year,$branch,$mydivision);
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
}