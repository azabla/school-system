<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Viewstudentlesson extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='homeworkworksheet' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='viewlesson')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_gradesec = $this->db->query("select gradesec from users where username='$user'");
        $row_gradesec = $query_gradesec->row();
        $gradesec=$row_gradesec->gradesec;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['lesson']=$this->main_model->mystudent_lesson($max_year,$user);
		$this->load->view('teacher/'.$page,$data);
	} 
    function deletelesson(){
        if($this->input->post('lessonid')){
           $lessonid=$this->input->post('lessonid');
           echo $this->main_model->delete_lesson($lessonid);
        }
    }

}