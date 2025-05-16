<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sentworksheet extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($this->session->userdata('username') == '' || $userLevel!='3'){
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
	public function index($page='sentworksheet')
	{
         if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_gradesec = $this->db->query("select * from users where username='$user'");
        $row_gradesec = $query_gradesec->row();
        $id=$row_gradesec->id;
        $grade=$row_gradesec->grade;

        if(isset($_GET['lessonid'])){
          $awid=$_GET['lessonid'];
          $this->load->model('main_model');
          $this->main_model->delete_sent_worksheet($awid);
        }

        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['sentworksheet']=$this->main_model->fetch_answer_worksheet($max_year,$id);
		    $this->load->view('student/'.$page,$data);
	} 

}