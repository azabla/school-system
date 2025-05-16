<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myattendance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('gs_model');
        ob_start();
        $this->load->helper('cookie');
        $this->load->helper('security');
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
	public function index($page='attendance')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_today_attendance']=$this->gs_model->my_total_absents($max_year,$user);
        $data['sessionuser']=$this->gs_model->fetch_session_user($user);
        $data['academicyear']=$this->gs_model->academic_year_filter();
        $data['schools']=$this->gs_model->fetch_school();
        $data['gradesec']=$this->gs_model->fetch_gradesec($max_year);
        $this->load->view('student/'.$page,$data);
	} 
}