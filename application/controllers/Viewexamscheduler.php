<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Viewexamscheduler extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='taskspage' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='viewexamschedule')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
           show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $query_quarter = $this->db->query("select max(quarter) as quarter from mark");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        if(isset($_POST['startscheduler'])){
           $data['scheduler']=$this->main_model->examschedule($max_year,$max_quarter);
        }
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['subject']=$this->main_model->fetchSubjectExam($max_year);
        $this->load->view('home-page/'.$page,$data);    
	}
    function viewexamscheduler(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('examSchedule')){
            $examSchedule=$this->input->post('examSchedule');
            if($examSchedule==trim('viewTeacher')){
                echo $this->main_model->viewTeacherExamSchedule($max_year);
            }else if($examSchedule==trim('viewSubject')){
                echo 'Subject';
            }else{

            }
        }
    }  
}