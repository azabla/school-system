<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mylibrary extends CI_Controller {
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
	public function index($page='library')
	{
    if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');

    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $grade=$row_branch->grade;

    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
 
    $data['fetch_gradesec']=$this->main_model->fetch_myschool_gradesec($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['usertype']=$this->main_model->fetch_usertype();
    $data['library']=$this->main_model->fetch_myelibrary($grade);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('student/'.$page,$data);
	} 
}